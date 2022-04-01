<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserAsset extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'title', 'amount','is_save_as_draft', 'description', 'nominee_name', 'nominee_email', 'nominee_phone_number','created_at','updated_at','created_ip','updated_ip'
    ];
    public function getUserDocuments(){
        return $this->hasMany('App\UserAssetDocuments','asset_id','id');
    }
    public function getAssetUser(){
        return $this->hasOne('App\User','email' ,'nominee_email');
    }
    public function getUser(){
        return $this->hasOne('App\User','id' ,'user_id');
    }
}
