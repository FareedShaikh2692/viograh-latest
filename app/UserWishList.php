<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UserWishList extends Model
{
    public $timestamps = false;
    

    public function getWishlistLikes(){
        return $this->hasMany('App\UserWishListLike','feed_id','feed_id')->where('is_like','=',1);
    }
    public function userWishlistLikes(){
        return $this->hasOne('App\UserWishListLike','feed_id','feed_id')->where('user_id','=',Auth::user()->id);
    }
    public function getWishlistComments(){
        return $this->hasMany('App\UserWishListComment','feed_id','feed_id');
    }
    public function getUserFeeds(){
        return $this->hasMany('App\UserFeed','id','feed_id')->where('status','!=','Delete')->orWhere('type_id','=',2);
    }
    public function getUserWishListFeed(){
        return $this->hasOne('App\UserFeed','id','feed_id');
    }
    
}
