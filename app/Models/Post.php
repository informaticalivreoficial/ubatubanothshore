<?php

namespace App\Models;

use App\Support\Cropper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'posts'; 

    protected $fillable = [
        'autor',
        'type',
        'title',
        'content',
        'slug',
        'tags',
        'views',
        'category',
        'comments',
        'highlight',
        'cat_pai',        
        'status',
        'menu',
        'thumb_caption',
        'publish_at'
    ];

    protected $casts = [
        //'publish_at' => 'datetime',
        'status' => 'boolean',
        'coments' => 'boolean',
    ];

    /**
     * Boot do model - executado automaticamente
    */
    protected static function boot()
    {
        parent::boot();

        // Antes de salvar (criar ou atualizar)
        static::saving(function ($post) {
            if (empty($post->slug) && !empty($post->title)) {
                $post->slug = $post->generateUniqueSlug($post->title);
            }
        });
    }

    /**
     * Gera um slug único
    */
    public function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        // Verifica se já existe, excluindo o próprio post em caso de edição
        while (static::where('slug', $slug)
                    ->where('id', '!=', $this->id ?? 0)
                    ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Método manual para atualizar o slug (se necessário)
    */
    public function updateSlug()
    {
        if (!empty($this->title)) {
            $this->slug = $this->generateUniqueSlug($this->title);
            $this->save();
        }
    }

    protected static function booted()
    {
        static::deleting(function ($post) {
            // Deleta imagens físicas e registros relacionados
            foreach ($post->images as $image) {
                if ($image->path && Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
                $image->delete();
            }

            // Deleta a pasta inteira do imóvel no storage
            Storage::disk('public')->deleteDirectory("posts/{$post->id}");
        });
    }

    /**
     * Scopes
    */
    public function scopePostson($query)
    {
        return $query->where('status', 1);
    }
    
    public function scopePostsoff($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Relacionamentos
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'autor', 'id');
    }
    
    public function category()
    {
        return $this->hasOne(CatPost::class, 'id', 'category');
    }

    public function categoryObject()
    {
        return $this->hasOne(CatPost::class, 'id', 'category');
    }
    
    public function userObject()
    {
        return $this->hasOne(User::class, 'id', 'category');
    }
    
    public function images()
    {
        return $this->hasMany(PostGb::class, 'post', 'id')->orderBy('cover', 'ASC');
    }

    public function countimages()
    {
        return $this->hasMany(PostGb::class, 'post', 'id')->count();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentsCount()
    {
        return $this->hasMany(Comment::class, 'post', 'id')->count(); // 'post' é a FK em comments
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('approved', true);
    }

    /**
     * Accerssors and Mutators
     */

    public function getContentWebAttribute()
    {
        return Str::words($this->content, '20', ' ...');
    }

    public function cover()
    {
        $images = $this->images();
        $cover = $images->where('cover', 1)->first(['path']) ??
                $images->first(['path']);

        if (!$cover || empty($cover->path)) {
            return asset('theme/images/image.jpg');
        }

        return Storage::url(Cropper::thumb($cover['path'], 720, 480));
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
    
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }

    public function setMenuAttribute($value)
    {
        $this->attributes['menu'] = ($value == '1' ? 1 : 0);
    }
    
    public function setPublishAtAttribute($value)
    {
        $this->attributes['publish_at'] = (!empty($value) ? $this->convertStringToDate($value) : null);
    }
    
    public function getPublishAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return date('d/m/Y', strtotime($value));
    }
    
    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }
}
