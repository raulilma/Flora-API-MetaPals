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

        // Cache key for the plant index including page and filter parameters
        $cacheKey = $this->generateCacheKey($request, $page);

        // Check if data exists in cache
        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);
            return response()->json(['data' => $cachedData, 'cache_key' => $cacheKey]);
        }

        // Start with all plants
        $query = Plant::query();

        // Apply filters based on combinations available
        if ($request->has('biodiversity_attracting')) {
            $query->where('biodiversity_attracting', true);
        }

        if ($request->has('edible')) {
            $query->where('edible', true);
        }

        if ($request->has('fragrant')) {
            $query->where('fragrant', true);
        }

        if ($request->has('native_to_singapore')) {
            $query->where('native_to_singapore', true);
        }

        if ($request->has('coastal_and_marine')) {
            $query->where('coastal_and_marine', true);
        }

        if ($request->has('freshwater')) {
            $query->where('freshwater', true);
        }

        if ($request->has('terrestrial')) {
            $query->where('terrestrial', true);
        }

        // Paginate the filtered results
        $perPage = $request->input('per_page', 20);
        $plants = $query->paginate($perPage);

        // Cache the data for future requests
        Cache::put($cacheKey, $plants, now()->addMinutes(10)); // Cache for 10 minutes

        return response()->json(['data' => $plants, 'cache_key' => $cacheKey]);
    }

    private function generateCacheKey(Request $request, $page)
    {
        $filters = $request->except(['page', 'per_page']); // Exclude pagination parameters
        ksort($filters); // Sort the filters to ensure consistent cache keys
        $filterQueryString = http_build_query($filters);

        return 'all_plants_page_' . $page . '_filters_' . $filterQueryString;
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
            'biodiversity_attracting' => 'required',
            'edible' => 'required',
            'fragrant' => 'required',
            'native_to_singapore' => 'required',
            'coastal_and_marine' => 'required',
            'freshwater' => 'required',
            'terrestrial' => 'required',
        ]);

        try {
            $plant = Plant::create($validatedData);
            // Clear the cache for all plants
            $this->forgetAllPlantsCache();
            return response()->json($plant, 201);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json(['error' => 'Failed to create plant because of '.$e.'.'], 500);
        }
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
            'biodiversity_attracting' => 'required',
            'edible' => 'required',
            'fragrant' => 'required',
            'native_to_singapore' => 'required',
            'coastal_and_marine' => 'required',
            'freshwater' => 'required',
            'terrestrial' => 'required',
        ]);

        $plant = Plant::findOrFail($id);
        $plant->update($validatedData);

        // Clear the cache for all plants
        $this->forgetAllPlantsCache();

        // Cache key for the individual plant
        $cacheKey = 'plant_' . $id;
        Cache::forget($cacheKey);

        return response()->json($validatedData);
    }

    public function destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();

        // Clear the cache for all plants
        $this->forgetAllPlantsCache();

        // Cache key for the individual plant
        $cacheKey = 'plant_' . $id;
        Cache::forget($cacheKey);

        return response()->json(null, 204);
    }

    private function forgetAllPlantsCache()
    {
        // Get all cache keys
        $keys = Cache::get('all_cache_keys', []);

        // Loop through the keys and forget the ones that start with "all_plants_page_"
        foreach ($keys as $key) {
            if (strpos($key, 'all_plants_page_') === 0) {
                Cache::forget($key);
            }
        }
    }
}
