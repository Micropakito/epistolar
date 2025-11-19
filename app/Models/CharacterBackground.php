<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterBackground extends Model
{
    use HasFactory;

    protected $fillable = [
        'character_id',
        'public_background',
        'private_notes',
        'last_updated_by',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}
