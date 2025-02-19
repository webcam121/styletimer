<h3 class="font-size-18 color333 fontfamily-medium mb-5"><?php if(!empty($formtitle)) echo $formtitle; ?></h3>
<form id="addclosetime" method="post">  
  <?php if(!empty($closetime->id)){ ?>
	<input type="hidden" name="closeid" value="<?php echo url_encode($closetime->id); ?>">
   <?php } ?>  
<div class="row">
  <div class="col-6">
	  <div class="form-group form-group-mb-50" id="startdate_validate">
		<span class="label"><?php echo $this->lang->line('start_date'); ?></span>
		<label class="inp">
		  <input type="text" placeholder="Startdatum wird benötigt" class="form-control pl-2" id="datepicker122" name="startdate" value="<?php if(!empty($closetime->booking_time)) echo date('d.m.Y',strtotime($closetime->booking_time)); else echo date('d.m.Y'); ?>" style="background-color: #ffffff;" readonly>
		  <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue" style="top:8px;right:9px;">
		</label>                                                
	  </div>
  </div>
  <div class="col-6">
	  <div class="form-group form-group-mb-50" id="enddate_validate">
		<span class="label"><?php echo $this->lang->line('end_date'); ?></span>
		<label class="inp">
		  <input type="text" placeholder="Enddatum wird benötigt" class="form-control pl-2" id="datepicker222" name="enddate" value="<?php if(!empty($closetime->booking_endtime)) echo date('d.m.Y',strtotime($closetime->booking_endtime)); else echo date('d.m.Y',strtotime('+1 day')); ?>" style="background-color: #ffffff;" readonly>
		  <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue" style="top:8px;right:9px;">
		</label> 
		<label class="error" id="enddateerror"></label>                                               
	  </div>
  </div>
  	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="form-group form-group-mb-50">
			<span class="label" id="levelEmp"><?php echo $this->lang->line('Select_Employee'); ?></span>
				<div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new"> 
					<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn timeSelected height56v levelEmp"><?php echo $this->lang->line('All_staff'); ?></button>
					<ul class="dropdown-menu mss_sl_btn_dm">
					<?php if(!empty($employees)){  ?>
						<li class="radiobox-image">
							<input type="radio" id="idq_1all_block" name="uid" class="" data-text="<?php echo $this->lang->line('All_staff'); ?>" value="all" <?php if(!empty($closetime->totalcount) && $closetime->totalcount>1 || empty($closetime)) echo 'CHECKED'; ?> ><label for="idq_1all_block"><?php echo $this->lang->line('All_staff'); ?></label>
							</li>							
												
					<?php	foreach($employees as $emp){ ?>
					<li class="radiobox-image"><input type="radio" id="idq_1_block<?php echo $emp->id; ?>" name="uid" class="" data-text="<?php echo $emp->first_name." ".$emp->last_name; ?>" value="<?php echo url_encode($emp->id); ?>" <?php if(!empty($closetime->totalcount) && $closetime->totalcount==1 && $closetime->employee_id==$emp->id) echo 'CHECKED'; ?>>
					<label for="idq_1_block<?php echo $emp->id; ?>"><?php echo $emp->first_name." ".$emp->last_name; ?></label></li>
					<?php } } ?>						
					</ul>
				</div> 
				<span id="Eerr" class="error"></span>
		</div>
	</div>  
  <div class="col-12">
	  <div class="form-group form-group-mb-50" id="">
		<span class="label"><?php echo $this->lang->line('description'); ?> </span>
		<label class="inp" style="height:auto;">
		  <textarea type="text" placeholder="Beispiel: Urlaub" class="form-control pl-2" name="block_note" value=""><?php if(!empty($closetime->notes)) echo $closetime->notes; ?></textarea>
		</label>                                                
	  </div>
  </div>
  <div class="col-12 text-center">
	   <!--<button class="btn btn-large widthfit" data-target="#close_time_modal" data-toggle="modal" data-dismiss="modal">Save</button> -->
	  <button class="btn btn-large widthfit" type="submit" id="submitclosetime">Speichern</button> 
  </div>
</div>
</form>

<script>
	
	$("#addclosetime").validate({
	errorElement: 'label',
    errorClass: 'error',
	  rules: {
	   startdate: {
		required: true
	   },
	   enddate: {
			required: true	
	   }
	 },
	 messages: {
		 startdate:{
			 required:"<i class='fas fa-exclamation-circle mrm-5'></i>Startdatum muss kleiner als das Enddatum sein"
			 },
		enddate:{
			 required:"<i class='fas fa-exclamation-circle mrm-5'></i>Enddatum muss größer als das Startdatum sein"
			}	 
	   
	 },
	 errorPlacement: function (error, element) {
				var name = $(element).attr("name");
				error.appendTo($("#" + name + "_validate"));
			},
	 submitHandler: function() {
		 
		 var from =$("#datepicker122").val();
		 var to =$("#datepicker222").val();
		var start=from.split("-");
		var end=to.split("-");

		var startdate =start[2]+start[1]+start[0];
		var enddate =end[2]+end[1]+end[0];	
		if(startdate>enddate){
			$("#enddateerror").css('display','block');
			$("#enddateerror").html("<i class='fas fa-exclamation-circle mrm-5'></i>Enddatum muss größer als das Startdatum sein");
		    return false;
		}
		
	//$("#userLogin").submit();
	//return true;
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
			//var urls=$('#select_url').val();
		   $.post(base_url+"rebook/addclosetime", $( "#addclosetime" ).serialize() , function( data ) {
			  // console.log(data);
				 var obj = jQuery.parseJSON( data );
				if(obj.success=='1'){
					location.reload();
					//alert(obj.url);
					
				}else{
					$("#alert_message").html('<strong>Message ! </strong>'+obj.message);
					$(".alert_message").css('display','block');
					
					//location.reload();
				
				}
				unloading();
		});
	   return false;
	  }
	});
	
	</script>
