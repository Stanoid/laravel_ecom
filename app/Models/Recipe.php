<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;
class recipe extends Model
{
    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory;


    protected $fillable = [
        "name",
        'description ',
        'serving',
        'img',
        'timeInMinutes',
        'instructions',




    ];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
