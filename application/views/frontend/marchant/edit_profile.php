

<?php $this->load->view('frontend/common/header');  $keyGoogle =GOOGLEADDRESSAPIKEY;
getmembership_exp();

 ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
  #gallery1 .upl-gallary-parent .upl-gallary {
    height: 180px;
  }
  #gallery1 .upl-gallary-parent {
    height: auto;
  }
  .small_image{
    height:100%;
    width:100%;
    border-radius: 4px;
    }
   .large_image{
    height:210px;
    width:800px;
    min-width: auto;
    border-radius: 4px;
    }
 .alert{
       top: -70px !important;
   }
   .all_type_upload_file.text-center {
    line-height: 100px;
   }
   .new-pro-toggle-btn{
    position: absolute;
    top:20px;
    right:10px;
   }
   
   .swal2-title {
    font-size: 0.92em;  
    }

    #mceu_16
        {
          display:none;
        }
     #mceu_23-body
     {
      display:none;
     } 
     
     #mceu_20-body
     {
       display:none;
     } 
     
     #mceu_49-body
     {
       display:none; 
     }
    #mceu_77-body{
        display:none; 
     }
    #mceu_4-body{
    display:none; 
    } 
  #tinymce{font-size:14px !important;}
 
  .fr-box.fr-basic .fr-element{
    min-height: 1230px!important;
  }
  .fr-wrapper {
    height: 1630px !important;
  }
  .top-demo-8{
    top: 8%;
  }
  /* .alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0.display-n, .alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0 {
 top: -62px !important;
  } */
  @media(max-width:1199px){    
    .fr-wrapper {
        height: 1050px !important;
    }
    .fr-box.fr-basic .fr-element{
      min-height: 930px!important;
    }
  }
  @media(min-width:1200px) and (max-width:1320px){
    .fr-wrapper {
      height: 1630px !important;
    }
  }
  @media(min-width:1919px){
    .fr-wrapper {
        height: 1525px !important;
    }
    .form-group-mb-50#about_validate {
        margin-bottom: 30px;
    }
  }
   /*.border-radius4.relative.uploaded-box {overflow: hidden;}*/
  </style>
    <!-- header end -->
    <div class="d-flex pt-84">

 <link rel="stylesheet" href="<?php echo base_url("assets/cropp/croppie.css");?>" type="text/css" />
        <!-- dashboard left side end -->
        
<?php $this->load->view('frontend/common/editer_css');
      $this->load->view('frontend/common/sidebar');

//print_r($userdetail); die;
 ?>
<div class="right-side-dashbord w-100 pl-30 pr-30">
         <!-- <div class="d-flex justify-content-between align-items-center">
          <h3 class="font-size-20 color333 fontfamily-medium mt-20 mb-4">Salon Settings</h3>
         </div>  -->
          <!-- tab start -->
          <div class="relative mb-60 mt-20"> 
          <!-- <img src="<?php // echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>"
           class="mr-10"> 
           <span id="alert_message">
            <?php //if($this->session->flashdata('success'))
             //echo $this->session->flashdata('success'); ?> </span>  -->
            <!-- Tab panes -->
            <!-- <p id="cropIMages"></p> -->
         
            <div class="tab-content mt-0">
              <div class="alert alert-success absolute vinay top w-100 
               alert_message alert-top-0 text-center display-n" >
    <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times; </button> -->
  
   <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span id="alert_message"><?php if($this->session->flashdata('success')) echo $this->session->flashdata('success'); ?> </span>
 </div>
              
         <?php $this->load->view('frontend/common/alert'); ?>
              <div id="edit_profile" class="border-radius4 relative pt-4 px-3 bgwhite box-shadow1 pb-4 <?php if(empty($_GET['tab']) || $_GET['tab']!='gallery') echo 'active'; ?>">
                
                <form method="post" id="marchant_update_profile" enctype="multipart/form-data" action="<?php echo base_url('profile/update_marchant_profile'); ?>">
                  <div class="row ">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-7 mobile-mb-20">
                      <h5 class="font-size-18 color333 fontfamily-medium <?php if(empty($_GET['tab']) || $_GET['tab']!='gallery') echo 'active'; ?>" href="#edit_profile"><?php echo $this->lang->line('Salon_Details'); ?></h5>
                      <div class="relative mt-4">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group form-group-mb-50" id="first_name_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="&nbsp;" name="first_name" value="<?php echo $userdetail[0]->first_name; ?>" class="form-control height56v">
                                 <span class="label"><?php echo $this->lang->line('First_Name'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group form-group-mb-50" id="last_name_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="&nbsp;" name="last_name" value="<?php echo $userdetail[0]->last_name; ?>" class="form-control height56v">
                                 <span class="label"><?php echo $this->lang->line('Last_Name'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group form-group-mb-50" id="">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="DOB" name="dob" value="<?php if(!empty($userdetail[0]->dob)) echo date('d-m-Y',strtotime($userdetail[0]->dob)); ?>" class="form-control height56v dobDatepicker" style="background-color:#ffffff" readonly>
                                 <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue" style="">
                                 <span class="label" style="transform: translateY(-36px) translateX(-15px) scale(0.75);"><?php echo $this->lang->line('Birthday'); ?></span>  
                               </label>
                             </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group form-group-mb-50" id="business_name_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" name="business_name" placeholder="&nbsp;" value="<?php echo $userdetail[0]->business_name; ?>" class="form-control height56v">
                                 <span class="label"><?php echo $this->lang->line('Business_Name'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group form-group-mb-50">
                                <div class="btn-group multi_sigle_select inp_select v_inp_new"> 
                                   <span class="label <?php if(!empty($userdetail[0]->business_type)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Business_Type'); ?></span>
                                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle height56v mss_sl_btn"><?php if(!empty($userdetail[0]->business_type)) echo $userdetail[0]->business_type; ?></button>
                                        <ul class="dropdown-menu mss_sl_btn_dm">
                                         <li class="radiobox-image"><input type="radio" id="id_310" 
                                         <?php if($userdetail[0]->business_type == 'Friseur'){ echo 'checked="checked"'; } ?>
                                          name="business_type" class="" value="Friseur"><label for="id_310">Friseur</label></li>
                                          <li class="radiobox-image"><input type="radio" id="id_311" 
                                          <?php if($userdetail[0]->business_type == 'Barbier'){ echo 'checked="checked"'; } ?> 
                                          name="business_type" class="" value="Barbier"><label for="id_311">Barbier</label></li>
                                          <li class="radiobox-image"><input type="radio" id="id_312" 
                                          <?php if($userdetail[0]->business_type == 'Nagelstudio'){ echo 'checked="checked"'; } ?>
                                           name="business_type" class="" value="Nagelstudio"><label for="id_312">Nagelstudio</label></li>
                                           <li class="radiobox-image"><input type="radio" id="id_313" 
                                          <?php if($userdetail[0]->business_type == 'Massage Salon'){ echo 'checked="checked"'; } ?>
                                           name="business_type" class="" value="Massage Salon"><label for="id_313">Massage Salon</label></li>
                                           <li class="radiobox-image"><input type="radio" id="id_314" 
                                          <?php if($userdetail[0]->business_type == 'Kosmetikstudio'){ echo 'checked="checked"'; } ?>
                                           name="business_type" class="" value="Kosmetikstudio"><label for="id_314">Kosmetikstudio</label></li>
                                        </ul>
                                </div>
                                <label id="busType_err" class="error"></label>
                             </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group form-group-mb-50" id="mobile_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" name="mobile" value="<?php echo $userdetail[0]->mobile; ?>" placeholder="&nbsp;" class="form-control height56v onlyNumber">
                                 <span class="label"><?php echo $this->lang->line('Telephone'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group form-group-mb-50" id="email_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="&nbsp;" name="email" value="<?php echo $userdetail[0]->email; ?>" class="form-control height56v">
                                 <span class="label"><?php echo $this->lang->line('Email'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group form-group-mb-50" id="address_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" id="address" name="address" value="<?php echo $userdetail[0]->address; ?>" placeholder="&nbsp;" class="form-control height56v">
                                 <span class="label"><?php echo $this->lang->line('Street_No'); ?></span>  
                                 <input type="hidden" name="latitude" value="<?php  echo $userdetail[0]->latitude; ?>" id="latitude">
                                  <input type="hidden" name="longitude" value="<?php  echo $userdetail[0]->longitude; ?>" id="longitude">
                                  <span class="error_label" id="addr_err"></span>                             
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                            <div class="form-group form-group-mb-50">
                                <div class="btn-group multi_sigle_select inp_select v_inp_new"> 
                                  <span class="label <?php if(!empty($userdetail[0]->country)) echo "label_add_top"; ?>"> <?php echo $this->lang->line('Country'); ?></span>
                                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle height56v mss_sl_btn" id="countyr_btn"></button>
                      <ul class="dropdown-menu mss_sl_btn_dm">
                        <li class="radiobox-image"><input type="radio" id="id_1" name="country" class="country" value="Germany" <?php if($userdetail[0]->country==1 || $userdetail[0]->country=='Germany') { echo 'checked="checked"'; } ?> data-text="<?php echo $this->lang->line('germany'); ?>"><label for="id_1"><?php echo $this->lang->line('germany'); ?></label></li>
                        <li class="radiobox-image"><input type="radio" id="id_2" name="country" class="country" value="Austria" <?php if($userdetail[0]->country==2 || $userdetail[0]->country=='Austria') { echo 'checked="checked"'; } ?> data-text="<?php echo $this->lang->line('astria'); ?>"><label for="id_2"><?php echo $this->lang->line('astria'); ?></label></li>
                        <li class="radiobox-image"><input type="radio" id="id_3" name="country" class="country" value="Switzerland" <?php if($userdetail[0]->country==3 || $userdetail[0]->country=='Switzerland') { echo 'checked="checked"'; } ?> data-text="<?php echo $this->lang->line('switzerland'); ?>"><label for="id_3"><?php echo $this->lang->line('switzerland'); ?></label></li>
                      </ul>
                                </div>
                                 <label id="country_err" class="error"></label>
                             </div>
                          </div>
                          <div class="col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                            <div class="form-group form-group-mb-50" id="zip_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="&nbsp;" name="zip" id="zipcode" class="form-control height56v onlyNumber" value="<?php echo $userdetail[0]->zip;  ?>" maxlength="5">
                                 <span class="label"><?php echo $this->lang->line('Postcode'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <div class="form-group form-group-mb-50" id="city_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="&nbsp;" value="<?php if(!empty($userdetail[0]->city)) echo $userdetail[0]->city; ?>" class="form-control city height56v" id="city" name="city">
                                 <span class="label"><?php echo $this->lang->line('City'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <div class="form-group form-group-mb-50" id="tax_number_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="&nbsp;" value="<?php if(!empty($userdetail[0]->tax_number)) echo $userdetail[0]->tax_number; ?>" class="form-control city height56v" id="tax_number" name="tax_number">
                                 <span class="label"><?php echo $this->lang->line('Tax_number'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group form-group-mb-50" id="about_validate">
                              <h5 class="font-size-18 color333 fontfamily-medium"><?php echo $this->lang->line('Salon_Description'); ?></h5>
                              
                                 <textarea placeholder="&nbsp;" style="width: 100%;"  name="about" id="about_salon" class="form-control h-100"><?php echo $userdetail[0]->about_salon;  ?></textarea>
                                 <!-- <span class="label">About Salon</span> -->                               
                                    
                            </div>
                            <!--<div id="about_err" class="error"></div>-->
                          </div>
                        </div>
                      </div>

                      
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-5">

                        <div class="relative">
                          <h5 class="font-size-18 color333 fontfamily-medium mb-3"><?php echo $this->lang->line('Salon_work_hours'); ?></h5>
                            <div class="relative px-3 py-3">
              
              <?php $days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday', 'Sunday');
                   $i=0;
                               foreach($days_array as $day){   ?> 
                              <div class="d-flex w-100 align-items-center mb-3 pt-3">
                                <div class="radio-btn display-ib">
                                  <label class="font-size-14 fontfamily-medium">
                                    <input type="checkbox" class="day_checked" name="days[]" id="dayinput<?php echo strtolower($day); ?>" value="<?php echo strtolower($day); ?>" <?php if(!empty($user_available[$i]->days) && $user_available[$i]->days==strtolower($day) && $user_available[$i]->type=='open'){ echo 'checked="checked"'; } ?>>
                                      <span class="radio-round">
                                        <span class="radio-round-chacked"><?php echo strtoupper(substr($this->lang->line(ucfirst($day)),0,1)); ?></span>
                                      </span>       
                                  </label>
                                 
                                </div>
                                 <span id="chk_monday" style="top:68px;" class="error"></span>
                                <div class="relative ml-2 min-width85">
                                  <p class="font-size-14 color333 fontfamily-medium mb-0"><?php echo ucfirst($this->lang->line(ucfirst($day))); ?></p>
                                </div>
                                <div class="form-group mb-0 ml-2" style="width:29%;">
                                  <div class="btn-group multi_sigle_select inp_select min-width120 btnslbl<?php echo strtolower($day); ?> <?php if(!empty($user_available[$i]->starttime)) echo "show"; ?>">
                                    <span class="label <?php if(!empty($user_available[$i]->starttime)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Start_Time'); ?></span>
                                    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn btnstxt<?php echo strtolower($day); ?>" aria-expanded="false"><?php if(!empty($user_available[$i]->starttime)) echo date('H:i',strtotime($user_available[$i]->starttime)); ?></button>                                
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="max-height: none;height: 320px !important;overflow-x: auto;">
                                     
                                 <?php  $start = "00:00"; 
                                      $end = "23:00";
                                      $tStart = strtotime($start);
                                      $tEnd = strtotime($end);
                                      $tNow = $tStart;
                                      $ii=0;
                                      while($tNow <= $tEnd){ ?> 
                                        <li class="radiobox-image">
                                        <input type="radio" id="id_timestart<?php echo $day.$ii; ?>" name="<?php echo strtolower($day); ?>_start" class="start_cls common_time" data-name="<?php echo strtolower($day) ?>" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php if(!empty($user_available[$i]->starttime) && $user_available[$i]->starttime==date("H:i:s",$tNow)) echo "checked"; ?>>
                                        <label for="id_timestart<?php echo $day.$ii; ?>">
                                       <?php echo date("H:i",$tNow) ?>                 
                                      </label>
                                      </li> 
                                <?php $tNow = strtotime('+30 minutes',$tNow);
                                        $ii++;
                                        } ?>
                                    </ul>
                                    
                                 </div>
                                 <span id="Serr_<?php echo strtolower($day); ?>" class="error"></span><!-- error -->
                               </div>
                               <div class="form-group mb-0 ml-2" style="width:29%;">
                                  <div class="btn-group multi_sigle_select inp_select min-width120 btnelbl<?php echo strtolower($day); ?> <?php if(!empty($user_available[$i]->endtime)) echo "show"; ?>">
                                    <span id="end_class" class="label <?php if(!empty($user_available[$i]->endtime)) echo "label_add_top"; ?>"><?php echo $this->lang->line('End_Time'); ?></span>
                                    <button id="cat_btn_end" data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn btnetxt<?php echo strtolower($day); ?>" aria-expanded="false"><?php if(!empty($user_available[$i]->endtime)) echo date('H:i',strtotime($user_available[$i]->endtime)); ?></button>                                
                                   <!-- error -->
                                  
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll"
                                     style="max-height: none;height: 320px !important;overflow-x: auto;">
                 <?php  $start = "01:00"; 
                    $end = "23:00";

                    $tStart = strtotime($start);
                    $tEnd = strtotime($end);
                    $tNow = $tStart;
                    $jj=1;
                    while($tNow <= $tEnd){ ?>

                      <li class="radiobox-image">
                      <input type="radio" id="id_timeend<?php echo $day.$jj; ?>" name="<?php echo strtolower($day); ?>_end" class="common_time" data-name="<?php echo strtolower($day) ?>" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php if(!empty($user_available[$i]->endtime) && $user_available[$i]->endtime==date("H:i:s",$tNow)) echo "checked"; ?>>
                      <label for="id_timeend<?php echo $day.$jj; ?>">
                      <?php echo date("H:i",$tNow) ?>                   
                      </label>
                      </li> 
                  <?php $tNow = strtotime('+30 minutes',$tNow); 
                      $jj++;
                      } ?> 
                                    </ul>
                                     
                                 </div>
                                  <span id="Eerr_<?php echo strtolower($day); ?>" class="error"></span>
                               </div>
                               <?php if(!empty($user_available[$i]->days) && $user_available[$i]->days==strtolower($day) && $user_available[$i]->type=='open'){ ?>
                               <a href="javascript:void(0);" class="font-size-30 color333 a_hover_333 close-salon-icon deleteDayTime" id="cross<?php echo strtolower($day); ?>" data-day="<?php echo strtolower($day); ?>"><img src="<?php echo base_url('assets/frontend/'); ?>images/crose_grediant_orange_icon1.svg" style="width:14px;"></a>
                               <?php } ?>
                              </div>



                           <?php $i++;  }  ?>
                        

                       
                        </div> 
                    </div>
                    <div class="row align-requr-bottom">
                      <div class="col-12">
                        <div class="after-top-line-vinu">
                          <h5 class="color333 fontfamily-medium font-size-18">Anzahl der Stunden vor/nach den Öffnungszeiten</h5>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                        <p class="font-size-12 color333 fontfamily-regular mb-10 display-ib">
                          Lege fest, wie viele Stunden dein Kalender vor bzw. nach deinen Öffnungszeiten haben soll. <br/>
                          Diese Zeiten sind nur vom Salon und nicht von Kunden buchbar.
                        </p>        
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new mb-4"> 
                            <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle height44v mss_sl_btn">
                              <?php echo $userdetail[0]->extra_hrs;?> Stunde<?php echo $userdetail[0]->extra_hrs > 1 ? 'n' : '';?>
                            </button>
                            <ul class="dropdown-menu mss_sl_btn_dm" style="min-width: 90px;">
                              <li class="radiobox-image"><input type="radio" id="extra_hrs_1" name="extra_hrs" class="extra_hrs" value="1" data-text="1 Stunde" <?php if($userdetail[0]->extra_hrs==1) echo 'checked'; ?>>
                                <label for="extra_hrs_1">1 Stunde</label></li>
                              <li class="radiobox-image"><input type="radio" id="extra_hrs_2" name="extra_hrs" class="extra_hrs" value="2" data-text="2  Stunden" <?php if($userdetail[0]->extra_hrs==2) echo 'checked'; ?>>
                                <label for="extra_hrs_2">2 Stunden</label></li>
                              <li class="radiobox-image"><input type="radio" id="extra_hrs_3" name="extra_hrs" class="extra_hrs" value="3" data-text="3  Stunden" <?php if($userdetail[0]->extra_hrs==3) echo 'checked'; ?>>
                                <label for="extra_hrs_3">3 Stunden</label></li>
                              <li class="radiobox-image"><input type="radio" id="extra_hrs_4" name="extra_hrs" class="extra_hrs" value="4" data-text="4  Stunden" <?php if($userdetail[0]->extra_hrs==4) echo 'checked'; ?>>
                                <label for="extra_hrs_4">4 Stunden</label></li>
                              <li class="radiobox-image"><input type="radio" id="extra_hrs_5" name="extra_hrs" class="extra_hrs" value="5" data-text="5  Stunden" <?php if($userdetail[0]->extra_hrs==5) echo 'checked'; ?>>
                                <label for="extra_hrs_5">5 Stunden</label></li>
                              <li class="radiobox-image"><input type="radio" id="extra_hrs_6" name="extra_hrs" class="extra_hrs" value="6" data-text="6  Stunden" <?php if($userdetail[0]->extra_hrs==6) echo 'checked'; ?>>
                                <label for="extra_hrs_6">6 Stunden</label></li>
                            </ul>
                        </div>
                      </div>
                    </div>
                    <div class="row align-requr-bottom">
                      <div class="col-12 col-xl-12 mb-20">
                        <div class="after-top-line-vinu after-bottom-line-vinu">
                          <h5 class="color333 fontfamily-medium font-size-18">Links</h5>

                          <div class="form-group input-group" id="web_link_validate">
                            <label class="font-size-14 color333 fontfamily-medium mb-1">Website</label>
                            <div class="input-group-prepend">
                              <span class="input-group-text" >
                                <img src="<?php echo base_url('assets/frontend/'); ?>images/internet-link-icon.svg" class="link-icon-20" alt="">
                              </span>
                            </div>
                            <input type="text" class="form-control" name="web_link" value="<?php echo $userdetail[0]->web_link; ?>" placeholder="deineseite.de">
                          </div>
                          <div class="form-group input-group" id="fb_link_validate">
                            <label class="font-size-14 color333 fontfamily-medium mb-1">Facebook-Seite</label>
                            <div class="input-group-prepend">
                              <span class="input-group-text" >
                                <img src="<?php echo base_url('assets/frontend/'); ?>images/facebook-link-icon.svg" class="link-icon-20" alt="">
                              </span>
                            </div>
                            <input type="text" class="form-control" name="fb_link" value="<?php echo $userdetail[0]->fb_link; ?>" placeholder="facebook.com/meinFB">
                          </div>
                          <div class="form-group input-group mb-4" id="insta_link_validate">
                            <label class="font-size-14 color333 fontfamily-medium mb-1">Instagram-Seite</label>
                            <div class="input-group-prepend">
                              <span class="input-group-text" >
                                <img src="<?php echo base_url('assets/frontend/'); ?>images/instagram-link-icon.svg" class="link-icon-20" alt="">
                              </span>
                            </div>
                            <input type="text" class="form-control" name="insta_link" value="<?php echo $userdetail[0]->insta_link; ?>" placeholder="instagram.com/meinIG">
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-xl-12 text-left ">
					  
					    <h5 class="color333 fontfamily-medium font-size-18 "><?php echo $this->lang->line('autometicaly_send_invoice'); ?></h5>
                      <div class="vertical-bottom mb-20 after-bottom-line-vinu">
                     <div class="row">
<div class="col-12 col-sm-12">	
<div class="color333 fontfamily-medium font-size-14 mb-3"> 	
   
                        <label class="switch " for="auto_send_invoice" style="top:8px">

                          <input type="hidden" name="auto_send_invoice" id="auto_send_invoice_check" class="togalValue_invoice" value="<?php echo $userdetail[0]->auto_send_invoice; ?>"> 

                          <input type="checkbox" id="auto_send_invoice" name="auto_send_invoice" value="1" <?php if($userdetail[0]->auto_send_invoice==1) echo 'checked'; ?> >
                          <div class="slider auto_send_invoice round"></div>
                        </label>
						</div>
						</div>
						<p class="font-size-12 ml-3 color333 fontfamily-regular mb-10 display-ib"><?php echo $this->lang->line('send_invoice'); ?></p>
                      </div>
					  </div>
					  
					   <h5 class="color333 fontfamily-medium font-size-18u">E-Mail Benachrichtigungen</h5>
                      
                      <div class="vertical-bottom mb-20 ">
                        <!-- <p class="font-size-14 color333 fontfamily-medium mb-10 display-ib"></p>-->
                        <label class="switch mb-10" for="email_noti" style="top:8px">
                          <input type="checkbox" id="email_noti" name="salon_email_setting" value="1" <?php if($userdetail[0]->salon_email_setting==1) echo 'CHECKED'; ?>>
                          <div class="slider round"></div>
                        </label>
						<h6 class="pt-2 mb-0 font-size-12 color333 fontfamily-regular">Erhalte E-Mail Benachrichtigungen über neue Buchungen, Umbuchungen und Stornierungen</h6>
                      </div>
					  
					  
					
                      <h5 class="color333 fontfamily-medium font-size-18 after-top-line-vinu"><?php echo $this->lang->line('Online_booking1'); ?></h5>
                      <div class="vertical-bottom mb-20 after-bottom-line-vinu">
                         <div class="row">
<div class="col-12 col-sm-12">	
<div class="color333 fontfamily-medium font-size-14 mb-3"> 					 
                          <?php 
                          
                          $chk='';
                          $memstatus =getmembership_status_for_onlinebooking($userdetail[0]->id);
                          $clss = "";
                          if(!empty($memstatus) && $memstatus !='yes')
                              $clss = "1";
                           
						   // echo "<pre/>";
						   // print_r($userdetail[0]);exit;
                          if(isset($userdetail[0]->online_booking) && ($userdetail[0]->online_booking == 1)) { $chk='checked="checked"'; } ?>
                            <label class="switch " for="vcheckbox8" style="top:8px">
                              <input type="checkbox" id="vcheckbox8" name="chk_online" <?php echo $chk; ?> data-check="<?php echo $clss; ?>">
                              <div class="slider round"></div>
                          </label>
						  </div>
						  </div>
						  <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
						  <p class="font-size-12 color333 fontfamily-regular mb-10 display-ib"><?php echo $this->lang->line('Online_Booking'); ?> </p>
						  </div>
                      </div>
					  </div>

                   
                        <div class="row">
							            <div class="col-xl-12 col-md-12 col-sm-12">
                            
                             <h5 class="color333 fontfamily-medium font-size-18">Akustische Benachrichtigung</h5>
                          </div>
                          <div class="col-xl-12 col-md-12 col-sm-12">
                            <div class="vertical-bottom mb-20 after-bottom-line-vinu">

                                <label class="switch" for="notifi-sound" style="top:8px">
                                  <input type="hidden" id="notification_sound_setting_check" name="notification_sound_setting" class="togalValue_notification" value="<?php echo $userdetail[0]->notification_sound_setting; ?>">
                                  
                                  <input type="checkbox" id="notifi-sound" name="notification_sound_setting" value="1" <?php if($userdetail[0]->notification_sound_setting==1) echo 'CHECKED'; ?>>
                                  <div class="slider notification_sound_setting round"></div>
                                </label>
                                <h6 class="pt-0 mb-2 color333 fontfamily-regular font-size-12 mt-3">Spiele Töne bei neuen Buchungen, Umbuchungen, Stornierungen etc.</h6>
                             </div>
                             <h5 class="color333 fontfamily-medium font-size-18">Kalenderansicht</h5>
                          </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">                        
                          <div class="btn-group multi_sigle_select inp_select v_inp_new mb-4"> 
                              <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle height44v mss_sl_btn"><?php if(!empty($userdetail[0]->calendar_view) && $userdetail[0]->calendar_view=='day') echo 'Tag'; else echo 'Woche'; ?></button>
                                <ul class="dropdown-menu mss_sl_btn_dm" style="min-width: 90px;">
                                  <li class="radiobox-image"><input type="radio" id="calendar_viewWeek" name="calendar_view" class="" value="week" data-text="Woche" <?php if($userdetail[0]->calendar_view=='week') echo 'checked'; ?>><label for="calendar_viewWeek">Woche</label></li>
                                  <li class="radiobox-image"><input type="radio" id="calendar_viewthreeday" name="calendar_view" class="" value="threeDay" data-text="3 Tage" <?php if($userdetail[0]->calendar_view=='threeDay') echo 'checked'; ?>><label for="calendar_viewthreeday">3 Tage</label></li>  
                                  <li class="radiobox-image"><input type="radio" id="calendar_viewday" name="calendar_view" class="" value="day" data-text="Tag" <?php if($userdetail[0]->calendar_view=='day') echo 'checked'; ?>><label for="calendar_viewday">Tag</label></li>                                
                                </ul>
                                </div>
                             </div>
                          </div>
                      </div>

                      <div class="col-12 col-xl-12 text-left">
                        <h5 class="color333 fontfamily-medium font-size-18 after-top-line-vinu">Wichtige Info</h5>
                        <div class="vertical-bottom mb-20">
                          <p class="pt-0 mb-4 font-size-12 color333 fontfamily-regular">Du kannst deinen Kunden optional zusätzliche Informationen zu ihrem Termin mitteilen. Diese Hinweise werden nur in E-Mails angezeigt.</p>
                          <p class="pt-0 mb-1 font-size-12 color333 fontfamily-regular">Wichtige Hinweise (optional)</p>
                          <textarea class="form-control height56v" name="mail_text" placeholder="z.B. Parkplatz auf der Straße verfügbar"><?php echo $userdetail[0]->email_text; ?></textarea>
                         </div>
                      </div>

                    <!-- pin section -->
                    <div class="col-12 col-xl-12 text-left">
                        <h5 class="color333 fontfamily-medium font-size-18 after-top-line-vinu"><?php echo $this->lang->line('pin_setting'); ?></h5>
                        <div class="vertical-bottom mb-20">
                          <p class="font-size-14 color333 fontfamily-medium mb-10 display-ib"><?php echo $this->lang->line('enable_pin_setting'); ?></p>
                            <label class="switch ml-2" for="vcheckboxpin" style="top:8px">

                              <input type="hidden" name="pinstatus" id="pinstatus_check" class="togalValue_pinstatus" value="<?php echo $userdetail[0]->pinstatus; ?>" id=""> 

                              <input type="checkbox" id="vcheckboxpin" name="pinstatus" value="on" <?php if($userdetail[0]->pinstatus=='on') echo 'checked'; ?>>
                              <div class="slider pinstatus round"></div>
                           </label>
                           <p class="pt-0 mb-4 font-size-12 color333 fontfamily-regular">
                            <?php echo $this->lang->line('pin_setting_description'); ?>
                           </p>
                         </div>
                         <div id="pinsection" class="row <?php if($userdetail[0]->pinstatus=='off') echo 'display-n'; ?>">
                            <div class="col-7 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                              <div class="form-group mobile1-mb-40" id="">
                                  <label class="inp">
                                    <input type="password" placeholder="<?php if(!empty($userdetail[0]->pin)) echo '****' ?>&nbsp;" value="<?php if(!empty($userdetail[0]->pin)) echo '****' ?>" name="pin" class="onlyNumber"class="onlyNumber" id="pinval" maxlength="4">
                                    <span class="label <?php if(!empty($userdetail[0]->pin)) echo 'label_add_top'; ?>"><?php echo $this->lang->line('enter_pin'); ?></span>
                                  </label>
                                  <label id="pinerror" generated="true" class="error">
                                  </label>
                              </div>
                            </div>
                            <div class="col-7 col-sm-4 col-md-4 col-lg-4 col-xl-4 pl-0 pr-0">
                              <div class="form-group mobile1-mb-40" id="">
                                  <label class="inp">
                                    <input type="password" placeholder="<?php if(!empty($userdetail[0]->pin)) echo '****' ?>&nbsp;" value="<?php if(!empty($userdetail[0]->pin)) echo '****' ?>" name="cpin" class="onlyNumber" id="cpinval" maxlength="4">
                                    <span class="label <?php if(!empty($userdetail[0]->pin)) echo 'label_add_top'; ?>"><?php echo $this->lang->line('confirm_pin'); ?></span>
                                  </label>
                                  <label id="confirmpinerror" generated="true" class="error" style="position: inherit;">
                                  </label>
                              </div>
                            </div>
                            <div class="col-5 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                              <?php if(empty($userdetail[0]->pin)){ ?>
                              <a href="JavaScript:Void(0);" id="addpinsave" class="btn"><?php echo $this->lang->line('Save_btn'); ?></a>
                            <?php } else{ ?>
                              <a href="JavaScript:Void(0);" class="btn" data-toggle="modal" data-target="#change-pin" style="text-transform: none;">PIN ändern</a>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                        
                    <div class="col-12 col-xl-12 text-left">
                      <!-- online cancellatios add -->
                      <div class="relative after-top-line-vinu">
                         <h5 class="font-size-18 color333 fontfamily-medium mt-1"><?php echo $this->lang->line('Online_Cancellations'); ?></h5>
                         <div class="row">
                           <div class="col-12 col-sm-12">
                            <div class="vertical-bottom mb-2">
                            <div class="color333 fontfamily-medium font-size-14 mb-3"><?php echo $this->lang->line('Allow_Customers'); ?> 
                              <label class="switch" for="vcheckbox15" style="top:8px">
                                  <input type="checkbox" id="vcheckbox15" name="cancel_booking_allow" value="yes" <?php if(!empty($userdetail[0]->cancel_booking_allow) && $userdetail[0]->cancel_booking_allow=='yes') echo 'checked'; ?>>
                                  <div class="slider round"></div>
                              </label>
                            </div>
                                
                              
                              <!-- <p class="color999 fontfamily-light font-size-12 mb-10 display-b">When this is turned on we will send your customers a cancel link together with the confirmation email.</p> -->
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                             <h6 class="pt-0 mb-3 color333 fontfamily-regular font-size-12"><?php echo $this->lang->line('Amount_of_hours'); ?></h6>
                          </div>
                          <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group mt-0">
                              <div class="btn-group multi_sigle_select inp_select v_inp_new"> 
                              <!--  <span class="label label_add_top"> Hours</span> -->
                                  <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle height44v mss_sl_btn <?php if($userdetail[0]->cancel_booking_allow=='no') echo 'disabled'; ?> cancelOnineHrOpt"><?php if(!empty($userdetail[0]->hr_before_cancel)) echo $userdetail[0]->hr_before_cancel." Stunde".($userdetail[0]->hr_before_cancel==1?'':'n');  $optionAray=array(1,2,3,4,5,12,24,48);  ?></button>
                                    <ul class="dropdown-menu mss_sl_btn_dm">
                                      <?php foreach($optionAray as $a){  ?>
                                        <li class="radiobox-image">
                                          <input type="radio" id="id_hrc<?php echo $a; ?>" name="hr_before_cancel" class="" value="<?php echo $a; ?>" <?php if(!empty($userdetail[0]->hr_before_cancel) && $userdetail[0]->hr_before_cancel==$a) echo 'checked'; ?>>
                                          <label for="id_hrc<?php echo $a; ?>"><?php echo $a; ?> Stunde<?php echo $a == 1 ? '' : 'n';?></label>
                                        </li>
                                      <?php } ?>
                                      <!-- checked="checked" -->
                                    </ul>
                                  </div>                                     
                              </div>
                            </div>                            
                          </div>

                          <!-- QR-Code -->
                          <div id="qrcode" class="col-12 col-xl-12 text-left 
                          <?php 
                          $memstatus =getmembership_status_for_onlinebooking($userdetail[0]->id);
                          if($userdetail[0]->online_booking != 1) echo 'display-n'; ?>">
                            <h5 class="color333 fontfamily-medium font-size-18 after-top-line-vinu">QR-Code</h5>
                              <div class="vertical-bottom mb-20">
                                <p class="pt-0 mb-4 font-size-12 color333 fontfamily-regular">Dies ist dein persönlicher QR Code. Er leitet deine Kunden direkt zu deinem Salonprofil bei styletimer weiter.</p>
                              </div>
                              <div class="vertical-bottom mb-20 ">
                                <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=https%3A%2F%2Fwww.styletimer.de%2Fsalons%2F<?php $str = $userdetail[0]->business_name; $str = str_replace(" ", "-", $str); echo strtolower($str); ?>%2F<?php if(!empty($userdetail[0]->city)) echo $userdetail[0]->city; ?>&choe=UTF-8" title="Link to Marchant Profile" />
                              </div>
                              
                          </div>
                          
                          <?php //echo base_url("taxes/listing"); ?>
                          <div class="button-width-50 after-top-line-vinu">
                            <a id="tax_listing_popup" class="btn widthfit ml-0" href="javascript:void(0)" style="text-transform: unset;"><?php echo ucfirst(strtolower($this->lang->line('Manage_taxes'))); ?></a>
                           <a id="payment_listing_popup" class="btn widthfit mr-0" href="javascript:void(0)" style="text-transform: unset;">Zahlungsmethoden</a>
                          </div>
                      <!-- online cancellatios add -->
                      </div>
                    </div>                    
                  </div>                      
                </div>
                      <div class="col-12 col-sm-6 col-md-4 col-lg-5 col-xl-3 m-auto">
                        <div class="text-center mt-35 pt-1">
                          <input type="hidden" id="shift_check" name="shift_check" value="">
                          <button class="btn btn-large"><?php echo $this->lang->line('Save_btn'); ?></button>
                        </div>
                      </div>
                </form>
            </div>


            <div class="font-size-20 color333 fontfamily-medium mt-20 mb-4" style="margin-top:30px; margin-bottom: 30px"><?php echo $this->lang->line('Your_Gallery'); ?></div>
                <div id="gallery" class="tab-pane active show <?php if(!empty($_GET['tab']) && $_GET['tab']=='gallery') echo 'active show'; ?>">
                <div class="relative around-30 bgwhite box-shadow1">
                  <div class="row">
          <?php if(empty($banerdata->image) || empty($banerdata->image1) || empty($banerdata->image2) || empty($banerdata->image3) || empty($banerdata->image4)){ ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
             <form method="post" id="uploadBannerImage" enctype="multipart/form-data" action="">
                      <div class="relative upl-gallary-parent d-flex justify-content-center align-items-center bglightgray" style="border:1px dashed #00b3bf;">
                        <label class="btn-upload-file">
                              <img src="<?php echo base_url('assets/frontend/images/upl-icon-gallry.png'); ?>">
                              <span class="colorcyan fott-size-12 fontfamily-regular display-b"><?php echo $this->lang->line('upload_images'); ?></span>
                              <input type="file" name="image" id="banner_img_upload"/>
                        </label>  
                      </div>
                      <label id="img_error" class="error_label" style="top: 180px;left: 0px;right: 0px;"></label>
                      </form>              
                    </div>
                    
          <?php } if(!empty($banerdata->image)){ ?> 
			  
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
						  
                      <picture class="upl-gallary-image">
						 <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <img src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image); ?>" type="image/png" class="upl-gallary">
                      </picture>
                      
                         <img src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/',$banerdata->image); ?>" id='image' class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                             <li class="px-13 py-2">
                              <a data-src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/',$banerdata->image); ?>" data-name="<?php echo $banerdata->image; ?>" data-id="image" class="color333 font-size-14 fontfamily-regular cropImage"><?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>

                        <div class="slect-benner-bg">
                        <?php echo $this->lang->line('Banner'); ?>
                        </div>
                      </div>
                    </div>
                    
        <?php } if(!empty($banerdata->image1)){ ?>
			
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
                       
                       <picture class="upl-gallary-image1">
						 <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image1,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image1,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <img src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image1); ?>" type="image/png" class="upl-gallary">
                      </picture>
                      
                         <img src="<?php echo base_url('assets/uploads/banners/'.$banerdata->user_id.'/'.$banerdata->image1); ?>" id='image1' class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                            <li class="px-13 py-2">
                              <a href="<?php echo base_url('profile/update_banner/'.$banerdata->image1.'?field=image1');?>" class="color333 font-size-14 fontfamily-regular setAsBanner" data-name="<?php echo $banerdata->image1; ?>"><?php echo $this->lang->line('Set_as_Banner'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a onclick="return deleteBnanerImage('<?php echo base_url('profile/delete_banner_image/'.$banerdata->image1.'?field=image1');?>');" class="color333 font-size-14 fontfamily-regular deleteImage" data-name="<?php echo $banerdata->image1; ?>"><?php echo $this->lang->line('Delete_Picture'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a data-src="<?php echo base_url('assets/uploads/banners/'.$banerdata->user_id.'/'.$banerdata->image1); ?>" data-name="<?php echo $banerdata->image1; ?>" data-id="image1" class="color333 font-size-14 fontfamily-regular cropImage"><?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>
                        <!-- <div class="slect-benner-bg">
                          Banner
                        </div> -->
                      </div>
                    </div>
                    
          <?php } if(!empty($banerdata->image2)){ ?>
			  
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
						  
					  <picture class="upl-gallary-image2">
						 <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image2,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image2,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <img src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image2); ?>" type="image/png" class="upl-gallary">
                      </picture>
                        
                        <img src="<?php echo base_url('assets/uploads/banners/'.$banerdata->user_id.'/'.$banerdata->image2); ?>" id='image2' class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                            <li class="px-13 py-2">
                              <a href="<?php echo base_url('profile/update_banner/'.$banerdata->image2.'?field=image2');?>"  class="color333 font-size-14 fontfamily-regular setAsBanner" data-name="<?php echo $banerdata->image2; ?>"><?php echo $this->lang->line('Set_as_Banner'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a onclick="return deleteBnanerImage('<?php echo base_url('profile/delete_banner_image/'.$banerdata->image2.'?field=image2');?>');" class="color333 font-size-14 fontfamily-regular deleteImage" data-name="<?php echo $banerdata->image2; ?>"><?php echo $this->lang->line('Delete_Picture'); ?></a>
                            </li>
                             <li class="px-13 py-2">
                              <a data-src="<?php echo base_url('assets/uploads/banners/'.$banerdata->user_id.'/'.$banerdata->image2); ?>" data-name="<?php echo $banerdata->image2; ?>" data-id="image2" class="color333 font-size-14 fontfamily-regular cropImage"> <?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>
                        <!-- <div class="slect-benner-bg">
                          Banner
                        </div> -->
                      </div>
                    </div>
                    
            <?php } if(!empty($banerdata->image3)){ ?>
				
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
                      
                      <picture class="upl-gallary-image3">
						   
						 <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image3,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image3,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <img src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image3); ?>" type="image/png" class="upl-gallary">
                         
                      </picture>
                      
                         <img src="<?php echo base_url('assets/uploads/banners/'.$banerdata->user_id.'/'.$banerdata->image3); ?>" id='image3' class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                            <li class="px-13 py-2">
                              <a href="<?php echo base_url('profile/update_banner/'.$banerdata->image3.'?field=image3');?>" class="color333 font-size-14 fontfamily-regular setAsBanner" data-name="<?php echo $banerdata->image3; ?>"><?php echo $this->lang->line('Set_as_Banner'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a onclick="return deleteBnanerImage('<?php echo base_url('profile/delete_banner_image/'.$banerdata->image3.'?field=image3');?>');" class="color333 font-size-14 fontfamily-regular deleteImage" data-name="<?php echo $banerdata->image3; ?>"><?php echo $this->lang->line('Delete_Picture'); ?></a>
                            </li>
                             <li class="px-13 py-2">
                              <a data-src="<?php echo base_url('assets/uploads/banners/'.$banerdata->user_id.'/'.$banerdata->image3); ?>" data-name="<?php echo $banerdata->image3; ?>" data-id="image3" class="color333 font-size-14 fontfamily-regular cropImage"><?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>
                        <!-- <div class="slect-benner-bg">
                          Banner
                        </div> -->
                      </div>
                    </div>
                    
            <?php } if(!empty($banerdata->image4)){ ?>
				
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
                      
                      <picture class="upl-gallary-image4">
						   
						 <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image4,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image4,'webp'); ?>" type="image/webp" class="upl-gallary">
                         <img src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->user_id.'/','crop_'.$banerdata->image4,'png'); ?>" type="image/png" class="upl-gallary">
                         
                      </picture>
                      
                         <img src="<?php echo base_url('assets/uploads/banners/'.$banerdata->user_id.'/'.$banerdata->image4); ?>" id="image4" class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                            <li class="px-13 py-2">
                              <a href="<?php echo base_url('profile/update_banner/'.$banerdata->image4.'?field=image4');?>"  class="color333 font-size-14 fontfamily-regular setAsBanner" data-name="<?php echo $banerdata->image4; ?>"><?php echo $this->lang->line('Set_as_Banner'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a onclick="return deleteBnanerImage('<?php echo base_url('profile/delete_banner_image/'.$banerdata->image4.'?field=image4');?>');" class="color333 font-size-14 fontfamily-regular deleteImage" data-name="<?php echo $banerdata->image4; ?>"><?php echo $this->lang->line('Delete_Picture'); ?></a>
                            </li>
                             <li class="px-13 py-2">
                              <a data-src="<?php echo base_url('assets/uploads/banners/'.$banerdata->user_id.'/'.$banerdata->image4); ?>" data-name="<?php echo $banerdata->image4; ?>" data-id="image4" class="color333 font-size-14 fontfamily-regular cropImage"><?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>
                        <!-- <div class="slect-benner-bg">
                          Banner
                        </div> -->
                      </div>
                    </div>
          <?php } ?>
                  
                      </div>
                    </div>
                  </div>
                  <div class="font-size-20 color333 fontfamily-medium mt-20 mb-4" style="margin-top:30px; margin-bottom: 30px">Präsentiere Bilder deiner Arbeit in deinem Profil</div>
                  <div id="gallery1" class="tab-pane active show">
                    <div class="relative around-30 bgwhite box-shadow1">
                      <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                          <form method="post" id="uploadBannerImage1" enctype="multipart/form-data" action="">
                            <div class="relative upl-gallary-parent d-flex justify-content-center align-items-center bglightgray" style="border:1px dashed #00b3bf; height:207px;">
                              <label class="btn-upload-file">
                                    <img src="<?php echo base_url('assets/frontend/images/upl-icon-gallry.png'); ?>">
                                    <span class="colorcyan fott-size-12 fontfamily-regular display-b"><?php echo $this->lang->line('upload_images'); ?></span>
                                    <input type="file" name="image" id="banner_img_upload1"/>
                              </label>  
                            </div>
                            <label id="img_error" class="error_label" style="top: 180px;left: 0px;right: 0px;"></label>
                          </form>              
                        </div>
                        <?php if (!$gbanerdata) $gbanerdata = []; ?>
                        <?php foreach ($gbanerdata as $banerdata) { ?>
                          <div class="col-12 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                            <div class="relative upl-gallary-parent">
                              <picture class="upl-gallary-image">
                                <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/','crop_'.$banerdata->image,'webp'); ?>" type="image/webp" class="upl-gallary">
                                <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/','crop_'.$banerdata->image,'webp'); ?>" type="image/webp" class="upl-gallary">
                                <img src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/','crop_'.$banerdata->image); ?>" type="image/png" class="upl-gallary">
                              </picture>
                              
                              <img src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/',$banerdata->image); ?>" id='image' class="upl-gallary display-n" />
                              <?php
                                $emp = $this->user->select_row('st_users','*', array('id' => $banerdata->employee_id));
                                $cat = $this->user->select_row('st_merchant_category','*', array('id' => $banerdata->category_id));
                                $sub = $this->user->select_row('st_category','*', array('id' => $cat->subcategory_id));
                              ?>
                              <div>
                                <label for="employeeId<?=$emp->id?>" class="height48v vertical-middle pt-2">
                                  <img class="employee-round-icon display-ib mr-1" src="<?php echo $emp->profile_pic !='' ? (base_url('assets/uploads/employee/').$emp->id.'/'.$emp->profile_pic) : (base_url('assets/frontend/images/user-icon-gret.svg'))?>">
                                  <?php echo $emp->first_name.' '.$emp->last_name;?>
                                </label>
                              </div>
                              <div>
                                <?php echo $cat->name ? $sub->category_name . ' - ' . $cat->name : $sub->category_name;?>
                              </div>
                              <div class="dropdown">
                                <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                                </div>
                                <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                                  <li class="px-13 py-2">
                                    <a onclick="return deleteBnanerImage('<?php echo base_url('profile/delete_gal_banner_image/'.url_encode($banerdata->id));?>');" class="color333 font-size-14 fontfamily-regular deleteImage"><?php echo $this->lang->line('Delete_Picture'); ?></a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <!-- dashboard right side end -->

    </div>

    <!-- modal start -->
    <div class="modal fade" id="salon-working-table">
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
            <h5 class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1"><?php echo $this->lang->line('delete_working_hr_confirm'); ?></h5>
            <input type="hidden" id="salon_close_day" name="" value="">
            <button type="button" id="close_workinghour" class="btn btn-large widthfit"><?php echo $this->lang->line('Delete'); ?></button>
          </div>
        </div>
      </div>
    </div>

     <!-- modal start -->
    <div class="modal fade" id="service-Changetime-table">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="conftime_close">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/icon_cmp.png'); ?>">
            <h5 class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1">Bist du sicher, dass du deine Öffnungszeiten ändern möchtest? Dies hat Auswirkungen auf die Arbeitszeiten deiner Mitarbeiter.</h5>
             <input type="hidden" id="" name="" value="">
            <button type="button" class=" btn btn-large widthfit display-ib" id="changetime_done">ok</button>
            <a href="<?php echo base_url('merchant/employee_shift'); ?>" class=" btn btn-large widthfit display-ib" id="">Zu den Arbeitszeiten</a>
          </div>
        </div>
      </div>
    </div>

    <!-- page-modal -->
    <div class="modal fade" id="TaxListing">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
        <div class="modal-content text-left">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0 relative">
              <div class="d-flex justify-content-between align-items-end p-3">
                <div class="relative">
                  <h3 class="font-size-24 color333 fontfamily-semibold"><?php echo $this->lang->line('Taxes'); ?></h3>
                  <p class="font-size-14 colorgray fontfamily-regular mb-0"><?php echo $this->lang->line('Add_your_tax'); ?></p>
                </div>
                <div class="">
                  <button class="btn widthfit" id="add_new_taxes" data-backdrop="static" data-keyboard="false" data-dismiss="modal" style="text-transform: unset;"><?php echo $this->lang->line('New_tax'); ?></button>
                </div>
              </div>

              <div class="my-table service-list-table col-12 col-sm-12">
                <table class="table">
                    <thead>
                        <tr>
                          <th class="text-center"><?php echo $this->lang->line('Tax'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Taxrate'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Default'); ?></th>
                          <th class="text-center"><?php echo ucfirst(strtolower($this->lang->line('Action'))); ?> </th>
                        </tr>
                    </thead>
                    <tbody id="add_taxes_list">
                   </tbody>
                </table>
                </div>
         
          </div>
        </div>
      </div>
    </div>
   

    <!-- modal start -->
    <div class="modal fade add-new-tax">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-left">      
          <a href="javascript:void(0)" class="crose-btn close_taxes_popup" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0">
            <h3 class="color333 fontfamily-medium font-size-24 border-b pb-3 text-center"><?php echo $this->lang->line('New_tax'); ?></h3>
            <div class=" pl-3 pr-3 pt-2">
              <p class="font-size-14 colorgray fontfamily-regular"><?php echo $this->lang->line('set_the_tax'); ?></p>
             <form id="addTaxform" method="post" action="<?php echo base_url("taxes/add"); ?>">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-6">
                  <div class="form-group" id="">
                    <span class="label"><?php echo $this->lang->line('Tax_name'); ?></span>
                      <label class="inp">
                        <input type="text" id="ad_taxname" name="name" placeholder="&nbsp;" class="form-control" autocomplete="off">
                      </label>  
                      <span class="error_label" style="position: relative;" id="ad_taxname_error"></span>                                              
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-6">
                  <div class="form-group" id="">
                    <span class="label"><?php echo $this->lang->line('Tax_rate'); ?></span>
                      <label class="inp">
                        <span class="rate-fixed">%</span>
                        <input type="text" id="ad_taxper" name="rate" placeholder="&nbsp;" class="form-control onlyNumber" autocomplete="off">
                      </label>      
                      <span class="error_label" style="position: relative;" id="ad_taxper_error"></span>                                          
                  </div>
                </div>
              </div>
                <button type="button" data-frm="addTaxform" data-url="add" class=" btn btn-blue btn-large mt-4 save_taxes_value"><?php echo $this->lang->line('Save_btn'); ?></button>
               </form>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade edit-tax" id="editTax">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-left">      
          <a href="javascript:void(0)" class="crose-btn close_taxes_popup" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0">
            <h3 class="color333 fontfamily-medium font-size-24 border-b pb-3 text-center"><?php echo $this->lang->line('Edit_Tax'); ?>
            </h3>
            <div class=" pl-3 pr-3 pt-2">
              <p class="font-size-14 colorgray fontfamily-regular"><?php echo $this->lang->line('set_the_tax'); ?></p>
             <form method="post" id="editTaxes" action="<?php echo base_url('taxes/edit'); ?>">
             <input type="hidden" name="eid" id="eid" value="">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-6">
                  <div class="form-group" id="ename_validate">
                    <span class="label"><?php echo $this->lang->line('Tax_name'); ?></span>
                      <label class="inp">
                        <input type="text" name="ename" id="taxname" placeholder="&nbsp;" class="form-control" value="">
                      </label>   
                      <span class="error_label" style="position: relative;" id="taxname_error"></span>                                             
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-6">
                  <div class="form-group" id="erate_validate">
                    <span class="label"><?php echo $this->lang->line('Tax_rate'); ?></span>
                      <label class="inp">
                        <span class="rate-fixed">%</span>
                        <input type="text" name="erate" id="taxper" placeholder="&nbsp;" class="form-control" value="">
                      </label>
                      <span class="error_label" style="position: relative;" id="taxper_error"></span>                                                
                  </div>
                </div>
              </div>
                <button type="button" data-frm="editTaxes" data-url="edit" class=" btn btn-blue btn-large mt-4 save_taxes_value"><?php echo $this->lang->line('Save_btn'); ?></button>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="confirm-editprofile-popup">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a id="close_box" href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/icon_cmp.png'); ?>">
            <h5 class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1">Bist du sicher, dass du deine Öffnungszeiten ändern möchtest? Dies hat Auswirkungen auf die Arbeitszeiten deiner Mitarbeiter. </h5>
            <input type="hidden" id="check_confirm_yes" name="" value="ok">
            <button id="ok_confirm" type="button" class=" btn btn-large widthfit mr-1">Ok</button>
            <button type="button" href="javascript:void(0)" class=" btn btn-large widthfit" id="updatetoshift">Zu den Arbeitszeiten</button>
            <?php //echo base_url('merchant/employee_shift'); ?>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="membership_exp_onlinesetup">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="conftime_close">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
         
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/rocketneu.png'); ?>" width="76" height="76">
            <!-- <h5 class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:3;"> -->
               <!-- <?php
               //echo $_SESSION['sty_membership']; ?> </h5> -->
                       <h5 class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:5;" id="online_booking_error_message">
                       </h5> 
           
            <a href="<?php echo base_url('membership')?>"  class=" btn btn-large widthfit display-ib" > zu unseren Mitgliedschaften</a>
          </div>
        </div>
      </div>
    </div>
    
   
<!------------------------------------------------ add payment method-------------------------------------------------------------------------------->



 <div class="modal fade" id="PaymentMethodListing">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
        <div class="modal-content text-left">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-40 mb-30 px-0 relative">
              <div class="d-flex justify-content-between align-items-end p-3">
                <div class="relative">
                  <h3 class="font-size-24 color333 fontfamily-semibold">Zahlungsmethoden</h3>                  
                </div>
                <div class="">
                  <button class="btn widthfit" id="add_new_paumentmethod" data-backdrop="static" data-keyboard="false" data-dismiss="modal" style="text-transform: unset;">Neue Zahlungsmethode hinzufügen</button>
                </div>
              </div>

              <div class="my-table service-list-table col-12 col-sm-12">
                <table class="table">
                    <thead>
                        <tr>
                          <th class="text-center">Name der Zahlungsmethode</th>
                          <th class="text-center"><?php echo $this->lang->line('Default'); ?></th>
                          <th class="text-center"><?php echo ucfirst(strtolower($this->lang->line('Action'))); ?> </th>
                        </tr>
                    </thead>
                    <tbody id="add_payment_method_list">
                   </tbody>
                </table>
              </div>         
          </div>
        </div>
      </div>
    </div>


 <!-- modal start -->
    <div class="modal fade add-new-paymentmethod" id="">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-left">      
          <a href="javascript:void(0)" class="crose-btn close_paymethod_popup" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30">
				<form id="addPaymentMethodform" method="post" action="<?php echo base_url("payment_methods/add"); ?>" style="position: relative;">
					<div class="" style="position: relative;">
					<h3 class="color333 fontfamily-medium font-size-24 border-b pb-3 text-center"><?php echo 'Neue Zahlungsmethode hinzufügen'; ?></h3>
						<div class=" pl-3 pr-3 pt-2">
						  <p class="font-size-14 colorgray fontfamily-regular"></p>
					   
						  <div class="row">
								
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-12">
							  <div class="form-group">
								<span class="label"><?php echo 'Name der Zahlungsmethode'; ?></span>
								  <label class="inp">
									<input type="text" id="ad_method" name="name" placeholder="&nbsp;" class="form-control" autocomplete="off">
								  </label>  
								  <span class="error_label" style="position: relative;" id="ad_method_error"></span>                                              
							  </div>
							</div>
							
						  </div>
							<button type="button" data-frm="addPaymentMethodform" data-url="add" class=" btn btn-blue btn-large mt-4 save_paymentmethod_value"><?php echo $this->lang->line('Save_btn'); ?></button>
						   
						</div>
						</div>
					</form>
          </div>
        </div>
      </div>
    </div>

 <!-- modal start -->
    <div class="modal fade" id="editpaymentmethod">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-left">      
          <a href="javascript:void(0)" class="crose-btn close_paymentmethod_popup" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-40 mb-30 px-0">			  
              <form method="post" id="editpaymentmethodform" action="<?php echo base_url('payment_methods/edit'); ?>">
                <h3 class="color333 fontfamily-medium font-size-24 border-b pb-3 text-center"><?php echo $this->lang->line('edit_payment_methods'); ?>
                </h3>
                <div class=" pl-3 pr-3 pt-2">
                  <p class="font-size-14 colorgray fontfamily-regular"><?php //echo $this->lang->line('set_the_tax'); ?></p>
                <input type="hidden" name="paymentmethodid" id="paymentmethodid" value="">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-12">
                      <div class="form-group" id="">
                        <span class="label"><?php echo 'Name der Zahlungsmethode'; ?></span>
                          <label class="inp">
                            <input type="text" name="ename" id="paymentmethod" placeholder="&nbsp;" class="form-control" value="">
                          </label>   
                          <span class="error_label" style="position: relative;" id="paymentmethod_error"></span>                                             
                      </div>
                    </div>
                    
                  </div>
                  <button type="button" data-frm="editpaymentmethodform" data-url="edit" class=" btn btn-blue btn-large mt-4 save_paymentmethod_value"><?php echo $this->lang->line('Save_btn'); ?></button>
               </form>
            </div>
          </div>
        </div>
      </div>
    </div>

      
<div id="change-pin" class="modal" role="dialog">
  <div class="modal-dialog modal-md modal-dialog-centered" style="max-width:350px;">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
      </a>
        <div class="modal-body text-left">
          <div class="relative align-self-center w-100">          
            <h2 class="font-size-22 color333 fontfamily-medium mb-4 text-center">PIN ändern</h2>
             <div class="relative">
               <form id="changepin">
                  <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                      <p class="mb-40 font-size-14 fontfamily-regular color666 text-center">Bitte alte und neue PIN eingeben</p>
                      <div class="form-group form-group-mb-50 height60" id="">
                        <label class="inp">  
                            <input type="password" id="old_pin" name="old_pin" placeholder="&nbsp;" maxlength="4" class="form-control onlyNumber">
                            <span class="label">Alte PIN*</span>   
                        </label> 
                        <label id="old_pinerror" generated="true" class="error"></label>                                               
                      </div>
                      <div class="form-group form-group-mb-50 height60" id="">
                        <label class="inp">  
                            <input type="password" id="new_pin" name="new_pin" placeholder="&nbsp;" maxlength="4" class="form-control onlyNumber">
                            <span class="label">Neue PIN*</span>                                               
                        </label>    
                        <label id="new_pinerror" generated="true" class="error"></label>                                             
                      </div>
                      <div class="form-group form-group-mb-50 height60" id="">
                        <label class="inp">  
                            <input type="password" id="confirm_pin" name="confirm_pin" placeholder="&nbsp;" maxlength="4" class="form-control onlyNumber">
                            <span class="label">PIN bestätigen*</span>                                               
                        </label>    
                        <label id="confirm_pinerror" generated="true" class="error"></label>                                             
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                      <div class="form-group text-center">
                          <button class="btn width180" type="button" id="savechangepin" >Speichern</button> 
                      </div>
                    </div>
                  </div>
               </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

   

<!------------------------------------------------ --------------------------------------------------------------------------------------------------->



 <?php $this->load->view('frontend/common/footer_script');
        $this->load->view('frontend/common/editer_js');
 $this->load->view('frontend/common/crop_pic'); ?>
 <?php $pinstatus = get_pin_status($this->session->userdata('st_userid')); 
//echo current_url().'=='.$_SERVER['HTTP_REFERER'];
    if($pinstatus->pinstatus=='on'){
        $this->load->view('frontend/marchant/profile/ask_pin_popup');
      }  
  ?>
 
<script type="text/javascript" src="<?php echo base_url('assets/cropp/croppie.js'); ?>"></script>


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
    </script>
    <script>
       $('#datepicker').datepicker({
           uiLibrary: 'bootstrap4'
       });
    </script>
    <script type="text/javascript">

      $('.clockpicker').clockpicker();
$("#countyr_btn").text($("input[name='country']:checked").attr('data-text'));
      function readURL(input,id) {

      if (input.files && input.files[0]) {
      var reader = new FileReader();
              var fileInput = input;
      var filePath = fileInput.value;
      var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.svg)$/i;
      if(!allowedExtensions.exec(filePath)){
        alert('Please upload file having type jpeg,jpg,png,gif,svg only');
        fileInput.value = '';
        return false;
      }
      reader.onload = function(e) {
        if(id=="imgInp"){
          $('#'+id).addClass("large_image");
        }else{
              //$('#'+id).addClass("small_image");
              $('#'+id).addClass("upload-vinay-img");
              
            }
        $('#'+id).attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
      }
    }

    $(".imgInp").change(function() {
      var id=$(this).attr('data-id');
      readURL(this,id);
    });

    $(document).on('click', '.extra_hrs', function() {
      const hr = $('input[name="extra_hrs"]:checked').val();
      loading();
      $.ajax({
        url: base_url + "profile/salon_update_extra_hrs",
        type: "POST",
        data: {
          hrs: hr,
        },
        success: function (response) {
          //var obj = jQuery.parseJSON( response );
          unloading();
        },
      });
    });

    $(document).on('click','.auto_send_invoice',function(){ 
       var val =  $('#auto_send_invoice_check').val(); 
      if(val==='1'){
        var tem = 0;
        $(".togalValue_invoice").val(tem);
      }else{
        var tem = 1;
        $(".togalValue_invoice").val(tem);
      }
    });

    $(document).on('click','.notification_sound_setting',function(){ 
       var val =  $('#notification_sound_setting_check').val(); 
      if(val==='1'){
        var tem = 0;
        $(".togalValue_notification").val(tem);
      }else{
        var tem = 1;
        $(".togalValue_notification").val(tem);
      }
    });

    $(document).on('click','.pinstatus',function(){ 
       var val =  $('#pinstatus_check').val(); 
      if(val=== 'on'){
        var tem = 'off';
        $(".togalValue_pinstatus").val(tem);
      }else{
        var tem = 'on';
        $(".togalValue_pinstatus").val(tem);
      }
    });


  $(document).on('click','#zipcode',function(){      //#address,
    
    $("#latitude").val("");
    $("#longitude").val("");
    
  }); 
  $(document).on('blur','#zipcode',function(){   //#address,
  
          getaddress();
    
    }); 
  $(document).on('change','.country',function(){
      $("#latitude").val("");
      $("#longitude").val("");
      getaddress();
    
  }); 

  function getaddress(){
    
    var country= $("input[name='country']:checked").attr('data-text');
        var zipcode=$("#zipcode").val();
        var address="";
        var location=$("#address").val();
        if(location!=""){
			address=location;
			}
        var c_code='de';
        if(zipcode =="")
          { return false; }
        // if(zipcode!=undefined && zipcode!=""){
        //     address=address+" "+zipcode;

        //   }
         if(zipcode!=undefined && zipcode!=""){
             address= zipcode;

           }

         // console.log("address-------",address)
        
        if(country!=undefined && country!=""){
          address=address+" "+country;
         if(country == 'Austria')
            c_code = 'at';
         else if(country == 'Switzerland')
            c_code = 'ch';
        }
      
     // var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&q="+c_code+"&postalcode="+address+"&street="+location+"&addressdetails=1&format=json";
     var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&q="+address+"&addressdetails=1&format=json&countrycodes=de";

         loading();
         $.get(gurl,function(data){
          var count = data.length;
          if(data.length > 0){
          // if(data[0].address.town!=undefined)
          // var citty = data[0].address.town
          // else if(data[0].address.city!=undefined)
          // var citty = data[0].address.city
          // else
          // var citty = "";
           $.each(data, function () {
              var citty = "";
               if(data[0].address.town!=undefined)
                  citty = data[0].address.town
              else if(data[0].address.city!=undefined)
                citty = data[0].address.city
              else if(data[0].address.municipality!=undefined)    
                citty = data[0].address.municipality
             else  if(data[1].address.town!=undefined)
                  citty = data[1].address.town
            else if(data[1].address.city!=undefined)
                citty = data[1].address.city
            else if(data[1].address.municipality!=undefined)    
              citty = data[1].address.municipality
            else  if(data[2].address.town!=undefined)
                  citty = data[2].address.town
            else if(data[2].address.city!=undefined)
                citty = data[2].address.city
            else if(data[2].address.municipality!=undefined)    
              citty = data[2].address.municipality
              else
                citty = "";
            $("#city").val(citty);
            
            $("#latitude").val(data[0].lat);
            $("#longitude").val(data[0].lon);
            $('#addr_err').html('');
            });

         
          unloading();
          }
          else{
            $("#location_validate label.error").css('display','none');
            $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
            $("#latitude").val('');
              unloading();
            return false;
            }  
          }).fail(function() {
           $("#location_validate label.error").css('display','none');
            $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
          
            $("#latitude").val('');
              unloading();
            return false;
          });

      }

//------------------------------pinjs-----------------------------------------

$(document).on('change','#vcheckboxpin',function(){
  if($(this).prop("checked") == true){
      $('#pinsection').removeClass('display-n');
      var pinstatus ='on';
    
    }else{
    $('#pinsection').addClass('display-n');
     var pinstatus ='off';
   }
 var pinval = $("#pinval").val();
 if(pinval=='****'){
  loading();
      $.ajax({
                url: base_url+'profile/updatepinstatus',
                type: "POST",
                data:{pinoption:pinstatus},
                success: function (response) {
                  unloading();
                  location.reload();
 
                 }
            });
  }

});

//------------------------------qrcodejs-----------------------------------------

$(document).on('change','#vcheckbox8',function(){
  if($(this).prop("checked") == true){
      $('#qrcode').removeClass('display-n');
      var cancel_booking_allow ='yes';
    
    }else{
    $('#qrcode').addClass('display-n');
     var cancel_booking_allow ='no';
   }
});

$(document).on('click','#addpinsave',function(){
  if($('#vcheckboxpin').prop("checked")==true){
   var pinoption = $("input[name='pinstatus']:checked").val();

    var pinval = $("#pinval").val();
    var cpinval = $("#cpinval").val();
 var token =true;
    if(pinval.length<4){
      $("#pinerror").html('Bitte 4-stellige Pin eingeben.');
      token=false;
    }else{
		$("#pinerror").html('');
		}
     if(cpinval.length<4){
		$("#confirmpinerror").html('Bitte 4-stellige Pin eingeben.');
		token=false;
		}
	else if(cpinval != pinval){
		$("#confirmpinerror").html('PIN und Bestätigung stimmen nicht überein');
		token=false;
		}
	else{
		$("#confirmpinerror").html('');
		}		
    if(token==true){
     loading();
        $.ajax({
                url: base_url+'profile/addpin',
                type: "POST",
                data:{pin:pinval,pinoption:pinoption},
                success: function (response) {
                   unloading();
                  location.reload();
 
                 }
            });

    }else{
		
		return false;
		}

   }
});

$(document).on('click','#savechangepin',function(){

   
    var old_pinval = $("#old_pin").val();
    var new_pinval = $("#new_pin").val();
    var confirm_pinval = $("#confirm_pin").val();
   var token =true;
    if(old_pinval.length<4){
      $("#old_pinerror").html('Bitte 4-stellige Pin eingeben.');
      token=false;
      //return false;
    }else{
       $("#old_pinerror").html('');
     }
   if(new_pinval.length<4){
      $("#new_pinerror").html('Bitte 4-stellige Pin eingeben.');
      token=false;
      //return false;
    }else{
       $("#new_pinerror").html('');
     }
     
     if(confirm_pinval.length<4){
		$("#confirm_pinerror").html('Bitte 4-stellige Pin eingeben.');
		token=false;
		}
	else if(confirm_pinval != new_pinval){
		$("#confirm_pinerror").html('PIN und Bestätigung stimmen nicht überein');
		token=false;
		}
	else{
		$("#confirm_pinerror").html('');
		}	 

    if(token==true){
     loading();
        $.ajax({
                url: base_url+'profile/changepin',
                type: "POST",
                data:{old_pin:old_pinval,new_pin:new_pinval},
                success: function (response) {
                  unloading();
                  var obj = $.parseJSON(response);
                  if(obj.success==1){
                    location.reload();
                  }else{
                   $("#old_pinerror").html(obj.message);
                  }

                   
                  
 
                 }
            });
    }else{
      return false;
    }

    

   
});

//------------------------------pinjsend-----------------------------------------

function deleteBnanerImage(url){
   Swal.fire({
      title: are_you_sure,
      text: "<?php echo $this->lang->line('you_want_delete_image'); ?>",
      type: 'warning',
      showCancelButton: true,
      reverseButtons:true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<?php echo $this->lang->line("Submit"); ?>',
      cancelButtonText: '<?php echo $this->lang->line("abort"); ?>'
    }).then((result) => {
    if (result.value) {
      $.ajax({
                url: url,
                type: "POST",
                success: function (response) {
          location.href=base_url+'profile/edit_marchant_profile';
 
                 }
            });
      
      }else{
        return false;
        }
    });  

}

$(document).ready(function(){
    $image_crop = $('#image_demo').croppie({
      enableExif: false,
      viewport: {
        width:300,
        height:200,
        type:'square' //circle
      },
      boundary:{
        width:450,
        height:300
      }
    });
    $image_crop1 = $('#image_demo1').croppie({
      enableExif: false,
      viewport: {
        width:250,
        height:300,
        type:'square' //circle
      },
      boundary:{
        width:350,
        height:450
      }
    });

 $(document).on('change','#banner_img_upload', function(){
    var reader = new FileReader();
    //console.log(reader);
    reader.onload = function (event) {
		 $("#cropImageName").val('add');
      $image_crop.croppie('bind', {
        url:event.target.result
      }).then(function(){
        //console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

  $(document).on('click','.cropImage', function(){
    //var reader = new FileReader();
    var imgurl=$(this).attr('data-src');
    var iname=$(this).attr('data-name');
    var tname=$(this).attr('data-id');
    
    var myImg = document.querySelector("#"+tname);
    var realWidth = myImg.naturalWidth;
    var realHeight = myImg.naturalHeight;
    //alert("Original width=" + realWidth + ", " + "Original height=" + realHeight);
    if(realWidth>=672 && realHeight>=448){
      $("#cropImageName").val(iname);
      $image_crop.croppie('bind', {
        url:imgurl
      }).then(function(){
        console.log('jQuery bind complete');
      });
      
      //reader.readAsDataURL(this.files[0]);
      $('#uploadimageModal').modal('show');
    }
    else{
      Swal.fire({
        title: 'Dieses Größe des Bild kann nicht verändert werden, da es kleiner als 672 x 448px ist.',
        width: 600,
        padding: '3em'
      });
    } 
  });

  $('.crop_image').click(function(event){
      
    $image_crop.croppie('result', {
      type: 'canvas',
      size:{
        width:672,
        height:448
      }
    }).then(function(response){
      loading();
      var iname=$("#cropImageName").val(); 
    
      if(iname=='add')
      {
		    var urlreq ="<?php echo base_url('profile/upload_banner_img'); ?>";
	    }else{
		    var urlreq ="<?php echo base_url('profile/imagecropp'); ?>";
		  } 
      $.ajax({
        url:urlreq,
        type: "POST", 
        data:{"image": response,name:iname},
        success:function(data)
        { 
          var obj = $.parseJSON(data);
          if(obj.success=='1'){
            $('#success_message').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Galeriebild erfolgreich bearbeitet</div>');

            setTimeout(function(){ window.location.reload(); },500);
          }
        }
      });
    });
  });

  $(document).on('click','#banner_img_upload1', function(e){
    if ($("#imageLoaded").html()) {
      e.preventDefault();
      e.stopPropagation();
      $('#uploadimageModal1').modal('show');
    } else {
      $("#galServiceId").html('');
    }
  });

  $(document).on('change','#banner_img_upload1', function(){
    var reader = new FileReader();
    //console.log(reader);
    reader.onload = function (event) {
		 $("#cropImageName1").val('add');
      $image_crop1.croppie('bind', {
        url:event.target.result
      }).then(function(){
        //console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal1').modal('show');
    $("#imageLoaded").html('loaded');
  });

  $(document).on('click','.cropImage1', function(){
    //var reader = new FileReader();
    var imgurl=$(this).attr('data-src');
    var iname=$(this).attr('data-name');
    var tname=$(this).attr('data-id');
    
    var myImg = document.querySelector("#"+tname);
    var realWidth = myImg.naturalWidth;
    var realHeight = myImg.naturalHeight;
    //alert("Original width=" + realWidth + ", " + "Original height=" + realHeight);
    if(realWidth>=560 && realHeight>=672){
      $("#cropImageName1").val(iname);
      $image_crop1.croppie('bind', {
        url:imgurl
      }).then(function(){
        console.log('jQuery bind complete');
      });
      
      //reader.readAsDataURL(this.files[0]);
      $('#uploadimageModal1').modal('show');
    }
    else{
      Swal.fire({
        title: 'Dieses Größe des Bild kann nicht verändert werden, da es kleiner als 560 x 672px ist.',
        width: 600,
        padding: '3em'
      });
    } 
  });

  $('.crop_image1').click(function(event){
    let isValid = true;
    if($("#galServiceId").html() == '') {
      $("#category_err").removeClass('display-n');
      isValid = false;
    } else {
      $("#category_err").addClass('display-n');
    }

    if($("input[name='employee_select']:checked").length == 0) {
      $("#employee_err").removeClass('display-n');
      isValid = false;
    } else {
      $("#employee_err").addClass('display-n');
    }

    const catVal = $("#galServiceId").html();
    const empVal = $("input[name='employee_select']:checked").val();
    if (!isValid) return;
    $image_crop1.croppie('result', {
      type: 'canvas',
      size:{
        width:442,
        height:530,
      }
    }).then(function(response){
      loading();
      var iname=$("#cropImageName1").val(); 
    
      if(iname=='add')
      {
        var urlreq ="<?php echo base_url('profile/gal_upload_banner_img'); ?>";
      }else{
        var urlreq ="<?php echo base_url('profile/gal_imagecropp'); ?>";
      } 
      $.ajax({
        url:urlreq,
        type: "POST", 
        data:{"image": response,cat:catVal, emp:empVal},
        success:function(data)
        { 
          var obj = $.parseJSON(data);
          if(obj.success=='1'){
            $('#success_message1').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Galeriebild erfolgreich bearbeitet</div>');

            setTimeout(function(){ window.location.reload(); },500);
          }
        }
      });
    });
  });
   $("#vcheckbox15").change(function(){	  
	  if($(this).is(":checked")){
		  $(".cancelOnineHrOpt").removeClass('disabled');
		  }
	  else{ $(".cancelOnineHrOpt").addClass('disabled'); }	  
	 });  

   $(document).on('click','#tax_listing_popup', function(){
      $.ajax({
      url:"<?php echo base_url('taxes/get_alltaxes'); ?>",
      type: "POST",
      data:{ },
      success:function(data)
      { 
        var obj = $.parseJSON(data);
        $("#add_taxes_list").html(obj.html);
        $('#TaxListing').modal('show'); 
      }
      });
   });

   $(document).on('click','#add_new_taxes', function(){
      $('.add-new-tax').modal('show');
      $('#TaxListing').modal('hide');
   });
   $(document).on('click','.editTax',function(){
     $("#eid").val($(this).attr('data-id'));  
     $("#taxper").val($(this).attr('data-rate'));  
     $("#taxname").val($(this).attr('data-name'));  
     
      $("#editTax").modal('show'); 
      $('#TaxListing').modal('hide');
  
  });
  $(document).on('click','.close_taxes_popup', function(){
      $('#TaxListing').modal('show');
   });
   
   $(document).on('click','.deleteTax',function(){
         $('#TaxListing').modal('hide');
          var taxid = $(this).attr('data-tid');
          
                 Swal.fire({
                  title: are_you_sure,
                  text: "<?php echo $this->lang->line('you_want_delete_tax'); ?>",
                  type: 'warning',
                  showCancelButton: true,
                  reverseButtons:true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Bestätigen'
                }).then((result) => {
                if(result.value) {                  
                  
                  loading();
                                    
                    $.ajax({
                       type: "POST",
                       url:base_url+"taxes/delete",
                       data:'tid='+taxid,
                       success: function (response) {
                        unloading();
                        var obj = $.parseJSON( response );
                        if(obj.success==1){
                          $("#tax_listing_popup").trigger('click');
                          
                        }else{
                          Swal.fire(
                              'Try agian',
                              'warning'
                            );
                        }
                       }
                     });
                  }else{
                    $('#TaxListing').modal('show');
                    return false;
                    }
                });
  });
   
  $(document).on('click','.save_taxes_value', function(){

     var frm = $(this).attr('data-frm');
     var urls = $(this).attr('data-url');
     token = true;
     if(frm =="addTaxform"){
      if($("#ad_taxname").val() ==""){
          $("#ad_taxname_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>please enter tax name');
          token = false;
        }else
        $("#ad_taxname_error").html('');
      
        if($("#ad_taxper").val() == ""){
          $("#ad_taxper_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>please enter tax %');
            token = false;
        }
        else{
            $("#ad_taxper_error").html('');
            if($("#ad_taxper").val() > 101){
               $("#ad_taxper_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>Enter tax % less than equal to 100');
                token = false;
            }
          }
     }
     else{
         if($("#taxname").val() ==""){
          $("#taxname_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>please enter tax name');
          token = false;
        }else
          $("#taxname_error").html('');
        
        if($("#taxper").val() == ""){
          $("#taxper_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>please enter tax %');
            token = false;
        }
        else{
            $("#taxper_error").html('');
            if($("#taxper").val() > 101){
               $("#taxper_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>Enter tax % less than equal to 100');
                token = false;
            }
          }

     }
     
     
     if(token == false)
      return false;
      
    if($("#editTaxes").valid()){  

      $.ajax({
      url:"<?php echo base_url('taxes/'); ?>"+urls,
      type: "POST",
      data: $("#"+frm).serialize(),
      success:function(data)
        { 
          //var obj = $.parseJSON(data);
          $(".close_taxes_popup").trigger('click');
          $("#tax_listing_popup").trigger('click');
          $('#TaxListing').modal('show');
          $('#TaxListing').modal('show'); 
        }
      });
   }else{
	   return false;
	   }
   });

  $(document).on('click','.set_default_taxes', function(){
     var id = $(this).attr('data-id');
      $.ajax({
      url:"<?php echo base_url('taxes/set_default/'); ?>"+id,
      type: "POST",
      data: { },
      success:function(data)
        { 
           $("#tax_listing_popup").trigger('click');
        }
      });
   });

  

});

$('[data-toggle="tooltip"]').tooltip({ boundary: 'window' });  


//********************************************************************Payment method Js **************************************************************//
 $(document).on('click','#payment_listing_popup', function(){
      $.ajax({
      url:"<?php echo base_url('payment_methods/listing'); ?>",
      type: "POST",
      data:{ },
      success:function(data)
      { 
        var obj = $.parseJSON(data);
        $("#add_payment_method_list").html(obj.html);
        $('#PaymentMethodListing').modal('show'); 
      }
      });
   });

 $(document).on('click','#add_new_paumentmethod',function(){
	
     $(".add-new-paymentmethod").modal('show');
  });
  
  
    $(document).on('click','.editpaymentmethod',function(){
     $("#paymentmethodid").val($(this).attr('data-id'));   
     $("#paymentmethod").val($(this).attr('data-name'));  
     
      $("#editpaymentmethod").modal('show'); 
      $('#PaymentMethodListing').modal('hide');
  
  });
	
 $(document).on('click','.save_paymentmethod_value', function(){

     var frm1 = $(this).attr('data-frm');
     var urls1 = $(this).attr('data-url');
     token = true;
     if(frm1 =="addPaymentMethodform"){
		 
      if($("#ad_method").val() ==""){
          $("#ad_method_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte Name der Zahlungsmethode eingeben');
          token = false;
        }else
        $("#ad_method_error").html('');
       
     }
     else{
         if($("#paymethod").val() ==""){
          $("#paymethod_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte Name der Zahlungsmethode eingeben');
          token = false;
        }else
          $("#paymethod_error").html('');
        

     }
     if(token == false)
      return false;
    // alert(frm1);
      $.ajax({
      url:"<?php echo base_url('payment_methods/'); ?>"+urls1,
      type: "POST",
      data: $("#"+frm1).serialize(),
      success:function(data)
        { 
          //var obj = $.parseJSON(data);
          $("#ad_method").val('');
          $(".close_paymethod_popup").trigger('click');
          $("#editpaymentmethod").modal('hide');
          $("#payment_listing_popup").trigger('click');
          
          
        }
      });
   });


   $(document).on('click','.deletepaymentmethod',function(){
         $('#PaymentMethodListing').modal('hide');
          var pmid = $(this).attr('data-pmid');
          
                 Swal.fire({
                  title: "",
                  text: "Bist du sicher, dass du diese Zahlungsmethode löschen willst?",
                  type: 'warning',
                  showCancelButton: true,
                  reverseButtons:true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Bestätigen'
                }).then((result) => {
                if(result.value) {                  
                  
                  loading();
                                    
                    $.ajax({
                       type: "POST",
                       url:base_url+"payment_methods/delete",
                       data:'pmid='+pmid,
                       success: function (response) {
                        unloading();
                        var obj = $.parseJSON( response );
                        if(obj.success==1){
                          $("#payment_listing_popup").trigger('click');
                        }else{
                          Swal.fire(
                            'Try agian',
                            'warning'
                          );
                        }
                       }
                     });
                  }else{
                    $("#payment_listing_popup").trigger('click');
                  }
                });
  });
   

  $(document).on('click','.set_default_paymentmethod', function(){
     var id = $(this).attr('data-id');
      $.ajax({
      url:"<?php echo base_url('payment_methods/set_default/'); ?>"+id,
      type: "POST",
      data: { },
      success:function(data)
        { 
           $("#payment_listing_popup").trigger('click');
        }
      });
   });

  $(document).on('change','input[type=radio][name=employee_select]', function(){
    const id = $(this).val();
    $("#galServiceId").html('');
    $("#subservicelist").addClass('display-n');
    $("#categorySelected1").removeClass('label_add_top');
    $(".categorySelected1").html('');

    $("#categorySelected").removeClass('label_add_top');
    $(".categorySelected").html('');

    $.ajax({
      url:"<?php echo base_url('merchant/get_sub_category_by_emp'); ?>",
      type: "POST",
      data: { id },
      success:function(data)
      { 
        const dat = JSON.parse(data);
        $("#subcategorylist").html(dat.html);
        $(".categorySelected").prop("disabled", false);
        
      }
    });
  });

  $(document).on('change','input[type=radio][name=category_select]', function(){
    const id = $(this).val();
    const eid = $("input[name='employee_select']:checked").val();
    if ($(this).data('subservice') == 'n') {
      $("#galServiceId").html($(this).data('serid'));
      $("#subservicelist").addClass('display-n');
    } else {
      $("#galServiceId").html('');
      $.ajax({
        url:"<?php echo base_url('merchant/get_sub_service'); ?>",
        type: "POST",
        data: { id, eid },
        success:function(data)
          { 
            const dat = JSON.parse(data);
            $("#subservicelist").removeClass('display-n');
            $("#subservicelistul").html(dat.html);
            $("#categorySelected1").removeClass('label_add_top');
            $(".categorySelected1").html('');
          }
        });
    }
  });
  
  $(document).on('change','input[type=radio][name=service_select]', function(){
    const id = $(this).val();
    $("#galServiceId").html(id);    
  });

  //******************************************************************Payment method Js End**************************************************************//


    </script>
    
<script src="<?php echo base_url('assets/frontend/js/ui.js'); ?>"></script>
