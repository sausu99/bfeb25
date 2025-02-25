<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_member extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		//load CI library
			$this->load->library('form_validation');
			
			
	}
	
	public $validate_settings_add =  array(	
			array('field' => 'gender', 'label' => 'Gender', 'rules' => 'trim|required'),
			array('field' => 'first_name', 'label' => 'First Name', 'rules' => 'trim|required'),
			array('field' => 'last_name', 'label' => 'Last Name', 'rules' => 'trim|required'),
			array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email|callback_check_email'),
			array('field' => 'mobile', 'label'=>'mobile', 'rules' => 'required|trim|regex_match[/^[0-9+]{10,14}$/]|callback_check_mobile'),			
			array('field' => 'day', 'label'=>'day', 'rules' => 'required|numeric'),	
			array('field' => 'month', 'label'=>'month', 'rules' => 'required|numeric'),
			array('field' => 'year', 'label'=>'year', 'rules' => 'required|numeric|callback_under_18_check'),
			array('field' => 'user_name', 'label' => 'User Name', 'rules' => 'trim|required|min_length[6]|max_length[12]|is_unique[members.user_name]'),
			array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[6]|max_length[12]'),
			array('field' => 'country', 'label' => 'Country', 'rules' => 'required|trim'),
			array('field' => 'state', 'label' => 'State', 'rules' => 'required|trim'),
			array('field' => 'address', 'label' => 'Address1', 'rules' => 'trim|required'),
			array('field' => 'city', 'label' => 'City', 'rules' => 'trim|required'),
			array('field' => 'post_code', 'label' => 'Post Code', 'rules' => 'trim|required'),
			// array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|required'),			
			// array('field' => 'ship_country', 'label' => 'Country', 'rules' => 'required'),
			// array('field' => 'ship_address', 'label' => 'Address1', 'rules' => 'trim|required'),
			// array('field' => 'ship_city', 'label' => 'City', 'rules' => 'trim|required'),
			// array('field' => 'ship_post_code', 'label' => 'Post Code/ Zip Code', 'rules' => 'trim|required')
		);
		
	public $validate_settings_edit =  array(	
			array('field' => 'first_name', 'label' => 'First Name', 'rules' => 'trim|required'),
			array('field' => 'last_name', 'label' => 'Last Name', 'rules' => 'trim|required'),
			array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email|callback_check_email'),
			array('field' => 'mobile', 'label'=>'mobile', 'rules' => 'required|trim|regex_match[/^[0-9+]{10,14}$/]|callback_check_mobile'),			
			array('field' => 'day', 'label'=>'day', 'rules' => 'required|numeric'),	
			array('field' => 'month', 'label'=>'month', 'rules' => 'required|numeric'),
			array('field' => 'year', 'label'=>'year', 'rules' => 'required|numeric|callback_under_18_check'),
			
			array('field' => 'country', 'label' => 'Country', 'rules' => 'required|trim'),
			array('field' => 'address', 'label' => 'Address1', 'rules' => 'trim|required'),
			array('field' => 'city', 'label' => 'City', 'rules' => 'trim|required'),
			array('field' => 'post_code', 'label' => 'Post Code', 'rules' => 'trim|required'),
			// array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|required'),			
			// array('field' => 'ship_country', 'label' => 'Country', 'rules' => 'required'),
			// array('field' => 'ship_address', 'label' => 'Address1', 'rules' => 'trim|required'),
			// array('field' => 'ship_city', 'label' => 'City', 'rules' => 'trim|required'),
			// array('field' => 'ship_post_code', 'label' => 'Post Code/ Zip Code', 'rules' => 'trim|required')
		);
		
	
	public function get_total_members($status)
	{		
		$cur_date=date("Y-m-d");

		if($status) $status = $status; else $status = 'active';
		
		$this->db->select('*');
		if($status=='active' || $status=='inactive' || $status=='close' || $status=='suspended' )
		{
				$this->db->where('status',$status);			
		}
		else if($status=='today_join')
		{
			 $this->db->where(array("date(reg_date)"=>$cur_date));
		}
		else if($status=='online')
		{
			$this->db->where('mem_login_state','1');
		}
		else if($status=='obsence'){
			$this->db->where('obsence_flag','yes');
		}

		if($this->input->post('srch')!="")
		{
			$where = "(first_name LIKE '%".$this->input->post('srch')."%' OR user_name LIKE '%".$this->input->post('srch')."%' OR email LIKE '%".$this->input->post('srch')."%')";
			$this->db->where($where);
		}
		
		$query = $this->db->get('members');
		// echo $this->db->last_query();
		// die();

		return $query->num_rows();
	}
	
	public function get_members_details($status,$perpage,$offset)
	{
		$cur_date=date("Y-m-d");
		// echo $cur_date;
		// exit;
		if($status) $status = $status; else $status = 'active';
		
		$this->db->select('m.*,c.country_flag');
		
		if($status=='active' || $status=='inactive' || $status=='close' || $status=='suspended' )
		{
				$this->db->where('m.status',$status);			
		}
		else if($status=='today_join')
		{
			 $this->db->where(array("date(m.reg_date)"=>$cur_date));
		}
		else if($status=='online')
		{
			$this->db->where('m.mem_login_state','1');
		}
		else if($status=='obscene'){
			$this->db->where('obsence_flag','yes');
		}
		
		if($this->input->post('country')!="")
		{
			$this->db->where('m.country',$this->input->post('country'));
		}

		if($this->input->post('srch')!="")
		{
			$where = "(m.first_name LIKE '%".$this->input->post('srch')."%' OR m.user_name LIKE '%".$this->input->post('srch')."%' OR m.email LIKE '%".$this->input->post('srch')."%')";
			$this->db->where($where);
		}
		$this->db->from('members m');
		$this->db->join('country c','c.id=m.country','LEFT');
		
		$this->db->order_by("m.balance", "desc");
		$this->db->limit($perpage, $offset);
		// $query = $this->db->get('members');
		$query=$this->db->get();
		// echo $this->db->last_query();

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	public function mark_obscene($id){
		$this->db->where('id', $id);
		$this->db->update('members', array('obsence_flag'=>'yes'));
	}
	public function mark_safe($id){

		$this->db->where('id', $id);
		$this->db->update('members', array('obsence_flag'=>'no'));
	}
	
	public function change_image($id){
		$this->db->where('id', $id);
		$this->db->update('members', array('image'=>''));
		
	}
	
	public function get_member_byid($id)
	{			
				 $this->db->select('m.*,c.country as country_name, c.lang_id, c.currency_sign, c.currency_code, c.country_flag');
				 $this->db->from('members m');
				 $this->db->join('country c', 'c.id = m.country', 'left');
		$query = $this->db->get_where('members',array('m.id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}

	// public function get_member_byid($id)
	// {			
	// 			 $this->db->select('m.*,l.lang_name,l.short_code,l.lang_flag');
	// 			 $this->db->from('members m');
	// 			 $this->db->join('language l', 'l.id = m.lang_id', 'left');
	// 	$query = $this->db->get_where('members',array('m.id'=>$id));

	// 	if ($query->num_rows() > 0)
	// 	{
	// 	   return $query->row();
	// 	} 

	// 	return false;
	// }
	
	public function get_user_shipping_details($user_id)
	{
		$option = array('user_id'=>$user_id);
		$query = $this->db->get_where('members_address',$option);
		if($query->num_rows()==1)
		{
			return $query->row();
		}
	}
	
	public function insert_record()
	{
		//generate password
		$salt = $this->general->salt();		
		$password = $this->general->hash_password($this->input->post('password'),$salt);
		
		//set  info
		$data_profile = array(
			   'gender'=>$this->input->post('gender'),
               'first_name' => $this->input->post('first_name'),
               'last_name' => $this->input->post('last_name'),
			   'email' => $this->input->post('email'),
			   'mobile' => $this->input->post('mobile'),	
			   'dob_year'=>$this->input->post('year'),
			   'dob_month'=>$this->input->post('month'),
			   'dob_day'=>$this->input->post('day'),
			   'user_name' => $this->input->post('user_name'),	
				'password' => $password,			   
			   'status' => 'active',
			   'address' => $this->input->post('address'),	
			   'address2' => $this->input->post('address2'),			   
			   'country' => $this->input->post('country'),
			   'state' => $this->input->post('state'),
			   'city' => $this->input->post('city'),
			   'post_code' => $this->input->post('post_code'),			   
			   'reg_ip_address' => $this->general->get_real_ipaddr(),
			   'reg_date' => $this->general->get_local_time('time')
            );
			
			$data_profile['is_fb_user'] = 'No';
			
			//SET Sign bonus		
			if (SIGNUP_BONUS != '0') {
				$data_profile['bonus_points'] = SIGNUP_BONUS;
			}
			if (SIGNUP_CREDIT != '0') {
				$data_profile['balance'] = SIGNUP_CREDIT;
			}
		
		$this->db->insert('members', $data_profile);
		
	}
	
	public function update_record($id)
	{
		//set  info
		$data_profile = array(
			   //'title' => $this->input->post('title'),
               'first_name' => $this->input->post('first_name'),
               'last_name' => $this->input->post('last_name'),
			   'email' => $this->input->post('email'),
			   'dob_year'=>$this->input->post('year'),
			   'dob_month'=>$this->input->post('month'),
			   'dob_day'=>$this->input->post('day'),
			   'referer_marketing' => $this->input->post('referer_marketing'),
			   'status' => $this->input->post('status'),
			   'address' => $this->input->post('address'),	
			   'address2' => $this->input->post('address2'),
			   'gender'=>$this->input->post('gender'),
			   'country' => $this->input->post('country'),
			   'city' => $this->input->post('city'),
			   'post_code' => $this->input->post('post_code'),
			   'mobile' => $this->input->post('mobile'),	
			   'obsence_flag'=>$this->input->post('obsence_flag')		   
            );
		
		$this->db->where('id', $this->input->post('user_id'));
		$this->db->update('members', $data_profile);
		
		//check shipping address		
		// $data_ship = array(			   
  		//	   'name' => $this->input->post('name'),			   
		// 	   'address' => $this->input->post('ship_address'),	
		// 	   'address2' => $this->input->post('ship_address2'),
		// 	   'country_id' => $this->input->post('ship_country'),
		// 	    'city' => $this->input->post('ship_city'),
		// 		 'post_code' => $this->input->post('ship_post_code'),
		// 		  'phone' => $this->input->post('ship_phone'),
		// );
			
		// $option = array('user_id'=>$this->input->post('user_id'));
		// $query = $this->db->get_where('members_address',$option);
		// //echo $query->num_rows();exit;
		// if($query->num_rows()==1)
		// {
		// 	 //update records in the database
		// 	 $this->db->where('id', $this->input->post('ship_id'));
		// 	 $this->db->where('user_id', $this->input->post('user_id'));
		// 	 $this->db->update('members_address',$data_ship);
		// }
		// else
		// {
		// 	 //insert records in the database
		// 	 $data_ship['user_id'] = $this->input->post('user_id');
		// 	 $this->db->insert('members_address', $data_ship); 

		// }
		

	}
	
	public function count_member_transaction($user_id)
	{
		$option = array('user_id'=>$user_id,'transaction_status'=>'Completed');
		$query = $this->db->get_where('transaction',$option);
		return $query->num_rows();
	}
	public function get_member_bids_history($user_id)
	{
		$this->db->distinct();  		
		$this->db->select('a.product_id,ad.name,a.bid_fee,a.current_winner_amount');
		$this->db->from('user_bids ub');
		$this->db->join('auction a', 'a.product_id = ub.auc_id', 'left');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'left');				
		$this->db->where('ub.user_id',$user_id);
		//$this->db->where('a.status','Closed');
		$this->db->where("(a.status='Closed' OR a.status='Cancel' OR a.status='Dispatched')"); 
		$this->db->group_by('a.id');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}	
	

	public function get_member_bids($user_id,$status=false)
	{		
		$this->db->select('a.product_id,ad.name,a.bid_fee,a.current_winner_amount');
		$this->db->from('user_bids ub');
		$this->db->join('auction a', 'a.product_id = ub.auc_id', 'left');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'left');		
		$this->db->where('ub.user_id',$user_id);
		$this->db->where('a.product_id IS NOT NULL');
		if($status)
		{
			$this->db->where('a.status',$status);
		}
		
		$this->db->group_by('a.id');
		$this->db->order_by('ub.id','DESC');
		$query = $this->db->get();
		// echo $this->db->last_query();
		// die();
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}

	public function get_member_bids_won($user_id)
	{
				
		$this->db->select('a.product_id,ad.name,a.current_winner_amount');
		$this->db->from('auction_winner aw');
		$this->db->join('user_bids ub', 'ub.id = aw.bid_id', 'left');		
		$this->db->join('auction a', 'a.product_id = aw.auc_id', 'left');		
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'left');						
		$this->db->where('aw.user_id',$user_id);
		//$this->db->where('a.status','Closed');		
		$this->db->where("(a.status='Closed' OR a.status='Dispatched')"); 
		$this->db->group_by('a.id');
		$query = $this->db->get();

		if($query->num_rows() > 0)
		{
			return $query->result();
		}	
	}

		public function select_user_bid_detail($user_id,$auc_id)
	{
		$this->db->select('userbid_bid_amt,bid_date,auc_id');
		$this->db->from('user_bids');
		$this->db->where('user_id',$user_id);
		$this->db->where('auc_id',$auc_id);
		$this->db->order_by('userbid_bid_amt asc, bid_date asc'); //('bid_date','DESC');
		$query = $this->db->get();
	
		if($query->num_rows() > 0)
		{
			return $query->result();
		}					
		
	}

	public function get_member_transaction($user_id,$perpage,$offset)
	{
		$option = array('user_id'=>$user_id,'gross_amount !='=>'0.00','transaction_status'=>'Completed');
				 $this->db->order_by("invoice_id", "desc");
				 $this->db->limit($perpage, $offset);
		$query = $this->db->get_where('transaction',$option);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}
	
	public function count_member_ip($user_id)
	{
		$option = array('user_id'=>$user_id);
		$query = $this->db->get_where('members_ip',$option);
		return $query->num_rows();
	}
	
	public function get_member_ip($user_id,$perpage,$offset)
	{
		$option = array('user_id'=>$user_id);
				 $this->db->order_by("id", "desc");
				 $this->db->limit($perpage, $offset);
		$query = $this->db->get_where('members_ip',$option);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}
	
		function member_add_money($user_id)
	{
		$date=$this->general->get_local_time('time');
		
		
		$currency = $this->input->post('currency_code');
		if(!$currency)
			$currency = DEFAULT_CURRENCY_CODE;
		
		$amt=$this->input->post('amount');
		$pay_type=$this->input->post('payment_method');
		$bid=$this->input->post('credit_get');		
		$pay_details=$this->input->post('transaction_name');
		
		if($amt<=0)
 		{$db_cr="DEBIT";}
		else if ($pay_type=='deduct' || $pay_type=='refund')
	 	{$db_cr="DEBIT";}
	 	else
 		{$db_cr="CREDIT";}
		
		$mem_credit=$this->get_member_credit($user_id);
		
		if ($pay_type=='deduct' || $pay_type=="refund")
 		{
			$totalCredit=$mem_credit-$bid;
		}
 		else
		{
 			$totalCredit=$mem_credit+$bid;
		}
		
		//update member balance
		$mem_data=array('balance'=>$totalCredit);
		$this->db->where('id', $user_id);
		$this->db->update('members', $mem_data); 
		
		//insert into transaction table
		$tran_data=array('transaction_type'=>$pay_type,'user_id'=>$user_id,'credit_debit'=>$db_cr,'transaction_date'=>$date,'amount'=>abs($amt),
						 'credit_get'=>$bid,'transaction_name'=>$pay_details,'transaction_status'=>'Completed','mc_currency'=>$currency);		
		$this->db->insert('transaction', $tran_data); 
	}

	function get_member_credit($user_id)
	{
		$this->db->select('balance');
		$query=$this->db->get_where('members',array('id'=>$user_id));
		$data=$query->row_array();
		return $data['balance'];
	}
	
	
	function change_member_password()
	{		
		$user_id = $this->input->post('user_id');
		
		//generate password
		$salt = $this->general->salt();		
		$password = $this->general->hash_password($this->input->post('password'),$salt);
		
		$data_profile = array('salt' => $salt,'password' => $password);
		
		$this->db->where('id', $user_id);
		$this->db->update('members', $data_profile);
		
		//Send notification email to user
		
		$user_data = $this->get_member_byid($user_id);
		$lang_id = $user_data->lang_id;
		$user_name = $user_data->user_name;
		$email = $user_data->email;
		
		$first_name = $user_data->first_name;
		$last_name = $user_data->last_name;
		$full_name = $user_data->first_name.' '.$user_data->last_name;
		
		$enable_check = $this->general->check_notification_enable('change_password_user');

		if($enable_check->is_email_notification_send == '1')
		{	

		//load email library
    	$this->load->library('email');					
		$this->load->model('email_model');		
		
		//get subjet & body
		$template=$this->email_model->get_email_template("change_password_user",$lang_id);
		if(!isset($template['subject']))
		{
			$template=$this->email_model->get_email_template("change_password_user",DEFAULT_LANG_ID);
		}
		
        $subject=$template['subject'];
        $emailbody=$template['email_body'];
		
		//check blank valude before send message
		if(isset($subject) && isset($emailbody))
		{
			//parse email
	
					$parseElement=array("USERNAME"=>$user_name,
										"SITENAME"=>SITE_NAME,
										"EMAIL"=>$email,
										"FULLNAME"=>$full_name,
										"FIRSTNAME"=>$first_name,
										"LASTNAME"=>$last_name,	
										"PASSWORD"=>$this->input->post('password'));
	
					$subject=$this->email_model->parse_email($parseElement,$subject);
					$emailbody=$this->email_model->parse_email($parseElement,$emailbody);
					
			//set the email things
//			$this->email->from(SYSTEM_EMAIL);
//			$this->email->to($email); 
//			$this->email->subject($subject);
//			$this->email->message($emailbody); 
//			$this->email->send();
			//echo $this->email->print_debugger();exit;
                        
                        $this->netcoreemail_class->send_email(SYSTEM_EMAIL,$email,$subject,$emailbody);
			
			
		}
	}

	// if($enable_check->is_sms_notification_send == '1')
	// {

	// $this->send_sms_notification($user_data);	

	// }	

	if($enable_check->is_push_notification_send == '1')
	{

	$this->send_push_notification($user_data);		

	}	
return 'Password Changed!!!';
		
}


	public function send_push_notification($user_data)
	{
		$this->load->library('email');					
		$this->load->model('email_model');		
		
		//get subjet & body
		$template=$this->email_model->get_email_template("change_password_user",$user_data->lang_id);

		if(!isset($template['subject']))
		{
			$template=$this->email_model->get_email_template("change_password_user",DEFAULT_LANG_ID);
		}
		
        $subject=$template['subject'];
        $pushbody=$template['push_message_body'];
		
		//check blank valude before send message
		if(isset($subject) && isset($pushbody))
		{
			//parse email
	
					$parseElement=array("USERNAME"=>$user_data->user_name,
										"SITENAME"=>SITE_NAME,
										"EMAIL"=>$user_data->email,
										"FULLNAME"=>$user_data->first_name.' '.$user_data->last_name,
										"FIRSTNAME"=>$user_data->first_name,
										"LASTNAME"=>$user_data->last_name,	
										"PASSWORD"=>$this->input->post('password'));
	
	$subject=$this->email_model->parse_email($parseElement,$subject);
	$pushbody=$this->email_model->parse_email($parseElement,$pushbody);

	$user_push = $this->general->get_device_id($user_data->push_id);
	$this->fcm->send($user_push,array('message'=>$pushbody,'subject'=>$subject));


	} 	
}
	
	public function get_aleady_registered_mobile()
	{
		$user_id = $this->input->post('user_id');
		
		$this->db->where('mobile',$this->input->post('mobile'));
		
		$this->db->where('id !=',$user_id);
		$query = $this->db->get('members');

		if($query->num_rows()>0)
			return TRUE;
		else return FALSE;
	}
	
	public function get_auction_info($id)
	{							
		$this->db->select('a.*,ad.*');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$this->db->where('product_id',$id);			
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
	}
	
	public function get_bid_detail($id,$uid)
	{
       $this->db->select('id,auc_id,userbid_bid_amt,bid_date,freq');
		$this->db->from('user_bids');
		$this->db->where('user_id',$uid);
		$this->db->where('auc_id',$id);
		$this->db->order_by('userbid_bid_amt','asc');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result();
		}	
	}
	
}
