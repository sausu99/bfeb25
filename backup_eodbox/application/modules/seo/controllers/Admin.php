<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

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
		$this->load->model('Admin_seo');
		$this->load->model('language-settings/Admin_language_settings');
		//load custom helper
		$this->load->helper('editor_helper');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
		// get array of permissions for logged in use type
	}
	
	public function index($lang_id='')
	{
		if($lang_id)
		{
			$lang_id = $lang_id;
		}
		else
			{
				$lang_id = $this->Admin_language_settings->get_default_lang_id();
				if($lang_id == false)
				{
					$this->session->set_flashdata('message','Sorry to say we have not set default language.');
					redirect(ADMIN_DASHBOARD_PATH,'refresh');
					exit;
				}
			}	
		
		
		$this->data['seo_data'] = $this->Admin_seo->get_all_seo_by_lang($lang_id);

		// print_r($this->data['cat_data']);
		// exit;
		$this->data['lang_details'] = $this->Admin_language_settings->get_lang_details_for_others();
		
		$this->data['lang_id'] = $lang_id;
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('SEO View | '.SITE_NAME)
			->build('a_view_seo', $this->data);	

				
	}

	
	function add($lang_id=false)
	{
			$this->data['jobs']='Add';
		if($lang_id)
		{
			$lang_id = $lang_id;
		}
		else
			{
				$lang_id = $this->Admin_language_settings->get_default_lang_id();
				if($lang_id == false)
				{
					$this->session->set_flashdata('message','Sorry to say we have not set default language.');
					redirect(ADMIN_DASHBOARD_PATH,'refresh');
					exit;
				}
			}
			$this->data['all_seo_pages']=$this->Admin_seo->get_all_seo_pages();	

			$this->data['current_menu']='add';
			$this->data['lang_details'] = $this->Admin_seo->get_lang_details_for_others();
			$this->data['jobs']='Add';
			$this->data['error']=FALSE;
			
			
				$this->form_validation->set_rules($this->Admin_seo->validate_seo);
		
			if($this->form_validation->run()==TRUE )
			{
			
				//Insert category			
				$parent_id=$this->Admin_seo->insert_seo();				
				
				$this->session->set_flashdata('message','The SEO record added successfully.');
				redirect(ADMIN_DASHBOARD_PATH.'/seo/edit/'.$parent_id.'/'.$lang_id,'refresh');
			exit;
			}
			
		$this->data['lang_id'] = $lang_id;
			$this->template
				 ->set_layout('dashboard')
				 ->enable_parser(FALSE)
				 ->title(''.SITE_NAME.' | SEO Add')
				 ->build('a_add_seo',$this->data);

	}
	
	public function edit($parent_id,$lang_id)
	{	
		//if parent id is blank then redirect to dashboard page
		if(!isset($parent_id)){redirect(ADMIN_DASHBOARD_PATH.'/seo/index','refresh');exit;}
		
		//get lang id if it is set otherwise we take default language
		$this->data['all_seo_pages']=$this->Admin_seo->get_all_seo_pages();	
		$this->data['page_id']=$this->Admin_seo->get_page_id_by_parent_id($parent_id);

		if($lang_id)
		{
			$lang_id = $lang_id;
		}
		else
			{
				$lang_id = $this->Admin_language_settings->get_default_lang_id();
				if($lang_id == false)
				{
					$this->session->set_flashdata('message','Sorry to say we have not set default language.');
					redirect(ADMIN_DASHBOARD_PATH,'refresh');
					exit;
				}
			}	
			$this->data['jobs']='Edit';
			$this->data['error']=FALSE;

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		//print_r($_POST);
		// Set the validation rules
		$this->form_validation->set_rules($this->Admin_seo->validate_seo_edit);
		
		if($this->form_validation->run()==TRUE)
		{
			
			//update site setting
			$this->Admin_seo->update_seo();
			$this->session->set_flashdata('message','SEO update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/seo/edit/'.$parent_id.'/'.$lang_id,'refresh');
			exit;
		}
		
			$this->data['seo_data']=$this->Admin_seo->get_seo_by_parent_id($parent_id,$lang_id);
			// echo "<pre>";
			// print_r($this->data['seo_data']);
			// exit;
			// if(empty($this->data['seo_data']))
			// {
			// 	redirect(ADMIN_DASHBOARD_PATH.'/seo/index','refresh');
			// 	exit;
			// }

		$this->data['parent_id']=$parent_id;
		$this->data['lang_details'] = $this->Admin_language_settings->get_lang_details_for_others();
		
		$this->data['lang_id'] = $lang_id;
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Seo Edit | '.SITE_NAME)
			->build('a_edit_seo', $this->data);	
		
	}
	
	
		
	
	
	
	

	public function check_previous_page()
	{
		$lang_id=$this->input->post('lang');
		$seo_page=$this->input->post('seo_page');
		$seo_page_info=$this->Admin_seo->check_exit_seo_page($seo_page,$lang_id);
		if($seo_page_info)
		{
			$this->form_validation->set_message('check_previous_page', 'Already Exit this page for this language ');
			return false;
		}
		else
		{
			return true;
		}
	}
	

	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */