<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_faq extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
			
	}

	public $validate_settings =  array(			
			array('field' => 'lang_id[]', 'label' => 'Language', 'rules' => 'required')
		);
	public $validate_settings_edit =  array(			
			array('field' => 'help_category', 'label' => 'Auction Category', 'rules' => 'required'),
			array('field' => 'name', 'label' => 'Title', 'rules' => 'required'),
			array('field' => 'description', 'label' => 'Description', 'rules' => 'required')
		);
		
	public function get_help_lists($lang_id)
	{	
		$query = $this->db->get_where('auction_help',array('lang_id'=>$lang_id));
		
		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_help_byid($id)
	{
		$query = $this->db->get_where('auction_help',array('id'=>$id));

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
					   'auc_id' => $hlep_category_id,
					   'title' => $help_name,
					   'description' => $help_description,
					   'is_display' => $help_is_display,
					   'last_update' => $this->general->get_local_time('time')
					);
					
					$this->db->insert('auction_help', $array_data); 
				}


	}
	
	public function update_record($id)
	{
		//set value
		$data = array(
			   'auc_id' => $this->input->post('help_category', TRUE),
			   'title' => $this->input->post('name', TRUE),
			   'description' => $this->input->post('description', TRUE),
			   'is_display' => $this->input->post('is_display', TRUE),
			   'last_update' => $this->general->get_local_time('time')
			   
            );
		$this->db->where('id', $this->input->post('id', TRUE));
		$this->db->update('auction_help', $data);
		
		

	}
	
	public function get_auction_bylangid($lang_id,$type='lub')
	{
		$this->db->select('auc.id,auc_det.name,auc_det.lang_id');
		$this->db->from('auction auc');
		$this->db->join('auction_details auc_det', 'auc_det.auc_id = auc.id', 'left');
		$this->db->where('auc.status','Live');
		$this->db->where('auc.auc_type',$type);		
		$this->db->group_by('auc.id');
		$this->db->order_by("auc_det.name", "asc");
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	

}
