<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'default_merchant_uuid',
        'user_id',
        'product_id',
        'old_price',
        'retail_price',
        'discount',
        'img_url_thumbnail',
        'manufacturer',
        'score',
        'search_variant_code',
        'name',
        'slugged_name'
    ];
}
