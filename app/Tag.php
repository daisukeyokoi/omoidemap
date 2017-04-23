<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    ////////////////////////////////////////////////////////////////////////////
	// util
	////////////////////////////////////////////////////////////////////////////
    public function postsTags() {
        return $this->hasMany('App\PostsTag');
    }

    public function delete() {
        if (count($this->postsTags()->get()) != 0){
			$this->postsTags()->delete();
		}
		return parent::delete();
    }

    public function follow_tags() {
        return $this->hasMany('App\Followtag');
    }

    ////////////////////////////////////////////////////////////////////////////
	// scope
	////////////////////////////////////////////////////////////////////////////
    public function scopePostsSort($query) {
        return $query->leftJoin('posts_tags', 'tags.id', '=', 'posts_tags.tag_id')
                     ->selectRaw('tags.*, count(posts_tags.tag_id) as count')
                     ->groupBy('tags.id')
                     ->orderBy('count', 'desc');
    }
}
