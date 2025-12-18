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
        Schema::table('esp32s', function (Blueprint $table) {
            $table->decimal('value1', 10, 2)->nullable()->change();
            $table->decimal('value2', 10, 2)->nullable()->change();
            $table->decimal('value3', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('esp32s', function (Blueprint $table) {
            $table->string('value1')->nullable()->change();
            $table->string('value2')->nullable()->change();
            $table->string('value3')->nullable()->change();
        });
    }
};



