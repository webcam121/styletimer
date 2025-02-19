<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>styletimer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <style>
      .color666{
        color:#666;
      }
      .color333{
        color:#333;
      }

      *,body{
        font-family: 'Poppins', sans-serif;
        font-weight:300;
        line-height:16px;
      }
      table tr td,
      table tr td p,
      table tr td span{
        vertical-align: top;
        font-family: 'Poppins', sans-serif;
        font-weight:300;
        line-height:16px;
      }
      @font-face {                    
        font-family: 'Poppins', sans-serif;        
        font-weight: normal;
        font-style: normal;
        src: url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
      }
      /* devanagari */
      @font-face {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/poppins/v15/pxiEyp8kv8JHgFVrJJbecmNE.woff2) format('woff2');
        unicode-range: U+0900-097F, U+1CD0-1CF6, U+1CF8-1CF9, U+200C-200D, U+20A8, U+20B9, U+25CC, U+A830-A839, U+A8E0-A8FB;
      }
      /* latin-ext */
      @font-face {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/poppins/v15/pxiEyp8kv8JHgFVrJJnecmNE.woff2) format('woff2');
        unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
      }
      /* latin */
      @font-face {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/poppins/v15/pxiEyp8kv8JHgFVrJJfecg.woff2) format('woff2');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      }
  </style>
</head>
 
