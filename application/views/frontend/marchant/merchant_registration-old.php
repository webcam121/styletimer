<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php $this->load->view('frontend/common/head'); $keyGoogle =GOOGLEADDRESSAPIKEY; ?>
<style>
  .error_label{
    position: relative !important;
    }
    .error_label#busType_err{
      position: absolute!important;
      margin-top: 0px !important;
      display: block;
      width: auto;
    }
  </style>

<section class="login_register_sections clear">
    <div class="pl-15 pr-15">
        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 align-self-center login_register_main_left_block">
              <div class="row">
                   <div class="login_register_main_left_block_overlay around-50">
                        <div class="relative ">
                              <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/frontend/images/footer_logo_icon.png'); ?>" class="lrmlbo_logo_icon" /></a>
                        </div>
                        
                   </div>
              </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-7 col-sm-12 col-12 m-auto d-flex height-vh justify-content-center">
              <div class="relative align-self-center d-flex flex-row login_register_main_right_block w-100" id="lrmrb_scroll">
                <div class="relative align-self-center w-100">
                    <h2 class="font-size-36 color333 fontfamily-medium mb-60 text-center">Register as Merchant</h2>
                    <?php  
                    echo $this->session->flashdata('err_message'); ?>
                    <div class="relative login_register_form_block">
                         <form id="merchantRegist" method="post" action="<?php echo base_url('auth/registration/marchant') ?>">
                             <div class="row">
                                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="first_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="first_name" name="first_name">
                                               <span class="label">First Name *</span>
                                               <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="last_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="last_name" name="last_name">
                                               <span class="label">Last Name *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                    <div class="form-group form-group-mb-50" id="dob_validate">
                                         <label class="inp">
                                           <input type="text" placeholder="DOB *" class="form-control dobDatepicker" name="dob" style="background-color:#ffffff" readonly>
                                           <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue" style="top:8px;right:9px;">
                                           <!-- <span class="label"></span> -->
                                         </label>                                                
                                     </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="business_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control" id="business_name" name="business_name">
                                               <span class="label">Business Name *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50">
                                             <div class="btn-group multi_sigle_select inp_select"> 
                                                  <span class="label">Business Type *</span>
                                                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"></button>
                                                      <ul class="dropdown-menu mss_sl_btn_dm">
                                                        <li class="radiobox-image"><input type="radio" id="id_310" name="business_type" class="mer_regfrm" value="Hair"><label for="id_310">Hair</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="id_311" name="business_type" class="mer_regfrm" value="Salon"><label for="id_311">Salon</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="id_312" name="business_type" class="mer_regfrm" value="Day Spa"><label for="id_312">Day Spa</label></li>
                                                      </ul>
                                              </div>
                                                <label class="error_label" id="busType_err"></label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="telephone_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="telephone" name="telephone">
                                               <span class="label">Telephone *</span>
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
                                               <span class="label">Street *</span>
                                             </label>
                                             <input type="hidden" name="latitude" value="" id="latitude">

                                              <input type="hidden" name="longitude" value="" id="longitude"> 
                                              <span class="error" for="location" generated="true" id="addr_err"></span>                                      

                                       </div>
                                  </div>
                             </div>

                             <div class="row">
                                  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50">

                                             <div class="btn-group multi_sigle_select inp_select" id="countryDiv">
                                                  <span class="label">Country *</span> 
                                                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"></button>
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
                                               <span class="label">Post Code *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
									  <div class="form-group form-group-mb-50" id="city_validate">
                                              <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control city" id="city" name="city">
                                               <span class="label">City *</span>
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
                                                  <span class="label <?php if(!empty($_GET['r'])) echo 'label_add_top'; ?>" style="width: max-content;">How did you find out about Styletimer?*</span>
                                                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"><?php if(!empty($_GET['r'])) echo 'Referral'; ?></button>
                                                      <ul class="dropdown-menu mss_sl_btn_dm">
                                                          <li class="radiobox-image"><input type="radio" id="idopthow1" name="hot_toknow" class="selecthowtooption" value="Recommended by a customer"><label for="idopthow1">Recommended by a customer</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow2" name="hot_toknow" class="selecthowtooption" value="Recommended by another salon"><label for="idopthow2">Recommended by another salon</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow3" name="hot_toknow" class="selecthowtooption" value="Magazine/ print advertising"><label for="idopthow3">Magazine/ print advertising</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow4" name="hot_toknow" class="selecthowtooption" value="Facebook/ Instagram"><label for="idopthow4">Facebook/ Instagram</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow5" name="hot_toknow" class="selecthowtooption" value="LinkedIn"><label for="idopthow5">LinkedIn</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow6" name="hot_toknow" class="selecthowtooption" value="Google"><label for="idopthow6">Google</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow7" name="hot_toknow" class="selecthowtooption" value="Software comparison site"><label for="idopthow7">Software comparison site</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow8" name="hot_toknow" class="selecthowtooption" value="Outdoor advertising"><label for="idopthow8">Outdoor advertising</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow9" name="hot_toknow" class="selecthowtooption" value="TV advertising"><label for="idopthow9">TV advertising</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow10" name="hot_toknow" class="selecthowtooption" value="Events"><label for="idopthow10">Events</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow12" name="hot_toknow" class="selecthowtooption" value="Referral" <?php if(!empty($_GET['r'])) echo 'checked' ?>><label for="idopthow12">Referral</label></li>
                                                          <li class="radiobox-image">
															  <input type="radio" id="idopthow11" name="hot_toknow" class="selecthowtooption" value="Other"><label for="idopthow11">Other</label></li>
                                                      </ul>
                                                      
                                              </div>
                                                <label class="error_label" id="hot_toknow_err"></label>                                                
                                       </div>
                                  </div>
                                   <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 <?php if(empty($_GET['r'])) echo 'display-n'; ?>" id="referral_code">
                                       <div class="form-group form-group-mb-50" id="referral_code_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" value="<?php if(!empty($_GET['r'])) echo $_GET['r']; ?>" class="form-control" name="referral_code" id="referral_code_val">
                                               <span class="label">Referral code</span>
                                             </label>                                                
                                       </div>
                                  </div>
								 
							  </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center mb-10">
                                            <div class="checkbox mt-0 mb-0" id="termsDiv">
                                                <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="remember" value="" id="terms" name="terms">
                                                  <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                  I agree to the <a class="colororange a_hover_orange popup_terms" data-type="terms" data-access="merchant" href="javascript:void(0)"> terms and conditions </a> & <a href="javascript:void(0)" class="colororange a_hover_orange popup_terms" data-type="policy" data-access="merchant">privacy policy</a> of styletimer.
                                                </label>

                                                <label class="error_label" id="terms_err"></label>
                                            </div>  
                                                                                         
                                       </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center mb-1">
                                            <button type="submit" id="frmsubmit" name="frmsubmit" class="btn width250">Continue</button>                                            
                                       </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                       <div class="form-group text-center mbm-5">
                                            <span class="color333 fontsize-14 fontfamily-regular">If you already have an account ? </span>
                                            <a href="#" class="fontfamily-regular colororange a_hover_orange fontsize-14 mt-0 display-ib openLoginPopup">Sign in!</a>

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
     if($('[name="country"]:checked').length == 0){
      $("#country_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+country_required); 
       token =false;
    }else{ $("#country_err").html(''); }
    
   if($('[name="business_type"]:checked').length == 0){
      $("#busType_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+please_enter_business_name); 
       token =false;
    }else{ $("#busType_err").html(''); }
    if($('[name="hot_toknow"]:checked').length == 0){
      //$("#hot_toknow_err").('display','block'); 
      $("#hot_toknow_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+Please_select_about_styletimer); 
       token =false;
    }else{ $("#hot_toknow_err").html(''); }

  });
 
  $(document).on('change','.mer_regfrm,.country,.selecthowtooption',function(){
    if($('[name="country"]:checked').length > 0)
      $("#country_err").html('');
    if($('[name="business_type"]:checked').length > 0)
      $("#busType_err").html('');
     if($('[name="hot_toknow"]:checked').length > 0)
      $("#hot_toknow_err").html('');

  });

  $(document).on('blur','#post_code',function(){    //#location,,#city
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
       
        var location=$("#location").val(); 
            
    // var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&countrycodes="+c_code+"&postalcode="+address+"&street="+location+"&addressdetails=1&format=json";
    var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&q="+address+"&addressdetails=1&format=json&countrycodes=de";
   
     $.get(gurl,function(data){
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
        $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
        $("#latitude").val('');
        return false;
        }  
      }).fail(function() {
       $("#location_validate label.error").css('display','none');
        $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
      
        $("#latitude").val('');
        return false;
      });
     }




   function getLatlong_old(){
	   
	    var country= $("input[name='country']:checked").val();
        //var city= $("#city").val();
        //var street=$("#location").val();
        var zipcode=$("#post_code").val();
        if(zipcode =="")
         { return false;  } 

         var address="";
       if(zipcode!=undefined && zipcode!=""){
        address=zipcode;
        }
       /* if(street!=undefined && street!=""){
        address=address+" "+street;
        }*/
          //~ if(city!=undefined && city!=""){
        //~ address=address+" "+city;
        //~ }
      if(country!=undefined && country!=""){
        address=address+" "+country;
        }
         var gurl="https://maps.googleapis.com/maps/api/geocode/json?address="+address+"&key=<?php echo $keyGoogle; ?>";              
     $.get(gurl,function(data){
       if(data.status==="OK"){
		 $("#city").val(data.results[0].address_components[1].long_name);
         $("#latitude").val(data.results[0].geometry.location.lat);
         $("#longitude").val(data.results[0].geometry.location.lng);
         //console.log();
          $('#addr_err').html('');
         return true;
           //alert(data.status);
         }
      else{
		  //alert('d');
		   $("#addr_err").css('display','block');
        $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
        $("#latitude").val('');
        return false;
        }  
       
      });
	   }  
</script>
