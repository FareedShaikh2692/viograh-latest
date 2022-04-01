<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'feed_id', 'user_id', 'name', 'start_date', 'end_date', 'my_buddies', 'achievements', 'description','file', 'created_at','updated_at','created_ip','updated_ip'
    ];

    // public function getUserEducationFiles(){
    //     return $this->hasOne('App\UserEducationFiles','education_id','id')->where('file_type','=','file');
    // }
   /*  public function getUserEducationDocs(){
        return $this->hasMany('App\UserEducationFiles','education_id','feed_id')->where('file_type','=','document');
    } */
    public function getUserEducationFeed(){
        return $this->hasOne('App\UserFeed','id','feed_id');
    }
    public function getUserFeeds(){
        return $this->hasMany('App\UserFeed','id','feed_id')->where('status','!=','Delete')->orWhere('type_id','=',18);
    }
     
}
