<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studentCount = DB::table('students')->get()->count();
        $gpaList = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C', 'D', 'F'];
        if ($studentCount) {
            for ($i = 1; $i <= $studentCount; $i++) {
                DB::table('results')->insert([
                    'student_id' => $i,
                    'gpa' => $gpaList[rand(0, 8)],
                    'date' => now(),
                    'created_at' => now()
                ]);
            }
        }
    }
}
