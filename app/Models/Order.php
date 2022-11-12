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

    protected $fillable = ['status', 'merchant_id'];

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model){
            $model->merchant_id = "5616c9e6-6233-11ed-8cb2-d4d252eedae0";
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_order')->withPivot('quantity');
    }

}
