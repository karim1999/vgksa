<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'description', 'country', 'city', 'phone', 'investment_type', 'money', 'idea',
        'legal_status', 'twitter', 'facebook', 'linkedin', 'referral', 'how', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function projects(){
        return $this->hasMany('App\Project');
    }

    public function jointProjects(){
        return $this->belongsToMany('App\Project', 'projects_users');
    }
}
