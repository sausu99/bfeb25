<?php  if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
	public function __construct() 
	{
		parent::__construct();
		
		$this->image_name_path = '';
	}
	
	public $validate_category =  array(	
			array('field' => 'name', 'label' => 'Name', 'rules' => 'required|callback_check_cat_name'),
			array('field' => 'lang', 'label' => 'Language', 'rules' => 'required'),
			
		);

		public $validate_category_edit =  array(	
			array('field' => 'name', 'label' => 'Name', 'rules' => 'required|callback_check_cat_name_edit'),
			array('field' => 'lang', 'label' => 'Language', 'rules' => 'required'),
			
		);
	
	
	//function to resize images
	public function resize_image($file_name,$thumb_name,$width,$height)
	{
        $config['image_library'] = 'gd2';
		$config['source_image'] = './'.PRODUCT_CATEGORY_PATH.$file_name;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['master_dim'] = 'width';
		$config['new_image'] = './'.PRODUCT_CATEGORY_PATH.$thumb_name;
		
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		// $this->image_lib->clear(); 
	}
		
	
	public function file_settings_do_upload($file, $max_width, $max_height)
	{
		$config['upload_path'] = './'.PRODUCT_CATEGORY_PATH;//defined in constants
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['remove_spaces'] = TRUE;		
		$config['max_width'] = $max_width;
		$config['max_height'] = $max_height;
		
		// load upload library and set config
		$this->upload->initialize($config);	
		$this->upload->do_upload($file);
		
		if($this->upload->display_errors())
		{
			$this->error_img = $this->upload->display_errors();
			//echo $this->upload->display_errors(); exit;
			return false;
		}
		else
		{
			$data = $this->upload->data();
			return $data;
		}	
	}
	
	
	public function upload_category_images($job)
	{
		$image_error = FALSE;
		$this->session->unset_userdata('error_img');
		
		// Upload image 1
		if(($_FILES && $job =='Add') || (!empty($_FILES['img']['name']) && $job =='Edit'))
		{
			
			//make file settins and do upload it
			$image_name = $this->file_settings_do_upload('img','500','500');
			
            if (isset($image_name['file_name']))
            {
				$this->image_name_path = $image_name['file_name'];
				//resize image
				$this->resize_image($this->image_name_path,$image_name['raw_name'].$image_name['file_ext'],120,120); //55,74
            }
            else
            {
			   $image_error = TRUE;
               $this->session->set_userdata('error_img',$this->error_img);
            }
		}
			return $image_error;
	}
	
			
	public function get_all_categories()
	{	
		$this->db->order_by('id','desc');		
		$query = $this->db->get('product_categories');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}
	
	//categories by lang id
	public function get_all_categories_by_lang($lang_id)
	{	
		
		$this->db->where('lang_id',$lang_id);
		$this->db->order_by("id", "ASC");
		$query = $this->db->get('product_categories');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}
         public function get_lang_details_for_others()
	{		
		$this->db->order_by("id", "ASC");
		$query = $this->db->get('language');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_all_category_details($lang_id)
	{	
		$this->db->select('PC.*, L.lang_name');
		$this->db->from('product_categories PC');
		$this->db->join('language L', 'PC.lang_id = L.id', 'left');
		if($lang_id)
        $this->db->where('L.id',$lang_id);
		
		$this->db->order_by("PC.order_by, PC.name", "asc"); 		
		$query = $this->db->get();
		// echo $this->db->last_query();
		// die();

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}
	
	public function get_visible_categories()
	{	
		$this->db->where('parent_id','0');
		$this->db->where('is_display','1');
		$this->db->order_by('name','asc');		
		$query = $this->db->get('product_categories');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}
	
	
	
	public function get_subcategories_by_parent_id($lang_id)
	{	
		$this->db->select('PC.*, L.lang_name, L.id as lang_id');
		$this->db->from('product_categories PC');
		$this->db->join('language L', 'PC.lang_id = L.id', 'left');
		$this->db->where('PC.parent_id !=',0);
                if($lang_id)
                $this->db->where('L.id',$lang_id);
		$this->db->order_by("PC.parent_id, PC.order_by", "asc"); 		
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}
	
	public function get_subcatdetail_by_parent_id($id)
	{	
		$this->db->select('PC.*');
		$this->db->from('product_categories PC');
		$this->db->where('PC.parent_id ',$id);
		$this->db->order_by("PC.name", "asc"); 		
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}
	
	public function get_category_by_id($id)
	{		
		$query = $this->db->get_where('product_categories',array("id"=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}

	public function get_category_by_parent_id($prntid=false,$lang_id=false)
	 {
	 	$this->db->select('pc.*');
	 	$this->db->from('product_categories pc');
	 	// $this->db->join('language l',)
	 	$this->db->where(array('pc.parent_id'=>$prntid,'pc.lang_id'=>$lang_id));
	 	$query =$this->db->get();
		// $query = $this->db->get_where('product_categories pc',array("pc.parent_id"=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	
	public function get_category_name_by_id($id)
	{		
		$this->db->select('name');
		$this->db->where('id',$id);
		$query = $this->db->get_where('product_categories');

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
		   	return $row->name;
		} 

		return false;
	}
	
	public function insert_category()
	{
			$data = array(
               'lang_id' => $this->input->post('lang', TRUE),
               'name' => $this->input->post('name', TRUE),
			   'short_desc' => $this->input->post('short_desc'),
			   'is_display' => $this->input->post('is_display', TRUE),
			   'is_home' => $this->input->post('is_home', TRUE),
			   'post_date'=>$this->general->get_local_time('time'),
			   // 'update_date' => $this->general->get_local_time('time')
            );
			if(isset($this->image_name_path))
				$data['image'] = $this->image_name_path;
		
				//$data['parent_id'] = 'LAST_INSERT_ID()';
				$this->db->insert('product_categories', $data); 
				
				//update parent id
				$id = $this->db->insert_id();
				$this->db->where('id', $id);
				$this->db->update('product_categories', array('parent_id'=>$id)); 
				return $id;	
		
	}

	public function get_last_parent_id()
	{
		$this->db->select_max('parent_id');
		$query = $this->db->get('product_categories');
		// echo $this->db->last_query();
		// die();
		return $query->row();
	}
	

	public function update_category()
	{
		$data = array(
               'lang_id' => $this->input->post('lang', TRUE),
              	'name' => $this->input->post('name', TRUE),               
			   'short_desc' => $this->input->post('short_desc'),
			   'is_display' => $this->input->post('is_display', TRUE),
			   'is_home' => $this->input->post('is_home', TRUE),
			   'update_date' => $this->general->get_local_time('time')
            );
		if($this->image_name_path)
				$data['image'] = $this->image_name_path;
				
		$id = $this->uri->segment(4);
		$lang_id = $this->input->post('lang');
		
		//check this data is exist or not, if it is not exist then insert into table otherwise update it
		$query = $this->db->get_where('product_categories',array("parent_id"=>$id,"lang_id"=>$lang_id));
		
		if ($query->num_rows() > 0)
		{
			$this->db->where('parent_id', $id);
			$this->db->where('lang_id', $lang_id);
			$this->db->update('product_categories', $data); 
		}
		else
			{
				$data['parent_id'] = $id;
				$this->db->insert('product_categories', $data); 
			}
	}

public function check_exit_cat_name($name,$lang_id)
	{
		$data = array();
				// $query = $this->db->get_where("members",array('email'=>$email,'id!='=>$id,'email!='=>''));
		$query = $this->db->get_where("product_categories",array('name'=>$name,'lang_id'=>$lang_id));
		// echo $this->db->last_query();
		// exit;
		if ($query->num_rows() > 0) 
		{
			$data=$query->row();	
			return $data;			
		}
		return false;
	}


public function check_exit_cat_name_edit($name,$lang_id,$parent_id)
	{
		$data = array();
				// $query = $this->db->get_where("members",array('email'=>$email,'id!='=>$id,'email!='=>''));
		$query = $this->db->get_where("product_categories",array('name'=>$name,'lang_id'=>$lang_id,'parent_id !='=>$parent_id));
		// echo $this->db->last_query();
		// exit;
		if ($query->num_rows() > 0) 
		{
			$data=$query->row();	
			return $data;			
		}
		return false;
	}
	
	
}
