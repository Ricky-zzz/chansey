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
            $table->enum('sex', ['Male', 'Female']);
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Separated']);
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
            $table->string('admission_number')->unique(); // Visit ID
            // $table->string('qr_code_hash')->nullable();   // For wristband if ever needed
            
            // Assignment & Classification
            $table->foreignId('bed_id')->nullable()->constrained('beds'); 
            $table->foreignId('attending_physician_id')->constrained('physicians');
            $table->foreignId('admitting_clerk_id')->constrained('users'); 
            
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();
            
            // Types
            $table->enum('admission_type', ['Emergency', 'Outpatient', 'Inpatient', 'Transfer']);
            $table->enum('case_type', ['New Case', 'Returning', 'Follow-up']);
            $table->enum('status', ['Admitted', 'Discharged', 'Transferred', 'Died'])->default('Admitted')->index();
            
            // Clinical Entry Snapshot
            $table->text('chief_complaint');
            $table->text('initial_diagnosis')->nullable();
            $table->enum('mode_of_arrival', ['Walk-in', 'Ambulance', 'Wheelchair', 'Stretcher']);
            
            // Entry Vitals (Snapshot)
            $table->decimal('temp', 4, 1)->nullable();
            $table->integer('bp_systolic')->nullable();
            $table->integer('bp_diastolic')->nullable();
            $table->integer('pulse_rate')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->integer('o2_sat')->nullable();
            
            $table->json('known_allergies')->nullable(); 
            
            $table->timestamps();
        });

        // 3. BILLING INFO 
        Schema::create('admission_billing_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            
            $table->enum('payment_type', ['Cash', 'Insurance', 'HMO', 'Company']);
            
            // Insurance / HMO Details
            $table->string('primary_insurance_provider')->nullable(); // e.g. "Maxicare"
            $table->string('policy_number')->nullable();
            $table->string('approval_code')->nullable(); // LOA Code
            
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
            
            $table->enum('document_type', [
                'General Consent',
                'Privacy Notice',
                'PhilHealth MDR',
                'Insurance LOA',
                'Valid ID',
                'Lab Result',
                'Other'
            ]);
            
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