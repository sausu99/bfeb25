<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Welcome extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->model('email_model');
        $this->load->model('bidding/lub_bidding_model','test_model');
        $this->load->library('fcm');
        $this->load->library('netcoreemail_class');

    }
	 	
    function index()
    {
//        echo "asdf";exit;
    	$this->test_model->lowest_unique_push_notification('test auc','suip.shesta@gmail.com','suip','0.02','1','suip','shesta','15');
    	
    	$this->test_model->send__no_more_push_notification_lowest('test auc','suip.shesta@gmail.com','suip','0.02','1','suip','shesta','15');

               
		echo $this->general->get_local_time('time');exit;
		
		// echo 'GMT Time:'.gmdate('Y-m-d H:i:s').'<br>';
		// // Convert gmt to local timezone
		// $utc_date = DateTime::createFromFormat(
  //               'Y-m-d H:i:s', 
  //              gmdate('Y-m-d H:i:s'), 
  //               new DateTimeZone('UTC')
		// );
		
		// $nyc_date = $utc_date;
		// $nyc_date->setTimeZone(new DateTimeZone('Asia/Kathmandu'));
		// echo 'Asia/Kathmandu Time: ';
		// echo $nyc_date->format('Y-m-d H:i:s').'<br>';

		// //local time to gmt 
		// echo 'GMT Time';
		//echo $this->general->convert_gmt_time('2017-02-16 09:04:00', 'Asia/Kathmandu');
		//echo $this->general->get_local_time_clock();
		
		
		//email
		
		//load email library
    	$this->load->library('email');							
		//$this->load->model('email_model');		
		
		//set the email parameters
		$this->email->from('emts.testers@gmail.com');
	
		$this->email->to("sujit2039@gmail.com"); 
		$this->email->subject('Test Local Email');
		$this->email->message('sdfsd'); 
		$this->email->send();
		echo $this->email->print_debugger();

	}
	public function instamojo()
	{
		//$this->load->library('checkmobi');
		require_once(APPPATH. 'libraries/instamojo/Instamojo.php');
		define('API_KEY_INSTAMOJO','9e59b5249afd4b295108b609e08c7359');
		define('AUTH_TOKEN_INSTAMOJO','9aecdaf8d7eee3e0952143f5f299b564');
		
		//$api = new Instamojo\Instamojo('87af130399d4eb20f941c0342ddfc2f1', 'f4911ec1699869fa37e80d7d5b98ee3d', 'https://test.instamojo.com/api/1.1/');
		$api = new Instamojo\Instamojo(API_KEY_INSTAMOJO, AUTH_TOKEN_INSTAMOJO, 'https://test.instamojo.com/api/1.1/');
		//$api = new Instamojo\Instamojo(API_KEY, AUTH_TOKEN);
		try {
			$response = $api->paymentRequestCreate(array(
				"purpose" => "FIFA 16",
				"amount" => "10.00",
				"send_email" => true,
				"email" => "sujit2039@gmail.com",
				"redirect_url" => "http://202.166.198.151:8888/test_script/instamojo/test.php",
				"webhook" => "http://202.166.198.151:8888/test_script/instamojo/test.php",
				"allow_repeated_payments" => false
				));
			//print_r($response);
			header('Location:'.$response['longurl']);
		}
		catch (Exception $e) {
			print('Error: ' . $e->getMessage());
		}
	}
	
	public function checkmobi()
	{
		$this->load->library('checkmobi');
		
		$response = $this->checkmobi->sendSMS(CHECKMOBI_SMS_API_KEY,'+9779841819958','Your code is here 98562');
		
		print_r($response);		  
		  
	}
	
	function mailchimp()
	{
				//load mailchimp library
		  $this->load->library('mailchimp_library');	
		// Add member email in the mailchimp subscribe lists
		
		$result = $this->mailchimp_library->call('lists/subscribe', array(
				'id'                => LIST_ID,
				'email'             => array('email'=>'sujititc@gmail.com'),
				//'merge_vars'        => array('FNAME'=>'Sujit', 'LNAME'=> 'Shah'),
				'double_optin'      => false,
				'update_existing'   => true,
				'replace_interests' => false,
				'send_welcome'      => false,
			));
		print_r($result);
		//Array ( [status] => error [code] => 200 [name] => List_DoesNotExist [error] => Invalid MailChimp List ID: d9708bf37a )
		//Array ( [email] => sujititc@gmail.com [euid] => cfe80c48e5 [leid] => 45500591 )
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */