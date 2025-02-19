<?php $this->load->view('frontend/common/header');   ?>
 <style type="text/css">
 	.alert.alert-success.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0.text-center{
 		top:-35px;
 	}
  .alert.alert-danger.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0{
    top:-35px;
  }

  .dropdown-menu > li > input:checked ~ label,
.dropdown-menu > li > input:checked ~ label:hover,
.dropdown-menu > li > input:checked ~ label:focus,
.dropdown-menu > .active > label,
.dropdown-menu > .active > label:hover,
.dropdown-menu > .active > label:focus {
  color: #333;
  text-decoration: none;
  outline: 0;
  background-color: #DCEFF2 !important;
}
 </style>
 <div class="d-flex pt-84">
<?php $this->load->view('frontend/common/sidebar'); ?>

        <div class="right-side-dashbord w-100 pl-30 pr-30">
          <div class="relative mt-20 mb-20 bgwhite border-radius4 box-shadow1 py-3 pt-4 pl-20 pr-20 sticky-top80-fixed">
            <div class="row ">
              <?php //print_r($totalcount); ?>
              <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8">
                <form method="post" action="" id="frmrate_short">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <h3 class="color333 fontfamily-medium font-size-20 mb-40">
                        <?php echo 'Deine Kundenbewertungen'; ?></h3>
                      <div class="display-ib vertical-middle">
                        <h1 class="colororange font-size-52 fontfamily-medium"> 
                          <?php echo $rating= isset($allreviews[0]->average)?number_format($allreviews[0]->average,1):'0.0'; ?>
                        </h1>
                      </div>
                      <?php
                       $avgfive=$avgfour=$avgthree=$avgtwo=$avgone=0;
                       $total=isset($totalcount->tcount)?$totalcount->tcount:0;
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
                      <div class="display-ib vertical-middle ml-3">
                          <div class="d-flex mb-1 mt-minus8">


                            <?php for ($i=1; $i < 6; $i++){ 
                            if($i <= $rating)  
                              echo"<i class='fas fa-star colororange mr-2 font-size-16'></i>";
                            else
                              echo "<i class='fas fa-star colore99999940 mr-2 font-size-16'></i>";
                            } ?>

                            <!-- <i class="fas fa-star colororange mr-2 font-size-16"></i>
                            <i class="fas fa-star colororange mr-2 font-size-16"></i>
                            <i class="fas fa-star colororange mr-2 font-size-16"></i>
                            <i class="fas fa-star colore99999940 mr-2 font-size-16"></i>
                            <i class="fas fa-star colore99999940 mr-2 font-size-16"></i> -->
                          </div>
                          <span class="color999 font-size-14 fontfamily-regular">(<?php echo $total.' ';  echo ($total > 1)?$this->lang->line('Reviews'):$this->lang->line('Reviews'); ?>)</span>
                      </div>

                      <div class="relative mt-3 pr-5">
                        <h6 class="fontfamily-medium color999 font-size-16 mb-3"><?php echo $this->lang->line('Filter_after'); ?> </h6>
                       
                          <div class="btn-group multi_sigle_select mb-20">
                              <button id="close_multi" style="text-transform: none !important" data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"><?php echo $this->lang->line('All_reviews'); ?></button>
                              <ul class="rating_drop dropdown-menu mss_sl_btn_dm noclose scroll-effet" style="overflow-x: auto; max-height: 240px; overflow: auto;">
                                <li class="checkbox-image">
                                  <input type="checkbox" id="id_allreview" name="category[]" class="review_filter_all" value="">
                                  <label for="id_allreview"><?php echo $this->lang->line('All_reviews'); ?></label>
                                </li>
                                <?php if(!empty($merchant_category)){
                                  foreach($merchant_category as $cat){ ?>
                                <li class="checkbox-image">
                                  <input type="checkbox" id="id_<?php echo $cat->id; ?>" 
                                  name="category[]" 
                                  class="review_filter"
                                   value="<?php echo $cat->id; ?>">
                                  <label for="id_<?php echo $cat->id; ?>">
                               
                                  <?php echo $cat->catname; ?>
                                </label>
                                </li>
                                <?php  }
                                } ?>
                              
                              </ul>
                              
                          </div>
                        
                        </div>
                      </div>

                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 v-flex-right">
                        <div class="relative">
                          <h6 class="font-size-16 color999 fontfamily-medium mb-20"><?php echo $this->lang->line('Filter_star'); ?> </h6>  

                          <div class="d-flex mb-3">
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

                          <div class="d-flex mb-3">
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

                          <div class="d-flex mb-3">
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

                          <div class="d-flex mb-3">
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

                          <div class="d-flex mb-3">
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
                          </div>
                        </div>
                      </div>
                    </div>
                    <input type="hidden" id="serch_by_name_or_review" name="serch_by_name_or_review">
                   </form>
                  </div>
                </div>
              </div>

              <div class="relative mt-0 mb-60 bgwhite border-radius4 box-shadow1 py-3 pt-4 pl-20 pr-20">
                <div class="row">
                 <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8">
                  <div class="search-reting-parent">
                    <h3 class="color333 fontfamily-medium font-size-20 mb-3"><?php echo $this->lang->line('Rating_&_Reviews'); ?> </h3>
                    <div class="form-group search-teting-input">
                      <input type="text" placeholder="Nach Inhalt oder Verfasser durchsuchen" class="serch_by_name_or_review">
                    </div>
                  </div>
                  <div id="div_review">
                    <?php if(!empty($allreviews)){ //echo '<pre>'; print_r($allreviews); die;
                      foreach($allreviews as $rev){ ?>
                    <div class="relative">
                      <div class="relative border-b pb-3 pt-3">
                        <div class="clear mb-3">

                          <?php for ($i=0; $i < 5; $i++){ 
                            if($i < $rev->rate)  
                              echo"<i class='fas fa-star colororange mr-1'></i>";
                            else
                              echo "<i class='far fa-star colororange mr-1'></i>";
                            } ?>

                          <span class="color999 font-size-14 fontfamily-regular float-right"><?php  $timestamp = strtotime($rev->created_on); 
                              echo time_passed($timestamp);
                          ?></span>
                        </div>
                        <p class="color666 fontfamily-regular font-size-14" style="-webkit-line-clamp:2; word-wrap: break-word;">
                          <?php echo nl2br($rev->review); ?>
                        </p>
                        <p>                      
                        <?php if($rev->anonymous == 1)
                                echo "<span class='font-size-14 fontfamily-medium color333'>Anonym</span>";
                                else{
                                  echo "<span class='font-size-14 fontfamily-medium color333 mb-10'>".$rev->first_name."</span>";
                                } ?>
                          <span class="font-size-14 color999 fontfamily-regular"> - <?php echo $this->lang->line('Treated_by'); ?> <?php echo $rev->fname; ?></span></p>
                        <div class="d-flex">
                        <?php echo $this->lang->line('Services_Booked'); ?> : <?php echo get_servicename_with_sapce($rev->booking_id);  ?>
                          <?php
                          $m_reply=getselect_row('st_review','id,review,merchant_id',array('review_id' =>$rev->id,'created_by' => $rev->merchant_id)); 
                          if(!empty($m_reply)){
                              $edit_btn='';
                              $reply_btn='none';
                            }else{
                              $edit_btn='none';
                              $reply_btn='';
                            }

                          ?>
                            <div id="show_edit_<?php echo $rev->id; ?>" class="ml-auto" style="display:<?php echo $edit_btn; ?>"><a href="javascript:void(0);" class="font-size-14 fontfamily-regular colororange mb-10 text-underline a_hover_orange ml-auto reply_click" id="<?php echo $rev->id; ?>"><?php echo $this->lang->line('Edit'); ?></a></div>
                           <div id="show_reply_<?php echo $rev->id; ?>" class="ml-auto" style="display:<?php echo $reply_btn; ?>"><a href="javascript:void(0);" class="font-size-14 fontfamily-regular colororange mb-10 text-underline a_hover_orange ml-auto reply_click" id="<?php echo $rev->id; ?>"><?php echo $this->lang->line('Reply'); ?></a></div>
                          
                          
                        </div>
                        <?php 
                        $dis='none';
                        if(!empty($m_reply))
                          $dis=''; ?>
                        <div class="accordion pb-2" id="show_reply_div<?php echo $rev->id; ?>" style="display: <?php echo $dis;?>">
                          <div class="accordion-group">
                            <div class="accordion-heading p-3 relative">
                              <!-- <a class="accordion-toggle color333 fontfamily-medium font-size-16 a_hover_333 display-ib relative collapsed" data-toggle="collapse" data-parent="" href="#collapsev_<?php echo $rev->id; ?>">
                                Comment From <?php //echo $m_reply->b_name; ?>
                              </a> -->
                              <a class="color333 fontfamily-medium font-size-14 a_hover_333 display-ib relative">
                              Kommentar von <?php echo $this->session->userdata('business_name'); ?>
                              </a>
                            </div>
                            <!-- <div id="collapsev_<?php echo $rev->id; ?>" class="accordion-body collapse"> -->
                            <div id="collapsev_<?php echo $rev->id; ?>">
                              <div class="accordion-inner" id="append_row_<?php echo $rev->id; ?>">
                               <?php if(!empty($m_reply)){ //foreach($m_reply as $rep){ ?>
                                <div class="d-flex py-2 px-3" id="remove_reply_<?php echo $m_reply->id; ?>">
                                    <p class="fontfamily-regular color666 font-size-14 mb-0"><?php echo $m_reply->review; ?>   
                                    </p>
                                    <a href="javascript:void(0);" class="absolute delete_reply" style="right:1rem;" id="<?php echo $m_reply->id;?>" data-id="<?php echo $rev->id; ?>">
                                      <img src="<?php echo base_url('assets/frontend/images/delete-color-icon.svg'); ?> ">
                                    </a>
                                </div>
                               <?php } //} ?>
                              </div>
                            </div>
                          </div>
                        </div>
                       

                        <div class="massage-box-v_<?php echo $rev->id; ?>" style="display: none;">
                          <p class="font-size-16 color333 fontfamily-medium mt-10 mb-2"><?php echo $this->lang->line('Your_Reply'); ?></p>
                          <div class="form-group">
                            <textarea class="form-control scroll-effect height90v" placeholder="<?php echo $this->lang->line('Enter_here'); ?>" id="mer_reply_<?php echo $rev->id; ?>" style="min-height: 90px;max-height:90px;"><?php echo isset($m_reply->review)?$m_reply->review:''; ?></textarea>
                          </div>
                          <label for="identity" id="err_<?php echo $rev->id; ?>" generated="true" class="error"></label>
                          <div class="text-right">
                            <button id="<?php echo $rev->id; ?>" class="btn widthfit height44v width150v merchant_reply"><?php echo $this->lang->line('Submit'); ?></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php 
                      }
                    }
                    else{ ?>
                   <div class="text-center" style="padding-bottom: 45px;">
              <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;"> Bisher wurden keine Bewertungen abgegeben.</p></div>
                    <?php } ?>
                   

                  <?php if($total > 5){ ?>
                    <div id="load-1" class="text-center"><a href="javascript:void(0)" data-id="1"  class="colorcyan a_hover_cyan text-underline font-size-18 fontfamily-medium mt-4 mb-3 display-ib loadMoreReview"><?php echo $this->lang->line('Load_More'); ?></a></div>
                  <?php } ?>

                    </div>
                  </div>
                </div>
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
            <p class="fontfamily-medium color333 font-size-18 mb-40">Are you really want to delete this Newsletter Template. Please confirm by clicking on delete button.</p>
            <input type="hidden" id="news_letter" name="news_letter">
            <button id="remove_newsLetter" class="btn widthfit"><?php echo $this->lang->line('Delete'); ?></button>
          </div>
        </div>
      </div>
    </div>

<?php $this->load->view('frontend/common/footer_script');  ?>

<script type="text/javascript">
      $(function() {
        $(window).on("scroll", function() {
            if($(window).scrollTop() > 90) {
                $(".header").addClass("header_top");
            } else {
                //remove the background property so it comes transparent again (defined in your css)
               $(".header").removeClass("header_top");
            }
        });
      });

      $(document).ready(function(){
		  
		$(document).on('keyup','.serch_by_name_or_review',function(){
			    $("#serch_by_name_or_review").val($(this).val());
			    short_rating();
			});  

        $(document).on('click','.reply_click', function(){
            var id=$(this).attr('id');
            $(".massage-box-v_"+id).toggle();
        });
        $(document).on('click','.ratingshort', function(){
           short_rating();
        });
         $(document).on('click','.review_filter_all', function(){
            $('.review_filter' ).each( function() {
              this.checked = false;
              });
          $('#close_multi').trigger('click');
          short_rating();
        });
        $(document).on('click','.review_filter', function(){
            $('.review_filter_all' ).each( function() {
            this.checked = false;
            });
          short_rating();
        });
        $(document).on('click','.loadMoreReview', function(){
            loading();  
            var loadid=$("#loadmore_id").val();
            $.ajax({
                url: base_url+"merchant/loadreviewfilter",
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
         $(document).on('click','.merchant_reply', function(){
            var id=$(this).attr('id');
            var reply=$('#mer_reply_'+id).val();
            if(reply ==""){
              $('#err_'+id).html('<i class="fas fa-exclamation-circle mrm-5"></i> Bitte Antwort eingeben');
              return false;
            }
            else
              $('#err_'+id).html('');


            $(".massage-box-v_"+id).css('display','none');
          
            loading();  
            $.ajax({
                url: base_url+"merchant/replyonreview",
                type: "POST",
                data:{reviewid:id,reply:reply},
                success: function (data) {
                  var obj = jQuery.parseJSON( data );
                   if(obj.success=='1'){
                    //$('#div_review').html(response);
                   
                    $("#show_reply_div"+id).show();
                    //$("#append_row_"+id).append(obj.html);
                    $("#append_row_"+id).html(obj.html);
                    //$(".massage-box-v_"+id).toggle();
                    //$('#mer_reply_'+id).val('');
                    $('#err_'+id).html('');
                    $('#reply_edit_div').html(obj.text);
                    $('#show_edit_'+id).show();
                    $('#show_reply_'+id).hide();
                     }
                    //$('#load-'+loadid).remove();
                
                  
                  unloading();  
                 }
                 
              });
            
        });

        $(document).on('click','.delete_reply', function(){
                var id = $(this).attr('id');
                var rid= $(this).attr('data-id');
                loading();  
            $.ajax({
                url: base_url+"merchant/replydelete",
                type: "POST",
                data:{replyid:id,review:rid},
                success: function (data) {
                  var obj = jQuery.parseJSON( data );
                   if(obj.success=='1'){
                        $("#remove_reply_"+id).remove();
                        //if(obj.count == 0)
                        $("#show_reply_div"+rid).hide();
                     
                        $('#show_edit_'+rid).hide();
                        $('#show_reply_'+rid).show();
                        $("#mer_reply_"+rid).val('');
                        $("#reply_click").trigger('click');
                     }                
                  unloading();  
                 }
                 
              });
        });
        

        /*$("#riply-text1").click(function(){          
          $(".massage-box-v1").toggle();
        });
        $("#riply-text2").click(function(){          
          $(".massage-box-v2").toggle();
        });
        $("#riply-text3").click(function(){          
          $(".massage-box-v3").toggle();
        });
        $("#riply-text4").click(function(){          
          $(".massage-box-v4").toggle();
        });*/

      });


      function short_rating()
      {
        loading();  
          $.ajax({
              url: base_url+"merchant/reviewfilter",
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
