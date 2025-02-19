  
   <?php if(!empty($usersdetail)){
	   foreach($usersdetail as $user){
			$hrefUrl=base_url("salons/" . urlencode($user->slug) .'/'. urlencode(replace_spec_char($user->city)));
			if ($user->slug == '') {
			$hrefUrl=base_url("salons/?id=" . url_encode($user->id));
			}

			if($user->favourite == 1){
			   $fav_chk = 'block';
			   $fav_unchk = 'none';
			} else{
				$fav_chk = 'none';
				$fav_unchk = 'block';
			}
		?>
       <div class="slider-parent popupSlideCount">
		  <div class="d-flex">
			<div class="relative mr-30">
			  <div id="small-slider<?php echo $user->id; ?>" class="carousel slide" data-ride="carousel">
			       <div class="carousel-inner border-radius4">
								  <?php $alideImg=array();
								   if(!empty($user->image)){  $path='assets/uploads/banners/'.$user->id.'/prof_'.$user->image;							         if(file_exists($path))
                                                                  $img=base_url('assets/uploads/banners/').$user->id.'/prof_'.$user->image;             else $img=base_url('assets/uploads/banners/').$user->id.'/'.$user->image;
									   $alideImg[]=$user->image; ?>
                                <div class="carousel-item active">
                                  <img src="<?php echo $img; ?>" class="sliderImage">
                                </div>
                                <?php } if(!empty($user->image1)){ $path1='assets/uploads/banners/'.$user->id.'/prof_'.$user->image1;							         if(file_exists($path1))
                                                                  $img1=base_url('assets/uploads/banners/').$user->id.'/prof_'.$user->image1;             else $img1=base_url('assets/uploads/banners/').$user->id.'/'.$user->image1;
									$alideImg[]=$user->image1; ?>
                                <div class="carousel-item <?php if(empty($user->image)) echo "active"; ?>">
                                  <img src="<?php echo $img1; ?>" class="sliderImage">
                                </div>
                                <?php } if(!empty($user->image2)){ $path2='assets/uploads/banners/'.$user->id.'/prof_'.$user->image2;							         if(file_exists($path2))
                                                                  $img2=base_url('assets/uploads/banners/').$user->id.'/prof_'.$user->image2;             else $img2=base_url('assets/uploads/banners/').$user->id.'/'.$user->image2;
									$alideImg[]=$user->image2; ?>
                                <div class="carousel-item <?php if(empty($user->image) && empty($user->image1)) echo "active"; ?>">
                                  <img src="<?php echo $img2; ?>" class="sliderImage">
                                </div>
                                <?php } if(!empty($user->image3)){ $path3='assets/uploads/banners/'.$user->id.'/prof_'.$user->image3;							         if(file_exists($path3))
                                                                  $img3=base_url('assets/uploads/banners/').$user->id.'/prof_'.$user->image3;             else $img3=base_url('assets/uploads/banners/').$user->id.'/'.$user->image3;
									$alideImg[]=$user->image3; ?>
                                <div class="carousel-item <?php if(empty($user->image) && empty($user->image1) && empty($user->image2)) echo "active"; ?>">
                                  <img src="<?php echo $img3; ?>" class="sliderImage">
                                </div>
                                <?php } if(!empty($user->image4)){ $path4='assets/uploads/banners/'.$user->id.'/prof_'.$user->image4;							         if(file_exists($path4))
                                                                  $img4=base_url('assets/uploads/banners/').$user->id.'/prof_'.$user->image4;             else $img4=base_url('assets/uploads/banners/').$user->id.'/'.$user->image4;
									$alideImg[]=$user->image4; ?>
                                <div class="carousel-item <?php if(empty($user->image) && empty($user->image1) && empty($user->image2) && empty($user->image3)) echo "active"; ?>">
                                  <img src="<?php echo $img4; ?>" class="sliderImage">
                                </div>
                                <?php } if(empty($user->image) && empty($user->image1) && empty($user->image2) && empty($user->image3) && empty($user->image3)){  ?>
									<div class="carousel-item active">
                                  <img src="<?php echo base_url('assets/frontend/'); ?>images/noimage.png" class="sliderImage" style=
                                  "width: 225px;height: 172px;">
                                </div>
									<?php } ?>
                              </div>

                              <!-- Left and right controls -->
                              <?php  if(!empty($alideImg) && count($alideImg)>1 ){ ?>
                              <a class="carousel-control-prev" href="#small-slider<?php echo $user->id; ?>" data-slide="prev">
                                <img src="<?php echo base_url('assets/frontend/'); ?>images/prev-arrow-white.svg">
                              </a>
                              <a class="carousel-control-next" href="#small-slider<?php echo $user->id; ?>" data-slide="next">
                                <img src="<?php echo base_url('assets/frontend/'); ?>images/next-arrow-white.svg">
                              </a>
                         <?php } ?>
							  </div>                  
							</div>
							<div class="relative" style="width:100%;">
							  <div class="popup-right-slider-text">
								<div class="d-flex">
								  <div class="relative">
									<h3 class="fontfamily-medium font-size-20 color333 text-lines-overflow-1" style="-webkit-line-clamp: 1;"><?php if(!empty($user->business_name)) echo $user->business_name; ?></h3>
									<span class="font-size-14 color999 fontfamily-light mb-10 display-b" style="margin-top: -0.425rem;"><?php if(!empty($user->address)) echo $user->address; if(!empty($user->zip)) echo " <br/>".$user->zip; if(!empty($user->city)) echo " ".$user->city;  ?></span>
									  <div class="relative mb-2">
										<?php 
											if(!empty($user->rating)) $echorate=number_format($user->rating,1);
											else $echorate=0;
											
											for($i=1;$i<=5;$i++){
										if($i<=$echorate){  ?>
											<i class="fas fa-star colororange mr-1 font-size-16"></i>
									  <?php }elseif($echorate<$i && $echorate>($i-1)){ ?>
										<i class="fas fa-star colore99999940 mr-1 font-size-16"></i>
										  <i class="fas fa-star-half  colororange mr-1 font-size-16"></i>    
									<?php }else{  ?>
										  <i class="fas fa-star colore99999940 mr-1 font-size-16"></i>

										  <?php } } ?>
										<span class="color666 font-size-14 fontfamily-regular">
											(
												<?php echo ($user->ratingcnt ? $user->ratingcnt : '0'); ?>
												<?php echo ($user->ratingcnt == 1? 'Bewertungen' : 'Bewertung'); ?>
											)
										</span>
									  </div>
								  </div>
								  <div class="text-right ml-auto">
									<a  href="#" id="popupUrlHref<?php echo $user->id; ?>" class="btn widthfit">Jetzt buchen</a>
								  </div>
								</div>
									                              
								<?php
								  $sids=array();
								  if(!empty($user->sercvices)){
									  
									foreach($user->sercvices as $ser){
										$sids[]=$ser->subcategory_id; ?>
										<div class="w-100 clear mb-1">
										  <span class="color666 fontfamily-medium font-size-16 display-ib"><?php  echo $ser->m_category."-".$ser->s_category; ?></span>
										  <span class="color333 fontfamily-medium font-size-16 display-ib float-right">
											<?php if(!empty($ser->discount_price)){ $discount=get_discount_percent($ser->price,$ser->discount_price);
							    			if(!empty($discount)){  ?><span class="colorcyan fontfamily-regular font-size-14">spare bis zu <?php echo $discount; ?>% &nbsp;</span> <?php } 
								} ?><?php echo price_formate($ser->price); ?> €</span>
										</div>
								<?php  } } $servids=implode(',',$sids); ?>
								<div class="addanchorePopup" data-id="popupUrlHref<?php echo $user->id; ?>" data-href="<?php echo $hrefUrl; ?>" data-service="<?php echo url_encode($servids); ?>"></div>
<!--
								<div class="w-100 clear mb-1">
								  <span class="color666 fontfamily-medium font-size-16 display-ib">Pedicure</span>
								  <span class="color333 fontfamily-medium font-size-16 display-ib float-right">23 €</span>
								</div>
								<div class="w-100 clear mb-1">
								  <span class="color666 fontfamily-medium font-size-16 display-ib">Pedicure</span>
								  <span class="color333 fontfamily-medium font-size-16 display-ib float-right">23 €</span>
								</div>
-->
							  </div>                  
							</div>
						  </div>
						</div>
                  <?php }  } else { ?> 
					   <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 bggreengradient" style="text-align: center;margin-top: 9px;color: #fff;">
					   <h5 style="padding: 15px 0 0 0;"><?php echo $this->lang->line('we-havent-txt'); ?></h5>
                       <p> <?php echo $this->lang->line('pls-use-filter-txt'); ?></p>
                      </div>
                      </div>
                     <?php } ?>
                    
