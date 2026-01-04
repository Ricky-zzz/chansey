<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_files', function (Blueprint $table) {

            $table->string('result_type')
                ->nullable()
                ->after('file_name');
            $table->foreignId('medical_order_id')
                ->nullable()
                ->after('admission_id')
                ->constrained('medical_orders')
                ->nullOnDelete();

            $table->text('description')
                ->nullable()
                ->after('result_type');
        });
    }

    public function down(): void
    {
        Schema::table('patient_files', function (Blueprint $table) {
            $table->dropForeign(['medical_order_id']);
            $table->dropColumn(['medical_order_id', 'description','result_type']);
        });
    }
};
