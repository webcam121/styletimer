// ADD stylist by salon

function loading() {
  // alert('load');
  $("#loading_div .process_loading").removeClass("hide");
  $("#loading_div").addClass("overlay");
}
function unloading() {
  // alert('unload');
  $("#loading_div .process_loading").addClass("hide");
  $("#loading_div").removeClass("overlay");
}

function getBokkingDetail(id = "", frm = "") {
  //alert('unload');
  if (id != "") {
    loading();
    var status = localStorage.getItem("STATUS");
    if (status == "LOGGED_OUT") {
      console.log("STATUS=" + status);
      console.log(base_url);
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr("href", base_url);
    }
    console.log("STATUS=" + status);

    $.post(base_url + "booking/detail", { id: id }, function (data) {
      // console.log(data);
      var obj = jQuery.parseJSON(data);
      if (obj.success == "1") {
        $(".modal").modal("hide");
        $("#bookingDetailHtml").html(obj.html);
        $("#booking-details-modal").modal("show");
        if (frm != "") $("#action_from").val("calendar");
        if (obj.reviehtml != "") {
          $("#addrratingreviewForm").html(obj.reviehtml);
        }
      } else {
        alert(obj.msg);
      }
      unloading();
    });
  } else {
    return false;
  }
  //$('#loading_div img.process_loading').addClass('hide');
  //$('#loading_div').removeClass('overlay');
}

$(document).on("click", ".editCust", function () {
  var id = $(this).attr("data-id");
  var bid = $(this).attr("data-bid");
  getClientProfile(id, bid);
  //$("#"+id)[0].click();
});
function getClientProfile(id = "", bid = "") {
  //alert('unload');
  if (id != "") {
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
      base_url + "profile/clientview",
      { id: id, bid: bid },
      function (data) {
        // console.log(data);
        var obj = jQuery.parseJSON(data);
        if (obj.success == "1") {
          $(".modal").modal("hide");
          $("#clientViewProfileHtml").html(obj.html);
          $("#profile-client-view-modal").modal("show");
          $("#profile-client-view-modal").modal({
            backdrop: "static",
            keyboard: false,
          });
          $(".modal").css("overflow-y", "auto");
          get_clientBookingList();
          get_userrevenew(id);
        } else {
          return false;
          //alert(obj.msg);
        }
        unloading();
      }
    );
  } else {
    return false;
  }
  //$('#loading_div img.process_loading').addClass('hide');
  //$('#loading_div').removeClass('overlay');
}

$(document).ready(function () {
  $.validator.addMethod(
    "pattern",
    function (value, element, regexp) {
      var re = new RegExp(/^[0-9,]+$/);
      return this.optional(element) || re.test(value);
    },
    "<i class='fas fa-exclamation-circle mrm-5'></i>Enter a valid number"
  );

  $(document).on("click", ".selecthowtooption", function () {
    //alert($(this).val());
    if ($(this).val() == "Referral") {
      $("#reffrelotption").attr(
        "class",
        "col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"
      );
      $("#referral_code").removeClass("display-n");
    } else {
      $("#reffrelotption").attr(
        "class",
        "col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
      );
      $("#referral_code").addClass("display-n");
    }
  });

  $("#calculatorForm").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      booking_take_perweek: {
        required: true,
        min: 0,
      },
      taking_a_booking_in_min: {
        required: true,
        min: 0,
      },
      increase_in_booking_percent: {
        required: true,
        min: 0,
      },
      average_spend_per_treatment: {
        required: true,
        min: 0,
      },
      how_many_cancelled_in_a_week: {
        required: true,
        min: 0,
      },
    },
    messages: {
      booking_take_perweek: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
        min: "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
      },
      taking_a_booking_in_min: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
        min: "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
      },
      increase_in_booking_percent: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
        min: "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
      },
      average_spend_per_treatment: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
        min: "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
      },
      how_many_cancelled_in_a_week: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
        min: "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte geben Sie eine Zahl ein",
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function () {
      loading();

      $.post(
        base_url + "home/calculator",
        $("#calculatorForm").serialize(),
        function (data) {
          // console.log(data);
          var obj = jQuery.parseJSON(data);
          if (obj.success == 1) {
            $(".result").removeClass("display-n");
            $("#saved_hours").text(obj.savehours);
            $("#revenue_increase").text(obj.revenueIncrease);
          }

          unloading();
        }
      );
      return false;
    },
  });

  $("#userLogin").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      identity: {
        required: true,
        email: true,
      },
      password: {
        required: true,
      },
    },
    messages: {
      identity: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_email_id,
      },
      password: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_password,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function () {
      //$("#userLogin").submit();
      //return true;
      var chk_attemp = $("#check_login_attempt").val();
      if (chk_attemp == "on") {
        var $captcha = $("#recaptcha"),
          response = grecaptcha.getResponse();
        console.log(response);
        if (response.length === 0) {
          return false;
        }
      }

      loading();
      var urls = $("#select_url").val();
      $.post(
        base_url + "auth/login",
        $("#userLogin").serialize(),
        function (data) {
          // console.log(data);
          const oldUrl = window.location.href;
          var obj = jQuery.parseJSON(data);
          $(".alert_sucmessage").css("display", "none");
          console.log("obj.success------------", obj.success);
          if (obj.success == "3") {
            $("#alert_message_my").html(
              `<div class="alert alert-danger" style=" height:auto !important;" role="alert">` +
                obj.message +
                `</div>`
            );
            $("#check_login_attempt").val("on");
            console.log("obj.success------------", obj.success);
          }
          if (obj.success == "1") {
            console.log("LOGIN------");
            localStorage.setItem("STATUS", "LOGGED_IN");

            $("#check_login_attempt").val("");
            $("#recaptcha").hide();
            //alert(obj.url);
            if (oldUrl.includes("salons")) {
              location.reload();
            }
            else if (obj.url != "false") {
              window.location.href = obj.url;
            }
          } else {
            if (obj.success == "2") {
              $("#resend_activelink").show();
            } else if (obj.success == "3") {
              $("#check_login_attempt").val("on");
              $("#recaptcha").show();
            }
            $("#alert_message").html(
              "<strong>Message ! </strong>" + obj.message
            );
            $(".alert_message").css("display", "block");

            //location.reload();
          }
          unloading();
        }
      );
      return false;
    },
  });

  $("#marchant_update_profile").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      first_name: { required: true },
      last_name: { required: true },
      email: {
        required: true,
        email: true,
        remote: { url: base_url + "auth/checkemail_for_profile", type: "post" },
      },
      business_name: {
        required: true,
        remote: { url: base_url + "auth/checksalon_name", type: "post" },
      },
      mobile: { required: true },
      address: { required: true },
      zip: { required: true, maxlength: 5 },
      city: { required: true },
      /*about:{ required: true },*/
    },
    messages: {
      first_name: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>" +
          please_enter_first_name,
      },
      last_name: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>" +
          please_enter_last_name,
      },
      email: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_email_id,
        email:
          '<i class="fas fa-exclamation-circle mrm-5"></i>Please enter a valid email.',
        remote: jQuery.validator.format(
          '<i class="fas fa-exclamation-circle mrm-5"></i> Email already exists'
        ),
      },
      business_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_business_name,
        remote: jQuery.validator.format(
          '<i class="fas fa-exclamation-circle mrm-5"></i> Business name already exists'
        ),
      },
      mobile: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>" +
          phone_number_is_required,
      },
      address: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>" +
          address_is_required,
      },
      zip: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>" +
          postal_code_is_required,
        maxlength:
          '<i class="fas fa-exclamation-circle mrm-5"></i> Enter maximum 5 digits postcode',
      },
      city: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>" +
          city_name_is_required,
      },

      /*about:{ required:"<i class='fas fa-exclamation-circle mrm-5'></i>About salon is required" },*/
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },

    submitHandler: async function (form) {
      var token = true;
      if ($('[name="business_type"]:checked').length == 0) {
        $("#busType_err").text("Please select business type");
        token = false;
      } else {
        $("#busType_err").text("");
      }
      if ($('[name="country"]:checked').length == 0) {
        $("#country_err").text("Please select country");
        token = false;
      } else {
        $("#country_err").text("");
      }

      if ($("#latitude").val() == "") {
        $("#addr_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein'
        );
        token = false;
      } else {
        $("#addr_err").html("");
      }
      if ($("#about_salon").val() == "") {
        $("#about_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
            About_salon_is_required
        );
        token = false;
      } else {
        $("#about_err").html("");
      }

      var reminder_hr = $('[name="reminder_hr"]:checked').val();
      var ad_reminder_hr = $('[name="ad_reminder_hr"]:checked').val();

      if (ad_reminder_hr != 0 && reminder_hr >= ad_reminder_hr) {
        $("#ad_reminder_hr_err").css("display", "block");
        $("#ad_reminder_hr_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>Der Wert für die zusätzliche Erinnerung muss gößer als der Wert für die erste Erinnerung sein'
        );
        token = false;
      } else {
        $("#ad_reminder_hr_err").html("");
      }

      //alert(reminder_hr+"--"+ad_reminder_hr); return false;

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
              "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Startzeit auswählen	 	"
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

      if (token == false) {
        return false;
      } else {
        if ($("#check_confirm_yes").val() == "") {
          $("#confirm-editprofile-popup").modal("show");
          return false;
        }

        var key = '5eaae41f648ada';
        var gurl = "https://us1.locationiq.com/v1/search.php?key="+key+"&q="+$("#address").val()+", "+$("#zipcode").val()+" "+$("#city").val()+"&addressdetails=1&format=json&countrycodes=de";
        data = await $.get(gurl);
        if(data.length > 0){
          var lat = data[0].lat;
          var lon = data[0].lon;
          $("#latitude").val(lat);
          $("#longitude").val(lon);
        }
        
        form.submit();
      }
    },
  });

  $(document).on("click", "#ok_confirm", function () {
    $("#check_confirm_yes").val("ok");
    $("#marchant_update_profile").submit();
  });
  $(document).on("click", ".common_time", function () {
    var day = $(this).attr("data-name");
    if ($("#dayinput" + day).is(":checked")) $("#check_confirm_yes").val("");
  });
  $(document).on("click", ".day_checked", function () {
    $("#check_confirm_yes").val("");
  });

  $(document).on("click", "#updatetoshift", function () {
    $("#shift_check").val("ok");
    $("#check_confirm_yes").val("ok");
    $("#marchant_update_profile").submit();
  });

  $("#paymentForm").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      card_number: { required: true, number: true },
      nameofcarthoder: { required: true },
      expir_month_year: { required: true },
      cvv: { required: true },
    },
    messages: {
      card_number: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Card_number_is_required,
        number:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Enter a valid card number",
      },
      nameofcarthoder: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Card_holder_name_is_required,
      },
      expir_month_year: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Expiry_date_is_required,
      },
      cvv: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' + Cvv_is_required,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
  });

  $("#sepapaymentForm").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      nameacounthoder: { required: true },
      iban: { required: true },
    },
    messages: {
      nameacounthoder: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_your_name,
      },
      iban: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          IBAN_Number_is_required,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
  });

  $("#changesepapaymentForm").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      nameacounthoder: { required: true },
      iban: { required: true },
    },
    messages: {
      nameacounthoder: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_your_name,
      },
      iban: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          IBAN_Number_is_required,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
  });

  $("#submitpay").click(function () {
    if ($("#paymentForm").valid()) {
      $("#submitpay").prop("disabled", true);
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
        base_url + "membership/securepayment",
        $("#paymentForm").serialize(),
        function (data) {
          var obj = jQuery.parseJSON(data);
          //console.log(obj);
          if (obj.success == "1") {
            $("#paymentForm").trigger("reset");
            //$(".alert-danger.alert_message").css('display','none');
            //$(".alert-success.alert_message").css('display','block');
            //$(".alert-success.alert_message #alert_message").html(obj.message);

            window.location.href = obj.redirect_url;
          } else {
            $("#submitpay").prop("disabled", false);
            $(".alert-success.alert_message").css("display", "none");
            $(".alert-danger.alert_message").css("display", "block");
            $(".alert-danger.alert_message").removeClass("display-n");
            $(".alert-danger.alert_message #alert_message").html(obj.message);
            setTimeout(function () {
              $(".alert-danger.alert_message").css("display", "none");
            }, 4000);
          }
          unloading();
        }
      );
    } else return false;
  });

  $("#submitsepapay").click(function () {
    if ($("#sepapaymentForm").valid()) {
      $("#submitsepapay").prop("disabled", true);
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
        base_url + "membership/securepayment",
        $("#sepapaymentForm").serialize(),
        function (data) {
          var obj = jQuery.parseJSON(data);
          //console.log(obj);
          if (obj.success == "1") {
            $("#sepapaymentForm").trigger("reset");
            //$(".alert-danger.alert_message").css('display','none');
            //$(".alert-success.alert_message").css('display','block');
            //$(".alert-success.alert_message #alert_message").html(obj.message);

            window.location.href = base_url + "membership/thankyou";
          } else {
            $("#submitsepapay").prop("disabled", false);
            $(".alert-success.alert_message").css("display", "none");
            $(".alert-danger.alert_message").css("display", "block");
            $(".alert-danger.alert_message").removeClass("display-n");
            $(".alert-danger.alert_message #alert_message").html(obj.message);
            setTimeout(function () {
              $(".alert-danger.alert_message").css("display", "none");
            }, 4000);
          }
          unloading();
        }
      );
    } else return false;
  });

  $("#submitsepapaychange").click(function () {
    if ($("#changesepapaymentForm").valid()) {
      $("#submitsepapaychange").prop("disabled", true);
      loading();
      var status = localStorage.getItem("STATUS");
      if (status == "LOGGED_OUT") {
        console.log("STATUS=" + status);
        console.log(base_url);
        window.location.href = base_url;
        $(window.location)[0].replace(base_url);
        $(location).attr("href", base_url);
      }
      console.log("STATUS=" + status);
      $.post(
        base_url + "membership/change_card",
        $("#changesepapaymentForm").serialize(),
        function (data) {
          var obj = jQuery.parseJSON(data);
          //console.log(obj);
          if (obj.success == "1") {
            $("#changesepapaymentForm").trigger("reset");
            //$(".alert-danger.alert_message").css('display','none');
            //$(".alert-success.alert_message").css('display','block');
            //$(".alert-success.alert_message #alert_message").html(obj.message);

            window.location.href = base_url + "merchant/payment_list";
          } else {
            $("#submitsepapaychange").prop("disabled", false);
            $(".alert-success.alert_message").css("display", "none");
            $(".alert-danger.alert_message").css("display", "block");
            $(".alert-danger.alert_message").removeClass("display-n");
            $(".alert-danger.alert_message #alert_message").html(obj.message);
            setTimeout(function () {
              $(".alert-danger.alert_message").css("display", "none");
            }, 4000);
          }
          unloading();
        }
      );
    } else return false;
  });

  $("#changecard").click(function () {
    if ($("#changecardForm").valid()) {
      $("#changecard").prop("disabled", true);
      loading();
      var status = localStorage.getItem("STATUS");
      if (status == "LOGGED_OUT") {
        console.log("STATUS=" + status);
        console.log(base_url);
        window.location.href = base_url;
        $(window.location)[0].replace(base_url);
        $(location).attr("href", base_url);
      }
      console.log("STATUS=" + status);
      $.post(
        base_url + "membership/change_card",
        $("#changecardForm").serialize(),
        function (data) {
          var obj = jQuery.parseJSON(data);
          //console.log(obj);
          if (obj.success == "1") {
            $("#changecardForm").trigger("reset");
            //$(".alert-danger.alert_message").css('display','none');
            //$(".alert-success.alert_message").css('display','block');
            //$(".alert-success.alert_message #alert_message").html(obj.message);

            window.location.href = base_url + "merchant/payment_list";
          } else {
            $("#changecard").prop("disabled", false);
            $(".alert-success.alert_message").css("display", "none");
            $(".alert-danger.alert_message").css("display", "block");
            $(".alert-danger.alert_message").removeClass("display-n");
            $(".alert-danger.alert_message #alert_message").html(obj.message);
            setTimeout(function () {
              $(".alert-danger.alert_message").css("display", "none");
            }, 4000);
          }
          unloading();
        }
      );
    } else return false;
  });

  $("#changecardForm").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      card_number: { required: true, number: true },
      nameofcarthoder: { required: true },
      expir_month_year: { required: true },
      cvv: { required: true },
    },
    messages: {
      card_number: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Card_number_is_required,
        number:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Enter a valid card number",
      },
      nameofcarthoder: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Card_holder_name_is_required,
      },
      expir_month_year: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Expiry_date_is_required,
      },
      cvv: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' + Cvv_is_required,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
  });

  $("#add_category").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      //~ duration: { required: true,
      //~ number:true },
      price: { pattern: true, minlength: 1, maxlength: 20 },
      /*buffer_time:{ required: true,
	        number:true },*/
      subDuration: { required: true, number: true },
    },
    messages: {
      //~ duration:{ required:"<i class='fas fa-exclamation-circle mrm-5'></i>Duration is required",
      //~ number:"<i class='fas fa-exclamation-circle mrm-5'></i>Enter a valid number" },
      price: {
        minlength:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Enter a valid number",
      },
      /*buffer_time:{ required:"<i class='fas fa-exclamation-circle mrm-5'></i>Buffer time is required",
	   number:"<i class='fas fa-exclamation-circle mrm-5'></i>Enter a valid number" }*/
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },

    submitHandler: function (form) {
      var token = true;

      if ($("#discount_price").val() != "") {
        if (
          Number($("#discount_price").val()) > Number($("#standardprice").val())
        ) {
          $("#discount_price_valid").css("display", "block");
          $("#discount_price_valid").html(
            "<i class='fas fa-exclamation-circle mrm-5'></i>Rabattpreis kann nicht über Standardpreis liegen"
          );
          token = false;
        } else {
          $("#discount_price_valid").html("");
        }
      }
      if ($('[name="category"]:checked').length == 0) {
        $("#catgory_err").css("display", "block");
        $("#catgory_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
            category_is_required
        );
        token = false;
      } else {
        $("#catgory_err").css("display", "none");
        $("#catgory_err").html("");
      }

      if ($('[name="sub_category"]:checked').length == 0) {
        $("#subcatgory_err").css("display", "block");
        $("#subcatgory_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
            sub_category_is_required
        );
        token = false;
      } else {
        $("#subcatgory_err").css("display", "none");
        $("#subcatgory_err").html("");
      }

      $.each($(".validation"), function () {
        var val = $(this).val();
        var id = $(this).attr("data-text");
        if (val == "") {
          $(this).focus();
          $("#" + id).html(
            "<i class='fas fa-exclamation-circle mrm-5'></i>This field is required"
          );
          $("#" + id).css("display", "block");
          token = false;
        } else {
          $("#" + id).html("");
        }
      });
      $.each($(".checkPrice"), function () {
        var val = $(this).val();
        var id = $(this).attr("data-text");
        var dicPrice = $("." + id).val();

        if (Number(dicPrice) > val) {
          $("#dis" + id).focus();
          $("#dis" + id).html(
            "<i class='fas fa-exclamation-circle mrm-5'></i>Rabattpreis kann nicht über Standardpreis liegen"
          );
          $("#dis" + id).css("display", "block");
          token = false;
        } else {
          $("#" + id).html("");
        }
      });

      var discountCheck = 0;
		
      var inputs = $(".discount_price");

      for(var i = 0; i < inputs.length; i++){
        if($(inputs[i]).val()!='' && discountCheck==0){
          discountCheck=1;
        }
      }

      if ($("#discount_price").val() != "" || discountCheck == 1) {
        if ($("input[name='days[]']:checked").length == 0) {
          $(".select-day-time").addClass("show");
          $("#chk_monday").html(
            "<i class='fas fa-exclamation-circle mrm-5'></i>"+select_day_for_applying_that_special_discount
          );
          token = false;
        } else {
          $("#chk_monday").html("");
          var values = new Array();
          $.each($("input[name='days[]']:checked"), function () {
            // alert($('[name="'+day+'_start"]:checked').length);
            //values.push($(this).val());
            var day = $(this).val();
            if ($('[name="' + day + '_start"]:checked').length == 0) {
              $("#Serr_" + day).html(
                "<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Startzeit auswählen	 	"
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
          $("#busType_err").text("");
        }
      }

      if(discountCheck==0){
        $("input[name='days[]']:checked").removeAttr("checked");
        if($("input[name='days[]']:checked").length != 0){
          $("#discount_price_valid").css("width",'148px'); 
          $("#discount_price_valid").html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Rabattpreis angeben"); 
          token =false;
        }
      }

      if (token == false) {
        return false;
      } else {
        //$('#save_buuton').
        $("#save_buuton").prop("disabled", true);
        form.submit();
      }
    },
  });

  $(".validation").click(function () {
    var id = $(this).attr("data-text");
    $("#" + id).html("");
  });

  $("#discount_price").change(function () {
    if ($("#discount_price").val() != "") {
      if (
        Number($("#discount_price").val()) > Number($("#standardprice").val())
      ) {
        $("#discount_price_valid").html(
          "<i class='fas fa-exclamation-circle mrm-5'></i>Rabattpreis kann nicht über Standardpreis liegen"
        );
        token = false;
      } else {
        $("#discount_price_valid").html("");
      }
    }
  });

  $("input.s").click(function () {
    //if()
    var val = $(this).attr("data-val");

    if ($("#Serr_" + val).is(":visible")) {
      $("#Serr_" + val).html("");
    }
  });
  $(".checkbox").click(function () {
    //if()
    var val = $(this).val();

    if ($(this).is(":checked")) {
      $("#chk_" + val).html("");
    }
  });
  $("input.e").click(function () {
    //if()
    var val = $(this).attr("data-val");

    if ($("#Eerr_" + val).is(":visible")) {
      $("#Eerr_" + val).html("");
    }
  });

  $("#search_servce").keyup(function () {
    var value = $(this).val();
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
      base_url + "merchant/get_services_for_assign",
      { search: value },
      function (data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == "1") {
          $("#add_service_popup").modal("show");
          $("#service_html").html(obj.html);
          $(".deselect_service").each(function () {
            var id = $(this).attr("data-id");
            $("#" + id).prop("checked", true);
          });
        } else {
          alert(obj);
        }
        unloading();
      }
    );
  });

  $(document).on("click", "#get_service_for_asign", function () {
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
    $.post(base_url + "merchant/get_services_for_assign", function (data) {
      var obj = jQuery.parseJSON(data);
      if (obj.success == "1") {
        $("#add_service_popup").modal("show");
        $("#service_html").html(obj.html);
        $(".deselect_service").each(function () {
          var id = $(this).attr("data-id");
          $("#" + id).prop("checked", true);
        });

        $(".select_all").each(function () {
          var id = $(this).attr("id");

          //console.log($("."+id+":checked").length);
          if ($("." + id + ":checked").length == $("." + id).length) {
            $("#" + id).prop("checked", true);
          }
        });
      } else {
        alert(obj);
      }
      unloading();
    });
  });

  $("#SaveTempUser").click(function () {
    if ($("#aadNewCustomer").valid()) {
      $("#SaveTempUser").prop("disabled", true);
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
        base_url + "booking/save_temp_customer",
        $("#aadNewCustomer").serialize(),
        function (data) {
          var obj = jQuery.parseJSON(data);
          console.log("OBJ=" + obj);

          //alert(data);
          if (obj.success == "1") {
            sessionStorage.setItem("regestered", "");
            console.log("SESSION=" + sessionStorage.getItem("st_userid"));
            if (data.registered == "1") {
              console.log("REGISTERED");
              sessionStorage.setItem("regestered", "yes");
            }
            var html =
              '<div class="text-left p-3" style="box-shadow:0px 3px 25px rgba(215,232,233,.78);">\
			 						<p class="mb-0 color333 font-size-16 fontfamily-medium">' +
              obj.name +
              '</p>\
									<p class="mb-0 color999 font-size-14 fontfamily-regular">' +
              obj.mobile +
              '</p>\
									<p class="mb-0 color999 font-size-14 fontfamily-regular">' +
              obj.email +
              '</p>\
									</div>\
									<a href="#" id="removeCustomer" class="height56v vertical-middle colorcyan font-size-14 fontfamily-regular a_hover_cyan display-b p-3">Kunde/ Kundin entfernen</a>';
            $("#customePreview").html(html);

            $("#popup-v12").modal("hide");

            $("#tempuid").val(obj.uid);
            $("#search_keyword").addClass("display-n");
            $("#SaveTempUser").prop("disabled", false);
            $("#showkeyword").val($("#customerName").val());
            // $('#CusProfile').attr('src',dummy_img);
            $("#hasimage").val("");
            $(".slectCustomer").each(function () {
              $(this).prop("checked", false);
            });

            $("#aadNewCustomer")[0].reset();
            if($("#customePreview").length == 0){
              location.reload();
            } else if (obj.uid) {
              console.log(33333);
              getCustomerList(obj.uid);
            }
          }
          unloading();
        }
      );
    } else return false;
  });

  $(document).on('click', '#UpdateTempUser', function () {
    if ($("#editCustomer").valid()) {
      $("#UpdateTempUser").prop("disabled", true);
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
        base_url + "merchant/update_temp_customer",
        $("#editCustomer").serialize(),
        function (data) {
          var obj = jQuery.parseJSON(data);

          //alert(data);
          if (obj.success == "1") {
            $("#edit-customer-modal").modal("hide");

            $("#editCustomerHtml").html("");

            $("#UpdateTempUser").prop("disabled", false);

            window.location.reload();
          }
          unloading();
        }
      );
    } else return false;
  });

  $("#aadNewCustomer").validate({
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
          '<i class="fas fa-exclamation-circle mrm-5"></i>Bitte E-Mail Adresse eingeben'
      }
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      console.log(name, error);
      error.appendTo($("#aadNewCustomer #" + name + "_validate"));
    },
  });
  $("#submitBookNow").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      date: { required: true },
      //time:{ required: true},
      email: { required: true, email: true },
      //usernotes:{ required: true }
    },
    messages: {
      date: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>" + date_is_required,
      },
      time: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Time is required",
      },

      // usernotes:{ required:"<i class='fas fa-exclamation-circle mrm-5'></i>Cvv is required" }
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      var token = true;
      var temuid = $("#tempuid").val();

      if ($('[name="time"]:checked').length == 0) {
        $("#timeSelectErr").css("display", "block");
        $("#timeSelectErr").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
            please_select_booking_time
        );
        token = false;
      } else $("#timeSelectErr").html("");

      //~ if($('[name="customer_id"]:checked').length == 0 && temuid==''){
      //~ $("#customer_err").css('display','block');
      //~ $("#customer_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>Please select a customer');
      //~ token =false;
      //~ }else $("#customer_err").html('');

      if ($('[name="employee_select"]:checked').length == 0) {
        $("#employee_err").css("display", "block");
        $("#employee_err").html(
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
            please_select_a_employee
        );
        token = false;
      } else $("#employee_err").html("");

      if (token == true) {
        var date = form.elements.namedItem('date').value;
        var time = form.elements.namedItem('time').value;
        var emp = form.elements.namedItem('employee_select').value;
        
        $.post(
          base_url + "booking/check_emp_available",
          { emp_id: emp, date, time },
          function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.success == '1') {
              form.submit();
            } else if (obj.success == '2') {
              var empId = obj.empid;
              $("#employeeId" + empId).click();
              Swal.fire({
                title: '',
                html: "Der ausgewählte Mitarbeiter arbeitet<br/>bereits am ausgewählten Zeitpunkt -<br/>Bist du sicher, dass du mit der<br/>Buchung fortfahren möchtest?",
                type: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Buchung fortsetzen',
                cancelButtonText: 'Abbrechen',
                confirmButtonClass: 'delete-booking-confirm-button'
              }).then((result) => {
                if (result.value) {
                  form.submit();
                } else {
                  return false;
                }
              });
            } else if (obj.success == '3') {
              Swal.fire({
                title: '',
                html: "Der ausgewählte Mitarbeiter arbeitet<br/>bereits am ausgewählten Zeitpunkt -<br/>Bist du sicher, dass du mit der<br/>Buchung fortfahren möchtest?",
                type: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Buchung fortsetzen',
                cancelButtonText: 'Abbrechen',
                confirmButtonClass: 'delete-booking-confirm-button'
              }).then((result) => {
                if (result.value) {
                  form.submit();
                } else {
                  return false;
                }
              });
            } else if (obj.success == '0') {
              if (obj.url)
                window.location.href = obj.url;
              else
                window.location.reload();
            } else {
              window.location.reload();
            }
          }
        );
        return false;
        // form.submit();
      } else return false;
    },
  });

  $(document).on("click", "#salon_sch_close", function () {
    $("#getsalone").val("");
    $("#getsalone").trigger("keyup");
  });
  $("#getsalone").keyup(function () {
    var keybord = $(this).val();
    if (keybord != "") {
      $(this).css("border-color", "#e8e8e8");
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
        base_url + "home/getsalon_list",
        { keys: keybord },
        function (data) {
          var obj = jQuery.parseJSON(data);
          //console.log(data);
          if (obj.success == "1") {
            $("#salon_list").removeClass("display-n");
            $("#salon_list").html(obj.html);
            $("#findurl").attr("href", "#");
          }
        }
      );
      $("#salon_sch_close").show();
    } else {
      $("#salon_list").addClass("display-n");
      $("#salon_list").html("");
      $("#findurl").attr("href", "#");
      $("#salon_sch_close").hide();
    }
  });

  $(document).on("click", ".salon_li", function () {
    $("#getsalone").val($(this).attr("data-val"));
    var url = base_url + "user/service_provider/" + $(this).val();
    window.location.href = url;
  });

  $("#contact_for_salon").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      name: {
        required: true,
      },
      salon_name: {
        required: true,
      },
      salon_city: {
        required: true,
      },
    },
    messages: {
      name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_your_name,
      },
      salon_name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Salon_name_is_required,
      },
      salon_city: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Salon_city_is_required,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function () {
      //$("#userLogin").submit();
      //return true;
      loading();
      //~ var urls=$('#select_url').val();
      $.post(
        base_url + "home/contact_for_salon",
        $("#contact_for_salon").serialize(),
        function (data) {
          //console.log(data);
          var obj = jQuery.parseJSON(data);
          if (obj.success == "1") {
            $("#contact_for_salon").trigger("reset");
            $("#salon-list-blank").modal("hide");
            // 				Swal.fire({
            // 					icon: 'success',
            //   title: 'Vielen Dank2 !',
            //   text: obj.msg,

            //   //showCancelButton: true,
            //   confirmButtonColor: '#3085d6',
            //   //cancelButtonColor: '#d33',
            //   confirmButtonText: 'Schliessen'

            // });

            Swal.fire({
              imageUrl:
                "https://dev.styletimer.de/assets/uploads/users/iconesuccess.png",
              title: "Vielen Dank !",
              text: obj.msg,
              icon: "success",
              confirmButtonColor: "#3085d6",
              confirmButtonText: "Schliessen",
            });

            //Swal.fire(
            //  'Vielen Dank! !',
            //obj.msg,
            //'success'
            //);
          } else {
            $("#alert_message").html(
              "<strong>Message ! </strong>" + obj.message
            );
            $(".alert_message").css("display", "block");

            //location.reload();
          }
          unloading();
        }
      );
      return false;
    },
  });

  // add tax validation
  $("#addTaxform").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      name: {
        required: true,
      },
      rate: {
        required: true,
        number: true,
        max: 99.99,
      },
    },
    messages: {
      name: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Tax name is required",
      },
      rate: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Tax rate is required",
        number:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Enter a valid value.",
        max: "<i class='fas fa-exclamation-circle mrm-5'></i>Please enter value less than to 100.",
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
  });

  // Edit tax validation
  $("#editTaxes").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      ename: {
        required: true,
      },
      erate: {
        pattern: true,
        minlength: 1,
        maxlength: 5,
      },
    },
    messages: {
      ename: {
        required:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Tax name is required",
      },
      erate: {
        minlength:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Enter a valid value.",
        maxlength:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Please enter value less than to 100.",
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
  });

  // Edit tax validation
  //~ function checkoutFormValidation(){
  $("#checkoutForm").validate({
    errorElement: "span",
    errorClass: "error",
    rules: {
      totalPayInput: {
        pattern: true,
        minlength: 1,
        maxlength: 20,
      },
    },
    messages: {
      totalPayInput: {
        pattern:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Please enter valid value.",
        minlength:
          "<i class='fas fa-exclamation-circle mrm-5'></i>Please enter valid value.",
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
  });

  $("#suggestion_form").validate({
    errorElement: "span",
    errorClass: "error",
    rules: {
      suggest: {
        required: true,
      },
    },
    messages: {
      suggest: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_your_suggestion,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function () {
      loading();
      //~ var urls=$('#select_url').val();
      $.post(
        base_url + "home/submit_suggestion",
        $("#suggestion_form").serialize(),
        function (data) {
          //console.log(data);
          var obj = jQuery.parseJSON(data);
          if (obj.success == "1") {
            $("#suggestion_form").trigger("reset");
            $(".suggest_improvements").modal("hide");
            Swal.fire(suggested, obj.message, "success");
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: obj.message,
            });

            //location.reload();
          }
          unloading();
        }
      );
      return false;
    },
  });

  $("#suggestion_submit").click(function () {
    $("#suggestion_form").submit();
  });

  $(document).on("click", ".deleteBooking_old", function () {
    var url = $(this).attr("data-url");
    $("#booking-details-modal").modal("hide");
    var bid = $(this).attr("id");
    Swal.fire({
      title: "Bist du sicher?",
      text: "You want to delete this booking!",
      type: "warning",
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Bestätigen",
    }).then((result) => {
      if (result.value) {
        //$("#conf_close").trigger(click);
        location.href = url;
      } else {
        if (bid != "") {
          getBokkingDetail(bid);
        }
        return false;
      }
    });
  });

  $(document).on("click", ".deleteBooking", function () {
    var url = $(this).attr("data-url");
    $("#booking-details-modal").modal("hide");
    var bid = $(this).attr("id");
    var uid = $(this).attr("data-uid");
    Swal.fire({
      title: are_you_sure,
      html: you_want_delete_book,
      type: "warning",
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonClass: 'delete-booking-confirm-button',
      cancelButtonClass: 'delete-booking-confirm-button',
      confirmButtonText: 'Buchung löschen',
      cancelButtonText: abort,
    }).then((result) => {
      if (result.value) {
        //location.href=url;
        loading();
        $.ajax({
          url: base_url + "rebook/delete_booking_ajax",
          type: "POST",
          data: { bid: bid },
          success: function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.success == "1") {
              unloading();
              //alert($('#action_from').val());
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
              } else if ($("#action_from").val() == "calendar") {
                location.reload();
              } else if ($("#action_from").val() == "detail") {
                location.reload();
              }else getClientProfile(uid);
            } else {
              unloading();
            }
          },
        });
      } else {
        if (bid != "" && $("#action_from").val() != "list") {
          getBokkingDetail(bid);
        }
        return false;
      }
    });
  });

  $(document).on("click", ".deleteBookingEmp", function () {
    var url = $(this).attr("data-url");
    //$("#booking-details-modal").modal('hide');
    var bid = $(this).attr("id");
    var uid = $(this).attr("data-uid");
    Swal.fire({
      title: are_you_sure,
      html: you_want_delete_book,
      type: "warning",
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonClass: 'delete-booking-confirm-button',
      cancelButtonClass: 'delete-booking-confirm-button',
      confirmButtonText: 'Buchung löschen',
      cancelButtonText: abort,
    }).then((result) => {
      if (result.value) {
        //location.href=url;
        loading();
        $.ajax({
          url: base_url + "rebook/delete_booking_ajax",
          type: "POST",
          data: { bid: bid },
          success: function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.success == "1") {
              unloading();
              location.reload();
            } else {
              unloading();
            }
          },
        });
      } else {
        if (bid != "" && $("#action_from").val() != "list") {
          getBokkingDetail(bid);
        }
        return false;
      }
    });
  });

  $(document).on("click", "#saveReviewRating", function () {
    //alert('sf');
    var token = true;
    if ($('[name="rating"]:checked').length == 0) {
      $("#rating_err").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i>Bitte Sterne-Bewertung wählen'
      );
      token = false;
    } else if (
      $("input[name=rating]:checked").val() <= 3 &&
      $("#txtreview").val() == ""
    ) {
      $("#rating_text").html(
        '<i class="fas fa-exclamation-circle mrm-5"></i> Bitte Bewertung schreiben'
      );
      token = false;
      $("#rating_err").html("");
    } else {
      $("#rating_text").html("");
      $("#rating_err").html("");
    }

    if (token == true) {
      $(".widthfit").attr("disabled", true);
      loading();
      //~ var urls=$('#select_url').val();
      $.post(
        base_url + "booking/review",
        $("#frmReview").serialize(),
        function (data) {
          //console.log(data);
          var obj = jQuery.parseJSON(data);
          if (obj.success == "1") {
            getBokkingDetail(obj.bookid);
            $("#reting_review").modal("hide");
            $("#addrratingreviewForm").html("");
          } else {
            if (obj.page == "login") {
              $("#openLoginPopup").modal("show");
            } else {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: obj.message,
              });
            }
            //location.reload();
          }
          unloading();
        }
      );
    }
  });

  $("#contact_post").validate({
    errorElement: "label",
    errorClass: "error",
    rules: {
      name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      subject: {
        required: true,
      },
      message: {
        required: true,
      },
    },
    messages: {
      name: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_first_name,
      },
      email: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          Please_enter_your_email,
        email:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          email_valid_required,
      },
      subject: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_subject,
      },
      message: {
        required:
          '<i class="fas fa-exclamation-circle mrm-5"></i>' +
          please_enter_message,
      },
    },
    errorPlacement: function (error, element) {
      var name = $(element).attr("name");
      error.appendTo($("#" + name + "_validate"));
    },
    submitHandler: function (form) {
      $("#contact_req_send").attr("disabled", true);
      loading();
      $.post(
        base_url + "home/contact_post",
        $("#contact_post").serialize(),
        function (data) {
          var obj = jQuery.parseJSON(data);

          if (obj.success == "1") {
            $("#contact_req_send").attr("disabled", false);

            toastr.info(obj.message);
            grecaptcha.reset();

            setTimeout(function () {
              $("#contact_post").trigger("reset");
            }, 500);

          } else {
            $("#contact_req_send").attr("disabled", false);
            toastr.warning(obj.message);
          }
          unloading();
        }
      ).fail(function (xhr, status, error) {
        $("#frmsubmit").attr("disabled", false);
        unloading();
      });

      return false;
    }
  });

  // get_clientfilter();
});

