<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
            
            
          <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Admin</h2>
                    </div>
                
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $query_type; ?> salesman
                            <small></small>
                            </h2>
                        </div>
                        
                        <div class="card-body card-padding">
                            <form name="form-user" id="form-user" class="form-user" action="" method="post" enctype="multipart/form-data">
								
								<div class="row">
									<div class="col-xs-6">
										<div class="fg-line form-group">
											<label for="exampleInputEmail1">First name</label>
											<input type="text" class="form-control input-sm" id="first_name" name="first_name" placeholder="Enter First name" value="<?php echo (isset($user->first_name))?$user->first_name:((isset($first_name))?$first_name:''); ?>">
											<span class="error"><?php echo form_error('first_name') ;?></span>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="fg-line form-group">
											<label for="exampleInputEmail1">Last name</label>
											<input type="text" class="form-control input-sm" id="last_name" name="last_name" placeholder="Enter last name" value="<?php echo (isset($user->last_name))?$user->last_name:((isset($last_name))?$last_name:''); ?>">
											<span class="error"><?php echo form_error('last_name') ;?></span>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="fg-line form-group">
											<label for="exampleInputEmail1">Mobile</label>
											<input type="text" class="form-control input-sm" id="mobile" name="mobile" placeholder="Enter mobile number" value="<?php echo (isset($user->mobile))?$user->mobile:((isset($mobile))?$mobile:''); ?>">
											<span class="error"><?php echo form_error('mobile') ;?></span>
										
										</div>
									</div>								
									<div class="col-xs-6">
										<div class="fg-line form-group">
											<label for="exampleInputEmail1">Email address</label>
                                            <input type="text" class="form-control input-sm" id="email" name="email" placeholder="Enter Email" value="<?php echo (isset($user->email))?$user->email:((isset($email))?$email:''); ?>" <?php echo (isset($query_type) && $query_type=='Update')?'readonly':''; ?> >
                                            <span class="error"><?php echo form_error('email') ;?></span>
										</div>
										
									</div>
									<div class="col-xs-6">
										<div class="fg-line form-group">
											
										    <label for="exampleInputEmail1">Status</label>
                                    
											<select name="status" class="selectpicker">
												<option value="active" <?php echo (isset($user->status) && $user->status=='active')?'selected':((isset($status) && $status=='active')?'selected':''); ?>>Active</option>
												<option value="inactive" <?php echo (isset($user->status) && $user->status=='inactive')?'selected':((isset($status) && $status=='inactive')?'selected':''); ?>>Inactive</option>
											</select>
											
										</div>
									</div>
								 </div>	
								 <input type="hidden" name="access" value="<?php echo (isset($user->access))?$user->access:((isset($access))?$access:''); ?>">
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
