<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCareerComment extends Model
{
    public function getCareerCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
