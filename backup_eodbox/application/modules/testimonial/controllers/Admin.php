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
			$this->load->library('image_lib');
			
		//load custom module
			$this->load->model('admin_testimonial');
		
		//load helper
		$this->load->helper('file');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	public function index()
	{
		$this->data['details_data'] = $this->admin_testimonial->get_details_admin();
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Testimonial Management | '. SITE_NAME)
			->build('admin_testimonial_view', $this->data);	
		
	}
	
	public function add()
	{
		$this->data['jobs'] = 'Add';
		
		
			//print_r($_FILES);

		// Set the validation rules
		$this->form_validation->set_rules($this->admin_testimonial->validate_settings);		
		
		//make file settins and do upload it
		$this->admin_testimonial->file_settings_do_upload();
		$image_data = $this->upload->data();			
		
		$img_full_path = $image_data['file_name'];
			
		if($this->form_validation->run()==TRUE && !$this->upload->display_errors())
		{	
			$this->admin_testimonial->resize_image($img_full_path,'thumb_'.$image_data['raw_name'].$image_data['file_ext'],160,100);
			//Insert Lang Settings
			$this->admin_testimonial->insert($img_full_path);
			$this->session->set_flashdata('message','The testimonial insert successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/testimonial/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Add Testimonial | '. SITE_NAME)
			->build('admin_testimonial_add', $this->data);	
	}
	
	
	public function edit($id)
	{
		$this->data['jobs'] = 'Edit';
		
		//check lang id, if it is not set then redirect to view page
		if(!isset($id)){redirect(ADMIN_DASHBOARD_PATH.'/testimonial/index','refresh');exit;}
		
		$this->data['data'] = $this->admin_testimonial->get_details_by_id($id);
		
		//check lang data, if it is not set then redirect to view page
		if($this->data['data'] == false){redirect(ADMIN_DASHBOARD_PATH.'/testimonial/index','refresh');exit;}
		
			//print_r($_FILES);

		// Set the validation rules
		$this->form_validation->set_rules($this->admin_testimonial->validate_settings);		
		
		//make file settins and do upload it
		if(isset($_FILES['img']['tmp_name']) && $_FILES['img']['tmp_name']!='')
		{
			$this->admin_testimonial->file_settings_do_upload();
			$image_data = $this->upload->data();			
			$img_full_path = $image_data['file_name'];
			
			$file_error = $this->upload->display_errors();
			if(!$file_error)
			{$this->admin_testimonial->resize_image($img_full_path,'thumb_'.$image_data['raw_name'].$image_data['file_ext'],160,100);}
		}
		else
			{
				$file_error = '';
				$img_full_path = '';
			}
			
		
		if($this->form_validation->run()==TRUE && !$file_error)
		{	
			//Insert Lang Settings
			$this->admin_testimonial->update($id,$img_full_path);
			$this->session->set_flashdata('message','The testimonial update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/testimonial/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit Testimonial | '. SITE_NAME)
			->build('admin_testimonial_edit', $this->data);	
	}
	
	public function delete($id)
	{
		
			$query = $this->db->get_where('testimonial', array('id' => $id));

			if($query->num_rows() > 0) 
			{
				$this->db->delete('testimonial', array('id' => $id));
				$row_data = $query->row();	
				@unlink('./'.$row_data->image);
				$this->session->set_flashdata('message','Testimonial record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/testimonial/index','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','No record found in our database.');
					redirect(ADMIN_DASHBOARD_PATH.'/testimonial/index','refresh');
					exit;
				}

	}
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */