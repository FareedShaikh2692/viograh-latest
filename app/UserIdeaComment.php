<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserIdeaComment extends Model
{
    public $timestamps = false;

    public function getIdeaCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
