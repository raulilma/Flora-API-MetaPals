<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Generate 5700 flora entries
        for ($i = 0; $i < 5700; $i++) {
            Plant::create([
                'image_url' => $faker->imageUrl(),
                'common_name' => $faker->word,
                'scientific_name' => $faker->word,
                'description' => $faker->sentence,
                'family' => $faker->word,
                'plant_division' => $faker->word,
                'plant_growth_form' => $faker->word,
                'lifespan' => $faker->word,
                'native_habitat' => $faker->word,
                'preferred_climate_zone' => $faker->word,
                'local_conservation_status' => $faker->word,
            ]);
        }
    }
}
