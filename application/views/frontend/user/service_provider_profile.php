<?php $this->load->view('frontend/common/header'); $keyGoogle =GOOGLEADDRESSAPIKEY; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />  
 
   <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.css" rel="stylesheet" type="text/css" />
   <style type="text/css">

    /* #gimg-detail {
      z-index: 9999999;
    } */
    .bg-grey {
      background-color: grey;
    }
    .modal-fullscreen {
      padding-right: 0px !important;
    }
    .modal-fullscreen .modal-dialog {
      max-width: 100%;
      margin: 0px;
      min-height: 100%;
      height: 100%;
    }
    .modal-fullscreen .modal-content {
      min-height: 100%;
    }

    .gal-row2 {
      display: flex;
      flex-direction: column;
      max-height: 480px;
      flex-wrap: wrap;
      width: 100%;
      overflow: auto;
    }

    .gal-row1 {
      display: flex;
      flex-wrap: wrap;
    }
    
    #galimage {
      padding: 10px;
      border-radius: 25px;
      max-width: 100%;
      object-fit: cover;
    }
    .gimg {
      border: 1px solid #DCEBEC;
      width: 172px;
      transition: linear .2s;
      height: 207px;
    }
    .gimg:hover {
      border-radius: 5px;
      cursor: pointer;
      box-shadow: 0rem 3px 25px rgb(215 232 233);
      border: 1px solid transparent;
      transform: scale(1.02);
    }
     .checkbox-image input[type="checkbox"] + label {
        font-size: 14px;
      }
      .dropdown-menu > li > input:checked ~ label {
        color: #322;
        text-decoration: none;
        outline: 0;
          background-color: #DCEFF2 !important;
      }
      .dropdown-menu li div label{
      word-break: break-all;
      padding:4px 9px 4px 14px;
      color:#999999; 
      margin-bottom: 1px !important;
    }
      .dropdown-menu li:hover, .dropdown-menu  li:focus {
        text-decoration: none;
        color: #333;
        background-color: rgba(19,22,29,0.03);
      }
      .dropdown-menu li:first-child, .dropdown-menu  li:first-child {
        text-decoration: none;
        color: #333;
        background-color: transparent;
      }
      .dropdown-menu li:hover label, .dropdown-menu  li:focus label {
        text-decoration: none;
        color: #333;
      }
      .dropdown-menu li input:checked ~ label {
        color: #322;
        text-decoration: none;
        outline: 0;
        width: 100%;
          background-color: #DCEFF2 !important;
      }
      
      .checkbox-image label {
          padding-left: 1rem;
          
      }
     
   </style>

<!-- start mid content section-->
<?php 
  if($this->session->userdata('access') =='marchant' || $this->session->userdata('access') =='employee')
      $cls='pt-84';
  else
      $cls='pt-120';
      
      $bannerPath='assets/uploads/banners/';
      
      //echo file_exists($_SERVER['DOCUMENT_ROOT'].MAINDIR.'/assets/uploads/banners/'.$userdetail[0]->id.'/crop_'.$userdetail[0]->image).$psth; die;

if(!empty($userdetail)){ 
	//echo '<pre>'; print_r($matchcatsubcat); die;
  ?>
    <style>
 .slick-slide.slick-active img {
      /* width: 79% !important; */
      /* border-radius: 0px 8px 8px 0px; */
  }
  
  .slick-slide.slick-current.slick-active img {
      width: 100% !important;
      /* border-radius: 8px 0px 0px 8px; */
  }
  
		.leaflet-container.leaflet-touch-drag.leaflet-touch-zoom {
			-ms-touch-action: none;
			touch-action: none;
			z-index: 0 !important;
		}

   
      .dropdown-menu > li > input:checked ~ label {
  color: #333;
  text-decoration: none;
  outline: 0;
    background-color: #DCEFF2 !important;
}

		.leaflet-bar{
			border: 1px solid #ced4da !important;
			z-index: 4 !important;
			box-shadow: none !important;
		}
	.bg_change{
		background-color: #92D5DB;
		}
		.first_pop{
            z-index: 999;
			position: absolute;
			left: 70px;
			padding-top: 36px;
		}
		.second_pop{
			z-index: 999;
			position: absolute;
			left: 130px;
			padding-top: 36px;	
		}
	.leaflet-container.leaflet-touch-drag.leaflet-touch-zoom {
    -ms-touch-action: none;
    touch-action: none;
    z-index: 0 !important;
}
@media (max-width: 767px) {
  .slider-container {
    width: 100%;
    padding-top: 66.66%; /* 1:1 Aspect Ratio */
    position: relative; /* If you want text inside of it */
  }
  .single-slider {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
  }
  .slick-track {
    height: 100%;

  }
  .slider-full-img {
    height: 100% !important;
    object-fit: cover;
    object-position: left;
  }
  .slick-slide {
    flex-grow: 1;
    height: 100% !important;
  }
  .single-slider .slick-slide.slick-current.slick-active {
    transition: all 300ms !important;
  }
  .single-slider .slick-list {
    overflow: hidden;
    height: 100%;
  }
}
	</style>
	
    <section class="sevice_provider_section2 clear  bgwhite  nsec1">
      <div class="container">
		  <section class="sevice_provider_section1 clear relative <?php echo $cls; ?>">
        <div class="tab_group_button">
          <div class="tab_group_button_links">
              <a id="m_about" class="tgbl_a1 display-ib vertical-top "><?php echo $this->lang->line('About'); ?></a><a id="m_booknow" class="tgbl_a2 display-ib vertical-top "style="color:white;"><?php echo $this->lang->line('Book-now'); ?></a>
          </div>
        </div>  
        <div class="relative">
          <div class="slider-container">
          <div class="single-slider">
            <?php if($userdetail[0]->image !='' || $userdetail[0]->image1 !='' || $userdetail[0]->image2 !='' || $userdetail[0]->image3 !='' || $userdetail[0]->image4 !='') {              
            if($userdetail[0]->image !='') { $crop=''; if(file_exists($bannerPath.$userdetail[0]->id.'/crop_'.$userdetail[0]->image)) $crop='crop_';  ?>
				      <picture class="upl-gallary-image">
                <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image,'webp'); ?>" type="image/webp" class="slider-full-img">
                <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image,'webp'); ?>" type="image/webp" class="slider-full-img">
                <img src="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image,'png'); ?>" type="image/png" class="slider-full-img">
              </picture>
            <?php  } 
            if($userdetail[0]->image1 !='') { $crop=''; if(file_exists($bannerPath.$userdetail[0]->id.'/crop_'.$userdetail[0]->image1)) $crop='crop_'; ?>
             <picture class="upl-gallary-image1">
				        <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image1,'webp'); ?>" type="image/webp" class="slider-full-img">
				        <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image1,'webp'); ?>" type="image/webp" class="slider-full-img">
				        <img src="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image1,'png'); ?>" type="image/png" class="slider-full-img">
			       </picture>
            <?php }
            if($userdetail[0]->image2 !='') { $crop=''; if(file_exists($bannerPath.$userdetail[0]->id.'/crop_'.$userdetail[0]->image2)) $crop='crop_'; ?>
              <picture class="upl-gallary-image2">
				         <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image2,'webp'); ?>" type="image/webp" class="slider-full-img">
				         <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image2,'webp'); ?>" type="image/webp" class="slider-full-img">
				         <img src="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image2,'png'); ?>" type="image/png" class="slider-full-img">
			        </picture>
            <?php }
            if($userdetail[0]->image3 !='') {			
			        $crop=''; if(file_exists($bannerPath.$userdetail[0]->id.'/crop_'.$userdetail[0]->image3)) $crop='crop_'; ?>
              <picture class="upl-gallary-image3">
				        <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image3,'webp'); ?>" type="image/webp" class="slider-full-img">
				        <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image3,'webp'); ?>" type="image/webp" class="slider-full-img">
				        <img src="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image3,'png'); ?>" type="image/png" class="slider-full-img">
			        </picture>
            <?php }
            if($userdetail[0]->image4 !='') { $crop=''; if(file_exists($bannerPath.$userdetail[0]->id.'/crop_'.$userdetail[0]->image4)) $crop='crop_'; ?>
              <picture class="upl-gallary-image4">
				        <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image4,'webp'); ?>" type="image/webp" class="slider-full-img">
				        <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image4,'webp'); ?>" type="image/webp" class="slider-full-img">
				        <img src="<?php echo getimge_url('assets/uploads/banners/'.$userdetail[0]->id.'/',$crop.$userdetail[0]->image4,'png'); ?>" type="image/png" class="slider-full-img">
			        </picture>
            <?php }
             if($userdetail[0]->image1 =='' && $userdetail[0]->image2 =='' && $userdetail[0]->image3 =='' && $userdetail[0]->image4 =='') { ?>
				 <picture class="upl-gallary-image4">
				        <source srcset="<?php echo base_url('assets/frontend/images/noimage.png'); ?>" type="image/png" class="slider-full-img">
				        <source srcset="<?php echo  base_url('assets/frontend/images/noimage.png'); ?>" type="image/png" class="slider-full-img">
				        <img src="<?php echo  base_url('assets/frontend/images/noimage.png'); ?>" type="image/png" class="slider-full-img">
			        </picture>
				<?php }
            } 
            else{ ?>
                <img src="<?php echo base_url('assets/frontend/images/provider-slider-img1.png'); ?>">
            <?php } ?>
          <!-- <img src="<?php //echo base_url('assets/frontend/images/provider-slider-img1.svg'); ?>">
          <img src="<?php //echo base_url('assets/frontend/images/provider-slider-img1.svg'); ?>"> -->
          </div>
          
          <?php /*
            <div class="single-slider">
              <img src="<?php echo base_url('assets/frontend/images/provider-slider-img1.png'); ?>" class="slider-full-img">
              <img src="<?php echo base_url('assets/frontend/images/provider-slider-img1.png'); ?>" class="slider-full-img">
              <img src="<?php echo base_url('assets/frontend/images/home_sec2_view_venues_img1.webp'); ?>" class="slider-full-img">
              <img src="<?php echo base_url('assets/frontend/images/crop_banner_1608405852.jpg.webp'); ?>" class="slider-full-img">
              <img src="<?php echo base_url('assets/frontend/images/home_sec2_view_venues_img1.webp'); ?>" class="slider-full-img">
            </div>
            */ ?>
        </div> 
        </div>
      </section>
        <div class="relative bglightgreen1">
	        <div class="row">
	          <div class="col-12 col-sm-7 col-md-6 col-lg-6 col-xl-8">
	            <h1 class="font-size-36 fontfamily-medium colorblack1 pt-25" style="-webkit-line-clamp:1;"><?php echo $userdetail[0]->business_name; ?></h1>
	            <p class="color333 fontfamily-medium font-size-16 mb-25"><?php echo $userdetail[0]->address.', '.$userdetail[0]->city; ?></p>
	          </div>
	          <div class="col-12 col-sm-5 col-md-6 col-lg-6 col-xl-4 text-right clear">            
	            <div id="m_review" class="pt-30" style="cursor: pointer;">
	                  
	               <?php
	               $echorate=number_format($userdetail[0]->rating,1);
	               //$echorate='2.5';
	                //$rate=round($userdetail[0]->rating);
	               
	               echo '<span style="font-size:24px;color: #FF9944;">'.$echorate.' &nbsp;</span>';
	                      for($i=1;$i < 6;$i++){
	                        if($i <= $echorate){
	                      ?><i class="fas fa-star starcolor mr-2 fontsize-22"></i>
	                      <?php }elseif($echorate<$i && $echorate>($i-1)){ ?>
						         <i class="fas fa-star color999 mr-2 fontsize-22"></i>
                                      <i class="fas fa-star-half colororange mr-2 fontsize-22" ></i>    
						        <?php } else{ ?>
	                        <i class="fas fa-star color999 mr-2 fontsize-22"></i>
	                      <?php }
	                      } ?>
	           
	            </div>
	            <a id="m_review" href="javascript:void(0)" class="color666 a_hover_666 fontfamily-medium font-size-16 display-b mt-20">
               
                <?php if($rcount==0){echo $rcount.' Bewertungen';}else if($rcount==1){echo $rcount.' Bewertung';}else{echo $rcount.' Bewertungen';}  ?>
              </a>
	          </div>
	        </div>
        </div>
      </div>
    </section>
    <?php //print_r($all_cart_item); 
    $cartValue=array();
    if(!empty($all_cart_item)){
      foreach($all_cart_item as $all){
      $cartValue[]=$all->service_id;
      }
    }
    ?>
    <section class="sevice_provider_section2 clear  bgwhite nsec2">

     <?php
    	//echo '<pre>'; print_r($matchcatsubcat); die;
      if(!empty($matchcatsubcat)){ ?>
      <div class="container relative pb-20">
        <!-- <div class="alert alert-danger absolute vinay top w-100 mt-15 alert_message alert-top-0" id="error_message" style="display:none; top: -27px; width: 97%!important;">
              <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>
              <span id="alert_message">Sorry login with user to Add Service..! </span>
        </div> -->
        <h1 class="font-size-22 fontfamily-medium color333 text-lines-overflow-1 pt-25" style="-webkit-line-clamp:1;">Service Details</h1>
        <div class="row">
          <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">            
            <p class="color999 fontfamily-medium font-size-18 text-lines-overflow-1 pb-3" style="-webkit-line-clamp:1;"><?php echo $this->lang->line('Matching-Search-Result'); ?></p>
          </div>
          <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">  
			<?php 
				$i=0;
				$sub_ids=0;
				$chk_detail="";
				   foreach($matchcatsubcat as $k=>$v){
						// echo "<pre>"; print_r($v); 
					
				   $multiPlecat="";
				   $allid=array();
					$startFrom="";
					$duration=array();
					 $abd="";
				    $ij=1;
          $s2 = 0; $ss2 = 0;
					foreach($v as $ser){
						if ($ij == 1) {
              $s2 = $ser->price; $ss2 = 0;
            }
						if ($s2 != $ser->price) $ss2 = 1;
            else if ($s2 == $ser->price && $ser->price_start_option == 'ab')  $ss2 = 1;
            if ($s2 > $ser->price) $s2 = $ser->price;

						if(empty($ser->name)){ ?>
						
						<!-- data-toggle="model" data-target="#service-show-detail" -->
						<?php $cherv = check_review($ser->id);
						       
						
						 if(!empty($ser->service_detail) || !empty($cherv)){ ?>
						<a data-id="<?php echo $ser->id; ?>" data-allid="<?php echo $ser->id; ?>" href="javascript:void(0)" class="colorcyan a_hover_cyan first_pop salondetail_popup"><?php echo $this->lang->line('Show-Details'); ?></a>
						<?php } ?>
						<div class="border-b d-flex py-3 pl-3 bookingSelect"
             data-cid="<?php echo $ser->category_id; ?>" 
             data-fid="<?php echo $ser->filtercat_id; ?>"
              data-id="<?php echo $sid; ?>"
               id="<?php echo $ser->id; ?>"
                value="<?php if(!empty($ser->discount_price)) 
                echo $ser->discount_price; else echo $ser->price; ?>" 
                style="z-index: 0;position: relative;"> 
						  <div class="deatail-box-left">
							<p class="color333 font-size-16 fontfamily-medium mb-0"><?php echo $k; ?></p>
							<span class="font-size-14 color666 fontfamily-regular">
                <?php echo $ser->duration ?> Min.</span>
							
						  </div>
						  <div class="deatail-box-right d-inline-flex">
							<div class="relative text-right width200">
							  <p class=" color333 font-size-14 fontfamily-medium mb-0">
                  <?php if(!empty($ser->discount_price))
                   echo "<small class='font-size-18 fontfamily-semibold'>"
                   .price_formate($ser->discount_price)." €</small>";
                    else echo "<small class='font-size-18 fontfamily-semibold'>"
                    .($ser->price_start_option=="ab"?$ser->price_start_option:'')
                    ." ".price_formate($ser->price)." €</small>"; ?> </p>
							<?php if(!empty($ser->discount_price)){ $discount=get_discount_percent($ser->price,$ser->discount_price);
							    	if(!empty($discount)){  ?><span class="colorcyan fontfamily-regular font-size-14"><?php echo $this->lang->line('save-up-to'); ?> <?php echo $discount; ?> %</span> <?php } 
								} ?>
							</div>
							<button data-id="<?php echo $sid; ?>" class=" btn <?php echo (in_array($ser->id,$cartValue))?'selectedBtn':'btn-border-orange'; ?> btn-small widthfit2 btn-ml-20 class-<?php echo $ser->id; ?>" id="<?php echo $ser->id; ?>" value="<?php if(!empty($ser->discount_price)) echo $ser->discount_price; else echo $ser->price; ?>"  data-cid="<?php echo $ser->category_id; ?>" data-fid="<?php echo $ser->filtercat_id; ?>"><?php echo $this->lang->line('Select'); ?></button>
						  </div>
						</div>
						<?php } else {
							if(in_array($ser->id,$cartValue)) $slectClass='selectedBtn'; else $slectClass='btn-border-orange';
							
							$discntPrice="";
							$sub_ids = $ser->id;
							
							$allid[] = $ser->id;
							
							 if($ser->price_start_option=='ab' || $ij==2){
								$abd="ab ";
								} 
							$ij++;
							$chk_detail=$ser->service_detail;
							if(!empty($ser->discount_price)){ 
								$discount=get_discount_percent($ser->price,$ser->discount_price);
                // $discount=$ser->price;
							    	if(!empty($discount)){
										$discntPrice='<span class="colorcyan fontfamily-regular font-size-14">spare bis zu '.$discount.' %</span>'; 
										} 
								}
								
								if(!empty($ser->price)) $price=$ser->price; else $price= $ser->price;
								
								if(empty($startFrom)) $startFrom=$price;
								
								if($startFrom>$price) $startFrom=$price;
								
								$duration[]=$ser->duration;
							
							$multiPlecat=$multiPlecat.'<div class="d-flex py-2 pl-3 bookingSelect" data-id="'.$sid.'" data-cid="'.$ser->category_id.'" data-fid="'.$ser->filtercat_id.'" id="'.$ser->id.'" value="'.$price.'">
											  <div class="deatail-box-left">
												<p class="color666 font-size-16 fontfamily-medium mb-0">'.$ser->name.'</p>
												<span class="font-size-14 color999 fontfamily-regular">'.$ser->duration.' Min.</span>
											  </div>
											  <div class="deatail-box-right d-inline-flex pl-20">
												<div class="relative text-right width160">
												  <p class="fontfamily-medium color333 font-size-14 mb-0">'
                          .($ser->price_start_option=="ab"?$ser->price_start_option:'').
                          ' '.price_formate($price).' €</p>
												  '.$discntPrice.'
												</div>
												<button data-id="'.$sid.'" class=" btn '.$slectClass.' widthfit2 btn-ml-20 class-'.$ser->id.'" id="'.$ser->id.'" value="'.$price.'" data-cid="'.$ser->category_id.'" data-fid="'.$ser->filtercat_id.'">'.$this->lang->line('Select').'</button>
											  </div>
											</div>';
						 } } 
						 if(!empty($multiPlecat)){
							 $min = min($duration);
                             $max = max($duration);
                             $cherv = check_review(implode(",",$allid));
							
							  if(!empty($chk_detail) || !empty($cherv)){
							  ?>
							  <a data-id="<?php echo $sub_ids; //$v[0]->id; ?>" data-allid="<?php echo implode(",",$allid); ?>" href="javascript:void(0)" class="colorcyan a_hover_cyan <?php echo (($min==$max)?'first_pop':'second_pop'); ?> salondetail_popup"><?php echo $this->lang->line('Show-Details'); ?></a>
							  <?php } ?>
								<div class="border-b px-3"> 
								  <div class="accordion" id="right-side-box-accordian4">
									<div class="accordion-group">
									  <div class="accordion-heading p-3 relative">
										<a class="accordion-toggle color333 fontfamily-medium font-size-16 a_hover_333 d-flex collapsed" data-toggle="collapse" data-parent="#right-side-box-accordian4"  href="#collapseOne42<?php echo $i; ?>">
										   <p class="relative vertical-top deatail-box-left">
														<?php echo $k; ?>
										  <span class="font-size-14 color999 fontfamily-regular display-b">
                         <?php if($min==$max){ echo $min." Min."; }
                          else { echo $min." Min. - ".$max ." Min."; } ?> </span>
										  </p>
										  <span class="fontfamily-medium color333 font-size-14 mb-0 vertical-top deatail-box-right text-right"
                       style="margin-right: 27px;"><?php echo $ss2 ? 'ab ' : ''; ?>
                       <small class="font-size-18 fontfamily-semibold">
                         <?php echo price_formate($startFrom)." €"; ?></small></span>
										</a>
										
									  </div>
									  <div id="collapseOne42<?php echo $i; ?>" class="accordion-body collapse">
										<div class="accordion-inner">
											<?php 
										        echo $multiPlecat;
                                              ?>


										 
										</div>
									  </div>
									</div>
								  </div>
								</div>
								
				<?php } $i++; }   ?>



          </div>
        </div>
      </div>
      <?php } ?>
    </section>

    <section class="sevice_provider_section3 bgwhite clear pb-30 nsec3" >
      <div class="box-shadow2 bgwhite mbooknow_sec" >
        <div class="container">
        	<div class="relative bglightgreen1 pt-30 pb-30" >
          <h3 class="font-size-24 color333 fontfamily-medium mb-25"><?php echo $this->lang->line('All_Services'); ?></h3>
          <div class="d-flex" id="allsservice">
            <div id="tab-box-v1" class="mainCatSliderTab relative box-5-fill" data-id="1">
              <picture class="">
                <source srcset="<?php echo base_url('assets/frontend/images/box-5-fill-img1.webp') ?>" type="image/webp" class="">
                <source srcset="<?php echo base_url('assets/frontend/images/box-5-fill-img1.svg') ?>" type="image/svg" class="">
                <img src="<?php echo base_url('assets/frontend/images/box-5-fill-img1.svg') ?>" class="">
              </picture>
              <span class="color333 fontfamily-medium font-size-12 mt-2 display-b span_text_block lineheight24"><?php echo $this->lang->line('Show_all'); ?></span>
            </div>
             <?php if(!empty($main_category)){ $ct=2;
                  foreach($main_category as $cat){ 
                      if($cat->icon !='')
						 {
						   $cat_img= getimge_url('assets/uploads/category_icon/'.$cat->mainid.'/',$cat->icon,'png');
						   $cat_imgw= getimge_url('assets/uploads/category_icon/'.$cat->mainid.'/',$cat->icon,'webp');
						 }
					  else{
						$cat_img=base_url('assets/frontend/images/noimage.png');
						$cat_imgw=base_url('assets/frontend/images/noimage.webp');
						}

                     ?>
            <div id="tab-box-v<?php echo $ct; ?>" class="mainCatSliderTab relative box-5-fill" data-id="<?php echo $ct; ?>">
            
              <img src="<?php echo base_url('assets/frontend/images/round-orange-icon.png.png') ?>" alt="" id="checked_mcat_<?php echo $cat->mainid; ?>" class="round-orange-icon mancat_check_icon <?php if(empty($cat->total_in_cart)) echo 'display-n' ?>"/>
				<picture>
					<source srcset="<?php echo $cat_imgw; ?>" type="image/webp">
					<source srcset="<?php echo $cat_imgw; ?>" type="image/webp">
				   <img src="<?php echo $cat_img; ?>">
				</picture>
              <span class="color333 fontfamily-medium font-size-12 display-b span_text_block lineheight24 mt-2"><?php echo $cat->category_name; ?></span>
            </div>
            <?php $ct++; } 
              } ?>
          </div>
         </div>
        </div>
      </div>
      <div class="relative bgwhite">
        <div class="container tab-content pt-30">
          <div class="dispay-n mainCatTabs" id="tab-box1">
            <h3 class="font-size-24 color333 fontfamily-medium mb-25">Zeige alle Services</h3>
            <div class="row">

              <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 pr-5">            
                <?php 
                if(!empty($filter_category)){ 
					 $AllSubCat = array();
					 $AllSubCat1 = array();
					$ii=0;
					
                  foreach($filter_category as $cat){
					    if($ii==0){
					      $AllSubCat1 = $all_subcats[$cat->filter_category];				      
				        }
				        
				          $AllSubCat = $all_subcats[$cat->filter_category];		
					   ?>
                <a href="javascript:void(0);" class="getService" id="<?php echo $cat->created_by; ?>" data-fid="<?php echo $cat->filter_category; ?>" data-id="<?php echo implode(',',$AllSubCat); ?>">
                  <div id="div_<?php echo $cat->filter_category; ?>" class="<?php echo ($ii >0)?'bgwhite':'bggreengradient'; ?> pt-15 pb-15 pl-25 mt-3">
                  <span id="span_<?php echo $cat->filter_category; ?>" class="<?php echo ($ii >0)?'color999':'colorwhite'; ?> fontfamily-medium font-size-16 display-b"><?php echo $cat->filter_category_name; ?> <img src="<?php echo base_url('assets/frontend/images/round-orange-icon.png.png') ?>" alt="" id="checked_fcat_<?php echo $cat->filter_category; ?>" style="float: right;height: 22px;margin-right: 5px;" class="round-orange-icon mancat_check_icon checked_fcatc_<?php echo $cat->filter_category; ?> <?php if(empty($cat->total_in_cart)) echo 'display-n' ?>"/></span>
                  </div></a>
                  
              <?php $ii++; } ?>
              
                <input type="hidden" id="activeAllresult" value="<?php echo $sub_category[0]->filter_category; ?>" name="">
                <?php
                      } ?>
                <!-- <div class="bggreengradient pt-15 pb-15 pl-25 mt-3">
                  <span class="colorwhite fontfamily-medium font-size-16 display-b">Eyebrow Treatments ( 3 )</span>
                </div> -->
                
              </div>

              <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" id="allservice_div">
                <?php if(!empty($sub_category)){
                	                $this->db->having('checkemp > 0');
                	                $this->db->where_in('subcategory_id',$AllSubCat1);  
                      $allservices=  $this->user->join_two('st_merchant_category','st_category','subcategory_id','id',array('st_merchant_category.created_by' => $sub_category[0]->created_by,'st_merchant_category.status' =>'active','st_merchant_category.online' =>1),'st_merchant_category.id,st_merchant_category.category_id,st_merchant_category.filtercat_id,st_merchant_category.service_detail,name,duration,category_name,price,price_start_option,discount_price,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`st_merchant_category`.`id` AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND online_booking=1 AND status="active")>=1),0) as checkemp');
                     
                     if(!empty($allservices)){
						 $services_by_subcategory=array();
						  foreach($allservices as $service)$services_by_subcategory[$service->category_name ][] = $service;
						$jj=0;
						$sub_ids=0;
						foreach($services_by_subcategory as $k=>$v){
							
						$multiPlecat="";
						
                        $allid=array();
                        
                        $startFrom="";
					    $duration=array();
					    $abd="";
					    $ij=1;
             // print_r($ser);die;
             $s2 = 0;$ss2 = 0;
					foreach($v as $ser){
            if ($ij == 1) {
              $s2 = $ser->price; $ss2 = 0;
            }
						if ($s2 != $ser->price) $ss2 = 1;
            else if ($s2 == $ser->price && $ser->price_start_option == 'ab')  $ss2 = 1;
            if ($s2 > $ser->price) $s2 = $ser->price;

						if(empty($ser->name)){
							$cherv = check_review($ser->id); 
							if(!empty($ser->service_detail) || !empty($cherv)){ ?>
						<a data-id="<?php echo $ser->id; ?>" data-allid="<?php echo $ser->id; ?>" href="javascript:void(0)" class="colorcyan a_hover_cyan first_pop salondetail_popup"><?php echo $this->lang->line('Show-Details'); ?></a>
						<?php } ?>
						<div class="border-b d-flex py-3 pl-3 bookingSelect" data-id="<?php echo $sid; ?>" id="<?php echo $ser->id; ?>" value="<?php if(!empty($ser->discount_price)) echo $ser->discount_price; else echo $ser->price; ?>"> 
						  <div class="deatail-box-left">
							<p class="color333 font-size-16 fontfamily-medium mb-0">
                <?php echo $k; ?></p>
							<span class="font-size-14 color666 fontfamily-regular">
                <?php echo $ser->duration ?> Min.</span>
						  </div>
						  <div class="deatail-box-right d-inline-flex">
							<div class="relative text-right width200">
							  <p class="fontfamily-medium color333 font-size-14 mb-0">
                  <?php if(!empty($ser->price)) 
                  echo "<small class='font-size-18 fontfamily-semibold'>"
                  .price_formate($ser->price)." €</small>";
                   else echo "<small class='font-size-18 fontfamily-semibold'>"
                   .($ser->price_start_option=="ab"?$ser->price_start_option:'').
                   " ".price_formate($ser->price)." €</small>"; ?> </p>
							<?php if(!empty($ser->discount_price))
              { $discount=get_discount_percent($ser->price,$ser->discount_price);
							    	if(!empty($discount)){  ?>
                    <span class="colorcyan fontfamily-regular font-size-14">
                      <?php echo $this->lang->line('save-up-to'); ?> 
                      <?php echo $discount; ?> %</span> <?php } 
								} ?>
							</div>
							<button data-id="<?php echo $sid; ?>" class=" btn <?php echo (in_array($ser->id,$cartValue))?'selectedBtn':'btn-border-orange'; ?> btn-small widthfit2 btn-ml-20 class-<?php echo $ser->id; ?>" id="<?php echo $ser->id; ?>" value="<?php if(!empty($ser->discount_price)) echo $ser->discount_price; else echo $ser->price; ?>" data-cid="<?php echo $ser->category_id; ?>" data-fid="<?php echo $ser->filtercat_id; ?>"><?php echo $this->lang->line('Select'); ?></button>
						  </div>
						</div>
						<?php } else {
							if(in_array($ser->id,$cartValue)) $slectClass='selectedBtn'; else $slectClass='btn-border-orange';

							$discntPrice="";
							$sub_ids=$ser->id;
							
							$allid[]=$ser->id;
							
							 if($ser->price_start_option=='ab' || $ij==2){
								$abd="ab ";
								} 
							$ij++;
							
							$chk_detail=$ser->service_detail;
							if(!empty($ser->discount_price)){ 
								 $discount=get_discount_percent($ser->price,$ser->discount_price);
							    	if(!empty($discount)){
										$discntPrice='<span class="colorcyan fontfamily-regular font-size-14">
                    spare bis zu '.$discount.' %</span>'; 
										} 
								}
								
								if(!empty($ser->price)) $price=$ser->price; else $price= $ser->price;
								
								if(empty($startFrom)) $startFrom=$price;
								
						       if($startFrom>$price) $startFrom=$price;
                               
						       $duration[]=$ser->duration;
							
							$multiPlecat=$multiPlecat.'<div class="d-flex py-2 pl-3 bookingSelect"
               data-id="'.$sid.'" id="'.$ser->id.'" value="'.$price.'">
											  <div class="deatail-box-left">
												<p class="color666 font-size-16 fontfamily-medium mb-0">'
                        .$ser->name.'</p>
												<span class="font-size-14 color999 fontfamily-regular">'
                        .$ser->duration.' Min.</span>
											  </div>
											  <div class="deatail-box-right d-inline-flex pl-20">
												<div class="relative text-right width160">
												  <p class="fontfamily-medium color333 font-size-14 mb-0">'
                          .($ser->price_start_option=="ab"?$ser->price_start_option:'').
                          ' '.price_formate($price).' €</p>
												  '.$discntPrice.'
												</div>
												<button data-id="'.$sid.'" class=" btn '.$slectClass.' 
                        widthfit2 btn-ml-20 class-'.$ser->id.'" id="'.$ser->id.'" 
                        value="'.$price.'" data-cid="'.$ser->category_id.'"
                         data-fid="'.$ser->filtercat_id.'">'.$this->lang->line('Select').'</button>
											  </div>
											</div>';
						 } } 
				 if(!empty($multiPlecat)){
							 
							 $min = min($duration);
                             $max = max($duration); 
                             $cherv = check_review(implode(",",$allid));
                             
                             if(!empty($chk_detail) || !empty($cherv)){ ?>
                             <a data-id="<?php echo $sub_ids;//$v[0]->id; ?>" data-allid="<?php echo implode(',',$allid); ?>" href="javascript:void(0)" class="colorcyan a_hover_cyan <?php echo (($min==$max)?'first_pop':'second_pop'); ?> salondetail_popup"><?php echo $this->lang->line('Show-Details'); ?></a>
                             <?php } ?>
								<div class="border-b px-3"> 
								  <div class="accordion" id="right-side-box-accordian4">
									<div class="accordion-group">
									  <div class="accordion-heading p-3 relative">
										<a class="accordion-toggle color333 fontfamily-medium font-size-16 a_hover_333 d-flex collapsed" data-toggle="collapse" data-parent="#right-side-box-accordian4" href="#collapseOne42sec<?php echo $i; ?>">
										  <p class="relative vertical-top deatail-box-left">
														<?php echo $k; ?>
										  <span class="font-size-14 color999 fontfamily-regular display-b"> <?php if($min==$max){ echo $min." Min."; } else { echo $min." Min. - ".$max ." Min."; } ?></span>
										  </p>
										  <span class="fontfamily-medium color333 font-size-14 mb-0" style="float:right;margin-right: 27px;"><?php echo $ss ? 'ab ' : ''; ?> <small class="font-size-18 fontfamily-semibold"><?php echo price_formate($startFrom)." €"; ?></small></span>
										</a>
										
									  </div>
									  <div id="collapseOne42sec<?php echo $i; ?>" class="accordion-body collapse">
										<div class="accordion-inner">
											<?php 
										        echo $multiPlecat;
                                              ?>
										 
										</div>
									  </div>
									</div>
								  </div>
								</div>
								
				<?php } $i++; $jj++;  }  } } ?>
                
               
              </div>
            	<div id="m_review"></div>
            </div>

          </div>

          <?php 
          
           $add ="";
           $uid = $this->session->userdata('st_userid');		 
		 if(!empty($uid)){
				   $add.=",IFNULL((SELECT count(id) FROM st_cart WHERE subcat_id =`st_merchant_category`.`subcategory_id` AND user_id=".$uid."),'0') as total_in_cart";
			     }
          
          $field3='st_merchant_category.id,st_category.category_name,,st_merchant_category.created_by,st_category.filter_category,st_merchant_category.subcategory_id as subid,st_merchant_category.category_id as mainid,name,count(*) as count,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE subcat_id =`st_merchant_category`.`subcategory_id` AND created_by="'.$sid.'" AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND online_booking=1 AND status="active")>=1),0) as checkemp,IFNULL((SELECT category_name FROM st_filter_category WHERE id =`st_category`.`filter_category`),"") as filter_category_name'.$add.'';
          if(!empty($main_category)){ $tab=2;
                  foreach($main_category as $cat){ ?>
            <div class="display-n mainCatTabs" id="tab-box<?php echo $tab; ?>">
              <h3 class="font-size-24 color333 fontfamily-medium mb-25"><?php echo $cat->category_name; ?></h3>
              <div class="row">
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 pr-5">            
                  <!-- <p class="color999 fontfamily-medium font-size-20 text-lines-overflow-1" style="-webkit-line-clamp:1;">Matching Search Result</p> -->
                     <?php 
                     $mid=$cat->id;
                                   $this->db->having('checkemp > 0');
                     $allmainSub=  $this->user->join_two('st_merchant_category','st_category','subcategory_id','id',array('category_id' =>$cat->mainid,'st_merchant_category.created_by' => $cat->created_by,'st_merchant_category.status' =>'active','st_merchant_category.online'=>1),$field3,'subcategory_id');
                    // echo '<pre>'; print_r($allmainSub); //die;
                     
                    if(!empty($allmainSub)){
				
						 $filcatarr = array();
						 $filterCat = array();
						 $filtAllSubCat = array();
						 $AllSubCat = array();
						 $AllSubCat1 = array();
						 
						 foreach($allmainSub as $cat){
							 
							 $filtAllSubCat[$cat->filter_category][] =$cat->subid;
							 
							 if(!in_array($cat->filter_category,$filterCat))
							   {
								 $filcatarr[] = $cat;
								 $filterCat[] = $cat->filter_category;
							   }
							 }
							$allSubfilter_category =$filcatarr; 
						
						 $mi=0;
						 
                      foreach($allSubfilter_category as $cats){
						 if($mi==0){
					         $AllSubCat1 = $all_subcats[$cats->filter_category];	
					           
				           }
				       
						  $AllSubCat = $filtAllSubCat[$cats->filter_category];
						   ?>
                    <a href="javascript:void(0);" class="getServiceOther" id="<?php echo $cats->created_by; ?>" main-id="<?php echo $mid; ?>" data-fid="<?php echo $cats->filter_category; ?>" data-id="<?php echo implode(',',$AllSubCat); ?>">
                      <div id="divs_<?php echo $cats->filter_category; ?>" class="<?php echo ($mi >0)?'bgwhite':'bggreengradient'; ?> pt-15 pb-15 pl-25 mt-3 border-radius4">
                      <span id="spans_<?php echo $cats->filter_category; ?>" class="<?php echo ($mi >0)?'color999':'colorwhite'; ?> fontfamily-medium font-size-16 display-b"><?php echo $cats->filter_category_name; ?><img src="<?php echo base_url('assets/frontend/images/round-orange-icon.png.png') ?>" alt="" id="checked_fcat_<?php echo $cat->filter_category; ?>" style="float: right;height: 22px;margin-right: 5px;" class="round-orange-icon mancat_check_icon checked_fcatc_<?php echo $cat->filter_category; ?> <?php if(empty($cats->total_in_cart)) echo 'display-n' ?>"/></span>
                      </div></a>
                  <?php $mi++; } ?>
                    <input type="hidden" id="activeAllresult_<?php echo $mid; ?>" value="<?php echo $allSubfilter_category[0]->filter_category; ?>" name="">
                  
                    <?php } ?>



                </div>
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" id="ChgDivData_<?php echo $mid; ?>"> 

              <?php 
                
                               $this->db->where_in('subcategory_id',$AllSubCat1);  
                                $this->db->having('checkemp > 0');              
                $allservices=  $this->user->join_two('st_merchant_category','st_category','subcategory_id','id',array('st_merchant_category.created_by' =>$cat->created_by,'st_merchant_category.status' =>'active','st_merchant_category.online'=>1),'st_merchant_category.id,st_merchant_category.category_id,st_merchant_category.filtercat_id,st_merchant_category.service_detail,name,duration,category_name,price,discount_price,price_start_option,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`st_merchant_category`.`id` AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND online_booking=1 AND status="active")>=1),0) as checkemp');
               // echo $this->db->last_query();
                
                if(!empty($allservices)){
               $services_by_subcategory=array();
						  foreach($allservices as $service)$services_by_subcategory[$service->category_name ][] = $service;
						$kk=0; 
						$sub_ids=0; 
                       foreach($services_by_subcategory as $k=>$v){
                       	$multiPlecat="";
					   $startFrom="";
					   
					   $abd="";
					   $ij=1;
					   
					   $allid=array();
					   
					   $duration=array();
             $s2=0;$ss2=0;
					foreach($v as $ser){
            if ($ij == 1) {
              $s2 = $ser->price; $ss2 = 0;
            }
						if ($s2 != $ser->price) $ss2 = 1;
            else if ($s2 == $ser->price && $ser->price_start_option == 'ab')  $ss2 = 1;
            if ($s2 > $ser->price) $s2 = $ser->price;

						if(empty($ser->name)){ 
							
							$cherv = check_review($ser->id);
							
							if(!empty($ser->service_detail) || !empty($cherv)){ ?>
						<a data-id="<?php echo $ser->id; ?>" data-allid="<?php echo $ser->id; ?>"; href="javascript:void(0)" class="colorcyan a_hover_cyan first_pop salondetail_popup"><?php echo $this->lang->line('Show-Details'); ?></a>
						<?php } ?>
						<div class="border-b d-flex py-3 pl-3 bookingSelect" data-id="<?php echo $sid; ?>" id="<?php echo $ser->id; ?>" value="<?php if(!empty($ser->discount_price)) echo $ser->discount_price; else echo $ser->price; ?>"> 
						  <div class="deatail-box-left">
							<p class="color333 font-size-16 fontfamily-medium mb-0"><?php echo $k; ?></p>
							<span class="font-size-14 color666 fontfamily-regular"><?php echo $ser->duration ?> Min.</span>
						  </div>
						  <div class="deatail-box-right d-inline-flex">
							<div class="relative text-right width200">
							  <p class="fontfamily-medium color333 font-size-14 mb-0"><?php  if(!empty($ser->discount_price)) echo "<small class='font-size-18 fontfamily-semibold'>".$ser->discount_price." €</small>"; else echo "<small class='font-size-18 fontfamily-semibold'>".($ser->price_start_option=="ab"?$ser->price_start_option:'')." ".$ser->price." €</small>"; ?> </p>
							<?php if(!empty($ser->discount_price)){ $discount=get_discount_percent($ser->price,$ser->discount_price);
							    	if(!empty($discount)){  ?><span class="colorcyan fontfamily-regular font-size-14"><?php echo $this->lang->line('save-up-to'); ?> <?php echo $discount; ?> %</span> <?php } 
								} ?>
							</div>
							<button data-id="<?php echo $sid; ?>" class=" btn <?php echo (in_array($ser->id,$cartValue))?'selectedBtn':'btn-border-orange'; ?> btn-small widthfit2 btn-ml-20 class-<?php echo $ser->id; ?>" id="<?php echo $ser->id; ?>" value="<?php if(!empty($ser->discount_price)) echo $ser->discount_price; else echo $ser->price; ?>" data-cid="<?php echo $ser->category_id; ?>" data-fid="<?php echo $ser->filtercat_id; ?>"><?php echo $this->lang->line('Select'); ?></button>
						  </div>
						</div>
						<?php } else {
							if(in_array($ser->id,$cartValue)) $slectClass='selectedBtn'; else $slectClass='btn-border-orange';
							
						$discntPrice="";
							$sub_ids=$ser->id;
							
							$allid[]=$ser->id;
							
							//$cherv = check_review($ser->id);
							 if($ser->price_start_option=='ab' || $ij==2){
								 $abd="ab ";
								} 
							  
							  $ij++;
							
							
							
							$chk_detail=$ser->service_detail;
							if(!empty($ser->discount_price)){ 
								 $discount=get_discount_percent($ser->price,$ser->discount_price);
							    	if(!empty($discount)){
										$discntPrice='<span class="colorcyan fontfamily-regular font-size-14">save '.$discount.' %</span>'; 
										} 
								}
								
								if(!empty($ser->discount_price)) $price=$ser->discount_price; else $price= $ser->price;
								
								if(empty($startFrom)) $startFrom=$price;
								
						       if($startFrom>$price) $startFrom=$price;
						       
							   $duration[]=$ser->duration;
							   
							$multiPlecat=$multiPlecat.'<div class="d-flex py-2 pl-3 bookingSelect" data-id="'.$sid.'" id="'.$ser->id.'" value="'.$price.'">
											  <div class="deatail-box-left">
												<p class="color666 font-size-16 fontfamily-medium mb-0">'.$ser->name.'</p>
												<span class="font-size-14 color999 fontfamily-regular">'.$ser->duration.' Min.</span>
											  </div>
											  <div class="deatail-box-right d-inline-flex pl-20">
												<div class="relative text-right width75">
												  <p class="fontfamily-medium color333 font-size-14 mb-0 mt-2">'.($ser->price_start_option=="ab"?$ser->price_start_option:'').' '.$price.' €</p>
												  '.$discntPrice.'
												</div>
												<button data-id="'.$sid.'" class=" btn '.$slectClass.' widthfit2 btn-ml-20 class-'.$ser->id.'" id="'.$ser->id.'" value="'.$price.'" data-cid="'.$ser->category_id.'" data-fid="'.$ser->filtercat_id.'">'.$this->lang->line('Select').'</button>
											  </div>
											</div>';
						 } } 
						 if(!empty($multiPlecat)){
							 $min = min($duration);
                             $max = max($duration); 
                             $cherv = check_review(implode(",",$allid));
                             if(!empty($chk_detail) || !empty($cherv)){ ?>
                               <a data-id="<?php echo $sub_ids;//$v[0]->id; ?>" data-allid="<?php echo implode(',',$allid); ?>" href="javascript:void(0)" class="colorcyan a_hover_cyan <?php echo (($min==$max)?'first_pop':'second_pop'); ?> salondetail_popup"><?php echo $this->lang->line('Show-Details'); ?></a>
                               <?php } ?>
								<div class="border-b px-3"> 
								  <div class="accordion" id="right-side-box-accordian4">
									<div class="accordion-group">
									  <div class="accordion-heading p-3 relative">
										<a class="accordion-toggle color333 fontfamily-medium font-size-16 a_hover_333 d-flex collapsed" data-toggle="collapse" data-parent="#right-side-box-accordian4" href="#collapseOne42third<?php echo $i; ?>">
										 <p class="relative vertical-top deatail-box-left">
														<?php echo $k; ?>
										  <span class="font-size-14 color999 fontfamily-regular display-b"> <?php if($min==$max){ echo $min." Min."; } else { echo $min." Min. - ".$max ." Min."; } ?></span>
										  </p>
										  <span class="fontfamily-medium color333 font-size-14 mb-0" style="float:right;margin-right: 27px;"><?php echo $ss2?'ab ':''; ?><small class="font-size-18 fontfamily-semibold"><?php echo $startFrom." €"; ?></small></span>
										</a>
										
									  </div>
									  <div id="collapseOne42third<?php echo $i; ?>" class="accordion-body collapse">
										<div class="accordion-inner">
											<?php 
										        echo $multiPlecat;
                                              ?>
										 
										</div>
									  </div>
									</div>
								  </div>
								</div>
								
				<?php } $i++; $kk++; }  } 
                    
                  ?>
                
                </div>
              </div>
          </div>
          <?php  $tab++; }
          } ?>

        </div>
      </div>

    </section>

    <section class="sevice_provider_section0 clear bgwhite pb-5">
      <div class="container pt-30 review_scroll" id="gallery-wrapper">
        <?php if (!$gbanerdata) $gbanerdata = []; ?>
        <?php if ($gbanerdata) { ?>
          <h3 class="font-size-24 color333 fontfamily-medium mb-25">
            Arbeiten von <?php echo $userdetail[0]->business_name; ?>
          </h3>
        <?php }?>
        <div id="gallery" class="gal-row1">
        <?php foreach ($gbanerdata as $banerdata) { ?>
          <div style="padding: 8px;">
            <div
              class="gimg"
              data-url="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/','crop_'.$banerdata->image); ?>"
              data-url1="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/','crop_'.$banerdata->image,'webp'); ?>"
              data-id="<?php echo $banerdata->id;?>"
            >
              <picture class="upl-gallary-image">
                <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/','crop_'.$banerdata->image,'webp'); ?>" type="image/webp" class="upl-gallary">
                <source srcset="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/','crop_'.$banerdata->image,'webp'); ?>" type="image/webp" class="upl-gallary">
                <img
                  style="width: 100%; height: 100%; max-height: 300px;padding: 3px; object-fit: cover;"
                  src="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/','crop_'.$banerdata->image); ?>"
                  data-image="<?php echo getimge_url('assets/uploads/banners/'.$banerdata->merchant_id.'/','crop_'.$banerdata->image); ?>"
                  alt=""
                />
              </picture>
            </div>
          </div>
        <?php } ?>
        </div>
      </div>
    </section>

    <section class="sevice_provider_section3 clear">
     
      <div class="box-shadow2 bgwhite pb-30">
        <div class="container pt-30 review_scroll">
        <h3 class="color333 font-size-28 fontfamily-medium mb-20">
          <?php echo $this->lang->line('Salon-Reviews'); ?></h3>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 pr-5">                

                  <div class="display-ib vertical-middle">
                    <h1 class="colororange font-size-52 fontfamily-medium"><?php echo $echorate; ?></h1>
                  </div>
                  <div class="display-ib vertical-middle ml-3">

                      <div class="d-flex mb-1 mt-minus8">
                  	<?php
	                      for($i=1;$i < 6;$i++){
	                        if($i <= $echorate){
	                      ?><i class="fas fa-star starcolor mr-2 fontsize-16"></i>
	                      <?php }elseif($echorate<$i && $echorate>($i-1)){ ?>
						         <i class="fas fa-star color999 mr-2 fontsize-16"></i>
                                      <i class="fas fa-star-half colororange mr-2 fontsize-16" style="margin-left: -26px;" ></i>    
						        <?php } else{ ?>
	                        <i class="fas fa-star color999 mr-2 fontsize-16"></i>
	                      <?php }
	                      } ?>
	                     </div>

                       <!--  <i class="fas fa-star colororange mr-2 font-size-16"></i>
                        <i class="fas fa-star colororange mr-2 font-size-16"></i>
                        <i class="fas fa-star colororange mr-2 font-size-16"></i>
                        <i class="fas fa-star colore99999940 mr-2 font-size-16"></i>
                        <i class="fas fa-star colore99999940 mr-2 font-size-16"></i> -->
                      
                      <span class="color999 font-size-12 fontfamily-regular">(<?php echo $rcount.' '; echo (($rcount > 1)?"Bewertungen":"Rezension"); ?>)</span>
                  </div>

                  <div class="border-radius4 border-w p-3">
                    <h6 class="fontfamily-medium color999 font-size-16 mb-3"><?php echo $this->lang->line('Filter-after-treatment'); ?></h6>
                     <form method="post" action="" id="frmrate_short">
                      <div class="btn-group multi_sigle_select mb-20">
                         <button id="close_multi" data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"><?php echo $this->lang->line('All_reviews1'); ?></button>
                              <ul class="dropdown-menu mss_sl_btn_dm noclose">
                              	<li class="checkbox-image">
                                  <input type="checkbox" id="id_allreview" name="category[]" class="review_filter_all" value="">
                                  <label for="id_allreview"><?php echo $this->lang->line('All_reviews1'); ?></label>
                                </li>
                                <?php if(!empty($merchant_category)){
									
									$merchant_subcat =array();
									foreach($merchant_category as $row){
										 $merchant_subcat[$row->catname][]=$row->id;										
										}
									//print_r($merchant_subcat); die;
									
                                  foreach($merchant_subcat as $key=>$val){ ?>
                                <li class="checkbox-image">
								 <div> 
                                  <input type="checkbox" id="id_<?php echo $key; ?>" name="category[]" class="review_filter" value="<?php echo implode(',',$val); ?>">
                                  <label for="id_<?php echo $key; ?>"><?php echo $key; ?></label>
                              </div> 
                                 </li>
                                <?php  }
                                } ?>
                              </ul>
                      </div>

                    <h6 class="font-size-16 color999 fontfamily-medium mb-20"><?php echo $this->lang->line('Filter-by-stars'); ?></h6>  
                     <?php

                       $avgfive=$avgfour=$avgthree=$avgtwo=$avgone=0;
                       $total=$rcount;
                       $five=isset($totalcount->five)?$totalcount->five:0; 
                       $four=isset($totalcount->four)?$totalcount->four:0;
                       $three=isset($totalcount->three)?$totalcount->three:0;
                       $two=isset($totalcount->one)?$totalcount->two:0;
                       $one=isset($totalcount->one)?$totalcount->one:0;
                       if($total != 0){
                       $avgfive= ($five*100)/$total;
                       $avgfour= ($four*100)/$total;
                       $avgthree= ($three*100)/$total;
                       $avgtwo= ($two*100)/$total;
                       $avgone= ($one*100)/$total;
                        }

                      ?>
                    <div class="d-flex mb-20">
                      <div class="checkbox mt-0 mb-0">
                        <label class="font-size-14 pl0 color666">
                          <input type="checkbox" name="rating_point[]" class="ratingshort" value="5">
                          <span class="cr"><i class="cr-icon fa fa-check"></i></span>                          
                        </label>
                      </div>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i> 

                        <div class="display-ib" style="margin-top:-2px;">
                          <div class="test-slider-v">
                            <span class="underspan" style="width:<?php echo $avgfive; ?>%;"></span>
                          </div>
                          <span><?php echo $five; ?></span>
                        </div>
                    </div>

                    <div class="d-flex mb-20">
                      <div class="checkbox mt-0 mb-0">
                        <label class="font-size-14 pl0 color666">
                          <input type="checkbox" name="rating_point[]" class="ratingshort" value="4">
                          <span class="cr"><i class="cr-icon fa fa-check"></i></span>                          
                        </label>
                      </div>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940  mr-1 font-size-16"></i> 

                        <div class="display-ib" style="margin-top:-2px;">
                          <div class="test-slider-v">
                            <span class="underspan" style="width:<?php echo $avgfour; ?>%;"></span>
                          </div>
                          <span><?php echo $four; ?></span>
                        </div>
                    </div>

                    <div class="d-flex mb-20">
                      <div class="checkbox mt-0 mb-0">
                        <label class="font-size-14 pl0 color666">
                          <input type="checkbox" name="rating_point[]" class="ratingshort" value="3">
                          <span class="cr"><i class="cr-icon fa fa-check"></i></span>                          
                        </label>
                      </div>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940  mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940  mr-1 font-size-16"></i> 

                        <div class="display-ib" style="margin-top:-2px;">
                          <div class="test-slider-v">
                            <span class="underspan" style="width:<?php echo $avgthree; ?>%;"></span>
                          </div>
                          <span><?php echo $three; ?></span>
                        </div>
                    </div>

                    <div class="d-flex mb-20">
                      <div class="checkbox mt-0 mb-0">
                        <label class="font-size-14 pl0 color666">
                          <input type="checkbox" name="rating_point[]" class="ratingshort" value="2">
                          <span class="cr"><i class="cr-icon fa fa-check"></i></span>                          
                        </label>
                      </div>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940 mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940  mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940  mr-1 font-size-16"></i> 

                        <div class="display-ib" style="margin-top:-2px;">
                          <div class="test-slider-v">
                            <span class="underspan" style="width:<?php echo $avgtwo; ?>%;"></span>
                          </div>
                          <span><?php echo $two; ?></span>
                        </div>
                    </div>

                    <div class="d-flex mb-20">
                      <div class="checkbox mt-0 mb-0">
                        <label class="font-size-14 pl0 color666">
                          <input type="checkbox" name="rating_point[]" class="ratingshort" value="1">
                          <span class="cr"><i class="cr-icon fa fa-check"></i></span>                          
                        </label>
                      </div>
                        <i class="fas fa-star colororange mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940 mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940 mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940  mr-1 font-size-16"></i>
                        <i class="fas fa-star colore99999940  mr-1 font-size-16"></i> 

                        <div class="display-ib" style="margin-top:-2px;">
                          <div class="test-slider-v">
                            <span class="underspan" style="width:<?php echo $avgone; ?>%;"></span>
                          </div>
                          <span><?php echo $one; ?></span>
                        </div>
                         <input type="hidden" id="loadmore_id" name="load_more" value="1">
                         <input type="hidden" name="merchant_id" value="<?php echo $sid; ?>">
                    </div>
                  </form>
                  </div>

                  <div class="border-radius4 border-w pt-2 pl-10 pr-10 pb-2 d-flex mt-20">
                    <div class="display-ib vertical-middle">
                    <picture class="width24v mt-3 mr-10">
                     
                      <source srcset="<?php echo base_url('assets/frontend/checked_284_29.png') ?>" type="image/png" class="width24v">
                      <img src="<?php echo base_url('assets/frontend/checked_284_29.png') ?>" class="width24v">
                    </picture>
                    </div>
                    <div class="display-ib">
                      <p class="font-size-14 color999 fontfamily-medium mb-1"><?php echo $this->lang->line('Verified-Reviews'); ?></p>
                      <p class="font-size-12 color666 fontfamily-regular mb-0"><?php echo $this->lang->line('Written-customers'); ?></p>
                    </div>
                  </div>


            </div>
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8"> 
            <div id="div_review">
            <?php if(!empty($review)){ 
              foreach($review as $rev){ ?>
              <div class="relative border-b  pb-3 pt-20 ">
                <div class="clear mb-3">
                	<?php for ($i=0; $i < 5; $i++){ 
                    if($i < $rev->rate)  
                      echo"<i class='fas fa-star colororange mr-1'></i>";
                    else
                      echo "<i class='far fa-star colororange mr-1'></i>";
                    } ?>

                  <span class="color999 font-size-14 fontfamily-regular float-right mr-3"><?php  $timestamp = strtotime($rev->created_on); 
                            echo time_passed($timestamp);
                        ?></span>
                </div>
                <p class="color666 fontfamily-regular font-size-14 " style="-webkit-line-clamp:2;"> <!-- text-lines-overflow-1 -->
                 <?php echo nl2br($rev->review); ?>
                </p>
                <p> <?php if($rev->anonymous == 1)
               		 echo "<span class='font-size-14 fontfamily-medium color333 mb-10'>".$this->lang->line('Anonymous')."</span>";
               		else
               			echo "<span class='font-size-14 fontfamily-medium color333 mb-10 a_hover_333'>".$rev->first_name."</span>";
               		?><span class="font-size-14 color999 fontfamily-regular"> - <?php echo $this->lang->line('Treated-by'); ?> <?php echo $rev->fname; ?></span></p>
                	<?php echo $this->lang->line('Services_Booked'); ?> : <?php echo get_servicename_with_sapce($rev->booking_id); ?>
                 
                <?php $m_reply=getselect_row('st_review','id,review',array('review_id' =>$rev->id,'created_by' => $rev->merchant_id)); 
                      if(!empty($m_reply)){ ?>
                <div class="accordion pb-2" id="">
                <div class="accordion-group">
                  <div class="accordion-heading p-3 relative">
                    <a class="color333 fontfamily-medium font-size-14 a_hover_333 display-ib relative">
                    Kommentar von <?php echo isset($userdetail[0]->business_name)?$userdetail[0]->business_name:''; ?>
                    </a>
                  </div>
                  <div id="collapsev_<?php echo $rev->id; ?>" class="accordion-body">
                    <div class="accordion-inner">
                    	<div class="d-flex py-3 px-3">
                          <p class="fontfamily-regular color666 font-size-14 mb-0"><?php echo $m_reply->review; ?></p>
                        </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              	<?php } ?>
            </div>
            <?php } 
               if($total > 5){ ?>
              <div id="load-1"><a href="javascript:void(0)" data-id="1" class="colorcyan a_hover_cyan font-size-18 fontfamily-medium text-underline mt-30 display-b loadMoreReview"><?php echo $this->lang->line('Load_More'); ?></a></div>
              <?php } 
          	} else{ ?>
          		<div class="text-center" style="padding-bottom: 45px;">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;">Bisher wurden keine Bewertungen abgegeben.</p></div>
          		<?php  } ?>
          	  </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="sevice_provider_section3 bgwhite clear nsec4" style="display: none;">
      <div class="box-shadow2 ">
        <div class="container">
        	<div class="relative bglightgreen1 pt-30 pb-30">
          <div class="row">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"> 
               <h3 class="color333 font-size-24 fontfamily-medium"><?php echo $this->lang->line('Salon-Reviews'); ?></h3>
            </div>
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8"> 
            <div id="allreview">
            <?php 

              if(!empty($review)){ 
              foreach($review as $rev){ ?>
           
              <div class="relative border-b">
                <div class="clear mb-3">
                
                <?php for ($i=0; $i < 5; $i++){ 
                    if($i < $rev->rate)  
                      echo"<i class='fas fa-star starcolor mr-2'></i>";
                    else
                      echo "<i class='fas fa-star color999 mr-2'></i>";
                    } ?>
                  <span class="color999 font-size-14 fontfamily-regular float-right">
                     <?php  $timestamp = strtotime($rev->created_on); 
                      echo time_passed($timestamp);
                     ?>

                    <!-- 2 days ago --></span>

                </div>
                <p class="color666 fontfamily-regular font-size-14" style="-webkit-line-clamp:2;">
                  <?php echo nl2br($rev->review); ?>
                </p>
                <p class="font-size-16 fontfamily-medium color333 mb-10"><?php echo $rev->userfn; ?></p>
              </div>

              <?php }
                }else{ ?>
              <div class="relative border-b pt-20">

                <p class="color666 fontfamily-regular font-size-14 text-lines-overflow-1 text-center" style="-webkit-line-clamp:2;">
                  Sorry! no review found...!
                </p>
              </div> 
              <?php } if($rcount > 5){ ?>
              <div id="load-1"><a href="javascript:void(0)" id="<?php echo url_encode($sid); ?>" data-id="1"  class="colorcyan a_hover_cyan font-size-18 fontfamily-medium text-underline mt-30 display-b loadMore">weitere Bewertungen laden</a></div>
              <?php } ?>
             </div>
              

            </div>
          </div>
      </div>
        </div>
      </div>
    </section>
    <?php 
    if(isset($_GET['servicids'])){
    	$dates=$times='';
    	if(isset($_GET['date']) && $_GET['date']!='Beliebig')
    		$dates='&date='.$_GET['date'];
    	if(isset($_GET['time']))
    		$times='&time='.$_GET['time'];

      $urll=url_encode($userdetail[0]->id).'?servicids='.$_GET['servicids'].$dates.$times;
    }
    else
      $urll=url_encode($userdetail[0]->id); ?>


    <input type="hidden" id="select_url" name="" value="<?php echo $urll;?>">
    <section class="sevice_provider_section4 bgwhite clear pt-35 pb-50 mabout_sec">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <h3 class="fontfamily-medium color333 font-size-24 mb-30">
            <?php echo $this->lang->line('Salon-Map'); ?>
            </h3>
         <!-- <div id='map' style="height: 500px;"></div> -->
            <!-- <div id="map" style="height: 500px;"></div> -->
            <!-- <a href="https://us1.locationiq.com/v1/reverse.php?key='5eaae41f648ada'&lat=<?php echo $userdetail[0]->latitude; ?>&lon=<?php echo $userdetail[0]->longitude; ?>&format=json" >
               
            </a> -->
             <a href="https://maps.google.com?q=
             <?php echo $userdetail[0]->address.'
              '.$userdetail[0]->city.','.$userdetail[0]->country; ?>"
               target="_blank">
               <div id='map' style="height: 500px;"></div>
            </div>
          </a>
            <!-- <a href="#" class="image-map-link d-block" id="image-map-link">
                        
                       <div class="image-label fontfamily-medium" id="showOnMap" data-ids=""><?php echo $this->lang->line('Show-salons-on-map'); ?></div> 
                       <input type="hidden" name="uids" id="showOnMapUids">
                     </a> -->
          </div>
        </div>
         <?php 
         
          $days_array=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday', 'Sunday');
         
         $i=0;
         
          $dayName=date("l", strtotime(date('H:i:s')));
          //echo $dayName;
         if(!empty($payment_method)){
          ?>
          <div class="border-t border-b pt-3 pb-3 container">
            <div class="row ">
              <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                <p class="font-size-16 color333 fontfamily-semibold mb-0">Zahlungsmöglichkeiten</p>
              </div>
              <div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-9">
                <?php foreach($payment_method as $row){ ?>
                <p class="font-size-14 fontfamily-medium color333 mb-0"><?php echo $row->method_name; ?></p>
                <?php } ?>

              </div>
            </div>
          </div>
          
          
        <?php } 
        
        if(!empty($userdetail[0]->web_link) || !empty($userdetail[0]->fb_link) || !empty($userdetail[0]->insta_link)){ ?>
        
        <div class="border-b pt-3 pb-3 container" >
            <div class="row ">
              <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                <p class="font-size-16 color333 fontfamily-semibold mb-0">Links</p>
              </div>
              <div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-9 grayys">
                <?php if(!empty($userdetail[0]->web_link)){
				     
				       $url = parse_url($userdetail[0]->web_link);
					
					 ?>
					<a href="<?php if(!empty($url['scheme'])) echo $userdetail[0]->web_link; else echo 'https://'.$userdetail[0]->web_link ?>" target="_blank" class="font-size-14 fontfamily-medium color333 d-block mb-2"><img src="<?php echo base_url('assets/frontend/'); ?>images/internet-link-icon.svg" class="link-icon-20 mr-2" alt=""> <?php echo $userdetail[0]->web_link; ?></a>
				<?php } 
				
				if(!empty($userdetail[0]->fb_link)){ $url2 = parse_url($userdetail[0]->fb_link); ?>
						
                <a href="<?php if(!empty($url2['scheme'])) echo $userdetail[0]->fb_link; else echo 'https://'.$userdetail[0]->fb_link ?>" target="_blank" class="font-size-14 fontfamily-medium color333 d-block mb-2"><img src="<?php echo base_url('assets/frontend/'); ?>images/facebook-link-icon.svg" class="link-icon-20 mr-2" alt=""> <?php echo $userdetail[0]->fb_link; ?></a>
                
                <?php } 
               
               	if(!empty($userdetail[0]->insta_link)){ $url3 = parse_url($userdetail[0]->web_link); ?>
					
                <a href="<?php if(!empty($url3['scheme'])) echo $userdetail[0]->insta_link; else echo 'https://'.$userdetail[0]->insta_link ?>" target="_blank" class="font-size-14 fontfamily-medium color333 d-block mb-0"><img src="<?php echo base_url('assets/frontend/'); ?>images/instagram-link-icon.svg" class="link-icon-20 mr-2" alt=""> <?php echo $userdetail[0]->insta_link; ?></a>
                
                <?php } ?>
              </div>
            </div>
          </div>
          
        <?php } ?>
        <div class="container">
        <div class="row mt-4">
          <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
            <?php foreach($user_available as $row){ ?>
                <div class="d-flex border-w border-radius4 <?php echo ($row->starttime!='')?'bgwhite':'bge8e8e8'; ?> pt-10 pb-10 px-3 <?php echo ($i > 0)?'mt-10':''; if(!empty($days_array[$i]) && $dayName==$days_array[$i]) echo " bg_change"; ?>">
                <span class="<?php echo ($row->starttime !='')?'color333':'colorwhite';?> font-size-14 fontfamily-medium"><?php echo $this->lang->line($days_array[$i]); ?></span>
                <span class="<?php echo ($row->starttime !='')?'color999':'colorwhite';?> font-size-14 fontfamily-regular ml-auto">
                  <?php if($row->starttime !=''){ 
                    echo date('H:i', strtotime($row->starttime)).' - '.date('H:i', strtotime($row->endtime)); }
                  else{
                    echo "Geschlossen";
                  } ?></span>
                </div>
              <?php $i++; 
            } ?>
            <!-- <div class="d-flex border-w border-radius4 bgwhite pt-10 pb-10 px-3">
              <span class="color333 font-size-14 fontfamily-medium">Monday</span>
              <span class="color999 font-size-14 fontfamily-regular ml-auto">10:00 - 20:00</span>
            </div>
            <div class="d-flex border-w border-radius4 bgwhite pt-10 pb-10 px-3 mt-10">
              <span class="color333 font-size-14 fontfamily-medium">Tuesday</span>
              <span class="color999 font-size-14 fontfamily-regular ml-auto">10:00 - 20:00</span>
            </div>
            <div class="d-flex border-w border-radius4 bgwhite pt-10 pb-10 px-3 mt-10">
              <span class="color333 font-size-14 fontfamily-medium">Wednesday</span>
              <span class="color999 font-size-14 fontfamily-regular ml-auto">10:00 - 20:00</span>
            </div>
            <div class="d-flex border-w border-radius4 bgwhite pt-10 pb-10 px-3 mt-10">
              <span class="color333 font-size-14 fontfamily-medium">Thursday</span>
              <span class="color999 font-size-14 fontfamily-regular ml-auto">10:00 - 20:00</span>
            </div>
            <div class="d-flex border-w border-radius4 bgwhite pt-10 pb-10 px-3 mt-10">
              <span class="color333 font-size-14 fontfamily-medium">Friday</span>
              <span class="color999 font-size-14 fontfamily-regular ml-auto">10:00 - 20:00</span>
            </div>
            <div class="d-flex border-w border-radius4 bgwhite pt-10 pb-10 px-3 mt-10">
              <span class="color333 font-size-14 fontfamily-medium">Saturday</span>
              <span class="color999 font-size-14 fontfamily-regular ml-auto">10:00 - 20:00</span>
            </div>
            <div class="d-flex bge8e8e8 border-radius4 pt-10 pb-10 px-3 mt-10">
              <span class="colorwhite font-size-14 fontfamily-medium">Sunday</span>
              <span class="colorwhite font-size-14 fontfamily-regular ml-auto">Closed</span>
            </div> -->
          </div>
          <div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-9">
            <h6 class="font-size-16 color333 fontfamily-semibold mb-20"><?php echo $this->lang->line('About'); ?></h6>
            <!-- <p class="color666 font-size-14 fontfamily-regular text-lines-overflow-1" style="-webkit-line-clamp:2;">Ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
            <p class="color666 font-size-14 fontfamily-regular text-lines-overflow-1" style="-webkit-line-clamp:4;">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. t, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.  </p> -->
            <p class="color666 font-size-14 fontfamily-regular" style="-webkit-line-clamp:6;"><?php echo $userdetail[0]->about_salon; ?></p>
          </div>
        </div>
      </div>
          </div>
    </section>
    <!--start select box -->
    <?php
        if(!empty($my_cart->service)){
           $cart_div='';
           
            if(!empty($my_cart->price_start_option) && $my_cart->price_start_option=='ab'){
				$pricerr='ab '.price_formate(number_format($my_cart->tot_price,2));
				}
			else{
				$pricerr=price_formate(number_format($my_cart->tot_price,2));
				} 
	      }
        else{
          $cart_div='none';
          $pricerr="0";
	     }
          
         $param=$_SERVER['QUERY_STRING'];
         
       
         
         //echo $param;
     ?>

    <div id="my_cart_div" class="Select-box m-auto" style="display: <?php echo $cart_div; ?>">
      <div class="row">
        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
          <p class="fontfamily-medium colorwhite font-size-16 mb-2 mt-1">
            <span id="service_count"><?php echo $my_cart->service; ?></span>
            <span id="service_text">Service</span> <?php echo $this->lang->line('Services-Added'); ?>
          </p>
          <span class="colorcyan font-size-20 fontfamily-medium"><span id="total_amt"><?php echo $pricerr; ?></span> €</span>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-right">
          <a href="<?php echo base_url('booking/booking_detail/'.$service_id.'?'.$param); ?>"><button class="btn widthfit2 mt-1"><?php echo $this->lang->line('Book-now1'); ?></button></a>
        </div>
      </div>
    </div>
<!--end select box -->  

     <!-- modal start -->
   <!--start modal -->
<div class="modal fade" id="service-loging-check">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
    <div class="modal-content text-center">      
      <a href="#" class="crose-btn" data-dismiss="modal">
      <picture class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg'); ?>" type="image/svg" class="popup-crose-black-icon">
        <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg'); ?>" class="popup-crose-black-icon">
      </picture>
      </a>
      <div class="modal-body pt-30 pb-30 pl-40 pr-40 bglightgreen1">
      <picture class="">
        <source srcset="<?php echo base_url('assets/frontend/images/popup-registeri-user-icon.webp') ?>" type="image/webp">
        <source srcset="<?php echo base_url('assets/frontend/images/popup-registeri-user-icon.svg'); ?>" type="image/svg">
        <img src="<?php echo base_url('assets/frontend/images/popup-registeri-user-icon.svg'); ?>">
      </picture>
      <p id="addservice_message" class="font-size-18 color333 fontfamily-medium mt-3 mb-30 text-lines-overflow-1" style="-webkit-line-clamp: 4 !important;">
          <?php 
          if(!empty($this->session->userdata('st_userid'))){
			  if($this->session->userdata('access')=='employee'){
                  echo "As a employee you can not add the service in cart.";
			     }
			  else{
				  echo "Als Salon kannst du keine Services in den Warenkorb legen.";
				  } 
              
              }
          else{
              echo "Your need to register before continue booking or if already registered sign in with us.";
            }
            ?>
        </p>
        <?php  if(!empty($this->session->userdata('st_userid'))){ ?>
          <button type="button" class=" btn btn-large widthfit" data-dismiss="modal">Ok</button>
        <?php } else {  ?>
          <a href="#" class="openLoginPopup"><button type="button" class=" btn btn-large widthfit">Register / Login</button></a>
          <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- end modal --> 



   <!--Online booking model  -->
<div class="modal fade" id="online_booking_message">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
    <div class="modal-content text-center">      
      <a href="#" class="crose-btn" data-dismiss="modal">
      <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
      </a>
      <div class="modal-body pt-30 pb-30 pl-40 pr-40 bglightgreen1">
        <p id="addservice_message" class="font-size-18 color333 fontfamily-medium mt-3 mb-30 text-lines-overflow-1">
          <?php 
        
                  echo "This service not available for online booking.";
            ?>
        </p>
          <button type="button" class=" btn btn-large widthfit" data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>
<!-- end modal --> 

<div class="modal fade modal-fullscreen" id="gimg-detail">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="row" style="height: 50px;">
        <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
        </a>
      </div>
      <div class="row" style="flex-grow:1;">
        <div class="col-sm-12 col-md-6" style="justify-content: center;align-items: center;display: flex;">
          <picture class="upl-gallary-image">
              <source id="galsrc1">
              <source id="galsrc2">
              <img id="galimage">
          </picture>
        </div>
        <div class="col-sm-12 col-md-6 p-4" style="display: flex;flex-direction: column;justify-content: center;" id="galimagedetail">
          <h2> <b>Service</b> </h2> <br/>
          <h4> Frauen Diodenlaser - Gesicht </h4><br/><br/>
          <h2> <b>Der Stylist</b> </h2> <br/>
          <div>
            <label class="vertical-middle pt-2" style="height: 60px;">
              <img style="width:50px;height:50px;" class="employee-round-icon display-ib" src="http://localhost/styletimer/assets/uploads/employee/2576/profile_1663429020.png">
              <span style="font-size: 1.4rem">sfda asdf </span>
            </label>
            <div style="padding-left: 65px;">
              <span style="font-size: 1.5rem; color: #FF9944;">
                <i class='fas fa-star colororange mr-2 font-size-20'></i>
                4.9
              </span>
              <span style="font-size: 1.2rem; padding-left:10px;">
                (153 Bewertungen)
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <!--start modal -->
<div class="modal fade" id="service-show-detail">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
    <div class="modal-content p-3 pt-4">
      <a href="#" class="crose-btn" data-dismiss="modal">
      <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
      </a>
      <div class="modal-body mt-4 pt-2 mb-3 pl-20 pr-20 border-t" style="max-height: 400px;overflow: auto;" id="adddetail_message">

      <h3 id="service_name_fordetail" class="font-size-20 color333 fontfamily-medium mt-10 text-left"></h3>
      <!--   <img src="<?php echo base_url('assets/frontend/images/popup-registeri-user-icon.webp') ?>"> -->
        
        <!-- <p id="adddetail_message" class="font-size-14 color333 fontfamily-medium mt-0 mb-3 text-left">
        </p> -->
        <div class="relative ">
          <p class="color666 font-size-14"><?php echo $this->lang->line('Service-Rating'); ?></p>
          <div class="d-flex ">
            <div class="lineheight40 mr-2">
              <span class="starcolor fontfamily-regular fontsize-48">4.9</span>
            </div>
            <div class="">
              <span>
                <i class="fas fa-star starcolor mr-2 fontsize-22"></i>
                <i class="fas fa-star starcolor mr-2 fontsize-22"></i>
                <i class="fas fa-star starcolor mr-2 fontsize-22"></i>
                <i class="fas fa-star starcolor mr-2 fontsize-22"></i>
                <i class="fas fa-star starcolor mr-2 fontsize-22"></i>
                <!-- <i class="fas fa-star color999 mr-2 fontsize-22"></i> -->
              </span>
              <p class="m-0 color666 font-size-14">163 reviews</p>
            </div>
          </div>
          <a href="#service_reting" class="fontsize-14 colorred fontfamily-regular a_hover_pink" id="onscrollreview">Show 163 service reviews...</a>
          <p class="font-size-16 color666 fontfamily-medium mt-3 mb-3 text-left">ABOUT THIS SERVICE</p>
          <p id="adddetail_message" class="font-size-14 color333 fontfamily-medium mt-0 mb-3 text-left">
          </p>
          <div id="service_reting">
            <p class="font-size-16 color666 fontfamily-medium mt-3 mb-1 text-left ">SERVICE REVIEWS</p>
            <div class="service-review-box border-b pb-3 pt-3">
              <span class="display-b mb-2">
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <!-- <i class="fas fa-star color999 mr-2 fontsize-22"></i> -->
              </span>
              <p class="font-size-14 color333 mt-2">A very relaxing space and environment. The massage was through and strong when needed, and ultimately hugely beneficial when I walked out. Recommended.</p>
              <p class="font-size-14 color999 mt-2 relative after-line-small-bottom">Treatment by Stephen Nock</p>

              <div class="d-flex justify-content-between ">
                <div class="relative">
                  <span class="font-size-14 color999"><?php echo $this->lang->line('Anonymous');?> <i class="far fa-check-circle colorgreen"></i> <span class="font-size-14 color999 display-ib ml-3 after-dot-small-left relative"> a month ago</span></span>
                </div>
                <a href="JavaScript:Void(0);" class="fontsize-14 color999 a_hover_666">Report</a>
              </div>
            </div>

            <div class="service-review-box border-b pb-3 pt-3">
              <span class="display-b mb-2">
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <!-- <i class="fas fa-star color999 mr-2 fontsize-22"></i> -->
              </span>
              <p class="font-size-14 color333 mt-2">Stephen's Thai Vedic Massage was marvellous. He has a healing touch. I would strongly recommend him.</p>
              <p class="font-size-14 color999 mt-2 relative after-line-small-bottom">Treatment by Stephen Nock</p>

              <div class="d-flex justify-content-between ">
                <div class="relative">
                  <span class="font-size-14 color999"><?php echo $this->lang->line('Anonymous');?> <i class="far fa-check-circle colorgreen"></i> <span class="font-size-14 color999 display-ib ml-3 after-dot-small-left relative"> 2 months ago</span></span>
                </div>
                <a href="JavaScript:Void(0);" class="fontsize-14 color999 a_hover_666">Report</a>
              </div>
            </div>

            <div class="service-review-box border-b pb-3 pt-3">
              <span class="display-b mb-2">
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <!-- <i class="fas fa-star color999 mr-2 fontsize-22"></i> -->
              </span>
              <p class="font-size-14 color333 mt-2">Excellent will go back</p>
              <p class="font-size-14 color999 mt-2 relative after-line-small-bottom">Treatment by Stephen Nock</p>

              <div class="d-flex justify-content-between ">
                <div class="relative">
                  <span class="font-size-14 color999"><?php echo $this->lang->line('Anonymous');?> <i class="far fa-check-circle colorgreen"></i> <span class="font-size-14 color999 display-ib ml-3 after-dot-small-left relative"> 2 months ago</span></span>
                </div>
                <a href="JavaScript:Void(0);" class="fontsize-14 color999 a_hover_666">Report</a>
              </div>
            </div>

            <div class="service-review-box border-b pb-3 pt-3">
              <span class="display-b mb-2">
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <i class="fas fa-star starcolor mr-1 fontsize-18"></i>
                <!-- <i class="fas fa-star color999 mr-2 fontsize-22"></i> -->
              </span>
              <p class="font-size-14 color333 mt-2">Stephen really knows what he's doing, and is a really nice guy as well. The setup is really welcoming, from the furniture to the lighting to the ability to choose the music.</p>
              <p class="font-size-14 color999 mt-2 relative after-line-small-bottom">Treatment by Stephen Nock</p>

              <div class="d-flex justify-content-between ">
                <div class="relative">
                  <span class="font-size-14 color999"><?php echo $this->lang->line('Anonymous');?> <i class="far fa-check-circle colorgreen"></i> <span class="font-size-14 color999 display-ib ml-3 after-dot-small-left relative"> 3 months ago</span></span>
                </div>
                <a href="JavaScript:Void(0);" class="fontsize-14 color999 a_hover_666">Report</a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- end modal --> 

<?php } ?>
    <?php $this->load->view('frontend/common/footer'); ?>
    
   <!-- <script type="text/javascript" src="https://leafletjs-cdn.s3.amazonaws.com/content/leaflet/master/leaflet.js"></script>
    <script type="text/javascript" src="https://tiles.unwiredmaps.com/js/leaflet-unwired.js"></script>
    <script src="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.5/leaflet-geocoder-locationiq.min.js"></script> -->

<script>

// function myMap() {
//   var lat_log = {lat: <?php echo $userdetail[0]->latitude; ?>, lng: <?php echo $userdetail[0]->longitude; ?>};
// var maps = new google.maps.Map(document.getElementById("map"),{zoom: 13, center: lat_log});
// var marker = new google.maps.Marker({position: lat_log, map: map, icon: base_url+'assets/frontend/images/location.svg'});

// }
	
    function checkGalleryWidth() {
      if ($("#gallery-wrapper").width() <= 172 * $("#gallery").children().length) {
        $("#gallery").removeClass('gal-row1');
        $("#gallery").addClass('gal-row2');
      } else {
        $("#gallery").removeClass('gal-row2');
        $("#gallery").addClass('gal-row1');
      }
    }
    $( window ).resize(function() {
      checkGalleryWidth();
    });

    $( document ).ready(function() {
      checkGalleryWidth();
    });

		$(document).on('click','.salondetail_popup', function(){
			//alert();
			var ids=$(this).attr('data-id');
			var allid=$(this).attr('data-allid');
			loading();	
			$.ajax({
					url: base_url+"user/getsalon_servicedetail",
					type: "POST",
					data:{ id: ids,allid:allid},
					success: function (response) {
						 var obj = jQuery.parseJSON( response );
              			if(obj.success){
              			//if(response){
              				//$("#service_name_fordetail").html(obj.service);
							$("#adddetail_message").html(obj.html);
							$('#service-show-detail').modal('show');
							unloading();
						}
						else{
						unloading();
						}
						
					 }
					 	
				});
			

		});

    $(document).on('click', '.galbook', function() {
      var sid = $(this).data("id");
      var mid = $(this).data("mid");
      var eid = $("#empId").data("val");
      var base_url = "<?php echo base_url()?>";
      loading();
      $.ajax({
        url: base_url + "booking/addtobooking",
        type: "POST",
        data: {
          id: sid,
          mid: mid,
          from: 'gi', eid: eid
        },
        success: function (data) {
          var obj = jQuery.parseJSON(data);
          if (obj.success == 1) {
            window.location.href = base_url + "booking/booking_detail/" + obj.sid + "?employee_select=" + eid;
          } else if (obj.success == 2) {
            unloading();
            var url = $("#select_url").val();
            $("#error_message").css("display", "");
            $("#service-loging-check").modal("show");
            $("#addservice_message").html(obj.message);
            //window.location.href = base_url+'auth/login/'+url;
            $(window).scrollTop(200);
            setTimeout(function () {
              $("#error_message").hide();
            }, 5000);
          } else if (obj.success == 3) {
            unloading();
            $("#online_booking_message").modal("show");
          } else if (obj.success == 4) {
            unloading();
            $("#openLoginPopup").modal("show");
          } else {
            unloading();
            var url = $("#select_url").val();
            $("#error_message").css("display", "");
            $("#service-loging-check").modal("show");
            //window.location.href = base_url+'auth/login/'+url;
            $(window).scrollTop(200);
            setTimeout(function () {
              $("#error_message").hide();
            }, 5000);
          }
        },
      });
    });

    $(document).on('click',"#m_review",function(){

         var role="<?php if(!empty($this->session->userdata('access'))) echo $this->session->userdata('access'); ?>";
         //alert(role);
      if(role=='marchant'){
          $("html,body").animate({
	        "scrollTop": $('.review_scroll').offset().top-60
	     }, 1000);		//nsec4

      }else{
	      $("html,body").animate({
	        "scrollTop": $('.review_scroll').offset().top-110
	     }, 1000);   //nsec4
      }
	     
	 });

  $(document).on('click',"#m_about",function(){

         var role="<?php if(!empty($this->session->userdata('access'))) echo $this->session->userdata('access'); ?>";
         //alert(role);
      if(role=='marchant'){
          $("html,body").animate({
	        "scrollTop": $('.mabout_sec').offset().top-65+250
	     }, 1000);

      }else{
	      $("html,body").animate({
	        "scrollTop": $('.mabout_sec').offset().top-115+250
	     }, 1000);
      }
	     
	 });

   $(document).on('click', '.gimg', function() {
      $("#galimage").attr('src', $(this).data('url'));
      $("#galsrc1").attr('srcset', $(this).data('url1'));
      $("#galsrc2").attr('srcset', $(this).data('url1'));
      $("#galimagedetail").html("");
      $('#gimg-detail').modal();

      $.ajax({
        url: base_url+"user/getgallerydetail",
        type: "POST",
        data: {id: $(this).data('id')},
        success: function (data) {
          var obj = jQuery.parseJSON( data );
            if(obj.success=='1'){
              $("#galimagedetail").html(obj.html);
            }
          }
          
      });
   });

  $(document).on('click',"#m_booknow",function(){

         var role="<?php if(!empty($this->session->userdata('access'))) echo $this->session->userdata('access'); ?>";
         //alert(role);
      if(role=='marchant'){
          $("html,body").animate({
	        "scrollTop": $('.mbooknow_sec').offset().top-35
	     }, 1000);

      }else{
	      $("html,body").animate({
	        "scrollTop": $('.mbooknow_sec').offset().top-85
	     }, 1000);
      }
	     
	 });

  	 $(document).on('click','.review_filter', function(){
  	 		$('.review_filter_all' ).each( function() {
			this.checked = false;
			});
          short_rating();
        });
  	 $(document).on('click','.review_filter_all', function(){
          $('.review_filter' ).each( function() {
			this.checked = false;
			});
          $('#close_multi').trigger('click');
          short_rating();
        });
  	 $(document).on('click','.ratingshort', function(){
           short_rating();
        });
  	$(document).on('click','.loadMoreReview', function(){
            loading();  
            var loadid=$("#loadmore_id").val();
            $.ajax({
                url: base_url+"user/loadreviewfilter",
                type: "POST",
                data:$("#frmrate_short").serialize(),
                success: function (data) {
                  var obj = jQuery.parseJSON( data );
                   if(obj.success=='1'){
                    //$('#div_review').html(response);
                    $("#div_review").append(obj.html);
                    $('#load-'+loadid).remove();
                    $('#loadmore_id').val(obj.limit);
                     }
                    //$('#load-'+loadid).remove();
                
                  
                  unloading();  
                 }
                 
              });
        });

 //~ $(document).on('click',".selectSrviceForBooking",function(){
	//~ alert('sdf');
	 //~ // $(this).find('.bookingSelect').trigger('click');
	  //~ //return false;
   //~ // $(".accordion-toggle").click();	
	//~ });	
	

	function short_rating()
      {
        loading();  
          $.ajax({
              url: base_url+"user/reviewfilter",
              type: "POST",
              data:$("#frmrate_short").serialize(),
              success: function (data) {
                var obj = jQuery.parseJSON( data );
                 if(obj.success=='1'){
                  //$('#div_review').html(response);
                   $("#div_review").html(obj.html);
                   $('#loadmore_id').val('1');
                   }
                   else{
                    $("#div_review").html(obj.html);
                    $('#loadmore_id').val('1');
                   }
                  //$('#load-'+loadid).remove();
              
                
                unloading();  
               }
               
            });
      }



</script>
<?php  if($this->uri->segment(3)!="" && !isset($_GET['servicids']) && !isset($_GET['chk'])){
?>
<script>
$(document).ready(function(){
		
		
  $('html, body').animate({
       scrollTop: $('.mbooknow_sec').offset().top-85
   }, 1000);
  });

  $("#onscrollreview").click(function() {
      $('.modal-content').animate({
      scrollTop: $("#service_reting").offset().top
  },1000);

});
</script>
<?php

}

?>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<style type="text/css">
        body {
            margin: 0;
        }
        /* #map {
            width: 100vw;
            height: 100vh;
        } */
    </style>
</head>
<body>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script type="text/javascript" src='https://tiles.locationiq.com/v3/js/liq-styles-ctrl-leaflet.js?v=0.1.8'></script>
<script type="text/javascript">
    // Maps access token goes here
    var key = '5eaae41f648ada';

    // Add layers that we need to the map
    var streets = L.tileLayer.Unwired({key: key, scheme: "streets"});

    
    // Initialize the map
    var map = L.map('map', {
        center: [<?php echo $userdetail[0]->latitude; ?>,
        <?php echo $userdetail[0]->longitude; ?>], // Map loads with this location as center
        zoom: 17,
        scrollWheelZoom: false,
        layers: [streets] // Show 'streets' by default
    });

    // Add the 'scale' control
    L.control.scale().addTo(map);

    // Add the 'layers' control
    L.control.layers({
        "Streets": streets
    }).addTo(map);

    // Add a 'marker'
    var marker = L.marker(  [<?php echo $userdetail[0]->latitude; ?>,
        <?php echo $userdetail[0]->longitude; ?>], {draggable: true} )
        .addTo(map)
        .bindPopup(`<?php echo $userdetail[0]->address.'
              '.$userdetail[0]->city.','.$userdetail[0]->country; ?>`)
        .openPopup();

    
    marker.on('dragend', function(event) {
      var position = marker.getLatLng();
      marker.setLatLng(position, {
        draggable: 'true'
      }).bindPopup("You're now at " + position.toString())
        .openPopup();

    });
    
</script>