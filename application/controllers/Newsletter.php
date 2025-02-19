<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends Frontend_Controller {

	function __construct() {
		parent::__construct();
	
		//$this->load->model('Booking_model','booking');
		$usid=$this->session->userdata('st_userid');
		if(!empty($usid)){
		  $status=getstatus_row($usid);
		  if($status != 'active'){
		  	redirect(base_url('auth/logouts/').$status);
		   }
		}
	}

	//**** Send News Letter ****//
   function send()
	{
		$temid=url_decode($_POST['tempid']);
		$template=$this->user->select_row('st_newsletter','*',array('id'=>$temid));
		if(!empty($template)){
			
			if($_POST['usersIds']=='all')
			    {
				$sql="SELECT st_users.first_name,st_users.last_name,st_users.email FROM `st_booking` JOIN `st_users` ON `st_booking`.`user_id`=`st_users`.`id` WHERE `st_booking`.`merchant_id` = '".$this->session->userdata('st_userid')."' GROUP BY `user_id`";
				}
			 else{
				 $sql="SELECT st_users.first_name,st_users.last_name,st_users.email FROM `st_booking` JOIN `st_users` ON `st_booking`.`user_id`=`st_users`.`id` WHERE `st_booking`.`merchant_id` = '".$this->session->userdata('st_userid')."' AND user_id IN (".$_POST['usersIds'].") GROUP BY `user_id`";
				 }	
	     	 $users=$this->user->custome_query($sql,'result');
		    
		    if(!empty($users)){
				foreach($users as $usr){
					$data=array();
					if(!empty($template->image_path)){
						$data['logo']=base_url('assets/uploads/newsletter/'.$template->merchant_id.'/'.$template->image_path);
						}
					 $name=$usr->first_name." ".$usr->last_name;	
					 
					 $mauilrtemp=$template->description;
					 $data['message']=str_replace(array("[NAME]"),array($name),$mauilrtemp);
					 
					 $data['footer']=$template->footer;
					 
					$message = $this->load->view('email/newsletter_temlate',$data,true);
					
						$mail = emailsend($usr->email,$template->subject,$message,'styletimer');
					}
				 echo json_encode(array('success' =>1,'message'=>'Mails send successfully.'));  die;
				}else echo json_encode(array('success' =>0,'message'=>'You have not any customer available.'));  die;
		 
		 //echo "<pre>"; print_r($template); die;	 
			
			}
		else echo json_encode(array('success' =>0,'message'=>'You have not any template available.'));  die;	
	}

	//**** get all count customer ****//
   function get_count_all_customer(){
	    $where=array('st_booking.merchant_id'=>$this->session->userdata('st_userid'));
	    $count=$this->user->join_two_orderby('st_booking','st_users','user_id','id',$where,'count(DISTINCT(user_id)) totalcount','st_booking.id','user_id');
	    //echo $this->db->last_query(); die;
	    if(!empty($count)){
	       echo json_encode(array('success' =>1,'count'=>$count[0]->totalcount));  die;		       
	      }
	    else echo json_encode(array('success' =>0,'count'=>0));  die;		       
	       
	  }

	  //*** crop image function ****//
 function imagecropp(){
		$uid=$this->session->userdata('st_userid');
		$data =$_POST["image"];
		$image_array_1 = explode(";", $data);
		$image_array_2 = explode(",", $image_array_1[1]);
		$data = base64_decode($image_array_2[1]);
		$path='assets/uploads/temp/'.$uid.'/';
		
		$files = glob($path.'*');
		//print_r($files); die; //get all file names
		foreach($files as $file){
			if(is_file($file))
			unlink($file); //delete file
		}   
		
		@mkdir($path ,0777,TRUE);
		$imageName = $path.'Newsletter_'.time().$this->session->userdata('user_id').'.png';
		file_put_contents($imageName, $data);
		$arry=array('image'=>base_url($imageName),'status'=>'success');
		echo json_encode($arry); die;
   }  
	
 
}
