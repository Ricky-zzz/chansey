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
    Schema::create('medicines', function (Blueprint $table) {
        $table->id();
        
        // Identification
        $table->string('generic_name')->index(); // e.g. Paracetamol
        $table->string('brand_name')->nullable(); // e.g. Biogesic
        
        // Clinical Details
        $table->string('dosage'); 
        $table->string('form', );
        
        // Inventory (Ward Stock)
        $table->integer('stock_on_hand')->default(0);
        $table->integer('critical_level')->default(20);

        $table->decimal('price', 10, 2)->default(0.00);
        
        $table->date('expiry_date')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
