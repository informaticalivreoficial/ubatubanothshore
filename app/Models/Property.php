<?php

namespace App\Models;

use App\Support\Cropper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';

    protected $fillable = [
        'sale',
        'location',
        'category',
        'type',
        'expired_at',
        'display_values',
        'sale_value',
        'rental_value',
        'location_period',
        'iptu',
        'reference',
        'condominium',
        'description',
        'additional_notes',
        'dormitories',
        'suites',
        'bathrooms',
        'rooms',
        'garage',
        'covered_garage',
        'construction_year',
        'total_area',
        'useful_area',
        'measures',

        /** address */ 
        'latitude', 'longitude', 'display_address', 'zipcode', 'street',
        'number', 'complement', 'neighborhood', 'state', 'city',

        //accessories
        'ar_condicionado', 'areadelazer', 'aquecedor_solar', 'bar', 'banheirosocial', 'brinquedoteca',
        'biblioteca', 'balcaoamericano', 'churrasqueira', 'condominiofechado', 'estacionamento',
        'cozinha_americana', 'cozinha_planejada', 'dispensa', 'edicula', 'espaco_fitness',
        'escritorio', 'banheira', 'geradoreletrico', 'interfone', 'jardim', 'lareira', 'lavabo', 'lavanderia',
        'elevador', 'mobiliado', 'vista_para_mar', 'piscina', 'quadrapoliesportiva', 'sauna', 'salaodejogos',
        'salaodefestas', 'sistemadealarme', 'saladetv', 'ventilador_teto', 'armarionautico', 'fornodepizza',  
        'portaria24hs', 'permiteanimais', 'pertodeescolas', 'quintal', 'zeladoria', 'varandagourmet', 
        'internet', 'geladeira',

        //SEO
        'title', 'slug', 'url_booking', 'url_arbnb', 'status', 'views', 'metatags', 'headline',
        'display_marked_water', 'youtube_video', 'caption_img_cover', 'google_map',
        'experience', 'highlight', 'publication_type'
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
    public function scopeSale($query)
    {
        return $query->where('sale', 1);
    }

    public function scopeLocation($query)
    {
        return $query->where('location', 1);
    }

    protected static function booted()
    {
        // 🔹 Gerar slug automaticamente
        static::saving(function ($property) {
            $property->setSlug();
        });
        
        static::deleting(function ($property) {
            // Deleta imagens físicas e registros relacionados
            foreach ($property->images as $image) {
                if ($image->path && Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
                $image->delete();
            }

            // Deleta a pasta inteira do imóvel no storage
            Storage::disk('public')->deleteDirectory("properties/{$property->id}");
        });
    }

    /**
     * Relationships
    */
    public function seasons()
    {
        return $this->hasMany(PropertySeason::class);
    }

    public function blockedDates()
    {
        return $this->hasMany(PropertyBlockedDate::class);
    }

    public function reservations()
    {
        return $this->hasMany(PropertyReservation::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyGb::class, 'property', 'id')
                    ->orderBy('order_img', 'ASC')
                    ->orderBy('cover', 'DESC'); // cover primeiro (1 antes de 0)
    }

    public function imagesmarkedwater()
    {
        return $this->hasMany(PropertyGb::class, 'property', 'id')->whereNull('watermark')->count();
    }

    public function pimoveis()
    {
        return $this->hasMany(PortalImoveis::class, 'imovel', 'id');
    }

    /**
     * Accerssors and Mutators
    */  
    public function isAvailable($checkin, $checkout)
    {
        $checkin = Carbon::parse($checkin);
        $checkout = Carbon::parse($checkout);

        // Verifica reservas existentes
        $hasReservation = $this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkin, $checkout) {
                $query->whereBetween('checkin', [$checkin, $checkout])
                    ->orWhereBetween('checkout', [$checkin, $checkout])
                    ->orWhere(function ($q) use ($checkin, $checkout) {
                        $q->where('checkin', '<=', $checkin)
                            ->where('checkout', '>=', $checkout);
                    });
            })
            ->exists();

        if ($hasReservation) {
            return false;
        }

        // Verifica bloqueios manuais
        $hasBlocked = $this->blockedDates()
            ->whereBetween('date', [$checkin, $checkout])
            ->exists();

        return !$hasBlocked;
    }

    public function calculatePrice($checkin, $checkout)
    {
        $checkin = Carbon::parse($checkin);
        $checkout = Carbon::parse($checkout);

        $total = 0;

        for ($date = $checkin; $date < $checkout; $date->addDay()) {

            $season = $this->seasons()
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->first();

            if ($season) {
                $total += $season->price_per_night;
            } else {
                $total += $this->rental_value; // valor padrão
            }
        }

        return $total;
    }

    public function getContentWebAttribute()
    {
        return Str::words($this->description, '20', ' ...');
    }

    public function cover()
    {
        $images = $this->images();
        $cover = $images->where('cover', 1)->first(['path']) ??
                $images->first(['path']);

        if (!$cover || empty($cover->path)) {
            return asset('theme/images/image.jpg');
        }

        return Storage::url(Cropper::thumb($cover['path'], 1366, 768));
    }  

    public function nocover()
    {
        $images = $this->images();

        // Pega capa, se não existir usa a primeira imagem
        $cover = $images->where('cover', 1)->first(['path'])
            ?? $images->first(['path']);

        if (empty($cover['path']) || !Storage::disk()->exists($cover['path'])) {
            return asset('theme/images/image.jpg');
        }
        
        return Storage::url($cover['path']);
    }

    // public function nocover()
    // {
    //     $images = $this->images();
    //     $cover = $images->where('cover', 1)->first(['path']);

    //     if(!$cover) {
    //         $images = $this->images();
    //         $cover = $images->first(['path']);
    //     }
        
    //     if(empty($cover['path']) || !Storage::disk()->exists($cover['path'])) {
    //         return url(asset('theme/images/image.jpg'));
    //     }
        
    //     return Storage::url($cover['path']);        
    // }

    public function getStarsAttribute()
    {
        // Busca o maior e menor número de views entre os imóveis ativos
        $maxViews = self::where('status', 1)->max('views');
        $minViews = self::where('status', 1)->min('views');

        // Garante que não vai dividir por zero
        $range = max(1, $maxViews - $minViews);

        // Normaliza entre 0 e 1
        $score = ($this->views - $minViews) / $range;

        // Converte para estrelas (1 a 5)
        $stars = ceil($score * 5);

        return max(1, min(5, $stars)); // garante mínimo 1 e máximo 5
    }

    public function getLocationPeriod(): ?string
    {
        if (empty($this->location_period)) {
            return null;
        }

        $periods = [
            1 => 'Diária',
            2 => 'Quinzenal',
            3 => 'Mensal',
            4 => 'Trimestral',
            5 => 'Semestral',
            6 => 'Anual',
            7 => 'Bianual',
        ];

        // Retorna o valor correspondente ou 'Diária' como padrão
        return $periods[$this->location_period] ?? 'Diária';
    }

    // public function getStarsAttribute(): int
    // {
    //     $totalViews = self::where('status', 1)->sum('views');

    //     if ($this->views <= 0 || $totalViews <= 0) {
    //         return 0;
    //     }

    //     $percent = ($this->views / $totalViews) * 100;

    //     // transforma em número de estrelas (0 a 5)
    //     return ceil($percent / 20);
    // }

    public function setDisplayAddressAttribute($value)
    {
        $this->attributes['display_address'] = ($value == true || $value == '1' ? 1 : 0);
    }

    public function setDisplayValuesAttribute($value)
    {
        $this->attributes['display_values'] = ($value == true || $value == '1' ? 1 : 0);
    }

    public function setDisplayMarkedWaterAttribute($value)
    {
        $this->attributes['display_marked_water'] = ($value == true || $value == '1' ? 1 : 0);
    }
    
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }

    public function getFormattedSaleValueAttribute(): ?string
    {
        return $this->sale_value !== null
            ? 'R$ ' . number_format($this->sale_value, 0, ',', '.')
            : null;
    }

    public function getFormattedRentalValueAttribute(): ?string
    {
        return $this->rental_value !== null
            ? 'R$ ' . number_format($this->rental_value, 0, ',', '.')
            : null;
    }

    // public function setSaleValueAttribute($value)
    // {
    //     $this->attributes['sale_value'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    // }

    // public function getSaleValueAttribute($value)
    // {
    //     if (empty($value)) {
    //         return null;
    //     }

    //     return number_format($value, 2, ',', '.');
    // }
    
    // public function setRentalValueAttribute($value)
    // {
    //     $this->attributes['rental_value'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    // }

    // public function getRentalValueAttribute($value)
    // {
    //     if (empty($value)) {
    //         return null;
    //     }

    //     return number_format($value, 2, ',', '.');
    // }
    
    // public function setIptuAttribute($value)
    // {
    //     $this->attributes['iptu'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    // }

    // public function getIptuAttribute($value)
    // {
    //     if (empty($value)) {
    //         return null;
    //     }

    //     return number_format($value, 2, ',', '.');
    // }
    
    // public function setCondominiumAttribute($value)
    // {
    //     $this->attributes['condominium'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    // }

    // public function getCondominiumAttribute($value)
    // {
    //     if (empty($value)) {
    //         return null;
    //     }

    //     return number_format($value, 2, ',', '.');
    // }
    
    // public function setZipcodeAttribute($value)
    // {
    //     $this->attributes['zipcode'] = (!empty($value) ? $this->clearField($value) : null);
    // }

    // public function getZipcodeAttribute($value)
    // {
    //     if (empty($value)) {
    //         return null;
    //     }

    //     return substr($value, 0, 5) . '-' . substr($value, 5, 3);
    // }

    public function setSlug()
    {
        if (!empty($this->title)) {
    
            $baseSlug = Str::slug($this->title);
            $slug = $baseSlug;
            $count = 1;
    
            while (
                Property::where('slug', $slug)
                    ->where('id', '!=', $this->id)
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
                $count++;
            }
    
            $this->attributes['slug'] = $slug;
        }
    }    

    public function setExpiredAtAttribute($value)
    {
        $this->attributes['expired_at'] = (!empty($value) ? $this->convertStringToDate($value) : null);
    }
    
    public function getExpiredAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    }

    private function convertStringToDouble($param)
    {
        if(empty($param)){
            return null;
        }
        return str_replace(',', '.', str_replace('.', '', $param));
    }

    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }

    private function clearField(?string $param)
    {
        if(empty($param)){
            return null;
        }
        return str_replace(['.', '-', '/', '(', ')', ' '], '', $param);
    }
}
