<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Livewire\Loader;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('merchant', [MerchantController::class, 'index'])->name('merchant');

Route::post('merchant.create', [MerchantController::class, 'create'])->name('merchant.create');

Route::get('store/{id}', [StoreController::class, 'index'])->name('store');

require __DIR__.'/auth.php';





//https://umico.az/catalog/v3/market/products?page=1&per_page=24&q[opaque_id]=/ru/merchant/4195-EZZY-SHOP?page=2&q[seller_marketing_name_id_eq]=4195&include_fields=id,old_price,retail_price,availability,default_offer_id,img_url_thumbnail,name,categories,manufacturer,avail_check,status,slugged_name,discount,default_marketing_name,ratings,offers,offers,offers.retail_price,offers,offers.marketing_name,offers.merchant_uuid,category_id,product_labels,loyalty_cashback,default_merchant_rating,offers.id,offers.installment_enabled,offers.max_installment_months,offers.avail_check,offers.partner_rating,offers.uuid,offers.old_price,offers.seller_id,offers.seller_marketing_name,best_installment_offer_id,qty,non_refundable,offers.supplier_id,is_bulk_heavy,default_merchant_uuid,categories.path_ids&exclude_fields=ratings.questions,ratings.assessment_id,ratings.product_id&q[search_mode]=seller&q[response_mode]=default&q[default_facets]=true&q[s]=discount_score desc&q[status_in]=active

//https://umico.az/catalog/v3/market/products?page=1&per_page=24&q[opaque_id]=/ru/merchant/EZZY-SHOP?page=2&q[seller_marketing_name_id_eq]=4195&include_fields=id,old_price,retail_price,availability,default_offer_id,img_url_thumbnail,name,categories,manufacturer,avail_check,status,slugged_name,discount,default_marketing_name,ratings,offers,offers,offers.retail_price,offers,offers.marketing_name,offers.merchant_uuid,category_id,product_labels,loyalty_cashback,default_merchant_rating,offers.id,offers.installment_enabled,offers.max_installment_months,offers.avail_check,offers.partner_rating,offers.uuid,offers.old_price,offers.seller_id,offers.seller_marketing_name,best_installment_offer_id,qty,non_refundable,offers.supplier_id,is_bulk_heavy,default_merchant_uuid,categories.path_ids&exclude_fields=ratings.questions,ratings.assessment_id,ratings.product_id&q[search_mode]=seller&q[response_mode]=default&q[default_facets]=true&q[s]=discount_score desc&q[status_in]=active


// https://umico.az/catalog/v3/market/suggests?q[full_text]=ezzy%20shop&per_page=1&q[opaque_id]=/ru