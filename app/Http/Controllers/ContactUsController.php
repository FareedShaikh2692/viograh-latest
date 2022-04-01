<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ContactUs;
use App\MailSetting;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\User;
use Auth;

class ContactUsController extends Controller
{
    public function showcontactusform(Request $request){
        
        $data = array('title'=>"Contact Us",'action'=>url('contact-us'),'frn_id'=>'frm_contact_us');

        $data['sessiondata'] = Auth::user();
        return view('contact_us',$data);
    }

    public function insertcontactusform(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'name' => 'required|min:2|max:100|regex:/^[a-zA-Z]+$/',
            'email'=>'required|email|max:200',
            'contact_number' =>'nullable|numeric|digits_between:6,20',
            'subject' =>'required|min:2|max:200',
            'message'=>'required|min:10|max:500',
        );
        $rules = [
            'name.required' => trans('custom.js_required_with_attribute'),
            'name.min' => trans('custom.js_min_lengths_serverside'),
            'name.max' => trans('custom.js_max_lengths_serverside'),
            'name.regex' => trans('custom.only_alphabets'), 
            'email.required' =>  trans('custom.js_required_with_attribute'),
            'email.email' => trans('custom.check_email'),
            'email.max' => trans('custom.js_max_lengths_serverside'),
            'contact_number.numeric' => trans('custom.check_numeric'),
            'contact_number.digits_between' => trans('custom.contact_num'), 
            'subject.required' =>  trans('custom.js_required_with_attribute'),
            'subject.min' => trans('custom.js_min_lengths_serverside'),
            'subject.max' => trans('custom.js_max_lengths_serverside'),
            'message.required' => trans('custom.js_required_with_attribute'),
            'message.min' => trans('custom.js_min_lengths_serverside'),
            'message.max' => trans('custom.js_max_lengths_serverside'),
        ];

        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array, $rules);

        $insert_array = array(
            'name' => ucwords($request->name),
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => 0,
            'status' => 'enable',
            'created_ip' => ip2long(\Request::ip()),
            'created_at'=>Carbon::now(),
            'updated_at' => Carbon::now(),
        );

        ContactUs::insert($insert_array);
        $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'contact-mail-to-admin')->first();
        
        $msg = $mail_temp['content'];
        $msg = str_replace('[USER_NAME]', $request->name, $msg);
        $msg = str_replace('[SITE_URL]', url(''), $msg);
        $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
        $msg = str_replace('[SITE_NAME]',  config('custom_config.settings.site_name'), $msg);
        $msg = str_replace('[CONTACTINC_NAME]', $request->name, $msg);
        $msg = str_replace('[CONTACTINC_EMAIL]', $request->email, $msg);
        $msg = str_replace('[SUBJECTINC]', $request->subject, $msg);
        $msg = str_replace('[CONTACTINC_MESSEGE]', $request->message, $msg);
        $msg = str_replace('[YEAR]', date('Y'), $msg);
        $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);

        $email_data['to_email'] = $mail_temp['to_email'];
        $email_data['subject'] = $mail_temp['subject'];
        $email_data['from_email'] = $mail_temp['from_email'];
        $email_data['message'] = $msg;
        
        Mail::send([], [], function ($message) use ($email_data) {
            $message->to($email_data['to_email'])
                ->from($email_data['from_email'], config('custom_config.settings.site_name'))
                ->subject(str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $email_data['subject']))
                ->setBody($email_data['message'], 'text/html');
            });
            if (Mail::failures()) {
                //ERROR MAIL NOT SEND
                return redirect('/contact-us')->withInput()->withError(trans('custom.mail_send_error'));
            }
        return redirect('/contact-us')->withSuccess(trans('custom.succ_contact_us_send'));
    }
}
