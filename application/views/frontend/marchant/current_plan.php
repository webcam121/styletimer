<?php $this->load->view('frontend/common/header'); ?>
<style type="text/css">
.header-container{
		width:100%;
	}	 
	@media (min-width: 1400px) and (max-width: 1919px) {
		.header-container{
		   margin: 0 auto;
		   width: 100%;
		   padding: 0 20px;
		}
	}
	@media (min-width: 1920px) {
		  .header-container{
			margin: 0 auto;
			width: 100%;
			padding: 0 20px;
		}
	}
	@media (min-width: 1921px) and (max-width: 4400px) {
		 .header-container{
			margin: 0 auto;
			width: 100%;
			padding: 0 20px;
		}
	}
	@media (min-width:4401px){
		.header-container{
			margin: 0 auto;
			width: 100%;
			padding: 0 20px;
		}
	}
.page-item{display:inline-block;}
.page-item a{
    position: relative;
    display: block;
    height: 26px;
    width: 26px;
    padding: 0rem;
    margin: 0rem 2px;
    color: #333333;
    background-color: transparent;
    border: 1px solid transparent;
    border-radius: 4px;
    text-align: center;
    line-height: 26px;

}
.current_plan_membership_img{
	width:185px;
	height:185px;
}
</style>

<div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); //if(!empty($user)) echo "<pre>"; print_r($user);  die; ?>
 <div class="right-side-dashbord w-100 pl-30 pr-30">
	 
     <div class="row">
           <div class="col-12 col-sm-12 col-md-12 col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">
             <div class="text-center bgwhite box-shadow1 mt-20 mb-20">
               <div class="bgorange colorwhite text-center around-15">
				  
                 <p class="colorwhite fontfamily-medium font-size-18 mb-0"><?php echo $this->lang->line('Current-Plan'); ?></p>
               </div>
               <div class="pr-35 pl-35 pb-20">
                 <img src="<?php echo base_url("assets/frontend/"); ?>images/service_provider_membership_icon.svg" class="current_plan_membership_img">
                 
                 <p class="colororange font-size-18 fontfamily-medium"><?php if(!empty($planDetail->plan_name)) echo $planDetail->plan_name; ?> (<?php if(!empty($planDetail->price)) echo $planDetail->price; ?> €)</p>
                   <?php if($user->subscription_status=='active' || $user->subscription_status=='payment_failed'){ ?>
                  <a data-href="<?php echo base_url('membership/cancen_subscription'); ?>" id="cancel-subscription" class="font-size-18" style="color:#009EA8;cursor:pointer;"><?php echo $this->lang->line('Cancel-subscription'); ?></a>
                  <?php } else{ ?>
					   <a class="font-size-18" style="color:#009EA8;"><?php echo $this->lang->line('Subscription-cancelled'); ?></a>
					  <?php } ?>
                 <!--<p class="font-size-14 color666 fontfamily-regular" style="margin-top: 8px;"><?php
				 // echo $this->lang->line('Plan-Duration'); ?><?php //echo date('d-m-Y',strtotime($user->start_date)); ?>  <?php // echo date('d-m-Y',strtotime($user->end_date)); ?></p>-->
                 
                 <h3 class="color333 font-size-20 fontfamily-medium mb-20" style="margin-top: 8px;"><?php echo $this->lang->line('Your-month-Activated'); ?></h3>
                 <p class="color666 fontfamily-regular fon-size-14 lineheight22 mb-30"><?php echo $this->lang->line('current-plan-txt'); ?></p>
<!--
               <a href="#" class="color333 fontfamily-regular font-size-14 text-underline a_hover_333">Upgrade Plan <i class="fas fa-chevron-right fontweight-normal font-size-13"></i></a>
-->

             <?php if(!empty($planDetail->stripe_plan_id) && $planDetail->stripe_plan_id==STRIPE_P2){ ?>
				<div class="d-flex">
					<!-- <a href="<?php 
					//echo base_url('membership/upgrade'); ?>" class="btn widthfit display-ib"><?php
					// echo $this->lang->line('Degrade'); ?></a> -->
					<a href="<?php echo base_url('membership/upgrade'); ?>" class="btn widthfit display-ib ml-auto"><?php echo $this->lang->line('Upgrade'); ?></a>
				</div>
				<?php }
				
				if(!empty($planDetail->stripe_plan_id) && $planDetail->stripe_plan_id==STRIPE_P1){ ?>
				<div class="text-center">
					<a href="<?php echo base_url('membership/upgrade'); ?>" class="btn widthfit"><?php echo $this->lang->line('Upgrade'); ?></a>
				</div>
				<?php } if(!empty($planDetail->stripe_plan_id) && $planDetail->stripe_plan_id==STRIPE_P3){ ?>
					<div class="text-center">
						<a href="<?php echo base_url('membership/upgrade'); ?>" class="btn widthfit"><?php echo $this->lang->line('Upgrade'); ?></a>
					  <!-- <a href="<?php echo base_url('membership/upgrade'); ?>" class="btn widthfit"><?php echo $this->lang->line('Degrade'); ?></a> -->
				   </div>
				
				<?php } ?>
               </div>
             </div>

           </div>
         </div>
</div>
</div>

<!-- modal start -->
   

   

<?php $this->load->view('frontend/common/footer_script');  ?>
<script>
$(document).on('click','#cancel-subscription',function(){
  var url=$(this).data('href');
  
							Swal.fire({
								  title: '<?php echo $this->lang->line("are_you_sure"); ?>',
								  text: "<?php echo $this->lang->line('You_want_cancel_subscription'); ?>",
								  type: 'warning',
								  showCancelButton: true,
								  reverseButtons:true,
								  confirmButtonColor: '#3085d6',
								  cancelButtonColor: '#d33',
								  confirmButtonText: 'Bestätigen'
								}).then((result) => {
								if (result.value) {
									loading();
									//alert(url);
								window.location.href=url;
									
								  }else{
									  return false;
									//alert('else');  //revertFunc();
									  }
								})
  
 });

</script>

