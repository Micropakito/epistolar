<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'from_character_id',
        'from_user_id',
        'to_character_id',
        'content_html',
        'content_plain',
        'status',
        'is_story_advance',
        'sent_at',
    ];

    protected $casts = [
        'is_story_advance' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function fromCharacter()
    {
        return $this->belongsTo(Character::class, 'from_character_id');
    }

    public function toCharacter()
    {
        return $this->belongsTo(Character::class, 'to_character_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
