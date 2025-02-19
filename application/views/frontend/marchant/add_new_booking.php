<?php  //if(!empty($_SESSION["book_session"]["customer_id"])) echo $_SESSION["book_session"]["customer_id"]; die;
      $this->load->view('frontend/common/header');   ?>

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
	
	#allBookingService .border-b.d-flex.py-3.pl-3.addToCart .deatail-box-right.d-inline-flex .relative.text-right.width200 . color333.font-size-14.fontfamily-medium.mb-0{
	display: block !important;
    width: 100%;
}
</style>      
        <!-- dashboard left side end -->
         <div class="d-flex pt-84">
<?php $this->load->view('frontend/common/sidebar');

//print_r($_SESSION); die;
 ?>
<script>
  const dummy_img = "<?php echo base_url('assets/frontend/images/upload_dummy_img.svg'); ?>";
</script>
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
      #time_validate .v_time_claender_icon_blue+.error{
		top:56px!important;
		}
    </style>
        <div class="right-side-dashbord w-100 pl-30 pr-15">
          <div class="relative mt-20 mb-60 bgwhite box-shadow1">
          <h3 class="font-size-20 fontfamily-semibold color333 mb-3 pl-4 pt-3"><?php echo $this->lang->line('Add_New_Booking1'); ?></h3>
          <div class="absolute right top mt-10 mr-15"><a href="<?php echo base_url('merchant/dashboard'); ?>" class="font-size-30 color333 a_hover_333">
          <picture class="" style="width: 22px; height: 22px;">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="" style="width: 22px; height: 22px;">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="" style="width: 22px; height: 22px;">
                <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="" style="width: 22px; height: 22px;">
              </picture>
        </a></div>
          <form id="submitBookNow" method="post" action="<?php echo base_url('booking/card_details'); ?>">
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
              <div class="bgwhite border-radius4 box-shadow1 pt-20 px-4 mb-30 pb-1 h-100">
                <div class="row">
                  <div class="col-12 col-sm-5 col-md-5 col-lg-5 col-xl-6">
                    <h6 class="font-size-16 color333 fontfamily-semibold mb-15"><p class="mb-2"><?php echo $this->lang->line('Select_Date_and_Time'); ?></p> <span class="color999 font-size-14 fontfamily-regular"><?php //echo $this->lang->line('Select_service_first'); ?></span></h6>
                  </div>
                  <div class="col-12 col-sm-7 col-md-7 col-lg-7 col-xl-6 text-right pl-0">
                    <div class="relative">

						<?php $repeatMsgClass="";
						      $repetMessage="";

						  if(empty($_SESSION['book_session']['repeatval']) || (!empty($_SESSION['book_session']['repeatval']) && $_SESSION['book_session']['repeatval']=='no')) $repeatMsgClass="display-n";
						  else{ if(!empty($_SESSION['book_session']['repeatText'])) $repetMessage=$_SESSION['book_session']['repeatText']; }

						   ?>
                    <a class="font-size-14 colororange fontfamily-medium a_hover_orange mb-20 display-ib <?php echo $repeatMsgClass; ?>" id="msgRepeat">
                     <?php echo $repetMessage; ?><img style="cursor:pointer;" id="crossRepeat" src="<?php echo base_url("assets/frontend/images/crose_grediant_orange_icon1.svg"); ?>" class="widthv15 ml-1"></a>

                        <input type="hidden" name="repeatText" value='' id="repeatText">
						<input type="hidden" name="repeatval" value='no' id="repeatval">
                        <a href="#" class="font-size-14 colororange fontfamily-medium a_hover_orange display-ib <?php if(empty($cart_detail->service)){ echo 'novalue '; } if($repeatMsgClass!='display-n') echo 'display-n'; ?>" id="popup-toggle"><img src="<?php echo base_url('assets/frontend/images/refresh.png') ?>" class="widthv15"> <?php echo $this->lang->line('Repeat'); ?></a>
                         <span class="color666 font-size-12 fontfamily-regular display-b display-n" id="repeatServiceMsg"><?php echo $this->lang->line('Add_services'); ?></span>
                        <!-- display-none -->
                        <div class="refresh-box-v" style="display: none;">
                          <h3 class="color333 fontfamily-medium font-size-16 text-left mb-20"><?php echo $this->lang->line('Select_Frequency'); ?></h3>
                          <div class="row">
							  <div class="col-12 col-sm-12">

                          <div class="form-froup mb-30" style="top:35px!important;">
                            <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new">
                              <span class="label "><?php echo $this->lang->line('Frequency'); ?></span>
                              <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="cat_btn" aria-expanded="false"></button>
                                <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="overflow: scroll;" x-placement="bottom-start">
                                  <li class="radiobox-image ">
                                  <input type="radio" id="repeat" name="repeat" class="repeatbook" value="">
                                  <label for="repeat" class="height48v vertical-middle pt-2">
                                  <?php echo $this->lang->line('Doesn_repeat'); ?>
                                  </label>
                                </li>
                                <li class="radiobox-image ">
                                  <input type="radio" id="repeatdaily" name="repeat" class="repeatbook"  value="1">
                                  <label for="repeatdaily" class="height48v vertical-middle pt-2">
                                   <?php echo $this->lang->line('Daily'); ?> 
                                  </label>
                                </li>
                                <li class="radiobox-image ">
                                  <input type="radio" id="repeat2day" name="repeat" class="repeatbook"  value="2">
                                  <label for="repeat2day" class="height48v vertical-middle pt-2">
                                    <?php echo $this->lang->line('Every'); ?> 2 <?php echo $this->lang->line('Days'); ?>
                                  </label>
                                </li>
                                <li class="radiobox-image ">
                                  <input type="radio" id="repeat3day" name="repeat" class="repeatbook"  value="3">
                                  <label for="repeat3day" class="height48v vertical-middle pt-2">
                                    <?php echo $this->lang->line('Every'); ?> 3 <?php echo $this->lang->line('Days'); ?>
                                  </label>
                                </li>
                                <li class="radiobox-image ">
                                  <input type="radio" id="repeat4day" name="repeat" class="repeatbook"  value="4">
                                  <label for="repeat4day" class="height48v vertical-middle pt-2">
                                     <?php echo $this->lang->line('Every'); ?> 4 <?php echo $this->lang->line('Days'); ?>
                                  </label>
                                </li>
                                 <li class="radiobox-image ">
                                  <input type="radio" id="repeat5day" name="repeat" class="repeatbook"  value="5">
                                  <label for="repeat5day" class="height48v vertical-middle pt-2">
                                    <?php echo $this->lang->line('Every'); ?> 5 <?php echo $this->lang->line('Days'); ?>
                                  </label>
                                </li>
                                <li class="radiobox-image ">
                                  <input type="radio" id="repeat6day" name="repeat" class="repeatbook"  value="6">
                                  <label for="repeat6day" class="height48v vertical-middle pt-2">
                                    <?php echo $this->lang->line('Every'); ?> 6 <?php echo $this->lang->line('Days'); ?>
                                  </label>
                                </li>
                                 <li class="radiobox-image ">
                                  <input type="radio" id="repeatweekly" name="repeat" class="repeatbook"  value="7">
                                  <label for="repeatweekly" class="height48v vertical-middle pt-2">
                                  <?php echo $this->lang->line('Weekly'); ?> 
                                  </label>
                                </li>
                                 <li class="radiobox-image ">
                                  <input type="radio" id="repeat2weekly" name="repeat" class="repeatbook"  value="14">
                                  <label for="repeat2weekly" class="height48v vertical-middle pt-2">
                                     <?php echo $this->lang->line('Every'); ?> 2 <?php echo $this->lang->line('Weeks'); ?>
                                  </label>
                                </li>
                                <li class="radiobox-image ">
                                  <input type="radio" id="repeat3weekly" name="repeat" class="repeatbook"  value="21">
                                  <label for="repeat3weekly" class="height48v vertical-middle pt-2">
                                     <?php echo $this->lang->line('Every'); ?> 3 <?php echo $this->lang->line('Weeks'); ?>
                                  </label>
                                </li>
                                <li class="radiobox-image ">
                                  <input type="radio" id="repeat4weekly" name="repeat" class="repeatbook"  value="28">
                                  <label for="repeat4weekly" class="height48v vertical-middle pt-2">
                                     <?php echo $this->lang->line('Every'); ?> 4 <?php echo $this->lang->line('Weeks'); ?>
                                  </label>
                                </li>
                                <li class="radiobox-image ">
                                  <input type="radio" id="repeatmonthly" name="repeat" class="repeatbook"  value="month">
                                  <label for="repeatmonthly" class="height48v vertical-middle pt-2">
                                  <?php echo $this->lang->line('Monthly'); ?>
                                  </label>
                                </li>
                                </ul>
                             </div>
                             <label class="error" id="repeatError"></label>
                         </div>
                        
                         </div>

                          <!-- display-none -->
                         <div class="col-12 col-sm-12 display-n" id="ShowslotesRepeat">
                         <div class="form-froup mb-30">
                            <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new">
                              <span class="label "><?php echo $this->lang->line('End_Time'); ?></span>
                              <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="cat_btn" aria-expanded="false"></button>
                                <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="overflow: scroll;" x-placement="bottom-start" id="slotesFroReapetTerms">
                                <li class="radiobox-image ">
                                  <input type="radio" id="termsSlotes" name="terms" class="termsSlotes" value="2">
                                  <label for="termsSlotes" class="height48v vertical-middle pt-2">
                                    <?php echo $this->lang->line('After'); ?> 2 <?php echo $this->lang->line('times'); ?>
                                  </label>
                                </li>
                                 <li class="radiobox-image ">
                                  <input type="radio" id="termsSlotes3" name="terms" class="termsSlotes" value="3">
                                  <label for="termsSlotes3" class="height48v vertical-middle pt-2">
                                    <?php echo $this->lang->line('After'); ?> 3 <?php echo $this->lang->line('times'); ?>
                                  </label>
                                </li>
                                 <li class="radiobox-image ">
                                  <input type="radio" id="termsSlotes4" name="terms" class="termsSlotes" value="4">
                                  <label for="termsSlotes4" class="height48v vertical-middle pt-2">
                                    <?php echo $this->lang->line('After'); ?> 4 <?php echo $this->lang->line('times'); ?>
                                  </label>
                                </li>
                                 <li class="radiobox-image ">
                                  <input type="radio" id="termsSlotes5" name="terms" class="termsSlotes" value="5">
                                  <label for="termsSlotes5" class="height48v vertical-middle pt-2">
                                    <?php echo $this->lang->line('After'); ?> 5 <?php echo $this->lang->line('times'); ?>
                                  </label>
                                </li>
                                <li class="radiobox-image ">
                                  <input type="radio" id="termsSlotesSpecific" name="terms" class="termsSlotes" value="specific">
                                  <label for="termsSlotesSpecific" class="height48v vertical-middle pt-2">
                                  <?php echo $this->lang->line('Specific_date'); ?>
                                  </label>
                                </li>

                                </ul>
                             </div>
                             <label class="error" id="termsrepeatError"></label>
                             </div>
                         </div>
                          <div class="col-12 col-sm-6 display-n" id="repeatSpecificdate">
                             <div class="form-froup vmb-40 v_date_time_picker relative">
                                <input type="text" style="background-color:#fff;" name="reapetdate" placeholder="<?php echo $this->lang->line('Select_Date'); ?>" class="height56v form-control" id="repeatdatepicker" readonly>
                                <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue">
                                 <label class="error" id="specificrepeatError"></label>
                            </div>

                           </div>
                         </div>

                         <div class="d-flex">
                           <button type="button" class="btn btn-border-orange widthfit height48v width150v" id="closeReapet"><?php echo $this->lang->line('Close'); ?></button>
                           <button type="button" class="btn widthfit height48v width150v" id="applyReapet"><?php echo $this->lang->line('Apply_Changes'); ?></button>
                         </div>

                        </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-5 pr-0">
                    <div class="form-froup vmb-40 v_date_time_picker relative" id="date_validate">
                        <?php if(!empty($_GET['date'])){ 
                          $dt = date('d.m.Y',strtotime($_GET['date'])); }
                        else if(!empty($_SESSION['book_session']['date'])){ 
                          $dt = $_SESSION['book_session']['date']; 
                        }else{
                          $dt = $default_date;
                        } ?>

                        <input type="text" style="background-color:#fff;" name="date" value="<?php echo $dt; ?>" placeholder="<?php echo $this->lang->line('Select_Date'); ?>" class="height56v form-control" id="datepicker" autocomplete="off" readonly>
                        <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg') ?>" class="v_time_claender_icon_blue">
                    </div>
                  </div>
                  <div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-5 pr-0">
<!--
                    <div class="form-froup vmb-40 v_date_time_picker relative" id="time_validate">
                        <input type="text" style="background-color:#fff;" name="time" value="<?php //if(!empty($_GET['time'])) echo $_GET['time']; ?>"  data-autoclose="true" placeholder="<?php echo $this->lang->line('Select_Time'); ?>" class="height56v form-control " id="" autocomplete="off" readonly>
                        <img src="<?php //echo base_url('assets/frontend/images/blue-clock.svg') ?>" class="v_time_claender_icon_blue">
                    </div> 
-->
					           <div class="form-group ">
                      <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new">
                        <span class="label " id="timeSelected"><?php echo $this->lang->line('Select_Time'); ?></span>
                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn timeSelected height56v" data-use='1' id="cat_btn" aria-expanded="false"></button>
                          <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start" id="bookingTime" style="overflow-x: auto; height: 320px;">
<!--
							<li class="radiobox-image">
                              <input type="radio" id="onetime" name="cc" class="" data-val="" value="cc">
                              <label for="onetime">
                              12
                            </label>
                            </li>
                            <li class="radiobox-image">
                              <input type="radio" id="towtime" name="cc" class="" data-val="" value="cc">
                              <label for="towtime">
                              11
                            </label>
                            </li>
-->
                          </ul>

                       </div>
                        <label class="error" id="timeSelectErr"></label>
                   </div>
                  </div>
                </div>
                <h6 class="font-size-16 color333 fontfamily-semibold mb-25"><?php echo $this->lang->line('Select_Services'); ?></h6>

                <div class="backend">
                  <div class="row row_new_v relative d-flex">

					<?php if(!empty($main_category)){ ?>
						<!-- <div class="col-20"> -->
					  <div class="select_v active" data-id='all'>
						<picture class="">
							<source srcset="<?php echo base_url('assets/frontend/images/box-5-fill-img1.webp') ?>" type="image/webp" class="">
							<source srcset="<?php echo base_url('assets/frontend/images/box-5-fill-img1.webp') ?>" type="image/webp" class="">
							<img src="<?php echo base_url('assets/frontend/images/box-5-fill-img1.svg') ?>" class="">
						  </picture>
						  <span class="color333 fontfamily-medium font-size-12 display-b span_text_block lineheight24 mt-2"><?php echo $this->lang->line('Show_all'); ?></span>
					   </div>

					  <div class="select_v" data-id='most_booked'>
						<picture class="">
							<source srcset="<?php echo base_url('assets/frontend/images/box-5-fill-img1.webp') ?>" type="image/webp" class="">
							<source srcset="<?php echo base_url('assets/frontend/images/box-5-fill-img1.webp') ?>" type="image/webp" class="">
							<img src="<?php echo base_url('assets/frontend/images/box-5-fill-img1.svg') ?>" class="">
						  </picture>
						  <span class="color333 fontfamily-medium font-size-12 display-b span_text_block lineheight24 mt-2"><?php echo $this->lang->line('Most_booked'); ?></span>
					   </div>
					  <!-- </div>
					  <div class="col-80"> -->
					   <!-- <div class="relative d-flex" id=""> -->
    					  <?php foreach($main_category as $cat)
    					      {  if($cat->icon !='')
								     {
    								   $cat_img= getimge_url('assets/uploads/category_icon/'.$cat->mainid.'/',$cat->icon,'png');
    								   $cat_imgw= getimge_url('assets/uploads/category_icon/'.$cat->mainid.'/',$cat->icon,'webp');
								     }
    							  else{
    								$cat_img=base_url('assets/frontend/images/noimage.png');
    								$cat_imgw=base_url('assets/frontend/images/noimage.webp');
    								} ?>
    								
    						   <div class="select_v" data-id="<?php echo $cat->mainid; ?>">
    							   
    							   <picture>
										<source srcset="<?php echo $cat_imgw; ?>" type="image/webp">
										<source srcset="<?php echo $cat_imgw; ?>" type="image/webp">
									   <img src="<?php echo $cat_img; ?>">
									</picture>
    							   <span class="color333 fontfamily-medium font-size-12 display-b span_text_block lineheight24 mt-2"><?php echo $cat->category_name; ?></span>
    						   </div>
                 <?php } ?> 
              <!--  </div> -->
            <!-- </div> -->
          <?php }  else{ ?>
                 <div class="text-center" style="margin: 0 auto;">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>">
            <p style="margin-top: 20px;">There haven't been any services added yet</p></div>
                <?php } ?>

               </div>
              </div>
                  <div class="v_overflow_scroll custom_scroll" id="allBookingService">

                  </div>

                  
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-8 col-lg-4 col-xl-4">
              <div class="bgwhite border-radius4 box-shadow1 pt-20 px-3 py-3 v-after-before mb-30">
                <p class="color333 font-size-16 fontfamily-medium"><?php echo $this->lang->line('Select_Customer'); ?></p>
                <input type="hidden" name="uid" id="tempuid" value="<?php if(!empty($_SESSION['book_session']['uid'])) echo $_SESSION['book_session']['uid']; ?>">

                <div class="btn-group multi_sigle_select inp_select v-droup-new">
                  <div class="relative w-100">
                     <label class="inp-w w-100">
                       <input type="text" placeholder="<?php echo $this->lang->line('Select_Search'); ?>" class="form-control height56v dropdown-toggle height56v mss_sl_btn" id="showkeyword" data-toggle="dropdown" autocomplete="off">
                     </label>
                     <div class="bgwhite mss_sl_btn_dm scroll300 custom_scroll display-n" id="search_keyword">
                        <ul class="pl-0 scroll300 custom_scroll" style="height:272px;overflow: scroll;" id="bookingAllCustumers">

                        </ul>

                     </div>
                     <label class="error" id="customer_err"></label>
                  </div>
              </div>


                    <div class="relative around-20 text-center" id="customePreview">
                      <img src="<?php echo base_url('assets/frontend/images/user-ic.png') ?>" style="width: 70px;">
                      <p class="font-size-12 color999 fontfamily-regular mt-3"><?php echo $this->lang->line('use_the_search'); ?></p>
                      <a href="#" class="height56v vertical-middle colorcyan font-size-14 fontfamily-regular a_hover_cyan display-b p-3"  data-toggle="modal" data-target="#popup-v12"><?php echo $this->lang->line('Add_Customer'); ?></a>
                    </div>
              </div>

              <div class="pt-20 px-3 mb-0">
                <p class="color333 font-size-16 fontfamily-medium"><?php echo $this->lang->line('Employee'); ?></p>
                    <div class="form-group">
                      <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new bookingAllEmployee" id="">
                        <span class="label" id="employeSelected"><?php echo $this->lang->line('Select_Employee'); ?></span>
                        <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn employeSelected" id="cat_btn" aria-expanded="false" style="text-transform: none !important;"></button>
                          <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="overflow: scroll;" x-placement="bottom-start" id="bookingAllEmployee">

                          </ul>

                       </div>
                        <label class="error" id="employee_err"><?php echo $this->lang->line('add_service_first_before_selecting_employee'); ?></label>
                   </div>
              </div>
              <div class="pt-1 px-3 pb-3">
                <p class="color333 font-size-16 fontfamily-medium mt-2" style="padding-top:10px"><?php echo $this->lang->line('Additional_Note'); ?></p>
                  <div class="form-group vmb-40" id="">
                    <label class="inp v_inp_new height90v">
                      <textarea class="form-control height90v custom_scroll" placeholder="&nbsp;" value="" name="additionalNotes"><?php if(!empty($_SESSION['book_session']['additionalNotes'])) echo $_SESSION['book_session']['additionalNotes']; ?></textarea>
                      <span class="label"><?php echo $this->lang->line('Enter_Note'); ?></span>
                    </label>
                 </div>
              </div>
            </div>
          </div>

          <!-- booknow -->

      <div class="mx-auto booknow_new_bottom_line_v mt-2" <?php if(empty($cart_detail->service)){ ?>style="display: none;" <?php } ?> id="my_cart_div">
          <div class="d-flex bookingSelect align-items-center">
            <div class="deatail-box-left">
              <p class="colorwhite font-size-16 fontfamily-medium mb-0 overflow_elips" id="service_count"><?php if(!empty($cart_detail->service)) echo $cart_detail->service;  if($cart_detail->service>1) echo ' Services'; else echo ' Service'; ?> <?php echo $this->lang->line('Added'); ?></p>
              <span class="font-size-14 color999 fontfamily-regular"><span id="totalDuration"><?php if(!empty($cart_detail->duration1)) echo $cart_detail->duration1; ?></span> <?php echo $this->lang->line('Minutes'); ?></span>
            </div>
            <div class="deatail-box-right d-inline-flex align-items-center" style="margin-top: 2px;margin-right:31px;">
              <button class="btn selectedBtn widthfit2 btn-ml-20 m-auto "><?php echo $this->lang->line('Book_Now'); ?></button>
              <p class="show-on-mobile colorcyan font-size-18 fontfamily-semibold mb-0"><span id="total_amt"><?php if(!empty($cart_detail->price_start_option) && $cart_detail->price_start_option=='ab') echo 'ab '; if(!empty($cart_detail->tot_price)) echo price_formate($cart_detail->tot_price); ?></span> €</p>              
            </div>
            <div class="relative text-right width200">
              <p class="show-on-desktop colorcyan font-size-18 fontfamily-semibold mb-0"><span id="total_amt"><?php if(!empty($cart_detail->price_start_option) && $cart_detail->price_start_option=='ab') echo 'ab '; if(!empty($cart_detail->tot_price)) echo price_formate($cart_detail->tot_price); ?></span> €</p>
            </div>
          </div>
        </div>
      </div>
        
  	</form>
  <!-- dashboard right side end -->
  </div>

  <div class="modal fade" id="time_exceed_modal">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
      <div class="modal-content text-center">      
        <a href="#" class="crose-btn" data-dismiss="modal" >
          <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
        </a>
        <div class="modal-body pt-30 mb-30 pl-40 pr-40">
          <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">Die Dauer der ausgewählten Services überschreitet deine Öffnungszeiten.    </p>
          <button type="button" data-dismiss="modal" class="btn btn-large widthfit">OK</button>
        </div>
      </div>
    </div>
  </div>
<?php
  $this->load->view('frontend/common/footer_script');
  $this->load->view('frontend/marchant/add_new_customer');
?>
<?php
  $msgg =getmembership_daycount($this->session->userdata('st_userid'));

   if($msgg!=""){  ?>
    <!-- <div class="modal fade" id="membershipWarningPopup">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
          <div class="modal-content text-center">      
            <a href="<?php if(!empty($_SERVER['HTTP_REFERER']))
             echo $_SERVER['HTTP_REFERER']; 
             else echo base_url('merchant/dashboard'); ?>" class="crose-btn">
            <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
            </a>
            <h5 class="text-left pt-20 pl-15 color333"><?php echo $this->lang->line('Membership_expired'); ?></h5>
           <div class="pt-10 px-2">
            <p style="font-size: 18px;"><?php echo $msgg; ?></p>
            </div>
           
          </div>
        </div>
      </div> -->
      <div class="modal fade" id="membershipWarningPopup">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="<?php if(!empty($_SERVER['HTTP_REFERER']))
             echo $_SERVER['HTTP_REFERER']; 
             else echo base_url('merchant/dashboard'); ?>" class="crose-btn" data-dismiss="modal" id="conftime_close">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
   </br>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/service_provider_membership_icon.png'); ?>">
            <!-- <h5 class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:3;"> -->
               <!-- <?php
               //echo $_SESSION['sty_membership']; ?> </h5> -->
                       <!-- <h5 class="font-size-18  fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1"
                        style="-webkit-line-clamp:3;">
                       <?php // echo //$this->lang->line('Membership_expired'); ?>
                        <a href="<?php //echo base_url('membership')?>"  style="color:#00A8B2">
                        </a></h5>  -->
                        <h5 class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:3;">
                       Dein Abonnement ist abgelaufen, 
                       Sichere dir jetzt eine neue <a href="<?php echo base_url('membership')?>"  style="color:#00A8B2">Mitgliedschaft!</a></h5> 
                       <a href="<?php echo base_url('membership')?>"  class=" btn btn-large widthfit display-ib mr-4" > zu unseren Mitgliedschaften</a>
          </div>
        </div>
      </div>
    </div>
    
<?php  }  ?>

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
  


    var date = new Date();
    var today =new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var date40year = new Date('<?php  echo date("Y-m-d",strtotime("-90 years",time())); ?>');
   var sixyears = new Date("<?php echo date('Y-m-d H:i:s',strtotime('+3 months')); ?>");
    var twomonths =new Date(sixyears.getFullYear(), sixyears.getMonth(), sixyears.getDate());

       $('#repeatdatepicker').datepicker({
          uiLibrary: 'bootstrap4',
          locale: 'de-de',
          dateFormat: "dd.mm.yy",
          minDate:today,
          maxDate:twomonths,
          changeMonth: true,
          changeYear: true,
          yearRange: date40year.getFullYear() + ':' + date.getFullYear(),
          prevText: '&#x3c;zurück', prevStatus: '',
          prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
          nextText: 'Vor&#x3e;', nextStatus: '',
          firstDay: 1,
          nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
          currentText: 'heute', currentStatus: '',
          todayText: 'heute', todayStatus: '',
          clearText: '-', clearStatus: '',
          closeText: 'schließen', closeStatus: '',
          monthNames: ['Januar','Februar','März','April','Mai','Juni',
          'Juli','August','September','Oktober','November','Dezember'],
          monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
          dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
          dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
          dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
          dateFormat: 'dd.mm.yy',
       });
       $('#datepicker').datepicker({
          uiLibrary: 'bootstrap4',
          locale: 'de-de',
          dateFormat: "dd.mm.yy",
          minDate:today,
          maxDate:twomonths,
          changeMonth: true,
          changeYear: true,
          yearRange: date40year.getFullYear() + ':' + date.getFullYear(),
          prevText: '&#x3c;zurück', prevStatus: '',
          prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
          nextText: 'Vor&#x3e;', nextStatus: '',
          firstDay: 1,
          nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
          currentText: 'heute', currentStatus: '',
          todayText: 'heute', todayStatus: '',
          clearText: '-', clearStatus: '',
          closeText: 'schließen', closeStatus: '',
          monthNames: ['Januar','Februar','März','April','Mai','Juni',
          'Juli','August','September','Oktober','November','Dezember'],
          monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
          dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
          dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
          dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
          dateFormat: 'dd.mm.yy',
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
 var userid= 0;
 <?php if(isset($_GET['userid']) && !empty($_GET['userid'])){ ?>
         var userid = '<?php echo url_decode($_GET['userid']); ?>';
         /*setTimeout(function() {
          $('#idCustumer'+uid).click();
          }, 1000);*/
<?php }  ?>

function getCustomerList(defaultCustomer = -1){


var sch=$('#showkeyword').val();

var url=base_url+"booking/customer_list";

 loading();
 var status = localStorage.getItem('STATUS');
   if(status=='LOGGED_OUT'){
     console.log('STATUS='+status);
     console.log(base_url)
     window.location.href = base_url;
     $(window.location)[0].replace(base_url);
     $(location).attr('href',base_url);
   }
   console.log('STATUS='+status);

 $.post(url,{search:sch},function( data ) {


   var obj = jQuery.parseJSON( data );
   if(obj.success=='1'){
     //var time = $("#selctTimeFilter").val();
     $("#bookingAllCustumers").html(obj.html);
     if(obj.checkedName!=''){
       $('#showkeyword').val(obj.checkedName);

       //var email=$('[name="customer_id"]:checked').data('email');
       //var notes=$('[name="customer_id"]:checked').data('notes');
       //var mobile=$('[name="customer_id"]:checked').data('mobile');
       //var name=$('[name="customer_id"]:checked').data('val');

       var html='<div class="text-left p-3" style="box-shadow:0px 3px 25px rgba(215,232,233,.78);">\
           <p class="mb-0 color333 font-size-16 fontfamily-medium">'+obj.checkedName+'</p>\
           <p class="mb-0 color999 font-size-14 fontfamily-regular">'+obj.mobile+'</p>\
           <p class="mb-0 color999 font-size-14 fontfamily-regular">'+obj.email+'</p>\
           <p class="mb-0 color999 font-size-14 fontfamily-regular">'+obj.notes+'</p>\
           </div>\
           <a href="#" id="removeCustomer" class="height56v vertical-middle colorcyan font-size-14 fontfamily-regular a_hover_cyan display-b p-3"><?php echo $this->lang->line("Remove_Customer"); ?></a>';
       $("#customePreview").html(html);
     }

     if(userid != 0) 
       $('#idCustumer'+userid).click();

     if (defaultCustomer != -1) {
       $('#idCustumer'+defaultCustomer).click();
     }
       //$("#pagination").html(obj.pagination);

   }
   unloading();
 });

}

$(document).ready(function(){

     var checkmembership =`<?php echo $msgg; ?>`;
    //
     if(checkmembership!=''){
        $('#membershipWarningPopup').modal({backdrop: 'static', keyboard: false});  
       //$("#membershipWarningPopup").modal('show');

     }
     //~ $(document).on('click','.bookingAllEmployee,#bookingAllEmployee',function(){
	  //~ var checkedService= $('.selectedBtn').length;
	     //~ alert(checkedService);
	  //~ });

     
     
	$(document).on('click','.select_v',function(){
	var id=$(this).data('id');
	//alert(id);
	getAllService(id);
	$(".select_v.active").removeClass('active');
	$(this).addClass('active');
	});


	 $('#showkeyword').on('click', function(){
		$("#showkeyword").val("");
		$(".slectCustomer").each(function(){
				$(this).prop("checked", false);
				});
        $('#search_keyword').removeClass('display-n');
      });

      $('.key_word').on('click', function(){
        $('#search_keyword').addClass('display-n');
      });

   $('#popup-toggle,#closeReapet').on('click', function(){
         var repaetOption=$('[name="repeat"]:checked').val();
        if(repaetOption==undefined || repaetOption==''){
			$("#repeatError").html('');
			$("#termsrepeatError").html('');
			$("#ShowslotesRepeat").addClass('display-n');
			}
        if($(this).hasClass('novalue')){
			$('#repeatServiceMsg').removeClass('display-n');
			
			return false
			}
		else{
		  $('#repeatServiceMsg').addClass('display-n');	
           $('.refresh-box-v').toggle();
	     }
      });

  getTimeSlot();
  getCustomerList();
  getEmployeeList();
  getAllService();

 function getEmployeeList(){


   var date=$('#datepicker').val();
   var time=$('[name="time"]:checked').val();
   var empId="";
   if(time==undefined || time==''){
	   time="<?php if(!empty($_GET['time'])) echo $_GET['time']; ?>";
	   }
	   
	 if($('[name="employee_select"]:checked').length==1) empId=$('[name="employee_select"]:checked').val();  
   var url=base_url+"booking/employee_list";

    loading();
    var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);

    $.post(url,{date:date,time:time,empid:empId},function( data ) {


      var obj = jQuery.parseJSON( data );
         if(obj.success=='1'){
        //var time = $("#selctTimeFilter").val();
        $("#bookingAllEmployee").html(obj.html);
         if(obj.checkedName!=''){
			  $("#employeSelected").addClass('label_add_top');
			  $(".employeSelected").html(obj.checkedName);
		   }
		 else{
			  $("#employeSelected").removeClass('label_add_top');
			  $(".employeSelected").html('');
			 }
			
		 if($('[name="employee_select"]:checked').length<=0){
			 //alert('d');
			 if(obj.count==2){
				 $(".eclass1").attr("checked",true);
				 $("#employeSelected").addClass('label_add_top');
			      $(".employeSelected").html($(".eclass1").attr("data-val"));
				 }
			  else{
				  $(".eclassany").attr("checked",true);
				
				  $("#employeSelected").addClass('label_add_top');
			       
			      $(".employeSelected").html($(".eclassany").attr("data-val"));
				  }	 
			 }	 
          if(obj.msg!=''){
            $('#employee_err').text(obj.msg);
           }
         }
      unloading();
      });

    }

 function getAllService(id=''){


   var date=$('#datepicker').val();
   var time=$('[name="time"]:checked').val();
   if(time==undefined){
      time ="<?php if(!empty($_GET['time'])) echo $_GET['time']; ?>";
   }
    var url=base_url+"booking/get_booking_service";

    loading();
    var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);

    $.post(url,{date:date,time:time,id:id},function( data ) {


      var obj = jQuery.parseJSON( data );
         if(obj.success=='1'){
        //var time = $("#selctTimeFilter").val();
        $("#allBookingService").html(obj.html);
      var checkedServiceCount=$(".selectedBtn").length;
         if(checkedServiceCount>1){
			 $("#employee_err").html("");
			 }

         }
      unloading();
      });

    }

  function getTimeSlot(){


  var date=$('#datepicker').val();
  var empId="";
  var time="";
  if($(".timeSelected").attr('data-use')=='1'){
    //alert('sdf');
	   
    $(".timeSelected").attr('data-use','2');
    
    var time="<?php if(!empty($_GET['time'])) echo $_GET['time']; else echo ''; ?>";
  }
  if(time==undefined || time==''){
    var time=$('[name="time"]:checked').val();
  }
  if($('[name="employee_select"]:checked').length==1)
    empId=$('[name="employee_select"]:checked').val();

  if(date!=''){
    var testUrl = base_url+"booking/check_booking_possible";
    loading();
    var status = localStorage.getItem('STATUS');
    if(status=='LOGGED_OUT'){
      console.log('STATUS='+status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href',base_url);
    }
    console.log('STATUS='+status);
    var url=base_url+"booking/get_merchant_time_slot";
    loading();
    var status = localStorage.getItem('STATUS');
    if(status=='LOGGED_OUT'){
      console.log('STATUS='+status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href',base_url);
    }
    console.log('STATUS='+status);

    $.post(url,{date:date,time:time,empid:empId},function(data){
      var obj = jQuery.parseJSON( data );
      if(obj.success=='1'){
        //var time = $("#selctTimeFilter").val();
        $("#bookingTime").html(obj.html);
        if(obj.checktime!=""){
          //alert('if');
          $(".timeSelected").html(obj.checktime);
          $("#timeSelected").addClass('label_add_top');
        }
        else{
        //alert('else');
          $(".timeSelected").html('');
          $("#timeSelected").removeClass('label_add_top');
        }
      // $("#pagination").html(obj.pagination);
      }
      unloading();
    });        
    
  }else{
	  var html1='<li class="radiobox-image">\
					  <input type="radio" id="booktime" name="time" class="booktime" value="">\
					  <label for="booktime">Please select a date</label>\
					</li>';
	  $("#bookingTime").html(html1);
	  }

    }

  $(document).on('change','.selecteEmp',function(){
	  //alert('calltimeSlote');
	  getTimeSlot();
	});
	
 $(document).on('keyup',"#showkeyword",function(){
    getCustomerList();
  });
  

 $(document).on('change',".slectCustomer",function(){

    $('#showkeyword').val($(this).data('val'));
    $('#search_keyword').addClass('display-n');
   $("#tempuid").val('');
    var email=$(this).data('email');
    var notes=$(this).data('notes');
    var mobile=$(this).data('mobile');
    var name=$(this).data('val');

              var html='<div class="text-left p-3" style="box-shadow:0px 3px 25px rgba(215,232,233,.78);">\
                        <p class="mb-0 color333 font-size-16 fontfamily-medium">'+name+'</p>\
                        <p class="mb-0 color999 font-size-14 fontfamily-regular">'+mobile+'</p>\
                        <p class="mb-0 color999 font-size-14 fontfamily-regular">'+email+'</p>\
                        <p class="mb-0 color999 font-size-14 fontfamily-regular">'+notes+'</p>\
                        </div>\
                        <a  href="#" class="height56v vertical-middle colorcyan font-size-14 fontfamily-regular a_hover_cyan display-b p-3" id="removeCustomer"><?php echo $this->lang->line("Remove_Customer"); ?></a>';
              $("#customePreview").html(html);
              $("#aadNewCustomer")[0].reset();

  });


 $(document).on('click',"#removeCustomer",function(){

	  var html='<img src="<?php echo base_url("assets/frontend/images/user-icon-gret.svg") ?>" style="width: 70px;">\
                      <p class="font-size-12 color999 fontfamily-regular mt-3"><?php echo $this->lang->line("use_the_search"); ?></p>\
                      <a href="#" class="height56v vertical-middle colorcyan font-size-14 fontfamily-regular a_hover_cyan display-b p-3"  data-toggle="modal" data-target="#popup-v12"><?php echo $this->lang->line("Add_Customer"); ?></a>';
       
        $("#aadNewCustomer")[0].reset();
        
        $("#customePreview").html(html);
        $("#showkeyword").val("");
        $("#tempuid").val('');
         $(".slectCustomer").each(function(){
				$(this).prop("checked", false);
			});
      var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
		 $.ajax({
				url: base_url+"booking/removeBooking_session",
				type: "POST",
				data:{type:'user'},
				success: function (data) {

				 }

			});

  });


 $(document).on('blur',"#datepicker",function(){
	 //alert('fg');
	 setTimeout(function(){ 
    loading();
      $.ajax({ 
        url: base_url+"booking/clear_cart",
        type: "POST",
        success: function (data) {
          var obj = jQuery.parseJSON( data );
          if(obj.success ==1){
          getEmployeeList();
          getTimeSlot();
          $('#popup-toggle').addClass("novalue");  
          $('#my_cart_div').hide();

          $(".widthfit80v.selectedBtn").each(function(){
            $(this).removeClass('selectedBtn');
            $(this).addClass('btn-border-orange');
          });
          getAllService();
          $("#employee_err").html("<?php echo $this->lang->line('add_service_first_before_selecting_employee'); ?>");
        }
          unloading();
        
        }
      });  
    }, 500);

  });
 $(document).on('change',".booktime",function(){
	     //getEmployeeList();
       var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status); 
      loading();
      $.ajax({ 
        url: base_url+"booking/clear_cart",
        type: "POST",
        success: function (data) {
          var obj = jQuery.parseJSON( data );
          if(obj.success ==1){
          getEmployeeList();
          $('#popup-toggle').addClass("novalue");  
          $('#my_cart_div').hide();

          $(".widthfit80v.selectedBtn").each(function(){
            $(this).removeClass('selectedBtn');
            $(this).addClass('btn-border-orange');
          });
          getAllService();
          $("#employee_err").html("<?php echo $this->lang->line('add_service_first_before_selecting_employee'); ?>");
        }
          unloading();
        
        }
      });  
  });

$(document).on('click','.addToCart', function(){
		var bid=$(this).attr('id');
    var mid=$(this).attr('data-id');
    var valu=$(this).attr('data-val');


    var data = {'id' : bid, 'mid' : mid ,'price':valu};
    var status = localStorage.getItem('STATUS');
    if(status=='LOGGED_OUT'){
      console.log('STATUS='+status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href',base_url);
    }
    console.log('STATUS='+status);
    loading();

    var date=$('#datepicker').val();
    var empId="";
    var time="";
    if($(".timeSelected").attr('data-use')=='1'){
      //alert('sdf');
      
      $(".timeSelected").attr('data-use','2');
      
      var time="<?php if(!empty($_GET['time'])) echo $_GET['time']; else echo ''; ?>";
    }
    if(time==undefined || time==''){
      var time=$('[name="time"]:checked').val();
    }
    if($('[name="employee_select"]:checked').length==1)
      empId=$('[name="employee_select"]:checked').val();
    var testUrl = base_url+"booking/check_booking_possible";

    $.post(testUrl,{date:date,time:time,empid:empId,sid: bid},function(data1){
      var obj = jQuery.parseJSON( data1 );
      if(obj.success=='1' && obj.result == 'true'){
        $.ajax({
            url: base_url+"booking/add_service_in_cart_from",
            type: "POST",
            data:data,
            success: function (data) {
              var obj = jQuery.parseJSON( data );
              if(obj.success ==1){
                if(obj.count !=0){
                  if(obj.count>1){
                      $('#service_count').html(obj.count+' Services hinzugefügt');
                    }
                    else{

                    $('#service_count').html(obj.count+' Service hinzugefügt');
                    }
                $('#popup-toggle').removeClass("novalue");
                $('#repeatServiceMsg').addClass('display-n');			  
                $('#total_amt').html(obj.total);
                $('#totalDuration').html(obj.totalDuration);
                $('#my_cart_div').show();

                //$( "#mydiv" ).hasClass( "foo" )
                $(".class-"+bid).removeClass(obj.revCls);
                $(".class-"+bid).addClass(obj.addCls);
                
                if(obj.deletedId !=''){
                  $(".class-"+obj.deletedId).removeClass('selectedBtn');
                  $(".class-"+obj.deletedId).addClass('btn-border-orange');
                  }
                
                
                $("#employee_err").html("");
                //$("#"+bid).removeClass(obj.revCls);
                //$("#"+bid).addClass(obj.addCls);
                  unloading();
                }
                else{
                  $('#popup-toggle').addClass("novalue" 	);	
                  $('#my_cart_div').hide();
                  $(".class-"+bid).removeClass(obj.revCls);
                  $(".class-"+bid).addClass(obj.addCls);
                  $("#employee_err").html("<?php echo $this->lang->line('add_service_first_before_selecting_employee'); ?>");
                  unloading();
                }
                getEmployeeList();
                getTimeSlot();
              }
              else if(obj.success == 2){
                unloading();
                var url=$('#select_url').val();
                $("#error_message").css("display", "");
                $('#service-loging-check').modal('show');
                $('#addservice_message').html(obj.message);
                //window.location.href = base_url+'auth/login/'+url;
                $(window).scrollTop(200);
                setTimeout(function() {
                $('#error_message').hide();
                  }, 5000);
              }
              else{
                unloading();
                var url=$('#select_url').val();
                $("#error_message").css("display", "");
                $('#service-loging-check').modal('show');
                //window.location.href = base_url+'auth/login/'+url;
                $(window).scrollTop(200);
                setTimeout(function() {
                $('#error_message').hide();
                  }, 5000);

              }
            }
        });
      } else {
        $("#time_exceed_modal").modal('show');
      }
      unloading();
    });
});

$(document).on("change",".repeatbook",function(){
   var val=$(this).val();
   if(val!=""){
      $("#ShowslotesRepeat").removeClass('display-n');
    }
  else{
	  $('.refresh-box-v').toggle();
	  }
  });

$(document).on("change",".termsSlotes",function(){
   var val=$(this).val();
   if(val=="specific"){
      $("#ShowslotesRepeat").addClass('col-sm-6');
      $("#ShowslotesRepeat").removeClass('col-sm-12');
      $("#repeatSpecificdate").removeClass('display-n');
    }else{
		if($("#ShowslotesRepeat").hasClass('col-sm-6')){
	       $("#ShowslotesRepeat").addClass('col-sm-12');
	       $("#ShowslotesRepeat").removeClass('col-sm-6');
	       $("#repeatSpecificdate").addClass('display-n');
         }
		}

  }); 

  $(document).on("click","#applyReapet",function(){

    var repaetOption=$('[name="repeat"]:checked').val();
    var terms=$('[name="terms"]:checked').val();
    var specificDate=$("#repeatdatepicker").val();
    var date=$('#datepicker').val();
    var time=$('[name="time"]:checked').val();
    
    var chkspecificDate=new Date(specificDate).getTime();
    var chkdate=new Date(date).getTime();

    if(repaetOption==undefined || repaetOption==''){
		$("#repeatError").css('display','block');
		$("#repeatError").html('Please select a frequency');
		return false;
		}
	else{
		$("#repeatError").css('display','none');
		$("#repeatError").html('');
		}
    if(terms==undefined || terms==''){
		$("#termsrepeatError").css('display','block');
		$("#termsrepeatError").html('Endzeit auswählen');
		return false;
		}
	else{
		$("#termsrepeatError").css('display','none');
		$("#termsrepeatError").html('');
		}
    var employee_id=""
    var empId=$('[name="employee_select"]:checked').val();

    if(empId!=undefined)
      {
		employee_id=empId;
	  }

    if(terms=="specific"){
		if(specificDate==""){
			$("#specificrepeatError").css('display','block');
		    $("#specificrepeatError").html('Please select specific date for repeat');
			return false;
			}
	    if(chkspecificDate<=chkdate)
	       {
			$("#specificrepeatError").css('display','block');
		    $("#specificrepeatError").html('The specific date should be greater than booking date');
			return false;
		  }
		$("#specificrepeatError").css('display','none');
	    $("#specificrepeatError").html('');
		}
	 var data = {'repaetOption' : repaetOption, 'terms' : terms ,'specificDate':specificDate,'date':date,'time':time,'employee_id':employee_id};
   var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
     loading();
		 $.ajax({
				url: base_url+"booking/repeat_check_availablity",
				type: "POST",
				data:data,
				success: function (data) {
					var obj = jQuery.parseJSON( data );
					//console.log(obj);
					if(obj.success ==1){

							$('#popup-toggle').addClass('display-n');
							$('.refresh-box-v').toggle();
							$('#msgRepeat').removeClass('display-n');
							$('#msgRepeat').html(obj.msg+'<img style="cursor:pointer;" id="crossRepeat" src="<?php echo base_url("assets/frontend/images/crose_grediant_orange_icon1.svg"); ?>" class="widthv15 ml-1">');
							$("#repeatText").val(obj.msg);
							$("#bookingAllEmployee").html(obj.html);
							$("#repeatval").val('yes');
							$("#repeatError").css('display','none');
						    $("#repeatError").html('');

						     if(obj.checkedName!=''){
								  $("#employeSelected").addClass('show');
								  $(".employeSelected").html(obj.checkedName);
							   }
							 else{
								  $("#employeSelected").removeClass('show');
								  $(".employeSelected").html('');
								 }
							//$(".class-"+bid).removeClass(obj.revCls);
							//$(".class-"+bid).addClass(obj.addCls);

					  }
					 else{
						  $("#repeatError").css('display','block');
						  $("#repeatError").html(obj.msg);
						}

					unloading();

				 }

			});

    //alert(repaetOption+"=="+terms+"=="+specificDate);
   });

   $(document).on("click","#crossRepeat",function(){
	  // alert('g');
	 $('#msgRepeat').addClass('display-n');
	 $('#msgRepeat').html('');
	 $("#repeatval").val('no');
	 $('#popup-toggle').removeClass('display-n');
   var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status); 
	  $.ajax({
				url: base_url+"booking/removeBooking_session",
				type: "POST",
				data:{type:'repeat'},
				success: function (data) {

				 }

			});

	  var repaetOption=$('[name="repeat"]:checked').val();
	 if(repaetOption==undefined || repaetOption==''){
		$("#repeatError").html('');
		$("#termsrepeatError").html('');
		$("#ShowslotesRepeat").addClass('display-n');
		}

	});

  // select single user on booking
   $(document).on('click',"#idCustumer"+userid,function(){
         
        $('#showkeyword').val($(this).data('val'));
        $('#search_keyword').addClass('display-n');
        $("#tempuid").val('');
        var email=$(this).data('email');
        var notes=$(this).data('notes');
        var mobile=$(this).data('mobile');
        var name=$(this).data('val');

                  var html='<div class="text-left p-3" style="box-shadow:0px 3px 25px rgba(215,232,233,.78);">\
                            <p class="mb-0 color333 font-size-16 fontfamily-medium">'+name+'</p>\
                            <p class="mb-0 color999 font-size-14 fontfamily-regular">'+mobile+'</p>\
                            <p class="mb-0 color999 font-size-14 fontfamily-regular">'+email+'</p>\
                            <p class="mb-0 color999 font-size-14 fontfamily-regular">'+notes+'</p>\
                            </div>\
                            <a  href="#" class="height56v vertical-middle colorcyan font-size-14 fontfamily-regular a_hover_cyan display-b p-3" id="removeCustomer"><?php echo $this->lang->line("Remove_Customer"); ?></a>';
                  $("#customePreview").html(html);
                  $("#aadNewCustomer")[0].reset();

      });



    });
    </script>
     <script type="text/javascript">
      $(window).click(function() {
          $('#search_keyword').addClass('display-n');
      });
  </script>
  </body>
</html>
<!-- <script type="text/javascript">
  $('.datepicker').datepicker({
           uiLibrary: 'bootstrap4',
           format:"dd-mm-yyyy",
          minDate:today
       });
</script> -->
