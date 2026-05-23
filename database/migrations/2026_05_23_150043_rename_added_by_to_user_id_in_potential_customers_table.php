<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('potential_customers', function (Blueprint $schemaTable) {
            $schemaTable->renameColumn('user_id', 'user_id');
        });
    }

    public function down(): void
    {
        Schema::table('potential_customers', function (Blueprint $schemaTable) {
            $schemaTable->renameColumn('user_id', 'user_id');
        });
    }
};