<?php $this->load->view('frontend/common/head'); ?>
<section class="booking_confirmation_thankyou-section">
    <div class="container">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 d-flex height-vh justify-content-center">
        <div class="align-center pt-20 pb-20 align-self-center">
          <div class="thankyou_top_img_block">
            <img src="<?php echo base_url('assets/frontend/images/thankyou_top_img.png'); ?>" class="thankyou_top_img">
          </div>
          <div class="relative">
            <h1 class="fontfamily-medium font-size-70 colorlightgreen2 mb-0">Thank you!</h1>
            <img src="<?php echo base_url('assets/frontend/images/thankyou_mid_line_img.svg'); ?>" class="thankyou_mid_line_img">
          </div>
            <div class="mt-50">
              <h4 class="color333 fontfamily-medium font-size-30">Services Booked Successfull</h4>
              <p class="mt-30 mb-30 color666 font-size-20 fontfamily-regular">You service booking has been done successfully. We have sent a confirmation<br> mail to your registered Email Id. </p>
              <a href="<?php echo base_url(); ?>"><button type="button" class="btn widthfit">Book Another Service</button></a>
            </div>
        </div>
      </div>
    </div>
  </section>

 <?php $this->load->view('frontend/common/footer_script'); ?>