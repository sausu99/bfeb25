<?php  if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Admin_seo extends CI_Model {
	public function __construct() 
	{
		parent::__construct();
		
		$this->image_name_path = '';
	}
	
	public $validate_seo =  array(
		array('field' => 'seo_page', 'label' => 'Page', 'rules' => 'required|callback_check_previous_page'),
		array('field' => 'page_title', 'label' => 'Page Title', 'rules' => 'required'),
		array('field' => 'meta_key', 'label' => 'Meta Key', 'rules' => 'required'),
		array('field' => 'meta_desc', 'label' => 'Meta Description', 'rules' => 'required')
		);

		public $validate_seo_edit =  array(	
		// array('field' => 'seo_page', 'label' => 'Page', 'rules' => 'required|callback_check_previous_page_edit'),
		array('field' => 'page_title', 'label' => 'Page Title', 'rules' => 'required'),
		array('field' => 'meta_key', 'label' => 'Meta Key', 'rules' => 'required'),
		array('field' => 'meta_desc', 'label' => 'Meta Description', 'rules' => 'required')
			
		);
	
	
			
	public function get_all_seos()
	{	
		$this->db->order_by('id','desc');		
		$query = $this->db->get('seo');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}
	
	//categories by lang id
	public function get_all_seo_by_lang($lang_id)
	{	
		
		$this->db->where('lang_id',$lang_id);
		$query = $this->db->get('seo');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}

         public function get_lang_details_for_others()
	{		
		$this->db->order_by("id", "ASC");
		$query = $this->db->get('language');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}
	
	public function get_all_seo_details($lang_id)
	{	
		$this->db->select('PC.*, L.lang_name');
		$this->db->from('seo PC');
		$this->db->join('language L', 'PC.lang_id = L.id', 'left');
		if($lang_id)
        $this->db->where('L.id',$lang_id);
			
		$query = $this->db->get();
		// echo $this->db->last_query();
		// die();

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}
	
	
	
	public function get_subcategories_by_parent_id($lang_id)
	{	
		$this->db->select('PC.*, L.lang_name, L.id as lang_id');
		$this->db->from('seo PC');
		$this->db->join('language L', 'PC.lang_id = L.id', 'left');
		$this->db->where('PC.parent_id !=',0);
                if($lang_id)
                $this->db->where('L.id',$lang_id);
		$this->db->order_by("PC.parent_id, PC.order_by", "asc"); 		
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 
		return false;
	}
	
	
	public function get_category_by_id($id)
	{		
		$query = $this->db->get_where('seo',array("id"=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}

	public function get_seo_by_parent_id($prntid=false,$lang_id=false)
	 {
	 	$this->db->select('seo.*');
	 	$this->db->from('seo seo');
	 	// $this->db->join('language l',)
	 	$this->db->where(array('seo.parent_id'=>$prntid,'seo.lang_id'=>$lang_id));
	 	$query =$this->db->get();
		// $query = $this->db->get_where('seo pc',array("pc.parent_id"=>$id));

		if ($query->num_rows() > 0)
		{
		   return $query->row();
		} 

		return false;
	}
	
	public function get_page_id_by_parent_id($parent_id=false)
	{
		$this->db->select('seo.seo_pages_id');
	 	$this->db->from('seo seo');
	 	// $this->db->join('language l',)
	 	$this->db->where(array('seo.parent_id'=>$parent_id));
	 	$query =$this->db->get();
		// $query = $this->db->get_where('seo pc',array("pc.parent_id"=>$id));

		if ($query->num_rows() > 0)
		{
		   $result= $query->row();
		   return $result->seo_pages_id;
		} 

		return false;
	}
	
	
	public function insert_seo()
	{
			$get_parentid=$this->get_parentid_from_page($this->input->post('seo_page'));
			// echo $get_parentid;
			// exit;
			$data = array(
               'lang_id' => $this->input->post('lang', TRUE),
               'seo_pages_id'=>$this->input->post('seo_page'),
               'page_title' => $this->input->post('page_title', TRUE),               
			   'meta_key' => $this->input->post('meta_key'),
			   'meta_description' => $this->input->post('meta_desc', TRUE),
			   'created_date' => $this->general->get_gmt_time('time'),
			   'last_update'=> $this->general->get_gmt_time('time')
            );

				//$data['parent_id'] = 'LAST_INSERT_ID()';
				$this->db->insert('seo', $data); 
				
				//update parent id
				$id = $this->db->insert_id();
				if($get_parentid)
				{
					$prntid=$get_parentid;
				}
				else
				{
					$prntid=$id;
				}

				$this->db->where('id', $id);
				$this->db->update('seo', array('parent_id'=>$prntid)); 
				return $prntid;	
		
	}

	public function get_last_parent_id()
	{
		$this->db->select_max('parent_id');
		$query = $this->db->get('seo');
		// echo $this->db->last_query();
		// die();
		return $query->row();
	}
	

	public function update_seo()
	{
		$data = array(
               'lang_id' => $this->input->post('lang', TRUE),
               'seo_pages_id'=>$this->input->post('seo_page'),
               'page_title' => $this->input->post('page_title', TRUE),               
			   'meta_key' => $this->input->post('meta_key'),
			   'meta_description' => $this->input->post('meta_desc', TRUE),
			   'last_update'=> $this->general->get_gmt_time('time')
            );
		
		$id = $this->uri->segment(4);
		$lang_id = $this->input->post('lang');
		
		//check this data is exist or not, if it is not exist then insert into table otherwise update it
		$query = $this->db->get_where('seo',array("parent_id"=>$id,"lang_id"=>$lang_id));
		
		if ($query->num_rows() > 0)
		{
			$this->db->where('parent_id', $id);
			$this->db->where('lang_id', $lang_id);
			$this->db->update('seo', $data); 
		}
		else
			{
				$data['parent_id'] = $id;
				$data['created_date']=$this->general->get_gmt_time('time');
				$this->db->insert('seo', $data); 
			}
	}

public function get_all_seo_pages()
	{		
		$this->db->order_by("id", "ASC");
		$query = $this->db->get('seo_pages');

		if ($query->num_rows() > 0)
		{
		   return $query->result();
		} 

		return false;
	}


	public function get_parentid_from_page($page_id=false)
	{		
		$this->db->select('seo.parent_id');
	 	$this->db->from('seo seo');
	 	// $this->db->join('language l',)
	 	$this->db->where(array('seo.seo_pages_id'=>$page_id));
	 	$query =$this->db->get();
		// $query = $this->db->get_where('seo pc',array("pc.parent_id"=>$id));

		if ($query->num_rows() > 0)
		{
		   $result= $query->row();
		   return $result->parent_id;
		} 

		return false;
	}


	public function chekpreviousseo($page_id=false,$prnt_id=false)
	{
		// echo $test;
		// exit;
		$this->db->select('seo_pages_id');
		$this->db->from("seo");
		$this->db->where('seo_pages_id',$page_id);
		if($prnt_id)
		{
			$this->db->where('parent_id!=',$prnt_id);
		}
		$query = $this->db->get();
		// echo $this->db->last_query();
		// exit;
		if ($query->num_rows() > 0)
		{
		   return $query->num_rows();
		} 
		return false;
	}


	public function check_exit_seo_page($page_id,$lang_id)
	{
		$data = array();
				// $query = $this->db->get_where("members",array('email'=>$email,'id!='=>$id,'email!='=>''));
		$query = $this->db->get_where("seo",array('seo_pages_id'=>$page_id,'lang_id'=>$lang_id));
		// echo $this->db->last_query();
		// exit;
		if ($query->num_rows() > 0) 
		{
			$data=$query->row();	
			return $data;			
		}
		return false;
	}

	
	
}
