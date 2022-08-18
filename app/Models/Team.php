<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUsersPivot extends Pivot
{
    protected $casts = [
        'admin' => 'boolean'
    ];
}


class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'teams_users',
            'team_id',
            'user_id')->withPivot('admin')->using('App\Models\TeamUsersPivot');
    }


    public function invitations()
    {
        return $this->hasMany(Invitation::class)
            ->where(
            'is_from_team',
            false
        );
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
