<?php

namespace App\Livewire;

use Exception;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Loader extends Component
{
  
    public function loadGoods(){
        dd();
    }

    protected function getGoods($name = 'Ezzy Shop')
    {
        $url = 'https://umico.az/catalog/v3/market/products?page=1&per_page=1&q[opaque_id]=/ru/merchant/' . urlencode($name) . '?page=2&q[seller_marketing_name_id_eq]=4195&include_fields=id,old_price,retail_price,availability,default_offer_id,img_url_thumbnail,name,categories,manufacturer,avail_check,status,slugged_name,discount,default_marketing_name,ratings,offers,offers,offers.retail_price,offers,offers.marketing_name,offers.merchant_uuid,category_id,product_labels,loyalty_cashback,default_merchant_rating,offers.id,offers.installment_enabled,offers.max_installment_months,offers.avail_check,offers.partner_rating,offers.uuid,offers.old_price,offers.seller_id,offers.seller_marketing_name,best_installment_offer_id,qty,non_refundable,offers.supplier_id,is_bulk_heavy,default_merchant_uuid,categories.path_ids&exclude_fields=ratings.questions,ratings.assessment_id,ratings.product_id&q[search_mode]=seller&q[response_mode]=default&q[default_facets]=true&q[s]=discount_score desc&q[status_in]=active';
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                // If the request is successful, return true indicating data is available
                $responseData = $response->json();

                // Check if 'results' key exists in the response data
                if (isset($responseData['products'])) {
                    // Extract products and merchants data
                    $meta = $responseData['meta'];
                    $products = $responseData['products'];

                    // Return an array containing both products and merchants data
                    return ['meta' => $meta, 'products' => $products];
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
        // https://umico.az/catalog/v3/market/products?page=1&per_page=24&q[opaque_id]=/ru/merchant/EZZY-SHOP?page=2&q[seller_marketing_name_id_eq]=4195&include_fields=id,old_price,retail_price,availability,default_offer_id,img_url_thumbnail,name,categories,manufacturer,avail_check,status,slugged_name,discount,default_marketing_name,ratings,offers,offers,offers.retail_price,offers,offers.marketing_name,offers.merchant_uuid,category_id,product_labels,loyalty_cashback,default_merchant_rating,offers.id,offers.installment_enabled,offers.max_installment_months,offers.avail_check,offers.partner_rating,offers.uuid,offers.old_price,offers.seller_id,offers.seller_marketing_name,best_installment_offer_id,qty,non_refundable,offers.supplier_id,is_bulk_heavy,default_merchant_uuid,categories.path_ids&exclude_fields=ratings.questions,ratings.assessment_id,ratings.product_id&q[search_mode]=seller&q[response_mode]=default&q[default_facets]=true&q[s]=discount_score desc&q[status_in]=active

    }

    public function render()
    {
        return view('livewire.loader');
    }

}
