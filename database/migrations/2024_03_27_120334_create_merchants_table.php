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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name', 255)->unique();
            $table->integer('internal_id');
            $table->integer('uniq_id');
            $table->string('ext_id', 72);
            $table->integer('total_rows')->nullable();
            $table->string('thumb', 120);
            $table->string('original', 120);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
