<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activation extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
		$this->load->helper('text');
				
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'),'refresh');exit;
		}
		
		
		 //check banned IP address
		$this->general->check_banned_ip();
				
	}
	
	public function index()
	{
		redirect($this->general->lang_uri(),'refresh');exit;
	}
	
	public function email_activation($user_id='',$encrypt='')
    {
		$this->data = array();		
					
		//check blank parameter variable
		if($user_id == '' || $encrypt == '')
		{
			redirect($this->general->lang_uri(''), 'refresh');exit;
		}
		
		//check num row into user table and if blank then redirect to home page.
		$query = $this->db->get_where('members',array("id"=>$user_id));
		if ($query->num_rows() == 1)
		{
		   $user_info = $query->row_array();
		   //print_r($user_info['id']);exit;
		   
		   //make encryption combination based with user info and check it with URL encrypted value
		   $db_encrypt = sha1(base64_encode($user_info['id']."&&".$user_info['email']."&&".$user_info['new_email']));
		   if($db_encrypt === $encrypt)
		   {
		   		$data=array('email'=>$user_info['new_email']);
                $this->db->where('id',$user_info['id']);
                $this->db->update('members',$data);
				
				//Email activation success
				$this->data['cms'] = $this->general->get_cms_pid(7);
				//print_r($this->data['cms']);exit;
				$this->page_title = $this->data['cms']->page_title.' | '.SITE_NAME;
				$this->data['meta_keys']= $this->data['cms']->meta_key;
				$this->data['meta_desc']= $this->data['cms']->meta_description;
		   }
		   else
		   		{
					//Email activation failuer
					//Email activation success
					$this->data['cms'] = $this->general->get_cms_pid(8);
					//print_r($this->data['cms']);exit;
					$this->page_title = $this->data['cms']->page_title.' | '.SITE_NAME;
					$this->data['meta_keys']= $this->data['cms']->meta_key;
					$this->data['meta_desc']= $this->data['cms']->meta_description;
				}
				
				
				$this->template
				->set_layout('body_full')
				->enable_parser(FALSE)
				->title($this->page_title)			
				->build('register_status', $this->data);
		   	
		} 
		else
			{
				redirect($this->general->lang_uri(''), 'refresh');exit;
			}
	
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */