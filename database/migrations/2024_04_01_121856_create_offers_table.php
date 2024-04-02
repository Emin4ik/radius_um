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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('store_id');
            $table->integer('offer_id');
            $table->string('offer_uuid', 40);
            $table->float('retail_price');
            $table->string('offer_merchant_uuid', 40);
            $table->string('seller_id', 40);
            $table->float('old_price');
            $table->float('partner_rating');
            $table->integer('internal_id');
            $table->string('name', 40);
            $table->string('logo', 90);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
