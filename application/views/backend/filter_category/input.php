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
                        <h2>Filter category</h2>
                    </div>
                
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $query_type; ?> filter category 
                            <small></small>
                            </h2>
                        </div>
                       
                        <div class="card-body card-padding">
                            <form name="form-user" id="form-user" class="form-user" action="" method="post" enctype="multipart/form-data">
								
								<div class="row">
									<div class="col-xs-6" >
									

										<div class="fg-line form-group">
											<label for="exampleInputEmail1">Category name</label>
											<input type="text" class="form-control input-sm" id="category_name" name="category_name" placeholder="Enter category name" value="<?php echo (isset($category->category_name))?$category->category_name:((isset($category_name))?$category_name:''); ?>">
											<span class="error"><?php 
												if(form_error('category_name') =='<p>This filter category is already registered.</p>'){
													echo '<p>This category already added.</p>';
												}
												else{
													echo form_error('category_name');
												}
												echo $this->session->flashdata('err_mesg');
												?></span>
										</div>

										<div class="fg-line form-group">
											
										    <label for="exampleInputEmail1">Status</label>
                                    
											<select name="status" class="selectpicker">
												<option value="active" <?php echo (isset($category->status) && $category->status=='active')?'selected':((isset($status) && $status=='active')?'selected':''); ?>>Active</option>
												<option value="inactive" <?php echo (isset($category->status) && $category->status=='inactive')?'selected':((isset($status) && $status=='inactive')?'selected':''); ?>>Inactive</option>
												<option value="delete" <?php echo (isset($category->status) && $category->status=='deleted')?'selected':((isset($status) && $status=='deleted')?'selected':''); ?>>Delete</option>
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
