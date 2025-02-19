<?php $this->load->view('frontend/common/header'); ?>
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

<?php $this->load->view('frontend/common/footer_script');  ?>
<script src='<?php echo base_url('assets/frontend/js/moment.min.js'); ?>'></script>
<script src='<?php echo base_url('assets/frontend/js/fullcalendar.min.js'); ?>'></script>

 <script>

  $(document).ready(function() {
  	
  	$('#calendar').fullCalendar({

     header: {
		left: 'prev,next today',
		center: 'title',
		right: 'month,basicWeek,basicDay,listWeek'
	},
	// defaultDate: '2019-01-12',
	navLinks: true, // can click day/week names to navigate views
	editable: false,
	eventLimit: true, // allow "more" link when too many events
	displayEventEnd: true,
	events: {
			url: base_url+'employee/booking',
			type: 'POST',
			/*data: { userid: '1',
				},*/
					error: function() {
						alert('there was an error while fetching events!');
					}
			},
	});  


  });

</script>