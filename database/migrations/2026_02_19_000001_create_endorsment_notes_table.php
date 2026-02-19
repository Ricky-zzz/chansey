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
        // Append-only notes table for endorsement amendments
        // Allows creator & incoming nurse to add notes without editing original endorsement
        Schema::create('endorsment_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('endorsment_id')
                ->constrained('endorsments')
                ->cascadeOnDelete()
                ->name('fk_endorsment_notes_endorsment');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->name('fk_endorsment_notes_user');

            $table->text('note'); // The amendment/correction note
            $table->string('note_type')->default('amendment'); // 'amendment', 'observation', 'clarification'

            $table->timestamp('created_at')->useCurrent(); // Immutable: created_at only, no updates

            // Indexes
            $table->index('endorsment_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endorsment_notes');
    }
};
