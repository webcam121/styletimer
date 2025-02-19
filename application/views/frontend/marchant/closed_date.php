<?php $this->load->view('frontend/common/header'); ?>
<!-- dashboard left side end -->
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
.alert_message{
  top:0px !important;
}
.editEmp{
	cursor:pointer;
	}
	
</style>
 <div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>
	
	 <div class="right-side-dashbord w-100 pl-30 pr-30">
    <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-20 relative">
          <?php $this->load->view('frontend/common/alert'); ?>
    
    <!-- <div id="successMsgChg" class="alert alert-success absolute vinay top w-100 mt-15 alert_message alert-top-0 text-center" style="display: none;">
    <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span id="alert_message_suc"></span>
    </div> -->
            <!-- tab start -->
            <ul class="nav nav-tabs new_tab_v" role="tablist">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('merchant/employee_listing'); ?>"><?php echo $this->lang->line('Employee_List'); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('merchant/employee_shift'); ?>"><?php echo $this->lang->line('Employee_Shifts'); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="<?php echo base_url('merchant/closed_date'); ?>"><?php echo $this->lang->line('close_date'); ?></a>
              </li>
            </ul>
            <div class="pt-20 pb-20 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
                <div class="display-ib ml-auto">
                 <a href="JavaScript:Void();" class="addefitclosetime" data-href="<?php echo base_url('merchant/get_close_timeform'); ?>"><button class="btn btn-large widthfit" style="text-transform: unset;"><?php echo $this->lang->line('new_close_date'); ?></button></a>
                </div>
              </div>
            
              <div id="closed_date" class="tab-pane">
                <!-- top-table-droup -->
                
               <!-- end top-table-droup -->
                <!-- calender table new -->
                  <div class="my-table closed_date calender-table pb-4">
					  <?php if(!empty($closetimes)){ ?>
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="pl-5 height56v">Zeitraum</th>
                          <th class="text-center height56v">Anzahl Tage</th>
                           <th class="text-center height56v">Mitarbeiter</th>
                          <th class="text-center height56v">Beschreibung</th>
                          <th class="text-center height56v"><?php echo $this->lang->line('Action'); ?></th>
                          
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
						  foreach($closetimes as $row){
							  
							  $date1=date_create($row->booking_time);
								$date2=date_create($row->booking_endtime);
								$diff=date_diff($date1,$date2);
								$daycount= $diff->format("%a")+1;
                           ?>  
                        <tr>
                          <td class="addefitclosetime" data-href="<?php echo base_url('merchant/get_close_timeform/'.$row->id); ?>" style="cursor:pointer;">
                            <p class="font-size-14 color666 fontfamily-regular mb-0"><?php echo substr($this->lang->line(date('l',strtotime($row->booking_time))),0,2).'. '.date('d. ',strtotime($row->booking_time)).substr($this->lang->line(date('F',strtotime($row->booking_time))),0,3).date(' Y',strtotime($row->booking_time)).' - '.substr($this->lang->line(date('l',strtotime($row->booking_endtime))),0,2).'. '.date('d. ',strtotime($row->booking_endtime)).substr($this->lang->line(date('F',strtotime($row->booking_endtime))),0,3).date(' Y',strtotime($row->booking_endtime)); ?></p>
                          </td>
                          <td class="text-center addefitclosetime" data-href="<?php echo base_url('merchant/get_close_timeform/'.$row->id); ?>" style="cursor:pointer;">
                            <p class="font-size-14 color666 fontfamily-regular mb-0"><?php echo $daycount.($daycount==1?' Tag':' Tage'); ?></p>
                          </td>
                          <td class="text-center addefitclosetime" data-href="<?php echo base_url('merchant/get_close_timeform/'.$row->id); ?>" style="cursor:pointer;">
                            <p class="font-size-14 color666 fontfamily-regular mb-0"><?php if($row->close_for==1) echo $row->first_name.' '.$row->last_name; else echo $this->lang->line('All_staff'); ?></p>
                          </td>
                          <td class="addefitclosetime" data-href="<?php echo base_url('merchant/get_close_timeform/'.$row->id); ?>" style="cursor:pointer;">
                            <p class="font-size-14 color666 fontfamily-regular mb-0 text-center overflow_elips width260v"  style="margin:auto !important;"><?php echo $row->notes; ?></p>
                          </td>
                          <td class="font-size-14 color666 fontfamily-regular text-center">
                            <a data-href="<?php echo base_url('merchant/get_close_timeform/'.$row->id); ?>" href="#" class="mr-3 ml-3 addefitclosetime">
                              <img src="<?php echo base_url('assets/frontend/images/new-icon-edit-employee.png');?>" class="width22">
                            </a>
                            <a href="JavaScript:Void();" data-href="<?php echo base_url('rebook/delete_closetime/'.url_encode($row->id)); ?>" class="deleteclosetime">
                              <img src="<?php echo base_url('assets/frontend/images/new-icon-bin.png'); ?>" class="width22">
                            </a>
                          </td>
                          </tr>
                        <?php  } ?>
                      </tbody>
                    </table>
                    <?php } else{ ?>
            <div class="text-center" style="padding-bottom: 45px;">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;"><?php echo $this->lang->line('no_listing_for_closetime'); ?></p></div>
                <?php } ?>
                  </div>
                <!-- calender table new  -->
              </div>

              <!-- dashboard right side end -->       
              </div>
             </div>

      </div>



  <?php $this->load->view('frontend/common/footer_script');  ?>
