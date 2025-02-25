<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register_module extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public $validate_settings = array(
        array('field' => 'fname', 'label' => 'lang:register_fname', 'rules' => 'trim|required'),
        array('field' => 'lname', 'label' => 'lang:register_lname', 'rules' => 'trim|required'),
        array('field' => 'email', 'label' => 'lang:register_email', 'rules' => 'trim|required|valid_email|is_unique[members.email]'),
        array('field' => 'country', 'label' => 'lang:register_country', 'rules' => 'required'),
//        array('field' => 'zip', 'label' => 'lang:zip', 'rules' => 'trim|required|alpha_numeric'),
        array('field' => 'user_name', 'label' => 'lang:register_uname', 'rules' => 'trim|required|min_length[4]|max_length[20]|is_unique[members.user_name]'),
        array('field' => 'password', 'label' => 'lang:register_pass', 'rules' => 'trim|required|min_length[4]|max_length[20]'),
//        array('field' => 're_password', 'label' => 'lang:register_repass', 'rules' => 'required|matches[password]'),
        array('field' => 'dobday', 'label' => 'lang:day', 'rules' => 'required|numeric'),
        array('field' => 'city', 'label' => 'lang:city', 'rules' => 'required'),
        array('field' => 'state', 'label' => 'lang:state', 'rules' => 'required'),
        array('field' => 'dobmonth', 'label' => 'lang:month', 'rules' => 'required|numeric'),
        array('field' => 'dobyear', 'label' => 'lang:year', 'rules' => 'required|numeric|callback_under_16_check'),
        //array('field' => 'mobile', 'label' => 'lang:mobile', 'rules' => 'required|callback_mobile_taken'),
        // array('field' => 'verification_code', 'label' => 'lang:verification_code', 'rules' => 'required|callback_confirm_code'),
        // array('field' => 'voucher', 'label' => 'lang:voucher', 'rules' => 'callback_voucher'),
        array('field' => 't_c', 'label' => 'lang:label_terms_service', 'rules' => 'required')
    );

    public function insert_member() {

        // get random 10 numeric degit		
        // echo "<pre>";
        // print_r($this->input->post());
        // exit;
        $activation_code = $this->general->random_number();
        $ip_address = $this->general->get_real_ipaddr();
        $referer_id = get_cookie('set_refer_id');

        //generate password
        $salt = $this->general->salt();
        $password = $this->general->hash_password($this->input->post('password', TRUE), $salt);

        //set member info
        $data = array(
            'first_name' => $this->input->post('fname', TRUE),
            'last_name' => $this->input->post('lname', TRUE),
            'dob_day' => $this->input->post('dobday'),
            'dob_month' => $this->input->post('dobmonth'),
            'dob_year' => $this->input->post('dobyear'),
            'email' => $this->input->post('email', TRUE),
			 'country' => $this->input->post('country', TRUE),
           // 'country' => '5',
            //'mobile' => $this->input->post('mobile', TRUE),
//            'address' => $this->input->post('address', TRUE),
//            'address2' => $this->input->post('address2', TRUE),
            'gender' => $this->input->post('gender', true),
            'referer_id' => !empty($referer_id) ? $referer_id : 0,
            'city' => $this->input->post('city', TRUE),
//            'post_code' => $this->input->post('zip', TRUE),
            // 'user_source_id' => $this->input->post('user_source', TRUE),	
            'user_name' => $this->input->post('user_name', TRUE),
            'salt' => $salt,
            'password' => $password,
            'reg_ip_address' => $ip_address,
            'activation_code' => $activation_code,
            // 'lang_id' =>$this->config->item('current_language_id'),
            // 'referer_marketing' =>get_cookie('refererauktis'),
            'reg_date' => $this->general->get_local_time('time'),
            'state' => $this->input->post('state', TRUE)
        );
       
        //if ($this->input->post('country', TRUE) == '5') {
            $data['state'] = $this->input->post('state', TRUE);
        //}

        if ($this->session->userdata('is_fb_user') == "Yes") {
            $data['status'] = 'active';
            $data['is_fb_user'] = 'Yes';
        } else {
            $data['is_fb_user'] = 'No';
        }

        $enable_check = $this->general->check_notification_enable('register_notification_activation');

        if ($enable_check->is_email_notification_send == '0') {
            $data['status'] = 'active';
        }




        //SET Sign bonus		
        if (SIGNUP_BONUS != '0') {
            $data['bonus_points'] = SIGNUP_BONUS;
        }
        if (SIGNUP_CREDIT != '0') {
            $data['balance'] = SIGNUP_CREDIT;
        }
        //print_r($data);exit;
        // if($this->input->post('voucher'))
        // {
        // 	$free_bids_voucher = $this->registration_voucher($this->input->post('voucher'));
        // 	$data['balance'] = isset($free_bids_voucher) ? $free_bids_voucher : '';
        // }
        //Running Transactions
        $this->db->trans_start();
        //insert records in the database
        $this->db->insert('members', $data);

        $this->user_id = $this->db->insert_id();

        if ($this->input->post('push_id') != '' && $this->user_id) {
            $this->load->model('api/api_general', 'api_general');
            $this->api_general->device_manager($this->input->post('push_id'), $this->user_id);
        }

        //Insert Sign bonus transaction
        if (SIGNUP_BONUS != '0')
            $this->insert_signup_bonus_records_transaction($this->user_id);

        // check & added voucher in the transacton table
        // if(isset($free_bids_voucher))
        // {
        // 	$query = $this->db->get_where("vouchers",array("code"=>$this->input->post('voucher')));
        // 	$data=$query->row();			
        // 	$id = $data->id;			
        // 	$free_bids = $data->free_bids;
        // 	//add voucher records in transaction table
        // 	$item_name = 'Registration Voucher';
        // 	$data=array('transaction_status'=>'Completed','user_id'=>$this->user_id,'payment_date'=>$this->general->get_local_time('time'),
        // 				'credit_get'=>$free_bids_voucher,'transaction_name'=>$item_name,'transaction_type'=>'voucher',
        // 				'transaction_date'=>$this->general->get_local_time('time'),'voucher_id'=>$id,'voucher_code'=>$this->input->post('voucher'));
        // 	$this->db->insert('transaction', $data);
        // }
        //Complete Transactions
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "system_error";
        } else {
            return $activation_code;
        }
    }

    public function insert_social_user($social_site, $id) {

		$referer_id = get_cookie('set_refer_id');

        $ip_address = $this->general->get_real_ipaddr();
        //$dateofbirth = ($this->input->post('birthday', TRUE) && $this->input->post('birthday', TRUE) != '') ? $this->input->post('birthday', TRUE) : '';
        $email = ($this->input->post('email', TRUE) && $this->input->post('email', TRUE) != '') ? $this->input->post('email', TRUE) : '';
        $first_name = ($this->input->post('first_name', TRUE) && $this->input->post('first_name', TRUE) != '') ? $this->input->post('first_name', TRUE) : '';
        $last_name = ($this->input->post('last_name', TRUE) && $this->input->post('last_name', TRUE) != '') ? $this->input->post('last_name', TRUE) : '';

        $dob = $this->input->post('birthday', TRUE);
        if ($dob) {
            $time = strtotime($dob);
            $month = date("m", $time);
            $year = date("Y", $time);
            $day = date("d", $time);
        } else {
            $month = ' ';
            $year = ' ';
            $day = ' ';
        }
		
		$email_arr = explode("@",$email);
		
        $this->db->trans_start();
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'user_name' => $email_arr[0],
            'dob_year' => $year,
            'dob_month' => $month,
            'dob_day' => $day,
            'country' => COUNTRY_ID,
            'reg_ip_address' => $ip_address,
            'last_ip_address' => $ip_address,
            'social_id' => $id,
            'reg_date' => $this->general->get_gmt_time('time'),
            'reg_type' => $social_site,
			'referer_id' => !empty($referer_id) ? $referer_id : 0,
            'status' => 'active'
        );
		
		
        $this->db->insert('members', $data);
        $this->user_id = $this->db->insert_id();
		
        /*if ($this->user_id) {
            $this->load->model('api/api_general', 'api_general');
            $this->api_general->device_manager($this->input->post('push_id'), $this->user_id);
        }*/

        if ($this->user_id) {
            //sending mail notification
            $parse_element = array(
                "FIRSTNAME" => $first_name,
                "USERNAME" => $first_name . ' ' . $last_name,
                "EMAIL" => $email,
                "PASSWORD" => '********',
                "WEBSITE_NAME" => WEBSITE_NAME
            );
            // $this->notification->send_email_notification('register_notification_activation', $this->user_id, SYSTEM_EMAIL, $email,'','', $parse_element);
            // $this->notification->send_sms_notification('register_notification_activation', $this->user_id, $parse_element);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "system_error";
        } else {
            $this->invoice_id = $this->insert_signup_bonus_records_transaction($this->user_id); //insert signup bonus
            $this->update_member_balance();
			
			//Check and update referral bonus
			if ($referer_id != '0' && $referer_id != null)
                	$this->set_referrer_bonus($referer_id);
			
            return $this->user_id;
        }
    }

    public function insert_twitter_user() {


        $ip_address = $this->general->get_real_ipaddr();

        $this->db->trans_start();
        $data = array(
            'first_name' => preg_replace("/[^a-zA-Z]/", " ", $this->input->post('twi_user')),
            'last_name' => ' ',
            'email' => $this->input->post('t_email'),
            'user_name' => ' ',
            'dob_year' => $this->input->post('t_dob_year'),
            'dob_month' => $this->input->post('t_dob_month'),
            'dob_day' => $this->input->post('t_dob_day'),
            'country' => COUNTRY_ID,
            'reg_ip_address' => $ip_address,
            'last_ip_address' => $ip_address,
            'social_id' => $this->input->post('twi_id', true),
            'reg_date' => $this->general->get_gmt_time('time'),
            'reg_type' => 'twitter',
            'status' => 'active'
        );

        $this->db->insert('members', $data);
        $this->user_id = $this->db->insert_id();
        if ($this->user_id) {
            $this->load->model('api/api_general', 'api_general');
            $this->api_general->device_manager($this->input->post('push_id'), $this->user_id);
        }

        if ($this->user_id) {
            //sending mail notification
            $parse_element = array(
                "FIRSTNAME" => $this->input->post('twi_user'),
                "USERNAME" => $this->input->post('twi_user'),
                "EMAIL" => $this->input->post('t_email'),
                "PASSWORD" => '********',
                "WEBSITE_NAME" => WEBSITE_NAME
            );
            // $this->notification->send_email_notification('register_notification_activation', $this->user_id, SYSTEM_EMAIL, $email,'','', $parse_element);
            // $this->notification->send_sms_notification('register_notification_activation', $this->user_id, $parse_element);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "system_error";
        } else {
            $this->invoice_id = $this->insert_signup_bonus_records_transaction($this->user_id);
            $this->update_member_balance();
            return $this->user_id;
        }
    }

    public function reg_success_email() {
        //load email library
        $this->load->library('email');

        $this->load->model('email_model');
        if (!isset($template['subject'])) {

            //get subjet & body
            $template = $this->email_model->get_email_template("register_notification", LANG_ID);
            $template = $this->email_model->get_email_template("register_notification", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $emailbody = $template['email_body'];

        //check blank valude before send message
        if (isset($subject) && isset($emailbody)) {

            $parseElement = array(
                "USERNAME" => $this->input->post('user_name'),
                "SIGNUP_BONUS" => SIGNUP_BONUS,
                "SITENAME" => SITE_NAME,
                "EMAIL" => $this->input->post('email'),
                "FIRSTNAME" => $this->input->post('fname'),
                "PASSWORD" => $this->input->post('password'));

            $subject = $this->email_model->parse_email($parseElement, $subject);
            $emailbody = $this->email_model->parse_email($parseElement, $emailbody);

            // echo $emailbody;
            // exit;
            //set the email things
            $this->email->from(SYSTEM_EMAIL);
            $this->email->to($this->input->post('email', TRUE));
            // print_r($this->input->post('email'));exit;
            $this->email->subject($subject);
            $this->email->message($emailbody);
            $this->email->send();
            // echo $this->email->print_debugger();exit;
        }
    }

    public function reg_confirmation_email($activation_code) {
        //load email library
        $this->load->library('email');

        $this->load->model('email_model');
        if (!isset($template['subject'])) {

            //get subjet & body
            $template = $this->email_model->get_email_template("register_notification", LANG_ID);
            $template = $this->email_model->get_email_template("register_notification", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $emailbody = $template['email_body'];

        //check blank valude before send message
        if (isset($subject) && isset($emailbody)) {
            //parse email

            $confirm = "<a href='" . $this->general->lang_uri('/users/register/activation/' . $activation_code . '/' . $this->user_id) . "'>" . $this->general->lang_uri('/users/register/activation/' . $activation_code . '/' . $this->user_id) . "</a>";

            $parseElement = array("USERNAME" => $this->input->post('user_name'),
                "CONFIRM" => $confirm,
                "SIGNUP_BONUS" => SIGNUP_BONUS,
                "SITENAME" => SITE_NAME,
                "EMAIL" => $this->input->post('email'),
                "FIRSTNAME" => $this->input->post('fname'),
                "PASSWORD" => $this->input->post('password'));

            $subject = $this->email_model->parse_email($parseElement, $subject);
            $emailbody = $this->email_model->parse_email($parseElement, $emailbody);

            // echo $emailbody;
            // exit;
            //set the email things
            $this->email->from(SYSTEM_EMAIL);
            $this->email->to($this->input->post('email', TRUE));
            $this->email->subject($subject);
            $this->email->message($emailbody);
            $this->email->send();
            //echo $this->email->print_debugger();exit;
        }
    }

    public function send_sms_notification($activation_code, $user_id) {



        $this->load->library('email');

        $this->load->model('email_model');


        $this->user_id = $user_id;

        $user_info = $this->general->get_user_details_by_user_name($this->input->post('user_name', TRUE));

        if (!isset($template['subject'])) {

            //get subjet & body
            $template = $this->email_model->get_email_template("register_notification", LANG_ID);
            $template = $this->email_model->get_email_template("register_notification", DEFAULT_LANG_ID);
        }



        $subject = $template['subject'];
        $sms_body = $template['sms_body'];
        //check blank valude before send message
        if ($subject && $sms_body) {
            //parse email

            $confirm = "<a href='" . $this->general->lang_uri('/users/register/activation/' . $activation_code . '/' . $this->user_id) . "'>" . $this->general->lang_uri('/users/register/activation/' . $activation_code . '/' . $this->user_id) . "</a>";

            $parseElement = array("USERNAME" => $this->input->post('user_name'),
                "CONFIRM" => $confirm,
                "SIGNUP_BONUS" => SIGNUP_BONUS,
                "SITENAME" => SITE_NAME,
                "EMAIL" => $this->input->post('email'),
                "FIRSTNAME" => $this->input->post('fname'),
                "PASSWORD" => $this->input->post('password'));

            // $subject=$this->email_model->parse_email($parseElement,$subject);
            $smsbody = $this->email_model->parse_email($parseElement, $sms_body);

//            $this->checkmobi->sendSMS(CHECKMOBI_SMS_API_KEY, $user_info->mobile, $smsbody);
            $this->netcoresms_class->sendSMS($user_info->mobile);
        }
    }

    public function send_push_notification($activation_code, $user_id) {



        $this->load->library('email');

        $this->load->model('email_model');


        $this->user_id = $user_id;

        $user_info = $this->general->get_user_details_by_user_name($this->input->post('user_name', TRUE));
        $user_push = $this->general->get_device_id($user_info->push_id);

        if (!isset($template['subject'])) {

            //get subjet & body
            $template = $this->email_model->get_email_template("register_notification", LANG_ID);
            $template = $this->email_model->get_email_template("register_notification", DEFAULT_LANG_ID);
        }



        $subject = $template['subject'];
        $push_body = $template['push_message_body'];
        //check blank valude before send message
        if ($subject && $push_body) {
            //parse email

            $confirm = "<a href='" . $this->general->lang_uri('/users/register/activation/' . $activation_code . '/' . $this->user_id) . "'>" . $this->general->lang_uri('/users/register/activation/' . $activation_code . '/' . $this->user_id) . "</a>";

            $parseElement = array("USERNAME" => $this->input->post('user_name'),
                "CONFIRM" => $confirm,
                "SIGNUP_BONUS" => SIGNUP_BONUS,
                "SITENAME" => SITE_NAME,
                "EMAIL" => $this->input->post('email'),
                "FIRSTNAME" => $this->input->post('fname'),
                "PASSWORD" => $this->input->post('password'));

            // $subject=$this->email_model->parse_email($parseElement,$subject);
            $subject = $this->email_model->parse_email($parseElement, $subject);

            $this->fcm->send($user_push, array('message' => $push_body, 'subject' => $subject));
        }
    }

    // public function activated($activation_code,$user_id)
    // {		
    // 	 $query = $this->db->get_where('members',array('activation_code'=>$activation_code,'id'=>$user_id));
    // 	 if($query->num_rows()>0)
    //         {
    // 	 	$user_data = $query->row_array();
    // 		$user_id=$user_data['id'];
    // 		 	$data=array('status'=>'active');
    //                $this->db->where('id',$user_id);
    //                $this->db->update('members',$data);
    // 			return TRUE;
    // 	 }
    // }

    public function set_referrer_bonus($ref_id) {
        if ($ref_id) {
            $this->db->select('bonus_points');

            $query = $this->db->get_where('members', array('id' => $ref_id));

            $user_balance = $query->row();

            $user_total_balance = $user_balance->bonus_points + REFER_BONUS;

            $mem_data = array('bonus_points' => $user_total_balance);

            if ($query->num_rows() > 0) {
                $this->db->where('id', $ref_id);

                $this->db->update('members', $mem_data);

                $this->invoice_id = $this->insert_refer_bonus_records_transaction($ref_id);
                return $this->invoice_id;
            }
        }
        return faslse;
    }

    public function activated($activation_code, $user_id) {

        $this->load->library('email');

        $this->load->model('email_model');

        $query = $this->db->get_where('members', array('activation_code' => $activation_code, 'id' => $user_id));

        //echo $this->db->last_query(); exit; 

        if ($query->num_rows() > 0) {
            $user_data = $query->row_array();

            $user_id = $user_data['id'];
            $referer_id = $user_data['referer_id'];

            $data = array('status' => 'active', 'activation_code' => '');
            $this->db->where('id', $user_id);

            $this->db->update('members', $data);


            if ($referer_id) {
                $this->db->select('bonus_points');

                $query = $this->db->get_where('members', array('id' => $referer_id));

                $user_balance = $query->row();

                $user_total_balance = $user_balance->bonus_points + REFER_BONUS;

                $mem_data = array('bonus_points' => $user_total_balance);

                if ($query->num_rows() > 0) {
                    $this->db->where('id', $referer_id);

                    $this->db->update('members', $mem_data);

                    $this->invoice_id = $this->insert_refer_bonus_records_transaction($referer_id);
                }
            }

            $enable_check = $this->general->check_notification_enable('register_notification_activation');
            if ($enable_check->is_email_notification_send == '1') {
                //get subjet & body
                $template = $this->email_model->get_email_template("register_notification_activation", LANG_ID);
                if (!isset($template['subject'])) {
                    $template = $this->email_model->get_email_template("register_notification_activation", DEFAULT_LANG_ID);
                }

                $subject = $template['subject'];
                $emailbody = $template['email_body'];

                //check blank valude before send message
                if (isset($subject) && isset($emailbody)) {

                    $parse_element = array(
                        "USERNAME" => $user_data['user_name'],
                        "FIRSTNAME" => $user_data['first_name'],
                        "EMAIL" => $user_data['email'],
                        "PASSWORD" => '******',
                        "SITE_NAME" => SITE_NAME
                    );

                    $subject = $this->email_model->parse_email($parse_element, $subject);
                    $emailbody = $this->email_model->parse_email($parse_element, $emailbody);

                    // echo $emailbody;
                    // exit;
                    //set the email things
                    $this->email->from(SYSTEM_EMAIL);
                    $this->email->to($user_data['email']);
                    $this->email->subject($subject);
                    $this->email->message($emailbody);
                    $this->email->send();
                    //echo $this->email->print_debugger();exit;
                }



                return true;
            }

            if ($enable_check->is_sms_notification_send == '1') {

                $this->activation_sms($user_data);
            }
        }
    }

    public function activation_sms($user_data) {


        $this->load->library('email');

        $this->load->model('email_model');

        $template = $this->email_model->get_email_template("register_notification_activation", LANG_ID);
        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("register_notification_activation", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $smsbody = $template['sms_body'];

        //check blank valude before send message
        if (isset($subject) && isset($smsbody)) {

            $parse_element = array(
                "USERNAME" => $user_data['user_name'],
                "FIRSTNAME" => $user_data['first_name'],
                "EMAIL" => $user_data['email'],
                "PASSWORD" => '******',
                "SITE_NAME" => SITE_NAME
            );

            $subject = $this->email_model->parse_email($parse_element, $subject);
            $smsbody = $this->email_model->parse_email($parse_element, $smsbody);

            $this->checkmobi->sendSMS(CHECKMOBI_SMS_API_KEY, $user_data->mobile, $smsbody);
        }
    }

    public function insert_refer_bonus_records_transaction($referrer_id) {
        $data = array(
            'user_id' => $referrer_id,
            'bonus_points' => REFER_BONUS,
            'credit_debit' => 'CREDIT',
            'transaction_name' => 'Referral Bonus: ' . REFER_BONUS,
            'transaction_date' => $this->general->get_local_time('time'),
            'transaction_type' => 'refer_bonus',
            'transaction_status' => 'Completed',
            'payment_method' => 'direct'
        );
        $this->db->insert('transaction', $data);
        return $this->db->insert_id();
    }

    public function insert_signup_bonus_records_transaction($user_id) {

        $data = array(
            'user_id' => $user_id,
            'credit_debit' => 'CREDIT',
            'bonus_points' => SIGNUP_BONUS,
            'transaction_name' => lang('sign_up_bonus').' ' . SIGNUP_BONUS . ' '.lang('header_points'),
            'transaction_date' => $this->general->get_local_time('time'),
            'transaction_type' => 'sign_up_bonus',
            'transaction_status' => 'Completed',
            'payment_method' => 'direct'
        );
        $this->db->insert('transaction', $data);
        return $this->db->insert_id();
    }

    function update_member_balance() {
        $data = array(
            'bonus_points' => SIGNUP_BONUS,
            'balance' => SIGNUP_CREDIT
        );

        $this->db->where('id', $this->user_id);
        $this->db->update('members', $data);
    }

    public function get_user_source_from() {
        //get language id from configure file
        $lang_id = LANG_ID;

        $data = array();
        //$this->db->order_by("user_source_from", "asc"); 
        $query = $this->db->get_where("user_source_from", array("lang_id" => $lang_id));
        if ($query->num_rows() > 0) {
            $data = $query->result();
        } else {
            $query = $this->db->get_where("user_source_from", array("lang_id" => DEFAULT_LANG_ID));
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }

        $query->free_result();
        return $data;
    }

    public function get_cms($parent_id) {
        //get language id from configure file
        $lang_id = LANG_ID;

        $data = array();
        $query = $this->db->get_where("cms", array("parent_id" => $parent_id, "lang_id" => $lang_id));
        if ($query->num_rows() > 0) {
            $data = $query->row();
        } else {
            $query = $this->db->get_where("cms", array("parent_id" => $parent_id, "lang_id" => DEFAULT_LANG_ID));
            if ($query->num_rows() > 0) {
                $data = $query->row();
            }
        }
        $query->free_result();
        return $data;
    }

    public function check_voucher($val) {
        $query = $this->db->get_where("vouchers", array("code" => $val));
        return $query->num_rows();
    }

    public function registration_voucher($val) {
        $query = $this->db->get_where("vouchers", array("code" => $val));

        if ($query->num_rows() > 0) {
            $data = $query->row();

            $id = $data->id;
            $limit_number = $data->limit_number;
            $limit_per_user = $data->limit_per_user;
            $free_bids = $data->free_bids;
            $start_date = $data->start_date;
            $end_date = $data->end_date;
            $current_date = $this->general->get_local_time('none');

            //Check date range
            if ($start_date <= $current_date && $end_date >= $current_date && $free_bids != 0) {
                //get total voucher used
                $query_txn_voucher = $this->db->get_where("transaction", array("voucher_id" => $id, "transaction_status" => "Completed", 'transaction_type' => 'voucher'));

                if ($query_txn_voucher->num_rows() < $limit_number || $limit_number == 0) {
                    return $free_bids;
                }
            }
        }
    }
	
	
}
