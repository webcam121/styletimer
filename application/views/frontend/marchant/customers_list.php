<?php $this->load->view('frontend/common/header'); ?>
<div class="d-flex pt-84">
  <?php $this->load->view('frontend/common/sidebar'); ?>
  <?php $this->load->view('frontend/common/editer_css'); ?>
  <style type="text/css">
  .fr-box.fr-basic .fr-element {
    min-height: 220px !important;
  }

  .fr-wrapper {
    height: 250px !important;
  }

  #moreParagraph-2,
  #moreRich-2,
  #moreParagraph-1,
  #subscript-1,
  #superscript-1,
  #clearFormatting-1,
  #strikeThrough-2,
  #strikeThrough-2,
  #subscript-2,
  #superscript-2,
  #clearFormatting-2,
  #moreMisc-2,
  #undo-2,
  #redo-2 {
    display: none !important;
  }

  .fr-view p {
    margin-bottom: 0 !important;
  }
  </style>
  <div class="right-side-dashbord w-100 pl-30 pr-30 relative">
    <div class="border-radius4 height40 text-center lineheight40 mt-10" id="slectAllOption"
      style="display:none; background: #d4edda;">
      <span class="color666 fontfamily-regular font-size-14">Es wurden alle <b id="selectText"> 10 </b>
        <?php echo $this->lang->line(
            'customer-are-selected'
        ); ?></span> &nbsp; &nbsp; &nbsp; <span class="colorcyan font-size-14 fontfamily-regular" id="allCounttext"
        style="display:none;"> Select all Customers
        in Primary</span>
    </div>
    <div class="bgf8d7da border-radius4 height40 text-center lineheight40 mt-10 hide_news display-n" id="valid_error">
      <span class="color666 fontfamily-regular font-size-14" id="msg_err">Please Select atleast one user</span>
    </div>
    <div class="bgd4edda border-radius4 height40 text-center lineheight40 mt-10 hide_news display-n" id="success_msg">
      <span class="color666 fontfamily-regular font-size-14">Newsletter wurde erfolgreich versendet.</span>
    </div>
    <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-20">

      <input type="hidden" name="slectall" value="" id="checkallcust">
      <div class="pt-20 pb-20 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
        <?php $this->load->view('frontend/common/alert'); ?>
        <div class="relative display-ib">
          <?php if (!empty($_GET['tempid'])) { ?>
          <div class="btn-group multi_sigle_select widthfit110v mr-1 ml-10">
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn ">Alle Services</button>
            <ul class="dropdown-menu mss_sl_btn_dm widthfit115v" style="max-height:none">
              <li class="radiobox-image">
                <input type="radio" id="idc_0" name="category" class="change_category" value="">
                <label for="idc_0">Alle Services</label>
              </li>
              <?php if (!empty($merchant_category)) {
                  foreach ($merchant_category as $cat) { ?>
              <li class="radiobox-image">
                <input type="radio" id="idc_<?php echo $cat->category_id; ?>" name="category" class="change_category"
                  value="<?php echo $cat->category_id; ?>">
                <label for="idc_<?php echo $cat->category_id; ?>"><?php echo $cat->category; ?></label>
              </li>
              <?php }
              } ?>
            </ul>
          </div>
          <div class="btn-group multi_sigle_select widthfit110v ml-10">
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn ">Letzter Besuch</button>
            <ul class="dropdown-menu mss_sl_btn_dm widthfit110v">
              <li class="radiobox-image">
                <input type="radio" id="idw_0" name="visit" class="change_week" value="">
                <label for="idw_0">letzter Besuch</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_1" name="visit" class="change_week" value="1w">
                <label for="idw_1">1 Woche</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_2" name="visit" class="change_week" value="2w">
                <label for="idw_2">2 Wochen</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_3" name="visit" class="change_week" value="4w">
                <label for="idw_3">4 Wochen</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_4" name="visit" class="change_week" value="1m">
                <label for="idw_4">1 Monat</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_5" name="visit" class="change_week" value="2m">
                <label for="idw_5">2 Monate</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_6" name="visit" class="change_week" value="3m">
                <label for="idw_6">3 Monate</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_7" name="visit" class="change_week" value="4m">
                <label for="idw_7">4 Monate</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_8" name="visit" class="change_week" value="5m">
                <label for="idw_8">5 Monate</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_9" name="visit" class="change_week" value="6m">
                <label for="idw_9">6 Monate</label>
              </li>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="idw_10" name="visit" class="change_week" value="1y">
                <label for="idw_10">1 Jahr</label>
              </li>

            </ul>
          </div>
          <?php } else { ?>
          <span class="color999 fontfamily-medium font-size-14"><?php echo $this->lang->line(
              'Show'
          ); ?></span>
          <?php } ?>
          <div class="btn-group multi_sigle_select widthfit110v mr-3 ml-20">
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn ">10</button>
            <ul class="dropdown-menu mss_sl_btn_dm widthfit110v">
              <li class="radiobox-image">
                <input type="radio" id="id_14" name="limit" class="change_limit" value="10">
                <label for="id_14">10</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_15" name="limit" class="change_limit" value="20">
                <label for="id_15">20</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_16" name="limit" class="change_limit" value="30">
                <label for="id_16">30</label>
              </li>
              <li class="radiobox-image">
                <input type="radio" id="id_161" name="limit" class="change_limit" value="all">
                <label for="id_161">Alle anzeigen</label>
              </li>
            </ul>
          </div>
        </div>

        <!--  <form method="get"> -->
        <div class="display-ib mr-20">
          <input type="text" name="sch" placeholder="<?php echo $this->lang->line('Search_Name_client'); ?>" value="<?php if (isset($_GET['sch'])) {
                echo $_GET['sch'];
            } ?>" class=" widthfit310v cust_search" id="sch_data">
        </div>
        <?php if (empty($_GET['tempid'])) { ?>
        <div class="display-ib ml-auto">
          <button class="btn btn-large widthfit addcustomerbutton" data-id="" type="button">Kunde/-in hinzufügen</button>
        </div>
        <?php } ?>
        <?php if (!empty($_GET['tempid'])) { ?>
        <script type="text/javascript">
        var temp_id = "<?php echo $_GET['tempid']; ?>"
        </script>
        <div class="display-ib ml-auto">
          <button class="btn btn-large widthfit" data-text="<?php echo $_GET['tempid']; ?>" id="sendNesslater">
            <?php echo $this->lang->line(
                'Send_news'
            ); ?>
          </button>
        </div>
        <?php } else { ?>
        <script type="text/javascript">
        var temp_id = "";
        </script>
        <?php } ?>
      </div>

      <div class="my-table customer-list-table">
        <table class="table">
          <thead>
            <tr>
              <?php if (!empty($_GET['tempid'])) { ?>
              <th class="text-center pl-3 height56v">
                <div class="checkbox mt-0 mb-0">
                  <label class="fontsize-12 fontfamily-regular color333">
                    <input type="checkbox" name="remember" class="allcheck" id="select_all" value="">
                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                  </label>
                </div>
              </th>
              <?php } ?>
              <th class="pl-5 height56v" style="min-width: 220px;"><a href="javascript:void(0)" class="color333 custshorting" data-short="asc"
                  id="st_users.first_name"><?php echo ucfirst(
                      strtolower($this->lang->line('Customer'))
                  ); ?>
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/caret-arrow-up.png'
                    ); ?>" class="display-b " style="width:8px;">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/sort-down.png'
                    ); ?>" class="display-b " style="width:8px;">
                  </div>
                </a></th>
              <th class="text-center height56v"><a href="javascript:void(0)" class="color333 custshorting"
                  data-short="asc" id="st_users.email"><?php echo $this->lang->line(
                      'Email'
                  ); ?>
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/caret-arrow-up.png'
                    ); ?>" class="display-b " style="width:8px;">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/sort-down.png'
                    ); ?>" class="display-b " style="width:8px;">
                  </div>
                </a></th>
              <th class="text-center height56v"><a href="javascript:void(0)" class="color333 custshorting"
                  data-short="asc" id="st_users.mobile"><?php echo $this->lang->line(
                      'Contact'
                  ); ?>
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/caret-arrow-up.png'
                    ); ?>" class="display-b " style="width:8px;">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/sort-down.png'
                    ); ?>" class="display-b " style="width:8px;">
                  </div>
                </a></th>
              <th class="text-center height56v"><a href="javascript:void(0)" class="color333 custshorting"
                  data-short="asc" id="st_users.gender"><?php echo ucfirst(
                      strtolower($this->lang->line('Gender'))
                  ); ?>
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/caret-arrow-up.png'
                    ); ?>" class="display-b " style="width:8px;">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/sort-down.png'
                    ); ?>" class="display-b " style="width:8px;">
                  </div>
                </a></th>
              <th class="text-center height56v"><a href="javascript:void(0)" class="color333 custshorting"
                  data-short="asc" id="bookcount"><span class="tablete-none"><?php echo $this->lang->line(
                      'NoOf'
                  ); ?>
                  </span><?php echo $this->lang->line('Bookings'); ?>
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/caret-arrow-up.png'
                    ); ?>" class="display-b " style="width:8px;">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/sort-down.png'
                    ); ?>" class="display-b " style="width:8px;">
                  </div>
                </a></th>
              <th class="text-center height56v"><a href="javascript:void(0)" class="color333 custshorting"
                  data-short="asc" id="lastbook"><?php echo ucwords(
                      strtolower($this->lang->line('Last_Visited'))
                  ); ?>
                  <div class="display-ib vertical-middle ml-1">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/caret-arrow-up.png'
                    ); ?>" class="display-b " style="width:8px;">
                    <img src="<?php echo base_url(
                        'assets/frontend/images/sort-down.png'
                    ); ?>" class="display-b " style="width:8px;">
                  </div>
                </a></th>
              <th class="text-center height56v">Ursprung</th>
              <th class="text-center height56v"><?php echo $this->lang->line(
                  'Notes'
              ); ?> </th>
              <th class="text-center height56v" style="min-width: 108px;"><?php echo $this->lang->line(
                  'Action'
              ); ?> </th>
            </tr>
          </thead>
          <tbody id="allcust_listing">

          </tbody>
        </table>
        <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
          <ul class="pagination" style="display: inline-flex;" id="pagination">

          </ul>
        </nav>
      </div>
      <input type="hidden" id="shortby" value="asc">
      <input type="hidden" id="orderby" value="st_booking.id">
      <!-- dashboard right side end -->
    </div>
  </div>
