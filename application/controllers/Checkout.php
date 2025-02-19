<?php

require_once(APPPATH.'third_party/dompdf2/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;

defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends Frontend_Controller {

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
		//$this->load->model('Ion_auth_model','ion_auth');
	}
	
	
  //**** Get service assign ****//
  public function get_all_services()
	{ //echo "getALl"; die;
		$this->data['search'] = (isset($_POST['search'])?$_POST['search']:'');
		$this->data['services'] = $this->user->get_all_service_of_merchant($this->session->userdata('st_userid'),$this->data['search']);
		
		//$this->data['getarr1'] =  $getarr1;
		    //$this->data['eid'] = $_POST['eId'];
		//echo '<pre>'; print_r($this->data); die;
		 $view = $this->load->view('frontend/common/selectservice_checkout', $this->data, true); 
		
		echo json_encode(array('success' =>1,'html'=>$view)); die;

	}		
	
public function detail($id="")
	{  
		if(!empty($id))
		 {
			 if(!empty($_SERVER['HTTP_REFERER'])){
			 	if($_SERVER['HTTP_REFERER']==base_url('merchant/booking_listing')){
			 		$this->data['redirect_url'] ='booking_listing';
			 	}else{
			 		$this->data['redirect_url'] ='dashboard';
			 	}

			  }
			  else{
			  	$this->data['redirect_url'] ='dashboard';
			  }
	        $bid = url_decode($id);
	        
			$this->data['detail'] = $this->booking->join_two_row('st_booking','st_users','employee_id','id',array('st_booking.id' =>$bid),'st_booking.id,st_booking.user_id,st_booking.merchant_id,st_booking.booking_time,st_booking.employee_id,st_booking.status,st_booking.booking_type,st_booking.fullname,st_users.first_name,st_users.last_name');
		
			if(!empty($this->data['detail']) && $this->data['detail']->status !='completed')
				{
				$field = "st_booking_detail.id,st_booking_detail.user_id,setuptime_start,st_booking_detail.service_type,st_booking_detail.setuptime_end,st_booking_detail.finishtime_end,first_name,last_name,profile_pic,service_name,duration,price,discount_price,service_id,(select price_start_option from st_merchant_category WHERE id=st_booking_detail.service_id) as price_start_option,(SELECT tax_id FROM st_merchant_category WHERE id=st_booking_detail.service_id) as tax_id";  
				
				$whr   = array('booking_id'=>$bid);
				                                $this->db->order_by('st_booking_detail.id','asc');
				$this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field); 
                                                  // echo $this->db->last_query();die;
				//$sql2  = "SELECT `subcategory_id` FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` WHERE `r`.`status` = 'active' AND `r`.`created_by`=".$this->data['detail']->merchant_id." GROUP BY `r`.`subcategory_id` LIMIT 4";
				//$this->data['all_service']=$this->user->custome_query($sql2,'result');
				
				
				
				$this->data['allemployee'] = $this->booking->select('st_users','id,first_name,last_name',array('status'=>'active','access'=>'employee','merchant_id'=>$this->data['detail']->merchant_id));
				
			//  $this->data['payment_methods'] = $this->booking->select('st_merchant_payment_method','id,method_name,defualt',array('status'=>'active','user_id'=>$this->data['detail']->merchant_id));
			  
				//echo "<pre>"; print_r($this->data); die;
				$this->load->view('frontend/marchant/checkout',$this->data);
				}
			else{
				$this->session->set_flashdata('error','This booking already completed.');
				redirect($_SERVER['HTTP_REFERER']);
			   }
		 }
	 else redirect(base_url());
	 
	 
	}
	

