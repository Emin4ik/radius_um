<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index($id)
    {
        $merchant = Merchant::findOrFail($id);
        session()->put('shop', $merchant['name']);
        session()->put('shop_id', $merchant['internal_id']);

        // $products = DB::table('stores')
        //     ->leftJoin('offers', 'stores.id', '=', 'offers.store_id')
        //     ->select('stores.old_price', 'stores.retail_price', 'stores.discount', 'stores.img_url_thumbnail', 'stores.manufacturer', 'stores.score', 'stores.name', 'offers.retail_price', 'offers.old_price', 'offers.partner_rating', 'offers.name AS offer_name', 'offers.logo', 'offers.internal_id')
        //     ->orderBy('stores.id')
        //     ->get()
        //     ->groupBy('id');
        $products = DB::table('stores')
            ->select('stores.old_price', 'stores.retail_price', 'stores.discount', 'stores.img_url_thumbnail', 'stores.manufacturer', 'stores.score', 'stores.name')
            ->get();
        // dd($products);
        return view( 'store.index', [
                'products' => $products,
                'merchant' => $merchant
            ]
        );
    }
}

