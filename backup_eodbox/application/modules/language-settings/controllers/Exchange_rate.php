<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exchange_rate extends CI_Controller {

	function __construct() {
		parent::__construct();
					
		//load custom module
			$this->load->model('admin_language_settings');
		
	}
	
	public function index()
	{
		//All currency exchange format
		$exchangearray = file('http://quote.yahoo.com/d/quotes.csv?s=EUREUR=X+EURUSD=X+EURGBP=X+EURHRK=X+EURSEK=X+EURNOK=X&f=nl1d1t1');

		
		foreach($exchangearray as $key=>$value)
		{
			//$txt = '"EUR to USD",1.3562,"1/31/2013","4:58am"';
			list($currencyfromto, $rate, $date, $time) = explode(",", $value);
			$changeto = substr(trim($currencyfromto,'"'),-3);
			list($month, $day, $year) = explode('/', trim($date,'"'));
			list($hour, $min) = explode(':', trim($time,'"'));
			$datetime = "$year-$month-$day $hour:".str_replace('am','',$min).':00';	
			$this->admin_language_settings->update_exchange_rate_from_yahoo_api($rate,$datetime,$changeto);
		}	
		
	}
		
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */