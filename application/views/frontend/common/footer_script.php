<?php if(empty($this->session->userdata('access')) || $this->session->userdata('access')=='user'){ ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php } ?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src='https://www.google.com/recaptcha/api.js'></script>

<style>
/* .error_label{
		position: relative !important;
		} */
.toast-center {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.error_label#gender_err {
  position: absolute !important;
  margin-top: 4px !important;
  display: block;
  width: auto;
}

#rc-anchor-container {
  margin-left: 10px !important;
}

#recaptcha.recaptcha-popup div {
  margin: auto !important;
}

.land-label-css-custom {
  color: #999;
  transform: translateY(-24px) translateX(-2px) scale(0.75);
  padding: 0 0px;
  font-size: 14px;
  position: absolute;
}

.custo-recaptcha-ce div {
  margin: auto;
}
</style>
<?php if($this->session->userdata('access')=='marchant'){ ?>
<!-- modal start -->
<div class="modal fade" id="service-cancel-list">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content text-center">
      <a href="#" class="crose-btn" data-dismiss="modal" id="conf_close">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
            type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"
            class="popup-crose-black-icon">
          <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
            class="popup-crose-black-icon">
        </picture>
      </a>
      <h5 class="text-left pt-20 pl-15 color333"><?php echo $this->lang->line('cancelled_bookings_c'); ?></h5>
      <div class="pt-10" id="all_booking_list">
      </div>
    </div>
  </div>
</div>




<?php  } 

$this->load->view('frontend/common/common_modal');
 ?>

<!-- modal start -->
<div class="modal fade" id="service-reschedule-table">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal" data-bid="" id="close_resch">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
            type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"
            class="popup-crose-black-icon">
          <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
            class="popup-crose-black-icon">
        </picture>
      </a>
      <div class="modal-body pb-30 pt-30 pl-40 pr-40">
        <h3 class="font-size-18 fontfamily-medium color333"><?php echo $this->lang->line('postpond_book'); ?></h3>
        <label class="error_label" id="date_err" style="top:50px"></label>
        <form id="frmReshedule" name="frmReshedule" method="post">


        </form>

      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div id="openLoginPopup" class="modal login45 fade" role="dialog">
  <div class="modal-dialog modal-md modal-dialog-centered" style="max-width:350px;">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
            type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"
            class="popup-crose-black-icon">
          <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
            class="popup-crose-black-icon">
        </picture>
      </a>
      <div class="modal-body text-left">
        <div class="relative align-self-center w-100">
          <div class="alert alert-danger absolute top w-100 mt-15 alert_message" <?php if(empty($_GET['status'])){ ?>
            style="display:none;" <?php }else{ ?> style="height: 47px!important;" <?php } ?>>
            <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>-->
            <picture class="mr-10 width20">
              <source srcset="<?php echo base_url('assets/frontend/images/error-icon-alert.webp'); ?>" type="image/webp"
                class="width20">
              <source srcset="<?php echo base_url('assets/frontend/images/error-icon-alert.svg'); ?>" type="image/svg"
                class="width20">
              <img src="<?php echo base_url('assets/frontend/images/error-icon-alert.png'); ?>" class="width20">
            </picture>

            <span id="alert_message">
              <?php if(!empty($_GET['status']) && $_GET['status']=='inactive'){ echo 'Your status is inactive. Please contact to admin'; }
                    elseif(!empty($_GET['status']) && $_GET['status']=='email')
                    { echo 'We need to your email. please update your email and try again.'; }
                     ?> </span>
          </div>

          <div class="alert alert-success absolute top w-100 mt-15 alert_sucmessage" style="display:none;">
            <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>-->
            <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg')?>">
            <span id="alert_sucmessage"></span>
          </div>
          <h2 style="color:red">
            <?php //echo $this->session->flashdata('err_message'); ?>
          </h2>
          <h2 class="font-size-24 color333 fontfamily-medium mb-40 text-center">Login</h2>

          <div class="relative login_register_form_block">
            <?php
        $em_id=$pass=$rem="";
        if(isset($_COOKIE['ck_emailid']) && $_COOKIE['ck_emailid'] !=''){
            $em_id=$_COOKIE['ck_emailid'];       
        }
        if(isset($_COOKIE['ck_password']) && $_COOKIE['ck_password'] !=''){
            $pass=$_COOKIE['ck_password'];       
        }
        if($em_id !="" && $pass !=''){
            $rem="checked";       
        }

         ?>

            <form id="userLogin">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group form-group-mb-50 height60" id="identity_validate">

                    <label class="inp">
                      <input type="text" id="identity" name="identity" placeholder="&nbsp;" class="form-control "
                        value="<?php echo $em_id; ?>">
                      <span class="label"><?php echo $this->lang->line('Email'); ?> *</span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group height60" id="password_validate">
                    <label class="inp">
                      <input type="password" name="password" id="loginPassHideShow" placeholder="&nbsp;"
                        class="form-control" value="<?php echo $pass; ?>">
                      <span class="label"><?php echo $this->lang->line('password'); ?> *</span>
                      <span class="eye-icon">
                        <i class="far fa-eye-slash" id="ShowPassword"></i>
                      </span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group d-flex align-items-center justify-content-between">
                    <div class="checkbox mt-0 mb-0" id="">
                      <label class="fontfamily-regular font-size-14 color999 a_hover_999">
                        <input type="checkbox" value="1" id="remember" name="rememberme" <?php echo $rem; ?>>
                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                        <?php echo $this->lang->line('login_remember_label'); ?>
                      </label>

                    </div>
                    <?php //echo base_url('auth/forgot_password'); ?>
                    <a id="frg_password" href="javascript:void(0)"
                      class="fontfamily-regular font-size-14 color999 a_hover_999"><?php echo $this->lang->line('Forgot_Password'); ?>?</a>
                  </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mx-auto">
                  <div class="g-recaptcha recaptcha-popup" style="display: none;text-align:center" id="recaptcha"
                    data-sitekey="6Lel0UoeAAAAAJm755rLYXI9_DCfeeKIQ6TqRA9Z">
                    <!-- 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI -->
                  </div>
                  <a href="javaScript:void(0);" id="resend_activelink" style="display: none!important;"
                    class="colororange a_hover_orange fontsize-14 d-block text-center mb-3 text-underline"><?php echo $this->lang->line('Resend-account-activation-mail'); ?></a>
                </div>
              </div>
              <p style="font-size:14px;color:red;padding-left:10px;display:'none'" id="credential__error"></p>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group text-center">
                    <input type="hidden" id="check_login_attempt" value="">
                    <button class="btn width180" id="loginSubmitForm"><?php echo $this->lang->line('Get-Started1'); ?>
                    </button>
                  </div>
                </div>
              </div>
              <p id="alert_message_my"></p>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group text-center">
                    <span class="font-size-14 fontfamily-regular color333">oder</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                    <a href="<?php echo get_facebook_login_url(); ?>"
                      class="login_facebook_btn font-size-16 fontfamily-medium colorwhite a_hover_white display-b text-center border-radius4 around-10 relative"><i
                        class="fab fa-facebook-f  mr-10 absolute left top mr-10 absolute left top display-ib"></i> <span
                        class="display-ib ml-30"> Facebook</span></a>
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                    <a href="<?php echo get_google_login_url(); ?>"
                      class="login_google_btn font-size-16 fontfamily-medium colorwhite a_hover_white display-b text-center border-radius4 around-10 relative"><i
                        class="fab fa-google-plus-g  mr-10 absolute left top display-ib"></i> <span
                        class="display-ib ml-15">Google</span></a>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group text-center">
                    <span class="color333 fontsize-14 fontfamily-regular">Du hast noch keinen Account? </span> <a
                      href="JavaScript:Void(0);" data-dismiss="modal" data-toggle="modal"
                      data-target="#openRegisterPopup"
                      class="fontfamily-regular colororange a_hover_orange fontsize-14 mt-0 display-ib">Registriere
                      dich!</a>
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

              <input type="hidden" id="red_bookid" name="red_bookid"
                value="<?php echo !empty($_GET['bid'])?$_GET['bid']:'';?>">
              <input type="hidden" id="select_url" name="select_url" value="<?php echo $urll;?>">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- resister modal start-->
