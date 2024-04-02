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
// products
//   offers_count
//       retail_price
//       old_price
//       partner_rating
//       marketing_name['name']
//       marketing_name['logo']['thumbnail']
//   old_price
//   discount
//   retail_price
//   img_url_thumbnail
//   manufacturer
//   score
//   search_variant_code
//   name
//   slugged_name
