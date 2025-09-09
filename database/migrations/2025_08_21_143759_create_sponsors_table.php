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
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_person');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->decimal('contribution_amount', 15, 2)->default(0);
            $table->enum('sponsorship_level', ['platinum', 'gold', 'silver', 'bronze'])->default('bronze');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};
