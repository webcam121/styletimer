<?php $this->load->view('frontend/common/header'); ?>
<!-- dashboard left side end -->
<style>
  .header-container {
    width: 100% !important;
  }
 
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
					foreach($memberships as $plan){ ?>
					  <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
						<div class="membrer_plan_box c<?php echo $i ?>">
						  <div class="relative text-center" style="z-index:1;">
							<h1 class="colororange fontfamily-regular font-size-70 mb-4"><?php echo $plan->price; ?><span class="font-size-50">€</span></h1>
							<p class="fontfamily-bold color333 font-size-20 mb-4"><?php echo $plan->plan_name; ?></p>

							<?php /* <p class="fontfamily-medium color333 font-size-18"><?php echo $this->lang->line('up_to'); ?></p> */ ?>
							<p class="fontfamily-medium color333 font-size-30"><?php if($plan->employee=='unlimited'){ echo $this->lang->line('unlimited'); } else echo 'bis zu ' . ucfirst($plan->employee); ?></p>
							<p class="fontfamily-medium color333 font-size-20 mb-5"><?php echo $this->lang->line('Employees'); ?></p>
              <a onclick="return chooseSubscription('<?php echo base_url('membership/payment/'.url_encode($plan->id)); ?>','choose','<?php echo url_encode($plan->id); ?>');" ><button class="btn height48v width150v"><?php echo $this->lang->line('choose_plan'); ?></button></a> 
              <!-- <a href="JAvaScript:Void(0);" data-toggle="modal" data-target="#payment-type"><button class="btn height48v width150v">CHOOSE PLAN</button></a> -->
						  </div>
						</div>
					  </div>
             <?php $i++; } } ?>
              

             </div>
           <!-- <div class="text-center mb-4">
              
              <p class="color333 font-size-16 fontfamily-regular lineheight30 pt-30 mb-5">There needs to be 19% tax added on top of prices. Styletimer can be tested for 1 month free of charge.<br>All plans can be cancelled on a monthly basis</p>
            </div> -->
 
 
            </div>
</div>

  <?php $this->load->view('frontend/common/footer_script');  ?>  
  <script>
  
  function chooseSubscription(url,type,plan_id){
	        
			loading();
			
			$.post(
				base_url + "membership/securepayment",
				{planid: plan_id},
				function (data) {
					unloading();
					var obj = jQuery.parseJSON(data);
					//console.log(obj);
					if (obj.success == "1") {
						$("#paymentForm").trigger("reset");
						window.location.href = obj.redirect_url;
					} else {
						Swal.fire({
								title: 'Something Went Wrong',
								width: 600,
								padding: '3em'
						});
					}
				}
			);
	   }
  
  </script>
