<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDiary extends Model
{
    public $timestamps = false;

    public function getUserDiaryFeed(){
        return $this->hasOne('App\UserFeed','id','feed_id');
    }
    public function getUserDiaries(){
        return $this->hasOne('App\User','id','user_id');
    }
}
