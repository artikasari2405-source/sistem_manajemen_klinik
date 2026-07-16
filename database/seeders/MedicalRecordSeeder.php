<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;
use App\Models\Visit;
use App\Models\Medicine;
use App\Models\Treatment;
use App\Models\Prescription;
use App\Models\RecordTreatment;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $medicines  = Medicine::where('stock', '>', 0)->get();
        $treatments = Treatment::all();

        $symptoms  = ['Demam dan sakit kepala', 'Batuk dan pilek', 'Nyeri perut', 'Pusing dan mual', 'Sesak napas ringan'];
        $diagnoses = ['Gejala Tifus', 'ISPA', 'Gastritis', 'Vertigo', 'Bronkitis ringan'];
        $notes     = ['Istirahat cukup', 'Banyak minum air', 'Hindari makanan pedas', 'Cek ulang 3 hari lagi'];

        // Buat MR untuk semua kunjungan yang Done
        $visits = Visit::where('status', 'Done')->whereDoesntHave('medicalRecord')->get();

        foreach ($visits as $visit) {
            $idx = array_rand($symptoms);
            $mr  = MedicalRecord::create([
                'visit_id'  => $visit->id,
                'symptoms'  => $symptoms[$idx],
                'diagnosis' => $diagnoses[$idx],
                'notes'     => $notes[array_rand($notes)],
            ]);

            // Resep obat (1-2 item)
            $pickedMeds = $medicines->random(min(rand(1, 2), $medicines->count()));
            foreach ($pickedMeds as $med) {
                $qty = rand(1, 3);
                Prescription::create([
                    'medical_record_id' => $mr->id,
                    'medicine_id'       => $med->id,
                    'quantity'          => $qty,
                    'instructions'      => collect(['3x1 Sesudah makan', '2x1 Pagi & Malam', '1x1 Sebelum tidur'])->random(),
                    'subtotal'          => $med->price * $qty,
                ]);
                $med->decrement('stock', $qty);
                $med->refresh(); // update stok lokal agar tidak minus
                if ($med->stock <= 0) {
                    $medicines = $medicines->filter(fn($m) => $m->id !== $med->id);
                    if ($medicines->isEmpty()) break;
                }
            }

            // Tindakan medis (0-2 item)
            if ($treatments->isNotEmpty()) {
                $pickedTrt = $treatments->random(min(rand(0, 2), $treatments->count()));
                foreach ($pickedTrt as $trt) {
                    RecordTreatment::create([
                        'medical_record_id' => $mr->id,
                        'treatment_id'      => $trt->id,
                        'subtotal'          => $trt->price,
                    ]);
                }
            }
        }
    }
}
