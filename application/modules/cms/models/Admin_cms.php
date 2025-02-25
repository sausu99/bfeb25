<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_cms extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
	
	public $validate_cms =  array(
			array('field' => 'lang', 'label' => 'Language', 'rules' => 'required'),
			array('field' => 'cms_slug', 'label' => 'CMS Slug', 'rules' => 'required'),
			array('field' => 'headtext', 'label' => 'Heading', 'rules' => 'required'),
			array('field' => 'content', 'label' => 'Content', 'rules' => 'required'),
			//array('field' => 'page_title', 'label' => 'Page Title', 'rules' => 'required'),
			//array('field' => 'meta_key', 'label' => 'Meta Key', 'rules' => 'required'),
			//array('field' => 'meta_description', 'label' => 'Meta Description', 'rules' => 'required')
		);
		
	
	public function get_cms($lang_id)
	{		
		$query = $this->db->get_where('cms',array("lang_id"=>$lang_id));

		if ($query->num_rows() > 0)
		{
		   return $query->result_array();
		} 

		return false;
	}
		
	public function get_cms_byid($parent_id,$lang_id)
	{		
		$query = $this->db->get_where('cms',array("parent_id"=>$parent_id,"lang_id"=>$lang_id));

		if ($query->num_rows() > 0)
		{
		   return $query->row_array();
		} 

		return false;
	}
	public function add_cms()
	{
		$data = array(
               'lang_id' => $this->input->post('lang', TRUE),
               'heading' => $this->input->post('headtext', TRUE),               
			   'cms_slug' => $this->general->clean_url($this->input->post('cms_slug', TRUE)),
			    'content' => $this->input->post('content'),
			   'page_title' => $this->input->post('page_title', TRUE),
			   'meta_key' => $this->input->post('meta_key', TRUE),
			   'meta_description' => $this->input->post('meta_description', TRUE),
			   'is_display' => $this->input->post('status', TRUE),
			   'last_update' => $this->general->get_local_time('time')
            );
		if(isset($this->vid_name)){
			$data['video_file']=$this->vid_name;
		}
		
				//$data['parent_id'] = 'LAST_INSERT_ID()';
				$this->db->insert('cms', $data); 
				
				//update parent id
				$id = $this->db->insert_id();
				$this->db->where('id', $id);
				$this->db->update('cms', array('parent_id'=>$id)); 
				return $id;		
	}
	
	public function update_cms()
	{
		$data = array(
               'lang_id' => $this->input->post('lang', TRUE),
               'heading' => $this->input->post('headtext', TRUE),               
			   'cms_slug' => $this->general->clean_url($this->input->post('cms_slug', TRUE)),
			    'content' => $this->input->post('content'),
			   'page_title' => $this->input->post('page_title', TRUE),
			   'meta_key' => $this->input->post('meta_key', TRUE),
			   'meta_description' => $this->input->post('meta_description', TRUE),
			   'is_display' => $this->input->post('status', TRUE),
			   'last_update' => $this->general->get_local_time('time')
            );
		if(isset($this->vid_name)){
			$data['video_file']=$this->vid_name;
		}
		// print_r($data);exit;
		
		$id = $this->uri->segment(4);
		$lang_id = $this->input->post('lang');
		
		//check this data is exist or not, if it is not exist then insert into table otherwise update it
		$query = $this->db->get_where('cms',array("parent_id"=>$id,"lang_id"=>$lang_id));
		
		if ($query->num_rows() > 0)
		{
			$this->db->where('parent_id', $id);
			$this->db->where('lang_id', $lang_id);
			$this->db->update('cms', $data); 
		}
		else
			{
				$data['parent_id'] = $id;
				$this->db->insert('cms', $data); 
			}
		

	}
	
	function update_all_member_ts()
	{
		$this->db->update('members', array('terms_condition'=>'0')); 
	}

	public function file_settings_do_upload_add($file)
	{				
		$config['upload_path'] = './'.BANNER_PATH;//define in constants
		$config['allowed_types'] = 'gif|mp4';
		$config['remove_spaces'] = TRUE;		
		$config['max_size'] = '10000';
		$this->upload->initialize($config);
		
		$this->upload->do_upload($file);
		if($this->upload->display_errors())
			{
				$error_img=$this->upload->display_errors();
				return $error_img;
			}
		else
			{
			$data = $this->upload->data();
			$this->vid_name=$data['file_name'];
			return $data;
			}
					
	}
}
