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

        // Nurse Types Table (must come before nurses)
        Schema::create('nurse_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('nurses', function (Blueprint $table) {
            $table->id();

            // 1. User Account Link
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('employee_id')->unique();

            // 2. Personal Information (Bio-Data)
            $table->string('first_name');
            $table->string('last_name')->index();
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->date('birthdate')->nullable();

            // 3. Professional Details
            $table->string('license_number');
            $table->string('status')->default('Active'); // Active / Inactive
            $table->date('date_hired')->nullable();

            // Education (JSON for Grid Table)
            // Structure: [{ level: 'BS', school: 'UST', year: '2023' }]
            $table->json('educational_background')->nullable();

            // 4. Role Hierarchy & Assignment
            // Role Level: 'Staff', 'Charge', 'Head', 'Supervisor', 'Chief'
            $table->string('role_level')->default('Staff')->index();

            // Designation: 'Admitting' vs 'Clinical' (For Dashboard Redirect)
            $table->string('designation')->default('Clinical')->index();

            // Job Description: Link to "ER Nurse", "Dialysis", etc.
            $table->foreignId('nurse_type_id')->nullable()->constrained()->nullOnDelete();

            // 5. Location Assignments
            // Standard Nurses belong to a Station
            $table->foreignId('station_id')->nullable()->constrained('stations')->nullOnDelete();

            // Supervisors belong to a Unit (Building)
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();

            // 6. Scheduling
            $table->foreignId('shift_schedule_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();

            // Optimization
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
        Schema::dropIfExists('nurse_types');
        Schema::dropIfExists('admins');
    }
};
