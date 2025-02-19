<?php $this->load->view('frontend/common/header'); ?>
<!-- dashboard left side end -->
<style>

.gj-icon.chevron-left{
  display: inline-block;
  height: 12px;
  width: 12px;
  border: 2px solid #000;
    border-top-color: rgb(0, 0, 0);
    border-top-style: solid;
    border-top-width: 2px;
    border-right-color: rgb(0, 0, 0);
    border-right-style: solid;
    border-right-width: 2px;
  border-right: 2px solid transparent;
  border-top: 2px solid transparent;
  transform: rotate(45deg);
  
}
.gj-icon.chevron-right{
  display: inline-block;
  height: 12px;
  width: 12px;
  border: 2px solid #000;
    border-top-color: rgb(0, 0, 0);
    border-top-style: solid;
    border-top-width: 2px;
    border-right-color: rgb(0, 0, 0);
    border-right-style: solid;
    border-right-width: 2px;
  border-right: 2px solid transparent;
  border-top: 2px solid transparent;
  transform: rotate(-135deg);
  
}
.gj-picker div:first-child,
.gj-picker div:last-child{
  text-align:center;
}
.gj-picker-md div[role="navigator"] div:first-child, .gj-picker-md div[role="navigator"] div:last-child{
  max-width:30px!important;
}

  </style>
 <div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>  
<div class="right-side-dashbord w-100 pl-30 pr-30">
    <section class="clear membership_payment_section2 v1 pt-50 pb-60 bgwight">
      <div class="container">
        <div class="text-center mb-20">
            
          <p class="font-size-24 color333 fontfamily-medium">Change card details</p>
          
        </div>
        <div class="row">
            
          <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 offset-xl-2 offset-lg-1">
              
            <div class="relative border-w border-radius4 pt-50 pl-20 pr-20 pb-50 mb-4" style="background: #fff;">
                <?php $this->load->view('frontend/common/alert'); ?>
                <form id="changecardForm">
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
                    <div class="col-12 col-sm-6 col-md-12 col-lg-6 col-xl-6 pr-1">
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
                    <div class="col-12 col-sm-6 col-md-12 col-lg-6 col-xl-6">
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
                      <button class="btn btn-large" type="button" id="changecard">Submit</button>
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
   </div>
</div>

  <?php $this->load->view('frontend/common/footer_script');  ?>   
  <script>
       $('#yearmonth').datepicker({     
        autoclose: true,
        minViewMode: 1,
       format: 'mm/yyyy'
       });
  </script>
