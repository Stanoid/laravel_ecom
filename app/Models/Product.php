<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [

        'name',
        'price',
        'stock',
        'category_id',
        'description',
        'img',


    ];



public function category() : BelongsTo
{
    return $this->belongsTo(Category::class);
}



}


