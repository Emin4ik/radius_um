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
            'merchant' => 'required|max:255'
        ]);
        $result = $this->checkMerchant($validated['merchant']);
        // dd($result);
        if(count($result['merchants']) > 0){
            $request->session()->put('shop', $validated['merchant']);
            $request->session()->put('shop_id', $result['merchants'][0]['internal_id']);
            $request->session()->put('merchant_id', $result['merchants'][0]['partners_ext_ids'][0]);
        }else{
            return redirect()->route('merchant')->with('error', 'No Merchant founded');
        }
        // dd($result);
        if($result){
            $merchant = new Merchant();
            $merchant->user_id = Auth::id();
            $merchant->name = $result['merchants'][0]['name'] ?? null;
            $merchant->internal_id = $result['merchants'][0]['internal_id'] ?? null;
            $merchant->merchant_id = $result['merchants'][0]['partners_ext_ids'][0] ?? null;
            $merchant->uniq_id = $result['merchants'][0]['id'] ?? null;
            $merchant->ext_id = $result['merchants'][0]['ext_id'] ?? null;
            $merchant->thumb = $result['merchants'][0]['logo']['thumbnail'] ?? null;
            $merchant->original = $result['merchants'][0]['logo']['original'] ?? null;
            $merchant->save();
            $merchantId = $merchant->id;
        }else{
            return redirect()->route('merchant')->with('error', 'No Merchant founded');
        }
        $url = 'https://umico.az/catalog/v3/market/products?page=1&per_page=1&q[opaque_id]=/ru/merchant/' . urlencode(session()->get('shop')) . '?page=2&q[seller_marketing_name_id_eq]=' . session()->get('shop_id') . '&include_fields=id&exclude_fields=ratings.questions,ratings.assessment_id,ratings.product_id&q[search_mode]=seller&q[response_mode]=default&q[default_facets]=true&q[s]=discount_score desc&q[status_in]=active';
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Connection' => 'keep-alive',
        ];
        try {
            $response = Http::withHeaders($headers)->get($url);
            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['meta'])) {
                    $meta = $responseData['meta'];
                    Merchant::where('uniq_id', $result['merchants'][0]['id'])->update(['total_rows' => $meta['total_entries']]);
                } else {
                    return redirect()->route('merchant')->with('error', 'Not products founded');
                }
            } else {
                return redirect()->route('merchant')->with('error', 'Not success response');
            }
        } catch (Exception $e) {
            return redirect()->route('merchant')->with('error', $e);
        }
        return redirect()->route('store', ['id' => $merchantId])->with('success', 'New Merchant added successfully');
    }

    protected function checkMerchant($name){
        $url = 'https://umico.az/catalog/v3/market/suggests?q[full_text]=' . urlencode($name) . '&per_page=1&q[opaque_id]=/ru';
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $responseData = $response->json();
                // dd($responseData);
                if (isset($responseData['data']['results'])) {
                    $products = $responseData['data']['results']['products'];
                    $merchants = $responseData['data']['results']['merchants'];
                    return ['products' => $products, 'merchants' => $merchants];
                } else {
                    return redirect()->route('merchant')->with('error', 'No Data exists');
                }
            } else {
                return redirect()->route('merchant')->with('error', 'Not successfull response');
            }
        } catch (Exception $e) {
            return redirect()->route('merchant')->with('error', $e);
        }
    }
}
