<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostsTag extends Model
{
    protected $table = 'posts_tags';

    ////////////////////////////////////////////////////////////////////////////
	// util
	////////////////////////////////////////////////////////////////////////////
    public function post() {
        return $this->belongsTo('App\Post');
    }
    public function tag() {
        return $this->belongsTo('App\Tag');
    }
}
