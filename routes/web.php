<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

include ('manage.php');
include ('api.php');

//LOGIN ROUTES
Route::get('/', 'Auth\LoginController@showLoginForm');
Route::get('/login', 'Auth\LoginController@showLoginForm');

Route::post('/login/check','Auth\LoginController@login')->name('weblogin');
Route::get('/logout', 'Auth\LoginController@logout')->name('weblogout');

//REGISTERATION AND VERIFICATION ROUTES
Route::get('/signup', 'Auth\RegisterController@registers')->name('registers');
Route::post('/register/insert', 'Auth\RegisterController@insert')->name('register.insert');
Route::get('/verify/{any}', 'Auth\RegisterController@verify')->name('register.verify');
Route::post('/resend', 'Auth\RegisterController@resend')->name('register.resend');

//FORGOT AND RESET PASSWORD ROUTES
Route::get('/forgot-password', 'Auth\ForgotPasswordController@showEmailForm')->name('forgot-password');
Route::post('/password/sendEmail', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot-password.sendEmail');
Route::get('/reset-password/{token}', 'Auth\ResetPasswordController@showResetForm')->name('forgot-password.reset_pass');
Route::post('/reset-password/{any}', 'Auth\ResetPasswordController@reset')->name('forgot-password.reset_pass_succ');

//SOCIAL LOGIN 
Auth::routes();
Route::get('auth/google','Auth\LoginController@redirectToGoogle')->name('google-login');
Route::get('auth/google/callback','Auth\LoginController@handleGoogleCallback');

//FOR CLEAR CACHE
Route::get('/clear-cache', function() {
  $exitCode = Artisan::call('cache:clear');
  $exitCode = Artisan::call('config:clear');
  $exitCode = Artisan::call('route:clear');
  $exitCode = Artisan::call('view:clear');
});

//WEBPAGES ROUTES
Route::get('/p/{slug}', 'WebpageController@index')->name('permalink');

//CONTACT US ROUTE
Route::get('/contact-us','ContactUsController@showcontactusform');
Route::post('/contact-us','ContactUsController@insertcontactusform');

//************ REQUEST STATUS **************/
Route::get('/request_status/', 'RequestStatusController@index')->name('request_status.index');

Route::middleware(['VerifyUser'])->group(function () {
    /******** HOME ********/
    Route::get('/home', 'HomeController@index')->name('index');

    Route::post('/feedback','FeedbackController@index')->name('feedback');

    Route::post('/delete_feed','AjaxDeleteController@index')->name('deletefeed');
    Route::post('/like_feed','AjaxLikeUnlikeController@index')->name('likefeed');
    Route::post('/get_comment_feed','AjaxCommentController@index')->name('commentfeed');
    Route::post('/add_comment_feed','AjaxCommentController@newComment')->name('addcommentfeed');
    Route::post('/load_more_comment','AjaxCommentController@loadMoreComment')->name('loadmorecommentfeed');
    Route::post('/delete-document','AjaxDeleteDocumentController@index')->name('deletedocument');

    Route::get('/search', 'SearchController@index')->name('search.index');
    Route::post('/load-more-search', 'SearchController@loadMoreSearch')->name('search.load-more-search');
    
    Route::get('/public-profile/{any}', 'PublicProfileController@index')->name('public-profile.index');
   // Route::post('/load-more-{module}', 'PublicProfileController@loadMore')->name('public-profile.load-more-{module}');

    /*************** WISHLIST ************/
    Route::get('/wishlist', 'WishlistController@index')->name('wishlist.index');
    Route::get('/wishlist/add', 'WishlistController@add')->name('wishlist.add');
    Route::post('/wishlist/add', 'WishlistController@insert')->name('wishlist.insert');
    Route::get('/wishlist/edit/{any}','WishlistController@edit')->name('wishlist.edit');
    Route::post('/wishlist/update/{any}','WishlistController@update')->name('wishlist.update');
    Route::get('/wishlist/information/{any}', 'WishlistController@information')->name('wishlist.information');
    Route::post('/delete-wishlistfile', 'WishlistController@deleteFile')->name('wishlist.deletefile');
    Route::post('/load-more-wishlist', 'WishlistController@loadMoreWishlist')->name('wishlist.load-more-wishlist');

    /************** IDEA *************/
    Route::get('/idea', 'IdeasController@index')->name('idea.index');
    Route::get('/idea/add', 'IdeasController@add')->name('idea.add');
    Route::post('/idea/add', 'IdeasController@insert')->name('idea.insert');
    Route::get('/idea/edit/{any}','IdeasController@edit')->name('idea.edit');
    Route::post('/idea/update/{any}','IdeasController@update')->name('idea.update');
    Route::get('/idea/information/{any}', 'IdeasController@information')->name('idea.information');
    Route::post('/delete-idea', 'IdeasController@deleteFile')->name('idea.deletefile');
    Route::post('/load-more-idea', 'IdeasController@loadMoreIdea')->name('idea.load-more-idea');

    /************** DIARY *************/
    Route::get('/diary', 'DiaryController@index')->name('diary.index');
    Route::get('/diary/add', 'DiaryController@add')->name('diary.add');
    Route::post('/diary/add', 'DiaryController@insert')->name('diary.insert');
    Route::get('/diary/edit/{any}','DiaryController@edit')->name('diary.edit');
    Route::post('/diary/update/{any}','DiaryController@update')->name('diary.update');
    Route::get('/diary/information/{any}', 'DiaryController@information')->name('diary.information');
    Route::post('/delete-diaryfile', 'DiaryController@deleteFile')->name('diary.deletefile');
    Route::post('/load-more-diary', 'DiaryController@loadMoreDiary')->name('diary.load-more-diary');

    /************** LIFE LESSONS *************/
    Route::get('/lifelesson', 'LifeLessonController@index')->name('lifelesson.index');
    Route::get('/lifelesson/add', 'LifeLessonController@add')->name('lifelesson.add');
    Route::post('/lifelesson/add', 'LifeLessonController@insert')->name('lifelesson.insert');
    Route::get('/lifelesson/edit/{any}','LifeLessonController@edit')->name('lifelesson.edit');
    Route::post('/lifelesson/update/{any}','LifeLessonController@update')->name('lifelesson.update');
    Route::get('/lifelesson/information/{any}', 'LifeLessonController@information')->name('lifelesson.information');
    Route::post('/delete-lifelessonfile', 'LifeLessonController@deleteFile')->name('lifelesson.deletefile');
    Route::post('/load-more-lifelesson', 'LifeLessonController@loadMoreLifelesson')->name('lifelesson.load-more-lifelesson');

    /************** DREAMS *************/
    Route::get('/dream', 'DreamController@index')->name('dream.index');
    Route::get('/dream/add', 'DreamController@add')->name('dream.add');
    Route::post('/dream/add', 'DreamController@insert')->name('dream.insert');
    Route::get('/dream/edit/{any}','DreamController@edit')->name('dream.edit');
    Route::post('/dream/update/{any}','DreamController@update')->name('dream.update');
    Route::get('/dream/information/{any}', 'DreamController@information')->name('dream.information');
    Route::post('/delete-dreamfile', 'DreamController@deleteFile')->name('dream.deletefile');
    Route::post('/load-more-dream', 'DreamController@loadMoreDream')->name('dream.load-more-dream');

    /************** FIRST *************/
    Route::get('/first', 'FirstController@index')->name('first.index');
    Route::get('/first/add', 'FirstController@add')->name('first.add');
    Route::post('/first/add', 'FirstController@insert')->name('first.insert');
    Route::get('/first/edit/{any}','FirstController@edit')->name('first.edit');
    Route::post('/first/update/{any}','FirstController@update')->name('first.update');
    Route::get('/first/information/{any}', 'FirstController@information')->name('first.information');
    Route::post('/delete-firstfile', 'FirstController@deleteFile')->name('first.deletefile');
    Route::post('/load-more-first', 'FirstController@loadMoreFirst')->name('first.load-more-first');

    /************** LAST *************/
    Route::get('/last', 'LastController@index')->name('last.index');
    Route::get('/last/add', 'LastController@add')->name('last.add');
    Route::post('/last/add', 'LastController@insert')->name('last.insert');
    Route::get('/last/edit/{any}','LastController@edit')->name('last.edit');
    Route::post('/last/update/{any}','LastController@update')->name('last.update');
    Route::get('/last/information/{any}', 'LastController@information')->name('last.information');
    Route::post('/delete-lastfile', 'LastController@deleteFile')->name('last.deletefile');
    Route::post('/load-more-last', 'LastController@loadMoreLast')->name('last.load-more-last');
    
    /******* MY SELF ABOUT ***********/
    //Route::get('/myself', 'MyselfController@add')->name('myself.index');    
    Route::get('/myself', 'MyselfController@index')->name('myself.index');
    Route::get('/myself/edit', 'MyselfController@edit')->name('myself.edit');
    Route::post('/myself/edit', 'MyselfController@insert')->name('myself.insert');    
    Route::post('/delete-bannerfile','MyselfController@deleteFile')->name('myself.delete');

    /******* MY SELF EDUCATION ********/
    Route::get('/education', 'EducationController@index')->name('education.index');   
    Route::get('/education/add_education', 'EducationController@add')->name('education.add'); 
    Route::post('/education/insert', 'EducationController@insert')->name('education.insert');
    Route::get('/education/edit/{any}','EducationController@edit')->name('education.edit');
    Route::post('/education/update/{any}','EducationController@update')->name('education.update');
    Route::get('/education/information/{any}', 'EducationController@information')->name('education.information');
    Route::post('/delete-educationfile','EducationController@deleteFile')->name('education.delete');

    /******* MY SELF CAREER ********/
    Route::get('/career', 'CareerController@index')->name('career.index'); 
    Route::get('/career/add_career', 'CareerController@add')->name('career.add'); 
    Route::post('/career/insert', 'CareerController@insert')->name('career.insert');
    Route::get('/career/edit/{any}','CareerController@edit')->name('career.edit');
    Route::post('/career/update/{any}','CareerController@update')->name('career.update');
    Route::get('/career/information/{any}', 'CareerController@information')->name('career.information');
    Route::post('/delete-careerfile','CareerController@deleteFile')->name('career.delete');
    
    /************** EXPERIENCE *************/
    Route::get('/experience', 'ExperienceController@index')->name('experience.index');
    Route::get('/experience/add', 'ExperienceController@add')->name('experience.add');
    Route::post('/experience/add', 'ExperienceController@insert')->name('experience.insert');
    Route::get('/experience/edit/{any}','ExperienceController@edit')->name('experience.edit');
    Route::post('/experience/update/{any}','ExperienceController@update')->name('experience.update');
    Route::get('/experience/information/{any}', 'ExperienceController@information')->name('experience.information');
    Route::post('/delete-experiencefile', 'ExperienceController@deleteFile')->name('experience.deletefile');
    Route::post('/load-more-experience', 'ExperienceController@loadMoreExperience')->name('experience.load-more-experience');
    
    /************** SPIRITUAL JOURNEY *************/
    Route::get('/spiritual-journey', 'SpiritualJourneyController@index')->name('spiritual-journey.index');
    Route::get('/spiritual-journey/add', 'SpiritualJourneyController@add')->name('spiritual-journey.add');
    Route::post('/spiritual-journey/add', 'SpiritualJourneyController@insert')->name('spiritual-journey.insert');
    Route::get('/spiritual-journey/edit/{any}','SpiritualJourneyController@edit')->name('spiritual-journey.edit');
    Route::post('/spiritual-journey/update/{any}','SpiritualJourneyController@update')->name('spiritual-journey.update');
    Route::get('/spiritual-journey/information/{any}', 'SpiritualJourneyController@information')->name('spiritual-journey.information');
    Route::post('/delete-spiritualjourneyfile', 'SpiritualJourneyController@deleteFile')->name('spiritual-journey.deletefile');
    Route::post('/load-more-spiritual-journey', 'SpiritualJourneyController@loadMoreSpiritualJourney')->name('spiritual-journey.load-more-spiritualjourney');

    /*PROFILE ROUTE*/
    Route::get('/profile','ProfileController@index')->name('profile.index');
    Route::get('/profile/edit','ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update/{any}','ProfileController@update')->name('profile.update');
   
    /*CHANGE PASSWORD */
    Route::get('/change-password','ChangePasswordController@index')->name('changepass.index');
    Route::post('/change-password/update/','ChangePasswordController@update')->name('changepass.update');

    /*PRIVACY SETTING*/
    Route::get('/privacy-settings','PrivacySettingController@index')->name('privacy.index');
    Route::post('/privacy-settings/update/','PrivacySettingController@update')->name('privacy.update');

    /*NOTIFICATION*/
    Route::get('/notification','NotificationController@index')->name('notification.index');
    Route::post('/load-more-notification', 'NotificationController@loadMoreNotification')->name('notification.load-more-notification');
  
    /************** MOMENTS *************/
    Route::get('/special-moments', 'MomentsController@index')->name('moments.index');
    Route::get('/special-moments/add', 'MomentsController@add')->name('moments.add');
    Route::post('/special-moments/insert', 'MomentsController@insert')->name('moments.insert');
    Route::get('/special-moments/edit/{any}','MomentsController@edit')->name('moments.edit');
    Route::post('/special-moments/update/{any}','MomentsController@update')->name('moments.update');
    Route::get('/special-moments/information/{any}', 'MomentsController@information')->name('moments.information');
    Route::post('/moments/delete-momentsfile', 'MomentsController@deleteFile')->name('moments.delete-momentsfile');
    Route::post('/moments/load-more-moments', 'MomentsController@loadMoreMoments')->name('moments.load-more-moments');
    
    Route::middleware(['VerifyNetworthNomineePassword'])->group(function () {
      Route::get('/my-networth', 'AssetController@index')->name('asset.index'); 
   
      Route::get('/assets/add-asset', 'AssetController@asset_add')->name('assets.asset-add'); 
      Route::post('/assets/asset-insert', 'AssetController@asset_insert')->name('assets.asset-insert');
      Route::get('/assets/edit-asset/{any}','AssetController@asset_edit')->name('assets.asset-edit');
      Route::post('/assets/asset-update/{any}','AssetController@asset_update')->name('assets.asset-update');
      Route::get('/assets/asset-information/{any}', 'AssetController@asset_information')->name('assets.asset-information');
      Route::post('/assets/delete-assetfile', 'AssetController@asset_delete_File')->name('assets.delete-assetfile');
      Route::post('/assets/delete-nominee', 'AssetController@nominee_delete')->name('assets.delete-nominee');
      Route::post('/asset/send-email', 'AssetController@notificationmail')->name('asset.send-email');
      Route::post('/my-networth/verify_password', 'AssetController@verify_password')->name('mynetworth.verify_password');
      Route::post('/my-networth/verify_otp', 'AssetController@verify_otp')->name('mynetworth.verify_otp');
      Route::post('/my-networth/send-otp', 'AssetController@generate_otp')->name('asset.send-otp');
  
      /************ LIABILITY *************/
    //  Route::get('/my-networth', 'LiabilityController@index')->name('asset.index'); 
      Route::get('/liability/add-liability', 'LiabilityController@liability_add')->name('liability.liability-add'); 
      Route::post('/liability/liability-insert', 'LiabilityController@liability_insert')->name('liability.liability-insert');
      Route::get('/liability/edit-liability/{any}','LiabilityController@liability_edit')->name('liability.liability-edit');
  
      Route::post('/liability/liability-update/{any}','LiabilityController@liability_update')->name('liability.liability-update');
      Route::get('/liability/liability-information/{any}', 'LiabilityController@liability_information')->name('liability.liability-information');
      Route::post('/liability/delete-liabilityfile', 'LiabilityController@liability_delete_File')->name('liability.delete-liabilityfile');
      Route::post('/asset/send-email', 'LiabilityController@notificationmail')->name('asset.send-email');
     // Route::post('/my-networth/verify_password', 'LiabilityController@verify_password')->name('mynetworth.verify_password');

         /*************NOMINEE *****************/
      Route::get('/nominee', 'NomineeController@index')->name('nominee.index');
      Route::get('/nominee/myself_added', 'NomineeController@myself_added')->name('nominee.myself_added');
    });
    /************ ASSET *************/
    
  
    /************ FAMILY TREE *************/
    Route::get('/family-tree', 'FamilyTreeController@index')->name('familytree.index');
    Route::post('/family-tree', 'FamilyTreeController@insert')->name('familytree.insert');
    Route::post('/insert-family-tree', 'FamilyTreeController@insertTree')->name('familytree.insertTree');
    Route::post('/edit-tree', 'FamilyTreeController@edit')->name('familytree.editTree');
    Route::post('/update-tree', 'FamilyTreeController@update')->name('familytree.updateTree');
    Route::post('/update-family-tree', 'FamilyTreeController@updateTree')->name('familytree.updateFamilyTree');
    Route::post('/remove-family-tree', 'FamilyTreeController@removeTree')->name('familytree.removeTree');

     /************ FAMILY FEED *************/
     Route::get('/family-feed', 'FamilyFeedController@index')->name('familyfeed.index');
     Route::post('/load-more-familyfeed', 'FamilyFeedController@loadMoreFamilyFeed')->name('familyfeed.load-more-family-feed');

      /************ FAMILY FEED *************/
      Route::get('/family-list', 'FamilyListController@index')->name('familylist.index');

      /************ MILESTONES *************/
      Route::get('/milestone', 'MilestoneController@index')->name('milestone.index');
      Route::get('/milestone/add', 'MilestoneController@add')->name('milestone.add');
      Route::post('/milestone/insert', 'MilestoneController@insert')->name('milestone.insert');
      Route::get('/milestone/edit/{any}','MilestoneController@edit')->name('milestone.edit');
      Route::post('/milestone/update/{any}','MilestoneController@update')->name('milestone.update');
      Route::get('/milestone/information/{any}', 'MilestoneController@information')->name('milestone.information');
      Route::post('/delete-milestonefile', 'MilestoneController@deleteFile')->name('milestone.delete-milestonefile');
      Route::post('/load-more-milestone', 'MilestoneController@loadMoreMilestone')->name('milestone.load-more-milestone');
     
      
  });
  
