<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Servertimer extends CI_Controller {

    function __construct()
	{
        parent::__construct();		
    }

    public function index()
	{
       //echo $this->get_local_time_clock();exit;
	   if(is_ajax())
	   {
		echo $this->general->get_local_time_clock();
	   }
	}
	


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
