<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_time_zone_settings extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
	
	public function get_all_gmt()
	{
		$data=array();		
		$query = $this->db->get('time_zone_setting');
		if ($query->num_rows() > 0) 
		{
			$data=$query->result_array();				
		}		
		$query->free_result();
		return $data;
	}
	
	public function update_gmt_setting()
	{
		//set GMT off for all setting
		$data=array('status'=>"off");		
		$this->db->update('time_zone_setting',$data);
		
		$data = array('status' => 'on');
		$this->db->where('id',$this->input->post('gmt_time'));
		$this->db->update('time_zone_setting', $data); 

	}
	
}
