<?php
class paypal_class {
    
   var $last_error;                 // holds the last error encountered
   
   var $ipn_log;                    // bool: log IPN results to text file?
   
   var $ipn_log_file;               // filename of the IPN log
   var $ipn_response;               // holds the IPN response from paypal   
   var $ipn_data = array();         // array contains the POST values for IPN
   
   var $fields = array();           // array holds the fields to submit to paypal

   function __construct()  {
       
      // initialization constructor.  Called when class is created.
      
      $this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
      
      $this->last_error = '';
      
      $this->ipn_log_file = 'paypal_ipn.log';
      $this->ipn_log = true; 
      $this->ipn_response = '';
      
      // populate $fields array with a few default values.  See the paypal
      // documentation for a list of fields and their data types. These defaul
      // values can be overwritten by the calling script.

      $this->add_field('rm','2');           // Return method = POST
      $this->add_field('cmd','_xclick'); 
      
   }
   
   function add_field($field, $value) {
      
      // adds a key=>value pair to the fields array, which is what will be 
      // sent to paypal as POST variables.  If the value is already in the 
      // array, it will be overwritten.
            
      $this->fields["$field"] = $value;
   }

   function submit_paypal_post($order_details) {
 
      // this function actually generates an entire HTML page consisting of
      // a form with hidden elements which is submitted to paypal via the 
      // BODY element's onLoad attribute.  We do this so that you can validate
      // any POST vars from you custom form before submitting to paypal.  So 
      // basically, you'll have your own form which is submitted to your script
      // to validate the data, which in turn calls this function to create
      // another hidden form and submit to paypal.
 
      // The user will briefly see a message on the screen that reads:
      // "Please wait, your order is being processed..." and then immediately
      // is redirected to paypal.
	  $form_data = '';
	  if($order_details=="")
      $form_data.= "<html>\n";
      //$form_data.= "<head><title>Processing Payment...</title><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>\n";
	  if($order_details=="")
      $form_data.= "<body onLoad=\"document.forms['paypal_form'].submit();\">\n";
	  if($order_details=="")
      $form_data.= "<center>".lang('account_payment_process_txt');
	  else
	  	$form_data.= "<center><h3 class=\"m_btm0\">".lang('label_order_details');
		
      $form_data.= " </center>\n";
	  if($order_details)
	  	$form_data.= $order_details;
      $form_data.= "<form method=\"post\" name=\"paypal_form\" ";;
      $form_data.= "action=\"".$this->paypal_url."\">\n";

      foreach ($this->fields as $name => $value) {
         $form_data.= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
      }
	 $form_data.= '<input type="hidden" name="cbt" value="Continue >>">';
      $form_data.= "<center><br/><br/>".lang('account_payment_IFnot_redirect_txt');
      $form_data.= "<br/><br/>\n";
      $form_data.= "<input type=\"submit\" value=\"".lang('account_bttn_click_here')."\"></center>\n";
      
      $form_data.= "</form>\n";
	  if($order_details=="")
      $form_data.= "</body></html>\n";
	  return $form_data;
    
   }
   
   function validate_ipn() 
   {
		$post = file_get_contents('php://input');
		parse_str($post, $data);
		$data['cmd'] = '_notify-validate';
		$post = http_build_query($data);
		
		$ch = curl_init($this->paypal_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		
		// Execute request and get response and status code		
		$this->ipn_response = curl_exec($ch);
		$status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		
		
		 //@mail('sujit@emultitechsolution.com',$this->ipn_response.'='.$status,$this->ipn_response);
		  
		  if($status == 200 && $this->ipn_response == 'VERIFIED')
		  {  
			 // Valid IPN transaction.
			 $this->log_ipn_results(true);
			 return true;       
			 
		  } 
		  else 
		  {
	  
			 // Invalid IPN transaction.  Check the log for details.
			 $this->last_error = 'IPN Validation Failed.';
			 $this->log_ipn_results(false);   
			 return false;
			 
		  }
      
   }
   
   function log_ipn_results($success) {
       
      if (!$this->ipn_log) return;  // is logging turned off?
      
      // Timestamp
      $text = '['.date('m/d/Y g:i A').'] - '; 
      
      // Success or failure being logged?
      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$this->last_error."\n";
      
      // Log the POST variables
      $text .= "IPN POST Vars from Paypal:\n";
      foreach ($_POST as $key=>$value) {
         $text .= "$key=$value, ";
      }
 
      // Log the response from the paypal server
      $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;
      
      // Write to log
      $fp=fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n"); 

      fclose($fp);  // close file
   }

   function dump_fields() {
 
      // Used for debugging, this function will output all the field/value pairs
      // that are currently defined in the instance of the class using the
      // add_field() function.
      
      echo "<h3>paypal_class->dump_fields() Output:</h3>";
      echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>"; 
      
      ksort($this->fields);
      foreach ($this->fields as $key => $value) {
         echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";
      }
 
      echo "</table><br>"; 
   }
}         


 
