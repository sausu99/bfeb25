<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offline extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		
		//load custom library
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'online')
		{
			redirect($this->general->lang_uri(''));exit;
		}
		
		else if(SITE_STATUS == 'maintanance')
		{
			redirect($this->general->lang_uri('/maintanance'));exit;
		}
		
		
		
	}
	
	public function index()
	{
		$this->data = array();
		$this->data['offline_msg'] = $this->get_cms(26);
		
		//set SEO data
		$this->page_title = isset($this->data['offline_msg']->page_title)? $this->data['offline_msg']->page_title : SITE_NAME;
		$this->data['meta_keys'] = isset($this->data['offline_msg']->meta_keys)? $this->data['offline_msg']->meta_keys : SITE_NAME;
		$this->data['meta_desc'] = isset($this->data['offline_msg']->meta_desc)? $this->data['offline_msg']->meta_desc : SITE_NAME;
		
		$this->load->view('offline',$this->data);
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