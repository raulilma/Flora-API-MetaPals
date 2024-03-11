<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class PlantController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Determine the current page from the request
            $page = $request->input('page', 1);

            // Cache key for the plant index including page and filter parameters
            $cacheKey = $this->generateCacheKey($request, $page);

            // Ensure unique cache key for each combination of parameters
            $cacheKey .= '_'.md5(serialize($request->all()));

            // Check if data exists in cache
            if (Cache::has($cacheKey)) {
                $cachedData = Cache::get($cacheKey);
                return response()->json(['data' => $cachedData, 'cache_key' => $cacheKey]);
            }

            // Start with all plants
            $query = Plant::query();

            // Define filterable parameters
            $filterableParams = ['biodiversity_attracting', 'edible', 'fragrant', 'native_to_singapore', 'coastal_and_marine', 'freshwater', 'terrestrial'];

            // Apply filters based on request parameters
            foreach ($filterableParams as $param) {
                if ($request->has($param)) {
                    $value = filter_var($request->input($param), FILTER_VALIDATE_BOOLEAN);
                    $query->where($param, $value);
                }
            }

            // Paginate the filtered results
            $perPage = $request->input('per_page', 20);
            $plants = $query->paginate($perPage);

            // Cache the data for future requests
            Cache::put($cacheKey, $plants, now()->addMinutes(10)); // Cache for 10 minutes

            return response()->json(['data' => $plants, 'cache_key' => $cacheKey]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch plant data: ' . $e->getMessage()], 500);
        }
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
        try {
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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case when the plant is not found
            return response()->json(['error' => 'Plant not found'], 404);
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            return response()->json(['error' => 'Failed to fetch plant data: '.$e->getMessage()], 500);
        }
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
            return response()->json(['message' => 'Plant created successfully', 'data' => $plant], 201);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json(['error' => 'Failed to create plant: '.$e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
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

            return response()->json(['message' => 'Plant updated successfully', 'data' => $validatedData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update plant: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $plant = Plant::findOrFail($id);
            $plant->delete();

            // Clear the cache for all plants
            $this->forgetAllPlantsCache();

            // Cache key for the individual plant
            $cacheKey = 'plant_' . $id;
            Cache::forget($cacheKey);

            return response()->json(['message' => 'Plant deleted successfully'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete plant: ' . $e->getMessage()], 500);
        }
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
