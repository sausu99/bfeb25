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
			$this->load->model('admin_language_settings');
		
		//load helper
		$this->load->helper('file');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	public function index()
	{
		$this->data['lang_details'] = $this->admin_language_settings->get_lang_details();
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Language Settings | '.SITE_NAME)
			->build('lang_view', $this->data);	
		
	}
	
	public function add_lang()
	{
		$this->data['jobs'] = 'Add';
		
		
			//print_r($_FILES);

		// Set the validation rules
		$this->form_validation->set_rules($this->admin_language_settings->validate_lang_settings);		
		
		//make file settins and do upload it
		//$this->admin_language_settings->file_settings_do_upload();
		//$image_data = $this->upload->data();			
		
		//$flag_full_path = FLAG_PATH.$image_data['file_name'];
			
		//if($this->form_validation->run()==TRUE && !$this->upload->display_errors())
		if($this->form_validation->run()==TRUE)
		{			
			//Insert Lang Settings
			$this->admin_language_settings->insert_lang_record();
			$this->session->set_flashdata('message','The language settings records are insert successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/language-settings/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Language Settings | '.SITE_NAME)
			->build('lang_add', $this->data);	
	}
	
	
	public function edit_lang($id)
	{
		$this->data['jobs'] = 'Edit';
		
		//check lang id, if it is not set then redirect to view page
		if(!isset($id)){redirect(ADMIN_DASHBOARD_PATH.'/language-settings/index','refresh');exit;}
		
		$this->data['data_lang'] = $this->admin_language_settings->get_lang_details_by_id($id);
		
		//check lang data, if it is not set then redirect to view page
		if($this->data['data_lang'] == false){redirect(ADMIN_DASHBOARD_PATH.'/language-settings/index','refresh');exit;}
		
			//print_r($_FILES);

		// Set the validation rules
		$this->form_validation->set_rules($this->admin_language_settings->validate_lang_settings);		
		
		//make file settins and do upload it
		/*if(isset($_FILES['flag']['tmp_name']) && $_FILES['flag']['tmp_name']!='')
		{
			$this->admin_language_settings->file_settings_do_upload();
			$image_data = $this->upload->data();			
			$flag_full_path = FLAG_PATH.$image_data['file_name'];
			
			$file_error = $this->upload->display_errors();
		}
		else
			{
				$file_error = '';
				$flag_full_path = '';
			}*/
			
		
		//if($this->form_validation->run()==TRUE && !$file_error)
		if($this->form_validation->run()==TRUE)
		{	
			//Insert Lang Settings
			//$this->admin_language_settings->update_lang_record($id,$flag_full_path);
			$this->admin_language_settings->update_lang_record($id);
			$this->session->set_flashdata('message','The language settings records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/language-settings/index','refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Language Settings | '.SITE_NAME)
			->build('lang_edit', $this->data);	
	}
	
	public function delete_lang($id)
	{
		$query = $this->db->get_where('language', array('id' => $id));

			if($query->num_rows() > 0) 
			{
				
				$row_data = $query->row();	
				
				//check default language
				if($row_data->default_lang == "Yes")
				{
					$this->session->set_flashdata('message',"We can't delete default language.");
					redirect(ADMIN_DASHBOARD_PATH.'/language-settings/index','refresh');
					exit;
				}
				
				$this->db->delete('language', array('id' => $id));
				@unlink('./'.$row_data->lang_flag);
				$this->session->set_flashdata('message','Language settings record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/language-settings/index','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','No record found in our database.');
					redirect(ADMIN_DASHBOARD_PATH.'/language-settings/index','refresh');
					exit;
				}

	}
	public function duplicate_lang()
	{		
		
		if($this->uri->segment(4))
			$this->db->where('id !=', $this->uri->segment(4));
		
		$where = "(lang_name='".$this->input->post('lang_name')."')";
		$this->db->where($where);

		/*$this->db->where('lang_name', $this->input->post('lang_name'));
		$this->db->or_where('short_code', $this->input->post('short_code'));
		$this->db->or_where('currency_code', $this->input->post('currency_code'));*/
		
		$query = $this->db->get('language');
		//echo $this->db->last_query();exit;

		if ($query->num_rows()>=1)
		{
			$this->form_validation->set_message('duplicate_lang',"This language is already exist.");
			return false;
		}
		else
			return true;
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */