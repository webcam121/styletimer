<?php $this->load->view('backend/common/header'); ?>
<?php $this->load->view('backend/common/sidebar'); ?>       

<style type="text/css">
    .addbtn-p.waves-effect {
    position: absolute;
    right: 40px;
    top: 68px;
    height: 35px;
    line-height: 25px;
    z-index: 1;
    }
</style>            
            
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Category - Listing</h2>
                    </div>
                    
                    <div class="card ">
						
						<div class="card-header">
							<?php $this->load->view('backend/common/alert'); ?>  
						</div>
                        
                        
                            <a href="<?php echo base_url('backend/category/make'); ?>" class="btn btn-primary waves-effect addbtn-p">ADD</a>
                        
                        
                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-order="asc" data-type="numeric">ID</th>
                                    <th data-column-id="rowid" data-order="asc" data-type="numeric" data-visible="false"></th>
                                    <th data-column-id="name" data-order="asc" data-type="string">name</th>
                                    <th data-column-id="parent_category" data-type="string">Parent category</th>
                                    <th data-column-id="image"  data-formatter="image" data-sortable="false">Image</th>
                                    <th data-column-id="imagesrc" data-type="string" data-visible="false">Imagesrc</th>
                                    <th data-column-id="status">Status</th>
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
											<td><?php if(!empty($cat->parent_id) && $cat->parent_id!=0){
												 foreach($category as $parentcate){
                                                       if($parentcate->id==$cat->parent_id) echo $parentcate->category_name;													 
												  
												  }
												} else echo "----"; ?></td>
                                             <td></td>
											<td><?php echo $img; ?></td>	
											<td>
												<?php if($cat->status=='active'){ ?>
												   <button class="btn btn-success waves-effect" disabled="disabled">Active</button>	
												<?php	}else{  ?>
												   <button class="btn btn-danger waves-effect" disabled="disabled">Inactive</button>	
												<?php	} ?>
											</td>
											
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
                            return "<button type=\"button\" class=\"btn btn-icon command-edit\" onclick=\"EditRow(" + row.rowid + ",'backend/category/make/')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
                                "<button type=\"button\" class=\"btn btn-icon command-delete\" onclick=\"DeleteRow(" + row.rowid + ",'backend/category/delete/')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
                        },
                       "image":function(column,row){
						   
						   return "<img src="+row.imagesrc+" style='height:50px;width:60px;'>";
						   } 
                    }
                });
            });
        </script>
