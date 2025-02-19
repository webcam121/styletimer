<div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
              <div class="bgwhite border-radius4 box-shadow1 pt-20 px-4 mb-30 pb-1 h-100">
                <div class="row">
                  <div class="col-12 col-sm-5 col-md-5 col-lg-5 col-xl-6">
                    <h6 class="font-size-16 color333 fontfamily-semibold mb-15"><p class="mb-2"><?php echo $this->lang->line('Select_Date_and_Time'); ?></p> <span class="color999 font-size-14 fontfamily-regular"><?php echo $this->lang->line('Select_service_first'); ?></span></h6>
                  </div>
                  <div class="col-12 col-sm-7 col-md-7 col-lg-7 col-xl-6 text-right pl-0">
                    <div class="relative">

            <?php $repeatMsgClass="";
                  $repetMessage="";

              if(empty($_SESSION['book_session']['repeatval']) || (!empty($_SESSION['book_session']['repeatval']) && $_SESSION['book_session']['repeatval']=='no')) $repeatMsgClass="display-n";
              else{ if(!empty($_SESSION['book_session']['repeatText'])) $repetMessage=$_SESSION['book_session']['repeatText']; }

               ?>
                    <a class="font-size-14 colororange fontfamily-medium a_hover_orange mb-20 display-ib <?php echo $repeatMsgClass; ?>" id="msgRepeat">
                     <?php echo $repetMessage; ?>
                     <picture  style="cursor:pointer;" id="crossRepeat" class="widthv15 ml-1">
                      <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp"  style="cursor:pointer;" id="crossRepeat" class="widthv15">
                      <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"  style="cursor:pointer;" id="crossRepeat">
                      <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"  style="cursor:pointer;" id="crossRepeat" class="widthv15">
                    </picture>
                    </a>

                        <input type="hidden" name="repeatText" value='' id="repeatText">
            <input type="hidden" name="repeatval" value='no' id="repeatval">
                        <a href="#" class="font-size-14 colororange fontfamily-medium a_hover_orange display-ib <?php if(empty($cart_detail->service)){ echo 'novalue '; } if($repeatMsgClass!='display-n') echo 'display-n'; ?>" id="popup-toggle"><img src="<?php echo base_url('assets/frontend/images/refresh.png') ?>" class="widthv15"> Repeat</a>
                         <span class="color666 font-size-12 fontfamily-regular display-b display-n" id="repeatServiceMsg">Add Services</span>
                        <!-- display-none -->
                        <div class="refresh-box-v" style="display: none;">
                          <h3 class="color333 fontfamily-medium font-size-16 text-left mb-20"><?php echo $this->lang->line('Select_Frequency'); ?></h3>
                          <div class="row">
                <div class="col-12 col-sm-12">

                          <div class="form-froup mb-30" style="top:35px!important;">
                            <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new">
                              <span class="label "><?php echo $this->lang->line('Frequency'); ?></span>
                              <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="cat_btn" aria-expanded="false"></button>
                                <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start">
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
                                <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start" id="slotesFroReapetTerms">
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
                                <input type="text" style="background-color:#fff;" name="reapetdate" placeholder="Select Date" class="height56v form-control" id="repeatdatepicker" readonly>
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
                        <input type="text" style="background-color:#fff;" name="date" value="<?php if(!empty($_GET['date'])){ echo date('d-m-Y',strtotime($_GET['date'])); } else if(!empty($_SESSION['book_session']['date'])){ echo $_SESSION['book_session']['date']; } ?>" placeholder="Select Date" class="height56v form-control" id="datepicker" autocomplete="off" readonly>
                        <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg') ?>" class="v_time_claender_icon_blue">
                    </div>
                  </div>
                  <div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-5 pr-0">
<!--
                    <div class="form-froup vmb-40 v_date_time_picker relative" id="time_validate">
                        <input type="text" style="background-color:#fff;" name="time" value="<?php //if(!empty($_GET['time'])) echo $_GET['time']; ?>"  data-autoclose="true" placeholder="Select Time" class="height56v form-control " id="" autocomplete="off" readonly>
                        <img src="<?php //echo base_url('assets/frontend/images/blue-clock.svg') ?>" class="v_time_claender_icon_blue">
                    </div>
-->
                     <div class="form-group ">
                      <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new">
                        <span class="label " id="timeSelected">Select Time</span>
                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn timeSelected height56v" data-use='1' id="cat_btn" aria-expanded="false"></button>
                          <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start" id="bookingTime">
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
                <h6 class="font-size-16 color333 fontfamily-semibold mb-25">Select Services</h6>

                <div class="backend">
                  <div class="row row_new_v relative d-flex">

          <?php if(!empty($main_category)){ ?>
            <!-- <div class="col-20"> -->
            <div class="select_v active" data-id='all'>
            <picture class="">
                <source srcset="<?php echo base_url('assets/frontend/images/box-5-fill-img1.webp') ?>" type="image/webp" class="">
                <source srcset="<?php echo base_url('assets/frontend/images/box-5-fill-img1.svg') ?>" type="image/svg" class="">
                <img src="<?php echo base_url('assets/frontend/images/box-5-fill-img1.svg') ?>" class="">
              </picture>
              <span class="color333 fontfamily-medium font-size-12 display-b span_text_block lineheight24 mt-2">Show All</span>
             </div>
            <!-- </div>
            <div class="col-80"> -->
             <!-- <div class="relative d-flex" id=""> -->
                <?php foreach($main_category as $cat)
                    {  if($cat->icon !='')
                    $cat_img= base_url('assets/uploads/category_icon/').$cat->mainid.'/'.$cat->icon;
                    else
                    $cat_img=base_url('assets/frontend/images/noimage.png'); ?>
                   <div class="select_v" data-id="<?php echo $cat->mainid; ?>">
                     <img src="<?php echo $cat_img; ?>">
                     <span class="color333 fontfamily-medium font-size-12 display-b span_text_block lineheight24 mt-2"><?php echo $cat->category_name; ?></span>
                   </div>
                 <?php } ?> 
              <!--  </div> -->
            <!-- </div> -->
          <?php }  else{ ?>
                 <div class="text-center" style="margin: 0 auto;">
            <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>" class="no_listing_img"><p style="margin-top: 20px;">There haven't been any services added yet</p></div>
                <?php } ?>


               </div>
              </div>
                  <div class="v_overflow_scroll custom_scroll" id="allBookingService">

                  </div>

                  <div class="booknow_new_bottom_line_v mt-2" <?php if(empty($cart_detail->service)){ ?>style="display: none;" <?php } ?> id="my_cart_div">
                    <div class="d-flex bookingSelect align-items-center">
                      <div class="deatail-box-left">
                      <p class="colorwhite font-size-16 fontfamily-medium mb-0 overflow_elips" id="service_count"><?php if(!empty($cart_detail->service)) echo $cart_detail->service;  if($cart_detail->service>1) echo ' Services'; else echo ' Service'; ?> Added</p>
                      <span class="font-size-14 color999 fontfamily-regular"><span id="totalDuration"><?php if(!empty($cart_detail->duration1)) echo $cart_detail->duration1; ?></span> Minutes</span>
                      </div>
                      <div class="deatail-box-right d-inline-flex align-items-center">
                      <div class="relative text-right width200">
                        <p class=" colorcyan font-size-18 fontfamily-semibold mb-0"><span id="total_amt"><?php if(!empty($cart_detail->tot_price)) echo $cart_detail->tot_price; ?></span> €</p>
                      </div>
                      <button class="btn selectedBtn btn-small widthfit2 btn-ml-20 ">Book Now</button>
                      </div>
                    </div>
                  </div>
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-8 col-lg-4 col-xl-4">
              <div class="bgwhite border-radius4 box-shadow1 pt-20 px-3 py-3 v-after-before mb-30">
                <p class="color333 font-size-16 fontfamily-medium">Select Customer</p>
                <input type="hidden" name="uid" id="tempuid" value="<?php if(!empty($_SESSION['book_session']['uid'])) echo $_SESSION['book_session']['uid']; ?>">

                <div class="btn-group multi_sigle_select inp_select v-droup-new">
                  <div class="relative w-100">
                     <label class="inp-w w-100">
                       <input type="text" placeholder="Search Customer" class="form-control height56v dropdown-toggle height56v mss_sl_btn" id="showkeyword" data-toggle="dropdown" autocomplete="off">
                     </label>
                     <div class="bgwhite mss_sl_btn_dm scroll300 custom_scroll display-n" id="search_keyword">
                        <ul class="pl-0 scroll300 custom_scroll" style="height:272px;overflow: scroll;" id="bookingAllCustumers">

                        </ul>

                     </div>
                     <label class="error" id="customer_err"></label>
                  </div>
              </div>


                    <div class="relative around-20 text-center" id="customePreview">
                      <img src="<?php echo base_url('assets/frontend/images/orange-noteped-large.png') ?>">
                      <p class="font-size-12 color999 fontfamily-regular mt-3">Use the search to add a client, or keep empty to save as walk-in.</p>
                      <a href="#" class="height56v vertical-middle colorcyan font-size-14 fontfamily-regular a_hover_cyan display-b p-3"  data-toggle="modal" data-target="#popup-v12"><?php echo $this->lang->line('Add_Customer'); ?></a>
                    </div>
              </div>

              <div class="bgwhite border-radius4 box-shadow1 pt-20 px-3 py-3 pb-3 mb-30">
                <p class="color333 font-size-16 fontfamily-medium">Employee</p>
                    <div class="form-group">
                      <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new bookingAllEmployee" id="">
                        <span class="label" id="employeSelected">Mitarbeiter wählen</span>
                        <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn employeSelected" id="cat_btn" aria-expanded="false"></button>
                          <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start" id="bookingAllEmployee">

                          </ul>

                       </div>
                        <label class="error" id="employee_err">Add service first before selecting employee</label>
                   </div>
              </div>

              <div class="bgwhite border-radius4 box-shadow1 pt-20 px-3 py-3 pb-3">
                <p class="color333 font-size-16 fontfamily-medium mt-2">Additional Note</p>
                  <div class="form-group vmb-40" id="">
                    <label class="inp v_inp_new height90v">
                      <textarea class="form-control height90v custom_scroll" placeholder="&nbsp;" value="" name="additionalNotes"><?php if(!empty($_SESSION['book_session']['additionalNotes'])) echo $_SESSION['book_session']['additionalNotes']; ?></textarea>
                      <span class="label">Enter Note</span>
                    </label>
                 </div>
              </div>
            </div>

        </div>
        </div>