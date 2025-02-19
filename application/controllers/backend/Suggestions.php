<?php
class Suggestions extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_suggestions'; 
	
	function __construct() {
		parent::__construct();
		$this->db->cache_off();
		$this->data['segment1'] = $this->uri->segment(2);
		$this->data['segment2'] = $this->uri->segment(3);
		$this->data['segment3'] = $this->uri->segment(4);
		if(empty($this->session->userdata('st_userid')) && $this->session->userdata('access')!='admin'){
		redirect(base_url('backend'));
		}
		// Redirect if user is not logged in as admin
		$this->is_not_logged_in_as_admin_redirect();
        
	}
	
	
	function index(){  
		
		$this->listing();
	}
	
	/***
	 *  List all user according to his role
	 ***/
	function listing(){ 
		/*if(isset($_GET['role']) && $_GET['role']!=''){
			$role=$_GET['role'];
			}
	    else{
            $role='marchant';
			}*/		
		
		$this->data['suggetion'] =  $this->user->join_two_without_limit('st_suggestions','st_users','user_id','id',['st_suggestions.status!='=>'deleted'],'st_suggestions.*,st_users.business_name');
		//$this->data['booking'] = $this->user->getbookinglistadmin();
		  // echo "<pre>"; print_r($this->data);die;
		   
		admin_views("/suggetion/listing",$this->data);
		
	}


}
