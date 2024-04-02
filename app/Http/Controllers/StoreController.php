<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index($id)
    {
        $merchant = Merchant::findOrFail($id);
        session()->put('shop', $merchant['name']);
        session()->put('shop_id', $merchant['internal_id']);
        return view( 'store.index', [
                'merchant' => $merchant
            ]
        );
    }
}

