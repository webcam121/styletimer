<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rebook extends Frontend_Controller{

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

	
	
	//** Booking Confirm **//
 public function rebooking()
	{  
		// echo "<pre>"; print_r($_POST); echo url_decode($_POST['bookid']); die;
        $bookId  = url_decode($_POST['bookid']);
        $newtime = $_POST['datetime'];
		
		$field='id,merchant_id,user_id,booking_type,email,notes,gender,fullname,contact_number,employee_id,(SELECT notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as additional_notification_time';
		$info=$this->user->select_row('st_booking',$field,array('id'=> $bookId));
				
		if(!empty($info)){
			
		 $merchantDetail = $this->booking->select_row('st_users','id,status',array('id'=>$info->merchant_id));
		
		 if($merchantDetail->status !='active'){
			  echo json_encode(array('success'=>0,'msg'=>'Der Salon ist leider nicht mehr bei styletimer verfügbar')); die;
			 
			 }	
			
			$newDate = date('Y-m-d', strtotime($newtime));
			$str_time = date('H:i:s', strtotime($newtime));
						
                	 $nameOfDay     = date('l', strtotime($newDate));
               		 $totalMinutes  = 0; //$info->total_minutes+$info->total_buffer;
                	 $times         = strtotime($str_time);
					 $newTime       = date("H:i", strtotime('+ '.$totalMinutes.' minutes', $times));
					 $dayName       = strtolower($nameOfDay);

				     $bk_time       = $newtime;
				

				  $sqlForservice = "SELECT `st_booking_detail`.`id`,`st_booking_detail`.`user_id`,`st_booking_detail`.`buffer_time`,`st_booking_detail`.`has_buffer`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`,st_booking_detail.duration,st_booking_detail.service_type as stype,st_booking_detail.setuptime,st_booking_detail.processtime,st_booking_detail.finishtime,`st_merchant_category`.`price`, `st_merchant_category`.`discount_price`,`days`,`st_offer_availability`.`type`,`parent_service_id`,`starttime`,`endtime` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = ".$bookId."  ORDER BY st_booking_detail.id";
		
	    		  $booking_detail  = $this->user->custome_query($sqlForservice,'result');

	    		  $total_price=$total_buffer=$total_min=$total_dis=0;
				               
			      if(!empty($booking_detail)){
					  
					     $timeArray        = array();                           
						 $ikj              = 0;
						 $strtodatyetime   = $bk_time;
						 
						 //echo "<pre>"; print_r($booking_detail);
                       
			      		 foreach($booking_detail as $row)
									 {
											 
										$timeArray[$ikj]        = new stdClass;						
										$bkstartTime            = $strtodatyetime;
										$timeArray[$ikj]->start = $bkstartTime; 

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

										if($row->stype==1){
											$total_min=$row->duration+$total_min;
																		   
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
											$total_buffer           = $row->buffer_time+$total_buffer;
											$totalMin               = $row->duration;
											 
											$total_min              = $totalMin+$total_min - $row->buffer_time;
											
											$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
											$timeArray[$ikj]->end   = $bkEndTime;							    	
											$ikj++;	
											
											$strtodatyetime=$bkEndTime;							   
										} 	
											
											
											
										if(!empty($row->type) && $row->type=='open')
										   { 
											if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
											  {	  
											   if(!empty($row->discount_price)){	  
											       $total_dis    = ($row->price-$row->discount_price)+$total_dis;
											       $total_price  = $row->discount_price+$total_price;  
											    }
											   else{
												   $total_price  = $row->price+$total_price;  
												   } 
											  }
											 else $total_price   = $row->price+$total_price;  
										   }else $total_price    = $row->price+$total_price; 
											  
											
										 }  
										 
									$totalMinutes	= $total_buffer+$total_min;
									
									//print_r($timeArray); die;
                                   $resultCheckSlot = checkTimeSlotsMerchant($timeArray,$info->employee_id,$info->merchant_id,$totalMinutes,"");
                                   
                                   if($resultCheckSlot==true){

									 $min           = $totalMinutes;
									 $newtimestamp  = strtotime(''.$bk_time.' + '.$min.' minute');
									 $book_end      = date('Y-m-d H:i:s', $newtimestamp);
									 
	 								 //notification set time
	 								 $notif_time    = $info->notification_time;
	 								 $ad_notif_time    = $info->additional_notification_time;
									 $timestamp     = strtotime($bk_time);
									 $time          = $timestamp - ($notif_time * 60 * 60);
									 $ad_time          = $timestamp - ($ad_notif_time * 60 * 60);
									 $notif_date    = date("Y-m-d H:i:s", $time);


									 //~ $book_Arr['booking_time']      = $bk_time;
									 //~ $book_Arr['booking_endtime']   = $book_end;
									 //~ $book_Arr['total_minutes']     = $total_min;
									 //~ $book_Arr['total_buffer']      = $total_buffer;
									 //~ $book_Arr['total_price']       = $total_price;
									 //~ $book_Arr['total_discount']    = $total_dis;
									 //~ $book_Arr['pay_status']        = 'cash';
									 //~ $book_Arr['status']            = 'confirmed';
									 //~ $book_Arr['notification_date'] = $notif_date;
									 //~ $book_Arr['updated_on']        = date('Y-m-d H:i:s');
									 //~ $book_Arr['updated_by']        = $this->session->userdata('st_userid');
									 
								 if(!empty($info->booking_type=='guest'))
	                                 {							
									   $book_Arr['user_id']         = 0;
									   $book_Arr['booking_type']    = 'guest';
									   
									   if(!empty($info->fullname)){
										    $book_Arr['contact_number']  = $info->contact_number;
									        $book_Arr['email']           = $info->email;
									        $book_Arr['fullname']        = $info->fullname;
									        $book_Arr['gender']          = $info->gender;
										   
										   }
										else $book_Arr['fullname']       = "Walk-in";   
										
									    $userId                      = 0;
									   
								     }
									else{
										$book_Arr['user_id']        = $info->user_id;
										$book_Arr['booking_type']   = 'user';
										$userId                     = $info->user_id;						
										                  
										}
										 
									 if($ad_notif_time != '0'){
									 	 $ad_notif_date    = date("Y-m-d H:i:s", $ad_time);
									 	 $book_Arr['additional_notification_date'] = $ad_notif_date;
									 }


									 $book_Arr['notes']             = $info->notes;									
									 $book_Arr['total_time']        = $min;	 
									 $book_Arr['merchant_id']       = $info->merchant_id;
									 $book_Arr['book_id']  	        = get_last_booking_id($info->merchant_id);
									 $book_Arr['employee_id']       = $info->employee_id;
									 $book_Arr['booking_time']      = $bk_time;
									 $book_Arr['booking_endtime']   = $book_end;
									 $book_Arr['total_minutes']     = $total_min;
									 $book_Arr['total_buffer']      = $total_buffer;
									 $book_Arr['total_time']        = $total_buffer + $total_min;
									 $book_Arr['total_price']       = $total_price;
									 $book_Arr['total_discount']    = $total_dis;
									 $book_Arr['notification_date'] = $notif_date;
									 $book_Arr['seen_status'] = '1';
									 //$book_Arr['full_name']=$user_detail->first_name.' '.$user_detail->last_name;
									 //$book_Arr['contact_number']=$user_detail->mobile;
										//$book_Arr['email']=$user_detail->email;
									 $book_Arr['pay_status']        = 'cash';
									 $book_Arr['status']            = 'confirmed';
									 $book_Arr['created_by']        = $this->session->userdata('st_userid');
									 $book_Arr['created_on']        = date('Y-m-d H:i:s');
								
									$tid=$this->user->insert('st_booking',$book_Arr);
									


								if($tid)
								{
								//$this->user->delete('st_booking_detail',array('booking_id' => url_decode($_POST['reSchedule_id'])));
								 $boojkstartTime  = $bk_time;
								 
								foreach($booking_detail as $row){
									
									$detail_Arr = [];
									//$detail_Arr['booking_id']       = url_decode($_POST['reSchedule_id']);							
									$detail_Arr['mer_id']           = $info->merchant_id;
									$detail_Arr['emp_id']           = $info->employee_id;
									$detail_Arr['booking_id']       = $tid;
									$detail_Arr['service_type']     = $row->stype;
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
										$totalMin                       = $row->duration;							
										$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
										$detail_Arr['setuptime_start']  = $boojkstartTime;	
										$detail_Arr['setuptime_end']    = $setuEnd;	
										
										$boojkstartTime                 = $setuEnd;
								  }
									
									
									$detail_Arr['service_id']    = $row->service_id;
									if(!empty($row->name))         
									$detail_Arr['service_name']  = $row->name;
								   else                            
									$detail_Arr['service_name']  = $row->category_name;
									
									if(!empty($row->type) && $row->type=='open'){ 
										if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
										  {		  
											 if(!empty($row->discount_price)){
												$detail_Arr['price']           = $row->discount_price;
												$detail_Arr['discount_price']  = $row->price-$row->discount_price;
												}  
											  else $detail_Arr['price']        = $row->price;
										  }
										  else $detail_Arr['price']            = $row->price;
									   }
									 
									else $detail_Arr['price']                  = $row->price;
										 

									$detail_Arr['duration']                    = $row->duration;
									$detail_Arr['buffer_time']                 = $row->buffer_time;
									$detail_Arr['has_buffer']                 = $row->has_buffer;
									$detail_Arr['updated_on']                  = date('Y-m-d H:i:s');
									$detail_Arr['user_id']                     = $info->user_id;
									$detail_Arr['updated_by']                  = $this->session->userdata('st_userid');
									$this->user->insert('st_booking_detail',$detail_Arr,array('id'=>$row->id));
									

								}



								$this->data['main'] = "";
								///mail section
								$this->data['main'] = $this->user->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$tid),'st_booking.id,business_name,booking_time,book_id,st_booking.merchant_id,st_booking.user_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select email from st_users where id=st_booking.user_id) as usemail,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');

									if(!empty($this->data['main'])){

									$field  = "st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";  
									$whr    = array('booking_id'=>$tid);
									
									$this->data['booking_detail'] = $this->user->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
									
									$body_msg = str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_confirm_body"));
							        $MsgTitle = $this->lang->line("booking_confirm_title");
									
									if($info->booking_type=='guest'){
										
										if(!empty($info->email)){
									    	$email                                         = $info->email;
										    $this->data['booking_detail'][0]->first_name   = $info->fullname;
									       }
									       
										}
									else{
										if(!empty($this->data['main'][0]->user_notify)){
											 sendPushNotification($this->data['main'][0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $tid, 'booking_status' => 'confirmed' ,'click_action' => 'BOOKINGDETAIL'));
												// url_decode($tid)

											}
										$email = $this->data['main'][0]->usemail;
										}	

                                   if(!empty($email))
                                     {
									  $message = $this->load->view('email/booking_mail_from_marchant',$this->data, true);
									  $mail    = emailsend($email,$this->lang->line("styletimer_booking_confirmed"),$message,'styletimer');
								     }

									}
                                   $this->session->set_flashdata('success','Rebooking successfully made.');
                                   	$yrdata= strtotime($newtime);
					    			$ddd = date('d.m.Y', $yrdata);
					    			$yrda = strtotime($newtime);
					    			$ttt = date('H:i', $yrda);
						
									echo json_encode(array('success'=>1,'msg' => 'Eine erneute Buchung auf den '.$ddd.' um '.$ttt.'Uhr war erfolgreich.'));
									//echo json_encode(array('success'=>1));
								}
						  else
							 echo json_encode(array('success'=>0,'msg'=>'Sorry unable to process'));	
				  }
				else echo json_encode(array('success'=>0,'msg'=>'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar'));		
			   }	
			    else echo json_encode(array('success'=>0,'msg'=>'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar')); 
		}
	else echo json_encode(array('success'=>0,'msg'=>'You can not book this time.'));	
	
   }
   
 // delete booking parmanent
 
