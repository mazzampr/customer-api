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
        Schema::create('mobileconfig', function (Blueprint $table) {
            $table->tinyIncrements('ID');
            $table->string('BranchCode', 3)->nullable();
            $table->string('Name', 11)->nullable();
            $table->string('Description', 11)->nullable();
            $table->string('Value', 70)->nullable();

            $table->index('BranchCode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobileconfig');
    }
};

