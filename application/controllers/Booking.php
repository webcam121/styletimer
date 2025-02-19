<?php
require_once(APPPATH.'third_party/dompdf2/autoload.inc.php');

use Dompdf\Dompdf;

defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends Frontend_Controller{

	function __construct() {
		parent::__construct();
	
		$this->load->model('Booking_model','booking');
		$usid=$this->session->userdata('st_userid');
		if(!empty($usid)){
		  $status=getstatus_row($usid);
		  if($status != 'active'){
		  	redirect(base_url('auth/logouts/').$status);
		   }
		}
		$this->lang->load('push_notification','german');
	}
	//** Add to booking by salon **//
	function addtobooking()
	{
		$insertArr = array();
		$usid      = $this->session->userdata('st_userid');

		if($this->session->userdata('access')=='user')
		{
			if($this->booking->countResult('st_client_block',array('client_id'=>$usid,'merchant_id'=>$_POST['mid'])) > 0)
			{
				echo json_encode(array('success' =>2, 'message' => 'Du bist aktuell nicht berechtigt, Buchungen in diesem Salon zu erstellen.'));  die;
			}
			else if($this->booking->countResult('st_users',array('id'=>$_POST['mid'],'online_booking' => 0)) > 0)
			{
				echo json_encode(array('success' =>2, 'message' => 'Der Salon nimmt im Moment keine neuen Buchungen über styletimer entgegen'));  die;
			}
            $delID =0;
  
			if($this->booking->get_datacount('st_cart',array('user_id' => $usid,'service_id' => $_POST['id'])) == 0)
			{

				$service_det=$this->booking->select_row('st_merchant_category','price,created_by,discount_price,online,subcategory_id',array('id'=>$_POST['id']));
				if(!empty($service_det))
				{
					$checkIntocart=$this->booking->select_row('st_cart','id,subcat_id,(SELECT allow FROM st_subcategory_settings WHERE subcat_id='.$service_det->subcategory_id.' AND merchant_id='.$_POST['mid'].') as multiallow',array('subcat_id'=>$service_det->subcategory_id,'user_id'=>$usid));
                    
					// echo $this->db->last_query().'<pre>'; print_r($checkIntocart); die;

					// check online
					if($service_det->online==1)
					{
						if($this->booking->countResult('st_cart',array('merchant_id'=>$service_det->created_by,'user_id'=>$usid)))
						{
							$ss = "SELECT service_id FROM st_cart where user_id=".$usid."";
							$ress = $this->user->custome_query($ss,'result');
							$serId[] = $_POST['id'];	
							foreach ($ress as $key => $value)
							{
								$serId[]       = $value->service_id;
							
							}
							$emp = 1;
							$sql = $this->db->query("SELECT DISTINCT user_id,service_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$service_det->created_by."");
							$uidsRes= $sql->result();
							//echo '<pre>'; print_r($uidsRes);
							if(!empty($uidsRes)){
								
								$users = array();
								
								foreach($uidsRes as $res)
								{
									$users[$res->user_id][] = $res->service_id;
								}

								$userids=array();
								foreach($users as $k=>$v)
								{
									$arraymatch = count(array_intersect($v, $serId)) == count($serId);
									if($arraymatch)
										$userids[] = $k;
								}		
										
								if(empty($userids)){
									$emp =0;
								} 
										
							} else $emp =0;
								if($emp ==0){
								echo json_encode(array('success' =>2,'message' => 'Die ausgewählten Services müssen von unterschiedlichen Mitarbeitern ausgeführt werden. Du musst daher für diese Behandlung eine separate Buchung erstellen.'));  die;
							}

                          	$checkIntocart=$this->booking->select_row('st_cart','id,service_id,subcat_id,(SELECT allow FROM st_subcategory_settings WHERE subcat_id='.$service_det->subcategory_id.' AND merchant_id='.$_POST['mid'].') as multiallow',array('subcat_id'=>$service_det->subcategory_id,'user_id'=>$usid));

                          	if(!empty($checkIntocart->subcat_id) && $checkIntocart->subcat_id==$service_det->subcategory_id && empty($checkIntocart->multiallow)){
                            //  echo print_r($checkIntocart); die; 
								$delID = $checkIntocart->service_id;
									//echo $delID; die;
								$this->booking->delete('st_cart',array('id' => $checkIntocart->id));

							}

							$insertArr['service_id'] = $_POST['id'];
							$insertArr['user_id']    = $usid;
									
							if(!empty($service_det->discount_price))
								{
									$price = $service_det->discount_price;
								}
							else{
									$price = $service_det->price;
								}
									
							$insertArr['actual_price'] = $service_det->price;
							$insertArr['discounted_price'] = $service_det->discount_price;
							$insertArr['total_price'] = $price;
							$insertArr['subcat_id']   = $service_det->subcategory_id;
							$insertArr['merchant_id'] = $service_det->created_by;
							$insertArr['created_by']  = $usid;
							$insertArr['created_on']  = date('Y-m-d H:i:s');
							
							$this->user->insert('st_cart',$insertArr);
							
							$cls  = 'selectedBtn';
							$rcls = 'btn-border-orange';
						}
						else{
							$this->booking->delete('st_cart',array('user_id' => $usid));
							

							$serId[] = $_POST['id'];	
						
							$emp = 1;
							$sql = $this->db->query("SELECT DISTINCT user_id,service_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$service_det->created_by."");
							$uidsRes= $sql->result();
							//echo '<pre>'; print_r($uidsRes);
							if(!empty($uidsRes)){
								
								$users = array();
									
								foreach($uidsRes as $res)
								{
									$users[$res->user_id][] = $res->service_id;
								}

								$userids=array();
								foreach($users as $k=>$v)
								{
									$arraymatch = count(array_intersect($v, $serId)) == count($serId);
									if($arraymatch)
										$userids[] = $k;
								}		
										
								if(empty($userids)){
									$emp =0;
								} 
								
							} else $emp =0;
							if($emp ==0){
								echo json_encode(array('success' =>2,'message' => 'This service is not provided by any of the employee, so please select another service.'));  die;
							}
							$insertArr['service_id'] = $_POST['id'];
							$insertArr['subcat_id']   = $service_det->subcategory_id;
							$insertArr['user_id']    = $usid;
							
							if(!empty($service_det->discount_price))
							{
								$price = $service_det->discount_price;
							}
							else{
								$price = $service_det->price;
							}
							$insertArr['actual_price'] = $service_det->price;
							$insertArr['discounted_price'] = $service_det->discount_price;
							$insertArr['total_price'] = $price;
							$insertArr['merchant_id'] = $service_det->created_by;
							$insertArr['created_by']  = $usid;
							$insertArr['created_on']  = date('Y-m-d H:i:s');
							
							$this->user->insert('st_cart',$insertArr);
							
							$cls  = 'selectedBtn';
							$rcls = 'btn-border-orange';
						}
					}
					else
					{
						  echo json_encode(array('success' =>3));  die;	
					}		

				}
				else{
					  $cls='btn-border-orange';
					  $rcls='selectedBtn';
				}
			}
		  	else
			{
				$this->booking->delete('st_cart',array('user_id' => $usid,'service_id' => $_POST['id']));
				$cls  = 'btn-border-orange';
				$rcls = 'selectedBtn';
			}
			
			$field = 'COUNT(*) as service, SUM(actual_price) as tot_price,(SELECT price_start_option FROM st_merchant_category WHERE id=service_id AND price_start_option="ab" LIMIT 1) as price_start_option';
			$data  = $this->booking->select_row('st_cart',$field,array('user_id'=>$this->session->userdata('st_userid')));
			
			if(!empty($data->price_start_option) && $data->price_start_option=='ab'){
				$pricerr='ab '.price_formate($data->tot_price);
			}
			else{
				$pricerr=price_formate($data->tot_price);
			}
			echo json_encode(array('success' => 1,'count'=>$data->service,'total'=> $pricerr,'addCls' =>$cls,'revCls' =>$rcls,'deleteid'=>$delID, 'sid' => url_encode($_POST['mid'])));
		}
		else
		{
			if(empty($this->session->userdata('st_userid'))) 
			{
			echo json_encode(array('success' =>4));  die;
			}	  	
			else 
			{
			echo json_encode(array('success' =>0));  die;	
			}
		}
	}

	//** Booking Detail View **//
	public function booking_detail($id="")
	{

	//     print_r($id);
	
		if(empty($this->session->userdata('st_userid')))
		{
			redirect(base_url('auth/login'));
		}
        
		$field = "st_cart.id,service_id,name,duration,price,discount_price";  
		$whr   = array('user_id'=>$this->session->userdata('st_userid'));
		
		if(isset($_GET['date']) && $_GET['date']!="")
		{
		  	$date     = date('Y-m-d',strtotime($_GET['date']));
		  	$dayName  = date("l", strtotime($_GET['date']));
		  	$dayName  = strtolower($dayName);
		}
		else
		{
			$date     = date('Y-m-d');
			$dayName  = date("l", strtotime($date));
			$dayName  = strtolower($dayName);
		} 
		 
		    $ptime   = 'no';
		
		if(isset($_GET['time']) && $_GET['time']!="")
	    {
	       	$time   = date('H:i:s',strtotime($_GET['time']));;
	     	$ptime  = "yes";
		}
		else
			$time   = date('H:i:s');
		
		$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`service_id`,`st_category`.`category_name`, `name`, st_merchant_category.duration,`st_merchant_category`.`type` as stype,price_start_option,setuptime,processtime,finishtime,`price`, `discount_price`,`buffer_time`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime`,`parent_service_id` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$this->session->userdata('st_userid')."";

		if($this->session->userdata('access')=='user')
		{
	      $this->data['booking_detail'] = $this->user->custome_query($sqlForservice,'result');
		//  echo $this->db->last_query();die;
	    }
	   	$totalDuration              = 0;
	    $this->data['merchant_id']  = $id;
	    
	    $merchantId                 = url_decode($id);
	    $this->data['days'] = array();
	    // if cart is empty
		if(empty($this->data['booking_detail']))
		{
			
		    if(!empty($id))
		     {	
				$salon_selct                  = "SELECT id,business_name FROM st_users WHERE id=".$merchantId;
				$this->data['salon_detail']   = $this->user->custome_query($salon_selct,'row');
				$this->load->view('frontend/user/cart_empty',$this->data);
             }
            else redirect(base_url()); 			
		}
		else
		{
				
			foreach ($this->data['booking_detail'] as $key => $value)
			 {
				$serId[]       = $value->service_id;
				
				 if($value->stype==1)
						$totalDuration       = $totalDuration+$value->duration;
				 else{  
						$totalMin            = $value->duration+$value->buffer_time;   
						$totalDuration       = $totalDuration+$totalMin;
					 }	;
				
				
				//~ $tduraion      = $value->duration+$value->buffer_time;
				//~ $totalDuration = $totalDuration+$tduraion;
			 }
			
		$sql = $this->db->query("SELECT DISTINCT user_id,service_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$merchantId."");
		$uidsRes= $sql->result();
		//echo '<pre>'; print_r($uidsRes);
		if(!empty($uidsRes)){
			
               $users = array();
               
				foreach($uidsRes as $res)
				{
				 $users[$res->user_id][] = $res->service_id;
				}

	            $userids=array();
				foreach($users as $k=>$v)
				{
					$arraymatch = count(array_intersect($v, $serId)) == count($serId);
					if($arraymatch)
						$userids[] = $k;
				}		
					
           if(!empty($userids)){

           	 $time2  = $time;
			  $wherT = "";
			  if(!empty($totalDuration) && $ptime=='yes')
			  	{	//&& $ptime=='yes'
				    $newtimestamp = strtotime(''.$time.' +'.$totalDuration.' minute');
					$time2        = date('H:i:s', $newtimestamp);
					
					$wherT        = " AND ((`starttime`<='".$time."' AND `starttime`<='".$time2."' AND endtime>='".$time."' AND endtime>='".$time2."') OR (`starttime_two`<='".$time."' AND `starttime_two`<='".$time2."' AND endtime_two>='".$time."' AND endtime_two>='".$time2."'))";

					//  $select = "SELECT st_users.id,first_name,last_name,profile_pic,type,starttime,endtime,starttime_two,endtime_two,(SELECT AVG(rate) FROM st_review WHERE emp_id=st_users.id) as myrating,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users JOIN st_availability ON st_availability.user_id=st_users.id WHERE status='active' AND online_booking='1' AND days='".$dayName."' ".$wherT." AND type='open' AND access='employee' AND merchant_id=".$merchantId." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
					$select = $select = "SELECT st_users.id,first_name,last_name,profile_pic,(SELECT AVG(rate) FROM st_review WHERE emp_id=st_users.id) as myrating,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users WHERE status='active' AND online_booking='1' AND access='employee' AND merchant_id=".$merchantId." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
				}
				else
				{
					//    $select = "SELECT st_users.id,first_name,last_name,profile_pic,type,starttime,endtime,starttime_two,endtime_two,(SELECT AVG(rate) FROM st_review WHERE emp_id=st_users.id) as myrating,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users JOIN st_availability ON st_availability.user_id=st_users.id WHERE status='active' AND online_booking='1' AND days='".$dayName."' ".$wherT." AND type='open' AND access='employee' AND merchant_id=".$merchantId." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
					$select = "SELECT st_users.id,first_name,last_name,profile_pic,(SELECT AVG(rate) FROM st_review WHERE emp_id=st_users.id) as myrating,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users WHERE status='active' AND online_booking='1' AND  access='employee' AND merchant_id=".$merchantId." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
				} 
			   
			 $this->data['employee_list'] = $this->user->custome_query($select,'result');
			 if(!empty($_GET['employee_select']) && $_GET['employee_select']!="any")
			    {
				  	//$employeId = " AND employee_id=".url_decode($_GET['employee_select']);
				  	$avial_uid = url_decode($_GET['employee_select']);
				  	$select123 = "SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$avial_uid."";
				}
			 else
			  	{
				  if(!empty($this->data['employee_list']) && count($this->data['employee_list'])==1)
					{
					   //$employeId = " AND employee_id=".$this->data['employee_list'][0]->id;
					   $avial_uid = $this->data['employee_list'][0]->id;
					   $select123 = "SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$avial_uid."";
					}
					else
					{ 
						//$employeId   = " AND st_booking.merchant_id=".url_decode($id);
					    $avial_uid   = $merchantId;
					    
					    $selectStart = "SELECT MIN(starttime) as starttime,MIN(starttime_two) as starttime_two FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id!=".$avial_uid." AND created_by=".$avial_uid."";
					    
					   	$startTime             = $this->user->custome_query($selectStart,'row');
					    $this->data['dayslot'] = new stdClass();
					    
					    if(!empty($startTime->starttime))
					    	$this->data['dayslot']->starttime = $startTime->starttime; 
						if(!empty($startTime->starttime_two))
					    	$this->data['dayslot']->starttime_two = $startTime->starttime_two; 

					    $selectEnd = "SELECT MAX(endtime) as endtime,MAX(endtime_two) as endtime_two FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id!=".$avial_uid." AND created_by=".$avial_uid."";
					    
					     $endTime               =  $this->user->custome_query($selectEnd,'row');
					     
					    if(!empty($endTime->endtime))
						   $this->data['dayslot']->endtime = $endTime->endtime;
						if(!empty($endTime->endtime_two))
						   $this->data['dayslot']->endtime_two = $endTime->endtime_two;
					}
				  }	 
		      //~ if(!empty($employeId)){
                   //~ $selectBookingvailablity = "SELECT st_booking.id,employee_id,booking_time,booking_endtime,total_minutes,total_buffer,st_users.id as eid,st_availability.type FROM st_users INNER JOIN st_booking ON st_booking.employee_id=st_users.id INNER JOIN st_availability ON st_availability.user_id=st_users.id WHERE st_booking.status='confirmed' AND online_booking='1' AND days='".$dayName."' ".$wherT." AND type='open' AND st_users.status='active' AND st_booking.employee_id IN(".implode(',',$userids).") AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."'".$employeId." ORDER BY employee_id asc";
		        //~ $this->data['empBookSlot']  = $this->user->custome_query($selectBookingvailablity,'result');
		      //~ }
		      
		      $activeday            = $this->user->select('st_availability','days',array('user_id'=>$merchantId,'type'=>'close'));
		      
		      $days                 = array();
		      
		      if(!empty($activeday)) foreach($activeday as $row) $days[]=$row->days;
			  
			  $this->data['days']  = $days;
			  
			  if(!empty($select123))
			     $this->data['dayslot']         = $this->user->custome_query($select123,'row');
			    
			} else $this->data['employee_list'] = "";
			
		   } else $this->data['employee_list']  = "";

		  
		  $currentdate    = date('Y-m-d 00:00:00');
		  $threemonthaftr = date('Y-m-d 23:59:59',strtotime("+3 months", strtotime($currentdate)));
  //echo $currentdate.'='.$threemonthaftr; die;
		                            $this->db->group_by('booking_time');
		   $nationalholidaysdays  = $this->user->select('st_booking','booking_time',array('booking_time >='=>$currentdate,'booking_endtime <='=>$threemonthaftr,'merchant_id'=>$merchantId,'booking_type'=>'self','national_holiday'=>1));

          $nationalDays = array();

           if(!empty($nationalholidaysdays))
		   	 {
		   	 	foreach($nationalholidaysdays as $value) {
		   	 		$nationalDays[] = date('Y-m-d',strtotime($value->booking_time));
		   	 	}
		   	 }
		   
		    if(!empty($_GET['employee_select']) && $_GET['employee_select']!="any")
		      {
				 $employee_id = url_decode($_GET['employee_select']); 
			  $this->db->group_by('booking_time');
		     $blockdays  = $this->user->select('st_booking','booking_time',array('booking_time >='=>$currentdate,'booking_endtime <='=>$threemonthaftr,'employee_id'=>$employee_id,'booking_type'=>'self','blocked_type'=>'full'));
		   
			  }
			else{  
               $this->db->group_by('booking_time');
		   $blockdays  = $this->user->select('st_booking','booking_time',array('booking_time >='=>$currentdate,'booking_endtime <='=>$threemonthaftr,'merchant_id'=>$merchantId,'booking_type'=>'self','blocked_type'=>'full','block_for'=>1));
	     }
		 if(!$this->data['employee_list']) {
			
			$monday = new DateTime();
			$endDate = clone $monday;
			$endDate->modify('+90 days');
			$dateInterval = new DateInterval('P1D');
			$dateRange = new DatePeriod($monday, $dateInterval, $endDate);

			foreach ($dateRange as $day) {
				$nationalDays[] = $day->format('Y-m-d');
			}
		 }
		 if(!empty($this->data['employee_list']) && count($this->data['employee_list'])==1) {
			$employee_id = $this->data['employee_list'][0]->id; 

			$nonworkdays = $this->user->custome_query('select * from st_availability WHERE type="close" AND user_id='.$employee_id.' AND created_by='.$merchantId,'result');

			foreach($nonworkdays as $nonworkday) {
				$monday = new DateTime($nonworkday->days);
				$endDate = clone $monday;
				$endDate->modify('+90 days');
				$dateInterval = new DateInterval('P7D');
				$dateRange = new DatePeriod($monday, $dateInterval, $endDate);

				foreach ($dateRange as $day) {
					$nationalDays[] = $day->format('Y-m-d');
				}
			}
		 }
		 if(!empty($_GET['employee_select']) && $_GET['employee_select']!="any") {
			$employee_id = url_decode($_GET['employee_select']); 

			$nonworkdays = $this->user->custome_query('select * from st_availability WHERE type="close" AND user_id='.$employee_id.' AND created_by='.$merchantId,'result');

			foreach($nonworkdays as $nonworkday) {
				$monday = new DateTime($nonworkday->days);
				$endDate = clone $monday;
				$endDate->modify('+90 days');
				$dateInterval = new DateInterval('P7D');
				$dateRange = new DatePeriod($monday, $dateInterval, $endDate);

				foreach ($dateRange as $day) {
					$nationalDays[] = $day->format('Y-m-d');
				}
			}
		 }
//echo '<pre>'; print_r($blockdays); die;
         if(!empty($blockdays))
		   	 {
		   	 	foreach($blockdays as $value) {
		   	 		$nationalDays[] = date('Y-m-d',strtotime($value->booking_time));
		   	 	}
		   	 }
		   	 
		   	 $whereaas ="merchant_id=".$merchantId." AND booking_type='self' AND ((booking_time>='".$currentdate."' AND booking_time<'".$threemonthaftr."') OR (booking_endtime>'".$currentdate."' AND booking_endtime<='".$threemonthaftr."') OR (booking_time<='".$currentdate."' AND booking_endtime>'".$currentdate."') OR (booking_time>'".$currentdate."' AND booking_endtime<='".$threemonthaftr."'))";
		    
		    	     if(!empty($_GET['employee_select']) && $_GET['employee_select'] !='any'){
				         $eid = url_decode($_GET['employee_select']);
				          $this->db->where('employee_id',$eid);
				     }else{
						 $this->db->where('close_for',0);
						 }
		   	             $this->db->where($whereaas);
		                 $this->db->group_by('booking_time');
		   $closedays  = $this->user->select('st_booking','booking_time,booking_endtime',array('blocked_type'=>'close'));
		 // echo $this->db->last_query(); die;
		    if(!empty($closedays))
		   	 {
		   	 	foreach($closedays as $value) {
					$dates =getDatesFromRange(date('Y-m-d',strtotime($value->booking_time)),date('Y-m-d',strtotime($value->booking_endtime)));
					//$dates =getDatesFromRange('2020-07-23','2020-07-23');
					foreach($dates as $date){
						$nationalDays[] =$date;
						}
		   	 		// echo '<pre>'; print_r($dates);
		   	 		//$nationalDays[] = date('Y-m-d',strtotime($value->booking_time));
		   	 	}
		   	 }
    

		   $this->data['nationaldays']  = array_unique($nationalDays); 
		   
		   $this->data['merchant_id']  = $id;
		   $merchantId                 = url_decode($id);
		   $sTitle = "SELECT id,business_name FROM st_users WHERE id=".$merchantId;
		   $salon_title   = $this->user->custome_query($sTitle,'row');
		   $this->data['title'] = 'Buchung bei '. $salon_title->business_name;
		   $this->data['dayName'] = $dayName;
		   //echo '<pre>'; print_r($this->data); die;
		    
		   $isAjax = $_GET['is_ajax'];

		   if($isAjax){
				return $this->load->view('frontend/user/booking_details_content', $this->data);
		   }
		   
		  $this->load->view('frontend/user/booking_detail',$this->data);
		}
	}


	//** Remove Service From Cart **//
	public function remove_service()
	{
		    $usid    = $this->session->userdata('st_userid');
		
		if($this->booking->delete('st_cart',array('user_id' => $usid,'id' => $_POST['cart_id'])))
			$success = 1;
		else
			$success = 0;

			$field   = 'COUNT(*) as service, SUM(total_price) as tot_price';
			$data    = $this->booking->select_row('st_cart',$field,array('user_id'=>$this->session->userdata('st_userid')));
		echo json_encode(array('success' => $success,'count'=>$data->service,'total'=> $data->tot_price));
	}



	//** Booking Confirm **//
 public function booking_confirm()
	{
		
		if(empty($this->session->userdata('st_userid')) && $this->session->userdata('access')!='user')
		{
			echo json_encode(array('success'=>0, 'url' =>base_url('auth/login'))); die;
		}else
		{
			if($this->session->userdata('access') == 'user')
			{
				$usid  = $this->session->userdata('st_userid');
				$whrCheck =	'id='.url_decode($_POST['merch_id']).' AND (online_booking=0 OR status !="active")';			
				if($this->booking->countResult('st_client_block',array('client_id'=>$usid,'merchant_id'=> url_decode($_POST['merch_id'])))>0){
					   echo json_encode(array('success'=>0, 'url' =>'','message'=>'Du bist aktuell nicht berechtigt, Buchungen in diesem Salon zu erstellen.')); die;
				}				
				else if(($this->booking->countResult('st_booking',array('user_id'=>$usid,'status' => 'completed')) == 0) && ($this->booking->countResult('st_booking',array('user_id'=>$usid,'status'=>'confirmed')) > 2))
				{
					echo json_encode(array('success'=>0, 'url' =>'','message'=>'Zum Schutz unserer Salons wird Ihr Profil bei styletimer erst für weitere Buchungen freigeschaltet, nachdem mindestens eine Buchung erfolgreich vom Salon abgeschlossen wurde. Danach können Sie beliebig viele Buchungen gleichzeitig vornehmen.')); die;
				}				
				else if($this->booking->countResult('st_users',$whrCheck) > 0)
				{
					 echo json_encode(array('success' =>0,'url' =>'', 'message' => 'Der Salon ist leider nicht mehr bei styletimer verfügbar'));  die;
				}
		    }

			if($_POST['employee_select']=='any')
				$empId = $_POST['employee_select'];
			else		 
				$empId = url_decode($_POST['employee_select']);
			if(isset($_POST['date']) && $_POST['date']!="")
			{
				$date    = date('Y-m-d',strtotime($_POST['date']));
				$dayName = date("l", strtotime($_POST['date']));
				$dayName = strtolower($dayName);
			}
			else
			{
				$date    = date('Y-m-d');
				$dayName = date("l", strtotime($date));
				$dayName = strtolower($dayName);
			}  
					
		
		$sqlForservice   = "SELECT `st_cart`.`id`,`st_cart`.`merchant_id`,`st_cart`.`service_id`,`st_category`.`category_name`, `name`,`buffer_time`, st_merchant_category.duration,`st_merchant_category`.`type` as stype,price_start_option,setuptime,processtime,finishtime,`price`, `discount_price`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime`,`parent_service_id`,(SELECT notification_time FROM st_users WHERE st_users.id=st_cart.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_cart.merchant_id) as additional_notification_time FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$this->session->userdata('st_userid')."";
		
	    $booking_detail = $this->user->custome_query($sqlForservice,'result');
	    
	    //echo "<pre>"; print_r($booking_detail); die;
	    
	    if(!empty($booking_detail)){
			if($empId=='any')
			  {
				if(empty($booking_detail))
				{
					echo json_encode(array('success'=>0, 'url' =>base_url()));
			    }
				else
				{
					 $timeArray        = array();                           
					 $ikj              = 0;
					 $strtodatyetime   = $_POST['date']." ".$_POST['time'];
					 //echo $strtodatyetime."===".$tNow."===".date('Y-m-d H:i:s',$tNow); die;				     
					foreach($booking_detail as $row){
					
					    $serId[]               = $row->service_id;
					    
						$timeArray[$ikj]        = new stdClass;
						
						$bkstartTime            = $strtodatyetime;
						$timeArray[$ikj]->start = $bkstartTime; 
						
					   if($row->stype==1){
							//$totaldurationTim=$totaldurationTim+$row->duration;
														   
							$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
							$timeArray[$ikj]->end   = $bkEndTime;							    	
							$ikj++;	
							
							$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
							$timeArray[$ikj]        = new stdClass;
							$timeArray[$ikj]->start = $finishStart;
							
							$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
							$timeArray[$ikj]->end   = $finishEnd;
							$ikj++;
							
							$strtodatyetime=$finishEnd;
														
					   }else{
							$totalMin               = $row->duration+$row->buffer_time;   
							
							$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
							$timeArray[$ikj]->end   = $bkEndTime;							    	
							$ikj++;	
							
							//$totaldurationTim       = $totaldurationTim+$totalMin;
							
							$strtodatyetime=$bkEndTime;							   
					   } 
					
				     }
					
					//~ foreach ($booking_detail as $key => $value)
					//~ {
						//~ $serId[] = $value->service_id;
					//~ }
					$id       =  $booking_detail[0]->merchant_id;
					$sql      =  $this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$id."");
			
					$uidsRes  = $sql->result();
					if(!empty($uidsRes))
					  {
						 $users = array();
						 foreach($uidsRes as $res)
						 {
							$users[$res->user_id][] = $res->service_id;
						 }

						$userids=array();
						
						foreach($users as $k=>$v)
						{
							$arraymatch  = count(array_intersect($v, $serId)) == count($serId);
							if($arraymatch)
								$userids[]=$k;
						}		
								
					    if(!empty($userids))
					    {
						   $select = "SELECT id,first_name,last_name,profile_pic,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users WHERE status='active' AND online_booking='1' AND access='employee' AND merchant_id=".$id." AND id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
						   
						    $employee=$this->user->custome_query($select,'result');
						   // echo '<pre>'; print_r($timeArray);
						    
						    if(!empty($employee))
						     {
							   $reqtime     = $_POST['date']." ".$_POST['time'];
                               $chePastTime = date('Y-m-d H:i:s',strtotime($reqtime)); 
                               
						       $k=0;$l=0;
						       
                              foreach($employee as $emp)
                            	{
									$resultCheckSlot = checkTimeSlotsMerchant($timeArray,$emp->id,$id,$_POST['totalDuration']);
									//echo $emp->id; 
									if($resultCheckSlot==true)
									   {
										
										 $empId = $emp->id;
										 $k     = 1;
										 
										}
									if($k==1) break;	
							  		  
							 }
						//echo $empId;	die;								
								if(!empty($empId) && $empId!="any")
									{
						              $book_Arr    = array();
									  $user_detail = $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$this->session->userdata('st_userid')));
									  
									  $total_price=$total_buffer=$total_min=$total_dis=$i=0;
									  
									  $bk_time    = $_POST['date']." ".$_POST['time'];
								
									foreach($booking_detail as $row)
									 {
											
										$total_buffer = $row->buffer_time+$total_buffer;
										$total_min    = $row->duration+$total_min;
										
										if ($row->parent_service_id) {
											$pstime = $this->booking->select(
												'st_offer_availability',
												'starttime,endtime,days,type',
												array(
												'service_id'=>$row->parent_service_id,
												'days' => $dayName
												)
											);
											if ($pstime) {
												$row->starttime = $pstime[0]->starttime;
												$row->endtime = $pstime[0]->endtime;
												$row->type = $pstime[0]->type;
												$row->days = $pstime[0]->days;
											}
										}

										if(!empty($row->type) && $row->type=='open')
										{ 
											if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
											  {	  
											   if(!empty($row->discount_price))
											   {	  
											     $total_dis     = ($row->price-$row->discount_price)+$total_dis;
											     $total_price   = $row->discount_price+$total_price;  
											    }
											   else
											    {
												   $total_price = $row->price+$total_price;  
												} 
											  }
											  else $total_price = $row->price+$total_price;  
										   }
										   else $total_price    = $row->price+$total_price; 
									}  


									$min          = $total_buffer+$total_min;
									$newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
									$book_end     = date('Y-m-d H:i:s', $newtimestamp);
									
									//notification set time
									$notif_time   = $booking_detail[0]->notification_time;
									$ad_notif_time   = $booking_detail[0]->additional_notification_time;

									$timestamp    = strtotime($bk_time);
									$time         = $timestamp - ($notif_time * 60 * 60);
									$ad_time 	  = $timestamp - ($ad_notif_time * 60 * 60);

									// Date and time after subtraction
									$notif_date   = date("Y-m-d H:i:s", $time);
									if($ad_notif_time != '0'){
										$ad_notif_date   = date("Y-m-d H:i:s", $ad_time);
										$book_Arr['additional_notification_date'] = $ad_notif_date;
									}


									$book_Arr['user_id']      	   = $this->session->userdata('st_userid');
									$book_Arr['merchant_id']  	   = $booking_detail[0]->merchant_id;
									$book_Arr['employee_id']  	   = $empId;
									$book_Arr['book_id']  	       = get_last_booking_id($booking_detail[0]->merchant_id);
									$book_Arr['total_time']   	   = $min;
									$book_Arr['booking_time'] 	   = $bk_time;
									$book_Arr['booking_endtime']   = $book_end;
									$book_Arr['total_minutes']     = $total_min;
									$book_Arr['total_buffer']      = $total_buffer;
									$book_Arr['total_price']       = $total_price;
									$book_Arr['total_discount']    = $total_dis;
									$book_Arr['total_time']        = $total_min+$total_buffer;
									
									if(isset($_POST['user_booking_note']))
					 					$book_Arr['notes']         = $_POST['user_booking_note'];
																   
									$book_Arr['pay_status']        = 'cash';
									$book_Arr['status']            = 'confirmed';
									$book_Arr['notification_date'] = $notif_date;
									$book_Arr['created_by']        = $this->session->userdata('st_userid');
									$book_Arr['created_on']        = date('Y-m-d H:i:s');
									$book_Arr['updated_by']        = $this->session->userdata('st_userid');
									$book_Arr['updated_on']        = date('Y-m-d H:i:s');
									
									$tid = $this->user->insert('st_booking',$book_Arr);
									$boojkstartTime=$bk_time;
									if($tid)
									{
										foreach($booking_detail as $row)
										{   
											$detail_Arr = [];
											$detail_Arr['mer_id']           = $booking_detail[0]->merchant_id;
											$detail_Arr['emp_id']           = $empId;
											$detail_Arr['service_type']     = $row->stype;
											if ($row->buffer_time > 0)
												$detail_Arr['has_buffer']   = 1;

											if($row->stype==1){
												
													$detail_Arr['setuptime']        = $row->setuptime;
													$detail_Arr['processtime']      = $row->processtime;
													$detail_Arr['finishtime']       = $row->finishtime;
													$detail_Arr['setuptime_start']  = $boojkstartTime;										 
																								
													$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$row->setuptime.' minute'));
													$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$row->processtime.' minute'));
													$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
													
													$detail_Arr['setuptime_end']    = $setuEnd;	
													$detail_Arr['finishtime_start'] = $finishStart;	
													$detail_Arr['finishtime_end']   = $finishEnd;
																									
													$boojkstartTime                 = $finishEnd;
											    
										    }else{
												$totalMin                       = $row->duration+$row->buffer_time;
												
												$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
												$detail_Arr['setuptime_start']  = $boojkstartTime;	
												$detail_Arr['setuptime_end']    = $setuEnd;	
												
												$boojkstartTime                 = $setuEnd;
											  }
											
											
											$detail_Arr['booking_id']       = $tid;
											$detail_Arr['service_id']       = $row->service_id;
											if(!empty($row->name))
												$detail_Arr['service_name'] = $row->name;
											else
												$detail_Arr['service_name'] = $row->category_name;
												
											if(!empty($row->type) && $row->type=='open')
											{ 
												if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
									          	{		  
													 if(!empty($row->discount_price))
													 {
														$detail_Arr['price']          = $row->discount_price;
														$detail_Arr['discount_price'] = $row->price-$row->discount_price;
													 }  
													    else $detail_Arr['price']     = $row->price;
												}
												else $detail_Arr['price']             = $row->price;
											}
											else $detail_Arr['price']                 = $row->price;
											         

											$detail_Arr['duration']                   = $row->duration + $row->buffer_time;
											$detail_Arr['buffer_time']                = $row->buffer_time;
											$detail_Arr['created_on']                 = date('Y-m-d H:i:s');
											$detail_Arr['user_id']                    = $this->session->userdata('st_userid');
											$detail_Arr['created_by']                 = $this->session->userdata('st_userid');
											
											$this->user->insert('st_booking_detail',$detail_Arr);
												

										}  
										
										$this->booking->delete('st_cart',array('user_id' => $this->session->userdata('st_userid')));
										
										$this->data['main'] = "";
										///mail section
										$this->data['main'] = $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$tid),'st_booking.id,business_name,st_users.email,st_users.first_name as merchant_name,total_time,employee_id,st_users.salon_email_setting,booking_time,st_booking.merchant_id,book_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
										if(!empty($this->data['main']))
										{

										  $field                        = "st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";  
										  $whr                          = array('booking_id'=>$tid);
										  $this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','created_by','id',$whr,$field);
										  $this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

											$body_msg = str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_confirm_body"));
											$MsgTitle = $this->lang->line("booking_confirm_title");
											if($this->data['main'][0]->user_notify != 0)
											{
											 sendPushNotification($this->session->userdata('st_userid'),array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $tid, 'booking_status' => 'confirmed','click_action' => 'BOOKINGDETAIL'));
											}
												
											$empDat = is_mail_enable_for_user_action($this->data['main'][0]->employee_id);
											if ($empDat) {
												$tmp = $this->data;
												$tmp['main'][0]->first_name = $empDat->first_name;
												$message2 = $this->load->view('email/service_booking_merchant',$tmp, true);
												emailsend($empDat->email,'styletimer - Neue Buchung',$message2,'styletimer');
											}
										}

										$message = $this->load->view('email/service_booking',$this->data, true);
										$this->data['main'][0]->first_name = $this->data['main'][0]->merchant_name;
										$message1 = $this->load->view('email/service_booking_merchant',$this->data, true);
										$mail    = emailsend($user_detail->email,'styletimer - Buchung bestätigt',$message,'styletimer');
										if($this->data['main'][0]->salon_email_setting==1){
											$mail    = emailsend($this->data['main'][0]->email,'styletimer - Neue Buchung',$message1,'styletimer');
										}

										echo json_encode(array('success'=>1, 'url' =>'booking/thankyou'));
											
										}
									else
									{
									  echo json_encode(array('success'=>0, 'url' =>'booking/booking_detail/'.url_encode($booking_detail[0]->merchant_id)));
									}
						        }
						        else  
						        	echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
					
						    }  else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
						 
					} 
					else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
						
				} 
				else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee not available for this time')); die;
			   }
			}	  
			else{
				$bookTrue    = 0;
				$reqtime     = $_POST['date']." ".$_POST['time'];
		        $chePastTime = date('Y-m-d H:i:s',strtotime($reqtime)); 
		        
		        
		        $total_price = $total_buffer=$total_min=$total_dis=$i=0;
				$bk_time     = $_POST['date']." ".$_POST['time'];
				
				$timeArray        = array();                           
				$ikj              = 0;
				$strtodatyetime   = $_POST['date']." ".$_POST['time'];
									
				foreach($booking_detail as $row)
				{
					$timeArray[$ikj]        = new stdClass;
					
					$bkstartTime            = $strtodatyetime;
					$timeArray[$ikj]->start = $bkstartTime; 
						
				   if($row->stype==1){
						//$totaldurationTim=$totaldurationTim+$row->duration;
													   
						$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
						$timeArray[$ikj]->end   = $bkEndTime;							    	
						$ikj++;	
						
						$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
						$timeArray[$ikj]        = new stdClass;
						$timeArray[$ikj]->start = $finishStart;
						
						$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
						$timeArray[$ikj]->end   = $finishEnd;
						$ikj++;
						
						$strtodatyetime=$finishEnd;
						
						$total_min    = $row->duration+$total_min;	
													
				   }else{
						$totalMin               = $row->duration+$row->buffer_time;   
						
						$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
						$timeArray[$ikj]->end   = $bkEndTime;							    	
						$ikj++;	
					
						$strtodatyetime=$bkEndTime;		
						
						$total_buffer = $row->buffer_time+$total_buffer;
						$total_min    = $row->duration+$total_min;					   
				   } 

					if(!empty($row->type) && $row->type=='open')
					{ 
						if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
							{	  
							if(!empty($row->discount_price)){	  
								$total_dis     = ($row->price-$row->discount_price)+$total_dis;
								$total_price   = $row->discount_price+$total_price;  
							}
							else
							{
								$total_price = $row->price+$total_price;  
							} 
						}
							else $total_price  = $row->price+$total_price;  
					}
					else
						$total_price        = $row->price+$total_price; 
				}  
		        
		        $resultCheckSlot = checkTimeSlotsMerchant($timeArray,$empId,$booking_detail[0]->merchant_id,$_POST['totalDuration']);
			 
				
				if($resultCheckSlot==true)
				{
					$book_Arr    = array();
					$user_detail = $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$this->session->userdata('st_userid')));

					

					$min          = $total_buffer+$total_min;
					$newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
					
					//notification set time
					$book_end     = date('Y-m-d H:i:s', $newtimestamp);
					$notif_time   = $booking_detail[0]->notification_time;
					$ad_notif_time   = $booking_detail[0]->additional_notification_time;
					$timestamp    = strtotime($bk_time);
					$time         = $timestamp - ($notif_time * 60 * 60);
					$ad_time         = $timestamp - ($ad_notif_time * 60 * 60);
					// Date and time after subtraction
					$notif_date   = date("Y-m-d H:i:s", $time);

					if($ad_notif_time != '0'){
						$ad_notif_date   = date("Y-m-d H:i:s", $ad_time);
						$book_Arr['additional_notification_date'] = $ad_notif_date;
					}

					$book_Arr['user_id']           = $this->session->userdata('st_userid');
					$book_Arr['merchant_id']       = $booking_detail[0]->merchant_id;
					$book_Arr['employee_id']       = $empId;
					$book_Arr['book_id']  	       = get_last_booking_id($booking_detail[0]->merchant_id);
					$book_Arr['total_time']        = $min;
					$book_Arr['booking_time']      = $bk_time;
					$book_Arr['booking_endtime']   = $book_end;
					$book_Arr['total_minutes']     = $total_min;
					$book_Arr['total_buffer']      = $total_buffer;
					$book_Arr['total_price']       = $total_price;
					$book_Arr['total_discount']    = $total_dis;
					$book_Arr['total_time']        = $total_min+$total_buffer;
					
					if(isset($_POST['user_booking_note']))
					 	$book_Arr['notes']         = $_POST['user_booking_note'];
												   
					$book_Arr['pay_status']        = 'cash';
					$book_Arr['status']            = 'confirmed';
					$book_Arr['notification_date'] = $notif_date;
					$book_Arr['created_by']        = $this->session->userdata('st_userid');
					$book_Arr['created_on']        = date('Y-m-d H:i:s');
					$book_Arr['updated_by']        = $this->session->userdata('st_userid');
					$book_Arr['updated_on']        = date('Y-m-d H:i:s');
				
					$tid=$this->user->insert('st_booking',$book_Arr);
					$boojkstartTime=$bk_time;
					if($tid)
					{
						foreach($booking_detail as $row)
						{
							$detail_Arr = [];
							$detail_Arr['mer_id']           = $booking_detail[0]->merchant_id;
							$detail_Arr['emp_id']           = $empId;
							$detail_Arr['service_type']     = $row->stype;
							if ($row->buffer_time > 0)
								$detail_Arr['has_buffer']   = 1;		

							if($row->stype==1){
								
									$detail_Arr['setuptime']        = $row->setuptime;
									$detail_Arr['processtime']      = $row->processtime;
									$detail_Arr['finishtime']       = $row->finishtime;
									$detail_Arr['setuptime_start']  = $boojkstartTime;										 
																				
									$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$row->setuptime.' minute'));
									$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$row->processtime.' minute'));
									$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
									
									$detail_Arr['setuptime_end']    = $setuEnd;	
									$detail_Arr['finishtime_start'] = $finishStart;	
									$detail_Arr['finishtime_end']   = $finishEnd;
																					
									$boojkstartTime                 = $finishEnd;
								
							}else{
								$totalMin                       = $row->duration+$row->buffer_time;
								
								$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
								$detail_Arr['setuptime_start']  = $boojkstartTime;	
								$detail_Arr['setuptime_end']    = $setuEnd;	
								
								$boojkstartTime                 = $setuEnd;
							  }
											
							$detail_Arr['booking_id'] = $tid;
							$detail_Arr['service_id'] = $row->service_id;
                                
                        	if(!empty($row->name))
								$detail_Arr['service_name'] = $row->name;
						    else
								$detail_Arr['service_name'] = $row->category_name;
                                
							if(!empty($row->type) && $row->type=='open')
							{ 
								if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
								{		  
									if(!empty($row->discount_price))
									{
										$detail_Arr['price']           = $row->discount_price;
										$detail_Arr['discount_price']  = $row->price-$row->discount_price;
									}  
									else $detail_Arr['price']          = $row->price;
							  	}
								else $detail_Arr['price']              = $row->price;
						    }
							else 
								$detail_Arr['price']                   = $row->price;

							$detail_Arr['duration']                = $row->duration+$row->buffer_time;
							$detail_Arr['buffer_time']             = $row->buffer_time;
							$detail_Arr['created_on']              = date('Y-m-d H:i:s');
							$detail_Arr['user_id']                 = $this->session->userdata('st_userid');
							$detail_Arr['created_by']              = $this->session->userdata('st_userid');
							
							$this->user->insert('st_booking_detail',$detail_Arr);
						}  
						$this->booking->delete('st_cart',array('user_id' => $this->session->userdata('st_userid')));
						
						$this->data['main'] = "";
						
						///mail section
						$this->data['main'] = $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$tid),'st_booking.id,business_name,st_users.email,st_users.first_name as merchant_name,st_users.salon_email_setting,employee_id,total_time,book_id,booking_time,st_booking.merchant_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
						
						if(!empty($this->data['main']))
						{

							$field  = "st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";  
							$whr    = array('booking_id'=>$tid);
							
							$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','created_by','id',$whr,$field);
							$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

							$body_msg = str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_confirm_body"));
							$MsgTitle = $this->lang->line("booking_confirm_title");
							if($this->data['main'][0]->user_notify != 0)
							{
								sendPushNotification($this->session->userdata('st_userid'),array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $tid, 'booking_status' => 'confirmed','click_action' => 'BOOKINGDETAIL'));
							}

							$empDat = is_mail_enable_for_user_action($this->data['main'][0]->employee_id);
							if ($empDat) {
								$tmp = $this->data;
								$tmp['main'][0]->first_name = $empDat->first_name;
								$message2 = $this->load->view('email/service_booking_merchant',$tmp, true);
								emailsend($empDat->email,'styletimer - Neue Buchung',$message2,'styletimer');
							}

						}

						$message = $this->load->view('email/service_booking',$this->data, true);
						$this->data['main'][0]->first_name = $this->data['main'][0]->merchant_name;
						$message1 = $this->load->view('email/service_booking_merchant',$this->data, true);
						
						$mail    = emailsend($user_detail->email,'styletimer - Buchung bestätigt',$message,'styletimer');
						
						if($this->data['main'][0]->salon_email_setting==1){
							  $mail    = emailsend($this->data['main'][0]->email,'styletimer - Neue Buchung',$message1,'styletimer');
							}

						echo json_encode(array('success'=>1, 'url' =>'booking/thankyou'));
					}
					else
						echo json_encode(array('success'=>0, 'url' =>'booking/booking_detail/'.url_encode($booking_detail[0]->merchant_id)));
			} 
			else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Employee is not available for selected time period')); die;
           } 
             
	    }
	    else
	    	echo json_encode(array('success'=>0, 'url' =>''));
		}
	}
	
	//** Confirm Booking **// 
	public function confirmed($id="")
	{
		if(empty($this->session->userdata('st_userid')))
		{
			redirect(base_url('auth/login'));
		}
		if($id!="")
		{
			$bid                = url_decode($id);
			$this->data['main'] = $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$bid),'business_name,st_booking.created_on');
			if(!empty($this->data['main']))
			{
				$field  = "st_users.id,first_name,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price";  
				$whr    = array('booking_id'=>$bid);
				
		    	$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','created_by','id',$whr,$field);
		    	$this->load->view('frontend/user/booking_confirm',$this->data);
		    }
	    else
	    	redirect(base_url());
		}
		else
			redirect(base_url());
	}

	//** Booking Cancel **//
	public function booking_cancel()
	{
		$id    = url_decode($_POST['book_id']);
		$check = $this->booking->select_row('st_booking','booking_time,merchant_id',array('id'=>$id));
		if(!empty($check))
		{
			//~ $checkAllow = $this->booking->select_row('st_users','cancel_booking_allow,hr_before_cancel',array('id'=>$check->merchant_id));
			
			//~ if(!empty($checkAllow) && $checkAllow->cancel_booking_allow=='yes' && $check->booking_time > date('Y-m-d H:i:s',strtotime('+'.$checkAllow->hr_before_cancel.' hours')))
			  //~ {
				 //~ echo json_encode(array('success'=>0,'msg' => 'You can not cancel this booking.' ,'id' =>$id));
				 //~ die;
				  
			  //~ }
		   //~ else if(!empty($checkAllow) && $checkAllow->cancel_booking_allow=='no'){
			    //~ echo json_encode(array('success'=>0,'msg' => 'This salon not allow cancel booking.' ,'id' =>$id));
				//~ die;			   
			  //~ }  			
		    if(strtotime($check->booking_time) < strtotime(date('Y-m-d H:i:s')))
			{
				echo json_encode(array('success'=>0,'msg' => 'Booking time out unable to cancel' ,'id' =>$id));
				 die;
			}
			
		}
		$usid = $this->session->userdata('st_userid');
		$acc  = $this->session->userdata('access');
		$res  = isset($_POST['reason'])?$_POST['reason']:'vom Salon storniert';
		if ($res == 'Other') $res = 'andere';
		if($this->booking->update('st_booking',array('status' => 'cancelled','updated_by' => $usid,'updated_on' =>date('Y-m-d H:i:s'),'reason' => $res),array('id'=>$id)))
		{
			$field = 'st_booking.id,user_id,booking_time,total_time,st_booking.merchant_id,first_name,last_name,book_id,st_users.email,st_booking.booking_type,st_booking.fullname,st_booking.email as guestemail,employee_id,(select business_name from st_users where st_users.id = st_booking.merchant_id) as salon_name,(select first_name from st_users where st_users.id = st_booking.merchant_id) as merchant_name,(select email from st_users where st_users.id=st_booking.merchant_id AND salon_email_setting=1) as m_email,st_users.notification_status';
			$info  = $this->booking->join_two('st_booking','st_users','user_id','id',array('st_booking.id'=>$id),$field);
			if(!empty($info))
			{
				if($info[0]->user_id==$usid){
					$body_msg=str_replace('*salonname*',$info[0]->salon_name,$this->lang->line("booking_cancelled_body_user"));
				  }
				else{   
					$body_msg=str_replace('*salonname*',$info[0]->salon_name,$this->lang->line("booking_cancelled_body"));
			    }
				$MsgTitle=$this->lang->line("booking_cancelled_title");
				
				if($info[0]->booking_type != 'guest' && $info[0]->notification_status != 0)
				{
					sendPushNotification($info[0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$info[0]->merchant_id ,'book_id'=> $id,'booking_status' =>'cancelled','click_action' => 'BOOKINGDETAIL'));
				}
				
				$time = new DateTime($info[0]->booking_time);
	            $date = $time->format('d.m.Y');
	            $time = $time->format('H:i');
	            $bookingDate = $date;

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
				if($acc == 'user')
				{
					$insertArr = array("booking_id" => $id ,"status" => "cancel","merchant_id" => $check->merchant_id,"created_by" => $usid,"created_on" => date('Y-m-d H:i:s'));
					$this->user->insert('st_booking_notification',$insertArr);				
				}
				$message = '';
				if ($acc == 'user') {
					$message = $this->load->view('email/booking_cancel',array("fname"=>$first_name,"lname"=> $last_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,"booking_id" => $id,'book_id'=>$info[0]->book_id, "duration"=>$info[0]->total_time), true);
				} else {
					$message = $this->load->view('email/booking_cancel_by_merchant',array("fname"=>$first_name,"lname"=> $last_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,"booking_id" => $id,'book_id'=>$info[0]->book_id, "duration"=>$info[0]->total_time), true);
				}
				

				$mail = emailsend($emailsend,$this->lang->line("styletimer_booking_cancel"),$message,'styletimer');  
			   
			   $m_name   = $this->session->userdata('sty_fname');
			   
				if(!empty($info[0]->m_email) && $acc == 'user') 
				{		
					$message2 = $this->load->view('email/booking_cancel_salon',array("fname"=>$first_name,"lname"=> $last_name,"merchant_name" => $info[0]->merchant_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,'access' => $acc,'emp_name' =>$m_name,"booking_id" => $id,'book_id'=>$info[0]->book_id, 'duration'=>$info[0]->total_time), true);				
					
					$mail = emailsend($info[0]->m_email,$this->lang->line("styletimer_booking_cancel"),$message2,'styletimer');
				}
				if ($acc != 'user') {
					$empDat = is_mail_enable_for_merchant_action($info[0]->employee_id);
					if ($empDat) {
						$message2 = $this->load->view('email/booking_cancel_employee_by_merchant',array("fname"=>$first_name,"lname"=> $last_name,"merchant_name" => $empDat->first_name,"employee_name"=>$empDat->first_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,'access' => $acc,'emp_name' =>$m_name,"booking_id" => $id,'book_id'=>$info[0]->book_id, 'duration'=>$info[0]->total_time), true);
						emailsend($empDat->email,$this->lang->line('styletimer_booking_cancel'),$message2,'styletimer');
					}
					//$this->session->set_flashdata('success','Booking has been successfully cancelled.');
				} else {
					$empDat = is_mail_enable_for_user_action($info[0]->employee_id);
					if ($empDat) {
						$message2 = $this->load->view('email/booking_cancel_employee_by_user',array("fname"=>$first_name,"lname"=> $last_name,"merchant_name" => $empDat->first_name,"employee_name"=>$empDat->first_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,'access' => $acc,'emp_name' =>$m_name,"booking_id" => $id,'book_id'=>$info[0]->book_id, 'duration'=>$info[0]->total_time), true);
						emailsend($empDat->email,$this->lang->line('styletimer_booking_cancel'),$message2,'styletimer');
					}
				}

				if(isset($bookingDate) && $bookingDate !="") {
					$_SESSION['cancelled_booking_date_value'] = $bookingDate;
				}

			}
			echo json_encode(array('success'=>1, 'id' =>$id));
		}
		else{
			//$this->session->set_flashdata('error','Something is wrong. Try again')
			echo json_encode(array('success'=>0,'msg' => 'Sorry Unable to process...!','id' =>''));
		}
	}

	//** Rebooking **//
	public function rebooking()
	{
		$insertArr = array();
		$usid      = $this->session->userdata('st_userid');
		$id        = url_decode($_POST['id']);
		
		$mid       = url_decode($_POST['mid']); 
		
		$service   = $this->booking->select('st_booking_detail','service_id',array('booking_id' =>$id));
		


		$status = 0;
		$msg    = "";
		if(!empty($service))
		{
			if(!empty($usid))
			{
			 $this->booking->delete('st_cart',array('merchant_id !='=>$mid,'user_id'=>$usid));
			}
			foreach($service as $serv)
			{
				$service_det = $this->booking->select_row('st_merchant_category','id,price,created_by',array('id'=>$serv->service_id));
				if(!empty($service_det))
				 {
					if($this->booking->get_datacount('st_cart',array('user_id' => $usid,'service_id' => $service_det->id)) == 0)
					{
					 $insertArr['service_id']   = $service_det->id;
			      	 $insertArr['user_id']      = $usid;
			      	 $insertArr['total_price']  = $service_det->price;
			      	 $insertArr['merchant_id']  = $service_det->created_by;
			       	 $insertArr['created_by']   = $usid;
			      	 $insertArr['created_on']   = date('Y-m-d H:i:s');
			      	 
					 if($this->user->insert('st_cart',$insertArr))
						$status =1;
					 }
				else $status =1;
					   	
				}
			}
		}
		else 
		   $msg = 'Any service not have in this booking.';

		echo json_encode(['status'=>$status,'msg'=>$msg]);
	}


	//** Booking Detail **//
	public function detail($id="")
	{
		if(empty($this->session->userdata('st_userid')))
		{
			redirect(base_url('auth/login').'?detailids='.$id);
		}
	 if($_POST['id']!="")
		{
			$bid = url_decode($_POST['id']);
			//print_r($bid);
			$this->data['main'] = $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$bid),
			'st_booking.id,st_booking.book_id,employee_id,
			st_booking.invoice_id,st_booking.merchant_id,
			st_booking.google_event_id,business_name,st_users.address,st_users.city,
			st_users.zip,st_booking.booking_time,st_booking.email,st_booking.total_price,
			st_booking.updated_on,st_booking.total_minutes,st_booking.book_by,
			st_booking.user_id as userid,st_booking.notes as booknotes,st_booking.created_on,
			st_booking.updated_on,st_booking.created_by,st_booking.employee_id,
			st_booking.status,st_booking.booking_type,st_booking.fullname,
			st_booking.gender,st_booking.walkin_customer_notes,st_booking.notes,st_booking.email as guestemail,
			st_booking.reason,st_booking.contact_number as guestphone,
			(select status from st_users where id=st_booking.user_id) as statusUser,
			(select first_name from st_users where id=st_booking.employee_id) as first_name,
			(select last_name from st_users where id=st_booking.employee_id) as last_name');
		    // echo $this->db->last_query();die;
		if(!empty($this->data['main']))
			{
			$field = "st_users.id,st_users.status,st_booking_detail.id as bid,st_booking_detail.user_id,first_name,last_name,profile_pic,mobile,service_type,service_name,duration,setuptime,processtime,finishtime,price,buffer_time,has_buffer,discount_price,email,gender, address,city,country,zip,service_id,(select price_start_option from st_merchant_category WHERE id=st_booking_detail.service_id) as price_start_option,(select notes from st_usernotes WHERE user_id=st_users.id AND created_by = ".$this->data['main'][0]->merchant_id.") as notes, (select st_usernotes.id from st_usernotes WHERE user_id=st_users.id AND created_by = ".$this->data['main'][0]->merchant_id.") as noteid";  
			
			$whr   = array('booking_id'=>$bid);
    		$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field); 
			//created_by
	    	$this->data['review']    =$this->booking->select_row('st_review','id,rate,review,anonymous,merchant_id,created_on',array('booking_id'=>$bid));
	    	$sql2="SELECT `subcategory_id` FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` WHERE `r`.`status` = 'active' AND `r`.`created_by`=".$this->data['main'][0]->merchant_id." GROUP BY `r`.`subcategory_id` LIMIT 4";
	    	$this->data['all_service']=$this->user->custome_query($sql2,'result');
			//echo '<pre>';print_r($this->data);die();
			$html = $this->load->view('frontend/common/booking_detail_popup',$this->data,true);
			
			$reviehtml ="";
			
			if(empty($this->data['review']) && $this->session->userdata('access')=='user' && $this->data['main'][0]->status=='completed'){
				
				if($this->data['booking_detail'][0]->profile_pic !='')
				  $img=base_url('assets/uploads/users/').$this->data['booking_detail'][0]->id.'/icon_'.$this->data['booking_detail'][0]->profile_pic;
			  else
				  $img=base_url('assets/frontend/images/user-icon-gret.svg');
                 
                 $us_name = $this->data['booking_detail'][0]->first_name.' '.$this->data['booking_detail'][0]->last_name;
                              
				$reviehtml ='<div class="display-ib vertical-middle">
                      <img src="'.$img.'" class="width40v border-radius50 mr-3">
                    </div>
                    <div class="display-ib vertical-middle">
                      <p class="font-size-16 color333 fontfamily-medium mb-1">'.$us_name.'</p>
                      <p class="fontfamily-regular font-size-14 color666 mb-0">'.$this->data['main'][0]->business_name.'</p>
                    </div>
                    <form id="frmReview" method="post" action="'.base_url("booking/review").'">                    
                    <div class="form-group mb-30 pt-3 mt-3" style="border-top: 1px solid #c4c4c4;">
                      <fieldset class="rating vertical-sub" style="" >
                        <input type="radio" id="star-5" name="rating" value="5">
                        <label class="rating" for="star-5" title="">
                          <i class="fas fa-star"></i>
                        </label>
                        
                        <input type="radio" id="star-4" name="rating" value="4">
                        <label class="rating" for="star-4" title="">
                          <i class="fas fa-star"></i>
                        </label>
                        
                        <input type="radio" id="star-3" name="rating" value="3">
                        <label class="rating" for="star-3" title="">
                          <i class="fas fa-star"></i>
                        </label>
                        
                        <input type="radio" id="star-2" name="rating" value="2">
                        <label class="rating" for="star-2" title="">
                          <i class="fas fa-star"></i>
                        </label>
                        
                        <input type="radio" id="star-1" name="rating" value="1">
                        <label class="rating" for="star-1" title="">
                          <i class="fas fa-star"></i>
                        </label>
                              
                      </fieldset>
                    </div>
                     <label class="error_label" id="rating_err" style="margin-top: -15px;"></label>
                    <div class="form-group inp v_inp_new "  style="height:100px;">    
                      <textarea type="text" id="txtreview" name="txtreview" placeholder="&nbsp;" class="form-control custom_scroll w-100" style="max-height:100px;min-height: 100px"></textarea>                  
                       <label class="label">'.$this->lang->line('Write-Review').'</label>
                   </div>
                    <label class="error_label" id="rating_text"></label>
                   <div class="checkbox mt-4 mb-5">
                      <label class="font-size-14 pl0 colorcyan">
                        <input type="checkbox" name="anonymous" value="1">
                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                        '.$this->lang->line('Rate-as-Anonymous').'
                      </label>
                    </div>
                    <input type="hidden" id="booking_id" name="booking_id" value="'.url_encode($this->data['main'][0]->id).'">
                    <input type="hidden" id="merchant_id" name="merchant_id" value="'. url_encode($this->data['main'][0]->merchant_id).'">
                     <input type="hidden" id="" name="employeeid" value="'.$this->data['main'][0]->employee_id.'">
                   <div class="text-center">
                    <button type="button" id="saveReviewRating" class="btn btnlarge widthfit">'.$this->lang->line('Submit-Review').'</button>
                  </div>
                  </form>';
				
				
				}
			
			echo json_encode(['success'=>1,'html'=>$html,'reviehtml'=>$reviehtml]); die;
	    	}
	    else
	    	redirect(base_url());
		}
		else
			redirect(base_url());
	}

	//** Thankyou **//
	function thankyou()
	{
		$this->load->view('frontend/user/booking_thankyou');
	}

	//** Booking Review **//
	public function review()
	{
		if(empty($this->session->userdata('st_userid'))){
			echo json_encode(['success'=>0,'page'=>'login']);
		 }
		$insertArr=array();
		
		if(!empty($_POST['rating']))
		 {
			$insertArr['rate']=$_POST['rating'];
			$insertArr['booking_id']=url_decode($_POST['booking_id']);
			$insertArr['merchant_id']=url_decode($_POST['merchant_id']);
			$insertArr['review']=$_POST['txtreview'];
			$insertArr['emp_id']=$_POST['employeeid'];
			$insertArr['anonymous']=isset($_POST['anonymous'])?$_POST['anonymous']:0;
			$insertArr['read_status']='unread';
			$insertArr['user_id']=$this->session->userdata('st_userid');
			$insertArr['created_by']=$this->session->userdata('st_userid');
			$insertArr['created_on']=date('Y-m-d H:i:s');
			$this->user->insert('st_review',$insertArr);
			$this->booking->update('st_booking',array('updated_on' =>date('Y-m-d H:i:s')),array('id'=>url_decode($_POST['booking_id'])));
			$this->session->set_flashdata('success','Review added successfully.');
          
          echo json_encode(['success'=>1,'bookid'=>$_POST['booking_id']]);
		 }
		
	}

	//** Booking no Show **//
	public function booking_noshow()
	{
		$id = url_decode($_POST['book_id']);
		$usid=$this->session->userdata('st_userid');
		$acc=$this->session->userdata('access');
		$date=date('Y-m-d H:i:s');
		if($this->booking->update('st_booking',array('status' => 'no show','updated_by' => $usid,'updated_on' =>$date),array('id'=>$id))){
			$field = 'st_booking.id,user_id,booking_time,total_time,st_booking.merchant_id,book_id,first_name,last_name,st_users.email,st_booking.booking_type,st_booking.fullname,st_booking.email as guestemail,(select business_name from st_users where st_users.id = st_booking.merchant_id) as salon_name,(select email from st_users where st_users.id=st_booking.merchant_id) as m_email,st_users.notification_status';
			$info  = $this->booking->join_two('st_booking','st_users','user_id','id',array('st_booking.id'=>$id),$field);
			if(!empty($info))
			{
				$body_msg=str_replace('*salonname*',$info[0]->salon_name,$this->lang->line("booking_noshow_body"));
				$MsgTitle=$this->lang->line("booking_noshow_title");
				
				//echo $body_msg; 
				//echo $MsgTitle; die;
				
				if($info[0]->booking_type != 'guest' && $info[0]->notification_status != 0)
				{
					sendPushNotification($info[0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$info[0]->merchant_id ,'book_id'=> $id,'booking_status' =>'noshow','click_action' => 'BOOKINGDETAIL'));
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
				if(!empty($emailsend)){
				$message = $this->load->view('email/booking_cancel_no_show',array("fname"=>$first_name,"lname"=> $last_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,"booking_id" => $id,'book_id'=>$info[0]->book_id,"duration"=>$info[0]->total_time), true);
				
				$mail = emailsend($emailsend,'styletimer - Buchung verpasst',$message,'styletimer');  
				}
			}
				echo json_encode(array('success'=>1, 'id' =>$id));
		}
		else
			echo json_encode(array('success'=>0,'msg' => 'Sorry Unable to process...!','id' =>''));
	}

//======================================================== NEw booking From  Merchant Dashboard for customer==========================================//
  public function new_booking()
  {
	   $id=$this->session->userdata('st_userid');
	   if(!empty($id) && $this->session->userdata('access')=='marchant')
	   {
		   $this->data['cart_detail'] ="";
		   $this->data['main_category']="";
			$dataCont = getmembership_exp($id);
			if(!$dataCont['expired']){
				// $field2  = 'st_merchant_category.id,image,icon,st_category.category_name,st_merchant_category.created_by,st_merchant_category.subcategory_id as subid,st_merchant_category.category_id as mainid,name,count(*) as count';
			  
				// $group_by="'st_merchant_category.id','asc'";
	   			$this->data['main_category'] = $this->user->join_two('st_merchant_category','st_category','category_id','id',array('st_merchant_category.created_by' =>$id,'st_merchant_category.status' =>'active','st_merchant_category.online'=>1),$field2,'category_id');

				$query = "SELECT st_merchant_category.id,image,icon,st_category.category_name,st_merchant_category.created_by,st_merchant_category.subcategory_id AS subid,st_merchant_category.category_id AS mainid,st_category.`show_order`,NAME,COUNT(*) AS COUNT FROM st_merchant_category LEFT JOIN `st_category` ON `st_merchant_category`.`category_id`=`st_category`.`id` WHERE st_merchant_category.created_by = $id AND st_merchant_category.status = 'active' AND st_merchant_category.online = 1 GROUP BY category_id ORDER BY show_order";				

				$this->data['main_category'] = $this->user->custome_query($query);
				
	         	$field='COUNT(*) as service, SUM(total_price) as tot_price,SUM(duration) as duration1,(SELECT price_start_option FROM st_merchant_category WHERE id=service_id AND price_start_option="ab" LIMIT 1) as price_start_option';
			    $this->data['cart_detail']   =$this->booking->select_row('st_cart',$field,array('user_id'=>$this->session->userdata('st_userid')));
			}

	       //echo '<pre>'; print_r($this->data); //die;
	      

		   	$today = date('d.m.Y');
			$tnow = strtotime(date("H:i:s"));
			$nameOfDay     = date('l', strtotime($today));
			$resTime = $this->user->select_row('st_availability','starttime,endtime',array('user_id'=>$id,'type'=>'open','days'=>strtolower($nameOfDay)));
			$days = 0;
			while (true) {

				if(!empty($resTime)) {
					$tEnd   = strtotime($resTime->endtime);

					if (!($tnow >= $tEnd && $days == 1)) {
						break;
					}
				}
				if ($days == 8) {
					break;
				}

				$days++;
				$nameOfDay     = date('l', strtotime('+'.($days * 24).' hours', strtotime($today)));
				
				$resTime = $this->user->select_row('st_availability','starttime,endtime',array('user_id'=>$id,'type'=>'open','days'=>strtolower($nameOfDay)));
		
			}
			
			if ($days == 8) { $days = 0; }
			$this->data['default_date'] = date('d.m.Y', strtotime('+'.($days * 24).' hours', strtotime($today)));
			$this->data['is_edit_profile'] = true;
			$this->data['include_jqueryui'] = true;

	 		$this->load->view('frontend/marchant/add_new_booking',$this->data);
       }
       else 
       	redirect(base_url());
	 
  } 
	  
  //** Cart Detail **//
  public function card_details()
  {
	  
	   $id=$this->session->userdata('st_userid');
	   if(!empty($id) && $this->session->userdata('access')=='marchant')
	   {
		   if(empty($_POST) && empty($_SESSION['book_session']))
		   {
				redirect(base_url('merchant/dashboard'));
		   }
		   if(isset($_POST['date']) && $_POST['date']!="")
		   {
			  $date     = date('Y-m-d',strtotime($_POST['date']));
			  $dayName  = date("l", strtotime($_POST['date']));
			  $dayName  = strtolower($dayName);
		   }
		   else
		   {
				if(!empty($_SESSION['book_session']['date'])) 
				     $date = $_SESSION['book_session']['date'];
				else $date = date('Y-m-d');
			
				$dayName   = date("l", strtotime($date));
				$dayName   = strtolower($dayName);
			} 
			
			if(!empty($_POST))
			{	
		   		$_SESSION['book_session'] = $_POST;
		   		redirect(base_url('booking/card_details'));
	     	}	

		 	if(!empty($_SESSION['book_session']['uid']))
		 	{
			 	$customerId = $_SESSION['book_session']['uid'];
					
				$field                  = "id,first_name,last_name,gender,email,mobile,notes";			  
				$this->data['userdata'] = $this->booking->select_row('st_users',$field,array('id'=>$customerId));

			 }
			else{
			
				if(!empty($_SESSION['book_session']['customer_id']))
				{
					$customerId = $_SESSION['book_session']['customer_id'];
					
					$field                  = "id,first_name,last_name,gender,email,mobile,notes";			  
					$this->data['userdata'] = $this->booking->select_row('st_users',$field,array('id'=>$customerId));
				}
			}	 
		 
		  	if(!empty($_SESSION['book_session']['employee_select']) && $_SESSION['book_session']['employee_select']!='any')
		  	{
			  	$field  = "id,first_name,last_name,profile_pic";
			    $emplId = $_SESSION['book_session']['employee_select'];
			    
			 	$this->data['employeeData']=$this->booking->select_row('st_users',$field,array('id'=>$emplId));
			}
		 
	     	$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`service_id`,`st_category`.`category_name`, `name`, `st_merchant_category`.`duration`, `price`,`price_start_option`,`parent_service_id`,`discount_price`,`buffer_time`,`days`,`st_offer_availability`.`type`,st_merchant_category.type as stype,st_merchant_category.setuptime,st_merchant_category.processtime,st_merchant_category.finishtime,`starttime`,`endtime` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$this->session->userdata('st_userid')." ORDER BY id";
		
	   		 $this->data['booking_detail'] = $this->user->custome_query($sqlForservice,'result');
			$this->data['dayName'] = $dayName;
	    	if(empty($this->data['booking_detail']))
	    	{
				//$_SESSION['book_session']="";
				redirect(base_url('booking/new_booking'));
			}
	   $this->load->view('frontend/marchant/cart_details',$this->data);
      }
      else 
      	redirect(base_url());
	}	  
	  
// check employee avaialbe 
public function check_emp_available()
{
	$checkMembership = getmembership_daycount($this->session->userdata('st_userid'));
	if(empty($this->session->userdata('st_userid')) && $this->session->userdata('access')!='marchant')
	{
		echo json_encode(array('success'=>0, 'url' =>base_url('auth/login'))); die;
	}
	elseif($checkMembership!=""){
         echo json_encode(array('success'=>0, 'url' =>'','message'=>'<p style="font-size:16px;">'.$checkMembership.'</p>')); die;
	}
	else
	{
		if($this->session->userdata('access') == 'marchant')
		{
			$usid=$this->session->userdata('st_userid');
		}

		$empId = $_POST['emp_id'];

		$date=date('Y-m-d',strtotime($_POST['date']));
		$dayName=date("l", strtotime($_POST['date']));
		$dayName=strtolower($dayName);

		$time=$_POST['time'];

		$merchantDetail = $this->booking->select_row('st_users','id,status',array('id'=>$usid));
		
		if($merchantDetail->status !='active'){
			  echo json_encode(array('success'=>0,'msg'=>'The salon status is '.$merchantDetail->status)); die;
		}

		$data  = $this->booking->select_row('st_availability','id,days,starttime,endtime',array('user_id'=>$usid,'days'=>$dayName,'type'=>'open'));

		$temptime = strtotime($time . ':00');

		$sqlForservice  = "SELECT `st_cart`.`id`,`st_cart`.`merchant_id`,`st_cart`.`service_id`,`st_category`.`category_name`, `name`,`buffer_time`, st_merchant_category.duration, `price`, `parent_service_id`, `discount_price`,`days`,`st_offer_availability`.`type`,st_merchant_category.type as stype,st_merchant_category.setuptime,st_merchant_category.processtime,st_merchant_category.finishtime,`starttime`,`endtime`,(SELECT notification_time FROM st_users WHERE st_users.id=st_cart.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_cart.merchant_id) as additional_notification_time FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$this->session->userdata('st_userid')." ORDER BY id";
			
		$booking_detail = $this->user->custome_query($sqlForservice,'result');
		
		if(!empty($booking_detail)) {
			if($empId=='any')
			{
				$timeArray        = array();                           
				$ikj              = 0;
				$strtodatyetime   = $date." ".$time;
				
				$totalDuration    = 0;

				foreach ($booking_detail as $key => $value)
				{
					
					$serId[]                = $value->service_id;
					
					$timeArray[$ikj]        = new stdClass;
					
					$bkstartTime            = $strtodatyetime;
					$timeArray[$ikj]->start = $bkstartTime; 
					
					if($value->stype==1){
					
						$tdurtion = $row->setuptime+$row->processtime+$row->finishtime;
													
						$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
						$timeArray[$ikj]->end   = $bkEndTime;							    	
						$ikj++;	
						
						$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
						$timeArray[$ikj]        = new stdClass;
						$timeArray[$ikj]->start = $finishStart;
						
						$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
						$timeArray[$ikj]->end   = $finishEnd;
						$ikj++;
						
						$strtodatyetime=$finishEnd;
													
					}else{
						$totalMin               = $row->duration+$value->buffer_time;   
						
						$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
						$timeArray[$ikj]->end   = $bkEndTime;							    	
						$ikj++;	
						
						$tdurtion            = $totalMin;
						
						$strtodatyetime=$bkEndTime;							   
					} 

					$totalDuration=$totalDuration+$tdurtion;
			
				}

				$id  = $booking_detail[0]->merchant_id;
						
				$sql = $this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$id."");

				$uidsRes = $sql->result();

				if(!empty($uidsRes)){
							
					$users = array();
					
					foreach($uidsRes as $res){
						$users[$res->user_id][] = $res->service_id;
					}

					$userids = array();
					
					foreach($users as $k=>$v){
						$arraymatch = count(array_intersect($v, $serId)) == count($serId);
						if($arraymatch){
							$userids[] = $k;
							
						}
					}		
							
					if(!empty($userids)){
						
						$date       = date('Y-m-d',strtotime($date));
						$dayName    = date("l", strtotime($date));
						$dayName    = strtolower($dayName);
									
						$bookDate   = $date." ".$time;															 
						$estarttime = date('Y-m-d H:i:s',strtotime($bookDate));
						$eendtime   = date('Y-m-d H:i:s',strtotime($bookDate. "+ ".$totalDuration." minutes"));
					
						$select       = "SELECT id,first_name,last_name,profile_pic,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users WHERE status='active' AND access='employee' AND merchant_id=".$id." AND id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
						
						$employee     = $this->user->custome_query($select,'result');
						
						if(!empty($employee))
						{
							$reqtime=$_POST['date']." ".$_POST['time'];
							$chePastTime= date('Y-m-d H:i:s',strtotime($reqtime)); 
									
							$k=0;$l=0;
							$empId = '';

							foreach($employee as $emp)
							{
								$resultCheckSlot = checkTimeSlotsMerchantDuplicate($timeArray,$emp->id,$usid,$totalDuration);
								//echo $emp->id; 
								if($resultCheckSlot==true)
								{
									
									$empId = $emp->id;
									$k     = 1;
									
								}
								if($k==1) break;
									
							}		
								
							if(empty($empId)) {
								foreach($employee as $emp)
								{	
									$empId = $emp->id;
									break;
								}
								echo json_encode(array('success'=> 2, 'empid' => $empId));
							} else {
								echo json_encode(array('success'=> 1));
							}

						} else {
							echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
						}
					} 
					else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
					
				} 
				else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee not available for this time')); die;
				
			}
			else
			{
				$bookTrue    = 0;
				$reqtime     = $date." ".$time;
				$chePastTime = date('Y-m-d H:i:s',strtotime($reqtime)); 
				
				
				$total_price = $total_buffer=$total_min=$total_dis=$i=0;
				
				$bk_time     = $date." ".$time;;
				
				$timeArray        = array();                           
				$ikj              = 0;
				$strtodatyetime   = $bk_time;
				
				$totalDuration    = 0;	

				foreach($booking_detail as $row)
				{
					$timeArray[$ikj]        = new stdClass;
					
					$bkstartTime            = $strtodatyetime;
					$timeArray[$ikj]->start = $bkstartTime; 
					
					if($row->stype==1){
					
						$tdurtion = $row->setuptime+$row->processtime+$row->finishtime;
													
						$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
						$timeArray[$ikj]->end   = $bkEndTime;							    	
						$ikj++;	
						
						$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
						$timeArray[$ikj]        = new stdClass;
						$timeArray[$ikj]->start = $finishStart;
						
						$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
						$timeArray[$ikj]->end   = $finishEnd;
						$ikj++;
						
						$strtodatyetime=$finishEnd;
						
						$total_min    = $tdurtion+$total_min;	
														
					}else{
						$totalMin               = $row->duration+$row->buffer_time;   
						
						$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
						$timeArray[$ikj]->end   = $bkEndTime;							    	
						$ikj++;	
					
						$strtodatyetime=$bkEndTime;		
						
						$total_buffer = $row->buffer_time+$total_buffer;
						$total_min    = $row->duration+$total_min;	
						
						$tdurtion = $totalMin;				   
					} 
				
					$totalDuration = $totalDuration+$tdurtion;
				
					if ($row->parent_service_id) {
						$pstime = $this->booking->select(
							'st_offer_availability',
							'starttime,endtime,days,type',
							array(
							'service_id'=>$row->parent_service_id,
							'days' => $dayName
							)
						);
						if ($pstime) {
							$row->starttime = $pstime[0]->starttime;
							$row->endtime = $pstime[0]->endtime;
							$row->type = $pstime[0]->type;
							$row->days = $pstime[0]->days;
						}
					}
				}

				$resultCheckSlot = checkTimeSlotsMerchantDuplicate($timeArray,$empId,$booking_detail[0]->merchant_id,$totalDuration);

				if ($resultCheckSlot == true) {
					echo json_encode(array('success'=> 1));
				} else {
					// employee has already booking during slot
					echo json_encode(array('success'=> 3));
				}
			}
		}
		else
			echo json_encode(array('success'=>0, 'url' =>''));
	}
}
//** Confirm booking from merchant **//
public function confirm_merchant_booking()
{
    $checkMembership = getmembership_daycount($this->session->userdata('st_userid'));

	if(empty($this->session->userdata('st_userid')) && $this->session->userdata('access')!='marchant')
	{
		echo json_encode(array('success'=>0, 'url' =>base_url('auth/login'))); die;
	}
	elseif($checkMembership!=""){
         echo json_encode(array('success'=>0, 'url' =>'','message'=>'<p style="font-size:16px;">'.$checkMembership.'</p>')); die;
	}
	else
	{
		if($this->session->userdata('access') == 'marchant')
		{
			$usid=$this->session->userdata('st_userid');
		}
			// book employee id
		$empId=$_SESSION['book_session']['employee_select'];
		//booking date	
		if(isset($_POST['date']) && $_POST['date']!="")
		{
			$date=date('Y-m-d',strtotime($_POST['date']));
			$dayName=date("l", strtotime($_POST['date']));
			$dayName=strtolower($dayName);
		}
		else
		{
			$date=date('Y-m-d');
			$dayName=date("l", strtotime($date));
			$dayName=strtolower($dayName);
		} 
			
			/// booking time
		if(!empty($_POST['time']))
		{
			$time=$_POST['time'];
		}
		else
		{
			$time=date('H:i');
		}
		
	 	$merchantDetail = $this->booking->select_row('st_users','id,status',array('id'=>$usid));
		
		if($merchantDetail->status !='active'){
			  echo json_encode(array('success'=>0,'msg'=>'The salon status is '.$merchantDetail->status)); die;
		}	
				
		$data  = $this->booking->select_row('st_availability','id,days,starttime,endtime',array('user_id'=>$usid,'days'=>$dayName,'type'=>'open'));

		$temptime = strtotime($time . ':00');

//====================================================================================== Repeat booking ========================================================================//

		if(!empty($_SESSION['book_session']['repeatval']) && $_SESSION['book_session']['repeatval']=='yes')
		{			 
			if($_SESSION['book_session']['terms']!='specific')
			{
				$_SESSION['book_session']['terms'] = $_SESSION['book_session']['terms']+1;
			}
			
			$repetDate      = daysOfWeekBetween($date,$_SESSION['book_session']['repeat'],$_SESSION['book_session']['terms'],$_SESSION['book_session']['reapetdate'],'');	
		
			$sqlForservice  = "SELECT `st_cart`.`id`,`st_cart`.`merchant_id`,`st_cart`.`service_id`,`st_category`.`category_name`,`parent_service_id`,`starttime`,`endtime`, `name`,`buffer_time`, st_merchant_category.duration,st_merchant_category.type as stype,st_merchant_category.setuptime,st_merchant_category.processtime,st_merchant_category.finishtime, `price`, `discount_price`,(SELECT notification_time FROM st_users WHERE st_users.id=st_cart.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_cart.merchant_id) as additional_notification_time FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$this->session->userdata('st_userid')." ORDER BY id";
		
			$booking_detail = $this->user->custome_query($sqlForservice,'result');
			
			if(!empty($booking_detail))
			{
				$totalDuration = 0;
				
				foreach($booking_detail as $key => $value)
				{
					$serId[] = $value->service_id;
					
					if($value->stype==1)
						$tdurtion = $value->setuptime+$value->processtime+$_POST['duration_'.$value->id];
					else
						$tdurtion = $_POST['duration_'.$value->id]+$value->buffer_time;
					
					$totalDuration=$totalDuration+$tdurtion;
				}
			
					
				if($empId=='any') {
					$id   = $booking_detail[0]->merchant_id;
					$sql  = $this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$id."");
			
					$uidsRes = $sql->result();
					
					if(!empty($uidsRes)) {
						$users = array();
						
						foreach($uidsRes as $res)
						{
							$users[$res->user_id][] = $res->service_id;
						}

						$userids = array();
						
						foreach($users as $k=>$v)
						{
							$arraymatch = count(array_intersect($v, $serId)) == count($serId);
							if($arraymatch)
								$userids[] = $k;
						}
								
						if(!empty($userids))
						{
							$select  = "SELECT id,first_name,last_name,profile_pic,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users WHERE status='active' AND access='employee' AND merchant_id=".$id." AND id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
								
							$employee = $this->user->custome_query($select,'result');
							if(!empty($employee)) {
								$k=0;$l=0;
									
								foreach($employee as $emp)
								{
									$k=0;
									
									foreach($repetDate as $date)
									{
										$date       = date('Y-m-d',strtotime($date));
										
										$timeArray        = array();                           
										$ikj              = 0;
										$strtodatyetime   = $date." ".$time;
										
										foreach($booking_detail as $row){
										
											$timeArray[$ikj]        = new stdClass;									
											$bkstartTime            = $strtodatyetime;
											$timeArray[$ikj]->start = $bkstartTime; 
											
											if($row->stype==1){
												//$totaldurationTim=$totaldurationTim+$row->duration;
																			
												$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
												$timeArray[$ikj]->end   = $bkEndTime;							    	
												$ikj++;	
												
												$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
												$timeArray[$ikj]        = new stdClass;
												$timeArray[$ikj]->start = $finishStart;
												
												$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$_POST['duration_'.$row->id].' minute'));
												$timeArray[$ikj]->end   = $finishEnd;
												$ikj++;
												
												$strtodatyetime=$finishEnd;
																			
											}else{
												$totalMin               = $_POST['duration_'.$row->id]+$row->buffer_time;   
												
												$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
												$timeArray[$ikj]->end   = $bkEndTime;							    	
												$ikj++;	
												
												//$totaldurationTim       = $totaldurationTim+$totalMin;
												
												$strtodatyetime=$bkEndTime;							   
											} 
										}
						
										$resultCheckSlot = checkTimeSlotsMerchant($timeArray,$emp->id,$usid,$totalDuration);  
										
										if($resultCheckSlot==true)
										{
											$k++;
										}
												
									}  
									if(count($repetDate)==$k){
										$empId = $emp->id;
										break;
									}
								}
									
								if(!empty($empId))
								{
									if(!empty($_SESSION['book_session']['uid']))
									{			
															
										$user_detail = $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$_SESSION['book_session']['uid'])); 
									}
									elseif(!empty($_SESSION['book_session']['customer_id']))
									{						  
										$user_detail = $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$_SESSION['book_session']['customer_id'])); 
									}
										
									$newBookingId = 0;
										// print_r($repetDate); die;
									foreach($repetDate as $date){

										$date     = date('Y-m-d',strtotime($date));
										$dayName  = date("l", strtotime($date));
										$dayName  = strtolower($dayName);
										$book_Arr = array();
										
										$total_price=$total_buffer=$total_min=$total_dis=$i=0;
										
										$bk_time  = $date." ".$time;

										foreach($booking_detail as $row)
										{
											if ($row->parent_service_id) {
												$pstime = $this->booking->select(
													'st_offer_availability',
													'starttime,endtime',
													array(
													  'service_id'=>$row->parent_service_id,
													  'days' => $dayName
													)
												);
												if ($pstime) {
													$row->starttime = $pstime[0]->starttime;
													$row->endtime = $pstime[0]->endtime;
												}
											}

											//$total_price=$row->price+$total_price;
											$total_buffer = $row->buffer_time+$total_buffer;
											
											if($row->stype==1){
												$mduration = ($row->duration+$_POST['duration_'.$row->id])-$row->finishtime;
											}
											else{
												$mduration = $_POST['duration_'.$row->id];
											}	
											$total_min    = $mduration+$total_min;

											if ($row->parent_service_id) {
												$pstime = $this->booking->select(
													'st_offer_availability',
													'starttime,endtime,days,type',
													array(
													'service_id'=>$row->parent_service_id,
													'days' => $dayName
													)
												);
												if ($pstime) {
													$row->starttime = $pstime[0]->starttime;
													$row->endtime = $pstime[0]->endtime;
												}
											}

											if(!empty($row->discount_price) && !empty($row->starttime) && $time.':00'>=$row->starttime && $time.':00'<=$row->endtime)
						   					{ 
												$total_dis    = ($row->price-$row->discount_price)+$total_dis;
												$total_price  = $row->discount_price+$total_price;  
											}
											else   $total_price = $row->price+$total_price;  
										}  


										$min          = $total_buffer+$total_min;
										//echo $min; die;
										$newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
										$book_end     = date('Y-m-d H:i:s', $newtimestamp);


										if(!empty($_SESSION['book_session']['uid']))
										{							

											$book_Arr['user_id']        = $_SESSION['book_session']['uid'];
											$book_Arr['booking_type']   = 'user';
											$userId=$_SESSION['book_session']['uid'];

										}
										elseif(!empty($_SESSION['book_session']['customer_id'])){
											
											$book_Arr['user_id']      = $_SESSION['book_session']['customer_id'];
											$book_Arr['booking_type'] = 'user';
											$userId                   = $_SESSION['book_session']['customer_id'];
										}
										else{
											$book_Arr['user_id']      = 0;
											$book_Arr['booking_type'] = 'guest';
											$book_Arr['fullname']     = $this->lang->line('walk_in');
											$userId                   = 0;
										}
										
										if(!empty($_POST['additionalNotes']))
										{
											$book_Arr['notes']      = $_POST['additionalNotes'];
										}
										
										$notif_time  = $booking_detail[0]->notification_time;
										$ad_notif_time  = $booking_detail[0]->additional_notification_time;

										$timestamp   = strtotime($bk_time);
										$time1       = $timestamp - ($notif_time * 60 * 60);
										$ad_time1       = $timestamp - ($ad_notif_time * 60 * 60);
										// Date and time after subtraction
										$notif_date  = date("Y-m-d H:i:s", $time1);
										if($ad_notif_time != '0'){
											$ad_notif_date  = date("Y-m-d H:i:s", $ad_time1);
											$book_Arr['additional_notification_date'] = $ad_notif_date;
										}
									
										$book_Arr['merchant_id']       = $booking_detail[0]->merchant_id;
										$book_Arr['employee_id']       = $empId;
										$book_Arr['book_id']  	       = get_last_booking_id($booking_detail[0]->merchant_id);
										$book_Arr['total_time']        = $min;
										$book_Arr['booking_time']      = $bk_time;
										$book_Arr['booking_endtime']   = $book_end;
										$book_Arr['total_minutes']     = $total_min;
										$book_Arr['total_buffer']      = $total_buffer;
										$book_Arr['total_price']       = $total_price;
										$book_Arr['total_discount']    = $total_dis;
										$book_Arr['notification_date'] = $notif_date;
										//$book_Arr['full_name']=$user_detail->first_name.' '.$user_detail->last_name;
										//$book_Arr['contact_number']=$user_detail->mobile;
										//$book_Arr['email']=$user_detail->email;
										$book_Arr['pay_status']        = 'cash';
										$book_Arr['status']            = 'confirmed';
										$book_Arr['created_by']        = $this->session->userdata('st_userid');
										$book_Arr['created_on']        = date('Y-m-d H:i:s');

										$tid=$this->user->insert('st_booking',$book_Arr);
									
										$boojkstartTime = $bk_time;
										
										if($tid){
											
											if(empty($newBookingId)){
												$newBookingId = $tid;
											}
											foreach($booking_detail as $row){
												$detail_Arr=array();
												$detail_Arr['mer_id']           = $booking_detail[0]->merchant_id;
												$detail_Arr['emp_id']           = $empId;
												$detail_Arr['service_type']     = $row->stype;
												if ($row->buffer_time > 0)
													$detail_Arr['has_buffer']   = 1;
												if($row->stype==1){

													$detail_Arr['setuptime']        = $row->setuptime;
													$detail_Arr['processtime']      = $row->processtime;
													$detail_Arr['finishtime']       = $_POST['duration_'.$row->id];
													$detail_Arr['setuptime_start']  = $boojkstartTime;	
													
													$mduration                      = $row->setuptime+$row->processtime+$_POST['duration_'.$row->id];							 
																								
													$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$row->setuptime.' minute'));
													$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$row->processtime.' minute'));
													$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
													
													$detail_Arr['setuptime_end']    = $setuEnd;	
													$detail_Arr['finishtime_start'] = $finishStart;	
													$detail_Arr['finishtime_end']   = $finishEnd;
																									
													$boojkstartTime                 = $finishEnd;

												}else{
													$totalMin                       = $_POST['duration_'.$row->id]+$row->buffer_time;

													$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
													$detail_Arr['setuptime_start']  = $boojkstartTime;	
													$detail_Arr['setuptime_end']    = $setuEnd;	

													$boojkstartTime                 = $setuEnd;
													$mduration                      = $_POST['duration_'.$row->id];
												}
											
												$detail_Arr['booking_id'] = $tid;
												$detail_Arr['user_id']    = $userId;
												$detail_Arr['service_id'] = $row->service_id;
											
												if(!empty($row->name))
													$detail_Arr['service_name'] = $row->name;
												else
													$detail_Arr['service_name'] = $row->category_name;
											
												if(!empty($row->discount_price) && !empty($row->starttime) && $time.':00'>=$row->starttime && $time.':00'<=$row->endtime) {
													$detail_Arr['price']          = $row->discount_price;
													$detail_Arr['discount_price'] = $row->price-$row->discount_price;
												} else {
													$detail_Arr['price']       = $row->price;
												}

												$detail_Arr['duration']                    = $mduration+$row->buffer_time;
												$detail_Arr['buffer_time']                 = $row->buffer_time;
												$detail_Arr['created_on']                  = date('Y-m-d H:i:s');
												$detail_Arr['created_by']                  = $this->session->userdata('st_userid');

												$this->user->insert('st_booking_detail',$detail_Arr);
											}  

										}
									}
										
										//send mail for booking
									$this->data['main'] = $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$newBookingId),'st_booking.id,st_booking.user_id,business_name,booking_time,st_booking.merchant_id,st_booking.created_on,employee_id,(select first_name from st_users where id=st_booking.employee_id) as first_name,total_time,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
																
									if(!empty($this->data['main'])){
											
										$body_msg = str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_confirm_body"));
										$MsgTitle = $this->lang->line("booking_confirm_title");
									
										if(!empty($_SESSION['book_session']['uid'])){ 
											$whr                          = array('booking_id'=>$newBookingId);
											$this->data['booking_detail'] = $this->booking->select('st_booking_detail','*',$whr);	
											$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
											$this->data['booking_detail'][0]->first_name = $temUserData->name;
											
										}elseif(!empty($_SESSION['book_session']['customer_id'])){
											$field      = "st_booking_detail.id as booking_id,service_id,st_users.id,first_name,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price";  
											$whr        = array('booking_id'=>$newBookingId);
											$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
											$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
										
											if(!empty($this->data['main'][0]->user_notify))
											{
												sendPushNotification($this->data['main'][0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $newBookingId, 'booking_status' => 'confirmed','click_action' => 'BOOKINGDETAIL'));
											}
										}
									}

									
						
									if(!empty($_SESSION['book_session']['uid']))
									{ 
										$message = $this->load->view('email/booking_mail_from_marchant',$this->data, true);
										$mail    = emailsend($temUserData->email,$this->lang->line('styletimer_booking_confirmed'),$message,'styletimer');
									} 
									elseif(!empty($user_detail->email)){	   
										$message  = $this->load->view('email/booking_mail_from_marchant',$this->data, true);
								
										$mail     = emailsend($user_detail->email,$this->lang->line('styletimer_booking_confirmed'),$message,'styletimer');
									}



									$empDat = is_mail_enable_for_merchant_action($this->data['main'][0]->employee_id);
									if ($empDat) {
										$field      = "st_booking_detail.id as booking_id,service_id,st_users.id,first_name,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price";  
										$whr        = array('booking_id'=>$newBookingId);
										$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
										$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

										$tmp = $this->data;
										$tmp['main'][0]->first_name = $empDat->first_name;
										$message2 = $this->load->view('email/service_booking_employee_by_merchant',$tmp, true);
										emailsend($empDat->email,'styletimer - Neue Buchung',$message2,'styletimer');
									}								
											
									$this->booking->delete('st_cart',array('user_id' => $this->session->userdata('st_userid')));

									$_SESSION['book_session'] = "";
									
									$this->session->set_flashdata('success',$this->lang->line('booking_create_success'));

									if(isset($_POST['date']) && $_POST['date']!="") {
										$_SESSION['new_booking_date_value'] = $_POST['date'];
									}
									echo json_encode(array('success'=>1, 'url' =>'merchant/dashboard'));
								} else {
									echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
								}
								
							} else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
									
						} 
						else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
									
					} 
					else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee not available for this time')); die;
								
				}
				else
				{
					$time2 = $time;
					if(!empty($totalDuration)){
						$newtimestamp = strtotime(''.$time.' +'.$totalDuration.' minute');
						$time2        = date('H:i', $newtimestamp);
					}
							
							
					$k=0;
					foreach($repetDate as $date)
					{
						$date             = date('Y-m-d',strtotime($date));
						
						$timeArray        = array();                           
						$ikj              = 0;
						$strtodatyetime   = $date." ".$time;
							
						foreach($booking_detail as $row)
						{								
							$timeArray[$ikj]        = new stdClass;									
							$bkstartTime            = $strtodatyetime;
							$timeArray[$ikj]->start = $bkstartTime; 
									
							if($row->stype==1){
								//$totaldurationTim=$totaldurationTim+$row->duration;
															
								$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
								$timeArray[$ikj]->end   = $bkEndTime;							    	
								$ikj++;	
								
								$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
								$timeArray[$ikj]        = new stdClass;
								$timeArray[$ikj]->start = $finishStart;
								
								$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$_POST['duration_'.$row->id].' minute'));
								$timeArray[$ikj]->end   = $finishEnd;
								$ikj++;
								
								$strtodatyetime=$finishEnd;
																	
							}else{
								$totalMin               = $_POST['duration_'.$row->id]+$row->buffer_time;   
								
								$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
								$timeArray[$ikj]->end   = $bkEndTime;							    	
								$ikj++;	
								
								//$totaldurationTim       = $totaldurationTim+$totalMin;
								
								$strtodatyetime=$bkEndTime;							   
							}
						}
				
						$resultCheckSlot = checkTimeSlotsMerchant($timeArray,$empId,$usid,$totalDuration);  
						
						if($resultCheckSlot==true)
						{
							$k++;
						}
				
					}
							
					if(count($repetDate)==$k){
							
						if(!empty($_SESSION['book_session']['uid'])){								 
								
							$user_detail = $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$_SESSION['book_session']['uid'])); 

						}elseif(!empty($_SESSION['book_session']['customer_id'])){
													
							$user_detail = $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$_SESSION['book_session']['customer_id'])); 
							
						}
							
						$newBookingId = 0;
						
						foreach($repetDate as $date){
							
							$date     = date('Y-m-d',strtotime($date));
							$dayName  = date("l", strtotime($date));
							$dayName  = strtolower($dayName);
									
							$book_Arr = array();
							
							

							$total_price=$total_buffer=$total_min=$total_dis=$i=0;
							
							//$field1='SUM(price) as total_price,SUM(buffer_time) as total_buffer,SUM(duration) as total_duration,SUM(discount_price) as total_dis';
							//echo '<pre>'; print_r($booking_detail); die;
							$bk_time = $date." ".$time;
						
							foreach($booking_detail as $row)
							{
								if ($row->parent_service_id) {
									$pstime = $this->booking->select(
										'st_offer_availability',
										'starttime,endtime',
										array(
										  'service_id'=>$row->parent_service_id,
										  'days' => $dayName
										)
									);
									if ($pstime) {
										$row->starttime = $pstime[0]->starttime;
										$row->endtime = $pstime[0]->endtime;
									}
								}

								//$total_price=$row->price+$total_price;
								$total_buffer = $row->buffer_time+$total_buffer;
													
								if($row->stype==1){
									$mduration = ($row->duration+$_POST['duration_'.$row->id])-$row->finishtime;
								}
								else{
									$mduration = $_POST['duration_'.$row->id];
								}	
									//echo $mduration.'=='.$total_buffer;	die;
								$total_min    = $mduration+$total_min;
								
								if(!empty($row->discount_price) && !empty($row->starttime) && $time.':00'>=$row->starttime && $time.':00'<=$row->endtime)
								{ 
									$total_dis    = ($row->price-$row->discount_price)+$total_dis;
									$total_price  = $row->discount_price+$total_price;  
								}
								else   $total_price = $row->price+$total_price;   
								
									
							}  


							$min          = $total_buffer+$total_min;
							//echo $min; die; 

							$newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
							$book_end     = date('Y-m-d H:i:s', $newtimestamp);

							
							if(!empty($_SESSION['book_session']['uid']))
							{							
								$book_Arr['user_id']        = $_SESSION['book_session']['uid'];
								$book_Arr['booking_type']   = 'user';
								$userId                     = $_SESSION['book_session']['uid'];
							}
							elseif(!empty($_SESSION['book_session']['customer_id'])){
								$book_Arr['user_id']       = $_SESSION['book_session']['customer_id'];
								$book_Arr['booking_type']  = 'user';
								$userId                    = $_SESSION['book_session']['customer_id'];
							}
							else{
								$book_Arr['user_id']       = 0;
								$book_Arr['booking_type']  = 'guest';
								$book_Arr['fullname']      = $this->lang->line('walk_in');
								$userId                    = 0;
							}
							if(!empty($_POST['additionalNotes'])){
								$book_Arr['notes']   = $_POST['additionalNotes'];
							}
							
								//notification set time
							$notif_time  = $booking_detail[0]->notification_time;
							$ad_notif_time  = $booking_detail[0]->additional_notification_time;
							$timestamp   = strtotime($bk_time);
							$time1       = $timestamp - ($notif_time * 60 * 60);
							$ad_time1       = $timestamp - ($ad_notif_time * 60 * 60);
							// Date and time after subtraction
							$notif_date  = date("Y-m-d H:i:s", $time1);
							if($ad_notif_time != '0'){
								$ad_notif_date  = date("Y-m-d H:i:s", $ad_time1);
								$book_Arr['additional_notification_date'] = $ad_notif_date;
							}

							$book_Arr['total_time']        = $min;	 
							$book_Arr['merchant_id']       = $booking_detail[0]->merchant_id;
							$book_Arr['employee_id']       = $empId;
							$book_Arr['book_id']  	       = get_last_booking_id($booking_detail[0]->merchant_id);
							$book_Arr['booking_time']      = $bk_time;
							$book_Arr['booking_endtime']   = $book_end;
							$book_Arr['total_minutes']     = $total_min;
							$book_Arr['total_buffer']      = $total_buffer;
							$book_Arr['total_price']       = $total_price;
							$book_Arr['total_discount']    = $total_dis;
							$book_Arr['notification_date'] = $notif_date;
							//$book_Arr['full_name']=$user_detail->first_name.' '.$user_detail->last_name;
							//$book_Arr['contact_number']=$user_detail->mobile;
								//$book_Arr['email']=$user_detail->email;
							$book_Arr['pay_status']        = 'cash';
							$book_Arr['status']            = 'confirmed';
							$book_Arr['created_by']        = $this->session->userdata('st_userid');
							$book_Arr['created_on']        = date('Y-m-d H:i:s');
						
							$tid=$this->user->insert('st_booking',$book_Arr);
							
							$boojkstartTime = $bk_time;
								
							if($tid){
								if(empty($newBookingId)){
									$newBookingId = $tid;
								}
											
								//print_r($booking_detail);
								//die;
								foreach($booking_detail as $row){
									$detail_Arr=array();
									$detail_Arr['mer_id']           = $booking_detail[0]->merchant_id;
									$detail_Arr['emp_id']           = $empId;
									$detail_Arr['service_type']     = $row->stype;
									if ($row->buffer_time > 0)
										$detail_Arr['has_buffer']   = 1;
									if($row->stype==1){

										$detail_Arr['setuptime']        = $row->setuptime;
										$detail_Arr['processtime']      = $row->processtime;
										$detail_Arr['finishtime']       = $_POST['duration_'.$row->id];
										$detail_Arr['setuptime_start']  = $boojkstartTime;
										
										$mduration                      = $row->setuptime+$row->processtime+$_POST['duration_'.$row->id];										 
																					
										$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$row->setuptime.' minute'));
										$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$row->processtime.' minute'));
										$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$_POST['duration_'.$row->id].' minute'));
										
										$detail_Arr['setuptime_end']    = $setuEnd;	
										$detail_Arr['finishtime_start'] = $finishStart;	
										$detail_Arr['finishtime_end']   = $finishEnd;
																						
										$boojkstartTime                 = $finishEnd;

									}else{
										$totalMin                       = $_POST['duration_'.$row->id]+$row->buffer_time;

										$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
										$detail_Arr['setuptime_start']  = $boojkstartTime;	
										$detail_Arr['setuptime_end']    = $setuEnd;	

										$boojkstartTime                 = $setuEnd;
										$mduration                      = $_POST['duration_'.$row->id];
									}
										
									$detail_Arr['booking_id']   = $tid;
									$detail_Arr['user_id']      = $userId;
									$detail_Arr['service_id']   = $row->service_id;
																
									if(!empty($row->name))        
										$detail_Arr['service_name'] = $row->name;
									else                           
										$detail_Arr['service_name'] = $row->category_name;
								
									if ($row->parent_service_id) {
										$pstime = $this->booking->select(
											'st_offer_availability',
											'starttime,endtime,days,type',
											array(
											'service_id'=>$row->parent_service_id,
											'days' => $dayName
											)
										);
										if ($pstime) {
											$row->starttime = $pstime[0]->starttime;
											$row->endtime = $pstime[0]->endtime;
										}
									}
									
									//if(!empty($row->type) && $row->type=='open'){ 
										//if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
									
									//if(!empty($offerCheck)){ 
									if(!empty($row->discount_price) && !empty($row->starttime) && $time.':00'>=$row->starttime && $time.':00'<=$row->endtime) {
										$detail_Arr['price']          = $row->discount_price;
										$detail_Arr['discount_price'] = $row->price-$row->discount_price;
									} else {
										$detail_Arr['price']       = $row->price;
									}
									

									$detail_Arr['duration']                   = $mduration+$row->buffer_time;
									$detail_Arr['buffer_time']                = $row->buffer_time;
									$detail_Arr['created_on']                 = date('Y-m-d H:i:s');
									$detail_Arr['created_by']                 = $this->session->userdata('st_userid');

									$this->user->insert('st_booking_detail',$detail_Arr);
									

								}  
										
									
							}
						}
								
						//send mail for booking
						$this->data['main'] = $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$newBookingId),'st_booking.id,st_booking.user_id,business_name,booking_time,st_booking.merchant_id,st_booking.created_on,employee_id,total_time,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
												
						if(!empty($this->data['main'])){
							
							$body_msg = str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_confirm_body"));
							$MsgTitle = $this->lang->line("booking_confirm_title");
					
							if(!empty($_SESSION['book_session']['uid'])){ 
								$whr                                         = array('booking_id'=>$newBookingId);
								$this->data['booking_detail']                = $this->booking->select('st_booking_detail','*',$whr);	
								$this->data['booking_detail'][0]->first_name = $temUserData->name;
								$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
							}elseif(!empty($_SESSION['book_session']['customer_id'])){
								
								$field                        = "st_users.id,first_name,service_id,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price";  
								$whr                          = array('booking_id'=>$newBookingId);
								$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
								$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
								if(!empty($this->data['main'][0]->user_notify))
								{
									sendPushNotification($this->data['main'][0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $newBookingId, 'booking_status' => 'confirmed','click_action' => 'BOOKINGDETAIL'));
								}
							}
						}

					

						if(!empty($_SESSION['book_session']['uid']))
						{ 
							$message = $this->load->view('email/booking_mail_from_marchant',$this->data, true);
							$mail    = emailsend($temUserData->email,$this->lang->line('styletimer_booking_confirmed'),$message,'styletimer');
						} 
						elseif(!empty($user_detail->email)){
							
							$message = $this->load->view('email/booking_mail_from_marchant',$this->data, true);							  
							$mail    = emailsend($user_detail->email,$this->lang->line('styletimer_booking_confirmed'),$message,'styletimer');
						}
					


						$empDat = is_mail_enable_for_merchant_action($this->data['main'][0]->employee_id);
						if ($empDat) {
							$field                        = "st_users.id,first_name,service_id,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price";  
							$whr                          = array('booking_id'=>$newBookingId);
							$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
							$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
							$tmp = $this->data;
							$tmp['main'][0]->first_name = $empDat->first_name;
							$message2 = $this->load->view('email/service_booking_employee_by_merchant',$tmp, true);
							emailsend($empDat->email,'styletimer - Neue Buchung',$message2,'styletimer');
						}
				
				
						$this->booking->delete('st_cart',array('user_id' => $this->session->userdata('st_userid')));
					
						$_SESSION['book_session'] = "";
						$this->session->set_flashdata('success',$this->lang->line('booking_create_success'));
					
						if(isset($_POST['date']) && $_POST['date']!="") {
							$_SESSION['new_booking_date_value'] = $_POST['date'];
						}
						echo json_encode(array('success'=>1, 'url' =>'merchant/dashboard'));
					
					} else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Employee is not available for selected time period of repeat')); die;   
							
				}
				
			}
					
		}

//==============================================Repeat booking end==========================================================================//	
		else{
			$sqlForservice  = "SELECT `st_cart`.`id`,`st_cart`.`merchant_id`,`st_cart`.`service_id`,`st_category`.`category_name`, `name`,`buffer_time`, st_merchant_category.duration, `price`, `parent_service_id`, `discount_price`,`days`,`st_offer_availability`.`type`,st_merchant_category.type as stype,st_merchant_category.setuptime,st_merchant_category.processtime,st_merchant_category.finishtime,`starttime`,`endtime`,(SELECT notification_time FROM st_users WHERE st_users.id=st_cart.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_cart.merchant_id) as additional_notification_time FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$this->session->userdata('st_userid')." ORDER BY id";
			
			$booking_detail = $this->user->custome_query($sqlForservice,'result');
			if(!empty($booking_detail)){
				
				if($empId=='any')
				{
					
					if(empty($booking_detail)){
						echo json_encode(array('success'=>0, 'url' =>base_url()));
					}
					else{
						$timeArray        = array();                           
						$ikj              = 0;
						$strtodatyetime   = $date." ".$time;
						
						$totalDuration    = 0;	
						
						foreach ($booking_detail as $key => $value)
						{
							
							$serId[]                = $value->service_id;
							
							$timeArray[$ikj]        = new stdClass;
							
							$bkstartTime            = $strtodatyetime;
							$timeArray[$ikj]->start = $bkstartTime; 
							
							if($value->stype==1){
							
								$tdurtion = $_POST['duration_setup_'.$value->id]+$_POST['duration_process_'.$value->id]+$_POST['duration_'.$value->id];
															
								$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$_POST['duration_setup_'.$value->id].' minute'));
								$timeArray[$ikj]->end   = $bkEndTime;							    	
								$ikj++;	
								
								$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$_POST['duration_process_'.$value->id].' minute'));									
								$timeArray[$ikj]        = new stdClass;
								$timeArray[$ikj]->start = $finishStart;
								
								$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$_POST['duration_'.$value->id].' minute'));
								$timeArray[$ikj]->end   = $finishEnd;
								$ikj++;
								
								$strtodatyetime=$finishEnd;
															
							}else{
								$totalMin               = $_POST['duration_'.$value->id]+$value->buffer_time;   
								
								$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
								$timeArray[$ikj]->end   = $bkEndTime;							    	
								$ikj++;	
								
								$tdurtion            = $totalMin;
								
								$strtodatyetime=$bkEndTime;							   
							} 

							$totalDuration=$totalDuration+$tdurtion;
					
						}
						
						$id  = $booking_detail[0]->merchant_id;
						
						$sql = $this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$id."");
				
						$uidsRes = $sql->result();
						
						if(!empty($uidsRes)){
							
							$users = array();
							
							foreach($uidsRes as $res){
								$users[$res->user_id][] = $res->service_id;
							}

							$userids = array();
							
							foreach($users as $k=>$v){
								$arraymatch = count(array_intersect($v, $serId)) == count($serId);
								if($arraymatch){
									$userids[] = $k;
									
								}
							}		
									
							if(!empty($userids)){
								
								$date       = date('Y-m-d',strtotime($date));
								$dayName    = date("l", strtotime($date));
								$dayName    = strtolower($dayName);
											
								$bookDate   = $date." ".$time;															 
								$estarttime = date('Y-m-d H:i:s',strtotime($bookDate));
								$eendtime   = date('Y-m-d H:i:s',strtotime($bookDate. "+ ".$totalDuration." minutes"));
							
								$select       = "SELECT id,first_name,last_name,profile_pic,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users WHERE status='active' AND access='employee' AND merchant_id=".$id." AND id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
								
								$employee     = $this->user->custome_query($select,'result');
								
								if(!empty($employee))
								{
									$reqtime=$_POST['date']." ".$_POST['time'];
									$chePastTime= date('Y-m-d H:i:s',strtotime($reqtime)); 
											
									$k=0;$l=0;
									$empId = '';

									foreach($employee as $emp)
									{
										$resultCheckSlot = checkTimeSlotsMerchantDuplicate($timeArray,$emp->id,$usid,$totalDuration);
										//echo $emp->id; 
										if($resultCheckSlot==true)
										{
											
											$empId = $emp->id;
											$k     = 1;
											
										}
										if($k==1) break;
											
									}		
										
									if(empty($empId)) {
										$k = 0;
										foreach($employee as $emp)
										{
											$resultCheckSlot = checkTimeSlotsMerchant($timeArray,$emp->id,$usid,$totalDuration);
											//echo $emp->id; 
											if($resultCheckSlot==true)
											{
												
												$empId = $emp->id;
												$k     = 1;
												
											}
											if($k==1) break;
												
										}
									}	

									if(!empty($empId) && $empId!='any')
									{
										$book_Arr = array();
										
										// user details nsr
										if(!empty($_SESSION['book_session']['uid'])){
											
											$user_detail = $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$_SESSION['book_session']['uid']));
											
										}elseif(!empty($_SESSION['book_session']['customer_id'])){
											
											$user_detail = $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$_SESSION['book_session']['customer_id']));
											
										}

										$total_price = $total_buffer=$total_min=$total_dis=$i=0;
										
										//$field1='SUM(price) as total_price,SUM(buffer_time) as total_buffer,SUM(duration) as total_duration,SUM(discount_price) as total_dis';
										
										$bk_time = $date." ".$_POST['time'];
									
										foreach($booking_detail as $row)
										{
											if ($row->parent_service_id) {
												$pstime = $this->booking->select(
													'st_offer_availability',
													'starttime,endtime,days,type',
													array(
													'service_id'=>$row->parent_service_id,
													'days' => $dayName
													)
												);
												if ($pstime) {
													$row->starttime = $pstime[0]->starttime;
													$row->endtime = $pstime[0]->endtime;
													$row->type = $pstime[0]->type;
													$row->days = $pstime[0]->days;
												}
											}
											
										
											//$total_price=$row->price+$total_price;
											if($row->stype!=1)
												$total_buffer = $row->buffer_time+$total_buffer;
											
											if($row->stype==1)
											{
												$total_min = $total_min + $_POST['duration_setup_'.$row->id]+$_POST['duration_process_'.$row->id]+$_POST['duration_'.$row->id];
											}
											else{
												$total_min = $total_min + $_POST['duration_'.$row->id];
											}
											
											if(!empty($row->type) && $row->type=='open'){ 
												if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
												{	  
													if(!empty($row->discount_price)){	  
														$total_dis   = ($row->price-$row->discount_price)+$total_dis;
														$total_price = $row->discount_price+$total_price;  
													}
													else{
														$total_price  = $row->price+$total_price;  
													} 
												}
												else $total_price = $row->price+$total_price;  
											}else $total_price  = $row->price+$total_price; 
												
												
										}  


										$min          = $total_buffer+$total_min;
										$newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
										$book_end     = date('Y-m-d H:i:s', $newtimestamp);

										if(!empty($_SESSION['book_session']['uid']))
										{							
											$book_Arr['user_id']         = $_SESSION['book_session']['uid'];
											$book_Arr['booking_type']    = 'user';
											$userId                      = $_SESSION['book_session']['uid'];
											
										}
										elseif(!empty($_SESSION['book_session']['customer_id'])){
											$book_Arr['user_id']        = $_SESSION['book_session']['customer_id'];
											$book_Arr['booking_type']   = 'user';
											$userId                     = $_SESSION['book_session']['customer_id'];
										}
										else{
											$book_Arr['user_id']        = 0;
											$book_Arr['booking_type']   = 'guest';
											$book_Arr['fullname']       = $this->lang->line('walk_in');
											$userId                     = 0;
										}
												
										if(!empty($_POST['additionalNotes'])){
											$book_Arr['notes']         = $_POST['additionalNotes'];
										}

										//notification set time
										$notif_time                    = $booking_detail[0]->notification_time;
										$ad_notif_time                    = $booking_detail[0]->additional_notification_time;
										$timestamp                     = strtotime($bk_time);
										$time1                         = $timestamp - ($notif_time * 60 * 60);
										$ad_time1                         = $timestamp - ($ad_notif_time * 60 * 60);
										// Date and time after subtraction
										$notif_date                    = date("Y-m-d H:i:s", $time1);
										if($ad_notif_time != '0'){
											$ad_notif_date                    = date("Y-m-d H:i:s", $ad_time1);
											$book_Arr['additional_notification_date'] = $ad_notif_date;
										}

										$book_Arr['total_time']        = $min;	 
										$book_Arr['merchant_id']       = $booking_detail[0]->merchant_id;
										$book_Arr['employee_id']       = $empId;
										$book_Arr['book_id']  	        = get_last_booking_id($booking_detail[0]->merchant_id);
										$book_Arr['booking_time']      = $bk_time;
										$book_Arr['booking_endtime']   = $book_end;
										$book_Arr['total_minutes']     = $total_min;
										$book_Arr['total_buffer']      = $total_buffer;
										$book_Arr['total_price']       = $total_price;
										$book_Arr['total_discount']    = $total_dis;
										$book_Arr['notification_date'] = $notif_date;
										//$book_Arr['full_name']=$user_detail->first_name.' '.$user_detail->last_name;
										//$book_Arr['contact_number']=$user_detail->mobile;
											//$book_Arr['email']=$user_detail->email;
										$book_Arr['pay_status']        = 'cash';
										$book_Arr['status']            = 'confirmed';
										$book_Arr['created_by']        = $this->session->userdata('st_userid');
										$book_Arr['created_on']        = date('Y-m-d H:i:s');
									
										$tid=$this->user->insert('st_booking',$book_Arr);
										
										$boojkstartTime = $bk_time;
											
										if($tid){
											foreach($booking_detail as $row){
												$detail_Arr = [];
												$detail_Arr['mer_id']           = $booking_detail[0]->merchant_id;
												$detail_Arr['emp_id']           = $empId;
												$detail_Arr['service_type']     = $row->stype;
												if ($row->buffer_time > 0)
													$detail_Arr['has_buffer']   = 1;
												if($row->stype==1){

													$detail_Arr['setuptime']        = $_POST['duration_setup_'.$row->id];
													$detail_Arr['processtime']      = $_POST['duration_process_'.$row->id];
													$detail_Arr['finishtime']       = $_POST['duration_'.$row->id];
													$detail_Arr['setuptime_start']  = $boojkstartTime;
															
													$mduration                      = $_POST['duration_setup_'.$row->id]+$_POST['duration_process_'.$row->id]+$_POST['duration_'.$row->id];								 
																								
													$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$_POST['duration_setup_'.$row->id].' minute'));
													$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$_POST['duration_process_'.$row->id].' minute'));
													$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$_POST['duration_'.$row->id].' minute'));
													
													$detail_Arr['setuptime_end']    = $setuEnd;	
													$detail_Arr['finishtime_start'] = $finishStart;	
													$detail_Arr['finishtime_end']   = $finishEnd;
																									
													$boojkstartTime                 = $finishEnd;

												}else{
													$totalMin                       = $_POST['duration_'.$row->id]+$row->buffer_time;

													$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
													$detail_Arr['setuptime_start']  = $boojkstartTime;	
													$detail_Arr['setuptime_end']    = $setuEnd;	

													$boojkstartTime                 = $setuEnd;
													$mduration                      = $_POST['duration_'.$row->id];
												}
														
														
														
												$detail_Arr['booking_id']   = $tid;
												$detail_Arr['user_id']      = $userId;
												$detail_Arr['service_id']   = $row->service_id;
												if(!empty($row->name))
													$detail_Arr['service_name'] = $row->name;
												else
													$detail_Arr['service_name'] = $row->category_name;
														
												if(!empty($row->type) && $row->type=='open'){ 
													if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
													{		  
														if(!empty($row->discount_price)){
															$detail_Arr['price']          = $row->discount_price;
															$detail_Arr['discount_price'] = $row->price-$row->discount_price;
															}  
														else $detail_Arr['price']       = $row->price;
													}
													else $detail_Arr['price']           = $row->price;
												}
												
												else $detail_Arr['price']                 = $row->price;									         

												$detail_Arr['duration']                   = $mduration+$row->buffer_time;
												$detail_Arr['buffer_time']                = $row->buffer_time;
												$detail_Arr['created_on']                 = date('Y-m-d H:i:s');
												$detail_Arr['created_by']                 = $this->session->userdata('st_userid');

												$this->user->insert('st_booking_detail',$detail_Arr);
														

											}  
											$this->booking->delete('st_cart',array('user_id' => $this->session->userdata('st_userid')));
											
											$this->data['main'] = $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$tid),'st_booking.id,st_booking.user_id,business_name,booking_time,st_booking.merchant_id,employee_id,total_time,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
													
											if(!empty($this->data['main'])){
														
												$body_msg = str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_confirm_body"));
												$MsgTitle = $this->lang->line("booking_confirm_title");
												
												if(!empty($_SESSION['book_session']['uid'])){ 
													$whr                               = array('booking_id'=>$tid);
													$this->data['booking_detail']      = $this->booking->select('st_booking_detail','*',$whr);	
													$this->data['booking_detail'][0]->first_name = $temUserData->name;
													$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
												}elseif(!empty($_SESSION['book_session']['customer_id'])){
													$field = "st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";  
													$whr   = array('booking_id'=>$tid);
													
													$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
													$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
													if($this->data['main'][0]->user_notify != 0)
													{
													
														sendPushNotification($this->data['main'][0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $tid, 'booking_status' => 'confirmed','click_action' => 'BOOKINGDETAIL'));
														}
													}

												}

											
								
												if(!empty($_SESSION['book_session']['uid']))
													{   
														$message = $this->load->view('email/booking_mail_from_marchant',$this->data, true);						
														$mail   = emailsend($temUserData->email,$this->lang->line('styletimer_booking_confirmed'),$message,'styletimer');
													} 
												elseif(!empty($user_detail->email)){	   
														$message = $this->load->view('email/booking_mail_from_marchant',$this->data, true);				
														$mail    = emailsend($user_detail->email,$this->lang->line('styletimer_booking_confirmed'),$message,'styletimer');
												}
							
												$empDat = is_mail_enable_for_merchant_action($this->data['main'][0]->employee_id);
												if ($empDat) {
													$field = "st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";  
													$whr   = array('booking_id'=>$tid);
													
													$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
													$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
													$tmp = $this->data;
													$tmp['main'][0]->first_name = $empDat->first_name;
													$message2 = $this->load->view('email/service_booking_employee_by_merchant',$tmp, true);
													emailsend($empDat->email,'styletimer - Neue Buchung',$message2,'styletimer');
												}
												$_SESSION['book_session']=""; 
												$this->session->set_flashdata('success',$this->lang->line('booking_create_success'));

												if(isset($_POST['date']) && $_POST['date']!="") {
													$_SESSION['new_booking_date_value'] = $_POST['date'];
												}
												echo json_encode(array('success'=>1, 'url' =>'merchant/dashboard'));
														//echo json_encode(array('success'=>1, 'url' =>'booking/confirmed/'.url_encode($tid)));
											}
										else{
											echo json_encode(array('success'=>0, 'url' =>'booking/new_booking'));
										}
									}
									else  echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period1')); die;
						
								}
								else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
							
							} 
							else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee is not available for selected time period')); die;
							
						} 
						else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Any employee not available for this time')); die;
						
					}
				}	  
				else{
					
					$bookTrue    = 0;
					$reqtime     = $date." ".$time;
					$chePastTime = date('Y-m-d H:i:s',strtotime($reqtime)); 
					
					
					$total_price = $total_buffer=$total_min=$total_dis=$i=0;
					
					$bk_time     = $date." ".$time;;
					
					$timeArray        = array();                           
					$ikj              = 0;
					$strtodatyetime   = $bk_time;
					
					$totalDuration    = 0;	 
										
					foreach($booking_detail as $row)
					{
						$timeArray[$ikj]        = new stdClass;
						
						$bkstartTime            = $strtodatyetime;
						$timeArray[$ikj]->start = $bkstartTime; 
						
						if($row->stype==1){
						
							$tdurtion = $_POST['duration_setup_'.$row->id]+$_POST['duration_process_'.$row->id]+$_POST['duration_'.$row->id];
														
							$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$_POST['duration_setup_'.$row->id].' minute'));
							$timeArray[$ikj]->end   = $bkEndTime;							    	
							$ikj++;	
							
							$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$_POST['duration_process_'.$row->id].' minute'));									
							$timeArray[$ikj]        = new stdClass;
							$timeArray[$ikj]->start = $finishStart;
							
							$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$_POST['duration_'.$row->id].' minute'));
							$timeArray[$ikj]->end   = $finishEnd;
							$ikj++;
							
							$strtodatyetime=$finishEnd;
							
							$total_min    = $tdurtion+$total_min;	
															
						}else{
							$totalMin               = $_POST['duration_'.$row->id]+$row->buffer_time;   
							
							$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
							$timeArray[$ikj]->end   = $bkEndTime;							    	
							$ikj++;	
						
							$strtodatyetime=$bkEndTime;		
							
							$total_buffer = $row->buffer_time+$total_buffer;
							$total_min    = $_POST['duration_'.$row->id]+$total_min;	
							
							$tdurtion = $totalMin;				   
						} 
					
						$totalDuration = $totalDuration+$tdurtion;
					
						if ($row->parent_service_id) {
							$pstime = $this->booking->select(
								'st_offer_availability',
								'starttime,endtime,days,type',
								array(
								'service_id'=>$row->parent_service_id,
								'days' => $dayName
								)
							);
							if ($pstime) {
								$row->starttime = $pstime[0]->starttime;
								$row->endtime = $pstime[0]->endtime;
								$row->type = $pstime[0]->type;
								$row->days = $pstime[0]->days;
							}
						}
						if(!empty($row->type) && $row->type=='open')
						{
							if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
							{	  
								if(!empty($row->discount_price)){	  
									$total_dis     = ($row->price-$row->discount_price)+$total_dis;
									$total_price   = $row->discount_price+$total_price;  
									}
								else
									{
									$total_price = $row->price+$total_price;  
									} 
							}
							else $total_price  = $row->price+$total_price;  
						}
						else
							$total_price        = $row->price+$total_price; 
					}  
					
					$resultCheckSlot = checkTimeSlotsMerchant($timeArray,$empId,$booking_detail[0]->merchant_id,$totalDuration);	

					if($resultCheckSlot==true)
					{
						$book_Arr    = array();
						
						if(!empty($_SESSION['book_session']['uid'])){	
																
							$user_detail  =  $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$_SESSION['book_session']['uid'])); 
						
						}elseif(!empty($_SESSION['book_session']['customer_id'])){		
											
							$user_detail  =  $this->booking->select_row('st_users','first_name,last_name,email,mobile',array('id'=>$_SESSION['book_session']['customer_id'])); 
						
						}

						$min          = $total_buffer+$total_min;
						$newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
						
						//notification set time
						$book_end     = date('Y-m-d H:i:s', $newtimestamp);
						$notif_time   = $booking_detail[0]->notification_time;
						$timestamp    = strtotime($bk_time);
						$time         = $timestamp - ($notif_time * 60 * 60);
						// Date and time after subtraction
						$notif_date   = date("Y-m-d H:i:s", $time);

						if(!empty($_SESSION['book_session']['uid']))
						{							
							$book_Arr['user_id']         = $_SESSION['book_session']['uid'];
							$book_Arr['booking_type']    = 'user';
							$userId                      = $_SESSION['book_session']['uid'];
						
						}
						else if(!empty($_SESSION['book_session']['customer_id'])){
							$book_Arr['user_id']            = $_SESSION['book_session']['customer_id'];
							$book_Arr['booking_type']       = 'user';
							$userId                         = $_SESSION['book_session']['customer_id'];
						}
						else{
							$book_Arr['user_id']            = 0;
							$book_Arr['booking_type']       = 'guest';
							$book_Arr['fullname']           = $this->lang->line('walk_in');
							$userId                         = 0;
						}
							
						if(!empty($_POST['additionalNotes'])){
							$book_Arr['notes']             = $_POST['additionalNotes'];
						}	

						//notification set time
						$notif_time      = $booking_detail[0]->notification_time;
						$ad_notif_time      = $booking_detail[0]->additional_notification_time;
						$timestamp       = strtotime($bk_time);
						$time1           = $timestamp - ($notif_time * 60 * 60);
						$ad_time1           = $timestamp - ($ad_notif_time * 60 * 60);
						// Date and time after subtraction
						$notif_date      = date("Y-m-d H:i:s", $time1);
						
						if($ad_notif_time != '0'){
							$ad_notif_date      = date("Y-m-d H:i:s", $ad_time1);
							$book_Arr['additional_notification_date'] = $ad_notif_date;
						}


						$book_Arr['total_time']        = $min; 
						$book_Arr['merchant_id']       = $booking_detail[0]->merchant_id;
						$book_Arr['employee_id']       = $empId;
						$book_Arr['book_id']  	        = get_last_booking_id($booking_detail[0]->merchant_id);
						$book_Arr['booking_time']      = $bk_time;
						$book_Arr['booking_endtime']   = $book_end;
						$book_Arr['total_minutes']     = $total_min;
						$book_Arr['total_buffer']      = $total_buffer;
						$book_Arr['total_price']       = $total_price;
						$book_Arr['total_discount']    = $total_dis;
						$book_Arr['notification_date'] = $notif_date;
						//$book_Arr['full_name']=$user_detail->first_name.' '.$user_detail->last_name;
						//$book_Arr['contact_number']=$user_detail->mobile;
							//$book_Arr['email']=$user_detail->email;
						$book_Arr['pay_status']        = 'cash';
						$book_Arr['status']            = 'confirmed';
						$book_Arr['created_by']        = $this->session->userdata('st_userid');
						$book_Arr['created_on']        = date('Y-m-d H:i:s');
										
						$tid=$this->user->insert('st_booking',$book_Arr);
						
						$boojkstartTime = $bk_time;
						
						if($tid)
						{
							foreach($booking_detail as $row)
							{
								$detail_Arr = [];
								$detail_Arr['mer_id']           = $booking_detail[0]->merchant_id;
								$detail_Arr['emp_id']           = $empId;
								$detail_Arr['service_type']     = $row->stype;
								if ($row->buffer_time > 0)
									$detail_Arr['has_buffer']   = 1;	
								if($row->stype==1){
									
										$mdurtion = $_POST['duration_setup_'.$row->id]+$_POST['duration_process_'.$row->id]+$_POST['duration_'.$row->id];
									
										$detail_Arr['setuptime']        = $_POST['duration_setup_'.$row->id];
										$detail_Arr['processtime']      = $_POST['duration_process_'.$row->id];
										$detail_Arr['finishtime']       = $_POST['duration_'.$row->id];
										$detail_Arr['setuptime_start']  = $boojkstartTime;										 
																					
										$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$_POST['duration_setup_'.$row->id].' minute'));
										$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$_POST['duration_process_'.$row->id].' minute'));
										$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$_POST['duration_'.$row->id].' minute'));
										
										$detail_Arr['setuptime_end']    = $setuEnd;	
										$detail_Arr['finishtime_start'] = $finishStart;	
										$detail_Arr['finishtime_end']   = $finishEnd;
																						
										$boojkstartTime                 = $finishEnd;
									
								}else{
									$totalMin                       = $_POST['duration_'.$row->id]+$row->buffer_time;
									
									$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
									$detail_Arr['setuptime_start']  = $boojkstartTime;	
									$detail_Arr['setuptime_end']    = $setuEnd;	
									
									$boojkstartTime                 = $setuEnd;
									
									$mdurtion                       = $totalMin;
								}
												
								$detail_Arr['booking_id'] = $tid;
								$detail_Arr['service_id'] = $row->service_id;
									
								if(!empty($row->name))
									$detail_Arr['service_name'] = $row->name;
								else
									$detail_Arr['service_name'] = $row->category_name;
									
								if(!empty($row->type) && $row->type=='open')
								{ 
									if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
									{		  
										if(!empty($row->discount_price))
										{
											$detail_Arr['price']           = $row->discount_price;
											$detail_Arr['discount_price']  = $row->price-$row->discount_price;
										}  
										else $detail_Arr['price']          = $row->price;
									}
									else $detail_Arr['price']              = $row->price;
								}
								else 
									$detail_Arr['price']                   = $row->price;

								$detail_Arr['duration']                = $mdurtion;
								$detail_Arr['buffer_time']             = $row->buffer_time;
								$detail_Arr['created_on']              = date('Y-m-d H:i:s');
								$detail_Arr['user_id']                 = $userId;
								$detail_Arr['created_by']              = $this->session->userdata('st_userid');
									
								$this->user->insert('st_booking_detail',$detail_Arr);
							}  
							$this->booking->delete('st_cart',array('user_id' => $this->session->userdata('st_userid')));
							
							$this->data['main'] = "";
							
							///mail section
							$this->data['main'] = $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$tid),'st_booking.user_id,st_booking.id,business_name,booking_time,st_booking.merchant_id,st_booking.created_on,total_time,employee_id,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select notification_status from st_users where id=st_booking.user_id) as user_notify');
							
							if(!empty($this->data['main']))
							{

								$field  = "st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";  
								$whr    = array('booking_id'=>$tid);
								
								$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','created_by','id',$whr,$field);
								$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

								$body_msg = str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_confirm_body"));
								$MsgTitle = $this->lang->line("booking_confirm_title");
								
								if($this->data['main'][0]->user_notify != 0)
								{
									sendPushNotification($this->data['main'][0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $tid, 'booking_status' => 'confirmed','click_action' => 'BOOKINGDETAIL'));
								}

							}

							if(!empty($temUserData->email))
								{ 
									$message = $this->load->view('email/booking_mail_from_marchant',$this->data, true);
									$mail   = emailsend($temUserData->email,$this->lang->line('styletimer_booking_confirmed'),$message,'styletimer');
								} 
							elseif(!empty($user_detail->email)){	   
								$message = $this->load->view('email/booking_mail_from_marchant',$this->data, true);
								$mail    = emailsend($user_detail->email,$this->lang->line('styletimer_booking_confirmed'),$message,'styletimer');
								}
							
							$empDat = is_mail_enable_for_merchant_action($this->data['main'][0]->employee_id);
							if ($empDat) {
								$tmp = $this->data;
								$tmp['main'][0]->first_name = $empDat->first_name;
								$message2 = $this->load->view('email/service_booking_employee_by_merchant',$tmp, true);
								emailsend($empDat->email,'styletimer - Neue Buchung',$message2,'styletimer');
							}

							$_SESSION['book_session'] = ""; 
							$this->session->set_flashdata('success',$this->lang->line('booking_create_success'));

							if(isset($_POST['date']) && $_POST['date']!="") {
								$_SESSION['new_booking_date_value'] = $_POST['date'];
							}
							echo json_encode(array('success'=>1, 'url' =>'merchant/dashboard'));
								//echo json_encode(array('success'=>1, 'url' =>'booking/confirmed/'.url_encode($tid)));
						}
						else{
							echo json_encode(array('success'=>0, 'url' =>'booking/new_booking'));
						}
					} 
					else echo json_encode(array('success'=>0, 'url' =>'','message'=>'Employee is not available for selected time period')); die;
				} 
				
			}
			else
				echo json_encode(array('success'=>0, 'url' =>''));
		}
  	}
}
 /***** Customer Listing *****/	  
public function customer_list(){

         $mid     = $this->session->userdata('st_userid');
         $search  = '';
         $whr     = '';
		 if(isset($_POST['search']) && !empty($_POST['search'])){ $search = $_POST['search'];
		 	 $whr = "AND (st_users.first_name LIKE '%".$search."%' OR st_users.last_name LIKE '%".$search."%' OR st_users.email LIKE '%".$search."%')";
		  }
	
		 $sqlForservice = "SELECT st_booking.id,user_id,st_booking.merchant_id,st_users.notes,first_name,last_name,profile_pic,address,st_users.email,mobile,(SELECT count(id) FROM st_booking WHERE st_booking.user_id=st_users.id AND merchant_id=".$mid.") as bookcount,(SELECT booking_time FROM st_booking WHERE user_id=st_users.id AND status='completed' AND merchant_id=".$mid." ORDER BY id DESC LIMIT 1) as lastbook,(SELECT count(id) FROM st_client_block WHERE client_id=st_users.id AND merchant_id='".$mid."') as block FROM st_booking LEFT JOIN st_users ON st_booking.user_id=st_users.id WHERE st_booking.merchant_id=".$mid." AND st_booking.user_id !='0' AND access='user' AND (st_users.status='active' OR st_users.temp_user = 1) ".$whr." GROUP BY st_booking.user_id HAVING block < 1 ORDER BY st_booking.id DESC";

		
	    $customer = $this->user->custome_query($sqlForservice,'result');
	    
		 $html = '<li class="checkbox-image height56">
                            <a href="#" class="height56v vertical-middle colorcyan font-size-14 fontfamily-regular a_hover_cyan display-b p-3"  data-toggle="modal" data-target="#popup-v12">
                                <img class="employee-round-icon display-ib" src="'.base_url('assets/frontend/images/user-blue-v-icon.png').'">'.$this->lang->line('Add_Customer').'</a>
                          </li>';
                          
           $checkName = ""; 
           $mobile    = "";
           $email     = "";
           $notes     = "";              
		 if(!empty($customer)){
			  $customerId = "";
			  
			    if(!empty($_SESSION['book_session']['customer_id'])){
						   $customerId = $_SESSION['book_session']['customer_id'];
						 } 
				elseif(!empty($_SESSION['book_session']['uid'])){
					
					  $tempuserQuery  = "SELECT id,name,mobile,email,notes FROM temp_user WHERE id=". $_SESSION['book_session']['uid'];
					  $temUSername    = $this->user->custome_query($tempuserQuery,'row');
					    
					    if(!empty($temUSername->name)){
						 $checkName = $temUSername->name;
						 $mobile    = $temUSername->mobile;
                         $email     = $temUSername->email;
                         $notes     = $temUSername->notes;
						   }
						 } 		 
			 
			 foreach($customer as $row){
				if($row->profile_pic !='')
                        $usimg = base_url('assets/uploads/users/').$row->user_id.'/icon_'.$row->profile_pic;
                    else
                        $usimg = base_url('assets/frontend/images/user-icon-gret.svg');
                     
                     $checked  = "";
                    if(!empty($customerId) && $customerId==$row->user_id)
                         { $checked   = 'checked'; 
						   $checkName = $row->first_name.' '.$row->last_name;
						   
						   $mobile    = $row->mobile;
                           $email     = $row->email;
                           $notes     = $row->notes;
                         
						  }
                        
                 $html = $html.'<li class="checkbox-image height72v key_word">
                            <input type="radio" id="idCustumer'.$row->user_id.'" name="customer_id" class="slectCustomer" data-mobile="'.$row->mobile.'" data-email="'.$row->email.'" data-notes="'.$row->notes.'" data-val="'.$row->first_name.' '.$row->last_name.'" value="'.$row->user_id.'" '.$checked.'>
                            <label for="idCustumer'.$row->user_id.'" class="d-flex height72v vertical-middle">
                              <img class="employee-round-icon display-ib mt-1" style="vertical-align: middle;" src="'.$usimg.'">
                              <div class="display-ib">
                              <p class="color333 font-size-16 mb-0">'. $row->first_name.' '.$row->last_name.'</p>
                              <span class="color999 font-size-14">'.$row->email.'</span>
                              </div>
                            </label>
                          </li>';

				       }
			 } 
			
		  echo json_encode(array('success'=>'1','msg'=>'','html'=>$html,'checkedName'=>$checkName,'mobile'=>$mobile,'email'=>$email,'notes'=>$notes));	die;
	
	     
	}	
	
	
	
//*** Employee List ***//
public function employee_list(){
	
	$id  = $this->session->userdata('st_userid');
 	$whr = array('user_id'=>$this->session->userdata('st_userid'));
		
	if(isset($_POST['date']) && $_POST['date']!=""){
		$date    = date('Y-m-d',strtotime($_POST['date']));
		$dayName = date("l", strtotime($_POST['date']));
		$dayName = strtolower($dayName);
	}
	else{
		$date     = date('Y-m-d');
		$dayName  = date("l", strtotime($date));
		$dayName  = strtolower($dayName);
	}
			 
	$ptime = 'no';
	
	if(isset($_POST['time']) && $_POST['time']!="")
	{
		$time  = date('H:i:s',strtotime($_POST['time']));
		$ptime = "yes";
	}
	else{
		if(!empty($_SESSION['book_session']['time'])){
			$time  = date('H:i:s',strtotime($_SESSION['book_session']['time']));
			$ptime = "yes";  				
		}
		else $time = date('H:i:s');			
	}  

	// if ($ptime == 'yes') {
	// 	$sqlForservice = "SELECT `starttime`,`endtime` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') WHERE `user_id` = ".$this->session->userdata('st_userid')."";		
	// 	$tempdata = $this->user->custome_query($sqlForservice,'result');
	// 	if ($tempdata) {
	// 		if (strtotime($time) > strtotime($tempdata[0]->endtime) || strtotime($time) < strtotime($tempdata[0]->starttime)) {
	// 			$ptime = "no";
	// 		}
	// 	}
	// }

	$checkedName    = "";	
		     
	$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`service_id`,`st_category`.`category_name`, `name`, st_merchant_category.duration , `price`, `discount_price`,`buffer_time`,`days`,`st_offer_availability`.`type`,st_merchant_category.type as stype,st_merchant_category.setuptime,st_merchant_category.processtime,st_merchant_category.finishtime,`starttime`,`endtime` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$this->session->userdata('st_userid')."";

		
	$this->data['booking_detail'] = $this->user->custome_query($sqlForservice,'result');
	    
	// echo $this->db->last_query(); die;
	   
	   
	$this->data['merchant_id'] = $id;
	$html                      = "";
	$checkedName               = "";
	$j                         = 1;	
	$messageRes                 = "";
	if(!empty($this->data['booking_detail'])){
			
		$totalDuration = 0;
			
		foreach($this->data['booking_detail'] as $key => $value){
			$serId[]       = $value->service_id;
			
			if($value->stype==1)
				$tduraion      = $value->duration;
			else		        
				$tduraion      = $value->duration+$value->buffer_time;
			
			$totalDuration = $totalDuration+$tduraion;
		}
			
		$sql     = $this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$id."");
			
		$uidsRes = $sql->result();
			
		if(!empty($uidsRes)){
			
			$users = array();
               
			foreach($uidsRes as $res){
				$users[$res->user_id][] = $res->service_id;
			}

            $userids=array();
            
			foreach($users as $k=>$v){
				
				$arraymatch = count(array_intersect($v, $serId)) == count($serId);
				
				if($arraymatch){
					$userids[] = $k;
					
				}
			}		
					
           	if(!empty($userids)){
			   
			   	$time2 = $time;
			   	$wherT = "";
			  	if(!empty($totalDuration) && $ptime=='yes'){
				    $newtimestamp = strtotime(''.$time.' +'.$totalDuration.' minute');
					$time2        = date('H:i:s', $newtimestamp);

					$timelimit1 = $this->booking->select_row('st_availability','id, starttime, endtime',array('user_id'=>$id, 'days'=>$dayName, 'type'=>'open'));

					$f = 0;
					if (!empty($timelimit1->id)) {
						if ($time2 <= $timelimit->starttime)  $f = 1;
						if ($time >= $timelimit->endtime)  $f = 1;
					}
					$wherT        = " AND ((`starttime`<='".$time."' AND `starttime`<='".$time2."' AND endtime>='".$time."' AND endtime>='".$time2."') OR (`starttime_two`<='".$time."' AND `starttime_two`<='".$time2."' AND endtime_two>='".$time."' AND endtime_two>='".$time2."'))";
					if ($f == 1)
						$wherT = '';
				} 
			   	//echo $time;
			 	if(!empty($_POST['date']))
				{
					if ($ptime == 'yes') {		 
			       		$select = "SELECT st_users.id,first_name,last_name,profile_pic,type,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND days='".$dayName."' ".$wherT." AND type='open' AND access='employee' AND merchant_id=".$id." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
					}
					else 
						$select = "SELECT st_users.id,first_name,last_name,profile_pic,type,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id) as emp_time FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND days='".$dayName."' ".$wherT." AND type='open' AND access='employee' AND merchant_id=".$id." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
			    }
				else
			  	{
					if ($ptime == 'yes')
						$select  = "SELECT id,first_name,last_name,profile_pic,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users WHERE status='active' AND access='employee' AND merchant_id=".$id." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
					else
						$select  = "SELECT id,first_name,last_name,profile_pic,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id) as emp_time FROM st_users WHERE status='active' AND access='employee' AND merchant_id=".$id." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
			   	}
			 
			 	$employee_list = $this->user->custome_query($select,'result');
			 
				//echo "<pre>".$this->db->last_query(); print_r($employee_list); die;
				
				$html  = "";
				
				if(!empty($employee_list)){
				 
					// echo '<pre>'; print_r($employee_list); die;
				 
				 	$empId = "";
			    	if(!empty($_SESSION['book_session']['employee_select'])){
						$empId = $_SESSION['book_session']['employee_select'];
					} 
						 
					// echo "<pre>"; print_r($employee_list); die;
					$anychecked = "";
				
					if(!empty($_POST['empid'])){
					 	$empId = $_POST['empid'];
					}
				
					if(!empty($empId) && $empId=='any'){ $anychecked='checked'; $checkedName=$this->lang->line("any_employee_booking"); }
					$anyHtml = '<li class="radiobox-image">
								<input type="radio" id="employeeIdany" name="employee_select" class="eclassany selecteEmp" data-val="'.$this->lang->line("any_employee_booking").'" value="any" '.$anychecked.'>
								<label for="employeeIdany" class="height48v vertical-middle pt-2">
									<img class="employee-round-icon display-ib" src="'.base_url('assets/frontend/images/user-icon-gret.svg').'">'.$this->lang->line("any_employee_booking").'
								</label>
								</li>';
									  
								  
				 	foreach($employee_list as $emp){
					 
						if(!empty($_POST['date']) && $ptime=='yes')
					  	{
					 
						 	$bookDate   = $date." ".$time;
						 
						 
							$timeArray        = array();                           
							$ikj              = 0;
							$strtodatyetime   = $bookDate;
							//echo $strtodatyetime."===".$tNow."===".date('Y-m-d H:i:s',$tNow); die;				     
                            foreach($this->data['booking_detail'] as $row)
							{
								$timeArray[$ikj]        = new stdClass;									
								$bkstartTime            = $strtodatyetime;
								$timeArray[$ikj]->start = $bkstartTime; 
									
								if($row->stype==1){
									//$totaldurationTim=$totaldurationTim+$row->duration;
																	
									$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
									$timeArray[$ikj]->end   = $bkEndTime;							    	
									$ikj++;	
									
									$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
									$timeArray[$ikj]        = new stdClass;
									$timeArray[$ikj]->start = $finishStart;
									
									$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
									$timeArray[$ikj]->end   = $finishEnd;
									$ikj++;
									
									$strtodatyetime=$finishEnd;
																	
								}else{
									$totalMin               = $row->duration+$row->buffer_time;   
									
									$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
									$timeArray[$ikj]->end   = $bkEndTime;							    	
									$ikj++;	
																			
									$strtodatyetime=$bkEndTime;							   
								} 
							}
							// $estarttime = date('Y-m-d H:i:s',strtotime($bookDate));
							// $eendtime   = date('Y-m-d H:i:s',strtotime($bookDate. " +".$totalDuration." minutes"));	
								//echo $estarttime."==".$eendtime; die;
							//~ $selectBookingvailablity = "SELECT id FROM st_booking WHERE status='confirmed' AND ((booking_time>='".$estarttime."' AND booking_time<='".$eendtime."') OR (booking_endtime>='".$estarttime."' AND booking_endtime<='".$eendtime."')) AND employee_id=".$emp->id."";
							
							//~ $empBookSlot = $this->user->custome_query($selectBookingvailablity,'row');
						
							$resultCheckSlot = checkTimeSlotsMerchant($timeArray,$emp->id,$id,$totalDuration);
							if($resultCheckSlot==true){
							
								if(empty($html)){
								
								}
							
							 	//echo "if Booking";
							 	if($emp->profile_pic !='')
									$usimg = base_url('assets/uploads/employee/').$emp->id.'/'.$emp->profile_pic;
								else
									$usimg = base_url('assets/frontend/images/user-icon-gret.svg');
						  
								$checked = "";
								if(!empty($empId) && $empId==$emp->id){ $checked='checked';  $checkedName=$emp->first_name.' '.$emp->last_name; }  
								
									
									
								$html = $html.'<li class="radiobox-image ">
										<input type="radio" id="employeeId'.$emp->id.'" name="employee_select" class="eclass'.$j.' selecteEmp" data-val="'.$emp->first_name.' '.$emp->last_name.'" value="'.$emp->id.'" '.$checked.'>
										<label for="employeeId'.$emp->id.'" class="height48v vertical-middle pt-2">
										  <img class="employee-round-icon display-ib" src="'.$usimg.'">
										  '.$emp->first_name.' '.$emp->last_name.'
										</label>
									  </li> ';
									  
								$j++;
							 
							}
							
						}
						else
						{
							/*if(empty($html)){
									
								}*/
								
							//echo "if Booking";
							if($emp->profile_pic !='')
							$usimg   = base_url('assets/uploads/employee/').$emp->id.'/'.$emp->profile_pic;
							else     
							$usimg   = base_url('assets/frontend/images/user-icon-gret.svg');

							$checked = "";
							if(!empty($empId) && $empId==$emp->id){ $checked='checked';  $checkedName=$emp->first_name.' '.$emp->last_name; }  


							$html = $html.'<li class="radiobox-image ">
							<input type="radio" id="employeeId'.$emp->id.'" name="employee_select" class="eclass'.$j.' selecteEmp" data-val="'.$emp->first_name.' '.$emp->last_name.'" value="'.$emp->id.'" '.$checked.'>
							<label for="employeeId'.$emp->id.'" class="height48v vertical-middle pt-2">
							<img class="employee-round-icon display-ib" src="'.$usimg.'">
							'.$emp->first_name.' '.$emp->last_name.'
							</label>
							</li> ';

							$j++;
							
							
						}  
					}
					
					if($j>2){
						$html = $anyHtml.$html;
					
					}	 
				 
				}
				else{
					$messageRes = 'Der ausgewählte Service überschreitet die Öffnungszeiten. Bitte wähle eine andere Uhrzeit.';
				}
			}
			else{
				$messageRes = 'Die ausgewählten Services werden von unterschiedlichen Mitarbeitern ausgeführt. Du musst für diese Behandlung eine separate Buchung erstellen.';
			}
		} 
	}
	echo json_encode(array('success'=>'1','msg'=>$messageRes,'html'=>$html,'checkedName'=>$checkedName,'count'=>$j));	die;     
}					
	
