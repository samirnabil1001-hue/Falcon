<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CompanyService;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       DB::table('potential_customers')
            ->whereNull('service_type')
            ->update(['service_type' => CompanyService::OTHERS->value]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
