<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLiability extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'title', 'amount', 'description', 'bank_name', 'account_number','created_at','updated_at','created_ip','updated_ip'
    ];
    public function getUserLiabilityDocuments(){
        return $this->hasMany('App\UserLiabilityDocuments','liability_id','id');
    }
    public function getUserLiability(){
        return $this->hasOne('App\User','id' ,'user_id');
    }
}
