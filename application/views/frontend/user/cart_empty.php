<?php $this->load->view('frontend/common/header'); ?>



    <section class="empty_section1 clear pt-120">
      <div class="container">
        <div class="relative bgwhite border-radius4 boxshadow-1 d-flex justify-content-center align-items-center mt-60 mb-60" style="height:100vh;">
          <div class="relative text-center">
            <img src="<?php echo base_url('assets/frontend/images/empty-icon.png'); ?>" class="mb-4">
            <p class="font-size-20 color333 fontfamily-medium">Dein Warenkorb ist leer</p>
            <p class="color666 fontfamily-medium font-size-16 cursol-p "><img  src="<?php echo base_url('assets/frontend/images/add_blue_plus_icon.svg'); ?>" class="mr-2 width32" style="height: auto;width: 30px;">Füge Behandlungen von <a href="<?php echo base_url('user/service_provider/'.$merchant_id); ?>" class="colorcyan a_hover_cyan  text-underline display-ib"><?php if(!empty($salon_detail)) echo $salon_detail->business_name; ?></a> hinzu</p>
          </div>
        </div>
      </div>
    </section>
    <!-- modal end -->

<?php // $this->load->view('frontend/common/footer');

 ?>


