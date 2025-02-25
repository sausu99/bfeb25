<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Referer extends CI_Controller {
 
    function __construct()
    {
        parent::__construct();   
		//load custom library
		$this->load->library('my_language');
		$this->load->helper('cookie');   
    }
 
    function index($foo='')
    {
		//check blank value
		if($foo=='')
		{
			redirect(site_url(), 'refresh');exit;
		}
		
		if(get_cookie('refererauktis')=='')
		{
			$cookie1 = array('name' => "refererauktis",'value'  => strip_tags($foo),'expire' => time()+3600*24*7);
			$this->input->set_cookie($cookie1);
		}
		//echo get_cookie('refererauktis');
		$url = str_replace("@variable@",$foo,"?utm_source=@variable@&utm_medium=@variable@&utm_campaign=@variable@");
		
		redirect($this->general->lang_uri($url), 'refresh');exit;
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */