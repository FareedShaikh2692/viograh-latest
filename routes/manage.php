<?php

use Illuminate\Mail\Markdown;

Route::get('/manage/','HomeController@index')->name('manage.home');

Route::group(['prefix' => 'manage'], function () {
  Route::get('/', 'ManageAuth\LoginController@showLoginForm')->name('login');
  Route::get('/login', 'ManageAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'ManageAuth\LoginController@login');
  Route::post('/logout', 'ManageAuth\LoginController@logout')->name('logout');

  Route::get('/password/reset', 'ManageAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::post('/password/email', 'ManageAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
  
  Route::get('/password/reset/{token}', 'ManageAuth\ResetPasswordController@showResetForm');
  Route::post('/password/reset/{any}', 'ManageAuth\ResetPasswordController@reset');

  Route::get('/home', 'Manage\HomeController@index');

  Route::middleware(['verifyManageUser'])->group(function () 
  {
    Route::get('/home', 'Manage\HomeController@index');
    Route::get('/dashboard','Manage\HomeController@index')->name('home');
    
    Route::post('/status','Manage\AjaxstatusController@index')->name('status');
    Route::post('/delete','Manage\AjaxdeleteController@index')->name('delete');

    Route::get('/profile','Manage\ProfileController@index')->name('profile');
    Route::post('/profile/update','Manage\ProfileController@update')->name('profile.update');  
      
    /******** ADMIN ********/
    Route::get('/admins','Manage\AdminController@index')->name('admins');
    Route::get('/admins/add','Manage\AdminController@add')->name('admins.add');
    Route::post('/admins/insert','Manage\AdminController@insert')->name('admins.insert');
    Route::get('/admins/edit/{any}','Manage\AdminController@edit')->name('admins.edit');
    Route::post('/admins/update/{any}','Manage\AdminController@update')->name('admins.update');
    Route::get('/admins/information/{any}','Manage\AdminController@information')->name('admins.information');

    /********** USER **********/
    Route::get('/users','Manage\UserController@index')->name('users');
    Route::get('/users/information/{any}','Manage\UserController@information')->name('users.information');

    /********* CONTACT US  ********/
    Route::get('/contact-us','Manage\ContactusController@index')->name('contactus.index');
    Route::get('/contact-us/information/{any}','Manage\ContactusController@information')->name('contactus.information');

    /******** REPORTS ********/
    Route::get('/reports','Manage\ReportsController@index')->name('reports.index'); 
    Route::post('/reports/export', 'Manage\ReportsController@export')->name('reports.export');    

    /******** MAIL SETTINGS ********/
    Route::get('/mail-settings','Manage\MailsettingsController@index')->name('mailsettings.index');
    Route::get('/mail-settings/add','Manage\MailsettingsController@add')->name('mailsettings.add');
    Route::post('/mail-settings/insert','Manage\MailsettingsController@insert')->name('mailsettings.insert');
    Route::get('/mail-settings/edit/{any}','Manage\MailsettingsController@edit')->name('mailsettings.edit');
    Route::post('/mail-settings/update/{any}','Manage\MailsettingsController@update')->name('mailsettings.update');
    Route::get('/mail-settings/information/{any}','Manage\MailsettingsController@information')->name('mailsettings.information');

    /******** WEB PAGES ********/
    Route::get('/web-pages','Manage\WebpagesController@index')->name('web-pages.index');
    Route::get('/web-pages/add','Manage\WebpagesController@add')->name('web-pages.add');
    Route::post('/web-pages/insert','Manage\WebpagesController@insert')->name('web-pages.insert');
    Route::get('/web-pages/edit/{any}','Manage\WebpagesController@edit')->name('web-pages.edit');
    Route::post('/web-pages/update/{any}','Manage\WebpagesController@update')->name('web-pages.update');

    /******* SETTING ********/
    Route::get('/settings','Manage\SettingController@index')->name('setting.index');
    Route::get('/settings/add','Manage\SettingController@add')->name('setting.add');
    Route::post('/settings/insert','Manage\SettingController@insert')->name('setting.insert');
    Route::get('/settings/edit/{any}','Manage\SettingController@edit')->name('setting.edit');
    Route::post('/settings/update/{any}','Manage\SettingController@update')->name('setting.update');
  }); 
    
});