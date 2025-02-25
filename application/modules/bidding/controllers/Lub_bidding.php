<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lub_bidding extends CI_Controller {

    function __construct() {

        parent::__construct();

        $this->load->library('my_language');

        if (SITE_STATUS == 'offline') {
            redirect($this->general->lang_uri('/offline'));
            exit;
        }
        if (SITE_STATUS == 'maintanance') {
            if (!$this->session->userdata('MAINTAINANCE_KEY') OR $this->session->userdata('MAINTAINANCE_KEY') != 'YES') {
                redirect($this->general->lang_uri('/maintanance'));
                exit;
            }
        }
        //check banned IP address
        $this->general->check_banned_ip();

        //load module
        $this->load->model('lub_bidding_model');
        $this->load->helper('email');

        //sms library
        $this->load->library('Checkmobi');
        $this->load->library('Netcoreemail_class');
        $this->load->library('fcm');
    }

    public function index() {
        redirect(site_url(), 'refresh');
        exit;
    }

    public function single_bid() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        //print_r(json_encode($_POST));exit;
        $response = array();
        $response['message'] = '';
        $response['status'] = '';

        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_method_message');
            $response['estage'] = 1; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if (!$this->session->userdata(SESSION . 'user_id') OR $this->session->userdata(SESSION . 'user_id') == '') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong>' . lang('LUB_bidding_error_invalid_method_message');
            $response['estage'] = 2; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if (!$this->input->post('amount', TRUE) OR $this->input->post('amount', TRUE) == '') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong>' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 4; // for error tracking
            print_r(json_encode($response));
            exit;
        }

        //print_r(json_encode($_POST));exit;

        $current_date_time = $this->general->get_local_time('time');
        $user_id = $this->session->userdata(SESSION . 'user_id');

        $raw_bid_value = trim($this->input->post('amount', TRUE));

        //explode values by decimal and check whether entered value is valid or not.
        $raw_bid_value_arr = explode('.', $raw_bid_value);
        //print_r(json_encode($raw_bid_value_arr));exit;
        //$bid_value = @number_format($raw_bid_value, 2);
        $bid_value = @number_format($raw_bid_value, 2, '.', '');
        //print_r(json_encode($bid_value));exit;

        $auc_id = $this->input->post('auc_id', TRUE);
        $auc_data = $this->lub_bidding_model->get_auction_by_id($auc_id);
        $auc_price = $this->general->formate_price_currency_sign($auc_data['price']);

        if (!$auc_data OR $auc_data == FALSE) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_no_auction_data_message');
            $response['estage'] = 5; // for error tracking
            print_r(json_encode($response));
            exit;
        }
        //print_r($auc_data); exit;

        $user_info = $this->lub_bidding_model->get_user_data($user_id);
        //print_r($user_info); exit;	
        if (!$user_info OR $user_info == false) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_user_not_exist_message');
            $response['estage'] = 6; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($user_info['obsence_flag'] == 'yes') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_auction_obsence_member');
            $response['estage'] = 16; // for error tracking
            print_r(json_encode($response));
            exit;
        }


        //remaining bids for auction thats ends as required bids
        //$remaining_bid = $this->lub_bidding_model->getRemainingBids($auc_id);
        //echo $remaining_bid; exit;
        //count numbers of bids placed by this user
        $count_bids = $this->lub_bidding_model->count_duplicate_bid($auc_id, $bid_value, $user_id);

        if ($bid_value == '') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 7; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($bid_value <= 0.00) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 8; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if (!is_numeric($bid_value)) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 9; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($raw_bid_value_arr[0] == '' || !isset($raw_bid_value_arr[1]) || count($raw_bid_value_arr) != 2 || (isset($raw_bid_value_arr[1]) && strlen($raw_bid_value_arr[1]) != 2)) {
            //$response['length'] = print_r($raw_bid_value_arr);
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 10; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($auc_data['status'] == "Closed") {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_auction_closed_message');
            $response['estage'] = 11; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($bid_value < MIN_BID_VALUE) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . sprintf(lang('LUB_bidding_error_not_minimum_acceptable_bid_message'), $this->general->formate_price_currency_sign(MIN_BID_VALUE)); //$this->general->formate_price_currency_sign(MIN_BID_VALUE);
            $response['estage'] = 12; // for error tracking
            print_r(json_encode($response));
            exit;
        } /*else if ($bid_value > $auc_price) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . sprintf(lang('LUB_bidding_error_not_maximum_acceptable_bid_message'), $auc_price); //Maximum acceptable bid is '.$this->general->formate_price_currency_sign($auc_data['max_bid']);
            $response['estage'] = 13; // for error tracking
            print_r(json_encode($response));
            exit;
        }*/ else if ($this->get_closing_remaining_time($auc_data['end_date']) <= 0) {
            // get_closing_remaining_time = end_date - current_date
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_auction_closed_message');
            $response['estage'] = 15; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($count_bids >= 1) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_same_price_twice_message');
            $response['estage'] = 17; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($auc_data['bid_fee'] > $user_info['balance']) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_insufficient_balance_message');
            $response['estage'] = 18; // for error tracking
            print_r(json_encode($response));
            exit;
        } else {
            /*
             * **********************
              All the conditions are fulfilled when any placed bid reaches here.
             * ***********************
             */
			
            //Find current unique bidder before placing bid.
			$previous_lowest_unique_user_id = '';
            $previous_lowest_unique_info = $this->lub_bidding_model->get_lowest_unque_bid($auc_id);
			if($previous_lowest_unique_info)
				$previous_lowest_unique_user_id = $previous_lowest_unique_info['user_id'];
				
            $bid_freq = $this->lub_bidding_model->getFrequeryOfBid($auc_id, $bid_value);

            //print_r(json_encode($previous_lowest_unique_info));exit;
            //print_r(json_encode($bid_freq));exit;
            // check balance and decide which type of credit to use
            //if($auc_data['bid_fee'] <= $user_info['free_bids']){
            //$c_u_t = 'bonus_credit'; //credit used type
            //$user_balance_bonus = $user_info['free_bids'];
            //} else if($auc_data['bid_fee'] <= $user_info['balance']){
            $c_u_t = 'normal_credit';  //credit used type
            $user_balance_normal = $user_info['balance'];
            //}
            //echo $user_balance_normal;exit;
            //Insert Bid Data into various tables according to need
            if ($bid_freq < 1) {
                $result = $this->lub_bidding_model->record_bid($auc_id, $user_id, $user_info['user_name'], $bid_value, $auc_data['bid_fee'], 1, $auc_data['end_date'], $c_u_t);

                //$this->lub_bidding_model->update_auction_placed_bid($auc_id);		
            } else {
                //newly added function
                $final_bid_freq = $bid_freq + 1;

                $result = $this->lub_bidding_model->record_bid($auc_id, $user_id, $user_info['user_name'], $bid_value, $auc_data['bid_fee'], $final_bid_freq, $auc_data['end_date'], $c_u_t);

                $this->lub_bidding_model->update_bid($auc_id, $bid_value, $final_bid_freq); //update bid frequency
                //$this->lub_bidding_model->update_auction_placed_bid($auc_id);
            }

            //Get bid status of current bid
            $bid_status = $this->lub_bidding_model->getLowestBidder($auc_id, $user_id, $bid_value);
            //if($c_u_t == 'bonus_credit'){
            // $new_balance = $user_balance_bonus - $auc_data['bid_fee'];
            //} else if($c_u_t == 'normal_credit'){
            $new_balance = $user_balance_normal - $auc_data['bid_fee'];
            //}
            //$new_balance=$user_balance-$bid_fee;

            $auc_detail_info = $this->lub_bidding_model->get_auction_details_by_auction_id($auc_data['id']);

            $auc_name = $auc_detail_info['name'];

            $lowest_unique_info = $this->lub_bidding_model->get_lowest_unque_bid($auc_id);
            //$bids_left = $remaining_bid-1;

            /* send response before email execution */
            // Send the success message before executing upcomming scripts
            ob_start();
            $response['status'] = 'success';
            $response['message'] = $bid_status; //Bid status message.
            $response['c_u_t'] = $c_u_t;
            $response['new_balance'] = number_format($new_balance);
            $response['lowest_user_id'] = $lowest_unique_info['user_id'];
            $response['previous_user_id'] = $previous_lowest_unique_user_id;
			$response['total_bids'] = $this->lub_bidding_model->count_users_bid_history_by_auction_id($auc_id, $user_id);
            //$response['width'] = '';
            //$response['bids_left'] = $bids_left;
            $response['auc_id'] = $auc_id;
            print_r(json_encode($response));

            /*$size = ob_get_length();
            header("Content-Encoding: none");
            header("Content-Length: {$size}");
            header("Connection: close");
            ob_end_flush();
            ob_flush();
            flush();*/
            /* END send response before email execution */


            // email for lowest unique bidder



            $this->lowest_user_id = $lowest_unique_info['user_id'];

            $this->previous_user_id = $previous_lowest_unique_user_id;

            if ($previous_lowest_unique_user_id != $lowest_unique_info['user_id']) {
                //Get email id & country id of current lowest unique bidder and send email
                $lub_user_info = $this->lub_bidding_model->get_user_data($lowest_unique_info['user_id']);

                $current_lub_bidder_email = $lub_user_info['email'];
                $current_lub_bidder_lang_id = $this->general->get_lang_id_by_country($lub_user_info['country']);

                $enable_check = $this->general->check_notification_enable('lowest_unique_bidder_notification_user');


                //Now Send Email to winners
                $this->lub_bidding_model->lowest_unique_email($auc_name, $current_lub_bidder_email, $lowest_unique_info['user_name'], $lowest_unique_info['userbid_bid_amt'], $current_lub_bidder_lang_id, $lub_user_info['first_name'], $lub_user_info['last_name'], $lowest_unique_info['user_id']);

              
                //get email id of user whose bid is outbid and send outbid email notification
                $outbid_user_info = $this->lub_bidding_model->get_user_data($previous_lowest_unique_user_id);
				if($outbid_user_info){
					$previous_lub_bidder_email = $outbid_user_info['email'];
					$outbidder_lang_id = $this->general->get_lang_id_by_country($outbid_user_info['country']);
	
					$enable_check_no_more = $this->general->check_notification_enable('normal_outbid_notification_user');
					
					$this->lub_bidding_model->no_more_unique_lowest_email($auc_name, $previous_lub_bidder_email, $previous_lowest_unique_info['user_name'], $previous_lowest_unique_info['userbid_bid_amt'], $outbidder_lang_id, $outbid_user_info['first_name'], $outbid_user_info['last_name'], $previous_lowest_unique_user_id);
				}
                


               
            }
        }
    }

    public function multiple_bid() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        //print_r(json_encode($_POST));exit;

        $response = array();
        $response['message'] = '';
        $response['status'] = '';

        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_method_message');
            $response['estage'] = 1; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if (!$this->session->userdata(SESSION . 'user_id') OR $this->session->userdata(SESSION . 'user_id') == '') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_method_message');
            $response['estage'] = 2; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($this->input->post('amount_f', TRUE) == '' OR $this->input->post('amount_t', TRUE) == '') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 4; // for error tracking
            print_r(json_encode($response));
            exit;
        }

        //print_r(json_encode($_POST));exit;

        $current_date_time = $this->general->get_local_time('time');
        $user_id = $this->session->userdata(SESSION . 'user_id');

        $raw_bid_value_f = trim($this->input->post('amount_f', TRUE)); //Initial (from) amount of bid value
        $raw_bid_value_t = trim($this->input->post('amount_t', TRUE)); //Final (to) amount of bid value
        //Explode bid values by decimal
        $raw_bid_value_f_arr = explode('.', $raw_bid_value_f);
        $raw_bid_value_t_arr = explode('.', $raw_bid_value_t);
        //print_r(json_encode($raw_bid_value_f_arr));exit;
        //print_r(json_encode($raw_bid_value_t_arr));exit;
        //now change numberformat of bid amount
        $bid_amt_f = @number_format($raw_bid_value_f, 2, '.', '');
        $bid_amt_t = @number_format($raw_bid_value_t, 2, '.', '');
        //echo $bid_amt_f; exit;

        $auc_id = $this->input->post('auc_id', TRUE);
        $auc_data = $this->lub_bidding_model->get_auction_by_id($auc_id);
        $auc_price = $this->general->formate_price_currency_sign($auc_data['price']);

        if (!$auc_data OR $auc_data == FALSE) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_no_auction_data_message');
            $response['estage'] = 5; // for error tracking
            print_r(json_encode($response));
            exit;
        }


        $user_info = $this->lub_bidding_model->get_user_data($user_id);
        if (!$user_info OR $user_info == FALSE) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_user_not_exist_message');
            $response['estage'] = 6; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($user_info['obsence_flag'] == 'yes') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_auction_obsence_member');
            $response['estage'] = 16; // for error tracking
            print_r(json_encode($response));
            exit;
        }

        //print_r($user_info); exit;
        //remaining bids for auction thats ends as required bids
        //$remaining_bid = $this->lub_bidding_model->getRemainingBids($auc_id);
        //echo $remaining_bid; exit;
        //Find numbers of bids placed
        //if(BID_INCREMENT > 0){
        $number_of_bid = round((($bid_amt_t - $bid_amt_f) / BID_INCREMENT) + 1); //range($bid_amt_f, $bid_amt_t, BID_INCREMENT);//
        /* }else{
          $number_of_bid = round(($bid_amt_t - $bid_amt_f)*100+1);	//if BID_INCREMENT==0, assume bid increment is 0.01
          } */
        //echo $number_of_bid;exit;
        if ($number_of_bid > USER_MAX_MULTIPLE_BIDS) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . sprintf(lang('LUB_bidding_error_max_acceptable_bid_range_message'), USER_MAX_MULTIPLE_BIDS); //Bid Not Placed. Maximum acceptable bid range is '.USER_MAX_MULTIPLE_BIDS;
            $response['estage'] = 16; // for error tracking
            print_r(json_encode($response));
            exit;
        }

        //calculate bid fee required for placing this bid range.
        $normal_bid_fee = $auc_data['bid_fee'] * $number_of_bid;
        $bonus_bid_fee = $auc_data['bid_fee'] * $number_of_bid;

        //get this users previous bids on this auction
        $previous_bids = $this->lub_bidding_model->get_user_bids_on_auction($auc_id, $user_id);
        if ($previous_bids) {
            //now check duplicate bids in loop
            for ($i = 0; $i < $number_of_bid; $i++) {
                if (in_array($bid_amt_f + ($i * BID_INCREMENT), $previous_bids)) {
                    $response['status'] = 'error';
                    $response['message'] .= "<p>" . sprintf(lang('LUB_bidding_error_already_placed_bid_message'), ($bid_amt_f + ($i * BID_INCREMENT))) . "</p>"; //You have already placed a bid for ".($bid_amt_f+($i*BID_INCREMENT))." on this auction
                    $response['estage'] = 7; // for error tracking
                }
            }
            if ($response['status'] == 'error') {
                print_r(json_encode($response));
                exit;
            }
        }


        if ($bid_amt_f == '' || $bid_amt_t == '') {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 8; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($bid_amt_f <= 0.00 || $bid_amt_t <= 0.00) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 9; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if (!is_numeric($bid_amt_f) || !is_numeric($bid_amt_t)) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 10; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($raw_bid_value_f_arr[0] == '' || !isset($raw_bid_value_f_arr[1]) || count($raw_bid_value_f_arr) != 2 || (isset($raw_bid_value_f_arr[1]) && strlen($raw_bid_value_f_arr[1]) != 2) || $raw_bid_value_t_arr[0] == '' || !isset($raw_bid_value_t_arr[1]) || count($raw_bid_value_t_arr) != 2 || (isset($raw_bid_value_t_arr[1]) && strlen($raw_bid_value_t_arr[1]) != 2)) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_invalid_bid_amount_message');
            $response['estage'] = 11; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($auc_data['status'] == "Closed") {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_auction_closed_message');
            $response['estage'] = 12; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($bid_amt_f < MIN_BID_VALUE || $bid_amt_t < MIN_BID_VALUE) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . sprinf(lang('LUB_bidding_error_not_minimum_acceptable_bid_message'), $this->general->formate_price_currency_sign(MIN_BID_VALUE)); //Bid Not Placed. Minimum acceptable bid is '.$this->general->formate_price_currency_sign(MIN_BID_VALUE);
            $response['estage'] = 13; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($bid_amt_f > $bid_amt_t) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_from_greater_then_to_message');
            $response['estage'] = 14; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($bid_amt_f == $bid_amt_t) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_from_to_price_same_message');
            $response['estage'] = 15; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($number_of_bid > USER_MAX_MULTIPLE_BIDS) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . sprinf(lang('LUB_bidding_error_max_acceptable_bid_range_message'), USER_MAX_MULTIPLE_BIDS); //Bid Not Placed. Maximum acceptable bid range is '.USER_MAX_MULTIPLE_BIDS;
            $response['estage'] = 16; // for error tracking
            print_r(json_encode($response));
            exit;
        } /*else if ($bid_amt_f > $auc_price || $bid_amt_t > $auc_price) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . sprintf(lang('LUB_bidding_error_not_maximum_acceptable_bid_message'), $this->general->formate_price_currency_sign($auc_data['max_bid'])); //Bid Not Placed. Maximum acceptable bid is '.$this->general->formate_price_currency_sign($auc_data['max_bid']);
            $response['estage'] = 17; // for error tracking
            print_r(json_encode($response));
            exit;
        }*/ else if ($this->get_closing_remaining_time($auc_data['end_date']) <= 0) {
            // get_closing_remaining_time = end_date - current_date
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_auction_closed_message');
            $response['estage'] = 19; // for error tracking
            print_r(json_encode($response));
            exit;
        } else if ($normal_bid_fee > $user_info['balance']) {
            $response['status'] = 'error';
            $response['message'] = '<strong>' . lang('LUB_bidding_error_error_text') . '</strong> ' . lang('LUB_bidding_error_insufficient_balance_message');
            $response['estage'] = 21; // for error tracking
            print_r(json_encode($response));
            exit;
        } else {
            /*
             * **********************
              All the conditions are fulfilled when any placed bid reaches here.
             * ***********************
             */
			$previous_lowest_unique_user_id = '';
            $previous_lowest_unique_info = $this->lub_bidding_model->get_lowest_unque_bid($auc_id);
			if($previous_lowest_unique_info)
				$previous_lowest_unique_user_id = $previous_lowest_unique_info['user_id'];
				
            //$bid_freq = $this->lub_bidding_model->getFrequeryOfBid($auc_id,$bid_value);
            //if($bonus_bid_fee <= $user_info['free_bids']){
            //$c_u_t = 'bonus_credit'; //credit used type
            //$user_balance_bonus = $user_info['free_bids'];
            //} else if( $normal_bid_fee<= $user_info['balance']){
            $c_u_t = 'normal_credit';  //credit used type
            $user_balance_normal = $user_info['balance'];
            //}

            for ($i = 0; $i < $number_of_bid; $i++) {
                $duplicate = $this->lub_bidding_model->checkDuplicatedBid($auc_id, $user_id, number_format(($bid_amt_f + ($i * BID_INCREMENT)), 2));
                if ($duplicate == 0) {
                    //if(in_array($bid_amt_f+($i*BID_INCREMENT), $previous_bids)){
                    $bid_amt = number_format(($bid_amt_f + ($i * BID_INCREMENT)), 2);
                    //$bid_amt=number_format($number_of_bids_a[$i],2);
                    $bid_freq = $this->lub_bidding_model->getFrequeryOfBid($auc_id, $bid_amt);

                    if ($bid_freq < 1) {
                        $users_name = ($user_info['first_name']) ? $user_info['first_name'] : $user_info['user_name'];
                        $result = $this->lub_bidding_model->record_bid($auc_id, $user_id, $users_name, $bid_amt, $auc_data['bid_fee'], 1, $auc_data['end_date'], $c_u_t);

                        //$this->lub_bidding_model->update_auction_placed_bid($auc_id);	
                    } else {
                        //newly added function
                        $final_bid_freq = $bid_freq + 1;

                        $result = $this->lub_bidding_model->record_bid($auc_id, $user_id, $user_info['first_name'], $bid_amt, $auc_data['bid_fee'], $final_bid_freq, $auc_data['end_date'], $c_u_t);

                        $this->lub_bidding_model->update_bid($auc_id, $bid_amt, $final_bid_freq);

                        //$this->lub_bidding_model->update_auction_placed_bid($auc_id);
                    }
                }
            }


            $bid_status = lang('LUB_bidding_success_message');
            //if($c_u_t == 'bonus_credit'){
            // $new_balance = $user_info['free_bids'] - $bonus_bid_fee;
            //} else if($c_u_t == 'normal_credit'){
            $lowest_unique_info = $this->lub_bidding_model->get_lowest_unque_bid($auc_id);

            $new_balance = $user_info['balance'] - $normal_bid_fee;
            //}

            /* send response before email execution */
            // Send the success message before executing upcomming scripts
            ob_start();

            $response['status'] = 'success';
            $response['message'] = $bid_status; //Bid status message.
            $response['c_u_t'] = $c_u_t;
            $response['lowest_user_id'] = $lowest_unique_info['user_id'];
            $response['previous_user_id'] = $previous_lowest_unique_user_id;
			$response['total_bids'] = $this->lub_bidding_model->count_users_bid_history_by_auction_id($auc_id, $user_id);
            //$response['width'] = '';
            //$response['bids_left'] = $bids_left;
            $response['auc_id'] = $auc_id;
            print_r(json_encode($response));

            /*$size = ob_get_length();
            header("Content-Encoding: none");
            header("Content-Length: {$size}");
            header("Connection: close");
            ob_end_flush();
            ob_flush();
            flush();*/
            /* END send response before email execution */


            $auc_detail_info = $this->lub_bidding_model->get_auction_details_by_auction_id($auc_data['id']);
            $auc_name = $auc_detail_info['name'];
            //$bids_left = $remaining_bid-$number_of_bid;
            // email for lowest unique bidder	
            //$lowest_unique_info=$this->lub_bidding_model->get_lowest_unque_bid($auc_id);





            $this->lowest_user_id = $lowest_unique_info['user_id'];

            $this->previous_user_id = $previous_lowest_unique_user_id;

            if ($previous_lowest_unique_user_id != $lowest_unique_info['user_id']) {
                //Get email id & country id of current lowest unique bidder and send email
                $lub_user_info = $this->lub_bidding_model->get_user_data($lowest_unique_info['user_id']);
                $current_lub_bidder_email = $lub_user_info['email'];
                $current_lub_bidder_lang_id = $this->general->get_lang_id_by_country($lub_user_info['country']);

                $enable_check = $this->general->check_notification_enable('lowest_unique_bidder_notification_user');


                //Now Send Email to winner & Admin
                $this->lub_bidding_model->lowest_unique_email($auc_name, $current_lub_bidder_email, $lowest_unique_info['user_name'], $lowest_unique_info['userbid_bid_amt'], $current_lub_bidder_lang_id, $lub_user_info['first_name'], $lub_user_info['last_name'], $lowest_unique_info['user_id']);

              

                //get email id of user whose bid is outbid and send outbid email notification
                
                $outbid_user_info = $this->lub_bidding_model->get_user_data($previous_lowest_unique_user_id);
				if($outbid_user_info){
					$previous_lub_bidder_email = $outbid_user_info['email'];
					$outbidder_lang_id = $this->general->get_lang_id_by_country($outbid_user_info['country']);
	
					$enable_check_no_more = $this->general->check_notification_enable('normal_outbid_notification_user');
	
					
					$this->lub_bidding_model->no_more_unique_lowest_email($auc_name, $previous_lub_bidder_email, $previous_lowest_unique_info['user_name'], $previous_lowest_unique_info['userbid_bid_amt'], $outbidder_lang_id, $outbid_user_info['first_name'], $previous_lowest_unique_user_id);
				}


               
            }
        }
    }

    public function get_closing_remaining_time($end_time) {
        return strtotime($end_time) - strtotime($this->general->get_local_time('time'));
    }

    //controller method invoked by ajax pagination
    public function get_user_bids() {
        if (!$this->input->is_ajax_request()) {
            exit(lang('LUB_bidding_error_not_access_message'));
        }
        if (!$this->session->userdata(SESSION . 'user_id')) {
            exit(lang('LUB_bidding_error_not_access_message'));
        }

        $user_id = $this->session->userdata(SESSION . 'user_id');

        $item_per_page = intval($this->input->post('perpage', TRUE));
        $auction_id = $this->input->post('aid', TRUE);

        //Get page number from Ajax POST
        if ($this->input->post('page', TRUE)) {
            $page_number = intval($this->input->post('page', TRUE));
            if (!is_numeric($page_number)) {
                print_r(json_encode(array('response' => 'error', 'error_message' => lang('LUB_bidding_error_invalid_page_message'))));
                exit;
            }
        } else {
            $page_number = 1; //if there's no page number, set it to 1	
        }

        //print_r(json_encode($page_number)); exit;
        //print_r(json_encode($_POST)); exit;
        //get total number of records from database for pagination
        $get_total_rows = $this->lub_bidding_model->count_users_bid_history_by_auction_id($auction_id, $user_id);

        //print_r(json_encode($get_total_rows)); exit;
        //break records into pages
        $total_pages = ceil($get_total_rows / $item_per_page);

        //print_r(json_encode("total_pages :".$total_pages)); exit;
        //get starting position to fetch the records
        $page_position = (($page_number - 1) * $item_per_page);

        //Limit our results within a specified range and get the result
        $current_winner_amt = $this->general->get_winner_amt($auction_id);
        $user_bid_history = $this->lub_bidding_model->get_users_bid_history_by_auction_id($auction_id, $user_id, $item_per_page, $page_position);
        //print_r(json_encode($current_winner_amt)); exit;
        //print_r(json_encode($user_bid_history)); exit;

        $result_data = array();
        $result_data['response'] = 'error';

        if ($user_bid_history) {
            $status_type = '';
            $i = $page_position + 1;
            $bidders_data = '';
            $timeZone = $this->general->get_user_timezone_by_country($this->session->userdata(SESSION . 'country_id'));

            foreach ($user_bid_history as $biddata) {
                $class = '';
                if ($biddata->freq > 1) {
                    $status_type = lang('general_user_bid_status_nu'); //"Non Unique";
                    $class = 'class="color_red"';
                } else if ($biddata->freq == 1 && $current_winner_amt == $biddata->userbid_bid_amt) {
                    $status_type = lang('general_user_bid_status_lub'); //Lowest Unique Bid
                    $class = 'class="active color_green"';
                } else if ($biddata->freq == 1) {
                    $status_type = lang('general_user_bid_status_uth'); //"Unique, too high";
                }

                if ($biddata->user_id == $this->session->userdata(SESSION . 'user_id'))
                    $bid_amount = $this->general->formate_price_currency_sign($biddata->userbid_bid_amt);
                else
                    $bid_amount = '****';

                $bidders_data .= '<tr ' . $class . '>							
							<td>' . $this->general->convert_local_time($biddata->bid_date, $timeZone) . '</td>
							<td>' . $biddata->click_cost . '</td>
							<td>' . $bid_amount . '</td>
							<td>' . $biddata->remaining_bids . '</td>
							<td>' . $biddata->remaining_bonus . '</td>
							<td>' . $status_type . '</td>
						</tr>';
                $i++;
            }

            $pagination_nav_data = $this->general->paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages); //for pagination links
            //print_r(json_encode($pagination_nav_data)); exit;

            $result_data = array(
                'response' => 'success',
                'bidders_data' => $bidders_data,
                'pagination_nav_data' => $pagination_nav_data,
            );
        }
        print_r(json_encode($result_data));
        exit;
    }
	
	//controller method invoked by ajax pagination
    public function get_other_user_bids() {
        if (!$this->input->is_ajax_request()) {
            exit(lang('LUB_bidding_error_not_access_message'));
        }
        if (!$this->session->userdata(SESSION . 'user_id')) {
            exit(lang('LUB_bidding_error_not_access_message'));
        }

        $user_id = $this->session->userdata(SESSION . 'user_id');

        $item_per_page = intval($this->input->post('perpage', TRUE));
        $auction_id = $this->input->post('aid', TRUE);

        //Get page number from Ajax POST
        if ($this->input->post('page', TRUE)) {
            $page_number = intval($this->input->post('page', TRUE));
            if (!is_numeric($page_number)) {
                print_r(json_encode(array('response' => 'error', 'error_message' => lang('LUB_bidding_error_invalid_page_message'))));
                exit;
            }
        } else {
            $page_number = 1; //if there's no page number, set it to 1	
        }

        //get total number of records from database for pagination
        $get_total_rows = $this->lub_bidding_model->count_other_users_bid_history_by_auction_id($auction_id, $user_id);

        //print_r(json_encode($get_total_rows)); exit;
        //break records into pages
        $total_pages = ceil($get_total_rows / $item_per_page);

        //print_r(json_encode("total_pages :".$total_pages)); exit;
        //get starting position to fetch the records
        $page_position = (($page_number - 1) * $item_per_page);

        $user_bid_history = $this->lub_bidding_model->get_other_users_bid_history_by_auction_id($auction_id, $user_id, $item_per_page, $page_position);
        //print_r(json_encode($current_winner_amt)); exit;
        //print_r(json_encode($user_bid_history)); exit;

        $result_data = array();
        $result_data['response'] = 'error';

        if ($user_bid_history) {
            $status_type = '';
            $i = $page_position + 1;
            $bidders_data = '';
            $timeZone = $this->general->get_user_timezone_by_country($this->session->userdata(SESSION . 'country_id'));

            foreach ($user_bid_history as $biddata) {
                $class = '';
                
                $bidders_data .= '<tr ' . $class . '>							
							<td>' . $biddata->user_name . '</td>
							<td>' . $this->general->convert_local_time($biddata->bid_date, $timeZone,'date') . '</td>
							<td>' . $this->general->convert_local_time($biddata->bid_date, $timeZone,'time') . '</td>
						</tr>';
                $i++;
            }

            $pagination_nav_data = $this->general->paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages); //for pagination links
            //print_r(json_encode($pagination_nav_data)); exit;

            $result_data = array(
                'response' => 'success',
                'bidders_data' => $bidders_data,
                'pagination_nav_data' => $pagination_nav_data,
            );
        }
        print_r(json_encode($result_data));
        exit;
    }

}

?>