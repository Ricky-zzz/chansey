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
        Schema::create('station_tasks', function (Blueprint $table) {
            $table->id();

            // Scope
            $table->foreignId('station_id')
                ->constrained('stations')
                ->cascadeOnDelete()
                ->name('fk_station_tasks_station')
                ->index();

            // Actors
            $table->foreignId('created_by_user_id')
                ->constrained('users')
                ->name('fk_station_tasks_created_by')
                ->index();
            $table->foreignId('assigned_to_nurse_id')
                ->constrained('nurses')
                ->cascadeOnDelete()
                ->name('fk_station_tasks_assigned_to')
                ->index();

            // Content
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority');

            // Optional link to an admission id order has something to do with an admission
            $table->foreignId('admission_id')
                ->nullable()
                ->constrained('admissions')
                ->nullOnDelete()
                ->name('fk_station_tasks_admission');

            // Workflow
            $table->string('status')->default('Pending')->index();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_tasks');
    }
};
