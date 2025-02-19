<?php $this->load->view('frontend/common/header'); ?>
    <!-- start mid content section-->
    <section class="pt-84 clear">
      <div class="membership_plan_section1">
        <div class="container">
          <div class="row pt-90">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
              <h1 class="colorwhite font-size-34 fontfamily-semibold before_white_line relative lineheight50">Select Your Own <br> Membership Plan</h1>
              <p class="colorwhite font-size-14 fontfamily-regular pt-30 lineheight22">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature. Contrary to popular belief.</p>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
              <img src="<?php echo base_url('assets/frontend/'); ?>images/membership-plan-vectore-img.png" class="membership-plan-vectore-img">
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="clear membership_plan_section2 pt-60 pb-60">
      <div class="container">
        <div class="text-center mb-50">
          <h1 class="color333 font-size-34 fontfamily-semibold before_cyan_line relative mb-30">Membership Pricing</h1>
          <p class="color333 font-size-16 fontfamily-regular lineheight30 pt-30">Contrary to popular belief, Lorem Ipsum is not simply random text. It has <br> roots in a piece of classical Latin literature from 45 BC, making it over 2000 years.</p>
        </div>
        <div class="row">
          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="d-flex membership_white_box box-shadow4 bgwhite">
              <div class="bgorange text-right">
                <div class="relative">
                  <h1 class="colorwhite fontfamily-regular font-size-50 mb-0">€10</h1>
                  <span class="colorwhite fontfamily-light font-size-16 ">Monthly</span>
                </div>
              </div>

              <div class="relative pl-25 pr-25 pt-75">
                  <h1 class="color333 fontfamily-medium font-size-24 lineheight30 mb-3">Membership Plan Name</h1>
                  <p class="color999 fontfamily-regular font-size-16 ">Iterature Classic</p>
                  <p class="color999 fontfamily-regular font-size-16 ">Latin Literature</p>
                  <p class="color999 fontfamily-regular font-size-16 ">Classic Literature</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-10 offset-md-1 col-lg-6 offset-lg-0 col-xl-6 offset-xl-0">
            <div class="relative around-35">
              <h1 class="color333 fontsize-35 fontfamily-medium lineheight46">Grab Our <br> One Month Plan</h1>
              <p class="color333 font-size-16 fontfamily-regular lineheight30">This Membership plan will be a monthly plan will get auto renewed every month. You needs to choose a plan and make payment.</p>
              <a href="<?php echo base_url("membership/payment"); ?>"><button class="btn btn-large widthfit">Next</button></a>
            </div>
          </div>
        </div>
      </div>
    </section>

  <?php $this->load->view('frontend/common/footer');  ?>
