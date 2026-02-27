<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CatPost extends Model
{
    use HasFactory;

    protected $table = 'cat_post';

    protected $fillable = [
        'title',
        'content',
        'slug',
        'tags',
        'type',
        'status',
        'id_pai'
    ];

    protected $casts = [
        'status' => 'boolean',
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
    public function children()
    {
        return $this->hasMany(CatPost::class, 'id_pai')->orderBy('title');
    }

    /**
     * Accerssors and Mutators
     */
    // public function getStatusAttribute($value)
    // {
    //     if(empty($value)){
    //         return null;
    //     }

    //     return ($value == '1' ? 'Sim' : 'Não');
    // }

    // public function setStatusAttribute($value)
    // {
    //     $this->attributes['status'] = ($value == '1' ? 1 : 0);
    // }

    public function countposts()
    {
        return $this->hasMany(Post::class, 'category', 'id')->count();
    }

    public function setSlug()
    {
        if(!empty($this->title)){
            $category = CatPost::where('title', $this->title)->first(); 
            if(!empty($category) && $category->id != $this->id){
                $this->attributes['slug'] = Str::slug($this->title) . '-' . $this->id;
            }else{
                $this->attributes['slug'] = Str::slug($this->title);
            }            
            $this->save();
        }
    }
}
