<?php $this->load->view('frontend/common/head'); ?>
<div class="alert alert-success absolute top w-100 mt-15 alert_sucmessage_thk" style="display:none;">
                  <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>-->  
                  <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg')?>">
                    <span id="alert_sucmessage_thk"></span>
                </div>
<section class="service_provider_registration_done_thankyou">
    <div class="container">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 d-flex height-vh justify-content-center">
        <div class="align-center pt-20 pb-20 align-self-center">
          <div class="thankyou_top_img_block2">
            <img src="<?php echo base_url('assets/frontend/images/image_2021_09_10T09_54_47_284Z.png'); ?>" class="thankyou_top_img2">
          </div>
          <div class="relative mt-25">
            <h1 class="fontfamily-medium font-size-70 mb-0" style="color:#00b3bf;"><?php echo $this->lang->line('Thank-you'); ?>!</h1>
            <img src="<?php echo base_url('assets/frontend/images/thankyou_mid_line_img.png'); ?>" class="thankyou_mid_line_img">
          </div>
            <div class="mt-50">
              <h4 class="color333 fontfamily-medium font-size-30"><?php echo $this->lang->line('Your-registration-successfully'); ?></h4>
              <p class="mt-30 mb-20 color666 font-size-20 fontfamily-regular lineheight30">Wir haben dir einen Link zur Aktivierung deines Accounts <br/> an deine E-Mail Adresse gesendet.</p>
              <?php if(!empty($_SESSION['regstyle_email'])){ ?>
              <a href="javaScript:void(0);" id="resend_activelink_thk" style="" class="colororange a_hover_orange fontsize-14 d-block text-center mb-3 text-underline"><?php echo $this->lang->line('Resend-account-activation-mail'); ?></a>
              <input type="hidden" id="get_email" value="<?php echo $_SESSION['regstyle_email']; ?>" name="">
            <?php } ?>
              <a href="#" class="openLoginPopup"><button type="button" class="btn widthfit"><?php echo $this->lang->line('Go-To-Login'); ?></button></a>
            </div>
        </div>
      </div>
    </div>
  </section>

 <?php $this->load->view('frontend/common/footer_script'); ?>
<script type="text/javascript">
  
$(document).on('click','#resend_activelink_thk',function(){

   var value = $('#get_email').val();
     /*if(value==""){
      $("#identity_validate").append('<label for="identity" generated="true" class="error">Please enter a valid email address.</label>');
      return false;
     }else{*/
       loading();
           $.ajax({
            url: base_url+"auth/resend_activation_mail",
            type: "POST",
            data:{email:value},
            success: function (response) {
              var obj = jQuery.parseJSON( response );
              if(obj.success=='1'){
                unloading();
                $("#alert_sucmessage_thk").html('<strong>Message ! </strong>'+obj.message);
        $(".alert_sucmessage_thk").css('display','block');
        $(".alert_message_thk").css('display','none');
        $("#resend_activelink_thk").hide();
        
        }
              else{
                unloading();
                $("#alert_message_thk").html('<strong>Message ! </strong>'+obj.message);
        $(".alert_message_thk").css('display','block');
        $(".alert_sucmessage_thk").css('display','none');
        
              }
              
             }
              
          });
     /*}*/
     
}); 
</script>