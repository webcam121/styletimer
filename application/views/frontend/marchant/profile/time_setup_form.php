<div class="salon-new-top-bg pt-4">
                <div class="pl-20 pr-20 d-flex">
                  <h3 class="font-size-20 color333 fontfamily-medium mb-2"><?php echo $this->lang->line('setup_your_profile'); ?></h3>
                </div>
                  <div class="salon-new-step">
                        <?php if(!empty($setup_no)) $data['setup_no']=$setup_no; $this->load->view('frontend/marchant/common_setup',$data); ?>
                  </div>   
              </div>
              <div class="bgwhite relative pt-4 px-3">
                <p class="color333 font-size-16 fontfamily-semibold"><?php echo $this->lang->line('working-hour-add'); ?></p>
                <form method="post" id="marchant_working_time" action="<?php echo base_url('profile/workinghour_setup'); ?>">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 m-auto">
                    <?php $days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday', 'Sunday');
                   		$i=0;
                    	foreach($days_array as $day){ ?> 
                      <div class="d-flex w-100 align-items-center mb-3 pt-3">
                          <div class="checkbox-btn display-ib">
                            <label class="font-size-14 fontfamily-medium">
                               <input type="checkbox" name="days[]" id="dayinput<?php echo strtolower($day); ?>" value="<?php echo strtolower($day); ?>" <?php if(!empty($user_available[$i]->days) && $user_available[$i]->days==strtolower($day) && $user_available[$i]->type=='open'){ echo 'checked="checked"'; } ?>>
                                <span class="squer-chack">
                                  <span class="squer-chacked"><?php echo strtoupper(substr($this->lang->line(ucfirst($day)),0,1)); ?></span>
                                </span>       
                            </label>
                          </div>
                          <span id="chk_monday" style="top:68px;" class="error"></span>
                                <div class="relative ml-3 min-width85">
                                  <p class="font-size-14 color333 fontfamily-medium mb-0"><?php echo ucfirst($this->lang->line(ucfirst($day))); ?></p>
                                </div>
                                <div class="form-group mb-0 ml-3" style="width:29%;">
                                  <div class="btn-group multi_sigle_select inp_select min-width120 btnslbl<?php echo strtolower($day); ?> <?php if(!empty($user_available[$i]->starttime)) echo "show"; ?>">
                                    <span class="label <?php if(!empty($user_available[$i]->starttime)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Start_Time'); ?></span>
                                    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn btnstxt<?php echo strtolower($day); ?>" aria-expanded="false"><?php if(!empty($user_available[$i]->starttime)) echo date('H:i',strtotime($user_available[$i]->starttime)); ?></button> 
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
                                    style="max-height: none; overflow-x: auto; height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
                                     <?php  $start = "00:00"; 
                                      $end = "23:00";
                                      $tStart = strtotime($start);
                                      $tEnd = strtotime($end);
                                      $tNow = $tStart;
                                      $ii=0;
                                      while($tNow <= $tEnd){ ?> 
                                        <li class="radiobox-image">
                                        <input type="radio" id="id_timestart<?php echo $day.$ii; ?>" name="<?php echo strtolower($day); ?>_start" class="start_cls" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php if(!empty($user_available[$i]->starttime) && $user_available[$i]->starttime==date("H:i:s",$tNow)) echo "checked"; ?>>
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
                               <div class="form-group mb-0 ml-3" style="width:29%;">
                                    <div class="btn-group multi_sigle_select inp_select min-width120 btnelbl<?php echo strtolower($day); ?> <?php if(!empty($user_available[$i]->endtime)) echo "show"; ?>">
                                    <span id="end_class" class="label <?php if(!empty($user_available[$i]->endtime)) echo "label_add_top"; ?>"><?php echo $this->lang->line('End_Time'); ?></span>
                                   <button id="cat_btn_end" data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn btnetxt<?php echo strtolower($day); ?>" aria-expanded="false"><?php if(!empty($user_available[$i]->endtime)) echo date('H:i',strtotime($user_available[$i]->endtime)); ?></button>
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll"
                                    style="max-height: none; overflow-x: auto; height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
                                    	<?php  $start = "01:00"; 
					                    $end = "23:00";
										$tStart = strtotime($start);
					                    $tEnd = strtotime($end);
					                    $tNow = $tStart;
					                    $jj=1;
					                    while($tNow <= $tEnd){ ?>

					                      <li class="radiobox-image">
					                      <input type="radio" id="id_timeend<?php echo $day.$jj; ?>" name="<?php echo strtolower($day); ?>_end" class="" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php if(!empty($user_available[$i]->endtime) && $user_available[$i]->endtime==date("H:i:s",$tNow)) echo "checked"; ?>>
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
                      </div>
                      <?php $i++;} ?>
                      
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
                        <div class="pt-3 pb-3 bgwhite boxshadow-5 px-3">
                          <a href="#" onclick="getGelleryhtml();" style="margin-bottom: 10px;" class="btn btn-border-orange btn-large widthfit2 ml-0"><?php echo $this->lang->line('Previous'); ?></a>
                          <button id="save_salon_time" type="button" class="btn btn-large widthfit2 float-right"><?php echo $this->lang->line('SaveProceed'); ?></button>
                        </div>
                    </div>
                  </div>
                </form>
              </div>
