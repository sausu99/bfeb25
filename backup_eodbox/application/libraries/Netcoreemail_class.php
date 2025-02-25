<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Netcoreemail_class {

    public function __construct() {

        $this->ci = & get_instance();
    }

    function send_email($from,$to,$subject,$content) {
		
		$this->ci->load->library('email');

		$this->ci->email->from($from,WEBSITE_NAME);
		$this->ci->email->to($to);
		$this->ci->email->subject($subject);
		$this->ci->email->message($content);
		$this->ci->email->send();
		
		//@mail($to, $subject, $content);
		
		return true;
        $from = $from;
        $fromname = "Chasebid";
        $to = $to; //Recipients list (semicolon separated)
        $api_key = "5b930131818ec772a7e61a85a1d9eb83";
        $subject = $subject;
        $content = $content;

        $data = array();
        $data['subject'] = rawurlencode($subject);
        $data['fromname'] = rawurlencode($fromname);
        $data['api_key'] = $api_key;
        $data['from'] = $from;
        $data['content'] = rawurlencode($content);
        $data['recipients'] = $to;
        $apiresult = $this->callApi(@$api_type, @$action, $data);
//        echo trim($apiresult);
//        exit;
    }

//echo trim($apiresult);

    function callApi($api_type = '', $api_activity = '', $api_input = '') {
        $data = array();
        $result = $this->http_post_form("https://api.falconide.com/falconapi/web.send.rest", $api_input);
        return $result;
    }

    function http_post_form($url, $data, $timeout = 20) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RANGE, "1-2000000");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_REFERER, @$_SERVER['REQUEST_URI']);
        $result = curl_exec($ch);
        $result = curl_error($ch) ? curl_error($ch) : $result;
        curl_close($ch);
        return $result;
    }

}