<?php if (!isset($is_edit_profile)) {?>
<!-- Modal -->
<div id="openRegisterPopup" class="modal fade" role="dialog" style="overflow:auto;">
  <div class="modal-dialog modal-lg modal-dialog-centered" style="">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
            type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"
            class="popup-crose-black-icon">
          <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
            class="popup-crose-black-icon">
        </picture>
      </a>
      <div class="modal-body">
        <h2 class="font-size-24 color333 fontfamily-medium mb-4 text-center">
          <?php echo $this->lang->line('Register-as-User'); ?></h2>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 m-auto d-flex justify-content-center">
            <div class="relative align-self-center d-flex flex-row  w-100">
              <div class="relative align-self-center login_register_main_right_block w-100" id="lrmrb_scroll">
                <h2 style="color:red">
                  <?php //echo $this->session->flashdata('error'); ?></h2>
                <?php  
                    //echo $this->session->flashdata('err_message'); ?>
                <div class="relative login_register_form_block">
                  <?php
                  if ($ptype != 'merchant_registration'):
                  ?>
                  <form id="userRegistration" method="post" action="<?php echo base_url('auth/registration/user') ?>">
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50">
                          <div class="btn-group multi_sigle_select inp_select" id="genderDiv">
                            <span class="label"><?php echo $this->lang->line('Gender'); ?> *</span>
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"
                              id="gender" name="gender"></button>
                            <ul class="dropdown-menu mss_sl_btn_dm">
                              <li class="radiobox-image"><input type="radio" id="id_112" name="gender"
                                  class="user_regfrm" value="male"><label for="id_112">Männlich</label></li>
                              <li class="radiobox-image"><input type="radio" id="id_113" name="gender"
                                  class="user_regfrm" value="female"><label for="id_113">Weiblich</label></li>
                              <li class="radiobox-image"><input type="radio" id="id_114" name="gender"
                                  class="user_regfrm" value="other"><label for="id_114">Andere</label></li>
                            </ul>
                          </div>
                          <label class="error_label" id="gender_err"></label>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="dob_validate">
                          <label class="inp">
                            <input type="text" placeholder="Geburtsdatum *" class="form-control dobDatepicker"
                              name="dob" style="background-color:#ffffff" readonly>
                            <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>"
                              class="v_time_claender_icon_blue" style="top:8px;right:9px;">
                            <!-- <span class="label"></span> -->
                          </label>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="first_name_validate">
                          <label class="inp">
                            <input type="text" placeholder="&nbsp;" class="form-control" id="first_name"
                              name="first_name">
                            <span class="label"><?php echo $this->lang->line('First_Name'); ?> *</span>
                            <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                          </label>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="last_name_validate">
                          <label class="inp">
                            <input type="text" placeholder="&nbsp;" class="form-control" id="last_name"
                              name="last_name">
                            <span class="label"><?php echo $this->lang->line('Last_Name'); ?> *</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="telephone_validate">
                          <label class="inp">
                            <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="telephone"
                              name="telephone">
                            <span class="label"><?php echo $this->lang->line('Telephone'); ?> </span>
                          </label>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="email_validate">
                          <label class="inp">
                            <input type="text" placeholder="&nbsp;" class="form-control" id="email" name="email">
                            <span class="label"><?php echo $this->lang->line('Email'); ?> *</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="location_val">
                          <label class="inp">
                            <input type="text" placeholder="&nbsp;" class="form-control" id="location" name="location">
                            <span class="label"><?php echo $this->lang->line('Street'); ?> </span>
                          </label>
                          <input type="hidden" name="latitude" value="" id="latitude">
                          <input type="hidden" name="longitude" value="" id="longitude">
                          <span class="error_label" id="addr_err"></span>
                        </div>

                      </div>
                    </div>

                    <!-- <div class="row">
                                  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50">
                                             <div class="btn-group multi_sigle_select inp_select" id="countryDiv"> 
                                                  <span class="label"><?php echo $this->lang->line('Country'); ?> </span>
                                                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" id="country"></button>
                                                      <ul class="dropdown-menu mss_sl_btn_dm">
                                                          <li class="radiobox-image"><input type="radio" id="id_1" name="country" class="country" value="Germany"><label for="id_1">Germany</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="id_2" name="country" class="country" value="Austria"><label for="id_2">Austria</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="id_3" name="country" class="country" value="Switzerland"><label for="id_3">Switzerland</label></li>
                                                      </ul>
                                              </div>
                                              <label class="error_label" id="country_err"></label>

                                       </div>
                                  </div> -->
                    <div class="row">
                      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50">
                          <label class="land-label-css-custom"> <?php echo $this->lang->line('Country'); ?></label>
                          <div class="btn-group multi_sigle_select inp_select" id="countryDiv">

                            <!-- <span class="label"><?php echo $this->lang->line('Country'); ?> *</span>  -->
                            <!-- <select class="form-select" aria-label="Default select example" class="dropdown-menu mss_sl_btn_dm btn btn-default dropdown-toggle mss_sl_btn">
                                                        <option selected  value="Deutschland">Deutschland</option>
                                                        <option value="Österreich">Österreich</option>
                                                        <option value="Schweiz">Schweiz</option>
                                                        
                                                      </select> -->
                            <button data-toggle="dropdown"
                              class="btn btn-default dropdown-toggle mss_sl_btn">Deutschland</button>
                            <ul class="dropdown-menu mss_sl_btn_dm">
                              <li class="radiobox-image"><input type="radio" id="id_1" name="country" class="country"
                                  value="Germany" selected><label for="id_1">Deutschland </label></li>
                              <li class="radiobox-image"><input type="radio" id="id_2" name="country" class="country"
                                  value="Austria"><label for="id_2">Österreich</label></li>
                              <li class="radiobox-image"><input type="radio" id="id_3" name="country" class="country"
                                  value="Switzerland"><label for="id_3">Schweiz </label></li>
                            </ul>
                          </div>
                          <label class="error_label" id="country_err"></label>

                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="post_code_val">
                          <label class="inp">
                            <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="post_code"
                              name="post_code" maxlength="5">
                            <span class="label"><?php echo $this->lang->line('Postcode1'); ?> </span>
                          </label>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="city_val">
                          <label class="inp">
                            <input type="text" placeholder="&nbsp;" class="form-control city" id="city" name="city">
                            <span class="label"><?php echo $this->lang->line('City'); ?> </span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="password_validate">
                          <label class="inp">
                            <input type="password" placeholder="&nbsp;" class="form-control" id="password"
                              name="password">
                            <span class="label"><?php echo $this->lang->line('Password'); ?> *</span>
                          </label>
                        </div>

                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group form-group-mb-50" id="confirm_pass_validate">
                          <label class="inp">
                            <input type="password" placeholder="&nbsp;" class="form-control" id="confirm_pass"
                              name="confirm_pass">
                            <span class="label"><?php echo $this->lang->line('Confirm_Password'); ?> *</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div id="reffrelotption"
                        class="<?php if(!empty($_GET['r'])) echo 'col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12';  else echo 'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'; ?>">
                        <div class="form-group form-group-mb-50">
                          <div class="btn-group multi_sigle_select inp_select">
                            <span class="label <?php if(!empty($_GET['r'])) echo 'label_add_top'; ?>"
                              style="width: max-content;"><?php echo $this->lang->line('How-did-you'); ?></span>
                            <button data-toggle="dropdown"
                              class="btn btn-default dropdown-toggle mss_sl_btn" style="text-transform: none !important;"><?php if(!empty($_GET['r'])) echo 'Referral'; ?></button>
                            <ul class="dropdown-menu mss_sl_btn_dm">
                              <li class="radiobox-image"><input type="radio" id="idopthow1" name="hot_toknow"
                                  class="selecthowtooption" value="Heard about in salon"><label
                                  for="idopthow1"><?php echo $this->lang->line('Heard-about-in-salon'); ?></label></li>
                              <li class="radiobox-image"><input type="radio" id="idopthow2" name="hot_toknow"
                                  class="selecthowtooption" value="Heard from other customer"><label
                                  for="idopthow2"><?php echo $this->lang->line('Heard-from-other-customer'); ?></label>
                              </li>
                              <li class="radiobox-image"><input type="radio" id="idopthow3" name="hot_toknow"
                                  class="selecthowtooption" value="Facebook"><label for="idopthow3">Facebook</label>
                              </li>
                              <li class="radiobox-image"><input type="radio" id="idopthow4" name="hot_toknow"
                                  class="selecthowtooption" value="Instagram"><label for="idopthow4">Instagram</label>
                              </li>
                              <li class="radiobox-image"><input type="radio" id="idopthow5" name="hot_toknow"
                                  class="selecthowtooption" value="Google"><label for="idopthow5">Google</label></li>
                              <li class="radiobox-image"><input type="radio" id="idopthow7" name="hot_toknow"
                                  class="selecthowtooption" value="Magazine/print advertising"><label
                                  for="idopthow7"><?php echo $this->lang->line('Magazine-print-advertising'); ?></label>
                              </li>
                              <li class="radiobox-image"><input type="radio" id="idopthow8" name="hot_toknow"
                                  class="selecthowtooption" value="Outdoor advertising"><label
                                  for="idopthow8"><?php echo $this->lang->line('Outdoor-advertising'); ?></label></li>
                              <li class="radiobox-image"><input type="radio" id="idopthow9" name="hot_toknow"
                                  class="selecthowtooption" value="TV/cinema"><label
                                  for="idopthow9"><?php echo $this->lang->line('TV/cinema'); ?></label></li>
                              <li class="radiobox-image"><input type="radio" id="idopthow10" name="hot_toknow"
                                  class="selecthowtooption" value="Events"><label for="idopthow10">Events</label></li>
                              <li class="radiobox-image">
                                <input type="radio" id="idopthow11" name="hot_toknow" class="selecthowtooption"
                                  value="Other"><label
                                  for="idopthow11"><?php echo $this->lang->line('Other'); ?></label>
                              </li>
                            </ul>

                          </div>
                          <label class="error_label" id="hot_toknow_err"></label>
                        </div>
                      </div>


                    </div>
                    <div class="row">
                      <div class="col-xl-8 col-lg-9 col-md-10 col-sm-12 col-12 m-auto">
                        <div class="form-group text-left">
                          <div class="checkbox mt-0 mb-2" style="margin-bottom: 1rem !important;" id="termsDiv">
                            <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox"
                                name="remember" value="" id="terms" name="terms">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                              Ich stimme den <a class="colororange a_hover_orange popup_terms" data-type="terms"
                                data-access="user" href="javascript:void(0)">
                                Nutzungsbedingungen </a>
                              und <a class="colororange a_hover_orange popup_terms" data-type="conditions"
                                data-access="user" href="javascript:void(0)">
                                Datenschutzbestimmungen </a> von styletimer zu.
                            </label>
                            <label style="top: 16px; padding-left: 30px; position:inherit; display:none;" class="error_label" id="terms_err"></label>
                          </div>
                          <div class="checkbox mt-0 mb-2">
                            <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox"
                                name="service_mail" value="">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                              <?php echo $this->lang->line('Tick-to-allow-the-venue'); ?>
                            </label>
                          </div>
                          <div class="checkbox mt-0 mb-0">
                            <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox"
                                name="newsletter" value="">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                              <?php echo $this->lang->line('Receive-newsletter-from-styletimer'); ?>
                            </label>
                          </div>
                        </div>

                      </div>
                      <!--  -->
                      <div class="col-xl-6 col-lg-7 col-md-8 col-sm-9 col-12 mx-auto">
                        <div class="g-recaptcha mb-3 custo-recaptcha-ce"
                          data-sitekey="6Lel0UoeAAAAAJm755rLYXI9_DCfeeKIQ6TqRA9Z"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group text-center">
                          <button type="submit" id="frmsubmit" name="frmsubmit"
                            class="btn width250"><?php echo $this->lang->line('Get-Started'); ?></button>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group text-center">

                          <span
                            class="color333 fontsize-14 fontfamily-regular"><?php echo $this->lang->line('you-already-account'); ?>
                          </span> <a href="#"
                            class="fontfamily-regular colororange a_hover_orange fontsize-14 mtm-10 display-ib openLoginPopup"><?php echo $this->lang->line('sign-in'); ?>!</a>

                        </div>
                      </div>
                    </div>
                  </form>
                  <?php 
                  endif;
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- resister modal end -->
<?php }?>
<div id="forgotPasswordPopup" class="modal" role="dialog">
  <div class="modal-dialog modal-md modal-dialog-centered" style="max-width:350px;">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
            type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"
            class="popup-crose-black-icon">
          <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
            class="popup-crose-black-icon">
        </picture>
      </a>
      <div class="modal-body text-left">
        <div class="relative align-self-center w-100">
          <div id="err_msg_for"
            style="display: none; height: auto; top:103px;text-align:center !important;margin:0rem !important;z-index:9;"
            class="alert alert-danger absolute top w-100 text-center">
            <button type="button" class="close ml-10" data-dismiss="alert">&times;</button> <span id="errMsg"></span>
          </div>
          <div id="succ_msg_for"
            style="display: none; height: auto; top:103px;text-align:center !important;margin:0rem !important;z-index:9;"
            class="alert alert-success absolute top w-100">
            <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>
            <strong>Please check ! </strong> We have sent an password reset link to your registered email id.
          </div>
          <h2 class="font-size-22 color333 fontfamily-medium mb-40 text-center">
            <?php echo $this->lang->line('Forgot-your-Password'); ?>?</h2>

          <div class="relative login_register_form_block">
            <!--id="for_password" -->
            <form id="frmsubpassID">
              <div class="row">

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <p class="mb-20 font-size-14 fontfamily-regular color666 text-center">Bitte gib deine E-Mail Adresse
                    ein und du erhältst einen Link, um dein Passwort zurückzusetzen</p>
                  <div class="form-group form-group-mb-50 height60" id="email_validate">
                    <label class="inp">
                      <input type="text" id="email-data" name="email" placeholder="&nbsp;" class="form-control ">
                      <span class="label"><?php echo $this->lang->line('Email'); ?> *</span>
                      <p id="match_emails_success" style="color: green"></p>
                      <p id="match_emails_error" style="color: red">
                      <p>
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group text-center">
                    <button class="btn width220" id="frmsubpassID--"
                      name="frmsubpass"><?php echo $this->lang->line('Reset-Password'); ?> </button>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group text-center">
                    <span
                      class="color333 fontsize-14 fontfamily-regular"><?php echo $this->lang->line('you-already-account'); ?>
                    </span> <a href="#"
                      class="fontfamily-regular colororange a_hover_orange fontsize-14 mtm-10 display-ib openLoginPopup"><?php echo $this->lang->line('sign-in'); ?>!</a>
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

<!-- term conditon model -->
<div class="modal fade" id="terms_condition_modal_reg">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <a href="javascript:void(0)" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
            type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"
            class="popup-crose-black-icon">
          <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
            class="popup-crose-black-icon">
        </picture>
      </a>

      <div class="modal-body p-0" id="tems_condition_Html">


      </div>
    </div>
  </div>
</div>

<!-- thankyou modal -->
<div class="modal fade" id="resi-thankyou">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" id="">
      <div class="alert alert-success absolute top w-100 mt-15 alert_sucmessage_thk" style="display:none;">
        <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>-->
        <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg')?>">
        <span id="alert_sucmessage_thk"></span>
      </div>
      <section class="service_provider_registration_done_thankyou">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="align-center around-20 align-self-center mt-20">
              <div class="thankyou_top_img_block2">
                <img src="<?php echo base_url('assets/frontend/images/fireworks.png'); ?>"
                  class="thankyou_top_img2">
              </div>
              <div class="relative mt-25">
                <h1 class="fontfamily-medium font-size-60 mb-0" style="color:#E8E8E8;">
                  <?php echo $this->lang->line('Thank-you'); ?>!</h1>
                <img src="<?php echo base_url('assets/frontend/images/thankyou_mid_line_img.png'); ?>"
                  class="thankyou_mid_line_img">
              </div>
              <div class="mt-4">
                <h4 class="color333 fontfamily-medium font-size-26">
                  <?php echo $this->lang->line('Your-registration-successfully'); ?></h4>
                <p class="mt-4 mb-3 color666 font-size-16 fontfamily-regular lineheight30">Wir haben dir einen Link zur
                  Aktivierung deines Accounts <br /> an deine E-Mail Adresse gesendet.</p>
                <?php if(!empty($_SESSION['regstyle_email'])){ ?>
                <a href="javaScript:void(0);" id="resend_activelink_thk" style=""
                  class="colororange a_hover_orange fontsize-14 d-block text-center mb-3 text-underline"><?php echo $this->lang->line('Resend-account-activation-mail'); ?></a>
                <input type="hidden" id="get_email" value="<?php echo $_SESSION['regstyle_email']; ?>" name="">
                <?php } ?>
                <a href="#" class="openLoginPopup"><button type="button"
                    class="btn widthfit"><?php echo $this->lang->line('Go-To-Login'); ?></button></a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>


<div id="loading_div">
  <picture class="process_loading hide">
    <source srcset="<?php echo base_url('assets/frontend/images/loader.webp'); ?>" type="image/webp"
      class="process_loading hide">
    <source srcset="<?php echo base_url('assets/frontend/images/loader.gif'); ?>" type="image/gif"
      class="process_loading hide">
    <img src="<?php echo base_url('assets/frontend/images/loader.gif'); ?>" class="process_loading hide">
  </picture>
</div>
<audio id="myAudio" style="display:none;">
  <source src="<?php echo base_url('assets/frontend/css/noti_sound.mp3'); ?>" type="audio/mpeg">
</audio>
<audio id="myAudio1" style="display:none;">
  <source
    src="<?php echo base_url('assets/frontend/css/zapsplat_multimedia_alert_bell_chime_event_notification_00457855.mp3'); ?>"
    type="audio/mpeg">
</audio>


<script src="<?php echo base_url('assets/frontend/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/jquery.smartbanner.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/dropdown-enhancement.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/popper.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/bootstrap.min.js'); ?>"></script>
<script defer src="<?php echo base_url('assets/frontend/js/fa/fontawesome-all.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/slick.min.js'); ?>" type="text/javascript" charset="utf-8">
</script>
<script src="<?php echo base_url('assets/frontend/js/nouislider.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/mani_custom.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/vinu_custome.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/jquery.rippler.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/gijgo.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/gigjomessage.de-de.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/bootstrap-clockpicker.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/additional-methods.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/script.js'); ?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?php echo base_url('assets/frontend/custome/userFormValidation.js'); ?>"></script>
<script src='<?php echo base_url('assets/frontend/js/sweetalert2.js'); ?>'></script>

<script type="text/javascript">
  if (!navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
    $(function() { $.smartbanner({
      title: 'styletimer - Friseur & Beauty',
      button: 'Anzeigen',
      inGooglePlay: 'im Playstore',
      price: 'LADEN',
      icon: 'https://play-lh.googleusercontent.com/j5ZDHN--8TsxEtx_wfVYJ5wUWIitJgerTTISdQBdfKyz2dnrnJV00U9I8BXocwnM9XdJ=s48-rw',
      author: 'Dein Style in einer App!',
      daysHidden:0,
      daysReminder:0
    })} )
}
</script>

<?php
  if($this->session->userdata('access')=='marchant'){ 
    $this->load->view('frontend/common/editer_js');
    if (isset($include_jqueryui)) {
?>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php        
    }
  } 
  if(empty($this->session->userdata('access')) || (isset($inc_jqueryui))){ ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="application/javascript" src="https://app.usercentrics.eu/latest/main.js" id="F9VT5zId9"></script>
<?php } ?>


