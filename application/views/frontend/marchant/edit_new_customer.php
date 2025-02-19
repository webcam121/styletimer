<form id="editCustomer" method="post">
    <div class="modal-header-new">
        <div class="absolute right top mt-0 mr-0">
            <a href="javascript:void(0)" data-dismiss="modal" class="crose-btn font-size-30 color333 a_hover_333" style="right: 10px;">
                <picture class="" style="width: 22px; height: 22px;">
                    <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp"  style="cursor:pointer;">
                    <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png"  style="cursor:pointer;">
                    <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"  style="cursor:pointer;">
                </picture>  
            </a>
        </div>   
        <h3 class="font-size-20 fontfamily-medium color333 text-center">Kunde/-in bearbeiten</h3>
    </div>
    <div class="row mt-5">
        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">                         
        <div class="relative form-group pb-40">
            <div class="relative display-ib">
            <img id="CusProfile" style="width: 115px; height: 115px; border-radius: 50%;" src="<?php echo $customer->profile_pic ? base_url('assets/uploads/users/'.$customer->id.'/icon_'.$customer->profile_pic) : base_url('assets/frontend/images/upload_dummy_img.svg'); ?>">
            
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
        <div class="relative vertical-bottomform-group-mb-50 mb-20">
            <p class="color999 fontfamily-light mt-20 font-size-14 mb-10">
                Kunde angelegt am: <?php echo date('d.m.Y', strtotime($customer->created_on));?>
            </p>
            <p class="color999 fontfamily-light mt-10 font-size-14 mb-10">
            Marketing-Benachrichtigungen
            </p>
            <p class="color999 font-size-12 mb-10">
                Bitte stelle sicher, dass der Kunde seine schriftliche Einverständniserklärung für den Empfang von Marketing E-Mails gegeben hat.
            </p>
            <label class="switch" for="send_notification">
            <input type="checkbox" id="send_notification" name="send_notification" <?php echo $customer->service_email ? 'checked':''?>/>
            <div class="slider round"></div>
            </label>
        </div>
        </div>
        <input type="hidden" name="customerid" value="<?php echo url_encode($customer->id);?>"?>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="form-group form-group-mb-50">
            <div class="btn-group multi_sigle_select inp_select" id="genderDiv">
            <span class="label <?php echo $customer->gender ? 'label_add_top': '';?>"><?php echo $this->lang->line('Gender'); ?></span>
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"
                id="gender" name="gender">
                <?php
                    if ($customer->gender == 'male') echo 'Männlich';
                    else if ($customer->gender == 'female') echo 'Weiblich';
                    else if ($customer->gender == 'other') echo 'Andere';
                ?>
            </button>
            <ul class="dropdown-menu mss_sl_btn_dm">
                <li class="radiobox-image"><input type="radio" id="id_112" name="gender"
                    class="user_regfrm" value="male" <?php echo $customer->gender == 'male' ? 'checked': ''; ?>><label for="id_112">Männlich</label></li>
                <li class="radiobox-image"><input type="radio" id="id_113" name="gender"
                    class="user_regfrm" value="female" <?php echo $customer->gender == 'female' ? 'checked': ''; ?>><label for="id_113">Weiblich</label></li>
                <li class="radiobox-image"><input type="radio" id="id_114" name="gender"
                    class="user_regfrm" value="other" <?php echo $customer->gender == 'other' ? 'checked': ''; ?>><label for="id_114">Andere</label></li>
            </ul>
            </div>
            <label class="error_label" id="gender_err"></label>
        </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="form-group form-group-mb-50" id="dob_validate">
            <label class="inp">
            <input type="text" placeholder="Geburtsdatum" class="form-control dobDatepicker"
                name="dob" style="background-color:#ffffff" readonly value="<?php echo $customer->dob != '0000-00-00'?$customer->dob:'0';?>">
            <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>"
                class="v_time_claender_icon_blue" style="top:8px;right:9px;">
            </label>
        </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="form-group form-group-mb-50" id="first_name_validate">
            <label class="inp">
            <input type="text" placeholder="&nbsp;" class="form-control" id="first_name"
                name="first_name" value="<?php echo $customer->first_name?>">
            <span class="label"><?php echo $this->lang->line('First_Name'); ?> *</span>
            </label>
        </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="form-group form-group-mb-50" id="last_name_validate">
            <label class="inp">
            <input type="text" placeholder="&nbsp;" class="form-control" id="last_name"
                name="last_name" value="<?php echo $customer->last_name?>">
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
                name="telephone" value="<?php echo $customer->mobile?>">
            <span class="label"><?php echo $this->lang->line('Telephone'); ?> </span>
            </label>
        </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="form-group form-group-mb-50" id="email_validate">
            <label class="inp">
            <input type="text" placeholder="&nbsp;" class="form-control" id="email" name="email" value="<?php echo $customer->email?>">
            <span class="label"><?php echo $this->lang->line('Email'); ?></span>
            </label>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="form-group form-group-mb-50" id="location_val">
            <label class="inp">
            <input type="text" placeholder="&nbsp;" class="form-control" id="location" name="location" value="<?php echo $customer->address?>">
            <span class="label"><?php echo $this->lang->line('Street'); ?> </span>
            </label>
            <input type="hidden" name="latitude" value="" id="latitude" value="<?php echo $customer->latitude?>">
            <input type="hidden" name="longitude" value="" id="longitude" value="<?php echo $customer->longitude?>">
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
                class="btn btn-default dropdown-toggle mss_sl_btn">
                <?php
                    if ($customer->country == 'Germany') echo 'Deutschland';
                    else if ($customer->country == 'Austria') echo 'Österreich';
                    else if ($customer->country == 'Switzerland') echo 'Switzerland';
                    else echo 'Deutschland';
                ?>
            </button>
            <ul class="dropdown-menu mss_sl_btn_dm">
                <li class="radiobox-image"><input type="radio" id="id_1" name="country" class="country"
                    value="Germany" <?php echo $customer->country == 'Germany' ?'checked':''?>><label for="id_1">Deutschland </label></li>
                <li class="radiobox-image"><input type="radio" id="id_2" name="country" class="country"
                    value="Austria" <?php echo $customer->country == 'Austria' ?'checked':''?>><label for="id_2">Österreich</label></li>
                <li class="radiobox-image"><input type="radio" id="id_3" name="country" class="country"
                    value="Switzerland" <?php echo $customer->country == 'Switzerland' ?'checked':''?>><label for="id_3">Schweiz </label></li>
            </ul>
            </div>
            <label class="error_label" id="country_err"></label>

        </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="form-group form-group-mb-50" id="post_code_val">
            <label class="inp">
            <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="post_code"
                name="post_code" maxlength="5" value="<?php echo $customer->zip;?>">
            <span class="label"><?php echo $this->lang->line('Postcode1'); ?> </span>
            </label>
        </div>
        </div>
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="form-group form-group-mb-50" id="city_val">
            <label class="inp">
            <input type="text" placeholder="&nbsp;" class="form-control city" id="city" name="city" value="<?php echo $customer->city;?>">
            <span class="label"><?php echo $this->lang->line('City'); ?> </span>
            </label>
        </div>
        </div>
        <div class="col-12">
        <div class="form-group form-group-mb-50">
            <label class="inp">
            <textarea type="text" placeholder="&nbsp;" class="form-control notes" id="notes" name="notes"><?php echo !empty($notes) ? strip_tags($notes->notes) : '';?></textarea>
            <span class="label">Kundennotiz </span>
            </label>
        </div>
        </div>
    </div>
    <div class="text-center w-100 mt-3">
        <button class="btn widthfit" type="button" id="UpdateTempUser"><?php echo $this->lang->line('Save_btn'); ?></button>
    </div>
</form>                  

<script>
$("#editCustomer").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
        first_name: { required: true },
        email: {
            required: {
            depends: function(el) {
                console.log($("#send_notification").is(":checked"));
                return $("#send_notification").is(":checked");
            }
            }
        }
    },
    messages: {
        first_name: {
            required:
            '<i class="fas fa-exclamation-circle mrm-5"></i>' +
            please_enter_first_name,
        },
        email: {
            required: 
            '<i class="fas fa-exclamation-circle mrm-5"></i>Sie haben Newsletterempfang bei diesem Kunden aktiviert. <br/>Bitte geben Sie eine E-Mail Adresse an.'
        }
    },
    errorPlacement: function (error, element) {
    var name = $(element).attr("name");
    console.log(name, error);
    error.appendTo($("#editCustomer #" + name + "_validate"));
    },
});

var date40year = new Date('<?php  echo date("Y-m-d",strtotime("-90 years",time())); ?>');

$("#edit-customer-modal .dobDatepicker").datepicker({
    beforeShow: function(input, inst) {
    $(document).off('focusin.bs.modal');
    },
    onClose: function() {
    $(document).on('focusin.bs.modal');
    },
    // uiLibrary: 'bootstrap4',
    // locale: 'de-de',
    // format:"dd.mm.yyyy",
    changeMonth: true,
    changeYear: true,
    maxDate: today,
    yearRange: date40year.getFullYear() + ':' + date.getFullYear(),
    prevText: '&#x3c;zurück', prevStatus: '',
    prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
    nextText: 'Vor&#x3e;', nextStatus: '',
    nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
    currentText: 'heute', currentStatus: '',
    todayText: 'heute', todayStatus: '',
    clearText: '-', clearStatus: '',
    firstDay: 1,
    closeText: 'schließen', closeStatus: '',
    monthNames: ['Januar','Februar','März','April','Mai','Juni',
    'Juli','August','September','Oktober','November','Dezember'],
    monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
    dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
    dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
    dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
    dateFormat: 'dd.mm.yy',
});

<?php if ($customer->dob != '0000-00-00') {?>
$('#edit-customer-modal .dobDatepicker').datepicker('setDate', new Date("<?php echo $customer->dob;?>"));
<?php
} else {?>
$('#edit-customer-modal .dobDatepicker').datepicker('setDate', '');
<?php } ?>
</script>