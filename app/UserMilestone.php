<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMilestone extends Model
{
    public function getUserMilestoneFeed(){
        return $this->hasOne('App\UserFeed','id','feed_id');
    }
    public function getUserMilestones(){
        return $this->hasOne('App\User','id','user_id');
    }
}
