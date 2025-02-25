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
			$this->load->model('admin_block_ip');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	public function index()
	{
		$this->data['ip_data'] = $this->admin_block_ip->get_ip_details();
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Block IP View | '. SITE_NAME)
			->build('ip_view', $this->data);	
		
	}
	
	public function add_ip()
	{
		$this->data['jobs'] = 'Add';
		
		
			//print_r($_FILES);

		// Set the validation rules
		$this->form_validation->set_rules('ip_address', 'IP Address', 'required');
		$this->form_validation->set_rules('message', 'Message', 'required');
			
		if($this->form_validation->run()==TRUE)
		{			
			//Insert Lang Settings
			$this->admin_block_ip->insert_ip_record();
			$this->session->set_flashdata('message','The IP Address is inserted successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/block-ip/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Add IP Address | '. SITE_NAME)
			->build('ip_add', $this->data);	
	}
	
	
	public function edit_ip($id)
	{
		$this->data['jobs'] = 'Edit';
		
		//check lang id, if it is not set then redirect to view page
		if(!isset($id)){redirect(ADMIN_DASHBOARD_PATH.'/block-ip/index','refresh');exit;}
		
		$this->data['data_ip'] = $this->admin_block_ip->get_IP_by_id($id);
		
		//check lang data, if it is not set then redirect to view page
		if($this->data['data_ip'] == false){redirect(ADMIN_DASHBOARD_PATH.'/block-ip/index','refresh');exit;}

		// Set the validation rules
		$this->form_validation->set_rules('ip_address', 'IP Address', 'required');	
		
		if($this->form_validation->run()==TRUE)
		{	
			//Insert Lang Settings
			$this->admin_block_ip->update_ip_record($id);
			$this->session->set_flashdata('message','The Block IP Address records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/block-ip/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit IP Address | '. SITE_NAME)
			->build('ip_edit', $this->data);	
	}
	
	public function delete_ip($id)
	{
			$query = $this->db->get_where('block_ips', array('id' => $id));

			if($query->num_rows() > 0) 
			{
				$this->db->delete('block_ips', array('id' => $id));
				$this->session->set_flashdata('message','The IP Address record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/block-ip/index','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','No record in our database.');
					redirect(ADMIN_DASHBOARD_PATH.'/block-ip/index','refresh');
					exit;
				}

	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */