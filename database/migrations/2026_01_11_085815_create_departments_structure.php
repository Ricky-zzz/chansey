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
    Schema::create('departments', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); 
        $table->string('description')->nullable();
        $table->timestamps();
    });

    Schema::table('physicians', function (Blueprint $table) {
        $table->dropColumn('specialization'); 
        $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
    });

    Schema::table('appointments', function (Blueprint $table) {
        $table->dropColumn('department'); 
        $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeignIdFor('departments'); 
            $table->dropColumn('department_id'); 
            $table->string('department')->nullable(); 
        });

        Schema::table('physicians', function (Blueprint $table) {
            $table->dropForeignIdFor('departments'); 
            $table->dropColumn('department_id'); 
            $table->string('specialization')->nullable(); 
        });

        Schema::dropIfExists('departments');
    }
};
