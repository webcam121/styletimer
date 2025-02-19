<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog" style="max-width:625px !important;">
		<div class="modal-content">
			
    <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
           
          </div>
      		<div class="modal-body">
				  <div id="success_message">
   
					 
				  </div>
        		<div class="row">
  					<div class="col-md-12 text-center">
						  <div id="image_demo" style="margin-top:30px"></div>
  					</div>
  					<div class="col-md-12 text-center">
  			              <input type="hidden" id="cropImageName" value="">
							<button class="btn btn-success crop_image" style="width: auto;">
							<?php echo $this->lang->line('crop'); ?></button>
					</div>
				</div>
      		</div>
      		
    	</div>
    </div>
</div>

<div id="uploadimageModal1" class="modal" role="dialog">
	<div class="modal-dialog" style="max-width:625px !important;">
		<div class="modal-content">
			
    <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
           
          </div>
      		<div class="modal-body">
				  <div id="success_message1">
   
					 
				  </div>
        		<div class="row">
  					<div class="col-md-12 text-center">
						  <div id="image_demo1" style="margin-top:30px"></div>
  					</div>
					<div class="display-n" id="imageLoaded"></div>
					<div class="display-n" id="galServiceId"></div>
					<div class="col-md-12">
						<div class="pt-20 px-3 mb-0">
							<p class="color333 font-size-16 fontfamily-medium"><?php echo $this->lang->line('Employee'); ?></p>
							<div class="form-group">
								<div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new bookingAllEmployee" id="">
									<span class="label" id="employeSelected"><?php echo $this->lang->line('Select_Employee'); ?></span>
									<button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn employeSelected" id="cat_btn" aria-expanded="false" style="text-transform: none !important;"></button>
									<ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="overflow: scroll;" x-placement="bottom-start" id="bookingAllEmployee">
										<?php foreach($employees as $emp) {?>
											<li class="radiobox-image ">
												<input type="radio" id="employeeId<?=$emp->id?>" name="employee_select" data-val="<?php echo $emp->first_name.' '.$emp->last_name;?>" value="<?=$emp->id?>">
												<label for="employeeId<?=$emp->id?>" class="height48v vertical-middle pt-2">
													<img class="employee-round-icon display-ib" src="<?php echo $emp->profile_pic !='' ? (base_url('assets/uploads/employee/').$emp->id.'/'.$emp->profile_pic) : (base_url('assets/frontend/images/user-icon-gret.svg'))?>">
													<?php echo $emp->first_name.' '.$emp->last_name;?>
												</label>
											</li> 
										<?php } ?>
									</ul>
								</div>
							<label class="error display-n" id="employee_err">Bitte wähle einen Mitarbeiter</label>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pt-20 px-3 mb-0">
							<p class="color333 font-size-16 fontfamily-medium"><?php echo $this->lang->line('Services'); ?></p>
							<div class="form-group">
								<div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new" id="">
									<span class="label" id="categorySelected">Service auswählen</span>
									<button disabled data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn categorySelected" aria-expanded="false" style="text-transform: none !important;"></button>
									<ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" id="subcategorylist" style="max-height: 300px !important;overflow: scroll;" x-placement="bottom-start">
									</ul>
								</div>
								<div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new display-n mt-4" id="subservicelist">
									<span class="label" id="categorySelected1">Service auswählen</span>
									<button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn categorySelected1" aria-expanded="false" style="text-transform: none !important;"></button>
									<ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" id="subservicelistul" style="overflow: scroll;" x-placement="bottom-start">
									</ul>
								</div>
							<label class="error display-n" id="category_err">Bitte wähle einen Service</label>
							</div>
						</div>
					</div>
  					<div class="col-md-12 text-center">
						<input type="hidden" id="cropImageName1" value="">
						<button class="btn btn-success crop_image1" style="width: auto;">
							Bild speichern
						</button>
					</div>
				</div>
      		</div>
      		
    	</div>
    </div>
</div>
