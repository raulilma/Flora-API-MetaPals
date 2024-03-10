<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('image_url');
            $table->string('common_name');
            $table->string('scientific_name');
            $table->text('description');
            $table->string('family');
        
            $table->string('plant_division');
            $table->string('plant_growth_form');
            $table->string('lifespan');
        
            $table->string('native_habitat');
            $table->string('preferred_climate_zone');
            $table->string('local_conservation_status');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
