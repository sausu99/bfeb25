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
			$this->load->model('admin_vouchers');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	public function index()
	{
		$this->data['result_data'] = $this->admin_vouchers->get_all_vouchers();
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Vouchers View | '. SITE_NAME)
			->build('admin_vouchers_view', $this->data);	
		
	}
	
	public function add()
	{
		$this->data['jobs'] = 'Add';
		
		// Set the validation rules
		$this->form_validation->set_rules($this->admin_vouchers->validate_settings);
			
		if($this->form_validation->run()==TRUE)
		{			
			//Insert Lang Settings
			$this->admin_vouchers->insert_record();
			$this->session->set_flashdata('message','The Vouchers is inserted successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/vouchers/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Add Vouchers | '. SITE_NAME)
			->build('admin_vouchers_add', $this->data);	
	}
	
	
	public function edit($id)
	{
		$this->data['jobs'] = 'Edit';
		
		//check lang id, if it is not set then redirect to view page
		if(!isset($id)){redirect(ADMIN_DASHBOARD_PATH.'/vouchers/index','refresh');exit;}
		
		$this->data['data'] = $this->admin_vouchers->get_vouchers_by_id($id);
		
		//check lang data, if it is not set then redirect to view page
		if($this->data['data']==false){redirect(ADMIN_DASHBOARD_PATH.'/vouchers/index','refresh');exit;}

		// Set the validation rules
		$this->form_validation->set_rules($this->admin_vouchers->validate_settings_end);
		
		if($this->form_validation->run()==TRUE)
		{	
			//Insert Lang Settings
			$this->admin_vouchers->update_record($id);
			$this->session->set_flashdata('message','The Vouchars records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/vouchers/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Vouchars Update | '. SITE_NAME)
			->build('admin_vouchers_edit', $this->data);	
	}
	
	public function delete($id)
	{
			$query = $this->db->get_where('vouchers', array('id' => $id));

			if($query->num_rows() > 0) 
			{
				$this->db->delete('vouchers', array('id' => $id));
				$this->session->set_flashdata('message','The Vouchers record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/vouchers/index','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','No record in our database.');
					redirect(ADMIN_DASHBOARD_PATH.'/vouchers/index','refresh');
					exit;
				}

	}
	
	function check_end_date()
	{
		if($this->input->post('end_date') <= $this->general->get_local_time('none'))
		{
			$this->form_validation->set_message('check_end_date',"The %s must be greater than current date and time.");
			return false;
			
		}
		else if($this->input->post('end_date') <= $this->input->post('start_date'))
		{
			$this->form_validation->set_message('check_end_date',"The %s must be greater than start date.");
			return false;
		}
		
		return true;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */