<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post',
        'user',
        'author_name',
        'author_email',
        'content',
        'approved',
    ];

    /**
     * Relationships
    */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
