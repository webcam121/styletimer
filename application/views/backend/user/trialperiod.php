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
                            <h2><?php echo $query_type; ?> Trial Period
                            <small></small>
                            </h2>
                        </div>
                       
                        <div class="card-body card-padding">
                            <form name="form-user" id="form-user" class="form-user" action="" method="post" enctype="multipart/form-data">
								
								<div class="row">
									<div class="col-xs-6" >
										<div class="fg-line form-group">
											
										    <label for="exampleInputEmail1">Select a time period</label>                                    
												<select name="period" class="selectpicker">													
												<?php $priods=array(0,1,2,3,4,5,6,7,8,9,10,11,12);
												   if($priods){
													foreach($priods as $time){ ?>
                                                        <option value='<?php echo $time; ?>' <?php if(!empty($time) && $time==$detail->notification_time) echo 'selected="selected"'; ?>>
                                                            <?php
                                                                if ($time == 0) {
                                                                    echo '1 Day';
                                                                }
                                                                else {
                                                                    echo $time; if($time==1) echo ' month'; else echo ' months';
                                                                }
                                                            ?>
                                                        </option>
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
