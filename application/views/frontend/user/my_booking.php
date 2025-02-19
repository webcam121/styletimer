<?php $this->load->view('frontend/common/header'); ?>
<style type="text/css">
.page-item{display:inline-block;}
.page-item a{
    position: relative;
    display: block;
    height: 26px;
    width: 26px;
    padding: 0rem;
    margin: 0rem 2px;
    color: #333333;
    background-color: transparent;
    border: 1px solid transparent;
    border-radius: 4px;
    text-align: center;
    line-height: 26px;

}

.my-table-responsive .table th,
.my-table-responsive .table td {
    padding: 12px !important;
}
</style>

	<section class="pt-120 clear user_booking_list_section1">
      <div class="container">
        <div class="row">
          
        	<?php //print_r($booking); ?>
          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
           <div class="alert alert-danger absolute vinay top w-100 mt-15 alert_message alert-top-0 display-n" id="error_message" style="">
              <button type="button" class="close ml-10 " data-dismiss="alert">&times;</button>
              <span id="alert_message"> </span>
            </div>
            <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-10">


              <div class="pt-20 pb-20 pl-30 relative top-table-droup">
              
              <div class="relative my-new-drop-v display-ib">
               <!--  <span class="color999 fontfamily-medium font-size-14">Status</span> -->
                <div class="btn-group multi_sigle_select widthfit80 mr-20 display-ib"> 
                  <button data-toggle="dropdown" style="line-height: 30px" class="btn btn-default dropdown-toggle mss_sl_btn ">
                    <?php if(isset($_GET['status'])){ 
                      if($_GET['status']=='upcoming')
                        echo $this->lang->line('Upcoming_bookings'); 
                      else if ($_GET['status']=='recent')
                        echo $this->lang->line('Recent_Bookings'); 
                      else if ($_GET['status']=='cancelled')
                        echo $this->lang->line('Cancelled_bookings');
                      else
                        echo $this->lang->line('all_bookings');
                    }
                    else{ echo $this->lang->line('all_bookings'); } ?>

                    <?php //echo $this->lang->line('all_bookings'); ?></button>  <!-- Select Status -->
                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
                    <li class="radiobox-image">
                      <input type="radio" id="id_44b" name="booking_st" class="shortBookS" value="all">
                      <label for="id_44b"><?php echo $this->lang->line('all_bookings'); ?></label> 
                      <!-- checked="checked" -->
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_45b" name="booking_st" class="shortBookS" value="upcoming">
                      <label for="id_45b"><?php echo $this->lang->line('Upcoming_bookings'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_46b" name="booking_st" class="shortBookS" value="recent">
                      <label for="id_46b"><?php echo $this->lang->line('Recent_Bookings'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_47b" name="booking_st" class="shortBookS" value="cancelled">
                      <label for="id_47b"><?php echo $this->lang->line('Cancelled_bookings'); ?></label>
                    </li>
                  </ul>
                </div>                
              </div>
                  <span class="color999 fontfamily-medium font-size-14"><?php echo $this->lang->line('Show'); ?></span>
                  <div class="btn-group multi_sigle_select widthfit80"> 
                    <button data-toggle="dropdown" style="line-height: 30px;" class="btn btn-default dropdown-toggle mss_sl_btn "><?php if(isset($_GET['limit'])){ echo $_GET['limit']; }else{ echo '10'; } ?></button>
                    <ul class="dropdown-menu mss_sl_btn_dm widthfit80 noclose">
                      <li class="radiobox-image">
                        <input type="radio" id="id_14" name="cc" class="shortBook" value="10">
                        <label for="id_14">10</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_15" name="cc" class="shortBook" value="20">
                        <label for="id_15">20</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_16" name="cc" class="shortBook" value="30">
                        <label for="id_16">30</label>
                      </li>
                    
                    </ul>
                </div>
                <?php /* <span class="color999 fontfamily-medium font-size-14"><?php echo $this->lang->line('Show'); ?></span> */?>
                <input type="hidden" id="bookLimit" value="<?php echo (isset($_GET['limit']) ? $_GET['limit'] : '') ?>">
                <input type="hidden" id="bookStatus" value="<?php echo (isset($_GET['status']) ? $_GET['status'] : '') ?>">            
              </div>
            <?php if(!empty($booking)){ ?>
              <div class="my-table my-table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th class="text-center">Salon</th>
                      <th class="text-center"><?php echo $this->lang->line('Date'); ?> </th>
                      <th class="text-center"><?php echo $this->lang->line('Time'); ?> </th>
                      <th class="text-center"><?php echo $this->lang->line('Employees'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Service'); ?> </th>
                      <th class="text-center">Servicezeit </th>
                      <th class="text-center"><?php echo $this->lang->line('Price'); ?> </th>
                      <th class="text-center"><?php echo $this->lang->line('Status'); ?> </th>
                      <th class="text-center"><?php echo $this->lang->line('Action'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                 
                  foreach($booking as $row){
					if($row->profile_pic !=''){
						$img=getimge_url('assets/uploads/employee/'.$row->employee_id.'/','icon_'.$row->profile_pic,'png');
						$img1=getimge_url('assets/uploads/employee/'.$row->employee_id.'/','icon_'.$row->profile_pic,'webp');
					 }
					else{
						$img=base_url('assets/frontend/images/user-icon-gret.svg');
						$img1=base_url('assets/frontend/images/user-icon-gret.webp');
					 }

						$time = new DateTime($row->booking_time);
                 		$date = $time->format('d.m.Y');
                 		$time = $time->format('H:i');

                $ser_nm='';
                $book_details=getselect('st_booking_detail','id,service_id,service_name',array('booking_id'=>$row->id));
                        if(!empty($book_details)){ 
                            foreach($book_details as $serv){ 
                          $sub_name=get_subservicename($serv->service_id);  
                          if($sub_name == $serv->service_name)
                                  $ser_nm.=$serv->service_name.',';
                            else
                                  $ser_nm.=$sub_name.' - '.$serv->service_name.',';
                          } 
                        }
					 ?>
                    <tr>
                      <td class="font-size-14 color666 fontfamily-regular booking_row" id="<?php echo url_encode($row->id); ?>"><p class="overflow_elips mb-0" style="text-align:center;"><?php echo $row->business_name ?></p></td>   
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row" id="<?php echo url_encode($row->id); ?>"><?php echo $date; ?></td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row" id="<?php echo url_encode($row->id); ?>"><?php echo $time; ?></td>                      
                      <td class="font-size-14 color666 fontfamily-regular booking_row" id="<?php echo url_encode($row->id); ?>">  
                       
                        <picture class="mr-3 width30" >
                          <img src="<?php echo $img; ?>" type="image/png" class="width30 border-radius50">
                          <source srcset="<?php echo $img1; ?>" type="image/webp" class="width30 border-radius50">
                        </picture>
                        <p class="mb-0 display-ib"> <!-- overflow_elips -->
                          <?php if($row->first_name =='' && $row->last_name =='')
                              echo 'Any Employee';
                              else
                                echo $row->first_name; /*.' '.$row->last_name*/
                          ?>
                        </p>
                      </td>
                      <td class="font-size-14 color666 fontfamily-regular booking_row" id="<?php echo url_encode($row->id); ?>"><p class="overflow_elips mb-0"><?php echo rtrim($ser_nm, ',');//get_servicename($row->id); ?></p></td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row" id="<?php echo url_encode($row->id); ?>"><?php echo $row->total_time - $row->total_buffer; ?> Mins</td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row" id="<?php echo url_encode($row->id); ?>"><?php if($row->price_start_option=='ab' && $row->status != 'completed'){echo $row->price_start_option.' '.price_formate($row->total_price);}else{ echo price_formate($row->total_price);} ?> €</td>
                      <td class="font-size-14 fontfamily-regular booking_row" id="<?php echo url_encode($row->id); ?>">
                      	<?php $cls='';  $icon=''; 
                            if($row->status =='confirmed'){
                            $cls='conform';
                             $clss='Bestätigt';
                            }
                      				
                      		   else if($row->status =='cancelled')
                      		   	{
                                 	$cls='cencel';
                                    $clss='Storniert';
                               }
                      		   else if($row->status =='completed'){
                      		   	{
                                 	$cls='completed';
                                   $clss='Abgeschlossen';
                                 $icon=' <i class="fa fa-check" aria-hidden="true"></i>';
                               }
                             }
                             else if($row->status =='no show')
                                {
                                  $cls='cencel';
                                  $clss='Storniert';
                                }
                      	?>
                      	<span id="CssStatus_<?php echo $row->id; ?>"
                         class="<?php echo $cls; ?>">
                         <?php echo ucfirst($clss).$icon;
                         //echo ucfirst($row->status).$icon; ?></span>
                      </td>

                      <td class="text-center">                        
                        <div class="dropdown">
                          <div class="" data-toggle="dropdown">
                            <img src="<?php echo base_url('assets/frontend/images/table-more-icon.svg'); ?>">
                          </div>
                          <div class="dropdown-menu widthfit">
                            <a class="dropdown-item color666 font-size-14 fontfamily-regular booking_row" href="#" id="<?php echo url_encode($row->id); ?>">Ansehen
                              <?php //echo $row->booking_time.'>'.date('Y-m-d H:i:s',strtotime('+'.$row->hr_before_cancel.' hours'))  ?>
                            </a>
                           <?php //echo ($row->status=='cancelled')?'display:none':''; ?>
                           <div style="" id="divStatus_<?php echo $row->id; ?>">
                            <?php if($row->status == 'confirmed' && 
                            $row->cancel_booking_allow=="yes"){ 
                                   if($row->booking_time > date('Y-m-d H:i:s',strtotime('+'.$row->hr_before_cancel.' hours'))){ 
                                   ?> 
                                <a class="dropdown-item color666 font-size-14 fontfamily-regular booking_cancels" href="#" id="<?php echo url_encode($row->id); ?>"  data-toggle="modal" data-target="#booking-cencel-table"><?php echo $this->lang->line('Cancel');?></a> 
                           <?php }
                               else if($row->booking_time > date('Y-m-d H:i:s')){ ?>
                                <a class="dropdown-item font-size-14 fontfamily-regular bookCancelRes_popup" style="color: #bbb1b1;" data-hr="<?php echo $row->hr_before_cancel; ?>" data-salon="<?php echo $row->business_name?>" data-mobile="<?php echo $row->mobile; ?>"  href="javascript:void(0)" id="<?php echo url_encode($row->id); ?>" data-toggle="modal" data-target="#CancelReshedule"><?php echo $this->lang->line('Cancel');?></a>
                              <?php } 
                         }
                            
                            // if($row->status == 'confirmed' && $row->booking_time > date('Y-m-d H:i:s',strtotime('+24 hours')) && $row->reshedule_count_byuser==0){ 
                             if($row->status == 'confirmed' && $row->cancel_booking_allow=="yes" && $row->reshedule_count_byuser==0){ 
                                
                                 if($row->booking_time > date('Y-m-d H:i:s',strtotime('+'.$row->hr_before_cancel.' hours'))){
                                ?> 
                                <a class="dropdown-item color666 font-size-14 fontfamily-regular resheduleByuser" href="#" data-eid="<?php echo url_encode($row->employee_id); ?>" data-mid="<?php echo url_encode($row->merchant_id); ?>" id="<?php echo url_encode($row->id); ?>"><?php echo $this->lang->line('Reschedule');?></a> 
                              <?php }
                              else if($row->booking_time > date('Y-m-d H:i:s')){ ?>
                                <a class="dropdown-item font-size-14 fontfamily-regular bookCancelRes_popup" style="color: #bbb1b1;" data-hr="<?php echo $row->hr_before_cancel; ?>" data-salon="<?php echo $row->business_name?>" data-mobile="<?php echo $row->mobile; ?>" href="javascript:void(0)" id="<?php echo url_encode($row->id); ?>" data-toggle="modal"
                                 data-target="#CancelReshedule"><?php echo $this->lang->line('Reschedule');?></a>
                              <?php }
                                 
                             }  
                          if(($row->status =='completed' || $row->status =='cancelled' || $row->status =='no show' || $row->status =='confirmed') && ($row->end_date > date('Y-m-d H:i:s'))){                 
                                if($row->status =='confirmed' && $row->booking_time > date('Y-m-d H:i:s')){ 
                                   }else{ 
                                     
                                    $cuD = date("Y-m-d");
                                    $currentData = "'$cuD'" .' '."'00:00:00'";
                                    $sql2="SELECT `salon_id`,
                                    DATE_ADD(`st_users`.`created_on`,
                                    INTERVAL + extra_trial_month MONTH) AS endTrial FROM `st_favourite` 
                                    JOIN `st_users` ON `st_favourite`.`salon_id`=`st_users`.`id` 
                                    WHERE `st_favourite`.`salon_id` = $row->merchant_id HAVING endTrial > $currentData";
                                    $query2=$this->db->query($sql2);
                                    $favourite = $query2->result();
                                    // echo $this->db->last_query();
                                    //print_r($favourite);   
                                    if($favourite && $favourite[0]->salon_id == $row->merchant_id){
                                     
                                     
                                     ?>
                                
                                  <a id="<?php echo url_encode($row->id); ?>" 
                                  
                                  class="dropdown-item color666 font-size-14 fontfamily-regular rebooking" 
                                  href="#" data-toggle="modal" data-id="<?php echo url_encode($row->merchant_id); ?>" 
                                  data-target="#booking-rebook-table">Erneut buchen</a>
                                <?php }} 

                             }
                            if($row->status =='completed' && $row->tot_review < 1){ ?>
                            <a id="<?php echo url_encode($row->id); ?>" class="dropdown-item color666 font-size-14 fontfamily-regular user_reviewpopup" href="javascript:void(0)" data-id="<?php echo url_encode($row->merchant_id); ?>">Bewerten </a>
                            
                            <?php } ?>
                            </div>
                          </div>
                        </div>                      
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;">
                     <?php if($this->pagination->create_links()){ echo $this->pagination->create_links(); } ?>
                    </ul>
                 </nav>
                  <!-- <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;">
                      
                      <li class="page-item">
                        <a class="color333 a_hover_333 mr-3 display-b" href="#" aria-label="Previous">
                          <span class="sr-only">Previous</span>
                        </a>
                      </li>
                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item"><a class="page-link" href="#">4</a></li>
                      <li class="page-item">
                        <a class="color333 a_hover_333 ml-3 display-b" href="#" aria-label="Next">
                          <span class="sr-only">Next</span>
                        </a>
                      </li>
                    
                    </ul>
                  </nav> -->
              </div>
              <?php }else{ ?>
                <div class="text-center" style="padding-bottom: 45px;">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;">Du hast noch keine Buchungen.</p></div>
                <?php } ?>
            </div>
          </div>
          <input type="hidden" id="action_from" name="" value="list">
        </div>
      </div>
    </section>

     <!-- modal start -->
    <div class="modal fade" id="booking-cencel-table">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="close_cancel">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40 text-left">
            <!-- <img src="<?php //echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>"> -->
            <h4 class="fontsize-20 fontfamily-medium mb-10" style="color:#212121;"><?php echo $this->lang->line('Cancel-Booking'); ?></h4>
            <p class="font-size-14 color333 fontfamily-medium mt-10 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;"><?php echo $this->lang->line('Please-select-reason-cancel'); ?>  </p>

            <div class="radio mt-20 mb-20">
                <label class="fontsize-14 pl0 color333">
                  <input type="radio" name="reason" value="Sick">
                  <span class="cr"><i class="cr-icon fas fa-circle"></i></span>
                  <?php echo $this->lang->line('Sick'); ?>
                </label>
            </div>
            <div class="radio mt-20 mb-20">
                <label class="fontsize-14 pl0 color333">
                  <input type="radio" name="reason" value="No Time">
                  <span class="cr"><i class="cr-icon fas fa-circle"></i></span>
                  <?php echo $this->lang->line('No-Time'); ?>
                </label>
            </div>
            <div class="radio mt-20 mb-20">
                <label class="fontsize-14 pl0 color333">
                  <input type="radio" name="reason" value="Booked Accidentally">
                  <span class="cr"><i class="cr-icon fas fa-circle"></i></span>
                  <?php echo $this->lang->line('Booked-Accidentally'); ?>
                </label>
            </div>
            <div class="radio mt-20 mb-20">
                <label class="fontsize-14 pl0 color333">
                  <input type="radio" name="reason" value="Other">
                  <span class="cr"><i class="cr-icon fas fa-circle"></i></span>
                  <?php echo $this->lang->line('Other1'); ?>
                </label>
            </div>
            <label class="error font-size-16" id="res_err" style="bottom:70px; display: none;"><?php echo $this->lang->line('Please-select-reason-cancel'); ?></label>
            <div class="text-center mt-30">
              <input type="hidden" id="bookingid" name="bookingid" value="">
              <input type="hidden" id="check_access" name="" value="user">
              <button type="button" id="cancel_booking" class="btn btn-large widthfit"><?php echo $this->lang->line('Cancel-Booking'); ?></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->
    
  <!-- modal start -->
<!--
    <div class="modal fade" id="bookingrescheduleByuser">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="close_resch">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg'); ?>" class="popup-crose-black-icon">
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <h3 class="font-size-18 fontfamily-medium color333 mb-50">Reschedule Booking</h3>
            <label class="error_label" id="date_err" style="top:55px"></label>
            <form id="frmReshedule" name="frmReshedule" method="post" action="<?php echo base_url(); ?>">
              <div class="row">
                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group date_pecker">
                    <label class="inp">
                    <input type="text" id="chg_date" name="chg_date" placeholder="Select Date" value="" class="form-control datepicker" readonly="readonly">
                    
                  </label>
                  </div>
                  <label class="error_label" id="chg_date_err" style="top:40px"></label>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">

                   <div class="form-group form-group-mb-50">
					  <select class="widthfit btn-group custome-select pl-2" id="chg_time" name="chg_time" style="width: 185px !important;" >
						 <option value="">Select time</option>

					
					  </select>
					  
					</div>
					  <label class="error_label" id="chg_time_err" style="top:40px"></label>
                </div>
              </div>
              <div class="text-center mt-15">
                <input type="hidden" id="reSchedule_id" name="reSchedule_id" value="">
                <input type="hidden" id="reseid" name="reseid" value="">
                <input type="hidden" id="resmid" name="resmid" value="">
                <button type="button" class=" btn btn-large widthfit" id="resheduleSubByuser">ok</button>
              </div>
            </form>
            
          </div>
        </div>
      </div>
    </div>  
    
-->
  <!-- modal start -->
    <div class="modal fade" id="reshedulecompleteByuser">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="conf_close">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/table-rebook-icon-popup.svg'); ?>">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2; display: inline-block;">You have successfully rescheduled this booking. An Email has been sent to the Users email address</p>
             <button type="button" class=" btn btn-large widthfit" data-dismiss="modal">ok</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="CancelReshedule">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="conf_close">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/table-rebook-icon-popup.svg'); ?>">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2; display: inline-block;">
              Deine Buchung bei <span id="s_salon"></span> kann leider nicht mehr durch styletimer verlegt oder storniert werden, da dein Termin in weniger als <span id="s_hr"></span> beginnt.
              <br/><br/>
              Bitte kontaktiere den Salon direkt unter: <span id="s_mobile"></span>
            </p>
             <button type="button" class=" btn btn-large widthfit" data-dismiss="modal">Ok</button>
          </div>
        </div>
      </div>
    </div>
   

<?php $this->load->view('frontend/common/footer'); ?>

<script type="text/javascript">
	var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('.datepicker').datepicker({
         uiLibrary: 'bootstrap4',
         minDate:today
       });
  $(document).on('click','#chg_date',function () {
      $('.today gj-cursor-pointer')
        .css('background-color', '#000000');
    });
    
  
  
 $(document).on('change','.chg_date_u',function(){
	  gettimeslotForReshedule_u();
});

function gettimeslotForReshedule_u(){
	
	   var date    = $('input[name="chg_date"]:checked').val();
       var reseid  = $("#reseid").val();
	   var bkid    = $("#reSchedule_id").val();
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
	   // var urls=$('#select_url').val();
       $.post(base_url+"user/get_opning_hour",{date:date,eid:reseid,bk_id:bkid} , function( data ) {
		   //console.log(data);
             var obj = jQuery.parseJSON( data );
            if(obj.success=='1'){
				$("#time_err").html('');
				$("#dropdownTime").html(obj.html);
         if($('input[name="chg_time"]:checked').length!=0){
            var heith=0;
            $(".select-time-price").each(function(){
              var heit=$(this).height();
              
               if($(this).hasClass("selected_time")){
                 //alert(heit);
                return false;
                }
               else{
                heith=heith+heit; 
                 } 
              
              });  
            //alert(heith);
             $("#dropdownTime").animate({scrollTop:heith}, 500, 'swing', function() { 
             
             });
           }
        
			}else{
				$("#time_err").html(obj.message);
				//$(".alert_message").css('display','block');
				
				//location.reload();
			
			  }
			
  	        });
	   unloading();
	
  }	 
   //~ $(document).on('blur','#chg_date',function () {
    //~ setTimeout(function(){ 
		   //~ var date   = $("#chg_date").val();
           //~ var reseid = $("#reseid").val();
		   //~ var bkid   = $("#reSchedule_id").val();
		  //~ loading();
	   //~ // var urls=$('#select_url').val();
       //~ $.post(base_url+"user/get_opning_hour",{date:date,eid:reseid,bk_id:bkid} , function( data ) {
		   //~ //console.log(data);
             //~ var obj = jQuery.parseJSON( data );
            //~ if(obj.success=='1'){
				//~ $("#date_err").html('');
				//~ $("#chg_time").html(obj.html);
			//~ }else{
				//~ $("#date_err").html(obj.message);
				//~ //$(".alert_message").css('display','block');
				
				//~ //location.reload();
			
			  //~ }
			
  	        //~ });
	   //~ unloading();
	   //~ },500);
	  //~ }); 
	  
$(document).on('click','.resheduleByuser', function(){
	    
	    var id = $(this).attr('id');
		var eid = $(this).attr('data-eid');
		var mid = $(this).attr('data-mid');
		//~ $("#chg_date").val('');
		$("#date_err").html('');	   
		$("#time_err").html('');		
		//~ $('#reSchedule_id').val(id);
		//~ $('#reseid').val(eid);
		//~ $('#resmid').val(mid);
	
	    loading();	
		$.ajax({
				url: base_url+"user/reshedule_form",
				type: "POST",
				data: {emp_id:eid,book_id:id,merchant_id:mid},
				success: function (data) {
				var obj = jQuery.parseJSON( data );
				  if(obj.success == 1){
					
					$("#frmReshedule").html(obj.html); 
					
					$("#service-reschedule-table").modal('show'); 
					var slideIntil=$('input[name="chg_date"]:checked').attr('data-slide');
            var it = parseInt(slideIntil);
					
						$('.slider-wick-slick').slick({
							dots: false,
							infinite: false,
							speed: 500,
							slidesToShow: 1,
							slidesToScroll:1,
							autoplay: false,
							autoplaySpeed: 2000,
							arrows: true,
							initialSlide:0,
							variableWidth: true,
							
						});
            $('.slider-wick-slick').slick('slickGoTo',it);  
					  gettimeslotForReshedule_u();
					
					}
					else
					{
						$("#date_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+obj.msg);
					}
					unloading();
				}
					
			}); 
	
		//~ var id = $(this).attr('id');
		//~ var eid = $(this).attr('data-eid');
		//~ var mid = $(this).attr('data-mid');
		//~ $("#chg_date").val('');
		//~ $("#chg_date_err").html('');	   
		//~ $("#chg_time_err").html('');
		
		//~ $('#reSchedule_id').val(id);
		//~ $('#reseid').val(eid);
		//~ $('#resmid').val(mid);
		//~ $("#bookingrescheduleByuser").modal('show');
	});	   

$(document).on('click','.user_reviewpopup', function(){
      
    var id = $(this).attr('id');  
    
    loading();  
    $.ajax({
        url: base_url+"user/get_review_popup",
        type: "POST",
        data: {id:id},
        success: function (data) {
        var obj = jQuery.parseJSON( data );
        if(obj.success == 1){
          $("#addrratingreviewForm").html(obj.html); 
          $("#reting_review").modal('show'); 
          
          }
          unloading();
        }
          
      }); 
  
  });


 $(document).on('click','#saveReviewRatingUsers',function(){
     //alert('sf');
      var token =true;
      if($('[name="rating"]:checked').length == 0){
      $("#rating_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte Sterne-Bewertung wählen'); 
       token =false;
    }
    else if($('input[name=rating]:checked').val() <= 3 && $('#txtreview').val() ==''){
      $("#rating_text").html('<i class="fas fa-exclamation-circle mrm-5"></i> Bitte Bewertung schreiben'); 
       token =false;
       $("#rating_err").html('');
    }
    else{ $("#rating_text").html('');
         $("#rating_err").html('');
     }
    
    if(token == true){
      $('.widthfit').attr('disabled',true);
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
      //~ var urls=$('#select_url').val();
       $.post(base_url+"booking/review", $( "#frmReview" ).serialize() , function( data ) {
         //console.log(data);
         var obj = jQuery.parseJSON( data );
        unloading();
        if(obj.success=='1'){         
           Swal.fire(
                'Review',
                'Review has been succesfully saved.',
                'success'
              );
             setTimeout(function() {
              location.reload();
            }, 1000);          
        }
         
          });
  
    }
                 
   });


  <?php if(!empty($_GET['bid'])){ ?>

     var id = "<?php echo $_GET['bid']; ?>";
        getBokkingDetail(id);
     <?php } ?>

</script>
