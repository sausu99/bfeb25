<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contact_us extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('my_language');
        if (SITE_STATUS == 'offline') {
            redirect(site_url('/offline'));
            exit;
        }

        if (SITE_STATUS == 'maintanance') {
            if (!$this->session->userdata('MAINTAINANCE_KEY') OR $this->session->userdata('MAINTAINANCE_KEY') != 'YES') {
                redirect($this->general->lang_uri('/maintanance'));
                exit;
            }
        }

        //check banned IP address
        $this->general->check_banned_ip();

        $this->load->helper('text');
        $this->load->model('contact_us_model');
        $this->load->library('form_validation');
       
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

         $this->load->library('Netcoreemail_class');
    }

    public function index() {
        $this->session->unset_userdata('keyword');
        $this->session->unset_userdata('category');
        $this->session->unset_userdata('subcategory');

        $this->data['active_menu'] = 'contact_us';
       
        $this->form_validation->set_rules($this->contact_us_model->validate_contact_us);

        if ($this->form_validation->run() == TRUE) {
            $this->contact_us_model->send_email();

            $this->session->set_flashdata('message', lang('thank_u_message_contact_us'));

            redirect($this->general->lang_uri('/contact-us'), 'refresh');
        }

        $seo_data = $this->general->get_seo(LANG_ID, 11);
        if ($seo_data) {
            $this->data['page_title'] = $seo_data->page_title;
            $this->data['meta_keys'] = $seo_data->meta_key;
            $this->data['meta_desc'] = $seo_data->meta_description;
        } else {
            $this->data['page_title'] = SITE_NAME;
            $this->data['meta_keys'] = SITE_NAME;
            $this->data['meta_desc'] = SITE_NAME;
        }


        $this->template
                ->set_layout('body_full')
                ->enable_parser(FALSE)
                ->title($this->data['page_title'])
                ->build('contact_us_view', $this->data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */