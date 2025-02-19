<?php $this->load->view('frontend/common/header'); ?>
<style>

.fc-event-container{

    word-break: break-all;


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
	height: 50px !important;
	}


	
	
</style>
<link rel="stylesheet" href='<?php echo base_url('assets/frontend/css/fullcalendar.min.css'); ?>';  />

<section class="pt-84 clear employee_dashboard_calender_section">
      <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                 <div class="relative mt-60 mb-60 bgwhite around-20">
                        <div id='calendar'></div>
                 </div>
            </div>
        </div>
      </div>
    </section>

<!-- <section class="pt-84 clear user_profile_section1">
      <?php //$this->load->view('frontend/common/sidebar'); ?>
 <div class="right-side-dashbord w-100 pl-30 pr-15">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                     <div class="relative mt-60 mb-60 bgwhite around-20">   
                     	<div id='calendar'></div>
                     </div>

                </div>
            </div>
        </div>
 </div>
    </section> -->
<?php $this->load->view('frontend/common/footer_script'); ?>

 <div class="modal fade" id="employee-not-available">
     <div class="modal-dialog modal-md modal-dialog-centered" role="document">
       <div class="modal-content">
         <a href="#" class="crose-btn" data-dismiss="modal">
           <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg'); ?>" class="popup-crose-black-icon">
         </a>
         <div class="modal-body pt-30 mb-30 pl-40 pr-40">
           <h3 class="font-size-18 fontfamily-medium color333 mb-50">Select your time for which employee is not available.
           <p id="Showerr" class="error" ></p></h3>
           
           <form id="formUnvailablity">
             <div class="row">
				 <input type="hidden" name="date" id="dateunavailable">
				 <input type="hidden" name="uid" id="userid">
               <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group form-group-mb-50">
						<div class="btn-group multi_sigle_select inp_select"> 
							<span class="label">Startzeit</span>
							<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"></button>
							<ul class="dropdown-menu mss_sl_btn_dm custome_scroll height200">
								<?php  $start = "01:00"; //you can write here 00:00:00 but not need to it
							$end = "23:00";

							$tStart = strtotime($start);
							$tEnd = strtotime($end);
							$tNow = $tStart;
							while($tNow <= $tEnd){ ?>
							  <li class="radiobox-image">
								<input type="radio" id="idstart_<?php echo date("H:i",$tNow) ?>" name="starttime" class="" value="<?php echo date("H:i:s",$tNow) ?>">
								<label for="idstart_<?php echo date("H:i",$tNow) ?>"><?php echo date("H:i",$tNow) ?></label>
							  </li>
							  <?php $tNow = strtotime('+15 minutes',$tNow); } ?>
							</ul>
						</div> 
				     <span id="Serr" class="error"></span>
				   

               </div>
               </div>
               <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
				    <div class="form-group form-group-mb-50">
						<div class="btn-group multi_sigle_select inp_select"> 
							<span class="label">Endzeit</span>
							<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"></button>
							<ul class="dropdown-menu mss_sl_btn_dm custome_scroll height200">
								<?php  $start = "01:00"; //you can write here 00:00:00 but not need to it
							$end = "23:00";

							$tStart = strtotime($start);
							$tEnd = strtotime($end);
							$tNow = $tStart;
							while($tNow <= $tEnd){ ?>
							  <li class="radiobox-image">
								<input type="radio" id="id_<?php echo date("H:i",$tNow) ?>" name="endtime" class="" value="<?php echo date("H:i:s",$tNow) ?>">
								<label for="id_<?php echo date("H:i",$tNow) ?>"><?php echo date("H:i",$tNow) ?></label>
							  </li>
							  <?php $tNow = strtotime('+15 minutes',$tNow); } ?>
							</ul>
						</div> 
				     <span id="Eerr" class="error"></span>
				   

               </div>
             </div>
             <div class="text-center mt-15 col-12">
               <button type="button" id="submitUnvailablity" class=" btn btn-large widthfit">ok</button>
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
           <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg'); ?>" class="popup-crose-black-icon">
         </a>
         <div class="modal-body pt-20 pl-20">
           <h3 class="font-size-20 fontfamily-medium color333">Booking Details
             <div class="font-size-16 mt-20">
				 <div class="d-inline-flex booking-dtl-mdl-rw">
					 <div class="p-2 width130 booking-dtl-mdl-rw-lft relative fontsize-14">Time</div>
					 <div class="p-2 booking-dtl-mdl-rw-rgt fontsize-14" id="details_time"></div>
				 </div>
				<!--  <div class="d-inline-flex booking-dtl-mdl-rw">
					 <div class="p-2 width130 booking-dtl-mdl-rw-lft relative fontsize-14">Employee</div>
					 <div class="p-2 fontsize-14" id="detail_employee"></div>
				 </div> -->
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

<script src='<?php echo base_url('assets/frontend/js/moment.min.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/fullcalendar.min.js'); ?>'></script>
<script>
  $(document).ready(function() {
	  
	  
 
	 $('#calendar').fullCalendar({
            //aspectRatio: 2,
           
            header: {
                left: 'prev,today, title next ',
                //center: 'title',
                right: 'agendaWeek, agendaDay'
            },
           
            defaultView: 'agendaWeek',
            editable: true,
            eventLimit: true,
            eventColor:true, // allow "more" link when too many events
	        displayEventEnd: true,
	        slotDuration:'00:15:00', 
            viewRender: function(view, element) {
                if (!(/Mobi/.test(navigator.userAgent)) && jQuery().jScrollPane) {
                    $('.fc-scroller').jScrollPane({
                        autoReinitialise: true,
                        autoReinitialiseDelay: 100
                    });
                }
            },
           events: {
			url: base_url+'employee/booking',
			type: 'POST',
			/*data: {id: '<?php if(!empty($_GET["id"])) echo $_GET["id"]; ?>',
				},*/
			error: function() {
					alert('there was an error while fetching events!');
				}
			},

			timeFormat:'H(:mm)',
			slotLabelFormat: [
                'HH:mm'
            ],
			
            eventClick: function(calEvent, jsEvent, view) {
                if (!$(this).hasClass('event-clicked')) {
                    $('.fc-event').removeClass('event-clicked');
                    $(this).addClass('event-clicked');
                }
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
	 
  });

</script>
