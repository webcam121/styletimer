      <style>
		  #uploadimageModal{
			  z-index:1112 !important;
			  }
	  </style>	  
      
    <div class="salon-new-top-bg pt-4">
                <div class="pl-20 pr-20 d-flex">
                  <h3 class="font-size-20 color333 fontfamily-medium mb-2"><?php echo $this->lang->line('setup_your_profile'); ?></h3>
<!--
                  <a href="#" onclick="getTimehtml();" class="colorcyan font-size-14 a_hover_cyan ml-auto">Skip</a>
-->
                </div>
                  <div class="salon-new-step">
                       <?php if(!empty($setup_no)) $data['setup_no']=$setup_no;  $this->load->view('frontend/marchant/common_setup',$data); ?>
                  </div>
              </div>
              <div class="bgwhite relative pt-4 px-3">
                <p class="color333 font-size-16 fontfamily-semibold">
                  <?php echo $this->lang->line('please_add_images_of_your_salon_that_will_show_up_in_your_salon_profile'); ?>
                </p>
                  <div class="row">
                  <?php if(empty($banerdata->image) || empty($banerdata->image1) || empty($banerdata->image2) || empty($banerdata->image3) || empty($banerdata->image4)){ ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                    <form method="post" id="uploadBannerImage" enctype="multipart/form-data" action="">
                      <div class="relative upl-gallary-parent d-flex justify-content-center align-items-center bglightgray" style="border:1px dashed #00b3bf;">
                        <label class="btn-upload-file">
                              <img src="<?php echo base_url('assets/frontend/images/upl-icon-gallry.png'); ?>">
                              <span class="colorcyan fott-size-12 fontfamily-regular display-b"><?php echo $this->lang->line('Upload_Images'); ?></span>
                              <input type="file" name="image" id="banner_setupimg_upload"/>
                        </label>  
                      </div>
                      <label id="img_error" class="error_label" style="top: 180px;left: 0px;right: 0px;"></label>
                      </form>              
                    </div>
                    <?php } if(!empty($banerdata->image)){ ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
                        <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/crop_'.$banerdata->image; ?>"  class="upl-gallary">
                         <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image; ?>" id='image' class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                             <li class="px-13 py-2">
                              <a data-src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image; ?>" data-name="<?php echo $banerdata->image; ?>" data-id="image" class="color333 font-size-14 fontfamily-regular cropImage"><?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>

                        <div class="slect-benner-bg">
                        <?php echo $this->lang->line('Banner'); ?>
                        </div>
                      </div>
                    </div>
                    <?php } if(!empty($banerdata->image1)){ ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
                        <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/crop_'.$banerdata->image1; ?>"  class="upl-gallary">
                         <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image1; ?>" id='image1' class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                            <li class="px-13 py-2">
                              <a onclick="setBnanerImage('<?php echo base_url('profile/update_banner_ajax/'.$banerdata->image1.'?field=image1');?>');" class="color333 font-size-14 fontfamily-regular setAsBanner" data-name="<?php echo $banerdata->image1; ?>"><?php echo $this->lang->line('Set_as_Banner'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a onclick="return deleteBnanerImage('<?php echo base_url('profile/delete_banner_image/'.$banerdata->image1.'?field=image1');?>');" class="color333 font-size-14 fontfamily-regular deleteImage" data-name="<?php echo $banerdata->image1; ?>"><?php echo $this->lang->line('Delete_Picture'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a data-src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image1; ?>" data-name="<?php echo $banerdata->image1; ?>" data-id="image1" class="color333 font-size-14 fontfamily-regular cropImage"><?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>
                        <!-- <div class="slect-benner-bg">
                          Banner
                        </div> -->
                      </div>
                    </div>
                            <?php } if(!empty($banerdata->image2)){ ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
                        <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/crop_'.$banerdata->image2; ?>" class="upl-gallary">
                        <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image2; ?>" id='image2' class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                            <li class="px-13 py-2">
                              <a onclick="setBnanerImage('<?php echo base_url('profile/update_banner_ajax/'.$banerdata->image2.'?field=image2');?>');"  class="color333 font-size-14 fontfamily-regular setAsBanner" data-name="<?php echo $banerdata->image2; ?>"><?php echo $this->lang->line('Set_as_Banner'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a onclick="return deleteBnanerImage('<?php echo base_url('profile/delete_banner_image/'.$banerdata->image2.'?field=image2');?>');" class="color333 font-size-14 fontfamily-regular deleteImage" data-name="<?php echo $banerdata->image2; ?>"><?php echo $this->lang->line('Delete_Picture'); ?></a>
                            </li>
                             <li class="px-13 py-2">
                              <a data-src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image2; ?>" data-name="<?php echo $banerdata->image2; ?>" data-id="image2" class="color333 font-size-14 fontfamily-regular cropImage"><?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>
                        <!-- <div class="slect-benner-bg">
                          Banner
                        </div> -->
                      </div>
                    </div>
                   <?php } if(!empty($banerdata->image3)){ ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
                        <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/crop_'.$banerdata->image3; ?>" class="upl-gallary">
                         <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image3; ?>" id='image3' class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                            <li class="px-13 py-2">
                              <a onclick="setBnanerImage('<?php echo base_url('profile/update_banner_ajax/'.$banerdata->image3.'?field=image3');?>');" class="color333 font-size-14 fontfamily-regular setAsBanner" data-name="<?php echo $banerdata->image3; ?>"><?php echo $this->lang->line('Set_as_Banner'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a onclick="return deleteBnanerImage('<?php echo base_url('profile/delete_banner_image/'.$banerdata->image3.'?field=image3');?>');" class="color333 font-size-14 fontfamily-regular deleteImage" data-name="<?php echo $banerdata->image3; ?>"><?php echo $this->lang->line('Delete_Picture'); ?></a>
                            </li>
                             <li class="px-13 py-2">
                              <a data-src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image3; ?>" data-name="<?php echo $banerdata->image3; ?>" data-id="image3" class="color333 font-size-14 fontfamily-regular cropImage"><?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>
                        <!-- <div class="slect-benner-bg">
                          Banner
                        </div> -->
                      </div>
                    </div>
                            <?php } if(!empty($banerdata->image4)){ ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                      <div class="relative upl-gallary-parent">
                        <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/crop_'.$banerdata->image4; ?>" class="upl-gallary">
                         <img src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image4; ?>" id="image4" class="upl-gallary display-n">
                        <div class="dropdown">
                          <div class="upl-three-dot dropdown-toggle cursor-p" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url('assets/frontend/images/three-dot-upl-glry.svg'); ?>">
                          </div>
                          <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                            <li class="px-13 py-2">
                              <a onclick="setBnanerImage('<?php echo base_url('profile/update_banner_ajax/'.$banerdata->image4.'?field=image4');?>');"  class="color333 font-size-14 fontfamily-regular setAsBanner" data-name="<?php echo $banerdata->image4; ?>"><?php echo $this->lang->line('Set_as_Banner'); ?></a>
                            </li>
                            <li class="px-13 py-2">
                              <a onclick="return deleteBnanerImage('<?php echo base_url('profile/delete_banner_image/'.$banerdata->image4.'?field=image4');?>');" class="color333 font-size-14 fontfamily-regular deleteImage" data-name="<?php echo $banerdata->image4; ?>"><?php echo $this->lang->line('Delete_Picture'); ?></a>
                            </li>
                             <li class="px-13 py-2">
                              <a data-src="<?php echo base_url('assets/uploads/banners/').$banerdata->user_id.'/'.$banerdata->image4; ?>" data-name="<?php echo $banerdata->image4; ?>" data-id="image4" class="color333 font-size-14 fontfamily-regular cropImage"><?php echo $this->lang->line('Resize_Picture'); ?></a>
                            </li>
                          </ul>
                        </div>
                        <!-- <div class="slect-benner-bg">
                          Banner
                        </div> -->
                      </div>
                    </div>
          <?php } ?>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
                      <div class="pt-3 pb-3 bgwhite boxshadow-5 px-3">
                      <button type="button" onclick="getProfilehtml();" class="btn btn-border-orange btn-large widthfit2 ml-0"><?php echo $this->lang->line('Previous'); ?></button>
                         <button type="button" onclick="getTimehtml();" class="btn btn-large widthfit2 float-right"  <?php if(empty($banerdata->image) && empty($banerdata->image1) && empty($banerdata->image2) && empty($banerdata->image3) && empty($banerdata->image4)){ echo 'disabled'; } ?>><?php echo $this->lang->line('SaveProceed'); ?></button>
                        </div>
                    </div>
                  </div>
