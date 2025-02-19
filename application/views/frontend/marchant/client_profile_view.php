<?php $this->load->view('frontend/common/header'); ?>
<div class="d-flex pt-84">
  <?php $this->load->view('frontend/common/sidebar'); ?>

  <div class="right-side-dashbord right-side-dashbord-v1 w-100 pl-30 pr-30">
    <div class="relative mb-3 mt-10">
      <div class="row">

        <?php 
               $access= $this->session->userdata('access');
               if($access =='marchant')
                  $link='merchant/customers';
                else
                  $link='';
                
              ?>
        <div class="col-12 text-right">
          <a href="<?php if(!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; else echo base_url($link); ?>"
            class="display-ib" id="" data-id="">
            <picture class="" style="width:24px;position: absolute;right:20px; top:0px">
              <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
                type="image/webp" class="" style="width:24px;">
              <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
                type="image/png" class="" style="width:24px;">
              <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class=""
                style="width:24px;">
            </picture>
          </a>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
          <h3 class="font-size-20 fontfamily-semibold color333 mb-3"><?php echo $this->lang->line('Contact_Detail'); ?>
          </h3>
          <div class="bgwhite border-radius4 box-shadow1 small-mb-30" style="padding: 16px;">
            <!-- p-3 -->
            <div class="row">
              <div class="col-12 col-sm-3 col-md-3 col-lg-12 col-xl-3">
                <div class="relative p-1 text-center mb-30">
                  <?php if(!empty($userdata->profile_pic)){ ?>
                  <img
                    src="<?php echo base_url('assets/uploads/users/'.$userdata->id.'/icon_'.$userdata->profile_pic); ?>"
                    class="round-new-v80 ">
                  <?php }else{ ?>
                  <img src="<?php echo base_url('assets/frontend/images/user-icon-gret.svg'); ?>"
                    class="round-new-v80 ">
                  <?php } ?>
                </div>
              </div>
              <div class="col-12 col-sm-9 col-md-9 col-lg-12 col-xl-9 pl-0">
                <div class="relative">
                  <div class="display-ib">
                    <h5 class="font-size-16 colorcyan fontfamily-medium display-ib mt-1">
                      <?php echo ucfirst($userdata->first_name)." ".ucfirst($userdata->last_name); ?> </h5>
                    <span class="font-size-12 color333 fontfamily-regular">
                      (<?php echo ucfirst($userdata->gender); ?>)</span>
                    <p class="font-size-14 color666 fontfamily-regular mb-0"><img class="width24v mr-2"
                        src="<?php echo base_url('assets/frontend/images/birthday.png'); ?>"><span
                        class="color333 fontfamily-medium'"><?php if(!empty($userdata->dob) && $userdata->dob !="0000-00-00") echo date('d-m-Y',strtotime($userdata->dob)); else echo 'NA'; ?></span>
                    </p>
                  </div>
                  <div class="display-ib float-right mb-3" style="">
                    <div class="dropdown">
                      <button class="btn btn-new widthfit dropdown-toggle" type="button" id="dropdownMenuButton11"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="line-height: 34px;">
                        <?php echo $this->lang->line('More_Option'); ?>
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton11">
                        <?php if(!empty($blockclient->id)){ ?>
                        <a class="dropdown-item blockClient"
                          data-uid="<?php echo url_encode($userdata->id); ?>"><?php echo $this->lang->line('Freigeben'); ?></a>
                        <?php }else{ ?>
                        <a class="dropdown-item blockClient"
                          data-uid="<?php echo url_encode($userdata->id); ?>"><?php echo $this->lang->line('Blockieren'); ?></a>
                        <?php } ?>
                        <a href="<?php echo base_url('booking/new_booking').'?userid='.url_encode($userdata->id); ?>"
                          class="dropdown-item" style=""
                          data-uid="<?php echo url_encode($userdata->id); ?>"><?php echo $this->lang->line('Book'); ?></a>
                      </div>
                    </div>

                  </div>
                  <div class="relative mt-2">
                    <p class="mt-2 overflow_elips" style="width: 385px;">
                      <img src="<?php echo base_url('assets/frontend/images/orange-envlop24.png'); ?>"
                        class="width24v mr-2">
                      <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->email; ?></span>
                    </p>
                    <p class="mt-2 overflow_elips" style="width: 385px;">
                      <img src="<?php echo base_url('assets/frontend/images/orange-call24.png'); ?>"
                        class="width24v mr-2">
                      <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->mobile; ?></span>
                    </p>
                    <p class="mt-2 overflow_elips " style="width: 385px;">
                      <img src="<?php echo base_url('assets/frontend/images/orange-location24.png'); ?>"
                        class="width24v mr-2">
                      <span
                        class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->address ?></span></br>
                      <span class="font-size-14 color333 fontfamily-regular"
                        style="margin-left: 35px"><?php echo $userdata->zip." ".$userdata->city.", ".$userdata->country; ?></span>
                    </p>
                    <p class="mt-2 overflow_elips mb-1 cursor-p editNoteClient" style="width: 385px;" id="notePara"
                      data-toggle="modal" data-target="#add-note">
                      <img src="<?php echo base_url('assets/frontend/images/orange-noteped24.png'); ?>"
                        id="editNoteClient" class="width24v mr-2" style="cursor: pointer;">
                      <span class="font-size-14 color333 fontfamily-regular" id="getClientNote"
                        title="<?php if(!empty($userdata->notes)){ echo $userdata->notes; } ?>"><?php if(!empty($userdata->notes)){ if(strlen($userdata->notes)>100) echo substr($userdata->notes,0,100)."...."; else echo $userdata->notes; } else echo '<a class="font-size-14 text-underline a_hover_orange cursor-p" style="color: #FF9944;" >'.$this->lang->line('add_note_customer').'</a>'; ?></span>
                    </p>
                    <!--   <div class="display-n" id="textClientHS">
							<div class="form-group vmb-40" id="">
								<label class="inp v_inp_new height90v">
								  <textarea class="form-control h-100 custom_scroll" data-uid="<?php echo $userdata->id; ?>" id="clientNotevalue" placeholder="&nbsp;" value=""></textarea>
								  <span class="label label_add_top">Enter Note</span>
								</label>
							 </div>
							<button type="button" id="savenotesBtn" class="btn widthfit">Save</button>
                <button type="button" id="closenoteBtn" class="btn widthfit">Close</button>
                          </div> -->

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
          <h3 class="font-size-20 fontfamily-semibold color333 mb-3"><?php echo $this->lang->line('Bookings'); ?></h3>
          <div class="row h-85">

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
              <div class="bgwhite border-radius4 box-shadow1 h-100 px-3">
                <div class="row h-100">
                  <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 border-r border-b">
                    <div class="relative text-center">
                      <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                        <?php if(!empty($userdata->totalrevenew)) echo round($userdata->totalrevenew,2); else echo '0'; ?>
                        €</h3>
                      <span
                        class="font-size-14 colorgreen1 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Revenue'); ?></span>
                    </div>
                  </div>
                  <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 border-b">
                    <div class="relative text-center">
                      <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                        <?php if(!empty($userdata->totalbook)) echo $userdata->totalbook; else echo '0'; ?></h3>
                      <span
                        class="font-size-14 color333 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Total_Booking'); ?></span>
                    </div>
                  </div>
                  <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="relative text-center">
                      <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                        <?php if(!empty($userdata->totalcomplete)) echo $userdata->totalcomplete; else echo '0'; ?></h3>
                      <span
                        class="font-size-14 colorsuccess fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Completed'); ?></span>
                    </div>
                  </div>
                  <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="relative text-center">
                      <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                        <?php if(!empty($userdata->totalupcoming)) echo $userdata->totalupcoming; else echo '0'; ?></h3>
                      <span
                        class="font-size-14 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Upcoming'); ?></span>
                    </div>
                  </div>
                  <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="relative text-center">
                      <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                        <?php if(!empty($userdata->totalcanceled)) echo $userdata->totalcanceled; else echo '0'; ?></h3>
                      <span
                        class="font-size-14 colorred fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Cancelled'); ?></span>
                    </div>
                  </div>
                  <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="relative text-center">
                      <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                        <?php if(!empty($userdata->totalnoshow)) echo $userdata->totalnoshow; else echo '0'; ?></h3>
                      <span
                        class="font-size-14 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('No_show'); ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="col-6 col-sm-4 col-md-4 col-lg-6 col-xl-4 pl-0">
                    <div class="bgwhite border-radius4 box-shadow1 around-15 mb-3">
                      <div class="relative">
                        <span class="font-size-14 color333 fontfamily-regular mt-1 display-ib">All</span>
                        <img src="<?php //echo base_url('assets/frontend/images/right24v.png'); ?>" class="width24v float-right">
                        <h3 class="font-size-24 colordblue fontfamily-bold mt-20 mb-0"><?php //if(!empty($userdata->totalbook)) echo $userdata->totalbook; //else echo '0'; ?></h3>
                      </div>
                    </div>
                  </div> -->
          <?php /* <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pl-0">
                    <div class="bgwhite border-radius4 box-shadow1 around-15 mb-3">
                      <div class="relative">
                        <span class="font-size-14 colorgreen1 fontfamily-regular mt-1 display-ib">Revenue</span>
                        <img src="<?php echo base_url('assets/frontend/images/dolor24v.svg'); ?>" class="width30v
          float-right">
          <h3 class="font-size-24 colordblue fontfamily-bold mt-20 mb-0">
            <?php if(!empty($userdata->totalrevenew)) echo round($userdata->totalrevenew,2); else echo '0'; ?> €</h3>
        </div>
      </div>
    </div>

    <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pl-0">
      <div class="bgwhite border-radius4 box-shadow1 around-15 mb-3">
        <div class="relative">
          <span class="font-size-14 colorsuccess fontfamily-regular mt-1 display-ib">Completed</span>
          <img src="<?php echo base_url('assets/frontend/images/rightround24v.svg'); ?>" class="width30v float-right">
          <h3 class="font-size-24 colordblue fontfamily-bold mt-20 mb-0">
            <?php if(!empty($userdata->totalcomplete)) echo $userdata->totalcomplete; else echo '0'; ?></h3>
        </div>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pl-0">
      <div class="bgwhite border-radius4 box-shadow1 around-15 mb-3">
        <div class="relative">
          <span class="font-size-14 fontfamily-regular mt-1 display-ib">No Show</span>
          <img src="<?php echo base_url('assets/frontend/images/errorround24v.svg'); ?>" class="width30v float-right">
          <h3 class="font-size-24 colordblue fontfamily-bold mt-20 mb-0">
            <?php if(!empty($userdata->totalnoshow)) echo $userdata->totalnoshow; else echo '0'; ?></h3>
        </div>
      </div>
    </div>
    <!-- <div class="col-6 col-sm-4 col-md-4 col-lg-6 col-xl-4 pl-0">
                    <div class="bgwhite border-radius4 box-shadow1 around-15 mb-3">
                      <div class="relative">
                        <span class="font-size-14 colororange fontfamily-regular mt-1 display-ib">Upcoming</span>
                        <img src="<?php //echo base_url('assets/frontend/images/right-calender24v.png'); ?>" class="width24v float-right">
                        <h3 class="font-size-24 colordblue fontfamily-bold mt-20 mb-0"><?php //if(!empty($userdata->totalupcoming)) echo $userdata->totalupcoming; else echo '0'; ?></h3>
                      </div>
                    </div>
                  </div> -->
    <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pl-0">
      <div class="bgwhite border-radius4 box-shadow1 around-15 mb-3">
        <div class="relative">
          <span class="font-size-14 colorred fontfamily-regular mt-1 display-ib">Cancelled</span>
          <img src="<?php echo base_url('assets/frontend/images/roundcrose24v.svg'); ?>" class="width30v float-right">
          <h3 class="font-size-24 colordblue fontfamily-bold mt-20 mb-0">
            <?php if(!empty($userdata->totalcanceled)) echo $userdata->totalcanceled; else echo '0'; ?></h3>
        </div>
      </div>
    </div> */ ?>

  </div>

</div>
</div>

<div class="row">
  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <h3 class="font-size-20 fontfamily-semibold color333 mt-3" id="appoint_header">Appointment list</h3>
    <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-3">
      <div class="pt-20 pb-20 pl-30 pr-20 relative d-flex appoin-listing-filter">
        <div class="relative my-new-drop-v display-ib">
          <!--  <span class="color999 fontfamily-medium font-size-14">Status</span> -->
          <div class="btn-group multi_sigle_select widthfit80 mr-20 display-ib">
            <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn ">All
              Bookings</button> <!-- Select Status -->
            <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
              <li class="radiobox-image">
                <input type="radio" id="id_44" checked="checked" name="booking_st" class="book_status" value="all">
                <label for="id_44">All Bookings</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_45" name="booking_st" class="book_status" value="upcoming">
                <label for="id_45">Upcoming Bookings</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_46" name="booking_st" class="book_status" value="recent">
                <label for="id_46">Recent Bookings</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_47" name="booking_st" class="book_status" value="cancelled">
                <label for="id_47">Cancelled Bookings</label>
              </li>
            </ul>
          </div>
        </div>
        <!-- filter code new start-->
        <div class="relative my-new-drop-v display-ib">
          <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
          <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib">
            <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn"
              id="btn_text">Select Filter</button>
            <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
              <li class="radiobox-image">
                <input type="radio" id="id_34" name="filter" class="filterby_days" value="day">
                <label for="id_34">Today</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_35" name="filter" class="filterby_days" value="current_week">
                <label for="id_35">Current week</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_36" name="filter" class="filterby_days" value="current_month">
                <label for="id_36">Current month</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_37" name="filter" class="filterby_days" value="current_year">
                <label for="id_37">Current year</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_036" name="filter" class="filterby_days" value="last_month">
                <label for="id_036">Last month</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_037" name="filter" class="filterby_days" value="last_year">
                <label for="id_037">Last year</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_38" name="filter" class="filterby_days" value="date">
                <label for="id_38">Search by date</label>
              </li>
            </ul>
          </div>
        </div>

        <div class="form-group date_pecker display-ib mr-20  hide filterdate" style="max-width: 140px;">
          <label class="inp">
            <input type="text" id="start_date" name="" placeholder="Start Date" value="" class="form-control">
          </label>
          <span class="error" id="start_error"></span>
        </div>

        <div class="form-group date_pecker display-ib hide filterdate" style="max-width: 140px;">
          <label class="inp">
            <input type="text" id="end_date" name="" placeholder="End Date" value="" class="form-control">
          </label>
          <span class="error" id="end_error"></span>
        </div>
        <div class="display-ib hide filterdate"><i id="search_filter"
            class="fas fa-search colororange mt-2 ml-2 font-size-24"></i></div>


        <!-- filter code new end-->
        <div class="relative my-new-drop-v display-ib ml-auto mr-2">
          <span class="color999 fontfamily-medium font-size-14">Show</span>
          <div class="btn-group multi_sigle_select widthfit2 mr-20 ml-20 open">
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn">20</button>
            <ul class="dropdown-menu mss_sl_btn_dm widthfit2">
              <li class="radiobox-image">
                <input type="radio" id="id_14" name="limit" class="change_limit" value="10">
                <label for="id_14">10</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_15" name="limit" class="change_limit" value="20" checked>
                <label for="id_15">20</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_16" name="limit" class="change_limit" value="30">
                <label for="id_16">30</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_16all" name="limit" class="change_limit" value="all">
                <label for="id_16all">All</label>
              </li>
            </ul>
          </div>
          <span class="color999 fontfamily-medium font-size-14">Entries</span>

        </div>
        <a href="javascript:void(0);" data-id="csv" class="display-ib cursol-p m-1 export_filterreport"><img
            class="width30" src="<?php echo base_url('assets/frontend/images/csv-icon.svg'); ?>"></a>
        <a href="javascript:void(0)" data-id="excel" class="display-ib cursol-p m-1 export_filterreport"><img
            class="width30" src="<?php echo base_url('assets/frontend/images/exel-icon.svg'); ?>"></a>
      </div>
      <?php /* <div class="pt-20 pb-20 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
                <div class="relative display-ib">
                  <span class="color999 fontfamily-medium font-size-14">Show</span>
                    <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-20 open"> 
                      <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn ">20</button>
                      <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
                        <li class="radiobox-image">
                          <input type="radio" id="id_14" name="limit" class="change_limit" value="10">
                          <label for="id_14">10</label>
                        </li>
                        <li class="radiobox-image">
                          <input type="radio" id="id_15" name="limit" class="change_limit" value="20" checked>
                          <label for="id_15">20</label>
                        </li>
                        <li class="radiobox-image">
                          <input type="radio" id="id_16" name="limit" class="change_limit" value="30">
                          <label for="id_16">30</label>
                        </li>
                      </ul>
                  </div>
                  <span class="color999 fontfamily-medium font-size-14">Entries</span>                
                </div>                
              </div> */ ?>

      <div class="my-table my-table-v1">
        <table class="table" id="listingTabl"
          data-uid='<?php if(!empty($this->uri->segment(3))) echo $this->uri->segment(3); ?>'>
          <thead>
            <tr>
              <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting"
                  id="booking_time">DATE
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b "
                      style="width:8px;">
                    <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b "
                      style="width:8px;">
                  </div>
                </a>
                <input type="hidden" id="short_booking_time" value="desc">
              </th>
              <th class="text-center height56v">TIME
              </th>
              <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting" id="id">BOOKING
                  ID
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b "
                      style="width:8px;">
                    <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b "
                      style="width:8px;">
                  </div>
                </a>
                <input type="hidden" id="short_id" value="desc">
              </th>
              <th class="text-center height56v" style="width:250px;">SERVICE </th>
              <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting"
                  id="total_minutes">DURATION
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b "
                      style="width:8px;">
                    <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b "
                      style="width:8px;">
                  </div>
                </a>
                <input type="hidden" id="short_total_minutes" value="desc">
              </th>
              <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting"
                  id="total_price">PRICE
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b "
                      style="width:8px;">
                    <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b "
                      style="width:8px;">
                  </div>
                </a>
                <input type="hidden" id="short_total_price" value="desc">
              </th>
              <th class="text-center height56v">STATUS </th>
              <th class="text-center height56v">RECEIPT</th>
            </tr>
          </thead>
          <tbody id="all_listing">

          </tbody>
        </table>
        <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
          <ul class="pagination" style="display: inline-flex;" id="pagination">

          </ul>
        </nav>
        <input type="hidden" id="short_by_field" value="">
      </div>
    </div>

    <!-- dashboard right side end -->
  </div>
</div>
</div>




<!-- modal start -->
<div class="modal fade pr-0" id="popup-v11">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
            type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"
            class="popup-crose-black-icon">
          <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
            class="popup-crose-black-icon">
        </picture>
      </a>

      <span id="click_to_print_old" class="cursor-p fontfamily-medium colororange font-size-14 change_anchor"
        style="display: inline-block;position: absolute;right: 25px;top: 25px;margin-top: 15px;z-index: 999;"><img
          src="<?php echo base_url('assets/frontend/images/printer.svg'); ?>" style="width:24px;"></span>
      <div id="print_div_receipt" class="modal-body pt-40 mb-10 pl-25 pr-25 relative">
        <h3 id="print_title" class="text-center color333 fontfamily-medium hide" style="font-size: 0.875rem;">Booking
          Receipt</h3>

        <div class="relative d-flex">
          <div class="display-ib mr-20">
            <?php if(!empty($userdata->profile_pic)){ ?>
            <img src="<?php echo base_url('assets/uploads/users/'.$userdata->id.'/'.$userdata->profile_pic); ?>"
              class="round-new-v40" style="width: 40px;height: 40px;border-radius: 50%;">
            <?php }else{ ?>
            <img src="<?php echo base_url('assets/frontend/images/user-icon-gret.svg'); ?>" class="round-new-v40"
              style="width: 40px;height: 40px;border-radius: 50%;">
            <?php } ?>

          </div>
          <div class="display-ib">
            <p class="fontfamily-medium colorcyan font-size-16 mb-15 display-ib"
              style="color: #00b3bf;font-size: 1rem;">
              <?php echo ucfirst($userdata->first_name)." ".ucfirst($userdata->last_name); ?></p>


            <p class="font-size-14 color666 fontfamily-regular" style="font-size: 0.875rem;">
              <?php echo $userdata->address."<br>".$userdata->zip.",".$userdata->city; ?>

            </p>
          </div>
        </div>
        <div class="relative d-flex">
          <div class="display-ib">
            <p class="fontfamily-regular color999 font-size-12 mb-0" style="font-size: 0.75rem;">Total Duration </p>
            <span class="fontfamily-medium colororange font-size-14 " id="pbookDuration"
              style="color: #FF9944;font-size: 0.875rem;"></span>
          </div>
          <div class="display-ib text-right ml-auto">
            <button class="bg-blue-lite-btn mt-1 " id="pbookPrice"
              style="border-radius: 0.25rem;border: 1px solid #00b3bf;background: #DBF4F6;color: #00b3bf;height: 32px;width: 75px;"></button>
          </div>
        </div>

        <h5 class="font-size-18 color333 fontfamily-medium mt-4 mb-3" style="font-size: 1.125rem;">Booking Information
        </h5>
        <div class="relative">
          <span class="color999 font-size-12 fontfamily-regular">Booked via</span>
          <p class="color333 fontfamily-medium font-size-14" id="bookedvia"></p>
        </div>
        <div class="relative">
          <span class="color999 font-size-12 fontfamily-regular">Booking Date</span>
          <p class="color333 fontfamily-medium font-size-14" id="pbookTime"></p>
        </div>
        <div class="relative">
          <span class="color999 font-size-12 fontfamily-regular">Completed Date</span>
          <p class="color333 fontfamily-medium font-size-14" id="cbookTime"></p>
        </div>
        <div class="relative">
          <span class="color999 font-size-12 fontfamily-regular">Booking ID</span>
          <p class="color333 fontfamily-medium font-size-14" id="pbookId"></p>
        </div>
        <div class="relative">
          <span class="color999 font-size-12 fontfamily-regular">Service</span>
          <p class="color333 fontfamily-medium font-size-14" id="pbookService"></p>
        </div>
        <div class="relative">
          <span class="color999 font-size-12 fontfamily-regular">Salon name</span>
          <p class="color333 fontfamily-medium font-size-14  mb-0" id="pbookSalone"></p>
          <p class="color333 fontfamily-medium font-size-12" id="salonaddress"></p>
        </div>

        <div class="relative">
          <span class="color999 font-size-12 fontfamily-regular">Customer email address</span>
          <p class="color333 fontfamily-medium font-size-14"><?php echo $userdata->email; ?></p>
        </div>

      </div>
    </div>
  </div>
