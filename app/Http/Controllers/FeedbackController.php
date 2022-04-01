<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MailSetting;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\User;
use App\UserFeedback;
use Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'feedback';
    }

    public function index(Request $request){
        // VALIDATION RULE
        $validator = \Validator::make($request->all(), [
            'type'=> 'required',
            'subject' => 'required|max:200',
            'message'=> 'required|min:10',
        ],
        [
            'type.required' => 'The type is required',
            'subject.required' => 'The subject is required',         
            'message.required' => 'The message is required',
        ]
        );

        // CHECK SERVER SIDE VALIDATION
        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->all() as $row){
                $msg .= $row."</br>";
            }
            $data['msg'] = $msg;
            $data['code'] = 0;
            return response()->json($data);exit;
        }else{
            $insert_array = array(
                'user_id' => $request->userid,
                'url' => $request->url,
                'subject' => $request->subject,
                'message' => $request->message,
                'type' => $request->type,
                'is_read' => 0,
                'status' => 'enable',
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            UserFeedback::insert($insert_array);
            try{
                $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'feedback-mail-to-admin')->first();
        
                $msg = $mail_temp['content'];
                $msg = str_replace('[NAME]', Auth::user()->first_name.' '.Auth::user()->last_name, $msg);
                $msg = str_replace('[SITE_URL]', url(''), $msg);
                $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
                $msg = str_replace('[SITE_NAME]',  config('custom_config.settings.site_name'), $msg);
                $msg = str_replace('[TYPE]', $request->type, $msg);
                $msg = str_replace('[SUBJECT]', $request->subject, $msg);
                $msg = str_replace('[MESSEGE]', $request->message, $msg);
                $msg = str_replace('[YEAR]', date('Y'), $msg);
                $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);
        
                $email_data['to_email'] = $mail_temp['to_email'];
                $email_data['subject'] = str_replace('[SITE_NAME]',config('custom_config.settings.site_name'),$mail_temp['subject']);
                $email_data['subject'] = str_replace('[NAME]',Auth::user()->first_name.' '.Auth::user()->last_name, $email_data['subject'] );
                $email_data['from_email'] = $mail_temp['from_email'];
                $email_data['message'] = $msg;
               
                Mail::send([], [], function ($message) use ($email_data) {
                    $message->to($email_data['to_email'])
                        ->from($email_data['from_email'], config('custom_config.settings.site_name'))
                        ->subject(str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $email_data['subject']))
                        ->setBody($email_data['message'], 'text/html');
                });
            }
            catch(\Exception $e){
            }
            return response()->json(['code' => '1','msg' => 'Thank you for your feedback.']);exit;
        }
    }
}
