<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEducationComment extends Model
{
    public function getEducationCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
