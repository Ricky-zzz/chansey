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
        Schema::create('memos', function (Blueprint $table) {
            $table->id();

            // Who sent it? (Link to the user/nurse)
            $table->foreignId('created_by_user_id')->constrained('users')->index();

            $table->string('title');
            $table->text('content');

            // All nullable, All JSON. If null, it means "Don't send to this group".
             $table->json('target_roles')->nullable();    // ["Head", "Staff"]
             $table->json('target_units')->nullable();    // [1, 2]
             $table->json('target_stations')->nullable(); // [5, 8, 9]

            $table->string('attachment_path')->nullable(); // PDF/Image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memos');
    }
};
