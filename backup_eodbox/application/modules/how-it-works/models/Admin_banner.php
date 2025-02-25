<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_banner extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
	
	public $validate_settings =  array(	
			array('field' => 'lang', 'label' => 'Language', 'rules' => 'required')			
		);
		
	public function file_settings_do_upload()
	{
				$config['upload_path'] = './'.BANNER_PATH;//define in constants
				$config['allowed_types'] = 'gif|jpg|png';
				$config['remove_spaces'] = TRUE;		
				$config['max_size'] = '200';
				$config['max_width'] = '1000';
				$config['max_height'] = '400';

				// load upload library and set config				
				if(isset($_FILES['banner']['tmp_name']))
				{
					$this->upload->initialize($config);
					$this->upload->do_upload('banner');
				}		
	}
	public function get_banner_details()
	{		
				 $this->db->order_by("last_update", "desc"); 
		$query = $this->db->get('how_it_works');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_banner_lists($lang_id)
	{		
				 $this->db->where("lang_id", $lang_id); 
				 $this->db->order_by("id", "asc"); 
		$query = $this->db->get('how_it_works');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	
	public function get_banner_details_by_id($id)
	{
		$query = $this->db->get_where('how_it_works',array('id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	public function insert_record()
	{
		foreach($this->image_name_path as $key=>$value)
		{
			$data = array(               
					  'is_display' => 'Yes',//$this->input->post('is_display', TRUE),				   
					  'lang_id' => $this->input->post('lang', TRUE),
					  'banner' => $value,
					  'last_update' => $this->general->get_local_time('time')
				   
				);
	
			$this->db->insert('how_it_works', $data);
		}
	}
	
	public function update_record($id,$img_path)
	{
		$data = array(               
				  'is_display' => $this->input->post('is_display', TRUE),
				  'lang_id' => $this->input->post('lang', TRUE),
			  	  'last_update' => $this->general->get_local_time('time')
            );

		//only if new flag is uploaded
		if(isset($img_path) && $img_path !="")
		{
			@unlink('./'.$this->input->post('old_file'));
			$data['banner'] = $img_path;
		}
		
		$this->db->where('id', $id);
		//echo $this->db->last_query();
		$this->db->update('how_it_works', $data);

	}
	
	public function file_settings_do_upload_add($file)
	{				
		$config['upload_path'] = './'.BANNER_PATH;//define in constants
		$config['allowed_types'] = 'gif|jpg|png';
		$config['remove_spaces'] = TRUE;		
		$config['max_size'] = '200';
		$config['max_width'] = '1000';
		$config['max_height'] = '400';
		$this->upload->initialize($config);
		
		$this->upload->do_upload($file);
		if($this->upload->display_errors())
			{
				$this->error_img=$this->upload->display_errors();
				return false;
			}
		else
			{
			$data = $this->upload->data();
			return $data;
			}
					
	}
	public function upload_auction_images($job)
	{
		$this->image_name_path = array();
		$image_error = FALSE;
		$this->session->unset_userdata('error_img1');	
		$this->session->unset_userdata('error_img2');	
		$this->session->unset_userdata('error_img3');		
		$this->session->unset_userdata('error_img4');				
		// Upload image 1
		if(($_FILES && $job =='Add') || (!empty($_FILES['img1']['name']) && $job =='Edit'))
		{
			//make file settins and do upload it
			$image1_name = $this->file_settings_do_upload_add('img1');
			
            if ($image1_name['file_name'])
            {
				$this->image_name_path[1] = BANNER_PATH.$image1_name['file_name'];
				//resize image
				//$this->resize_image($this->image_name_path1,$image1_name['raw_name'].$image1_name['file_ext'],400,311);
				//$this->resize_image($this->image_name_path1,'thumb_'.$image1_name['raw_name'].$image1_name['file_ext'],100,100);
            }
            else
            {
			   $image_error = TRUE;
               $this->session->set_userdata('error_img1',$this->error_img);
            }
		}
		
		// Upload image 2
		if(!empty($_FILES['img2']['name']))
		{
			
			//make file settins and do upload it
			$image2_name = $this->file_settings_do_upload_add('img2');
			
            if ($image2_name['file_name'])
            {
				$this->image_name_path[2] = BANNER_PATH.$image2_name['file_name'];				
				//resize image
				//$this->resize_image($this->image_name_path2,$image2_name['raw_name'].$image2_name['file_ext'],400,311);
				//$this->resize_image($this->image_name_path2,'thumb_'.$image2_name['raw_name'].$image2_name['file_ext'],100,100);
            }
            else
            {
				$image_error = TRUE;
               $this->session->set_userdata('error_img2',$this->error_img);
            }
		}
		
		// Upload image 3
		if(!empty($_FILES['img3']['name']))
		{
			
			//make file settins and do upload it
			$image3_name = $this->file_settings_do_upload_add('img3');
			
            if ($image3_name['file_name'])
            {
				$this->image_name_path[3] = BANNER_PATH.$image3_name['file_name'];
				//resize image
				//$this->resize_image($this->image_name_path3,$image3_name['raw_name'].$image3_name['file_ext'],400,311);
				//$this->resize_image($this->image_name_path3,'thumb_'.$image3_name['raw_name'].$image3_name['file_ext'],100,100);
            }
            else
            {
				 $image_error = TRUE;
               $this->session->set_userdata('error_img3',$this->error_img);
            }
		}
		
		// Upload image 1
		if(!empty($_FILES['img4']['name']))
		{
			
			//make file settins and do upload it
			$image4_name = $this->file_settings_do_upload_add('img4');
			
            if ($image4_name['file_name'])
            {
				$this->image_name_path[4] = BANNER_PATH.$image4_name['file_name'];
				//resize image
				//$this->resize_image($this->image_name_path4,$image4_name['raw_name'].$image4_name['file_ext'],400,311);
				//$this->resize_image($this->image_name_path4,'thumb_'.$image4_name['raw_name'].$image4_name['file_ext'],100,100);
            }
            else
            {
				 $image_error = TRUE;
               $this->session->set_userdata('error_img4',$this->error_img);
            }
		}
		
		return $image_error;
		
	}
	

}
