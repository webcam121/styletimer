<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/phpexcel/PHPExcel.php';


class Reviews extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_review'; 
	
	function __construct() {
		parent::__construct();
		$this->db->cache_off();
		$this->data['segment1'] = $this->uri->segment(2);
		$this->data['segment2'] = $this->uri->segment(3);
		$this->data['segment3'] = $this->uri->segment(4);
		$this->load->library('PHPExcel');
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
			$where = "";
			
			 if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
				$ed_date= $date1->format('Y-m-d');
				$where=' AND DATE(st_review.created_on) >="'.$st_date.'" AND Date(st_review.created_on) <="'.$ed_date.'"';
				
			}
			
			$sql="SELECT st_review.id,st_review.user_id,st_review.booking_id,st_review.merchant_id,rate,review,anonymous,st_review.created_on,first_name,last_name,(SELECT business_name from st_users where st_users.id=st_review.merchant_id) as business_name FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.user_id!='0' AND review_id=0 ".$where." GROUP BY st_review.id DESC ";
		
		 $this->data['allreviews']=$this->user->custome_query($sql,'result');
		 
		//echo '<pre>'; print_r($this->data); die;
		   
		admin_views("/reviews/listing",$this->data);
		
	}
	
   function get_edit_review_form(){
	  
	   $status = 0;
	   $formHtml ="";
	   if(!empty($_POST['id'])){
		   extract($_POST);
		   
	  $review = $this->user->select_row('st_review','id,rate,review',array('id'=>$id));
	  
	  if(!empty($review))
	    {  
			$status = 1;
			$rateHtml ="";
			for($i=5;$i>=1;$i--){
				$check ="";
				
				if($review->rate==$i){ $check = "checked"; }				
				$rateHtml.= '<input type="radio" id="star'.$i.'21101253" name="rating" value="'.$i.'" '.$check.'><label class="full rating" for="star'.$i.'21101253" title=""></label>';
				
				}
			
			
	   $formHtml = '<form method="post" action="'.base_url('backend/reviews/edit').'">
	       <input type="hidden" name="id" value="'.$review->id.'">
          <div class="modal-body" style="padding-top:24px;padding-bottom:24px;">
            <div class="form-group">
                <label class="d-block text-left">Rating</label>
                  <div class="d-block" style="height:30px;">
                    <fieldset class="rating" style="">
                       '.$rateHtml.'
                    </fieldset>
                </div>
               </div>
                <div class="fg-line form-group">
                    <label class="d-block text-left">Review</label>
                    <textarea class="form-control" name="review" rows="3" placeholder="Enter text..">'.$review->review.'</textarea>                    
                </div>
                <button type="button" class="btn btn-danger  waves-effect" data-dismiss="modal" style="margin-right:10px;">Cancel</button>
                <button type="submit" class="btn btn-primary  waves-effect">Submit</button>
            </div>
         </form>';
	       }
        }
        
        echo json_encode(['success'=>$status,'html'=>$formHtml]);
	   
	   }	

     function edit(){
	  
	   $status = 0;
	   $formHtml ="";
	   if(!empty($_POST['id'])){
		   extract($_POST);
		   $cdate = date('Y-m-d H:i:s');
	       $review = $this->user->update('st_review',array('rate'=>$rating,'review'=>$review,'updated_on'=>$cdate),array('id'=>$id));
	  
	       $this->session->set_flashdata('message','Review Updated Successfully.');
	       
	       redirect(base_url('backend/reviews'));
        }
        
       // echo json_encode(['success'=>$status,'html'=>$formHtml]);
	   
	   }	
	/**
	 * Delete user 
	 ***/
	function delete($id=''){ 		
		  if($id!=''){
			     
			     $deleted = $this->user->delete($this->table,['id'=>$id]);
			  
			     if($deleted){ 

			        $deleted = $this->user->delete($this->table,['review_id'=>$id]);
				    	
				    $this->session->set_flashdata('message','Review deleted Successfully.');
				    } 
				 else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url(lcfirst($this->classname)).'/listing');
		  }else 
		     redirect(admin_url('404'));
	   
	             
	}
	
	

}
?>