public function add_more_service(){
	          extract($_POST);
	          
	          $taxHtml="";
	          $count =  $count+1;
	          if(!empty($tax_id)){
				   $taxRate = get_tax_details($tax_id); 
								if(!empty($taxRate)){  
									
								$taxcalculated = ($price*100/(100+$taxRate->price))*$taxRate->price/100;
																	                      
								$taxHtml='
								<input type="hidden" name="serviceCount[]" value="'.$count.'">
								<input type="hidden" name="taxids[]" value="'.$tax_id.'">
								<input type="hidden" name="tax_name_'.$tax_id.'" value="'.$taxRate->tax_name.' '.$taxRate->price.'%">
								<input type="hidden" name="tax_price_'.$tax_id.$count.'" data-taxrate="'.$taxRate->price.'" class="taxn'.$count.'" value="'.round($taxcalculated,3).'"><span class="color666 fontfamily-regular font-size-14" style="display: block;width: 162px;margin-left:auto;text-align:right;">
										 <span>'.price_formate($taxRate->price).'% '.$taxRate->tax_name.' : </span><span class="tax_textn'.$count.'">'.price_formate(round($taxcalculated,2)).' €</span>
                                </span>';
							 
							 }  
						} 
				  else{
					   $taxHtml='<input type="hidden" name="taxids[]"  value="0"><input type="hidden" name="serviceCount[]"  value="'.$count.'">';
					  }
			     
			     $sub_name=get_subservicename($service_id);
			   
	                    $html='<div class="card box-shadow1 mb-3 removeServicenew'.$count.'">
                          <div class="card-header" id="checkOnedropnew'.$count.'">
                            <div class="mb-0 d-flex justify-content-between">
                            <div class="relative pl-30 pt-3 pb-2 pr-3 w-100 collapsed" data-toggle="collapse" data-target="#checkcollapseOnenew'.$count.'" aria-expanded="true" aria-controls="checkcollapseOne" style="margin:-12px;">
                                <h5 class="fontfamily-medium color333 font-size-18 mb-0">'.$sub_name.($sub_name == $name ? '' : (' - '. $name)) .'</h5>
                                <span class="color666 font-size-14 fontfamily-regular">'.$duration.' Min. '.$this->lang->line('with').' <span class="empnamerplc">'.$employee_name.'</span></span>
                            </div>
                            <input type="hidden" placeholder="1" name="service_id[]" value="'.$service_id.'">
                            <input type="hidden" name="booking_detailIds[]" value="0">
                             <input type="hidden" name="discount[]" class="discountn'.$count.'" value="0">	  
                            
                              <div class="d-flex">
                                <div class="relative text-right mr-3">
                                  <p class="color333 fontfamily-medium mb-0"><span class="pricementax_n'.$count.'">'.price_formate(round($price,2)).'</span> €</p>
								    <a href="#" class="font-size-14 colorcyan a_hover_cyan display-b addDiscountperticulerService discountOptn'.$count.'" data-id="n'.$count.'" data-amount="'.$price.'"  style="width:140px;" >
									<span>+ '.$this->lang->line('Add_Discount').'</span> 
									
								</a>
                                <span class="font-size-14 colorcyan a_hover_cyan display-b dicounttextn'.$count.'" style="width: 140px;"></span>
                                </div>
                                <span class="checkout-crose-icon removeServiceCross" data-id="removeServicenew'.$count.'">
                                  <img class="" src="'.base_url("assets/frontend/images/crose_grediant_orange_icon1.svg").'">
                                </span>
                                
                              </div>
                            </div>
                            '.$taxHtml.'
                          </div>
                      
                          <div id="checkcollapseOnenew'.$count.'" class="collapse" aria-labelledby="checkOnecheckOnedropnew'.$count.'" data-parent="#accordion">
                            <div class="card-body">
                              <div class="row">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2 ">
                                  <div class="form-group">
                                    <span class="label">'.$this->lang->line('quantity').'</span>
                                      <label class="inp">
                                        <input type="text" placeholder="1" class="form-control" style="background-color: #e9ecef;" readonly>
                                      </label>                                                
                                  </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
                                  <div class="form-group">
                                    <span class="label">'.ucfirst(strtolower($this->lang->line('Price'))).'</span>
                                      <label class="inp">
                                        <span class="rate-fixed">€</span>
                                        <input type="text" data-number="n'.$count.'" name="price[]" value="'.price_formate($price).'" placeholder="&nbsp;" class="form-control priceInputChange">
                                      </label>                                                
                                  </div>
                                </div>
                               
								
                              </div>
                            </div>
                          </div>
                        </div>'; 
                        
                  echo json_encode(array('success' =>1,'html'=>$html,'count'=>$count)); die;
	
	}
public function calculation(){
	
	        extract($_POST); 
	        
	        $totalTax   = 0;
	        $totalPrice =0;
	        if(!empty($price)){
				foreach($price as $pr){
					$totalPrice =$totalPrice+price_formate($pr,'en');
					}
				}
	        
	        //$totalPrice = array_sum($price);
	        	        
            $taxArr     = array();

	        if(!empty($taxids)){
				  $taxId = array_unique($taxids);
				  
				  foreach($taxId as $tid){
					     $taxUniqtotal=0;
					    
					   if(!empty($tid))
					     { 
					     foreach($serviceCount as $ct){
							 
							 if(!empty($ct) && !empty($_POST['tax_price_'.$tid.$ct])){
							      $taxUniqtotal = $taxUniqtotal+$_POST['tax_price_'.$tid.$ct];
							      $totalTax     = $totalTax+$_POST['tax_price_'.$tid.$ct];
						       }
						       
							 }							 
						 $taxArr[$_POST['tax_name_'.$tid]] = $taxUniqtotal;				  
					  }
				    }
				}
				
			$subtotal  =  $totalPrice-round($totalTax,2);	
				
			
                           
          
		  $discount  = array_sum($_POST['discount']);	
		  $discountedPrice = $totalPrice-$discount;
		 		             
         $payTotal = round($tipamount+$discountedPrice,2);   
         $subtotal = $subtotal-$discount;
         
         $subtotalHtml='<div class="d-flex justify-content-between w-100">
                            <span class="font-size-16 color333 fontfamily-medium">'.$this->lang->line('subtotal').'</span>
                            <span class="font-size-16 color333 fontfamily-medium" id="getSubtotal" data-val="'.round($subtotal,3).'">'.price_formate(round($subtotal,2)).' €</span>
                           </div>';
         
          if(!empty($taxArr)){
				  foreach($taxArr as $k=>$v)
				     {
					   $subtotalHtml.='<div class="d-flex justify-content-between w-100">
                            <span class="font-size-16 color333 fontfamily-medium">'.$k.'</span>
                            <span class="font-size-16 color333 fontfamily-medium">'.price_formate(round($v,2)).' €</span>
                           </div>';
					 }
				} 
         
         echo json_encode(array('success' =>1,'html'=>$subtotalHtml,'total'=>$subtotal,'paytotal'=>price_formate($payTotal),'discount'=>price_formate($discount))); die;               
	        //$taxArr['subtotal'] = $totalPrice-$totalTax;	
	       
	        
	        
	        //echo "<pre>"; print_r($taxArr); die;
	        
	
	    }

public function save_invoice(){
	
	extract($_POST); 
	        
	//echo '<pre>'; print_r($_POST); die;
	        
   	if(!empty($booking_id)) { 
		$totalTax   = 0;
		$totalPrice =0;
		if(!empty($price)){
			foreach($price as $pr){
				$totalPrice =$totalPrice+price_formate($pr,'en');
			}
		}
	        
	        	        
		$taxArr     = array();

		if(!empty($taxids)){
			$taxId = array_unique($taxids);
				
			foreach($taxId as $tid){
				$taxUniqtotal=0;
				if(!empty($tid))
				{ 
					foreach($serviceCount as $ct){
							
						if(!empty($ct) && !empty($_POST['tax_price_'.$tid.$ct])){
								$taxUniqtotal = $taxUniqtotal+$_POST['tax_price_'.$tid.$ct];
								$totalTax     = $totalTax+$_POST['tax_price_'.$tid.$ct];
						}
						
					}							 
					$taxArr[$_POST['tax_name_'.$tid]] = $taxUniqtotal;	
				}			  
			}
				
		}
			
		$subtotal  =  $totalPrice-round($totalTax,2);	
			
		//~ $subtotalHtml='<div class="d-flex justify-content-between w-100">
						//~ <span class="font-size-16 color333 fontfamily-medium">Subtotal</span>
						//~ <span class="font-size-16 color333 fontfamily-medium" id="getSubtotal" data-val="'.round($subtotal,3).'">€'.round($subtotal,2).'</span>
						//~ </div>';
		$taxjson = "";              
		//echo '<pre>'; print_r($taxArr); die;
		if(!empty($taxArr)){
			$taxjson=json_encode($taxArr);
				//~ foreach($taxArr as $k=>$v)
					//~ {
					//~ $subtotalHtml.='<div class="d-flex justify-content-between w-100">
						//~ <span class="font-size-16 color333 fontfamily-medium">'.$k.'</span>
						//~ <span class="font-size-16 color333 fontfamily-medium">€'.round($v,2).'</span>
						//~ </div>';
					//~ }
		} 
		$discountamount = price_formate($discountamount,'en');
		$discountedPrice = $totalPrice-$discountamount;
		$subtotal        =	$subtotal-$discountamount;	             
		$payTotal        = round($tipamount+$discountedPrice,2); 
		$BookpayTotal    = round($discountedPrice,2);

		$changedEmpId = url_decode($changed_employee);

		$invoce               = array();  
			
		$invoce['booking_id']   = $booking_id;       
		$invoce['subtotal']     = $subtotal;       
		$invoce['emp_id']       = $changedEmpId;       
		$invoce['total']        = $payTotal;       
		$invoce['paytotal']     = price_formate($totalPayInput,'en');       
		$invoce['taxes']        = $taxjson;       
		$invoce['tip']          = $tipamount;       
		$invoce['discount']     = $discountamount;       
		$invoce['payment_type'] = $payment_type;       
		$invoce['details']      = $detail;  
				
		$invoce['pay_recieved_by']      = $changedEmpId;       
		
		$invoiceId = $this->booking->insert('st_invoices',$invoce);
		$emp_comm  = $this->booking->select_row('st_users','commission',array('id'=>$changedEmpId));

		if($invoiceId) {
			$updateBook  = array();	 
			$updateBook['status']      = 'completed';	 
			$updateBook['invoice_id']  = $invoiceId;	 
			$updateBook['updated_on']  = date('Y-m-d H:i:s');	 
			$updateBook['updated_by']  = $this->session->userdata('st_userid');	 
			$updateBook['total_price'] = $BookpayTotal;//$payTotal;
			$updateBook['employee_id'] = $changedEmpId;
			if(!empty($emp_comm->commission)){
				$pp = $payTotal-round($tipamount, 2);
				//$cc = ($payTotal*$emp_comm->commission)/100;
				$cc = (($pp)*$emp_comm->commission)/100;
				$updateBook['emp_commission'] = $cc;	
			}

			//$updateBook['total_discount'] = $discountamount;
			$this->user->update('st_booking',$updateBook,array('id'=>$booking_id));

			$field="st_booking.user_id,st_booking.merchant_id,st_booking.total_time,booking_type,book_id,fullname,st_booking.email as guestemail,u1.first_name as fname,u1.email as email,u1.last_name as lname,u2.business_name as salon_name,u1.notification_status as user_notify,u2.auto_send_invoice";
				//$info=$this->user->select_row('st_booking',$field,array('id'=>$booking_id));
				
				//$this->data['invoice_detail'] = $this->booking->join_three_row('st_invoices','st_booking','st_users','booking_id','id','employee_id','id',array('st_invoices.id'=>$invoiceId),$field);
			$info = $this->booking->join_three_row_from_same_table_for_two_users('st_booking','st_users','st_users','user_id','id','merchant_id','id',array('st_booking.id'=>$booking_id),$field);
				
				//echo $this->db->last_query(); print_r($info); die;
				
			if($info->booking_type == 'guest'){
					$first_name= ucwords($info->fullname);
					$last_name="";
					$emailsend= $info->guestemail;
			}
			else{
				$first_name= ucwords($info->fname);
				$last_name= ucwords($info->lname);
				$emailsend= $info->email;
			}

				

			$body_msg = str_replace('*salonname*',$info->salon_name,$this->lang->line("booking_complete_body"));
			$MsgTitle = $this->lang->line("booking_complete_title");
			if($info->booking_type != 'guest' && $info->user_notify != 0){
				$ress=sendPushNotification($info->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$info->merchant_id ,'book_id'=> $booking_id,'booking_status' =>'completed','click_action' => 'BOOKINGDETAIL'));
			}

			$message = $this->load->view('email/booking_complete',array("fname"=> $first_name,"lname"=>$last_name,"salon_name"=>$info->salon_name,"bookid" => url_encode($booking_id),'book_id'=>$info->book_id, 'duration'=>$info->total_time), true);
			$mail = emailsend($emailsend,'styletimer - Buchung abgeschlossen',$message,'styletimer'); 
					
				
			if(!empty($service_id))
			{
				$boojkstartTime = $lastServicebookTime;
				
				$update_total_minut_fieldInbooking = 0;
				$update_total_buffer_fieldInbooking = 0;
				
				for($i=0;$i<count($service_id);$i++)
				{
					if(empty($booking_detailIds[$i])){
						
						$row = $this->booking->select_row('st_merchant_category','st_merchant_category.*,(SELECT category_name FROM st_category WHERE st_category.id=st_merchant_category.subcategory_id) as category_name',array('id'=>$service_id[$i]));
						$detail_Arr                  = array();
						$detail_Arr['show_calender'] = 1;						        
						$detail_Arr['mer_id']        = $merchant_id;
						$detail_Arr['emp_id']        = $changedEmpId;
						$detail_Arr['service_type']  = $row->type;
						if ($row->buffer_time > 0)
							$detail_Arr['has_buffer']   = 1;
						if($row->type==1){
																
							$detail_Arr['setuptime']        = $row->setuptime;
							$detail_Arr['processtime']      = $row->processtime;
							$detail_Arr['finishtime']       = $row->finishtime;
							$detail_Arr['setuptime_start']  = $boojkstartTime;										 
																		
							$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$row->setuptime.' mine'));
							$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$row->processtime.' mine'));
							$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' mine'));
							
							$detail_Arr['setuptime_end']    = $setuEnd;	
							$detail_Arr['finishtime_start'] = $finishStart;	
							$detail_Arr['finishtime_end']   = $finishEnd;
																			
							$boojkstartTime                 = $finishEnd;
							
							$total = $row->setuptime+$row->processtime+$row->finishtime;
							
							$update_total_minut_fieldInbooking = $update_total_minut_fieldInbooking+$total;
								
						}else{
							$totalMin                       = $row->duration+$row->buffer_time;
							
							$update_total_minut_fieldInbooking        = $update_total_minut_fieldInbooking+$row->duration;
							$update_total_buffer_fieldInbooking = $update_total_buffer_fieldInbooking+$row->buffer_time;
							
							$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' mine'));												
							$detail_Arr['setuptime_start']  = $boojkstartTime;	
							$detail_Arr['setuptime_end']    = $setuEnd;	
							
							$boojkstartTime                 = $setuEnd;
						}
							
							
						$detail_Arr['booking_id']       = $booking_id;
						$detail_Arr['service_id']       = $service_id[$i];
						if(!empty($row->name))
							$detail_Arr['service_name'] = $row->name;
						else
							$detail_Arr['service_name'] = $row->category_name;
							
				
								
						$detail_Arr['price']                      = price_formate($price[$i],'en');
						$detail_Arr['duration']                   = $row->duration+$row->buffer_time;
						$detail_Arr['buffer_time']                = $row->buffer_time;
						$detail_Arr['created_on']                 = date('Y-m-d H:i:s');
						//$detail_Arr['discount_price']			  = $discount[$i];
						if(!empty($user_id))
						$detail_Arr['user_id']                    = $user_id;
						
						$detail_Arr['created_by']                 = $this->session->userdata('st_userid');
						
						if(!empty($taxids[$i])){
						$taxprice                          = $_POST['tax_price_'.$taxids[$i].$serviceCount[$i]];
						$detail_Arr['tax']      = $_POST['tax_name_'.$taxids[$i]].' :'.price_formate(round($taxprice,2)).' €';
						}
						
						
						$this->user->insert('st_booking_detail',$detail_Arr);
											
					}
					else
					{
						$detail_Arr                     = array();									 
						$detail_Arr['price']            = price_formate($price[$i],'en');
						$detail_Arr['updated_by']       = $this->session->userdata('st_userid');
						$detail_Arr['emp_id']        = $changedEmpId;
						$detail_Arr['updated_on']       = date('Y-m-d H:i:s');	
						// $detail_Arr['discount_price']			  = $discount[$i];

						if(!empty($taxids[$i])){
							$taxprice               = $_POST['tax_price_'.$taxids[$i].$serviceCount[$i]];
							$detail_Arr['tax']      = $_POST['tax_name_'.$taxids[$i]].' :'.price_formate(round($taxprice,2)).'€';
						}
												
							
						$this->user->update('st_booking_detail',$detail_Arr,array('id'=>$booking_detailIds[$i]));
							
					}	 
					
				}
				
				if($update_total_minut_fieldInbooking>0){
					$update_total_time_fieldInbooking = $update_total_minut_fieldInbooking+$update_total_buffer_fieldInbooking;
					$updateQury = 'UPDATE st_booking SET total_minutes=total_minutes+'.$update_total_minut_fieldInbooking.',total_time=total_time+'.$update_total_time_fieldInbooking.',total_buffer=total_buffer+'.$update_total_buffer_fieldInbooking.' WHERE id='.$booking_id;
					
					$this->db->query($updateQury);
					// echo $this->db->last_query(); die;
				}	
				
			}
			if($info->auto_send_invoice==1)
			{
				if(!empty($emailsend)){
					$this->autometic_send_invoice_in_mail($invoiceId,$emailsend);
				}
			}
			$_SESSION['booking_complete_success'] = 'Buchung erfolgreich abgeschlossen'; 
			redirect(base_url('checkout/viewinvoice/'.url_encode($invoiceId)));
		}   
	}     
	
}

function viewinvoice($id=""){
	if(!empty($id))
	  {
		$invoiceId = url_decode($id);
		
		$field = "st_invoices.*,st_booking.employee_id,st_booking.merchant_id,book_id,book_by,total_time,booking_time,st_users.first_name,st_users.last_name,st_booking.booking_type,st_booking.updated_on,fullname,(SELECT concat(first_name,' ',last_name) FROM st_users as su WHERE su.id=st_invoices.pay_recieved_by) as recievedByname";
		
	   // $field2 = "st_invoices.*,st_booking.employee_id,st_booking.merchant_id,book_id,book_by,total_time,booking_time,st_users.first_name,st_users.last_name,st_booking.booking_type,st_booking.updated_on,fullname,st_merchant_category.subcategory_id,(SELECT concat(first_name,' ',last_name) FROM st_users as su WHERE su.id=st_invoices.pay_recieved_by) as recievedByname,(select category_name from st_category where id=st_merchant_category.subcategory_id) as sub_name";
		
		$this->data['invoice_detail'] = $this->booking->join_three_row('st_invoices','st_booking','st_users','booking_id','id','employee_id','id',array('st_invoices.id'=>$invoiceId),$field);
		  if(!empty($this->data['invoice_detail']->booking_id))
		    {
			  $field2 = "st_booking_detail.id,st_booking_detail.user_id,st_booking_detail.tax,setuptime_start,first_name,last_name,profile_pic,email,address,zip,country,city,st_merchant_category.name,st_booking_detail.duration,st_booking_detail.price,st_booking_detail.discount_price,service_id,(select category_name from st_category where id=st_merchant_category.subcategory_id) as sub_name";  
				
				$whr    = array('booking_id'=>$this->data['invoice_detail']->booking_id);
				                                $this->db->order_by('st_booking_detail.id','asc');
				$this->data['booking_detail'] = $this->booking->join_three_booking('st_booking_detail','st_users','st_merchant_category','user_id','id','service_id','id',$whr,$field2); 
				
               $this->data['slondetail']  = $this->booking->select_row('st_users','business_name,email,address,zip,country,city,tax_number',array('id'=>$this->data['invoice_detail']->merchant_id));
				
			}
				
    //echo "<pre>"; print_r($this->data); die;
		$this->load->view('frontend/marchant/invoice',$this->data);
      }
	
  }
  
