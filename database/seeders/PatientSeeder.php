<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Patient;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        for($i = 0; $i < 20; $i++) {
            Patient::create([
                'rm_number' => 'RM-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'gender' => $faker->randomElement(['L', 'P']),
                'birth_date' => $faker->date('Y-m-d', '2010-01-01'),
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
            ]);
        }
    }
}
