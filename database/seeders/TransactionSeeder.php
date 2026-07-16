<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $adminFee = 15000;
        $methods  = ['Cash', 'Debit', 'QRIS'];

        $medicalRecords = MedicalRecord::with('prescriptions', 'recordTreatments')
            ->whereDoesntHave('transaction')
            ->get();

        foreach ($medicalRecords as $mr) {
            $totalMedicine  = $mr->prescriptions->sum('subtotal');
            $totalTreatment = $mr->recordTreatments->sum('subtotal');
            $totalAmount    = $totalMedicine + $totalTreatment + $adminFee;

            $payment = $totalAmount + rand(0, 50000); // Kadang pas, kadang lebih

            Transaction::create([
                'medical_record_id' => $mr->id,
                'total_amount'      => $totalAmount,
                'payment_amount'    => $payment,
                'payment_method'    => $methods[array_rand($methods)],
                'status'            => 'Paid',
                'created_at'        => $mr->created_at, // Ikuti waktu MR agar kronologis
                'updated_at'        => $mr->updated_at,
            ]);
        }
    }
}
