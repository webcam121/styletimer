<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php $this->load->view('frontend/common/head'); $keyGoogle =GOOGLEADDRESSAPIKEY; ?>
<style>
	.error_label{
		position: relative !important;
		}
    .error_label#gender_err{
      position: absolute!important;
      margin-top: 0px !important;
      display: block;
      width: auto;
    }
	</style>
<section class="login_register_sections pt-5 clear">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 align-self-center m-auto mb-5">
              <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mb-5">
                  <div class="text-center">
                    <picture class="w-290 m-auto">
                      <source srcset="<?php echo base_url('assets/frontend/images/dou-mobile-img.webp') ?>" type="image/webp" class="w-290">
                      <source srcset="<?php echo base_url('assets/frontend/images/dou-mobile-img.png') ?>" type="image/png" class="w-290">
                      <img src="<?php echo base_url('assets/frontend/images/dou-mobile-img.png') ?>" class="w-290">
                    </picture>
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mb-5">
                  <h3 class="fontsize-24 color333 fontfamily-bold">Lorem Ipsum</h3>
                  <p class="fontsize-16 color333 fontfamily-regular">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vehicula pharetra risus, vitae tempus diam pretium sed. Morbi venenatis porta eros, nec aliquet tortor hendrerit suscipit. Fusce accumsan massa sit amet enim pulvinar semper. </p>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <h3 class="fontsize-24 color333 fontfamily-bold">Aliquam quis tristique massa</h3>
                  <p class="fontsize-16 color333 fontfamily-regular">Aenean ante est, fermentum in malesuada a, semper non quam. Cras sagittis nisl eu posuere mattis. Aliquam quis tristique massa. Pellentesque facilisis felis et risus congue ultricies. Aenean ut lectus id turpis luctus ultrices id sit amet nunc. Proin quis nibh quis tortor ultricies fermentum nec quis mi. Vestibulum vitae quam est. Nullam erat nulla, mollis a diam vitae, efficitur pellentesque diam. Pellentesque sit amet aliquet felis. </p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 m-auto d-flex justify-content-center">
              <div class="relative align-self-center d-flex flex-row login_register_main_right_block w-100" id="lrmrb_scroll">
                <div class="relative align-self-center w-100">
                    <h2 class="font-size-36 color333 fontfamily-medium mb-5 text-center">Register as User</h2>
                    <?php  
                    echo $this->session->flashdata('err_message'); ?>
                    <div class="relative login_register_form_block">
                         <form id="userRegistration" method="post" action="<?php echo base_url('auth/registration/user') ?>">
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                   <div class="form-group form-group-mb-50">
                                         <div class="btn-group multi_sigle_select inp_select" id="genderDiv"> 
                                              <span class="label">Gender *</span>
                                              <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" id="gender" name="gender"></button>
                                                  <ul class="dropdown-menu mss_sl_btn_dm">
                                                      <li class="radiobox-image"><input type="radio" id="id_112" name="gender" class="user_regfrm" value="male"><label for="id_112">Male</label></li>
                                                      <li class="radiobox-image"><input type="radio" id="id_113" name="gender" class="user_regfrm" value="female"><label for="id_113">Female</label></li>
                                                      <li class="radiobox-image"><input type="radio" id="id_114" name="gender" class="user_regfrm" value="other"><label for="id_114">Other</label></li>
                                                  </ul>
                                          </div>
                                          <label class="error_label" id="gender_err"></label>
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                      <div class="form-group form-group-mb-50" id="dob_validate">
                                         <label class="inp">
                                           <input type="text" placeholder="DOB *" class="form-control dobDatepicker" name="dob" style="background-color:#ffffff" readonly>
                                           <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue" style="top:8px;right:9px;">
                                           <!-- <span class="label"></span> -->
                                         </label>                                                
                                     </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="first_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="first_name" name="first_name">
                                               <span class="label">First Name *</span>
                                               <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="last_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="last_name" name="last_name">
                                               <span class="label">Last Name *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="telephone_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="telephone" name="telephone">
                                               <span class="label">Telephone </span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="email_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="email" name="email">
                                               <span class="label">Email *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>                           
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="location_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="location" name="location">
                                               <span class="label">Street </span>
                                             </label>                                     
                                              <input type="hidden" name="latitude" value="" id="latitude">
                                              <input type="hidden" name="longitude" value="" id="longitude">    
                                              <span class="error_label" id="addr_err"></span>       
                                       </div>
                                        
                                  </div>
                             </div>

                             <div class="row">
                                  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50">
                                             <div class="btn-group multi_sigle_select inp_select" id="countryDiv"> 
                                                  <span class="label">Country </span>
                                                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" id="country"></button>
                                                      <ul class="dropdown-menu mss_sl_btn_dm">
                                                          <li class="radiobox-image"><input type="radio" id="id_1" name="country" class="country" value="Germany"><label for="id_1">Germany</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="id_2" name="country" class="country" value="Austria"><label for="id_2">Austria</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="id_3" name="country" class="country" value="Switzerland"><label for="id_3">Switzerland</label></li>
                                                      </ul>
                                              </div>
                                              <label class="error_label" id="country_err"></label>

                                       </div>
                                  </div>
                                  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="post_code_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="post_code" name="post_code" maxlength="5">
                                               <span class="label">Post Code </span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="city_validate">
                                              <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control city" id="city" name="city">
                                               <span class="label">City </span>
                                             </label>                                             
                                       </div>
                                  </div>
                             </div>  
                              <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                      <div class="form-group form-group-mb-50" id="password_validate">
                                             <label class="inp">
                                               <input type="password" placeholder="&nbsp;" class="form-control" id="password" name="password">
                                               <span class="label">Passwort *</span>
                                             </label>                                                
                                       </div>

                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="confirm_pass_validate">
                                             <label class="inp">
                                               <input type="password" placeholder="&nbsp;" class="form-control" id="confirm_pass" name="confirm_pass">
                                               <span class="label">Passwort bestätigen *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                                 <div class="row">
                                   <div id="reffrelotption" class="<?php if(!empty($_GET['r'])) echo 'col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12';  else echo 'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'; ?>">
                                                       <div class="form-group form-group-mb-50">
                                                             <div class="btn-group multi_sigle_select inp_select"> 
                                                                  <span class="label <?php if(!empty($_GET['r'])) echo 'label_add_top'; ?>" style="width: max-content;">How did you find out about Styletimer?</span>
                                                                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"><?php if(!empty($_GET['r'])) echo 'Referral'; ?></button>
                                                                      <ul class="dropdown-menu mss_sl_btn_dm">
                                                                          <li class="radiobox-image"><input type="radio" id="idopthow1" name="hot_toknow" class="selecthowtooption" value="Heard about in salon"><label for="idopthow1">Heard about in salon</label></li>
                                                                          <li class="radiobox-image"><input type="radio" id="idopthow2" name="hot_toknow" class="selecthowtooption" value="Heard from other customer"><label for="idopthow2">Heard from other customer</label></li>
                                                                          <li class="radiobox-image"><input type="radio" id="idopthow3" name="hot_toknow" class="selecthowtooption" value="Facebook"><label for="idopthow3">Facebook</label></li>
                                                                          <li class="radiobox-image"><input type="radio" id="idopthow4" name="hot_toknow" class="selecthowtooption" value="Instagram"><label for="idopthow4">Instagram</label></li>
                                                                          <li class="radiobox-image"><input type="radio" id="idopthow5" name="hot_toknow" class="selecthowtooption" value="Google"><label for="idopthow5">Google</label></li>
                                                                          <li class="radiobox-image"><input type="radio" id="idopthow7" name="hot_toknow" class="selecthowtooption" value="Magazine/print advertising"><label for="idopthow7">Magazine/print advertising</label></li>
                                                                          <li class="radiobox-image"><input type="radio" id="idopthow8" name="hot_toknow" class="selecthowtooption" value="Outdoor advertising"><label for="idopthow8">Outdoor advertising</label></li>
                                                                          <li class="radiobox-image"><input type="radio" id="idopthow9" name="hot_toknow" class="selecthowtooption" value="TV/cinema"><label for="idopthow9">TV/cinema</label></li>
                                                                          <li class="radiobox-image"><input type="radio" id="idopthow10" name="hot_toknow" class="selecthowtooption" value="Events"><label for="idopthow10">Events</label></li>
                                                                          <li class="radiobox-image">
                                                <input type="radio" id="idopthow11" name="hot_toknow" class="selecthowtooption" value="Other"><label for="idopthow11">Other</label></li>
                                                                      </ul>
                                                                      
                                                              </div>
                                                                <label class="error_label" id="hot_toknow_err"></label>                                                
                                                       </div>
                                                  </div>
                                                   
                                 
                                </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 m-auto">
                                       <div class="form-group text-left">
                                            <div class="checkbox mt-0 mb-2" id="termsDiv">
                                                <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="remember" value="" id="terms" name="terms">
                                                  <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                  I agree to the <a class="colororange a_hover_orange popup_terms" data-type="terms" data-access="user" href="javascript:void(0)"> terms and conditions </a> of styletimer.
                                                </label>
                                            </div>  
                                             <label style="top: 16px; padding-left: 30px;" class="error_label" id="terms_err"></label> 
                                             <div class="checkbox mt-0 mb-2">
                                               <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="service_mail" value="">
                                                  <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                  Tick to allow the venue i'm booking with to send me emails and SMS about their services.
                                                </label>
                                            </div>     
                                           <div class="checkbox mt-0 mb-0">
                                               <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="newsletter" value="">
                                                  <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                  Receive newsletter from styletimer.
                                                </label>
                                            </div> 
                                            

                                       </div>

                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">
                                            <button type="submit" id="frmsubmit" name="frmsubmit" class="btn width250">Get Started</button>                                            
                                       </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center">

                                            <span class="color333 fontsize-14 fontfamily-regular"><?php echo $this->lang->line('you-already-account'); ?> </span>  <a href="#" class="fontfamily-regular colororange a_hover_orange fontsize-14 mtm-10 display-ib openLoginPopup"><?php echo $this->lang->line('sign-in'); ?>!</a>

                                        </div>
                                  </div>
                              </div>
                         </form>
                    </div>
                  </div>  
              </div>
            </div>
        </div>
    </div>
  </section>

<?php $this->load->view('frontend/common/footer_script'); ?>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

 $(document).on('click','#frmsubmit',function(){    //#location,#city
     /*if($('[name="country"]:checked').length == 0){
      $("#country_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>Field is required'); 
       token =false;
    }else{ $("#country_err").html(''); }*/
    
    if($('[name="gender"]:checked').length == 0){
      $("#gender_err").html('<i class="fas fa-exclamation-circle mrm-5"></i><?php echo $this->lang->line('Select-gender'); ?>'); 
       token =false;    

     }else { $("#gender_err").html(''); }
     /*if($('[name="hot_toknow"]:checked').length == 0){
      //$("#hot_toknow_err").('display','block'); 
      $("#hot_toknow_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>Please select how did you find out about styletimer'); 
       token =false;
    }else{ $("#hot_toknow_err").html(''); }*/
  });
 
  $(document).on('change','.user_regfrm,.country,.selecthowtooption',function(){
    if($('[name="country"]:checked').length > 0)
      $("#country_err").html('');
    if($('[name="gender"]:checked').length > 0)
      $("#gender_err").html('');
    if($('[name="hot_toknow"]:checked').length > 0)
      $("#hot_toknow_err").html('');

  });

  $(document).on('blur','#post_code',function(){    //#location,#city
   getLatlong(); 
  });
  $(document).on('change','.country',function(){
   getLatlong(); 
  });
    

    function getLatlong(){
       var country= $("input[name='country']:checked").val();
        var c_code='de';
         var location=$("#location").val();
          if(location!=""){
			address=location;
			}
        
        var zipcode=$("#post_code").val();
        if(zipcode == ''){
          return false;
        }
        var address="";
        if(zipcode!=undefined && zipcode!=""){
          address=address+" "+zipcode;
        }
        if(country!=undefined && country!=""){
          address=address+" "+country;
         if(country == 'Austria')
            c_code = 'at';
         else if(country == 'Switzerland')
            c_code = 'ch';
        }
       
        //~ if(location!="")
         //~ var searchQery=location+", "+zipcode+" "+country;

   // var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&countrycodes="+c_code+"&postalcode="+address+"&street="+location+"&addressdetails=1&format=json";
   var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&q="+address+"&addressdetails=1&format=json&countrycodes=de";
    
     $.get(gurl,function(data){
		 //console.log(data);
      var count = data.length;
      if(data.length > 0){
		  if(data[0].address.town!=undefined)
              var citty = data[0].address.town
          else if(data[0].address.city!=undefined)
             var citty = data[0].address.city
          else if(data[0].address.municipality!=undefined)    
            var citty = data[0].address.municipality
         else
             var citty = "";
        $("#city").val(citty);
        $("#latitude").val(data[0].lat);
        $("#longitude").val(data[0].lon);
         $('#addr_err').html('');
      }
      else{
        $("#location_validate label.error").css('display','none');
        $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Please enter valid address');
        $("#latitude").val('');
        return false;
        }  
      }).fail(function() {
       $("#location_validate label.error").css('display','none');
        $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Please enter valid address');
      
        $("#latitude").val('');
        return false;
      });
     }  

  
</script>
