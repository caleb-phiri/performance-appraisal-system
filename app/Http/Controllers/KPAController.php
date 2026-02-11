<?php

namespace App\Http\Controllers;

use App\Models\AppraisalKpa;
use App\Models\EmployeeRatingSupervisor;
use App\Models\Appraisal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KPAController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Rate a KPA (for single supervisor system)
     */
    public function rate(Request $request)
    {
        $request->validate([
            'kpa_id' => 'required|exists:appraisal_kpas,id',
            'supervisor_rating' => 'required|numeric|min:1',
            'supervisor_comments' => 'required|string|max:1000',
        ]);

        try {
            $kpa = AppraisalKpa::with('appraisal.user')->findOrFail($request->kpa_id);
            $appraisal = $kpa->appraisal;
            $supervisor = Auth::user();

            // Check authorization - single supervisor system
            $employee = $appraisal->user;
            if ($employee->manager_id !== $supervisor->employee_number) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to rate this employee'
                ], 403);
            }

            // Update KPA with single supervisor rating
            $kpa->update([
                'supervisor_rating' => $request->supervisor_rating,
                'supervisor_comments' => $request->supervisor_comments,
            ]);

            // Update appraisal scores
            $this->updateAppraisalScores($appraisal);

            return response()->json([
                'success' => true,
                'message' => 'KPA rated successfully',
                'rating' => $request->supervisor_rating,
                'comments' => $request->supervisor_comments
            ]);

        } catch (\Exception $e) {
            Log::error('KPA rating error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error rating KPA: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rate a KPA for multiple supervisors system
     */
    public function rateMultiple(Request $request)
    {
        $request->validate([
            'kpa_id' => 'required|exists:appraisal_kpas,id',
            'supervisor_rating' => 'required|numeric|min:1',
            'supervisor_comments' => 'required|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $kpa = AppraisalKpa::with('appraisal.user')->findOrFail($request->kpa_id);
            $appraisal = $kpa->appraisal;
            $supervisor = Auth::user();
            $employee = $appraisal->user;

            // Check authorization for multiple supervisors system
            $canRate = DB::table('employee_rating_supervisors')
                ->where('employee_number', $employee->employee_number)
                ->where('supervisor_id', $supervisor->employee_number)
                ->whereNull('kpa_id')
                ->exists();

            if (!$canRate) {
                throw new \Exception('You are not authorized to rate this KPA');
            }

            // Get the relationship details
            $relationship = DB::table('employee_rating_supervisors')
                ->where('employee_number', $employee->employee_number)
                ->where('supervisor_id', $supervisor->employee_number)
                ->whereNull('kpa_id')
                ->first();

            if (!$relationship) {
                throw new \Exception('Supervisor relationship not found');
            }

            // Check if rating already exists
            $existingRating = EmployeeRatingSupervisor::where('kpa_id', $kpa->id)
                ->where('supervisor_id', $supervisor->employee_number)
                ->first();

            if ($existingRating) {
                // UPDATE existing rating
                $existingRating->update([
                    'rating' => $request->supervisor_rating,
                    'comments' => $request->supervisor_comments,
                    'status' => 'completed',
                    'rated_at' => now(),
                ]);
                
                $message = 'Rating updated successfully';
            } else {
                // INSERT new rating - Use try-catch to handle unique constraint
                try {
                    EmployeeRatingSupervisor::create([
                        'kpa_id' => $kpa->id,
                        'supervisor_id' => $supervisor->employee_number,
                        'employee_number' => $employee->employee_number,
                        'relationship_type' => $relationship->relationship_type,
                        'rating_weight' => $relationship->rating_weight,
                        'is_primary' => $relationship->is_primary,
                        'can_view_appraisal' => $relationship->can_view_appraisal,
                        'can_approve_appraisal' => $relationship->can_approve_appraisal,
                        'rating' => $request->supervisor_rating,
                        'comments' => $request->supervisor_comments,
                        'status' => 'completed',
                        'rated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    // If unique constraint fails, try to update existing
                    $existing = EmployeeRatingSupervisor::where('kpa_id', $kpa->id)
                        ->where('supervisor_id', $supervisor->employee_number)
                        ->first();
                    
                    if ($existing) {
                        $existing->update([
                            'rating' => $request->supervisor_rating,
                            'comments' => $request->supervisor_comments,
                            'status' => 'completed',
                            'rated_at' => now(),
                        ]);
                        $message = 'Rating updated successfully';
                    } else {
                        throw $e; // Re-throw if no existing record found
                    }
                }
                
                if (!isset($message)) {
                    $message = 'KPA rated successfully';
                }
            }

            // Update KPA with weighted average
            $this->updateKPAAverageRating($kpa);

            // Update appraisal status and scores
            $this->updateAppraisalStatus($appraisal);
            $this->updateAppraisalScores($appraisal);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'rating' => $request->supervisor_rating,
                'weighted_average' => $kpa->getFinalSupervisorRatingAttribute(),
                'supervisor_name' => $supervisor->name,
                'is_update' => isset($existingRating),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Multiple KPA rating error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update KPA average rating based on all supervisor ratings
     */
    private function updateKPAAverageRating(AppraisalKpa $kpa)
    {
        // Get all completed ratings for this KPA
        $ratings = EmployeeRatingSupervisor::where('kpa_id', $kpa->id)
            ->where('status', 'completed')
            ->get();

        if ($ratings->isEmpty()) {
            $kpa->update(['supervisor_rating' => null]);
            return;
        }

        // Calculate weighted average
        $totalWeightedRating = 0;
        $totalWeight = 0;

        foreach ($ratings as $rating) {
            $weight = $rating->rating_weight ?? 100;
            $totalWeightedRating += ($rating->rating * $weight);
            $totalWeight += $weight;
        }

        $averageRating = $totalWeight > 0 ? $totalWeightedRating / $totalWeight : 0;

        // Update KPA
        $kpa->update([
            'supervisor_rating' => round($averageRating, 2),
            'supervised_at' => now(),
        ]);
    }

    /**
     * Update appraisal status based on rating completion
     */
    private function updateAppraisalStatus(Appraisal $appraisal)
    {
        $employee = $appraisal->user;
        
        // Get all rating supervisors for this employee
        $ratingSupervisors = DB::table('employee_rating_supervisors')
            ->where('employee_number', $employee->employee_number)
            ->whereNull('kpa_id')
            ->get();

        if ($ratingSupervisors->isEmpty()) {
            return;
        }

        // Check if all KPAs are rated by all supervisors
        $allRated = true;
        foreach ($appraisal->kpas as $kpa) {
            foreach ($ratingSupervisors as $supervisor) {
                $hasRated = EmployeeRatingSupervisor::where('kpa_id', $kpa->id)
                    ->where('supervisor_id', $supervisor->supervisor_id)
                    ->where('status', 'completed')
                    ->exists();
                    
                if (!$hasRated) {
                    $allRated = false;
                    break 2;
                }
            }
        }

        // Update status if all rated
        if ($allRated && $appraisal->status === 'submitted') {
            $appraisal->update(['status' => 'in_review']);
        }
    }

    /**
     * Update overall appraisal scores
     */
    private function updateAppraisalScores(Appraisal $appraisal)
    {
        $appraisal->load('kpas');
        
        $totalScore = 0;
        $supervisorScore = 0;
        
        foreach ($appraisal->kpas as $kpa) {
            // Use weighted average for multiple supervisors, or direct rating for single
            $finalRating = $kpa->getFinalSupervisorRatingAttribute();
            $kpi = $kpa->kpi ?? 4;
            
            if ($kpi > 0) {
                $score = ($finalRating / $kpi) * $kpa->weight;
                $totalScore += $score;
                $supervisorScore += $score;
            }
        }
        
        $appraisal->update([
            'supervisor_score' => round($supervisorScore, 2),
            'overall_score' => round($totalScore, 2),
        ]);
    }

    /**
     * Get KPA ratings for a specific KPA
     */
    public function getKpaRatings($kpaId)
    {
        try {
            $kpa = AppraisalKpa::findOrFail($kpaId);
            
            $ratings = EmployeeRatingSupervisor::where('kpa_id', $kpaId)
                ->where('status', 'completed')
                ->with('supervisor')
                ->get()
                ->map(function($rating) {
                    return [
                        'supervisor_id' => $rating->supervisor_id,
                        'supervisor_name' => $rating->supervisor->name ?? 'Unknown',
                        'rating' => $rating->rating,
                        'comments' => $rating->comments,
                        'rated_at' => $rating->rated_at ? $rating->rated_at->format('M d, Y H:i') : null,
                        'weight' => $rating->rating_weight,
                        'relationship_type' => $rating->relationship_type,
                        'is_primary' => (bool) $rating->is_primary,
                    ];
                });

            return response()->json([
                'success' => true,
                'ratings' => $ratings,
                'weighted_average' => $kpa->getFinalSupervisorRatingAttribute(),
                'total_ratings' => $ratings->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting KPA ratings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching ratings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rating completion statistics for an appraisal
     */
    public function getRatingStats($appraisalId)
    {
        try {
            $appraisal = Appraisal::with(['kpas', 'user'])->findOrFail($appraisalId);
            $employee = $appraisal->user;
            
            // Get rating supervisors
            $ratingSupervisors = DB::table('employee_rating_supervisors')
                ->where('employee_number', $employee->employee_number)
                ->whereNull('kpa_id')
                ->get();

            $stats = [
                'total_kpas' => $appraisal->kpas->count(),
                'rated_kpas' => 0,
                'completion_percentage' => 0,
                'supervisor_stats' => [],
                'weighted_total_score' => 0,
                'has_multiple_supervisors' => $ratingSupervisors->count() > 1,
            ];

            // Calculate supervisor statistics
            foreach ($ratingSupervisors as $supervisor) {
                $ratedCount = 0;
                $totalRating = 0;
                
                foreach ($appraisal->kpas as $kpa) {
                    $hasRated = EmployeeRatingSupervisor::where('kpa_id', $kpa->id)
                        ->where('supervisor_id', $supervisor->supervisor_id)
                        ->where('status', 'completed')
                        ->exists();
                        
                    if ($hasRated) {
                        $ratedCount++;
                        $rating = EmployeeRatingSupervisor::where('kpa_id', $kpa->id)
                            ->where('supervisor_id', $supervisor->supervisor_id)
                            ->first();
                        $totalRating += $rating->rating ?? 0;
                    }
                }
                
                $supervisorUser = \App\Models\User::where('employee_number', $supervisor->supervisor_id)->first();
                
                $stats['supervisor_stats'][] = [
                    'supervisor_id' => $supervisor->supervisor_id,
                    'supervisor_name' => $supervisorUser->name ?? 'Unknown',
                    'rated_kpas' => $ratedCount,
                    'total_kpas' => $appraisal->kpas->count(),
                    'average_rating' => $ratedCount > 0 ? round($totalRating / $ratedCount, 2) : 0,
                    'weight' => $supervisor->rating_weight,
                    'is_primary' => (bool) $supervisor->is_primary,
                    'relationship_type' => $supervisor->relationship_type,
                ];
            }

            // Calculate completion
            $totalPossible = $ratingSupervisors->count() * $appraisal->kpas->count();
            $completed = 0;
            
            foreach ($appraisal->kpas as $kpa) {
                $ratedCount = EmployeeRatingSupervisor::where('kpa_id', $kpa->id)
                    ->where('status', 'completed')
                    ->count();
                    
                if ($ratedCount > 0) {
                    $stats['rated_kpas']++;
                }
                $completed += $ratedCount;
            }
            
            $stats['completion_percentage'] = $totalPossible > 0 
                ? round(($completed / $totalPossible) * 100, 2) 
                : 0;
            
            // Calculate weighted total score
            $totalScore = 0;
            foreach ($appraisal->kpas as $kpa) {
                $totalScore += $kpa->getSupervisorWeightedScoreAttribute();
            }
            $stats['weighted_total_score'] = round($totalScore, 2);

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'total_supervisors' => $ratingSupervisors->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting rating stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a KPA rating
     */
    public function deleteRating($kpaId)
    {
        try {
            $supervisor = Auth::user();
            
            $rating = EmployeeRatingSupervisor::where('kpa_id', $kpaId)
                ->where('supervisor_id', $supervisor->employee_number)
                ->first();
            
            if (!$rating) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating not found'
                ], 404);
            }
            
            // Get KPA before deleting
            $kpa = AppraisalKpa::findOrFail($kpaId);
            
            DB::beginTransaction();
            
            // Delete the rating
            $rating->delete();
            
            // Recalculate average
            $this->updateKPAAverageRating($kpa);
            
            // Update appraisal scores
            $appraisal = $kpa->appraisal;
            $this->updateAppraisalScores($appraisal);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Rating deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting rating: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting rating: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending ratings for current supervisor
     */
    public function getPendingRatings()
    {
        try {
            $supervisor = Auth::user();
            
            $pendingRatings = EmployeeRatingSupervisor::where('supervisor_id', $supervisor->employee_number)
                ->where('status', 'pending')
                ->with(['kpa.appraisal.user'])
                ->get()
                ->map(function($rating) {
                    return [
                        'id' => $rating->id,
                        'kpa_id' => $rating->kpa_id,
                        'kpa_name' => $rating->kpa->kpa ?? 'N/A',
                        'category' => $rating->kpa->category ?? 'N/A',
                        'employee_name' => $rating->kpa->appraisal->user->name ?? 'N/A',
                        'employee_number' => $rating->kpa->appraisal->employee_number ?? 'N/A',
                        'appraisal_period' => $rating->kpa->appraisal->period ?? 'N/A',
                        'created_at' => $rating->created_at->format('M d, Y'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'ratings' => $pendingRatings,
                'count' => $pendingRatings->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting pending ratings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending ratings: ' . $e->getMessage()
            ], 500);
        }
    }
}