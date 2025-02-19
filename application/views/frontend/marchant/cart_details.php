<?php $this->load->view('frontend/common/header');   

$optArr = array('5'=>'5min','10'=>'10min','15'=>'15min','20'=>'20min','25'=>'25min','30'=>'30min','35'=>'35min','40'=>'40min','45'=>'45min','50'=>'50min','55'=>'55min','60'=>'1h','65'=>'1h 5min','70'=>'1h 10min','75'=>'1h 15min','80'=>'1h 20min','85'=>'1h 25min','90'=>'1h 30min','95'=>'1h 35min','100'=>'1h 40min','105'=>'1h 45min','110'=>'1h 50min','115'=>'1h 55min','120'=>'2h','135'=>'2h 15min','150'=>'2h 30min','165'=>'2h 45min','180'=>'3h','195'=>'3h 15min','210'=>'3h 30min','225'=>'3h 45min','240'=>'4h','255'=>'4h 15min','270'=>'4h 30min','285'=>'4h 45min','300'=>'5h','315'=>'5h 15min','330'=>'5h 30min','345'=>'5h 45min','360'=>'6h'); 

   ?>
   <style>
  .header-container{
		width:100%;
	}	 
	@media (min-width: 1400px) and (max-width: 1919px) {
		.header-container{
		   margin: 0 auto;
		   width: 100%;
		   padding: 0 20px;
		}
	}
	@media (min-width: 1920px) {
		  .header-container{
			margin: 0 auto;
			width: 100%;
			padding: 0 20px;
		}
	}
	@media (min-width: 1921px) and (max-width: 4400px) {
		 .header-container{
			margin: 0 auto;
			width: 100%;
			padding: 0 20px;
		}
	}
	@media (min-width:4401px){
		.header-container{
			margin: 0 auto;
			width: 100%;
			padding: 0 20px;
		}
	}
</style>
        <!-- dashboard left side end -->
         <div class="d-flex pt-84">
<?php $this->load->view('frontend/common/sidebar');
//echo '<pre>'; print_r($_SESSION); die;
//print_r($userdetail); die;
 ?>
 <style type="text/css">
      .mss_sl_btn_dm.scroll300.custom_scroll{
        box-shadow: 0rem 3px 25px rgba(215,232,233,.78);
        border: none;
      }
      .mss_sl_btn_dm.scroll300.custom_scroll .checkbox-image.height72v label{
        padding-left: 0rem;
        padding: 15px 1rem;
        width: 100%;
      }
      .mss_sl_btn_dm.scroll300.custom_scroll .checkbox-image.height72v:hover,
      .mss_sl_btn_dm.scroll300.custom_scroll .checkbox-image.height72v:focus{
        background: #DBF4F6;
      }
      .swal2-title{
	font-size:1.125em !important;
	}
.card-detail-new .border-b.d-flex.py-3.pl-3.bookingSelect2 {
    vertical-align: bottom;
    align-items: flex-end;
    padding-left:0rem !important;
    padding-right:16px !important;
}
.multi_sigle_select.arrow-none .dropdown-toggle::before,
.multi_sigle_select.arrow-none .dropdown-toggle::after {
  display:none;
}
.multi_sigle_select.arrow-none button.dropdown-toggle{
  padding-right:20px !important;
}
/* .card-detail-new.v_overflow_scroll .overflow_elips {
    width: auto;
} */
    </style>
    
        <div class="right-side-dashbord w-100 pl-30 pr-15">
			<form method="post" id="merchantBookingForm">
          <div class="relative mt-20 mb-60 bgwhite box-shadow1">
          <h3 class="font-size-20 fontfamily-semibold color333 mb-3 pl-4 pt-3"><?php echo $this->lang->line('Add_New_Booking1'); ?></h3>
          <div class="absolute right top mt-10 mr-15"><a href="<?php echo base_url('merchant/dashboard'); ?>" class="font-size-30 color333 a_hover_333">
          <picture class="" style="width: 22px; height: 22px;">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="" style="width: 22px; height: 22px;">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="" style="width: 22px; height: 22px;">
                <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="" style="width: 22px; height: 22px;">
              </picture></a></div>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
              <div class="bgwhite border-radius4 box-shadow1 pt-20 px-4 mb-30 h-100">
                <div class="row">
                  <div class="col-12 col-sm-5 col-md-5 col-lg-6 col-xl-5">
                    <h6 class="font-size-16 color333 fontfamily-semibold mb-25"><?php echo $this->lang->line('Select_Date_and_Time'); ?></h6>
                  </div>
                  <div class="col-12 col-sm-7 col-md-7 col-lg-6 col-xl-7 text-right pl-0">
					  
					  <?php $repeatMsgClass="";
						      $repetMessage="";
						  if(!empty($_SESSION['book_session']['repeatval']) && $_SESSION['book_session']['repeatval']!='yes') $repeatMsgClass="display-n";
						  else $repetMessage=$_SESSION['book_session']['repeatText'];
						  
						   ?>

                    <a href="javascript:void(0)" class="font-size-14 colororange fontfamily-medium a_hover_orange mb-20 display-ib <?php echo $repeatMsgClass; ?>"> <?php echo $repetMessage; ?>
                     </a>
                     <!-- <img style="cursor:pointer;" id="crossRepeat" src="<?php //echo base_url("assets/frontend/images/crose_grediant_orange_icon.svg"); ?>" class="widthv15 ml-1"> -->
                     <a class="btn" style="width: auto; height: 28px;line-height: 28px; font-size: 12px;" href="<?php echo base_url('booking/new_booking'); ?>"><?php echo $this->lang->line('Back'); ?></a> 

                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-5 pr-0">
                    <div class="form-froup vmb-40 v_date_time_picker relative">                        
                        <input type="text" name="date" value="<?php if(!empty($_SESSION['book_session']['date'])) echo $_SESSION['book_session']['date']; ?>" placeholder="<?php echo $this->lang->line('Select_Date'); ?>" class="height56v form-control" style="background-color:#ffffff;" readonly>
                        <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue">
                    </div>
                  </div>
                  <div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-5 pr-0">
                    <div class="form-froup vmb-40 v_date_time_picker relative">                        
                        <input type="text" name="time" value="<?php if(!empty($_SESSION['book_session']['time'])) echo $_SESSION['book_session']['time']; ?>"  placeholder="<?php echo $this->lang->line('Select_Date'); ?>" class="height56v form-control" style="background-color:#ffffff;" readonly>
                        <img src="<?php echo base_url('assets/frontend/images/blue-clock.svg'); ?>" class="v_time_claender_icon_blue">
                    </div>
                  </div>
                </div>
                <h6 class="font-size-16 color333 fontfamily-semibold mb-25"><?php echo $this->lang->line('Selected_Services'); ?></h6>
                

                  <div class="card-detail-new v_overflow_scroll custom_scroll">
					  <?php if(!empty($booking_detail)){
						  $totalAmpunt=0;
						   $totaldurationTim=0;
						  $discoutPrice=0;
						  
						  $abcheck ="";
						  
						  foreach($booking_detail as $ser){ 
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
                  if($ser->stype==1)
                    $ti = $ser->duration;
                  else
                    $ti = $ser->duration+$ser->buffer_time;
							  	
					          $totaldurationTim = $totaldurationTim+$ti;
					            
						$time=date('H:i:s');
						
						
						if($ser->price_start_option=='ab')
						  {
							if(empty($abcheck))
						    $abcheck = $ser->price_start_option;
						  }
						
						if(!empty($_SESSION['book_session']['time'])) $time= $_SESSION['book_session']['time'];	  
							  
					 $discntPrice='';
					 //echo $time.'-'.$ser->starttime;
						if(!empty($ser->discount_price) && !empty($ser->starttime) && $time.':00'>=$ser->starttime && $time.':00'<=$ser->endtime)
						   { 
						      //$discount=get_discount_percent($ser->price,$ser->discount_price);
						      $discount=$ser->price-$ser->discount_price;
						     
						    if(!empty($discount)){
							      $discntPrice='<p class="colorcyan fontfamily-regular font-size-14">enthält '.price_formate($discount).' € '.$this->lang->line('Discount1').'</p>'; 
							   } 
						   }

						if(!empty($ser->discount_price) && !empty($ser->starttime) && $time.':00'>=$ser->starttime && $time.':00'<=$ser->endtime)
						   { 
						       $price=$ser->discount_price; 
						    
						       $disPrice=$ser->price-$ser->discount_price;
						    
						       $discoutPrice=$discoutPrice+$disPrice;
						  }
						else   $price= $ser->price;
						
						$totalAmpunt = $totalAmpunt+$price;
						  ?>
                    <div class="border-b d-flex py-3 pl-3 bookingSelect2" style="align-items: flex-start;"> 
						        <input type="hidden" name="serviceids[]" value="<?php echo $ser->id; ?>">
				       <?php if($ser->stype!=1){ ?>
                        <div class="d-flex standard-duration-full">
                          <div class="relative mr-1" style="min-width: 118px;">
                          <label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Standard_Duration'); ?></label>
                            <div class="form-group mb-0">
                              <div class="btn-group multi_sigle_select">
                                <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idopt01d<?php echo $ser->id; ?>" style="" aria-expanded="false"></button>
                                <ul class="dropdown-menu mss_sl_btn_dm noclose scroll-effect" x-placement="bottom-start" style="overflow-x: auto; height: 320px">
                               	<?php foreach($optArr as $optk=>$optv){ ?>										 
                                  <li class="radiobox-image">
                                    <input type="radio" id="idopt01<?php echo $optk.$ser->id; ?>" class="timeDropDown" data-c="idopt01d<?php echo $ser->id; ?>" data-text="<?php echo $optv; ?>" name="duration_<?php echo $ser->id; ?>" value="<?php echo $optk; ?>" <?php if(!empty($ser->duration) && $ser->duration==$optk || empty($ser->duration) && 60==$optk) echo 'checked'; ?>><label for="idopt01<?php echo $optk.$ser->id; ?>"><?php echo $optv; ?></label>
                                  </li>   
                                <?php } ?>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                     <?php } else{ ?>
						 
                        <div class="d-flex">
                          <div class="relative mr-1">
                            <label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('working_time'); ?></label>
                            <div class="form-group mb-0">
                              <div class="btn-group multi_sigle_select">
                                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idopt03<?php echo $ser->id; ?>" style="" aria-expanded="false"><?php if(($ser->setuptime/60)>=1) echo (round($ser->setuptime/60)).'h '.($ser->setuptime%60).'min'; else  echo $ser->setuptime.'min';  ?></button>
                                <ul class="dropdown-menu mss_sl_btn_dm noclose scroll-effect height240" x-placement="bottom-start">                                                       
                                    <?php foreach($optArr as $optk=>$optv){ ?>
                                    <li class="radiobox-image">
                                      <input type="radio" id="idoptcom03<?php echo $optk.$ser->id; ?>" class="timeDropDown" data-c="idopt03<?php echo $ser->id; ?>" data-text="<?php echo $optv; ?>" name="duration_setup_<?php echo $ser->id; ?>" value="<?php echo $optk; ?>" <?php if(!empty($ser->setuptime) && $ser->setuptime==$optk || empty($ser->setuptime) && 60==$optk) echo 'checked'; ?>><label for="idoptcom03<?php echo $optk.$ser->id; ?>"><?php echo $optv; ?></label>
                                    </li>   
                                    <?php } ?>
                                </ul>
                              </div>
                            </div>
                          </div>

                          <div class="relative mr-1">
                            <label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('exposure'); ?></label>
                            <div class="form-group mb-0">
                              <div class="btn-group multi_sigle_select">
                                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idopt02<?php echo $ser->id; ?>" style="" aria-expanded="false"><?php if(($ser->processtime/60)>=1) echo (round($ser->processtime/60)).'h '.($ser->processtime%60).'min'; else  echo $ser->processtime.'min';  ?></button>
                                <ul class="dropdown-menu mss_sl_btn_dm noclose scroll-effect height240" x-placement="bottom-start">                                                       
                                    <?php foreach($optArr as $optk=>$optv){ ?>
                                    <li class="radiobox-image">
                                      <input type="radio" id="idoptcom02<?php echo $optk.$ser->id; ?>" class="timeDropDown" data-c="idopt02<?php echo $ser->id; ?>" data-text="<?php echo $optv; ?>" name="duration_process_<?php echo $ser->id; ?>" value="<?php echo $optk; ?>" <?php if(!empty($ser->processtime) && $ser->processtime==$optk || empty($ser->processtime) && 60==$optk) echo 'checked'; ?>><label for="idoptcom02<?php echo $optk.$ser->id; ?>"><?php echo $optv; ?></label>
                                    </li>   
                                    <?php } ?>
                                </ul>
                              </div>
                            </div>
                          </div>

                          <div class="relative mr-1">
                            <label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Complete'); ?></label>
                            <div class="form-group mb-0">
                              <div class="btn-group multi_sigle_select">
                                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idopt01<?php echo $ser->id; ?>" style="" aria-expanded="false"></button>
                                <ul class="dropdown-menu mss_sl_btn_dm noclose scroll-effect height240" x-placement="bottom-start">                                                       
                                    <?php foreach($optArr as $optk=>$optv){ ?>
                                    <li class="radiobox-image">
                                      <input type="radio" id="idoptcom01<?php echo $optk.$ser->id; ?>" class="timeDropDown" data-c="idopt01<?php echo $ser->id; ?>" data-text="<?php echo $optv; ?>" name="duration_<?php echo $ser->id; ?>" value="<?php echo $optk; ?>" <?php if(!empty($ser->finishtime) && $ser->finishtime==$optk || empty($ser->finishtime) && 60==$optk) echo 'checked'; ?>><label for="idoptcom01<?php echo $optk.$ser->id; ?>"><?php echo $optv; ?></label>
                                    </li>   
                                    <?php } ?>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                      <?php } ?>  
                        <div class="max-width1350 d-flex w-100">
                            <div class="deatail-box-left">
                              <p class="color333 font-size-16 fontfamily-medium mb-0 overflow_elips" style="white-space:break-spaces;" data-toggle="tooltip" title="<?php if(!empty($ser->name)) echo $ser->category_name." - ".$ser->name; else  echo $ser->category_name; ?>"><?php if(!empty($ser->name)) echo $ser->category_name." - ".$ser->name; else  echo $ser->category_name; ?></p>
                              <span class="font-size-14 color666 fontfamily-regular"><?php echo $ser->duration; ?> <?php echo $this->lang->line('Minutes'); ?></span>
                            </div>
                            <div class="deatail-box-right d-inline-flex align-items-center">
                                <div class="relative text-right width200 d-block">
                                  <p class=" color333 font-size-14 fontfamily-medium mb-0" >
                                    <?php if($ser->price_start_option=='ab') echo $ser->price_start_option.' ';
                                     echo price_formate($price); ?> €</p>
                                  <?php echo $discntPrice; ?>
                                </div>
                                <a href="javascript:void(0);" class="delete_service_from_merchant" id="<?php echo $ser->id; ?>" data-id="<?php echo $this->session->userdata('st_userid'); ?>">
                                  <img src="<?php echo base_url('assets/frontend/images/crose_grediant_orange_icon1.svg'); ?>" class="widthv15 ml-2">
                                </a>
                            </div>
                         </div>
                      </div>
                      <?php } }  else{  ?>
						  
						   <div class="text-center" style="padding-bottom: 45px;">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;"> Whoops! Looks like there is no data.</p></div>
						  
						  <?php }  if(!empty($_SESSION['book_session']['date'])) $date = date('Y-m-d',strtotime($_SESSION['book_session']['date'])); 
                                  if(!empty($_SESSION['book_session']['time'])) $time  = $_SESSION['book_session']['time']; 
                      
                      ?>
                     
                  </div>

                  <div class="relative pt-3">
                    <p class="font-size-16 color333 fontfamily-medium"><a style="color:#000;" href="<?php echo base_url('booking/new_booking?date='.$date.'&time='.$time); ?>"><img src="<?php echo base_url('assets/frontend/images/add_blue_plus_icon.svg'); ?>" style="width: 24px; height: 24px;" class="mr-2"> <?php echo $this->lang->line('Add_Another_Service'); ?></a></p>
                  </div>
                  <input type="hidden" name="totalDuration" value="<?php echo $totaldurationTim; ?>">
                  <?php if(!empty($booking_detail)){ ?>
						
                  <div class=" d-flex mt-1 mb-5">
					  
                    <div class="relative">
                        <p class="color666 fontfamily-medium font-size-16"><?php echo $this->lang->line('Discount1'); ?></p>
                        <p class="color333 fontfamily-semibold font-size-20"><?php echo $this->lang->line('Payable_Amount'); ?></p>
                      </div>
                      <div class="relative ml-auto text-right">
                        <p class="color666 fontfamily-medium font-size-16"><?php if(!empty($discoutPrice)) echo price_formate($discoutPrice).' €'; else echo '-'; ?> </p>
                        <p class="color333 fontfamily-semibold font-size-20"><?php echo $abcheck.' '.price_formate($totalAmpunt); ?> €</p>
                      </div>
                  </div>
                  
                  <!-- <div class="pb-30 text-center">
                    <button type="button" id="mercjhant_booking_confirm" class="btn">Confirm Booking</button>
                  </div> -->
                 
                 <?php } ?>
                  
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-8 col-lg-4 col-xl-4">
              <div class="bgwhite border-radius4 box-shadow1 pt-20 px-3 py-3 v-after-before mb-30" style="margin-bottom:0px;padding-bottom:8px !important;">
                <p class="color333 font-size-16 fontfamily-medium"><?php echo $this->lang->line('Selected_Customer'); ?></p>

                <div class="btn-group multi_sigle_select inp_select v-droup-new">
                  <div class="relative w-100">
                     <label class="inp-w w-100">
                       <input type="text" placeholder="Search Customer" value="<?php if(!empty($userdataTempdata)) echo $userdataTempdata->name; elseif(!empty($userdata)){  echo $userdata->first_name." ".$userdata->last_name; } else echo $this->lang->line('walk_in'); ?>" class="form-control height56v dropdown-toggle height56v mss_sl_btn" readonly>
                     </label>

                  </div>                           
                 </div>
                   <?php if(!empty($userdataTempdata) || !empty($userdata)){ ?>
                    <div class="relative pb-0 pt-20 text-center">
                      <div class="box-shadow1 text-left p-3 mb-40">
						  <?php if(!empty($userdataTempdata)){ ?>
                        <p class="mb-0 color333 font-size-16 fontfamily-medium"><?php  echo $userdataTempdata->name;  ?></p>
                        <p class="mb-0 color999 font-size-14 fontfamily-regular"><?php  if(!empty($userdataTempdata->mobile)) echo $userdataTempdata->mobile; ?></p>
                        <p class="mb-0 color999 font-size-14 fontfamily-regular overflow_elips w-100"><?php  if(!empty($userdataTempdata->email)) echo $userdataTempdata->email; ?></p>
                        <p class="mb-0 color999 font-size-14 fontfamily-regular"><?php  if(!empty($userdataTempdata->notes)) echo $userdataTempdata->notes; ?></p>
                        
                        <?php } else{ if(!empty($userdata)){ ?>
							         <p class="mb-0 color333 font-size-16 fontfamily-medium"><?php  echo $userdata->first_name." ".$userdata->last_name;  ?></p>
                        <p class="mb-0 color999 font-size-14 fontfamily-regular"><?php  if(!empty($userdata->mobile)) echo $userdata->mobile; ?></p>
                        <p class="mb-0 color999 font-size-14 fontfamily-regular overflow_elips w-100"><?php  if(!empty($userdata->email)) echo $userdata->email; ?></p>
                        <p class="mb-0 color999 font-size-14 fontfamily-regular"><?php  if(!empty($userdata->notes)) echo $userdata->notes; ?></p>
						<?php } } ?>	
                      </div>
                    </div>
                    
                   <?php } ?>
                    
              </div>

              <div class="pt-20 px-3 mb-0">
                <p class="color333 font-size-16 fontfamily-medium"><?php echo $this->lang->line('Employee'); ?></p>
                <div class="form-group">
                      <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new show">
                        <!-- <span class="label ">Select Employee</span> -->
                        <button type="button" class="height56v btn btn-default mss_sl_btn" id="cat_btn" style="text-transform:none !important;">
                          <?php if($employeeData->profile_pic ==""){
                                  $pimg= base_url('assets/frontend/images/user-icon-gret.svg');
                                  $pimgw= base_url('assets/frontend/images/user-icon-gret.svg');
                              }
                              else{
                                  $pimg= getimge_url('assets/uploads/employee/'.$employeeData->id.'/','icon_'.$employeeData->profile_pic,'png');
                                  $pimgw= getimge_url('assets/uploads/employee/'.$employeeData->id.'/','icon_'.$employeeData->profile_pic,'webp');
                              }

                          ?>
                          
                          <picture class="conform-img display-ib mr-3" style="width:30px;height:30px;">
              
                            <img id="chgemp_img<?php echo $employeeData->id; ?>" style="border-radius: 50px;" src="<?php echo $pimg; ?>" class="mr-3 width30">
                          </picture>

                          <?php if(!empty($employeeData)) echo $employeeData->first_name." ".$employeeData->last_name; 
                        else{  if(!empty($_SESSION['book_session']['employee_select']) && $_SESSION['book_session']['employee_select']=='any') echo 'Nächster freier Mitarbeiter'; }
                        
                        ?></button>
<!--
                          <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start">
                            <li class="radiobox-image ">
                            <input type="radio" id="id_users50" name="assigned_users[]" class="" data-val="narendra rathore" value="11">
                            <label for="id_users50" class="height48v vertical-middle pt-2">
                              <img class="employee-round-icon display-ib" src="http://localhost:81/styletimer/assets/frontend/images/user-icon-gret.svg">
                              narendra rathore
                            </label>
                          </li>
                          <li class="radiobox-image ">
                            <input type="radio" id="id_users51" name="assigned_users[]" class="" data-val="narendra rathore" value="11">
                            <label for="id_users51" class="height48v vertical-middle pt-2">
                              <img class="employee-round-icon display-ib" src="http://localhost:81/styletimer/assets/frontend/images/user-icon-gret.svg">
                              narendra rathore
                            </label>
                          </li>
                          <li class="radiobox-image ">
                            <input type="radio" id="id_users52" name="assigned_users[]" class="" data-val="narendra rathore" value="11">
                            <label for="id_users52" class="height48v vertical-middle pt-2">
                              <img class="employee-round-icon display-ib" src="http://localhost:81/styletimer/assets/frontend/images/user-icon-gret.svg">
                              narendra rathore
                            </label>
                          </li>                            
                          </ul>
-->

                       </div>
                   </div>               
              </div>

              <div class="pt-1 px-3 pb-3">
                <p class="color333 font-size-16 fontfamily-medium mt-2"><?php echo $this->lang->line('Additional_Note1'); ?></p>
                  <div class="form-group vmb-40" id="">
                    <label class="inp v_inp_new">
                      <textarea class="form-control height90v custom_scroll" placeholder="&nbsp;" name="additionalNotes"><?php if(!empty($_SESSION['book_session']['additionalNotes'])) echo $_SESSION['book_session']['additionalNotes']; ?></textarea>
                      <!-- <span class="label">Enter Note</span> -->
                    </label>
                 </div>
              </div>
            </div>

        </div>
        <!-- booking conform buttom -->
          <div class="booknow_new_bottom_line_v">
            <button type="button" id="mercjhant_booking_confirm" style="text-transform:none;" class="btn"><?php echo $this->lang->line('Confirm_Booking'); ?></button>
          </div>
        </div>
		</form>
      <!-- dashboard right side end -->       
      </div>




      <!-- modal start -->
<!--
    <div class="modal fade pr-0" id="popup-v12" >
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" class="crose-btn" data-dismiss="modal">
            <img src="images/popup_crose_black_icon.svg" class="popup-crose-black-icon">
          </a>
          <div class="modal-body pt-20 mb-10 pl-25 pr-25 relative">
            <h3 class="font-size-18 color333 fontfamily-medium mb-20">Add New Customer</h3>
            <div class="row">
              <div class="col-12 col-sm-12">
                <div class="form-group vmb-40" id="">
                     <label class="inp v_inp_new">
                       <input type="text" placeholder="&nbsp;" value="" name="name" class="form-control height56v">
                       <span class="label">Service Name </span>
                     </label>
                 </div>
               </div>
               <div class="col-12 col-sm-6">
                 <div class="form-group vmb-40">
                      <div class="btn-group multi_sigle_select inp_select v_inp_new">
                              <span class="label ">Gender</span>
                              <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="cat_btn" aria-expanded="false"></button>
                          <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start">
                            <li class="radiobox-image">
                              <input type="radio" id="id_cat10" name="category" class="select_cat" data-val="Male" value="10">
                              <label for="id_cat10">
                              Male                   
                            </label>
                            </li>
                            <li class="radiobox-image">
                              <input type="radio" id="id_cat11" name="category" class="select_cat" data-val="Female" value="11">
                              <label for="id_cat11">
                              Female                   
                            </label>
                            </li>
                            <li class="radiobox-image">
                              <input type="radio" id="id_cat12" name="category" class="select_cat" data-val="Other" value="12">
                              <label for="id_cat12">
                              Other                   
                            </label>
                            </li>                            
                          </ul>

                       </div>
                   </div>
               </div>
               <div class="col-12 col-sm-6">
                 <div class="form-group vmb-40" id="">
                     <label class="inp v_inp_new">
                       <input type="text" placeholder="&nbsp;" value="" name="" class="form-control height56v">
                       <span class="label">Contact Number</span>
                     </label>
                 </div>
               </div>
               <div class="col-12 col-sm-12">
                 <div class="form-group vmb-40" id="">
                     <label class="inp v_inp_new">
                       <input type="text" placeholder="&nbsp;" value="" name="" class="form-control height56v">
                       <span class="label">Email Id</span>
                     </label>
                 </div>
               </div>

               <div class="col-12 col-sm-12">
                 <div class="form-group vmb-40" id="">
                    <label class="inp v_inp_new">
                      <textarea class="form-control height72v custom_scroll" placeholder="&nbsp;" value="" name=""></textarea>
                      <span class="label">Enter Note</span>
                    </label>
                 </div>

                 <p class="font-size-12 color999 fontfamily-regular relative pl-3"><img src="images/information-button.png" class="absolute left" style="top:4px;"> An email notification will be sent to this customer for confirming booking.</p>
               </div>

               <div class="text-center w-100 mt-3">
                 <button class="btn widthfit ">Save</button>
               </div>

               
            </div>
          </div>
        </div>
      </div>
