<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Followtag extends Model
{
  protected $table = 'followtags';

  ////////////////////////////////////////////////////////////////////////////
  // util
  ////////////////////////////////////////////////////////////////////////////
  public function user() {
    return $this->belongsTo('App\User');
  }

  public function tag() {
    return $this->belongsTo('App\Tag');
  }

}
