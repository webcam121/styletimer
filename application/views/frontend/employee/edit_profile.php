<?php $this->load->view('frontend/common/header'); //echo '<pre>'; print_r($this->session->userdata()); die; ?>
<style type="text/css">
  .alert{
       top: -106px !important;
       z-index:1050 !important;
   }
</style>
<link rel="stylesheet" href="<?php echo base_url("assets/cropp/croppie.css");?>" type="text/css" />
<section class="pt-84 clear user_profile_section1">
      <div class="container">
        <div class="relative mt-50">
        
          <div class="row">
            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
              <form method="post" id="profileChange" enctype="multipart/form-data" action="<?php echo base_url('profile/edit_employee_profile'); ?>">
              <div class="bgwhite box-shadow1 border-radius4 pt-40 pb-25 text-center mb-50">
                <?php if($userdetail->profile_pic ==""){ ?>
                <div class="relative display-ib">
                  <div id="user-avtar"><img style="width: 115px; height: 115px; border-radius:50%;" id="imgPrevie" src="<?php echo base_url('assets/frontend/images/upload_dummy_img.svg'); ?>"></div>
                  <label class="all_type_upload_file">
                    <img src="<?php echo base_url('assets/frontend/images/camera_upload_icon.svg'); ?>" class="edit_pencil_bg_white_circle_icon1">
                    <input id="profile_pic" name="profile_pic" type="file" onchange="changeImageUpload()">
                </label>
                </div> <?php }
                else { ?>
                <div class="relative display-ib round-employee-upload">
					
				    <picture>
						 <!-- <source srcset="<?php //echo getimge_url('assets/uploads/employee/'.$userdetail->id.'/','thumb_'.$userdetail->profile_pic,'webp'); ?>" type="image/webp" class="round-employee-img-upload">
						 
                         <source srcset="<?php //echo getimge_url('assets/uploads/employee/'.$userdetail->id.'/','thumb_'.$userdetail->profile_pic,'webp'); ?>" type="image/webp" class="round-employee-img-upload"> -->
                         
                         <img style="width: 115px; height: 115px;" id="imgPrevie" src="<?php echo getimge_url('assets/uploads/employee/'.$userdetail->id.'/','thumb_'.$userdetail->profile_pic,'png'); ?>" type="image/png" class="round-employee-img-upload">
                    </picture>	
                  
                  <label class="all_type_upload_file bgblack18">
                    <span class="colorwhite fontfamily-medium font-size-12"><?php echo $this->lang->line('Change'); ?></span>
                    <input id="profile_pic" name="profile_pic" type="file" onchange="changeImageUpload()">
                </label>
                </div>
                <?php } ?>
                <label id="img_error" class="error_label" style="top: 170px;left: 0px;right: 0px;"></label>
                 <input type="hidden" id="old_image" name="old_image" value="<?php echo $userdetail->profile_pic; ?>">
                <p class="font-size-18 color333 fontfamily-medium mt-25 mb-0"><?php echo $userdetail->first_name.' '.$userdetail->last_name; ?></p>
                <?php if($userdetail->profile_pic !=""){ ?>
				<a class="font-size-10 color666 fontfamily-medium display-ib relative" onclick="return deleteImgaeConfirm();" style="top:-42px; cursor:pointer;"><?php echo $this->lang->line('Remove_Picture'); ?></a>
				<?php } ?>
                  
              </div>
            </form>
            </div> 
            <div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-9"> 
             <form class="w-100" class="relative" method="post" id="employee_update_profile" action="<?php echo base_url('profile/edit_employee_profile'); ?>">   
              <?php if($this->session->flashdata('success')) { ?>
                <div id="succ_msg" class="alert alert-success absolute vinay top w-100 mt-15 text-center">
                  <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <?php echo $this->session->flashdata('success'); ?>
                 </div><?php }
                  if($this->session->flashdata('error')) { ?>
                 <div id="err_msg" class="alert alert-danger absolute top w-100 mt-15 vinay">
                  <button type="button" class="close ml-10" data-dismiss="alert">&times;</button> <?php echo $this->session->flashdata('error'); ?>
                </div>   
                <?php } ?>
              <div class="bgwhite box-shadow1 border-radius4 around-30 pt-60 pr-90 ">
                 
                <div class="row">                  
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <div class="form-group form-group-mb-50" id="first_name_validate">
                        <label class="inp">
                           <input type="text" placeholder="&nbsp;" class="form-control" id="first_name" name="first_name" value="<?php echo $userdetail->first_name; ?>">
                           <span class="label">Vorname *</span>
                           <!-- <label class="error_label">
                            <i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name
                            </label> -->
                         </label>  
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <div class="form-group form-group-mb-50" id="last_name_validate">
                        <label class="inp">
                           <input type="text" placeholder="&nbsp;" class="form-control" id="last_name" name="last_name" value="<?php echo $userdetail->last_name; ?>">
                           <span class="label">Nachname *</span>
                           <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                         </label>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <div class="form-group form-group-mb-50" id="email_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" name="email" value="<?php echo $userdetail->email; ?>" class="form-control" >
                          <span class="label">E-Mail *</span>
                          <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                        </label>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <div class="form-group form-group-mb-50" id="telephone_validate">
                        <label class="inp">
                          <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" value="<?php echo $userdetail->mobile; ?>" id="telephone" name="telephone">
                          <span class="label">Telefonnummer *</span>
                          <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                        </label>
                      </div>
                    </div>
                  </div>
                           
              </div> 
              <div class="relative text-center mt-50 mb-50">
                <button type="submit" id="frmUpdate" name="frmUpdate" class="btn btn-large widthfit">Änderungen speichern</button>
              </div>    
            </form>   
          </div>
          <!-- <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="bgwhite box-shadow1 border-radius4 around-30">
              <p class="fontfamily-regular font-size-14 mb-0 hide-changepassword">
                <a href="#" class="colororange a_hover_orange text-underline a_show-changepassword" id="show-changepassword">CLICK HERE</a> to change your -password *</p>
             
              <div class="relative show-changepassword display-n">
                <h6 class="color333 fontfamily-medium font-size-16 mb-30 text-center">Passwort ändern</h6>
                <div class="form-group form-group-mb-50">
                  <label class="inp">
                    <input type="text" placeholder="&nbsp;" class="form-control">
                    <span class="label">Old Password *</span>
                  </label>
                </div>
                <div class="form-group form-group-mb-50">
                  <label class="inp">
                    <input type="text" placeholder="&nbsp;" class="form-control">
                    <span class="label">New Password *</span>
                  </label>
                </div> 
                <div class="form-group form-group-mb-50">
                  <label class="inp">
                    <input type="text" placeholder="&nbsp;" class="form-control">
                    <span class="label">Confirm Password *</span>
                  </label>
                </div> 
                <button class="btn btn-large again-show-clickhere" id="">Change</button>
              </div>
            </div>
           
          </div> -->
        </div>
     
        </div>
      </div>
    </section>
     <?php $this->load->view('frontend/common/footer_script');
     $this->load->view('frontend/common/crop_pic');  ?>
<script type="text/javascript" src="<?php echo base_url('assets/cropp/croppie.js'); ?>"></script>

<script>

$(document).ready(function(){
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
      var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);  
		loading();
      $.ajax({
        url:"<?php echo base_url('profile/profile_imagecropp'); ?>",
        type: "POST",
        data:{"image": response},
        success:function(data)
        { var obj = $.parseJSON(data);
          $('#uploadimageModal').modal('hide');
           $('#success_message').html('<div class="alert alert-success alert-dismissible" 
           role="alert"> <button type="button" class="close" 
           data-dismiss="alert" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>Galeriebild erfolgreich bearbeitet</div>');
          unloading();
          // var $el = $('#profile_pic');
           //$el.wrap('<form>').closest('form').get(0).reset();
          // $el.unwrap();
         // $('#upload_image').attr('data-url','');
          //$('#profile-picture').attr('data-img',obj.status);
          $('#imgPrevie').attr('src',obj.image);
        }
      });
    })
  });


	});			

</script>
