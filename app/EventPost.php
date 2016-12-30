<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventPost extends Model
{
    protected $table = 'event_posts';

    ////////////////////////////////////////////////////////////////////////////
	// util
	////////////////////////////////////////////////////////////////////////////
    public function post() {
        return $this->belongsTo('App\Post');
    }
    public function event() {
        return $this->belongsTo('App\Event');
    }
}
