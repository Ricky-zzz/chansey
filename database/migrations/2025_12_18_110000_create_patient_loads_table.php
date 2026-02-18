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
        Schema::create('patient_loads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('nurse_id')->constrained('nurses')->cascadeOnDelete();
            $table->enum('acuity', ['Severe', 'High', 'Moderate', 'Low'])->default('Moderate');
            $table->integer('score')->storedAs('CASE
                WHEN acuity = "Severe" THEN 4
                WHEN acuity = "High" THEN 3
                WHEN acuity = "Moderate" THEN 2
                WHEN acuity = "Low" THEN 1
                ELSE 0
            END');
            $table->text('description')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate assignments
            $table->unique(['patient_id', 'nurse_id']);

            // Indexes for common queries
            $table->index('nurse_id');
            $table->index('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_loads');
    }
};
