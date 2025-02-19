<?php $timeselected = false; ?>
<div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="relative around-40 pr-65 box-shadow1 bgwhite mt-50 mb-80">
                    <?php $this->load->view('frontend/common/alert'); ?>
                    <h3 class="color333 fontfamily-medium font-size-28"><?php echo $this->lang->line('Cart2'); ?></h3>
                    <form id="selectFilters" method="get">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-5 col-lg-4 col-xl-4">

                                <!--------------------- Employee section start------------------------------------------------------------------------------------------------------------------------->
                                <div class="form-group">
                                    <p class="font-size-14 color666 fontfamily-regular">
                                        <?php echo $this->lang->line('Select-Employee'); ?></p>
                                    <div class="btn-group multi_sigle_select">
                                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn fontfamily-medium" id="emplNametxt">
                                            <img class="employee-round-icon" src="<?php echo base_url('assets/frontend/images/user-icon-gret.svg'); ?>">
                                            <?php echo $this->lang->line('Select-Employee'); ?></button>

                                        <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" id="employeeList">

                                            <?php

                                            // print_r($employee_list);
                                            if (!empty($employee_list)) {
                                                if (count($employee_list) > 1) {
                                                    $checked = ""; ?>
                                                    <li class="radiobox-image">
                                                        <input type="radio" id="id_employee1" name="employee_select" class="selectEmployee" value="any" data-text="<?php echo $this->lang->line('Any_Employee'); ?>" data-src="<?php echo base_url('assets/frontend/images/user-icon-gret.svg'); ?>" <?php if (!empty($_GET['employee_select']) && $_GET['employee_select'] == 'any' || empty($_GET['employee_select']) && !empty($employee_list) && count($employee_list) > 1) echo "checked"; ?>>
                                                        <label for="id_employee1">
                                                            <img class="employee-round-icon" src="<?php echo base_url('assets/frontend/images/user-icon-gret.svg'); ?>"><?php echo $this->lang->line('Any_Employee'); ?>
                                                        </label>
                                                    </li>

                                                <?php } else $checked = "checked";
                                                foreach ($employee_list as $emp) {
                                                    if ($emp->profile_pic != '')
                                                        $img = base_url('assets/uploads/employee/') . $emp->id . '/' . $emp->profile_pic;
                                                    else
                                                        $img = base_url('assets/frontend/images/user-icon-gret.svg');
                                                ?>
                                                    <li class="radiobox-image">
                                                        <input type="radio" id="id_employee<?php echo $emp->id; ?>" name="employee_select" class="selectEmployee" value="<?php echo url_encode($emp->id); ?>" data-src="<?php echo $img; ?>" data-text="<?php echo $emp->first_name; ?>" data-rate="<?php if (!empty($emp->myrating)) {
                                                                                                                                                                                                                                                                                                        echo number_format($emp->myrating, 1);
                                                                                                                                                                                                                                                                                                    } ?>" <?php if (!empty($_GET['employee_select']) && $_GET['employee_select'] == url_encode($emp->id))
                                                                                                                                                                                                                                                                                                                echo "checked";
                                                                                                                                                                                                                                                                                                            else echo $checked; ?>>
                                                        <label for="id_employee<?php echo $emp->id; ?>">
                                                            <img class="employee-round-icon" src="<?php echo $img; ?>">
                                                            <?php echo $emp->first_name; ?>
                                                            <!-- $emp->last_name -->
                                                            <?php if (!empty($emp->myrating)) { ?>
                                                                <span class="float-right mr-10">
                                                                    <?php echo number_format($emp->myrating, 1); ?>
                                                                    <i class="fas fa-star colororange mr-2 fontsize-16"></i></span>
                                                            <?php } ?>
                                                        </label>
                                                    </li>
                                            <?php }
                                            } else echo '<li class="checkbox-image" style="margin: 10px 0px 0px 10px;font-size: 14px;color: rgba(4, 4, 5, 0.42);">kein Mitarbeiter verfügbar</li>';  ?>
                                        </ul>

                                    </div>
                                    <label class="error" id="employe_err"></label>
                                </div>
                                <!--------------------- Employee section start------------------------------------------------------------------------------------------------------------------------->

                                <!--------------------- Calender start ------------------------------------------------------------------------------------------------------------------------->

                                <div class="form-group mt-30">
                                    <p class="font-size-14 color666 fontfamily-regular">
                                        <?php echo $this->lang->line('Select_Date_and_Time'); ?> </p>
                                    <div class="border-w border-radius4 text-center">
                                        <div class="around-15 text-center slider-wick-slick">

                                            <?php $current = date('Y-m-d');
                                            $maxdays = 90;

                                            if (!empty($_GET['date']) && $_GET['date'] != 'Beliebig') {
                                                $checkeddate = date('Y-m-d', strtotime($_GET['date']));
                                                $week = date('W', strtotime($checkeddate));
                                            } else {
                                                $checkeddate = date('Y-m-d');
                                                $week = date('W', strtotime($checkeddate));
                                            }
                                            $slide = 0;
                                            for ($i = 0; $i <= $maxdays; $i += 7) {

                                                $monthCurrent = date('Y-m-d', strtotime($current . ' +' . $i . ' days'));
                                                $setMonthDate = $monthCurrent;
                                                if (!empty($_GET['date'])) {
                                                    //echo date('d M Y',strtotime($monthCurrent));
                                                    $mindate = $monthCurrent;
                                                    $mandate = date('Y-m-d', strtotime($monthCurrent . '+7 days'));

                                                    $selectedDate = date('Y-m-d', strtotime($_GET['date']));
                                                    if ($mindate <= $selectedDate && $mandate >= $selectedDate) {
                                                        //echo date('d M Y',strtotime($monthCurrent)).'='.date('d M Y',strtotime($mindate))."==".date('d M Y',strtotime($mandate)); die;
                                                        $setMonthDate = $selectedDate;
                                                        // echo "last".date('d M Y',strtotime($setMonthDate)); die;
                                                    }
                                                }

                                                //echo date('Y-m-d',strtotime($setMonthDate));

                                            ?>

                                                <div class="slider-wick display-b  week<?php echo date('W', strtotime($monthCurrent)); ?>">

                                                    <p class="color333 font-size-16 fontfamily-medium mt-0 mb-20">
                                                        <?php

                                                        //$month_next = strftime('%B %Y', strtotime($date_now.' +1 month'));
                                                        $month =  date('M', strtotime($setMonthDate));
                                                        if ($month == 'Jan') {
                                                            $mo = 'Januar';
                                                        } else if ($month == 'Feb') {
                                                            $mo = 'Februar';
                                                        } else if ($month == 'Mar') {
                                                            $mo = 'März';
                                                        } else if ($month == 'Apr') {
                                                            $mo = 'April';
                                                        } else if ($month == 'May') {
                                                            $mo = 'Mai';
                                                        } else if ($month == 'Jun') {
                                                            $mo = 'Juni';
                                                        } else if ($month == 'Jul') {
                                                            $mo = 'Juli';
                                                        } else if ($month == 'Aug') {
                                                            $mo = 'August';
                                                        } else if ($month == 'Sep') {
                                                            $mo = 'September';
                                                        } else if ($month == 'Oct') {
                                                            $mo = 'Oktober';
                                                        } else if ($month == 'Nov') {
                                                            $mo = 'November';
                                                        } else if ($month == 'Dec') {
                                                            $mo = 'Dezember';
                                                        }
                                                        echo $mo . ' ' . $year =  date('Y', strtotime($setMonthDate));
                                                        ?></p>
                                                    <div class="relative d-flex">
                                                        <?php   //echo $i; 

                                                        for ($j = 0; $j <= 6; $j++) {

                                                            $row = $i + $j;
                                                            //echo $row;
                                                            $currentplus = date('Y-m-d', strtotime($current . ' +' . $row . ' days')); ?>



                                                            <div class="same-width color999 display-b">
                                                                <!--
							<?php if ($j == 3) { ?>
							
							<?php }
                                                            $day = strtolower(date("l", strtotime($currentplus)));
                                                            $day1 = $day; ?>
-->

                                                                <span class="d-block <?php if (in_array($day, $days) || in_array($currentplus, $nationaldays)) echo 'unslect'; ?>">
                                                                    <?php if (date('D', strtotime($currentplus)) == 'Mon') {
                                                                        $day = 'Mo';
                                                                    } else if (date('D', strtotime($currentplus)) == 'Tue') {
                                                                        $day = 'Di';
                                                                    } else if (date('D', strtotime($currentplus)) == 'Wed') {
                                                                        $day = 'Mi';
                                                                    } else if (date('D', strtotime($currentplus)) == 'Thu') {
                                                                        $day = 'Do';
                                                                    } else if (date('D', strtotime($currentplus)) == 'Fri') {
                                                                        $day = 'Fr';
                                                                    } else if (date('D', strtotime($currentplus)) == 'Sat') {
                                                                        $day = 'Sa';
                                                                    } else {
                                                                        $day = 'So';
                                                                    }
                                                                    echo  $day;
                                                                    // date('D',strtotime($currentplus)); 
                                                                    ?></span>
                                                                <div class="radiobox-image <?php if (in_array($day1, $days) || in_array($currentplus, $nationaldays)) echo 'unslect'; ?>">
                                                                    <label class="color666 pl-0">
                                                                        <input type="radio" name="date" data-month="<?php echo date('M Y', strtotime($currentplus)); ?>" ; data-slide="<?php echo $slide; ?>" class="selectdate <?php if ($currentplus == $checkeddate) echo "selectedDate"; ?>" value="<?php echo $currentplus; ?>" <?php if ($currentplus == $checkeddate) echo "checked='checked'"; ?>>
                                                                        <span class="date-chack">
                                                                            <span class="date-chacked">
                                                                                <?php echo date('d', strtotime($currentplus)); ?></span>
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
                                                <?php
                                                $totaldurationTim = 0;

                                                if (!empty($_GET['time'])) {
                                                    $time = date('H:i:s', strtotime($_GET['time']));
                                                } else {
                                                    $time = date('H:i:s');
                                                }
                                                //echo $time; die;
                                                if (!empty($_GET['date']) && $_GET['date'] != 'Beliebig') {
                                                    $dateslct = date('Y-m-d', strtotime($_GET['date']));
                                                } else {
                                                    $dateslct = date('Y-m-d');
                                                }
                                                // echo  $time; die;
                                                $checkTime = 0;

                                                if (!empty($dayslot->starttime) && !empty($dayslot->endtime)) {


                                                    foreach ($booking_detail as $row) {

                                                        if ($row->stype == 1)
                                                            $totaldurationTim       = $totaldurationTim + $row->duration;
                                                        else {
                                                            $totalMin               = $row->duration + $row->buffer_time;
                                                            $totaldurationTim       = $totaldurationTim + $totalMin;
                                                        }
                                                    }

                                                    $start = $dayslot->starttime;
                                                    $end = date('H:i:s', strtotime($dayslot->endtime));
                                                    //$end= $dayslot->endtime;

                                                    $tStart        = strtotime($start);
                                                    $tEnd          = strtotime($end);
                                                    $tNow          = $tStart;
                                                    $k             = 1;
                                                    $chekDuration  = 1;


                                                    while ($tNow <= $tEnd) {
                                                        $dis     = 0;
                                                        $total   = 0;

                                                        $timeArray        = array();
                                                        $ikj              = 0;
                                                        $strtodatyetime   = $dateslct . " " . date('H:i:s', $tNow);
                                                        $employeeId       = "";
                                                        $pstartoption     = "";
                                                        // print_r($booking_detail);
                                                        //echo $strtodatyetime."===".$tNow."===".date('Y-m-d H:i:s',$tNow); die;				     
                                                        foreach ($booking_detail as $row) {

                                                            $timeArray[$ikj]        = new stdClass;

                                                            $bkstartTime            = $strtodatyetime;
                                                            $timeArray[$ikj]->start = $bkstartTime;

                                                            if ($row->parent_service_id) {
                                                                $pstime = $this->user->select(
                                                                    'st_offer_availability',
                                                                    'starttime,endtime,days,type',
                                                                    array(
                                                                        'service_id' => $row->parent_service_id,
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

                                                            if ($row->stype == 1) {
                                                                //$totaldurationTim=$totaldurationTim+$row->duration;

                                                                $bkEndTime              = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $row->setuptime . ' minute'));
                                                                $timeArray[$ikj]->end   = $bkEndTime;
                                                                $ikj++;

                                                                $finishStart            = date('Y-m-d H:i:s', strtotime('' . $bkEndTime . ' + ' . $row->processtime . ' minute'));
                                                                $timeArray[$ikj]        = new stdClass;
                                                                $timeArray[$ikj]->start = $finishStart;

                                                                $finishEnd              = date('Y-m-d H:i:s', strtotime('' . $finishStart . ' + ' . $row->finishtime . ' minute'));
                                                                $timeArray[$ikj]->end   = $finishEnd;
                                                                $ikj++;

                                                                $strtodatyetime = $finishEnd;
                                                            } else {
                                                                $totalMin               = $row->duration + $row->buffer_time;

                                                                $bkEndTime              = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $totalMin . ' minute'));
                                                                $timeArray[$ikj]->end   = $bkEndTime;
                                                                $ikj++;

                                                                //$totaldurationTim       = $totaldurationTim+$totalMin;

                                                                $strtodatyetime = $bkEndTime;
                                                            }

                                                            if (!empty($row->type) && $row->type == 'open') {
                                                                if ($row->starttime <= date('H:i:s', $tNow) && $row->endtime >= date('H:i:s', $tNow)) {

                                                                    if (!empty($row->discount_price)) {

                                                                        $dis = ($row->price - $row->discount_price) + $dis;
                                                                        $total = $row->price + $total;
                                                                    } else {
                                                                        $total = $row->price + $total;
                                                                    }
                                                                } else {
                                                                    $total = $row->price + $total;
                                                                }
                                                            } else {
                                                                $total = $row->price + $total;
                                                            }

                                                            if ($pstartoption == "" && $row->price_start_option == "ab") {
                                                                $pstartoption = "ab ";
                                                            }

                                                            $checkBoking = 0;
                                                            $checkallUser = 0;

                                                            if ((!empty($_GET['employee_select']) && $_GET['employee_select'] != 'any' && !empty($employee_list)) || (!empty($employee_list) && count($employee_list) == 1)) {
                                                                if (!empty($_GET['employee_select']) && $_GET['employee_select'] != 'any') {
                                                                    $employeeId = url_decode($_GET['employee_select']);
                                                                } else
                                                                    $employeeId  = $employee_list[0]->id;
                                                            }
                                                            //echo '<pre>'; print_r($employee_list); die;	  

                                                            if (!empty($empBookSlot) && (empty($_GET['employee_select']) || $_GET['employee_select'] == 'any') && !empty($employee_list) && count($employee_list) != 1) {
                                                                $employeeId = $employee_list[0]->id;
                                                            }
                                                        }

                                                        $resultCheckSlot = false;
                                                        if (!empty($employee_list)) {
                                                            $resultCheckSlot = checkTimeSlots($timeArray, $employeeId, url_decode($merchant_id), $totaldurationTim, "", $employee_list);
                                                        }

                                                        if ($checkBoking != 1 && $resultCheckSlot == true) {
                                                            if (($dateslct == date('Y-m-d') && date('H:i:s', $tNow) < date('H:i:s'))) {
                                                            } else {


                                                                $checkTime++; ?>

                                                                <li class="select-time-price lineheight40 <?php if ($time <= date('H:i:s', $tNow) && ($time > date('H:i:s', strtotime('-15 minutes', $tNow)) || date("H:i:s", $tNow) == '00:00:00')) { echo "selected_time"; $timeselected = true;} ?>">
                                                                    <input type="radio" id="id_time-price<?php echo $k; ?>" name="time" class="slectTime" value="<?php echo date("H:i:s", $tNow) ?>" <?php if ($time <= date('H:i:s', $tNow) && ($time > date('H:i:s', strtotime('-15 minutes', $tNow))
                                                                                                                                                                                                            || date("H:i:s", $tNow) == '00:00:00')) echo "checked";  ?>>
                                                                    <label for="id_time-price<?php echo $k; ?>">
                                                                        <span class="text-left pl-10"><?php echo date("H:i", $tNow); ?></span>
                                                                        <span>

                                                                            <span class="new-price float-right"><?php if ($pstartoption == 'ab ') {
                                                                                                                    echo $pstartoption . ' ' . price_formate($total - $dis);
                                                                                                                } else {
                                                                                                                    echo price_formate($total - $dis);
                                                                                                                }  ?> €</span>

                                                                            <span class="old-price "><del>
                                                                                    <?php if (!empty($dis)) {
                                                                                        if ($pstartoption == 'ab ') {
                                                                                            echo $pstartoption . ' ' . price_formate($total) . '€';
                                                                                        } else {
                                                                                            echo price_formate($total) . '€';
                                                                                        }
                                                                                    }
                                                                                    // echo price_formate($total)
                                                                                    ?> </del></span>
                                                                        </span>
                                                                    </label>
                                                                </li>
                                                    <?php   }
                                                        }
                                                        $k++;
                                                        $tNow = strtotime('+15 minutes', $tNow);
                                                    }
                                                } else {
                                                    $checkTime++; ?>

                                                    <h6 class="mt-3">
                                                        <?php

                                                        $formatter = new IntlDateFormatter(
                                                            "de-DE",
                                                            IntlDateFormatter::LONG,
                                                            IntlDateFormatter::NONE,
                                                            "Europe/Berlin",
                                                            IntlDateFormatter::GREGORIAN,
                                                            "MMMM"
                                                        );
                                                        $month = $formatter->format(new DateTime($dateslct));

                                                        echo sprintf($this->lang->line('No-Free-Slots'), date('d', strtotime($dateslct)), $month);
                                                        ?>
                                                    </h6>
                                                    <h6><?php echo $this->lang->line('Please-choose-another-day'); ?></h6>


                                                    <?php }


                                                if (!empty($dayslot->starttime_two) && !empty($dayslot->endtime_two)) {

                                                    //~ foreach($booking_detail as $row){

                                                    //~ $ti=$row->duration+$row->buffer_time;
                                                    //~ $totaldurationTim=$totaldurationTim+$ti;

                                                    //~ }

                                                    $start  = $dayslot->starttime_two;
                                                    $end    = date('H:i:s', strtotime($dayslot->endtime_two));
                                                    //$end= $dayslot->endtime;

                                                    $tStart = strtotime($start);
                                                    $tEnd   = strtotime($end);
                                                    $tNow   = $tStart;
                                                    $k      = 1;
                                                    $chekDuration = 1;

                                                    while ($tNow <= $tEnd) {

                                                        $dis              = 0;
                                                        $total            = 0;
                                                        $timeArray        = array();
                                                        $ikj              = 0;
                                                        $strtodatyetime   = $dateslct . " " . date('H:i:s', $tNow);
                                                        $employeeId       = "";
                                                        $pstartoption     = "";
                                                        //echo $strtodatyetime."===".$tNow."===".date('Y-m-d H:i:s',$tNow); die;				     
                                                        foreach ($booking_detail as $row) {

                                                            $timeArray[$ikj]        = new stdClass;

                                                            $bkstartTime            = $strtodatyetime;
                                                            $timeArray[$ikj]->start = $bkstartTime;

                                                            if ($row->parent_service_id) {
                                                                $pstime = $this->user->select(
                                                                    'st_offer_availability',
                                                                    'starttime,endtime,days,type',
                                                                    array(
                                                                        'service_id' => $row->parent_service_id,
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

                                                            if ($row->stype == 1) {
                                                                //$totaldurationTim=$totaldurationTim+$row->duration;

                                                                $bkEndTime              = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $row->setuptime . ' minute'));
                                                                $timeArray[$ikj]->end   = $bkEndTime;
                                                                $ikj++;

                                                                $finishStart            = date('Y-m-d H:i:s', strtotime('' . $bkEndTime . ' + ' . $row->processtime . ' minute'));
                                                                $timeArray[$ikj]        = new stdClass;
                                                                $timeArray[$ikj]->start = $finishStart;

                                                                $finishEnd              = date('Y-m-d H:i:s', strtotime('' . $finishStart . ' + ' . $row->finishtime . ' minute'));
                                                                $timeArray[$ikj]->end   = $finishEnd;
                                                                $ikj++;

                                                                $strtodatyetime = $finishEnd;
                                                            } else {
                                                                 $totalMin               = $row->duration + $row->buffer_time;

                                                                $bkEndTime              = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $totalMin . ' minute'));
                                                                $timeArray[$ikj]->end   = $bkEndTime;
                                                                $ikj++;

                                                                //$totaldurationTim       = $totaldurationTim+$totalMin;

                                                                $strtodatyetime = $bkEndTime;
                                                            }


                                                            if (!empty($row->type) && $row->type == 'open') {

                                                                if ($row->starttime <= date('H:i:s', $tNow) && $row->endtime >= date('H:i:s', $tNow)) {

                                                                    if (!empty($row->discount_price)) {
                                                                        $dis   = ($row->price - $row->discount_price) + $dis;
                                                                        $total = $row->price + $total;
                                                                    } else {
                                                                        $total  = $row->price + $total;
                                                                    }
                                                                } else {
                                                                    $total  = $row->price + $total;
                                                                }
                                                            } else {
                                                                $total        = $row->price + $total;
                                                            }

                                                            if ($pstartoption == "" && $row->price_start_option == "ab") {
                                                                $pstartoption = "ab ";
                                                            }

                                                            $checkBoking  = 0;
                                                            $checkallUser = 0;

                                                            if ((!empty($_GET['employee_select']) && $_GET['employee_select'] != 'any' && !empty($employee_list)) || (!empty($employee_list) && count($employee_list) == 1)) {
                                                                if (!empty($_GET['employee_select']) && $_GET['employee_select'] != 'any') {
                                                                    $employeeId = url_decode($_GET['employee_select']);
                                                                } else
                                                                    $employeeId  = $employee_list[0]->id;
                                                            }
                                                            //echo '<pre>'; print_r($employee_list); die;	  

                                                            if ((empty($_GET['employee_select']) || $_GET['employee_select'] == 'any') && !empty($employee_list) && count($employee_list) != 1) {
                                                                $employeeId = $employee_list[0]->id;
                                                            }
                                                        }

                                                        $resultCheckSlot = false;
                                                        if (!empty($employee_list)) {
                                                            $resultCheckSlot = checkTimeSlots($timeArray, $employeeId, url_decode($merchant_id), $totaldurationTim, "", $employee_list);
                                                        }

                                                        if ($checkBoking != 1 && $resultCheckSlot == true) {
                                                            if (($dateslct == date('Y-m-d') && date('H:i:s', $tNow) < date('H:i:s'))) {
                                                            } else {
                                                                $checkTime++; ?>
                                                                <li class="select-time-price lineheight40 <?php if (($time <= date('H:i:s', $tNow) && ($time > date('H:i:s', strtotime('-15 minutes', $tNow)) || date("H:i:s", $tNow) == '00:00:00')) || ($timeselected == false && date('H:i:s', $tNow) >= date('H:i:s'))) { echo "selected_time"; $timeselected = true;} ?>">
                                                                    <input type="radio" id="id_time-2price<?php echo $k; ?>" name="time" class="slectTime" value="<?php echo date("H:i:s", $tNow) ?>" <?php if ($time <= date('H:i:s', $tNow) && ($time > date('H:i:s', strtotime('-15 minutes', $tNow)) || date("H:i:s", $tNow) == '00:00:00')) echo "checked";  ?>>
                                                                    <label for="id_time-2price<?php echo $k; ?>">
                                                                        <span class="text-left pl-10"><?php echo date("H:i", $tNow); ?></span>
                                                                        <span>

                                                                            <span class="new-price float-right"><?php if ($pstartoption == 'ab ') {echo $pstartoption;} echo price_formate($total - $dis); ?> €</span>

                                                                            <span class="old-price "><del><?php if (!empty($dis)) { if ($pstartoption == 'ab ') {echo $pstartoption;} echo price_formate($total) . " €";} ?> </del></span>
                                                                        </span>
                                                                    </label>
                                                                </li>
                                                    <?php }
                                                        }
                                                        //echo $k;
                                                        $k++;
                                                        $tNow = strtotime('+15 minutes', $tNow);
                                                    }
                                                }



                                                if ($checkTime == 0) { ?>
                                                    <h6 class="mt-3">
                                                        <?php

                                                        $formatter = new IntlDateFormatter(
                                                            "de-DE",
                                                            IntlDateFormatter::LONG,
                                                            IntlDateFormatter::NONE,
                                                            "Europe/Berlin",
                                                            IntlDateFormatter::GREGORIAN,
                                                            "MMMM"
                                                        );

                                                        $month = $formatter->format(new DateTime($dateslct));
                                                        echo sprintf($this->lang->line('No-Free-Slots'), date('d', strtotime($dateslct)), $month);
                                                        ?>
                                                    </h6>
                                                    <h6><?php echo $this->lang->line('Please-choose-another-day'); ?></h6>

                                                <?php } ?>

                                            </ul>
                                        </div>
                                        <label class="error" id="time_err"></label>
                                    </div>
                                </div>
                                <input type="hidden" name="totalDuration" value="<?php echo $totaldurationTim; ?>">
                                <input type="hidden" id="" name="merch_id" value="<?php echo $merchant_id; ?>">

                            </div>

                            <!--------------------- Time Slot End ----------------------------------------------------------------------------------------------------------------------------->

                            <!---------------------Cart Service List----------------------------------------------------------------------------------------------------------------------------->

                            <div class="col-12 col-sm-12 col-md-7 col-lg-8 col-xl-8">

                                <?php

                                $dis   = 0;
                                $total = 0;

                                $abcheck = "";
                                foreach ($booking_detail as $row) {
                                    if ($row->price_start_option == 'ab') {
                                        if (empty($abcheck)) $abcheck = "ab";
                                    }

                                    $discount = 0;
                                    if (!empty($row->type) && $row->type == 'open') {
                                        if ($row->starttime <= $time && $row->endtime >= $time) {

                                            if (!empty($row->discount_price)) {
                                                $discount = get_discount_percent($row->price, $row->discount_price);
                                                $dis = ($row->price - $row->discount_price) + $dis;
                                                $total = $row->discount_price + $total;
                                                $priceCh = $row->discount_price;
                                            } else {
                                                $total = $row->price + $total;
                                                $priceCh = $row->price;
                                            }
                                        } else {
                                            $total = $row->price + $total;
                                            $priceCh = $row->price;
                                        }
                                    } else {
                                        $priceCh = $row->price;
                                        $total = $row->price + $total;
                                    }

                                ?>
                                    <div class="border-b d-flex p-3 mt-4" id="row_<?php echo $row->id; ?>">
                                        <div class="">
                                            <p class="color333 font-size-16 fontfamily-medium mb-1"><?php echo $row->category_name;
                                                                                                    if (!empty($row->name)) echo " - " . $row->name; ?></p>
                                            <span class="font-size-14 color666 fontfamily-regular"><?php echo $row->duration; ?> <?php echo $this->lang->line('Minute'); ?></span>
                                        </div>
                                        <div class="deatail-box-right text-right">
                                            <div class="relative text-right display-ib vertical-middle mr-2">
                                                <p class="fontfamily-medium color333 font-size-14 mb-0">
                                                    <?php
                                                    if (!empty($discount)) {
                                                    ?>
                                                        <span class="color999"><del>
                                                                <?php
                                                                if ($row->price_start_option == 'ab') echo $row->price_start_option . ' ';
                                                                echo price_formate($row->price); ?> €<del></span>
                                                    <?php }
                                                    if ($row->price_start_option == 'ab') echo $row->price_start_option . ' ';
                                                    echo price_formate($priceCh); ?> €
                                                </p>
                                                <?php
                                                if (!empty($discount)) {  ?>
                                                    <span class="colorcyan fontfamily-regular font-size-14"><?php echo $this->lang->line('You-Save'); ?> <?php echo $discount; ?> %</span>
                                                <?php }  ?>
                                            </div>
                                            <a href="javascript:void(0);" class="display-ib remove_service" id="<?php echo $row->id; ?>" data-id="<?php echo $merchant_id; ?>"><img src="<?php echo base_url('assets/frontend/images/crose_grediant_orange_icon1.svg'); ?>"></a>
                                        </div>
                                    </div>
                                <?php }
                                $getUrl = "";
                                if (!empty($_GET)) {
                                    $param = [];
                                    foreach ($_GET as $k => $v) $param[] = $k . '=' . $v;

                                    $getUrl = '?' . implode('&', $param);
                                } ?>

                                <div class="relative p-3">
                                    <a href="<?php echo base_url('user/service_provider/' . $merchant_id . $getUrl); ?>" class="color333 fontfamily-medium font-size-16 a_hover_333"><img src="<?php echo base_url('assets/frontend/images/add_blue_plus_icon.svg'); ?>" class="mr-12"><?php echo $this->lang->line('Add_Another_Service'); ?></a>
                                </div>

                                <div class="d-flex p-3">
                                    <div class="deatail-box-left">
                                        <?php if (!empty($dis)) { ?>
                                            <p class="font-size-16 color666 fontfamily-medium mb-20"><?php echo $this->lang->line('Discount1'); ?></p>
                                        <?php } ?>
                                        <p class="font-size-20 color333 fontfamily-semibold"><?php echo $this->lang->line('Payable-Amount'); ?></p>
                                    </div>
                                    <div class="deatail-box-right text-right">
                                        <?php if (!empty($dis)) { ?>
                                            <p class="font-size-16 color666 fontfamily-medium mb-20"><span id="service_count"><?php echo price_formate($dis); ?></span> €</p>
                                        <?php } ?>
                                        <p class="font-size-20 color333 fontfamily-semibold"><span id="total_amt"><?php echo $abcheck . ' ' . price_formate($total); ?></span> €</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control scroll-effect height90v" placeholder="Buchungsnotiz schreiben..." id="user_booking_note" name="user_booking_note" style="min-height: 70px;max-height:70px;"></textarea>
                                </div>
                                <div class="relative text-center mt-30 mb-30">
                                    <button type="button" id="confirm_booking_next" class="btn btn-bg-orange-gre widthfit" value="<?php echo $merchant_id; ?>" <?php if (!empty($dayslot)) echo " ";
                                                                                                                                                                else echo "disabled='disabled'"; ?>><?php echo $this->lang->line('Confirm-Booking'); ?></button>
                                    <!--id="confirm_booking"-->
                                    <!-- <button class=" btn btn-large widthfit mx-auto" data-toggle="modal" data-target=".booking_success" data-backdrop="static" data-keyboard="false">Confirm Booking</button> -->
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        var baseUrl = "<?php echo base_url(); ?>";

        jQuery(document).ready(function($) {
            if ($('input[name="time"]:checked').length == 0) {
                $($('input[name="time"]')[0]).trigger('click');
            }

            if ($('input[name="time"]:checked').length != 0) {
                var heith = 0;
                $(".select-time-price").each(function() {
                    var heit = $(this).height();
                    if ($(this).hasClass("selected_time")) {
                        return false;
                    } else {
                        heith = heith + heit;
                    }
                });

                $("#dropdownTime").animate({
                    scrollTop: heith
                }, 500, 'swing', function() {

                });
            }



            var name = $('input[name="employee_select"]:checked').attr('data-text');
            var src = $('input[name="employee_select"]:checked').attr('data-src');
            var rate = $('input[name="employee_select"]:checked').attr('data-rate');
            //$(".selected_time").focus();
            console.log("src---------------", src)
            console.log("name---------------", name)

            if (src != undefined && src != "" && name != undefined && name != "") {
                if (rate == undefined || rate == "") {
                    var st = "";
                    var rate = "";
                } else {
                    var st = '<i class="fas fa-star colororange mr-2 fontsize-16"></i>';
                }
                $('#emplNametxt').val("");
                $("#emplNametxt").html('<img class="employee-round-icon" src="' + src + '">' + name + '<span class="float-right mr-10">' + rate + ' ' + st + '</span>');

            }
            var slideIntil = $('input[name="date"]:checked').attr('data-slide');

            //Number(slideIntil);
            var it = parseInt(slideIntil);
            $('.slider-wick-slick').slick({
                dots: false,
                infinite: false,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                initialSlide: it

            });

        });
    </script>