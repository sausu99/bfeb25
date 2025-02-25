<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paypal_ipn extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));exit;
		}
			
		
		
		$this->load->model('account_module');
		$this->load->model('paypal_module');
                $this->load->library('Netcoreemail_class');
		
	}
	
	public function index()
	{
		//load paypal class
		$this->load->library('paypal_class');
		
		//get payment method info
		$this->payment_data = $this->account_module->get_payment_gateway_byid(1);
		$this->paypal_business_email = $this->payment_data->email;
		
		$post_string = '';    
			  foreach ($_POST as $field=>$value) { 
				 $this->ipn_data["$field"] = $value;
				 $post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
			  }
			  
		//for validate IPN
		if($this->payment_data->status == 2)
				$this->paypal_class->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';	 // paypal url
			else
				$this->paypal_class->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
		
		//$this->send_test_email('Before Validate IPN',$post_string);//test email
		
		//check validate IPN
		if ($this->paypal_class->validate_ipn()) 
		{
				//$this->send_test_email('Validate IPN',$post_string);//test email
				
				//set paypal variable
				$this->set_paypal_post_value();
				
				//check duplicate transaction
				if($this->paypal_module->count_txn_id($this->txn_id) == 0)
				{
					//$this->send_test_email('Transaction OK',$post_string);//test email
					
					//check payment status
					if($this->payment_status == 'Completed')
					{
						//$this->send_test_email('Status Completed',$post_string);//test email
						
						//check business id for more validation
						if(strtolower(trim($this->business))==strtolower(trim($this->paypal_business_email)))
						{
							//$this->send_test_email('Business Email Verify',$post_string);//test email
							
							//get inserted transaction record based on invoice id & custom value.
							$this->get_txn_data = $this->paypal_module->get_transaction_data($this->invoice);
							
							//check empty value
							if($this->get_txn_data)
							{
								//$this->send_test_email('Transaction Data featch',$post_string);//test email
								
								if($this->get_txn_data->amount == $this->mc_gross)
								{
									//$this->send_test_email('Amount Verify',$post_string);//test email
									
									$this->db->trans_start();//transaction start
										
										if($this->get_txn_data->transaction_type == 'purchase_credit')
										{	
												//$this->send_test_email('purchase_credit',$post_string);//test email
												//update transaction records
												$this->paypal_module->paypal_data_update();
												
												//Extra bids from voucher if they have
												$voucher_id = $this->get_txn_data->voucher_id;
												$voucher_code = $this->get_txn_data->voucher_code;
												$extra_bids = '';
												
												if(isset($voucher_id) && isset($voucher_code))
												{
													$extra_bids = $this->general->give_extra_bids_voucher($voucher_id,$this->get_txn_data->credit_get);
													//insert voucher records in transaction
													if($extra_bids)
													$this->general->transaction_records_extra_bids_voucher($this->get_txn_data->user_id,$extra_bids,$voucher_id,$voucher_code);
												}
		
												
												//update user balance
												 $this->paypal_module->update_user_balance($this->get_txn_data->credit_get,$this->get_txn_data->bonus_points,$this->get_txn_data->user_id,$extra_bids);
											
										}
										else if($this->get_txn_data->transaction_type == 'pay_for_won_auction')
										{	
												//$this->send_test_email($this->get_txn_data->auc_id.'=pay_for_won_auction='.$this->get_txn_data->user_id,$post_string);//test email
												//update transaction records
												$this->paypal_module->paypal_data_update();
												
												//check bid package auction
												//if it is bid package auction update winner shipping status as shipped=1
												//if it is bid package auction then update user balance and insert record in transaction table
												$shipping_status = 1;
												
												/*$auc_data = $this->general->get_bid_package_auc_byid($this->get_txn_data->auc_id);
												if($auc_data != false)
												{
													$shipping_status = 2;
													$this->general->transaction_records_bidpackage($this->get_txn_data->user_id,$auc_data->bids_value,$this->get_txn_data->auc_id);
													//update user balance
												 	$this->paypal_module->update_user_balance($auc_data->bids_value,'0',$this->get_txn_data->user_id,'0');
												}*/
												
												//update winner status
												$this->paypal_module->update_auction_winner($this->get_txn_data->auc_id,$this->get_txn_data->user_id,$shipping_status);
												
												
											
										}
										else if($this->get_txn_data->transaction_type == 'buy_auction')
										{
											//check bid package auction
											//if it is bid package auction update winner shipping status as shipped=1
											//if it is bid package auction then update user balance and insert record in transaction table												
											/*$auc_data = $this->general->get_bid_package_auc_byid($this->get_txn_data->auc_id);
											if($auc_data != false)
											{
												$shipping_status = 2;
												$this->general->transaction_records_bidpackage($this->get_txn_data->user_id,$auc_data->bids_value,$this->get_txn_data->auc_id);
												//update user balance
												$this->paypal_module->update_user_balance($auc_data->bids_value,'0',$this->get_txn_data->user_id,'0');
												//$this->general->update_shipping_status($shipping_status,$this->invoice);
											}*/
											
											//check bid used in this auction and give as bonus
											$credit_used = $this->get_txn_data->credit_used;
											if($credit_used > 0)
											{
												//update user balance
												$this->paypal_module->update_user_balance('0',$credit_used,$this->get_txn_data->user_id,'0');
												$this->paypal_module->insert_user_records_transaction($this->get_txn_data->user_id,$credit_used,'','buy_auction_bonus','Get Credit back as bonus from auction id:'.$this->get_txn_data->auc_id);
											}
											
											//update transaction records
											$this->paypal_module->paypal_data_update();
										}
										
									$this->db->trans_complete();//transaction end
								}
							}
						}
					}
					else
						{
							//update transaction records if transaction is pending or others
							//$this->paypal_module->paypal_data_update()
						}
					
					
					
				}
				
				
			
			
			// put your code here
		}
		
	}
	
	public function send_test_email($subject,$message)
	{
		$this->load->library('email');

		$this->email->from('emts.testers@gmail.com', 'Sujit Shah');
		$this->email->to('sujit2039@gmail.com');		
		
		$this->email->subject($subject);
		$this->email->message($message); 
		
		$this->email->send();
	}
	
	public function set_paypal_post_value()
	{
		// assign posted variables to local variables
		$this->item_name = $this->input->post('item_name');
		$this->business = $this->input->post('business');
		$this->item_number = $this->input->post('item_number');
		$this->payment_status = $this->input->post('payment_status');
		$this->mc_gross = number_format($this->input->post('mc_gross'),2,".",'');
		$this->payment_currency = $this->input->post('mc_currency');
		$this->txn_id = $this->input->post('txn_id');
		$this->receiver_email = $this->input->post('receiver_email');
		$this->receiver_id = $this->input->post('receiver_id');
		$this->quantity = $this->input->post('quantity');
		$this->num_cart_items = $this->input->post('num_cart_items');
		$this->payment_date = $this->input->post('payment_date');
		$this->first_name = $this->input->post('first_name');
		$this->last_name = $this->input->post('last_name');
		$this->payment_type = $this->input->post('payment_type');
		
		$this->payment_gross = $this->input->post('payment_gross');
		$this->payment_fee = $this->input->post('payment_fee');		
		$this->payer_email = $this->input->post('payer_email');
		$this->txn_type = $this->input->post('txn_type');
		$this->payer_status = $this->input->post('payer_status');
		/*$address_street = $this->input->post('address_street');
		$address_city = $this->input->post('address_city');
		$address_state = $this->input->post('address_state');
		$address_zip = $this->input->post('address_zip');
		$address_country = $this->input->post('address_country');
		$address_status = $this->input->post('address_status');
		$item_number = $this->input->post('item_number');
		$tax = number_format($this->input->post('tax'),2,".",'');
		$option_name1 = $this->input->post('option_name1');
		$option_selection1 = $this->input->post('option_selection1');
		$option_name2 = $this->input->post('option_name2');
		$option_selection2 = $this->input->post('option_selection2');
		$for_auction = $this->input->post('for_auction');*/
		$this->invoice = $this->input->post('invoice');
		$this->custom = $this->input->post('custom');
		$this->notify_version = $this->input->post('notify_version');
		$this->verify_sign = $this->input->post('verify_sign');
		$this->payer_business_name = $this->input->post('payer_business_name');
		$this->payer_id =$this->input->post('payer_id');
		$this->mc_currency = $this->input->post('mc_currency');
		$this->mc_fee = number_format($this->input->post('mc_fee'),2,".",'');
		$this->exchange_rate = $this->input->post('exchange_rate');
		$this->settle_currency  = $this->input->post('settle_currency');
		$this->parent_txn_id  = $this->input->post('parent_txn_id');
		$this->pending_reason  = $this->input->post('pending_reason');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */