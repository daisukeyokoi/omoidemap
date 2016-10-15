<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostsImage extends Model
{
    protected $table = 'posts_images';

    ////////////////////////////////////////////////////////////////////////////
	// util
	////////////////////////////////////////////////////////////////////////////
    public function Post() {
        return $this->belongsTo('App\Post');
    }
}
