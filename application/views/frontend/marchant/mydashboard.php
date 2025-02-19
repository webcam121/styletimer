<style type="text/css">
	.c100{
		float: none !important;
		margin: 0px auto 15px auto !important;
	}
	#frmReshedule .slick-slide{
		height: auto;
	}
	.flot-tooltip {
    padding: 3px 5px;
    background-color: #fff;
    z-index: 100;
    color: #333;
    /*opacity: .80;
    filter: alpha(opacity=85);*/
    font-weight: normal !important;
	}
	.flc-pie-payment-type table{
		margin: 10px auto auto auto;
	}
	.border-radius4{
        margin-top:0px !important;
	}
	.p-4 {
	   padding:.5rem !important;
	}
	.mb-30{
		margin-bottom: 0px !important;
	}
</style>
<?php $this->load->view('frontend/common/header'); ?>
<div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>
 
 <div class="right-side-dashbord w-100 pl-30 pr-30">
        	<div class="mb-60 mt-20 bgwhite box-shadow1 border-radius4">
	        	<div class="row">
					

	        		<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 large-width-fixed">
							<div class="relative border-radius4 p-4 mb-30">
							<!--Bar Chart-->
						   <div id="chartjs-bar-chart" class="card" style="border:none !important;">
						      <div class="card-content">
						      	<div class="d-flex justify-content-between align-items-center mb-4">
						         <h4 class="card-title font-size-18 color333 fontfamily-medium display-ib "><?php echo $this->lang->line('Calendar_Utilization'); ?></h4>
						        
					          </div>

					          <?php
					          $today_totaltime= 0;
					          $total_bookingtime= 0;
					          $et_total= 0;
					          $et_tot= 0;
					          $all_total= 0;
					          $all_tot= 0;
					          	if(!empty($booking_time->todays_total)){
					          		$today_totaltime=$booking_time->todays_total;
					          	}
					          	if(!empty($employee_today)){
									foreach ($employee_today as $val) {
					          		$start = explode(':', $val->starttime);
								    $s_time=(($start[0]*60) + ($start[1]) + ($start[2]/60));

								    $end = explode(':', $val->endtime);
								    $e_time=(($end[0]*60) + ($end[1]) + ($end[2]/60));

								    $et_tot = $e_time-$s_time;		
					          		$et_total = $et_tot+$et_total;
					          		}

					          	}
					          	if($et_total != 0)
					          		$today_per= ($today_totaltime/$et_total)*100;
					          	else
					          		$today_per=0;

					          	if(!empty($booking_time->tot_time)){
					          		$total_bookingtime=$booking_time->tot_time;
					          	}
					          	if(!empty($employee_alldays)){
									foreach ($employee_alldays as $val) {
					          		$start = explode(':', $val->starttime);
								    $s_time=(($start[0]*60) + ($start[1]) + ($start[2]/60));

								    $end = explode(':', $val->endtime);
								    $e_time=(($end[0]*60) + ($end[1]) + ($end[2]/60));

								    $all_tot = $e_time-$s_time;		
					          		$all_total = $all_tot+$all_total;
					          		}

					          	} 
					          	if($all_total!=0)
					          	 	$lastdays_per= ($total_bookingtime/$all_total)*100;
					          	else
					          		$lastdays_per=0;
					          	//echo $lastdays_per." ".$total_bookingtime." ".$all_total;
								?>
						<div class="row">
							<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center">
					          	  <div class="c100 p<?php echo round($today_per) ?> big green">
				                    <span><?php echo round($today_per) ?>%</span>
				                    <div class="slice">
				                        <div class="bar"></div>
				                        <div class="fill"></div>
				                    </div>
				                 </div>
				                 <label class="display-b text-center font-size-18 color333 fontfamily-medium" ><?php echo $this->lang->line('today'); ?></label>
				             </div>
				             <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center">   
				                  <div class="c100 p<?php echo round($lastdays_per) ?> big green">
				                    <span><?php echo round($lastdays_per) ?>%</span>
				                    <div class="slice">
				                        <div class="bar"></div>
				                        <div class="fill"></div>
				                    </div>
				                 </div>
				                 <label class="display-b text-center font-size-18 color333 fontfamily-medium"><?php echo $this->lang->line('next7day'); ?></label>
				             </div>
				         </div>
					  </div>
				   </div>
				</div>

				<div class="relative border-radius4 p-4 mt-30">
							<!--Bar Chart-->
						   <div id="chartjs-bar-chart" class="card" style="border:none !important;">
						      <div class="card-content">
						      	<div class="d-flex justify-content-between align-items-center">
							         <h4 class="card-title font-size-18 color333 fontfamily-medium display-ib"><?php echo $this->lang->line('Upcoming_bookings'); ?></h4>
							         <div class="form-group relative my-new-drop-v display-ib ml-auto ">
						               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
						                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
						                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('in_upcoming_7_day'); ?></button>
						                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" style=" max-height: 400px;  !important">
						                    <li class="radiobox-image">
						                      <input type="radio" id="id_234" name="filter_upcoming" class="filterby_upcoming" value="upcoming_7_day" checked>
						                      <label for="id_234"><?php echo $this->lang->line('in_upcoming_7_day'); ?></label>
						                    </li>
						                    <li class="radiobox-image">
						                      <input type="radio" id="id_235" name="filter_upcoming" class="filterby_upcoming" value="upcoming_30_day">
						                      <label for="id_235"><?php echo $this->lang->line('in_upcoming_30_day'); ?></label>
						                    </li>
						                    
						                  </ul>
						                </div>                
						              </div>
						          </div>
						         <!-- <span class="fontfamily-regular font-size-14 color999 float-right">In Upcoming 7 days</span> -->
						         <div class="caption">
						            <p class="font-size-14">
						            	<span class="fontfamily-medium font-size-14 color333 display-ib"><?php echo $this->lang->line('Confirmed_bookings'); ?> : </span>
						            	<span class="color666 display-ib width50 text-left ml-2" id="confirm_booking_count"><?php echo isset($up_count->confirm)?$up_count->confirm:0; ?></span>
						            </p>
						            <p class="font-size-14">
						            	<span class="fontfamily-medium font-size-14 color333 display-ib"><?php echo $this->lang->line('Cancelled_bookings'); ?> : </span>
						            	<span class="color666 display-ib width50 text-left ml-2" id="cancel_booking_count"><?php echo isset($up_count->cancel)?$up_count->cancel:0; ?></span>
						            </p>
						         </div>
						          <div id="chartContainer" style="width:100%;height:280px;"></div>				           
						      </div>
						   </div>
						</div>

					<div class="relative border-radius4 p-4">
		        			<!--Line Chart-->
						   <div id="chartjs-line-chart " class="card " style="border:none !important;">
						      <div class="card-content">
						         <h4 class="card-title font-size-18 color333 fontfamily-medium display-ib"><?php echo $this->lang->line('Recent_Sales'); ?></h4>
						         <!-- <span class="fontfamily-regular font-size-14 color999 float-right">In last 7 days</span> -->
						         <div class="form-group relative my-new-drop-v display-ib ml-auto float-right">
					               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
					                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
					                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('last_seven_days'); ?></button>
					                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" style=" max-height: 400px;  !important">
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_341" name="filter_sale" class="filterby_days_sale" value="day">
					                      <label for="id_341"><?php echo $this->lang->line('today'); ?></label>
					                    </li>
					                     <li class="radiobox-image">
					                      <input type="radio" id="id_381" name="filter_sale" class="filterby_days_sale" value="last_seven_day" checked>
					                      <label for="id_381"><?php echo $this->lang->line('last_seven_days'); ?></label>
					                    </li>
					                     
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_351" name="filter_sale" class="filterby_days_sale" value="current_week">
					                      <label for="id_351"><?php echo $this->lang->line('current_week'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_361" name="filter_sale" class="filterby_days_sale" value="current_month">
					                      <label for="id_361"><?php echo $this->lang->line('current_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_371" name="filter_sale" class="filterby_days_sale" value="current_year">
					                      <label for="id_371"><?php echo $this->lang->line('current_year'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_0361" name="filter_sale" class="filterby_days_sale" value="last_month">
					                      <label for="id_0361"><?php echo $this->lang->line('last_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_0371" name="filter_sale" class="filterby_days_sale" value="last_year">
					                      <label for="id_0371"><?php echo $this->lang->line('last_year'); ?></label>
					                    </li>
					                   <!--  <li class="radiobox-image">
					                      <input type="radio" id="id_38" name="filter" class="filterby_days" value="date">
					                      <label for="id_38">Search by date</label>
					                    </li> -->
					                  </ul>
					                </div>                
					              </div>
					              <!-- new droup end -->

						         <div class="caption">
						            <p class="font-size-14">
						            	<span class="fontfamily-medium font-size-14 color333 display-ib"><?php echo $this->lang->line('Bookings'); ?> : </span>
						            	<span class="color666 display-ib width50 text-left ml-2" id="salebokking_count"><?php echo isset($sale_count->tot_booking)?$sale_count->tot_booking:0; ?></span>
						            </p>
						            <p class="font-size-14">
						            	<span class="fontfamily-medium font-size-14 color333 display-ib"><?php echo $this->lang->line('Booking_Value'); ?> : </span>
						            	<span class="color666 display-ib width50 text-left ml-2" id="salebokking_value" style="width: 4.56rem !important;"> <?php echo isset($sale_count->tot_sale)?price_formate($sale_count->tot_sale):0; ?> €</span>
						            </p>
						         </div>
						               
						        <div class="sample-chart-wrapper">
						        	<canvas id="chartjs-1"></canvas>
						        </div>
						      </div>
						   </div>
					</div>

						

						<div class="relative border-radius4 p-4 mt-30">
							<!--Bar Chart-->
						   <div id="chartjs-bar-chart" class="card" style="border:none !important;">
						      <div class="card-content ">
						      	<div class="d-flex justify-content-between align-items-center">
						         <h4 class="card-title font-size-18 color333 fontfamily-medium display-ib"><?php echo $this->lang->line('top_5_services'); ?></h4>
						         <!-- <span class="fontfamily-regular font-size-14 color999 float-right">In last 30 days</span> -->
						         <div class="form-group relative my-new-drop-v display-ib ml-auto ">
					               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
					                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
					                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('last_seven_days'); ?></button>
					                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" style=" max-height: 400px;  !important">
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_134" name="filter_top5_service" class="filterby_top5_service" value="day">
					                      <label for="id_134"><?php echo $this->lang->line('today'); ?></label>
					                    </li>
					                     <li class="radiobox-image">
					                      <input type="radio" id="id_138" name="filter_top5_service" class="filterby_top5_service" value="last_seven_day" checked>
					                      <label for="id_138"><?php echo $this->lang->line('last_seven_days'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_135" name="filter_top5_service" class="filterby_top5_service" value="current_week">
					                      <label for="id_135"><?php echo $this->lang->line('current_week'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_136" name="filter_top5_service" class="filterby_top5_service" value="current_month">
					                      <label for="id_136"><?php echo $this->lang->line('current_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_137" name="filter_top5_service" class="filterby_top5_service" value="current_year">
					                      <label for="id_137"><?php echo $this->lang->line('current_year'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_0136" name="filter_top5_service" class="filterby_top5_service" value="last_month">
					                      <label for="id_0136"><?php echo $this->lang->line('last_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_0137" name="filter_top5_service" class="filterby_top5_service" value="last_year">
					                      <label for="id_0137"><?php echo $this->lang->line('last_year'); ?></label>
					                    </li>
					                   <!--  <li class="radiobox-image">
					                      <input type="radio" id="id_38" name="filter" class="filterby_days" value="date">
					                      <label for="id_38">Search by date</label>
					                    </li> -->
					                  </ul>
					                </div>                
					              </div>
					          </div>
					              <!-- new droup end -->
						         
						          <div id="chartContainerserv" style="width:100%;height:300px;"></div>  					           
						      </div>
						   </div>
						</div>

						<div class="relative border-radius4 p-4 mt-30 mb-30">
							<!--Bar Chart-->
						   <div id="chartjs-bar-chart" class="card" style="border:none !important;">
						      <div class="card-content">
						      	<div class="d-flex justify-content-between align-items-center">
						         <h4 class="card-title font-size-18 color333 fontfamily-medium display-ib"><?php echo $this->lang->line('top_5_staff'); ?></h4>
						         <div class="form-group relative my-new-drop-v display-ib ml-auto ">
					               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
					                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
					                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('last_seven_days'); ?></button>
					                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" style=" max-height: 400px;  !important">
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_5134" name="filter_top5_staff" class="filterby_top5_staff" value="day">
					                      <label for="id_5134"><?php echo $this->lang->line('today'); ?></label>
					                    </li>
					                     <li class="radiobox-image">
					                      <input type="radio" id="id_5138" name="filter_top5_staff" class="filterby_top5_staff" value="last_seven_day" checked>
					                      <label for="id_5138"><?php echo $this->lang->line('last_seven_days'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_5135" name="filter_top5_staff" class="filterby_top5_staff" value="current_week">
					                      <label for="id_5135"><?php echo $this->lang->line('current_week'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_5136" name="filter_top5_staff" class="filterby_top5_staff" value="current_month">
					                      <label for="id_5136"><?php echo $this->lang->line('current_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_5131" name="filter_top5_staff" class="filterby_top5_staff" value="current_year">
					                      <label for="id_5131"><?php echo $this->lang->line('current_year'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_5132" name="filter_top5_staff" class="filterby_top5_staff" value="last_month">
					                      <label for="id_5132"><?php echo $this->lang->line('last_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_5137" name="filter_top5_staff" class="filterby_top5_staff" value="last_year">
					                      <label for="id_5137"><?php echo $this->lang->line('last_year'); ?></label>
					                    </li>
					                   <!--  <li class="radiobox-image">
					                      <input type="radio" id="id_38" name="filter" class="filterby_days" value="date">
					                      <label for="id_38">Search by date</label>
					                    </li> -->
					                  </ul>
					                </div>                
					              </div>
					          </div>
						         <!-- <span class="fontfamily-regular font-size-14 color999 float-right">In last 30 days</span> -->
						         
						          <div id="chartContainerstaff" style="width:100%;height:300px;"></div>  				           
						      </div>
						   </div>
						</div>


					<div class="relative border-radius4 p-4 mb-30">
							<!--Bar Chart-->
						   <div id="chartjs-bar-chart" class="card" style="border:none !important;">
						      <div class="card-content">
						      	<div class="d-flex justify-content-between align-items-center mb-4">
						         <h4 class="card-title font-size-18 color333 fontfamily-medium display-ib "><?php echo $this->lang->line('Payment_Type_Ratio'); ?></h4>
						        
						        <div class="form-group relative my-new-drop-v display-ib ml-auto float-right">
					               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
					                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
					                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('last_seven_days'); ?></button>
					                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" style=" max-height: 400px;  !important">
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_over11" name="payment_type" class="filterby_paymenttype" value="">
					                      <label for="id_over11"><?php echo $this->lang->line('Total'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_td11" name="payment_type" class="filterby_paymenttype" value="day">
					                      <label for="id_td11"><?php echo $this->lang->line('today'); ?></label>
					                    </li>
					                     <li class="radiobox-image">
					                      <input type="radio" id="id_ls11" name="payment_type" class="filterby_paymenttype" value="last_seven_day" checked>
					                      <label for="id_ls11"><?php echo $this->lang->line('last_seven_days'); ?></label>
					                    </li>
					                     
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_cw11" name="payment_type" class="filterby_paymenttype" value="current_week">
					                      <label for="id_cw11"><?php echo $this->lang->line('current_week'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_cm11" name="payment_type" class="filterby_paymenttype" value="current_month">
					                      <label for="id_cm11"><?php echo $this->lang->line('current_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_cy11" name="payment_type" class="filterby_paymenttype" value="current_year">
					                      <label for="id_cy11"><?php echo $this->lang->line('current_year'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_lm11" name="payment_type" class="filterby_paymenttype" value="last_month">
					                      <label for="id_lm11"><?php echo $this->lang->line('last_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_ly11" name="payment_type" class="filterby_paymenttype" value="last_year">
					                      <label for="id_ly11"><?php echo $this->lang->line('last_year'); ?></label>
					                    </li>
					                   
					                  </ul>
					                </div>                
					              </div>
					          </div>

					         
						<div class="row">
							<div class="col-12 col-sm-12 text-center">
					          	  <div id="pie-chart-payment-type" class="flot-chart-pie" style="width: 100%; height: 300px;"></div>
                                    <div class="flc-pie-payment-type hidden-xs"></div>
				                
				             </div>
				           
				         </div>
					  </div>
				   </div>
				</div>



	        		</div>
	        		<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 large-width-fixed">
	        			<div class="relative border-radius4 p-4" style="padding-right:4px !important;">
	        				<p class="font-size-18 color333 fontfamily-medium">
							   <?php echo $this->lang->line('Booking_Activity'); ?></p>
	        				<div class="relative scroll300 custom_scroll" style="max-height:850px;min-height:220px;overflow: auto;">
	        					<?php if(!empty($booking)) {
									//echo '<pre>'; print_r($booking); die;
	        					foreach($booking as $book){ 
	        						if($book->booking_type !='guest')
	        							$name=$book->first_name.' '.$book->last_name;
	        						else
	        							$name=$book->fullname;

	        						if($book->booking_type =='user')
	        							$link='cursor-p editCust';
	        						else
	        							$link='';
	        						 //$time = new DateTime($book->booking_time);
	        						// $time = new DateTime($book->updated_on);
					                // $date = $time->format('d M y');
					                 //$time = $time->format('H:i');
	        					$ser_nm='';
	        					$book_details=getselect('st_booking_detail','id,service_id,service_name',array('booking_id'=>$book->id));
			                      if(!empty($book_details)){ 
									  $countser=count($book_details);
				                      	$ijk=1;
				                      	foreach($book_details as $serv){ 
				                    	 $sub_name=get_subservicename($serv->service_id);  
				                    	if($countser==$ijk){
											if($sub_name == $serv->service_name)
				                              $ser_nm.=$serv->service_name;
				                        else
				                              $ser_nm.=$sub_name.' - '.$serv->service_name;
											
											}
										else{	
				                    	if($sub_name == $serv->service_name)
				                              $ser_nm.=$serv->service_name.', ';
				                        else
				                              $ser_nm.=$sub_name.' - '.$serv->service_name.', ';
										  }
										  $ijk++;
				                      } 
			                      	}
					            if(((empty($book->rate)) || ($book->status=='completed' && $book->rate !='' && empty($book->anonymous))) && $book->profile_pic !=''){
                              		$img=getimge_url('assets/uploads/users/'.$book->user_id.'/','icon_'.$book->profile_pic,'webp');
                              		$imgp=getimge_url('assets/uploads/users/'.$book->user_id.'/','icon_'.$book->profile_pic,'png');
								  }
                          		else{
                              		$imgp=base_url('assets/frontend/images/user-icon-gret.svg');
                              		$img=base_url('assets/frontend/images/user-icon-gret.svg');
								 }
	        						?>
		        				<div class="clear relative d-flex pt-3 pb-2">
		        				  <div>
										
										  <picture>
												 <img class="round-new-v40 display-ib mr-3" src="<?php echo $imgp; ?>" type="image/png">
												
											</picture>
				
					
										<h3 class="mt-10 mb-0 mr-3 text-center color333 font-size-20 fontfamily-semibold"><?php echo date('d',strtotime($book->updated_on)); ?></h3>
										<?php
										$germ_month=['Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mär','Apr'=>'Apr','May'=>'Mai','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Okt','Nov'=>'Nov','Dec'=>'Dez'];
										$month=date('M',strtotime($book->updated_on));
										
										 ?>
										
										<p class="mb-0 mr-3 text-center color999 font-size-15 fontfamily-regular"><?php echo $germ_month[$month]; ?></p>
										

		        				  </div>
	                              <div class="display-ib">
	                             
	                              <span class="font-size-15 fontfamily-regular">
									  <?php if($book->status=='confirmed' && $book->updated_on != $book->created_on){ 
										  echo $this->lang->line('booking_rescheduled').','; 
										  }elseif($book->status=='completed' && $book->rate !=''){ 
											  echo 'Neue Bewertung erhalten <br/>'; 
											}else{ echo $this->lang->line('booking_'.str_replace(' ','_',$book->status)).','; 
												 }
												 
										if(empty($book->rate) || ($book->status=='completed' && $book->rate !='' && empty($book->anonymous))){ ?> 
											ID : <a href="#" class="colorcyan a_hover_cyan text-underline booking_row" id="<?php echo url_encode($book->id); ?>"> <?php echo $book->book_id; ?></a> 
											<?php } ?>
											
											</span>
									<?php if(empty($book->rate) || ($book->status=='completed' && $book->rate !='' && empty($book->anonymous))){ ?> 		
	                               <p class="color333 font-size-15 fontfamily-semibold mb-0 <?php echo $link; ?>" data-id="<?php echo url_encode($book->user_id); ?>"><?php echo $name;
	                                
	                                if($book->status=='completed' && empty($book->rate)){ ?> 	         
										                      
	                                 <img src="<?php echo base_url('assets/frontend/images/completed-booking-icon.svg'); ?>" width="20" height="18" class="ml-0 mt--2">
	                               
	                               <?php }elseif($book->status=='confirmed' && $book->updated_on != $book->created_on){ ?>
									   <img src="<?php echo base_url('assets/frontend/images/reshedule-icon.svg'); ?>" width="22" height="18" class="ml-0 mt--2">
									   
									<?php }elseif($book->status=='cancelled'){ ?>
										 <img src="<?php echo base_url('assets/frontend/images/booking-cancel-icon.svg'); ?>" width="20" height="18" class="ml-0 mt--2">
								    <?php }elseif($book->status=='confirmed'){ ?>
										<img src="<?php echo base_url('assets/frontend/images/checked.svg'); ?>" width="16" height="16" class="ml-1 mt--2">
										
										<?php }?>
	                               </p>
	                               <?php }else{  ?><p class="color333 font-size-14 fontfamily-semibold mb-0"> Anonym</p> <?php } ?>
	                              
<!--
	                              <p class="color999 fontfamily-regular font-size-12 mb-0"><?php //echo $date.' at '.$time; ?></p>
-->
								<p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0"><?php echo $this->lang->line('Employee'); ?> : </span> <?php echo $book->emp_name; ?></p>
								<p class="color999 fontfamily-regular font-size-12 mb-0"><?php 
	                              	$service_nm=rtrim($ser_nm, ',');
	                             /* if(strlen($service_nm) > 25)
	                              	  echo substr($service_nm, 0, 25).'..';
	                              	else
	                              		echo $service_nm;*/
	                              		echo $service_nm;

	                              //get_servicename($book->id); ?> </p>

								<?php if($book->status=='completed' && $book->rate !=''){ ?> 
								 <p class="color999 fontfamily-regular font-size-12 mb-0 display-ib rating-p"><span class="color999 fontfamily-regular font-size-12 mb-0"><?php echo $this->lang->line('Rating'); ?> : </span></p>
								 	 <a href="<?php echo base_url('merchant/rating_review') ?>" class="rating-box"><div class="display-ib mt-1">
								 <?php for ($i=1; $i < 6; $i++){ 
                           			 if($i <= $book->rate)  
                              		echo"<i class='fas fa-star colororange mr-2 font-size-16'></i>";
                           			 else
                             		 echo "<i class='fas fa-star colore99999940 mr-2 font-size-16'></i>";

                           			 } ?> </div></a>

								 
	                              <p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0"><?php //echo $this->lang->line('Reviews'); ?>  </span>
	                              	<?php //echo nl2br($book->review); ?>
	                               <?php if(strlen($book->review) < 50){
	                              	echo nl2br($book->review); 
	                              }else{
									echo nl2br(substr($book->review, 0, 50)).'...'; }
									?> </p>
									
	                               <p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0"><?php echo $this->lang->line('Rating').' '.$this->lang->line('Received'); ?> : </span> <br><?php

										$germ_month=['Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mär','Apr'=>'Apr','May'=>'Mai','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Okt','Nov'=>'Nov','Dec'=>'Dez'];
										$rev_month=date('M',strtotime($book->rev_date));
										$mon=$germ_month[$rev_month];
										
										$date=date('d. M. Y - H:i',strtotime($book->rev_date));
										$ger_date= str_replace($rev_month,$mon,$date);
										echo $ger_date; 
										 
								     ?> 
								   Uhr</p>

								<?php } else{ ?>
	                              <p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0">Datum : </span> <?php echo date('d.m.Y - H:i',strtotime($book->booking_time)); ?> Uhr</p>
	                              <p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0"> Gebucht am : </span> <?php echo date('d.m.Y - H:i',strtotime($book->created_on)); ?> Uhr</p>
	                              <?php } ?>
 
	                              </div>
		        				</div>
		        				<?php } 
		        					}  
		        					else{ ?>
		        					<div class="clear relative d-flex pt-3 pb-2">
		        						<p>Keine Buchungsaktivitäten</p>
		        					</div>
		        				<?php } ?>
		        				
		        				
		        			</div>
	        			</div>
	        			
	        			<div class="relative border-radius4 p-4" style="padding-right:4px !important;">
	        				<p class="font-size-18 color333 fontfamily-medium"><?php echo $this->lang->line('Upcoming_bookings'); ?></p>
	        				<div class="relative scroll300 custom_scroll" style="max-height:707px;min-height:220px;overflow: auto;">
	        					<?php if(!empty($upcomingbooking)) {
	        					foreach($upcomingbooking as $book){ 
	        						if($book->booking_type !='guest')
	        							$name=$book->first_name.' '.$book->last_name;
	        						else
	        							$name=$book->fullname;

	        						if($book->booking_type =='user')
	        							$link='cursor-p editCust';
	        						else
	        							$link='';
	        						 //$time = new DateTime($book->booking_time);
	        						
	        					$ser_nm='';
	        					$book_details=getselect('st_booking_detail','id,service_id,service_name',array('booking_id'=>$book->id));
			                      if(!empty($book_details)){ 
				                      $countser=count($book_details);
				                      	$ijk=1;
				                      	foreach($book_details as $serv){ 
				                    	 $sub_name=get_subservicename($serv->service_id);  
				                    	if($countser==$ijk){
											if($sub_name == $serv->service_name)
				                              $ser_nm.=$serv->service_name;
				                        else
				                              $ser_nm.=$sub_name.' - '.$serv->service_name;
											
											}
										else{	
				                    	if($sub_name == $serv->service_name)
				                              $ser_nm.=$serv->service_name.', ';
				                        else
				                              $ser_nm.=$sub_name.' - '.$serv->service_name.', ';
										  }
										  $ijk++;
				                      } 
			                      	}
					            if($book->profile_pic !='')
                              		$img=base_url('assets/uploads/users/').$book->user_id.'/icon_'.$book->profile_pic;
                          		else
                              		$img=base_url('assets/frontend/images/user-icon-gret.svg');
	        						?>
		        				<div class="clear relative d-flex pt-3 pb-2">
		        				    <div>
										<img class="round-new-v40 display-ib mr-3" src="<?php echo $img; ?>">
										<h3 class="mt-10 mb-0 mr-3 text-center color333 font-size-20 fontfamily-semibold"><?php echo date('d',strtotime($book->booking_time)); ?></h3>
										
										<?php
										$germ_month2=['Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mär','Apr'=>'Apr','May'=>'Mai','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Okt','Nov'=>'Nov','Dec'=>'Dez'];
										$month2=date('M',strtotime($book->booking_time));
										
										 ?>
																													
										<p class="mb-0 mr-3 text-center color999 font-size-14 fontfamily-regular"><?php echo $germ_month2[$month2]; ?></p>
		        				  </div>
	                              <div class="display-ib">
	                              <p class="color333 font-size-15 fontfamily-semibold mb-0 <?php echo $link; ?>" data-id="<?php echo url_encode($book->user_id); ?>"><?php echo $name; 
	                                if($book->status=='completed'){ ?> 	         
										                      
	                                 <img src="<?php echo base_url('assets/frontend/images/completed-booking-icon.svg'); ?>" width="20" height="18" class="ml-0 mt--2">
	                               
	                               <?php }elseif($book->status=='confirmed' && $book->updated_on != $book->created_on){ ?>
									   <img src="<?php echo base_url('assets/frontend/images/reshedule-icon.svg'); ?>" width="22" height="18" class="ml-0 mt--2">
									   
									<?php }elseif($book->status=='cancelled'){ ?>
										 <img src="<?php echo base_url('assets/frontend/images/booking-cancel-icon.svg'); ?>" width="20" height="18" class="ml-0 mt--2">
								    <?php }elseif($book->status=='confirmed'){ ?>
										<img src="<?php echo base_url('assets/frontend/images/checked.svg'); ?>" width="16" height="16" class="ml-1 mt--2">
										<?php } ?>
										</p>
	                              <span class="font-size-15 fontfamily-regular"><?php if($book->status=='confirmed' && $book->updated_on != $book->created_on){ $pstatus = 'booking_rescheduled'; } else $pstatus ='booking_'.str_replace(' ','_',$book->status); echo $this->lang->line($pstatus); ?>, ID : <a href="#" class="colorcyan a_hover_cyan text-underline booking_row" id="<?php echo url_encode($book->id); ?>"> <?php echo $book->book_id; ?></a></span><br/>
	                              <p class="color999 fontfamily-regular font-size-12 mb-0"><?php
	                              	$service_nm=rtrim($ser_nm, ',');
	                              	/*if(strlen($service_nm) > 25)
	                              	  	echo substr($service_nm, 0, 25).'..';
	                              	else
	                              		echo $service_nm;*/
	                              		echo $service_nm;
	                               //get_servicename($book->id); ?> </p>
	                             
	                              <p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0">Datum : </span> <?php echo date('d.m.Y - H:i',strtotime($book->booking_time)); ?> Uhr</p>
	                              <p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0">Gebucht am : </span> <?php echo date('d.m.Y - H:i',strtotime($book->created_on)); ?> Uhr</p>
	                              </div>
		        				</div>
		        				<?php } 
		        					}  
		        					else{ ?>
		        					<div class="clear relative d-flex pt-3 pb-2">
		        						<p>Keine Buchungen in den kommenden 7 Tagen</p>
		        					</div>
		        				<?php } ?>
		        				
		        				
		        			</div>
	        			</div>       			
	        			
	        		</div>
	        	</div>
	        </div>
        	


          <!-- dashboard right side end -->       
        </div>
    </div>

<?php $this->load->view('frontend/common/footer_script');  ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/frontend/css/circle.css">
<script src="<?php echo base_url('assets/frontend/'); ?>js/chart.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/frontend/'); ?>js/canvasjs.min.js"></script>

<script src="<?php echo base_url('assets/backend/vendors/bower_components/flot/jquery.flot.js'); ?>"></script>
<!-- <script src="<?php echo base_url('assets/backend/vendors/bower_components/flot/jquery.flot.resize.js'); ?>"></script> -->
<script src="<?php echo base_url('assets/backend/vendors/bower_components/flot/jquery.flot.pie.js'); ?>"></script>
<script src="<?php echo base_url('assets/backend/vendors/bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js') ?>"></script>

<?php $pinstatus = get_pin_status($this->session->userdata('st_userid')); 
//echo current_url().'=='.$_SERVER['HTTP_REFERER'];
    if($pinstatus->pinstatus=='on'){
        $this->load->view('frontend/marchant/profile/ask_pin_popup');
      }  
  ?>

<script type="text/javascript">
$(document).ready(function(){
	getupcomingbookingList();
	getupcomingserviceList();
	getupcomingstaffList();
	getsaleboookingline();
	getpaymenttypeRatio();

 function getsaleboookingline(){
  surl=base_url+"merchant/getbookingrecentsale";
  var dayfilter=$("input[name='filter_sale']:checked").val();
  var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
	 $.post(surl,{filter:dayfilter},function( data1 ) {
	 	var data1 = jQuery.parseJSON( data1 );
	
  	 new Chart(document.getElementById("chartjs-1"),{"type":"line","data":{"labels":data1.days,
            "datasets":[{"label":"<?php echo $this->lang->line('SALES'); ?>","data":data1.sales,"fill":false,"borderColor":"rgb(251, 153, 71)","lineTension":0},{"label":"<?php echo $this->lang->line('Bookings'); ?>","data":data1.booking,"fill":false,"borderColor":"rgb(1, 214, 227)","lineTension":0}]},
            "options":{tooltips: {
								enabled: true,
								mode: 'single',
								callbacks: {
									label: function(tooltipItems, data,i=0) { 
					//console.log(tooltipItems);
					//console.log(data.datasets[i].label);
					if(data.datasets[tooltipItems.datasetIndex].label=='<?php echo $this->lang->line("SALES"); ?>'){
					   return '<?php echo $this->lang->line("SALES"); ?> :'+tooltipItems.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' €';
				    }else{
						 return '<?php echo $this->lang->line("Bookings"); ?> :'+tooltipItems.yLabel;
						} 
						i++;
				}
			}
		},
	  scales: {
		yAxes: [{
		  ticks: {
			beginAtZero: true,
			callback: function(value, index, values) {
			  
				if(parseInt(value) >= 1000){
					return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' €';
				  } else {
					return value+' €';
				  }
			  
			}
		  }
		}]
		
	  }
	}
});
  	
  	 if(data1.sale_count){
  	    $("#salebokking_value").text(data1.sale_count.tot_sale+' €');
  	    $("#salebokking_count").text(data1.sale_count.tot_booking);
      }
      else{
      	$("#salebokking_value").text('0 €');
  	    $("#salebokking_count").text('0');
      }

	});

 }

function getupcomingbookingList(){
	url=base_url+"merchant/getbookinglistforchart";
	
	 var dayfilter=$("input[name='filter_upcoming']:checked").val();
	 var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
	 $.post(url,{filter:dayfilter},function( data1 ) {
	 	var data1 = jQuery.parseJSON( data1 );
	 	
	 	/*var finalsdata=[];
       for(var i = 0; i < data1.length; i++)
        {
        	finalsdata.push({ y: data1[i].y,label:data1[i].label,color: "#00b3bf" });
        }*/
        //console.log(finalsdata);
		var chart = new CanvasJS.Chart("chartContainer", {
			dataPointWidth:28,
			axisY:{
				labelFontSize: 12,
				labelFontWeight: "normal",
				labelFontColor: "#333",
				labelFontFamily: "arial",
			},
			axisX:{
				labelFontSize: 12,
				labelFontWeight: "normal",
				labelFontColor: "#333",
				labelFontFamily: "arial",
			},
			data: [{        
				type: "column",
				dataPoints: data1.graphdata

			}]
		 });
	chart.render();
	if(data1.countdata.confirm !=undefined || data1.countdata.confirm !='' ||data1.countdata.confirm !=null)
	  {
	   $('#confirm_booking_count').text(data1.countdata.confirm);
	  }
	else{
		$('#confirm_booking_count').text('0');
		}  
	if(data1.countdata.cancel !=undefined || data1.countdata.cancel !='' ||data1.countdata.cancel !=null)
	  {
	   $('#cancel_booking_count').text(data1.countdata.cancel);
	  }
	else{
		$('#cancel_booking_count').text('0');
		}  	
  });
}

function getupcomingserviceList(){
	url1=base_url+"merchant/gettopfiveservice";
	var dayfilter=$("input[name='filter_top5_service']:checked").val();
	var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
	 $.post(url1,{filter:dayfilter},function( data ) {
	 	//var obj = jQuery.parseJSON( data );
	 	var tabData = JSON.parse(data);
		 if(tabData==''){
			$('#chartContainerserv').html('<div style="text-align:center;"><img style="margin-top:0" src="https://dev.styletimer.de/assets/frontend/images/no_listing.png"></div><div style="margin-top:5px;text-align:center;">Es sind noch keine Daten vorhanden</div>');
			$("#chartContainerserv").css("height","150");
			return;
		};
		
	 	var finals = [];
	 	var s_nm='';
        for(var i = 0; i < tabData.length; i++)
        {
        	//alert(tabData[i].count);
            //var firstdate = tabData[i].nextdate;
            //var res = firstdate.split('-'); 
            //finals.push({ 'x': new Date(res[2],res[1],res[0]), 'y': tabData[i].count });
            //finals.push({ y: 80,  label: "Steps Haicut",color: "#FF9944" });
            if(tabData[i].service_name!='' && tabData[i].subcategory != tabData[i].service_name)
            	s_nm=tabData[i].subcategory+' - '+tabData[i].service_name;
            else
            	s_nm=tabData[i].subcategory;

            finals.push({ y: tabData[i].tprice,  label: s_nm,color: "#FF9944" });
            
        }
		var chart = new CanvasJS.Chart("chartContainerserv", {
			dataPointWidth:28,
			 axisY:{
				valueFormatString:"#,##0 €",
				labelFontSize: 12,
				labelFontWeight: "normal",
				labelFontColor: "#333",
				labelFontFamily: "arial",
			},
			axisX:{
				labelFontSize: 12,
				labelFontWeight: "normal",
				labelFontColor: "#333",
				labelFontFamily: "arial",
				labelAngle: 0,
			},
			data: [{        
				type: "column",
				yValueFormatString: "#,### €",
				dataPoints: finals

			}]
		 });
		chart.render();
		
	});
	
}

function getupcomingstaffList(){
	url2=base_url+"merchant/gettopfivestaff";
	
	 var dayfilter=$("input[name='filter_top5_staff']:checked").val();
	 var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
	 $.post(url2,{filter:dayfilter},function( data ) {
	 	//var obj = jQuery.parseJSON( data );
	 	var tabData = JSON.parse(data);
		 if(tabData==''){
			$('#chartContainerstaff').html('<div style="text-align:center;"><img style="margin-top:0" src="https://dev.styletimer.de/assets/frontend/images/no_listing.png"></div><div style="margin-top:5px;text-align:center;">Es sind noch keine Daten vorhanden</div>');
			$("#chartContainerstaff").css("height","150");
			return;
		};
		 
	 	var finals = [];
        for(var i = 0; i < tabData.length; i++)
        {
        	finals.push({ y: tabData[i].tprice,  label: tabData[i].first_name+' '+tabData[i].last_name,color: "#5380AC" });
            
        }
		var chart = new CanvasJS.Chart("chartContainerstaff", {
			dataPointWidth:28,
			axisY:{
				valueFormatString:"#,##0 €",
				labelFontSize: 12,
				labelFontWeight: "normal",
				labelFontColor: "#333",
				labelFontFamily: "arial",
			},
			axisX:{
				labelFontSize: 12,
				labelFontWeight: "normal",
				labelFontColor: "#333",
				labelFontFamily: "arial",
			},
			data: [{        
				type: "column",
				yValueFormatString: "#,### €",
				dataPoints: finals

			}]
		 });
		 
		chart.render();
	    
	});
	
}


function getpaymenttypeRatio(){
	url=base_url+"merchant/getpaymenttype_ratio";
	
	 var dayfilter=$("input[name='payment_type']:checked").val();
	 var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
	 $.post(url,{filter:dayfilter},function( data1 ) {
	 	
	 	var data1 = jQuery.parseJSON( data1 );
	 	var pieData3 = [
       {data: data1.cash_p, color: '#009688', label: data1.cash+' <?php echo $this->lang->line("Cash"); ?>'},
       {data: data1.card_p, color: '#CCCCCC', label: data1.card+' <?php echo $this->lang->line("Card1"); ?>'},
       {data: data1.other_p, color: '#5481B0', label: data1.other+' <?php echo $this->lang->line("Other"); ?>'},
       {data: data1.vouch_p, color: '#807BC6', label: data1.voucher+' <?php echo $this->lang->line("Voucher"); ?>'}
    ];

	// console.log('PIE='+$('#pie-chart-payment-type').val());
	// console.log('Next1='+JSON.parse($('#pie-chart-payment-type')[0]));
	//console.log('Next1='+JSON.parse($('#pie-chart-payment-type').val()));
	//console.log('Next1='+JSON.stringify($('#pie-chart-payment-type')[0]));
	// if(JSON.stringify($('#pie-chart-payment-type')[0])=='{}'){
		//jQuery.isEmptyObject(
	// if(jQuery.isEmptyObject($('#pie-chart-payment-type')[0])){		
	// 	   $('#pie-chart-payment-type').html('<div ><img style="margin-top:0" src="https://dev.styletimer.de/assets/frontend/images/no_listing.png"></div><div style="margin-top:5px;">Es sind noch keine Daten vorhanden</div>');
	// 		$("#pie-chart-payment-type").css("height","150");
	// 		return;
	// 	};
		
if($('#pie-chart-payment-type')[0]){
    $.plot('#pie-chart-payment-type', pieData3, {
            series: {
                pie: {
                    show: true,
                    stroke: { 
                        width: 2,
                    },
                },
            },
            legend: {
                container: '.flc-pie-payment-type',
                backgroundOpacity: 0.5,
                noColumns: 0,
                backgroundColor: "white",
                lineWidth: 0
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
            	content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false,
                cssClass: 'flot-tooltip'
            }
            
        });
    }else{
		$('#pie-chart-payment-type').html('<div ><img style="margin-top:0" src="https://dev.styletimer.de/assets/frontend/images/no_listing.png"></div><div style="margin-top:5px;">Es sind noch keine Daten vorhanden</div>');
			$("#pie-chart-payment-type").css("height","150");
			return;
	}	
  });
}



 $(document).on('change','.filterby_days_sale',function(){
	getsaleboookingline();
	
	});
	
 $(document).on('change','.filterby_upcoming',function(){
	getupcomingbookingList();
	
	});

 $(document).on('change','.filterby_top5_staff',function(){
	getupcomingstaffList();
	
	});	
 $(document).on('change','.filterby_top5_service',function(){
	getupcomingserviceList();
	
	});		
	
	$(document).on('change','.filterby_paymenttype',function(){
		getpaymenttypeRatio();
	});

 


});
</script>
