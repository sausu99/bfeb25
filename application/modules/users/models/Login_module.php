<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_module extends CI_Model {

    public $validate_settings = array(
        array('field' => 'user_name', 'label' => 'lang:register_uname', 'rules' => 'trim|required'),
        array('field' => 'password', 'label' => 'lang:register_pass', 'rules' => 'trim|required'),
    );
    public $validate_rules_reset_password = array(
        array('field' => 'password', 'label' => 'lang:change_pass_new', 'rules' => 'required|min_length[6]|max_length[20]'),
        array('field' => 'repassword', 'label' => 'lang:new_password_confirm', 'rules' => 'required|min_length[6]|max_length[20]|matches[password]'),
    );
    public $twitter_signup_validation = array(
        array('field' => 't_email', 'label' => 'lang:email', 'rules' => 'trim|required'),
        array('field' => 't_dob_day', 'label' => 'lang:day', 'rules' => 'trim|required'),
        array('field' => 't_dob_month', 'label' => 'lang:month', 'rules' => 'trim|required'),
        array('field' => 't_dob_year', 'label' => 'lang:year', 'rules' => 'trim|required'),
    );

    public function __construct() {
        parent::__construct();
    }

    public function check_login_process() {
        //get member info based on login value
        $options = array('user_name' => $this->input->post('user_name', TRUE));
        $query = $this->db->get_where('members', $options);

        //check valide login
        if ($query->num_rows() > 0) {
            $record = $query->row_array();
            //checl active user
            if ($record['status'] === 'active') {
                //re verify login info
                if ($record['user_name'] === $this->input->post('user_name') && $record['password'] === $this->general->hash_password($this->input->post('password', TRUE), $record['salt'])) {
                    $user_ip = $this->general->get_real_ipaddr();
                    //check blocked IP
                    if ($this->general->check_block_ip($user_ip) === 0) {
                        //Get Language info for this users and check it is availabe or not
                        $lang_information = $this->general->get_country_info_byid($record['country']);
                        //print_r($lang_information);exit;
                        // if(!$lang_information)
                        // {
                        // 	return 'sub_domain';exit;
                        // }

                        $this->session->set_userdata(array(SESSION . 'country_flag' => $lang_information['country_flag']));
                        $this->session->set_userdata(array(SESSION . 'short_code' => $lang_information['short_code']));

                        $current_date = $this->general->get_local_time('time');
                        $token_c=@$_SESSION['token'];
                        $update_data = array('last_login_date' => $current_date, 'last_ip_address' => $user_ip, 'mem_login_state' => '1', 'mem_last_activated' => $current_date,'token'=>$token_c);
                        //print_r($update_data);exit;
                        //echo $record['id'];
                        $this->db->where('id', $record['id']);
                        $this->db->update('members', $update_data);
						
						//Insert User IP Address
						$insert_data = array('user_id'=>$record['id'], 'date' => $current_date, 'ip_address' => $user_ip);                       
                        $this->db->insert('members_ip', $insert_data);
						
						
                        //echo $this->db->last_query();
                        //exit;
                        $this->session->set_userdata(array(SESSION . 'user_id' => $record['id']));
                        $this->session->set_userdata(array(SESSION . 'first_name' => $record['first_name']));
                        $this->session->set_userdata(array(SESSION . 'email' => $record['email']));
						if($record['image'])
                        	$this->session->set_userdata(array(SESSION . 'profile_pic' => 'thumb_' . $record['image']));
						else
							$this->session->set_userdata(array(SESSION . 'profile_pic' => ''));
							
						$this->session->set_userdata(array(SESSION . 'gender' => $record['gender']));	
                        $this->session->set_userdata(array(SESSION . 'last_name' => $record['last_name']));
                        $this->session->set_userdata(array(SESSION . 'username' => $record['user_name']));
                        $this->session->set_userdata(array(SESSION . 'balance' => $record['balance']));
                        $this->session->set_userdata(array(SESSION . 'country_id' => $record['country']));
                        $this->session->set_userdata(array(SESSION . 'terms' => $record['terms_condition']));
                        $this->session->set_userdata(array('last_login' => $this->general->date_time_formate($record['last_login_date'])));
                        $this->session->set_userdata(array(SESSION . 'login_state' => $record['mem_login_state']));
						$this->session->set_userdata(array(SESSION.'social_id' => $record['social_id']));
                        //$this->session->set_userdata(array(SESSION.'session_id' => $unique_session_id));


                        if ($this->input->post('remember') == 'yes') {
                            $cookie1 = array('name' => SESSION . "username", 'value' => $record['user_name'], 'expire' => 3600 * 24 * 30);
                            $this->input->set_cookie($cookie1);

                            $cookie2 = array('name' => SESSION . "password", 'value' => $this->input->post('password'), 'expire' => 3600 * 24 * 30);
                            $this->input->set_cookie($cookie2);
                        } else {
                            $cookie1 = array('name' => SESSION . "username", 'value' => '', 'expire' => 0);
                            $this->input->set_cookie($cookie1);

                            $cookie2 = array('name' => SESSION . "password", 'value' => '', 'expire' => 0);
                            $this->input->set_cookie($cookie2);
                        }

                        //check bonus expire
                        //if($record['bonus_points'] > 0)
                        //{$this->bonus_expire($record['id'], $record['bonus_points']);}

                        return 'success';
                    } else {
                        return 'blocked_ip';
                    }
                } else {
                    return 'invalid';
                }
            } else if ($record['status'] === 'inactive') {
                return 'unverified';
            } else if ($record['status'] === 'close') {
                return 'close';
            } else if ($record['status'] === 'suspended') {
                return 'suspended';
            }
        } else {
            return 'unregistered';
        }
    }


    public function check_email() {
        $options = array('email' => $this->input->post('email', TRUE));
        $query = $this->db->get_where('members', $options);
        return $query->num_rows();
    }

    public function get_fb_user_num($user_id) {
        $option = array('social_id' => $user_id, 'reg_type' => 'facebook');
        $query = $this->db->get_where('members', $option);

        //echo $this->db->last_query(); exit;
        return $query->num_rows();
    }

    public function get_twitter_user_num($user_id) {
        $option = array('social_id' => $user_id, 'reg_type' => 'twitter');
        $query = $this->db->get_where('members', $option);
        //echo $this->db->last_query(); exit;
        return $query->num_rows();
    }

    public function get_google_user_num($google_id) {
        $option = array('social_id' => $google_id, 'reg_type' => 'google');
        $query = $this->db->get_where('members', $option);
        // echo $this->db->last_query(); exit;
        return $query->num_rows();
    }

    public function forget_password_reminder_email() {
        $options = array('email' => $this->input->post('email', TRUE));
        $query = $this->db->get_where('members', $options);
        $row = $query->row();
        $user_id = $row->id;
        $email = $row->email;

        $user_name = $row->user_name;
        $first_name = $row->first_name;
        $countryid = $row->country;
        $lang_id = $this->general->get_lang_id_by_country($countryid);

        // echo 'lang_id:'.$lang_id;
        // exit;
        //load email library
        $this->load->library('email');

        $this->load->model('email_model');

        //get subjet & body
        $template = $this->email_model->get_email_template("forgot_password_notification", $lang_id);

        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("forgot_password_notification", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $emailbody = $template['email_body'];

        // echo $emailbody;
        // exit;
        $activation_key = $this->generate_password_activation_code($user_id, $email);
        if ($activation_key) {
            $encoded_email = base64_encode($email);
            $reset_link = "<a href='" . $this->general->lang_uri('/users/login/reset_password') . '/?key=' . urlencode($activation_key) . '&auth=' . urlencode($encoded_email) . "'>" . $this->general->lang_uri('/users/login/reset_password') . '/?key=' . urlencode($activation_key) . '&auth=' . urlencode($encoded_email) . "</a>";

            //check blank valude before send message
            if (isset($subject) && isset($emailbody)) {
                //parse email
                $parseElement = array(
                    "SITENAME" => SITE_NAME,
                    "EMAIL" => strtolower($this->input->post('email')),
                    "FIRSTNAME" => $first_name,
                    "RESET_LINK" => $reset_link,
                    "WEBSITE_NAME" => WEBSITE_NAME
                );

                $subject = $this->email_model->parse_email($parseElement, $subject);
                $emailbody = $this->email_model->parse_email($parseElement, $emailbody);
                // echo $emailbody;
                // exit;
                //set the email things
                // $this->email->from(SYSTEM_EMAIL);
                // $this->email->to($this->input->post('email', TRUE));
                // $this->email->subject($subject);
                // $this->email->message($emailbody);
                // $this->email->send();
                $this->netcoreemail_class->send_email(SYSTEM_EMAIL,$this->input->post('email', TRUE),$subject,$emailbody);
            }
        }
    }

    public function send_forgot_sms_notification() {
        $options = array('email' => $this->input->post('email', TRUE));
        $query = $this->db->get_where('members', $options);
        $row = $query->row();
        $user_id = $row->id;
        $email = $row->email;

        $user_name = $row->user_name;
        $first_name = $row->first_name;
        $countryid = $row->country;
        $lang_id = $this->general->get_lang_id_by_country($countryid);

        // echo 'lang_id:'.$lang_id;
        // exit;
        //load email library
        $this->load->library('email');

        $this->load->model('email_model');

        //get subjet & body
        $template = $this->email_model->get_email_template("forgot_password_notification", $lang_id);

        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("forgot_password_notification", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $smsbody = $template['sms_body'];

        // echo $emailbody;
        // exit;
        $activation_key = $this->generate_password_activation_code($user_id, $email);
        if ($activation_key) {
            $encoded_email = base64_encode($email);
            $reset_link = "<a href='" . $this->general->lang_uri('/users/login/reset_password') . '/?key=' . urlencode($activation_key) . '&auth=' . urlencode($encoded_email) . "'>" . $this->general->lang_uri('/users/login/reset_password') . '/?key=' . urlencode($activation_key) . '&auth=' . urlencode($encoded_email) . "</a>";

            //check blank valude before send message
            if (isset($subject) && isset($smsbody)) {
                //parse email
                $parseElement = array(
                    "SITENAME" => SITE_NAME,
                    "EMAIL" => strtolower($this->input->post('email')),
                    "FIRSTNAME" => $first_name,
                    "RESET_LINK" => $reset_link,
                    "WEBSITE_NAME" => WEBSITE_NAME
                );

                $subject = $this->email_model->parse_email($parseElement, $subject);
                $smsbody = $this->email_model->parse_email($parseElement, $smsbody);


                $this->checkmobi->sendSMS(CHECKMOBI_SMS_API_KEY, $row->mobile, $smsbody);
            }
        }
    }

    public function send_forgot_push_notification() {
        $options = array('email' => $this->input->post('email', TRUE));
        $query = $this->db->get_where('members', $options);
        $row = $query->row();
        $user_id = $row->id;
        $email = $row->email;

        $user_name = $row->user_name;
        $first_name = $row->first_name;
        $countryid = $row->country;
        $lang_id = $this->general->get_lang_id_by_country($countryid);

        // echo 'lang_id:'.$lang_id;
        // exit;
        //load email library
        $this->load->library('email');

        $this->load->model('email_model');

        //get subjet & body
        $template = $this->email_model->get_email_template("forgot_password_notification", $lang_id);

        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("forgot_password_notification", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $push_body = $template['push_message_body'];

        // echo $emailbody;
        // exit;
        $activation_key = $this->generate_password_activation_code($user_id, $email);
        if ($activation_key) {
            $encoded_email = base64_encode($email);
            $reset_link = "<a href='" . $this->general->lang_uri('/users/login/reset_password') . '/?key=' . urlencode($activation_key) . '&auth=' . urlencode($encoded_email) . "'>" . $this->general->lang_uri('/users/login/reset_password') . '/?key=' . urlencode($activation_key) . '&auth=' . urlencode($encoded_email) . "</a>";

            //check blank valude before send message
            if (isset($subject) && isset($push_body)) {
                //parse email
                $parseElement = array(
                    "SITENAME" => SITE_NAME,
                    "EMAIL" => strtolower($this->input->post('email')),
                    "FIRSTNAME" => $first_name,
                    "RESET_LINK" => $reset_link,
                    "WEBSITE_NAME" => WEBSITE_NAME
                );

                $subject = $this->email_model->parse_email($parseElement, $subject);
                $push_message_body = $this->email_model->parse_email($parseElement, $push_body);

                $user_push = $this->general->get_device_id($row->push_id);
                $this->fcm->send($user_push, array('message' => $push_message_body, 'subject' => $subject));
            }
        }
    }

    public function change_users_password($email) {
        $this->load->library('email');
        $this->load->model('email_model');

        $password_tmp = $this->input->post('password');

        // Create a random salt
        $salt = $this->general->salt();
        $password = $this->general->hash_password($password_tmp, $salt);

        $data = array(
            'password' => $password,
            'salt' => $salt,
            'forgot_password_code' => '',
            'forgot_password_code_expire' => '0000-00-00 00:00:00',
        );

        $this->db->update('members', $data, array('email' => $email));

        $options = array('email' => strtolower($email));
        $query = $this->db->get_where('members', $options);
        $row = $query->row();
        $user_id = $row->id;
        $user_name = $row->user_name;
        $first_name = $row->first_name;

        $countryid = $row->country;
        $lang_id = $this->general->get_lang_id_by_country($countryid);


        $template = $this->email_model->get_email_template("change_password_user", $lang_id);
        if (!isset($template['subject'])) {
            $template = $this->email_model->get_email_template("change_password_user", DEFAULT_LANG_ID);
        }

        $subject = $template['subject'];
        $emailbody = $template['email_body'];

        if (isset($subject) && isset($emailbody)) {
            //parse email
            $parseElement = array(
                "USERNAME" => $user_name,
                "SITENAME" => SITE_NAME,
                "EMAIL" => strtolower($email),
                "FIRSTNAME" => $first_name,
                "WEBSITE_NAME" => WEBSITE_NAME
            );

            $subject = $this->email_model->parse_email($parseElement, $subject);
            $emailbody = $this->email_model->parse_email($parseElement, $emailbody);
            // echo $emailbody;
            // exit;
            //set the email things
            $this->email->from(CONTACT_EMAIL);
            $this->email->to($this->input->post('email', TRUE));
            $this->email->subject($subject);
            $this->email->message($emailbody);
            $this->email->send();
        }

        return $this->db->affected_rows();
    }

    public function is_user_ready_reset_password($email, $code) {

        $this->db->select('forgot_password_code_expire');
        $query = $this->db->get_where('members', array('forgot_password_code' => $code, 'email' => $email));
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_fb_user($email) {
        $query = $this->db->select('is_fb_user')
                ->where('email', $email)
                ->where('status', 'active')
                ->get('members');

        if ($query->num_rows() > 0) {
            $result = $query->row();
            if ($result->is_fb_user == 'Yes') {
                return 'success';
            } else {
                return 'failed';
            }
        } else {
            return 'failed';
        }
    }

    function bonus_expire($user_id, $user_balance_bonus) {
        //$user_id = 6;
        //$user_balance_bonus = 65;

        $query_bonus_given = "SELECT GROUP_CONCAT(DATE(transaction_date)) AS invoice_dates, GROUP_CONCAT(invoice_id) AS invoice_ids, SUM(bonus_points) AS total_bonus_given FROM emts_transaction WHERE  user_id='$user_id' AND transaction_type='purchase_credit' AND bonus_points!=0 AND DATE(transaction_date) <= DATE(DATE_SUB('" . $this->general->get_local_time('none') . "', INTERVAL 1 YEAR)) AND bonus_status = 0 AND transaction_status = 'Completed' GROUP BY user_id";
        $query = $this->db->query($query_bonus_given);

        //print_r($query->row());
        //echo "<br>";

        if ($query->num_rows() > 0) {
            $result_bonus_given = $query->row();
            $invoice_ids = $result_bonus_given->invoice_ids;
            $total_bonus_given = $result_bonus_given->total_bonus_given;
            $invoice_dates = explode(",", '2012-01-20');
            $bonus_first_date = $invoice_dates[0];

            $total_bonus_used = 0;
            $expire_bonus = 0;

            //Bonus used

            $query_bonus_used = "SELECT GROUP_CONCAT(invoice_id) AS invoice_ids, SUM(bonus_points) AS total_bonus_used FROM emts_transaction WHERE  user_id='$user_id' AND transaction_type='redeem_bonus' AND bonus_points!=0 AND (DATE(transaction_date) >= '$bonus_first_date' AND DATE(transaction_date) < '" . $this->general->get_local_time('none') . "') AND bonus_status = 0 AND transaction_status = 'Completed' GROUP BY user_id";
            $query2 = $this->db->query($query_bonus_used);
            //print_r($query2->row());
            if ($query2->num_rows()) {
                $result_bonus_used = $query2->row();
                $invoice_ids_bonus_used = $result_bonus_used->invoice_ids;
                $total_bonus_used = $result_bonus_used->total_bonus_used;
            }

            $expire_bonus = $total_bonus_given - $total_bonus_used;

            //echo $total_bonus_given .'>'. $total_bonus_used .'&&'. $expire_bonus .'>='. $user_balance_bonus;

            if ($total_bonus_given > $total_bonus_used && $expire_bonus <= $user_balance_bonus) {

                //update user bonus balance
                $this->db->query("UPDATE emts_members SET bonus_points = bonus_points - '$expire_bonus' WHERE id = " . $user_id);

                //update transaction 				
                $item_name = 'Bonus has been expired';

                $data = array('transaction_status' => 'Completed', 'user_id' => $user_id, 'payment_date' => $this->general->get_local_time('time'),
                    'bonus_points' => $expire_bonus, 'transaction_name' => $item_name, 'transaction_type' => 'bonus_expired',
                    'transaction_date' => $this->general->get_local_time('time'));

                $this->db->insert('transaction', $data);
            }

            //update for bonus give
            if (isset($invoice_ids))
                $this->db->query('UPDATE emts_transaction SET bonus_status = 1 WHERE invoice_id IN (' . $invoice_ids . ')');

            //update for bonus used
            if (isset($invoice_ids_bonus_used))
                $this->db->query('UPDATE emts_transaction SET bonus_status = 1 WHERE invoice_id IN (' . $invoice_ids_bonus_used . ')');
        }
    }

    public function check_login_process_app() {
        //get member info based on login value
        $options = array('user_name' => $this->input->post('username', TRUE));
        $query = $this->db->get_where('members', $options);
        //echo $this->db->last_query();exit;
        //check valide login
        if ($query->num_rows() > 0) {
            $record = $query->row_array();
            //checl active user
            if ($record['status'] === 'active') {
                //re verify login info
                if ($record['user_name'] === $this->input->post('username') && $record['password'] === $this->general->hash_password($this->input->post('password', TRUE), $record['salt'])) {
                    $user_ip = $this->general->get_real_ipaddr();
                    //check blocked IP
                    if ($this->general->check_block_ip($user_ip) === 0) {
                        //Get Language info for this users and check it is availabe or not
                        // $lang_information = $this->general->get_country_info_byid($record['country']);
                        // if(!$lang_information)
                        // {
                        // 	return 'sub_domain';exit;
                        // }
                        // $this->session->set_userdata(array(SESSION.'lang_flag' => $lang_information['lang_flag']));
                        // $this->session->set_userdata(array(SESSION.'short_code' => $lang_information['short_code']));



                        $current_date = $this->general->get_local_time('time');

                        $update_data = array('last_login_date' => $current_date, 'last_ip_address' => $user_ip);
                        $this->db->where('id', $record['id']);
                        $this->db->update('members', $update_data);
						
						//Insert User IP Address
						$insert_data = array('user_id'=>$record['id'], 'date' => $current_date, 'ip_address' => $user_ip);                       
                        $this->db->insert('members_ip', $insert_data);
						
                        $this->session->set_userdata(array(SESSION . 'user_id' => $record['id']));
                        $this->session->set_userdata(array(SESSION . 'first_name' => $record['first_name']));
                        $this->session->set_userdata(array(SESSION . 'email' => $record['email']));
                        $this->session->set_userdata(array(SESSION . 'last_name' => $record['last_name']));
                        $this->session->set_userdata(array(SESSION . 'username' => $record['user_name']));
                        $this->session->set_userdata(array(SESSION . 'balance' => $record['balance']));
                        $this->session->set_userdata(array(SESSION . 'lang_id' => $record['lang_id']));
                        $this->session->set_userdata(array(SESSION . 'terms' => $record['terms_condition']));
                        $this->session->set_userdata(array('last_login' => $this->general->date_time_formate($record['last_login_date'])));
                        // $this->session->set_userdata(array(SESSION.'session_id' => $unique_session_id));


                        if ($this->input->post('remember') == 'yes') {
                            $cookie1 = array('name' => SESSION . "username", 'value' => $record['user_name'], 'expire' => 3600 * 24 * 30);
                            $this->input->set_cookie($cookie1);

                            $cookie2 = array('name' => SESSION . "password", 'value' => $this->input->post('password'), 'expire' => 3600 * 24 * 30);
                            $this->input->set_cookie($cookie2);
                        } else {
                            $cookie1 = array('name' => SESSION . "username", 'value' => '', 'expire' => 0);
                            $this->input->set_cookie($cookie1);

                            $cookie2 = array('name' => SESSION . "password", 'value' => '', 'expire' => 0);
                            $this->input->set_cookie($cookie2);
                        }

                        //check bonus expire
                        //if($record['bonus_points'] > 0)
                        //{$this->bonus_expire($record['id'], $record['bonus_points']);}

                        return 'success';
                    } else {
                        return 'blocked_ip';
                    }
                } else {
                    return 'invalid';
                }
            } else if ($record['status'] === 'inactive') {
                return 'inactive';
            } else if ($record['status'] === 'close') {
                return 'close';
            } else if ($record['status'] === 'suspended') {
                return 'suspended';
            }
        } else {
            return 'unregistered';
        }
    }

    public function check_FBlogin_process_app($email) {
        //get member info based on login value
        $options = array('email' => $email);
        $query = $this->db->get_where('members', $options);
        //echo $this->db->last_query();exit;
        //check valide login
        if ($query->num_rows() > 0) {
            $record = $query->row_array();

            //checl active user
            if ($record['status'] === 'active' && $record['is_fb_user'] == 'Yes') {

                $user_ip = $this->general->get_real_ipaddr();
                //check blocked IP
                if ($this->general->check_block_ip($user_ip) === 0) {

                    //Get Language info for this users and check it is availabe or not
                    $lang_information = $this->general->get_lang_info($record['lang_id']);

                    if (!$lang_information) {
                        $this->session->set_userdata(array(SESSION . 'sub_domain' => "sub domain issue"));
                        return 'sub_domain';
                        exit;
                    }

                    $current_date = $this->general->get_local_time('time');

                    $update_data = array('last_login_date' => $current_date, 'last_ip_address' => $user_ip);
                    $this->db->where('id', $record['id']);
                    $this->db->update('members', $update_data);
					
					//Insert User IP Address
						$insert_data = array('user_id'=>$record['id'], 'date' => $current_date, 'ip_address' => $user_ip);                       
                        $this->db->insert('members_ip', $insert_data);
						
                    $this->session->set_userdata(array(SESSION . 'user_id' => $record['id']));
                    $this->session->set_userdata(array(SESSION . 'first_name' => $record['first_name']));
                    $this->session->set_userdata(array(SESSION . 'email' => $record['email']));
                    $this->session->set_userdata(array(SESSION . 'last_name' => $record['last_name']));
                    $this->session->set_userdata(array(SESSION . 'username' => $record['user_name']));
                    $this->session->set_userdata(array(SESSION . 'balance' => $record['balance']));
                    $this->session->set_userdata(array(SESSION . 'lang_id' => $record['lang_id']));
                    $this->session->set_userdata(array(SESSION . 'terms' => $record['terms_condition']));
                    $this->session->set_userdata(array('last_login' => $this->general->date_time_formate($record['last_login_date'])));

                    //check bonus expire
                    //if($record['bonus_points'] > 0)
                    //{$this->bonus_expire($record['id'], $record['bonus_points']);}

                    return 'success';
                } else {
                    return 'blocked_ip';
                }
            } else if ($record['status'] === 'inactive') {
                return 'unverified';
            } else if ($record['status'] === 'close') {
                return 'close';
            } else if ($record['status'] === 'suspended') {
                return 'suspended';
            }
        }
    }

    public function send_forget_password_link() {

        $options = array('email' => strtolower($this->input->post('email', TRUE)));
        $query = $this->db->get_where('members', $options);
        $row = $query->row();
        $user_id = $row->id;
        $user_name = $row->user_name;
        $first_name = $row->first_name;
        $email = $this->input->post('email', true);

        $activation_key = $this->generate_password_activation_code($user_id, $email);
        if ($activation_key) {
            $encoded_email = base64_encode($email);
            $reset_link = "<a href='" . $this->general->lang_uri('/user/login/reset_password') . '/?key=' . urlencode($activation_key) . '&auth=' . urlencode($encoded_email) . "'>" . $this->general->lang_uri('/user/login/reset_password') . '/?key=' . urlencode($activation_key) . '&auth=' . urlencode($encoded_email) . "</a>";
            $parseElement = array("USERNAME" => $user_name,
                "SITENAME" => SITE_NAME,
                "EMAIL" => strtolower($this->input->post('email')),
                "FIRSTNAME" => $first_name,
                "RESET_LINK" => $reset_link,
                "WEBSITE_NAME" => WEBSITE_NAME
            );
            $from = SYSTEM_EMAIL;
            $to = $email;
            $this->notification->send_email_notification('forgot_password_notification', $user_id, SYSTEM_EMAIL, strtolower($this->input->post('email', TRUE)), '', '', $parseElement);
            $this->notification->send_sms_notification('forgot_password_notification', $user_id, $parseElement);

            return true;
        } else {
            return false;
        }
    }

    public function generate_password_activation_code($id, $email) {
        /* The activation code is only valid for next 24hrs
         * +24 hours   = for next 24 hrs
         * +6 hours    = for next 6 hrs
         */
        $data = array(
            'forgot_password_code' => random_string('unique'),
            'forgot_password_code_expire' => date('Y-m-d H:i:s', strtotime("+24 hours"))
        );

        $res = $this->db->update('members', $data, array('id' => $id, 'email' => $email));
        if ($res)
            return $data['forgot_password_code'];
        else
            false;
    }
	
	function get_device_details($device){
		$query = $this->db->get_where("members_device_token", array("device" => $device));
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return false;
		
	}
	
	function insert_device_tokent(){
		$current_date = $this->general->get_local_time('none');
		$device_id = $this->input->post('device_id', true);
		$token = $this->input->post('token', true);
		
		$data = array('device'=>$device_id, 'token'=>$token,'post_date'=>$current_date,'update_date'=>$current_date);		
		$this->db->insert('members_device_token', $data);
		return $this->db->insert_id();
	}
	
	function update_device_tokent(){
		$current_date = $this->general->get_local_time('none');
		$device_id = $this->input->post('device_id', true);
		$token = $this->input->post('token', true);
		
		$data = array('token'=>$token,'update_date'=>$current_date);		
		$this->db->where('device', $device_id);
		$this->db->update('members_device_token', $data);
		
	}
	
	function check_update_device_user_id($user_id){
		$device_id = md5($_SERVER['HTTP_USER_AGENT']);
		if($device_details = $this->get_device_details($device_id)){
			if($device_details->user_id == ""){
				$data = array('user_id'=>$user_id);		
				$this->db->where('device', $device_id);
				$this->db->update('members_device_token', $data);
			}
		}
	}

}
