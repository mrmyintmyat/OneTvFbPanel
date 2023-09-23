<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

      // The table associated with the model
      protected $table = 'app_settings';

      // The attributes that are mass assignable
      protected $fillable = [
        'serverDetails',
        'sponsorGoogle',
        'sponsorText',
        'sponsorBanner',
        'sponsorInter',
      ];

      protected $casts = [
        'serverDetails' => 'json',
        'sponsorGoogle' => 'json',
        'sponsorText' => 'json',
        'sponsorBanner' => 'json',
        'sponsorInter' => 'json',
    ];
}
