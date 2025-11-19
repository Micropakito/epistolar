<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'owner_user_id',
        'name',
        'type',
        'is_active',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function background()
    {
        return $this->hasOne(CharacterBackground::class);
    }

    public function lettersSent()
    {
        return $this->hasMany(Letter::class, 'from_character_id');
    }

    public function lettersReceived()
    {
        return $this->hasMany(Letter::class, 'to_character_id');
    }
}
