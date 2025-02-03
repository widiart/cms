<?php

namespace App\Http\Controllers;

use App\EventAttendance;
use App\Events;
use App\Facades\EmailTemplate;
use App\Feedback;
use App\FormBuilder;
use App\Helpers\NexelitHelpers;
use App\JobApplicant;
use App\Jobs;
use App\HomeContactUs;
use App\TheNoveContactUs;
use App\Mail\BasicMail;
use App\Mail\ContactMessage;
use App\Mail\CustomFormBuilderMail;
use App\Mail\FeedbackMessage;
use App\Mail\PlaceOrder;
use App\Mail\RequestQuote;
use App\Newsletter;
use App\Order;
use App\PricePlan;
use App\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use function GuzzleHttp\Promise\all;

class FrontendFormController extends Controller
{
    public function service_quote(Request $request)
    {
        $validated_data = $this->get_filtered_data_from_request(get_static_option('service_query_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];

        $succ_msg = get_static_option('service_query_' . get_user_lang() . '_success_message');
        $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your contact!!');
        try {
            Mail::to(get_static_option('service_single_page_query_form_email'))
                ->send(new ContactMessage(
                    $all_field_serialize_data,
                    $all_attachment,
                    __('You Have A Service Query Message')
                ));
        }catch (\Exception $e){
            return redirect()->back()->with(NexelitHelpers::item_delete($e->getMessage()));
        }

        return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);
    }

    public function case_study_quote(Request $request)
    {
        $validated_data = $this->get_filtered_data_from_request(get_static_option('case_study_query_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];
        
        $succ_msg = get_static_option('case_study_query_' . get_user_lang() . '_success_message');
        $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your contact!!');

        try {
            Mail::to(get_static_option('case_study_query_form_mail'))
                ->send(new ContactMessage(
                    $all_field_serialize_data,
                    $all_attachment,
                    __('You Have A Case Study Query Message')
                ));
        }catch (\Exception $e){
            return redirect()->back()->with(NexelitHelpers::item_delete($e->getMessage()));
        }

        return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);
        
    }

    public function appointment_message(Request $request)
    {

        $validated_data = $this->get_filtered_data_from_request(get_static_option('appointment_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];
        unset($all_field_serialize_data['captcha_token']);
        $google_captcha_result = google_captcha_check($request->captcha_token);

        /* for dev only */
        if (getenv('APP_ENV') === 'development' || empty(get_static_option('site_google_captcha_status'))) {
            $succ_msg = get_static_option('appointment_form_mail_'.get_user_lang().'_success_message');
            $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your contact!!');
            $email_address = get_static_option('medical_appointment_section_appointment_email') ?? get_static_option('site_global_email');
            try {
                Mail::to($email_address)
                    ->send(new ContactMessage(
                    $all_field_serialize_data,
                    $all_attachment,
                    __('You Have a appointment mail')
                ));
            }catch (\Exception $e){
                return response()->json([
                    'status' => '400',
                    'msg' => $e->getMessage()
                ]);
            }

            $data['status'] = '200';
            $data['msg'] = $success_message;

            return response()->json($data);
        }
        /* for dev only */

        if ($google_captcha_result['success'] && !empty(get_static_option('site_googl_captcha_status'))) {
            $data['status'] = '400';
            $data['msg'] = __('google recaptcha error');
            return response()->json($data);
        }
        $succ_msg = get_static_option('appointment_form_mail_'.get_user_lang().'_success_message');
        $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your contact!!');
        $email_address = get_static_option('medical_appointment_section_appointment_email') ?? get_static_option('site_global_email');

        try {
            Mail::to($email_address)
                ->send(new ContactMessage(
                    $all_field_serialize_data,
                    $all_attachment,
                    __('You Have a appointment mail')
                ));
        }catch (\Exception $e){
            return response()->json([
                'status' => '200',
                'msg' => $e->getMessage()
            ]);
        }

        $data['status'] = '200';
        $data['msg'] = $success_message;

        return response()->json($data);


    }

    public function get_touch(Request $request)
    {

        $validated_data = $this->get_filtered_data_from_request(get_static_option('get_in_touch_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];

        $google_captcha_result = google_captcha_check($request->captcha_token);

        if (!$google_captcha_result['success']) {
            $data['status'] = '400';
            $data['msg'] = __('google recaptcha error');
            return response()->json($data);
        }
        $succ_msg = get_static_option('get_in_touch_mail_'.get_user_lang().'_success_message');
        $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your contact!!');

        try {
            Mail::to(get_static_option('site_global_email'))
                ->send(new ContactMessage(
                    $all_field_serialize_data,
                    $all_attachment,
                    __('You Have Contact Mail')
                ));
        }catch (\Exception $e){
            return response()->json([
                'status' => '400',
                'msg' => $e->getMessage()
            ]);
        }

        $data['status'] = '200';
        $data['msg'] = $success_message;
        return response()->json($data);


        $data['status'] = '400';
        $data['msg'] = __('Something goes wrong, Please try again later !!');
        return response()->json($data);
    }

    public function subscribe_newsletter(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:191|unique:newsletters'
        ]);
        $verify_token = \Str::random(32);
        $newsletter = Newsletter::create([
            'email' => $request->email,
            'verified' => 0,
            'token' => $verify_token
        ]);
        try{
            //send verify mail to newsletter subscriber
            Mail::to($request->email)
                ->send(new BasicMail(EmailTemplate::newsletterVerifyMail($newsletter)));
        }catch (\Exception $e){
            return redirect()->back()->with(NexelitHelpers::item_delete($e->getMessage()));
        }

        return redirect()->back()->with([
            'msg' => __('Thanks for Subscribe Our Newsletter'),
            'type' => 'success'
        ]);
    }

    public function send_order_message(Request $request)
    {
        $validated_data = $this->get_filtered_data_from_request(get_static_option('order_page_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];


        $package_detials = PricePlan::find($request->package);

        $order_id = Order::create([
            'custom_fields' => serialize($all_field_serialize_data),
            'attachment' => serialize($all_attachment),
            'status' => 'pending',
            'package_name' => $package_detials->title,
            'package_price' => $package_detials->price,
            'package_id' => $package_detials->id,
            'checkout_type' => !empty($request->checkout_type) ? $request->checkout_type : '',
            'user_id' => Auth::guard('web')->check() ? Auth::guard('web')->user()->id : 0,
        ])->id;

        if (!empty(get_static_option('site_payment_gateway'))) {
            return redirect()->route('frontend.order.confirm', $order_id);
        }

        $google_captcha_result = google_captcha_check($request->captcha_token);
        if (!$google_captcha_result['success'] ) {
            return redirect()->back()->with(['msg' => __('google recaptcha error, please reload the page and try again!!'), 'type' => 'danger']);
        }

        $succ_msg = get_static_option('order_mail_' . get_user_lang() . '_success_message');
        $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your order. we will get back to you very soon.');
        $order_rmail = get_static_option('order_page_form_mail');
        $order_mail = $order_rmail ?? get_static_option('site_global_email');

        //have to set condition for redirect in payment page with payment information
        if (!empty(get_static_option('site_payment_gateway'))) {
            return redirect()->route('frontend.order.confirm', $order_id);
        }

        try {
            Mail::to($order_mail)
                ->send(new BasicMail([
                    'subject' => __('You have a package order from').' '.get_static_option('site_'.get_default_language().'_title'),
                    'message' => __('You have a package order') .' #'.$order_id.' '.__('checkout your dashboard for more info'),
                ]));
        }catch (\Exception $e){
            return redirect()->back()->with(NexelitHelpers::item_delete($e->getMessage()));
        }

        return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);

    }


    public function send_contact_message(Request $request)
    {

        $validated_data = $this->get_filtered_data_from_request(get_static_option('contact_page_contact_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];

        $google_captcha_result = google_captcha_check($request->captcha_token);
        if (!$google_captcha_result['success'] ) {

            return redirect()->back()->with(['msg' =>  __('google recaptcha error'), 'type' => 'danger']);
        }

        $succ_msg = get_static_option('contact_mail_' . get_user_lang() . '_success_message');
        $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your contact!!');
        try {
            Mail::to(get_static_option('site_global_email'))
                ->send(new ContactMessage(
                    $all_field_serialize_data,
                    $all_attachment,
                    __('You Have Contact Message from').' '.get_static_option('site_'.get_default_language().'_title')
                ));
        }catch (\Exception $e){
            return redirect()->back()->with(NexelitHelpers::item_delete($e->getMessage()));
        }
        return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);
    }

    public function nuvasa_form_otp_verify(Request $request)
    {
        $field_details = FormBuilder::find($request->id);
        
        $otp = $request->session()->get($request->otp_id);

        $input = '';
        foreach($request->otp as $key => $value) {
            $input .= $value;
        }

        $success_message = $field_details->success_message ?? __('Thanks for your contact!!');
        
        if($otp != $input) {
            return ['msg'=>'OTP Code is Not Valid','status'=>'failed', 'type'=>'danger'];
        }

        return ['msg'=>$success_message,'status'=>'ok'];
    }

    public function nuvasa_form_otp(Request $request)
    {
        $url = 'https://api.myvaluefirst.com/psms/servlet/psms.JsonEservice';
        $username = "ptbumixmlint";
        $password = "ptbumi21";
        $field_details = FormBuilder::find($request->custom_form_id);
        $validated_data = $this->get_filtered_data_from_request($field_details->fields,$request,false);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];
        
        $trimHP = $all_field_serialize_data['phone-number'];
        
        $random = random_int(100000,999999);
        $splitMessage = [
            'Your OTP Code for Nuvasabay is : ',
            $random,
        ];

        if($request->session()->missing($all_field_serialize_data['id'])) {
            $request->session()->put($all_field_serialize_data['id'], $random);
    
            $arrayName = array(
                "@VER" => "1.2",
                "USER" => array(
                    "@USERNAME" => "" . $username . "",
                    "@PASSWORD" => "" . $password . "",
                    "@UNIXTIMESTAMP" => ""
                ),
    
                "DLR" => array(
                    "@URL" => ""
                ),
                "SMS" => array(
                            array(
                                "@UDH" => "0",
                                "@CODING" => "1",
                                // "@TEXT" => "TEST, Kepada YTH customer dengan no.pelanggan 12345.%0aNomor handphone ".$trimHP." sudah berhasil di verifikasi ",
                                "@TEXT" => "" . $splitMessage[0] . " " . $splitMessage[1] . "",
                                "@PROPERTY" => "0",
                                "@ID" => "" .$splitMessage[1] . "",
                                // "@ID" => "001",
                                "ADDRESS" => array(
                                                array(
                                                    "@FROM" => "SinarmasLnd",
                                                    "@TO" => "" . $trimHP . "",
                                                    "@SEQ" => "" . $splitMessage[1] . "",
                                                    // "@SEQ" => "001",
                                                    "@TAG" => "Registration OTP"
                                                )
                                )
                            )
                )
            );
    
            $data_string = json_encode($arrayName);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type:application/json"
                )
            ));
        }

        return $splitMessage;
    }

    public function nuvasa_form_cpi(Request $request)
    {
        $field_details = FormBuilder::find($request->custom_form_id);
        if(empty($field_details)){
            return response()->json(['msg' => __('form not valid'),'type' => 'danger']);
        }

        $validated_data = $this->get_filtered_data_from_request($field_details->fields,$request,false);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];
        $utm_source = get_cookie('utm_source') ?? "";
        $utm_medium = get_cookie('utm_medium') ?? "";
        $utm_campaign = get_cookie('utm_campaign') ?? "";
        $utm_content = get_cookie('utm_content') ?? "";
        $utm_id = get_cookie('utm_id') ?? "";

        $cookie = [
            "utm_source" => $utm_source,
            "utm_medium" => $utm_medium,
            "utm_campaign" => $utm_campaign,
            "utm_content" => $utm_content,
            "utm_id" => $utm_id,
        ];

        $name = $all_field_serialize_data['name'];
        $email = $all_field_serialize_data['email-address'];
        $telp = $all_field_serialize_data['phone-number'];
        $otp = $request->session()->get($request->otp_id);

        if($request->custom_form_id == 2) {
            // ====CPI====
            $paramWithUTM = array(
                "utm_source" => $utm_source,
                "utm_medium" => $utm_medium,
                "utm_campaign" => $utm_campaign,
                "utm_content" => $utm_content,
                "utm_id"=> $utm_id,
                "fullname" => $name,
                "email" => $email,
                "mobile" => $telp,
                "project_name" => $all_field_serialize_data['project_name'],
                "projects_code" => $all_field_serialize_data['project_code'],
                "cluster_code" => $all_field_serialize_data['cluster_code'],
                "lead_source_name" => $all_field_serialize_data['lead_source_name'],
                "web" => request()->getHttpHost(),
            );

            $paramWithOutUTM = array(
                "fullname" => $name,
                "email" => $email,
                "mobile" => $telp,
                "project_name" => $all_field_serialize_data['project_name'],
                "projects_code" => $all_field_serialize_data['project_code'],
                "cluster_code" => $all_field_serialize_data['cluster_code'],
                "lead_source_name" => $all_field_serialize_data['lead_source_name'],
                "web" => request()->getHttpHost(),                                              
            );

            if ($utm_source == '' && $utm_medium == '' && $utm_campaign == '' && $utm_content == '' && $utm_id == '') {
                $parameterCPI = json_encode($paramWithOutUTM);
            } else {
                $parameterCPI = json_encode($paramWithUTM);
            }
            //print_r($parameterCPI);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://l201091-iflmap.hcisbp.ap1.hana.ondemand.com/http/LandingPageLeadCreation',//sesuaikan url dengan cpi yg di infokan oleh tim CRM
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $parameterCPI,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic UzAwMTg3MDU4MDA6U21sMTIzNDUh',
                    'Content-Type: application/xml',
                    'Cookie: JSESSIONID=6E96DB1640A7AE81900C01B359B6C915; JTENANTSESSIONID_gdl5d0zxbi=pcc7UODdN9BtBH%2FAsxss1Q1iyM3tDzoT8ZK5VoDqI4A%3D; BIGipServere200505iflmapavshcie.factoryap1.customdomain=!Cge9rQNqV+9i9LHRvomOhEuQFNye6aS+4vV63b6+HXDn4E3pEe6CCukGxYfM508NrjRh6azUWe+wCTs=; sap-usercontext=sap-client=141'
                ),
            ));
            //$response = curl_exec($curl);
            curl_exec($curl);
            curl_close($curl);
            // dd($all_field_serialize_data);

