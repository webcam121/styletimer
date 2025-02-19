<?php $this->load->view('frontend/common/header');
      $this->load->view('frontend/common/editer_css');

$optArr =  array('5'=>'5 Min.','10'=>'10 Min.','15'=>'15 Min.','20'=>'20 Min.','25'=>'25 Min.','30'=>'30 Min.','35'=>'35 Min.','40'=>'40 Min.','45'=>'45 Min.','50'=>'50 Min.','55'=>'55 Min.','60'=>'1h','65'=>'1h 5 Min.','70'=>'1h 10 Min.','75'=>'1h 15 Min.','80'=>'1h 20 Min.','85'=>'1h 25 Min.','90'=>'1h 30 Min.','95'=>'1h 35 Min.','100'=>'1h 40 Min.','105'=>'1h 45 Min.','110'=>'1h 50 Min.','115'=>'1h 55 Min.','120'=>'2h','135'=>'2h 15 Min.','150'=>'2h 30 Min.','165'=>'2h 45 Min.','180'=>'3h','195'=>'3h 15 Min.','210'=>'3h 30 Min.','225'=>'3h 45 Min.','240'=>'4h','255'=>'4h 15 Min.','270'=>'4h 30 Min.','285'=>'4h 45 Min.','300'=>'5h','315'=>'5h 15 Min.','330'=>'5h 30 Min.','345'=>'5h 45 Min.','360'=>'6h');

 ?>
<!-- dashboard left side end -->
<style type="text/css">
/*.pagination li:first-child{
    width: auto;
}*/
.modal-body .mobile1-mb-40 .fr-wrapper {
    height: 210px;
    overflow-x: auto !important;
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
.alert_message{
  top:0px !important;
}
#add-delet-service-popup{
	z-index: 1111;
}
.fr-box.fr-basic .fr-element{
    min-height: 350px;
  }

</style>
 <div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>

 <div class="right-side-dashbord w-100 pl-30 pr-30">
          <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-20 relative">
            <?php $this->load->view('frontend/common/alert'); ?>

              <div class="pt-20 pb-20 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
                <div class="display-ib ml-auto">
                 <button class="btn btn-large widthfit addservicebutton" data-id="" type="button"><?php echo $this->lang->line('Add_New_Service'); ?></button>
                </div>
              </div>
             <div class="my-table service-list-table">
                <?php if(!empty($serviceList)){ //echo '<pre>'; print_r($serviceList); die; ?>
                <table class="table">
                    <thead>
                        <tr>
                          <th class="text-center" style="padding-left: 30px;"><?php echo $this->lang->line('Category'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Service'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Duration'); ?> </th>
                          <th class="text-center"><?php echo $this->lang->line('Price'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Discount1'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Buffer_Time'); ?></th>
                          <th class="text-center"><?php echo $this->lang->line('Action'); ?> </th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						$filtercat = '';
						?>
                        <?php
						foreach($serviceList as $row){
							if ($row['m_category'] != $filtercat) {
						?>
							<tr style="background-color: rgba(0, 179, 190, 0.5); " class="categorybar">
								<td class="text-center" style="color: black;">
									<?php echo $row['m_category'];?>
								</td>
								<td colspan="6">
								</td>
							</tr>
						<?php
								$filtercat = $row['m_category'];
							}
						?>
						<?php
							$s1 = $row['duration']; $ss1 = 0;
							$s2 = $row['price']; $ss2 = 0;
							$s3 = $row['discount_price']; $ss3 = 0;
							$s4 = $row['buffer_time']; $ss4 = 0;
							if(!empty($row['subService'])) {
								foreach ($row['subService'] as $subservice) {
									if ($s1 != $subservice->duration) $ss1 = 1;
									if ($s1 > $subservice->duration) $s1 = $subservice->duration;
									if ($s2 != $subservice->price) $ss2 = 1;
									else if ($s2 == $subservice->price && $subservice->price_start_option == 'ab')  $ss2 = 1;
									if ($s2 > $subservice->price) $s2 = $subservice->price;
									if ($s3 != $subservice->discount_price) $ss3 = 1;
									else if ($s3 == $subservice->discount_price && $subservice->price_start_option == 'ab')  $ss3 = 1;
									if (!$s3 && $subservice->discount_price > 0) $s3 = $subservice->discount_price;
									if ($s3 > $subservice->discount_price && $subservice->discount_price > 0) $s3 = $subservice->discount_price;
									if ($s4 != $subservice->buffer_time) $ss4 = 1;
									if ($s4 > $subservice->buffer_time) $s4 = $subservice->buffer_time;
								}
							}
							if ($row['price_start_option'] == 'ab') {
								$ss2 = 1;
								$ss3 = 1;
							}
						?>
                        <tr id="row_<?php echo $row['sid']; ?>">
                          <td class="text-center" style="position:relative;padding:0px;padding-left:30px;">
						  	<?php
							if(!empty($row['subService'])) {
							?>
								<button style="position:absolute; left:0; border:none; background:none; top:10px;"onclick="function(e) {e.preventDefault();}"type="button" data-toggle="collapse" data-target=".multi-collapse<?php echo ($row['sid']); ?>" aria-expanded="false">
									<img src="<?php echo base_url('assets/frontend/images/down-arrow.png'); ?>" class="width22">
								</button>
							<?php } ?>
							<div class="addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>" style="line-height: 40px;">
						  		<?php echo $row['s_category']; ?>
							</div>
						  </td>
                          <td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
						  	<p class="mb-0 display-ib" style="max-width: 500px; white-space:initial;">
								<?php
									if(!empty($row['subService'])) echo (count($row['subService'])+1) . ' Services';
									else if(!empty($row['name'])) echo $row['name']; else echo "-";
								?>
							</p>
                          </td>
						  <td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
						  	<?php echo ($ss1 ? 'ab ' : ''). $s1; ?> Min. </td>
                          <td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
						  	<?php echo  ($ss2 ? 'ab ' : '').price_formate($s2). " € ";?></td>
                          <td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
						  	<?php if(!empty($s3)) echo ($ss3 ? "ab " : '').price_formate($s3)."  €"; else echo '-'; ?></td>
                          <td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
						  	<?php echo ($ss4 ? 'ab ' : '').$s4; ?> Min.</td>
                          <td class="text-center">
                            <a href="#" data-id="<?php echo url_encode($row['sid']); ?>" title="<?php echo $this->lang->line('Duplicate'); ?>" data-type="duplicate" class="mr-3 addservicebutton">
                            <img src="<?php echo base_url('assets/frontend/images/new-icon-duplicate.png'); ?>" class="width22"></a>
                            <a href="#" data-id="<?php echo url_encode($row['sid']); ?>" class="mr-3 addservicebutton" title="<?php echo $this->lang->line('Edit'); ?>" ><img src="<?php echo base_url('assets/frontend/images/new-icon-edit-service.png'); ?>" class="width22"></a>
                            <a href="#" data-toggle="modal" data-target="#employee-delete-popup" class="mr-3 deleteserviceclick"  title="<?php echo $this->lang->line('Delete'); ?>" id="<?php echo url_encode($row['sid']); ?>"><img src="<?php echo base_url('assets/frontend/images/new-icon-bin.png'); ?>" class="width22"></a>
                            <a href="#" data-toggle="modal" data-subcat_id="<?php echo $row['subcategory_id']; ?>" data-subcat_name="<?php echo $row['s_category']; ?>" class="allowservice" title="Multiple Service Auswahl"><img src="<?php echo base_url('assets/frontend/images/togglefinish.png'); ?>" class="width22" style="filter:brightness(0.25);"></a>
                           </td>
                        </tr>
						<?php
							if(!empty($row['subService'])) {
								$cnt = 1;
						?>
						<div>
							<tr class="collapse multi-collapse<?php echo ($row['sid']); ?> row_<?php echo $row['sid']; ?>">
								<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>"><?php echo $cnt;?></td>
								<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
									<p class="mb-0 display-ib" style="max-width: 500px; white-space:initial;">
										<?php if(!empty($row['name'])) echo $row['name']; else echo "-";?>
									</p>
								</td>
								<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>"><?php echo $row['duration']; ?> Min. </td>
								<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>"> <?php if($row['price_start_option']=='ab'){ echo  'ab '.price_formate($row['price']). " € ";}else{echo price_formate($row['price']). " € ";} ?></td>
								<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>"><?php if(!empty($row['discount_price'])){
									echo $row['price_start_option'] == 'ab' ? 'ab': '';
									echo price_formate($row['discount_price'])."  €";
								} else echo '-'; ?>
								</td>
								<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>"><?php echo $row['buffer_time']; ?> Min.</td>
								<td class="text-center">
								</td>
							</tr>
							<?php
								foreach ($row['subService'] as $subservice) {
									$cnt++;
							?>
								<tr class="collapse multi-collapse<?php echo ($row['sid']);?> row_<?php echo $row['sid']; ?>">
									<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>"><?php echo $cnt;?></td>
									<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>" style="max-width: 300px; white-space:initial;">
										<?php echo $subservice->name;?>
									</td>
									<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>"><?php echo $subservice->duration; ?> Min.</td>
									<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
										<?php if($subservice->price_start_option=='ab'){ echo  'ab '.price_formate($subservice->price). " € ";}else{echo price_formate($subservice->price). " € ";} ?>
									</td>
									<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
										<?php if(!empty($subservice->discount_price)){
											echo $subservice->price_start_option == 'ab' ? 'ab' : '';
											echo price_formate($subservice->discount_price)."  €";
										} else echo '-';?>
									</td>
									<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
										<?php echo $service->buffer_time ? $service->buffer_time : 0; ?> Min.
									</td>
									<td class="text-center addservicebutton" data-id="<?php echo url_encode($row['sid']); ?>">
									</td>
								</tr>
							<?php
								}
							?>
						</div>
						<?php
						}
						?>
                       <?php } ?>
                      
                       </tbody>

                </table>
                <?php } else{ ?>
                 <div class="text-center" style="padding-bottom: 45px;">
					  <img src="<?php echo base_url('assets/frontend/images/no_listing.png'); ?>"><p style="margin-top: 20px;"> Bisher wurden keine Services angelegt.</p></div>
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

    <div class="modal fade" id="employee-delete-popup">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a id="close_box" href="#" class="crose-btn" data-dismiss="modal">
		  <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/new-icon-bin.png'); ?>" style="width: 40px;">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;"><?php echo $this->lang->line('service_delete_confimation'); ?></p>
            <input type="hidden" id="deleteid" name="" value="0">
            <button id="deleteservice" type="button" class=" btn btn-large widthfit"><?php echo $this->lang->line('Delete'); ?></button>
          </div>
        </div>
      </div>
	</div>

    <div class="modal fade" id="add-delet-service-popup">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">      
          <a id="close_box" href="#" class="crose-btn deletePOPservice" data-dismiss="modal">
		  <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
          </a>
          <div class="modal-body pt-30 mb-30 pl-40 pr-40">
            <img src="<?php echo base_url('assets/frontend/images/new-icon-bin.png'); ?>" style="width: 40px;">
            <p class="font-size-18 color333 fontfamily-medium mt-3 mb-30 lineheight26 text-lines-overflow-1" style="-webkit-line-clamp:2;"><?php echo $this->lang->line('service_delete_confimation'); ?></p>
            <input type="hidden" data-text="" id="deleteid" name="" value="">
            <button type="button" id="removeExistSubservice" class=" btn btn-large widthfit"><?php echo $this->lang->line('Delete'); ?></button>
          </div>
        </div>
      </div>
    </div>
 <?php $this->load->view('frontend/common/footer_script');
       $this->load->view('frontend/common/editer_js');  ?>
 
 <!-- add-service-modal start-->
<div class="modal fade" id="add-service-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
    <div class="modal-content"> 
      <div class="modal-body" id="addServiceHtml">
         
	  </div>
	</div>
  </div>
</div>
<!-- add-service-modal end -->

<!-- satting modal start -->
<div class="modal fade" id="setting-toggle-popup">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
        <div class="modal-content text-center">  
			<div class=" " style="height:50px;">    
          <a id="close_box" href="#" class="crose-btn" data-dismiss="modal">
		  <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
		  </a>
				</div>
          <div class="modal-body pt-0 mb-1 pl-0 pr-0 scroll-300">
		  	<div class="my-table">
			  <table class="table ">
                    <thead>
                        <tr>
                          <th class="text-center pl-4 fontfamily-regular" style="font-weight:400;" colspan="2">
						  Wenn Sie diese Option aktivieren, können Kunden mehrere Varianten innerhalb dieses Services auswählen und in den Warenkorb legen. Ist die Option deaktiviert, kann der Kunde jeweils nur 1 Variante auswählen.

	  					</th>
						</tr>
					</thead>
					<tbody class="subcatIdHtml">
						
					</tbody>
			  </div>
          </div>
        </div>
      </div>
	</div>
	<!-- satting modal end -->

<script>
	
	$(document).on('click','.allowservice', function(){
	   var id =$(this).attr('data-subcat_id');
	 var name =$(this).attr('data-subcat_name');
	     var gurl=base_url+"category_setting/check_permission";
		 var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
		 $.post(gurl,{subcat_id:id,subcat_name:name},function(data){
			  var obj = jQuery.parseJSON( data );
			 if(obj.success=='1'){
				 	$(".subcatIdHtml").html(obj.html);
				 	$("#setting-toggle-popup").modal('show');
            //alert(data.status);
				 }

		  });
	 });
	 
  $(document).on('click','#subCategorySetting', function(){
	  var id =$(this).attr('data-id');	  
	  if($(this).prop("checked") == true){
			var value =1;
		}
		else{
		  var value =0;
		}
	    var gurl=base_url+"category_setting/update_category_setting";
		var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
		 $.post(gurl,{subcat_id:id,val:value},function(data){
			  var obj = jQuery.parseJSON( data );
			 if(obj.success=='1'){
				 	//$(".subcatIdHtml").html(obj.html);
				 	//$("#setting-toggle-popup").modal('show');
            //alert(data.status);
				 }

		  });
	 });
	
    $(document).on('click','.addservicebutton',function(){
		var id = $(this).attr('data-id');
		var type = $(this).attr('data-type');
		if(type==undefined)
		  {
			  type="";
		  }
		
		var gurl=base_url+"merchant/add_service";
		 $.get(gurl,{id:id,duplecate:type},function(data){
			  var obj = jQuery.parseJSON( data );
			 if(obj.success=='1'){
				 	$("#addServiceHtml").html(obj.html);
				 	$("#add-service-modal").modal('show');
				 	 $("#changeTaxTxt").text($("input[name='tax']:checked").attr('data-text'));
          	     //alert(data.status);
          	        
          	          $("#cat_btn").text($("input[name='category']:checked").attr('data-val'));
                      $("#fcat_btn").text($("input[name='filtercategory']:checked").attr('data-val'));
					  $("#subCat_btn").text($("input[name='sub_category']:checked").attr('data-val'));
					  
					 var lengt=$("input[name='assigned_users[]']:checked").length;
				//alert(lengt);
        	 if(lengt>1){
						  $("#assign_user").text(lengt+"  Mitarbeiter ausgewählt");
						 }
					else{
						$("#assign_user").text($("input[name='assigned_users[]']:checked").attr('data-val'));
						}
						
					$(".timeDropDown:checked").each(function(){
					   var clss = $(this).attr('data-c');
					   var texts = $(this).attr('data-text');
					   $("."+clss).text(texts);
					 });
						
					 $(function(){
						  const editorInstance = new FroalaEditor('#about_salon',{
							enter: FroalaEditor.ENTER_P,
							key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
							placeholderText: null,
							language: 'de',
							events: {
							  initialized: function (){
								const editor = this
								this.el.closest('form').addEventListener('submit', function (e) {
								  console.log(editor.$oel.val())
								  e.preventDefault()
								})
							  }
							}
						   })
						});	
								   
				 }

		  });
	  
	  });

function submitAddService(){
	
	/*if($("#add_category").valid()){	*/
		
    	var token = true;
    	if($('#discount_price').val()!=''){
    		if(Number(onlydotvalreturn($('#discount_price').val())) > Number(onlydotvalreturn($('#standardprice').val()))){
				$("#discount_price_valid").css('display','block');
    			$('#discount_price_valid').html("<i class='fas fa-exclamation-circle mrm-5'></i>Discount can not be greater than standard price.")
    			 token =false;
    		}
    		else{
    			$('#discount_price_valid').html(''); }
    	}

    	/*if($('[name="category"]:checked').length == 0){
    			$("#catgory_err").css('display','block');
    			$("#catgory_err").html("<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line('category_is_required'); ?>"); 
    			 token =false;
    		}else{ $("#catgory_err").css('display','none');
    			$("#catgory_err").html(''); 
        } */

        if($('[name="filtercategory"]:checked').length == 0){
          $("#fcatgory_err").css('display','block');
          $("#fcatgory_err").html("<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line('filtercategory_is_required'); ?>"); 
           token =false;
        }else{ $("#fcatgory_err").css('display','none');
          $("#fcatgory_err").html(''); 
        }

      if($('[name="assigned_users[]"]:checked').length == 0){
          $(".asign_employee_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+please_select_employee);
          $( "#assign_user" ).focus(); 
           token =false;
        }else{ 
          $(".asign_employee_err").html(''); 
        }  

      if($('#standardprice').val() == ''){
        $( "#standardprice" ).focus();
      $("#standardprice_err").css('display','block');
      $("#standardprice_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+Price_is_required); 
       token =false;
      }else{ $("#standardprice_err").css('display','none');
      $("#standardprice_err").html(''); }
		
		if($('[name="sub_category"]:checked').length == 0){
			$("#subcatgory_err").css('display','block');
			$("#subcatgory_err").html("<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line('sub_category_is_required'); ?>"); 
			 token =false;
		}else{ $("#subcatgory_err").css('display','none');
			 $("#subcatgory_err").html(''); }
		
		$.each($(".validation"), function() {
			var val=$(this).val();
			var id=$(this).attr('data-text');
			var name=$(this).attr('name');
			
			//alert(name);
			id=id.replaceAll(' ','-')
			id=id.replaceAll('.','')
			if(val==""){
				$(this).focus();
				if(name=='subPrice[]'){
				   $("#"+id).html("<i class='fas fa-exclamation-circle mrm-5'></i>"+Price_is_required);
				}
			   else{
				  $("#"+id).html("<i class='fas fa-exclamation-circle mrm-5'></i>Unterkategorie ist erforderlich");
			    }
				$("#"+id).css("display",'block');
				 token =false;
				}
			   else{
				$("#"+id).html("");
				}	
		});
		$.each($(".checkPrice"), function() {
			var val=$(this).val();
			var id=$(this).attr('data-text');
			var dicPrice=$("."+id).val();
			
			if(Number(onlydotvalreturn(dicPrice))>onlydotvalreturn(val)){
				$("#dis"+id).focus();
				$("#dis"+id).html("<i class='fas fa-exclamation-circle mrm-5'></i>Discount can not be greater than standard price.");
				$("#dis"+id).css("display",'block');
				 token =false;
				}
			else{
				$("#"+id).html("");
				}	
		});
			
		var discountCheck = 0;
		
		var inputs = $(".discount_price");

		for(var i = 0; i < inputs.length; i++){
			if($(inputs[i]).val()!='' && discountCheck==0){
			  discountCheck=1;
		   	}
		}
		
		if($("#discount_price").val() !='' || discountCheck == 1){
			if($("input[name='days[]']:checked").length == 0){
				$(".select-day-time").addClass('show');
				$(".select-day-time-click ").removeClass('collapsed');
				$("#chk_monday").html("<i class='fas fa-exclamation-circle mrm-5'></i>" + select_day_for_applying_that_special_discount); 
				token =false;
			}else{ 
				$("#chk_monday").html('');
				var values = new Array();
				$.each($("input[name='days[]']:checked"), function() {
					// alert($('[name="'+day+'_start"]:checked').length);
				//values.push($(this).val());
					var day=$(this).val();
					if($('[name="'+day+'_start"]:checked').length == 0){
						$("#Serr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Startzeit auswählen"); 
					token =false;
					}else{ $("#Serr_"+day).html(''); }
					if($('[name="'+day+'_end"]:checked').length == 0){
						$("#Eerr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Endzeit auswählen"); 
					token =false;
					}else{ $("#Eerr_"+day).html(''); }
					
					if($('[name="'+day+'_start"]:checked').length != 0 && $('[name="'+day+'_end"]:checked').length != 0 && $('[name="'+day+'_start"]:checked').val()>=$('[name="'+day+'_end"]:checked').val()){
						$("#Eerr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line("select_end_time_greater_than_start_time"); ?>"); 
						token =false;
						}
				});
				$("#busType_err").text(''); 
			}
		}
		
		
		if(discountCheck==0){
			$("input[name='days[]']:checked").removeAttr("checked");
			if($("input[name='days[]']:checked").length != 0){
				$("#discount_price_valid").css("width",'148px'); 
				$("#discount_price_valid").html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Rabattpreis angeben"); 
				token =false;
			}
		}
	//alert(discountCheck); 
			
			//return false;
		//alert(discountCheck);
		if(token == false){
			return false;
		}
		else{
			//$('#save_buuton').
			//$("#save_buuton").prop("disabled",true);
			$("#add_category").submit();
		}		
    //}
}
    
	 //alert(lengt);
      $(function(){
        $(window).on("scroll", function(){
            if($(window).scrollTop() > 90) {
                $(".header").addClass("header_top");
            } else {
                //remove the background property so it comes transparent again (defined in your css)
               $(".header").removeClass("header_top");
            }
        });
      });
       $('#datepicker').datepicker({
           uiLibrary: 'bootstrap4'
       });
       $('.clockpicker').clockpicker();
       $(document).on('change','.select_fcat',function(){
       	var catid= $("input[name='filtercategory']:checked").val();
         if(catid!=undefined){
		 $('#subCat_btn').attr('disabled',false);
	     $('#assign_user').attr('disabled',false);	 
		
	       var gurl=base_url+"merchant/get_sub_category";
		   var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);  
		 $.post(gurl,{catid:catid},function(data){
			  var obj = jQuery.parseJSON( data );
			 if(obj.access=='1'){
				 	$("#sub_category").html(obj.html);
          $("#subCat_btn").text('');	     //alert(data.status);
				 }

		  });
	    }
	   else{ alert('Please select category.'); }
	  });
	  
 $(document).on('change','.select_cat',function(){
       	var catid= $("input[name='category']:checked").val();
         if(catid!=undefined){
	     $('#subCat_btn').attr('disabled',false);
	     $('#assign_user').attr('disabled',false);
	    }
	
	  });
	  

	 $(function(){
        $('.select-day-time-click').click( function(){
        rt= $('.down-arrow-white').attr('src');
       if(rt=="images/down-arrow-white.svg"){
        $('.down-arrow-white').attr('src', 'images/top-arrow-white.svg');
       }
       else if(rt=="images/top-arrow-white.svg"){
        $('.down-arrow-white').attr('src', 'images/down-arrow-white.svg');
       }
    });
    });

      $(document).on("click",".addmoreservice",function(){
				var val=$(this).attr('data-count');
				var num=Number(val)+1;
				$(this).attr('data-count',num);
				  var addmOre_section='<div class="spacial-discount addedsction">\
									 <div class="border-w3 border-radius4 pt-30 pb-20 pl-14 pr-14 mb-40 relative mt-10">\
										<a class="colororange fontfamily-medium font-size-14 a_hover_orange absolute removebtntoggle removenew" style="color:#FF9944 !important;cursor:pointer;"><?php echo $this->lang->line("Delete"); ?></a>\
										<div class="relative pl-15 pr-15">\
											<div class="row">\
												<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">\
													<div class="form-group mobile1-mb-40">\
														<label class="inp">\
															<input type="text" placeholder="&nbsp;" name="subService[]" class="form-control validation" data-text="subService'+val+'" required>\
															<span class="label"><?php echo $this->lang->line("Service_servicename"); ?></span>\
														</label>\
														 <label class="error" id="subService'+val+'"></label>\
													</div>\
												</div>\
												<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">\
													<div class="row">\
													 <div class="col-12 col-sm-7 col-md-7 col-lg-7 col-xl-7" >\
							                           <div class="row">\
														<div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pr-0">\
														<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line("Standard_Price"); ?></label>\
															<div class="relative form-group-mb-50">\
																<div class="input-group ">\
																	<div class="input-group-prepend ">\
																	<span class="input-group-text bge8e8e8">€</span>\
																	</div>\
																	<input type="text" class="form-control validation" data-text="subPricenew'+val+'" name="subPrice[]" placeholder="" style="padding: 0px 5px;text-align: center;">\
																</div>\
															   <label class="error" id="subPricenew'+val+'"></label>\
															</div>\
														</div>\
														<div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pr-0">\
														<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line("Discounted_Price"); ?></label>\
															<div class="relative form-group-mb-50">\
																<div class="input-group ">\
																	<div class="input-group-prepend ">\
																		<span class="input-group-text bge8e8e8">€</span>\
																		</div>\
																	 <input type="text" name="subDiscount_price[]"  class="form-control discount_price subPricenew'+val+'" placeholder="" style="padding: 0px 5px;text-align: center;">\
																	</div>\
																<label  class="error" id="dissubPricenew'+val+'"></label>\
															</div>\
														</div>\
													</div>\
												  </div>\
											    <div class="col-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" >\
													<div class="relative form-group form-group-mb-50">\
														<span class="color999 fontfamily-light font-size-12 mb-1 d-block">Preisart</span>\
														<div class="btn-group multi_sigle_select inp_select">\
															<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" style="text-transform: none !important;">Festpreis</button>\
															<ul class="dropdown-menu mss_sl_btn_dm" x-placement="bottom-start">\
																<li class="radiobox-image">\
																	<input type="radio" id="id_ad'+val+'" name="subprice_start_option['+val+']" value="ab">\
																	<label for="id_ad'+val+'">ab</label>\
																</li>\
																<li class="radiobox-image">\
																	<input type="radio" id="id_festprice'+val+'" name="subprice_start_option['+val+']" value="Festpreis" checked><label for="id_festprice'+val+'">Festpreis</label>\
																</li>\
															</ul>\
														</div>\
													</div>\
												  </div>\
												   </div>\
												   </div>\
													\<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">\
													<div class="row procsstimediv" id="withoutProcessTime'+val+'">\
														<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">\
														<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line("Standard_Duration"); ?></label>\
															<div class="form-group">\
														     <div class="btn-group multi_sigle_select">\
																<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptnew04'+val+'" style="">1h</button>\
																<ul class="dropdown-menu mss_sl_btn_dm noclose scroll-effect height240" style="max-height: none;height: 320px !important;overflow-x: auto;">\
																<?php foreach($optArr as $optk=>$optv){ ?><li class="radiobox-image">\
																<input type="radio" name="getChecked_idoptnew04'+val+'" id="idoptnew04'+val+'<?php echo $optk; ?>" class="timeDropDown addMoretimeDropDown" data-c="idoptnew04'+val+'" data-text="<?php echo $optv; ?>" value="<?php echo $optk; ?>" <?php if(60==$optk) echo "checked"; ?>><label for="idoptnew04'+val+'<?php echo $optk; ?>"><?php echo $optv; ?></label></li><?php   } ?></ul>\
														</div>\
														<label class="error" id="subtimenew'+val+'"></label>\
														<input type="hidden" id="idoptnew04'+val+'" name="subDuration[]" data-text="subtimenew'+val+'" class="form-control validation" value="60">\
													</div>\
												 </div>\
													<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">\
														<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo ucfirst(strtolower($this->lang->line('Buffer_Time'))); ?></label>\
															<div class="form-group">\
														     <div class="btn-group multi_sigle_select">\
																<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptnewb04'+val+'" style=""><?php echo 'kein Puffer'; ?></button>\
																<ul class="dropdown-menu mss_sl_btn_dm noclose scroll-effect height240" style="max-height: none;height: 320px !important;overflow-x: auto;">\
																   <li class="radiobox-image">\
																	<input type="radio" id="idoptnewb04'+val+'0" class="timeDropDown addMoretimeDropDown" data-c="idoptnewb04'+val+'0" data-text="no buffer" name="getChecked_idoptnewb04'+val+'" value="0"><label for="idoptnewb04'+val+'0"><?php echo $this->lang->line("no_buffer"); ?></label>\
																	 </li>\
																    <?php foreach($optArr as $optk=>$optv){ ?><li class="radiobox-image">\
																      <input type="radio" name="getChecked_idoptnewb04'+val+'" id="idoptnewb04'+val+'<?php echo $optk; ?>" class="timeDropDown addMoretimeDropDown" data-c="idoptnewb04'+val+'" data-text="<?php echo $optv; ?>" value="<?php echo $optk; ?>"><label for="idoptnewb04'+val+'<?php echo $optk; ?>"><?php echo $optv; ?></label></li><?php   } ?></ul>\
														     </div>\
														<label class="error" id="subbUffernew'+val+'"></label>\
														<input type="hidden" id="idoptnewb04'+val+'" name="subBuffer_time[]" value="0" data-text="subbUffernew'+val+'">\
														</div>\
													  </div>\
													</div>\
													<div class="row procsstimediv display-n" id="withProcessTime'+val+'">\
														<div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 pr-2" >\
														<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('working_time'); ?></label>\
																<div class="form-group">\
														     <div class="btn-group multi_sigle_select">\
																<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptnewwt04'+val+'" style="">15 min</button>\
																<ul class="dropdown-menu mss_sl_btn_dm noclose scroll-effect height240" style="background:white;">\
																<?php foreach($optArr as $optk=>$optv){ ?><li class="radiobox-image">\
																<input type="radio" name="getChecked_idoptnewwt04'+val+'" id="idoptnewwt04'+val+'<?php echo $optk; ?>" class="timeDropDown addMoretimeDropDown" data-c="idoptnewwt04'+val+'" data-text="<?php echo $optv; ?>" value="<?php echo $optk; ?>" <?php if(15==$optk) echo "checked"; ?>><label for="idoptnewwt04'+val+'<?php echo $optk; ?>"><?php echo $optv; ?></label></li><?php   } ?></ul>\
															</div>\
															<label class="error" id="subsetuptime'+val+'"></label>\
															<input type="hidden" id="idoptnewwt04'+val+'" name="subsetuptime[]" value="15" data-text="subsetuptime'+val+'"  class="form-control onlyNumber">\
														</div>\
														</div>\
														<div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 pr-2 pl-2" >\
														<label class="color999 fontfamily-light font-size-12 mb-1">Einwirkzeit</label>\
														<div class="form-group">\
														     <div class="btn-group multi_sigle_select">\
																<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptnewex04'+val+'" style="">15 min</button>\
																<ul class="dropdown-menu mss_sl_btn_dm noclose scroll-effect height240" style="background:white;">\
																<?php foreach($optArr as $optk=>$optv){ ?><li class="radiobox-image">\
																<input type="radio" name="getChecked_idoptnewex04'+val+'" id="idoptnewex04'+val+'<?php echo $optk; ?>" class="timeDropDown addMoretimeDropDown" data-c="idoptnewex04'+val+'" data-text="<?php echo $optv; ?>" value="<?php echo $optk; ?>" <?php if(15==$optk) echo "checked"; ?>><label for="idoptnewex04'+val+'<?php echo $optk; ?>"><?php echo $optv; ?></label></li><?php   } ?></ul>\
															</div>\
															 <label class="error" id="subprocesstime'+val+'"></label>\
															<input type="hidden" id="idoptnewex04'+val+'" name="subprocesstime[]" value="15"  data-text="subprocesstime'+val+'" class="form-control onlyNumber">\
														 </div>\
														</div>\
														<div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 pl-2">\
														<label class="color999 fontfamily-light font-size-12 mb-1">Abschließen</label>\
															<div class="form-group">\
														     <div class="btn-group multi_sigle_select">\
																<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptnewfin04'+val+'" style="">15 min</button>\
																<ul class="dropdown-menu mss_sl_btn_dm noclose scroll-effect height240" style="background:white;">\
																<?php foreach($optArr as $optk=>$optv){ ?><li class="radiobox-image">\
																<input type="radio" name="getChecked_idoptnewfin04'+val+'" id="idoptnewfin04'+val+'<?php echo $optk; ?>" class="timeDropDown addMoretimeDropDown" data-c="idoptnewfin04'+val+'" data-text="<?php echo $optv; ?>" value="<?php echo $optk; ?>" <?php if(15==$optk) echo "checked"; ?>><label for="idoptnewfin04'+val+'<?php echo $optk; ?>"><?php echo $optv; ?></label></li><?php   } ?></ul>\
															</div>\
															 <label class="error" id="subprocesstime'+val+'"></label>\
															<input type="hidden" id="idoptnewfin04'+val+'" name="subfinishtime[]" value="15" data-text="subfinishtime'+val+'"  class="form-control onlyNumber">\
														</div>\
														</div>\
														<span class="fontsize-12 lineheight12 color-333 display-ib pl-3 mt-1 pr-3" style="margin-top:0.75rem !important;"><?php echo $this->lang->line('the_exposure'); ?> </span>\
														</div>\
															<div class="checkbox mb-2" style="margin-top:0.75rem;">\
															    <label class="fontsize-12 fontfamily-regular color333">\
															      <input type="checkbox" data-id="subproccess_time'+val+'" value="yes" class="addProcceesTime" id="addProcceesTime" data-count="'+val+'">\
															    <span class="cr"><i class="cr-icon fa fa-check"></i></span><?php echo $this->lang->line('add_exposure'); ?></label>\
															</div>\
															<input type="hidden" name="subproccess_time[]" value="" id="subproccess_time'+val+'">\
													</div>\
												</div>\
											 <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">\
								                  	<div class="vertical-bottom">\
									                   <label class="switch ml-2" for="vcheckbox15sub'+val+'" style="top:8px">\
										                    <input type="checkbox" id="vcheckbox15sub'+val+'" class="onlineOption" data-id="subonline'+val+'" checked>\
										                    <div class="slider round"></div>\
										                 </label>\
										                 <input type="hidden" name="subonline[]" value="1" id="subonline'+val+'">\
										                 <p class="color333 fontfamily-medium font-size-14 display-ib"><?php echo $this->lang->line('available_online'); ?></p>\
										             </div>\
								                  </div>\
											</div>\
										</div>\
									</div>';
									
            $("#add_more_section").append(addmOre_section);
      });
      
         $(document).on('click',".removeExistSubservice",function(){
	         var id=$(this).attr('data-id');
	         var deltid=$(this).attr('data-delid');
	         $("#deleteid").val(id);
	         $("#deleteid").attr('data-text',deltid);
	         $("#add-delet-service-popup").modal('show');
	       });

		$(document).on('click',".deletePOPservice",function(){
		 $(".modal").css('overflow-y','auto');
		});
     $(document).on('click',"#removeExistSubservice",function(){

		  //if (!confirm('Are you sure you want to delete?')) return false;

	   var id=$("#deleteid").val();
	    var deltid=$("#deleteid").attr('data-text');
	   var gurl=base_url+"merchant/deleteservice";

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
	    $.post(gurl,{tid:id},function(data){
			  var obj = jQuery.parseJSON( data );
			 if(obj.success=='1'){
               	   $("#delete"+deltid).remove();
               	    $("#deleteid").val('');
	                $("#deleteid").attr('data-text','');
               	   $("#add-delet-service-popup").modal('hide');
               	   $(".modal").css('overflow-y','auto');
				  unloading();
				 }

		  });
	  });

	  $(document).on('click',".removenew",function(){

	    $(this).parents().remove('.addedsction');
	  });
	 
	 $(document).on('change',".addProcceesTime",function(){
		
		 var count=$(this).attr('data-count');
		 var dataid=$(this).attr('data-id');
		 // alert(dataid);
		 if($(this).prop('checked')==true){
			 $("#withProcessTime"+count).removeClass('display-n');
			 $("#withoutProcessTime"+count).addClass('display-n');
			 
				 if(dataid !=undefined){
					 $("#"+dataid).val('yes'); 
					}
					
			$("#withProcessTime"+count+" input").each(function(){
			    $(this).addClass('validation');
			  });
			$("#withoutProcessTime"+count+" input").each(function(){
			    $(this).removeClass('validation');
			  });  	
				
			 }
		 else{
			  $("#withProcessTime"+count).addClass('display-n');
			 $("#withoutProcessTime"+count).removeClass('display-n');
			  if(dataid !=undefined){
				 $("#"+dataid).val(''); 
				}
			 $("#withProcessTime"+count+" input").each(function(){
				 
			    $(this).removeClass('validation');
			  });
			$("#withoutProcessTime"+count+" input").each(function(){
				//alert('da');
			    $(this).addClass('validation');
			  });
			 
			 }	 

	   // $(this).parents().remove('.addedsction');
	  }); 
 

      $( ".select-day-time-click" ).click(function() {
        $( ".toggle-parent-date-time" ).removeClass('border-w4');
      });
      
     $(document).on('change','.onlineOption',function(){
		    var id=$(this).attr('data-id');
           if($(this).prop("checked")){ $("#"+id).val('1'); }
           else  $("#"+id).val('0');		 
		 });

		 	 
 
  
  $(".timeDropDown:checked").each(function(){
	   var clss = $(this).attr('data-c');
	   var texts = $(this).attr('data-text');
	   $("."+clss).text(texts);
	 });
	 
   $(document).on('change','.addMoretimeDropDown',function(){
	   var classs = $(this).attr('data-c');
	   //var value = $(".getChecked_"+classs+":checked").val();
	   var value = $("input[name='getChecked_"+classs+"']:checked").val();
	   $("#"+classs).val(value);
	  //alert(value+"== "+classs);
	 });
	 
	 $(document).on('click','.radiobox-image', function(){
		$('.dropdown-menu').removeClass('show');
	});
	  
$(document).on('change','.asign_employee', function(){
   if($('[name="assigned_users[]"]:checked').length == 0){
          $("#assign_user").text("Mitarbeiter wählen"); 
          // token =false;
        }
    //$('.dropdown-menu').removeClass('show');
  });
</script>
