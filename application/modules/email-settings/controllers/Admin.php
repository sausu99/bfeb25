<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		// Check if User has logged in
		if (!$this->general->admin_logged_in())			
		{
			redirect(ADMIN_LOGIN_PATH, 'refresh');exit;
		}
			
		//load CI library
			$this->load->library('form_validation');		
		//load custom module
			$this->load->model('admin_email_settings');
			$this->load->model('language-settings/Admin_language_settings');
		
		//load custom helper
			$this->load->helper('editor_helper');
		
	}
	
	public function index($lang_id='')
	{		
		//get lang id if it is set otherwise we take default language
		if($lang_id)
		{
			$this->data['lang_id'] = $lang_id;
		}
		else
			{
				$this->data['lang_id'] = $this->Admin_language_settings->get_default_lang_id();
				if($this->data['lang_id'] == false)
				{
					$this->session->set_flashdata('message','Sorry to say we have not set default language.');
					redirect(ADMIN_DASHBOARD_PATH,'refresh');exit;
				}
			}	
				
		$this->data['email_data'] = $this->admin_email_settings->get_all_email_template($this->data['lang_id']);
		
		//get all language
		$this->data['lang_details'] = $this->Admin_language_settings->get_lang_details_for_others();
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Email Settings Management System | '. SITE_NAME)
			->build('email_view', $this->data);	
		
	}
	
	public function edit($email_code,$lang_id)
	{
		if(!$email_code || !$lang_id)
		{
			//$this->session->set_flashdata('message','Sorry to say we have not set default language.');
			redirect(ADMIN_DASHBOARD_PATH.'/email-settings/index','refresh');exit;
		}
			
		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		//print_r($_POST);
		// Set the validation rules
		$this->form_validation->set_rules($this->admin_email_settings->validate_settings);
		
		if($this->form_validation->run()==TRUE)
		{
			//update site setting
			$this->admin_email_settings->update_email_settings();
			$this->session->set_flashdata('message','The email settins are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/email-settings/edit/'.$email_code.'/'.$lang_id,'refresh');
			exit;
		}
		
		$this->data['email_data'] = $this->admin_email_settings->get_email_setting_byemailcode($email_code,$lang_id);
		$this->data['lang_details'] = $this->Admin_language_settings->get_lang_details_for_others();
		
		$this->data['lang_id'] = $lang_id;
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Email Settings Management System | '. SITE_NAME)
			->build('email_edit', $this->data);		
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */