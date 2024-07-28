<?php

namespace App\Models;

use App\Models\AppSetting;
use App\Models\SliderSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageUrl extends Model
{
    use HasFactory;

    protected $fillable = ['slider_setting_id', 'app_setting_id', 'img_url', 'click_url'];

    public function SliderSetting()
    {
        return $this->belongsTo(SliderSetting::class);
    }

    public function AppSetting()
    {
        return $this->belongsTo(AppSetting::class);
    }
}
