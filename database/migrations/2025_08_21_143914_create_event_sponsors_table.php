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
        Schema::create('event_sponsors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('sponsor_id')->constrained()->onDelete('cascade');
            $table->enum('sponsorship_level', ['platinum', 'gold', 'silver', 'bronze']);
            $table->decimal('contribution_amount', 15, 2);
            $table->timestamps();
            $table->unique(['event_id', 'sponsor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_sponsors');
    }
};
