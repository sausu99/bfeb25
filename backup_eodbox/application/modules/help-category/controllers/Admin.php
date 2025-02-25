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
			$this->load->model('admin_help_category');
			$this->load->model('language-settings/admin_language_settings');
		
		//load helper
		$this->load->helper('editor_helper');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	public function index()
	{		
		//get all language
		$this->data['lang_details'] = $this->admin_language_settings->get_lang_details_for_others();
		
		if($this->uri->segment(4))
		{$this->data['lang_id'] = $this->uri->segment(4);}
		else
			{$this->data['lang_id'] = $this->data['lang_details'][0]->id;}
		
		$this->data['result_data'] = $this->admin_help_category->get_help_category_lists($this->data['lang_id']);
		
		
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Help Category View | '. SITE_NAME)
			->build('view', $this->data);	
		
	}
	
	public function add_help_category()
	{
		$this->data['jobs'] = 'Add';
		//$name = $this->input->post('name');
		//print_r($name[1]);//exit;
		$this->data['error'] = FALSE;
		
		//get all language
		$this->data['lang_details'] = $this->admin_language_settings->get_lang_details_for_others();		
		
		
		if($this->input->post('lang_id') != "")
		{
			$lang_id = '';
			
			for($i=0; $i<count($this->input->post('lang_id')); $i++)
			{
				$all_lang_id = $this->input->post('lang_id');
				$lang_id = $all_lang_id[$i];
				
				$name = $this->input->post('name');

				if($name[$lang_id] == "")
					{$this->data['error'] = TRUE; $this->session->set_userdata('name_'.$lang_id, 'The Help Category field is required.');}
				else
					$this->session->unset_userdata('name_'.$lang_id);

			}
		}	
		
		// Set the validation rules
		$this->form_validation->set_rules($this->admin_help_category->validate_settings);		
		
		if($this->form_validation->run()==TRUE && $this->data['error'] == FALSE)
		{			
			//Insert Lang Settings
			$this->admin_help_category->insert_record();
			$this->session->set_flashdata('message','The Help Category records are insert successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/help-category/index','refresh');
			exit;			
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Add Help Category | '. SITE_NAME)
			->build('add', $this->data);	
	}
	
	
	public function edit_help_category($lang_id,$id)
	{
		$this->data['jobs'] = 'Edit';
		
		//check id, if it is not set then redirect to view page
		if(!isset($lang_id) || !isset($id))
		{	
			redirect(ADMIN_DASHBOARD_PATH.'/help-category/index','refresh');
			exit;
		}
		
		$this->data['data_help_category'] = $this->admin_help_category->get_help_category_byid($id);
		//print_r($this->data['data_help_category']);
		
		//check data, if it is not set then redirect to view page
		if($this->data['data_help_category'] ==false)
		{
			redirect(ADMIN_DASHBOARD_PATH.'/help-category/index','refresh');
			exit;
		}
		


		//Set the validation rules
		$this->form_validation->set_rules($this->admin_help_category->validate_settings_edit);		
	
			
		if($this->form_validation->run()==TRUE)
		{
			//Insert Lang Settings
			$this->admin_help_category->update_record($id);
			$this->session->set_flashdata('message','The Help Category records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/help-category/index/'.$lang_id,'refresh');			
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit Help Category | '. SITE_NAME)
			->build('edit', $this->data);	
	}
	
	public function delete_help_category($lang_id,$id)
	{
			$query = $this->db->get_where('help_category', array('id' => $id));

			if($query->num_rows() > 0) 
			{
				$this->db->delete('help_category', array('id' => $id));
				$this->db->delete('help', array('help_cat_id' => $id));
				
				$this->session->set_flashdata('message','The help category record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/help-category/index/'.$lang_id,'refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','Sorry no record found.');
					redirect(ADMIN_DASHBOARD_PATH.'/help-category/index/'.$lang_id,'refresh');
					exit;
				}
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */