<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class KpaSeeder extends Seeder
{
public function run()
{
// Example: store standard KPA template table for later use
$kpas = [
['kpa' => 'ATTENDANCE', 'kpi' => 'Attended work on time...', 'weight' => 10],
['kpa' => 'CODE OF CONDUCT', 'kpi' => 'Perform well... no disciplinary record', 'weight' => 25],
['kpa' => 'WORK PERFORMANCE', 'kpi' => 'Complete deposit of toll revenue...', 'weight' => 10],
// add more rows as needed to match your form
];


foreach ($kpas as $row) {
DB::table('kpa_templates')->insert([ // you can create kpa_templates migration if you want
'kpa' => $row['kpa'],
'kpi' => $row['kpi'],
'weight' => $row['weight'],
'created_at' => now(),
'updated_at' => now(),
]);
}
}
}