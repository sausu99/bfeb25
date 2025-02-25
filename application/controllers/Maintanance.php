<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintanance extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		
		//load custom library
		$this->load->library('my_language');
		if(SITE_STATUS == 'online')
		{
			redirect($this->general->lang_uri(''));exit;
		}
		
		else if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));exit;
		}
	}
	
	public function index()
	{
		// $cms_id = '12';
		$this->data['maintainance_msg'] = $this->get_cms(27);
		//print_r($this->data['offline_msg']);
		
		if($this->input->server('REQUEST_METHOD')=='POST' && $this->input->post('key',TRUE))
		{
			//check whether key is matched with key or not
			$maintainance_key = $this->input->post('key',TRUE);
			
			$this->db->select('maintainance_key');
			$query = $this->db->get("site_settings");
			if($query->num_rows() > 0) 
			{
				$data=$query->row();
				
				if($maintainance_key===$data->maintainance_key)
				{
					//set session for maintainance
					$this->session->set_userdata('MAINTAINANCE_KEY','YES');
					//echo $this->session->userdata('MAINTAINANCE_KEY');exit;
					redirect(site_url(),'refresh'); exit;
				}
			}
		}
		//set SEO data
		$this->page_title = isset($this->data['offline_msg']->page_title)? $this->data['offline_msg']->page_title : SITE_NAME;
		$this->data['meta_keys'] = isset($this->data['offline_msg']->meta_keys)? $this->data['offline_msg']->meta_keys : SITE_NAME;
		$this->data['meta_desc'] = isset($this->data['offline_msg']->meta_desc)? $this->data['offline_msg']->meta_desc : SITE_NAME;
		
		$this->load->view('maintainance',$this->data);
	}

	public function get_cms($parent_id)
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$data = array();
		$query = $this->db->get_where("cms",array("parent_id"=>$parent_id,"lang_id"=>$lang_id,"is_display"=>"Yes"));
		if ($query->num_rows() > 0) 
		{
			$data=$query->row();				
		}	
		else
			{
				$query = $this->db->get_where("cms",array("parent_id"=>$parent_id,"lang_id"=>DEFAULT_LANG_ID));
				if ($query->num_rows() > 0) 
				{
					$data=$query->row();				
				}
			}	
		$query->free_result();
		return $data;
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */