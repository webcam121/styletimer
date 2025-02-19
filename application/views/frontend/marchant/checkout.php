<?php $this->load->view('frontend/common/head'); ?>
   <style>
	   span.error{
		   bottom: -16px;
		   }
      .select-cat-check{
         
      }
      .select-cat-inner-check{
          display: none;
      }
    </style>
    <body id="chg_hover" class="bglightgreen1">
		<span class="natification-count display-n" id="chgreview_count" style="display: none;">0</span>
      <div class="checkout-new-page">
		  <form id="checkoutForm" method="post" action="<?php echo base_url('checkout/save_invoice'); ?>">
        <input type="hidden" name="redirect" value="">
          <div class="full-popup ">
              <a href="javascript:history.go(-1)" class="crose-btn">
              <picture class="popup-crose-black-icon">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
                <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
              </picture>
              </a>
              <div class="text-center bgwhite">
                <h4 class="fontfamily-semibold font-size-24 color333 pt-3 pb-3 border-b mb-0">Checkout</h4>
              </div>  
              <div class="container-fluid">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                    <div class="relative around-20 min-height89vh scroll-effect">

                       <input type="hidden" name="booking_id" value="<?php if(!empty($detail->id)) echo $detail->id; ?>">
                       <input type="hidden" name="merchant_id" value="<?php if(!empty($detail->merchant_id)) echo $detail->merchant_id; ?>">
                       <input type="hidden" name="emp_id" value="<?php if(!empty($detail->employee_id)) echo $detail->employee_id; ?>">
                       <input type="hidden" name="user_id" value="<?php if(!empty($detail->user_id)) echo $detail->user_id; ?>">
                       <input type="hidden" name="lastServicebookTime" value="<?php if(!empty($booking_detail)){ if($booking_detail[count($booking_detail)-1]->service_type==1)echo $booking_detail[count($booking_detail)-1]->finishtime_end;  else echo $booking_detail[count($booking_detail)-1]->setuptime_end; } ?>">
<!------------------------------------------------------------------------------------------------------------------------------------------------------------>	
					
                      <div id="accordion" data-count="<?php echo count($booking_detail); ?>">
						  
						<?php
            
             if(!empty($booking_detail)){
							$i = 1;
					foreach($booking_detail as $row){ ?>  
						
                        <div class="card box-shadow1 mb-3">
                          <div class="card-header" id="checkOnedrop<?php echo $row->id;  ?>">
                            <div class="mb-0 d-flex justify-content-between ">
                            <div class="relative pl-30 pt-3 pb-3 pr-3 w-100" data-toggle="collapse" data-target="#checkcollapseOne<?php echo $row->id;  ?>" aria-expanded="true" aria-controls="checkcollapseOne" style="margin:-12px;">
                                <h5 class="fontfamily-medium color333 font-size-18 mb-0"><?php $sub_name=get_subservicename($row->service_id);  if($sub_name == $row->service_name) echo $row->service_name;
                                 else echo $sub_name.' - '.$row->service_name ?></h5>
                                <span class="color666 font-size-14 fontfamily-regular">
                                  <?php echo date('H:i \U\h\r',strtotime($row->setuptime_start)); ?>
                                   <?php echo $this->lang->line('with'); ?> <span class="empnamerplc">
                                     <?php echo $detail->first_name; ?></span>
                                     <?php if(!empty($row->discount_price)){ 
									$disc = get_discount_percent($row->discount_price+$row->price,$row->price);
                  // echo ",
                  // ".price_formate($disc)."% 'Rabatt'";
									  } // , Off peak 10% off?></span>
                            </div>
                            <input type="hidden" name="booking_detailIds[]" value="<?php echo $row->id; ?>">	
                            <input type="hidden" placeholder="1" name="service_id[]" value="<?php echo $row->service_id; ?>">
                            <input type="hidden" name="discount[]" class="discount<?php echo $i; ?>" value="0">
                              
                           

                              <div class="d-flex">
                                <div class="relative text-right">
                                  <p class="color333 fontfamily-medium mb-0"><span class="check_ab<?php echo $i; ?>"><?php if($row->price_start_option=='ab'){ echo 'ab'; } ?></span>
                                  
                                  <span class="pricementax_<?php echo $i; ?>"> <?php if(!empty($row->price)) echo price_formate(round($row->price,2)); ?> €</span></p>
                               
                               <?php if(!empty($row->discount_price)){ ?>
                                  <span class="color666 fontfamily-regular font-size-14"><del><?php echo price_formate($row->discount_price+$row->price);  ?> €</del>
                                </span>
                                <?php } ?>
                                
                                <a href="javascript:void(0)" class="font-size-14 colorcyan a_hover_cyan display-b addDiscountperticulerService discountOpt<?php echo $i; ?>" data-id="<?php echo $i; ?>" data-amount="<?php echo $row->price; ?>"  style="width:140px;margin-left:auto;" >
									            <span>+ <?php echo $this->lang->line('Add_Discount'); ?></span> 
									
								              </a>
                                <span class="font-size-14 colorcyan a_hover_cyan display-b dicounttext<?php echo $i; ?>" style="width: 140px;margin-left:auto;"></span>
                                 <?php if(!empty($row->tax_id)){ $taxRate = get_tax_details($row->tax_id); 
								if(!empty($taxRate)){  $taxcalculated = ($row->price*100/(100+$taxRate->price))*$taxRate->price/100;
									                      ?>
								                    
								<input type="hidden" name="serviceCount[]" value="<?php echo $i; ?>">								
								<input type="hidden" name="taxids[]"  value="<?php echo $row->tax_id; ?>">
								<input type="hidden" name="tax_name_<?php echo $row->tax_id; ?>" value="<?php echo price_formate($taxRate->price)."% ".$taxRate->tax_name; ?>">
								<input type="hidden" name="tax_price_<?php echo $row->tax_id.$i; ?>" data-taxrate="<?php echo $taxRate->price; ?>" class="tax<?php echo $i; ?>" value="<?php echo round($taxcalculated,3); ?>">
								
								<span class="color666 fontfamily-regular font-size-14" style="display: block;width: 200px;">
										 <span><?php echo price_formate($taxRate->price)."% ".$taxRate->tax_name; ?> : </span><span class="tax_text<?php echo $i; ?>"><?php echo price_formate(round($taxcalculated,2)); ?> €</span>
                                </span>
							 
							 <?php }  }else{ ?>
								   <input type="hidden" name="taxids[]"  value="0">
								   <input type="hidden" name="serviceCount[]"  value="<?php echo $i; ?>">
							 <?php } ?>
                                
                                 
                                </div>
                                
<!--
                                <span class="checkout-crose-icon">
                                  <img class="" src="<?php //echo base_url('assets/frontend/images/crose_grediant_orange_icon.svg'); ?>">
                                </span>
-->
                              </div>
                            </div>
                          </div>
                      
                          <div id="checkcollapseOne<?php echo $row->id;  ?>" class="collapse <?php if($i==1) echo 'show'; ?>" aria-labelledby="checkOnecheckOnedrop<?php echo $row->id;  ?>" data-parent="#accordion">
                            <div class="card-body">
                              <div class="row">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2 ">
                                  <div class="form-group">
                                    <span class="label"><?php echo $this->lang->line('quantity'); ?></span>
                                      <label class="inp">
                                        <input type="text" placeholder="1" class="form-control" style="background-color: #e9ecef;" readonly>
                                      </label>                                                
                                  </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                  <div class="form-group">
                                    <span class="label"><?php echo ucfirst(strtolower($this->lang->line('Price'))); ?></span>
                                      <label class="inp">
                                        <span class="rate-fixed">€</span>
                                        <input type="text" name="price[]" value="<?php echo price_formate($row->price); ?>" placeholder="&nbsp;" data-number='<?php echo $i; ?>' class="form-control priceInputChange">
                                      </label>                                                
                                  </div>
                                </div>
                               <!--  <div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                  <div class="form-group">
                                    <span class="label">Staff</span>
                                    <div class="btn-group multi_sigle_select inp_select"> 
                                      <button data-toggle="dropdown" class="btn btn-default mss_sl_btn employeeName"><?php echo $detail->first_name;  ?></button>
                                      </div>
                                    </div>
                                </div>  -->
                                
                              </div>
                            </div>
                          </div>
                        </div> 
                        
                        <?php $i++; }  } ?>
                        
                      </div>
                      
<!------------------------------------------------------------------------------------------------------------------------------------------------------------>
                      <div class="row pl-3 pr-3">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                          <a href="javascript:void(0)" class="font-size-14 colorcyan a_hover_cyan" id="addmoreservice">+ <?php echo $this->lang->line('Add_item_to_sale'); ?></a>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                          <div class="pb-3 pt-3 border-b" id="TaxSubtoal">
                           
                           
<!--
                           <div class="d-flex justify-content-between w-100">
                            <span class="font-size-16 color333 fontfamily-medium">Subtotal</span>
                            <span class="font-size-16 color333 fontfamily-medium">€ 75</span>
                           </div> 
-->
                            
                          </div>
<!--
                          <div class="d-flex justify-content-between pb-3 pt-3 border-b">
                            <span class="font-size-16 color333 fontfamily-medium">Total</span> 
                            <div class="relative">
                              <span class="font-size-16 color333 fontfamily-medium" data-total="0" id="totaltext">€0</span>
                            </div>  
                          </div>
-->

                          <div class="d-flex justify-content-between pb-3 pt-3 border-b display-n" id="discount-sec">
                            <a href="JavaScript:Void(0);" style="font-weight:bold;cursor: auto;" class="font-size-14 color333 a_hover_cyan display-b display-n" id="discounttext"><?php echo $this->lang->line('Discount1'); ?></a>
                            
                            <div class="relative">
							  <input type="hidden" id="discountamount" name="discountamount" value="0">
                              <span class="font-size-16 color333 fontfamily-medium" id="discountamounttext">0 €</span>
                            </div> 
                          </div>
                          <div class="d-flex justify-content-between pb-3 pt-3 border-b">
                            <a href="JavaScript:Void(0);" class="font-size-14 colorcyan a_hover_cyan display-b" data-toggle="modal" data-target="#add-tip" data-backdrop="static" data-keyboard="false" id="tipaddedtxt">+ <?php echo $this->lang->line('Add_Tip'); ?></a>
                             <div class="relative display-n" id="tip-sec">
							  <input type="hidden" id="tipamount" name="tipamount" value="0">
                              <span class="font-size-16 color333 fontfamily-medium" data-total="0" id="tipamounttext">0 €</span>
                              <img class="width12 ml-2" style="cursor: pointer;" id="tipdelete" src="<?php echo base_url('assets/frontend/images/crose_grediant_orange_icon1.svg'); ?>">
                            </div> 
                          </div>
                          
                          
                          <div class="d-flex justify-content-between pb-3 pt-3 border-b">
                            <span class="font-size-20 color333 fontfamily-medium"><?php echo $this->lang->line('Total'); ?></span>
                            <span class="font-size-20 color333 fontfamily-medium" id="balanceText">0 €</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                 
                 

                  <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 pr-0">

                    
                 
                 
                 <div class="bgwhite border-l min-height90vh scroll-effect">
                  <div class="on-hover-div d-flex" style="align-items: center;">
							
						 <?php if($detail->booking_type=='guest'){ ?>
							  <div class="display-ib mr-3 new-popup-img-text">
								<img src="<?php echo base_url('assets/frontend/images/user-blue-v-icon.png'); ?>" class="">
							  </div>
							  <div class="display-ib">
								<p class="mb-0 color333 fontsize-16 fontfamily-medium"><?php echo $detail->fullname; ?></p>
							  </div>
						
						<?php }else{ if(!empty($booking_detail[0]->profile_pic)) 
							                $img_URl  = base_url("assets/uploads/users/".$booking_detail[0]->user_id."/".$booking_detail[0]->profile_pic); 
							          else  $img_URl  = base_url('assets/frontend/images/user-blue-v-icon.png');  ?>
							
							<div class="display-ib mr-3 new-popup-img-text">
								<img src="<?php echo $img_URl; ?>" class="">
							  </div>
							  <div class="display-ib">
								<p class="mb-0 color333 fontsize-16 fontfamily-medium"><?php echo $booking_detail[0]->first_name." ".$booking_detail[0]->last_name; ?></p>
							  </div>
							
						<?php	} ?>	  
                        </div>
                         <div class=" on-hover-div d-flex" style="align-items: center;">
                    <div class="pt-20 mb-0" style="width:100%;">
                      <p class="color333 font-size-16 fontfamily-medium" style="margin-bottom: 0rem !important;"><?php echo $this->lang->line('Employee'); ?></p>
                          <div class="form-group">
                            <div class="btn-group multi_sigle_select inp_select v_inp_new v-droup-new bookingAllEmployee" >
                              
                              <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn employeSelected" id="cat_btn" aria-expanded="false"><?php echo $detail->first_name." ".$detail->last_name;  ?></button>
                                <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" x-placement="bottom-start" id="bookingAllEmployee" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 56px, 0px);">
                                 <?php if(!empty($allemployee)){
                           foreach($allemployee as $emp){ ?>    
                             <li class="radiobox-image"><input type="radio" id="idqemp_<?php echo $emp->id; ?>" name="changed_employee" data-name='<?php echo $emp->first_name; ?>' class="chngeemployee" value="<?php echo url_encode($emp->id); ?>" <?php if($detail->employee_id==$emp->id) echo 'checked'; ?>><label for="idqemp_<?php echo $emp->id; ?>"><?php echo $emp->first_name.' '.$emp->last_name; ?></label></li>
                      <?php } } ?>
                            </ul>

                             </div>
                              <label class="error" id="employee_err"></label>
                         </div>
                    </div>
                  
                  </div>
                        <div class="d-flex w-100 min-height74vh ">
                          <div class="relative w-100 text-center px-3 py-3 bgwhite">                            
                            <span class="font-size-16 color999 fontfamily-regular display-b mb-2">Bezahlt</span>
                            <div class="relative broad-input" id="totalPayInput_validate">  
                                <span class="fixed-euro">€</span>  
<!--
                                <input type="text" placeholder="&nbsp;" class="width-dynamic" autocomplete="off" value="&nbsp; &nbsp; 000 &nbsp; &nbsp; ">
-->
                                <input type="text" placeholder="&nbsp;" name="totalPayInput" id="totalPayInput" class="" autocomplete="off" value="">
                            </div>

                            <div class="pt-4">
                              <button type="button" data-val="cash" class="btn widthfit mx-1 mb-3 cashSlide"><?php echo $this->lang->line('Cash'); ?></button>
                              <button type="button" data-val="card" class="btn widthfit mx-1 mb-3 cashSlide"><?php echo $this->lang->line('Card'); ?></button>
                               <button type="button" data-val="voucher" class="btn widthfit mx-1 mb-3 display-b cashSlide"><?php echo $this->lang->line('Voucher'); ?></button>
                              <button type="button" data-val="other" class="btn widthfit mx-1 mb-3 cashSlide"><?php echo $this->lang->line('Other'); ?></button>
                              
                              <input type="hidden" name="payment_type" id="payment_type" value="">
                              <input type="hidden" name="detail" id="invoicedetailval" value="">
                              <input type="hidden" name="payreicevedby" id="payreicevedby" value="<?php echo url_encode($detail->employee_id); ?>">
                            </div>
                            <div class="checkout-slide-content">
                              <img src="<?php echo base_url('assets/frontend/images/payment-method.png'); ?>" class="display-b slide-icon">
                              <p class="font-size-14 color666 fontfamily-regular mb-40"><?php echo $this->lang->line('Full_payment'); ?></p>

                              <button type="submit" id="complete_sale" class="btn mx-1" style="text-transform: unset;"><?php echo $this->lang->line('Complete_Sale'); ?></button>

                              <a href="JavaScript:Void(0);" id="" class="display-b colorcyan a_hover_cyan mt-3 close-side-slide"><i class="fas fa-chevron-left"></i> <?php echo $this->lang->line('Back_to_payments'); ?></a>
                            </div>

                            <div class="more-opciton-bottom-set">
                              <div class="display-ib dropdown dropdownMoreOption">
                                <button class="btn widthfit dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $this->lang->line('More_Option'); ?></button>
                                <ul class="dropdown-menu">
<!--
                                  <li>
                                    <a class="dropdown-item" href="javascript:void(0);">Save Unpaid</a> 
                                  </li>
