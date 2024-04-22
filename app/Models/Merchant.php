<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'merchant_db_id',
        'internal_id',
        'uniq_id',
        'ext_id',
        'thumb',
        'original'
    ];

}
