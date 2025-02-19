<?php 
$optArr = array('5'=>'5min','10'=>'10min','15'=>'15min','20'=>'20min','25'=>'25min','30'=>'30min','35'=>'35min','40'=>'40min','45'=>'45min','50'=>'50min','55'=>'55min','60'=>'1h','65'=>'1h 5min','70'=>'1h 10min','75'=>'1h 15min','80'=>'1h 20min','85'=>'1h 25min','90'=>'1h 30min','95'=>'1h 35min','100'=>'1h 40min','105'=>'1h 45min','110'=>'1h 50min','115'=>'1h 55min','120'=>'2h','135'=>'2h 15min','150'=>'2h 30min','165'=>'2h 45min','180'=>'3h','195'=>'3h 15min','210'=>'3h 30min','225'=>'3h 45min','240'=>'4h','255'=>'4h 15min','270'=>'4h 30min','285'=>'4h 45min','300'=>'5h','315'=>'5h 15min','330'=>'5h 30min','345'=>'5h 45min','360'=>'6h'); 

 ?>
 <script>
	 
		 $(document).on('change','#profile_pic', function(){
    var reader = new FileReader();
    //console.log(reader);
    reader.onload = function (event) {
		 $(".cr-boundary").css('width','500px');
		  $(".cr-boundary").css('height','400px');
		  $(".cr-viewport").css('width','250px');
		  $(".cr-viewport").css('height','250px');
		  $(".crop_image").addClass('crop_image_profile');
		  $(".crop_image").removeClass('crop_image');
		//console.log(event);
      $image_crop.croppie('bind', {
        url:event.target.result
      }).then(function(){
		 
        //console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  }); 



 $(document).on('click','.crop_image_profile',function(event){
	// alert('df');
    $image_crop.croppie('result', {
      type:'canvas',
       size:{
		  width:250,
		  height:250
		   }
    }).then(function(response){
		loading();
      $.ajax({
        url:"<?php echo base_url('profile/profile_imagecropp'); ?>",
        type: "POST",
        data:{"image": response},
        success:function(data)
        { var obj = $.parseJSON(data);
          $('#uploadimageModal').modal('hide');
          $('.modal').css('overflow-y','auto');
          unloading();
          // var $el = $('#profile_pic');
           //$el.wrap('<form>').closest('form').get(0).reset();
          // $el.unwrap();
         // $('#upload_image').attr('data-url','');
          //$('#profile-picture').attr('data-img',obj.status);
          $('#EmpProfile').attr('src',obj.image);
        }
      });
    })
  });
		
    $(document).on('click','.cropImage', function(){
     
    var imgurl=$(this).attr('data-src');
    var iname=$(this).attr('data-name');
    var tname=$(this).attr('data-id');
    
    var myImg = document.querySelector("#"+tname);
        var realWidth = myImg.naturalWidth;
        var realHeight = myImg.naturalHeight;
        //alert("Original width=" + realWidth + ", " + "Original height=" + realHeight);
    if(realWidth>=1110 && realHeight>=375){
    $("#cropImageName").val(iname);
      //console.log(event);
      $image_crop.croppie('bind', {
      url:imgurl
      }).then(function(){
		  $(".cr-boundary").css('width','500px');
		  $(".cr-boundary").css('height','170px');
		  $(".cr-viewport").css('width','296px');
		  $(".cr-viewport").css('height','100px');
		   $(".crop_image").addClass('crop_image');
		  $(".crop_image").removeClass('crop_image_profile');
      //console.log('jQuery bind complete');
      });
    
    //reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
      }
     else{
       Swal.fire({
        title: 'Dieses Größe des Bild kann nicht verändert werden, da es kleiner als 1100 x 375px ist.',
        width: 600,
        padding: '3em'
      });
       
       } 
    });

    $(document).on('click','.crop_image',function(event){ 
		//~ alert('sd')     
		$image_crop.croppie('result', {
		  type: 'canvas',
		  size:{
		  width:672,
		  height:448
		   }
		}).then(function(response){
			$
		  loading();
		var iname=$("#cropImageName").val();  
		 if(iname=='add')
      {
		  var urlreq ="<?php echo base_url('profile/upload_banner_img'); ?>";
	  }else{
		  var urlreq ="<?php echo base_url('profile/imagecropp'); ?>";
		  } 
		  $.ajax({
		  url:urlreq,
		  type: "POST",
		  data:{"image": response,name:iname},
		  success:function(data)
		  { 
			  getGelleryhtml();
			  $("#uploadimageModal").modal('hide');
			  $(".modal").css('overflow-y','auto');
			 
		  }
		  });
		});
		
	  
	});


	
 //profile setup js statrt
 
   $(document).on('change','#banner_setupimg_upload', function(){
    var reader = new FileReader();
    //console.log(reader);
    reader.onload = function (event) {
		 $("#cropImageName").val('add');
      $image_crop.croppie('bind', {
        url:event.target.result
      }).then(function(){
        //console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });
       
     $(document).on('click','#zipcode',function(){      //#address,
    
    $("#latitude").val("");
    $("#longitude").val("");
    
  }); 
  $(document).on('blur','#zipcode',function(){   //#address,
  
          getaddress();
    
    }); 
  $(document).on('change','.country',function(){
      $("#latitude").val("");
      $("#longitude").val("");
      getaddress();
    
  }); 
   function getaddress(){
    
    var country= $("input[name='country']:checked").attr('data-text');
        var zipcode=$("#zipcode").val();
        var address="";
        var location=$("#address").val(); 
        if(location!=""){
			address=location;
			}
        var c_code='de';
        if(zipcode =="")
          { return false; 
			  }
        if(zipcode!=undefined && zipcode!=""){
            address=address+" "+zipcode;
          }
        
        if(country!=undefined && country!=""){
         if(country == 'Austria')
            c_code = 'at';
         else if(country == 'Switzerland')
            c_code = 'ch';
        }
       var location=$("#address").val(); 
      
      //var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&countrycodes="+c_code+"&postalcode="+address+"&street="+location+"&addressdetails=1&format=json";
      var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&q="+address+"&addressdetails=1&format=json&countrycodes="+c_code;

         loading();
         $.get(gurl,function(data){
          var count = data.length;
          if(data.length > 0){
              if(data[0].address.town!=undefined)
              var citty = data[0].address.town
				  else if(data[0].address.city!=undefined)
					 var citty = data[0].address.city
			else if(data[0].address.municipality!=undefined)    
            	var citty = data[0].address.municipality
			else
					 var citty = "";
					 
				$("#city").val(citty);
        
            $("#latitude").val(data[0].lat);
            $("#longitude").val(data[0].lon);
             $('#addr_err').html('');
               unloading();
          }
          else{
            $("#location_validate label.error").css('display','none');
            $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
            $("#latitude").val('');
              unloading();
            return false;
            }  
          }).fail(function() {
           $("#location_validate label.error").css('display','none');
            $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
          
            $("#latitude").val('');
              unloading();
            return false;
          });

      }

$(document).on('click','#save_profile_setup',async function(){
	if($("#profile_setup").valid())
	  {

	  	var about = $("#about_salon").val();
	  	// if(about ==""){
	  	// 	$("#about_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+Salon_description_is_required);
	  	// 	return false;
	  	// }else{
	  	// 	$("#about_err").html("");
	  	// }
	  	
		var key = '5eaae41f648ada';
		var gurl = "https://us1.locationiq.com/v1/search.php?key="+key+"&q="+$("#address").val()+", "+$("#zipcode").val()+" "+$("#city").val()+"&addressdetails=1&format=json&countrycodes=de";
		data = await $.get(gurl);
		if(data.length > 0){
			var lat = data[0].lat;
			var lon = data[0].lon;
			$("#latitude").val(lat);
			$("#longitude").val(lon);
		}

		  //~ alert('fg')
		 $.ajax({
		  url:"<?php echo base_url('profile/profile_setup'); ?>",
		  type: "POST",
		  data:$("#profile_setup").serialize(),
		  success:function(data)
		  {
			  var obj = $.parseJSON(data);
			if(obj.success==1){
				getGelleryhtml(obj.msg);
				}
			else{
				  alert(obj.msg);
				}	  
	
		  }
		  });  
		  
	  }
   else
      {
		return false;  
	  }  
	
	});
	
	
$(document).on('click','#save_salon_time',function(){
	 var token = true;
    	 
        if($("input[name='days[]']:checked").length == 0){
			$("#chk_monday").text('Bitte Öff nungszeiten festlegen'); 
			 token =false;
		}else{ 
			$("#chk_monday").text(''); 
			 var values = new Array();
			 $.each($("input[name='days[]']:checked"), function() {
				// alert($('[name="'+day+'_start"]:checked').length);
			   //values.push($(this).val());
			 	 var day=$(this).val();
			 	 if($('[name="'+day+'_start"]:checked').length == 0){
			 		 $("#Serr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Startzeit auswählen"); 
				  token =false;
			 	 }else{ $("#Serr_"+day).html(''); }
			 	 if($('[name="'+day+'_end"]:checked').length == 0){
			 		 $("#Eerr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Endzeit auswählen"); 
				  token =false;
			 	 }else{ $("#Eerr_"+day).html(''); }
			 	 
			 	 if($('[name="'+day+'_start"]:checked').length != 0 && $('[name="'+day+'_end"]:checked').length != 0 && $('[name="'+day+'_start"]:checked').val()>=$('[name="'+day+'_end"]:checked').val()){
					  $("#Eerr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line("select_end_time_greater_than_start_time"); ?>"); 
				      token =false;
					 }
			 });
			 //$("#busType_err").text(''); 
		}

		if(token == false)
		 {
			return false; 
		 }
		else 
		  {
			//form.submit();	
			
			 $.ajax({
		  url:"<?php echo base_url('profile/workinghour_setup'); ?>",
		  type: "POST",
		  data:$("#marchant_working_time").serialize(),
		  success:function(data)
		  {
			  var obj = $.parseJSON(data);
			if(obj.success==1){
				getAddEmployeehtml(obj.msg);
				}
			else{
				  alert(obj.msg);
				}	  
	
		  }
		  });  
		  
	  }
	
	});




$(document).on('click','#save_add_employee_setup',function(){
	if($("#frmmyEmployee").valid())
	  {
		  
		var token = true;
		if($("input[name='days[]']:checked").length==0){
			//alert('if');
			$(".first_Err").text('Bitte Öff nungszeiten festlegen'); 
			 token =false;
		}else{ 
			var values = new Array();
			$.each($("input[name='days[]']:checked"), function() {
				
			 	var day=$(this).val();
			 	
			 	if($('[name="'+day+'_start"]:checked').length==0){
			 		$("#Serr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Startzeit auswählen"); 
				 token =false;
			 	}else{ $("#Serr_"+day).html(''); 
					}
					
			 	if($('[name="'+day+'_end"]:checked').length==0){
					//alert('e '+day);
			 		$("#Eerr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Endzeit auswählen"); 
				 token =false;
			 	}else{ 
					$("#Eerr_"+day).html(''); 
					}
			 	
                 //return false;
			 	if($('[name="'+day+'_start"]:checked').val()!='' && $('[name="'+day+'_end"]:checked').val() !='' && $('[name="'+day+'_end"]:checked').val()<=$('[name="'+day+'_start"]:checked').val()){
			 		$("#Eerr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"); 
				 token =false;

			 	}else{ if($('[name="'+day+'_start"]:checked').length!=0 && $('[name="'+day+'_end"]:checked').length!=0){ $("#Eerr_"+day).html(''); }
					 }

			 	
			 	if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end_two"]:checked').val() !='' && $('[name="'+day+'_end_two"]:checked').val()<=$('[name="'+day+'_start_two"]:checked').val()){
			 		$("#Eerrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"); 
				 token =false;

			 	}else{ if($('[name="'+day+'_start_two"]:checked')!='' && $('[name="'+day+'_end_two"]:checked').val() !='') $("#Eerrtwo_"+day).html(''); }
			
				if($('[name="'+day+'_start_two"]:checked').val()==''){
			 		$("#Serrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Startzeit auswählen"); 
				 token =false;
			 	}else{ $("#Serrtwo_"+day).html(''); }
			 	
			 	if($('[name="'+day+'_end_two"]:checked').val() == ''){
			 		$("#Eerrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Endzeit auswählen"); 
				 token =false;
			 	}else{ $("#Eerrtwo_"+day).html(''); }
			 	
			 	if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end_two"]:checked').val() !='' && $('[name="'+day+'_end_two"]:checked').val()<=$('[name="'+day+'_start_two"]:checked').val()){
			 		$("#Eerrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Die Endzeit kann nicht vor der Startzeit liegen"); 
				 token =false;
			 	}else{ 
				if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end_two"]:checked').val()!='') $("#Eerrtwo_"+day).html('');
				 }


				 //alert($('[name="'+day+'_start_two"]:checked').val()+"-"+$('[name="'+day+'_end"]:checked').val());
			 	if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end"]:checked').val() !='' && $('[name="'+day+'_end"]:checked').val()>=$('[name="'+day+'_start_two"]:checked').val()){
			 		$("#Serrtwo_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte gültige Startzeit wählen");//Bitte gültige Startzeit wählen 
				 token =false;
			 	}else{ if($('[name="'+day+'_start_two"]:checked').val()!='' && $('[name="'+day+'_end"]:checked').val() !='') $("#Serrtwo_"+day).html(''); }
			

			});
			$("#busType_err").text(''); 

			if($("#commission_check").is(':checked'))
		        {
		          if($("#commission").val() ==""){
		            token = false;
		            $("#commission_error").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+Please_enter_commission);
		            $("#commission_error").show();
		          }
		          else if($("#commission").val() > 100){
		            token = false;
		            $("#commission_error").html("<i class='fas fa-exclamation-circle mrm-5'></i>Commission % not greater than 100");  
		            $("#commission_error").show();
		          }
		          else
		            $("#commission_error").html("");
		        }
			
		}
		//return false;
		if(token == false){
			return false;
		}
		else{
			
		loading();
		
		 $.ajax({
		  url:"<?php echo base_url('profile/employee_setup'); ?>",
		  type: "POST",
		  data:$("#frmmyEmployee").serialize(),
		  success:function(data)
		   {
			  var obj = $.parseJSON(data);
			if(obj.success==1){
				   if(obj.page=='same'){
						  getAddEmployeehtml('add_more',obj.msg);
					  }
				  else{		
						getAddServicehtml(obj.msg);
					  }
				}
			else{
				  alert(obj.msg);
				}	  
	
		    }
		  });  
	    }
	  }
   else
      {
		return false;  
	  }  
	
	});


 $(document).on('click',".addServiceSub",function(){

        $("#addServiceSub").val($(this).attr('data-val'));
		
		 /*if($("#add_category").valid())
		  { */
		  //alert($("#add_category").valid());
			  
			 var token = true;
    	
			if($('#discount_price').val()!=''){
			if(Number(onlydotvalreturn($('#discount_price').val())) > Number(onlydotvalreturn($('#standardprice').val()))){
				$("#discount_price_valid").css('display','block');
				$('#discount_price_valid').html("<i class='fas fa-exclamation-circle mrm-5'></i>Rabattpreis kann nicht über Standardpreis liegen")
				 token =false;
			}
			else{
				$('#discount_price_valid').html('');
			  }
			}
		/*	if($('[name="category"]:checked').length == 0){
			$("#catgory_err").css('display','block');
			$("#catgory_err").html("<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line('category_is_required'); ?>"); 
			 token =false;
			}else{ $("#catgory_err").css('display','none');
			$("#catgory_err").html(''); } */
			if($('[name="filtercategory"]:checked').length == 0){
			$("#fcatgory_err").css('display','block');
			$("#fcatgory_err").html("<i class='fas fa-exclamation-circle mrm-5'></i><?php echo $this->lang->line('filtercategory_is_required'); ?>"); 
			 token =false;
			}else{ $("#fcatgory_err").css('display','none');
			$("#fcatgory_err").html(''); }

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
			$("#subcatgory_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+sub_category_is_required); 
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
				  $("#"+id).html("<i class='fas fa-exclamation-circle mrm-5'></i>This field is required");
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

			if(Number(dicPrice)>val){
				$("#dis"+id).focus();
				$("#dis"+id).html("<i class='fas fa-exclamation-circle mrm-5'></i>Rabattpreis kann nicht über Standardpreis liegen");
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
					$("#chk_monday").html("<i class='fas fa-exclamation-circle mrm-5'></i>"+select_day_for_applying_that_special_discount); 
					token =false;
				}else{ 
					$("#chk_monday").html('');
					var values = new Array();
					$.each($("input[name='days[]']:checked"), function() {
						// alert($('[name="'+day+'_start"]:checked').length);
					//values.push($(this).val());
						var day=$(this).val();
						if($('[name="'+day+'_start"]:checked').length == 0){
							$("#Serr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Startzeit auswählen"); 
							token =false;
						}else{ $("#Serr_"+day).html(''); }
						if($('[name="'+day+'_end"]:checked').length == 0){
							$("#Eerr_"+day).html("<i class='fas fa-exclamation-circle mrm-5'></i>Bitte Endzeit auswählen"); 
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
				} else {
					$("#discount_price_valid").css("display","none"); 
				}
			}
			

			if(token == false){
				return false;
			}
			else{

			 $.ajax({
			  url:"<?php echo base_url('profile/service_setup'); ?>",
			  type: "POST",
			  data:$("#add_category").serialize(),
			  success:function(data)
			  {
				  var obj = $.parseJSON(data);
				if(obj.success==1){
						if(obj.page=='next')
						 {
							 getOnlineSetuphtml(obj.msg);
						 }
						else{ 
						   getAddServicehtml(obj.msg);
					   }
					}
				else{
					  alert(obj.msg);
					}	  

			  }
			  });  
			} 
		 /* }
		else
		  {
			return false;  
		  }  */
	  
         
	  });
	  
	  
	  
$(document).on('click','#onlinrsetup_submit',function(){
	
	 $.ajax({
		url:"<?php echo base_url('profile/online_setup'); ?>",
		type: "POST",
		data:$("#onlinrsetup_form").serialize(),
		success:function(data)
		{
			var obj = $.parseJSON(data);
			if(obj.success==1){
				// getThankYouhtml(obj.msg);
				window.location.href = "<?php echo base_url('merchant/dashboard'); ?>";
				// startConfetti();
				// setTimeout(function() {
				// 	stopConfetti();
				// }, 4000);
			}
			else{
				alert(obj.msg);
			}	  
		}
	});  
});
	  
	  
// profile setup js end 

function getProfilehtml(){
	
	   $.ajax({
		  url:"<?php echo base_url('profile/profile_setup'); ?>",
		  type: "GET",
		  success:function(data)
		  {
			 var obj = $.parseJSON(data);
			 $("#addprofile_setupform").html(obj.html);
			 $("#countyr_btn").text($("input[name='country']:checked").attr('data-text'));
             
			  $(function (){
				  const editorInstance = new FroalaEditor('#about_salon', {
					enter: FroalaEditor.ENTER_P,
					key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
					placeholderText: null,
					language: 'de',
					events: {
					  initialized: function () {
						const editor = this
						this.el.closest('form').addEventListener('submit', function (e) {
						  console.log(editor.$oel.val())
						  e.preventDefault()
						})
					  }
					}
				  })
				});
			 var date = new Date();
			var today =new Date(date.getFullYear(), date.getMonth(), date.getDate());
			 var date40year = new Date('<?php  echo date("Y-m-d",strtotime("-90 years",time())); ?>');
			 
			$(".dobDatepicker").datepicker({
				   uiLibrary: 'bootstrap4',
				   format:"dd-mm-yyyy",
				   maxDate:today
			   }); 	
			//location.href=base_url+"profile/gallery_setup";
			unloading();
		   
		  }
		  });
	
	}

function getGelleryhtml(){
	
	   $.ajax({
		  url:"<?php echo base_url('profile/gallery_setup'); ?>",
		  type: "GET",
		  success:function(data)
		  {
			 var obj = $.parseJSON(data);
			 $("#addprofile_setupform").html(obj.html);
			//location.href=base_url+"profile/gallery_setup";
			unloading();
		   
		  }
		  });
	
	}

function getTimehtml(){
	
	   $.ajax({
		  url:"<?php echo base_url('profile/workinghour_setup'); ?>",
		  type: "GET",
		  success:function(data)
		  {
			 var obj = $.parseJSON(data);
			 $("#addprofile_setupform").html(obj.html);
			//location.href=base_url+"profile/gallery_setup";
			unloading();
		  }
		  });
	
	}

function getAddEmployeehtml(form="",msg=""){
	var moreurl= "";
	
	if(form=='add_more'){
		var moreurl= "/add_more";
		}
	   $.ajax({
		  url:"<?php echo base_url('profile/employee_setup'); ?>"+moreurl,
		  type: "GET",
		  data:{message:msg},
		  success:function(data)
		  {
			 var obj = $.parseJSON(data);
			 $("#addprofile_setupform").html(obj.html);
			
			 $('#setup_modal').animate({ scrollTop: 0 }, 'slow');
			 
			jscolor.installByClassName("jscolor");
			 setTimeout(function(){	
				 //alert('sf');
				$(".messageAlert1").addClass('display-n');
				},4000);
			
			unloading();
		  }
		  });
	
	}
	
function getAddServicehtml(msg=""){
	
	   $.ajax({
		  url:"<?php echo base_url('profile/service_setup'); ?>",
		  type: "GET",
		  data:{message:msg},
		  success:function(data)
		  {
			 var obj = $.parseJSON(data);
			 $("#addprofile_setupform").html(obj.html);
			 
			 $("#changeTaxTxt").text($("input[name='tax']:checked").attr('data-text'));
			 	 $(function (){
				  const editorInstance = new FroalaEditor('#about_salon', {
					enter: FroalaEditor.ENTER_P,
					key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
					placeholderText: null,
					language: 'de',
					events: {
					  initialized: function () {
						const editor = this
						this.el.closest('form').addEventListener('submit', function (e) {
						  console.log(editor.$oel.val())
						  e.preventDefault()
						})
					  }
					}
				  })
				});
				setTimeout(function(){	
					//alert('sf');
					$(".messageAlert1").addClass('display-n');
					},4000);
			//location.href=base_url+"profile/gallery_setup";
			unloading();
		  }
		  });
	
	}
	
function getOnlineSetuphtml(){
	
	   $.ajax({
		  url:"<?php echo base_url('profile/online_setup'); ?>",
		  type: "GET",
		  success:function(data)
		  {
			 var obj = $.parseJSON(data);
			 $("#addprofile_setupform").html(obj.html);
			//location.href=base_url+"profile/gallery_setup";
			unloading();
		  }
		  });
	
	}	
	
function getThankYouhtml(){
	
	   $.ajax({
		  url:"<?php echo base_url('profile/thankyou_popup'); ?>",
		  type: "GET",
		  success:function(data)
		  {
			 var obj = $.parseJSON(data);
			 console.log(data);
			 $("#addprofile_setupform").html(obj.html);
			//location.href=base_url+"profile/gallery_setup";
			unloading();
			startConfetti();
      		setTimeout(function() {
            	stopConfetti();
            }, 4000);
			
		  }
		  });
	
	}
	
 // MODAL ON LOAD PAGE
 $(document).ready(function(){
//gellery setup js start	
  $image_crop = $('#image_demo').croppie({
    enableExif: false,
    viewport: {
      width:300,
      height:200,
      type:'square' //circle
    },
    boundary:{
      width:450,
      height:300
    }
    });
  

  

});

function deleteBnanerImage(url){
   Swal.fire({
      title: '<?php echo $this->lang->line("are_you_sure"); ?>',
      text: "<?php echo $this->lang->line("you_want_delete_image"); ?>",
      type: 'warning',
      showCancelButton: true,
      reverseButtons:true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Bestätigen'
    }).then((result) => {
    if (result.value) {
      $.ajax({
                url: url,
                type: "POST",
                success: function (response) {
					getGelleryhtml();
                 //location.href=base_url+'profile/gallery_setup';
 
                 }
            });
      
      }else{
        return false;
        }
    });  

}


function setBnanerImage(url=''){
	if(url!='')
      { 
      $.ajax({
                url: url,
                type: "POST",
                success: function (response) {
					getGelleryhtml();
 
                 }
            });
      }
    else{
		return false;
		}  
  

}

// gallery setup end


// add service form js
      //~ $("#cat_btn").text($("input[name='category']:checked").attr('data-val'));
	  //~ $("#subCat_btn").text($("input[name='sub_category']:checked").attr('data-val'));
	 //~ var lengt=$("input[name='assigned_users[]']:checked").length;
	 //~ if(lengt>1){
		  //~ $("#assign_user").text(lengt+" selected");
		 //~ }
	//~ else{
		//~ $("#assign_user").text($("input[name='assigned_users[]']:checked").attr('data-val'));
		//~ }
	 //alert(lengt);
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
     
     
      $(document).on('change','.select_fcat',function(){
        var catid= $("input[name='filtercategory']:checked").val();
         if(catid!=undefined){
	       var gurl=base_url+"merchant/get_sub_category";
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

      $(document).on("click",".addmoreservice",function() {
				var val=$(this).attr('data-count');
				var num=Number(val)+1;
				$(this).attr('data-count',num);
					 var addmOre_section='<div class="spacial-discount addedsction">\
									 <div class="border-w3 border-radius4 pt-30 pb-20 pl-14 pr-14 mb-40 relative mt-10">\
										<a class="colororange fontfamily-medium font-size-14 a_hover_orange absolute removebtntoggle removenew" style="color:#FF9944 !important;cursor:pointer;">Entfernen</a>\
										<div class="relative pl-15 pr-15">\
											<div class="row">\
												<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">\
													<div class="form-group mobile1-mb-40">\
														<label class="inp">\
															<input type="text" placeholder="&nbsp;" name="subService[]" class="form-control">\
															<span class="label"><?php echo $this->lang->line('Service_servicename'); ?></span>\
														</label>\
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
															<ul class="dropdown-menu mss_sl_btn_dm" x-placement="bottom-start"   >\
																<li class="radiobox-image" >\
																	<input type="radio" id="id_ad1'+val+'" name="subprice_start_option['+val+']" value="ab">\
																	<label for="id_ad1'+val+'">ab</label>\
																</li>\
																<li class="radiobox-image">\
																	<input type="radio" id="id_festprice1'+val+'" name="subprice_start_option['+val+']" value="Festpreis" checked><label for="id_festprice1'+val+'">Festpreis</label>\
																</li>\
															</ul>\
														</div>\
													</div>\
												  </div>\
												   </div>\
												   </div>\
												<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">\
													<div class="row procsstimediv" id="withoutProcessTime'+val+'">\
														<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">\
														<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Standard_Duration'); ?></label>\
															<div class="form-group">\
														     <div class="btn-group multi_sigle_select">\
																<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptnew04'+val+'" style="">1h</button>\
																<ul class="dropdown-menu mss_sl_btn_dm  scroll-effect height240"  style="max-height: none; overflow-x: auto; height: 320px !important;position: absolute; will-change: transform; top: 0px; left: 0px;transform: translate3d(0px, -158px, 0px);">\
																<?php foreach($optArr as $optk=>$optv){ ?><li class="radiobox-image">\
																<input type="radio" name="getChecked_idoptnew04'+val+'" id="idoptnew04'+val+'<?php echo $optk; ?>" class="timeDropDown addMoretimeDropDown" data-c="idoptnew04'+val+'" data-text="<?php echo $optv; ?>" value="<?php echo $optk; ?>" <?php if(60==$optk) echo "checked"; ?>><label for="idoptnew04'+val+'<?php echo $optk; ?>"><?php echo $optv; ?></label></li><?php   } ?></ul>\
														</div>\
														<label class="error" id="subtimenew'+val+'"></label>\
														<input type="hidden" id="idoptnew04'+val+'" name="subDuration[]" value="60" data-text="subtimenew'+val+'" class="form-control validation">\
													</div>\
												 </div>\
													<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">\
														<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Buffer_Time'); ?></label>\
															<div class="form-group">\
														     <div class="btn-group multi_sigle_select">\
																<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptnewb04'+val+'" style=""><?php echo $this->lang->line('no_buffer'); ?></button>\
																<ul class="dropdown-menu mss_sl_btn_dm scroll-effect height240"  style="max-height: none; overflow-x: auto; height: 320px !important;position: absolute; will-change: transform; top: 0px; left: 0px;transform: translate3d(0px, -158px, 0px);">\
																   <li class="radiobox-image">\
																	<input type="radio" id="idoptnewb04'+val+'0" class="timeDropDown addMoretimeDropDown" data-c="idoptnewb04'+val+'0" data-text="no buffer" name="getChecked_idoptnewb04'+val+'" value="0"><label for="idoptnewb04'+val+'0"><?php echo $this->lang->line('no_buffer'); ?></label>\
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
																<ul class="dropdown-menu mss_sl_btn_dm  scroll-effect height240" style="max-height: 320px !important;position: absolute; will-change: transform; top: 0px; left: 0px;transform: translate3d(0px, -158px, 0px);background:white;">\
																<?php foreach($optArr as $optk=>$optv){ ?><li class="radiobox-image"  >\
																<input type="radio" name="getChecked_idoptnewwt04'+val+'" id="idoptnewwt04'+val+'<?php echo $optk; ?>" class="timeDropDown addMoretimeDropDown" data-c="idoptnewwt04'+val+'" data-text="<?php echo $optv; ?>" value="<?php echo $optk; ?>" <?php if(15==$optk) echo "checked"; ?>><label for="idoptnewwt04'+val+'<?php echo $optk; ?>"><?php echo $optv; ?></label></li><?php   } ?></ul>\
															</div>\
															<label class="error" id="subsetuptime'+val+'"></label>\
															<input type="hidden" id="idoptnewwt04'+val+'" name="subsetuptime[]" value="15" data-text="subsetuptime'+val+'"  class="form-control onlyNumber">\
														</div>\
														</div>\
														<div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 pr-2 pl-2" >\
														<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('exposure'); ?></label>\
														<div class="form-group">\
														     <div class="btn-group multi_sigle_select">\
																<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptnewex04'+val+'" style="">15 min</button>\
																<ul class="dropdown-menu mss_sl_btn_dm  scroll-effect height240"  style="max-height: 320px !important;position: absolute; will-change: transform; top: 0px; left: 0px;transform: translate3d(0px, -158px, 0px);background:white;">\
																<?php foreach($optArr as $optk=>$optv){ ?><li class="radiobox-image">\
																<input type="radio" name="getChecked_idoptnewex04'+val+'" id="idoptnewex04'+val+'<?php echo $optk; ?>" class="timeDropDown addMoretimeDropDown" data-c="idoptnewex04'+val+'" data-text="<?php echo $optv; ?>" value="<?php echo $optk; ?>" <?php if(15==$optk) echo "checked"; ?>><label for="idoptnewex04'+val+'<?php echo $optk; ?>"><?php echo $optv; ?></label></li><?php   } ?></ul>\
															</div>\
															 <label class="error" id="subprocesstime'+val+'"></label>\
															<input type="hidden" id="idoptnewex04'+val+'" name="subprocesstime[]" value="15"  data-text="subprocesstime'+val+'" class="form-control onlyNumber">\
														 </div>\
														</div>\
														<div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 pl-2">\
														<label class="color999 fontfamily-light font-size-12 mb-1"><?php echo $this->lang->line('Complete'); ?></label>\
															<div class="form-group">\
														     <div class="btn-group multi_sigle_select">\
																<button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn idoptnewfin04'+val+'" style="">15 min</button>\
																<ul class="dropdown-menu mss_sl_btn_dm  scroll-effect height240"  style="max-height: 320px !important;position: absolute; will-change: transform; top: 0px; left: 0px;transform: translate3d(0px, -158px, 0px);background:white;">\
																<?php foreach($optArr as $optk=>$optv){ ?><li class="radiobox-image">\
																<input type="radio" name="getChecked_idoptnewfin04'+val+'" id="idoptnewfin04'+val+'<?php echo $optk; ?>" class="timeDropDown addMoretimeDropDown" data-c="idoptnewfin04'+val+'" data-text="<?php echo $optv; ?>" value="<?php echo $optk; ?>" <?php if(15==$optk) echo "checked"; ?>><label for="idoptnewfin04'+val+'<?php echo $optk; ?>"><?php echo $optv; ?></label></li><?php   } ?></ul>\
															</div>\
															 <label class="error" id="subprocesstime'+val+'"></label>\
															<input type="hidden" id="idoptnewfin04'+val+'" name="subfinishtime[]" value="15" data-text="subfinishtime'+val+'"  class="form-control onlyNumber">\
														</div>\
														</div>\
														<span class="fontsize-12 lineheight12 color-333 display-ib pl-3 mt-1 pr-3" style="margin-top:0.75rem !important;"><?php echo $this->lang->line('the_exposure'); ?></span>\
														</div>\
															<div class="checkbox mb-2" style="margin-top:0.75rem;">\
															    <label class="fontsize-12 fontfamily-regular color333">\
															      <input type="checkbox" data-id="subproccess_time'+val+'" value="yes" class="addProcceesTime" id="addProcceesTime" data-count="'+val+'">\
															    <span class="cr"><i class="cr-icon fa fa-check"></i></span><?php echo $this->lang->line('add_exposure'); ?></label>\
															</div>\
															<input type="hidden" name="subproccess_time[]" value="" id="subproccess_time'+val+'">\
													</div>\
												</div>\
											 <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0">\
								                  	<div class="vertical-bottom">\
									                   <label class="switch mr-2" for="vcheckbox15sub'+val+'" style="top:8px">\
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

     $(document).on('click',"#removeExistSubservice",function(){

		  //if (!confirm('Are you sure you want to delete?')) return false;

	   var id=$("#deleteid").val();
	    var deltid=$("#deleteid").attr('data-text');
	   var gurl=base_url+"merchant/deleteservice";
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
	    $.post(gurl,{tid:id},function(data){
			  var obj = jQuery.parseJSON( data );
			 if(obj.success=='1'){
               	   $("#delete"+deltid).remove();
               	    $("#deleteid").val('');
	                $("#deleteid").attr('data-text','');
               	   $("#add-delet-service-popup").modal('hide');
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
 

  $(".select-day-time-click" ).click(function() {
	 $( ".toggle-parent-date-time" ).removeClass('border-w4');
  });
  
 

 $(".timeDropDown:checked").each(function(){
	   var clss = $(this).attr('data-c');
	   var texts = $(this).attr('data-text');
	   $("."+clss).text(texts);
	 });
	 

   $(document).on('click','#save_add_employee_add_more',function(){
	//alert('s');
	$("#save_type").val('add_more');	
	
	$("#save_add_employee_setup").trigger('click');
	
	}); 

 $(document).on('change','.addMoretimeDropDown',function(){
	   var classs = $(this).attr('data-c');
	   //var value = $(".getChecked_"+classs+":checked").val();
	   var value = $("input[name='getChecked_"+classs+"']:checked").val();
	   $("#"+classs).val(value);
	  //alert(value+"== "+classs);
	 });




</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

 <script type="text/javascript">

  var maxParticleCount=250;
  var particleSpeed=4;
  var startConfetti;
  var stopConfetti;
  var toggleConfetti;
  var removeConfetti;
  (function(){
    startConfetti=startConfettiInner;stopConfetti=stopConfettiInner;toggleConfetti=toggleConfettiInner;removeConfetti=removeConfettiInner;
  var colors=["DodgerBlue","OliveDrab","Gold","Pink","SlateBlue","LightBlue","Violet","PaleGreen","SteelBlue","SandyBrown","Chocolate","Crimson"]
  var streamingConfetti=false;
  var animationTimer=null;
  var particles=[];
  var waveAngle=0;
  function resetParticle(particle,width,height){particle.color=colors[(Math.random()*colors.length)|0];particle.x=Math.random()*width;particle.y=Math.random()*height-height;particle.diameter=Math.random()*10+5;particle.tilt=Math.random()*10-10;particle.tiltAngleIncrement=Math.random()*0.07+0.05;particle.tiltAngle=0;return particle;}
  function startConfettiInner(){var width=window.innerWidth;var height=window.innerHeight;window.requestAnimFrame=(function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(callback){return window.setTimeout(callback,16.6666667);};})();var canvas=document.getElementById("confetti-canvas");if(canvas===null){canvas=document.createElement("canvas");canvas.setAttribute("id","confetti-canvas");canvas.setAttribute("style","display:block;z-index:999999;pointer-events:none");document.body.appendChild(canvas);canvas.width=width;canvas.height=height;window.addEventListener("resize",function(){canvas.width=window.innerWidth;canvas.height=window.innerHeight;},true);}
var context=canvas.getContext("2d");while(particles.length<maxParticleCount)
particles.push(resetParticle({},width,height));streamingConfetti=true;if(animationTimer===null){(function runAnimation(){context.clearRect(0,0,window.innerWidth,window.innerHeight);if(particles.length===0)
animationTimer=null;else{updateParticles();drawParticles(context);animationTimer=requestAnimFrame(runAnimation);}})();}}
function stopConfettiInner(){streamingConfetti=false;}
function removeConfettiInner(){stopConfetti();particles=[];}
function toggleConfettiInner(){if(streamingConfetti)
stopConfettiInner();else
startConfettiInner();}
function drawParticles(context){
  var particle;
  var x;
  for(var i=0;i<particles.length;i++){
    particle=particles[i];
    context.beginPath();
    context.lineWidth=particle.diameter;
    context.strokeStyle=particle.color;
    x=particle.x+particle.tilt;
    context.moveTo(x+particle.diameter/2,particle.y);
    context.lineTo(x,particle.y+particle.tilt+particle.diameter/2);
    context.stroke();}
  }
function updateParticles(){
  var width=window.innerWidth;
  var height=window.innerHeight;
  var particle;waveAngle+=0.01;
  for(var i=0;i<particles.length;i++){
    particle=particles[i];
    if(!streamingConfetti&&particle.y<-15)
        particle.y=height+100;
    else{
      particle.tiltAngle+=particle.tiltAngleIncrement;particle.x+=Math.sin(waveAngle);particle.y+=(Math.cos(waveAngle)+particle.diameter+particleSpeed)*0.5;particle.tilt=Math.sin(particle.tiltAngle)*15;
    }
    if(particle.x>width+20||particle.x<-20||particle.y>height){
      if(streamingConfetti&&particles.length<=maxParticleCount)
        resetParticle(particle,width,height);
      else{particles.splice(i,1);
        i--;}
      }
    }
  }
})();

$('[data-toggle="tooltip"]').tooltip({ boundary: 'window' });  
    </script>
    
<script src="<?php echo base_url('assets/frontend/js/ui.js'); ?>"></script>
