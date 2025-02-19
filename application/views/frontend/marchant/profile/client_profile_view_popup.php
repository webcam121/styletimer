

<style type="text/css">
   /* .fr-box.fr-basic .fr-element{
    min-height: 220px!important;
  }
  .fr-wrapper {
    height: 250px !important;
  } */
  #moreParagraph-1,#subscript-1,#superscript-1,#clearFormatting-1{
    display: none;
  }

  
  .plus-green1 {
    /* //color: #fff; */
    /* border: none;
    height: 35px !important;
    width: 35px  !important;
    display: inline-block; */
    text-align: center;
    font-size: 20px;
    border-radius: 0.25rem;
    cursor: pointer;
    padding: 0px !important;
    margin-left: 10px;
    /* background-color: #00949d !important; */
    height: 37px;
    width: 38px;
    -webkit-background-color: #00949d  !important;
}
  .fr-view p{ margin-bottom: 0 !important; }

  .flex-display {
    flex: 1;
    display: flex;	
    flex-direction: row;	
  }
  .flex-center-display {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  @media(max-width:991px){
    .mh300sm {
      min-height: 300px;
      flex-grow: 1;
    }
  }
</style>
  <div class="modal-body"> 
        <div class="relative mb-3">
            <div class="row">
              <?php 
               $access= $this->session->userdata('access');
               if($access =='marchant')
                  $link='merchant/customers';
                else
                  $link='';
                
              ?>
            <div class="col-12 text-right mb-4">
              <div class="modal-header-new relative">
                <a href="#" class="crose-btn font-size-30 color333 a_hover_333 cust_close" data-bookid="<?php echo $bookingid ?>" data-dismiss="modal" id="conf_close">
                <picture class="popup-crose-black-icon" style="width: 22px; height: 22px;">
                  <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon" style="width: 22px; height: 22px;">
                  <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon" style="width: 22px; height: 22px;">
                  <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon" style="width: 22px; height: 22px;">
                </picture> 
                </a>
                
                <h3 class="font-size-20 fontfamily-semibold color333 text-center mb-3"><?php echo $this->lang->line('Customer_profile'); ?></h3>
              </div>
            </div>
                    
              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="bgwhite border-radius4 box-shadow1 small-mb-30" style="padding: 16px;"> <!-- p-3 -->
                  <div class="row">
                    <div class="col-12 col-sm-3 col-md-3 col-lg-12 col-xl-3">
                      <div class="relative p-1 text-center mb-30">
						             <?php if(!empty($userdata->profile_pic)){ ?> 
                        <img src="<?php echo base_url('assets/uploads/users/'.$userdata->id.'/icon_'.$userdata->profile_pic); ?>" class="round-new-v80 ">
                        <?php }else{ ?>
                        <img src="<?php echo base_url('assets/frontend/images/user-icon-gret.svg'); ?>" class="round-new-v80 ">
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-12 col-sm-9 col-md-9 col-lg-12 col-xl-9 pl-0" >
                      <div class="relative">
                         <div class="display-ib mt-2 " style="width:140px;word-wrap: break-word;"> 
                          <h5 class="font-size-14 colorcyan fontfamily-medium display-ib mt-1" style="width:180px;word-wrap: break-word;"><?php echo ucfirst($userdata->first_name)." ".ucfirst($userdata->last_name); ?> </h5>
                          <?php if ($userdata->gender) { ?>
                          <span class="font-size-12 color333 fontfamily-regular"> (<?php echo $this->lang->line('gender_'.$userdata->gender); ?>)</span>
                          <?php } ?>
                          <?php $bc=""; if(!empty($blockclient->id)){
                            $bc="BLOCKIERT";
                          }?>
                          <span id="chg_block_text" class="font-size-12 colorred"><?php echo $bc; ?></span>
                         </div>   
                        <div class="display-ib float-right mb-3" style="">
                          <div class="dropdown float-left">
            							  <button class="btn btn-new widthfit dropdown-toggle" type="button" id="dropdownMenuButton11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="line-height: 34px;">
            								<?php echo 'Optionen';
                            //$this->lang->line('More_Option'); ?>
            							  </button>
            							  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton11">
												<?php if(!empty($blockclient->id)){ ?>
													  <a href="javascript:void(0)" class="dropdown-item blockClient" data-uid="<?php echo url_encode($userdata->id); ?>"><?php echo $this->lang->line('Unblock'); ?></a>
												  <?php }else{ ?>
												<a href="javascript:void(0)" class="dropdown-item blockClient" data-uid="<?php echo url_encode($userdata->id); ?>"><?php echo $this->lang->line('Block'); ?></a>
												<?php } ?>
            								 <!-- <a href="<?php //echo base_url('booking/new_booking').'?userid='.url_encode($userdata->id); ?>" class="dropdown-item" style="" data-uid="<?php //echo url_encode($userdata->id); ?>"><?php //echo $this->lang->line('Book'); ?></a> -->

                             <a href="javascript:void(0)" id="delete_customer" class="dropdown-item" style="" data-uid="<?php echo url_encode($userdata->id); ?>"><?php echo $this->lang->line('Delete'); ?></a>

            							  </div>
            							</div>
                          <div class="float-left">
            							  <!-- <a href="<?php echo base_url('booking/new_booking').'?userid='.url_encode($userdata->id); ?>"
                             style="
background-color: #00949d !important" class="plus-green1" type="button">
            								<svg class="svg-inline--fa fa-plus fa-w-14 colorwhite" style="
background-color: #00949d !important" 
                            aria-hidden="true" data-prefix="fas" data-icon="plus" 
                            role="img" xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 448 512" data-fa-i2svg=""><path style="
background-color: #00949d !important" 
                             fill="currentColor" 
                             d="M448 294.2v-76.4c0-13.3-10.7-24-24-24H286.2V56c0-13.3-10.7-24-24-24h-76.4c-13.3 0-24 10.7-24 24v137.8H24c-13.3 0-24 10.7-24 24v76.4c0 13.3 10.7 24 24 24h137.8V456c0 13.3 10.7 24 24 24h76.4c13.3 0 24-10.7 24-24V318.2H424c13.3 0 24-10.7 24-24z"></path></svg>
                              <i class="fas fa-plus colorwhite"></i> 
                          </a> -->
                          <a href="<?php echo base_url('booking/new_booking').'?userid=
                          '.url_encode($userdata->id); ?>"
                              class="plus-green1" type="button">
            								<img src="<?php echo base_url('assets/backend/img/z.png')?>"  width="37px"/>
                          </a>
                          </div>
                            
                          </div>
                        <div class="relative mt-2">
                          <p class="font-size-14 color666 fontfamily-regular mt-2 overflow_elips" style="width: 385px;">
                            <img src="<?php echo base_url('assets/frontend/images/add-user.png'); ?>" class="mr-2" style="width:22px;height:22px;margin-left:2px;">
                            <span class="font-size-14 color333 fontfamily-regular">
                              <?php 
                                if($userdata->temp_user) {
                                  echo 'Von Salon erstellt am ';
                                  echo date('d.m.Y',strtotime($userdata->created_on));
                                } else {
                                  echo 'App - Kunde seit ';
                                  if ($firstbookingdate) echo date('d.m.Y',strtotime($firstbookingdate->created_on));
                                }?>
                            </span>
                          </p>
                          <p class="font-size-14 color666 fontfamily-regular mt-2 overflow_elips" style="width: 385px;">
                            <img src="<?php echo base_url('assets/frontend/images/birthday.svg'); ?>" class="width24v mr-2">
                            <span class="font-size-14 color333 fontfamily-regular"><?php if(!empty($userdata->dob) && $userdata->dob !="0000-00-00") { echo date('d.m.Y',strtotime($userdata->dob)); echo ' ('.(floor((time() - strtotime($userdata->dob)) / 31556926)).')';}  else echo 'NA'; ?></span>
                          </p>
                          <p class="mt-2 overflow_elips" style="width: 385px;">
                            <img src="<?php echo base_url('assets/frontend/images/orange-envlop24.svg'); ?>" class="width24v mr-2">
                            <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->email; ?></span>
                          </p>
                          <p class="mt-2 overflow_elips" style="width: 385px;">
                            <img src="<?php echo base_url('assets/frontend/images/orange-call24.svg'); ?>" class="width24v mr-2">
                            <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->mobile; ?></span>
                          </p>
                          <p class="mt-2 overflow_elips " style="width: 385px;">
                            <img src="<?php echo base_url('assets/frontend/images/orange-location24.svg'); ?>" class="width24v mr-2">
                            <?php if ($userdata->address) {?>
                            <span class="font-size-14 color333 fontfamily-regular"><?php echo $userdata->address ?></span></br>
                            <?php } ?>
                            <span class="font-size-14 color333 fontfamily-regular" style="margin-left: <?php echo $userdata->address?'35px':'0px';?>"><?php echo $userdata->zip." ".$userdata->city; ?></span>
                          </p>
                          <div data-noteid="<?php echo (!empty($userdata->note_id)?url_encode($userdata->note_id):'') ?>" class="mt-2 overflow_elips mb-1 cursor-p editNoteClient" style="width: 385px;" id="notePara">
                            <img src="<?php echo base_url('assets/frontend/images/orange-noteped24.svg'); ?>" id="editNoteClient" class="width24v mr-2 vertical-top" style="cursor: pointer;">
                            
                            <span id="add_toolvalue" class="on-hover-show-tool" style="<?php if(empty($userdata->notes)){ echo 'display:none;'; } ?>"><?php if(!empty($userdata->notes)) echo strip_tags($userdata->notes); ?></span>
                            
                            <?php  $clss=""; if(!empty($userdata->notes)){ 
                              $notesStr = strip_tags($userdata->notes);
                              $styles = '';
                              if(strlen($notesStr)>85){
                                $clss ="notes_view"; $notes=substr($notesStr,0,85);
                                $styles = 'padding-bottom: 20px';
                              } 
                              else{
                                
                               $notes= $notesStr; }
                               }
                               else {
                                $notes ='<a class="font-size-14 text-underline a_hover_orange cursor-p" style="color: #FF9944;" >'.$this->lang->line('add_note_customer').'</a>'; 
                              } ?>
                            <div class="font-size-14 color333 fontfamily-regular <?php echo $clss; ?>" id="getClientNote" data-uid="<?php echo $userdata->id; ?>" data-enuid="<?php echo url_encode($userdata->id) ?>" title="" style="<?php echo $styles?>">
                              <?php echo $notes; ?>
                            </div>
                            

                          </div>
                        </div>
                      </div>
                    </div>
                    <input type="hidden" id="editnotes_pp" name="" value="">
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 flex-display">
                <div class="row mh300sm">

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 flex-display">
                        <div class="bgwhite border-radius4 box-shadow1 px-3 flex-display">
                        <div class="form-group relative my-new-drop-v1 display-ib ml-auto">
					                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
					                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text" aria-expanded="false"><?php echo $this->lang->line('current_year'); ?></button>
					                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" x-placement="bottom-start"
                             style=" max-height: 400px;  !important">
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_1341cf" data-uid="<?php echo url_encode($userdata->id); ?>" name="filter_sale_revenew" class="filterby_revenew" value="day">
					                      <label for="id_1341cf"><?php echo $this->lang->line('today'); ?></label>
					                    </li>
					                     <li class="radiobox-image">
					                      <input type="radio" id="id_1381cf" data-uid="<?php echo url_encode($userdata->id); ?>" name="filter_sale_revenew" class="filterby_revenew" value="last_seven_day">
					                      <label for="id_1381cf"><?php echo $this->lang->line('last_seven_days'); ?></label>
					                    </li>					                     
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_1351cf" data-uid="<?php echo url_encode($userdata->id); ?>" name="filter_sale_revenew" class="filterby_revenew" value="current_week">
					                      <label for="id_1351cf"><?php echo $this->lang->line('current_week'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_1361cf" data-uid="<?php echo url_encode($userdata->id); ?>" name="filter_sale_revenew" class="filterby_revenew" value="current_month">
					                      <label for="id_1361cf"><?php echo $this->lang->line('current_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_1371cf" data-uid="<?php echo url_encode($userdata->id); ?>" name="filter_sale_revenew" class="filterby_revenew" value="current_year" checked="">
					                      <label for="id_1371cf"><?php echo $this->lang->line('current_year'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_10361cf" data-uid="<?php echo url_encode($userdata->id); ?>" name="filter_sale_revenew" class="filterby_revenew" value="last_month">
					                      <label for="id_10361cf"><?php echo $this->lang->line('last_month'); ?></label>
					                    </li>
					                    <li class="radiobox-image">
					                      <input type="radio" id="id_10371cf" data-uid="<?php echo url_encode($userdata->id); ?>" name="filter_sale_revenew" class="filterby_revenew" value="last_year">
					                      <label for="id_10371cf"><?php echo $this->lang->line('last_year'); ?></label>
					                    </li>
					                  </ul>
					                </div>                
					              </div>
                          <div class="row" style="padding-top: 20px;width: 100%;">
                            <div class="flex-center-display col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 border-r border-b">
                              <div class="relative text-center">
                              <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="revenew">
                                <?php if(!empty($userdata->totalrevenew)){
                                  //echo $userdata->totalrevenew;
                                //    $price =  price_formate($userdata->totalrevenew); 
                                //  echo number_format($price, 2, '.', '');
                                }else{
                                   echo '0';
                                }
                                
                                  ?> €</h3>
                                <span class="font-size-14 colorgreen1 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Revenue'); ?></span>
                              </div>
                            </div>
                            <div class="flex-center-display col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 border-b">
                              <div class="relative text-center">
                              <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="totalbook"><?php if(!empty($userdata->totalbook)) echo $userdata->totalbook; else echo '0'; ?></h3>
                                <span class="font-size-14 color333 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Total_Booking'); ?></span>
                              </div>
                            </div>
                            <div class="flex-center-display col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                              <div class="relative text-center">
                                <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="totalcomplete"><?php if(!empty($userdata->totalcomplete)) echo $userdata->totalcomplete; else echo '0'; ?></h3>
                                <span class="font-size-14 colorsuccess fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('book_status_completed'); ?></span>
                              </div>
                            </div>
                            <div class="flex-center-display col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                              <div class="relative text-center">
                                <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="totalupcoming"><?php if(!empty($userdata->totalupcoming)) echo $userdata->totalupcoming; else echo '0'; ?></h3>
                                <span class="font-size-14 fontfamily-regular mt-1 display-ib"><?php echo $this->lang->line('Upcoming'); ?></span>
                              </div>
                            </div>
                            <div class="flex-center-display col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                              <div class="relative text-center">
                                <h3 class="font-size-24 colordblue fontfamily-regular mt-0 mb-0" id="totalcanceled"><?php if(!empty($userdata->totalcanceled)) echo $userdata->totalcanceled; else echo '0'; ?></h3>
                                <span class="font-size-14 colorred fontfamily-regular mt-1 display-ib"><?php echo ucfirst(strtolower($this->lang->line('book_status_cancelled'))); ?></span>
                              </div>
                            </div>
                            <div class="flex-center-display col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                              <div class="relative text-center">
                                <h3 class="font-size-24 colordblue fontfamily-regular mb-0" id="totalnoshow"><?php if(!empty($userdata->totalnoshow)) echo $userdata->totalnoshow; else echo '0'; ?></h3>
                                <span class="font-size-14 fontfamily-regular mt-1 display-ib" style="height: 20px;"><?php echo $this->lang->line('No_show'); ?></span>
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
                <h3 class="font-size-20 fontfamily-semibold color333 mt-3" id="appoint_header">
                  <?php echo $this->lang->line('Bookings'); ?></h3>
                <div class="bgwhite border-radius4 box-shadow1 mb-3 mt-3">
            <div class="pt-20 pb-20 pl-30 pr-20 relative d-flex appoin-listing-filter">
              <div class="relative my-new-drop-v display-ib">
               <!--  <span class="color999 fontfamily-medium font-size-14">Status</span> -->
                <div class="btn-group multi_sigle_select widthfit80 mr-20 display-ib"> 
                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn "><?php echo $this->lang->line('Upcoming_bookings'); ?></button>  <!-- Select Status -->
                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
                    <li class="radiobox-image">
                      <input type="radio" id="id_44" name="booking_st" class="book_status" value="all">
                      <label for="id_44"><?php echo $this->lang->line('all_bookings'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_45" name="booking_st" class="book_status" value="upcoming" checked="checked">
                      <label for="id_45"><?php echo $this->lang->line('Upcoming_bookings'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_46" name="booking_st" class="book_status" value="recent">
                      <label for="id_46"><?php echo $this->lang->line('Recent_Bookings'); ?> </label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_47" name="booking_st" class="book_status" value="cancelled">
                      <label for="id_47"><?php echo $this->lang->line('Cancelled_bookings'); ?></label>
                    </li>
                  </ul>
                </div>                
              </div>
              <!-- filter code new start-->
              <div class="relative my-new-drop-v display-ib">
               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('select_filter'); ?></button>
                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
                    <li class="radiobox-image">
                      <input type="radio" id="id_34" name="filter" class="filterby_days" value="day">
                      <label for="id_34"><?php echo $this->lang->line('Today'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_35" name="filter" class="filterby_days" value="current_week">
                      <label for="id_35"><?php echo $this->lang->line('current_week'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_36" name="filter" class="filterby_days" value="current_month">
                      <label for="id_36"><?php echo $this->lang->line('current_month'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_37" name="filter" class="filterby_days" value="current_year">
                      <label for="id_37"><?php echo $this->lang->line('current_year'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_036" name="filter" class="filterby_days" value="last_month">
                      <label for="id_036"><?php echo $this->lang->line('last_month'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_037" name="filter" class="filterby_days" value="last_year">
                      <label for="id_037"><?php echo $this->lang->line('last_year'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_38" name="filter" class="filterby_days" value="date">
                      <label for="id_38"><?php echo $this->lang->line('serach_by_date'); ?></label>
                    </li>
                  </ul>
                </div>                
              </div>

              <div class="form-group date_pecker display-ib mr-20  hide filterdate" style="max-width: 140px;">
                <label class="inp">
                  <input type="text" class="form-control" id="start_date" placeholder="<?php echo $this->lang->line('Start_Date'); ?>">
                </label>
                <span class="error" id="start_error"></span>
              </div>

              <div class="form-group date_pecker display-ib hide filterdate" style="max-width: 140px;">
                <label class="inp">
                  <input type="text" class="form-control" id="end_date" placeholder="<?php echo $this->lang->line('End_Date'); ?>">
                </label>
                <span class="error" id="end_error"></span>
              </div>
              <div class="display-ib hide filterdate"><i id="search_filter" class="fas fa-search colororange mt-2 ml-2 font-size-24"></i></div>


              <!-- filter code new end-->
              <div class="relative my-new-drop-v display-ib ml-auto mr-2">
                   <span class="color999 fontfamily-medium font-size-14"><?php echo $this->lang->line('Show'); ?></span> 
                    <div class="btn-group multi_sigle_select widthfit2 mr-20 ml-20 open"> 
                      <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn">20</button>
                      <ul class="dropdown-menu mss_sl_btn_dm widthfit2">
                        <li class="radiobox-image">
                          <input type="radio" id="clientid_14" name="limit" class="change_client_bookinglimit" value="10">
                          <label for="clientid_14">10</label>
                        </li>
                        <li class="radiobox-image">
                          <input type="radio" id="clientid_15" name="limit" class="change_client_bookinglimit" value="20" checked>
                          <label for="clientid_15">20</label>
                        </li>
                        <li class="radiobox-image">
                          <input type="radio" id="clientid_16" name="limit" class="change_client_bookinglimit" value="30">
                          <label for="clientid_16">30</label>
                        </li>
                        <li class="radiobox-image">
                          <input type="radio" id="clientid_16all" name="limit" class="change_client_bookinglimit" value="all">
                          <label for="clientid_16all"><?php echo $this->lang->line('all'); ?></label>
                        </li>
                      </ul>
                  </div>
                   <span class="color999 fontfamily-medium font-size-14"><?php echo $this->lang->line('Entries'); ?></span>             
                 
              </div>
              <a href="javascript:void(0);" data-id="csv" class="display-ib cursol-p m-1 export_filterreport"><img class="width30" src="<?php echo base_url('assets/frontend/images/csv-icon.svg'); ?>"></a>
              <a href="javascript:void(0)" data-id="excel" class="display-ib cursol-p m-1 export_filterreport"><img class="width30" src="<?php echo base_url('assets/frontend/images/exel-icon.svg'); ?>"></a>
            </div>

              <div class="my-table my-table-v1">
                <table class="table" id="listingTabl" 
                data-uid="<?php echo url_encode($userdata->id); ?>">
                 <!-- <table class="table" id="myTable"> -->
                  <thead>
                    <tr>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting" id="booking_time"><?php echo $this->lang->line('Date'); ?> 
                        <div class="display-ib vertical-middle ml-1">
                          <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_booking_time" value="desc">
                      </th>
                      <th class="text-center height56v"><?php echo $this->lang->line('Time'); ?>
                      </th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting" id="id"><?php echo strtoupper($this->lang->line('Booking_Id')); ?>
                        <div class="display-ib vertical-middle ml-1">
                          <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_id" value="desc">
                      </th>
                      <th class="text-center height56v" style="width:80px;"><?php echo $this->lang->line('Service'); ?> </th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting" id="total_minutes"><?php echo $this->lang->line('Duration'); ?>
                        <div class="display-ib vertical-middle ml-1">
                          <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_total_minutes" value="desc">
                      </th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting" id="total_price"><?php echo $this->lang->line('Price'); ?>
                        <div class="display-ib vertical-middle ml-1">
                          <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_total_price" value="desc">
                      </th>
                      <th class="text-center height56v">Ursprung</th>
                      <th class="text-center height56v"><?php echo $this->lang->line('Status'); ?> </th>
                      <th class="text-center height56v"><?php echo strtoupper($this->lang->line('Receipt')); ?> </th>
                    </tr>
                  </thead>
                  <tbody id="all_listing">

                  </tbody>
                </table>
                  <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;" id="pagination_clientBooking">
               
                    </ul>
                  </nav>
                  <input type="hidden" id="short_by_field" value="">
              </div>
            </div>

          <!-- dashboard right side end -->       
          </div>
         </div>                  

      </div>
      
      <script type="text/javascript">
      
      $(document).ready(function(){
        $(document).on('click','#start_date', function(){
           var date = new Date();
          var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
          $('.datepicker').datepicker({
              locale: 'de-de',
              uiLibrary: 'bootstrap4',
              monthNames: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
         dayNamesMin:[ 'SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
              minDate:today
            });

          $('#start_date').datepicker({
            locale: 'de-de',
              uiLibrary: 'bootstrap4',
              monthNames: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
         dayNamesMin:[ 'SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
              dateFormat: 'dd.mm.yy'
            });
            $('#end_date').datepicker({
              locale: 'de-de',
              uiLibrary: 'bootstrap4',
               monthNames: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
         dayNamesMin:[ 'SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
              dateFormat: 'dd.mm.yy'
            });
      });
          var date = new Date();
          var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
          $('.datepicker').datepicker({
              locale: 'de-de',
              uiLibrary: 'bootstrap4',
              monthNames: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
         dayNamesMin:[ 'SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
              minDate:today
            });

          $('#start_date').datepicker({
            locale: 'de-de',
              uiLibrary: 'bootstrap4',
              monthNames: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
         dayNamesMin:[ 'SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
              dateFormat: 'dd.mm.yy'
            });
            $('#end_date').datepicker({
              locale: 'de-de',
              uiLibrary: 'bootstrap4',
               monthNames: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
         dayNamesMin:[ 'SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
              dateFormat: 'dd.mm.yy'
            });
      });
        

   
      </script>

      
   <script src="~/scripts/jquery-1.10.2.js"></script>

<!-- #region datatables files -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" />
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">

</script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js">

</script>


<!-- #endregion -->

 <script>
      jquery(document).ready(function() {
    jquery('#myTable').DataTable();
} );
    </script>  