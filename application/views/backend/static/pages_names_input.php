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
                        <h2>Links</h2>
                    </div>
                
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $query_type; ?> links name 
                            <small></small>
                            </h2>
                        </div>
                       
                        <div class="card-body card-padding">
                            <form name="form-user" id="form-user" class="form-user" action="" method="post" enctype="multipart/form-data">
								
								<div class="row">
									<div class="col-xs-6" >
										<div class="fg-line form-group">
											
										    <label for="exampleInputEmail1">Register your Salon</label>
                                    
												<input type="text" class="form-control input-sm" id="reg_salon" name="reg_salon" placeholder="Enter ...." value="<?php echo (isset($names->reg_salon))?$names->reg_salon:((isset($reg_salon))?$reg_salon:''); ?>">
											<span class="error"><?php 
												if(form_error('reg_salon')){
													echo form_error('category_name');
												}
												
												echo $this->session->flashdata('err_mesg');
												?></span>

											
										</div>

										<div class="fg-line form-group">
											<label for="exampleInputEmail1">Book Appointment</label>
											<input type="text" class="form-control input-sm" id="book_appointment" name="book_appointment" placeholder="Enter .." value="<?php echo (isset($names->book_appointment))?$names->book_appointment:((isset($book_appointment))?$book_appointment:''); ?>">
											<span class="error"><?php 
												if(form_error('book_appointment')){
													echo form_error('book_appointment');
												}
												
												?></span>
										</div>

										<div class="fg-line form-group">
											
										    <label for="exampleInputEmail1">Contact Us</label>
											<input type="text" class="form-control input-sm" id="contact_us" name="contact_us" placeholder="Enter .." value="<?php echo (isset($names->contact_us))?$names->contact_us:((isset($contact_us))?$contact_us:''); ?>">
											<span class="error"><?php 
												if(form_error('contact_us')){
													echo form_error('contact_us');
												}
												
												?></span>
											
										</div>
									</div>
									<div class="col-xs-6" >
										<div class="fg-line form-group">
											
										    <label for="exampleInputEmail1">About Us</label>
                                    
												<input type="text" class="form-control input-sm" id="about_us" name="about_us" placeholder="Enter ...." value="<?php echo (isset($names->about_us))?$names->about_us:((isset($about_us))?$about_us:''); ?>">
											<span class="error"><?php 
												if(form_error('about_us')){
													echo form_error('about_us');
												}
												
												?></span>

											
										</div>

										<div class="fg-line form-group">
											<label for="exampleInputEmail1">Conditions</label>
											<input type="text" class="form-control input-sm" id="conditions" name="conditions" placeholder="Enter .." value="<?php echo (isset($names->conditions))?$names->conditions:((isset($conditions))?$conditions:''); ?>">
											<span class="error"><?php 
												if(form_error('conditions')){
													echo form_error('conditions');
												}
												
												?></span>
										</div>

										<div class="fg-line form-group">
											
										    <label for="exampleInputEmail1">Terms of Use</label>
											<input type="text" class="form-control input-sm" id="terms_of_use" name="terms_of_use" placeholder="Enter .." value="<?php echo (isset($names->terms_of_use))?$names->terms_of_use:((isset($terms_of_use))?$terms_of_use:''); ?>">
											<span class="error"><?php 
												if(form_error('terms_of_use')){
													echo form_error('terms_of_use');
												}
												
												?></span>
											
										</div>
									</div>

                            	</div>
                            	<div class="row">
                            		<div class="col-xs-6" >
										<div class="fg-line form-group">
											
										    <label for="exampleInputEmail1">Imprint</label>
                                    
												<input type="text" class="form-control input-sm" id="imprint" name="imprint" placeholder="Enter ...." value="<?php echo (isset($names->imprint))?$names->imprint:((isset($imprint))?$imprint:''); ?>">
											<span class="error"><?php 
												if(form_error('imprint')){
													echo form_error('imprint');
												}
												
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
