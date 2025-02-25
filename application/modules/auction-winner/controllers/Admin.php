<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		// Check if User has logged in
		if (!$this->general->admin_logged_in())			
		{
			redirect(ADMIN_LOGIN_PATH, 'refresh');exit;
		}
			
		//load CI library
			$this->load->library('form_validation');
			$this->load->library('pagination');

		//load custom module
			$this->load->model('admin_winner');
                        $this->load->library('Netcoreemail_class');

			//$this->load->library('Checkmobi');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	public function index()
	{	
		
		if($this->uri->segment(4) == '2')
		{
			$status = 2; 
		}
		else
		{
			$status = 1;//It means Item not shipped
		}
				
		//set pagination configuration			
		$config['base_url'] = site_url(ADMIN_DASHBOARD_PATH).'/auction-winner/index/'.$status;
		$config['total_rows'] = $this->admin_winner->get_toal_auc_winner($status);
		$config['num_links'] = 10;
		$config['prev_link'] = 'Prev';
		$config['next_link'] = 'Next';
		$config['per_page'] = '30'; 
		$config['next_tag_open'] = '<span>';
		$config['next_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span>';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '<span>';
		$config['num_tag_close'] = '</span>';
		
		$config['uri_segment'] = '5';
		$offset=$this->uri->segment(5,0);	
		$this->pagination->initialize($config); 
		
		$this->data['result_data'] = $this->admin_winner->get_auction_winner($status,$config['per_page'],$offset);
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Auctions Winner View | '. SITE_NAME)
			->build('winner_view', $this->data);	
		
	}
	
	public function details($auction_winner_id)
	{	
		
		if($auction_winner_id == "")		
		{
			redirect(site_url(ADMIN_DASHBOARD_PATH).'/auction-winner/index', 'refresh');exit;
		}
		
		
		//For changing the shipping status
		$this->form_validation->set_rules('shipping', 'Shipping Status ', 'required');
		if ($this->form_validation->run() == TRUE)
		{
			//Update winner shipping status
			$this->admin_winner->update_shipping_status();
			//Send email to winner if shipping status change to shipped.
			if($this->input->post('shipping') == 2)
			{

				$enable_check = $this->general->check_notification_enable('shipping_status_email_winnner');

				if($enable_check->is_email_notification_send == '1')
				{			

				$this->send_shipping_status_email_winnner($auction_winner_id);
				}

				if($enable_check->is_sms_notification_send == '1')
				{			

				$this->send_shipping_status_sms_winnner($auction_winner_id);
				}				


			}
			$this->session->set_flashdata('message','The shipping status changed successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/auction-winner/index/'.$this->input->post('shipping'),'refresh');
			exit;
		}
		
		$this->data['won_details'] = $this->admin_winner->get_auction_winner_details($auction_winner_id);

		if($this->data['won_details'] == false)		
		{
			redirect(site_url(ADMIN_DASHBOARD_PATH).'/auction-winner/index', 'refresh');exit;
		}
		
		$this->data['auc_name'] = $this->admin_winner->get_auction_name_by_user_lang($this->data['won_details']->id,$this->data['won_details']->lang_id);		

		$this->data['won_txn_details'] = $this->admin_winner->get_winner_transaction_details($this->data['won_details']->invoice_id);
		
		$this->data['winner_address'] = $this->admin_winner->get_winner_address($this->data['won_details']->auction_winner_id,$this->data['won_details']->user_id);
		
		
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Auctions Winner Details | '. SITE_NAME)
			->build('winner_details', $this->data);	
		
	}

	function send_shipping_status_sms_winnner($auction_winner_id)
	{
		//load email library
    	$this->load->library('email');			
		$this->load->model('email_model');		
		
		//get winner info
		$user_info = $this->admin_winner->get_auction_winner_details($auction_winner_id);
		$user_email = $user_info->email;
		$user_name = $user_info->user_name;
		$user_lang_id = $user_info->lang_id;
		
		$auction_info = $this->admin_winner->get_auction_name_by_user_lang($user_info->id,$user_lang_id);		
		$product_name = $auction_info->name;
		//$this->data['won_txn_details'] = $this->admin_winner->get_winner_transaction_details($this->data['won_details']->invoice_id);		
		//$this->data['winner_address'] = $this->admin_winner->get_winner_address($this->data['won_details']->auction_winner_id,$this->data['won_details']->user_id);
		
		
		
		
						
		
		
		//Get auction closed template for winner
		$template=$this->email_model->get_email_template("shipping_status_email_winnner",$user_lang_id);
		if(!isset($template['subject']))
		{
			$template=$this->email_model->get_email_template("shipping_status_email_winnner",DEFAULT_LANG_ID);
		}
		
        $subject=$template['subject'];
        $smsbody=$template['sms_body'];
		
				//parse email
                $parseElement=array("USERNAME"=>$user_name,
                                    "SITENAME"=>SITE_NAME,
                                    "AUCTIONNAME"=>$product_name,                                    
                                    "DATE"=>$this->general->get_local_time('time'));

                $subject=$this->email_model->parse_email($parseElement,$subject);
                $smsbody=$this->email_model->parse_email($parseElement,$smsbody);
	
	$this->checkmobi->sendSMS(CHECKMOBI_SMS_API_KEY,$user_info->mobile,$smsbody);			



	}
	
	function send_shipping_status_email_winnner($auction_winner_id)
	{
		
		//load email library
    	$this->load->library('email');			
		$this->load->model('email_model');		
		
		//get winner info
		$user_info = $this->admin_winner->get_auction_winner_details($auction_winner_id);
		$user_email = $user_info->email;
		$user_name = $user_info->user_name;
		$user_lang_id = $user_info->lang_id;
		
		$auction_info = $this->admin_winner->get_auction_name_by_user_lang($user_info->id,$user_lang_id);		
		$product_name = $auction_info->name;
		//$this->data['won_txn_details'] = $this->admin_winner->get_winner_transaction_details($this->data['won_details']->invoice_id);		
		//$this->data['winner_address'] = $this->admin_winner->get_winner_address($this->data['won_details']->auction_winner_id,$this->data['won_details']->user_id);
		
		
		
		
						
		
		
		//Get auction closed template for winner
		$template=$this->email_model->get_email_template("shipping_status_email_winnner",$user_lang_id);
		if(!isset($template['subject']))
		{
			$template=$this->email_model->get_email_template("shipping_status_email_winnner",DEFAULT_LANG_ID);
		}
		
        $subject=$template['subject'];
        $emailbody=$template['email_body'];
		
				//parse email
                $parseElement=array("USERNAME"=>$user_name,
                                    "SITENAME"=>SITE_NAME,
                                    "AUCTIONNAME"=>$product_name,                                    
                                    "DATE"=>$this->general->get_local_time('time'));

                $subject=$this->email_model->parse_email($parseElement,$subject);
                $emailbody=$this->email_model->parse_email($parseElement,$emailbody);
				
		//set the email things
//		$this->email->from(SYSTEM_EMAIL);
//		$this->email->to($user_email); 
//		$this->email->subject($subject);
//		$this->email->message($emailbody); 
//		$this->email->send();
                
                $this->netcoreemail_class->send_email(SYSTEM_EMAIL,$user_email,$subject,$emailbody);
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */