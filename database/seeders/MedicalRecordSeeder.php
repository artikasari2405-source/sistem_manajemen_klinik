<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\MedicalRecord;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Treatment;
use App\Models\Prescription;
use App\Models\RecordTreatment;
use Carbon\Carbon;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $doctor = User::where('role', 'Dokter')->first();
        $patients = Patient::inRandomOrder()->limit(3)->get();
        
        foreach($patients as $patient) {
            // Buat Visit masa lalu
            $visit = Visit::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'visit_date' => Carbon::now()->subDays(rand(1, 10)),
                'queue_number' => rand(1, 10),
                'status' => 'Done',
            ]);

            // Buat Medical Record
            $mr = MedicalRecord::create([
                'visit_id' => $visit->id,
                'symptoms' => 'Demam dan sakit kepala selama 3 hari',
                'diagnosis' => 'Gejala Tifus',
                'notes' => 'Istirahat yang cukup',
            ]);

            // Attach Medicine
            $med = Medicine::first();
            if ($med && $med->stock > 0) {
                Prescription::create([
                    'medical_record_id' => $mr->id,
                    'medicine_id' => $med->id,
                    'quantity' => 1,
                    'instructions' => '3x1 Sesudah makan',
                    'subtotal' => $med->price * 1,
                ]);
                $med->decrement('stock', 1);
            }

            // Attach Treatment
            $trt = Treatment::first();
            if ($trt) {
                RecordTreatment::create([
                    'medical_record_id' => $mr->id,
                    'treatment_id' => $trt->id,
                    'subtotal' => $trt->price,
                ]);
            }
        }
    }
}
