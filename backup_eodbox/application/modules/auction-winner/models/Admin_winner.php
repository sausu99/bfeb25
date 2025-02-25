<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_winner extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
	
			
	}
	
	public $validate_settings =  array(	
			array('field' => 'price', 'label' => 'Auction Price', 'rules' => 'required|decimal'),
			array('field' => 'shipping_cost', 'label' => 'Shipping Cost', 'rules' => 'required|decimal'),
			array('field' => 'bid_fee', 'label' => 'Bid Fee', 'rules' => 'required|integer'),
			array('field' => 'start_date', 'label' => 'Start Date', 'rules' => 'required'),
			array('field' => 'end_date', 'label' => 'End Date', 'rules' => 'required|callback_check_end_date'),
			array('field' => 'lang_id[]', 'label' => 'Language', 'rules' => 'required')
		);
	
	public function get_toal_auc_winner($status)
	{			
		$this->db->select('auc.*,auc_det.name,aw.id AS auction_winner_id, aw.user_id,aw.won_amt,aw.auction_close_date,aw.payment_status,aw.shipping_status,aw.invoice_id,m.lang_id');
		$this->db->from('auction_winner aw');
		$this->db->join('auction auc', 'auc.product_id = aw.auc_id', 'left');
		$this->db->join('auction_details auc_det', 'auc_det.auc_id = auc.id', 'left');	
		$this->db->join('members m', 'm.id = aw.user_id', 'left');		
		$this->db->where('aw.shipping_status',$status);
		$this->db->where('aw.payment_status IS NOT NULL');
		$this->db->where('auc.product_id IS NOT NULL');
		$this->db->where('m.id IS NOT NULL');
		$this->db->group_by('auc.id');
		$this->db->order_by("aw.auction_close_date", "desc");
		$query = $this->db->get();

		return $query->num_rows();
	}
	
	public function get_auction_winner($status,$perpage,$offset)
	{		
		$this->db->select('auc.*,auc_det.name,aw.id AS auction_winner_id, aw.user_id,aw.won_amt,aw.auction_close_date,aw.payment_status,aw.shipping_status,aw.invoice_id,m.lang_id,m.country,m.first_name,m.last_name');
		$this->db->from('auction_winner aw');
		$this->db->join('auction auc', 'auc.product_id = aw.auc_id', 'left');
		$this->db->join('auction_details auc_det', 'auc_det.auc_id = auc.id', 'left');	
		$this->db->join('members m', 'm.id = aw.user_id', 'left');		
		$this->db->where('aw.shipping_status',$status);
		$this->db->where('aw.payment_status IS NOT NULL');
		$this->db->where('auc.product_id IS NOT NULL');
		$this->db->where('m.id IS NOT NULL');
		$this->db->group_by('auc.id');
		$this->db->order_by("aw.auction_close_date", "desc");
		$this->db->limit($perpage, $offset);
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_auction_winner_details($auction_winner_id)
	{		
		$this->db->select('auc.id,auc.price,auc.shipping_cost,aw.id AS auction_winner_id,aw.auc_id, aw.user_id,aw.won_amt,aw.auction_close_date,aw.payment_status,aw.invoice_id,
						   m.email,m.user_name,m.mobile,m.first_name,m.last_name,m.lang_id,m.country,aw.shipping_status');
		
		$this->db->from('auction_winner aw');
		$this->db->join('members m', 'm.id = aw.user_id', 'left');
		$this->db->join('auction auc', 'auc.product_id = aw.auc_id', 'left');		
		$this->db->where('aw.id',$auction_winner_id);	
		$this->db->where('aw.id IS NOT NULL');
				
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	public function get_auction_name_by_user_lang($auc_id,$lang_id)
	{
		$this->db->select('name');
		$query = $this->db->get_where('auction_details',array('auc_id'=>$auc_id,'lang_id'=>$lang_id));
		
		if ($query->num_rows() == 0)
		{
			$query = $this->db->get_where('auction_details',array('auc_id'=>$auc_id,'lang_id'=>DEFAULT_LANG_ID));
			
			if ($query->num_rows() == 0)
			{
				$query = $this->db->get_where('auction_details',array('auc_id'=>$auc_id));
			}
		}
		//echo $this->db->last_query();
		//echo $query->num_rows();exit;
		return $query->row();
	}
	
	public function get_winner_transaction_details($invoice_id)
	{
		$this->db->select('amount, transaction_date, transaction_type, transaction_status, payment_method, gross_amount');
		$query = $this->db->get_where('transaction',array('invoice_id'=>$invoice_id));

		return $query->row();
	}
	
	public function get_winner_address($auc_won_id,$user_id)
	{
		$query = $this->db->get_where('auction_winner_address',array('auc_won_id'=>$auc_won_id,'user_id'=>$user_id));
		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	function update_shipping_status()
	{
		$data = array('shipping_status' => $this->input->post('shipping'));
		$this->db->where('id', $this->input->post('auction_winner_id'));
		$this->db->update('auction_winner', $data); 
	}
	
	
}
