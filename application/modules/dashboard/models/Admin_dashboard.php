<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_dashboard extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
	
	public function total_notship_auctions()
	{
		$this->db->select('auc.*,auc_det.name,aw.id AS auction_winner_id, aw.user_id,aw.won_amt,aw.auction_close_date,aw.payment_status,aw.shipping_status,aw.invoice_id,m.lang_id');
		$this->db->from('auction_winner aw');
		$this->db->join('auction auc', 'auc.product_id = aw.auc_id', 'left');
		$this->db->join('auction_details auc_det', 'auc_det.auc_id = auc.id', 'left');	
		$this->db->join('members m', 'm.id = aw.user_id', 'left');		
		$this->db->where('aw.shipping_status',1);
		$this->db->where('aw.payment_status','Completed');
		$this->db->where('auc.product_id IS NOT NULL');
		$this->db->group_by('auc.id');
		$this->db->order_by("aw.auction_close_date", "desc");
		$query = $this->db->get();

		return $query->num_rows();
	}
	public function total_notship_order_auctions()
	{
		$this->db->select('auc.*,auc_det.name,t.user_id,t.amount,t.transaction_date,t.transaction_status,t.invoice_id');
		$this->db->from('transaction t');
		$this->db->join('auction auc', 'auc.product_id = t.auc_id', 'left');
		$this->db->join('auction_details auc_det', 'auc_det.auc_id = auc.id', 'left');		
		$this->db->join('auction_winner_address awa', 'awa.invoice_id = t.invoice_id', 'left');
		$this->db->where('t.transaction_status','Completed');
		$this->db->where('t.transaction_type','buy_auction');
		$this->db->where('awa.buy_auc_shipping_status',1);
		//$this->db->group_by('auc.id');
		$this->db->order_by("t.transaction_date", "desc");
		$query = $this->db->get();

		return $query->num_rows();
	}
	public function total_pending_testimonials()
	{
		$query = $query = $this->db->get_where('testimonial',array('status'=>'Pending'));
		return $query->num_rows();
	}
	public function file_settings_do_upload_ajax($file, $location, $encrypt_filename='')
 	{
		$config['upload_path'] = './'.$location;   //file upload location
		$config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
		$config['remove_spaces'] = TRUE;  
		$config['max_size'] = '4200000';
		//$config['max_width'] = '3000';
		//$config['max_height'] = '3000';
		if($encrypt_filename='encrypt')
		{
			//$config['file_name'] = $new_file_name;
			$config['encrypt_name'] = TRUE;
		}
		$this->upload->initialize($config);
		//print_r($_FILES);
		
		$this->upload->do_upload($file);
		if($this->upload->display_errors())
		{
			$this->error_img = $this->upload->display_errors();
			//echo $this->error_img;
			return false;
		}
		else
		{
			$data = $this->upload->data();
			return $data;
		}
	}
	
}
