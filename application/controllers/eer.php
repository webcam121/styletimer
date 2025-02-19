	 <?php 
      
      $html="";
		 if(!empty($booking)){
			 foreach($booking as $row){	
				 $html=$html.'<tr>   
                      <td class="text-center font-size-14 height56v booking_row" id="'.url_encode($row->id).'">'.date("d.m.Y",strtotime($row->booking_time)).'</td>
                      <td class="text-center font-size-14 height56v booking_row" id="'.url_encode($row->id).'">'.date("H:i",strtotime($row->booking_time)).' Uhr</td>                   
                      <td class="text-center font-size-14 height56v booking_row" id="'.url_encode($row->id).'">'.$row->book_id.'</td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular booking_row" id="'.url_encode($row->id).'"><p class="vertical-meddile mb-0 display-ib" style="width: 240px;white-space: normal;">'.rtrim($sevices, ',').'</p></td>
                      <td class="text-center height56v font-size-14 color666 fontfamily-regular booking_row" id="'.url_encode($row->id).'">'.$row->total_minutes.' Min.</td>
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center" id="'.url_encode($row->id).'">'.price_formate($row->total_price).' €</td>
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">
                        <span href="#" class="'.$cls.' font-size-14 fontfamily-regular a_hover_red">'.$newStatus.$icon.'</span>
                        <span class="font-size-10 color666 fontfamily-regular display-b">am '.$action_date.'</span>
                      </td>  
                      <td class="text-center"><a style="'.$txtDacoration.'" href="'.$recipUrl.'" class="text-underline '.$detalClass.' color333">'.$recp.'</a></td>                    
                    </tr>';
                    
				 //<a style="'.$txtDacoration.'" data-encode="'.url_encode($row->id).'" overflow_elips // css class ....... data-saddress="'.$saddress.'" data-bookedvia="'.$bookedvia.'" data-duration="'.$row->total_minutes.' Mins" data-price="'.$row->total_price.' €" data-id="'.$row->id.'" data-bookid="'.$row->book_id.'" data-time="'.date("d F Y, H : i",strtotime($row->booking_time)).'" data-complete ="'.date("d F Y, H : i",strtotime($row->updated_on)).'" data-service="'.rtrim($sevices, ',').'" data-salone="'.$row->business_name.'"  class="text-underline '.$detalClass.' color333">'.$recp.'</a>
				 
				 }
			 
			 }
		else{
			$html='<tr><td colspan="8" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="'.base_url('assets/frontend/images/no_listing.png').'"><p style="margin-top: 20px;">not found</p></div></td></tr>';
			
			}	 
			
		  echo json_encode(array('success'=>'1','msg'=>'','html'=>$html,
		  'pagination'=>$pagination));	die;
      
      
      
      
      ?>