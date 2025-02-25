<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auto_close_model extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
	}
	
	function get_all_closing_auctions()
	{
		$this->db->select('id,product_id,end_date');
		$this->db->where(array('status'=>'Live','auc_type'=>'lub', 'end_date <=' => $this->current_date_time));
		$query=$this->db->get('auction');
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$data = $query->result();	
			$query->free_result();
			return $data;
		}
		return false;
	}
	
	
	public function insert_winner()
	{	
		$data = array(
			'auc_id' => $this->product_id,
			'user_id' => $this->winner_id,
			'bid_id' => $this->winner_bid_id,
			'won_amt' => $this->winner_amount,
			'auction_close_date' => $this->general->get_local_time('time')
		);

		$this->db->insert('auction_winner', $data);									
	}
	
	public function update_auction_to_closed($id)
	{
		$data = array(
			'status'=>"Closed",
			'current_winner_id'=>$this->winner_id,
			'current_winner_name'=>$this->winner_full_name,
			'current_winner_amount'=>$this->winner_amount,
			'current_winner_date'=>$this->general->get_local_time('time'),
		);
		//print_r($data);exit;
		$this->db->where(array('id'=>$id));
		$query = $this->db->update('auction', $data);			
	}
	
	public function update_auction_to_cancelled($id)
	{
		$this->db->where(array('id'=>$id));
		$query=$this->db->update('auction', array('status'=>"Cancel"));			
	}

	
	public function send_email_winner_admin()
	{	
		//load email library
    	$this->load->library('email');							
		$this->load->model('email_model');		
		
		//get winner info
		$user_info = $this->get_winner_info($this->winner_id);

		$user_email = $user_info->email;
		
		//Get auction info
		$auction_info = $this->get_auction_byproductid($this->product_id);		
		$product_name = $auction_info->name;
		
		
	
		//Get auction closed template for winner
		$lang_id = $this->general->get_lang_id_by_country($user_info->country);
		$template=$this->email_model->get_email_template("auction_closed_notification_user",$lang_id);		
		if(!isset($template['subject']))
		{
			$template=$this->email_model->get_email_template("auction_closed_notification_user",DEFAULT_LANG_ID);
		}
		
		$subject=$template['subject'];
		$emailbody=$template['email_body'];
	
		$confirm="<a href='".$this->general->lang_uri('/'.MY_ACCOUNT.'/users/won_auction')."'>CLICK TO PAY</a>";

		//parse email
		$parseElement=array("USERNAME"=>$this->winner_name,
								"FIRSTNAME"=>$user_info->first_name,
								"LASTNAME"=>$user_info->last_name,
								"FULLNAME"=>$user_info->first_name.' '.$user_info->last_name,
								"SITENAME"=>SITE_NAME,
								"CONFIRM"=>$confirm,
								"AUCTIONNAME"=>$product_name,									
								"AMOUNT"=>$this->winner_amount,
								"DATE"=>$auction_info->current_winner_date);

		$subject=$this->email_model->parse_email($parseElement,$subject);
		$emailbody=$this->email_model->parse_email($parseElement,$emailbody);
		
			
		//set the email parameters
//		$this->email->from(SYSTEM_EMAIL);
//	
//		$this->email->to($user_email); 
//		$this->email->subject($subject);
//		$this->email->message($emailbody); 
//		$this->email->send();
//		
//                
//		
//		//Get auction closed template for Admin
//		$this->email->clear();
                
                $this->netcoreemail_class->send_email(SYSTEM_EMAIL,$user_email,$subject,$emailbody);
      
		
		$template=$this->email_model->get_email_template("auction_closed_notification_admin",DEFAULT_LANG_ID);
		$subject=$template['subject'];
		$emailbody=$template['email_body'];
	
		//parse email
		$parseElement=array("USERNAME"=>$this->winner_name,
								"SITENAME"=>SITE_NAME,
								"FIRSTNAME"=>$user_info->first_name,
								"LASTNAME"=>$user_info->last_name,
								"FULLNAME"=>$user_info->first_name.' '.$user_info->last_name,
								"AUCTIONNAME"=>$product_name,									
								"AMOUNT"=>$this->winner_amount,
								"DATE"=>$auction_info->current_winner_date);

		//parse email
		$subject=$this->email_model->parse_email($parseElement,$subject);
		$emailbody=$this->email_model->parse_email($parseElement,$emailbody);
			
		//set the email things
//		$this->email->from(SYSTEM_EMAIL);
//		$this->email->to(CONTACT_EMAIL); 
//		$this->email->subject($subject);
//		$this->email->message($emailbody); 
//		$this->email->send();
                
                $this->netcoreemail_class->send_email(SYSTEM_EMAIL,CONTACT_EMAIL,$subject,$emailbody);
	}


	public function send_sms_notification()
	{
		//load email library
    	$this->load->library('email');							
		$this->load->model('email_model');		
		
		//get winner info
		$user_info = $this->get_winner_info($this->winner_id);

		$user_email = $user_info->email;
		
		//Get auction info
		$auction_info = $this->get_auction_byproductid($this->product_id);		
		$product_name = $auction_info->name;
		
		
	
		//Get auction closed template for winner
		$lang_id = $this->general->get_lang_id_by_country($user_info->country);
		$template=$this->email_model->get_email_template("auction_closed_notification_user",$lang_id);		
		if(!isset($template['subject']))
		{
			$template=$this->email_model->get_email_template("auction_closed_notification_user",DEFAULT_LANG_ID);
		}
		
		$subject=$template['subject'];
		$smsbody=$template['sms_body'];
	
		$confirm="<a href='".$this->general->lang_uri('/'.MY_ACCOUNT.'/users/won_auction')."'>CLICK TO PAY</a>";

		//parse email
		$parseElement=array("USERNAME"=>$this->winner_name,
								"FIRSTNAME"=>$user_info->first_name,
								"LASTNAME"=>$user_info->last_name,
								"FULLNAME"=>$user_info->first_name.' '.$user_info->last_name,
								"SITENAME"=>SITE_NAME,
								"CONFIRM"=>$confirm,
								"AUCTIONNAME"=>$product_name,									
								"AMOUNT"=>$this->winner_amount,
								"DATE"=>$auction_info->current_winner_date);

		$subject=$this->email_model->parse_email($parseElement,$subject);
		$smsbody=$this->email_model->parse_email($parseElement,$smsbody);

		$this->checkmobi->sendSMS(CHECKMOBI_SMS_API_KEY,$user_info->mobile,$smsbody);

	}

	public function send_push_notification()
	{
		//load email library
    	$this->load->library('email');							
		$this->load->model('email_model');		
		
		//get winner info
		$user_info = $this->get_winner_info($this->winner_id);

		$user_email = $user_info->email;
		
		//Get auction info
		$auction_info = $this->get_auction_byproductid($this->product_id);		
		$product_name = $auction_info->name;
		
		
	
		//Get auction closed template for winner
		$lang_id = $this->general->get_lang_id_by_country($user_info->country);
		$template=$this->email_model->get_email_template("auction_closed_notification_user",$lang_id);		
		if(!isset($template['subject']))
		{
			$template=$this->email_model->get_email_template("auction_closed_notification_user",DEFAULT_LANG_ID);
		}
		
		$subject=$template['subject'];
		$push_body=$template['push_message_body'];
	
		$confirm="<a href='".$this->general->lang_uri('/'.MY_ACCOUNT.'/users/won_auction')."'>CLICK TO PAY</a>";

		//parse email
		$parseElement=array("USERNAME"=>$this->winner_name,
								"FIRSTNAME"=>$user_info->first_name,
								"LASTNAME"=>$user_info->last_name,
								"FULLNAME"=>$user_info->first_name.' '.$user_info->last_name,
								"SITENAME"=>SITE_NAME,
								"CONFIRM"=>$confirm,
								"AUCTIONNAME"=>$product_name,									
								"AMOUNT"=>$this->winner_amount,
								"DATE"=>$auction_info->current_winner_date);

		$subject=$this->email_model->parse_email($parseElement,$subject);
		$push_body=$this->email_model->parse_email($parseElement,$push_body);
		$user_push = $this->general->get_device_id($user_info->push_id);
		$this->fcm->send($user_push,array('message'=>$push_body,'subject'=>$subject));
	}		

	
		
	public function get_winner_info($winner_id)
	{
		$query = $this->db->get_where('members',array('id'=>$winner_id));

		if ($query->num_rows() > 0)
		{
		  return $query->row(); 
		} 

	}
	public function get_auction_byproductid($product_id)
	{
		
		$this->db->select('a.*,ad.*');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$array = array('a.product_id' => $product_id);
		$this->db->where($array); 
		$this->db->order_by("end_date", "asc"); 

		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$data = $query->row();
			$query->free_result();
			return $data;
		}
	}
	
	public function CloseAuction_ClosingDate($auc_id,$close_date)
	{
		if(!$this->greaterDate($timenow,$close_date))
		{
			$data=array('status'=>"Closed",'end_date'=>$this->current_date_time);
			$this->db->where(array('product_id'=>$auc_id,'status'=>"Live"));
			$query=$this->db->update('auction', $data);	
		}
	}
	
	
	function greaterDate($start_date,$end_date)
	{		
			$uts['start'] = strtotime($start_date); 
			
			$uts['end'] = strtotime($end_date); 
			
			$diff = $uts['end'] - $uts['start'];
			
			if ($diff > 0)
				return 1;
			else
				return 0;			
	}		

}
?>	