$("#search_client").keypress(function () {
  // alert($(this).attr('name'));
  get_clientfilter();
});
$(document).on("click", "#globalsearch_icon", function () {
  // alert($(this).attr('id'));
  get_clientfilter();
});

function get_clientfilter() {
  var value = $("#search_client").val();
  loading();
  $.ajax({
    url: base_url + "merchant/search_client_filter",
    type: "POST",
    data: { search_client: value },
    success: function (response) {
      var obj = jQuery.parseJSON(response);
      //if(obj.success=='1'){
      $("#client_filter_list").html(obj.client_list);
      $("#booking_filter_list").html(obj.booking_list);
      $("#search-wala-modal").modal("show");
      $("#search-wala-modal").modal({ backdrop: "static", keyboard: false });
      unloading();
      //$('#search-wala-modal input#search_client').focus();
      setTimeout(function () {
        $("#search_client").focus();
      }, 1000);
      //alert('ss');
      /* }
              else{
              unloading();
              }*/
    },
  });
}
$(document).on("click", ".load_booking", function () {
  var value = $("#search_client").val();
  var page = $(this).attr("data-page");
  loading();
  $.ajax({
    url: base_url + "merchant/loadmore_booking",
    type: "POST",
    data: { search_client: value, page: page },
    success: function (response) {
      var obj = jQuery.parseJSON(response);
      if (obj.success == "1") {
        $(".load_booking").remove();
        $("#booking_filter_list").append(obj.booking_list);
        unloading();
      } else {
        unloading();
      }
    },
  });
});

$(document).on("change", ".filterby_revenew", function () {
  //alert('f');
  var uid = $(this).data("uid");
  get_userrevenew(uid);
});
function get_userrevenew(cid) {
  //alert('nhjk');
  var value = $("input[name=filter_sale_revenew]:checked").val();
  loading();
  $.ajax({
    url: base_url + "profile/filter_client_revenew",
    type: "POST",
    data: { filter: value, cid: cid },
    success: function (response) {
      var obj = jQuery.parseJSON(response);
      if (obj.success == "1") {
        // $(".load_booking").remove();
        $("#revenew").html(obj.revenew);
        $("#totalbook").html(obj.totalbook);
        $("#totalcomplete").html(obj.totalcomplete);
        $("#totalupcoming").html(obj.totalupcoming);
        $("#totalcanceled").html(obj.totalcanceled);
        $("#totalnoshow").html(obj.totalnoshow);
        unloading();
      } else {
        unloading();
      }
    },
  });
}

$(document).on("click", "#checkpinsubmit", function () {
  var value = $("#checkpinval").val();
  if (value.length < 4) {
    $("#checkpinerror").html("Bitte 4-stellige Pin eingeben.");
    return false;
  } else {
    loading();
    $.ajax({
      url: base_url + "profile/checkpin",
      type: "POST",
      data: { pin: value },
      success: function (response) {
        var obj = jQuery.parseJSON(response);
        if (obj.success == "1") {
          unloading();
          $("#Enter-pin").modal("hide");
        } else {
          unloading();
          $("#checkpinerror").html(obj.message);
        }
      },
    });
  }
});

$(document).on("keypress", "#checkpinval", function (e) {
  if (e.which == 13) {
    $("#checkpinsubmit").trigger("click");
    return false;
  }
});

$(document).on("click", "#resend_activelink", function () {
  var value = $("#identity").val();
  /*if(value==""){
     	$("#identity_validate").append('<label for="identity" generated="true" class="error">Please enter a valid email address.</label>');
     	return false;
     }else{*/
  loading();
  $.ajax({
    url: base_url + "auth/resend_activation_mail",
    type: "POST",
    data: { email: value },
    success: function (response) {
      var obj = jQuery.parseJSON(response);
      if (obj.success == "1") {
        unloading();
        $("#alert_sucmessage").html(
          "<strong>Message ! </strong>" + obj.message
        );
        $(".alert_sucmessage").css("display", "block");
        $(".alert_message").css("display", "none");
        $("#resend_activelink").hide();
      } else {
        unloading();
        $("#alert_message").html("<strong>Message ! </strong>" + obj.message);
        $(".alert_message").css("display", "block");
        $(".alert_sucmessage").css("display", "none");
      }
    },
  });
  /*}*/
});
