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
			$this->load->model('admin_help');
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
		
		$this->data['result_data'] = $this->admin_help->get_help_lists($this->data['lang_id']);
		
		
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Help View | '. SITE_NAME)
			->build('view', $this->data);	
		
	}
	
	public function add_help()
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
				
				$hlep_category = $this->input->post('hlep_category');
				$name = $this->input->post('name');
				$description = $this->input->post('description');

				if($hlep_category[$lang_id] == "")
					{$this->data['error'] = TRUE; $this->session->set_userdata('hlep_category_'.$lang_id, 'The Help Category field is required.');}
				else
					$this->session->unset_userdata('hlep_category_'.$lang_id);
					
				if($name[$lang_id] == "")
					{$this->data['error'] = TRUE; $this->session->set_userdata('name_'.$lang_id, 'The Help Title field is required.');}
				else
					$this->session->unset_userdata('name_'.$lang_id);
				
				if($description[$lang_id] == "")
					{$this->data['error'] = TRUE;$this->session->set_userdata('description_'.$lang_id, 'The Help Description field is required.');}
				else
					$this->session->unset_userdata('description_'.$lang_id);

			}
		}	
		
		// Set the validation rules
		$this->form_validation->set_rules($this->admin_help->validate_settings);		
		
		if($this->form_validation->run()==TRUE && $this->data['error'] == FALSE)
		{			
			//Insert Lang Settings
			$this->admin_help->insert_record();
			$this->session->set_flashdata('message','The help records are insert successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/help/index','refresh');
			exit;			
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Add Help | '. SITE_NAME)
			->build('add', $this->data);	
	}
	
	
	public function edit_help($lang_id,$id)
	{
		$this->data['jobs'] = 'Edit';
		
		//check id, if it is not set then redirect to view page
		if(!isset($lang_id) || !isset($id))
		{	
			redirect(ADMIN_DASHBOARD_PATH.'/help/index','refresh');
			exit;
		}
		
		$this->data['data_help'] = $this->admin_help->get_help_byid($id);
		//print_r($this->data['data_help']);
		
		//check data, if it is not set then redirect to view page
		if($this->data['data_help'] ==false)
		{
			redirect(ADMIN_DASHBOARD_PATH.'/help/index','refresh');
			exit;
		}
		
		$this->data['lang_id']=$lang_id;

		//Set the validation rules
		$this->form_validation->set_rules($this->admin_help->validate_settings_edit);		
	
			
		if($this->form_validation->run()==TRUE)
		{
			//Insert Lang Settings
			$this->admin_help->update_record($id);
			$this->session->set_flashdata('message','The help records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/help/index/'.$lang_id,'refresh');			
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit Help | '. SITE_NAME)
			->build('edit', $this->data);	
	}
	
	public function delete_help($lang_id,$id)
	{
			$query = $this->db->get_where('help', array('id' => $id));

			if($query->num_rows() > 0) 
			{
				$this->db->delete('help', array('id' => $id));
				
				$this->session->set_flashdata('message','The help record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/help/index/'.$lang_id,'refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','Sorry no record found.');
					redirect(ADMIN_DASHBOARD_PATH.'/help/index/'.$lang_id,'refresh');
					exit;
				}
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */