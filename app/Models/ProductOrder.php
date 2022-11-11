<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOrder extends Pivot
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];
}
