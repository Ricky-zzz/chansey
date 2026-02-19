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
        // Main incidents table
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            // Station and optional admission
            $table->foreignId('station_id')
                ->constrained('stations')
                ->cascadeOnDelete()
                ->name('fk_incidents_station');
            $table->foreignId('admission_id')
                ->nullable()
                ->constrained('admissions')
                ->nullOnDelete()
                ->name('fk_incidents_admission');

            // Who reported and who resolved
            $table->foreignId('created_by_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->name('fk_incidents_created_by');
            $table->foreignId('resolved_by_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->name('fk_incidents_resolved_by');

            // A. Incident Details
            $table->dateTime('time_of_incident'); // When incident occurred
            $table->dateTime('time_reported'); // When reported
            $table->string('location_details')->nullable(); // "Room 101, Bed 2"
            $table->string('incident_category'); // medication_error, patient_fall, equipment_malfunction, near_miss, wrong_documentation, other
            $table->string('severity_level'); // Low, Moderate, High, Severe

            // B. Description (narratives)
            $table->text('narrative')->nullable(); // Overall narrative
            $table->text('what_happened')->nullable(); // What actually happened
            $table->text('how_discovered')->nullable(); // How was it discovered
            $table->text('action_taken')->nullable(); // Actions taken immediately

            // C. Patient Outcome
            $table->boolean('injury')->default(false); // Was patient injured
            $table->string('injury_type')->nullable(); // Type of injury
            $table->json('vitals')->nullable(); // Vitals taken after: temp, bp, hr, pr, rr, o2
            $table->boolean('doctor_notified')->default(false);
            $table->boolean('family_notified')->default(false);

            // D. Root Cause & Follow-up
            $table->string('root_cause')->nullable(); // human_error, system_issue, equipment_failure, staffing_issue, other
            $table->string('follow_up_actions')->nullable(); // monitoring, referral, corrective_action
            $table->text('follow_up_instructions')->nullable(); // Describe follow-up

            // Optional: Witnesses (flexible - could be staff or visitors)
            $table->json('witness')->nullable();

            // Status & Resolution
            $table->string('status')->default('unresolved'); // unresolved, investigating, resolved
            $table->timestamp('resolved_at')->nullable(); // When resolved
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            // Indexes for queries
            $table->index('station_id');
            $table->index('admission_id');
            $table->index('created_by_id');
            $table->index('resolved_by_id');
            $table->index('status');
            $table->index('severity_level');
            $table->index('time_of_incident');
            $table->index(['station_id', 'status']); // For filtering by station + status
            $table->index(['station_id', 'created_by_id']); // For "incidents I reported"
        });

        // Pivot table for staff involved in incident
        Schema::create('incident_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')
                ->constrained('incidents')
                ->cascadeOnDelete()
                ->name('fk_incident_staff_incident');
            $table->foreignId('staff_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->name('fk_incident_staff_user');
            $table->string('role_in_incident')->default('involved'); // primary, witness, responder, involved
            $table->timestamp('created_at')->useCurrent();

            // Indexes
            $table->index('incident_id');
            $table->index('staff_id');
            $table->unique(['incident_id', 'staff_id']); // Prevent duplicate involvement
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_staff');
        Schema::dropIfExists('incidents');
    }
};