<table style="width:450px;margin:0px auto;background: #fff;border:1px solid #e8e8e8;" cellpadding="0" cellspacing="0">
  <tr>
    <td style="padding:20px;width: 100%;">
    <table width="100%" style="width:100%;margin:0 auto;background: #fff;" cellpadding="0" cellspacing="0">
			<?php  if(!empty($invoice_detail->tip)){  ?>
        <tr>
          <th colspan="2" style="text-align: center;border-bottom:1px solid #e8e8ee;margin-top:0px;height: 90px;">
          <img src="<?php echo base_url('assets/frontend/images/heartinvoice.png'); ?>" style="width:44px;height:44px;margin-right:10px;display:inline-block;vertical-align: middle;margin-top: 0;">
          <h6 style="font-size:20px;font-weight:400;font-style:bold;color:#666;display:inline-block;text-align:left;padding-left:35px;margin-top: 0;margin-bottom: 0;line-height: 20px;padding-bottom: 0;">Vielen Dank für's Trinkgeld,<br><?php if($invoice_detail->booking_type=='guest'){ echo $invoice_detail->fullname; } else {
								if(!empty($booking_detail[0]->first_name)) echo $booking_detail[0]->first_name;
								}   ?>!</h6>
          </th>
        </tr>
        <?php } ?>
      <tr>
        <th colspan="2" style="text-align: center;">
          <h6 style="font-weight:400; font-size:18px; color:#333;margin:14px 0px 4px 0px;"><?php echo $this->lang->line('Invoice'); ?> #<?php if(!empty($invoice_detail->booking_id)) echo $invoice_detail->booking_id; ?></h6>
          <p style="font-size: 14px;font-weight:400;color: #666;margin:0px 0px 4px 0px;"><?php if(!empty($invoice_detail->created_on)){ echo $this->lang->line(date('l',strtotime($invoice_detail->created_on))).', '.date('d.m.Y',strtotime($invoice_detail->created_on)); }?></p>
        </th>
      </tr>
      <tr style="">
        <td style="padding-bottom:10px;" colspan="2">
          <!--<span style="font-size: 14px;color: #000000;font-family: 'Poppins-regular';"><?php echo $this->lang->line('SalonName_addresh1'); ?></span><br/>-->
          <h6 style="font-size: 12px;color: #000000;font-weight:400;margin-bottom: 0.25rem;margin-top: 0.5rem;"><?php if(!empty($slondetail->business_name)) echo $slondetail->business_name; ?></h6>
          <p style="font-size: 12px;font-weight:300;color: #333333;margin: 0px;line-height:16px;">
            <?php  echo $slondetail->address.' <br />'.$slondetail->zip.' '.$slondetail->city; ?></p>
          <p style="font-size: 12px;font-weight:400;color: #333333;margin: 0px;"><?php if(!empty($slondetail->tax_number)) echo 'Steuernummer : '.$slondetail->tax_number; ?></p>
        </td>
      </tr>
    

      <tr style="">
        <td colspan="2" style="text-align: center;padding:10px 0px 0px 0px ;border-top:1px solid #e8e8ee;">
          <h5 style="font-size: 16px;color: #000000;font-weight:400;margin:0px 0px;"><?php echo $this->lang->line('Booking_Customer_Information'); ?></h5>
        </td>
      </tr>

      <tr style="">
        <td style="vertical-align: top;padding:6px 0px">
        <!--  <span style="font-size: 14px;color: #000000;font-family: 'Poppins-regular';"><?php echo $this->lang->line('Customer_Information'); ?></span><br/>-->
          <h6 style="font-size: 14px;color: #000000;font-weight:400;margin: 0rem;"><?php if($invoice_detail->booking_type=='guest'){ echo $invoice_detail->fullname; } else {
								if(!empty($booking_detail[0]->first_name)) echo $booking_detail[0]->first_name.' '.$booking_detail[0]->last_name;
								}   ?></h6>
							<?php if($invoice_detail->booking_type=='user'){ ?>	
                <p style="font-size: 12px;font-family: 'Poppins-Medium';color: #333333;margin: 0px;margin-top:3px;"><?php  echo $booking_detail[0]->address; ?> <br><?php  echo $booking_detail[0]->zip.' '.$booking_detail[0]->city; ?></p>
                <?php } ?>
                <p style="font-size: 12px;font-family: 'Poppins-Medium';color: #333333;margin-top:3px;margin-bottom:0rem;"><?php if($invoice_detail->booking_type=='guest'){ if(!empty($invoice_detail->email))echo $invoice_detail->email; }else{ echo $booking_detail[0]->email; } ?> </p>
              </td>
              <td style="vertical-align: top;padding:6px 0px;text-align: right;width:190px;">
                <span style="font-size: 14px;color: #999999;font-family: 'Poppins-regular';margin:0px;"><?php echo $this->lang->line('Total_Duration'); ?> : <span style="color: #999999;"> <?php echo $invoice_detail->total_time; ?> Min.</span></span>
              </td>
            </tr>

            <tr>
              <td colspan="2" style="padding-bottom:8px;">
                <p style="font-size: 12px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Booked_id'); ?> : <span style="color: #333333;"> <?php echo $invoice_detail->book_id; ?></span></p>
                <p style="font-size: 12px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Booked_via'); ?> : <span style="color: #333333;"><?php if($invoice_detail->book_by>=1) echo 'App'; else echo 'Web'; ?>  </span></p>
                <p style="font-size: 12px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Booking_Made'); ?> : <span style="color: #333333;"><?php echo date('d.m.Y , H:i',strtotime($invoice_detail->booking_time)); ?></span></p>
                
                <p style="font-size: 12px;color: #999999;font-family: 'Poppins-regular';margin:0rem;"><?php echo $this->lang->line('Completed_Date'); ?> : <span style="color: #333333;"><?php echo date('d.m.Y , H:i',strtotime($invoice_detail->updated_on)); ?> </span></p>

              </td>
            </tr>
                          
					  <?php if(!empty($booking_detail)){
							  foreach($booking_detail as $row){ ?>
                <tr style="">
                  <td style="vertical-align: middle;padding:6px 0px;">
                    <span style="font-size: 14px;color: #666;">1 <?php echo $this->lang->line('Position'); ?></span>
                    <p style="color:#333;font-size:14px;margin:0px;"><?php 
                    if($row->sub_name==$row->name)
                    { echo $row->name; }
                     else echo $row->sub_name.' - '.$row->name; ?></p>
                    <span style="font-size: 14px;color: #666;"><?php //echo date('\a\m d.m.Y, H:i \U\h\r',strtotime($row->setuptime_start)) ?> <?php echo $this->lang->line('with'); ?> <?php echo $invoice_detail->first_name; ?></span>
                  </td>
                  <td style="vertical-align: middle;text-align: right;padding:6px 0px">

                    <?php if(!empty($row->discount_price)){ ?>
                        <del style="color:#666;font-size:14px;"><?php echo price_formate($row->price+$row->discount_price);  ?> €</del>
                    <?php } ?>
                    <p style="color:#333;font-size:14px;margin:0px;"><?php echo price_formate($row->price); ?> €</p>
                    
                    <span style="font-family: 'Poppins-Regular';color: #666666;font-size: 12px;"><?php if(!empty($row->tax)) { echo $this->lang->line('included').' '.str_replace(':',': ',$row->tax); } ?></span>
                  </td> 
                </tr>
                
              <?php }   } ?>
                
                <tr >
                  <td colspan="2" style="border-top:1px solid #e8e8ee;">
                    <table style="width:100%;">
                      <tr>
                        <td style="vertical-align: middle;padding:6px 0px 0px 0px;">
                          <p style="color:#333;font-size:14px;margin:0px;"><?php echo $this->lang->line('subtotal'); ?></p>
                        </td>
                        <td style="vertical-align: middle;text-align: right;padding:6px 0px 0px 0px;">
                          <p style="color:#333;font-size:14px;margin:0px;"><?php if(!empty($invoice_detail->subtotal)) echo price_formate($invoice_detail->subtotal); ?> €</p>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              
              <?php if(!empty($invoice_detail->taxes)){ $taxes=json_decode($invoice_detail->taxes);
      foreach($taxes as $k=>$v){
        if(!empty($k) && !empty($v)){ ?>
                <tr>
                  <td style="vertical-align: middle;padding:0px 0px 6px 0px;">
                    <p style="color:#333;font-size:14px;margin:0px;"><?php echo $k; ?></p>
                  </td>
                  <td style="vertical-align: middle;text-align: right;padding:0px 0px 6px 0px;">
                    <p style="color:#333;font-size:14px;margin:0px;"><?php echo price_formate($v); ?> €
                    </p>
                  </td>
                </tr>
              <?php } } } if(!empty($invoice_detail->discount)){ ?>

                <tr >
                  <td colspan="2" style="border-top:1px solid #e8e8ee;">
                    <table style="width:100%;">
                      <tr>
                        <td style="vertical-align: middle;padding:8px 0px;">
                          <p style="color:#333;font-size:14px;margin:0px;"><?php echo $this->lang->line('Discount1'); ?></p>
                        </td>
                        <td style="vertical-align: middle;text-align: right;padding:8px 0px;">
                          <p style="color:#333;font-size:14px;margin:0px;"><?php if(!empty($invoice_detail->discount)) echo price_formate($invoice_detail->discount); ?> €
                          </p>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
							
						 
            
            
              <?php }
              $tip =0; 
              if(!empty($invoice_detail->tip)){ 
              $tip =$invoice_detail->tip; ?>

              <tr >
                <td colspan="2" style="border-top:1px solid #e8e8ee;">
                  <table style="width:100%;">
                    <tr>
                      <td style="vertical-align: middle;padding:8px 0px;">
                        <p style="color:#333;font-size:14px;margin:0px;">Trinkgeld</p>
                      </td>
                      <td style="vertical-align: middle;text-align: right;padding:8px 0px;">
                        <p style="color:#333;font-size:14px;margin:0px;"><?php if(!empty($invoice_detail->tip)) echo price_formate($invoice_detail->tip).' €'; ?>
                        </p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
						
          
          
            <?php } ?>

              <tr >
                <td colspan="2" style="border-top:1px solid #e8e8ee;">
                  <table style="width:100%;">
                    <tr>
                    <td style="vertical-align: middle;padding:8px 0px;">
                      <p style="color:#333;font-size:14px;margin:0px;"><?php echo $this->lang->line('Total'); ?></p>
                    </td>
                    <td style="vertical-align: middle;text-align: right;padding:8px 0px;">
                      <p style="color:#333;font-size:14px;margin:0px;"><?php if(!empty($invoice_detail->total)) echo price_formate($invoice_detail->total-$tip); ?> €
                      </p>
                    </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <tr >
                <td colspan="2" style="border-top:1px solid #e8e8ee;">
                  <table style="width:100%;">
                    <tr>
                    <td style="vertical-align: middle;padding:8px 0px;">
                      <p style="color:#333;font-size:14px;margin:0px;">Bezahlung : <?php if($invoice_detail->payment_type=='card') echo $this->lang->line('Card1'); else echo $this->lang->line(ucfirst($invoice_detail->payment_type));  ?></p>
                      <?php /*<span style="color:#666;font-size:14px;margin:0px;"><<?php if(!empty($invoice_detail->created_on)){ echo 'am '.$this->lang->line(date('l',strtotime($invoice_detail->created_on))).date(', \d\e\n d.m.Y',strtotime($invoice_detail->created_on)).',<br/>'.date('\u\m H:i \U\h\r',strtotime($invoice_detail->created_on)); } ?></span>*/ ?>
                    </td>
                    <td style="vertical-align: middle;text-align: right;padding:8px 0px;">
                      <p style="color:#333;font-size:14px;margin:0px;"><?php if(!empty($invoice_detail->paytotal)) echo price_formate($invoice_detail->paytotal); ?> €
                      </p>
                    </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="vertical-align: middle;padding:8px 0px;">
                  <p style="color:#666;font-size:14px;margin:0px;"><?php if(!empty($invoice_detail->details)) echo $invoice_detail->details; ?></p>
                </td>
                <td style="vertical-align: middle;text-align: right;padding:8px 0px;">
                  <p style="color:#333;font-size:14px;margin:0px;"></p>
                </td>
              </tr>
      </table>
    </td>
  </tr>
</table>
</div>
</html>
