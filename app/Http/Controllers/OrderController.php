<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    public OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        // Validate request payload.
        $validated = $request->all();
dd($validated);
        //store order.
        $order = $this->orderService->createOrder($validated);

        return ($order)
            ? response()->json(['message' => 'The order has been created successfully.!'])
            : response()->json(['message' => 'The order cannot be created.!']);

    }

}
