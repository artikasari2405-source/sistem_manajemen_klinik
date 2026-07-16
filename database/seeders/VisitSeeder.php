<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Visit;
use App\Models\User;
use App\Models\Patient;
use Carbon\Carbon;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor = User::where('role', 'Dokter')->first();
        $patients = Patient::inRandomOrder()->limit(5)->get();
        
        $queue = 1;
        foreach($patients as $patient) {
            Visit::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'visit_date' => Carbon::today(),
                'queue_number' => $queue++,
                'status' => collect(['Waiting', 'Examining', 'Done', 'Cancelled'])->random(),
            ]);
        }
    }
}
