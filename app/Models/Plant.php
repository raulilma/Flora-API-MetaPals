<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];

    protected $casts = [
        'biodiversity_attracting' => 'boolean',
        'edible' => 'boolean',
        'fragrant' => 'boolean',
        'native_to_singapore' => 'boolean',
        'coastal_and_marine' => 'boolean',
        'freshwater' => 'boolean',
        'terrestrial' => 'boolean',
    ];
}
