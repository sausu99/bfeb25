<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_block_ip extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();	
	}
		
	public function file_settings_do_upload()
	{
				$config['upload_path'] = './'.FLAG_PATH;//define in constants
				$config['allowed_types'] = 'gif|jpg|png';
				$config['remove_spaces'] = TRUE;		
				$config['max_size'] = '100';
				$config['max_width'] = '16';
				$config['max_height'] = '11';

				// load upload library and set config				
				if(isset($_FILES['flag']['tmp_name']))
				{
					$this->upload->initialize($config);
					$this->upload->do_upload('flag');
				}		
	}
	public function get_ip_details()
	{		
				 $this->db->order_by("last_update", "desc"); 
		$query = $this->db->get('block_ips');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}	
	
	public function get_default_lang_id()
	{		
		
		$query = $this->db->get_where('block_ips',array("default_lang" => "Yes"));

		if($query->num_rows() > 0)
		{
		  $row = $query->row(); 
		  return $row->id;
		} 

		return false;
	}
	
	public function get_IP_by_id($id)
	{
		$query = $this->db->get_where('block_ips',array('id'=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	public function insert_ip_record()
	{
		$data = array(
               'ip_address' => $this->input->post('ip_address', TRUE),
			   'message' => $this->input->post('message'),
			   'last_update' => $this->general->get_local_time('time')
            );

		$this->db->insert('block_ips', $data); 

	}
	
	public function update_ip_record($id)
	{
		$data = array(
               'ip_address' => $this->input->post('ip_address', TRUE),
			    'message' => $this->input->post('message'),
			   'last_update' => $this->general->get_local_time('time')
            );
			
		$this->db->where('id', $id);
		//echo $this->db->last_query();
		$this->db->update('block_ips', $data);

	}
	
	
	

}
