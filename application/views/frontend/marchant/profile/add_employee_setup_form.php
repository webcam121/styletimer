      <style>
		  #uploadimageModal{
			  z-index:1112 !important;
			  }
		.modal-backdrop.fade.show+div{
			z-index:1500!important;
			}	  
      #setup_modal.show ~ #ui-datepicker-div+div,
      #setup_modal.show ~ #ui-datepicker-div+div+div{
          z-index:1500!important;
      }
      .modal-backdrop.fade.show+div>div>div>div>div,
      #ui-datepicker-div+div>div>div>div>div{
        cursor: pointer !important;
      }
	  </style>	  
      


        <div class="salon-new-top-bg pt-4">
                <div class="pl-20 pr-20 d-flex">
                  <h3 class="font-size-20 color333 fontfamily-medium mb-2">
                    <?php echo $this->lang->line('setup_your_profile'); ?>
                  </h3>
                </div>
                <div class="alert alert-success absolute vinay top w-100 mt-15 alert_message alert-top-0 text-center <?php if(!empty($message)) echo 'messageAlert1'; else echo 'display-n';  ?>" style="top: -20px !important;">
					<!-- <button type="button" class="close ml-10" data-dismiss="alert">&times; </button> -->
				  
				   <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span id="alert_message"><?php if($message) echo $message; ?> </span>
				 </div>
                  <div class="salon-new-step">
                     <?php if(!empty($setup_no)) $data['setup_no']=$setup_no;  $this->load->view('frontend/marchant/common_setup',$data); ?>
              </div>
              <div class="bgwhite relative pt-4 px-3">
                <p class="color333 font-size-16 fontfamily-semibold mb-0"><?php echo $this->lang->line('in-order-booking'); ?></p>
                <label class="color999 fontfamily-light color333 font-size-12 mb-25">
                  <img src="<?php echo base_url('assets/frontend/images/information-button.png'); ?>" class="edit_pencil_bg_white_circle_icon1">
                  Du kannst später jederzeit noch weitere Mitarbeiter hinzufügen oder bestehende Mitarbeiter bearbeiten.
                </label>
                <form class="relative" id="frmmyEmployee" method="post" enctype="multipart/form-data">
                 <div class="bgwhite border-radius4 mb-4">
               
                      <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">                         
                         <div class="relative form-group pb-40">
                          <?php if(!empty($Empdetail->profile_pic) && $Empdetail->profile_pic !=''){ ?>
                          <div class="relative display-ib round-employee-upload">
                             <img style="width: 115px; height: 115px;" id="EmpProfile" src="<?php echo base_url('assets/uploads/employee/').$Empdetail->id.'/icon_'.$Empdetail->profile_pic; ?>" class="round-employee-img-upload">
                             <label class="all_type_upload_file bgblack18 text-center">
                              <span class="colorwhite fontfamily-medium font-size-12 "><?php echo $this->lang->line('Change'); ?></span>
                              <input type="file" id="profile_pic" name="profile_img">
                            </label>
                           </div>      
                            <?php } 
                            else {
                            ?>
                            <div class="relative display-ib">
                              <img id="EmpProfile" style="width: 115px; height: 115px; border-radius: 50%;" src="<?php echo base_url('assets/frontend/images/upload_dummy_img.svg'); ?>">
                              <label class="all_type_upload_file">
                                <img src="<?php echo base_url('assets/frontend/images/camera_upload_icon.svg'); ?>" class="edit_pencil_bg_white_circle_icon1">
                                <input type="file" id="profile_pic" name="profile_img">
                            </label>
                            </div>
                            
                           <?php } ?>
                            <label class="error" id="imgerror"></label>     
                             <?php if(!empty($Empdetail->profile_pic) && $Empdetail->profile_pic!=""){ ?>
				                <a class="font-size-10 color666 fontfamily-medium display-ib relative" onclick="return deleteImgaeConfirm('<?php echo $Empdetail->id; ?>');" style="top:20px; cursor:pointer;margin-left: -94px;"><?php echo $this->lang->line('Remove_Picture'); ?></a>
				           <?php } ?>                                     
                         </div>
                        
                        </div> 
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                          <div class="relative vertical-bottom mt-60 form-group-mb-50">
                            <p class="color999 fontfamily-light color333 font-size-12 mb-10"><?php echo $this->lang->line('Online_Booking'); ?> *</p>
                            <?php $chk=''; 
                             if(empty($Empdetail->id)){
								 $chk='checked'; 
								 }
                            if(isset($Empdetail->online_booking) && ($Empdetail->online_booking == 1)){ $chk='checked="checked"'; } ?>
                            <label class="switch" for="vcheckbox8">
                                <input type="checkbox" id="vcheckbox8" name="chk_online" <?php echo $chk; ?> />
                                <div class="slider round"></div>
                             </label>
                           </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                          <div class="form-group form-group-mb-50" id="first_name_validate">
                             <label class="inp">
                               <input type="text" placeholder="&nbsp;" class="form-control" id="first_name" name="first_name" value="<?php echo isset($Empdetail->first_name)?$Empdetail->first_name:''; ?>">
                               <span class="label"><?php echo $this->lang->line('First_Name'); ?>  </span>
                             </label>                                                
                         </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                          <div class="form-group form-group-mb-50" id="last_name_validate">
                             <label class="inp">
                               <input type="text" placeholder="&nbsp;" class="form-control" id="last_name" name="last_name" value="<?php echo isset($Empdetail->last_name)?$Empdetail->last_name:''; ?>">
                               <span class="label"><?php echo $this->lang->line('Last_Name'); ?> </span>
                             </label>                                                
                         </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                          <div class="form-group form-group-mb-50" id="telephone_validate">
                             <label class="inp">
                               <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="telephone" name="telephone" value="<?php echo isset($Empdetail->mobile)?$Empdetail->mobile:''; ?>">
                               <span class="label"><?php echo $this->lang->line('Telephone'); ?> </span>
                             </label>                                                
                         </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                          <div class="form-group form-group-mb-50" id="email_validate">
                             <label class="inp">
                              <?php if(isset($Empdetail->email) && ($Empdetail->email !='')){ ?>
                               <input type="text" placeholder="&nbsp;" class="form-control" value="<?php echo $Empdetail->email; ?>" disabled>
                               <input type="hidden" id="empid" name="empid" value="<?php echo url_encode($Empdetail->id); ?>">
                               <?php } else{ ?>
                               <input type="text" placeholder="&nbsp;" class="form-control" id="email" name="email" value="<?php echo isset($Empdetail->email)?$Empdetail->email:''; ?>">
                               <?php } ?>
                               

                               <span class="label"><?php echo $this->lang->line('Email'); ?></span>
                             </label>                                                
                         </div>
                        </div>
                        <?php if(empty($Empdetail->id)){ ?>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                          <div class="form-group mobile-mb-44" id="password_validate">
                             <label class="inp">
                               <input type="password" placeholder="&nbsp;" class="form-control" id="password" autocomplete="off" name="password" value="">
                               <span class="label"><?php echo $this->lang->line('password'); ?> </span>
                             </label>                                                
                         </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                          <div class="form-group mobile-mb-44" id="cpassword_validate">
                             <label class="inp">
                               <input type="password" placeholder="&nbsp;" name="cpassword" class="form-control" autocomplete="off" value="">
                               <span class="label"><?php echo $this->lang->line('Confirm_Password'); ?></span>
                             </label>                                                
                         </div>
                        </div>
                        
                        <?php  }else{ ?>
							<input type="hidden" name="empid" value="<?php echo url_encode($Empdetail->id); ?>">
							
						<?php } ?>
                        
                      </div>
                      <div class="row">
                    
                      <div class="col-12"> <!--  col-sm-6 col-md-6 col-lg-6 col-xl-6 -->
							<div class="relative mb-25">
								<?php $colors=array('#FF9944','#E46986','#6CC198','#F6BC51','#F3DDCF','#90624B','#3296D2','#48C3CA','#A9A2CE','#fff');
								      $slectColr=[];
								      $defulatColor="";
								      if(!empty($selectedcolor) && empty($this->uri->segment(3)))
                      {
							    			foreach($selectedcolor as $calColor){
									    		$slectColr[]=$calColor->calender_color;
											  }
									    }
									    foreach($colors as $col){
										    if(in_array($col,$slectColr)){}
										    else{
											    $defulatColor=$col;
											  }	
									    }
								   ?>
								     <label class="fontfamily-medium color333 font-size-14 display-b mb-10"><?php echo $this->lang->line('choose_color'); ?></label>
                        <input type="text" class="jscolor" style="width:90px;cursor:pointer;" name="calender_color" value="<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>" readonly>
                    </div>
                  </div>
              </div>

              <div class="relative ">
                <div class="checkbox mt-1 mb-2">
                  <label class="fontsize-12 fontfamily-regular color333">
					  <input type="checkbox" name="allow_emp_to_delete_cancel_booking" value="1" class="mitarbain" id="mitarbain" <?php if(!empty($Empdetail->allow_emp_to_delete_cancel_booking) && $Empdetail->allow_emp_to_delete_cancel_booking==1) echo 'checked'; ?>>
                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                    <?php echo $this->lang->line('Mitarbeiter'); ?>                    
                  </label>
                </div>
                <div class="checkbox mt-1 mb-2">
                  <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="mail_by_user" value="1" class="mail_by_user" id="mail_by_user" data-count="" <?php echo empty($Empdetail->mail_by_user)?'':'checked="checked"'; ?> <?php echo empty($Empdetail)?'checked="checked"':''; ?>>
                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                    Per E-Mail über neue Buchungen, Umbuchungen und Stornierungen von Kunden informieren
                  </label>
                </div> 
                <div class="checkbox mt-1 mb-2">
                  <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="mail_by_merchant" value="1" class="mail_by_merchant" id="mail_by_merchant" data-count="" <?php echo empty($Empdetail->mail_by_merchant)?'':'checked="checked"'; ?> <?php echo empty($Empdetail)?'checked="checked"':''; ?>>
                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                    Per E-Mail über neue Buchungen, Umbuchungen und Stornierungen des Salons informieren
                  </label>
                </div>
                <div class="checkbox mt-1 mb-2 display-ib">
                <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="commission_check" value="1" class="commission_check" id="commission_check" data-count="" <?php echo empty($Empdetail->commission)?'':'checked="checked"'; ?> >
                  <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                  <?php echo $this->lang->line('Enable_Commission'); ?>
                </label>
              </div> 
              <div class="row <?php echo empty($Empdetail->commission)?'hide':''; ?>" id="commission_div">
                <div class="relative form-group-mb-50 col-12 col-sm-8 col-md-7 col-lg-6 col-xl-6" id="">
                  <label class="label "><?php echo $this->lang->line('Service_Commission'); ?></label>
                  <div class="input-group ">
                    <div class="input-group-prepend ">
                      <span class="input-group-text bge8e8e8">%</span>
                      </div>
                      <input type="text" id="commission" name="commission" value="<?php echo empty($Empdetail->commission)?'':$Empdetail->commission; ?>" class="form-control onlyNumber" placeholder="<?php echo $this->lang->line('Enter_Commission'); ?>">
                     
                    </div>
                     <label class="error" id="commission_error"></label>
                  </div>
                </div>
              </div>



              </div>
              <div class="row">
               <div class="col-12 col-sm-12 col-md-12 ">

                    <div class="bgwhite border-radius4 mt-0 mb-0">
                    <h6 class="color333 fontfamily-medium font-size-16 mb-40 text-center"><?php echo $this->lang->line('Working_Hours'); ?></h6>

                    <?php //$days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday', 'Sunday');
                    $ii=0;
                    //print_r($merchant_available);
                    if(!empty($merchant_available)){
						$t=1;
                    foreach($merchant_available as $day){
						if($day->type=='open'){ ?>
                        <div class="d-flex">
                            <div class="checkbox-btn display-ib">
                              <label class="/font-size-14 fontfamily-medium">
                              <?php 
                               if($this->uri->segment(3) !='')
                                 $chkbtn = "";
                               else
                                 $chkbtn = 'checked="checked"';

                              if(!empty($user_available[$ii]->starttime)){ 
                                $chkbtn= 'checked="checked"'; 
                                }else{
                                 $chkbtn ='checked="checked"'; 
                                } ?>
                                
                                <input type="checkbox" <?php echo $chkbtn; ?> name="days[]" class="checkbox" value="<?php echo strtolower($day->days); ?>">
                                  <span class="squer-chack">
                                    <span class="squer-chacked"><?php echo strtoupper(substr($this->lang->line(ucfirst($day->days)),0,1)); ?></span>
                                  </span>
                              </label>
                              <span id="chk_<?php echo strtolower($day->days); ?>" class="<?php if($t==1) echo "first_Err "; ?>error"></span>
                                <p class="color333 fontfamily-light font-size-12 display-ib ml-10 mb-0 overflow_elips" style="width:260px;white-space: pre-wrap;"><?php echo $this->lang->line('employe_text_between_'.strtolower($day->days)); ?></p>
                            </div>
                            <div class="relative ml-auto">
                            <div class="d-flex">
                            <div class="display-ib mr-20 ">
                                <div class="form-group form-group-mb-50">
									<div class="btn-group multi_sigle_select inp_select v_inp_new width130">
                                    <span class="label <?php if(!empty($user_available[$ii]->endtime) || !empty($day->starttime)) echo 'label_add_top'; ?>"><?php echo $this->lang->line('Start_Time'); ?></span>
                                    <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false"><?php if(!empty($user_available[$ii]->starttime)) echo date('H:i',strtotime($user_available[$ii]->starttime)); else if(!empty($day->starttime)) echo date('H:i',strtotime($day->starttime));   ?></button>                                
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
                                    style="max-height: none; overflow-x: auto;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
									<?php if(!empty($day->starttime) && !empty($day->endtime))
											 {
											$tStart = strtotime($day->starttime);
											$tEnd = strtotime($day->endtime);
											$tNow = $tStart;
										while($tNow <= $tEnd){
											$srt_dt="";
											

											 if(!empty($user_available[$ii]->starttime) && $user_available[$ii]->starttime==date("H:i:s",$tNow))
												$srt_dt = "checked";
											 else if(empty($user_available[$ii]->starttime) && date("H:i:s",$tNow) == date("H:i:s",$tStart))
												$srt_dt = "checked";
                                              
											 ?>
											  <li class="radiobox-image">
												<input type="radio" id="id_time<?php echo strtolower($day->days); echo $tNow; ?>" name="<?php echo strtolower($day->days); ?>_start" class="start_<?php echo strtolower($day->days); ?>" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php echo $srt_dt; ?>>
												<label for="id_time<?php echo strtolower($day->days); echo $tNow; ?>">
												<?php echo date("H:i",$tNow) ?>                   
											  </label>
											  </li> 
										<?php $tNow = strtotime('+30 minutes',$tNow); } } ?>	  
											                    
                                    </ul>
                                 </div>
                                  <span id="Serr_<?php echo strtolower($day->days); ?>" class="error"></span>
                                  
                                </div>
                            </div>
                      <div class="display-ib">
                             <div class="form-group form-group-mb-50">
                                 <div class="btn-group multi_sigle_select inp_select v_inp_new width130">
                                    <span class="label <?php if(!empty($user_available[$ii]->endtime) || !empty($day->endtime)) echo 'label_add_top'; ?>"><?php echo $this->lang->line('End_Time'); ?></span>
                                    <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false"><?php if(!empty($user_available[$ii]->endtime)) echo date('H:i',strtotime($user_available[$ii]->endtime)); else if(!empty($day->endtime)) echo date('H:i',strtotime($day->endtime)); ?></button>                                
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
                                    style="max-height: none; overflow-x: auto; max-height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
                                     <?php if(!empty($day->starttime) && !empty($day->endtime))
											 {
											$tStart = strtotime($day->starttime);
											$tEnd = strtotime($day->endtime);
											$tNow = $tStart;
										while($tNow <= $tEnd){ 
											
											$end_dt = "";
										/*if($this->uri->segment(3) !='')
											$end_dt = "";
                    else if(date("H:i:s",$tNow) == date("H:i:s",$tEnd))
											$end_dt = "checked";*/

									   if(!empty($user_available[$ii]->endtime) && $user_available[$ii]->endtime==date("H:i:s",$tNow))
											$end_dt = "checked";
                     else if(empty($user_available[$ii]->endtime))
                        $end_dt = "checked";
											 ?>
											  <li class="radiobox-image">
												<input type="radio" id="endid_time<?php echo strtolower($day->days); echo $tNow; ?>" name="<?php echo strtolower($day->days); ?>_end" class="end_<?php echo strtolower($day->days); ?>" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php echo $end_dt; ?>>
												<label for="endid_time<?php echo strtolower($day->days); echo $tNow; ?>">
												<?php echo date("H:i",$tNow) ?>                   
											  </label>
											  </li> 
										<?php $tNow = strtotime('+30 minutes',$tNow); } } ?>	                       
                                    </ul>
                                 </div>
                                  

                            <span id="Eerr_<?php echo strtolower($day->days); ?>" class="error"></span>
                                </div>
                            </div>
                            <?php if(!empty($user_available[$ii]->starttime_two) && !empty($user_available[$ii]->endtime_two))
                                $add_btn="none";
                              else $add_btn=""; ?>
                            <div id="addbtn_<?php echo strtolower($day->days); ?>" style="display: <?php echo $add_btn; ?>">
                             <a href="#" class="mt-3 display-b ml-2 add_new_time" data-toggle="modal" data-target="" id="<?php echo strtolower($day->days); ?>">
                              <img src="<?php echo base_url('assets/frontend/images/add_blue_plus_icon.svg'); ?>" class="width24v">
                             </a>
                            </div>
                          </div>
                          <div id="new_add<?php echo strtolower($day->days); ?>">
                          <?php if(!empty($user_available[$ii]->starttime_two) && !empty($user_available[$ii]->endtime_two)){ ?>
                          <div class="d-flex" id="remove_<?php echo strtolower($day->days); ?>">
                              <div class="display-ib mr-20 ml-auto">
                                <div class="form-group form-group-mb-50">
									<div class="btn-group multi_sigle_select inp_select v_inp_new width130">
                                    <span class="label <?php if(!empty($user_available[$ii]->starttime_two)) echo 'label_add_top'; ?>"><?php echo $this->lang->line('Start_Time'); ?></span>
                                    <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false"><?php if(!empty($user_available[$ii]->starttime_two)) echo date('H:i',strtotime($user_available[$ii]->starttime_two));  ?></button>                                
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll">
									<?php if(!empty($day->starttime) && !empty($day->endtime))
											 {
											$tStart = strtotime($day->starttime);
											$tEnd = strtotime($day->endtime);
											$tNow = $tStart;
										while($tNow <= $tEnd){ ?>
											  <li class="radiobox-image">
												<input type="radio" id="id_time_two<?php echo strtolower($day->days); echo $tNow; ?>" name="<?php echo strtolower($day->days); ?>_start_two" class="start_<?php echo strtolower($day->days); ?>" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php if(!empty($user_available[$ii]->starttime_two) && $user_available[$ii]->starttime_two==date("H:i:s",$tNow)) echo "checked"; ?>>
												<label for="id_time_two<?php echo strtolower($day->days); echo $tNow; ?>">
												<?php echo date("H:i",$tNow) ?>                   
											  </label>
											  </li> 
										<?php $tNow = strtotime('+30 minutes',$tNow); } } ?>	  
											                    
                                    </ul>
                                 </div>

                                 <span id="Serrtwo_<?php echo strtolower($day->days); ?>" class="error"></span>
                                  
                                </div>
                            </div>
                            <div class="display-ib">
                                <div class="form-group form-group-mb-50">
                                 <div class="btn-group multi_sigle_select inp_select v_inp_new width130">
                                    <span class="label <?php if(!empty($user_available[$ii]->endtime_two)) echo 'label_add_top'; ?>"><?php echo $this->lang->line('End_Time'); ?></span>
                                    <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false"><?php if(!empty($user_available[$ii]->endtime_two)) echo date('H:i',strtotime($user_available[$ii]->endtime_two)); ?></button>                                
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll">
                                     <?php if(!empty($day->starttime) && !empty($day->endtime))
											 {
											$tStart = strtotime($day->starttime);
											$tEnd = strtotime($day->endtime);
											$tNow = $tStart;
										while($tNow <= $tEnd){ ?>
											  <li class="radiobox-image">
												<input type="radio" id="endid_time_two<?php echo strtolower($day->days); echo $tNow; ?>" name="<?php echo strtolower($day->days); ?>_end_two" class="end_<?php echo strtolower($day->days); ?>" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php if(!empty($user_available[$ii]->endtime_two) && $user_available[$ii]->endtime_two==date("H:i:s",$tNow)) echo "checked"; ?>>
												<label for="endid_time_two<?php echo strtolower($day->days); echo $tNow; ?>">
												<?php echo date("H:i",$tNow) ?>                   
											  </label>
											  </li> 
										<?php $tNow = strtotime('+30 minutes',$tNow); } } ?>	                       
                                    </ul>
                                 </div>
                                  <span id="Eerrtwo_<?php echo strtolower($day->days); ?>" class="error"></span>
                                </div>
                            </div>

                             <a href="javascript:void(0);" id="<?php echo strtolower($day->days); ?>" class="mt-1 display-b ml-2 remove_timeset" data-toggle="modal" data-target="">
                              <img src="<?php echo base_url('assets/frontend/images/remove.svg'); ?>" class="width24v">
                            </a>
                          </div>
                          <?php } ?>
                          </div>

                        </div>
                           


                        </div>
                        <?php  $t++;
                      } $ii++;  } } else '<p class="color333 fontfamily-light font-size-12 display-ib ml-10 mb-0 overflow_elips" style="width:260px;white-space: pre-wrap;"> There is time not updated by marchent.</p>'; ?>

                    </div>
                </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
                        <div class="pt-3 pb-3 bgwhite boxshadow-5 px-3 mt-4">

                         <button type="button" onclick="getTimehtml();" class="btn btn-border-orange btn-large widthfit2 ml-0"><?php echo $this->lang->line('Previous'); ?></button>
                         <button type="button" id="save_add_employee_add_more" class="btn btn-large widthfit2 float-right ml-3"><?php echo $this->lang->line('SaveAddanotheremployee'); ?></button>
                          <button type="button" id="save_add_employee_setup" class="btn btn-large widthfit2 float-right"><?php echo $this->lang->line('SaveProceed'); ?></button>
                           

                        </div>
                        <input type="hidden" id="merchant_id" name="merchant_id" value="<?php echo $merchant_id; ?>">
                        <input type="hidden" id="save_type" name="save_type" value="next">
                    </div>
                </div>
                    
                  </div>
                </form>
              </div>
<script type="text/javascript" src="<?php echo base_url('assets/frontend/js/jscolor.js'); ?>"></script>

<script>
	


     //~ $image_crop.croppie('viewport',  {
		   //~ width:250,
		   //~ height:250,
		 //~ type:'square' //circle
		//~ });
	 

	
$("#frmmyEmployee").validate({
	rules: {
		first_name:{ required: true },
		last_name:{ required: true },
        email: {
			required: true,
			email:true,
			remote: {url: base_url+"auth/checkemail", type : "post"}
		},
		password: {
			required: true
		},
	    cpassword: {
			required: true,
			equalTo: "#password"
		}
		
     },
     messages: {
     	first_name:{
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+please_enter_first_name,
     	},
     	last_name:{
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+please_enter_last_name,
     	},
		email:
			 {
			 	required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+Please_enter_email_id,
				remote: jQuery.validator.format('<i class="fas fa-exclamation-circle mrm-5"></i>Diese E-Mail Adresse existiert bereits')
			 },
	  password:{
		  required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+please_enter_password
		  },
	  cpassword:{
		   required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+please_enter_confirm_password,
		   equalTo:"<i class='fas fa-exclamation-circle mrm-5'></i>"+Confirm_password_doesnt_match
		  }	  	 
	},
    errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        }
                 
   });

$(document).on('click','#save_add_employee_setup',function(){

  if($("#commission_check").is(':checked'))
            {
              if($("#commission").val() ==""){
                token = false;
                $("#commission_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+Please_enter_commission);
                $("#commission_error").show();
              }
              else if($("#commission").val() > 100){
                token = false;
                $("#commission_error").html("<i class='fas fa-exclamation-circle mrm-5'></i>Commission % not greater than 100");  
                $("#commission_error").show();
              }
              else
                $("#commission_error").html("");
            }
 });

$(document).on('blur','#commission',function(){
    if($("#commission").val() !="" && $("#commission").val() < 101){
               $("#commission_error").html("");
              }
             
});


</script>
