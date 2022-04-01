<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSpiritualJourneyComment extends Model
{
    public function getSpiritualJourneyCommentedUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}
