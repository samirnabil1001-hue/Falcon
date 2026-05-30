<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('potential_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('country_code', 5)->nullable();
            $table->string('status')->default('new');
            $table->string('source')->nullable();
            $table->timestamp('added_at')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();

            // Create a composite unique index for phone and country_code
            $table->unique(['phone', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potential_customers');
    }
};