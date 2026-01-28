<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. PATIENTS (Permanent Demographic Data)
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            
            // System Identifiers
            $table->string('patient_unique_id')->unique(); 
            $table->foreignId('created_by_user_id')->constrained('users'); 
            
            // A. Identity
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->index();
            $table->date('date_of_birth')->index();
            
    
            $table->string('sex');
            $table->string('civil_status'); 
            
            $table->string('nationality')->default('Filipino');
            $table->string('religion')->nullable(); 
            
            // B. Contact & Address
            $table->text('address_permanent');
            $table->text('address_present')->nullable(); 
            $table->string('contact_number');
            $table->string('email')->nullable();
            
            // C. Emergency Contact 
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_number');

            // D. Identification Tags
            $table->string('philhealth_number')->nullable();
            $table->string('senior_citizen_id')->nullable();
            
            $table->timestamps();
            
            $table->index(['last_name', 'first_name']); 
        });

        // 2. ADMISSIONS (The Clinical Event)
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            
            // System Identifiers
            $table->string('admission_number')->unique(); 
            
            // Assignment & Classification
            $table->foreignId('station_id')->nullable()->constrained('stations'); 
            $table->foreignId('bed_id')->nullable()->constrained('beds'); 
            $table->foreignId('attending_physician_id')->constrained('physicians');
            $table->foreignId('admitting_clerk_id')->constrained('users'); 
            
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();
            
            // CHANGED: Enum -> String
            $table->string('admission_type'); 
            $table->string('case_type'); 
            $table->string('status')->default('Admitted')->index(); 
            
            // Clinical Entry Snapshot
            $table->text('chief_complaint');
            $table->text('initial_diagnosis')->nullable();
            
            // CHANGED: Enum -> String
            $table->string('mode_of_arrival'); 
            
            $table->json('initial_vitals')->nullable(); // Stores: BP, Temp, PR, RR, O2, Height, Weight
            
            $table->json('known_allergies')->nullable(); 
            
            $table->timestamps();
        });

        // 3. BILLING INFO 
        Schema::create('admission_billing_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            
        
            $table->string('payment_type')->default('Cash'); 
            
            // Insurance / HMO Details
            $table->string('primary_insurance_provider')->nullable(); 
            $table->string('policy_number')->nullable();
            $table->string('approval_code')->nullable(); 
            
            // Guarantor (Person paying if not patient)
            $table->string('guarantor_name')->nullable();
            $table->string('guarantor_relationship')->nullable();
            $table->string('guarantor_contact')->nullable();
            
            $table->timestamps();
        });

        // 4. PATIENT FILES (The "Bucket" for uploads)
        Schema::create('patient_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admission_id')->nullable()->constrained()->cascadeOnDelete();
            
            $table->string('file_path'); 
            $table->string('file_name');
            
            // CHANGED: Enum -> String
            $table->string('document_type'); // Consent, Lab Result...
            
            $table->foreignId('uploaded_by_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_files');
        Schema::dropIfExists('admission_billing_infos');
        Schema::dropIfExists('admissions');
        Schema::dropIfExists('patients');
    }
};