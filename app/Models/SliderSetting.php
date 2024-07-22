<?php

namespace App\Models;

use App\Models\ImageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SliderSetting extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'autoplay', 'duration', 'click_url'];

    public function imageUrls()
    {
        return $this->hasMany(ImageUrl::class);
    }
}