//*** Check repeat booking is avaiable ***/	
public function repeat_check_availablity(){
	//print_r($_POST); die;
	$repetDate = daysOfWeekBetween($_POST['date'],$_POST['repaetOption'],$_POST['terms'],$_POST['specificDate'],'check');
	//print_r($repetDate); //die;
	
	if(!empty($repetDate))
	  {
	//echo "<pre>"; print_r($repetDate); die;
	    $id  = $this->session->userdata('st_userid');
        $whr = array('user_id'=>$this->session->userdata('st_userid'));
		
		if(isset($_POST['time']) && $_POST['time']!="")
		      {
               $time = $_POST['time'];
			  }
		  else{
			   $time  = date('H:i:s');			
		 	 }  			
		
		$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`service_id`,`st_category`.`category_name`, `name`, st_merchant_category.duration,st_merchant_category.type as stype,st_merchant_category.setuptime,st_merchant_category.processtime,st_merchant_category.finishtime, `price`, `discount_price`,`buffer_time` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$this->session->userdata('st_userid')."";

		
	    $this->data['booking_detail'] = $this->user->custome_query($sqlForservice,'result');
	    
	   // echo $this->db->last_query(); die;
	   
	   
	    $this->data['merchant_id'] = $id;
	    
	    $html = "";
	    
		if(!empty($this->data['booking_detail'])){
			
			$totalDuration = 0;
			
			foreach($this->data['booking_detail'] as $key => $value)
			 {
				 $serId[] = $value->service_id;
				 
				 if($value->stype==1)
				   $tduraion = $value->duration;
				 else
				   $tduraion = $value->duration+$value->buffer_time;
				
			 $totalDuration = $totalDuration+$tduraion;
			}
			
			$sql = $this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$id."");
			$uidsRes = $sql->result();
			
		if(!empty($uidsRes)){
			
               $users = array();
               
			 foreach($uidsRes as $res){
				 $users[$res->user_id][] = $res->service_id;
				 }

            $userids = array();
			foreach($users as $k=>$v){
				
				$arraymatch = count(array_intersect($v, $serId)) == count($serId);
				
				if($arraymatch){
					 $userids[] = $k;
				   }
				}		
					
           if(!empty($userids)){
			  
			   
			   
			   
			 $select = "SELECT st_users.id,first_name,last_name,profile_pic FROM st_users WHERE status='active' AND online_booking='1'AND access='employee' AND merchant_id=".$id." AND st_users.id IN(".implode(',',$userids).")";
			 
			 $employee_list  = $this->user->custome_query($select,'result');
			 
			 $html        = "";
			 $mseg        = "";
			 $checkedName = "";
			 $j           = 0;
			 $anyHtml     = "";
			 if(!empty($employee_list)){
				
				 $empId   = "";
				 
			    if(!empty($_SESSION['book_session']['employee_select']))
						 $empId = $_SESSION['book_session']['employee_select'];
						 
				if(!empty($_POST['employee_id'])) 
				         $empId = $_POST['employee_id'];	
					  
				$anychecked = "";	
					 
			if(!empty($empId) && $empId=='any'){ $anychecked='checked'; $checkedName="Nächster freier Mitarbeiter"; }
				$anyHtml = '<li class="radiobox-image ">
										<input type="radio" id="employeeIdany" name="employee_select" class="selecteEmp" data-val="'.$this->lang->line("any_employee_booking").'" value="any" '.$anychecked.'>
										<label for="employeeIdany" class="height48v vertical-middle pt-2">
										  <img class="employee-round-icon display-ib" src="'.base_url('assets/frontend/images/user-icon-gret.svg').'">
										  '.$this->lang->line("any_employee_booking").'
										</label>
									  </li>';
					 
				// echo "<pre>"; print_r($employee_list); die;
				 foreach($employee_list as $emp){
					 
					 $datesCount = count($repetDate); 
					 $k = 0;
					 
					  foreach($repetDate as $date){

							   $date              = date('Y-m-d',strtotime($date));
							  
							   $timeArray        = array();                           
							   $ikj              = 0;
							   $strtodatyetime   = $date." ".$time;
							   
							    foreach($this->data['booking_detail'] as $row){
								
									$timeArray[$ikj]        = new stdClass;									
									$bkstartTime            = $strtodatyetime;
									$timeArray[$ikj]->start = $bkstartTime; 
									
								   if($row->stype==1){
										//$totaldurationTim=$totaldurationTim+$row->duration;
																	   
										$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
										$timeArray[$ikj]->end   = $bkEndTime;							    	
										$ikj++;	
										
										$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
										$timeArray[$ikj]        = new stdClass;
										$timeArray[$ikj]->start = $finishStart;
										
										$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
										$timeArray[$ikj]->end   = $finishEnd;
										$ikj++;
										
										$strtodatyetime=$finishEnd;
																	
								   }else{
										$totalMin               = $row->duration+$row->buffer_time;   
										
										$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
										$timeArray[$ikj]->end   = $bkEndTime;							    	
										$ikj++;	
										
										//$totaldurationTim       = $totaldurationTim+$totalMin;
										
										$strtodatyetime=$bkEndTime;							   
								   } 
							   }
							   
							 $resultCheckSlot = checkTimeSlotsMerchant($timeArray,$emp->id,$id,$totalDuration);  
							 
							 if($resultCheckSlot==true)
							   {
							     $k++;
							   }
							  
							  //~ $dayName  = date("l", strtotime($date));
							  //~ $dayName  = strtolower($dayName);
							  
							  //~ $time2    = $time;
							  //~ if(!empty($totalDuration)){
								   //~ $newtimestamp = strtotime(''.$time.' +'.$totalDuration.' minute');
									//~ $time2       = date('H:i', $newtimestamp);
								  //~ }
							 //~ $select123 = "SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND ((`starttime`<='".$time."' AND `starttime`<='".$time2."' AND endtime>='".$time."' AND endtime>='".$time2."') OR (`starttime_two`<='".$time."' AND `starttime_two`<='".$time2."' AND endtime_two>='".$time."' AND endtime_two>='".$time2."')) AND user_id=".$emp->id."";
							
							//~ $avalablity = $this->user->custome_query($select123,'row');
							//~ //echo $this->db->last_query();
							//~ if(!empty($avalablity))
								//~ {
								
								 //~ $bookDate   = $date." ".$time;
								 
								 //~ $estarttime = date('Y-m-d H:i:s',strtotime($bookDate));
								 //~ $eendtime   = date('Y-m-d H:i:s',strtotime($bookDate. "+ ".$totalDuration." minutes"));
								 
								 //~ $selectBookingvailablity = "SELECT id FROM st_booking WHERE status='confirmed' AND ((booking_time>='".$estarttime."' AND booking_time<='".$eendtime."') OR (booking_endtime>='".$estarttime."' AND booking_endtime<='".$eendtime."')) AND employee_id=".$emp->id."";
								//~ // echo $selectBookingvailablity;
								//~ $empBookSlot = $this->user->custome_query($selectBookingvailablity,'row');
								//~ //echo $this->db->last_query();
								//~ if(empty($empBookSlot)){
									 //~ //echo "if Booking";
									 //~ $k++;
									//~ }
									
								  //~ }
							   }
							   
					   //echo $datesCount."==".$k."\n";
					  if($datesCount==$k){
						  
						       if($emp->profile_pic !='')
									$usimg = base_url('assets/uploads/employee/').$emp->id.'/'.$emp->profile_pic;
								else
									$usimg = base_url('assets/frontend/images/user-icon-gret.svg');
						  
								  $checked="";
								if(!empty($empId) && $empId==$emp->id){ $checked='checked';  $checkedName=$emp->first_name.' '.$emp->last_name; } 
									
								  $html = $html.'<li class="radiobox-image ">
											<input type="radio" id="employeeId'.$emp->id.'" name="employee_select" class="selecteEmp" data-val="'.$emp->first_name.' '.$emp->last_name.'" value="'.$emp->id.'" '.$checked.'>
											<label for="employeeId'.$emp->id.'" class="height48v vertical-middle pt-2">
											  <img class="employee-round-icon display-ib" src="'.$usimg.'">
											  '.$emp->first_name.' '.$emp->last_name.'
											</label>
										  </li> ';
									  
								 $j++;
						  
						  } 
					 }
					 
					if($j>=2){
					  $html = $anyHtml.$html;					
					} 
					 
					if(!empty($html))
					  {
					    if($_POST['terms']=='specific'){
							  $mseg = "Repeat ".$datesCount." Times";
							}
						else{
						    $lastDate  = $repetDate[$datesCount-1];
						    //echo lastDate;

							$times = new DateTime($lastDate);
							$formatter = new IntlDateFormatter( 
								"de-DE", 
								IntlDateFormatter::LONG, 
								IntlDateFormatter::NONE, 
								"Europe/Berlin", 
								IntlDateFormatter::GREGORIAN, 
								"EEEE', ' dd. MMMM" 
							); 
						
							$untildate = $formatter->format($times);
							
							$mseg      = "Wiederholung bis ".$untildate.", ".$datesCount ." mal";
							}
							//die;	
				       echo json_encode(array('success'=>'1','msg'=>$mseg,'html'=>$html,'checkedName'=>$checkedName));	die; 
				      }
				    else
				      {
						echo json_encode(array('success'=>'0','msg'=>'Mitarbeiter nicht verfügbar','html'=>$html));	die; 
					  }
				 
				 }else echo json_encode(array('success'=>'0','msg'=>'Mitarbeiter nicht verfügbar'));	die; 
			 
		    }else echo json_encode(array('success'=>'0','msg'=>'Mitarbeiter nicht verfügbar'));	die; 
			
		   } else echo json_encode(array('success'=>'0','msg'=>'Mitarbeiter nicht verfügbar'));	die; 
	   }
	else echo json_encode(array('success'=>'0','msg'=>'Please select service for repeat'));	die; 
   //} 
  }  
  else echo json_encode(array('success'=>'0','msg'=>'Any repeat not available for selected slote'));	die; 	 
	 
	 
}
			
