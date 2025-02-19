<?php $this->load->view('frontend/common/header');
 $this->load->view('frontend/common/editer_css'); $keyGoogle =GOOGLEADDRESSAPIKEY;


 ?>

<style>
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
#accordion-faq ol{
	list-style: devanagari;
}
#accordion-faq ol > li{
	list-style: inherit;
}
#accordion-faq ul{
	list-style: unset;
}
#accordion-faq ul > li{
	list-style: inherit;
}

</style>


<section class="pt-84 clear user_profile_section1">
    <?php $this->load->view('frontend/common/sidebar'); ?>
 	<div class="right-side-dashbord w-100 pl-30 pr-15">
		<div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="relative mb-60 bgwhite box-shadow1 around-20 mt-20">
					<div id="accordion-faq">
					<?php if(!empty($faqs)){
						$cat ="";
						$i=1;
						$j=1;
						  foreach($faqs as $row){ 
							if($cat != $row->cat_name){ ?>
								
						  	  	<div class="pb-2 categaryFaq" data-toggle="collapse" data-target="#cat-dyname<?php echo $row->cat_id; ?>" aria-expanded="false">
									<h5 class="font-size-18 fontfamily-medium color333"><?php echo $row->cat_name; ?></h5>
								</div>
								<div class="collapse" id="cat-dyname<?php echo $row->cat_id; ?>">
								<?php $i =1; } ?>	
								<div class="faq-Que border-w " >
									<div data-toggle="collapse" data-target="#collapseOne<?php echo $row->id; ?>" aria-expanded="true" aria-controls="collapseOne<?php echo $row->id; ?>" class="question collapsed">
										<span class="fontsize-16 color333 fontfamily-medium">
										<span class="display-ib mr-2"><?php echo $i; ?>.</span> 
										<?php echo $row->question; ?></span>
										<i class="fas fa-chevron-up colorcyan"></i>
									</div>
									<div id="collapseOne<?php echo $row->id; ?>" class="collapse fr-view" data-parent="#accordion-faq">
										<span class="faq-answer">
										<?php echo $row->answer; ?>
										</span>	
									</div>
								</div>
						      <?php if($row->qus_count == $j){ $j= 0; ?>
						      </div>
						      <?php } ?> 
							 <?php $i++; $j++; $cat = $row->cat_name;
							 
							 } }else{ ?>
							<div class="no-listing-faq">
								<img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>" class="">
							</div>
							<?php } ?>
<!--
						<div class="faq-Que border-w">
							<div data-toggle="collapse" data-target="#collapsetwo" aria-expanded="true" aria-controls="collapsetwo" class="question collapsed">
								<span class="fontsize-16 color333 fontfamily-medium"><span class="display-ib mr-2">Que 2.</span> Anim pariatur cliche reprehenderit ?</span>
								<i class="fas fa-chevron-up colorcyan"></i>
							</div>
							<div id="collapsetwo" class="collapse " data-parent="#accordion-faq">
								<span class="faq-answer">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
								</span>	
							</div>
						</div>


						<div class="faq-Que border-w">
							<div data-toggle="collapse" data-target="#collapsethree" aria-expanded="true" aria-controls="collapsethree" class="question collapsed">
								<span class="fontsize-16 color333 fontfamily-medium"><span class="display-ib mr-2">Que 3.</span> Anim pariatur cliche reprehenderit ?</span>
								<i class="fas fa-chevron-up colorcyan"></i>
							</div>
							<div id="collapsethree" class="collapse " data-parent="#accordion-faq">
								<span class="faq-answer">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
								</span>	
							</div>
						</div>

						<div class="faq-Que border-w">
							<div data-toggle="collapse" data-target="#collapsefore" aria-expanded="true" aria-controls="collapsefore" class="question collapsed">
								<span class="fontsize-16 color333 fontfamily-medium"><span class="display-ib mr-2">Que 4.</span> Anim pariatur cliche reprehenderit ?</span>
								<i class="fas fa-chevron-up colorcyan"></i>
							</div>
							<div id="collapsefore" class="collapse " data-parent="#accordion-faq">
								<span class="faq-answer">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
								</span>	
							</div>
						</div>

						<div class="faq-Que border-w">
							<div data-toggle="collapse" data-target="#collapsefive" aria-expanded="true" aria-controls="collapsefive" class="question collapsed">
								<span class="fontsize-16 color333 fontfamily-medium"><span class="display-ib mr-2">Que 5.</span> Anim pariatur cliche reprehenderit ?</span>
								<i class="fas fa-chevron-up colorcyan"></i>
							</div>
							<div id="collapsefive" class="collapse " data-parent="#accordion-faq">
								<span class="faq-answer">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
								</span>	
							</div>
						</div>

						<div class="faq-Que border-w">
							<div data-toggle="collapse" data-target="#collapsesix" aria-expanded="true" aria-controls="collapsesix" class="question collapsed">
								<span class="fontsize-16 color333 fontfamily-medium">
									<span class="display-ib mr-2">Que 6.</span> Anim pariatur cliche reprehenderit ?</span>
								<i class="fas fa-chevron-up colorcyan"></i>
							</div>
							<div id="collapsesix" class="collapse " data-parent="#accordion-faq">
								<span class="faq-answer">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
								</span>	
							</div>
						</div>
-->

						<!-- <nav aria-label="" class="text-right">
							<ul class="pagination" style="display: inline-flex;" id="pagination">
								 <?php if($this->pagination->create_links()){ echo $this->pagination->create_links(); } ?>

							</ul>
						</nav> -->
					</div>
                </div>
            </div>
        </div>
    </div>
</section>
 
<?php      $this->load->view('frontend/common/footer_script');
           ?>
 


