<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    ////////////////////////////////////////////////////////////////////////////
	// util
	////////////////////////////////////////////////////////////////////////////
    public function posts() {
        return $this->hasMany('App\Post');
    }
    public function eventPosts() {
        return $this->hasMany('App\EventPost');
    }

    public function delete() {
        if (count($this->eventPosts()->get()) != 0) {
            $this->eventPosts()->delete();
        }
        return parent::delete();
    }

    ////////////////////////////////////////////////////////////////////////////
	// 定数
	////////////////////////////////////////////////////////////////////////////
    const YET        = 0;
    const OPEN       = 1;
    const REVIEW     = 2;
    const WAIT_CLOSE = 3;
    const CLOSE      = 4;

}
