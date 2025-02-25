<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sitemap_module extends CI_Model 
{
	/**
	 * Prepare the class variables for storing items and checking valid changefreq values
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->urls = array();
		$this->changefreqs = array(
			'always',
			'hourly',
			'daily',
			'weekly',
			'monthly',
			'yearly',
			'never'
		);
	}
	
	/**
	 * Add an item to the array of items for which the sitemap will be generated
	 * 
	 * @param string $loc URL of the page. This URL must begin with the protocol (such as http) and end with a trailing slash, if your web server requires it. This value must be less than 2,048 characters.
	 * @param string $lastmod The date of last modification of the file. This date should be in W3C Datetime format. This format allows you to omit the time portion, if desired, and use YYYY-MM-DD.
	 * @param string $changefreq How frequently the page is likely to change. This value provides general information to search engines and may not correlate exactly to how often they crawl the page.
	 * @param number $priority The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0. This value does not affect how your pages are compared to pages on other sites—it only lets the search engines know which pages you deem most important for the crawlers.
	 * @access public
	 * @return boolean
	 */
	public function add($loc, $lastmod = NULL, $changefreq = NULL, $priority = NULL) {
		// Do not continue if the changefreq value is not a valid value
		if ($changefreq !== NULL && !in_array($changefreq, $this->changefreqs)) {
			show_error('Unknown value for changefreq: '.$changefreq);
			return false;
		}
		// Do not continue if the priority value is not a valid number between 0 and 1 
		if ($priority !== NULL && ($priority < 0 || $priority > 1)) {
			show_error('Invalid value for priority: '.$priority);
			return false;
		}
		$item = new stdClass();
		$item->loc = $loc;
		$item->lastmod = $lastmod;
		$item->changefreq = $changefreq;
		$item->priority = $priority;
		$this->urls[] = $item;
		
		return true;
	}
	
	/**
	 * Generate the sitemap file and replace any output with the valid XML of the sitemap
	 * 
	 * @param string $type Type of sitemap to be generated. Use 'urlset' for a normal sitemap. Use 'sitemapindex' for a sitemap index file.
	 * @access public
	 * @return void
	 */
	public function output($type = 'urlset') {
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><'.$type.'/>');
		$xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		if ($type == 'urlset') {
			foreach ($this->urls as $url) {
				$child = $xml->addChild('url');
				$child->addChild('loc', strtolower($url->loc));
				if (isset($url->lastmod)) $child->addChild('lastmod', $url->lastmod);
				if (isset($url->changefreq)) $child->addChild('changefreq', $url->changefreq);
				if (isset($url->priority)) $child->addChild('priority', number_format($url->priority, 1));
			}
		} elseif ($type == 'sitemapindex') {
			foreach ($this->urls as $url) {
				$child = $xml->addChild('sitemap');
				$child->addChild('loc', strtolower($url->loc));
				if (isset($url->lastmod)) $child->addChild('lastmod', $url->lastmod);
			}
		}
		$this->output->set_content_type('application/xml')->set_output($xml->asXml());
	}
	
	/**
	 * Clear all items in the sitemap to be generated
	 * 
	 * @access public
	 * @return boolean
	 */
	public function clear() {
		$this->urls = array();
		return true;
	}
	
	public function get_all_live_auctions()
	{
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		$current_dt = $this->general->get_local_time('time');
		
		$this->db->select('a.id,a.product_id,ad.name');
		$this->db->from('auction a');
		$this->db->join('auction_details ad', 'ad.auc_id = a.id', 'right');
		
		$array = array('a.start_date <=' => $current_dt,'a.end_date >=' => $current_dt, 'ad.lang_id' => $lang_id, 'a.is_display' => 'Yes', 'a.status' => 'Live', 'a.auc_type' => 'lub');
			
		$this->db->where($array); 
		
		$this->db->group_by('a.id');
		$this->db->order_by("a.end_date asc"); 
		
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			$query->free_result();
			return $data;
		}
		
		return false;
	}
}
