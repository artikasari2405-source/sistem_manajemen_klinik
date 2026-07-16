<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Medicine;
use Faker\Factory as Faker;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        for($i = 0; $i < 20; $i++) {
            Medicine::create([
                'code' => 'MED-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'name' => 'Obat ' . ucfirst($faker->word),
                'stock' => $faker->numberBetween(10, 100),
                'price' => $faker->numberBetween(10, 200) * 500,
            ]);
        }
    }
}
