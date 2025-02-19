<?php $this->load->view('frontend/common/header'); ?>
<style>
.thankyou-panel {
  padding-left: 75px;
}
@media(max-width: 767px) {
  .thankyou-panel {
    padding-left: 0px;
  } 
}
</style>
<div class="d-flex pt-84">
  <?php $this->load->view('frontend/common/sidebar'); ?>  
  <div class="w-100 thankyou-panel">
    <section class="service_provider_registration_done_thankyou">
        <div class="container">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 d-flex justify-content-center">
            <div class="align-center pt-20 pb-20 mt-50">
              <div class="thankyou_top_img_block2">
                <img src="<?php echo base_url('assets/frontend/images/thankyou_top_img2.svg'); ?>" class="thankyou_top_img2">
              </div>
              <div class="relative mt-25">
                <h1 class="fontfamily-medium mb-0" style="color:#E8E8E8;font-size:2.5rem;">Willkommen an Bord bei styletimer!</h1>
                <img src="<?php echo base_url('assets/frontend/images/thankyou_mid_line_img.png'); ?>" class="thankyou_mid_line_img">
              </div>
                <div class="mt-50">
                  <h4 class="color333 fontfamily-medium font-size-20" style="font-weight:bold;">Deine Mitgliedschaft wurde erfolgreich aktiviert.</h4>
                  <p class="mt-30 mb-30 color666 font-size-16 fontfamily-regular lineheight30">Klicke auf den unteren Link, um wieder zurück in deine Salon Übersicht zu gelangen.</p>
                  <a href="<?php echo base_url('merchant/dashboard'); ?>"><button type="button" class="btn widthfit">zu deinem Kalender</button></a>
                </div>
            </div>
          </div>
        </div>
      </section>
  </div>
 <?php $this->load->view('frontend/common/footer_script'); ?>
</div>