<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            
            $table->string('room_number')->unique(); 
            
            $table->enum('room_type', ['Private', 'Semi-Private', 'Ward', 'ICU', 'ER'])->index();
            
            $table->integer('capacity')->default(1); 
            
            $table->enum('status', ['Active', 'Maintenance', 'Closed'])->default('Active')->index();
            
            $table->timestamps();
        });

        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            
            $table->string('bed_code')->unique(); 
            
            $table->enum('status', ['Available', 'Occupied', 'Cleaning', 'Maintenance'])->default('Available')->index();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beds');
        Schema::dropIfExists('rooms');
    }
};