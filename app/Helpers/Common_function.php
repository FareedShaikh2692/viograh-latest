<?php
namespace App\Helpers;
use Session;
use App\Setting;
use App\UserAsset;
use App\UserLiability;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\DsPushToken;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use RNCryptor\RNCryptor\Decryptor;
use RNCryptor\RNCryptor\Encryptor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use DateTime;
use Illuminate\Http\File;
use App\UserNotification;
use App\MailSetting;
use App\User;
use App\UserFamilyTree;
use Illuminate\Support\Facades\Auth;

class Common_function{

    public static function encrypt($string){
        return Crypt::encryptString($string);
    }

    public static function decrypt($string){
        return Crypt::decryptString($string);
    }

    public static function long_ip($ip){
        return sprintf ("%u", ip2long ($ip));
    }

    public static function file_upload($file,$path){
        $image = Image::make($file)->orientate();
        $image = $image->stream()->__toString();
        Storage::disk('s3')->put($path, $image);
    }

    public static function thumb_create($source,$desti){
        $img = Image::make($source)->resize(300, 300,function ($source) {
            $source->aspectRatio();
            $source->upsize();
        });
        $img->save($desti);
    }

    //THUMB CREATION OF THE CROPPED IMAGE
    public static function crop_thumb_storage($path,$thumb_path,$cropped_image_file){
        $image = Image::make($cropped_image_file)->resize(300,300,function ($path) {
            $path->aspectRatio();
            $path->upsize();
        })->encode();

        Storage::disk('s3')->put($thumb_path,$image,'');
    }
    public static function crop_thumb_storage_bannerimg($path,$thumb_path,$cropped_image_file){
        $image = Image::make($cropped_image_file)->resize(600,300,function ($path) {
            $path->aspectRatio();
            $path->upsize();
        })->encode();

        Storage::disk('s3')->put($thumb_path,$image,'');
    }

    public static function thumb_storage($path,$thumb_path){
        $image = Image::make(Storage::disk('s3')->get($path))->resize(300,300,function ($path) {
            $path->aspectRatio();
            $path->upsize();
        })->encode();
        Storage::disk('s3')->put($thumb_path,$image,'');
    }

    // FUNCTION TO GET PROFILE IMAGES ONLY
    public static function check_show_image($image,$folder,$small=false){
        if($image!=''){
            $path = $small==true?config('custom_config.s3_'.$folder.'_thumb'):config('custom_config.s3_'.$folder.'_big');

            return Storage::disk('s3')->url($path.$image);
        }
        else{
            if($folder == "admin"){
                return asset('images/default.png');
            }
            else{
                return asset('images/default.png');
            }
        }
    }

    public static function get_s3_file($path,$image,$expire_time = "+24 hours"){         
        if($image!=''){            
            $s3 = Storage::disk('s3');
            $client = $s3->getDriver()->getAdapter()->getClient();
            $expiry = $expire_time;

            $command = $client->getCommand('GetObject', [
                'Bucket' => config('custom_config.settings.aws_bucket'),
                'Key'    => $path.$image,
            ]);
            
            $request = $client->createPresignedRequest($command, $expiry);
            
            return (string) $request->getUri();
            // return Storage::disk('s3')->url($path.$image);
        }
        else{
            return asset('images/default_banner.jpg');
        }
    }

    public static function get_public_s3_file($path,$image){
        if($image!=''){
            $s3 = Storage::disk('s3');
            $client = $s3->getDriver()->getAdapter()->getClient();

            $url = $client->getObjectUrl(config('custom_config.settings.aws_bucket'), $path.$image);

            return $url;
        }
        else{
            return asset('images/default_banner.jpg');
        }
    }

    public static function delete_s3($path,$image){
        Storage::disk('s3')->delete($path.$image);
    }

    public static function move_s3_file($server_folder, $local_file, $file_name)
    {
        Storage::disk('s3')->putFileAs($server_folder, new File($local_file), $file_name);
    }

    public static function generateToken($length = 40) {
        $characters = '0123456789';
        $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters)-1;
        $password = '';

