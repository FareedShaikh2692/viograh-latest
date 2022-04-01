<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserMoments extends Model
{
    public function getUserMomentImage(){
        return $this->hasMany('App\UserMomentFiles','feed_id','feed_id');
    }
    public function getUserMomentFeed(){
        return $this->hasOne('App\UserFeed','id','feed_id');
    }    
    
}
