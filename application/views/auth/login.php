  <?php $this->load->view('frontend/common/head'); ?>
  
  <section class="login_register_sections clear">
    <div class="pl-15 pr-15">
        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 align-self-center login_register_main_left_block">
              <div class="row">
                   <div class="login_register_main_left_block_overlay around-50">
                        <div class="relative ">
                            <a href="<?php  echo base_url();?>"> 
                              <img src="<?php echo base_url("assets/frontend/images/footer_logo_icon.png"); ?>" class="lrmlbo_logo_icon" />
                            </a>
                        </div>
                        
                   </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12 col-12 m-auto d-flex height-vh justify-content-center">
        
                <div class="alert alert-danger absolute top w-100 mt-15 alert_message" style="display:none;">
                  <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>-->     
                  <picture class="mr-10 width20">
                    <img src="<?php echo base_url('assets/frontend/images/error-icon-alert.png'); ?>" class="">
                    </picture>   
                    <span id="alert_message"> </span>
                </div>

              <?php if($this->session->flashdata('success')) { ?>
              <div style="top:0px;" id="succ_msg" class="alert alert-success absolute top w-100 mt-15">
                  <button type="button" class="close ml-10" data-dismiss="alert">&times;</button> <?php echo $this->session->flashdata('success'); ?>
              </div><?php } ?>
               
                <?php if($this->session->flashdata('error')) { ?>
                 <div id="login_error_blank" style="top:15px;" class="alert alert-warning alert-dismissible absolute fade show" role="alert">
                   <?php echo $this->session->flashdata('error'); ?>
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                   </button>
                 </div>
                 <?php } ?>

              <div class="relative align-self-center d-flex flex-row login_register_main_right_block w-100 mobile-mt-75" id="lrmrb_scroll">
                <div class="relative align-self-center w-100">
                    <h2 class="font-size-36 color333 fontfamily-medium mb-60 text-center">Sign In</h2>
                    <div class="relative login_register_form_block">
                         <form id="userLogin">
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50 height60" id="identity_validate">
                                             <label class="inp">                         
                                               <input type="text" name="identity" placeholder="&nbsp;" class="form-control ">
                                               <span class="label">Email *</span>                                               
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group height60" id="password_validate">
                                             <label class="inp">
                        
                                               <input type="password" name="password" placeholder="&nbsp;" class="form-control">
                                                
                                                
                                                <span class="label">Password *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-right">
                                             <a href="<?php echo base_url('auth/forgot_password'); ?>" class="fontfamily-regular font-size-14 color999 a_hover_999">Forgot Password?</a>                                               
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                            <button class="btn width180">Get Started </button>                                            
                                       </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                            <span class="font-size-14 fontfamily-regular color333">Or</span>                                            
                                       </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                       <div class="form-group">
                                              <a href="" class="login_facebook_btn font-size-16 fontfamily-medium colorwhite a_hover_white display-b text-center border-radius4 around-10 relative"><i class="fab fa-facebook-f  mr-10 absolute left top"></i> Facebook</a>                                         
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                       <div class="form-group">
                                            <a href="" class="login_google_btn font-size-16 fontfamily-medium colorwhite a_hover_white display-b text-center border-radius4 around-10 relative"><i class="fab fa-google-plus-g  mr-10 absolute left top"></i> Google</a>
                                       </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                           <span class="color333 fontsize-14 fontfamily-regular">Don't have an account ? </span> <a href="<?php echo base_url('user/registration') ?>" class="fontfamily-regular colororange a_hover_orange fontsize-14 mt-0 display-ib">Register</a>
                                        </div>
                                  </div>
                              </div>
                              <?php 
                                if(isset($_GET['servicids'])){
                                  $dates=$times='';
                                  if(isset($_GET['date']))
                                    $dates='&date='.$_GET['date'];
                                  if(isset($_GET['time']))
                                    $times='&time='.$_GET['time'];
                                  $urll= 'user/service_provider/'.$this->uri->segment(3).'?servicids='.$_GET['servicids'].$dates.$times;
                                }
                                else if(isset($_GET['detailids'])){
                                  $urll= 'booking/detail/'.$_GET['detailids'];
                                }
                                else if($this->uri->segment(3) !='')
                                  $urll= 'user/service_provider/'.$this->uri->segment(3); 
                                else
                                  $urll="";
                                  ?>

                                <input type="hidden" id="select_url" name="select_url" value="<?php echo $urll;?>">
                         </form>
                    </div>
                  </div>
              </div>
            </div>
        </div>
    </div>
  </section>


  <!-- Modal -->
<!--
<div id="" class="modal login45 fade" role="dialog">
  <div class="modal-dialog modal-md modal-dialog-centered" style="max-width:350px;">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
        <img src="<?php echo base_url("assets/frontend/images/popup_crose_black_icon.svg") ?>" class="popup-crose-black-icon">
      </a>
      <div class="modal-body text-left">
          <div class="relative align-self-center w-100">
          <h2 class="font-size-24 color333 fontfamily-medium mb-40 text-center">Sign In</h2>
          <div class="relative login_register_form_block">
               <form id="userLogin">
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="form-group form-group-mb-50 height60" id="identity_validate">
                                   <label class="inp">  
                                        <input type="text" name="identity" placeholder="&nbsp;" class="form-control ">
                                        <span class="label">Email *</span>                                               
                                   </label>                                                
                              </div>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="form-group height60" id="password_validate">
                                   <label class="inp">
                                        <input type="password" name="password" placeholder="&nbsp;" class="form-control">
                                        <span class="label">Password *</span>
                                   </label>                                                
                              </div>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="form-group text-right">
                                   <a href="<?php echo base_url('auth/forgot_password'); ?>" class="fontfamily-regular font-size-14 color999 a_hover_999">Forgot Password?</a>    
                              </div>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="form-group text-center">
                                   <button class="btn width180">Get Started </button> 
                              </div>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="form-group text-center">
                                   <span class="font-size-14 fontfamily-regular color333">Or</span>
                              </div>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                              <div class="form-group">
                                        <a href="" class="login_facebook_btn font-size-16 fontfamily-medium colorwhite a_hover_white display-b text-center border-radius4 around-10 relative"><i class="fab fa-facebook-f  mr-10 absolute left top mr-10 absolute left top display-ib"></i> <span class="display-ib ml-30"> Facebook</span></a>
                              </div>
                         </div>
                         <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                              <div class="form-group">
                                   <a href="" class="login_google_btn font-size-16 fontfamily-medium colorwhite a_hover_white display-b text-center border-radius4 around-10 relative"><i class="fab fa-google-plus-g  mr-10 absolute left top display-ib"></i> <span class="display-ib ml-15">Google</span></a>
                              </div>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="form-group text-center">
                                   <span class="color333 fontsize-14 fontfamily-regular">Don't have an account ? </span> <a href="<?php echo base_url('user/registration') ?>" class="fontfamily-regular colororange a_hover_orange fontsize-14 mt-0 display-ib">Register</a>
                              </div>
                         </div>
                    </div>
                    <?php 
                         if(isset($_GET['servicids'])){
                         $dates=$times='';
                         if(isset($_GET['date']))
                              $dates='&date='.$_GET['date'];
                         if(isset($_GET['time']))
                              $times='&time='.$_GET['time'];
                         $urll= 'user/service_provider/'.$this->uri->segment(3).'?servicids='.$_GET['servicids'].$dates.$times;
                         }
                         else if(isset($_GET['detailids'])){
                         $urll= 'booking/detail/'.$_GET['detailids'];
                         }
                         else if($this->uri->segment(3) !='')
                         $urll= 'user/service_provider/'.$this->uri->segment(3); 
                         else
                         $urll="";
                         ?>

                         <input type="hidden" id="select_url" name="select_url" value="<?php echo $urll;?>">
               </form>
             </div>
          </div>
      </div>
    </div>
  </div>
</div>
-->

  
  <?php $this->load->view('frontend/common/footer_script'); ?>
  <script type="text/javascript">
    
    setTimeout(function() {
      $("#login_error_blank").hide();
      }, 4000);

    // MODAL ON LOAD PAGE
 $(document).ready(function(){
      //$('.login45').modal('show');
  });
  </script>
