<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public $timestamps = false;
    protected $fillable = [
        'id','first_name','last_name', 'email', 'nationality_id','password', 'google_id', 'login_platform','tree', 'gender','phone_number','birth_date','status','created_at','updated_at','created_ip','updated_ip','currency_id'
    ];
    
    public function UserNotification(){
        return $this->belongsTo('App\User','id' ,'from_id');
    }
    public function UserCurrency(){
        return $this->belongsTo('App\Countries','currency_id' ,'id');
    }
    public function userFeed(){
        return $this->hasMany('App\UserFeed','user_id' ,'id');
    } 
    public function UserEducation(){
        return $this->hasMany('App\UserEducation','user_id' ,'id')->where('status','!=','delete')->orderBy('id','DESC');
    }
    public function UserCareer(){
        return $this->hasMany('App\UserCareer','user_id' ,'id')->where('status','!=','delete')->orderBy('id','DESC');
    }
    public function UserWishList(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['wish_list'])->orderBy('id','DESC');
    }
    public function UserIdeas(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['idea'])->orderBy('id','DESC');
    }
    public function UserDiaries(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['diary'])->orderBy('id','DESC');
    }
    public function UserLifeLessons(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['life_lessons'])->orderBy('id','DESC');
    }
    public function UserDreams(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['dreams'])->orderBy('id','DESC');
    }
    public function user_experiance(){
        return $this->hasMany('App\UserExperience','user_id' ,'id')->orderBy('id','DESC');
    }
    public function user_myfirst_mylast(){
        return $this->hasMany('App\UserFirstLast','user_id' ,'id')->orderBy('id','DESC');
    }
    public function user_spiritual(){
        return $this->hasMany('App\UserSpiritualJourney','user_id' ,'id')->orderBy('id','DESC');
    }
    public function UserEducationFeed(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['education'])->orderBy('id','DESC');
    }
    public function UserCareerFeed(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['career'])->orderBy('id','DESC');
    }
    public function user_family(){
        return $this->hasMany('App\UserFamilyTree','user_id' ,'id')->where('family_tree_id','!=','0');
    }
    public function user_family_email(){
        return $this->hasMany('App\UserFamilyTree','email' ,'email')->where('status','=','enable')->where('family_tree_id','!=',0);
    }
    public function UserExperiences(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['experiences'])->orderBy('id','DESC');
    }
    public function UserMoments(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['moments'])->orderBy('id','DESC');
    }
    public function UserFirsts(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])->orderBy('id','DESC');
    }
    public function UserLasts(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])->orderBy('id','DESC');
    }
    public function UserSpirituals(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['spiritual_journeys'])->orderBy('id','DESC');
    }
    
    public function UserMilestones(){
        return $this->hasMany('App\UserFeed','user_id' ,'id')->where('status','=','enable')->where('type_id','=',config('custom_config.user_feed_type')['milestones'])->orderBy('id','DESC');
    }
    
    
  
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
}
