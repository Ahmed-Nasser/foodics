<?php

namespace App\Http\Controllers;

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

       dd($validated);
    }
}
