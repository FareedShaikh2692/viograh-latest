<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    public function getUserExperienceFeed(){
        return $this->hasOne('App\UserFeed','id','feed_id');
    }
}
