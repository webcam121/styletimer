<?php 
$optArr = array('5'=>'5min','10'=>'10min','15'=>'15min','20'=>'20min','25'=>'25min','30'=>'30min','35'=>'35min','40'=>'40min','45'=>'45min','50'=>'50min','55'=>'55min','60'=>'1h','65'=>'1h 5min','70'=>'1h 10min','75'=>'1h 15min','80'=>'1h 20min','85'=>'1h 25min','90'=>'1h 30min','95'=>'1h 35min','100'=>'1h 40min','105'=>'1h 45min','110'=>'1h 50min','115'=>'1h 55min','120'=>'2h','135'=>'2h 15min','150'=>'2h 30min','165'=>'2h 45min','180'=>'3h','195'=>'3h 15min','210'=>'3h 30min','225'=>'3h 45min','240'=>'4h','255'=>'4h 15min','270'=>'4h 30min','285'=>'4h 45min','300'=>'5h','315'=>'5h 15min','330'=>'5h 30min','345'=>'5h 45min','360'=>'6h'); 

 ?>
<style type="text/css">
	.fr-box.fr-basic .fr-element{
		min-height: 350px;
	}
	.modal-body .mobile1-mb-40 .fr-wrapper {
    height: 210px;
    overflow-x: auto !important;
}
#assign_user{
	text-transform: none !important;
 }
