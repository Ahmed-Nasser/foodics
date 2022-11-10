<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
       'name'
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

}
