<?php  if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_model extends CI_Model {
	public function __construct() 
	{
		parent::__construct();
	}
	
	public function count_transaction()
	{
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		
		$this->db->select("T.*, M.user_name");
		$this->db->from('transaction T');
		$this->db->join('members M','M.id=T.user_id','LEFT');
		
		
		if($start_date && $end_date)
		{
			$this->db->where("(T.transaction_date>='".$start_date."' and T.transaction_date<='".$end_date."' )");
		}
		
		$this->db->where(array('T.amount !='=>'0.00','T.transaction_status'=>'Completed'));
				 $this->db->order_by("T.invoice_id", "desc");
				 
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function get_transaction($perpage,$offset)
	{
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		
		$this->db->select("T.*, M.user_name");
		$this->db->from('transaction T');
		$this->db->join('members M','M.id=T.user_id','LEFT');
		
		
		if($start_date && $end_date)
		{
			$this->db->where("(T.transaction_date>='".$start_date."' and T.transaction_date<='".$end_date."' )");
		}
		
		$this->db->where(array('T.amount !='=>'0.00','T.transaction_status'=>'Completed'));
				 $this->db->order_by("T.invoice_id", "desc");
				 $this->db->limit($perpage, $offset);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}
	
	
}
