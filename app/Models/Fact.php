<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;

class Fact extends Model
{
    /** @use HasFactory<\Database\Factories\FactFactory> */
    use HasFactory;


    protected $fillable = [
        'facts',

    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}


