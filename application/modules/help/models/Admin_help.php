<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_help extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
			
	}

	public $validate_settings =  array(			
			array('field' => 'lang_id[]', 'label' => 'Language', 'rules' => 'required')
		);
	public $validate_settings_edit =  array(			
			array('field' => 'help_category', 'label' => 'Help Category', 'rules' => 'required'),
			array('field' => 'name', 'label' => 'Help Title', 'rules' => 'required'),
			array('field' => 'description', 'label' => 'Help Description', 'rules' => 'required')
		);
		
	public function get_help_lists($lang_id)
	{	
		$query = $this->db->get_where('help',array('lang_id'=>$lang_id));

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_help_byid($id)
	{
		$query = $this->db->get_where('help',array('id'=>$id));

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
					
					$hlep_category = $this->input->post('hlep_category', TRUE);
					$hlep_category_id = $hlep_category[$lang_id];
					
					$name = $this->input->post('name', TRUE);
					$help_name = $name[$lang_id];
					
					$description = $this->input->post('description', TRUE);	
					$help_description = $description[$lang_id];
					
					$is_display = $this->input->post('is_display', TRUE);	
					$help_is_display = $is_display[$lang_id];
					
					//set auction details info
					$array_data = array(					   
					   'lang_id' => $lang_id,
					   'help_cat_id' => $hlep_category_id,
					   'title' => $help_name,
					   'description' => $help_description,
					   'is_display' => $help_is_display,
					   'last_update' => $this->general->get_local_time('time')
					);
					
					$this->db->insert('help', $array_data); 
				}


	}
	
	public function update_record($id)
	{
		//set value
		$data = array(
			   'help_cat_id' => $this->input->post('help_category', TRUE),
			   'title' => $this->input->post('name', TRUE),
			   'description' => $this->input->post('description', TRUE),
			   'is_display' => $this->input->post('is_display', TRUE),
			   'last_update' => $this->general->get_local_time('time')
			   
            );
		$this->db->where('id', $this->input->post('id', TRUE));
		$this->db->update('help', $data);
		
		

	}
	
	public function get_help_category_byid($lang_id)
	{
		$query = $this->db->get_where('help_category',array('lang_id'=>$lang_id));

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	

}
