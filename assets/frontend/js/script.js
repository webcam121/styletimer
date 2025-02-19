function strreplacejs(strings1) {
  //$data=array(",");
  //$replace=array(".");
  strings1 = Number(strings1);
  strings1 = strings1.toFixed(2);
  String(strings1);
  //alert(strings);
  if (strings1 != "0") {
    var returnval = strings1.replace(".", ",");
    return returnval;
  }
  return 0;
}
function onlydotvalreturn(strings1) {
  //$data=array(",");
  //$replace=array(".");
  String(strings1);
  //alert(strings);
  if (strings1 != "0") {
    var returnval = strings1.replace(",", ".");
    return returnval;
  }
  return 0;
}

$(document).ready(() => {
  /***** User Registration *****/
  var com_count = 3;
  $("#userRegistration").validate({
    rules: {
      first_name: { required: true },
      last_name: { required: true },
      dob: { required: true },
      /*telephone:{ required: true,
				minlength:8 },*/
      email: {
        required: true,
        email: true,
        remote: { url: base_url + "auth/checkemail", type: "post" },
      },
      // location:{ required: true },
      // post_code:{
      // 	required: true,
      // 	maxlength: 5
      //  },
      // city:{ required: true },
      password: {
        required: true,
        minlength: 8,
      },
      confirm_pass: {
        equalTo: "#password",
      },
    },
    messages: {
      first_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_first_name,
      },
      last_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_last_name,
      },
      dob: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_select_date_of_birth,
      },
      /*telephone:{
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i> Please enter phone number',
     		minlength: '<i class="fas fa-exclamation-circle mrm-5"></i> Bitte mindestens 8-stellige Nummer eingeben'
     	},*/
      email: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_email_id,
        email:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Please enter a valid email id',
        remote: jQuery.validator.format(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Diese E-Mail Adresse ist bereits vergeben'
        ),
      },
      // location:{
      // 	required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+Please_enter_your_location
      // },
      // post_code:{
      // 	required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+postal_code_is_required,
      // 	maxlength: '<i class="fas fa-exclamation-circle mrm-5"></i> Enter maximum 5 digits'
      // },
      // city:{
      // 	required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+city_name_is_required,
      // },
      password: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_password,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Das Passwort muss mindestens 8 Zeichen lang sein',
      },
      confirm_pass: {
        equalTo:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Confirm_password_doesnt_match,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      var token = true;
      if (!$("#terms").is(":checked")) {
        $("#terms_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Um dich zu registrieren, musst du unseren Datenschutzbestimmungen und unseren Nutzungsbedingungen zustimmen'
        );
        $("#terms_err").css("display", "block");
        token = false;
      } else {
        $("#terms_err").html("");
        $("#terms_err").css("display", "none");
      }

      /*if($('[name="country"]:checked').length == 0){
			$("#country_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>Field is required'); 
			 token =false;
		}else{ $("#country_err").html(''); }*/

      if ($('[name="gender"]:checked').length == 0) {
        $("#gender_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Select gender'
        );
        token = false;
      } else {
        $("#gender_err").html("");
      }
      /*if($('[name="hot_toknow"]:checked').length == 0){
			//$("#hot_toknow_err").css('display','block'); 
			$("#hot_toknow_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>Please select how did you find out about styletimer'); 
			 token =false;
		}else{ $("#hot_toknow_err").html(''); }*/

      /*if($('#latitude').val() ==''){
			
			
			$('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Please enter valid address');
		
			token =false;
		}
		else { $("#addr_err").html(''); }*/

      if (token == false) return false;

      $("#frmsubmit").attr("disabled", true);

      loading();

      $.post(
        base_url + "auth/registration/user",
        $("#userRegistration").serialize(),
        function (data) {
          var obj = jQuery.parseJSON(data);
          
          if (obj.success == "1") {
            $("#frmsubmit").attr("disabled", false);
            $("#openRegisterPopup").modal("hide");
            $("#resi-thankyou").modal("show");

            setTimeout(function () {
              $("#userRegistration").trigger("reset");
            }, 500);
          } else {
            $("#frmsubmit").attr("disabled", false);
            toastr.warning(obj.message);
          }
          unloading();
        }
      ).fail(function (xhr, status, error) {
        $("#frmsubmit").attr("disabled", false);
        unloading();
      });

      return false;
    },
  });

  /*	
$("#merchantRegist").submit(function(){
		$('#addr_err').html('');
	});*/

  /***** Merchant Regist *****/
  $("#merchantRegist").validate({
    rules: {
      first_name: { required: true },
      last_name: { required: true },
      dob: { required: true },
      business_name: {
        required: true,
        remote: { url: base_url + "auth/checksalon_name", type: "post" },
      },
      telephone: { required: true, minlength: 8 },
      email: {
        required: true,
        email: true,
        remote: { url: base_url + "auth/checkemail", type: "post" },
      },
      // location:{ required: true },
      country: {
        required: true,
      },
      post_code: { required: true, maxlength: 5 },
      city: { required: true },
      password: {
        required: true,
        minlength: 8,
      },
      confirm_pass: {
        equalTo: "#password",
      },
      referral_code: {
        minlength: 5,
        remote: { url: base_url + "home/checkrefferal", type: "post" },
      },
      terms: {
        required: true,
      },
    },
    messages: {
      first_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_first_name,
      },
      last_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_last_name,
      },
      dob: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_select_date_of_birth,
      },
      business_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_business_name,
        //    remote: jQuery.validator.format(
        //      '<i class="fas fa-exclamation-circle mrm-5"></i> Business name already exists'
        //    ),
      },
      telephone: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_phone_number,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Bitte mindestens 8-stellige Nummer eingeben',
      },
      email: {
        email:
          '<i class="fas fa-exclamation-circle mrm-5"></i>Please enter a valid email address',
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_email_id,
        remote: jQuery.validator.format(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Diese E-Mail Adresse ist bereits vergeben'
        ),
      },
      // location:{
      // 	required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+Please_enter_your_location
      // },
      country: {
        required:
        '<i class="fas fa-exclamation-circle mrm-5"></i> Country code is required'
      },
      post_code: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          postal_code_is_required,
        maxlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Enter maximum 5 digits',
      },
      city: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          city_name_is_required,
      },
      password: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_password,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Das Passwort muss mindestens 8 Zeichen lang sein',
      },
      confirm_pass: {
        equalTo:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Confirm_password_doesnt_match,
      },
      referral_code: {
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Please enter minimun 5 character',
        remote: jQuery.validator.format(
          '<i class="fas fa-exclamation-circle mrm-5"></i>This referral code doent exists'
        ),
      },
      terms: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_first_name,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
      $("#addr_err").html("");
    },
    submitHandler: async function (form) {
      var token = true;
      if (!$("#terms").is(":checked")) {
        $("#terms_err").html(
          "Um dich zu registrieren musst du unseren allgemeinen Geschäftsbedingungen und Datenschutzbedingungen zustimmen"
        );
        token = false;
      } else {
        $("#terms_err").text("");
      }
      if ($('[name="business_type"]:checked').length == 0) {
        $("#busType_err").text("Please select business type");
        token = false;
      } else {
        $("#busType_err").text("");
      }

      if ($('[name="hot_toknow"]:checked').length == 0) {
        //$("#hot_toknow_err").css('display','block');
        $("#hot_toknow_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Bitte sag uns, wie du auf styletimer aufmerksam geworden bist'
        );
        token = false;
      } else {
        $("#hot_toknow_err").html("");
      }

      if (
        $('[name="hot_toknow"]:checked').val() == "Referral" &&
        $("#referral_code_val").val() == ""
      ) {
        $("#referral_code_validate label.error").remove();
        $("#referral_code_validate").append(
          '<label class="error" for="referral_code_val" generated="true"><i class="fas fa-exclamation-circle mrm-5"></i>Please enter referral code</label>'
        );
        token = false;
      }

      // if($('[name="country"]:checked').length == 0){
      // 	$("#country_err").text('Please select country');
      // 	 token =false;
      // }else{ $("#country_err").text(''); }

      if ($("#latitude").val() == "") {
        $("#addr_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein'
        );
        token = false;
      } else {
        $("#addr_err").html("");
      }

      if (token == false) {
        return false;
      }
      loading();

      var key = '5eaae41f648ada';
      var gurl = "https://us1.locationiq.com/v1/search.php?key="+key+"&q="+$("#location").val()+", "+$("#post_code").val()+" "+$("#city").val()+"&addressdetails=1&format=json&countrycodes=de";
      data = await $.get(gurl);
      if(data.length > 0){
        var lat = data[0].lat;
        var lon = data[0].lon;
        $("#latitude").val(lat);
        $("#longitude").val(lon);
      }


      $.post(
        base_url + "auth/registration/marchant",
        $("#merchantRegist").serialize(),
        function (data) {
          var obj = jQuery.parseJSON(data);
          
          if (obj.success == "1") {
            $("#frmsubmit").attr("disabled", false);
            $("#resi-thankyou").modal("show");

            setTimeout(function () {
              $("#merchantRegist").trigger("reset");
            }, 500);

          } else {
            $("#frmsubmit").attr("disabled", false);
            if (obj.message) {
              toastr.warning(obj.message);
            }
          }
          unloading();
        }
      ).fail(function (xhr, status, error) {
        $("#frmsubmit").attr("disabled", false);
        unloading();
      });

      return false;
    },
  });

  /***** Forgot password *****/
  // 	$("#for_password1").validate({
  // 	rules: {
  // 		email: {
  // 			required: true,
  // 			email:true,
  // 			remote: {url: base_url+"auth/emailschk", type : "post"}
  // 		}

  //      },
  //      messages: {
  //      	email:
  // 			 {
  // 			 	required: '<i class="fas fa-exclamation-circle mrm-5"></i>'+Please_enter_email_id,
  // 			 	email:'<i class="fas fa-exclamation-circle mrm-5"></i> Please enter a valid email id',
  // 				remote: jQuery.validator.format('<i class="fas fa-exclamation-circle mrm-5"></i>Please enter a registered email id')
  // 			 },
  // 	},
  //     errorPlacement: function (error, element) {
  //             var name = $(element).attr("name");
  //             error.appendTo($("#" + name + "_validate"));
  //         },
  //     submitHandler: function(form) {
  //     	$("#frmsubpass").attr('disabled','');
  //     	var data = {'email' : $('#email').val()};
  //     	loading();
  // 		$.ajax({
  // 				url: base_url+"auth/forgot_password",
  // 				type: "POST",
  // 				data:data,
  // 				success: function (response) {
  // 					var obj = jQuery.parseJSON( response );
  // 					if(obj.success == 1){
  // 						$("#succ_msg_for").show();
  // 						$("#err_msg_for").hide();
  // 						$('#email').val('');
  // 						$("#frmsubpass").attr('disabled',false);
  // 					}
  // 					else{
  // 						$("#succ_msg_for").hide();
  // 						$("#errMsg").html(obj.message);
  // 						$("#err_msg_for").show();
  // 						$("#frmsubpass").attr('disabled',false);
  // 					}
  // 					setTimeout(function() {
  // 			    	$("#succ_msg_for").hide();
  // 			    	$("#err_msg_for").hide();
  // 			  		}, 5000);

  // 				 }
  // 			});
  // 			unloading();

  //     }

  //    });

  /***** Forgot password New *****/
  // $("#for_password").validate({
  // 	loading();
  // 	$("#frmsubpass").attr('disabled','');
  // 	var data = {'email' : $('#email').val()};
  // 	$.ajax({
  // 			url: base_url+"auth/forgot_password",
  // 			type: "POST",
  // 			data:data,
  // 			success: function (response) {
  // 				var obj = jQuery.parseJSON( response );
  // 				if(obj.success == 1){
  // 					$("#succ_msg_for").show();
  // 					$("#err_msg_for").hide();
  // 					$('#email').val('');
  // 					$("#frmsubpass").attr('disabled',false);
  // 				}
  // 				else{
  // 					$("#succ_msg_for").hide();
  // 					$("#errMsg").html(obj.message);
  // 					$("#err_msg_for").show();
  // 					$("#frmsubpass").attr('disabled',false);
  // 				}
  // 				setTimeout(function() {
  // 				$("#succ_msg_for").hide();
  // 				$("#err_msg_for").hide();
  // 				  }, 5000);

  // 			 }
  // 		});
  // 		unloading();

  //    });

  // $("#for_password").click(function(){
  // 	var data = {'email' : $('#email').val()};
  // 	alert(data);
  //   });

  /***** reset password *****/
  $("#resetPassword").validate({
    rules: {
      new: {
        required: true,
        minlength: 8,
      },
      new_confirm: {
        equalTo: "#new",
      },
    },
    messages: {
      new: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_password,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Das Passwort muss mindestens 8 Zeichen lang sein',
      },
      new_confirm: {
        equalTo:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Confirm_password_doesnt_match,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      $("#frmrestpass").attr("disabled", "");
      form.submit();
    },
  });

  $("#user_update_profile").validate({
    rules: {
      first_name: { required: true },
      last_name: { required: true },
      /*telephone:{ required: true,
			minlength:8 },
		location:{ required: true },
		post_code:{ required: true,
			maxlength:5 },
		city:{ required: true },*/
      password: {
        required: true,
        minlength: 8,
      },
      confirm_pass: {
        equalTo: "#password",
      },
    },
    messages: {
      first_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_first_name,
      },
      last_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_last_name,
      },
      /*telephone:{
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i> Please enter phone number',
     		minlength: '<i class="fas fa-exclamation-circle mrm-5"></i> Bitte mindestens 8-stellige Nummer eingeben'
     	},
		location:{
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i> Please enter your location'
     	},
     	post_code:{
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i> Please enter postal code',
     		maxlength: '<i class="fas fa-exclamation-circle mrm-5"></i> Enter maximum 5 digits postcode'
     	},
     	city:{
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i> Please enter city name'
     	},*/
      password: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_password,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Das Passwort muss mindestens 8 Zeichen lang sein',
      },
      confirm_pass: {
        equalTo:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Confirm_password_doesnt_match,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      var token = true;

      // if($('[name="country"]:checked').length == 0){
      // 	$("#country_err").text('Please select country');
      // 	 token =false;
      // }else{ $("#country_err").text(''); }

      if ($('[name="gender"]:checked').length == 0) {
        $("#gender_err").text("Please select gender");
        token = false;
      } else {
        $("#gender_err").text("");
      }
      /*if($('#latitude').val()==''){
			$('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Please enter valid address');
			token =false;
		}
		else { $("#addr_err").html(''); }*/

      if (token == false) return false;
      else form.submit();
    },
  });

  /***** reset password *****/
  $("#chgPassword").validate({
    rules: {
      old: {
        required: true,
      },
      new: {
        required: true,
        minlength: 8,
      },
      new_confirm: {
        equalTo: "#new",
      },
    },
    messages: {
      old: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_old_password,
      },
      new: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_password,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Das Passwort muss mindestens 8 Zeichen lang sein',
      },
      new_confirm: {
        equalTo:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Confirm_password_doesnt_match,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      //$("#frmrestpass").attr('disabled','');
      loading();
      $.post(
        base_url + "auth/change_password",
        $("#chgPassword").serialize(),
        function (data) {
          //console.log(data);
          var obj = jQuery.parseJSON(data);
          if (obj.success == "1") {
            $(".alert-success.alert_message #alert_message").html(
              "<strong>Message ! </strong>" + obj.message
            );
            $(".alert-danger.alert_message").css("display", "none");
            $(".alert-danger.alert_message").addClass("display-n");

            $(".alert-success.alert_message").css("display", "block");
            $(".alert-success.alert_message").removeClass("display-n");
            $("#chgPassword").trigger("reset");
            setTimeout(function () {
              $(".alert-success.alert_message").css("display", "none");
            }, 3000);
          } else {
            $(".alert-danger.alert_message #alert_message").html(
              "<strong>Message ! </strong>" + obj.message
            );
            $(".alert-danger.alert_message").css("display", "block");
            $(".alert-success.alert_message").addClass("display-n");
            $(".alert-danger.alert_message").removeClass("display-n");
            setTimeout(function () {
              $(".alert-danger.alert_message").css("display", "none");
            }, 3000);
            //location.reload();
          }
          unloading();
        }
      );
      return false;
      //form.submit();
    },
  });

  /***** employee *****/
  $("#Frmemployee").validate({
    rules: {
      first_name: { required: true },
      last_name: { required: true },
      telephone: { required: true, minlength: 8 },
      email: {
        required: true,
        email: true,
        remote: { url: base_url + "auth/checkemail", type: "post" },
      },
      password: {
        required: true,
        minlength: 8,
      },
      cpassword: {
        required: true,
        equalTo: "#password",
      },
    },
    messages: {
      first_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_first_name,
      },
      last_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_last_name,
      },
      telephone: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_phone_number,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Bitte mindestens 8-stellige Nummer eingeben',
      },
      email: {
        email:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Please enter a valid email address',
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_email_id,
        remote: jQuery.validator.format(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Diese E-Mail Adresse ist bereits vergeben'
        ),
      },
      password: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_password,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Das Passwort muss mindestens 8 Zeichen lang sein',
      },
      cpassword: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' + please_enter_confirm_password,
        equalTo:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Confirm_password_doesnt_match,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      $("#subMyemp").attr("disabled", "");
      form.submit();
      setTimeout(function () {
        $("#Frmemployee").trigger("reset");
      }, 5000);
    },
  });

  $(document).on("click", ".remove_img", function () {
    //var id= $(this).attr('id');
    var field = $(this).attr("data-id");
    var data = {
      id: $(this).attr("id"),
      field: $(this).attr("data-id"),
      old_image: $("#old_image" + field).val(),
    };
    var ids = $(this).attr("data-id");
    loading();
    $.ajax({
      url: base_url + "merchant/remove_image",
      type: "POST",
      data: data,
      success: function (response) {
        if (response) {
          //alert();
          $("#imgInp" + ids).attr(
            "src",
            base_url + "assets/frontend/images/uploded-icon-with-text.svg"
          );
          unloading();
        } else {
          unloading();
        }
      },
    });
  });

  $(document).on("change", "#profile_img", function (event) {
    var upath = document.getElementById("profile_img").files[0].name,
      path = URL.createObjectURL(event.target.files[0]),
      extension = upath.substr(upath.lastIndexOf(".") + 1);
    switch (extension) {
      case "jpg":
      case "png":
      case "svg":
      case "jpeg":
        $("#EmpProfile").attr("src", path);
        break;
      default:
        $("#imgerror").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>ungültiges Bildformat'
        );
        return false;
    }
  });

  $("#emp_registration").validate({
    rules: {
      first_name: { required: true },
      last_name: { required: true },
      telephone: { required: true, minlength: 8 },
      password: {
        required: true,
        minlength: 8,
      },
      confirm_pass: {
        equalTo: "#password",
      },
    },
    messages: {
      first_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_first_name,
      },
      last_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_last_name,
      },
      telephone: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_phone_number,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Bitte mindestens 8-stellige Nummer eingeben',
      },
      password: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_password,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Das Passwort muss mindestens 8 Zeichen lang sein',
      },
      confirm_pass: {
        equalTo:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Confirm_password_doesnt_match,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      var token = true;
      if (!$("#terms").is(":checked")) {
        $("#terms_err").text("Bitte akzeptiere die Nutzungsbedingungen.");
        token = false;
      } else {
        $("#terms_err").text("");
      }

      if (token == false) return false;
      else form.submit();
    },
  });

  $("#frmmyEmployee").validate({
    rules: {
      first_name: { required: true },
      last_name: { required: true },
      email: {
        required: true,
        email: true,
        remote: { url: base_url + "auth/checkemail", type: "post" },
      },
      password: {
        required: true,
      },
      cpassword: {
        required: true,
        equalTo: "#password",
      },
    },
    messages: {
      first_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_first_name,
      },
      last_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_last_name,
      },
      email: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_email_id,
        remote: jQuery.validator.format(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Diese E-Mail Adresse ist bereits vergeben'
        ),
      },
      password: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_password,
      },
      cpassword: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' + please_enter_confirm_password,
        equalTo:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Confirm_password_doesnt_match,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      var token = true;
      if ($("input[name='days[]']:checked").length == 0) {
        //alert('if');
        $(".first_Err").text("Bitte Öff nungszeiten festlegen");
        token = false;
      } else {
        var values = new Array();
        $.each($("input[name='days[]']:checked"), function () {
          var day = $(this).val();

          if ($('[name="' + day + '_start"]:checked').length == 0) {
            $("#Serr_" + day).html(
              "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Startzeit auswählen"
            );
            token = false;
          } else {
            $("#Serr_" + day).html("");
          }

          if ($('[name="' + day + '_end"]:checked').length == 0) {
            //alert('e '+day);
            $("#Eerr_" + day).html(
              "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Endzeit auswählen"
            );
            token = false;
          } else {
            $("#Eerr_" + day).html("");
          }

          //return false;
          if (
            $('[name="' + day + '_start"]:checked').val() != "" &&
            $('[name="' + day + '_end"]:checked').val() != "" &&
            $('[name="' + day + '_end"]:checked').val() <=
              $('[name="' + day + '_start"]:checked').val()
          ) {
            $("#Eerr_" + day).html(
              "<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"
            );
            token = false;
          } else {
            if (
              $('[name="' + day + '_start"]:checked').length != 0 &&
              $('[name="' + day + '_end"]:checked').length != 0
            ) {
              $("#Eerr_" + day).html("");
            }
          }

          if (
            $('[name="' + day + '_start_two"]:checked').val() != "" &&
            $('[name="' + day + '_end_two"]:checked').val() != "" &&
            $('[name="' + day + '_end_two"]:checked').val() <=
              $('[name="' + day + '_start_two"]:checked').val()
          ) {
            $("#Eerrtwo_" + day).html(
              "<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"
            );
            token = false;
          } else {
            if (
              $('[name="' + day + '_start_two"]:checked') != "" &&
              $('[name="' + day + '_end_two"]:checked').val() != ""
            )
              $("#Eerrtwo_" + day).html("");
          }

          if ($('[name="' + day + '_start_two"]:checked').val() == "") {
            $("#Serrtwo_" + day).html(
              "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Startzeit auswählen"
            );
            token = false;
          } else {
            $("#Serrtwo_" + day).html("");
          }

          if ($('[name="' + day + '_end_two"]:checked').val() == "") {
            $("#Eerrtwo_" + day).html(
              "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Endzeit auswählen"
            );
            token = false;
          } else {
            $("#Eerrtwo_" + day).html("");
          }

          if (
            $('[name="' + day + '_start_two"]:checked').val() != "" &&
            $('[name="' + day + '_end_two"]:checked').val() != "" &&
            $('[name="' + day + '_end_two"]:checked').val() <=
              $('[name="' + day + '_start_two"]:checked').val()
          ) {
            $("#Eerrtwo_" + day).html(
              "<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"
            );
            token = false;
          } else {
            if (
              $('[name="' + day + '_start_two"]:checked').val() != "" &&
              $('[name="' + day + '_end_two"]:checked').val() != ""
            )
              $("#Eerrtwo_" + day).html("");
          }
          if (
            $('[name="' + day + '_start_two"]:checked').val() != "" &&
            $('[name="' + day + '_end"]:checked').val() != "" &&
            $('[name="' + day + '_end"]:checked').val() >=
              $('[name="' + day + '_start_two"]:checked').val()
          ) {
            $("#Serrtwo_" + day).html(
              "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte gültige Startzeit wählen"
            );
            token = false;
          } else {
            if (
              $('[name="' + day + '_start_two"]:checked').val() != "" &&
              $('[name="' + day + '_end"]:checked').val() != ""
            )
              $("#Serrtwo_" + day).html("");
          }
        });
        $("#busType_err").text("");

        /*if($("#commission_check").is(':checked'))
				{
					if($("#commission").val() ==""){
						token = false;
						$("#commission_error").html("<i class='fas fa-exclamation-circle mrm-5'></i>Please enter commission %");
					}
					else if($("#commission").val() > 100){
						token = false;
						$("#commission_error").html("<i class='fas fa-exclamation-circle mrm-5'></i>Commission % not greater than 100");	
					}
					else
						$("#commission_error").html("");
				}*/
      }

      if (token == false) {
        return false;
      } else {
        $("#submitEmp").attr("disabled", "");
        form.submit();
        setTimeout(function () {
          $("#frmmyEmployee").trigger("reset");
        }, 5000);
      }
    },
  });

  $("#employee_update_profile").validate({
    rules: {
      first_name: { required: true },
      last_name: { required: true },
      telephone: { required: true, minlength: 8 },
      email: {
        required: true,
        email: true,
        remote: { url: base_url + "auth/checkemail_for_profile", type: "post" },
      },
    },
    messages: {
      first_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_first_name,
      },
      last_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_last_name,
      },
      telephone: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_phone_number,
        minlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Bitte mindestens 8-stellige Nummer eingeben',
      },
      email: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_email_id,
        remote: jQuery.validator.format(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Diese E-Mail Adresse ist bereits vergeben'
        ),
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      form.submit();
    },
  });

  $(document).on("change", ".changeOnlineBooking", function () {
    var id = $(this).attr("data-id");
    if ($(this).is(":checked")) var status = 1;
    else var status = 0;

    var data = { tid: id, status: status };

    loading();
    $.ajax({
      url: base_url + "merchant/employee_onlinestatus",
      type: "POST",
      data: data,
      success: function (response) {
        /*if(response){}
					else{ }*/
        unloading();
      },
    });
  });
  $(document).on("click", ".deleteemployeeclick", function () {
    var id = $(this).attr("id");
    $("#deleteid").val(id);
  });

  $(document).on("click", "#deleteemployee", function () {
    var id = $("#deleteid").val();
    var data = { tid: id };

    loading();

    $.ajax({
      url: base_url + "merchant/deleteemployee",
      type: "POST",
      data: data,
      success: function (response) {
        var obj = jQuery.parseJSON(response);
        if (obj.success == "1") {
          //if(response){
          $("#row_" + obj.ids).remove();
          $("#deleteid").val(0);
        }
        $("#close_box").trigger("click");
        unloading();
      },
    });
  });

  $(document).on("click", "#deletecustomer", function () {
    var id = $("#deleteid").val();

    loading();
      $.ajax({
        url: base_url + "merchant/delete_single_customer",
        type: "POST",
        data: {
          id: id
        },
        success: function(data) {
          var obj = jQuery.parseJSON(data);
          location.reload();
          unloading();
        }

      });
  });

  $(document).on("click", ".shortEmp", function () {
    var lmt = $(this).val();
    var url = base_url + "merchant/employee_listing?limit=" + lmt;
    window.location.href = url;
  });

  $(document).on("click", ".deleteserviceclick", function () {
    var id = $(this).attr("id");
    $("#deleteid").val(id);
  });

  $(document).on("click", "#deleteservice", function () {
    var id = $("#deleteid").val();
    var data = { tid: id };
    loading();
    $.ajax({
      url: base_url + "merchant/deleteservice",
      type: "POST",
      data: data,
      success: function (response) {
        var obj = jQuery.parseJSON(response);
        if (obj.success == "1") {
          //if(response){
          $("#row_" + obj.ids).remove();
          $(".row_" + obj.ids).remove();
          $("#deleteid").val(0);
          var els = $(".categorybar");
          for (const el of els) {
            if(!$(el).next().attr('id')) {
              $(el).remove();
            }
          }
        }
        $("#close_box").trigger("click");
        unloading();
      },
    });
  });

  $(document).on("click", ".shortService", function () {
    var lmt = $(this).val();
    var url = base_url + "merchant/service_listing?limit=" + lmt;
    window.location.href = url;
  });

  $(document).on("click", ".getService", function () {
    var sid = $(this).attr("data-id");
    var fid = $(this).attr("data-fid");
    var data = { id: $(this).attr("id"), sub_catid: sid };
    loading();
    $.ajax({
      url: base_url + "user/get_service",
      type: "POST",
      data: data,
      success: function (response) {
        //var obj = jQuery.parseJSON( response );
        $("#allservice_div").html(response);
        var act = $("#activeAllresult").val();

        //alert(act);

        $("#div_" + act)
          .removeClass("bggreengradient")
          .addClass("bgwhite");
        $("#span_" + act)
          .removeClass("colorwhite")
          .addClass("color999");

        $("#div_" + fid)
          .removeClass("bgwhite")
          .addClass("bggreengradient");
        $("#span_" + fid)
          .removeClass("color999")
          .addClass("colorwhite");

        $("#activeAllresult").val(fid);
        unloading();
      },
    });
  });

  $(document).on("click", ".getServiceOther", function () {
    var sid = $(this).attr("data-id");
    var mid = $(this).attr("main-id");
    var fid = $(this).attr("data-fid");
    var data = { id: $(this).attr("id"), sub_catid: sid };
    loading();
    $.ajax({
      url: base_url + "user/get_service",
      type: "POST",
      data: data,
      success: function (response) {
        //alert(mid);
        //var obj = jQuery.parseJSON( response );
        $("#ChgDivData_" + mid).html(response);
        var act = $("#activeAllresult_" + mid).val();
        $("#divs_" + act)
          .removeClass("bggreengradient")
          .addClass("bgwhite");
        $("#spans_" + act)
          .removeClass("colorwhite")
          .addClass("color999");

        $("#divs_" + fid)
          .removeClass("bgwhite")
          .addClass("bggreengradient");
        $("#spans_" + fid)
          .removeClass("color999")
          .addClass("colorwhite");

        $("#activeAllresult_" + mid).val(fid);
        unloading();
      },
    });
  });

  $(document).on("click", ".bookingSelect", function () {
    var bid = $(this).attr("id");
    var mid = $(this).attr("data-id");
    var price = $(this).val();
    var data = { id: bid, price: price, mid: mid };
    loading();
    $.ajax({
      url: base_url + "booking/addtobooking",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          if (obj.count != 0) {
            $("#service_count").html(obj.count);
            if (obj.count == 1)
              $("#service_text").html("Service");
            else
              $("#service_text").html("Services");
            $("#total_amt").html(obj.total);
            $("#my_cart_div").show();

            //$( "#mydiv" ).hasClass( "foo" )
            $(".class-" + bid).removeClass(obj.revCls);
            $(".class-" + bid).addClass(obj.addCls);
            $(".class-" + obj.deleteid).removeClass("selectedBtn");
            $(".class-" + obj.deleteid).addClass("btn-border-orange");
            //$("#"+bid).removeClass(obj.revCls);
            //$("#"+bid).addClass(obj.addCls);
            unloading();
          } else {
            $("#my_cart_div").hide();
            $(".class-" + bid).removeClass(obj.revCls);
            $(".class-" + bid).addClass(obj.addCls);
            unloading();
          }

          $(".mancat_check_icon").each(function () {
            $(this).addClass("display-n");
          });

          $(".selectedBtn").each(function () {
            var cid = $(this).attr("data-cid");
            var fid = $(this).attr("data-fid");

            $("#checked_mcat_" + cid).removeClass("display-n");
            $(".checked_fcatc_" + fid).removeClass("display-n");
          });
        } else if (obj.success == 2) {
          unloading();
          var url = $("#select_url").val();
          $("#error_message").css("display", "");
          $("#service-loging-check").modal("show");
          $("#addservice_message").html(obj.message);
          //window.location.href = base_url+'auth/login/'+url;
          $(window).scrollTop(200);
          setTimeout(function () {
            $("#error_message").hide();
          }, 5000);
        } else if (obj.success == 3) {
          unloading();
          $("#online_booking_message").modal("show");
        } else if (obj.success == 4) {
          unloading();
          $("#openLoginPopup").modal("show");
        } else {
          unloading();
          var url = $("#select_url").val();
          $("#error_message").css("display", "");
          $("#service-loging-check").modal("show");
          //window.location.href = base_url+'auth/login/'+url;
          $(window).scrollTop(200);
          setTimeout(function () {
            $("#error_message").hide();
          }, 5000);
        }
      },
    });
  });

  $(document).on("click", ".remove_service", function () {
    var id = $(this).attr("id");
    var sid = $(this).attr("data-id");
    var data = { cart_id: id };
    loading();
    $.ajax({
      url: base_url + "booking/remove_service",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.count != 0) {
          if (obj.success == 1) {
            $("#service_count").html(obj.count);
            $("#total_amt").html(obj.total);
            $("#row_" + id).remove();
          }
          window.location.reload();
          unloading();
        } else {
          window.location.href = base_url + "user/service_provider/" + sid;
          unloading();
        }
      },
    });
  });

  $(document).on("click", ".delete_service_from_merchant", function () {
    var id = $(this).attr("id");
    var sid = $(this).attr("data-id");
    var data = { cart_id: id };
    loading();
    $.ajax({
      url: base_url + "booking/remove_service",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);

        if (obj.success == 1) {
          $("#service_count").html(obj.count);
          $("#total_amt").html(obj.total);
          $("#row_" + id).remove();
        }
        window.location.reload(true);
        unloading();
      },
    });
  });

  $("#standardprice").on("change", function () {
    if ($("#standardprice").val() != "")
      $("#stdpriceChg").html($("#standardprice").val() + " €");
    else $("#stdpriceChg").html("0 €");
  });

  $(document).on("click", "#confirm_booking_next", function () {
    var empid = $('[name="employee_select"]:checked').val();
    // console.log("empid-----------", empid);
    var data = { mer_id: $(this).val(), employe_id: empid };
    if ($('[name="employee_select"]:checked').length != 0) {
      var empid = $('[name="employee_select"]:checked').val();
      $("#employe_err").html("");
    } else {
      $("#emplNametxt").focus();
      $("#employe_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_select_employee
      );
      return false;
    }
    if ($('[name="time"]:checked').length != 0) {
      $("#time_err").html("");
    } else {
      $("#time_err").focus();
      $("#time_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i> Bitte wähle eine Uhrzeit'
      );

      return false;
    }
    loading();
    var data = $("#selectFilters").serialize();
    var url = base_url + "booking/user_booking_comfirm?" + data;
    window.location.href = url;
  });

  $(document).on("click", "#confirm_booking", function () {
    /*var empid=$('[name="employee_select"]:checked').val();
 	var data = {'mer_id' : $(this).val(),'employe_id' : empid};
 	if($('[name="employee_select"]:checked').length !=0){
	 	var empid=$('[name="employee_select"]:checked').val();
	 	$('#employe_err').html('');	
 	}
 	else
 	{   $("#emplNametxt").focus();
 		$('#employe_err').html('<i class="fas fa-exclamation-circle mrm-5"></i> please select employee');
 		return false;	
 	}
    if($('[name="time"]:checked').length !=0){
	 	$('#time_err').html('');	
 	}
 	else
 	{   $("#time_err").focus();
 		$('#time_err').html('<i class="fas fa-exclamation-circle mrm-5"></i> please select a time slot');
 		
 		return false;	
 	}*/

    loading();
    $.ajax({
      url: base_url + "booking/booking_confirm",
      type: "POST",
      data: $("#selectFilters").serialize(),
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.count != 0) {
          if (obj.success == 1) {
            //alert();
            //window.location.href = base_url+obj.url;
            $("#booking_done_show").modal({
              backdrop: "static",
              keyboard: false,
            });
          } else {
            window.scrollTo(0, 0);
            $(".alert-danger.alert_message").removeClass("display-n");
            $(".alert-danger.alert_message").css("display", "block");
            $(".alert-danger.alert_message #alert_message").html(obj.message);
          }
          unloading();
        } else {
          window.scrollTo(0, 0);
          //console.log(obj);
          //$(".alert-success.alert_message").css('display','none');
          $(".alert-danger.alert_message").removeClass("display-n");
          $(".alert-danger.alert_message").css("display", "block");
          $(".alert-danger.alert_message #alert_message").html(obj.message);
          //window.location.href = base_url+obj.url;
          unloading();
        }
      },
    });
  });

  $(document).on("click", ".shortBook", function () {
    var lmt = $(this).val();
    var st = $("#bookStatus").val();
    if (st == "") var url = base_url + "user/all_bookings?limit=" + lmt;
    else
      var url = base_url + "user/all_bookings?status=" + st + "&limit=" + lmt;

    window.location.href = url;
  });

  $(document).on("click", ".shortBookS", function () {
    var st = $(this).val();
    var lmt = $("#bookLimit").val();
    if (lmt == "") var url = base_url + "user/all_bookings?status=" + st;
    else
      var url = base_url + "user/all_bookings?status=" + st + "&limit=" + lmt;

    window.location.href = url;
  });

  $(document).on("click", ".shortMyfav", function () {
    var lmt = $(this).val();
    var url = base_url + "user/favourite_salon?limit=" + lmt;
    window.location.href = url;
  });

  $(document).on("click", ".rebooking", function () {
    var bid = $(this).attr("id");
    var mid = $(this).attr("data-id");
    var data = { id: bid, mid: mid };
    loading();
    $.ajax({
      url: base_url + "booking/rebooking",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.status == 1) {
          window.location.href = base_url + "booking/booking_detail/" + mid;
          unloading();
        } else {
          $("#alert_message").html(obj.msg);
          $("#error_message").removeClass("display-n");
          unloading();
        }
      },
    });
  });

  $(document).on("click", ".shortEmpbook", function () {
    var lmt = $(this).val();
    var url = base_url + "employee/all_bookings?limit=" + lmt;
    window.location.href = url;
  });

  $(document).on("click", ".booking_cancels", function () {
    $("#booking-details-modal").modal("hide");
    var id = $(this).attr("id");
    
    $("#bookingid").val(id);
    $("#res_err").hide();    
  });

  $(document).on("click", "#cancel_booking", function () {
    //var id = $('#bookingid').val();
    var id = $("#bookingid").val();
    if (!id) {
      id = $(this).attr("data-id");  
    }
    var uid = $(this).attr("data-uid");
    if ($("#check_access").val() == "user") {
      if ($('[name="reason"]:checked').length == 0) {
        $("#res_err").show();
        return false;
      }
    }
    $("#booking-details-modal").modal("hide");
    var res = $('input[name="reason"]:checked').val();
    var data = { book_id: id, reason: res };
    //loading();
    $("#res_err").hide();
    Swal.fire({
      title: are_you_sure,
      html: you_want_cancel_book,
      type: "warning",
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonClass: 'delete-booking-confirm-button',
      cancelButtonClass: 'delete-booking-confirm-button',
      confirmButtonText: 'Buchung stornieren',
      cancelButtonText: abort,
    }).then((result) => {
      if (result.value) {
        loading();
        $.ajax({
          url: base_url + "booking/booking_cancel",
          type: "POST",
          data: data,
          success: function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.success == 1) {
              // alert('dd');

              //$('#divStatus_'+obj.id).hide();
              if ($("#check_access").val() == "user") {
                location.reload();
              } else if (sessionAccess == "employee") {
                location.reload();
              } else {
                //alert($('#action_from').val());
                if ($("#action_from").val() == "list") {
                  $("#service-cencel-table").modal("hide");

                  var start = "";
                  var end = "";
                  var order = $("#orderby").val();
                  var shortby = $("#shortby").val();
                  var search = $.trim($("#sch_data_cuss").val());
                  var status = $("input[name=booking_st]:checked").val();
                  if (typeof status == "undefined") status = "";

                  var value = $("input[name=filter]:checked").val();
                  if (typeof value == "undefined") value = "";

                  if (value == "date") {
                    var start = $("#start_date").val();
                    var end = $("#end_date").val();
                  }

                  var page = $("#pagination .active").text();
                  var url =
                    base_url +
                    "merchant/getbokkinglist_ajax/" +
                    page +
                    "?short=" +
                    value +
                    "&status=" +
                    status +
                    "&start_date=" +
                    start +
                    "&end_date=" +
                    end +
                    "&orderby=" +
                    order +
                    "&shortby=" +
                    shortby +
                    "&search=" +
                    search;

                  getBookingList(url);
                } else if ($("#action_from").val() == "calendar")
                  location.reload();
                else getClientProfile(uid);
                //location.reload();
              }
            } else {
              $("#close_cancel").trigger("click");
              $("#error_message").css("display", "");
              $("#alert_message").html(obj.msg);
            }
            setTimeout(function () {
              $("#error_message").css("display", "none");
            }, 5000);
            unloading();
          },
        });
      } else {
        if ($(this).attr("data-click") == "list") id = "";
        if (id != "") {
          getBokkingDetail(id);
        }
        return false;
      }
    });
  });

  $(document).on("click", "#cancel_booking_old", function () {
    var id = $("#bookingid").val();

    if ($("#check_access").val() == "user") {
      if ($('[name="reason"]:checked').length == 0) {
        $("#res_err").show();
        return false;
      }
    }
    var res = $('input[name="reason"]:checked').val();
    var data = { book_id: id, reason: res };
    loading();
    $("#res_err").hide();
    $.ajax({
      url: base_url + "booking/booking_cancel",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          //$('#divStatus_'+obj.id).hide();
          if ($("#check_access").val() == "user") {
            location.reload();
          } else {
            if ($("#action_from").val() == "list") {
              $("#service-cencel-table").modal("hide");
              var start = "";
              var end = "";
              var order = $("#orderby").val();
              var shortby = $("#shortby").val();
              var search = $.trim($("#sch_data_cuss").val());
              var status = $("input[name=booking_st]:checked").val();
              if (typeof status == "undefined") status = "";

              var value = $("input[name=filter]:checked").val();
              if (typeof value == "undefined") value = "";

              if (value == "date") {
                var start = $("#start_date").val();
                var end = $("#end_date").val();
              }

              var page = $("#pagination .active").text();
              var url =
                base_url +
                "merchant/getbokkinglist_ajax/" +
                page +
                "?short=" +
                value +
                "&status=" +
                status +
                "&start_date=" +
                start +
                "&end_date=" +
                end +
                "&orderby=" +
                order +
                "&shortby=" +
                shortby +
                "&search=" +
                search;
              getBookingList(url);
            } else location.reload();
          }
          //$('#divStatus_'+obj.id).hide();
          //$('#CssStatus_'+obj.id).html('Cancelled');
          //$("#CssStatus_"+obj.id).removeClass('conform').addClass('cencel');
          //$("#close_cancel").trigger('click');
          //$('#bookingid').val('');
          //$("#divSta_"+obj.id).hide();
          //$("#divRes_"+obj.id).hide();
        } else {
          $("#close_cancel").trigger("click");
          $("#error_message").css("display", "");
          $("#alert_message").html(obj.msg);
        }
        setTimeout(function () {
          $("#error_message").css("display", "none");
        }, 5000);
        unloading();
      },
    });
  });

  $(document).on("click", ".shortMerbook", function () {
    var lmt = $(this).val();
    var url = base_url + "merchant/booking_listing?limit=" + lmt;
    window.location.href = url;
  });

  $(document).on("click", ".complete_book", function () {
    var id = $(this).attr("id");
    $("#book_conf").val(id);
  });

  $(document).on("click", "#booking_done", function () {
    var id = $("#book_conf").val();
    var data = { book_id: id };
    loading();
    $.ajax({
      url: base_url + "merchant/booking_confirm",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          $("#service-complete-table").modal("hide");
          if ($("#action_from").val() == "list") {
            var start = "";
            var end = "";
            var order = $("#orderby").val();
            var shortby = $("#shortby").val();
            var search = $.trim($("#sch_data_cuss").val());
            var status = $("input[name=booking_st]:checked").val();
            if (typeof status == "undefined") status = "";

            var value = $("input[name=filter]:checked").val();
            if (typeof value == "undefined") value = "";

            if (value == "date") {
              var start = $("#start_date").val();
              var end = $("#end_date").val();
            }

            var page = $("#pagination .active").text();
            var url =
              base_url +
              "merchant/getbokkinglist_ajax/" +
              page +
              "?short=" +
              value +
              "&status=" +
              status +
              "&start_date=" +
              start +
              "&end_date=" +
              end +
              "&orderby=" +
              order +
              "&shortby=" +
              shortby +
              "&search=" +
              search;
            getBookingList(url);
          } else location.reload();
          // loading();
          //$('#divStatus_'+obj.id).hide();
          //window.location.href = base_url+'merchant/booking_listing';
          //location.reload();
        }
        unloading();
      },
    });
  });

  $(document).on("click", ".favourite_click", function () {
    var id = $(this).attr("id");
    $("#favouriteid").val(id);
  });

  $(document).on("click", "#un_favourite", function () {
    var id = $("#favouriteid").val();
    var data = { fav_id: id };
    loading();
    $.ajax({
      url: base_url + "user/unfavourite_salon",
      type: "POST",
      data: data,
      success: function (data) {
        //var obj = jQuery.parseJSON( data );
        if (data == 1) {
          window.location.href = base_url + "user/favourite_salon";
        }
        unloading();
      },
    });
  });

  //boking confirm from marchant
  $(document).on("click", "#mercjhant_booking_confirm", function () {
    //console.log("style time",$("#merchantBookingForm").serialize())
    // alert($("#merchantBookingForm").serialize())
    loading();
    $.ajax({
      url: base_url + "booking/confirm_merchant_booking",
      type: "POST",
      data: $("#merchantBookingForm").serialize(),
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          //$('#divStatus_'+obj.id).hide();
          window.location.href = base_url + obj.url;
        } else {
          Swal.fire({
            title: obj.message,
            width: 600,
            padding: "3em",
          });
        }
        unloading();
      },
    });
  });

  $(document).on("click", ".reSchedule_book", function () {
    var id = $(this).attr("id");
    var eid = $(this).attr("data-eid");
    var bid = $(this).attr("data-bid");
    if (bid == undefined) {
      bid = "";
    }

    //$('#reSchedule_id').val(id);
    //$('#reseid').val(eid);

    loading();
    $.ajax({
      url: base_url + "rebook/reshedule_form",
      type: "POST",
      data: { emp_id: eid, book_id: id },
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          $("#frmReshedule").html(obj.html);
          $(".modal").modal("hide");
          $("#service-reschedule-table").modal("show");

          $("#close_resch").attr("data-bid", bid);
          var slideIntil = $('input[name="chg_date"]:checked').attr(
            "data-slide"
          );
          var it = parseInt(slideIntil);
          $(".slider-wick-slick").slick({
            dots: false,
            infinite: false,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 2000,
            arrows: true,
            initialSlide: 0,
            variableWidth: true,
          });
          $(".slider-wick-slick").slick("slickGoTo", it);
          gettimeslotForReshedule();
        } else {
          $("#date_err").html(
            '<i class="fas fa-exclamation-circle mrm-5"></i>' + obj.msg
          );
        }
        unloading();
      },
    });

    //~ $("#chg_date").val('');
    //~ $("#chg_date_err").html('');

    //~ $("#chg_time_err").html('');

    //~ $('#reSchedule_id').val(id);
    //~ $('#reseid').val(eid);
  });

  $(document).on("change", ".chg_date", function () {
    gettimeslotForReshedule();
  });

  function gettimeslotForReshedule() {
    var date = $('input[name="chg_date"]:checked').val();
    var reseid = $("#reseid").val();
    var bkid = $("#reSchedule_id").val();
    loading();

    if (sessionAccess == "employee") {
      var url = "employee";
    } else {
      var url = "merchant";
    }
    // var urls=$('#select_url').val();
    $.post(
      base_url + url + "/get_opning_hour",
      { date: date, eid: reseid, bk_id: bkid },
      function (data) {
        //console.log(data);
        var obj = jQuery.parseJSON(data);
        if (obj.success == "1") {
          $("#time_err").html("");
          if (obj.message != 'success') {
            $("#dropdownTime").css("display", "flex");
            $("#dropdownTime").css("flex-direction", "column");
            $("#dropdownTime").css("align-items", "center");
            $("#dropdownTime").css("justify-content", "center");
          } else {
            $("#dropdownTime").css("display", "block");
          }
          $("#dropdownTime").html(obj.html);
          if ($('input[name="chg_time"]:checked').length != 0) {
            var heith = 0;
            $(".select-time-price").each(function () {
              var heit = $(this).height();

              if ($(this).hasClass("selected_time")) {
                //alert(heit);
                return false;
              } else {
                heith = heith + heit;
              }
            });
            //alert(heith);
            $("#dropdownTime").animate(
              { scrollTop: heith },
              500,
              "swing",
              function () {}
            );
          }
        } else {
          $("#time_err").html(obj.message);
          //$(".alert_message").css('display','block');

          //location.reload();
        }
      }
    );
    unloading();
  }

  $(document).on("click", "#booking_reSch", function () {
    //chg_date
    var date = $('input[name="chg_date"]:checked').val();
    var time = $('input[name="chg_time"]:checked').val();
    $("#close_resch").attr("data-bid", "");
    //alert(date+"--"+time);
    var token = true;
    if (date == undefined || date == "") {
      $("#chg_date_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>Please select start date'
      );
      token = false;
    }
    if (time == undefined || time == "") {
      $("#time_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>Startzeit auswählen'
      );
      token = false;
    }
    if (token == false) {
      return false;
    }

    Swal.fire({
      title: are_you_sure,
      text: "Du möchtest diesen Termin verlegen?",
      type: "warning",
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Bestätigen",
    }).then((result) => {
      if (result.value) {
        loading();
        if (sessionAccess == "employee") {
          var url = "employee";
        } else {
          var url = "merchant";
        }

        $.ajax({
          url: base_url + url + "/booking_reshedule",
          type: "POST",
          data: $("form").serialize(),
          success: function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.success == 1) {
              //$('#divStatus_'+obj.id).hide();
              $("#close_resch").trigger("click");
              ///$("#reshedule-complete-table").modal('show');
              /*Swal.fire(
							  'Rescheduled',
							  obj.msg,
							  'success'
							);*/

              Swal.fire({
                title: "Buchung erfolgreich verlegt",
                html: obj.msg,
                type: "success",
                confirmButtonText: "OK",
                allowOutsideClick: "true",
              }).then(() => {
                if ($("#action_from").val() == "list") {
                  var start = "";
                  var end = "";
                  var order = $("#orderby").val();
                  var shortby = $("#shortby").val();
                  var search = $.trim($("#sch_data_cuss").val());
                  var status = $("input[name=booking_st]:checked").val();
                  if (typeof status == "undefined") status = "";

                  var value = $("input[name=filter]:checked").val();
                  if (typeof value == "undefined") value = "";

                  if (value == "date") {
                    var start = $("#start_date").val();
                    var end = $("#end_date").val();
                  }

                  var page = $("#pagination .active").text();
                  var url =
                    base_url +
                    "merchant/getbokkinglist_ajax/" +
                    page +
                    "?short=" +
                    value +
                    "&status=" +
                    status +
                    "&start_date=" +
                    start +
                    "&end_date=" +
                    end +
                    "&orderby=" +
                    order +
                    "&shortby=" +
                    shortby +
                    "&search=" +
                    search;
                  getBookingList(url);
                }
                // window.location.href = base_url+'merchant/booking_listing';
                else location.reload();
              });

              /*setTimeout(function() {
						 if($('#action_from').val() =='list'){
    						 window.location.href = base_url+'merchant/booking_listing';
							}
							else
								location.reload();

						}, 3000);*/
            } else {
              $("#date_err").html(
                '<i class="fas fa-exclamation-circle mrm-5"></i>' + obj.msg
              );
            }
            unloading();
          },
        });
      } else {
        return false;
      }
    });
  });

  $(document).on("click", "#resheduleSubByuser", function () {
    var token = true;
    var date = $('input[name="chg_date"]:checked').val();
    var time = $('input[name="chg_time"]:checked').val();
    //alert(date+"--"+time);
    var token = true;
    if (date == undefined || date == "") {
      $("#chg_date_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>Please select start date'
      );
      token = false;
    }
    if (time == undefined || time == "") {
      $("#time_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>Startzeit auswählen'
      );
      token = false;
    }
    if (token == false) {
      return false;
    }

    Swal.fire({
      title: are_you_sure,
      text: "Du möchtest diesen Termin verlegen?",
      type: "warning",
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Bestätigen",
    }).then((result) => {
      if (result.value) {
        loading();
        $.ajax({
          url: base_url + "user/booking_reshedule",
          type: "POST",
          data: $("form").serialize(),
          success: function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.success == 1) {
              //$('#divStatus_'+obj.id).hide();
              $("#close_resch").trigger("click");
              //$("#reshedulecompleteByuser").modal('show');
              /*Swal.fire(
							  'Rescheduled',
							   obj.msg,
							  'success'
							);
						 setTimeout(function() {						 
								location.reload();
						}, 3000);*/
              Swal.fire({
                title: "Buchung erfolgreich verlegt",
                html: obj.msg,
                type: "success",
                confirmButtonText: "OK",
                allowOutsideClick: "true",
              }).then(() => {
                location.reload();
              });
            } else {
              $("#date_err").html(
                '<i class="fas fa-exclamation-circle mrm-5"></i>' + obj.msg
              );
            }
            unloading();
          },
        });
      } else {
        return false;
      }
    });
  });

  $(document).on("click", ".customer_chg", function () {
    var lmt = $(this).val();
    var url = base_url + "merchant/customers?limit=" + lmt;
    window.location.href = url;
  });

  $(document).on("click", ".addnote", function () {
    var id = $(this).attr("id");
    $("#booking_ids").val(id);
    $("#booking_id_note").val(id);
  });

  $(document).on("click", ".salon_profile_click", function () {
    var id = $(this).attr("id");
    var salon = $(this).attr("data-id");
    var sids = $("#servicemch" + id).attr("data-service");
    var url =
      base_url + "user/service_provider/" + salon + "?servicids=" + sids;
    window.location.href = url;
  });

  /*$("#frmNotes").validate({
	rules: {
		txtnote: {
			required: true,
		},
		
     },
     messages: {
     	txtnote:{
     		required: '<i class="fas fa-exclamation-circle mrm-5"></i> Please enter customer notes'
     	},
     	
	},
    errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },
    submitHandler: function(form) {


			form.submit();
			
    }
                 
   });*/

  $(document).on("click", "#saveNote_fromlist", function () {
    var id = $("#booking_id_note").val();
    var note = $("#txtnote").val();
    if (note == "") {
      $("#error_notess").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_customer_note
      );
      $("#error_notess").show();
      return false;
    }
    //alert(loadid);
    var data = { id: id, note: note };
    loading();
    $.ajax({
      url: base_url + "merchant/addnotes_fromlist",
      type: "POST",
      data: data,
      success: function (response) {
        location.reload();
      },
    });
  });

  $(document).on("click", ".loadMore", function () {
    var sid = $(this).attr("id");
    var loadid = $(this).attr("data-id");
    //alert(loadid);
    var data = { mid: sid, load_id: loadid };
    loading();
    $.ajax({
      url: base_url + "user/loadmorereview",
      type: "POST",
      data: data,
      success: function (response) {
        $("#allreview").append(response);
        $("#load-" + loadid).remove();
        //var obj = jQuery.parseJSON( response );
        //alert(response);
        //$('#allservice_div').html(response);

        unloading();
      },
    });
  });

  $(document).on("change", "#newsImg", function (event) {
    var upath = document.getElementById("newsImg").files[0].name,
      path = URL.createObjectURL(event.target.files[0]),
      extension = upath.substr(upath.lastIndexOf(".") + 1);
    switch (extension) {
      case "jpg":
      case "png":
      case "svg":
      case "jpeg":
        $("#NewChgImg").attr("src", path).width("340px").height("115px");
        break;
      default:
        $("#imgerror").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>ungültiges Bildformat'
        );
        return false;
    }
  });

  $(document).on("submit", "#FrmNewsletter", function () {
    var token = true;

    if ($("#txtsubject").val() == "") {
      $("#txtsubject_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>' + please_enter_subject
      );
      token = false;
    }
    if ($("#description").val() == "") {
      $("#description_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_description
      );
      token = false;
    }
    if ($("#txtfooter").val() == "") {
      $("#txtfooter_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_add_footer_text
      );
      token = false;
    }
    if (token == false) {
      return false;
    }
    loading();
    //unloading();
  });

  $(document).on("click", ".delete_newsLetter", function () {
    var id = $(this).attr("id");
    $("#news_letter").val(id);
  });

  $(document).on("click", "#remove_newsLetter", function () {
    var id = $("#news_letter").val();
    var data = { tid: id };

    loading();

    $.ajax({
      url: base_url + "merchant/deletenewsLetter",
      type: "POST",
      data: data,
      success: function (response) {
        var obj = jQuery.parseJSON(response);
        if (obj.success == "1") {
          //if(response){
          location.reload();
          $("#Remove_" + obj.ids).remove();
          $("#news_letter").val(0);
        }
        $("#close_box").trigger("click");
        unloading();
      },
    });
  });

  $(document).on("click", ".noshow_book", function () {
    $("#booking-details-modal").modal("hide");
    var id = $(this).attr("id");
    $("#noshowbook_id").val(id);
    $(".commoncancel_cal").attr("data-bid", id);
  });

  $(document).on("click", "#noshow_booking", function () {
    //var id = $('#noshowbook_id').val();
    var id = $(this).attr("data-id");
    var data = { book_id: id };
    $("#booking-details-modal").modal("hide");
    Swal.fire({
      title: are_you_sure,
      text: you_want_noshow_book,
      type: "warning",
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: Submit,
      cancelButtonText: abort,
    }).then((result) => {
      if (result.value) {
        loading();
        $.ajax({
          url: base_url + "booking/booking_noshow",
          type: "POST",
          data: data,
          success: function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.success == 1) {
              if ($("#action_from").val() == "list") {
                $("#service-noshow-table").modal("hide");
                var start = "";
                var end = "";
                var order = $("#orderby").val();
                var shortby = $("#shortby").val();
                var search = $.trim($("#sch_data_cuss").val());
                var status = $("input[name=booking_st]:checked").val();
                if (typeof status == "undefined") status = "";

                var value = $("input[name=filter]:checked").val();
                if (typeof value == "undefined") value = "";

                if (value == "date") {
                  var start = $("#start_date").val();
                  var end = $("#end_date").val();
                }

                var page = $("#pagination .active").text();
                var url =
                  base_url +
                  "merchant/getbokkinglist_ajax/" +
                  page +
                  "?short=" +
                  value +
                  "&status=" +
                  status +
                  "&start_date=" +
                  start +
                  "&end_date=" +
                  end +
                  "&orderby=" +
                  order +
                  "&shortby=" +
                  shortby +
                  "&search=" +
                  search;

                getBookingList(url);
              } else location.reload();
            } else {
              $("#close_cancel").trigger("click");
              $("#error_message").css("display", "");
              $("#alert_message").html(obj.msg);
            }
            setTimeout(function () {
              $("#error_message").css("display", "none");
            }, 5000);
            unloading();
          },
        });
      } else {
        if ($(this).attr("data-click") == "list") id = "";
        if (id != "") {
          getBokkingDetail(id);
        }
        return false;
      }
    });
  });

  $(document).on("click", "#noshow_booking_old", function () {
    var id = $("#noshowbook_id").val();
    var data = { book_id: id };
    loading();
    $.ajax({
      url: base_url + "booking/booking_noshow",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          if ($("#action_from").val() == "list") {
            $("#service-noshow-table").modal("hide");
            var start = "";
            var end = "";
            var order = $("#orderby").val();
            var shortby = $("#shortby").val();
            var search = $.trim($("#sch_data_cuss").val());
            var status = $("input[name=booking_st]:checked").val();
            if (typeof status == "undefined") status = "";

            var value = $("input[name=filter]:checked").val();
            if (typeof value == "undefined") value = "";

            if (value == "date") {
              var start = $("#start_date").val();
              var end = $("#end_date").val();
            }

            var page = $("#pagination .active").text();
            var url =
              base_url +
              "merchant/getbokkinglist_ajax/" +
              page +
              "?short=" +
              value +
              "&status=" +
              status +
              "&start_date=" +
              start +
              "&end_date=" +
              end +
              "&orderby=" +
              order +
              "&shortby=" +
              shortby +
              "&search=" +
              search;

            getBookingList(url);
          } else location.reload();
        } else {
          $("#close_cancel").trigger("click");
          $("#error_message").css("display", "");
          $("#alert_message").html(obj.msg);
        }
        setTimeout(function () {
          $("#error_message").css("display", "none");
        }, 5000);
        unloading();
      },
    });
  });

  $(document).on("click", ".booking_row", function () {
    var id = $(this).attr("id");
    getBokkingDetail(id);
  });

  $(document).on("click", ".booking_row_emp", function () {
    var id = $(this).attr("id");
    getBokkingDetail(id);
    //window.location=base_url+"employee/booking_detail/"+id;
  });

  //~ $(document).on('click','.service_row', function(){
  //~ var id = $(this).attr('id');
  //~ window.location=base_url+"merchant/add_service/"+id;
  //~ });

  setTimeout(function () {
    $(".alert-success").addClass("display-n");
  }, 5000);
  setTimeout(function () {
    $(".alert-danger").addClass("display-n");
  }, 5000);
  setTimeout(function () {
    $(".messageAlert").removeClass("display-n");
  }, 500);

  $("#showkeyword").on("click", function () {
    $("#company_keyword").removeClass("display-n");
  });
  //key_word
  $(document).on("click", ".category_li", function () {
    var value = $(this).val();
    var cat_nam = $(this).attr("data-id");

    $("#company_keyword").addClass("display-n");
    $("#showkeyword").val(cat_nam);
    if (value != "") {
      $("#service_sch_close").show();
    } else {
      $("#service_sch_close").hide();
    }

    // $('#sch_catvalue').val(value);
  });

  $(document).on("keyup", "#showkeyword", function () {
    var value = $(this).val();
    //~ alert(value);
    if (value != "") {
      $("#service_sch_close").show();
    } else {
      $("#service_sch_close").hide();
    }
    var status = localStorage.getItem("STATUS");
    // if (status == "LOGGED_OUT") {
    //   console.log("STATUS=" + status);
    //   console.log(base_url);
    //   window.location.href = base_url;
    //   $(window.location)[0].replace(base_url);
    //   $(location).attr("href", base_url);
    // }
    console.log("STATUS=" + status);
    $.post(
      base_url + "user/category_search",
      { keyword: value },
      function (data) {
        $(".category_ul").html(data);
      }
    );
  });
  $(document).on("click", "#service_sch_close", function () {
    $("#showkeyword").val("");
    $("#service_sch_close").hide();
    $("#showkeyword").trigger("keyup");
  });

  $(window).click(function (e) {
    if (e.target.id == "") $("#company_keyword").addClass("display-n");
    //alert(e.target.id); // gives the element's ID
  });

  $(document).on("keypress keyup blur", ".onlyNumber", function (evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
  });

  $(document).on("keypress keyup blur", ".onlyNumberDecimal", function (evt) {
    var iKeyCode = evt.which ? evt.which : evt.keyCode;
    console.log(iKeyCode);
    if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
      return false;
    return true;
  });

  $(".onlyNumber_old").on("keypress keyup blur", function (evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
  });

  $(".onlyNumberDecimal_old").on("keypress keyup blur", function (evt) {
    var iKeyCode = evt.which ? evt.which : evt.keyCode;
    if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
      return false;
    return true;
  });

  // employee shift section

  $(document).on("click", ".shift_popup", function () {
    var tab_id = $(this).attr("id");
    var value = $(this).attr("data-id");
    var dtext = $(this).attr("data-text");
    var mer = $("#merch_id").val();
    var data = { day_nm: value, merchant_id: mer };
    $.ajax({
      url: base_url + "merchant/getavailability",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          $("#chg_dayname").html(dtext);
          $("#employee-select-time").modal("show");
          $("#start_one").html(obj.start_list);
          $("#end_one").html(obj.end_list);
          $("#tab_ids").val(tab_id);
          $("#current_day").val(value);
          $("#remove_shift").trigger("click");
          $("#addmore_shift").trigger("click");
          $("#cat_btn_start").text("");
          $("#cat_btn_end").text("");
          $("#show_start").removeClass("show");
          $("#show_end").removeClass("show");
        } else {
          $("#close_cancel").trigger("click");
          $("#error_message").css("display", "");
          $("#alert_message").html(obj.message);
        }
        //unloading();
      },
    });
  });

  $(document).on("click", "#addmore_shift", function () {
    var mer = $("#merch_id").val();
    var value = $("#current_day").val();
    var data = { day_nm: value, merchant_id: mer, count: com_count };
    $.ajax({
      url: base_url + "merchant/addmoreshift",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          $("#app_newshift").append(obj.html);

          if ($("input[name='shift_starttime[]']").length > 1)
            $("#addmore_shift").hide();

          com_count++;
        } else {
        }
      },
    });
  });

  $(document).on("click", "#addmore_shift_old", function () {
    var mer = $("#merch_id").val();
    var value = $("#current_day").val();
    var data = { day_nm: value, merchant_id: mer };
    $.ajax({
      url: base_url + "merchant/addmoreshift",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          $("#addmore_shift").hide();
          $("#app_newshift").html(obj.html);
        } else {
        }
      },
    });
  });
  $(document).on("click", "#remove_shift_old", function () {
    $("#addmore_shift_div").remove();
    $("#addmore_shift").show();
    //$('.shifttwo').prop('checked', false);
  });
  $(document).on("click", ".remove_shift", function () {
    var id = $(this).attr("data-id");
    $("#addmore_shift_div" + id).remove();
    $("#addmore_shift").show();
  });

  $(document).on("click", ".edit_shift_old", function () {
    var tab_id = $(this).attr("id");
    var value = $(this).attr("data-id");
    var mer = $("#merch_id").val();

    var data = { day_nm: value, merchant_id: mer, table_id: tab_id };
    $.ajax({
      url: base_url + "merchant/getupdatedavailability",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          $("#cat_btn_start").text("");
          $("#cat_btn_end").text("");
          $("#btn_start_two").text("");
          $("#btn_end_two").text("");
          $("#remove_shift").trigger("click");
          $("#chg_dayname").html(value);
          $("#employee-select-time").modal("show");
          $("#start_one").html(obj.start_list);
          $("#end_one").html(obj.end_list);
          $("#tab_ids").val(tab_id);
          $("#current_day").val(value);
          $("#app_newshift").html(obj.html);
          $("#cat_btn_start").text(
            $("input[name='startone_time']:checked").attr("data-text")
          );
          $("#cat_btn_end").text(
            $("input[name='endone_time']:checked").attr("data-text")
          );
          $("#btn_start_two").text(
            $("input[name='starttwo_time']:checked").attr("data-text")
          );
          $("#btn_end_two").text(
            $("input[name='endtwo_time']:checked").attr("data-text")
          );
          $("#show_start").addClass("show");
          $(".show_start").addClass("label_add_top");
          $("#show_end").addClass("show");
          $(".show_end").addClass("label_add_top");
        } else {
          $("#close_cancel").trigger("click");
          $("#error_message").css("display", "");
          $("#alert_message").html(obj.message);
        }
        //unloading();
      },
    });
  });

  $(document).on("click", ".edit_shift", function () {
    var tab_id = $(this).attr("id");
    var value = $(this).attr("data-id");
    var dtext = $(this).attr("data-text");
    var mer = $("#merch_id").val();

    var data = { day_nm: value, merchant_id: mer, table_id: tab_id };
    $.ajax({
      url: base_url + "merchant/getupdatedavailability",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          /*$("#cat_btn_start").text("");
					$("#cat_btn_end").text("");
					$("#btn_start_two").text("");
					$("#btn_end_two").text("");*/
          //$("#remove_shift").trigger('click');
          $("#chg_dayname").html(dtext);
          $("#employee-select-time").modal("show");
          $("#tab_ids").val(tab_id);
          $("#current_day").val(value);
          $("#app_newshift").html(obj.html);
          if ($("input[name='shift_starttime[]']").length > 1)
            $("#addmore_shift").hide();
          else $("#addmore_shift").show();
          //$("#cat_btn_start").text($("input[name='startone_time']:checked").attr('data-text'));
          //$("#cat_btn_end").text($("input[name='endone_time']:checked").attr('data-text'));
          //$("#btn_start_two").text($("input[name='starttwo_time']:checked").attr('data-text'));
          //$("#btn_end_two").text($("input[name='endtwo_time']:checked").attr('data-text'));
          /*$("#show_start").addClass('show');
					$(".show_start").addClass('label_add_top');
					$("#show_end").addClass('show');
					$(".show_end").addClass('label_add_top');*/
          //$('#start_one').html(obj.start_list);
          //$('#end_one').html(obj.end_list);
          //$('#start_shifttwo').html(obj.start_list1);
          //$('#end_shifttwo').html(obj.end_list1);
        } else {
          $("#close_cancel").trigger("click");
          $("#error_message").css("display", "");
          $("#alert_message").html(obj.message);
        }
        //unloading();
      },
    });
  });

  $(document).on("click", "#save_shift", function () {
    var firstVal = [];
    var firstErr = [];
    var secondVal = [];
    var secondErr = [];
    $.each($("input[name='shift_starttime[]']"), function () {
      firstVal.push($(this).val());
      firstErr.push($(this).attr("data-id"));
    });

    if (firstVal.length > 0) {
      for (let step = 0; step < firstVal.length; step++) {
        if (step == 0) {
          var startTime1 = firstVal[step];
          var start_err1 = firstErr[step];
        } else {
          var startTime2 = firstVal[step];
          var start_err2 = firstErr[step];
        }
      }
    }

    $.each($("input[name='shift_endtime[]']"), function () {
      secondVal.push($(this).val());
      secondErr.push($(this).attr("data-id"));
    });
    if (secondVal.length > 0) {
      for (let step = 0; step < firstVal.length; step++) {
        if (step == 0) {
          var endTime1 = secondVal[step];
          var end_err1 = secondErr[step];
        } else {
          var endTime2 = secondVal[step];
          var end_err2 = secondErr[step];
        }
      }
    }

    var token = true;
    if (startTime1 != undefined || endTime1 != undefined) {
      if (startTime1 == "") {
        $("#shift_stime_err_" + start_err1).html(
          "Pleas select shift one start time"
        );
        token = false;
        return false;
      } else $("#shift_stime_err_" + start_err1).html("");

      if (endTime1 == "") {
        $("#shift_etime_err_" + end_err1).html(
          "Pleas select shift one end time"
        );
        token = false;
        return false;
      } else $("#shift_etime_err_" + end_err1).html("");

      if (startTime1 >= endTime1) {
        $("#shift_etime_err_" + end_err1).html(
          "Die Endzeit kann nicht vor der Startzeit liegen"
        );
        token = false;
        return false;
      } else $("#shift_etime_err_" + end_err1).html("");
    }
    if (startTime2 != undefined || endTime2 != undefined) {
      if (startTime2 <= endTime1) {
        $("#shift_stime_err_" + start_err2).html(
          "Bitte gültige Startzeit wählen"
        );
        token = false;
        return false;
      } else $("#shift_stime_err_" + start_err2).html("");

      if (startTime2 == undefined) {
        $("#shift_stime_err_" + start_err2).html(
          "Die Endzeit kann nicht vor der Startzeit liegen"
        );
        token = false;
        return false;
      } else $("#shift_stime_err_" + start_err2).html("");

      if (endTime2 == undefined) {
        $("#shift_etime_err_" + end_err2).html(
          "Please select shift two end time"
        );
        token = false;
        return false;
      } else $("#shift_etime_err_" + end_err2).html("");

      if (startTime2 >= endTime2) {
        $("#shift_etime_err_" + start_err2).html(
          "Die Endzeit kann nicht vor der Startzeit liegen"
        );
        token = false;
        return false;
      } else $("#shift_etime_err_" + start_err2).html("");
    }

    if (token == true) {
      $.ajax({
        url: base_url + "merchant/updateavailability",
        type: "POST",
        data: $("#frm_chgshift").serialize(),
        success: function (data) {
          var obj = jQuery.parseJSON(data);
          if (obj.success == 1) {
            location.reload();
            $("#new_shift_" + obj.id).html(obj.html);
            $(".crose-btn").trigger("click");
            $("#addmore_shift_div").hide();
            $("#addmore_shift").show();
            $("#successMsgChg").show();
            $("#alert_message_suc").html(obj.msg);
            //alert(obj.msg);
            setTimeout(function () {
              $("#successMsgChg").hide();
              $("#alert_message_suc").html("");
            }, 4000);
            //$('.shiftone').prop('checked', false);
            //$("#addmore_shift_div").remove();
          } else {
            //$("#close_cancel").trigger('click');
            $("#alert_messages").css("display", "");
            $("#alert_messages").html(obj.msg);
          }
          //unloading();
        },
      });
    }
  });

  $(document).on("click", "#save_shift_old", function () {
    var startTime1 = $('[name="startone_time"]:checked').val();
    var endTime1 = $('[name="endone_time"]:checked').val();

    var startTime2 = $('[name="starttwo_time"]:checked').val();
    var endTime2 = $('[name="endtwo_time"]:checked').val();

    var token = true;

    if (startTime1 == undefined) {
      $("#startone_time_err").html("Pleas select shift one start time");
      token = false;
      return false;
    } else $("#startone_time_err").html("");

    if (endTime1 == undefined) {
      $("#endone_time_err").html("Pleas select shift one end time");
      token = false;
      return false;
    } else $("#endone_time_err").html("");

    if (startTime1 >= endTime1) {
      $("#endone_time_err").html("Die Endzeit kann nicht vor der Startzeit liegen");
      token = false;
      return false;
    } else $("#endone_time_err").html("");

    if (startTime2 != undefined || endTime2 != undefined) {
      if (startTime2 <= endTime1) {
        $("#starttwo_time_err").html("Bitte gültige Startzeit wählen");
        token = false;
        return false;
      } else $("#starttwo_time_err").html("");

      if (startTime2 == undefined) {
        $("#starttwo_time_err").html(
          "Die Endzeit kann nicht vor der Startzeit liegen"
        );
        token = false;
        return false;
      } else $("#starttwo_time_err").html("");

      if (endTime2 == undefined) {
        $("#endtwo_time_err").html("Please select shift two end time");
        token = false;
        return false;
      } else $("#endtwo_time_err").html("");

      if (startTime2 >= endTime2) {
        $("#endtwo_time_err").html(
          "Die Endzeit kann nicht vor der Startzeit liegen"
        );
        token = false;
        return false;
      } else $("#endtwo_time_err").html("");
    }
    if (token == true) {
      $.ajax({
        url: base_url + "merchant/updateavailability",
        type: "POST",
        data: $("#frm_chgshift").serialize(),
        success: function (data) {
          var obj = jQuery.parseJSON(data);
          if (obj.success == 1) {
            $("#new_shift_" + obj.id).html(obj.html);
            $(".crose-btn").trigger("click");
            $("#addmore_shift_div").hide();
            $("#addmore_shift").show();
            $(".shiftone").prop("checked", false);
            $("#addmore_shift_div").remove();
          } else {
            $("#close_cancel").trigger("click");
            $("#error_message").css("display", "");
            $("#alert_message").html(obj.msg);
          }
          //unloading();
        },
      });
    }
  });

  $(document).on("click", ".empshort_chg", function () {
    //alert();
    var value = $(this).attr("value");
    if (value != "") var url = base_url + "merchant/employee_shift/" + value;
    else var url = base_url + "merchant/employee_shift";

    window.location.href = url;
  });

  $(document).on("click", ".remove_timeset", function () {
    var id = $(this).attr("id");
    $("#remove_" + id).remove();
    $("#addbtn_" + id).show();
  });

  $(document).on("click", ".add_new_time", function () {
    var id = $(this).attr("id");
    var m_id = $("#merchant_id").val();
    var data = { day_nm: id, merchant_id: m_id };
    $.ajax({
      url: base_url + "merchant/employeenewtimediv",
      type: "POST",
      data: data,
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == 1) {
          $("#new_add" + id).html(obj.html);
          $("#addbtn_" + id).hide();
        }
        //$(".crose-btn").trigger('click');

        //unloading();
      },
    });
  });

  $(document).on("click", "#gotocustomerdetail", function () {
    //alert();
    var value = $("#customer_url").val();
    if (value != "") window.location.href = value;
  });

  $(document).on("change", ".changeNewsletter", function () {
    var id = $(this).attr("data-id");
    if ($(this).is(":checked")) var status = 1;
    else var status = 0;

    var data = { tid: id, status: status };

    loading();
    $.ajax({
      url: base_url + "user/change_news_letter",
      type: "POST",
      data: data,
      success: function (response) {
        /*if(response){}
					else{ }*/
        unloading();
      },
    });
  });

  $(document).on("change", ".changeNewsletterNotification", function () {
    var id = $(this).attr("data-id");
    if ($(this).is(":checked")) var status = 1;
    else var status = 0;

    var data = { tid: id, status: status };

    loading();
    $.ajax({
      url: base_url + "user/notification_status",
      type: "POST",
      data: data,
      success: function (response) {
        /*if(response){}
					else{ }*/
        unloading();
      },
    });
  });

  $(document).on("click", "#save_salon_time", function () {
    if ($('[name="day"]:checked').length == 0) {
      $("#day_error").html(
        "<i class='fas fa-exclamation-circle mrm-5'></i>Select day to add time"
      );
    } else {
      $("#day_error").html("");
      var token = true;

      var startTime1 = $('[name="start_time"]:checked').val();
      var endTime1 = $('[name="end_time"]:checked').val();

      if (startTime1 == undefined) {
        $("#Serr_start").html(
          "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Startzeit auswählen"
        );
        token = false;
        return false;
      } else $("#Serr_start").html("");

      if (endTime1 == undefined) {
        $("#Eerr_end").html(
          "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Endzeit auswählen"
        );
        token = false;
        return false;
      } else $("#Eerr_end").html("");

      if (startTime1 >= endTime1) {
        $("#Eerr_end").html(
          "<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"
        );
        token = false;
        return false;
      } else $("#Eerr_end").html("");

      $("#service-Changetime-table").modal("show");
    }
  });

  $(document).on("click", "#changetime_done", function () {
    var startTime1 = $('[name="start_time"]:checked').val();
    var endTime1 = $('[name="end_time"]:checked').val();
    loading();
    var day_nm = $('[name="day"]:checked').val();
    var data = { day_nm: day_nm, s_time: startTime1, e_time: endTime1 };

    $.ajax({
      url: base_url + "profile/change_time",
      type: "POST",
      data: data,
      success: function (response) {
        var obj = jQuery.parseJSON(response);
        if (obj.success == 1) {
          $("#time_" + day_nm).html(obj.starttime + " - " + obj.endtime);
          $('input[type="radio"]').prop("checked", false);
          $("#cat_btn_start").text("");
          $("#cat_btn_end").text("");
          $("#start_class").removeClass("label_add_top");
          $("#end_class").removeClass("label_add_top");
          $("#time_" + day_nm).removeClass("cencel");
          $("#time_" + day_nm).addClass("color999");
          $("#" + day_nm).show();
          $("#conftime_close").trigger("click");
        }
      },
    });
    unloading();
  });

  $(document).on("click", ".selectday", function () {
    var day = $(this).val();
    var data = { day_nm: day };
    loading();
    $.ajax({
      url: base_url + "profile/get_change_time",
      type: "POST",
      data: data,
      success: function (response) {
        var obj = jQuery.parseJSON(response);
        if (obj.success == 1) {
          if (obj.setstart != "") {
            $("#id_timestart" + obj.starttime).prop("checked", true);
            $("#id_timeend" + obj.endtime).prop("checked", true);
            //$('#id_timestart'+obj.starttime).attr('checked', true);
            //$('#id_timeend'+obj.endtime).attr('checked', true);
            $("#cat_btn_start").text(obj.setstart);
            $("#cat_btn_end").text(obj.setend);
            $("#start_class").addClass("label_add_top");
            $("#end_class").addClass("label_add_top");
            $("#day_error").html("");
          }
        }
      },
    });
    unloading();
  });

  $(document).on("click", ".deleteDayTime", function () {
    var day = $(this).attr("data-day");
    //alert(day);
    $("#salon_close_day").val(day);
    $("#salon-working-table").modal("show");
  });

  $(document).on("click", "#close_workinghour", function () {
    //var day=$(this).attr("id");
    var day = $("#salon_close_day").val();
    var data = { day_nm: day };
    loading();
    $.ajax({
      url: base_url + "profile/salon_time_status",
      type: "POST",
      data: data,
      success: function (response) {
        var obj = jQuery.parseJSON(response);
        if (obj.success == 1) {
          $("#cross" + day).remove();
          $("#dayinput" + day).prop("checked", false);
          $(".btnetxt" + day).text("");
          $(".btnstxt" + day).text("");
          $(".btnelbl" + day).removeClass("show");
          $(".btnslbl" + day).removeClass("show");
          //$("#"+day).hide();
        }
        $("#close_cancel").trigger("click");
      },
    });
    unloading();
  });

  $(document).on("click", "#auto_send_invoice", function() {
    if ($('[name="auto_send_invoice"]:checked').length == 0) var autosend = 0;
    else var autosend = 1;

    var data = { key: 'auto_send_invoice', value: autosend };
    loading();
    $.ajax({
      url: base_url + "profile/salon_update_switches",
      type: "POST",
      data: data,
      success: function (response) {
        //var obj = jQuery.parseJSON( response );
        unloading();
      },
    });
  });
  
  $(document).on("click", "#vcheckbox15", function() {
    if ($('[name="cancel_booking_allow"]:checked').length == 0) var cancelbookingallow = 0;
    else var cancelbookingallow = 1;

    var data = { key: 'cancel_booking_allow', value: cancelbookingallow };
    loading();
    $.ajax({
      url: base_url + "profile/salon_update_switches",
      type: "POST",
      data: data,
      success: function (response) {
        //var obj = jQuery.parseJSON( response );
        unloading();
      },
    });
  });

  $(document).on("click", "#notifi-sound", function() {
    if ($('[name="notification_sound_setting"]:checked').length == 0) var notifsound = 0;
    else var notifsound = 1;

    window.glb_sound_setting = notifsound;
    var data = { key: 'notification_sound_setting', value: notifsound };
    loading();
    $.ajax({
      url: base_url + "profile/salon_update_switches",
      type: "POST",
      data: data,
      success: function (response) {
        //var obj = jQuery.parseJSON( response );
        unloading();
      },
    });
  });

  $(document).on("click", "#email_noti", function() {
    if ($('[name="salon_email_setting"]:checked').length == 0) var emailnoti = 0;
    else var emailnoti = 1;

    var data = { key: 'salon_email_setting', value: emailnoti };
    loading();
    $.ajax({
      url: base_url + "profile/salon_update_switches",
      type: "POST",
      data: data,
      success: function (response) {
        //var obj = jQuery.parseJSON( response );
        unloading();
      },
    });
  });

  $(document).on("click", "#vcheckbox8st", function () {
    if ($('#vcheckbox8st:checked').length == 0) {
      $("#vcheckbox9").prop('checked', false); 
    }
  });

  $(document).on("click", "#vcheckbox8", function () {
    if ($('[name="chk_online"]:checked').length == 0) var online = 0;
    else var online = 1;

    var data = { online_status: online };
    loading();
    $.ajax({
      url: base_url + "profile/salon_online_status",
      type: "POST",
      data: data,
      success: function (response) {
        var obj = jQuery.parseJSON( response );
        if (obj.success != 1) {
          $("#vcheckbox8").prop("checked", false);
          $("#membership_exp_onlinesetup").modal("show");
          $("#online_booking_error_message").html(obj.message);
        }
      },
    });
    unloading();
  });

  //~ $(document).on('click', ".header-menu-cat", function() {
  //~ var test=$(this).attr('aria-expanded');
  //~ if(test =='true')

  //~ //$("#chg_hover").addClass('after');
  //~ else
  //~ //$("#chg_hover").removeClass('after');

  //~ });

  $(document).on("click", ".client_profile", function () {
    var id = $(this).attr("id");
    var url = base_url + "profile/clientview/" + id;
    //userdata('st_userid')
    console.log("SESSION=" + $_SESSION["userdata"]);

    //$.session.get(‘userdata’)->st_userid;
    window.location.href = url;
  });
});

function changeImageUpload() {
  /*$("#loading_img").css("display", "block");
				$("body").prepend("<div class=\"div_overlay\"></div>");
			    $(".div_overlay").css({
			        "position": "absolute",
			        "width": $(document).width(),
			        "height": $(document).height(),
			        "z-index": 99999,
			    }).fadeTo(0, 0.8);*/

  loading();

  var formData = new FormData(document.getElementById("profileChange"));
  $.ajax({
    url: base_url + "user/changeImage",
    data: formData,
    cache: !1,
    mimeType: "multipart/form-data",
    contentType: !1,
    processData: !1,
    type: "POST",
    success: function (data) {
      var obj = jQuery.parseJSON(data);
      if (obj.success == 1) {
        location.reload();
        $("#user-avtar").html(obj.html);
        $("#TopUserMenu .top-avtar").html(obj.html);
      } else {
        $("#img_error").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i> ungültiges Bildformat'
        );
      }

      $("#loading_img").css("display", "none");
      $(".div_overlay").remove();
      unloading();
    },
  });
}
function uploadBannerImage() {
  /*$("#loading_img").css("display", "block");
				$("body").prepend("<div class=\"div_overlay\"></div>");
			    $(".div_overlay").css({
			        "position": "absolute",
			        "width": $(document).width(),
			        "height": $(document).height(),
			        "z-index": 99999,
			    }).fadeTo(0, 0.8);*/

  loading();

  var formData = new FormData(document.getElementById("uploadBannerImage"));
  $.ajax({
    url: base_url + "profile/upload_banner_img",
    data: formData,
    cache: !1,
    mimeType: "multipart/form-data",
    contentType: !1,
    processData: !1,
    type: "POST",
    success: function (data) {
      var obj = jQuery.parseJSON(data);
      if (obj.success == 1) {
        location.href = base_url + "profile/edit_marchant_profile";
        //location.href=base_url+"profile/edit_marchant_profile?tab=gallery";
        //location.reload();
        //$("#user-avtar").html(obj.html);
        //$("#TopUserMenu .top-avtar").html(obj.html);
      } else {
        $("#img_error").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>' + obj.message
        );
      }

      $("#loading_img").css("display", "none");
      $(".div_overlay").remove();
      unloading();
    },
  });
}
// upload from setup gellry image on first time login
function gellery_image_setup() {
  /*$("#loading_img").css("display", "block");
				$("body").prepend("<div class=\"div_overlay\"></div>");
			    $(".div_overlay").css({
			        "position": "absolute",
			        "width": $(document).width(),
			        "height": $(document).height(),
			        "z-index": 99999,
			    }).fadeTo(0, 0.8);*/

  loading();

  var formData = new FormData(document.getElementById("uploadBannerImage"));
  $.ajax({
    url: base_url + "profile/upload_banner_img",
    data: formData,
    cache: !1,
    mimeType: "multipart/form-data",
    contentType: !1,
    processData: !1,
    type: "POST",
    success: function (data) {
      var obj = jQuery.parseJSON(data);
      if (obj.success == 1) {
        getGelleryhtml();
        //location.href=base_url+"profile/gallery_setup";
        //location.href=base_url+"profile/edit_marchant_profile?tab=gallery";
        //location.reload();
        //$("#user-avtar").html(obj.html);
        //$("#TopUserMenu .top-avtar").html(obj.html);
      } else {
        $("#img_error").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>' + obj.message
        );
      }

      $("#loading_img").css("display", "none");
      $(".div_overlay").remove();
      unloading();
    },
  });
}

// notification popup cancel booking
$(document).on("click", "#chgcancel_count", function () {
  loading();
  $.ajax({
    url: base_url + "merchant/cancel_bookingpopup",
    type: "POST",
    data: {},
    success: function (data) {
      var obj = jQuery.parseJSON(data);

      if (obj.success == 1) {
        $("#service-cancel-list").modal("show");
        $("#all_booking_list").html(obj.html);
        $("#chgcancel_count").hide();
      }
      unloading();
    },
  });
});

function getBookingList(url = "") {
  var lim = $("input[name='limit']:checked").val();
  var order = $("#orderby").val();
  var shortby = $("#shortby").val();
  // var sch=$('#sch_data').val();
  var sch = $.trim($("#sch_data_cuss").val());

  //var uid=$('#listingTabl').attr('data-uid');
  if (url == "") {
    url = base_url + "merchant/getbokkinglist_ajax";
  }

  var status = localStorage.getItem("STATUS");
  if (status == "LOGGED_OUT") {
    console.log("STATUS=" + status);
    console.log(base_url);
    window.location.href = base_url;
    $(window.location)[0].replace(base_url);
    $(location).attr("href", base_url);
  }
  console.log("STATUS=" + status);
  loading();
  $.post(
    url,
    { limit: lim, orderby: order, shortby: shortby, search: sch },
    function (data) {
      var obj = jQuery.parseJSON(data);
      if (obj.success == "1") {
        //var time = $("#selctTimeFilter").val();
        // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
        //console.log(obj.html);
        $("#all_listing_mer").html(obj.html);
        $("#all_listing").html(obj.html);
        $("#pagination").html(obj.pagination);
        $(function () {
          $(".tooltipnew").tooltip({ customClass: "tooltip-custom" });
        });
        (function ($) {
          if (typeof $.fn.tooltip.Constructor === "undefined") {
            throw new Error("Bootstrap Tooltip must be included first!");
          }

          var Tooltip = $.fn.tooltip.Constructor;

          // add customClass option to Bootstrap Tooltip
          $.extend(Tooltip.Default, {
            customClass: "",
          });

          var _show = Tooltip.prototype.show;

          Tooltip.prototype.show = function () {
            // invoke parent method
            _show.apply(this, Array.prototype.slice.apply(arguments));

            if (this.config.customClass) {
              var tip = this.getTipElement();
              $(tip).addClass(this.config.customClass);
            }
          };
        })(window.jQuery);
        //~ if(temp_id !="")
        //~ $(".news_row").show();
        //~ else
        //~ $(".news_row").hide();

        //~ $('[data-toggle="popover"]').popover({
        //~ trigger: 'hover',
        //~ 'placement': 'top'
        //~ });
      }
      unloading();
    }
  );
}

$(document).on("click", "#click_to_print", function () {
  printFunc();
});

function printFunc() {
  /* var css= '<link rel="stylesheet" href="'+base_url+'assets/frontend/css/new-modal-style.css" />';
       var panel = document.getElementById("print_div_receipt");
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>DIV Contents</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(panel.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.head.innerHTML = css;
            printWindow.document.close();
            setTimeout(function () {
                printWindow.print();
            }, 500);
             $("#print_title").hidden();
            return false;*/

  $("#print_title").show();
  var css =
    '<link rel="stylesheet" href="' +
    base_url +
    'assets/frontend/css/new-modal-style.css" />';
  var divToPrint = document.getElementById("print_div_receipt");
  newWin = window.open("");
  newWin.document.write(divToPrint.outerHTML);
  $("#print_title").hide();
  newWin.document.head.innerHTML = css;
  newWin.print();
  //window.print();
  newWin.close();
  return false;
}

// click on header notification

$(document).on("click", "#click_notification", function () {
  loading();
  $.ajax({
    url: base_url + "merchant/getall_recentbooking",
    type: "POST",
    data: {},
    success: function (data) {
      var obj = jQuery.parseJSON(data);
      if (obj.success == 1) {
        document.getElementById("mySidenav").style.width = "340px";
        $("#chg_div_activity").html(obj.activity);
        $("#div_chg_upcoming").html(obj.upcoming);
        $("#chgcancel_count").hide();
        $("#booking_count").hide();
        $("#all_notify_count").hide();
        $("#chg_nofifyImg").attr(
          "src",
          base_url + "assets/frontend/images/notification.svg"
        );
      }
      unloading();
    },
  });
});

$("#marchant_working_time").validate({
  submitHandler: function (form) {
    var token = true;

    if ($("input[name='days[]']:checked").length == 0) {
      $("#chk_monday").text("Bitte Öff nungszeiten festlegen");
      token = false;
    } else {
      $("#chk_monday").text("");
      var values = new Array();
      $.each($("input[name='days[]']:checked"), function () {
        // alert($('[name="'+day+'_start"]:checked').length);
        //values.push($(this).val());
        var day = $(this).val();
        if ($('[name="' + day + '_start"]:checked').length == 0) {
          $("#Serr_" + day).html(
            "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Startzeit auswählen"
          );
          token = false;
        } else {
          $("#Serr_" + day).html("");
        }
        if ($('[name="' + day + '_end"]:checked').length == 0) {
          $("#Eerr_" + day).html(
            "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Endzeit auswählen"
          );
          token = false;
        } else {
          $("#Eerr_" + day).html("");
        }

        if (
          $('[name="' + day + '_start"]:checked').length != 0 &&
          $('[name="' + day + '_end"]:checked').length != 0 &&
          $('[name="' + day + '_start"]:checked').val() >=
            $('[name="' + day + '_end"]:checked').val()
        ) {
          $("#Eerr_" + day).html(
            "<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"
          );
          token = false;
        }
      });
      //$("#busType_err").text('');
    }

    if (token == false) return false;
    else form.submit();
  },
});

$(document).on("change", ".commission_check", function () {
  if ($(this).is(":checked")) $("#commission_div").show();
  else $("#commission_div").hide();
});

$(document).on("click", ".popup_terms", function () {
  var access = $(this).attr("data-access");
  var type = $(this).attr("data-type");
  $.ajax({
    url: base_url + "user/termsin_popup",
    type: "POST",
    data: { access: access, type: type },
    success: function (data) {
      console.log(data);
      var obj = jQuery.parseJSON(data);
      if (obj.success == 1) {
        $("#terms_condition_modal").modal("show");
        $("#tems_condition_Html").html(obj.html);
      }
      //unloading();
    },
  });
});

$(document).on("click", ".shiftstartChg", function () {
  var id = $(this).attr("data-id");
  var val = $(this).val();
  $("#shiftStart_" + id).val(val);
});
$(document).on("click", ".shiftendChg", function () {
  var id = $(this).attr("data-id");
  var val = $(this).val();
  $("#shiftEnd_" + id).val(val);
});

$(document).on("click", ".bookCancelRes_popup", function () {
  var mobile = $(this).attr("data-mobile");
  var hr = $(this).attr("data-hr");
  var salonname = $(this).attr("data-salon");
  $("#s_mobile").html(mobile);
  if (hr == 1) {
    $("#s_hr").html(hr + ' Stunde');
  } else {
    $("#s_hr").html(hr + ' Stunden');
  }
  $("#s_salon").html(salonname);
  $("#CancelReshedule").modal("hide");
});

$(document).on("click", "#sendResetpasswordLink", function () {
  var url = $(this).attr("data-href");

  Swal.fire({
    title: "Bist du sicher,",
    text: you_want_to_send_reset_password_link,
    type: "warning",
    showCancelButton: true,
    reverseButtons: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Nein",
    confirmButtonText: "Ja",
    customClass: {
      title: 'mb-0'
    }
  }).then((result) => {
    if (result.value) {
      loading();

      $.ajax({
        url: url,
        type: "GET",
        success: function (data) {
          //console.log(data);
          var obj = jQuery.parseJSON(data);
          if (obj.success == 1) {
            Swal.fire("Success", obj.msg, "success");
            $("#swal2-title").css("display", "none");
            //$('#terms_condition_modal').modal('show');
            //$('#tems_condition_Html').html(obj.html);
          } else {
            Swal.fire({
              title: obj.msg,
              width: 600,
              padding: "3em",
            });
            return false;
          }
          unloading();
        },
      });
      //location.href=url;
    } else {
      return false;
    }
  });

  //alert(url);
});

$(document).on("click", "#search-crose_btn", function () {
  $("#search_servce").val("");
  $("#search_servce").trigger("keyup");
});
