<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;

class OrderService
{
    private OrderRepository $repository;

    private ProductService $productService;

    public function __construct(OrderRepository $repository, ProductService $productService){
        $this->repository = $repository;
        $this->productService = $productService;
    }

    public function createOrder(array $data): bool
    {
        DB::beginTransaction();
        try {
                $order = $this->repository->create();

                $products = $this->productService->prepareProductQuantity($data['products']);

                $order->products()->attach($products);

                DB::commit();

                return true;
            //TODO:: update stock_transaction, make an observer to update stock transaction.
        } catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }
}
