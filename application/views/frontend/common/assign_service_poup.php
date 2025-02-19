 <?php 
   if($services){
	   $i=11;
   foreach($services as $k=>$v){ ?>
                <table class="w-100 small-width-large" >
                  <thead>
                    <tr class="border-b border-t">
                      <th class=" color333 font-size-14 fontfamily-semibold pl-4 pt-2 pb-2" style="">
						            <div class="checkbox mt-0 mb-0 text-left">
                            <label class="color333 font-size-14 fontfamily-semibold">
                              <input type="checkbox" id="service_asnfsdf<?php echo $i; ?>" class="select_all">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                              <?= $k ?>
                             </label>
                        </div>
                      </th>
                      <th class="text-center color333 font-size-14 fontfamily-semibold pt-2 pb-2">Dauer</th>
                      <th class="text-center color333 font-size-14 fontfamily-semibold pt-2 pb-2">Preis </th>
                      <th class="text-center color333 font-size-14 fontfamily-semibold pt-2 pb-2">Rabatt </th>
                    </tr>
                  </thead>
                  <tbody>
					  <?php
                  foreach($v as $ser){  ?>
                    <tr>
                      <td class="text-left pl-4 pt-3 pb-3" style="">
                        <div class="checkbox mt-0 mb-0">
                            <label class="font-size-14 color333 fontfamily-regular">
                              <input type="checkbox" name="service_id" data-subcat="<?php echo $ser->subcategory_id; ?>" data-text="<?php if(!empty($ser->name)) echo $k; else echo $k;?>" id="service_asn<?php echo $ser->id; ?>" class="select_asn_service service_asnfsdf<?php echo $i; ?>" value="<?php echo $ser->id; ?>">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                              <?php if(!empty($ser->name)) echo $ser->name; else echo $k; ?> 
                             </label>
                        </div>
                      </td>
                      <td> <?php echo $ser->duration; ?> Min.</td>
                      <td><?php echo price_formate($ser->price); ?> €</td>
                      <td><?php echo price_formate($ser->discount_price); ?> €</td>
                    </tr>
                    <?php  } ?>	   
                  </tbody>
                  
                </table>
<?php $i++; } }else{ ?>
    <span style=""></br></br>Es konnten keine Services gefunden werden.</br></br></span> 
   
  <?php } ?>
              
