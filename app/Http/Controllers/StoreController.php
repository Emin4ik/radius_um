<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index($id, Request $request)
    {
        $sort = 'stores.id';
        $asc = 'asc';
        if( ($request->input('sortBy')) || ($request->input('sortBy') == 'negative' && $request->input('sortBy') == 'positive') ){
            $sort = $request->input('sortBy');
            if($sort == 'negative'){
                $sort = 'stores.positive';
                $asc = 'asc';
            }else{
                $sort = 'stores.positive';
                $asc = 'desc';
            }
        }
        // dd($sort);
        $merchant = Merchant::findOrFail($id);
        session()->put('shop', $merchant['name']);
        session()->put('shop_id', $merchant['internal_id']);

        $products = DB::table('stores')
            ->select('stores.id', 'stores.positive' ,'stores.default_merchant_uuid' ,'stores.old_price', 'stores.retail_price', 'stores.discount', 'stores.img_url_thumbnail', 'stores.manufacturer', 'stores.score', 'stores.name')
            ->orderBy($sort, $asc)
            ->paginate(25);

        // if ($request->has('sort') && $request->sort === 'positive') {
        //     $productsQuery->orderBy('stores.positive', 'desc');
        // }

        $offers = DB::table('offers')
            ->select('offers.store_id', 'offers.name', 'offers.retail_price', 'offers.old_price', 'offers.partner_rating', 'offers.name AS offer_name', 'offers.logo', 'offers.internal_id')
            ->orderBy('offers.retail_price', 'asc')
            ->orderBy('offers.old_price', 'asc')
            ->get();

        return view( 'store.index', [
                'products' => $products,
                'offers' => $offers,
                'merchant' => $merchant,
                'sort' => $sort
            ]
        );
    }
}

