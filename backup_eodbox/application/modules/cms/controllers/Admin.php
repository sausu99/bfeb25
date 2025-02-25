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
			$this->load->model('Admin_cms');
			$this->load->model('language-settings/Admin_language_settings');
		
		//load custom helper
			$this->load->helper('editor_helper');
			$this->load->library('upload');
		
	}
	
	public function index($lang_id='')
	{	
		
		//get lang id if it is set otherwise we take default language
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
		
		
		$this->data['cms_data'] = $this->Admin_cms->get_cms($lang_id);
		$this->data['lang_details'] = $this->Admin_language_settings->get_lang_details_for_others();
		
		$this->data['lang_id'] = $lang_id;
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Content Management System | '.SITE_NAME)
			->build('cms_view', $this->data);	
		
	}
	
	public function add($lang_id='')
	{			
		//get lang id if it is set otherwise we take default language
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
			
		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		//print_r($_POST);
		// Set the validation rules
		$this->form_validation->set_rules($this->Admin_cms->validate_cms);
		$v_error=false;
		 if (!empty($_FILES['video_file']['name'])) {
		 	$result=$this->Admin_cms->file_settings_do_upload_add('video_file');
		 	if(isset($result['file_name'])){
		 		$v_error=false;
		 	}else{
		 		$v_error=true;
		 		$this->data['vid_error']=$result;
		 	}

		 }
		
		
		if($this->form_validation->run()==TRUE && $v_error==false)
		{
			
			//update site setting
			$parent_id = $this->Admin_cms->add_cms();
			$this->session->set_flashdata('message','The CMS Page added successful.');
			//redirect(ADMIN_DASHBOARD_PATH.'/cms/index/'.$lang_id,'refresh');
			redirect(ADMIN_DASHBOARD_PATH.'/cms/edit/'.$parent_id.'/'.$lang_id,'refresh');
			exit;
		}		
		
		$this->data['lang_details'] = $this->Admin_language_settings->get_lang_details_for_others();
		
		$this->data['lang_id'] = $lang_id;
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Content Management System | '.SITE_NAME)
			->build('cms_add', $this->data);	
		
	}
	
	public function edit($parent_id,$lang_id)
	{	

		//if parent id is blank then redirect to dashboard page
		if(!isset($parent_id)){redirect(ADMIN_DASHBOARD_PATH.'/cms/index','refresh');exit;}
		
		//get lang id if it is set otherwise we take default language
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
			
		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		//print_r($_POST);
		// Set the validation rules
		$this->form_validation->set_rules($this->Admin_cms->validate_cms);

		$v_error=false;
		 if (!empty($_FILES['video_file']['name'])) {
		 	$result=$this->Admin_cms->file_settings_do_upload_add('video_file');
		 	if(isset($result['file_name'])){
		 		$v_error=false;
		 	}else{
		 		$v_error=true;
		 		$this->data['vid_error']=$result;
		 	}

		 }
		
		
		if($this->form_validation->run()==TRUE && $v_error==false)
		{
			
			//update site setting
			$this->Admin_cms->update_cms();
			$this->session->set_flashdata('message','The CMS Page are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/cms/edit/'.$parent_id.'/'.$lang_id,'refresh');
			exit;
		}
		
		$this->data['cms_data'] = $this->Admin_cms->get_cms_byid($parent_id,$lang_id);
		$this->data['lang_details'] = $this->Admin_language_settings->get_lang_details_for_others();
		
		$this->data['lang_id'] = $lang_id;
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Content Management System | '.SITE_NAME)
			->build('cms_edit', $this->data);	
		
	}
	
	public function delete($id,$lang_id)
	{
			$query = $this->db->get_where('cms', array('id' => $id));

			if($query->num_rows() > 0) 
			{
				$this->db->delete('cms', array('id' => $id));
				
				$this->session->set_flashdata('message','The cms record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/cms/index/'.$lang_id,'refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','Sorry no record found.');
					redirect(ADMIN_DASHBOARD_PATH.'/cms/index/'.$lang_id,'refresh');
					exit;
				}
		
	}	
	public function delete_vid($id,$parent_id){
		$lang_id = $this->Admin_language_settings->get_default_lang_id();

		$query=$this->db->get_where('cms',array('id'=>$id));
		if($query->num_rows()=='1'){
			@unlink('./'.BANNER_PATH.'/'.$query->row()->video_file);
		}
		
		
		
				$data=array('video_file'=>'');
				$this->db->where('id',$id);
				$this->db->update('cms',$data);
				
				$this->session->set_flashdata('message','The Video  deleted successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/cms/edit/'.$parent_id.'/'.$lang_id,'refresh');
			exit;
			
	
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */