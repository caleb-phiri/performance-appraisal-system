<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\KpaTemplate;
use App\Models\AppraisalPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@appraisal.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'job_title' => 'System Administrator',
            'toll_plaza' => 'Head Office',
        ]);

        // Create Manager
        $manager = User::create([
            'name' => 'John Manager',
            'email' => 'manager@appraisal.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'job_title' => 'Operations Manager',
            'toll_plaza' => 'Main Plaza',
        ]);

        // Create Employees
        User::create([
            'name' => 'Alice Employee',
            'email' => 'alice@appraisal.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'job_title' => 'Toll Collector',
            'toll_plaza' => 'Main Plaza',
            'manager_id' => $manager->id,
        ]);

        User::create([
            'name' => 'Bob Employee',
            'email' => 'bob@appraisal.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'job_title' => 'Shift Supervisor',
            'toll_plaza' => 'North Plaza',
            'manager_id' => $manager->id,
        ]);

        // Create Appraisal Period
        AppraisalPeriod::create([
            'name' => 'Q1 2024',
            'start_date' => '2024-01-01',
            'end_date' => '2024-03-31',
            'is_active' => true,
            'created_by' => $manager->id,
        ]);

        // Create KPA Templates
        $kpas = [
            [
                'kpa' => 'Revenue Collection',
                'kpi_description' => 'Accuracy in toll collection and cash handling',
                'rating_standard' => '1: Poor (Many errors), 2: Fair (Some errors), 3: Good (Minimal errors), 4: Excellent (No errors)',
                'weight' => 25.00,
                'category' => 'Core',
                'sort_order' => 1,
                'created_by' => $manager->id,
            ],
            [
                'kpa' => 'Customer Service',
                'kpi_description' => 'Quality of interaction with customers',
                'rating_standard' => '1: Poor (Complaints), 2: Fair (Needs improvement), 3: Good (Satisfactory), 4: Excellent (Exceeds expectations)',
                'weight' => 20.00,
                'category' => 'Core',
                'sort_order' => 2,
                'created_by' => $manager->id,
            ],
            [
                'kpa' => 'Attendance & Punctuality',
                'kpi_description' => 'Regularity and timeliness',
                'rating_standard' => '1: Poor (Multiple issues), 2: Fair (Occasional issues), 3: Good (Rarely late), 4: Excellent (Always on time)',
                'weight' => 15.00,
                'category' => 'Behavioral',
                'sort_order' => 3,
                'created_by' => $manager->id,
            ],
            [
                'kpa' => 'Safety Compliance',
                'kpi_description' => 'Adherence to safety protocols',
                'rating_standard' => '1: Poor (Non-compliant), 2: Fair (Sometimes compliant), 3: Good (Usually compliant), 4: Excellent (Always compliant)',
                'weight' => 20.00,
                'category' => 'Core',
                'sort_order' => 4,
                'created_by' => $manager->id,
            ],
            [
                'kpa' => 'Teamwork',
                'kpi_description' => 'Cooperation with colleagues',
                'rating_standard' => '1: Poor (Uncooperative), 2: Fair (Limited cooperation), 3: Good (Good team player), 4: Excellent (Excellent collaborator)',
                'weight' => 10.00,
                'category' => 'Behavioral',
                'sort_order' => 5,
                'created_by' => $manager->id,
            ],
            [
                'kpa' => 'Equipment Maintenance',
                'kpi_description' => 'Care and reporting of equipment issues',
                'rating_standard' => '1: Poor (Negligent), 2: Fair (Sometimes reports), 3: Good (Usually reports), 4: Excellent (Proactive in maintenance)',
                'weight' => 10.00,
                'category' => 'Technical',
                'sort_order' => 6,
                'created_by' => $manager->id,
            ],
        ];

        foreach ($kpas as $kpa) {
            KpaTemplate::create($kpa);
        }
    }
}
