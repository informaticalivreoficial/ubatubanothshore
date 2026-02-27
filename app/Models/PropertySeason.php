<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertySeason extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'label',
        'start_date',
        'end_date',
        'price_per_day',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
