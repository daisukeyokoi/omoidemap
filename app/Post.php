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

    public function goes() {
        return $this->hasMany('App\Go');
    }

    public function eventPost() {
        return $this->hasOne('App\EventPost');
    }

    public function delete() {
        if ($this->event_id != 0) {
            if (count($this->eventPost) != 0) {
                $eventPosts = EventPost::where('event_id', $this->event_id)->get();
                foreach ($eventPosts as $eventPost) {
                    if ($eventPost->ranking > $this->event_id) {
                        $eventPost->ranking -= 1;
                        $eventPost->save();
                    }
                }
                $this->eventPost->delete();
            }
        }
        if (count($this->comments()->get()) != 0){
			$this->comments()->delete();
		}
        if (count($this->goods()->get()) != 0) {
            $this->goods()->delete();
        }
        if (count($this->postsTags()->get()) != 0) {
            $this->postsTags()->delete();
        }
        if (count($this->oneImage()->first()) != 0) {
            $this->oneImage()->delete();
        }
		return parent::delete();
    }


    ////////////////////////////////////////////////////////////////////////////
	// scope
	////////////////////////////////////////////////////////////////////////////
    public function scopeMapRange($query, $sw_lat, $sw_lng, $ne_lat, $ne_lng) {
        return $query->where('lat', '>', $sw_lat)
                     ->where('lat', '<', $ne_lat)
                     ->where('lng', '>', $sw_lng)
                     ->where('lng', '<', $ne_lng);
    }
    public function scopeGoodsSort($query) {
        return $query->leftJoin('goods', 'posts.id', '=', 'goods.post_id')
                     ->selectRaw('posts.*, count(goods.post_id) as count')
                     ->groupBy('posts.id')
                     ->orderBy('count', 'desc');
    }
    public function scopeGoesSort($query) {
        return $query->leftJoin('goes', 'posts.id', '=', 'goes.post_id')
                     ->selectRaw('posts.*, count(goes.post_id) as count')
                     ->groupBy('posts.id')
                     ->orderBy('count', 'desc');
    }
}
