<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserFamilyTree extends Model
{
    protected $fillable = [
        'id','user_id','family_tree_id', 'image', 'name','email','age','gender','tree', 'is_alive','relationship', 'status','request_status','token','phone_number','created_at','updated_at','created_ip','updated_ip'
    ];
    public function getFamilyTreeUser(){
        return $this->hasOne('App\User','id','user_id');
    }
   
    public function getFamilyTreeUserEmail(){
        return $this->hasOne('App\User','email','email')->where('status','=','enable');
    }
    public function getFamilyTreeUsername(){
        return $this->hasOne('App\User','id','user_id')->where('id','!=',Auth::user()->id);
    }
}
