<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFirstLastComment extends Model
{
    public function getFirstLastCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
