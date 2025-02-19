<?php $this->load->view('frontend/common/header'); ?>
<!-- dashboard left side end -->
<style type="text/css">
.page-item{display:inline-block;}
#add_service_popup{
	z-index:1051 !important;
	}
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

  /*.page-item a:hover, .page-item a:focus {
    color: #fff;
    text-decoration: none;
    background-color: #00b3bf;
    border-color: transparent;
    box-shadow: unset!important;
}*/
.alert_message {
    /* top: 0px !important; */
    /*width: auto!important;*/
    right: 10px;
    height: 35px!important;
    padding: 5px 35px!important;
    font-size: 12px;
} 
.alert_message img{
  width: 18px;
}
.editEmp{
	cursor:pointer;
	}
#swal2-title{
	font-size: 1.25em;
	}	
  span.asign-color-box {
 height: 24px;
 width: 24px;
 display: block;
 margin: auto;
 border-radius: 50%;
}
</style>
 <div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>
	<link rel="stylesheet" href="<?php echo base_url("assets/cropp/croppie.css");?>" type="text/css" />
	 <div class="right-side-dashbord w-100 pl-30 pr-30">
          <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-20 relative">
          <?php $this->load->view('frontend/common/alert'); ?>
            <!-- tab start -->
            <ul class="nav nav-tabs new_tab_v" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" href="<?php echo base_url('merchant/employee_listing'); ?>"><?php echo $this->lang->line('Employee_List'); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('merchant/employee_shift'); ?>"><?php echo $this->lang->line('Employee_Shifts'); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('merchant/closed_date'); ?>"><?php echo $this->lang->line('close_date'); ?></a>
              </li>
            </ul>
              <div class="pt-20 pb-20 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
                <div class="display-ib ml-auto">
                 <a onclick="return checkMembership();"><button class="btn btn-large widthfit"><?php echo $this->lang->line('Add_Employee'); ?></button></a>
                </div>
              </div>
              <?php if(!empty($employees)){ ?>
              <div class="my-table new-tablwe-1">
                <table class="table">
                  <thead>
                    <tr>
                      <th class="pl-5"><?php echo $this->lang->line('Employee'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Email'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Telefon'); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('color'); ?></th>
                      <th class="text-center"><?php echo ucfirst(strtolower($this->lang->line('Status'))); ?></th>
                      <th class="text-center" style="min-width: 127px;"><?php echo ucfirst(strtolower($this->lang->line('Action'))); ?></th>
                      <th class="text-center"><?php echo $this->lang->line('Online_Booking'); ?> </th>
                    </tr>
                  </thead>
                  <tbody>
                  	<?php foreach($employees as $row){ ?>
                    <tr id="row_<?php echo $row->id; ?>">
                      <td class="font-size-14 color666 fontfamily-regular editEmp" data-id="editEmp<?php echo $row->id; ?>">
                        <?php if($row->profile_pic ==""){
                                $pimg= base_url('assets/frontend/images/user-icon-gret.svg');
                                $pimgw= base_url('assets/frontend/images/user-icon-gret.svg');
							              }
                             else{
                                $pimg= getimge_url('assets/uploads/employee/'.$row->id.'/','icon_'.$row->profile_pic,'png');
                                $pimgw= getimge_url('assets/uploads/employee/'.$row->id.'/','icon_'.$row->profile_pic,'webp');
							              }

                         ?>
                        
                        <picture class="conform-img display-ib mr-3">
            
                          <img id="chgemp_img<?php echo $row->id; ?>" style="border-radius: 50px;" src="<?php echo $pimg; ?>" class="mr-3 width30">
                        </picture>
                        
                        <p class="overflow_elips mb-0 display-ib"><?php echo $row->first_name.' '.$row->last_name; ?> </p>
                      </td>                      
                      <td class="text-center font-size-14 color666 fontfamily-regular overflow_elips editEmp" data-id="editEmp<?php echo $row->id; ?>"><?php echo $row->email; ?></td>
                      <td class="text-center font-size-14 color666 fontfamily-regular editEmp" data-id="editEmp<?php echo $row->id; ?>"><?php if(!empty($row->mobile)) echo $row->mobile; else echo '-'; ?></td>
                       <td class="text-center font-size-14 color666 fontfamily-regular editEmp" data-id="editEmp<?php echo $row->id; ?>"><span class="asign-color-box" style="background:<?php echo $row->calender_color; ?>"></span></td>

                      <td class="text-center font-size-14 color666 fontfamily-regular editEmp" data-id="editEmp<?php echo $row->id; ?>">
                        <?php echo $row->status == 'active' ? 'aktiv':'inaktiv'; ?>
                      </td>
                      <td class="font-size-14 color666 fontfamily-regular text-center" style="min-width: 127px;">
                      <a href="<?php echo base_url("merchant/dashboard?id=".url_encode($row->id)); ?>">
                          <img src="<?php echo base_url('assets/frontend/images/new-icon-calendar-employee.png');?>" class="width22" style="margin-top:2px;">
                        </a>
                        <a data-href="<?php echo base_url('merchant/dashboard_addemployee/').url_encode($row->id); ?>" href="#" class="mr-3 ml-3 editEmp" data-id="editEmp<?php echo $row->id; ?>" id="editEmp<?php echo $row->id; ?>" style="">
                          <img src="<?php echo base_url('assets/frontend/images/new-icon-edit-employee.png');?>" class="width22">
                        </a>
                        <a href="#" data-toggle="modal" data-target="#employee-delete-popup" class="deleteemployeeclick" id="<?php echo url_encode($row->id); ?>"><img src="<?php echo base_url('assets/frontend/images/new-icon-bin.png'); ?>" class="width22"></a>
                        
                      </td>
                      <td class="text-center">
                        <label class="switch" for="vcheckbox<?php echo $row->id; ?>">
                            <input class="changeOnlineBooking" data-id="<?php echo url_encode($row->id); ?>" type="checkbox" <?php if($row->online_booking ==1){ echo 'checked="checked"'; } ?> id="vcheckbox<?php echo $row->id; ?>" />
                            <div class="slider round"></div>
                         </label>
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
		           <?php } else{ ?>
                <div class="text-center" style="padding-bottom: 45px;">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;">Bisher wurden keine Mitarbeiter erstellt.</p></div>
                <?php } ?>

              <!-- dashboard right side end -->       
              </div>
             </div>

      </div>


     <div class="modal fade" id="employee-delete-popup">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a id="close_box" href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/new-icon-bin.png'); ?>" style="width: 40px;">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;"><?php echo $this->lang->line('employee_delete_confimation'); ?></p>
            <input type="hidden" id="deleteid" name="" value="0">
            <button id="deleteemployee" type="button" class=" btn btn-large widthfit"><?php echo $this->lang->line('Delete'); ?></button>
          </div>
        </div>
      </div>
    </div>

  <!-- add employee modal start-->
<div class="modal fade" id="add-employee-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
    <div class="modal-content"> 
      <div class="modal-body " id="addEmployeeHtmlPopup">
              
         
      </div>
    </div>
  </div>
</div>
<!-- add employee modal end-->



  <?php $this->load->view('frontend/common/footer_script');
     $this->load->view('frontend/marchant/assign_service_poup'); 
     $this->load->view('frontend/common/crop_pic'); 
  
     $pinstatus = get_pin_status($this->session->userdata('st_userid')); 
//echo current_url().'=='.$_SERVER['HTTP_REFERER'];
    if($pinstatus->pinstatus=='on'){
        $this->load->view('frontend/marchant/profile/ask_pin_popup');
      }  
  ?>
     <script type="text/javascript" src="<?php echo base_url('assets/cropp/croppie.js'); ?>"></script>

<script>
	
  $(document).on('click','.editEmp',function(){
	var id=$(this).attr('data-id');
	//alert(id);
	   var url = $("#"+id).data('href');
	   //$(this).removeClass('editEmp');
    // setTimeout(function(){ $(this).addClass('editEmp'); console.log(this); alert('tt'); }, 3000);
      
  //alert(id);
  loading();
     var url = $("#"+id).data('href');
    console.log('----- url -----------',url)
     getaddEmployeeHtml(url);
	});
  
function checkMembership(){
	loading();
	var url=base_url+'user/check_employee_membership'
			$.ajax({
                url: url,
                type: "POST",
                success: function (response){
					var obj = $.parseJSON(response);
					if(obj.msg!=''){
						Swal.fire({
							  title: obj.msg,
							  width: 600,
							  padding: '0.5em'
							});
            unloading();
					  return false;		
					}
					else{
         
						getaddEmployeeHtml(obj.url);
					 // location.href=obj.url;
				    }
 
                 }
            });

	}	

function getaddEmployeeHtml(url){
		var gurl=url;
    console.log('-----gurl url -----------',gurl)
		 $.get(gurl,function(data){
			  var obj = jQuery.parseJSON( data );
			 if(obj.success=='1'){
        /*$('#add-employee-modal').modal({
              backdrop: 'static',
              keyboard: false
          });*/
          //console.log("get add emp data pop up",obj.html)
				 	$("#addEmployeeHtmlPopup").html(obj.html);
				 	$("#add-employee-modal").modal('show');
          jscolor.installByClassName("jscolor");
          unloading();
						   
				 }

		  });
	
	}
	
 $(document).ready(function(){

	 
	 $(document).on("change",".select_all",function(){
		 var classnm=$(this).attr('id');  //"select all" change
	  var status = this.checked; // "select all" checked status
	  $('.'+classnm).each(function(){ //iterate all listed checkbox items
		this.checked = status; //change ".checkbox" checked status
	  });

	});
    
	 $(document).on('click','.deselect_service',function(){
		  // alert('sd');
                  var id=$(this).attr('data-subcat');
                  
                  //alert(id);
                  $("."+id).remove();
                  var arr=[];
                  var subcatarr=[];
                  $(".deselect_service").each(function() {
					 var id=$(this).attr('data-val');
					 var subcatid=$(this).attr('data-subcat');
					 arr.push(id);
					 subcatarr.push(subcatid);
				  });
				  $("#all_select_service").val(arr.join());
				   $("#all_subcat").val(subcatarr.join());
                  //$("#"+id).prop("checked",false);
                //alert($(this).attr('id'));
		
		});	
    
    	$("#close_service_modal").click(function(){
			var html="";
			var arr=[];
			var subCat=[];
			$(".select_asn_service:checked").each(function() {
				var val=$(this).val();
				var subcatVal=$(this).attr('data-subcat');
		     
		      
		      var id=$(this).attr('id');
              var text=$(this).attr('data-text');
              if(subCat.includes(subcatVal)==false){
               html=html+'<span class="bge8e8e8 border-radius4 pt-1 pb-1 pl-15 pr-15 color333 font-size-14 display-ib m-1 '+subcatVal+'">'+text+'<img src="<?php echo base_url(); ?>assets/frontend/images/search-crose-small-icon.svg" class="ml-4 deselect_service" data-subcat="'+subcatVal+'"  data-val="'+val+'" data-id="'+id+'" style="cursor:pointer"></span>';
		       }else{
				   html=html+'<span class="bge8e8e8 border-radius4 pt-1 pb-1 pl-15 pr-15 color333 font-size-14 display-n m-1 '+subcatVal+'">'+text+'<img src="<?php echo base_url(); ?>assets/frontend/images/search-crose-small-icon.svg" class="ml-4 deselect_service" data-subcat="'+subcatVal+'" data-val="'+val+'" data-id="'+id+'" style="cursor:pointer"></span>'; 
				   }
               
                subCat.push(subcatVal);
		      arr.push(val);
            
		  });
		   $("#all_service_tag").append(html);
             $("#all_select_service").val(arr.join());
             $("#all_subcat").val(subCat.join());
		  $("#all_service_tag").html(html);
		   $("#add_service_popup").modal("hide");
		   $(".modal").css('overflow-y','auto');
		
		});	
		

  $image_crop = $('#image_demo').croppie({
		enableExif: false,
		viewport: {
		  width:250,
		  height:250,
		  type:'square' //circle
		},
		boundary:{
		  width:500,
		  height:400
		}
	  });

  $(document).on('change','#profile_pic', function(){
    var reader = new FileReader();
    //console.log(reader);
    reader.onload = function (event) {
		//console.log(event);
      $image_crop.croppie('bind', {
        url:event.target.result
      }).then(function(){
        //console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type:'canvas',
      size:'viewport'
    }).then(function(response){
		loading();
      $.ajax({
        url:"<?php echo base_url('profile/profile_imagecropp'); ?>",
        type: "POST",
        data:{"image": response},
        success:function(data)
        { var obj = $.parseJSON(data);
          $('#uploadimageModal').modal('hide');
          unloading();
          $('.modal').css('overflow-y','auto');
          // var $el = $('#profile_pic');
           //$el.wrap('<form>').closest('form').get(0).reset();
          // $el.unwrap();
         // $('#upload_image').attr('data-url','');
          //$('#profile-picture').attr('data-img',obj.status);
          $('#EmpProfile').attr('src',obj.image);
        }
      });
    })
  });

		
		
	 	
	});
	
</script>
