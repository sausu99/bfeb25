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
			$this->load->model('admin_time_zone_settings');

		
	}
	
	public function index()
	{
		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$this->form_validation->set_rules('gmt_time', 'Time Zone', 'required');

		if($this->form_validation->run()==TRUE)
		{
			$this->admin_time_zone_settings->update_gmt_setting();
			$this->session->set_flashdata('message','Time zone setting updated.');
			redirect(ADMIN_DASHBOARD_PATH.'/time-zone-settings/index','refresh');
			exit;
		}
		
		$this->data['gmt_lists'] = $this->admin_time_zone_settings->get_all_gmt();
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Time Zone Settings | '.SITE_NAME)
			->build('time_zone_settings', $this->data);	
		
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */