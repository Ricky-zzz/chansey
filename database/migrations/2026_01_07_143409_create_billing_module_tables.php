<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ACCOUNTANT PROFILE
        Schema::create('accountants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('employee_id')->unique(); // e.g. ACC-001
            $table->string('first_name');
            $table->string('last_name')->index();
            $table->timestamps();
        });

        // 2. BILLINGS (The Transaction Record / Receipt)
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            
            // Link to the specific hospital stay
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            
            // The person who processed the payment
            $table->foreignId('processed_by')->constrained('users');
            
            // --- THE FINANCIALS ---
            // Stores the snapshot of what they paid for (Room days, meds, etc.)
            // Essential for re-printing receipts later without recalculating.
            $table->json('breakdown'); 
            /* Example JSON: 
               {
                 "room_total": 5000, 
                 "meds_total": 2000, 
                 "services_total": 1000,
                 "pf_fee": 10000,
                 "deductions": {"philhealth": 5000}
               }
            */

            $table->decimal('gross_total', 10, 2); // Subtotal before deductions
            $table->decimal('final_total', 10, 2); // Amount Due
            $table->decimal('amount_paid', 10, 2); // Cash given
            $table->decimal('change', 10, 2);      // Change returned
            
            $table->string('status')->default('Paid'); // Paid, Refunded, Cancelled
            $table->string('receipt_number')->unique(); // OR-2025-0001
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billings');
        Schema::dropIfExists('accountants');
    }
};
