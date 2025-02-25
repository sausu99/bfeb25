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
			$this->load->library('upload');
			
		//load custom module
			$this->load->model('admin_payment_model');
			 $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');  
			
		
	}
	
	public function index()
	{
		redirect(ADMIN_DASHBOARD_PATH.'/payment/paypal','refresh');
	}
	
	public function paypal()
	{
		$this->form_validation->set_rules($this->admin_payment_model->validate_paypal);
		
		//make file settins and do upload it
		if(isset($_FILES['logo']['tmp_name']) && $_FILES['logo']['tmp_name']!='')
		{
			$this->admin_payment_model->file_settings_do_upload();
			$image_data = $this->upload->data();			
			$logo_full_path = PAYMENT_API_LOGO_PATH.$image_data['file_name'];
			
			$file_error = $this->upload->display_errors();
		}
		else
			{
				$file_error = '';
				$logo_full_path = '';
			}
			
		if($this->form_validation->run()==TRUE && !$file_error)
		{
			//update site setting
			$this->admin_payment_model->update_paypal_settings($logo_full_path);
			$this->session->set_flashdata('message','The PayPal Gateway API update successfull.');
			redirect(ADMIN_DASHBOARD_PATH.'/payment/paypal','refresh');
			exit;
		}
		
		
		$this->data['payment'] = $this->admin_payment_model->get_payment_gateway_info(1);
		//print_r($this->data['payment']);
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('PayPal Management System | '. SITE_NAME)
			->build('admin_paypal', $this->data);
	}
	
	public function instamojo()
	{
		$this->form_validation->set_rules($this->admin_payment_model->validate_instamojo);
		
		//make file settins and do upload it
		if(isset($_FILES['logo']['tmp_name']) && $_FILES['logo']['tmp_name']!='')
		{
			$this->admin_payment_model->file_settings_do_upload();
			$image_data = $this->upload->data();			
			$logo_full_path = PAYMENT_API_LOGO_PATH.$image_data['file_name'];
			
			$file_error = $this->upload->display_errors();
		}
		else
			{
				$file_error = '';
				$logo_full_path = '';
			}
			
		if($this->form_validation->run()==TRUE && !$file_error)
		{
			//update site setting
			$this->admin_payment_model->update_instamojo_settings($logo_full_path);
			$this->session->set_flashdata('message','The Instamojo Payment API update successfull.');
			redirect(ADMIN_DASHBOARD_PATH.'/payment/instamojo','refresh');
			exit;
		}
		
		
		$this->data['payment'] = $this->admin_payment_model->get_payment_gateway_info(2);
		//print_r($this->data['payment']);
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Instamojo Management System | '.SITE_NAME)
			->build('admin_instamojo', $this->data);
	}
	public function ccavenue()
	{
		$this->form_validation->set_rules($this->admin_payment_model->validate_ccavenue);
		
		//make file settins and do upload it
		if(isset($_FILES['logo']['tmp_name']) && $_FILES['logo']['tmp_name']!='')
		{
			$this->admin_payment_model->file_settings_do_upload();
			$image_data = $this->upload->data();			
			$logo_full_path = PAYMENT_API_LOGO_PATH.$image_data['file_name'];
			
			$file_error = $this->upload->display_errors();
		}
		else
			{
				$file_error = '';
				$logo_full_path = '';
			}
			
		if($this->form_validation->run()==TRUE && !$file_error)
		{
			//update site setting
			$this->admin_payment_model->update_ccavenue_settings($logo_full_path);
			$this->session->set_flashdata('message','The ccAvenue Payment API update successfull.');
			redirect(ADMIN_DASHBOARD_PATH.'/payment/ccavenue','refresh');
			exit;
		}
		
		
		$this->data['payment'] = $this->admin_payment_model->get_payment_gateway_info(3);
		//print_r($this->data['payment']);
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('CCavenue Management System | '.SITE_NAME)
			->build('admin_ccavenue', $this->data);
	}
        public function paytm()
	{
		$this->form_validation->set_rules($this->admin_payment_model->validate_paytm);
		
		//make file settins and do upload it
		if(isset($_FILES['logo']['tmp_name']) && $_FILES['logo']['tmp_name']!='')
		{
			$this->admin_payment_model->file_settings_do_upload();
			$image_data = $this->upload->data();			
			$logo_full_path = PAYMENT_API_LOGO_PATH.$image_data['file_name'];
			
			$file_error = $this->upload->display_errors();
		}
		else
			{
				$file_error = '';
				$logo_full_path = '';
			}
			
		if($this->form_validation->run()==TRUE && !$file_error)
		{
			//update site setting
			$this->admin_payment_model->update_paytm_settings($logo_full_path);
			$this->session->set_flashdata('message','The ccAvenue Payment API update successfull.');
			redirect(ADMIN_DASHBOARD_PATH.'/payment/paytm','refresh');
			exit;
		}
		
		
		$this->data['payment'] = $this->admin_payment_model->get_payment_gateway_info(4);
		//print_r($this->data['payment']);
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Paytm Management System| '.SITE_NAME)
			->build('admin_paytm', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */