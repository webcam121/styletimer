<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/phpexcel/PHPExcel.php';


class User extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_users'; 
	
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
		
		
	   require_once(FCPATH.'/stripe/init.php');
        // our test credential ankit sir id
		$stripe = array(
           "secret_key"      => STRIPE_SK,
           "publishable_key" => STRIPE_PK
		);
		
		//client acciount credential 
		//~ $stripe = array(
           //~ "secret_key"      => "sk_test_a1FT5pS7qPKpw2wuhtOhr1it",
           //~ "publishable_key" => "pk_test_gdnVrLe5D7NWVxa2Nx9YNSp3"
		//~ );
		
		\Stripe\Stripe::setApiKey($stripe['secret_key']);
			
        
	}
	
	
	function index(){  
		
		$this->listing();
	}
	
	/***
	 *  List all user according to his role
	 ***/
	function listing($role='user'){ 
		/*if(isset($_GET['role']) && $_GET['role']!=''){
			$role=$_GET['role'];
			}
	    else{
            $role='marchant';
			}*/
			$whr1= array('status!='=>'deleted','access'=>$role);
			if(empty($_GET['short'])){
			 if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
				$ed_date= $date1->format('Y-m-d');
				//$whr1='AND DATE(updated_on) >="'.$st_date.'" AND Date(updated_on) <="'.$ed_date.'"';
				$whr1= array('status!='=>'deleted','access'=>$role,'DATE(created_on) >='=>$st_date,'Date(created_on) <=' =>$ed_date);
			}
			
		}
		else if($_GET['short'] =='all'){
				$whr1= array('status!='=>'deleted','access'=>$role);
			}
		else{
			if($_GET['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_GET['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_GET['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
            else if($_GET['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-01', strtotime('last year'));
            }
			else{
				$start_date=date('Y-m-d');
                $end_date=date('Y-m-d');
			}

			 //$whr1='AND DATE(updated_on) >="'.$start_date.'" AND DATE(updated_on) <="'.$end_date.'"';
			 $whr1= array('status!='=>'deleted','access'=>$role,'DATE(created_on) >='=>$start_date,'Date(created_on) <=' =>$end_date);

		}

			if(!empty($_GET['code']))
			{
				if(!empty($_GET['paid'])){
				$this->db->where('salesman_code',$_GET['code']);
				$this->db->where('plan_id !=','');
				}
				else
				$this->db->where('salesman_code',$_GET['code']); 
			}
		$this->data['users'] = $this->user->select($this->table,'*',$whr1);
		//echo $this->db->last_query();die;
		$this->data['role'] = $role;
		
		  // echo "<pre>"; print_r($this->data);
		 
		admin_views("/user/listing",$this->data);
		
	}

	function status($status,$role,$id){
		if($this->user->update('st_users',array('status'=>$status),array('id'=>$id))){
			if($role=='marchant' && $status=='inactive'){
			   $userDtails = $this->user->select_row('st_users','id,subscription_id,stripe_id',array('id' =>$id,'subscription_status'=>'active'));	
			   $this->db->query("DELETE FROM `st_favourite` WHERE `salon_id`=".$id);
			     
			        if(!empty($userDtails->subscription_id)){

						$subscription= \Stripe\Subscription::update(
							  $userDtails->subscription_id,
							  [
								'cancel_at_period_end' => true,
							  ]
							);
							
					  //$event_data = $subscription->__toArray(true);
					  
					  if($subscription->cancel_at_period_end==1){
						  $res=$ins = $this->db->query("UPDATE `st_users` SET `subscription_status`='cancel' WHERE id='".$id."'");
						  
						  if($res){
							  $this->session->set_flashdata('message','Status updated and subscription has been canceled Successfully.');
							  
							  }
							else{
							  $this->session->set_flashdata('message', 'Status updated Successfully. And subscription was not cancelled.');
							  
							  }  
						// echo "<pre>"; print_r($event_data); die;
						  }
						else{
							$this->session->set_flashdata('message', 'Status updated Successfully. And subscription was not cancelled.');
							  
						  }    
						}
				 else{
					  $this->session->set_flashdata('message', 'Status updated Successfully.');
					 }	
			     
			     
				}
			else{	
			  $this->session->set_flashdata('message', 'Status updated Successfully.');
		    }
		} 
		else
			$this->session->set_flashdata('error', 'Something went wrong.');  
			 	
		redirect(admin_url().'user/listing/'.$role);
	}
	
	/**
	 * Create or edit user 
	 ***/
	function make($id=''){ 
			  	
		if($id!=''){ //update
			 
			 $this->data['query_type'] = 'Update';
			 
			 $this->form_validation->set_rules('first_name', 'First name', 'required');
			 $this->form_validation->set_rules('last_name', 'Last name', 'required');
			 //$this->form_validation->set_rules('email', 'Email', 'required');
			 $this->form_validation->set_rules('mobile', 'Phone', 'required');
			 //$this->form_validation->set_rules('hospital_id', 'Hospital id', 'required');
			 
			 if ($this->form_validation->run() === TRUE)
			 {     
				  
				  
				  extract($_POST);
				    
				  $UpdateData = ['first_name'=>$first_name,'last_name'=>$last_name,'email'=>$email,'mobile'=>$mobile,'status'=>$status];
				  
				  $update = $this->user->update($this->table,$UpdateData,['id'=>$id]);
				  
				  if($update)   
				    $this->session->set_flashdata('message', 'Salesman updated Successfully.'); 
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing/'.$access);
	
			 }else
			 {    $this->data['message'] = validation_errors();
				  $this->data['user'] = $this->user->select_row($this->table,'*',['id'=>$id]);
				  
				  if($this->data['user']->access=='salesman'){
				   admin_views("/user/input",$this->data);
			      }
			     else{  $this->session->set_flashdata('error', 'You can not edit this user.');  				 
				         redirect(admin_url($this->classname).'/listing/'.$access); 
				     } 
			 } 
			 
			 			
		}else{ //add
			
			 $this->data['query_type'] = 'Add';
			
			 $this->form_validation->set_rules('first_name', 'First name', 'required');
			 $this->form_validation->set_rules('last_name', 'Last name', 'required');
			 $this->form_validation->set_rules('email', 'Email', 'required|callback_uniqe_exist_check');
			 $this->form_validation->set_rules('mobile', 'Mobile', 'required');
			        
			       // print_r($_POST); die;
			        //~ echo var_dump($this->form_validation->run());
			        //~ print_r(validation_errors()); die;
			         
			 if ($this->form_validation->run() === TRUE)
			 {
				  extract($_POST);
				  
				  $InsertData = ['first_name'=>$first_name,'last_name'=>$last_name,'email'=>$email,'mobile'=>$mobile,'status'=>$status,'access'=>'salesman'];
				  
				  $insert = $this->user->insert($this->table,$InsertData);
				 // echo $this->db->last_query(); die;
				  
				  if($insert){ 
					  $fl=ucfirst(substr($first_name,0,1));  
					  $ll=ucfirst(substr($last_name,0,1)); 
					  $code="ST".$fl.$ll.$insert; 
					  
					   $UpdateData = ['salesman_code'=>$code];				  
				       $update = $this->user->update($this->table,$UpdateData,['id'=>$insert]);
				       
				       $senddata=array();
				       $senddata['first_name']=$first_name;
				       $senddata['code']=$code;
				       $senddata['url']=base_url('merchant/registration?r='.$code);
				       
				       $datasend['data']=$senddata;
				       
				        $message = $this->load->view('email/salesman_register',$datasend, true);
				        
					   $mail = emailsend($email,'Styletimer-Registration',$message,'styletimer');
				      $this->session->set_flashdata('message', 'Salesman added Successfully.'); 
				    }
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing/salesman');
				  
			 }else
			 {
				     //$this->data['message'] = validation_errors();
					 $this->data['first_name'] = $this->form_validation->set_value('first_name');
					 $this->data['last_name'] = $this->form_validation->set_value('last_name');
					 $this->data['email'] = $this->form_validation->set_value('email');
					 $this->data['mobile'] = $this->form_validation->set_value('mobile');
					   //echo "<pre>"; print_r($this->data); die;
					 admin_views("/user/input",$this->data);
			 }
			
			
		}
		
	}
	
	/**
	 * Delete user 
	 ***/
	function delete($role,$id=''){ 
		ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

		if($id!=''){
			     
			$update = $this->user->update($this->table,['status'=>'deleted'],['id'=>$id]);

			if($update)  { 

				if($role=='user')
				{

					$where = array("user_id" => $id);
					$whr   = array("user_id" => $id);

					$select ='SELECT GROUP_CONCAT(id) as m_id FROM `st_booking` WHERE user_id="'.$id.'" AND status !="completed"';

					$all_id  = $this->user->custome_query($select,'row');
							//print_r($all_id )
					if($all_id.length > 0){
						$where1="booking_id IN(".$all_id->m_id.")";  
						$this->db->delete('st_invoices', $where1);
					}

					if($this->db->delete('st_booking', $where)){
						$this->db->delete('st_review', $where);
						$this->db->delete('st_booking_detail', $whr);
						//echo true;
					}

				}
				


				if($role =="marchant")
					$acc ="Merchant";
				else
					$acc =ucfirst($role);
					
				if($role=='marchant'){

					$employees=$this->user->select('st_users','id',array('merchant_id'=>$id),'','id','ASC');	

					$employee = [];
					$all = [$id];
					if(!empty($employees)){
						foreach($employees as $emp){
							$employee[] = $emp->id;
							$all[] = $emp->id;
						}
					}

					$this->db->delete('st_availability', "user_id IN(".implode(',',$all).")");
					$this->db->delete('st_banner_images', ['user_id' => $id]);
					$this->db->delete('st_booking', ['merchant_id' => $id]);
					$this->db->delete('st_booking_detail', ['mer_id' => $id]);
					$this->db->delete('st_booking_notification', ['merchant_id' => $id]);
					$this->db->delete('st_cart', ['merchant_id' => $id]);
					$this->db->delete('st_contactus', ['created_by' => $id]);
					$this->db->delete('st_favourite', ['salon_id' => $id]);
					$this->db->delete('st_merchant_category', ['created_by' => $id]);
					$this->db->delete('st_merchant_payment_method', ['user_id' => $id]);
					$this->db->delete('st_newsletter', ['merchant_id' => $id]);
					$this->db->delete('st_offer_availability', ['created_by' => $id]);
					$this->db->delete('st_payments', ['user_id' => $id]);
					$this->db->delete('st_recently_search', ['salon_id' => $id]);
					$this->db->delete('st_review', ['merchant_id' => $id]);
					$this->db->delete('st_service_employee_relation', ['created_by' => $id]);
					$this->db->delete('st_subcategory_settings', ['merchant_id' => $id]);
					$this->db->delete('st_suggestions', "user_id IN(".implode(',',$all).")");
					$this->db->delete('st_taxes', ['merchant_id' => $id]);
					$this->db->delete('st_usernotes', ['created_by' => $id]);
					$this->db->delete('temp_user', ['created_by' => $id]);				
					

					$userDtails = $this->user->select_row('st_users','id,subscription_id,stripe_id',array('id' =>$id,'subscription_status'=>'active'));	

					if(!empty($userDtails->subscription_id))
					{

						try {
							$subscription= \Stripe\Subscription::update(
								$userDtails->subscription_id,
								[
									'cancel_at_period_end' => true,
								]
							);
						} catch (Exception $e) {}
									
						//$event_data = $subscription->__toArray(true);
								
						// if($subscription->cancel_at_period_end==1){
						// 	$res=$ins = $this->db->query("UPDATE `st_users` SET `subscription_status`='cancel' WHERE id='".$id."'");
						// }
					}

					$this->db->delete('st_users', "id IN(".implode(',',$all).")");
							
				}
					
				$this->session->set_flashdata('message', $acc.' deleted Successfully.');
			} 
			else  
				$this->session->set_flashdata('error', 'Something went wrong.');   
				
			redirect(admin_url(lcfirst($this->classname)).'/listing/'.$role);
		}else 
			redirect(admin_url('404'));
	}
	
	/**
	 * set defualt free trial period
	 ***/
	function trial_period($id){
	   
	    if(!empty($id)){ //update
			 
			 $this->data['query_type'] = 'Update';
			 
			 $this->form_validation->set_rules('period', 'Time', 'required');
		
			 
			 if($this->form_validation->run() === TRUE)
			 {     
				  
				  
				  extract($_POST);
				    
				  $UpdateData = ['notification_time'=>$_POST['period']];
				  
				  $update = $this->user->update($this->table,$UpdateData,['id'=>$id]);
				  
				  if($update)   
				    $this->session->set_flashdata('message', 'Period updated Successfully.'); 
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/trial_period/1');
	
			 }else
			 {    $this->data['message'] = validation_errors();
				  $this->data['detail'] = $this->user->select_row($this->table,'id,notification_time',['id'=>$id]);
				   admin_views("/user/trialperiod",$this->data);
			 }
			 
			 			
		}
	  else redirect($_SERVER['HTTP_REFERER']);  
	   
	}

function uniqe_exist_check($email)
    {
        $users=$this->user->select_row($this->table,'id',['email'=>$email,'status !='=>'deleted']);  
        if(empty($users)) return true;
        else{ $this->form_validation->set_message('uniqe_exist_check', 'Diese E-Mail Adresse existiert bereits.');
			  return false;
			  }
    
    }
 function update_trial(){
 	extract($_POST);
 	$users=$this->user->select_row($this->table,'created_on',['id'=>$mid]);
	$time = strtotime($users->created_on);
 	if($month=='240'){
 		$end_date= Date("Y-m-d H:i:s", strtotime("+".$month." Month", $time));
 	} else if ($month == '0') {
		$end_date= Date("Y-m-d H:i:s", strtotime("+"."1 day", $time));
	}else{
		//$today = date("Y-m-d");
          $end_date = date("Y-m-d H:i:s", strtotime("+".$month. " month", $time));
		//$end_date = date('Y-m-d H:i:s',  strtotime("+".$month. "month", $time));
 	//$end_date= Date("Y-m-d H:i:s", strtotime($users->created_on."+".$month." Month"));
	 print_r($end_date);
 	}
    
 	if($this->user->update($this->table,array('end_date' => $end_date,'extra_trial_month' => $month),['id'=>$mid]))
 		echo 'true';
 	else
 		echo 'false';
    
 }

 function update_online_booking() {
	extract($_POST);
	$users=$this->user->select_row($this->table,'created_on',['id'=>$mid]);
	if($this->user->update($this->table,array('allow_online_booking' => $val),['id'=>$mid]))
 		echo 'true';
 	else
 		echo 'false';
 }

 function getsalon_list($code='',$type=''){ 
	$html="";

	 if(!empty($code)){
	 	if($type == 'plan')
	 		$whr = array('access' =>'marchant','plan_id !=' =>'','salesman_code'=>$code);
	 	else
	 		$whr = array('access' =>'marchant','salesman_code'=>$code);

		$salon = $this->user->select($this->table,'id,business_name,created_on',$whr);
		//echo $this->db->last_query();
		if(!empty($salon)){
			$i =1;
				foreach($salon as $row){
					$newDate = date("d/m/Y H:i", strtotime($row->created_on)); 

					$url =base_url("backend/user/salon_detail");

					$html.='<tr><td>'.$i.'</td><td><a data-sid="'.$row->id.'" data-url="'.$url.'" class="getsalondetails">'.$row->business_name.'</a></td><td>'.$newDate.'</td></tr>';
				$i++;
				}
			}


		}
	
		 echo json_encode(array('html' =>$html));	die;
	}


	function list_export($type='excel'){
	
		$whr1="";
 	 if(empty($_GET['short'])){
			 if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
				$ed_date= $date1->format('Y-m-d');
				$whr1='AND DATE(created_on) >="'.$st_date.'" AND Date(created_on) <="'.$ed_date.'"';
				//$whr1= array('status!='=>'deleted','access'=>$role,'DATE(created_on) >='=>$st_date,'Date(created_on) <=' =>$ed_date);
			}
			
		}
		else if($_GET['short'] =='all'){
				//$whr1= array('status!='=>'deleted','access'=>$role);
				$whr1="";
			}
		else{
			if($_GET['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_GET['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_GET['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
            else if($_GET['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-01', strtotime('last year'));
            }
			else{
				$start_date=date('Y-m-d');
                $end_date=date('Y-m-d');
			}

			 $whr1='AND DATE(created_on) >="'.$start_date.'" AND DATE(created_on) <="'.$end_date.'"';
			 //$whr1= array('status!='=>'deleted','access'=>$role,'DATE(created_on) >='=>$start_date,'Date(created_on) <=' =>$end_date);

		}

 	 $search='';
 		if($_GET['access'] =="marchant"){
 	 	 $access = 'marchant';
 	 	 $header = array('Sr.No','FIRST NAME','LAST NAME','BUSINESS NAME','EMAIL','REGISTERED','TRIAL','STATUS');
		$table_columns = array('Sr.No','FIRST NAME','LAST NAME','BUSINESS NAME','EMAIL','REGISTERED','TRIAL','STATUS');
 	 	 	if(!empty($_GET['search']))
 	 	   $search ='AND (first_name LIKE "%'.$_GET['search'].'%" OR last_name LIKE "%'.$_GET['search'].'%" OR email LIKE "%'.$_GET['search'].'%" OR business_name LIKE "%'.$_GET['search'].'%")';
 	 	 
 		}
 		else if($_GET['access'] =="salesman"){
 		 $access = 'salesman';
 	 	 $header = array('Sr.No','FIRST NAME','LAST NAME','EMAIL','REGISTERED','REG TOTAL','REG PAID','REFCODE','LINK','STATUS');
		 $table_columns = array('Sr.No','FIRST NAME','LAST NAME','EMAIL','REGISTERED','REG TOTAL','REG PAID','REFCODE','LINK','STATUS');
 	 	 	if(!empty($_GET['search']))
 	 	   $search ='AND (first_name LIKE "%'.$_GET['search'].'%" OR last_name LIKE "%'.$_GET['search'].'%" OR email LIKE "%'.$_GET['search'].'%" OR salesman_code LIKE "%'.$_GET['search'].'%")';
 	 	 	
 		}
 		else{
 	 	 $access = $_GET['access'];
 	 	 $header = array('Sr.No','FIRST NAME','LAST NAME','EMAIL','REGISTERED','STATUS');
		 $table_columns = array('Sr.No','FIRST NAME','LAST NAME','EMAIL','REGISTERED','STATUS');
 	 	 if(!empty($_GET['search']))
 	 	   $search ='AND (first_name LIKE "%'.$_GET['search'].'%" OR last_name LIKE "%'.$_GET['search'].'%" OR email LIKE "%'.$_GET['search'].'%")';
 	 	 
 	 	}

 	 	  

		$sql="SELECT id,first_name,last_name,email,created_on,status,business_name,extra_trial_month, 	salesman_code  FROM st_users WHERE access='".$access."' AND status !='deleted' ".$whr1." ".$search." ORDER BY id desc";
	   $all_record = $this->user->custome_query($sql,'result');
	   
	
			 $delimiter = ",";
		if($type=='excel')
		  $filename = 'excel_report'.time().'.xls';
		else
		   $filename = 'csv_report'.time().'.csv'; 
		    
		   header("Content-Description: File Transfer"); 
		   header("Content-Disposition: attachment; filename=$filename"); 
		 /*if($type=='excel')
			header("Content-Type: application/vnd.ms-excel");
		 else 
		    header("Content-Type: application/csv;");*/

		   if($type=='excel'){
		   	$object = new PHPExcel();
			$object->setActiveSheetIndex(0);
			
			$column = 0;
			//$object->getActiveSheet()->setCellValueByColumnAndRow($column, 0, '');

			foreach($table_columns as $field)
				{
				  $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				  $column++;
				}
				if(!empty($all_record)){
					$excel_row = 2;  $i=1;
				  foreach($all_record as $row){
					 $time = new DateTime($row->created_on);
		             $date = $time->format('d/m/Y');
		             //$date = $time->format('d/m/Y H:i');
		             
     
		             $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $i++);
		             //$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->id);
		             $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->first_name);
		             $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->last_name);
		             if($_GET['access'] !="marchant"){
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->email);
		         		}
		             if($_GET['access'] =="marchant"){
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->business_name);
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->email);
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $date);
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->extra_trial_month.' Month');
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, ucfirst($row->status));
		             }
		             else if($_GET['access'] =="salesman"){
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $date);
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, getMerchentCountBycode($row->salesman_code));
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, getMerchentCountBycode_plan($row->salesman_code));
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->salesman_code);
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, base_url().'merchant/registration?r=STTA17');
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, ucfirst($row->status));	
		             }
		             else{
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $date);
		             	$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, ucfirst($row->status));	
		             }
		             
		             $excel_row++;
		            
		          }
		       }

				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="excel_'.$_GET['access'].'-'.time().'.xls"');
				$object_writer->save('php://output');
		   }
		   else{
		    header("Content-Type: application/csv;");
           // file creation 
		   $file = fopen('php://output', 'w');
		   
		   fputcsv($file, $header);
		    $i=1;
		      fclose($file); 
			   exit; 
			}
					  
 		//print_r($booking);
 	}

 	public function login_tosalon($id="")
	{ 
		
		$user = $this->user->select_row('st_users','*',['id'=>$id,'status !='=>'deleted']);
		if(!empty($user)){		  
		$session_data = [
		    'identity'             => $user->email,
		    'email'                => $user->email,
		    'st_userid'              => $user->id, //everyone likes to overwrite id so we'll use user_id
		    'old_last_login'       => $user->last_login,
		    'last_check'           => time(),
		    'access'       		   => $user->access,
		    'status'       		   => $user->status,
		    'sty_fname'		       => $user->first_name,
		    'business_name'		   => $user->business_name,
		    'sty_profile'		   => $user->profile_pic,
		    'profile_status'	   => $user->profile_status
		];

			$this->session->set_userdata($session_data);
			redirect(base_url('merchant/dashboard'));
		}
		else{
			$this->session->set_flashdata('error', 'Something went wrong, unable to login.');
			redirect(base_url('backend/user/listing/marchant?short=all'));
		}

	}

	public function salon_detail(){

		if(!empty($_POST['id']))
		  {
            $mid = $_POST['id'];
            

            $query = "SELECT st_users.id,st_users.status,first_name,last_name,business_name,st_users.email,st_users.created_on,st_users.gender,st_users.email,mobile,address,zip,country,city,IFNULL((SELECT image FROM st_banner_images WHERE user_id=".$mid."),'') as profile_pic,count(st_booking.id) as totalbook,(SELECT count(id) FROM st_booking WHERE merchant_id=".$mid." AND status='completed' AND booking_type!='self') as totalcomplete,(SELECT SUM(total_price) FROM st_booking WHERE merchant_id=".$mid." AND status='completed' AND booking_type!='self') as totalrevenew,(SELECT count(id) FROM st_booking WHERE merchant_id=".$mid." AND status='cancelled' AND booking_type!='self') as totalcanceled,(SELECT count(id) FROM st_booking WHERE merchant_id=".$mid." AND status='no show' AND booking_type!='self') as totalnoshow,(SELECT count(id) FROM st_booking WHERE merchant_id=".$mid." AND status='confirmed' AND booking_type!='self') as totalupcoming FROM st_users LEFT JOIN st_booking ON st_booking.merchant_id=st_users.id AND st_booking.booking_type != 'self' AND st_booking.merchant_id=".$mid." WHERE st_users.id=".$mid." ORDER BY booking_time ASC";
            
            $this->data['userdata'] = $this->user->custome_query($query,'row');
            //$blockQuery  = "SELECT id FROM st_client_block WHERE client_id=".$cid." AND merchant_id=".$mid;
            //$this->data['blockclient'] = $this->user->custome_query($blockQuery,'row');
            
	       // echo "<pre>"; print_r($this->data); die;
	       $html = $this->load->view('backend/user/client_profile_popup',$this->data,true);	
	       echo json_encode(['success'=>1,'html'=>$html]); die;
         }
	   else{ 
		   echo json_encode(['success'=>0,'msg'=>'']); die;
		   
		   } 
	   
	}
	
	
	

	public function client_booking_list($id='',$page='0'){
 

		if(!empty($id))
		  {
            $mid=url_decode($id);
            //$mid=$this->session->userdata('st_userid');
            
           $where=array('booking_type !='=>'self','st_booking.merchant_id'=>$mid);
           if(isset($_POST['order']))
           	  $order=$_POST['order'];
           else
              $order="";
              
        	if($_POST['short'] =='day'){
				 $start_date=date('Y-m-d');
                 $end_date=date('Y-m-d');
            }
        	else if($_POST['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_POST['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_POST['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_POST['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
            else if($_POST['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
            else if($_POST['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-01', strtotime('last year'));
            }
            
			else if($_POST['short'] == 'all'){
				$start_date="";
                $end_date="";
			}
			else {
            	if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_POST['start_date']);
				$start_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_POST['end_date']);
				$end_date= $date1->format('Y-m-d');
				}
				
            }

			if(!empty($start_date) && !empty($end_date)){
				$whr=array('DATE(st_booking.booking_time) >=' => $start_date , 'DATE(st_booking.booking_time) <=' => $end_date);
				$where=$where+$whr;
			}
			
		


		/*if(!empty($_GET['status'])){
			if($_GET['status'] =='upcoming'){
				$td= date('Y-m-d');
				$whr = array('DATE(st_booking.booking_time) >=' => $td,'st_booking.status' => 'confirmed');
				$where=$where+$whr;
			}
			else if($_GET['status'] =='recent'){
			 $whr = array('st_booking.status' => 'completed');	
				$where=$where+$whr;
			}
			else if($_GET['status'] =='cancelled'){
				$whr1 ='(st_booking.status="cancelled" OR st_booking.status="no_show")';	
			}
		
		}*/      
              
              if(!empty($whr1))
			           $this->db->where($whr1); 
		 $totalcount = $this->user->getbookinglist($where,0,0,'employee_id',$order);
		 if(!empty($totalcount))
		 	$total=count($totalcount);
		 else
		 	$total=0;
        if($_POST['limit']=='all'){
			$limit =$total;
	    }else{
		   $limit = isset($_POST['limit'])?$_POST['limit']:20;	//PER_PAGE10
	     }
		 $url = 'backend/user/client_booking_list/'.$id;
		 $segment = 5;    
		// $page = mypaging($url,$total,$segment,$limit);
		$offset=0;
		 if($page != 0){
			  $offset = ($page-1) * $limit;
			}
		$config = array();
	   $config["base_url"] = base_url().$url;
	   $config["total_rows"] = $total;
	   $config["per_page"] =$limit;
	   $config['use_page_numbers'] = TRUE;
	   $config['num_links'] =   2;
	   $config['num_tag_open'] = '<li class="page-item">';
       $config['num_tag_close'] = '</li>';
	   $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
	   $config['cur_tag_close'] = '</a></li>';
	   $config['first_tag_open'] = '<li class="page-item">';
	   $config['first_tag_close'] = '</li>';
	   $config['next_link'] = '&gt;';
	   $config['prev_link'] = '&lt;';
	   $config['first_link'] = '&laquo;';
	   $config['last_link'] = '&raquo;';
	   $config['last_tag_open'] = '<li class="page-item">';
	   $config['last_tag_close'] = '</li>';
	   $config['next_tag_open'] = '<li class="page-item">';
	   $config['next_tag_close'] = '</li>';
	   $config['prev_tag_open'] = '<li class="page-item">';
	   $config['prev_tag_close'] = '</li>';
	   $config["uri_segment"] = $segment;
		
		 $this->pagination->initialize($config);
		 
	     $pagination=$this->pagination->create_links();
	     
	             if(!empty($whr1))
			      $this->db->where($whr1); 
		 $booking=$this->user->getbookinglist($where,$config["per_page"],$offset,'employee_id',$order);
		//echo '<pre>'; print_r($booking); die;
		 $html="";
		 if(!empty($booking)){
			 foreach($booking as $row){
			 	$book_detail=$this->user->select('st_booking_detail','id,booking_id,service_id,service_name',array('booking_id'=>$row->id),'','id','ASC');
				//$sevices= get_servicename($row->id); 
				$sevices='';
                        if(!empty($book_detail)){foreach($book_detail as $serv){ 
                    	 $sub_name=get_subservicename($serv->service_id);  
                    	  if($sub_name == $serv->service_name)
                              $sevices.=$serv->service_name.',';
                          else
                              $sevices.=$sub_name.' - '.$serv->service_name.',';
                      		}
                      	}
                      $recipUrl="";
                		$up_time = new DateTime($row->updated_on);
                    	$action_date = $up_time->format('d/m/Y');

						$cls=''; $icon =''; 
						if($row->status =='confirmed')
				             {
                      				$cls='conform';
                      				$detalClass="";
                      				$txtDacoration="text-decoration: none !important;";
                      				$bk_class="booking_row";
                      				$recp='Cancel';
                      		}
                      	 else if($row->status =='cancelled')
                      	      {
                      		   		$cls='cencel';
                      		   		$detalClass="";
                      		   		$txtDacoration="text-decoration: none !important;";
                      		   		$bk_class="booking_row";
                      		   		$recp='';
							 }
                       else if($row->status =='completed')
                      		  { 
                      		   		$cls='completed';
                      		   		$detalClass="";
                      		   		$txtDacoration="cursor:pointer;";
                      		   		$bk_class="";
                      		   		$recp='';
                      		   		$icon=' <i class="fa fa-check" aria-hidden="true"></i>';
                      		   		$recipUrl=base_url('checkout/viewinvoice/'.url_encode($row->invoice_id));
							 }
                       else if($row->status =='no show')
                             { 
                                $cls='cencel'; 
                               $detalClass="";
                               $txtDacoration="text-decoration: none !important;";
                               $bk_class="booking_row";
                               $recp='';
						   }
				
				    if($row->book_by=='0') $bookedvia='Web';
					else $bookedvia='App';
					
				$saddress=$row->address." <br/>".$row->zip." ".$row->city;
					
				 $html=$html.'<tr>   
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.date("d/m/Y",strtotime($row->booking_time)).'</td>
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.date("H:i",strtotime($row->booking_time)).'</td>                   
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.$row->book_id.'</td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular booking_row" id="'.url_encode($row->id).'"><p class=" overflow_elips vertical-meddile mb-0 display-ib" style="width: 200px; white-space: none;">'.rtrim($sevices, ',').'</p></td>
                      <td class="text-center height56v font-size-14 color666 fontfamily-regular booking_row" id="'.url_encode($row->id).'">'.$row->total_minutes.' Mins</td>
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center" id="'.url_encode($row->id).'">'.$row->total_price.' €</td>
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">
                        <span href="#" class="'.$cls.' font-size-14 fontfamily-regular a_hover_red">'.$row->status.$icon.'</span>
                        <span class="font-size-10 color666 fontfamily-regular display-b">on '.$action_date.'</span>
                      </td>  
                      <td class="text-center"><a id="'.url_encode($row->id).'" style="'.$txtDacoration.'" href="javascript:void(0)" class="text-underline '.$detalClass.' color333 cancel_booking_admin">'.$recp.'</a></td>                    
                    </tr>';
                    
				 }
			 
			 }
		else{
			$html='<tr><td colspan="8" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="'.base_url('assets/frontend/images/no_listing.png').'"><p style="margin-top: 20px;">'.$this->lang->line('dont_any_appointments').'</p></div></td></tr>';
			
			}	 
			
		  echo json_encode(array('success'=>'1','msg'=>'','html'=>$html,'pagination'=>$pagination));	die;
	        //echo "<pre>"; print_r($booking); die;
	       // $this->load->view('frontend/marchant/client_profile_view',$this->data);	
         }  else echo json_encode(array('success'=>'0','msg'=>'','html'=>'','pagination'=>'')); die;
	   
	     
	}



public function user_booking_list($id='',$page='0'){
 

		if(!empty($id))
		  {
            $mid=url_decode($id);
            //$mid=$this->session->userdata('st_userid');
            
           $where=array('booking_type !='=>'self','st_booking.user_id'=>$mid);
           if(isset($_POST['order']))
           	  $order=$_POST['order'];
           else
              $order="";
              
        	if($_POST['short'] =='day'){
				 $start_date=date('Y-m-d');
                 $end_date=date('Y-m-d');
            }
        	else if($_POST['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_POST['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_POST['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_POST['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
            else if($_POST['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
            else if($_POST['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-01', strtotime('last year'));
            }
            
			else if($_POST['short'] == 'all'){
				$start_date="";
                $end_date="";
			}
			else {
            	if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_POST['start_date']);
				$start_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_POST['end_date']);
				$end_date= $date1->format('Y-m-d');
				}
				
            }

			if(!empty($start_date) && !empty($end_date)){
				$whr=array('DATE(st_booking.booking_time) >=' => $start_date , 'DATE(st_booking.booking_time) <=' => $end_date);
				$where=$where+$whr;
			}
			
		


		/*if(!empty($_GET['status'])){
			if($_GET['status'] =='upcoming'){
				$td= date('Y-m-d');
				$whr = array('DATE(st_booking.booking_time) >=' => $td,'st_booking.status' => 'confirmed');
				$where=$where+$whr;
			}
			else if($_GET['status'] =='recent'){
			 $whr = array('st_booking.status' => 'completed');	
				$where=$where+$whr;
			}
			else if($_GET['status'] =='cancelled'){
				$whr1 ='(st_booking.status="cancelled" OR st_booking.status="no_show")';	
			}
		
		}*/      
              
              if(!empty($whr1))
			           $this->db->where($whr1); 
		 $totalcount = $this->user->getbookinglist($where,0,0,'employee_id',$order);
		 if(!empty($totalcount))
		 	$total=count($totalcount);
		 else
		 	$total=0;
        if($_POST['limit']=='all'){
			$limit =$total;
	    }else{
		   $limit = isset($_POST['limit'])?$_POST['limit']:20;	//PER_PAGE10
	     }
		 $url = 'backend/user/user_booking_list/'.$id;
		 $segment = 5;    
		// $page = mypaging($url,$total,$segment,$limit);
		$offset=0;
		 if($page != 0){
			  $offset = ($page-1) * $limit;
			}
		$config = array();
	   $config["base_url"] = base_url().$url;
	   $config["total_rows"] = $total;
	   $config["per_page"] =$limit;
	   $config['use_page_numbers'] = TRUE;
	   $config['num_links'] =   2;
	   $config['num_tag_open'] = '<li class="page-item">';
       $config['num_tag_close'] = '</li>';
	   $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
	   $config['cur_tag_close'] = '</a></li>';
	   $config['first_tag_open'] = '<li class="page-item">';
	   $config['first_tag_close'] = '</li>';
	   $config['next_link'] = '&gt;';
	   $config['prev_link'] = '&lt;';
	   $config['first_link'] = '&laquo;';
	   $config['last_link'] = '&raquo;';
	   $config['last_tag_open'] = '<li class="page-item">';
	   $config['last_tag_close'] = '</li>';
	   $config['next_tag_open'] = '<li class="page-item">';
	   $config['next_tag_close'] = '</li>';
	   $config['prev_tag_open'] = '<li class="page-item">';
	   $config['prev_tag_close'] = '</li>';
	   $config["uri_segment"] = $segment;
		
		 $this->pagination->initialize($config);
		 
	     $pagination=$this->pagination->create_links();
	     
	             if(!empty($whr1))
			      $this->db->where($whr1); 
		 $booking=$this->user->getbookinglist($where,$config["per_page"],$offset,'employee_id',$order);
		//echo '<pre>'; print_r($booking); die;
		 $html="";
		 if(!empty($booking)){
			 foreach($booking as $row){
			 	$book_detail=$this->user->select('st_booking_detail','id,booking_id,service_id,service_name',array('booking_id'=>$row->id),'','id','ASC');
				//$sevices= get_servicename($row->id); 
				$sevices='';
                        if(!empty($book_detail)){foreach($book_detail as $serv){ 
                    	 $sub_name=get_subservicename($serv->service_id);  
                    	  if($sub_name == $serv->service_name)
                              $sevices.=$serv->service_name.',';
                          else
                              $sevices.=$sub_name.' - '.$serv->service_name.',';
                      		}
                      	}
                      $recipUrl="";
                		$up_time = new DateTime($row->updated_on);
                    	$action_date = $up_time->format('d/m/Y');

						$cls=''; $icon =''; 
						if($row->status =='confirmed')
				             {
                      				$cls='conform';
                      				$detalClass="";
                      				$txtDacoration="text-decoration: none !important;";
                      				$bk_class="booking_row";
                      				$recp='Cancel';
                      		}
                      	 else if($row->status =='cancelled')
                      	      {
                      		   		$cls='cencel';
                      		   		$detalClass="";
                      		   		$txtDacoration="text-decoration: none !important;";
                      		   		$bk_class="booking_row";
                      		   		$recp='';
							 }
                       else if($row->status =='completed')
                      		  { 
                      		   		$cls='completed';
                      		   		$detalClass="";
                      		   		$txtDacoration="cursor:pointer;";
                      		   		$bk_class="";
                      		   		$recp='';
                      		   		$icon=' <i class="fa fa-check" aria-hidden="true"></i>';
                      		   		$recipUrl=base_url('checkout/viewinvoice/'.url_encode($row->invoice_id));
							 }
                       else if($row->status =='no show')
                             { 
                                $cls='cencel'; 
                               $detalClass="";
                               $txtDacoration="text-decoration: none !important;";
                               $bk_class="booking_row";
                               $recp='';
						   }
				
				    if($row->book_by=='0') $bookedvia='Web';
					else $bookedvia='App';
					
				$saddress=$row->address." <br/>".$row->zip." ".$row->city;
					
				 $html=$html.'<tr>   
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.date("d/m/Y",strtotime($row->booking_time)).'</td>
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.date("H:i",strtotime($row->booking_time)).'</td>   
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.$row->business_name.'</td>                 
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.$row->book_id.'</td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular booking_row" id="'.url_encode($row->id).'"><p class=" overflow_elips vertical-meddile mb-0 display-ib" style="width: 200px;">'.rtrim($sevices, ',').'</p></td>
                      <td class="text-center height56v font-size-14 color666 fontfamily-regular booking_row" id="'.url_encode($row->id).'">'.$row->total_minutes.' Mins</td>
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center" id="'.url_encode($row->id).'">'.$row->total_price.' €</td>
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">
                        <span href="#" class="'.$cls.' font-size-14 fontfamily-regular a_hover_red">'.$row->status.$icon.'</span>
                        <span class="font-size-10 color666 fontfamily-regular display-b">on '.$action_date.'</span>
                      </td>  
                      <td class="text-center"><a id="'.url_encode($row->id).'" style="'.$txtDacoration.'" href="javascript:void(0)" class="text-underline '.$detalClass.' color333 cancel_booking_admin">'.$recp.'</a></td>                    
                    </tr>';
                    
				 }
			 
			 }
		else{
			$html='<tr><td colspan="8" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="'.base_url('assets/frontend/images/no_listing.png').'"><p style="margin-top: 20px;">'.$this->lang->line('dont_any_appointments').'</p></div></td></tr>';
			
			}	 
			
		  echo json_encode(array('success'=>'1','msg'=>'','html'=>$html,'pagination'=>$pagination));	die;
	        //echo "<pre>"; print_r($booking); die;
	       // $this->load->view('frontend/marchant/client_profile_view',$this->data);	
         }  else echo json_encode(array('success'=>'0','msg'=>'','html'=>'','pagination'=>'')); die;
	   
	     
	}




	public function customer_list($id="",$page='0'){
 

		/*if(!empty($id))
		  {*/
         //$cid=url_decode($id);
         $mid=url_decode($id);
         $search='';
		 //if(isset($_POST['search']) && !empty($_POST['search'])){ $search = $_POST['search']; }

		 /*if($_POST['newcheck'] !='')
         	$where=array('st_booking.merchant_id'=>$mid,'st_users.newsletter' =>1);
		 else*/
		 $where=array('st_booking.merchant_id'=>$mid);
			
			if($_POST['short'] =='day'){
				 $start_date=date('Y-m-d');
                 $end_date=date('Y-m-d');
            }
			else if($_POST['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_POST['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_POST['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_POST['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
            else if($_POST['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
            else if($_POST['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-01', strtotime('last year'));
            }
            else if($_POST['short'] == 'all'){
            	$start_date="";
                $end_date="";
            }
			else {
            	if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_POST['start_date']);
				$start_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_POST['end_date']);
				$end_date= $date1->format('Y-m-d');
				}
				
            }
			if(!empty($start_date) && !empty($end_date)){
				$whr=array('DATE(st_users.created_on) >=' => $start_date , 'DATE(st_users.created_on) <=' => $end_date);
				$where=$where+$whr;
			}
			
		



		 $totalcount = $data=$this->user->join_two_orderby('st_booking','st_users','user_id','id',$where,'user_id','st_booking.id','user_id',0,0,$search);
		 if(!empty($totalcount))
		 	$total=count($totalcount);
		 else
		 	$total=0;


		$limit = isset($_POST['limit'])?$_POST['limit']:PER_PAGE10;	//PER_PAGE10
		 $url = 'backend/user/customer_list/'.$id;
		 $segment = 5;     
		// $page = mypaging($url,$total,$segment,$limit);
		$offset=0;
		 if($page != 0){
			  $offset = ($page-1) * $limit;
			}
		$config = array();
	   $config["base_url"] = base_url().$url;
	   $config["total_rows"] = $total;
	   $config["per_page"] =$limit;
	   $config['use_page_numbers'] = TRUE;
	   $config['num_links'] =   2;
	   $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
	   $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
	   $config['cur_tag_close'] = '</a></li>';
	   $config['first_tag_open'] = '<li class="page-item">';
	   $config['first_tag_close'] = '</li>';
	   $config['next_link'] = '&gt;';
	   $config['prev_link'] = '&lt;';
	   $config['first_link'] = '&laquo;';
	   $config['last_link'] = '&raquo;';
	   $config['last_tag_open'] = '<li class="page-item">';
	   $config['last_tag_close'] = '</li>';
	   $config['next_tag_open'] = '<li class="page-item">';
	   $config['next_tag_close'] = '</li>';
	   $config['prev_tag_open'] = '<li class="page-item">';
	   $config['prev_tag_close'] = '</li>';
	   $config["uri_segment"] = $segment;
		
		 $this->pagination->initialize($config);
		 
	     $pagination=$this->pagination->create_links();
	     
		 $field='st_booking.id,user_id,st_booking.merchant_id,first_name,last_name,profile_pic,address,st_users.gender,st_users.email,mobile,(select notes from st_usernotes where user_id=st_users.id AND created_by="'.$mid.'") as notes,(SELECT count(id) FROM st_booking WHERE user_id=st_users.id AND merchant_id="'.$mid.'") as bookcount,(SELECT booking_time FROM st_booking WHERE user_id=st_users.id AND status="completed" AND merchant_id="'.$mid.'" ORDER BY id DESC LIMIT 1) as lastbook';
		 
		 if(!empty($_POST['orderby'])) $order=$_POST['orderby'];
         else $order='st_booking.id';
         
         if(!empty($_POST['shortby'])) $sort=$_POST['shortby'];
         else $sort='asc';
         
		 $customer=$this->user->join_two_orderby('st_booking','st_users','user_id','id',$where,$field,$order,'user_id',$config["per_page"],$offset,$search,$sort);
		//echo '<pre>'; print_r($booking); die;
		 $html="";
		 if(!empty($customer)){
			 foreach($customer as $row){
				if($row->profile_pic !='')
                        $usimg=base_url('assets/uploads/users/').$row->user_id.'/icon_'.$row->profile_pic;
                    else
                        $usimg=base_url('assets/frontend/images/user-icon-gret.svg');
                  
                    if(!empty($row->mobile)) $mobile=$row->mobile; else $mobile='-';
              
                $uIDencoded = url_encode($row->user_id);
                
                $notes = strip_tags($row->notes);
              
                 $html=$html.'<tr>
                      <td class="font-size-14 height56v color666 fontfamily-regular editCust" data-id="'.$uIDencoded.'">
					 
                        <img src="'.$usimg.'" class="mr-3 width30 border-radius50">
                        <p class="overflow_elips mb-0 display-ib color666">'. $row->first_name.' '.$row->last_name.'</p>
                        
                      </td>                      
                      <td class="font-size-14 height56v color666 fontfamily-regular editCust" data-id="'.$uIDencoded.'"><p class="mb-0 overflow_elips" style="width:175px;">'.$row->email.'</p></td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular editCust" data-id="'.$uIDencoded.'">'.$mobile.'</td>
                      <td class="text-center font-size-14 color666 fontfamily-regular height56v editCust" data-id="'.$uIDencoded.'">
                        <p class="mb-0 display-ib">'.$row->gender.'</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center editCust" data-id="'.$uIDencoded.'">
                        <p class="mb-0 display-ib">'.(!empty($row->bookcount)?$row->bookcount:"0").'</p>
                      </td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center editCust" data-id="'.$uIDencoded.'">
                        <p class="mb-0 display-ib">'.(!empty($row->lastbook)?date('d M Y',strtotime($row->lastbook)):"NA").'</p>
                      </td> 
                      <td class="text-center">';
                        $html.='<a href="#" class="color666 font-size-14 fontfamily-regular a_hover_666 overflow_elips" title="'.$notes.'" data-toggle="popover">';
                           if(strlen($notes) > 18)
                                $html.=substr($notes, 0, 18).'...'."</a>";
                            else
                                $html.=$notes."</a>";
                                        
                       $html.='</td></tr>';
				
				 
				 
				 }
			 
			 }
		else{
			$html='<tr><td colspan="7" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="'.base_url('assets/frontend/images/no_listing.png').'"><p style="margin-top: 20px;"> You dont have any customer yet.</p></div></td></tr>';
			
			}	 
			
		  echo json_encode(array('success'=>'1','msg'=>'','html'=>$html,'pagination'=>$pagination));	die;
	    
	     
	}	

	public function salon_detail_filter(){

		if(!empty($_POST['id']))
		  {
            $mid = $_POST['id'];
			
			$whr1="";
			if(empty($_POST['short'])){
			 if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_POST['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_POST['end_date']);
				$ed_date= $date1->format('Y-m-d');
				$whr1='AND DATE(booking_time) >="'.$st_date.'" AND Date(booking_time) <="'.$ed_date.'"';
				//$whr1= array('status!='=>'deleted','access'=>$role,'DATE(created_on) >='=>$st_date,'Date(created_on) <=' =>$ed_date);
			}
			
		}
		else if($_POST['short'] =='all'){
				$whr1= '';
			}
		else{
			if($_POST['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_POST['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_POST['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_POST['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
			else if($_POST['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
            else if($_POST['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-01', strtotime('last year'));
            }
			else{
				$start_date=date('Y-m-d');
                $end_date=date('Y-m-d');
			}

			 $whr1='AND DATE(booking_time) >="'.$start_date.'" AND DATE(booking_time) <="'.$end_date.'"';
			 
		}            

            $query = "SELECT count(st_booking.id) as totalbook,(SELECT count(id) FROM st_booking WHERE merchant_id=".$mid." AND status='completed' AND booking_type!='self' ".$whr1.") as totalcomplete,(SELECT SUM(total_price) FROM st_booking WHERE merchant_id=".$mid." AND status='completed' AND booking_type!='self' ".$whr1.") as totalrevenew,(SELECT count(id) FROM st_booking WHERE merchant_id=".$mid." AND status='cancelled' AND booking_type!='self' ".$whr1.") as totalcanceled,(SELECT count(id) FROM st_booking WHERE merchant_id=".$mid." AND status='no show' AND booking_type!='self' ".$whr1.") as totalnoshow,(SELECT count(id) FROM st_booking WHERE merchant_id=".$mid." AND status='confirmed' AND booking_type!='self' ".$whr1.") as totalupcoming FROM st_users LEFT JOIN st_booking ON st_booking.merchant_id=st_users.id AND st_booking.merchant_id=".$mid." WHERE st_users.id=".$mid." ".$whr1." ORDER BY booking_time ASC";
            
            $userdata = $this->user->custome_query($query,'row');
            //$blockQuery  = "SELECT id FROM st_client_block WHERE client_id=".$cid." AND merchant_id=".$mid;
            //$this->data['blockclient'] = $this->user->custome_query($blockQuery,'row');
            
	       // echo "<pre>"; print_r($this->data); die;
	       
	       echo json_encode(['success'=>1,'html'=>$userdata]); die;
         }
	   else{ 
		   echo json_encode(['success'=>0,'msg'=>'']); die;
		   
		   } 
	   
	}
	
	
	public function user_detail_filter(){

		if(!empty($_POST['id']))
		  {
            $uid = $_POST['id'];
			
			$whr1="";
			if(empty($_POST['short'])){
			 if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_POST['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_POST['end_date']);
				$ed_date= $date1->format('Y-m-d');
				$whr1='AND DATE(booking_time) >="'.$st_date.'" AND Date(booking_time) <="'.$ed_date.'"';
				//$whr1= array('status!='=>'deleted','access'=>$role,'DATE(created_on) >='=>$st_date,'Date(created_on) <=' =>$ed_date);
			}
			
		}
		else if($_POST['short'] =='all'){
				$whr1= '';
			}
		else{
			if($_POST['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_POST['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_POST['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_POST['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
			else if($_POST['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
            else if($_POST['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-01', strtotime('last year'));
            }
			else{
				$start_date=date('Y-m-d');
                $end_date=date('Y-m-d');
			}

			 $whr1='AND DATE(booking_time) >="'.$start_date.'" AND DATE(booking_time) <="'.$end_date.'"';
			 
		}            

            $query = "SELECT count(st_booking.id) as totalbook,(SELECT count(id) FROM st_booking WHERE user_id=".$uid." AND status='completed' AND booking_type!='self' ".$whr1.") as totalcomplete,(SELECT SUM(total_price) FROM st_booking WHERE user_id=".$uid." AND status='completed' AND booking_type!='self' ".$whr1.") as totalrevenew,(SELECT count(id) FROM st_booking WHERE user_id=".$uid." AND status='cancelled' AND booking_type!='self' ".$whr1.") as totalcanceled,(SELECT count(id) FROM st_booking WHERE user_id=".$uid." AND status='no show' AND booking_type!='self' ".$whr1.") as totalnoshow,(SELECT count(id) FROM st_booking WHERE user_id=".$uid." AND status='confirmed' AND booking_type!='self' ".$whr1.") as totalupcoming FROM st_users LEFT JOIN st_booking ON st_booking.user_id=st_users.id AND st_booking.user_id=".$uid." WHERE st_users.id=".$uid." ".$whr1." ORDER BY booking_time ASC";
            
            $userdata = $this->user->custome_query($query,'row');
            //$blockQuery  = "SELECT id FROM st_client_block WHERE client_id=".$cid." AND merchant_id=".$mid;
            //$this->data['blockclient'] = $this->user->custome_query($blockQuery,'row');
            
	       // echo "<pre>"; print_r($this->data); die;
	       
	       echo json_encode(['success'=>1,'html'=>$userdata]); die;
         }
	   else{ 
		   echo json_encode(['success'=>0,'msg'=>'']); die;
		   
		   } 
	   
	}

	public function booking_cancel()
	{
		$id    = url_decode($_POST['book_id']);
		$check = $this->user->select_row('st_booking','status,booking_time,merchant_id',array('id'=>$id));
		if(!empty($check))
		{
			if($check->status !="confirmed")
			{
				echo json_encode(array('success'=>0,'msg' => 'Current booking status is '.$check->status));
				 die;
			}
			
		}
		//$usid = $this->session->userdata('st_userid');
		//$acc  = $this->session->userdata('access');
		$res  = 'Cancelled by Styletimer administrator';
		if($this->user->update('st_booking',array('status' => 'cancelled','updated_by' => '1','updated_on' =>date('Y-m-d H:i:s'),'reason' => $res),array('id'=>$id)))
		{
			$field = 'st_booking.id,user_id,total_time,booking_time,st_booking.merchant_id,first_name,book_id,last_name,st_users.email,st_booking.booking_type,st_booking.fullname,st_booking.email as guestemail,employee_id,(select business_name from st_users where st_users.id = st_booking.merchant_id) as salon_name,(select first_name from st_users where st_users.id = st_booking.merchant_id) as merchant_name,(select first_name from st_users where st_users.id = st_booking.merchant_id) as merchant_name,st_users.notification_status';
			$info  = $this->user->join_two_orderby('st_booking','st_users','user_id','id',array('st_booking.id'=>$id),$field);

			if(!empty($info))
			{
				$body_msg = "Booking cancelled by Styletimer administrator";
				$MsgTitle = "Styletimer-Booking cancelled";
				
				if($info[0]->booking_type != 'guest' && $info[0]->notification_status != 0)
				{
					sendPushNotification($info[0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$info[0]->merchant_id ,'book_id'=> $id,'booking_status' =>'cancelled','click_action' => 'BOOKINGDETAIL'));
				}
				
				$time = new DateTime($info[0]->booking_time);
	            $date = $time->format('d.m.Y');
	            $time = $time->format('H:i');

	            if($info[0]->booking_type == 'guest')
	            {
					$first_name  = ucwords($info[0]->fullname);
					$last_name   = "";
					$emailsend   = $info[0]->guestemail;
				}
				else
				{
					$first_name  = ucwords($info[0]->first_name);
					$last_name   = ucwords($info[0]->last_name);
					$emailsend   = $info[0]->email;
				}
				/*if($acc == 'user')
				{
					$insertArr = array("booking_id" => $id ,"status" => "cancel","merchant_id" => $check->merchant_id,"created_by" => $usid,"created_on" => date('Y-m-d H:i:s'));
					$this->user->insert('st_booking_notification',$insertArr);				
				}*/
				$message = $this->load->view('email/booking_cancel',array("fname"=>$first_name,"lname"=> $last_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,"booking_id" => $id,'book_id'=>$info[0]->book_id,'duration'=>$info[0]->total_time), true);
				
				$m_name   = $this->session->userdata('sty_fname');
					
				$message2 = $this->load->view('email/booking_cancel_salon',array("fname"=>$first_name,"lname"=> $last_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"merchant_name" => $info[0]->merchant_name,"booking_date"=>$date,"booking_time"=>$time,'access' => 'admin','emp_name' =>$m_name,"booking_id" => $id,'book_id'=>$info[0]->book_id, 'duration'=>$info[0]->total_time), true);
				
				$mail = emailsend($emailsend,$this->lang->line("styletimer_booking_cancel"),$message,'styletimer');  
				$mail = emailsend($info[0]->m_email,$this->lang->line("styletimer_booking_cancel"),$message2,'styletimer');
				$empDat = is_mail_enable_for_user_action($info[0]->employee_id);
				if ($empDat) {
					$message2 = $this->load->view('email/booking_cancel_salon',array("fname"=>$first_name,"lname"=> $last_name,"merchant_name" => $empDat->first_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,'access' => $acc,'emp_name' =>$m_name,"booking_id" => $id,'book_id'=>$info[0]->book_id, 'duration'=>$info[0]->total_time), true);	
					emailsend($empDat->email,$this->lang->line('styletimer_booking_cancel'),$message2,'styletimer');
				}
			}
			echo json_encode(array('success'=>1, 'id' =>$id));
		}
		else
			echo json_encode(array('success'=>0,'msg' => 'Sorry Unable to process...!','id' =>''));
	}

	function update_status_byadmin(){
		if($this->user->update('st_users',array('status'=>$_POST['status']),array('id'=>$_POST['id'])))
			$message ='Status updated Successfully.'; 
		else
			$error= 'Something went wrong.';  
		echo true;
	}
	

	public function user_detail(){

		if(!empty($_POST['id']))
		  {
            $uid = $_POST['id'];
            

            $query = "SELECT st_users.id,st_users.status,first_name,last_name,st_users.email,st_users.created_on,st_users.gender,st_users.email,mobile,address,zip,country,city,profile_pic,count(st_booking.id) as totalbook,(SELECT count(id) FROM st_booking WHERE user_id=".$uid." AND status='completed' AND booking_type!='self') as totalcomplete,(SELECT SUM(total_price) FROM st_booking WHERE user_id=".$uid." AND status='completed' AND booking_type!='self') as totalrevenew,(SELECT count(id) FROM st_booking WHERE user_id=".$uid." AND status='cancelled' AND booking_type!='self') as totalcanceled,(SELECT count(id) FROM st_booking WHERE user_id=".$uid." AND status='no show' AND booking_type!='self') as totalnoshow,(SELECT count(id) FROM st_booking WHERE user_id=".$uid." AND status='confirmed' AND booking_type!='self') as totalupcoming FROM st_users LEFT JOIN st_booking ON st_booking.user_id=st_users.id AND st_booking.booking_type != 'self' AND st_booking.user_id=".$uid." WHERE st_users.id=".$uid." ORDER BY booking_time ASC";
            
            $this->data['userdata'] = $this->user->custome_query($query,'row');
            //$blockQuery  = "SELECT id FROM st_client_block WHERE client_id=".$cid." AND merchant_id=".$mid;
            //$this->data['blockclient'] = $this->user->custome_query($blockQuery,'row');
            
	       // echo "<pre>"; print_r($this->data); die;
	       $html = $this->load->view('backend/user/user_profile_popup',$this->data,true);	
	       echo json_encode(['success'=>1,'html'=>$html]); die;
         }
	   else{ 
		   echo json_encode(['success'=>0,'msg'=>'']); die;
		   
		   } 
	   
	}


		
}
?>
