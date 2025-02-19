<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seo extends Frontend_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct(){
		parent::__construct();
	
	}
	//*** public function  ***//	
public function sitemap_dynamic()
	{ 
		$otherUrl =array('merchant/registration',
		                  'rechner',
		                  'dsgvo',
		                  'imprint',
		                  'about',
		                  'contact');
		                  
		 $this->data['otherUrl'] =$otherUrl;                 
		$this->data['userdata'] = $this->user->select('st_users','id,slug,city',array('status'=>'active','access'=>'marchant'));
		
		 $cat=$this->db->query("SELECT id,category_name FROM st_category WHERE status='active' AND parent_id='0' ORDER BY show_order asc");
         $this->data['category'] = $cat->result(); 
         
		  header("Content-Type: text/xml;charset=iso-8859-1");
		$this->load->view('frontend/sitemap',$this->data);
	}
	
}
