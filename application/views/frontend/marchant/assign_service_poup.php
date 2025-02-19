 <!-- modal start -->
    <div class="modal fade add_service_popup" id="add_service_popup">
      <!-- custom_scroll -->
      <div class="modal-dialog modal-lg modal-dialog-centered "  role="document">    
        <div class="modal-content text-center" >  
        <a id="close_box" href="#" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a> 
          <div class="modal-body pt-20 pl-0 pr-0 ">
            <div class="px-3">
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="pl-20 pr-20 ">
                    <div class="form-group relative">
                      <input type="search" name="search" id="search_servce" class="form-control" placeholder="Suchen..">
                      <a href="#" class="search-crose" id="search-crose_btn"><img src="<?php echo base_url('assets/frontend/images/search-crose-small-icon.svg'); ?>" class="width10"></a>
                    </div>  
                  </div>
                </div>
              </div>
            </div>
              <div class="" id="service_html">
              
              </div>
              <div class="text-center mt-20">
                <button class="btn btn-large widthfit" id="close_service_modal">Speichern</button>
              </div>
              
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->
