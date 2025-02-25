<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Winners_module extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		$this->current_dt = $this->general->get_local_time('time');
		
	}
	
	
	
	public function get_closed_auctions($perpage,$offset)
	{
		// echo 'here';exit;
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$this->db->select('a.*,ad.*, count(*) as total_bids, m.image, m.first_name, m.last_name, m.gender');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id AND ub.user_id=a.current_winner_id', 'right');
		$this->db->join('members m', 'm.id = a.current_winner_id', 'right');
		$array = array('ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status !=' => 'Live', 'a.current_winner_id !='=>NULL);
		
		$this->db->where($array); 
		$this->db->group_by('a.id');
		$this->db->limit($perpage, $offset);
		$this->db->order_by("a.end_date", "desc"); 

		$query = $this->db->get();
		// echo $this->db->last_query();;exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return FALSE;		
	}
	
	public function get_toal_closed_auctions()
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$this->db->select('a.id');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id AND ub.user_id=a.current_winner_id', 'right');
		$this->db->join('members m', 'm.id = a.current_winner_id', 'right');
		$array = array('ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status !=' => 'Live', 'a.current_winner_id !='=>NULL);
		
		$this->db->where($array); 
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date", "desc"); 

		$query = $this->db->get();
		//echo $this->db->last_query();;exit;
		return $query->num_rows();
	}
	
	public function get_auction_byproductid($product_id)
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$this->db->select('a.*,ad.*, count(*) as total_bids, m.image, m.first_name, m.last_name, m.gender, c.name as cat_name');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id AND ub.user_id=a.current_winner_id', 'right');
		$this->db->join('members m', 'm.id = a.current_winner_id', 'right');		
		$this->db->join('product_categories c', 'c.parent_id = a.cat_id', 'right');
		$array = array('a.product_id' => $product_id, 'a.status !=' => 'Live', 'ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.current_winner_id !='=>NULL);
		
		$this->db->where($array);
		$this->db->group_by('a.id');
		$query = $this->db->get();
		//echo $this->db->last_query();;exit;
		if($query->num_rows() > 0)
		{
			$data = $query->row();
			$query->free_result();
			return $data;
		}
		return FALSE;
	}
	
	
}
