<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_email',
        'is_from_team'
    ];

    protected $attributes = [
        'is_from_team' => true,
    ];

    protected $with = ['team'];

    protected $casts = [
        'is_from_team' => 'boolean',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }
}
