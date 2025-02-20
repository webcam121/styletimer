<style type="text/css">
.modal-body.pt-3.mb-0.pl-3.pr-3 .relative .color999 p,
.modal-body.pt-3.mb-0.pl-3.pr-3 .relative .color999 p span,
.modal-body.pt-3.mb-0.pl-3.pr-3 .relative .color999 p b{
  color:#999 !important;
  font-family: 'Poppins-Regular';
  font-size: 0.875rem;
}
.showmorePara2{
  display:block !important;
}
span.showmoreText2{
  color:#666666 !important;
}
.duration-update-btn {
  background: #00b3bf !important;
  border: 1px solid #00b3bf !important;
  color: #ffffff !important;
  height: 36px !important;
  border-radius: 0px !important;
  line-height: 36px !important;
}
.btn-duration {
  width: 20px;
  height: 20px;
  padding: 0px !important;
  text-align: center;
  display: inline-flex;
  justify-content: center;
  align-items: center;

  background: #00949d;
  color: #fff;
  border: none;
  text-align: center;
  font-size: 20px;
  border-radius: 0.25rem;
  cursor: pointer;
}
.sub-duration > td {
  padding: 2px !important;
}
.service_more > td {
  padding-bottom: 0px !important;
}
</style>
<?php         
if(!empty($booking_detail)){ 
				 
  $time = new DateTime($main[0]->booking_time);
  $date = $time->format('d.m.Y');
  $time = $time->format('H:i');

  $uptime  = new DateTime($main[0]->updated_on);
  $up_date = $uptime->format('d.m.Y');
  $uptime  = $uptime->format('H:i');
  $access= $this->session->userdata('access');
  if($access == 'user')
    $link='user/all_bookings';
  else if($access =='marchant')
    $link='merchant/booking_listing';
  else if($access =='employee')
    $link='employee/dashboad';
  else
    $link=''; 
?>
<?php 
  $permissions = getEmpPermissionForDeletCancel($this->session->userdata('st_userid'));
  $userAccess = ($access == 'marchant' || ($access == 'employee' && $permissions->allow_emp_to_delete_cancel_booking == 1));
  $emp_url="";
?>
<div class="relative">	
  <div class="modal-header-new">
    <div class="absolute right top mt-0 mr-0">
      <button type="button" style="background: transparent;" class="btn" data-toggle="modal" id="#conf_close">
        <a href="javascript:void(0)" data-dismiss="modal" class="crose-btn font-size-30 color333 a_hover_333">
          <picture class="" style="width: 22px; height: 22px;">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp')?>" type="image/webp" class="" style="width: 22px; height: 22px;">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png') ?>" type="image/png" class="" style="width: 22px; height: 22px;">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png') ?>" class="" style="width: 22px; height: 22px;">
          </picture>
        </a>
      </button>
    </div>

    <h3 class="font-size-20 color333 fontfamily-medium mb-0 text-center">Termindetails</h3>
  </div>
					
  <div class="pt-40">
    <div class="row">
		<?php   $this->load->view('frontend/common/alert');
		
    $emp_url="";
                 
    if($main[0]->booking_type == "guest"){
      $us_name = $main[0]->fullname;
      $notes = $main[0]->walkin_customer_notes;
    }
    else{
      $us_name = $booking_detail[0]->first_name.' '.$booking_detail[0]->last_name;
      $notes   = $booking_detail[0]->notes;
      if($this->session->userdata('access') =='marchant' && $booking_detail[0]->status !='deleted')
        $emp_url = base_url("profile/clientview/").url_encode($main[0]->userid);
    }
    ?>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
      <?php  if($booking_detail[0]->profile_pic !=''){
                $img=getimge_url('assets/uploads/users/'.$booking_detail[0]->id.'/','icon_'.$booking_detail[0]->profile_pic,'webp');
                $imgp=getimge_url('assets/uploads/users/'.$booking_detail[0]->id.'/','icon_'.$booking_detail[0]->profile_pic,'png');
              }
            else{
                $img=base_url('assets/frontend/images/user-icon-gret.svg');
                $imgp=base_url('assets/frontend/images/user-icon-gret.svg');
            }
      ?>
                  
                  
      <picture class="conform-img display-ib mr-3">
        <img style="border-radius: 50%;" src="<?php echo $imgp; ?>" type="image/png" class="conform-img display-ib mr-3">
      </picture>
                  
      <div class="relative display-ib vertical-middle">
        <h3 class="fontfamily-medium color333 font-size-18 mb-1 mt-1 <?php echo ($emp_url !='')?'editCust':''; ?>" data-bid="<?php echo url_encode($main[0]->id) ?>"  data-id="<?php if(!empty($main[0]->userid)) echo url_encode($main[0]->userid);  ?>">
          <?php echo $us_name; if($booking_detail[0]->status =='deleted'){ ?> 
            <span style="color:#FC6076;font-size: 12px;">
          </span><?php } ?>
        </h3>
        <?php if($booking_detail[0]->mobile && $access == 'employee' && $permissions->allow_emp_to_delete_cancel_booking == 1){ ?> 
        <p class="mt-0 mb-1 overflow_elips" style="width: 100%;">
          <img src="https://www.styletimer.de/assets/frontend/images/orange-call24.svg" class="width24v mr-2">
          <span class="font-size-14 color333 fontfamily-regular"><?php echo $booking_detail[0]->mobile;?></span>
        </p>
        <?php } ?>
        <?php if($main[0]->booking_type == "guest"){ ?>
          <?php if(isset($main[0]->guestphone) && ($main[0]->guestphone!='')) { ?> 
            <div class="mb-1"><img src="<?php echo base_url('assets/frontend/images/orange-call24.svg')?>" class="width24v mr-2"><?php echo $main[0]->guestphone; ?></div>
          <?php   }  ?>
          <?php if(isset($main[0]->guestemail) && ($main[0]->guestemail!='')) { ?>  
            <div class="mb-1"><img src="<?php echo base_url('assets/frontend/images/orange-envlop24.svg')?>" class="width24v mr-2"><?php echo $main[0]->guestemail; ?></div>
          <?php   }  ?>
        <?php   }  ?>     
        <?php 
          $sids=array();
          if(!empty($all_service)){
            foreach($all_service as $ser){ 
              $sids[]=$ser->subcategory_id;
              }
              $servids=implode(',',$sids);
          }
          else
            $servids='';

          ?>
        <?php
          $cls='';
          if($main[0]->status =='cancelled')
                $cls='btn-danger';
            else if($main[0]->status =='completed' || $main[0]->status =='confirmed')
                $cls='bgsuccess';
            else if($main[0]->status =='no show')
                $cls='btn-danger';
        ?>
        <?php
          if($main[0]->statusUser=='deleted'){ ?>
            <p style="color:red;margin-bottom: 7px;">(Benutzer existiert nicht mehr)</p>
        <?php } ?>

        <a href="javascript:void(0)" class="border-radius4 colorwhite <?php echo $cls; if($main[0]->status=='completed') echo ' b-complete-new-status'; elseif($main[0]->status=="cancelled") echo ' b-cancel-new-status'; ?> pl-2 pr-2 pt-1 pb-1 fontfamily-regular font-size-10 display-ib" style="cursor: default;">
          <?php echo $this->lang->line('book_status_'.str_replace(' ','_',$main[0]->status)); ?>
        </a>
                    
        <?php                    
        if($main[0]->reason !="") { ?>
            <div class="pt-1">
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="relative">
                  <p class="text-lines-overflow-1 font-size-14 color333 fontfamily-regular mb-1" style="-webkit-line-clamp:2;"><?php echo $main[0]->reason; ?></p>
                    </div>
                  </div>
              </div>
            </div>
        <?php } ?>
                          
      </div>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
      <div class="mobile-mt-20 mb-4">
        <p class="mb-1 "><?php echo $this->lang->line('Employee'); ?> : <span class=""><?php echo $main[0]->first_name; ?><span></p>
        <p class="mb-1 "><?php echo $this->lang->line('Booking_Id'); ?> : <span class=""><?php echo $main[0]->book_id; ?><span></p>
        <p class="mb-1 "><?php echo $this->lang->line('Booking_Date1'); ?>  : <span class=""><?php echo $date.' - '.$time." Uhr"; ?><span></p>
        <p class="mb-1 "><?php echo $this->lang->line('Booking_Made1'); ?>  : <span class=""><?php echo date('d.m.Y - H:i',strtotime($main[0]->created_on))." Uhr"; ?><span></p>
        <?php if($main[0]->status =='completed'){ ?>
        <p class="mb-1 "><?php echo $this->lang->line('Completed_Date'); ?> : <span class=""><?php echo $up_date.' '.$uptime; ?><span></p>
        <?php  }  ?>

      </div>
    </div>
  </div>
</div>
<div class="comform-table ">
  <table class="w-100 text-center">
    <thead>
      <tr class="border-b">
        <th class="pb-1 text-left" style="float: left !important;"><?php echo $this->lang->line('Service'); ?> </th>
        <th class="pb-1"><?php echo $this->lang->line('Minutes1'); ?> </th>
        <th class="pb-1"><?php echo $this->lang->line('Discount1'); ?> </th>
        <th class="pb-1" style="float: right;"><?php echo $this->lang->line('Price1'); ?>  </th>
      </tr>
    </thead>
    <tbody>
      <?php $min=$dis=$prc=0;
      $ser_name=$service_nm=''; 
      $pricestartoption ="";
      foreach($booking_detail as $row){
				if($row->price_start_option=='ab'){
          if(empty($pricestartoption)){
            $pricestartoption =$row->price_start_option;
          }	
        }
							 
        $sub_name=get_subservicename($row->service_id); ?>
      <tr class="<?php echo $row->service_type == 1? 'service_more':''?>">
        <td class="font-size-14 text-left color666" style="float: left !important;">
          <?php
            if($sub_name == $row->service_name)
              echo $service_nm=$row->service_name;
            else
              echo $service_nm=$sub_name.' - '.$row->service_name; 

            $ser_name=$ser_name.','.$service_nm;
          ?>
        </td>
        <td class="font-size-14 color666">
          <?php if ((date('Y-m-d', strtotime($main[0]->booking_time)) >= date('Y-m-d') && $row->service_type == 0) && ($main[0]->status != 'cancelled' && $main[0]->status != 'completed') && $userAccess) { ?>
            <button class="btn-duration" type="button" onclick="decreaseDuration('<?php echo $row->bid;?>')">
              <i class="fas fa-minus colorwhite" style="font-size: 12px"></i>
            </button>
          <?php } ?>
          <span class="<?php echo $row->service_type ? '' : 'service_duration'?>" id="duration<?php echo $row->bid;?>" style="width: 25px; display: inline-block;">
            <?php
              echo $row->duration - $row->buffer_time;
            ?>
          </span>
          <?php if ((date('Y-m-d', strtotime($main[0]->booking_time)) >= date('Y-m-d') && $row->service_type == 0) && ($main[0]->status != 'cancelled' && $main[0]->status != 'completed') && $userAccess) { ?>
            <button class="btn-duration" type="button" onclick="increaseDuration('<?php echo $row->bid;?>')">
              <i class="fas fa-plus colorwhite" style="font-size: 12px"></i>
            </button>
          <?php } ?>
          Min.
        </td>
        <td class="font-size-14 color666"><?php if(!empty($row->discount_price)) echo price_formate($row->discount_price).' €'; else echo '-'; ?></td>
        <td class="text-right font-size-14 color666"><?php if($row->price_start_option=='ab') echo $row->price_start_option.' '; echo price_formate($row->price); ?> €</td>
      </tr>
      <?php if ($row->service_type == 1 && ($main[0]->status != 'cancelled' && $main[0]->status != 'completed')&& date('Y-m-d', strtotime($main[0]->booking_time)) >= date('Y-m-d') && $userAccess) { ?>
        <tr class="sub-duration">
          <?php
            // echo (date('Y-m-d', strtotime($main[0]->booking_time));
          ?>
          <td style="font-size:14px; text-align:right">Servicezeit</td>
          <td style="font-size:14px;">
            <button class="btn-duration" type="button" onclick="decreaseDuration('<?php echo $row->bid;?>' + '_1')">
              <i class="fas fa-minus colorwhite" style="font-size: 12px"></i>
            </button>
            <span class="service_duration" id="duration<?php echo $row->bid;?>_1" style="width: 25px; display: inline-block;">
              <?php echo $row->setuptime; ?>
            </span>
            <button class="btn-duration" type="button" onclick="increaseDuration('<?php echo $row->bid;?>' + '_1')">
              <i class="fas fa-plus colorwhite" style="font-size: 12px"></i>
            </button>
            Min.
          </td>
          <td></td>
          <td></td>
        </tr>
        <tr class="sub-duration">
          <td style="font-size:14px; text-align:right">Einwirkzeit</td>
          <td style="font-size:14px;">
            <button class="btn-duration" type="button" onclick="decreaseDuration('<?php echo $row->bid;?>' + '_2')">
              <i class="fas fa-minus colorwhite" style="font-size: 12px"></i>
            </button>
            <span class="service_duration" id="duration<?php echo $row->bid;?>_2" style="width: 25px; display: inline-block;">
              <?php echo $row->processtime; ?>
            </span>
            <button class="btn-duration" type="button" onclick="increaseDuration('<?php echo $row->bid;?>' + '_2')">
              <i class="fas fa-plus colorwhite" style="font-size: 12px"></i>
            </button>
            Min.
          </td>
          <td></td>
          <td></td>
        </tr>
        <tr class="sub-duration">
          <td style="font-size:14px; text-align:right;padding-bottom: 13px !important">Abschließen</td>
          <td style="padding-bottom: 13px !important; font-size:14px;">
            <button class="btn-duration" type="button" onclick="decreaseDuration('<?php echo $row->bid;?>' + '_3')">
              <i class="fas fa-minus colorwhite" style="font-size: 12px"></i>
            </button>
            <span class="service_duration" id="duration<?php echo $row->bid;?>_3" style="width: 25px; display: inline-block;">
              <?php echo $row->finishtime; ?>
            </span>
            <button class="btn-duration" type="button" onclick="increaseDuration('<?php echo $row->bid;?>' + '_3')">
              <i class="fas fa-plus colorwhite" style="font-size: 12px"></i>
            </button>
            Min.
          </td>
          <td></td>
          <td></td>
        </tr>
      <?php } ?>
      <?php if ($row->has_buffer != 0 && ($main[0]->status != 'cancelled' && $main[0]->status != 'completed')&& date('Y-m-d', strtotime($main[0]->booking_time)) >= date('Y-m-d') && $userAccess) { ?>
        <tr class="sub-duration">
          <?php
            // echo (date('Y-m-d', strtotime($main[0]->booking_time));
          ?>
          <td style="font-size:14px; text-align:right">Puffer</td>
          <td style="font-size:14px;">
            <button class="btn-duration" type="button" onclick="decreaseBufferDuration('<?php echo $row->bid;?>' + '_4')">
              <i class="fas fa-minus colorwhite" style="font-size: 12px"></i>
            </button>
            <span class="service_duration" id="duration<?php echo $row->bid;?>_4" style="width: 25px; display: inline-block;">
              <?php echo $row->buffer_time; ?>
            </span>
            <button class="btn-duration" type="button" onclick="increaseBufferDuration('<?php echo $row->bid;?>' + '_4')">
              <i class="fas fa-plus colorwhite" style="font-size: 12px"></i>
            </button>
            Min.
          </td>
          <td></td>
          <td></td>
        </tr>
      <?php
      }
      ?>
      <?php   $min=$min+$row->duration;
              if ($access == 'user') {
                $min = $min - $row->buffer_time;
              }
              $dis=$dis+$row->discount_price;
              $prc=$prc+$row->price; 
      }  ?>
      <tr class="border-t border-b">
        <td class=" font-size-16 color333"></td>
        <td class="fontfamily-semibold font-size-16 color333">
          <span id="total_duration">
            <?php echo $min; ?>
          </span>
          Min.
        </td>
        <td class="fontfamily-semibold font-size-16 color333"><?php echo price_formate($dis); ?> €</td>
        <td class="text-right fontfamily-semibold font-size-16 color333 "><?php if($pricestartoption=='ab') echo $pricestartoption.' '; echo price_formate($prc); ?> €</td>
      </tr>
      <tr class="">
        <td class="fontfamily-medium font-size-16 pb-0 text-left" style="float: left;">enthaltener Rabatt</td>
        <td class="fontfamily-medium font-size-16 pb-0"></td>
        <td class="fontfamily-medium font-size-16 pb-0"></td>
        <td class="text-right fontfamily-medium font-size-16 pb-0"><?php if(!empty($dis) || ($prc - $main[0]->total_price) > 0) echo price_formate($dis + $prc - $main[0]->total_price).' €'; else echo '-'; ?> </td>
      </tr>
      <tr class=" border-b">
        <td class="fontfamily-semibold font-size-20 color333 pb-3 text-left" style="float: left;">
          <?php echo $main[0]->status == 'completed' ? 'gezahlter Betrag' : $this->lang->line('Payable_Amount'); ?>
        </td>
        <td class="fontfamily-semibold font-size-20 color333 pb-3"></td>
        <td class="fontfamily-semibold font-size-20 color333 pb-3"></td>
        <td class="text-right fontfamily-semibold font-size-20 color333 pb-3"><?php if($pricestartoption=='ab' && $main[0]->status != 'completed') echo $pricestartoption.' '; echo price_formate($main[0]->total_price); //if(!empty($dis)){ echo $prc-$dis; }else{ echo $prc; } ?> €</td>
      </tr>
    </tbody>
  </table>
</div>
  <?php
                   $html ="";
                 if(($booking_detail[0]->status !='deleted' || $main[0]->booking_type == "guest") && $this->session->userdata('access') !='employee')  
                $html.='<a href="'.base_url('merchant/dashboard/'.url_encode($main[0]->id)).'?id='.url_encode($main[0]->employee_id).'&option=rebook" class="dropdown-item">'.$this->lang->line('Rebook').'</a>';
                
                if(strtotime($main[0]->booking_time) > strtotime(date('Y-m-d H:i:s'))  && $main[0]->status != 'cancelled' && ($booking_detail[0]->status !='deleted' || $main[0]->booking_type == "guest")){   
                    $html.='<a id="cancel_booking" data-uid="'.url_encode($main[0]->userid).'" data-id="'.url_encode($main[0]->id).'" class="dropdown-item " href="javascript:void(0)">'.$this->lang->line('Cancel').'</a>';
                  } /*<a id="'.url_encode($main[0]->id).'" class="dropdown-item  booking_cancels" data-toggle="modal" data-target="#service-cencel-table" href="javascript:void(0)">Cancel</a>*/
                 if($main[0]->status != 'no show' && $main[0]->status !='cancelled' && strtotime($main[0]->booking_time) < strtotime(date('Y-m-d H:i:s')) && $main[0]->status !='completed'){ 
                    $html.='<a data-id="'.url_encode($main[0]->id).'" id="noshow_booking" class="dropdown-item " href="javascript:void(0);" href="javascript:void(0)">'.$this->lang->line('No_show').'</a>';
                   } /*<a id="'.url_encode($main[0]->id).'" class="dropdown-item  noshow_book" data-toggle="modal" data-target="#service-noshow-table" href="javascript:void(0);" href="javascript:void(0)">No Show</a>*/
                  if(strtotime($main[0]->booking_time) > strtotime(date('Y-m-d H:i:s'))  && $main[0]->status != 'completed' && $main[0]->status != 'cancelled' && $main[0]->status != 'no show'  && ($booking_detail[0]->status !='deleted' || $main[0]->booking_type == "guest")){ 
                    $html.='<a id="'.url_encode($main[0]->id).'" data-bid="'.url_encode($main[0]->id).'" class="dropdown-item reSchedule_book" data-eid="'.url_encode($main[0]->employee_id).'" href="javascript:void(0)">'.$this->lang->line('Reschedule').'</a>';
                   }
                   
                     $html.='<a id="'.url_encode($main[0]->id).'" data-uid="'.url_encode($main[0]->userid).'" class="dropdown-item  deleteBooking" data-url="'.base_url('rebook/delete_booking/'.url_encode($main[0]->id).'/dashboard').'" href="javascript:void(0)">'.$this->lang->line('Delete').'</a>';
				  
                    
                    ?>

              <!-- add new droup down -->
              <div class="d-flex mr-0 mt-3 pb-3 ml-0">				  
                <?php
                
                if($this->session->userdata('access') =='employee'){
					
					$html ="";
					 
					if(strtotime($main[0]->booking_time) > strtotime(date('Y-m-d H:i:s'))  && $main[0]->status != 'cancelled' && ($booking_detail[0]->status !='deleted' || $main[0]->booking_type == "guest")){   
						$html.='<a id="cancel_booking" data-uid="'.url_encode($main[0]->userid).'" data-id="'.url_encode($main[0]->id).'" class="dropdown-item " href="javascript:void(0)">'.$this->lang->line('Cancel').'</a>';
					  } 
					  
					   
					  if(strtotime($main[0]->booking_time) > strtotime(date('Y-m-d H:i:s'))  && $main[0]->status != 'completed' && $main[0]->status != 'cancelled' && $main[0]->status != 'no show'  && ($booking_detail[0]->status !='deleted' || $main[0]->booking_type == "guest")){ 
						$html.='<a id="'.url_encode($main[0]->id).'" data-bid="'.url_encode($main[0]->id).'" class="dropdown-item reSchedule_book" data-eid="'.url_encode($main[0]->employee_id).'" href="javascript:void(0)">'.$this->lang->line('Reschedule').'</a>';
					   }
				  
					
					 if($permissions->allow_emp_to_delete_cancel_booking==1){
					?>
					  <div class=" ml-auto">
                  <?php if($html !=''){ ?>
                   <div class="display-ib dropdown dropdownMoreOption">
                    <button class="btn widthfit dropdown-toggle" type="button" id="dropdownMoreOption" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $this->lang->line('More_Option'); ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMoreOption">
                    <?php echo $html; ?>
                    </div>
                  </div>
                  <?php }   ?>
                  <button class="btn widthfit duration-update-btn ml-2" id="update_service_duration" type="button" onclick="update_service_duration()">Änderungen speichern</button>
                 </div>
			  <?php	}	}
                
                 if($this->session->userdata('access') =='marchant'){ ?>
         <?php 
        
          if($booking_detail[0]->status=='deleted'){ ?>
          

        <?php  }else{ ?>
          <div class="d-flex ml-auto">
          <?php if($html !=''){ ?>
            <div class="display-ib dropdown dropdownMoreOption">
              <button class="btn widthfit dropdown-toggle" style="margin-left: 8px;" type="button" id="dropdownMoreOption" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo $this->lang->line('More_Option'); ?>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMoreOption">
                <?php echo $html; ?>
              </div>
            </div>
        <?php } ?>
               

                  <?php }   ?>
                  <?php if($booking_detail[0]->status=='deleted'){ ?>
                  <?php }else if($main[0]->status=='completed'){ ?>
					  <a href="<?php echo base_url("checkout/viewinvoice/".url_encode($main[0]->invoice_id)); ?>"> <button class="btn widthfit new-btn-compltate complete_book ml-2" style="background-color:#666666 !important;border: 1px solid #666666 !important;"><?php echo $this->lang->line('View_Invoice'); ?></button></a>
					  
					<?php }else if(strtotime($main[0]->booking_time) < strtotime(date('Y-m-d H:i:s')) 
                  && $main[0]->status != 'completed'){ ?>
                  <a class="click_closepop" 
          href="<?php echo base_url("checkout/process/".url_encode($main[0]->id)); ?>">
           <button class="btn widthfit new-btn-compltate complete_book ml-2"
            style="text-transform: initial;"  data-toggle="modal">
            <?php echo $this->lang->line('Complete_btn'); ?></button></a>
      <?php    } ?>
                  <button class="btn widthfit duration-update-btn ml-2" id="update_service_duration" type="button" onclick="update_service_duration()">Änderungen speichern</button>
                 </div>
                 <?php  } ?>
               </div>
              <!--end new froup down -->
            <?php
           
              if($notes !='' && $this->session->userdata('access')!='user'){ 
              $cut =substr_count( $notes, "\n" ); 
              $s_cut  =  strlen($notes); 
              if($cut >=0 || $s_cut > 150)
                $clss='';
              else
                $clss='hide';
              ?>
              <div class="around-15 pt-20 pb-10">
              <div class="row border-w pt-10 pb-10">
               <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="relative">
                      <p class="colorgreen fontfamily-medium font-size-16 mb-1" style="display:inline;"><?php echo $this->lang->line('customer_note'); ?></p>
                      <?php if ($userAccess) {?>
                      <p data-bid="<?php echo $main[0]->id?url_encode($main[0]->id):'';?>" data-noteid="<?php echo $booking_detail[0]->noteid?url_encode($booking_detail[0]->noteid):'';?>" data-uid="<?php echo $main[0]->userid;?>" class="editNoteClient1 colorgreen fontfamily-medium font-size-14 mb-1" style="display:inline;float:right;cursor:pointer;">
                        <img src="https://www.styletimer.de/assets/frontend/images/orange-noteped24.svg" width="20" height="20" class="vertical-top" style="cursor: pointer;">Notiz bearbeiten</p>
                      <?php } ?>
                      <div class="font-size-14 color999 relative showmorePara" style=""><?php echo nl2br($notes);//nl2br($notes);strip_tags ?>
                        <span class="showmoreText fontfamily-medium <?php echo $clss ?>"><?php echo 'mehr anzeigen'; ?></span>
                      </div>
                </div>
               </div>
              </div>
            </div>
            <?php  }
            else if (empty($notes) && $userAccess) {
              ?>
                <div class="around-15 pt-10 pb-20">
                    <div class="row border-w pt-10 pb-10">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <div class="relative">
                        <p data-bid="<?php echo $main[0]->id?url_encode($main[0]->id):'';?>" data-noteid="<?php echo $booking_detail[0]->noteid?url_encode($booking_detail[0]->noteid):'';?>" data-uid="<?php echo $main[0]->userid;?>" class="editNoteClient1 colorgreen fontfamily-medium font-size-14 mb-1" style="display:inline;cursor:pointer;">
                        <img src="https://www.styletimer.de/assets/frontend/images/orange-noteped24.svg" width="20" height="20" class="vertical-top" style="cursor: pointer;">Kundennotiz hinzufügen</p>
                      </div>
                    </div>
                    </div>
                  </div>
              <?php
              }
			//print_r($main[0]);
             if(!empty($main[0]->booknotes) && $this->session->userdata('access')!='user'){ 
                  $cut2 =substr_count( $main[0]->booknotes, "\n" ); 
                  $s_cut2  =  strlen($main[0]->booknotes); 
                  if($cut2 >= 0 || $s_cut2 > 150)
                    $clss2='';
                  else
                    $clss2='hide';
              ?>
              <div class="around-15 pt-10 pb-20">
              <div class="row border-w pt-10 pb-10">
               <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="relative">
                      <p class="colorgreen fontfamily-medium font-size-16 mb-1" style="display:inline;"><?php echo $this->lang->line('booking_note'); ?></p>
                      <?php if ($userAccess) {?>
                      <p data-bookingid="<?php echo url_encode($main[0]->id);?>" class="editBookingNote colorgreen fontfamily-medium font-size-14 mb-1" style="display:inline;float:right;cursor:pointer;">
                        <img src="https://www.styletimer.de/assets/frontend/images/orange-noteped24.svg" width="20" height="20" class="vertical-top" style="cursor: pointer;">Notiz bearbeiten</p>
                      <?php } ?>
                      <div class="font-size-14 color999 relative showmorePara2" style="-webkit-line-clamp:2;"><?php echo nl2br($main[0]->booknotes); ?>
                      <span class="showmoreText2 fontfamily-medium <?php echo $clss2; ?>"><?php echo 'mehr anzeigen'; ?></span>
             </div>
                </div>
               </div>
              </div>
            </div>
            <?php  } 
            else if(!empty($main[0]->booknotes) && $main[0]->created_by == $this->session->userdata('st_userid')){ 
			 //print_r($main[0]->booknotes);
              $cut2 =substr_count( $main[0]->booknotes, "\n" ); 
			  //print_r($cut2);
                  $s_cut2  =  strlen($main[0]->booknotes); 
				 // print_r($s_cut2);
                  if($cut2 >=0  || $s_cut2 > 150)
                    $clss2='';
                  else
                    $clss2='hide';
                  ?>
                <div class="around-15 pt-10 pb-20">
              <div class="row">
               <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="relative">
                      <P class="colorgreen fontfamily-medium font-size-16 mb-2"><?php echo $this->lang->line('booking_note'); ?></P>
                      <p class="font-size-14 fontfamily-medium color999 relative showmorePara2" style="-webkit-line-clamp:2;"><?php echo nl2br($main[0]->booknotes); ?></p>
                      <span class="showmoreText2 <?php echo $clss2; ?>"><?php echo 'mehr anzeigen'; ?></span>
                    </div>
                  </div>
              </div>
            </div>

              <?php }
              else if ((empty($main[0]->booknotes) && $userAccess)) {
              ?>
                <div class="around-15 pt-10 pb-20">
                  <div class="row border-w pt-10 pb-10">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="relative">
                      <p data-bookingid="<?php echo url_encode($main[0]->id);?>" class="editBookingNote colorgreen fontfamily-medium font-size-14 mb-1" style="cursor:pointer;">
                        <img src="https://www.styletimer.de/assets/frontend/images/orange-noteped24.svg" width="20" height="20" class="vertical-top" style="cursor: pointer;">Buchungsnotiz hinzufügen</p>
                    </div>
                  </div>
                  </div>
                </div>
              <?php
              }
                if($main[0]->status !='completed')
                    $rev_div='none';
                else if(isset($review->anonymous) && $review->anonymous== 1)
                    $rev_div='none';
                else
                    $rev_div='';

               ?>
            
              <div class="around-15 pt-20 pb-20" style="display: <?php echo $rev_div; ?>">
                <?php if($this->session->userdata('access') == 'user'){
                    if(empty($review))
                      $tit='Add';
                    else
                      $tit='View';
                }else
                  $tit='View'; ?>
                <div class="text-right" style="display: none;">
                  <a class="btn btn-large widthfit cursor_pointer relative"  data-toggle="collapse" data-target="#change_reting_reveiw" href="#"> <?php echo $tit; ?> Review <i class="fas fa-chevron-down ml-10"></i></a>
                </div>
                <?php if(empty($review)){ 
                  if($this->session->userdata('access') =='user' && $main[0]->status =='completed'){ ?>
                 <div class="text-left">
                  <a id="open_rate_popup" class=" cursor_pointer relative fontfamily-medium colororange a_hover_orange font-size-16"  data-toggle="modal" data-target="#reting_review" href="#">
                    <i class="fas fa-star colororange mr-1 font-size-16 display-ib"></i> 
                    <span class="ml-1 text-underline"> Bewertung </span>
                  </a>
                </div>
                <script type="text/javascript">
                  window.onload=function(){
                          document.getElementById("open_rate_popup").click();
                        };
                </script>
                <?php }
                 } 
                 else{ ?>

                 <div class="text-left">
                  <a class=" cursor-p relative fontfamily-medium colororange a_hover_orange font-size-16 collapsed"  data-toggle="collapse" data-target="#change_reting_reveiw" href="#">
                    <i class="fas fa-star colororange mr-1 font-size-16 display-ib"></i> 
                    <span class="ml-1 text-underline"> Bewertung </span>
                    <i class="fas fa-chevron-down ml-10"></i>
                  </a>
                </div>
                <div class="collapse show" id="change_reting_reveiw">
                  <div class="pt-30"> 
                    <p class="font-size-14 fontfamily-medium color333">
                     <?php if($review->anonymous == 1)
                        echo "Rated as Anonymous";
                      else
                        echo $us_name; ?>
                    </p>
                    <div class="relative mb-10">
                      <?php $rate=$review->rate;
                      for($i=0;$i < 5;$i++){
                        if($rate > $i){
                      ?><i class="fas fa-star colororange mr-2 font-size-24"></i>
                      <?php } else{ ?>
                        <i class="fas fa-star color999 mr-2 font-size-24"></i>
                      <?php }
                      } ?>
                    </div>

                   <div class="relative">
                        <p class="color666 fontfamily-medium font-size-14 text-lines-overflow-1 mb-3" style="-webkit-line-clamp:6; word-wrap: break-word;"><?php echo nl2br($review->review); ?></p>
                        <span class="fontfamily-regular color999 font-size-14">
                          <?php 
						 
								//   echo($month);
								   
							
								   $arr_days=['Monday'=>'Montag','Tuesday'=>'Dienstag','Wednesday'=>'Mittwoch','Thursday'=>'Donnerstag','Friday'=>'Freitag','Saturday'=>'Samstag','Sunday'=>'Sonntag'];
								   $arr_month=['January'=>'Januar','February'=>'Februar','March'=>'März','April'=>'April','May'=>'Mai','June'=>'Juni','July'=>'Juli','August'=>'August','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Dezember'];
								   
								   $dt = strtotime($review->created_on);
						           $month=date('F',strtotime($review->created_on));
						           //$day=date('l',strtotime($review->created_on)) ;
								  // $con_day=$arr_days[$day];
								   $con_mon=$arr_month[$month];
								   
								   $date=date('j. F \u\m G:i \U\h\r ', $dt);
										//$ger_date= str_replace($day,$con_day,$date);
										$con_all=str_replace($month,$con_mon,$date);
										echo $con_all;   
						  
                           
                           ?></span>

                        <?php $m_reply=getselect('st_review','review,created_on, merchant_id',array('review_id' =>$review->id,'created_by' => $review->merchant_id)); 
                          if(!empty($m_reply)){ 
                            $merchant_data=getselect('st_users','business_name',array('id' =>$m_reply[0]->merchant_id)); 
                        ?>

                        <h6 class="font-size-16 fontfamily-semibold color333 mt-3 mb-3">Antwort von <?php echo $merchant_data[0]->business_name;?></h6>
                        <?php foreach($m_reply as $rev){ ?>
                        <p class="color666 fontfamily-medium font-size-14 mb-0"><?php echo $rev->review; ?></p>
                        <span class="fontfamily-regular color999 font-size-14">
                          <?php $dts = strtotime($rev->created_on); 
                          $tmp = date('j. F \u\m G:i \U\h\r ', $dts);
                          echo str_replace($month,$con_mon,$tmp);?>
                        </span>
                        <?php } } ?>
                   </div>

                  </div>
                </div>
                 <?php } ?>
                
            </div>


            </div>
          </div>
          
        <?php } ?>

<script>

  $(".editBookingNote").on('click', function() {
    const bookid = $(this).data('bookingid');
    $("#booking-note-modal").modal('show');
    if (bookid) {
      $.ajax({
        type: 'post',
        url: base_url + "merchant/get_bookingnote",
        data: {
          bid: bookid
        },
        success: function(res) {
          var obj = jQuery.parseJSON(res);
          console.log(obj);
          $("#bookingnote_id").val(bookid);
          $("#mbnote").val(obj.note);
        }
      });  
    }
  });

  $("#bookingnotesave").on('click', function() {
    const bookid = $("#bookingnote_id").val();
    if (bookid) {
      $.ajax({
        type: 'post',
        url: base_url + "merchant/update_bookingnote",
        data: {
          bid: bookid,
          bnote: $("#mbnote").val(),
        },
        success: function(res) {
          $("#booking-note-modal").modal('hide');
          window.location.reload();
        }
      });  
    }
  });

  $(".editNoteClient1").on('click', function() {
    $("#add-noteFromPoup1").modal('show');
    var nid = $(this).attr('data-noteid');
    var uid = $(this).attr('data-uid');
    var bid = $(this).attr('data-bid');
    $.ajax({
      type: 'post',
      url: base_url + "merchant/get_clientusernote",
      data: {
        nid: nid,
        bid: bid,
      },
      success: function(res) {
        var obj = jQuery.parseJSON(res);
        $("#clientNotevalue1").val(obj.note);
        $(".fr-view").html(obj.note);
      }
    });
    $("#clientNotevalue1").attr('data-uid', uid);
    $("#clientNotevalue1").attr('data-bid', bid);
  });

  $('#savenotesBtnck1').on('click', function() {
    var upval = $("#clientNotevalue1").val();
    var uid = $("#clientNotevalue1").attr('data-uid');
    var bid = $("#clientNotevalue1").attr('data-bid');

    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    loading();
    var url = base_url + "profile/update_notes";
    $.post(url, {
      uid: uid,
      bid: bid,
      notes: upval
    }, function(data) {

      $("#add-noteFromPoup1").modal('hide');
      window.location.reload();
    });
  });

  $(document).ready(function() {
    $(function (){
      const editorInstance = new FroalaEditor('#clientNotevalue1', {
        enter: FroalaEditor.ENTER_P,
        key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
        placeholderText: null,
        language: 'de',
        events: {
          initialized: function () {
            const editor = this
            this.el.closest('form').addEventListener('submit', function (e) {
              //console.log(editor.$oel.val())
              e.preventDefault()
            })
          }
        }
      })
    });

    $("#update_service_duration").css("display", "none");
    $('.showmoreText').click(function(){
		//alert($(this).hasClass(' showText'));
        if($(".showmorePara").hasClass('showText')==false){
          $('.showmorePara').addClass('showText');
        }
        else{
          $('.showmorePara').removeClass('showText');
        }

        $(this).text(($(".showmorePara").hasClass('showText')==true) ? 'weniger anzeigen' : 'mehr anzeigen').fadeIn();
    });
    $('.showmoreText2').click(function(){
        if($(".showmorePara2").hasClass('showText2')==false)
          $('.showmorePara2').addClass('showText2');
        else
          $('.showmorePara2').removeClass('showText2');

        $(".showmoreText2").text(($(".showmorePara2").hasClass('showText2')==true) ? 'weniger anzeigen' : 'mehr anzeigen').fadeIn();
    });

  });    
  function decreaseDuration(id) {
    $("#update_service_duration").css("display", "inline-flex");
    const oldDuration = parseInt($("#duration" + id).text());
    if (oldDuration > 15) {
      const newDuration = Math.ceil(oldDuration / 15.0) * 15 - 15;
      const delta = newDuration - oldDuration;
      $("#duration" + id).html(newDuration);
      let index = id.indexOf('_');
      if (index != -1) {
        index = id.slice(0, index);
        $("#duration" + index).html(delta + parseInt($("#duration" + index).text()));
      }
      $("#total_duration").html(delta + parseInt($("#total_duration").text()));
    }
  }
  function increaseDuration(id) {
    $("#update_service_duration").css("display", "inline-flex");
    const oldDuration = parseInt($("#duration" + id).text());
    const newDuration = Math.floor(oldDuration / 15.0) * 15 + 15;
    const delta = newDuration - oldDuration;
    $("#duration" + id).html(newDuration);
    let index = id.indexOf('_');
    if (index != -1) {
      index = id.slice(0, index);
      $("#duration" + index).html(delta + parseInt($("#duration" + index).text()));
    }
    $("#total_duration").html(delta + parseInt($("#total_duration").text()));
  }
  function decreaseBufferDuration(id) {
    $("#update_service_duration").css("display", "inline-flex");
    const oldDuration = parseInt($("#duration" + id).text());
    if (oldDuration > 0) {
      const newDuration = Math.ceil(oldDuration / 5.0) * 5 - 5;
      const delta = newDuration - oldDuration;
      $("#duration" + id).html(newDuration);
      let index = id.indexOf('_');
      $("#total_duration").html(delta + parseInt($("#total_duration").text()));
    }
  }
  function increaseBufferDuration(id) {
    $("#update_service_duration").css("display", "inline-flex");
    const oldDuration = parseInt($("#duration" + id).text());
    const newDuration = Math.floor(oldDuration / 5.0) * 5 + 5;
    const delta = newDuration - oldDuration;
    $("#duration" + id).html(newDuration);
    let index = id.indexOf('_');
    $("#total_duration").html(delta + parseInt($("#total_duration").text()));
  }
  function update_service_duration() {
    var subservices = $(".service_duration");
    let dat = {};
    for (const service of subservices) {
      const id = $(service).attr('id').slice(8);
      if (!dat[parseInt(id)]) {
        dat[parseInt(id)] = [];
      }
      dat[parseInt(id)].push(parseInt($("#duration" + id).text()));
    }

    loading();
    $.ajax({
      type: "POST",
      url:base_url+"merchant/bookingtime_resize_by_popup",
      data:'details=' + JSON.stringify(dat),
      success: function (response) {
        unloading();
        var obj = $.parseJSON( response );
        if(obj.success==1){
            
            $("#booking-details-modal").modal('hide');
            
            if ($('#b_list_page').length) {
              window.location.reload();  
            }
            if ($('#calendar').length) {
              $('#calendar').fullCalendar('refetchEvents');
            }
        }else{
          revertFunc();
          Swal.fire(
            obj.msg,
            'warning'
          );
        }
        
      }
    });
  }
</script>
         
