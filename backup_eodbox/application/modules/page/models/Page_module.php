<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class page_module extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
		public $validate_contact_us =  array(
			
			array('field' => 'name', 'label' => 'lang:name', 'rules' => 'trim|required'),
			array('field' => 'email', 'label' => 'lang:email', 'rules' => 'required|valid_email'),			
		   // array('field' => 'address', 'label' => 'Address', 'rules' => 'required'),			
            array('field' => 'subject', 'label' => 'lang:subject', 'rules' => 'required'),			
		    array('field' => 'message', 'label' => 'lang:message', 'rules' => 'trim|required'),
			
			
		);
	
	public function get_cms($slug)
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$data = array();
		$query = $this->db->get_where("cms",array("cms_slug"=>$slug,"lang_id"=>$lang_id,"is_display"=>"Yes"));
		if ($query->num_rows() > 0) 
		{
			$data=$query->row();				
		}	
		else
			{
				$query = $this->db->get_where("cms",array("cms_slug"=>$slug,"lang_id"=>DEFAULT_LANG_ID));
				if ($query->num_rows() > 0) 
				{
					$data=$query->row();				
				}
			}	
		$query->free_result();
		return $data;
	}

}
