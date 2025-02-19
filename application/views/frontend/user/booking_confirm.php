<?php $this->load->view('frontend/common/header'); ?>
<!-- start mid content section-->
<style type="text/css">
  #txtreview_validate .error{
   margin-top: -12px !important;
  }
  .alert.alert-success.vinay, .alert.alert-danger.vinay {

    margin-left: -24px !important;
    width: 100% !important;
    top: -55px;

}
a:hover {
  color: #FF9944;
  }
</style>
<?php if($this->session->userdata('access') == 'marchant' || $this->session->userdata('access') == 'employee')
          $ct='pt-84';
      else
          $ct='pt-120';
        ?>
    <section class="<?php echo $ct; ?> clear user_booking_conform_section1">
      <div class="container">
        <div class="row">
          <?php
         
         if(!empty($booking_detail)){ 
                $time = new DateTime($main[0]->booking_time);
                 $date = $time->format('d M y');
                 $time = $time->format('H:i');

                 $uptime = new DateTime($main[0]->updated_on);
                 $up_date = $uptime->format('d M y');
                 $uptime = $uptime->format('H:i');
                 
              

                 $access= $this->session->userdata('access');
                 if($access == 'user')
                  $link='user/all_bookings';
                 else if($access =='marchant')
                  $link='merchant/booking_listing';
                 else if($access =='employee')
                  $link='employee/dashboad';
                 else
                  $link=''; 
                  
                  //echo current_url(); die;
            ?>
          <div class="col-12 col-sm-12 col-md-12 col-lg-10 offset-lg-1 col-xl-10 offset-xl-1">
           <div class="bgwhite border-radius4 box-shadow1 relative mb-60 mt-30">
				    <div class="absolute right top mt-10 mr-15"><a href="<?php if(!empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=current_url()) echo $_SERVER['HTTP_REFERER']; else echo base_url($link); ?>" class="font-size-30 color333 a_hover_333"><picture class="" style="width: 22px; height: 22px;">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="" style="width: 22px; height: 22px;">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="" style="width: 22px; height: 22px;">
                <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="" style="width: 22px; height: 22px;">
              </picture></a></div>
              <div class="around-40">
                <div class="row">
					 <?php $this->load->view('frontend/common/alert');
                $emp_url="";
               
                if($main[0]->booking_type == "guest"){
                  $us_name = $main[0]->fullname;
                  $notes = $main[0]->notes;
                }
                else{
                  $us_name = $booking_detail[0]->first_name.' '.$booking_detail[0]->last_name;
                  $notes = $booking_detail[0]->notes;
                  if($this->session->userdata('access') =='marchant')
                    $emp_url = base_url("profile/clientview/").url_encode($main[0]->userid);
                }
            ?>
                  <div class="col-12 col-sm-12 col-md-7 col-lg-8 col-xl-8">
                    <?php  if($booking_detail[0]->profile_pic !='')
                                {
                                  $img=getimge_url('assets/uploads/users/'.$booking_detail[0]->id.'/','icon_'.$booking_detail[0]->profile_pic,'png');
                                  $imgw=getimge_url('assets/uploads/users/'.$booking_detail[0]->id.'/','icon_'.$booking_detail[0]->profile_pic,'webp');
						       }
                           else{
                                 $img=base_url('assets/frontend/images/user-icon-gret.svg');
                                 $imgw=base_url('assets/frontend/images/user-icon-gret.webp');
						       }
                      ?>
                  
                    <picture>
						<source srcset="<?php echo $imgw; ?>" type="image/webp" class="sliderImage">						 
						<source srcset="<?php echo $imgw; ?>" type="image/webp" class="sliderImage">						 
					    <img style="border-radius: 50%;" src="<?php echo $img; ?>" class="conform-img display-ib mr-3">					   
					</picture>
                    
                    <div class="relative display-ib vertical-middle">
                      <h3 id="gotocustomerdetail" class="fontfamily-medium color333 font-size-18 mb-1 mt-1 <?php echo ($emp_url !='')?'cursor-p':''; ?>"><?php echo $us_name; ?></h3>
                      <input type="hidden" id="customer_url" name="" value="<?php echo $emp_url; ?>">
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
                      <a href="<?php echo base_url('user/service_provider/').url_encode($main[0]->merchant_id)."?servicids=".url_encode($servids); ?>"><p class="font-size-14 color333 fontfamily-regular mb-1"><?php echo $main[0]->business_name; ?></p>
                      <?php $cls=''; /*if($main[0]->status =='confirmed')
                                    $cls='bgorange';
                               else*/
                                if($main[0]->status =='cancelled')
                                    $cls='btn-danger';
                               else if($main[0]->status =='completed' || $main[0]->status =='confirmed')
                                    $cls='bgsuccess';
                               else if($main[0]->status =='no show')
                                    $cls='btn-danger';
                        ?>

                      <a href="javascript:void(0)" class="border-radius4 colorwhite <?php echo $cls; ?> pl-2 pr-2 pt-1 pb-1 fontfamily-regular font-size-10 display-ib" style="cursor: default;"><?php echo $main[0]->status; ?></a>
                      
                       <?php if($main[0]->reason !="") { ?>
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
                  <div class="col-12 col-sm-12 col-md-5 col-lg-4 col-xl-4">
                    <div class="mobile-mt-20">
                        <p class="mb-1 "><?php echo $this->lang->line('Employee'); ?> : <span class=""><?php echo $main[0]->first_name; ?><span></p>  <!-- .' '.$main[0]->last_name --> 
                       <p class="mb-1 "><?php echo $this->lang->line('Booking_Id'); ?> : <span class=""><?php echo $main[0]->book_id; ?><span></p>
                      <p class="mb-1 "><?php echo $this->lang->line('Booking_Date1'); ?>  : <span class=""><?php echo $date.' '.$time; ?><span></p>
                      <p class="mb-1 "><?php echo $this->lang->line('Booking_Made1'); ?> : <span class=""><?php echo date('d M y H:i',strtotime($main[0]->created_on)); ?><span></p>
                      <?php if($main[0]->status =='completed'){ ?>
                     <p class="mb-1 "><?php echo $this->lang->line('Completed_Date'); ?> : <span class=""><?php echo $up_date.' '.$uptime; ?><span></p>
                     
                      
                      <p class="mb-1">
						  <?php if($this->session->userdata('access')=='marchant'){ ?>
						   <a class="colororange text-underline" href="<?php echo base_url('checkout/viewinvoice/'.url_encode($main[0]->invoice_id)); ?>"><?php echo $this->lang->line('Receipt'); ?></a>
						   <?php }else{ ?>
							   <a class="colororange text-underline" href="<?php echo base_url('user/viewinvoice/'.url_encode($main[0]->invoice_id)); ?>"><?php echo $this->lang->line('Receipt'); ?></a>
							 <?php } ?>
						  
<!--
                        <a class="colororange text-underline" href="#" id="" data-toggle="modal" data-target="#popup-v11">Receipt</a>
-->
                        </p>
                   <?php  } ?>

                    </div>
                  </div>
                </div>
              </div>
              <div class="comform-table ">
                <table class="w-100">
                  <thead>
                    <tr class="border-b">
                      <th class="pb-1"><?php echo $this->lang->line('Service'); ?> </th>
                      <th class="pb-1"><?php echo $this->lang->line('Minutes1'); ?> </th>
                      <th class="pb-1"><?php echo $this->lang->line('Discount1'); ?> </th>
                      <th class="text-right pb-1"><?php echo $this->lang->line('Price1'); ?>  </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $min=$dis=$prc=0;
                    $ser_name=$service_nm=''; 
                    foreach($booking_detail as $row){ 
                     $sub_name=get_subservicename($row->service_id); ?>
                    <tr>
                      <td class="font-size-14 color666">
                        <?php if($sub_name == $row->service_name)
                            echo $service_nm=$row->service_name;
                          else
                            echo $service_nm=$sub_name.' - '.$row->service_name; 

                          $ser_name=$ser_name.','.$service_nm;
                          ?>
                        </td>
                      <td class="font-size-14 color666"><?php echo $row->duration; ?> Mins</td>
                      <td class="font-size-14 color666"><?php if(!empty($row->discount_price)) echo $row->discount_price.' €'; else echo '-'; ?></td>
                      <td class="text-right font-size-14 color666"><?php echo $row->price; ?> €</td>
                    </tr>
                    <?php   $min=$min+$row->duration;
                            $dis=$dis+$row->discount_price;
                            $prc=$prc+$row->price; 
                      } ?>
                    <tr class="border-t border-b">
                      <td class=" font-size-16 color333"></td>
                      <td class="fontfamily-semibold font-size-16 color333"><?php echo $min; ?> Mins</td>
                      <td class="fontfamily-semibold font-size-16 color333"><?php echo $dis; ?> €</td>
                      <td class="text-right fontfamily-semibold font-size-16 color333 "><?php echo $prc; ?> €</td>
                    </tr>
                    <tr class="">
                      <td class="fontfamily-medium font-size-16 pb-0"><?php echo $this->lang->line('Discount'); ?> </td>
                      <td class="fontfamily-medium font-size-16 pb-0"></td>
                      <td class="fontfamily-medium font-size-16 pb-0"></td>
                      <td class="text-right fontfamily-medium font-size-16 pb-0"><?php if(!empty($dis)) echo $dis.' €'; else echo '-'; ?> </td>
                    </tr>
                    <tr class=" border-b">
                      <td class="fontfamily-semibold font-size-20 color333 pb-3"><?php echo $this->lang->line('Payable_Amount'); ?> </td>
                      <td class="fontfamily-semibold font-size-20 color333 pb-3"></td>
                      <td class="fontfamily-semibold font-size-20 color333 pb-3"></td>
                      <td class="text-right fontfamily-semibold font-size-20 color333 pb-3"><?php  echo $prc; ?> €</td>
                    </tr>
                  </tbody>
                </table>
              </div>
               <?php $html='<a href="'.base_url('merchant/dashboard/'.url_encode($main[0]->id)).'?option=rebook" class="dropdown-item">Rebook</a>';
                if(strtotime($main[0]->booking_time) > strtotime(date('Y-m-d H:i:s'))  && $main[0]->status != 'cancelled'){   
                    $html.='<a id="'.url_encode($main[0]->id).'" class="dropdown-item booking_cancels" data-toggle="modal" data-target="#service-cencel-table" href="javascript:void(0)">'.$this->lang->line('Cancel').'</a>';
                  } 
                 if($main[0]->status != 'no show'){ 
                    $html.='<a id="'.url_encode($main[0]->id).'" class="dropdown-item noshow_book" data-toggle="modal" data-target="#service-noshow-table" href="javascript:void(0);" href="javascript:void(0)">'.$this->lang->line('No_Show').'</a>';
                   } 
                  if(strtotime($main[0]->booking_time) > strtotime(date('Y-m-d H:i:s'))  && $main[0]->status != 'completed' && $main[0]->status != 'cancelled' && $main[0]->status != 'no show'){ 
                    $html.='<a id="'.url_encode($main[0]->id).'" class="dropdown-item  reSchedule_book" data-toggle="modal" data-target="#service-reschedule-table" data-eid="'.url_encode($main[0]->employee_id).'" href="javascript:void(0)">'.$this->lang->line('Reschedule').'</a>';
                   }
                   
                    $html.='<a class="dropdown-item  deleteBooking" data-url="'.base_url('rebook/delete_booking/'.url_encode($main[0]->id).'/dashboard').'" href="javascript:void(0)">'.$this->lang->line('Delete').'</a>';
                    
                    ?>

              <!-- add new droup down -->
              <div class="d-flex mr-4 mt-3 pb-3 ml-40">
				 
				  
                <?php if($this->session->userdata('access') =='marchant'){ ?>
                  <!-- delete button Delete-->
<!--
				<button class="btn widthfit ml-0 deleteBooking" data-url="<?php echo base_url('rebook/delete_booking/'.url_encode($main[0]->id)); ?>" type="button">Delete</button> 
-->
        <!-- delete button Delete -->
                <div class=" ml-auto" >
                  <?php if($html !=''){ ?>
                   <div class="display-ib dropdown dropdownMoreOption">
                    <button class="btn widthfit dropdown-toggle" type="button" id="dropdownMoreOption" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $this->lang->line('More_Option'); ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMoreOption">
                    <?php echo $html; ?>
                    </div>
                  </div>
                  <?php } ?>
                  <?php if(strtotime($main[0]->booking_time) < strtotime(date('Y-m-d H:i:s')) && $main[0]->status != 'completed'){ ?>

					<a href="<?php echo base_url("checkout/process/".url_encode($main[0]->id)); ?>"> <button class="btn widthfit new-btn-compltate complete_book ml-2"  data-toggle="modal"><?php echo $this->lang->line('Complete'); ?></button></a>


<!--
                  <button id="<?php echo url_encode($main[0]->id); ?>"  class="btn widthfit new-btn-compltate complete_book ml-2"  data-toggle="modal" data-target="#service-complete-table">Complete</button>
-->

                  <?php }else if($main[0]->status=='completed'){ ?>
            <a href="<?php echo base_url("checkout/viewinvoice/".url_encode($main[0]->invoice_id)); ?>"> <button class="btn widthfit new-btn-compltate complete_book ml-2" style="background-color:#666666 !important;border: 1px solid #666666 !important;">
            <?php echo $this->lang->line('View_Invoice'); ?></button></a>
					  
					<?php } ?>
                 </div>
                 <?php } ?>
               </div>
              <!--end new froup down -->
            <?php
           
              if($notes !='' && $this->session->userdata('access')!='user'){ ?>
              <div class="around-40 pt-20 pb-10">
              <div class="row border-w pt-10 pb-10">
               <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="relative">
                      <p class="colorgreen fontfamily-medium font-size-16 mb-1"><?php echo $this->lang->line('Customer_Note'); ?></p>
                      <p class="text-lines-overflow-1 font-size-14 fontfamily-medium color999" style="-webkit-line-clamp:2;"><?php echo $notes; ?></p>
                    </div>
                  </div>
              </div>
            </div>
            <?php  }
             if(!empty($main[0]->booknotes) && $this->session->userdata('access')!='user'){ ?>
              <div class="around-40 pt-10 pb-20">
              <div class="row border-w pt-10 pb-10">
               <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="relative">
                      <p class="colorgreen fontfamily-medium font-size-16 mb-1"><?php echo $this->lang->line('booking_note'); ?></p>
                      <p class="text-lines-overflow-1 font-size-14 fontfamily-medium color999" style="-webkit-line-clamp:2;"><?php echo $main[0]->booknotes; ?></p>
                    </div>
                  </div>
              </div>
            </div>
            <?php  } 
            else if(!empty($main[0]->booknotes) && $main[0]->created_by == $this->session->userdata('st_userid')){ ?>
                <div class="around-40 pt-10 pb-20">
              <div class="row">
               <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="relative">
                      <P class="colorgreen fontfamily-medium font-size-16 mb-2"><?php echo $this->lang->line('booking_note'); ?></P>
                      <p class="text-lines-overflow-1 font-size-14 fontfamily-medium color999" style="-webkit-line-clamp:2;"><?php echo $main[0]->booknotes; ?></p>
                    </div>
                  </div>
              </div>
            </div>

              <?php }
                if($main[0]->status !='completed')
                    $rev_div='none';
                else if(isset($review->anonymous) && $review->anonymous== 1)
                    $rev_div='none';
                else
                    $rev_div='';

               ?>
            
              <div class="around-40 pt-20 pb-20" style="display: <?php echo $rev_div; ?>">
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
                    <span class="ml-1 text-underline"> Please Rate & Review on this service </span>
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
                    <span class="ml-1 text-underline"> View your Rate & Review </span>
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
                        <p class="color666 fontfamily-medium font-size-14 text-lines-overflow-1 mb-0" style="-webkit-line-clamp:6;"><?php echo $review->review; ?></p>
                        <span class="fontfamily-regular color999 font-size-14">
                          <?php 
                          $dt = strtotime($review->created_on); 
                          echo date('l jS F y \a\t g:ia', $dt); ?></span>

                        <?php $m_reply=getselect('st_review','review,created_on',array('review_id' =>$review->id,'created_by' => $review->merchant_id)); 
                          if(!empty($m_reply)){ ?>

                        <h6 class="font-size-16 fontfamily-semibold color333 mt-3 mb-3">Salon Responses</h6>
                        <?php foreach($m_reply as $rev){ ?>
                        <p class="color666 fontfamily-medium font-size-14 mb-0"><?php echo $rev->review; ?></p>
                        <span class="fontfamily-regular color999 font-size-14">
                          <?php $dts = strtotime($rev->created_on); 
                          echo date('l jS F y \a\t g:ia', $dts); ?>
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
        </div>
      </div>
       <input type="hidden" id="action_from" name="" value="detail">
    </section>  

    <!-- end mid content section -->
    <div class="modal fade" id="reting_review">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-4 pr-4 bglightgreen1">
            <div id="change_reting_reveiw">
                 
                    <div class="display-ib vertical-middle">
                      <img src="<?php echo $img; ?>" class="width40v border-radius50 mr-3">
                    </div>
                    <div class="display-ib vertical-middle">
                      <p class="font-size-16 color333 fontfamily-medium mb-1"><?php echo $us_name; ?></p>
                      <p class="fontfamily-regular font-size-14 color666 mb-0"><?php echo isset($main[0]->business_name)?$main[0]->business_name:''; ?></p>
                    </div>
                    <form id="frmReview" method="post" action="<?php echo base_url('booking/review'); ?>">
                    
                   <!--  <p class="font-size-16 fontfamily-medium color999">Rate this service</p> -->
                    
                    <div class="form-group mb-30 pt-3 mt-3" style="border-top: 1px solid #c4c4c4;">
                      <fieldset class="rating vertical-sub" style="" >
                        <input type="radio" id="star-5" name="rating" value="5">
                        <label class="rating" for="star-5" title="">
                          <i class="fas fa-star"></i>
                        </label>
                        
                        <input type="radio" id="star-4" name="rating" value="4">
                        <label class="rating" for="star-4" title="">
                          <i class="fas fa-star"></i>
                        </label>
                        
                        <input type="radio" id="star-3" name="rating" value="3">
                        <label class="rating" for="star-3" title="">
                          <i class="fas fa-star"></i>
                        </label>
                        
                        <input type="radio" id="star-2" name="rating" value="2">
                        <label class="rating" for="star-2" title="">
                          <i class="fas fa-star"></i>
                        </label>
                        
                        <input type="radio" id="star-1" name="rating" value="1">
                        <label class="rating" for="star-1" title="">
                          <i class="fas fa-star"></i>
                        </label>
                              
                      </fieldset>
                      <!-- <span class="colorsuccess fontfamily-medium font-size-16 ml-3">(Good)</span> -->
                    </div>
                     <label class="error_label" id="rating_err" style="margin-top: -15px;"></label>
                    <div class="form-group inp v_inp_new "  style="height:100px;">    
                      <textarea type="text" id="txtreview" name="txtreview" placeholder="&nbsp;" class="form-control custom_scroll w-100" style="max-height:100px;min-height: 100px"></textarea>                  
                       <!-- <textarea type="text" placeholder="&nbsp;" class="form-control custom_scroll" style="max-height:100px;min-height: 100px "></textarea> -->
                       <label class="label"><?php echo $this->lang->line('Write_Review'); ?></label>
                   </div>
                    <label class="error_label" id="rating_text"></label>
                   <div class="checkbox mt-4 mb-5">
                      <label class="font-size-14 pl0 colorcyan">
                        <input type="checkbox" name="anonymous" value="1">
                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                        <?php echo $this->lang->line('Rate-as-Anonymous'); ?>
                      </label>
                    </div>
                    <input type="hidden" id="booking_id" name="booking_id" value="<?php echo url_encode($main[0]->id);  ?>">
                    <input type="hidden" id="merchant_id" name="merchant_id" value="<?php echo url_encode($main[0]->merchant_id); ?>">
                     <input type="hidden" id="" name="employeeid" value="<?php echo $main[0]->employee_id; ?>">
                     <input type="hidden" id="" name="refferer_link" value="<?php if(!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; ?>">
                   <div class="text-center">
                    <button class="btn btnlarge widthfit"><?php echo $this->lang->line('Submit-Review'); ?></button>
                  </div>
                  </form>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- end modal --> 

 <div class="modal fade pr-0" id="popup-v11" >
     <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
           <a target="_blank" href="<?php echo base_url('booking/downloadReceipt/').url_encode($main[0]->id) ?>"><span id="click_to_print_old" class="cursor-p fontfamily-medium colororange font-size-14" style="display: inline-block;position: absolute;right: 25px;top: 25px;margin-top: 15px;z-index: 999;"><img src="<?php echo base_url('assets/frontend/images/printer.svg'); ?>" style="width:24px;"></span>
          <div id="print_div_receipt" class="modal-body pt-40 mb-10 pl-25 pr-25 relative">
            <h3 id="print_title" class="text-center color333 fontfamily-medium hide" style="font-size: 0.875rem;">Booking Receipt</h3>
            <div class="relative d-flex">
              <div class="display-ib mr-20"> 
                <img src="<?php echo $img; ?>" class="round-new-v40"  style="width: 40px;height: 40px;border-radius: 50%;">
              </div>
              <div class="display-ib">
                <p class="fontfamily-medium colorcyan font-size-16 mb-15 display-ib" style="color: #00b3bf;font-size: 1rem;"><?php echo ucfirst($us_name); ?></p>

                <?php if($main[0]->booking_type != "guest"){ ?>
                <p class="font-size-14 color666 fontfamily-regular" style="font-size: 0.875rem;"><?php echo $booking_detail[0]->address." <br>".$booking_detail[0]->zip." ".$booking_detail[0]->city; ?></p>
                <?php } ?>
              </div>
            </div>
            <div class="relative d-flex">
                <div class="display-ib">
                  <p class="fontfamily-regular color999 font-size-12 mb-0" style="font-size: 0.75rem;">Total Duration </p>
                  <span class="fontfamily-medium colororange font-size-14 " style="color: #FF9944;font-size: 0.875rem;"><?php echo $main[0]->total_minutes.' Mins'; ?></span>
                </div>
                <div class="display-ib text-right ml-auto">
                  <button class="bg-blue-lite-btn mt-1 " style="border-radius: 0.25rem;border: 1px solid #00b3bf;background: #DBF4F6;color: #00b3bf;height: 32px;width: 75px;"><?php echo $main[0]->total_price.' €'; ?></button>
                </div>
              </div>

               <h5 class="font-size-18 color333 fontfamily-medium mt-4 mb-3" style="font-size: 1.125rem;">Booking Information   </h5> 
               <div class="relative">
                <span class="color999 font-size-12 fontfamily-regular">Booked via</span>
                <p class="color333 fontfamily-medium font-size-14"><?php if($main[0]->book_by=='0') echo 'Web'; else echo 'App'; ?></p>
              </div>
              <div class="relative">
                <span class="color999 font-size-12 fontfamily-regular">Booking Date</span>
                <p class="color333 fontfamily-medium font-size-14"><?php echo date("d F Y, H : i",strtotime($main[0]->booking_time)); ?></p>
              </div>
              <div class="relative">
                <span class="color999 font-size-12 fontfamily-regular">Completed Date</span>
                <p class="color333 fontfamily-medium font-size-14"><?php echo date("d F Y, H : i",strtotime($main[0]->updated_on)); ?></p>
              </div>
              <div class="relative">
                <span class="color999 font-size-12 fontfamily-regular">Booking ID</span>
                <p class="color333 fontfamily-medium font-size-14"><?php echo $main[0]->book_id; ?></p>
              </div>
              <div class="relative">
                <span class="color999 font-size-12 fontfamily-regular">Service</span>
                <p class="color333 fontfamily-medium font-size-14"><?php echo ltrim($ser_name, ','); ?></p>
              </div>
                <div class="relative">
                <span class="color999 font-size-12 fontfamily-regular">Salon name</span>
                <p class="color333 fontfamily-medium font-size-14 mb-0"><?php echo $main[0]->business_name; ?></p>
                 <p class="color333 fontfamily-regular font-size-12"><?php echo $main[0]->address." <br/>".$main[0]->zip." ".$main[0]->city; ?></p>
              </div>
              <div class="relative">
                <span class="color999 font-size-12 fontfamily-regular">Customer email address</span>
                <p class="color333 fontfamily-medium font-size-14">
                  <?php if($main[0]->booking_type != "guest") 
                          echo $booking_detail[0]->email;
                        else
                          echo $main[0]->email;
                        ?></p>
              </div>
      
            </div>
          </div>
        </div>
      </div>


 <!--booking complete modal start -->
    <div class="modal fade" id="service-complete-table">
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
            <img src="<?php echo base_url('assets/frontend/images/icon_cmp.png'); ?>">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;"> Are you sure you want to mark complete this booking</p>
             <input type="hidden" id="book_conf" name="book_conf" value="">
            <button type="button" class=" btn btn-large widthfit" id="booking_done">ok</button>
          </div>
        </div>
      </div>
    </div>

    <!--no show modal start -->
    <div class="modal fade" id="service-noshow-table">
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
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">Are you sure you really want to no show this booking?    </p>
            <input type="hidden" id="noshowbook_id" name="noshowbook_id" value="">
            <button type="button" id="noshow_booking" class="btn btn-large widthfit">Ok</button>
          </div>
        </div>
      </div>
    </div>


    <!--booking cancel modal start -->
    <div class="modal fade" id="service-cencel-table">
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
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">Are you sure you really want to cancel this booking?    </p>
            <input type="hidden" id="bookingid" name="bookingid" value="">
            <input type="hidden" id="check_access" name="" value="<?php echo $this->session->userdata('access'); ?>">
            <button type="button" id="cancel_booking" class="btn btn-large widthfit">Cancel</button>
          </div>
        </div>
      </div>
    </div>

<!-- reschedule modal start -->
<!--
    <div class="modal fade" id="service-reschedule-table">
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
                <button type="button" class=" btn btn-large widthfit" id="booking_reSch">ok</button>
              </div>
            </form>
            
          </div>
        </div>
      </div>
    </div>
-->


    <!-- modal start -->
    <div class="modal fade" id="reshedule-complete-table">
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

    <!-- page-modal -->
  


<?php $this->load->view('frontend/common/footer'); ?>
<script type="text/javascript">
  $(document).on('click',".bookDetailShow",function(){
    $("#popup-v11").modal('show');
  });

  $(document).ready(function(){
    $("#booking-conform-modal").modal('show');
  });
  $('#booking-conform-modal').modal({backdrop: 'static', keyboard: false})  
</script>
<script type="text/javascript">
  var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('.datepicker').datepicker({
         uiLibrary: 'bootstrap4',
         minDate:today
       });

</script>
<script type="text/javascript">
      $('.clockpicker').clockpicker();

    $(document).on('click','#chg_date',function () {
      $('.today gj-cursor-pointer')
        .css('background-color', '#000000');
    });
    
  
   $(document).on('blur','#chg_date',function () {
    setTimeout(function(){ 
       var date=$("#chg_date").val();
       var reseid=$("#reseid").val();
       var bkid=$("#reSchedule_id").val();
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
       $.post(base_url+"merchant/get_opning_hour",{date:date,eid:reseid,bk_id:bkid} , function( data ) {
       //console.log(data);
             var obj = jQuery.parseJSON( data );
            if(obj.success=='1'){
        $("#date_err").html('');
        $("#chg_time").html(obj.html);
      }else{
        $("#date_err").html(obj.message);
        //$(".alert_message").css('display','block');
        
        //location.reload();
      
        }
      
            });
     unloading();
     },500);
    });   

  </script>
