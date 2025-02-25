<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct() {

		parent::__construct();		

		//load custom library
		$this->load->library('my_language');
		$this->load->helper('text');

		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'),'refresh');exit;
		}

		

		if(SITE_STATUS == 'maintanance')
		{

			if(!$this->session->userdata('MAINTAINANCE_KEY') OR $this->session->userdata('MAINTAINANCE_KEY')!='YES'){

				redirect($this->general->lang_uri('/maintanance'));exit;

			}			

		}

		

		if($this->session->userdata(SESSION.'user_id'))
		{

			redirect($this->general->lang_uri(''),'refresh');exit;

		}

		 //check banned IP address

		$this->general->check_banned_ip();

		//load CI library
		$this->load->library('form_validation');

                $this->load->library('Netcoreemail_class');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

		
		//load module
		$this->load->model('login_module');
		$this->load->model('register_module');
		
		//include_once APPPATH."libraries/Twitteroauth.php";
		//$this->load->library('TwitterOAuth');	

	}

	public function twitter_init(){

		$consumerKey = TWITTER_APP_KEY;

		$consumerSecret = TWITTER_APP_SECRET;

		$oauthCallback =$this->general->lang_uri('/users/login/twitter_login');

		//unset token and token secret from session

		$this->session->unset_userdata('token');

		$this->session->unset_userdata('token_secret');

			//Fresh authentication

		$connection = new TwitterOAuth($consumerKey, $consumerSecret);

		$requestToken = $connection->getRequestToken($oauthCallback);

			//Received token info from twitter

		$this->session->set_userdata('token',$requestToken['oauth_token']);

		$this->session->set_userdata('token_secret',$requestToken['oauth_token_secret']);

			//Any value other than 200 is failure, so continue only if http code is 200

		if($connection->http_code == '200'){

				//redirect user to twitter

			$twitterUrl = $connection->getAuthorizeURL($requestToken['oauth_token']);

			return $twitterUrl;

		}else{

			$data['oauthURL'] = base_url().'user_authentication';

			$data['error_msg'] = lang('error_connecting_twitter');

			echo $data['error_msg'];

			exit;

		}

	}

	

	public function index()
	{

		$this->data['twitter_login_url'] = '';//$this->twitter_init();

		// Set the validation rules

		$this->data['right_sidebar'] = 'common/sidebar_right';

		$this->form_validation->set_rules($this->login_module->validate_settings);

		if($this->form_validation->run()==TRUE)

		{

			$login_status = $this->login_module->check_login_process();

			// echo $login_status;exit;

			if($login_status == 'success' && $this->session->userdata(SESSION.'user_id'))
			{
				//update user id in member device token
				$this->login_module->check_update_device_user_id($this->session->userdata(SESSION.'user_id'));
				
				//check for terms & conditon
				if($this->session->userdata(SESSION.'terms') != 1)

					redirect($this->general->change_lang_uri($this->session->userdata(SESSION.'short_code'),'/'.MY_ACCOUNT.'/user/agm'),'refresh');

				else

					redirect($this->general->lang_switch_uri($this->session->userdata(SESSION.'short_code')),'refresh');

				exit;

			}

			else

			{

				if($login_status==='unregistered')					

					{$this->session->set_flashdata('loginerror',lang('login_error_unregistered'));}

				else if($login_status==='unverified')

					{$this->session->set_flashdata('loginerror',lang('login_error_unverified'));}

				else if($login_status==='suspended')

					{$this->session->set_flashdata('loginerror',lang('login_error_suspended'));}

				else if($login_status==='close')

					{$this->session->set_flashdata('loginerror',lang('login_error_close'));}

				else if($login_status==='invalid')

					{$this->session->set_flashdata('loginerror',lang('login_error_invalid'));}

				else if($login_status==='sub_domain')

					{$this->session->set_flashdata('loginerror',lang('login_error_sub_domain'));}

				redirect($this->general->lang_uri('/users/login'),'refresh');

				exit;

			}

		}

		

		$seo_data=$this->general->get_seo(LANG_ID, 3);

		if($seo_data)

		{

			//set SEO data

			$this->page_title = $seo_data->page_title.' | '.SITE_NAME;

			$this->data['meta_keys']= $seo_data->meta_key;

			$this->data['meta_desc']= $seo_data->meta_description;

		}

		else

		{

			//set SEO data

			$this->page_title = SITE_NAME;

			$this->data['meta_keys']= SITE_NAME;

			$this->data['meta_desc']= SITE_NAME;

		}		

		

		$this->template

		->set_layout('body_full')

		->enable_parser(FALSE)

		->title($this->page_title)			

		->build('login_body', $this->data);

	}

	public function check_existing_user(){

		$id=$this->input->post('id');

		$do_login=$this->check_user_exist($id);

		if($do_login=="success"){

			//$return_data['status']="exist";

			print_r(json_encode(array('status'=>'exist'))); exit;

		}else{

			$return_data['status']="not exist";

			print_r(json_encode($return_data)); exit;

		}

		

	}

	public function check_user_exist($social_id)

	{

		

		//get member info based on login value

		$options = array('social_id' => $social_id); //'facebook_id' = $facebook_id

		$query = $this->db->get_where('members',$options);

		if($query->num_rows()==1)

		{

			return 'success';

		}

		

	}

	

	public function check_unique_email(){

		$email=$this->input->post('u_email');

		$query = $this->db->get_where('members',array('email'=>$email));

		echo $query->num_rows();

	}

	public function forgot()
	{


		$this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email');

		

		if($this->form_validation->run()==TRUE)

		{

			 //check email from our database record

			if($this->login_module->check_email() == 1)

			{

				$this->login_module->forget_password_reminder_email();

				$this->session->set_flashdata('message',lang('forget_password_message_success'));

				$this->session->set_flashdata('success',"yes");

				redirect($this->general->lang_uri('/users/login/forgot'),'refresh');

				exit;

			}

			else

			{

				$this->session->set_flashdata('message_err',lang('forget_password_message_error'));

				redirect($this->general->lang_uri('/users/login/forgot'),'refresh');

				exit;

			}

			

		}

		

		$seo_data=$this->general->get_seo(LANG_ID, 4);

		if($seo_data)

		{

			//set SEO data

			$this->page_title = $seo_data->page_title.' | '.SITE_NAME;

			$this->data['meta_keys']= $seo_data->meta_key;

			$this->data['meta_desc']= $seo_data->meta_description;

		}

		else

		{

			//set SEO data

			$this->page_title = SITE_NAME;

			$this->data['meta_keys']= SITE_NAME;

			$this->data['meta_desc']= SITE_NAME;

		}		

		

		

		$this->template

		->set_layout('body_full')

		->enable_parser(FALSE)

		->title($this->page_title)			

		->build('forgot_password', $this->data);

	}

	

	public function reset_password()

	{

		$code = urldecode($this->input->get('key'));

		$email = (base64_decode(urldecode($this->input->get('auth'))));

		$this->data['right_sidebar'] = 'common/sidebar_right';

		$user = $this->login_module->is_user_ready_reset_password($email,$code);

		if($user)

		{

			if ($this->input->server('REQUEST_METHOD') === 'POST'){

           	// echo $this->input->server('REQUEST_METHOD'); exit;

				$this->form_validation->set_rules($this->login_module->validate_rules_reset_password);

				//echo "form validatoin"; exit;

				if($this->form_validation->run()==TRUE){

					$trans_stat = $this->login_module->change_users_password($email);

					if($trans_stat){

						$this->session->unset_userdata(SESSION.'user_id');

						$this->session->unset_userdata(SESSION.'first_name');

						$this->session->unset_userdata(SESSION.'email');

						$this->session->unset_userdata(SESSION.'last_name');

						$this->session->unset_userdata(SESSION.'username');

						$this->session->unset_userdata(SESSION.'balance');

						$this->session->unset_userdata(SESSION.'country_id');

						$this->session->set_flashdata('message', lang('psd_change_success'));

						redirect($this->general->lang_uri('/users/login')); exit();

					}  else {

						$this->session->set_flashdata('error_message', lang('unable_psd_change'));

						redirect($this->general->lang_uri('users/login/forgot'),'refresh'); exit(); 

					}

				}

			}

			

			if(strtotime($user->forgot_password_code_expire)> time()){

				$this->data['allow_reset'] = TRUE;

			}else{

				$this->data['allow_reset'] = FALSE;

				$this->session->set_flashdata('error_message',lang('session_expired_message'));

				redirect($this->general->lang_uri('/users/login/forget'));

			}

			$seo_data=$this->general->get_seo(LANG_ID, 5);

			if($seo_data)

			{

			//set SEO data

				$this->page_title = $seo_data->page_title.' | '.SITE_NAME;

				$this->data['meta_keys']= $seo_data->meta_key;

				$this->data['meta_desc']= $seo_data->meta_description;

			}

			else

			{

			//set SEO data

				$this->page_title = SITE_NAME;

				$this->data['meta_keys']= SITE_NAME;

				$this->data['meta_desc']= SITE_NAME;

			}		

			

			$this->template

			->set_layout('body_full')

			->enable_parser(FALSE)

			->title($this->page_title)			

			->build('v_reset_password', $this->data);

		}

		else

		{			

			redirect($this->general->lang_uri(''),'refresh');

			exit;

		}	

	}

	

	public function applogin()

	{

		

		if($this->input->post('username') && $this->input->post('password'))

		{

			$login_status = $this->login_module->check_login_process_app();

			

			//$this->output->set_header("HTTP/1.1 200 OK");

			if($login_status == 'success')

			{

				

				$result = array("status" => 1);

				echo json_encode($result);

			}

			else

			{

				$result = array("status" => 0);

				echo json_encode($result);

			}

		}

	}

	

	public function appFBlogin()

	{

		$email = $this->input->post('email',TRUE);

		$password = $this->input->post('password',TRUE);

		

		if($email != '' && $password === 'NEK321acerJP76LAMP')

		{

			$login_status = $this->login_module->check_FBlogin_process_app($email);

			//echo $login_status;exit;

			$this->output->set_header("HTTP/1.1 200 OK");

			if($login_status == 'success' && $this->session->userdata(SESSION.'user_id'))

			{

				

				$result = array("status" => 1);

				echo json_encode($result);

			}

			else

			{

				$result = array("status" => 0);

				echo json_encode($result);

			}

		}

	}

	public function facebook_login(){

		if(!$this->input->is_ajax_request())
		{
			exit(lang.('no_direct_script_allowed'));
		}

		$response = array();	
		$response['status'] = 'error';
		$response['message'] = 'Error occurred please try again later!';
		$response['redirect_to'] = "";
		
		$facebook_id = ($this->input->post('id',TRUE) && $this->input->post('id',TRUE)!='') ? $this->input->post('id',TRUE):'';
		$email = ($this->input->post('email',TRUE) && $this->input->post('email',TRUE)!='') ? $this->input->post('email',TRUE):'';
		$first_name = ($this->input->post('first_name',TRUE) && $this->input->post('first_name',TRUE)!='') ? $this->input->post('first_name',TRUE):'';
		$last_name = ($this->input->post('last_name',TRUE) && $this->input->post('last_name',TRUE)!='') ? $this->input->post('last_name',TRUE):'';
		$dob=($this->input->post('birthday',TRUE) && $this->input->post('birthday',TRUE)!='') ? $this->input->post('birthday',TRUE):'';
		
		//assign current date and users ip in local variables
		$this->current_date = $this->general->get_gmt_time('time');
		$this->user_ip = $this->general->get_real_ipaddr();
		
		
		//get account using google id, if it is there go for login process
		//check existing user by email, if it there return error message
		//None of above then reguster and go for login process
		
		if($facebook_id){
			//check whether this user is exist or not
			$fbnum = $this->login_module->get_fb_user_num($facebook_id);

			if($fbnum == 1 ){

				$do_login = $this->check_sociallogin_process($facebook_id);
				if($do_login == 'success'){
										
					  if($this->session->userdata(SESSION.'user_id')){					
						  $response['status'] = 'success';	
						  $response['redirect_to'] = $this->general->lang_switch_uri($this->session->userdata(SESSION.'short_code'));
					  }
		
				} else {	
							if($do_login==='unverified')							
								$response['message'] = lang('not_verified');							
							else if($do_login==='suspended')
								$response['message'] = lang('you_suspended');
							else if($do_login==='closed')
								$response['message'] = lang('you_are_deleted');	
							else	
								$response['message'] = lang('inactive_user');	
						}

			} 
			
			else if($this->login_module->check_email() == 1){
				$response['message'] = lang('register_email_in_used');
			}else{
				//registration process
				$this->register_module->insert_social_user('facebook',$facebook_id);	
				$do_login = $this->check_sociallogin_process($facebook_id);
				if($do_login == 'success') {				
					$response['status']='success';				
					$response['redirect_to']=$this->general->lang_switch_uri($this->session->userdata(SESSION.'short_code'));				
				}
			}

		}

		print_r(json_encode($response)); exit;

	}	

	public function google_login()
	{

		//return false if it is not an ajax request
		if(!$this->input->is_ajax_request())
		{
			exit(lang('no_direct_script_allowed'));
		}

		$response = array();	
		$response['status'] = 'error';
		$response['message'] = 'Error occurred please try again later!';
		$response['redirect_to'] = "";
		
		$google_id = ($this->input->post('id',TRUE) && $this->input->post('id',TRUE)!='') ? $this->input->post('id',TRUE):'';
		$first_name = ($this->input->post('first_name',TRUE) && $this->input->post('first_name',TRUE)!='') ? $this->input->post('first_name',TRUE):'';
		$last_name = ($this->input->post('last_name',TRUE) && $this->input->post('last_name',TRUE)!='') ? $this->input->post('last_name',TRUE):'';
		$name = ($this->input->post('name',TRUE) && $this->input->post('name',TRUE)!='') ? $this->input->post('name',TRUE):'';
		$email = ($this->input->post('email',TRUE) && $this->input->post('email',TRUE)!='') ? $this->input->post('email',TRUE):'';
		$picture = ($this->input->post('picture',TRUE) && $this->input->post('picture',TRUE)!='') ? $this->input->post('picture',TRUE):'';
		//assign current date and users ip in local variables
		$this->current_date = $this->general->get_gmt_time('time');
		$this->user_ip = $this->general->get_real_ipaddr();
			
		//get account using google id, if it is there go for login process
		//check existing user by email, if it there return error message
		//None of above then reguster and go for login process
		
		//check whether this user is exist or not
		$user_nums = $this->login_module->get_google_user_num($google_id);
		if($user_nums == 1){
			$do_login = $this->check_sociallogin_process($google_id);	
			if($do_login == 'success'){
									
				  if($this->session->userdata(SESSION.'user_id')){					
					  $response['status'] = 'success';	
					  $response['redirect_to'] = $this->general->lang_switch_uri($this->session->userdata(SESSION.'short_code'));
				  }
	
			} else {	
						if($do_login==='unverified')							
							$response['message'] = lang('not_verified');							
						else if($do_login==='suspended')
							$response['message'] = lang('you_suspended');
						else if($do_login==='closed')
							$response['message'] = lang('you_are_deleted');	
						else	
							$response['message'] = lang('inactive_user');	
					}
		}
		else if($this->login_module->check_email() == 1){
			$response['message'] = lang('register_email_in_used');
		}else{
			//registration process
			$this->register_module->insert_social_user('google',$google_id);
			$do_login = $this->check_sociallogin_process($google_id);
			if($do_login == 'success') {				
				$response['status']='success';				
				$response['redirect_to']=$this->general->lang_switch_uri($this->session->userdata(SESSION.'short_code'));				
			}
		}

		print_r(json_encode($response)); exit;

}

