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
        Schema::create('customertthdetail', function (Blueprint $table) {
            $table->tinyIncrements('ID');
            $table->string('TTHNo', 18)->nullable();
            $table->string('TTOTTPNo', 19)->nullable();
            $table->string('Jenis', 13)->nullable();
            $table->tinyInteger('Qty')->nullable();
            $table->string('Unit', 6)->nullable();

            $table->index('TTOTTPNo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customertthdetail');
    }
};

