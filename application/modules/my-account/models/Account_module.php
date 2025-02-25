<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account_module extends CI_Model {

    public $validate_settings = array(
        array('field' => 'first_name', 'label' => 'lang:register_fname', 'rules' => 'trim|required'),
        array('field' => 'last_name', 'label' => 'lang:register_lname', 'rules' => 'trim|required'),
        array('field' => 'email', 'label' => 'lang:register_email', 'rules' => 'trim|required|valid_email|callback_check_email_check'),
        array('field' => 'country', 'label' => 'lang:register_country', 'rules' => 'required'),
        array('field' => 'address', 'label' => 'lang:profile_address', 'rules' => 'trim|required'),
        array('field' => 'city', 'label' => 'lang:profile_city', 'rules' => 'trim|required'),		
        array('field' => 'post_code', 'label' => 'lang:profile_post_code', 'rules' => 'trim|required')
    );
    public $validate_settings_myaddress = array(
        array('field' => 'name', 'label' => 'lang:profile_name', 'rules' => 'trim|required'),
        array('field' => 'country', 'label' => 'lang:register_country', 'rules' => 'required'),
        array('field' => 'address', 'label' => 'lang:profile_address', 'rules' => 'trim|required'),
        array('field' => 'city', 'label' => 'lang:profile_city', 'rules' => 'trim|required'),
        array('field' => 'post_code', 'label' => 'lang:profile_post_code', 'rules' => 'trim|required'),
        array('field' => 'phone', 'label' => 'lang:phone', 'rules' => 'trim|required')
    );
    public $update_validate_settings = array(
        array('field' => 'fname', 'label' => 'lang:register_fname', 'rules' => 'required|trim'),
        array('field' => 'lname', 'label' => 'lang:register_lname', 'rules' => 'required|trim'),        
        array('field' => 'address', 'label' => 'lang:address', 'rules' => 'required|trim'),
        array('field' => 'city', 'label' => 'lang:city', 'rules' => 'required|trim|trim'),
		array('field' => 'state', 'label' => 'lang:profile_state', 'rules' => 'trim|required'),
        array('field' => 'zip', 'label' => 'lang:profile_post_code', 'rules' => 'required|trim'),
        array('field' => 'country', 'label' => 'lang:country', 'rules' => 'required|trim'),
        //array('field' => 'mobile', 'label' => 'lang:mobile', 'rules' => 'trim|required|callback_check_mobile'),
        array('field' => 'email', 'label' => 'lang:register_email', 'rules' => 'trim|required|valid_email|callback_check_email_check'),
    );
	
	public $validate_user_dob = array(        
        array('field' => 'dobday', 'label' => 'lang:day', 'rules' => 'required|numeric'),
        array('field' => 'dobmonth', 'label' => 'lang:month', 'rules' => 'required|numeric'),
        array('field' => 'dobyear', 'label' => 'lang:year', 'rules' => 'required|numeric|callback_under_18_check'),        
    );
	
	public $validate_user_mobile = array(
        
        array('field' => 'mobile', 'label' => 'lang:mobile', 'rules' => 'trim|required|callback_check_mobile'),
        
    );
	
    public $update_validate_verification_code = array(
        array('field' => 'verification_code', 'label' => 'lang:verification_code', 'rules' => 'required|callback_confirm_code'));
		
    public $validate_password = array(
        array('field' => 'old_password', 'label' => 'lang:old_password', 'rules' => 'required|callback_check_old_password'),
        array('field' => 'new_password', 'label' => 'lang:change_pass_new', 'rules' => 'required|min_length[6]'),
        array('field' => 're_password', 'label' => 'lang:confirm_password', 'rules' => 'required|min_length[6]|matches[new_password]')
    );

    public function __construct() {
        parent::__construct();
    }

    public function get_user_details() {
        $option = array('id' => $this->session->userdata(SESSION . 'user_id'));
        $query = $this->db->get_where('members', $option);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function get_user_shipping_details() {
        $option = array('user_id' => $this->session->userdata(SESSION . 'user_id'));
        $query = $this->db->get_where('members_address', $option);

        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function get_aleady_registered_email() {
        $this->db->where('email', $this->input->post('email'));
        $this->db->where('id !=', $this->session->userdata(SESSION . 'user_id'));
        $query = $this->db->get('members');

        if ($query->num_rows() > 0)
            return TRUE;
        else
            return NULL;
    }

    public function get_aleady_registered_mobile() {
        $this->db->where('mobile', $this->input->post('mobile'));
        $this->db->where('id !=', $this->session->userdata(SESSION . 'user_id'));
        $query = $this->db->get('members');

        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }

    public function check_diff_mobile() {
        //echo $this->input->post('mobile');exit;
        $this->db->where('mobile', $this->input->post('mobile'));
        $this->db->where('id', $this->session->userdata(SESSION . 'user_id'));
        $query = $this->db->get('members');

        if ($query->num_rows() > 0)
            return FALSE;
        else
            return TRUE;
    }

    public function update_member($user_id = false) {

       
        $data = array(
            'first_name' => $this->input->post('fname'),
            'last_name' => $this->input->post('lname'),            
            'address' => $this->input->post('address'),
            'address2' => $this->input->post('address2'),
            'country' => $this->input->post('country'),
            'city' => $this->input->post('city'),
            'gender'=>$this->input->post('gender'),
           // 'mobile' => $this->input->post('mobile'),
            'post_code' => $this->input->post('zip'),
            'last_modify_date' => $this->general->get_gmt_time('time'),
        );
       // if($this->input->post('country')=='1'){
            $data['state']=$this->input->post('state');
        //}
		
		if($this->input->post('dobday') && $this->input->post('dobmonth') && $this->input->post('dobyear')){
			$data['dob_day'] = $this->input->post('dobday');
            $data['dob_month'] = $this->input->post('dobmonth');
            $data['dob_year'] = $this->input->post('dobyear');
		}
		
        $this->db->where('id', $user_id);
        $this->db->update('members', $data);

        return $this->db->affected_rows();
    }
	
	public function update_mobile($user_id = false) {

       
        $data = array(            
            'mobile' => $this->input->post('mobile'),            
            'last_modify_date' => $this->general->get_gmt_time('time'),
        );
        
        $this->db->where('id', $user_id);
        $this->db->update('members', $data);

        //destory session veriable 
        //destory session veriable 		
        $this->session->unset_userdata(SESSION . 'verify_mobile');
        $this->session->unset_userdata(SESSION . 'verification_code');
		delete_cookie(SESSION . "verification_code");
		delete_cookie(SESSION . "mobile_number");
		delete_cookie(SESSION . "otp_count");
		/*$cookie_otp = array('name' => SESSION . "verification_code", 'value' => "", 'expire' => time() - 3600 * 2);
		$this->input->set_cookie($cookie_otp);
		
		$cookie_mobile = array('name' => SESSION . "mobile_number", 'value' => "", 'expire' => time() - 3600 * 2);
		$this->input->set_cookie($cookie_mobile);*/
			
        return $this->db->affected_rows();
    }

    public function update_new_email_confirmation_email($profile) {
        $activation_code = $this->general->random_number();
        //set member info
        $data = array('new_email' => $this->input->post('email', TRUE), 'activation_code' => $activation_code);

        //insert records in the database
        $this->db->where('id', $profile->id);
        $this->db->update('members', $data);

        //send email confirm to user
        //load email library
        $this->load->library('email');

        $this->load->model('email_model');

        //get subjet & body
        $template = $this->email_model->get_email_template("email_confirmation", $this->config->item('current_language_id'));
        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("email_confirmation", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $emailbody = $template['email_body'];

        //check blank valude before send message
        if (isset($subject) && isset($emailbody)) {
            $new_email = $this->input->post('email', TRUE);
            //generate varification encryption
            $verify_encrypt = sha1(base64_encode($profile->id . "&&" . $profile->email . "&&" . $new_email));

            //parse email			
            $confirm = "<a href='" . $this->general->lang_uri('/activation/email/' . $profile->id . '/' . $verify_encrypt) . "'>" . $this->general->lang_uri('/activation/email/' . $profile->id . '/' . $verify_encrypt) . "</a>";

            $parseElement = array("USERNAME" => $profile->user_name,
                "CONFIRM" => $confirm,
                "SITENAME" => SITE_NAME,
                "EMAIL" => $this->input->post('email'),
                "FIRSTNAME" => $this->input->post('firstname'));

            $subject = $this->email_model->parse_email($parseElement, $subject);
            $emailbody = $this->email_model->parse_email($parseElement, $emailbody);

            //set the email things
//            $this->email->from(SYSTEM_EMAIL);
//            $this->email->to($this->input->post('email', TRUE));
//            $this->email->subject($subject);
//            $this->email->message($emailbody);
//            $this->email->send();
            
            $this->netcoreemail_class->send_email(SYSTEM_EMAIL,$this->input->post('email', TRUE),$subject,$emailbody);
            
            
        }
    }

    public function update_new_email_sms_notification($profile) {
        $activation_code = $this->general->random_number();
        //set member info
        $data = array('new_email' => $this->input->post('email', TRUE), 'activation_code' => $activation_code);

        //insert records in the database
        $this->db->where('id', $profile->id);
        $this->db->update('members', $data);

        //send email confirm to user
        //load email library
        $this->load->library('email');

        $this->load->model('email_model');

        //get subjet & body
        $template = $this->email_model->get_email_template("email_confirmation", $this->config->item('current_language_id'));
        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("email_confirmation", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $smsbody = $template['sms_body'];

        //check blank valude before send message
        if (isset($subject) && isset($smsbody)) {
            $new_email = $this->input->post('email', TRUE);
            //generate varification encryption
            $verify_encrypt = sha1(base64_encode($profile->id . "&&" . $profile->email . "&&" . $new_email));

            //parse email			
            $confirm = "<a href='" . $this->general->lang_uri('/activation/email/' . $profile->id . '/' . $verify_encrypt) . "'>" . $this->general->lang_uri('/activation/email/' . $profile->id . '/' . $verify_encrypt) . "</a>";

            $parseElement = array("USERNAME" => $profile->user_name,
                "CONFIRM" => $confirm,
                "SITENAME" => SITE_NAME,
                "EMAIL" => $this->input->post('email'),
                "FIRSTNAME" => $this->input->post('firstname'));

            $subject = $this->email_model->parse_email($parseElement, $subject);
            $smsbody = $this->email_model->parse_email($parseElement, $smsbody);

            $this->checkmobi->sendSMS(CHECKMOBI_SMS_API_KEY, $profile->mobile, $smsbody);
        }
    }

    public function update_new_email_push_notification($profile) {
        $activation_code = $this->general->random_number();
        //set member info
        $data = array('new_email' => $this->input->post('email', TRUE), 'activation_code' => $activation_code);

        //insert records in the database
        $this->db->where('id', $profile->id);

        $this->db->update('members', $data);

        //send email confirm to user
        //load email library
        $this->load->library('email');

        $this->load->model('email_model');

        //get subjet & body
        $template = $this->email_model->get_email_template("email_confirmation", $this->config->item('current_language_id'));
        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("email_confirmation", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $pushbody = $template['push_message_body'];

        //check blank valude before send message
        if (isset($subject) && isset($pushbody)) {
            $new_email = $this->input->post('email', TRUE);
            //generate varification encryption
            $verify_encrypt = sha1(base64_encode($profile->id . "&&" . $profile->email . "&&" . $new_email));

            //parse email			
            $confirm = "<a href='" . $this->general->lang_uri('/activation/email/' . $profile->id . '/' . $verify_encrypt) . "'>" . $this->general->lang_uri('/activation/email/' . $profile->id . '/' . $verify_encrypt) . "</a>";

            $parseElement = array("USERNAME" => $profile->user_name,
                "CONFIRM" => $confirm,
                "SITENAME" => SITE_NAME,
                "EMAIL" => $this->input->post('email'),
                "FIRSTNAME" => $this->input->post('firstname'));

            $subject = $this->email_model->parse_email($parseElement, $subject);
            $pushbody = $this->email_model->parse_email($parseElement, $pushbody);

            $user_push = $this->general->get_device_id($profile->push_id);

            $this->fcm->send($user_push, array('message' => $pushbody, 'subject' => $subject));
        }
    }

    public function check_old_password() {
        $option = array('id' => $this->session->userdata(SESSION . 'user_id'));
        $query = $this->db->get_where('members', $option);

        if ($query->num_rows() > 0) {
            $user_data = $query->row();
            $user_password = $user_data->password;
            $salt = $user_data->salt;
            $password = $this->general->hash_password($this->input->post('old_password', TRUE), $salt);

            if ($user_password === $password) {
                return TRUE;
            }
        }

        return false;
    }

    public function change_password() {
        //generate password
        $salt = $this->general->salt();
        $password = $this->general->hash_password($this->input->post('new_password', TRUE), $salt);

        //set member info
        $data = array(
            'password' => $password,
            'salt' => $salt,
            'last_modify_date' => $this->general->get_local_time('time')
        );
        //insert records in the database
        $this->db->where('id', $this->session->userdata(SESSION . 'user_id'));
        $this->db->update('members', $data);
    }

    public function update_user_shipping_address() {

        //set member info
        $data = array(
            'name' => $this->input->post('name', TRUE),
            'country_id' => $this->input->post('country', TRUE),
            'address' => $this->input->post('address', TRUE),
            'address2' => $this->input->post('address2', TRUE),
            'city' => $this->input->post('city', TRUE),
            'post_code' => $this->input->post('post_code', TRUE),
            'phone' => $this->input->post('phone', TRUE),
            'last_modify_date' => $this->general->get_local_time('time')
        );
        $option = array('user_id' => $this->session->userdata(SESSION . 'user_id'));
        $query = $this->db->get_where('members_address', $option);
        if ($query->num_rows() == 1) {
            //update records in the database
            $this->db->where('id', $this->input->post('id', TRUE));
            $this->db->where('user_id', $this->session->userdata(SESSION . 'user_id'));
            $this->db->update('members_address', $data);
        } else {
            //insert records in the database
            $data['user_id'] = $this->session->userdata(SESSION . 'user_id');
            $this->db->insert('members_address', $data);
        }
    }

    public function cancel_user_account() {
        //set member info
        $data = array('status' => 'close',
            'last_modify_date' => $this->general->get_local_time('time')
        );
        //insert records in the database
        $this->db->where('id', $this->session->userdata(SESSION . 'user_id'));
        $this->db->update('members', $data);

        //destory user session

        $array_items = array(SESSION . 'user_id' => '', SESSION . 'first_name' => '', SESSION . 'email' => '', SESSION . 'last_name' => '', SESSION . 'username' => ''
            , SESSION . 'balance' => '', SESSION . 'last_login' => '');
        $this->session->unset_userdata($array_items);
    }

    public function total_my_transaction() {

        $option = array('user_id' => $this->session->userdata(SESSION . 'user_id'), 'bid_id' => '0', 'transaction_name !=' => '', 'transaction_status !=' => 'Incomplete', 'transaction_type !=' => 'buy_auction');
        $this->db->where("transaction_type != 'pay_for_won_auction'");
        $this->db->order_by("invoice_id", "desc");
        $query = $this->db->get_where('transaction', $option);

        return $query->num_rows();
    }

    public function get_my_transaction($limit = 10, $offset = 0) {

        $option = array('user_id' => $this->session->userdata(SESSION . 'user_id'), 'bid_id' => '0', 'transaction_name !=' => '', 'transaction_status !=' => 'Incomplete', 'transaction_type !=' => 'buy_auction');
        $this->db->where("transaction_type != 'pay_for_won_auction'");
        $this->db->order_by("invoice_id", "desc");
        $this->db->limit($limit, $offset);
        $query = $this->db->get_where('transaction', $option);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }
	
	 public function get_my_bid_credit($limit = 10) {
		$this->db->select('T.invoice_id, T.amount, T.credit_get, T.bonus_points, T.transaction_name, T.transaction_date, T.txn_id, BP.name');
		$this->db->from('transaction T');
		$this->db->join('bidpackage BP','BP.id=T.bidpackage_id');
        $this->db->where(array('T.user_id' => $this->session->userdata(SESSION . 'user_id'), 'T.bidpackage_id !=' => '0', 'T.transaction_status' => 'Completed'));
        $this->db->order_by("T.invoice_id", "desc");
        $this->db->limit($limit);
				 
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function get_my_live_bid_auctions($user_id = false, $langid = false) {
        //get language id from configure file
        if ($langid) {
            $lang_id = $langid;
        } else {
            $lang_id = $this->config->item('current_language_id');
        }

        // $this->db->select("a.id, a.product_id, a.image1, a.current_winner_name, ad.name, count(*) AS no_bids");
        //$this->db->select("a.id, a.product_id,a.image1, a.end_date, ad.name, a.price, count(*) AS no_bids");
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->join('user_bids ub', 'ub.auc_id=a.product_id', 'left');
        $this->db->where('user_id', $user_id);
        $this->db->where('a.status', 'Live');
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('ad.lang_id', $lang_id);
        $this->db->where('ad.name IS NOT NULL');

        $this->db->order_by("a.end_date", "desc");
        $this->db->group_by("a.product_id");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }
	
	public function get_total_live_bid_auctions($user_id) {

		$this->db->select('a.id');
        $this->db->from('auction a');
        $this->db->join('user_bids ub', 'ub.auc_id=a.product_id', 'left');
        $this->db->where('ub.user_id', $user_id);
        $this->db->where('a.status', 'Live');
        $this->db->where('a.is_display', 'Yes');
		$this->db->group_by("a.product_id");
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function total_my_won_auctions($user_id) {
        //get language id from configure file		
        $lang_id = $this->config->item('current_language_id');

        $this->db->select("a.id, a.product_id, a.image1, a.end_date, ad.name, w.won_amt, w.payment_status, w.shipping_status");
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->join('auction_winner w', 'w.auc_id=a.product_id', 'left');
        $this->db->where('user_id', $user_id);
        $this->db->where("(a.status = 'Closed' OR a.status = 'Dispatched')");
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('ad.lang_id', $lang_id);
        $this->db->where('w.won_amt IS NOT NULL');

        $this->db->order_by("a.end_date", "desc");
        $this->db->group_by("a.product_id");
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
        return $query->num_rows();
    }

    public function get_my_won_auctions($user_id, $limit = 10, $offset = 0) {
        //get language id from configure file		
        $lang_id = $this->config->item('current_language_id');

        $this->db->select("a.id, a.product_id, a.image1, a.end_date, ad.name, w.won_amt, w.payment_status, w.shipping_status");
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->join('auction_winner w', 'w.auc_id=a.product_id', 'left');
        $this->db->where('user_id', $user_id);
        $this->db->where("(a.status = 'Closed' OR a.status = 'Dispatched')");
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('ad.lang_id', $lang_id);
        $this->db->where('w.won_amt IS NOT NULL');

        $this->db->order_by("a.end_date", "desc");
        $this->db->limit($limit, $offset);
        $this->db->group_by("a.product_id");
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function get_my_won_auctions_byid($product_id) {
        //get language id from configure file
        $lang_id = $this->config->item('current_language_id');

        $this->db->select("a.id, a.product_id, a.image1, a.end_date, a.shipping_cost, w.id AS auc_win_id, ad.name, w.won_amt, w.payment_status, w.shipping_status");
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->join('auction_winner w', 'w.auc_id=a.product_id', 'left');
        $this->db->where('w.user_id', $this->session->userdata(SESSION . 'user_id'));
        $this->db->where('w.auc_id', $product_id);
        $this->db->where('w.payment_status', 'Incomplete');
        $this->db->where('a.status', 'Closed');
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('ad.lang_id', $lang_id);
        $this->db->where('w.won_amt IS NOT NULL');

        $this->db->order_by("a.end_date", "desc");
        $this->db->group_by("a.product_id");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function get_shopping_items($limit = false, $start = false) {
        $user_id = $this->session->userdata(SESSION . 'user_id');

        $this->db->select('a.id, a.product_id, a.price, a.image1, pd.name, pd.description, m.first_name, m.last_name, so.order_datetime, so.unit_price, so.shipping_charge, so.total_cost,so.delivery_url');

        $this->db->from('auction a');
        $this->db->join('auction_details pd', 'pd.auc_id = a.id', 'left');
        $this->db->join('auction_order so', 'so.auc_id = a.id', 'left');
        $this->db->join('members m', 'm.id = so.user_id ', 'left');

        $this->db->where('so.payment_status', 'Completed');
        $this->db->where('m.id', $user_id);
        //$this->db->where('a.status','closed');

        $this->db->order_by("so.order_datetime", "DESC");
        $this->db->group_by('so.order_id');
        // $this->db->limit($limit,$start);

        $query = $this->db->get('auction_order');

        //echo $this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function get_watch_list() {
		$user_id = $this->session->userdata(SESSION . 'user_id');
		$lang_id = $this->config->item('current_language_id');
		
		$this->db->select('a.*,ad.name,ad.meta_desc, COUNT(ub.id) AS total_bids');
        $this->db->from('member_watch_lists mwl');
        $this->db->join('auction a', 'a.product_id=mwl.auction_id', 'LEFT');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->join('user_bids ub', 'ub.auc_id=a.product_id', 'left');
		$this->db->where(array('mwl.user_id' => $user_id, 'ad.lang_id' => LANG_ID));        
        $this->db->where('a.status !=', 'Closed');
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('ad.name IS NOT NULL');

        $this->db->order_by("a.end_date", "desc");
        $this->db->group_by("a.product_id");
        $query = $this->db->get();
		
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function get_close_auctions_byid($product_id) {
        //get language id from configure file
        $lang_id = $this->config->item('current_language_id');

        $this->db->select("a.id, a.product_id, a.image1, a.end_date, a.shipping_cost,a.price, ad.name");
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->where('a.product_id', $product_id);
        $this->db->where('a.status !=', 'Live');
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('a.is_buy_now', 'Yes');
        $this->db->where('ad.lang_id', $lang_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function get_live_auctions_byid($product_id) {
        //get language id from configure file
        $lang_id = $this->config->item('current_language_id');

        $this->db->select("a.id, a.product_id, a.image1, a.end_date, a.shipping_cost,a.price,a.buy_now_price, a.no_qty, ad.name");
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->where('a.product_id', $product_id);
        $this->db->where('a.status', 'Live');
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('a.is_buy_now', 'Yes');
        $this->db->where('ad.lang_id', $lang_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function get_all_bid_package() {
        $this->db->order_by("amount", "ASC");
        $query = $this->db->get('bidpackage');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function get_bid_package_byid($id) {
        $this->db->where("id", $id);
        $query = $this->db->get('bidpackage');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function get_all_bonus_package() {
        $this->db->order_by("bonus_points", "ASC");
        $query = $this->db->get('bonuspackage');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function get_bonus_package_byid($id) {
        $this->db->where("id", $id);
        $query = $this->db->get('bonuspackage');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function get_all_payment_gateway() {
        $this->db->order_by("id", "DESC"); 
		//$this->db->limit("1"); 
        $query = $this->db->get_where('payment_gateway', array('is_display' => 'Yes'));

        if ($query->num_rows() > 0) {
            return $query->result();
        }
		return false;
    }

    public function get_payment_gateway_byid($id) {
        $query = $this->db->get_where('payment_gateway', array('id' => $id, 'is_display' => 'Yes'));

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function get_user_upaid_auction() {
        $query = $this->db->get_where('auction_winner', array('user_id' => $this->session->userdata(SESSION . 'user_id'), 'payment_status' => 'Incomplete'));
        return $query->num_rows();
    }

    public function check_valide_testimonial_user($product_id) {
        $query = $this->db->get_where('auction_winner', array('auc_id' => $product_id, 'user_id' => $this->session->userdata(SESSION . 'user_id'), 'payment_status' => 'Completed', 'shipping_status' => 2));
        return $query->num_rows();
    }

    public function check_testimonial_added($product_id) {
        $query = $this->db->get_where('testimonial', array('auc_id' => $product_id, 'user_id' => $this->session->userdata(SESSION . 'user_id')));
        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return false;
    }

    //upload testimonial

    public function resize_image($file_name, $thumb_name, $width, $height) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = './' . TESTIMONIAL_PATH . $file_name;
        //$config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['master_dim'] = 'width';
        $config['new_image'] = './' . TESTIMONIAL_PATH . $thumb_name;

        $this->image_lib->initialize($config);

        $this->image_lib->resize();
        //$this->image_lib->clear(); 
    }

    public function file_settings_do_upload($file) {
        $config['upload_path'] = './' . TESTIMONIAL_PATH; //define in constants
        $config['allowed_types'] = 'gif|jpg|png';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = '5000';
        $config['max_width'] = '1600';
        $config['max_height'] = '1024';
        $this->upload->initialize($config);
        //print_r($_FILES);

        $this->upload->do_upload($file);
        if ($this->upload->display_errors()) {
            $this->error_img = $this->upload->display_errors();
            return false;
        } else {
            $data = $this->upload->data();
            return $data;
        }
    }

    public function get_auction_name_byid($product_id) {
        //get language id from configure file
        $lang_id = $this->config->item('current_language_id');

        $this->db->select("ad.name");
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->join('auction_winner w', 'w.auc_id=a.product_id', 'left');
        $this->db->where('w.user_id', $this->session->userdata(SESSION . 'user_id'));
        $this->db->where('w.auc_id', $product_id);
        $this->db->where('w.payment_status', 'Completed');
        $this->db->where('a.status', 'Closed');
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('ad.lang_id', $lang_id);
        $this->db->where('w.won_amt IS NOT NULL');

        $this->db->order_by("a.end_date", "desc");
        $this->db->group_by("a.product_id");
        $query = $this->db->get();
        //echo $this->db->last_query();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function upload_testimonial_image($product_id) {
        $image_error = FALSE;
        $this->session->unset_userdata('error_img');

        // Upload image
        if ($_FILES && !empty($_FILES['img']['name'])) {
            //make file settins and do upload it
            $image_name = $this->file_settings_do_upload('img');

            if ($image_name['file_name']) {
                $this->image_name_path = $image_name['file_name'];
                //resize image
                $this->resize_image($this->image_name_path, 'thumb_' . $image_name['raw_name'] . $image_name['file_ext'], 160, 100);
            } else {
                $image_error = TRUE;
                $this->session->set_userdata('error_img', $this->error_img);
            }
        }

        if ($image_error == FALSE) {
            //get language id from configure file
            $lang_id = $this->config->item('current_language_id');
            //get product info
            $product_info = $this->get_auction_name_byid($product_id);
            //print_r($product_info);exit;
            $data = array(
                'lang_id' => $lang_id,
                'auc_id' => $product_id,
                'user_id' => $this->session->userdata(SESSION . 'user_id'),
                'status' => 'pending',
                'winner_name' => $this->session->userdata(SESSION . 'username'),
                'product_name' => $product_info->name,
                'description' => $this->input->post('description', TRUE),
                'image' => $this->image_name_path,
                'last_update' => $this->general->get_local_time('time'),
                'post_date' => $this->general->get_local_time('time')
            );

            $this->db->insert('testimonial', $data);
        }

        return $image_error;
    }

    public function check_user_live_auction_bids() {
        //check with live bids
        $this->db->from('auction a');
        $this->db->join('user_bids ub', 'ub.auc_id=a.product_id', 'left');
        $this->db->where('ub.user_id', $this->session->userdata(SESSION . 'user_id'));
        $this->db->where('a.status', 'Live');
        $this->db->where('a.is_display', 'Yes');
        $this->db->group_by("a.product_id");
        $query = $this->db->get();
        //echo $this->db->last_query();

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }
	
	public function total_bid_statement($user_id, $date_from, $date_to) {

        //check with live bids
        $this->db->from('user_bids ub');        
        $this->db->where('ub.user_id', $this->session->userdata(SESSION . 'user_id'));
		$this->db->where("(ub.bid_date>='".$date_from."' AND ub.bid_date<='".$date_to."')");		
        $query = $this->db->get();
        //echo $this->db->last_query();exit;

        return $query->num_rows();
    }
	
	public function get_bid_statement($user_id, $date_from, $date_to, $limit = 20, $offset = 0) {

        //check with live bids
        
		$this->db->select("ub.*, a.product_id, ad.name");
        $this->db->from('user_bids ub');
		$this->db->join('auction a', 'a.product_id = ub.auc_id', 'left');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');        
        $this->db->where('ub.user_id', $this->session->userdata(SESSION . 'user_id'));
		$this->db->where("(ub.bid_date>='".$date_from."' AND ub.bid_date<='".$date_to."')");
		$this->db->group_by("ub.id");
		$this->db->order_by("ub.id","desc");
		$this->db->limit($limit, $offset);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

    public function update_user_balance_with_purchase_bonus_package($package_id, $bids, $bonus) {
        $this->db->trans_start(); //transaction start
        //update user balance
        $this->update_user_balance_bonus($bids, $bonus, $this->session->userdata(SESSION . 'user_id'));

        //update transaction
        $this->insert_record_transaction($package_id, $bids, $bonus, $this->session->userdata(SESSION . 'user_id'));

        $this->db->trans_complete(); //transaction end
    }

    public function update_user_balance_bonus($purchase_credit, $bonus, $user_id) {
        //get user current balance
        $this->db->select('balance,bonus_points');
        $query = $this->db->get_where('members', array('id' => $user_id));
        $user_balance = $query->row();

        $user_total_balance = $user_balance->balance + $purchase_credit;
        $user_total_bonus = $user_balance->bonus_points - $bonus;

        //update user balance
        $data = array('balance' => $user_total_balance, 'bonus_points' => $user_total_bonus);
        $this->db->where('id', $user_id);
        $this->db->update('members', $data);
    }

    public function insert_record_transaction($package_id, $bids, $bonus, $user_id) {
        $item_name = 'Buy Bid Credit: ' . $bids . ' With bonus points: ' . $bonus;
        $txn_id = $this->general->random_number() . '-' . $this->general->random_number();

        $data = array('transaction_status' => 'Completed', 'user_id' => $user_id, 'payment_date' => $this->general->get_local_time('time'),
            'credit_get' => $bids, 'bonus_points' => $bonus, 'transaction_name' => $item_name, 'payment_method' => 'bonuspackage', 'txn_id' => $txn_id,
            'bonuspackage_id' => $package_id, 'transaction_type' => 'redeem_bonus', 'transaction_date' => $this->general->get_local_time('time'));

        $this->db->insert('transaction', $data);
    }

    public function update_user_terms_condition() {
        $this->db->where('id', $this->session->userdata(SESSION . 'user_id'));
        $this->db->update('members', array('terms_condition' => '1'));
    }

    public function total_buy_auctions() {
        //get language id from configure file
        $lang_id = $this->config->item('current_language_id');

        $this->db->select("a.id, a.product_id, a.image1, ad.name, t.amount, t.transaction_status,t.transaction_date");
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->join('transaction t', 't.auc_id=a.product_id', 'left');
        $this->db->where('t.user_id', $this->session->userdata(SESSION . 'user_id'));
        //$this->db->where("(a.status = 'Closed' OR a.status = 'Dispatched')");
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('ad.lang_id', $lang_id);
        $this->db->where('t.transaction_status', 'Completed');
        $this->db->where('t.transaction_type', 'buy_auction');
        $this->db->where('t.amount IS NOT NULL');

        $this->db->order_by("t.transaction_date", "desc");

        //$this->db->group_by("a.product_id");
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_my_buy_auctions($limit = 10, $offset = 0) {
        //get language id from configure file
        $lang_id = $this->config->item('current_language_id');

        $this->db->select("a.id, a.product_id, a.image1, ad.name, t.amount, t.transaction_status,t.transaction_date");
        $this->db->from('auction a');
        $this->db->join('auction_details ad', 'ad.auc_id=a.id', 'left');
        $this->db->join('transaction t', 't.auc_id=a.product_id', 'left');
        $this->db->where('t.user_id', $this->session->userdata(SESSION . 'user_id'));
        //$this->db->where("(a.status = 'Closed' OR a.status = 'Dispatched')");
        $this->db->where('a.is_display', 'Yes');
        $this->db->where('ad.lang_id', $lang_id);
        $this->db->where('t.transaction_status', 'Completed');
        $this->db->where('t.transaction_type', 'buy_auction');
        $this->db->where('t.amount IS NOT NULL');

        $this->db->order_by("t.transaction_date", "desc");
        $this->db->limit($limit, $offset);
        //$this->db->group_by("a.product_id");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function check_password() {
        if ($this->session->userdata(SESSION . 'user_id')) {
            $id = $this->session->userdata(SESSION . 'user_id');
        } else {
            $id = $this->user_id;
        }

        $old_password = $this->input->post('old_password', true);
        $this->db->select('salt,password');
        $this->db->where('id', $id);
        $query1 = $this->db->get('members');
        if ($query1->num_rows() == 1) {
            $used_salt = $query1->row()->salt;
        }
        $used_password = $this->general->hash_password($old_password, $used_salt);
        if ($query1->row()->password == $used_password)
            return true;
    }

    public function send_mail_refer_friend() {


        $referer_name = $this->session->userdata(SESSION . 'first_name') . ' ' . $this->session->userdata(SESSION . 'last_name');
        if ($this->session->userdata(SESSION . 'user_id')) {
            $user_id = $this->session->userdata(SESSION . 'user_id');
        } else {
            $user_id = $this->user_id;
        }


        $this->load->library('email');
        $this->load->model('email_model');

        //get subjet & body
        $template = $this->email_model->get_email_template("refer_friend", $this->config->item('current_language_id'));
        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("refer_friend", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $emailbody = $template['email_body'];



        $email = $this->input->post('email');
        $name = $this->input->post('name');
        if ($email) {
            for ($i = 1; $i <= count(array_filter($email)); $i++) {
                $email_val = $email[$i];
                $name_val = $name[$i];
                if (isset($subject) && isset($emailbody)) {
                    //parse email
                    $parseElement = array(
                        "FULLNAME" => $name_val,
                        "REFERER" => $referer_name,
                        "AMOUNT" => SIGNUP_BONUS,
                        "CONFIRM" => $this->general->lang_uri('/users/register?ref=' . $this->session->userdata(SESSION . 'user_id')),
                        "SITENAME" => SITE_NAME
                    );
                    $subject = $this->email_model->parse_email($parseElement, $subject);
                    $emailbody = $this->email_model->parse_email($parseElement, $emailbody);
                    // echo $emailbody;
                    // exit;
                    //set the email things
//                    $this->email->from(SYSTEM_EMAIL);
//                    $this->email->to($email_val);
//                    $this->email->subject($subject);
//                    $this->email->message($emailbody);
//                    $this->email->send();
                    
                    $this->netcoreemail_class->send_email(SYSTEM_EMAIL,$email_val,$subject,$emailbody);
                }
            }
        }
    }

    public function update_user_profile_image($img, $user_id) {
        //get prvious img and delete it.
        $user_data = $this->get_user_details();
        @unlink('./' . USER_PROFILE_PATH . $user_data->image);

        $this->db->where('id', $user_id);
        $this->db->update('members', array('image' => $img));
    }

    public function remove_profile_img() {
        $user_data = $this->get_user_details();
        @unlink('./' . USER_PROFILE_PATH . $user_data->image);
        
        $this->db->where('id', $this->session->userdata(SESSION . 'user_id'));
        $this->db->update('members', array('image' => ''));
    }

    public function update_user_gender($user_id) {


        $this->db->where('id', $user_id);
        $this->db->update('members', array('gender' => $this->input->post('gender', true)));
        // echo $this->db->last_query();
    }
	
	function send_opt_code($opt_code,$user_mobile){
		
		$message = urlencode("Welcome to Eodbox.com , OTP for linking your Mobile No. is ".$opt_code." . Eodbox - The most Transparent & Trustworthy Bidding Platform of India");
		$url = 'http://SMS.VRINFOSOFT.CO.IN/unified.php?key=i968134kl41u41UI94KL6813&ph='.$user_mobile.'&sndr=EODBOX&text='.$message;
		
		$ch = curl_init(); 
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$output=curl_exec($ch);
		if(curl_errno($ch))
		{
			//echo 'error:' . curl_error($c);
		}
		curl_close($ch);		
		//print_r($output);
	}

}
