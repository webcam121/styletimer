<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php $this->load->view('frontend/common/head'); $keyGoogle =GOOGLEADDRESSAPIKEY; ?>
<script src='https://www.google.com/recaptcha/api.js'></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript">
    <?php if($this->session->flashdata('success')){ ?>
        toastr.success("<?php echo $this->session->flashdata('success'); ?>");
    <?php }else if($this->session->flashdata('error')){  ?>
        toastr.error("<?php echo $this->session->flashdata('error'); ?>");
    <?php }else if($this->session->flashdata('warning')){  ?>
        toastr.warning("<?php echo $this->session->flashdata('warning'); ?>");
    <?php }else if($this->session->flashdata('info')){  ?>
        toastr.info("<?php echo $this->session->flashdata('info'); ?>");
    <?php } ?>
</script>
<style>
  .width-450 {
    max-width: 450px;
    width: 100%;
  }
  .error_label{
    position: relative !important;
    }
    .error_label#busType_err{
      position: absolute!important;
      margin-top: 0px !important;
      display: block;
      width: auto;
    }
    .nav-link {
        position: relative;
        color: #333 !important;
        padding: 10px;
    }
    .nav-link.colorwhite,.nav-link.colorwhite:hover {
      color:#fff !important;
    }
    #supermenu-accordion{
      display:none !important;
    }
    .header {
      height: 64px !important;
  }
  #terms_err{
    position:static !important;
    top:13px !important;
  }
  .navbar-brand{
    padding-top:3px !important;
  }
  .header .navbar-light .navbar-nav .nav-link {
    color: #333 !important;
  }
  .header .navbar-light .navbar-nav .nav-link.header-btn.bgpinkorangegradient.colorwhite{
    color:#fff !important;
  }
  .col-xl-5 {
      flex: 0 0 41.666667%;
      max-width: 42.666667% !important;
  }
  .para-wrap {
    margin-top: 1rem;
    margin-bottom: 1rem;
  }
  .para-title {
    font-weight: 700;
    margin: 0px;
    font-size: 24px;
    line-height: normal;
    font-family: Tahoma, Geneva, sans-serif;;
    color: rgb(0, 0, 0);
    -webkit-text-stroke-width: initial;
    -webkit-text-stroke-color: rgb(0, 0, 0);
    box-sizing: border-box;
    -webkit-font-smoothing: antialiased;
    margin-top: 2rem;
    margin-bottom: 1rem;
  }
  .para-content {
    font-family: Tahoma, Geneva, sans-serif;
    font-size: 18px;
    color: black;
    line-height: 1.5em;
  }
  .para-hr {
    box-sizing: content-box;
    height: 0px;
    margin-top: 18px;
    margin-bottom: 18px;
    border-width: 1px 0px 0px;
    border-top-style: solid;
    border-top-color: rgb(238, 238, 238);
    -webkit-font-smoothing: antialiased;
    clear: both;
    -webkit-user-select: none;
    break-after: page;
    width: 100%;
  }
  .para-image {
    width:100%;
    object-fit:contain;
    text-align:center;
  }
  .text-col-cyan {
    color: rgb(0, 179, 190);
  }
  .text-italic {
    font-style: italic;
  }
  .text-sm {
    font-size: 15px;
  }

  .dn-md {
    display: none;
  }
  .dn-sm {
    display: block;
  }
  @media(max-width:767px){
    .dn-md {
      display: block;
    }
    .dn-sm {
      display: none;
    }
  }
  </style>
<?php $this->load->view('frontend/common/header'); ?>
<section class="login_register_sections pt-65 clear">
<section class="bggreengradient marchand-registration clear pt-4">
  <div class="container">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 order-1">
        <h1 class="colorwhite fontsize-34 fontfamily-bold mb-3">Stressfreie Salonverwaltung made in Hessen</h1>
        <picture class="width-450 dn-md">
          <!--  <source srcset="<?php //echo base_url('assets/frontend/images/header-registration.webp'); ?>" type="image/webp" class="width-450">-->
          <source srcset="<?php echo base_url('assets/uploads/staticpages/static_1655403194.png'); ?>" type="image/png" class="width-450">
          <img class="width-450" src="<?php echo base_url('assets/uploads/staticpages/static_1655403194.png');?>">
        </picture>
        <ul class="mb-4">
          <li class="fontsize-18 color333 fontfamily-regular mb-3" ><img src="<?php echo base_url('assets/frontend/images/rightround24v.svg'); ?>" class="salon-registration-right-img" alt="">Digitale Termin- und Kundenverwaltung mit automatisiertem Buchungsmanagement</li>
          <li class="fontsize-18 color333 fontfamily-regular mb-3" ><img src="<?php echo base_url('assets/frontend/images/rightround24v.svg'); ?>" class="salon-registration-right-img" alt="">Automatische Terminerinnerungen an Ihre Kunden</li>
          <li class="fontsize-18 color333 fontfamily-regular mb-3" ><img src="<?php echo base_url('assets/frontend/images/rightround24v.svg'); ?>" class="salon-registration-right-img" alt="">Online Terminbuchung via Web & Apps mit intelligentem Kalender rund um die Uhr</li>
          <li class="fontsize-18 color333 fontfamily-regular mb-3" ><img src="<?php echo base_url('assets/frontend/images/rightround24v.svg'); ?>" class="salon-registration-right-img" alt="">Mehr Zeit für den Termin mit Ihrem Kunden</li>
          <li class="fontsize-18 color333 fontfamily-regular mb-3" ><img src="<?php echo base_url('assets/frontend/images/rightround24v.svg'); ?>" class="salon-registration-right-img" alt="">1 Monat kostenlos und unverbindlich testen</li>
          <li class="fontsize-18 color333 fontfamily-regular mb-3" ><img src="<?php echo base_url('assets/frontend/images/rightround24v.svg'); ?>" class="salon-registration-right-img" alt="">Risikofrei, weil monatlich kündbar</li>
          <li class="fontsize-18 color333 fontfamily-regular mb-3" ><img src="<?php echo base_url('assets/frontend/images/rightround24v.svg'); ?>" class="salon-registration-right-img" alt="">Keine verstecken Kosten</li>
        </ul>

        <span class="colorwhite"></span>
      </div>
      
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 order-2 dn-sm">
        <div class="text-right ">
          <!-- <img src="<?php //echo base_url('assets/frontend/images/static_1594819174.png'); ?>" class="width-400" alt=""> -->
          <picture class="width-450">
           <!--  <source srcset="<?php //echo base_url('assets/frontend/images/header-registration.webp'); ?>" type="image/webp" class="width-450">-->
            <source srcset="<?php echo base_url('assets/uploads/staticpages/static_1655403194.png'); ?>" type="image/png" class="width-450">
            <img class="width-450" src="<?php echo base_url('assets/uploads/staticpages/static_1655403194.png');?>">
          </picture>
        </div>
      </div>
      <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12 order-3">
        <button type="button" class="btn mb-1 mt-3 regiscroll" style="width:fit-content;text-transform:none;max-width: 100%;white-space: normal;">styletimer kostenlos testen!</button>
      </div>
    </div>
  </div>
</section>
    <div class="container">    
          <div class="row">
            <div class="col-12 para-wrap">
              <p class="para-title text-center mb-5">
                Sie möchten Ihren <span class="text-col-cyan">Kalender online</span> verwalten, <br/>
                neue <span class="text-col-cyan">Kunden gewinnen</span> und weniger Terminausfälle?
              </p>
              <p class="para-content text-center">
                Teilen Sie Ihren Terminkalender online mit Ihren Kunden. Sie tragen die verfügbaren Zeiten ein. Ihre Kunden buchen dann die gewünschte Behandlung eigenständig – schnell und rund um die Uhr. So füllt sich Ihr Kalender ganz von selbst! Sie und Ihre Mitarbeiter haben immer alle Termine im Blick. <br/><br/><b>Dadurch bleibt mehr Zeit für das Wichtigste: Ihre Kunden</b><br/><br/>
                Aber styletimer kann noch deutlich mehr. Organisieren Sie Kundentermine und die Arbeitszeiten Ihrer Mitarbeiter. Gewinnen Sie Neukunden und steigern Sie Ihren Umsatz nachhaltig.<br/>
                <br/>
                Jetzt ganz einfach für den kostenlosen Probemonat anmelden: Wir richten styletimer gebührenfrei für Sie ein! styletimer ist in verschiedenen Abo-Modellen erhältlich und monatlich kündbar.
              </p>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-md-6 col-lg-4">
                  <img class="para-image" src="<?php echo base_url('assets/uploads/staticpages/static_1655403193.png');?>">
                </div>
                <div class="col-md-6 col-lg-8">
                  <p class="para-title text-col-cyan text-left">
                    Bis zu 50% mehr Termine
                  </p>
                  <p class="para-content">
                    Umfragen belegen, dass die Mehrzahl der Kunden Ihre Termine gerne auch außerhalb der Öffnungszeiten buchen möchten. Ermöglichen Sie Ihren Kunden eine online Buchung – mit styletimer. <br/>
                    <br/>
                    Sie richten Ihren persönlichen Kalender ein und der Kunde bucht selbst den passenden Termin per Web oder App. <br/>
                    <br/>
                    <b>Gewinnen Sie so bis zu 50% mehr Termine – zu jeder Tageszeit!</b>
                  </p>
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <p class="para-title text-col-cyan">
                Termine übersichtlich verwalten
              </p>
              <p class="para-content">
                Die online über styletimer gebuchten Termine erscheinen in Echtzeit in Ihrem Kalender. Aber auch telefonisch vereinbarte Termine können Sie oder Ihre Mitarbeiter natürlich weiterhin mit einem Klick in den styletimer eintragen. So behalten Sie jederzeit den vollen Überblick.
              </p>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-md-6 col-lg-8">
                  <p class="para-title text-col-cyan text-left">
                    Weniger Terminausfälle
                  </p>
                  <p class="para-content">
                    Laut Studien fallen zu 15% aller Termine im Beauty-Bereich aus. Kunden erscheinen nicht oder haben Ihren Termin schlichtweg vergessen. <br/>
                    <br/>
                    <b>Das kostet Sie Zeit und bares Geld. </b><br/>
                    <br/>
                    Styletimer erinnert Ihre Kunden automatisch an anstehende Buchungen und reduziert Terminausfälle so deutlich.
                  </p>
                </div>
                <div class="col-md-6 col-lg-4">
                  <img class="para-image" src="<?php echo base_url('assets/uploads/staticpages/static_1656576285.png');?>">
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-md-6 col-lg-5">
                  <img class="para-image p-4" src="<?php echo base_url('assets/uploads/staticpages/static_1624634314.png');?>">
                </div>
                <div class="col-md-6 col-lg-7">
                  <p class="para-title text-col-cyan text-left">
                    Intelligenter Kalender 
                  </p>
                  <p class="para-content">
                    Jeder Mitarbeiter hinterlegt seine verfügbaren Arbeitszeiten in styletimer. Ihr Kunde hat einen bevorzugten Mitarbeiter? Dann wählt er oder sie diesen Mitarbeiter direkt mit aus. Außerdem können Sie Ihr gesamtes Team online vorstellen – so wird Ihr Service noch persönlicher.  <br/>
                    <br/>
                    Wählt ein Kunde keine spezifischen Mitarbeiter aus, wird der Termin automatisch einem Mitarbeiter mit freien Kapazitäten zugewiesen. So verteilt styletimer die Termine gleichmäßig auf Ihre Mitarbeiter.
                  </p>
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-md-6 col-lg-7">
                  <p class="para-title text-col-cyan text-left">
                    Übersichtliches Personal-Management
                  </p>
                  <p class="para-content">
                    styletimer hilft Ihnen, die Arbeitszeiten Ihres Teams zu organisieren. Sie oder Ihre Mitarbeiter tragen alle verfügbaren Termine in den Kalender ein.  <br/>
                    <br/>
                    So planen Sie Ihr Personal fest ein und Ihre Kunden sehen, wer wann verfügbar ist. Flexible Arbeitszeiten sind für Sie und Ihr Team einfacher nachvollziehbar.
                  </p>
                </div>
                <div class="col-md-6 col-lg-5">
                  <img class="para-image" src="<?php echo base_url('assets/uploads/staticpages/static_1610996663.png');?>">
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-md-6 col-lg-5">
                  <img class="para-image" src="<?php echo base_url('assets/uploads/staticpages/static_1655403194.png');?>">
                </div>
                <div class="col-md-6 col-lg-7">
                  <p class="para-title text-col-cyan text-left">
                    Ein zusätzlicher - unbezahlter - Mitarbeiter
                  </p>
                  <p class="para-content">
                    Mit styletimer ersparen Sie sich durchschnittlich 5-7 Stunden pro Woche beim Vereinbaren von Terminen und vielen weiteren ungeliebten Verwaltungsaufgaben! Ihre Kunden sehen alle noch verfügbaren Termine in Echtzeit und können Buchungen bearbeiten, überprüfen und neu buchen, alles über styletimer.<br/>
                    <br/>
                    <b>Im Salon klingelt das Telefon deutlich seltener und Ihre Kunden genießen Ihre Behandlungen ganz entspannt. </b><br/>
                    <br/>
                    Im Schnitt dauert es 2-3 Minuten, einen Termin telefonisch zu vereinbaren. styletimer nimmt Ihnen diese Zeit komplett ab und steigert gleichzeitig die Zufriedenheit Ihrer Kunden. Ihr Terminkalender füllt sich währenddessen ganz von selbst.
                  </p>
                </div>
                <div class="col-12">
                  <p class="para-title text-col-cyan text-left">
                  </p>
                  <p class="para-content text-center">
                    <b><u>Nachfolgend ein kurzes Rechenbeispiel: </u></b><br/>
                    <br/>
                    Entgegengenommene Buchungen pro Tag: <span style="color:brown;font-weight:bold;">15</span><br/><br/>
                    Durchschnittliche Dauer eines Telefonats: <span style="color:brown;font-weight:bold;">2 Minuten</span><br/><br/>
                    Steigerung der Buchungen durch Buchungsmöglichkeit ausserhalb der Öffnungszeiten: <span style="color:brown;font-weight:bold;">20%</span><br/>
                    <br/>
                    Durchschnittlicher Umsatz pro Buchung: <span style="color:brown;font-weight:bold;">30€</span><br/><br/>
                    Durch Kunden verpasste Termine (pro Woche): <span style="color:brown;font-weight:bold;">5</span> <br/>
                    <br/>
                    <b><u>Ergebnis:</u></b><br/><br/>
                    Jeden Monat durch styletimer eingesparte Zeit : <span style="color:limegreen;font-weight:bold;">12 Stunden</span><br/><br/>
                    Monatlich durch styletimer generiertes Umsatzplus : <span style="color:limegreen;font-weight:bold;">2640 €</span><br/>
                    <br/>
                    <b>Glauben Sie nicht ? Rechnen Sie die genaue Ersparnis für Ihren Salon ganz einfach selbst aus unter:</b><br/><br/>
                    <a href="https://www.styletimer.de/rechner" class="text-col-cyan"><b>https://www.styletimer.de/rechner</b></a>
                  </p>
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <p class="para-title text-col-cyan">
                Bequeme Salonverwaltung 
              </p>
              <p class="para-content">
                Mit styletimer läuft die Verwaltung Ihres Salons wie von selbst. Sie haben alle Infos auf einen Blick: freie Zeitfenster, gebuchte Termine und verfügbares Personal. Mit eigenen Logins verwalten Ihre Mitarbeiter Ihre Termine auf Wunsch selbstständig.
              </p>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-md-6 col-lg-5">
                  <img class="para-image" src="<?php echo base_url('assets/uploads/staticpages/static_876542124.png');?>">
                </div>
                <div class="col-md-6 col-lg-7">
                  <p class="para-title text-col-cyan text-left">
                    Zeigen Sie, was Sie können!
                  </p>
                  <p class="para-content">
                    Ihre Arbeiten sind Ihre Visitenkarte! Laut Studien informieren sich mittlerweile über 50% aller Kunden im Beauty Bereich im Vorfeld Online, bevor Sie einen Termin für eine Behandlung vereinbaren. <br/>
                    <br/>
                    Mit styletimer posten Sie Bilder Ihrer besten Arbeiten einfach in Ihrem Salon Profil und heben sich so gegenüber Ihren Mitbewerbern ab. Potentielle Neukunden sehen so bereits im Vorfeld, wie die Ergebnisse einer bestimmten Behandlung, oder die Arbeiten eines bestimmten Mitarbeiters aussehen und entscheiden sich so für Ihren Salon. <br/>
                    <br/>
                    Zudem haben Kunden mit styletimer die Möglichkeit, die präsentierten Behandlungen mit wenigen Klicks in Sekundenschnelle einfach selbst zu buchen. <br/>
                  </p>
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-md-6 col-lg-7">
                  <p class="para-title text-col-cyan text-left">
                    Aufbau einer Kundendatenbank
                  </p>
                  <p class="para-content">
                    styletimer erhebt und speichert alle relevanten Daten. Telefonnummern und Kontaktdaten sind immer griffbereit. <br/>
                    <br/>
                    Wann war Ihr Kunde zuletzt bei Ihnen, welche Behandlung bucht er oder sie am häufigsten? <br/>
                    <br/>
                    styletimer zeigt es Ihnen. Sie können zu jedem Kunden Notizen anlegen. Damit merken Sie sich einfacher Sonderwünsche oder Vorlieben und bauen so persönlichere Beziehungen auf. <br/>
                    Ihre Neukunden sind so garantiert bald Ihre Stammkunden!<br/>
                    <br/>
                    Durch Verschlüsselung sind alle Daten sicher – entsprechend der DSGVO.
                  </p>
                </div>
                <div class="col-md-6 col-lg-5">
                  <img class="para-image" src="<?php echo base_url('assets/uploads/staticpages/static_1618498808.png');?>">
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-md-6 col-lg-5">
                  <img class="para-image" src="<?php echo base_url('assets/uploads/staticpages/static_1613751442.png');?>">
                </div>
                <div class="col-md-6 col-lg-7">
                  <p class="para-title text-col-cyan text-left">
                    Prüfen Sie Ihren Erfolg
                  </p>
                  <p class="para-content">
                    styletimer erstellt regelmäßig Statistiken aus Ihrem Kalender. <br/>
                    Die Daten Ihres Salons werden automatisch ausgewertet und verständlich dargestellt. <br/>
                    <br/>
                    Sie können nachvollziehen, welche Services gerade am beliebtesten sind. Sie sehen, welche Mitarbeiter und Angebote gefragt sind.  <br/>
                    <br/>
                    Optimieren Sie damit Ihr Angebot und steigern Sie nachhaltig Ihren Umsatz. 
                  </p>
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-md-6 col-lg-7">
                  <p class="para-title text-col-cyan text-left">
                    Erstellen Sie Aktionen
                  </p>
                  <p class="para-content">
                    Mit styletimer können Sie Angebote und Rabatt-Aktionen bewerben. Damit gewinnen Sie Neukunden und bieten Ihren Bestandskunden attraktive Treueaktionen. <br/>
                    <br/>
                    Ihre bisherigen Specials haben nicht die gewünschte Reichweite erzielt? Mit der Newsletter-Funktion von styletimer informieren Sie alle Kunden regelmäßig über aktuelle Aktionen.<br/>
                    <br/>
                    Das Angebot limitieren Sie online oder bieten es beispielsweise nur für zwei Wochen an. So füllen Sie gezielt freie Zeitfenster im Kalender.
                  </p>
                </div>
                <div class="col-md-6 col-lg-5">
                  <img class="para-image" src="<?php echo base_url('assets/uploads/staticpages/static_1603722586.png');?>">
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <p class="para-title text-col-cyan">
                Wir übernehmen ihr Marketing 
              </p>
              <p class="para-content">
                Dass Sie viele neue Kunden gewinnen, liegt auch in unserem Interesse. styletimer führt deshalb regelmäßig Marketing-Aktionen für Ihren Salon durch. Sie erreichen damit viele Interessenten über neue Vertriebswege, ihr Kundenstamm wächst und Ihr Umsatz steigt. Wir bewerben styletimer in Ihrer Umgebung und machen potentielle Neukunden so völlig kostenlos auf Sie aufmerksam.
              </p>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap text-center">
              <p class="para-title text-col-cyan">
                styletimer – die Komplettlösung für Ihren Salon
              </p>
              <p class="para-content">
                Ihre Terminplanung war bisher zeitaufwändig und unstrukturiert? <br/>
                Sie wünschen sich mehr Zeit für Ihre Kunden, neue Vertriebswege und weniger Terminausfälle? <br/>
                <br/>
                <b>Mit styletimer verwalten Sie Ihren Salon so entspannt wie nie. </b><br/>
                <br/>
                Das System ist nutzerfreundlich und alle Termine, Kunden und Personal sind übersichtlich eingetragen. <br/>
                Schluss mit ewigem Telefonklingeln. Vergessen Sie unübersichtliche Terminbücher und Kunden, die nicht erscheinen! <br/>
                Ergänzen Sie mit styletimer schnell und günstig den modernen und professionellen Auftritt Ihres Salons. <br/>
                <br/>
                <b>Styletimer gibt es in drei Abo-Modellen – auf Ihren Salon angepasst: </b><br/>
              </p>
            </div>
            <div class="col-12 text-center">
              <img class="para-image" src="<?php echo base_url('assets/uploads/staticpages/static_1600551358.png" style="max-width: 850px;');?>">
            </div>
            <div class="col-12 text-center">
              <p class="para-content">
                <br/>
                Styletimer wird Sie überzeugen – davon sind wir überzeugt! Um alle Vorteile kennenzulernen, testen Sie styletimer einen ganzen Monat kostenlos. Erst danach entscheiden Sie sich für eine unserer Mitgliedschaften.  <br/>
                <br/>
                <b>Mindestvertragslaufzeiten gibt es bei styletimer deswegen nicht – Sie können Ihre Mitgliedschaft jederzeit monatlich kündigen.</b>
              </p>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 para-wrap">
              <div class="row">
                <div class="col-sm-12 col-md-3">
                  <img class="para-image" style="max-height: 380px;" src="<?php echo base_url('assets/uploads/staticpages/static_1599738554.jpg')?>">
                </div>
                <div class="col-sm-12 col-md-9" style="display:flex; align-items:center;">
                  <p class="para-content mt-4">
                    <span class="text-italic">"Mit styletimer habe ich mehr Zeit für meine Kunden und erspare mir das Telefonieren. Die Kunden können ja einfach selbst buchen!</span> <br/>
                    <br/>
                    <span class="text-italic">Das hat mein Tagesgeschäft wirklich revolutioniert."</span><br/>
                    <br/>
                    <span class="text-sm">Anna Weber, Hairdesign by Anna </span>
                  </p>
                </div>
              </div>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 text-center">
              <p class="para-title text-col-cyan">
                Alle Vorteile im Überblick:
              </p>
              <div>
              <img class="para-image" style="max-height: 350px;" src="<?php echo base_url('assets/uploads/staticpages/static_1611154781.png')?>">
              </div>
              <br/>
            </div>
            <hr class="para-hr"style="">
            <div class="col-12 text-center">
              <p class="para-title text-col-cyan">
                Unser Versprechen an Sie:<br/>
                <br/>
              </p>
              <p class="para-title">
                Kostenfreie Einrichtung
              </p>
              <p class="para-content text-center" style="max-width: 410px; margin-left:auto;margin-right:auto;">
                Wir richten styletimer in Ihrem Salon kostenfrei ein 
                und erklären Ihnen alle Funktionen. 
                Bei Rückfragen sind wir für Sie da.
              </p>
              <br/>
              <p class="para-title">
                Technischer Support
              </p>
              <p class="para-content text-center" style="max-width: 410px; margin-left:auto;margin-right:auto;">
                Wir helfen bei allen technischen Fragen. 
                Wir sind immer erreichbar und kommen 
                bei Problemen umgehend 
                in Ihrem Salon vorbei!
              </p>
            </div>
            <hr class="para-hr"style="">
          </div>
          
          <div class="row">
            <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 m-auto d-flex justify-content-center">
              <div class="relative align-self-center d-flex flex-row login_register_main_right_block w-100" id="lrmrb_scroll">
                <div class="relative align-self-center w-100">
                    <h2 class="font-size-30 color333 fontfamily-medium mb-5 text-center"><?php echo $this->lang->line('Register-as-Merchant'); ?></h2>
                    
                    <div class="relative login_register_form_block">
                         <form id="merchantRegist" method="post" action="<?php echo base_url('auth/registration/marchant') ?>">
                             

                             <div class="row">
                                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="first_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" 
                                               class="form-control" id="first_name" name="first_name" value="<?php echo set_value('first_name'); ?>">
                                               <span class="label"><?php echo $this->lang->line('First_Name'); ?> *</span>
                                               <!-- <label class="error_label"><i class="fas fa-exclamation-circle mrm-5"></i> Please Enter First Name</label> -->
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="last_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control"
                                                id="last_name" name="last_name" value="<?php echo set_value('last_name'); ?>">
                                               <span class="label"><?php echo $this->lang->line('Last_Name'); ?> *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                    <div class="form-group form-group-mb-50" id="dob_validate">
                                         <label class="inp">
                                           <input type="text" placeholder="Geburtsdatum *" class="form-control dobDatepicker"
                                            name="dob" style="background-color:#ffffff" readonly value="<?php echo set_value('dob'); ?>">
                                           <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>"
                                            class="v_time_claender_icon_blue" style="top:8px;right:9px;">
                                           <!-- <span class="label"></span> -->
                                         </label>                                                
                                     </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="business_name_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" 
                                               class="form-control" id="business_name"
                                                name="business_name" value="<?php echo set_value('business_name'); ?>"> 
                                               <span class="label"><?php echo $this->lang->line('Business_Name1'); ?> *</span>
                                             </label>                                                
                                       </div>
                                      
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50">
                                             <div class="btn-group multi_sigle_select inp_select"> 
                                                  <span class="label"><?php echo $this->lang->line('Business_Type'); ?> *</span>
                                                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn"></button>
                                                      <ul class="dropdown-menu mss_sl_btn_dm">
                                                         
                                                            
                                                        <li class="radiobox-image"><input type="radio" id="id_310"
                                                         name="business_type" class="mer_regfrm" value="Friseur">
                                                         <label for="id_310">Friseur</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="id_311" 
                                                          name="business_type" class="mer_regfrm" value="Barbier">
                                                          <label for="id_311">Barbier</label></li>
                                                          <li class="radiobox-image"><input type="radio" id="id_312" 
                                                          name="business_type" class="mer_regfrm" value="Nagelstudio">
                                                          <label for="id_312">Nagelstudio</label></li>

                                                           <li class="radiobox-image"><input type="radio" id="id_313" 
                                                          name="business_type" class="mer_regfrm" value="Massage Salon">
                                                          <label for="id_313">Massage Salon
                                                            </label></li>
                                                          <li class="radiobox-image"><input type="radio" id="id_314" 
                                                          name="business_type" class="mer_regfrm" value="Kosmetikstudio">
                                                          <label for="id_314">Kosmetikstudio</label></li>
                                                      </ul>
                                              </div>
                                                <label class="error_label" id="busType_err"></label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="telephone_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control onlyNumber"
                                                id="telephone" name="telephone" value="<?php echo set_value('telephone'); ?>">
                                               <span class="label"><?php echo $this->lang->line('Telephone'); ?> *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="email_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" 
                                               class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>">
                                               <span class="label"><?php echo $this->lang->line('Email'); ?> *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>                           
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="location_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" 
                                               class="form-control" id="location" 
                                               name="location" value="<?php echo set_value('location'); ?>">
                                               <span class="label"><?php echo $this->lang->line('Street'); ?> *</span>
                                             </label>
                                             <input type="hidden" name="latitude" value="" id="latitude">

                                              <input type="hidden" name="longitude" value="" id="longitude"> 
                                              <span class="error" for="location" generated="true" id="addr_err"></span>                                      

                                       </div>
                                  </div>
                             </div>

                             <div class="row">
                                  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50">
                                       <label style="margin-top: -24px;" > <?php echo $this->lang->line('Country'); ?></label>
                                             <div class="btn-group multi_sigle_select inp_select" id="country">
                                            
                                                  <!-- <span class="label"><?php echo $this->lang->line('Country'); ?> *</span>  -->
                                                  <!-- <select class="form-select" aria-label="Default select example" class="dropdown-menu mss_sl_btn_dm btn btn-default dropdown-toggle mss_sl_btn">
                                                        <option selected  value="Deutschland">Deutschland</option>
                                                        <option value="Österreich">Österreich</option>
                                                        <option value="Schweiz">Schweiz</option>
                                                        
                                                      </select> -->
                                                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn">Deutschland</button>
                                                     <ul class="dropdown-menu mss_sl_btn_dm">
                                                          <li class="radiobox-image"><input type="radio" id="id_1" name="country" 
                                                          class="country" value="Germany" checked><label for="id_1">Deutschland </label></li>
                                                          <li class="radiobox-image"><input type="radio" 
                                                          id="id_2" name="country" class="country"
                                                           value="Austria"><label for="id_2">Österreich</label></li>
                                                          <li class="radiobox-image"><input type="radio"
                                                           id="id_3" name="country" class="country" 
                                                           value="Switzerland"><label for="id_3">Schweiz </label></li>
                                                      </ul>  
                                              </div>
                                                <!-- <label class="error_label" id="country_err"></label> -->

                                       </div>
                                  </div>
                                  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="form-group form-group-mb-50" id="post_code_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" class="form-control onlyNumber" id="post_code"
                                               value="<?php echo set_value('post_code'); ?>" name="post_code" maxlength="5">
                                               <span class="label"><?php echo $this->lang->line('Postcode1'); ?> *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                  <?php $this->form_validation->set_rules('city', 'City', 'trim|required'); ?>
 
									  <div class="form-group form-group-mb-50" id="city_validate">
                                              <label class="inp">
                                               <input type="text" placeholder="&nbsp;" 
                                               class="form-control city" id="city" 
                                               name="city" value="<?php echo set_value('city'); ?>">
                                               <span class="label"><?php echo $this->lang->line('City'); ?> *</span>
                                             </label>                                            
                                       </div>
                                      
                                  </div>
                                   
                             </div>  
                              <div class="row">
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                      <div class="form-group form-group-mb-50" id="password_validate">
                                             <label class="inp">
                                               <input type="password" placeholder="&nbsp;" 
                                               class="form-control" id="password" name="password" value="<?php echo set_value('password'); ?>">
                                               <span class="label"><?php echo $this->lang->line('password'); ?> *</span>
                                             </label>                                                
                                       </div>

                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                       <div class="form-group form-group-mb-50" id="confirm_pass_validate">
                                             <label class="inp">
                                               <input type="password" placeholder="&nbsp;" 
                                               class="form-control" id="confirm_pass" 
                                               name="confirm_pass" value="<?php echo set_value('confirm_pass'); ?>">
                                               <span class="label"><?php echo $this->lang->line('Confirm_Password'); ?> *</span>
                                             </label>                                                
                                       </div>
                                  </div>
                             </div>
                             <div class="row">
								   <div id="reffrelotption" class="<?php if(!empty($_GET['r'])) echo 'col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12';  else echo 'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'; ?>">
                                       <div class="form-group form-group-mb-50">
                                             <div class="btn-group multi_sigle_select inp_select"> 
                                                  <span class="label <?php if(!empty($_GET['r'])) echo 'label_add_top'; ?>" style="width: max-content;"><?php echo $this->lang->line('How-did-you'); ?>*</span>
                                                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn" style="text-transform: none !important;"><?php if(!empty($_GET['r'])) echo 'Referral'; ?></button>
                                                      <ul class="dropdown-menu mss_sl_btn_dm">
                                                          <li class="radiobox-image"><input type="radio" id="idopthow1" name="hot_toknow" class="selecthowtooption" value="Recommended by a customer"><label for="idopthow1"><?php echo $this->lang->line('Recommended-by-a-customer'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow2" name="hot_toknow" class="selecthowtooption" value="Recommended by another salon"><label for="idopthow2"><?php echo $this->lang->line('Recommended-by-another-salon'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow3" name="hot_toknow" class="selecthowtooption" value="Magazine/ print advertising"><label for="idopthow3"><?php echo $this->lang->line('Magazine-print-advertising'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow4" name="hot_toknow" class="selecthowtooption" value="Facebook/ Instagram"><label for="idopthow4"><?php echo $this->lang->line('Facebook-Instagram'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow5" name="hot_toknow" class="selecthowtooption" value="LinkedIn"><label for="idopthow5"><?php echo $this->lang->line('LinkedIn'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow6" name="hot_toknow" class="selecthowtooption" value="Google"><label for="idopthow6"><?php echo $this->lang->line('Google'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow7" name="hot_toknow" class="selecthowtooption" value="Software comparison site"><label for="idopthow7"><?php echo $this->lang->line('Software-comparison-site'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow8" name="hot_toknow" class="selecthowtooption" value="Outdoor advertising"><label for="idopthow8"><?php echo $this->lang->line('Outdoor-advertising'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow9" name="hot_toknow" class="selecthowtooption" value="TV advertising"><label for="idopthow9"><?php echo $this->lang->line('TV-advertising'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow10" name="hot_toknow" class="selecthowtooption" value="Events"><label for="idopthow10"><?php echo $this->lang->line('Events'); ?></label></li>
                                                          <li class="radiobox-image"><input type="radio" id="idopthow12" name="hot_toknow" class="selecthowtooption" value="Referral" <?php if(!empty($_GET['r'])) echo 'checked' ?>><label for="idopthow12"><?php echo $this->lang->line('Referral'); ?></label></li>
                                                          <li class="radiobox-image">
															                            <input type="radio" id="idopthow11" name="hot_toknow" class="selecthowtooption" value="Other"><label for="idopthow11"><?php echo $this->lang->line('Other'); ?></label></li>
                                                      </ul>
                                                      
                                              </div>
                                                <label class="error_label" id="hot_toknow_err"></label>                                                
                                       </div>
                                  </div>
                                   <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 <?php if(empty($_GET['r'])) echo 'display-n'; ?>" id="referral_code">
                                       <div class="form-group form-group-mb-50" id="referral_code_validate">
                                             <label class="inp">
                                               <input type="text" placeholder="&nbsp;" value="<?php if(!empty($_GET['r'])) echo $_GET['r']; ?>" class="form-control"
                                                name="referral_code" id="referral_code_val" value="<?php echo set_value('referral_code'); ?>">
                                               <span class="label"><?php echo $this->lang->line('Referral-code'); ?></span>
                                             </label>                                                
                                       </div>
                                  </div>
								 
							  </div>
                             <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-left mb-4">
                                            <div class="checkbox mt-0 mb-2" id="terms_validate">
                                                <label class="fontsize-12 fontfamily-regular color333" style="white-space: normal;"><input type="checkbox" name="remember" value="0" id="terms" name="terms">
                                                  <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                  Ich stimme den <a class="colororange a_hover_orange popup_terms" data-type="terms" data-access="merchant" href="javascript:void(0)"> Allgemeinen  </a> <a href="javascript:void(0)" class="colororange a_hover_orange popup_terms" data-type="policy" data-access="merchant">Geschäftsbedingungen</a>
                                                   und <a class="colororange a_hover_orange popup_terms" 
                                                  data-type="conditions" data-access="user" href="javascript:void(0)">
                                                   Datenschutzbestimmungen </a> von styletimer zu.
                                                </label>

                                                <label class="error_label" id="terms_err"></label>
                                            </div>  
                                           
                                           <div class="checkbox mt-0 mb-0">
                                               <label class="fontsize-12 fontfamily-regular color333"><input type="checkbox" name="newsletter" value="1">
                                                  <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                  <?php echo $this->lang->line('Receive-newsletter-from-styletimer'); ?>
                                                </label>
                                            </div> 
                                                                                         
                                       </div>
                                  </div>
                                  <div class="col-12" style="display: flex; justify-content: center;">
                                    <div class="g-recaptcha mb-3" data-sitekey="6Lel0UoeAAAAAJm755rLYXI9_DCfeeKIQ6TqRA9Z"></div>
                                  </div>
                                  <br>
                                  <div class="col-xl-7 col-lg-7 col-md-7 col-sm-8 col-12 mx-auto">
                                  <?php  
                    echo $this->session->flashdata('err_message'); ?>
                     <h8 style="color:red; text-align: center;margin-left:93px;margin-top: 5px;margin-bottom: 56px;">
                     <?php echo $this->session->flashdata('error'); ?></h8> 
                                  </div>
                                  
                              </div>

                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                       <div class="form-group text-center mb-4">
                                            <button type="submit" style="margin-top: 7px;"
                                             id="frmsubmit" name="frmsubmit" class="btn width250"><?php echo $this->lang->line('Continue'); ?></button>                                            
                                       </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                       <div class="form-group text-center mbm-5"  style="margin-bottom: 30px;">
                                            <span class="color333 fontsize-14 fontfamily-regular">Du hast bereits einen Account? </span>
                                            <a href="#" class="fontfamily-regular colororange a_hover_orange fontsize-14 mt-0 display-ib openLoginPopup">Jetzt einloggen!</a>

                                        </div>
                                  </div>
                              </div>
                         </form>
                    </div>
                  </div>  
              </div>
            </div>
        </div>
    </div>
  </section>
 
<?php $this->load->view('frontend/common/footer'); ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  $(document).on('click','#frmsubmit',function(){    //#location,#city
    //  if($('[name="country"]:checked').length == 0){
    //   $("#country_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+country_required); 
    //    token =false;
    // }else{ $("#country_err").html(''); }
    
   if($('[name="business_type"]:checked').length == 0){
      $("#busType_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+please_enter_business_name); 
       token =false;
    }else{ $("#busType_err").html(''); }
    if($('[name="hot_toknow"]:checked').length == 0){
      //$("#hot_toknow_err").('display','block'); 
      $("#hot_toknow_err").html('<i class="fas fa-exclamation-circle mrm-5"></i>'+Please_select_about_styletimer); 
       token =false;
    }else{ $("#hot_toknow_err").html(''); }

  });
 
  $(document).on('change','.mer_regfrm,.country,.selecthowtooption',function(){
    if($('[name="country"]:checked').length > 0)
      $("#country_err").html('');
    if($('[name="business_type"]:checked').length > 0)
      $("#busType_err").html('');
     if($('[name="hot_toknow"]:checked').length > 0)
      $("#hot_toknow_err").html('');

  });

  $(document).on('blur','#post_code',function(){    //#location,,#city
     getLatlong(); 
    });
    $(document).on('change','.country',function(){
     getLatlong(); 
    });

    function getLatlong(){
       var country= $("input[name='country']:checked").val();
        var c_code='de';
         var location=$("#location").val(); 
          if(location!=""){
			address=location;
			}
        
        var zipcode=$("#post_code").val();
        if(zipcode == ''){
          return false;
        }
        var address="";
        if(zipcode!=undefined && zipcode!=""){
          address=address+" "+zipcode;
        }
        if(country!=undefined && country!=""){
          address=address+" "+country;
         if(country == 'Austria')
            c_code = 'at';
         else if(country == 'Switzerland')
            c_code = 'ch';
        }
       
        var location=$("#location").val(); 
            
    // var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&countrycodes="+c_code+"&postalcode="+address+"&street="+location+"&addressdetails=1&format=json";
     var gurl = "https://us1.locationiq.com/v1/search.php?key="+iq_api_key+"&q="+address+"&addressdetails=1&format=json&countrycodes="+c_code+"";
   
     $.get(gurl,function(data){
      console.log('JSON='+JSON.stringify(data));
      var count = data.length;
      if(data.length > 0){
            $.each(data, function () {
              if(data[0].address.town!=undefined)
                  var citty = data[0].address.town
              else if(data[0].address.city!=undefined)
                var citty = data[0].address.city
              else if(data[0].address.municipality!=undefined)
                var citty = data[0].address.municipality
             else  if(data[1].address.town!=undefined)
                  var citty = data[1].address.town
            else if(data[1].address.city!=undefined)
                var citty = data[1].address.city
              else if(data[1].address.municipality!=undefined)
                var citty = data[1].address.municipality
              else
                var citty = "";
            $("#city").val(citty);
            
            $("#latitude").val(data[0].lat);
            $("#longitude").val(data[0].lon);
            $('#addr_err').html('');
            });
      }
      else{
        $("#location_validate label.error").css('display','none');
        $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
        $("#latitude").val('');
        return false;
        }  
      }).fail(function() {
       $("#location_validate label.error").css('display','none');
        $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
      
        $("#latitude").val('');
        return false;
      });
     }




   function getLatlong_old(){
	   
	    var country= $("input[name='country']:checked").val();
        //var city= $("#city").val();
        //var street=$("#location").val();
        var zipcode=$("#post_code").val();
        if(zipcode =="")
         { return false;  } 

         var address="";
       if(zipcode!=undefined && zipcode!=""){
        address=zipcode;
        }
       /* if(street!=undefined && street!=""){
        address=address+" "+street;
        }*/
          //~ if(city!=undefined && city!=""){
        //~ address=address+" "+city;
        //~ }
      if(country!=undefined && country!=""){
        address=address+" "+country;
        }
         var gurl="https://maps.googleapis.com/maps/api/geocode/json?address="+address+"&key=<?php echo $keyGoogle; ?>";              
     $.get(gurl,function(data){
       if(data.status==="OK"){
		 $("#city").val(data.results[0].address_components[1].long_name);
         $("#latitude").val(data.results[0].geometry.location.lat);
         $("#longitude").val(data.results[0].geometry.location.lng);
         //console.log();
          $('#addr_err').html('');
         return true;
           //alert(data.status);
         }
      else{
		  //alert('d');
		   $("#addr_err").css('display','block');
        $('#addr_err').html('<i class="fas fa-exclamation-circle mrm-5"></i>Bitte gebe eine gültige Adresse ein');
        $("#latitude").val('');
        return false;
        }  
       
      });
     }  
     
     $(".regiscroll").click(function() {
        $('html,body').animate({
            scrollTop: $(".login_register_main_right_block").offset().top-65},
            'slow');
    });
</script>
