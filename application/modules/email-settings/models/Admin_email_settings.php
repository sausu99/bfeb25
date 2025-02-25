<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_email_settings extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
	
	public $validate_settings =  array(
			array('field' => 'lang', 'label' => 'Language', 'rules' => 'required'),
			array('field' => 'subject', 'label' => 'Subject', 'rules' => 'required'),
			array('field' => 'sms_body', 'label' => 'SMS Body', 'rules' => 'trim|required'),
			array('field' => 'is_email_notification_send', 'label' => 'send email notification', 'rules' => 'required'),
			array('field' => 'is_sms_notification_send', 'label' => 'send sms notification', 'rules' => 'required'),
			array('field' => 'content', 'label' => 'Email Body', 'rules' => 'required')
			);
	
	public function get_all_email_template($lang_id)
	{		
		$query = $this->db->get_where('email_settings',array("lang_id"=>$lang_id));

		if ($query->num_rows() > 0)
		{
		   return $query->result_array();
		} 

		return false;
	}
		
		
	public function get_email_setting_byemailcode($emailcode,$lang_id)
	{		
		$query = $this->db->get_where('email_settings',array("email_code "=>$emailcode,"lang_id"=>$lang_id));

		if ($query->num_rows() > 0)
		{
		   return $query->row_array();
		} 

		return false;
	}
	
	public function get_email_setting_byid($id,$lang_id)
	{		
		$query = $this->db->get_where('email_settings',array("id "=>$id,"lang_id"=>$lang_id));

		if ($query->num_rows() > 0)
		{
		   return $query->row_array();
		} 

		return false;
	}
	
	public function update_email_settings()
	{
		$data = array(
               'lang_id' => $this->input->post('lang', TRUE),
               'subject' => $this->input->post('subject', TRUE),
			   'sms_body' => $this->input->post('sms_body', TRUE),
			   'push_message_body' => $this->input->post('push_message_body',TRUE),
			   'is_email_notification_send' => $this->input->post('is_email_notification_send', TRUE),
			   'is_sms_notification_send' => $this->input->post('is_sms_notification_send', TRUE),
			   'is_push_notification_send' => $this->input->post('is_push_notification_send', TRUE),               
			   'email_body' => $this->input->post('content'),			  
			   'last_update' => $this->general->get_local_time('time')
            );
		//print_r($data);exit;
		$email_code = $this->uri->segment(4);
		$lang_id = $this->input->post('lang');
		
		//check this data is exist or not, if it is not exist then insert into table otherwise update it
		$query = $this->db->get_where('email_settings',array("email_code"=>$email_code,"lang_id"=>$lang_id));
		
		if ($query->num_rows() > 0)
		{
			$this->db->where('email_code', $email_code);
			$this->db->where('lang_id', $lang_id);
			$this->db->update('email_settings', $data); 
		}
		else
			{
				$data['email_code'] = $email_code;
				$this->db->insert('email_settings', $data); 
			}
		

	}
	
	

}