public function delete_booking($bid="",$url=""){
	    if(!empty($bid))
	    {
	       $id = url_decode($bid);
	       	       
	      $res = $this->user->delete('st_booking',array('id'=>$id));
	      if($res){
	         $this->user->delete('st_booking_detail',array('booking_id'=>$id));
	         $this->user->delete('st_booking_notification',array('booking_id'=>$id));
	         $this->user->delete('st_invoices',array('booking_id'=>$id));
	         $this->user->delete('st_review',array('booking_id'=>$id));
	         
	         //$redirect = base_url('merchant/'.$url);
	         $redirect= $_SERVER['HTTP_REFERER'];
	         $this->session->set_flashdata('success','Booking has been successfully deleted.');
	       }
	      else{
			   $this->session->set_flashdata('error','Something is wrong. Try again');
			   $redirect= $_SERVER['HTTP_REFERER'];
			  } 
		redirect($redirect);	  
	   }  
  }  
  
  public function delete_booking_ajax($bid="",$url=""){
	    if(!empty($_POST['bid']))
	    {
	       $id = url_decode($_POST['bid']);
		   $bookingdetail = $this->user->select_row('st_booking','id,booking_time',array('id'=>url_decode($_POST['bid'])));
		   // print_r($bookingdetail->booking_time);
		   $extinguish_booking_date_value = '';
		   if($bookingdetail) {
			  $extinguish_booking_date_value = date('Y-m-d', strtotime($bookingdetail->booking_time));
		   }
	       	       
	      $res =$this->user->delete('st_booking_detail',array('booking_id'=>$id));
	      if($res){
	          $this->user->delete('st_booking',array('id'=>$id));
	         $this->user->delete('st_booking_notification',array('booking_id'=>$id));
	         $this->user->delete('st_invoices',array('booking_id'=>$id));
	         //$this->user->delete('st_review',array('booking_id'=>$id));

			 if(isset($extinguish_booking_date_value) && $extinguish_booking_date_value!="") {
				$_SESSION['extinguish_booking_date_value'] = $extinguish_booking_date_value;
			}
	         
	         //$redirect = base_url('merchant/'.$url);
	         //$redirect= $_SERVER['HTTP_REFERER'];
	         //$this->session->set_flashdata('success','Booking has been successfully deleted.');
	          echo json_encode(array('success'=>1,'msg'=>'Booking has been successfully deleted.'));	  
	       }
	      else{
			   $this->session->set_flashdata('error','Something is wrong. Try again');
			   //$redirect= $_SERVER['HTTP_REFERER'];
			   echo json_encode(array('success'=>0,'msg'=>'You can not book this time.'));	  
			  } 
		//redirect($redirect);
		
	   }  
  }
  
