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
        Schema::create('session_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained()->onDelete('cascade');
            $table->enum('attendance_status', ['registered', 'attended', 'absent'])->default('registered');
            $table->timestamps();
            $table->unique(['event_session_id', 'participant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_participants');
    }
};
