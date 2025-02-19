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
            
          <p class="font-size-24 color333 fontfamily-medium"><?php echo $this->lang->line("Change_payment_details"); ?></p>
          
        </div>
        <div class="row">
            
          <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 offset-xl-2 offset-lg-1">
              
            <div class="relative border-w border-radius4 pt-50 pl-20 pr-20 pb-50 mb-4" style="background: #fff;">
               <div class="alert alert-success absolute vinay top w-100 mt-15 alert_message alert-top-0 text-center display-n <?php if(!empty($this->session->flashdata('success'))) echo 'messageAlert' ?>">
    <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times; </button> -->
  
				   <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span id="alert_message"><?php if($this->session->flashdata('success')) echo $this->session->flashdata('success'); ?> </span>
				 </div>
				 <div class="alert alert-danger absolute vinay top w-100 mt-15 alert_message alert-top-0 display-n <?php if(!empty($this->session->flashdata('error'))) echo 'messageAlert' ?>">
					<span id="alert_message"><?php if($this->session->flashdata('error')) echo $this->session->flashdata('error'); ?> </span>
				 </div>
                
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="radio mt-0 mb-3">
                      <label class="fontsize-14 pl0 color333">
                         <input type="radio" class="paymentoption" name="payment_method" value="card" checked="checked">
                          <span class="cr"><i class="cr-icon fas fa-circle"></i></span>
                          <?php echo $this->lang->line("credit-debit-card"); ?>
                          </label>
                    </div>
                    <div class="radio mt-0 mb-4">
                        <label class="fontsize-14 pl0 color333">
                          <input type="radio" class="paymentoption" name="payment_method" value="sepa_debit">
                            <span class="cr"><i class="cr-icon fas fa-circle"></i></span>
                            <?php echo $this->lang->line("sepa-devid-payment"); ?>
                        </label>
                    </div>
                  </div>
             
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                 <form id="changecardForm">
                  <input type="hidden" name="payment_method_type" value="card">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <div class="form-group form-group-mb-50 pt-3" id="card_number_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="card_number" class="form-control">
                          <span class="label"><?php echo $this->lang->line("Card_Number"); ?></span>
                        </label>
                      </div>
                      <div class="form-group" id="nameofcarthoder_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="nameofcarthoder" class="form-control">
                          <span class="label"><?php echo $this->lang->line("Name_on_Card"); ?></span>
                        </label>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-12 col-lg-6 col-xl-6 pr-1">
                      <label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line("Expiry_Date"); ?></label>
                      <div class="relative form-group-mb-50" id="expir_month_year_validate">
                           <div class="input-group">
                        <div class="input-group-prepend ">
                          <span class="input-group-text bge8e8e8 pl-1 pr-1">MM/YY</span>
                        </div>
                        <input type="text" class="form-control" maxlength="7" id="yearmonth" name="expir_month_year" placeholder="">
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
                    <div class="col-12">
                      <button class="btn btn-large" type="button" id="changecard"><?php echo $this->lang->line("Pay1"); ?></button>
                    </div>
                  </div>
                  <input type="hidden" name="planid" value="<?php if(!empty($plan_id)) echo $plan_id; ?>"> 

                </form>
                 <form id="changesepapaymentForm" class="display-n">
                  <input type="hidden" name="payment_method_type" value="sepa_debit">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">                   
                      <div class="form-group" id="nameacounthoder_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="nameacounthoder" class="form-control">
                          <span class="label">Name</span>
                        </label>
                      </div>
                       <div class="form-group form-group-mb-50 pt-3" id="iban_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="iban" class="form-control">
                          <span class="label">IBAN</span>
                        </label>
                      </div>
                    </div>
                   
                  
                    <div class="col-12">
                      <button class="btn btn-large" type="button" id="submitsepapaychange"><?php echo $this->lang->line("Pay1"); ?></button>
                    </div>
                  </div>
                    <input type="hidden" name="planid" value="<?php if(!empty($plan_id)) echo $plan_id; ?>"> 
               </form>

                </div>
                  <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center">
                  <img src="<?php echo base_url('assets/frontend/'); ?>images/payment-muchin-img.png" class="payment-muchin-img mt-20">
                </div> 
                                 
               <!--  <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                 
                </div> -->
                 <!--  <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center">
                  <img src="<?php echo base_url('assets/frontend/'); ?>images/payment-muchin-img.png" class="payment-muchin-img mt-20">
                </div>  -->
               
               
                           
             
              
            </div>
          </div>
          </div>
        </div>
      </div>
    </section>
   </div>
</div>

  <?php $this->load->view('frontend/common/footer_script');  ?>   
  <script>
       /*$('#yearmonth').datepicker({     
        autoclose: true,
        minViewMode: 1,
       format: 'mm/yyyy'
       });*/

$(document).on('keyup', '#yearmonth', function(e){
//console.log($(this).val());

var val = $(this).val();
if(!isNaN(val)) {
    if(val > 1 && val < 10 && val.length == 1) {
        temp_val = "0" + val + "/";
        $(this).val(temp_val);
    }
    else if (val >= 1 && val < 10 && val.length == 2 && e.keyCode != 8) {
        temp_val = val + "/";
        $(this).val(temp_val);                
    }
    else if(val > 9 && val.length == 2 && e.keyCode != 8) {
        temp_val = val + "/";
        $(this).val(temp_val);
    }
}
else {
}
});

       $(document).on('change','.paymentoption',function(){
       if($(this).val()=='card'){
        $("#changesepapaymentForm").addClass('display-n');
        $("#changecardForm").removeClass('display-n');
       }else{
          $("#changecardForm").addClass('display-n');
          $("#changesepapaymentForm").removeClass('display-n');
        
       }
    });  
  </script>