function printinvoice($id="",$type=""){
	
	   	if(!empty($id))
		  {
			$invoiceId = url_decode($id);
			
			$field = "st_invoices.*,st_booking.employee_id,st_booking.merchant_id,book_id,book_by,total_time,booking_time,st_users.first_name,st_users.last_name,st_booking.booking_type,st_booking.updated_on,fullname";
			
			$this->data['invoice_detail'] = $this->booking->join_three_row('st_invoices','st_booking','st_users','booking_id','id','employee_id','id',array('st_invoices.id'=>$invoiceId),$field);
		
			  if(!empty($this->data['invoice_detail']->booking_id))
				{
				 $field2 = "st_booking_detail.id,st_booking_detail.user_id,st_booking_detail.tax,setuptime_start,first_name,last_name,profile_pic,email,address,zip,country,city,st_merchant_category.name,st_booking_detail.duration,st_booking_detail.price,st_booking_detail.discount_price,service_id,(select category_name from st_category where id=st_merchant_category.subcategory_id) as sub_name";  
				
				$whr    = array('booking_id'=>$this->data['invoice_detail']->booking_id);
				                                $this->db->order_by('st_booking_detail.id','asc');
				$this->data['booking_detail'] = $this->booking->join_three_booking('st_booking_detail','st_users','st_merchant_category','user_id','id','service_id','id',$whr,$field2); 
					
				   $this->data['slondetail']  = $this->booking->select_row('st_users','business_name,email,address,zip,country,city,tax_number',array('id'=>$this->data['invoice_detail']->merchant_id));
					
				}
					
		//echo "<pre>"; print_r($this->data); die;
			$htmlIn = $this->load->view('frontend/common/send_and_download_invoice',$this->data,true);
			//echo $htmlIn; 
			$options = new Options();
			$options->set('isRemoteEnabled', true);
			$dompdf = new Dompdf($options);
			$dompdf->set_paper("A4");
			$dompdf->loadHtml($htmlIn,'UTF-8');
			$dompdf->set_option('enable_remote', TRUE);
			$dompdf->set_option('enable_css_float', TRUE);
			$dompdf->set_option('enable_html5_parser', TRUE);
			$dompdf->render();
			//$output = $dompdf->output();
			//file_put_contents('receipt.pdf', $output);
		    $filename = 'invoice';
			//$dompdf->stream('receipt.pdf');
			$f_name = 'quittung-'.$this->data['invoice_detail']->book_id.'.pdf';	
			if($type=="download")
				$dompdf->stream($f_name,array("Attachment"=>1));
			else
				$dompdf->stream($f_name,array("Attachment"=>0));
			//1  = Download
			//0 = Preview	

		  }
				
				//~ echo $pdfHtml; die;
				
	}  

