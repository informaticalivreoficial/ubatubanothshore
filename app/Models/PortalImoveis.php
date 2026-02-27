<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalImoveis extends Model
{
    use HasFactory;

    protected $table = 'portal_imoveis';

    protected $fillable = [
        'portal',
        'imovel'
    ];

    /**
     * Relacionamentos
    */
    public function portal()
    {
        return $this->belongsTo(Portal::class, 'portal', 'id');
    }

    public function imovel()
    {
        return $this->belongsTo(Property::class, 'imovel', 'id');
    } 
}
