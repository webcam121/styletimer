<?php $this->load->view('frontend/common/header');
 $this->load->view('frontend/common/editer_css'); $keyGoogle =GOOGLEADDRESSAPIKEY;
 ?>

<style>
.pick-overlay {
	display: none;
	position: absolute;
	width: 100%;
	height: 100%;
	z-index: 1000;
}
#block_specific_date_wrap {
	position: 'relative';
}
#block_specific_date_wrap.pick-disable .pick-overlay{
	display: block;
}
.fc-bgevent.fc-extrabooking {
	/* background: #d7d7d7; */
	touch-action: pan-y;
    /* width: 100%; */
    /* height: 100%; */
    position: relative;
    background-size: 8px 8px;
    background-image: linear-gradient(45deg, transparent 46%, rgba(16, 25, 40, 0.69) 49%, rgba(16, 25, 40, 0.62) 51%, transparent 55%);
    background-color: transparent;
}
#block_specific_date_wrap.pick-disable #block_specific_date,
#block_specific_date_wrap.pick-disable #block_specific_date::placeholder {
	color: #999 !important;
}
.after-box-roted {
	display: none !important;
}
/*******************************************************Profile setup*******************************************************/
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

.round-employee-upload {
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
}

.fc-time-grid{
	min-height:0% !important;
}
.round-employee-upload .all_type_upload_file.bgblack18 {
    background:
    rgba(00,00,00,.60);
    width: 100%;
    position: absolute;
    bottom: -2rem;
    right: 0rem;
    height: 25px;
    transition: linear .4s;
}
.round-employee-upload:hover .all_type_upload_file.bgblack18 {
    bottom: 0rem;
}


/*******************************************************Profile setup*******************************************************/

.pignose-calender-row a.hover {
    background-color: #D8D8D8;
}

.pignose-calender-row a:hover {
    background-color: #D8D8D8;
}

.fc-toolbar .fc-right {
    float: right;
    position: absolute;
    right: 80px;
}

.fc-time-grid .fc-content-col {

    position: static !important;

}

.v_custome_button{
	position:absolute;
	right: 20px;
	}
