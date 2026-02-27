<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Portal extends Model
{
    use HasFactory;

    protected $table = 'portais';

    protected $fillable = [
        'pago',
        'gratuito',
        'nome',
        'logomarca',
        'link',
        'notas',
        'status',
        'codigo',
        'servidor_ftp',
        'servidor_user',
        'servidor_pass',
        'plano1_nome',
        'plano1_qtd',
        'plano2_nome',
        'plano2_qtd',
        'plano3_nome',
        'plano3_qtd',
        'plano4_nome',
        'plano4_qtd',
        'xml_nome'
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

    public function scopePago($query)
    {
        return $query->where('pago', 1);
    }

    public function scopeGratuito($query)
    {
        return $query->where('gratuito', 1);
    }

    /**
     * Relacionamentos
    */    
    public function pimoveis()
    {
        return $this->hasMany(PortalImoveis::class, 'portal', 'id');
    } 

    public function countimoveis()
    {
        return $this->hasMany(PortalImoveis::class, 'portal', 'id')->count();
    }

    /**
     * Accerssors and Mutators
    */  
    public function getUrlLogomarcaAttribute()
    {
        if(empty($this->logomarca) || !Storage::disk()->exists($this->logomarca)) {
            return url(asset('backend/assets/images/image.jpg'));
        } 
        return Storage::url($this->logomarca);
    }

    public function setPagoAttribute($value)
    {
        $this->attributes['pago'] = ($value == true || $value == 'on' ? 1 : 0);
    }

    public function setGratuitoAttribute($value)
    {
        $this->attributes['gratuito'] = ($value == true || $value == 'on' ? 1 : 0);
    }
}
