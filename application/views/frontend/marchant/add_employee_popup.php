
<style>
	.modal-backdrop + div{
		z-index:1050 !important;
		}
		  #uploadimageModal{
			  z-index:1112 !important;
			  }
	  </style>		
</style>
 <form class="relative" id="frmmyEmployee" method="post" enctype="multipart/form-data" action="<?php echo base_url('merchant/dashboard_addemployee'); ?>">
          <div class="modal-header-new">
            <div class="absolute right top mt-0 mr-0">
              <a href="javascript:void(0)" data-dismiss="modal" class="crose-btn font-size-30 color333 a_hover_333">
                <picture class="" style="width: 22px; height: 22px;">
                    <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="" style="width: 22px; height: 22px;">
                    <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="" style="width: 22px; height: 22px;">
                    <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="" style="width: 22px; height: 22px;">
                  </picture>
              </a>
            </div>
            <h3 class="font-size-20 fontfamily-medium color333 text-center">
                  <?php echo ($this->uri->segment(3) !='')?$this->lang->line('edit_employe_detail'):$this->lang->line('add_employe_detail');?></h3>
          </div>
              <div class="bgwhite border-radius4 box-shadow1 mb-0 pb-1 ">
                  
                <div class="p-3">
                      <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">                         
                         <div class="relative form-group pb-40">
                          <?php if(!empty($Empdetail->profile_pic) && $Empdetail->profile_pic !=''){ 
                              $img=base_url('assets/uploads/employee/').$Empdetail->id.'/icon_'.$Empdetail->profile_pic;
                              $div="round-employee-upload";
                              $cls ="bgblack18 text-center";
                              $rem="";
                              $rem2="none";
                           }else{
                             $img = base_url('assets/frontend/images/upload_dummy_img.svg');
                             $div="";
                             $cls="";
                             $rem="none";
                             $rem2="";
                           } ?>
                          <div id="divclss" class="relative display-ib <?php echo $div; ?>">
                             <img style="width: 115px; height: 115px; border-radius: 50%;" id="EmpProfile" src="<?php echo $img; ?>" class="round-employee-img-upload">
                             <label id="alltype_cls" class="all_type_upload_file <?php echo $cls ?>">
                              <span id="spamCls" class="colorwhite fontfamily-medium font-size-12 " style="display: <?php echo $rem ?>"><?php echo $this->lang->line('Change'); ?></span>
                              <img id="editiconCls" style="display: <?php echo $rem2 ?>" src="<?php echo base_url('assets/frontend/images/camera_upload_icon.svg'); ?>" class="edit_pencil_bg_white_circle_icon1">
                              <input type="file" id="profile_pic" name="profile_img">
                            </label>
                          </div>
                          <!-- <?php if(!empty($Empdetail->profile_pic) && $Empdetail->profile_pic !=''){ ?>
                          <div class="relative display-ib round-employee-upload">
                             <img style="width: 115px; height: 115px;" id="EmpProfile" src="<?php echo base_url('assets/uploads/employee/').$Empdetail->id.'/icon_'.$Empdetail->profile_pic; ?>" class="round-employee-img-upload">
                             <label class="all_type_upload_file bgblack18 text-center">
                              <span class="colorwhite fontfamily-medium font-size-12 ">Change</span>
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
                            
                           <?php } ?> -->
                            <label class="error" id="imgerror"></label>     
                             <?php if(!empty($Empdetail->profile_pic) && $Empdetail->profile_pic!=""){ ?>
				                      <a id="rm_picture" class="font-size-10 color666 fontfamily-medium display-ib relative" onclick="return deleteImgaeConfirm('<?php echo $Empdetail->id; ?>');" style="top:20px; cursor:pointer;margin-left: -94px;"><?php echo $this->lang->line('Remove_Picture'); ?></a>
				                      <?php } ?>                                     
                         </div>
                        
                        </div> 
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                          <div class="relative vertical-bottom mt-60 form-group-mb-50">
                            <p class="color999 fontfamily-light font-size-12 mb-10"><?php echo $this->lang->line('Online_Booking'); ?></p>
                            <?php $chk=''; 
                             if(empty($this->uri->segment(3))){
								 $chk='checked'; 
								 }
                            if(isset($Empdetail->online_booking) && ($Empdetail->online_booking == 1)){ $chk='checked="checked"'; } ?>
                            <label class="switch" for="vcheckbox9">
                                <input type="checkbox" id="vcheckbox9" name="chk_online" <?php echo $chk; ?> />
                                <div class="slider round"></div>
                             </label>
                           </div>
                        </div>
                         <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                          <div class="relative vertical-bottom mt-60 form-group-mb-50">
                            <p class="color999 fontfamily-light font-size-12 mb-10">Mitarbeiter <br/><?php echo 'Aktivieren/Deaktivieren'; ?></p>
                            <?php $chk=''; 
                             if(empty($this->uri->segment(3))){
								 $chk=''; 
								 }
                            if(!isset($Empdetail->status) ||(isset($Empdetail->status) && ($Empdetail->status == 'active'))){ $chk='checked="checked"'; } ?>
                            <label class="switch" for="vcheckbox8st">
                                <input type="checkbox" id="vcheckbox8st" value="active" name="chk_status" <?php echo $chk; ?> />
                                <div class="slider round"></div>
                             </label>
                           </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                          <div class="form-group form-group-mb-50" id="first_name_validate">
                             <label class="inp">
                               <input type="text" placeholder="&nbsp;" class="form-control" id="first_name" name="first_name" value="<?php echo isset($Empdetail->first_name)?$Empdetail->first_name:''; ?>">
                               <span class="label"><?php echo $this->lang->line('First_Name'); ?> </span>
                             </label>                                                
                         </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                          <div class="form-group form-group-mb-50" id="last_name_validate">
                             <label class="inp">
                               <input type="text" placeholder="&nbsp;" class="form-control" id="last_name" name="last_name" value="<?php echo isset($Empdetail->last_name)?$Empdetail->last_name:''; ?>">
                               <span class="label"><?php echo $this->lang->line('Last_Name'); ?></span>
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
                             <?php if(!empty($Empdetail->id)){ ?>
                                <a href="#" id="sendResetpasswordLink" class="colorcyan" data-href="<?php echo base_url('profile/resend_password_link/'.url_encode($Empdetail->id)); ?>"><?php echo $this->lang->line('send_reset_password_link'); ?></a>      
                             <?php } ?>                                       
                         </div>
                        </div>
                        <?php if(empty($this->uri->segment(3))){ ?>
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
                               <span class="label"><?php echo $this->lang->line('change_password'); ?> </span>
                             </label>                                                
                         </div>
                        </div>
                        
                        <?php  } ?>
                        
                      </div>
                      <div class="row">
                      <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
						            <div class="relative mb-25">
                        <p class="fontfamily-medium color333 font-size-14"><?php echo $this->lang->line('Services'); ?></p>
                        
                        <a href="#" class="color333 fontfamily-medium font-size-16 a_hover_333" data-toggle="modal" id="get_service_for_asign">
                          <img src="<?php echo base_url('assets/frontend/images/add_blue_plus_icon.svg'); ?>" class="mr-12"><?php echo $this->lang->line('Add_Service'); ?>
                        </a> 
                           </div>                    
                      </div>
                      <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="relative mb-25">
								<?php $colors=array('#FF9944','#E46986','#6CC198','#F6BC51','#F3DDCF','#90624B','#3296D2','#48C3CA','#A9A2CE','#BDBEB9');
								      $slectColr=[];
								      $defulatColor="";
								     if(!empty($selectedcolor) && empty($this->uri->segment(3)))
								       {
										foreach($selectedcolor as $calColor){
											$slectColr[]=$calColor->calender_color;
											}
									   }
									foreach($colors as $col){
										if(in_array($col,$slectColr)){
											}
										else{
											$defulatColor=$col;
											}	
									 }
								   ?>
								  <label class="fontfamily-medium color333 font-size-14 display-b mb-10"><?php echo $this->lang->line('choose_color'); ?></label>
									<input type="text" class="jscolor" style="width:90px;cursor:pointer;" name="calender_color" value="<?php if(!empty($Empdetail->calender_color)) echo $Empdetail->calender_color; else echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>" readonly>
							</div>
            </div>
            
          </div>
          <div id="all_service_tag">
						  <?php $serarr=array();
						  $subacet=array();
						  if(!empty($services)){
							    foreach($services as $ser){
									$serarr[]=$ser->id;
								
								if(!in_array($ser->subcategory_id,$subacet)){
									 ?>
									<span class="bge8e8e8 border-radius4 pt-1 pb-1 pl-15 pr-15 color333 font-size-14 display-ib m-1 service_asn<?php echo $ser->subcategory_id.' '.$ser->subcategory_id; ?>"><?php if(!empty($ser->name)) echo $ser->category_name; else echo $ser->category_name; ?><img src="<?php echo base_url(); ?>assets/frontend/images/search-crose-small-icon.svg" class="ml-4 deselect_service" data-subcat="<?php echo $ser->subcategory_id; ?>"  data-val="<?php echo $ser->id; ?>" data-id="service_asn<?php echo $ser->id; ?>" style="cursor:pointer"></span>
									<?php }else{ ?>
										<span class="bge8e8e8 border-radius4 pt-1 pb-1 pl-15 pr-15 color333 font-size-14 display-n m-1 service_asn<?php echo $ser->subcategory_id.' '.$ser->subcategory_id; ?>"><?php if(!empty($ser->name)) echo $ser->category_name; else echo $ser->category_name; ?><img src="<?php echo base_url(); ?>assets/frontend/images/search-crose-small-icon.svg" class="ml-4 deselect_service" data-subcat="<?php echo $ser->subcategory_id; ?>"  data-val="<?php echo $ser->id; ?>" data-id="service_asn<?php echo $ser->id; ?>" style="cursor:pointer"></span>
										<?php }
									
									$subacet[]= $ser->subcategory_id;
									
									 }
							  } ?>
              </div>
              <input type="hidden" name="old_assined_service" value="<?php echo implode(',',$serarr); ?>">
              <input type="hidden" name="assigned_service" value="<?php echo implode(',',$serarr); ?>" id="all_select_service">
              <input type="hidden" name="all_subcat" value="<?php echo implode(',',$subacet); ?>" id="all_subcat">


              <div class="relative mt-4">
                <div class="checkbox mt-1 mb-2">
                  <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="allow_emp_to_delete_cancel_booking" value="1" class="mitarbain" id="mitarbain" <?php if(!empty($Empdetail->allow_emp_to_delete_cancel_booking) && $Empdetail->allow_emp_to_delete_cancel_booking==1) echo 'checked'; ?>>
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
                <div class="checkbox mt-1 mb-2">
                  <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="commission_check" value="1" class="commission_check" id="commission_check" data-count="" <?php echo empty($Empdetail->commission)?'':'checked="checked"'; ?> >
                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                    <?php echo $this->lang->line('Enable_Commission'); ?>
                  </label>
                </div> 
              <div class="row <?php echo empty($Empdetail->commission)?'hide':''; ?>" id="commission_div">
                <div class="relative form-group-mb-50 col-12 col-sm-8 col-md-7 col-lg-6 col-xl-6" id="">
                  <label class="fontsize-12 fontfamily-regular color333" "><?php echo $this->lang->line('Service_Commission'); ?></label>
                  <div class="input-group ">
                    <div class="input-group-prepend ">
                      <span class="input-group-text bge8e8e8">%</span>
                      </div>
                      <input type="text" id="commission" name="commission" value="<?php echo empty($Empdetail->commission)?'':price_formate($Empdetail->commission); ?>" class="form-control" placeholder="<?php echo $this->lang->line('Enter_Commission'); ?>">
                     
                    </div>
                     <label class="error" id="commission_error"></label>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="around-30 mt-0 mb-3 pt-0">
              <h6 class="color333 fontfamily-medium font-size-16 mb-40 text-center"><?php echo $this->lang->line('Working_Hours'); ?> </h6>

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

                              if(isset($user_available[$ii]->starttime) && $user_available[$ii]->starttime !=''){ 
                                $chkbtn= 'checked="checked"'; 
                                } ?>
                                
                                <input type="checkbox" <?php echo $chkbtn; ?> name="days[]" class="checkbox" value="<?php echo strtolower($day->days); ?>">
                                  <span class="squer-chack">
                                    <span class="squer-chacked"><?php echo strtoupper(substr($this->lang->line(ucfirst($day->days)),0,1)); ?></span>
                                  </span>
                              </label>
                              <span id="chk_<?php echo strtolower($day->days); ?>" class="<?php if($t==1) echo "first_Err "; ?>error"></span>
                                <p class="color333 fontfamily-light font-size-12 display-ib ml-10 mb-0 overflow_elips" style="width:306px;white-space: unset;"><?php echo $this->lang->line('employe_text_between_'.strtolower($day->days)); ?></p>
                            </div>
                            <div class="relative ml-auto">
                            <div class="d-flex">
                            <div class="display-ib mr-20 ">
                                <div class="form-group form-group-mb-50">
									<div class="btn-group multi_sigle_select inp_select v_inp_new width130">
                                    <span class="label <?php if(!empty($user_available[$ii]->endtime) || empty($this->uri->segment('3'))) echo 'label_add_top'; ?>"><?php echo $this->lang->line('Start_Time'); ?></span>
                                    <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn " aria-expanded="false"><?php if(!empty($user_available[$ii]->starttime)) echo date('H:i',strtotime($user_available[$ii]->starttime)); else if(empty($this->uri->segment(3)) && !empty($day->starttime)) echo date('H:i',strtotime($day->starttime));   ?></button>                                
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll"
                                     style="max-height: none !important;overflow-x: auto  !important; height: 221px !important;">
									<?php if(!empty($day->starttime) && !empty($day->endtime))
											 {
											$tStart = strtotime($day->starttime);
											$tEnd = strtotime($day->endtime);
											$tNow = $tStart;
										while($tNow <= $tEnd){
											
											$srt_dt = "";
											if($this->uri->segment(3) !='')
												$srt_dt = "";
											else if(date("H:i:s",$tNow) == date("H:i:s",$tStart))
													$srt_dt = "checked";

											if(!empty($user_available[$ii]->starttime) && $user_available[$ii]->starttime==date("H:i:s",$tNow)) 
													  $srt_dt= "checked"; 
                                              
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
                                    <span class="label <?php if(!empty($user_available[$ii]->endtime) || empty($this->uri->segment('3'))) echo 'label_add_top'; ?>"><?php echo $this->lang->line('End_Time'); ?></span>
                                    <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false"><?php if(!empty($user_available[$ii]->endtime)) echo date('H:i',strtotime($user_available[$ii]->endtime)); else if(empty($this->uri->segment(3)) && !empty($day->endtime)) echo date('H:i',strtotime($day->endtime)); ?></button>                                
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="max-height: none !important;overflow-x: auto  !important; height: 221px !important;">
                                     <?php if(!empty($day->starttime) && !empty($day->endtime))
											 {
											$tStart = strtotime($day->starttime);
											$tEnd = strtotime($day->endtime);
											$tNow = $tStart;
										while($tNow <= $tEnd){ 
											
											$end_dt = "";
										if($this->uri->segment(3) !='')
											$end_dt = "";
										else if(date("H:i:s",$tNow) == date("H:i:s",$tEnd))
											$end_dt = "checked";

									   if(!empty($user_available[$ii]->endtime) && $user_available[$ii]->endtime==date("H:i:s",$tNow))
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
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="max-height: none;height: auto !important;overflow-x: auto;">
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
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll"
                                     style="max-height: none;height: auto !important;overflow-x: auto;">
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
                      } $ii++;  } } else '<p class="color333 fontfamily-light font-size-12 display-ib ml-10 mb-0 overflow_elips" style="width:306px;"> There is time not updated by marchent.</p>'; ?>

                    </div>
                    <div class="text-center mb-50">
                      <a href="<?php if(!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; ?>"><button type="button" class="btn btn-large widthfit2" style="margin-right: 30px;"><?php echo $this->lang->line('abort'); ?></button></a>
                      <button type="submit" id="submitEmp" name="submitEmp" class="btn btn-large widthfit2"><?php echo $this->lang->line('Save_btn'); ?></button>
                      <input type="hidden" id="merchant_id" name="merchant_id" value="<?php echo $merchant_id; ?>">
                    </div>
                </div>
              </form>

<script src="<?php echo base_url('assets/frontend/js/jscolor.js'); ?>"></script>
<script>

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
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i><?php echo $this->lang->line("please_enter_first_name"); ?>'
     	},
     	last_name:{
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i><?php echo $this->lang->line("please_enter_last_name"); ?>'
     	},
		email:
			 {
			 	required: '<i class="fas fa-exclamation-circle mrm-5"></i><?php echo $this->lang->line("Please_enter_email_id"); ?>',
				remote: jQuery.validator.format('<i class="fas fa-exclamation-circle mrm-5"></i>Diese E-Mail Adresse existiert bereits')
			 },
	  password:{
		  required: '<i class="fas fa-exclamation-circle mrm-5"></i><?php echo $this->lang->line("please_enter_password"); ?>'
		  },
	  cpassword:{
		   required: '<i class="fas fa-exclamation-circle mrm-5"></i><?php echo $this->lang->line("please_enter_confirm_password"); ?>',
		   equalTo:"<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line('Confirm_password_doesnt_match'); ?>"
		  }	  	 
	},
    errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },
    submitHandler: function(form) {
		var token = true;
		if($("input[name='days[]']:checked").length==0){
			//alert('if');
			$(".first_Err").text('Bitte Öff nungszeiten festlegen'); 
			 token =false;
		}else{ 
			var values = new Array();
			$.each($("input[name='days[]']:checked"), function() {
				
			 	var day=$(this).val();
			 	
			 	if($('[name="'+day+'_start"]:checked').length==0){
			 		$("#Serr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Startzeit auswählen"); 
				 token =false;
			 	}else{ $("#Serr_"+day).html(''); 
					}
					
			 	if($('[name="'+day+'_end"]:checked').length==0){
					//alert('e '+day);
			 		$("#Eerr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Endzeit auswählen"); 
				 token =false;
			 	}else{ 
					$("#Eerr_"+day).html(''); 
					}
			 	
                 //return false;
			 	if($('[name="'+day+'_start"]:checked').val()!='' && $('[name="'+day+'_end"]:checked').val() !='' && $('[name="'+day+'_end"]:checked').val()<=$('[name="'+day+'_start"]:checked').val()){
			 		$("#Eerr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"); 
				 token =false;

			 	}else{ if($('[name="'+day+'_start"]:checked').length!=0 && $('[name="'+day+'_end"]:checked').length!=0){ $("#Eerr_"+day).html(''); }
					 }

			 	
			 	if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end_two"]:checked').val() !='' && $('[name="'+day+'_end_two"]:checked').val()<=$('[name="'+day+'_start_two"]:checked').val()){
			 		$("#Eerrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"); 
				 token =false;

			 	}else{ if($('[name="'+day+'_start_two"]:checked')!='' && $('[name="'+day+'_end_two"]:checked').val() !='') $("#Eerrtwo_"+day).html(''); }
			
				if($('[name="'+day+'_start_two"]:checked').val()==''){
			 		$("#Serrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Startzeit auswählen"); 
				 token =false;
			 	}else{ $("#Serrtwo_"+day).html(''); }
			 	
			 	if($('[name="'+day+'_end_two"]:checked').val() == ''){
			 		$("#Eerrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Endzeit auswählen"); 
				 token =false;
			 	}else{ $("#Eerrtwo_"+day).html(''); }
			 	
			 	if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end_two"]:checked').val() !='' && $('[name="'+day+'_end_two"]:checked').val()<=$('[name="'+day+'_start_two"]:checked').val()){
			 		$("#Eerrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"); 
				 token =false;
			 	}else{ 
				if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end_two"]:checked').val()!='') $("#Eerrtwo_"+day).html('');
				 }
			 	if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end"]:checked').val() !='' && $('[name="'+day+'_end"]:checked').val()>=$('[name="'+day+'_start_two"]:checked').val()){
			 		$("#Serrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte gültige Startzeit wählen"); 
				 token =false;
			 	}else{ if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end"]:checked').val() !='') $("#Serrtwo_"+day).html(''); }
			

			});
			$("#busType_err").text(''); 


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
			
		}

		if(token == false){
			return false;
		}
		else{
		 $("#submitEmp").attr('disabled','');
		 form.submit();
		 setTimeout(function() {
    	  $("#frmmyEmployee").trigger('reset');
  		   }, 5000);
		}	
    }
                 
   });
</script>
