<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_auction extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		//load CI library
			$this->load->library('form_validation');
			
			$this->image_name_path1 = '';
			$this->image_name_path2 = '';
			$this->image_name_path3 = '';
			$this->image_name_path4 = '';
			
	}
	
	public $validate_settings =  array(	
			array('field' => 'cat_id', 'label' => 'Category Name', 'rules' => 'required'),
			array('field' => 'price', 'label' => 'Auction Price', 'rules' => 'required'),
			array('field' => 'shipping_cost', 'label' => 'Shipping Cost', 'rules' => 'required'),
			array('field' => 'bid_fee', 'label' => 'Bid Fee', 'rules' => 'required|integer'),
			array('field' => 'sms_code', 'label' => 'SMS Code', 'rules' => 'required|trim|is_unique[auction.sms_code]'),
			// array('field' => 'reset_time', 'label' => 'Reset Time', 'rules' => 'required|integer|greater_than[14]'),
			array('field' => 'start_date', 'label' => 'Start Date', 'rules' => 'required'),
			array('field' => 'end_date', 'label' => 'End Date', 'rules' => 'required|callback_check_end_date'),
			
			array('field' => 'lang_id[]', 'label' => 'Language', 'rules' => 'required')
		);

		public $validate_settings_vote =  array(	
			array('field' => 'cat_id', 'label' => 'Category Name', 'rules' => 'required'),
			array('field' => 'price', 'label' => 'Auction Price', 'rules' => 'required'),
			array('field' => 'shipping_cost', 'label' => 'Shipping Cost', 'rules' => 'required'),
			array('field' => 'sms_code', 'label' => 'SMS Code', 'rules' => 'required|trim|is_unique[auction.sms_code]'),
			array('field' => 'bid_fee', 'label' => 'Bid Fee', 'rules' => 'required|integer'),
			array('field' => 'lang_id[]', 'label' => 'Language', 'rules' => 'required')
		);

		public $validate_settings_edit =  array(	
			array('field' => 'cat_id', 'label' => 'Category Name', 'rules' => 'required'),
			array('field' => 'price', 'label' => 'Auction Price', 'rules' => 'required|decimal'),
			array('field' => 'shipping_cost', 'label' => 'Shipping Cost', 'rules' => 'required'),
			array('field' => 'bid_fee', 'label' => 'Bid Fee', 'rules' => 'required|integer'),
			// array('field' => 'reset_time', 'label' => 'Reset Time', 'rules' => 'required|integer|greater_than[14]'),
			array('field' => 'sms_code', 'label' => 'SMS Code', 'rules' => 'required|callback_check_sms_code'),
			array('field' => 'start_date', 'label' => 'Start Date', 'rules' => 'required'),
			array('field' => 'end_date', 'label' => 'End Date', 'rules' => 'required|callback_check_end_date'),
			array('field' => 'lang_id[]', 'label' => 'Language', 'rules' => 'required'),

		);

			public $validate_settings_vote_edit =  array(	
			array('field' => 'cat_id', 'label' => 'Category Name', 'rules' => 'required'),
			array('field' => 'price', 'label' => 'Auction Price', 'rules' => 'required|decimal'),
			array('field' => 'shipping_cost', 'label' => 'Shipping Cost', 'rules' => 'required'),
			array('field' => 'sms_code', 'label' => 'SMS Code', 'rules' => 'required|callback_check_sms_code'),
			array('field' => 'bid_fee', 'label' => 'Bid Fee', 'rules' => 'required|integer'),
			// array('field' => 'reset_time', 'label' => 'Reset Time', 'rules' => 'required|integer|greater_than[14]'),
			array('field' => 'lang_id[]', 'label' => 'Language', 'rules' => 'required'),
			);
		
	public function resize_image($file_name,$thumb_name,$width,$height)
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = './'.AUCTION_IMG_PATH.$file_name;
		//$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['master_dim'] = 'width';
		$config['new_image'] = './'.AUCTION_IMG_PATH.$thumb_name;
		
		$this->image_lib->initialize($config);
		
		$this->image_lib->resize();
		// $this->image_lib->clear(); 
		
	}
	public function file_settings_do_upload($file)
	{
				$config['upload_path'] = './'.AUCTION_IMG_PATH;//define in constants
				$config['allowed_types'] = 'gif|jpg|png';
				$config['remove_spaces'] = TRUE;		
				$config['encrypt_name'] = TRUE;
				$config['max_size'] = '5000';
				$config['max_width'] = '1024';
				$config['max_height'] = '1024';
				$this->upload->initialize($config);
				//print_r($_FILES);
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
		$image_error = FALSE;
		$this->session->unset_userdata('error_img1');	
		$this->session->unset_userdata('error_img2');	
		$this->session->unset_userdata('error_img3');		
		$this->session->unset_userdata('error_img4');
					
		// Upload image 1
		if(($_FILES && $job =='Add') || (!empty($_FILES['img1']['name']) && $job =='Edit'))
		{
			//make file settins and do upload it
			$image1_name = $this->file_settings_do_upload('img1');
			
            if ($image1_name['file_name'])
            {
				$this->image_name_path1 = $image1_name['file_name'];
				//resize image
				$this->resize_image($this->image_name_path1,$image1_name['raw_name'].$image1_name['file_ext'],560,400);
				$this->resize_image($this->image_name_path1,'thumb_'.$image1_name['raw_name'].$image1_name['file_ext'],330,250);
            }
            else
            {
			   $image_error = TRUE;
               $this->session->set_userdata('error_img1',$this->error_img);
            }
		}elseif(($_FILES && $job =='Copy')){
                    $this->image_name_path1 = $this->input->post('copy_img1');
                }
		
		// Upload image 2
		if(!empty($_FILES['img2']['name']))
		{
			
			//make file settins and do upload it
			$image2_name = $this->file_settings_do_upload('img2');
			
            if ($image2_name['file_name'])
            {
				$this->image_name_path2 = $image2_name['file_name'];				
				//resize image
				$this->resize_image($this->image_name_path2,$image2_name['raw_name'].$image2_name['file_ext'],560,400);
				$this->resize_image($this->image_name_path2,'thumb_'.$image2_name['raw_name'].$image2_name['file_ext'],200,200);
            }
            else
            {
				$image_error = TRUE;
               $this->session->set_userdata('error_img2',$this->error_img);
            }
		}else if($this->input->post('copy_img2'))
		{$this->image_name_path2 = $this->input->post('copy_img2');}
		
		// Upload image 3
		if(!empty($_FILES['img3']['name']))
		{
			
			//make file settins and do upload it
			$image3_name = $this->file_settings_do_upload('img3');
			
            if ($image3_name['file_name'])
            {
				$this->image_name_path3 = $image3_name['file_name'];
				//resize image
				$this->resize_image($this->image_name_path3,$image3_name['raw_name'].$image3_name['file_ext'],560,400);
				$this->resize_image($this->image_name_path3,'thumb_'.$image3_name['raw_name'].$image3_name['file_ext'],330,250);
            }
            else
            {
				 $image_error = TRUE;
               $this->session->set_userdata('error_img3',$this->error_img);
            }
		}else if($this->input->post('copy_img3'))
		{$this->image_name_path3 = $this->input->post('copy_img3');}
		
		// Upload image 4
		if(!empty($_FILES['img4']['name']))
		{			
			//make file settins and do upload it
			$image4_name = $this->file_settings_do_upload('img4');
			
            if ($image4_name['file_name'])
            {
				$this->image_name_path4 = $image4_name['file_name'];
				//resize image
				$this->resize_image($this->image_name_path4,$image4_name['raw_name'].$image4_name['file_ext'],560,400);
				$this->resize_image($this->image_name_path4,'thumb_'.$image4_name['raw_name'].$image4_name['file_ext'],330,250);
            }
            else
            {
				 $image_error = TRUE;
               $this->session->set_userdata('error_img4',$this->error_img);
            }
		}else if($this->input->post('copy_img4'))
		{
			$this->image_name_path4 = $this->input->post('copy_img4');
		}
		
		// Upload manufacturer logo image 5
		else if($this->input->post('copy_img5'))
		{
			$this->image_name_path5 = $this->input->post('copy_img5');
		}
		
		// Upload partners logo image 6
		else if($this->input->post('copy_img6'))
		{
			$this->image_name_path6 = $this->input->post('copy_img6');
		}
		
		return $image_error;
		
	}
	public function get_toal_auctions($status,$type=false)
	{		
		if($status) $status = $status; else $status = 'Live';
		
		$this->db->select('auc.*,auc_det.name');
		$this->db->from('auction auc');
		$this->db->join('auction_details auc_det', 'auc_det.auc_id = auc.id', 'left');
		$this->db->where('auc.status',$status);
		if($this->input->post('srch') != "")
			$this->db->like('auc_det.name',$this->input->post('srch'));
		if($type)
		{
			$this->db->where('auc_type',$type);
		}
		$this->db->group_by('auc.id');
		$this->db->order_by("auc.last_update", "desc");
		$query = $this->db->get();
		// echo $this->db->last_query();
		// exit;

		return $query->num_rows();
	}
	
	public function get_auction_details($status,$perpage,$offset,$type=false)
	{		
		if($status) $status = $status; else $status = 'Live';
		
		$this->db->select('auc.*,auc_det.name,auc_det.lang_id');
		$this->db->from('auction auc');
		$this->db->join('auction_details auc_det', 'auc_det.auc_id = auc.id', 'left');
		$this->db->where('auc.status',$status);
		if($this->input->post('srch') != "")
		{
			$this->db->like('auc_det.name',$this->input->post('srch'));
		}
		if($this->input->post('cat_id') != "")
		{
		$this->db->where('auc.cat_id',$this->input->post('cat_id'));
		}
		if($type)
		{
			$this->db->where('auc.auc_type',$type);
		}
		$this->db->group_by('auc.id');
		$this->db->order_by("auc.last_update", "desc");
		$this->db->limit($perpage, $offset);
		
		$query = $this->db->get();
		// echo $this->db->last_query();
		// exit;
		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_bid_palce_byid($auc_id)
	{
		$query = $this->db->get_where('user_bids',array('auc_id'=>$auc_id));
		return $query->num_rows();
	}
	
	public function get_all_bid_palce_byid($auc_id,$perpage, $offset)
	{			
		$this->db->select('ub.*,m.id as user_id,m.user_name,m.first_name,m.last_name,m.email');
		$this->db->from('user_bids ub');
		$this->db->join('members m', 'm.id = ub.user_id', 'left');
		$this->db->where('ub.auc_id',$auc_id);
		$this->db->order_by('userbid_bid_amt,id','asc,DESC');
		$this->db->limit($perpage, $offset);
		$query = $this->db->get();
		// echo $this->db->last_query();
		// die();
		
		
		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_auction_name($id)
	{
		$this->db->select('name');
		$this->db->from('auction_details');
		$this->db->where('auc_id',$id);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
		   $data = $query->row();
		 
		   return $data->name;
		} 

		return false;
	}
	
	public function get_auction_byid($id)
	{
		$query = $this->db->get_where('auction',array('id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
      
	
	public function check_live_auction($id,$product_id)
	{
		$query = $this->db->get_where('auction',array('id'=>$id,'product_id'=>$product_id,'status'=>'Live'));

		return $query->num_rows();
	}
	
	public function get_users_used_fee($auc_id)
	{
		$this->db->select('user_id,SUM(click_cost) balance');
		$this->db->group_by('user_id');
		$query = $this->db->get_where('user_bids',array('auc_id'=>$auc_id));
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_auction_details_byid($id)
	{
		$this->db->select('auc_det.*,lang.lang_name,lang.short_code');
		$this->db->from('auction_details auc_det');
		$this->db->join('language lang', 'lang.id = auc_det.lang_id', 'left');		
		$this->db->where(array('auc_det.auc_id'=>$id));
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function insert_record()
	{
		
		$start_date_gmt=$this->general->convert_gmt_time($this->general->change_date_time_format_satndard($this->input->post('start_date')));
		$end_date_gmt=$this->general->convert_gmt_time($this->general->change_date_time_format_satndard($this->input->post('end_date')));




		$product_id = $this->general->random_number();
		
		//set auction info
		$data = array(
			'cat_id' => $this->input->post('cat_id', TRUE),
               'product_id' => $product_id,
			   'price' => $this->input->post('price', TRUE),
               'shipping_cost' => $this->input->post('shipping_cost', TRUE),
               'bid_fee' => $this->input->post('bid_fee', TRUE),

			   // 'reset_time' => $this->input->post('reset_time', TRUE),
			   'start_date' => $start_date_gmt,
			   'end_date' => $end_date_gmt,
			   // 'is_bidbutler' => $this->input->post('is_bidbutler', TRUE),	
			   'is_display' => $this->input->post('is_display', TRUE),
			   'is_featured' => $this->input->post('is_featured', TRUE),
			   // 'image1' => $this->image_name_path1,
			   // 'image2' => $this->image_name_path2,
			   // 'image3' => $this->image_name_path3,
			   // 'image4' => $this->image_name_path4,
			   // 'is_bid_package' => $this->input->post('is_bid_package', TRUE),
			   'is_buy_now' => $this->input->post('is_buy_now', TRUE),
			   'no_qty' => $this->input->post('no_qty', TRUE),
			   'buy_now_price' => $this->input->post('buy_now_price'),
			   'sms_code'=>$this->input->post('sms_code',TRUE),
			   'last_update' => $this->general->get_gmt_time('time')
			   
            );

		$this->db->insert('auction', $data); 
		
		$insert_id = $this->db->insert_id();
			if($insert_id)
			{
			//insert different language record into auction details table
				for($i=0; $i<count($this->input->post('lang_id')); $i++)
				{
					$all_lang_id = $this->input->post('lang_id');
					$lang_id = $all_lang_id[$i];
					
					$name = $this->input->post('name', TRUE);
					$auction_name = $name[$lang_id];
					
					$description = $this->input->post('description', TRUE);	
					$auction_description = $description[$lang_id];
					
					$page_title = $this->input->post('page_title', TRUE);
					$auction_page_title = $page_title[$lang_id];
					
					$meta_key = $this->input->post('meta_key', TRUE);
					$auction_meta_key = $meta_key[$lang_id];
					
					$meta_desc = $this->input->post('meta_desc', TRUE);
					$auction_meta_desc = $meta_desc[$lang_id];
					//set auction details info
					$auc_details = array(
					   'auc_id' => $insert_id,
					   'lang_id' => $lang_id,
					   'name' => $auction_name,
					   'description' => $auction_description,
					   'page_title' => $auction_page_title,
					   'meta_keys' => $auction_meta_key,
					   'meta_desc' => $auction_meta_desc		   
					);
					
					$this->db->insert('auction_details', $auc_details); 
				}

			$product_code = $this->input->post('pcodeimg',TRUE);
			$query = $this->db->get_where('product_images_temp',array('product_code'=>$product_code));
			// echo $this->db->last_query();
			if ($query->num_rows() > 0)
			{ 
// echo 'here';exit;
				$tmp_images =  $query->result();
				$img_cnt=1;
				$image_data = array();
	
				foreach($tmp_images as $img){
					
					//echo $img->image; 
					$source_img = './'.AUCTION_TEMP_PATH.''.$img->image;
					$destination_img = './'.AUCTION_IMG_PATH.''.$img->image;
					
					if(file_exists($source_img)){
						$movefile = copy($source_img, $destination_img); //move_uploaded_file($filename, $dest);
						if($movefile){
							//var_dump($movefile);
							//generate new name for product image
							
							$path_info = pathinfo($destination_img);

							$image_ext = $path_info['extension'];
							
							$new_image_name = $img->image;

							$image_data['image'.$img_cnt.''] = $new_image_name;
							
							$this->resize_image($img->image, 'thumb_'.$new_image_name,330,250); 
							
							// $this->resize_image(AUCTION_IMG_PATH, $img->image, $new_image_name,330,250);



							@unlink(AUCTION_TEMP_PATH.''.$img->image);
							// @unlink(PRODUCT_IMAGE_PATH.''.$img->image);
							
							//push image details into array
							
						}
					}
					$img_cnt++;
				}
// print_r($image_data);exit;

				$this->db->update('auction', $image_data,array('id'=>$insert_id)); 
				//now delete temp images from database
				$query = $this->db->delete('product_images_temp',array('product_code'=>$product_code));
				
			}



				//insert auction record in the XML file
				// $this->add_auction_xml($insert_id);
			}

	}


	public function insert_record_vote()
	{
		
	
		$product_id = $this->general->random_number();
		
		//set auction info
		$data = array(
				'cat_id' => $this->input->post('cat_id', TRUE),
               'product_id' => $product_id,
			   'price' => $this->input->post('price', TRUE),
               'shipping_cost' => $this->input->post('shipping_cost', TRUE),
               'bid_fee' => $this->input->post('bid_fee', TRUE),
               'end_day'=>$this->input->post('end_day',TRUE),
               'end_hour'=>$this->input->post('end_hour',TRUE),
               'end_minute'=>$this->input->post('end_minute',TRUE),
               'is_display' => $this->input->post('is_display', TRUE),
               'auc_type'=>'vote',
               'sms_code'=>$this->input->post('sms_code',TRUE),
			   // 'image1' => $this->image_name_path1,
			   // 'image2' => $this->image_name_path2,
			   // 'image3' => $this->image_name_path3,
			   // 'image4' => $this->image_name_path4,
			   'is_buy_now' => $this->input->post('is_buy_now', TRUE),
			   'no_qty' => $this->input->post('no_qty', TRUE),
			   //'buy_now_price' => $this->input->post('buy_now_price'),
			   'last_update' => $this->general->get_gmt_time('time'),
			    'status'=>'Pending',
			   
            );

		$this->db->insert('auction', $data); 
		
		$insert_id = $this->db->insert_id();
			if($insert_id)
			{
			//insert different language record into auction details table
				for($i=0; $i<count($this->input->post('lang_id')); $i++)
				{
					$all_lang_id = $this->input->post('lang_id');
					$lang_id = $all_lang_id[$i];
					
					$name = $this->input->post('name', TRUE);
					$auction_name = $name[$lang_id];
					
					$description = $this->input->post('description', TRUE);	
					$auction_description = $description[$lang_id];
					
					$page_title = $this->input->post('page_title', TRUE);
					$auction_page_title = $page_title[$lang_id];
					
					$meta_key = $this->input->post('meta_key', TRUE);
					$auction_meta_key = $meta_key[$lang_id];
					
					$meta_desc = $this->input->post('meta_desc', TRUE);
					$auction_meta_desc = $meta_desc[$lang_id];
					//set auction details info
					$auc_details = array(
					   'auc_id' => $insert_id,
					   'lang_id' => $lang_id,
					   'name' => $auction_name,
					   'description' => $auction_description,
					   'page_title' => $auction_page_title,
					   'meta_keys' => $auction_meta_key,
					   'meta_desc' => $auction_meta_desc		   
					);
					
					$this->db->insert('auction_details', $auc_details); 
				}
				//insert auction record in the XML file
				// $this->add_auction_xml($insert_id);
			}

	}
	
	public function update_record($id)
	{
		//set auction info
	$start_date_gmt=$this->general->convert_gmt_time($this->general->change_date_time_format_satndard($this->input->post('start_date')));
	$end_date_gmt=$this->general->convert_gmt_time($this->general->change_date_time_format_satndard($this->input->post('end_date')));


		$data = array(
			'cat_id' => $this->input->post('cat_id', TRUE),
			   'price' => $this->input->post('price', TRUE),
               'shipping_cost' => $this->input->post('shipping_cost', TRUE),
               'bid_fee' => $this->input->post('bid_fee', TRUE),
               // 'reset_time' => $this->input->post('reset_time', TRUE),
			   'start_date' => $start_date_gmt,
			   'end_date' => $end_date_gmt,
			   // 'is_bidbutler' => $this->input->post('is_bidbutler', TRUE),	
			   'is_display' => $this->input->post('is_display', TRUE),
			    'is_featured' => $this->input->post('is_featured', TRUE),
			   // 'is_bid_package' => $this->input->post('is_bid_package', TRUE),
			   'is_buy_now' => $this->input->post('is_buy_now', TRUE),
			    'no_qty' => $this->input->post('no_qty', TRUE),
				'buy_now_price' => $this->input->post('buy_now_price'),
			    'sms_code'=>$this->input->post('sms_code',TRUE),
			   'last_update' => $this->general->get_gmt_time('time'),

			   
            );
		//only if new flag is uploaded
		//image1
		if(isset($this->image_name_path1) && $this->image_name_path1 !="")
		{
			//@unlink('./'.$this->input->post('flag_old'));
			$data['image1'] = $this->image_name_path1;
		}
		//only if new flag is uploaded
		//image2
		if(isset($this->image_name_path2) && $this->image_name_path2 !="")
		{
			//@unlink('./'.$this->input->post('flag_old'));
			$data['image2'] = $this->image_name_path2;
		}
		//only if new flag is uploaded
		//image3
		if(isset($this->image_name_path3) && $this->image_name_path3 !="")
		{
			//@unlink('./'.$this->input->post('flag_old'));
			$data['image3'] = $this->image_name_path3;
		}
		//only if new flag is uploaded
		//image4
		if(isset($this->image_name_path4) && $this->image_name_path4 !="")
		{
			//@unlink('./'.$this->input->post('flag_old'));
			$data['image4'] = $this->image_name_path4;
		}
		
		//print_r($data);exit;
		$this->db->where('id', $this->input->post('id', TRUE));
		$this->db->update('auction', $data);
		
		//insert auction record in the XML file
				// $this->update_auction_xml($this->input->post('id', TRUE));
				
			//insert different language record into auction details table
				for($i=0; $i<count($this->input->post('lang_id')); $i++)
				{
					$all_lang_id = $this->input->post('lang_id');
					$lang_id = $all_lang_id[$i];
					
					$name = $this->input->post('name', TRUE);
					$auction_name = $name[$lang_id];
					
					$description = $this->input->post('description', TRUE);	
					$auction_description = $description[$lang_id];
					
					$page_title = $this->input->post('page_title', TRUE);
					$auction_page_title = $page_title[$lang_id];
					
					$meta_key = $this->input->post('meta_key', TRUE);
					$auction_meta_key = $meta_key[$lang_id];
					
					$meta_desc = $this->input->post('meta_desc', TRUE);
					$auction_meta_desc = $meta_desc[$lang_id];
					
					$auc_details_id = $this->input->post('auc_details_id', TRUE);
					$auc_details_id = $auc_details_id[$i];
					
					//set auction details info
					$auc_details = array(					   
					   'name' => $auction_name,
					   'description' => $auction_description,
					   'page_title' => $auction_page_title,
					   'meta_keys' => $auction_meta_key,
					   'meta_desc' => $auction_meta_desc		   
					);
					
					$this->db->where('id', $auc_details_id);
					$this->db->update('auction_details', $auc_details);
				}

			$product_code = $this->input->post('pcodeimg',TRUE);
			$query = $this->db->get_where('product_images_temp',array('product_code'=>$product_code));
			// echo $this->db->last_query();
			if ($query->num_rows() > 0)
			{ 
// echo 'here';exit;
				$tmp_images =  $query->result();
				
				$img_cnt=1;
				$image_data = array();
				$img_i = 0;
				$checkEmptyField = $this->checkImageEmptyField($id);				
				foreach($tmp_images as $img){
					
					//echo $img->image; 
					$source_img = './'.AUCTION_TEMP_PATH.''.$img->image;
					$destination_img = './'.AUCTION_IMG_PATH.''.$img->image;
					
					if(file_exists($source_img)){
						
						$movefile = copy($source_img, $destination_img); //move_uploaded_file($filename, $dest);
						if($movefile){
							//var_dump($movefile);
							//generate new name for product image
							$name = 'image'.$img_cnt;
							$name_upper = 'image'.($img_cnt+1);
							$path_info = pathinfo($destination_img);

							$image_ext = $path_info['extension'];
							
							$new_image_name = $img->image;

							
				
							// $this->db->update('auction',array($checkEmptyField=>$new_image_name),array('id'=>$id));
							
							$image_data[$checkEmptyField[$img_i]] = $new_image_name;
							$img_i++;
							$this->resize_image($img->image, 'thumb_'.$new_image_name,330,250); 
							
							// $this->resize_image(AUCTION_IMG_PATH, $img->image, $new_image_name,330,250);



							@unlink(AUCTION_TEMP_PATH.''.$img->image);
							// @unlink(PRODUCT_IMAGE_PATH.''.$img->image);
							
							//push image details into array
							
						}
					}
					$img_cnt++;
				}
 //print_r($image_data);exit;
				$this->db->update('auction', $image_data,array('id'=>$id)); 
				$query = $this->db->delete('product_images_temp',array('product_code'=>$product_code));
				
			}



	}

	

	public function update_record_vote($id)
	{
		
		$data = array(
			'cat_id' => $this->input->post('cat_id', TRUE),
			   'price' => $this->input->post('price', TRUE),
               'shipping_cost' => $this->input->post('shipping_cost', TRUE),
               'bid_fee' => $this->input->post('bid_fee', TRUE),
			   'is_display' => $this->input->post('is_display', TRUE),
			   'is_buy_now' => $this->input->post('is_buy_now', TRUE),
			    'no_qty' => $this->input->post('no_qty', TRUE),
			    'sms_code'=>$this->input->post('sms_code',TRUE),
			   'last_update' => $this->general->get_gmt_time('time')
			   
            );
		//only if new flag is uploaded
		//image1
		if(isset($this->image_name_path1) && $this->image_name_path1 !="")
		{
			//@unlink('./'.$this->input->post('flag_old'));
			$data['image1'] = $this->image_name_path1;
		}
		//only if new flag is uploaded
		//image2
		if(isset($this->image_name_path2) && $this->image_name_path2 !="")
		{
			//@unlink('./'.$this->input->post('flag_old'));
			$data['image2'] = $this->image_name_path2;
		}
		//only if new flag is uploaded
		//image3
		if(isset($this->image_name_path3) && $this->image_name_path3 !="")
		{
			//@unlink('./'.$this->input->post('flag_old'));
			$data['image3'] = $this->image_name_path3;
		}
		//only if new flag is uploaded
		//image4
		if(isset($this->image_name_path4) && $this->image_name_path4 !="")
		{
			//@unlink('./'.$this->input->post('flag_old'));
			$data['image4'] = $this->image_name_path4;
		}
		
		//print_r($data);exit;
		$this->db->where('id', $this->input->post('id', TRUE));
		$this->db->update('auction', $data);
		
		//insert auction record in the XML file
				// $this->update_auction_xml($this->input->post('id', TRUE));
				
			//insert different language record into auction details table
				for($i=0; $i<count($this->input->post('lang_id')); $i++)
				{
					$all_lang_id = $this->input->post('lang_id');
					$lang_id = $all_lang_id[$i];
					
					$name = $this->input->post('name', TRUE);
					$auction_name = $name[$lang_id];
					
					$description = $this->input->post('description', TRUE);	
					$auction_description = $description[$lang_id];
					
					$page_title = $this->input->post('page_title', TRUE);
					$auction_page_title = $page_title[$lang_id];
					
					$meta_key = $this->input->post('meta_key', TRUE);
					$auction_meta_key = $meta_key[$lang_id];
					
					$meta_desc = $this->input->post('meta_desc', TRUE);
					$auction_meta_desc = $meta_desc[$lang_id];
					
					$auc_details_id = $this->input->post('auc_details_id', TRUE);
					$auc_details_id = $auc_details_id[$i];
					
					//set auction details info
					$auc_details = array(					   
					   'name' => $auction_name,
					   'description' => $auction_description,
					   'page_title' => $auction_page_title,
					   'meta_keys' => $auction_meta_key,
					   'meta_desc' => $auction_meta_desc		   
					);
					
					$this->db->where('id', $auc_details_id);
					$this->db->update('auction_details', $auc_details);
				}
	}

	
	
	public function copy_record()
	{
		//get random 10 numeric degit		
		$start_date_gmt=$this->general->convert_gmt_time($this->general->change_date_time_format_satndard($this->input->post('start_date')));
		$end_date_gmt=$this->general->convert_gmt_time($this->general->change_date_time_format_satndard($this->input->post('end_date')));

		$product_id = $this->general->random_number();
		
		//set auction info
		$data = array(
			  'cat_id' => $this->input->post('cat_id', TRUE),
               'product_id' => $product_id,
			   'price' => $this->input->post('price', TRUE),
               'shipping_cost' => $this->input->post('shipping_cost', TRUE),
               'bid_fee' => $this->input->post('bid_fee', TRUE),
			   // 'reset_time' => $this->input->post('reset_time', TRUE),
			   'start_date' => $start_date_gmt,
			   'end_date' => $end_date_gmt,
			   // 'is_bidbutler' => $this->input->post('is_bidbutler', TRUE),	
			   'is_display' => $this->input->post('is_display', TRUE),
			    'is_featured' => $this->input->post('is_featured', TRUE),				   
			   // 'image1' => $this->image_name_path1,
			   // 'image2' => $this->image_name_path2,
			   // 'image3' => $this->image_name_path3,
			   // 'image4' => $this->image_name_path4,
			   // 'is_bid_package' => $this->input->post('is_bid_package', TRUE),
			   'is_buy_now' => $this->input->post('is_buy_now', TRUE),
			   'no_qty' => $this->input->post('no_qty', TRUE),
			   'buy_now_price' => $this->input->post('buy_now_price'),
			   'sms_code'=>$this->input->post('sms_code',TRUE),
			   'last_update' => $this->general->get_gmt_time('time'),
			   
            );
		
		$this->db->insert('auction', $data); 
		
		$insert_id = $this->db->insert_id();
			if($insert_id)
			{
			//insert different language record into auction details table
				for($i=0; $i<count($this->input->post('lang_id')); $i++)
				{
					$all_lang_id = $this->input->post('lang_id');
					$lang_id = $all_lang_id[$i];
					
					$name = $this->input->post('name', TRUE);
					$auction_name = $name[$lang_id];
					
					$description = $this->input->post('description', TRUE);	
					$auction_description = $description[$lang_id];
					
					$page_title = $this->input->post('page_title', TRUE);
					$auction_page_title = $page_title[$lang_id];
					
					$meta_key = $this->input->post('meta_key', TRUE);
					$auction_meta_key = $meta_key[$lang_id];
					
					$meta_desc = $this->input->post('meta_desc', TRUE);
					$auction_meta_desc = $meta_desc[$lang_id];
					
					
					//set auction details info
					$auc_details = array(
					   'auc_id' => $insert_id,
					   'lang_id' => $lang_id,
					   'name' => $auction_name,
					   'description' => $auction_description,
					   'page_title' => $auction_page_title,
					   'meta_keys' => $auction_meta_key,
					   'meta_desc' => $auction_meta_desc		   
					);
					
					$this->db->insert('auction_details', $auc_details); 
				}
			// $product_code = $this->input->post('pcodeimg',TRUE);
			$this->db->select('id,image1,image2,image3,image4,image5,image6');
			$query = $this->db->get_where('auction',array('id'=>$this->product_original));
			// echo $this->db->last_query();
			if ($query->num_rows() > 0)
			{ 
// echo 'here';exit;
				$tmp_images =  $query->row();

				// echo '<pre>';
				//print_r($tmp_images);exit;
				$image_data = array();
				$count = $this->count_valid_products($this->product_original);
				for($i = 1; $i<=$count; $i++)
				{

					$name = 'image'.$i;
					// if($this->checkImage($name,$this->product_original) == true){
					$source_img = './'.AUCTION_IMG_PATH.''.$tmp_images->$name;
					$destination_img = './'.AUCTION_IMG_PATH.''.$i.$tmp_images->$name;		

					if(file_exists($source_img)){
					$movefile = copy($source_img, $destination_img);
					 //move_uploaded_file($filename, $dest);
					if($movefile){	
					$path_info = pathinfo($destination_img);
					$new_image_name = $i.$tmp_images->$name;
					$image_ext = $path_info['extension'];
					$image_data['image'.$i.''] = $new_image_name;
					$this->resize_image($tmp_images->$name, 'thumb_'.$new_image_name,330,250); 
					}
					}	
				// }

				}
				//print_r($image_data);exit;
				$this->db->update('auction', $image_data,array('id'=>$insert_id));
				
				
			}

			$product_code = $this->input->post('pcodeimg',TRUE);
			$query = $this->db->get_where('product_images_temp',array('product_code'=>$product_code));
			// echo $this->db->last_query();
			if ($query->num_rows() > 0)
			{ 
// echo 'here';exit;
				$tmp_images =  $query->result();
				$img_cnt=1;
				$image_data = array();
				$img_i = 0;
				$checkEmptyField = $this->checkImageEmptyField($insert_id);
				
				foreach($tmp_images as $img){
					
					//echo $img->image; 
					$source_img = './'.AUCTION_TEMP_PATH.''.$img->image;
					$destination_img = './'.AUCTION_IMG_PATH.''.$img->image;
					
					if(file_exists($source_img)){
						$movefile = copy($source_img, $destination_img); //move_uploaded_file($filename, $dest);
						if($movefile){
							//var_dump($movefile);
							//generate new name for product image
							$name = 'image'.$img_cnt;
							$name_upper = 'image'.($img_cnt+1);
							$path_info = pathinfo($destination_img);

							$image_ext = $path_info['extension'];
							
							$new_image_name = $img->image;
											
							// $this->db->update('auction',array($checkEmptyField=>$new_image_name),array('id'=>$id));
							
							$image_data[$checkEmptyField[$img_i]] = $new_image_name;
							$img_i++;
							$this->resize_image($img->image, 'thumb_'.$new_image_name,330,250); 
							
							// $this->resize_image(AUCTION_IMG_PATH, $img->image, $new_image_name,330,250);

							@unlink(AUCTION_TEMP_PATH.''.$img->image);
							// @unlink(PRODUCT_IMAGE_PATH.''.$img->image);
							
							//push image details into array
							
						}
					}
					$img_cnt++;
				}
// print_r($image_data);exit;
				$this->db->update('auction', $image_data,array('id'=>$insert_id)); 
				$query = $this->db->delete('product_images_temp',array('product_code'=>$product_code));
				
			}

				//insert auction record in the XML file
				// $this->add_auction_xml($insert_id);
			}

	}

	public function checkImage($columnname,$id){

		$this->db->select('*');
		$this->db->from('auction');
		$this->db->where('id',$id);
		$query = $this->db->get('');
		$data = $query->row();
		for($i = 1; $i<5; $i++)
		{
		 $name = 'image'.$i;
		if($data->$name != ''){
			return true;
			
		}
		return false;

	}


	}	


	public function copy_record_vote()
	{
		//get random 10 numeric degit		
		$start_date_gmt=$this->general->convert_gmt_time($this->general->change_date_time_format_satndard($this->input->post('start_date')));
		$end_date_gmt=$this->general->convert_gmt_time($this->general->change_date_time_format_satndard($this->input->post('end_date')));

		$product_id = $this->general->random_number();
		
		//set auction info
		$data = array(
				'cat_id' => $this->input->post('cat_id', TRUE),
               'product_id' => $product_id,
			   'price' => $this->input->post('price', TRUE),
               'shipping_cost' => $this->input->post('shipping_cost', TRUE),
               'bid_fee' => $this->input->post('bid_fee', TRUE),
               'end_day'=>$this->input->post('end_day',TRUE),
               'end_hour'=>$this->input->post('end_hour',TRUE),
               'end_minute'=>$this->input->post('end_minute',TRUE),
               'is_display' => $this->input->post('is_display', TRUE),
               'auc_type'=>'vote',
               'sms_code'=>$this->input->post('sms_code',TRUE),
			   'image1' => $this->image_name_path1,
			   'image2' => $this->image_name_path2,
			   'image3' => $this->image_name_path3,
			   'image4' => $this->image_name_path4,
			   'is_buy_now' => $this->input->post('is_buy_now', TRUE),
			   'no_qty' => $this->input->post('no_qty', TRUE),
			   'last_update' => $this->general->get_gmt_time('time'),
			   'status'=>'Pending',
			   
            );
		
		$this->db->insert('auction', $data); 
		
		$insert_id = $this->db->insert_id();
			if($insert_id)
			{
			//insert different language record into auction details table
				for($i=0; $i<count($this->input->post('lang_id')); $i++)
				{
					$all_lang_id = $this->input->post('lang_id');
					$lang_id = $all_lang_id[$i];
					
					$name = $this->input->post('name', TRUE);
					$auction_name = $name[$lang_id];
					
					$description = $this->input->post('description', TRUE);	
					$auction_description = $description[$lang_id];
					
					$page_title = $this->input->post('page_title', TRUE);
					$auction_page_title = $page_title[$lang_id];
					
					$meta_key = $this->input->post('meta_key', TRUE);
					$auction_meta_key = $meta_key[$lang_id];
					
					$meta_desc = $this->input->post('meta_desc', TRUE);
					$auction_meta_desc = $meta_desc[$lang_id];
					
					
					//set auction details info
					$auc_details = array(
					   'auc_id' => $insert_id,
					   'lang_id' => $lang_id,
					   'name' => $auction_name,
					   'description' => $auction_description,
					   'page_title' => $auction_page_title,
					   'meta_keys' => $auction_meta_key,
					   'meta_desc' => $auction_meta_desc		   
					);
					
					$this->db->insert('auction_details', $auc_details); 
				}
				//insert auction record in the XML file
				// $this->add_auction_xml($insert_id);
			}

	}
	
	
	public function add_auction_xml($auc_id)
	{
			$filename="./aucstatus.xml";
			//check XML file is in root or not
			if (!file_exists($filename)) { echo "There is not a aucstatus.xml file in the root directory.";exit;}
					 
			$this->db->select('product_id,end_date,status,current_winner_name,current_winner_amount,shipping_cost,price');
			$query = $this->db->get_where('auction',array('id'=>$auc_id));
			
			if ($query->num_rows() > 0)
				{$auc_data = $query->row_array(); }
			else
				{echo "There is no record for auction id: ".$auc_id;exit;}
			
			//calculate total price and saving in a auction
			$auction_price = $auc_data['price'];

			$shipping_cost = $auc_data['shipping_cost'];
			$curren_winning_amot = $auc_data['current_winner_amount'];
			$total_price = $curren_winning_amot + $shipping_cost;
			
			$saving_per = number_format( ( ($auction_price - $total_price) / $auction_price ) * 100 ,'2','.','');


			$xml = simplexml_load_file($filename);
			$new_node = $xml->addChild('auc');
			$new_node->addChild('id', $auc_data['product_id']);			
			$new_node->addChild('timer', strtotime($auc_data['end_date']));
			$new_node->addChild('status', $auc_data['status']);
			$new_node->addChild('user', $auc_data['current_winner_name']);
			$new_node->addChild('amt', $auc_data['current_winner_amount']);
			$new_node->addChild('total_amt', $total_price);			
			$new_node->addChild('saving', $saving_per);
			$new_node->addChild('bid_type', 'N');
			
			$xml->asXML($filename);
	}
	
	public function update_auction_xml($auc_id)
	{
		$update = '';
		$filename="./aucstatus.xml";
			//check XML file is in root or not
			if (!file_exists($filename)) { echo "There is not a aucstatus.xml file in the root directory.";exit;}
				
					
			$this->db->select('product_id,end_date,status,current_winner_name,current_winner_amount,shipping_cost,price');
			$query = $this->db->get_where('auction',array('id'=>$auc_id));
			//print_r('test');exit;
			if ($query->num_rows() > 0)
				{$auc_data = $query->row_array(); }
			else
				{echo "There is no record for auction id: ".$auc_id;exit;}
			
			//print_r($auc_data);exit;
			//calculate total price and saving in a auction
			$auction_price = $auc_data['price'];

			$shipping_cost = $auc_data['shipping_cost'];
			$curren_winning_amot = $auc_data['current_winner_amount'];
			$total_price = number_format($curren_winning_amot + $shipping_cost,'2','.','');
			
			$saving_per = number_format( ( ($auction_price - $total_price) / $auction_price ) * 100 ,'2','.','');
			
			
				$xml = simplexml_load_file($filename);

				//Edit particular node by product id.
				$node = $xml->xpath('/auction/auc[id="' . $auc_data['product_id'] . '"]');
		
				if(sizeof($node) > 0) 
				{					
					$update = 'yes';					
					$node[0]->id = $auc_data['product_id'];
					$node[0]->timer = strtotime($auc_data['end_date']);
					$node[0]->status = $auc_data['status'];
					$node[0]->user = $auc_data['current_winner_name'];
					$node[0]->amt = $auc_data['current_winner_amount'];
					$node[0]->total_amt = $total_price;
					$node[0]->saving = $saving_per;
					$node[0]->bid_type = 'N';
				}	
			
				
				if($update == '')
				{

					$new_node = $xml->addChild('auc');
					$new_node->addChild('id', $auc_data['product_id']);			
					$new_node->addChild('timer', strtotime($auc_data['end_date']));
					$new_node->addChild('status', $auc_data['status']);
					$new_node->addChild('user', $auc_data['current_winner_name']);
					$new_node->addChild('amt', $auc_data['current_winner_amount']);
					$new_node->addChild('total_amt', $total_price);			
					$new_node->addChild('saving', $saving_per);
					$new_node->addChild('bid_type', 'N');
				}
				
				$xml->asXML($filename);	
			
	}
	
	public function delete_auction_xml($product_id)
	{
		$filename="./aucstatus.xml";
			$xml = new SimpleXMLElement($filename, NULL, TRUE);
            $node = $xml->xpath('/auction/auc[id="' . $product_id . '"]');
            if(sizeof($node) > 0) 
			{
            	unset($node[0][0]);
                $xml->asXML($filename);
            }	
	}
	
	public function update_XML_status($product_id)
	{
		$update = '';
		$filename="./aucstatus.xml";
		
		//check XML file is in root or not
		if(is_file($filename))
		{			
			if($xml = simplexml_load_file($filename))
			{
				//Edit particular node by product id.
				$node = $xml->xpath('/auction/auc[id="' . $product_id . '"]');
		
				if(sizeof($node) > 0) 
				{
					$node[0]->status = 'Cancel';
					$node[0]->user='None';
					$node[0]->amt='0.00';
				}
				
				$xml->asXML($filename);	
			}
		}
	}
	
	public function update_auction_status($auc_id,$status)
	{
		$data=array('status'=>$status);
		$this->db->where('id',$auc_id);
		$this->db->update('auction', $data);
	}
	
	public function update_user_balance($return_credit,$user_id)
	{		
		//get user current balance
		$this->db->select('balance');
		$query = $this->db->get_where('members', array('id'=>$user_id));
		$user_balance = $query->row();
		
		$user_total_balance = $user_balance->balance+$return_credit;
		
		//update user balance
		$data=array('balance'=>$user_total_balance);
		$this->db->where('id',$user_id);
		$this->db->update('members', $data);
	}
	
	public function update_refund_transaction($user_id,$auc_id,$total_amount,$item_name)
	{
		$inserting_transaction=array('user_id'=>$user_id,
								'auc_id'=>$auc_id,
								 'credit_get'=>$total_amount,
								'credit_debit'=>'CREDIT',
								'transaction_name'=>$item_name,
								'transaction_date'=>$this->general->get_local_time('time'),
								'transaction_status' => 'Completed', 	
								'transaction_type'=>'penny'
											 );
		$this->db->insert('transaction', $inserting_transaction);	
	}
	
	public function get_member_info($user_id)
	{
		$query = $this->db->get_where('members',array('id'=>$user_id));

		if ($query->num_rows() > 0)
		{
		  return $query->row(); 
		} 

	}
	
	public function get_auction_byproductid($product_id,$user_lang_id)
	{
		//get language id from configure file
		$lang_id = $user_lang_id;
		
		$this->db->select('a.*,ad.*');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$array = array('product_id' => $product_id,'lang_id' => $lang_id);
		$this->db->where($array); 
		$this->db->order_by("end_date", "asc"); 

		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$data = $query->row();
			$query->free_result();
			return $data;
		}
	}
	
	public function send_auction_cancel_notification_user($product_id,$user_id,$return_credit)
	{
		//load email library
    	$this->load->library('email');
					
		$this->load->model('email_model');		
		
		//get winner info
		$user_info = $this->get_member_info($user_id);
		
		$user_email = $user_info->email;
		$user_lang_id = $user_info->lang_id;
		
		//Get auction info
		$auction_info = $this->get_auction_byproductid($product_id,$user_lang_id);
		$product_name = $auction_info->name;
		
		//Get auction closed template for winner
			$template=$this->email_model->get_email_template("auction_cancel_notification_user",$user_lang_id);
		if(empty($template))
			$template=$this->email_model->get_email_template("auction_cancel_notification_user",DEFAULT_LANG_ID);
			

        $subject=$template['subject'];
        $emailbody=$template['email_body'];
		
				//parse email
                $parseElement=array("USERNAME"=>$user_info->user_name,
                                    "SITENAME"=>SITE_NAME,
                                    "AUCTIONNAME"=>$product_name,									
                                    "AMOUNT"=>$return_credit,
                                    "DATE"=>$this->general->get_local_time('time'));

                $subject=$this->email_model->parse_email($parseElement,$subject);
                $emailbody=$this->email_model->parse_email($parseElement,$emailbody);
				
		//set the email things
//		$this->email->from(SYSTEM_EMAIL);
//		$this->email->to($user_email); 
//		$this->email->subject($subject);
//		$this->email->message($emailbody); 
//		$this->email->send();
                
                $this->netcoreemail_class->send_email(SYSTEM_EMAIL,$user_email,$subject,$emailbody);
	}
	

	public function check_exit_sms_code($sms_code,$auc_id)
	{
		$data = array();
				// $query = $this->db->get_where("members",array('email'=>$email,'id!='=>$id,'email!='=>''));
		$query = $this->db->get_where("auction",array('sms_code'=>$sms_code,'id!='=>$auc_id,'sms_code!='=>''));
		// echo $this->db->last_query();
		// exit;
		if ($query->num_rows() > 0) 
		{
			$data=$query->row();	
			return $data;			
		}
		return false;
	}

public function send_sms_notification($product_id,$user_id,$return_credit)
{
		//load email library
    	$this->load->library('email');
					
		$this->load->model('email_model');		
		
		//get winner info
		$user_info = $this->get_member_info($user_id);
		
		$user_email = $user_info->email;
		$user_lang_id = $user_info->lang_id;
		
		//Get auction info
		$auction_info = $this->get_auction_byproductid($product_id,$user_lang_id);
		$product_name = $auction_info->name;
		
		//Get auction closed template for winner
			$template=$this->email_model->get_email_template("auction_cancel_notification_user",$user_lang_id);
		if(empty($template))
			$template=$this->email_model->get_email_template("auction_cancel_notification_user",DEFAULT_LANG_ID);
			

        $subject=$template['subject'];
        $smsbody=$template['sms_body'];
		
				//parse email
                $parseElement=array("USERNAME"=>$user_info->user_name,
                                    "SITENAME"=>SITE_NAME,
                                    "AUCTIONNAME"=>$product_name,									
                                    "AMOUNT"=>$return_credit,
                                    "DATE"=>$this->general->get_local_time('time'));

                $subject=$this->email_model->parse_email($parseElement,$subject);
                $smsbody=$this->email_model->parse_email($parseElement,$smsbody);
				

	$this->checkmobi->sendSMS(CHECKMOBI_SMS_API_KEY,$user_info->mobile,$smsbody);			
}	

public function send_push_notification($product_id,$user_id,$return_credit)
{


    	$this->load->library('email');
					
		$this->load->model('email_model');		
		
		//get winner info
		$user_info = $this->get_member_info($user_id);
		
		$user_email = $user_info->email;
		$user_lang_id = $user_info->lang_id;
		
		//Get auction info
		$auction_info = $this->get_auction_byproductid($product_id,$user_lang_id);
		$product_name = $auction_info->name;
		
		//Get auction closed template for winner
			$template=$this->email_model->get_email_template("auction_cancel_notification_user",$user_lang_id);
		if(empty($template))
			$template=$this->email_model->get_email_template("auction_cancel_notification_user",DEFAULT_LANG_ID);
			

        $subject=$template['subject'];
        $push_body=$template['push_message_body'];
		
				//parse email
                $parseElement=array("USERNAME"=>$user_info->user_name,
                                    "SITENAME"=>SITE_NAME,
                                    "AUCTIONNAME"=>$product_name,									
                                    "AMOUNT"=>$return_credit,
                                    "DATE"=>$this->general->get_local_time('time'));

                $subject=$this->email_model->parse_email($parseElement,$subject);
                $push_body=$this->email_model->parse_email($parseElement,$push_body);
				
     $user_push = $this->general->get_device_id($user_info->push_id);
	$this->fcm->send($user_push,array('message'=>$push_body,'subject'=>$subject));	
}
	public function file_settings_do_upload_ajax($file, $location, $encrypt_filename='')
 	{
		$config['upload_path'] = './'.$location;   //file upload location
		$config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
		$config['remove_spaces'] = TRUE;  
		$config['max_size'] = '4200000';
		//$config['max_width'] = '3000';
		//$config['max_height'] = '3000';
		if($encrypt_filename='encrypt')
		{
			//$config['file_name'] = $new_file_name;
			$config['encrypt_name'] = TRUE;
		}
		$this->upload->initialize($config);
		//print_r($_FILES);
		
		$this->upload->do_upload($file);
		if($this->upload->display_errors())
		{
			$this->error_img = $this->upload->display_errors();
			//echo $this->error_img;
			return false;
		}
		else
		{
			$data = $this->upload->data();
			return $data;
		}
	}

	public function get_producttemp_images($product_code){
		
		$query = $this->db->get_where('product_images_temp', array('product_code' => $product_code));
// echo $this->db->last_query();
		if( $query->num_rows() > 0 ){
			return $query->result();
		}
		return array();
	}

	public function get_product_images_by_product_id($product_id)
	{
		$this->db->select('id,image1,image2,image3,image4,image5,image6');
		// $this->db->where('imag1')
		$query = $this->db->get_where('auction',array('id'=>$product_id));

		if ($query->num_rows() > 0){
		   return $query->row();
		} 
		return false;
	}

	public function count_valid_products($auction_id)
	{
		$this->db->select('*');
		// $this->db->where('imag1')
		$query = $this->db->get_where('auction',array('id'=>$auction_id));
		$data = $query->row();
		$count = 0;
		for($i = 1; $i<7; $i++)
		{
		 $name = 'image'.$i;
		if($data->$name !=''){
			$count += 1;
			// break;
		}

	}
		return $count;
			

	}

	public function count_total_temp_images_by_product_code($product_code)
	{
		$this->db->where('product_code',$product_code);
		$this->db->from('product_images_temp');
		return $this->db->count_all_results();
	}

	public function checkImageEmptyField($id){
		$data_img = array();
		$this->db->select('*');
		$this->db->from('auction');
		$this->db->where('id',$id);
		$query = $this->db->get('');
		$data = $query->row();
		for($i = 1; $i<7; $i++)
		{
		 $name = 'image'.$i;
		if($data->$name ==''){
			$data_img[] = 'image'.$i;
			
		}

	}
		
		return $data_img;

	}	
	

}
