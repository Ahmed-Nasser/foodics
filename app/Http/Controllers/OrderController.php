<?php

namespace App\Http\Controllers;

use App\Models\IngredientProduct;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'products'             => ['array', 'required'],
            'products.*.productId' => ['required', 'string', 'exists:products,id'],
            'products.*.quantity'  => ['required', 'integer', 'min:1'],
        ]);

        /**
        //store order.
        $products = collect($validated['products'])->map(function ($product){
            return [
                $product['productId'] => ['quantity' => $product['quantity']]
            ];
        });

        $order = new  Order();

        $order->save();

        //store order products.

        $order->products()->attach(Arr::collapse($products));
        **/

        //check order products (product) ingredients.

        dd(IngredientProduct::WhereIn('product_id', ['aeae07ee-75e3-4f81-8b68-8329f3dbd4e1'])->get());

        //calculate ingredient consumption for the ordered quantity

        // update stock

        // update stock_transaction


        Order::create([$validated]);
    }


}
