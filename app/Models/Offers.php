<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'offer_id',
        'offer_uuid',
        'retail_price',
        'offer_merchant_uuid',
        'seller_id',
        'old_price',
        'partner_rating',
        'internal_id',
        'name',
        'logo'
    ];

}
