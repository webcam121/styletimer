<?php $this->load->view('frontend/common/header'); ?>
<style>
	.alert_message{
		margin:4px 0px 0px -30px !important;
		}
		
	</style>
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


    <section class="clear membership_payment_section2 pt-100 pb-50">
      <div class="container">
        <div class="text-center mb-20">
			
          <p class="font-size-16 color333 fontfamily-medium">Amount need to pay</p>
          <h1 class="font-size-50 colororange fontfamily-regular display-ib">€10<small class="fontfamily-light colororange font-size-16 display-ib">/ Montly</small></h1>
        </div>
        <div class="row">
			
          <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 offset-xl-2 offset-lg-1">
			  
            <div class="relative border-w border-radius4 pt-60 pl-30 pr-20 pb-60">
				<?php $this->load->view('frontend/common/alert'); ?>
				<form id="paymentForm">
              <div class="row">
               
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <div class="form-group form-group-mb-50 pt-3" id="card_number_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="card_number" class="form-control">
                          <span class="label">Card Number</span>
                        </label>
                      </div>
                      <div class="form-group" id="nameofcarthoder_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="nameofcarthoder" class="form-control">
                          <span class="label">Name on Card</span>
                        </label>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                      <label class="color999 fontfamily-light font-size-12 mb-1">Expiry Date</label>
                      <div class="relative form-group-mb-50" id="expir_month_year_validate">
						   <div class="input-group">
                        <div class="input-group-prepend ">
                          <span class="input-group-text bge8e8e8 pl-1 pr-1">MM/YY</span>
                        </div>
                        <input type="text" class="form-control" id="yearmonth" name="expir_month_year" placeholder="">
                      </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                      <label class="color999 fontfamily-light font-size-12 mb-1">CVV Code</label>
                      <div class="relative form-group-mb-50" id="cvv_validate">
						  <div class="input-group">
                        <div class="input-group-prepend ">
                          <span class="input-group-text bge8e8e8">CVV</span>
                        </div>
                        <input type="text" class="form-control" name="cvv" placeholder="">
                      </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 offset-sm-3 col-md-6 offset-md-0 col-lg-6 offset-lg-0 col-xl-6 offset-xl-0">
                      <button class="btn btn-large" type="button" id="submitpay">Pay</button>
                    </div>
                  </div>
                </div>
               
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center">
                  <img src="<?php echo base_url('assets/frontend/'); ?>images/payment-muchin-img.png" class="payment-muchin-img mt-20">
                </div>
               
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

   <?php $this->load->view('frontend/common/footer'); ?>
   <script>
	   //~ $('#yearmonth').datepicker({		
		//~ autoclose: true,
        //~ minViewMode: 1,
       //~ format: 'mm/yyyy'
	   //~ });
	   var date = new Date();
    var today =new Date(date.getFullYear(), date.getMonth(), date.getDate());
    
   $('#yearmonth').datepicker({
	   uiLibrary: 'bootstrap4',
           format:"mm-yyyy",
          startDate:today
       }); 
	   </script>
