<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakongTransaction extends Model
{
    protected $fillable = [
        'hash', 'amount', 'currency', 'city',
        'qr_payload', 'status', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
