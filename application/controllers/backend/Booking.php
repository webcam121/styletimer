<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/phpexcel/PHPExcel.php';

class Booking extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_booking'; 
	
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
		
			$whr1=array('booking_type !=' => 'self');
 	 if(empty($_GET['short'])){
			 if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
				$ed_date= $date1->format('Y-m-d');
				//$whr1='AND DATE(created_on) >="'.$st_date.'" AND Date(created_on) <="'.$ed_date.'"';
				$whr1= array('booking_type !=' => 'self','DATE(booking_time) >='=>$st_date,'Date(booking_time) <=' =>$ed_date);
			}
			
		}
		else if($_GET['short'] =='all'){
				//$whr1= array('status!='=>'deleted','access'=>$role);
				$whr1=array('booking_type !=' => 'self');
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

			 //$whr1='AND DATE(created_on) >="'.$start_date.'" AND DATE(created_on) <="'.$end_date.'"';
			 $whr1= array('booking_type !=' => 'self','DATE(booking_time) >='=>$start_date,'Date(booking_time) <=' =>$end_date);

		}


		//$this->data['booking'] = $this->user->select($this->table,'*');
		//$where = array("booking_type !=" => 'self');
		$this->data['booking'] = $this->user->getbookinglistadmin($whr1);
		  // echo "<pre>"; print_r($this->data);die;
		   
		admin_views("/booking/listing",$this->data);
		
	}

 function list_export($type ='excel'){
 	 

 	 $where="";
 	 $search="";
 	 if(!empty($_GET['search'])){
 	 	$search=$_GET['search'];
 	 }
 	 $whr1=array('booking_type !=' => 'self');
 	 if(empty($_GET['short'])){
			 if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
				$ed_date= $date1->format('Y-m-d');
				//$whr1='AND DATE(created_on) >="'.$st_date.'" AND Date(created_on) <="'.$ed_date.'"';
				$whr1= array('booking_type !=' => 'self','DATE(booking_time) >='=>$st_date,'Date(booking_time) <=' =>$ed_date);
			}
			
		}
		else if($_GET['short'] =='all'){
				//$whr1= array('status!='=>'deleted','access'=>$role);
				$whr1=array('booking_type !=' => 'self');
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

			 //$whr1='AND DATE(created_on) >="'.$start_date.'" AND DATE(created_on) <="'.$end_date.'"';
			 $whr1= array('booking_type !=' => 'self','DATE(booking_time) >='=>$start_date,'Date(booking_time) <=' =>$end_date);

		}
 	 		
		//if(!empty($_GET['orderby'])) $ordrer=$_GET['orderby']; else $ordrer='st_booking.booking_time'; 
 	 	 //$where = array("booking_type !=" => 'self');
 	 	 $booking=$this->user->getbookinglistadmin_export($whr1,$search);


	 	 //$header = array('Sr.No','Booking Id','CUSTOMER','DATE','TIME','SOLON NAME','PRICE','STATUS');
	 	 $header = array('Sr.No','SOLON NAME','CUSTOMER','BOOKING DATE','CREATED DATE','AMOUNT','STATUS');
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
			$table_columns = array('Sr.No','SOLON NAME','CUSTOMER','BOOKING DATE','CREATED DATE','AMOUNT','STATUS');
			$column = 0;
			//$object->getActiveSheet()->setCellValueByColumnAndRow($column, 0, '');

			foreach($table_columns as $field)
				{
				  $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				  $column++;
				}
				if(!empty($booking)){
					$excel_row = 2;  $i=1;
				  foreach($booking as $row){
					 $time = new DateTime($row->booking_time);
		             $date = $time->format('d/m/Y');
		             $time = $time->format('H:i');

		             $cre_date = new DateTime($row->created_on);
		              $c_date = $cre_date->format('d/m/Y H:i');
		            if($row->booking_type =='guest')
		              $us_name=$row->fullname;
		            else
		              $us_name=$row->first_name.' '.$row->last_name;
		              
		           
		           /*$book_detail=$this->user->select('st_booking_detail','id,booking_id,service_id,service_name',array('booking_id'=>$row->id),'','id','ASC');
		           $ser_nm='';
		          if(!empty($book_detail)){
		            foreach($book_detail as $serv){ 
		        	 $sub_name=get_subservicename($serv->service_id);  
		        	  if($sub_name == $serv->service_name)
		                  $ser_nm.=$serv->service_name.',';
		              else
		                  $ser_nm.=$sub_name.' - '.$serv->service_name.',';
		          		}
		          	}*/
		                  
		             //$s_names=rtrim($ser_nm, ',');
		             $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $i++);
		             $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->business_name);
		             $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $us_name);
		             //$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->emp_fn);
		             $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $date.' '.$time);
		             $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $c_date);
		             $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->total_price);
		             //$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->total_minutes.' Mins');
		             $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, ucfirst($row->status));
		             $excel_row++;
		            
		          }
		       }

				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="excel_booking-'.time().'.xlsx"');
				$object_writer->save('php://output');
		   }
		   else{
		    header("Content-Type: application/csv;");
           // file creation 
		   $file = fopen('php://output', 'w');
		   
		   fputcsv($file, $header);
		    $i=1;
		   if(!empty($booking)){
			  foreach($booking as $row){
				 $time = new DateTime($row->booking_time);
	             $date = $time->format('d/m/Y');
	             $time = $time->format('H:i');

	            if($row->booking_type =='guest')
	              $us_name=$row->fullname;
	            else
	              $us_name=$row->first_name.' '.$row->last_name;
	              
	           
	           $book_detail=$this->user->select('st_booking_detail','id,booking_id,service_id,service_name',array('booking_id'=>$row->id),'','id','ASC');
	           $ser_nm='';
	          
	            foreach($book_detail as $serv){ 
	        	 $sub_name=get_subservicename($serv->service_id);  
	        	  if($sub_name == $serv->service_name)
	                  $ser_nm.=$serv->service_name.',';
	              else
	                  $ser_nm.=$sub_name.' - '.$serv->service_name.',';
	          		}
	                  
	             $s_names=rtrim($ser_nm, ',');   
	             $data=array($i,$row->id,$us_name, $row->emp_fn,$date,$time,$s_names,$row->total_price,$row->total_minutes.' Mins',ucfirst($row->status));                                        
	          fputcsv($file, $data);
			   $i++;
				}
		 	}

			   fclose($file); 
			   exit; 
			}
					  
 	//print_r($booking);
  }
  
 public function set_booking_count(){
	 $this->data['query_type'] = 'Edit';
	 if(!empty($_POST['booking_count']))
	    {
		  $update = $this->user->update('st_users',array('booking_count'=>$_POST['booking_count']),['id'=>1]);
		  
		  $this->session->set_flashdata('message','App review setting updated successfully.');
		  
		  redirect(base_url('backend/booking/set_booking_count')); 
		}
	 
	 $this->data['detail'] = $this->user->select_row('st_users','booking_count',array('id'=>1));
	 admin_views("/booking/set_booking_count_for_app_review",$this->data);
	
	 } 


}
