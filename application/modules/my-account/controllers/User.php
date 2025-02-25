<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();

        //load custom library
        $this->load->library('my_language');

        if (SITE_STATUS == 'offline') {
            redirect($this->general->lang_uri('/offline'));
            exit;
        }

        if (SITE_STATUS == 'maintanance') {
            if (!$this->session->userdata('MAINTAINANCE_KEY') OR $this->session->userdata('MAINTAINANCE_KEY') != 'YES') {
                redirect($this->general->lang_uri('/maintanance'));
                exit;
            }
        }
		//print_r($this->session->all_userdata());exit;//1650522763 
		
        if (!$this->session->userdata(SESSION . 'user_id')) {
            redirect($this->general->lang_uri('/users/login'), 'refresh');
            exit;
        }

        //check banned IP address
        $this->general->check_banned_ip();

        //load CI library

        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('image_lib');

        //Changing the Error Delimiters
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		$this->load->helper('text');
        $this->load->model('account_module');
        $this->load->library('pagination');
        $this->load->library('Checkmobi');
        $this->load->library('Checkmobi');
        $this->load->library('fcm');
        $this->load->model('ccavenue_model');

        $this->ccav_working_key = '9BD6991DD4BC72CCB393896091138C0D';
		
		$this->user_id = $this->session->userdata(SESSION . 'user_id');
    }

    public function index() {
        $this->data['account_menu_active'] = 'dashboard';
		$this->data['account_page_name'] = lang('account_dashboard');
        $user_id = $this->session->userdata(SESSION . 'user_id');
		$this->data['total_won_auc'] = $this->account_module->total_my_won_auctions($user_id);
		$this->data['total_live_auc'] = $this->account_module->get_total_live_bid_auctions($user_id);
		$this->data['user_watchlist'] = $this->general->get_user_watchlist($user_id);
		
		
        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title(lang('account_dashboard').' | ' . SITE_NAME)
                ->build('dash_board', $this->data);
    }

    public function edit() {
		//$this->account_module->send_opt_code("456321","9818259993");
		//exit;
        $this->data['account_menu_active'] = 'profile';
		$this->data['account_page_name'] = lang('account_personal_profile');
        $this->data['countries'] = $this->general->get_country();
        $this->data['profile'] = $this->account_module->get_user_details();

        if ($this->session->userdata(SESSION . 'user_id')) {
            $user_id = $this->session->userdata(SESSION . 'user_id');
        } else {
            $user_id = $this->user_id;
        }

        $this->form_validation->set_rules($this->account_module->update_validate_settings);
        
		if($this->input->post('dob_status')=='0'){
			$this->form_validation->set_rules($this->account_module->validate_user_dob);
		}
		
		
        if ($this->form_validation->run() == TRUE) {

            $this->account_module->update_member($user_id);

            if ($this->data['profile']->email != $this->input->post('email', TRUE)) {
                $enable_check = $this->general->check_notification_enable('profile_msg_verification_email_sent');
                //update new email & send confirmation email
                if ($enable_check_no_more->is_email_notification_send == '1') {


                    $this->account_module->update_new_email_confirmation_email($this->data['profile']);
                }
                if ($enable_check_no_more->is_sms_notification_send == '1') {

                    $this->account_module->update_new_email_sms_notification($this->data['profile']);
                }


                $this->session->set_flashdata('success_message', lang('profile_msg_verification_email_sent'));
            } else
                $this->session->set_flashdata('success_message', lang('profile_msg_update_success'));

            redirect($this->general->lang_uri('/my-account/user/edit'));
            exit;
        }
		//echo $this->session->userdata(SESSION.'verification_code');exit;
        $this->page_title = lang('my_profile') . ' | ' . SITE_NAME;
        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title($this->page_title)
                ->build('profile', $this->data);
    }
	
	public function mobile() {
		
        $this->data['account_menu_active'] = 'moible';
		$this->data['account_page_name'] = lang('account_personal_mobile');
        
        $this->data['profile'] = $this->account_module->get_user_details();

        if ($this->session->userdata(SESSION . 'user_id')) {
            $user_id = $this->session->userdata(SESSION . 'user_id');
        } else {
            $user_id = $this->user_id;
        }
		
		if($this->input->server('REQUEST_METHOD') === 'POST' && $this->input->post('mobile')==$this->input->post('mobile_old')){
			$this->session->set_flashdata('error_message', lang('label_no_changes_msg'));
            redirect($this->general->lang_uri('/my-account/user/mobile'));
            exit;
		}
		
        $this->form_validation->set_rules($this->account_module->validate_user_mobile);
        //enable mobile verification validation if user changed mobile
        //$verify_mobile = $this->session->userdata(SESSION . 'verify_mobile');		
		//echo $this->input->cookie(SESSION.'verification_code');exit;
        if ($this->input->cookie(SESSION.'verification_code'))
            $this->form_validation->set_rules($this->account_module->update_validate_verification_code);
		
		
		
        if ($this->form_validation->run() == TRUE) {

            $this->account_module->update_mobile($user_id);
			$this->session->set_flashdata('success_message', lang('label_mobile_update_msg'));
			
			if($this->general->check_profile_blank_field() >=1)
			{
				$this->session->set_flashdata('error_message', lang('profile_complete_personal_details'));
				redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/edit'),'refresh');
				exit;
			}
		
            redirect($this->general->lang_uri('/my-account/user/mobile'));
            exit;
        }
		
        $this->page_title = lang('account_personal_mobile') . ' | ' . SITE_NAME;
        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title($this->page_title)
                ->build('user_mobile', $this->data);
    }
	
	function send_verification_code(){
			$user_mobile = $this->input->cookie(SESSION.'mobile_number');
			//generate otp code
			$opt_code = mt_rand(100000, 999999);
			$this->session->set_userdata(SESSION . 'verification_code', $opt_code);
			//save otp in cookie for 2 hours
			$cookie_otp = array('name' => SESSION . "verification_code", 'value' => $opt_code, 'expire' => 3600 * 2);
            $this->input->set_cookie($cookie_otp);
			
			$cookie_mobile = array('name' => SESSION . "mobile_number", 'value' => $user_mobile, 'expire' => 3600 * 2);
            $this->input->set_cookie($cookie_mobile);
			
			$old_otp_count = $this->input->cookie(SESSION.'otp_count');
			if($old_otp_count>1){
				
				$this->session->set_flashdata(array('error_message' => 'You have try more than our limit. to verify your account and start biding please contact site admin or wait 2 hours to try again.'));
				redirect($this->general->lang_uri('/my-account/user/mobile'));
            	exit;
			}
			$cookie_otp_count = array('name' => SESSION . "otp_count", 'value' => $old_otp_count+1, 'expire' => 3600 * 2);
            $this->input->set_cookie($cookie_otp_count);
			
			//send otp in mobile
			$this->account_module->send_opt_code($opt_code,$user_mobile);
			$this->session->set_flashdata(array('error_message' => 'A OTP has been sent to your registered mobile number. Please enter the OTP below to verify your mobile number. The OTP code will expire in 2 hours.'));
			redirect($this->general->lang_uri('/my-account/user/mobile'));
            exit;
	}

    function check_old_password() {
        if ($this->account_module->check_password()) {
            return true;
        } else {
            $this->form_validation->set_message('check_old_password', lang('old_passwordd_not_match'));
            return false;
        }
    }

    public function myaddress() {
        $this->data['account_menu_active'] = 'myaddress';
        $this->data['countries'] = $this->general->get_country();
        $this->data['profile'] = $this->account_module->get_user_shipping_details();

        // Set the validation rules
        $this->form_validation->set_rules($this->account_module->validate_settings_myaddress);

        if ($this->form_validation->run() == TRUE) {
            $this->account_module->update_user_shipping_address();
            $this->session->set_flashdata('message', lang('profile_msg_update_ship_addr'));
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/myaddress'), 'refresh');
            exit;
        }

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title('My Account | ' . SITE_NAME)
                ->build('address_shipping', $this->data);
    }

    public function changepassword() {
		
		if($this->session->userdata(SESSION.'social_id')!=""){
			redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/index'), 'refresh');
                exit;
		}
        $this->data['account_menu_active'] = 'password';
		$this->data['account_page_name'] = lang('account_change_pass');
        // Set the validation rules
        $this->form_validation->set_rules($this->account_module->validate_password);

        if ($this->form_validation->run() == TRUE) {
            //check current password with previous password
            if ($this->account_module->check_old_password() == TRUE) {
                //change new password
                $activation_code = $this->account_module->change_password();

                //unset all session variable
                $this->session->unset_userdata(SESSION . 'user_id');
                $this->session->unset_userdata(SESSION . 'first_name');
                $this->session->unset_userdata(SESSION . 'email');
                $this->session->unset_userdata(SESSION . 'last_name');
                $this->session->unset_userdata(SESSION . 'username');
                $this->session->unset_userdata(SESSION . 'balance');
                $this->session->unset_userdata(SESSION . 'last_login');
                $this->session->unset_userdata(SESSION . 'lang_flag');
                $this->session->unset_userdata(SESSION . 'short_code');
                $this->session->unset_userdata(SESSION . 'lang_id');

                $this->session->set_flashdata('message', lang('change_pass_changed'));
                redirect($this->general->lang_uri('/users/login'), 'refresh');
                exit;
            } else {
                $this->session->set_flashdata('message', lang('change_pass_invalid'));
                redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/changepassword'), 'refresh');
                exit;
            }
        }
		
		$this->page_title = lang('account_change_pass') . ' | ' . SITE_NAME;
        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';
		
        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title(lang('account_change_pass').' | ' . SITE_NAME)
                ->build('change_password', $this->data);
    }

    public function refer() {
        $this->data['account_menu_active'] = 'refer';
		$this->data['account_page_name'] = lang('refer_a_friend');
        $this->form_validation->set_rules('name[1]', 'lang:name', 'trim|required');

        $this->form_validation->set_rules('email[1]', 'lang:email', 'trim|required|valid_email|is_unique[members.email]');
        $this->form_validation->set_message('is_unique', 'Account already exists.');

        $name = $this->input->post('name');
        $email = $this->input->post('email');


        if (isset($name[2]) || isset($email[2])) {
            $this->form_validation->set_rules('name[2]', 'lang:name', 'trim|required');
            $this->form_validation->set_rules('email[2]', 'lang:email', 'trim|required|valid_email|is_unique[members.email]');
        }
        if (isset($name[3]) || isset($email[3])) {
            $this->form_validation->set_rules('name[3]', 'lang:name', 'trim|required');
            $this->form_validation->set_rules('email[3]', 'lang:email', 'trim|required|valid_email|is_unique[members.email]');
        }
        if (isset($name[4]) || isset($email[4])) {
            $this->form_validation->set_rules('name[4]', 'lang:name', 'trim|required');
            $this->form_validation->set_rules('email[4]', 'lang:email', 'trim|required|valid_email|is_unique[members.email]');
        }

        if ($this->form_validation->run() == TRUE) {
            $this->account_module->send_mail_refer_friend();

            $this->session->set_flashdata('message', lang('refer_friends_response'));
            // //'Thank you very much for referring your friend(s) to '.SITE_NAME);
            redirect($this->general->lang_uri('/my-account/user/refer'), 'refresh');
            // exit;
        }


        $this->page_title = lang('refer_friends');
        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';
        $this->data['account_menu'] = 'refer_friends';
        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title($this->page_title)
                ->build('v_refer', $this->data);
    }

    public function wonauctions() {
        //Check profile empty befor start bidding.
        if($this->user_id && $this->general->check_mobile_blank_field() >=1){
			$this->session->set_flashdata('error_message', lang('label_req_mobile_verification_msg'));
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/mobile'),'refresh');
			exit;
		}
		else if ($this->session->userdata(SESSION . 'user_id') && $this->general->check_profile_blank_field() >= 1) {
            $this->session->set_flashdata('message', lang('profile_complete_personal_details'));
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/edit'), 'refresh');
            exit;
        }

        $user_id = $this->session->userdata(SESSION . 'user_id');
        $this->data['account_menu_active'] = 'wonauctions';
		$this->data['account_page_name'] = lang("account_winning_bid");

        $config['base_url'] = $this->general->lang_uri('/' . MY_ACCOUNT . '/user/purchases');
        $config['total_rows'] = $this->account_module->total_my_won_auctions($user_id);
        $config['per_page'] = 15;
        $config['page_query_string'] = FALSE;
        $config["uri_segment"] = 5;

        $this->general->frontend_pagination_config($config);
        $this->pagination->initialize($config);
        $this->data['offset'] = $this->uri->segment(5, 0);

        $this->data['won_auc'] = $this->account_module->get_my_won_auctions($user_id, $config["per_page"], $this->data['offset']);
        $this->data["pagination_links"] = $this->pagination->create_links();


        $this->page_title = SITE_NAME . " | " . lang('won_auctions');
        $this->data['meta_keys'] = SITE_NAME . " | " . lang('won_auctions');
        $this->data['meta_desc'] = SITE_NAME . " | " . lang('won_auctions');

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title(lang("account_winning_bid").' | ' . SITE_NAME)
                ->build('won_auctions', $this->data);
    }

    public function wonauctionsconfirm($product_id = '') {
        //$product_id = $this->uri->segment(4);
        //check integer value
        if ($this->general->check_int_vlaue($product_id) == false) {
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/wonauctions'), 'refresh');
            exit;
        }

        $this->data['account_menu_active'] = 'wonauctions';
		$this->data['account_page_name'] = lang('account_winning_bid');
        $this->data['payment_lists'] = $this->account_module->get_all_payment_gateway();
        $this->data['countries'] = $this->general->get_active_countries();
        //$this->data['shipping_info'] = $this->account_module->get_user_shipping_details();
        $this->data['profile'] = $this->account_module->get_user_details();

        $this->data['auc_lists'] = $this->account_module->get_my_won_auctions_byid($product_id);

        //check winnder data
        if (!isset($this->data['auc_lists']->won_amt) || $this->data['auc_lists']->won_amt <= 0) {
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/wonauctions'), 'refresh');
            exit;
        }

        //set validation rules

        $validate_settings = array(
            array('field' => 'payment_type', 'label' => 'lang:account_payment_method', 'rules' => 'trim|required'),
            array('field' => 'email', 'label' => 'lang:register_email', 'rules' => 'trim|required|valid_email'),
            array('field' => 'name', 'label' => 'lang:profile_name', 'rules' => 'trim|required'),
            array('field' => 'country', 'label' => 'lang:register_country', 'rules' => 'required'),
            array('field' => 'address', 'label' => 'lang:profile_address', 'rules' => 'trim|required'),
			 array('field' => 'phone', 'label' => 'lang:label_phone', 'rules' => 'trim|required'),
            array('field' => 'city', 'label' => 'lang:profile_city', 'rules' => 'trim|required'),
            array('field' => 'post_code', 'label' => 'lang:profile_post_code', 'rules' => 'trim|required'),
            array('field' => 'ship_name', 'label' => 'lang:profile_name', 'rules' => 'trim|required'),
			array('field' => 'ship_email', 'label' => 'lang:register_email', 'rules' => 'trim|required|valid_email'),
            array('field' => 'ship_country', 'label' => 'lang:register_country', 'rules' => 'required'),
            array('field' => 'ship_address', 'label' => 'lang:profile_address', 'rules' => 'trim|required'),
            array('field' => 'ship_city', 'label' => 'lang:profile_city', 'rules' => 'trim|required'),
            array('field' => 'ship_post_code', 'label' => 'lang:profile_post_code', 'rules' => 'trim|required'),
            array('field' => 'ship_phone', 'label' => 'lang:label_phone', 'rules' => 'trim|required')
        );
        $this->form_validation->set_rules($validate_settings);

        if ($this->form_validation->run() == TRUE) {
            $this->payment_method_id = $this->input->post('payment_type', TRUE);

            switch ($this->payment_method_id) {
                case '1':
                    $this->paypal();
                    break;
                case '3':

                    $this->ccavenue();
                    break;
                
                case '4':
                    $this->paytm();
                    break;
                
                default:
                    $this->session->set_flashdata('message', lang('won_pay_auc_if_uhv'));
                    redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/wonauctions'), 'refresh');
                    exit;
                    break;
            }
        } else {

            $this->page_title = lang('won_auction_confim') . ' | ' . SITE_NAME;
            $this->data['meta_keys'] = '';
            $this->data['meta_desc'] = '';


            $this->template
                    ->set_layout('account')
                    ->enable_parser(FALSE)
                    ->title($this->page_title)
                    ->build('won_auctions_payment', $this->data);
        }
    }

    public function ongoing_auction() {
        if (!$this->session->userdata(SESSION . 'user_id')) {
            redirect($this->general->lang_uri(''), 'refresh');
            exit;
        }

        $user_id = $this->session->userdata(SESSION . 'user_id');

        $this->data['account_menu_active'] = 'ongoing_auction';
		$this->data['account_page_name'] = lang('ongoing_auctions');
		
		//get user watchlist
		$this->data['user_watchlist'] = $this->general->get_user_watchlist($user_id);
		
        $this->data['ongoing_auc'] = $this->account_module->get_my_live_bid_auctions($user_id);
        // echo "<pre>";
        // print_r($this->data['ongoing_auc']);
        // exit;
        $this->page_title = lang('my_ongoing_auction');
        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title($this->page_title)
                ->build('ongoing_auction', $this->data);
    }
	
	
	public function bid_statement() {
        if (!$this->session->userdata(SESSION . 'user_id')) {
            redirect($this->general->lang_uri(''), 'refresh');
            exit;
        }
		$date_from = $this->input->get("date_from");
		$date_to = $this->input->get("date_to");
		if($date_from=="" || $date_to==""){
			redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/ongoing_auction'), 'refresh');
            exit;
		}
		
        $user_id = $this->session->userdata(SESSION . 'user_id');
		
		$config['base_url'] = $this->general->lang_uri('/' . MY_ACCOUNT . '/user/bid_statement');
        $config['total_rows'] = $this->account_module->total_bid_statement($user_id, $date_from, $date_to);
        $config['per_page'] = 20;       
       	$config["uri_segment"] = 5;
		$config['enable_query_strings'] = FALSE;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;

        $this->general->frontend_pagination_config($config);
        $this->pagination->initialize($config);
        $this->data['offset'] = $this->input->get("per_page");
        		
		$this->data['statement'] = $this->account_module->get_bid_statement($user_id, $date_from, $date_to, $config["per_page"], $this->data['offset']);
		
		//$this->data["pagination_links"] = $this->pagination->create_links();
		
        $this->data['account_menu_active'] = 'ongoing_auction';
		$this->data['account_page_name'] = lang('label_my_bids_statement');
		
		
        $this->page_title = lang('label_my_bids_statement');
        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title($this->page_title)
                ->build('bid_statement', $this->data);
    }

    public function watchlist() {
        $this->data['page_title'] = $this->lang->line('my_watch_list');
        $this->data['account_menu_active'] = 'watchlist';
		$this->data['account_page_name'] = lang('favorite_auctions');
		$user_id = $this->session->userdata(SESSION . 'user_id');
		//get user watchlist
		$this->data['user_watchlist'] = $this->general->get_user_watchlist($user_id);
		
        $this->data['watchlist'] = $this->account_module->get_watch_list();
        // echo "<pre>";
        // print_r($this->data['watchlist']);
        // exit;

        $this->page_title = lang('favorite_auctions') . ' | ' . lang('watch_list');
        $this->data['meta_keys'] = SITE_NAME . ' | ' . lang('watch_list');
        $this->data['meta_desc'] = SITE_NAME . ' | ' . lang('watch_list');


        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title($this->page_title)
                ->build('watchlist', $this->data);
    }

    public function purchases() {
        //Check profile empty befor start bidding.
        if($this->user_id && $this->general->check_mobile_blank_field() >=1){
			$this->session->set_flashdata('error_message', lang('label_req_mobile_verification_msg'));
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/mobile'),'refresh');
			exit;
		}
		else if ($this->session->userdata(SESSION . 'user_id') && $this->general->check_profile_blank_field() >= 1) {
            $this->session->set_flashdata('error_message', lang('profile_complete_personal_details'));
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/edit'), 'refresh');
            exit;
        }

        $this->data['account_menu_active'] = 'purchase';
		$this->data['account_page_name'] = $this->lang->line('account_purchase_history');
        $config['base_url'] = $this->general->lang_uri('/' . MY_ACCOUNT . '/user/purchases');
        $config['total_rows'] = $this->account_module->total_my_transaction();
        $config['per_page'] = 15;
        $config['page_query_string'] = FALSE;
        $config["uri_segment"] = 5;

        $this->general->frontend_pagination_config($config);
        $this->pagination->initialize($config);
        $this->data['offset'] = $this->uri->segment(5, 0);

        $this->data['get_trans'] = $this->account_module->get_my_transaction($config["per_page"], $this->data['offset']);
        $this->data["pagination_links"] = $this->pagination->create_links();

        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title(lang('account_purchase_history').' | ' . SITE_NAME)
                ->build('purchases', $this->data);
    }

    public function trasaction_completed($message="", $trans = false, $amt = false) {
        if ($message === "Success") {
            $this->data['final_message'] = "<div class='alert alert-success'>" . lang('payment_success_message') . "</div>";


            $this->data['marketing_script'] = "<script type='text/javascript'>dataLayer =[{'user_id': '".$this->session->userdata(SESSION.'user_id')."','user_email': '".$this->session->userdata(SESSION.'email')."','order_id': '".$trans."', 'event': 'payment','amount': '".$amt."'}];</script>";
            /*$this->data['marketing_script']="<script src='https://www.s2d6.com/js/globalpixel.js?x=sp&a=2021&h=70888&o=payment&userID=".$this->session->userdata(SESSION.'user_id')."&transactionID=".$trans."&amount=".$amt."&g=payment&s=0.00&q=1'></script>";*/
        } else if ($message === "Aborted") {
            $this->data['final_message'] = "<div class='alert alert-info'>" . lang('payment_aborted_message') . "</div>";
        } else if ($message === "Failure") {
            $this->data['final_message'] = "<div class='alert alert-danger'>" . lang('payment_failure_message') . "</div>";
        } else {
            $this->data['final_message'] = "<div class='alert alert-danger'>" . lang('payment_illigal_message') . "</div>";
        }
        $this->data['account_menu_active'] = 'buybids';
		$this->data['account_page_name'] = "Payment Success";
        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title('Purchase Bid Credits | ' . SITE_NAME)
                ->build('ccavenue_message', $this->data);
    }

    public function buybids() {
        //Check profile empty befor start bidding.
        if($this->user_id && $this->general->check_mobile_blank_field() >=1){
			$this->session->set_flashdata('error_message', lang('label_req_mobile_verification_msg'));
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/mobile'),'refresh');
			exit;
		}
		else if ($this->session->userdata(SESSION . 'user_id') && $this->general->check_profile_blank_field() >= 1) {
            $this->session->set_flashdata('message', lang('profile_complete_personal_details'));
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/edit'), 'refresh');
            exit;
        }
        
        $this->data['account_menu_active'] = 'buybids';
		$this->data['account_page_name'] = lang('purchase_bid_credits');
        $this->data['bid_packages'] = $this->account_module->get_all_bid_package();
        $this->data['payment_lists'] = $this->account_module->get_all_payment_gateway();

        // Set the validation rules
        $this->form_validation->set_rules('package', 'lang:account_buy_credits', 'required');
        $this->form_validation->set_rules('payment_type', 'lang:account_payment_method', 'required');

        if ($this->form_validation->run() == TRUE) {

            $this->payment_method_id = $this->input->post('payment_type', TRUE);

            switch ($this->payment_method_id) {
                case '1':
                    $this->paypal();
                    break;
                
                case '3':
                    $this->ccavenue();
                    break;
                case '4':
                    $this->paytm();
                    break;
                
                default:
                    $this->session->set_flashdata('message', lang('account_error_choose_credit_payment_method'));
                    redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/buybids'), 'refresh');
                    exit;
                    break;
            }
        } else {
			
			$this->data['get_trans'] = $this->account_module->get_my_bid_credit(10, 0);
            $this->data['meta_keys'] = '';
            $this->data['meta_desc'] = '';
            $this->template
                    ->set_layout('account')
                    ->enable_parser(FALSE)
                    ->title(lang('purchase_bid_credits').' | ' . SITE_NAME)
                    ->build('buy_bids', $this->data);
        }
    }

    public function bonuspackage() {
        //Check profile empty befor start bidding.
        if($this->user_id && $this->general->check_mobile_blank_field() >=1){
			$this->session->set_flashdata('error_message', lang('label_req_mobile_verification_msg'));
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/mobile'),'refresh');
			exit;
		}
		else if ($this->session->userdata(SESSION . 'user_id') && $this->general->check_profile_blank_field() >= 1) {
            $this->session->set_flashdata('error_message', lang('profile_complete_personal_details'));
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/edit'), 'refresh');
            exit;
        }

        $this->data['account_menu_active'] = 'bonuspackage';
		$this->data['account_page_name'] = lang('redem_bonus_points');
		
        $this->data['bonus_packages'] = $this->account_module->get_all_bonus_package();

        // Set the validation rules
        $this->form_validation->set_rules('package', 'lang:account_bonus_package_lists', 'required');

        if ($this->form_validation->run() == TRUE) {
            //get user bonus points
            $user_bonus = $this->general->get_user_bonus($this->session->userdata(SESSION . 'user_id'));

            //Get bonus package by id
            $bonus_package_data = $this->account_module->get_bonus_package_byid($this->input->post('package'));
            //check user bonus point with selsected bonus package
            if ($user_bonus >= $bonus_package_data->bonus_points) {
                //update user balance with purchase package
                $this->account_module->update_user_balance_with_purchase_bonus_package($this->input->post('package'), $bonus_package_data->credits, $bonus_package_data->bonus_points);
                $this->session->set_flashdata('success_message', lang('account_msg_complete_purchase'));
                redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/purchases'), 'refresh');
            } else {
                $this->session->set_flashdata('error_message', lang('account_bonus_package_udnt_hv_points'));
                redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/bonuspackage'), 'refresh');
            }
        }

        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title(lang('redem_bonus_points').' | ' . SITE_NAME)
                ->build('bonus_package_lists', $this->data);
    }

    public function cancel() {
        $this->data['account_menu_active'] = 'cancel';
		$this->data['account_page_name'] = lang('delete_account');
		
        $this->form_validation->set_rules('terms', 'Terms and Conditions', 'required');
        if ($this->form_validation->run() == TRUE) {
            //Check if the user haveing live bids or bids-agents
            if ($this->account_module->check_user_live_auction_bids() == true) {
                $this->session->set_flashdata('error_message', lang('acc_not_cancel_msg'));
                redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/cancel'), 'refresh');
                exit;
            } else {

                $this->account_module->cancel_user_account();								
                $this->session->set_flashdata('success_message', lang('acc_cancel_msg'));
                redirect($this->general->lang_uri('/users/logout?job=cancel_account'), 'refresh');
                exit;
            }
        }

        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title(lang('delete_account').' | ' . SITE_NAME)
                ->build('cancel', $this->data);
    }

    public function paypal() {
			
        //get payment method info
        $this->payment_data = $this->account_module->get_payment_gateway_byid($this->payment_method_id);

        if ($this->payment_data) {
            //paypal settings			
            $this->load->model('paypal_module'); //load paypal module
            $this->data['payment_title'] = lang('account_processing_payment'); //from language file
            $this->data['body'] = $this->paypal_module->set_paypal_form_submit();
            // print_r("coming after form submit");exit;
            //print_r($this->data['body']);exit;

            $this->data['meta_keys'] = '';
            $this->data['meta_desc'] = '';

            $this->template
                    ->set_layout('account')
                    ->enable_parser(FALSE)
                    ->title('My Account | ' . lang('account_processing_payment'))
                    ->build('payment_process', $this->data);
        } else {
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/index'), 'refresh');
            exit;
        }
    }

   

    public function ccavenue() {
        //get payment method info
        $this->payment_data = $this->account_module->get_payment_gateway_byid($this->payment_method_id);
		//print_r($this->payment_data);exit;
        $this->data['ccavenue_pay_mode'] = $this->payment_data->status;
        if ($this->payment_data) {
            // settings	
			$this->data['merchant_id'] = $this->payment_data->merchant_id;
			$this->data['access_code'] = 		$this->payment_data->access_code;
			
			if($this->payment_data->status=="1")
			$this->data['payment_url'] = "https://test.ccavenue.com/transaction/transaction.do?command=getJsonData&access_code=";
			else
			$this->data['payment_url'] = "https://secure.ccavenue.com/transaction/transaction.do?command=getJsonData&access_code=";
						
            //load paypal module
            $this->data['payment_title'] = lang('account_processing_payment'); //from language file
            //$this->data['personal_details'] = $this->account_module->get_user_details();
			
			$this->data['body'] = $this->ccavenue_model->set_ccavenue_form_submit();
            
            $this->data['meta_keys'] = '';
            $this->data['meta_desc'] = '';
            $this->template
                    ->set_layout('account')
                    ->enable_parser(FALSE)
                    ->title('My Account | ' . lang('account_processing_payment'))
                    //->build('ccavenue_payment_process', $this->data);
					->build('payment_process', $this->data);
        } else {
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/index'), 'refresh');
            exit;
        }
    }

    public function paytm_ipn() {
        $this->payment_data = $this->account_module->get_payment_gateway_byid('4');
        if ($this->payment_data->status == 1)
            define('PAYTM_ENVIRONMENT', 'TEST');
        else
            define('PAYTM_ENVIRONMENT', 'PROD');

        $PAYTM_DOMAIN = "securegw-stage.paytm.in";
        if (PAYTM_ENVIRONMENT == 'PROD') {
            $PAYTM_DOMAIN = 'securegw.paytm.in';
        }
		
		define('PAYTM_REFUND_URL', 'https://' . $PAYTM_DOMAIN . '/oltp/HANDLER_INTERNAL/REFUND');
        define('PAYTM_STATUS_QUERY_URL', 'https://' . $PAYTM_DOMAIN . '/oltp/HANDLER_INTERNAL/TXNSTATUS');
        define('PAYTM_STATUS_QUERY_NEW_URL', 'https://' . $PAYTM_DOMAIN . '/oltp/HANDLER_INTERNAL/getTxnStatus');
        define('PAYTM_TXN_URL', 'https://' . $PAYTM_DOMAIN . '/theia/processTransaction');

        $paytm_merchant_id = $this->payment_data->merchant_id;
        $paytm_merchant_key = $this->payment_data->merchant_key;
        // echo $paytm_merchant_key;exit;
//        echo $paytm_merchant_id."-".$paytm_merchant_key;exit;

        require_once("encdec_paytm.php");

        $paramList = array();
        $isValidChecksum = "FALSE";

        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
        $isValidChecksum = verifychecksum_e($paramList, $paytm_merchant_key, $paytmChecksum); //will return TRUE or FALSE string.
        //you have to catch post information  from callback url first then 
        // echo "<pre>";
        // print_r($_POST);exit;
        $requestParamList = array("MID" => $paytm_merchant_id, "ORDERID" => $_POST["ORDERID"]);
        $StatusCheckSum = getChecksumFromArray($requestParamList, $paytm_merchant_key);
//      echo $StatusCheckSum;exit;
        $requestParamList['CHECKSUMHASH'] = $StatusCheckSum;
        // Call the PG's getTxnStatusNew() function for verifying the transaction status.
        $responseParamList = getTxnStatusNew($requestParamList);

//                foreach($responseParamList as $paramName => $paramValue) {
//                    echo $paramName."=".$paramValue."<br>";
//                }
//               exit;
//                  echo "<pre>";
//                  print_r($_POST);
//                  exit;
        //        if (isset($_POST) && count($_POST) > 0) {
        //                foreach ($_POST as $paramName => $paramValue) {
        //                    echo "<br/>" . $paramName . " = " . $paramValue;
        //                }
        //            }
        //            exit;
        // echo $isValidChecksum;exit;

        if ($isValidChecksum == "TRUE") {
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                $order_id = $_POST['ORDERID'];
				
                $txn_id = $_POST['TXNID'];
                $this->load->model('paytm_model');
				if($this->paytm_model->count_txn_id($txn_id) == 0)
				{
					$this->paytm_model->update_transaction($order_id, $txn_id);
					//update member balance
					$this->get_txn_data = $this->paytm_model->get_transaction_data($order_id);
					
					if ($this->get_txn_data) {
						$this->db->trans_start(); //transaction start
						if ($this->get_txn_data->transaction_type == 'purchase_credit') {
							$voucher_id = $this->get_txn_data->voucher_id;
							$voucher_code = $this->get_txn_data->voucher_code;
							$extra_bids = 0;
							if (isset($voucher_id) && isset($voucher_code)) {
								$extra_bids = $this->general->give_extra_bids_voucher($voucher_id, $this->get_txn_data->credit_get);
								//insert voucher records in transaction
								if ($extra_bids)
									$this->general->transaction_records_extra_bids_voucher($this->get_txn_data->user_id, $extra_bids, $voucher_id, $voucher_code);
							}
							
							//update user balance
							$this->paytm_model->update_user_balance($this->get_txn_data->credit_get, $this->get_txn_data->bonus_points, $this->get_txn_data->user_id, $extra_bids);
						} 
						else if ($this->get_txn_data->transaction_type == 'pay_for_won_auction') {
							$shipping_status = '1';
							$this->paytm_model->update_auction_winner($this->get_txn_data->auc_id, $this->get_txn_data->user_id, $shipping_status);
						} 
						else if ($this->get_txn_data->transaction_type == 'buy_auction') {
							$credit_used = $this->get_txn_data->credit_used;
							if ($credit_used > 0) {
								//update user balance
								$this->paytm_model->update_user_balance('0', $credit_used, $this->get_txn_data->user_id, '0');
								$this->paytm_model->insert_user_records_transaction($this->get_txn_data->user_id, $credit_used, '', 'buy_auction_bonus', 'Get Credit back as bonus from auction id:' . $this->get_txn_data->auc_id);
							}
						}
						$this->db->trans_complete(); //transaction end
					}
					$amount = $this->get_txn_data->amount;
					$order_status = 'Success';
					redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/trasaction_completed/' . $order_status . '/' . $txn_id . '/' . $amount));
					exit;
				}else{
					redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/trasaction_completed/' . 'Aborted'));
               		 exit;
				}
            } else if ($_POST["STATUS"] == "TXN_FAILURE") {
                redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/trasaction_completed/' . 'Aborted'));
                exit;
            }
        } else {
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/trasaction_completed/' . 'fail'));
            exit;
            //Process transaction as suspicious.
        }
        exit;


