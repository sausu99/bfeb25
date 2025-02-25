<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instamojo_module extends CI_Model 
{
	
	public function __construct() 
	{
		parent::__construct();
		
			
	}
	
	
	public function set_payment()
	{
		//print_r($this->payment_data);exit;
		$data = array();
			$this->transaction_type = $this->input->post('transaction_type',TRUE);
			
			if($this->transaction_type == 'purchase_credit')
			{
				//get package info
				$this->package_id = $this->input->post('package',TRUE);
				$this->package_data = $this->account_module->get_bid_package_byid($this->package_id);

				$this->item_name = $this->package_data->name.':'.$this->package_data->credits;
				$this->total_cost = $this->general->exchange_price($this->package_data->amount);
				$this->bonus_points = $this->package_data->bonus_points;
				
				$this->invoice_id = $this->insert_purchase_credit_records_transaction();
			}
			else if($this->transaction_type == 'pay_for_won_auction' || $this->transaction_type == 'buy_auction')
			{
				$this->auc_win_id = $this->input->post('auc_win_id',TRUE);
				$this->auc_name = $this->input->post('auc_name',TRUE);
				$this->product_id = $this->input->post('product_id',TRUE);
				$this->amount = $this->input->post('amount',TRUE);
				$this->credit_used = $this->input->post('credit_used',TRUE);
				$this->ship_cost = $this->input->post('ship_cost',TRUE);
				$this->gross_amt = $this->amount+$this->ship_cost;
				
				if($this->transaction_type == 'buy_auction')
				$this->total_cost = $this->general->exchange_price($this->gross_amt);
				else
				$this->total_cost = $this->general->default_exchange_price($this->gross_amt);
				
				if($this->transaction_type == 'buy_auction')
					$this->item_name = 'Buy Auction: '.$this->auc_name;
				else
					$this->item_name = 'Pay for auction: '.$this->auc_name;
				
				//insert transaction 
				$this->invoice_id = $this->insert_won_auction_records_transaction();
				//insert billing & shipping address
				$this->insert_winner_billing_shipping();
			}			
			else
			{			
				redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/index'),'refresh');
				exit;
			}
			
			
			require_once(APPPATH. 'libraries/instamojo/Instamojo.php');
			
			if($this->payment_data->status == 1)
				$api = new Instamojo\Instamojo($this->payment_data->api_key, $this->payment_data->secret_token, 'https://test.instamojo.com/api/1.1/');
			else
				$api = new Instamojo\Instamojo($this->payment_data->api_key, $this->payment_data->secret_token);
			
			//Get user deatils
			$user_id = $this->session->userdata(SESSION.'user_id');
			$user_details = $this->get_user_details($user_id);
			
			
			try {
				$response = $api->paymentRequestCreate(array(
					"variants" => array('invoice_id'=>$this->invoice_id), //user invoice id instead of discount code
					"custom_fields" => array('invoice_id'=>$this->invoice_id), //user invoice id instead of discount code
					"purpose" => $this->item_name,
					"amount" => $this->total_cost,
					"send_email" => true,
					"buyer_name" => $user_details->first_name.' '.$user_details->last_name,
					"email" => $user_details->email,
					"phone" => $user_details->mobile,
					"redirect_url" => $this->general->lang_uri('/'.MY_ACCOUNT.'/purchase/success_instamojo'),
					"webhook" => $this->general->lang_uri('/'.MY_ACCOUNT.'/instamojo_ipn'),
					"allow_repeated_payments" => false
					));
				//print_r($response);
				//header('Location:'.$response['longurl']);
				$data['status'] = 'SUCCESS';
				$data['message'] = $response['longurl'];
			}
			catch (Exception $e) {
				$data['status'] = 'ERROR';
				$data['message'] = $e->getMessage();				
			}
			
			return $data;
	}
	
	public function insert_purchase_credit_records_transaction()
	{
		
		
		$data = array(
		   'user_id' => $this->session->userdata(SESSION.'user_id') ,
		   'bidpackage_id' => $this->package_data->id,
		   'amount' => $this->total_cost,
		   'bonus_points' => $this->bonus_points,
		   'credit_get' => $this->package_data->credits ,
		   'credit_debit' => 'CREDIT',
		   'transaction_name' => $this->item_name,
		   'transaction_date' => $this->general->get_local_time('time'),
		   'transaction_type' => $this->transaction_type,
		   'transaction_status' => 'Incomplete',
		   'payment_method' => 'paypal'
		   
		);
		
		//check voucher & insert id & code
		//after transaction success wll be added to the user balance & new records in the transaction table
		if($this->input->post('voucher'))
		{
			$voucher_id = $this->general->buy_bids_voucher($this->input->post('voucher'));
			if($voucher_id>0)
			{
				$data['voucher_id'] = $voucher_id;
				$data['voucher_code'] = $this->input->post('voucher');
			}
		}
		
		$this->db->insert('transaction', $data);
		return $this->db->insert_id(); 
	}
	
	public function insert_won_auction_records_transaction()
	{
		$data = array(
		   'user_id' => $this->session->userdata(SESSION.'user_id') ,
		   'auc_id' => $this->product_id,
		   'amount' => $this->total_cost,
		   'credit_debit' => 'DEBIT',
		   'credit_used' => $this->credit_used,
		   'transaction_name' => $this->item_name,
		   'transaction_date' => $this->general->get_local_time('time'),
		   'transaction_type' => $this->transaction_type,
		   'transaction_status' => 'Incomplete',
		   'payment_method' => 'paypal'
		   
		);
		$this->db->insert('transaction', $data);
		return $this->db->insert_id(); 
	}
	
	public function insert_winner_billing_shipping()
	{
		
		$data = array(
		   'user_id' => $this->session->userdata(SESSION.'user_id') ,
		   // 'auc_won_id' => $this->auc_win_id,		   
		   'invoice_id' => $this->invoice_id,
		   'name' => $this->input->post('name',TRUE),
		   'email' => $this->input->post('email',TRUE),
		   'address' => $this->input->post('address',TRUE),
		   'address2' => $this->input->post('address2',TRUE),
		   'country' => $this->input->post('country',TRUE),
		   'city' => $this->input->post('city',TRUE),
		   'post_code' => $this->input->post('post_code',TRUE),
		   'phone' => $this->input->post('phone',TRUE),
		   'ship_name' => $this->input->post('ship_name',TRUE),
		   'ship_address' => $this->input->post('ship_address',TRUE),
		   'ship_address2' => $this->input->post('ship_address2',TRUE),
		   'ship_country' => $this->input->post('ship_country',TRUE),
		   'ship_city' => $this->input->post('ship_city',TRUE),
		   'ship_post_code' => $this->input->post('ship_post_code',TRUE),
		   'country' => $this->input->post('country',TRUE),
		   'ship_phone' => $this->input->post('ship_phone',TRUE)
		   
		);

		if($this->auc_win_id)
		$data['auc_won_id'] = $this->auc_win_id;

		$this->db->insert('auction_winner_address', $data);
		
	}
	
	public function count_txn_id($txn_id)
	{
		$query = $this->db->get_where('transaction',array('txn_id'=>$txn_id));
		return $query->num_rows();
	}
	
	public function get_transaction_data($invoice)
	{
		$query = $this->db->get_where('transaction',array('invoice_id'=>$invoice));
		
		if($query->num_rows()>0)
		{
			return $query->row();
		}
	}
	public function update_user_balance($purchase_credit,$bonus_points,$user_id,$extra_bids)
	{		
		//get user current balance
		$this->db->select('balance,bonus_points');
		$query = $this->db->get_where('members', array('id'=>$user_id));
		$user_balance = $query->row();
		
		$user_total_balance = $user_balance->balance+$purchase_credit+$extra_bids;
		$user_total_bonus = $user_balance->bonus_points+$bonus_points;
		
		//update user balance
		$data=array('balance'=>$user_total_balance,'bonus_points'=>$user_total_bonus);
		$this->db->where('id',$user_id);
		$this->db->update('members', $data);
	}
	
	public function paypal_data_update()
	{
		$data=array('received_amount'=>$this->input->post('received_amount'),'receiver_email'=>$this->input->post('receiver_email'),
					'transaction_status'=>$this->input->post('payment_status'),'pending_reason'=>$this->input->post('pending_reason'),
					'payment_date'=>$this->input->post('payment_date'),'mc_gross'=>$this->input->post('mc_gross'),'mc_fee'=>$this->input->post('mc_fee'),
					'tax'=>$this->input->post('tax'),'mc_currency'=>$this->input->post('mc_currency'),'txn_id'=>$this->input->post('txn_id'),
					'txn_type'=>$this->input->post('txn_type'),'payer_email'=>$this->input->post('payer_email'),
					'payer_status'=>$this->input->post('payer_status'),'payment_type'=>$this->input->post('payment_type'),'notify_version'=>$this->input->post('notify_version'),
					'verify_sign'=>$this->input->post('verify_sign'),'date_creation'=>$this->input->post('date_creation'));																
		$this->db->where('invoice_id', $this->input->post('invoice'));
		$this->db->update('transaction', $data); 																
	}
	
	public function update_auction_winner($auc_id,$user_id,$shipping_status)
	{		
		//update user balance
		$data=array('payment_status'=>'Completed','shipping_status'=>$shipping_status);
		$this->db->where('auc_id',$auc_id);
		$this->db->where('user_id',$user_id);
		$this->db->update('auction_winner', $data);
	}
	
	public function insert_user_records_transaction($user_id,$bonus_points,$credit_get,$bonus_type,$txn_details)
	{
		$data = array(
			'user_id' => $user_id,
			'credit_debit' => 'CREDIT',   		
			'credit_get'=>$credit_get,
			'bonus_points'=>$bonus_points,
			'transaction_name' => $txn_details,
			'transaction_date' => $this->general->get_local_time('time'),
			'transaction_type' => $bonus_type,
			'transaction_status' => 'Completed',
			'payment_method' => 'direct'
			);
		$this->db->insert('transaction', $data);
		return $this->db->insert_id(); 
	}
	
	public function get_user_details($user_id)
	{
		$data=array();
		
		$this->db->select("first_name, last_name, email,mobile");		
		$query = $this->db->get_where("members",array("id"=>$user_id));

		if ($query->num_rows() > 0) 
		{
			$data=$query->row();
			return $data;				
		}	
		
		return false;	
		
	}
	
}
