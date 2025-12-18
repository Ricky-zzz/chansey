<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();

            $table->string('station_name')->unique(); 
            
            $table->string('station_code')->nullable()->unique(); 
            
            $table->string('floor_location')->nullable(); 

            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('station_id')->constrained('stations')->cascadeOnDelete();

            $table->string('room_number'); 
            
            $table->string('room_type')->index(); 
            
            $table->integer('capacity')->default(1);
            $table->decimal('price_per_night', 10, 2)->default(0.00); 

            $table->string('status')->default('Active')->index(); 
            $table->timestamps();
            
            $table->unique('room_number');
        });

        Schema::create('beds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();

            $table->string('bed_code')->unique();

            $table->string('status')->default('Available')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beds');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('stations'); 
    }
};