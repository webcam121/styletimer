<?php $this->load->view('frontend/common/header'); ?>
<div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>
<style type="text/css">

      
    </style>
        <div class="right-side-dashbord w-100 pl-30 pr-30 relative">
          <div class="bge8e8e8 border-radius4 height40 text-center lineheight40 mt-10" id="slectAllOption" style="display:none;">
            <span class="color666 fontfamily-regular font-size-14" ><?php echo $this->lang->line('All'); ?> <b id="selectText"> 10 </b> <?php echo $this->lang->line('customer-are-selected'); ?></span> &nbsp; &nbsp; &nbsp; <span class="colorcyan font-size-14 fontfamily-regular" id="allCounttext" style="display:none;"> Select all Customers in Primary</span>
          </div>
            <div class="bgf8d7da border-radius4 height40 text-center lineheight40 mt-10 hide_news display-n messageAlert" id="valid_error">
                <span class="color666 fontfamily-regular font-size-14" id="msg_err">Please Select atleast one user</span>
            </div> 
            <div class="bgd4edda border-radius4 height40 text-center lineheight40 mt-10 hide_news display-n messageAlert" id="success_msg">
                <span class="color666 fontfamily-regular font-size-14">Mails send successfully</span>
            </div> 
          <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-60">

			  <input type="hidden" name="slectall" value="" id="checkallcust">
              <div class="pt-20 pb-20 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
				  
				   <div class="relative display-ib">
                  <span class="color999 fontfamily-medium font-size-14">Show</span>
                    <div class="btn-group multi_sigle_select widthfit110v mr-3 ml-20"> 
                      <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn "><?php if(isset($_GET['limit'])){ echo $_GET['limit']; }else{ echo '10'; } ?></button>
                      <ul class="dropdown-menu mss_sl_btn_dm widthfit110v noclose">
                        <li class="radiobox-image">
                          <input type="radio" id="id_14" name="cc" class="customer_chg" value="10">
                          <label for="id_14">10</label>
                        </li>
                        <li class="radiobox-image">
                          <input type="radio" id="id_15" name="cc" class="customer_chg" value="20">
                          <label for="id_15">20</label>
                        </li>
                        <li class="radiobox-image">
                          <input type="radio" id="id_16" name="cc" class="customer_chg" value="30">
                          <label for="id_16">30</label>
                        </li>
                      </ul>
                  </div>        
                </div>
                

              <form method="get">
             <div class="display-ib mr-20">
                  <input type="text" name="sch" placeholder="Search by client name" value="<?php if(isset($_GET['sch'])) echo $_GET['sch']; ?>" class="height56v widthfit310v">
                </div>
                <div class="display-ib">
                  <button class="btn btn-large48 widthfit"><?php echo $this->lang->line('Search'); ?></button>
                </div>
                </form>

                
                <?php if(!empty($_GET['tempid'])){ ?>
					<div class="display-ib ml-auto">
					  <button class="btn btn-large widthfit" data-text="<?php echo $_GET['tempid']; ?>" id="sendNesslater">Send Newsletter</button>
					</div>
               <?php } ?>
              </div>
              <?php if(!empty($customer)){ ?>
              <div class="my-table">
                <table class="table">
                  <thead>
                    <tr>

                      <th class="text-center pl-3 height56v">
                        <div class="checkbox mt-0 mb-0">
                            <label class="fontsize-12 fontfamily-regular color333">
                              <input type="checkbox" name="remember" class="allcheck" id="select_all" value="">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                            </label>
                        </div> 
                      </th>

                       <th class="pl-5 height56v"><?php echo $this->lang->line('Customer'); ?></th>
                      <th class="text-center height56v"><?php echo $this->lang->line('Email'); ?></th>
                      <th class="text-center height56v"><?php echo $this->lang->line('Contact'); ?></th>
                      <th class="text-center height56v"><?php echo $this->lang->line('Gender'); ?></th>
                      <th class="text-center height56v">
                        <span class="tablete-none"><?php echo $this->lang->line('NoOf'); ?> </span><?php echo $this->lang->line('Bookings'); ?></th>
                      <th class="text-center height56v"><?php echo $this->lang->line('Last_Visited'); ?></th>
                      <th class="text-center height56v"><?php echo $this->lang->line('Notes'); ?> </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php  foreach($customer as $row){
                    if($row->profile_pic !='')
                        $usimg=base_url('assets/uploads/users/').$row->user_id.'/'.$row->profile_pic;
                    else
                        $usimg=base_url('assets/frontend/images/user-icon-gret.svg');
                      ?>
                    <tr>

                      <td class="text-center pl-3">
                          <div class="checkbox mt-0 mb-0">
                            <label class="fontsize-12">
                              <input type="checkbox" class="checkbox" name="remember" value="<?php echo $row->user_id; ?>">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                            </label>
                        </div>
                      </td>

                      <td class="font-size-14 height56v color666 fontfamily-regular">
					   <a href="<?php echo base_url('profile/clientview/'.url_encode($row->user_id)); ?>">
                        <img src="<?php echo $usimg; ?>" class="mr-3 width30 border-radius50">
                        <p class="overflow_elips mb-0 display-ib color666 text-underline"><?php echo $row->first_name.' '.$row->last_name; ?></p>
                        </a>
                      </td>                      
                      <td class="font-size-14 height56v color666 fontfamily-regular"><p class="mb-0 overflow_elips" style="width:175px;"><?php echo $row->email; ?></p></td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular"><?php echo $row->mobile; ?></td>
                      
                      <td class="font-size-14 color666 fontfamily-regular height56v">
                        <p class="mb-0 display-ib"><?php echo $row->gender; ?></p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib"><?php if(!empty($row->bookcount)) echo $row->bookcount; else echo '0'; ?></p>
                      </td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib"><?php if(!empty($row->lastbook)) echo date('d M Y',strtotime($row->lastbook)); else echo 'NA'; ?></p>
                      </td> 
                      <td class="">
                        <?php if($row->notes ==''){ ?>
                        <a href="#" id="<?php echo url_encode($row->user_id); ?>" class="colororange font-size-14 fontfamily-medium text-underline a_hover_orange addnote" data-toggle="modal" data-target="#add-note"><?php echo $this->lang->line('Add_Note'); ?></a>
                        <?php } else { ?>
                         <a href="#" class="color666 font-size-14 fontfamily-regular a_hover_666 overflow_elips editNotes" data-id="<?php echo url_encode($row->user_id); ?>"  data-toggle="popover" data-content="<?php echo $row->notes; ?>">
                          <?php if(strlen($row->notes) > 18)
                                    echo substr($row->notes, 0, 18).'...';
                                else
                                  echo $row->notes;
                           ?> </a> 
						 <?php }                  ?>
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
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;">You dont have any customer yet.</p></div>
                <?php } ?>
              <!-- dashboard right side end -->       
              </div>
             </div>
          </div>

   <?php $this->load->view('frontend/common/footer_script');  ?>
 <script type="text/javascript">
      // Tooltips
$('[data-toggle="popover"]').popover({
    trigger: 'hover',
        'placement': 'top'
});
$(document).on('click','.editNotes',function(){
	
   $("#txtnote").val($(this).attr('data-content'));
        var id = $(this).attr('data-id');
		$('#booking_ids').val(id);
	$(".para_text").text('Kundennotiz hinzufügen');
		
   $("#add-note").modal('show');
   
 });

$(document).on("change","#select_all",function(){  //"select all" change
	  var status = this.checked; // "select all" checked status
	  $('.checkbox').each(function(){ //iterate all listed checkbox items
		this.checked = status; //change ".checkbox" checked status
	  });
	  if(status==true){
      var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);  
	  $.post(base_url+"newsletter/get_count_all_customer",function(data) {
		  var obj = jQuery.parseJSON( data );
		  if(obj.success=='1'){
			 	   $("#slectAllOption").css('display','block');
				   $("#selectText").text($('.checkbox:checked').length);
			 if($('.checkbox:checked').length<obj.count)
			     {
				   $("#allCounttext").css('display','block');
				   $("#allCounttext").text("Select all "+obj.count+" Customers in Primary");	 
				 } 
			}
	  });
    }
   else{
	    $("#slectAllOption").css('display','none');
	    $("#allCounttext").css('display','none');
	    
	   } 
	});

$(document).on("change",".checkbox",function(){ //".checkbox" change
		//uncheck "select all", if one of the listed checkbox item is unchecked
		if(this.checked == false){
			$("#slectAllOption").css('display','none');
	        $("#allCounttext").css('display','none'); //if this item is unchecked
			$("#select_all")[0].checked = false; //change "select all" checked status to false
		}
	   
		//check "select all" if all checkbox items are checked
		if ($('.checkbox:checked').length == $('.checkbox').length ){
			$("#select_all")[0].checked = true; //change "select all" checked status to true
		}
	});

  $(document).on('click','#sendNesslater',function(){
	  var tempid=$(this).attr('data-text');
	    var chkArray = [];
		  /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
		  $(".checkbox:checked").each(function() {
			chkArray.push($(this).val());
		  });
	var valus=$("#checkallcust").val();	  
	var selected;
	if(valus!=undefined && valus!=""){
		selected=valus;
		}
	else{		 
      selected = chkArray.join(','); 
    }
    if(selected.length<1){
     $('#valid_error').show();
     $('#msg_err').html('Please select atleast one user.');
     $('#success_msg').hide();
     setTimeout(function() {
      $(".hide_news").hide();
      }, 4000);
     return false;
    }else{
	  //~ //var result = confirm("Are you sure you want send n?");
  //~ if (result) {
    loading();
    $("#sendNesslater").attr("disabled", true);
    var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			} 
			console.log('STATUS='+status);
   // return false;
     $.ajax({type:'post',
		 url:base_url+"newsletter/send",
		 data:{usersIds:selected,tempid:tempid},
		success:function(res){
			var obj = jQuery.parseJSON( res );
		    if(obj.success=='1'){
        $('#success_msg').show();
        setTimeout(function() {
           //location.href=base_url+"merchant/selectnewesletter";
            }, 4000);
				
				}
			else{
          $('#valid_error').show();
          $('#msg_err').html(obj.message);
          $('#success_msg').hide();
          $('#sendNesslater').attr("disabled", false);  
				}
	          }
		});
	 unloading();	
   setTimeout(function() {
      $(".hide_news").hide();
      }, 4000);
}

   });


    </script>

    <!-- footer end -->
    <!-- modal start -->

    <div class="modal fade" id="add-note">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-40 mb-30 pl-40 pr-40">
            <p class="font-size-18 color333 fontfamily-medium mb-20 para_text">
              <?php echo $this->lang->line('add_notes_for_customer'); ?></p>
            <form id="frmNotes" method="post" action="<?php echo base_url('merchant/addnotes'); ?>">
            <div class="form-group" id="txtnote_validate">
              <label class="inp display-b" style="height: 120px;">
                    <textarea type="text" name="txtnote" id="txtnote" placeholder="&nbsp;" class="form-control custom_scroll" style="min-height: 120px;max-height:120px;;width: 100%;"></textarea>
                </label>
            </div>
            <div class="text-center mt-30 display-b">
                <input type="hidden" id="booking_ids" name="booking_id" value="">
                <button type="button" class=" btn btn-large widthfit" id="saveNote">Save Note</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <!-- modal end -->
    

    
