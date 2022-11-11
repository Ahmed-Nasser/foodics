<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['status'];

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_order')->withPivot('quantity');
    }

}
