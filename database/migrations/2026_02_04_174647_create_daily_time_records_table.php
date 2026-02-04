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
        Schema::create('daily_time_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();

            $table->dateTime('time_in');
            $table->dateTime('time_out')->nullable();

            // Calculations (Computed on Time Out)
            $table->decimal('total_hours', 5, 2)->default(0);

            // Status Logic
            // Ongoing: Currently clocked in
            // Present: Normal completion
            // Late: Time In > Shift Start
            // Incomplete: Forgot to Time Out (Auto-closed by system)
            $table->string('status')->default('Ongoing');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_time_records');
    }
};
