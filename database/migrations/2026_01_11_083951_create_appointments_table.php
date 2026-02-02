<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. THE BUCKETS (Created by Doctor)
        Schema::create('appointment_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('physician_id')->constrained()->cascadeOnDelete();
            
            $table->date('date');        // "2025-01-20"
            $table->time('start_time');  // "08:00:00"
            $table->time('end_time');    // "12:00:00"
            
            $table->integer('capacity'); // e.g. 10 patients max
            $table->string('status')->default('Active'); // Active, Cancelled
            
            $table->timestamps();
        });

        // 2. THE BOOKINGS (Created by Patient)
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            
            // LINK TO THE SLOT (This replaces physician_id and scheduled_at)
            $table->foreignId('appointment_slot_id')->constrained()->cascadeOnDelete();

            // Patient Info (Guest)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('contact_number');
            $table->text('purpose');

            // Status
            // Booked: Successfully took a slot
            // Admitted: Turned into an admission
            // Cancelled: User cancelled or Doctor cancelled
            $table->string('status')->default('Booked')->index(); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('appointment_slots');
    }
};