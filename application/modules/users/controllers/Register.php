<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends CI_Controller {

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

        

        //check banned IP address
        $this->general->check_banned_ip();

        //load CI library
        $this->load->library('form_validation');

        //Changing the Error Delimiters
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        //load module
        $this->load->model('register_module');
        //load module
        $this->load->model('login_module');

        //load mailchimp library
        $this->load->library('mailchimp_library');

        //push notification library
        $this->load->library('fcm');

        //sms library
        $this->load->library('Netcoresms_class');


        // $this->load->library('twitteroauth');
        include_once APPPATH . "libraries/Twitteroauth.php";
        // $this->load->library('Twitter');	
    }

    public function index() {
        //echo APPPATH;
		
		if ($this->session->userdata(SESSION . 'user_id')) {
            redirect(site_url(''));
            exit;
        }
		
        $refid = $this->input->get('ref');
        $ref_email = $this->input->get('email');
        $this->data['ref_email'] = $ref_email;
        if ($this->input->cookie('set_refer_id') == "") {
            $cookie1 = array('name' => 'set_refer_id', 'value' => $refid, 'expire' => 60 * 60 * 24 * 7);
            $this->input->set_cookie($cookie1);
        }


        $this->data['countries'] = $this->general->get_active_countries();
        // $this->data['user_source'] = $this->register_module->get_user_source_from();
        // Set the validation rules
        // print_r($this->input->post());
        // exit;
        $this->form_validation->set_rules($this->register_module->validate_settings);

        if ($this->form_validation->run() == TRUE) {
            $activation_code = $this->register_module->insert_member();
            if ($activation_code != 'system_error') {
                if ($this->session->userdata('is_fb_user') == "Yes") {
                    $login_status = $this->login_module->check_login_process();

                    if ($this->session->userdata(SESSION . 'user_id')) {
                        //Get Language info for this users
                        $lang_information = $this->general->get_lang_info($this->session->userdata(SESSION . 'lang_id'));
                        $this->session->set_userdata(array(SESSION . 'lang_flag' => $lang_information['lang_flag']));
                        $this->session->set_userdata(array(SESSION . 'short_code' => $lang_information['short_code']));
                        //unset CUSTOM SESSION FOR FB
                        $this->session->unset_userdata('fb_signup');
                        $this->session->unset_userdata('me');
                        $this->session->unset_userdata('is_fb_user');
                        redirect($this->general->lang_switch_uri($lang_information['short_code']));
                        exit;
                    }
                } else {
                    $this->session->set_userdata("registration", "success");
                   
                    $enable_check_email_acitvation = $this->general->check_notification_enable('register_notification_activation');
                    if ($enable_check_email_acitvation->is_email_notification_send == '1') {
                        $this->register_module->reg_confirmation_email($activation_code);
                         // print_r("coming to registration success"); exit;
                        redirect($this->general->lang_uri('/users/register/success/'), 'refresh');
                        exit;
                    }

                    $enable_check = $this->general->check_notification_enable('register_notification');

                    if ($enable_check->is_email_notification_send == '1') {
                        // print_r("coming to enable check");exit;
                        $this->register_module->reg_success_email();
                    }


                    $login_status = $this->login_module->check_login_process();
                  
                    if ($login_status == 'success' && $this->session->userdata(SESSION . 'user_id')) {
                        //set refferrer bonus balance
                        $referr_id = get_cookie('set_refer_id');
                        if ($referr_id != '0' && $referr_id != null)
                            $this->register_module->set_referrer_bonus($referr_id);

                        //redirect for login
                        $this->session->set_flashdata("registration", "success");
                        redirect($this->general->lang_switch_uri($this->session->userdata(SESSION . 'short_code')), 'refresh');
                        exit;
                    }
                }
            }
        }

        //$this->data['twitter_login_url'] = $this->twitter_init();
        $seo_data = $this->general->get_seo(LANG_ID, 2);
        if ($seo_data) {
            //set SEO data
            $this->page_title = $seo_data->page_title . ' | ' . SITE_NAME;
            $this->data['meta_keys'] = $seo_data->meta_key;
            $this->data['meta_desc'] = $seo_data->meta_description;
        } else {
            //set SEO data
            $this->page_title = SITE_NAME;
            $this->data['meta_keys'] = SITE_NAME;
            $this->data['meta_desc'] = SITE_NAME;
        }

        $this->template
                ->set_layout('body_full')
                ->enable_parser(FALSE)
                ->title($this->page_title)
                ->build('register_body', $this->data);
    }

    public function twitter_init() {
        $consumerKey = TWITTER_APP_KEY;
        $consumerSecret = TWITTER_APP_SECRET;
        $oauthCallback = $this->general->lang_uri('/users/login/twitter_login');
        //unset token and token secret from session
        $this->session->unset_userdata('token');
        $this->session->unset_userdata('token_secret');
        //Fresh authentication
        $connection = new TwitterOAuth($consumerKey, $consumerSecret);
        $requestToken = $connection->getRequestToken($oauthCallback);
        //Received token info from twitter
        $this->session->set_userdata('token', $requestToken['oauth_token']);
        $this->session->set_userdata('token_secret', $requestToken['oauth_token_secret']);
        //Any value other than 200 is failure, so continue only if http code is 200
        if ($connection->http_code == '200') {
            //redirect user to twitter
            $twitterUrl = $connection->getAuthorizeURL($requestToken['oauth_token']);
            return $twitterUrl;
        } else {
            $data['oauthURL'] = base_url() . 'user_authentication';
            $data['error_msg'] = lang('error_connecting');
            echo $data['error_msg'];
            exit;
        }
    }

    public function success() {
        // print_r("coming");
        // print_r($this->session->userdata('registration'));exit;
        if ($this->session->userdata("registration") != "success") {
            redirect($this->general->lang_uri('/users/register'), 'refresh');
            exit;
        }
        $this->data['cms'] = $this->register_module->get_cms(22);

        // $seo_data=$this->general->get_seo(LANG_ID, 9);
        if ($this->data['cms']) {
            //set SEO data
            $this->page_title = $this->data['cms']->page_title . ' | ' . SITE_NAME;
            $this->data['meta_keys'] = $this->data['cms']->meta_key;
            $this->data['meta_desc'] = $this->data['cms']->meta_description;
        } else {
            //set SEO data
            $this->page_title = SITE_NAME;
            $this->data['meta_keys'] = SITE_NAME;
            $this->data['meta_desc'] = SITE_NAME;
        }


        $this->template
                ->set_layout('body_full')
                ->enable_parser(FALSE)
                ->title($this->page_title)
                ->build('register_status', $this->data);
    }

    public function under_16_check() {
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
            $this->form_validation->set_message('under_16_check', lang('age_valid_18'));
            return FALSE;
        }
    }

    public function activation($activation_code, $user_id) {

        if ($this->register_module->activated($activation_code, $user_id) == true) {
            $this->data['cms'] = $this->register_module->get_cms(21);
            // $seo_data=$this->general->get_seo(LANG_ID, 10);
            if ($this->data['cms']) {
                //set SEO data
                $this->page_title = $this->data['cms']->page_title . ' | ' . SITE_NAME;
                $this->data['meta_keys'] = $this->data['cms']->meta_key;
                $this->data['meta_desc'] = $this->data['cms']->meta_description;
            } else {
                //set SEO data
                $this->page_title = SITE_NAME;
                $this->data['meta_keys'] = SITE_NAME;
                $this->data['meta_desc'] = SITE_NAME;
            }

            $this->template
                    ->set_layout('body_full')
                    ->enable_parser(FALSE)
                    ->title($this->page_title)
                    ->build('register_status', $this->data);
        } else {
            redirect($this->general->lang_uri(''), 'refresh');
            exit;
        }
    }

    public function voucher($str) {
        if ($str) {
            if ($this->register_module->check_voucher($str) == 0) {
                $this->form_validation->set_message('voucher', lang('register_wrong_voucher'));
                return FALSE;
            }

            return TRUE;
        }
    }

    public function check_username() {
        $username = $this->input->post('username', TRUE);
        $query = $this->db->get_where("members", array('user_name' => $username));
        echo $query->num_rows();
    }

    public function check_email() {
        $email = $this->input->post('email', TRUE);
        $query = $this->db->get_where("members", array('email' => $email));
        echo $query->num_rows();
    }

    function mobile_verification() {


        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }


        $dub_mob = $this->dup_mobile_taken();

        if ($dub_mob == FALSE) {
            echo lang('mobile_no_not_use_message');
            exit;
        }


        $mobile_number = $this->input->post('mobile', true);


        $result = $this->netcoresms_class->sendSMS($mobile_number);
        echo lang('verification_code_sent_success');
        exit;
        //$result['response']['error']
        //$result['status']
        $this->session->set_userdata(SESSION . 'verification_code', $confirmcode);
        if ($result['status'] == '200') {
            //$this->session->set_userdata(SESSION . 'verification_code', $confirmcode);
            echo lang('verification_code_sent_success');
        } else {
            echo $result['response']['error'] . lang('verifiaction_code_not_sent'); //"Verification code could not be sent. Please try again.";
        }
    }

    public function dup_mobile_taken() {
        $mobile = trim($this->input->post('mobile'));
        $query = $this->db->get_where("members", array('mobile' => $mobile));
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function mobile_taken() {
        $mobile = trim($this->input->post('mobile'));
        $query = $this->db->get_where("members", array('mobile' => $mobile));
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('mobile_taken', lang('mobile_already_exist'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function confirm_code($confirmcode) {
        if ($confirmcode != $this->session->userdata(SESSION . 'verification_code')) {
            $this->form_validation->set_message('confirm_code', lang('the_code_not_match'));
            return false;
        }
        return true;
    }

    public function subscribe() {
		$response = array();
		$response['status']='error';
		//mail("sujit2039@gmail.com","eodbox","testing...");
		//exit;
		if(is_ajax())
	   {
		   $this->form_validation->set_rules('subscribe_email', 'lang:register_email', 'trim|required|valid_email');
			if ($this->form_validation->run() == TRUE) {
				//echo SYSTEM_EMAIL;exit;
				//SUBSCRIPTION_EMAIL
        		$subscribe_email = $this->input->post('subscribe_email');
				$response['status'] = 'success';
				$response['message'] = lang('your_email_listed');
				
				//load email library
				$this->load->library('email');		
				$this->load->model('email_model');			
		
				$subject = "Subscribe to eodbox";
				$emailbody = $subscribe_email;
		
				$this->email->from(SYSTEM_EMAIL);
				$this->email->to(SUBSCRIPTION_EMAIL);
				$this->email->subject($subject);
				$this->email->message($emailbody);
				$this->email->send();
				//mail("sujit2039@gmail.com",$subject,$emailbody);
				
			}
		   else
		   	$response['message'] = validation_errors();
			
		   echo json_encode($response); exit;
	   }
        //print_r($_POST);exit;
		
        /*$result = $this->mailchimp_library->call('lists/subscribe', array(
            'id' => LIST_ID,
            'email' => array('email' => $email),
            //'merge_vars'        => array('FNAME'=>'Sujit', 'LNAME'=> 'Shah'),
            'double_optin' => false,
            'update_existing' => true,
            'replace_interests' => false,
            'send_welcome' => false,
        ));*/
        //print_r($result);exit;
		
		
        /*if (isset($result['status']) && $result['status'] == 'error')
            echo lang('try_later');
        else
            echo lang('your_email_listed');*/
    }
	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */