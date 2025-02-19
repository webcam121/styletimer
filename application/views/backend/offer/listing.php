<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
            
            
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Category - Listing</h2>
                    </div>
                    
                    <div class="card">
						
						<div class="card-header">
							<?php $this->load->view('backend/common/alert'); ?>  
						</div>
                        
                        
                        
                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-order="asc" data-type="numeric">ID</th>
                                    <th data-column-id="rowid" data-order="asc" data-type="numeric" data-visible="false"></th>
                                    <th data-column-id="name" data-order="asc" data-type="string">Category</th>
                                    <th data-column-id="status">Offer Text</th>
									<th data-column-id="commands" data-formatter="commands" data-sortable="false">Action</th>
									 
                                </tr>
                            </thead>
                            <tbody>
								<?php 
								   
								   if($category){
									   $i=1;
								     foreach($category as $cat){
										 if(!empty($cat->image)){
											 $img=base_url('assets/uploads/category/'.$cat->id.'/'.$cat->image);
											 }else $img=base_url('assets/backend/img/noimage.png'); ?>
										  
										  <tr>
											<td><?php echo $i; ?></td>  
											<td><?php echo $cat->id; ?></td>
											<td><?php echo $cat->category_name; ?></td>
											<td><?php echo $cat->offer_text; ?></td>
											
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
                            return "<button type=\"button\" class=\"btn btn-icon command-edit\" onclick=\"EditRow(" + row.rowid + ",'backend/offers/description/')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-edit\"></span></button> ";
                        }
                      
                    }
                });
            });
        </script>
