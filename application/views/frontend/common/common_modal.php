    <style>
      #match_pass_success{
        margin-bottom:1px !important
      }
    </style>
    <!-- -------------------------------- booking detail modal satart ------------------------------------------------------------------------------- -->
     <div class="modal fade" id="booking-details-modal">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
        <div class="modal-content"> 
          <div class="modal-body pt-3 mb-0 pl-3 pr-3" id="bookingDetailHtml">
  

           </div>
         </div>
       </div>
      <input type="hidden" id="action_from" name="" value="detail">
    </div>

    <!-- -------------------------------- booking detail modal end ------------------------------------------------------------------------------- -->
  
    <!-- -------------------------------- booking no show confirmation satart ------------------------------------------------------------------------------- -->
  <div class="modal fade" id="service-noshow-table">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn commoncancel_cal" data-bid="" data-dismiss="modal" id="close_cancel">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
          <picture class="">
            <source srcset="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.webp'); ?>" type="image/webp">
            <source srcset="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>" type="image/svg">
            <img src="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>">
          </picture>
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">Are you sure you really want to no show this booking?    </p>
            <input type="hidden" id="noshowbook_id" name="noshowbook_id" value="">
            <button type="button" id="noshow_booking" class="btn btn-large widthfit">Ok</button>
          </div>
        </div>
      </div>
    </div>
  <!-- -------------------------------- booking no show confirmation end ------------------------------------------------------------------------------- -->
  
  <!-- -------------------------------- booking cancel confirmation satart ------------------------------------------------------------------------------- -->

 <div class="modal fade" id="service-cencel-table">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn commoncancel_cal" data-bid="" data-dismiss="modal" id="close_cancel">
          <picture class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
        <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
      </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <picture class="">
              <source srcset="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.webp'); ?>" type="image/webp">
              <source srcset="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>" type="image/svg">
              <img src="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>">
            </picture>
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">Are you sure you really want to cancel this booking?    </p>
            <input type="hidden" id="bookingid" name="bookingid" value="">
            <input type="hidden" id="check_access" name="" value="<?php echo $this->session->userdata('access'); ?>">
            <button type="button" id="cancel_booking" class="btn btn-large widthfit">Cancel</button>
          </div>
        </div>
      </div>
    </div>
<!-- -------------------------------- booking cancel confirmation satart ------------------------------------------------------------------------------- -->

<!-- -------------------------------- booking review modal satart ------------------------------------------------------------------------------- -->

 <div class="modal fade" id="reting_review">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
        <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
      </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-4 pr-4 bglightgreen1">
            <div id="addrratingreviewForm">
                 
                   
          </div>
      </div>
    </div>
  </div>
</div>
<!-- end modal --> 
<!-- -------------------------------- booking review modal end ------------------------------------------------------------------------------- -->

<!-- -------------------------------- Salon view client profile------------------------------------------------------------------------------- -->
<div class="modal fade" id="profile-client-view-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
    <div class="modal-content" id="clientViewProfileHtml"> 
    
    </div>
  </div>
</div>                          
<!-- -------------------------------- Salon view client profile end ------------------------------------------------------------------------------- -->


<!-- -------------------------------- Header search modal start ------------------------------------------------------------------------------- -->


<div class="modal fade" id="search-wala-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
    <div class="modal-content">      
      <a href="#" class="crose-btn" data-dismiss="modal" id="conf_close">
      <picture class="popup-crose-black-icon">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
                <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
              </picture>
      </a> 
<!-- 
      <a href="<?php echo base_url('merchant/dashboard'); ?>" class="crose-btn" >
        <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg') ?>" class="popup-crose-black-icon">
      </a> -->

      <div class="modal-body p-0">
      <div class="search-icon-wala-popup">
        <div class="full-popup ">
            
            <div class="relative h-100 pl-3 pr-3 pt-4 pb-3">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                  <input type="text" class="new-input-vinu-bottom" placeholder="<?php echo $this->lang->line('What-are-looking'); ?> ?" id="search_client" name="search_client">
                </div>
                <span class="color666 d-block mb-2"><?php echo $this->lang->line('search-by-client'); ?></span>
               </div>
              <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <p class="color333 fontsize-14 fontfamily-medium"><?php echo $this->lang->line('Upcoming-appointments'); ?></p>
                <div id="booking_filter_list" class="scroll-effect h-100 max-height530">
                 
                </div>
              </div>
              <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <p class="color333 fontsize-14 fontfamily-medium"><?php echo $this->lang->line('Clients(recentlyadded)'); ?></p>
                <div id="client_filter_list" class="scroll-effect h-100 max-height530">
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
      </div>
    </div>
  </div>
</div>
<!-- -------------------------------- Header search modal end ------------------------------------------------------------------------------- -->
<!-- -------------------------------- Add note from clicnt profile by salon start ------------------------------------------------------------------------------- -->


<div class="modal fade" id="booking-note-modal">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" id="close_pop" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
        <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
      </picture>
          </a>
          <div class="modal-body pt-40 mb-30 pl-40 pr-40">
            <p class="font-size-18 color333 fontfamily-medium mb-20 bnotetitle">
            Buchungsnotiz
            </p>
            <!-- <form id="frmNotesS" method="post" action=""> -->
            <div class="form-group" id="txtnote_validate">
                <textarea class="form-control height90v custom_scroll" id="mbnote" placeholder="&nbsp;" value="" name=""></textarea>
            </div>
            <div class="text-center mt-30 display-b">
                <input type="hidden" id="bookingnote_id" name="mbooking_id" value="">
                <button class=" btn btn-large widthfit" style="text-transform: unset;" id="bookingnotesave"><?php echo $this->lang->line('save_notes'); ?></button>
              </div>
           <!--  </form> -->
          </div>
        </div>
      </div>
    </div>

