<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Votes_module extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		$this->current_dt = $this->general->get_local_time('time');
		
	}
	

	public function get_auction_byproductid($product_id)
	{
		//get language id from configure file
		$lang_id = LANG_ID;
		
		// $this->db->select('a.*,ad.*');
		// $this->db->from('auction a');
		// $this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');

		$this->db->select('a.product_id, a.price,ad.name,a.image1,a.image2,a.image3,a.image4,ad.description,IFNULL(sum(av.positive_rating),0) as positive_rating ,IFNULL(sum(av.negative_rating),0) as negative_rating');
		$this->db->from('auction a');
		$this->db->join('auction_vote av','av.product_id=a.product_id','Left');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');

		$array = array('a.product_id' => $product_id,'ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status' => 'Pending');
		$this->db->where($array); 

		$query = $this->db->get();
		// echo $this->db->last_query();;exit;
		if($query->num_rows() > 0)
		{
			$data = $query->row();
			$query->free_result();
			return $data;
		}
		return FALSE;
	}
	
	public function get_total_vote_auction()
	{
		$lang_id = LANG_ID;
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.id');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$array = array('lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Pending', 'auc_type' => 'vote');
		$this->db->where($array); 
		$query = $this->db->get();
		
		return $query->num_rows();

	}

	public function get_vote_auctions($limit=9, $offset=0)
	{
		//get language id from configure file
		$lang_id = LANG_ID;
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.product_id, a.price,ad.name,a.image1,IFNULL(sum(av.positive_rating),0) as positive_rating ,IFNULL(sum(av.negative_rating),0) as negative_rating');
		$this->db->from('auction a');
		$this->db->join('auction_vote av','av.product_id=a.product_id','Left');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$array = array('lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Pending', 'auc_type' => 'vote');
		$this->db->where($array); 
		$this->db->order_by("a.end_date asc"); 
		$this->db->group_by('a.product_id');
		
		$this->db->limit($limit, $offset);
		
		$query = $this->db->get();
		// echo $this->db->last_query();
		// die();
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
	}

}
