<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appraisal;
use App\Models\KPA;

class FormController extends Controller
{
    /**
     * Display the forms dashboard
     */
    public function dashboard()
    {
        return view('forms.dashboard');
    }

    /**
     * Display individual forms (based on your actual file names)
     */
    public function plazaManager()
    {
        return view('appraisal.Plaza_Manager');
    }

    public function edit()
    {
        return view('forms.edit');
    }

    public function emTechnician()
    {
        return view('forms.E&M');
    }

    public function adminClerk()
    {
        return view('forms.admin-clerk');
    }

    public function shiftManager()
    {
        return view('forms.shift-manager');
    }

    public function seniorTollCollector()
    {
        return view('forms.senior-toll-collector-form');
    }

    public function tollCollector()
    {
        return view('forms.toll-collector-form');
    }

    public function tce()
    {
        return view('forms.TCE');
    }

    public function routePatrolDriver()
    {
        return view('forms.route-patrol-driver-form');
    }

    public function plazaAttendant()
    {
        return view('forms.plaza-attendant-form');
    }

    public function laneAttendant()
    {
        return view('forms.lane-attendant-form');
    }

    public function hrAssistant()
    {
        return view('forms.hr-assistant-form');
    }

    /**
     * Store appraisal data (common for all forms)
     */
    public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'period' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'job_title' => 'required|string',
            'status' => 'required|string',
            'development_needs' => 'nullable|string',
            'employee_comments' => 'nullable|string',
            'kpas' => 'required|array',
            'kpas.*.category' => 'required|string',
            'kpas.*.kpa' => 'required|string',
            'kpas.*.result_indicators' => 'required|string',
            'kpas.*.kpi' => 'required|integer',
            'kpas.*.weight' => 'required|numeric|min:0|max:100',
            'kpas.*.self_rating' => 'required|integer|min:1|max:4',
            'kpas.*.comments' => 'nullable|string',
        ]);

        // Calculate total weight
        $totalWeight = collect($request->kpas)->sum('weight');
        
        if ($totalWeight != 100 && $request->status == 'submitted') {
            return back()->withErrors(['weight' => 'Total weight must equal 100% before submitting.'])->withInput();
        }

        try {
            // Create appraisal record
            $appraisal = Appraisal::create([
                'user_id' => auth()->id(),
                'period' => $request->period,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'job_title' => $request->job_title,
                'status' => $request->status,
                'development_needs' => $request->development_needs,
                'employee_comments' => $request->employee_comments,
                'total_weight' => $totalWeight,
                'self_total_score' => $this->calculateTotalScore($request->kpas),
            ]);

            // Create KPA records
            foreach ($request->kpas as $kpaData) {
                KPA::create([
                    'appraisal_id' => $appraisal->id,
                    'category' => $kpaData['category'],
                    'kpa' => $kpaData['kpa'],
                    'result_indicators' => $kpaData['result_indicators'],
                    'kpi' => $kpaData['kpi'],
                    'weight' => $kpaData['weight'],
                    'self_rating' => $kpaData['self_rating'],
                    'comments' => $kpaData['comments'],
                ]);
            }

            // Redirect based on status
            if ($request->status == 'submitted') {
                return redirect()->route('forms.dashboard')->with('success', 'Appraisal submitted successfully!');
            } else {
                return redirect()->route('forms.dashboard')->with('info', 'Appraisal saved as draft.');
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while saving the appraisal.'])->withInput();
        }
    }

    /**
     * Calculate total score from KPAs
     */
    private function calculateTotalScore($kpas)
    {
        $totalScore = 0;
        foreach ($kpas as $kpa) {
            $totalScore += $kpa['self_rating'];
        }
        return $totalScore;
    }
}