<script type="text/javascript">
toastr.options = {
  "positionClass": "toast-center",
  "timeOut": "500000",
  "closeButton": true,
}

$(document).on('click', '#frmsubmit', function() { //#location,#city
  /*if($('[name="country"]:checked').length == 0){
      $("#country_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>Field is required'); 
       token =false;
    }else{ $("#country_err").html(''); }*/

  if ($('[name="gender"]:checked').length == 0) {
    $("#gender_err").html(
      '<i class="fas fa-exclamation-circle mrm-5"></i><?php echo $this->lang->line('Select-gender'); ?>');
    token = false;

  } else {
    $("#gender_err").html('');
  }
  /*if($('[name="hot_toknow"]:checked').length == 0){
      //$("#hot_toknow_err").('display','block'); 
      $("#hot_toknow_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>Please select how did you find out about styletimer'); 
       token =false;
    }else{ $("#hot_toknow_err").html(''); }*/
});

$(document).on('change', '.user_regfrm,.country,.selecthowtooption', function() {
  if ($('[name="country"]:checked').length > 0)
    $("#country_err").html('');
  if ($('[name="gender"]:checked').length > 0)
    $("#gender_err").html('');
  if ($('[name="hot_toknow"]:checked').length > 0)
    $("#hot_toknow_err").html('');

});

$(document).on('click', '#post_code', function() { //#location,

  $("#latitude").val("");
  $("#longitude").val("");

});
$(document).on('blur', '#post_code', function() { //#location,

  getaddress();

});
$(document).on('change', '.country', function() {
  $("#latitude").val("");
  $("#longitude").val("");
  getaddress();

});


function getaddress() {
  var country = $("input[name='country']:checked").attr('data-text');
  var zipcode = $("#post_code").val();
  var c_code = 'de';
  var location = $("#location").val();
  if (location != "") {
    address = location;
  }
  if (zipcode == "") {
    return false;
  }
  var address = "";
  if (zipcode != undefined && zipcode != "") {
    address = address + " " + zipcode;
  }

  if (country != undefined && country != "") {
    //address=address+" "+country;
    if (country == 'Austria')
      c_code = 'at';
    else if (country == 'Switzerland')
      c_code = 'ch';
  }



  //var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&countrycodes="+c_code+"&postalcode="+address+"&street="+location+"&addressdetails=1&format=json";
  var gurl = "https://us1.locationiq.com/v1/search.php?key=" + iq_api_key + "&q=" + address +
    "&addressdetails=1&format=json&countrycodes=de";

  loading();
  $.get(gurl, function(data) {
    var count = data.length;
    if (data.length > 0) {
      $.each(data, function() {
        if (data[0].address.town != undefined)
          var citty = data[0].address.town
        else if (data[0].address.city != undefined)
          var citty = data[0].address.city
        else if (data[0].address.municipality != undefined)
          var citty = data[0].address.municipality
        else if (data[1].address.town != undefined)
          var citty = data[1].address.town
        else if (data[1].address.city != undefined)
          var citty = data[1].address.city
        else if (data[1].address.municipality != undefined)
          var citty = data[1].address.municipality
        else if (data[2].address.town != undefined)
          var citty = data[2].address.town
        else if (data[2].address.city != undefined)
          var citty = data[2].address.city
        else if (data[2].address.municipality != undefined)
          var citty = data[2].address.municipality
        else
          var citty = "";
        $("#city").val(citty);

        $("#latitude").val(data[0].lat);
        $("#longitude").val(data[0].lon);
        $('#addr_err').html('');
      });
      unloading();
      //   if(data[0].address.town!=undefined)
      //       var citty = data[0].address.town
      //   else if(data[0].address.city!=undefined)
      //      var citty = data[0].address.city
      //      else if(data[1].address.city!=undefined)
      //      var citty = data[1].address.city
      //   else
      //      var citty = "";

      // $("#city").val(citty);
      // $("#latitude").val(data[0].lat);
      // $("#longitude").val(data[0].lon);
      //  $('#addr_err').html('');
      //    unloading();
    } else {
      $("#location_validate label.error").css('display', 'none');
      $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
      $("#latitude").val('');
      unloading();
      return false;
    }
  }).fail(function() {
    $("#location_validate label.error").css('display', 'none');
    $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');

    $("#latitude").val('');
    unloading();
    return false;
  });
}


// $(document).on('blur','#post_code',function(){    //#location,#city
//  getLatlong(); 
// });
// $(document).on('change','.country',function(){
//  getLatlong(); 
// });


//   function getLatlong(){
//      var country= $("input[name='country']:checked").val();
//       var c_code='de';
//        var location=$("#location").val();
//         if(location!=""){
// 		address=location;
// 		}

//       var zipcode=$("#post_code").val();
//       if(zipcode == ''){
//         return false;
//       }
//       var address="";
//       // if(zipcode!=undefined && zipcode!=""){
//       //   address=address+" "+zipcode;
//       // }
//       if(country!=undefined && country!=""){
//         address=address+" "+country;
//        if(country == 'Germany')
//           c_code = 'de';
//       //  else if(country == 'Switzerland')
//       //     c_code = 'ch';
//       }

//       //~ if(location!="")
//        //~ var searchQery=location+", "+zipcode+" "+country;

//  // var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&countrycodes="+c_code+"&postalcode="+address+"&street="+location+"&addressdetails=1&format=json";
//    var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&q="+address+"&addressdetails=1&format=json";

//    $.get(gurl,function(data){
// 	 console.log(data);
//     var count = data.length;
//     if(data.length > 0){
// 	  if(data[0].address.town!=undefined)
//             var citty = data[0].address.town
//         else if(data[0].address.city!=undefined)
//            var citty = data[0].address.city
//        else
//            var citty = "";
//       $("#city").val(citty);
//       $("#latitude").val(data[0].lat);
//       $("#longitude").val(data[0].lon);
//        $('#addr_err').html('');
//     }
//     else{
//       $("#location_validate label.error").css('display','none');
//       $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Please enter valid address');
//       $("#latitude").val('');
//       return false;
//       }  
//     }).fail(function() {
//      $("#location_validate label.error").css('display','none');
//       $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Please enter valid address');

//       $("#latitude").val('');
//       return false;
//     });
//    }  
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $("#credential_pass_error").hide();
  $(document).on('click', '#ShowPassword', function() {
    if ($(this).hasClass('fa-eye-slash')) {

      $(this).removeClass('fa-eye-slash');
      $(this).addClass('fa-eye');

      $("#loginPassHideShow").attr('type', 'text');
    } else {
      $(this).removeClass('fa-eye');
      $(this).addClass('fa-eye-slash');

      $("#loginPassHideShow").attr('type', 'password');
    }

  });

  $(document).on("click", "#conf_close", function() {
    var id = $(this).attr('data-bookid');
    if (id != undefined)
      getBokkingDetail(id);
  });

});


$(document).on('click', '#resendOtp', function() {
  console.log('IN');
  // console.log('SESSION='+<?php //echo $_SESSION['userdata'];?>); 
  var status = localStorage.getItem('STATUS');
  if (status == 'LOGGED_OUT') {
    console.log('STATUS=' + status);
    console.log(base_url)
    window.location.href = base_url;
    $(window.location)[0].replace(base_url);
    $(location).attr('href', base_url);
  }
  console.log('STATUS=' + status);
  $.ajax({
    url: base_url + 'profile/delete_account_otp_sent',
    type: "POST",
    success: function(response) {
      var obj = jQuery.parseJSON(response);
      if (obj.success == 1) {
        //  alert("success",obj.success)
        //  $("#deactivate_profile_model").modal('show');
        $("#match_pass_success").html("Code erneut gesendet");

        setTimeout(function() {
          $("#match_pass_success").hide();
        }, 3000);

      } else {
        $("#match_pass_error").html(obj.msg);
      }

    }
  });

});

// $(document).on('click', '#loginSubmitForm', function() {
//   var identity = $('#identity').val();
//   var password = $('#loginPassHideShow').val();
//   // alert(identity);
//   //alert(password);
//   $.ajax({
//     url: base_url + 'Auth/login',
//     type: "POST",
//     success: function(data) {
//       var obj = jQuery.parseJSON(data);
//       console.log("response------------ login ", obj);

//       if (data.success == 1) {
//         //    $("#deactivate_profile_model").modal('show');
//         //     $("#deactivate_profile_model").modal('show');
//         //  $("#match_pass_success").html("Code erneut gesendet");

//         //  setTimeout(function(){  $("#match_pass_success").hide(); }, 3000);

//         // if(response.success == 1){
//         // alert("success",response)
//         //  $("#deactivate_profile_model").modal('show');
//         //  $("#match_pass_success").html("Code erneut gesendet");

//         //  setTimeout(function(){  $("#match_pass_success").hide(); }, 3000);

//       } else {
//         $("#credential_pass_error").show();
//         $("#credential_pass_error").html("Bitte E-Mail und Passwort prüfen").addClass("error-msg");

//         //$("#credential_pass_error").html('Bitte E-Mail und Passwort prüfen');
//         setTimeout(function() {
//           $("#credential_pass_error").hide();
//         }, 8000);
//       }

//     }
//   });

// });

$("#frmsubpassID").validate({

  submitHandler: function(form) {
    var identity = $('#email-data').val();

    // var password = $('#loginPassHideShow').val();

    $.ajax({
      url: base_url + 'Auth/emailschk',
      type: "POST",
      data: {
        email: identity
      },
      success: function(response) {

        // var obj = jQuery.parseJSON(response);
        response = JSON.parse(response);
        if (response.success == 1) {
          // alert(response);
          //alert("success",obj.success)
          //  $("#deactivate_profile_model").modal('show');
          $("#forgotPasswordPopup").modal('show');
          $("#match_emails_success").html(response.message);
          $('#email-data').val('');
          console.log(response);
        } else {
          //alert(response);
          console.log(response);
          // alert("error",obj.success)
          $("#forgotPasswordPopup").modal('show');
          $("#match_emails_error").html('Kein Nutzerkonto mit dieser E-Mail Adresse vorhanden');
          $('#email-data').val('');
          //$("#match_pass_error").html(obj.msg);
        }
        setTimeout(function() {
          $("#match_emails_success").hide();
        }, 9000);
        setTimeout(function() {
          $("#match_emails_error").hide();
        }, 9000);
        $("#forgotPasswordPopup").modal('show');

      }
    });
    $("#forgotPasswordPopup").modal('show');
  }
});

$(document).on('click', '#frmsubpassID--', function() {



});




$(document).on('click', '#deactivate_profile', function() {
  Swal.fire({
    title: '<?php echo $this->lang->line("are_you_sure"); ?>',
    text: "Sie möchten Ihr Konto löschen? Diese Aktion ist irreversibel.",
    type: 'warning',
    showCancelButton: true,
    reverseButtons: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Bestätigen'
  }).then((result) => {
    if (result.value) {
      var status = localStorage.getItem('STATUS');
      if (status == 'LOGGED_OUT') {
        console.log('STATUS=' + status);
        console.log(base_url)
        window.location.href = base_url;
        $(window.location)[0].replace(base_url);
        $(location).attr('href', base_url);
      }
      console.log('STATUS=' + status);
      $.ajax({
        url: base_url + 'profile/delete_account_otp_sent',
        type: "POST",
        success: function(response) {
          var obj = jQuery.parseJSON(response);
          if (obj.success == 1) {
            $("#deactivate_profile_model").modal('show');
            //$("#match_pass_error").html(obj.msg);
            $("#match_pass_error").html('');
            $("#delete_account_otp").val('');
          } else {
            $("#match_pass_error").html(obj.msg);
          }

        }
      });
    } else {
      return false;
    }
  });

});

$(document).on('click', '#resendOtp', function() {
  $("#match_pass_error").html('');
  $("#delete_account_otp").val('');
});


$(document).on('click', '#deleteAccount', function() {
  var pass = $('#delete_account_otp').val();
  var status = localStorage.getItem('STATUS');
  if (status == 'LOGGED_OUT') {
    console.log('STATUS=' + status);
    console.log(base_url)
    window.location.href = base_url;
    $(window.location)[0].replace(base_url);
    $(location).attr('href', base_url);
  }
  console.log('STATUS=' + status);
  $.ajax({
    url: base_url + 'profile/delete_account',
    type: "POST",
    data: {
      otp: pass
    },
    success: function(response) {
      var obj = jQuery.parseJSON(response);
      if (obj.success == 1) {
        location.href = obj.url;
      } else {
        $("#match_pass_error").html(obj.msg);
      }

    }
  });

});
$(document).on('click', '#deleteAccountResend', function() {
  var pass = $('#delete_account_otp').val();
  var status = localStorage.getItem('STATUS');
  if (status == 'LOGGED_OUT') {
    console.log('STATUS=' + status);
    console.log(base_url)
    window.location.href = base_url;
    $(window.location)[0].replace(base_url);
    $(location).attr('href', base_url);
  }
  console.log('STATUS=' + status);
  $.ajax({
    url: base_url + 'profile/delete_account',
    type: "POST",
    data: {
      otp: pass
    },
    success: function(response) {
      var obj = jQuery.parseJSON(response);
      if (obj.success == 1) {
        location.href = obj.url;
      } else {
        $("#match_pass_error").html(obj.msg);
      }

    }
  });

});

$(document).on('click', '.login_facebook_btn,.login_google_btn', function() {
  var url = location.href;
  $.ajax({
    url: base_url + 'home/set_redirect_cookies',
    type: "POST",
    data: {
      url: url
    },
    success: function(response) {}
  });
});

$(document).on('click', '.openLoginPopup', function() {

  $("#service-loging-check").modal('hide');
  $(".modal").modal('hide')
  $("#openLoginPopup").modal('show');

});

$(document).on('click', '#frg_password', function() {

  $("#service-loging-check").modal('hide');
  $(".modal").modal('hide')
  $("#forgotPasswordPopup").modal('show');

});

$(document).on('click', '#frmsubpass_forPassword', function() {

  //  $("#service-loging-check").modal('hide');
  //  $(".modal").modal('hide')
  //  $("#forgotPasswordPopup").modal('show');
  // var bid=$(this).attr('data-bid');
  var id = $("#email-data").val();
  alert(id);

});


function deleteImgaeConfirm(id = '') {
  Swal.fire({
    title: '<?php echo $this->lang->line("are_you_sure"); ?>',
    text: "<?php echo $this->lang->line("you_want_delete_image"); ?>",
    type: 'warning',
    showCancelButton: true,
    reverseButtons: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Bestätigen'
  }).then((result) => {
    if (result.value) {
      var status = localStorage.getItem('STATUS');
      if (status == 'LOGGED_OUT') {
        console.log('STATUS=' + status);
        console.log(base_url)
        window.location.href = base_url;
        $(window.location)[0].replace(base_url);
        $(location).attr('href', base_url);
      }
      console.log('STATUS=' + status);
      $.ajax({
        url: base_url + 'profile/delete_profile_image',
        type: "POST",
        data: {
          id: id
        },
        success: function(response) {
          $("#EmpProfile").attr("src", base_url + 'assets/frontend/images/upload_dummy_img.svg');
          $("#divclss").removeClass("round-employee-upload");
          $("#alltype_cls").removeClass("bgblack18 text-center");
          $("#spamCls").hide();
          $("#editiconCls").show();
          $("#rm_picture").hide();
          $("#chgemp_img" + id).attr("src", base_url + 'assets/frontend/images/user-icon-gret.svg');
          //location.reload();

        }
      });

    } else {
      return false;
    }
  });

}
$(document).on('click', '#close_resch', function() {
  var bid = $(this).attr('data-bid');
  //var bid=$(this).val('data-bid');
  if (bid != "") {

    getBokkingDetail(bid);

  }
});

$(document).on('click', '.commoncancel_cal', function() {
  var bid = $(this).attr('data-bid');
  //var bid=$(this).val('data-bid');
  if (bid != "") {

    getBokkingDetail(bid);

  }
});

$(document).on('click', "#accept_cookie", function() {
  $.post(base_url + "home/accept_cookie", function(data) {

  });

});

$(document).on('click', ".header-menu-cat", function() {

  var id = $(this).attr('data-id');


  if ($(this).hasClass('yes')) {
    $('.after-overlay-on-click-toggle').css('display', 'none');
    //alert('if');
    $(this).removeClass('yes');
    $("#" + id).removeClass('show');

  } else {
    $(".header-menu-cat").removeClass('yes');
    $('.after-overlay-on-click-toggle').css('display', 'block');
    //alert(id);
    $('.supermenu-drop.show').each(function() {
      $(this).removeClass('show');

    });
    $(this).addClass('yes');
    $("#" + id).addClass('show');
  }
});
$(document).on('click', ".after-overlay-on-click-toggle", function() {
  $('.header-menu-cat').each(function() {
    $(this).removeClass('yes');
  });

  $('.supermenu-drop').each(function() {
    $(this).removeClass('show');
    $('.header-menu-cat').removeClass('yes');
    $('.after-overlay-on-click-toggle').css('display', 'none');
  });
});


<?php if($this->session->userdata('access')=='marchant'){ ?>
crone(0);

function crone(id) {

  $.ajax({
    url: base_url + 'cron/get_recently_review',
    type: "POST",
    success: function(response) {
      var obj = jQuery.parseJSON(response);
      if (obj.success == 1) {
        var sound = 'no';
        var soundType = 0;
        var c_notify = 0;
        if (obj.count >= 1) {
          if (Number($("#chgreview_count").text()) != Number(obj.count)) {
            sound = 'yes';
            var soundType = 1;
          }
          $("#chgreview_count").show();
          $("#chgreview_count").text(obj.count);
          c_notify = Number(c_notify) + Number(obj.count);

        } else {
          $("#chgreview_count").hide();
        }

        if (obj.bookingCount >= 1) {
          if (Number($("#booking_count").text()) != Number(obj.bookingCount)) {
            // alert($("#booking_count").text()+'=='+Number(obj.bookingCount)); 
            sound = 'yes';
          }
          $("#booking_count").show();
          $("#booking_count").text(obj.bookingCount);
          c_notify = Number(c_notify) + Number(obj.bookingCount);

        } else {
          $("#booking_count").hide();
        }

        if (obj.cancelCount >= 1) {
          if (Number($("#chgcancel_count").text()) != Number(obj.cancelCount)) {
            sound = 'yes';
          }
          //$("#booking_count").show();
          //$("#booking_count").text(obj.cancelCount);
          //c_notify=Number(c_notify)+Number(obj.cancelCount);
          $("#chgcancel_count").show();
          $("#chgcancel_count").text(obj.cancelCount);
          c_notify = Number(c_notify) + Number(obj.cancelCount);


        } else {
          $("#chgcancel_count").hide();
        }

        if (c_notify != 0) {
          $("#all_notify_count").show();
          $("#all_notify_count").text(c_notify);
          $("#chg_nofifyImg").attr("src", base_url + "assets/frontend/images/notification_fill.svg");
        } else {
          $("#all_notify_count").hide();
          $("#chg_nofifyImg").attr("src", base_url + "assets/frontend/images/notification.svg");
        }



        if (sound == 'yes' && id == 1) {
          playAudio(soundType);
        }

      } else {
        $("#chgreview_count").hide();
        return false;
      }

    }
  });

}
setInterval(function() {

  crone(1);

  //    }, 10000);
}, 60000);

function unlockAudio() {
    const sound = new Audio('<?php echo base_url('assets/frontend/css/noti_sound.mp3'); ?>');

    sound.play();
    sound.pause();
    sound.currentTime = 0;

    document.body.removeEventListener('click', unlockAudio)
    document.body.removeEventListener('touchstart', unlockAudio)
}


document.body.addEventListener('click', unlockAudio);
document.body.addEventListener('touchstart', unlockAudio);

function playAudio(soundType) {
  var checkStting = "<?php echo $this->session->userdata('sound_setting'); ?>";
  //alert(soundType);
  if (checkStting == "1" || window.glb_sound_setting == 1) {
    if (soundType == 1) {
      //alert(soundType);
      var x = document.getElementById("myAudio1");
      x.autoplay = true;
      x.play();
    } else {
      // alert(soundType);
      var x = document.getElementById("myAudio");
      x.autoplay = true;
      x.play();
    }
  }
}

<?php } ?>

$(document).on('click', "#mobile-btn-toggle", function() {
  $('.dropdown').addClass('show');
  $('.drop_down_show').addClass('show');
  $('#rem_toggle').removeAttr('data-toggle');
});

$( ".calender-btn" ).click(function() {
  $(this).focus();
});

<?php if(!empty($_GET['page']) && $_GET['page'] =='login' && $this->session->userdata('access')==""){ ?>
$(".openLoginPopup").trigger("click");
<?php } ?>

<?php if(!empty($_GET['page']) && $_GET['page'] =='registration' && $this->session->userdata('access')==""){ ?>
$(".openRegisterPopup").trigger("click");
<?php } ?>

<?php if(!empty($_GET['bid']) && $this->session->userdata('access')==""){ ?>
$(".openLoginPopup").trigger("click");
<?php } ?>
</script>
<script type="text/javascript">
$(function() {
  $('[data-toggle="tooltip"]').tooltip({
    boundary: 'window'
  })
})

function toggleFullScreen() {
  if (
    (document.fullScreenElement && document.fullScreenElement !== null) ||
    (!document.mozFullScreen && !document.webkitIsFullScreen)
  ) {
    if (document.documentElement.requestFullScreen) {
      document.documentElement.requestFullScreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullScreen) {
      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
    } else if (document.documentElement.msRequestFullscreen) {
      if (document.msFullscreenElement) {
        document.msExitFullscreen();
      } else {
        document.documentElement.msRequestFullscreen();
      }
    }
    //$("#chg_fullClass").removeClass('mt-20');
    $("#zoom_icon_click").removeClass('fa-expand-arrows-alt');
    $("#zoom_icon_click").addClass('fa-compress');
  } else {
    if (document.cancelFullScreen) {
      document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
      document.webkitCancelFullScreen();
    }
    //$("#chg_fullClass").addClass('mt-20');
    $("#zoom_icon_click").removeClass('fa-compress');
    $("#zoom_icon_click").addClass('fa-expand-arrows-alt');


  }
}

$(".toggle-fullscreen").click(function() {

  toggleFullScreen();
  $("#chg_hover").toggleClass('open manizoom');


});

$(document).ready(function() {

  var date = new Date();
  var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

  var date40year = new Date('<?php  echo date("Y-m-d",strtotime("-90 years",time())); ?>');
  //var date40yearbefor =new Date(date40year.getFullYear(), date40year.getMonth(), date40year.getDate());

  // $(".dobDatepicker").datepicker({
  //       uiLibrary: 'bootstrap4',
  //       format:"dd-mm-yyyy",
  //       maxDate:today
  //   });   
  $(function() {
    $(".dobDatepicker").datepicker({
      beforeShow: function(input, inst) {
        $(document).off('focusin.bs.modal');
      },
      onClose: function() {
        $(document).on('focusin.bs.modal');
      },
      // uiLibrary: 'bootstrap4',
      // locale: 'de-de',
      // format:"dd.mm.yyyy",
      changeMonth: true,
      changeYear: true,
      // defaultDate: '<?php  echo date("d-m-Y",strtotime("-20 years",time())); ?>',
      maxDate: today,
      yearRange: date40year.getFullYear() + ':' + date.getFullYear(),
      prevText: '&#x3c;zurück', prevStatus: '',
      prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
      nextText: 'Vor&#x3e;', nextStatus: '',
      firstDay: 1,
      nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
      currentText: 'heute', currentStatus: '',
      todayText: 'heute', todayStatus: '',
      clearText: '-', clearStatus: '',
      closeText: 'schließen', closeStatus: '',
      monthNames: ['Januar','Februar','März','April','Mai','Juni',
      'Juli','August','September','Oktober','November','Dezember'],
      monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
      dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
      dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
      dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
      dateFormat: 'dd.mm.yy',
    }).val()
  });
  var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;

  $.fn.modal.Constructor.prototype.enforceFocus = function() {};

  // $confModal.on('hidden', function() {
  //   $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
  // });

  // $confModal.modal({
  //   backdrop: false
  // });

  //$(".messageAlert").addClass('display-n');
  setTimeout(function() {
    $(".messageAlert").toggleClass('display-n');
  }, 200)

  $('.btn').addClass('effect');
  $('.btn.dropdown-toggle').removeClass('effect');









})

// $(".dropdown-menu").parent().on("shown.bs.dropdown", function() {
//   var el = $(this).find(".dropdown-menu li input:checked");
//   if (el.length) {
//     var delta = $(el[0]).parent().position().top;
//     $(this).find(".dropdown-menu").scrollTop(
//       $(this).find(".dropdown-menu").scrollTop() + delta
//     );
//   }
// });
$(document).on("shown.bs.dropdown", ".multi_sigle_select", function() {
  var el = $(this).find(".dropdown-menu li input:checked");
  if (el.length) {
    var delta = $(el[0]).parent().position().top;
    $(this).find(".dropdown-menu").scrollTop(
      $(this).find(".dropdown-menu").scrollTop() + delta
    );
  }
});

$(document).on('click', '#delete_customer', function() {
  //alert('d');
  //var id = $('#bookingid').val();
  $(".cust_close").trigger('click');
  //$("#clientViewProfileHtml").hide();
  var id = $(this).attr('data-uid');
  Swal.fire({
    title: '',
    html: "<?php echo $this->lang->line('You_want_delete_customer_recover'); ?>",
    type: 'warning',
    showCancelButton: true,
    reverseButtons: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Bestätigen'
  }).then((result) => {
    if (result.value) {
      loading();
      $.ajax({
        url: base_url + "merchant/delete_single_customer",
        type: "POST",
        data: {
          id: id
        },
        success: function(data) {
          var obj = jQuery.parseJSON(data);
          location.reload();
          unloading();
        }

      });

    } else {
      getClientProfile(id);
    }
  });


});

//  thankyou modal register script
$(document).on('click', '#resend_activelink_thk', function() {

  var value = $('#get_email').val();
  /*if(value==""){
   $("#identity_validate").append('<label for="identity" generated="true" class="error">Please enter a valid email address.</label>');
   return false;
  }else{*/
  loading();
  $.ajax({
    url: base_url + "auth/resend_activation_mail",
    type: "POST",
    data: {
      email: value
    },
    success: function(response) {
      var obj = jQuery.parseJSON(response);
      if (obj.success == '1') {
        unloading();
        $("#alert_sucmessage_thk").html('<strong>Message ! </strong>' + obj.message);
        $(".alert_sucmessage_thk").css('display', 'block');
        $(".alert_message_thk").css('display', 'none');
        $("#resend_activelink_thk").hide();

      } else {
        unloading();
        $("#alert_message_thk").html('<strong>Message ! </strong>' + obj.message);
        $(".alert_message_thk").css('display', 'block');
        $(".alert_sucmessage_thk").css('display', 'none');

      }

    }

  });
  /*}*/

});
</script>

<?php 
    
    $this->load->view('frontend/marchant/profile/client_profile_view_js');
    
    if(!isset($_COOKIE['side_nav_status']))
                   $cls="hide-left-bar";
                else if($_COOKIE['side_nav_status'] =="open"){ 
                     $cls="hide-left-bar left-bar-dask"; ?>
<script type="text/javascript">
$('.testts').prop('title', '');
</script>
<?php }
                else{ ?>
<script type="text/javascript">
$('.testts').each(function() {
  var tt = $(this).attr('data-temp');
  $(this).prop('title', tt);
});
</script>

<?php 
                }
                ?>
</body>

</html>