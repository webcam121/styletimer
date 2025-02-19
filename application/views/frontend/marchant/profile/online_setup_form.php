 <div class="salon-new-top-bg pt-4">
  <div class="pl-20 pr-20 d-flex">
    <h3 class="font-size-20 color333 fontfamily-medium mb-2"><?php echo $this->lang->line('setup_your_profile'); ?></h3>
  </div>
  <div class="salon-new-step">
      <?php if(!empty($setup_no)) $data['setup_no']=$setup_no;  $this->load->view('frontend/marchant/common_setup',$data); ?>
  </div>
</div>

<div class="bgwhite relative pt-4 px-3">
                
  <form id="onlinrsetup_form" method="post" action="<?php echo base_url('profile/online_setup'); ?>">
    <input type="hidden" name="id" value="<?php echo $this->session->userdata('st_userid'); ?>">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-10 text-center m-auto">
        <h4 class="mb-20"> HERZLICHEN GLÜCKWUNSCH! </h4>
        <img src="<?php echo base_url('assets/frontend/images/fireworks.png'); ?>" width="106" height="106">
        <p class="color333 font-size-16 fontfamily-semibold mt-20 mb-20" style="width: 90%;margin-left:auto;margin-right:auto;">
          Du hast deinen Salon erfolgreich eingerichtet und kannst styletimer ab jetzt 4 Wochen lang kostenlos testen.
        </p>
        <p class="color666 font-size-13 fontfamily-regular">Antworten auf häufig gestellte Fragen zur Nutzung und allen Funktionen von styletimer finden Sie unter <a href="<?php echo base_url('faq'); ?>" target="_blank" class="text-underline" style="color:#00b3bf">häufige Fragen</a> im Profilmenü oben rechts.</p>  
        
        <p class="color666 font-size-13 fontfamily-regular">Wir empfehlen die Browser Google Chrome und Mozilla Firefox für ein optimales Nutzererlebnis.</p>  
      </div>
      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
        <div class="pt-3 pb-3 bgwhite boxshadow-5 px-3 mt-4 text-center d-flex align-items-center justify-content-center">
          <button  type="button" id="" class="btn btn-border-orange btn-large widthfit2 ml-0" onclick="getAddServicehtml();"><?php echo $this->lang->line('Previous'); ?></button>
          <button  type="button" id="onlinrsetup_submit" class="btn btn-large widthfit2 mr-0">
            Einrichtung abschliessen
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
