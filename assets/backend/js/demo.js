$(document).ready(function () {
  //Welcome Message (not for login page)

  function notify(message, type) {
    $.growl(
      {
        message: message,
      },
      {
        type: type,
        allow_dismiss: false,
        label: "Cancel",
        className: "btn-xs btn-inverse",
        placement: {
          from: "top",
          align: "right",
        },
        delay: 2500,
        animate: {
          enter: "animated bounceIn",
          exit: "animated bounceOut",
        },
        offset: {
          x: 20,
          y: 85,
        },
      }
    );
  }

  $(document).on("click", ".cancel_booking_admin", function () {
    //var id = $('#bookingid').val();

    var id = $(this).attr("id");
    swal(
      {
        title: "Bist du sicher?",
        text: "You want to cancel this booking!",
        type: "warning",
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Bestätigen",
      },
      function () {
        loading();
        $.ajax({
          url: base_url + "backend/user/booking_cancel",
          type: "POST",
          data: { book_id: id },
          success: function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.success == 1) {
              get_clientBookingList();
              unloading();
            } else {
              unloading();
              swal("Unable to Cancel", obj.msg, "error");
              // $("#error_message").css("display", "");
              //$("#alert_message").html(obj.msg);
            }
          },
        });
      }
    );

    /* swal({
                          title: 'Bist du sicher?',
                          text: "You want to cancel this booking!",
                          type: 'warning',
                          showCancelButton: true,
                          reverseButtons:true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Bestätigen'
                        }).then((result) =>{
                        if (result.value) {
                            loading();
                            $.ajax({
                            url: base_url+"backend/user/booking_cancel",
                            type: "POST",
                            data: { book_id : id},
                            success: function (data) {
                            var obj = jQuery.parseJSON( data );
                              if(obj.success == 1){
                                     get_clientBookingList();
                                    unloading();
                                 }
                                else
                                {
                                    unloading();
                                    swal(
                                      'Unable to Cancel',
                                      obj.msg,
                                      'error'
                                    );

                                }

                            }
                            }); 
                            
                          }
                        });*/
  });

  $(document).on("click", ".getsalondetails", function () {
    var id = $(this).attr("data-sid");
    var url = $(this).attr("data-url");
    $("#salon_list_popup").modal("hide");
    viewRowsalon(id, url);
  });

  function loading() {
    // alert('load');
    $("#loading_div img.process_loading").removeClass("hide");
    $("#loading_div").addClass("overlay");
  }
  function unloading() {
    //alert('unload');
    $("#loading_div img.process_loading").addClass("hide");
    $("#loading_div").removeClass("overlay");
  }

  /*if (!$('.login-content')[0]) {
        notify('Welcome back Mallinda Hollaway', 'inverse');
    }*/
});

//*******custome js************//

//****User listing Start****//

//edit record
function EditRow(id, url) {
  //alert(base_url);
  window.location.href = base_url + url + id;
}

//view record
function viewRow(url) {
  //alert(base_url);
  window.location.href = base_url + url;
}
//delete record
function DeleteRow(id, url, text = "category") {
  //alert(id);
  swal(
    {
      title: "Bist du sicher?",
      text: "You want to delete this " + text + " !",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      //alert("dsf");
      window.location.href = base_url + url + id;
      //swal("Deleted!", "Your imaginary file has been deleted.", "success");
    }
  );
}

//edit status
function EditStatus(id, url) {
  //alert(base_url);
  var message = "You want to change status of this !";
  if (url == "/backend/user/status/inactive/marchant/") {
    var message =
      "You want to change status of this, also this will cancel subscription.";
  }
  swal(
    {
      title: "Bist du sicher?",
      text: message,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, change it!",
      closeOnConfirm: false,
    },
    function () {
      //alert("dsf");
      window.location.href = base_url + url + id;
      //swal("Deleted!", "Your imaginary file has been deleted.", "success");
    }
  );
}

// delete user
//delete record
function DeleteUser(id, url) {
  var message = "You want to delete this record !";
  if (url == "/backend/user/delete/user/") {
    var message =
      "Deleting this customer, will affect booking count and revenue for salons that have attended to him/her.";
  }
  //alert(id);
  swal(
    {
      title: "Bist du sicher?",
      text: message,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      //alert("dsf");
      window.location.href = base_url + url + id;
      //swal("Deleted!", "Your imaginary file has been deleted.", "success");
    }
  );
}

//****User listing End****//

//****Eyedrop listing Start****//

//edit record
function EditEyedrop(id) {
  //alert(base_url);
  window.location.href = base_url + "/backend/eyedrop/make/" + id;
}

//delete record
function DeleteEyedrop(id) {
  //alert(id);
  swal(
    {
      title: "Bist du sicher?",
      text: "You will not be able to recover this user!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      //alert("dsf");
      window.location.href = base_url + "/backend/eyedrop/delete/" + id;
      //swal("Deleted!", "Your imaginary file has been deleted.", "success");
    }
  );
}

//****Eyedrop listing End****//

//****Treatment listing Start****//

$(document).on("click", ".add_eyedrop_times", function () {
  var id = $(this).attr("data-id");
  var count = $("#eyedrop_sec_" + id + " div.eyedrop_sec_times").length;

  //~ var html = $.post(base_url+'/backend/treatment/eyedrop_times').done();
  //~ console.log(html);
  //~ return false;

  $.post(
    base_url + "/backend/treatment/eyedrop_times",
    { id: id },
    function (data) {
      $("#eyedrop_sec_" + id + " .html").append(data);
      $(".selectpicker").selectpicker("refresh");
    }
  );
  return false;
});

$(document).on("click", ".add_new_eyedrop button", function () {
  var count = $(".eyedrop_sec").length;

  $.post(
    base_url + "/backend/treatment/eyedrop_section",
    { id: count + 1 },
    function (data) {
      $("#eyedrop_div").append(data);
      $(".selectpicker").selectpicker("refresh");
    }
  );
  return false;
});

$(document).on("click", ".remove_eyedrop_sec_times", function () {
  $(this).parents(".eyedrop_sec_times").remove();
});
$(document).on("click", ".remove_eyedrop_sec", function () {
  $(this).parents(".eyedrop_sec").remove();
});

//edit record
function EditTreatment(id) {
  //alert(base_url);
  window.location.href = base_url + "/backend/treatment/make/" + id;
}

//delete record
function DeleteTreatment(id) {
  //alert(id);
  swal(
    {
      title: "Bist du sicher?",
      text: "You will not be able to recover this user!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      //alert("dsf");
      window.location.href = base_url + "/backend/treatment/delete/" + id;
      //swal("Deleted!", "Your imaginary file has been deleted.", "success");
    }
  );
}

//****Treatment listing Start****//

//****Quetionaire listing Start****//
function EditQuetion(id) {
  //alert(base_url);
  window.location.href = base_url + "/backend/quetionaire/make/" + id;
}

//delete record
function DeleteQuetion(id) {
  //alert(id);
  swal(
    {
      title: "Bist du sicher?",
      text: "You will not be able to recover this user!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      //alert("dsf");
      window.location.href = base_url + "/backend/quetionaire/delete/" + id;
      //swal("Deleted!", "Your imaginary file has been deleted.", "success");
    }
  );
}

//edit status
function copy_reflink(id, value) {
  var tempInput = document.createElement("input");
  tempInput.style = "position: absolute; left: -1000px; top: -1000px";
  tempInput.value = value;
  document.body.appendChild(tempInput);
  tempInput.select();
  document.execCommand("copy");
  document.body.removeChild(tempInput);
  $("#" + id + "_btntext").text("Copied");
  setTimeout(function () {
    $("#" + id + "_btntext").text("Copy Link");
  }, 2000);
  return false;
}

$(document).on("click", "#faq_submit", function () {
  var token = true;
  if ($("#category").val() == "0") {
    $("#category_error").html("Please enter category");
    token = false;
  } else $("#category_error").html("");
  if ($("#question").val() == "") {
    $("#question_error").html("Please enter question");
    token = false;
  } else $("#question_error").html("");
  if ($("#answer").val() == "") {
    $("#answer_error").html("Please enter answer");
    token = false;
  } else $("#answer_error").html("");

  if (token == false) return false;

  /*$.ajax({
				url: base_url+"backend/faq/make",
				type: "POST",
				data:$("#form-faq").serialize(),
				success: function (data) {
				//var obj = jQuery.parseJSON( data );
				 window.location.href = base_url+'backend/faq/listing';
				 }
					
			}); 
		return false;*/
});

$(document).on("submit", "#static_frm", function () {
  var token = true;

  if ($("#title_name").val() == "") {
    $("#title_err").html("Please enter page title");
    $(window).scrollTop(0);
    token = false;
  }
  if ($("#about_salon").val() == "") {
    $("#description_err").html("Please enter content for this page");
    token = false;
  }

  if (token == false) {
    return false;
  }
});
$(document).on("click", ".edit_about", function () {
  $("#editor_div").show();
  $("#about_div").hide();
});
function get_salon_list(id, value) {
  $.ajax({
    url: value,
    type: "POST",
    data: {},
    success: function (data) {
      var obj = jQuery.parseJSON(data);
      if (obj.html != "") {
        console.log(obj.html);
        $("#all_salon_listing").html(obj.html);
        $("#salon_list_popup").modal("show");
      }
    },
  });
}

function login_tosalon(id, url) {
  //alert(base_url);
  window.location.href = base_url + url + id;
}
