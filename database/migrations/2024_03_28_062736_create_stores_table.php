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
            $table->string('default_merchant_uuid', 50);
            $table->integer('user_id');
            $table->integer('product_id');
            $table->float('old_price');
            $table->float('retail_price');
            $table->float('discount');
            $table->string('img_url_thumbnail', 100);
            $table->string('manufacturer', 90);
            $table->float('score');
            $table->string('search_variant_code', 30);
            $table->string('name', 120);
            $table->string('slugged_name', 120);
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
