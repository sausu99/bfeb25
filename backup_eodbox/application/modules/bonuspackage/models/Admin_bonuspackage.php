<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_bonuspackage extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();	
	}
		
	public $validate_settings =  array(
			array('field' => 'name', 'label' => 'Bid Package Name ', 'rules' => 'required'),
			array('field' => 'bonus_points', 'label' => 'Bonuu Points', 'rules' => 'required'),
			array('field' => 'credits', 'label' => 'Bids', 'rules' => 'required|integer')
		);
		
	public function get_bid_package()
	{		
				 $this->db->order_by("bonus_points", "desc"); 
		$query = $this->db->get('bonuspackage');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}	
	
	public function get_bonuspackage_by_id($id)
	{
		$query = $this->db->get_where('bonuspackage',array('id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	public function insert_bonuspackage_record()
	{
		$data = array(
               'name' => $this->input->post('name', TRUE),
			   'bonus_points' => $this->input->post('bonus_points', TRUE),
			   'credits' => $this->input->post('credits', TRUE),
			   'last_update' => $this->general->get_local_time('time')
            );

		$this->db->insert('bonuspackage', $data); 

	}
	
	public function update_bonuspackage_record($id)
	{
		$data = array(
               'name' => $this->input->post('name', TRUE),
			   'bonus_points' => $this->input->post('bonus_points', TRUE),
			   'credits' => $this->input->post('credits', TRUE),
			   'last_update' => $this->general->get_local_time('time')
            );
			
		$this->db->where('id', $id);
		//echo $this->db->last_query();
		$this->db->update('bonuspackage', $data);

	}
	
	
	

}
