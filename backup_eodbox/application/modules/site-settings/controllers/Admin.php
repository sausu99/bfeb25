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
			$this->load->model('Admin_site_settings');
			$this->load->library('upload');
			$this->load->library('image_lib');

		
	}
	
	public function index()
	{
		//Changing the Error Delimiters
		$this->data['jobs'] = 'Edit';
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		// Set the validation rules
		$this->form_validation->set_rules($this->Admin_site_settings->validate_site_settings);
		$upload_result = $this->Admin_site_settings->upload_auction_images($this->data['jobs']);
		if($this->input->post('site_status')=='maintanance')
		{
			$this->form_validation->set_rules('maintainance_key','maintainance_key','required');
		}
		
		if($this->form_validation->run()==TRUE && $upload_result == FALSE )
		{
			//update site setting
			$this->Admin_site_settings->update_site_settings();
			$this->session->set_flashdata('message','The site settings records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/site-settings/index','refresh');
			exit;
		}
		
		$this->data['site_set'] = $this->Admin_site_settings->get_site_setting();
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Site Settings | '.SITE_NAME)
			->build('site_settings', $this->data);	
		
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */