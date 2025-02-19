<div class="modal-body p-0 pl-3 pr-3"> 
  <div class="relative mb-3 mt-10">
    <div class="row">
        <div class="col-12 text-right">
          <a href="#" class="crose-btn font-size-30 color333 a_hover_333" data-dismiss="modal" id="conf_close">
            <picture class="popup-crose-black-icon" style="width: 22px; height: 22px;">
              <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon" style="width: 22px; height: 22px;">
            </picture>
          </a>
        </div>
                     
              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <h3 class="font-size-20 fontfamily-semibold color333 mb-3"><?php echo $this->lang->line('Contact_Detail'); ?></h3>
                <div class="bgwhite border-radius4 box-shadow1 small-mb-30" style="padding: 16px;"> <!-- p-3 -->
                  <div class="row">
                    <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                      <div class="relative p-1 text-center mb-30">
                                      
                       <?php if(!empty($userdata->profile_pic)){ ?> 
                        <img src="<?php echo base_url('assets/uploads/banners/'.$userdata->id.'/prof_'.$userdata->profile_pic); ?>" class="round-new-v80 ">
                        <?php }else{ ?>
                        <img src="<?php echo base_url('assets/frontend/images/user-icon-gret.svg'); ?>" class="round-new-v80 ">
                        <?php } ?>

                      </div>
                    </div>
                    <div class="col-12 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                      <div class="relative">
                         <div class="display-ib"> 
                          <h5 class="font-size-16 colorcyan fontfamily-medium display-ib mt-1"><?php echo ucfirst($userdata->business_name); ?> </h5>
                          <!-- <span class="font-size-12 color333 fontfamily-regular"> (Male)</span> -->
                            
                         </div>   
                        <div class="display-ib float-right mb-3 relative" style="z-index:999;clear:both;">
                          <div class="dropdown">
                              <button class="btn btn-new widthfit dropdown-toggle" type="button" id="dropdownMenuButton11" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" style="line-height: 34px;">
                              <?php echo $this->lang->line('More_Option'); ?>
                              </button>
                              <div class="dropdown-menu show" aria-labelledby="dropdownMenuButton11" x-placement="bottom-start" style="">
                                <?php if($userdata->status =='active'){
                                          $st = 'Inactive';
                                          $stt = 'inactive';
                                        }
                                      else{
                                          $st = 'Active';
                                          $stt = 'active';
                                        }
                                  ?>

                                <a data-status="<?php echo $stt; ?>" class="dropdown-item" href="#" onClick="changeSalonstatus(<?php echo $userdata->id; ?>);" id="change_salon_status" data-uid="<?php echo $userdata->id; ?>"><?php echo $st; ?></a>
                                <input type="hidden" id="salon_status_change" value="<?php echo $stt; ?>" name="">

                                  <a href="<?php echo base_url('backend/user/login_tosalon/').$userdata->id; ?>" class="dropdown-item" style="" data-uid="<?php echo $userdata->status; ?>">Login</a>
                              </div>
                            </div>                            
                          </div>
                        <div class="relative mt-2">
                          <p class="font-size-14 color666 fontfamily-regular mt-2 overflow_elips" style="width: 385px;">
                            <img class="width24v mr-2" src="<?php echo base_url('assets/frontend/images/birthday.svg'); ?>"> 
                            <span class="color333 fontfamily-medium'"><?php echo date('d-m-Y',strtotime($userdata->created_on)); ?></span>
                          </p>
                          <p class="mt-2 overflow_elips" style="width: 385px;">
                            <img src="<?php echo base_url('assets/frontend/images/orange-envlop24.svg'); ?>" class="width24v mr-2">
                            <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->email ?></span>
                          </p>
                          <p class="mt-2 overflow_elips" style="width: 385px;">
                            <img src="<?php echo base_url('assets/frontend/images/orange-call24.svg'); ?>" class="width24v mr-2">
                            <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->mobile; ?></span>
                          </p>
                          <p class="mt-2 overflow_elips " style="width: 385px;">
                            <img src="<?php echo base_url('assets/frontend/images/orange-location24.svg'); ?>" class="width24v mr-2">
                            <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->address ?></span><br>
                            <span class="font-size-14 color333 fontfamily-regular" style="margin-left: 28px"><?php echo $userdata->zip." ".$userdata->city; ?></span>
                          </p>
                          <input type="hidden" id="salon_idds" value="<?php echo $userdata->id; ?>" name="">
                        </div>
                      </div>
                    </div>
                    <input type="hidden" id="editnotes_ppp" name="" value="">
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <h3 class="font-size-20 fontfamily-semibold color333 mb-3"><?php echo $this->lang->line('Bookings'); ?></h3>
                <div class="row h-85">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
                      <div class="bgwhite border-radius4 box-shadow1 h-100 px-3">
                        <select id="" class="searchNewFilter">                         
                          <option value="all" selected="selected">Over All</option>
                          <option value="day">Today</option>
                          <option value="current_week">Current Week</option>
                          <option value="current_month">Current Month</option>
                          <option value="last_month">Last Month</option>
                          <option value="last_sixmonth">Last 6 Months</option>
                          <option value="current_year">Current Year</option>
                          <option value="last_year">Last Year</option>
                        </select>
                        <div class="row h-100">
                          <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 border-r border-b">
                            <div class="relative text-center">
                            <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="tot_revenue"><?php if(!empty($userdata->totalrevenew)) echo round($userdata->totalrevenew,2); else echo '0'; ?> €</h3>
                              <span class="font-size-14 colorgreen1 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Revenue'); ?></span>
                            </div>
                          </div>
                          <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 border-b">
                            <div class="relative text-center">
                            <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="tot_book"><?php if(!empty($userdata->totalbook)) echo $userdata->totalbook; else echo '0'; ?></h3>
                              <span class="font-size-14 color333 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Total_Booking'); ?></span>
                            </div>
                          </div>
                          <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                            <div class="relative text-center">
                              <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="tot_comp"><?php if(!empty($userdata->totalcomplete)) echo $userdata->totalcomplete; else echo '0'; ?></h3>
                              <span class="font-size-14 colorsuccess fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Completed'); ?></span>
                            </div>
                          </div>
                          <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                            <div class="relative text-center">
                              <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="tot_upcom"><?php if(!empty($userdata->totalupcoming)) echo $userdata->totalupcoming; else echo '0'; ?></h3>
                              <span class="font-size-14 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Upcoming'); ?></span>
                            </div>
                          </div>
                          <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                            <div class="relative text-center">
                              <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="tot_canc"><?php if(!empty($userdata->totalcanceled)) echo $userdata->totalcanceled; else echo '0'; ?></h3>
                              <span class="font-size-14 colorred fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Cancelled'); ?></span>
                            </div>
                          </div>
                          <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                            <div class="relative text-center">
                              <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="tot_noshow"><?php if(!empty($userdata->totalnoshow)) echo $userdata->totalnoshow; else echo '0'; ?></h3>
                              <span class="font-size-14 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('No_show'); ?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </div> 
              </div>                
            </div>
          </div>

          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <h3 class="font-size-20 fontfamily-semibold color333 mt-3" id="appoint_header">Appointment list</h3>

              <div class="bgwhite border-radius4 box-shadow1 mb-3 mt-3">
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                  <div class="pt-20 pb-20 pl-30 pr-20 relative d-flex appoin-listing-filter">
                      <div class="relative my-new-drop-v display-ib">
                      <!--  <span class="color999 fontfamily-medium font-size-14">Status</span> -->
                        <select id="over_all_filter_c" class="on_change_short_c">
                              <option value="">Search By Date</option>
                              <option value="all" selected="selected">Over All</option>
                              <option value="day">Today</option>
                              <option value="current_week">Current Week</option>
                              <option value="current_month">Current Month</option>
                              <option value="last_month">Last Month</option>
                              <option value="last_sixmonth">Last 6 Months</option>
                              <option value="current_year">Current Year</option>
                              <option value="last_year">Last Year</option>
                            </select>
                      
                          <div class="col-md-6">
                            <div id="date_section_c" style="display: none;">
                              <div class="col-sm-5">
                                  <!-- <p class="f-500 m-b-15 c-black">Start Date</p> -->
                                  <div class="input-group form-group">
                                      <span class="input-group-addon">
                                          <i class="zmdi zmdi-calendar"></i>
                                      </span>
                                      <div class="dtp-container fg-line">
                                          <input type="text" style="padding-left: 10px;" id="start_date_c" class="form-control date-picker" name="start_date_c" value="" placeholder="select start date">
                                      </div>
                                      <div style="position: absolute;" id="error_start_c" class="error"></div>
                                  </div>
                              </div>
                              <div class="col-sm-5">
                                    <!--  <p class="f-500 m-b-15 c-black">End Date</p> -->
                                <div class="input-group form-group">
                                  <span class="input-group-addon">
                                      <i class="zmdi zmdi-calendar"></i>
                                  </span>
                                  <div class="dtp-container fg-line">
                                      <input type="text" style="padding-left: 10px;" id="end_date_c" class="form-control date-picker" name="end_date" value="" placeholder="select end date">
                                  </div>
                                  <div style="position: absolute;" id="error_end_c" class="error"></div>
                                </div>
                              </div>
                              <div class="col-sm-2">
                                  <!-- <p class="f-500 c-black"></p> -->
                                  <button type="button" id="click_search_c" class="btn btn-info btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-search"></i></button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- filter code new start-->
                        <div class="display-ib hide filterdate"><i id="search_filter" class="fas fa-search colororange mt-2 ml-2 font-size-24"></i></div>
                      </div>
                      <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">BOOKING</a>
                      <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">CUSTOMER</a>
                  </div>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                      <div class="my-table my-table-v1">
                      <table class="table" id="listingTabl" data-uid="<?php echo url_encode($userdata->id); ?>">
                        <thead>
                          <tr>
                            <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting" id="booking_time" data-short="desc">DATE 
                              <div class="display-ib vertical-middle ml-1">
                                <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                                <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                              </div></a>
                              <input type="hidden" id="short_booking_time" value="desc">
                            </th>
                            <th class="text-center height56v">TIME
                            </th>
                            <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting" id="id" data-short="desc">BOOKING ID
                              <div class="display-ib vertical-middle ml-1">
                                <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                                <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                              </div></a>
                              <input type="hidden" id="short_id" value="desc">
                            </th>
                            <th class="text-center height56v" style="width:250px;">SERVICE </th>
                            <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting" id="total_minutes" data-short="desc">DURATION
                              <div class="display-ib vertical-middle ml-1">
                                <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                                <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                              </div></a>
                              <input type="hidden" id="short_total_minutes" value="desc">
                            </th>
                            <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting" id="total_price" data-short="desc">PRICE
                              <div class="display-ib vertical-middle ml-1">
                                <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                                <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                              </div></a>
                              <input type="hidden" id="short_total_price" value="desc">
                            </th>
                            <th class="text-center height56v">STATUS </th>
                            <th class="text-center height56v">ACTION</th>
                          </tr>
                        </thead>
                        <tbody id="all_listing"><tr>   
                            </tbody>
                      </table>
                      <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                          <ul class="pagination" style="display: inline-flex;" id="pagination_clientBooking">
                    
                          </ul>
                        </nav>
                      <!-- <ul class="pagination" style="padding-left:15px;">
                        <li class="first" aria-disabled="false">
                          <a href="#first" class="button">
                            <i class="zmdi zmdi-more-horiz"></i>
                          </a>
                        </li>
                        <li class="prev" aria-disabled="false">
                          <a href="#prev" class="button">
                            <i class="zmdi zmdi-chevron-left"></i>
                          </a>
                        </li>
                        <li class="page-1 disabled active" aria-disabled="false" aria-selected="false">
                          <a href="#1" class="button">1</a>
                        </li>
                        <li class="next" aria-disabled="false">
                          <a href="#next" class="button">
                            <i class="zmdi zmdi-chevron-right"></i>
                        </a>
                      </li>
                        <li class="last" aria-disabled="false">
                          <a href="#last" class="button"><i class="zmdi zmdi-more-horiz"></i></a>
                        </li>
                      </ul> -->
                        <input type="hidden" id="short_by_field" value="">
                    </div>
                    </div>
                    <div class="tab-pane" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                       <table class="table">
                  <thead>
                    <tr>
                      <?php if(!empty($_GET['tempid'])){ ?>
                      <th class="text-center pl-3 height56v">
                        <div class="checkbox mt-0 mb-0">
                            <label class="fontsize-12 fontfamily-regular color333">
                              <input type="checkbox" name="remember" class="allcheck" id="select_all" value="">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                            </label>
                        </div> 
                      </th>
                      <?php } ?>
                       <th class="pl-5 height56v"><a href="javascript:void(0)" class="color333 shorting_c" data-short="asc" id="st_users.first_name">CUSTOMER 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_c" data-short="asc" id="st_users.email">EMAIL 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_c" data-short="asc" id="st_users.mobile">CONTACT 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_c" data-short="asc" id="st_users.gender">GENDER 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_c" data-short="asc" id="bookcount"><span class="tablete-none">NO. OF </span>BOOKINGS 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_c" data-short="asc" id="lastbook">LAST VISITED
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center height56v">NOTES </th>
                    </tr>
                  </thead>
                  <tbody id="allcust_listing">
                  
                  </tbody>
                </table>
                  <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;" id="pagination">
               
                    </ul>
                  </nav>
                </div>
                <input type="hidden" id="shortby_cust" value="asc">
                <input type="hidden" id="orderby_cust" value="st_booking.id">
                    </div>
            </div>

              
            </div>

           <!-- dashboard right side end -->       
           </div>
         </div> 
      </div>
                      </div>

      <div id="loading_div">
      <picture class="process_loading hide">
            <img src="<?php echo base_url('assets/frontend/images/loader.gif'); ?>" class="process_loading hide">
          </picture>
    </div>    

