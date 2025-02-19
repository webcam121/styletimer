<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
            
            
          <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Offer</h2>
                    </div>
                
                    <div class="card">
                        <div class="card-header">
                            <h2>Update Offer Text 
                            <small></small>
                            </h2>
                        </div>
                       
                        <div class="card-body card-padding">
                            <form name="form-user" id="form-user" class="form-user" action="" method="post" enctype="multipart/form-data">
								
								<div class="row">
									
   								 <div class="col-xs-12">
									<div class="fg-line form-group">
											<label for="exampleInputEmail1">Offer Text</label>
											<!-- <input type="text" class="form-control input-sm" id="category_name" name="category_name" placeholder="Enter category name" value="<?php echo (isset($category->category_name))?$category->category_name:((isset($category_name))?$category_name:''); ?>"> -->
											<textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter offer text.."><?php echo (isset($category->offer_text))?$category->offer_text:''; ?></textarea>
											<span class="error"><?php 
												echo form_error('description');
												?></span>
										</div>
   									</div>
                                
                            	</div>
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
