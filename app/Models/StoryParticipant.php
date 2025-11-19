<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'user_id',
        'role',
        'invitation_status',
        'is_invited',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
