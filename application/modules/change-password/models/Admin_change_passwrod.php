<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_change_passwrod extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}		
		
		
	public function update_admin_password()
	{
		$data = array('password' => md5($this->input->post('new_password', TRUE)));
		
		$this->db->where('id',$this->session->userdata(ADMIN_LOGIN_ID));
		$this->db->update('admin_users', $data); 

	}
	
	

}
