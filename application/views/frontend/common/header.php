 <!-- start header section-->
 <?php $this->load->view('frontend/common/head');
 ?>
  
 <style type="text/css">
 	<?php if( ($this->session->userdata('access')=="") || ($this->session->userdata('access')=='user')){ ?>
 		.header{ height: 104px;  }
 		.search-header-btn, 
 		.notification-header-btn, 
 		.waves-effect.waves-block.waves-light.toggle-fullscreen.display-ib{
 			padding-top: 20px !important;
		 }
		 #optimize{display:none;}
 		.user-icon-profile img.mr-2{
 			width:44px;
 			height:44px;
 			border-radius: 50%;
 			display: inline-block;
			object-fit: cover;
 		}
 	<?php }else{ ?>
 		.header{ height:50px; }

 		.header-container .navbar-nav .dropdown .dropdown-toggle::after{
 			top:9px;
 		}
 		.header-container .navbar-nav .dropdown.show .dropdown-toggle::after{
 			top:12px;
 		}
 		.navbar-brand{
 			padding-top: 9px;
 		}
 		.header_logo{
 			height:auto;
 			width:140px;
 		}
 		.user-icon-profile img.mr-2{
 			width:30px;
 			height:30px;
 			border-radius: 50%;
 			display: inline-block;
			object-fit: cover;
		 }
		 .header-container{
			width:100%;
			}	
			.header-container .waves-effect{
				display:block;
			} 
			@media (min-width: 1400px) and (max-width: 1919px) {
				.header-container{
				margin: 0 auto;
				width: 100%;
				padding: 0 20px;
				}
			}
			@media (min-width: 1920px) {
				.header-container{
					margin: 0 auto;
					width: 100%;
					padding: 0 20px;
				}
			}
			@media (min-width: 1921px) and (max-width: 4400px) {
				.header-container{
					margin: 0 auto;
					width: 100%;
					padding: 0 20px;
				}
			}
			@media (min-width:4401px){
				.header-container{
					margin: 0 auto;
					width: 100%;
					padding: 0 20px;
				}
			}
 	 <?php } ?>

     .alert.coocky_tab{bottom:0px !important;margin-bottom:0px;padding: 10px 1.25rem;position: fixed;width: 100%;top:unset !important;}	
     .alert-dismissible.coocky_tab .close {padding: 8px 15px;}


<?php if(($this->uri->segment(1) == 'merchant') && ($this->session->userdata('access')=='marchant') || ($this->uri->segment(2) == 'edit_marchant_profile')){ ?>
	.header-container{
		width:100%;
	}	 
	@media (min-width: 1400px) and (max-width: 1919px) {
		.header-container{
		   margin: 0 auto;
		   width: 100%;
		   padding: 0 20px;
		}
	}
	@media (min-width: 1920px) {
		  .header-container{
			margin: 0 auto;
			width: 100%;
			padding: 0 20px;
		}
	}
	@media (min-width: 1921px) and (max-width: 4400px) {
		 .header-container{
			margin: 0 auto;
			width: 100%;
			padding: 0 20px;
		}
	}
	@media (min-width:4401px){
		.header-container{
			margin: 0 auto;
			width: 100%;
			padding: 0 20px;
		}
	}
	<?php } ?>

.name_icon{
		  display:inline-block;
		  font-size:1em;
		  width:2.5em;
		  height:2.5em;
		  line-height:2.5em;
		  text-align:center;
		  border-radius:50%;
		  background:#009DA6;
		  vertical-align:middle;
		  font-weight: bold;
		  text-transform: uppercase;
		  /*margin-right:.5rem !important;*/
		  color:white;
		  }
.supermenu-drop{
	overflow: hidden !important;
}

 </style>
  
<header class="relative header">
        <nav class="navbar navbar-expand-lg navbar-light navbar-expand-md h-100">
          <div class="relative header-container">
                <div class="d-flex cnc_top">
                      <a class="navbar-brand" <?php if($this->session->userdata('access')!='marchant' && $this->session->userdata('access') !='employee'){ ?>href="<?php  echo base_url(); ?>" <?php } ?>><img src="<?php echo base_url("assets/frontend/images/".HEADER_LOGO_IMAGE); ?>" class="header_logo" /></a>
                      <?php if(!empty($this->session->userdata('st_userid'))){ ?>
                      <div class=" d-inline-flex ml-auto align-self-center">
                      	<!-- viewfull  -->
                          <a class="waves-effect waves-block waves-light toggle-fullscreen display-ib" href="javascript:void(0);" id="optimize">
                            <!--<i class="fas fa-expand-arrows-alt" id="zoom_icon_click"></i>-->
                            <img src="<?php echo base_url('assets/frontend/images/'); ?>fullscreen.png" id="zoom_icon_click" width="20">
                            <!-- <i class="fas fa-compress-arrows-alt"></i> -->
                          </a>
                          <!-- viewfull -->
                          <style>
						 .under{
							text-decoration: underline;
						 }
						 #alert_message a{
						 text-decoration: underline;
   						 color: #fff;
						}
					 </style>
                      	
                      	<?php if($this->session->userdata('access') =='marchant'){ 
							$msgg = getmembership_exp($this->session->userdata('st_userid'));
							$muser=getselect_row('st_users','profile_status',array('id' => $this->session->userdata('st_userid')));
                      		if($msgg['msg'] && $muser->profile_status == 'complete'){
                      		?>
							<div class="alert alert-danger1 absolute vinay top mt-20 alert-top-0" 
							style="top: -6px;height: auto !important;padding: 2px 12px !important;background-color: #0098A1;
    color: white;">
							<span id="alert_message"><?php echo $msgg['msg']; ?></span>
						   </div>
						   <?php } ?>
                      	 <div class=" search-header-btn display-ib mr-2">
                               <a href="#" id="globalsearch_icon" style="z-index:1040 !important;">
						 <img src="<?php echo base_url("assets/frontend/images/magnifying-glass.svg"); ?>"
						  class="width24"></a>
                          </div>
                          <div class=" notification-header-btn display-ib mr-2">
                               <a href="javascript:void(0)" id="click_notification" onclick="openNav()">
                               		<img id="chg_nofifyImg" src="<?php echo base_url("assets/frontend/images/notification.svg"); ?>" class="width24">
                               		<!-- <img src="<?php echo base_url("assets/frontend/images/notification_fill.svg"); ?>" class="width24 hide"> -->
                               		<span id="all_notify_count" class="notification-num-count hide">0</span>
                               	</a>
                          </div>
                          <?php } ?>
                            <ul class="navbar-nav ml-auto">
                            	
                                <li class="nav-item r_l_s_s_h_dekstop ">
                                 
                                  <div class="dropdown onhovershow"> 
                                    <a id="" class="dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    	 <div class="user-icon-profile color333 fontfamily-regular font-size-14">
										  	<?php
										  	 $name_icon='';
										  	  $pic=base_url("assets/frontend/images/user-icon-profile.svg");
										  	  
										  	  $inf=getselect_row('st_users','id,profile_pic',array('id' => $this->session->userdata('st_userid')));
										  	   
										  	   if($this->session->userdata('access')=="marchant"){
													     $banners=getselect_row('st_banner_images','id,image',array('user_id' => $this->session->userdata('st_userid')));	
													     if(!empty($banners->image)){ 
															 if(file_exists("assets/uploads/banners/".$this->session->userdata('st_userid')."/".'prof_'.$banners->image)){															 
															 $pic=base_url("assets/uploads/banners/".$this->session->userdata('st_userid')."/".'prof_'.$banners->image."");
														   }
														 else  $pic=base_url("assets/uploads/banners/".$this->session->userdata('st_userid')."/".'crop_'.$banners->image.""); 
														   
														 }
														 else
														 	$name_icon=substr($this->session->userdata('business_name'), 0, 1);
														
										  		}
										  	 elseif($this->session->userdata('access')=="employee" && !empty($inf->profile_pic)){										  
												 $pic=base_url("assets/uploads/employee/".$this->session->userdata('st_userid')."/".'icon_'.$inf->profile_pic);
												}
												elseif($this->session->userdata('access')=="user" && !empty($inf->profile_pic)){
													$pic=base_url("assets/uploads/users/".$this->session->userdata('st_userid')."/".'icon_'.$inf->profile_pic."");	
												}
												 ?>
											<?php if($name_icon!="")
												echo '<span class="name_icon">'.$name_icon.'</span>';
												else{ ?>
												<picture>
												
													<img style="" src="<?php echo $pic; ?>" class="mr-2">
												</picture>	
												
											<?php }
											 if($this->session->userdata('access')=='marchant'){
											 	//echo $this->session->userdata('business_name');
											 }
											 else
											 	echo $this->session->userdata('sty_fname');?>
										  </div> 

                                    </a>
                                    <ul class="dropdown-menu box-shadow3 mt-10 onhovershow drop_down_show" aria-labelledby="Menu">
	                                      <?php if($this->session->userdata('access') == 'marchant')
											  		$profile_url= 'profile/edit_marchant_profile';
											  	    else if($this->session->userdata('access') == 'user')
											  	     	$profile_url= 'profile/edit_user_profile';
											  	     else
											  	     	$profile_url= 'profile/edit_employee_profile';

											
							       
                                        if($this->session->userdata('access') == 'marchant')
										  		$acs = 'merchant';
										  	else
										  		$acs = $this->session->userdata('access'); 
										  	
										$title='Meine Buchungen										';
										$icon='booking_icon666.svg';
										$cls="img3";
										if($this->session->userdata('access') == 'user')
												$myBook=base_url('user/all_bookings');
										else if($this->session->userdata('access') == 'employee'){
										 	  	$myBook=base_url('employee/dashboard');
										 	  	$title='Mein Kalender';
										 	  	$icon='dashboard_icon666.svg';
										 	  	$cls="img6";
										 	  }
										else if($this->session->userdata('access') == 'marchant'){
												$myBook=base_url('merchant/mydashboard');
										 		$title=$this->lang->line('dashboard');
										 		$icon='dashboard_icon666.svg';
										 		$cls="img6";
										}
										else
										 	$myBook='';
										 ?>
                                      <li class="mb-10">
                                      	<a href="<?php echo $myBook; ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test2">
                                      	<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/").$icon; ?>" id="<?php echo $cls; ?>"  class="mb-1"></span><?php echo $title; ?></a>
                                      </li>
                                      <?php if($this->session->userdata('access') == 'employee'){ ?>
									
									  <li class="mb-10">
                                      	  <a href="<?php echo base_url('employee/all_bookings'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test5">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/booking_icon666.svg"); ?>" id="img7"  class="mb-1"></span>Meine Buchungen</a>
                                      </li>
										  
								      <li class="mb-10">
                                      	<a href="<?php echo base_url($profile_url) ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/vuser_profile_icon.svg"); ?>" id="img1" class="mb-1"></span>Mein Profil</a>
                                      </li> 
                                      
                                      <?php  } 
                                      if($this->session->userdata('access') == 'user'){ ?>
										   <li class="mb-10">
	                                     <a href="<?php echo base_url('user/favourite_salon'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test3">
	                                      	<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/hard-icon666.svg"); ?>" id="img4" class="mb-1 ml-auto-1"></span>Meine Favoriten</a>
                                      </li> 
										  
										   <li class="mb-10">
                                      	<a href="<?php echo base_url($profile_url) ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/vuser_profile_icon.svg"); ?>" id="img1" class="mb-0"></span>Mein Profil</a>
                                      </li> 

                                       <!-- <li class="mb-10">
                                      	<a href="#" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="deactivate_profile">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/vuser_profile_icon.svg"); ?>" id="img1" class="mb-0"></span>Delete account</a>
                                      </li>  -->

                                    <?php } ?>
                                     <li class="mb-10">
                                      	<a href="<?php echo base_url($acs.'/change_password'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test1">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/change_password_icon.svg"); ?>" id="img2" class="mb-0"></span><?php echo $this->lang->line('change_password'); ?></a>
                                      </li>
                                      
                                    <?php if($this->session->userdata('access') == 'marchant'){ ?>
                                       
                                       <li class="mb-10">
                                      	<a href="javascript:Void(0);" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test11" data-toggle="modal" data-target=".suggest_improvements">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/recommendation.svg"); ?>" id="img8" class="mb-0 width20"></span><?php echo $this->lang->line('suggest_improvements'); ?></a>
									   </li>
									   <!-- payment new -->
									   <li class="mb-10">
                                      	<a href="<?php echo base_url('merchant/payment_list'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test15">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/payment-icon666.svg"); ?>" id="img11" class="mb-0 width24"></span><?php echo $this->lang->line('Payment'); ?></a>
									   </li>
									   <!-- membership new -->
									   <li class="mb-10">
                                      	<a href="<?php echo base_url('membership'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test13">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/membershi-icon666.svg"); ?>" id="img9" class="mb-0 width24"></span><?php echo $this->lang->line('Membership'); ?></a>
									   </li>
									   <!-- faq new -->
									   <li class="mb-10">
                                      	<a href="<?php echo base_url('faq'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test14">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/faq-icon666.svg"); ?>" id="img10" class="mb-0 width24"></span><?php echo $this->lang->line('FAQ'); ?></a>
                                       </li>
                                      
                                      <?php } ?>
                                      
                                      <li class="mb-10">
	                                     <a href="<?php echo base_url('auth/logouts'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test4">
	                                      	<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/layout.svg"); ?>" id="img5" class="mb-1 ml-auto-1"></span><?php echo $this->lang->line('Logout'); ?></a>
                                      </li>                                      
                                    </ul>
                                    </div>
                                </li>
                            </ul>
                      </div>
                      <?php } else{ ?>
                      		<div class=" d-inline-flex ml-auto align-self-center">
								<ul class="navbar-nav ml-auto">
									<li class="nav-item r_l_s_s_h_dekstop">
									  <a class="nav-link header-btn bgpinkorangegradient colorwhite font-size-14 fontfamily-regular widthfit" href="<?php echo base_url("salon/registrieren"); ?>"><?php echo $this->lang->line('register-your-salon'); ?></a>
									</li>
									<li class="nav-item align-self-center r_l_s_s_h_dekstop">
									  <a class="nav-link colorwhite font-size-14 fontfamily-regular openLoginPopup" href="#"><?php echo $this->lang->line('Login'); ?></a>
									</li>
									<li class="nav-item align-self-center r_l_s_s_h_dekstop">
									  <!-- <a class="nav-link colorwhite font-size-14 fontfamily-regular" href="<?php echo base_url("user/registration"); ?>">Sign Up</a> -->
									  <?php
									  if ($ptype != 'merchant_registration'):
										?>
									  <a class="nav-link colorwhite font-size-14 fontfamily-regular openRegisterPopup" href="JavaScript:Void(0);" data-toggle="modal" data-target="#openRegisterPopup"><?php echo $this->lang->line('Sign-Up'); ?></a>
									  <?php
									  else:
										?>
										<a class="nav-link colorwhite font-size-14 fontfamily-regular" href="<?php echo base_url("auth/c_registration"); ?>"><?php echo $this->lang->line('Sign-Up'); ?></a>
									  <?php
									    endif;
									  ?>
									</li>
								</ul>
						  	</div>
                      <?php }
                       ?>
                       <button class="navbar-toggler align-self-center" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" id="mobile-btn-toggle">
                          <span class="navbar-toggler-icon"></span>
                       </button>
                </div>
               
                      	

                <?php if(!empty($this->session->userdata('st_userid'))){ ?>
                <div class="collapse navbar-collapse w-100 " id="navbarSupportedContent">
                      <ul class="navbar-nav ml-auto mr-4">
                        <li class="nav-item r_l_s_s_h_mobile">
                          <div class="dropdown widthfit ml-auto"> 
                            <a id="rem_toggle" class="dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <div class="user-icon-profile color333 fontfamily-regular font-size-14">
                               	<?php
                               	if($name_icon!="")
								  echo '<span class="name_icon" style="width:33px; height: 33px; margin-right: .5rem">'.$name_icon.'</span>';
								else{ ?>
								<img style="width:44px; height: 44px; border-radius: 50px;" src="<?php echo $pic; ?>" alt="2" class="mr-2">
								<?php }
								 if($this->session->userdata('access')=='marchant')
									echo $this->session->userdata('business_name');
								 else
									echo $this->session->userdata('sty_fname');?> 
								</div>                                       
                            </a>
                            <ul class="dropdown-menu box-shadow3 mt-10 drop_down_show" aria-labelledby="Menu">
							<?php if($this->session->userdata('access') == 'marchant')
											  		$profile_url= 'profile/edit_marchant_profile';
											  	    else if($this->session->userdata('access') == 'user')
											  	     	$profile_url= 'profile/edit_user_profile';
											  	     else
											  	     	$profile_url= 'profile/edit_employee_profile';

											
							       
                                        if($this->session->userdata('access') == 'marchant')
										  		$acs = 'merchant';
										  	else
										  		$acs = $this->session->userdata('access'); 
										  	
										$title='Meine Buchungen';
										$icon='booking_icon666.svg';
										$cls="img3";
										if($this->session->userdata('access') == 'user')
												$myBook=base_url('user/all_bookings');
										else if($this->session->userdata('access') == 'employee'){
										 	  	$myBook=base_url('employee/dashboard');
										 	  	$title='Mein Kalender';
										 	  	$icon='dashboard_icon666.svg';
										 	  	$cls="img6";
										 	  }
										else if($this->session->userdata('access') == 'marchant'){
												$myBook=base_url('merchant/mydashboard');
										 		$title=$this->lang->line('dashboard');
										 		$icon='dashboard_icon666.svg';
										 		$cls="img6";
										}
										else
										 	$myBook='';
										 ?>
                                      <li class="mb-10">
                                      	<a href="<?php echo $myBook; ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test2">
                                      	<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/").$icon; ?>" id="<?php echo $cls; ?>"  class="mb-1"></span><?php echo $title; ?></a>
                                      </li>
                                      <?php if($this->session->userdata('access') == 'employee'){ ?>
									
									  <li class="mb-10">
                                      	  <a href="<?php echo base_url('employee/all_bookings'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test5">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/booking_icon666.svg"); ?>" id="img7"  class="mb-1"></span>Meine Buchungen</a>
                                      </li>
										  
								      <li class="mb-10">
                                      	<a href="<?php echo base_url($profile_url) ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/vuser_profile_icon.svg"); ?>" id="img1" class="mb-1"></span>Mein Profil</a>
                                      </li> 
                                      
                                      <?php  } 
                                      if($this->session->userdata('access') == 'user'){ ?>
										   <li class="mb-10">
	                                     <a href="<?php echo base_url('user/favourite_salon'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test3">
	                                      	<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/hard-icon666.svg"); ?>" id="img4" class="mb-1 ml-auto-1"></span>Meine Favoriten</a>
                                      </li> 
										  
										   <li class="mb-10">
                                      	<a href="<?php echo base_url($profile_url) ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/vuser_profile_icon.svg"); ?>" id="img1" class="mb-0"></span>Mein Profil</a>
                                      </li> 
                                      
                                    <?php } ?>
                                     <li class="mb-10">
                                      	<a href="<?php echo base_url($acs.'/change_password'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test1">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/change_password_icon.svg"); ?>" id="img2" class="mb-0"></span><?php echo $this->lang->line('change_password'); ?></a>
                                      </li>
                                      
                                    <?php if($this->session->userdata('access') == 'marchant'){ ?>
                                       
                                       <li class="mb-10">
                                      	<a href="javascript:Void(0);" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test11" data-toggle="modal" data-target=".suggest_improvements">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/recommendation.svg"); ?>" id="img8" class="mb-0 width20"></span><?php echo $this->lang->line('suggest_improvements'); ?></a>
									   </li>
									   <!-- payment new -->
									   <li class="mb-10">
                                      	<a href="<?php echo base_url('merchant/payment_list'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test15">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/payment-icon666.svg"); ?>" id="img11" class="mb-0 width24"></span><?php echo $this->lang->line('Payment'); ?></a>
									   </li>
									   <!-- membership new -->
									   <li class="mb-10">
                                      	<a href="<?php echo base_url('membership'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test13">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/membershi-icon666.svg"); ?>" id="img9" class="mb-0 width24"></span><?php echo $this->lang->line('Membership'); ?></a>
									   </li>
									   <!-- faq new -->
									   <li class="mb-10">
                                      	<a href="<?php echo base_url('faq'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test14">
                                      		<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/faq-icon666.svg"); ?>" id="img10" class="mb-0 width24"></span><?php echo $this->lang->line('FAQ'); ?></a>
                                       </li>
                                      
                                      <?php } ?>
                                      
                                      <li class="mb-10">
	                                     <a href="<?php echo base_url('auth/logouts'); ?>" class="color666 fontfamily-regular font-size-14 a_hover_666 changeimg" id="v-test4">
	                                      	<span class="width30 display-ib mr-2 text-center">
					                        <img src="<?php echo base_url("assets/frontend/images/layout.svg"); ?>" id="img5" class="mb-1 ml-auto-1"></span><?php echo $this->lang->line('Logout'); ?></a>
                                      </li>                                   
                                    </ul>
                          </div>
                        </li>
                      </ul>
                </div> 
                <?php } else{ ?>
                <div class="collapse navbar-collapse w-100 " id="navbarSupportedContent">
				  <ul class="navbar-nav ml-auto">
					<li class="nav-item r_l_s_s_h_mobile">
					  <a class="nav-link header-btn bgpinkorangegradient colorwhite font-size-14 fontfamily-regular widthfit m-auto" href="<?php echo base_url("merchant/registration"); ?>"><?php echo $this->lang->line('register-your-salon'); ?></a>
					</li>
					<li class="nav-item align-self-center r_l_s_s_h_mobile">
					  <a class="nav-link colorwhite font-size-14 fontfamily-regular openLoginPopup" href="#"><?php echo $this->lang->line('Login'); ?></a>
					</li>
					<li class="nav-item align-self-center r_l_s_s_h_mobile">
						<a class="nav-link colorwhite font-size-14 fontfamily-regular" href="JavaScript:Void(0);" data-toggle="modal" data-target="#openRegisterPopup"><?php echo $this->lang->line('Sign-Up'); ?></a>
						<!-- <a class="nav-link colorwhite font-size-14 fontfamily-regular" href="<?php echo base_url("user/registration"); ?>">Sign Up</a> -->
					</li>
				  </ul>
			    </div> 
			<?php } ?>


			<?php if( ($this->session->userdata('access')=="") || ($this->session->userdata('access')=='user')){ ?>
                <div class="cnc_bottom w-100" id="supermenu-accordion">
                  <div class="relative header_cnc_bottom_scroll ">
                    <ul class="navbar-nav mr-auto">
                        <?php  
                        $main_menu=get_menu(); 
							foreach($main_menu as $menu){
                        ?>
                        <li class="nav-item">
                        	<a class="nav-link header-menu-cat" data-id="collapse-supermenu<?php echo $menu->id; ?>" <?php if($menu->sub){ ?> data-toggle="collapse" href="#collapse-supermenu<?php echo $menu->id; ?>" role="button" aria-expanded="" aria-controls="collapse-supermenu<?php echo $menu->id; ?>" <?php } ?>><?php echo $menu->category_name; ?></a>
                        </li>
                        <?php } ?>
                        
                      </ul>
                  </div>
                  
                </div> 
               <?php } ?>
           </div>
             
                      <?php 
                      if(!empty($main_menu)){
						  
                      foreach($main_menu as $menu){
                      	$submenu=get_filtersub_menu($menu->id); 
                      	if(!empty($submenu)){ ?>
                      <div class=" w-100 supermenu-drop collapse" id="collapse-supermenu<?php echo $submenu[0]['parent_id']; ?>" data-parent="#supermenu-accordion">
                       
						<div class="container">
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
								<ul class="lineheight30 pl-0 mb-0">
								<?php 
			                      foreach($submenu as $suss){ ?>
									<li class=""><a class="fontfamily-regular color333 font-size-14 a_hover_333"
									 href="<?php echo base_url('listing/search/'.'?filtercat='.url_encode($suss['my_cat_id'])); ?>"><?php echo $suss['category_name']; ?></a></li>
									<?php } ?>
									<li class=""><a class="fontfamily-regular color333 font-size-14 a_hover_333" href="<?php echo base_url('listing/search/'.create_slug_without_db($menu->category_name).'?category='.url_encode($menu->id)); ?>">Alle Behandlungen</a></li>
								</ul>
								</div>
						  </div>
						</div>
						
                        <?php } ?>
                      </div>
                      <?php } } ?>
        </nav>
    </header>


    <div id="opct" class="" onclick="closeNav()"></div>
    <div class="after-overlay-on-click-toggle"></div>


    <!-- notification code -->
    
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
  <picture class="popup-crose-black-icon">
            <!-- <source srcset="<?php
		  // echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php
		  // echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon"> -->
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>			
  </a>
  <p class="font-size-20 color333 fontfamily-medium pl-3"><?php echo $this->lang->line('Notification'); ?></p>
  <p class="font-size-18 color666 fontfamily-medium pl-3"><?php echo $this->lang->line('Recent_booking_activity'); ?></p>
  <div class="relative scroll300 custom_scroll pl-3" id="chg_div_activity" style="">
  </div>


  <p class="font-size-18 color666 fontfamily-medium pl-3 mb-0 mt-3">5 letzte Buchungen</p>
  <div class="relative scroll300 custom_scroll pl-3" id="div_chg_upcoming" style="">
  </div>
</div>

<!-- Modal -->
<div id="" class="modal suggest_improvements fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-md" >

    <!-- Modal content-->
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
	  <picture class="popup-crose-black-icon">
            <source srcset="<?php 
		 // echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php 
		  //echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
      </a>
      <div class="modal-body text-left">
        <h3 class="font-size-20 color333 fontfamily-medium mb-5"><?php echo $this->lang->line('suggest_improvements1'); ?></h3>
        <form id="suggestion_form">
        <div class="form-group vmb-10" id="suggest_validate">
	        <label class="inp v_inp_new" style="height: 180px;">
	          <textarea class="form-control custom_scroll w-100" style="height:170px" placeholder="&nbsp;" name="suggest"></textarea>
	          <span class="label"><?php echo $this->lang->line('sug_text'); ?></span>
	        </label>
	     </div>
	     <div class="text-center">
    		<button type="button" id="suggestion_submit" class="btn widthfit"><?php echo $this->lang->line('Submit'); ?></button>
    	</div>
    	</form>
      </div>
    </div>

  </div>
</div>
<script>
	<?php
	if($this->session->userdata('access') =='marchant'){
		$dataCont = getmembership_exp($this->session->userdata('st_userid'));
		if($dataCont['expired']){
		?>
			window.glb_expired = true;
		<?php
		}
	}
	?>
	function openNav() {
	  document.getElementById("mySidenav").style.right = "0px";
	  document.getElementById("opct").classList.add("bodyopicity");
	}
	function closeNav() {
	  document.getElementById("mySidenav").style.right = "-342px";
	  document.getElementById("opct").classList.remove("bodyopicity");
	}
	
</script>
<!-- notification code end -->
