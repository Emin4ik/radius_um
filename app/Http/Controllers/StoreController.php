<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index($id, Request $request)
    {
        $merchant = Merchant::findOrFail($id);
        session()->put('merchant_id', $merchant->merchant_id);
        $sort = 'id';
        $asc = 'asc';
        if ($request->has('sortBy')) {
            if ($request->input('sortBy') === 'negative') {
                $sort = 'negative';
                $asc = 'asc';
            } elseif ($request->input('sortBy') === 'positive') {
                $sort = 'positive';
                $asc = 'desc';
            }
        } else {
            $sort = 'positive';
        }

        session()->put('shop', $merchant['name']);
        session()->put('shop_id', $merchant['internal_id']);

        $products = DB::table('stores')
            ->select('stores.id', 'stores.positive' ,'stores.default_merchant_uuid' ,'stores.old_price', 'stores.retail_price', 'stores.discount', 'stores.img_url_thumbnail', 'stores.manufacturer', 'stores.score', 'stores.name')
            ->orderBy('positive', $asc)
            ->paginate(25);

        $offers = DB::table('offers')
            ->select('offers.store_id', 'offers.name', 'offers.retail_price', 'offers.old_price', 'offers.partner_rating', 'offers.name AS offer_name', 'offers.logo', 'offers.internal_id')
            ->orderBy('offers.retail_price', 'asc')
            ->orderBy('offers.old_price', 'asc')
            ->get();
        // dd($merchant);
        return view( 'store.index', [
                'products' => $products,
                'offers' => $offers,
                'merchant' => $merchant,
                'sort' => $sort
            ]
        );
    }
}

