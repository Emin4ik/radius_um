<?php

namespace App\Livewire;

use App\Models\Merchant;
use App\Models\Offers;
use App\Models\Store;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Loader extends Component
{
    public $isLoading = false;
    public function loadGoods(){
        $this->isLoading = true;
        $page = 1;
        $this->getGoods($page);
        $this->isLoading = false;
    }

    protected function getGoods($page){
        $total_pages = 1;
        $url = 'https://umico.az/catalog/v3/market/products?page='.$page.'&per_page=100&q[opaque_id]=/az/merchant/' . urlencode(session()->get('shop')) . '?page=2&q[seller_marketing_name_id_eq]='.session()->get('shop_id').'&include_fields=id,old_price,retail_price,availability,default_offer_id,img_url_thumbnail,name,categories,manufacturer,avail_check,status,slugged_name,discount,default_marketing_name,ratings,offers,offers,offers.retail_price,offers,offers.marketing_name,offers.merchant_uuid,category_id,product_labels,loyalty_cashback,default_merchant_rating,offers.id,offers.installment_enabled,offers.max_installment_months,offers.avail_check,offers.partner_rating,offers.uuid,offers.old_price,offers.seller_id,offers.seller_marketing_name,best_installment_offer_id,qty,non_refundable,offers.supplier_id,is_bulk_heavy,default_merchant_uuid,categories.path_ids&exclude_fields=ratings.questions,ratings.assessment_id,ratings.product_id&q[search_mode]=seller&q[response_mode]=default&q[default_facets]=true&q[s]=discount_score desc&q[status_in]=active';
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
                // dd($responseData);
                $user_id = Auth::id();
                $merchant = Merchant::where('user_id', $user_id)->first();
                if ($merchant) {
                    $merchant_db_id = $merchant->id;
                } else {
                    $merchant_db_id = null;
                }
                if (isset($responseData['products'])) {
                    Merchant::where('name', session()->get('shop'))->update(['total_rows' => $responseData['meta']['total_entries']]);
                    $total_pages = $responseData['meta']['total_pages'];
                    $products = $responseData['products'];
                    foreach($products as $product){
                        $store = new Store();
                        if($product['default_merchant_uuid'] == session()->get('merchant_id')){
                            $store->positive = 1;
                        }
                        $store->merchant_db_id = $merchant_db_id;
                        $store->user_id = $user_id;
                        $store->default_merchant_uuid = $product['default_merchant_uuid'] ?? null;
                        $store->product_id = $product['id'] ?? null;
                        $store->old_price = $product['old_price'] ?? null;
                        $store->retail_price = $product['retail_price'] ?? null;
                        $store->discount = $product['discount'] ?? null;
                        $store->img_url_thumbnail = $product['img_url_thumbnail'] ?? null;
                        $store->manufacturer = $product['manufacturer'] ?? null;
                        $store->score = $product['score'] ?? null;
                        $store->search_variant_code = $product['search_variant_code'] ?? null;
                        $store->name = $product['name'] ?? null;
                        $store->slugged_name = $product['slugged_name'] ?? null;
                        try {
                            $store->save();

                        } catch (Exception $e) {
                            dd($e->getMessage());
                        }
                        $store_id = $store->id;
                        $offers = $product['offers'];
                        if(count($offers)>0){
                            foreach($offers as $opponent){
                                $offer = new Offers();
                                $offer->merchant_db_id = $merchant_db_id;
                                $offer->user_id = $user_id;
                                $offer->store_id = $store_id;
                                $offer->offer_id = $opponent['id'] ?? null;
                                $offer->offer_uuid = $opponent['uuid'] ?? null;
                                $offer->retail_price = $opponent['retail_price'] ?? null;
                                $offer->offer_merchant_uuid = $opponent['merchant_uuid'] ?? null;
                                $offer->seller_id = $opponent['seller_id'] ?? null;
                                $offer->old_price = $opponent['old_price'] ?? null;
                                $offer->partner_rating = $opponent['partner_rating'] ?? null;
                                $offer->internal_id = $opponent['marketing_name']['internal_id'] ?? null;
                                $offer->name = $opponent['marketing_name']['name'] ?? null;
                                $offer->logo = $opponent['marketing_name']['logo']['thumbnail'] ?? 'https://umico.az/_ipx/_/images/no-image-found.webp' ?? null;
                                try {
                                    $offer->save();
                                } catch (Exception $e) {
                                    dd($e->getMessage());
                                }
                            }
                        }
                    }
                    if($total_pages >= $page){
                        $page++;
                        $this->getGoods($page);
                    }
                } else {
                    dd('error');
                    return false;
                }
            } else {
                dd('error2');
                return false;
            }
        } catch (Exception $e) {
            dd($e);
            return false;
        }
    }

    public function render(){
        return view('livewire.loader');
    }


}
