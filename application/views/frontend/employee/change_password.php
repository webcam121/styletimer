<?php $this->load->view('frontend/common/header'); ?>
<!-- start mid content section-->

    <section class="pt-84 clear user_profile_section1">
      <div class="container">
        <div class="relative mt-50">
        <?php $this->load->view('frontend/common/alert'); ?>
            <div class="row">
              <div class="col-12 col-sm-12 col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-xl-4 offset-xl-4">
                
                <form id="chgPassword" method="post" action="<?php echo base_url('auth/change_password'); ?>">
              <div class="bgwhite box-shadow1 border-radius4 around-30">
                <!-- <p class="fontfamily-regular font-size-14 mb-0 hide-changepassword">
                  <a href="#" class="colororange a_hover_orange text-underline a_show-changepassword" id="show-changepassword">CLICK HERE</a> to change your -password *</p> -->
                
                 <?php if($this->session->flashdata('success')){ ?>
                 <div id="succ_msgs" class="alert alert-success absolute vinay top w-100 mt-15">
                  <button type="button" class="close ml-10" data-dismiss="alert">&times;</button> <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php }
                if($this->session->flashdata('error')){ ?>
                <div id="err_msgs" class="alert alert-danger absolute top w-100 mt-15 vinay">
                  <button type="button" class="close ml-10" data-dismiss="alert">&times;</button> <?php echo $this->session->flashdata('error'); ?>
                </div>   
              <?php } ?>
              <div class="relative">
                <h6 class="color333 fontfamily-medium font-size-16 mb-30 text-center">Passwort ändern</h6>
                <div class="form-group form-group-mb-50" id="old_validate">
                  <label class="inp">
                    <input id="old" name="old" type="password" placeholder="&nbsp;" class="form-control">
                    <span class="label">Altes Passwort *</span>
                  </label>
                </div>
                <div class="form-group form-group-mb-50" id="new_validate">
                  <label class="inp">
                    <input type="password" id="new" name="new" placeholder="&nbsp;" class="form-control">
                    <span class="label">Neues Passwort*</span>
                  </label>
                </div> 
                <div class="form-group form-group-mb-50" id="new_confirm_validate">
                  <label class="inp">
                    <input type="password" id="new_confirm" name="new_confirm" placeholder="&nbsp;" class="form-control">
                    <span class="label">Passwort bestätigen *</span>
                  </label>
                </div> 
                
              </div>
            </div>
            <div class="mt-50 mb-50 text-center">
				<a href="<?php if(!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; ?>">
          <button type="button" class="btn btn-large widthfit2 mr-4" style="">Abbrechen</button>
        </a>
              <button type="submit" id="subPass" name="subPass" class="btn btn-large widthfit2" id="">Passwort ändern</button>
            </div>
           </form>
          </div>

            </div>
          
        </div>
      </div>
    </section>
 <?php $this->load->view('frontend/common/footer_script');  ?>
