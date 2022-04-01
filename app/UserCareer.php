<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCareer extends Model
{
    protected $table = 'user_career';

    public $timestamps = false;
    protected $fillable = [
        'feed_id', 'user_id', 'name', 'role', 'start_date', 'end_date', 'buddies', 'achievements', 'description','file', 'created_at','updated_at','created_ip','updated_ip'
    ];

    // public function getUserCareerFiles(){
    //     return $this->hasOne('App\UserCareerFiles','career_id','id')->where('file_type','=','file');
    // }
    // public function getUserCareerDocs(){
    //     return $this->hasMany('App\UserCareerFiles','career_id','id')->where('file_type','=','document');
    // }
    public function getUserCareerFeed(){
        return $this->hasOne('App\UserFeed','id','feed_id');
    }
}
