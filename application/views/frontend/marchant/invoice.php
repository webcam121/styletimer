   <?php $this->load->view('frontend/common/head'); ?>
   
    <body id="chg_hover" class="bglightgreen1">
		<span class="natification-count display-n" id="chgreview_count" style="display: none;">0</span>
      <div class="checkout-new-page">
          <div class="full-popup ">
            <?php 
             if(!empty($_SERVER['HTTP_REFERER'])){
              if(strpos($_SERVER['HTTP_REFERER'], 'checkout') !== false){
                    $url = "javascript:history.go(-2)";
                   // $this->session->set_flashdata('success','Buchung erfolgreich abgeschlossen.');
                   // print_r($this->session->flashdata()); die;
				}
              else{
                    $url = $_SERVER['HTTP_REFERER'];
                }    
                
              }
              else{
                $url = "javascript:history.go(-2)";
                 //$this->session->set_flashdata('success','Buchung erfolgreich abgeschlossen.');
                  //  print_r($this->session->flashdata()); die;
			}
                
                
            ?>
              <a href="<?php echo $url; ?>" class="crose-btn">
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
                  <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                    <div class="relative around-20 min-height89vh scroll-effect">
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
                            <th colspan="2" style="text-align: center;padding-top:10px;">
                              <h6 class="fontfamily-semibold fontsize-18 color333"><?php echo $this->lang->line('Invoice'); ?> #<?php if(!empty($invoice_detail->book_id)) echo $invoice_detail->book_id; ?></h6>
                              <p class="fontfamily-regular fontsize-14 color666"><?php if(!empty($invoice_detail->created_on)){ echo $this->lang->line(date('l',strtotime($invoice_detail->created_on))).', '.date('d.m.Y',strtotime($invoice_detail->created_on)); }?></p>
                            </th>
                          </tr>
                           <tr style="">
                            <td style="padding-bottom:10px;" colspan="2">
                              <!--<span style="font-size: 16px;color: #000000;font-family: 'Poppins-regular';"><?php echo $this->lang->line('SalonName_addresh1'); ?></span><br/>-->
                              <h6 style="font-size: 16px;color: #000000;font-family: 'Poppins-Semibold';margin-bottom: 0.25rem;margin-top: 0.5rem;"><?php if(!empty($slondetail->business_name)) echo $slondetail->business_name; ?></h6>
                              <p style="font-size: 14px;font-family: 'Poppins-Medium';color: #333333;margin: 0px;"><?php  echo $slondetail->address.' </br>'.$slondetail->zip.' '.$slondetail->city; ?></p>
                              <p style="font-size: 14px;font-family: 'Poppins-Medium';color: #333333;margin: 0px;"><?php if(!empty($slondetail->tax_number)) echo 'Steuernummer : '.$slondetail->tax_number; ?></p>
                            </td>
                          </tr>
                       

                          <tr style="border-top:1px solid #e8e8ee;">
                            <td colspan="2" style="text-align: center;padding:10px 0px 0px 0px ;">
                              <h5 style="font-size: 18px;color: #000000;font-family: 'Poppins-Semibold';"><?php echo $this->lang->line('Booking_Customer_Information'); ?></h5>
                            </td>
                          </tr>

                          <tr style="">
                            <td style="vertical-align: top;padding:10px 0px">
                              <!--<span style="font-size: 16px;color: #000000;font-family: 'Poppins-regular';"><?php echo $this->lang->line('Customer_Information'); ?></span><br/> -->
                              <h6 style="font-size: 16px;color: #000000;font-family: 'Poppins-Semibold';margin-bottom: 0.25rem;margin-top: 0.5rem;"><?php if($invoice_detail->booking_type=='guest'){ echo $invoice_detail->fullname; } else {
								if(!empty($booking_detail[0]->first_name)) echo $booking_detail[0]->first_name.' '.$booking_detail[0]->last_name;
								}   ?></h6>
							<?php if($invoice_detail->booking_type=='user'){ ?>	
                             <p style="font-size: 14px;font-family: 'Poppins-Medium';color: #333333;margin: 0px;"><?php  echo $booking_detail[0]->address; ?> <br><?php  echo $booking_detail[0]->zip.' '.$booking_detail[0]->city; ?></p>
                              <?php } ?>
                              <p style="font-size: 14px;font-family: 'Poppins-Medium';color: #333333;margin-bottom:0rem;"><?php if($invoice_detail->booking_type=='guest'){ if(!empty($invoice_detail->email))echo $invoice_detail->email; }else{ echo $booking_detail[0]->email; } ?> </p>
                            </td>
                            <td style="vertical-align: top;padding:10px 0px;text-align: right;width:190px;">
                              <span style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';"><?php echo $this->lang->line('Total_Duration'); ?> : <span style="color: #999999;"> <?php echo $invoice_detail->total_time; ?> Min.</span></span>
                            </td>
                          </tr>

                          <tr>
                            <td colspan="2" style="padding-bottom:10px;">
                              <p style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Booked_id'); ?> : <span style="color: #333333;"> <?php echo $invoice_detail->book_id; ?></span></p>
                              <p style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Booked_via'); ?> : <span style="color: #333333;"><?php if($invoice_detail->book_by>=1) echo 'App'; else echo 'Web'; ?>  </span></p>
                              <p style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Booking_Made'); ?> : <span style="color: #333333;"><?php echo date('d.m.Y , H:i',strtotime($invoice_detail->booking_time)); ?> Uhr</span></p>
                              
                              <p style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Completed_Date'); ?> : <span style="color: #333333;"><?php echo date('d.m.Y , H:i',strtotime($invoice_detail->updated_on)); ?> Uhr</span></p>