.fc-toolbar.fc-header-toolbar .fc-left{
	right:8rem;
	background:linear-gradient(to bottom, #fff, #e6e6e6);

}
.swal2-title{
	font-size:1.125em !important;
	}
.fc-event-container{
    word-break: break-all;
}
sup {

    top: -0.7em;
    font-size: 13px;

}
.subtimeslot{
	color: #BBBCBC !important;
	}
.fc-day-header.fc-widget-header {

    font-size: 20px;
    font-weight: inherit;
}

.fc-axis.fc-widget-header{
	width: 40px;
    height: 40px;
    padding-top: 10px;
	}
.fc-day-header.fc-widget-header{
	padding-top: 10px;
	}

.fc-right .fc-agendaThreeDay-button{
    padding: 0 15px !important;
    height: 42px !important;
    text-shadow: none !important;
    box-shadow: none !important;
    border: none;
    border-right: 1px solid #e8e8e8 !important;
    text-transform: uppercase !important;
    font-weight: 700 !important;
    color: #666666 !important; 
    border-radius: 0px 0px 0px 0px !important;   
}

.fc-time-grid-event{/*
width: 100%;*/
margin: 0px -3px 0px -2px;
padding: 4px 0px 0 4px;
 }
.fc-time-grid-event .fc-time {
    font-size: 1.0em !important;
}


.fc-event-time, .fc-event-title {
padding: 0 1px;
white-space: nowrap;
}
.fc-toolbar .fc-center {
    display: none !important;
	}
	.fc .fc-toolbar > * > * {
    float: left;
    margin-left: 0 !important;
}
.fc-time{
	color: #666666;
	}

.fc-widget-content{
	height: 25px !important;
	}
.tooltipevent {
    padding: 15px 15px 5px 15px;
}

.plus-green{
  background:#00949d;
  color: #fff;
  border:none;
  height: 42px;
  width: 45px;
  text-align: center;
  font-size: 20px;
  border-radius: 0.25rem;
  cursor: pointer;
  padding:11px !important;
}

.v_custome_button .dropdown-toggle::after{
	display:none;
}

.fc-ltr .fc-time-grid .fc-now-indicator-arrow {

    left: 0;
    border-width: none !important;
    height: 15px;
    line-height: 15px;
    border-top-color: none !important;
    border-bottom-color: none !important;
    border-radius: 4px !important;
    border: 2px solid red;
    border-top-color: red;
    border-bottom-color: red;
    border-top-color: red;
    border-bottom-color: red;
    width: 40px!important;
    font-size: 12px !important;
    background: red !important;
	z-index: 5 !important;
	color: white !important;
	font-weight: bold;

}
.fc-toolbar.fc-header-toolbar .fc-left h2{
	overflow: hidden;
	background:linear-gradient(to bottom, #fff, #e6e6e6);
	cursor:pointer;
}
.fc-toolbar.fc-header-toolbar .fc-left:hover,
.fc-toolbar.fc-header-toolbar .fc-left h2:hover{
	color: #333;
    text-decoration: none;
    -webkit-transition: background-position .1s linear;
    -moz-transition: background-position .1s linear;
    -o-transition: background-position .1s linear;
	transition: background-position .1s linear;
	background-color:#666666 !important;
}
 .alert.alert-danger1.vinay{
	position: absolute;
    padding: 7px 10px;
    margin-top: 10px;
    margin-bottom: 10px;
    top: 0px;
    border: 1px solid transparent;
    border-radius: .25rem;
    height: 40px;
    z-index: 9999;
    background-color: #ebccd1;
    width:97%;
    margin-top:7px;
    text-align:center;
    z-index: 10;
}
.v_custome_button .dropdown-menu{
	left:auto !important;
	right:0px !important;
  transform: translate3d(0px, 44px, 0px)!important;
}
.sticky-calender-top{
	 background:#00b3bf;
	 height: 52px;
    line-height: 52px;
    position: fixed;
    text-align: 0;
    top: -52px;
    width: 100%;
    left: 0;
    right:0rem;
    font-size:20px;
    z-index: 9999;
    text-align: center;
    color:#fff;
    padding:0rem 30px;
    transition:ease .5s;
	}
	.sticky-calender-top.fixed-top-add{
		top:0px;
		transition:ease .5s;
		}
	.sticky-calender-top a,.sticky-calender-top a:hover{
		color:#fff;
		float:right;}

  .datepickerweekend.form-control {
    border:none;
    height: 0rem;
    padding: 0rem;
  }

 .fc-today{
	  background-color:rgba(69,170,181,0.05) !important;
	}

.fc-current {
	background-color: rgba(255,255,255,0) !important;
	}
	/* #fc-today-tag{
	  background-color:rgba(69,170,181,0.05) !important;
	} */
	.fc-scroller.fc-time-grid-container{
		height:calc(100vh - 220px) !important;
	}
	/* .fc-scroller.fc-time-grid-container{
	height: calc(100vh - 295px) !important
} Dharmend	 */
  .notePretag{
    overflow-x: auto;
    white-space: pre-wrap;
    white-space: -moz-pre-wrap;
    white-space: -pre-wrap;
    white-space: -o-pre-wrap;
    word-wrap: break-word;
    margin-bottom: 0;
  }
  .notePretag p{
    margin-bottom: 0!important;
  }

  .alert-new-warning{
    position: relative;
    top: 10px !important;
    z-index: 999;
    background: #00949d;
    color: #fff;
    padding: 7px 15px;
    min-width: auto;
    width: auto !important;
    max-width: max-content !important;
    padding-right: 40px;
}
.fc-unthemed .fc-head .fc-row,
.fc-unthemed .fc-head td,
.fc-unthemed .fc-head th,
.fc-unthemed .fc-head thead ,
.fc-unthemed td.fc-axis.fc-time.fc-widget-content{
	border-color: transparent !important;
	border-left-color:transparent !important;
	border-bottom-color:#ddd !important;
}
/* .fc-unthemed .fc-head th:first-child{
	position:relative;
}
.fc-unthemed .fc-head th:first-child::after{
	content:'';
	position:absolute;
	bottom:-1px;
	width:auto;
	height:1px;
	background:#fff;
	left:0px;
	right:0px;
} */
/* .fc-unthemed .fc-head thead tr:first-child th {
    border-bottom-color: transparent !important;
} */

.fc-unthemed .fc-body > tr > td.fc-widget-content{
	border-left-color:transparent !important;
}
	@media(max-width:992px){
		.fc-scroller.fc-time-grid-container{
			height:calc(100vh - 265px) !important;
		}
	}
	@media(max-width:576px){
		.fc-scroller.fc-time-grid-container{
			height:calc(100vh - 285px) !important;
		}
	}
	@media(max-width:471px){
		.fc-scroller.fc-time-grid-container{
			height:calc(100vh - 340px) !important;
		}
	}
</style>
<link rel="stylesheet" href='<?php echo base_url('assets/frontend/js/fullcalender/fullcalendar.min.css'); ?>'/>
<link rel="stylesheet" href='<?php echo base_url('assets/frontend/css/pignose.calender.css'); ?>'/>
<link rel="stylesheet" href="<?php echo base_url("assets/cropp/croppie.css");?>" type="text/css" />

<!-- 
<link rel="stylesheet" href='<?php echo base_url('assets/frontend/js/fullcalender/scheduler.min.css'); ?>'/>
<link rel="stylesheet" href='<?php echo base_url('assets/frontend/js/fullcalender/fullcalendar.print.min.css'); ?>'/> 
-->

<style>
.user_profile_section1 .alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0.display-n, .user_profile_section1 .alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0{
    top: 0px !important;
}

</style>

<section class="pt-84 clear user_profile_section1">
    <?php $this->load->view('frontend/common/sidebar'); ?>
 	<div class="right-side-dashbord w-100 pl-30 pr-15">
 	<?php $this->load->view('frontend/common/alert'); ?>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

					<div class="sticky-calender-top">
						<span id="blocktext"><?php echo $this->lang->line('select_atime_to_block'); ?></span><a href="#" id="closeDrop_selctTime" class="fontsize-30">&times;</a>
					</div>



					 <?php if(!empty($this->session->userdata('online_booking'))){ ?>

						 <div class="alert alert-new-warning">Ihr Salon ist aktuell nicht für Kunden sichtbar. Um Online Buchungen zu aktivieren, besuchen Sie bitte die <a href="<?php echo base_url('profile/edit_marchant_profile'); ?>" class="a_hover_white colorwhite text-underline">Salon Einstellungen</a> und aktivieren Sie Online-Buchungen. <a href="JavaScript:Void(0);" class="close" id="closeOnlineBokkingOptionMsg" data-dismiss="alert" aria-label="close"><img src="<?php echo base_url('assets/frontend/images/close-new-white.svg'); ?>" class="close-alert-white" alt="" ></a></div>

        			<?php } ?>
					 <div id="chg_fullClass" class="relative mb-60 bgwhite box-shadow1 around-20 mt-20 border-radius4" style="<?php if(!empty($this->session->flashdata('member_error'))) echo $this->lang->line(''); ?>;">




						<form method='get' id="employeeForm">
						<div class="btn-group multi_sigle_select calender-droupdown">
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" style=""><?php if(!empty($employee)) echo $employee->first_name." ".$employee->last_name; else echo $this->lang->line('All_staff'); ?></button>
                            <ul class="dropdown-menu mss_sl_btn_dm noclose">
								<?php if(!empty($employees)){  ?>
                                  	<li class="radiobox-image">
								    <input type="radio" id="idq_1all" name="id" <?php if(empty($_GET['id'])){ echo 'checked'; } ?> class="saleEmployee" value=""><label for="idq_1all"><?php echo $this->lang->line('All_staff'); ?></label></li>

								<?php	foreach($employees as $emp){ ?>
                                <li class="radiobox-image"><input type="radio" id="idq_1<?php echo $emp->id; ?>" name="id" class="saleEmployee" value="<?php echo url_encode($emp->id); ?>" <?php if(!empty($_GET['id']) && $_GET['id']==url_encode($emp->id)) echo 'checked'; ?>><label for="idq_1<?php echo $emp->id; ?>"><?php echo $emp->first_name." ".$emp->last_name; ?></label></li>
                                <?php } } ?>

                            </ul>
                        </div>
                        <input type="hidden" name="view" value="<?php if(!empty($_GET["view"])) echo $_GET["view"]; else echo "agendaWeek"; ?>" id="calenderView">
                        <input type="hidden" id="selectTypeOption" name="select_type" value="<?php if(!empty($_SERVER['HTTP_REFERER']) && !empty($_GET['option']) && $_GET['option']=='rebook') echo "rebook"; else echo 'book'; ?>">
                        </form>
                         <div class="display-ib ml-auto v_custome_button" >
            							 <div class="dropdown">
            							  <button class="plus-green dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            								<i class="fas fa-plus colorwhite"></i>
            							  </button>
            							  <div class="new-effect-droup" aria-labelledby="dropdownMenuButton">
            								<a class="dropdown-item" href="<?php echo base_url('booking/new_booking'); ?>"><?php echo $this->lang->line('Add_New_Booking'); ?></a>
            								<a class="dropdown-item" id="newBlockedTime" href="#"><?php echo $this->lang->line('New_Blocked_time'); ?></a>
            							  </div>
            							</div>
<!--
							  <a href="<?php echo base_url('booking/new_booking'); ?>"><button class="btn btn-large widthfit"></button></a>
-->

                         </div>
                         <!-- <span id="d" class="datepickerweekend">dd</span> -->

                        <div id='calendar'>
                          <div class="weekly-calender display-n">
                            <div class="calender-weekily"></div>
                            	<div class="weekly-btn">
									<div class="relative w-100">
										<span class="weekJump" data-date="<?php echo date('Y-m-d',strtotime(date("Y-m-d",time())." +1 week")); ?>">In 1 <?php echo $this->lang->line('week'); ?></span>
										<span class="weekJump" data-date="<?php echo date('Y-m-d',strtotime(date("Y-m-d",time())." +2 week")); ?>">In 2 <?php echo $this->lang->line('Weeks'); ?></span>
										<span class="weekJump" data-date="<?php echo date('Y-m-d',strtotime(date("Y-m-d",time())." +3 week")); ?>">In 3 <?php echo $this->lang->line('Weeks'); ?></span>
										<span class="weekJump" data-date="<?php echo date('Y-m-d',strtotime(date("Y-m-d",time())." +4 week")); ?>">In 4 <?php echo $this->lang->line('Weeks'); ?></span>
										<span class="weekJump" data-date="<?php echo date('Y-m-d',strtotime(date("Y-m-d",time())." +5 week")); ?>">In 5 <?php echo $this->lang->line('Weeks'); ?></span>
										<span class="weekJump" data-date="<?php echo date('Y-m-d',strtotime(date("Y-m-d",time())." +6 week")); ?>">In 6 <?php echo $this->lang->line('Weeks'); ?></span>
									</div>
                            	</div>
                          	</div>
                        </div>
                     </div>
<!--
                     <button class="btn widthfit" data-toggle="modal" data-target="#employee-not-available">for popup trigger</button>
-->
                </div>
            </div>
        </div>
 </div>
    </section>

       <!-- modal start in setup -->
    <div class="modal new-modal-full-page fade pr-0" id="setup_modal" >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <!-- <a href="#" class="crose-btn" data-dismiss="modal">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg') ?>" class="popup-crose-black-icon">
          </a> -->
          <div class="modal-body pt-3 mb-10 pl-25 pr-25 relative">
			   <?php $this->load->view('frontend/common/alert'); ?>
            <div class="bgwhite border-radius4 box-shadow2 relative mb-0 mt-0" id="addprofile_setupform">


             </div>

          </div>
        </div>
      </div>
    </div>
    <!-- modal end setup -->

<?php      $this->load->view('frontend/common/footer_script');
           $this->load->view('frontend/common/crop_pic');
           $this->load->view('frontend/common/editer_js');
		   if(!empty($_GET['setup'])) {
				$this->load->view('frontend/marchant/profile/profile_setup_js');
		   }
?>

 <div class="modal fade" id="employee-not-available">
     <div class="modal-dialog modal-md modal-dialog-centered" role="document">
       <div class="modal-content">
         <a href="#" class="crose-btn" data-dismiss="modal">
		 <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
         </a>

         <div class="modal-body pt-30 mb-30 pl-40 pr-40">
           <h3 class="font-size-18 fontfamily-medium color333 mb-3">
			 <?php echo $this->lang->line('block_time_heading'); ?>
           <p id="Showerr" class="error" ></p></h3>

           <form id="formUnvailablity">
             	<div class="row">
				  	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div class="form-group mb-3 v_date_time_picker relative" id="date_validate">
							<input type="text" class="height56v form-control" name="date" style="background-color: #fff !important;" id="dateunavailable" placeholder="Select Date" readonly>
							<div class="checkbox mt-1 mb-2 display-ib" style="margin-top:0.75rem !important;">
								<label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="allday" value="yes" id="allday">
						  			<span class="cr"><i class="cr-icon fa fa-check"></i></span>
						 			<?php echo $this->lang->line('all_day'); ?>
								</label><br>
								<label class="fontsize-12 fontfamily-regular color333" style="margin-top: 10px;"><input type="checkbox" name="allemaployee" value="yes" id="allemaployee">
						  			<span class="cr"><i class="cr-icon fa fa-check"></i></span>
									<?php echo $this->lang->line('All_staff'); ?>
								</label>
							</div>
						</div>
				  	</div>
				  	<input type="hidden" name="block_id" value="0" id="block_id_update">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div class="form-group form-group-mb-50">
							<div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn timeSelected height56v levelEmp" style="text-transform: none !important;"></button>
								<span class="label" id="levelEmp"><?php echo $this->lang->line('Select_Employee'); ?></span>
								<ul class="dropdown-menu mss_sl_btn_dm"
								style="max-height: none !important; overflow-x: auto !important; height: 100px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 40px, 0px);" x-placement="bottom-start">
									<?php if(!empty($employees)){  ?>
									<!-- <li class="radiobox-image">
										<input type="checkbox" id="idq_1all_block" name="uid" class="" value="all" checked><label for="idq_1all_block"><?php echo $this->lang->line('All_staff'); ?></label>
										</li>	-->

									<?php	foreach($employees as $emp){ ?>
									<li class="radiobox-image"><input type="checkbox" id="idq_1_block<?php echo $emp->id; ?>" name="uid[]" class="" data-text="<?php echo $emp->first_name." ".$emp->last_name; ?>" value="<?php echo url_encode($emp->id); ?>">
									<label for="idq_1_block<?php echo $emp->id; ?>"><?php echo $emp->first_name." ".$emp->last_name; ?></label></li>
									<?php } } ?>
								</ul>
							</div>
				     		<span id="Eerr" class="error"></span>
               			</div>
             		</div>
					<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group mb-2">
							<div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new" id="addstarttimedropdown"></div>
							<span id="Serrt" class="error"></span>
						</div>
               		</div>
					<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group mb-2">
							<div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new" id="addendtimedropdown"></div>
							<span id="Eerrt" class="error"></span>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="block_more_area">
						<div class="form-group mb-3 v_date_time_picker relative">
							<div class="checkbox mb-2 display-ib" style="margin-top:0.75rem !important;">
								<label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="block_more" value="yes" id="block_more">
						  			<span class="cr"><i class="cr-icon fa fa-check"></i></span>
									Gleichen Zeitraum auch in kommenden Wochen blockieren
								</label><br>
							</div>
						</div>
						<div class="form-group mb-3">
							<div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn timeSelected height56v block_more_period" disabled="disabled"></button>
								<span class="label" id="block_more_period">Wie oft?</span>
								<ul class="dropdown-menu mss_sl_btn_dm custome_scroll height200 over-flow-auto" style="max-height: none; overflow-x: auto !important; height: 200px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -158px, 0px);">
									<li class="radiobox-image">
										<input type="radio" id="block_more_period_1" name="block_more_period" class="block_more_period" data-text="Nächste Woche" value="1">
										<label for="block_more_period_1">Nächste Woche</label>
									</li>
									<li class="radiobox-image">
										<input type="radio" id="block_more_period_2" name="block_more_period" class="block_more_period" data-text="Nächste 2 Wochen" value="2">
										<label for="block_more_period_2">Nächste 2 Wochen</label>
									</li>
									<li class="radiobox-image">
										<input type="radio" id="block_more_period_3" name="block_more_period" class="block_more_period" data-text="Nächste 3 Wochen" value="3">
										<label for="block_more_period_3">Nächste 3 Wochen</label>
									</li>
									<li class="radiobox-image">
										<input type="radio" id="block_more_period_4" name="block_more_period" class="block_more_period" data-text="Nächste 4 Wochen" value="4">
										<label for="block_more_period_4">Nächste 4 Wochen</label>
									</li>
									<li class="radiobox-image">
										<input type="radio" id="block_more_period_5" name="block_more_period" class="block_more_period" data-text="Nächste 5 Wochen" value="5">
										<label for="block_more_period_5">Nächste 5 Wochen</label>
									</li>
									<li class="radiobox-image">
										<input type="radio" id="block_more_period_6" name="block_more_period" class="block_more_period" data-text="Nächste 6 Wochen" value="6">
										<label for="block_more_period_6">Nächste 6 Wochen</label>
									</li>
									<li class="radiobox-image">
										<input type="radio" id="block_more_period_7" name="block_more_period" class="block_more_period" data-text="Nächste 7 Wochen" value="7">
										<label for="block_more_period_7">Nächste 7 Wochen</label>
									</li>
									<li class="radiobox-image">
										<input type="radio" id="block_more_period_8" name="block_more_period" class="block_more_period" data-text="Nächste 8 Wochen" value="8">
										<label for="block_more_period_8">Nächste 8 Wochen</label>
									</li>
									<li class="radiobox-image">
										<input type="radio" id="block_more_period_specific" name="block_more_period" class="block_more_period" data-text="bestimmtes  Datum" value="0">
										<label for="block_more_period_specific">bestimmtes  Datum</label>
									</li>
								</ul>
							</div>
				     		<span id="Eerr" class="error"></span>
               			</div>
						<div class="form-group form-group-mb-50 display-n pick-disable" id="block_specific_date_wrap">
							<div class="pick-overlay"></div>
							<div class="form-froup vmb-40 v_date_time_picker relative">
                                <input type="text" style="height:56px; background-color:#fff;" name="block_specific_date" placeholder="Datum auswählen" class="height56v form-control" id="block_specific_date" readonly>
                                <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue">
								<span id="SerrBM" class="error"></span>
                            </div>
						</div>
				  	</div>
             		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
             			<div class="form-group mb-50" id="">
							<label class="inp v_inp_new height90v mt-3">
								<textarea class="form-control height90v custom_scroll" placeholder="&nbsp;" value="" name="block_note" id="block_note"></textarea>
								<span class="label"><?php echo $this->lang->line('block_enter_note'); ?></span>
							</label>
                 		<div>
					</div>
				</div>
             	<div class="text-center mt-15 col-12">
				 	<a id="blockdelet_url"><button type="button" style="background: #00949d; border-color: #00949d" class="btn btn-large widthfit blockdelet_url mr-4 display-n"><?php echo $this->lang->line('Delete'); ?></button></a>
               		<button type="button" id="submitUnvailablity" style="background: #00949d; border-color: #00949d" class=" btn btn-large widthfit"><?php echo $this->lang->line('Submit'); ?></button>
             	</div>
           </form>

         </div>
       </div>
     </div>
   </div>
</div>

<div class="modal fade" id="bookingDetailsCalender">
     <div class="modal-dialog modal-md modal-dialog-centered" role="document">
       <div class="modal-content">
         <a href="#" class="crose-btn" data-dismiss="modal">
		 <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
         </a>
         <div class="modal-body pt-20 pl-20">
           <h3 class="font-size-20 fontfamily-medium color333">Booking Details
             <div class="font-size-16 mt-20">
				 <div class="d-inline-flex booking-dtl-mdl-rw">
					 <div class="p-2 width130 booking-dtl-mdl-rw-lft relative fontsize-14">Time</div>
					 <div class="p-2 booking-dtl-mdl-rw-rgt fontsize-14" id="details_time"></div>
				 </div>
				 <div class="d-inline-flex booking-dtl-mdl-rw">
					 <div class="p-2 width130 booking-dtl-mdl-rw-lft relative fontsize-14">Employee</div>
					 <div class="p-2 fontsize-14" id="detail_employee"></div>
				 </div>
				 <div class="d-inline-flex booking-dtl-mdl-rw">
					 <div class="p-2 width130 booking-dtl-mdl-rw-lft relative fontsize-14">Service</div>
					 <div class="p-2 fontsize-14" id="details_service"></div>
				 </div>

				 </div>

			  </div>

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
            <img src="<?php echo base_url('assets/frontend/images/service_provider_membership_icon.png'); ?>">
            <!-- <h5 class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:3;"> -->
               <!-- <?php
               //echo $_SESSION['sty_membership']; ?> </h5> -->
                       <h5 class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:3;">
                       Dein Abonnement ist abgelaufen,
                       Sichere dir jetzt eine neue <a href="<?php echo base_url('membership')?>"  style="color:#00A8B2">Mitgliedschaft!</a></h5>

            <a href="<?php echo base_url('membership')?>"  class=" btn btn-large widthfit display-ib mr-4" > zu unseren Mitgliedschaften</a>
          </div>
        </div>
      </div>
    </div>

	<div class="modal fade" id="close_time_modal">
		<div class="modal-dialog modal-md modal-dialog-centered" role="document">
			<div class="modal-content">
			<a href="#" class="crose-btn" data-dismiss="modal">
				<picture class="popup-crose-black-icon">
					<source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
					<source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
					<img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
				</picture>
			</a>
			<div class="modal-body pt-5 mb-10 pl-25 pr-25 relative text-center">
				<p class="color666 fontsize-16">Innerhalb der Ferien oder an gesetzlichen Feiertagen <br/> können keine Buchungen erstellt werden. <br/><a href="<?php echo base_url('merchant/closed_date'); ?>" class="a_hover_orange colororange text-underline">Hier klicken</a>, um Ferien oder Feiertage zu bearbeiten.</p>
			</div>
			</div>
  		</div>
	</div>

<?php /* $pinstatus = get_pin_status($this->session->userdata('st_userid'));
//echo current_url().'=='.$_SERVER['HTTP_REFERER'];
    if($pinstatus->pinstatus=='on'){
        $this->load->view('frontend/marchant/profile/ask_pin_popup');
      } */
  ?>




<script src='<?php echo base_url('assets/frontend/js/fullcalender/moment.min.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/fullcalender/fullcalendar.min.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/fullcalender/locale-all.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/fullcalender/scheduler.min.js'); ?>'></script>


<script src='<?php echo base_url('assets/frontend/js/tooltip.min.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/pignose.calender.js'); ?>'></script>
<script type="text/javascript" src="<?php echo base_url('assets/cropp/croppie.js'); ?>"></script>

<script>
	$(document).on('click','.showmoreText4',function(){
		//alert($(this).hasClass(' showText'));
        if($(".showmorePara4").hasClass('showText4')==false){
          $('.showmorePara4').addClass('showText4');
        }
        else{
          $('.showmorePara4').removeClass('showText4');
        }

        $(this).text(($(".showmorePara4").hasClass('showText4')==false) ? 'weniger anzeigen' : 'mehr anzeigen').fadeIn();
    });
	$(document).on('click','.showmoreText3',function(){
		//alert($(this).hasClass(' showText'));
        if($(".showmorePara3").hasClass('showText3')==false){
          $('.showmorePara3').addClass('showText3');
        }
        else{
          $('.showmorePara3').removeClass('showText3');
        }

        $(this).text(($(".showmorePara3").hasClass('showText3')==false) ? 'weniger anzeigen' : 'mehr anzeigen').fadeIn();
    });
function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
	localStorage.setItem("STATUS", "LOGGED_IN");
	$(document).ready(function(){

	$(document).on('click','.fc-agendaDay-button',function(){
	//alert('TAG');
     //$('.fc-day fc-widget-content fc-sat').removeClass('fc-today');
     $('.fc-day').removeClass('fc-today');
    });

	$(document).on('click','.fc-agendaThreeDay-button',function(){
	//alert('TAG');
     //$('.fc-day fc-widget-content fc-sat').removeClass('fc-today');
     $('.fc-day').removeClass('fc-today');
    });

	// $('.calender-droupdown').change(function(){
	// 	//alert('IN');
    //     $('.fc-day').removeClass('fc-today');
	// });


    var businesshours = '<?php if(!empty($businesshours)) echo json_encode($businesshours); ?>';
    var businessHoursres = JSON.parse(businesshours);

	  var setup = "<?php if(!empty($_GET['setup'])) echo $_GET['setup']; ?>";
	  if(setup !=undefined && setup !=""){
	  $('#setup_modal').modal({backdrop: 'static', keyboard: false});
      $("#setup_modal").modal('show');
       if(setup=='workinghour')
      {
			 getTimehtml();
		  }
      else if(setup=='gallery'){
        getGelleryhtml();
      }
	   else if(setup=='employee'){
		   getAddEmployeehtml();
		   }
	  else if(setup=='service'){
		   getAddServicehtml();
		   }
       else{
          getProfilehtml();
	    }
     }

    // $('.weekly-calender').addClass('display-n');

	var date = new Date();
    var today =new Date(date.getFullYear(), date.getMonth(), date.getDate());

    var sixyears = new Date("<?php echo date('Y-m-d H:i:s',strtotime('+3 months')); ?>");
    var twomonths =new Date(sixyears.getFullYear(), sixyears.getMonth(), sixyears.getDate());
    //alert(today+"="+twomonths);

	let days = [0,1,2,3,4,5,6];
	days.splice(date.getDay(), 1);

	var blockSpecificDate;
	var blockSpecificDate = $("#block_specific_date").datepicker({
		uiLibrary: 'bootstrap4',
		locale: 'de-de',
		format:"dd.mm.yyyy",
		minDate:today,
		disableDaysOfWeek: days,
	});

	  $('#dateunavailable').datepicker({
         locale: 'de-de',
         uiLibrary: 'bootstrap4',
         minDate:today,
         maxDate:twomonths,
		 weekStartDay: 1,
         format:"dd.mm.yyyy",
         monthNames: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
         dayNamesMin:[ 'SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
		 change: function (e) {
			var selectdate=$(this).val();
			var startsamay1="";
			var startsamay="";
			var endsamay1="";
			var endsamay="";
			$.ajax({
				type: "POST",
				url:base_url+"rebook/getopeninghours_forblock",
				data:'date='+selectdate,
				success: function (response) {
					unloading();
					var obj = $.parseJSON( response );
						if(obj.success==1){
							$("#addstarttimedropdown").html(obj.starttime);
							$("#addendtimedropdown").html(obj.endtime);

							//$("#employee-not-available").modal('show');
							$("#selectTypeOption").val("book");
							var uiserid= $("input[name='id']:checked").val();
							if(uiserid==undefined){
								$("#userid").val('all');
								}
							else{
								$("#userid").val(uiserid);
								}

							if($("#allday").val() !=undefined){
								$(".checkFirstSlot").prop("checked",true);
								$(".checkLastSlot").prop("checked",true);

								}

								//alert("#idstart_"+startsamay+"=="+"#id_"+endsamay)
						//$("#idstart_"+startsamay1).prop("checked",true);
						//$("#id_"+endsamay1).prop("checked",true);
						$(".levelEmp").attr('disabled',false);
						$("#blockdelet_url").attr('href',"");
						// $("#block_id_update").val('0');
						$(".blockdelet_url").addClass('display-n');
						$("#dateunavailable").val(selectdate);

						const dateParts = selectdate.split(".");
						const cur_day = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]).getDay();
						let days = [0,1,2,3,4,5,6];
						days.splice(cur_day, 1);
						if (blockSpecificDate) {
							blockSpecificDate.destroy();
						}
						blockSpecificDate = $("#block_specific_date").datepicker({
							uiLibrary: 'bootstrap4',
							locale: 'de-de',
							format:"dd.mm.yyyy",
							minDate:today,
							disableDaysOfWeek: days,
							value: selectdate,
						});

						$("#levelEnd").addClass('label_add_top');
						$("#levelStart").addClass('label_add_top');
						$(".levelEnd").text($("input[name='endtime']:checked").attr('data-val'));
						$(".levelStart").text($("input[name='starttime']:checked").attr('data-val'));
						//$(".sticky-calender-top").removeClass('fixed-top-add');



						}else{
							Swal.fire({
								title: 'Der Salon ist an der ausgewählten Zeit bereits geschlossen',
								width: 600,
								padding: '3em'
								});
							return false;
						}

				}
			});
         }
     });

	  $('.saleEmployee').change(function(){
		  $("#employeeForm").submit();
		  });


	const weekday1 = [ 'Sonntag', 'Tontag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
	const weekday2 = [ 'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];

	const weekday = $(window).width() >= 768 ? weekday1 : weekday2;

	$('#calendar').fullCalendar({

		plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
		header: {
			left: 'prev,today, title next ',
			//center: 'title',
			right: 'agendaWeek, agendaThreeDay, agendaDay'
		},
		locale:'de',
		timezone:'Asia/Kolkata',
		//timezone:'Europe/Madrid',
		monthNames: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
		monthNamesShort: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
		dayNames:  weekday,
		dayNamesMin: weekday,
		dayNamesShort: weekday,
		buttonText: {
			today: 'HEUTE',
			week: 'WOCHE',
			threeDay: '3 TAGE',
			day: 'TAG',			
		},
		titleFormat:'DD. MMMM YYYY',
		selectable:true,
		editable: true,
		slotEventOverlap:false,
		nowIndicator:true,
		eventDurationEditable:false,
		minTime:'<?php if(!empty($minmaxtime->mintime))	echo $minmaxtime->mintime; else echo "00:00:00"; ?>',
		maxTime:'<?php if(!empty($minmaxtime->maxtime))	echo $minmaxtime->maxtime; else echo "23:00:00"; ?>',
		columnFormat:'                ',
		allDaySlot:false,
		eventLimit: true,
		eventColor:true, // allow "more" link when too many events
		displayEventEnd: true,
		slotDuration:'00:15:00',
		slotLabelInterval : '01:00:00',
		longPressDelay:500,
		businessHours:businessHoursres.filter((el) => el.end != '00:00:00'),
		selectConstraint:'businessHours',
		eventConstraint:'businessHours',
		dayRender: function( date, cell) {
			cell.addTouch();
		},
		views: {
			agendaThreeDay: {
				type: 'agenda',
				duration: { days: 3 },
				buttonText: '3 TAGE',
				// views that are more than a day will NOT do this behavior by default
				// so, we need to explicitly enable it
				groupByResource: true

				//// uncomment this line to group by day FIRST with resources underneath
				//groupByDateAndResource: true
			}
		},
		resources: {
			url: base_url+'merchant/getResource',
			type: 'POST',
			data: {id: '<?php if(!empty($_GET["id"])) echo $_GET["id"]; ?>',
				}
		},
		resourceRender: function(resourceObj, $th) {
			$th.prepend(
				$('<img src="'+resourceObj.imgurl+'" class="mr-3 width30 border-radius50">').popover()
			);
		},
		defaultView: '<?php if(!empty($_GET["view"])){ echo $_GET["view"]; } else{ if($mdetails->calendar_view=="day") echo "agendaDay"; else { if($mdetails->calendar_view=="week") echo "agendaWeek"; else echo "agendaThreeDay"; }} ?>',
		viewRender: renderViewColumns,
		events: {
			url: base_url+'merchant/booking',
			type: 'POST',
			data: {id: '<?php if(!empty($_GET["id"])) echo $_GET["id"]; ?>',
			}
		},
		timeFormat:'H:mm',
		slotLabelFormat: [
			'HH:mm'
		],
		eventDrop: function(event,dayDelta,revertFunc) {
			if(event.blocked!=0){
				//alert(event.start+"=="+event.end);
				Swal.fire({
					title: '<?php echo $this->lang->line("are_you_sure"); ?>',
					text: "Du möchtest diesen blockierten Zeitraum verlegen?",
					type: 'warning',
					showCancelButton: true,
					reverseButtons:true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Bestätigen'
				}).then((result) => {
					if (result.value) {

						loading();

						$.ajax({
							type: "POST",
							url:base_url+"merchant/blocktime_reshedule",
							data:'blocked_id=' + event.blocked + '&new_time=' + dayDelta + '&start=' + event.start + '&end=' + event.end,
							success: function (response) {
								unloading();
								var obj = $.parseJSON( response );
								if(obj.success==1){

										Swal.fire(
											'Blockierter Zeitraum verlegt',
											'Der blockierte Zeitraum wurde erfolgreich verlegt.',
											'success'
										);

										//$(this).removeClass("unblock");
										//$(this).addClass("partialblock");
								}else{
									revertFunc();
									Swal.fire({
										title: obj.msg,
										text: "",
										type: 'warning',
									});
									//alert(obj.msg);
									//$(this).addClass("unblock");
									//$(this).removeClass("partialblock");
								}

							}
						});


					} else {
						revertFunc();
					}
				});
			} else {

				var startsamay=moment(event._start._i).format('YYYY-MM-DD');
				//var today=moment(new Date()).utcOffset('+0530').format('YYYY-MM-DD');
				var today=moment(new Date()).format('YYYY-MM-DD');
				//alert(startsamay+"=="+today);
				if(startsamay < today)
				{
					//Swal.fire('You cannot reschedule a booking. That booking time has passed');
					Swal.fire({
						title: 'Eine bereits vergangene Buchung kann nicht verlegt werden.',
						width: 600,
						padding: '3em'
					});
					revertFunc();
					return false;
				} else {
					Swal.fire({
						title: '<?php echo $this->lang->line("are_you_sure"); ?>',
						text: "<?php echo $this->lang->line("you_want_reschedule"); ?>",
						type: 'warning',
						showCancelButton: true,
						reverseButtons:true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: '<?php echo $this->lang->line("Submit"); ?>'
					}).then((result) => {
						if (result.value) {

							loading();

							$.ajax({
								type: "POST",
								url:base_url+"merchant/booking_reshedule_from_calender",
								data:'reSchedule_id=' + event.id + '&new_time=' + dayDelta,
								success: function (response) {
									unloading();
									var obj = $.parseJSON( response );
									if(obj.success==1){

										Swal.fire(
											'Buchung erfolgreich verlegt',
											obj.msg,
											'success'
										).then((result) => {
											$('#calendar').fullCalendar('refetchEvents');
										});
									} else {
										revertFunc();
										Swal.fire(
											'',
											obj.msg,
											''
										);
									}
								}
							});

						} else {
							revertFunc();
						}
					});


				}
			}
		},
		dateClick: function(info) {},
		selectAllow: function(start) {
			//alert('df');
			var selectTypeOption=$("#selectTypeOption").val();

			if(selectTypeOption=="book"){
				if(window.glb_expired) {
					$("#membership_exp_onlinesetup").modal("show");
					return false;
				}
				// console.log('fg');
				// var asiaTime = new Date(start.start._d).toLocaleString({timeZone: "UTC -5:30"});
				var startsamay=moment(start.start._d).utcOffset('+0000').format('YYYY-MM-DD');
				//var endsamay=moment(start.start._d).utcOffset('+0000').format('YYYY-MM-DD HH:mm');
				//alert(endsamay);
				//var today=moment(new Date()).utcOffset('+0530').format('YYYY-MM-DD HH:mm');
				var today=moment(new Date()).format('YYYY-MM-DD');
				//alert(startsamay+"=="+today);
				if(startsamay < today)
				{
					Swal.fire({
						title: 'Du kannst keine Buchungen in der Vergangenheit erstellen.',
						width: 600,
						padding: '3em'
					});
					return false;
				}
				else
				{
					var startdate=moment(start.start._d).utcOffset('+0000').format('YYYY-MM-DD');
					var starttime=moment(start.start._d).utcOffset('+0000').format('HH:mm');
					//alert(starttime);
					location.href=base_url+"booking/new_booking?date="+startdate+"&time="+starttime;
				}
			}
			else if(selectTypeOption=="rebook"){

				// var asiaTime = new Date(start.start._d).toLocaleString({timeZone: "UTC -5:30"});
				var startsamay = moment(start.start._d).utcOffset('+0000').format('YYYY-MM-DD HH:mm');
				//var endsamay = moment(start.start._d).utcOffset('+0000').format('YYYY-MM-DD HH:mm');
				//alert(endsamay);
				//var today=moment(new Date()).utcOffset('+0530').format('YYYY-MM-DD HH:mm');
				var today=moment(new Date()).format('YYYY-MM-DD HH:mm');
				if(startsamay <= today)
				{
					Swal.fire({
						title: 'Du kannst keine Buchungen in der Vergangenheit erstellen.',
						width: 600,
						padding: '3em'
					});
					return false;
				}
				else
				{
					var bookid = "<?php if(!empty($this->uri->segment(3))) echo $this->uri->segment(3); ?>"

					loading();
					$.ajax({
						type: "POST",
						url:base_url+"rebook/rebooking",
						data:'bookid='+bookid+'&datetime='+startsamay,
						success: function (response) {
							unloading();
							var obj = $.parseJSON( response );
							if(obj.success==1){
								$(".sticky-calender-top").removeClass('fixed-top-add');
								$("#selectTypeOption").val('book');
								Swal.fire( 'Buchung erfolgreich',
									obj.msg,
									'success'
								).then((result) => {
									$('#calendar').fullCalendar('refetchEvents');
									// window.location = base_url+"merchant/dashboard";
								});
								$(".swal2-confirm.swal2-styled").addClass('rebookedOk');
								$('#calendar').fullCalendar('refetchEvents');
							}else{
								Swal.fire(
									'Zeitüberschneidung',
									obj.msg,
									'warning'
								);
							}
						}
					});
				}
			}
		},
		select: function(start,end){
			//alert('d');
			var selectdate     = moment(start._d).utcOffset('+0000').format('DD-MM-YYYY');
			var selectdatetime = moment(start._d).utcOffset('+0000').format('YYYY-MM-DD');

			//var today          = moment(new Date()).utcOffset('+0530').format('YYYY-MM-DD HH:mm');
			var today=moment(new Date()).format('YYYY-MM-DD');

			if(selectdatetime < today)
			{
				Swal.fire({
					title: 'You cannot blocked a past time.',
					width: 600,
					padding: '3em'
				});
				return false;
			}

			var startsamay  = moment(start._d).utcOffset('+0000').format('HH:mm');
			var endsamay    = moment(end._d).utcOffset('+0000').format('HH:mm');
			var startsamay1 = moment(start._d).utcOffset('+0000').format('HH-mm');
			var endsamay1   = moment(end._d).utcOffset('+0000').format('HH-mm');



			if($("#selectTypeOption").val()=="block"){
				//loading();
				$.ajax({
					type: "POST",
					url:base_url+"rebook/getopeninghours_forblock",
					data:'date='+selectdate,
					success: function (response) {
						unloading();
						var obj = $.parseJSON( response );
						if(obj.success==1){
							$("#addstarttimedropdown").html(obj.starttime);
							$("#addendtimedropdown").html(obj.endtime);

							$("#employee-not-available").modal('show');
							$("#selectTypeOption").val("book");
							var uiserid= $("input[name='id']:checked").val();
							if(uiserid==undefined){
								$("#userid").val('all');
							}
							else{
								$("#userid").val(uiserid);
							}
							//alert("#idstart_"+startsamay+"=="+"#id_"+endsamay)
							$("#idstart_"+startsamay1).prop("checked",true);
							$("#id_"+endsamay1).prop("checked",true);
							$(".levelEmp").attr('disabled',false);
							$("#block_note").val("");
							$("#blockdelet_url").attr('href',"");
							$("#block_id_update").val('0');
							$(".blockdelet_url").addClass('display-n');
							$("#dateunavailable").val(selectdate);
							$("#levelEnd").addClass('label_add_top');
							$("#levelStart").addClass('label_add_top');
							$(".levelEnd").text(endsamay);
							$(".levelStart").text(startsamay);
							$(".sticky-calender-top").removeClass('fixed-top-add');



						}else{
							Swal.fire({
								title: 'Salon not open for selected day.',
								width: 600,
								padding: '3em'
							});
							return false;
						}

					}
				});

				//alert(uiserid);
			}
			//console.log(start);
			//console.log(end);
			//alert('selected ' +startsamay+ ' to ' +endsamay);
		},
		eventLeave: function() {
			console.log(3333333333);
		},
		eventMouseover: function(calEvent, jsEvent) {

			if(calEvent.blocked_type=='close'){
				var starttime  = moment(calEvent.start._d).utcOffset('+0000').format('dd-mm-yyyy HH:mm');
				var endtime    = moment(calEvent.end._d).utcOffset('+0000').format('dd-mm-yyyy HH:mm');
				var notes  = "";
				console.log("tooltipevent-------------",notes);
				if(calEvent.notes){
					notes  = '<p class="fontfamily-medium font-size-12 mb-1"><span class="color333">Beschreibung</span> : '+calEvent.notes+'</p>';

					var tooltip = '<div class="tooltipevent" style="background:#fff;position:absolute;z-index:10001;overflow: visible;">\
										<div class="confirm-popup-cnt relative">\
										<span class="after-box-roted"></span>\
											<div class="relative pr-3 pl-3">\
												<div class="row">\
													<div class="col-12 col-sm-12 vertical-middle p-0">\
													<p class="font-size-14 fontfamily-regular color333 mb-0">'+notes+'</p></div>\
													</div>\
										</div>\
									</div>\
								</div>';
				}else{
					return false;
				}
			}
			else{
				if($(this).hasClass('fc-short')){
					var shortClass=45;
				}
				else var shortClass=0;

				var width=$(this).css('inset');
				var starttime  = moment(calEvent.start._d).utcOffset('+0000').format('HH:mm');
				var endtime    = moment(calEvent.end._d).utcOffset('+0000').format('HH:mm');
				var timeStart  = new Date(calEvent.start._d).getHours();
				var timeEnd    = new Date(calEvent.end._d).getHours();

				var num      = calEvent.totaltime;
				var hours    = (num / 60);
				var rhours   = Math.floor(hours);
				var minutes  = (hours - rhours) * 60;
				var rminutes = Math.round(minutes);

				var totime  = "";
				if(rhours!=0){
					totime  = rhours+"h";
				}
				if(rminutes!=0){
					totime  = totime+" "+rminutes+"min";
				}
				var notes  = "";
				if(calEvent.notes){
					notes = '<div class="relative border-b pt-2 pb-2"><p class="color333 fontfamily-medium font-size-12 pb-2 mb-0" style="text-decoration:underline">Buchungsnotiz:</p><div class="font-size-12 fontfamily-medium relative showmorePara3'+(calEvent.notes.length>150?'':'paraSmall')+'" style="">'+nl2br(calEvent.notes)+'</div></div>';
				}

				var unotes  = "";
				if(calEvent.unotes){
					unotes = '<div class="relative border-b pt-2 pb-2"><p class="color333 fontfamily-medium font-size-12 pb-2 mb-0" style="text-decoration:underline"><?php echo $this->lang->line('Customer_note'); ?>:</p><div class="font-size-12 fontfamily-medium relative showmorePara4 '+(calEvent.unotes.length>150?'':'paraSmall')+'" style="">'+nl2br(calEvent.unotes)+'</div></div>';
				}

				var boostatus = "";
				if(calEvent.status=='completed'){
					boostatus = '<p class="pt-1 -medium font-size-14 mb-1"><span class="color333">Status </span> :<span style="color:#00949d"> Abgeschlossen</span> <img src="<?php echo base_url('assets/frontend/images/completed-booking-icon.svg'); ?>" class="width20" style="margin-top:-4px;" alt="" /></p>';
				}
				if(calEvent.status=='no-show'){
					boostatus = '<p class="pt-2 fontfamily-medium font-size-14 mb-1"><span class="color333">Status </span> :<span style="color:#dc3545"> Nicht erschienen</span> <img src="<?php echo base_url('assets/frontend/images/eye-icon-new.png'); ?>" class="width20" style="margin-top:-4px;" alt="" /></p>';
				}
				if(calEvent.status=='confirmed'){
					boostatus = '<p class="pt-2 fontfamily-medium font-size-14 mb-1"><span class="color333">Status </span> :<span style="color:#4BB543;"> Bestätigt</span> <img src="<?php echo base_url('assets/frontend/images/comfirm-booking-new-green-checked-icon.svg'); ?>" class="" style="margin-top:-2px;width:18px;" alt="" /></p>';
				}
				console.log("notes+unotes+boostatus",notes+unotes+boostatus)
				if(calEvent.title!=''){
					var tooltip = '<div class="tooltipevent" style="background:#fff;position:absolute;z-index:10001;overflow: visible;border:0px solid transparent;box-shadow:rgba(16, 25, 40, 0.16) 0px 16px 32px 0px;">\
								<div class="confirm-popup-cnt relative">\
								<span class="after-box-roted"></span>\
									<div class="relative pr-3 pl-3">\
										<div class="row border-b pb-2">\
												<div class="col-3 col-sm-2 p-0">\
													<img src="'+calEvent.image_url+'" style="" class="width50v border-radius50">\
												</div>\
												<div class="col-9 col-sm-10">\
														<p class="font-size-16 fontfamily-regular color333 mb-0 mt-2" style="margin-left:-10px;"><span class="mt-2 text-transform: capitalize;">'+calEvent.userName+'</span><span class="font-size-14 color333 fontfamily-medium display-ib float-right">'+calEvent.abcheck+' € '+calEvent.totalprice+'</span></p>\<span class="font-size-14 float-left fontfamily-medium color666" style="margin-left:-10px;">Mitarbeiter : '+calEvent.title+', '+totime+'</span>\
														</div>\
													</div>\
										<div class="row pt-2">\
											<div class="col-12 col-sm-12 vertical-middle p-0">\
												<p class="font-size-14 fontfamily-regular color666 mb-1">'+starttime+' - '+endtime+' Uhr</p>\
												<p class="fontfamily-medium color333 font-size-14 pb-2 mb-0 border-b">'+calEvent.serviceName+'</p>\
												\
											'+notes+unotes+boostatus+'</div>\
											<div class="col-4 col-sm-3 text-right pl-0 vertical-middle p-0">\
												\
											</div>\
											</div>\
									</div>\
								</div>\
							</div>\
						</div>';
				}
				else{
					var notes  = "";
					if(calEvent.notes){
						notes  = '<p class="fontfamily-medium font-size-12 mb-1"><span class="color333">Beschreibung</span> : '+calEvent.notes+'</p>';
					}
					var tooltip = '<div class="tooltipevent" style="background:#fff;position:absolute;z-index:10001;overflow: visible;">\
								<div class="confirm-popup-cnt relative">\
								<span class="after-box-roted"></span>\
									<div class="relative pr-3 pl-3">\
										<div class="row border-b pb-2">\
												<div class="col-3 col-sm-2 p-0">\
													<img src="'+calEvent.image_url+'" style="" class="width50v border-radius50">\
												</div>\
												<div class="col-9 col-sm-10">\
														<p class="font-size-16 fontfamily-regular color333 mb-0 mt-3" style="margin-left:-10px;text-transform: capitalize;">'+starttime+' - '+endtime+'</p>\
														</div>\
													</div>\
													<div class="row pt-2">\
											<div class="col-8 col-sm-8 vertical-middle p-0">\
											<p class="font-size-14 fontfamily-regular color333 mb-0">'+notes+'</p></div>\
											</div>\
									</div>\
									</div>\
								</div>\
							</div>\
						</div>';
				}
			}
			$("body").append(tooltip);
			var windowHeght = $(window).height();
			var windowWidth = $(window).width();
			var thiswidth = $(this).width();
			$(this).mouseover(function(e) {
				if (e.pageX > 800) {
					$(this).css('z-index', 10000);
					$('.tooltipevent').fadeIn('500');
					$('.tooltipevent').fadeTo('10', 1.9);
				}
			}).mousemove(function(e) {
				if (e.pageX > 800) {
					//~ $('.tooltipevent').css('top', e.pageY - 90);
					//console.log(1);
					var toolhight = $('.tooltipevent').height();
					var x = $("a.cursor_hover").offset();
					var yjheight = $("a.cursor_hover").outerHeight();
					var yleft = $("a.cursor_hover").css('left');
					var res = yleft.split("p");
					var yright = $("a.cursor_hover").css('right');
					var res1 = yright.split("p");
					var ttaolleft = Number(res[0])+Number(res1[0]);
					var ewidthpx = $("a.cursor_hover").css('width');
					var ewidth  = ewidthpx.split("p")[0];
					var yleft1 = $("a.cursor_hover")[0].style.left;
					var yright1 = $("a.cursor_hover")[0].style.right;
					// console.log(yleft1+"="+yright1);
					//console.log("1="+x.top+" | "+$(window).height()+"=="+yjheight);
					//console.log($("a.cursor_hover"));
					//console.log(yjheight-38);

					if (x.top>500){

						if (x.left>900){
							//alert(x.top+"="+yjheight);
							var notePretag_hieght = $('.notePretag').height();
							if(notePretag_hieght==undefined){
								notePretag_hieght=0;
							}
							console.log('1');
							$('.tooltipevent .after-box-roted').removeClass('bottom');
							$('.tooltipevent .after-box-roted').removeClass('top');
							$('.tooltipevent .after-box-roted').removeClass('left');
							$('.tooltipevent .after-box-roted').addClass('right');
							$('.tooltipevent').css('top', x.top +(yjheight/2)-88-(notePretag_hieght/2));
							$('.tooltipevent').css('left', x.left-408);

						} else {
							console.log('2');
							//alert(x.top+"=="+yjheight);
							$('.tooltipevent .after-box-roted').removeClass('left');
							$('.tooltipevent .after-box-roted').removeClass('right');
							$('.tooltipevent .after-box-roted').removeClass('top');
							$('.tooltipevent .after-box-roted').addClass('bottom');
							$('.tooltipevent').css('top', x.top-toolhight-30);
							$('.tooltipevent').css('left', x.left -100-shortClass-ttaolleft);
						}

					} else {

						//alert(x.left);
						if(x.left>900) {
							var notePretag_hieght = $('.notePretag').height();
							if(notePretag_hieght==undefined){
								notePretag_hieght=0;
							}
							//console.log(notePretag_hieght);
							var etop =x.top +(yjheight/2)-80-(notePretag_hieght/2);
							if(etop<200){
								etop = 200
							}
							else if(etop>1000){
								etop=500;
							}

							console.log('3');
							$('.tooltipevent .after-box-roted').removeClass('bottom');
							$('.tooltipevent .after-box-roted').removeClass('top');
							$('.tooltipevent .after-box-roted').removeClass('left');
							$('.tooltipevent .after-box-roted').addClass('right');
							$('.tooltipevent').css('top', etop);
							$('.tooltipevent').css('left', x.left-408);

						}
						else {
							console.log('4');
							$('.tooltipevent .after-box-roted').removeClass('bottom');
							$('.tooltipevent .after-box-roted').removeClass('left');
							$('.tooltipevent .after-box-roted').removeClass('right');
							$('.tooltipevent .after-box-roted').addClass('top');
							$('.tooltipevent').css('top', x.top + 50 + (yjheight-38));
							$('.tooltipevent').css('left', x.left-100-shortClass-ttaolleft);
						}
					}
				}
			});
			$(this).mouseover(function(e) {
				//console.log(width);
				if(e.pageX < 800){
					$(this).css('z-index', 10000);
					$('.tooltipevent').fadeIn('500');
					$('.tooltipevent').fadeTo('10', 1.9);
				}
			}).mousemove(function(e) {
				if(e.pageX < 800){
					var x = $("a.cursor_hover").offset();
					var yjheight = $("a.cursor_hover").outerHeight();
					var toolhight = $('.tooltipevent').height();
					var yleft = $("a.cursor_hover").css('left');
					var yleft1 = $("a.cursor_hover")[0].style.left;
					var res = yleft.split("p");
					var yright = $("a.cursor_hover").css('right');
					var yright2 = $("a.cursor_hover")[0].style.right;
					var res1 = yright.split("p");
					var ttaolleft = Number(res[0])+Number(res1[0]);

					var ewidthpx = $("a.cursor_hover").css('width');
					var ewidth  = ewidthpx.split("p")[0];
					console.log(yleft1+"="+yright2);

					var notePretag_hieght = $('.notePretag').height();
					if(notePretag_hieght==undefined){
						notePretag_hieght=0;
					}

					if(x.top>=500){

						if(x.left<400 && windowWidth>=700 && thiswidth<400)
						{
							var addLeft=thiswidth+17;
							console.log('5');
							$('.tooltipevent .after-box-roted').removeClass('bottom');
							$('.tooltipevent .after-box-roted').removeClass('top');
							$('.tooltipevent .after-box-roted').removeClass('right');
							$('.tooltipevent .after-box-roted').addClass('left');
								$('.tooltipevent').css('top', x.top +(yjheight/2)-88-(notePretag_hieght/2));
							$('.tooltipevent').css('left', x.left+addLeft);

						} else {
							console.log('6');
							$('.tooltipevent .after-box-roted').removeClass('top');
							$('.tooltipevent .after-box-roted').removeClass('left');
							$('.tooltipevent .after-box-roted').removeClass('right');
							$('.tooltipevent .after-box-roted').addClass('bottom');

							$('.tooltipevent').css('top', x.top-toolhight-30);
							$('.tooltipevent').css('left', x.left - 100-shortClass-ttaolleft);
						}
					}else{

						if(x.left<400 && windowWidth>=700 && thiswidth<400)
						{
							var addLeft=thiswidth+17;

							var etop = x.top +(yjheight/2)-88-(notePretag_hieght/2);
							if(etop<200){
								etop = 200
							}
							else if(etop>1000){
								etop=$(window).height()-100;
							}

							console.log(etop);

							console.log('7');
							$('.tooltipevent .after-box-roted').removeClass('bottom');
							$('.tooltipevent .after-box-roted').removeClass('top');
							$('.tooltipevent .after-box-roted').removeClass('right');
							$('.tooltipevent .after-box-roted').addClass('left');
							//$('.tooltipevent').css('top', x.top + ((yjheight/2)-110));
							$('.tooltipevent').css('top',etop);
							$('.tooltipevent').css('left', x.left+addLeft);

						}else{
							console.log('8');
								$('.tooltipevent .after-box-roted').removeClass('bottom');
								$('.tooltipevent .after-box-roted').removeClass('left');
								$('.tooltipevent .after-box-roted').removeClass('right');
								$('.tooltipevent .after-box-roted').addClass('top');
								$('.tooltipevent').css('top', x.top + 50 + (yjheight-38));
								$('.tooltipevent').css('left', x.left - 100-shortClass-ttaolleft);
						}

					}
							//console.log(x.top);

				}
			});
		},
		eventClick: function(calEvent) {
			if(calEvent.blocked!=0){
				if(calEvent.blocked_type=='close'){
					$("#close_time_modal").modal('show');
					return false;
				}


				loading();

				var selectdate  = moment(calEvent.start._d).utcOffset('+0000').format('DD.MM.YYYY');
				var startsamay  = moment(calEvent.start._d).utcOffset('+0000').format('HH:mm');
				var endsamay    = moment(calEvent.end._d).utcOffset('+0000').format('HH:mm');
				var startsamay1 = moment(calEvent.start._d).utcOffset('+0000').format('HH-mm');
				var endsamay1   = moment(calEvent.end._d).utcOffset('+0000').format('HH-mm');

				$.ajax({
					type: "POST",
					url:base_url+"merchant/get_blocktime_details",
					data:'block_id=' + calEvent.blocked+'&date='+selectdate,
					success: function (response) {
						unloading();
						var obj=$.parseJSON(response);
						if(obj.success==1){

							$("#addstarttimedropdown").html(obj.starttime);
							$("#addendtimedropdown").html(obj.endtime);



							if(obj.blocked_type=="full"){
								$("#allday").prop('checked',true);
								$(".levelStart").attr('disabled',true);
								$(".levelEnd").attr('disabled',true);
							}


							$("#levelEmp").addClass('label_add_top');
							$("#block_note").val(calEvent.notes);
							if(obj.empcheck=='yes'){
								$(".levelEmp").text(obj.employee_txt);
								// $(".levelEmp").text(employee_txt);
								//$(".levelEmp").text(employee_txt);
								$("#allemaployee").prop("checked",true);
								$(".levelEmp").attr('disabled',true);
							}
							else{
								$("#allemaployee").prop("checked",false);
								$(".levelEmp").text(obj.employee_txt);
								$(".levelEmp").attr('disabled',false);
							}
							$("input[name='uid[]']").each(function () {
								if (obj.emp_ids) {
									if (obj.emp_ids.includes($(this).val())){
										$(this).prop('checked', true);
									} else {
										$(this).prop('checked', false);
									}
								} else {
									$(this).prop('checked', false);
								}
							});

							//   $("#allemaployee").attr("disabled",true);
							$("#blockdelet_url").attr('href',base_url+"merchant/blockdelete/"+calEvent.blocked);
							$(".blockdelet_url").removeClass('display-n');
							$("#block_more_area").css("display", "none");
							$("#employee-not-available").modal('show');
							$("#idstart_"+startsamay1).prop("checked",true);
							$("#id_"+endsamay1).prop("checked",true);
							$("#dateunavailable").val(selectdate);
							$("#block_id_update").val(calEvent.blocked);
							$("#levelEnd").addClass('label_add_top');
							$("#levelStart").addClass('label_add_top');
							$(".levelEnd").text(endsamay);
							$(".levelStart").text(startsamay);
						}else{
							return false;
						}

					}
				});


			}
			else{

				//console.log(calEvent.id);
				getBokkingDetail(calEvent.detail_url,"calendar");
				//window.location.href = calEvent.detail_url;
			}
		},
		eventMouseout: function(calEvent, jsEvent) {

			$(this).css('z-index', 8);
			$('.tooltipevent').remove();

		},

  	});

	// redirect calendar on selected block slot deleted date after form submit
	var block_slot_delete_date_value = '<?php echo $_SESSION['block_slot_delete_date_value'] ?>';
	if(block_slot_delete_date_value != '') {
		block_slot_delete_date_value = block_slot_delete_date_value.split(" ");
		block_slot_delete_date_value = block_slot_delete_date_value[0];
		var goToDateCalendar = moment(block_slot_delete_date_value, 'YYYY-MM-DD')
		goToDateCalendar = goToDateCalendar.toISOString();
		$("#calendar").fullCalendar('gotoDate',goToDateCalendar);
		// to avoid redirection back to previous state on page reload, remove session value
		var truncate_block_slot_delete_session_date = '<?php echo $_SESSION['block_slot_delete_date_value'] = '' ?>';
	}

	var new_booking_date_value = '<?php echo $_SESSION['new_booking_date_value'] ?>';
	if(new_booking_date_value != '') {
		new_booking_date_value = new_booking_date_value.replaceAll('.', '-')
		var goToDateCalendar = moment(new_booking_date_value, 'DD-MM-YYYY')
		// goToDateCalendar = `${goToDateCalendar.year()}-${goToDateCalendar.month()+1}-${goToDateCalendar.date()}`; 
		goToDateCalendar = goToDateCalendar.toISOString();
		$("#calendar").fullCalendar('gotoDate',goToDateCalendar);
		// to avoid redirection back to previous state on page reload, remove session value
		var truncate_session_date = '<?php echo $_SESSION['new_booking_date_value'] = '' ?>';
	}

	var cancelled_booking_date_value = '<?php echo $_SESSION['cancelled_booking_date_value'] ?>';
	if(cancelled_booking_date_value != '') {
		cancelled_booking_date_value = cancelled_booking_date_value.replaceAll('.', '-')
		var goToDateCalendar = moment(cancelled_booking_date_value, 'DD-MM-YYYY')
		// goToDateCalendar = `${goToDateCalendar.year()}-${goToDateCalendar.month()+1}-${goToDateCalendar.date()}`; 
		goToDateCalendar = goToDateCalendar.toISOString();
		$("#calendar").fullCalendar('gotoDate',goToDateCalendar);
		// to avoid redirection back to previous state on page reload, remove session value
		var truncate_cancelled_booking_session_date = '<?php echo $_SESSION['cancelled_booking_date_value'] = '' ?>';
	}

	var booking_rebook_date_value = '<?php echo $_SESSION['booking_rebook_date_value'] ?>';
	if(booking_rebook_date_value != '') {
		booking_rebook_date_value = booking_rebook_date_value.replaceAll('.', '-')
		var goToDateCalendar = moment(booking_rebook_date_value, 'DD-MM-YYYY')
		// goToDateCalendar = `${goToDateCalendar.year()}-${goToDateCalendar.month()+1}-${goToDateCalendar.date()}`; 
		// goToDateCalendar = goToDateCalendar.format('DD-MM-YYYY');
		goToDateCalendar = goToDateCalendar.toISOString();
		$("#calendar").fullCalendar('gotoDate',goToDateCalendar);
		// to avoid redirection back to previous state on page reload, remove session value
		var truncate_booking_rebook_date = '<?php echo $_SESSION['booking_rebook_date_value'] = '' ?>';
	}

	var extinguish_booking_date_value = '<?php echo $_SESSION['extinguish_booking_date_value'] ?>';
	if(extinguish_booking_date_value != '') {
		// extinguish_booking_date_value = extinguish_booking_date_value.replaceAll('.', '-')
		var goToDateCalendar = moment(extinguish_booking_date_value, 'YYYY-MM-DD')
		// goToDateCalendar = `${goToDateCalendar.year()}-${goToDateCalendar.month()+1}-${goToDateCalendar.date()}`; 
		// goToDateCalendar = goToDateCalendar.format('DD-MM-YYYY');
		goToDateCalendar = goToDateCalendar.toISOString();
		$("#calendar").fullCalendar('gotoDate',goToDateCalendar);
		// to avoid redirection back to previous state on page reload, remove session value
		var truncate_booking_rebook_date = '<?php echo $_SESSION['extinguish_booking_date_value'] = '' ?>';
	}

function renderViewColumns(view, element) {
	if($('.fc-state-active').text()=='day' && $('.fc-state-active').text()=="TAG"){
		// var element =  document.getElementsByClassName("fc-today-tag");
		//alert('Inside');
           element.ClassName.add("fc-today");
		}


  element.find('th.fc-day-header.fc-widget-header').each(function() {
    var theDate = moment($(this).data('date')); /* th.data-date="YYYY-MM-DD" */

    $(this).html(buildDateColumnHeader(theDate));
  });

   element.find('.fc-slats tr').each(function() {
    var theDate = $(this).data('time'); /* th.data-date="YYYY-MM-DD" */
    var res = theDate.split(":");
    if(res[1] != '00'){
		  $(this).find('.fc-time').html('<span>'+res[0]+':'+res[1]+'</span>');
		  $(this).find('.fc-time').addClass('subtimeslot');
		}

   //$(this).html(buildDateColumnHeader(theDate));
  });

  function buildDateColumnHeader(theDate){
    var container = document.createElement('div');
    var DATE = document.createElement('span');
    var DDD = document.createElement('sup');
    var ddMMM = document.createElement('div');
    DDD.textContent = theDate.format('dddd');
    //var DDD1 = document.createElement('span');
    DATE.textContent = theDate.format('DD');
    container.appendChild(DATE);
    container.appendChild(DDD);
    //container.appendChild(ddMMM);
    return container;
  }
  function GetCalendarDateRange() {
        var calendar = $('#calendar').fullCalendar('getCalendar');
        var view = calendar.view;
        var start = view.start._d;
        var end = view.end._d;
        var dates = { start: start, end: end };
        return dates;
    }

 setTimeout(function(){
  var checkOpt =$("#selectTypeOption").val();
	  if(checkOpt=='rebook'){
		  //alert('jkj');
		  $(".fc-time-grid-event.fc-v-event.fc-event.fc-start.fc-end").each(function(){
			  $(this).addClass('addtransparent');
			  });
		  }
	  },2000);
}




$(document).on("mouseenter",".fc-time-grid-event.fc-v-event.fc-event",function(){
	    $("a.fc-time-grid-event.fc-v-event.fc-event").removeClass("cursor_hover");
       	$(this).addClass("cursor_hover");
});

$(document).on("mouseout",".fc-time-grid-event.fc-v-event.fc-event",function(){

});

 $(document).on('click','body',function(){
   if($("#d").hasClass('open')){
     $('.weekly-calender').addClass('display-n');
       $("#d").removeClass("open");
   }
});

$(document).on('click',"td .fc-future",function(){

	var date=$(this).attr("data-date");
	var empId='<?php if(!empty($_GET["id"])) echo $_GET["id"]; ?>';
	if(empId!=undefined && empId!=""){
		$("#dateunavailable").val(date);
		$("#userid").val(empId);
		$("#employee-not-available").modal("show");

		}
	 else{
		 return false;
		 }
	});
$('.clockpicker').clockpicker();

  $("#submitUnvailablity").click(function(){

	  var startTime = $("input[name='starttime']:checked").val();
	  var endTime = $("input[name='endtime']:checked").val();
    //console.log(startTime+'='+endTime);
	  var token=1;

	  if($("#block_more").is(':checked')) {
		if($("input[name='block_more_period']:checked").val() == "0") {
			if (!$("#block_specific_date").val()) {
				$("#SerrBM").html("<i class='fas fa-exclamation-circle mrm-5'></i>Datum auswählen");
			    token=0;
			}
		}
	  }
	  if($("#allday").is(':checked')==false)
	    {
		  if(startTime==undefined || startTime==""){
			    $("#Serrt").html("<i class='fas fa-exclamation-circle mrm-5'></i>Startzeit auswählen");
			    token=0;
			  }
		  if(endTime==undefined || endTime==""){
			    $("#Eerrt").html("<i class='fas fa-exclamation-circle mrm-5'></i>Endzeit auswählen");
			    token=0;
			  }
		  if(endTime<=startTime){
			    $("#Eerrt").html("<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line('end_time_should_be_greater_than_start_time'); ?>");
			   token=0;
			  }
	   }

	 if($("#allemaployee").is(':checked')==false && $("#block_id_update").val()==0)
	    {
		   if($("input[name='uid[]']:checked").val() ==undefined)
		     {
			  $("#Eerr").html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte wähle einen Mitarbeiter aus");
		      token=0;
		     }else{
			$("#Eerr").html("");
			}
		}

	if(token==1){
   // console.log($("#formUnvailablity").serialize());
		$("#Eerrt").html("");
		$("#Serrt").html("");
		loading();
		console.log($("#block_specific_date").val());
		var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
		  $.post(base_url+"merchant/employee_unavailablity", $("#formUnvailablity").serialize(), function( data ) {
		   //console.log(data);
			 var obj = jQuery.parseJSON( data );
			if(obj.success=='1'){
				$('#employee-not-available').modal('toggle');
				$('#calendar').fullCalendar('refetchEvents');
				  //window.location.href=obj.url;
				// window.location.reload();

			}else{
				if(obj.url!=""){
					window.location.href=obj.url;
					}
				else{
					$("#Showerr").html(obj.message);
					}
			}
			unloading();
		});

		}
	 else{
		 return false;
		 }
	});

   $(document).on('click','.viewMoreDetail',function(){
	  var fulltime=$(this).attr('data-fulltime');
	  var title=$(this).attr('data-title');
	  var arrayTitle = title.split("_");
	  $("#details_time").text(fulltime);
	  $("#detail_employee").text(arrayTitle[1]);
	  $("#details_service").text(arrayTitle[0]);
	  $("#bookingDetailsCalender").modal('show');
	  //alert(title);
	});




$('.fc-next-button,.fc-prev-button,.fc-today-button,.fc-agendaDay-button, .fc-agendaThreeDay-button,.fc-agendaWeek-button').click(function(){
	//alert('d');
	if($('.fc-state-active').text()=="TAG"){
		$("#calenderView").val('agendaDay');
		//$('.pignose-calender-unit-first-active').trigger('click');
		}
    else if($('.fc-state-active').text()=="WOCHE"){
		$("#calenderView").val('agendaWeek');
		checkMediaQuery();
		//$('.pignose-calender-unit-first-active').trigger('click');
		 }
	else{
		$("#calenderView").val('agendaThreeDay');
		//$('.pignose-calender-unit-first-active').trigger('click');
		 }

  loadScript();
});

$(document).on('click','#newBlockedTime',function(){
	var selectdate="<?php echo date('d.m.Y'); ?>";
	var startsamay1="";
	var startsamay="";
	var endsamay1="";
	var endsamay="";
	$.ajax({
		type: "POST",
		url:base_url+"rebook/getopeninghours_forblock",
		data:'date='+selectdate,
		success: function (response) {
			   unloading();
			  	var obj = $.parseJSON( response );
				if(obj.success==1){
					$("#addstarttimedropdown").html(obj.starttime);
					$("#addendtimedropdown").html(obj.endtime);

					$("#block_more_area").css("display", "block");
					$("#employee-not-available").modal('show');
					$("#selectTypeOption").val("book");
					var uiserid= $("input[name='id']:checked").val();
					if(uiserid==undefined){
						$("#userid").val('all');
					}
					else{
						$("#userid").val(uiserid);
					}
					$(".levelEmp").html("");
					$("#levelEmp").removeClass("label_add_top");
					$("input[name='uid[]']").each(function () {
						$(this).prop('checked', false);
					});
					$("#allemaployee").prop("checked",false);
					$("#allemaployee").attr("disabled",false);
					$("#idstart_"+startsamay1).prop("checked",true);
					$("#id_"+endsamay1).prop("checked",true);
					$(".levelEmp").attr('disabled',false);
					$("#block_note").val("");
					$("#blockdelet_url").attr('href',"");
					$("#block_id_update").val('0');
					$(".blockdelet_url").addClass('display-n');
					$("#dateunavailable").val(obj.date);

				  	const dateParts = obj.date.split(".");
					const cur_day = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]).getDay();
					let days = [0,1,2,3,4,5,6];
					days.splice(cur_day, 1);
					if (blockSpecificDate) {
						blockSpecificDate.destroy();
					}
					blockSpecificDate = $("#block_specific_date").datepicker({
						uiLibrary: 'bootstrap4',
						locale: 'de-de',
						format:"dd.mm.yyyy",
						minDate:today,
						disableDaysOfWeek: days,
						value: obj.date,
					});
					$(".levelEnd").text(endsamay);
					$(".levelStart").text(startsamay);
					$(".sticky-calender-top").removeClass('fixed-top-add');
				}else{
					Swal.fire({
						title: 'Der Salon ist an der ausgewählten Zeit bereits geschlossen',
						width: 600,
						padding: '3em'
					});
					return false;
				}

		   	}
	});
});
$(document).on('click','#closeDrop_selctTime',function(){
	$("#selectTypeOption").val("book");
	$(".sticky-calender-top").removeClass('fixed-top-add');

});

/* calender code */
//~ $(document).ready(function(){
  //~ $(".fc-toolbar.fc-header-toolbar .fc-left h2").attr('id','d');
//~ });

$(".fc-toolbar.fc-header-toolbar .fc-left h2").attr('id','d');

var rbookOption="<?php if(!empty($_GET['option'])) echo $_GET['option']; ?>";
var httpReffre="<?php if(!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; ?>";

if(rbookOption=='rebook' && httpReffre!="")
  {
   	$(".sticky-calender-top").addClass('fixed-top-add');
	$("#blocktext").text('Termin für Buchung auswählen');
  }

$(document).on("click","#d",function(){
    if($(".weekly-calender").attr("class")=="weekly-calender display-n"){

        $(".weekly-calender").removeClass("display-n");
        setTimeout(function(){ $("#d").addClass("open"); }, 500);


    }else{
        $(".weekly-calender").addClass("display-n");
        $("#d").removeClass("open");
    }
});


 });



//~ $(function(){
    $('.calender-weekily').pignoseCalender({
      select: function(date, obj) {

		   //~ $(this).find('div').each(function(){
	               //~ $(this).addClass('pignose-calender-unit-active');
	         //~ });


	        $(".pignose-calender-unit-active").each(function(){
				$(this).removeClass('pignose-calender-unit-active');
				});

	     if($('.fc-state-active').text()=='WOCHE'){

		    $(this).parent('.pignose-calender-row').find('div').each(function(){
	               $(this).addClass('pignose-calender-unit-active');
	         });
	      } else {  $(this).addClass('pignose-calender-unit-active'); }

	      //console.log('test');
		   setTimeout(function(){ loadScript(); },500);

		  $("#calendar").fullCalendar('gotoDate',date[0].format('YYYY-MM-DD'));


		  $(".weekly-calender").addClass("display-n");
		  $("#d").removeClass("open");


        //~ obj.calender.parent().next().show().text('You selected ' +
        //~ (date[0] === null? 'null':date[0].format('YYYY-MM-DD')) +
        //~ '.');
      }
    });

   $(".weekJump").click(function(){

	     //~ loadScript();

	 $("#calendar").fullCalendar('gotoDate',$(this).data('date'));
	 $(".weekly-calender").addClass("display-n");
    $("#d").removeClass("open");
    setTimeout(function(){ loadScript(); },500);
    $('.calender-weekily').pignoseCalender({
         date: moment($(this).data('date')),
          select: function(date, obj) {

	         $(".pignose-calender-unit-active").each(function(){
				$(this).removeClass('pignose-calender-unit-active');
				});

		    if($('.fc-state-active').text()=='week'){

		         $(this).parent('.pignose-calender-row').find('div').each(function(){
					   $(this).addClass('pignose-calender-unit-active');
				 });
			 }else {  $(this).addClass('pignose-calender-unit-active'); }

			  $("#calendar").fullCalendar('gotoDate',date[0].format('YYYY-MM-DD'));
			  setTimeout(function(){ loadScript(); },500);
			  $(".weekly-calender").addClass("display-n");
			  $("#d").removeClass("open");

      }
    });
    if($('.fc-state-active').text()=='week'){
     $(".pignose-calender-unit-active").parent('.pignose-calender-row').find('div').each(function(){
	               $(this).addClass('pignose-calender-unit-active');
	         });
	}

    //alert($(this).data('date'));
});


  //~ });

function loadScript(){
//console.log('f');

$('.fc-widget-content').hover(function(){
	//alert('ds');
    if(!$(this).html() && !$(this).hasClass('fc-time')){
		var tim= $(this).closest("tr").data('time');
		var res =tim.split(":");
		var ti=res[0]+":"+res[1];
		//alert(ti);
		if($('thead tr th').hasClass('fc-resource-cell')){
			  var numItems = $('thead tr th.fc-resource-cell').length;
			  var clas='fc-resource-cell';
			}else{
		   var numItems = $('thead tr th.fc-day-header').length;
		    var clas='fc-day-header'; //alert('no');
	      }
        for(i=0;i<numItems;i++){
            $(this).append('<td class="temp_cell" style="border: 0px;cursor:cell; height:25px; width:'+(Number($('.'+clas).width())+2)+'px !important;"></td>');
        }

        $(this).children('td').each(function(){
            $(this).hover(function(){
                $(this).css({'background-color': '#e0f7ff'});
                $(this).html(ti);
            },function(){
                $(this).prop('style').removeProperty( 'background-color' );
                $(this).html('');
            });
        });
    }
},function(){
    $(this).children('.temp_cell').remove();
});

if($(".fc-now-indicator").is(":visible")){
	var whight=$(document).height();
	var nhight=$(".fc-now-indicator").css('top');
	nhight= parseInt(nhight)- 10;
	//hieth=hieth-300;
	//alert($(".fc-scroller").height()+"=="+nhight);
	 $('.fc-scroller').animate({
        scrollTop: nhight
    }, 'slow');

	}

}

setTimeout(function(){ loadScript(); },500);



$(document).on('mouseenter','.pignose-calender-row',function(){
	//alert($('.fc-state-active').text());
   //~ $('.pignose-calender-row a.hover').each(function(){
	    //~ $(this).removeClass('hover');
	   //~ });
  if($('.fc-state-active').text()=='WOCHE'){
		$(this).find('a').each(function(){
			 $(this).addClass('hover');
		  });
	}
 else{

	 }
 });

$(document).on('click','.pignose-calender-unit-first-active',function(){
  //alert($('.fc-state-active').text());
   //~ $('.pignose-calender-row a.hover').each(function(){
	    //~ $(this).removeClass('hover');
	   //~ });
  if($('.fc-state-active').text()=='WOCHE'){
		  $(this).parent('.pignose-calender-row').find('div').each(function(){
	               $(this).addClass('pignose-calender-unit-active');
	         });
	}
 else{

	 }
 });




$(document).on('mouseleave','.pignose-calender-row',function(){

  $(this).find('a').each(function(){
	  $(this).removeClass('hover');
	  });

 });

$(document).on("change","#allday",function(){

 if($(this).is(':checked')){
  //alert('s');

      $(".checkFirstSlot").prop('checked',true);
      $(".checkLastSlot").prop('checked',true);

      $("#levelStart").addClass('label_add_top');
      $(".levelStart").text($(".checkFirstSlot").attr('data-val'));
      $("#levelEnd").addClass('label_add_top');
      $(".levelEnd").text($(".checkLastSlot").attr('data-val'));

    	$(".levelStart").attr('disabled',true);
    	$(".levelEnd").attr('disabled',true);



	 }
 else{
	    $(".levelStart").attr('disabled',false);
    	$(".levelEnd").attr('disabled',false);
	 }

 });

 $(document).on("change","#allemaployee",function(){

 if($(this).is(':checked')){
  //alert('s');

      $("#allemaployee").prop('checked',true);
      //$(".checkLastSlot").prop('checked',true);

     // $(".levelStart").text($(".checkFirstSlot").attr('data-val'));
      //$(".levelEnd").text($(".checkLastSlot").attr('data-val'));

    	$(".levelEmp").attr('disabled',true);


	 }
 else{
	    $(".levelEmp").attr('disabled',false);
    	//$(".levelEnd").attr('disabled',false);
	 }

 });

$(document).on("change",".block_more_period",function(){
   	var val=$(this).val();
   	if(val=="0"){
      	$("#block_specific_date_wrap").removeClass('display-n');
    }else{
		$("#block_specific_date_wrap").addClass('display-n');
	}
});
$(document).on("change","#block_more",function(){
  if($(this).is(':checked')){
   //alert('s');

	   $("#block_more").prop('checked',true);
	   //$(".checkLastSlot").prop('checked',true);

	  // $(".levelStart").text($(".checkFirstSlot").attr('data-val'));
	   //$(".levelEnd").text($(".checkLastSlot").attr('data-val'));

		$(".block_more_period").attr('disabled',false);
		$("#block_specific_date_wrap").removeClass('pick-disable');

	  }
  else{
		 $(".block_more_period").attr('disabled',true);
		 $("#block_specific_date_wrap").addClass('pick-disable');
		 //$(".levelEnd").attr('disabled',false);
	  }

});

$(document).on("click",".rebookedOk",function(){
	//alert('as');
	window.location.href =base_url+"merchant/dashboard";

	});

$('#widget').draggable();

$(document).on("change","#vcheckbox15",function(){
  //$("#vcheckbox15").change(function(){
    if($(this).is(":checked")){
      $(".cancelOnineHrOpt").removeClass('disabled');
      }
    else{ $(".cancelOnineHrOpt").addClass('disabled'); }
   });

$(document).on("click","#conf_close",function(){
  var id = $(this).attr('data-bookid');
  if(id != undefined)
    getBokkingDetail(id);

});

$(document).on('click','.click_closepop', function(){
      $("#booking-details-modal").modal('hide');
});

$(document).on('click','#closeOnlineBokkingOptionMsg', function(){
	// $.ajax({
	// 	type: "POST",
	// 	url:base_url+"profile/upset_bookingoption",
	// 	success: function (response) {
	// 		unloading();
	// 		var obj=$.parseJSON(response);
	// 		if(obj.success==1){
	// 			unloadin();
	// 		}
	// 		}
	// });
});



$('#dropdownMenuButton').on('click', function(){
    $('.new-effect-droup').Toggle('slow');
});

checkMediaQuery();
function checkMediaQuery() {
  // If the inner width of the window is greater then 768px
  if (window.innerWidth < 992) {
    // Then log this message to the console
    $(function(){
		$(".fc-widget-header div sup").each(function(i){
			len=$(this).text().length;
			if(len>2)
			{
				$(this).text($(this).text().substr(0,2)+'.');
			}
		});
	});
  }
}
// Add a listener for when the window resizes
window.addEventListener('resize', checkMediaQuery);
  $(document).ready(function(){
   if(document.readyState === 'interactive') {
	  //alert('sd');
      $('.pignose-calender-unit-first-active').trigger('click');
   }
});
 </script>

<?php if(!empty($this->session->flashdata('setup_success'))) {?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script type="text/javascript">

 var maxParticleCount=250;
 var particleSpeed=4;
 var startConfetti;
 var stopConfetti;
 var toggleConfetti;
 var removeConfetti;
 (function(){
   startConfetti=startConfettiInner;stopConfetti=stopConfettiInner;toggleConfetti=toggleConfettiInner;removeConfetti=removeConfettiInner;
 var colors=["DodgerBlue","OliveDrab","Gold","Pink","SlateBlue","LightBlue","Violet","PaleGreen","SteelBlue","SandyBrown","Chocolate","Crimson"]
 var streamingConfetti=false;
 var animationTimer=null;
 var particles=[];
 var waveAngle=0;
 function resetParticle(particle,width,height){particle.color=colors[(Math.random()*colors.length)|0];particle.x=Math.random()*width;particle.y=Math.random()*height-height;particle.diameter=Math.random()*10+5;particle.tilt=Math.random()*10-10;particle.tiltAngleIncrement=Math.random()*0.07+0.05;particle.tiltAngle=0;return particle;}
 function startConfettiInner(){var width=window.innerWidth;var height=window.innerHeight;window.requestAnimFrame=(function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(callback){return window.setTimeout(callback,16.6666667);};})();var canvas=document.getElementById("confetti-canvas");if(canvas===null){canvas=document.createElement("canvas");canvas.setAttribute("id","confetti-canvas");canvas.setAttribute("style","display:block;z-index:999999;pointer-events:none");document.body.appendChild(canvas);canvas.width=width;canvas.height=height;window.addEventListener("resize",function(){canvas.width=window.innerWidth;canvas.height=window.innerHeight;},true);}
var context=canvas.getContext("2d");while(particles.length<maxParticleCount)
particles.push(resetParticle({},width,height));streamingConfetti=true;if(animationTimer===null){(function runAnimation(){context.clearRect(0,0,window.innerWidth,window.innerHeight);if(particles.length===0)
animationTimer=null;else{updateParticles();drawParticles(context);animationTimer=requestAnimFrame(runAnimation);}})();}}
function stopConfettiInner(){streamingConfetti=false;}
function removeConfettiInner(){stopConfetti();particles=[];}
function toggleConfettiInner(){if(streamingConfetti)
stopConfettiInner();else
startConfettiInner();}
function drawParticles(context){
 var particle;
 var x;
 for(var i=0;i<particles.length;i++){
   particle=particles[i];
   context.beginPath();
   context.lineWidth=particle.diameter;
   context.strokeStyle=particle.color;
   x=particle.x+particle.tilt;
   context.moveTo(x+particle.diameter/2,particle.y);
   context.lineTo(x,particle.y+particle.tilt+particle.diameter/2);
   context.stroke();}
 }
function updateParticles(){
 var width=window.innerWidth;
 var height=window.innerHeight;
 var particle;waveAngle+=0.01;
 for(var i=0;i<particles.length;i++){
   particle=particles[i];
   if(!streamingConfetti&&particle.y<-15)
	   particle.y=height+100;
   else{
	 particle.tiltAngle+=particle.tiltAngleIncrement;particle.x+=Math.sin(waveAngle);particle.y+=(Math.cos(waveAngle)+particle.diameter+particleSpeed)*0.5;particle.tilt=Math.sin(particle.tiltAngle)*15;
   }
   if(particle.x>width+20||particle.x<-20||particle.y>height){
	 if(streamingConfetti&&particles.length<=maxParticleCount)
	   resetParticle(particle,width,height);
	 else{particles.splice(i,1);
	   i--;}
	 }
   }
 }
})();

$('[data-toggle="tooltip"]').tooltip({ boundary: 'window' });
   </script>

<script src="<?php echo base_url('assets/frontend/js/ui.js'); ?>"></script>

<script>
	$( document ).ready(function() {
		startConfetti();
		setTimeout(function() {
			stopConfetti();
		}, 4000);
	});
</script>
<?php }?>
