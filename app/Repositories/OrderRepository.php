<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    private Order $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function create(array $data = []): Order
    {
        return $this->model::create($data);
    }
}
