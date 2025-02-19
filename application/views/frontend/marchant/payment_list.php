<?php $this->load->view('frontend/common/header'); ?>
    <!-- header end -->
    <div class="d-flex pt-84">   
		 <style>
				.completed{
					color:#4BB543 !important;
					}
			  .colororange{
				 color: #FF9944 !important;
				}		
				</style>      
      <?php $this->load->view('frontend/common/sidebar'); ?>
        <!-- dashboard left side end -->

        <div class="right-side-dashbord w-100 pl-30 pr-30">
          <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-20">
            <?php if(!empty($card_details)){ ?>
            <div class="btn-group multi_sigle_select widthfit80 mr-20 display-ib"> 
                  <p>Kommende Zahlungen werden am <?php echo date('d.m.Y',strtotime($plan_details->end_date)); ?> per <?php echo $card_details['brand'].' *****'.$card_details['last4']; ?>. <?php if($card_details['brand']=='SEPA Debit'){ echo 'Sie möchten ihre Zahlungsart ändern'; }else{ echo 'Sie möchten ihre Zahlungsart ändern'; } ?> ? <a style="color: #0098A1;" href="<?php echo base_url('membership/change_card'); ?>">Hier klicken</a></p>
                </div>
        
           <?php } ?>
              <div class="my-table">
				  <?php  if(!empty($payment)){ ?>
                <table class="table">
                	<thead>
                		<tr>
	                	   <th class="pl-4">Mitgliedschaft</th>
	                      <th class="">Von</th>
	                      <th class="">Bis</th>
	                      <th class="">Preis</th>
	                      <th class="">Status </th>
	     
	                      <th class="">Rechnung</th>
                		</tr>
                	</thead>
                  <tbody>
					 <?php
						 foreach($payment as $pay){ ?>
                    <tr>
                        <td class="">
                          <?php 
                            if ($pay->plan_id == STRIPE_P1) echo 'Basic';
                            if ($pay->plan_id == STRIPE_P2) echo 'Gold';
                            if ($pay->plan_id == STRIPE_P3) echo 'Unbegrenzt';
                          ?>
                        </td>
                        <td class=""><?php if(!empty($pay->start_date)) echo date('d.m.Y',strtotime($pay->start_date)); ?></td>
                        <td class=""><?php if(!empty($pay->end_date)) echo date('d.m.Y',strtotime($pay->end_date)); ?></td>
                        <td class=""><?php if(!empty($pay->amount)) echo $pay->amount; ?> €</td>
                        <td class="">
                          <?php
                            if(!empty($pay->status) && $pay->status=='succeeded' || $pay->status=='paid') echo "Bezahlt";
                            else if ($pay->status=='disputed') echo "Fehler";
                            else echo $pay->status;
                          ?>
                        </td>
                        <!--<td class=""><a class="font-size-14 <?php if(date('Y-m-d H:i:s')<=date('Y-m-d H:i:s',strtotime($pay->end_date))) echo "completed a_hover_cyan"; else echo "colororange"; ?>"><?php if(date('Y-m-d H:i:s')<=date('Y-m-d H:i:s',strtotime($pay->end_date))) echo "Current Plan"; else echo "Expired"; ?></a></td>-->
                        <td class=""> <?php if(!empty($pay->invoice_url)){ ?><a class="font-size-14" style="color: #0098A1;" target="_blank" href="<?php echo $pay->invoice_url; ?>">Download</a><?php } ?></td>
                    </tr>
                    <?php }  ?>
                   
                  </tbody>
                	<tbody>



                    </tbody>

                </table>
                <?php } else{ ?>
                <div class="text-center pb-20 pt-50">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;"><?php echo $this->lang->line('dont_any_invoice'); ?></p></div>
					  <?php } ?>
                  <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;">
                      <?php if($this->pagination->create_links()){ echo $this->pagination->create_links(); } ?>
                    
                    </ul>
                  </nav>
                </div>
              <!-- dashboard right side end -->       
              </div>
             </div>
          </div>

  <?php $this->load->view('frontend/common/footer_script'); 
    $pinstatus = get_pin_status($this->session->userdata('st_userid')); 
//echo current_url().'=='.$_SERVER['HTTP_REFERER'];
    if($pinstatus->pinstatus=='on'){
        $this->load->view('frontend/marchant/profile/ask_pin_popup');
      }   ?>
  
