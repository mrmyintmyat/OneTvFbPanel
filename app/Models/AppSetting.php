<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppSetting extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'app_settings';

    // The attributes that are mass assignable
    protected $fillable = ['appDetails', 'sponsorGoogle', 'sponsorText', 'sponsorBanner', 'sponsorInter', 'updateInfo'];

    protected $casts = [
        'appDetails' => 'json',
        'sponsorGoogle' => 'json',
        'sponsorText' => 'json',
        'sponsorBanner' => 'json',
        'sponsorInter' => 'json',
        'updateInfo' => 'json',
    ];

    public function imageUrls()
    {
        return $this->hasMany(ImageUrl::class, 'app_setting_id');
    }
}
