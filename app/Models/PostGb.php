<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Support\Cropper;

class PostGb extends Model
{
    use HasFactory;

    protected $table = 'post_gb'; 

    protected $fillable = [
        'post',
        'path',
        'cover',
        'order_img',
    ];

    /**
     * Accerssors and Mutators
     */

    public function getUrlCroppedAttribute()
    {
        //return \App\Support\ImageService::makeThumb($this->path, 720, 480);
        //return Storage::url(Cropper::thumb($this->path, 720, 480));
        return Storage::url($this->path);
    }

    public function getUrlImageAttribute()
    {
        return Storage::url($this->path);
    }
}
