<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Ingredient extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'amount',
        'type',
    ];

    protected static function newFactory(): IngredientFactory
    {
        return IngredientFactory::new();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }
}
