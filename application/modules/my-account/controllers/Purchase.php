<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ob_start();

class Purchase extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
                $this->load->library('Netcoreemail_class');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));exit;
		}
		
		if(!$this->session->userdata(SESSION.'user_id'))
         {
          	redirect($this->general->lang_uri(''),'refresh');exit;
         }
		
	}
	
	public function index()
	{
		redirect($this->general->lang_uri(''),'refresh');exit;
		/*$this->load->librarary('paypal_class');
		
		
		$this->paypal_class->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
		//$this->paypal_class->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';	 // paypal url
		$this->paypal_class->add_field('currency_code', 'CHF');
		$this->paypal_class->add_field('business', $this->config->item('bussinessPayPalAccountTest'));
		$this->paypal_class->add_field('return', $this->base.'/checkout/success'); // return url
		$this->paypal_class->add_field('cancel_return', $this->base.'/checkout/step4'); // cancel url
		$this->paypal_class->add_field('notify_url', $this->base.'/validate/validatePaypal'); // notify url
		$totalPrice = $this->session->userdata('totalPrice');
		$this->paypal_class->add_field('item_name', 'Testing');
		$this->paypal_class->add_field('amount', $totalPrice);
		$this->paypal_class->add_field('custom', $this->session->userdata('orderId'));
		$this->paypal_class->submit_paypal_post(); // submit the fields to paypal
		//$p->dump_fields();	  // for debugging, output a table of all the fields
		exit;*/
	}
	
	public function success()
	{
		$this->data = array();
		
		$invoice_id = ''; 
		$session_id = ''; 
		
		if($this->input->get('invoice_id', TRUE))
			{$invoice_id = $this->input->get('invoice_id', TRUE);}
		else if($this->input->post('invoice', TRUE))
			{$invoice_id = $this->input->post('invoice', TRUE);}
		else if($this->input->get('session_id', TRUE))
			{$session_id = $this->input->get('session_id', TRUE);}
		
		//$invoice_id = '1059';
		
		$tranx = $this->get_transaction_data($invoice_id,$session_id);
				//print_r($tranx);exit;
		if($tranx!=false && $tranx->transaction_type == 'purchase_credit')
		{

			if($tranx->transaction_status == 'Completed')
				$this->session->set_userdata('success_message', lang('account_msg_complete_purchase'));
			else
				$this->session->set_userdata('error_message', lang('account_msg_pending_purchase'));
						
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/purchases'),'refresh');
			exit;			
			
		}
		else if($tranx!=false && $tranx->transaction_type == 'pay_for_won_auction')
		{
			if($tranx->transaction_status == 'Completed')
			$this->session->set_userdata('success_message',lang('account_msg_complete_paid_4won_item'));
			else
				$this->session->set_userdata('error_message', lang('account_msg_pending_purchase'));
			
			//$this->data['redirect_to'] = "wonauctions";
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/wonauctions'),'refresh');
			exit;			
		}
		else if($tranx!=false && $tranx->transaction_type == 'buy_auction')
		{
			if($tranx->transaction_status == 'Completed')
				$this->session->set_userdata('success_message',lang('account_msg_complete_buy_item'));
			else
				$this->session->set_userdata('error_message', lang('account_msg_pending_purchase'));
			
			//$this->data['redirect_to'] = "wonauctions";
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/buyauctions'),'refresh');
			exit;			
		}
		else
		{
			$this->session->set_userdata('error_message', lang('account_msg_pending_purchase'));
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/purchases'),'refresh');
			exit;			
		}
		
		$this->data['account_menu_active'] = '';
		$this->data['payment_title'] = lang('account_payment_success');
		//$this->data['body'] = "<center><h2>Your order has been completed successful.</h2></center>\n<center><br/><br/> </center>";
		$this->template
			->set_layout('account')
			->enable_parser(FALSE)
			->title(lang('account_payment_success'))			
			->build('payment_success', $this->data);
	}
	
	public function success_instamojo()
	{
		//get payment method info
		$this->load->model('account_module');
		$this->payment_data = $this->account_module->get_payment_gateway_byid(2);
		
		require_once(APPPATH. 'libraries/instamojo/Instamojo.php');
			
			if($this->payment_data->status == 1)
				$api = new Instamojo\Instamojo($this->payment_data->api_key, $this->payment_data->secret_token, 'https://test.instamojo.com/api/1.1/');
			else
				$api = new Instamojo\Instamojo($this->payment_data->api_key, $this->payment_data->secret_token);
		
		try {
			$response = $api->paymentRequestPaymentStatus($_GET['payment_request_id'], $_GET['payment_id']);
			print_r($response);  // print purpose of payment request
		   // print_r($response['payment']['status']);  // print status of payment
		}
		catch (Exception $e) {
			print('Error: ' . $e->getMessage());
		}

		/*$this->data = array();
		
		$invoice_id = ''; 
		$session_id = ''; 
		
		if($this->input->get('invoice_id', TRUE))
			{$invoice_id = $this->input->get('invoice_id', TRUE);}
		else if($this->input->post('invoice', TRUE))
			{$invoice_id = $this->input->post('invoice', TRUE);}
		else if($this->input->get('session_id', TRUE))
			{$session_id = $this->input->get('session_id', TRUE);}
		
		//$invoice_id = '1059';
		
		$tranx = $this->get_transaction_data($invoice_id,$session_id);
				//print_r($tranx);exit;
		if($tranx!=false && $tranx->transaction_type == 'purchase_credit')
		{

			if($tranx->transaction_status == 'Completed')
				$this->session->set_userdata('success_message', lang('account_msg_complete_purchase'));
			else
				$this->session->set_userdata('error_message', lang('account_msg_pending_purchase'));
						
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/purchases'),'refresh');
			exit;			
			
		}
		else if($tranx!=false && $tranx->transaction_type == 'pay_for_won_auction')
		{
			if($tranx->transaction_status == 'Completed')
			$this->session->set_userdata('success_message',lang('account_msg_complete_paid_4won_item'));
			else
				$this->session->set_userdata('error_message', lang('account_msg_pending_purchase'));
			
			//$this->data['redirect_to'] = "wonauctions";
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/wonauctions'),'refresh');
			exit;			
		}
		else if($tranx!=false && $tranx->transaction_type == 'buy_auction')
		{
			if($tranx->transaction_status == 'Completed')
				$this->session->set_userdata('success_message',lang('account_msg_complete_buy_item'));
			else
				$this->session->set_userdata('error_message', lang('account_msg_pending_purchase'));
			
			//$this->data['redirect_to'] = "wonauctions";
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/buyauctions'),'refresh');
			exit;			
		}
		else
		{
			$this->session->set_userdata('error_message', lang('account_msg_pending_purchase'));
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/purchases'),'refresh');
			exit;			
		}
		
		$this->data['account_menu_active'] = '';
		$this->data['payment_title'] = lang('account_payment_success');
		//$this->data['body'] = "<center><h2>Your order has been completed successful.</h2></center>\n<center><br/><br/> </center>";
		$this->template
			->set_layout('account')
			->enable_parser(FALSE)
			->title(lang('account_payment_success'))			
			->build('payment_success', $this->data);*/
	}
	
	public function cancel()
	{
		$this->data['account_menu_active'] = '';
		$this->data['account_page_name'] = lang('account_purchase_cancel');
		$this->data['payment_title'] = lang('account_purchase_cancel');
		$this->data['body'] = lang('account_order_cacnel_txt');
		
		$this->data['meta_keys'] = 'My Account | '.SITE_NAME;
		$this->data['meta_desc'] = 'My Account | '.SITE_NAME;
		
		$this->template
			->set_layout('account')
			->enable_parser(FALSE)
			->title('My Account | '. lang('account_processing_payment'))			
			->build('payment_process', $this->data);
	}
	
	public function get_transaction_data($invoice_id,$session_id)
	{
		if(isset($invoice_id) && $invoice_id!=''){$this->db->where('invoice_id',$invoice_id);}
		else if(isset($session_id) && $session_id!=''){$this->db->where('session_id',$session_id);}
		else{return false;}
			
		$this->db->where('user_id',$this->session->userdata(SESSION.'user_id'));
		$this->db->order_by("invoice_id", "desc");
		$query = $this->db->get('transaction');
		//echo $this->db->last_query();
		if($query->num_rows()>0)
		{
			return $query->row();
		}
		return false;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */