<link rel="stylesheet" href="<?php echo base_url("assets/cropp/croppie.css");?>" type="text/css" />
<!-- modal start -->
<div class="modal fade pr-0" id="popup-v12" >
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <form id="aadNewCustomer" method="post">
                <div class="modal-header-new">
                    <div class="absolute right top mt-0 mr-0">
                        <a href="javascript:void(0)" data-dismiss="modal" class="crose-btn font-size-30 color333 a_hover_333" style="right:10px;">
                            <picture class="" style="width: 22px; height: 22px;">
                                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp"  style="cursor:pointer;">
                                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"  style="cursor:pointer;">
                                <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"  style="cursor:pointer;">
                            </picture>
                        </a>
                    </div>   
                    <h3 class="font-size-20 fontfamily-medium color333 text-center"><?php echo $this->lang->line('Add_New_Customer'); ?></h3>
                </div>
                <div class="row mt-5">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">                         
                    <div class="relative form-group pb-40">
                        <div class="relative display-ib">
                        <img id="CusProfile" style="width: 115px; height: 115px; border-radius: 50%;" src="<?php echo base_url('assets/frontend/images/upload_dummy_img.svg'); ?>">
                        <label class="all_type_upload_file">
                            <img src="<?php echo base_url('assets/frontend/images/camera_upload_icon.svg'); ?>" class="edit_pencil_bg_white_circle_icon1">
                            <input type="file" id="profile_pic" name="profile_img">
                        </label>
                        </div>
                        <label class="error" id="imgerror"></label>  
                        <input type="hidden" name="hasimage" id="hasimage" />
                    </div>
                    </div> 
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="relative vertical-bottom mt-60 form-group-mb-50">
                        <p class="color999 fontfamily-light font-size-14 mb-10">
                        Marketing-Benachrichtigungen
                        </p>
                        <p class="color999 font-size-12 mb-10">
                            Bitte stelle sicher, dass der Kunde seine schriftliche Einverständniserklärung für den Empfang von Marketing E-Mails gegeben hat.
                        </p>
                        <label class="switch" for="send_notification">
                        <input type="checkbox" id="send_notification" name="send_notification" />
                        <div class="slider round"></div>
                        </label>
                    </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50">
                        <div class="btn-group multi_sigle_select inp_select" id="genderDiv">
                        <span class="label"><?php echo $this->lang->line('Gender'); ?></span>
                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"
                            id="gender" name="gender"></button>
                        <ul class="dropdown-menu mss_sl_btn_dm">
                            <li class="radiobox-image"><input type="radio" id="id_112" name="gender"
                                class="user_regfrm" value="male"><label for="id_112">Männlich</label></li>
                            <li class="radiobox-image"><input type="radio" id="id_113" name="gender"
                                class="user_regfrm" value="female"><label for="id_113">Weiblich</label></li>
                            <li class="radiobox-image"><input type="radio" id="id_114" name="gender"
                                class="user_regfrm" value="other"><label for="id_114">Andere</label></li>
                        </ul>
                        </div>
                        <label class="error_label" id="gender_err"></label>
                    </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50" id="dob_validate">
                        <label class="inp">
                        <input type="text" placeholder="Geburtsdatum" class="form-control dobDatepicker"
                            name="dob" style="background-color:#ffffff" readonly>
                        <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>"
                            class="v_time_claender_icon_blue" style="top:8px;right:9px;">
                        </label>
                    </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50" id="first_name_validate">
                        <label class="inp">
                        <input type="text" placeholder="&nbsp;" class="form-control" id="first_name"
                            name="first_name">
                        <span class="label"><?php echo $this->lang->line('First_Name'); ?> *</span>
                        </label>
                    </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50" id="last_name_validate">
                        <label class="inp">
                        <input type="text" placeholder="&nbsp;" class="form-control" id="last_name"
                            name="last_name">
                        <span class="label"><?php echo $this->lang->line('Last_Name'); ?></span>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50" id="telephone_validate">
                        <label class="inp">
                        <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="telephone"
                            name="telephone">
                        <span class="label"><?php echo $this->lang->line('Telephone'); ?> </span>
                        </label>
                    </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50" id="email_validate">
                        <label class="inp">
                        <input type="text" placeholder="&nbsp;" class="form-control" id="email" name="email">
                        <span class="label"><?php echo $this->lang->line('Email'); ?></span>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50" id="location_val">
                        <label class="inp">
                        <input type="text" placeholder="&nbsp;" class="form-control" id="location" name="location">
                        <span class="label"><?php echo $this->lang->line('Street'); ?> </span>
                        </label>
                        <input type="hidden" name="latitude" value="" id="latitude">
                        <input type="hidden" name="longitude" value="" id="longitude">
                        <span class="error_label" id="addr_err"></span>
                    </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50">
                        <label class="land-label-css-custom"> <?php echo $this->lang->line('Country'); ?></label>
                        <div class="btn-group multi_sigle_select inp_select" id="countryDiv">
                        <button data-toggle="dropdown"
                            class="btn btn-default dropdown-toggle mss_sl_btn">Deutschland</button>
                        <ul class="dropdown-menu mss_sl_btn_dm">
                            <li class="radiobox-image"><input type="radio" id="id_1" name="country" class="country"
                                value="Germany" selected><label for="id_1">Deutschland </label></li>
                            <li class="radiobox-image"><input type="radio" id="id_2" name="country" class="country"
                                value="Austria"><label for="id_2">Österreich</label></li>
                            <li class="radiobox-image"><input type="radio" id="id_3" name="country" class="country"
                                value="Switzerland"><label for="id_3">Schweiz </label></li>
                        </ul>
                        </div>
                        <label class="error_label" id="country_err"></label>

                    </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50" id="post_code_val">
                        <label class="inp">
                        <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="post_code"
                            name="post_code" maxlength="5">
                        <span class="label"><?php echo $this->lang->line('Postcode1'); ?> </span>
                        </label>
                    </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group form-group-mb-50" id="city_val">
                        <label class="inp">
                        <input type="text" placeholder="&nbsp;" class="form-control city" id="city" name="city">
                        <span class="label"><?php echo $this->lang->line('City'); ?> </span>
                        </label>
                    </div>
                    </div>
                    <div class="col-12">
                    <div class="form-group form-group-mb-50">
                        <label class="inp">
                        <textarea type="text" placeholder="&nbsp;" class="form-control notes" id="notes" name="notes"></textarea>
                        <span class="label">Kundennotiz </span>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="text-center w-100 mt-3">
                    <button class="btn widthfit" type="button" id="SaveTempUser"><?php echo $this->lang->line('Save_btn'); ?></button>
                </div>
                </form>                  
            </div>
        </div>
    </div>
</div>
<!-- modal end -->
<?php
    $this->load->view('frontend/common/crop_pic');  
?>
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
            $(".cr-boundary").css('width','500px');
            $(".cr-boundary").css('height','400px');
            $(".cr-viewport").css('width','250px');
            $(".cr-viewport").css('height','250px');
            $(".crop_image").addClass('crop_image_profile');
            $(".crop_image").removeClass('crop_image');
            $image_crop.croppie('bind', {
                url:event.target.result
            }).then(function(){
            });
        }
        reader.readAsDataURL(this.files[0]);
        $('#uploadimageModal').modal('show');
    });

    $(document).on('click','.crop_image_profile',function(event){
        $image_crop.croppie('result', {
        type:'canvas',
        size:{
            width:250,
            height:250
        }
        }).then(function(response){
            loading();
            $.ajax({
                url:"<?php echo base_url('profile/profile_imagecropp'); ?>",
                type: "POST",
                data:{"image": response},
                success:function(data)
                { var obj = $.parseJSON(data);
                $('#uploadimageModal').modal('hide');
                $('.modal').css('overflow-y','auto');
                unloading();
                $('#CusProfile').attr('src',obj.image);
                $("#hasimage").val("has");
                }
            });
        })
    });
});

</script>