<!--
                              <p style="font-size: 12px;color: #999999;font-family: 'Poppins-regular';margin:0rem;">Service : <span style="color: #333333;">  Coloring</span>
-->
                            </td>
                          </tr>

                         
                          <!--<tr style="border-top:1px solid #e8e8ee;">
							<td colspan="2" style="padding:10px 0px;">
								<span style="font-size: 16px;color: #000000;font-family: 'Poppins-regular';"><?php echo $this->lang->line('Booking_Information'); ?></span>
							</td>
                          </tr> -->
					  <?php if(!empty($booking_detail)){
							  foreach($booking_detail as $row){ ?>
                          <tr style="">
                            <td style="vertical-align: middle;padding:10px 0px;width:50%;">
                              <span class="" style="font-size: 14px;color: #666;">1 <?php echo $this->lang->line('Position'); ?></span>
                              <p class="" style="text-transform:capitalize;color:#333;font-size:16px;margin:0px;">
                              <?php if($row->sub_name==$row->name){ echo $row->name; } else ?>
                            </p><p style="color:#333;font-size:16px;margin:0px;">
                             
                            <?php 
                            if($row->name){
                               echo $subNameService =  $row->sub_name.' - '.strtolower($row->name); 
                            }else{
                             echo $subNameService =  $row->sub_name; 
                            }
                            
                           

                                //  echo trim($subNameService,"-");
                             ?></p>
                              <span class="" style="font-size: 14px;color: #666;"><?php //echo date('\a\m d.m.Y, H:i \U\h\r',strtotime($row->setuptime_start)) ?> <?php echo $this->lang->line('with'); ?> <?php echo $invoice_detail->first_name; ?></span>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:10px 0px">

                              <?php if(!empty($row->discount_price)){ ?>
                                 <del style="color:#666;font-size:14px;"><?php echo price_formate($row->price+$row->discount_price);  ?> €</del>
                              <?php } ?>
                              <p style="color:#333;font-size:16px;margin:0px;"><?php echo price_formate($row->price); ?> €</p>
                              
                              <span><?php if(!empty($row->tax)) { echo $this->lang->line('included').' '.str_replace(':',': ',$row->tax); } ?></span>
                            </td> 
                          </tr>
                          
                        <?php }   } ?>
                          
                          <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px 5px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo $this->lang->line('subtotal'); ?></p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px 5px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->subtotal)) echo price_formate($invoice_detail->subtotal); else echo 0;?> €</p>
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
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo price_formate(round($v,2)); ?> €
                              </p>
                            </td>
                          </tr>
                        <?php } } } if(!empty($invoice_detail->discount)){ ?>
							
						  <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo $this->lang->line('Discount1'); ?></p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->discount)) echo price_formate($invoice_detail->discount); else echo 0;?> €
                              </p>
                            </td>
                          </tr>
                        
                        
                         <?php }
                         $tip =0; 
                         if(!empty($invoice_detail->tip)){ 
                         $tip =$invoice_detail->tip; ?>
							
						  <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;">Trinkgeld</p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->tip)) echo price_formate($invoice_detail->tip).' €'; else echo '0 €'; ?>
                              </p>
                            </td>
                          </tr>
                        
                        
                         <?php } ?>
                         
                          <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php echo $this->lang->line('Total'); ?></p>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->total)) echo price_formate($invoice_detail->total-$tip); else echo 0;?> €
                              </p>
                            </td>
                          </tr>
                          <tr style="border-top:1px solid #e8e8ee;">
                            <td style="vertical-align: middle;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;">Bezahlung : <?php if($invoice_detail->payment_type=='card') echo $this->lang->line('Card1'); else echo $this->lang->line(ucfirst($invoice_detail->payment_type));  ?></p>
                              <?php /*<span style="color:#666;font-size:14px;margin:0px;"><?php if(!empty($invoice_detail->created_on)){ echo 'am '.$this->lang->line(date('l',strtotime($invoice_detail->created_on))).date(', \d\e\n d.m.Y',strtotime($invoice_detail->created_on)).',<br/>'.date('\u\m H:i \U\h\r',strtotime($invoice_detail->created_on)); } ?></span>*/ ?>
                            </td>
                            <td style="vertical-align: middle;text-align: right;padding:15px 0px;">
                              <p class="" style="color:#333;font-size:16px;margin:0px;"><?php if(!empty($invoice_detail->paytotal)) echo price_formate($invoice_detail->paytotal); else echo 0;?> €
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

                  <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 pr-0">
                    <div class="bgwhite border-l min-height90vh scroll-effect">
                        <div class=" on-hover-div d-flex" style="align-items: center;">
                          <div class="display-ib mr-3 new-popup-img-text">
							  
							  <?php  $imgUrl = base_url('assets/frontend/images/user-blue-v-icon.png');
							    if(!empty($booking_detail[0]->profile_pic)){ $imgUrl = base_url('assets/uploads/users/'.$booking_detail[0]->user_id.'/'.$booking_detail[0]->profile_pic); } ?>

                            <img src="<?php echo $imgUrl; ?>" class="">
                          </div>
                          <div class="display-ib">
                            <p class="mb-0 color333 fontsize-16 fontfamily-medium"><?php if($invoice_detail->booking_type=='guest'){ echo $invoice_detail->fullname; } else {
								if(!empty($booking_detail[0]->first_name)) echo $booking_detail[0]->first_name.' '.$booking_detail[0]->last_name;
								}   ?></p>
                          </div>
                        </div>
                        <div class="d-flex w-100 min-height74vh">
                          <div class="relative w-100 text-center px-3 pt-4 bgwhite"><img class="width64" src="<?php echo  base_url('assets/frontend/images/checkmark.svg'); ?>">  
                              <h4 class="color333 fontsize-18 fontfamily-semibold mt-3 mb-3"><?php echo $this->lang->line('Completed'); ?></h4>
                              <span class="fontfamily-regular color666 fontsize-14"><?php if(!empty($invoice_detail->recievedByname)){ $name = $invoice_detail->recievedByname; }else{ $name = $invoice_detail->first_name.' '.$invoice_detail->last_name; } echo str_replace('*employee*',$name,$this->lang->line('Full_payment_received_on')); ?> <?php if(!empty($invoice_detail->created_on)){ echo $this->lang->line(date('l',strtotime($invoice_detail->created_on))).''.date(', \d\e\n d.m.Y \u\m H:i \U\h\r',strtotime($invoice_detail->created_on)); } ?> </span> 
                            
                            <div class="more-opciton-bottom-set ">
                              <div class="text-left m-auto" style="width:90%">
                                <span class="label"><?php echo $this->lang->line('Send_invoice'); ?></span>
                                <div class="input-group d-flex">
                                  <input type="text" class="form-control" id="emailVal" placeholder="<?php echo $this->lang->line('Email_address'); ?>" >
                                  <input type="hidden" class="form-control" id="invoiceIdSendMail" value="<?php echo url_encode($invoice_detail->id); ?>" placeholder="<?php echo $this->lang->line('Email_address'); ?>" >
                                  <button type="button" class="btn p-0 widthfit" id="sendEmailInvoice">
                                    <span class=" px-3"><?php echo $this->lang->line('Send'); ?></span>
                                  </button>
                                </div>
                                <span class="error" id="emailError"></span>
                              </div>  
                              <div class="d-flex align-items-center justify-content-around py-3 border-t mt-3">
                                <div class="display-ib dropdown dropdownMoreOption">
                                  <button class="btn widthfit dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $this->lang->line('More_Option'); ?></button>
                                  <ul class="dropdown-menu">
                                    
                                    <li>
                                      <a  class="dropdown-item" href="<?php echo base_url('merchant/dashboard/'.url_encode($invoice_detail->booking_id).'?id='.url_encode($invoice_detail->employee_id).'&option=rebook'); ?>"><?php echo $this->lang->line('Rebook_Appointment'); ?></a> 
                                    </li>
                                    <li>
                                      <a  class="dropdown-item" href="<?php echo base_url('checkout/printinvoice/'.url_encode($invoice_detail->id)); ?>/download"><?php echo $this->lang->line('Download'); ?></a> 
                                    </li> 
                                    <li>
                                      <a  class="dropdown-item" target="_blank" href="<?php echo base_url('checkout/printinvoice/'.url_encode($invoice_detail->id)); ?>"><?php echo $this->lang->line('Print'); ?></a> 
                                    </li> 
                                  </ul>
                                </div>
                                <a href="javascript:history.go(-2)"><button type="button" class="btn ml-0 mr-0 widthfit" style="width: 145px !important;"><?php echo 'Schliessen'; ?></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>    
          </div>
      </div>

    
        
  <?php $this->load->view('frontend/common/footer_script');
  //print_r($this->session->flashdata()); die;
   ?>
  <script>
  $(document).ready(function(){
	  
	   $(document).on("click","#sendEmailInvoice",function(){
		   var emailSend = $("#emailVal").val();
		   var invoiceId = $("#invoiceIdSendMail").val();
		   var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
			if(testEmail.test(emailSend)){
			 	$("#emailError").html('');
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
									  '<?php echo $this->lang->line("email_sended"); ?>',
									  '<?php echo $this->lang->line("invoice_send_successfully"); ?>',
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
				$("#emailError").html('Bitte trage ein gültiges E-Mail Format ein');
				//alert('no');
				}
		  
		  });
  });
  </script>
