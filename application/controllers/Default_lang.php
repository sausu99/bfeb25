

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Default_lang extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $default_language = $this->get_default_language();
      

        $this->data['language_info'] = ($default_language) ? $default_language :'en';

        $this->data['status'] = true;

        $this->output->set_output(json_encode($this->data))->_display();

        exit;
    }
    public function get_default_language() {
        $this->db->select('*');
        $this->db->from('language');
        $this->db->where('default_lang', 'Yes');
        $query = $this->db->get();
        // echo $this->db->last_query();
        // exit;
        if ($query->num_rows() > 0) {
            return $query->row()->short_code;
        }
        return false;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */