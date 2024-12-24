<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Recipe;
use App\Models\Brand;


class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [

        'name',
        'price',
        'stock',
        'category_id',
        'brand_id',
        'description',
        'img',


    ];


    public function brand() : BelongsTo
{
    return $this->belongsTo(Brand::class);
}


public function category() : BelongsTo
{
    return $this->belongsTo(Category::class);
}

public function recipes() : HasMany
{
    return $this->hasMany(Recipe::class);
}



}


