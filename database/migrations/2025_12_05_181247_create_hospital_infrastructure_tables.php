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
    // 1. ROOMS (The Containers)
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->string('room_number')->unique(); 
        $table->enum('room_type', ['Private', 'Semi-Private', 'Ward', 'ICU', 'ER']);
        $table->integer('capacity')->default(1); // How many beds?
        $table->enum('status', ['Active', 'Maintenance', 'Closed'])->default('Active');
        $table->timestamps();
    });

    Schema::create('beds', function (Blueprint $table) {
        $table->id();
        $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
        $table->string('bed_code')->unique(); 
        $table->enum('status', ['Available', 'Occupied', 'Cleaning', 'Maintenance'])->default('Available');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_infrastructure_tables');
    }
};
