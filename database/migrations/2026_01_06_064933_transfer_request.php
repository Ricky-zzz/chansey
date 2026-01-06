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
        Schema::create('transfer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medical_order_id')->constrained();
            $table->foreignId('requested_by_user_id')->constrained('users');

            $table->foreignId('target_station_id')->constrained('stations');
            $table->foreignId('target_bed_id')->constrained('beds'); 

            $table->string('status')->default('Pending'); 
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
        Schema::create('patient_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();

            $table->foreignId('room_id')->constrained();
            $table->foreignId('bed_id')->constrained(); 
            $table->decimal('room_price', 10, 2);

            // Time tracking
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_movements');
        Schema::dropIfExists('transfer_requests');
    }
};
