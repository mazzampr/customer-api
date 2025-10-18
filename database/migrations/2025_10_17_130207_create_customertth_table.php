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
        Schema::create('customertth', function (Blueprint $table) {
            $table->unsignedTinyInteger('ID')->nullable();
            $table->string('TTHNo', 18)->nullable();
            $table->string('SalesID', 10)->nullable();
            $table->string('TTOTTPNo', 19);
            $table->string('CustID', 11)->nullable();
            $table->string('DocDate', 19)->nullable();
            $table->tinyInteger('Received')->nullable();
            $table->dateTime('ReceivedDate')->nullable();
            $table->string('FailedReason', 100)->nullable();

            $table->primary('TTOTTPNo');
            $table->index('CustID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customertth');
    }
};

