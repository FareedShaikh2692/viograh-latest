<?php

namespace App;

use App\Notifications\ManageResetPassword;
use Illuminate\Database\Eloquent\Model;

class ManagePasswordResets extends Model
{
    protected $guarded = ['id'];

    protected $table = 'manage_password_resets';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'created_at';

    public function user(){
        return $this->belongsTo('App\Manage', 'user_id');
    }
}
