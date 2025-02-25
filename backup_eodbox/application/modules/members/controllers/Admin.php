<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed'); //error_reporting(0);

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();

        // Check if User has logged in
        if (!$this->general->admin_logged_in()) {
            redirect(ADMIN_LOGIN_PATH, 'refresh');
            exit;
        }

        //load CI library
        $this->load->library('form_validation');
        $this->load->library('pagination');

        //load custom module
        $this->load->model('admin_member');
        // $this->load->model('country/admin_country');

        $this->load->library('Checkmobi');
        $this->load->library('Netcoreemail_class');
        $this->load->library('fcm');

        //Changing the Error Delimiters
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }

    public function index() {
        if ($this->uri->segment(4))
            $status = $this->uri->segment(4);
        else
            $status = 'active';
        //set pagination configuration			
        $config['base_url'] = site_url(ADMIN_DASHBOARD_PATH) . '/members/index/' . $status;
        $config['total_rows'] = $this->admin_member->get_total_members($status);
        $config['num_links'] = 5;
        $config['prev_link'] = 'Prev';
        $config['next_link'] = 'Next';
        $config['per_page'] = '30';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span>';
        $config['num_tag_close'] = '</span>';
        $config['uri_segment'] = '5';
        $this->pagination->initialize($config);

        $offset = $this->uri->segment(5, 0);
        $this->data['country_list'] = $this->general->get_active_countries();
        $this->data['result_data'] = $this->admin_member->get_members_details($this->uri->segment(4), $config['per_page'], $offset);
        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('Members View | ' . SITE_NAME)
                ->build('members_view', $this->data);
    }

    public function add_member() {
		//echo "test";exit;
		$this->data = [];
        //Set the validation rules
        $this->form_validation->set_rules($this->admin_member->validate_settings_add);
        // echo '<pre>';
        // print_r($this->input->post('email'));
        // exit;
        $dob_day = $this->input->post('day');
        $dob_month = $this->input->post('month');
        $dob_year = $this->input->post('year');

        if ($dob_day == "" || $dob_day == "" || $dob_year == "") {
            // $this->form_validation->set_rules('dob','DOB','required');
            // $this->form_validation->set_message('dob', 'Invalid date of birth');
            $this->form_validation->set_rules('dob', 'Invalid date of birth and DD-MM-YY', 'required|integer');
        }

        if ($this->form_validation->run() == TRUE) {
            //Insert Lang Settings
            $this->admin_member->insert_record();
            $this->session->set_flashdata('message', 'The Member records added successful.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/all', 'refresh');
            exit;
        }




        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('Member Add | ' . SITE_NAME)
                ->build('member_add', $this->data);
    }
	
	public function edit_member($status, $id) {
        //check id, if it is not set then redirect to view page
        if (!isset($id)) {
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        }

        $this->data['profile'] = $this->admin_member->get_member_byid($id);
        // exit;
        //check data, if it is not set then redirect to view page
        if ($this->data['profile'] == false) {
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        }
        $this->data['ship_addr'] = $this->admin_member->get_user_shipping_details($id);

        //Set the validation rules
        $this->form_validation->set_rules($this->admin_member->validate_settings_edit);
        // echo '<pre>';
        // print_r($this->input->post('email'));
        // exit;
        $dob_day = $this->input->post('day');
        $dob_month = $this->input->post('month');
        $dob_year = $this->input->post('year');

        if ($dob_day == "" || $dob_day == "" || $dob_year == "") {
            // $this->form_validation->set_rules('dob','DOB','required');
            // $this->form_validation->set_message('dob', 'Invalid date of birth');
            $this->form_validation->set_rules('dob', 'Invalid date of birth and DD-MM-YY', 'required|integer');
        }

        if ($this->form_validation->run() == TRUE) {
            //Insert Lang Settings
            $this->admin_member->update_record($id);
            $this->session->set_flashdata('message', 'The Member records are update successful.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        }




        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('Member Edit | ' . SITE_NAME)
                ->build('member_edit', $this->data);
    }

    public function delete_member($status, $id) {
        $query = $this->db->get_where('members', array('id' => $id));

        if ($query->num_rows() > 0) {
            $this->db->delete('members', array('id' => $id));
            $this->db->delete('auction_winner', array('user_id' => $id));
            $this->db->delete('auction_winner_address', array('user_id' => $id));
            $this->db->delete('members_address', array('user_id' => $id));
            $this->db->delete('member_mobile_verify', array('id' => $id));
            $this->db->delete('member_watch_lists', array('user_id' => $id));
            $this->db->delete('transaction', array('user_id' => $id));
            $this->db->delete('user_bids', array('user_id' => $id));


            $this->session->set_flashdata('message', 'The member record delete successful.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        } else {
            $this->session->set_flashdata('message', 'Sorry no record found.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        }
    }

    public function mark_safe($status, $id) {
        $query = $this->db->get_where('members', array('id' => $id));

        if ($query->num_rows() > 0) {
            $this->admin_member->mark_safe($id);
            $this->session->set_flashdata('message', 'The member is marked safe.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        } else {
            $this->session->set_flashdata('message', 'Sorry no record found.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        }
    }

    public function mark_obscene($status, $id) {
        $query = $this->db->get_where('members', array('id' => $id));

        if ($query->num_rows() > 0) {
            $this->admin_member->mark_obscene($id);
            $this->session->set_flashdata('message', 'The member is listed in obscene members.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        } else {
            $this->session->set_flashdata('message', 'Sorry no record found.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        }
    }

    public function delete_image_only($id) {

        $query = $this->db->get_where('members', array('id' => $id));

        if ($query->num_rows() > 0) {
            $this->admin_member->change_image($id);
            $this->session->set_flashdata('message', 'The Image deleted successfully.');
            @unlink(base_url() . USER_PROFILE_PATH . $query->result()->image);
            redirect(ADMIN_DASHBOARD_PATH . '/members/edit_member/all/' . $id, 'refresh');
            exit;
        } else {
            $this->session->set_flashdata('message', 'Sorry no record found.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        }
    }

    public function check_email() {
        // echo "test";
        // exit;
        $user_id = $this->input->post('user_id');
        $email = $this->input->post('email');
        $query = $this->db->get_where('members', array('id !=' => $user_id, 'email' => $email));
        // echo $this->db->last_query();
        // die();
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('check_email', "The email address is already in used.");
            return false;
        } else {
            return true;
        }
    }

    public function transaction($status, $user_id) {

        $this->data['profile'] = $this->admin_member->get_member_byid($user_id);
        $config['base_url'] = site_url(ADMIN_DASHBOARD_PATH) . '/members/transaction/'. $status.'/' . $user_id;
        $config['total_rows'] = $this->admin_member->count_member_transaction($user_id);
        $config['num_links'] = '10';
        $config['prev_link'] = 'Prev';
        $config['next_link'] = 'Next';
        $config['per_page'] = '30';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span>';
        $config['num_tag_close'] = '</span>';

        //set pagination configuration			
//        $config['base_url'] = site_url(ADMIN_DASHBOARD_PATH) . '/members/transaction/' . $status . '/' . $user_id;
//        $config['total_rows'] = $this->admin_member->count_member_transaction($user_id);
//        $config['num_links'] = '3';
////		$config['prev_link'] = 'Prev';
////		$config['next_link'] = 'Next';
//        $config['per_page'] = '2';
////		$config['next_tag_open'] = '<span>';
////		$config['next_tag_close'] = '</span>';
////		$config['cur_tag_open'] = '<span>';
////		$config['cur_tag_close'] = '</span>';
////		$config['num_tag_open'] = '<span>';
////		$config['num_tag_close'] = '</span>';	
//        $config['cur_tag_open'] = '&nbsp;<a class="current">';
//        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = false;
        $config['prev_link'] = false;
//
        $config['uri_segment'] = '6';
        $offset = $this->uri->segment(6, 0);
        $this->pagination->initialize($config);
//                echo $offset;exit;
//               echo $config['per_page'].','.$offset;

        $this->data['result_data'] = $this->admin_member->get_member_transaction($user_id, $config['per_page'], $offset);

        //$this->data = '';
        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('' . SITE_NAME . 'Members Transaction')
                ->build('member_transaction', $this->data);
    }
	
	public function ip_address($status, $user_id) {

        $this->data['profile'] = $this->admin_member->get_member_byid($user_id);
        $config['base_url'] = site_url(ADMIN_DASHBOARD_PATH) . '/members/ip_address/'. $status.'/' . $user_id;
        $config['total_rows'] = $this->admin_member->count_member_ip($user_id);
        $config['num_links'] = '10';
        $config['prev_link'] = 'Prev';
        $config['next_link'] = 'Next';
        $config['per_page'] = '30';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span>';
        $config['num_tag_close'] = '</span>';
        $config['next_link'] = false;
        $config['prev_link'] = false;
//
        $config['uri_segment'] = '6';
        $offset = $this->uri->segment(6, 0);
        $this->pagination->initialize($config);
//                echo $offset;exit;
//               echo $config['per_page'].','.$offset;

        $this->data['result_data'] = $this->admin_member->get_member_ip($user_id, $config['per_page'], $offset);

        //$this->data = '';
        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('' . SITE_NAME . 'Members Transaction')
                ->build('member_ip_address', $this->data);
    }

    public function view_bid_history($status = '', $user_id = '') {
        // echo $user_id;
        // exit;
        if (!$status || !$user_id) {
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/', 'refresh');
            exit;
        }

        $this->data['profile'] = $this->admin_member->get_member_byid($user_id);
        //print_r($this->data['profile']->currency_sign);exit;
        $this->data['user_currency_sign'] = $this->data['profile']->currency_sign;
        $this->data['result_data'] = $this->admin_member->get_member_bids_history($user_id);


        $this->data['user_id'] = $user_id;

        //$this->data = '';
        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('' . SITE_NAME . 'Members Bidding Information')
                ->build('member_bid_history', $this->data);
    }

    public function popup_detail_bid($id, $uid) {

        //$this->data['profile'] = $this->admin_member->get_member_byid($uid);

        $this->data['auction_info'] = $this->admin_member->get_auction_info($id);

        $this->data['bid_info'] = $this->admin_member->get_bid_detail($id, $uid);


        $this->template
                ->set_layout('')
                ->enable_parser(FALSE)
                ->title('Member Bid Detial')
                ->build('member_popup_detail_bid', $this->data);
    }

    public function view_current_bids($status = '', $user_id = '') {
        if (!$status || !$user_id) {
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/', 'refresh');
            exit;
        }

        $this->data['profile'] = $this->admin_member->get_member_byid($user_id);


        $this->data['result_data'] = $this->admin_member->get_member_bids($user_id);
        // echo "<pre>";
        // print_r($this->data['result_data']);
        // exit;
        $this->data['user_id'] = $user_id;

        //$this->data = '';
        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('' . SITE_NAME . 'Members Bidding Information')
                ->build('member_current_bids', $this->data);
    }

    public function view_closed_bids($status = '', $user_id = '') {
        if (!$status || !$user_id) {
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/', 'refresh');
            exit;
        }

        $this->data['profile'] = $this->admin_member->get_member_byid($user_id);


        $this->data['result_data'] = $this->admin_member->get_member_bids($user_id, 'Closed');


        $this->data['user_id'] = $user_id;

        //$this->data = '';
        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('' . SITE_NAME . 'Members Bidding Information')
                ->build('member_current_bids', $this->data);
    }

    public function view_auctions_won($status, $user_id) {
        $this->data['profile'] = $this->admin_member->get_member_byid($user_id);
        $this->data['user_currency_sign'] = $this->data['profile']->currency_sign;
        $this->data['result_data'] = $this->admin_member->get_member_bids_won($user_id);


        $this->data['user_id'] = $user_id;

        //$this->data = '';
        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('' . SITE_NAME . 'Members Bidding Information')
                ->build('view_auctions_won', $this->data);
    }

    public function add_balance($status, $user_id) {
        //check id, if it is not set then redirect to view page
        if (!isset($user_id)) {
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        }

        $this->data['profile'] = $this->admin_member->get_member_byid($user_id);

        $this->form_validation->set_rules('payment_method', 'Transaction Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('credit_get', 'Balance', 'trim|required|xss_clean');
        $this->form_validation->set_rules('transaction_name', 'Transaction Details', 'trim|required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $this->admin_member->member_add_money($user_id);
            $this->session->set_flashdata('message', 'The user balance ' . $this->input->post('credit_get') . ' credit/debit successfully.');
            redirect(ADMIN_DASHBOARD_PATH . '/members/transaction/' . $status . '/' . $user_id, 'refresh');
        }

        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('' . SITE_NAME . 'Member Add Credits')
                ->build('member_add_credit', $this->data);
    }

    public function add_free_bids($status, $user_id) {
        //check id, if it is not set then redirect to view page
        if (!isset($user_id)) {
            redirect(ADMIN_DASHBOARD_PATH . '/members/index/' . $status, 'refresh');
            exit;
        }

        $this->data['profile'] = $this->admin_member->get_member_byid($user_id);

        $this->data['bidpacks'] = $this->admin_member->get_bid_packs();

        $this->form_validation->set_rules('bid_packs', 'Bidpacks', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $this->admin_member->member_free_bids($user_id);
            $this->session->set_flashdata('message', 'The free bid is inserted successfully');
            redirect(ADMIN_DASHBOARD_PATH . '/members/transaction/' . $status . '/' . $user_id, 'refresh');
        }

        $this->template
                ->set_layout('dashboard')
                ->enable_parser(FALSE)
                ->title('' . SITE_NAME . 'Member Add Free Bids')
                ->build('member_free_bids', $this->data);
    }

    public function change_user_password() {
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|max_length[12]');
        if ($this->form_validation->run() == TRUE) {
            echo $this->admin_member->change_member_password();
        } else {
            echo '<input name="password" type="text" class="inputtext" id="password" size="30" /> <input class="bttn" type="button" name="Submit" value="Changed" id="changed"  onclick="changedpassword(this.value)" />' . form_error('password');
        }
    }

    public function check_mobile() {
        if ($this->admin_member->get_aleady_registered_mobile() == TRUE) {
            $this->form_validation->set_message('check_mobile', 'The mobile is already exist.');
            return false;
        }
        return true;
    }

    public function under_18_check() {
        $day = $this->input->post('day', TRUE);
        $month = $this->input->post('month', TRUE);
        $year = $this->input->post('year', TRUE);
        $dateofbirth = $year . "-" . $month . "-" . $day;
        // $then will first be a string-date
        $bday = strtotime($dateofbirth);
        //The age to be over, over +18
        $res = strtotime('+18 years', $bday);
        //echo $res;
        if (time() > $res) {

            return TRUE;
        } else {
            $this->form_validation->set_message('under_18_check', 'You must be 18 years old.');

            return FALSE;
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */