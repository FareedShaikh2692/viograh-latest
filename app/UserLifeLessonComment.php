<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLifeLessonComment extends Model
{
    public function getLifeLessonCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
