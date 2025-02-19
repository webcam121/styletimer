<style type="text/css">
  .fr-box.fr-basic .fr-element {
    min-height: 350px;
  }
</style>
       <div class="salon-new-top-bg pt-4">
                <div class="pl-20 pr-20 d-flex">
                  <h3 class="font-size-20 color333 fontfamily-medium mb-2"><?php echo $this->lang->line('setup_your_profile'); ?></h3>
                </div>
                  <div class="salon-new-step">

                        <?php if(!empty($setup_no)) $data['setup_no']=$setup_no; $this->load->view('frontend/marchant/common_setup',$data); ?>

                  </div>
              </div>
               <div class="bgwhite relative pt-4 px-3">      
            <p class="color333 font-size-16 fontfamily-semibold"><?php echo $this->lang->line('add-profile-detail'); ?></p>
                <form id="profile_setup" method="post" action="<?php echo  base_url('profile/profile_setup'); ?>">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="form-group form-group-mb-50" id="first_name_validate">
                           <label class="inp v_inp_new">
                             <input type="text" placeholder="&nbsp;" name="first_name" value="<?php echo $userdetail->first_name; ?>" class="form-control height56v">
                             <span class="label"><?php echo $this->lang->line('First_Name'); ?></span>                               
                           </label>                                                
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="form-group form-group-mb-50" id="last_name_validate">
                           <label class="inp v_inp_new">
                             <input type="text" placeholder="&nbsp;" name="last_name" value="<?php echo $userdetail->last_name; ?>" class="form-control height56v">
                             <span class="label"><?php echo $this->lang->line('Last_Name'); ?></span>                               
                           </label>                                                
                        </div>
                      </div>
                       <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group form-group-mb-50" id="">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="Geburtsdatum" name="dob" value="<?php if(!empty($userdetail->dob)) echo date('d-m-Y',strtotime($userdetail->dob)); ?>" class="form-control height56v dobDatepicker" style="background-color:#ffffff" readonly>
                                 <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue" style="">
                               </label>
                             </div>
                          </div>
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group form-group-mb-50" id="business_name_validate">
                           <label class="inp v_inp_new">
                             <input type="text" placeholder="&nbsp;" name="business_name" value="<?php echo $userdetail->business_name; ?>" class="form-control height56v">
                             <span class="label"><?php echo $this->lang->line('Business_Name'); ?></span>                               
                           </label>                                                
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group form-group-mb-50">
                             <div class="btn-group multi_sigle_select inp_select v_inp_new"> 
                                   <span class="label <?php if(!empty($userdetail->business_type)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Business_Type'); ?></span>
                                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle height56v mss_sl_btn"><?php if(!empty($userdetail->business_type)) echo $userdetail->business_type; ?></button>
                                        <ul class="dropdown-menu mss_sl_btn_dm">
                                         <li class="radiobox-image"><input type="radio" id="id_310" 
                                         <?php if($userdetail->business_type == 'Friseur'){ echo 'checked="checked"'; } ?>
                                          name="business_type" class="" value="Friseur"><label for="id_310">Friseur</label></li>
                                          <li class="radiobox-image"><input type="radio" id="id_311" 
                                          <?php if($userdetail->business_type == 'Barbier'){ echo 'checked="checked"'; } ?> 
                                          name="business_type" class="" value="Barbier"><label for="id_311">Barbier</label></li>
                                          <li class="radiobox-image"><input type="radio" id="id_312" 
                                          <?php if($userdetail->business_type == 'Nagelstudio'){ echo 'checked="checked"'; } ?>
                                           name="business_type" class="" value="Nagelstudio"><label for="id_312">Nagelstudio</label></li>
                                           <li class="radiobox-image"><input type="radio" id="id_312" 
                                          <?php if($userdetail->business_type == 'Massage Salon'){ echo 'checked="checked"'; } ?>
                                           name="business_type" class="" value="Massage Salon"><label for="id_312">Massage Salon</label></li>
                                             <li class="radiobox-image"><input type="radio" id="id_312" 
                                          <?php if($userdetail->business_type == 'Kosmetikstudio'){ echo 'checked="checked"'; } ?>
                                           name="business_type" class="" value="Kosmetikstudio"><label for="id_312">Kosmetikstudio</label></li>
                                        </ul>
                                </div>
                                <label id="busType_err" class="error"></label>
                         </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group form-group-mb-50" id="mobile_validate">
                           <label class="inp v_inp_new">
                             <input type="text" placeholder="&nbsp;" name="mobile" value="<?php echo $userdetail->mobile; ?>" class="form-control height56v onlyNumber">
                             <span class="label"><?php echo $this->lang->line('Telephone'); ?></span>                               
                           </label>                                                
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group form-group-mb-50">
                           <label class="inp v_inp_new">
                             <input type="text" placeholder="&nbsp;" disabled value="<?php echo $userdetail->email; ?>" class="form-control height56v">
                             <span class="label"><?php echo $this->lang->line('Email'); ?></span>                               
                           </label>                                                
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group form-group-mb-50" id="address_validate">
                           <label class="inp v_inp_new">
                             <input type="text" placeholder="&nbsp;" id="address" name="address" value="<?php echo $userdetail->address; ?>" class="form-control height56v">
                             <span class="label"><?php echo $this->lang->line('Street_No'); ?></span>
                             <input type="hidden" name="latitude" value="<?php  echo $userdetail->latitude; ?>" id="latitude">
                              <input type="hidden" name="longitude" value="<?php  echo $userdetail->longitude; ?>" id="longitude">                               
                           </label>                                                
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                        <div class="form-group form-group-mb-50">
                             <div class="btn-group multi_sigle_select inp_select v_inp_new"> 
                                  <span class="label <?php if(!empty($userdetail->country)) echo "label_add_top"; ?>"><?php echo $this->lang->line('Country'); ?> </span>
                                      <button data-toggle="dropdown" class="btn btn-default dropdown-toggle height56v mss_sl_btn" id="countyr_btn"></button>
                                      <ul class="dropdown-menu mss_sl_btn_dm">
                                      <li class="radiobox-image"><input type="radio" id="id_1" name="country" class="country" value="Germany" <?php if($userdetail->country==1 || $userdetail->country=='Germany') { echo 'checked="checked"'; } ?> data-text="<?php echo $this->lang->line('germany'); ?>"><label for="id_1"><?php echo $this->lang->line('germany'); ?></label></li>
                                      <li class="radiobox-image"><input type="radio" id="id_2" name="country" class="country" value="Austria" <?php if($userdetail->country==2 || $userdetail->country=='Austria') { echo 'checked="checked"'; } ?> data-text="<?php echo $this->lang->line('astria'); ?>"><label for="id_2"><?php echo $this->lang->line('astria'); ?></label></li>
                                      <li class="radiobox-image"><input type="radio" id="id_3" name="country" class="country" value="Switzerland" <?php if($userdetail->country==3 || $userdetail->country=='Switzerland') { echo 'checked="checked"'; } ?> data-text="<?php echo $this->lang->line('switzerland'); ?>"><label for="id_3"><?php echo $this->lang->line('switzerland'); ?> </label></li>
                                      </ul>
                              </div>
                              <label id="country_err" class="error"></label>
                         </div>
                      </div>
                       <div class="col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                            <div class="form-group form-group-mb-50" id="zip_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="&nbsp;" name="zip" id="zipcode" class="form-control height56v onlyNumber" value="<?php echo $userdetail->zip;  ?>" maxlength="5">
                                 <span class="label"><?php echo $this->lang->line('Postcode'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <div class="form-group form-group-mb-50" id="city_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="&nbsp;" value="<?php if(!empty($userdetail->city)) echo $userdetail->city; ?>" class="form-control city height56v" id="city" name="city">
                                 <span class="label"><?php echo $this->lang->line('City'); ?></span>                               
                               </label>                                                
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <div class="form-group form-group-mb-50" id="tax_number_validate">
                               <label class="inp v_inp_new">
                                 <input type="text" placeholder="&nbsp;" value="<?php if(!empty($userdetail->tax_number)) echo $userdetail->tax_number; ?>" class="form-control city height56v" id="tax_number" name="tax_number">
                                 <span class="label"><?php echo $this->lang->line('Tax_number'); ?></span>                               
                              </label>                                                
                           </div>
                       </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group form-group-mb-50" id="about_validate">
                             <p class="font-size-18 color333 fontfamily-medium"><?php echo $this->lang->line('Salon_Description'); ?></p>
                             <textarea placeholder="&nbsp;" name="about" id="about_salon"  class="form-control h-100"><?php echo $userdetail->about_salon;  ?></textarea>
<!--
                             <span class="label">About Salon</span>                               
-->
                            <div id="about_err" class="error"></div>
                                                                         
                        </div>
                       
                      </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <!-- online cancellatios add -->
                      <div class="relative">
                         <p class="font-size-18 color333 fontfamily-medium mt-3 mb-0"><?php echo $this->lang->line('Online_Cancellations'); ?></p>
                         <div class="row">
                           <div class="col-12 col-sm-12">
                            <div class="vertical-bottom mb-20">
                            <p class="color333 font-size-14 fontfamily-medium mb-10 display-ib" style="display:initial;"><?php echo $this->lang->line('Allow_Customer'); ?></p>
                                <label class="switch mr-2" for="vcheckbox15" style="top:8px">
                                  <input type="checkbox" id="vcheckbox15" name="cancel_booking_allow" value="yes" <?php if(!empty($userdetail->cancel_booking_allow) && $userdetail->cancel_booking_allow=='yes') echo 'checked'; ?>>
                                  <div class="slider round"></div>
                              </label>
                              
                              <!-- <p class="color999 fontfamily-light font-size-12 mb-10 mt-3 display-b">When this is turned on we will send your customers a cancel link together with the confirmation email.</p> -->
                            </div>
                          </div>
                          <div class="col-12 col-sm-7 col-md-7 col-lg-6 col-xl-5">
                             <h6 class="color333 font-size-14 fontfamily-medium mb-0 mt-2"><?php echo $this->lang->line('Amount_of_hours'); ?></h6>
                          </div>

                          <div class="col-12 col-sm-5 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group form-group-mb-50 mt-2">
                              <div class="btn-group multi_sigle_select inp_select v_inp_new"> 
                              <!--  <span class="label label_add_top"> Hours</span> -->
                                  <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle height44v mss_sl_btn <?php if($userdetail->cancel_booking_allow=='no') echo 'disabled'; ?> cancelOnineHrOpt"><?php if(!empty($userdetail->hr_before_cancel)) echo $userdetail->hr_before_cancel." Stunden";  $optionAray=array(1,2,3,4,5,12,24,48);  ?></button>
                                    <ul class="dropdown-menu mss_sl_btn_dm">
                                      <?php foreach($optionAray as $a){  ?>
                                        <li class="radiobox-image">
                                          <input type="radio" id="id_hrc<?php echo $a; ?>" name="hr_before_cancel" class="" value="<?php echo $a; ?>" <?php if(!empty($userdetail->hr_before_cancel) && $userdetail->hr_before_cancel==$a) echo 'checked'; ?>>
                                          <label for="id_hrc<?php echo $a; ?>"><?php echo $a; ?> Stunde<?php echo $a == 1 ? '' : 'n';?></label>
                                        </li>
                                      <?php } ?>
                                      <!-- checked="checked" -->
                                    </ul>
                                  </div>                                     
                              </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <h5 class="color333 fontfamily-medium font-size-18">Links</h5>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                              <div class="form-group input-group"  id="web_link_validate">
                                <label class="font-size-14 color333 fontfamily-medium mb-1">Website</label>
                                <div class="input-group-prepend">
                                  <span class="input-group-text" >
                                    <img src="<?php echo base_url('assets/frontend/'); ?>images/internet-link-icon.svg" class="link-icon-20" alt="">
                                  </span>
                                </div>
                                <input type="text" name="web_link" value="<?php echo $userdetail->web_link; ?>" class="form-control" placeholder="deineseite.de">
                              </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 pl-0">
                              <div class="form-group input-group"  id="fb_link_validate">
                                <label class="font-size-14 color333 fontfamily-medium mb-1">Facebook-Seite</label>
                                <div class="input-group-prepend">
                                  <span class="input-group-text" >
                                    <img src="<?php echo base_url('assets/frontend/'); ?>images/facebook-link-icon.svg" class="link-icon-20" alt="">
                                  </span>
                                </div>
                                <input type="text" name="fb_link" class="form-control" value="<?php echo $userdetail->fb_link; ?>" placeholder="facebook.de/meinFB">
                              </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 pl-0">
                              <div class="form-group input-group mb-4"  id="insta_link_validate">
                                <label class="font-size-14 color333 fontfamily-medium mb-1">Instagram-Seite</label>
                                <div class="input-group-prepend">
                                  <span class="input-group-text" >
                                    <img src="<?php echo base_url('assets/frontend/'); ?>images/instagram-link-icon.svg" class="link-icon-20" alt="">
                                  </span>
                                </div>
                                <input type="text" name="insta_link" class="form-control" value="<?php echo $userdetail->insta_link; ?>" placeholder="instagram.de/meinIG">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>  

                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
                        <div class="text-right pt-3 pb-3 bgwhite boxshadow-5 px-3">
                          <button id="save_profile_setup" type="button" class="btn btn-large widthfit2"><?php echo $this->lang->line('SaveProceed'); ?></button>
                        </div>
                      </div>
                    </div>
                </form>
 </div>
<script>
  
	
 $("#profile_setup").validate({
	 errorElement: 'label',
     errorClass: 'error',
  	 rules: {
   	 first_name: { required: true },
     last_name: { required: true },
     //business_name:{ required: true },
     business_name:{ 
			required: true,
			remote: {url: base_url+"auth/checksalon_name", type : "post"} 
		},
     mobile:{ required: true },
     address:{ required: true },
     zip:{ required: true,
     maxlength: 5 },
     city:{ required: true }
     
     /*about:{ required: true },*/
  },
  messages: {
    first_name:{ required:'<i class="fas fa-exclamation-circle mrm-5"></i>'+please_enter_first_name },
    last_name:{ required:'<i class="fas fa-exclamation-circle mrm-5"></i>'+please_enter_last_name },	 
    business_name:{ required:"<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line('business_name_is_required');?>" },
    // business_name:{
    //  	required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+please_enter_business_name,
    //  	remote: jQuery.validator.format('<i class="fas fa-exclamation-circle mrm-5"></i> Business name already exists')
    //  },
    mobile:{ required:'<i class="fas fa-exclamation-circle mrm-5"></i>'+phone_number_is_required },
    address:{ required:'<i class="fas fa-exclamation-circle mrm-5"></i>'+address_is_required },
    zip:{ required:'<i class="fas fa-exclamation-circle mrm-5"></i>'+postal_code_is_required,
    maxlength: '<i class="fas fa-exclamation-circle mrm-5"></i> Enter maximum 5 digits postcode' },
    city:{ required:'<i class="fas fa-exclamation-circle mrm-5"></i>'+city_name_is_required }
    
    /*about:{ required:"<i class='fas fa-exclamation-circle mrm-5'></i>About salon is required" },*/
  }, 
  errorPlacement: function (error, element) {
             var name = $(element).attr("name");
             error.appendTo($("#" + name + "_validate"));
         },
        
     submitHandler: function(form) {

    	 var token = true;
    	 if($('[name="business_type"]:checked').length == 0){
			 $("#busType_err").text('Please select business type'); 
			  token =false;
		 }else{ $("#busType_err").text(''); }
		 if($('[name="country"]:checked').length == 0){
			 $("#country_err").text('Please select country'); 
			  token =false;
		 }else{ $("#country_err").text(''); }
		
		 if($('#latitude').val() ==''){
			 $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
			 token =false;
		 }
		 else { $("#addr_err").html(''); }
		// if($('#about_salon').val()==''){
		// 	$("#about_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+About_salon_is_required); 
		// 	 token =false;
		// }else{
		// 	$("#about_err").html('');
		// }

 


		 if(token == false){
			 return false;
		 }
		 else{
			 form.submit();
		 }		
     }

 });

var date = new Date();
      var today =new Date(date.getFullYear(), date.getMonth(), date.getDate());
       var date40year = new Date('<?php  echo date("Y-m-d",strtotime("-90 years",time())); ?>');
       
      /*$(".dobDatepicker").datepicker({ 
             changeMonth: true, 
             changeYear: true, 
             dateFormat: "dd-mm-yy",
             defaultDate:'<?php  echo date("d-m-Y",strtotime("-20 years",time())); ?>',
             maxDate:today,
             yearRange: date40year.getFullYear()+':'+date.getFullYear()
          }).val()*/

      $(".dobDatepicker").datepicker({
          beforeShow: function(input, inst) {
              $(document).off('focusin.bs.modal');
          },
          onClose:function(){
              $(document).on('focusin.bs.modal');
          },
          changeMonth: true, 
             changeYear: true, 
             dateFormat: "dd-mm-yy",
             defaultDate:'<?php  echo date("d-m-Y",strtotime("-20 years",time())); ?>',
             maxDate:today,
             yearRange: date40year.getFullYear()+':'+date.getFullYear()
      }).val()



 /*$(".dobDatepicker").datepicker({ 
      changeMonth: true, 
      changeYear: true, 
      dateFormat: "dd-mm-yy" ,
      maxDate:today
  }).val()*/

</script>
