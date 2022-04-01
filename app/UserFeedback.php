<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFeedback extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'url', 'type','subject', 'message', 'is_read'.'status','created_at','updated_at','created_ip','updated_ip'
    ];
}
