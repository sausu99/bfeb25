<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_language_settings extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		//load CI library
			$this->load->library('form_validation');
	}
	
	public $validate_lang_settings =  array(	
			array('field' => 'lang_name', 'label' => 'Language Name', 'rules' => 'required|callback_duplicate_lang'),
			//array('field' => 'short_code', 'label' => 'Short Code', 'rules' => 'required|alpha|exact_length[2]'),
			//array('field' => 'currency_code', 'label' => 'Currency Code', 'rules' => 'required|alpha'),
			//array('field' => 'currency_sign', 'label' => 'Currency Sign', 'rules' => 'required'),
			//array('field' => 'exchange_rate', 'label' => 'Currency Exchange Rate', 'rules' => 'required|numeric')
			
		);
		
	public function file_settings_do_upload()
	{
				$config['upload_path'] = './'.FLAG_PATH;//define in constants
				$config['allowed_types'] = 'gif|jpg|png';
				$config['remove_spaces'] = TRUE;		
				$config['max_size'] = '100';
				$config['max_width'] = '16';
				$config['max_height'] = '11';

				// load upload library and set config				
				if(isset($_FILES['flag']['tmp_name']))
				{
					$this->upload->initialize($config);
					$this->upload->do_upload('flag');
				}		
	}
	public function get_lang_details()
	{		
				 $this->db->order_by("last_update", "desc"); 
		$query = $this->db->get('language');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_lang_details_for_others()
	{		
				 $this->db->select("id,lang_name"); 
				 $this->db->from("language");
				  $this->db->where("is_display","Yes");
				 $this->db->order_by("id", "ASC"); 
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_default_lang_id()
	{		
		
		$query = $this->db->get_where('language',array("default_lang" => "Yes"));

		if($query->num_rows() > 0)
		{
		  $row = $query->row(); 
		  return $row->id;
		} 

		return false;
	}
	
	public function get_lang_details_by_id($id)
	{
		$query = $this->db->get_where('language',array('id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	public function insert_lang_record()
	{
		$data = array(
               'lang_name' => $this->input->post('lang_name', TRUE),
              // 'short_code' => $this->input->post('short_code', TRUE),
               //'currency_code' => $this->input->post('currency_code', TRUE),
			   //'currency_sign' => $this->input->post('currency_sign', TRUE),
			   //'exchange_rate' => $this->input->post('exchange_rate', TRUE),
			   //'display_in' => $this->input->post('display_in', TRUE),	
				  'is_display' => $this->input->post('is_display', TRUE),				   
			   //'lang_flag' => $lang_flag,
			   'last_update' => $this->general->get_local_time('time')
			   
            );

		$this->db->insert('language', $data); 

	}
	
	//public function update_lang_record($id,$flag_full_path)
	public function update_lang_record($id)
	{
		$data = array(
               'lang_name' => $this->input->post('lang_name', TRUE),
              // 'short_code' => $this->input->post('short_code', TRUE),
               //'currency_code' => $this->input->post('currency_code', TRUE),
			   //'currency_sign' => $this->input->post('currency_sign', TRUE),
			    //'exchange_rate' => $this->input->post('exchange_rate', TRUE),	
				 //'display_in' => $this->input->post('display_in', TRUE),	
				  'is_display' => $this->input->post('is_display', TRUE),	
			   'last_update' => $this->general->get_local_time('time')
			   
            );

		//only if new flag is uploaded
		/*if(isset($flag_full_path) && $flag_full_path !="")
		{
			@unlink('./'.$this->input->post('flag_old'));
			$data['lang_flag'] = $flag_full_path;
		}*/
		
		$this->db->where('id', $id);
		//echo $this->db->last_query();
		$this->db->update('language', $data);

	}
	
	public function update_exchange_rate_from_yahoo_api($exchange_rate,$datetime,$currency_code)
	{
		$data = array(               
			    		'exchange_rate' => $exchange_rate,				 
			    		'last_update' => $datetime
            		  );
					  
		$this->db->where('currency_code', $currency_code);		
		$this->db->update('language', $data);

	}
	
	
	

}
