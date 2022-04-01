<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMomentComment extends Model
{
    public function getMomentCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
