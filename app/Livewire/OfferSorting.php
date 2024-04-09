<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OfferSorting extends Component
{
    public $offers;

    public function mount()
    {
        // Load initial offers data
        $this->loadOffers();
    }

    public function loadOffers()
    {
        $this->offers = DB::table('offers')
            ->select('offers.store_id', 'offers.name', 'offers.retail_price', 'offers.old_price', 'offers.partner_rating', 'offers.name AS offer_name', 'offers.logo', 'offers.internal_id')
            ->orderBy('offers.retail_price', 'asc')
            ->orderBy('offers.old_price', 'asc')
            ->get();
    }

    public function sort($type)
    {
        if ($type == 'positive') {
            $this->offers = $this->offers->sortBy('default_merchant_uuid');
        } elseif ($type == 'negative') {
            $this->offers = $this->offers->sortByDesc('default_merchant_uuid');
        }
    }

    public function render()
    {
        return view('livewire.offer-sorting');
    }
}