<div class="modal fade" id="add-noteFromPoup">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" id="close_pop" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
        <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
      </picture>
          </a>
          <div class="modal-body pt-40 mb-30 pl-40 pr-40">
            <p class="font-size-18 color333 fontfamily-medium mb-20 para_text"><?php echo $this->lang->line('add_notes_for_customer'); ?></p>
            <!-- <form id="frmNotesS" method="post" action=""> -->
            <div class="form-group" id="txtnote_validate">
                   <!--  <textarea type="text" name="txtnote" id="txtnote" 
                   placeholder="&nbsp;" 
                   class="form-control custom_scroll"
                    style="min-height: 120px;max-height:120px;;width: 100%;"></textarea> -->
                     <!-- <textarea class="form-control h-100 custom_scroll cccctxtnote" 
                     data-uid="" style="" id="clientNotevalue" placeholder="&nbsp;" value=""></textarea> -->

                     <textarea class="form-control h-100 custom_scroll cccctxtnote" 
                     data-uid="" style="" id="about_salon" placeholder="&nbsp;" value=""></textarea>
                
                <label class="error display-n" id="err_note"><i class="fas fa-exclamation-circle mrm-5"></i> <?php echo $this->lang->line('Please_enter_customer_note'); ?> </label>
            </div>
            <div class="text-center mt-30 display-b">
                <input type="hidden" id="booking_ids" name="booking_id" value="">
                <button class=" btn btn-large widthfit" style="text-transform: unset;" id="savenotesBtnck"><?php echo $this->lang->line('save_notes'); ?></button>
              </div>
           <!--  </form> -->
          </div>
        </div>
      </div>
    </div>

<div class="modal fade" id="add-noteFromPoup1">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
    <div class="modal-content">      
      <a href="#" id="close_pop" class="crose-btn" data-dismiss="modal">
      <picture class="popup-crose-black-icon">
    <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
    <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
    <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
  </picture>
      </a>
      <div class="modal-body pt-40 mb-30 pl-40 pr-40">
        <p class="font-size-18 color333 fontfamily-medium mb-20 para_text"><?php echo $this->lang->line('add_notes_for_customer'); ?></p>
        <!-- <form id="frmNotesS" method="post" action=""> -->
        <div class="form-group" id="txtnote_validate">
                <!--  <textarea type="text" name="txtnote" id="txtnote" 
                placeholder="&nbsp;" 
                class="form-control custom_scroll"
                style="min-height: 120px;max-height:120px;;width: 100%;"></textarea> -->
                  <textarea class="form-control h-100 custom_scroll cccctxtnote" 
                  data-uid="" data-bid="" style="" id="clientNotevalue1" placeholder="&nbsp;" value=""></textarea>
            
            <label class="error display-n" id="err_note"><i class="fas fa-exclamation-circle mrm-5"></i> <?php echo $this->lang->line('Please_enter_customer_note'); ?> </label>
        </div>
        <div class="text-center mt-30 display-b">
            <input type="hidden" id="booking_ids" name="booking_id" value="">
            <button class=" btn btn-large widthfit" style="text-transform: unset;" id="savenotesBtnck1"><?php echo $this->lang->line('save_notes'); ?></button>
          </div>
        <!--  </form> -->
      </div>
    </div>
  </div>
</div>

<!-- -------------------------------- Add note from clicnt profile by salon end ------------------------------------------------------------------------------- -->

<div class="modal fade" id="terms_condition_modal">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
        <div class="modal-content">
        <a href="javascript:void(0)" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
        <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
        <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
      </picture>
          </a> 

          <div class="modal-body p-0" id="tems_condition_Html">
  

           </div>
         </div>
       </div>
</div>


<div id="deactivate_profile_model" class="modal" role="dialog">
  <div class="modal-dialog modal-md modal-dialog-centered" style="max-width:350px;">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
      <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
      </a>
      <div class="modal-body text-left">
          <div class="relative align-self-center w-100">
          <div class="relative">
               <form id="deleteaccount">
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                          <p class="mb-40 font-size-14 fontfamily-regular color666 text-center" style="margin-top: 31px;">Bitte bestätige die Löschung deines Accounts mit dem Code, den wir soeben an deine hinterlegte E-Mail Adresse gesendet haben.  </p>

                              <div class="form-group form-group-mb-50 height60" id="">
                                   <label class="inp">  
                                        <input type="password" id="delete_account_otp" name="otp" placeholder="&nbsp;" class="form-control">
                                        <span class="label">OTP*</span>   
                                   </label> 
                                    <p style="color:green;margin-top:0px;margin: bottom 1px;
                                    " id="match_pass_success"></p>
                                    <label id="match_pass_error" style="font-size:14px;margin-bottom:10px" generated="true" class="error">
                                   </label>                                               
                              </div>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="form-group text-center">
                              <a  href="javascript:void(0)" style="color: orangered;"  id="resendOtp" >Code erneut senden</a>
                              </div>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="form-group text-center">
                                   <button class="btn width180" type="button" id="deleteAccount">Account <span style="text-transform: lowercase;" id="lowerCase">löschen</span></button> 
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
<script>
  // $(function (){
  //     const editorInstance = new FroalaEditor('#popCustomerData', {
  //       enter: FroalaEditor.ENTER_P,
  //       key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
  //       placeholderText: null,
  //       language: 'de',
  //       events: {
  //         initialized: function () {
  //           const editor = this
  //           this.el.closest('form').addEventListener('submit', function (e) {
  //             //console.log(editor.$oel.val())
  //            // e.preventDefault()
  //           })
  //         }
  //       }
  //     })
  //   });
</script>