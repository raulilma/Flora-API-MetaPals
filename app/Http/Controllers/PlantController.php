<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::all();
        return response()->json($plants);
    }

    public function show($id)
    {
        $plant = Plant::findOrFail($id);
        return response()->json($plant);
    }

    public function store(Request $request)
    {
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
        return response()->json($plant, 201);
    }

    public function update(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->update($request->all());
        return response()->json($plant, 200);
    }

    public function destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();
        return response()->json(null, 204);
    }
}
