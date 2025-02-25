<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_push_settings extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public $validate_settings = array(
        array('field' => 'subject', 'label' => 'Notification title', 'rules' => 'required|max_length[65]'),
        array('field' => 'message_body', 'label' => 'Notification text', 'rules' => 'trim|required|max_length[240]'),
    );
	
	 public $validate_balance = array(
       array('field' => 'user_balance', 'label' => 'User Balance', 'rules' => 'required|integer'),       
    );

    public function get_all_device_id() {

        $this->db->select('d.device_id');
        $this->db->from('device d');
        $this->db->join('members m', 'm.id = d.id', 'left');
        $this->db->where(array('m.status' => 'active'));
          $this->db->where(array('d.device_id !=' => ''));
        $query = $this->db->get();
//        echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	public function get_members_device_token(){
		
		$query = $this->db->get("members_device_token");
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
		
	}
	
	public function get_low_balance_members_device_token(){
		
		$user_balance = $this->input->post('user_balance');
		$this->db->select("MDT.device, MDT.token");
		$this->db->from('members m');
		$this->db->join('members_device_token MDT','MDT.user_id=m.id','left');
		$this->db->where(array('m.balance <='=>$user_balance,'m.status'=>'active'));
		$this->db->where("MDT.device IS NOT NULL");
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
		
	}
}
