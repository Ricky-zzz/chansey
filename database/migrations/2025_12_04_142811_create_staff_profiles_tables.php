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
        // Admins Table
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreign('user_id', 'admins_user_id_foreign')->references('id')->on('users')->cascadeOnDelete();
            $table->string('full_name');
            $table->timestamps();
        });

        // Nurses Table
        Schema::create('nurses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreign('user_id', 'nurses_user_id_foreign')->references('id')->on('users')->cascadeOnDelete();

            $table->string('employee_id')->unique();

            $table->string('first_name');
            $table->string('last_name')->index();

            $table->string('license_number');

            $table->string('designation')->default('Clinical')->index(); // can be admitting, clinical, etc.

            $table->foreignId('station_id')->nullable()->constrained('stations')->nullOnDelete();

            $table->foreignId('shift_schedule_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_head_nurse')->default(false);

            $table->timestamps();

            $table->index(['last_name', 'first_name']);
        });

        // Physicians Table
        Schema::create('physicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreign('user_id', 'physicians_user_id_foreign')->references('id')->on('users')->cascadeOnDelete();
            $table->string('employee_id')->unique();

            $table->string('first_name');
            $table->string('last_name')->index();

            $table->string('specialization')->index();

            $table->string('employment_type');
            $table->timestamps();

            $table->index(['last_name', 'first_name']);
        });

        // General Services Table
        Schema::create('general_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreign('user_id', 'general_services_user_id_foreign')->references('id')->on('users')->cascadeOnDelete();
            $table->string('employee_id')->unique();

            $table->string('first_name');
            $table->string('last_name')->index();

            $table->string('assigned_area')->index();

            $table->time('shift_start');
            $table->time('shift_end');
            $table->timestamps();

            $table->index(['last_name', 'first_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fix: You need to drop the tables individually, not just 'staff_profiles_tables'
        Schema::dropIfExists('general_services');
        Schema::dropIfExists('physicians');
        Schema::dropIfExists('nurses');
        Schema::dropIfExists('admins');
    }
};
