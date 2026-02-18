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
        // Audit trail for endorsement views
        // Logs every time someone views an endorsement (including multiple views by same person)
        Schema::create('endorsment_viewers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('endorsment_id')->constrained('endorsments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Who viewed it
            $table->timestamp('viewed_at')->useCurrent(); // When they viewed it

            // Indexes for quick lookups
            $table->index(['endorsment_id', 'user_id']);
            $table->index('viewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endorsment_viewers');
    }
};
