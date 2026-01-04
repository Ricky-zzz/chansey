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
        Schema::create('nursing_care_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nurse_id')->constrained('nurses'); 

            $table->text('assessment'); 
            $table->text('diagnosis');  

            $table->json('planning')->nullable(); 
            $table->json('interventions')->nullable(); 

            $table->text('rationale')->nullable(); 
            $table->text('evaluation')->nullable(); 

            $table->string('status')->default('Active'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nursing_care_plans');
    }
};
