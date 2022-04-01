<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDreamComment extends Model
{
    public function getDreamCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
