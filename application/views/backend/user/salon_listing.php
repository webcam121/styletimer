<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
            
            
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Salon - Listing from dashboard</h2>
                    </div>
                    
                    <div class="card">
						<?php if(empty($_GET['short']))
                                $dis='';
                              else
                                $dis="display: none;";
                         ?>
						<div class="card-header">
							<?php $this->load->view('backend/common/alert'); ?>  
						</div>
                      <div class="col-md-12 p-b-10">
                        <?php if(!empty($_GET['short']))
                            $get_data = "?short=".$_GET['short'];
                          else if(!empty($_GET['start_date']) && !empty($_GET['end_date']))
                            $get_data= "?start_date=".$_GET['start_date']."&end_date=".$_GET['end_date'];
                          else
                            $get_data = "?short=30";
                         ?>
                        <div class="col-sm-2 m-b-15">
                         <select class="selectpicker on_change_short">
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
                      <div class="col-sm-2 m-b-15">
                         <select class="selectpicker on_change_type">
                            <option <?php if(!empty($_GET['type'])){ echo ($_GET['type'] =="")?'selected="selected"':''; } ?> value="">All</option>
                            <option <?php if(!empty($_GET['type'])){ echo ($_GET['type'] =="Hair")?'selected="selected"':''; } ?> value="Hair">Hair</option>
                            <option <?php if(!empty($_GET['type'])){ echo ($_GET['type'] =="Salon")?'selected="selected"':''; } ?> value="Salon">Salon</option>
                            <option <?php if(!empty($_GET['type'])){ echo ($_GET['type'] =="Day Spa")?'selected="selected"':''; } ?> value="Day Spa">Day Spa</option>
                        </select>
                      </div>
                      <div class="col-sm-6 m-b-15"></div>
                      <div class="col-sm-2 m-b-15 text-right"><a href="<?php echo base_url('backend/dashboard').$get_data; ?>"><button class="btn btn-info waves-effect">Go Back</button></a></div>

                    </div>
                    <div class="col-md-12" id="date_section" style="<?php echo $dis; ?>">
                        
                    <div class="col-sm-3">
                         <p class="f-500 m-b-15 c-black">Start Date</p>
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
                        <div class="col-sm-3">
                            <p class="f-500 m-b-15 c-black">End Date</p>
                            <div class="input-group form-group">
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-calendar"></i>
                            </span>
                            <div class="dtp-container fg-line">
                                <input type="text" style="padding-left: 10px;" id="end_date" class="form-control date-picker" name="end_date" value="<?php echo isset($_GET['end_date'])?$_GET['end_date']:''; ?>" placeholder="select end date">
                            </div>
                                <div style="position: absolute;" id="error_end" class="error"></div>
                        </div></div>
                        <div class="col-sm-3">
                            <p class="f-500 m-b-25 c-black"></p>
                            <button type="button" id="click_search" class="btn btn-info btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-search"></i></button>
                        </div>
                    
                    </div>
                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-order="asc" data-type="numeric">ID</th>
                                    <th data-column-id="rowid" data-order="asc" data-type="numeric" data-visible="false"></th>
                                    <th data-column-id="first_name" data-order="asc" data-type="string" >Name</th>
                                     <th data-column-id="created" data-type="string">Created On</th>
                                     <th data-column-id="type" data-type="string">Type</th>
                                    <th class="text-center" data-column-id="t_booking">Total Booking</th>
                                    <th class="text-center" data-column-id="cmp_booking">Completed</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
								<?php 
								   
								   if($users){
									   $i=1;
								     foreach($users as $user){ ?>
										  
										  <tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $user->id; ?></td>
											<td><?php echo $user->business_name; ?></td>
											<td><?php echo $user->created_on; ?></td>
											<td><?php echo $user->business_type; ?></td>
											<td class="text-center"><?php echo $user->total_booking; ?></td>
                                            <td class="text-center"><?php echo $user->completed_booking; ?></td>
											
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
/*
                 $(document).on('change','.on_change_short', function(){
                        var type=$(".on_change_type option:selected").val();
                        var limit=$(this).val();
                        var url=base_url+"backend/dashboard/salon_listing?short="+limit+"&type="+type;
                            window.location.href = url; 
                       });*/
                 $(document).on('change','.on_change_type', function(){
                        var short=$(".on_change_short option:selected").val();
                        var limit=$(this).val();
                          if(short !=''){  
                            var url=base_url+"backend/dashboard/salon_listing?short="+short+"&type="+limit;
                            }
                            else{
                                 var start = $('#start_date').val();
                                 var end =  $('#end_date').val();
                                 var url=base_url+"backend/dashboard/salon_listing?start_date="+start+"&end_date="+end+"&type="+limit;
                            }
                            window.location.href = url;
                       });


                 $(document).on('change','.on_change_short', function(){
                     var limit=$(this).val();
                    
                    if(limit !=''){  
                       var type=$(".on_change_type option:selected").val();
                        var url=base_url+"backend/dashboard/salon_listing?short="+limit+"&type="+type;
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
                            var type = $('.on_change_type option:selected').val();
                            var url=base_url+"backend/dashboard/salon_listing?start_date="+start+"&end_date="+end+"&type="+type;
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
                       "status": function(column, row) {
                             //console.log(row);
                         },
                        "commands": function(column, row) {
							
                        }
                    }
                });
            });
        </script>
