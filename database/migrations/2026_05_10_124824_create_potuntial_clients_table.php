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
        Schema::create('potential_clients', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('phone')->nullable();

            $table->string('data_source')->nullable();
            $table->string('service')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->string('status')->default('new');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potential_clients');
    }
};
