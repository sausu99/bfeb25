<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instamojo_ipn extends CI_Controller {

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
		
		$post_string = '';    
			  foreach ($_POST as $field=>$value) { 
				 $this->ipn_data["$field"] = $value;
				 $post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
			  }
		  
		
		$this->send_test_email('Before Validate IPN',$post_string);//test email
		
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