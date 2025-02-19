<?php $this->load->view('frontend/common/header'); ?>
<?php $permissions = getEmpPermissionForDeletCancel($this->session->userdata('st_userid'));
      $permission = $permissions->allow_emp_to_delete_cancel_booking; ?>
<style>
.pignose-calender-row a.hover {
    background-color: #D8D8D8;
}
.after-box-roted {
	display: none !important;
}
.pignose-calender-row a:hover {
    background-color: #D8D8D8;
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
.fc-time-grid-event.fc-v-event.fc-event.fc-start.fc-end.block-time-bg{
    background-color:rgb(212 212 212) !important;
    border-color:rgb(212 212 212) !important;
}
.fc-time-grid-event.fc-v-event.fc-event.fc-start.fc-end.block-time-bg .fc-bg{
    background: #d7d7d7;
    touch-action: pan-y;
    width: 100%;
    height: 100%;
    position: relative;
    background-size: 8px 8px;
    background-image: linear-gradient(45deg, transparent 46%, rgba(16, 25, 40, 0.69) 49%, rgba(16, 25, 40, 0.62) 51%, transparent 55%);
    background-color: transparent;
    left: -2px;
}
.fc-time-grid-event.fc-v-event.fc-event.fc-start.fc-end.block-time-bg .fc-title{
    color: #333 !important;
}
.fc-time-grid-event.fc-v-event.fc-event.fc-start.fc-end.block-time-bg .fc-time{
    color: #666 !important;
    font-weight: 600;
}
.fc-time-grid-event.fc-v-event.fc-event.fc-start.fc-end.event-text-white .fc-title,
.fc-time-grid-event.fc-v-event.fc-event.fc-start.fc-end.event-text-white .fc-time {
    color: white !important;
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
.fc-today{
	  background-color:rgba(69,170,181,0.05) !important;
}
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
    background: #ffc107;
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

.fc-unthemed .fc-body > tr > td.fc-widget-content{
	border-left-color:transparent !important;
}
@media(max-width:992px){
	.fc-scroller.fc-time-grid-container{
		height:calc(100vh - 265px) !important;
	}
	.employee_dashboard_calender_section #calendar .fc-toolbar .fc-right {
		top: 0px;
	}
}
@media(max-width:767){
	.fc-toolbar.fc-header-toolbar .fc-left h2{
		width:140px;
	}
}
@media(max-width:576px){
	.employee_dashboard_calender_section .relative.mt-20.mb-60.bgwhite.around-20{
		padding:0px 10px 10px 10px
	}
	#calendar .fc-left .fc-button-group{
		width:40%;
	}
	.fc-toolbar.fc-header-toolbar .fc-left{
		margin-right:0px;
	}
	.fc-scroller.fc-time-grid-container{
		height:calc(100vh - 285px) !important;
	}
}
@media(max-width:471px){
	.fc-scroller.fc-time-grid-container{
		height:calc(100vh - 340px) !important;
	}
	#calendar .fc-left .fc-button-group{
		width:37%;
	}
	.fc-toolbar.fc-header-toolbar .fc-left h2{
		width:125px;
	}
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

#calendar{margin-top:60px;	 }
#calendar .fc-left,#calendar .fc-right{margin-top:-60px}
@media(max-width:992px){
	#calendar .fc-left,#calendar .fc-right{margin-top:10px}
	.fc-toolbar .fc-right {
    right: 0px !important;
    top: 0px;
	}
	#calendar{margin-top:0px;}
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
</style>
<link rel="stylesheet" href='<?php echo base_url('assets/frontend/js/fullcalender/fullcalendar.min.css'); ?>'/>
<link rel="stylesheet" href='<?php echo base_url('assets/frontend/css/pignose.calender.css'); ?>'/>
<link rel="stylesheet" href="<?php echo base_url("assets/cropp/croppie.css");?>" type="text/css" />
<!--
<link rel="stylesheet" href='<?php //echo base_url('assets/frontend/js/fullcalender/scheduler.min.css'); ?>'/>
<link rel="stylesheet" href='<?php //echo base_url('assets/frontend/js/fullcalender/fullcalendar.print.min.css'); ?>'/>
-->
<section class="pt-84 clear employee_dashboard_calender_section">
      <?php // $this->load->view('frontend/common/sidebar'); ?>
 <div class="w-100 pl-15 pr-15">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

					 <div class="relative mt-20 mb-60 bgwhite around-20">
						<?php if ($permission == 1) { ?>
						<div class="display-ib ml-auto v_custome_button" >
							<div class="dropdown">
								<button class="plus-green dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-plus colorwhite"></i>
								</button>
								<div class="new-effect-droup" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" id="newBlockedTime" href="#"><?php echo $this->lang->line('New_Blocked_time'); ?></a>
								</div>
							</div>
						</div>
						<?php } ?>
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
                     </div>
<!--
                     <button class="btn widthfit" data-toggle="modal" data-target="#employee-not-available">for popup trigger</button>
-->
                </div>
            </div>
        </div>
 </div>
    </section>
<?php $this->load->view('frontend/common/footer_script'); ?>

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
								</label>
							</div>
						</div>
				  	</div>
				  	<input type="hidden" name="block_id" value="0" id="block_id_update">
					<input type="checkbox" id="idq_1_block<?php echo $employee_id; ?>" name="uid[]" checked class="" value="<?php echo url_encode($employee_id); ?>">
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


<script src='<?php echo base_url('assets/frontend/js/fullcalender/moment.min.js'); ?>'></script>


<script src='<?php echo base_url('assets/frontend/js/fullcalender/fullcalendar.min.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/fullcalender/locale-all.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/fullcalender/scheduler.min.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/pignose.calender.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/tooltip.min.js'); ?>'></script>

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
$(document).on('click','.fc-agendaDay-button',function(){
	//alert('TAG');
     //$('.fc-day fc-widget-content fc-sat').removeClass('fc-today');
     $('.fc-day').removeClass('fc-today');
    });
localStorage.setItem("STATUS", "LOGGED_IN");
  $(document).ready(function(){

	 var permission = '<?php if(!empty($permission) && $permission==1) echo TRUE; else echo FALSE; ?>';
	 var businesshours = '<?php if(!empty($businesshours)) echo json_encode($businesshours); ?>';
    var businessHoursres = JSON.parse(businesshours);

	  $('.saleEmployee').change(function(){
		  $("#employeeForm").submit();
		  });


	const weekday1 = [ 'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
	const weekday2 = [ 'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];

	const weekday = $(window).width() >= 768 ? weekday1 : weekday2;

	 $('#calendar').fullCalendar({
		//aspectRatio: 2,

		plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
		header: {
			left: 'prev,today, title next ',
			//center: 'title',
			right: 'agendaWeek, agendaDay'
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
			day: 'TAG'
		},
		titleFormat:'DD. MMMM YYYY',
		selectable:true,
		editable: permission,
		slotEventOverlap:false,
		nowIndicator:true,
		eventDurationEditable:false,
		minTime:'<?php if(!empty($minmaxtime->mintime))
			echo $minmaxtime->mintime; else echo "00:00:00"; ?>',
		maxTime:'<?php if(!empty($minmaxtime->maxtime))
			echo $minmaxtime->maxtime; else echo "23:00:00"; ?>',
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
			agendaTwoDay: {
				type: 'agenda',
				duration: { days: 2 },

				// views that are more than a day will NOT do this behavior by default
				// so, we need to explicitly enable it
				groupByResource: true

				//// uncomment this line to group by day FIRST with resources underneath
				//groupByDateAndResource: true
			}
		},
		defaultView: '<?php if(!empty($_GET["view"])) echo $_GET["view"]; else echo "agendaWeek"; ?>',
		viewRender: renderViewColumns,
		events: {
			url: base_url+'employee/booking',
			type: 'POST',
			data: {id: '<?php if(!empty($_GET["id"])) echo $_GET["id"]; ?>'}
		},
		timeFormat:'H:mm',
		slotLabelFormat: [
			'HH:mm'
		],
		eventDragStart: function(event) {
			console.log(444);
			return false;
		},
		eventDrop: function(event,dayDelta,revertFunc) {
			if(event.blocked!=0){
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


					}else{
						revertFunc();
						}
				});


			}else{

				var startsamay=moment(event._start._i).format('YYYY-MM-DD HH:mm');
				//var today=moment(new Date()).utcOffset('+0530').format('YYYY-MM-DD HH:mm');
				var today=moment(new Date()).format('YYYY-MM-DD HH:mm');
				//alert(startsamay+"=="+today);
				if(startsamay < today)
				{
					//alert('You cannot reschedule a booking. That booking time has passed');
					//Swal.fire('You cannot reschedule a booking. That booking time has passed');
					Swal.fire({
						title: 'Eine bereits vergangene Buchung kann nicht verlegt werden.',
						width: 600,
						padding: '3em'
					});
					revertFunc();
					return false;
				}
				else{
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
									}else{
										revertFunc();
										Swal.fire(
											'',
											obj.msg,
											''
										);
									}

								}
							});


						}else{
							revertFunc();
						}
					});


				}
			}
		},
		dateClick: function(info) {
			//alert('clicked ' + info.dateStr);
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

				if (calEvent.editable == true) {
					loading();

					var selectdate  = moment(calEvent.start._d).utcOffset('+0000').format('DD.MM.YYYY');
					var startsamay  = moment(calEvent.start._d).utcOffset('+0000').format('HH:mm');
					var endsamay    = moment(calEvent.end._d).utcOffset('+0000').format('HH:mm');
					var startsamay1 = moment(calEvent.start._d).utcOffset('+0000').format('HH-mm');
					var endsamay1   = moment(calEvent.end._d).utcOffset('+0000').format('HH-mm');

					$.ajax({
						type: "POST",
						url:base_url+"employee/get_blocktime_details",
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

								$("#allemaployee").prop("checked",false);
								$(".levelEmp").text(obj.employee_txt);
								$(".levelEmp").attr('disabled',false);

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
								$("#blockdelet_url").attr('href',base_url+"employee/blockdelete/"+calEvent.blocked);
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
function renderViewColumns(view, element) {
	if($('.fc-state-active').text()=='day'){
		$('.fc-today').each(function(){
			$(this).removeClass('fc-today');
			});
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
}


$(document).on("mouseenter",".fc-time-grid-event.fc-v-event.fc-event",function(){
	    $("a.fc-time-grid-event.fc-v-event.fc-event").removeClass("cursor_hover");
       	$(this).addClass("cursor_hover");
});

$(document).on("mouseout",".fc-time-grid-event.fc-v-event.fc-event",function(){

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

$(document).on('click','body',function(){
   if($("#d").hasClass('open')){
     $('.weekly-calender').addClass('display-n');
       $("#d").removeClass("open");
   }
});

$("#submitUnvailablity").click(function(){

	var startTime = $("input[name='starttime']:checked").val();
	var endTime = $("input[name='endtime']:checked").val();
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

	if(token==1){
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
		$.post(base_url+"employee/employee_unavailablity", $("#formUnvailablity").serialize(), function( data ) {
		var obj = jQuery.parseJSON( data );
		if(obj.success=='1'){
			window.location.reload();
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
$(document).on("change",".block_more_period",function(){
   	var val=$(this).val();
   	if(val=="0"){
      	$("#block_specific_date_wrap").removeClass('display-n');
    }else{
		$("#block_specific_date_wrap").addClass('display-n');
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
function loadScript(){
//alert('f');

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
            $(this).append('<td class="temp_cell" style="border: 0px;cursor:cell; height:26px; width:'+(Number($('.'+clas).width())+2)+'px !important;"></td>');
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





$('.fc-next-button,.fc-prev-button,.fc-today-button,.fc-agendaDay-button,.fc-agendaWeek-button').click(function(){
	//alert('d');
	if($('.fc-state-active').text()=="TAG"){
		$("#calenderView").val('agendaDay');
		$(this).removeClass('fc-widget-header');

		//$('.pignose-calender-unit-first-active').trigger('click');
		$('.fc-today').each(function(){
			$(this).removeClass('fc-today');
		});
	}
    else{
	    $("#calenderView").val('agendaWeek');
		checkMediaQuery();
		//$('.pignose-calender-unit-first-active').trigger('click');
		 }
  loadScript();
});

var blockSpecificDate;
$(document).ready(function(){
	var date = new Date();
    var today =new Date(date.getFullYear(), date.getMonth(), date.getDate());

    var sixyears = new Date("<?php echo date('Y-m-d H:i:s',strtotime('+3 months')); ?>");
    var twomonths =new Date(sixyears.getFullYear(), sixyears.getMonth(), sixyears.getDate());
    //alert(today+"="+twomonths);

	let days = [0,1,2,3,4,5,6];
	days.splice(date.getDay(), 1);

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
		 weekStartDay: 1,
         maxDate:twomonths,
         format:"dd.mm.yyyy",
        //  monthNames: ["JANUAR","FEBRUAR","MÄRZ","APRIL","MAI","JUNI","JULI", "AUGUST", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DEZEMBER" ],
        //  dayNamesMin:[ 'SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
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
	$("#blocktext").text('Select a time to book');
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

      $(".levelStart").text($(".checkFirstSlot").attr('data-val'));
      $(".levelEnd").text($(".checkLastSlot").attr('data-val'));

    	$(".levelStart").attr('disabled',true);
    	$(".levelEnd").attr('disabled',true);



	 }
 else{
	    $(".levelStart").attr('disabled',false);
    	$(".levelEnd").attr('disabled',false);
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
$(document).on("change","#block_more",function(){
  	if($(this).is(':checked')){
		$("#block_more").prop('checked',true);
		$(".block_more_period").attr('disabled',false);
		$("#block_specific_date_wrap").removeClass('pick-disable');
	}
  	else{
		$(".block_more_period").attr('disabled',true);
		$("#block_specific_date_wrap").addClass('pick-disable');
	}
});
$(document).on('click','#closeOnlineBokkingOptionMsg', function(){
    	// $.ajax({
		// 	   type: "POST",
		// 	   url:base_url+"profile/upset_bookingoption",
		// 	   success: function (response) {
		// 		  unloading();
		// 			var obj=$.parseJSON(response);
		// 			if(obj.success==1){
		// 				unloadin();
		// 			}
		// 		  }
		// 		});
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
