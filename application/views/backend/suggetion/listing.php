<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
          <style>
	   .width10{
		   width: 10%;
		  }
		  .width60{
		   width: 60%;
		  }
		 .width30{
           width: 15%;
		   text-align: center !important;
		  } 
   .bootgrid-table td{
    text-overflow: ellipsis;  
    white-space: break-spaces;
   }
</style>	   
            
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Booking - Listing</h2>
                    </div>
                    
                    <div class="card">
						
						<div class="card-header">
							<?php $this->load->view('backend/common/alert'); ?>  
						</div>
                        
                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-order="asc" data-header-css-class="width10" data-type="numeric">ID</th>
                                    <th data-column-id="rowid" data-order="asc" data-type="numeric" data-visible="false"></th>
                                    <th data-column-id="salon" data-order="asc" data-header-css-class="width30" data-type="string">Salon name</th>
                                    <th data-column-id="suggestion" data-order="asc" data-header-css-class="width60" data-type="string">Suggestion</th>                                    
                                    <th data-column-id="creteddate" data-header-css-class="width30 text-right">Date time</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
								<?php 
								   
								   if($suggetion){
									   $i=1;
								     foreach($suggetion as $row){ ?>
										  
										  <tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row->id; ?></td>
											<td><?php echo $row->business_name; ?></td>
											<td><?php echo $row->description; ?></td>											
											<td data-header-css-class="text-right"><?php echo date('d-m-Y H:i',strtotime($row->created_on)); ?></td>
											
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
    <script type="text/javascript">
            $(document).ready(function(){              
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
                    }
                });
            });
        </script>

 <!-- Data Table -->
      
