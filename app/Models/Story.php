<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'visibility',
        'status',
        'rules',
    ];

    // Creador
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Participantes
    public function participants()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }
    // Personajes
    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    // Cartas
    public function letters()
    {
        return $this->hasMany(Letter::class);
    }

    public function owner()
    {
        // si ya tienes algo así, mantén lo que uses
        return $this->belongsTo(User::class, 'user_id');
    }
}
