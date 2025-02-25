<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_country extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public $validate_settings = array(
        array('field' => 'language', 'label' => 'Language', 'rules' => 'required'),
        array('field' => 'country_timezone', 'label' => 'Country Timezone', 'rules' => 'required'),
        //array('field' => 'flag', 'label' => 'Flag', 'rules' => 'required'),
        array('field' => 'country', 'label' => 'Country Name', 'rules' => 'required|max_length[50]|is_unique[country.country]'),
        array('field' => 'short_code', 'label' => 'Country Short Code', 'rules' => 'required|max_length[5]|is_unique[country.short_code]'),
        array('field' => 'currency_code', 'label' => 'Currency Code', 'rules' => 'required|max_length[10]'),
        array('field' => 'currency_sign', 'label' => 'Currency Sign', 'rules' => 'required|max_length[10]'),
        array('field' => 'exchange_rate', 'label' => 'Exchange Rate', 'rules' => 'required'),
        array('field' => 'currency_display_in', 'label' => 'Currency Display Style', 'rules' => 'required'),
        array('field' => 'default_country', 'label' => 'Default Country', 'rules' => 'required'),
        array('field' => 'is_display', 'label' => 'Is Display', 'rules' => 'required'),
        array('field' => 'country_code', 'label' => 'Country code', 'rules' => 'required'),
    );
    public $validate_settings_edit = array(
        array('field' => 'language', 'label' => 'Language', 'rules' => 'required'),
        array('field' => 'country_timezone', 'label' => 'Country Timezone', 'rules' => 'required'),
        //array('field' => 'flag', 'label' => 'Flag', 'rules' => 'required'),
        array('field' => 'country', 'label' => 'Country Name', 'rules' => 'required|max_length[50]|callback_country_name'),
        array('field' => 'short_code', 'label' => 'Country Short Code', 'rules' => 'required|max_length[5]|callback_country_short_code'),
        array('field' => 'currency_code', 'label' => 'Currency Code', 'rules' => 'required|max_length[10]'),
        array('field' => 'currency_sign', 'label' => 'Currency Sign', 'rules' => 'required|max_length[10]'),
        array('field' => 'exchange_rate', 'label' => 'Exchange Rate', 'rules' => 'required'),
        array('field' => 'currency_display_in', 'label' => 'Currency Display Style', 'rules' => 'required'),
        array('field' => 'default_country', 'label' => 'Default Country', 'rules' => 'required'),
        array('field' => 'is_display', 'label' => 'Is Display', 'rules' => 'required'),
        array('field' => 'country_code', 'label' => 'Country code', 'rules' => 'required'),
    );

    public function file_settings_do_upload() {
        $config['upload_path'] = './' . FLAG_PATH; //define in constants
        $config['allowed_types'] = 'gif|jpg|png';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = '100';
        $config['max_width'] = '16';
        $config['max_height'] = '11';

        // load upload library and set config				
        if (isset($_FILES['flag']['tmp_name'])) {
            $this->upload->initialize($config);
            $this->upload->do_upload('flag');
        }
    }

    public function get_country_list() {
        //$query = $this->db->get('help');
        $this->db->select('c.*, l.lang_name');
        $this->db->from('country c');
        $this->db->join('language l', 'l.id = c.lang_id', 'left');
        //$this->db->where('1=1');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

    public function get_country_byid($id) {
        $query = $this->db->get_where('country', array('id' => $id));

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function get_all_country_list() {
        $query = $this->db->get_where('country', array('is_display' => 'Yes'));

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

    public function insert_record($flag_full_path) {
        //check defualt country is yes, if yes then make other country as defoult to no
        if ($this->input->post('default_country', TRUE) == 'Yes') {
            $this->db->update('country', array('default_country' => 'No'));
        }


        //set auction details info
        $array_data = array(
            'lang_id' => $this->input->post('language', TRUE),
            'country' => $this->input->post('country', TRUE),
            'country_timezone' => $this->input->post('country_timezone', TRUE),
            'short_code' => $this->input->post('short_code', TRUE),
            'currency_code' => $this->input->post('currency_code', TRUE),
            'currency_sign' => $this->input->post('currency_sign', TRUE),
            'exchange_rate' => $this->input->post('exchange_rate', TRUE),
            'currency_display_in' => $this->input->post('currency_display_in', TRUE),
            'default_country' => $this->input->post('default_country', TRUE),
            'is_display' => $this->input->post('is_display', TRUE),
            'last_update' => $this->general->get_gmt_time('time'),
            'country_code' => $this->input->post('country_code')
        );

        //only if new flag is uploaded
        if (isset($flag_full_path) && $flag_full_path != "") {
            $array_data['country_flag'] = $flag_full_path;
        }


        $this->db->insert('country', $array_data);
    }

    public function update_record($id, $flag_full_path) {
        //set auction details info
        $array_data = array(
            'lang_id' => $this->input->post('language', TRUE),
            'country' => $this->input->post('country', TRUE),
            'country_timezone' => $this->input->post('country_timezone', TRUE),
            'short_code' => $this->input->post('short_code', TRUE),
            'currency_code' => $this->input->post('currency_code', TRUE),
            'currency_sign' => $this->input->post('currency_sign', TRUE),
            'exchange_rate' => $this->input->post('exchange_rate', TRUE),
            'currency_display_in' => $this->input->post('currency_display_in', TRUE),
            'default_country' => $this->input->post('default_country', TRUE),
            'is_display' => $this->input->post('is_display', TRUE),
            'last_update' => $this->general->get_gmt_time('time'),
            'country_code' => $this->input->post('country_code')
        );
        //only if new flag is uploaded
        if (isset($flag_full_path) && $flag_full_path != "") {
            @unlink('./' . $this->input->post('flag_old'));
            $array_data['country_flag'] = $flag_full_path;
        }

        //check defualt country is yes, if yes then make other country as defoult to no
        if ($this->input->post('default_country', TRUE) == 'Yes') {
            $this->db->update('country', array('default_country' => 'No'));
        }

        $this->db->where('id', $id);
        $this->db->update('country', $array_data);
    }

    public function get_help_category() {
        $query = $this->db->get('help_category');

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

}