//**** Get booking service ***//
public function get_booking_service(){

		$mid  = $this->session->userdata('st_userid');
          
		if(isset($_POST['date']) && $_POST['date']!=""){
			$date     = date('Y-m-d',strtotime($_POST['date']));
			$dayName  = date("l", strtotime($_POST['date']));
			$dayName  = strtolower($dayName);
		}
		else{
			$date      = date('Y-m-d');
			$dayName   = date("l", strtotime($date));
			$dayName   = strtolower($dayName);
		} 
		if(!empty($_POST['time'])){
			$time   = date('H:i:00',strtotime($_POST['time']));
		}	 
        else $time   = date('H:i:00');
        
		$where   = "";
          
		if(!empty($_POST['id']) && $_POST['id']!='all'&& $_POST['id'] != 'most_booked'){
			$where = " AND r.category_id=".$_POST['id'];
		}

		$mostBooked = $_POST['id'] == 'most_booked' ;
          

		if($mostBooked){
			$sql2 = "SELECT `u3`.`category_name` as `filtercat_name`, `r`.`id`,`r`.`name`,`r`.`category_id`,`r`.`duration`, `r`.`price`,`price_start_option`, `r`.`buffer_time`,`parent_service_id`,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`r`.`id` AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND status='active')>=1),'0') as checkemp, `r`.`discount_price`,`subcategory_id`, `u1`.`category_name` as `m_category`, `u2`.`category_name` as `s_category`,`st_offer_availability`.`starttime`,`st_offer_availability`.`endtime`, COUNT(bd.service_id) AS booking_count FROM `st_merchant_category` `r` LEFT JOIN `st_offer_availability` ON (`st_offer_availability`.`service_id`=`r`.`id` AND `st_offer_availability`.`days`='" . $dayName . "') JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_filter_category` `u3` ON `r`.`filtercat_id` =  `u3`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` JOIN st_booking_detail AS bd ON bd.`service_id` = r.`id` AND bd.`mer_id` =".$mid."  WHERE `r`.`status` = 'active' " . $where . " AND `r`.`created_by`=" . $mid . "  GROUP BY bd.`service_id`  having booking_count > 0 ORDER BY booking_count desc, `u2`.`filter_category` ASC, `u1`.`id` DESC, `r`.`id` ASC";
		}else{

		$sql2 = "SELECT `u3`.`category_name` as `filtercat_name`, `r`.`id`,`r`.`name`,`r`.`category_id`,`duration`, `price`,`price_start_option`, `buffer_time`,`parent_service_id`,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`r`.`id` AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND status='active')>=1),'0') as checkemp, `discount_price`,`subcategory_id`, `u1`.`category_name` as `m_category`, `u2`.`category_name` as `s_category`,`st_offer_availability`.`starttime`,`st_offer_availability`.`endtime` FROM `st_merchant_category` `r` LEFT JOIN `st_offer_availability` ON (`st_offer_availability`.`service_id`=`r`.`id` AND `st_offer_availability`.`days`='".$dayName."') JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_filter_category` `u3` ON `r`.`filtercat_id` =  `u3`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` WHERE `r`.`status` = 'active' ".$where." AND `r`.`created_by`=".$mid." ORDER BY  `u2`.`filter_category` ASC, `u1`.`id` DESC, `r`.`id` ASC";
	}

		$query2    = $this->db->query($sql2);
		//echo $this->db->last_query();die;
		$sercvices = $query2->result();
		// echo $this->db->last_query()."<pre>"; print_r($sercvices); die;

		$html = '';
		if(!empty($sercvices)){
			   
			$allCartService = $this->booking->select('st_cart','id,service_id',array('user_id'=>$this->session->userdata('st_userid')));
			$cartService = array();
			  
			if(!empty($allCartService))
			{
				foreach($allCartService as $cartServ) $cartService[] = $cartServ->service_id;
			}
				
			$services_by_subcategory = [];
					
			foreach($sercvices as $service){
				if($service->checkemp>0){
					$services_by_subcategory[$service->s_category][] = $service;	
				}
			}	
			// echo "<pre>"; print_r($services_by_subcategory); die;
			$i=0;
			$sub_ids=0;

			$filtercatname = '';
			foreach($services_by_subcategory as $k=>$v){	
				    
				$multiPlecat="";
				$allid=array();
				$startFrom="";
				$duration=array();
				
				$abd="";
				$ij=1;
				$s2=0;$ss2=0;
				if ($filtercatname != $v[0]->filtercat_name) {
					$html = $html.'<div class="font-size-18 p-3 colorcyan" style="padding-bottom: 0px !important">'.$v[0]->filtercat_name.'</div>';
					$filtercatname = $v[0]->filtercat_name;
				}
				foreach($v as $ser){		   
					if ($ij == 1) {
					$s2 = $ser->price; $ss2 = 0;
					}
								if ($s2 != $ser->price) $ss2 = 1;
					else if ($s2 == $ser->price && $ser->price_start_option == 'ab')  $ss2 = 1;
					if ($s2 > $ser->price) $s2 = $ser->price;

               		$name = $ser->s_category;
               
					if ($ser->parent_service_id) {
						$pstime = $this->booking->select(
							'st_offer_availability',
							'starttime,endtime',
							array(
								'service_id'=>$ser->parent_service_id,
								'days' => $dayName
							)
						);
						if ($pstime) {
							$ser->starttime = $pstime[0]->starttime;
							$ser->endtime = $pstime[0]->endtime;
						}
					}
               		if(!empty($ser->name))
                 	{
				  		$name = $ser->s_category.' - '.$ser->name;
				 	}
				 	$discntPrice = '';
					// echo $time; die;
               		if(!empty($ser->price) && !empty($ser->starttime) && $time>=$ser->starttime && $time<=$ser->endtime)
                 	{
						//echo $ser->starttime;
						$discount = $ser->price - $ser->discount_price;
						// 	 print_r($discount);
						//   $discount = get_discount_percent($ser->price,$ser->discount_price);
						//   print_r($discount);die;
						if(!empty($discount)){
						
							$discntPrice = '<span class="colorcyan fontfamily-regular
							font-size-14" style="width:100%;">enthält '
							.$discount.' € '
							.$this->lang->line('Discount1')
							.'</span>'; 
					 	} 
					}
				
					if(!empty($ser->discount_price) && !empty($ser->starttime) && $time>=$ser->starttime && $time<=$ser->endtime)
				    	$price = $ser->discount_price; 
			    	else $price = $ser->price;
               
               		if(in_array($ser->id,$cartService)) 
                    	$btnCls = 'selectedBtn';
               		else $btnCls = 'btn-border-orange';
               
               		$ab="";
            		if($ser->price_start_option=='ab'){
						$ab="ab ";
					}  
               
             		if(empty($ser->name)){  
						$html = $html.'<div class="border-b d-flex py-3 pl-3 addToCart" data-id="'.$mid.'" id="'.$ser->id.'" data-val="'.$price.'"> 
						<div class="deatail-box-left">
							<p class="color333 font-size-16 fontfamily-medium mb-0 overflow_elips">'.$name.'</p>
							<span class="font-size-14 color999 fontfamily-regular">'.$ser->duration.' '.$this->lang->line('Minutes').'</span>
						</div>
						<div class="deatail-box-right d-inline-flex">
						<div class="relative text-right width200">
							<p class=" color333 font-size-14 fontfamily-medium mb-0">'.$ab.price_formate($price).' €</p>
							'.$discntPrice.'
						</div>
						<button type="button" class="class-'.$ser->id.' btn '.$btnCls.'  btn-small widthfit80v ml-5 ">'.$this->lang->line('Select').'</button>
						</div>
						</div>';
					}else{
						if(in_array($ser->id,$cartService)) $slectClass='selectedBtn'; else $slectClass='btn-border-orange';
							
						$discntPrice="";
						$sub_ids = $ser->id;
							
						if($ser->price_start_option=='ab' || $ij==2){
							$abd="ab ";
						}  
									
						$ij++;
						$allid[] = $ser->id;
							
						if(!empty($ser->discount_price) && !empty($ser->starttime) && $time>=$ser->starttime && $time<=$ser->endtime)
						{ //echo $ser->starttime;
							$discount = $ser->price - $ser->discount_price;
									 
							//$discount = get_discount_percent($ser->price,$ser->discount_price);
							if(!empty($discount)){
										
								$discntPrice = '<span class="colorcyan fontfamily-regular font-size-14" style="width:100%;">enthält '.price_formate($discount).' € '.$this->lang->line('Discount1').'</span>'; 
							} 
						}
								
						if(!empty($ser->discount_price) && !empty($ser->starttime) && $time>=$ser->starttime && $time<=$ser->endtime)
							$price=$ser->discount_price;
						else $price= $ser->price;
						
						if(empty($startFrom)) $startFrom=$price;
						
						if($startFrom>$price) $startFrom=$price;
						
						$duration[]=$ser->duration;
							
						$multiPlecat=$multiPlecat.'<div class="d-flex flex-row py-2 pl-3 addToCart" data-id="'.$mid.'" id="'.$ser->id.'" data-val="'.$price.'"><div class="deatail-box-left">
											<p class="color666 font-size-16 fontfamily-medium mb-0">'.$ser->name.'</p>
											<span class="font-size-14 color999 fontfamily-regular">'.$ser->duration.' Minuten</span>
											</div>
											<div class="deatail-box-right d-inline-flex pl-20">
											<div class="relative text-right width160">
												<p class="fontfamily-medium color333 font-size-14 mb-0">'.($ser->price_start_option=="ab"?$ser->price_start_option:'').' '.price_formate($price).' €</p>
												'.$discntPrice.'
											</div>
											<button type="button" class="class-'.$ser->id.' btn '.$slectClass.'  btn-small widthfit80v ml-5 ">'.$this->lang->line('Select').'</button>
											</div>
										</div>';
					}
				}
				if(!empty($multiPlecat)){ 
					$min = min($duration);
					$max = max($duration);
					if($min==$max){ $dur= $min." Minuten"; $cls="first_pop"; } else { $dur= $min." Min. - ".$max ." Min.";  $cls="second_pop"; } 
						 
					$html=$html.'<div class="border-b px-3"> 
						<div class="accordion" id="right-side-box-accordian4">
						<div class="accordion-group">
							<div class="accordion-heading p-3 relative">
							<a class="accordion-toggle color333 fontfamily-medium font-size-16 a_hover_333 d-flex align-items-center collapsed" data-toggle="collapse" data-parent="#right-side-box-accordian4" href="#collapseOne42ajx'.$i.'"><p class="relative vertical-top deatail-box-left">
								'.$k.'<span class="font-size-14 color999 fontfamily-regular display-b">'.$dur.'</span></p>
								<span class="fontfamily-medium color333 font-size-14 mb-0" style="float:right;">'.($ss2 ? 'ab ':'').'<small class="font-size-18 fontfamily-semibold">'.price_formate($startFrom).' €</small></span>
							</a>
							</div>
							<div id="collapseOne42ajx'.$i.'" class="accordion-body collapse">
							<div class="accordion-inner">
								'.$multiPlecat.'
							</div>
							</div>
						</div>
						</div>
					</div>';
								
				}
				$i++; 
			}
		}
		$dataCont = getmembership_exp($mid);
		if(!$dataCont['expired']){
			echo json_encode(array('success'=>'1','msg'=>'','html'=>$html));	die;
		}else{
			echo json_encode(array('success'=>'1','msg'=>'','html'=>""));	die;
		}
	}
	
	
