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
        Schema::create('otc_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId')->unique(); // Ensure one OTC per user
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->string('otc_passcode', 6);
            $table->timestamps();
            $table->timestamp('expires_at')->nullable(); // Add expires_at column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otc_user');
    }
};
