<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakeChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_name', 'channel_logo', 'servers',
    ];

    protected $casts = [
        'servers' => 'array',
    ];
}
