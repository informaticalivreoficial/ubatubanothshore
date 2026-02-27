<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Template extends Model
{
    use HasFactory;

    protected $table = 'templates';

    protected $fillable = [
        'name',
        'image',
        'content',
        'status'
    ];

    /**
     * Scopes
    */
    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Relacionamentos
    */
    

    /**
     * Accerssors and Mutators
    */
    public function getimagem()
    {
        if(empty($this->image) || !Storage::disk()->exists($this->image)) {
            return url(asset('backend/assets/images/image.jpg'));
        } 
        return Storage::url($this->image);
    }
}
