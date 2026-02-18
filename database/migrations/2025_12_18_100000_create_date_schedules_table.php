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
        Schema::create('date_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->foreignId('nurse_id')->constrained('nurses')->cascadeOnDelete();
            $table->time('start_shift');
            $table->time('end_shift');
            $table->string('assignment')->nullable();
            $table->timestamps();

            // Composite index for efficient querying by date and nurse
            $table->index(['date', 'nurse_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('date_schedules');
    }
};