-->
                                  <li>
                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#invoice_details" data-backdrop="static" data-keyboard="false"><?php echo $this->lang->line('Invoice_Details'); ?></a> 
                                  </li>
                                </ul>
                              </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>    
          </div>
          </form>
      </div>

 <!-- modal start -->
    <div class="modal fade" id="add-dicount">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0">
            <h3 class="color333 fontfamily-medium font-size-24 border-b pb-3"><?php echo $this->lang->line('Add_Discount'); ?></h3>
            <div class="d-flex justify-content-between align-items-end pl-3 pr-3 pt-2">
              <div class="text-left">
                <div class="form-group mb-0">
                  <span class="label"><?php echo $this->lang->line('Discount_Amount'); ?> <span id="discalculationtext">(0.00%)</span></span>
                    <label class="inp">
                      <span class="rate-fixed" id="dissign">€</span>
                      <input type="text" id="disinputval" placeholder="&nbsp;" class="form-control">
                    </label>                                                
                </div>
              </div>
              
              <input type="hidden" id="totalserviceprice" value="">
              <input type="hidden" id="dicountServiceCount" value="">
              
              <div class="radio rodio-select-tip-amount">
                <label class="fontsize-14 pl0 color333">
                  <input type="radio" class="disoption euro" name="disoption" value="€" checked>
                  <span class="cr">€</span>
                </label>
                <label class="fontsize-14 pl0 color333">
                  <input type="radio" class="disoption percent" name="disoption"  value="%">
                  <span class="cr">%</span>
                </label>
              </div>
            </div>
              <div class="px-3">
                <button type="button" id="dissave" class="btn btn-blue btn-large mt-4"><?php echo $this->lang->line('Save_btn'); ?></button>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->



    <!-- modal start -->
    <div class="modal fade" id="add-tip">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0">
            <h3 class="color333 fontfamily-medium font-size-24 border-b pb-3"><?php echo $this->lang->line('Add_Tip'); ?></h3>
            <div class="d-flex justify-content-between align-items-end pl-3 pr-3 pt-2">
              <div class="text-left">
                <div class="form-group mb-0">
                  <span class="label"><?php  echo $this->lang->line('tipamount'); ?> <span id="tipcalculationtext">(0.00%)</span></span>
                    <label class="inp">
                      <span class="rate-fixed" id="tipsign">€</span>
                      <input type="text" id="tipinputval" placeholder="&nbsp;" class="form-control">
                    </label>                                                
                </div>
              </div>
              <div class="radio rodio-select-tip-amount">
                <label class="fontsize-14 pl0 color333">
                  <input type="radio" class="tipoption" name="tipoption" value="€" checked>
                  <span class="cr">€</span>
                </label>
                <label class="fontsize-14 pl0 color333">
                  <input type="radio" class="tipoption" name="tipoption"  value="%">
                  <span class="cr">%</span>
                </label>
              </div>
            </div>
              <div class="px-3">
                <button type="button" id="tipsave" class=" btn btn-blue btn-large mt-4"><?php echo $this->lang->line('Save_btn'); ?></button>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->
    
    
     <!-- modal start -->
    <div class="modal fade" id="invoice_details">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0">
            <h3 class="color333 fontfamily-medium font-size-24 border-b pb-3"><?php echo $this->lang->line('Invoice_Details'); ?></h3>
            <div class="text-left pl-3 pr-3 pt-2">
			<!-- <div class="form-group">
			  <span class="label">Payment received by</span>
				<label class="inp">
				 <div class="btn-group multi_sigle_select open">
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" style="" aria-expanded="true">
								<?php echo $detail->first_name." ".$detail->last_name;  ?>
							</button>
                            <ul class="dropdown-menu mss_sl_btn_dm" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 44px, 0px);">
								
					   <?php if(!empty($allemployee)){
							   foreach($allemployee as $emp){ ?>		
							     <li class="radiobox-image"><input type="radio" id="idq_<?php echo $emp->id; ?>" name="pay_recieved_by" class="pay_recieved_by" value="<?php echo url_encode($emp->id); ?>" <?php if($detail->employee_id==$emp->id) echo 'checked'; ?>><label for="idq_<?php echo $emp->id; ?>"><?php echo $emp->first_name.' '.$emp->last_name; ?></label></li>
						<?php } } ?>
						
                                                                
                            </ul>
                        </div>
				</label>                                                
			</div> -->
			<div class="form-group">
			  <span class="label"><?php echo $this->lang->line('Invoice_notes'); ?></span>
				<label class="inp">
				  <input type="text" id="invoicedetailinput" placeholder="&nbsp;" class="form-control">
				</label>                                                
			</div>
            </div>
              <div class="px-3">
                <button type="button" id="invoicedetailsave" class=" btn btn-blue btn-large mt-3"><?php echo $this->lang->line('Save_btn'); ?></button>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->
    
    
    

    <!-- modal start -->
    <div class="modal fade" id="select-item-modal">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a href="#" class="crose-btn" data-dismiss="modal">
          <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-4 mb-30 px-0">
			     
             <a href="JavaScript:Void(0);" class="display-n backbtnpup">
              <img src="<?php echo base_url('assets/frontend/images/arrow-angle-pointing-to-right.svg'); ?>" class="back-btn-check" >
            </a>
            <h3 class="color333 fontfamily-medium font-size-24 border-b pb-3">
              <span class="select-cat-none" id="hedertextpup"><?php echo $this->lang->line('Select_Category'); ?></span>
            
            </h3>
            
            <div class="pl-3 pr-3 pt-2">
              <div class="mb-3 relative">
                <input type="text" id="seachServiceval" class="pl-30" placeholder="<?php echo $this->lang->line('Search'); ?>" style="padding-left: 30px;">
                <img class="search-icon-check" src="<?php echo base_url("assets/frontend/images/search-new.svg"); ?>">
              </div>
              <div id="setservicehtml"> </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->
    
  <?php $this->load->view('frontend/common/footer_script'); ?>


    <script type="text/javascript">
      $(function() {
        $(window).on("scroll", function() {
            if($(window).scrollTop() > 90) {
                $(".header").addClass("header_top");
            } else {
                //remove the background property so it comes transparent again (defined in your css)
               $(".header").removeClass("header_top");
            }
        });
      });
     /* $(document).ready(function(){
        alert(window.history.go(-2));
      })*/


      
        $(document).on('click','.cashSlide',function(){
			if($("#checkoutForm").valid()){
      $("#payment_type").val($(this).data('val'));
            $('.checkout-slide-content').addClass('add-slide');
		   }else return false;
        });
        $(document).on('click','.close-side-slide',function(){
            $('.checkout-slide-content').removeClass('add-slide');
        });
        $(document).on('click','#tipaddedtxt',function(){
			setTimeout(function(){ 
				$('#tipinputval').focus();
				},500);
            
        });
        
      
   
      $(document).ready(function(){
        $('.checkout-crose-icon').mouseenter(function(){
          $(this).css("transform", "rotate(90deg)");
        });
        $('.checkout-crose-icon').mouseleave(function(){
          $(this).css("transform", "rotate(0deg)");
        });
        
      });

      $.fn.textWidth = function(text, font) {    
      if (!$.fn.textWidth.fakeEl) $.fn.textWidth.fakeEl = $('<span>').hide().appendTo(document.body);
      $.fn.textWidth.fakeEl.text(text || this.val() || this.text() || this.attr('placeholder')).css('font', font || this.css('font'));    
          return $.fn.textWidth.fakeEl.width();
      };
      $('.width-dynamic').on('input', function() {
          var inputWidth = $(this).textWidth();
        $(this).css({
            width: inputWidth
        })
      }).trigger('input');
      function inputWidth(elem, minW, maxW) {
          elem = $(this);
          console.log(elem)
      }
      var targetElem = $('.width-dynamic');
      inputWidth(targetElem);

      // 
        $(document).ready(function(){ 
			
		function calculation(){
      var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
			  loading();
				
				$.ajax({
				   type: "POST",
				   url:base_url+"checkout/calculation",
				   data:$("#checkoutForm").serialize(),
				   success: function (response) {
					  unloading();
						var obj = $.parseJSON( response );
						if(obj.success==1){
							  unloading();							
								
							  $("#TaxSubtoal").html(obj.html);    
							  $("#totaltext").text(obj.total+' €');   
							  $("#totalPayInput").val(obj.paytotal.split('.').join(''));   
							  $("#balanceText").text(obj.paytotal+' €');  
							  $("#totaltext").attr('data-total',obj.total); 
							  
							  if(obj.discount>0){
								 $("#discounttext").removeClass('display-n');
								 $("#discount-sec").removeClass('display-n');
								 $("#discountamount").val(obj.discount); 
								 //$("#discountamounttext").text(obj.discount.toFixed(2)+' €'); 
                 $('#discountamounttext').text($('#discountamount').val()+' €')

								  
								}else{
								 $("#discounttext").addClass('display-n');
								 $("#discount-sec").addClass('display-n');
								 $("#discountamount").val(obj.discount); 
								 $("#discountamounttext").text(obj.discount+' €'); 
                 $('#discountamounttext').text($('#discountamount').val()+' €')
									
									}  
							    
						}else{
							
							alert('Try again');
							
						}
						
				   }
				});
			
			}	
		
			
			
			
		function get_service(serach=""){
			
			  loading();
				var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
				$.ajax({
				   type: "POST",
				   url:base_url+"checkout/get_all_services",
				   data:{search:serach},
				   success: function (response) {
					  unloading();
						var obj = $.parseJSON( response );
						if(obj.success==1)
						 {
							  unloading();
							 								
							  $("#setservicehtml").html(obj.html);    
							  $("#select-item-modal").modal('show');   
							   if(serach!="")
							     {
								   $('.select-cat-check').css('display','none');
								   $('.select-cat-inner-check').css('display','flex');																	
								 } 
							     
						 }else{
							
							 alert('Try again');
							
						 }
						
				   }
				});
			
			}	
		
		$(document).on('keyup','#tipinputval',function(){
			tipcalcalation();
		 });
		 
		 $(document).on('change','.tipoption',function(){
			tipcalcalation();
		 });
		 
		  $(document).on('click','#tipsave',function(){
			tipcalcalation('yes');
		 });
		
		 $(document).on('click','#tipdelete',function(){
			 $("#tip-sec").addClass('display-n');
			 $("#tipamount").val('0');
			 $("#tipamounttext").text('€0');
			 $("#tipaddedtxt").text('+ <?php echo $this->lang->line("Add_Tip"); ?>');
			 $("#tipinputval").val('0');
			 $("#tipcalculationtext").text('(€00)');
			 calculation();
		 });
		 
		 
		
		function tipcalcalation(save=""){			
			  var total  = $("#getSubtotal").attr('data-val');
			  var tipopt = $("input[name='tipoption']:checked").val();
			  var tipval = onlydotvalreturn($("#tipinputval").val());
			  var employee =$("input[name='changed_employee']:checked").attr('data-name');
			  if(tipval=="" && save=="yes"){
				  $("#add-tip").modal('hide'); return false;
				  }
			  
			  if(tipopt=="%"){
				  
				  $("#tipsign").text("%");
				  var tipval = (total*tipval)/100;
				  $("#tipcalculationtext").text('(€'+strreplacejs(tipval.toFixed(2))+')');
				  
				  if(save=="yes"){
					  $("#tip-sec").removeClass('display-n');
					  $("#tipamount").val(tipval);
					  $("#tipamounttext").text(strreplacejs(tipval.toFixed(2))+' €');
					  $("#tipaddedtxt").html('<?php echo $this->lang->line("tip_for"); ?> <span class="empnamerplc">'+employee+'</span>');
					  $("#add-tip").modal('hide');
					  calculation();					  
					  }
				   //alert('prcent');
				   }
			  else{ 
				    $("#tipsign").text("€");
				    var tipvalEr=tipval;
				    var tipval = (tipval*100)/total;
				    $("#tipcalculationtext").text('('+strreplacejs(tipval.toFixed(2))+'%)');
				    
				    if(save=="yes"){
					  $("#tip-sec").removeClass('display-n');
					  $("#tipamount").val(tipvalEr);
					  $("#tipamounttext").text(strreplacejs(tipvalEr)+' €');
					  $("#tipaddedtxt").html('<?php echo $this->lang->line("tip_for"); ?> <span class="empnamerplc">'+employee+'</span>');
					  $("#add-tip").modal('hide');
					  calculation();					  					  
					  }
				    
				    //alert('euro');
				  }
			
			}
			
			
		// discount popup open	
	    $(document).on('click','.addDiscountperticulerService',function(){
			var id    = $(this).attr('data-id');
			var value = $(this).attr('data-amount');
						
			$("#totalserviceprice").val(value);
			$("#dicountServiceCount").val(id);
            $("#disinputval").val('');            
            $("#add-dicount").modal('show');
            $(".euro").prop('checked',true);
            $("#dissign").text('€');
            $("#discalculationtext").text('(0%)');
            
           setTimeout(function(){
               $("#disinputval").focus(); 
	       },500);
			
	   	});
			
		// discount input check on keyup and calculate	
		$(document).on('keyup','#disinputval',function(){
			  var total  = $("#totalserviceprice").val();
			  var disopt = $("input[name='disoption']:checked").val();
			  var val    = $("#disinputval").val();
			if(disopt=='%'){
				if(Number(val)>100)
				   {
						$("#disinputval").val(
						  function(index, value){
						     return value.substr(0, value.length - 1);
						});


				     return false;
				   }
				} 
			else{
				if(Number(val)>total)
				   {
					   $("#disinputval").val(
						  function(index, value){
						     return value.substr(0, value.length - 1);
						});
						
					   return false;
				   }
				}	 
			discountcalcalation();
		 });
		 
		 // change discount option and calculate
		 	
		 $(document).on('change','.disoption',function(){
			discountcalcalation();
		 });
		 
		 // save discount and re-calculate all value
		  $(document).on('click','#dissave',function(){
			discountcalcalation('yes');
		 });
			
			
		// discount calculateion on save click in poup	
		function discountcalcalation(save=""){			
			  var total  = $("#totalserviceprice").val();
			  var disopt = $("input[name='disoption']:checked").val();
			  var disval = onlydotvalreturn($("#disinputval").val());
			  
			   if(disval=="" && save=="yes"){
				  $("#add-dicount").modal('hide'); return false;
				  }
			  var dis = $('#discountamount').val();
        var txt = $('#discountamounttext').text();
       // alert(dis);
        //alert(txt);
        //$('#discountamounttext').text($('#discountamount').val()+' €')
			  if(disopt=="%"){				  
				  $("#dissign").text("%");
				  var disvaltext=disval;
				  var disval = (total*disval)/100;
				  $("#discalculationtext").text('(€'+strreplacejs(disval.toFixed(2))+')');
				  
				   if(save=="yes"){
					 var id = $("#dicountServiceCount").val();
					// alert(id);
					//alert('1');
					  $(".discountOpt"+id).addClass('display-n');
					  $(".dicounttext"+id).html('Rabatt: : '+strreplacejs(disval.toFixed(2))+' €<img class="width12 ml-2 discountdelete" style="cursor: pointer;margin-left:auto;" data-id="'+id+'" src="<?php echo base_url("assets/frontend/images/crose_grediant_orange_icon1.svg"); ?>">');
					  $(".discount"+id).val(disval);
					  $("#add-dicount").modal('hide');
					  if($(".tax"+id).attr('data-taxrate')!=undefined){
						     var taxrate     = Number($(".tax"+id).attr('data-taxrate'));
						    var remaitoatal = Number(total)-Number(disval);
						 
						   var taxcalculated = (remaitoatal*100/(100+taxrate))*taxrate/100;
						   //alert(taxcalculated);
			                 $(".tax"+id).val(taxcalculated);
			                 $(".tax_text"+id).text(strreplacejs(taxcalculated.toFixed(2)));
						 // alert('11');
						  }
					  calculation();					  					  
					  }
				   //alert('prcent');
				   }
			  else{ 
				    $("#dissign").text("€");
				    var disvalEr=disval;
				    var disval = (disval*100)/total;
				    $("#discalculationtext").text('('+strreplacejs(disval.toFixed(2))+'%)');
				    
				     if(save=="yes"){
					 var id = $("#dicountServiceCount").val();
					//alert(disvalEr);
					//alert('2');
					  $(".discountOpt"+id).addClass('display-n');
					  $(".dicounttext"+id).html('Rabatt : '+strreplacejs(disvalEr)+' €<img class="width12 ml-2 discountdelete" style="cursor: pointer;margin-left:auto;" data-id="'+id+'" src="<?php echo base_url("assets/frontend/images/crose_grediant_orange_icon1.svg"); ?>">');
					  $(".discount"+id).val(disvalEr);
					  
					  if($(".tax"+id).attr('data-taxrate')!=undefined){
						     var taxrate     = Number($(".tax"+id).attr('data-taxrate'));
						    var remaitoatal = Number(total)-Number(disvalEr);
						// alert(disvalEr);
						   var taxcalculated = (remaitoatal*100/(100+taxrate))*taxrate/100;
						   //alert(taxcalculated);
						   $(".tax"+id).val(taxcalculated);
						   $(".tax_text"+id).text(strreplacejs(taxcalculated.toFixed(2))
						   
						   );
						   //alert(id);
						  }
					  
					 // $("#discounttext").text('Dicount '+disval+'%');
					  $("#add-dicount").modal('hide');
					  calculation();				
            $('#discountamounttext').text($('#discountamount').val()+' €');	  					  
					  }
				    
				    //alert('euro');
				  }
          $('#discountamounttext').text($('#discountamount').val()+' €');

			}	
			
			
			
		  $(document).on("click",".discountdelete",function(){
			   var id = $(this).attr('data-id');
			   
			  $(".discountOpt"+id).removeClass('display-n');
			  $(".dicounttext"+id).html('');
			  $(".discount"+id).val('0');
			  
			 var total = $(".discountOpt"+id).attr('data-amount');
			  
			  if($(".tax"+id).attr('data-taxrate')!=undefined){
					 var taxrate     = Number($(".tax"+id).attr('data-taxrate'));
					var remaitoatal = Number(total);
				 
				   var taxcalculated = (remaitoatal*100/(100+taxrate))*taxrate/100;
				   //alert(taxcalculated);
				   $(".tax"+id).val(taxcalculated);
				   $(".tax_text"+id).text(taxcalculated.toFixed(2));
				  }
			 calculation();			  
			});
			 	
			
	      $("#addmoreservice").click(function(){			  			  
				get_service();			  
		  });
		  
		  $(document).on('keyup','#seachServiceval',function(){
			   var serach = $(this).val();			 
			   get_service(serach);
			  
			});
			
		  $(document).on('click','.removeServiceCross',function(){
			   var id=$(this).data('id');
			   $("."+id).remove();
			    calculation(); 
			  
		  });
		
			
		 $(document).on('click','#invoicedetailsave',function(){
			 
			 $("#payreicevedby").val($("input[name='pay_recieved_by']:checked").val());
			 $("#invoicedetailval").val($("#invoicedetailinput").val());
			 $("#invoice_details").modal('hide');
			  
		  });	
			
		    $(document).on('click','.selectCategory',function(){
			 var arr   = $(this).data();
			 var ename = $("input[name='changed_employee']:checked").attr('data-name');
			 var count = $("#accordion").attr('data-count');
			 //alert(count);
       var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);	 
			   loading();				
				$.ajax({
				   type: "POST",
				   url:base_url+"checkout/add_more_service",
				   data:{service_id:arr.sid,count:count,name:arr.name,duration:arr.duration,employee_name:ename,price:arr.price,tax_id:arr.tax},
				   success: function (response) {
					  unloading();
						var obj = $.parseJSON(response);
						if(obj.success==1){
							  unloading();
								
							  $("#accordion").append(obj.html);    
							  $("#accordion").attr('data-count',obj.count);   
							  $("#select-item-modal").modal('hide');
							  $("#seachServiceval").val('');
							  calculation(); 
						}else{
							//~ revertFunc();
							//~ Swal.fire(
								  //~ obj.msg,
								  //~ 'warning'
								//~ );
							alert('Try again');
							//$(this).addClass("unblock");    
							//$(this).removeClass("partialblock");
						  }
						
				        }
				   });
			  
			   });
         
         
      $(document).on('keyup','.priceInputChange',function(){
				//alert('df');  
			$('.check_ab'+$(this).attr('data-number')).text('');	    
        if(onlydotvalreturn($(this).val())>=0)
				   {
             var thisvl = Number(onlydotvalreturn($(this).val()));
             var number = $(this).attr('data-number');
             var checkdiscount = Number($('.discount'+number).val());

            if(checkdiscount!=0 && thisvl<checkdiscount){
             
              $(this).val($('.discountOpt'+number).attr('data-amount'));
              return false;

             }          

              $('.discountOpt'+number).attr('data-amount',thisvl);
              $('.pricementax_'+number).text(strreplacejs(thisvl.toFixed(2))+' €');
           
            var checktx =$('.tax'+number).val();
            if(checktx!=undefined){
              var taxRate = Number($('.tax'+number).attr('data-taxrate'));
              var taxcalculated = (thisvl*100/(100+taxRate))*taxRate/100;
              
              $('.tax_text'+number).text(strreplacejs(taxcalculated.toFixed(2))+' €');
              $('.tax'+number).val(taxcalculated.toFixed(2));
               //alert(taxcalculated+'=='+thisvl+'=='+taxRate);                                                             
            }
				    calculation();
			     }else{
            $(this).val('0');
           }
				});		
			       
          $(document).on('click','.select-cat-check', function(){
			 // alert('dsf');
            $('.select-cat-check').css('display','none');
           var id=$(this).attr('data-id');
           var txt=$(this).attr('data-text');
            $('#hedertextpup').text(txt);
            $('.select-cat-inner-check').css('display','none');
            $('.backbtnpup').removeClass('display-n');
            $('.select-cat-inner-check'+id).css('display','flex');
          });
          
          $(document).on('click','.backbtnpup', function(){
            $('.select-cat-inner-check').css('display','none');
            $('.backbtnpup').addClass('display-n');
            $('#hedertextpup').text('<?php echo $this->lang->line('Select_Category'); ?>');
            $('.select-cat-check').css('display','flex');
          });
        
        calculation()
        
        });
      $(document).on('click','#complete_sale', function(){
         loading();
        });
      $(document).on('change','.chngeemployee',function(){
         var employee =$("input[name='changed_employee']:checked").attr('data-name');
         $('span.empnamerplc').text(employee);
      });

    </script>
 
 
