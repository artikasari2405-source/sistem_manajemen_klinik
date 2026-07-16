<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Treatment;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treatments = [
            ['name' => 'Konsultasi Dokter Umum', 'price' => 50000],
            ['name' => 'Pembersihan Luka', 'price' => 75000],
            ['name' => 'Jahit Luka Kecil', 'price' => 150000],
            ['name' => 'Suntik Vitamin', 'price' => 100000],
            ['name' => 'Cek Gula Darah', 'price' => 30000],
            ['name' => 'Cek Kolesterol', 'price' => 40000],
            ['name' => 'Cek Asam Urat', 'price' => 35000],
            ['name' => 'Nebulizer', 'price' => 120000],
            ['name' => 'EKG (Rekam Jantung)', 'price' => 200000],
            ['name' => 'Konsultasi Dokter Spesialis', 'price' => 150000],
        ];

        foreach ($treatments as $treatment) {
            Treatment::create($treatment);
        }
    }
}
