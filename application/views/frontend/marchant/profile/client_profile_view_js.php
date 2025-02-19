<script>
<?php
$usid = '';
if (!empty($this->session->userdata('st_userid'))) {
  $usid = $this->session->userdata('st_userid');
}
?>

var usid = '<?php echo $usid;?>';
if (usid) {
  localStorage.setItem("STATUS", "LOGGED_IN");
} else {
  localStorage.setItem("STATUS", "LOGGED_OUT");
}
$(function() {
  $(window).on("scroll", function() {
    if ($(window).scrollTop() > 90) {
      $(".header").addClass("header_top");
    } else {
      //remove the background property so it comes transparent again (defined in your css)
      $(".header").removeClass("header_top");
    }
  });
});

var date = new Date();
var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
var date40year = new Date('<?php  echo date("Y-m-d",strtotime("+10 years",time())); ?>');
$('.datepicker').datepicker({
  beforeShow: function(input, inst) {
    $(document).off('focusin.bs.modal');
  },
  onClose: function() {
    $(document).on('focusin.bs.modal');
  },
  minDate: today,
  changeMonth: true,
  changeYear: true,
  yearRange: date.getFullYear() + ':' + date40year.getFullYear(),
  prevText: '&#x3c;zurück', prevStatus: '',
  prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
  nextText: 'Vor&#x3e;', nextStatus: '',
  firstDay: 1,
  nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
  monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
  monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
  dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
  dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
  dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa']
});

$('#start_date').datepicker({
  beforeShow: function(input, inst) {
    $(document).off('focusin.bs.modal');
  },
  onClose: function() {
    $(document).on('focusin.bs.modal');
  },
  minDate: today,
  changeMonth: true,
  changeYear: true,
  maxDate: today,
  yearRange: date.getFullYear() + ':' + date40year.getFullYear(),
  prevText: '&#x3c;zurück', prevStatus: '',
  prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
  nextText: 'Vor&#x3e;', nextStatus: '',
  firstDay: 1,
  nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
  currentText: 'heute', currentStatus: '',
  todayText: 'heute', todayStatus: '',
  clearText: '-', clearStatus: '',
  closeText: 'schließen', closeStatus: '',
  monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
  monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
  dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
  dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
  dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
  dateFormat: 'dd/mm/yy',
});
$('#end_date').datepicker({
  beforeShow: function(input, inst) {
    $(document).off('focusin.bs.modal');
  },
  onClose: function() {
    $(document).on('focusin.bs.modal');
  },
  minDate: today,
  changeMonth: true,
  changeYear: true, 
  maxDate: today,
  yearRange: date.getFullYear() + ':' + date40year.getFullYear(),
  prevText: '&#x3c;zurück', prevStatus: '',
  prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
  nextText: 'Vor&#x3e;', nextStatus: '',
  firstDay: 1,
  nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
  currentText: 'heute', currentStatus: '',
  todayText: 'heute', todayStatus: '',
  clearText: '-', clearStatus: '',
  closeText: 'schließen', closeStatus: '',
  monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
  monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
  dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
  dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
  dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
  dateFormat: 'dd/mm/yy',
});



// Tooltips
$('[data-toggle="popover"]').popover({
  trigger: 'hover',
  'placement': 'top'
});



$(document).ready(function() {
  //getBookingList();


  $(document).on('click', "#pagination_clientBooking .page-item a", function() {
    var url = $(this).attr('href');
    //alert(url);
    if (url != undefined) {
      get_clientBookingList(url);
    }
    window.scrollTo(0, 350);
    //$("#listingTabl").focus();	
    return false;
    // alert(url);

  });
  $(document).on('click', ".blockClient", function() {
    var uid = $(this).attr('data-uid');
    var url = base_url + "profile/client_block";
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    loading();
    $.post(url, {
      uid: uid
    }, function(data) {


      var obj = jQuery.parseJSON(data);
      if (obj.success == '1') {
        $(".blockClient").text(obj.text);
        if (obj.text == 'Freigeben')
          $("#chg_block_text").html("BLOCKIERT");
        else
          $("#chg_block_text").html("");
      }
      unloading();
    });

  });

  $(document).on('click', ".shorting", function() {
    var short = $(this).attr('id');
    var chk = $("#short_" + short).val();
    if (chk == 'asc') {
      $("#short_" + short).val('desc');
      $("#short_by_field").val(short + ' desc');
    } else {
      $("#short_" + short).val('asc');
      $("#short_by_field").val(short + ' asc');
    }

    get_clientBookingList();
  });


  $(document).on('click', ".editNoteClient", function() {
    // alert('a');
    var notval = $("#getClientNote").attr('title');
    var uid = $("#getClientNote").attr('data-uid');
    if (notval == "") {
      notval = $('#editnotes_pp').val();
    }
    //$("#textClientHS").removeClass('display-n');
    //$("#notePara").addClass('display-n');
    $("#add-noteFromPoup").modal('show');
    //$('#clientNotevalue').froalaEditor('html.set', 'My custom paragraph.');
    var nid = $(this).attr('data-noteid');
    $.ajax({
      type: 'post',
      url: base_url + "merchant/get_clientusernote",
      data: {
        nid: nid
      },
      success: function(res) {
        var obj = jQuery.parseJSON(res);
        $("#clientNotevalue").val(obj.note);
        $(".fr-view").html(obj.note);
      }
    });
    //$(".fr-view").html(notval);
    //$("#clientNotevalue").val(notval);
    $("#clientNotevalue").attr('data-uid', uid);
    $("#about_salon").attr('data-uid', uid);


    //notePara,textClientHS,savenotesBtn
  });


  $(document).on('click', "#closenoteBtn", function() {
    $("#notePara").removeClass('display-n');
    $("#textClientHS").addClass('display-n');
    //notePara,textClientHS,savenotesBtn
  });

  $(document).on('click', "#savenotesBtnck", function() {
    var upval = $("#about_salon").val();
    var uid = $("#about_salon").attr('data-uid');
    $("#add_toolvalue").html(upval);
    $("#editnotes_pp").val(upval);
    /*if(upval==''){ 
       $("#err_note").removeClass('display-n');
       return false;
     }*/
    if (upval == '') {
      $("#getClientNote").html(
        '<a class="font-size-14 text-underline a_hover_orange cursor-p editNoteClient" style="color: #FF9944;"><?php echo $this->lang->line("add_note_customer") ?></a>'
      ); //text
      $("#add_toolvalue").css('display', 'none');
    } else {
      if (upval.length > 75) {
        var res = upval.substr(0, 75);
      } else {
        var res = upval
      }
      $("#add_toolvalue").css('display', 'block');
      //$("#getClientNote").text(res);
      $("#getClientNote").html(res);
    }

    $("#textClientHS").addClass('display-n');
    $("#notePara").removeClass('display-n');
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    loading();
    var url = base_url + "profile/update_notes";
    $.post(url, {
      uid: uid,
      notes: upval
    }, function(data) {

      var obj = jQuery.parseJSON(data);
      if (obj.success == '1') {
        if (obj.notes != "")
          $("#getClientNote").html(obj.notes);
        $("#getClientNote").removeClass("notes_view");
        if (obj.count > 75)
          $("#getClientNote").addClass("notes_view");

        /*if(upval.length > 75)
        {
          $("#getClientNote").addClass("notes_view");
        }*/

        //getClientProfile(enuid);

        //~ if(obj.text=='Unblock'){
        //~ $(".blockClient").removeClass('btn-new');
        //~ $(".blockClient").addClass('btn-new1');

        //~ }
        //~ else{
        //~ $(".blockClient").removeClass('btn-new1');
        //~ $(".blockClient").addClass('btn-new');
        //~ }	
        $(".blockClient").text(obj.text);
        $("#add-noteFromPoup").modal('hide');
        $(".modal").css('overflow-y', 'auto');
        $("#err_note").addClass('display-n');

      }
      unloading();
    });
  });
  $(document).on('click', "#savenotesBtn", function() {
    var upval = $("#clientNotevalue").val();
    var uid = $("#clientNotevalue").attr('data-uid');
    //alert(uid)
    //var enuid= $("#getClientNote").attr('data-enuid');
    //$("#getClientNote").attr('title',upval);

    $("#add_toolvalue").html(upval);
    $("#editnotes_pp").val(upval);
    /*if(upval==''){ 
       $("#err_note").removeClass('display-n');
       return false;
     }*/
    if (upval == '') {
      $("#getClientNote").html(
        '<a class="font-size-14 text-underline a_hover_orange cursor-p editNoteClient" style="color: #FF9944;"><?php echo $this->lang->line("add_note_customer") ?></a>'
      ); //text
      $("#add_toolvalue").css('display', 'none');
    } else {
      if (upval.length > 75) {
        var res = upval.substr(0, 75);
      } else {
        var res = upval
      }
      $("#add_toolvalue").css('display', 'block');
      //$("#getClientNote").text(res);
      $("#getClientNote").html(res);
    }

    $("#textClientHS").addClass('display-n');
    $("#notePara").removeClass('display-n');
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    loading();
    var url = base_url + "profile/update_notes";
    $.post(url, {
      uid: uid,
      notes: upval
    }, function(data) {

      var obj = jQuery.parseJSON(data);
      if (obj.success == '1') {
        if (obj.notes != "")
          $("#getClientNote").html(obj.notes);
        $("#getClientNote").removeClass("notes_view");
        if (obj.count > 75)
          $("#getClientNote").addClass("notes_view");

        /*if(upval.length > 75)
        {
          $("#getClientNote").addClass("notes_view");
        }*/

        //getClientProfile(enuid);

        //~ if(obj.text=='Unblock'){
        //~ $(".blockClient").removeClass('btn-new');
        //~ $(".blockClient").addClass('btn-new1');

        //~ }
        //~ else{
        //~ $(".blockClient").removeClass('btn-new1');
        //~ $(".blockClient").addClass('btn-new');
        //~ }	
        $(".blockClient").text(obj.text);
        $("#add-noteFromPoup").modal('hide');
        $(".modal").css('overflow-y', 'auto');
        $("#err_note").addClass('display-n');

      }
      unloading();
    });

    //notePara,textClientHS,
  });

  $(document).on('click', ".bookDetailShow", function() {
    $("#popup-v11").modal('show');
    $("#pbookId").text($(this).data('bookid'));
    var bid = $(this).data('encode');
    $(".change_anchor").html('<a class="colororange" target="_blank" href="' + base_url +
      'booking/downloadReceipt/' + bid + '"><img src="' + base_url + 'assets/frontend/images/printer.svg' +
      '" style="width:24px;"></a>');
    $("#pbookTime").text($(this).data('time'));
    $("#cbookTime").text($(this).data('complete'));
    $("#pbookPrice").text($(this).data('price'));
    $("#pbookDuration").text($(this).data('duration'));
    $("#pbookSalone").text($(this).data('salone'));
    $("#pbookService").text($(this).data('service'));
    $("#bookedvia").text($(this).data('bookedvia'));
    $("#salonaddress").html($(this).data('saddress'));
    //$(".shodropdown").removeClass('show');
  });


});

//~ $(document).on('show.bs.modal', '.modal', function (event) {
//~ var zIndex = 1040 + (10 * $('.modal:visible').length);
//~ $(this).css('z-index', zIndex);
//~ setTimeout(function() {
//~ $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
//~ }, 0);
//~ });



$(document).on('change', '.filterby_days', function() {
  //alert('d');
  var value = $(this).val();
  var uid = $('#listingTabl').attr('data-uid');
  //alert(value);
  if (value == "date") {
    $('.filterdate').css('display', 'inline-block');
    return false;
  } else
    $('.filterdate').hide();

  var status = $('input[name=booking_st]:checked').val();
  if (typeof status == 'undefined')
    status = '';

  var url = base_url + 'profile/client_booking_list/' + uid + '?short=' + value + '&status=' + status;
  get_clientBookingList(url);

});



$(document).on('change', '.book_status', function() {
  var status = $(this).val();
  var uid = $('#listingTabl').attr('data-uid');
  if (status == "all") {
    // $('.filterby_days').prop('checked', false);
    //$('#btn_text').text('Select Filter');
    //$('.filterdate').hide();
  }
  var start = $('#start_date').val();
  var end = $('#end_date').val();
  var value = $('input[name=filter]:checked').val();
  if (typeof value == 'undefined')
    value = '';

  var url = base_url + 'profile/client_booking_list/' + uid + '?short=' + value + '&status=' + status +
    '&start_date=' + start + '&end_date=' + end;
  get_clientBookingList(url);

});

