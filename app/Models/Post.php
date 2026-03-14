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

        
    }      

    protected static function booted()
    {
        // 🔹 Gerar slug automaticamente
        static::saving(function ($property) {
            $property->setSlug();
        });

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

    public function setSlug()
    {
        if (!empty($this->title)) {
    
            $baseSlug = Str::slug($this->title);
            $slug = $baseSlug;
            $count = 1;
    
            while (
                Post::where('slug', $slug)
                    ->where('id', '!=', $this->id)
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
                $count++;
            }
    
            $this->attributes['slug'] = $slug;
        }
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
