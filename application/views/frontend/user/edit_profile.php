<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php $this->load->view('frontend/common/header');  $keyGoogle =GOOGLEADDRESSAPIKEY; //print_r($userdetail); die; ?>
<!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php //echo $keyGoogle; ?>&&libraries=places"></script>
<script type="text/javascript">
        google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('location'));
            google.maps.event.addListener(places, 'place_changed', function () {
        var place = places.getPlace();
                var address = place.formatted_address;
                //var latitude = place.geometry.location.lat();
                //var longitude = place.geometry.location.lng();
                //document.getElementById('latitude').value=latitude;
                //document.getElementById('longitude').value=longitude;
                document.getElementById('location').value=address;
            });



        });


 </script>
 -->
<style>
	.small_image{
		height:100px;
		width:157px;
		}
   .large_image{
		height:209px;
		width:700px;
		}
	.alert{
		top: -30px !important;
        right: 0;
		}	
   .alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0 {
    top: -28px !important;
 }
	</style>
	<link rel="stylesheet" href="<?php echo base_url("assets/cropp/croppie.css");?>" type="text/css" />
 <section class="pt-120 clear user_profile_section1">

      <div class="container">
        <div class="relative mt-10">
          <?php $this->load->view('frontend/common/alert'); /* if($this->session->flashdata('success')) { ?>
                <div id="succ_msg" class="alert alert-success absolute vinay top w-100 mt-15">
                  <button type="button" class="close ml-10" data-dismiss="alert">&times;</button> <?php echo $this->session->flashdata('success'); ?>
                 </div><?php }
                  if($this->session->flashdata('error')) { ?>
                 <div id="err_msg" class="alert alert-danger absolute top w-100 mt-15 vinay">
                  <button type="button" class="close ml-10" data-dismiss="alert">&times;</button> <?php echo $this->session->flashdata('error'); ?>
                </div>   
                <?php } */ ?>
          <div class="row">
           
            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
            <form method="post" id="profileChange" enctype="multipart/form-data" action="">
              <div class="bgwhite box-shadow1 border-radius4 pt-40 pb-25 text-center mb-30">
                <?php if($userdetail->profile_pic ==""){ ?>
                <div class="relative display-ib">
                  <div id="user-avtar"><img style="width: 115px; height: 115px; border-radius:50%;" id="imgPrevie" src="<?php echo base_url('assets/frontend/images/upload_dummy_img.svg'); ?>" class="round-employee-img-upload"></div>
                  <label class="all_type_upload_file">
                    <img src="<?php echo base_url('assets/frontend/images/camera_upload_icon.svg'); ?>" class="edit_pencil_bg_white_circle_icon1">
                    <input id="profile_pic" name="profile_pic" type="file" onchange="changeImageUpload()">
                </label>
                </div> <?php }
                else { ?>
                <div class="relative display-ib round-employee-upload">
                  
                  
                   <picture>
						 <!-- <source srcset="<?php //echo getimge_url('assets/uploads/users/'.$userdetail->id.'/','thumb_'.$userdetail->profile_pic,'webp'); ?>" type="image/webp" class="round-employee-img-upload">
						 
                         <source srcset="<?php //echo getimge_url('assets/uploads/users/'.$userdetail->id.'/','thumb_'.$userdetail->profile_pic,'webp'); ?>" type="image/webp" class="round-employee-img-upload"> -->
                         
                         <img style="width: 115px; height: 115px;" id="imgPrevie" src="<?php echo getimge_url('assets/uploads/users/'.$userdetail->id.'/','thumb_'.$userdetail->profile_pic,'png'); ?>" type="image/png" class="round-employee-img-upload">
                    </picture>
                  
                  <label class="all_type_upload_file bgblack18">
                    <span class="colorwhite fontfamily-medium font-size-12"><?php echo $this->lang->line('Change'); ?></span>
                    <input id="profile_pic" name="profile_pic" type="file" onchange="changeImageUpload()">
                </label>
                
                </div>
                
                <?php } ?>
                <label id="img_error" class="error_label" style="top: 170px;left: 0px;right: 0px;"></label>
                <input type="hidden" id="old_image" name="old_image" value="<?php echo $userdetail->profile_pic; ?>">
                
                <p class="font-size-18 color333 fontfamily-medium mt-25 mb-0"><?php echo $userdetail->first_name.' '.$userdetail->last_name; ?></p>
                
                <?php if($userdetail->profile_pic !=""){ ?>
				<a class="font-size-10 color666 fontfamily-medium display-ib relative" onclick="return deleteImgaeConfirm();" style="top:-42px; cursor:pointer;"><?php echo $this->lang->line('Remove_Picture'); ?></a>
                  <?php } ?>
              </div>
          	</form>


                 <div class="relative box-shadow1 bgwhite vertical-bottom d-flex" style="padding: 10px 10px 0px 10px;">
                  <p class="color999 fontfamily-light font-size-12 mb-10 display-ib" style="width:74%;"> <?php echo $this->lang->line('Allow-deny'); ?></p>
                   <?php $chk=''; 
                  if(isset($userdetail->service_email) && ($userdetail->service_email == 1)){ $chk='checked="checked"'; } ?>
                     <label class="switch ml-2" for="vcheckbox8us" style="top:8px">
                      <input type="checkbox" class="changeNewsletterNotification" id="vcheckbox8us" data-id="<?php echo url_encode($userdetail->id); ?>" name="chk_online" <?php echo $chk; ?>>
                      <div class="slider round"></div>
                   </label>
                 </div>
                 <div class="relative box-shadow1 bgwhite vertical-bottom d-flex" style="padding: 10px 10px 0px 10px;margin-top: 10;">
                  <p class="color999 fontfamily-light font-size-12 mb-10 display-ib" style="width:74%;"><?php echo $this->lang->line('Receive-newsletter-from-styletimer'); ?></p>
                   <?php $chk1=''; 
                  if(isset($userdetail->newsletter) && ($userdetail->newsletter== 1)){ $chk1='checked="checked"'; } ?>
                     <label class="switch ml-2" for="vcheckbox8st" style="top:8px">
                      <input type="checkbox" class="changeNewsletter" id="vcheckbox8st" data-id="<?php echo url_encode($userdetail->id); ?>" name="chk_onlinest" <?php echo $chk1; ?>>
                      <div class="slider round"></div>
                   </label>
                 </div>

                  <div class="text-center mt-3">
                    <a href="JavaScript:Void(0);" id="deactivate_profile" class="font-size-18 color333 fontfamily-medium a_hover_333" style="font-size: 0.75rem;">Account löschen</a>
                  </div>  


            </div> 

            <div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-9">  
              <!-- id="user_update_profile" -->
             <form method="post"  action="<?php echo base_url('profile/edit_user_profile'); ?>">
              <div class="bgwhite box-shadow1 relative border-radius4 around-30 pr-90 pt-50">
               <!--  <form  class="w-100"> -->
                  <div class="row">   

                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                      <div class="form-group form-group-mb-50" id="first_name_validate">
                        <label class="inp">
                           <input type="text" placeholder="&nbsp;" class="form-control" value="<?php echo $userdetail->first_name; ?>" id="first_name" name="first_name">
                           <span class="label"><?php echo $this->lang->line('First_Name'); ?>*</span>
                          <!--  <label class="error_label">
                            <i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name
                            </label> -->
                         </label>  
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                      <div class="form-group form-group-mb-50" id="last_name_validate">
                        <label class="inp">
                           <input type="text" placeholder="&nbsp;" class="form-control" value="<?php echo $userdetail->last_name; ?>" id="last_name" name="last_name">
                           <span class="label"><?php echo $this->lang->line('Last_Name'); ?>*</span>
                           <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                         </label>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                      <div class="form-group form-group-mb-50" id="first_name_validate">
                           <label class="inp">
                             <input type="text" placeholder="DOB" class="form-control" id="StartDate" name="dob" value="<?php if(!empty($userdetail->dob) && $userdetail->dob!='0000.00.00') echo date('d.m.Y',strtotime($userdetail->dob)); ?>" style="background-color:#ffffff" readonly>
                             <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue" style="top:8px;right:9px;">
                             <span class="label" style="transform: translateY(-30px) translateX(-15px) scale(0.75);"><?php echo $this->lang->line('Birthday'); ?></span>
                           </label>                                                
                       </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <div class="form-group form-group-mb-50" id="email_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" class="form-control" value="<?php echo $userdetail->email; ?>" readonly="readonly">
                          <span class="label"><?php echo $this->lang->line('Email'); ?> *</span>
                          <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                        </label>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <div class="form-group form-group-mb-50" id="telephone_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" value="<?php echo $userdetail->mobile; ?>" id="telephone" name="telephone">
                          <span class="label"><?php echo $this->lang->line('Telephone'); ?> </span>
                          <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                        </label>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">                      
                      <div class="form-group form-group-mb-50" id="location_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" class="form-control" id="location" name="location" value="<?php echo $userdetail->address; ?>">
                          <span class="label"><?php echo $this->lang->line('Street'); ?> </span>
                          <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                        </label>

                        <input type="hidden" name="latitude" value="<?php  echo $userdetail->latitude; ?>" id="latitude">
                        <input type="hidden" name="longitude" value="<?php  echo $userdetail->longitude; ?>" id="longitude">
                         <span class="error_label" id="addr_err"></span>

                      </div>
                    </div>
                     <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                      <div class="form-group  form-group-mb-50">
                        <!-- <label class="inp-label"></label> -->
                        <div class="btn-group multi_sigle_select inp_select"> 
                            <span class="label <?php if(!empty($userdetail->country)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Country'); ?> </span>
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" id="countyr_btn">
                              <?php echo $userdetail->country ? $this->lang->line(strtolower($userdetail->country)) : '';?>
                            </button>
                            <ul class="dropdown-menu mss_sl_btn_dm">
                           
                              <li class="radiobox-image"><input type="radio" id="id_1" name="country" class="country" value="Germany" <?php if($userdetail->country==1 || $userdetail->country=='Germany') { echo 'checked="checked"'; } ?> data-text="Germany"><label for="id_1">Deutschland</label></li>
                              <li class="radiobox-image"><input type="radio" id="id_2" name="country" class="country" value="Austria" <?php if($userdetail->country==2 || $userdetail->country=='Austria') { echo 'checked="checked"'; } ?> data-text="Austria"><label for="id_2">Österreich</label></li>
                              <li class="radiobox-image"><input type="radio" id="id_3" name="country" class="country" value="Switzerland" <?php if($userdetail->country==3 || $userdetail->country=='Switzerland') { echo 'checked="checked"'; } ?> data-text="Switzerland"><label for="id_3">Schweiz</label></li>
                            </ul>
                        </div>
                      </div>
                    </div>
                     <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                      <div class="form-group form-group-mb-50" id="post_code_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="post_code" name="post_code" value="<?php echo $userdetail->zip; ?>" maxlength="5">
                          <span class="label"><?php echo $this->lang->line('Postcode1'); ?> </span>
                        </label>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                      <div class="form-group form-group-mb-50" id="city_validate">
                        <!-- <label class="inp-label">City</label> -->
                         <label class="inp">
						   <input type="text" placeholder="&nbsp;" value="<?php if(!empty($userdetail->city)) echo $userdetail->city; ?>" class="form-control city" id="city" name="city">
						   <span class="label"><?php echo $this->lang->line('City'); ?> </span>
						 </label> 
                      </div>
                    </div>
                   

                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                      <div class="form-group form-group-mb-50">
                        <!-- <label class="inp-label">Female</label> -->
                        <div class="btn-group multi_sigle_select inp_select"> 
                             <span class="label <?php if(!empty($userdetail->gender)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Gender'); ?></span>
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn">
                            <?php
                                if ($userdetail->gender == 'male')  echo $this->lang->line('gender_male');
                                else if ($userdetail->gender == 'female')  echo $this->lang->line('gender_female');
                                else if ($userdetail->gender == 'other') echo $this->lang->line('Other');
                            ?>
                            </button>
                            <ul class="dropdown-menu mss_sl_btn_dm">
                              <li class="radiobox-image"><input type="radio" id="id_112" name="gender" class="" value="male" <?php if($userdetail->gender == 'male') echo 'checked="checked"'; ?>><label for="id_112"><?php echo $this->lang->line('gender_male'); ?></label></li>
                              <li class="radiobox-image"><input type="radio" id="id_113" name="gender" class="" value="female" <?php if($userdetail->gender == 'female') echo 'checked="checked"'; ?>><label for="id_113"><?php echo $this->lang->line('gender_female'); ?></label></li>
                              <li class="radiobox-image"><input type="radio" id="id_114" name="gender" class="" value="other" <?php if($userdetail->gender == 'other') echo 'checked="checked"'; ?>><label for="id_114"><?php echo $this->lang->line('Other'); ?></label></li>
                            </ul>
                        </div>
                      </div>
                    </div>               
                  </div>
                <!-- </form> -->                
              </div> 
              <div class="relative text-center mt-50 mb-50">
                <button id="frmUpdate" type="submit" name="frmUpdate" class="btn btn-large widthfit"><?php echo $this->lang->line('Update-Information'); ?></button>
              </div>  
              </form>  
          </div>
          <!-- <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="bgwhite box-shadow1 border-radius4 around-30">
              <p class="fontfamily-regular font-size-14 mb-0 hide-changepassword">
                <a href="#" class="colororange a_hover_orange text-underline a_show-changepassword" id="show-changepassword">CLICK HERE</a> to change your -password *</p>
             
              <div class="relative show-changepassword display-n">
                <h6 class="color333 fontfamily-medium font-size-16 mb-30 text-center">Passwort ändern</h6>
                <div class="form-group form-group-mb-50">
                  <label class="inp">
                    <input type="text" placeholder="&nbsp;" class="form-control">
                    <span class="label">Old Password *</span>
                  </label>
                </div>
                <div class="form-group form-group-mb-50">
                  <label class="inp">
                    <input type="text" placeholder="&nbsp;" class="form-control">
                    <span class="label">New Password *</span>
                  </label>
                </div> 
                <div class="form-group form-group-mb-50">
                  <label class="inp">
                    <input type="text" placeholder="&nbsp;" class="form-control">
                    <span class="label">Confirm Password *</span>
                  </label>
                </div> 
                <button class="btn btn-large again-show-clickhere" id="">Change</button>
              </div>
            </div>
           
          </div> -->
        </div>
      </form>
        </div>
      </div>
    </section>

<?php $this->load->view('frontend/common/footer'); 
      $this->load->view('frontend/common/crop_pic');  ?>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/cropp/croppie.js'); ?>"></script>

<script type="text/javascript">
  
  localStorage.setItem("STATUS", "LOGGED_IN");
  // $('#datepickerProfile').datepicker({
  //          uiLibrary: 'bootstrap4',
  //          locale: 'de-de',
  //          format:"dd.mm.yyyy",
          

  //      });
      
  
  $("#countyr_btn").text($("input[name='country']:checked").attr('data-text'));

 $(document).on('click','#post_code',function(){ //#location,
		
	  $("#latitude").val("");
	  $("#longitude").val("");
		
	});	
	$(document).on('blur','#post_code',function(){    //#location,
	
      		getaddress();
		
		});	
	$(document).on('change','.country',function(){
	    $("#latitude").val("");
	    $("#longitude").val("");
    	getaddress();
		
	});	
	

    function getaddress(){
        var country= $("input[name='country']:checked").attr('data-text');
        var zipcode=$("#post_code").val();
        var c_code='de';
        var location=$("#location").val(); 
        if(location!=""){
        address=location;
        }
        if(zipcode ==""){
          return false;
        }
        var address="";
        if(zipcode!=undefined && zipcode!=""){
          address=address+" "+zipcode;
        }
       
        if(country!=undefined && country!=""){
          //address=address+" "+country;
          if(country == 'Österreich')
            c_code = 'at';
          else if(country == 'Schwei')
            c_code = 'ch';
        }
        if(country == 'Deutschland')
            c_code = 'de';
    
    
      
      
      //var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&countrycodes="+c_code+"&postalcode="+address+"&street="+location+"&addressdetails=1&format=json";
      var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&q="+address+"&addressdetails=1&format=json&countrycodes=de";

      loading();
      $.get(gurl,function(data){
    
        var count = data.length;
        if(data.length > 0){
          
            $.each(data, function () {
                  if(data[0].address.town!=undefined)
                    var citty = data[0].address.town
                else if(data[0].address.city!=undefined)
                  var citty = data[0].address.city
                else if(data[0].address.municipality!=undefined)    
                  var citty = data[0].address.municipality
                else  if(data[1].address.town!=undefined)
                    var citty = data[1].address.town
              else if(data[1].address.city!=undefined)
                  var citty = data[1].address.city
              else if(data[1].address.municipality!=undefined)    
                var citty = data[1].address.municipality
              else  if(data[2].address.town!=undefined)
                    var citty = data[2].address.town
              else if(data[2].address.city!=undefined)
                  var citty = data[2].address.city
              else if(data[2].address.municipality!=undefined)    
                var citty = data[2].municipality
                else
                  var citty = "";
              $("#city").val(citty);
              
              $("#latitude").val(data[0].lat);
              $("#longitude").val(data[0].lon);
              $('#addr_err').html('');
              });
                unloading();
          //   if(data[0].address.town!=undefined)
          //       var citty = data[0].address.town
          //   else if(data[0].address.city!=undefined)
          //      var citty = data[0].address.city
          //      else if(data[1].address.city!=undefined)
          //      var citty = data[1].address.city
          //   else
          //      var citty = "";
                
          // $("#city").val(citty);
          // $("#latitude").val(data[0].lat);
          // $("#longitude").val(data[0].lon);
          //  $('#addr_err').html('');
          //    unloading();
        }
        else{
          $("#location_validate label.error").css('display','none');
          $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
          $("#latitude").val('');
            unloading();
          return false;
        }  
      }).fail(function() {
       $("#location_validate label.error").css('display','none');
        $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
      
        $("#latitude").val('');
          unloading();
        return false;
      });
    }

  function getaddress_old(){
		
		var country= $("input[name='country']:checked").attr('data-text');
        //var city= $("#city").val();
       // var street=$("#location").val();
        var zipcode=$("#post_code").val();
        if(zipcode ==""){
          return false;
        }
        var address="";
       if(zipcode!=undefined && zipcode!=""){
        address=address+" "+zipcode;
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
	        loading();	  	  	  
		 $.get(gurl,function(data){
			 if(data.status==="OK"){
				  if(data.results[0].address_components[1]!=undefined){
				     $("#city").val(data.results[0].address_components[1].long_name);
			      }
				 else $("#city").val('');
				 $("#latitude").val(data.results[0].geometry.location.lat);
				 $("#longitude").val(data.results[0].geometry.location.lng);
				 //console.log();
         $('#addr_err').html('');
          unloading();
				 return true;
			     //alert(data.status);
				 }
			else{
				setTimeout(function() {
					if($("#location").val()!=""){
					$('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
				    }
					}, 1000);
        
        $("#latitude").val('');
         unloading();
				return false;
				}	 
			 
		  });
		}
		
$(document).ready(function(){
  var date = new Date();
  var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

  var date40year = new Date('<?php  echo date("Y-m-d",strtotime("-90 years",time())); ?>');

  console.log(333);
  $("#StartDate").datepicker({
    // uiLibrary: 'bootstrap4',
    // locale: 'de-de',
    // format:"dd.mm.yyyy",
    changeMonth: true,
    changeYear: true,
    // defaultDate: '<?php  echo date("d-m-Y",strtotime("-20 years",time())); ?>',
    maxDate: today,
    yearRange: date40year.getFullYear() + ':' + date.getFullYear(),
    prevText: '&#x3c;zurück', prevStatus: '',
    prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
    nextText: 'Vor&#x3e;', nextStatus: '',
    firstDay: 1,
    nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
    currentText: 'heute', currentStatus: '',
    todayText: 'heute', todayStatus: '',
    clearText: '-', clearStatus: '',
    closeText: 'schließen', closeStatus: '',
    monthNames: ['Januar','Februar','März','April','Mai','Juni',
    'Juli','August','September','Oktober','November','Dezember'],
    monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
    dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
    dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
    dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
    dateFormat: 'dd.mm.yy',
  });

  $image_crop = $('#image_demo').croppie({
		enableExif: false,
		viewport: {
		  width:250,
		  height:250,
		  type:'square' //circle
		},
		boundary:{
		  width:500,
		  height:400
		}
	  });

  $(document).on('change','#profile_pic', function(){
    var reader = new FileReader();
    //console.log(reader);
    reader.onload = function (event) {
		//console.log(event);
      $image_crop.croppie('bind', {
        url:event.target.result
      }).then(function(){
        //console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type:'canvas',
      size:'viewport'
    }).then(function(response){
		loading();
      $.ajax({
        url:"<?php echo base_url('profile/profile_imagecropp'); ?>",
        type: "POST",
        data:{"image": response},
        success:function(data)
        { var obj = $.parseJSON(data);
          $('#uploadimageModal').modal('hide');
          unloading();
          // var $el = $('#profile_pic');
           //$el.wrap('<form>').closest('form').get(0).reset();
          // $el.unwrap();
         // $('#upload_image').attr('data-url','');
          //$('#profile-picture').attr('data-img',obj.status);
          $('#imgPrevie').attr('src',obj.image);
        }
      });
    })
  });


	});			
		 
</script>
