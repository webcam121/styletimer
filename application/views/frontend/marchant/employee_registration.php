<?php $this->load->view('frontend/common/head'); ?>
 <section class="login_register_sections clear">
    <div class="pl-15 pr-15">
        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 align-self-center login_register_main_left_block">
              <div class="row">
                   <div class="login_register_main_left_block_overlay around-50">
                        <div class="relative ">
                             <img src="<?php echo base_url('assets/frontend/images/footer_logo_icon.png'); ?>" class="lrmlbo_logo_icon" />
                        </div>
                        
                   </div>
              </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-7 col-sm-12 col-12 m-auto d-flex height-vh justify-content-center">
              <div class="relative align-self-center d-flex flex-row login_register_main_right_block w-100" id="lrmrb_scroll">
                <div class="relative align-self-center w-100">
                    <h2 class="font-size-36 color333 fontfamily-medium mb-60 text-center">Register as Employee</h2>
                    <div class="relative login_register_form_block">
                         <form id="emp_registration" method="post" 
                         action="<?php echo base_url('auth/employee_register'); ?>">
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="first_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="first_name" name="first_name" value="<?php echo isset($userdetail->first_name)?$userdetail->first_name:''; ?>">
                                               <span class="label">First Name *</span>
                                              <!--  <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="last_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="last_name" name="last_name" value="<?php echo isset($userdetail->last_name)?$userdetail->last_name:''; ?>">
                                               <span class="label">Last Name *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="telephone_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="telephone" name="telephone" value="<?php echo isset($userdetail->mobile)?$userdetail->mobile:''; ?>">
                                               <span class="label">Telephone *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="email_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" disabled value="<?php echo isset($userdetail->email)?$userdetail->email:''; ?>">
                                               <span class="label">Email *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>                           
                            <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group" id="password_validate">
                                             <label class="inp">
                                               <input type="password" placeholder="&nbsp;" class="form-control" id="password" name="password">
                                               <span class="label">Passwort *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group" id="confirm_pass_validate">
                                             <label class="inp">
                                               <input type="password" placeholder="&nbsp;" class="form-control" id="confirm_pass" name="confirm_pass">
                                               <span class="label">Passwort bestätigen *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                            <div class="checkbox mt-0 mb-0">
                                                <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="remember" value="" id="terms" name="terms">
                                                  <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                  I agree to the terms and service of style timer.
                                                </label>

                                            </div>
                                            <label class="error_label" id="terms_err"></label>                                              
                                       </div>
                                  </div>
                                  <input type="hidden" id="" name="employee_id" value="<?php echo $this->uri->segment(3); ?>">
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                            <button type="submit" name="frmsubmit" class="btn width250">Get Started</button>                                            
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
