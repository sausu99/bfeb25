<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contact_us_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public $validate_contact_us = array(
        array('field' => 'fname', 'label' => 'First Name', 'rules' => 'trim|required|max_length[50]'),
        array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'),
       // array('field' => 'mobile', 'label' => 'Mobile', 'rules' => 'required|regex_match[/^[0-9+]{10,14}$/]'),
        array('field' => 'message', 'label' => 'Message', 'rules' => 'trim|required|max_length[300]'),
       // array('field' => 'security_code', 'label' => 'Captcha Code', 'rules' => 'trim|required'),
    );

    function get_captcha() {
        $configs = array(
            'word' => strtolower(random_string('alnum', 8)),
            'img_path' => './captcha/',
            'img_url' => MAIN_CAPTCHA_DIR_FULL_PATH,
            'img_width' => '131',
            'img_height' => 40,
            'char_set' => "ABCDEFGHJKLMNPQRSTUVWXYZ2345689",
            'char_color' => "#000000"
        );
        $captcha = $this->antispam->get_antispam_image($configs);

        $cap = strtolower($captcha['word']);

        $this->session->set_userdata('word', $cap);

        return $captcha;
    }

    function send_email() {
        $fname = $this->input->post('fname');
        $email = $this->input->post('email');
        //$phone = $this->input->post('mobile');
        //$subject=$this->input->post('subject');
        $msg = $this->input->post('message');

        $subj = "Contact Us Details";

        //load email library
        $this->load->library('email');


        //set the email things
        $complete_msg = "Full Name: <strong>" . $fname . " </strong>" . br(2);
        $complete_msg .= "Email: " . $email . br(2);
       // $complete_msg .= "Mobile: " . $phone . br(2);
        //$complete_msg .= "Subject: ".$subject.br(2);					
        $complete_msg .= br(1) . "<u>Message</u>: " . br(2);
        $complete_msg .= $msg;

//	    $this->email->from(SYSTEM_EMAIL);
//		$this->email->to(CONTACT_EMAIL); 
//		$this->email->subject($subj);     
//	    $this->email->message($complete_msg); 	
//				
//	   $this->email->send();	
        // echo $this->email->print_debugger();exit;	

        $this->netcoreemail_class->send_email(SYSTEM_EMAIL, CONTACT_EMAIL, $subj, $complete_msg);
    }

    function send_email_tests() {
        $fname = $this->input->post('fname');
        $email = $this->input->post('email');
        $lname = $this->input->post('lname');


        $subj = "Contact Us Details";

        //load email library
        $this->load->library('email');
        //$this->email->clear();
        //configure mail
        //set the email things
        $complete_msg = "Full Name: <strong>" . $fname . " </strong>" . br(2);
        $complete_msg .= "Email: " . $email . br(2);
        $complete_msg .= "Mobile: " . $lname . br(2);
        //$complete_msg .= "Subject: ".$subject.br(2);					
        $complete_msg .= br(1) . "<u>Message</u>: " . br(2);
        //  $complete_msg .= $msg;
//	    $this->email->from(SYSTEM_EMAIL);
//		$this->email->to($email); 
//		$this->email->subject($subj);     
//	    $this->email->message($complete_msg); 	
//				
//	   $this->email->send();

        $this->netcoreemail_class->send_email(SYSTEM_EMAIL, $email, $subj, $complete_msg);
        // echo $this->email->print_debugger();exit;			
    }

}
