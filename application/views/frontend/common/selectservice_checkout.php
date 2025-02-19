       
              <!-- first list -->

              <!-- first list -->
              
              <!-- second list -->
             <div class="max-height300">
				  <?php if(!empty($services)){
					  
					  foreach($services as $k=>$v){
						                               ?>
				  <div class="modal-list-new select-cat-check" data-text="<?php echo $k; ?>" data-id="<?php echo $v[0]->subcategory_id; ?>">
					<p class="mb-0 color666 fontfamily-regular font-size-18"><?php echo $k; ?></p>
					<img src="<?php echo base_url('assets/frontend/images/arrow-angle-pointing-to-right.svg'); ?>" class="width12" style="opacity: 0.5;">
				  </div>  
				  <!-- second list -->

				  <!-- third list -->
				  <?php if(!empty($v)){ 
						foreach($v as $row){ ?>
							
							  <div class="modal-list-new select-cat-inner-check select-cat-inner-check<?php echo $row->subcategory_id; ?>  selectCategory" data-price="<?php echo $row->price; ?>" data-sid="<?php echo $row->id; ?>" data-name="<?php if(!empty($row->name)) echo $row->name; else echo $row->category_name; ?>" data-tax="<?php echo $row->tax_id; ?>" data-duration="<?php echo $row->duration; ?>">
								<div class="text-left">
								  <p class="mb-0 color666 fontfamily-regular font-size-16"><?php echo $row->category_name; ?></p>
								  <span class="font-size-14 color999 fontfamily-regular"><?php if(!empty($row->name)) echo $row->name.', '; echo $row->duration; ?> min</span>
								</div>
								<span class="font-size-16 color666">€<?php echo price_formate($row->price);  ?></span>
							  </div>
				  
              <!-- third list -->
              
            
					<?php } } } }
					else{ ?>
					<div class="text-center pb-20 pt-50">
						  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;">No services found</p>
						  </div>

					<?php } ?>
            </div>
       
