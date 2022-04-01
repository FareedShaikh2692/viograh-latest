<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExperienceComment extends Model
{
    
    public function getExperienceCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
