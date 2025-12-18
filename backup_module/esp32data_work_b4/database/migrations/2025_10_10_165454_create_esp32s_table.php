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
        Schema::create('esp32s', function (Blueprint $table) {
            $table->id();
            $table->string('sensor')->nullable();
            $table->string('location')->nullable();
            $table->string('value1')->nullable();
            $table->string('value2')->nullable();
            $table->string('value3')->nullable();        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esp32s');
    }
};
