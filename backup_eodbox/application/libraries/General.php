<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class General {

    /**
     * CodeIgniter global
     *
     * @var string
     * */
    protected $ci;

    /**
     * account status ('not_activated', etc ...)
     *
     * @var string
     * */
    protected $status;

    /**
     * error message (uses lang file)
     *
     * @var string
     * */
    protected $errors = array();

    public function __construct() {

        $this->ci = & get_instance();

        //define site settings info
        $site_info = $this->get_site_settings_info();

        //System Setting Variable
        define('SITE_NAME', $site_info['site_name']);
        define('SITE_LOGO', $site_info['site_logo']);
        define('CONTACT_EMAIL', $site_info['contact_email']);
		define('CONTACT_PHONE', $site_info['contact_phone']);
		define('CONTACT_ADDRESS', $site_info['contact_address']);
        define('SYSTEM_EMAIL', $site_info['system_email']);
        define('SITE_STATUS', $site_info['site_status']);

        // Bonus Setting
        define('SIGNUP_CREDIT', $site_info['signup_credit']);
        define('SIGNUP_BONUS', $site_info['signup_bonus']);
        define('REFER_BONUS', $site_info['refer_bonus']);

        // Auction Setting
        define('MIN_BID_4BUY_NOW', $site_info['min_bid_4buy_now']);
        define('BUY_NOW_BID_REWARD_TIMES', $site_info['buy_now_bid_reward_times']);
        define('BUY_NOW_PRODUCT_PER_WEEK', $site_info['buy_now_product']);

        //Social page url & API Setting
        define('ANDROID_APP', $site_info['android_app']);
        define('IOS_APP', $site_info['ios_app']);

        define('FACEBOOK_URL', $site_info['facebook_url']);
        define('FACEBOOK_APP_ID', $site_info['facebook_app_id']);
        define('GOOGLE_URL', $site_info['google_url']);
        define('GOOGLE_APP_KEY', $site_info['google_api_key']);
        define('GOOGLE_CLIENT_ID', $site_info['google_client_id']);
        define('TWITTER_URL', $site_info['twitter_url']);
        define('LINKEDIN_URL', $site_info['linkedin_url']);
        define('INSTAGRAM_URL', $site_info['instagram_url']);

        define('TWITTER_APP_KEY', $site_info['twitter_app_key']);
        define('TWITTER_APP_SECRET', $site_info['twitter_app_secret']);
        define('TWITTER_APP_REDIRECT_URI', $this->lang_uri() . '/users/twitter_signup');

        //Tracking code setting
        define('GOOGLE_ANALYTICAL_CODE', $site_info['google_analytical_code']);
        define('HTML_TRACKING_CODE', $site_info['html_tracking_code']);


        //node server
        define('NODE_SERVER', $site_info['node_server']);
        define('NODE_PORT', $site_info['node_port']);

        //MAILCHIMP API DETAILS
        define('API_KEY', $site_info['mailchimp_api_key']);
        define('LIST_ID', $site_info['mailchimp_list_id']);

        //CHECKMOBI SMS Details
        define('CHECKMOBI_SMS_API_KEY', $site_info['checkmobi_sms_api_key']);

        //Default Timezone setting
        define('DEFAULT_TIMEZONE', $site_info['default_timezone']);

        //Bidding Constant Value
        define('BID_INCREMENT', '0.01');
        define('MIN_BID_VALUE', '0.01');
        define('USER_MAX_MULTIPLE_BIDS', '100');

        //Define default language & its details info
        $default_lang_info = $this->get_default_country();
        //print_r($default_lang_info);exit;
        define('DEFAULT_LANG_ID', $default_lang_info->lang_id);
        define('DEFAULT_CURRENCY_CODE', $default_lang_info->currency_code);
        define('DEFAULT_CURRENCY_SIGN', $default_lang_info->currency_sign);
        define('DEFAULT_LANG_EXCHANGE_RATE', $default_lang_info->exchange_rate);
        define('DEFAULT_LANG_DISPLAY_IN', $default_lang_info->currency_display_in);


        //update member login info			 
        $site_user_id = $this->ci->input->post('user_id');
        if ($this->ci->session->userdata(SESSION . 'user_id')) {
            $site_user_id = $this->ci->session->userdata(SESSION . 'user_id');
        }


        if ($site_user_id) {
            $this->updateOnlineMembers($site_user_id);
        }

        define('APP_SECRET_KEY', '12341234');
        //get ccavenue live/test mode
        if($this->ci->session->userdata(SESSION . 'user_id')){
            $this->check_session_active();
        }
        
    }
    public function check_session_active(){
        $this->ci->db->select('token');
        $this->ci->db->where('id',$this->ci->session->userdata(SESSION . 'user_id'));
        $query=$this->ci->db->get('members');
        if($query->num_rows()=='1'){
            $new_id=$query->row()->token;
        }else{
            $new_id='';
        }
        if(isset($_SESSION['token']) && $new_id!=$_SESSION['token']){
            $this->logout();
        }   
    }
    public function logout()
	{
		if($this->ci->session->userdata(SESSION.'login_state')!="")
		{			
			$update_data = array('mem_login_state'=>'0');
			$this->ci->db->where('id',$this->ci->session->userdata(SESSION.'user_id'));
			$this->ci->db->update('members',$update_data);			
		}
		
		$this->ci->session->unset_userdata(SESSION.'user_id');
		$this->ci->session->unset_userdata(SESSION.'first_name');
		$this->ci->session->unset_userdata(SESSION.'email');
		$this->ci->session->unset_userdata(SESSION.'last_name');
		$this->ci->session->unset_userdata(SESSION.'username');
		$this->ci->session->unset_userdata(SESSION.'balance');
		$this->ci->session->unset_userdata(SESSION.'last_login');
		$this->ci->session->unset_userdata(SESSION.'lang_flag');
		$this->ci->session->unset_userdata(SESSION.'short_code');
		$this->ci->session->unset_userdata(SESSION.'lang_id');
		$this->ci->session->unset_userdata(SESSION.'sub_domain');
		$this->ci->session->unset_userdata(SESSION.'terms');
		//$this->session->sess_destroy();
					
		if($this->ci->session->userdata('is_fb_user') == 'Yes')
		{
			//FB Logout
			$this->load->library('Facebook',$this->config->item('facebook'));
			
			$args['next'] = site_url('');
			$logoutUrl = $this->facebook->getLogoutUrl($args);
			$this->facebook->destroySession();
			
			//unset fb session
			$this->ci->session->unset_userdata('is_fb_user');
			
			//set fb logout session for js
			$this->ci->session->set_userdata('fb_logout', 'Yes');
			
		}
        $this->ci->session->set_flashdata('logged_out_device','logged_out');

	}

    public function get_payment_mode_by_id($id) {
        $this->ci->db->where('id', $id);
        $query = $this->ci->db->get('payment_gateway');
        if ($query->num_rows() == '1') {
            return $query->row();
        }
    }

    public function get_all_total() {
        $query = $this->ci->db->get('members');
        return $query->num_rows();
    }

    function get_online_members() {
        $query = $this->ci->db->get_where('members', array("mem_login_state" => '1'));
        return $query->num_rows();
    }

    public function get_join_today_members() {
        $cur_date = date('Y-m-d');
        $query = $this->ci->db->get_where('members', array("date(reg_date)" => $cur_date));
        return $query->num_rows();
    }

    public function get_total_members($status) {

        $this->ci->db->where('status', $status);

        $query = $this->ci->db->get('members');
        return $query->num_rows();
    }

    public function updateOnlineMemberByTime() {

        $options = array('mem_login_state' => '1');
        $this->ci->db->select('id,mem_login_state,mem_last_activated');
        $query = $this->ci->db->get_where('members', $options);

        if ($query->num_rows() > 0) {
            $record = $query->result();
            //print_r($record);exit;

            foreach ($record as $result) {
                $time_now = strtotime($this->get_local_time('time'));
                $login_time = strtotime($result->mem_last_activated);
                $time_diff = $time_now - $login_time;
                $time_diff = ($time_diff / 60);

                if ($time_diff > 3) {
                    $this->ci->db->update('members', array('mem_login_state' => '0'), array('id' => $result->id));
                }
            }
        }
    }

    public function updateOnlineMembers() {
        $time_now = $this->get_local_time('time');

        $data = array(
            'mem_login_state' => '1',
            'mem_last_activated' => $time_now
        );
        //print_r($data);
        $this->ci->db->where('id', $this->ci->session->userdata(SESSION . 'user_id'));
        $this->ci->db->update('members', $data);
    }

    //function to check admin logged in
    public function admin_logged_in() {
        return $this->ci->session->userdata(ADMIN_LOGIN_ID);
    }

    //function to admin logout
    public function admin_logout() {
        $this->ci->session->unset_userdata(ADMIN_LOGIN_ID);
        return true;
    }

    //find user real ip address
    public function get_real_ipaddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];

        return $ip;
    }

    // Get list of all timezonees
    public function timezone_list($name, $default = '') {
        static $timezones = null;

        if ($timezones === null) {
            $timezones = [];
            $offsets = [];
            $now = new DateTime();

            foreach (DateTimeZone::listIdentifiers() as $timezone) {
                $now->setTimezone(new DateTimeZone($timezone));
                $offsets[] = $offset = $now->getOffset();

                $hours = intval($offset / 3600);
                $minutes = abs(intval($offset % 3600 / 60));
                $gmt_ofset = 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');

                $timezone_name = str_replace('/', ', ', $timezone);
                $timezone_name = str_replace('_', ' ', $timezone_name);
                $timezone_name = str_replace('St ', 'St. ', $timezone_name);

                $timezones[$timezone] = $timezone_name . ' (' . $gmt_ofset . ')';
            }

            array_multisort($offsets, $timezones);
        }

        $formdropdown = form_dropdown($name, $timezones, trim($default));

        return $formdropdown;
    }

    //get GMT time from database
    function get_gmt_info() {
        $data = array();
        $CI = & get_instance();
        $CI->db->select("gmt_time");
        $query = $CI->db->get_where("time_zone_setting", array("status" => "on"));
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data['gmt_time'];
    }

    public function convert_gmt_time($dt, $tz1 = DEFAULT_TIMEZONE) {
        $df1 = 'Y-m-d H:i:s';
        $df2 = 'Y-m-d H:i:s';
        $tz2 = 'UTC';

        //Example > $this->general->convert_gmt_time('2017-02-16 14:21:00', 'Asia/Kathmandu', 'Y-m-d H:i:s', 'UTC', 'Y-m-d H:i:s A');
        // create DateTime object
        $d = DateTime::createFromFormat($df1, $dt, new DateTimeZone($tz1));
        // convert timezone
        $d->setTimeZone(new DateTimeZone($tz2));
        // convert dateformat
        return $d->format($df2);
    }

    public function convert_local_time($date, $timeZone = DEFAULT_TIMEZONE, $time = "all") {
        $utc_date = DateTime::createFromFormat('Y-m-d H:i:s', $date, new DateTimeZone('UTC'));

        $nyc_date = $utc_date;
        $nyc_date->setTimeZone(new DateTimeZone($timeZone));

        if ($time == 'none' || $time == 'date') {
            return $nyc_date->format('Y-m-d');
        }
		else if ($time == 'time') {
            return $nyc_date->format('H:i:s');
        }
		 else
            return $nyc_date->format('Y-m-d H:i:s');
    }

    public function get_local_time($time = "time") {
        if ($time == 'none') {
            return gmdate('Y-m-d');
        } else
            return gmdate('Y-m-d H:i:s');
    }

    public function get_gmt_time($time = "") {
        if ($time == 'none') {
            return gmdate('Y-m-d');
        } else
            return gmdate('Y-m-d H:i:s');
    }

    function change_date_time_format_satndard($str) {
        return date("Y-m-d H:i:s", strtotime($str));
    }

    //date format only
    function date_formate($date) {
        $str_date = strtotime($date);
        $dt_frmt = date("D, dS M Y", $str_date);

        return $dt_frmt;
    }

    //date & time format only
    function date_time_formate($str) {
        $str_date = strtotime($str);
        $dt_frmt = date("d.M.Y H:i", $str_date);

        return $dt_frmt;
    }

    //date & time format only
    function full_date_time_formate($str) {
        return date("d M Y H:i:s", strtotime($str));
    }

    function get_local_time_clock() {
        $gmt_info = $this->get_gmt_time('time');
        $gmt_info = $this->convert_local_time($gmt_info, DEFAULT_TIMEZONE, "time");
        $time = explode(':', date('H:i:s', strtotime($gmt_info)));
        return $time[0] * 60 * 60 + $time[1] * 60 + $time[2];
    }

    public function get_site_settings_info() {
        $query = $this->ci->db->get("site_settings");
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }

    public function get_active_countries() {
        $data = array();
        $this->ci->db->order_by('country', 'asc');
        $query = $this->ci->db->get_where("country", array("is_display" => "Yes"));
        if ($query->num_rows() > 0) {
            $data = $query->result();
        }
        $query->free_result();
        return $data;
    }

    public function get_default_country() {
        $query = $this->ci->db->get_where("country", array("default_country" => "Yes"));

        if ($query->num_rows() > 0) {
            $data = $query->row();
        }
        $query->free_result();
        return $data;
    }

    public function get_country_bycode($short_code) {
        $this->ci->db->select('c.*,l.lang_name');
        $this->ci->db->from('country c');
        $this->ci->db->join('language l', 'l.id = c.lang_id', 'right');
        $this->ci->db->where(array("c.short_code" => $short_code));
        $query = $this->ci->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row();
        }
        $query->free_result();
        return $data;
    }

    public function get_user_token($userid) {
        $this->ci->db->select('token');
        $query = $this->ci->db->get_where("members", array("id" => $userid));
        if ($query->num_rows() > 0) {
            $data = $query->row();
            $query->free_result();
            return $data->token;
        }
    }

    public function get_user_timezone_by_country($countryid = false) {
        $this->ci->db->select('country_timezone');
        $this->ci->db->from('country');
        $this->ci->db->where(array("id" => $countryid));
        $query = $this->ci->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data->country_timezone;
        }
        return false;
    }

    public function get_lang_id_by_country($countryid = false) {
        $this->ci->db->select('c.lang_id');
        $this->ci->db->from('country c');
        $this->ci->db->where(array("c.id" => $countryid));
        $query = $this->ci->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data->lang_id;
        }
        return false;
    }

    public function check_country_short_code($code = '') {
        $query = $this->ci->db->get_where("country", array("short_code" => $code, "is_display" => "Yes"));
        return $query->num_rows();
    }

    public function get_default_lang_info() {
        $query = $this->ci->db->get_where("language", array("default_lang" => "Yes"));

        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }

    public function get_all_languages() {
        $data = array();
        $query = $this->ci->db->get_where("language", array("id !=" => LANG_ID, "is_display" => "Yes"));
        if ($query->num_rows() > 0) {
            $data = $query->result();
        }
        $query->free_result();
        return $data;
    }

    public function get_active_languages() {
        $data = array();
        $query = $this->ci->db->get_where("language", array("is_display" => "Yes"));
        if ($query->num_rows() > 0) {
            $data = $query->result();
        }
        $query->free_result();
        return $data;
    }

    public function get_lang_id($short_code) {
        $this->ci->db->select('id');
        $query = $this->ci->db->get_where("language", array("short_code" => $short_code));
        if ($query->num_rows() > 0) {
            $data = $query->row();
        }
        $query->free_result();
        return $data->id;
    }

    public function get_cms_pid($parent_id) {
        //get language id from configure file
        $lang_id = $this->ci->config->item('current_language_id');
        $data = array();


        //$this->ci->db->select('cms_slug,heading');				
        $this->ci->db->where("parent_id", $parent_id);
        $this->ci->db->where(array("lang_id" => $lang_id, "is_display" => "Yes"));
        $this->ci->db->order_by('heading', 'ASC');
        // $where = "'lang_id'='".$lang_id."' AND 'is_display'='Yes' AND parent_id IN (1,3,4,6) ORDER BY parent_id ASC";				 
        //$this->ci->db->where($where);

        $query = $this->ci->db->get("cms");
        //echo $this->ci->db->last_query();exit;
        //print_r($query->result());exit;
        if ($query->num_rows() > 0) {
            $data = $query->row();
        } else {
            $this->ci->db->where("parent_id", $parent_id);
            $this->ci->db->where("lang_id", DEFAULT_LANG_ID);
            $this->ci->db->order_by('parent_id', 'DESC');
            $query = $this->ci->db->get("cms");

            if ($query->num_rows() > 0) {
                $data = $query->row();
            }
        }
        $query->free_result();
        return $data;
    }
	
	public function get_all_cms_except($parent_id) {
        //get language id from configure file
        $lang_id = $this->ci->config->item('current_language_id');
        $data = array();


        $this->ci->db->select('cms_slug,heading');
        $this->ci->db->where_not_in("parent_id", $parent_id);
        $this->ci->db->where(array("lang_id" => $lang_id, "is_display" => "Yes"));
       
        $query = $this->ci->db->get("cms");
       
        if ($query->num_rows() > 0) {
            $data = $query->result();
        } else {
            $this->ci->db->where_not_in("parent_id", $parent_id);
            $this->ci->db->where("lang_id", DEFAULT_LANG_ID);
            $this->ci->db->order_by('parent_id', 'DESC');
            $query = $this->ci->db->get("cms");

            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }
        $query->free_result();
        return $data;
    }
	
    public function get_cms_lists($parent_id) {
        //get language id from configure file
        $lang_id = $this->ci->config->item('current_language_id');
        $data = array();


        $this->ci->db->select('cms_slug,heading');
        $this->ci->db->where_in("parent_id", $parent_id);
        $this->ci->db->where(array("lang_id" => $lang_id, "is_display" => "Yes"));
        //$this->ci->db->order_by('heading', 'ASC');
        // $where = "'lang_id'='".$lang_id."' AND 'is_display'='Yes' AND parent_id IN (1,3,4,6) ORDER BY parent_id ASC";				 
        //$this->ci->db->where($where);

        $query = $this->ci->db->get("cms");
        //echo $this->ci->db->last_query();exit;
        //print_r($query->result());exit;
        if ($query->num_rows() > 0) {
            $data = $query->result();
        } else {
            $this->ci->db->where_in("parent_id", $parent_id);
            $this->ci->db->where("lang_id", DEFAULT_LANG_ID);
            $this->ci->db->order_by('parent_id', 'DESC');
            $query = $this->ci->db->get("cms");

            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }
        $query->free_result();
        return $data;
    }

    public function get_cms_link_byid($cms_id) {
        //get language id from configure file
        $lang_id = $this->ci->config->item('current_language_id');
        $data = array();

        $this->ci->db->select('cms_slug,heading');
        $this->ci->db->where("parent_id", $cms_id);
        $this->ci->db->where(array("lang_id" => $lang_id, "is_display" => "Yes"));
        $query = $this->ci->db->get("cms");

        if ($query->num_rows() > 0) {
            $data = $query->row();
        } else {
            $this->ci->db->where("parent_id", $cms_id);
            $this->ci->db->where("lang_id", DEFAULT_LANG_ID);
            $this->ci->db->order_by('parent_id', 'ASC');
            $query = $this->ci->db->get("cms");

            if ($query->num_rows() > 0) {
                $data = $query->row();
            }
        }
        $query->free_result();

        echo '<a href="' . $this->lang_uri("/page/" . $data->cms_slug) . '" target="_blank">' . $data->heading . '</a>';
    }

    public function get_latest_testimonial() {
        $data = array();
        $this->ci->db->order_by("id", 'DESC');
        $query = $this->ci->db->get_where("testimonial", array('status' => 'Active'));

        if ($query->num_rows() > 0) {
            $data = $query->row();
        }
        $query->free_result();
        return $data;
    }

    public function get_remaining_time($end_time) {
        return strtotime($end_time) - strtotime($this->get_local_time('time'));
    }

    function auction_reset_time($auc_remaining_time, $auction_reset_time) {
        if ($auc_remaining_time < $auction_reset_time) {
            $reset_time = $auction_reset_time - $auc_remaining_time;
        } else {
            $reset_time = 0;
        }
        return $reset_time;
    }

    function auction_timer($end_time) {
        $time_left = strtotime($end_time) - strtotime($this->get_local_time('time'));

        if ($time_left >= 0 && $time_left <= 60) {
            return $time_left . 's';
        }


        $oneMinute = 60;
        $oneHour = $oneMinute * 60;
        $oneDay = $oneHour * 24;

        $dayfield = floor($time_left / $oneDay);
        $hourfield = floor(($time_left - $dayfield * $oneDay) / $oneHour);
        $minutefield = floor(($time_left - $dayfield * $oneDay - $hourfield * $oneHour) / $oneMinute);
        $secondfield = floor(($time_left - $dayfield * $oneDay - $hourfield * $oneHour - $minutefield * $oneMinute));

        if ($dayfield > 0)
            $dayfield = $dayfield . "d";
        else
            $dayfield = "";


        if ($hourfield < 10)
            $hourfield = "0" . $hourfield;
        if ($minutefield < 10)
            $minutefield = "0" . $minutefield;
        if ($secondfield < 10)
            $secondfield = "0" . $secondfield;

        if ($time_left < 0) {
            return 'Checking...';
        } else if ($dayfield == 0 && $hourfield == 0) {
            return $minutefield . ':' . $secondfield;
        } else {
            return $dayfield . ' ' . $hourfield . ':' . $minutefield . ':' . $secondfield;
        }
    }

    function auction_end_days($end_time) {
        $time_gone = strtotime($this->get_local_time('time')) - strtotime($end_time);

        if ($time_gone >= 0 && $time_gone <= 60) {
            return 0;
        }

        $oneMinute = 60;
        $oneHour = $oneMinute * 60;
        $oneDay = $oneHour * 24;

        $dayfield = floor($time_gone / $oneDay);

        if ($dayfield > 0) {
            return $dayfield;
        }
    }

    function random_number() {
        return mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(11, 99);
    }

    function clean_url($str, $replace = array(), $delimiter = '-') {
        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    function check_block_ip($ip_address) {
        $this->ci->db->select('ip_address ');
        $query = $this->ci->db->get_where("block_ips", array("ip_address" => $ip_address));
        return $query->num_rows();
    }

    public function get_country() {
        $data = array();
        $this->ci->db->where('is_display', 'Yes');
        $this->ci->db->order_by("country", "asc");
        $query = $this->ci->db->get("country");
        if ($query->num_rows() > 0) {
            $data = $query->result();
        }
        $query->free_result();
        return $data;
    }

    public function get_country_info_byid($country_id) {
        $data = array();

        $query = $this->ci->db->get_where("country", array("id" => $country_id, "is_display" => "Yes"));
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }
        $query->free_result();

        return $data;
    }

    //This function return either language information based on current language id 
    //Other wise it return default language information

    public function get_lang_info($lang_id) {
        $data = array();

        $query = $this->ci->db->get_where("language", array("id" => $lang_id, "is_display" => "Yes"));
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }
        $query->free_result();

        return $data;
    }

    public function default_exchange_price($price) {
        $exchange_rate = DEFAULT_LANG_EXCHANGE_RATE;
        return number_format($price * $exchange_rate, '2', '.', '');
    }

    public function exchange_price($price) {
        if (LANG_EXCHANGE_RATE == "")
            $exchange_rate = DEFAULT_LANG_EXCHANGE_RATE;
        else
            $exchange_rate = LANG_EXCHANGE_RATE;
        return number_format($price * $exchange_rate, '2', '.', '');
    }

    public function formate_currency_sign_normal($price) {
        if (LANG_EXCHANGE_RATE == "")
            $exchange_rate = DEFAULT_LANG_EXCHANGE_RATE;
        else
            $exchange_rate = LANG_EXCHANGE_RATE;
        if (LANG_DISPLAY_IN == "")
            $display_in = DEFAULT_LANG_DISPLAY_IN;
        else
            $display_in = LANG_DISPLAY_IN;
        if (LANG_CURRENCY_SIGN == "")
            $currency_sign = DEFAULT_CURRENCY_SIGN;
        else
            $currency_sign = LANG_CURRENCY_SIGN;


        $price = number_format($price * $exchange_rate, '2', '.', '');

        if ($display_in == 'Right') {
            $formate = $html_start_tag . ' ' . $currency_sign;
        } else {
            $formate = $currency_sign . ' ' . $price;
        }

        return $formate;
    }

    public function default_formate_price_currency_sign($price, $html_start_tag = "", $html_end_tag = "") {
        $exchange_rate = DEFAULT_LANG_EXCHANGE_RATE;
        if (LANG_DISPLAY_IN == "")
            $display_in = DEFAULT_LANG_DISPLAY_IN;
        else
            $display_in = LANG_DISPLAY_IN;
        if (LANG_CURRENCY_SIGN == "")
            $currency_sign = DEFAULT_CURRENCY_SIGN;
        else
            $currency_sign = LANG_CURRENCY_SIGN;


        $price = number_format($price * $exchange_rate, '2', '.', '');

        if ($display_in == 'Right') {
            $formate = $html_start_tag . $price . $html_end_tag . '<span> ' . $currency_sign . '</span>';
        } else {
            $formate = '<span>' . $currency_sign . ' </span>' . $html_start_tag . $price . $html_end_tag;
        }

        return $formate;
    }

    public function formate_price_currency_sign($price, $html_start_tag = "", $html_end_tag = "") {
        if (LANG_EXCHANGE_RATE == "")
            $exchange_rate = DEFAULT_LANG_EXCHANGE_RATE;
        else
            $exchange_rate = LANG_EXCHANGE_RATE;
        if (LANG_DISPLAY_IN == "")
            $display_in = DEFAULT_LANG_DISPLAY_IN;
        else
            $display_in = LANG_DISPLAY_IN;
        if (LANG_CURRENCY_SIGN == "")
            $currency_sign = DEFAULT_CURRENCY_SIGN;
        else
            $currency_sign = LANG_CURRENCY_SIGN;


        $price = number_format($price * $exchange_rate, '2', '.', '');

        if ($display_in == 'Right') {
            $formate = $html_start_tag . $price . $html_end_tag . '<span> ' . $currency_sign . '</span>';
        } else {
            $formate = '<span>' . $currency_sign . ' </span>' . $html_start_tag . $price . $html_end_tag;
        }

        return $formate;
    }

    public function formate_price_currency_sign_admin($lang_id, $price) {
        //$lang_data = $this->get_lang_info($lang_id);
        $lang_data = $this->get_country_info_byid($lang_id);
        //print_r($lang_data);exit;

        $exchange_rate = $lang_data['exchange_rate'];
        $display_in = $lang_data['currency_display_in'];
        $currency_sign = $lang_data['currency_sign'];

        $price = number_format($price * $exchange_rate, '2', '.', '');

        if ($display_in == 'Right') {
            $formate = $price . '<span> ' . $currency_sign . '</span>';
        } else {
            $formate = '<span>' . $currency_sign . ' </span>' . $price;
        }

        return $formate;
    }

    public function default_formate_price_currency_sign_admin($lang_id, $price) {
        //$lang_data = $this->get_lang_info($lang_id);
        $lang_data = $this->get_country_info_byid($lang_id);
        //print_r($lang_data);exit;

        $exchange_rate = DEFAULT_LANG_EXCHANGE_RATE; //$lang_data['exchange_rate'];
        $display_in = $lang_data['currency_display_in'];
        $currency_sign = $lang_data['currency_sign'];

        $price = number_format($price * $exchange_rate, '2', '.', '');

        if ($display_in == 'Right') {
            $formate = $price . '<span> ' . $currency_sign . '</span>';
        } else {
            $formate = '<span>' . $currency_sign . ' </span>' . $price;
        }

        return $formate;
    }

    function get_user_balance($user_id) {
        $data = array();
        $CI = & get_instance();
        $CI->db->select("balance");
        $query = $CI->db->get_where("members", array("id" => $user_id));
        // echo $CI->db->last_query();

        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data->balance;
        }

        return false;
    }

    function get_user_bonus($user_id) {
        $data = array();
        $CI = & get_instance();
        $CI->db->select("bonus_points");
        $query = $CI->db->get_where("members", array("id" => $user_id));
        // echo $CI->db->last_query();

        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data->bonus_points;
        }

        return false;
    }

    function get_user_profile_img($user_id) {
        $data = array();
        $CI = & get_instance();
        $CI->db->select("image,gender");
        $query = $CI->db->get_where("members", array("id" => $user_id));
        // echo $CI->db->last_query();

        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }

        return false;
    }

    public function getBidStatus1($auc_amt, $auc_id) {
        //winner till now to compare with $auc_amt
        $winner = $this->get_winner($auc_id);

        $this->winning_amt = $winner['userbid_bid_amt'];


        $option = array('userbid_bid_amt' => $auc_amt, 'auc_id' => $auc_id);

        $this->ci->db->select('count(userbid_bid_amt) as num');

        $query = $this->ci->db->get_where('user_bids', $option);

        if ($query->num_rows() > 0) {

            $data = $query->row();

            $data->num;
        }

        if ($data->num > 1) {
            return "not_unique";
        } else if ($auc_amt == $this->winning_amt) {
            return "Lowest_Unique_Bid";
        } else {
            return "Unique_But_Not_Lowest";
        }
    }

    public function get_bid_on_auction($auc_id) {
        $data = array();
        $CI = & get_instance();
        $CI->db->select("auc_id,bid_date,userbid_bid_amt,freq");
        $CI->db->order_by("userbid_bid_amt", "asc");
        //$CI->db->limit(10);
        $query = $CI->db->get_where("user_bids", array("user_id" => $CI->session->userdata(SESSION . 'user_id'), 'auc_id' => $auc_id));
        // echo $CI->db->last_query();
        if ($query->num_rows() > 0) {
            $data = $query->result();
        }
        $query->free_result();
        return $data;
    }

    public function get_winner($auc_id) {
        $this->ci->db->select('userbid_bid_amt,count(userbid_bid_amt) as times');
        $this->ci->db->group_by('userbid_bid_amt');
        $this->ci->db->order_by('times,userbid_bid_amt', "asc"); //asc so from lowest to highest lowest means winnin amount 
        $query = $this->ci->db->get_where('user_bids', array("auc_id" => $auc_id));
//                        echo $this->ci->db->last_query();exit;

        $data = $query->result_array();
        //$winning_amount=$data[0]['userbid_bid_amt'];

        $winning_amount = isset($data[0]['userbid_bid_amt']) ? $data[0]['userbid_bid_amt'] : '';
        if (!$winning_amount)
            return false;
        $this->ci->db->select('m.first_name,m.image,m.country,m.gender,m.address,m.state,m.city,m.last_name,m.user_name,b.user_id,b.id AS bid_id,b.userbid_bid_amt');
        $this->ci->db->from('user_bids b');
        $this->ci->db->join('members m', 'm.id = b.user_id', 'left');
        $this->ci->db->where(array('b.userbid_bid_amt' => (float) $winning_amount, 'b.auc_id' => $auc_id, 'm.obsence_flag' => 'no'));
        $this->ci->db->order_by('b.bid_date', "asc");
        $qry = $this->ci->db->get();
//                        echo $this->ci->db->last_query();exit;
        $win = $qry->row_array();
        return $win;
    }

    function check_float_vlaue($str) {
        if (preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $str)) {
            return true;
        } else {
            return false;
        }
    }

    function check_int_vlaue($str) {
        if (preg_match("/^[0-9]+$/", $str)) {
            return true;
        } else {
            return false;
        }
    }

    function change_lang_uri($lang, $url) {
        //if($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "202.79.37.78:3000" || $_SERVER['HTTP_HOST'] == "nepaimpressions.com")
        return site_url() . $lang . '/' . $url;
        //else if($_SERVER['HTTP_HOST'] == "de.auktis-staging.com" || $_SERVER['HTTP_HOST'] == "de.viewhimalaya.com")
        //return 'http://'.$lang.'.'.WEBSITE_NAME.$url;
        //else
        //return 'https://'.$lang.'.'.WEBSITE_NAME.$url;
    }

    function lang_switch_uri($lang) {
        //if($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "202.79.37.78:3000" || $_SERVER['HTTP_HOST'] == "nepaimpressions.com")
        return site_url() . $lang;
        //else
        //return str_replace(LANG_SHORT_CODE, $lang, site_url());
    }

    function lang_uri($path = '') {
        //if($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "202.79.37.78:3000" || $_SERVER['HTTP_HOST'] == "nepaimpressions.com")
        return site_url($this->ci->uri->segment(1) . $path);
        //else
        //return site_url($path);
    }

    public function salt() {
        return substr(md5(uniqid(rand(), true)), 0, '10');
    }

    public function hash_password($password, $salt) {

        return sha1($salt . sha1($salt . sha1($password)));
    }

    function create_password($length = 8, $use_upper = 1, $use_lower = 1, $use_number = 1, $use_custom = "") {
        $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $lower = "abcdefghijklmnopqrstuvwxyz";
        $number = "0123456789";
        $seed_length = '';
        $seed = '';
        $password = '';

        if ($use_upper) {
            $seed_length += 26;
            $seed .= $upper;
        }
        if ($use_lower) {
            $seed_length += 26;
            $seed .= $lower;
        }
        if ($use_number) {
            $seed_length += 10;
            $seed .= $number;
        }
        if ($use_custom) {
            $seed_length += strlen($use_custom);
            $seed .= $use_custom;
        }

        for ($x = 1; $x <= $length; $x++) {
            $password .= $seed[rand(0, $seed_length - 1)];
        }

        return $password;
    }

    public function check_banned_ip() {
        //get user ip and check with banned IP address lists.
        $user_ip = $this->ci->input->ip_address();//$this->get_real_ipaddr();
		
        if ($this->check_block_ip($user_ip) !== 0) {
            redirect($this->lang_uri('/ipbanned'), 'refresh');
            exit;
        }
    }

    public function get_more_live_auction($limit = '',$ex_prod=false) {
        //get language id from configure file
        $lang_id = $this->ci->config->item('current_language_id');
        $current_dt = $this->get_local_time('time');

        $this->ci->db->select('a.*,ad.name');
        $this->ci->db->from('auction a');
        $this->ci->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');

        $array = array('start_date <=' => $current_dt, 'end_date >=' => $current_dt, 'lang_id' => $lang_id, 'is_display' => 'Yes', 'status' => 'Live', 'auc_type' => 'lub');
        $this->ci->db->where($array);
        if($ex_prod)
            $this->ci->db->where('a.product_id !=',$ex_prod);
        $this->ci->db->order_by("a.end_date asc");
        if ($limit)
            $this->ci->db->limit($limit);

        $query = $this->ci->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result();
            $query->free_result();
            return $data;
        }
    }

    public function get_user_refer_id($user_id) {
        $data = array();
        $CI = & get_instance();
        $CI->db->select("referer_marketing");
        $query = $CI->db->get_where("members", array("id" => $user_id));
        if ($query->num_rows() > 0) {
            $data = $query->row();
            $query->free_result();
            return $data->referer_marketing;
        }

        return false;
    }

    // sms
    function gw_send_sms($user, $pass, $sms_from, $sms_to, $sms_msg) {
        // http://gateway80.onewaysms.sg/api2.aspx        
        $query_string = "?apiusername=" . $user . "&apipassword=" . $pass;
        $query_string .= "&senderid=" . rawurlencode($sms_from) . "&mobileno=" . rawurlencode($sms_to);
        $query_string .= "&message=" . rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";
        $url = "http://gateway80.onewaysms.sg/api2.aspx" . $query_string;
        // echo $url ;  exit;

        $fd = @implode('', file($url));
        // print_r($fd); echo file ($url); exit; 				     
        if ($fd) {
            //print_r($fd);  exit;                      
            if ($fd > 0) {
                //Print("MT ID : " . $fd);
                $ok = "success";
            } else {
                //print("Please refer to API on Error : " . $fd);
                $ok = "fail";
            }
        } else {
            // no contact with gateway                      
            $ok = "fail";
        }
        return $ok;
    }

    public function buy_bids_voucher($voucher_code) {
        $CI = & get_instance();
        $query = $CI->db->get_where("vouchers", array("code" => $voucher_code));

        if ($query->num_rows() > 0) {
            $data = $query->row();

            $id = $data->id;
            $limit_number = $data->limit_number;
            $limit_per_user = $data->limit_per_user;
            $extra_bids = $data->extra_bids;
            $start_date = $data->start_date;
            $end_date = $data->end_date;
            $current_date = $this->get_local_time('none');

            //Check date range
            if ($start_date <= $current_date && $end_date >= $current_date && $extra_bids != 0) {
                //get total voucher used
                $query_txn_voucher = $CI->db->get_where("transaction", array("voucher_id" => $id, "transaction_status" => "Completed", 'transaction_type' => 'voucher'));

                //Get total boucher used by this user
                $query_user_voucher = $CI->db->get_where("transaction", array("voucher_id" => $id, "transaction_status" => "Completed", "user_id" => $CI->session->userdata(SESSION . 'user_id'), 'transaction_type' => 'voucher'));

                if (($query_txn_voucher->num_rows() < $limit_number || $limit_number == 0) && ($query_user_voucher->num_rows() < $limit_per_user || $limit_per_user == 0)) {
                    return $id;
                }
            }
        }
    }

    public function get_user_details_by_user_name($user_name) {
        $this->ci->db->select('id,email,user_name,balance,bonus_points,token,id,country,mobile,push_id');
        $query = $this->ci->db->get_where("members", array("user_name" => $user_name));
        // echo $this->ci->db->last_query();exit;
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
    }

    public function get_device_id($push_id) {
        $this->ci->db->select('device_id');
        $query = $this->ci->db->get_where("device", array("id" => $push_id));
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data->device_id;
        }
    }

    public function check_notification_enable($email_code) {
        $this->ci->db->select('is_push_notification_send,is_email_notification_send,is_sms_notification_send,sms_body,push_message_body,subject');
        $query = $this->ci->db->get_where("email_settings", array("email_code" => $email_code));
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
        return false;
    }

    public function give_extra_bids_voucher($voucher_id, $bid_credit) {
        $CI = & get_instance();

        $query = $CI->db->get_where("vouchers", array("id" => $voucher_id));

        if ($query->num_rows() > 0) {
            $data = $query->row();

            $id = $data->id;
            $limit_number = $data->limit_number;
            $limit_per_user = $data->limit_per_user;
            $extra_bids = $data->extra_bids;
            $start_date = $data->start_date;
            $end_date = $data->end_date;
            $current_date = $this->get_local_time('none');

            //Check date range
            if ($start_date <= $current_date && $end_date >= $current_date && $extra_bids != 0) {
                //get total voucher used
                $query_txn_voucher = $CI->db->get_where("transaction", array("voucher_id" => $id, "transaction_status" => "Completed", 'transaction_type' => 'voucher'));

                //Get total boucher used by this user
                $query_user_voucher = $CI->db->get_where("transaction", array("voucher_id" => $id, "transaction_status" => "Completed", "user_id" => $CI->session->userdata(SESSION . 'user_id'), 'transaction_type' => 'voucher'));

                if (($query_txn_voucher->num_rows() < $limit_number || $limit_number == 0) && ($query_user_voucher->num_rows() < $limit_per_user || $limit_per_user == 0)) {
                    return floor(($bid_credit * $extra_bids) / 100);
                }
            }
        }
    }

    public function transaction_records_extra_bids_voucher($user_id, $extra_bids, $voucher_id, $voucher_code) {
        $CI = & get_instance();

        $item_name = 'Get extra bids from Voucher : '.$voucher_code;

        $data = array('transaction_status' => 'Completed', 'user_id' => $user_id, 'payment_date' => $this->get_local_time('time'),
            'credit_get' => $extra_bids, 'transaction_name' => $item_name, 'transaction_type' => 'voucher',
            'transaction_date' => $this->get_local_time('time'), 'voucher_id' => $voucher_id, 'voucher_code' => $voucher_code);
        $CI->db->insert('transaction', $data);
    }
	
	public function check_mobile_blank_field() {
        $CI = & get_instance();

        $CI->db->where("id ='" . $CI->session->userdata(SESSION . 'user_id') . "' AND (mobile='')");
        $query = $CI->db->get('members');
        return $query->num_rows();
    }
	
    public function check_profile_blank_field() {
        $CI = & get_instance();

        $CI->db->where("id ='" . $CI->session->userdata(SESSION . 'user_id') . "' AND (country='' OR address='' OR dob_year='')");
        $query = $CI->db->get('members');
        return $query->num_rows();
    }

    public function get_bid_package_auc_byid($auc_id) {
        $CI = & get_instance();

        $CI->db->where("product_id", $auc_id);
        $CI->db->where("is_bid_package", "Yes");
        $CI->db->where("bids_value !=", "0");
        $query = $CI->db->get('auction');
        //echo $CI->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function transaction_records_bidpackage($user_id, $bids, $auc_id) {
        $CI = & get_instance();

        $item_name = $bids . ' Bids (#' . $auc_id . ')';

        $data = array('transaction_status' => 'Completed', 'user_id' => $user_id, 'payment_date' => $this->get_local_time('time'),
            'credit_get' => $bids, 'transaction_name' => $item_name, 'transaction_type' => 'bidsPackage',
            'transaction_date' => $this->get_local_time('time'));
        $CI->db->insert('transaction', $data);
    }

    public function get_bid_count_by_auc_user($auc_id, $user_id) {
        $CI = & get_instance();

        $CI->db->where("auc_id", $auc_id);
        $CI->db->where("user_id", $user_id);
        $query = $CI->db->get('user_bids');
        //echo $CI->db->last_query();
        return $query->num_rows();
    }

    public function is_buy_now_auc($auc_id) {
        $CI = & get_instance();

        $CI->db->where("product_id", $auc_id);
        $CI->db->where("is_display", "Yes");
        $CI->db->where("status", "Live");
        $CI->db->where("is_buy_now", "Yes");
        $query = $CI->db->get('auction');
        //echo $CI->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function is_user_buy_auc($auc_id, $user_id) {
        $CI = & get_instance();

        $CI->db->where("auc_id", $auc_id);
        $CI->db->where("user_id", $user_id);
        $CI->db->where("transaction_type", "buy_auction");
        $CI->db->where("transaction_status", "Completed");
        $query = $CI->db->get('transaction');
        //echo $CI->db->last_query();
        return $query->num_rows();
    }

    public function total_sold_product($auc_id) {
        $CI = & get_instance();

        $CI->db->where("auc_id", $auc_id);
        //$CI->db->where("user_id",$user_id);
        $CI->db->where("transaction_type", "buy_auction");
        $CI->db->where("transaction_status", "Completed");
        $query = $CI->db->get('transaction');
        //echo $CI->db->last_query();
        return $query->num_rows();
    }

    public function check_user_purchase_product_inweek($user_id) {
        $CI = & get_instance();
        $current_time = $this->get_local_time('time');
        //$CI->db->where("auc_id",$auc_id);
        $CI->db->where("user_id", $user_id);
        $CI->db->where("transaction_type", "buy_auction");
        $CI->db->where("transaction_status", "Completed");
        $CI->db->where('transaction_date >= ' . "'" . $current_time . "'" . ' - INTERVAL 7 DAY');
        $query = $CI->db->get('transaction');
        //echo $CI->db->last_query();exit;
        return $query->num_rows();
    }

    function update_shipping_status($ship_status, $invoice_id) {
        $CI = & get_instance();
        $data = array('buy_auc_shipping_status' => $ship_status);
        $CI->db->where('invoice_id', $invoice_id);
        $CI->db->update('auction_winner_address', $data);
    }

    function check_pause_status() {
        $site_info = $this->get_site_settings_info();
        $pause_status = $site_info['pause_status'];
        $pause_start_time = $site_info['pause_start_time'];
        $pause_end_time = $site_info['pause_end_time'];


        $current_time = date('H', strtotime($this->get_local_time('time')));

        if ($pause_end_time > $pause_start_time) {
            if ($pause_status == 'Yes' && ($current_time >= $pause_start_time && $current_time < $pause_end_time)) {
                return true;
            }
        }
        if ($pause_start_time > $pause_end_time) {
            if ($pause_status == 'Yes' && ($current_time >= $pause_start_time || $current_time < $pause_end_time)) {
                //echo $pause_status .'=='. 'Yes'.' && ('.$current_time.'>='.$pause_start_time.'  || '.$current_time.'<'.$pause_end_time.')';exit;
                return true;
            }
        }
        return false;
    }

    public function get_winner_amt($auc_id) {
        $this->ci->db->select('freq, userbid_bid_amt');
        $this->ci->db->order_by('freq,userbid_bid_amt', "asc");
        $query = $this->ci->db->get_where('user_bids', array("auc_id" => $auc_id, "freq" => 1));
        // echo $this->ci->db->last_query();

        if ($query->num_rows() > 0) {
            $win = $query->row();
            return $win->userbid_bid_amt;
        }

        return false;
    }

    public function string_limit($string, $limit) {
        $name = (strlen($string) > $limit) ? substr($string, 0, $limit) . '...' : $string;
        return $name;
    }

    public function long_date_time_format($str) {
        return date('D, M d, Y H:i A', strtotime($str));
    }

    public function get_vote_count($product_id, $column = false) {
        $CI = & get_instance();
        // $CI->db->select("SUM() as vote');
        if ($column == 'positive_rating') {
            $CI->db->where('positive_rating', '1');
        }
        if ($column == 'negative_rating') {
            $CI->db->where('negative_rating', '1');
        }

        $qry = $CI->db->get_where('auction_vote', array('product_id' => $product_id));
        //echo $CI->db->last_query();
        if ($qry->num_rows() > 0) {
            return $qry->num_rows();
        }
        return 0;
    }

    public function get_all_categories_display($lang_id = false) {
        $CI = & get_instance();
        $CI->db->order_by('name', 'asc');
        $CI->db->where('is_display', 'Y');
        if ($lang_id) {
            $CI->db->where('lang_id', $lang_id);
        }
        $query = $CI->db->get('product_categories');
        // echo $CI->db->last_query();
        // die();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function get_page_name_by_id($pid) {

        $CI = & get_instance();
        $qry = $CI->db->get_where('seo_pages', array('id' => $pid));
        if ($qry->num_rows() > 0) {
            $result = $qry->row();
            return $result->page;
        }
        return false;
    }

    public function get_month($lang) {
        $months = array(
            'en' => array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'),
            'fi' => array(1 => 'tammi', 2 => 'helmi', 3 => 'maalis', 4 => 'huhti', 5 => 'touko', 6 => 'kesä', 7 => 'heinä', 8 => 'elo', 9 => 'syys', 10 => 'loka', 11 => 'marras', 12 => 'joulu')
        );

        return $months[$lang];
    }

    public function get_seo($lang_id, $parent_id = '') {
        $this->ci->db->select('page_title, meta_key, meta_description');
        $this->ci->db->from('emts_seo');
        $this->ci->db->where('lang_id', $lang_id);
        $this->ci->db->where('seo_pages_id', $parent_id);
        $this->ci->db->limit(1);
        $query = $this->ci->db->get();
        // echo $this->ci->db->last_query(); exit;
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return false;
    }
	public function get_user_watchlist($user_id) {
        $CI = & get_instance();
        $query = $CI->db->get_where('member_watch_lists', array('user_id' => $user_id));
        // echo $CI->db->last_query();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function get_watchlist_check($user_id, $product_id) {
        $CI = & get_instance();
        $query = $CI->db->get_where('member_watch_lists', array('auction_id' => $product_id, 'user_id' => $user_id));
        // echo $CI->db->last_query();

        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    public function get_vote_check($user_id, $product_id) {
        $CI = & get_instance();
        $query = $CI->db->get_where('auction_vote', array('product_id' => $product_id, 'user_id' => $user_id));
        // echo $CI->db->last_query();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    function paginate_function($item_per_page, $current_page, $total_records, $total_pages) {
        $pagination = '';
        if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { //verify total pages and current page number
            $pagination .= '<ul class="pagination">';

            $right_links = $current_page + 3;
            $previous = $current_page - 3; //previous link 
            $next = $current_page + 1; //next link
            $first_link = true; //boolean var to decide our first link

            if ($current_page > 1) {
                $previous_link = ($previous == 0) ? 1 : $previous;
                $pagination .= '<li><a href="javascript:void(0)" aria-label="First" data-page="1" title="First"><span aria-hidden="true">&laquo;</span></a></li>'; //first link
                //$pagination .= '<li><a href="javascript:void(0)" aria-label="Previous" data-page="'.$previous_link.'" title="Previous"><span aria-hidden="true">&lt;</span></a></a></li>'; //previous link (Dont Delete)
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li><a href="javascript:void(0)" data-page="' . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
                    }
                }
                $first_link = false; //set first link to false
            }

            if ($first_link) { //if current active page is first link
                $pagination .= '<li class="active"><a href="javascript:void(0)" class="active">' . $current_page . '</a></li>';
            } elseif ($current_page == $total_pages) { //if it's the last active link
                $pagination .= '<li class="active"><a href="javascript:void(0)" class="active">' . $current_page . '</a></li>';
            } else { //regular current link
                $pagination .= '<li class="active"><a href="javascript:void(0)" class="active">' . $current_page . '</a></li>';
            }

            for ($i = $current_page + 1; $i < $right_links; $i++) { //create right-hand side links
                if ($i <= $total_pages) {
                    $pagination .= '<li><a href="javascript:void(0)" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
                }
            }
            if ($current_page < $total_pages) {
                $next_link = ($i > $total_pages) ? $total_pages : $i;
                //$pagination .= '<li><a href="javascript:void(0)" data-page="'.$next_link.'" aria-label="Next" title="Next"><span aria-hidden="true">&gt;</span></a>'; //Next Link (Dont Delete)
                $pagination .= '<li><a href="javascript:void(0)" data-page="' . $total_pages . '" title="Last" aria-label="Last"><span aria-hidden="true">&raquo;</span></a></li>'; //last link
            }
            $pagination .= '</ul>';
        }
        //echo $pagination; exit;
        return $pagination; //return pagination links
    }

    //pagination config for frontend
    public function frontend_pagination_config(&$config) {
        $config['full_tag_open'] = '<nav class="pagination_sec"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['next_link'] = '<i class="flaticon-right-arrow"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="flaticon-left-arrow"></i>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)" class="active">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        //$config['first_link'] = lang('general_page_first_link');
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        //$config['last_link'] = lang('general_page_last_link');
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        /*$get_vars = $this->ci->input->get();
        if (is_array($get_vars)) {
            $config['suffix'] = '?' . http_build_query($get_vars, '', '&');
        }*/
        return $config;
    }

    public function get_closed_auctions($limit) {
        //get language id from configure file
        $lang_id = LANG_ID;

        $this->ci->db->select('a.*,ad.*, count(*) as total_bids, m.image,m.gender');
        $this->ci->db->from('auction a');
        $this->ci->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
        $this->ci->db->join('user_bids ub', 'ub.auc_id = a.product_id AND ub.user_id=a.current_winner_id', 'right');
        $this->ci->db->join('members m', 'm.id = a.current_winner_id', 'right');
        $array = array('ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status !=' => 'Live', 'a.current_winner_id !=' => NULL);

        $this->ci->db->where($array);
        $this->ci->db->group_by('a.id');
        $this->ci->db->limit($limit);
        $this->ci->db->order_by("a.end_date", "desc");

        $query = $this->ci->db->get();
        //echo $this->ci->db->last_query();;exit;
        if ($query->num_rows() > 0) {
            $data = $query->result();
            $query->free_result();
            return $data;
        }
        return FALSE;
    }

    public function get_indian_states() {
        $this->ci->db->where('is_display', 'yes');
        $this->ci->db->group_by('city_state');
        $this->ci->db->order_by('city_state', 'ASC');
        $query = $this->ci->db->get('indian_states');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return fasel;
        }
    }
	
	public function get_days($date)
	{
		$today = time(); // or your date as well
		$end_date = strtotime($date);
		$datediff = $end_date - $today;

		$result = round($datediff / (60 * 60 * 24));
		if($result >0)
		 return 'Open After '.$result.' Days';
		 else return 'Open Today '.date('H:i',strtotime($date));
	}

}