$(document).on('change', ".change_client_bookinglimit", function() {
  var uid = $('#listingTabl').attr('data-uid');
  var start = $('#start_date').val();
  var end = $('#end_date').val();
  var value = $('input[name=filter]:checked').val();
  if (typeof value == 'undefined')
    value = '';

  var status = $('input[name=booking_st]:checked').val();
  if (typeof status == 'undefined')
    status = '';

  var url = base_url + 'profile/client_booking_list/' + uid + '?short=' + value + '&status=' + status +
    '&start_date=' + start + '&end_date=' + end;
  get_clientBookingList(url);
});


$(document).on('click', '#search_filter', function() {
  var token = true;
  var start = $('#start_date').val();
  var end = $('#end_date').val();
  var uid = $('#listingTabl').attr('data-uid');
  if (start == "") {
    $('#start_error').html('please select start date');
    token = false;
  } else
    $('#start_error').html('');

  if (end == "") {
    $('#end_error').html('please select end date');
    token = false;
  } else
    $('#end_error').html('');
  if (token == true) {

    var status = $('input[name=booking_st]:checked').val();
    if (typeof status == 'undefined')
      status = '';

    var value = $('input[name=filter]:checked').val();
    if (typeof value == 'undefined')
      value = '';

    var url = base_url + 'profile/client_booking_list/' + uid + '?short=' + value + '&status=' + status +
      '&start_date=' + start + '&end_date=' + end;
    get_clientBookingList(url);
  }
});


function onsearch_filter() {

  var start = '';
  var end = '';
  var uid = $('#listingTabl').attr('data-uid');
  var status = $('input[name=booking_st]:checked').val();
  if (typeof status == 'undefined')
    status = '';

  var value = $('input[name=filter]:checked').val();
  if (typeof value == 'undefined')
    value = '';


  if (value == 'date') {
    var start = $('#start_date').val();
    var end = $('#end_date').val();
  }

  var url = base_url + 'profile/client_booking_list/' + uid + '?short=' + value + '&status=' + status + '&start_date=' +
    start + '&end_date=' + end;
  return url;
}

///  export to csv 
$(document).on('click', '.export_filterreport', function() {
  var type = $(this).attr('data-id');
  var start = '';
  var end = '';
  var order = $("#orderby").val();
  var shortby = $("#shortby").val();
  var status = $('input[name=booking_st]:checked').val();
  if (typeof status == 'undefined')
    status = '';

  var value = $('input[name=filter]:checked').val();
  if (typeof value == 'undefined')
    value = '';

  if (value == 'date') {
    var start = $('#start_date').val();
    var end = $('#end_date').val();
  }
  var uid = $('#listingTabl').attr('data-uid');
  window.location.href = base_url + 'profile/download_userbooking_in_csv_exel/' + uid + '/' + type + '?short=' +
    value + '&status=' + status + '&start_date=' + start + '&end_date=' + end + '&orderby=' + order + '&shortby=' +
    shortby;
});


function get_clientBookingList(url = '') {

  var lim = $("input[name='limit']:checked").val();
  var uid = $('#listingTabl').attr('data-uid');
  var sht = $('#short_by_field').val();
  if (url == '') {
    url = base_url + "profile/client_booking_list/" + uid;
  }
  var status = localStorage.getItem('STATUS');
  if (status == 'LOGGED_OUT') {
    console.log('STATUS=' + status);
    console.log(base_url)
    window.location.href = base_url;
    $(window.location)[0].replace(base_url);
    $(location).attr('href', base_url);
  }
  console.log('STATUS=' + status);
  loading();
  $.post(url, {
    limit: lim,
    order: sht
  }, function(data) {
    var obj = jQuery.parseJSON(data);
    if (obj.success == '1') {
      //var time = $("#selctTimeFilter").val();
      // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
      //console.log(obj.html);
      $("#all_listing").html(obj.html);
      $("#pagination_clientBooking").html(obj.pagination);
    }
    unloading();
  });
}
</script>