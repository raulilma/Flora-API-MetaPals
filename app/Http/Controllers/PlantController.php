<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlantController extends Controller
{
    public function index(Request $request)
    {
        // Determine the current page from the request
        $page = $request->input('page', 1);

        // Cache key for the plant index including page
        $cacheKey = 'all_plants_page_' . $page;

        // Check if data exists in cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Retrieve plants data with pagination
        $plants = Plant::paginate($request->per_page ?? 20);

        // Cache the data for future requests
        Cache::put($cacheKey, $plants, now()->addMinutes(10)); // Cache for 10 minutes

        return response()->json($plants);
    }

    public function show($id)
    {
        // Cache key for the individual plant
        $cacheKey = 'plant_' . $id;

        // Check if data exists in cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Retrieve plant data by ID
        $plant = Plant::findOrFail($id);

        // Cache the data for future requests
        Cache::put($cacheKey, $plant, now()->addMinutes(10)); // Cache for 10 minutes

        return response()->json($plant);
    }

    public function store(Request $request)
    {
        // Validation rules for plant creation
        $validatedData = $request->validate([
            'image_url' => 'required',
            'common_name' => 'required',
            'scientific_name' => 'required',
            'description' => 'required',
            'family' => 'required',
            'plant_division' => 'required',
            'plant_growth_form' => 'required',
            'lifespan' => 'required',
            'native_habitat' => 'required',
            'preferred_climate_zone' => 'required',
            'local_conservation_status' => 'required',
        ]);

        $plant = Plant::create($validatedData);

        // Clear the cache for all plants
        Cache::forget('all_plants');

        return response()->json($plant, 201);
    }

    public function update(Request $request, $id)
    {
        // Validation rules for plant update
        $validatedData = $request->validate([
            'image_url' => 'required',
            'common_name' => 'required',
            'scientific_name' => 'required',
            'description' => 'required',
            'family' => 'required',
            'plant_division' => 'required',
            'plant_growth_form' => 'required',
            'lifespan' => 'required',
            'native_habitat' => 'required',
            'preferred_climate_zone' => 'required',
            'local_conservation_status' => 'required',
        ]);

        $plant = Plant::findOrFail($id);
        $plant->update($validatedData);

        // Clear the cache for all plants
        Cache::forget('all_plants');

        return response()->json($plant, 200);
    }

    public function destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();

        // Clear the cache for all plants
        Cache::forget('all_plants');

        return response()->json(null, 204);
    }
}
