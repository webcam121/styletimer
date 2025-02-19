<?php $this->load->view('frontend/common/header'); ?>
<!-- start mid content section-->
<style type="text/css">
  /* .alert_message{
    top:55px!important;
  }
 .d-flex{
   top:10px
 } */

</style>
    <section class="pt-84 clear user_profile_section1">
      <?php $this->load->view('frontend/common/sidebar');


 ?>
        <!-- dashboard left side end -->
 <div class="right-side-dashbord w-100 pl-15 pr-15">
      <div class="container">
        <div class="relative pt-50">
        <?php $this->load->view('frontend/common/alert'); ?>
            <div class="row">
				   
              <div class="col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5 mx-auto">
				     
                <form id="chgPassword" class="relative" method="post" action="<?php echo base_url('auth/change_password'); ?>">
                
            <div class="bgwhite box-shadow1 border-radius4 around-30">
             

              <div class="relative">
                <h6 class="color333 fontfamily-medium font-size-16 mb-30 text-center"><?php echo $this->lang->line('change_password'); ?></h6>
                <div class="form-group form-group-mb-50" id="old_validate">
                  <label class="inp">
                    <input id="old" name="old" type="password" placeholder="&nbsp;" class="form-control">
                    <span class="label"><?php echo $this->lang->line('Old_Password'); ?> *</span>
                  </label>
                </div>
                <div class="form-group form-group-mb-50" id="new_validate">
                  <label class="inp">
                    <input type="password" id="new" name="new" placeholder="&nbsp;" class="form-control">
                    <span class="label"><?php echo $this->lang->line('New_Password'); ?> *</span>
                  </label>
                </div> 
                <div class="form-group form-group-mb-50" id="new_confirm_validate">
                  <label class="inp">
                    <input type="password" id="new_confirm" name="new_confirm" placeholder="&nbsp;" class="form-control">
                    <span class="label"><?php echo $this->lang->line('Confirm_Password'); ?> *</span>
                  </label>
                </div> 
                
              </div>
            </div>
            <div class="mt-50 mb-50 pl-25 pr-25 text-center">
				<a href="<?php if(!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; ?>"><button type="button" class="btn btn-large widthfit2" style="margin-right: 30px;">Abbrechen</button></a>
              <button type="submit" id="subPass" name="subPass" class="btn btn-large widthfit2" id=""><?php echo $this->lang->line('change_password'); ?></button>
            </div>
           </form>
          </div>

            </div>
          
        </div>
      </div>
    </div>
    </section>
 <?php $this->load->view('frontend/common/footer_script');  ?>
