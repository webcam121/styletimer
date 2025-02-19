<?php $this->load->view('frontend/common/head'); ?>
<style>
#imgerror{
	left:0px;
	right:0px;
	margin-bottom:5px;
	}
</style>
<section class="login_register_sections clear">
    <div class="pl-15 pr-15">
        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 align-self-center login_register_main_left_block">
              <div class="row">
                   <div class="login_register_main_left_block_overlay around-50">
                        <div class="relative ">
                             <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/frontend/images/footer_logo_icon.png'); ?>" class="lrmlbo_logo_icon" /></a>
                        </div>
                        
                   </div>
              </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 m-auto d-flex height-vh justify-content-center">
              <div class="relative align-self-center d-flex flex-row login_register_main_right_block w-100" id="lrmrb_scroll">
                <div class="relative align-self-center w-100">
                    <h2 class="font-size-36 color333 fontfamily-medium mb-10 text-center">Add Employee</h2>
                    <p class="mb-20 font-size-14 fontfamily-regular color666 text-center">You need to add atlease one employee for your booking management.</p>
                    <div class="relative login_register_form_block">
                         <form id="Frmemployee" method="post" action="<?php echo base_url('merchant/addemployee'); ?>" enctype="multipart/form-data">
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="relative text-center form-group form-group-mb-50">
                                             <div class="relative mb-60">
                                                  <img id="EmpProfile" src="<?php echo base_url('assets/frontend/images/default_add_employee_img1.svg'); ?>" class="add_employee_uploaded_img border-radius50 m-auto">    
                                                  <div class="add_employee_camera_icon_block">
                                                       <label class="all_type_upload_file">
                                                            <img src="<?php echo base_url('assets/frontend/images/add_employee_camera_icon1.svg'); ?>" class="add_employee_camera_icon border-radius50">
                                                            <input type="file" id="profile_img" name="profile_img">
                                                       </label>
                                                  </div> 
                                             </div>
                                              <label class="error_label " id="imgerror"></label>                                          
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="first_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="first_name" name="first_name">
                                               <span class="label">First Name *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="last_name_validate">
                                             <label class="inp">
                                               <input type="text" id="last_name" name="last_name" placeholder="&nbsp;" class="form-control">
                                               <span class="label">Last Name *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="telephone_validate">
                                             <label class="inp">
                                               <input type="text" id="telephone" name="telephone" placeholder="&nbsp;" class="form-control onlyNumber">
                                               <span class="label">Telephone *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="email_validate">
                                             <label class="inp">
                                               <input type="text" id="email" name="email" placeholder="&nbsp;" class="form-control">
                                               <span class="label">Email *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>  
                             <div class="row">
                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									  <div class="form-group mobile-mb-44" id="password_validate">
										 <label class="inp">
										   <input type="password" placeholder="&nbsp;" class="form-control" id="password" autocomplete="off" name="password" value="">
										   <span class="label">Password </span>
										 </label>                                                
									 </div>
									</div>

									<div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									  <div class="form-group mobile-mb-44" id="cpassword_validate">
										 <label class="inp">
										   <input type="password" placeholder="&nbsp;" name="cpassword" class="form-control" autocomplete="off" value="">
										   <span class="label">Confirm password</span>
										 </label>                                                
									 </div>
									</div>
                             </div>                         
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                            <button type="submit" id="subMyemp" name="subEmps" class="btn width250">Get Started</button>                                            
                                       </div>
                                  </div>
                              </div>
                               <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">

                                            <span class="color333 fontsize-14 fontfamily-regular"> </span>
                                            <?php if(empty($this->session->userdata('st_userid'))){ ?><a href="<?php echo base_url('merchant/thankyou'); ?>" class="fontfamily-regular colororange a_hover_orange fontsize-14 mtm-10 display-ib">Skip >></a> <?php } ?>

                                        </div>
                                  </div>
                              </div>
                         </form>
                    </div>
                  </div>  
              </div>
            </div>
        </div>
    </div>
  </section>
  <?php $this->load->view('frontend/common/footer_script'); ?>
