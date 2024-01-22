<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts() {
        // One to Many = Posts asociados a un usuario
        return $this->hasMany(Post::class);
    }

    public function likes() {
        // One to Many = Likes asociados a un user
        return $this->hasMany(Like::class);
    }

    // Tabla pivot: Almacena los seguidores de un usuario
    public function followers() {
        // Many to many = Relacion de muchos a muchos
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // Tabla pivot: Almacena los usuarios a los que seguimos
    public function followings() {
         // Many to many = Relacion de muchos a muchos
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // Comprobar si un usuario ya sigue a otro
    public function siguiendo(User $user) {
        return $this->followers->contains($user->id);
    }

}
