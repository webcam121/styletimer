<?php $this->load->view('frontend/common/header'); ?>
<style type="text/css">
.page-item{display:inline-block;}
.page-item a{
    position: relative;
    display: block;
    height: 26px;
    width: 26px;
    padding: 0rem;
    margin: 0rem 2px;
    color: #333333;
    background-color: transparent;
    border: 1px solid transparent;
    border-radius: 4px;
    text-align: center;
    line-height: 26px;

}
.unfvtClass{
    float: left;
    font-size: 1rem;
    color: #212529;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: .25rem;
}
</style>

	<section class="pt-120 clear user_booking_list_section1">
      <div class="container">
        <div class="row">
        
          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
           <div class="alert alert-danger absolute vinay top w-100 mt-15 alert_message alert-top-0" id="error_message" style="display: none;">
              <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>
              <span id="alert_message"> </span>
            </div>
            <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-10">
              <div class="pt-20 pb-20 pl-30 relative top-table-droup">
                  <span class="color999 fontfamily-medium font-size-14"><?php echo $this->lang->line('Show'); ?></span>
                  <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-20"> 
                    <button data-toggle="dropdown" style="line-height: 30px;" class="btn btn-default dropdown-toggle mss_sl_btn "><?php if(isset($_GET['limit'])){ echo $_GET['limit']; }else{ echo '10'; } ?></button>
                    <ul class="dropdown-menu mss_sl_btn_dm widthfit80 noclose">
                      <li class="radiobox-image">
                        <input type="radio" id="id_14" name="cc" class="shortMyfav" value="10">
                        <label for="id_14">10</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_15" name="cc" class="shortMyfav" value="20">
                        <label for="id_15">20</label>
                      </li>
                      <li class="radiobox-image">
                        <input type="radio" id="id_16" name="cc" class="shortMyfav" value="30">
                        <label for="id_16">30</label>
                      </li>
                    </ul>
                </div>
                <span class="color999 fontfamily-medium font-size-14"><?php echo $this->lang->line('Entries'); ?></span>                
              </div>
           
           <?php if(!empty($favourite)){ ?>
              <div class="my-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th class="text-left pl-4" style="padding-left:1.875rem!important">SALON</th>
                      <!-- <th class="text-left">DATUM</th> -->
                      <th class="text-left">AKTION</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  foreach($favourite as $row){
					       	$time = new DateTime($row->created_on);
                 		$date = $time->format('d.m.Y');
                    $time = $time->format('H:i');
                    $sids=array();
                    if(!empty($row->sercvices)){
                      foreach($row->sercvices as $ser){ 
                          $sids[]=$ser->subcategory_id;
                         }
                          $servids=implode(',',$sids);
                    }
                    else
                      $servids='';

                    if($row->image =='')
                      $img=base_url('assets/frontend/images/user-icon-gret.svg');
                    else
                      $img=base_url('assets/uploads/banners/').$row->id.'/'.$row->image;
					?>
                    <tr>
                      <td class="font-size-14 color666 fontfamily-regular cursor-p text-left salon_profile_click" data-id="<?php echo url_encode($row->id); ?>" id="<?php echo $row->id; ?>">
                        <img src="<?php echo $img; ?>" style="margin-right: 5px;" class="width30 border-radius50">
                        <input type="hidden" id="servicemch<?php echo $row->id; ?>" data-service="<?php echo url_encode($servids); ?>">
                        <?php echo $row->business_name;?>
                        </td>
                      <!-- <td class="text-left font-size-14 color666 fontfamily-regular cursor-p salon_profile_click" data-id="<?php echo url_encode($row->id); ?>" id="<?php echo $row->id; ?>"><?php echo $date.' '.$time; ?></td> -->
                     
                      <td class="text-left">    
                        <div class="show unfvtClass">
                           <a class="dropdown-item color666 font-size-14 fontfamily-regular favourite_click" href="#" id="<?php echo url_encode($row->id); ?>" data-toggle="modal" data-target="#booking-cencel-table"><?php echo $this->lang->line('Unfavourite'); ?></a> 
                        </div>                    
                        <!-- <div class="dropdown">
                          <div class="" data-toggle="dropdown">
                            <img src="<?php echo base_url('assets/frontend/images/table-more-icon.svg'); ?>">
                          </div>
                          <div class="dropdown-menu widthfit">
                           <a class="dropdown-item color666 font-size-14 fontfamily-regular favourite_click" href="#" id="<?php echo url_encode($row->id); ?>" data-toggle="modal" data-target="#booking-cencel-table">Unfavourite</a> 
                           
                            
                            </div>
                          </div> -->

                        </div>                      
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;">
                     <?php if($this->pagination->create_links()){ echo $this->pagination->create_links(); } ?>
                    </ul>
                 </nav>
                 
              </div>
              <?php }else{ ?>
                <div class="text-center" style="padding-bottom: 45px;">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;"> Du hast noch keine Favoriten hinzugefügt</p></div>
                <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </section>

     <!-- modal start -->
    <div class="modal fade" id="booking-cencel-table">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="close_cancel">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <!-- <img src="<?php //echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>"> -->
            <p class="font-size-18 color333 fontfamily-medium mt-10 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">Bist du sicher, dass du diesen Salon aus deinen Favoriten entfernen möchtest?  </p>

            <div class="text-center mt-30">
              <input type="hidden" id="favouriteid" name="favouriteid" value="">
              <button type="button" id="un_favourite" class="btn btn-large widthfit">Bestätigen</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->

<?php $this->load->view('frontend/common/footer'); ?>
