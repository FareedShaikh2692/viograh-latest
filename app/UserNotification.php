<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id', 'message','type', 'unique_id', 'type_id','from_id', 'is_read','created_at','updated_at','created_ip','updated_ip'
    ];
    public function UserInfo(){
        return $this->belongsTo('App\User','from_id' ,'id');
    }
    /* public function UserFeedNotification(){
        return $this->hasOne('App\UserFeed','id' ,'unique_id')->where('status','=','enable');
    } */
    public function UserFeedNotification(){
        return $this->hasOne('App\UserFeed','id' ,'unique_id');
    }
    
}
