<?php 
/**
* 
*/
class Fcm 
{
    
    // function __construct()
    // {
       
    //     define('API_ACCESS_KEY','AAAAtJhCW9I:APA91bG1qnjhFgyBgxvRQBerL5HEAO9FqgPKAuSUow89HjlNmQh9y8TfW0kwu_23s_AnGI_JbEOuen8k4vc7TGVxsJ4df2fP19CKxINKSFiJIifuzvm9DP_KKvkjj9_4f7ZQf6zv_Fi-');
      
    // }
    function index()
    {
      // $data= $this->send('eJcAKUKmCWc:APA91bEliK4gJlWBd9j7GnuYC7vbjUVOxhFNWSLNLUz1aaQbpK0AeoyD6Pep35ivbnMTNNEn62S4RTWUdeJQMPE2uqVlwG8FLXq3Wav13rIQghXQb6mxEYNgMO4f_yZUOIRoulHvR20l','Hello ,This is test');
      
    }
 // sending push message to single user by android reg id
    public function send($to, $message,$parse_element) {
        $parsed_message=$this->parse_message($parse_element,$message);  
      
        $fields = array(
            'to' => $to,
            'data' =>($parsed_message),
        );
//        print_r($fields);exit;
        $status= $this->sendPushNotificationFCM($fields);
        return $status;
    }
    //for no templa use
    public function send_to_all($registration_ids,$message){
        
       $fields = array
                (
                    'registration_ids' => $registration_ids,
                    'data' =>($message),
                );
       
//       echo "<pre>";
       
//       print_r($fields);exit;
        $status= $this->sendPushNotificationFCM($fields);
        return $status;
    }

 

 // sending push message to multiple users by android registration ids
    public function sendMultiplepush($registration_ids=false, $message=false,$parse_element=array()) 
    {
		 if($parse_element)
       	 	$parsed_message=$this->parse_message($parse_element,$message);
		 else
		 	$parsed_message = $message;
			
         if((is_array($registration_ids)) && (count($registration_ids)>0))
         {
                $fields = array
                (
                    'registration_ids' => $registration_ids,
                    'data' =>$parsed_message,
                );
                $status= $this->sendPushNotificationFCM($fields);   
               return true;
         }
         else
         {
                echo json_encode(array('error_message','No users found'));
         }
        
    }
 

     private function sendPushNotificationFCM($fields) {
         
       
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
 
        $headers = array(
            'Authorization: key=AAAAEqn2174:APA91bHrY69xgS6fSe0WQp56WN19tSNOR4Ft5lF_lx98GuXgy8NPR9U1EtUilK78Aq02b90wXpRJNqIh6loi5lkYFbZRo7fU_KfSJGtaJptY5OAR8S42ZKHo8YJQKj9OFJWNUw8XWVU7',
            'Content-Type: application/json'
        );
		
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
    
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        header('Content-Type: application/json');

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
      
        $result = curl_exec($ch);
        print_r($result);exit;
       
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
  
        curl_close($ch);
   
 
        return  $result;
    }
    function parse_message($parseElement,$mail_body)
    {   

       $notify=array();
       $notify['subject']=$mail_body['message']['subject'];
       $notify['notify_type']=$mail_body['notify_type']; 
       $body=$mail_body['message']['push_message_body'];

       foreach($parseElement as $key=>$value)
       {
            $parserName=$key;
            $parseValue=$value;
             $body=str_replace("[$parserName]",$parseValue,$body);
       } 

       $notify['message']=$body;

       return $notify;
    }
}
?>
