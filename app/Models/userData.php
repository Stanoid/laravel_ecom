<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

class userData extends Model
{
    /** @use HasFactory<\Database\Factories\UserDataFactory> */
    use HasFactory;

    protected $fillable = [
     'address',
     'phone'
    ];


public function user () : BelongsTo
{
    return $this->belongsTo(User::class);
}


}
