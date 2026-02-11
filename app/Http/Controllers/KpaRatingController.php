<?php

namespace App\Http\Controllers;

use App\Models\Kpa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KpaRatingController extends Controller
{
    /**
     * Rate a KPA (for single supervisor)
     */
    public function rateKPA(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'kpa_id' => 'required|exists:kpas,id',
                'supervisor_rating' => 'required|numeric|min:1|max:5',
                'supervisor_comments' => 'nullable|string',
                'agree_with_self_rating' => 'nullable|boolean',
            ]);

            $kpa = Kpa::with('appraisal')->findOrFail($request->kpa_id);
            
            // Check if current user is authorized to rate this KPA
            if (!Auth::user()->canRateEmployee($kpa->appraisal->employee_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to rate this employee.',
                ], 403);
            }

            // Check if appraisal is in submitted status
            if ($kpa->appraisal->status !== 'submitted') {
                return response()->json([
                    'success' => false,
                    'message' => 'This appraisal is not in a rateable status.',
                ], 400);
            }

            // Handle agree with self-rating option
            if ($request->has('agree_with_self_rating') && $request->agree_with_self_rating) {
                $validated['supervisor_rating'] = $kpa->self_rating;
                $validated['supervisor_comments'] = 'Agreed with employee self-rating.';
            }

            // Check if rating already exists for this supervisor
            $existingRating = DB::table('employee_rating_supervisors')
                ->where('kpa_id', $request->kpa_id)
                ->where('supervisor_id', Auth::user()->employee_number)
                ->where('employee_number', $kpa->appraisal->employee_number)
                ->first();

            if ($existingRating) {
                // Update existing rating
                DB::table('employee_rating_supervisors')
                    ->where('id', $existingRating->id)
                    ->update([
                        'rating' => $validated['supervisor_rating'],
                        'comments' => $validated['supervisor_comments'] ?? null,
                        'status' => 'completed',
                        'rated_at' => now(),
                        'updated_at' => now(),
                    ]);
                
                // Update KPA supervisor rating for single supervisor case
                $this->updateKpaSupervisorRating($kpa);
                
                return response()->json([
                    'success' => true,
                    'message' => 'KPA rating updated successfully',
                ]);
            } else {
                // Get relationship data from pivot table
                $relationship = DB::table('employee_rating_supervisors')
                    ->whereNull('kpa_id') // Get relationship record
                    ->where('supervisor_id', Auth::user()->employee_number)
                    ->where('employee_number', $kpa->appraisal->employee_number)
                    ->first();

                // Insert new rating
                DB::table('employee_rating_supervisors')->insert([
                    'kpa_id' => $request->kpa_id,
                    'supervisor_id' => Auth::user()->employee_number,
                    'employee_number' => $kpa->appraisal->employee_number,
                    'relationship_type' => $relationship->relationship_type ?? 'direct',
                    'rating_weight' => $relationship->rating_weight ?? 100,
                    'is_primary' => $relationship->is_primary ?? false,
                    'can_view_appraisal' => $relationship->can_view_appraisal ?? true,
                    'can_approve_appraisal' => $relationship->can_approve_appraisal ?? true,
                    'rating' => $validated['supervisor_rating'],
                    'comments' => $validated['supervisor_comments'] ?? null,
                    'status' => 'completed',
                    'rated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Update KPA supervisor rating for single supervisor case
                $this->updateKpaSupervisorRating($kpa);
                
                return response()->json([
                    'success' => true,
                    'message' => 'KPA rated successfully',
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('KPA Rating Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'user' => Auth::user()->employee_number ?? 'unknown',
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while rating the KPA. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Rate a KPA for multiple supervisors
     */
    public function rateKpaMultiple(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'kpa_id' => 'required|exists:kpas,id',
                'supervisor_rating' => 'required|numeric|min:1|max:5',
                'supervisor_comments' => 'nullable|string',
            ]);

            $kpa = Kpa::with('appraisal')->findOrFail($request->kpa_id);
            
            // Check if current user is authorized to rate this KPA
            if (!Auth::user()->canRateEmployee($kpa->appraisal->employee_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to rate this employee.',
                ], 403);
            }

            // Check if appraisal is in submitted status
            if ($kpa->appraisal->status !== 'submitted') {
                return response()->json([
                    'success' => false,
                    'message' => 'This appraisal is not in a rateable status.',
                ], 400);
            }

            // Check if rating already exists for this supervisor
            $existingRating = DB::table('employee_rating_supervisors')
                ->where('kpa_id', $request->kpa_id)
                ->where('supervisor_id', Auth::user()->employee_number)
                ->where('employee_number', $kpa->appraisal->employee_number)
                ->first();

            if ($existingRating) {
                // Update existing rating
                DB::table('employee_rating_supervisors')
                    ->where('id', $existingRating->id)
                    ->update([
                        'rating' => $validated['supervisor_rating'],
                        'comments' => $validated['supervisor_comments'] ?? null,
                        'status' => 'completed',
                        'rated_at' => now(),
                        'updated_at' => now(),
                    ]);
                
                // Update KPA final rating
                $this->updateKpaFinalRating($kpa);
                
                return response()->json([
                    'success' => true,
                    'message' => 'KPA rating updated successfully',
                ]);
            } else {
                // Get relationship data from pivot table
                $relationship = DB::table('employee_rating_supervisors')
                    ->whereNull('kpa_id') // Get relationship record
                    ->where('supervisor_id', Auth::user()->employee_number)
                    ->where('employee_number', $kpa->appraisal->employee_number)
                    ->first();

                if (!$relationship) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No relationship found between you and this employee.',
                    ], 400);
                }

                // Insert new rating
                DB::table('employee_rating_supervisors')->insert([
                    'kpa_id' => $request->kpa_id,
                    'supervisor_id' => Auth::user()->employee_number,
                    'employee_number' => $kpa->appraisal->employee_number,
                    'relationship_type' => $relationship->relationship_type ?? 'direct',
                    'rating_weight' => $relationship->rating_weight ?? 100,
                    'is_primary' => $relationship->is_primary ?? false,
                    'can_view_appraisal' => $relationship->can_view_appraisal ?? true,
                    'can_approve_appraisal' => $relationship->can_approve_appraisal ?? true,
                    'rating' => $validated['supervisor_rating'],
                    'comments' => $validated['supervisor_comments'] ?? null,
                    'status' => 'completed',
                    'rated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Update KPA final rating
                $this->updateKpaFinalRating($kpa);
                
                return response()->json([
                    'success' => true,
                    'message' => 'KPA rated successfully',
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('KPA Rating Multiple Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'user' => Auth::user()->employee_number ?? 'unknown',
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while rating the KPA. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get existing ratings for a KPA
     */
    public function getKpaRatings($kpaId)
    {
        try {
            $kpa = Kpa::findOrFail($kpaId);
            
            $ratings = DB::table('employee_rating_supervisors')
                ->where('kpa_id', $kpaId)
                ->whereNotNull('rating')
                ->join('users', 'employee_rating_supervisors.supervisor_id', '=', 'users.employee_number')
                ->select(
                    'employee_rating_supervisors.*',
                    'users.name as supervisor_name',
                    'users.email as supervisor_email'
                )
                ->get();
            
            return response()->json([
                'success' => true,
                'ratings' => $ratings,
            ]);
        } catch (\Exception $e) {
            \Log::error('Get KPA Ratings Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load ratings',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update KPA supervisor rating for single supervisor case
     */
    private function updateKpaSupervisorRating($kpa)
    {
        // For single supervisor, update the KPA directly
        $latestRating = DB::table('employee_rating_supervisors')
            ->where('kpa_id', $kpa->id)
            ->whereNotNull('rating')
            ->latest('rated_at')
            ->first();
        
        if ($latestRating) {
            $kpa->update([
                'supervisor_rating' => $latestRating->rating,
                'supervisor_comments' => $latestRating->comments,
                'supervisor_rated_at' => $latestRating->rated_at,
            ]);
        }
    }

    /**
     * Calculate and update final rating for multiple supervisors
     */
    private function updateKpaFinalRating($kpa)
    {
        // Get all ratings for this KPA
        $ratings = DB::table('employee_rating_supervisors')
            ->where('kpa_id', $kpa->id)
            ->whereNotNull('rating')
            ->get();

        if ($ratings->isEmpty()) {
            return;
        }

        // Calculate weighted average
        $totalWeight = $ratings->sum('rating_weight');
        $weightedSum = 0;
        
        foreach ($ratings as $rating) {
            $weight = $rating->rating_weight ?? 100;
            $weightedSum += $rating->rating * ($weight / $totalWeight);
        }
        
        $finalRating = round($weightedSum, 2);
        
        // Update KPA with final rating
        $kpa->update([
            'final_supervisor_rating' => $finalRating,
        ]);
    }

    /**
     * Delete a KPA rating
     */
    public function deleteRating(Request $request)
    {
        try {
            $validated = $request->validate([
                'kpa_id' => 'required|exists:kpas,id',
            ]);

            $deleted = DB::table('employee_rating_supervisors')
                ->where('kpa_id', $validated['kpa_id'])
                ->where('supervisor_id', Auth::user()->employee_number)
                ->whereNotNull('rating')
                ->delete();

            if ($deleted) {
                $kpa = Kpa::findOrFail($validated['kpa_id']);
                
                // Recalculate if multiple supervisors
                $employee = $kpa->appraisal->user;
                if ($employee->ratingSupervisors->count() > 1) {
                    $this->updateKpaFinalRating($kpa);
                } else {
                    $kpa->update([
                        'supervisor_rating' => null,
                        'supervisor_comments' => null,
                        'supervisor_rated_at' => null,
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Rating deleted successfully',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No rating found to delete',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Delete Rating Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete rating',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}