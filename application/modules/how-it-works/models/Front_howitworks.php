<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_howitworks extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
	
	
	public function get_how_it_works()
	{		
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
				// $this->db->order_by("last_update", "desc"); 
		$query = $this->db->get_where('how_it_works',array("lang_id"=>$lang_id,"is_display"=>"Yes"));

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	
}
