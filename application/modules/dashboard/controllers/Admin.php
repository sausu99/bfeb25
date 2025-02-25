<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		// Check if User has logged in
		if (!$this->general->admin_logged_in())			
		{
			redirect(ADMIN_LOGIN_PATH, 'refresh');exit;
		}
		
		$this->load->model('admin_dashboard');	
	}
	
	public function index()
	{
		$this->data['total_notship_auc'] = $this->admin_dashboard->total_notship_auctions();
		$this->data['total_notship_ord_auc'] = $this->admin_dashboard->total_notship_order_auctions();
		$this->data['total_pending_testimonial'] = $this->admin_dashboard->total_pending_testimonials();
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Dashboard | '.SITE_NAME)
			->build('dashboard_body', $this->data);	
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */