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
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();

            // Actors
            $table->foreignId('created_by_user_id')->constrained('users'); // Head Nurse
            $table->foreignId('assigned_to_nurse_id')->constrained('nurses'); // Staff Nurse

            // Content
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority'); //'Low', 'Medium', 'High'

            // Optional link to an admission id order has somthing to do with an admission
            $table->foreignId('admission_id')->nullable()->constrained()->nullOnDelete();

            // Workflow
            $table->string('status')->default('Pending'); // Pending -> In Progress -> Done
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
