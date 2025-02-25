<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		// Check if User has logged in
		if (!$this->general->admin_logged_in())			
		{
			redirect(ADMIN_LOGIN_PATH, 'refresh');exit;
		}
			
		//load custom module
		$this->load->model('transaction_model');
		$this->load->library('pagination');
		
	}
	
	public function index()
	{
		
		$this->data = array();
		
		$config['base_url'] = site_url(ADMIN_DASHBOARD_PATH) . '/transaction-history/index';
        $config['total_rows'] = $this->transaction_model->count_transaction();
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
		
		$config['enable_query_strings'] = FALSE;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;	

        $config['uri_segment'] = '4';
        $offset = $this->input->get('per_page');
		
        $this->pagination->initialize($config);


        $this->data['result_data'] = $this->transaction_model->get_transaction($config['per_page'], $offset);
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Transaction History View | '.SITE_NAME)
			->build('a_view', $this->data);	

				
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */