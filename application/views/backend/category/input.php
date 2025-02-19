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
		<h2>User</h2>
		</div>

		<div class="card">
		<div class="card-header">
			<h2><?php echo $query_type; ?> category 
			<small></small>
			</h2>
		</div>

		<div class="card-body card-padding">
			<form name="form-user" id="form-user" class="form-user" action="" method="post" enctype="multipart/form-data">
				<?php
					if(isset($category->parent_id) && $category->parent_id == 0)
						$style='none';
					else
						$style='block';
					if(isset($category->parent_id) && $category->parent_id != 0)
						$style1='block';
					else
						$style1='none';

					?>
				<div class="row">
					<div class="col-xs-6" >
						<div class="fg-line form-group" style="display: <?php echo $style; ?>">
							
							<label for="exampleInputEmail1">Parent category select</label>
					
								<select name="parent_id" class="selectpicker" id="select_parent_cat_id">
									<option value='0' <?php if(!empty($category->parent_id) && $category->parent_id=='0') echo 'selected="selected"'; ?>>Parent category select</option>
								<?php if($categories){
									foreach($categories as $cat){ ?>
											<option value='<?php echo $cat->id; ?>' <?php if(!empty($category->parent_id) && $category->parent_id==$cat->id) echo 'selected="selected"'; ?>><?php echo $cat->category_name; ?></option>
										<?php } } ?>
									</select>
						</div>

						<div class="fg-line form-group">
							<label for="exampleInputEmail1">Category name</label>
							<input type="text" class="form-control input-sm" id="category_name" name="category_name" placeholder="Enter category name" value="<?php echo (isset($category->category_name))?$category->category_name:((isset($category_name))?$category_name:''); ?>">
							<span class="error"><?php 
								if(form_error('category_name') =='<p>This email is already registered.</p>'){
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
					<div class="col-xs-6">
						<div class="row">
							<div class="col-xs-6">
								<p class="f-500 c-black m-b-20">Upload Image</p>
								
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<span class="btn btn-primary btn-file m-r-10">
										<span class="fileinput-new">Select file</span>
										<span class="fileinput-exists">Change</span>
										<input type="file" name="image">
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput">&times;</a>
								</div>
								<div class="new-img"><?php if(!empty($category->image)){ ?>
									<img src="<?php echo base_url('assets/uploads/category/').$category->id.'/'.$category->image; ?>" style="width: 45%;height: 90px;">
									<?php } ?>
								</div>
							</div>
							<div class="col-xs-6">
								<p class="f-500 c-black m-b-20">Upload Icon</p>
								
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<span class="btn btn-primary btn-file m-r-10">
										<span class="fileinput-new">Select file</span>
										<span class="fileinput-exists">Change</span>
										<input type="file" name="icon">
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput">&times;</a>
								</div>
								<div class="new-img"><?php if(!empty($category->image)){ ?>
									<img src="<?php echo base_url('assets/uploads/category_icon/').$category->id.'/'.$category->icon; ?>" style="width: 45%;height: 90px;">
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
							<div class="fg-line form-group">
								<?php $countyArr =array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'); ?>
							
							<label for="exampleInputEmail1">Sorting Position</label>
					
								<select name="sorting_position" class="selectpicker">
								
								<?php if($countyArr){
									foreach($countyArr as $count){ ?>
											<option value='<?php echo $count; ?>' <?php if(!empty($category->show_order) && $category->show_order==$count) echo 'selected="selected"'; ?>><?php echo $count; ?></option>
										<?php } } ?>
									</select>
							<span class="error"><?php echo form_error('sorting_position') ?></span>
						      </div>
							</div>
							
						<div class="col-xs-6">
							<div class="fg-line form-group select_parent_cat_id" style="display: <?php echo $style1; ?>">
							
							<label for="exampleInputEmail1">Select filter category</label>
					
								<select name="filter_category" class="selectpicker">
								<option value='' <?php if(!empty($category->filter_category) && $category->filter_category=='0') echo 'selected="selected"'; ?>>Select filter category</option>
								<?php if($filter_category){
									foreach($filter_category as $fcat){ ?>
											<option value='<?php echo $fcat->id; ?>' <?php if(!empty($category->filter_category) && $category->filter_category==$fcat->id) echo 'selected="selected"'; ?>><?php echo $fcat->category_name; ?></option>
										<?php } } ?>
									</select>
							<span class="error"><?php echo form_error('filter_category') ?></span>
						      </div>
							</div>
							<div class="col-xs-12">
								<div class="form-group">
								<label class="" style="margin-bottom:16px;">Show on dropdown</label>
									<div id="showondrop">
										<div class="toggle-switch">
											<input id="show-droup" type="checkbox" value="1" name="show_on_dropdown" hidden="hidden" <?php if(!empty($category->show_dropdown) && $category->show_dropdown==1) echo 'checked'; ?>>
											<label for="show-droup" class="ts-helper"></label>
										</div>
									</div>
								</div>
							</div>
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
          $("#select_parent_cat_id").change(function(){
			  if($(this).val()==0){
               $(".select_parent_cat_id").css('display','none');
			  }
			 else{
				$(".select_parent_cat_id").css('display','block');

			 } 

		  });
		});
		</script>
