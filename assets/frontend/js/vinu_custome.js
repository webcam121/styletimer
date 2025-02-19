

$(document).ready(function(){
    $("#tab-box-v1").addClass("active");
    
    
    $(".mainCatSliderTab").click(function(){  
	var tabid=$(this).attr('data-id');
	
	$(".mainCatSliderTab").each(function(){
	    $(this).removeClass("active");  
		});
		
    $(this).addClass("active");  
    $(".mainCatTabs").each(function(){
		$(this).addClass("display-n");    
		})
    $("#tab-box"+tabid).removeClass("display-n");  
  });
});

$(document).ready(function(){
  $(".showmore").click(function(){
    $(".hidden-display").toggleClass('showmoretoggle'); 
  });
});

/**/
/**/
$(document).ready(function(){
  $(".a_show-changepassword").click(function(){    
    $(".show-changepassword").removeClass('display-n');
    $(".hide-changepassword").addClass('display-n');
  });
});
$(document).ready(function(){
  $(".again-show-clickhere").click(function(){    
    $(".show-changepassword").addClass('display-n');
    $(".hide-changepassword").removeClass('display-n');
  });
});

$(document).ready(function(){
  $(".left-side-dashbord").addClass('hide-left-bar');
  $(".left-side-arrow").click(function(){     
    $(this).css('left','0px'); 
    if("left-side-dashbord bgwhite box-shadow1 pt-10 scroll-effect left-bar-dask"==$('#left-id').attr('class')){
        $(this).css('left','0px');   
        $(".left-side-dashbord").toggleClass('hide-left-bar');
    }
    else{
        $(this).css('left','210px'); 
        $(".left-side-dashbord").toggleClass('hide-left-bar');
    }
    
  });
});

/*for dasktop view sidebar*/
$(document).ready(function(){ 
  //$(".left-side-dashbord").addClass('left-bar-dask');
//$(".right-side-dashbord").css('padding-left','95px');
  $(".menu-toggle").click(function(){ 
 
 if("left-side-dashbord bgwhite box-shadow1 pt-10 scroll-effect hide-left-bar"==$('#left-id').attr('class')){
    //$(this).css('left','0px');
    $('.testts').each(function(){
           $(this).attr('title', '');
           $(this).attr('data-original-title', '');
           
      }); 
    var sidebar ='open';
    $(".left-side-dashbord").toggleClass('left-bar-dask');
    $(".right-side-dashbord").css('padding-left','230px');
   setTimeout(function(){  $('.left-side-dashbord.left-bar-dask a span.colorwhite').css('display','inline-block');
 }, 300);
  } 
else{
    
    $('.testts').each(function(){
      var tt=$(this).attr('data-temp');
           $(this).attr('title', '');
           $(this).attr('data-original-title', tt);
      });   
      $('[data-toggle="tooltip"]').tooltip({ boundary: 'window' });
      var sidebar ='close';
    //$(this).css('left','210px'); 
    $(".left-side-dashbord").toggleClass('left-bar-dask');
    $(".right-side-dashbord").css('padding-left','95px');
    $('.left-side-dashbord a span.colorwhite').css('display','none');
    }  

    
    $.ajax({
        url: base_url+"merchant/test_cookies",
        type: "POST",
        data:{ status : sidebar},
        success: function (response) {
          if(response){
           }
         }
      });

  });
});


/*change icon nav-icon*/
$(document).ready(function () {
  $('.nav-item.r_l_s_s_h_dekstop').click(function(){
    $('.onhovershow').addClass('show');
    //$('.dropdown-menu').addClass('show');
  });
  $('.nav-item.r_l_s_s_h_dekstop').click(function(){
    $('.onhovershow').removeClass('show');
    //$('.dropdown-menu').removeClass('show');
  });
});

