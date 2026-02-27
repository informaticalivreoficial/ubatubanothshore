<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyBlockedDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'date',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
