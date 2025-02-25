<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Notification
{
	
	function __construct()
	{
		$this->ci =& get_instance();
				

	}


	// Example function call 
    //send_email_notification('register_notification', 1, 'email1@gmail.com, email2@gmail.com', 'test@test.com', 'email3@gmail.com','email4@gmail.com', array("EMAIL"=>'Bikram',"PASSWORD"=>'123456',"SITENAME"=>WEBSITE_NAME) ) );
	public function send_email_notification($email_code, $user_id, $sender_email, $receiver_email, $cc, $bcc, $parse_element=array(), $attachments=array() ){
		

			if (defined('LANG_ID')) {
			   $lang_id=LANG_ID;
			}else{
				$lang_id=$this->get_lang_id($user_id)->lang_id;

			}
		$email_template = $this->get_email_part($email_code);

		if(empty($email_template) OR $email_template == ''){
			return false;
		}
		$email_template_id = $email_template->id;
	
		// Get Template's Default Settings by admin
		$template_settings = $this->get_template_settings_byid($email_template_id,$lang_id);
		
		// print_r($template_settings);
		// exit;
		if(!$template_settings) 
			return false;		

		//Get user custom settings
		$user_settings = $this->get_user_notification_setting($email_template_id, $lang_id);

			//If user settings is not set, put template default settings to user.
		if( empty( $user_settings ) ){
			$user_settings = $template_settings;
		}

			// If admin set the template setting is not visible to user and send email notification is set yes, send email to user by default
			//Also IF user has previllage to enable/disable email notification, we have to check user's and templates default email send notification option. If both are set yes then send this sms template to user.		
	
		if(($template_settings->is_display_notification == 0 && $template_settings->is_email_notification_send ==1)  || ($template_settings->is_display_notification==1 && $template_settings->is_email_notification_send==1 && $user_settings->is_email_notification_send==1) ) {	
			

			if( $this->send_mail($sender_email, $receiver_email, $cc, $bcc, $template_settings->subject, $template_settings->email_body, $parse_element)){
				return TRUE;
			}else{
				return FALSE;
			}					
		}

		
	}


	// for admin only
	public function send_email_notification_admin($email_code, $sender_email, $receiver_email, $cc, $bcc, $parse_element=array(), $attachments=array() ){

		// get email template id using email code
		$email_template = $this->get_email_part($email_code);

		if(empty($email_template) OR $email_template == ''){
			return false;
		}
		$email_template_id = $email_template->id;
			// Get Template's Default Settings by admin
		$template_settings = $this->get_template_settings_byid($email_template_id);
			// send email
		if( $this->send_mail($sender_email, $receiver_email, $cc, $bcc, $template_settings->subject, $template_settings->email_body, $parse_element)){
			return TRUE;
		} else {
			return FALSE;
		}					
		
	}

	public function send_sms_notification($template_code, $user_id,$parse_element=array()){
		if (defined('LANG_ID')) {
			   $lang_id=LANG_ID;
			}else{
				$lang_id=$this->get_lang_id($user_id)->lang_id;

			}
		
		if(SMS_NOTIFICATION == 1){
			
		// get email template id using email code
			$sms_template = $this->get_sms_part($template_code);
			$sms_id=false;
			if(empty($sms_template) OR $sms_template == ''){
				die('test1');return false;
			}
			$template_id = $sms_template->id;

			$user_mob = $this->user_mobile($user_id);

			if(!$user_mob || $user_mob==false){
				return false; 
			}
			$receiver_mobile = $user_mob->mobile; 
			if($receiver_mobile=='' || $receiver_mobile ==0){
				return false; 
			}

			// Get Template's Default Settings by admin
			$template_settings = $this->get_template_settings_byid($template_id,$lang_id);	

			//Get user custom settings
			$user_settings = $this->get_user_notification_setting($template_id, $user_id);

			//If user settings is not set, put template default settings to user.
			if( empty( $user_settings ) ){
				$user_settings = $template_settings;
			} 
			
			// If admin set the template setting is not visible to user and send sms notification is set yes, send sms to user by default		
			//Also IF user has previllage to enable/disable sms notification, check users and templates sms send notification option. If both are set yes then send this sms template to user.	
			if(($template_settings->is_display_notification == 0 && $template_settings->is_sms_notification_send ==1 )||($template_settings->is_display_notification==1 && $template_settings->is_sms_notification_send==1 && $user_settings->is_sms_notification_send==1)) {	

				$sms_msg = $sms_template->sms_body;
				 
				$message = $this->parse_message($parse_element, $sms_msg); 
				
				try{
					$sms_id = $this->send_sms_twilio($receiver_mobile, $message );
				}
				catch (Exception $e){
					
					//echo $e->getMessage();
					//$error_message="You have provided Invalid Mobile number";
					//return TRUE;
					// throw $e->getMessage();
					 (array('error_msg'=>lang('sms_alert_message')));
				}
           		


           		if($sms_id)
           			return TRUE;
			} 			

			return FALSE;
		}
	}

	public function errorMessage() {
    //error message
    $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
    .': <b>'.$this->getMessage().'</b> is not a valid E-Mail address';
    return $errorMsg;
  }

	public function send_sms_twilio($receiver, $message){
		
		$client = new Twilio();
		$message = $client->messages->create(
		  $receiver, // +9779849670849
		  array(
		    'from' => SMS_SENDER_MOBILE, //+19097481415
		    'body' => $message // your message
		    )
		  );
		return $message->sid;
		}

	public function user_notification_settings(){	
		$userid = 1;////$this->session->userdata(SESSION.'user_id');
		$data['user_set'] = $this->import_user_settings($userid);
		$data['default_set'] = $this->import_default_settings();
		$final_result = array();

		foreach ($data['default_set'] as $defkey => $defval) {

			if( $defval->is_display_notification ==1 ){

				$final_result[$defval->id]['email_template_id'] = $defval->id;

				$final_result[$defval->id]['email_send_user'] = $defval->is_email_notification_send;

				$final_result[$defval->id]['email_send_admin'] = $defval->is_email_notification_send;

				$final_result[$defval->id]['subject'] = $defval->subject;

				if(SMS_NOTIFICATION ==1){

					$final_result[$defval->id]['sms_send_admin'] = $defval->is_sms_notification_send;

					$final_result[$defval->id]['sms_send_user'] = $defval->is_sms_notification_send;
				}

				foreach ($data['user_set'] as $usrkey => $usrval) {

					if( $defval->id == $usrval->email_template_id ){


						$final_result[$defval->id]['email_send_user'] = $usrval->is_email_notification_send;

						if(SMS_NOTIFICATION ==1){							

							$final_result[$defval->id]['sms_send_user'] = $usrval->is_sms_notification_send;
						}
						
					} 
					
				}
			}
		}

		return $final_result;
	}

	// to parse the the email which is available in the
	function parse_message($parseElement,$mail_body)
	{
		foreach($parseElement as $key=>$value)
		{
			$parserName=$key;
			$parseValue=$value;
			$mail_body=str_replace("[$parserName]",$parseValue,$mail_body);
		}

		return $mail_body;
	}

	public function send_mail($from,$to,$cc,$bcc,$subject,$body, $parse_element, $attachments = array()) { 


		$this->ci->load->library('email');

		// Configuration for email is in configure folder			

			$email_header = $this->get_email_part('email_header');

			$email_footer = $this->get_email_part('email_footer');

			$main_body = ' <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 700px;" class="responsive-table">
			<tbody>                   
				<tr>
					<td colspan="2" style="padding: 20px 0 0 0; font-size: 16px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color: #666666; font-size:13px; font-weight:normal; width:100%;" class="padding-copy">'.$body.'</td>
				</tr></tbody></table>';

				$message = $email_header->email_body.$main_body.$email_footer->email_body;

				$parsed_message = $this->parse_message($parse_element,$message);

				$this->ci->email->from($from,WEBSITE_NAME);

				$this->ci->email->to($to);

				if($cc!=''){
					$this->ci->email->cc($cc);
				}
				if($bcc!=''){
					$this->ci->email->bcc($bcc);
				}

				$this->ci->email->subject($subject);

				$this->ci->email->message($parsed_message);

				if(count($attachments)>0){

					foreach($attachments as $files){

						$this->ci->email->attach($files->path, 'attachment', $files->name);
					}
				}

		//print_r($parsed_message);

				if ($this->ci->email->send()){
			 //echo $this->ci->email->print_debugger(); exit;
					return TRUE;
				}
				else{
			 //echo $this->ci->email->print_debugger();exit;
					return FALSE;
				}

				


			}

			public function get_user_notification_setting($email_template_id, $user_id){

				$query = $this->ci->db->get_where('member_notification_settings', array('user_id'=>$user_id, 'email_template_id'=>$email_template_id));

				if($query->num_rows()==1){

					return $query->row();
				}

			}
			public function get_lang_id($user_id){
				$this->ci->db->select('co.lang_id');
				$this->ci->db->from('country co');
				$this->ci->db->join('members m','co.id=m.country_id','left');
				$this->ci->db->where('m.id',$user_id);
				$query=$this->ci->db->get();
				//echo $this->ci->db->last_query();
				if($query->num_rows()>0){
					return $query->row();

				}

			}

	//function to get email details by email code
			public function get_template_settings_byid($email_template_id,$lang_id=false)
			{		
				// echo "email";
				// echo $email_template_id;
				// exit;
				if(!$lang_id)
					$query = $this->ci->db->get_where('email_settings',array("parent_id"=>$email_template_id));
				else
					$query = $this->ci->db->get_where('email_settings',array("parent_id"=>$email_template_id,"LANG_ID"=>$lang_id));
				
				if ($query->num_rows() > 0)
				{
					return $query->row();
				} 

				return FALSE;
			}

			function get_email_part($email_code){

				$this->ci->db->select('id, email_body');
				$query = $this->ci->db->get_where('email_settings',array('email_code'=>$email_code));
				// echo $this->ci->db->last_query();
				// die();
				if( $query->num_rows() > 0 ){
					return $query->row();
				}else
				return FALSE;
			}

			function get_sms_part($template_code){

				$this->ci->db->select('id, sms_body');
				$query = $this->ci->db->get_where('email_settings',array('email_code'=>$template_code));
				if( $query->num_rows() > 0 ){
					return $query->row();
				}else
				return FALSE;
			}		

			public function import_user_settings($userid){

				$query = $this->ci->db->get_where('member_notification_settings', array('user_id'=>$userid) );

				if($query->num_rows() > 0 ){

					return $query->result();
				} else {

					return FALSE;
				}
			}
			public function import_default_settings(){

				$this->ci->db->select('id, is_display_notification, is_email_notification_send, is_sms_notification_send, subject, email_code');

				$query = $this->ci->db->get('email_settings');

				if($query->num_rows() > 0 ){

					return $query->result();
				} else {
					
					return FALSE;
				}
			}

			public function update_notification_status($field, $value, $template_id, $user_id){
				$data = array(
					$field=>$value,
					);
				$this->ci->db->where('user_id', $user_id);
				$this->ci->db->where('email_template_id', $template_id);
				if( $this->ci->db->update('member_notification_settings', $data) ){
					return TRUE;
				}else{
					return FALSE;
				}

			}

			public function insert_notification_status($field, $value, $template_id, $user_id){
				$data = array(
					$field=>$value,
					'email_template_id'=>$template_id,
					'user_id'=>$user_id
					);
				
				if( $this->ci->db->insert('member_notification_settings', $data) ){
					return TRUE;
				}else{
					return FALSE;
				}

			}

			public function get_user_settingsby_tempid($template_id, $user_id){

				$this->ci->db->select('id');
				$query = $this->ci->db->get_where('member_notification_settings', array('email_template_id'=>$template_id,'user_id'=>$user_id));

				if( $query->num_rows() > 0 ){

					return TRUE;
					
				}else{
					return FALSE;
				}
			}

			public function user_mobile($userid){
				$this->ci->db->select('mobile');
				$query = $this->ci->db->get_where('members', array('id'=>$userid));
				//echo $this->ci->db->last_query();
				if($query->num_rows() > 0){
					return $query->row();
				} else 
				return false;
			}


		}