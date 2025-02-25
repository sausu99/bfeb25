<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fb_connect extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'),'refresh');exit;
		}
		
		if($this->session->userdata(SESSION.'user_id'))
         {
          	redirect(site_url(''),'refresh');exit;
         }
		
		//load module
		$this->load->model('login_module');
		
		//load CI library
			$this->load->library('form_validation');
			
		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		//load module
		$this->load->model('register_module');
		
	}
	
	public function index()
	{	
		
		$this->load->library('Facebook', $this->config->item('facebook'));
		//$code = $_REQUEST['code'] ? true : false;
		$access = $this->facebook->getAccessToken();
		$signed_request = $this->facebook->getSignedRequest();
        
        $user_id = $this->facebook->getUser();
		
		$profile = null;
        if($user_id > 0)
        {
			
            try 
				{
					$profile = $this->facebook->api('/me','GET');
				  	$fql    =   "select current_location from user where uid=" . $user_id;
          		  	$param  =   array('method'    => 'fql.query', 'query'     => $fql, 'callback'  => '' );
				
				
					//Get user address
					$fqlResult		= $this->facebook->api($param);
					$country		= $fqlResult[0]['current_location']['country'];
					$city			= $fqlResult[0]['current_location']['city'];
					$zip			= $fqlResult[0]['current_location']['zip'];
				
				 	/*$fb_data = array(
                        'me'			 => $profile,
                        'uid'			 => $user_id,
                        'loginUrl'		 => $this->facebook->getLoginUrl(),
                        'logoutUrl'		 => $this->facebook->getLogoutUrl(array('next'=>site_url('/users/logout'))),
						'country' 		 => $country,
						'city'			 => $city,
						'zip'			 => $zip
                    );*/
 			  		
       		  		//$this->session->set_userdata('fb_data', $fb_data);
					$this->session->set_userdata('me', $profile);
					$this->session->set_userdata('country', $country);
			 		$this->session->set_userdata('is_fb_user', 'Yes');
					
					
					//$count_fb_login = $this->session->userdata('count_fb_login');
					//$this->session->set_userdata('count_fb_login', $count_fb_login+1);
					//$this->session->set_userdata('logoutUrl', $this->facebook->getLogoutUrl(array('next'=>site_url('/users/logout'))));
					
					//proceds to FBlogin
			  		$this->fblogin();
		    	} 
				catch (FacebookApiException $e) 
												{
                									error_log($e);
                									$user_id = NULL;
													$this->session->unset_userdata('fb_data');
            									}
        }
		
		redirect($this->general->lang_uri('/users/login'),'refresh');
		exit;		
		
	}
	
	// user FB login 
	public function fblogin() 
	{	
				
		$fb_user_info = $this->session->userdata('me');
		//echo $fb_user_info['me']['location']['id'];
		//print_r($fb_user_info);exit;
		$fbuser = $this->login_module->get_fb_user($fb_user_info['email']);
			
		if($fbuser == 'success')
		{
			$do_login = $this->check_login_process($fb_user_info['email']);
			if($do_login == 'success') 
			{
				if($this->session->userdata(SESSION.'user_id'))
				{
					//Get Language info for this users
					$lang_information = $this->general->get_lang_info($this->session->userdata(SESSION.'lang_id'));
					$this->session->set_userdata(array(SESSION.'lang_flag' => $lang_information['lang_flag']));
					$this->session->set_userdata(array(SESSION.'short_code' => $lang_information['short_code']));
					$this->session->set_userdata('is_fb_user', 'Yes');
					
					//check for terms & conditon
					if($this->session->userdata(SESSION.'terms') != 1)
						redirect($this->general->lang_switch_uri($lang_information['short_code'].'/'.MY_ACCOUNT.'/user/agm'),'refresh');
					else
						redirect($this->general->lang_switch_uri($lang_information['short_code']),'refresh');
					exit;
				}
			
			}			
			else 
				{
					if($do_login==='unverified')
						{$this->session->set_flashdata('message',lang('login_error_unverified'));}
					else if($do_login==='suspended')
						{$this->session->set_flashdata('message',lang('login_error_suspended'));}
					else if($do_login==='close')
						{$this->session->set_flashdata('message',lang('login_error_close'));}
					else if($do_login==='invalid')
						{$this->session->set_flashdata('message',lang('login_error_invalid'));}
					else if($do_login==='sub_domain')
						{$this->session->set_flashdata('message',lang('login_error_sub_domain'));}
					else
						{
							$this->session->set_userdata('fb_signup', "To use your Facebook account to log in, we need some information from you.");
							redirect($this->general->lang_uri('/users/register'), 'refresh');
							exit;
						}
					redirect($this->general->lang_uri('/users/login'),'refresh');
					exit;
				}
			
		}
			
		if($fb_user_info['email'] && $fbuser == 'failed') 
		{
			//print_r($fb_user_info);exit;
			//FB Signup process			
			$this->session->set_userdata('fb_signup', "Hi. We're going to need a few more details from you to successfully sign up using your Facebook account.");			
			redirect($this->general->lang_uri('/users/register'), 'refresh');exit;
			//$this->signup();
		}
		
	}
	
	public function check_login_process($email)
	{
		//get member info based on login value
		$options = array('email'=>$email);
        $query = $this->db->get_where('members',$options);
		//echo $this->db->last_query();exit;
		//check valide login
		if($query->num_rows()>0)
		{
			$record = $query->row_array();
			//checl active user
			if($record['status']==='active' && $this->session->userdata('is_fb_user')=='Yes')
			{
				
					$user_ip = $this->general->get_real_ipaddr();
					//check blocked IP
					if($this->general->check_block_ip($user_ip)===0)
					{
					
						//Get Language info for this users and check it is availabe or not
						$lang_information = $this->general->get_lang_info($record['lang_id']);
						
						if(!$lang_information)
						{
							$this->session->set_userdata(array(SESSION.'sub_domain' => "sub domain issue"));
							return 'sub_domain';exit;
						}
						
							$current_date = $this->general->get_local_time('time');
							
							$update_data = array('last_login_date'=>$current_date,'last_ip_address'=>$user_ip);
                            $this->db->where('id',$record['id']);
                            $this->db->update('members',$update_data);
								
								$this->session->set_userdata(array(SESSION.'user_id' => $record['id']));
                             	$this->session->set_userdata(array(SESSION.'first_name' => $record['first_name']));
							    $this->session->set_userdata(array(SESSION.'email' => $record['email']));
								$this->session->set_userdata(array(SESSION.'last_name' => $record['last_name']));
								$this->session->set_userdata(array(SESSION.'username' => $record['user_name']));
								$this->session->set_userdata(array(SESSION.'balance' => $record['balance']));
								$this->session->set_userdata(array(SESSION.'lang_id' => $record['lang_id']));
								$this->session->set_userdata(array(SESSION.'terms' => $record['terms_condition']));		
                                $this->session->set_userdata(array('last_login' => $this->general->date_time_formate($record['last_login_date'])));
                               // $this->session->set_userdata(array(SESSION.'session_id' => $unique_session_id));
								
								
								if($this->input->post('remember')=='yes')
								{
										$cookie1 = array('name' => SESSION."username",'value'  => $record['user_name'],'expire' => time()+3600*24*30);
										$this->input->set_cookie($cookie1);
										
										$cookie2 = array('name'   => SESSION."password",'value'  => $this->input->post('password'),'expire' => time()+3600*24*30);
										$this->input->set_cookie($cookie2);		
								}
								else
									{
										$cookie1 = array('name'   => SESSION."username",'value'  => '','expire' => 0);
										$this->input->set_cookie($cookie1);		
										
										$cookie2 = array('name'   => SESSION."password",'value'  => '','expire' => 0);
										$this->input->set_cookie($cookie2);
										
									}	
								
								//check bonus expire
								if($record['bonus_points'] > 0)
								{$this->bonus_expire($record['id'], $record['bonus_points']);}
									
								return 'success';
							
					}
					else
						{
							return 'blocked_ip';
						}
				
			}
			else if($record['status']==='inactive')
			{
				return 'unverified';
			}
			else if($record['status']==='close')
			{
				return 'close';
			}
			else if($record['status']==='suspended')
			{
				return 'suspended';
			}
		}
		
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */