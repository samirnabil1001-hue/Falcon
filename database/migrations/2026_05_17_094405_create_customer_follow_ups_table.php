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
        Schema::create('customer_follow_ups', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('potential_customer_id')
                  ->constrained('potential_customers')
                  ->onDelete('cascade');

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('restrict'); 

            $table->string('status');

            $table->string('reason')->nullable();

            $table->dateTime('next_follow_up_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['potential_customer_id', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_follow_ups');
    }
};