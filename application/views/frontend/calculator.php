
  <?php $this->load->view('frontend/common/header'); ?>  
  <style>
      input.error{
          position:relative !important;
          display:inline-block !important;
      }
      .error{
          margin-top:5px !important;
      }
 label {
   cursor: default;
}
  </style>
  <section class="<?php if(empty($this->session->userdata('access')) || $this->session->userdata('access')=='user') echo 'colcu-sec1'; else echo 'colcu-sec1 login-small-header'; ?>">
      <div class="container">
          <form id="calculatorForm" method="post">
            <div class="row">
                <div class="col-12 col-sm-12 col-xl-12">
                    <h3 class="fontsize-24 color333 fontfamily-medium text-center mb-5">Rechnen Sie einfach selbst aus, was styletimer für ihren Salon tun kann!</h3>
                </div>
                <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-7 m-auto">
                    <div class="form-group form-group-mb-50" >
                        <label class="calcu-label">Wieviele Buchungen nehmen Sie pro Tag manuell entgegen ?</label>
                        <div class="relative" id="booking_take_perweek_validate">
                            <label class="inp ">
                                <input type="text" placeholder="&nbsp;" class="form-control" name="booking_take_perweek" value="15">                                   
                            </label>   
                        </div>                                             
                    </div>
                    
                    <div class="form-group form-group-mb-50" >
                        <label class="calcu-label">Wie lange dauert es im Schnitt, bis Sie eine Buchung entgegen genommen haben ?</label>
                        <div class="relative" id="taking_a_booking_in_min_validate">
                            <label class="inp">
                                <input id="txt" type="text" value="2" name="taking_a_booking_in_min"><span id="hide"></span> <span class="span-center">Minuten</span>                        
                            </label>   
                        </div>                                             
                    </div>
                    <div class="form-group form-group-mb-50" id="">
                        <label class="calcu-label">Steigerung der Buchungen durch Buchungsmöglichkeit auch ausserhalb der Öffnungszeiten:</label>
                        <div class="relative" id="increase_in_booking_percent_validate">
                            <label class="inp">
                                <input id="txt2" type="text" value="20" name="increase_in_booking_percent"><span id="hide2"></span> <span class="span-center">%</span>                         
                            </label>    
                        </div>                                               
                    </div>
                    <div class="form-group form-group-mb-50" id="">
                        <label class="calcu-label">Welchen Umsatz machen ihre Kunden im Schnitt pro Besuch ?</label>
                        <div class="relative" id="average_spend_per_treatment_validate">
                            <label class="inp">
                                <input id="txt1" type="text" value="30" name="average_spend_per_treatment"><span id="hide1"></span> <span class="span-center">€</span>                          
                            </label>        
                        </div>                                           
                    </div>
                    <div class="form-group form-group-mb-50" id="">
                        <label class="calcu-label">Wieviele Kunden erscheinen pro Woche nicht zum vereinbarten Termin ?</label>
                        <div class="relative" id="how_many_cancelled_in_a_week_validate">
                            <label class="inp ">
                                <input type="text" placeholder="&nbsp;" class="form-control" value="5" name="how_many_cancelled_in_a_week">                                   
                            </label> 
                        </div>                                               
                    </div>

                  

                    <div class="text-center mt-4">
                        <button type="submit" class="btn width250 ">Berechnen</button>
                    </div>
                      <div class="result">
						   <h5 class="fontsize-16 color333 fontfamily-semibold mb-4 text-center">Ergebnis:</h5>

                        <h5 class="fontsize-16 color333 fontfamily-semibold text-center">Jeden Monat durch styletimer eingesparte Zeit : <span id="saved_hours" class="completed">12 Stunden</span></h5>
                        <h5 class="fontsize-16 color333 fontfamily-semibold text-center">Monatlich durch styletimer generiertes Umsatzplus : <span id="revenue_increase" class="completed">2640 €</span></h5>

                    </div>
                  <!--  <div class="relative pt-5">

                       
                        <p class="color333 fontfamily-regular fontsize-14">Auf Grundlage der obigen Daten Konnen Sie Durch die Nutzung von styletimer pro Monat <span class="colorcyan">'time saved in hours' Stunden</span> fur die terminplanung sparen und ihren Umsatz monatlic um <span class="colorcyan">'revenue incresed in round numbers' € </span>erhohen.</p>
                    </div>-->
                </div>

            </div>
        </form>
      </div>
  </section>

<script>
var hide = document.getElementById('hide');
var txt = document.getElementById('txt');
var hide1 = document.getElementById('hide1');
var txt1 = document.getElementById('txt1');
var hide2 = document.getElementById('hide2');
var txt2 = document.getElementById('txt2');

resize();
txt.addEventListener("input", resize);
txt1.addEventListener("input", resize);
txt2.addEventListener("input", resize);

function resize() {
  hide.textContent = txt.value;
  txt.style.width = hide.offsetWidth + "px";

  hide1.textContent = txt1.value;
  txt1.style.width = hide1.offsetWidth + "px";

  hide2.textContent = txt2.value;
  txt2.style.width = hide2.offsetWidth + "px";
}
</script>

<?php $this->load->view('frontend/common/footer'); ?>


