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
			$this->load->model('admin_bidpackage');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	public function index()
	{
		$this->data['bp_data'] = $this->admin_bidpackage->get_bid_package();
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Bid Package View | '. SITE_NAME)
			->build('bidpackage_view', $this->data);	
		
	}
	
	public function add_bidpackage()
	{
		$this->data['jobs'] = 'Add';
		
		// Set the validation rules
		$this->form_validation->set_rules($this->admin_bidpackage->validate_settings);
			
		if($this->form_validation->run()==TRUE)
		{			
			//Insert Lang Settings
			$this->admin_bidpackage->insert_bidpackage_record();
			$this->session->set_flashdata('message','The Bid Package is inserted successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/bidpackage/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Add Bid Package | '.SITE_NAME)
			->build('bidpackage_add', $this->data);	
	}
	
	
	public function edit_bidpackage($id)
	{
		$this->data['jobs'] = 'Edit';
		
		//check lang id, if it is not set then redirect to view page
		if(!isset($id)){redirect(ADMIN_DASHBOARD_PATH.'/bidpackage/index','refresh');exit;}
		
		$this->data['data'] = $this->admin_bidpackage->get_bidpackage_by_id($id);
		
		//check lang data, if it is not set then redirect to view page
		if($this->data['data']==false){redirect(ADMIN_DASHBOARD_PATH.'/bidpackage/index','refresh');exit;}

		// Set the validation rules
		$this->form_validation->set_rules($this->admin_bidpackage->validate_settings);
		
		if($this->form_validation->run()==TRUE)
		{	
			//Insert Lang Settings
			$this->admin_bidpackage->update_bidpackage_record($id);
			$this->session->set_flashdata('message','The Bid Package records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/bidpackage/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit bid pack | '.SITE_NAME)
			->build('bidpackage_edit', $this->data);	
	}
	
	public function delete_bidpackage($id)
	{
			$query = $this->db->get_where('bidpackage', array('id' => $id));

			if($query->num_rows() > 0) 
			{
				$this->db->delete('bidpackage', array('id' => $id));
				$this->session->set_flashdata('message','The Bid Package record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/bidpackage/index','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','No record in our database.');
					redirect(ADMIN_DASHBOARD_PATH.'/bidpackage/index','refresh');
					exit;
				}

	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */