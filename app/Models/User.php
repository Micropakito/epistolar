<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Friendship;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Historias que el usuario ha creado
    public function createdStories()
    {
        return $this->hasMany(Story::class, 'creator_id');
    }

    // Participaciones en historias
    public function storyParticipants()
    {
        return $this->hasMany(StoryParticipant::class);
    }

    // Personajes que controla
    public function characters()
    {
        return $this->hasMany(Character::class, 'owner_user_id');
    }

    // Cartas que ha escrito
    public function letters()
    {
        return $this->hasMany(Letter::class, 'from_user_id');
    }

    // Notificaciones recibidas
    public function notificationsEpistolar()
    {
        return $this->hasMany(Notification::class);
    }

    // Amistades (solicitudes enviadas)
    public function sentFriendships()
    {
        return $this->hasMany(Friendship::class, 'requester_id');
    }

    // Amistades (solicitudes recibidas)
    public function receivedFriendships()
    {
        return $this->hasMany(Friendship::class, 'addressee_id');
    }

    // Historias que posee (si las usas en algún sitio)
    public function ownedStories()
    {
        return $this->hasMany(Story::class, 'user_id');
    }

    // Historias en las que participa (pivot story_user o similar)
    public function stories()
    {
        return $this->belongsToMany(Story::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /* ==========================
     *        AMIGOS
     * ========================== */

    // Amigos donde yo soy requester
    public function friendsAsRequester()
    {
        return $this->belongsToMany(
            User::class,
            'friendships',
            'requester_id',
            'addressee_id'
        )->wherePivot('status', 'accepted');
    }

    // Amigos donde yo soy addressee
    public function friendsAsAddressee()
    {
        return $this->belongsToMany(
            User::class,
            'friendships',
            'addressee_id',
            'requester_id'
        )->wherePivot('status', 'accepted');
    }

    // Colección combinada de todos los amigos aceptados
    public function friends()
    {
        // devuelve Collection<User>
        return $this->friendsAsRequester->merge($this->friendsAsAddressee);
    }
}