            TheNoveContactUs::create([
                "name" => $name,
                "email" => $email,
                "phone" => $telp,
                "otp_code" => $otp,
                "ip_client" => request()->ip(),
                "phone" => $telp,
                "message" => $all_field_serialize_data['message'],
                "project_name" => $all_field_serialize_data['project_name'],
                "project_code" => $all_field_serialize_data['project_code'],
                "cluster_code" => $all_field_serialize_data['cluster_code'],
                "lead_source_name" => $all_field_serialize_data['lead_source_name'],
                "cookie" => json_encode($cookie),
            ]);

            $request->session()->forget($request->otp_id);
            return ['status'=>'the_nove'];
            
        } else {
            $request->session()->forget($request->otp_id);
            return ['status'=>'home'];
        }

    }

    public function nuvasa_form_builder_message(Request $request)
    {
        $this->validate($request,[
            "custom_form_id" => 'required'
        ]);
        $field_details = FormBuilder::find($request->custom_form_id);
        if(empty($field_details)){
            return response()->json(['msg' => __('form not valid'),'type' => 'danger', 'status'=>'fail']);
        }

        $validated_data = $this->get_filtered_data_from_request($field_details->fields,$request,false);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];

        $success_message = $field_details->success_message ?? __('Thanks for your contact!!');
        
        if(empty($all_field_serialize_data['is_otp'])) {
            $name = $all_field_serialize_data['name'];
            $email = $all_field_serialize_data['email-address'];
            $telp = $all_field_serialize_data['phone-number'];
            $utm_source = get_cookie('utm_source') ?? "";
            $utm_medium = get_cookie('utm_medium') ?? "";
            $utm_campaign = get_cookie('utm_campaign') ?? "";
            $utm_content = get_cookie('utm_content') ?? "";
            $utm_id = get_cookie('utm_id') ?? "";
            
            $cookie = [
                "utm_source" => $utm_source,
                "utm_medium" => $utm_medium,
                "utm_campaign" => $utm_campaign,
                "utm_content" => $utm_content,
                "utm_id" => $utm_id,
            ];
    
            if($request->custom_form_id == 2) {
                TheNoveContactUs::create([
                    "name" => $name,
                    "email" => $email,
                    "phone" => $telp,
                    "ip_client" => request()->ip(),
                    "message" => $all_field_serialize_data['message'],
                    "project_name" => $all_field_serialize_data['project_name'],
                    "project_code" => $all_field_serialize_data['project_code'],
                    "cluster_code" => $all_field_serialize_data['cluster_code'],
                    "lead_source_name" => $all_field_serialize_data['lead_source_name'],
                    "cookie" => json_encode($cookie),
                ]);
            } else {
                HomeContactUs::create([
                    "name" => $name,
                    "email" => $email,
                    "phone" => $telp,
                    "ip_client" => request()->ip(),
                    "message" => $all_field_serialize_data['message'],
                    "project_name" => $all_field_serialize_data['project_name'],
                    "project_code" => $all_field_serialize_data['project_code'],
                    "cluster_code" => $all_field_serialize_data['cluster_code'],
                    "lead_source_name" => $all_field_serialize_data['lead_source_name'],
                    "cookie" => json_encode($cookie),
                ]);
            }
        }

        return response()->json(['msg' => $success_message, 'type' => 'success','status'=>'ok']);
    }

    public function custom_form_builder_message(Request $request)
    {
        $this->validate($request,[
            "custom_form_id" => 'required'
        ]);
        if(in_array($request->custom_form_id,[2,3])) {
            return $this->nuvasa_form_builder_message($request);
        }
        $field_details = FormBuilder::find($request->custom_form_id);
        if(empty($field_details)){
            return response()->json(['msg' => __('form not valid'),'type' => 'danger']);
        }
        unset($request['custom_form_id']);
        $validated_data = $this->get_filtered_data_from_request($field_details->fields,$request,false);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];
        $success_message = $field_details->success_message ?? __('Thanks for your contact!!');
        $google_captcha_result = google_captcha_check($request->captcha_token);
        dd($google_captcha_result);
        if (!$google_captcha_result['success']) {
            return response()->json(['msg' => __('google recaptcha error!!, reload the page and try again'), 'type' => 'danger']);
        }
        try {
            Mail::to($field_details->email)->send(
                new CustomFormBuilderMail([
                    'data' => [
                        'all_fields' => $all_field_serialize_data,
                        'attachments' => $all_attachment
                    ],
                    'form_title' => $field_details->title,
                    'subject' => sprintf(__('You Have %s Message from'),$field_details->title).' '.get_static_option('site_'.get_default_language().'_title')
                ])
            );
        }catch(\Exception $e){
            return response()->json(['msg' => $e->getMessage(), 'type' => 'danger']);
        }

        return response()->json(['msg' => $success_message, 'type' => 'success']);
    }



    public function send_quote_message(Request $request)
    {

        $validated_data = $this->get_filtered_data_from_request(get_static_option('quote_page_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];

        $quote_details = Quote::create([
            'custom_fields' => serialize($all_field_serialize_data),
            'status' => 'pending',
            'attachment' => serialize($all_attachment)
        ]);

        $google_captcha_result = google_captcha_check($request->captcha_token);

        if (!$google_captcha_result['success'] ) {
            return redirect()->back()->with(['msg' =>__( 'google recaptcha error!!'), 'type' => 'danger']);
        }
        //have to check mail
        $succ_msg = get_static_option('quote_mail_' . get_user_lang() . '_success_message');
        $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your quote. we will get back to you very soon.');

        try {
            Mail::to(get_static_option('quote_page_form_mail'))
                ->send(new BasicMail(EmailTemplate::quoteAdminMail($quote_details)));
        }catch (\Exception $e){
            return redirect()->back()->with(NexelitHelpers::item_delete($e->getMessage()));
        }

        return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);



    }

    public function store_event_booking_data(Request $request){

        $validated_data = $this->get_filtered_data_from_request(get_static_option('event_attendance_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];

        $event_detials = Events::find($request->event_id);
        $event_attendance_id = EventAttendance::create([
            'custom_fields' => serialize($all_field_serialize_data),
            'status' => 'pending',
            'event_name' => $event_detials->title,
            'event_cost' => $event_detials->cost,
            'quantity' => $request->quantity,
            'event_id' => $request->event_id,
            'checkout_type' => !empty($request->checkout_type) ? $request->checkout_type : '',
            'user_id' => Auth::guard('web')->check() ? Auth::guard('web')->user()->id : 0,
            'attachment' => serialize($all_attachment)
        ])->id;

        if (in_array(env('APP_ENV'), ['development','local'])){
            //have to set condition for redirect in payment page with payment information
            if (!empty(get_static_option('site_payment_gateway'))) {

                $succ_msg = get_static_option('event_attendance_mail_' . get_user_lang() . '_subject');
                $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your Booking. we will get back to you very soon.');
                $order_mail = get_static_option('event_attendance_receiver_mail') ? get_static_option('event_attendance_receiver_mail') : get_static_option('site_global_email');

                if ($event_detials->cost == 0 || empty(get_static_option('site_payment_gateway'))){
                    try {
                        Mail::to($order_mail)
                            ->send(new ContactMessage(
                                $all_field_serialize_data,
                                $all_attachment,
                                __('Your have an event booking for').' '.$event_detials->title
                            ));

                    }catch (\Exception $e){
                        return redirect()->back()->with(NexelitHelpers::item_delete($e->getMessage()));
                    }
                    return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);
                }

                return redirect()->route('frontend.event.booking.confirm', $event_attendance_id);

            }
            event(new Events\AttendanceBooking([
                'attendance_id' => $event_attendance_id,
                'transaction_id' => 'free event'
            ]));
            $success_message = __('Thanks for your Booking. we will get back to you very soon.');
            return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);
        }


        // $google_captcha_result = google_captcha_check($request->captcha_token);
        // if ($google_captcha_result['success'] && !empty(get_static_option('site_google_captcha_status'))) {
        //     return redirect()->back()->with(['msg' => __('google recaptcha error'), 'type' => 'danger']);
        // }

        //have to set condition for redirect in payment page with payment information
        if (!empty(get_static_option('site_payment_gateway'))) {

            $succ_msg = get_static_option('event_attendance_mail_' . get_user_lang() . '_subject');
            $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your Booking. we will get back to you very soon.');
            $order_mail = get_static_option('event_attendance_receiver_mail') ? get_static_option('event_attendance_receiver_mail') : get_static_option('site_global_email');

            if ($event_detials->cost == 0 || empty(get_static_option('site_payment_gateway'))){
                try {
                    Mail::to($order_mail)->send(new ContactMessage($all_field_serialize_data, $all_attachment, __('Your have an event booking for').' '.$event_detials->title));
                }catch (\Exception $e){
                    return redirect()->back()->with(NexelitHelpers::item_delete($e->getMessage()));
                }
                return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);
            }

            return redirect()->route('frontend.event.booking.confirm', $event_attendance_id);

        }
         event(new Events\AttendanceBooking([
            'attendance_id' => $event_attendance_id,
            'transaction_id' => 'free event'
        ]));
        $success_message = get_static_option('event_attendance_mail_'.get_user_lang().'_success_message') ?? __('Thanks for your Booking. we will get back to you very soon.');
        return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);


    }

    public function clients_feedback_store(Request $request){
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'ratings' => 'required|string|max:191',
            'description' => 'nullable|string',
        ],
        [
            'name.required' => __('Name field is required'),
            'email.required' => __('Email field is required'),
            'ratings.required' =>__('Ratings field is required'),
        ]);

        $validated_data = $this->get_filtered_data_from_request(get_static_option('feedback_page_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];
        
        $google_captcha_result = google_captcha_check($request->captcha_token);
        if (!$google_captcha_result['success']) {
            return redirect()->back()->with(['msg' =>  __('google recaptcha error!!'), 'type' => 'danger']);
        }
        
        $feedback = Feedback::create([
            'name' => $request->name,
            'email' => $request->email,
            'ratings' => $request->ratings,
            'description' => $request->description,
            'custom_fields' => serialize($all_field_serialize_data),
            'attachment' => serialize($all_field_serialize_data)
        ]);


       //have to check mail
        $succ_msg = get_static_option('feedback_mail_' . get_user_lang() . '_success_message');
        $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your feedback.');
        $feedback_email_address = get_static_option('feedback_notify_mail') ?? get_static_option('site_global_email');
        try {
            Mail::to($feedback_email_address)
                ->send(new FeedbackMessage([
                    'field_name' => $all_field_serialize_data,
                    'feedback' => $feedback],
                    $all_attachment,
                    __('Your Have A Feedback Message From').' '.get_static_option('site_'.get_default_language().'_title')
                ));
        }catch (\Exception $e){
            return redirect()->back()->with(NexelitHelpers::item_delete($e->getMessage()));
        }

        return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);
    }


    public function get_filtered_data_from_request($option_value,$request,$file_save = true){

        $all_attachment = [];
        $all_quote_form_fields = (array) json_decode($option_value);
        $all_field_type = isset($all_quote_form_fields['field_type']) ? (array) $all_quote_form_fields['field_type'] : [];
        $all_field_name = isset($all_quote_form_fields['field_name']) ? $all_quote_form_fields['field_name'] : [];
        $all_field_required = isset($all_quote_form_fields['field_required'])  ? (object) $all_quote_form_fields['field_required'] : [];
        $all_field_mimes_type = isset($all_quote_form_fields['mimes_type']) ? (object) $all_quote_form_fields['mimes_type'] : [];

        //get field details from, form request
        $all_field_serialize_data = $request->all();
        unset($all_field_serialize_data['_token']);
        if (isset($all_field_serialize_data['captcha_token'])){
            unset($all_field_serialize_data['captcha_token']);
        }


        if (!empty($all_field_name)){
            foreach ($all_field_name as $index => $field){

                $is_required = !empty($all_field_required) && property_exists($all_field_required,$index) ? $all_field_required->$index : '';
                $mime_type = !empty($all_field_mimes_type) && property_exists($all_field_mimes_type,$index) ? $all_field_mimes_type->$index : '';
                $field_type = isset($all_field_type[$index]) ? $all_field_type[$index] : '';
                $validation_rules = [];
                $validation_rules[] = !empty($is_required) ? 'required': 'nullable';
                if (!empty($field_type) && $field_type === 'file'){
                    unset($all_field_serialize_data[$field]);
                    if (!empty($mime_type)){
                        $validation_rules[]  = $mime_type;
                        $validation_rules[]  = 'max:20000';
                    }
                }
                if ($field_type === 'email'){
                    $validation_rules[]  = 'email';
                }
                //validate field
                $this->validate($request,[
                    $field => implode('|',$validation_rules)
                ]);

                if ($field_type == 'file' && $request->hasFile($field)) {
                    $filed_instance = $request->file($field);
                    $file_extenstion = $filed_instance->extension();

                    if (!$file_save){
                        $attachment_name = 'custom-form-file-'.Str::random(32).'-'. $field .'.'. $file_extenstion;
                        $filed_instance->move('assets/uploads/custom-form-files', $attachment_name);
                        $all_attachment[$field] = 'assets/uploads/custom-form-files/' . $attachment_name;
                    }else {
                        $attachment_name = 'attachment-' . Str::random(32) . '-' . $field . '.' . $file_extenstion;
                        $filed_instance->move('assets/uploads/attachment/applicant', $attachment_name);
                        $all_attachment[$field] = 'assets/uploads/attachment/applicant/' . $attachment_name;
                    }
                }
            }
        }
        return [
            'all_attachment' => $all_attachment,
            'field_data' => $all_field_serialize_data
        ];
    }

    public function send_estimate_message(Request $request){

        $validated_data = $this->get_filtered_data_from_request(get_static_option('estimate_form_fields'),$request);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];

        $google_captcha_result = google_captcha_check($request->captcha_token);
        if ($google_captcha_result['success'] &&  !empty(get_static_option('site_google_captcha_status'))) {
            $data['status'] = '400';
            $data['msg'] = __('google recaptcha error!!');
            return response()->json($data);
        }

        $succ_msg = get_static_option('estimate_form_mail_' . get_user_lang() . '_success_message');
        $success_message = !empty($succ_msg) ? $succ_msg : __('Thanks for your contact!!');
        $send_mail = get_static_option('home_page_16_estimate_area_form_email') ?? get_static_option('site_global_email');
        try {
            Mail::to($send_mail)
                ->send(new ContactMessage(
                    $all_field_serialize_data,
                    $all_attachment,
                    __('You Have Estimate Message from').' '.get_static_option('site_'.get_default_language().'_title')
                ));
        }catch (\Exception $e){
            $data['status'] = '400';
            $data['msg'] = $e->getMessage();
            return response()->json($data);
        }
        $data['status'] = '200';
        $data['msg'] = $success_message;

        return response()->json($data);
    }
}