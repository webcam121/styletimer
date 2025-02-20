<?php $this->load->view('frontend/common/header'); ?>

<style>
.alert_message{    
	margin-top: -50px !important;
   }
   .alert.alert-danger.absolute.vinay{
      z-index: 99;
      left: 0;
      right: 0 !important;
      top: 10px !important;
      height: auto !important;
   }
   .alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0{
    top: 10px !important;
   }
   .comform-table table tr td {
    padding: 13px 18px;
    white-space: unset;
}
</style>

<!-- start mid content section-->
    <section class="pt-84 clear user_booking_conform_section4" style="padding-top: 90px;">
      <div class="container relative">

        <div class="row">
         <?php if($detail->image =='')
                  $img = base_url('assets/frontend/images/noimage.png');
                else
                  $img = base_url('assets/uploads/banners/').$detail->id.'/crop_'.$detail->image;
          ?>
          <div class="col-12 col-sm-12 col-md-10 col-lg-9 col-xl-8 m-auto">
            <div class="bgwhite border-radius4 box-shadow1 relative mb-60 mt-60">
             <?php $this->load->view('frontend/common/alert12'); ?>
             <img src="<?php echo $img; ?>" class="img-fluid" style="width:100%;!important">
            <form id="selectFilters" method="get">
              <div class="around-30 pt-3">
                <div class="row">
                  <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <h3 class="font-size-24 color333 fontfamily-medium mt-3"><?php echo $detail->business_name; ?></h3>
                  </div>
                  <?php 
                    $times = new DateTime($_GET['date']);
                 	  $dates = $times->format('d M');
                 	  $days=date('l', strtotime($_GET['date']));
                    $formatter = new IntlDateFormatter( 
                      "de-DE", 
                      IntlDateFormatter::LONG, 
                      IntlDateFormatter::NONE, 
                      "Europe/Berlin", 
                      IntlDateFormatter::GREGORIAN, 
                      "EEEE', den' dd. MMMM" 
                    ); 
                  
                    $onDate = $formatter->format($times);
                    if(!empty($_GET['time'])){
                        $time=date('H:i:s',strtotime($_GET['time']));
                    }
                    else{
                        $time=date('H:i:s');
                    }
                  ?>
                  <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-right">
                    <div class="display-ib">
                      <h3 class="font-size-24 colororange fontfamily-semibold mb-0"><?php echo date('H:i', strtotime($_GET['time'])); ?> Uhr</h3>
                      <span class="font-size-14 color999 fontfamily-medium"><?php echo $this->lang->line('on'); ?> <?php echo $onDate; ?></span><br/>
                      <span class="font-size-14 color999 fontfamily-medium"><?php echo $this->lang->line('Employee'); ?> : <?php echo $employee_name; ?></span><br/>
                      <a href="javascript:history.back()" class="colorcyan a_hover_cyan text-underline display-ib">Buchung bearbeiten</a>
                    </div>
                  </div>
                </div>
                <div class="comform-table ">
                  <table class="w-100">
                    <tbody>
                  <?php
				          	$dis= 0;
                  	$total= 0;
                  	
                  	$priceoption="";
                  	//print_r($booking_detail);
                  	foreach($booking_detail as $row){
                      if ($row->parent_service_id) {
                        $pstime = $this->booking->select(
                          'st_offer_availability',
                          'starttime,endtime,days,type',
                          array(
                          'service_id'=>$row->parent_service_id,
                          'days' => $dayName
                          )
                        );
                        if ($pstime) {
                          $row->starttime = $pstime[0]->starttime;
                          $row->endtime = $pstime[0]->endtime;
                          $row->type = $pstime[0]->type;
                          $row->days = $pstime[0]->days;
                        }
                      }
					  $discount=0;
				   if(!empty($row->type) && $row->type=='open'){ 
					     if($row->starttime<=$time && $row->endtime>=$time){	 

						    if(!empty($row->discount_price)){ 
						       $discount=get_discount_percent($row->price,$row->discount_price);
						       $dis=($row->price-$row->discount_price)+$dis;
                        $total=$row->discount_price+$total; 
				          $priceCh=$row->discount_price;
				          }
				        else{
                        $total=$row->price+$total; 
                        $priceCh=$row->price;
                    }
			           }
			        else{
						      $total=$row->price+$total; 
                  $priceCh=$row->price;
						      }
				      }
			       else{
					        $priceCh=$row->price;
                  $total=$row->price+$total; 
                 }
                     
                      ?>
                      <tr class="border-b">
                        <td class="pb-2 bt-3 ">
                          <p class="color333 font-size-16 fontfamily-medium mb-0"><?php echo $row->category_name;
                           if(!empty($row->name)) echo ' - '.$row->name; ?> </p>
                          <span class="color999 font-size-14 fontfamily-regular"><?php echo $row->duration; ?> <?php echo $this->lang->line('Minutes'); ?></span>
                        </td>
                        
                        <td class="text-right pb-2 bt-3 color333 font-size-14 fontfamily-semibold">
                          <p class="color333 font-size-16 fontfamily-medium mb-0">
                          <?php
                          if (!empty($discount)) {
                          ?>
                          <span class="color999"><del>
                          <?php
                            if($row->price_start_option=='ab') echo $row->price_start_option.' ';
                            echo price_formate($row->price); ?> €<del></span>
                          <?php }
                          if($row->price_start_option=='ab') echo $row->price_start_option.' ';
                          echo price_formate($priceCh); ?> €</p>
                        	<?php if(!empty($discount)){  ?>
                           <span class="colorcyan fontfamily-regular font-size-14"><?php echo $this->lang->line('You-Save'); ?> <?php echo $discount; ?> %</span>
                           <?php }  ?>
                        </td>
                      </tr>
                      <?php } ?>
                      <input type="hidden" name="employee_select" value="<?php echo $_GET['employee_select'] ?>" readonly="readonly">
                      <input type="hidden" name="date" value="<?php echo $_GET['date'] ?>" readonly="readonly">
                      <input type="hidden" name="time" value="<?php echo $_GET['time'] ?>" readonly="readonly">
                      <input type="hidden" name="totalDuration" value="<?php echo $_GET['totalDuration'] ?>" readonly="readonly">
                      <input type="hidden" name="merch_id" value="<?php echo $_GET['merch_id'] ?>" readonly="readonly">
                      <input type="hidden" name="user_booking_note" value="<?php echo $_GET['user_booking_note'] ?>" readonly="readonly">
                     <?php if(!empty($dis)){ ?> 
                      <tr class="">
                        <td class="fontfamily-medium font-size-16 pb-0"><?php echo $this->lang->line('Discount1'); ?></td>
                        <td class="text-right fontfamily-medium font-size-16 pb-0"><?php echo price_formate($dis); ?> €</td>
                      </tr>
                      <?php } ?>
                      <tr class=" border-b">
                        <td class="fontfamily-semibold font-size-20 color333 pb-3"><?php echo $this->lang->line('Order-Total'); ?></td>
                        <td class="text-right fontfamily-semibold font-size-20 color333 pb-3"><?php  echo $priceoption.' '.price_formate($total); ?> €</td>
                      </tr> 
                    </tbody>
                  </table>
                </div>                
              </div>
                <div class="relative mt-0 around-30 pt-0">
                 
                  <div class="relative">
                   <?php 
                    if($detail->cancel_booking_allow =='yes'){
                            $mssg ="Du kannst deine Buchung bis zu ".$detail->hr_before_cancel." Stunden vor deinem Termin über styletimer stornieren oder verlegen.";
                        }else{
                          $mssg="Bitte kontaktiere den Salon für eine Verlegung oder Stornierung deines Termins";
                        }
                      ?>
                    <p class="font-size-18 fontfamily-medium color333"><img src="<?php echo base_url('assets/frontend/images/money_bag_icon.svg') ?>" class="mr-2"> <?php echo $this->lang->line('Payment1'); ?></p>
                    <div class="pymnnt-box ">
                    <picture class="">
                      <!--<source srcset="<?php //echo base_url('assets/frontend/images/date_pecker_icon.webp') ?>" type="image/webp" class="">-->
                     <!-- <source srcset="<?php //echo base_url('assets/frontend/images/date_pecker_icon.png') ?>" type="image/png" class="">-->
                      <img src="<?php echo base_url('assets/frontend/images/date_pecker_icon.svg" class="width16 mr-10">
                    </picture>
                      <span class="font-size-12 fontfamily-medium"><?php echo $mssg; ?></span>
                    </div>
                  </div>
                  <div class="radio mt-0 mb-3 " id="">
                      <label class="font-size-14 fontfamily-regular color333">
                        <input type="radio" name="remember" value="" id="" name="" checked="checked">
                        <span class="cr"><i class="cr-icon fas fa-circle"></i></span>
                        <?php echo $this->lang->line('Pay-at-venue'); ?>
                      </label>
                  </div> 

                  <div class="text-left">
                    <p class="font-size-14 color333 fontfamily-regular mt-5"><b>Bitte buche verantwortungsvoll.</b> Für die auf styletimer gelisteten Salons ist ein Nichterscheinen, oder eine sehr kurzfristige Stornierung mit Problemen verbunden. Bitte verlege oder storniere deine Buchung daher rechtzeitig über styletimer, falls du deinen gebuchten Termin doch nicht wie geplant wahrnehmen kannst.</p>
                  </div>
                </div>

                
                <div class="text-center p-3" style="background: #F2F2F2;">
                  <p class="font-size-14 color333 fontfamily-medium mb-0"><?php echo $this->lang->line('By-continuing'); ?> <a href="javascript:void(0)" class="colorcyan a_hover_cyan text-underline display-ib popup_terms" data-access="user" data-type="terms"><?php echo $this->lang->line('Booking-Terms'); ?></a> <?php echo $this->lang->line('And');?> <a href="javascript:void(0)" class="colorcyan a_hover_cyan text-underline display-ib popup_terms" data-access="user" data-type="conditions"><?php echo $this->lang->line('Privacy-Policy'); ?></a> <?php echo $this->lang->line('i-agree'); ?>.

                  <!-- and <a href="javascript:void(0)" class="text-underline colorcyan a_hover_cyan popup_terms" data-access="user" data-type="policy">privacy policy.</a> -->
                  </p>
                  <?php //echo base_url("user/terms_condition/user"); ?>
                </div>
                <div class="text-center mt-30 pb-4">
                  <button type="button" id="confirm_booking" class="btn btn-large widthfit cursor_pointer  display-ib"><?php echo $this->lang->line('Confirm_Booking'); ?></button>
                </div>
             </form>
            </div>
            
          </div>
        </div>
      </div>
    </section>  


<!-- modal booking success fully start -->
    <div class="modal fade booking_success" id="booking_done_show">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <!-- <a href="#" class="crose-btn" data-dismiss="modal">
            <img src="images/popup_crose_black_icon.svg" class="popup-crose-black-icon">
          </a> -->
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/thankyou_top_img2.png'); ?>" class="mb-3" style="width:150px;">
            <h4 class="color333 fontfamily-medium font-size-24"><?php echo $this->lang->line('Services-Booked-Successfully'); ?></h4>
              <p class="mb-30 color666 font-size-16 fontfamily-regular"><?php echo $this->lang->line('your-booking-txt'); ?> </p>
            <a href="<?php echo base_url('user/all_bookings'); ?>" class=" btn btn-large widthfit"><?php echo $this->lang->line('add_to_my_favorites'); ?></a>
          </div>
        </div>
      </div>
    </div>


    <!-- modal end -->
<?php  $this->load->view('frontend/common/footer'); ?>
