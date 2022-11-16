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
        $validated = $request->all();

        $order = $this->orderService->createOrder($validated);

        return ($order)
            ? $this->successResponse([], 'The order has been created successfully')
            : $this->errorResponse(401, 'The order cannot be created');

    }

}