</div>

<div class="modal fade" id="customer-delete-popup">
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
        <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;">
          Bist du sicher, dass du diesen Kunden löschen möchtest?
        </p>
        <input type="hidden" id="deleteid" name="" value="0">
        <button id="deletecustomer" type="button" class=" btn btn-large widthfit"><?php echo $this->lang->line('Delete'); ?></button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="edit-customer-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
    <div class="modal-content"> 
      <div class="modal-body" id="editCustomerHtml">
         
	  </div>
	</div>
  </div>
</div>

<?php
$this->load->view('frontend/common/footer_script');
$this->load->view('frontend/common/editer_js');
$this->load->view('frontend/marchant/add_new_customer', ['custlisting' => true]);
?>
<script type="text/javascript">
// Tooltips
$('[data-toggle="popover"]').popover({
  trigger: 'hover',
  'placement': 'top'
});

$(document).ready(function() {

  //alert(temp_id);
  getCustomerList();

  function getCustomerList(url = '') {

    //alert(tempid);

    var lim = $("input[name='limit']:checked").val();
    var order = $("#orderby").val();
    var shortby = $("#shortby").val();
    var sch = $('#sch_data').val();

    if (temp_id != "") {
      var category = $("input[name='category']:checked").val();
      //alert(category);
      var visit = $("input[name='visit']:checked").val();
    } else {
      var category = '';
      var visit = '';
    }

    //var uid=$('#listingTabl').attr('data-uid');
    if (url == '') {
      url = base_url + "merchant/customer_list";
    }

    loading();
    //alert('s');
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    $.post(url, {
      limit: lim,
      search: sch,
      newcheck: temp_id,
      orderby: order,
      shortby: shortby,
      category: category,
      visit: visit
    }, function(data) {


      var obj = jQuery.parseJSON(data);
      if (obj.success == '1') {
        console.log('RES=' + obj.success);
        //var time = $("#selctTimeFilter").val();
        // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
        $("#allcust_listing").html(obj.html);
        $("#pagination").html(obj.pagination);
        if (temp_id != "")
          $(".news_row").show();
        else
          $(".news_row").hide();

        $('[data-toggle="popover"]').popover({
          trigger: 'hover',
          'placement': 'top'
        });

      }
      unloading();
    });

  }
  $(document).on('change', ".change_limit", function() {

    getCustomerList();
    //$(".shodropdown").removeClass('show');
  });

  $(document).on('change', ".change_category", function() {
    getCustomerList();
    //$(".shodropdown").removeClass('show');
  });
  $(document).on('change', ".change_week", function() {
    getCustomerList();
    //$(".shodropdown").removeClass('show');
  });

  $(document).on('keyup', ".cust_search", function() {
    getCustomerList();
  });
  $(document).on('click', ".subcust_search", function() {
    getCustomerList();
  });
  $(document).on('click', "#pagination .page-item a", function() {
    var url = $(this).attr('href');

    if (url != undefined) {
      getCustomerList(url);
    }
    window.scrollTo(0, 350);
    //$("#listingTabl").focus();  
    return false;
    // alert(url);

  });

  $(document).on('click', '.addnote', function() {
    $('#txtnote').val('');
    $("#booking_ids").val($(this).attr('id'));
    $(".para_text").text('<?php echo $this->lang->line(
        'add_notes_for_customer'
    ); ?>');
    $('label[for=txtnote]').remove();
  });

  $(document).on("hidden.bs.modal", '#add-note', function () {
    $(".deleteNoteData").css('display', 'none');
    $("#txtnote").val('');
    $(".fr-view").html('');
  });

  $(document).on('click', '.editNotes', function() {

    var nid = $(this).attr('data-noteid');
    //$("#txtnote").val($(this).attr('data-content'));
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    $.ajax({
      type: 'post',
      url: base_url + "merchant/get_clientusernote",
      data: {
        nid: nid
      },
      success: function(res) {
        var obj = jQuery.parseJSON(res);
        $("#txtnote").val(obj.note);
        $(".fr-view").html(obj.note);
        $(".deleteNoteData").css('display', 'inline-block');
      }
    });

    //$(".fr-view").html($(this).attr('data-notes'));
    var id = $(this).attr('data-id');
    $('#booking_ids').val(id);
    $(".para_text").text('Kundennotiz hinzufügen');

    $("#add-note").modal('show');
    $('label[for=txtnote]').remove();
    $("#booking_id_note").val($(this).attr('data-id'));

  });

  $(document).on('click', '.deleteNoteData', function() {
    //alert($(this).attr('data-content'));
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
      console.log('STATUS=' + status);
      console.log(base_url)
      window.location.href = base_url;
      $(window.location)[0].replace(base_url);
      $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    var nid = $("#booking_id_note").val();
    $.ajax({
      type: 'post',
      url: base_url + "merchant/delete_clientusernote",
      data: {
        nid: nid
      },
      success: function(res) {
        location.reload();
        // var obj = jQuery.parseJSON( res );
        //   $("#txtnote").val(obj.note);
        //   $(".fr-view").html(obj.note);
      }
    });

  });


  $(document).on("change", "#select_all", function() { //"select all" change
    var status = this.checked; // "select all" checked status
    $('.checkbox').each(function() { //iterate all listed checkbox items
      this.checked = status; //change ".checkbox" checked status
    });
    if (status == true) {
      var status = localStorage.getItem('STATUS');
      if (status == 'LOGGED_OUT') {
        console.log('STATUS=' + status);
        console.log(base_url)
        window.location.href = base_url;
        $(window.location)[0].replace(base_url);
        $(location).attr('href', base_url);
      }
      console.log('STATUS=' + status);
      $.post(base_url + "newsletter/get_count_all_customer", function(data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == '1') {
          $("#slectAllOption").css('display', 'block');
          $("#selectText").text($('.checkbox:checked').length);
          if ($('.checkbox:checked').length < obj.count) {
            $("#allCounttext").css('display', 'block');
            $("#allCounttext").text("Select all " + obj.count + " Customers in Primary");
          }
        }


      });
    } else {
      $("#slectAllOption").css('display', 'none');
      $("#allCounttext").css('display', 'none');

    }
  });

  $(document).on("change", ".checkbox", function() { //".checkbox" change
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if (this.checked == false) {
      $("#slectAllOption").css('display', 'none');
      $("#allCounttext").css('display', 'none'); //if this item is unchecked
      $("#select_all")[0].checked = false; //change "select all" checked status to false
    }

    //check "select all" if all checkbox items are checked
    if ($('.checkbox:checked').length == $('.checkbox').length) {
      $("#select_all")[0].checked = true; //change "select all" checked status to true
    }
  });

  $(document).on('click', '#sendNesslater', function() {
    var tempid = $(this).attr('data-text');
    var chkArray = [];
    /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
    $(".checkbox:checked").each(function() {
      chkArray.push($(this).val());
    });
    var valus = $("#checkallcust").val();
    var selected;
    if (valus != undefined && valus != "") {
      selected = valus;
    } else {
      selected = chkArray.join(',');
    }
    if (selected.length < 1) {
      $('#valid_error').show();

      $('#valid_error').addClass('messageAlert');
      $('#valid_error').removeClass('display-n');
      $('#msg_err').html('Please select atleast one user.');
      $('#success_msg').hide();
      setTimeout(function() {
        $(".hide_news").hide();
      }, 4000);
      //alert('Please select atleast one user.');
      return false;
    } else {
      //~ //var result = confirm("Are you sure you want send n?");
      //~ if (result) {
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
      $("#sendNesslater").attr("disabled", true);
      // return false;
      $.ajax({
        type: 'post',
        url: base_url + "newsletter/send",
        data: {
          usersIds: selected,
          tempid: tempid
        },
        success: function(res) {
          var obj = jQuery.parseJSON(res);
          unloading();
          if (obj.success == '1') {
            //alert(obj.message);
            $('#slectAllOption').hide();
            $('#success_msg').removeClass('display-n');
            $('#success_msg').show();
            setTimeout(function() {
              location.href = base_url + "merchant/selectnewesletter";
            }, 3000);

          } else {
            $('#valid_error').show();
            $('#msg_err').html(obj.message);
            $('#success_msg').hide();
            $('#sendNesslater').attr("disabled", false);
            //alert(obj.message);
          }
        }
      });

      setTimeout(function() {
        $(".hide_news").hide();
      }, 4000);

      //~ }
    }

  });



  $(document).on('click', '.custshorting', function() {

    $("#orderby").val($(this).attr('id'));
    $("#shortby").val($(this).attr('data-short'));

    if ($(this).attr('data-short') == 'asc') {
      $(this).attr('data-short', 'desc');
    } else {
      $(this).attr('data-short', 'asc');
    }
    getCustomerList();

  });

  $(document).on('click', '#delete_customer_old', function() {
    //alert();
  });


  $(document).on('click', '#conf_close', function() {
    var id = $(this).attr('data-user');
    if (id != undefined) {
      getClientProfile(id);
    }
  });

  $(document).on('click', '.addcustomerbutton', function() {
    $("#popup-v12").modal('show');
  });

  $(document).on('click','.editcustomerbutton',function(){
		var id = $(this).attr('data-id');
		
		var gurl=base_url+"merchant/editCustomer";
		$.get(gurl,{id:id},function(data){
			var obj = jQuery.parseJSON( data );
			if(obj.success=='1'){
				$("#editCustomerHtml").html(obj.html);
				$("#edit-customer-modal").modal('show');
			}

		});
	});

});


$(function() {
  const editorInstance = new FroalaEditor('#txtnote', {
    enter: FroalaEditor.ENTER_P,
    key: 'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
    placeholderText: null,
    language: 'de',
    events: {
      initialized: function() {
        const editor = this
        this.el.closest('form').addEventListener('submit', function(e) {
          //console.log(editor.$oel.val())
          // e.preventDefault()
        })
      }
    }
  })
});

$(function() {
  const editorInstance = new FroalaEditor('.cccctxtnote', {
    enter: FroalaEditor.ENTER_P,
    key: 'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
    placeholderText: null,
    language: 'de',
    events: {
      initialized: function() {
        const editor = this
        this.el.closest('form').addEventListener('submit', function(e) {
          //console.log(editor.$oel.val())
          // e.preventDefault()
        })
      }
    }
  })
});
// $('#clientNotevalue').froalaEditor('html.set', 'My custom paragraph.');
</script>

<!-- footer end -->
<!-- modal start -->
<div class="modal fade" id="add-note">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url(
              'assets/frontend/images/popup_crose_black_icon.webp'
          ); ?>" type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url(
              'assets/frontend/images/popup_crose_black_icon.png'
          ); ?>" type="image/png" class="popup-crose-black-icon">
          <img src="<?php echo base_url(
              'assets/frontend/images/popup_crose_black_icon.png'
          ); ?>" class="popup-crose-black-icon">
        </picture>
      </a>
      <div class="modal-body pt-40 mb-30 pl-40 pr-40">
        <p class="font-size-18 color333 fontfamily-medium mb-20 para_text">
          <?php echo $this->lang->line('add_notes_for_customer'); ?></p>
        <!--   <form id="frmNotes" method="post" action="<?php echo base_url(
            'merchant/addnotes'
        ); ?>"> -->
        <div class="form-group" id="txtnote_validate">
          <!-- <label class="inp display-b" style="height: 120px;"> -->
          <textarea type="text" name="txtnote" id="txtnote" placeholder="&nbsp;" class="form-control custom_scroll"
            style=""></textarea>
          <!-- </label> -->
          <label class="error" id="error_notess"></label>
        </div>

        <div class="text-center mt-30 display-b">
          <input type="hidden" id="booking_ids" name="booking_id" value="">
          <input type="hidden" id="booking_id_note" name="booking_id_note" value="">
          <button type="button" class="btn btn-large widthfit deleteNoteData" data-toggle="modal" data-target="#employee-delete-popup" style="display:none;text-transform: unset;">
            Notiz löschen
          </button>
          <button type="button" class=" btn btn-large widthfit" style="text-transform: unset;" id="saveNote_fromlist"><?php echo $this->lang->line(
                'save_notes'
            ); ?></button>
        </div>
        <!-- </form> -->
      </div>
    </div>
  </div>
</div>