<?php $this->load->view('frontend/common/header');   ?>
 
 <div class="d-flex pt-84">
<?php $this->load->view('frontend/common/sidebar'); ?>

        <div class="right-side-dashbord w-100 pl-30 pr-30">
          <div class="bgwhite border-radius4 box-shadow1 mb-50 mt-20 around-20">

              <div class="pb-20 relative alert-parent">
              	<?php $this->load->view('frontend/common/alert'); ?>
                <p class="font-size-20 color333 fontfamily-medium mb-30"><?php echo $this->lang->line('select_newsletter'); ?></p>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-4 m-auto">
                    <div class=" mb-30 bgwhite height148  border-w border-radius4 d-flex align-items-center justify-content-center">
                      <a href="<?php echo base_url('merchant/newsletter') ?>" class="text-center">
                        <img src="<?php echo base_url('assets/frontend/images/create_new_tamplet.svg'); ?>" class="mb-3">
                        <p class="color333 font-size-14 fontfamily-medium mb-0"><?php echo $this->lang->line('create_new_template'); ?></p>
                      </a>
                    </div>
                  </div>
                  <?php 
                  //print_r($newsletter);

                  if(!empty($newsletter)){
                  	foreach($newsletter as $row){ ?>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-4 m-auto" id="Remove_<?php echo $row->id; ?>">
                    <div class="bgwhite height148 p-3 text-center border-w border-radius4 d-flex mb-30 category selctTemlat" data-id="<?php echo url_encode($row->id); ?>">
                      <div class="relative text-left wm-205">
                        <p class="font-size-18 color333 fontfamily-medium"><?php if(strlen($row->subject) >40)
                        		echo substr($row->subject, 0, 40).'..';
                        	else 
                        		echo $row->subject; ?></p>
                        <p class="font-size-14 color666 fontfamily-regular webkit-line-clamp" style="-webkit-line-clamp:2;">
                        	<?php 
                        	$desc=strip_tags($row->description); 
                        	if(strlen($desc) > 25)
                        		echo substr($desc, 0,25).'..';
                        	else 
                        		echo $desc; ?>
                        </p>
						<a href="<?php echo base_url('merchant/newsletter/').url_encode($row->id); ?>" class="colorcyan font-size-14 fontfamily-regular a_hover_cyan mr-3"><?php echo $this->lang->line('Edit'); ?></a>
                        <a href="javascript:void(0);" id="<?php echo url_encode($row->id); ?>" data-toggle="modal" data-target=".delete-popup" class="colororange font-size-14 fontfamily-regular a_hover_orange delete_newsLetter"><?php echo $this->lang->line('Delete'); ?></a>
                      </div>
                      <div class="relative salon-img-bg ml-auto">
                      	<?php if($row->image_path !='')
                      			$img= base_url('assets/uploads/newsletter/').$row->merchant_id.'/'.$row->image_path; 
                      		else
                      			$img= base_url('assets/frontend/images/noimage.png');
                      		?>
                        <img src="<?php echo $img; ?>" style="width: 80px; height: 80px;" class="vertical-middle">
                      </div>
                    </div>
                  </div>
                  <?php } 
              		} ?>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="text-center pt-0">
                      <a href="" id="nextUrl"><button class="btn widthfit2"><?php echo $this->lang->line('Next'); ?></button></a>
                    </div>
                  </div>
                </div>
                
              </div>
              <!-- dashboard right side end -->  
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->

    <!-- modal start -->
    <div class="modal delete-popup fade">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="close_box">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-40 mb-30 pl-25 pr-25 text-center">
            <img src="<?php echo base_url('assets/frontend/images/delete-popup-icon.svg'); ?>" class="mb-20">
            <p class="fontfamily-medium color333 font-size-18 mb-40"><?php echo $this->lang->line('delete_newslatter_confirm'); ?></p>
            <input type="hidden" id="news_letter" name="news_letter">
            <button id="remove_newsLetter" class="btn widthfit"><?php echo $this->lang->line('Delete'); ?></button>
          </div>
        </div>
      </div> 
    </div>

<?php $this->load->view('frontend/common/footer_script');

 $pinstatus = get_pin_status($this->session->userdata('st_userid')); 
//echo current_url().'=='.$_SERVER['HTTP_REFERER'];
    if($pinstatus->pinstatus=='on'){
        $this->load->view('frontend/marchant/profile/ask_pin_popup');
      }    ?>
<script>
 
 $(document).on('click','.selctTemlat',function(){
	var id= $(this).attr('data-id');
	
	$(".selctTemlat.active").removeClass('active');
	$(this).addClass('active');
	 var url="<?php echo base_url(); ?>merchant/customers?tempid="+id;
	 $("#nextUrl").attr('href',url);
	});

</script>