</div>
</div>
<!-- modal end -->

<div class="modal fade" id="add-note">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content">
      <a href="#" id="close_pop" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
            type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"
            class="popup-crose-black-icon">
          <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
            class="popup-crose-black-icon">
        </picture>
      </a>
      <div class="modal-body pt-40 mb-30 pl-40 pr-40">
        <p class="font-size-18 color333 fontfamily-medium mb-20 para_text">Add note for this customer</p>
        <!-- <form id="frmNotesS" method="post" action=""> -->
        <div class="form-group" id="txtnote_validate">
          <label class="inp display-b" style="height: 120px;">
            <!--  <textarea type="text" 
                   name="txtnote" id="txtnote" 
                   placeholder="&nbsp;" 
                   class="form-control custom_scroll" style="min-height: 120px;max-height:120px;;width: 100%;"></textarea> -->
            <textarea class="form-control h-100 custom_scroll" data-uid="<?php echo $userdata->id; ?>"
              style="min-height: 120px;max-height:120px;;width: 100%;" id="clientNotevalue" placeholder="&nbsp;"
              value=""></textarea>
          </label>
          <label class="error display-n" id="err_note"><i class="fas fa-exclamation-circle mrm-5"></i>
            <?php echo $this->lang->line('Please_enter_customer_note'); ?></label>
        </div>
        <div class="text-center mt-30 display-b">
          <input type="hidden" id="booking_ids" name="booking_id" value="">
          <button class=" btn btn-large widthfit" id="savenotesBtn">Save Note</button>
        </div>
        <!--  </form> -->
      </div>
    </div>
  </div>
