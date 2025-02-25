<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Details_module extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		$this->current_dt = $this->general->get_local_time('time');
		
	}
	

	public function get_auction_byproductid($product_id)
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$this->db->select('a.*,ad.*, c.name as cat_name');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('product_categories c', 'c.parent_id = a.cat_id', 'right');
		
		$array = array('a.product_id' => $product_id,'a.start_date <=' => $this->current_dt, 'ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status' => 'Live');
		
		$this->db->where($array); 
		$this->db->order_by("a.end_date", "asc"); 

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
	
	public function get_live_ending_soon_auctions($product_id)
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$this->db->select('a.*,ad.name');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$this->db->where_not_in('product_id', $product_id);

		$array = array('lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live');
		$this->db->where($array); 
		$this->db->order_by("end_date", "asc"); 
		$this->db->limit(2);

		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
	}
	
	public function get_last_10bids($product_id)
	{
		$data = '';
		$this->db->group_by("new_auction_price"); 
		$this->db->order_by("id", "desc");
		$this->db->limit(5); 
		$query = $this->db->get_where('user_bids',array('auc_id'=>$product_id));
		//echo $this->db->last_query();exit;
		if ($query->num_rows($query->result() ) > 0)
		{
			
			$data = $query->result();
		}
		
		$query->free_result();
		return $data;
	}
	
	public function get_user_autobids($product_id)
	{
		$data = '';
		$user_id=$this->session->userdata(SESSION.'user_id');
		$this->db->select('bids,current_bid,status');
		$query = $this->db->get_where('user_autobid',array('auc_id'=>$product_id,'user_id'=>$user_id,'status'=>'available'));		
				 $this->db->order_by("id", "desc"); 

		
		if($query->num_rows()>0)
		{
			$data = $query->row();
		}
		$query->free_result();
		return $data;
	}
		
	public function get_preview_auction_byproductid($product_id)
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$this->db->select('a.*,ad.*');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$array = array('product_id' => $product_id, 'lang_id' => $lang_id, 'status' => 'Live');
		
		$this->db->where($array); 
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
	
	public function count_users_bid_history_by_auction_id($auc_id, $user_id=''){
		$this->db->select("userbid_bid_amt,user_id, freq");
		$this->db->where('auc_id',$auc_id);
		if($user_id != ''){
			$this->db->where('user_id',$user_id);
		}
		//$this->db->order_by('freq ASC, userbid_bid_amt ASC');
		$query = $this->db->get('user_bids');
		//echo $this->db->last_query(); exit;
		return $query->num_rows();
	}
	
	public function get_users_bid_history_by_auction_id($auc_id, $user_id, $perpage, $offset){
		$data=array();
		$this->db->select("*");
		$this->db->where(array('auc_id'=>$auc_id,'user_id'=>$user_id));
		//$this->db->order_by('freq ASC, userbid_bid_amt ASC');
		$this->db->limit($perpage, $offset);
		$this->db->order_by('id DESC');
		$query = $this->db->get('user_bids');
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) 
		{
			$data=$query->result();	
			$query->free_result();
		   return $data;				
		}	
	}
	
	public function count_other_users_bid_history_by_auction_id($auc_id, $user_id=''){
		$query = $this->db->get_where('user_bids', array('auc_id'=>$auc_id,'user_id !='=>$user_id));
		return $query->num_rows();
	}
	public function get_other_users_bid_history_by_auction_id($auc_id, $user_id, $perpage, $offset){
		$data=array();
		$this->db->select("user_name, bid_date");
		$this->db->where(array('auc_id'=>$auc_id,'user_id !='=>$user_id));
		$this->db->order_by('id DESC');
		$this->db->limit($perpage, $offset);
		$query = $this->db->get('user_bids');
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) 
		{
			$data=$query->result();	
			$query->free_result();
		   return $data;				
		}	
	}
	public function count_all_live_auctions($cid=''){
		//get language id from configure file
		$lang_id = LANG_ID;
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.id');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$array = array('a.start_date <=' => $current_dt,'a.end_date >=' => $current_dt, 'ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status' => 'Live', 'a.auc_type' => 'lub');	
		
		if($cid)
			$array['a.cat_id']=$cid;
		
		$this->db->where($array); 
					
		$srch = $this->input->get('srch');
		if($srch){
				$this->db->where("(ad.name LIKE '%".$srch."%' OR ad.description LIKE '%".$srch."%')"); 
		}
				
		$query = $this->db->get();
		
		return $query->num_rows();
	}
	
	public function get_all_live_auctions($limit=9, $offset=0, $cid='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name, COUNT(ub.id) AS total_bids, ad.meta_desc');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');
		
		$array = array('a.start_date <=' => $current_dt,'a.end_date >=' => $current_dt, 'ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status' => 'Live', 'a.auc_type' => 'lub');
		
		if($cid)
			$array['a.cat_id']=$cid;
			
		$this->db->where($array); 
		
		$srch = $this->input->get('srch');
		if($srch){
				$this->db->where("(ad.name LIKE '%".$srch."%' OR ad.description LIKE '%".$srch."%')"); 
		}
		
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		
		$this->db->limit($limit, $offset);
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
	}
	
	public function count_upcomming_auctions(){
		//get language id from configure file
		$lang_id = LANG_ID;
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.id');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$array = array('a.start_date >' => $current_dt, 'ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status' => 'Live', 'a.auc_type' => 'lub');		
		
		
		$this->db->where($array); 		
		$query = $this->db->get();		
		return $query->num_rows();
	}
	
	public function get_upcomming_auctions($limit=9, $offset=0)
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name, ad.meta_desc, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');
		
		$array = array('a.start_date >' => $current_dt, 'ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status' => 'Live', 'a.auc_type' => 'lub');
			
		$this->db->where($array); 
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		
		$this->db->limit($limit, $offset);
		
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
	}
	
	public function get_upcomming_auction_byproductid($product_id)
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		$this->db->select('a.*,ad.*, c.name as cat_name');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('product_categories c', 'c.parent_id = a.cat_id', 'right');
		
		$array = array('a.product_id' => $product_id,'a.start_date >' => $this->current_dt, 'ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status' => 'Live');
		
		$this->db->where($array); 
		$this->db->order_by("a.end_date", "asc"); 

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
	
	public function get_all_tv_auctions($limit=9, $offset=0, $cid='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.id,a.product_id,a.end_date,a.start_date,a.image1,a.price,a.end_day,a.end_hour,a.end_minute,ad.name');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$array = array('start_date <=' => $current_dt,'end_date >=' => $current_dt, 'lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub','tv_show'=>'Yes');
		
		if($cid)
			$array['a.cat_id']=$cid;
			
		$this->db->where($array); 
		$this->db->order_by("a.end_date asc"); 
		
		$this->db->limit($limit, $offset);
		
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
	}
	public function get_total_watchlist($product_id) {
        $CI = & get_instance();
        $query = $CI->db->get_where('member_watch_lists', array('auction_id' => $product_id));
        // echo $CI->db->last_query();

        return $query->num_rows();
    }
	public function get_auction_faq_lists($lang_id, $auc_id)
	{	
		$query = $this->db->get_where('auction_help',array('lang_id'=>$lang_id, 'auc_id' =>$auc_id));
		
		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_category_name($id)
	{		
		$query = $this->db->get_where('product_categories',array("id"=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row()->name;
		} 

		return false;
	}
}
