 <?php  $this->load->view('frontend/common/head');  ?>

  <body>
  <section class="user_registration_done_thankyou_section thankyou-new-page-effect">
    <div class="container">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 d-flex height-vh justify-content-center">
        <div class="align-center pt-20 pb-20 align-self-center">
          <div class="thankyou_top_img_block2">
            <img src="<?php echo base_url('assets/frontend/images/thankyou_top_img2.svg'); ?>" class="thankyou_top_img2">
          </div>
          <div class="relative mt-25">
            <h1 class="fontfamily-medium font-size-70 colorlightgreen2 mb-0"><?php echo $this->lang->line('Congratulation'); ?>!</h1>
            <img src="<?php echo base_url('assets/frontend/images/thankyou_mid_line_img.svg'); ?>" class="thankyou_mid_line_img">
          </div>
            <div class="mt-50">
              <h4 class="color333 fontfamily-medium font-size-30"><?php echo $this->lang->line('Your-account-set'); ?></h4>
              <p class="mt-30 mb-30 color666 font-size-20 fontfamily-regular"><?php echo $this->lang->line('if-you-want'); ?></p>
              <a href="<?php echo base_url('merchant/mydashboard'); ?>"><button type="button" class="btn widthfit"><?php echo $this->lang->line('Go-To-Dashboard'); ?></button></a>
            </div>
        </div>
      </div>
    </div>
  </section>

 <?php  $this->load->view('frontend/common/footer_script');  ?>

<script type="text/javascript">
      $(document).ready(function(){
      toggleConfetti();
      
      /*setInterval(function(){ 
            toggleConfetti();
      }, 2000);*/

      setTimeout(function() {
            stopConfetti();
            }, 4000);

      });


  var maxParticleCount=150;
  var particleSpeed=2;
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
  function startConfettiInner(){var width=window.innerWidth;var height=window.innerHeight;window.requestAnimFrame=(function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(callback){return window.setTimeout(callback,16.6666667);};})();var canvas=document.getElementById("confetti-canvas");if(canvas===null){canvas=document.createElement("canvas");canvas.setAttribute("id","confetti-canvas");canvas.setAttribute("style","display:block;z-index:999999;pointer-events:none");document.body.appendChild(canvas);canvas.width=width;canvas.height=height;window.addEventListener("resize",function(){/*canvas.width=window.innerWidth;canvas.height=window.innerHeight;*/},true);}
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
    </script>
