<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_help extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
			
	}
	
	public function get_help_category()
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$data = array();
		$query = $this->db->get_where("help_category",array("lang_id"=>$lang_id,"is_display"=>"Yes"));
		if ($query->num_rows() > 0) 
		{
			$data=$query->result();				
		}	
		else
			{
				$query = $this->db->get_where("help_category",array("lang_id"=>DEFAULT_LANG_ID));
				if ($query->num_rows() > 0) 
				{
					$data=$query->result();				
				}	
			}	
		$query->free_result();
		return $data;
	}
	
	public function get_help_bycatid($help_cat_id)
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$data = array();
		$query = $this->db->get_where("help",array("help_cat_id"=>$help_cat_id,"lang_id"=>$lang_id,"is_display"=>"Yes"));
		if ($query->num_rows() > 0) 
		{
			$data=$query->result();				
		}	
		else
			{
				$query = $this->db->get_where("help",array("help_cat_id"=>$help_cat_id,"lang_id"=>DEFAULT_LANG_ID));
				if ($query->num_rows() > 0) 
				{
					$data=$query->result();				
				}	
			}	
		$query->free_result();
		return $data;
	}
	

}
