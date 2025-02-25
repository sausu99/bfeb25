<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Checkmobi {

    public function __construct() {

        $this->ci = & get_instance();
    }

    public function sendSMS($auth_token, $to, $text) {
        $this->ch = curl_init();
        $options = array(
            CURLOPT_URL => 'https://api.checkmobi.com/v1/sms/send',
            CURLOPT_USERAGENT => 'checkmobi/curl',
            CURLOPT_SSLVERSION => 0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HEADER => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_VERBOSE => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE);

        $headers = array('Authorization: ' . $auth_token);

        $options[CURLOPT_POST] = TRUE;

        //if(is_array($params))
        //{
        $json_params = json_encode(array('to' => $to, 'text' => $text));
        $options[CURLOPT_POSTFIELDS] = $json_params;
        array_push($headers, "Content-Type: application/json");
        array_push($headers, 'Content-Length: ' . strlen($json_params));
        //}


        $options[CURLOPT_HTTPHEADER] = $headers;
        curl_setopt_array($this->ch, $options);
        $res = curl_exec($this->ch);


        $status = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        if ($res === FALSE) {
            $err = curl_error($this->ch);
            return array("status" => $status, "response" => array("error" => $err));
            //print_r($data);
        }

        $result = json_decode($res, TRUE);
        return array("status" => $status, "response" => $result);
        //print_r($data);
    }

}
