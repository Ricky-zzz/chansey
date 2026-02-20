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
        // 1. MEMOS TABLE (Core announcement data)
        Schema::create('memos', function (Blueprint $table) {
            $table->id();

            // Who sent it? (Link to the user/nurse)
            $table->foreignId('created_by_user_id')->constrained('users')->index();

            $table->string('title');
            $table->text('content');

            $table->string('attachment_path')->nullable(); // PDF/Image
            $table->timestamps();
        });

        // 2. PIVOT: Target Roles (Staff, Head, Supervisor, Chief)
        Schema::create('memo_target_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained()->cascadeOnDelete();
            $table->string('role'); // Staff, Head, Supervisor, Chief

            // Indexes for fast queries
            $table->index('memo_id');
            $table->index('role');
            $table->unique(['memo_id', 'role']); // No duplicates
        });

        // 3. PIVOT: Target Units
        Schema::create('memo_target_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();

            // Indexes for fast queries
            $table->index('memo_id');
            $table->index('unit_id');
            $table->unique(['memo_id', 'unit_id']); // No duplicates
        });

        // 4. PIVOT: Target Stations
        Schema::create('memo_target_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();

            // Indexes for fast queries
            $table->index('memo_id');
            $table->index('station_id');
            $table->unique(['memo_id', 'station_id']); // No duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_target_stations');
        Schema::dropIfExists('memo_target_units');
        Schema::dropIfExists('memo_target_roles');
        Schema::dropIfExists('memos');
    }
};
