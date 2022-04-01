<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    protected $guarded = [];

    protected $table = 'user_verifications';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'created_at';

    public function userDetail(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