//*** Add service in cart ***//
function add_service_in_cart_from()
	{
		$insertArr = array();
		$usid      = $this->session->userdata('st_userid');
		
		$delID = "";
			
		 if($this->booking->get_datacount('st_cart',array('user_id' => $usid,'service_id' => $_POST['id'])) == 0)
		  {

				$service_det = $this->booking->select_row('st_merchant_category','price,created_by,duration,discount_price,subcategory_id',array('id'=>$_POST['id']));
				//print_r($service_det);
				if(!empty($service_det)){
					
					
					
                          $checkIntocart=$this->booking->select_row('st_cart','id,service_id,subcat_id,(SELECT allow FROM st_subcategory_settings WHERE subcat_id='.$service_det->subcategory_id.' AND merchant_id='.$usid.') as multiallow',array('subcat_id'=>$service_det->subcategory_id,'user_id'=>$usid));
                          
                         //  print_r($checkIntocart);
                          if(!empty($checkIntocart->subcat_id) && $checkIntocart->subcat_id==$service_det->subcategory_id && empty($checkIntocart->multiallow)){
                            //  echo print_r($checkIntocart); die; 
                              $delID = $checkIntocart->service_id;
                                  //echo $delID; die;
                          	  $this->booking->delete('st_cart',array('id' => $checkIntocart->id));

                               }
					
					
					
					
						if($this->booking->countResult('st_cart',array('merchant_id'=>$service_det->created_by,'user_id'=>$usid)))
						{
							$insertArr['service_id']  = $_POST['id'];
			      			$insertArr['user_id']     = $usid;
			     			
			      			$insertArr['total_price'] = $_POST['price'];
			      			$insertArr['merchant_id'] = $service_det->created_by;
			      			$insertArr['subcat_id']   = $service_det->subcategory_id;
			      			$insertArr['duration']    = $service_det->duration;
			      			$insertArr['created_by']  = $usid;
			      			$insertArr['created_on']  = date('Y-m-d H:i:s');
			      			
							$this->user->insert('st_cart',$insertArr);
							$cls  = 'selectedBtn';
							$rcls = 'btn-border-orange';
						}
						else{
							$this->booking->delete('st_cart',array('user_id' => $usid));
							
							$insertArr['service_id']  = $_POST['id'];
			      			$insertArr['user_id']     = $usid;
			      			
							$insertArr['duration']    = $service_det->duration;	 
			      			$insertArr['total_price'] = $_POST['price'];
			      			$insertArr['merchant_id'] = $service_det->created_by;
			      			$insertArr['subcat_id']   = $service_det->subcategory_id;
			      			$insertArr['created_by']  = $usid;
			      			$insertArr['created_on']  = date('Y-m-d H:i:s');
			      			
							$this->user->insert('st_cart',$insertArr);
							
							$cls  = 'selectedBtn';
							$rcls = 'btn-border-orange';
						}
					

				}else{
					$cls  = 'btn-border-orange';
					$rcls = 'selectedBtn';
				}
		}
		else{
			$this->booking->delete('st_cart',array('user_id' => $usid,'service_id' => $_POST['id']));
			
			$cls  = 'btn-border-orange';
			$rcls = 'selectedBtn';
		}
		$field = 'COUNT(*) as service, SUM(total_price) as tot_price,SUM(duration) as duration1,(SELECT price_start_option FROM st_merchant_category WHERE id=service_id AND price_start_option="ab" LIMIT 1) as price_start_option';
		$data  = $this->booking->select_row('st_cart',$field,array('user_id'=>$this->session->userdata('st_userid')));
		
		if(!empty($data->price_start_option) && $data->price_start_option=='ab'){
				$pricerr='ab '.price_formate($data->tot_price);
				}
			else{
				$pricerr=price_formate($data->tot_price);
				}

		echo json_encode(array('success' => 1,'count'=>$data->service,'total'=> $pricerr,'totalDuration'=>$data->duration1,'addCls' =>$cls,'revCls' =>$rcls,'deletedId'=>$delID));
	 
		
	}

