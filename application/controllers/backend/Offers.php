<?php
class Offers extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_category'; 
	
	function __construct() {
		parent::__construct();
		$this->db->cache_off();
		$this->data['segment1'] = $this->uri->segment(2);
		$this->data['segment2'] = $this->uri->segment(3);
		// Redirect if user is not logged in as admin
		$this->is_not_logged_in_as_admin_redirect();
		if(empty($this->session->userdata('st_userid')) && $this->session->userdata('access')!='admin'){
		redirect(base_url('backend'));
		}
        
	}
	
	
	function index(){  
		
	   $this->listing();
	}
	
	/***
	 *  List all user according to his role
	 ***/
	function listing(){ 
			
		
		$this->data['category'] = $this->user->select($this->table,'id,category_name,parent_id,status,offer_text',['status!='=>'deleted','parent_id' => '0']);
		
		 // echo $this->db->last_query(); die();
		  // echo "<pre>"; print_r($this->data);die;
		   
		admin_views("/offer/listing",$this->data);
		
	}
	function description($id=''){

		 $this->form_validation->set_rules('description', 'Offer text', 'required');

			 if ($this->form_validation->run() === TRUE)
			 {   
			 	 $update = $this->user->update('st_category',array('offer_text'=>$_POST['description']),['id'=>$id]);
			 	 if($update)   
				    $this->session->set_flashdata('message', 'Offer text updated Successfully.'); 
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing');
			 }
			 else{
			 	 $this->data['message'] = validation_errors();
			 }
		$this->data['category'] = $this->user->select_row($this->table,'id,category_name,offer_text',['id' => $id,'parent_id'=>0]);
			admin_views("/offer/input",$this->data);

	}


}
?>
