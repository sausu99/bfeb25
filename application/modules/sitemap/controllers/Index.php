<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));exit;
		}
		
		if(SITE_STATUS == 'maintanance')
		{
			if(!$this->session->userdata('MAINTAINANCE_KEY') OR $this->session->userdata('MAINTAINANCE_KEY')!='YES'){
				redirect($this->general->lang_uri('/maintanance'));exit;
			}			
		}
		
		//load module
		$this->load->model('sitemap_module');
		$this->load->helper('url');
		
	}
	
	/**
	 * Generate a sitemap index file
	 * More information about sitemap indexes: http://www.sitemaps.org/protocol.html#index
	 */
	public function index() {
		
		//Add home page
		$this->sitemap_module->add($this->general->lang_uri(""), date('Y-m-d', time()), 'daily', 0.9);
		$this->sitemap_module->add($this->general->lang_uri('/users/register'), date('Y-m-d', time()), 'monthly', 0.9);
		$this->sitemap_module->add($this->general->lang_uri('/users/login'), date('Y-m-d', time()), 'monthly', 0.9);
		$this->sitemap_module->add($this->general->lang_uri('/users/login/forgot'), date('Y-m-d', time()), 'monthly', 0.9);
		$this->sitemap_module->add($this->general->lang_uri('/auctions/live'), date('Y-m-d', time()), 'daily', 0.9);
		$this->sitemap_module->add($this->general->lang_uri('/auctions/upcomming'), date('Y-m-d', time()), 'daily', 0.9);
		$this->sitemap_module->add($this->general->lang_uri('/auctions/winners'), date('Y-m-d', time()), 'daily', 0.9);
		$this->sitemap_module->add($this->general->lang_uri('/contact-us'), date('Y-m-d', time()), 'monthly', 0.9);
		$this->sitemap_module->add($this->general->lang_uri('/help/index'), date('Y-m-d', time()), 'monthly', 0.9);
		
		$get_all_auctionis =  $this->sitemap_module->get_all_live_auctions();
		if($get_all_auctionis){
			foreach($get_all_auctionis as $auc){
				$url = $this->general->lang_uri('/auctions/' . $this->general->clean_url($auc->name) . '-' . $auc->product_id);
				$this->sitemap_module->add($url, date('Y-m-d', time()), 'daily', 0.9);
			}
		}
		
		//Add all cms in sitemap
		$get_all_cms =  $this->general->get_all_cms_except(array('7','8','21','26','27','22')); 
		if($get_all_cms){
			foreach($get_all_cms as $cms){
				$url = $this->general->lang_uri("/page/".$cms->cms_slug);
				$this->sitemap_module->add($url, date('Y-m-d', time()), 'monthly', 0.9);
			}
		}
		
		
		
		//$this->sitemap_module->output('sitemapindex');
		$this->sitemap_module->output('urlset');
	}
	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */