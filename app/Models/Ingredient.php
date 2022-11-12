<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ingredient extends Model
{
    use HasFactory, HasUuids;

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

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }
}
