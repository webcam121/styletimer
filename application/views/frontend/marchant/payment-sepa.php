<?php $this->load->view('frontend/common/header'); ?>
<!-- dashboard left side end -->
<style>
	.alert.alert-danger.vinay {

    padding: 7px 12px;
    margin-top: 4px;
   
   
}
	</style>
 <div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>	
<div class="right-side-dashbord w-100 pl-30 pr-30">
    <section class="clear membership_payment_section2 v1 pt-50 pb-60 bgwight">
      <div class="container">
        <div class="text-center mb-20">
            
          <p class="font-size-24 color333 fontfamily-medium">SEPA Direct Debit payments</p>
          <h1 class="font-size-50 colororange fontfamily-regular display-ib">€<?php if(!empty($planDetail->price)) echo $planDetail->price; ?><small class="fontfamily-light colororange font-size-16 display-ib">/ Montly</small></h1>
        </div>
        <div class="row">
            
          <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 offset-xl-2 offset-lg-1">
              
            <div class="relative border-w border-radius4 pt-50 pl-20 pr-20 pb-50 mb-4" style="background: #fff;">
                <?php $this->load->view('frontend/common/alert'); ?>
                <form id="paymentForm">
              <div class="row">
               
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <div class="form-group form-group-mb-50 pt-3" id="">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="" class="form-control">
                          <span class="label">Name</span>
                        </label>
                      </div>
                      <div class="form-group form-group-mb-50" id="">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="" class="form-control">
                          <span class="label">Email</span>
                        </label>
                      </div>
                      <div class="form-group form-group-mb-50" id="">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="" class="form-control">
                          <span class="label">Account Number</span>
                        </label>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 offset-sm-3 col-md-6 offset-md-0 col-lg-6 offset-lg-0 col-xl-6 offset-xl-0">
                      <button class="btn btn-large" type="button" id="">Pay</button>
                    </div>
                  </div>
                </div>
               
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center">
                  <img src="<?php echo base_url('assets/frontend/'); ?>images/payment-muchin-img.png" class="payment-muchin-img mt-20">
                </div>  
                <input type="hidden" name="planid" value="<?php if(!empty($plan_id)) echo $plan_id; ?>">             
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
   </div>
</div>

  <?php $this->load->view('frontend/common/footer_script');  ?>   