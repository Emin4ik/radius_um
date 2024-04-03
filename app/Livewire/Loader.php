<?php

namespace App\Livewire;

use App\Models\Offers;
use App\Models\Store;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Loader extends Component
{

    public function loadGoods(){
        $this->getGoods();
    }

    protected function getGoods(){
        $url = 'https://umico.az/catalog/v3/market/products?page=1&per_page=100&q[opaque_id]=/ru/merchant/' . urlencode(session()->get('shop')) . '?page=2&q[seller_marketing_name_id_eq]='.session()->get('shop_id').'&include_fields=id,old_price,retail_price,availability,default_offer_id,img_url_thumbnail,name,categories,manufacturer,avail_check,status,slugged_name,discount,default_marketing_name,ratings,offers,offers,offers.retail_price,offers,offers.marketing_name,offers.merchant_uuid,category_id,product_labels,loyalty_cashback,default_merchant_rating,offers.id,offers.installment_enabled,offers.max_installment_months,offers.avail_check,offers.partner_rating,offers.uuid,offers.old_price,offers.seller_id,offers.seller_marketing_name,best_installment_offer_id,qty,non_refundable,offers.supplier_id,is_bulk_heavy,default_merchant_uuid,categories.path_ids&exclude_fields=ratings.questions,ratings.assessment_id,ratings.product_id&q[search_mode]=seller&q[response_mode]=default&q[default_facets]=true&q[s]=discount_score desc&q[status_in]=active';
        try {
            $response = Http::get($url);
            if ($response->successful()) {

                $responseData = $response->json();
                // dd($responseData);

                $user_id = Auth::id();
                DB::table('stores')->where('user_id', $user_id)->delete();
                DB::table('offers')->where('user_id', $user_id)->delete();
                if (isset($responseData['products'])) {
                    // $meta = $responseData['meta'];
                    $products = $responseData['products'];
                    // dd($products[0]['offers'][0]['marketing_name']['internal_id']);
                    foreach($products as $product){
                        $store = new Store();
                        $store->user_id = $user_id;
                        $store->default_merchant_uuid = $product['default_merchant_uuid'];
                        $store->product_id = $product['id'];
                        $store->old_price = $product['old_price'];
                        $store->retail_price = $product['retail_price'];
                        $store->discount = $product['discount'];
                        $store->img_url_thumbnail = $product['img_url_thumbnail'];
                        $store->manufacturer = $product['manufacturer'];
                        $store->score = $product['score'];
                        $store->search_variant_code = $product['search_variant_code'];
                        $store->name = $product['name'];
                        $store->slugged_name = $product['slugged_name'];
                        try {
                            $store->save();
                        } catch (Exception $e) {
                            dd($e->getMessage());
                        }
                        $store_id = $store->id;
                        $offers = $product['offers'];
                        if(count($offers)>0){
                            foreach($offers as $opponent){
                                //dd($offer['marketing_name']['internal_id']);
                                $offer = new Offers();
                                $offer->user_id = $user_id;
                                $offer->store_id = $store_id;
                                $offer->offer_id = $opponent['id'];
                                $offer->offer_uuid = $opponent['uuid'];
                                $offer->retail_price = $opponent['retail_price'];
                                $offer->offer_merchant_uuid = $opponent['merchant_uuid'];
                                $offer->seller_id = $opponent['seller_id'];
                                $offer->old_price = $opponent['old_price'];
                                $offer->partner_rating = $opponent['partner_rating'];
                                $offer->internal_id = $opponent['marketing_name']['internal_id'];
                                $offer->name = $opponent['marketing_name']['name'];
                                $offer->logo = $opponent['marketing_name']['logo']['thumbnail'];
                                try {
                                    $offer->save();
                                } catch (Exception $e) {
                                    dd($e->getMessage());
                                }
                            }
                        }
                    }
                    // dd( ['meta' => $meta, 'products' => $products]);
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


    // $url = 'https://umico.az/catalog/v3/market/products?page=1&per_page=1&q[opaque_id]=/ru/merchant/' . urlencode(session()->get('shop')) . '?page=2&q[seller_marketing_name_id_eq]='.session()->get('shop_id').'&include_fields=id,old_price,retail_price,availability,default_offer_id,img_url_thumbnail,name,categories,manufacturer,avail_check,status,slugged_name,discount,default_marketing_name,ratings,offers,offers,offers.retail_price,offers,offers.marketing_name,offers.merchant_uuid,category_id,product_labels,loyalty_cashback,default_merchant_rating,offers.id,offers.installment_enabled,offers.max_installment_months,offers.avail_check,offers.partner_rating,offers.uuid,offers.old_price,offers.seller_id,offers.seller_marketing_name,best_installment_offer_id,qty,non_refundable,offers.supplier_id,is_bulk_heavy,default_merchant_uuid,categories.path_ids&exclude_fields=ratings.questions,ratings.assessment_id,ratings.product_id&q[search_mode]=seller&q[response_mode]=default&q[default_facets]=true&q[s]=discount_score desc&q[status_in]=active';
    // try {
    //     $response = Http::get($url);
    //     if ($response->successful()) {
    //         // If the request is successful, return true indicating data is available
    //         $responseData = $response->json();

    //         // Check if 'results' key exists in the response data
    //         if (isset($responseData['products'])) {
    //             // Extract products and merchants data
    //             $meta = $responseData['meta'];
    //             $products = $responseData['products'];

    //             // Return an array containing both products and merchants data
    //             dd( ['meta' => $meta, 'products' => $products]);
    //         } else {
    //             // If 'results' key is not found, return false indicating no data is available
    //             return false;
    //         }
    //     } else {
    //         // If the request is not successful, return false indicating no data is available
    //         return false;
    //     }
    // } catch (Exception $e) {
    //     // Handle exceptions if any, and return false
    //     return false;
    // }


    // $url = 'https://umico.az/catalog/v3/market/products?page=1&per_page=1&q[opaque_id]=/ru/merchant/' . urlencode(session()->get('shop')) . '?page=2&q[seller_marketing_name_id_eq]=' . session()->get('shop_id') . '&include_fields=id&exclude_fields=ratings.questions,ratings.assessment_id,ratings.product_id&q[search_mode]=seller&q[response_mode]=default&q[default_facets]=true&q[s]=discount_score desc&q[status_in]=active';
    //     try {
    //         $response = Http::get($url);
    //         if ($response->successful()) {
    //             $responseData = $response->json();
    //             if (isset($responseData['products'])) {
    //                 $meta = $responseData['meta'];
    //                 dd( ['meta' => $meta]);
    //             } else {
    //                 return false;
    //             }
    //         } else {
    //             return false;
    //         }
    //     } catch (Exception $e) {
    //         return false;
    //     }

}