</style> 
  <div class="salon-new-top-bg pt-4">
                <div class="pl-20 pr-20 d-flex">
                  <h3 class="font-size-20 color333 fontfamily-medium mb-2"><?php echo $this->lang->line('setup_your_profile'); ?></h3>
                </div>
                <div class="alert alert-success absolute vinay top w-100 mt-15 alert_message alert-top-0 text-center <?php if(!empty($message)) echo 'messageAlert1'; else echo 'display-n';  ?>" style="top: -20px !important;">
					<!-- <button type="button" class="close ml-10" data-dismiss="alert">&times; </button> -->
				  
				   <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span id="alert_message"><?php if($message) echo $message; ?> </span>
				 </div>
                  <div class="salon-new-step">
                      <?php if(!empty($setup_no)) $data['setup_no']=$setup_no;  $this->load->view('frontend/marchant/common_setup',$data); ?>
                  </div>
              </div>
              <div class="bgwhite relative pt-4 px-3">
                <p class="color333 font-size-16 fontfamily-semibold mb-0">
					<?php echo $this->lang->line('please_add_your_services_that_will_show_up_in_your_salon_detail_page'); ?>
				</p>
				<label class="color999 fontfamily-light color333 font-size-12 mb-25">
                  <img src="<?php echo base_url('assets/frontend/images/information-button.png'); ?>" class="edit_pencil_bg_white_circle_icon1">
                  Du kannst später jederzeit noch weitere Services hinzufügen oder bestehende Services bearbeiten.
                </label>
                <form class="relative" method="post" id="add_category">
					<?php if(!empty($service->id)){ ?>
					  <input type="hidden" name="id" value="<?php echo url_encode($service->id); ?>">
					<?php } ?>
                  <div class="row">
                  <!--  <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">

                      <div class="form-group form-group-mb-50">

                        <div class="btn-group multi_sigle_select inp_select">
                              <span class="label <?php if(!empty($service->category_id)) echo "label_add_top"; ?>">Category</span>
                              <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" id="cat_btn"></button>
                          <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start">
							  <?php if(!empty($category)){
								  foreach($category as $cat){ ?>
									<li class="radiobox-image">
									  <input type="radio" id="id_cat<?php echo $cat->id; ?>" name="category" class="select_cat" data-val="<?php echo $cat->category_name; ?>" value="<?php echo $cat->id; ?>" <?php if(!empty($service->category_id) && $cat->id==$service->category_id) echo "checked" ?>>
									  <label for="id_cat<?php echo $cat->id; ?>">
										<?php echo $cat->category_name; ?>
									  </label>
									</li>
                            <?php } } ?>

                          </ul>

                       </div>
                        <label style="top:40px;" class="error" id="catgory_err"></label>
                     </div>
                    </div> -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <div class="form-group form-group-mb-50">
                       <div class="btn-group multi_sigle_select inp_select">
                              <span class="label <?php if(!empty($service->category_id)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Category'); ?></span>
                              <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" id="fcat_btn"></button>
                          <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start"
					 style="max-height: none; overflow-x: auto; max-height: 200px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
							<?php if (isset($filtercategory) && !empty($filtercategory)) {
								  foreach($filtercategory as $subcat) { ?>
									<li class="radiobox-image" style="background:#00b3bf9e; text-align:center;">
									<label for="id_fcat<?php echo $subcat->id; ?>_parent" style="color:black;">
										<?php echo $subcat->category_name; ?>
									</label>
									</li>
							<?php	foreach($subcat->sub_category as $fcat){ ?>
										<li class="radiobox-image">
										<input type="radio" id="id_fcat<?php echo $fcat['my_cat_id']; ?>" name="filtercategory" class="select_fcat" data-val="<?php echo $fcat['category_name']; ?>" value="<?php echo $fcat['my_cat_id']; ?>" <?php if(!empty($service->filtercat_id) && $fcat['my_cat_id']==$service->filtercat_id) echo "checked" ?>>
										<label for="id_fcat<?php echo $fcat['my_cat_id']; ?>">
											<?php echo $fcat['category_name']; ?>
										</label>
										</li>
                            <?php } } }?>

                          </ul>

                       </div>
                     <label style="top:40px;dispaly:block !important;" class="error" id="fcatgory_err"></label>
                   </div>
                  </div>
                    
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <div class="form-group form-group-mb-50">
                      <div class="btn-group multi_sigle_select inp_select">
                              <span class="label <?php if(!empty($service->subcategory_id)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Sub_Category'); ?></span>
                              <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" id="subCat_btn"></button>
                        <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start" id="sub_category" 
				    style="max-height: none; overflow-x: auto; max-height: 200px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
						    <?php if(!empty($subcategory)){
								foreach($subcategory as $subcat){
									$check="";
									if($subcat->id==$service->subcategory_id){
										$check="checked";
										}
									 echo '<li class="radiobox-image"><input type="radio" id="id_subcat'.$subcat->id.'" name="sub_category" data-val="'.$subcat->category_name.'" value="'.$subcat->id.'" '.$check.'><label for="id_subcat'.$subcat->id.'">'.$subcat->category_name.'</label></li>';  } }else{ ?>
										 
										 <li class="radiobox-image"><input type="radio" id="" name="" value=""><label for="id_subcat">
										 	<?php echo $this->lang->line('first_choose_category'); ?>
										 </label></li>
										 <?php } ?>

                        </ul>
                     </div>
                     <label style="top:40px;dispaly:block !important;" class="error" id="subcatgory_err"></label>
                   </div>
                  </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  	
                    <div class="form-group mobile1-mb-40" id="name_validate">
                         <label class="inp">
                           <input type="text" placeholder="&nbsp;" value="<?php if(!empty($service->name)) echo $service->name; ?>" name="name" class="form-control">
                           <span class="label"><?php echo $this->lang->line('Service_servicename'); ?></span>
                         </label>
                
                 </div>
                </div>
                  <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="form-group mobile1-mb-40">
                      <div class="btn-group multi_sigle_select inp_select">
                              <span class="label <?php if(!empty($assigned_user)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Assigned_To'); ?></span>
                              <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" id="assign_user"></button>
                        <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll"  
				    style="max-height: none; overflow-x: auto; max-height: 100px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
					      <?php if(!empty($users)){
							  foreach($users as $user){ ?>
                          <li class="checkbox-image">
                            <input type="checkbox" id="id_users<?php echo $user->id; ?>" name="assigned_users[]" class="" data-val="<?php echo $user->first_name." ".$user->last_name; ?>" value="<?php echo $user->id; ?>" <?php if(!empty($assigned_user) && in_array($user->id,$assigned_user)) echo "checked"; ?>>
                            <label for="id_users<?php echo $user->id; ?>"><img class="employee-round-icon" src="<?php if(!empty($user->profile_pic)) echo base_url('assets/uploads/employee/'.$user->id.'/'.$user->profile_pic); else echo base_url('assets/frontend/images/user-icon-gret.svg'); ?>"><?php echo $user->first_name." ".$user->last_name; ?></label>
                          </li>
                          <?php } }else echo '<li class="checkbox-image" style="margin: 10px 0px 0px 10px;font-size: 14px;color: rgba(4, 4, 5, 0.42);">kein Mitarbeiter verfügbar</li>'; if(!empty($assigned_user)){ $user_assign=implode(',',$assigned_user); ?>
						     <input type="hidden" name="old_assined_user" value="<?php echo $user_assign; ?>">
						  <?php } ?>

                        </ul>
                    </div>
                    <span class="error asign_employee_err"></span>
                  </div>
                  </div>
                
               <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<div class="row">
						<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7" >
							<div class="row">
								<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 pr-0" >
									<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Standard_Price'); ?></label>
									<div class="relative form-group-mb-50" id="">
										<div class="input-group ">
											<div class="input-group-prepend ">
												<span class="input-group-text bge8e8e8">€</span>
											</div>
											<input type="text" id="standardprice" name="price" value="<?php if(!empty($service->price)) echo $service->price; ?>"  class="form-control" placeholder="">
										</div>
										<label style="top:40px;" class="error" id="standardprice_err"></label>
									</div>
								</div>

								<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 pr-0" >
									<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Discounted_Price'); ?></label>
									<div class="relative form-group-mb-50" id="discount_price_validate">
										<div class="input-group ">
											<div class="input-group-prepend ">
												<span class="input-group-text bge8e8e8">€</span>
											</div>
											<input type="text" name="discount_price" value="<?php if(!empty($service->discount_price)) echo $service->discount_price; ?>"  class="form-control discount_price" id="discount_price" placeholder="">
										</div>
										<label  class="error" id="discount_price_valid"></label>
									</div>
								</div>
						 	 </div>
						</div>
						<div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5" >
							<div class="relative form-group form-group-mb-50">
								<span class="color999 fontfamily-light font-size-12 mb-1 d-block">Preisart</span>
								<div class="btn-group multi_sigle_select inp_select">									
									<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" style="text-transform: none !important;">Festpreis</button>
									<ul class="dropdown-menu mss_sl_btn_dm" x-placement="bottom-start" id="" 
									>
										<li class="radiobox-image">
											<input type="radio" id="id_ad1" name="price_start_option" value="ab" <?php if(!empty($service->price_start_option) && $service->price_start_option=='ab') echo 'checked'; ?>>
											<label for="id_ad1">ab</label>
										</li>
										<li class="radiobox-image">
											<input type="radio" id="id_festprice1" name="price_start_option" value="Festpreis" <?php if(!empty($service->price_start_option) && $service->price_start_option=='Festpreis') echo 'checked'; else if(empty($service->price_start_option)) echo 'checked'; ?>><label for="id_festprice1">Festpreis</label>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pl-0 pr-0">
						<div class="form-group form-group-mb-50">
							<div class="btn-group multi_sigle_select inp_select">
								<span class="label label_add_top"><?php echo $this->lang->line('tex_include'); ?></span>
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" id="changeTaxTxt"></button>
								<ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
								style="max-height: none; overflow-x: auto; max-height: 100px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">									
									<li class="radiobox-image">
										<input type="radio" id="id_textno10" name="tax" value="notax" data-text="notax" <?php if(!empty($service->id) && empty($service->tax_id)) echo "checked"; ?>>
										<label for="id_textno10"><?php echo $this->lang->line('no_tax'); ?></label>
									</li>
							    
							<?php if(!empty($taxes))
									{ foreach($taxes as $tax)
									  { ?>
										<li class="radiobox-image">
											<input type="radio" id="id_text10<?php echo $tax->id; ?>"  name="tax" data-text="<?php If($tax->defualt==1) echo "Standard: "; echo  $tax->tax_name.' ('.price_formate($tax->price).'%)'; ?>"; value="<?php echo url_encode($tax->id); ?>" <?php If(($tax->defualt==1 && empty($service->id)) || (!empty($service->id) && $service->tax_id==$tax->id)) echo 'checked'; ?>>
											<label for="id_text10<?php echo $tax->id; ?>"><?php  If($tax->defualt==1) echo "Standard: "; echo $tax->tax_name.' ('.price_formate($tax->price).'%)'; ?></label>
										</li>
							
							 <?php  }  } ?>	
									
								</ul>
							</div>
							<label style="top:40px;" class="error" ></label>
						</div>
					</div>
				
				</div>
				<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">

					<div class="row procsstimediv <?php if(!empty($service->id) && $service->type==1) echo 'display-n'; ?>" id="withoutProcessTime0">
						<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" >
						<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Standard_Duration'); ?></label>

                         <div class="form-group mb-0">
								<div class="btn-group multi_sigle_select">
									<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idopt01" style="">1h</button>
									<ul class="dropdown-menu mss_sl_btn_dm scroll-effect height240" 
									style="max-height: none; overflow-x: auto; max-height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
										<?php 
																				
										foreach($optArr as $optk=>$optv){ ?>
										 
										 <li class="radiobox-image">
										   <input type="radio" id="idopt01<?php echo $optk; ?>" class="timeDropDown" data-c="idopt01" data-text="<?php echo $optv; ?>" name="duration" value="<?php echo $optk; ?>" <?php if(!empty($service->duration) && $service->duration==$optk || empty($service->duration) && 60==$optk) echo 'checked'; ?>><label for="idopt01<?php echo $optk; ?>"><?php echo $optv; ?></label>
										 </li>   
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" >
							<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Buffer_Time'); ?></label>

                           <div class="form-group mb-0">
								<div class="btn-group multi_sigle_select">
									<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptb02" style=""><?php echo $this->lang->line('no_buffer'); ?></button>

									<ul class="dropdown-menu mss_sl_btn_dm scroll-effect height240" 
									style="max-height: none; overflow-x: auto; max-height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
										

										<li class="radiobox-image">
										<input type="radio" id="idoptb020" class="timeDropDown" data-c="idoptb02" data-text="no buffer" name="buffer_time" value="0" <?php if(!empty($service->buffer_time) && $service->buffer_time==0) echo 'checked'; ?>><label for="idoptb020"><?php echo $this->lang->line('no_buffer'); ?></label>
										 </li>
										<?php foreach($optArr as $optk=>$optv){ ?>
										
											 <li class="radiobox-image">
										   <input type="radio" id="idoptb02<?php echo $optk; ?>" class="timeDropDown" data-c="idoptb02" data-text="<?php echo $optv; ?>" name="buffer_time" value="<?php echo $optk; ?>" <?php if(!empty($service->buffer_time) && $service->buffer_time==$optk) echo 'checked'; ?>><label for="idoptb02<?php echo $optk; ?>"><?php echo $optv; ?></label>
										 </li>   
										<?php } ?>

									</ul>
								</div>
							</div>
							
						</div>
					</div>

					<div class="row procsstimediv <?php if(!empty($service->id) && $service->type ==1) echo ''; else echo 'display-n'; ?>" id="withProcessTime0">
						<div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 pr-2" >
						<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('working_time'); ?></label>

              	          <div class="form-group mb-0">
								<div class="btn-group multi_sigle_select">
									<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptexp04" style="">15 min</button>
									<ul class="dropdown-menu mss_sl_btn_dm scroll-effect height240"
									 style="max-height: none; overflow-x: auto; max-height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
									<?php foreach($optArr as $optk=>$optv)
									        { ?>
										 
											 <li class="radiobox-image">
											   <input type="radio" id="idoptexp04<?php echo $optk; ?>" class="timeDropDown" data-c="idoptexp04" data-text="<?php echo $optv; ?>" name="setuptime" value="<?php echo $optk; ?>" <?php if(!empty($service->setuptime) && $service->setuptime==$optk || empty($service->setuptime) && 15==$optk) echo 'checked'; ?>><label for="idoptexp04<?php echo $optk; ?>"><?php echo $optv; ?></label>
											 </li>   
									 <?php   } ?>                   
									</ul>
								</div>
							</div>
              
						</div>
						<div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 pl-2 pr-2" >
							<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('exposure'); ?></label>
								<div class="form-group mb-0">
								<div class="btn-group multi_sigle_select">
									<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptexp04" style="">15 min</button>
									<ul class="dropdown-menu mss_sl_btn_dm scroll-effect height240"
									style="max-height: none; overflow-x: auto; max-height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);" >
									<?php foreach($optArr as $optk=>$optv)
									        { ?>
										 
											 <li class="radiobox-image">
											   <input type="radio" id="idoptexp04<?php echo $optk; ?>" class="timeDropDown" data-c="idoptexp04" data-text="<?php echo $optv; ?>" name="processtime" value="<?php echo $optk; ?>" <?php if(!empty($service->setuptime) && $service->setuptime==$optk || empty($service->setuptime) && 15==$optk) echo 'checked'; ?>><label for="idoptexp04<?php echo $optk; ?>"><?php echo $optv; ?></label>
											 </li>   
									 <?php   } ?>                   
									</ul>
								</div>
								<label class="error" id="processtime0"></label>
							</div>

						</div>
						<div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 pl-2" >
							<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Complete'); ?></label>
							<div class="form-group mb-0">
								<div class="btn-group multi_sigle_select">
									<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptfin05" style="">15 min</button>
									<ul class="dropdown-menu mss_sl_btn_dm scroll-effect height240" style="max-height: none; overflow-x: auto; max-height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
									<?php foreach($optArr as $optk=>$optv)
									        { ?>
										 
											 <li class="radiobox-image">
											   <input type="radio" id="idoptfin05<?php echo $optk; ?>" class="timeDropDown" data-c="idoptfin05" data-text="<?php echo $optv; ?>" name="finishtime" value="<?php echo $optk; ?>" <?php if(!empty($service->setuptime) && $service->setuptime==$optk || empty($service->setuptime) && 15==$optk) echo 'checked'; ?>><label for="idoptfin05<?php echo $optk; ?>"><?php echo $optv; ?></label>
											 </li>   
									 <?php   } ?>             
									</ul>
								</div>
								<label class="error" id="finishtime0"></label>
							</div>

						</div>
						
					<span class="fontsize-12 lineheight12 color-333 display-ib pl-3 mt-1 pr-3" style="margin-top:0.75rem !important;"><?php echo $this->lang->line('the_exposure'); ?> </span>
					
					</div>
					<div class="checkbox mt-1 mb-2 display-ib" style="margin-top:0.75rem !important;">
						<label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="proccess_time" value="yes" class="addProcceesTime" id="addProcceesTime" data-count="0" <?php if(!empty($service->id) && $service->type==1) echo 'checked'; ?>>
						  <span class="cr"><i class="cr-icon fa fa-check"></i></span>
						  <?php echo $this->lang->line('add_exposure'); ?>
						</label>
					</div> 
				</div>

                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="form-group mobile1-mb-40">
                          <p class="font-size-18 color333 fontfamily-medium"><?php echo $this->lang->line('Service-Detail'); ?></p>
                           <textarea placeholder="&nbsp;" name="detail" id="about_salon" class="form-control h-100"><?php if(!empty($service->service_detail)) echo $service->service_detail; ?></textarea>
                         
                     </div>
                  </div>
                   <!-- add online booking -->
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  	<div class="vertical-bottom mb-20">
	                   <label class="switch mr-2" for="vcheckbox15" style="top:8px">
		                    <input type="checkbox" id="vcheckbox15" name="check_online_option" <?php if(!empty($service->online) && $service->online==1 || empty($service)) echo 'checked'; else echo ''; ?>>
		                    <div class="slider round"></div>
		                 </label>
		                 <p class="color333 fontfamily-medium font-size-14 display-ib"><?php echo $this->lang->line('Service_Name'); ?></p>
		             </div>
                  </div>

                </div>
                <div class="relative" id="add_more_section">

        </div>
        <div class="text-center border-w3 border-radius4 pt-2 pb-2">
          <a class="cursor_pointer relative color333 a_hover_333 fontfamily-medium font-size-16 addmoreservice" data-count="1" style="cursor:pointer;color:#333 !important">
            <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line('Add-More-Service'); ?> </a>
        </div>
        <?php if(!empty($offer_check)){
                        $cls_css='';
                        $cls_div='select-day-time collapse show';
                      }
                      else{
                        $cls_css='collapsed';
                        $cls_div='collapse select-day-time';
                      }
                 ?>
              <div class="relative mb-30 mt-30">
              <div class="relative bgwhite border-radius4 toggle-parent-date-time">

                <a href="#"  class="colorcyan fontfamily-medium font-size-16 a_hover_cyan w-100 bgwhite display-b pl-3 pr-3 pt-10 pb-10 border-radius4 select-day-time-click border-w4 <?php echo $cls_css; ?>" data-toggle="collapse" data-target=".select-day-time"><?php echo $this->lang->line('select_day_time'); ?> <span class="ml-auto toggle-cyan-round"><img src="<?php echo base_url('assets/frontend/'); ?>images/down-arrow-white.svg" class="down-arrow-white"></span></a>

                <div class="around-25 collapse select-day-time <?php echo $cls_div; ?>" id=" ">
                    <div class="row mt-0">
                         <?php //$days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday', 'Sunday');
                    $ii=0;
                    if(!empty($days_array)){
                    foreach($days_array as $day){ ?>
            
								<div class="d-flex w-100">
									<div class="checkbox-btn display-ib">
									  <label class="/font-size-14 fontfamily-medium">
										<input type="checkbox" <?php if(isset($offer[$ii]->starttime) && $offer[$ii]->starttime !=''){ echo 'checked="checked"';} ?> name="days[]" class="checkbox" value="<?php echo strtolower($day->days); ?>">
										  <span class="squer-chack">
											<span class="squer-chacked"><?php echo strtoupper(substr($this->lang->line(ucfirst($day->days)),0,1)); ?></span>
										  </span>
									  </label>
									  <span id="chk_<?php echo strtolower($day->days); ?>" class="error"></span>
									  <p class="color333 fontfamily-light font-size-12 display-ib ml-10 mb-0 overflow_elips" style="white-space: pre-line;"> <?php echo $this->lang->line('text_between_'.strtolower($day->days)); ?></p>
									</div>
									<div class="ml-auto">
									<div class="display-ib mr-20 ml-30 width160">
										<div class="form-group form-group-mb-50">
								<div class="btn-group multi_sigle_select inp_select v_inp_new">
											<span class="label <?php if(!empty($offer[$ii]->endtime)) echo 'label_add_top'; ?>"><?php echo $this->lang->line('Start_Time'); ?></span>
											<button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false"><?php if(!empty($offer[$ii]->starttime)) echo date('H:i',strtotime($offer[$ii]->starttime));  ?></button>
											<ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
											style="max-height: none; overflow-x: auto; max-height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
								<?php if(!empty($day->starttime) && !empty($day->endtime))
								{
								$tStart = strtotime($day->starttime);
								$tEnd = strtotime($day->endtime);
								$tNow = $tStart;
								while($tNow <= $tEnd){ ?>
								<li class="radiobox-image">
								<input type="radio" id="id_time<?php echo strtolower($day->days); echo $tNow; ?>" name="<?php echo strtolower($day->days); ?>_start" class="start_<?php echo strtolower($day->days); ?>" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php if(!empty($offer[$ii]->starttime) && $offer[$ii]->starttime==date("H:i:s",$tNow)) echo "checked"; ?>>
								<label for="id_time<?php echo strtolower($day->days); echo $tNow; ?>">
								<?php echo date("H:i",$tNow) ?>                   
								</label>
								</li> 
								<?php $tNow = strtotime('+30 minutes',$tNow); } } ?>    
												  
											</ul>
										 </div>
								<span id="Serr_<?php echo strtolower($day->days); ?>" class="error"></span>

                                
                                </div>
                            </div>
                            <div class="display-ib width160">
                                <div class="form-group form-group-mb-50">
                         
                                  <div class="btn-group multi_sigle_select inp_select v_inp_new">
                                    <span class="label <?php if(!empty($offer[$ii]->endtime)) echo 'label_add_top'; ?>"><?php echo $this->lang->line('End_Time'); ?></span>
                                    <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false"><?php if(!empty($offer[$ii]->endtime)) echo date('H:i',strtotime($offer[$ii]->endtime)); ?></button>                                
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="max-height: none; overflow-x: auto; max-height: 320px !important;
                                     position: absolute; will-change: transform; top: 0px; left: 0px;
                                      transform: translate3d(0px, -158px, 0px);">
                                     <?php if(!empty($day->starttime) && !empty($day->endtime))
											   {
											  $tStart = strtotime($day->starttime);
											  $tEnd = strtotime($day->endtime);
											  $tNow = $tStart;
											while($tNow <= $tEnd){ ?>
												<li class="radiobox-image">
												<input type="radio" id="endid_time<?php echo strtolower($day->days); echo $tNow; ?>" name="<?php echo strtolower($day->days); ?>_end" class="end_<?php echo strtolower($day->days); ?>" data-val="" value="<?php echo date("H:i:s",$tNow) ?>" <?php if(!empty($offer[$ii]->endtime) && $offer[$ii]->endtime==date("H:i:s",$tNow)) echo "checked"; ?>>
												<label for="endid_time<?php echo strtolower($day->days); echo $tNow; ?>">
												<?php echo date("H:i",$tNow) ?>                   
												</label>
												</li> 
											<?php $tNow = strtotime('+30 minutes',$tNow); } } ?>                         
															</ul>
														 </div>


														  <span id="Eerr_<?php echo strtolower($day->days); ?>" class="error"></span>
														</div>
													</div>
													</div>
												</div>
            
            
            

                              <?php $ii++; } } ?>

                        </div>

                   </div>
                </div>
            </div>


               <input type="hidden" name="saveoption" id="addServiceSub" value="next">
				<div class="row">
	              	 <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
	                   <div class="pt-3 pb-3 bgwhite boxshadow-5 px-3 mt-4">
	                       <button type="button" onclick="getAddEmployeehtml();" class="btn btn-border-orange btn-large widthfit2 ml-0"><?php echo $this->lang->line('Previous'); ?></button>	                     
	                       <button type="button" class="btn btn-large widthfit2 float-right addServiceSub" data-val="another" style="margin-left: 14px;"><?php echo 'weiteren Service hinzufügen'; ?></button>
	                      <button type="button" class="btn btn-large widthfit2 float-right addServiceSub" data-val="next"><?php echo $this->lang->line('SaveProceed'); ?></button>
	                      
	                   </div>
                    </div>
				</div>
                </form>
              </div>

<script>


/*$("#add_category").validate({
	errorElement: 'label',
    errorClass: 'error',
  rules: {
   price:{ required: true ,
	       number:true 
	       }
},
 messages: {
 
   price:{ required:"<i class='fas fa-exclamation-circle mrm-5'></i>Price is required",
	   number:"<i class='fas fa-exclamation-circle mrm-5'></i>Enter a valid number" },
   
 }, 
 errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        }

});
*/

</script>
