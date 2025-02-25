<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_help_category extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
			
	}

	public $validate_settings =  array(			
			array('field' => 'lang_id[]', 'label' => 'Language', 'rules' => 'required')
		);
	public $validate_settings_edit =  array(			
			array('field' => 'name', 'label' => 'Help Category Name', 'rules' => 'required')
		);
		
	public function get_help_category_lists($lang_id)
	{	
		$query = $this->db->get_where('help_category',array('lang_id'=>$lang_id));

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_help_category_byid($id)
	{
		$query = $this->db->get_where('help_category',array('id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	
	public function insert_record()
	{

			//insert different language record into auction details table
				for($i=0; $i<count($this->input->post('lang_id')); $i++)
				{
					$all_lang_id = $this->input->post('lang_id');
					$lang_id = $all_lang_id[$i];
					
					$name = $this->input->post('name', TRUE);
					$help_cat_name = $name[$lang_id];
					
					$is_display = $this->input->post('is_display', TRUE);	
					$help_cat_is_display = $is_display[$lang_id];
					
					//set auction details info
					$array_data = array(					   
					   'lang_id' => $lang_id,
					   'help_category_name' => $help_cat_name,
					   'is_display' => $help_cat_is_display,
					   'last_update' => $this->general->get_local_time('time')
					);

					$this->db->insert('help_category', $array_data); 
				}


	}
	
	public function update_record($id)
	{
		//set value
		$data = array(
			   'help_category_name' => $this->input->post('name', TRUE),
			   'is_display' => $this->input->post('is_display', TRUE),
			   'last_update' => $this->general->get_local_time('time')
			   
            );
		$this->db->where('id', $this->input->post('id', TRUE));
		$this->db->update('help_category', $data);
		
		

	}
	
	
	

}
