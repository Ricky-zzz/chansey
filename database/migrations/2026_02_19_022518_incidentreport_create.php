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
                // Endorsement: Created by outgoing nurse, received by incoming nurse
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            // A. Shift Information
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete(); // Per-admission endorsement

            // Shift participants (from users table, matching with nurse roles)
            $table->foreignId('outgoing_nurse_id')->constrained('users')->cascadeOnDelete(); // Creator
            $table->foreignId('incoming_nurse_id')->constrained('users')->cascadeOnDelete(); // Receiver
            $table->foreignId('submitted_by_id')->nullable()->constrained('users')->nullOnDelete(); // Formal submission

            // Submission tracking (immutable after submission)
            $table->timestamp('submitted_at')->nullable(); // NULL = draft, SET = locked
            $table->timestamp('created_at')->useCurrent(); // Immutable record creation time (no updated_at)

            // B. Patient Level Endorsement Details (SBAR Format)

            // 1. SITUATION
            $table->string('diagnosis');
            $table->text('current_condition')->nullable();
            $table->string('code_status'); // 'Low', 'Moderate', 'High', 'Severe'

            // 2. BACKGROUND (copied/manually filled at creation time - immutable snapshot)
            $table->timestamp('date_admitted')->nullable();
            $table->json('known_allergies')->nullable(); // Snapshot from admission
            $table->json('medication_history')->nullable(); // Snapshot from admission
            $table->json('past_medical_history')->nullable(); // Snapshot from admission

            // 3. ASSESSMENT (manually filled by outgoing nurse)
            $table->json('latest_vitals')->nullable(); // {"temp": 37.5, "bp": "120/80", ...}
            $table->string('pain_scale')->nullable(); // "1-10 scale"
            $table->json('iv_lines')->nullable(); // [{line: "peripheral", site: "left arm", ...}]
            $table->json('wounds')->nullable(); // [{location: "chest", type: "surgical", ...}]
            $table->json('labs_pending')->nullable(); // [{test: "CBC", ordered_at: ...}]
            $table->json('abnormal_findings')->nullable(); // [{finding: "...", significance: "..."}]

            // 4. RECOMMENDATIONS
            $table->json('upcoming_medications')->nullable(); // [{med: "...", dose: "...", route: "..."}]
            $table->json('labs_follow_up')->nullable(); // [{test: "...", timeline: "..."}]
            $table->json('monitor_instructions')->nullable(); // [{parameter: "vitals", frequency: "q4h"}]
            $table->json('special_precautions')->nullable(); // [{precaution: "...", reason: "..."}]

            // C. Ward Level Endorsement Details
            $table->string('bed_occupancy')->nullable(); // "6/10 beds occupied"
            $table->json('equipment_issues')->nullable(); // [{equipment: "monitor", issue: "..."}]
            $table->json('pending_admissions')->nullable(); // [{patient: "...", eta: "..."}]
            $table->json('station_issues')->nullable(); // [{issue: "...", severity: "..."}]
            $table->json('critical_incidents')->nullable(); // [{incident: "...", time: "..."}]

            // Indexes for fast queries
            $table->index('admission_id');
            $table->index('station_id');
            $table->index('outgoing_nurse_id');
            $table->index('incoming_nurse_id');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
