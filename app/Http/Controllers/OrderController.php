<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'             => ['array', 'required'],
            'items.*.productId' => ['required', 'string', 'exists:products,id'],
            'items.*.quantity'  => ['required', 'integer', 'min:1'],
        ]);

        //store order.

        //store order items.

        //check order items (product) ingredients.

        //calculate ingredient consumption for the ordered quantity

        // update stock

        // update stock_transaction


        Order::create([$validated]);
    }


}
