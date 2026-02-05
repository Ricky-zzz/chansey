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
        Schema::table('medical_orders', function (Blueprint $table) {
            $table->boolean('dispensed')->default(false)->after('status');
            $table->foreignId('dispensed_by_user_id')->nullable()->after('dispensed');
            $table->timestamp('dispensed_at')->nullable()->after('dispensed_by_user_id');

            // Add foreign key constraint
            $table->foreign('dispensed_by_user_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_orders', function (Blueprint $table) {
            $table->dropForeignKey(['dispensed_by_user_id']);
            $table->dropColumn(['dispensed', 'dispensed_by_user_id', 'dispensed_at']);
        });
    }
};
