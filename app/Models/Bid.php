<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $table = "bids";

    protected $fillable = [
        'user_id',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
