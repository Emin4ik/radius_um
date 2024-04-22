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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->integer('merchant_db_id');
            $table->string('default_merchant_uuid', 50);
            $table->boolean('positive')->default(false);
            $table->integer('user_id');
            $table->integer('product_id');
            $table->float('old_price')->nullable();
            $table->float('retail_price')->nullable();
            $table->float('discount')->nullable();
            $table->string('img_url_thumbnail', 100)->nullable();
            $table->string('manufacturer', 90)->nullable();
            $table->float('score')->nullable();
            $table->string('search_variant_code', 30);
            $table->string('name', 120);
            $table->string('slugged_name', 120)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
