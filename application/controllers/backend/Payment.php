<?php
class Payment extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_payments'; 
	
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
		
		if(isset($_GET['start_date']) && !empty($_GET['start_date'])){
			$date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
			$st_date= $date->format('Y-m-d');
			//$st_date= date("Y-m-d", strtotime($_GET['start_date']));
		}
		else
			$st_date='';

		if(isset($_GET['end_date']) && !empty($_GET['end_date'])){
			$date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
			$ed_date= $date1->format('Y-m-d');
			//$ed_date= date("Y-m-d", strtotime($_GET['end_date']));
		}
		else
			$ed_date='';

		//die;

		$this->data['payment'] = $this->user->getpaymentlistadmin($st_date , $ed_date);
		//$this->data['booking'] = $this->user->select($this->table,'*');
		admin_views("/payment/listing",$this->data);
		
	}


}
