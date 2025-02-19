<?php $this->load->view('frontend/common/header'); ?>
<?php $this->load->view('frontend/common/editer_css'); ?>       
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
.alert.alert-success.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0.text-center.display-n,
.alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0.display-n,
.alert.absolute.vinay.top.w-100.mt-15.alert_message.alert-top-0{
  top:-74px !important;
  z-index:1050;
} 
   .fr-box.fr-basic .fr-element{
    min-height: 220px!important;
  }
  .fr-wrapper {
    height: 250px !important;
  }
  .my-table .table tr td:first-child {
    padding-left: 10px;
}

</style>
<div style="display:none;" id="b_list_page"></div>
<div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar');   //print_r($this->session->flashdata()); die; ?>
 
      <div class="right-side-dashbord w-100 pl-30 pr-30 ">
          <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-20 relative">
            <div class="alert alert-danger absolute vinay top w-100 mt-15 alert_message alert-top-0" id="error_message" style="display: none; top:-30px;">
              <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>
              <span id="alert_message"></span>
            </div>
           <?php $this->load->view('frontend/common/alert'); ?>
            <div class="pt-20 pb-20 pl-30 pr-20 relative d-flex">
              <div class="relative my-new-drop-v display-ib">
               <!--  <span class="color999 fontfamily-medium font-size-14">Status</span> -->
                <div class="btn-group multi_sigle_select widthfit80 mr-20 display-ib"> 
                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn "><?php echo $this->lang->line('all_bookings'); ?></button>  <!-- Select Status -->
                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
                    <li class="radiobox-image">
                      <input type="radio" id="id_44b" checked="checked" name="booking_st" class="book_status_list" value="all">
                      <label for="id_44b"><?php echo $this->lang->line('all_bookings'); ?></label>
                    </li>
                    <li class="radiobox-image"> 
                      <input type="radio" id="id_45b" name="booking_st" class="book_status_list" value="upcoming">
                      <label for="id_45b"><?php echo $this->lang->line('Upcoming_bookings'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_46b" name="booking_st" class="book_status_list" value="recent">
                      <label for="id_46b"><?php echo $this->lang->line('Recent_Bookings'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_47b" name="booking_st" class="book_status_list" value="cancelled">
                      <label for="id_47b"><?php echo $this->lang->line('Cancelled_bookings'); ?></label>
                    </li>
                  </ul>
                </div>                
              </div>
              <!-- filter code new start-->
              <div class="relative my-new-drop-v display-ib" id="booking-date-filter">
               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('select_filter'); ?></button>
                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" style="max-height: none;height: auto !important;overflow-x: auto;">					
                    <li class="radiobox-image">
                      <input type="radio" id="id_d34" name="filter" class="filterby_days_list" value="day">
                      <label for="id_d34"><?php echo $this->lang->line('today'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d34y" name="filter" class="filterby_days_list" value="yesterday">
                      <label for="id_d34y"><?php echo $this->lang->line('yesterday'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="id_d35" name="filter" class="filterby_days_list" value="current_week">
                      <label for="id_d35"><?php echo $this->lang->line('current_week'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d36" name="filter" class="filterby_days_list" value="current_month">
                      <label for="id_d36"><?php echo $this->lang->line('current_month'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d37" name="filter" class="filterby_days_list" value="current_year">
                      <label for="id_d37"><?php echo $this->lang->line('current_year'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="id_d34cwtd" name="filter" class="filterby_days_list" value="cwtd">
                      <label for="id_d34cwtd"><?php echo $this->lang->line('current_week_to_day'); ?></label>
                    </li>                    
                     <li class="radiobox-image">
                      <input type="radio" id="id_d34cmtd" name="filter" class="filterby_days_list" value="cmtd">
                      <label for="id_d34cmtd"><?php echo $this->lang->line('current_month_to_day'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="id_d34cqtd" name="filter" class="filterby_days_list" value="cqtd">
                      <label for="id_d34cqtd"><?php echo $this->lang->line('current_quarter_to_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d34cytd" name="filter" class="filterby_days_list" value="cytd">
                      <label for="id_d34cytd"><?php echo $this->lang->line('current_year_to_day'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="id_d036" name="filter" class="filterby_days_list" value="last_month">
                      <label for="id_d036"><?php echo $this->lang->line('last_month'); ?></label>
                    </li>
                 
                    <li class="radiobox-image">
                      <input type="radio" id="id_d3430" name="filter" class="filterby_days_list" value="30">
                      <label for="id_d3430"><?php echo $this->lang->line('last_30_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d3490" name="filter" class="filterby_days_list" value="90">
                      <label for="id_d3490"><?php echo $this->lang->line('last_90_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d037" name="filter" class="filterby_days_list" value="last_year">
                      <label for="id_d037"><?php echo $this->lang->line('last_year'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="id_d38all" name="filter" class="filterby_days_list" value="all">
                      <label for="id_d38all"><?php echo $this->lang->line('all_time'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d38" name="filter" class="filterby_days_list" value="date">
                      <label for="id_d38"><?php echo $this->lang->line('serach_by_date'); ?></label>
                    </li>
                  </ul>
                </div>                
              </div>
              <div class="form-group relative my-new-drop-v mr-20 display-ib">
                  <input type="text" name="sch" placeholder="<?php echo $this->lang->line('Search_Name_client'); ?>" value="" class="widthfit310v cust_search" id="sch_data_cuss">
                </div>

              <div class="form-group date_pecker display-ib mr-20  hide filterdate" style="max-width: 140px;">
                <label class="inp">
                  <input type="text" id="start_date1" name="" placeholder="Start" value="" class="form-control">
                </label>
               <span class="error" id="start_error"></span>
              </div>

              <div class="form-group date_pecker display-ib hide filterdate" style="max-width: 140px;">
                <label class="inp">
                  <input type="text" id="end_date1" name="" placeholder="Ende" value="" class="form-control">
                </label>
                <span class="error" id="end_error"></span>
              </div>
              <div class="display-ib hide filterdate"><i id="search_filter1" class="fas fa-search colororange mt-2 ml-2 font-size-24"></i></div>


              <!-- filter code new end-->
              <div class="relative my-new-drop-v display-ib ml-auto mr-2">
                <!-- <span class="color999 fontfamily-medium font-size-14">Show</span> -->
                <div class="btn-group multi_sigle_select widthfit80 mr-2 ml-20"> 
                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn "><?php if(isset($_GET['limit'])){ echo $_GET['limit']; }else{ echo '10'; } ?></button>
                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80">
                    <li class="radiobox-image">
                      <input type="radio" id="id_14" name="limit" class="change_limit" value="10">
                      <label for="id_14">10</label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_15" name="limit" class="change_limit" value="20">
                      <label for="id_15">20</label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_16" name="limit" class="change_limit" value="30">
                      <label for="id_16">30</label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_1all" name="limit" class="change_limit" value="">
                      <label for="id_1all"><?php echo $this->lang->line('show_all_pagin'); ?></label>
                    </li>
                  </ul>
                </div>
                <!-- <span class="color999 fontfamily-medium font-size-14">Entries</span> -->  
              </div>
              <a href="javascript:void(0);" data-id="csv" class="display-ib cursol-p m-1 export_filterreport"><img class="width30" src="<?php echo base_url('assets/frontend/images/csv-icon.svg'); ?>"></a>
              <a href="javascript:void(0)" data-id="excel" class="display-ib cursol-p m-1 export_filterreport"><img class="width30" src="<?php echo base_url('assets/frontend/images/exel-icon.svg'); ?>"></a>
            </div>
               
              <div class="my-table booking-table">
                <table class="table">
                  <thead>
                    <tr>
					 <th class="pl-5"><a href="javascript:void(0)" class="color333 shorting" data-short="asc" id="us2.first_name"><?php echo $this->lang->line('Customer'); ?> 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center"><a href="javascript:void(0)" class="color333 shorting" data-short="asc" id="us.first_name"><?php echo $this->lang->line('Employee'); ?> 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center"><a href="javascript:void(0)" class="color333 shorting" data-short="asc" id="st_booking.booking_time"><?php echo $this->lang->line('Date'); ?> 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center"><?php echo $this->lang->line('Time'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Service'); ?></th>
                      <th class="text-center"><a href="javascript:void(0)" class="color333 shorting" data-short="asc" id="st_booking.total_price"><?php echo $this->lang->line('Price'); ?> 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                      </th>
                      <th class="text-center"><a href="javascript:void(0)" class="color333 shorting" data-short="asc" id="st_booking.total_minutes"><?php echo $this->lang->line('Duration'); ?> 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center"><a href="javascript:void(0)" class="color333 shorting" data-short="asc" id="st_booking.status"><?php echo $this->lang->line('Status'); ?> 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a></th>
                      <th class="text-center"><?php echo $this->lang->line('Action'); ?></th>
                    </tr>
                  </thead>
                  <tbody id="all_listing_mer">
                     
                  </tbody>
                </table>
                   <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;"  id="pagination">
                     
                    </ul>
                 </nav>
              </div>
              <input type="hidden" id="shortby" value="desc">
              <input type="hidden" id="orderby" value="st_booking.booking_time">
                
          </div>
        <!-- dashboard right side end -->        
    </div>
    <input type="hidden" id="action_from" name="" value="list">
</div>

<!-- modal start -->
<!--
    <div class="modal fade" id="service-cencel-table">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="close_cancel">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg'); ?>" class="popup-crose-black-icon">
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">Are you sure you really want to cancel this booking?    </p>
            <input type="hidden" id="bookingid" name="bookingid" value="">
            <input type="hidden" id="check_access" name="" value="merchant">
            <button type="button" id="cancel_booking" class="btn btn-large widthfit">Cancel</button>
          </div>
        </div>
      </div>
    </div>
-->

     <!-- modal start -->
    <div class="modal fade" id="service-complete-table">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="conf_close">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/icon_cmp.png'); ?>">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;"> Are you sure you want to mark complete this booking</p>
             <input type="hidden" id="book_conf" name="book_conf" value="">
            <button type="button" class=" btn btn-large widthfit" id="booking_done">ok</button>
          </div>
        </div>
      </div>
    </div>

     <!-- modal start -->
<!--
    <div class="modal fade" id="reshedule-complete-table">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="conf_close">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg'); ?>" class="popup-crose-black-icon">
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/table-rebook-icon-popup.svg'); ?>">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2; display: inline-block;">You have successfully rescheduled this booking. An Email has been sent to the Users email address</p>
             <button type="button" class=" btn btn-large widthfit" data-dismiss="modal">ok</button>
          </div>
        </div>
      </div>
    </div>
-->



    <!-- modal start -->
<!--
    <div class="modal fade" id="service-noshow-table">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal" id="close_cancel">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.svg'); ?>" class="popup-crose-black-icon">
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/table-cencel-icon-popup.svg'); ?>">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">Are you sure you really want to no show this booking?    </p>
            <input type="hidden" id="noshowbook_id" name="noshowbook_id" value="">
            <button type="button" id="noshow_booking" class="btn btn-large widthfit">Ok</button>
          </div>
        </div>
      </div>
    </div>
-->

<?php $this->load->view('frontend/common/footer_script'); 
  $this->load->view('frontend/common/editer_js');     


  ?>
<script type="text/javascript">
	var date = new Date();
   /* var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('.datepicker').datepicker({
         uiLibrary: 'bootstrap4',
         minDate:today
       });*/

     $('#start_date1').datepicker({
         uiLibrary: 'bootstrap4',
         locale: 'de-de',
         format: 'dd.mm.yyyy'
       });
      $('#end_date1').datepicker({
         uiLibrary: 'bootstrap4',
          locale: 'de-de',
         format: 'dd.mm.yyyy'
       }); 
       $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<script type="text/javascript">
      $('.clockpicker').clockpicker();

    $(document).on('click','#chg_date',function () {
      $('.today gj-cursor-pointer')
        .css('background-color', '#000000');
    });
    
  
   $(document).on('blur','#chg_date',function () {
    setTimeout(function(){ 
		   var date=$("#chg_date").val();
       var reseid=$("#reseid").val();
		   var bkid=$("#reSchedule_id").val();
		  loading();
	   // var urls=$('#select_url').val();
     var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
       $.post(base_url+"merchant/get_opning_hour",{date:date,eid:reseid,bk_id:bkid} , function( data ) {
		   //console.log(data);
             var obj = jQuery.parseJSON( data );
            if(obj.success=='1'){
				$("#date_err").html('');
				$("#chg_time").html(obj.html);
			}else{
				$("#date_err").html(obj.message);
				//$(".alert_message").css('display','block');
				
				//location.reload();
			
			  }
			
  	        });
	   unloading();
	   },500);
	  });   

$(document).ready(function(){

  //alert(temp_id);
  getBookingList();
  

    
$(document).on('change',".change_limit",function(){
  
    var url=onsearch_filter();
    if(url)
     getBookingList(url);
    else
      getBookingList();
    //$(".shodropdown").removeClass('show');
  });


 $(document).on('click',"#pagination .page-item a",function(){
	 //alert('f'); return false;
  var url=$(this).attr('href');
  
  if(url!=undefined){
    getBookingList(url);
    }
     window.scrollTo(0, 350);
  //$("#listingTabl").focus();  
  return false; 
    // alert(url);
  
  });     
  
 $(document).on('click','.shorting',function(){
	  
	  $("#orderby").val($(this).attr('id'));
      $("#shortby").val($(this).attr('data-short'));
 
       if($(this).attr('data-short')=='asc'){
		  $(this).attr('data-short','desc');
		}
	  else{
		  $(this).attr('data-short','asc');
		  }	

    var url=onsearch_filter();
    if(url)
        getBookingList(url);
    else
        getBookingList();

  });    

$(document).on('change','.filterby_days_list',function(){
    var value = $(this).val();
    if(value =="date"){
      $('.filterdate').css('display','inline-block');
      return false;
    }
    else
      $('.filterdate').hide();

    var status =$('input[name=booking_st]:checked').val();
    if (typeof status == 'undefined')
      status ='';

    var url=base_url+'merchant/getbokkinglist_ajax?short='+value+'&status='+status;
    getBookingList(url);
    
});
$(document).on('change','.book_status_list',function(){
    var status = $(this).val();
    if(status =="all"){
      // $('.filterby_days').prop('checked', false);
      //$('#btn_text').text('Select Filter');
      //$('.filterdate').hide();
    }
    $("#booking-date-filter").css('display', 'inline-block');
    if (status == 'upcoming') {
      $("#booking-date-filter").css('display', 'none');
    }
    var start = $('#start_date1').val();
    var end = $('#end_date1').val();
    var value =$('input[name=filter]:checked').val();
    if (typeof value == 'undefined')
      value ='';

    var url=base_url+'merchant/getbokkinglist_ajax?short='+value+'&status='+status+'&start_date='+start+'&end_date='+end;
    getBookingList(url);
    
});
$(document).on('click','#search_filter1',function(){
   var token = true;
   var start = $('#start_date1').val();
   var end = $('#end_date1').val();
   if(start == ""){
      $('#start_error').html('please select start date');
      token = false;
   }
   else
      $('#start_error').html('');

   if(end == ""){
      $('#end_error').html('please select end date');
      token = false;
   }
   else
      $('#end_error').html('');
    if(token == true){

    var status =$('input[name=booking_st]:checked').val();
    if (typeof status == 'undefined')
      status ='';
    //alert(status);

    var value =$('input[name=filter]:checked').val();
    if (typeof value == 'undefined')
      value ='';

    var url=base_url+'merchant/getbokkinglist_ajax?short='+value+'&status='+status+'&start_date='+start+'&end_date='+end;
    getBookingList(url);
    }
});
 
$(document).on('keyup',".cust_search",function(){
  var start = '';
  var end = '';
  var order=$("#orderby").val();
  var shortby=$("#shortby").val();
  var search = $.trim($("#sch_data_cuss").val());
  var status =$('input[name=booking_st]:checked').val();
    if (typeof status == 'undefined')
      status ='';
    
    var value =$('input[name=filter]:checked').val();
    if (typeof value == 'undefined')
      value ='';

    if(value =='date'){
      var start = $('#start_date').val();
      var end = $('#end_date').val();
      }

  var url = base_url+'merchant/getbokkinglist_ajax?short='+value+'&status='+status+'&start_date='+start+'&end_date='+end+'&orderby='+order+'&shortby='+shortby+'&search='+search;

     getBookingList(url);
  });

 function onsearch_filter(){
   
   var start = '';
   var end = '';
    var status =$('input[name=booking_st]:checked').val();
    if (typeof status == 'undefined')
      status ='';
    
    var value =$('input[name=filter]:checked').val();
    if (typeof value == 'undefined')
      value ='';

    
    if(value =='date'){
      var start = $('#start_date1').val();
       var end = $('#end_date1').val();
      }

    var url=base_url+'merchant/getbokkinglist_ajax?short='+value+'&status='+status+'&start_date='+start+'&end_date='+end;
    return url;
 }
    
 ///  export to csv 
$(document).on('click','.export_filterreport',function(){
  var type = $(this).attr('data-id');
  var start = '';
  var end = '';
  var order=$("#orderby").val();
  var shortby=$("#shortby").val();
  var search = $.trim($("#sch_data_cuss").val());
  var status =$('input[name=booking_st]:checked').val();
    if (typeof status == 'undefined')
      status ='';
    
    var value =$('input[name=filter]:checked').val();
    if (typeof value == 'undefined')
      value ='';

    if(value =='date'){
      var start = $('#start_date1').val();
      var end = $('#end_date1').val();
      }

    window.location.href = base_url+'merchant/booking_export_tocsv/'+type+'?short='+value+'&status='+status+'&start_date='+start+'&end_date='+end+'&orderby='+order+'&shortby='+shortby+'&search='+search;
  });


});	  

	   
 </script> 


 <!--///////////// new calender script start /////////////////-->
 <script type="text/javascript">
   
      $(function() {
        $('.scroll245').on("scroll", function() {
            if($('.scroll245').scrollTop() > 20) {
                $(".scroll245").addClass("box-shadow-bottom");
            } else {
               $(".scroll245").removeClass("box-shadow-bottom");
            }
        });
      });
      
  
   
      $(document).ready(function(){
	
		 
		  $(document).on('click','.slick-arrow',function(){
			  
			  //~ if($('.selectedDate').is(':visible')){
				  //~ alert('ds');
				  //~ }
				//~ else{
					//~ alert('dsee');
					 //~ }  
				  //alert($('input[name="date"]:checked').val());
			 
			  $(".selectMonthWeek").removeClass('display-b');
			  $(".selectMonthWeek").addClass('display-n');
			  
			  $(".currentMonthWeek").removeClass('display-n');
			  $(".currentMonthWeek").addClass('display-b');
			  // alert('sdf');
			  });
       // $('.booking_success').modal({backdrop: 'static', keyboard: false})
      

    });

  $(document).on("click","#conf_close",function(){
    var id = $(this).attr('data-bookid');
    if(id != undefined)  
    getBokkingDetail(id);        
  });  

    <?php if(!empty($_GET['bid'])){ ?>

     var id = "<?php echo $_GET['bid']; ?>";
        getBokkingDetail(id);
     <?php } ?>
   </script>