public function reshedule_form()
   {
	
	  //echo '<pre>'; print_r($_POST); die;
	  
	  $eid                  = url_decode($_POST['emp_id']);	  
	  $activeday            = $this->user->select('st_availability','days',array('user_id'=>$eid,'type'=>'close'));
		      
	  $days                 = array();	  
	  if(!empty($activeday)) foreach($activeday as $row) $days[]=$row->days;
	  
	   $bookingdetai           = $this->user->select_row('st_booking','id,booking_time',array('id'=>url_decode($_POST['book_id'])));
	 // echo '<pre>'; print_r($bookingdetai); die;
	  if(!empty($bookingdetai)){
		  $this->data['postdate'] = date('Y-m-d',strtotime($bookingdetai->booking_time));
		  }

    $currentdate    = date('Y-m-d');
    $threemonthaftr = date('Y-m-d',strtotime("+3 months", strtotime($currentdate)));

	  $this->db->group_by('booking_time');
		   $nationalholidaysdays  = $this->user->select('st_booking','booking_time',array('booking_time >='=>$currentdate,'booking_endtime <='=>$threemonthaftr,'employee_id'=>$eid,'booking_type'=>'self','national_holiday'=>1));

	  $nationalDays = array();
	   if(!empty($nationalholidaysdays))
	   	 {
	   	 	foreach($nationalholidaysdays as $value) {
	   	 		$nationalDays[] = date('Y-m-d',strtotime($value->booking_time));
	   	 	}
	   	 }


	   $this->db->group_by('booking_time');
	$blockdays  = $this->user->select('st_booking','booking_time',array('booking_time >='=>$currentdate,'booking_endtime <='=>$threemonthaftr,'employee_id'=>$eid,'booking_type'=>'self','blocked_type'=>'full'));
//echo '<pre>'; print_r($blockdays); die;
         if(!empty($blockdays))
		   	 {
		   	 	foreach($blockdays as $value) {
		   	 		$nationalDays[] = date('Y-m-d',strtotime($value->booking_time));
		   	 	}
		   	 }
		   	 
		 $whereaas ="employee_id=".$eid." AND booking_type='self' AND ((booking_time>='".$currentdate."' AND booking_time<'".$threemonthaftr."') OR (booking_endtime>'".$currentdate."' AND booking_endtime<='".$threemonthaftr."') OR (booking_time<='".$currentdate."' AND booking_endtime>'".$currentdate."') OR (booking_time>'".$currentdate."' AND booking_endtime<='".$threemonthaftr."'))";
		   	          if(!empty($eid)){
				        
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
	  	  
	  $this->data['days']     = $days;
	  $this->data['emp_id']   = $_POST['emp_id'];
	  $this->data['book_id']  = $_POST['book_id'];
	  
	  $html = $this->load->view('frontend/common/resheduleform',$this->data,true);
	  
	  echo json_encode(['success'=>1,'html'=>$html]); die;
	
   
   }  


public function getopeninghours_forblock(){
	
	$mid = $this->session->userdata('st_userid');
		
	$days = 0;

	$nameOfDay     = date('l', strtotime($_POST['date']));

	$resTime = $this->user->select_row('st_availability','starttime,endtime',array('user_id'=>$mid,'type'=>'open','days'=>strtolower($nameOfDay)));

	while (empty($resTime)) {

		if ($days == 8) {
			break;
		}

		$days++;

		$nameOfDay     = date('l', strtotime('+'.($days * 24).' hours', strtotime($_POST['date'])));		
		$resTime = $this->user->select_row('st_availability','starttime,endtime',array('user_id'=>$mid,'type'=>'open','days'=>strtolower($nameOfDay)));
	}
	

	if(!empty($resTime)){	
				
		$endHtml = "";
		$startHtml = "";
		
		$tStart = strtotime($resTime->starttime);
		$tEnd   = strtotime($resTime->endtime);
		$tNow   = $tStart;
		$i = 0;
		
		while($tNow <= $tEnd){

			if($i==0) $startClass = 'checkFirstSlot';
			else $startClass = '';

			if($tNow==$tEnd) $endClass = 'checkLastSlot';
			else $endClass = '';

			$endHtml.='<li class="radiobox-image" data-id='.$i.'>
			<input type="radio" id="id_'.date("H-i",$tNow).'" name="endtime" class="'.$endClass.'" value="'.date("H:i:s",$tNow).'" data-val="'.date("H:i",$tNow).'">
			<label for="id_'.date("H-i",$tNow).'">'.date("H:i",$tNow).'</label>
			</li>';
			
			$startHtml.='<li class="radiobox-image" data-id='.$i.'>
			<input type="radio" id="idstart_'.date("H-i",$tNow).'" name="starttime" class="'.$startClass.'" value="'.date("H:i:s",$tNow).'" data-val="'.date("H:i",$tNow).'">
			<label for="idstart_'.date("H-i",$tNow).'">'.date("H:i",$tNow).'</label>
			</li>';
			$tNow = strtotime('+15 minutes',$tNow);

			$i++;
		} 
			
			
		$endtimes='<span class="label" id="levelEnd">'.$this->lang->line('End_Time').'</span>
						<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn timeSelected height56v levelEnd"></button>
						<ul class="secondSlotWrapper dropdown-menu mss_sl_btn_dm custome_scroll height200 over-flow-auto"
						style="max-height: none; overflow-x: auto !important;
						height: 200px !important;
						position: absolute; will-change: transform; top: 0px; left: 0px;
						transform: translate3d(0px, -158px, 0px);">'.$endHtml.'							
					</ul>
					<script>
					var firstClicked = 0;
					$(".secondSlotWrapper li").on("click",function(e){
						if (firstClicked == 0) {
							firstClicked = 1;
							let myid = $(this).data("id") - 1;
							$(".firstSlotWrapper > li[data-id="+myid+"] > input").click();
						}
					});
					</script>';

		$startTime='<span class="label" id="levelStart">'.$this->lang->line('Start_Time').'</span>
						<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn timeSelected height56v levelStart"></button>
						<ul class="firstSlotWrapper dropdown-menu mss_sl_btn_dm custome_scroll height200 over-flow-auto"  
						style="max-height: none; overflow-x: auto !important; 
						height: 200px !important;
						position: absolute; will-change: transform; top: 0px; left: 0px;
						transform: translate3d(0px, -158px, 0px);">'.$startHtml.'									
					</ul>
					<script>
					$(".firstSlotWrapper li").on("click",function(e){
						if (firstClicked == 0) {
							firstClicked = 1;
							let myid = $(this).data("id") + 1;
							$(".secondSlotWrapper > li[data-id="+myid+"] > input").click();
						}
					});
					</script>
					';				
					
		echo json_encode([
			'success' => 1,
			'starttime' => $startTime,
			'endtime' => $endtimes,
			'date' => date('d.m.Y', strtotime('+'.($days * 24).' hours', strtotime($_POST['date'])))
		]); die;
	} else{
		
		echo json_encode(['success'=>0,'starttime'=>'','endtime'=>'']); die;
	}	

	//echo "<pre>"; print_r($resTime); die;
	
}  
  
public function addclosetime(){
	 //print_r($_POST); die;
	 $mid = $this->session->userdata('st_userid');
    if(!empty($mid))
      {
		 if(!empty($_POST['closeid']))
		  {
			 $blockeid =url_decode($_POST['closeid']);
			if(isset($_POST['startdate']))
			   $startTime = date('Y-m-d 00:00:00',strtotime($_POST['startdate']));
		
		    if(isset($_POST['enddate']))
			   $endTime   = date('Y-m-d 23:00:00',strtotime($_POST['enddate']));
			   
			   $insertArr['booking_time']     = $startTime;
			   $insertArr['booking_endtime']  = $endTime;
			   $insertArr['notes']           = $_POST['block_note'];
			   
			    $this->db->where('blocked',$blockeid);
				$res = $this->db->update('st_booking',$insertArr);
				if($res){
					$this->session->set_flashdata('success','Ferien erfolgreich bearbeitet'); 
					echo json_encode(array('success' =>1,'url'=>"")); die;
					}
			
		  }else{
			   $blockedId  = 0;
			   if(isset($_POST['startdate']))
			   $startTime = date('Y-m-d 00:00:00',strtotime($_POST['startdate']));
		
		    if(isset($_POST['enddate']))
			   $endTime   = date('Y-m-d 23:00:00',strtotime($_POST['enddate']));
			  if(!empty($_POST['uid']) && $_POST['uid'] !='all'){
				         $insertArr                   = array();
						$insertArr['employee_id']     = url_decode($_POST['uid']);
						$insertArr['merchant_id']     = $mid;
						$insertArr['close_for']     = 1;
						
				
						/*$insertArr['booking_time']    = $_POST['date']." 00:00:00";
						$insertArr['booking_endtime'] = $_POST['date']." 23:00:00";*/
						$insertArr['blocked_type']    = 'close';
					
				/*else{*/
			            $insertArr['booking_time']     = $startTime;
						$insertArr['booking_endtime']  = $endTime;
					 /*}*/
					    
						//~ $insertArr['booking_time']    = $startTime;
						//~ $insertArr['booking_endtime'] = $endTime;
						
						$insertArr['notes']           = $_POST['block_note'];
						
						$insertArr['booking_type']    = 'self';
						$insertArr['blocked']         = $blockedId;
						$insertArr['blocked_perent']  = $blockedId;
						$insertArr['created_on']      = date('Y-m-d H:i:s');
						$insertArr['created_by']      = $mid;
												
						$res = $this->user->insert('st_booking',$insertArr);
						
						if($blockedId==0){	
						   $blockedId = $res;
						   $this->db->where('id',$res);
						   $this->db->update('st_booking',array('blocked'=>$res));
						  }	
						 $this->session->set_flashdata('success','Ferien erfolgreich hinzugefügt');   
				  
				  }
				else{  
		      $sql   = 'SELECT id FROM `st_users` WHERE access="employee" AND merchant_id='.$mid.' AND status !="deleted"';
			
			    $query = $this->db->query($sql);
			
			    $employeIds = $query->result();
			    
			    //$blockedId  = 0;
			    
			
			    
			    if(!empty($employeIds)){
					foreach($employeIds as $eids){
						$insertArr                    = array();
						$insertArr['employee_id']     = $eids->id;
						$insertArr['merchant_id']     = $mid;
						
				
						/*$insertArr['booking_time']    = $_POST['date']." 00:00:00";
						$insertArr['booking_endtime'] = $_POST['date']." 23:00:00";*/
						$insertArr['blocked_type']    = 'close';
					
				/*else{*/
			            $insertArr['booking_time']     = $startTime;
						$insertArr['booking_endtime']  = $endTime;
					 /*}*/
					    
						//~ $insertArr['booking_time']    = $startTime;
						//~ $insertArr['booking_endtime'] = $endTime;
						
						$insertArr['notes']           = $_POST['block_note'];
						
						$insertArr['booking_type']    = 'self';
						$insertArr['blocked']         = $blockedId;
						$insertArr['blocked_perent']  = $blockedId;
						$insertArr['created_on']      = date('Y-m-d H:i:s');
						$insertArr['created_by']      = $mid;
												
						$res = $this->user->insert('st_booking',$insertArr);
						
						if($blockedId==0){	
						   $blockedId = $res;
						   $this->db->where('id',$res);
						   $this->db->update('st_booking',array('blocked'=>$res));
						  }	
						 $this->session->set_flashdata('success','Ferien erfolgreich hinzugefügt');   				
						}
					
					}
				 }	
			 }
				echo json_encode(array('success' =>1,'url'=>"")); die;
	   
	  }
	
	} 

function delete_closetime($id=""){
    if(!empty($id)){
		$bid = url_decode($id);
		  $this->db->where('blocked',$bid);
		  $this->db->delete('st_booking');
		  
		$this->session->set_flashdata('success','Ferien erfolgreich gelöscht');  
		}
		redirect(base_url('merchant/closed_date'));	
	
	}


}
