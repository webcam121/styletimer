<?php $this->load->view('frontend/common/header'); ?>
<!-- dashboard left side end -->
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
.alert_message{
  top:-55px !important;
}
.editEmp{
	cursor:pointer;
	}
  .alert_message {
    top: 0 !important;
}
</style>
 <div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>
	
	 <div class="right-side-dashbord w-100 pl-30 pr-30">
    <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-20 relative">
          <?php $this->load->view('frontend/common/alert'); ?>
    
    <!-- <div id="successMsgChg" class="alert alert-success absolute vinay top w-100 mt-15 alert_message alert-top-0 text-center" style="display: none;">
    <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span id="alert_message_suc"></span>
    </div> -->
            <!-- tab start -->
            <ul class="nav nav-tabs new_tab_v" role="tablist">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('merchant/employee_listing'); ?>"><?php echo $this->lang->line('Employee_List'); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="<?php echo base_url('merchant/employee_shift'); ?>"><?php echo $this->lang->line('Employee_Shifts'); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('merchant/closed_date'); ?>"><?php echo $this->lang->line('close_date'); ?></a>
              </li>
            </ul>
              
            <?php $seg= $this->uri->segment(3); ?>
              <div id="employee_shifts" class="tab-pane">
                <!-- top-table-droup -->
                <div class="pt-10 pb-10 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
                  <div class="relative display-ib">
                    <div class="form-group mb-0 widthfit">
                      <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new widthfit180v show" id="chg_class">
                        
                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false" id="short_emp"></button>
                          <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll widthfit180v" x-placement="bottom-start" style="overflow-x: auto !important; max-height: 221px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 56px, 0px);">
                            <li class="radiobox-image">
                              <input type="radio" class="empshort_chg" id="id_users0" name="employee" data-text="<?php echo $this->lang->line('All_staff'); ?>" value="" checked="checked">
                              <label for="id_users0" class="height48v vertical-middle pt-2">
                              <?php echo $this->lang->line('All_staff'); ?>
                            </label>
                           </li>
                            <?php if(!empty($employees_list)){
                              foreach($employees_list as $emp){
                             ?>
                            <li class="radiobox-image">
                            <input type="radio" class="empshort_chg" id="id_users<?php echo $emp->id; ?>" data-text="<?php echo $emp->first_name.' '.$emp->last_name; ?>" name="employee" value="<?php echo url_encode($emp->id); ?>" <?php if($seg == url_encode($emp->id)) echo "checked='checked'"; ?>>
                            <label for="id_users<?php echo $emp->id; ?>" class="height48v vertical-middle pt-2">
                              <?php echo $emp->first_name.' '.$emp->last_name; ?>
                            </label>
                          </li>
                          <!-- <li class="radiobox-image ">
                            <input type="radio" id="id_users72" name="aa" value="11">
                            <label for="id_users72" class="height48v vertical-middle pt-2">
                              All Staff
                            </label>
                          </li>
                          <li class="radiobox-image ">
                            <input type="radio" id="id_users73" name="aa" value="11">
                            <label for="id_users73" class="height48v vertical-middle pt-2">
                              All Staff
                            </label>
                          </li>   -->
                          <?php  }
                          } ?>                          
                          </ul>

                       </div>
                   </div>        
                  </div>
                 <!--  <div class="display-ib ml-auto">
                    <div class="form-froup v_date_time_picker relative">                        
                        <input type="text" name="" placeholder="Select Date" class="width260v form-control" id="datepicker">
                        <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue">
                    </div>
                  </div> -->
                </div>
               <!-- end top-table-droup -->
                <!-- calender table new -->
                  <div class="my-table calender-table pb-4">
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="pl-5 height56v"><?php echo $this->lang->line('Employee'); ?></th>
                          <th class="text-center height56v"><?php echo $this->lang->line('Monday'); ?></th>
                          <th class="text-center height56v"><?php echo $this->lang->line('Tuesday'); ?></th>
                          <th class="text-center height56v"><?php echo $this->lang->line('Wednesday'); ?></th>
                          <th class="text-center height56v"><?php echo $this->lang->line('Thursday'); ?> </th>
                          <th class="text-center height56v"><?php echo $this->lang->line('Friday'); ?> </th>
                          <th class="text-center height56v"><?php echo $this->lang->line('Saturday'); ?></th>
                          <th class="text-center height56v"><?php echo $this->lang->line('Sunday'); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($employees)){
                              foreach($employees as $emp){
                             ?>
                        <tr>
                          <td class="">
                          <?php if($emp->profile_pic ==""){
                                          $pimg= base_url('assets/frontend/images/user-icon-gret.svg');
                                          $pimgw= base_url('assets/frontend/images/user-icon-gret.svg');
                          }
                                      else{
                                          $pimg= getimge_url('assets/uploads/employee/'.$emp->id.'/','icon_'.$emp->profile_pic,'png');
                                          $pimgw= getimge_url('assets/uploads/employee/'.$emp->id.'/','icon_'.$emp->profile_pic,'webp');
                          }

                                  ?>
                                  
                                <picture class="conform-img display-ib mr-3" style="width: 30px;height: 30px; vertical-align:middle;">
                    
                      <img id="chgemp_img<?php echo $emp->id; ?>" style="border-radius: 50px;" src="<?php echo $pimg; ?>" class="mr-3 width30">
                    </picture>
                            <p class="mb-0 color333 fontfamily-medium font-size-14" style="display:inline-block;"><?php echo $emp->first_name.' '.$emp->last_name; ?></p>
                            <!-- <span class="font-size-12 color666 fontfamily-regular">tatal 48 hours</span> -->
                          </td>
                          <?php

                           $alltime=data_join_two('st_users','st_availability','id','user_id',array('merchant_id'=>$this->session->userdata('st_userid'),'status !='=>'deleted','st_users.id'=>$emp->id),'st_users.id,st_availability.id as av_id,first_name,last_name,email,days,type,starttime,endtime,starttime_two,endtime_two'); 
                           if(!empty($alltime)){
                            $i=1;
                            foreach($alltime as $tms){
                              if($i==1)
                                $day_nm='monday';
                              else if($i==2)
                                $day_nm='tuesday';
                              else if($i==3)
                                $day_nm='wednesday';
                              else if($i==4)
                                $day_nm='thursday';
                              else if($i==5)
                                $day_nm='friday';
                              else if($i==6)
                                $day_nm='saturday';
                              else if($i==7)
                                $day_nm='sunday';
                            //print_r($alltime);
                           ?>
                          <td class="text-center">
                             <?php
                             $j=$i-1;
                             if(isset($merchant_available[$j]->type) && $merchant_available[$j]->type== 'open'){
                              if($tms->starttime!='' && $tms->endtime!=''){ ?>
                            <div id="new_shift_<?php echo $tms->av_id; ?>">
                              <input id="<?php echo $tms->av_id; ?>" data-id="<?php echo $day_nm; ?>" data-text="<?php echo $this->lang->line(ucfirst($day_nm)); ?>" type="text" name="" value="<?php echo substr($tms->starttime, 0, 5).' - '.substr($tms->endtime, 0, 5); ?>" readonly="" class="calender-chips-new edit_shift">
                            <?php if($tms->starttime_two !='' && $tms->endtime_two !=''){ ?>
                            <input id="<?php echo $tms->av_id; ?>" data-id="<?php echo $day_nm; ?>" type="text" name="" value="<?php echo substr($tms->starttime_two, 0, 5).' - '.substr($tms->endtime_two, 0, 5); ?>" readonly="" class="calender-chips-new edit_shift">
                            <?php } ?>
                              </div>
                            <?php }
                            else{ ?>
                                <div id="new_shift_<?php echo $tms->av_id; ?>"><a href="javascript:void(0);" id="<?php echo $tms->av_id; ?>" data-id="<?php echo $day_nm; ?>" data-text="<?php echo $this->lang->line(ucfirst($day_nm)); ?>" class="mt-1 display-b shift_popup"> <!-- data-toggle="modal" data-target="#employee-select-time" -->
                                  <img src="<?php echo base_url('assets/frontend/images/add_blue_plus_icon.svg'); ?>" class="width24v">
                                </a></div>
                            <?php  } 
                          }
                          else {
                          echo "<span class='cencel'>".$this->lang->line('close_shift')."</span>";
                          }
                            ?> 
                          </td>
                          <!-- <td class="text-center">
                            <input type="text" name="" value="09:00 - 17:00" readonly="" class="calender-chips-new">
                          </td>
                          <td class="text-center">
                            <input type="text" name="" value="09:00 - 17:00" readonly="" class="calender-chips-new">
                          </td>
                          <td class="text-center">
                            <input type="text" name="" value="08:00 - 16:00" readonly="" class="calender-chips-new">
                          </td>
                          <td class="text-center">
                            <input type="text" name="" value="09:00 - 17:00" readonly="" class="calender-chips-new">
                          </td>
                          <td class="text-center">
                            <input type="text" name="" value="10:00 - 18:00" readonly="" class="calender-chips-new" data-toggle="modal" data-target="#employee-select-time">
                          </td>
                          <td class="text-center">
                            <a href="#" class="mt-1 display-b" data-toggle="modal" data-target="#employee-select-time">
                              <img src="<?php //echo base_url('assets/frontend/images/add_blue_plus_icon.svg'); ?>" class="width24v">
                            </a>
                          </td> -->
                          <?php  
                          $i++;
                            }
                           } ?>
                        </tr>
                        <?php  }
                          } ?>
                      
                      </tbody>
                    </table>
                  </div>
                <!-- calender table new  -->
              </div>

              <!-- dashboard right side end -->       
              </div>
             </div>

      </div>


     
 <!-- modal start -->
    <div class="modal fade pr-0" id="employee-select-time" >
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-20 mb-10 pl-25 pr-25 relative">
            <h3 class="font-size-18 color333 fontfamily-medium mb-1"><?php echo $this->lang->line('Edit_Shift_Hours'); ?></h3>
            <p class="fontfamily-regular color333 font-size-14 mb-4" style="text-transform: capitalize;">
              <span id="chg_dayname"><?php echo $this->lang->line('Tuesday'); ?></span> 
              
            </p>
            <form method="post" action="" id="frm_chgshift">
              
               <div id="app_newshift"></div>

               <input type="hidden" id="merch_id" name="merchant_id" value="<?php echo $merchant_id; ?>">
               <input type="hidden" id="tab_ids" name="tab_id" value="0">
               <input type="hidden" id="current_day" name="current_day" value="">
               <div id="alert_messages" class="error text-center mb-3" style="display: none;"></div>
               <div class="w-100 text-right pr-25">
                    <a href="javascript:void(0);" class="display-b mb-20 color333" id="addmore_shift"><span style="margin-right:15px">weitere hinzufügen </span>
                        <img src="<?php echo base_url('assets/frontend/images/add_blue_plus_icon.svg'); ?>" class="width24v">
                    </a>
              </div>
              <div class="relative mt-3 text-center">
                 <button type="button" class="btn btn-border-orange width150v btn-mr-10 height48v" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                 <button type="button" id="save_shift" class="btn width150v  height48v"><?php echo $this->lang->line('Save_btn'); ?></button>
               </div>
             </form>
               
            </div>
          </div>
        </div>
      </div>

  <?php $this->load->view('frontend/common/footer_script');  ?>

<script type="text/javascript">
    $(function() {
      $(window).on("scroll", function() {
          if($(window).scrollTop() > 90) {
              $(".header").addClass("header_top");
          } else {
              //remove the background property so it comes transparent again (defined in your css)
             $(".header").removeClass("header_top");
          }
      });
    });
    
    $('#datepicker').datepicker({
           uiLibrary: 'bootstrap4'
       });
    
    $('.clockpicker').clockpicker();
      // Tooltips
    
    $('[data-toggle="popover"]').popover({
        trigger: 'hover',
            'placement': 'top'
    });

    chgshiftEmpoyee();
  function chgshiftEmpoyee()
  {
    $("#short_emp").text($("input[name='employee']:checked").attr('data-text'));
    $("#chg_class").addClass('show');
  }
    

</script>
