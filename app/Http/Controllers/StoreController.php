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
            ->paginate(10);

        $positive_count = 0;
        $total = 0;
        $store_rating = '';
        $all_products = DB::table('stores')
            ->select('stores.id', 'stores.positive' ,'stores.default_merchant_uuid' ,'stores.old_price', 'stores.retail_price', 'stores.discount', 'stores.img_url_thumbnail', 'stores.manufacturer', 'stores.score', 'stores.name')
            ->orderBy('positive', $asc)
            ->get();
        foreach($all_products as $product){
            $total++;
            if($product->default_merchant_uuid == $merchant['merchant_id']){
                $positive_count++;
            }
        }
        $negative_count = $total - $positive_count;
        // dd($count);
        $offers = DB::table('offers')
            ->select('offers.store_id', 'offers.name', 'offers.offer_merchant_uuid', 'offers.retail_price', 'offers.old_price', 'offers.partner_rating', 'offers.name AS offer_name', 'offers.logo', 'offers.internal_id')
            ->orderBy('offers.retail_price', 'asc')
            ->orderBy('offers.old_price', 'asc')
            ->get();
        // dd(session()->get('merchant_id'));
        foreach($offers as $offer){
            if($offer->offer_merchant_uuid == session()->get('merchant_id')){
                $store_rating = $offer->partner_rating;
                break;
            }else{
                $store_rating = 'unknown';
            }
        }
        return view( 'store.index', [
                'products' => $products,
                'offers' => $offers,
                'merchant' => $merchant,
                'sort' => $sort,
                'count' => $positive_count,
                'negative_count' => $negative_count,
                'store_rating' => $store_rating
            ]
        );
    }
}