public function check_sociallogin_process($social_id)

{

		//get member info based on login value

		$options = array('social_id' => $social_id); //'facebook_id' = $facebook_id

		$query = $this->db->get_where('members',$options);

		if($query->num_rows()>0)

		{

			$record = $query->row_array();

			//checl active user

			if($record['status']=='active' && $record['social_id'] > 0)

			{

				$user_ip = $this->general->get_real_ipaddr();

					//check blocked IP

				if($this->general->check_block_ip($user_ip)==0)

				{

					$current_date = $this->general->get_gmt_time('time');

					$lang_information = $this->general->get_country_info_byid($record['country']);

					$this->session->set_userdata(array(SESSION.'country_flag' => $lang_information['country_flag']));

					$this->session->set_userdata(array(SESSION.'short_code' => $lang_information['short_code']));

					//$update_data = array('last_login_date'=>$current_date,'last_ip_address'=>$user_ip);

                                         $token_c=@$_SESSION['token'];

					$update_data = array('last_login_date'=>$current_date,'last_ip_address'=>$user_ip,'mem_login_state'=>'1','mem_last_activated' => $current_date,'token'=>$token_c);

					$this->db->where('id',$record['id']);

					$this->db->update('members',$update_data);
					
					//Insert User IP Address
						$insert_data = array('user_id'=>$record['id'], 'date' => $current_date, 'ip_address' => $user_ip);                       
                        $this->db->insert('members_ip', $insert_data);

					$this->session->set_userdata(array(SESSION.'user_id' => $record['id']));

					$this->session->set_userdata(array(SESSION.'first_name' => $record['first_name']));

					$this->session->set_userdata(array(SESSION.'email' => $record['email']));

					$this->session->set_userdata(array(SESSION.'last_name' => $record['last_name']));

					$this->session->set_userdata(array(SESSION.'username' => $record['user_name']));

					$this->session->set_userdata(array(SESSION.'balance' => $record['balance']));

					$this->session->set_userdata(array(SESSION.'country_id' => $record['country']));

					$this->session->set_userdata(array(SESSION.'terms' => $record['terms_condition']));
					
					$this->session->set_userdata(array(SESSION.'social_id' => $record['social_id']));
					
					$this->session->set_userdata(array(SESSION.'reg_type' => $record['reg_type']));	
					$this->session->set_userdata(array(SESSION.'gender' => $record['gender']));	

					$this->session->set_userdata(array('last_login' => $this->general->date_time_formate($record['last_login_date'])));

					$this->session->set_userdata(array(SESSION.'login_state' => $record['mem_login_state']));

					//update user id in member device token
					$this->login_module->check_update_device_user_id($record['id']);

					return 'success';

				}

				else

				{

					return 'blocked_ip';

				}

				

			}

			else if($record['status']=='inactive')

			{

				return 'inactive';

			}

			else if($record['status']=='suspended')

			{

				return 'suspended';

			}

			else if($record['status']=='close')

			{

				return 'closed';

			}

		}

		

	}

	public function twitter_verify_and_return_result(){

		//Get existing token and token secret from session

		$sessToken = $this->session->userdata('token');

		$sessTokenSecret = $this->session->userdata('token_secret');

		$consumerKey = TWITTER_APP_KEY;

		$consumerSecret = TWITTER_APP_SECRET;

		//Get status and user info from session

		$sessStatus = $this->session->userdata('status');

		$sessUserData = $this->session->userdata('userData');

		

		

		if((isset($_REQUEST['oauth_token']) && $sessToken == $_REQUEST['oauth_token'])){

			

			$connection = new TwitterOAuth($consumerKey, $consumerSecret, $sessToken, $sessTokenSecret);

			

			$accessToken = $connection->getAccessToken($_REQUEST['oauth_verifier']);

		

			if($connection->http_code == '200'){

				

			    return  $connection->get('account/verify_credentials');

			}

			else{

				$data['error_msg'] = lang('some_problem_occur');

				echo $data['error_msg'];exit;

			}

			

		}

		

	}

	public function twitter_login()

	{

				$user=$this->twitter_verify_and_return_result();

				$tnum = $this->login_module->get_twitter_user_num($user->id_str);

				if($tnum == '1' ){

					$do_login = $this->check_sociallogin_process($user->id_str);



					if($do_login === 'success') 

					{

						if($this->session->userdata(SESSION.'user_id'))

						{

							redirect($this->general->lang_uri());

						}

					}			

					else 

					{

						if($do_login==='unverified')

							{$this->session->set_flashdata('message',lang('not_verified'));}

						else if($do_login==='suspended')

							{$this->session->set_flashdata('message',lang('you_suspended'));}

						else if($do_login==='close')

							{$this->session->set_flashdata('message',lang('you_are_deleted'));}

						else if($do_login==='invalid')

							{$this->session->set_flashdata('message',lang('invalid_username'));}

						else

						{

							$this->session->set_flashdata('message', lang('inactive_user'));

							$return_data['status']='failed';

							$return_data['url']= $this->general->lang_uri('/users/register');

						}

						//$return_data['status']='failed';

						$return_data['url']= $this->general->lang_uri('/users/login');

						redirect($return_data['url']);exit;

					}

				} else if($tnum=='0'){

					redirect($this->general->lang_uri('/users/login/twitter_signup/'.$user->id_str.'/'.$user->name));exit;

				   

				}

	}

	public function twitter_signup($twitter_id,$user){

		$this->form_validation->set_rules($this->login_module->twitter_signup_validation);

		if($this->form_validation->run()==true){

			$this->register_module->insert_twitter_user();	

			$do_login = $this->check_sociallogin_process($this->input->post('twi_id'));

			if($do_login === 'success') 

			{

				if($this->session->userdata(SESSION.'user_id'))

				{

					redirect($this->general->lang_uri());

				}

			}

		}

		$this->data['twi_user']=$user;

		$this->data['twitter_id']  = $twitter_id;

		$seo_data=$this->general->get_seo(LANG_ID, 2);

		if($seo_data)

		{

			//set SEO data

			$this->page_title = $seo_data->page_title.' | '.SITE_NAME;

			$this->data['meta_keys']= $seo_data->meta_key;

			$this->data['meta_desc']= $seo_data->meta_description;

		}

		else

		{

			//set SEO data

			$this->page_title = SITE_NAME;

			$this->data['meta_keys']= SITE_NAME;

			$this->data['meta_desc']= SITE_NAME;

		}

		$this->template

		->set_layout('body_full')

		->enable_parser(FALSE)

		->title($this->page_title)			

		->build('twitter_signup', $this->data);

	}
	
	public function token() {
		
		if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
        $device_id = $this->input->post('device_id', true);
		$token = $this->input->post('token', true);
		
		if($device_id=="" || $token ==""){
			exit('The device and token is required.');
		}
		
		$device_data = $this->login_module->get_device_details($device_id);
		
		if($device_data){
			//echo $device_data->token."!=".$token;
			if($device_data->token!=$token){
				//update device tokent
				$this->login_module->update_device_tokent();
				echo "UpdateSuccess";
			}
		}else{
			//insert device tokent
			if($this->login_module->insert_device_tokent())
				echo "InsertSuccess";
		}
		
		//echo "Error";
    }

}

/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */