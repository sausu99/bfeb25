<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auto_close extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		if(SITE_STATUS == 'offline'){
			exit;
		}
		
		$this->load->model('auto_close_model');


		 //push notification library
		$this->load->library('fcm');

		//sms library
		//$this->load->library('Checkmobi');	
		 $this->load->library('netcoreemail_class');	
	}
	
	public function index()
	{
		
		
		$enable_check = $this->general->check_notification_enable('auction_closed_notification_user');

		//$this->test_send_email('Job Scheduler STARTED','This is test email for job scheduler!!!');
		
		$this->current_date_time = $this->general->get_local_time('time');
		//echo 'hello'; exit;
		
		$closed_auctions = $this->auto_close_model->get_all_closing_auctions();
		
		// echo '<pre>'; print_r($closed_auctions); echo '</pre>'; exit;
	
		if($closed_auctions)
		{
			foreach($closed_auctions as $closed_auction)
			{
				$this->product_id = $closed_auction->product_id;
				$auction_winner = $this->general->get_winner($closed_auction->product_id);

				
				//echo '<pre>'; print_r($auction_winner); echo '</pre>';// exit;
				if($auction_winner){
					$this->winner_id = $auction_winner['user_id'];
					$this->winner_name = $auction_winner['first_name'];
					$this->winner_full_name = $auction_winner['user_name'];//$auction_winner['first_name']." ".$auction_winner['last_name'];
					$this->winner_bid_id = $auction_winner['bid_id'];
					$this->winner_amount = $auction_winner['userbid_bid_amt'];
												
					$this->db->trans_start();
					
					if($this->winner_id)
					{
						//Insert record in the winner table
						$this->auto_close_model->insert_winner();
						
						$this->auto_close_model->update_auction_to_closed($closed_auction->id);																				
												
						if($enable_check->is_email_notification_send == '1')
						{						
						//Now Send Email to winner & Admin
						$this->auto_close_model->send_email_winner_admin();	
						}

						if($enable_check->is_sms_notification_send == '1')
						{
							$this->auto_close_model->send_sms_notification();
						}

						if($enable_check->is_push_notification_send == '1')
						{
							$this->auto_close_model->send_push_notification();
						}											
					}
					
					$this->db->trans_complete();	
				}else{
					//cancel this auction.
					$this->auto_close_model->update_auction_to_cancelled($closed_auction->id);	
				}
			}// End Foreach
		}
		
		//$this->test_send_email('Job Scheduler ENDS','This is test email for job scheduler!!!');	
	}
	
	
	function test_send_email($subject,$message)
	{
		$this->load->library('email');

		$this->email->from('demo@nepaimpressions.com');
		$this->email->to('suip.shesta4@gmail.com');
		
		$this->email->subject($subject);
		$this->email->message($message); 
		
		$this->email->send();
	}
}	
?>