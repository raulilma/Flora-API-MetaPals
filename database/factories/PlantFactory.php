<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PlantFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create();
        return [
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
        ];
    }
}
