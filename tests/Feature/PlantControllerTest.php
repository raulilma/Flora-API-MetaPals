<?php

namespace Tests\Feature;

use App\Models\Plant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class PlantControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method.
     *
     * @return void
     */
    public function testIndex()
    {
        Plant::factory()->count(5)->create();

        $response = $this->get('/api/v1/plants');

        $response->assertStatus(Response::HTTP_OK)
         ->assertJsonStructure([
             'data' => [
                 'current_page',
                 'data' => [
                     '*' => [
                         'id',
                         'image_url',
                         'common_name',
                         'scientific_name',
                         'description',
                         'family',
                         'plant_division',
                         'plant_growth_form',
                         'lifespan',
                         'native_habitat',
                         'preferred_climate_zone',
                         'local_conservation_status',
                         'biodiversity_attracting',
                         'edible',
                         'fragrant',
                         'native_to_singapore',
                         'coastal_and_marine',
                         'freshwater',
                         'terrestrial',
                         'created_at',
                         'updated_at',
                     ],
                 ],
                 'first_page_url',
                 'from',
                 'last_page',
                 'last_page_url',
                 'links' => [
                     '*' => [
                         'url',
                         'label',
                         'active',
                     ],
                 ],
                 'next_page_url',
                 'path',
                 'per_page',
                 'prev_page_url',
                 'to',
                 'total',
             ],
             'cache_key',
         ]);
    }

    /**
     * Test show method.
     *
     * @return void
     */
    public function testShow()
    {
        $plant = Plant::factory()->create();

        $response = $this->get("/api/v1/plants/{$plant->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'image_url',
                'common_name',
                'scientific_name',
                'description',
                'family',
                'plant_division',
                'plant_growth_form',
                'lifespan',
                'native_habitat',
                'preferred_climate_zone',
                'local_conservation_status',
                'biodiversity_attracting',
                'edible',
                'fragrant',
                'native_to_singapore',
                'coastal_and_marine',
                'freshwater',
                'terrestrial',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test store method.
     *
     * @return void
     */
    public function testStore()
    {
        $plantData = Plant::factory()->make()->toArray();

        $response = $this->post('/api/v1/plants', $plantData);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Test update method.
     *
     * @return void
     */
    public function testUpdate()
    {
        $plant = Plant::factory()->create();
        $updatedData = Plant::factory()->make()->toArray();

        $response = $this->put("/api/v1/plants/update/{$plant->id}", $updatedData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment($updatedData);
    }

    /**
     * Test destroy method.
     *
     * @return void
     */
    public function testDestroy()
    {
        $plant = Plant::factory()->create();

        $response = $this->delete("/api/v1/plants/{$plant->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('plants', ['id' => $plant->id]);
    }
}
