<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class PerformanceDistributionService
{
    /**
     * Get the distribution caps based on the policy
     * Rating 5 (Outstanding): ≤ 10%
     * Rating 4 (Excellent): ≤ 20%
     * Rating 3 (Competent): ≤ 40%
     * Rating 2 (Below Average): ≤ 20%
     * Rating 1 (Unsatisfactory): ≤ 10%
     */
    public function getDistributionCaps()
    {
        return [
            5 => ['name' => 'Outstanding', 'cap' => 10, 'bonus' => 2.0],
            4 => ['name' => 'Excellent', 'cap' => 20, 'bonus' => 1.5],
            3 => ['name' => 'Competent', 'cap' => 40, 'bonus' => 1.0],
            2 => ['name' => 'Below Average', 'cap' => 20, 'bonus' => 0.5],
            1 => ['name' => 'Unsatisfactory', 'cap' => 10, 'bonus' => 0],
        ];
    }

    /**
     * Calculate the maximum number of employees allowed per rating for a team
     */
    public function calculateRatingLimits($teamSize)
    {
        $caps = $this->getDistributionCaps();
        $limits = [];
        
        foreach ($caps as $rating => $data) {
            $limits[$rating] = [
                'max' => ceil(($data['cap'] / 100) * $teamSize),
                'cap_percentage' => $data['cap'],
                'name' => $data['name'],
                'bonus' => $data['bonus']
            ];
        }
        
        return $limits;
    }

    /**
     * Get team size by workstation
     * This determines the denominator for distribution calculations
     */
    public function getTeamSizeByWorkstation($workstation = null, $year = null, $quarter = null)
    {
        $query = User::where('is_onboarded', true);
        
        // Apply workstation filter to determine team
        if ($workstation && $workstation !== '') {
            if ($workstation === 'HQ') {
                $query->where('workstation_type', 'hq');
            } else {
                // Specific toll plaza
                $plazaCode = $this->getTollPlazaCode($workstation);
                $query->where('workstation_type', 'toll_plaza')
                    ->where('toll_plaza', $plazaCode);
            }
        } else {
            // If no specific workstation, consider entire organization as one team
            // Or you can group by workstation - this depends on business logic
            return $query->count();
        }
        
        return $query->count();
    }

    /**
     * Get team size grouped by workstation
     * Returns array of team sizes per workstation
     */
    public function getAllTeamSizes($year = null)
    {
        $teamSizes = [];
        
        // Get HQ team
        $hqCount = User::where('is_onboarded', true)
            ->where('workstation_type', 'hq')
            ->count();
        $teamSizes['HQ'] = $hqCount;
        
        // Get Toll Plazas
        $plazas = [
            'TP-001' => 'Kafulafuta Toll Plaza',
            'TP-002' => 'Abram Zayoni Mokola Toll Plaza',
            'TP-003' => 'Katuba Toll Plaza',
            'TP-004' => 'Manyumbi Toll Plaza',
            'TP-005' => 'Konkola Toll Plaza'
        ];
        
        foreach ($plazas as $code => $name) {
            $plazaCount = User::where('is_onboarded', true)
                ->where('workstation_type', 'toll_plaza')
                ->where('toll_plaza', $code)
                ->count();
            $teamSizes[$name] = $plazaCount;
        }
        
        return $teamSizes;
    }

    /**
     * Get toll plaza code from name
     */
    private function getTollPlazaCode($name)
    {
        $plazas = [
            'Kafulafuta Toll Plaza' => 'TP-001',
            'Abram Zayoni Mokola Toll Plaza' => 'TP-002',
            'Katuba Toll Plaza' => 'TP-003',
            'Manyumbi Toll Plaza' => 'TP-004',
            'Konkola Toll Plaza' => 'TP-005'
        ];
        
        return $plazas[$name] ?? $name;
    }

    /**
     * Calculate distribution compliance for a team
     * Checks if the current rating distribution follows the policy caps
     */
    public function checkDistributionCompliance($ratingsCount, $teamSize)
    {
        $limits = $this->calculateRatingLimits($teamSize);
        $compliance = [];
        $warnings = [];
        $isCompliant = true;
        
        foreach ($limits as $rating => $limit) {
            $current = $ratingsCount[$rating] ?? 0;
            $maxAllowed = $limit['max'];
            $compliant = $current <= $maxAllowed;
            
            $compliance[$rating] = [
                'current' => $current,
                'max_allowed' => $maxAllowed,
                'cap_percentage' => $limit['cap_percentage'],
                'compliant' => $compliant,
                'excess' => $compliant ? 0 : $current - $maxAllowed
            ];
            
            if (!$compliant) {
                $isCompliant = false;
                $warnings[] = "Rating {$rating} ({$limit['name']}): {$current} employees exceed the cap of {$maxAllowed} ({$limit['cap_percentage']}% of {$teamSize})";
            }
        }
        
        return [
            'is_compliant' => $isCompliant,
            'compliance' => $compliance,
            'warnings' => $warnings,
            'team_size' => $teamSize,
            'limits' => $limits
        ];
    }

    /**
     * Calculate recommended rating adjustments to meet distribution caps
     */
    public function getRecommendedAdjustments($ratingsCount, $teamSize)
    {
        $limits = $this->calculateRatingLimits($teamSize);
        $recommendations = [];
        $excess = [];
        $deficit = [];
        
        foreach ($limits as $rating => $limit) {
            $current = $ratingsCount[$rating] ?? 0;
            $maxAllowed = $limit['max'];
            
            if ($current > $maxAllowed) {
                $excess[$rating] = [
                    'current' => $current,
                    'max' => $maxAllowed,
                    'excess' => $current - $maxAllowed,
                    'rating_name' => $limit['name']
                ];
            } else {
                $deficit[$rating] = [
                    'current' => $current,
                    'max' => $maxAllowed,
                    'available' => $maxAllowed - $current,
                    'rating_name' => $limit['name']
                ];
            }
        }
        
        // Generate recommendations
        if (!empty($excess)) {
            $recommendations[] = "The following ratings exceed the distribution caps:";
            foreach ($excess as $rating => $data) {
                $recommendations[] = "  • Rating {$rating} ({$data['rating_name']}): Reduce by {$data['excess']} employee(s)";
            }
        }
        
        return [
            'excess' => $excess,
            'deficit' => $deficit,
            'recommendations' => $recommendations
        ];
    }

    /**
     * Get rating name based on score
     */
    public function getRatingFromScore($score)
    {
        if ($score >= 90) return 5;
        if ($score >= 80) return 4;
        if ($score >= 70) return 3;
        if ($score >= 60) return 2;
        return 1;
    }

    /**
     * Get rating details from score
     */
    public function getRatingDetails($score)
    {
        $rating = $this->getRatingFromScore($score);
        $caps = $this->getDistributionCaps();
        
        return [
            'rating' => $rating,
            'name' => $caps[$rating]['name'],
            'bonus_multiplier' => $caps[$rating]['bonus'],
            'cap_percentage' => $caps[$rating]['cap']
        ];
    }
}