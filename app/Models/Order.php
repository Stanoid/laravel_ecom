<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\OrderItems;
use App\Models\city;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
    'order_number',
    'total_price',
    'status',
    'phone',
    'address',
    'city_id',
    'payment_id',
    ];


    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items() : HasMany
    {
        return $this->hasMany(OrderItems::class);
    }


    public function city() : BelongsTo
    {
        return $this->belongsTo(city::class);
    }


      public function payment() : BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }





}


