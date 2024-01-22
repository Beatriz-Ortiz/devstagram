<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'user_id'
    ];

    public function user() {
        // Belongs To = User asociado a un post
        return $this->belongsTo(User::class)->select(['name', 'username']);
    }

    public function comentarios() {
        // One To Many = Comentarios asociados a un post
        return $this->hasMany(Comentario::class);
    }

    public function likes() {
        // One To Many = Likes asociados a un post
        return $this->hasMany(Like::class);
    }

    public function checkLike(User $user) {
        return $this->likes->contains('user_id', $user->id);
    }
}
