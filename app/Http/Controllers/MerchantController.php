<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MerchantController extends Controller
{
    public function index(){
        return view('merchant.index');
    }

    public function create(Request $request){
        $validated = $request->validate([
            'merchant' => 'required|max:255' // |unique:merchants
        ]);
        $request->session()->put('shop', $validated['merchant']);
        $result = $this->checkMerchant($validated['merchant']);
        $request->session()->put('shop_id', $result['merchants'][0]['internal_id']);
        if($result){
            $merchant = new Merchant();
            $merchant->user_id = Auth::id();
            $merchant->name = $result['merchants'][0]['name'];
            $merchant->internal_id = $result['merchants'][0]['internal_id'];
            $merchant->uniq_id = $result['merchants'][0]['id'];
            $merchant->ext_id = $result['merchants'][0]['ext_id'];
            $merchant->thumb = $result['merchants'][0]['logo']['thumbnail'];
            $merchant->original = $result['merchants'][0]['logo']['original'];
            $merchant->save();
            $merchantId = $merchant->id;
        }else{
            return redirect()->route('merchant')->with('error', 'No Merchant founded');
        }
        return redirect()->route('store', ['id' => $merchantId])->with('success', 'New Merchant added successfully');
    }

    protected function checkMerchant($name='Ezzy Shop'){
        $url = 'https://umico.az/catalog/v3/market/suggests?q[full_text]=' . urlencode($name) . '&per_page=1&q[opaque_id]=/ru';
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                // If the request is successful, return true indicating data is available
                $responseData = $response->json();

                // Check if 'results' key exists in the response data
                if (isset($responseData['data']['results'])) {
                    // Extract products and merchants data
                    $products = $responseData['data']['results']['products'];
                    $merchants = $responseData['data']['results']['merchants'];

                    // Return an array containing both products and merchants data
                    return ['products' => $products, 'merchants' => $merchants];
                } else {
                    // If 'results' key is not found, return false indicating no data is available
                    return false;
                }
            } else {
                // If the request is not successful, return false indicating no data is available
                return false;
            }
        } catch (Exception $e) {
            // Handle exceptions if any, and return false
            return false;
        }
        // https://umico.az/catalog/v3/market/suggests?q[full_text]=ezzy%20shop&per_page=1&q[opaque_id]=/ru
    }
}
