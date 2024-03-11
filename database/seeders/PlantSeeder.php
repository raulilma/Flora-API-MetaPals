<?php

namespace Database\Seeders;

use App\Models\Plant;
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
                'biodiversity_attracting' => $faker->boolean(),
                'edible' => $faker->boolean(),
                'fragrant' => $faker->boolean(),
                'native_to_singapore' => $faker->boolean(),
                'coastal_and_marine' => $faker->boolean(),
                'freshwater' => $faker->boolean(),
                'terrestrial' => $faker->boolean(),
            ]);
        }
    }
}
