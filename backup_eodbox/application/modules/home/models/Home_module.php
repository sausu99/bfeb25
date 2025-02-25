<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home_module extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
	
	public function get_banner()
	{
		$data = array();
		$lang_id = $this->config->item('current_language_id');
		$query = $this->db->get_where('banner',array('type'=>'home','lang_id'=>$lang_id,'is_display'=>'Yes'));

		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
	}
        public function get_tvshowbanner(){
            $data = array();
		$lang_id = $this->config->item('current_language_id');
		$query = $this->db->get_where('banner',array('type'=>'tv_show','lang_id'=>$lang_id,'is_display'=>'Yes'));

		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
        }
	
	public function get_live_auction($limit='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');
		
		$array = array('start_date <=' => $current_dt,'end_date >=' => $current_dt, 'lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub');
		$this->db->where($array); 
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		if($limit)
		$this->db->limit($limit); 
		
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
	}
	
	public function get_featured_auction($limit='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');
		
		$array = array('start_date <=' => $current_dt,'end_date >=' => $current_dt, 'lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub', 'is_featured'=>'Yes');
		$this->db->where($array); 
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		if($limit)
		$this->db->limit($limit); 
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return false;
	}
	
	public function get_trending_auction($limit='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');
		
		$array = array('start_date <=' => $current_dt,'end_date >=' => $current_dt, 'lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub');
		$this->db->where($array); 
		$this->db->where("ub.id is not null"); 
		$this->db->group_by('a.id');
		$this->db->order_by("total_bids desc"); 
		if($limit)
		$this->db->limit($limit); 
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return false;
	}
	
	
	public function get_ending_soon_auction($limit='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');
		
		$array = array('start_date <=' => $current_dt, 'lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub');
		$this->db->where($array); 	
		$this->db->where("end_date > DATE_SUB('".$current_dt."', INTERVAL 168 HOUR)");	
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		if($limit)
		$this->db->limit($limit); 
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return false;
	}
	
	
	public function get_buy_auctions($limit='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');		
		$array = array('start_date <=' => $current_dt,'end_date >=' => $current_dt, 'lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub','is_buy_now'=>'Yes');
		$this->db->where($array); 	
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		if($limit)
		$this->db->limit($limit); 
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return array();
	}
	
	
	public function get_upcomming_auctions($limit='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');		
		$array = array('start_date >' => $current_dt, 'lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub');
		$this->db->where($array); 	
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		if($limit)
		$this->db->limit($limit); 
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return array();
	}
	
	public function get_home_category(){
		//get language id from configure file
		$lang_id = LANG_ID;
		$this->db->order_by("name", "asc"); 
		$query = $this->db->get_where('product_categories',array('is_home'=>1, 'is_display'=>'Y', 'lang_id'=>$lang_id)); 
		
		//echo $this->db->last_query();;exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return false;
	}
	
	public function get_auction_cat($cat_id, $limit='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');
		
		$array = array('start_date <=' => $current_dt,'end_date >=' => $current_dt, 'lang_id' => $lang_id, 'cat_id' => $cat_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub');
		$this->db->where($array); 
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		if($limit)
		$this->db->limit($limit); 
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return false;
	}
	
	public function get_large_bidding_category($limit='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.cat_id, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');
		$this->db->where("ub.bid_date > DATE_SUB('".$current_dt."', INTERVAL 90 DAY)");
		$this->db->group_by('a.cat_id');
		$this->db->order_by("total_bids desc"); 
		if($limit)
		 $this->db->limit($limit); 
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			$query->free_result();
			return $data;
		}
		return false;
	}
	
	public function get_popular_auctions($cat_ids, $limit='')
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id', 'left');
		
		$array = array('start_date <=' => $current_dt,'end_date >=' => $current_dt, 'lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub');
		$this->db->where($array); 
		$this->db->where_in($cat_ids); 
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		if($limit)
		$this->db->limit($limit); 
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return false;
	}
	
	
	public function get_closed_auctions()
	{
		//get language id from configure file
		$lang_id = LANG_ID;
		
		$this->db->select('a.*,ad.*, count(*) as total_bids, m.image,m.gender');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		$this->db->join('user_bids ub', 'ub.auc_id = a.product_id AND ub.user_id=a.current_winner_id', 'right');
		$this->db->join('members m', 'm.id = a.current_winner_id', 'right');
		$array = array('ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status !=' => 'Live', 'a.current_winner_id !='=>NULL);
		
		$this->db->where($array); 
		$this->db->group_by('a.id');
		$this->db->limit(3); 
		$this->db->order_by("a.end_date", "desc"); 

		$query = $this->db->get();
		//echo $this->db->last_query();;exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		return false;
	}
	
	

	public function add_watch_user_list()
	{
		
		$user_id=$this->session->userdata(SESSION.'user_id');
		$auction_id=$this->input->post('product_id');
		if($user_id)
		{
			$status=$this->check_prev_watchlist($user_id,$auction_id);
			if(empty($status))
			{
				$data=array(
				'user_id'=>$user_id,
				'auction_id'=>$auction_id,
				'watch_date'=>$this->general->get_gmt_time('time')
				);
				if($this->db->insert('member_watch_lists',$data))
				{
					return 'insert';
				}
				return false;
			}
			else
			{
				if($this->db->delete('member_watch_lists',array('auction_id'=>$auction_id,'user_id'=>$user_id)))
				 {
				 	return 'delete';
				 }
				return false;
			}
		}
		return false;
	}


	public function check_prev_watchlist($user_id,$auction_id)
	{
		$this->db->select('*');
		$this->db->from('member_watch_lists');
		$this->db->where(array('user_id'=>$user_id,'auction_id'=>$auction_id));
		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return $query->row();
		}
		return false;
	}

	public function check_pre_vote($vote_type)
	{

	$vote = ($vote_type =='positive')?'positive_rating':'negative_rating';

		$this->db->select('*');
		$this->db->where(array('user_id'=>$this->input->post('user_id'),'product_id'=>$this->input->post('product_id')));	
		$qry= $this->db->get('auction_vote');
		// echo $this->db->last_query();exit;
		if($qry->num_rows()>0)
		{
			return $qry->num_rows();
		}
		return false;
	}

	public function check_same_vote($vote_type)
	{

		$vote = ($vote_type =='positive')?'positive_rating':'negative_rating';

		$this->db->select('*');
		$this->db->where(array('user_id'=>$this->input->post('user_id'),'product_id'=>$this->input->post('product_id'),$vote=>'1'));	
		$qry= $this->db->get('auction_vote');
		// echo $this->db->last_query();exit;
		if($qry->num_rows()>0)
		{
			return $qry->num_rows();
		}
		return false;
	}


	public function vote_record_insert($user_id,$vote_count)
	{
		

		$data=array(
			'product_id'=>$this->input->post('product_id'),
			'user_id'=>$this->input->post('user_id'),
			'positive_rating'=>$this->positive_rating,
			'negative_rating'=>$this->negative_rating,
			'post_date'=>$this->general->get_gmt_time('time'),
			);
		if( $vote_count > 0 )
		{
			if($this->db->update('auction_vote',$data,array('user_id'=>$user_id,'product_id'=>$this->input->post('product_id'))))
			{
				return $this->db->affected_rows();
			}
		}else
		{
			if($this->db->insert('auction_vote',$data))	
			{
				return $this->db->insert_id();
			}		
		}


		if($this->db->insert('auction_vote',$data))
		{
			return $this->db->insert_id();
		}
		return false;

	}

	public function auction_vote_count()
	{
		$product_id=$this->input->post('product_id');
		$this->db->select('product_id,SUM(positive_rating) as positive_rating,sum(negative_rating) as negative_rating');
		$qry=$this->db->get_where('auction_vote',array('product_id'=>$product_id));
		if($qry->num_rows()>0)
		{
			return $qry->row();
		}
		return false;
	}	


}
