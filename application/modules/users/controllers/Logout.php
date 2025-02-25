<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		if(!$this->session->userdata(SESSION.'user_id'))
         {
          	redirect(site_url(''),'refresh');exit;
         }
		
		//load custom library
		$this->load->library('my_language');
	}
	
	public function index()
	{
		//load custom library
		if($this->session->userdata(SESSION.'login_state')!="")
		{			
			$update_data = array('mem_login_state'=>'0');
			$this->db->where('id',$this->session->userdata(SESSION.'user_id'));
			$this->db->update('members',$update_data);			
		}
		
		$this->session->unset_userdata(SESSION.'user_id');
		$this->session->unset_userdata(SESSION.'first_name');
		$this->session->unset_userdata(SESSION.'email');
		$this->session->unset_userdata(SESSION.'last_name');
		$this->session->unset_userdata(SESSION.'username');
		$this->session->unset_userdata(SESSION.'balance');
		$this->session->unset_userdata(SESSION.'last_login');
		$this->session->unset_userdata(SESSION.'lang_flag');
		$this->session->unset_userdata(SESSION.'short_code');
		$this->session->unset_userdata(SESSION.'lang_id');
		$this->session->unset_userdata(SESSION.'sub_domain');
		$this->session->unset_userdata(SESSION.'terms');
		$this->session->unset_userdata(SESSION.'social_id');
		//$this->session->sess_destroy();
		
		if($this->input->get('job') == "cancel_account")
			$this->session->set_flashdata('message', lang('acc_cancel_msg'));
		
					
		if($this->session->userdata('is_fb_user') == 'Yes')
		{
			//FB Logout
			$this->load->library('Facebook',$this->config->item('facebook'));
			
			$args['next'] = site_url('');
			$logoutUrl = $this->facebook->getLogoutUrl($args);
			$this->facebook->destroySession();
			
			//unset fb session
			$this->session->unset_userdata('is_fb_user');
			
			//set fb logout session for js
			$this->session->set_userdata('fb_logout', 'Yes');
			
			redirect($logoutUrl,'refresh');			
		}
		else
		{
			redirect($this->general->lang_uri('/users/login'),'refresh');	
			//redirect(site_url(''),'refresh');
		}
		
		exit;
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */