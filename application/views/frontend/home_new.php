<?php
$this->load->view('frontend/common/header');
//$keyGoogle =GOOGLEADDRESSAPIKEY;

$ip = $_SERVER['REMOTE_ADDR'];
//  echo "fddfgdfgdfg";
//  echo $ip;  die;
//  echo "sdfjhfsdfsdfsdf";
$url = 'http://www.geoplugin.net/json.gp?ip=' . $ip;
//$ipdat = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_POST, true);
$response = curl_exec($ch);

$ipdat = json_decode($response);

if (!empty($ipdat)) {
    $country = $ipdat->geoplugin_countryCode;
} else {
    $country = '';
}
// echo "<pre>"; print_r($ipdat);
if ($country == 'AT' && empty($_GET['lat']) && empty($_GET['lng'])) {
  $lat = '47.5162';
  $lng = '14.5501';
} elseif ($country == 'CH' && empty($_GET['lat']) && empty($_GET['lng'])) {
  $lat = '46.8182';
  $lng = '8.2275';
} elseif (!empty($_GET['lat']) && !empty($_GET['lng'])) {
  $lat = $_GET['lat'];
  $lng = $_GET['lng'];
} else {
  $lat = '51.1657';
  $lng = '10.4515';
}
//echo $lat."==".$lng; die;

if (
    $this->session->userdata('access') == 'marchant' ||
    $this->session->userdata('access') == 'employee'
) {
    $cls = 'pt-84';
} else {
    $cls = 'pt-120';
}
?>
<script src="https://player.vimeo.com/api/player.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet"
  href="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.5/leaflet-geocoder-locationiq.min.css">
<script src="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.5/leaflet-geocoder-locationiq.min.js"></script>


<!-- <script src="<?php echo base_url(
    'assets/frontend/js/leaflet-geocoder-locationiq.js'
); ?>"></script> -->



<style type="text/css">
.leaflet-locationiq-control {
  z-index: 1;
}

.width90 {
  width: 90px;
}
.height90 {
  height: 90px;
  object-fit: contain;
}
.sliderImage {
  width: 275px;
  height: 183px;
}

.colororange {
  color: #FE7D5D;
}

.leaflet-locationiq-results {
  margin-top: 40px !important;
  padding-top: 0px !important;
}

.color999 {
  color: #999;
}

.page-item {
  padding: 4px;
}

.calender-date-custume {
  height: 238px;

}

.pac-container:after {
  /* Disclaimer: not needed to show 'powered by Google' if also a Google Map is shown */

  background-image: none !important;
  height: 0px;
}

.calender-date-custume button.btn.mt-20 {
  position: unset;
  bottom: unset;
  left: unset;
  right: unset;
  width: 260px;
}

#map {
  width: 0;
  height: 0;
  background-color: transparent;
  display: hidden;
}

.leaflet-locationiq-results {
  padding-top: 45px;
}

.leaflet-locationiq-expanded {
  width: 100%;
  height: 40px;
}

.leaflet-bar {
  box-shadow: 0 0px 1px rgba(57, 56, 56, 0.65);
}

.leaflet-locationiq-search-icon {
  background-image: url('<?php echo base_url(
      'assets/frontend/images/search-new.svg'
  ); ?>');

}

.leaflet-locationiq-expanded .leaflet-locationiq-input {
  padding-left: 39px;
}

.leaflet-bar a,
.leaflet-bar a:hover {
  width: 40px;
}
</style>

<section class="home_section1 bggreengradient clear <?php echo $cls; ?>">
  <div class="container">
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="relative pl-45">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-12 col-xs-12">
              <div class="relative around-30 bgwhite border-radius4 boxshadow-3 mt-40 home_sec1_form_block">
                <!-- <h4 class="font-size-14 fontfamily-regular color333">Search by Name of Service  Provider or Name of Service</h4> -->
                <div class="relative d-flex" style="min-height: 290px;">

                  <input type="hidden" id="urladdress" value="">
                  <form method="get" action="<?php echo base_url(
                      'listing/search'
                  ); ?>" id="searchform" class="w-100">
                    <a href="JavaScript:Void(0);" class="home-form-tab active font-size-16" id="home-servics-tab">
                      <i class="fas fa-cut fontsize-18 display-b m-auto"></i>
                      <span class="display-ib relative"><?php echo $this->lang->line('Services'); ?></span>
                    </a>
                    <a href="JavaScript:Void(0);" class="home-form-tab font-size-16" id="home-salon-tab">
                      <i class="fas fa-home fontsize-18 display-b m-auto"></i>
                      <span class="display-ib relative"><?php echo $this->lang->line('Salon'); ?></span>
                    </a>
                    <div class="row home-servics-tab">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group relative mb-0 mt-20">
                          <input type="text" name="service_name" class="form-control inp-left-icon"
                            placeholder="Service" id="showkeyword" value="" autocomplete="off">

                          <img src="<?php echo base_url(
                              'assets/frontend/images/home_input_service_icon.svg'
                          ); ?>" class="input_left_with_icon">

                          <img id="service_sch_close" src="<?php echo base_url(
                              'assets/frontend/images/popup_crose_black_icon1.svg'
                          ); ?>" class="crose-icon-new hide">
                          <div class="boxshadow-2 bgwhite display-n" id="company_keyword">

                            <ul class="pl-0 category_ul scroll-effect">
                              <?php foreach ($filter_category as $subcat) { ?>                                
                                <li class="key_word" style="background:#00b3be9e; text-align:center;">
                                  <label style="color:black;" for="category_<?php echo $subcat->id; ?>_parent"><?php echo $subcat->category_name; ?></label>
                                </li>
                                <?php foreach ($subcat->sub_category as $cat) { ?>
                                  <li class="key_word"><input type="radio" id="category_<?php echo $cat['my_cat_id']; ?>"
                                      class="category_li" name="filtercat" data-id="<?php echo $cat['category_name']; ?>"
                                      value="<?php echo url_encode($cat['my_cat_id']); ?>">
                                    <label for="category_<?php echo $cat['my_cat_id']; ?>"><?php echo $cat['category_name']; ?></label>
                                  </li>
                                <?php } }?>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group relative mb-0 mt-20">
                          <div id="map"></div>
                          <div id="search-box"></div>
                          <input type="hidden" id="address" name="address" class="form-control inp-left-icon"
                            placeholder="Ort">
                          <!--  <img src="<?php echo base_url(
                              'assets/frontend/images/home_input_location_icon.svg'
                          ); ?>" class="input_left_with_icon" /> -->
                          <input type="hidden" name="lat" value="<?php if (
                              isset($_GET['lat'])
                          ) {
                              echo $_GET['lat'];
                          } ?>" id="lat">
                          <input type="hidden" name="lng" value="<?php if (
                              isset($_GET['lng'])
                          ) {
                              echo $_GET['lng'];
                          } ?>" id="lng">
                        </div>
                      </div>
                      <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pr-2">
                        <div class="form-group date_pecker1 relative mt-20">
                          <input class="pl-35 form-control vinu_style_date_calender dat" name="date" id="setDateFilter"
                            value="Beliebig" placeholder="Datum" autocomplete="off" style="background-color:#fff;"
                            readonly>
                          <div class="calender-date-custume relative around-15" id="datadate">
                            <p class="color242424 fontfamily-light font-size-14 mb-25">
                              <img src="<?php echo base_url(
                                  'assets/frontend/images/date_pecker_icon.svg'
                              ); ?>" class="width16 mr-10">
                              Datum
                            </p>
                            <div class="row mb-20 relative">
                              <div class="col-6">
                                <input type="button" class="calender-btn selectTTA" placeholder="Beliebig"
                                  value="Beliebig" data-val="Beliebig" id="anydate">
                              </div>

                              <div class="col-6">
                                <input type="button" class="calender-btn selectTTA" placeholder="Heute" value="Heute"
                                  data-val="<?php echo date('d.m.Y'); ?>" id="today">
                              </div>

                              <div class="col-6" style=" margin-top: 15px; position: relative;">
                                <input type="button" class="calender-btn datepicker"
                                  placeholder="Datum wählen" value="Datum wählen" style="padding: 1px 6px; opacity: 0; position: absolute; top: 0; left: 15px;">
                                <label class="calender-btn" id="clickheight" style="display: flex;justify-content: center;align-items: center;">Datum wählen</label>
                              </div>
                              <div class="col-6" style=" margin-top: 15px;">
                                <input type="button" class="calender-btn selectTTA" placeholder="Morgen" value="Morgen"
                                  data-val="<?php echo date('d.m.Y', strtotime('+1 day',strtotime(date('Y-m-d')))); ?>" id="tomorrow">
                              </div>
                            </div>
                            <button type="button" class="btn mt-20 submitdate_time"><?php echo $this->lang->line('Done'); ?></button>
                          </div>
                        </div>
                      </div>
                      <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pl-2">
                        <div class="form-group  time_pecker1 mt-20">
                          <input class="pl-35 form-control vinu_style_time tim" placeholder="Time" name="time"
                            id="selctTimeFilter" value="Uhrzeit" autocomplete="off" style="background-color:#fff;"
                            readonly>
                          <div class="time-custume relative around-15" id="timedata">
                            <p class="color242424 fontfamily-light font-size-14 mb-25">
                              <img class="width16 mr-10" src="<?php echo base_url(
                                  'assets/frontend/'
                              ); ?>images/time_pecker_icon.svg">
                              Uhrzeit
                            </p>
                            <div class="row mb-20">
                              <div class="col-6">
                                <input type="button" class="calender-btn" id="anytime" placeholder="Beliebig"
                                  value="Beliebig">
                              </div>
                              <div class="col-6">
                                <input type="button" class="calender-btn" id="clicktimeheight"
                                  placeholder="Uhrzeit wählen" value="Uhrzeit wählen">
                              </div>
                              <div class="col-6">
                                <div class="time_picker_droupdown w-100 ml-0">
                                  <label>von</label>
                                  <div class="btn-group multi_sigle_select inp_select">

                                    <button data-toggle="dropdown"
                                      class="btn btn-default dropdown-toggle mss_sl_btn">08:00</button>

                                    <ul class="dropdown-menu mss_sl_btn_dm custom_scroll height300"
                                      style="max-height: none; overflow-x: auto; height: 320px !important; position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;"
                                      x-placement="bottom-start">
                                      <?php
                                      if (!empty($minmaxtime->mintime)) {
                                          $start = $minmaxtime->mintime;
                                      } else {
                                          $start = '00:00';
                                      }

                                      if (!empty($minmaxtime->mintime)) {
                                          $end = $minmaxtime->maxtime;
                                      } else {
                                          $end = '23:00';
                                      }

                                      $tStart = strtotime($start);
                                      $tEnd = strtotime($end);
                                      $tNow = $tStart;
                                      while ($tNow <= $tEnd) { ?>
                                      <li class="radiobox-image">
                                        <input
                                          class="s <?php if ( date('H:i', $tNow) == '08:00') { echo 'schecked';} ?>" 
                                          type="radio" 
                                          id="from_<?php echo date('H:i', $tNow); ?>"
                                          name="starttime" 
                                          value="<?php echo date('H:i', $tNow); ?>">
                                        <label for="from_<?php echo date('H:i', $tNow); ?>"><?php echo date('H:i', $tNow); ?></label>
                                      </li>
                                      <?php $tNow = strtotime('+60 minutes', $tNow); } ?>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="time_picker_droupdown w-100 ml-0">
                                  <label>bis</label>
                                  <div class="btn-group multi_sigle_select inp_select">
                                    <button data-toggle="dropdown"
                                      class="btn btn-default dropdown-toggle mss_sl_btn">20:00</button>
                                    <ul class="dropdown-menu mss_sl_btn_dm custom_scroll height300" style="max-height: none; overflow-x: auto; height: 320px !important; position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-start">
                                      <?php
                                      if (!empty($minmaxtime->mintime)) {
                                          $start = $minmaxtime->mintime;
                                      } else {
                                          $start = '00:00';
                                      }

                                      if (!empty($minmaxtime->mintime)) {
                                          $end = $minmaxtime->maxtime;
                                      } else {
                                          $end = '23:00';
                                      }

                                      $tStart = strtotime($start);
                                      $tEnd = strtotime($end);
                                      $tNow = $tStart;
                                      while ($tNow <= $tEnd) { ?>
                                      <li class="radiobox-image">
                                        <input class="s <?php if (
                                            date('H:i', $tNow) == '20:00'
                                        ) {
                                            echo 'echecked';
                                        } ?>" type="radio" id="to_<?php echo date('H:i', $tNow); ?>" name="endtime" value="<?php echo date('H:i', $tNow); ?>">
                                        <label for="to_<?php echo date('H:i', $tNow); ?>"><?php echo date('H:i', $tNow); ?></label>
                                      </li>
                                      <?php 
                                        $tNow = strtotime('+60 minutes', $tNow);
                                      } ?>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12"><span class="error timeconflict_err"></span></div>
                            </div>
                            <button class="btn mt-20 closeTime" type="button"><?php echo $this->lang->line(
                                'Done'
                            ); ?></button>
                          </div>
                        </div>
                      </div>
                      <div
                        class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0 m-auto form-group relative mb-0 mt-20 fine-btn">
                        <button type="submit" class="btn" style="text-transform: none;">
                          <?php echo $this->lang->line('Find-Now'); ?></button>
                      </div>
                    </div>
                    <div class="row home-salon-tab">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group relative mb-0 mt-20">
                          <input type="text" class="form-control inp-left-icon" placeholder="Salonname" id="getsalone" autocomplete="off">
                          <img src="<?php echo base_url('assets/frontend/images/home_input_service_icon.svg'); ?>" class="input_left_with_icon">
                          <img id="salon_sch_close" src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon1.svg'); ?>" class="crose-icon-new hide">
                          <div class="boxshadow-2 bgwhite" id="salon_list">
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0 m-auto form-group relative mb-0 mt-20 fine-btn ">
                        <a href="#" id="findurl" class="btn">
                          <?php echo $this->lang->line('Find-Now'); ?>
                        </a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-12 col-xs-12">
              <div class="relative pt-70 pb-70 pl-45">
                <h2 class="font-size-30 colorwhite fontfamily-semibold bottom_border_white mb-30 text-lines-overflow-1" style="">
                  Friseur & Beauty<br/>einfach online buchen
                </h2>
                <div id="appLinks">
                  <h5 class="font-size-16 colorwhite fontfamily-semibold mb-15">
                    Dein Style - in einer App!
                  </h5>
                  <div class="relative">
                    <a href="<?php echo ANDROID_LINK; ?>" target="_blank" class="">
                      <picture class="width150">
                        <source srcset="
                          <?php echo base_url(
                              'assets/frontend/images/google-play-and-app-store-badges.webp'
                          ); ?>" type="image/webp" class="width150">
                        <source srcset="<?php echo base_url(
                            'assets/frontend/images/google-play-and-app-store-badges.png'
                        ); ?>" type="image/png" class="width150">
                        <img src="<?php echo base_url(
                            'assets/frontend/images/google-play-and-app-store-badges.png'
                        ); ?>" class="width150">
                      </picture>
                    </a>
                    <a href="<?php echo IOS_LINK; ?>" target="_blank" class="">
                      <picture class="width150 mlm-5">
                        <source srcset="
                          <?php echo base_url(
                              'assets/frontend/images/google-play-and-app-store-badges1.webp'
                          ); ?>" type="image/webp" class="width150">
                        <source srcset="<?php echo base_url(
                            'assets/frontend/images/google-play-and-app-store-badges1.png'
                        ); ?>" type="image/png" class="width150">
                        <img src="<?php echo base_url(
                            'assets/frontend/images/google-play-and-app-store-badges1.png'
                        ); ?>" class="width150">
                      </picture>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="home_banner_top_girl_img_block d-md-none d-lg-none d-xl-block">
            <div><iframe id="vimeo_player" src="https://player.vimeo.com/video/717794186?h=5fee2350f6&autoplay=1&loop=0&title=0&byline=0&portrait=0&muted=0&controls=0" style="position:absolute;top:0;left:-40px;width:94%;height:94%;" frameborder="0" controls="0" background="1"  allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>
          </div>

          <div class="row" id="appLinksMobile">
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-12 col-xs-12">
              <div class="relative pt-70 pb-70 pl-45">
                <div >
                  <h5 class="font-size-16 colorwhite fontfamily-semibold text-uppercase mb-15">
                    Dein Style in einer App</h5>
                  <div class="relative">
                    <a href="<?php echo ANDROID_LINK; ?>" target="_blank" class="">
                      <picture class="width150">
                        <source srcset="
                          <?php echo base_url(
                              'assets/frontend/images/google-play-and-app-store-badges.webp'
                          ); ?>" type="image/webp" class="width150">
                        <source srcset="<?php echo base_url(
                            'assets/frontend/images/google-play-and-app-store-badges.png'
                        ); ?>" type="image/png" class="width150">
                        <img src="<?php echo base_url(
                            'assets/frontend/images/google-play-and-app-store-badges.png'
                        ); ?>" class="width150">
                      </picture>
                    </a>
                    <a href="<?php echo IOS_LINK; ?>" target="_blank" class="">
                      <picture class="width150 mlm-5">
                        <source srcset="
                          <?php echo base_url(
                              'assets/frontend/images/google-play-and-app-store-badges1.webp'
                          ); ?>" type="image/webp" class="width150">
                        <source srcset="<?php echo base_url(
                            'assets/frontend/images/google-play-and-app-store-badges1.png'
                        ); ?>" type="image/png" class="width150">
                        <img src="<?php echo base_url(
                            'assets/frontend/images/google-play-and-app-store-badges1.png'
                        ); ?>" class="width150">
                      </picture>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="clear pt-60 pb-20">
  <div class="container">
    <div class="row pl-45 pr-45">
      <div class="col-md-4 col-xs-12 text-center pt-4 pb-4">
        <img class="width90 height90" src="<?php echo base_url('assets/frontend/images/home-discount.png'); ?>">
        <h2 class="fontfamily-semibold color333 font-size-20 text-center pt-4 pt-2">Exklusive Rabatte</h2>
        <h6>Spare in Nebenzeiten oder Last-Minute</h6>
      </div>
      <div class="col-md-4 col-xs-12 text-center pt-4 pb-4">
        <img class="width90 height90" src="<?php echo base_url('assets/frontend/images/home-realtime.png'); ?>">
        <h2 class="fontfamily-semibold color333 font-size-20 text-center pt-4 pt-2">Buche, wann du willst</h2>
        <h6>Vereinbare deine Termine<br/> rund um die Uhr</h6>
      </div>
      <div class="col-md-4 col-xs-12 text-center pt-4 pb-4">
        <img class="width90 height90" src="<?php echo base_url('assets/frontend/images/home-location.png'); ?>">
        <h2 class="fontfamily-semibold color333 font-size-20 text-center pt-4 pt-2">Finde die besten Salons</h2>
        <h6>Alle Top-Salons in deiner Nähe</h6>
      </div>
    </div>
  </div>
</section>
<?php
if (!empty($recent_view)) { ?>
<section class="home_section2 clear pt-40 pb-10">
  <div class="container">
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="relative">
          <h2 class="fontfamily-semibold color333 font-size-20 text-center bottom_border mb-30">
            <?php echo $this->lang->line('Recently-Viewed-Venues'); ?></h2>
          <!--   <div class="relative mb-50">
                                 <p class="font-size-16 fontfamily-regular color333 text-center mb-0 text-lines-overflow-1" style="-webkit-line-clamp: 2;">Contrary to popular belief, Lorem Ipsum is not simply random text. It has <br/> roots in a piece of classical Latin literature from 45 BC, making it over 2000 years.</p>
                            </div> -->
          <div class="relative">
            <div class="row">
              <?php
              $count = count($recent_view);
              $i = 0;
              foreach ($recent_view as $row) { ?>
              <a href="<?php echo base_url('user/service_provider/') .
                  url_encode($row->id) .
                  '/?chk=allserve'; ?>" class="color333 a_hover_333 font-size-14 fontfamily-regular">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12 <?php
                if ($count == 2 && $i == 0) {
                    echo 'offset-lg-2';
                }
                if ($count == 1 && $i == 0) {
                    echo 'm-auto';
                }
                ?>">
                  <div class="relative boxshadow-3 mt-30">
                    <div id="homesmall-slider<?php echo $row->id; ?>" class="carousel slide homeSliderRecntView"
                      data-ride="carousel">
                      <!-- The slideshow -->
                      <div class="carousel-inner border-radius4">
                        <?php
                        $alideImg = [];
                        if (!empty($row->image)) {

                            $path =
                                'assets/uploads/banners/' .
                                $row->id .
                                '/prof_' .
                                $row->image;
                            if (file_exists($path)) {
                                $imgw = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image,
                                    'webp'
                                );
                                $imgp = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image,
                                    'png'
                                );
                            } else {
                                $imgw = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image,
                                    'webp'
                                );
                                $imgp = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image,
                                    'png'
                                );
                            }

                            $alideImg[] = $row->image;
                            ?>
                        <div class="carousel-item active">
                          <picture class="upl-gallary-image">
                            <source srcset="<?php echo $imgw; ?>" type="image/webp" class="sliderImage">

                            <source srcset="<?php echo $imgw; ?>" type="image/webp" class="sliderImage">

                            <img src="<?php echo $imgp; ?>" class="sliderImage">

                          </picture>
                        </div>
                        <?php
                        }
                        if (!empty($row->image1)) {

                            $path1 =
                                'assets/uploads/banners/' .
                                $row->id .
                                '/prof_' .
                                $row->image1;
                            if (file_exists($path1)) {
                                $imgw1 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image1,
                                    'webp'
                                );
                                $imgp1 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image1,
                                    'png'
                                );
                            } else {
                                $imgw1 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image1,
                                    'webp'
                                );
                                $imgp1 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image1,
                                    'png'
                                );
                            }

                            $alideImg[] = $row->image1;
                            ?>

                        <div class="carousel-item <?php if (
                            empty($row->image)
                        ) {
                            echo 'active';
                        } ?>">
                          <picture class="upl-gallary-image">
                            <source srcset="<?php echo $imgw1; ?>" type="image/webp" class="sliderImage">

                            <source srcset="<?php echo $imgw1; ?>" type="image/webp" class="sliderImage">

                            <img src="<?php echo $imgp1; ?>" class="sliderImage">

                          </picture>
                        </div>
                        <?php
                        }
                        if (!empty($row->image2)) {

                            $path2 =
                                'assets/uploads/banners/' .
                                $row->id .
                                '/prof_' .
                                $row->image2;
                            if (file_exists($path2)) {
                                $imgw2 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image2,
                                    'webp'
                                );
                                $imgp2 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image2,
                                    'png'
                                );
                            } else {
                                $imgw2 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image2,
                                    'webp'
                                );
                                $imgp2 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image2,
                                    'png'
                                );
                            }
                            $alideImg[] = $row->image2;
                            ?>
                        <div class="carousel-item <?php if (
                            empty($row->image) &&
                            empty($row->image1)
                        ) {
                            echo 'active';
                        } ?>">
                          <picture class="upl-gallary-image">
                            <source srcset="<?php echo $imgw2; ?>" type="image/webp" class="sliderImage">

                            <source srcset="<?php echo $imgw2; ?>" type="image/webp" class="sliderImage">

                            <img src="<?php echo $imgp2; ?>" class="sliderImage">

                          </picture>
                        </div>
                        <?php
                        }
                        if (!empty($row->image3)) {

                            $path3 =
                                'assets/uploads/banners/' .
                                $row->id .
                                '/prof_' .
                                $row->image3;
                            if (file_exists($path3)) {
                                $imgw3 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image3,
                                    'webp'
                                );
                                $imgp3 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image3,
                                    'png'
                                );
                            } else {
                                $imgw3 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image3,
                                    'webp'
                                );
                                $imgp3 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image3,
                                    'png'
                                );
                            }
                            $alideImg[] = $row->image3;
                            ?>
                        <div class="carousel-item <?php if (
                            empty($row->image) &&
                            empty($row->image1) &&
                            empty($row->image2)
                        ) {
                            echo 'active';
                        } ?>">
                          <picture class="upl-gallary-image">
                            <source srcset="<?php echo $imgw3; ?>" type="image/webp" class="sliderImage">

                            <source srcset="<?php echo $imgw3; ?>" type="image/webp" class="sliderImage">

                            <img src="<?php echo $imgp3; ?>" class="sliderImage">

                          </picture>
                        </div>
                        <?php
                        }
                        if (!empty($row->image4)) {

                            $path4 =
                                'assets/uploads/banners/' .
                                $row->id .
                                '/prof_' .
                                $row->image4;
                            if (file_exists($path4)) {
                                $imgw4 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image4,
                                    'webp'
                                );
                                $imgp4 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    'prof_' . $row->image4,
                                    'png'
                                );
                            } else {
                                $imgw4 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image4,
                                    'webp'
                                );
                                $imgp4 = getimge_url(
                                    'assets/uploads/banners/' . $row->id . '/',
                                    $row->image4,
                                    'png'
                                );
                            }
                            $alideImg[] = $row->image4;
                            ?>
                        <div class="carousel-item <?php if (
                            empty($row->image) &&
                            empty($row->image1) &&
                            empty($row->image2) &&
                            empty($row->image3)
                        ) {
                            echo 'active';
                        } ?>">
                          <picture class="upl-gallary-image">
                            <source srcset="<?php echo $imgw4; ?>" type="image/webp" class="sliderImage">

                            <source srcset="<?php echo $imgw4; ?>" type="image/webp" class="sliderImage">

                            <img src="<?php echo $imgp4; ?>" class="sliderImage">

                          </picture>
                        </div>
                        <?php
                        }
                        if (
                            empty($row->image) &&
                            empty($row->image1) &&
                            empty($row->image2) &&
                            empty($row->image3) &&
                            empty($row->image3)
                        ) { ?>
                        <div class="carousel-item active">
                          <img src="<?php echo base_url(
                              'assets/frontend/'
                          ); ?>images/noimage.png" class="sliderImage" style="">
                        </div>
                        <?php }
                        ?>
                      </div>

                      <!-- Left and right controls -->
                      <a class="carousel-control-prev" href="#homesmall-slider<?php echo $row->id; ?>"
                        data-slide="prev">
                        <img src="<?php echo base_url(
                            'assets/frontend/'
                        ); ?>images/prev-arrow-white.svg">
                      </a>
                      <a class="carousel-control-next" href="#homesmall-slider<?php echo $row->id; ?>"
                        data-slide="next">
                        <img src="<?php echo base_url(
                            'assets/frontend/'
                        ); ?>images/next-arrow-white.svg">
                      </a>

                    </div>
                    <!--
                                                 <div class="relative home_sec2_view_venues_img_block">
                                                   <img src="<?php echo $img; ?>" class="home_sec2_view_venues_img border-t-r4" />
                                                 </div>
-->
                    <div class="relative">
                      <div class="relative around-15 height160v">
                        <a href="<?php echo base_url('user/service_provider/') .
                            url_encode($row->id) .
                            '/?chk=allserve'; ?>">
                          <h3 class="fontfamily-semibold font-size-18 color333 overflow_elips" style="width:100%;">
                            <?php echo $row->business_name; ?> </h3>
                          <h6 class="fontfamily-regular font-size-14 color999 text-lines-overflow-1 overflow_elips"
                            style="width:100%;-webkit-line-clamp: 1;"><?php echo $row->address .
                                ', ' .
                                $row->city; ?>
                          </h6>
                          <p class="fontfamily-regular font-size-14 color666 mb-15 text-lines-overflow-1"
                            style="-webkit-line-clamp: 2;">
                            <?php
                            $desc = strip_tags($row->about_salon);
                            if (strlen($desc) > 30) {
                                echo substr($desc, 0, 90) . '...';
                            } else {
                                echo $desc;
                            }
                            ?>
                          </p>
                        </a>
                        <a href="<?php echo base_url('user/service_provider/') .
                            url_encode($row->id) .
                            '/?chk=allserve'; ?>"
                          class="color333 a_hover_333 font-size-14 fontfamily-regular text-underline display-b"><?php echo $this->lang->line(
                              'show-more'
                          ); ?></a>
                      </div>
                      <div class="relative around-15 bgpinkorangegradient border-b-l4">
                        <div class="row row_adj5">
                          <div class="col-xl-8 col-lg-7 col-md-8 col-sm-7 col-7 col_adj5">
                            <div class="relative">
                              <?php
                              $echorate = number_format($row->rating, 1);
                              $rate = round($row->rating);
                              echo '<span class="font-size-16">';

                              for ($i = 1; $i < 6; $i++) {
                                  if (
                                      $i <= $rate
                                  ) { ?><i class="fas fa-star mrm-5 colorwhite"></i>
                              <?php } else { ?>
                              <i class="fas fa-star mrm-5 colorwhite40"></i>
                              <?php }
                              }
                              ?>
                              </span>

                              <small
                                class="fontfamily-regular font-size-14 colorwhite mlm-5">(<?php echo $echorate; ?>)</small>
                            </div>
                          </div>
                          <div class="col-xl-4 col-lg-5 col-md-4 col-sm-5 col-5 col_adj5">
                            <div class="relative">
                              <a href="<?php echo base_url(
                                  'user/service_provider/'
                              ) .
                                  url_encode($row->id) .
                                  '/?chk=allserve'; ?>"
                                class="fontfamily-regular colorwhite a_hover_white font-size-14 text-uppercase"><?php echo $this->lang->line(
                                    'Book_Now'
                                ); ?></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </a>

              <?php $i++;}
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php }
if (!empty($offer_listing)) { ?>
<section class="home_section3 clear pt-40 pb-80">
  <div class="container">
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="relative">
          <h2 class="fontfamily-semibold color333 font-size-20 text-center bottom_border mb-30">
            <?php echo $this->lang->line('Offer-Listing'); ?></h2>
          <!--  <div class="relative mb-50">
                                 <p class="font-size-16 fontfamily-regular color333 text-center mb-0 text-lines-overflow-1" style="-webkit-line-clamp: 2;">Contrary to popular belief, Lorem Ipsum is not simply random text. It has <br/> roots in a piece of classical Latin literature from 45 BC, making it over 2000 years.</p>
                            </div> -->
          <div class="relative">
            <section id="regular" class=" slider" style="padding: 0 35px;margin: 0 auto;text-align: center;">
              <?php foreach ($offer_listing as $list) {
                  if ($list->image != '') {
                      $img_path =
                          'assets/uploads/category/' .
                          $list->id .
                          '/crop_' .
                          $list->image;
                      if (file_exists($img_path)) {
                          $img_path = getimge_url(
                              'assets/uploads/category/' . $list->id . '/',
                              'crop_' . $list->image,
                              'webp'
                          );
                          $img_pathp = getimge_url(
                              'assets/uploads/category/' . $list->id . '/',
                              'crop_' . $list->image,
                              'png'
                          );
                      } else {
                          $img_path = getimge_url(
                              'assets/uploads/category/' . $list->id . '/',
                              $list->image,
                              'webp'
                          );
                          $img_pathp = getimge_url(
                              'assets/uploads/category/' . $list->id . '/',
                              $list->image,
                              'png'
                          );
                      }
                  } else {
                      $img_path = base_url(
                          'assets/frontend/images/noimage.webp'
                      );
                      $img_pathp = base_url(
                          'assets/frontend/images/noimage.png'
                      );
                  } ?>
              <div class="row">
                <a href="<?php echo base_url('listing/search?category=') . url_encode($list->id); ?>">
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="relative">
                      <div class="relative h_sec3_offer_listing_img_block">
                        <picture class="h_sec3_offer_listing_img border-t-r4">
                          <source srcset="<?php echo $img_path; ?>" type="image/webp"
                            class="h_sec3_offer_listing_img border-t-r4">
                          <source srcset="<?php echo $img_path; ?>" type="image/webp"
                            class="h_sec3_offer_listing_img border-t-r4">
                          <img src="<?php echo $img_pathp; ?>" class="h_sec3_offer_listing_img border-t-r4">
                        </picture>
                      </div>
                      <div class="relative around-15 border-w border-b-l4 text-left height160v"
                        style="border-top: 0px;">
                        <h3 class="font-size-18 fontfamily-semibold color333 mb-15 text-lines-overflow-1"
                          style="-webkit-line-clamp: 1;">Bis zu <?php echo floatval(
                              $list->percent
                          ); ?>% Rabatt</h3>
                        <p class="font-size-14 fontfamily-regular color666 text-lines-overflow-1"
                          style="-webkit-line-clamp: 2;">
                          <?php if (strlen($list->offer_text) > 30) {
                              echo substr($list->offer_text, 0, 65) . '...';
                          } else {
                              echo $list->offer_text;
                          } ?>
                        </p>
                        <div class="relative text-left">
                          <a href="<?php echo base_url('listing/search?category=') .
                              url_encode(
                                  $list->id
                              ); ?>" class="btn widthfit"><?php echo $this->lang->line(
    'Discover-Now'
); ?></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              <?php
              } ?>



            </section>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php }
?>

<input type="hidden" id="pop_up_open" value="<?php echo isset($_GET['pg']) &&
empty($this->session->userdata('st_userid'))
    ? 'login'
    : ''; ?>">
<!--trigger data-toggle="modal" data-target="#r-success" -->
<div id="congratulations_popup" class="modal fade pr-0" id="r-success">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url(
              'assets/frontend/images/popup_crose_black_icon.webp'
          ); ?>" type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url(
              'assets/frontend/images/popup_crose_black_icon.png'
          ); ?>" type="image/png" class="popup-crose-black-icon">
          <img src="<?php echo base_url(
              'assets/frontend/images/popup_crose_black_icon.png'
          ); ?>" class="popup-crose-black-icon">
        </picture>
      </a>
      <div class="modal-body pt-30 mb-20 pl-25 pr-25 relative">
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="relative text-center">
              <picture class="mb-35">
                <source srcset="<?php echo base_url(
                    'assets/frontend/images/icon-new-popup.webp'
                ); ?>'); ?>" type="image/webp" class="popup-crose-black-icon">
                <source srcset="<?php echo base_url(
                    'assets/frontend/images/icon-new-popup.png'
                ); ?>" type="image/png" class="popup-crose-black-icon">
                <img src="<?php echo base_url(
                    'assets/frontend/images/icon-new-popup.png'
                ); ?>" class="popup-crose-black-icon">
              </picture>
              <p class="font-size-18 color333 fontfamily-medium mb-4 " style="line-height: 26px;">Congratulations
                your account has been successfully activated you now may log in with your credentials or something.
              </p>countrycodes: 'DE,AT,CH',
              <a href="#" class="btn btn-large widthfit openLoginPopup">Login</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--trigger data-toggle="modal" data-target="#employee-select-time" -->

<div id="app_popup" class="modal fade pr-0" id="download-ios-gplay">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <a href="#" class="crose-btn" data-dismiss="modal">
        <picture class="popup-crose-black-icon">
          <source srcset="<?php echo base_url(
              'assets/frontend/images/popup_crose_black_icon.webp'
          ); ?>" type="image/webp" class="popup-crose-black-icon">
          <source srcset="<?php echo base_url(
              'assets/frontend/images/popup_crose_black_icon.png'
          ); ?>" type="image/png" class="popup-crose-black-icon">
          <img src="<?php echo base_url(
              'assets/frontend/images/popup_crose_black_icon.png'
          ); ?>" class="popup-crose-black-icon">
        </picture>
      </a>
      <div class="modal-body pt-30 mb-20 pl-25 pr-25 relative">
        <div class="row">
          <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <div class="relative">
              <picture class="height370">


                <img src="<?php echo base_url(
                    'assets/frontend/images/dou-mobile-img.png'
                ); ?>" class="height370">
              </picture>
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <div class="d-flex h-100 justify-content-center align-items-center">
              <div class="relative">
                <picture class="">
                  <source srcset="<?php echo base_url(
                      'assets/frontend/images/header_top_logo_icon.webp'
                  ); ?>" type="image/webp" class="">
                  <source srcset="<?php echo base_url(
                      'assets/frontend/images/header_top_logo_icon.svg'
                  ); ?>" type="image/svg" class="">
                  <img src="<?php echo base_url(
                      'assets/frontend/images/header_top_logo_icon.svg'
                  ); ?>" class="">
                </picture>
                <h3 class="font-size-30 fontfamily-semibold color333 mb-3 mt-3">Jetzt App downloaden!</h3>
                <p class="font-size-14 color333 fontfamily-regular mb-4">Hol dir die styletimer App für Android und
                  iOS und verwalte deine Buchungen noch besser! Dein Style in einer App! </p>
                <div class="relative">
                  <a href="<?php echo ANDROID_LINK; ?>" target="_blank" class=""><img src="<?php echo base_url(
                      'assets/frontend/images/google-play-and-app-store-badges.webp'
                  ); ?>" class="width115"></a>
                  <a href="https://apps.apple.com/de/app/styletimer/id1619747854" target="_blank" class=""><img src="<?php echo base_url(
                      'assets/frontend/images/google-play-and-app-store-badges1.webp'
                  ); ?>" class="mlm-5 width115"></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- Download Our App -->




<?php $this->load->view('frontend/common/footer'); ?>
<script type="text/javascript">
//  $(document).on('keyup',function(){
// 			   //var search = $('#search-box').val();	leaflet-locationiq-list ,  leaflet-locationiq-result		 
// 			   //console.log('SEARCH='+search);
//          setTimeout(function() {
//          $('.leaflet-locationiq-list .leaflet-locationiq-result').each(function(index,value){
//           //console.log('HTML='+$('.leaflet-locationiq-list .leaflet-locationiq-result .address').html());
//                //console.log('Address='+$('.address').html());
//                //console.log('THIS'+JSON.parse($(this)));
//                //console.log('TH'+$(this.address.html()));
//                console.log(index + ':' + $(value).text());
//                var stripped = $(value).text().split(",");
//                console.log('NEW= '+stripped[0]+' ,'+stripped[1]);
//                $(value).text(stripped[0]+' ,'+stripped[1])
//                //$('.address').html(stripped[0]+' ,'+stripped[1]);
//              //$(this).attr('rel'); // This is your rel value
//             // console.log('.leaflet-locationiq-result').html();

//             // var html = $('.leaflet-locationiq-list .leaflet-locationiq-result .address').html() 
//             //  var html = data = data.filter(function( element ) {
//             //       return element !== undefined;
//             //  });
//             // var stripped = $('.leaflet-locationiq-list .leaflet-locationiq-result .address').html().split(",");
//           //   if (!$('.leaflet-locationiq-list .leaflet-locationiq-result .address').html().indexOf('undefined') > -1) {
//           //     console.log('HTML='+$('.leaflet-locationiq-list .leaflet-locationiq-result .address').html());  
//           //   var stripped = $('.address').html().split(",");
//           //   console.log('Com='+stripped);
//           //   console.log('ST-0= '+stripped[0]);
//           //   console.log('ST-1= '+stripped[1]);
//           //   // var data1 = stripped[1]
//           //   // data = data1.filter(function( element ) {
//           //   //     return element !== undefined;
//           //   //  });
//           //    // undefined
//           //    if (!(stripped[1].indexOf('undefined') > -1)) {
//           //         $('.address').html(stripped[0]+stripped[1]);
//           //    }
//           // }

//          });
//         }, 500);

// 			});

var lat1 = $("#lat").val();
var lng1 = $("#lng").val();
if (lng1 == "" && lat1 == "") {
  lat1 = "<?php echo $lat; ?>";
  lng1 = "<?php echo $lng; ?>";
}

var RESULTS_HEIGHT_MARGIN = 20; // in pixels

// Initialize an empty map without layers (invisible map)
var map = L.map('map', {
  center: [lat1, lng1], // Map loads with this location as center
  zoom: 12,
  scrollWheelZoom: true,
  zoomControl: false,
  attributionControl: false,
});

//Geocoder options
var geocoderControlOptions = {
  params: {
    countrycodes: 'DE'
  },
  textStrings: {
    NO_RESULTS: 'Keine Ergebnisse gefunden',
    ERROR_403: 'Keine Ergebnisse gefunden',
    ERROR_404: 'Keine Ergebnisse gefunden',
    ERROR_408: 'Keine Ergebnisse gefunden',
    ERROR_429: 'Keine Ergebnisse gefunden',
    ERROR_500: 'Keine Ergebnisse gefunden',
    ERROR_502: 'Keine Ergebnisse gefunden',
    ERROR_DEFAULT: 'Keine Ergebnisse gefunden'
  },
  bounds: false,
  markers: false,
  limit: 10, //To not add markers when we geocoder
  attribution: null, //No need of attribution since we are not using maps
  expanded: true, //The geocoder search box will be initialized in expanded mode
  panToPoint: false, //Since no maps, no need to pan the map to the geocoded-selected location
  placeholder: 'Ort',
  

}

//Initialize the geocoder
var geocoderControl = new L.control.geocoder(iq_api_key, geocoderControlOptions).addTo(map).on('select', function(e) {

  displayLatLon(e.feature.feature.display_name, e.latlng.lat, e.latlng.lng, e.feature.name, e.feature.name);
}).on('reset', function(e) {
  displayLatLon('', '', '', '');

});


geocoderControl.showResults = function (features, input) {
    // Exit function if there are no features
    if (features.length === 0) {
      L.showMessage(L.options.textStrings['NO_RESULTS']);
      return;
    }


    var resultsContainer = geocoderControl._results;

    // Reset and display results container
    resultsContainer.innerHTML = '';
    resultsContainer.style.display = 'block';
    // manage result box height
    resultsContainer.style.maxHeight = (geocoderControl._map.getSize().y - resultsContainer.offsetTop - geocoderControl._container.offsetTop - RESULTS_HEIGHT_MARGIN) + 'px';

    var list = L.DomUtil.create('ul', 'leaflet-locationiq-list', resultsContainer);

    for (var i = 0, j = features.length; i < j; i++) {
      var feature = features[i];
      var resultItem = L.DomUtil.create('li', 'leaflet-locationiq-result', list);
      resultItem.feature = feature;
      resultItem.coords = {lon: feature.lon, lat: feature.lat};
      if(typeof feature.display_place !== 'undefined') {
        resultItem.name = feature.display_place;
      } else {
        resultItem.name = " ";
      }
      // if(typeof feature.address.county !== 'undefined') {
      //   resultItem.address = feature.address.county;
      // } else {
      //   resultItem.address = " ";
      // }
      
      if(typeof feature.address.state !== 'undefined') {
        resultItem.address = feature.address.state;
      } else {
        resultItem.address = " ";
      }

      resultItem.innerHTML += 
        "<div class='name' data-name='"+resultItem.name+"'>" + geocoderControl.highlight(resultItem.name, input) + "</div>"
        + "<div class='address'>" + geocoderControl.highlight(resultItem.address, input) + "</div>";
    }
}

geocoderControl.setSelectedResult = function (selected, originalEvent) {
    var latlng = L.latLng(selected.coords.lat, selected.coords.lon);

    this._input.value = $(selected).find('div').data('name');
    if (selected.boundingbox && !options.overrideBbox) {
      this.removeMarkers();
      this.fitBoundingBox(selected.boundingbox);       
    } else {
      this.removeMarkers();
      this.showMarker(selected.innerHTML, latlng);
    }
    this.fire('select', {
      originalEvent: originalEvent,
      latlng: latlng,
      feature: selected
    });
    this.blur();

}

//Get the "search-box" div
var searchBoxControl = document.getElementById("search-box");
//Get the geocoder container from the leaflet map
var geocoderContainer = geocoderControl.getContainer();
console.log("geocoder Container", geocoderContainer);
//Append the geocoder container to the "search-box" div
console.log('Append Called');
searchBoxControl.appendChild(geocoderContainer);

//Displays the geocoding response in the "result" div
function displayLatLon(display_name, lat, lng, name, address) {
  //   const add = display_name;
  // console.log("display_name ==============", display_name);
  // console.log("lat ==============", lat);
  // console.log("lng ==============", lng);
  // console.log("name ==============", name);
  // console.log("address ==============", address.county);
  // console.log("address ==============", address.region);
  // console.log("address ==============", address.state);


  //console.log("country ==============",country);

  //   const myArr = add.split(",");
  //  if(myArr){
  //   var newAddress = myArr[0]  + " ," +   myArr[1] + " ," + myArr[2];
  //  }
  //  console.log("address------- ",newAddress)
  /* var resultString = "You have selected " + display_name + "<br/>Lat: " + lat + "<br/>Lon: " + lng;*/
  document.getElementById('lat').value = lat;
  document.getElementById('lng').value = lng;
  document.getElementById('address').value = address;
  document.getElementById('urladdress').value = name;

  //document.getElementById("result").innerHTML = resultString;
}



/*
      $('#clickheight').append(test);*/
$('.clockpicker').clockpicker();

$('.datepicker').datepicker({
  locale: 'de-de',
  uiLibrary: 'bootstrap4', 
  format: "dd-mm-yyyy",
  minDate: today
});

$('.datepicker').datepicker().on('change', function() {

  var firstDate = $(this).val();
  var firstDateArr = firstDate.split('/');
  //alert(firstDate);
  $(".dat").val(firstDateArr[1] + '.' + firstDateArr[0] + '.' + firstDateArr[2]);
  $("#clickheight").val('Datum wählen');
  //$(".calender-date-custume").removeClass('height400');
});


$(".vinu_style_date_calender").click(function() {
  $("#timedata").hide();
  $(".calender-date-custume").toggle();

});
$(".vinu_style_time").click(function() {

  $("#datadate").hide();
  $(".time-custume").toggle();

});



$("#clickheight").click(function() {
  //$(".calender-date-custume").addClass('height400');
});
$(".selectTTA").click(function() {
  $("#setDateFilter").val($(this).attr('data-val'));
  // $(".calender-date-custume").removeClass('height400');
});
/*

$(document).on('click', function (event)
    {
       if (!$(event.target).closest('#testdiv').length)
       {
           $("#collapse2").addClass('collapsein');
           $("#collapse2").removeClass('in');
      }

     
   });
*/
$("#clicktimeheight").click(function() {
  //alert('fh');
  $(".time-custume").addClass('height240');
  $(".time_picker_droupdown").show();
  $('.t.active').removeClass('t active');
  $(this).addClass('t active');
});
//~ $("#clicktimeheight").click(function(){

//~ });
$("#anytime").click(function() {
  //alert($(this).val());
  $("#selctTimeFilter").val($(this).val());

  $(".time_picker_droupdown").hide();

  $(".time-custume").removeClass('height240');
  $('.t.active').removeClass('t active');
  $(this).addClass('t active');
});
$(".s").change(function() {

  if ($(this).attr('name') == 'starttime') {
    $('.s').removeClass('schecked');
    $(this).addClass('schecked');
  } else {
    $('.s').removeClass('echecked');
    $(this).addClass('echecked');
  }

  if ($('input[name="starttime"]:checked').length == 0) {
    //alert('df')
    $('.schecked').attr('checked', true);

  }
  if ($('input[name="endtime"]:checked').length == 0) {
    $('.echecked').attr('checked', true);
  }


  var time = $('input[name="starttime"]:checked').val();
  var endtime = $('input[name="endtime"]:checked').val();

  //alert(time+"="+endtime);
  var val = "";
  if (time != undefined && time != "" && endtime != undefined && endtime != "" && time >=
    endtime) { //alert(time+"="+endtime);
    $('.timeconflict_err').html('Die Endzeit kann nicht vor der Startzeit liegen');

    $(this).closest('div').find('button').text('');
    $(this).prop('checked', false);


    return false;
  } else {
    $('.timeconflict_err').html('');

  }
  if (time != undefined && time != "") {
    val = time;
  }
  if (endtime != undefined && endtime != "") {
    if (val != "") {
      val = val + '-' + endtime;
    }
  }
  $("#selctTimeFilter").val(val);
});

$(".submitdate_time").click(function() {
  $("#datadate").css("display", "block");
  //mapview();
});
$(".submitdate_time").click(function() {
  $("#datadate").css("display", "none");
  //mapview();
});


$(".closeTime").click(function() {
  var val = $(".t.active").val();
  // alert(val);
  if (val != 'Anytime') {
    if ($('input[name="starttime"]:checked').length == 0 && $('input[name="endtime"]:checked').length == 0) {
      $('.schecked').attr('checked', true);
      $('.echecked').attr('checked', true);
    }
    //~ $("input:radio.schecked:checked");
    //~ $("input:radio.echecked:checked");
    var time = $('input[name="starttime"]:checked').val();
    var endtime = $('input[name="endtime"]:checked').val();
    var val = "";
    if (time != undefined && time != "") {
      val = time;
    }
    if (endtime != undefined && endtime != "") {
      if (val != "") {
        val = val + '-' + endtime;
      }
    }


    if (endtime != undefined && endtime != "" && (time == undefined || time == "")) {

      $('.timeconflict_err').html('Startzeit auswählen');
      //$(this).prop('checked',false);
      return false;
    } else if (time != undefined && time != "" && (endtime == undefined || endtime == "")) {
      $('.timeconflict_err').html('Endzeit auswählen');
      //$(this).prop('checked',false);
      return false;
    } else if (time != undefined && time != "" && endtime != undefined && endtime != "" && time >= endtime) {
      $('.timeconflict_err').html('Die Endzeit kann nicht vor der Startzeit liegen');
      //$(this).prop('checked',false);
      return false;
    } else {

      $('.timeconflict_err').html('');
    }
    $("#selctTimeFilter").val(val);

  }
  $("#timedata").css("display", "none");
  // mapview();
});


// google_map _iq_start
// google.maps.event.addDomListener(window, 'load', function(){
//   var options = {
//       componentRestrictions: {country: ["DE"]}
//      };

//           var places = new google.maps.places.Autocomplete(document.getElementById('address'),options);


//           google.maps.event.addListener(places,'place_changed', function () {
//       var place = places.getPlace();
//               var address = place.formatted_address;
//               var latitude = place.geometry.location.lat();
//               var longitude = place.geometry.location.lng();
//               document.getElementById('lat').value=latitude;
//               document.getElementById('lng').value=longitude;
//               document.getElementById('address').value=address;
//               mapview();
//           });
//       });
// google _map _ iq

// var time = $("#selctTimeFilter").val();
//if(time=='')$("#selctTimeFilter").val('08:00-20:00');     

thankyoupopup();

function thankyoupopup() {
  var check = $('#pop_up_open').val();
  if (check != '')
    $('#congratulations_popup').modal('show');
}

$(document).ready(function() {


  $("#searchFormSubmit").click(function() {

    var catgory = $("input[name='category']:checked").attr('data-id');
    var addressurl = $("#urladdress").val();
    var actionUrl = base_url + 'listing/search';

    if (catgory != undefined && catgory != "") {
      catgory = catgory.replace(/[^A-Z0-9]/ig, "-").toLowerCase();

      actionUrl = actionUrl + '/' + catgory;
      //actionUrl=actionUrl+'/ testing only';

    }

    if (addressurl != undefined && addressurl != "") {
      addressurl = addressurl.replace(/[^A-Z0-9]/ig, "-").toLowerCase();

      actionUrl = actionUrl + '/' + addressurl;
      //actionUrl=actionUrl+'/ testing only';


    }
    //alert(actionUrl)
    $("#searchform").attr('action', actionUrl);

  });

  if ($(window).width() < 481) {
    // $('#app_popup').modal('show');
  }

  $("#findurl").click(function() {
    if ($("#getsalone").val() == '') {
      $("#getsalone").css('border-color', 'red');
    } else $("#getsalone").css('border-color', '#e8e8e8');
  });
  /*else {
     alert('close');
  }*/

});

$( window ).resize(function() {
  if ($(window).width() < 481) {
    $('#app_popup').modal('hide');
  }
});

$(document).on('click', function(event) {
  //console.log(event.target.id);
  if ($(event.target).closest('#datadate').length) {} else {
    //alert(event.target.class);
    var cls = $(event.target).attr('class');
    if (event.target.id != "setDateFilter" && cls != undefined)
      $('#datadate').css('display', 'none');
  }
  if ($(event.target).closest('#timedata').length) {} else {
    if (event.target.id != "selctTimeFilter")
      $('#timedata').css('display', 'none');
  }


});

//Select the #embeddedVideo element
var video = document.getElementById('vimeo_player');

//Create a new Vimeo.Player object
var player = new Vimeo.Player(video,{controls:false});

//When the player is ready, set the volume to 0
player.ready().then(function() {
    console.log('vimeo playing');
    player.setVolume(0);
    player.setMuted(true);  
});
</script>