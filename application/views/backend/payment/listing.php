<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
            
            
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Payment - Listing</h2>
                    </div>
                    
                    <div class="card">
						<div class="card-header">
							<?php $this->load->view('backend/common/alert'); ?>  
						</div>
                        <form method="GET">
                       <div class="card-padding card-header"><div class="row"> 
                        <div class="col-sm-4"><div class="input-group form-group">
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-calendar"></i>
                            </span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control date-picker" name="start_date" value="<?php echo isset($_GET['start_date'])?$_GET['start_date']:''; ?>" placeholder="select start date">
                            </div>
                        </div></div>
                        <div class="col-sm-4"><div class="input-group form-group">
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-calendar"></i>
                            </span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control date-picker" name="end_date" value="<?php echo isset($_GET['end_date'])?$_GET['end_date']:''; ?>" placeholder="select end date">
                            </div>

                        </div></div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-info btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-search"></i></button>
                        </div>
                       </div>
                    </div>
                </form>

                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-order="asc" data-type="numeric">ID</th>
                                   <!--  <th data-column-id="rowid" data-order="asc" data-type="numeric" data-visible="false"></th> -->
                                    <th data-column-id="first_name" data-order="asc" data-type="string">Salon Name</th>
                                    <th data-column-id="last_name" data-type="string">User Name</th>
                                    <th data-column-id="transection" data-type="string">Transaction Id</th>
                                    <th data-column-id="email">Created Date</th>
                                    <th data-column-id="age_group" data-type="string">Amount</th>
                                    <!-- <th data-column-id="mobile" data-type="string">Phone</th> -->
                                    <!-- <th data-column-id="status">Status</th> -->
									<!--  <th data-column-id="action">Action</th>-->
									<!-- <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-visible="false">Commands</th> -->
                                </tr>
                            </thead>
                            <tbody>
								<?php 
								   if($payment){
									   $i=1;
								     foreach($payment as $user){ ?>
										  
										  <tr>
											<td><?php echo $i; ?></td>
											<!-- <td><?php //echo $user->id; ?></td> -->
											<td><?php echo $user->business_name; ?></td>
											<td><?php echo $user->first_name.' '.$user->last_name; ?></td>
											<td><?php echo $user->transuction_id; ?></td>
											<td><?php echo $user->created_on; ?></td>
											<td><?php echo $user->amount.' €'; ?></td>
											
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