function invoice_send_in_email(){
	   
	   extract($_POST);
	
	   	if(!empty($id))
		   {
			$invoiceId = url_decode($id);
			
			$field = "st_invoices.*,st_booking.employee_id,st_booking.merchant_id,book_id,book_by,total_time,booking_time,st_users.first_name,st_users.last_name,st_booking.booking_type,st_booking.updated_on,fullname";
			
			$this->data['invoice_detail'] = $this->booking->join_three_row('st_invoices','st_booking','st_users','booking_id','id','employee_id','id',array('st_invoices.id'=>$invoiceId),$field);
		
			  if(!empty($this->data['invoice_detail']->booking_id))
				{
				   $field2 = "st_booking_detail.id,st_booking_detail.user_id,st_booking_detail.tax,setuptime_start,first_name,last_name,profile_pic,email,address,zip,country,city,st_merchant_category.name,st_booking_detail.duration,st_booking_detail.price,st_booking_detail.discount_price,service_id,(select category_name from st_category where id=st_merchant_category.subcategory_id) as sub_name";  
				
				$whr    = array('booking_id'=>$this->data['invoice_detail']->booking_id);
				                                $this->db->order_by('st_booking_detail.id','asc');
				$this->data['booking_detail'] = $this->booking->join_three_booking('st_booking_detail','st_users','st_merchant_category','user_id','id','service_id','id',$whr,$field2); 
				//print_r($this->data['booking_detail']);	
					
				   $this->data['slondetail']  = $this->booking->select_row('st_users','business_name,email,address,zip,country,city',array('id'=>$this->data['invoice_detail']->merchant_id));
					
				}
					
		    //echo "<pre>"; print_r($this->data); die;
			$htmlIn = $this->load->view('frontend/common/send_and_download_invoice',$this->data,true);
			$options = new Options();
			$options->set('isRemoteEnabled', true);
			$dompdf = new Dompdf($options);
			// $dompdf->loadHtml($htmlIn,'UTF-8');
			// $dompdf->set_option('enable_remote', TRUE);
			// $dompdf->set_option('enable_css_float', TRUE);
			// $dompdf->set_option('enable_html5_parser', FALSE);
			// $dompdf->render();
			// $filename = 'order.pdf';
			// //$dompdf->stream($filename);
			// $output = $dompdf->output();
			//$this->load->library('dompdf_gen');
// 			mail to: "Deine Quittung - styletimer"

// and text in mail to: "Im Anhang findest du deine Quittung von 'Salonname'"
			$dompdf->loadHtml($htmlIn);
			$dompdf->render();       
$output = $dompdf->output();
file_put_contents(APPPATH.'Invoice.pdf', $output);
chmod(APPPATH.'Invoice.pdf', 0777);
$email=$_POST['email'];
$subject="styletimer - deine Quittung";
$message="Im Anhang findest du deine Quittung von".' '.$this->data['slondetail']->business_name;
//print_r($message);
//$this->sendEmail($email,$subject,$message);
$config = Array(
    'protocol' => 'smtp',
	'smtp_host' => getenv('SMTP_HOST'),
	'smtp_port' => getenv('SMTP_PORT'),
	'smtp_user' => getenv('SMTP_USER'),
	'smtp_pass' => getenv('SMTP_PASS'),
	'mailtype' => 'html',
	'crlf' => "\r\n",
	'newline' => "\r\n"
);
$this->load->library('email', $config);
$this->email->initialize($config);
$this->email->set_mailtype("html");
$this->email->set_newline("\r\n");
$this->email->from('info@dev.styletimer.de');
$this->email->to($email);
$this->email->subject($subject);
$this->email->message($message);
$this->email->attach(APPPATH.'Invoice.pdf', 'attachment', 'Quittung'.$this->data['invoice_detail']->booking_id.'.pdf');
$mail = $this->email->send();
if($mail){
	
	echo json_encode(array('success'=>1, 'url' =>'','msg'=>'Invoice sent successfully on your email.'));
	return true;
}
// if($this->email->send())
// {
// 	echo json_encode(array('success'=>1, 'url' =>'','msg'=>'Invoice sent successfully on your email.'));
// }
// else
// {
//  show_error($this->email->print_debugger());die;
// }
            
				
			
			
			//$mail    = emailsend($_POST['email'],'styletimer - Booking Invoice',$htmlIn,'styleTimer',$output,'','','pdf');

			
					
	
		  }

	}	


