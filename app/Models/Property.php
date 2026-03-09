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
        'category',
        'type',
        'expired_at',
        'display_values',
        'rental_value',
        'min_nights',
        'cleaning_fee',
        'value_aditional',
        'aditional_person',
        'capacity',
        'reference',
        'description',
        'additional_notes',
        'politica_cancelamento',
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
        'acesso_praia',
        'adequado_criancas',
        'adequado_idosos',
        'agua_quente',
        'aquecedor',
        'ar_condicionado',
        'areadelazer',
        'banheira',
        'banheiro_privativo',
        'cafeteira',
        'cama_casal',
        'cameras',
        'churrasqueira',
        'condominiofechado',
        'cozinha',
        'elevador',
        'espaco_fitness',
        'estacionamento',
        'fechadura_eletronica',
        'ferro_passar',
        'fornopizza',
        'frigobar',
        'garagem',
        'geladeira',
        'interfone',
        'jardim',
        'lareira',
        'lavabo',
        'maquina_lavar',
        'mesa_refeicao',
        'mesa_trabalho',
        'microondas',
        'mobiliado',
        'permiteanimais',
        'piscina',
        'portaria24hs',
        'pratos_talheres',
        'produtos_limpeza',
        'roupa_cama',
        'sauna',
        'salaodejogos',
        'secador_cabelo',
        'secadora',
        'tv',
        'tv_netflix',
        'ventilador_teto',
        'vista_para_mar',
        'wifi',

        //SEO
        'title', 'slug', 'status', 'views', 'metatags', 'headline',
        'youtube_video', 'caption_img_cover', 'google_map',
        'experience', 'highlight', 'publication_type'
    ];

    protected $casts = [
        'acesso_praia' => 'boolean',
        'adequado_criancas' => 'boolean',
        'adequado_idosos' => 'boolean',
        'agua_quente' => 'boolean',
        'aquecedor' => 'boolean',
        'ar_condicionado' => 'boolean',
        'areadelazer' => 'boolean',
        'banheira' => 'boolean',
        'banheiro_privativo' => 'boolean',
        'cafeteira' => 'boolean',
        'cama_casal' => 'boolean',
        'cameras' => 'boolean',
        'churrasqueira' => 'boolean',
        'condominiofechado' => 'boolean',
        'cozinha' => 'boolean',
        'elevador' => 'boolean',
        'espaco_fitness' => 'boolean',
        'estacionamento' => 'boolean',
        'fechadura_eletronica' => 'boolean',
        'ferro_passar' => 'boolean',
        'fornopizza' => 'boolean',
        'frigobar' => 'boolean',
        'garagem' => 'boolean',
        'geladeira' => 'boolean',
        'interfone' => 'boolean',
        'jardim' => 'boolean',
        'lareira' => 'boolean',
        'lavabo' => 'boolean',
        'maquina_lavar' => 'boolean',
        'mesa_refeicao' => 'boolean',
        'mesa_trabalho' => 'boolean',
        'microondas' => 'boolean',
        'mobiliado' => 'boolean',
        'permiteanimais' => 'boolean',
        'piscina' => 'boolean',
        'portaria24hs' => 'boolean',
        'pratos_talheres' => 'boolean',
        'produtos_limpeza' => 'boolean',
        'roupa_cama' => 'boolean',
        'sauna' => 'boolean',
        'salaodejogos' => 'boolean',
        'secador_cabelo' => 'boolean',
        'secadora' => 'boolean',
        'tv' => 'boolean',
        'tv_netflix' => 'boolean',
        'ventilador_teto' => 'boolean',
        'vista_para_mar' => 'boolean',
        'wifi' => 'boolean',
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

    public function hasImagesWithoutWatermark()
    {
        return $this->images->where('watermark', false)->isNotEmpty();
    }

    public function reviews()
    {
        return $this->hasMany(PropertyReview::class);
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
                $query->whereBetween('check_in', [$checkin, $checkout])
                    ->orWhereBetween('check_out', [$checkin, $checkout])
                    ->orWhere(function ($q) use ($checkin, $checkout) {
                        $q->where('check_in', '<=', $checkin)
                            ->where('check_out', '>=', $checkout);
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

        $seasons = $this->seasons()->get();

        $total = 0;

        for ($date = $checkin->copy(); $date < $checkout; $date->addDay()) {

            $season = $seasons->first(fn($season) =>
                $date->between($season->start_date, $season->end_date)
            );

            $total += $season
                ? $season->price_per_night
                : $this->rental_value;
        }

        return $total;
    }

    public function getPriceForDate($date)
    {
        $season = $this->seasons()
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        if ($season) {
            return $season->price_per_day;
        }

        return $this->price_per_day; // preço padrão da propriedade
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

    

    public function setDisplayAddressAttribute($value)
    {
        $this->attributes['display_address'] = ($value == true || $value == '1' ? 1 : 0);
    }

    public function setDisplayValuesAttribute($value)
    {
        $this->attributes['display_values'] = ($value == true || $value == '1' ? 1 : 0);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }    

    public function getFormattedRentalValueAttribute(): ?string
    {
        return $this->rental_value !== null
            ? 'R$ ' . number_format($this->rental_value, 0, ',', '.')
            : null;
    }    

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
