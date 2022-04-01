<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMilestoneComments extends Model
{
    public function getMilestoneCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
