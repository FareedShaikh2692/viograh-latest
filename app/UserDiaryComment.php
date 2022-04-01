<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDiaryComment extends Model
{
    public $timestamps = false;

    public function getDiaryCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
