<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class My_language {
	
	protected $ci;
		
	public function __construct() {
		
			$this->ci =& get_instance();			

			//get country short code from URL
			$lang_short_code = $this->ci->uri->segment(1);
			
			//check short code and set it in to the configure otherwise redirect to the default country
			if(!isset($lang_short_code) || strlen($lang_short_code)!=2 || $this->ci->general->check_country_short_code($lang_short_code)!=1)
			{		
				redirect(site_url($this->default_country()),'refresh');exit;
			}
			
			//Redirect login user to their respective country.
			if($this->ci->session->userdata(SESSION.'short_code') && $this->ci->session->userdata(SESSION.'short_code') != $lang_short_code)
			{
				redirect($this->ci->general->lang_switch_uri($this->ci->session->userdata(SESSION.'short_code')),'refresh');exit;
			}
											
			//set current lanugae id in the configure file
			$country_info = $this->ci->general->get_country_bycode($lang_short_code);
			//print_r($country_info);exit;
			$this->ci->config->set_item('current_language_id', $country_info->lang_id);
			$this->ci->config->set_item('language', strtolower($country_info->lang_name));
			$this->ci->config->set_item('lang', $lang_short_code);
			
			//Set Language information
			define('LANG_ID',$country_info->lang_id);
			define('COUNTRY_ID',$country_info->id);			
			define('LANG_CURRENCY_CODE',$country_info->currency_code);
			define('LANG_CURRENCY_SIGN',$country_info->currency_sign);
			define('LANG_EXCHANGE_RATE',$country_info->exchange_rate);
			define('LANG_DISPLAY_IN',$country_info->currency_display_in);
			define('LANG_SHORT_CODE',$lang_short_code);
						
			 //load language file
			$this->ci->lang->load('general');
	}

	// default language: first element of $this->languages
	function default_country()
	{
		$default_country = $this->ci->general->get_default_country();
		return $default_country->short_code;
	}	

}