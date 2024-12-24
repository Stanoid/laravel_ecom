<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Order;

class city extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;


    protected $fillable = [
        "name",
        ' price',

    ];


    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }
}