$(document).ready(function () {
  
  $('.changeimg').mouseover(function () {
    if($(this).attr('id')=='v-test'){
      $('#img1').attr('src',base_url+'assets/frontend/images/vuser_profile_icon666.svg');      
      $('#img2').attr('src',base_url+'assets/frontend/images/change_password_icon.svg');
      $('#img3').attr('src',base_url+'assets/frontend/images/booking_icon666.svg');
      $('#img4').attr('src',base_url+'assets/frontend/images/hard-icon666.svg');
      $('#img5').attr('src',base_url+'assets/frontend/images/layout.svg');
      $('#img6').attr('src',base_url+'assets/frontend/images/dashboard_icon666.svg');
    }
    else if($(this).attr('id')=='v-test1'){
       $('#img1').attr('src',base_url+'assets/frontend/images/vuser_profile_icon.svg');
       $('#img2').attr('src',base_url+'assets/frontend/images/change_poss_orange_icon.svg');
       $('#img3').attr('src',base_url+'assets/frontend/images/booking_icon666.svg');
       $('#img4').attr('src',base_url+'assets/frontend/images/hard-icon666.svg');
       $('#img5').attr('src',base_url+'assets/frontend/images/layout.svg');
       $('#img6').attr('src',base_url+'assets/frontend/images/dashboard_icon666.svg');
    }
    else if($(this).attr('id')=='v-test2'){
       $('#img1').attr('src',base_url+'assets/frontend/images/vuser_profile_icon.svg');
       $('#img2').attr('src',base_url+'assets/frontend/images/change_password_icon.svg');
       $('#img3').attr('src',base_url+'assets/frontend/images/booking_icon_orange.svg');
       $('#img4').attr('src',base_url+'assets/frontend/images/hard-icon666.svg');
       $('#img5').attr('src',base_url+'assets/frontend/images/layout.svg');
       $('#img6').attr('src',base_url+'assets/frontend/images/dashboard_icon_orange.svg');
    }
    else if($(this).attr('id')=='v-test3'){
       $('#img1').attr('src',base_url+'assets/frontend/images/vuser_profile_icon.svg');
       $('#img2').attr('src',base_url+'assets/frontend/images/change_password_icon.svg');
       $('#img3').attr('src',base_url+'assets/frontend/images/booking_icon666.svg');
       $('#img4').attr('src',base_url+'assets/frontend/images/hard-icon_orange.svg');
       $('#img5').attr('src',base_url+'assets/frontend/images/layout.svg');
       $('#img6').attr('src',base_url+'assets/frontend/images/dashboard_icon666.svg');
    }
    else if($(this).attr('id')=='v-test4'){
       $('#img1').attr('src',base_url+'assets/frontend/images/vuser_profile_icon.svg');
       $('#img2').attr('src',base_url+'assets/frontend/images/change_password_icon.svg');
       $('#img3').attr('src',base_url+'assets/frontend/images/booking_icon666.svg');
       $('#img4').attr('src',base_url+'assets/frontend/images/hard-icon666.svg');
       $('#img5').attr('src',base_url+'assets/frontend/images/layout_orange.svg');
       $('#img6').attr('src',base_url+'assets/frontend/images/dashboard_icon666.svg');
    }
    else if($(this).attr('id')=='v-test5'){
       $('#img7').attr('src',base_url+'assets/frontend/images/booking_icon_orange.svg');
    }
    else if($(this).attr('id')=='v-test11'){
       $('#img8').attr('src',base_url+'assets/frontend/images/recommendation-orange.svg');
    }
    else if($(this).attr('id')=='v-test13'){
      $('#img9').attr('src',base_url+'assets/frontend/images/membershi-icon-orange.svg');
   }
   else if($(this).attr('id')=='v-test14'){
    $('#img10').attr('src',base_url+'assets/frontend/images/faq-icon-orange.svg');
 }
 else if($(this).attr('id')=='v-test15'){
  $('#img11').attr('src',base_url+'assets/frontend/images/payment-icon-orange.svg');
}
   
   
  });
  $('.changeimg').mouseleave(function () {    
      $('#img1').attr('src',base_url+'assets/frontend/images/vuser_profile_icon.svg');      
      $('#img2').attr('src',base_url+'assets/frontend/images/change_password_icon.svg');
      $('#img3').attr('src',base_url+'assets/frontend/images/booking_icon666.svg');
      $('#img4').attr('src',base_url+'assets/frontend/images/hard-icon666.svg');
      $('#img5').attr('src',base_url+'assets/frontend/images/layout.svg');
      $('#img6').attr('src',base_url+'assets/frontend/images/dashboard_icon666.svg'); 
      $('#img7').attr('src',base_url+'assets/frontend/images/booking_icon666.svg');  
      $('#img8').attr('src',base_url+'assets/frontend/images/recommendation.svg');  
      $('#img9').attr('src',base_url+'assets/frontend/images/membershi-icon666.svg');  
      $('#img10').attr('src',base_url+'assets/frontend/images/faq-icon666.svg');  
      $('#img11').attr('src',base_url+'assets/frontend/images/payment-icon666.svg');  
     });
})


/*home tab*/
$(".home-salon-tab").hide();
$("#home-servics-tab").click(function(){
  $(this).addClass('active');
  $('#home-salon-tab').removeClass('active');
    $(".home-salon-tab").hide();
    $(".home-servics-tab").show();
  });
  $("#home-salon-tab").click(function(){ 
  $(this).addClass('active');   
  $('#home-servics-tab').removeClass('active');
    $(".home-servics-tab").hide();
    $(".home-salon-tab").show();
  });
/*home tab*/
