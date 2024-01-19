<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VnMatch extends Model
{
    use HasFactory;

    protected $table = 'vnmatches';

    // The attributes that are mass assignable
    protected $fillable = [
        'match_time',
        'home_team_name',
        'home_team_logo',
        'home_team_score',
        'away_team_name',
        'away_team_logo',
        'away_team_score',
        'league_name',
        'league_logo',
        'match_status',
        'servers',
        'is_auto_match',
    ];

}
