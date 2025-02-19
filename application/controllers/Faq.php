<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends Frontend_Controller {

	function __construct() {
		parent::__construct();
	
		//$this->load->model('Ion_auth_model','ion_auth');
	}

	//*** Get recently booking ***//
	public function index()
	 {  
	   $this->listing();
	 }
	
	public function listing()
	 {  
		//echo $this->session->userdata('access'); die;
	   /* $totalcount = $this->user->select_row('st_faq','count(id) as total',array('status'=>'active'));
	   // echo '<pre>'; print_r($totalcount); die;
		 if(!empty($totalcount->total))
		   $total=$totalcount->total;
		 else $total=0;
		 
		 $limit = isset($_GET['limit'])?$_GET['limit']:PER_PAGE10;	//PER_PAGE10
		 $url = 'faq/listing';
		 $segment = 3;    
		 $page = mypaging($url,$total,$segment,$limit);*/
		                          //~ //$this->db->order_by('r.id','desc');
		 if(!empty($this->session->userdata('access')) && $this->session->userdata('access')=='marchant')
		 {                         
		$this->data['faqs']=$this->user->select('st_faq','id,question,answer,cat_id,(Select count(cat_id) FROM st_faq as fq WHERE fq.cat_id=st_faq.cat_id AND status ="active" GROUP BY fq.cat_id) as qus_count,(SELECT name FROM st_faq_category WHERE id = cat_id) as cat_name',array('status'=>'active'),'','cat_id','desc');  
		   //echo '<pre>'; print_r($this->data); die;
	   $this->load->view('frontend/marchant/faq',$this->data);
       }else{
		   redirect(base_url());
		   }
	 }
	

}
