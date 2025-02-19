<link href="<?php echo admin_assets(); ?>css/vinu_style.css" rel="stylesheet">
<?php $this->load->view('backend/common/header'); ?>
<?php $this->load->view('backend/common/sidebar'); ?>       
            
       <style>
         html, body{
         overflow:auto !important;
       }
       
       .idth{
           width: 70px;
           }
         .comandth{
             width:125px;
             }
      .pagination > .active > a,
      .bootgrid-header .search .form-control,
      .bootgrid-header .actions{
        z-index: 0 !important;
      }
      .modal{
        padding:30px 30px 24px 30px !important;
      }
      .modal a.close-modal {
        top: 6.5px !important;
        right: 6.5px !important;
        width: 20px !important;
        height: 20px !important;
      }
      .overflow-scroll{
        max-height: 60vh;
        overflow: auto;
      }
        .bootgrid-header .actions {
        z-index: 9 !important;
    } 
    .border-radius50{border-radius: 50%;}
    .mr-3{margin-right: 16px;}
</style>


            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2><?php if($segment3 =='marchant')
                                     echo "Merchant";
                                  else
                                     echo $segment3; ?> - Listing</h2>
                    </div>
                     <?php if(empty($_GET['short']))
                                $dis='';
                              else
                                $dis="display: none;";
                        ?>
                    <div class="card">
                        
                        <div class="card-header">
                            <?php $this->load->view('backend/common/alert'); ?>  
                        </div>
                      <div class="row" style="padding:0px 30px;">
                        <div class="col-md-4">
                        <select id="over_all_filter" class="selectpicker on_change_short">
                             <option value="">Search By Date</option>
                             <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="all")?'selected="selected"':''; } ?> value="all">Over All</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="day")?'selected="selected"':''; } ?> value="day">Today</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="current_week")?'selected="selected"':''; } ?> value="current_week">Current Week</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="current_month")?'selected="selected"':''; } ?> value="current_month">Current Month</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="last_month")?'selected="selected"':''; } ?> value="last_month">Last Month</option>
                             <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="last_sixmonth")?'selected="selected"':''; } ?> value="last_sixmonth">Last 6 Months</option>
                             <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="current_year")?'selected="selected"':''; } ?> value="current_year">Current Year</option>
                              <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="last_year")?'selected="selected"':''; } ?> value="last_year">Last Year</option>

                        </select>
                    </div>
                     <div class="col-md-6">
                        <div id="date_section" style="<?php echo $dis; ?>">
                    <div class="col-sm-5">
                         <!-- <p class="f-500 m-b-15 c-black">Start Date</p> -->
                        <div class="input-group form-group">
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-calendar"></i>
                            </span>
                            <div class="dtp-container fg-line">
                                <input type="text" style="padding-left: 10px;" id="start_date" class="form-control date-picker" name="start_date" value="<?php echo isset($_GET['start_date'])?$_GET['start_date']:''; ?>" placeholder="select start date">
                            </div>
                             <div style="position: absolute;" id="error_start" class="error"></div>
                        </div>

                    </div>
                        <div class="col-sm-5">
                           <!--  <p class="f-500 m-b-15 c-black">End Date</p> -->
                            <div class="input-group form-group">
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-calendar"></i>
                            </span>
                            <div class="dtp-container fg-line">
                                <input type="text" style="padding-left: 10px;" id="end_date" class="form-control date-picker" name="end_date" value="<?php echo isset($_GET['end_date'])?$_GET['end_date']:''; ?>" placeholder="select end date">
                            </div>
                                <div style="position: absolute;" id="error_end" class="error"></div>
                        </div></div>
                        <div class="col-sm-2">
                            <!-- <p class="f-500 c-black"></p> -->
                            <button type="button" id="click_search" class="btn btn-info btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-search"></i></button>
                        </div>
                    </div>
                     </div>

                     <div class="col-md-2">
                      <?php if(empty($_GET['code'])){ ?>
                        <span style="float: right;   margin-bottom: -25px; padding-right: 20px;position: relative;z-index: 5;"><a href="javascript:void(0)" data-id="excel" class="display-ib cursol-p m-1 export_filterreport"><img style="width: 35px; height: 35px;" class="width30" src="<?php echo base_url('assets/frontend/images/exel-icon.svg'); ?>"></a>
                        </span>
                    <?php } ?>
                     </div>    
                    </div>
                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-header-css-class="idth" data-order="asc" data-type="numeric">ID</th>
                                    <th data-column-id="rowid" data-order="asc" data-type="numeric" data-visible="false"></th>
                                    <th data-column-id="first_name"  data-header-css-class="nameth" data-order="asc" data-type="string" >First name</th>
                                    <th data-column-id="last_name"  data-header-css-class="nameth" data-type="string">Last name</th>
                                    <?php if($segment3 == 'marchant'){ ?>
                                    <th data-column-id="gender" data-type="string">
                                       Business Name
                                    </th>
                                    <?php } ?>
                                    <th data-column-id="email">Email</th>
                                    <th data-column-id="mobile" data-type="string">Registered</th>
                                    <?php if($segment3 == 'user'){ ?>
                                        <th data-formatter="createdby" data-sortable="false">Created By</th>
                                        <th data-column-id="temp_user" data-type="string" data-sortable="false" data-visible="false"></th>
                                    <?php } ?>
                                     <?php if($segment3 == 'marchant'){ ?>
                                    <th data-column-id="trial_set" data-formatter="trial_set" data-sortable="false">Set Trial</th>
                                    <th data-formatter="allow_ob" data-sortable="false">Allow OB</th>
                                    <?php } 
                                     if($segment3 == 'salesman'){ ?>
                                       <th data-column-id="salon_count" data-formatter="salon_count" data-sortable="false">Reg</th>
                                       <th data-column-id="salon_plan" data-formatter="salon_plan" data-sortable="false">Count</th>
                                       <th data-column-id="refcode" data-formatter="refcode" data-sortable="false">Refcode</th>
                                       <th data-column-id="ref_link" data-formatter="ref_link" data-sortable="false">Link</th>
                                     <?php }
                                    ?>
                                    <th data-column-id="status" data-formatter="status" data-sortable="false">Status</th>
                                    <!--  <th data-column-id="action">Action</th>-->
                                     <th data-column-id="role" data-type="string" data-sortable="false" data-visible="false"></th>
                                     <th data-column-id="subscription" data-type="string" data-sortable="false" data-visible="false"></th>
                                     <th data-column-id="sub_month" data-type="string" data-sortable="false" data-visible="false"></th>
                                     <th data-column-id="allow_online_booking" data-type="string" data-sortable="false" data-visible="false"></th>
                                    <th data-column-id="commands" data-header-css-class="comandth" data-formatter="commands" data-sortable="false">Action</th>
                                    <?php if($segment3 == 'marchant'){ ?>
                                    <th data-column-id="salon_login"
                                     data-formatter="salon_login" data-sortable="false">Salon</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // print_r($segment3);die;
                                  // print_r($users);echo '</pre>';die;
                                   if($users){
                                       $i=1;
                                     foreach($users as $user){ 
                                          $reg=date("d/m/Y", strtotime($user->created_on)); ?>
                                          <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $user->id; ?></td>
                                            <td><?php echo $user->first_name; ?></td>
                                            <td><?php echo $user->last_name; ?></td>
                                            <?php if($segment3 == 'marchant'){ ?>
                                            <td> <?php echo $user->business_name; ?>
                                            </td><?php } ?>
                                            <td title="<?php echo $user->email; ?>"><?php echo $user->email; ?></td>
                                            <!-- <td><?php //echo $user->age_group; ?></td> -->
                                            <td><?php echo $reg; ?></td>
                                             <?php if($segment3 == 'marchant'){ ?>
                                            <td></td>
                                            <td></td>
                                            <?php } ?>
                                            <?php if($segment3 == 'user'){ ?>
                                                <td><?php echo $user->temp_user; ?></td>
                                                <td><?php echo $user->temp_user; ?></td>
                                            <?php } 
                                             if($segment3 == 'salesman'){ ?>
                                                 <td><?php echo getMerchentCountBycode($user->salesman_code); ?></td>
                                                 <td><a href="<?php echo base_url(); ?>"><?php echo getMerchentCountBycode_plan($user->salesman_code); ?></a></td>
                                             <td><?php echo $user->salesman_code; ?></td>
                                             <td> </td>
                                             <?php } ?>
                                            <td>
                                                <?php if($user->status=='active'){ ?>
                                                   <button class="btn btn-success waves-effect" disabled="disabled">Active</button> 
                                                <?php   }else{  ?>
                                                   <button class="btn btn-danger waves-effect" disabled="disabled">Inactive</button>    
                                                <?php   } ?>
                                            </td>
                                            <td><?php echo $role; ?></td>
                                            <td><?php echo $user->subscription_status; ?></td>
                                            <td><?php echo $user->extra_trial_month; ?></td>
                                            <td><?php echo $user->allow_online_booking; ?></td>
                                          </tr>
                                          
                                    <?php $i++; }
                                   } 
                                
                                ?>
                                
                               
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" id="list_type" value="<?php echo $role; ?>">
            </section>
       
  <div id="salon_list_popup" class="modal">
    <div class="overflow-scroll">
         <table class="table">
             <th>##</th>
             <th>Salon Name</th>
             <th>Register Date</th>
             <tbody id="all_salon_listing">
                 
             </tbody>
         </table>
     </div>
  </div>



 

<!-- Link to open the modal -->
 <!-- modal on load -->
            <!---------------------------------- Salon view client profile--------------------------------------------------------------------------------->
<div class="modal fade" id="profile-client-view-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
    <div class="modal-content" id="clientViewProfileHtml">            
    
    </div>
  </div>
</div>

<div class="modal fade" id="profile-user-view-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
    <div class="modal-content" id="userViewProfileHtml">            
    
    </div>
  </div>
</div>

<?php 
$sm_sch='';
    if(!empty($_GET['code']))
    {
        if(!empty($_GET['paid']))
            $sm_sch="&code=".$_GET['code']."&paid=".$_GET['paid'];
        else
            $sm_sch="&code=".$_GET['code'];
    }
?>
        
  <?php $this->load->view('backend/common/footer'); ?>
  <!-- <script src='<?php echo base_url(); ?>assets/frontend/js/sweetalert2.js'></script> -->
<!-- jQuery Modal -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
 Data Table -->
        <script type="text/javascript">
            $(document).ready(function(){
                
                //~ //Basic Example
                //~ $("#data-table-basic").bootgrid({
                    //~ css: {
                        //~ icon: 'zmdi icon',
                        //~ iconColumns: 'zmdi-view-module',
                        //~ iconDown: 'zmdi-expand-more',
                        //~ iconRefresh: 'zmdi-refresh',
                        //~ iconUp: 'zmdi-expand-less'
                    //~ },
                //~ });
                
                //~ //Selection
                //~ $("#data-table-selection").bootgrid({
                    //~ css: {
                        //~ icon: 'zmdi icon',
                        //~ iconColumns: 'zmdi-view-module',
                        //~ iconDown: 'zmdi-expand-more',
                        //~ iconRefresh: 'zmdi-refresh',
                        //~ iconUp: 'zmdi-expand-less'
                    //~ },
                    //~ selection: true,
                    //~ multiSelect: true,
                    //~ rowSelect: true,
                    //~ keepSelection: true
                //~ });
                
                //Command Buttons
                $("#data-table-command").bootgrid({
                    caseSensitive: false,
                    columnSelection: false,
                    css: {
                        icon: 'zmdi icon',
                        iconColumns: 'zmdi-view-module',
                        iconDown: 'zmdi-expand-more',
                        iconRefresh: 'zmdi-refresh',
                        iconUp: 'zmdi-expand-less'
                    },
                    formatters: {
                       "status": function(column, row) {
                             //console.log(row);
                             //console.log(row.status);
                            if($.trim(row.status) == 'Active'){
                            return "<button class=\"btn btn-success waves-effect\" onclick=\"EditStatus(" + row.rowid + ",'/backend/user/status/inactive/"+row.role+"/')\" >"+ row.status +"</button>";
                            }
                            else{
                             return "<button class=\"btn btn-danger waves-effect\" onclick=\"EditStatus(" + row.rowid + ",'/backend/user/status/active/"+row.role+"/')\" >"+ row.status +"</button>";   
                            }
                        },
                        "trial_set": function(column, row) {
                          
                            // console.log("triale ---------",row);
                            if(row.subscription=='trial' || row.subscription=='active'){
                                var html='';
                                for(var i=0; i < 14; i++){
                                    if(row.sub_month == i || row.sub_month=="240")
                                     var sel ="selected='seleted'";
                                     else
                                      var sel='';    
                                    if (i == 13) {
                                        html+=`'<option ${sel} value="240">Forever</option>'`;
                                    } else if (i == 0){
                                        html+=`'<option ${sel} value="${i}">1 Day</option>'`;
                                    } else{
                                        html+=`'<option ${sel} value="${i}">${i} Month</option>'`;
                                    }

                                }
                                
                             return '<select data-id="'+row.rowid+'" class="form-control click_month">'+html+'</select>';
                                                        }
                            else{
                                return '';
                            }   
                        },
                        "createdby": function(column, row) {
                            console.log(row);
                            return row.temp_user == '1' ? 'Salon' : 'App';
                        },
                        "allow_ob": function(column, row) {
                            return '<input type="checkbox" data-id="' + row.rowid + '" class="allow_ob"'+(row.allow_online_booking == 'true' ? 'checked' : '')+'>';
                        },
                         "salon_count":function(column, row) {
                           var html="<span style=\"color:#00bcd4\" class=\"waves-effect\" onclick=\"get_salon_list(" + row.id + ",'"+base_url+"backend/user/getsalon_list/"+row.refcode+"')\" >"+row.salon_count+"</span>";
                             return html;
                        },
                        "salon_plan":function(column, row) {
                           var html="<span style=\"color:#00bcd4\" class=\"waves-effect\" onclick=\"get_salon_list(" + row.id + ",'"+base_url+"backend/user/getsalon_list/"+row.refcode+"/plan')\" >"+row.salon_plan+"</span>";
                             return html;
                        },
                        "ref_link": function(column, row) {
                             //console.log(row);
                            var html="<button id='"+row.id+"_btntext' class=\"btn btn-primary waves-effect\" onclick=\"copy_reflink(" + row.id + ",'"+base_url+"merchant/registration?r="+row.refcode+"')\" >Copy Link</button>";
                             return html;
                          
                           
                        },
                        "commands": function(column, row) {
                            // console.log(row);
                            var html="<button type=\"button\" class=\"btn btn-icon command-delete\" onclick=\"DeleteUser(" + row.rowid + ",'/backend/user/delete/"+row.role+"/')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
                            //alert(row.access);
                            
                            if(row.role=='marchant'){
                                //alert(row.role);
                                html=html+"<button type=\"button\" title=\"View salons\" class=\"btn btn-icon command-delete\" onclick=\"viewRowsalon(" + row.rowid + ",'"+base_url+"backend/user/salon_detail')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-eye zmdi-hc-fw\"></span></button>";
                               }
                            if(row.role=='user'){
                                //alert(row.role);
                                html=html+"<button type=\"button\" title=\"View user\" class=\"btn btn-icon command-delete\" onclick=\"viewRowUser(" + row.rowid + ",'"+base_url+"backend/user/user_detail')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-eye zmdi-hc-fw\"></span></button>";
                               }   
                            if(row.role=='salesman'){
                                //alert(row.role);
                                html=html+"<button type=\"button\" title=\"View salons\" class=\"btn btn-icon command-delete\" onclick=\"viewRow('backend/user/listing/marchant?code=" + row.refcode + "')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-eye zmdi-hc-fw\"></span></button>";
                               }
                            return html; 
                        },
                        "salon_login": function(column, row) {
                            // console.log(row);
                            return "<button class=\"btn btn-primary waves-effect\" onclick=\"login_tosalon(" + row.rowid + ",'/backend/user/login_tosalon/')\" >Login</button>";
                            
                            
                        }
                    }
                });

                $(document).on('change', '.allow_ob', function() {
                    var mid = $(this).attr('data-id');
                    if(status=='LOGGED_OUT'){
                        console.log('STATUS='+status);
                        console.log(base_url)
                        window.location.href = base_url;
                        $(window.location)[0].replace(base_url);
                        $(location).attr('href',base_url);
                    }
                    console.log('STATUS='+status);
                    $.ajax({
                        url: base_url+"backend/user/update_online_booking",
                        type: "POST",
                        data:{val: $(this).is(":checked"), mid},
                        success: function (response) {
                            if(response){
                                swal({   
                                        title: "Success",   
                                        text: "Successfully updated online booking",   
                                        type: "success",   
                                        confirmButtonText: "Ok",   
                                        closeOnConfirm: false 
                                    })
                            // swal("Success !", "Successfully updated trial period")
                            }

                        }                      
                    });
                });

                $(document).on('change','.click_month', function(){
                    var month =$(this).val();
                    var mid = $(this).attr('data-id');
                    var status = localStorage.getItem('STATUS');
                    if(status=='LOGGED_OUT'){
                        console.log('STATUS='+status);
                        console.log(base_url)
                        window.location.href = base_url;
                        $(window.location)[0].replace(base_url);
                        $(location).attr('href',base_url);
                    }
                    console.log('STATUS='+status);
                    $.ajax({
                        url: base_url+"backend/user/update_trial",
                        type: "POST",
                        data:{'month' : month , 'mid' : mid},
                        success: function (response) {
                            if(response){
                                swal({   
                                        title: "Success",   
                                        text: "Successfully updated trial period",   
                                        type: "success",   
                                        confirmButtonText: "Ok",   
                                        closeOnConfirm: false 
                                    })
                            // swal("Success !", "Successfully updated trial period")
                            }

                        }                      
                    });
                });


                //$(document).on('click','.export_filterreport',function(){

                  /*$(".dropdown-menu").on('click', 'li a', function(){
                  var tt = $(".btn:first-child").text($(this).text());
                  var yy = $(".btn:first-child").val($(this).text());
                  console.log(yy);
                  //alert(tt+" "+yy);
                });*/
            $(document).on('click','.export_filterreport',function(){

              var short = $('.on_change_short').val();
               var start ="";
               var end ="";
               if(short ==""){
                 var start = $('#start_date').val();
                 var end =  $('#end_date').val();
               }
              //var search = $("input[name=search-field]").val();
              var search = $(".search-field").val();
              var list_type = $("#list_type").val();
                
                window.location.href = base_url+'backend/user/list_export/excel?search='+search+'&access='+list_type+'&short='+short+'&start_date='+start+'&end_date='+end;
              });


            });

            $(document).on('change','.on_change_short', function(){
                var limit=$(this).val();
                if(limit !=''){  
                 var url=base_url+"backend/user/listing/<?php echo $role ?>?short="+limit+"<?php echo $sm_sch; ?>";
                  window.location.href = url; 
                  $('#date_section').hide();
                }
                else{
                    $('#date_section').show();
                }
                

               });
            $(document).on('click','#click_search', function(){
                    var start = $('#start_date').val();
                    var end =  $('#end_date').val();
                    var tokan =true;
                    if(start ==""){
                        $('#error_start').html('select start date');
                        tokan = false;
                    }
                    else
                        $('#error_start').html('');
                    if(end ==""){
                        $('#error_end').html('select end date');
                        tokan = false;
                    }
                    else
                        $('#error_end').html('');
                    if(tokan == true){
                        var url=base_url+"backend/user/listing/<?php echo $role ?>?start_date="+start+"&end_date="+end+"<?php echo $sm_sch ?>";
                        window.location.href = url;
                    }
               });

            $(document).on('change','.on_change_short_c', function(){
                var short=$(".on_change_short_c option:selected").val();
                var idd = $("#salon_idds").val();

                    if(short ==""){
                        $('#date_section_c').show();
                    }
                    else{
                        $('#date_section_c').hide();
                        
                        get_clientBookingList();
                       getCustomerList();
                        //viewRowsalon_filter(idd,base_url+'backend/user/salon_detail_filter');
                    }

                   });
                   
             $(document).on('change','.on_change_short_u', function(){
                var short=$(".on_change_short_u option:selected").val();
                var idd = $("#salon_idds").val();

                    if(short ==""){
                        $('#date_section_u').show();
                    }
                    else{
                        $('#date_section_u').hide();
                        
                        get_userBookingList();
                       //getCustomerList();
                        //viewRowsalon_filter(idd,base_url+'backend/user/salon_detail_filter');
                    }

                   });
                   
                   
             $(document).on('change','.searchNewFilter', function(){
                var short=$(".on_change_short_c option:selected").val();
                var idd = $("#salon_idds").val();

                    if(short ==""){
                        $('#date_section_c').show();
                    }
                    else{
                        $('#date_section_c').hide();
                        viewRowsalon_filter(idd,base_url+'backend/user/salon_detail_filter');
                    }

                   });  
                   
            $(document).on('change','.searchNewFilteruser', function(){
                var short=$(".on_change_short_c option:selected").val();
                var idd = $("#salon_idds").val();

                    if(short ==""){
                        $('#date_section_c').show();
                    }
                    else{
                        $('#date_section_c').hide();
                        viewRowuser_filter(idd,base_url+'backend/user/user_detail_filter');
                    }

                   });         

            $(document).on('click','#click_search_c', function(){
                var start = $('#start_date_c').val();
                var end =  $('#end_date_c').val();
                var idd = $("#salon_idds").val();
                var tokan =true;
                if(start ==""){
                    $('#error_start_c').html('select start date');
                    tokan = false;
                }
                else
                    $('#error_start_c').html('');
                if(end ==""){
                    $('#error_end_c').html('select end date');
                    tokan = false;
                }
                else
                    $('#error_end_c').html('');
                if(tokan == true){
                    viewRowsalon_filter(idd,base_url+'backend/user/salon_detail_filter');
                    /*get_clientBookingList();
                    getCustomerList();*/
                }
           });

            function viewRowsalon(id,url){
                //alert(id+" "+url);
                //var short=$(".on_change_short_c option:selected").val();
                //alert(short);
                //$('#profile-client-view-modal').modal('show');
                //return false;
                var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
                    $.ajax({
                            url: url,
                            type: "POST",
                            data:{ id : id},
                            success: function (data) {
                             var obj = jQuery.parseJSON( data );
                                if(obj.success == 1){  
                                   $("#clientViewProfileHtml").html(obj.html);
                                    $("#profile-client-view-modal").modal('show');
                                    $('#profile-client-view-modal').modal({backdrop: 'static', keyboard: false});  
                                    $(".modal").css('overflow-y','auto');
                                    get_clientBookingList();
                                    getCustomerList();
                                    $('.date-picker').datetimepicker({
                                        format: 'DD/MM/YYYY'
                                        });
                                           
                                }
                            }
                                
                        }); 
                    
                    
                }
                
                function viewRowUser(id,url){
                //alert(id+" "+url);
                //var short=$(".on_change_short_c option:selected").val();
                //alert(short);
                //$('#profile-client-view-modal').modal('show');
                //return false;
                var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
                    $.ajax({
                            url: url,
                            type: "POST",
                            data:{ id : id},
                            success: function (data) {
                             var obj = jQuery.parseJSON( data );
                                if(obj.success == 1){  
                                   $("#userViewProfileHtml").html(obj.html);
                                    $("#profile-user-view-modal").modal('show');
                                    $('#profile-user-view-modal').modal({backdrop: 'static', keyboard: false});  
                                    $(".modal").css('overflow-y','auto');
                                    get_userBookingList();
                                    //getCustomerList();
                                    $('.date-picker').datetimepicker({
                                        format: 'DD/MM/YYYY'
                                        });
                                           
                                }
                            }
                                
                        }); 
                    
                    
                } 
                

                function viewRowsalon_filter(id,url){
                var short=$(".searchNewFilter option:selected").val();
                var start = $("#start_date_c").val();
                var end = $("#end_date_c").val();
                var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
                    $.ajax({
                            url: url,
                            type: "POST",
                            data:{ id : id,short : short, start_date : start, end_date: end},
                            success: function (data) {
                             var obj = jQuery.parseJSON( data );
                                if(obj.success == 1){ 
                                    if(obj.html.totalrevenew != null)
                                        $("#tot_revenue").html(obj.html.totalrevenew+' €');
                                    else
                                        $("#tot_revenue").html('0 €');
                                    
                                    $("#tot_book").html(obj.html.totalbook);
                                    $("#tot_comp").html(obj.html.totalcomplete);
                                    $("#tot_upcom").html(obj.html.totalupcoming);
                                    $("#tot_canc").html(obj.html.totalcanceled);
                                    $("#tot_noshow").html(obj.html.totalnoshow);
                                   // get_clientBookingList();
                                  //  getCustomerList();
                             
                                }
                            }
                                
                        }); 
                    
                    
                }
                
                
               function viewRowuser_filter(id,url){
                var short=$(".searchNewFilteruser option:selected").val();
                var start = $("#start_date_c").val();
                var end = $("#end_date_c").val();
                var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
                    $.ajax({
                            url: url,
                            type: "POST",
                            data:{ id : id,short : short, start_date : start, end_date: end},
                            success: function (data) {
                             var obj = jQuery.parseJSON( data );
                                if(obj.success == 1){ 
                                    if(obj.html.totalrevenew != null)
                                        $("#tot_revenue").html(obj.html.totalrevenew+' €');
                                    else
                                        $("#tot_revenue").html('0 €');
                                    
                                    $("#tot_book").html(obj.html.totalbook);
                                    $("#tot_comp").html(obj.html.totalcomplete);
                                    $("#tot_upcom").html(obj.html.totalupcoming);
                                    $("#tot_canc").html(obj.html.totalcanceled);
                                    $("#tot_noshow").html(obj.html.totalnoshow);
                                   // get_userBookingList();
                                  //  getCustomerList();
                             
                                }
                            }
                                
                        }); 
                    
                    
                }
                
              
                 function get_clientBookingList(url=''){
  
                        var lim=10;
                        var uid=$('#listingTabl').attr('data-uid');
                        var sht=$('#short_by_field').val();
                        var short=$(".on_change_short_c option:selected").val();
                        var start = $("#start_date_c").val();
                        var end = $("#end_date_c").val();
                    if(url==''){
                            url=base_url+"backend/user/client_booking_list/"+uid;
                            }
                        
                        //loading();
                        var status = localStorage.getItem('STATUS');
                        if(status=='LOGGED_OUT'){
                            console.log('STATUS='+status);
                            console.log(base_url)
                            window.location.href = base_url;
                            $(window.location)[0].replace(base_url);
                            $(location).attr('href',base_url);
                        }
			            console.log('STATUS='+status);
                        $.post(url,{limit:lim,order:sht,short:short,start_date:start,end_date:end},function( data ) {
                         
                            
                            var obj = jQuery.parseJSON( data );
                            if(obj.success=='1'){
                                //var time = $("#selctTimeFilter").val();
                               // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
                                //console.log(obj.html);
                                $("#all_listing").html(obj.html);
                                $("#pagination_clientBooking").html(obj.pagination);
                                


                            }
                           // unloading();    
                        });
                        
                        }
                     
                     function get_userBookingList(url=''){
  
                        var lim=10;
                        var uid=$('#listingTabl').attr('data-uid');
                        var sht=$('#short_by_field').val();
                        var short=$(".on_change_short_u option:selected").val();
                        var start = $("#start_date_u").val();
                        var end = $("#end_date_u").val();
                    if(url==''){
                            url=base_url+"backend/user/user_booking_list/"+uid;
                            }
                        
                        //loading();
                        var status = localStorage.getItem('STATUS');
                        if(status=='LOGGED_OUT'){
                            console.log('STATUS='+status);
                            console.log(base_url)
                            window.location.href = base_url;
                            $(window.location)[0].replace(base_url);
                            $(location).attr('href',base_url);
                        }
			            console.log('STATUS='+status);
                        $.post(url,{limit:lim,order:sht,short:short,start_date:start,end_date:end},function( data ) {
                         
                            
                            var obj = jQuery.parseJSON( data );
                            if(obj.success=='1'){
                                //var time = $("#selctTimeFilter").val();
                               // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
                                //console.log(obj.html);
                                $("#all_listing").html(obj.html);
                                $("#pagination_clientBooking").html(obj.pagination);
                                
                                
                            }
                           // unloading();    
                        });
                        
                        }   
                        

                    $(document).on('click','.shorting',function(){
                         var sh =$(this).attr('data-short'); 
                         var id = $(this).attr('id');

                         if(sh == 'desc'){
                           $("#short_by_field").val(id+" asc");
                           $(this).attr( 'data-short','1000');
                         }
                         else{ 
                           $("#short_by_field").val(id+" desc");
                           $(this).attr( 'data-short','1000');
                            }
                         get_clientBookingList();    

                      });
                      
                   
                    $(document).on('click','.shorting_user',function(){
                         var sh =$(this).attr('data-short'); 
                         var id = $(this).attr('id');

                         if(sh == 'desc'){
                           $("#short_by_field").val(id+" asc");
                           $(this).attr( 'data-short','asc');
                         }
                         else{ 
                           $("#short_by_field").val(id+" desc");
                           $(this).attr( 'data-short','desc');
                            }
                         get_userBookingList();    

                      });   
            
                $(document).on('click',"#pagination_clientBooking .page-item a",function(){
                          var url=$(this).attr('href');
                          
                          if(url!=undefined){
                            get_clientBookingList(url);
                            }
                            window.scrollTo(0, 350); 
                            return false;
                          });


                      function getCustomerList(url=''){

                        var lim=10;
                        var order=$("#orderby").val();
                        var shortby=$("#shortby").val();
                        var sch="";
                        var uid=$('#listingTabl').attr('data-uid');
                        var short=$(".on_change_short_c option:selected").val();
                        var start = $("#start_date_c").val();
                        var end = $("#end_date_c").val();
                        //var uid=$('#listingTabl').attr('data-uid');
                        if(url==''){
                          url=base_url+"backend/user/customer_list/"+uid;
                         }
                         var status = localStorage.getItem('STATUS');
                        if(status=='LOGGED_OUT'){
                            console.log('STATUS='+status);
                            console.log(base_url)
                            window.location.href = base_url;
                            $(window.location)[0].replace(base_url);
                            $(location).attr('href',base_url);
                        }
			            console.log('STATUS='+status);
                        $.post(url,{limit:lim,search:sch,orderby:order,shortby:shortby,short:short,start_date:start,end_date:end},function( data ) {
                         
                            
                          var obj = jQuery.parseJSON( data );
                                if(obj.success=='1'){
                            $("#allcust_listing").html(obj.html);
                            $("#pagination").html(obj.pagination);
                            
                          }
                         });
                        
                        }


                         $(document).on('click',"#pagination .page-item a",function(){
                          var url=$(this).attr('href');
                          
                          if(url!=undefined){
                            getCustomerList(url);
                            }
                             window.scrollTo(0, 350);
                          //$("#listingTabl").focus();  
                            return false; 
                          
                          });

                          $(document).on('click','.shorting_c',function(){
      
                              $("#orderby_c").val($(this).attr('id'));
                              $("#shortby_c").val($(this).attr('data-short'));
                         
                               if($(this).attr('data-short')=='asc'){
                                  $(this).attr('data-short','desc');
                                }
                              else{
                                  $(this).attr('data-short','asc');
                                  } 
                            getCustomerList();    

                          });

                   

                function changeSalonstatus(id){
                    //var id = $(this).att('data-uid');
                        var status = $('#salon_status_change').val();
                        var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
                        $.ajax({
                        url: base_url+"backend/user/update_status_byadmin",
                        type: "POST",
                        data:{'id' : id , 'status' : status},
                        success: function (response) {
                            if(response){
                                if(status == 'active'){
                                 $("#change_salon_status").html('Inactive');
                                 $("#salon_status_change").val('inactive');
                                }
                                else{
                                 $("#change_salon_status").html('Active');
                                 $("#salon_status_change").val('active');   
                                }
                                $("#dropdownMenuButton11").trigger('click');

                            }

                         }
                         
                    });
                }
                
                  function changeUserstatus(id){
                    //var id = $(this).att('data-uid');
                        var status = $('#user_status_change').val();
                        var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
                        $.ajax({
                        url: base_url+"backend/user/update_status_byadmin",
                        type: "POST",
                        data:{'id' : id , 'status' : status},
                        success: function (response) {
                            if(response){
                                if(status == 'active'){
                                 $("#change_user_status").html('Inactive');
                                 $("#user_status_change").val('inactive');
                                }
                                else{
                                 $("#change_user_status").html('Active');
                                 $("#user_status_change").val('active');   
                                }
                                $("#dropdownMenuButton11").trigger('click');

                            }

                         }
                         
                    });
                }
                
                function showDiv(id){
                    alert(id);
                }
               
                 $(document).on('click','.nav-link', function() {
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');
                 });
        </script>

        
