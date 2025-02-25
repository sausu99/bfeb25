<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ccavenue_model extends CI_Model 
{
	
	public function __construct() 
	{
		parent::__construct();
		
			
	}
	public $validate_ccavenue_form =  array(	
			// array('field' => 'card_number', 'label' => 'lang:profile_name', 'rules' => 'trim|required'),			
			// array('field' => 'payOption', 'label' => 'lang:register_country', 'rules' => 'required'),
			// array('field' => 'address', 'label' => 'lang:profile_address', 'rules' => 'trim|required'),
			// array('field' => 'city', 'label' => 'lang:profile_city', 'rules' => 'trim|required'),
			// array('field' => 'post_code', 'label' => 'lang:profile_post_code', 'rules' => 'trim|required'),
			// array('field' => 'phone', 'label' => 'lang:phone', 'rules' => 'trim|required')			
		);
	
	
	public function set_ccavenue_form_submit(){
				
			$this->transaction_type = $this->input->post('transaction_type');
			$order_details = "";
			if($this->transaction_type == 'purchase_credit')
			{
				//get package info
				$this->package_id = $this->input->post('package');
				$this->package_data = $this->account_module->get_bid_package_byid($this->package_id);
				$this->item_name = $this->package_data->name . ' @ ' . $this->package_data->credits.' '.lang('account_bidpack_bids');
				
				$this->total_cost = $this->general->exchange_price($this->package_data->amount);
				$this->bonus_points = $this->package_data->bonus_points;
				
				$this->invoice_id = $this->insert_purchase_credit_records_transaction();
				
				$total_bids = $this->package_data->credits;
			$extra_bids_per = "";
			if ($this->input->post('voucher')) {
				$voucher_id = $this->general->buy_bids_voucher($this->input->post('voucher'));
				if ($voucher_id > 0) {
						$extra_bids = $this->general->give_extra_bids_voucher($voucher_id, $this->package_data->credits);						
						$total_bids = $this->package_data->credits + $extra_bids;
						
						$query = $this->db->get_where("vouchers", array("id" => $voucher_id));						
						$data = $query->row();						
						$extra_bids_per = $data->extra_bids;
					}
			}
		
			$order_details = '<table class="table table-bordered">
                          
                          <tbody>
                            <tr>                              
                              <td>'.$this->item_name.'</td>                              
                              <td>'.$this->general->formate_price_currency_sign($this->total_cost).'</td>
                            </tr>';
                 if($extra_bids_per){           
              $order_details.= '<tr>                              
                              <td>Voucher Code</td>                              
                              <td>Applicable for extra bid of '.$extra_bids_per.'%</td>
                            </tr>';}
				$order_details.= '<tr>                              
                              <td>Total Bids</td>                              
                              <td>'.$total_bids.'</td>
                            </tr>
                            
                            <tr>                              
                              <td>Total Cost</td>                              
                              <td>'.$this->general->formate_price_currency_sign($this->total_cost).'</td>
                            </tr>
                            
                          </tbody>
                        </table>';
								
			}
			else if($this->transaction_type == 'pay_for_won_auction' || $this->transaction_type == 'buy_auction')
			{
				// echo "<pre>"
				//print_r($_POST);exit;
				$this->auc_win_id = $this->input->post('auc_win_id');
				$this->auc_name = $this->input->post('auc_name');
				$this->product_id = $this->input->post('product_id');
				$this->amount = $this->input->post('amount');
				$this->credit_used = $this->input->post('credit_used');
				$this->ship_cost = $this->input->post('ship_cost');
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
				// redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/index'),'refresh');
				// exit;
			}
			
			
			$this->load->library('ccavenue_class');
	
			if($this->payment_data->status =='2')
				$this->ccavenue_class->ccavenue_url = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';	 // paypal url
			else
				$this->ccavenue_class->ccavenue_url = 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction'; 
			
			$this->ccavenue_class->merchant_data=$this->payment_data->merchant_id;
	        $this->ccavenue_class->working_key=$this->payment_data->working_key;//Shared by CCAVENUES
	        $this->ccavenue_class->access_code=$this->payment_data->access_code;
			
			$this->ccavenue_class->add_field('tid', time()); // return url
			$this->ccavenue_class->add_field('merchant_id', $this->payment_data->merchant_id);			
			$this->ccavenue_class->add_field('currency', LANG_CURRENCY_CODE);
			$this->ccavenue_class->add_field('redirect_url', $this->general->lang_uri('/' . MY_ACCOUNT . '/user/ccavenue_ipn'));			
			$this->ccavenue_class->add_field('cancel_url', $this->general->lang_uri('/' . MY_ACCOUNT . '/user/ccavenue_cancel'));
			$this->ccavenue_class->add_field('order_id', $this->invoice_id);
			$this->ccavenue_class->add_field('transaction_type', $this->transaction_type);			
			$this->ccavenue_class->add_field('amount', $this->total_cost);
			
			
			/* add billing details */
			$personal_details = $this->account_module->get_user_details();
			$this->ccavenue_class->add_field('billing_name', $personal_details->first_name);
			$this->ccavenue_class->add_field('billing_address', $personal_details->address);
			$this->ccavenue_class->add_field('billing_city', $personal_details->city);
			$this->ccavenue_class->add_field('billing_state', $personal_details->state);
			$this->ccavenue_class->add_field('billing_zip', $personal_details->post_code);
			$this->ccavenue_class->add_field('billing_country', "India");
			$this->ccavenue_class->add_field('billing_tel', $personal_details->mobile);
			$this->ccavenue_class->add_field('billing_email', $personal_details->email);
			
			return $this->ccavenue_class->submit_ccavenue_post($order_details);
			//$p->dump_fields();	  // for debugging, output a table of all the fields
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
		   'payment_method' => 'ccAvenue'
		   
		);
		// echo $this->input->post('order_id',true).'-asdf';exit;
		if($this->input->post('order_id')){
			$data['order_id']=$this->input->post('order_id',true);
		}
		if($this->input->post('tid')){
			//$data['txn_id']=$this->input->post('tid',true);
		}
		// echo "<pre>";
		// print_r($data);exit;

		
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
		   'payment_method' => 'ccAvenue'
		   
		);
		// echo $this->input->post('order_id',true).'-asdf';exit;
		if($this->input->post('order_id')){
			$data['order_id']=$this->input->post('order_id',true);
		}
		if($this->input->post('tid')){
			//$data['txn_id']=$this->input->post('tid',true);
		}
		// echo "<pre>";
		// print_r($data);exit;

		
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
	
	public function insert_winner_billing_shipping()
	{
		
		$data = array(
		   'user_id' => $this->session->userdata(SESSION.'user_id') ,
		   // 'auc_won_id' => $this->auc_win_id,		   
		   'invoice_id' => $this->invoice_id,
		   'name' => $this->input->post('name'),
		   'email' => $this->input->post('email'),
		   'address' => $this->input->post('address'),
		   'address2' => $this->input->post('address2'),
		   'country' => $this->input->post('country'),
		   'city' => $this->input->post('city'),
		   'post_code' => $this->input->post('post_code'),
		   'phone' => $this->input->post('phone'),
		   'ship_name' => $this->input->post('ship_name'),
		   'ship_email' => $this->input->post('ship_email'),
		   'ship_address' => $this->input->post('ship_address'),
		   'ship_address2' => $this->input->post('ship_address2'),
		   'ship_country' => $this->input->post('ship_country'),
		   'ship_city' => $this->input->post('ship_city'),
		   'ship_post_code' => $this->input->post('ship_post_code'),
		   'country' => $this->input->post('country'),
		   'ship_phone' => $this->input->post('ship_phone')
		   
		);
		// echo "<pre>";
		// print_r($data);exit;

		if($this->auc_win_id)
		$data['auc_won_id'] = $this->auc_win_id;

		$this->db->insert('auction_winner_address', $data);
		
	}
	
	public function count_txn_id($txn_id)
	{
		$query = $this->db->get_where('transaction',array('txn_id'=>$txn_id));
		return $query->num_rows();
	}
	
	public function get_transaction_data($order_id)
	{
		
		$query = $this->db->get_where('transaction',array('order_id'=>$order_id));
		// echo $this->db->last_query();exit;
		
		if($query->num_rows()>0)
		{
			return $query->row();
		}
	}
	public function update_transaction($order_id,$txn_id){
	
		$this->db->where('order_id',$order_id);
		$this->db->update('transaction',array('transaction_status'=>'Completed','txn_id'=>$txn_id));
	


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
	
}
