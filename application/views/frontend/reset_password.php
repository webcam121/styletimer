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
              <div class="relative align-self-center d-flex flex-row login_register_main_right_block w-100" id="lrmrb_scroll">
                <div class="relative align-self-center w-100">
                    <h2 class="font-size-36 color333 fontfamily-medium mb-60 text-center">Passwort zurücksetzen</h2>
                    <div class="relative login_register_form_block">
                         <form id="resetPassword" method="post" action="<?php echo base_url('auth/reset_password/' . $code); ?>">
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="new_validate">
                                             <label class="inp">
                                               <input type="password" placeholder="&nbsp;" class="form-control" id="new" name="new">
                                               <span class="label">Neues Passwort*</span>
                                               <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter Password</label> -->
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="new_confirm_validate">
                                             <label class="inp">
                                               <input type="password" placeholder="&nbsp;" class="form-control" id="new_confirm" name="new_confirm">
                                               <span class="label">Passwort bestätigen *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                            <button type="submit" id="frmrestpass" class="btn width180" style="text-transform: none;">Passwort ändern</button>                                            
                                       </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                          <span class="color333 fontsize-14 fontfamily-regular"><?php echo $this->lang->line('you-already-account'); ?> </span>  <a href="<?php echo base_url(); ?>" class="fontfamily-regular colororange a_hover_orange fontsize-14 mtm-10 display-ib"><?php echo $this->lang->line('sign-in'); ?>!</a>
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
