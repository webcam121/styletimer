<?php $this->load->view('frontend/common/header'); ?>
<style>
/* .alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0 {
    top: -40px !important;
} */
</style>
    <div class="d-flex pt-84">        
		<?php $this->load->view('frontend/common/sidebar'); ?>
        

        <div class="right-side-dashbord w-100 pl-30 pr-30">
        
        	<div class="mb-60 mt-4">
	        	<div class="bgwhite relative">
            <?php $this->load->view('frontend/common/alert'); ?>
              <!-- <a href="<?php echo base_url("profile/edit_marchant_profile"); ?>" class="colorcyan a_hover_cyan font-size-14 display-b mb-4 "><i class="fas fa-chevron-left"></i> Back to Settings</a> -->


              <div class="d-flex justify-content-between align-items-end p-3 border-b">
                <div class="relative">
                  <h3 class="font-size-24 color333 fontfamily-semibold"><?php echo $this->lang->line('Taxes'); ?></h3>
                  <p class="font-size-14 colorgray fontfamily-regular mb-0"><?php echo $this->lang->line('Add_your_tax'); ?> </p>
                </div>
                <div class="">
                  <button class="btn widthfit" data-toggle="modal" data-target=".add-new-tax" data-backdrop="static" data-keyboard="false"><?php echo $this->lang->line('New_tax'); ?></button>
                </div>
              </div>
            
              <div class="relative mt-4 p-3 min-height60vh">
				    
                <!-- <h5 class="fontfamily-medium font-size-18 color333">Tax Rates</h5> -->

                <div class="row ">
					
				<!--  <?php if(!empty($taxes)){
					      foreach($taxes as $row){ ?>	
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                  <div class="d-flex justify-content-between align-items-center py-3 px-4 border-w border-radius4 tax-box-hover mb-3" >
                    <div class="relative w-100 editTax"  data-id="<?php echo url_encode($row->id); ?>" data-name="<?php echo $row->tax_name; ?>"  data-rate="<?php echo $row->price; ?>"   >
                      <span class="font-szie-14 color333 fontfamily-semibold display-b capitalize"><?php echo $row->tax_name; ?></span>
                      <span class="font-szie-14 color666 fontfamily-medium display-b"><?php echo round($row->price,2); ?>% <?php if($row->defualt==1){ ?><span class="default-tax"><i>Default</i></span> <?php } ?></span>
                    </div>
                    <div class="dropdown">
                      <div class="tax-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                      </div>
                      <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                        <li class="px-13 py-2">
                          <a href="#" class="color333 font-size-14 fontfamily-regular editTax display-b" data-id="<?php echo url_encode($row->id); ?>" data-name="<?php echo $row->tax_name; ?>"  data-rate="<?php echo $row->price; ?>"   data-toggle="modal" data-target=".edit-tax" data-backdrop="static" data-keyboard="false">Edit</a>
                        </li>
                        <?php if($row->defualt !=1){ ?>
                        <li class="px-13 py-2">
                          <a href="#" data-tid="<?php echo url_encode($row->id); ?>" class="deleteTax color333 font-size-14 fontfamily-regular display-b">Delete</a>
                        </li>
                        
                        <li class="px-13 py-2">
                          <a href="<?php echo base_url("taxes/set_default/".url_encode($row->id)); ?>" class="color333 font-size-14 fontfamily-regular display-b">Set as Default</a>
                        </li>
                        <?php  } ?>
                      </ul>
                    </div>
                  </div>
                </div>
                  
                  <?php } } ?>  -->
  

                <div class="my-table service-list-table col-12 col-sm-12">
                <table class="table">
                    <thead>
                        <tr>
                          <th class="text-center"><?php echo $this->lang->line('Tax'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Taxrate'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Default'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Action'); ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                  
               <?php if(!empty($taxes)){
                 foreach($taxes as $row){ ?>  
                        <tr>
                          <td class="text-center"><?php echo $row->tax_name; ?></td>
                          <td class="text-center"><?php echo round($row->price,2); ?>% </td>
                          <td class="text-center"><?php if($row->defualt==1){ ?><span class="default-tax"><i><?php echo $this->lang->line('Default'); ?></i></span> <?php } ?></td>
                          <td class="text-center">
                            <div class="dropdown">
                              <div class="" data-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/table-more-icon.svg'); ?>">
                          </div>
                              <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                                <li class="px-13 py-2">
                                  <a href="#" class="color333 font-size-14 fontfamily-regular editTax display-b" data-id="<?php echo url_encode($row->id); ?>" data-name="<?php echo $row->tax_name; ?>"  data-rate="<?php echo $row->price; ?>"   data-toggle="modal" data-target=".edit-tax" data-backdrop="static" data-keyboard="false"><?php echo $this->lang->line('Edit'); ?></a>
                                </li>
                                <?php if($row->defualt !=1){ ?>
                                <li class="px-13 py-2">
                                  <a href="#" data-tid="<?php echo url_encode($row->id); ?>" class="deleteTax color333 font-size-14 fontfamily-regular display-b"><?php echo $this->lang->line('Delete'); ?></a>
                                </li>
                                
                                <li class="px-13 py-2">
                                  <a href="<?php echo base_url("taxes/set_default/".url_encode($row->id)); ?>" class="color333 font-size-14 fontfamily-regular display-b"><?php echo $this->lang->line('Set_as_Default'); ?></a>
                                </li>
                                <?php  } ?>
                              </ul>
                            </div>
                          </td>
                        </tr>
                        <?php }
                      } ?>
                      </tbody>
                </table>
                </div>
              </div>
                  
            </div>
	        </div>
          <!-- dashboard right side end -->       
        </div>
      </div>


  



    <!-- page-modal -->
    <div class="modal fade" id="TaxListing">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
        <div class="modal-content text-left">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0 relative">
          <?php $this->load->view('frontend/common/alert'); ?>
              <div class="d-flex justify-content-between align-items-end p-3">
                <div class="relative">
                  <h3 class="font-size-24 color333 fontfamily-semibold"><?php echo $this->lang->line('Taxes'); ?></h3>
                  <p class="font-size-14 colorgray fontfamily-regular mb-0"><?php echo $this->lang->line('Add_your_tax'); ?> </p>
                </div>
                <div class="">
                  <button class="btn widthfit" data-toggle="modal" data-target=".add-new-tax" data-backdrop="static" data-keyboard="false" data-dismiss="modal"><?php echo $this->lang->line('New_tax'); ?></button>
                </div>
              </div>

              <div class="my-table service-list-table col-12 col-sm-12">
                <table class="table">
                    <thead>
                        <tr>
                          <th class="text-center"><?php echo $this->lang->line('Tax'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Taxrate'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Default'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Action'); ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                  
               <?php if(!empty($taxes)){
                 foreach($taxes as $row){ ?>  
                        <tr>
                          <td class="text-center"><?php echo $row->tax_name; ?></td>
                          <td class="text-center"><?php echo round($row->price,2); ?>% </td>
                          <td class="text-center"><?php if($row->defualt==1){ ?><span class="default-tax"><i><?php echo $this->lang->line('Default'); ?></i></span> <?php } ?></td>
                          <td class="text-center">
                            <div class="dropdown">
                              <div class="" data-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/table-more-icon.svg'); ?>">
                          </div>
                              <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                                <li class="px-13 py-2">
                                  <a href="#" class="color333 font-size-14 fontfamily-regular editTax display-b" data-id="<?php echo url_encode($row->id); ?>" data-name="<?php echo $row->tax_name; ?>"  data-rate="<?php echo $row->price; ?>"   data-toggle="modal" data-target=".edit-tax" data-backdrop="static" data-keyboard="false"><?php echo $this->lang->line('Edit'); ?></a>
                                </li>
                                <?php if($row->defualt !=1){ ?>
                                <li class="px-13 py-2">
                                  <a href="#" data-tid="<?php echo url_encode($row->id); ?>" class="deleteTax color333 font-size-14 fontfamily-regular display-b"><?php echo $this->lang->line('Delete'); ?></a>
                                </li>
                                
                                <li class="px-13 py-2">
                                  <a href="<?php echo base_url("taxes/set_default/".url_encode($row->id)); ?>" class="color333 font-size-14 fontfamily-regular display-b"><?php echo $this->lang->line('Set_as_Default'); ?></a>
                                </li>
                                <?php  } ?>
                              </ul>
                            </div>
                          </td>
                        </tr>
                        <?php }
                      } ?>
                      </tbody>
                </table>
                </div>
         
          </div>
        </div>
      </div>
    </div>
    <!-- page modal -->

    <!-- modal start -->
    <div class="modal fade add-new-tax">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-left">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0">
            <h3 class="color333 fontfamily-medium font-size-24 border-b pb-3 text-center"><?php echo $this->lang->line('New_Tax'); ?></h3>
            <div class=" pl-3 pr-3 pt-2">
              <p class="font-size-14 colorgray fontfamily-regular"><?php echo $this->lang->line('set_the_tax'); ?></p>
             <form id="addTaxform" method="post" action="<?php echo base_url("taxes/add"); ?>">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-6">
                  <div class="form-group" id="name_validate">
                    <span class="label"><?php echo $this->lang->line('Edit_name'); ?></span>
                      <label class="inp">
                        <input type="text" name="name" placeholder="&nbsp;" class="form-control" autocomplete="off">
                      </label>                                                
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-6">
                  <div class="form-group" id="rate_validate">
                    <span class="label"><?php echo $this->lang->line('Tax_rate'); ?></span>
                      <label class="inp">
                        <span class="rate-fixed">%</span>
                        <input type="text" name="rate" placeholder="&nbsp;" class="form-control" autocomplete="off">
                      </label>                                                
                  </div>
                </div>
              </div>
                <button type="submit" class=" btn btn-blue btn-large mt-4"><?php echo $this->lang->line('Save_btn'); ?></button>
               </form>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade edit-tax" id="editTax">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-left">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0">
            <h3 class="color333 fontfamily-medium font-size-24 border-b pb-3 text-center"><?php echo $this->lang->line('Edit_Tax'); ?>
            </h3>
            <div class=" pl-3 pr-3 pt-2">
              <p class="font-size-14 colorgray fontfamily-regular"><?php echo $this->lang->line('set_the_tax'); ?></p>
             <form method="post" id="editTaxes" action="<?php echo base_url('taxes/edit'); ?>">
             <input type="hidden" name="eid" id="eid" value="">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-6">
                  <div class="form-group" id="ename_validate">
                    <span class="label"><?php echo $this->lang->line('Tax_name'); ?></span>
                      <label class="inp">
                        <input type="text" name="ename" id="ename" placeholder="&nbsp;" class="form-control" value="">
                      </label>                                                
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-6">
                  <div class="form-group" id="erate_validate">
                    <span class="label"><?php echo $this->lang->line('Tax_rate'); ?></span>
                      <label class="inp">
                        <span class="rate-fixed">%</span>
                        <input type="text" name="erate" id="erate" placeholder="&nbsp;" class="form-control" value="">
                      </label>                                                
                  </div>
                </div>
              </div>
                <button type="submit" class=" btn btn-blue btn-large mt-4"><?php echo $this->lang->line('Save_btn'); ?></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->
<?php $this->load->view('frontend/common/footer_script'); ?>








<script>
$('#TaxListing').modal('show'); 

 $(document).on('click','.editTax',function(){
     $("#eid").val($(this).attr('data-id'));	
     $("#erate").val($(this).attr('data-rate'));	
     $("#ename").val($(this).attr('data-name'));	
     
    $("#editTax").modal('show'); 
	
  });
 $(document).on('click','.deleteTax',function(){
	       
	        var taxid = $(this).attr('data-tid');
	        
                          Swal.fire({
								  title: '<?php echo $this->lang->line("are_you_sure"); ?>',
								  text: "You want to delete this tax!",
								  type: 'warning',
								  showCancelButton: true,
								  reverseButtons:true,
								  confirmButtonColor: '#3085d6',
								  cancelButtonColor: '#d33',
								  confirmButtonText: 'Bestätigen'
								}).then((result) => {
								if(result.value) {									
									
									loading();
																		
										$.ajax({
										   type: "POST",
										   url:base_url+"taxes/delete",
										   data:'tid='+taxid,
										   success: function (response) {
											  unloading();
												var obj = $.parseJSON( response );
												if(obj.success==1){
													  
                                                   location.reload();
														    
												}else{
													
													Swal.fire(
														  'Try agian',
														  'warning'
														);
													
												}
												
										   }
									   });
									  
									
								  }else{
									  return false;
									  }
								});
	});
</script>
