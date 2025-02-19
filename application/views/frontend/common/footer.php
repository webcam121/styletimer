  <!-- start footer section-->
  <?php $links = get_footer_links(); ?>
    <footer class="clear footer_section bggreengradient relative pt-65">
      <div class="container">
        <div class="relative pl-35 pr-35">
             <div class="row">
                    <div class="col-xl-5 col-lg-5 offset-xl-0 offset-lg-0 col-md-6 col-sm-8 col-xs-12 mb-25">
                          
                          <div class="relative">
                              <h4 class="font-size-16 fontfamily-regular colorwhite mb-15"><?php echo $this->lang->line('Get-The-App'); ?></h4>
                                <div class="relative">
                                <a href="<?php echo ANDROID_LINK; ?>" target="_blank" class="">
                                  <picture class="">
                                    <source srcset="
                                    <?php echo base_url("assets/frontend/images/google-play-and-app-store-badges.webp"); ?>" type="image/webp" class="">
                                    <source srcset="<?php echo base_url("assets/frontend/images/google-play-and-app-store-badges.png"); ?>" type="image/png" class="">
                                    <img src="<?php echo base_url("assets/frontend/images/google-play-and-app-store-badges.png"); ?>" class="">
                                  </picture>
                                </a>
                                <a href="<?php echo IOS_LINK; ?>" target="_blank" class="">
                                  <picture class="">
                                    <source srcset="
                                    <?php echo base_url("assets/frontend/images/google-play-and-app-store-badges1.webp"); ?>" type="image/webp" class="">
                                    <source srcset="<?php echo base_url("assets/frontend/images/google-play-and-app-store-badges1.png"); ?>" type="image/png" class="">
                                    <img src="<?php echo base_url("assets/frontend/images/google-play-and-app-store-badges1.png"); ?>" class="">
                                  </picture>
                                </a>
                                </div>
                          </div>
                          <div class="relative">
                                <img src="<?php echo base_url('assets/frontend/images/footer_logo_icon.png'); ?>" class="mt-30 footer_logo_icon" />
                          </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-6 col-xs-12 mb-25">
                         <div class="relative">
                              <h3 class="font-size-20 colorwhite fontfamily-regular mb-25">Quick Links</h3>
                              <ul class="mb-0 pl-0"> 
                                <li class="mb-10"><a href="<?php echo base_url('rechner'); ?>" class="colorwhite a_hover_white font-size-14 fontfamily-light">Rechner</a></li>
                                <li class="mb-10"><a href="<?php echo base_url('salon/registrieren'); ?>" class="colorwhite a_hover_white font-size-14 fontfamily-light"><?php echo $links->reg_salon; ?></a></li>
                                <li class="mb-10"><a href="<?php echo base_url(); ?>" class="colorwhite a_hover_white font-size-14 fontfamily-light"><?php echo $links->book_appointment; ?></a></li>
                               
                                <li class="mb-10"><a href="<?php echo base_url('contact'); ?>" class="colorwhite a_hover_white font-size-14 fontfamily-light"><?php echo $links->contact_us; ?></a></li>
                              </ul>
                         </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 mb-25">
                         <div class="relative">
                          <?php if($this->session->userdata('access')=='marchant')
                                  $accs="merchant";
                                else
                                  $accs="user";
                           ?>
                              <h3 class="font-size-20 colorwhite fontfamily-regular mb-25">About Us</h3>
                              <ul class="mb-0 pl-0">
                                <li class="mb-10"><a href="<?php echo base_url('about'); ?>" class="colorwhite a_hover_white font-size-14 fontfamily-light"><?php echo $links->about_us; ?></a></li>
                                <li class="mb-10"><a href="javascript:void(0)" class="colorwhite a_hover_white font-size-14 fontfamily-light popup_terms" data-type="conditions" data-access="<?php echo $accs ?>"><?php echo $links->conditions; ?></a></li>
                                <li class="mb-10"><a href="javascript:void(0)" class="colorwhite a_hover_white font-size-14 fontfamily-light popup_terms" data-type="terms" data-access="<?php echo $accs ?>"><?php echo $links->terms_of_use; ?></a></li>
                                <li class="mb-10"><a href="<?php echo base_url('imprint'); ?>" class="colorwhite a_hover_white font-size-14 fontfamily-light"><?php echo $links->imprint; ?></a></li>
                              </ul>
                         </div>
                    </div>
                  </div>
        </div>
        <div class="row border-t2">
          <div class="col-xl-6 col-lg-6 col-md-8 col-sm-8 col-xs-12">
               <div class="relative around-25 pl-35 pr-35">
                 <p class="mb-0 colorwhite fontfamily-light font-size-14">© Copyright styletimer <?php echo date('Y'); ?> | All Rights Reserved</p>
               </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-4 col-sm-4 col-xs-12">
               <div class="relative around-25 pl-35 pr-35 text-right">
                 <a href="<?php echo FACEBOOK_LINK; ?>" target="_blank" class="ml-15">
                 <?php /*  <picture class="">
                    <source srcset="<?php echo base_url('assets/frontend/images/footer_social_facebook_icon.webp'); ?>" type="image/webp" class="width10footer">
                    <source srcset="<?php echo base_url('assets/frontend/images/footer_social_facebook_icon.png'); ?>" type="image/png" class="width10footer">
                    <img src="<?php echo base_url('assets/frontend/images/footer_social_facebook_icon.png'); ?>" class="width10footer">
                  </picture> */ ?>
                  <img src="<?php echo base_url('assets/frontend/images/footer_social_facebook_icon.svg'); ?>" class="width10footer">
                </a>
                 <a href="<?php echo INSTAGRAM_LINK; ?>" target="_blank" class="ml-15">
                  <img src="<?php echo base_url('assets/frontend/images/footer_social_instagram_icon.svg'); ?>" class="width22footer">
                </a>
              
               </div>
          </div>
        </div>
      </div>      
    </footer>
     <?php /*  if(empty($_COOKIE['styletimer_cookie_accept'])){ //print_r($_COOKIE);
	  ?>
   <div class="alert alert-secondary alert-dismissible coocky_tab">
    <button type="button" class="close" id="accept_cookie" data-dismiss="alert">&times;</button>
    We use cookies to enhance your user experience, improve our site and provide tailored offers on Styletimer and other sites. By continuing to browse you agree to our <a href="javascript:void(0)" class="text-underline colorcyan a_hover_cyan popup_terms" data-access="<?php echo $accs; ?>" data-type="policy">cookie policy</a>.
  </div>
  <?php } */ ?>
  
  
    <div id="salon-list-blank" class="modal fade pr-0">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <a href="#" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
        </a>
        <div class="modal-body pt-30 mb-20 pl-25 pr-25 relative">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <div class="relative">
                <h3 class="font-size-20 color333 fontfamily-medium mb-4"><?php echo $this->lang->line('Add-Salon'); ?></h3>
                <form method="post" id="contact_for_salon" action="<?php echo base_url('home/contact_for_salon') ?>">
                  <div class="form-group form-group-mb-50" id="name_validate">
                     <label class="inp v_inp_new">
                       <input type="text" placeholder="&nbsp;" name="name" class="form-control height56v">
                       <span class="label"><?php echo $this->lang->line('Your-Name'); ?></span>                               
                     </label>                                                
                  </div>
                  <div class="form-group form-group-mb-50" id="salon_name_validate">
                     <label class="inp v_inp_new">
                       <input type="text" placeholder="&nbsp;" name="salon_name"  class="form-control height56v">
                       <span class="label"><?php echo $this->lang->line('Name-of-Salon'); ?></span>                               
                     </label>                                                
                  </div>
                  <div class="form-group form-group-mb-50" id="salon_city_validate">
                     <label class="inp v_inp_new">
                       <input type="text" placeholder="&nbsp;" name="salon_city" class="form-control height56v">
                       <span class="label labels"style="position:absolute;top: 10px;/* margin-bottom: 20px; */ width:100%;"><?php echo $this->lang->line('City-of-Salon'); ?></span>                               
                     </label>                                                
                  </div>
                  <button class="btn btn-large"><?php echo $this->lang->line('Save'); ?></a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .swal2-image{
      width: 90px;
    height: 90px;
    }
    
    .labels{
      margin-top: 10px;
}
    }
  </style>

   <!-- modal end -->
  
 <!-- Optional JavaScript -->
 <!-- jQuery first, then Popper.js, then Bootstrap JS -->
 <?php $this->load->view('frontend/common/footer_script');
      
  ?>
    

