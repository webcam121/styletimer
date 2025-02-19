<?php $this->load->view('frontend/common/header'); ?>
<!-- dashboard left side end -->
<style>
.swal2-title{
	font-size: 1.15em;
	}
</style>
 <div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>	
	<div class="right-side-dashbord w-100 pl-30 pr-30">
          <div class="relative mt-60 mb-60">
            <div class="text-center mb-4">
              <h1 class="color333 font-size-34 fontfamily-semibold before_cyan_line relative mb-30"><?php echo $this->lang->line('Membership_Pricing'); ?></h1>
              <p class="color333 font-size-16 fontfamily-regular lineheight30 pt-30 mb-5"><?php echo $this->lang->line('there_need_to'); ?><br><?php echo $this->lang->line('all_plan_can'); ?></p>
            </div>

            <div class="row membrer_plan_row">
				<?php if(!empty($memberships)){ 
					$i=1;
					foreach($memberships as $plan){ 
						$type="degrade";
						if($userdata->plan_id==STRIPE_P1){
							$type="upgrade";
							}
						elseif($userdata->plan_id==STRIPE_P2 && $plan->stripe_plan_id==STRIPE_P3)
						   {
							 $type="upgrade";  
						   }	
						//elseif($userdata->plan_id=='st_gold' && $plan->stripe_plan_id=='st_regular')  
						   
						 ?>
					  <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
						<div class="membrer_plan_box c1">
						<span  style="float: right;position: absolute;top: -5px;right: -3px;"><?php if($plan->plan_name=='PREMIUM')
							{ $img=base_url('assets/frontend/images/member-box-best-offer.png');?>
							 <img src='<?php echo $img;?>' alt="Girl in a jacket"
							 > <?php } ?>
                                      </span>
						  <div class="relative text-center" style="z-index:1;">
							<h1 class="colororange fontfamily-regular font-size-70 mb-4"><span class="font-size-50"></span>
							<!-- <?php echo $plan->price; ?> -->
							<?php 
								switch($plan->plan_name) {
									case 'BASIC':
										echo '4,90€';
										break;
									case 'GOLD':
										echo '9,90€';
										break;
									case 'PREMIUM':
										echo '14,90€';
										break;
								}
							?>
							</h1>
							<p class="fontfamily-bold color333 font-size-20 mb-4"><?php echo $plan->plan_name; ?></p>

							<?php /* <p class="fontfamily-medium color333 font-size-18"><?php echo $this->lang->line('up_to'); ?></p> */ ?>
							<p class="fontfamily-medium color333 font-size-30"><?php if($plan->employee=='unlimited'){ echo $this->lang->line('unlimited'); } else echo 'bis zu ' . ucfirst($plan->employee); ?></p>
							<p class="fontfamily-medium color333 font-size-20 mb-5"><?php echo $this->lang->line('Employees'); ?></p>
							<a <?php if($userdata->plan_id==$plan->stripe_plan_id){ }else{ ?> onclick="return upgradeSubscription('<?php echo base_url('membership/upgrade_plan_submit/'.$plan->stripe_plan_id); ?>','<?php echo $type; ?>','<?php echo $plan->stripe_plan_id; ?>');" <?php } ?>>
							<button class="btn height48v width150v" style="text-transform: lowercase; padding: 0.5rem 0rem !important"><?php if($userdata->plan_id==$plan->stripe_plan_id){ echo  $this->lang->line('change_plan'); }else{ echo $this->lang->line('choose_plan'); } ?></button></a>
						  </div>
						</div>
					  </div>
             <?php $i++; } } ?>
             
  
            </div>
            
      <!-- <div class="text-center mb-4">
              
              <p class="color333 font-size-16 fontfamily-regular lineheight30 pt-30 mb-5"><?php echo $this->lang->line('there_need_to'); ?><br><?php echo $this->lang->line('all_plan_can'); ?></p>
            </div>-->

            </div>
            
</div>

<style>
	 .swal2-content{
		 font-size: 1.125em !important
		 }
</style>

  <?php $this->load->view('frontend/common/footer_script');  ?>  
  <script>
  
  function upgradeSubscription(url,type,plan_id){
	  
	  Swal.fire({
			  title: '<?php echo $this->lang->line("are_you_sure"); ?>',
			  text: "Du möchtest deine Mitgliedschaft ändern ?",
			  type: 'warning',
			  showCancelButton: true,
			  reverseButtons:true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Bestätigen'
			}).then((result) => {
			if (result.value) {
				loading();
				if(type=='degrade'){
						$.ajax({
					   type: "POST",
					   url:base_url+"membership/checkemployee",
					   data:'plan_id='+plan_id,
					   success: function (response) {
						  unloading();
							var obj = $.parseJSON( response );
							if(obj.success==1){
								  
								   window.location.href=url;
								  //$(this).removeClass("unblock");    
								  //$(this).addClass("partialblock");    
							}else{
								Swal.fire({
									  title: obj.msg,
									  width: 600,
									  padding: '3em'
									});
								//alert(obj.msg);
								//$(this).addClass("unblock");    
								//$(this).removeClass("partialblock");
							}
							
					   }
				   });
			}
		  else{
			  window.location.href=url;
			  
			  }	  
				
			  }else{
				 return false;
				  }
			});
	  
	   }
  
  </script>
