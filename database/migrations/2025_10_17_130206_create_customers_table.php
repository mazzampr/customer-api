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
        Schema::create('customer', function (Blueprint $table) {
            $table->string('CustID', 11)->primary();
            $table->string('Name', 17)->nullable();
            $table->string('Address', 37)->nullable();
            $table->string('BranchCode', 3)->nullable();
            $table->string('PhoneNo', 14)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
