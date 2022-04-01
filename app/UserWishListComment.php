<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWishListComment extends Model
{
    public $timestamps = false;

    public function getcommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