</div>


<!-- profile-client-view modal start -->
<div class="modal fade" id="profile-client-view-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body p-0 pl-3 pr-3">
        <div class="relative mb-3 mt-10">
          <div class="row">
            <?php 
               $access= $this->session->userdata('access');
               if($access =='marchant')
                  $link='merchant/customers';
                else
                  $link='';
                
              ?>
            <div class="col-12 text-right">
              <a href="<?php if(!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; else echo base_url($link); ?>"
                class="display-ib" id="" data-id="">
                <picture class="" style="width: 22px; height: 22px;">
                  <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
                    type="image/webp" class="" style="width: 22px; height: 22px;">
                  <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
                    type="image/png" class="" style="width: 22px; height: 22px;">
                  <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class=""
                    style="width: 22px; height: 22px;">
                </picture>
              </a>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
              <h3 class="font-size-20 fontfamily-semibold color333 mb-3">Contact Detail</h3>
              <div class="bgwhite border-radius4 box-shadow1 small-mb-30" style="padding: 16px;">
                <!-- p-3 -->
                <div class="row">
                  <div class="col-12 col-sm-3 col-md-3 col-lg-12 col-xl-3">
                    <div class="relative p-1 text-center mb-30">
                      <?php if(!empty($userdata->profile_pic)){ ?>
                      <img
                        src="<?php echo base_url('assets/uploads/users/'.$userdata->id.'/icon_'.$userdata->profile_pic); ?>"
                        class="round-new-v80 ">
                      <?php }else{ ?>
                      <img src="<?php echo base_url('assets/frontend/images/user-icon-gret.svg'); ?>"
                        class="round-new-v80 ">
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-12 col-sm-9 col-md-9 col-lg-12 col-xl-9 pl-0">
                    <div class="relative">
                      <div class="display-ib">
                        <h5 class="font-size-16 colorcyan fontfamily-medium display-ib mt-1">
                          <?php echo ucfirst($userdata->first_name)." ".ucfirst($userdata->last_name); ?> </h5>
                        <span class="font-size-12 color333 fontfamily-regular">
                          (<?php echo ucfirst($userdata->gender); ?>)</span>

                      </div>
                      <div class="display-ib float-right mb-3" style="">
                        <div class="dropdown">
                          <button class="btn btn-new widthfit dropdown-toggle" type="button" id="dropdownMenuButton11"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            style="line-height: 34px;">
                            More Option
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton11">
                            <?php if(!empty($blockclient->id)){ ?>
                            <a class="dropdown-item blockClient"
                              data-uid="<?php echo url_encode($userdata->id); ?>">Unblock</a>
                            <?php }else{ ?>
                            <a class="dropdown-item blockClient"
                              data-uid="<?php echo url_encode($userdata->id); ?>">Block</a>
                            <?php } ?>
                            <a href="<?php echo base_url('booking/new_booking').'?userid='.url_encode($userdata->id); ?>"
                              class="dropdown-item" style=""
                              data-uid="<?php echo url_encode($userdata->id); ?>">Book</a>
                          </div>
                        </div>

                      </div>
                      <div class="relative mt-2">
                        <p class="font-size-14 color666 fontfamily-regular mt-2 overflow_elips" style="width: 385px;">
                          <img class="width24v mr-2"
                            src="<?php echo base_url('assets/frontend/images/birthday.png'); ?>"><span
                            class="color333 fontfamily-medium'"><?php if(!empty($userdata->dob) && $userdata->dob !="0000-00-00") echo date('d-m-Y',strtotime($userdata->dob)); else echo 'NA'; ?></span>
                        </p>
                        <p class="mt-2 overflow_elips" style="width: 385px;">
                          <img src="<?php echo base_url('assets/frontend/images/orange-envlop24.png'); ?>"
                            class="width24v mr-2">
                          <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->email; ?></span>
                        </p>
                        <p class="mt-2 overflow_elips" style="width: 385px;">
                          <img src="<?php echo base_url('assets/frontend/images/orange-call24.png'); ?>"
                            class="width24v mr-2">
                          <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->mobile; ?></span>
                        </p>
                        <p class="mt-2 overflow_elips " style="width: 385px;">
                          <img src="<?php echo base_url('assets/frontend/images/orange-location24.png'); ?>"
                            class="width24v mr-2">
                          <span
                            class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->address ?></span></br>
                          <span class="font-size-14 color333 fontfamily-regular"
                            style="margin-left: 35px"><?php echo $userdata->zip." ".$userdata->city.", ".$userdata->country; ?></span>
                        </p>
                        <p class="mt-2 overflow_elips mb-1 cursor-p editNoteClient" style="width: 385px;" id="notePara"
                          data-toggle="modal" data-target="#add-note">
                          <img src="<?php echo base_url('assets/frontend/images/orange-noteped24.png'); ?>"
                            id="editNoteClient" class="width24v mr-2" style="cursor: pointer;">
                          <span class="font-size-14 color333 fontfamily-regular" id="getClientNote"
                            title="<?php if(!empty($userdata->notes)) echo $userdata->notes; ?>"><?php if(!empty($userdata->notes)){ if(strlen($userdata->notes)>100) echo substr($userdata->notes,0,100)."...."; else echo $userdata->notes; } else echo '<a class="font-size-14 text-underline a_hover_orange cursor-p" style="color: #FF9944;" >'.$this->lang->line('add_note_customer').'</a>'; ?></span>

                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
              <h3 class="font-size-20 fontfamily-semibold color333 mb-3">Bookings</h3>
              <div class="row h-85">

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
                  <div class="bgwhite border-radius4 box-shadow1 h-100 px-3">
                    <div class="row h-100">
                      <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 border-r border-b">
                        <div class="relative text-center">
                          <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                            <?php if(!empty($userdata->totalrevenew)) echo round($userdata->totalrevenew,2); else echo '0'; ?>
                            €</h3>
                          <span class="font-size-14 colorgreen1 fontfamily-regular mt-1 display-ib">Revenue</span>
                        </div>
                      </div>
                      <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 border-b">
                        <div class="relative text-center">
                          <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                            <?php if(!empty($userdata->totalbook)) echo $userdata->totalbook; else echo '0'; ?></h3>
                          <span class="font-size-14 color333 fontfamily-regular mt-1 display-ib">Total Booking</span>
                        </div>
                      </div>
                      <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <div class="relative text-center">
                          <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                            <?php if(!empty($userdata->totalcomplete)) echo $userdata->totalcomplete; else echo '0'; ?>
                          </h3>
                          <span class="font-size-14 colorsuccess fontfamily-regular mt-1 display-ib">Completed</span>
                        </div>
                      </div>
                      <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <div class="relative text-center">
                          <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                            <?php if(!empty($userdata->totalupcoming)) echo $userdata->totalupcoming; else echo '0'; ?>
                          </h3>
                          <span class="font-size-14 fontfamily-regular mt-1 display-ib">Upcoming</span>
                        </div>
                      </div>
                      <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <div class="relative text-center">
                          <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                            <?php if(!empty($userdata->totalcanceled)) echo $userdata->totalcanceled; else echo '0'; ?>
                          </h3>
                          <span class="font-size-14 colorred fontfamily-regular mt-1 display-ib">Cancelled</span>
                        </div>
                      </div>
                      <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <div class="relative text-center">
                          <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0">
                            <?php if(!empty($userdata->totalnoshow)) echo $userdata->totalnoshow; else echo '0'; ?></h3>
                          <span class="font-size-14 fontfamily-regular mt-1 display-ib">No Show</span>
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
              <div class="pt-20 pb-20 pl-30 pr-20 relative d-flex appoin-listing-filter">
                <div class="relative my-new-drop-v display-ib">
                  <!--  <span class="color999 fontfamily-medium font-size-14">Status</span> -->
                  <div class="btn-group multi_sigle_select widthfit80 mr-20 display-ib">
                    <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn ">All
                      Bookings</button> <!-- Select Status -->
                    <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
                      <li class="radiobox-image">
                        <input type="radio" id="id_44" checked="checked" name="booking_st" class="book_status"
                          value="all">
                        <label for="id_44">All Bookings</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_45" name="booking_st" class="book_status" value="upcoming">
                        <label for="id_45">Upcoming Bookings</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_46" name="booking_st" class="book_status" value="recent">
                        <label for="id_46">Recent Bookings</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_47" name="booking_st" class="book_status" value="cancelled">
                        <label for="id_47">Cancelled Bookings</label>
                      </li>
                    </ul>
                  </div>
                </div>
                <!-- filter code new start-->
                <div class="relative my-new-drop-v display-ib">
                  <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
                  <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib">
                    <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn"
                      id="btn_text">Select Filter</button>
                    <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
                      <li class="radiobox-image">
                        <input type="radio" id="id_34" name="filter" class="filterby_days" value="day">
                        <label for="id_34">Today</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_35" name="filter" class="filterby_days" value="current_week">
                        <label for="id_35">Current week</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_36" name="filter" class="filterby_days" value="current_month">
                        <label for="id_36">Current month</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_37" name="filter" class="filterby_days" value="current_year">
                        <label for="id_37">Current year</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_036" name="filter" class="filterby_days" value="last_month">
                        <label for="id_036">Last month</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_037" name="filter" class="filterby_days" value="last_year">
                        <label for="id_037">Last year</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_38" name="filter" class="filterby_days" value="date">
                        <label for="id_38">Search by date</label>
                      </li>
                    </ul>
                  </div>
                </div>

                <div class="form-group date_pecker display-ib mr-20  hide filterdate" style="max-width: 140px;">
                  <label class="inp">
                    <input type="text" id="start_date" name="" placeholder="Start Date" value="" class="form-control">
                  </label>
                  <span class="error" id="start_error"></span>
                </div>

                <div class="form-group date_pecker display-ib hide filterdate" style="max-width: 140px;">
                  <label class="inp">
                    <input type="text" id="end_date" name="" placeholder="End Date" value="" class="form-control">
                  </label>
                  <span class="error" id="end_error"></span>
                </div>
                <div class="display-ib hide filterdate"><i id="search_filter"
                    class="fas fa-search colororange mt-2 ml-2 font-size-24"></i></div>


                <!-- filter code new end-->
                <div class="relative my-new-drop-v display-ib ml-auto mr-2">
                  <span class="color999 fontfamily-medium font-size-14">Show</span>
                  <div class="btn-group multi_sigle_select widthfit2 mr-20 ml-20 open">
                    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn">20</button>
                    <ul class="dropdown-menu mss_sl_btn_dm widthfit2">
                      <li class="radiobox-image">
                        <input type="radio" id="id_14" name="limit" class="change_limit" value="10">
                        <label for="id_14">10</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_15" name="limit" class="change_limit" value="20" checked>
                        <label for="id_15">20</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_16" name="limit" class="change_limit" value="30">
                        <label for="id_16">30</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_16all" name="limit" class="change_limit" value="all">
                        <label for="id_16all">All</label>
                      </li>
                    </ul>
                  </div>
                  <span class="color999 fontfamily-medium font-size-14">Entries</span>

                </div>
                <a href="javascript:void(0);" data-id="csv" class="display-ib cursol-p m-1 export_filterreport"><img
                    class="width30" src="<?php echo base_url('assets/frontend/images/csv-icon.svg'); ?>"></a>
                <a href="javascript:void(0)" data-id="excel" class="display-ib cursol-p m-1 export_filterreport"><img
                    class="width30" src="<?php echo base_url('assets/frontend/images/exel-icon.svg'); ?>"></a>
              </div>

              <div class="my-table my-table-v1">
                <table class="table" id="listingTabl"
                  data-uid='<?php if(!empty($this->uri->segment(3))) echo $this->uri->segment(3); ?>'>
                  <thead>
                    <tr>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting"
                          id="booking_time">DATE
                          <div class="display-ib vertical-middle ml-1">
                            <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>"
                              class="display-b " style="width:8px;">
                            <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>"
                              class="display-b " style="width:8px;">
                          </div>
                        </a>
                        <input type="hidden" id="short_booking_time" value="desc">
                      </th>
                      <th class="text-center height56v">TIME
                      </th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting"
                          id="id">BOOKING ID
                          <div class="display-ib vertical-middle ml-1">
                            <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>"
                              class="display-b " style="width:8px;">
                            <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>"
                              class="display-b " style="width:8px;">
                          </div>
                        </a>
                        <input type="hidden" id="short_id" value="desc">
                      </th>
                      <th class="text-center height56v" style="width:250px;">SERVICE </th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting"
                          id="total_minutes">DURATION
                          <div class="display-ib vertical-middle ml-1">
                            <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>"
                              class="display-b " style="width:8px;">
                            <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>"
                              class="display-b " style="width:8px;">
                          </div>
                        </a>
                        <input type="hidden" id="short_total_minutes" value="desc">
                      </th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting"
                          id="total_price">PRICE
                          <div class="display-ib vertical-middle ml-1">
                            <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>"
                              class="display-b " style="width:8px;">
                            <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>"
                              class="display-b " style="width:8px;">
                          </div>
                        </a>
                        <input type="hidden" id="short_total_price" value="desc">
                      </th>
                      <th class="text-center height56v">STATUS </th>
                      <th class="text-center height56v">RECEIPT</th>
                    </tr>
                  </thead>
                  <tbody id="all_listing">

                  </tbody>
                </table>
                <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                  <ul class="pagination" style="display: inline-flex;" id="pagination">

                  </ul>
                </nav>
                <input type="hidden" id="short_by_field" value="">
              </div>
            </div>

            <!-- dashboard right side end -->
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- profile-client-view modal start -->

<?php $this->load->view('frontend/common/footer_script');  ?>

<script type="text/javascript">
// modal js on load
$(document).ready(function() {
  $("#profile-client-view-modal").modal('show');
});
$('#profile-client-view-modal').modal({
  backdrop: 'static',
  keyboard: false
});



$(function() {
  $(window).on("scroll", function() {
    if ($(window).scrollTop() > 90) {
      $(".header").addClass("header_top");
    } else {
      //remove the background property so it comes transparent again (defined in your css)
      $(".header").removeClass("header_top");
    }
  });
});

var date = new Date();
var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
$('.datepicker').datepicker({
  uiLibrary: 'bootstrap4',
  minDate: today
});

$('#start_date').datepicker({
  uiLibrary: 'bootstrap4',
  dateFormat: 'dd/mm/yy'
});
$('#end_date').datepicker({
  uiLibrary: 'bootstrap4',
  dateFormat: 'dd/mm/yy'
});

$.datepicker.regional['de'] = {
  closeText: 'Done',
  prevText: 'Prev',
  nextText: 'Next',
  currentText: 'heute',
  monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
    'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
  ],
  monthNamesShort: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun',
    'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'
  ],
  dayNames: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
  dayNamesShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
  dayNamesMin: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
  weekHeader: 'KW',
  dateFormat: 'dd.mm.yy',
  firstDay: 0,
  isRTL: false,
  showMonthAfterYear: false,
  yearSuffix: ''
};

$("#start_date").datepicker($.datepicker.regional["de"]);
$("#end_date").datepicker($.datepicker.regional["de"]);


// Tooltips
$('[data-toggle="popover"]').popover({
  trigger: 'hover',
  'placement': 'top'
});

$(document).ready(function() {
  getBookingList();

  function getBookingList(url = '') {
    var lim = $("input[name='limit']:checked").val();
    var uid = $('#listingTabl').attr('data-uid');
    var sht = $('#short_by_field').val();

    if (url == '') {
      url = base_url + "profile/client_booking_list/" + uid;
    }

    loading();
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    $.post(url, {
      limit: lim,
      order: sht
    }, function(data) {
      var obj = jQuery.parseJSON(data);
      if (obj.success == '1') {
        //var time = $("#selctTimeFilter").val();
        // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
        //console.log(obj.html);
        $("#all_listing").html(obj.html);
        $("#pagination").html(obj.pagination);
      }
      unloading();
    });
  }
  
  $(document).on('click', "#pagination .page-item a", function() {
    var url = $(this).attr('href');

    if (url != undefined) {
      getBookingList(url);
    }
    window.scrollTo(0, 350);
    //$("#listingTabl").focus();	
    return false;
    // alert(url);

  });
  $(document).on('click', ".blockClient", function() {
    var uid = $(this).attr('data-uid');
    var url = base_url + "profile/client_block";
    loading();
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    $.post(url, {
      uid: uid
    }, function(data) {


      var obj = jQuery.parseJSON(data);
      if (obj.success == '1') {
        $(".blockClient").text(obj.text);

      }
      unloading();
    });

  });
  $(document).on('change', ".change_limit", function() {
    getBookingList();
    //$(".shodropdown").removeClass('show');
  });

  $(document).on('click', ".shorting", function() {
    var short = $(this).attr('id');
    var chk = $("#short_" + short).val();
    if (chk == 'asc') {
      $("#short_" + short).val('desc');
      $("#short_by_field").val(short + ' desc');
    } else {
      $("#short_" + short).val('asc');
      $("#short_by_field").val(short + ' asc');
    }

    getBookingList();
  });


  $(document).on('click', ".editNoteClient", function() {
    var notval = $("#getClientNote").attr('title');
    //$("#textClientHS").removeClass('display-n');
    //$("#notePara").addClass('display-n');
    $("#add-note").show();
    $("#clientNotevalue").val(notval);
    //notePara,textClientHS,savenotesBtn
  });
  $(document).on('click', "#closenoteBtn", function() {
    $("#notePara").removeClass('display-n');
    $("#textClientHS").addClass('display-n');
    //notePara,textClientHS,savenotesBtn
  });
  $(document).on('click', "#savenotesBtn", function() {
    var upval = $("#clientNotevalue").val();
    var uid = $("#clientNotevalue").attr('data-uid');
    $("#getClientNote").attr('title', upval);
    /*if(upval==''){ 
      $("#err_note").removeClass('display-n');
      return false;
    }*/
    if (upval == '')
      $("#getClientNote").html(
        '<a class="font-size-14 text-underline a_hover_orange cursor-p editNoteClient" style="color: #FF9944;">'
        .$this - > lang - > line('add_note_customer').
        ' </a>'); //text
    else {
      if (upval.length > 100) {
        var res = upval.substr(0, 100) + "...";
      } else {
        var res = upval
      }
      $("#getClientNote").text(res);
    }

    $("#textClientHS").addClass('display-n');
    $("#notePara").removeClass('display-n');
    loading();
    var url = base_url + "profile/update_notes";
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    $.post(url, {
      uid: uid,
      notes: upval
    }, function(data) {


      var obj = jQuery.parseJSON(data);
      if (obj.success == '1') {
        if (obj.text == 'Unblock') {
          $(".blockClient").removeClass('btn-new');
          $(".blockClient").addClass('btn-new1');

        } else {
          $(".blockClient").removeClass('btn-new1');
          $(".blockClient").addClass('btn-new');
        }
        $(".blockClient").text(obj.text);
        $("#close_pop").trigger('click');
        $("#err_note").addClass('display-n');

      }
      unloading();
    });

    //notePara,textClientHS,
  });

  $(document).on('click', ".bookDetailShow", function() {
    $("#popup-v11").modal('show');
    $("#pbookId").text($(this).data('bookid'));
    var bid = $(this).data('encode');
    $(".change_anchor").html('<a class="colororange" target="_blank" href="' + base_url +
      'booking/downloadReceipt/' + bid + '"><img src="' + base_url + 'assets/frontend/images/printer.svg' +
      '" style="width:24px;"></a>');
    $("#pbookTime").text($(this).data('time'));
    $("#cbookTime").text($(this).data('complete'));
    $("#pbookPrice").text($(this).data('price'));
    $("#pbookDuration").text($(this).data('duration'));
    $("#pbookSalone").text($(this).data('salone'));
    $("#pbookService").text($(this).data('service'));
    $("#bookedvia").text($(this).data('bookedvia'));
    $("#salonaddress").html($(this).data('saddress'));
    //$(".shodropdown").removeClass('show');
  });

});



$(document).on('change', '.filterby_days', function() {
  var value = $(this).val();
  var uid = $('#listingTabl').attr('data-uid');
  //alert(value);
  if (value == "date") {
    $('.filterdate').css('display', 'inline-block');
    return false;
  } else
    $('.filterdate').hide();

  var status = $('input[name=booking_st]:checked').val();
  if (typeof status == 'undefined')
    status = '';

  var url = base_url + 'profile/client_booking_list/' + uid + '?short=' + value + '&status=' + status;
  getBookingList(url);

});
$(document).on('change', '.book_status', function() {
  var status = $(this).val();
  var uid = $('#listingTabl').attr('data-uid');
  if (status == "all") {
    // $('.filterby_days').prop('checked', false);
    //$('#btn_text').text('Select Filter');
    //$('.filterdate').hide();
  }
  var start = $('#start_date').val();
  var end = $('#end_date').val();
  var value = $('input[name=filter]:checked').val();
  if (typeof value == 'undefined')
    value = '';

  var url = base_url + 'profile/client_booking_list/' + uid + '?short=' + value + '&status=' + status +
    '&start_date=' + start + '&end_date=' + end;
  getBookingList(url);

});
$(document).on('click', '#search_filter', function() {
  var token = true;
  var start = $('#start_date').val();
  var end = $('#end_date').val();
  var uid = $('#listingTabl').attr('data-uid');
  if (start == "") {
    $('#start_error').html('please select start date');
    token = false;
  } else
    $('#start_error').html('');

  if (end == "") {
    $('#end_error').html('please select end date');
    token = false;
  } else
    $('#end_error').html('');
  if (token == true) {

    var status = $('input[name=booking_st]:checked').val();
    if (typeof status == 'undefined')
      status = '';

    var value = $('input[name=filter]:checked').val();
    if (typeof value == 'undefined')
      value = '';

    var url = base_url + 'profile/client_booking_list/' + uid + '?short=' + value + '&status=' + status +
      '&start_date=' + start + '&end_date=' + end;
    getBookingList(url);
  }
});


function onsearch_filter() {

  var start = '';
  var end = '';
  var uid = $('#listingTabl').attr('data-uid');
  var status = $('input[name=booking_st]:checked').val();
  if (typeof status == 'undefined')
    status = '';

  var value = $('input[name=filter]:checked').val();
  if (typeof value == 'undefined')
    value = '';


  if (value == 'date') {
    var start = $('#start_date').val();
    var end = $('#end_date').val();
  }

  var url = base_url + 'profile/client_booking_list/' + uid + '?short=' + value + '&status=' + status + '&start_date=' +
    start + '&end_date=' + end;
  return url;
}

///  export to csv 
$(document).on('click', '.export_filterreport', function() {
  var type = $(this).attr('data-id');
  var start = '';
  var end = '';
  var order = $("#orderby").val();
  var shortby = $("#shortby").val();
  var status = $('input[name=booking_st]:checked').val();
  if (typeof status == 'undefined')
    status = '';

  var value = $('input[name=filter]:checked').val();
  if (typeof value == 'undefined')
    value = '';

  if (value == 'date') {
    var start = $('#start_date').val();
    var end = $('#end_date').val();
  }
  var uid = $('#listingTabl').attr('data-uid');
  window.location.href = base_url + 'profile/download_userbooking_in_csv_exel/' + uid + '/' + type + '?short=' +
    value + '&status=' + status + '&start_date=' + start + '&end_date=' + end + '&orderby=' + order + '&shortby=' +
    shortby;
});
</script>
<script src="https://app.codox.io/plugins/wave.client.js?apiKey=your-api-key&app=froala" type="text/javascript">
</script>
<script>
const codox = new Codox();
const editor = new FroalaEditor('#editor', {
      events: {
        //setting up on initialization event
        'initialized': function() {
          //Adding Wave configuration
          var config = {
            "app": "froala",
            "docId": "mydoc",
            "username": "Chris",
            "editor": editor,
            "apiKey": "d5bb1f48-356b-4032-8d0c-ba1a79396f79", //replace this
          };

          codox.init(config);
        }
      }


      /// model popup customer 
</script>