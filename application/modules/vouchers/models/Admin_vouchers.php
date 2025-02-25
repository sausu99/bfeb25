<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_vouchers extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();	
	}
		
	public $validate_settings =  array(
			array('field' => 'code', 'label' => 'Voucher Code', 'rules' => 'required|alpha_dash|min_length[10]|max_length[20]|is_unique[vouchers.code]'),
			array('field' => 'limit_number', 'label' => 'Limit', 'rules' => 'required|integer'),
			array('field' => 'limit_per_user', 'label' => 'Limit Per User', 'rules' => 'required|integer'),
			//array('field' => 'free_bids', 'label' => 'Free Bids', 'rules' => 'required|integer'),
			array('field' => 'extra_bids', 'label' => 'Extra Bids', 'rules' => 'required|integer'),
			array('field' => 'start_date', 'label' => 'Start Date', 'rules' => 'required'),
			array('field' => 'end_date', 'label' => 'End Date', 'rules' => 'required|callback_check_end_date')			
		);
	
	public $validate_settings_end =  array(
			array('field' => 'code', 'label' => 'Voucher Code', 'rules' => 'required|alpha_dash|min_length[10]|max_length[20]'),
			array('field' => 'limit_number', 'label' => 'Limit', 'rules' => 'required|integer'),
			array('field' => 'limit_per_user', 'label' => 'Limit Per User', 'rules' => 'required|integer'),
			//array('field' => 'free_bids', 'label' => 'Free Bids', 'rules' => 'required|integer'),
			array('field' => 'extra_bids', 'label' => 'Extra Bids', 'rules' => 'required|integer'),
			array('field' => 'start_date', 'label' => 'Start Date', 'rules' => 'required'),
			array('field' => 'end_date', 'label' => 'End Date', 'rules' => 'required|callback_check_end_date')			
		);
		
	public function get_all_vouchers()
	{		
		$query = $this->db->get('vouchers');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}	
	
	public function get_vouchers_by_id($id)
	{
		$query = $this->db->get_where('vouchers',array('id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	public function insert_record()
	{
		$data = array(
               'code' => $this->input->post('code'),
			   'limit_number' => $this->input->post('limit_number'),
			   'limit_per_user' => $this->input->post('limit_per_user'),
			   //'free_bids' => $this->input->post('free_bids'),
			   'extra_bids' => $this->input->post('extra_bids'),
			   'start_date' => $this->input->post('start_date'),
			   'end_date' => $this->input->post('end_date')			   
            );

		$this->db->insert('vouchers', $data); 

	}
	
	public function update_record($id)
	{
		$data = array(
               'code' => $this->input->post('code'),
			   'limit_number' => $this->input->post('limit_number'),
			   'limit_per_user' => $this->input->post('limit_per_user'),
			   //'free_bids' => $this->input->post('free_bids'),
			   'extra_bids' => $this->input->post('extra_bids'),
			   'start_date' => $this->input->post('start_date'),
			   'end_date' => $this->input->post('end_date')
            );
			
		$this->db->where('id', $id);
		//echo $this->db->last_query();
		$this->db->update('vouchers', $data);

	}
	
	
	

}
