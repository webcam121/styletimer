jQuery(function($){
/* ----------------------------------------------------------- */
/* Slick slider example
/* ----------------------------------------------------------- */  

$("#regular").slick({
  lazyLoad: 'ondemand',
  arrows: false,
  dots: true,
  slidesToShow: 3,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 2000,
  responsive: [
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

/* ----------------------------------------------------------- */
/* Slick slider example
/* ----------------------------------------------------------- */  

$("#v_regular").slick({
  arrows: true,
  dots: false,
  slidesToShow: 6,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 2000,
  responsive: [
  {
      breakpoint: 1800,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
      }
    },
  {
      breakpoint: 1326,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 1000,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 620,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});


/* ----------------------------------------------------------- */
/* Slick slider example
/* ----------------------------------------------------------- */  

$("#v_regular1").slick({
  arrows: true,
  dots: false,
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 2000,
  responsive: [
  {
      breakpoint: 1800,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
      }
    },
  {
      breakpoint: 1326,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 1000,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 620,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});



/* ----------------------------------------------------------- */
/* Slick product slider example
/* ----------------------------------------------------------- */  

$('.slider-for').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  fade: true,
  asNavFor: '.slider-nav'
});
$('.slider-nav').slick({
  slidesToShow: 6,
  slidesToScroll: 1,
  asNavFor: '.slider-for',
  dots: false,
  centerMode: true,
  focusOnSelect: true
});

if ($(".single-slider > picture").length == 2) {
  $(".single-slider").html($(".single-slider").html() + $(".single-slider").html());
}

$('.single-slider').slick({
  dots: false,
  infinite: true,
  speed: 500,
  slidesToShow: 2,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: false,
  loop: true,
  // variableWidth: true,
  // accessibility: false,
  // centerMode: true,
  arrows: true, 
  // swipeToSlide: true,
  infinite: true,
  responsive: [
    {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: false, 
          dots: true,
        }
      },
  ]
});







/* ----------------------------------------------------------- */
/* modal open close any where only close button will close
/* ----------------------------------------------------------- */  

  $(document).ready(function(){
    $('.launch-modal').click(function(){
      $('.notclose1').modal({
        backdrop: 'static'
      });
    }); 
  });
/* ----------------------------------------------------------- */
/* modal multiple open second remove
/* ----------------------------------------------------------- */  

$(document).on("click","#forgot_model",function(){
  $('.login').modal('hide');
  
});

/* ----------------------------------------------------------- */
/* icon upload
/* ----------------------------------------------------------- */  

$(document).on('click','.clickClass',function(){
    $(this).siblings('input.clickClass').click();
});

/* ----------------------------------------------------------- */
/* price range slider
/* ----------------------------------------------------------- */ 

var slider = document.getElementById('test-slider');
    noUiSlider.create(slider, {
    start: [0, 1000],
    connect: true,
    step: 5,
    orientation: 'horizontal', // 'horizontal' or 'vertical'
     range: {
    'min': 0,
    '2%': [20, 10],
    '10%': [100, 25],
    '20%': [200, 100],
    'max': 1000
},
  format: wNumb({
  decimals:false
})
});

slider.noUiSlider.on('update', function (values, handle) {

    var value = values[handle];

    if (handle){
    value = Math.round(value);
    $('#endRange').text(value+" €");
    $('#endRangeVal').val(value);
    //alert("if"+value);
        //inputNumber.value = value;
    } else {
         value = Math.round(value);
        $('#startRange').text(value+" €");
        $('#startRangeVal').val(value);
      
    }
    //mapview();
});
slider.noUiSlider.on('end', function (values, handle) {
  mapview();
});
 //~ noUiSlider.slide(event, ui {
  //~ alert(ui.values[0]); 
   
//~ });
//~ slide: function(event, ui) {
      //~ $("#search_radius").val(ui.values[0]);
      //~ $("#range").text(ui.values[0]);
      //~ var mi = ui.values[0];
      //~ var mx = ui.values[1];
      //~ filterSystem(mi, mx);
    //~ }


/* ----------------------------------------------------------- */
/* button click effect
/* ----------------------------------------------------------- */ 



/* ----------------------------------------------------------- */
/* header top script
/* ----------------------------------------------------------- */ 









});