//*** Save temporary customer ***//
public function save_temp_customer(){  
	$usid = $this->session->userdata('st_userid');
	  
	if ($usid) {

		$email = strtolower($this->input->post('email'));
		$password = '';
		$gender = $this->input->post('gender');
		$b_name = $b_type = $slug = '';
		$subscription_status = '';
		$sstart = '';
		$send = '';
		$refference = '';
		$online = 0;
		$trialTime = 0;
		$cancelallow = 'no';
		$hrsBeforeCancel = "0";
		$notification = $this->input->post('send_notification') == 'on' ? 1 : 0;
		$cityCheck = $this->input->post('city');
		if (!empty($cityCheck)) {
			$city = $this->input->post('city');
		} else {
			$city = "";
		}
		if (!empty($this->input->post('dob'))) {
			$dob = date('Y-m-d', strtotime($this->input->post('dob')));
		} else {
			$dob = "";
		}
		

		if ($email) {
			$usersSql = $this->db->select('count(*) as cnt')->from('st_users')->where('email', trim($email))->where('temp_user', 0);
			$data = $usersSql->get()->row();
			if($data->cnt > 0) {
				echo json_encode(array('success' =>0)); die;
			}
		}
		$name = $this->input->post('first_name') . ' ' . $this->input->post('last_name');

		$nimages = '';
		if (!empty($this->input->post('hasimage'))) {
			$upload_path = 'assets/uploads/users/' . $usid . '/icon_';
			$filepath = 'assets/uploads/profile_temp/' . $usid . '/';

			@mkdir($upload_path, 0777, true);
			@mkdir($filepath, 0777, true);
			$filepath2 = $upload_path;

			$images = scandir($filepath);
			$InserData = array();

			if (file_exists($filepath . $images[2])) {
				// echo file_exists($filepath.$images[2]);
				// rename($filepath . $images[2], $filepath2 . $images[2]);
				$nimages = $images[2];
			}
		}
		$additional_data = [
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'gender' => $gender ? $gender : '',
			'email' => $email,
			'mobile' => $this->input->post('telephone'),
			'zip' => (!empty($this->input->post('post_code')) ? $this->input->post('post_code') : ''),
			'address' => (!empty($this->input->post('location')) ? $this->input->post('location') : ''),
			'latitude' => (!empty($this->input->post('latitude')) ? $this->input->post('latitude') : ''),
			'longitude' => (!empty($this->input->post('longitude')) ? $this->input->post('longitude') : ''),
			'business_name' => (!empty($this->input->post('business_name')) ? $this->input->post('business_name') : ''),
			'slug' => $slug,
			'notes' => '',
			'business_type' => $b_type,
			'country' => (!empty($this->input->post('country')) ? $this->input->post('country') : ''),
			'city' => $city,
			'dob' => $dob,
			'created_on' => date("Y-m-d H:i:s"),
			'access' => 'user',
			'online_booking' => $online,
			'reffrel_code' => $refference,
			'salesman_code' => '',
			'profile_pic' => $nimages,
			'status' => 'active',
			'subscription_status' => $subscription_status,
			'start_date' => $sstart,
			'end_date' => $send,
			'extra_trial_month' => 1,
			'activation_code' => '',
			'temp_user' => 1,
			'newsletter' => 1,
			'service_email' => $notification,
			'cancel_booking_allow' => $cancelallow,
			'hr_before_cancel' => $hrsBeforeCancel,
		];
	    
		$res = $this->booking->insert('st_users',$additional_data);
		if($res){
			if (!empty($this->input->post('hasimage'))) {
				$upload_path = 'assets/uploads/users/' . $res . '/icon_';
				$filepath = 'assets/uploads/profile_temp/' . $usid . '/';
	
				@mkdir($upload_path, 0777, true);
				@mkdir($filepath, 0777, true);
				$filepath2 = $upload_path;
	
				$images = scandir($filepath);
				$InserData = array();
	
				if (file_exists($filepath . $images[2])) {
					// echo file_exists($filepath.$images[2]);
					rename($filepath . $images[2], $filepath2 . $images[2]);
				}
			}

			if (!empty($this->input->post('notes'))) {
				$insert_note['user_id'] = $res;
				$insert_note['notes'] = $this->input->post('notes');
				$insert_note['created_by'] = $usid;
				$insert_note['created_on'] = date('Y-m-d H:i:s');
				$this->booking->insert('st_usernotes', $insert_note);
			}

			$this->booking->insert('st_booking', [
				'user_id' => $res,
				'merchant_id' => $usid,
				'employee_id' => -1,
				'book_id' => -1,
				'booking_time' => '0000-00-00 00:00:00',
				'booking_endtime' => '0000-00-00 00:00:00',
				'total_minutes' => 0,
				'total' => 0,
				'total_time' => 0,
				'total_buffer' => 0,
				'total_price' => 0,
				'total_discount' => 0,
				'booking_type' => 'user',
				'contact_number' => '',
				'email' => '',
				'fullname' => '',
				'gender' => '',
				'notes' => '',
				'pay_status' => 'cash',
				'status' => 'confirmed',
				'reason' => '',
				'book_by' => 0,
				'seen_status' => 1,
				'blocked' => 0,
				'blocked_perent' => 0,
				'blocked_type' => '',
				'close_for' => 0,
				'block_for' => 0,
				'national_holiday' => 0,
				'notification_date' => '0000-00-00 00:00:00',
				'additional_notification_date' => '0000-00-00 00:00:00',
				'reshedule_count_byuser' => 0,
				'invoice_id' => 0,
				'emp_commission' => 0,
				'google_event_id' => '',
				'created_by' => $usid,
				'updated_by' => $usid,
				'created_on' => '0000-00-00 00:00:00',
				'updated_on' => '0000-00-00 00:00:00'
			]);
		  echo json_encode(array('success' => 1,'registered'=>1,'uid'=>$res,'name'=>$name,'email'=>$email,'mobile'=>$this->input->post('telephone'))); die;
	   	} else echo json_encode(array('success' =>0)); die;
	}
	else {
		echo json_encode(array('success' =>0)); die;
	}
}	

//*** Remove Booking in session  ***//	
public function removeBooking_session(){
	
	if($_POST['type']=='user'){
	   if(!empty($_SESSION["book_session"]["customer_id"])) $_SESSION["book_session"]["customer_id"]="";
	   if(!empty($_SESSION["book_session"]["uid"])) $_SESSION["book_session"]["uid"]="";
	   
     }
    if($_POST['type']=='repeat'){
	   if(!empty($_SESSION["book_session"]["repeatText"])) $_SESSION["book_session"]["repeatText"]="";
	   if(!empty($_SESSION["book_session"]["repeatval"])) $_SESSION["book_session"]["repeatval"]="no";
	   if(!empty($_SESSION["book_session"]["repeat"])) $_SESSION["book_session"]["repeat"]="";
	   if(!empty($_SESSION["book_session"]["terms"])) $_SESSION["book_session"]["terms"]="";
	   if(!empty($_SESSION["book_session"]["reapetdate"])) $_SESSION["book_session"]["reapetdate"]="";
	   
     } 
	else echo json_encode(array('success' =>1)); die;
	}	
	
public function check_booking_possible(){
	$usid=$this->session->userdata('st_userid');
		
	$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`service_id` FROM `st_cart` WHERE `service_id` = ".$_POST['sid']."";
	$services       = $this->user->custome_query($sqlForservice,'result');

	if (!empty($services)) {
		echo json_encode(array('success' =>1,'result'=> 'true'));
		die;
	}
	if(!empty($_POST['date'])){
		$date     = date('Y-m-d',strtotime($_POST['date']));
		$dayName  = date("l", strtotime($_POST['date']));
		$dayName  = strtolower($dayName);
	}
	else{
		$date    = date('Y-m-d');
		$dayName = date("l", strtotime($date));
		$dayName = strtolower($dayName);
	} 
			
	$sqlForservice = "SELECT * FROM `st_merchant_category` WHERE `id` = ".$_POST['sid']."";
		
	$services       = $this->user->custome_query($sqlForservice,'result');
	$html           = "";
	$checktime      = "";
	$tottalDuration = 0;
	if(!empty($services))
	{
		foreach($services as $key => $value)
		{
			if($value->type==1) 
				$tot            = $value->duration;
			else
				$tot            = $value->duration+$value->buffer_time;
				
			$tottalDuration = $tottalDuration+$tot;
		}
	}

	$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`service_id`,st_merchant_category.duration,buffer_time,type,setuptime,processtime,finishtime FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` WHERE `user_id` = ".$usid."";
	 
	$services       = $this->user->custome_query($sqlForservice,'result');
	if(!empty($services))
	{
	    foreach($services as $key => $value)
		{
			if($value->type==1) 
				$tot            = $value->duration;
			else
				$tot            = $value->duration+$value->buffer_time;
				
			$tottalDuration = $tottalDuration+$tot;
		}
	}

	$data  = $this->booking->select_row('st_availability','id,days,starttime,endtime',array('user_id'=>$usid,
	'days'=>$dayName,'type'=>'open'));

	$resultCheckSlot = false;
	if(!empty($data->starttime) && !empty($data->endtime))
	{
		$oldstarttime = $data->starttime;
		$oldendtime = $data->endtime;
		$data->starttime = getPreExtraHrs($usid);
		$data->endtime = getAfterExtraHrs($usid);

		if(!empty($_POST['empid']) && $_POST['empid']!='any') $empid = $_POST['empid'];
		else $empid = "";
				
		$disabled = '';
			
		$checkClass = "";
		
		if(!empty($services))
		{
			$timeArray        = array();
			$ikj              = 0;
			$strtodatyetime   = $date." ".$_POST['time'].':00';
		
			foreach($services as $row){
					
				$timeArray[$ikj]        = new stdClass;
				
				$bkstartTime            = $strtodatyetime;
				$timeArray[$ikj]->start = $bkstartTime; 
				
				if($row->type==1){
																			
					$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
					$timeArray[$ikj]->end   = $bkEndTime;							    	
					$ikj++;	
					
					$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
					$timeArray[$ikj]        = new stdClass;
					$timeArray[$ikj]->start = $finishStart;
					
					$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
					$timeArray[$ikj]->end   = $finishEnd;
					$ikj++;
					
					$strtodatyetime=$finishEnd;
												
				}else{
					$totalMin               = $row->duration+$row->buffer_time;   							
					$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
					$timeArray[$ikj]->end   = $bkEndTime;							    	
					$ikj++;								
					$strtodatyetime=$bkEndTime;							   
				} 
			}
			$resultCheckSlot=checkTimeSlotsMerchant($timeArray,$empid,$usid,$tottalDuration);

			if ($resultCheckSlot == true) {
				$etime = date('Y-m-d H:i:s',strtotime($date." ".$data->endtime));
				$etime1 = date('Y-m-d H:i:s',strtotime($timeArray[0]->start.' +'.$tottalDuration.' minute'));
				if ($etime1 > $etime)
					$resultCheckSlot = false;
			}
		} else $resultCheckSlot = true;
	}

	echo json_encode(array('success' =>1,'result'=> $resultCheckSlot ? 'true' : 'false'));
}	
//*** Get merchant time slot ***//
public function get_merchant_time_slot(){
	$usid=$this->session->userdata('st_userid');
	  
	if(!empty($_POST['date'])){
		$date     = date('Y-m-d',strtotime($_POST['date']));
		$dayName  = date("l", strtotime($_POST['date']));
		$dayName  = strtolower($dayName);
	}
	else{
		$date    = date('Y-m-d');
		$dayName = date("l", strtotime($date));
		$dayName = strtolower($dayName);
	} 
			
	$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`service_id`,st_merchant_category.duration,buffer_time,type,setuptime,processtime,finishtime FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` WHERE `user_id` = ".$usid."";
	 
	$services       = $this->user->custome_query($sqlForservice,'result');
	$html           = "";
	$checktime      = "";
	$tottalDuration = 0;
	if(!empty($services))
	{
	    foreach($services as $key => $value)
		{
			if($value->type==1) 
				$tot            = $value->duration;
			else
				$tot            = $value->duration+$value->buffer_time;
				
			$tottalDuration = $tottalDuration+$tot;
			$serId[]        = $value->service_id;
		}
			
		$sql = $this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$usid."");
			
		$uidsRes = $sql->result();
		if(!empty($uidsRes))
		{
			$users = array();
				  
			foreach($uidsRes as $res){
				$users[$res->user_id][] = $res->service_id;
			}

			$userids = array();
				
			foreach($users as $k=>$v){
				$arraymatch = count(array_intersect($v, $serId)) == count($serId);
				if($arraymatch){
					if(!empty($_POST['empid']) && $_POST['empid']!='any')
					{ 
						if($_POST['empid']==$k) $userids[] = $k;
					}
					else
					{
						$userids[] = $k;   
					}
				}
			}
			if(!empty($userids))
			{ 
				//   $selectEmp = "SELECT st_users.id as eid,starttime,endtime,starttime_two,endtime_two FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND online_booking='1' AND days='".$dayName."' AND type='open' AND access='employee' AND merchant_id=".$usid." AND st_users.id IN(".implode(',',$userids).")";
				
				$selectEmp = "SELECT st_users.id as eid,starttime,endtime,starttime_two,endtime_two FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND days='".$dayName."' AND type='open' AND access='employee' AND merchant_id=".$usid." AND st_users.id IN(".implode(',',$userids).")";
			}
			else{
				$html = '<li class="radiobox-image">
					<input type="radio" id="booktime" name="time" class="booktime" value="">
					<label for="booktime">
						Employee not available            
					</label>
				</li>';
			}			
		}
		else{
			$html = '<li class="radiobox-image">
					  <input type="radio" id="booktime" name="time" class="booktime" value="">
					  <label for="booktime">
					  Employee not available            
					</label>
					</li>';
			
		}
	}
	
		   
	$data  = $this->booking->select_row('st_availability','id,days,starttime,endtime',array('user_id'=>$usid,'days'=>$dayName,'type'=>'open'));

	if(!empty($data->starttime) && !empty($data->endtime))
	{
		$oldstarttime = $data->starttime;
		$oldendtime = $data->endtime;
		$data->starttime = getPreExtraHrs($usid);
		$data->endtime = getAfterExtraHrs($usid);

		if(!empty($_POST['empid']) && $_POST['empid']!='any') $empid = $_POST['empid'];
		else $empid = "";

		$tNow      = strtotime($data->starttime);
		//$tEnd      = strtotime($data->endtime);
		$tEnd=strtotime(date('H:i:s',strtotime($data->endtime. "- ".$tottalDuration." minutes")));

		//echo date('Y-m-d H:i:s',$tNow)."==".date('Y-m-d H:i:s',$tEnd); die;
		//echo $tNow."==".$tEnd; die;
		$curdate   = date('Y-m-d');
		$curtime   = date('H:i');
				
		$checkTime = "";
		if(!empty($_POST['time'])) $checkTime=$_POST['time'];
		
		if(!empty($_SESSION["book_session"]["time"])) $checkTime=$_SESSION["book_session"]["time"];
		$ij=1;		
		while($tNow <= $tEnd){
				
			$disabled = '';
			// if($curdate==$date){
			// 	if($curtime>=date('H:i',$tNow)){
			// 		$disabled = 'disable';
			// 	}
			// }
				
			$checkClass = "";
			//echo $tottalDuration; die;	
			if(empty($disabled))
			{
				if(!empty($services))
				{
					$timeArray        = array();                           
					$ikj              = 0;
					$strtodatyetime   = $date." ".date('H:i:s',$tNow);
				
					foreach($services as $row){
							
						$timeArray[$ikj]        = new stdClass;
						
						$bkstartTime            = $strtodatyetime;
						$timeArray[$ikj]->start = $bkstartTime; 
						
						if($row->type==1){
																					
							$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
							$timeArray[$ikj]->end   = $bkEndTime;							    	
							$ikj++;	
							
							$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
							$timeArray[$ikj]        = new stdClass;
							$timeArray[$ikj]->start = $finishStart;
							
							$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
							$timeArray[$ikj]->end   = $finishEnd;
							$ikj++;
							
							$strtodatyetime=$finishEnd;
														
						}else{
							$totalMin               = $row->duration+$row->buffer_time;   							
							$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
							$timeArray[$ikj]->end   = $bkEndTime;							    	
							$ikj++;								
							$strtodatyetime=$bkEndTime;							   
						} 
					}
				//~ $ij = 0;	
					$resultCheckSlot=checkTimeSlotsMerchant($timeArray,$empid,$usid,$tottalDuration);
				}
				else $resultCheckSlot=true;

				if($resultCheckSlot==true)
				{				
					if($checkTime==date('H:i',$tNow))
					{ 
						$checkClass = 'checked'; $checktime = date('H:i',$tNow); 
					}
					else if($checkClass == "" && $ij==1){
						$checkClass = 'checked';
						$checktime = date('H:i',$tNow);
					}
					
					$html = $html.'<li class="radiobox-image">
						<input type="radio" id="booktime'.$tNow.'" name="time" class="booktime" data-val="'.date('H:i',$tNow).'" value="'.date('H:i',$tNow).'" '.$checkClass.'>
						<label for="booktime'.$tNow.'">
						'.date('H:i',$tNow).'            
					</label>
					</li>';
					$ij++;
				}
			}
					
			$tNow = strtotime('+15 minutes',$tNow); 
			
		}
		
		
	//~ if(!empty($_POST['empid']) && $_POST['empid']!="any")
		//~ {
			//~ $selectEmp = "SELECT st_users.id as eid,starttime,endtime,starttime_two,endtime_two FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND online_booking='1' AND days='".$dayName."' AND type='open' AND access='employee' AND st_users.id=".$_POST['empid']."";
		//~ }
	//~ else{
			//~ $selectEmp = "SELECT st_users.id as eid,starttime,endtime,starttime_two,endtime_two FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND online_booking='1' AND days='".$dayName."' AND type='open' AND access='employee' AND merchant_id=".$usid."";
		
		//~ }   			

	}

	if(empty($html)){
		$html = '<li class="radiobox-image">
			<input type="radio" id="booktime" name="time" class="booktime" value="">
			<label for="booktime">
				Salon nicht verfügbar           
			</label>
		</li>'; 
	}

	echo json_encode(array('success' =>1,'html'=>$html,'checktime'=>$checktime)); die;
}						
//======================================================= NEw booking From  Merchant Dashboard for customer END ======================================//

	public function user_booking_comfirm(){
		//print_r($_GET);
		$this->data['booking_confirm']='confirm page';
		if(empty($this->session->userdata('st_userid')))
		{
			redirect(base_url('auth/login'));
		}
        
		if(isset($_GET['date']) && $_GET['date']!="")
		{
		  	$date     = date('Y-m-d',strtotime($_GET['date']));
		  	$dayName  = date("l", strtotime($_GET['date']));
		  	$dayName  = strtolower($dayName);
		}
		else
		{
			$date     = date('Y-m-d');
			$dayName  = date("l", strtotime($date));
			$dayName  = strtolower($dayName);
		}  
		$ptime        = 'no';
		if(isset($_GET['time']) && $_GET['time']!="")
	    {
	       	$time     = date('H:i:s',strtotime($_GET['time']));;
	     	$ptime    = "yes";
		}
		else
			$time     = date('H:i:s');
		
		$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`service_id`,`st_category`.`category_name`, `name`, st_merchant_category.duration , `price`, `discount_price`,`buffer_time`,`days`,`st_offer_availability`.`type`,`starttime`,`parent_service_id`,`price_start_option`,`endtime` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$this->session->userdata('st_userid')."";

		$this->data['dayName'] = $dayName;
		if($this->session->userdata('access')=='user')
		{
		  $this->data['detail']         = $this->booking->select_row('st_users','id,business_name,cancel_booking_allow,hr_before_cancel,(select image from st_banner_images where user_id = '.url_decode($_GET['merch_id']).') as image',array('id'=>url_decode($_GET['merch_id'])));

		if(!empty($_GET['employee_select']) && $_GET['employee_select']=='any'){
		  	$this->data['employee_name'] = 'Nächster freier Mitarbeiter';
		  }
		else{
              $employee = $this->booking->select_row('st_users','id,first_name,last_name',array('id'=>url_decode($_GET['employee_select'])));
              if(!empty($employee)){
                  $this->data['employee_name'] = $employee->first_name.' '.$employee->last_name;
               }
		    }
		  
	      $this->data['booking_detail'] = $this->user->custome_query($sqlForservice,'result');
	     //  print_r($this->data['detail']);
	    if(empty($this->data['booking_detail']))
	    	redirect(base_url('user/service_provider/').$_GET['merch_id']);
	    }
	    
	    $this->data['title']= 'Buchung bei  '.$this->data['detail']->business_name;
		$this->load->view('frontend/user/booking_confirm_page',$this->data);
	}


	// Download Certificate
    public function downloadReceipt($id="")
	{
		//$id=$this->session->userdata('st_userid');
		if($id !='')
	    {
	    	$this->data = array();
	    	$bid=url_decode($id);
			$this->data['main']= $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$bid),'st_booking.id,st_booking.book_id,st_booking.merchant_id,business_name,st_users.address,st_users.city,st_users.zip,st_booking.booking_time,st_booking.email,st_booking.total_price,st_booking.updated_on,st_booking.total_minutes,st_booking.book_by,st_booking.user_id as userid,st_booking.created_on,st_booking.updated_on,st_booking.created_by,st_booking.status,st_booking.booking_type,st_booking.fullname,st_booking.gender,st_booking.email as guestemail,st_booking.reason,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name');
			if(!empty($this->data['main']))
			{
				$field="st_users.id,st_booking_detail.user_id,first_name,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price,email,gender,address,city,country,zip,service_id";  
				$whr=array('booking_id'=>$bid);
    			$this->data['booking_detail']= $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field); 

	    	}
	    	else{
	    		redirect(base_url());
	    		die;
	    	}
	    
				// ----------
				 
				
				$pdfHtml = $this->load->view('frontend/receipt_pdf',$this->data,true);
				//~ echo $pdfHtml; die;
				$dompdf = new Dompdf();
				$dompdf->set_paper("A4");
				$dompdf->loadHtml($pdfHtml,'UTF-8');
				$dompdf->set_option('enable_remote', TRUE);
				$dompdf->set_option('enable_css_float', TRUE);
				$dompdf->set_option('enable_html5_parser', TRUE);
				$dompdf->render();
				//$output = $dompdf->output();
    			//file_put_contents('receipt.pdf', $output);
					$filename = 'receipt';
				//$dompdf->stream('receipt.pdf');	
				$dompdf->stream("receipt.pdf",array("Attachment"=>0));			
				// ----------	
				
		}
		else 
			redirect(base_url());
	    		die; 
	}
	// ------------------------------------------------------
function clear_cart(){
    $uid =$this->session->userdata('st_userid');
	$res = $this->db->query('DELETE FROM st_cart WHERE user_id='.$uid);
    if($res){	
	 echo json_encode(array('success' =>1)); die;
	  }
	else{
		echo json_encode(array('success' =>0)); die;
	}  
}

}