<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
            
            
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Booking - Listing</h2>
                    </div>
                    
                    <div class="card">
						
						<div class="card-header">
							<?php $this->load->view('backend/common/alert'); ?>  
						</div>

                         <?php if(empty($_GET['short']))
                                $dis='';
                              else
                                $dis="display: none;";
                        ?>

                        <div class="row">
                        <div class="col-md-4" style="padding-left: 40px;">
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
                        <span style="float: right;   margin-bottom: -25px; padding-right: 20px;position: relative;z-index: 5;"><a href="javascript:void(0)" data-id="excel" class="display-ib cursol-p m-1 export_filterreport"><img style="width: 35px; height: 35px;" class="width30" src="<?php echo base_url('assets/frontend/images/exel-icon.svg'); ?>"></a>
                        </span>
                     </div>    
                    </div>
                            
                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-order="asc" data-type="numeric">ID</th>
                                    <th data-column-id="rowid" data-order="asc" data-type="numeric" data-visible="false"></th>
                                    <th data-column-id="first_name" data-order="asc" data-type="string">Salon Name</th>
                                    <th data-column-id="last_name" data-type="string">Customer Name</th>
                                    <th data-column-id="gender" data-type="string">Booking Date</th>
                                    <th data-column-id="email">Created Date</th>
                                    <th data-column-id="age_group" data-type="string">Amount</th>
                                    <!-- <th data-column-id="mobile" data-type="string">Phone</th> -->
                                    <th data-column-id="status">Status</th>
									<!--  <th data-column-id="action">Action</th>-->
									<!-- <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-visible="false">Commands</th> -->
                                </tr>
                            </thead>
                            <tbody>
								<?php 
								   
								   if($booking){
									   $i=1;
								     foreach($booking as $user){ ?>
										  
										  <tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $user->id; ?></td>
											<td><?php echo $user->business_name; ?></td>
											<td>
                                                <?php 
                                                if($user->booking_type =="guest")
                                                    echo $user->fullname;
                                                else
                                                    echo $user->first_name.' '.$user->last_name; ?>
                                                    
                                                </td>
											<td><?php echo $user->booking_time; ?></td>
											<td><?php echo $user->created_on; ?></td>
											<td><?php echo $user->total_price.' €'; ?></td>
											<td><?php echo $user->status; ?></td>
											<!-- <td>
												<?php if($user->status=='active'){ ?>
												   <button class="btn btn-success waves-effect" disabled="disabled">Active</button>	
												<?php	}else{  ?>
												   <button class="btn btn-danger waves-effect" disabled="disabled">Inactive</button>	
												<?php	} ?>
											</td> -->
											
										  </tr>
										  
									<?php $i++; }
								   } 
								
								?>
								
                               
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
       
  
        
  <?php $this->load->view('backend/common/footer'); ?>
 <!-- Data Table -->
        <script type="text/javascript">
            $(document).ready(function(){
                 $(document).on('click','.export_filterreport',function(){
                        //var search = $("input[name=search-field]").val();
                   var search = $(".search-field").val();
                   var short = $('.on_change_short').val();
                   var start ="";
                   var end ="";
                   if(short ==""){
                     var start = $('#start_date').val();
                     var end =  $('#end_date').val();
                   }

                  window.location.href = base_url+'backend/booking/list_export/excel?search='+search+'&short='+short+'&start_date='+start+'&end_date='+end;
                  });


                 $(document).on('change','.on_change_short', function(){
                var limit=$(this).val();
                if(limit !=''){  
                 var url=base_url+"backend/booking/listing?short="+limit;
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
                        var url=base_url+"backend/booking/listing?start_date="+start+"&end_date="+end;
                        window.location.href = url;
                    }
               });
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
                        "commands": function(column, row) {
							 //console.log(column);
							 console.log(row);
                            return "<button type=\"button\" class=\"btn btn-icon command-edit\" onclick=\"EditRow(" + row.rowid + ",'/backend/user/make/')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
                                "<button type=\"button\" class=\"btn btn-icon command-delete\" onclick=\"DeleteRow(" + row.rowid + ",'/backend/user/make/')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
                        }
                    }
                });
            });
        </script>
