<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_ajax extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));exit;
		}
		
		if(!$this->session->userdata(SESSION.'user_id'))
         {
          	redirect($this->general->lang_uri(''),'refresh');exit;
         }
		 
		 //check banned IP address
		$this->general->check_banned_ip();
		
	}
	
	public function index()
	{
		if(is_ajax())
	    {
			$voucher_code = $this->input->post('voucher_code');
			
			$query = $this->db->get_where("vouchers",array("code"=>$voucher_code));
			
			if ($query->num_rows() > 0) 
			{
				$data=$query->row();				
				
				$id = $data->id;
				$limit_number = $data->limit_number;
				$limit_per_user = $data->limit_per_user;
				$extra_bids = $data->extra_bids;
				$start_date = $data->start_date;
				$end_date = $data->end_date;
				$current_date = $this->general->get_local_time('none');
				
				//Check date range
				if($extra_bids==0)
				{echo lang('voucher_wrong');exit;}
				else if($start_date <= $current_date && $end_date >= $current_date)
				{
					//get total voucher used
					$query_txn_voucher = $this->db->get_where("transaction",array("voucher_id"=>$id,"transaction_status"=>"Completed",'transaction_type'=>'voucher'));
					
					//Get total boucher used by this user
					$query_user_voucher = $this->db->get_where("transaction",array("voucher_id"=>$id,"transaction_status"=>"Completed","user_id"=>$this->session->userdata(SESSION.'user_id'),'transaction_type'=>'voucher'));
					
					if(($query_txn_voucher->num_rows() < $limit_number || $limit_number == 0) && ($query_user_voucher->num_rows() < $limit_per_user || $limit_per_user ==0))
					{
						echo str_replace('@@extra_bids@@',$extra_bids,lang('voucher_get_extra_bids'));exit;
					}
					else
					{
						echo lang('voucher_limit_exceed');exit;
					}
				}
				else
				{
					echo lang('voucher_expired');exit;
				}
	
			}
			
			else
			{
				echo lang('voucher_wrong');exit;
			}
		}
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */