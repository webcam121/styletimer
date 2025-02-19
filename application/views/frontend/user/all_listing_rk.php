<style>
.popup-right-slider-text .fa-star-half.font-size-16 {
margin-left: -26px;
}
</style>
<?php 

if(!empty($usersdetail))
       {
      foreach($usersdetail as $user)
      {
        if ($user->slug !== '') {
          $hrefUrl=base_url("salons/".$user->slug);
        } else {
          $hrefUrl=base_url("salons/?id=".url_encode($user->id));
        }
        
        //print_r($hrefUrl);
         ?>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" >

                <div class="right-side-box mt-20 pb-10">
                  <a href="#" id="profileUrl-<?php echo $user->id;  ?>">
                  <div class="row mb-20">
                      <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">

                        <h3 class="fontfamily-medium font-size-20 color333" style="cursor:pointer;"><?php if(!empty($user->business_name)) echo $user->business_name; ?>
                        </h3>

                        <div class="d-flex">
                          <?php if(!empty($user->rating))
                                { //$rate=round($user->rating);
                                   $echorate=number_format($user->rating,1);
                                //$echorate='2.5';
                                }
                                else{ //$rate=0;
                                   $echorate='0';
                                }
                              //$rate=4.3;
                                 echo '<span style="font-size:17px;color: #FF9944;" class="mani_stars_count">'.$echorate.' &nbsp;</span>';

                              for($i=1;$i<=5;$i++){
                                  if($i<=$echorate)
                                    {  ?>
                                       <i class="fas fa-star colororange mr-2 font-size-16"></i>
                              <?php }
                                 elseif($echorate<$i && $echorate>($i-1))
                                  { ?>
                                      <i class="fas fa-star colore99999940 mr-2 font-size-16"></i>
                                      <i class="fas fa-star-half  colororange mr-2 font-size-16" style="margin-left:-26px;"></i>
                              <?php }else{  ?>
                                     <i class="fas fa-star colore99999940 mr-2 font-size-16"></i>
                              <?php } } ?>

                              <span class="color666 font-size-14 fontfamily-regular mani_text_review"><?php echo $user->totalcount; ?> <?php echo $this->lang->line('Reviews'); ?></span>

                          </div>
                          <span class="font-size-14 color999 fontfamily-light mt-10 mb-10 display-b">
                             <?php if(!empty($user->address)) echo $user->address."<br/>";
                                  if(!empty($user->zip)) echo $user->zip; if(!empty($user->city)) echo " ".$user->city;
                                ?>
                          </span>
                          <span class="colorcyan display-b"><?php echo $this->lang->line('Distance'); ?>: <?php echo (!empty($user->distance)?round($user->distance, 1):'0'); ?> Km</span>
                          <span href="#" data-uid="<?php echo $user->id; ?>" class="colorcyan showSalonOnmap fontfamily-light font-size-14 a_hover_cyan mb-10 vertical-middle display-ib" style="">
                              <img src="<?php echo base_url('assets/frontend/images/blue_localtion.svg')?>" class="width24v mr-10 vertical-middle">
                              <?php echo $this->lang->line('show-on-map'); ?>
                          </span>
                        <?php 
                         $cuD = date("Y-m-d");
                         $currentData = "'$cuD'" .' '."'00:00:00'";
                          $sql2="SELECT `salon_id`,
                           DATE_ADD(`st_users`.`created_on`,
                            INTERVAL + extra_trial_month MONTH) AS endTrial FROM `st_favourite` 
                            JOIN `st_users` ON `st_favourite`.`salon_id`=`st_users`.`id` 
                            WHERE `st_favourite`.`salon_id` = $user->id HAVING endTrial > $currentData";
			  $query2=$this->db->query($sql2);
	       $favourite = $query2->result();
       // echo $this->db->last_query();
        //print_r($favourite);   
                        if($favourite && $favourite[0]->salon_id == $user->id){
                       if($user->favourite == 1){
                           $fav_chk = 'block';
                           $fav_unchk = 'none';
                          }
                          else{
                            $fav_chk = 'none';
                            $fav_unchk = 'block';
                          }
                        }else{
                          $fav_chk = 'none';
                            $fav_unchk = 'block';
                        }
                        
                          ?>

                          <div class="no_favourite mb-10" id="no_fav_<?php echo $user->id; ?>"  style="display:<?php echo $fav_unchk; ?>;width:50%;">
                             <span data-id="<?php echo $user->id; ?>" class="display-b favourite_click" id="add">
                                 <img src="<?php echo base_url('assets/frontend/images/blanck-hard.svg')?>" class="width24v mr-10 vertical-middle">
                               <span class="colorpink fontfamily-light font-size-14"><?php echo $this->lang->line('Add-to-Favourite'); ?></span>
                             </span>
                          </div>
                          <div class="your_favourite mb-10" id="your_fav_<?php echo $user->id; ?>"  style="display:<?php echo $fav_chk; ?>;width:50%;">
                             <span data-id="<?php echo $user->id; ?>" class="display-b favourite_click" id="remove">
                                 <img src="<?php echo base_url('assets/frontend/images/fill-hard.svg')?>" class="width24v mr-10 vertical-middle">
                                <span class="colorpink fontfamily-light font-size-14"><?php echo $this->lang->line('Your-Favourite'); ?></span>
                            </span>
                          </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
                            <div id="small-slider123<?php echo $user->id; ?>" class="carousel slide" data-ride="carousel">
                              <!-- The slideshow -->
                              <div class="carousel-inner border-radius4">
                                  <?php $alideImg=array();
                                  if(!empty($user->image)){ $path='assets/uploads/banners/'.$user->id.'/prof_'.$user->image;							  if(file_exists($path))
									                             {
                                                                  $img1=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image,'webp');  
                                                                  $img=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image,'webp');  
																 }          
															  else
															  { 
															    $img1=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image,'webp');
																$img=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image,'png');
																  
															  }
                                  $alideImg[]=$user->image; ?>
                                  <div class="carousel-item active">
									<picture class="upl-gallary-image">
										 <source srcset="<?php echo $img1; ?>" type="image/webp" class="sliderImage">
										 
										 <source srcset="<?php echo $img1; ?>" type="image/webp" class="sliderImage">
										 
									   <img src="<?php echo $img; ?>" class="sliderImage">
									</picture>
                                  
                                  </div>
                                  <?php } if(!empty($user->image1)){ $path1='assets/uploads/banners/'.$user->id.'/prof_'.$user->image1;						
									                     if(file_exists($path1))
									                           {
                                                                $img21=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image1,'webp');  
                                                                 $img2=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image1,'webp');  
															   }          
															else
															  { 
															    $img21=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image1,'webp');
																$img2=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image1,'png');
															  }
															  
                                  $alideImg[]=$user->image1; ?>
                                  <div class="carousel-item <?php if(empty($user->image)) echo "active"; ?>">
                                 	
                                 	<picture class="upl-gallary-image">
										 <source srcset="<?php echo $img21; ?>" type="image/webp" class="sliderImage">
										 
										 <source srcset="<?php echo $img21; ?>" type="image/webp" class="sliderImage">
										 
									   <img src="<?php echo $img2; ?>" class="sliderImage">
									</picture>
									
                                  </div>
                                  <?php } if(!empty($user->image2)){ $path2='assets/uploads/banners/'.$user->id.'/prof_'.$user->image2;							 
									                  if(file_exists($path2))
									                           {
                                                                $img31=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image2,'webp');  
                                                                 $img3=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image2,'webp');  
															   }          
															else
															  { 
															    $img31=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image2,'webp');
																$img3=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image2,'png');
															  }
                                  $alideImg[]=$user->image2; ?>
                                  <div class="carousel-item <?php if(empty($user->image) && empty($user->image1)) echo "active"; ?>">
                                   <picture class="upl-gallary-image">
										 <source srcset="<?php echo $img31; ?>" type="image/webp" class="sliderImage">
										 
										 <source srcset="<?php echo $img31; ?>" type="image/webp" class="sliderImage">
										 
									   <img src="<?php echo $img3; ?>" class="sliderImage">
									</picture>
                                  </div>
                                  <?php } if(!empty($user->image3)){ $path3='assets/uploads/banners/'.$user->id.'/prof_'.$user->image3;
									  
									  					  if(file_exists($path3))
									                           {
                                                                $img41=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image3,'webp');  
                                                                 $img4=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image3,'webp');  
															   }          
															else
															  { 
															    $img41=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image3,'webp');
																$img4=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image3,'png');
															  }
                                  $alideImg[]=$user->image3; ?>
                                  <div class="carousel-item <?php if(empty($user->image) && empty($user->image1) && empty($user->image2)) echo "active"; ?>">
                                  <picture class="upl-gallary-image">
										<source srcset="<?php echo $img41; ?>" type="image/webp" class="sliderImage">
										 
										<source srcset="<?php echo $img41; ?>" type="image/webp" class="sliderImage">
										 
									   <img src="<?php echo $img4; ?>" class="sliderImage">
									   
									</picture>
                                  </div>
                                  <?php } if(!empty($user->image4)){ $path4='assets/uploads/banners/'.$user->id.'/prof_'.$user->image4;							  
									                      if(file_exists($path4))
									                           {
                                                                $img51=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image4,'webp');  
                                                                 $img5=getimge_url('assets/uploads/banners/'.$user->id.'/','prof_'.$user->image4,'webp');  
															   }          
															else
															  { 
															    $img51=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image4,'webp');
																$img5=getimge_url('assets/uploads/banners/'.$user->id.'/',$user->image4,'png');
															  }

                                  $alideImg[]=$user->image4; ?>
                                  <div class="carousel-item <?php if(empty($user->image) && empty($user->image1) && empty($user->image2) && empty($user->image3)) echo "active"; ?>">
                                      <picture class="upl-gallary-image">
										<source srcset="<?php echo $img51; ?>" type="image/webp" class="sliderImage">
										 
										<source srcset="<?php echo $img51; ?>" type="image/webp" class="sliderImage">
										 
									   <img src="<?php echo $img5; ?>" class="sliderImage">
									   
									</picture>
                                  </div>
                                  <?php } if(empty($user->image) && empty($user->image1) && empty($user->image2) && empty($user->image3) && empty($user->image3)){   ?>
                                  <div class="carousel-item active">
                                       <img src="<?php echo base_url('assets/frontend/'); ?>images/noimage.png" class="sliderImage" style=
                                      "width: 275px;height: 183px;">
                                  </div>
                                  <?php } ?>
                              </div>

                              <!-- Left and right controls -->
                              <?php  if(!empty($alideImg) && count($alideImg)>1 )
                                   { ?>
                                    <span class="carousel-control-prev" href="#small-slider123<?php echo $user->id; ?>" data-slide="prev">
                                    <img src="<?php echo base_url('assets/frontend/'); ?>images/prev-arrow-white.svg">
                                  </span>
                                    <span class="carousel-control-next" href="#small-slider123<?php echo $user->id; ?>" data-slide="next">
                                    <img src="<?php echo base_url('assets/frontend/'); ?>images/next-arrow-white.svg">
                                  </span>
                              <?php } ?>

                            </div>
                        </div>
                    </div>

                <?php
                  $sids=array();
                   if(!empty($user->sercvices))
                     {
                    $i=0;

                    foreach($user->sercvices as $ser)
                      {
                    $sids[]=$ser->subcategory_id; ?>

                    <div class="row <?php if($i==0) echo "bglightgray"; else echo "bgwhite"; ?> py-2">
                        <div class="col-12 col-sm-9 col-md-8 col-lg-9">
                            <h4 class="font-size-16 fontfamily-medium color333 mb-0 text-lines-overflow-1" style="-webkit-line-clamp: 1;">
                               <?php  echo $ser->s_category; ?>
                            </h4>
                            <span class="font-size-14 fontfamily-light color999">
                              <?php echo $ser->duration; ?> Mins
                            </span>
                        </div>
                        <div class="col-12 col-sm-3 col-md-4 col-lg-3 pl-0 text-right">
                           <p class="font-size-14 fontfamily-medium color333 mb-0">
                             <?php if($ser->price_start_option=='ab' ||
                              $ser->totalservice>=2) echo 'ab'; ?>
                          <?php
                              if(!empty($ser->discount_price))
                               echo price_formate($ser->price); 
                               else echo price_formate($ser->price);
                           ?> €
                          </p>
                         <?php if(!empty($ser->discount_price))
                               {
                                $discount=get_discount_percent($ser->price,$ser->discount_price);
                                if(!empty($discount))
                                   {  ?>
                                      <span class="colorcyan fontfamily-regular font-size-14">
                                        <?php echo $this->lang->line('save-up-to'); ?>
                                          <?php echo $discount; ?> %
                                      </span>
                          <?php    }
                                } ?>
                        </div>
                    </div>

              <?php  $i++;  if($i==2) $i=0;
                       }
                     }
                    $servids=implode(',',$sids);
                ?>

              </a>
                    <div class="addanchoreUrl accordion pb-0 servicemactch<?php echo $user->id;  ?>" id="right-side-box-accordian1" data-href="<?php echo $hrefUrl; ?>" data-id="profileUrl-<?php echo $user->id;  ?>" data-service="<?php echo url_encode($servids); ?>">
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <span class="accordion-toggle color666 fontfamily-regular font-size-14 a_hover_666 open_time_dropdown collapsed" data-toggle="collapse" data-parent="#right-side-box-accordian1" data-id="<?php echo $user->id; ?>" href="#collapseOne<?php echo $user->id; ?>" aria-expanded="false" data-count="0">
                                <?php echo $this->lang->line('Click-here'); ?>
                              </span>
                            </div>
                            <div id="collapseOne<?php echo $user->id; ?>" class="accordion-body collapse mt-30">
                              <div class="accordion-inner">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5" id="days_availablity<?php echo $user->id; ?>">
                                       Loading.....
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-7 col-xl-7">
                                        <h3 class="mt-20 color333 font-size-16 fontfamily-semibold ">Salon Informationen</h3>
                                        <p class="color666 font-size-14 lineheight21 fontfamily-regular text-lines-overflow-1" style="-webkit-line-clamp: 5;"><?php if(!empty($user->about_salon)) echo $user->about_salon; ?></p>

                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                  </div>
            </div>
          </div>
    <?php }  ?>
        <p class="text-center mt-3"><?php echo $this->lang->line('Your-salon-not-listed'); ?> ? <a href="#" data-toggle="modal" data-target="#salon-list-blank" data-backdrop="static" data-keyboard="false" style="color:#00b3bf ;"><?php echo $this->lang->line('click-here'); ?></a></p>
<?php }
   else{ ?>
   <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">

                        <h3 class="fontfamily-medium font-size-20 color333" style="cursor:pointer;">reree0                        </h3>

                        <div class="d-flex">
                          <span style="font-size:17px;color: #FF9944;" class="mani_stars_count">0 &nbsp;</span>                                     <svg class="svg-inline--fa fa-star fa-w-18 colore99999940 mr-2 font-size-16" aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg><!-- <i class="fas fa-star colore99999940 mr-2 font-size-16"></i> -->
                                                                   <svg class="svg-inline--fa fa-star fa-w-18 colore99999940 mr-2 font-size-16" aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg><!-- <i class="fas fa-star colore99999940 mr-2 font-size-16"></i> -->
                                                                   <svg class="svg-inline--fa fa-star fa-w-18 colore99999940 mr-2 font-size-16" aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg><!-- <i class="fas fa-star colore99999940 mr-2 font-size-16"></i> -->
                                                                   <svg class="svg-inline--fa fa-star fa-w-18 colore99999940 mr-2 font-size-16" aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg><!-- <i class="fas fa-star colore99999940 mr-2 font-size-16"></i> -->
                                                                   <svg class="svg-inline--fa fa-star fa-w-18 colore99999940 mr-2 font-size-16" aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg><!-- <i class="fas fa-star colore99999940 mr-2 font-size-16"></i> -->
                              
                              <span class="color666 font-size-14 fontfamily-regular mani_text_review">0 Bewertungen</span>

                          </div>
                          <span class="font-size-14 color999 fontfamily-light mt-10 mb-10 display-b">
                             1233<br>65582 Berlin                          </span>
                          <span class="colorcyan display-b">Entfernung: 6172.8 Km</span>
                          <span href="#" data-uid="2015" class="colorcyan showSalonOnmap fontfamily-light font-size-14 a_hover_cyan mb-10 vertical-middle display-ib" style="">
                              <img src="https://dev.styletimer.de/assets/frontend/images/blue_localtion.svg" class="width24v mr-10 vertical-middle">
                              auf der Karte zeigen                          </span>
                        
                          <div class="no_favourite mb-10" id="no_fav_2015" style="display:block;width:50%;">
                             <span data-id="2015" class="display-b favourite_click" id="add">
                                 <img src="https://dev.styletimer.de/assets/frontend/images/blanck-hard.svg" class="width24v mr-10 vertical-middle">
                               <span class="colorpink fontfamily-light font-size-14">Als Favorit hinzufügen</span>
                             </span>
                          </div>
                          <div class="your_favourite mb-10" id="your_fav_2015" style="display:none;width:50%;">
                             <span data-id="2015" class="display-b favourite_click" id="remove">
                                 <img src="https://dev.styletimer.de/assets/frontend/images/fill-hard.svg" class="width24v mr-10 vertical-middle">
                                <span class="colorpink fontfamily-light font-size-14">Als Favorit markiert</span>
                            </span>
                          </div>
                        </div>
  <!-- <div class="row" style="margin-right: 0px;">
      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 bggreengradient" style="text-align: center;margin-top: 9px;color: #fff;">
          <h5 style="padding: 15px 0 0 0;"><?php echo $this->lang->line('we-havent-txt'); ?></h5>
          <p> <?php echo $this->lang->line('pls-use-filter-txt'); ?></p>
      </div>
  </div>
      <p class="text-center mt-3"><?php echo $this->lang->line('Your-salon-not-listed'); ?> ?
          <a href="#" data-toggle="modal" data-target="#salon-list-blank" data-backdrop="static" data-keyboard="false" style="color:#00b3bf ;"><?php echo $this->lang->line('click-here'); ?>
           </a>
      </p> -->
<?php } ?>


