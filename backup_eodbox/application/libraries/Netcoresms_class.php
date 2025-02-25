<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Netcoresms_class {

    public function __construct() {

        $this->ci = & get_instance();
    }

    public function sendSMS($target) {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $to = str_replace("+", "", $target);

        $pin = mt_rand(1000, 9999);
        $this->ci->session->set_userdata(SESSION . 'verification_code', $pin);
        $curl = curl_init();
        // $send_text ="Your+Verification+Code+is+".$pin.".+%0D%0ARegards%2C%0D%0ABID2WIN";
        $send_text="Your+Verification+Code+is+".$pin."+%0D%0ARegards%2C%0D%0AChaseBid.com";
        $req_url = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=366387&username=7506440989&password=Samurai23@&To=" . $to . "&Text=" . $send_text . "&senderid=BIDWIN";
//        echo $req_url;
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $req_url,
            CURLOPT_USERAGENT => 'Codular Sample cURL Request'
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        echo $pin;
        return $pin;

//         if(curl_exec($ch) === false)
// {
//     echo 'Curl error: ' . curl_error($ch);
// }
// else
// {
//     echo 'Operation completed without any errors';
// }

// // Close handle
// curl_close($ch);
        // echo $pin;
//        $string_data = $resp;
//        $xml = simplexml_load_string($string_data);
//        $result = (string) $xml->RESULT;
//        echo $result;exit;
//        echo "<pre>";
//        print_r($resp);exit;
//       $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//
//        if ($res === FALSE) {
//            $err = curl_error($curl);
//            return array("status" => $status, "response" => array("error" => $err));
//            //print_r($data);
//        }
//
//        $result = json_decode($res, TRUE);
//        
//        return array("status" => $status, "response" => $result);
        //print_r($data);
    }

}
