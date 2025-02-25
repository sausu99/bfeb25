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
		$this->load->model('category_model');
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
		
		
		$this->data['cat_data'] = $this->category_model->get_all_categories_by_lang($lang_id);

		// print_r($this->data['cat_data']);
		// exit;
		$this->data['lang_details'] = $this->Admin_language_settings->get_lang_details_for_others();
		
		$this->data['lang_id'] = $lang_id;
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Product Category View | '.SITE_NAME)
			->build('a_view', $this->data);	

				
	}

	
	function add_category($lang_id=false)
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

			$this->data['current_menu']='add_cat';
			$this->data['lang_details'] = $this->category_model->get_lang_details_for_others();
			$this->data['jobs']='Add';
			$this->data['error']=FALSE;

			$this->form_validation->set_rules($this->category_model->validate_category);
			$upload_result = $this->category_model->upload_category_images($this->data['jobs']);
			if($this->form_validation->run()==TRUE && $upload_result == FALSE)
			{
			
				//Insert category			
				$parent_id=$this->category_model->insert_category();				
				
				$this->session->set_flashdata('message','The Category record added successfully.');
				redirect(ADMIN_DASHBOARD_PATH.'/product-categories/edit_category/'.$parent_id.'/'.$lang_id,'refresh');
			exit;
			}
		$this->data['lang_id'] = $lang_id;
			$this->template
				 ->set_layout('dashboard')
				 ->enable_parser(FALSE)
				 ->title(''.SITE_NAME.' | Category Add')
				 ->build('a_add_cat',$this->data);

	}
	

	
	public function edit_category($parent_id,$lang_id)
	{	
		//if parent id is blank then redirect to dashboard page
		if(!isset($parent_id)){redirect(ADMIN_DASHBOARD_PATH.'/product-categories/index','refresh');exit;}
		
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
			$this->data['jobs']='Edit';
			$this->data['error']=FALSE;

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		//print_r($_POST);
		// Set the validation rules
		$this->form_validation->set_rules($this->category_model->validate_category_edit);
		
		$upload_result = $this->category_model->upload_category_images($this->data['jobs']);
		if($this->form_validation->run()==TRUE && $upload_result == FALSE)
		{
			
			//update site setting
			$this->category_model->update_category();
			$this->session->set_flashdata('message','The Product Category update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/product-categories/edit_category/'.$parent_id.'/'.$lang_id,'refresh');
			exit;
		}
		
			$this->data['cat_data']=$this->category_model->get_category_by_parent_id($parent_id,$lang_id);
			// echo "<pre>";
			// print_r($this->data['cat_data']);
			// exit;
		$this->data['parent_id']=$parent_id;
		$this->data['lang_details'] = $this->Admin_language_settings->get_lang_details_for_others();
		
		$this->data['lang_id'] = $lang_id;
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Product Category Edit | '.SITE_NAME)
			->build('a_edit_cat', $this->data);	
		
	}
	
	

	
	
	public function delete_category($id,$lang_id=false)
	{
		
			$query = $this->db->get_where('product_categories', array('id' => $id));
	
			if($query->num_rows() > 0) 
			{
				$this->db->delete('product_categories', array('id' => $id));
								
				
				$this->session->set_flashdata('message','The Product Category record deleted successfully.');
				redirect(ADMIN_DASHBOARD_PATH.'/product-categories/index/'.$lang_id,'refresh');
				exit;
			}
			else
			{
					
				$this->session->set_flashdata('message','Sorry no record found.');
				redirect(ADMIN_DASHBOARD_PATH.'/product-categories/index/'.$lang_id,'refresh');
				exit;
			}
			
		
	}
	
	
	
	
	
		
	
	
	
	//function for category by language
	public function get_categories()
	{
		$lang_id = $this->input->post('lang_id', TRUE);
		$cat_id = $this->input->post('cat_id', TRUE) ? $this->input->post('cat_id', TRUE) : '';
		$cat = $this->category_model->get_all_categories_by_lang($lang_id);
		if($cat)
		{
			echo "<option value=''>Now Select Category</option>";
			foreach($cat as $sc)
			{
				if($sc->id == $cat_id){
					echo "<option value='".$sc->id."' selected='selected'>". $sc->name ."</option>";	
				} else {
					echo "<option value='".$sc->id."'>". $sc->name ."</option>";
				}
			}
		}
		else
		{
			echo "<option value=''>No Category Found</option>";
		}
	}

	public function check_cat_name()
	{
		$lang_id=$this->input->post('lang');
		$name=$this->input->post('name');
		$cat_name=$this->category_model->check_exit_cat_name($name,$lang_id);
		if($cat_name)
		{
			$this->form_validation->set_message('check_cat_name', 'Name Must be unique for this language');
			return false;
		}
		else
		{
			return true;
		}
	}
	
public function check_cat_name_edit()
	{
		$lang_id=$this->input->post('lang');
		$parent_id=$this->input->post('parent_id');
		$name=$this->input->post('name');
		$cat_name=$this->category_model->check_exit_cat_name_edit($name,$lang_id,$parent_id);
		if($cat_name)
		{
			$this->form_validation->set_message('check_cat_name_edit', 'Name Must be unique for this language');
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