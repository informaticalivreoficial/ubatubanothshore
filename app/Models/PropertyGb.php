<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PropertyGb extends Model
{
    use HasFactory;

    protected $table = 'property_gbs'; 
    
    protected $fillable = [
        'property',
        'path',
        'cover',
        'watermark',
        'order_img',
    ];

    protected $casts = [
        'cover' => 'boolean',
        'watermark' => 'boolean',
        'order_img' => 'integer',
    ];
      

    public function property()
    {
        return $this->belongsTo(Property::class, 'property');
    }

    /**
     * Accerssors and Mutators
    */
    public function getUrlImageAttribute()
    {
        return Storage::url($this->path);
    }

    public function setWatermarkAttribute($value)
    {
        $this->attributes['watermark'] = ($value == true || $value == '1' ? 1 : 0);
    }
}
