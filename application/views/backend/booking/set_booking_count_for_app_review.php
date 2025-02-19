<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
    <style type="text/css">
    	.new-img img{
    		width: 100%;
			height: 145px;
			object-fit: cover;
    	}
    </style>       
            
          <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2></h2>
                    </div>
                
                    <div class="card">
							<div class="card-header">
							<?php $this->load->view('backend/common/alert'); ?>  
						</div>
                        <div class="card-header">
                            <h2><?php echo $query_type; ?> app review setting
                            <small></small>
                            </h2>
                        </div>
                       
                        <div class="card-body card-padding">
                            <form name="form-user" id="form-user" class="form-user" action="" method="post" enctype="multipart/form-data">
								
								<div class="row">
									<div class="col-xs-6" >
										<div class="fg-line form-group">
											
										    <label for="exampleInputEmail1">Select a booking count for ask review on app</label>                                    
												<select name="booking_count" class="selectpicker">													
												<?php $priods=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19);
												   if($priods){
													foreach($priods as $time){ ?>
														 <option value='<?php echo $time; ?>' <?php if(!empty($time) && $time==$detail->booking_count) echo 'selected="selected"'; ?>><?php echo $time; if($time==1) echo ' Booking'; else echo ' Booking'; ?></option>
														<?php } } ?>
												  </select>
											
										</div>
									</div>
								 </div>
								<input type="hidden" name="access" value=""> 
								<button type="submit" class="btn btn-primary btn-sm m-t-10">Submit</button>
                            </form>
                        </div>
                    </div>
                    
                      
                </div>
            </section>
        
  <?php $this->load->view('backend/common/footer'); ?>
  
  
        
 <!-- Data Table -->
        <script type="text/javascript">
            $(document).ready(function(){
               
            });
        </script>