        //select some random characters
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[mt_rand(0, $charactersLength)];
        }
        return $password;
    }

    public static function generateTokenNumeric($length = 40) {
        $characters = '0123456789';
        $charactersLength = strlen($characters)-1;
        $password = '';

        //select some random characters
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[mt_rand(0, $charactersLength)];
        }

        return $password;
    }

    public static function str_random($length = 16) {
        return Str::random($length);
    }

    public static function show_date_time($date){
		setlocale(LC_TIME, 'eN_EN'); // substitute your locale if not es_ES
		return  strftime("%d %b, %Y at %I:%M %p", strtotime($date));
    }

    public static function show_pdf_date($date){
		setlocale(LC_TIME, 'eN_EN'); // substitute your locale if not es_ES
		return  strftime("%d %b, %Y", strtotime($date));
    }

    public static function date_with_weekday($date){
		setlocale(LC_TIME, 'eN_EN'); // substitute your locale if not es_ES
		return  date("D d, M Y", strtotime($date));
    }

    public static function date_formate($date){ 
		setlocale(LC_TIME, 'eN_EN'); // substitute your locale if not es_ES
		return  date("d-m-Y", strtotime($date));
    }

    public static function date_formate1($date){ 
		setlocale(LC_TIME, 'eN_EN'); // substitute your locale if not es_ES
		return  date("d M, Y", strtotime($date));
    }
    public static function date_formate2($date){ 
		setlocale(LC_TIME, 'eN_EN'); // substitute your locale if not es_ES
		return  date("m/d/Y", strtotime($date));
    }

    public static function string_read_more($str,$length=250){
        if(strlen($str) > $length)
            return nl2br(substr($str,0,$length)).'<a href="javascript:;" class="js-readMore"> Read More</a><span class="span-load-more">'.nl2br(substr($str,$length)).'</span> <a href="javascript:;" class="js-readless">Read Less</a>';
        else
            return $str;
    }

    public static function android_push_notification($token,$message,$additional){
        
        $google_api_key = config('custom_config.fcm_google_api_push_key');

        $url = 'https://fcm.googleapis.com/fcm/send';

        $data = array(
            "title" => config('custom_config.settings.site_name'),
            "message" => $message,

        );
        
        $dataArray = array_merge($data,$additional);

        $fields = array(
            'registration_ids' => (is_array($token))?$token:array($token),          
            'data' => $dataArray,
        );
        //print_r($fields);exit;

        $headers = array(
            'Authorization: key=' . $google_api_key,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);

        if($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return;
    }

    public static function ios_push_notification($token,$message,$type =''){
        $google_api_key = config('custom_config.fcm_google_api_push_key');

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids' => (is_array($token))?$token:array($token),
            "notification" => array(
                "title" => config('custom_config.settings.site_name'),
                "body" => $message,
				"tag" => $type,
				"click_action" => $type,
			),
        );

        $headers = array(
            'Authorization: key=' . $google_api_key,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);

        if($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return;
    }

    public static function date_formatted($datetime) {
        return  strftime("%b %d, %Y", strtotime($datetime));
    }

    // ENCRYPT
    public static function rn_encryption($plainText) {
        $cryptor = new  Encryptor();
        return $cryptor->encrypt($plainText, config('custom_config.rn_cryptor_key'));
    }

    // DECRYPT
    public static function rn_decryption($base64Encrypted) {
        $cryptor = new Decryptor();
        return $cryptor->decrypt($base64Encrypted, config('custom_config.rn_cryptor_key'));
    }

    public static function get_time_ago( $time )
    {
        $time_difference = time() - $time;

        if( $time_difference < 1 ) { return ' Just now'; }
        $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                    30 * 24 * 60 * 60       =>  'month',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hour',
                    60                      =>  'min',
                    1                       =>  'second'
        );

        foreach( $condition as $secs => $str )
        {
            $d = $time_difference / $secs;

            if( $d >= 1 )
            {
                $t = round( $d );
                return  $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
            }
        }
    }
    public static function notification(){
        if(Auth::check()){
            $counter=UserNotification::where(array('is_read'=>0,'user_id'=>Auth::user()->id))->orderBy('id','DESC')->limit(5)->get();
            
            if(!empty($counter)){
                return $counter;
            } 
        }

    }
    public static function notificationCount(){
        if(Auth::check()){
            $counter=UserNotification::where(array('is_read'=>0,'user_id'=>Auth::user()->id))->orderBy('id','DESC')->get()->count();
            
            if($counter>0){
                return $counter;
            } 
        }

    }

    public static function addnotification($mail_data){         
       try{
            // get site email
            $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'like-comment')->first();
            // parameter for mail template and function to send

            $msg = $mail_temp['content'];
        
            $msg = str_replace('[USER_NAME]', ucfirst($mail_data['user_name']), $msg);
            $msg = str_replace('[SITE_URL]', url(''), $msg);
            $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
            $msg = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $msg);
            $msg = str_replace('[YEAR]', date('Y'), $msg);
            $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);
            $msg = str_replace('[SENTENCE]', $mail_data['sentence'], $msg);
            $msg = str_replace('[DETAILPAGE_LINK]', url($mail_data['post_url']), $msg);

            $email_data['to_email'] = $mail_data['user_email'];
            $email_data['subject'] = str_replace('[SITE_NAME]',config('custom_config.settings.site_name'),$mail_temp['subject']);
            $email_data['subject'] = str_replace('[SUBJECT]',$mail_data['subject'],$email_data['subject'] );
            $email_data['from_email'] = $mail_temp['from_email'];
            $email_data['message'] = $msg;  
         
            Mail::send([], [], function ($message) use ($email_data) {
            $message->to($email_data['to_email'])
                ->from($email_data['from_email'], config('custom_config.settings.site_name'))
                ->subject(str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $email_data['subject']))
                ->setBody($email_data['message'], 'text/html');
            });
        }catch(\Exception $e){
        }
    }
    public static function fileNameTrimmer($inp){
        $file = explode(".",$inp);
        if(strlen($file[0]) >= 40){
            return substr($file[0],0,30).".".$file[1];
        }else{
            return $inp;
        }
    }
   
    public static function passwordProtectedNetworth(Request $request){
        
        //Session::forget('verifyNetworthLogin');
        $url =url()->previous();
        $name = explode('?',$url);
        $final = implode(',',$name);
        $final_name = explode('/',$final);
        $data['results'] = User::where('status','=','enable') 
        ->where('id','=',Auth::user()->id)       
        ->first();
       
        if(session()->has('verifyNetworthLogin')==true && ($final_name[3] != 'myself' && $final_name[3] != 'career' && $final_name[3] != 'education' ) ){
            if(((int)(time() - $request->session()->get('verifyNetworthLogin')) / 60) > config('custom_config.networth__nominee_login_allowed_time')){
                return  view('password_protected_networth');
            }else{
                if(session()->has('verifyNetworthLogin')==true && ((int)(time() - $request->session()->get('verifyNetworthLogin')) / 60) <=  config('custom_config.networth__nominee_login_allowed_time')){
                    $request->session()->put('verifyNetworthLogin',time());
                } else{
                    Session::forget('verifyNetworthLogin');
                } 
                
                return true;
            }
        } else{
            Session::forget('verifyNetworthLogin');
            return false;
        }
    }
    
    public static  function amount_format($no)
    {
        $finalval = 0;
        if($no == 0) {
            return ' ';
        }else{
            $n =  strlen((int)$no); // 7
                switch ($n) {
                    case 1:
                        if(strpos($no, '.') !== false){
                            $finalval =  number_format($no,2);
                        }else{
                            $finalval =  number_format($no);
                        }
                        break;
                    case 2:
                        if(strpos($no, '.') !== false){
                            $finalval =  number_format($no,2);
                        }else{
                            $finalval =  number_format($no);
                        }
                        break;
                    case 3:
                        if(strpos($no, '.') !== false){
                            $finalval =  number_format($no,2);
                        }else{
                            $finalval =  number_format($no);
                        }
                        break;
                    case 4:
                        if(strpos($no, '.') !== false){
                            $finalval =  number_format($no,2);
                        }else{
                            $finalval =  number_format($no);
                        }
                        break;
                    case 5:
                        if(strpos($no, '.') !== false){
                            $finalval =  number_format($no,2);
                        }else{
                            $finalval =  number_format($no);
                        }
                        break;
                    case 6:
                        $val = $no/100000;
                        $val = round($val, 2);
                        if(strpos($val, '.') !== false){
                            $finalval =  number_format($val,2)." Lac";
                        }else{
                            $finalval =  number_format($val)." Lac";
                        }
                        break;
                    case 7:
                        $val = $no/100000;  
                        $val = round($val, 2);
                        if(strpos($val, '.') !== false){
                            $finalval =  number_format($val,2)." Lac";
                        }else{
                            $finalval =  number_format($val)." Lac";
                        }
                        break;
                    case  $n >= 8:
                        $val = $no/10000000;
                        $val = round($val, 2);
                        if(strpos($val, '.') !== false){
                            $finalval =  number_format($val,2)." Cr";
                        }else{
                            $finalval =  number_format($val)." Cr";
                        }
                        break;
                    default:
                        echo "";
                }
                return $finalval;
        }   
    }   
    public static function feed_images_home($type,$file,$file_type,$type_name=''){
        
        $default = 1;        
        $path = '';
       // $file_type='';
        if($file){
            $default = 0;
            if($file_type == 'video'){
                if($type == 2)
                    $path = 'wishlist/video/';
                if($type == 5)
                    $path = 'idea/video/';
                if($type == 9)
                    $path = 'diary/video/';
                if($type == 12)
                    $path = 'lifelesson/video/';
                if($type == 15)
                    $path = 'dreams/video/';
                if($type == 18)
                    $path = 'education/video/';
                if($type == 19)
                    $path = 'career/video/';
                if($type == 20)
                    $path = 'experiences/video/';
                if($type == 23)
                    $path = $type_name.'/video/';
                if($type == 26)
                    $path = 'spiritual-journey/video/';
                if($type == 29)
                    $path = 'moment/video/';
                if($type == 40)
                $path = 'milestone/video/';
            }
            else if($file_type == 'file'){
                if($type == 2)
                    $path = 'wishlist/thumb/';
                if($type == 5)
                    $path = 'idea/thumb/';
                if($type == 9)
                    $path = 'diary/thumb/';
                if($type == 12)
                    $path = 'lifelesson/thumb/';
                if($type == 15)
                    $path = 'dream/thumb/';
                if($type == 18)
                    $path = 'education/thumb/';
                if($type == 19)
                    $path = 'career/thumb/';
                if($type == 20)
                    $path = 'experience/thumb/';
                if($type == 23)
                   $path = $type_name.'/thumb/';
                if($type == 26)
                    $path = 'spiritual-journey/thumb/';
                if($type == 29)
                    $path = 'moment/thumb/';
                if($type == 40)
                    $path = 'milestone/thumb/';
            }
            return Self::get_s3_file($path,$file);
        }
        else{
            if($type == 2)
                $path = 'images/default/wishlist_default.jpg';
            if($type == 5)
                $path = 'images/default/ideas_default.jpg';
            if($type == 9)
                $path = 'images/default/diary_default.png';
            if($type == 12)
                $path = 'images/default/lifelesson_default.jpg';
            if($type == 15)
                $path = 'images/default/dream_default.jpg';
            if($type == 18)
                $path = 'images/default/education_default.jpg';
            if($type == 19)
                $path = 'images/default/career_default.jpg';
            if($type == 20)
                $path = 'images/default/experience_default.jpg';
            if($type == 23)
                $path = 'images/default/firstlast_default.jpg';
            if($type == 26)
                $path = 'images/default/spiritualjourney_default.jpg';
            if($type == 29)
                $path = 'images/default/moment_default.jpg';
            if($type == 40)
                $path = 'images/default/milestone_default.jpg';
        }
        return $path;
        
    }    
    public static function feed_info_home($type,$type_name){
            if($type == 2)
                $pathnew = '/wishlist/information/';
            if($type == 5)
                $pathnew = '/idea/information/';
            if($type == 9)
                $pathnew = '/diary/information/';
            if($type == 12)
                $pathnew = '/lifelesson/information/';
            if($type == 15)
                $pathnew = '/dream/information/';
            if($type == 18)
                $pathnew = '/education/information/';
            if($type == 19)
                $pathnew = '/career/information/';
            if($type == 20)
                $pathnew = '/experience/information/';
            if($type == 23)
                $pathnew =  $type_name.'/information/';
            if($type == 26)
                $pathnew = '/spiritual-journey/information/';
            if($type == 29)
                $pathnew = '/special-moments/information/';
            if($type == 40)
                $pathnew = '/milestone/information/';
            if($type == 39)
                $pathnew = '';

            return $pathnew;
    }
    public static function like_comment_count($num) {
        if($num>999) {
            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('K', 'M');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];
            return $x_display;
        }
        return $num;
    }

    public static function PostCreationEmail($type_id,$feed_id,$module,$first_last,$feedPrivacy){
      $notification_data=array();
      if($feedPrivacy != 'private'){
        $result_family_data  = UserFamilyTree::whereHas('getFamilyTreeUser',function($query){
            $query->where('status','=','enable');
        })->where('email','=',Auth::user()->email)->where('status','=','enable')->where('request_status','=','accept')->where('family_tree_id','!=','0')->get();
        
        if(!empty($result_family_data)){
            $resultEmail=array();
            foreach($result_family_data as $key => $row){
                $result_email_user= @$row->getFamilyTreeUser->email;
                $login_user = @$row;
                if (!empty(@$login_user)){
                    array_push($notification_data,array(
                        'user_id' =>  @$login_user->getFamilyTreeUser->id,
                        'type_id' => $type_id,
                        'from_id' => Auth::user()->id,
                        'unique_id' => $feed_id,
                        'type' => 'feed',
                        'message' => ($first_last == 'first') ? 'Created first Post.' : (($first_last == 'last') ? 'Created last Post.' : 'Created '.config('custom_config.user_feed_type_name')[$type_id].' Post.'),
                        'created_at' => Carbon::now(),
                        'created_ip' => ip2long(\Request::ip()),
                    )); 
                    if(@$login_user->getFamilyTreeUser->email_notification == 1){
                        array_push($resultEmail,$result_email_user);  
                        $mail_data = array(
                            'subject' => ($first_last == 'first') ? 'First'.' post has been created by '.Auth::user()->first_name .' '. Auth::user()->last_name : ((($first_last == 'last') ? 'Last' : config('custom_config.user_feed_type_name')[$type_id])).' post has been created by '.Auth::user()->first_name .' '. Auth::user()->last_name,
                            'user_name' => @$row->getFamilyTreeUser->first_name.' '.@$row->getFamilyTreeUser->last_name,
                            'user_email' => @$result_email_user,
                            'sentence' => ($first_last == 'first') ? Auth::user()->first_name .' '. Auth::user()->last_name.' has created post on First. Please check the post using the below button' : (($first_last == 'last') ? Auth::user()->first_name .' '. Auth::user()->last_name.' has created post of Last. Please check the post using the below button' : Auth::user()->first_name .' '. Auth::user()->last_name.' has created post of '.config('custom_config.user_feed_type_name')[$type_id].'. Please check the post using the below button'),
                            'post_url' => '/'.strtolower($module).'/information/'.$feed_id,
                        );
                        
                        self::email_creation_post($mail_data);
                    }
                
                }else{
                    array_push($resultEmail,$result_email_user);   
                    $mail_data = array(
                        'subject' => ($first_last == 'first') ? 'First'.' post has been created by '.Auth::user()->first_name .' '. Auth::user()->last_name : ((($first_last == 'last') ? 'Last' : config('custom_config.user_feed_type_name')[$type_id])).' post has been created by '.Auth::user()->first_name .' '. Auth::user()->last_name,
                        'user_name' => @$row->getFamilyTreeUser->first_name.' '.@$row->getFamilyTreeUser->last_name,
                        'user_email' => @$result_email_user,
                        'sentence' => ($first_last == 'first') ? Auth::user()->first_name .' '. Auth::user()->last_name.' has created post of First. Please check post using below button' : (($first_last == 'last') ? Auth::user()->first_name .' '. Auth::user()->last_name.' has created post of Last. Please check post using below button' : Auth::user()->first_name .' '. Auth::user()->last_name.' has created post of '.config('custom_config.user_feed_type_name')[$type_id].'. Please check the post using the below button'),
                        'post_url' => '/'.strtolower(config('custom_config.user_feed_type_name')[$type_id]).'/information/'.$feed_id,
                       
                    );
                    self::email_creation_post($mail_data);
                }   
            }
            if(!empty(@$notification_data)){
                UserNotification::insert($notification_data);
            }
        }
      }
    
    }
    public static function email_creation_post($mail_data){       
        // get site email
        try{
            $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'like-comment')->first();
            // parameter for mail template and function to send

            $msg = $mail_temp['content'];
            
            $msg = str_replace('[USER_NAME]', ucfirst($mail_data['user_name']), $msg);
            $msg = str_replace('[SITE_URL]', url(''), $msg);
            $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
            $msg = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $msg);
            $msg = str_replace('[YEAR]', date('Y'), $msg);
            $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);
            $msg = str_replace('[SENTENCE]', $mail_data['sentence'], $msg);
            $msg = str_replace('[DETAILPAGE_LINK]', url($mail_data['post_url']), $msg);
          
            $email_data['to_email'] = $mail_data['user_email'];
            $email_data['subject'] = str_replace('[SITE_NAME]',config('custom_config.settings.site_name'),$mail_temp['subject']);
            $email_data['subject'] = str_replace('[SUBJECT]',$mail_data['subject'],$email_data['subject'] );
            $email_data['from_email'] = $mail_temp['from_email'];
            $email_data['message'] = $msg;  
           
            Mail::send([], [], function ($message) use ($email_data) {
                $message->to($email_data['to_email'])
                    ->from($email_data['from_email'], config('custom_config.settings.site_name'))
                    ->subject(str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $email_data['subject']))
                    ->setBody($email_data['message'], 'text/html');
            });
           
        }catch(\Exception $e){
        }
    }
    public static function previous_url(){
        $url =url()->previous();
        $name = explode('?',$url);
        $final = implode(',',$name);
        $final_url = explode('/',$final);
        return [$final_url,$url];
        
    }
    public static function age($bday){
        $birthday = new DateTime($bday); // Your date of birth
        $today = new Datetime(date('Y-m-d'));
        $diff = $today->diff($birthday);
        return $diff->y;
    }
   
    public static function mynetworth_verification_otp_email($username,$otp){
       try{
            $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'mynetworth-nominee-verification')->first();
            // parameter for mail template and function to send
            $msg = $mail_temp['content'];

            $msg = str_replace('[SITE_URL]', url(''), $msg);
            $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
            $msg = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $msg);
            $msg = str_replace('[YEAR]', date('Y'), $msg);
        
            $msg = str_replace('[USER_NAME]', ucfirst($username), $msg);
            $msg = str_replace('[OTP_CODE]', $otp, $msg);
            $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);
            $msg = str_replace('[CONTACT_US]', config('custom_config.settings.contact_email'), $msg);

            $email_data['from_email'] = $mail_temp['from_email'];
            $email_data['to_email'] = Auth::user()->email;
            $email_data['subject'] = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $mail_temp['subject']);
            $email_data['message'] = $msg;
        
            Mail::send([], [], function ($message) use ($email_data) {
                $message->to($email_data['to_email'])
                    ->from($email_data['from_email'], config('custom_config.settings.site_name'))
                    ->subject( $email_data['subject'])
                    ->setBody($email_data['message'], 'text/html');
            });
        }catch(\Exception $e){
        }

    }
}