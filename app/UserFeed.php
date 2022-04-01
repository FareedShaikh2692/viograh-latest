<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UserFeed extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'feed_id','user_id', 'type_id', 'is_save_as_draft','like_count','comment_count','privacy','status','created_ip','created_at','updated_ip','updated_at'
    ];
    public function getUser(){
        return $this->hasOne('App\User','id','user_id');
    }
   
    public function getUserWishlist(){
        return $this->hasOne('App\UserWishList','feed_id','id');
    }
    public function getUserWishlistLikes(){
        return $this->hasOne('App\UserWishListLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserWishlistComments(){
        return $this->hasMany('App\UserWishListComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserIdea(){
        return $this->hasOne('App\UserIdea','feed_id','id');
    }
    public function getUserIdeaFile(){
        return $this->hasOne('App\UserIdeaFile','feed_id','id');
    }
    public function getUserIdeaLike(){
        return $this->hasOne('App\UserIdeaLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserIdeaComment(){
        return $this->hasMany('App\UserIdeaComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserDiary(){
        return $this->hasOne('App\UserDiary','feed_id','id');
    }
    public function getUserDiaryLike(){
        return $this->hasOne('App\UserDiaryLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserDiaryComment(){
        return $this->hasMany('App\UserDiaryComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserLifeLesson(){
        return $this->hasOne('App\UserLifeLesson','feed_id','id');
    }
    public function getUserLifeLessonLike(){
        return $this->hasOne('App\UserLifeLessonLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserLifeLessonComment(){
        return $this->hasMany('App\UserLifeLessonComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserDream(){
        return $this->hasOne('App\UserDream','feed_id','id');
    }
    public function getUserDreamLike(){
        return $this->hasOne('App\UserDreamLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserDreamComment(){
        return $this->hasMany('App\UserDreamComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserExperience(){
        return $this->hasOne('App\UserExperience','feed_id','id');
    }
    public function getUserExperienceLike(){
        return $this->hasOne('App\UserExperienceLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserExperienceComment(){
        return $this->hasMany('App\UserExperienceComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserFirst(){
        return $this->hasOne('App\UserFirstLast','feed_id','id')->where('type','=','first');
    }
    public function getUserFirstLike(){
        return $this->hasOne('App\UserFirstLastLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserFirstComment(){
        return $this->hasMany('App\UserFirstLastComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserLast(){
        return $this->hasOne('App\UserFirstLast','feed_id','id')->where('type','=','last');
    }
    public function getUserLastLike(){
        return $this->hasOne('App\UserFirstLastLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserLastComment(){
        return $this->hasMany('App\UserFirstLastComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserSpiritualJourney(){
        return $this->hasOne('App\UserSpiritualJourney','feed_id','id');
    }
    public function getUserSpiritualJourneyLike(){
        return $this->hasOne('App\UserSpiritualJourneyLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserSpiritualJourneyComment(){
        return $this->hasMany('App\UserSpiritualJourneyComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserMoment(){
        return $this->hasOne('App\UserMoments','feed_id','id');
    }
    public function getUserMomentLikes(){
        return $this->hasOne('App\UserMomentLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserMomentComments(){
        return $this->hasMany('App\UserMomentComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getdocuments(){
        return $this->hasMany('App\FeedDocument','feed_id','id');
    }
    public function getUserEducation(){
        return $this->hasOne('App\UserEducation','feed_id','id');
    }
    public function getUserEducationLike(){
        return $this->hasOne('App\UserEducationLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserEducationComment(){
        return $this->hasMany('App\UserEducationComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function getUserCareer(){
        return $this->hasOne('App\UserCareer','feed_id','id');
    }
    public function getUserCareerLike(){
        return $this->hasOne('App\UserCareerLike','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserCareerComment(){
        return $this->hasMany('App\UserCareerComment','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function user_family_email(){
        return $this->hasMany('App\UserFamilyTree','user_id' ,'id')->where('family_tree_id','!=','0');
    } 
    public function getUserMomentImage(){
        return $this->hasMany('App\UserMomentFiles','feed_id','id');
    }
    public function getUserMilestone(){
        return $this->hasOne('App\UserMilestone','feed_id','id')->orderBy('achieve_date','ASC');
    }
    public function getUserMilestoneLike(){
        return $this->hasOne('App\UserMilestoneLikes','feed_id','id')->where('user_id','=',Auth::user()->id);
    }
    public function getUserMilestoneComment(){
        return $this->hasMany('App\UserMilestoneComments','feed_id','id')->orderBy('id','DESC')->limit(3);
    }
    public function family_feed(){
        $type_id = $this->attributes['type_id'];
      
        if($type_id == 2){
            return $this->hasOne('App\UserWishList','feed_id','id');
        }
        else if($type_id == 5){
            return $this->hasOne('App\UserIdea','feed_id','id');
        }
        else if($type_id == 9){
            return $this->hasOne('App\UserDiary','feed_id','id');
        }
        else if($type_id == 12){
            return $this->hasOne('App\UserLifeLesson','feed_id','id');
        }
        else if($type_id == 15){
            return $this->hasOne('App\UserDream','feed_id','id');
        }
        else if($type_id == 18){
            return $this->hasOne('App\UserEducation','feed_id','id');
        }
        else if($type_id == 19){
            return $this->hasOne('App\UserCareer','feed_id','id');
        }
        else if($type_id == 20){
            return $this->hasOne('App\UserExperience','feed_id','id');
        }
        else if($type_id == 23){
            return $this->hasOne('App\UserFirstLast','feed_id','id');
        }
        else if($type_id == 26){
            return $this->hasOne('App\UserSpiritualJourney','feed_id','id');
        }
        else if($type_id == 29){
            return $this->hasOne('App\UserMoments','feed_id','id');
        }
        else if($type_id == 40){
            return $this->hasOne('App\UserMilestone','feed_id','id');
        }
      
    }
}
