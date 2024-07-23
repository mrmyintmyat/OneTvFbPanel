<?php

namespace App\Models;

use App\Models\VnMatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class League extends Model
{
    use HasFactory;

    protected $table = 'leagues';

    protected $fillable = [
        'name',
        'logo',
    ];

    public function matches()
    {
        return $this->hasMany(VnMatch::class);
    }
}
