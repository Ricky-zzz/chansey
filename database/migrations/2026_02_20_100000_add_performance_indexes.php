<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Performance Indexes Migration
 *
 * Adds indexes to columns frequently used in WHERE clauses, JOINs, and ORDER BY
 * Based on analysis of controller query patterns.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ============================================
        // 1. ADMISSIONS TABLE
        // Most heavily queried table - status/station combo used everywhere
        // ============================================
        Schema::table('admissions', function (Blueprint $table) {
            // Single column indexes
            $table->index('station_id', 'idx_admissions_station_id');
            $table->index('attending_physician_id', 'idx_admissions_attending_physician_id');
            $table->index('patient_id', 'idx_admissions_patient_id');
            $table->index('admission_date', 'idx_admissions_admission_date');

            // Composite indexes for common query patterns
            // ->where('station_id', X)->whereIn('status', ['Admitted', 'Ready for Discharge'])
            $table->index(['station_id', 'status'], 'idx_admissions_station_status');

            // Physician dashboard: ->where('attending_physician_id', X)->whereIn('status', [...])
            $table->index(['attending_physician_id', 'status'], 'idx_admissions_physician_status');

            // Patient history lookups
            $table->index(['patient_id', 'status'], 'idx_admissions_patient_status');
        });

        // ============================================
        // 2. NURSES TABLE
        // Frequently filtered by station + status
        // ============================================
        Schema::table('nurses', function (Blueprint $table) {
            // Status filtering: ->where('status', 'Active')
            $table->index('status', 'idx_nurses_status');

            // Station filtering with FK (explicit index for better performance)
            $table->index('station_id', 'idx_nurses_station_id');

            // Floater queries: ->where('nurse_type_id', X)->whereNull('station_id')
            $table->index('nurse_type_id', 'idx_nurses_nurse_type_id');

            // Composite: station + status used together
            $table->index(['station_id', 'status'], 'idx_nurses_station_status');

            // Floater filtering pattern
            $table->index(['nurse_type_id', 'station_id', 'status'], 'idx_nurses_type_station_status');
        });

        // ============================================
        // 3. BEDS TABLE
        // Status queries for availability checks
        // ============================================
        Schema::table('beds', function (Blueprint $table) {
            // Available bed searches: ->where('status', 'Available')
            $table->index('status', 'idx_beds_status');
        });

        // ============================================
        // 4. MEDICINES TABLE
        // Stock filtering for prescriptions
        // ============================================
        Schema::table('medicines', function (Blueprint $table) {
            // Stock availability: ->where('stock_on_hand', '>', 0)
            $table->index('stock_on_hand', 'idx_medicines_stock');
        });

        // ============================================
        // 5. INVENTORY_ITEMS TABLE
        // Supply availability checks
        // ============================================
        Schema::table('inventory_items', function (Blueprint $table) {
            // Quantity filtering: ->where('quantity', '>', 0)
            $table->index('quantity', 'idx_inventory_items_quantity');
        });

        // ============================================
        // 6. TRANSFER_REQUESTS TABLE
        // Status filtering for pending approvals
        // ============================================
        Schema::table('transfer_requests', function (Blueprint $table) {
            // Pending requests: ->where('status', 'Pending')
            $table->index('status', 'idx_transfer_requests_status');

            // Admission history
            $table->index('admission_id', 'idx_transfer_requests_admission_id');
        });

        // ============================================
        // 7. DAILY_TIME_RECORDS TABLE
        // DTR queries by user and date ranges
        // ============================================
        Schema::table('daily_time_records', function (Blueprint $table) {
            // User DTR history: ->where('user_id', X)
            $table->index('user_id', 'idx_dtr_user_id');

            // Date range filtering: ->whereYear('time_in', X)->whereMonth('time_in', X)
            $table->index('time_in', 'idx_dtr_time_in');

            // Hanging records: ->whereNull('time_out')
            $table->index('time_out', 'idx_dtr_time_out');

            // Status queries
            $table->index('status', 'idx_dtr_status');

            // User + time_in composite for monthly DTR views
            $table->index(['user_id', 'time_in'], 'idx_dtr_user_time_in');
        });

        // ============================================
        // 8. PATIENT_MOVEMENTS TABLE
        // Admission movement history
        // ============================================
        Schema::table('patient_movements', function (Blueprint $table) {
            // Movement history per admission
            $table->index('admission_id', 'idx_patient_movements_admission_id');

            // Active movements: ->whereNull('ended_at')
            $table->index('ended_at', 'idx_patient_movements_ended_at');
        });

        // ============================================
        // 9. BILLABLE_ITEMS TABLE
        // Status and admission filtering
        // ============================================
        Schema::table('billable_items', function (Blueprint $table) {
            // Admission billing: ->where('admission_id', X)
            $table->index('admission_id', 'idx_billable_items_admission_id');

            // Payment status: ->where('status', 'Unpaid')
            $table->index('status', 'idx_billable_items_status');

            // Type filtering (if applicable)
            $table->index('type', 'idx_billable_items_type');
        });

        // ============================================
        // 10. TREATMENT_PLANS TABLE
        // Admission treatment lookups
        // ============================================
        Schema::table('treatment_plans', function (Blueprint $table) {
            $table->index('admission_id', 'idx_treatment_plans_admission_id');
            $table->index('status', 'idx_treatment_plans_status');
        });

        // ============================================
        // 11. NURSING_CARE_PLANS TABLE
        // Admission care plan lookups
        // ============================================
        Schema::table('nursing_care_plans', function (Blueprint $table) {
            $table->index('admission_id', 'idx_nursing_care_plans_admission_id');
            $table->index('nurse_id', 'idx_nursing_care_plans_nurse_id');
            $table->index('status', 'idx_nursing_care_plans_status');
        });

        // ============================================
        // 12. PATIENT_FILES TABLE
        // File lookups by admission
        // ============================================
        Schema::table('patient_files', function (Blueprint $table) {
            $table->index('admission_id', 'idx_patient_files_admission_id');
            $table->index('patient_id', 'idx_patient_files_patient_id');
        });

        // ============================================
        // 13. APPOINTMENT_SLOTS TABLE
        // Date-based slot queries
        // ============================================
        Schema::table('appointment_slots', function (Blueprint $table) {
            // Date filtering: ->whereDate('date', today())
            $table->index('date', 'idx_appointment_slots_date');

            // Physician slots
            $table->index('physician_id', 'idx_appointment_slots_physician_id');

            // Composite for physician schedule views
            $table->index(['physician_id', 'date'], 'idx_appointment_slots_physician_date');
        });

        // ============================================
        // 14. APPOINTMENTS TABLE
        // Status and slot filtering
        // ============================================
        Schema::table('appointments', function (Blueprint $table) {
            // Status filtering: ->where('status', 'Booked')
            $table->index('status', 'idx_appointments_status');

            // Slot lookups
            $table->index('appointment_slot_id', 'idx_appointments_slot_id');
        });

        // ============================================
        // 15. BILLINGS TABLE
        // Admission billing queries
        // ============================================
        Schema::table('billings', function (Blueprint $table) {
            $table->index('admission_id', 'idx_billings_admission_id');
            $table->index('status', 'idx_billings_status');
        });

        // ============================================
        // 16. ADMISSION_BILLING_INFOS TABLE
        // Admission billing info lookups
        // ============================================
        Schema::table('admission_billing_infos', function (Blueprint $table) {
            $table->index('admission_id', 'idx_admission_billing_infos_admission_id');
        });
    }

    public function down(): void
    {
        // Remove indexes in reverse order

        Schema::table('admission_billing_infos', function (Blueprint $table) {
            $table->dropIndex('idx_admission_billing_infos_admission_id');
        });

        Schema::table('billings', function (Blueprint $table) {
            $table->dropIndex('idx_billings_admission_id');
            $table->dropIndex('idx_billings_status');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('idx_appointments_status');
            $table->dropIndex('idx_appointments_slot_id');
        });

        Schema::table('appointment_slots', function (Blueprint $table) {
            $table->dropIndex('idx_appointment_slots_date');
            $table->dropIndex('idx_appointment_slots_physician_id');
            $table->dropIndex('idx_appointment_slots_physician_date');
        });

        Schema::table('patient_files', function (Blueprint $table) {
            $table->dropIndex('idx_patient_files_admission_id');
            $table->dropIndex('idx_patient_files_patient_id');
        });

        Schema::table('nursing_care_plans', function (Blueprint $table) {
            $table->dropIndex('idx_nursing_care_plans_admission_id');
            $table->dropIndex('idx_nursing_care_plans_nurse_id');
            $table->dropIndex('idx_nursing_care_plans_status');
        });

        Schema::table('treatment_plans', function (Blueprint $table) {
            $table->dropIndex('idx_treatment_plans_admission_id');
            $table->dropIndex('idx_treatment_plans_status');
        });

        Schema::table('billable_items', function (Blueprint $table) {
            $table->dropIndex('idx_billable_items_admission_id');
            $table->dropIndex('idx_billable_items_status');
            $table->dropIndex('idx_billable_items_type');
        });

        Schema::table('patient_movements', function (Blueprint $table) {
            $table->dropIndex('idx_patient_movements_admission_id');
            $table->dropIndex('idx_patient_movements_ended_at');
        });

        Schema::table('daily_time_records', function (Blueprint $table) {
            $table->dropIndex('idx_dtr_user_id');
            $table->dropIndex('idx_dtr_time_in');
            $table->dropIndex('idx_dtr_time_out');
            $table->dropIndex('idx_dtr_status');
            $table->dropIndex('idx_dtr_user_time_in');
        });

        Schema::table('transfer_requests', function (Blueprint $table) {
            $table->dropIndex('idx_transfer_requests_status');
            $table->dropIndex('idx_transfer_requests_admission_id');
        });

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropIndex('idx_inventory_items_quantity');
        });

        Schema::table('medicines', function (Blueprint $table) {
            $table->dropIndex('idx_medicines_stock');
        });

        Schema::table('beds', function (Blueprint $table) {
            $table->dropIndex('idx_beds_status');
        });

        Schema::table('nurses', function (Blueprint $table) {
            $table->dropIndex('idx_nurses_status');
            $table->dropIndex('idx_nurses_station_id');
            $table->dropIndex('idx_nurses_nurse_type_id');
            $table->dropIndex('idx_nurses_station_status');
            $table->dropIndex('idx_nurses_type_station_status');
        });

        Schema::table('admissions', function (Blueprint $table) {
            $table->dropIndex('idx_admissions_station_id');
            $table->dropIndex('idx_admissions_attending_physician_id');
            $table->dropIndex('idx_admissions_patient_id');
            $table->dropIndex('idx_admissions_admission_date');
            $table->dropIndex('idx_admissions_station_status');
            $table->dropIndex('idx_admissions_physician_status');
            $table->dropIndex('idx_admissions_patient_status');
        });
    }
};
