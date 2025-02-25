<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_payment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public $validate_paypal = array(
        array('field' => 'email', 'label' => 'PayPal Email', 'rules' => 'required'),
        array('field' => 'is_display', 'label' => 'Is Display', 'rules' => 'required')
    );
    public $validate_instamojo = array(
        array('field' => 'api_key', 'label' => 'API Key', 'rules' => 'required'),
        array('field' => 'auth_token', 'label' => 'Auth Token Key', 'rules' => 'required'),
        array('field' => 'is_display', 'label' => 'Is Display', 'rules' => 'required')
    );
    public $validate_ccavenue = array(
        array('field' => 'is_display', 'label' => 'Display', 'rules' => 'required'),
        array('field' => 'merchant_id', 'label' => 'Merchant id', 'rules' => 'required'),
        array('field' => 'access_code', 'label' => 'Access code', 'rules' => 'required'),
        array('field' => 'working_key', 'label' => 'Working key', 'rules' => 'required')
    );
    public $validate_paytm = array(
        array('field' => 'is_display', 'label' => 'Display', 'rules' => 'required'),
        array('field' => 'merchant_key', 'label' => 'Merchant Key', 'rules' => 'required'),
        array('field' => 'merchant_id', 'label' => 'Merchant Id', 'rules' => 'required'),
        array('field' => 'merchant_website', 'label' => 'Merchant website', 'rules' => 'required')
    );

    public function file_settings_do_upload() {
        $config['upload_path'] = './' . PAYMENT_API_LOGO_PATH; //define in constants
        $config['allowed_types'] = 'gif|jpg|png';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = '100';
        $config['max_width'] = '350';
        $config['max_height'] = '100';

// load upload library and set config				
        if (isset($_FILES['logo']['tmp_name'])) {
            $this->upload->initialize($config);
            $this->upload->do_upload('logo');
        }
    }

    public function get_payment_gateway_info($id) {
        $query = $this->db->get_where('payment_gateway', array("id" => $id));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return false;
    }

    public function update_paypal_settings($logo_full_path) {
        $data = array(
            'email' => $this->input->post('email', TRUE),
            'status' => $this->input->post('status', TRUE),
            'is_display' => $this->input->post('is_display', TRUE),
            'last_update' => $this->general->get_local_time('time')
        );

//only if new flag is uploaded
        if (isset($logo_full_path) && $logo_full_path != "") {
            @unlink('./' . $this->input->post('logo_old'));
            $data['payment_logo'] = $logo_full_path;
        }

        $this->db->where('id', 1);
        $this->db->update('payment_gateway', $data);
    }

    public function update_instamojo_settings($logo_full_path) {
        $data = array(
            'api_key' => $this->input->post('api_key', TRUE),
            'secret_token' => $this->input->post('auth_token', TRUE),
            'status' => $this->input->post('status', TRUE),
            'is_display' => $this->input->post('is_display', TRUE),
            'last_update' => $this->general->get_local_time('time')
        );

//only if new flag is uploaded
        if (isset($logo_full_path) && $logo_full_path != "") {
            @unlink('./' . $this->input->post('logo_old'));
            $data['payment_logo'] = $logo_full_path;
        }

        $this->db->where('id', 2);
        $this->db->update('payment_gateway', $data);
    }

    public function update_ccavenue_settings($logo_full_path) {
        $data = array(
            'api_key' => $this->input->post('api_key', TRUE),
            'secret_token' => $this->input->post('auth_token', TRUE),
            'status' => $this->input->post('status', TRUE),
            'is_display' => $this->input->post('is_display', TRUE),
            'last_update' => $this->general->get_local_time('time'),
            'merchant_id' => $this->input->post('merchant_id', true),
            'access_code' => $this->input->post('access_code', true),
            'working_key' => $this->input->post('working_key', true)
        );

            //only if new flag is uploaded
        if (isset($logo_full_path) && $logo_full_path != "") {
            @unlink('./' . $this->input->post('logo_old'));
            $data['payment_logo'] = $logo_full_path;
        }

        $this->db->where('id', 3);
        $this->db->update('payment_gateway', $data);
    }
    public function update_paytm_settings($logo_full_path){
         $data = array(
            'merchant_key' => $this->input->post('merchant_key'),
            'merchant_id' => $this->input->post('merchant_id'),
            'status' => $this->input->post('status'),
            'is_display' => $this->input->post('is_display'),
            'last_update' => $this->general->get_local_time('time'),
            'merchant_website' => $this->input->post('merchant_website')
        );

            //only if new flag is uploaded
        if (isset($logo_full_path) && $logo_full_path != "") {
            @unlink('./' . $this->input->post('logo_old'));
            $data['payment_logo'] = $logo_full_path;
        }

        $this->db->where('id', 4);
        $this->db->update('payment_gateway', $data);
        
    }

}
