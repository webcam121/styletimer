   <?php $this->load->view('frontend/common/head'); ?>
   
    <body id="chg_hover" class="bglightgreen1">
      <div class="checkout-new-page">
          <div class="full-popup ">
              <a href="javascript:history.go(-1)" class="crose-btn">
              <picture class="popup-crose-black-icon">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
                <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
                <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
              </picture>
              </a>
              <div class="text-center bgwhite">
                <h4 class="fontfamily-semibold font-size-24 color333 pt-3 pb-3 border-b mb-0"><?php echo $this->lang->line('View_Invoice'); ?>
                </h4>
              </div>  
              <div class="container-fluid">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 m-auto">
                    <div class="relative around-20 ">
                      <div class="" style="width: 500px;margin: auto;background: #fff;border:1px solid #e8e8e8;padding-top:30px;padding-bottom:30px;">
                        <table style="width:440px;margin:auto;background: #fff;">
					<?php  if(!empty($invoice_detail->tip)){  ?>
                          <tr>
                            <th colspan="2" style="text-align: center;border-bottom:1px solid #e8e8ee;">
								<img src="<?php echo base_url('assets/frontend/images/heartinvoice.png'); ?>" style="width:44px;height:44px;margin-right:10px;display:inline-block;
								vertical-align: sub;
								"><h6 class="fontfamily-semibold fontsize-20 color666 d-inline-block text-left">Vielen Dank für's Trinkgeld,<br><?php if($invoice_detail->booking_type=='guest'){ echo $invoice_detail->fullname; } else {
									if(!empty($booking_detail[0]->first_name)) echo $booking_detail[0]->first_name;
									}   ?>!</h6>
								</th>
							</tr>
						<?php } ?>
                          <tr>
                            <th colspan="2" style="text-align: center;position:relative;">
                              <h6 class="fontfamily-semibold fontsize-18 color333">Invoice #<?php if(!empty($invoice_detail->book_id)) echo $invoice_detail->book_id; ?></h6>
                              <p class="fontfamily-regular fontsize-14 color666"><?php if(!empty($invoice_detail->created_on)) echo date('l, d M Y',strtotime($invoice_detail->created_on)); ?></p>
                              <a href="<?php echo base_url('checkout/printinvoice/'.url_encode($invoice_detail->id)); ?>" target="_blank" style="position: absolute;top: 0;right: 0"><img src="<?php echo base_url('assets/frontend/images/printer.svg'); ?>" style="width:24px;"></</a>
                            </th>
                          </tr>
                          
                       

                          <tr style="border-top:1px solid #e8e8ee;">
                            <td colspan="2" style="text-align: center;padding:20px 0px 0px 0px ;">
                              <h5 style="font-size: 18px;color: #000000;font-family: 'Poppins-Semibold';"><?php echo $this->lang->line('Booking_Customer_Information'); ?></h5>
                            </td>
                          </tr>

                          <tr style="">
                            <td style="vertical-align: top;padding:10px 0px">
                              <span style="font-size: 16px;color: #000000;font-family: 'Poppins-regular';"><?php echo $this->lang->line('Customer_Information'); ?></span><br/>
                              <h6 style="font-size: 16px;color: #000000;font-family: 'Poppins-Semibold';margin-bottom: 0.25rem;margin-top: 0.5rem;"><?php if($invoice_detail->booking_type=='guest'){ echo $invoice_detail->fullname; } else {
								if(!empty($booking_detail[0]->first_name)) echo $booking_detail[0]->first_name.' '.$booking_detail[0]->last_name;
								}   ?></h6>
							<?php if($invoice_detail->booking_type=='user'){ ?>	
                             <p style="font-size: 14px;font-family: 'Poppins-Medium';color: #333333;margin: 0px;"><?php  echo $booking_detail[0]->address; ?> <br><?php  echo $booking_detail[0]->zip.' '.$booking_detail[0]->city.' </br>'.$booking_detail[0]->country; ?></p>
                              <?php } ?>
                              <p style="font-size: 14px;font-family: 'Poppins-Medium';color: #333333;margin-bottom:0rem;"><?php if($invoice_detail->booking_type=='guest'){ if(!empty($invoice_detail->email))echo $invoice_detail->email; }else{ echo $booking_detail[0]->email; } ?> </p>
                            </td>
                            <td style="vertical-align: top;padding:10px 0px;text-align: right;width:190px;">
                              <span style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';">Total Duration : <span style="color: #999999;"> <?php echo $invoice_detail->total_time; ?> Mins</span></span>
                            </td>
                          </tr>

                          <tr>
                            <td colspan="2">
                              <p style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Booked_id'); ?> : <span style="color: #333333;"> <?php echo $invoice_detail->book_id; ?></span></p>
                              <p style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Booked_via'); ?>  : <span style="color: #333333;"><?php if($invoice_detail->book_by>=1) echo 'App'; else echo 'Web'; ?>  </span></p>
                              <p style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Booking_Date'); ?> : <span style="color: #333333;"><?php echo date('d M Y , H:i',strtotime($invoice_detail->booking_time)); ?></span></p>
                              
                              <p style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Completed_Date'); ?> : <span style="color: #333333;"><?php echo date('d M Y , H:i',strtotime($invoice_detail->updated_on)); ?> </span></p>
<!--
                              <p style="font-size: 12px;color: #999999;font-family: 'Poppins-regular';margin:0rem;">Service : <span style="color: #333333;">  Coloring</span>
-->
                            </td>
                          </tr>

                          <tr style="">
                            <td style="vertical-align: top;padding:10px 0px" colspan="2">
                              <span style="font-size: 16px;color: #000000;font-family: 'Poppins-regular';"><?php echo $this->lang->line('SalonName_addresh'); ?></span><br/>
                              <h6 style="font-size: 16px;color: #000000;font-family: 'Poppins-Semibold';margin-bottom: 0.25rem;margin-top: 0.5rem;"><?php if(!empty($slondetail->business_name)) echo $slondetail->business_name; ?></h6>
                              <p style="font-size: 14px;font-family: 'Poppins-Medium';color: #333333;margin: 0px;"><?php  echo $slondetail->address; ?> <br><?php  echo $slondetail->zip.' '.$slondetail->city.'</br>'.$slondetail->country; ?></p>
                            </td>
                          </tr>
                          <tr style="border-top:1px solid #e8e8ee;">
							<td colspan="2" style="padding-top:10px;">
								<span style="font-size: 16px;color: #000000;font-family: 'Poppins-regular';"><?php echo $this->lang->line('Booking_Information'); ?></span>
							</td>
                          </tr>
					    <?php if(!empty($booking_detail)){
                foreach($booking_detail as $row){ ?>
                          <tr style="">
                            <td style="vertical-align: middle;padding:10px 0px;width:50%;">
                              <span class="" style="font-size: 14px;color: #666;">1 <?php echo $this->lang->line('Item'); ?></span>
                              <p class="" style="text-transform:capitalize;color:#333;font-size:16px;margin:0px;"><?php echo $row->service_name; ?></p>
                              <span class="" style="font-size: 14px;color: #666;"><?php //echo date('H:i, d M Y',strtotime($row->setuptime_start)) ?> <?php echo $this->lang->line('with'); ?> <?php echo $invoice_detail->first_name; ?></span>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:10px 0px">

                              <?php if(!empty($row->discount_price)){ ?>
                                 <del style="color:#666;font-size:14px;"><?php echo $row->price+$row->discount_price;  ?>€</del>
                              <?php } ?>
                              <p style="color:#333;font-size:16px;margin:0px;"><?php echo $row->price; ?>€</p>
                              
                              <span><?php if(!empty($row->tax)){ echo 'included '.$row->tax; } ?></span>
                            </td> 
                          </tr>
                          
                        <?php }   } ?>
                          
                          <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px 5px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo $this->lang->line('Subtotal'); ?></p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px 5px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->subtotal)) echo $invoice_detail->subtotal; ?>€</p>
                            </td>
                          </tr>
                       
                       <?php if(!empty($invoice_detail->taxes)){ $taxes=json_decode($invoice_detail->taxes);
               foreach($taxes as $k=>$v){
                 if(!empty($k) && !empty($v)){ ?>
                          <tr>
                            <td style="vertical-align: middle;padding:5px 0px 15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo $k; ?></p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:5px 0px 15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo round($v,2); ?>€
                              </p>
                            </td>
                          </tr>
                        <?php } } } if(!empty($invoice_detail->discount)){ ?>
              
              <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo $this->lang->line('Discount'); ?></p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->discount)) echo $invoice_detail->discount; ?>€
                              </p>
                            </td>
                          </tr>
                        
                        
                         <?php }
                         $tip =0; 
                         if(!empty($invoice_detail->tip)){ 
                         $tip =$invoice_detail->tip; ?>
              
              <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo $this->lang->line('Tips'); ?></p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->tip)) echo $invoice_detail->tip.'€'; ?>
                              </p>
                            </td>
                          </tr>
                        
                        
                         <?php } ?>
                         
                          <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo $this->lang->line('Total'); ?></p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->total)) echo $invoice_detail->total-$tip; ?>€
                              </p>
                            </td>
                          </tr>
                          <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo $this->lang->line('Payment'); ?> <?php echo ucfirst($invoice_detail->payment_type); ?></p>
                              <span style="color:#666;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->created_on)) echo date('l, d.m.Y \a\t H:i',strtotime($invoice_detail->created_on)); ?></span>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->paytotal)) echo $invoice_detail->paytotal; ?>€
                              </p>
                            </td>
                          </tr>

                          <tr>
                            <td style="vertical-align: middle;padding:15px 0px;">
                              <p class="" style="color:#666;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->details)) echo $invoice_detail->details; ?></p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"></p>
                            </td>
                          </tr>
                        </table>
                        </div>
                    </div>
                  </div>

                 
            </div>    
          </div>
      </div>

    
        
  <?php $this->load->view('frontend/common/footer_script'); ?>
  <script>
  $(document).ready(function(){
	  
	   $(document).on("click","#sendEmailInvoice",function(){
		   var emailSend = $("#emailVal").val();
		   var invoiceId = $("#invoiceIdSendMail").val();
		   var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
			if(testEmail.test(emailSend)){
			 	
			   loading();
				$.ajax({
				   type: "POST",
				   url:base_url+"checkout/invoice_send_in_email",
				   data:{id:invoiceId,email:emailSend},
				   success: function (response) {
					  unloading();
						var obj = $.parseJSON( response );
						if(obj.success==1){
							$("#emailVal").val("");
							  unloading();
							   Swal.fire(
									  'Email Send',
									  obj.msg,
									  'success'
									);							  
							    
						}else{
							
							alert('Try again');
							
						}
						
				   }
				    });
				//alert('yes');
				}
			else{
				$("#emailError").html('Bitte gib eine gültige E-Mail Adresse ein');
				//alert('no');
				}
		  
		  });
  });
  </script>
