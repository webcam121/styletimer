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
.my-table .table td, .my-table .table th {
  padding: 15px 5px;
}
.alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0{
  top:-113px !important;
}
</style>
<div style="display:none;" id="b_list_page"></div>
<!-- start mid content section-->
    <section class="pt-84 clear user_booking_list_section1">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-60 relative">
                <div class="alert alert-danger absolute vinay top w-100 mt-15 alert_message alert-top-0" id="error_message" style="display: none; top:-60px;">
                  <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>
                  <span id="alert_message"></span>
                </div>
              <div class="pt-20 pb-20 pl-30 relative top-table-droup">
                  <span class="color999 fontfamily-medium font-size-14">Zeige</span>
                  <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-20"> 
                    <button data-toggle="dropdown" style="line-height: 30px;" class="btn btn-default dropdown-toggle mss_sl_btn "><?php if(isset($_GET['limit'])){ echo $_GET['limit']; }else{ echo '10'; } ?></button>
                    <ul class="dropdown-menu mss_sl_btn_dm widthfit80 noclose">
                      <li class="radiobox-image">
                        <input type="radio" id="id_14" name="cc" class="shortEmpbook" value="10">
                        <label for="id_14">10</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_15" name="cc" class="shortEmpbook" value="20">
                        <label for="id_15">20</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_16" name="cc" class="shortEmpbook" value="30">
                        <label for="id_16">30</label>
                      </li>
                    </ul>
                </div>        
              </div>
            <?php if(!empty($booking)){ ?>
              <div class="my-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th class="text-left" style="padding-left:30px;"><?php echo $this->lang->line('Customer'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Date'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Time'); ?></th>
                      <th class="text-center">Salon</th>
                      <th class="text-center">Behandlung</th>
                      <th class="text-center"><?php echo $this->lang->line('Duration'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Price'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Status'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Action'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                <?php  
              $pdata =  getEmpPermissionForDeletCancel($this->session->userdata('st_userid'));
              // echo '<pre>'; print_r($pdata); die; 
                foreach($booking as $row){                  
                        
                    if($row->profile_pic !=''){
						          $img=getimge_url('assets/uploads/users/'.$row->user_id.'/','icon_'.$row->profile_pic,'png');
					 }
					else{
						$img=base_url('assets/frontend/images/user-icon-gret.svg');
						$img1=base_url('assets/frontend/images/user-icon-gret.webp');
					 }

                        $time = new DateTime($row->booking_time);
                        $date = $time->format('d.m.Y');
                        $time = $time->format('H:i');

                        if($row->booking_type =='guest')
                          $us_name=$row->fullname;
                        else
                          $us_name=$row->first_name.' '.$row->last_name;
                    ?>
                    <tr>
                      <td class="font-size-14 color666 fontfamily-regular booking_row_emp" id="<?php echo url_encode($row->id); ?>">
                        <div style="width: max-content">
                          <picture class="mr-2 width30">
                            <img src="<?php echo $img; ?>" style="border-radius: 50%;" class="width30">
                          </picture>
                          <span class="mb-0 display-ib"><?php echo $us_name; ?></span>
                        </div>
                      </td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row_emp" id="<?php echo url_encode($row->id); ?>"><?php echo $date; ?></td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row_emp" id="<?php echo url_encode($row->id); ?>"><?php echo $time." Uhr"; ?></td>
                      <td class="font-size-14 color666 fontfamily-regular booking_row_emp" id="<?php echo url_encode($row->id); ?>"><p class="overflow_elips mb-0"><?php echo $row->business_name; ?></p></td>
                      <td class="font-size-14 color666 fontfamily-regular booking_row_emp" id="<?php echo url_encode($row->id); ?>"><p class="overflow_elips mb-0"><?php echo get_servicename($row->id); ?></p></td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row_emp" id="<?php echo url_encode($row->id); ?>"><?php echo $row->total_minutes; ?> Min.</td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row_emp" id="<?php echo url_encode($row->id); ?>"><?php echo $row->total_price; ?> €</td>
                       <td class="text-center font-size-14 fontfamily-regular booking_row_emp" id="<?php echo url_encode($row->id); ?>">
                        <?php $cls=''; if($row->status =='confirmed')
                                    $cls='conform';
                               else if($row->status =='cancelled')
                                    $cls='cencel';
                               else if($row->status =='completed')
                                    $cls='completed';
                               else if($row->status =='no show')
                                    $cls='cencel';
                        ?>
                        <span id="CssStatus_<?php echo $row->id; ?>" class="<?php echo $cls; ?>"><?php echo $this->lang->line(ucfirst($row->status)); ?></span>
                      </td>
                      <td class="text-center">                        
                        <div class="dropdown">
                          <div class="" data-toggle="dropdown">
                            <img src="<?php echo base_url('assets/frontend/images/table-more-icon.svg'); ?>">
                          </div>
                          <div class="dropdown-menu widthfit">
							  
							   <a href="#" class="dropdown-item color666 font-size-14 fontfamily-regular booking_row_emp" id="<?php echo url_encode($row->id); ?>" data-href="#" ><?php echo $this->lang->line('View'); ?></a>
                            <?php if($row->status == 'confirmed' && $pdata->allow_emp_to_delete_cancel_booking==1){
                                if(strtotime($row->booking_time) > strtotime(date('Y-m-d H:i:s'))){  
                             ?>
                            <div id="divStatus_<?php echo $row->id; ?>"><span class="dropdown-item color666 font-size-14 fontfamily-regular booking_cancels" style="cursor:pointer;" id="<?php echo url_encode($row->id); ?>"><?php echo $this->lang->line('Cancel'); ?></span></div>
                            <?php } 
                            } ?>                           
                           
                           <?php if($pdata->allow_emp_to_delete_cancel_booking==1){
                            if(strtotime($row->booking_time) > strtotime(date('Y-m-d H:i:s'))  && $row->status != 'completed' && $row->status != 'cancelled' && $row->status != 'no show'){ 								 
                              echo '<a class="dropdown-item color666 font-size-14 fontfamily-regular reSchedule_book" href="#" data-toggle="modal" data-eid="'.url_encode($row->employee_id).'" id="'.url_encode($row->id).'">'.$this->lang->line('Reschedule').'</a></div>';
                             } } ?>
                            
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
              </div>
               <?php }else{ ?>
                   <div class="text-center" style="padding-bottom: 45px;">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;"><?php echo $this->lang->line('dont_any_booking'); ?></p></div>
                <?php } ?>
            </div>
          </div>
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
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
          <picture class="">
            <source srcset="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.webp'); ?>" type="image/webp">
            <source srcset="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>" type="image/svg">
            <img src="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>">
          </picture>
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">Are you sure you really want to cancel this booking?  </p>
            <input type="hidden" id="bookingid" name="bookingid" value="">
            <button type="button" id="cancel_booking" class="btn btn-large widthfit">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->

<?php $this->load->view('frontend/common/footer'); ?>
