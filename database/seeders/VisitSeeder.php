<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;
use App\Models\User;
use App\Models\Patient;
use Carbon\Carbon;

class VisitSeeder extends Seeder
{
    public function run(): void
    {
        $doctors  = User::where('role', 'Dokter')->get();
        $patients = Patient::all();

        // Generate 30 hari ke belakang, tiap hari 1-4 kunjungan
        for ($daysAgo = 30; $daysAgo >= 0; $daysAgo--) {
            $date    = Carbon::today()->subDays($daysAgo);
            $perDay  = rand(1, 4);
            $queue   = 1;

            for ($i = 0; $i < $perDay; $i++) {
                $doctor  = $doctors->random();
                $patient = $patients->random();

                Visit::create([
                    'patient_id'   => $patient->id,
                    'doctor_id'    => $doctor->id,
                    'visit_date'   => $date,
                    'queue_number' => $queue++,
                    'status'       => 'Done',
                ]);
            }
        }

        // Tambahkan beberapa kunjungan hari ini yang belum selesai (Waiting/Examining)
        $today  = Carbon::today();
        $doctor = $doctors->first();
        $queue  = 10;
        foreach ($patients->random(3) as $patient) {
            Visit::create([
                'patient_id'   => $patient->id,
                'doctor_id'    => $doctor->id,
                'visit_date'   => $today,
                'queue_number' => $queue++,
                'status'       => collect(['Waiting', 'Examining'])->random(),
            ]);
        }
    }
}
