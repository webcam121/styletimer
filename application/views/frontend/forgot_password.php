<?php $this->load->view('frontend/common/head'); ?>
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

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 m-auto d-flex height-vh justify-content-center">
              <div id="succ_msg" style="display: none; height: auto; top:0px" class="alert alert-success absolute top w-100 mt-15">
                  <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>
                  <strong>Please check ! </strong>  We have sent an password reset link to your registered email id.
                </div>
                 <div id="err_msg" style="display: none; height: auto; top:0px" class="alert alert-danger absolute top w-100 mt-15 text-center">
                  <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>
                  <picture class="mr-10 width20">
                    <source srcset="<?php echo base_url('assets/frontend/images/error-icon-alert.webp'); ?>" type="image/webp" class="width20">
                    <source srcset="<?php echo base_url('assets/frontend/images/error-icon-alert.svg'); ?>" type="image/svg" class="width20">
                    <img src="<?php echo base_url('assets/frontend/images/error-icon-alert.png'); ?>" class="width20">
                    </picture>  
                  <span id="errMsg"></span>
                </div>
                <?php $this->load->view('frontend/common/alert'); ?>
              <div class="relative align-self-center d-flex flex-row login_register_main_right_block w-100 mobile-mt-75" id="lrmrb_scroll">
                <div class="relative align-self-center w-100">
                    <h2 class="font-size-36 color333 fontfamily-medium mb-10 text-center">Forgot your Password?</h2>
                    <p class="mb-60 font-size-14 fontfamily-regular color666 text-center">Enter your email and will send you a link to reset your password</p>
                    <div class="relative login_register_form_block">
                         <form id="for_password" method="post">
                          <!-- action="<?php //echo base_url('auth/forgot_password') ?>" -->
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="email_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="email" name="email">
                                               <span class="label">Email *</span>
                                               <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter Email</label> -->
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                            <button type="submit" id="frmsubpass" name="frmsubpass" class="btn width180">Reset Password </button>                                            
                                       </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">

                                          <span class="color333 fontsize-14 fontfamily-regular"><?php echo $this->lang->line('you-already-account'); ?> </span>  <a href="#" class="fontfamily-regular colororange color333 a_hover_333 fontsize-14 mt-15 display-ib openLoginPopup"><?php echo $this->lang->line('sign-in'); ?>!</a>

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