-->
    <!-- modal end -->





 <?php $this->load->view('frontend/common/footer_script');  ?>
<script src='<?php echo base_url('assets/frontend/js/sweetalert2.js'); ?>'></script>


    <script type="text/javascript">
      $(function() {
        $(window).on("scroll", function() {
            if($(window).scrollTop() > 90) {
                $(".header").addClass("header_top");
            } else {
                //remove the background property so it comes transparent again (defined in your css)
               $(".header").removeClass("header_top");
            }
        });
      });
    </script>
    <script>
       $('#datepicker').datepicker({
           uiLibrary: 'bootstrap4'
       });
    </script>
    <script type="text/javascript">
      $('.clockpicker').clockpicker();
    </script>
    <script type="text/javascript">
      // Tooltips
$('[data-toggle="popover"]').popover({
    trigger: 'hover',
        'placement': 'top'
});


    $(".timeDropDown:checked").each(function(){
	   var clss = $(this).attr('data-c');
	   var texts = $(this).attr('data-text');
	   $("."+clss).text(texts);
	 });
	 
	 
      $('#showkeyword').on('click', function(){
        $('#search_keyword').removeClass('display-n');
      });     
      
      $('.key_word').on('click', function(){
        $('#search_keyword').addClass('display-n');
      });
      $(document).on('click','.radiobox-image', function(){
		$('.dropdown-menu').removeClass('show');
	});
    </script>
    
  </body>
</html>  