<!-- modal start -->
<div class="modal fade" id="closed_date_modal">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
    <div class="modal-content">      
      <a href="#" class="crose-btn" data-dismiss="modal">
      <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
      </a>
      <div class="modal-body pt-20 mb-10 pl-25 pr-25 relative" id="closetimeformhtml">
        
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function () {	  
var date = new Date();
    var today =new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var datepickrOption ={
		    uiLibrary: 'bootstrap4',
		    locale: 'de-de',
        format: "dd.mm.yyyy",
        minDate:today,
        weekStartDay: 1,
    };
       
       
   $(document).on('click','.closedate_limit', function(){
		var lmt=$(this).val();
		var url =base_url+'merchant/closed_date?limit='+lmt;
		window.location.href = url; 
	});     


$(document).on('click','.addefitclosetime',function(){
	 var url =$(this).attr('data-href');
	 	loading();
			//var urls=$('#select_url').val();
		   $.get(url,function( data ) {
			  // console.log(data);
				 var obj = jQuery.parseJSON( data );
				if(obj.success=='1'){
				 $("#closetimeformhtml").html(obj.html);
				 $("#closed_date_modal").modal('show');
				  $("#datepicker122").datepicker(datepickrOption);
				  $("#datepicker222").datepicker(datepickrOption);
          var uiserid= $("input[name='uid']:checked").attr('data-text');
          //alert(uiserid);
          if(obj.edit==1){
            $(".levelEmp").text(uiserid);          
				    $(".levelEmp").attr('disabled',true);
          }
				}else{
					$("#alert_message").html('<strong>Message ! </strong>'+obj.message);
					$(".alert_message").css('display','block');
					
					//location.reload();
				
				}
				unloading();
		});
	});	
	
$(document).on('click','.deleteclosetime',function(){
	 var url =$(this).attr('data-href');
	 	
		  Swal.fire({
      title: '<?php echo $this->lang->line("are_you_sure"); ?>',
      text: "",
      type: 'warning',
      showCancelButton: true,
      reverseButtons:true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Abbrechen',
      confirmButtonText: 'Bestätigen'
    }).then((result) => {
    if (result.value) {
		loading();
      window.location.href=url;
      }else{
        return false;
        }
    });  
	});		
        
  }); 
	
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
    
   
    $('[data-toggle="popover"]').popover({
        trigger: 'hover',
            'placement': 'top'
    });

  
    

</script>
