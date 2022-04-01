<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserForgotPassword extends Model
{
    protected $guarded = ['id'];

    protected $table = 'user_forgot_passwords';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'created_at';

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
