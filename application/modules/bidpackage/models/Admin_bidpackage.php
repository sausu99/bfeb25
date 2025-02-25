<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class admin_bidpackage extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public $validate_settings = array(
        array('field' => 'name', 'label' => 'Bid Package Name ', 'rules' => 'required'),
        array('field' => 'amount', 'label' => 'Amount', 'rules' => 'required'),
        array('field' => 'credits', 'label' => 'Bids', 'rules' => 'required|integer')
    );

    public function get_bid_package() {
        $this->db->order_by("amount", "asc");
        $query = $this->db->get('bidpackage');

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

    public function get_bidpackage_by_id($id) {
        $query = $this->db->get_where('bidpackage', array('id' => $id));

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return false;
    }

    public function insert_bidpackage_record() {
        $data = array(
            'name' => $this->input->post('name'),
            'amount' => $this->input->post('amount', TRUE),
            'credits' => $this->input->post('credits', TRUE),
            'best_tag' => $this->input->post('best_tag', TRUE),
            'bonus_points' => $this->input->post('bonus_points', TRUE),
            'last_update' => $this->general->get_local_time('time')
        );

        $this->db->insert('bidpackage', $data);
    }

    public function update_bidpackage_record($id) {
        $data = array(
            'name' => $this->input->post('name'),
            'amount' => $this->input->post('amount', TRUE),
            'credits' => $this->input->post('credits', TRUE),
            'best_tag' => $this->input->post('best_tag', TRUE),
            'bonus_points' => $this->input->post('bonus_points', TRUE),
            'last_update' => $this->general->get_local_time('time')
        );

        $this->db->where('id', $id);
        //echo $this->db->last_query();
        $this->db->update('bidpackage', $data);
    }

}
