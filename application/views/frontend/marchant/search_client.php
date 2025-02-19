<style type="text/css">
  body{
    background: #FFF!important;
  }
  .on-hover-div a span,
  .scroll-effect a span{
    color: #999;
  } 
  .crose-btn .popup-crose-black-icon {
    width: 25px !important;
    height: 25px !important;
}
.crose-btn {
    position: absolute !important;
    top: 15px !important;
    right: 16px !important;
    z-index: 1 !important;
}
</style>
<?php $this->load->view('frontend/common/head'); ?>
   <?php //echo "<pre>";//print_r($client_list);
            //echo "<pre>";print_r($booking_list); die;?>
  


<!-- search wala modal -->
<div class="modal fade" id="search-wala-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
    <div class="modal-content">      
      <!-- <a href="#" class="crose-btn" data-dismiss="modal" id="conf_close">
        <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg'); ?>" class="popup-crose-black-icon">
      </a> -->
      <a href="<?php echo base_url('merchant/dashboard'); ?>" class="crose-btn" >
      <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
      </a>
      <div class="modal-body p-0">
      <div class="search-icon-wala-popup">
        <div class="full-popup ">
            
            <div class="relative h-100">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                  <input type="text" class="new-input-vinu-bottom" placeholder="<?php echo $this->lang->line('What-are-looking'); ?> ?" id="search_client12" name="search_client">
                </div>
                <span class="color666 d-block mb-2"><?php echo $this->lang->line('search-by-client'); ?></span>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p class="color333 fontsize-14 fontfamily-medium"><?php echo $this->lang->line('Upcoming-appointments'); ?></p>
                <div id="booking_filter_list" class="scroll-effect h-100 max-height530">
                 
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p class="color333 fontsize-14 fontfamily-medium"><?php echo $this->lang->line('Clients(recentlyadded)'); ?></p>
                <div id="client_filter_list" class="scroll-effect h-100 max-height530">
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
      </div>
    </div>
  </div>
</div>
     
  <?php $this->load->view('frontend/common/footer_script'); ?>

   <script type="text/javascript">

     </script>
