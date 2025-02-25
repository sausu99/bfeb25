<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_testimonial extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		//load CI library
			$this->load->library('form_validation');
	}
	
	public $validate_settings =  array(	
			array('field' => 'lang_id', 'label' => 'Language', 'rules' => 'required'),
			array('field' => 'winner_name', 'label' => 'Username', 'rules' => 'required'),
			array('field' => 'product_name', 'label' => 'Product Name', 'rules' => 'required'),
			array('field' => 'description', 'label' => 'Description', 'rules' => 'required')			
		);
		
	public function file_settings_do_upload()
	{
				$config['upload_path'] = './'.TESTIMONIAL_PATH;//define in constants
				$config['allowed_types'] = 'gif|jpg|png';
				$config['remove_spaces'] = TRUE;		
				$config['max_size'] = '5000';
				$config['max_width'] = '1600';
				$config['max_height'] = '1024';
				

				// load upload library and set config				
				if(isset($_FILES['img']['tmp_name']))
				{
					$this->upload->initialize($config);
					$this->upload->do_upload('img');
				}		
	}
	
	public function resize_image($file_name,$thumb_name,$width,$height)
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = './'.TESTIMONIAL_PATH.$file_name;
		//$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['master_dim'] = 'width';
		$config['new_image'] = './'.TESTIMONIAL_PATH.$thumb_name;
		
		$this->image_lib->initialize($config);
		
		$this->image_lib->resize();
		//$this->image_lib->clear(); 
		
	}
	
	public function get_details_admin()
	{		
		$this->db->order_by("last_update", "desc"); 
		$query = $this->db->get('testimonial');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_details()
	{		
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
			
		$this->db->order_by("last_update", "desc"); 
		$query = $this->db->get_where('testimonial',array('status'=>'Active','lang_id'=>$lang_id));

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}


	public function get_details_by_id($id)
	{
		$query = $this->db->get_where('testimonial',array('id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	public function insert($img)
	{
		$data = array(               
			   'lang_id' => $this->input->post('lang_id', TRUE),
			   'status' => $this->input->post('status', TRUE),
			   'winner_name' => $this->input->post('winner_name'),
			   'product_name' => $this->input->post('product_name'),	
			   'description' => $this->input->post('description'),				   
			   'image' => $img,
			   'last_update' => $this->general->get_local_time('time')
			   
            );

		$this->db->insert('testimonial', $data); 

	}
	
	public function update($id,$img_full_path)
	{
		$data = array(               
				'lang_id' => $this->input->post('lang_id', TRUE),
			   'status' => $this->input->post('status', TRUE),
			   'winner_name' => $this->input->post('winner_name'),
			   'product_name' => $this->input->post('product_name'),	
			   'description' => $this->input->post('description'),				   			   
			   'last_update' => $this->general->get_local_time('time')
			   
            );

		//only if new img is uploaded
		if(isset($img_full_path) && $img_full_path !="")
		{
			@unlink('./'.$this->input->post('img_old'));
			$data['image'] = $img_full_path;
		}
		//print_r($data);exit;
		$this->db->where('id', $id);
		
		$this->db->update('testimonial', $data);

	}
	
	
	

}
