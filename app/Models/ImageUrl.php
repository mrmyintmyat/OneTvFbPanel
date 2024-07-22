<?php

namespace App\Models;

use App\Models\SliderSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageUrl extends Model
{
    use HasFactory;

    protected $fillable = ['slider_setting_id', 'img_url', 'file_path'];

    public function SliderSetting()
    {
        return $this->belongsTo(SliderSetting::class);
    }
}
