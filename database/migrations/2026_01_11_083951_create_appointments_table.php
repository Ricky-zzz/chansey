<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('contact_number');

            // Request
            $table->string('department');
            $table->text('purpose');

            // Schedule (Filled by Admitting Nurse)
            $table->foreignId('physician_id')->nullable()->constrained();
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('end_time')->nullable(); 

            // Status Workflow
            // Pending: Guest sent it
            // Approved: Nurse assigned a Doctor
            // Admitted: Nurse clicked "Check-In" (Turned into Admission)
            // Cancelled: Denied
            $table->string('status')->default('Pending')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
