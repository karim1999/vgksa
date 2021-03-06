<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $with = ['user', 'category'];

    //
    protected $fillable = [
        'title', 'description', 'amount', 'presentation', 'study', 'img'
    ];

    public function jointUsers(){
        return $this->belongsToMany('App\User', 'projects_users')->withPivot('amount')->withTimestamps();
    }
    public function lovedBy(){
        return $this->belongsToMany('App\User', 'favorites')->withTimestamps();
    }

    public function tags(){
        return $this->belongsToMany('App\Tag', 'projects_tags')->withTimestamps();
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function category(){
        return $this->belongsTo('App\category');
    }

}
