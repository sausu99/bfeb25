<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();

        // Check if User has logged in
        if (!$this->general->admin_logged_in()) {
            redirect(ADMIN_LOGIN_PATH, 'refresh');
            exit;
        }

        //load CI library
        $this->load->library('form_validation');
        $this->load->library('fcm');
        //load custom module
        $this->load->model('admin_push_settings');
//        $this->load->model('language-settings/Admin_language_settings');
        //load custom helper
    }

    public function index() {

        //Changing the Error Delimiters
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules($this->admin_push_settings->validate_settings);
		if($this->input->post('sent_to')==2)
			$this->form_validation->set_rules($this->admin_push_settings->validate_balance);
			
        if ($this->form_validation->run() == TRUE) {
			if($this->input->post('sent_to')==2)
			$device_ids = $this->admin_push_settings->get_low_balance_members_device_token();
			else			
            $device_ids = $this->admin_push_settings->get_members_device_token();
         	
			//print_r($device_ids);
			//exit;
			
            $FcmToken = array();
			if($device_ids){
				foreach($device_ids as $dev){
					array_push($FcmToken,$dev->token);
					
				}
				
				
				$this->send_push_notification($FcmToken, $this->input->post('subject'), $this->input->post('message_body'));
				//$message_data = array("title" =>  $this->input->post('subject'), "body" =>  $this->input->post('message_body'), "icon" =>  "", "image" =>  "", "click_action" =>  site_url());
				//$this->fcm->sendMultiplepush($ids_array, $message_data);
				$this->session->set_flashdata('message','Push notification has been sent to all successfully.');
			}
			else{
				$this->session->set_flashdata('message','There is no any device found.');
			}
			 
			
            //$message_data=array('subject'=>$this->input->post('subject'),'message'=>$this->input->post('message_body'));
//            echo "<pre>";
//            print_r($device_ids);exit;
        
            //$this->fcm->send_to_all($ids_array, $message_data);
            // $to="fTHlZOLREMg:APA91bHvAn6bJA7JkXncQWA0dQhAMAlyhaur_bLGkm57_p2t3p8t4wYfNdSwQl0Uxv9GnXyjlH9ePCci-EZMKASFEAJLOURgReqd0t36VODNmoVSA5lsfscoSpXt6Stp822wwWcNLF2Z";
            // $this->fcm->send($to,$message);
			//exit;
            
            redirect(ADMIN_DASHBOARD_PATH.'/notification/index','refresh');exit;
        }

        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('firebase web notification Management System | ' . SITE_NAME)
                ->build('a_view');
    }
	
	public function send_push_notification($FcmToken, $title, $body){
		
		$url = 'https://fcm.googleapis.com/fcm/send';
  // $FcmToken = array("fkT4mjw223v3bYBEGXs6f-:APA91bEA_DA1Ys0GdSe9Op_5nsdTd3yy8oatp0FhbdPe5HagUWXcQiIuq6rlJxf8WgqK3AIExx3IJDLekdbEBfb05MWQfve07t4tsQq2u2GYPqFg_r34i6zhTwddE8CjTRER1I6yC46T","eYqM3qOqX8RrLByNSISn0L:APA91bHaoYC6uFloNocqekFhtD550piwiItI4Y2pEYX18ybMvku4uB7mt8b77HSA-_QLqDUwGo2hq_JmG3BhXGK5v7Gybv2G3tbhIRcjpoLTYBRreoPAoQFDZh-6jnbeou36jjTRn5yk");
          
        $serverKey = 'AAAAEqn2174:APA91bHrY69xgS6fSe0WQp56WN19tSNOR4Ft5lF_lx98GuXgy8NPR9U1EtUilK78Aq02b90wXpRJNqIh6loi5lkYFbZRo7fU_KfSJGtaJptY5OAR8S42ZKHo8YJQKj9OFJWNUw8XWVU7';
  
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
        //print_r($result);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */