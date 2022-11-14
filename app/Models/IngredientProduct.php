<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IngredientProduct extends Pivot
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';
}
