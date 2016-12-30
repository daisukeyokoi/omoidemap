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

    ////////////////////////////////////////////////////////////////////////////
	// 定数
	////////////////////////////////////////////////////////////////////////////
    const YET    = 0;
    const OPEN   = 1;
    const REVIEW = 2;
    const CLOSE  = 3;

}
