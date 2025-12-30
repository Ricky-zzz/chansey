<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TREATMENT PLANS (Strategic Goal Setting)
        // One plan per admission, but editable.
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('physician_id')->constrained(); // Who authored it
            
            $table->text('main_problem'); // Diagnosis/Injury
            
            // JSON Arrays are perfect for bullet points
            $table->json('goals')->nullable(); // ["Stabilize BP", "Manage Pain"]
            $table->json('interventions')->nullable(); // ["Monitor Vitals", "Administer Antibiotics"]
            
            $table->text('expected_outcome')->nullable();
            $table->text('evaluation')->nullable(); // Doctor's notes on progress
            
            $table->string('status')->default('active'); // active, completed, revised
            $table->timestamps();
        });

        // 2. MEDICAL ORDERS (The Command Center)
        Schema::create('medical_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('physician_id')->constrained();
            
            // Filters what the nurse sees/does
            $table->string('type')->index(); // 'Medication', 'Lab', 'Dietary', 'Transfer', 'Discharge'
            
            $table->text('instruction'); // "Paracetamol 500mg" or "Soft Diet"
            
            // Optional Link to Pharmacy (If it is a medication order)
            $table->foreignId('medicine_id')->nullable()->constrained();
            $table->integer('quantity')->default(1); // Add this!
            
            $table->string('frequency')->nullable(); // "Every 4 hours", "Once"
            
            // Status Loop
            $table->string('status')->default('Pending')->index(); // Pending -> Done -> Cancelled
            
            // Execution Trail (Filled when Nurse clicks "Done")
            $table->foreignId('fulfilled_by_user_id')->nullable()->constrained('users');
            $table->timestamp('fulfilled_at')->nullable();
            
            $table->timestamps();
        });

        // 3. CLINICAL LOGS (The Evidence / History)
        Schema::create('clinical_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // User (Nurse/Doc) who wrote the log
            $table->foreignId('medical_order_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('type')->index(); // 'Vitals', 'Note', 'Medication', 'Intake'
            
            // The JSON Powerhouse
            // Example Vitals: {"temp": 37.5, "bp": "120/80", "remark": "Patient stable"}
            // Example Note: {"observation": "Patient sleeping", "feedback": "Responded well to meds"}
            $table->json('data')->nullable();
            
            $table->timestamps();
        });

        // 4. BILLABLE ITEMS (The Wallet)
        Schema::create('billable_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            
            $table->string('name'); // "Paracetamol 500mg"
            $table->decimal('amount', 10, 2); // Price at moment of use
            $table->integer('quantity')->default(1);
            $table->decimal('total', 10, 2); // amount * quantity
            
            $table->string('status')->default('Unpaid'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billable_items');
        Schema::dropIfExists('clinical_logs');
        Schema::dropIfExists('medical_orders');
        Schema::dropIfExists('treatment_plans');
    }
};