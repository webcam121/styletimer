<?php
class Filter_category extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_filter_category'; 
	
	function __construct() {
		parent::__construct();
		$this->db->cache_off();
		$this->data['segment1'] = $this->uri->segment(2);
		$this->data['segment2'] = $this->uri->segment(3);
		
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
			
		
		$this->data['category'] = $this->user->select($this->table,'id,category_name,status',['status!='=>'deleted']);
		
		 // echo $this->db->last_query(); die();
		  // echo "<pre>"; print_r($this->data);die;
		   
		admin_views("/filter_category/listing",$this->data);
		
	}
	
	/**
	 * Create or edit user 
	 ***/
	function make($id=''){ 
			  	
		if($id!=''){ //update
			 
			 $this->data['query_type'] = 'Update';
			 
			 $this->form_validation->set_rules('category_name', 'Category name', 'required');
			 
			 //$this->form_validation->set_rules('hospital_id', 'Hospital id', 'required');
			 
			 if ($this->form_validation->run() === TRUE)
			 {     
				  
				  //print_r($_FILES); die;
				  extract($_POST);
				  $whrr=array('category_name' => $category_name, 'id !='=>$id,'status !='=>'deleted');
				  $ct=get_employeecount($this->table, $whrr);
				  if($ct > 0){
				  		 $this->session->set_flashdata('err_mesg', '<p>This filter category already added.</p>');
				  		redirect(admin_url($this->classname).'/make/'.$id);
				   		die;
				  }

				 
				  $UpdateData = ['category_name'=>$category_name,'status'=>$status];     
				  
				  $update = $this->user->update($this->table,$UpdateData,['id'=>$id]);
				  
				  if($update)   
				    $this->session->set_flashdata('message', 'Filter category updated Successfully.'); 
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing/'.$access);
	
			 }else
			 {    $this->data['message'] = validation_errors();
				  $this->data['category'] = $this->user->select_row($this->table,'*',['id'=>$id]);
				  //$this->data['categories'] = $this->user->select($this->table,'id,category_name',['status!='=>'deleted','parent_id'=>0]);
				   admin_views("/filter_category/input",$this->data);
			 }
			 
			 			
		}else{ //add
			
			 $this->data['query_type'] = 'Add';
			 $this->form_validation->set_rules('category_name', 'Category name', 'required|trim');
			 //$this->form_validation->set_rules('category_name', 'category name', 'required');
			 		        
			        //~ print_r($_POST);
			        //~ echo var_dump($this->form_validation->run());
			        //~ print_r(validation_errors()); die;
			         
			 if ($this->form_validation->run() === TRUE)
			 {
				  extract($_POST);
				   $whrr=array('category_name' => $category_name,'status !='=>'deleted');
				  $ct=get_employeecount($this->table, $whrr);
				  if($ct > 0){
				  		 $this->session->set_flashdata('err_mesg', '<p>This filter category already added.</p>');
				  		redirect(admin_url($this->classname).'/make');
				   		die;
				  }
				  
				  $InsertData = ['category_name'=>$category_name,'status'=>$status];
				  
				  $insert = $this->user->insert($this->table,$InsertData);
				  
				  if($insert){   
					  
				    $this->session->set_flashdata('message', 'Filter category added Successfully.'); 
				   }
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing/');
				  
			 }else
			 {
				     //$this->data['message'] = validation_errors();
					 $this->data['category_name'] = $this->form_validation->set_value('category_name');
					
					  admin_views("/filter_category/input",$this->data);
			 }
			
			
		}
		
	}
	
	/**
	 * Delete category 
	 ***/
	function delete($id=''){ 
		
		  if($id!=''){
			     
			     $update = $this->user->update($this->table,['status'=>'deleted'],['id'=>$id]);
			  
			     if($update)   
				    $this->session->set_flashdata('message', 'Filter category deleted Successfully.'); 
				 else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing/');
		  }else 
		     redirect(admin_url('404'));
	   
	              
	       	
	}
	
	
	
}
?>