//        $json = json_encode($_POST);
        //$this->send_test_email('paytm_test',$json);
    }

    public function send_test_email($subject, $message) {
        $this->load->library('email');

        $this->email->from('emts.testers@gmail.com', 'Sujit Shah');
        $this->email->to('suip.shesta4@gmail.com');

        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->send();
        echo $this->email->print_debugger();
        exit;
    }

    public function paytm() {

        $this->payment_data = $this->account_module->get_payment_gateway_byid($this->payment_method_id);
        $this->data['paytm_pay_mode'] = $this->payment_data->status;

        if ($this->payment_data) {
            //paypal settings			
            $this->load->model('paytm_model'); //load paypal module
            $this->data['payment_title'] = lang('account_processing_payment'); //from language file
            $this->data['body'] = $this->paytm_model->set_paytm_form_submit();
            //print_r($this->data['body']);exit;
            $this->data['meta_keys'] = '';
            $this->data['meta_desc'] = '';
            $this->template
                    ->set_layout('account')
                    ->enable_parser(FALSE)
                    ->title('My Account | ' . lang('account_processing_payment'))
                    ->build('payment_process', $this->data);
        } else {
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/index'), 'refresh');
            exit;
        }
    }

    public function ccavenue_order() {
        $this->payment_data = $this->account_module->get_payment_gateway_byid('3');

        $this->data['ccavenue_pay_mode'] = $this->payment_data->status;
        $this->ccavenue_model->set_ccavenue_form_submit();
        // $this->form_validation->set_rules($this->ccavenue_model->validate_ccavenue_form);
        //  if ($this->form_validation->run() == TRUE) {
        //   }
        //   $this->data['payment_title'] = lang('account_processing_payment'); //from language file
        //      //print_r($this->data['body']);exit;
        //      $this->data['meta_keys'] = '';
        //      $this->data['meta_desc'] = '';
        //      $this->template
        //      ->set_layout('account')
        //      ->enable_parser(FALSE)
        //      ->title('My Account | ' . lang('account_processing_payment'))
        //      ->build('ccavenue_payment_process', $this->data);
    }

    public function ccavenue_ipn() {
        include('Crypto.php');
		$this->payment_data = $this->account_module->get_payment_gateway_byid('3');
        $workingKey = $this->payment_data->working_key;     //Working Key should be provided here.
        $encResponse = $_POST["encResp"];         //This is the response sent by the CCAvenue Server
        $rcvdString = decrypt($encResponse, $workingKey);      //Crypto Decryption used as per the specified working key.
        $order_status = "";
        $decryptValues = explode('&', $rcvdString);
		//echo "<pre>";
		//print_r($decryptValues);
        $dataSize = sizeof($decryptValues);
        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            if ($i == 3)
                $order_status = $information[1];
            if ($i == 0)
                $order_id = $information[1];
            if ($i == 1)
                $txn_id = $information[1];
			if ($i == 10)
                $amount = $information[1];
        }


        // for($i = 0; $i < $dataSize; $i++) 
        // {
        //     $information=explode('=',$decryptValues[$i]);
        //     echo '<tr><td>'.$information[0].'</td><td>'.urldecode($information[1]).'</td></tr>';
        // }
        // exit;
        // update transaction table and update user 
        // echo $order_status."-".$order_id."-".$txn_id;exit;
        if ($order_status == 'Success') {
			
			//check duplicate transaction
				if($this->ccavenue_model->count_txn_id($txn_id) == 0)
				{
					//get transaction details
					$this->get_txn_data = $this->ccavenue_model->get_transaction_data($order_id);
					//print_r($this->get_txn_data);exit;
					//check empty value
					if($this->get_txn_data)
					{
						if($this->get_txn_data->amount == $amount)
						{
							$this->db->trans_start(); //transaction start
							//update transaction table
           					$this->ccavenue_model->update_transaction($order_id, $txn_id);
							if ($this->get_txn_data) {

								
								if ($this->get_txn_data->transaction_type == 'purchase_credit') {
				
									$voucher_id = $this->get_txn_data->voucher_id;
									$voucher_code = $this->get_txn_data->voucher_code;
									$extra_bids = 0;
									if (isset($voucher_id) && isset($voucher_code)) {
										$extra_bids = $this->general->give_extra_bids_voucher($voucher_id, $this->get_txn_data->credit_get);
										//insert voucher records in transaction
										if ($extra_bids)
											$this->general->transaction_records_extra_bids_voucher($this->get_txn_data->user_id, $extra_bids, $voucher_id, $voucher_code);
									}
									
				
									//update user balance
									$this->ccavenue_model->update_user_balance($this->get_txn_data->credit_get, $this->get_txn_data->bonus_points, $this->get_txn_data->user_id, $extra_bids);
								} else if ($this->get_txn_data->transaction_type == 'pay_for_won_auction') {
									$shipping_status = '1';
									$this->ccavenue_model->update_auction_winner($this->get_txn_data->auc_id, $this->get_txn_data->user_id, $shipping_status);
								} else if ($this->get_txn_data->transaction_type == 'buy_auction') {
				
									$credit_used = $this->get_txn_data->credit_used;
									if ($credit_used > 0) {
										//update user balance
										$this->ccavenue_model->update_user_balance('0', $credit_used, $this->get_txn_data->user_id, '0');
										$this->ccavenue_model->insert_user_records_transaction($this->get_txn_data->user_id, $credit_used, '', 'buy_auction_bonus', 'Get Credit back as bonus from auction id:' . $this->get_txn_data->auc_id);
									}
								}
								
						}
							$this->db->trans_complete(); //transaction end
							
							redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/trasaction_completed/' . $order_status . '/' . $txn_id . '/' . $amount));
            
							exit;
						}
					}
				}
        }
		else{
			//Add failure transaction log
			$this->ccavenue_model->insert_transaction_log($information);
		}
		
        redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/trasaction_completed/' . 'fail'));
        exit;
        
    }

    public function ccavenue_cancel() {
        include('Crypto.php');
		
		$this->payment_data = $this->account_module->get_payment_gateway_byid('3');
        $workingKey = $this->payment_data->working_key;     //Working Key should be provided here.
		
       // $workingKey = $this->ccav_working_key;     //Working Key should be provided here.
        $encResponse = $_POST["encResp"];         //This is the response sent by the CCAvenue Server
        $rcvdString = decrypt($encResponse, $workingKey);      //Crypto Decryption used as per the specified working key.
        $order_status = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        // echo "<center>";

        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            if ($i == 3)
                $order_status = $information[1];
        }



        // for($i = 0; $i < $dataSize; $i++) 
        // {
        //     $information=explode('=',$decryptValues[$i]);
        //     echo '<tr><td>'.$information[0].'</td><td>'.urldecode($information[1]).'</td></tr>';
        // }

        redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/trasaction_completed/' . $order_status), 'refresh');
        exit;
    }

    

    

    public function check_email_check() {
        if ($this->account_module->get_aleady_registered_email() == TRUE) {
            $this->form_validation->set_message('check_email_check', lang('register_email_in_used'));
            return false;
        }
        return true;
    }

    public function check_mobile() {
		//echo strlen($this->input->post('mobile'));exit;
		$user_mobile = $this->input->post('mobile');
		$mobile_old = $this->input->post('mobile_old');
		if($user_mobile!='' && $user_mobile==$mobile_old){
			return true;
		}
		else if($this->input->cookie(SESSION.'mobile_number')==$this->input->post('mobile')){
			return true;
		}
        else if($this->general->check_int_vlaue($user_mobile)==false  || strlen($user_mobile)<10 || strlen($user_mobile)>14){
			$this->form_validation->set_message('check_mobile', lang('enter_valid_mob'));
			return false;
		}
		else if ($this->account_module->get_aleady_registered_mobile() == TRUE) {
            $this->form_validation->set_message('check_mobile', lang('register_mobile_in_used'));
            return false;
        } else if ($this->account_module->check_diff_mobile()) {
            $this->form_validation->set_message('check_mobile', lang('mobile_verify_required'));
            
			//generate otp code
			$opt_code = mt_rand(100000, 999999);
			$this->session->set_userdata(SESSION . 'verification_code', $opt_code);
			//save otp in cookie for 2 hours
			$cookie_otp = array('name' => SESSION . "verification_code", 'value' => $opt_code, 'expire' => 3600 * 2);
            $this->input->set_cookie($cookie_otp);
			
			$cookie_mobile = array('name' => SESSION . "mobile_number", 'value' => $user_mobile, 'expire' => 3600 * 2);
            $this->input->set_cookie($cookie_mobile);
			
			$cookie_otp_count = array('name' => SESSION . "otp_count", 'value' => 1, 'expire' => 3600 * 2);
            $this->input->set_cookie($cookie_otp_count);
			
			//send otp in mobile
			$this->account_module->send_opt_code($opt_code,$user_mobile);
			
			$this->session->set_flashdata(array('error_message' => 'A OTP has been sent to your registered mobile number. Please enter the OTP below to verify your mobile number. The OTP code will expire in 2 hours.'));
			//redirect($this->general->lang_uri('/my-account/user/edit'));
            //exit;
            return false;
        }
        //destory session veriable 		
        $this->session->unset_userdata(SESSION . 'verify_mobile');

        return true;
    }

    public function confirm_code($confirmcode) {
        if ($confirmcode != $this->input->cookie(SESSION.'verification_code')) {
            $this->form_validation->set_message('confirm_code', lang('the_code_not_match'));
            return false;
        }

        return true;
    }

    public function activation($email = '', $code = '', $id = '') {
        $query = $this->db->get_where('members', array('activation_code' => $code, 'id' => $id));

        if ($query->num_rows() > 0) {
            $user_data = $query->row_array();

            $user_id = $user_data['id'];
            $new_email = $user_data['new_email'];

            $data = array('email' => $new_email);
            $this->db->where('id', $id);
            $this->db->update('members', $data);

            $this->session->set_flashdata('message', lang('profile_msg_update_new_email'));

            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/edit'), 'refresh');
        } else {
            $this->session->set_flashdata('message', lang('profile_msg_no_valide_info_2update_new_email'));

            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/edit'), 'refresh');
            exit;
        }
    }

    public function testimonial($product_id = '') {
        $this->load->library('upload');
        $this->load->library('image_lib');

        $this->data = array();
        $this->data['account_menu_active'] = 'wonauctions';
        //check blank value
        if ($product_id == '') {
            //redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/wonauctions'),'refresh');exit;
        }

        //check valide winner and payment status completed for sending testimonial
        if ($this->account_module->check_valide_testimonial_user($product_id) == 0) {
            //redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/wonauctions'),'refresh');exit;
        }

        //ckeck testimonail added
        $user_testimonial = $this->account_module->check_testimonial_added($product_id);
        if ($user_testimonial == false) {

            //$this->form_validation->set_rules('img', ' Upload Image', 'required');
            $this->form_validation->set_rules('description', 'lang:user_testimonial_message', 'required');
            if ($this->form_validation->run() == TRUE && $this->account_module->upload_testimonial_image($product_id) == FALSE) {
                $this->session->set_flashdata('message', lang('user_testimonial_success'));
                redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/wonauctions'), 'refresh');
                exit;
                exit;
            }

            $view_page = 'testimonial_add';
        } else {
            $this->session->set_flashdata('message', lang('user_testimonial_already_sent'));
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/wonauctions'), 'refresh');
            exit;
        }

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title('My Account | ' . SITE_NAME)
                ->build($view_page, $this->data);
    }

    function agm() {

        $this->data['account_menu_active'] = '';

        $this->form_validation->set_rules('t_c', 'lang:acc_toc_desc', 'required');
        if ($this->form_validation->run() == TRUE) {
            $this->account_module->update_user_terms_condition();

            //update user session
            $this->session->set_userdata(array(SESSION . 'terms' => 1));

            redirect(site_url(), 'refresh');
            exit;
        }


        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title('My Account | ' . SITE_NAME)
                ->build('terms_condition', $this->data);
    }

    public function buynow($product_id = '') {
        $user_id = $this->session->userdata(SESSION . 'user_id');
        //check integer value
        if ($this->general->check_int_vlaue($product_id) == false) {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
            exit;
        }

        //Check is buy now & close auction
        if ($this->general->is_buy_now_auc($product_id) == false) {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
            exit;
        }
        //echo $product_id;exit;
        //Check if user already buy this auction
        if ($this->general->is_user_buy_auc($product_id, $user_id) > 0) {
            $this->session->set_flashdata('error_message', lang('product_already_bought'));
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
            exit;
        }

        //Check is user palce bid in this auction
        $this->data['total_bids_placed'] = $this->general->get_bid_count_by_auc_user($product_id, $user_id);

        if ($this->data['total_bids_placed'] < MIN_BID_4BUY_NOW) {
            $this->session->set_flashdata('error_message', lang('buy_now_option_enable').' '. MIN_BID_4BUY_NOW);
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
            exit;
        }

        //check user buy product and get discount in week
        //echo $this->general->check_user_purchase_product_inweek($user_id);
        if ($this->general->check_user_purchase_product_inweek($user_id) >= BUY_NOW_PRODUCT_PER_WEEK) {
            $this->data['total_bids_placed'] = 0;
        }


        $this->data['auc_lists'] = $this->account_module->get_live_auctions_byid($product_id);

        //Check quantity
        $no_qty = $this->data['auc_lists']->no_qty;
        if ($this->general->total_sold_product($product_id) >= $no_qty) {
            $this->session->set_flashdata('error_message', lang('all_product_sold'));
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
            exit;
        }


        $this->data['account_menu_active'] = 'wonauctions';
		$this->data['account_page_name'] = lang('order_auctions');
        $this->data['payment_lists'] = $this->account_module->get_all_payment_gateway();
        $this->data['countries'] = $this->general->get_country();
        //$this->data['shipping_info'] = $this->account_module->get_user_shipping_details();
        $this->data['profile'] = $this->account_module->get_user_details();



        //set validation rules		
        $validate_settings = array(
            array('field' => 'payment_type', 'label' => 'lang:account_payment_method', 'rules' => 'trim|required'),
            array('field' => 'email', 'label' => 'lang:register_email', 'rules' => 'trim|required'),
            array('field' => 'name', 'label' => 'lang:profile_name', 'rules' => 'trim|required'),
            array('field' => 'country', 'label' => 'lang:register_country', 'rules' => 'required'),
            array('field' => 'address', 'label' => 'lang:profile_address', 'rules' => 'trim|required'),
			 array('field' => 'phone', 'label' => 'lang:label_phone', 'rules' => 'trim|required'),
            array('field' => 'city', 'label' => 'lang:profile_city', 'rules' => 'trim|required'),
            array('field' => 'post_code', 'label' => 'lang:profile_post_code', 'rules' => 'trim|required'),
            array('field' => 'ship_name', 'label' => 'lang:profile_name', 'rules' => 'trim|required'),
			array('field' => 'ship_email', 'label' => 'lang:register_email', 'rules' => 'trim|required|valid_email'),
            array('field' => 'ship_country', 'label' => 'lang:register_country', 'rules' => 'required'),
            array('field' => 'ship_address', 'label' => 'lang:profile_address', 'rules' => 'trim|required'),
            array('field' => 'ship_city', 'label' => 'lang:profile_city', 'rules' => 'trim|required'),
            array('field' => 'ship_post_code', 'label' => 'lang:profile_post_code', 'rules' => 'trim|required'),
            array('field' => 'ship_phone', 'label' => 'lang:label_phone', 'rules' => 'trim|required')
        );
        $this->form_validation->set_rules($validate_settings);

        if ($this->form_validation->run() == TRUE) {
            $this->payment_method_id = $this->input->post('payment_type', TRUE);
            switch ($this->payment_method_id) {
                case '1':
                    $this->paypal();
                    break;
                case '3':
                    $this->ccavenue();
                    break;
                case '4':
                    $this->paytm();
                    break;
               
                default:
                    $this->session->set_flashdata('error_message', lang('valid_info_provide'));
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    exit;
                    exit;
                    break;
            }
        } else {

            $this->data['meta_keys'] = '';
            $this->data['meta_desc'] = '';
            $this->template
                    ->set_layout('account')
                    ->enable_parser(FALSE)
                    ->title('My Account | ' . SITE_NAME)
                    ->build('buy_auctions_payment', $this->data);
        }
    }

    public function buyauctions() {
        //Check profile empty befor start bidding.
        if($this->user_id && $this->general->check_mobile_blank_field() >=1){
			$this->session->set_flashdata('error_message', lang('label_req_mobile_verification_msg'));
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/mobile'),'refresh');
			exit;
		}
		else if ($this->session->userdata(SESSION . 'user_id') && $this->general->check_profile_blank_field() >= 1) {
            $this->session->set_flashdata('message', lang('profile_complete_personal_details'));
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/edit'), 'refresh');
            exit;
        }

        $this->data['account_menu_active'] = 'buyauctions';
		$this->data['account_page_name'] = lang('account_buy_auctions');
		
        $config['base_url'] = $this->general->lang_uri('/' . MY_ACCOUNT . '/user/buyauctions');
        $config['total_rows'] = $this->account_module->total_buy_auctions();
        $config['per_page'] = 15;
        $config['page_query_string'] = FALSE;
        $config["uri_segment"] = 5;

        $this->general->frontend_pagination_config($config);
        $this->pagination->initialize($config);
        $this->data['offset'] = $this->uri->segment(5, 0);

        $this->data['buy_auc'] = $this->account_module->get_my_buy_auctions($config["per_page"], $this->data['offset']);
        $this->data["pagination_links"] = $this->pagination->create_links();


        $this->data['meta_keys'] = '';
        $this->data['meta_desc'] = '';

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title(lang('account_buy_auctions').' | ' . SITE_NAME)
                ->build('buy_auctions', $this->data);
    }

    public function remindme() {
        //Check profile empty befor start bidding.
        if($this->user_id && $this->general->check_mobile_blank_field() >=1){
			$this->session->set_flashdata('error_message', lang('label_req_mobile_verification_msg'));
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/mobile'),'refresh');
			exit;
		}
		else if ($this->session->userdata(SESSION . 'user_id') && $this->general->check_profile_blank_field() >= 1) {
            $this->session->set_flashdata('message', lang('profile_complete_personal_details'));
            redirect($this->general->lang_uri('/' . MY_ACCOUNT . '/user/edit'), 'refresh');
            exit;
        }

        $this->data['account_menu_active'] = 'remindme';
        $this->data['reminder_me_lists'] = $this->account_module->get_reminder_me_lists();

        $this->template
                ->set_layout('account')
                ->enable_parser(FALSE)
                ->title('My Account | ' . SITE_NAME)
                ->build('reminder_me', $this->data);
    }

    public function under_18_check() {
        $day = $this->input->post('dobday', TRUE);
        $month = $this->input->post('dobmonth', TRUE);
        $year = $this->input->post('dobyear', TRUE);
        $dateofbirth = $year . "-" . $month . "-" . $day;
        // $then will first be a string-date
        $bday = strtotime($dateofbirth);
        //The age to be over, over +18
        $res = strtotime('+18 years', $bday);
        //echo $res;
        if (time() > $res) {

            return TRUE;
        } else {
            $this->form_validation->set_message('under_18_check', lang('age_valid_18'));

            return FALSE;
        }
    }

    function mobile_verification() {
        $this->load->library('netcoresms_class');

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $mobile_number = $this->input->post('mobile', true);

        // $confirmcode = $this->general->random_number();
        //$sms_message = "Please verify your mobile number:\r\n";
        // $sms_message = "verification code: " . $confirmcode . " \r\n " . SITE_NAME;

        $result = $this->netcoresms_class->sendSMS($mobile_number);
        echo lang('verification_code_sent_success');
        exit;

        //print_r($result); exit;
        //$result['response']['error']
        //$result['status']
        // $this->session->set_userdata(SESSION . 'verification_code', $confirmcode);
        // if ($result['status'] == '200') {
        //     //$this->session->set_userdata(SESSION . 'verification_code', $confirmcode);
        //     echo lang('verification_code_sent_success') . $confirmcode;
        // } else {
        //     echo $result['response']['error'] . lang('for_testing_purpose_code') . $confirmcode; //"Verification code could not be sent. Please try again.";
        // }
    }

    public function remove_profile_img() {
        $this->account_module->remove_profile_img();
    }

    //ajax function to upload profile image
    public function upload_profile_img() {
        //echo './'.USER_PROFILE_PATH;//exit;
        //print_r($_FILES); //exit;
        //only if file is selected and submit button is clicked
        if ($_FILES) {
            //START UPLOADING profile THUMB IMAGE
            $image1_name = $this->file_settings_do_upload('profile_img');

            if ($image1_name['file_name']) {
                $this->image_name_path1 = $image1_name['file_name'];
                //resize image
                $this->resize_image($this->image_name_path1, $image1_name['raw_name'] . $image1_name['file_ext'], 200, 200);

                //update user profile images
                $this->account_module->update_user_profile_image($this->image_name_path1, $this->session->userdata(SESSION . 'user_id'));

                print_r(json_encode(array('status' => 'success', 'message' => base_url(USER_PROFILE_PATH . $this->image_name_path1))));
                // exit;
            } else {
                // print_r(json_encode(array('status' => 'error', 'message' => $this->error_img)));
                // exit;
            }
        } else {
            // print_r(json_encode(array('status' => 'error', 'message' => lang('image_not_selected'))));
        }

        // $this->account_module->update_user_gender($this->session->userdata(SESSION . 'user_id'));
        exit;
    }

    function upload_cropped_img() {
        $img_blob = $this->input->post('img_data');

        $new_name = time() . rand(0, 100000);
        $path = './' . USER_PROFILE_PATH;
        $img = explode(",", $_POST['img_data']);
        $savefile1 = file_put_contents($path . "$new_name.jpg", base64_decode($img['1']));

        $this->db->select('image');
        $this->db->where('id', $this->session->userdata(SESSION . 'user_id'));
        $query = $this->db->get('members');
        if ($query->num_rows() == '1') {
            $old_img = $query->row()->image;
        }


        @unlink('./' . USER_PROFILE_PATH . $old_img);
        @unlink('./' . USER_PROFILE_PATH . 'thumb_' . $old_img);

        $datapass = array(
            'image' => $new_name . ".jpg",
        );
		
        $this->db->where('id', $this->session->userdata(SESSION . 'user_id'));
        $this->db->update('members', $datapass);
		$this->session->set_userdata(array(SESSION . 'profile_pic' => 'thumb_' . $new_name . '.' . 'jpg'));
        $this->resize_image($new_name . '.' . 'jpg', 'thumb_' . $new_name . '.' . 'jpg', 150, 150);
    }

    public function file_settings_do_upload($file_name) {
        $config['upload_path'] = './' . USER_PROFILE_PATH; //define in constants
        $config['allowed_types'] = 'gif|jpg|png';
        $config['remove_spaces'] = TRUE;
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 5000;
        $config['max_width'] = 1024;
        $config['max_height'] = 1024;
        $this->upload->initialize($config);

        $this->upload->do_upload($file_name);
        if ($this->upload->display_errors()) {
            $this->error_img = $this->upload->display_errors();
            return false;
        } else {
            $data = $this->upload->data();
            return $data;
        }
    }

    public function resize_image($file_name, $thumb_name, $width, $height) {

        $config['image_library'] = 'gd2';
        $config['source_image'] = './' . USER_PROFILE_PATH . $file_name;
        //$config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['master_dim'] = 'height';
        $config['new_image'] = './' . USER_PROFILE_PATH . $thumb_name;

        //echo $config['new_image'];exit;
        $this->image_lib->initialize($config);

        $this->image_lib->resize();
        // $this->image_lib->clear(); 
    }

    public function test_test() {
        echo "helo test email";





        $config = array(
            'protocol' => 'sendmail',
            'smtp_host' => 'smtp.falconide.com',
            'smtp_port' => 25,
            'smtp_pass' => '77085$ee3d113',
            'smtp_user' => 'chasebid',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE,
            'newline' => "\r\n",
                //'crlf' => "\r\n",
        );
        $this->load->library('email');
        $this->email->initialize($config);



        $this->email->from('emts.testers@gmail.com', 'joker test');
        $this->email->to('suip.shesta4@gmail.com');

        $this->email->subject('test3');
        $this->email->message('helo this is test');

        $this->email->send();
        $this->email->print_debugger();
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */