<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_login extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
	
	public function admin_login()
	{		
		$login = $this->check_admin_login();
		if($login == 'success'){
			
			return true;
		}
		else {
			return false;
		}
		
	}
	
	/* admin login */
	private function check_admin_login() 
	{
			$uname = $this->input->post('username');
			
			$pass = md5($this->input->post('password'));
			
			
			$query = $this->db->select('*')
					  ->from('admin_users')					  
					  ->where('user_name',$uname)
					  ->where('password',$pass)					 
					  ->limit(1)
					  ->get();
			
			if($query->num_rows() == 1) 
			{
					$result = $query->row();
					
					if($result->user_name===$this->input->post('username') && $result->password===md5($this->input->post('password')))
					{
							//update admin last login date & time	
							$this->update_last_login($result->id);
							//store admin valuse is session
							$this->session->set_userdata(ADMIN_LOGIN_ID,$result->id); 
							return 'success';
					}
					else 
					{
						return 'failed';
					}
			}
			else
			{
				return 'failed';
			}
			
	}
	
	// update admin last login
	public function update_last_login($id)
	{
		$ip_addr = $this->general->get_real_ipaddr();
	    $this->db->update('admin_users', array('last_login' => date('Y-m-d H:i:s'),'ip_addr' =>$ip_addr ), array('id' => $id));
	    return $this->db->affected_rows() == 1;
	}

}
