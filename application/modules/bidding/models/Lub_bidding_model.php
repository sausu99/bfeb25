<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lub_bidding_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_auction_by_id($auc_id) {
        $this->db->select('id, product_id, end_date, bid_fee, price, status');
        $query = $this->db->get_where('auction', array('product_id' => $auc_id));
        //echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            $query->free_result();
            return $data;
        }
        return FALSE;
    }

    public function get_auction_details_by_auction_id($auc_id) {
        $this->db->select('name');
        $query = $this->db->get_where('auction_details', array('auc_id' => $auc_id, 'lang_id' => LANG_ID));

        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            $query->free_result();
            return $data;
        }
        return FALSE;
    }

    //get user record by user id
    public function get_user_data($user_id) {
        $this->db->select('balance, first_name, last_name, user_name, email,country,mobile,push_id,obsence_flag');
        $query = $this->db->get_where('members', array('id' => $user_id));
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            $query->free_result();
            return $data;
        }
        return FALSE;
    }

    function get_user_bids_on_auction($auc_id, $user_id) {
        $this->db->select('userbid_bid_amt');
        $query = $this->db->get_where('user_bids', array('auc_id' => $auc_id, 'user_id' => $user_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $data) {
                $return_data[] = $data->userbid_bid_amt;
            }
            return $return_data;
        }
        return false;
    }

    function get_user_bid_place_before($auc_id, $user_id) {
        $option = array('auc_id' => $auc_id, 'user_id' => $user_id);
        $query = $this->db->get_where('user_bids', $option);
        return $query->num_rows();
    }

    /* public function getRemainingBids($product_id)
      {
      $this->db->select('a.id, a.required_bid, count(ub.id) as bids_count');
      $this->db->from('auction a');
      $this->db->join('user_bids ub', 'ub.auc_id = a.id', 'left');
      $this->db->where(array('a.id' => $product_id));
      $this->db->group_by('a.id');
      $query = $this->db->get();
      $data = $query->row();
      return ($data->required_bid - $data->bids_count);
      } */

    public function getRemainingBids($auction_id) {
        $this->db->select('required_bid, placed_bid, (required_bid-placed_bid) as remaining_bids');
        $this->db->where(array('id' => $auction_id));
        $query = $this->db->get('auction');
        //echo $this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            $data = $query->row();
        }
        return $data->remaining_bids;
    }

    function count_duplicate_bid($auc_id, $auc_amt, $user_id) {
        $this->db->where(array('auc_id' => $auc_id, 'userbid_bid_amt' => (float) $auc_amt, 'user_id' => $user_id));
        $query = $this->db->get('user_bids');
        //echo $this->db->last_query();exit;
        return $query->num_rows();
    }

    function getNOofBidsByAuction($auc_id) {
        $this->db->select("count(*) AS no_of_bids");
        $this->db->from('user_bids ub');
        $this->db->where('ub.auc_id', $auc_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data->no_of_bids;
        }
    }

    function getFrequeryOfBid($auc_id, $auc_amt) {
        $this->db->select('userbid_bid_amt');
        $this->db->where(array('userbid_bid_amt' => (float) $auc_amt, 'auc_id' => $auc_id));
        $query = $this->db->get('user_bids');
        return $query->num_rows();
    }

    function record_bid($auc_id, $userid, $username, $bidamt, $admin_fee, $num, $end_date, $c_u_t) {
        $this->auction_id = $auc_id;

        $this->user_id = $userid;

        $this->user_name = $username;

        $this->user_bid_amt = $bidamt;

        $this->user_bid_time = $this->general->get_local_time('time');

        $this->click_cost = $admin_fee;

        $this->bid_freq = $num;

        $this->end_date = $end_date;

        //$this->auc_type = 'bid';

        $this->credit_used_type = $c_u_t;

        /*         * *********************Running Transactions*********************** */
        $this->db->trans_start();        
        
            //update member table
            $rem_balance = $this->update_member_balance();
			
			//Insert user biding record	
			$this->insert_user_bid($rem_balance);
            //insert bidding records in the transaction table
            //$this->insert_transaction_record();
        

        $this->db->trans_complete();
        /*         * *********************End Running Transactions*********************** */
    }

    public function insert_user_bid($rem_balance) {
		
		$ip_address = $this->general->get_real_ipaddr();
		
        $inserting_data = array(
            'auc_id' => $this->auction_id,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'click_cost' => $this->click_cost,
            'userbid_bid_amt' => $this->user_bid_amt,
            'bid_date' => $this->user_bid_time,
            'freq' => $this->bid_freq,
            'remaining_bids'=>$rem_balance['remaining_bids'],
            'remaining_bonus'=>$rem_balance['remaining_bonus'],
			'ip_address' => $ip_address
        );
        //$this->db->insert('user_bids', $inserting_data); 

        $insert_query = $this->db->insert_string('user_bids', $inserting_data);
        $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        $this->db->query($insert_query);
        $this->bid_id = $this->db->insert_id();
        return $this->db->affected_rows();
    }

    public function update_member_balance() {
        $this->db->select('balance,bonus_points');
        $query = $this->db->get_where('members', array('id' => $this->user_id));
        $user_balance = $query->row();
		
		$return_data = array('remaining_bids'=>$user_balance->balance,'remaining_bonus'=>$user_balance->bonus_points); 
        if ($this->credit_used_type == 'bonus_credit') {
            $user_total_balance = $user_balance->bonus_points - $this->click_cost;
            $mem_data = array('bonus_points' => $user_total_balance);
			$return_data['remaining_bonus'] = $user_total_balance;
        } else {
            $user_total_balance = $user_balance->balance - $this->click_cost;
            $mem_data = array('balance' => $user_total_balance);
			$return_data['remaining_bids'] = $user_total_balance;
        }

        if ($query->num_rows() > 0) {
            $this->db->where('id', $this->user_id);
            $this->db->update('members', $mem_data);
        }
		
		return $return_data;
    }

    function insert_transaction_record() {
        $data = array(
            'user_id' => $this->user_id,
            'auc_id' => $this->auction_id,
            'credit_used' => $this->click_cost,
            //'credit_used_type' => $this->credit_used_type,
            'credit_debit' => 'DEBIT',
            'bid_id' => $this->bid_id,
            'transaction_name' => sprintf(lang('LUB_bid_insert_txn_name'), $this->auction_id),
            'transaction_date' => $this->general->get_local_time('time'),
            'transaction_type' => 'bid',
            'transaction_status' => 'Completed',
            'payment_method' => 'direct'
        );

        $this->db->insert('transaction', $data);
        return $this->db->insert_id();
    }

    function get_users_email_by_user_id($user_id) {
        $this->db->select('email');
        $query = $this->db->get_where('members', array('id' => $user_id));

        if ($query->num_rows() > 0) {
            $data = $query->row();
            $query->free_result();
            return $data->email;
        }
        return FALSE;
    }

    //function getLowestBidder($auc_id)
    function getLowestBidder($auc_id, $user_id, $bid_value) {
        $data = array();
        $this->db->select('id,auc_id,userbid_bid_amt,user_id,freq');
        $this->db->where(array("auc_id" => $auc_id, "user_id" => $user_id, "userbid_bid_amt" => (float) $bid_value));
        $this->db->order_by('bid_date', "desc");
        $query = $this->db->get('user_bids');

        $current_winner_amt = $this->general->get_winner_amt($auc_id);

        if ($query->num_rows() > 0) {
            $data = $query->row();
            //print_r ($data);exit;
            if ($data->freq > 1) {
                $bid_status = "<strong>" . lang('LUB_bidding_bid_placed_text') . "</strong> - " . lang('LUB_bidding_not_unique_bid_message'); //"Not Unique";
            } else if ($data->freq == 1 && $current_winner_amt == $bid_value) {
                $bid_status = "<strong>" . lang('LUB_bidding_bid_placed_text') . "</strong> - " . lang('LUB_bidding_lowest_bid_message'); //"Lowest Unique Bid";			
            } else if ($data->freq == 1) {
                $bid_status = "<strong>" . lang('LUB_bidding_bid_placed_text') . "</strong> - " . lang('LUB_bidding_unique_hight_bid_placed_message'); //"Unique, Too High";
            }

            return $bid_status;
        }
        return false;
    }

    public function insert_winner() {
        $data = array(
            'bid_id' => $this->winner_bid_id,
            'auc_id' => $this->product_id,
            'user_id' => $this->winner_id,
            'won_amt' => $this->winner_amount,
            'auction_close_date' => $this->closed_date
        );
        //$this->db->insert('auction_winner', $data); 
        $insert_query = $this->db->insert_string('auction_winner', $data);
        $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        $this->db->query($insert_query);
        return $this->db->insert_id();
    }

    public function update_auction_to_closed() {
        $data = array(
            'status' => 'Closed',
            'current_winner_id' => $this->winner_id,
            'current_winner_name' => $this->winner_name,
            'current_winner_amount' => $this->winner_amount,
            'current_winner_date' => $this->closed_date
        );

        $this->db->where(array('id' => $this->product_id));

        $query = $this->db->update('auction', $data);
    }

    public function send_email_winner_admin() {
        //load email library
        $this->load->library('email');
        $this->load->model('email_model');

        //get winner info
        $user_info = $this->get_winner_info($this->winner_id);
        $user_email = $user_info->email;

        //Get auction info
        $auction_info = $this->get_auction_byproductid($this->product_id);
        $product_name = $auction_info->name;

        //Get auction closed template for winner
        $template = $this->email_model->get_email_template("auction_closed_notification_user");

//print_r(json_encode($template));exit;

        $subject = $template['subject'];
        $emailbody = $template['email_body'];

        $confirm = "<a href='" . $this->general->lang_uri('/' . MY_ACCOUNT . 'won_auction') . "'>CLICK TO PAY</a>";

        //parse email
        $parseElement = array(
            "USERNAME" => $this->winner_name,
            "SITENAME" => SITE_NAME,
            "CONFIRM" => $confirm,
            "AUCTIONNAME" => $product_name,
            "AMOUNT" => $this->general->formate_price_currency_sign($this->winner_amount),
            "DATE" => $this->closed_date
        );

        $subject = $this->email_model->parse_email($parseElement, $subject);
        $emailbody = $this->email_model->parse_email($parseElement, $emailbody);

        //set the email things
//		$this->email->from(SYSTEM_EMAIL);
//		$this->email->to($user_email); 
//		$this->email->subject($subject);
//		$this->email->message($emailbody); 
//		$this->email->send();
        $this->netcoreemail_class->send_email(SYSTEM_EMAIL, $user_email, $subject, $emailbody);

        //Get auction closed template for Admin
//		$this->email->clear();

        $template = $this->email_model->get_email_template("auction_closed_notification_admin");
        $subject = $template['subject'];
        $emailbody = $template['email_body'];

        $parseElement = array(
            "USERNAME" => $this->winner_name,
            "SITENAME" => SITE_NAME,
            "AUCTIONNAME" => $product_name,
            "AMOUNT" => $this->general->formate_price_currency_sign($this->winner_amount),
            "DATE" => $this->closed_date
        );

        //parse email
        $subject = $this->email_model->parse_email($parseElement, $subject);
        $emailbody = $this->email_model->parse_email($parseElement, $emailbody);

        //set the email things
//		$this->email->from(SYSTEM_EMAIL);
//		$this->email->to(CONTACT_EMAIL); 
//		$this->email->subject($subject);
//		$this->email->message($emailbody); 
//		$this->email->send();

        $this->netcoreemail_class->send_email(SYSTEM_EMAIL, CONTACT_EMAIL, $subject, $emailbody);
    }

    function update_auction_record() {
        $update_auction = "UPDATE emts_auction SET
								end_date=DATE_ADD( end_date, INTERVAL '$this->reset_time' SECOND )
								WHERE id='$this->auction_id'
								";
        $this->db->query($update_auction);
    }

    function update_auction_placed_bid($auc_id) {
        $this->db->where('id', $auc_id);
        $this->db->set('placed_bid', 'placed_bid+1', FALSE);
        $this->db->update('auction');
        //echo $this->db->last_query();exit;
        //$this->db->update('auction', $data);
    }

    function update_bid($auc_id, $bid_value, $final_bid_freq) {
        $data = array('freq' => $final_bid_freq);
        $this->db->where(array('auc_id' => $auc_id, 'userbid_bid_amt' => (float) $bid_value));
        $this->db->update('user_bids', $data);
    }

    function getlowestBid($auc_id) {
        $this->db->select('userbid_bid_amt');
        $this->db->order_by('userbid_bid_amt,bid_date', "asc");
        $this->db->limit(1);
        $query = $this->db->get_where('user_bids', array('auc_id' => $auc_id));
        if ($query->num_rows() > 0) {
            $data = $query->row();
            $data = $data->userbid_bid_amt;
            $query->free_result();
            return $data;
        }
        return false;
    }

    // for multiple bidding
    public function CloseAuction_ClosingDate($auc_id, $close_date) {
        $timenow = $this->general->get_local_time('time');
        if ($this->greaterDate($timenow, $close_date) == 0) {
            return 1;
        }
    }

    function greaterDate($start_date, $end_date) {
        $uts['start'] = strtotime($start_date);
        $uts['end'] = strtotime($end_date);
        $diff = $uts['end'] - $uts['start'];

        if ($diff > 0)
            return 1;
        else
            return 0;
    }

    function checkDuplicatedBid($auc_id, $userid, $bid_amt) {
        $this->db->select('userbid_bid_amt');
        $this->db->where(array('userbid_bid_amt' => (float) $bid_amt, 'auc_id' => $auc_id, 'user_id' => $userid));
        $query = $this->db->get('user_bids');

        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function lowest_unique_email($auc_name, $user_email, $user_name, $bid_value, $lang_id, $first_name, $last_name, $user_id) {
        //load email library

        $this->load->library('email');
        $this->load->model('email_model');
        $template = $this->email_model->get_email_template("lowest_unique_bidder_notification_user", $lang_id);
        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("lowest_unique_bidder_notification_user", DEFAULT_LANG_ID);
        }

        if ($template) {
            $subject = $template['subject'];
            $emailbody = $template['email_body'];
            if (isset($subject) && isset($emailbody)) {
                $parseElement = array(
                    "USERNAME" => $user_name,
                    "FIRSTNAME" => $first_name,
                    "LASTNAME" => $last_name,
                    "FULLNAME" => $first_name . ' ' . $last_name,
                    "AUCTIONNAME" => $auc_name,
                    "AMOUNT" => DEFAULT_CURRENCY_SIGN . ' ' . $bid_value,
                    "SITENAME" => SITE_NAME
                );

                $subject = $this->email_model->parse_email($parseElement, $subject);
                $emailbody = $this->email_model->parse_email($parseElement, $emailbody);

                $this->netcoreemail_class->send_email(SYSTEM_EMAIL, $user_email, $subject, $emailbody);


                $device_token = '';
                $device_token = $this->get_users_devicetoken_by_user_id($user_id);

                $device_token = '';
                $device_token = $this->get_users_devicetoken_by_user_id($user_id);
                if ($device_token !=FALSE) {

                    $message['message'] = $template;
                    $message['notify_type'] = 'lowest_unique_bidder_notification_user';
                    $this->fcm->send($device_token, $message, $parseElement);
                }



//                $this->lowest_unique_push_notification($auc_name, $user_email, $user_name, $bid_value,$lang_id,$first_name,$last_name,$user_id);
//                    $this->lowest_unique_push_notification('test auc','suip.shesta@gmail.com','suip','0.02','1','suip','shesta','15');
                //echo $this->email->print_debugger();
                //exit;	
            }
        }
    }

  
    function lowest_unique_push_notification($auc_name, $user_email, $user_name, $bid_value, $lang_id, $first_name, $last_name, $userid = false) {
        $parseElement = array(
            "USERNAME" => $user_name,
            "FIRSTNAME" => $first_name,
            "LASTNAME" => $last_name,
            "FULLNAME" => $first_name . ' ' . $last_name,
            "AUCTIONNAME" => $auc_name,
            "AMOUNT" => DEFAULT_CURRENCY_SIGN . ' ' . $bid_value,
            "SITENAME" => SITE_NAME
        );


        /* push notification start */
        $device_token = '';
        $device_token = $this->get_users_devicetoken_by_user_id($userid);
        if ($device_token != '' || $device_token != null) {
            $subject = $this->email_model->get_email_template("lowest_unique_bidder_notification_user", $lang_id);
            $message['message'] = $subject;
            $message['notify_type'] = 'lowest_unique_bidder_notification_user';
            $this->fcm->send($device_token, $message, $parseElement);
        }

        return true;
    }

    function get_users_devicetoken_by_user_id($user_id) {
        $this->db->select('device_id');
        $query = $this->db->get_where('device', array('id' => $user_id));

        if ($query->num_rows() > 0) {
            $data = $query->row();
            $query->free_result();
            return $data->device_id;
        }
        return FALSE;
    }

    function get_lowest_unque_bid($auc_id) {
        $this->db->where(array('auc_id' => $auc_id, 'freq' => '1'));
        $this->db->order_by('freq,userbid_bid_amt,bid_date', "asc");
        $this->db->limit('1');
        $query = $this->db->get_where("user_bids");

        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            $query->free_result();
            return $data;
        }
        return false;
    }

    public function get_winner_info($winner_id) {
        $query = $this->db->get_where('members', array('id' => $winner_id));

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function get_auction_byproductid($product_id) {
        $this->db->select('a.*,ad.*');
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
        $array = array('a.id' => $product_id);
        $this->db->where($array);
        $this->db->order_by("end_date", "asc");

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row();
            $query->free_result();
            return $data;
        }
        return false;
    }

    function no_more_unique_lowest_email($auc_name, $user_email, $user_name, $bid_value, $lang_id, $first_name, $last_name, $user_id) {
        //load email library
        $this->load->library('email');
        $this->load->model('email_model');

        $template = $this->email_model->get_email_template("normal_outbid_notification_user", $lang_id);

        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("normal_outbid_notification_user", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];

        $emailbody = $template['email_body'];

        if (isset($subject) && isset($emailbody)) {
            $parseElement = array(
                "USERNAME" => $user_name,
                "FIRSTNAME" => $first_name,
                "LASTNAME" => $last_name,
                "FULLNAME" => $first_name . ' ' . $last_name,
                "AUCTIONNAME" => $auc_name,
                "AMOUNT" => DEFAULT_CURRENCY_SIGN . ' ' . $bid_value,
                "SITENAME" => SITE_NAME
            );

            $subject = $this->email_model->parse_email($parseElement, $subject);
            $emailbody = $this->email_model->parse_email($parseElement, $emailbody);
            $this->netcoreemail_class->send_email(SYSTEM_EMAIL, $user_email, $subject, $emailbody);

            $device_token = '';
            $device_token = $this->get_users_devicetoken_by_user_id($user_id);
            if ($device_token != FALSE) {
                $message['message'] = $template;
                $message['notify_type'] = 'lowest_unique_bidder_notification_user';
                $this->fcm->send($device_token, $message, $parseElement);
            }

            //echo $this->email->print_debugger();
        }
    }

    public function count_users_bid_history_by_auction_id($auc_id, $user_id) {
        $this->db->select("userbid_bid_amt,user_id, freq");
        $this->db->where(array('auc_id' => $auc_id, 'user_id' => $user_id));
        //$this->db->order_by('freq ASC, userbid_bid_amt ASC');
        $query = $this->db->get('emts_user_bids');
        //echo $this->db->last_query(); exit;
        return $query->num_rows();
    }

    public function get_users_bid_history_by_auction_id($auc_id, $user_id, $perpage, $offset) {
        $data = array();
        $this->db->select("*");
        $this->db->where(array('auc_id' => $auc_id, 'user_id' => $user_id));
        //$this->db->order_by('freq ASC, userbid_bid_amt ASC');
		$this->db->order_by('id DESC');
        $this->db->limit($perpage, $offset);
        $query = $this->db->get('emts_user_bids');
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $data = $query->result();
            $query->free_result();
            return $data;
        }
    }
	public function count_other_users_bid_history_by_auction_id($auc_id, $user_id) {
        $query = $this->db->get_where('emts_user_bids',array('auc_id' => $auc_id, 'user_id !=' => $user_id));
        //echo $this->db->last_query(); exit;
        return $query->num_rows();
    }
	public function get_other_users_bid_history_by_auction_id($auc_id, $user_id, $perpage, $offset) {
        $data = array();
        $this->db->select("*");
        $this->db->where(array('auc_id' => $auc_id, 'user_id !=' => $user_id));
		$this->db->order_by('id DESC');
        $this->db->limit($perpage, $offset);
        $query = $this->db->get('emts_user_bids');
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $data = $query->result();
            $query->free_result();
            return $data;
        }
		
    }
    public function get_user_details_by_user_id($id) {
        $this->db->select('id,email,user_name,balance,token,id,country,mobile,push_id');
        $query = $this->db->get_where("members", array("id" => $id));
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
    }

}

?>