function autometic_send_invoice_in_mail($invoiceId,$email){
	   
	 
	   	if(!empty($invoiceId))
		   {
			
			
			$field = "st_invoices.*,st_booking.employee_id,st_booking.merchant_id,book_id,book_by,total_time,booking_time,st_users.first_name,st_users.last_name,st_booking.booking_type,st_booking.updated_on,fullname";
			
			$this->data['invoice_detail'] = $this->booking->join_three_row('st_invoices','st_booking','st_users','booking_id','id','employee_id','id',array('st_invoices.id'=>$invoiceId),$field);
		
			  if(!empty($this->data['invoice_detail']->booking_id))
				{
				  $field2 = "st_booking_detail.id,st_booking_detail.user_id,st_booking_detail.tax,setuptime_start,first_name,last_name,profile_pic,email,address,zip,country,city,service_name,st_booking_detail.duration,st_booking_detail.price,st_booking_detail.discount_price,service_id,(select category_name from st_category where id=st_merchant_category.subcategory_id) as sub_name";  
				
				$whr    = array('booking_id'=>$this->data['invoice_detail']->booking_id);
				                                $this->db->order_by('st_booking_detail.id','asc');
				$this->data['booking_detail'] = $this->booking->join_three_booking('st_booking_detail','st_users','st_merchant_category','user_id','id','service_id','id',$whr,$field2); 
					 
					
				   $this->data['slondetail']  = $this->booking->select_row('st_users','business_name,email,address,zip,country,city',array('id'=>$this->data['invoice_detail']->merchant_id));
					
				}
					
		//echo "<pre>"; print_r($this->data); die;
			        $htmlIn = $this->load->view('frontend/common/send_and_download_invoice',$this->data,true);
			
									
					$options = new Options();
					$options->set('isRemoteEnabled', true);
					$dompdf = new Dompdf($options);
					$dompdf->loadHtml($htmlIn,'UTF-8');
					$dompdf->set_option('enable_remote', TRUE);
					$dompdf->set_option('enable_css_float', TRUE);
					$dompdf->set_option('enable_html5_parser', FALSE);
					$dompdf->render();
					$filename = 'order.pdf';
					//$dompdf->stream($filename);
					$output = $dompdf->output();
			
		         	$mail    = emailsend($email,'styletimer - deine Quittung',$htmlIn,'styletimer',$output,'quittung-'.$this->data['invoice_detail']->booking_id,'','pdf', 'auto');

			return true;
					
	
		  }

	}		
	
}
