<?php

namespace App\Models;

use Database\Factories\StockFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'ingredient_id';

    protected $fillable = [
        'ingredient_id',
        'ingredient_amount',
        'initial_ingredient_amount',
        'notifiable'
    ];

    protected static function newFactory(): StockFactory
    {
        return StockFactory::new();
    }

    public function ingredients(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id', 'id');
    }
}
