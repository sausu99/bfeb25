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
		$this->load->model('admin_country');
			

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	public function index()
	{		
		$this->data['result_data'] = $this->admin_country->get_country_list();
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title(''.SITE_NAME.' - Country View')
			->build('view', $this->data);	
		
	}
	
	public function add_country()
	{
		$this->data['jobs'] = 'Add';
	
		$this->data['error'] = FALSE;
		
		$file_error = true;
		//make file settins and do upload it
		if(isset($_FILES['flag']['tmp_name']) && $_FILES['flag']['tmp_name']!='')
		{
			$this->admin_country->file_settings_do_upload();
			$image_data = $this->upload->data();			
			$flag_full_path = FLAG_PATH.$image_data['file_name'];
			
			$file_error = $this->upload->display_errors();
		}				
		else
		{$this->admin_country->file_settings_do_upload();}
		
		// Set the validation rules
		$this->form_validation->set_rules($this->admin_country->validate_settings);		
		
		if($this->form_validation->run()==TRUE && $this->data['error'] == FALSE && $file_error=='')
		{		
			//Insert Lang Settings
			$this->admin_country->insert_record($flag_full_path);
			$this->session->set_flashdata('message','The Country records are inserted successfully.');
			redirect(ADMIN_DASHBOARD_PATH.'/country/index','refresh');
			exit;			
		}
		
		$this->data['languages'] = $this->general->get_active_languages();	

		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title(''.SITE_NAME.'Add Country')
			->build('add', $this->data);	
	}	
	
	
	public function edit_country($id)
	{
		$this->data['jobs'] = 'Edit';
		
		//check id, if it is not set then redirect to view page
		if(!isset($id))
		{	
			redirect(ADMIN_DASHBOARD_PATH.'/help/index','refresh');
			exit;
		}
		
		$this->data['data_country'] = $this->admin_country->get_country_byid($id);
		
		
		//check data, if it is not set then redirect to view page
		if($this->data['data_country'] ==false)
		{
			redirect(ADMIN_DASHBOARD_PATH.'/country/index','refresh');
			exit;
		}
		
		
		
		//make file settins and do upload it
		if(isset($_FILES['flag']['tmp_name']) && $_FILES['flag']['tmp_name']!='')
		{
			$this->admin_country->file_settings_do_upload();
			$image_data = $this->upload->data();			
			$flag_full_path = FLAG_PATH.$image_data['file_name'];
			
			$file_error = $this->upload->display_errors();
		}
		else
			{
				$file_error = '';
				$flag_full_path = '';
			}
		
		//Set the validation rules
		$this->form_validation->set_rules($this->admin_country->validate_settings_edit);		
		
			
		if($this->form_validation->run()==TRUE && !$file_error)
		{
			//Insert Lang Settings
			$this->admin_country->update_record($id,$flag_full_path);
			$this->session->set_flashdata('message','The Country records are updated successfully.');
			redirect(ADMIN_DASHBOARD_PATH.'/country/index/','refresh');			
			exit;
		}

		$this->data['languages'] = $this->general->get_active_languages();	
				
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title(''.SITE_NAME.' - Edit Country')
			->build('edit', $this->data);	
	}
	
	public function delete_country($id)
	{
            
			$query = $this->db->get_where('country', array('id' => $id));

			if($query->num_rows() > 0) 
			{				
				$row_data = $query->row();	
				
				//check default language
				if($row_data->default_country == "Yes")
				{
					$this->session->set_flashdata('message',"We can't delete default country.");
					redirect(ADMIN_DASHBOARD_PATH.'/country/index','refresh');
					exit;
				}
				
				$this->db->delete('country', array('id' => $id));
				@unlink('./'.$row_data->country_flag);
				
				$this->session->set_flashdata('message','The Country delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/country/index/','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','Sorry no record found.');
					redirect(ADMIN_DASHBOARD_PATH.'/country/index/','refresh');
					exit;
				}
		
	}
	
	public function country_short_code($code)
	{
		$country_id = $this->uri->segment(4);
		$query = $this->db->get_where('country',array('id !='=>$country_id,'short_code'=>$code));
		if ($query->num_rows() > 0)
		{
			$this->form_validation->set_message('country_short_code', 'The %s is already exist.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function country_name($country)
	{
		$country_id = $this->uri->segment(4);
		$query = $this->db->get_where('country',array('id !='=>$country_id,'country'=>$country));
		if ($query->num_rows() > 0)
		{
			$this->form_validation->set_message('country_name', 'The %s is already exist.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */