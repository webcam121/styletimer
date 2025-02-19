<div class="form-group">
        <p class="font-size-14 color666 fontfamily-regular"><?php echo $this->lang->line('reshwdule_select_date_and_time'); ?></p>
        <div class="border-w border-radius4 text-center">							
          <div class="around-15 text-center slider-wick-slick">
                           
			<?php   //$days         = array();  
                        $current      = date('Y-m-d');
					    $maxdays      = 90;
							  // echo $postdate; die; 
							  if(!empty($postdate))
								    {

								      $checkeddate = date('Y-m-d',strtotime($postdate));
								      $week        = date('W',strtotime($checkeddate));
								    }
							   else{
									    $checkeddate = date('Y-m-d');
									    $week        = date('W',strtotime($checkeddate));
									}

									$slide = 0;

							   for($i=0;$i<=$maxdays;$i += 7)
                                  {
								   
								   	$monthCurrent=date('Y-m-d', strtotime($current . ' +'.$i.' days'));		
								   	  $setMonthDate=$monthCurrent;  		
										   if(!empty($postdate)){ 
											    //echo date('d M Y',strtotime($monthCurrent));
											  $mindate=$monthCurrent;
											  $mandate=date('Y-m-d', strtotime($monthCurrent . '+7 days'));
											   		
												  $selectedDate=date('Y-m-d',strtotime($postdate));
												  if($mindate<=$selectedDate && $mandate>=$selectedDate){
													 //echo date('d M Y',strtotime($monthCurrent)).'='.date('d M Y',strtotime($mindate))."==".date('d M Y',strtotime($mandate)); die;
													  $setMonthDate=$selectedDate;
													 // echo "last".date('d M Y',strtotime($setMonthDate)); die;
													  }	
																	   
										     }
								   	
								   	//echo date('Y-m-d',strtotime($setMonthDate));
									
										

									
								   ?>
								   
								   <div class="slider-wick display-b  week<?php echo date('W',strtotime($monthCurrent)); ?>">
								   <?php
								   $month=date('M',strtotime($setMonthDate));
								   $year=date('Y',strtotime($setMonthDate)) ;
								   $arr_month=['Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mär','Apr'=>'Apr','May'=>'Mai','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'okt','Nov'=>'Nov','Dec'=>'Dez'];
								   $mon=$arr_month[$month];
?>								   
									 <p class="color333 font-size-16 fontfamily-medium mt-0 mb-20"><?php echo $mon ?> <?php echo $year ?></p>
									 <div class="relative d-flex">

										<?php  for($j=0;$j<=6;$j++)
										           { 
												   
													   $row         = $i+$j;
											  // echo $row;
														 $currentplus = date('Y-m-d',strtotime($current . ' +'.$row.' days'));
														 $day = strtolower(date("l",strtotime($currentplus)));
														 $day1 = strtolower(date("l",strtotime($currentplus)));
														 ?>


																							
															  <div class="same-width color999 display-b">							
																<span class="d-block <?php if(in_array($day,$days) || in_array($currentplus,$nationaldays)) echo 'unslect'; ?>">
																<?php 
																$Arr_days=['Mon'=>'Mo','Tue'=>'Di','Wed'=>'Mi','Thu'=>'Do','Fri'=>'Fr','Sat'=>'Sa','Sun'=>'So'];
																$day=date('D',strtotime($currentplus));
																$ger_day=$Arr_days[$day];
																
																echo $ger_day  ?>
																
																</span>
																 <div class="radiobox-image <?php if(in_array($day1,$days) || in_array($currentplus,$nationaldays)) echo 'unslect'; ?>">
																  <label class="color666 pl-0">
																	<input type="radio" name="chg_date" data-month="<?php echo date('M Y',strtotime($currentplus)); ?>"; data-slide="<?php echo $slide; ?>" class="chg_date <?php if($currentplus==$checkeddate) echo "selectedDate"; ?>" value="<?php echo $currentplus; ?>" <?php if($currentplus==$checkeddate) echo "checked='checked'"; ?>>
																	   <span class="date-chack">
																		  <span class="date-chacked">
																		  <?php echo date('d',strtotime($currentplus)); ?>
																		</span>
																	   </span>
																  </label>                                
																</div>
															  </div>

												<?php }  ?>

									   </div> 
									 </div>
								<?php $slide++;

						     }  ?>

        </div>

<!--------------------- Calender End -------------------------------------------------------------------------------------------------------------------------------->

<!--------------------- Time Slot Start ----------------------------------------------------------------------------------------------------------------------------->

         <div class="border-t">
            <ul class="pl-0 scroll245 custom_scroll mb-0" id="dropdownTime">

					   

                        </ul>
               </div>
		  <label class="error" id="time_err"></label>
		</div>
	  </div>
	  
	 <input type="hidden" id="reSchedule_id" name="reSchedule_id" value="<?php echo $book_id; ?>">
	 <input type="hidden" id="reseid" name="reseid" value="<?php echo $emp_id; ?>">
	  
	  <div class="text-center">
		<button type="button" id="booking_reSch" class="btn widthfit"><?php echo $this->lang->line('Submit'); ?></button>
	</div>
	</div>
