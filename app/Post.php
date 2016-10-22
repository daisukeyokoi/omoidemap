<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    ////////////////////////////////////////////////////////////////////////////
	// util
	////////////////////////////////////////////////////////////////////////////
    public function oneImage() {
        return $this->hasOne('App\PostsImage');
    }

    public function Images() {
        return $this->hasMany('App\PostsImage');
    }

    public function postsTags() {
        return $this->hasMany('App\PostsTag');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function comments() {
        return $this->hasMany('App\Comment');
    }

    public function goods() {
        return $this->hasMany('App\Good');
    }
}
