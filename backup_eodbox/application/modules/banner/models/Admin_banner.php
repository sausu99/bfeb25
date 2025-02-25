<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_banner extends CI_Model 
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
				$config['encrypt_name'] = TRUE;		
				$config['max_size'] = '5000';
				$config['max_width'] = '2000';
				$config['max_height'] = '840';

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
		$query = $this->db->get('banner');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_banner_lists($lang_id)
	{		
				 $this->db->where("lang_id", $lang_id); 
				 $this->db->order_by("last_update", "desc"); 
		$query = $this->db->get('banner');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	
	public function get_banner_details_by_id($id)
	{
		$query = $this->db->get_where('banner',array('id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	public function insert_record()
	{
		$i=1;
		foreach($this->image_name_path as $key=>$value)
		{
			$data = array(               
					  'is_display' => 'Yes',//$this->input->post('is_display', TRUE),				   
					  'lang_id' => $this->input->post('lang', TRUE),
					  'banner' => $value,
					  'url' => $this->input->post('url'.$i, TRUE),
					  'last_update' => $this->general->get_gmt_time('time'),
					  'type' => $this->input->post('type')
				   
				);
	
			$this->db->insert('banner', $data);
			$i++;
		}
	}
	
	public function update_record($id,$img_path)
	{
		$data = array(               
				  'is_display' => $this->input->post('is_display', TRUE),
				  'lang_id' => $this->input->post('lang', TRUE),
				  'url' => $this->input->post('url', TRUE),
			  	  'last_update' => $this->general->get_local_time('time'),
			  	   'type' => $this->input->post('type')
            );

		//only if new flag is uploaded
		if(isset($img_path) && $img_path !="")
		{
			@unlink(BANNER_PATH.$this->input->post('old_file'));
			@unlink(BANNER_PATH.'thumb_'.$this->input->post('old_file'));
			$data['banner'] = $img_path;
		}
		
		$this->db->where('id', $id);
		//echo $this->db->last_query();
		$this->db->update('banner', $data);

	}
	
	public function file_settings_do_upload_add($file)
	{				
		$config['upload_path'] = './'.BANNER_PATH;//define in constants
		$config['allowed_types'] = 'gif|jpg|png';
		$config['remove_spaces'] = TRUE;		
		$config['max_size'] = '5000';
		$config['max_width'] = '1900';
		$config['max_height'] = '840';
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
				$this->image_name_path[1] = $image1_name['file_name'];
				//resize image
				$this->resize_image('./'.BANNER_PATH,$image1_name['file_name'],'thumb_'.$image1_name['file_name'],960,420);
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
				$this->image_name_path[2] = $image2_name['file_name'];				
				//resize image
				$this->resize_image('./'.BANNER_PATH,$image2_name['file_name'],'thumb_'.$image2_name['file_name'],960,420);
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
				$this->image_name_path[3] = $image3_name['file_name'];
				//resize image
				$this->resize_image('./'.BANNER_PATH,$image3_name['file_name'],'thumb_'.$image3_name['file_name'],960,420);
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
				$this->image_name_path[4] = $image4_name['file_name'];
				//resize image
				$this->resize_image('./'.BANNER_PATH,$image4_name['file_name'],'thumb_'.$image4_name['file_name'],960,420);
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

	//function to resize images
	public function resize_image($location,$source_image,$new_image,$width,$height)
	{
		// echo "#Location :".$location.' #$original file : '.$source_image.' #New file name :'.$new_image.' #width :'.$width.' #height'.$height;
		// echo './'.$location.$source_image;exit;
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = './'.$location.$source_image;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['master_dim'] = 'width';
		$config['new_image'] = './'.$location.$new_image;
		
		$this->image_lib->initialize($config);
		$resize = $this->image_lib->resize();
		//var_dump($resize);
		//echo $this->image_lib->display_errors();
		// $this->image_lib->clear(); 
	}

	

}
