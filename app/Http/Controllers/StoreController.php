<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index($id)
    {
        return view( 'store.index', [
                'merchant' => Merchant::findOrFail($id)
            ]
        );
    }
}
