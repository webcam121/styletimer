<?php $this->load->view('frontend/common/header'); ?>
<!-- start mid content section-->
    <section class="pt-84 clear user_booking_conform_section1">
      <div class="container">
        <div class="row">
          <?php 
          if(!empty($booking_detail)){ 
                $time = new DateTime($main[0]->booking_time);
                 $date = $time->format('d M y');
                 $time = $time->format('H:i');

                 if($main[0]->booking_type == "guest")
                    $us_name = $main[0]->fullname;
                 else
                    $us_name = $booking_detail[0]->first_name.' '.$booking_detail[0]->last_name;
            ?>
          <div class="col-12 col-sm-12 col-md-12 col-lg-10 offset-lg-1 col-xl-10 offset-xl-1">
            <div class="bgwhite border-radius4 box-shadow1 relative mb-60 mt-60">
              <div class="absolute right top mt-10 mr-15"><a href="<?php if(!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; else echo base_url(); ?>" class="font-size-30 color333 a_hover_333">
              <picture class="" style="width: 22px; height: 22px;">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="" style="width: 22px; height: 22px;">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="" style="width: 22px; height: 22px;">
                <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="" style="width: 22px; height: 22px;">
              </picture>
            </a></div>

              <div class="around-40">
                <div class="row">
                  <div class="col-12 col-sm-8 col-md-9 col-lg-9 col-xl-9">
                    <?php if($booking_detail[0]->profile_pic !='')
                              $img=base_url('assets/uploads/users/').$booking_detail[0]->id.'/'.$booking_detail[0]->profile_pic;
                          else
                              $img=base_url('assets/frontend/images/user-icon-profile.svg');
                      ?>
                    <img style="border-radius: 50%;" src="<?php echo $img; ?>" class="conform-img display-ib mr-3">
                    
                    <div class="relative display-ib vertical-middle">
                      <h3 class="fontfamily-medium color333 font-size-18 mb-1 mt-1"><?php echo $us_name; ?></h3>
                      <p class="font-size-14 color333 fontfamily-regular mb-1"><?php echo $main[0]->business_name; ?></p>
                       <?php $cls=''; if($main[0]->status =='confirmed')
                                    $cls='bgorange';
                               else if($main[0]->status =='cancelled')
                                    $cls='btn-danger';
                               else if($main[0]->status =='completed')
                                    $cls='bgsuccess';
                               else if($main[0]->status =='no show')
                                    $cls='btn-danger';
                        ?>
                      <a href="#" class="border-radius4 colorwhite <?php echo $cls; ?> pl-2 pr-2 pt-1 pb-1 fontfamily-regular font-size-10 a_hover_white"><?php echo $main[0]->status; ?></a>
                    </div>
                  </div>
                 <div class="col-12 col-sm-4 col-md-3 col-lg-3 col-xl-3">
                  <div class="mobile-mt-20">
                      <p class="mb-1 color333 fontfamily-medium font-size-14">By : <span class=""><?php echo $main[0]->first_name.' '.$main[0]->last_name; ?><span></p>
                      <p class="mb-1 color333 fontfamily-medium font-size-14">Date : <span class=""><?php echo $date; ?><span></p>
                      <p class="mb-1 color333 fontfamily-medium font-size-14">Time : <span class=""><?php echo $time." Uhr"; ?><span></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="comform-table ">
                <table class="w-100">
                  <thead>
                    <tr class="border-b">
                      <th class="pb-1">SERVICE </th>
                      <th class="pb-1">MINUTES </th>
                      <th class="pb-1">DISCOUNT </th>
                      <th class="text-right pb-1">PRICE  </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $min=$dis=$prc=0; 
                    foreach($booking_detail as $row){ 
                      $sub_name=get_subservicename($row->service_id);
                      ?>
                    <tr>
                      <?php  ?>
                      <td class="font-size-14 color666"><?php 
                      if($sub_name == $row->service_name)
                        echo $row->service_name;
                      else
                        echo $sub_name.' - '.$row->service_name; ?></td>
                      <td class="font-size-14 color666"><?php echo $row->duration; ?> Mins</td>
                      <td class="font-size-14 color666"><?php echo $row->discount_price; ?> €</td>
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
                      <td class="fontfamily-medium font-size-16 pb-0">Discount</td>
                      <td class="fontfamily-medium font-size-16 pb-0"></td>
                      <td class="fontfamily-medium font-size-16 pb-0"></td>
                      <td class="text-right fontfamily-medium font-size-16 pb-0"><?php echo $dis; ?> €</td>
                    </tr>
                    <tr class=" border-b">
                      <td class="fontfamily-semibold font-size-20 color333 pb-3">Payable Amount</td>
                      <td class="fontfamily-semibold font-size-20 color333 pb-3"></td>
                      <td class="fontfamily-semibold font-size-20 color333 pb-3"></td>
                      <td class="text-right fontfamily-semibold font-size-20 color333 pb-3"><?php echo $prc; ?> €</td>
                    </tr>
                  </tbody>
                </table>
              </div>

     
             <?php     
      if(!empty($review)){   ?>
        <div class="around-40 pt-20 pb-20">
                <div class="text-right">
                  <a class="btn btn-large widthfit cursor_pointer relative"  data-toggle="collapse" data-target="#change_reting_reveiw" href="#"> View Review <i class="fas fa-chevron-down ml-10"></i></a>
                </div>
                 <div class="collapse" id="change_reting_reveiw">
                  <div class="pt-20">
                 <p class="font-size-20 fontfamily-semibold color333">Customer Rate & Review</p>
                   
                    <div class="relative mb-10">
                      <?php $rate=$review->rate;
                      for($i=0;$i < 5;$i++){
                        if($rate > $i){
                      ?><i class="fas fa-star colororange mr-2 font-size-20"></i>
                      <?php } else{ ?>
                        <i class="fas fa-star color999 mr-2 font-size-20"></i>
                      <?php }
                      } ?>
                      </div>
                    <div class="relative">
                        <p class="color333 fontfamily-medium font-size-14 text-lines-overflow-1" style="-webkit-line-clamp:6;"><?php echo $review->review; ?></p>
                   </div>
                 </div>
               </div>
             </div>
        <?php } ?>
            
            </div>
          </div>
        <?php 
        } ?>
        </div>
      </div>
    </section>  


<?php $this->load->view('frontend/common/footer'); ?>
