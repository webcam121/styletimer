<?php
$this->load->view('frontend/common/header');
$keyGoogle = GOOGLEADDRESSAPIKEY;

$ip = $_SERVER['REMOTE_ADDR'];

//122.168.75.249 192.168.1.23
//$publicIP = file_get_contents("http://ipecho.net/plain");
//echo $publicIP;

//$localIp = gethostbyname(gethostname());
//echo $localIp;

//echo $ip; die;
$url = "http://www.geoplugin.net/json.gp?ip=" . $ip;
//$ipdat = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_POST, true);
$response = curl_exec($ch);

$ipdat = json_decode($response); // echo "<pre>"; print_r($ipdat); die;
if (!empty($ipdat)) {
    $country = $ipdat->geoplugin_countryCode;
    $my_lat = $ipdat->geoplugin_latitude;
    $my_long = $ipdat->geoplugin_longitude;
} else {
    $country = '';
    $my_lat = '';
    $my_long = '';
}
//echo "<pre>"; print_r($ipdat);
if ($country == "AT" && empty($_GET['lat']) && empty($_GET['lng'])) {
    $lat = "47.5162";
    $lng = "14.5501";
} elseif ($country == "CH" && empty($_GET['lat']) && empty($_GET['lng'])) {
    $lat = "46.8182";
    $lng = "8.2275";

} elseif (!empty($_GET['lat']) && !empty($_GET['lng'])) {
    $lat = $_GET['lat'];
    $lng = $_GET['lng'];
} else {
    $lat = "51.1657";
    $lng = "10.4515";
}
//echo $lat."==".$lng; die;
$loc_sch = (!empty($_GET['address']) ? $_GET['address'] : '');

?>

<link rel="stylesheet" href="<?php echo base_url('assets/frontend/'); ?>css/nouislider.css" />

<!-- location IQ Api -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
<link rel="stylesheet"
    href="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.5/leaflet-geocoder-locationiq.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.css" rel="stylesheet" type="text/css" />

<!-- start mid content section-->
<style>
.leaflet-locationiq-search-icon {
    background-image: url('<?php echo base_url("assets/frontend/images/search-new.svg"); ?>');
}

.leaflet-locationiq-expanded .leaflet-locationiq-input {
    /* padding-left:39px; */
    padding-left: px;
}

.leaflet-bar a,
.leaflet-bar a:hover {
    width: 30px;
    height: 40px;
    border-bottom: none !important;
}

.leaflet-container.leaflet-touch-drag.leaflet-touch-zoom {
    -ms-touch-action: none;
    touch-action: none;
    z-index: 0 !important;
}

.carousel-item.active {
    border-radius: 8px;
}

.sliderImage {
    width: 275px !important;
    height: 172px !important;
    border-radius: 0.25rem !important;
}

#slideramp .leaflet-proxy.leaflet-zoom-animated {
    transform: translate3d(17259px, 11062px, 0px) scale(64) !important;
}

.leaflet-bar {
    border: 1px solid #ced4da !important;
    z-index: 4 !important;
    box-shadow: none !important;
}

.sliderImage {
    width: 275px;
    height: 183px;
}

.colororange {
    color: #FF9944;
}

.leaflet-locationiq-results {
    margin-top: 40px !important;
    padding-top: 0px !important;
}

.pac-container:after {
    /* Disclaimer: not needed to show 'powered by Google' if also a Google Map is shown */

    background-image: none !important;
    height: 0px;
}

/* map css */
.leaflet-locationiq-results {
    padding-top: 45px;
}

.leaflet-locationiq-expanded {
    width: 100% !important;
    height: 40px;
    margin-bottom: 1rem;
}

.leaflet-bar {
    box-shadow: 0 0px 1px rgba(57, 56, 56, 0.65);
}

.slideramp {
    width: 100%;

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

.mss_sl_btn {
    font-size: 0.775rem !important;
}

.gj-picker-bootstrap {
    top: 615px !important;
    margin-left: 275px !important;
    border: none !important;
    border-radius: 0px !important;
    height: 238px !important;
    z-index: 1039 !important;
}

.calender-date-custume button.btn.mt-20 {
    position: unset;
    bottom: unset;
    left: unset;
    right: unset;
    width: 260px;
}

@media (max-width:767px) {
    .gj-picker-bootstrap {
        top: 415px !important;
        left: -222px !important;
    }

    .header {
        position: absolute;
        z-index: 9;
    }

    .on-sroll-fixed {
        position: fixed;
        z-index: 9;
        top: 0;
        left: 0;
        background: #fff;
        padding: 10px;
        box-shadow: 0px 5px 3px 0px rgba(00, 00, 00, 0.10);
    }

    .on-sroll-fixed+.mobile-none {
        margin-top: 260px;
    }
}
</style>
<?php
if ($this->session->userdata('access') == 'marchant' || $this->session->userdata('access') == 'employee') {
    $cls = 'pt-84';
} else {
    $cls = 'pt-120';
}

?>

<section class="clear service_listing_section1 clear <?php echo $cls; ?>">
    <div class="container mt-30">
        <input type="hidden" id="serchSiburb" value="">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <!-- left-side-search start-->
                <form id="serch_form">
                    <input type="hidden" value="" name="orderby" id="orderbySelect">
                    <div class="left-side-search">
                        <div class="map_view_hide_on_mobile">
                            <h4 class="color333 font-size-16 fontfamily-medium">
                                <?php echo $this->lang->line('Map-View'); ?></h4>
                            <div id="" class="relative mb-30">
                                <a href="#" class="image-map-link d-block" id="image-map-link">
                                    <div class="image-map" id="map1"></div>
                                    <div class="image-label fontfamily-medium" id="showOnMap" data-ids="">
                                        <?php echo $this->lang->line('Show-salons-on-map'); ?></div>
                                    <input type="hidden" name="uids" id="showOnMapUids">
                                </a>
                            </div>
                        </div>
                        <!-- search date time location section-->
                        <div id="map"></div>
                        <div id="on-sroll-fixed">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="form-group relative">
                                        <div class="location-icon absolute"></div>

                                        <div id="search-box"></div>
                                        <input type="hidden" name="address" id="address" value="<?php if (isset($_GET['address'])) {
    echo $_GET['address'];
}
?>" placeholder="PLZ/ Ort" class="form-control pl-28">
                                        <span class="reset display-n">
                                            <picture class="">
                                                <source
                                                    srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
                                                    type="image/webp" class="">
                                                <source
                                                    srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
                                                    type="image/png" class="">
                                                <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
                                                    class="">
                                            </picture>
                                        </span>
                                    </div>
                                </div>
                                <div class="order-1 order-sm-1 col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <input type="hidden" name="lat" value="<?php if (isset($_GET['lat'])) {
    echo $_GET['lat'];
}
?>" id="lat">
                                    <input type="hidden" name="lng" value="<?php if (isset($_GET['lng'])) {
    echo $_GET['lng'];
}
?>" id="lng">
                                    <input type="hidden" name="category" value="<?php if (isset($_GET['category'])) {
    echo $_GET['category'];
}
?>">


                                    <input type="hidden" name="my_lat" value="" id="my_lat">
                                    <input type="hidden" name="my_long" value="" id="my_long">

                                    <div class="form-group relative">
                                        <div class=""><span style="z-index: 3;"
                                                class="location-icon-two absolute"></span><span
                                                style="left: 35px;top: 10px;z-index: 3;"
                                                class="absolute fontfamily-light color666 font-size-12 mb-0 color999"><?php echo $this->lang->line('Search-distance'); ?></span>
                                        </div>
                                        <div class="btn-group multi_sigle_select">
                                            <?php $distance = array('0.2', '0.5', '1', '3', '5', '10', '20');?>

                                            <button data-toggle="dropdown"
                                                class="btn btn-default dropdown-toggle mss_sl_btn color999"
                                                style="padding-left:164px !important;color:#999 !important;text-align: center!important;font-size:12px !important;text-transform: none !important"><?php echo $this->lang->line('Nearby'); ?></button>
                                            <ul class="dropdown-menu mss_sl_btn_dm">
                                                <li class="radiobox-image">
                                                    <input type="radio" id="id_1d" name="distance" class="distance"
                                                        value="5">
                                                    <label
                                                        for="id_1d"><?php echo $this->lang->line('Near-me'); ?></label>
                                                </li>
                                                <?php
$i = 0;foreach ($distance as $d) {?>
                                                <li class="radiobox-image">
                                                    <input type="radio" id="id_2<?php echo $i; ?>" name="distance"
                                                        class="distance" value="<?php echo $d; ?>">
                                                    <label
                                                        for="id_2<?php echo $i; ?>"><?php echo $this->lang->line('within'); ?>
                                                        <?php echo $d; ?> Km</label>
                                                </li>
                                                <?php $i++;}?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pr-2">
                                    <div class="form-group date_pecker1 relative">
                                        <?php
if (isset($_COOKIE['sch_date']) && $_COOKIE['sch_date'] != '') {
    $dates = $_COOKIE['sch_date'];
} else if (!empty($_GET['date'])) {
    $dates = $_GET['date'];
} else {
    $dates = 'Anydate';
}

?>
                                        <input class="pl-35 form-control vinu_style_date_calender dat" name="date"
                                            id="setDateFilter" placeholder="Date" value="<?php echo $dates; ?>"
                                            autocomplete="off" style="background-color:#fff;" readonly>

                                        <div class="calender-date-custume relative around-15" id="datadate">

                                            <p class="color242424 fontfamily-light font-size-14 mb-25">
                                                <img src="https://dev.styletimer.de/assets/frontend/images/date_pecker_icon.svg"
                                                    class="width16 mr-10">
                                                Datum
                                            </p>
                                            <div class="row mb-20 relative">
                                                <div class="col-6">

                                                    <input type="button" class="calender-btn d selectTTA"
                                                        placeholder="Beliebig" value="Beliebig" data-val="Beliebig"
                                                        id="anydate">

                                                </div>

                                                <div class="col-6">
                                                    <input type="button" class="calender-btn d selectTTA"
                                                        placeholder="Heute" value="Heute"
                                                        data-val="<?php echo date("d-m-Y") ?>" id="today">
                                                </div>


                                                <div class="col-6" style=" margin-top: 15px;">
                                                    <input type="button" style="
                               z-index:5 !important;" class="calender-btn d datepicker selectTTA" id="clickheight"
                                                        placeholder="Datum wählen" value="Datum wählen">
                                                </div>
                                                <div class="col-6" style=" margin-top: 15px;">
                                                    <input type="button" class="calender-btn d selectTTA"
                                                        placeholder="Morgen" value="Morgen"
                                                        data-val="<?php echo date('d-m-Y', strtotime("+1 day", strtotime(date("d-m-Y")))); ?>"
                                                        id="tomorrow">
                                                </div>
                                            </div>

                                            <button type="button"
                                                class="btn mt-20 submitdate_time"><?php echo $this->lang->line('Done'); ?></button>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pl-2">
                                    <div class="form-group  time_pecker1">
                                        <?php
if (isset($_COOKIE['sch_time']) && $_COOKIE['sch_time'] != '') {
    $times = $_COOKIE['sch_time'];
} else if (!empty($_GET['time'])) {
    $times = $_GET['time'];
} else {
    $times = 'Anytime';
}

?>
                                        <input class="pl-35 form-control vinu_style_time tim" placeholder="Time"
                                            name="time" id="selctTimeFilter" value="<?php echo $times; ?>"
                                            autocomplete="off" style="background-color:#fff;" readonly>

                                        <div class="time-custume relative around-15" id="timedata">
                                            <p class="color242424 fontfamily-light font-size-14 mb-25">
                                                <img class="width16 mr-10"
                                                    src="https://dev.styletimer.de/assets/frontend/images/time_pecker_icon.svg">
                                                Uhrzeit
                                            </p>
                                            <div class="row mb-20">
                                                <div class="col-6">

                                                    <input type="button" class="calender-btn t" id="anytime"
                                                        placeholder="Anytime" value="Anytime">

                                                </div>
                                                <div class="col-6">
                                                    <input type="button" class="calender-btn t" id="clicktimeheight"
                                                        placeholder="Select Time" value="Select Time">
                                                </div>
                                                <div class="col-6">
                                                    <div class="time_picker_droupdown w-100 ml-0">
                                                        <?php
if (isset($_COOKIE['sch_start']) && $_COOKIE['sch_start'] != '') {
    $start_tm = $_COOKIE['sch_start'];
    $shwStime = $_COOKIE['sch_start'];
} else if (isset($_GET['starttime'])) {
    $start_tm = $_GET['starttime'];
    $shwStime = $_GET['starttime'];
} else {
    $start_tm = 'From';
    $shwStime = '08:00';
}
?>
                                                        <label>From</label>
                                                        <div class="btn-group multi_sigle_select inp_select">

                                                            <button data-toggle="dropdown"
                                                                class="btn btn-default dropdown-toggle mss_sl_btn"><?php echo $shwStime; ?>
                                                            </button>

                                                            <ul
                                                                class="dropdown-menu mss_sl_btn_dm custom_scroll height300">
                                                                <?php if (!empty($minmaxtime->mintime)) {
    $start = $minmaxtime->mintime;
} else {
    $start = "00:00";
}

if (!empty($minmaxtime->mintime)) {
    $end = $minmaxtime->maxtime;
} else {
    $end = "23:00";
}

$tStart = strtotime($start);
$tEnd = strtotime($end);
$tNow = $tStart;
while ($tNow <= $tEnd) {?>
                                                                <li class="radiobox-image">
                                                                    <input class="s <?php if ($start_tm == 'From' && date("H:i", $tNow) == '08:00') {
    echo 'schecked';
}
    ?>" type="radio" id="from_<?php echo date("H:i", $tNow); ?>" name="starttime"
                                                                        value="<?php echo date("H:i", $tNow); ?>"
                                                                        <?php if ($start_tm == date("H:i", $tNow)) {echo "checked='checked'";}?>>
                                                                    <label
                                                                        for="from_<?php echo date("H:i", $tNow); ?>"><?php echo date("H:i", $tNow) ?></label>
                                                                </li>
                                                                <?php $tNow = strtotime('+60 minutes', $tNow);}?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <?php if (isset($_COOKIE['sch_end']) && $_COOKIE['sch_end'] != '') {
    $end_tm = $_COOKIE['sch_end'];
    $showEtime = $_COOKIE['sch_end'];
} else if (isset($_GET['endtime'])) {

    $end_tm = $_GET['endtime'];
    $showEtime = $_GET['endtime'];
} else {
    $end_tm = 'To';
    $showEtime = '20:00';
}
?>
                                                    <div class="time_picker_droupdown w-100 ml-0">
                                                        <label>To</label>
                                                        <div class="btn-group multi_sigle_select inp_select">

                                                            <button data-toggle="dropdown"
                                                                class="btn btn-default dropdown-toggle mss_sl_btn"><?php echo $showEtime; ?></button>

                                                            <ul
                                                                class="dropdown-menu mss_sl_btn_dm custom_scroll height300">
                                                                <?php if (!empty($minmaxtime->mintime)) {
    $start = $minmaxtime->mintime;
} else {
    $start = "00:00";
}

if (!empty($minmaxtime->mintime)) {
    $end = $minmaxtime->maxtime;
} else {
    $end = "23:00";
}

$tStart = strtotime($start);
$tEnd = strtotime($end);
$tNow = $tStart;
while ($tNow <= $tEnd) {?>
                                                                <li class="radiobox-image">
                                                                    <input class="s <?php if ($end_tm == 'To' && date("H:i", $tNow) == '20:00') {
    echo 'echecked';
}
    ?>" type="radio" id="to_<?php echo date("H:i", $tNow); ?>" name="endtime" value="<?php echo date("H:i", $tNow); ?>"
                                                                        <?php if ($end_tm == date("H:i", $tNow)) {echo "checked='checked'";}?>>
                                                                    <label
                                                                        for="to_<?php echo date("H:i", $tNow); ?>"><?php echo date("H:i", $tNow) ?></label>
                                                                </li>
                                                                <?php $tNow = strtotime('+60 minutes', $tNow);}?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12"><span class="error timeconflict_err"></span>
                                                </div>
                                            </div>
                                            <button class="btn mt-20 closeTime"
                                                type="button"><?php echo $this->lang->line('Done'); ?></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- search date time location section-->
                            <div class="mobile-btn-show" href="#jump">
                                <button type="button"
                                    class="btn btn-border-orange border-radius4 widthfit1 mobile-show-col colororange">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="#FF9944">
                                        <path fill="#FF9944"
                                            class="Button--button--242502 Button--medium--3861b1 Button--outlineBlue--6d4b33"
                                            d="M10 17v-1.01c0-.546.444-.99 1-.99a1 1 0 0 1 1 .99v4.02c0 .546-.444.99-1 .99a1 1 0 0 1-1-.99V19H2v-2h8zm5-6V9.99c0-.546.444-.99 1-.99a1 1 0 0 1 1 .99v4.02c0 .546-.444.99-1 .99a1 1 0 0 1-1-.99V13H2v-2h13zM5 5V3.99C5 3.445 5.444 3 6 3a1 1 0 0 1 1 .99v4.02C7 8.555 6.556 9 6 9a1 1 0 0 1-1-.99V7H2V5h3zm4 0h13v2H9V5zm10 6h3v2h-3v-2zm-5 6h8v2h-8v-2z">
                                        </path>
                                    </svg>
                                    <i class="fas fa-check ml-1 font-size-16 mt-2" style=""></i>
                                </button>
                                <!-- after filter show -->

                            </div>
                        </div>
                        <div class="mobile-none" id="jump">
                            <div class="border-t border-b pt-30 pb-30">
                                <h4 class="color333 font-size-16 fontfamily-medium mb-20">Services</h4>
                                <?php if (!empty($sucategory)) {
    //echo '<pre>'; print_r($sucategory); die;
    foreach ($sucategory as $cat) {
        if ($cat->totalcount > 0) {?>
                                <div class="d-flex mt-3">
                                    <div class="checkbox mt-0 mb-0">
                                        <label class="font-size-14 pl0 color666">
                                            <!-- <input type="checkbox" name="sucatgory[]" class="checkboxcat" value="<?php echo $cat->id; ?>" <?php if ((!empty($_GET['cid']) && $cat->id == url_decode($_GET['cid'])) || (!empty($_GET['category']) && $cat->parent_id == url_decode($_GET['category']))) {
            echo "checked";
        }
            ?>> -->
                                            <input type="checkbox" name="sucatgory[]" class="checkboxcat"
                                                value="<?php echo $cat->id; ?>" <?php if (!empty($_GET['cid']) && $cat->id == url_decode($_GET['cid'])) {
                echo "checked";
            }
            ?>>
                                            <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                            <?php echo $cat->category_name; ?>
                                        </label>
                                    </div>

                                    <span class="ml-auto color999 font-size-14 fontfamily-regular">
                                        <?php echo $cat->totalcount; ?>
                                    </span>

                                </div>
                                <?php }}} else {
    echo "No listing yet.";
}
?>
                            </div>

                            <div class="pt-30 pb-30 border-b">
                                <h4 class="color333 font-size-16 fontfamily-medium mb-20">
                                    <?php echo $this->lang->line('Price-Range'); ?></h4>
                                <div class="relative">
                                    <div id="test-slider"></div>

                                    <small
                                        class="small_box_input border-w border-radius4 bgwhite color333 vertical-top display-ib text-center"
                                        style="left:0px;" id="startRange">0 €</small>
                                    <small
                                        class="small_box_input border-w border-radius4 bgwhite color333 vertical-top display-ib text-center"
                                        id="endRange">80 €</small>
                                    <input type="hidden" name="startrange" id="startRangeVal">
                                    <input type="hidden" name="endrange" id="endRangeVal">
                                </div>

                            </div>

                            <div class="pt-30 pb-30">
                                <h4 class="color333 font-size-16 fontfamily-medium mb-20">
                                    <?php echo $this->lang->line('Express-Treatments'); ?></h4>
                                <div class="checkbox mt-0 mb-0">
                                    <label class="fontsize-14 pl0 color666 fontfamily-medium mb-1">
                                        <input type="checkbox" name="expess_offer" class="exp_offer_2hrs" value="yes">
                                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                        <i class="far fa-clock mr-1"></i>
                                        <?php echo $this->lang->line('Express-offers'); ?>
                                    </label>
                                    <span class="fontfamily-regular font-size-14 color999 display-b ml-50">(nächste
                                        2 Stunden oder weniger)</span>
                                </div>

                            </div>
                            <!-- <div class="text-center mobile-btn-show">
                    <button type="button" class="btn btn-small widthfit mobile-show-col">Apply Now</button>
                  </div> -->
                        </div>


                    </div>
                    <input type="hidden" id="page_no" name="page" value="1">
                    <!-- left-side-search end-->
                </form>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <!-- right-side-search end-->
                <div class="right-side-search">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-7 col-lg-6 col-xl-7 pr-0">
                            <div class="display-ib vertical-middle lineheight40">
                                <span class="font-size-14 color666 fontfamily-regular" id="saloneCountText"></span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-5 col-lg-6 col-xl-5">
                            <div class="btn-group multi_sigle_select">

                                <button data-toggle="dropdown"
                                    class="btn btn-default dropdown-toggle mss_sl_btn color999 font-size-14"
                                    style="font-size:14px!important;color:#999 !important;"><?php echo $this->lang->line('Sortby'); ?></button>
                                <ul class="dropdown-menu mss_sl_btn_dm">
                                    <li class="radiobox-image">
                                        <input type="radio" id="id_1" name="order_by" class="order_by"
                                            value="rating desc">
                                        <label for="id_1"><?php echo $this->lang->line('Highest-Rated'); ?></label>
                                    </li>
                                    <li class="radiobox-image">
                                        <input type="radio" id="id_2" name="order_by" class="order_by"
                                            value="rating asc">
                                        <label for="id_2"><?php echo $this->lang->line('Lowest-Rated'); ?></label>
                                    </li>
                                    <li class="radiobox-image">
                                        <input type="radio" id="idc_3" name="order_by" class="order_by"
                                            value="distance asc">
                                        <label for="idc_3"><?php echo $this->lang->line('Closest-Location'); ?></label>
                                    </li>
                                    <li class="radiobox-image">
                                        <input type="radio" id="idc_4" name="order_by" class="order_by"
                                            value="totalcount desc">
                                        <label for="idc_4"><?php echo $this->lang->line('Most-Reviews'); ?></label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- first end -->
                    <div id="all_listing">

                    </div>
                </div>
                <nav aria-label="" class="mb-50">
                    <ul class="pagination" id="pagination">
                        <!--
                      <li class="page-item">
                        <a class="color333 a_hover_333 mr-3" href="#" aria-label="Previous">
                          <span class="sr-only">Previous</span>
                        </a>
                      </li>
                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item"><a class="page-link" href="#">4</a></li>
                      <li class="page-item">
                        <a class="color333 a_hover_333 ml-3" href="#" aria-label="Next">
                          <span class="sr-only">Next</span>
                        </a>
                      </li>
-->
                    </ul>
                </nav>
                <!-- right-side-search end-->
            </div>
        </div>
        <!-- <div class="row">
				<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 offset-xl-4 offset-lg-4">


				</div>
              </div> -->

    </div>
</section>
<!-- end mid content section-->
<?php $this->load->view('frontend/common/footer');?>


<!-- SHOW map view -->

<div class="modal fade" id="map-slider">
    <div class="modal-dialog modal-lg width1100 modal-dialog-centered" role="document">
        <div class="modal-content">
            <a href="#" class="crose-btn-blue" data-dismiss="modal">
                <img src="<?php echo base_url("assets/frontend/"); ?>images/popup_crose_white_icon.svg"
                    class="popup-crose-white-icon">
            </a>
            <div class="modal-body p-0 bglightgreen1">
                <div class="popup-map-slider-img" style="height:400px; width: 100%;" id="slideramp">
                </div>
                <div class="relative ml-60 mr-60 mt-30 mb-30 all_popup_slider_html">
                    <div class="bgwhite box-shadow1 rio-promos">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="favourite-loging-check">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <a href="#" class="crose-btn" data-dismiss="modal">
                <picture class="popup-crose-black-icon">
                    <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>"
                        type="image/webp" class="popup-crose-black-icon">
                    <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
                        type="image/png" class="popup-crose-black-icon">
                    <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>"
                        class="popup-crose-black-icon">
                </picture>
            </a>
            <div class="modal-body pt-30 mb-30 pl-40 pr-40 bglightgreen1">
                <picture class="">
                    <source srcset="<?php echo base_url('assets/frontend/images/popup-registeri-user-icon.webp') ?>"
                        type="image/webp">
                    <source srcset="<?php echo base_url('assets/frontend/images/popup-registeri-user-icon.svg'); ?>"
                        type="image/svg">
                    <img src="<?php echo base_url('assets/frontend/images/popup-registeri-user-icon.svg'); ?>">
                </picture>
                <p id="addservice_message"
                    class="font-size-18 color333 fontfamily-medium mt-3 mb-30 text-lines-overflow-1"
                    style="padding:5px;">
                    <?php
if (!empty($this->session->userdata('st_userid'))) {
    if ($this->session->userdata('access') == 'employee') {
        echo "As a employee you can not mark favourite.";
    } else {
        echo "As a salon you can not mark favourite.";
    }

} else {
    echo "Um einen Salon zu deinen Favoriten hinzuzufügen, logge dich ein oder registriere dich, falls du noch keinen Account besitzt.";
}
?>
                </p>
                <?php if (!empty($this->session->userdata('st_userid'))) {?>
                <button type="button" class=" btn btn-large widthfit" data-dismiss="modal">Ok</button>
                <?php } else {?>
                <a href="<?php echo base_url('auth/login'); ?>"><button type="button"
                        class=" btn btn-large widthfit">Einloggen / Registrieren</button></a>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/frontend/'); ?>js/nouislider.js"></script>
<!-- location IQ Api -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>
<script type="text/javascript" src="https://tiles.unwiredmaps.com/js/leaflet-unwired.js"></script>
<script src="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.5/leaflet-geocoder-locationiq.min.js">
</script>

<script type="text/javascript">
var lat1 = $("#lat").val();
var lng1 = $("#lng").val();
//alert(lat1);
if (lng1 == "" && lat1 == "") {
    lat1 = "<?php echo $lat; ?>";
    lng1 = "<?php echo $lng; ?>";
}
$("#my_lat").val("<?php echo $my_lat; ?>");
$("#my_long").val("<?php echo $my_long; ?>");

// first map section

// Maps access token goes here

// Add layers that we need to the map
var streets = L.tileLayer.Unwired({
    key: iq_api_key,
    scheme: "streets"
});

// Initialize the map
var map1 = L.map('map1', {
    center: [lat1, lng1], // Map loads with this location as center
    zoom: 10,
    scrollWheelZoom: false,
    layers: [streets] // Show 'streets' by default
});
console.log("streets================ lat lon", map1)
// Add the 'scale' control
L.control.scale().addTo(map1);

// Add the 'layers' control
/* L.control.layers({
     "Streets": streets
 }).addTo(map1);*/

// Add a 'marker'
//L.marker([39.73, -104.98]).addTo(map1).bindPopup("<b>Hello world!</b><br>I am a popup one.");
//L.marker([39.74, -104.98]).addTo(map1).bindPopup("<b>Hello world!</b><br>I am a popup two.");
//L.marker([39.73, -104.98]).addTo(map1).on('click', onClick);
// function onClick(e) {
//   alert(this.getLatLng());
// }


var streets = L.tileLayer.Unwired({
    key: iq_api_key,
    scheme: "streets"
});

// Initialize the map
var map2 = L.map('slideramp', {
    center: [lat1, lng1], // Map loads with this location as center
    zoom: 7,
    scrollWheelZoom: true,
    layers: [streets] // Show 'streets' by default
});

// Add the 'scale' control
L.control.scale().addTo(map2);

// Add the 'layers' control
/* L.control.layers({
     "Streets": streets
 }).addTo(map2);*/




// search section
// Initialize an empty map without layers (invisible map)
var map = L.map('map', {
    center: [lat1, lng1], // Map loads with this location as center
    zoom: 17,
    scrollWheelZoom: true,
    zoomControl: false,
    attributionControl: false,

});

//Geocoder options
var geocoderControlOptions = {
    params: {
        countrycodes: 'DE,AT,CH'
    },
    bounds: false, //To not send viewbox
    markers: false, //To not add markers when we geocoder
    attribution: null, //No need of attribution since we are not using maps
    expanded: true, //The geocoder search box will be initialized in expanded mode
    panToPoint: false, //Since no maps, no need to pan the map to the geocoded-selected location
    placeholder: 'PLZ/ Ort'

}

//Initialize the geocoder
var geocoderControl = new L.control.geocoder(iq_api_key, geocoderControlOptions).addTo(map).on('select', function(e) {
    displayLatLon(e.feature.feature.display_name, e.latlng.lat, e.latlng.lng);
}).on('reset', function(e) {
    document.getElementById('lat').value = "";
    document.getElementById('lng').value = "";
    document.getElementById('address').value = "";
    mapview();
});


//Get the "search-box" div
var searchBoxControl = document.getElementById("search-box");
//Get the geocoder container from the leaflet map
var geocoderContainer = geocoderControl.getContainer();
//Append the geocoder container to the "search-box" div
searchBoxControl.appendChild(geocoderContainer);

//Displays the geocoding response in the "result" div
function displayLatLon(display_name, lat, lng) {
    //var resultString = "You have selected " + display_name + "<br/>Lat: " + lat + "<br/>Lon: " + lng;

    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;
    document.getElementById('address').value = display_name;
    //document.getElementById("result").innerHTML = resultString;
    mapview();
}


var loc_sch = "<?php echo $loc_sch; ?>";
if (loc_sch != "")
    $('.leaflet-locationiq-close').removeClass('leaflet-locationiq-hidden');
//$('.leaflet-locationiq-close').show();
</script>


<script>
var sch_add = "<?php echo (!empty($_GET['address']) ? $_GET['address'] : ''); ?>";

var marker;
var theMarker1 = [];
var theMarker2 = [];

function mapview() {
    //alert('er');
    var styles = {
        default: null,
        hide: [{
            featureType: 'poi.business',
            stylers: [{
                visibility: 'off'
            }]
        }]
    };

    //markers.clearLayers();

    loading();

    $.post(base_url + "listing/getUserdetails", $("#serch_form").serialize(), function(data) {

        var obj = jQuery.parseJSON(data);
        if (obj.success == '1') {

            if ($("#address").val()) {
                var address = $("#address").val();
                var splival = address.split(',');
                $("#saloneCountText").html(obj.count + " salons found in " + splival[0]);
            } else {
                $("#saloneCountText").html(obj.count + " <?php echo $this->lang->line('salons-found'); ?>");
            }
            //addanchoreUrl
            $("#all_listing").html(obj.html);
            $("#pagination").html(obj.pagination);
            unloading();
            //alert('f');
            addanchor_url();


        }

    });

    // map1.removeLayer(theMarker1);
    //map2.removeLayer(theMarker2);
    // Reset marker before reset
    for (k = 0; k < theMarker1.length; k++) {
        map1.removeLayer(theMarker1[k]);
    }
    for (l = 0; l < theMarker2.length; l++) {
        map2.removeLayer(theMarker2[l]);
    }

    //theMarker1.clearLayers();


    $.post(base_url + "listing/get_lat_lng_marker", $("#serch_form").serialize(), function(data) {
        //console.log(data);
        var obj = jQuery.parseJSON(data);
        if (obj.success == '1') {
            var icone = base_url + 'assets/frontend/images/location.svg';
            // alert(icone);
            // var marker;
            var i;
            var locations = obj.latlng;
            var userids = [];
            var latlng = [];
            for (i = 0; i < locations.length; i++) {

                latlng.push(new L.LatLng(locations[i]['latitude'], locations[i]['longitude']));

                //alert(locations[i]['latitude']);

                var greenIcon = L.icon({
                    iconUrl: base_url + 'assets/frontend/images/location.svg'

                });

                //var greenIcon = new LeafIcon({iconUrl: base_url+'assets/frontend/images/location.svg'});

                //~ L.icon = function (options) {
                //~ return new L.Icon(options);
                //~ };

                userids.push(locations[i]['id']);
                theMarker = new L.marker([locations[i]['latitude'], locations[i]['longitude']], {
                    id: locations[i]['id'],
                    icon: greenIcon
                }).addTo(map1).on('click', function(map1) {});



                //One way is to do MapClick.on('click', L.bind(onMapClick, null, ID)).
                //L.marker([locations[i]['latitude'], locations[i]['longitude']]).addTo(map2).on('click', onClick_popup);
                theMarkers = new L.marker([locations[i]['latitude'], locations[i]['longitude']], {
                    id: locations[i]['id'],
                    icon: greenIcon
                }).addTo(map2).on('click', L.bind(changeSlick, null, i));

                theMarker1.push(theMarker);
                theMarker2.push(theMarkers)
            }

            //alert('bound');
            var bounds = new L.LatLngBounds(latlng);
            map1.fitBounds(bounds);


            //function(e) {  console.log(e); changeSlick(j); }
            //map.fitBounds(latlngbounds);
            //map2.fitBounds(latlngbounds);
            if (userids.length >= 1) {
                var uids = userids.join(',');
                $("#showOnMapUids").val(uids);

            }

        } else
            $("#showOnMapUids").val(0);

    });




}

function addanchor_url() {

    $(".addanchoreUrl").each(function() {
        var id = $(this).attr('data-id');
        var href = $(this).attr('data-href');
        var sids = $(this).attr('data-service');
        var date = $("#setDateFilter").val();
        var time = $('input[name="starttime"]:checked').val();

        var urlParam = "";
        if (date != undefined && date != "" && date != "Anydate") {
            urlParam = '&date=' + date;
        }
        if (time != undefined && time != "") {
            urlParam = urlParam + '&time=' + time;
        }
        //var date=$("#setDateFilter").val(starttime);

        if (sids != undefined && sids != "") {
            $("#" + id).attr('href', href + "?servicids=" + sids +
                urlParam); //window.location.href=href+"?servicids="+sids+urlParam;
        } else {
            $("#" + id).attr('href', href); //window.location.href=href;
        }

    });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
        'Error: The Geolocation service failed.' :
        'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
}


window.onload = function() {
    mapview();

};


$(function() {
    $(window).on("scroll", function() {
        if ($(window).scrollTop() > 90) {
            $(".header").addClass("header_top");
        } else {
            //remove the background property so it comes transparent again (defined in your css)
            $(".header").removeClass("header_top");
        }
    });
});

/*$('.datepicker').datepicker({
    uiLibrary: 'bootstrap4',
    format:"dd-mm-yyyy"
});*/

$('.clockpicker').clockpicker();


$(document).on('click', '.open_time_dropdown', function() {
    var uid = $(this).attr('data-id');
    var count = $(this).attr('data-count');
    if (count == "1") {} else {
        $(this).attr('data-count', "1");
        var status = localStorage.getItem('STATUS');
        if (status == 'LOGGED_OUT') {
            console.log('STATUS=' + status);
            console.log(base_url)
            window.location.href = base_url;
            $(window.location)[0].replace(base_url);
            $(location).attr('href', base_url);
        }
        console.log('STATUS=' + status);
        loading();
        //alert(uid);
        $.post(base_url + "listing/get_merchant_availablity", {
            uid: uid
        }, function(data) {

            // alert(';ds');
            //console.log(data);
            var obj = jQuery.parseJSON(data);
            if (obj.success == '1') {

                $("#days_availablity" + uid).html(obj.dayhtml);

                unloading();
                //alert('if');
                //console.log(obj);
            }

        });
    }


});

$(document).on('change', '.distance,.checkboxcat,.exp_offer_2hrs', function() {
    mapview();
});
$(document).on('change', '.order_by', function() {
    $("#orderbySelect").val($(this).val());
    mapview();
});

var date = new Date();
var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());


/*    $('.datepicker').datepicker({
         uiLibrary: 'bootstrap4',
         format:"dd-mm-yyyy",
         minDate:today

     }); */
/*
      $('#clickheight').append(test);*/
$('.clockpicker').clockpicker();
</script>
<script type="text/javascript">
$('.datepicker').datepicker().on('change', function() {

    var firstDate = $(this).val();
    $(".dat").val(firstDate);
    $("#clickheight").val('Choose Date');
    //$(".calender-date-custume").removeClass('height400');
});

/*$(document).on('click' ,function(){
  $(".calender-date-custume").removeClass('height400');
});*/
</script>
<script type="text/javascript">
$(".vinu_style_date_calender").click(function() {
    $("#timedata").hide();
    $(".calender-date-custume").toggle();

});
$(".vinu_style_time").click(function() {

    $("#datadate").hide();
    $(".time-custume").toggle();

});
$(document).on('click', function(event) {
    //console.log(event.target.id);
    if ($(event.target).closest('#datadate').length) {} else {
        var cls = $(event.target).attr('class');
        if (event.target.id != "setDateFilter" && cls != undefined)
            $('#datadate').css('display', 'none');
    }
    if ($(event.target).closest('#timedata').length) {} else {
        if (event.target.id != "selctTimeFilter")
            $('#timedata').css('display', 'none');
    }


});
</script>
<script type="text/javascript">
$("#clickheight").click(function() {
    //$(".calender-date-custume").addClass('height400');
});
$(".selectTTA").click(function() {
    $("#setDateFilter").val($(this).attr('data-val'));
    //$(".calender-date-custume").removeClass('height400');
});

$("#clicktimeheight").click(function() {
    //alert('fh');
    $(".time-custume").addClass('height240');
    $(".time_picker_droupdown").show();
});
//~ $("#clicktimeheight").click(function(){

//~ });
$("#anytime").click(function() {
    $("#selctTimeFilter").val($(this).val());

    $(".time_picker_droupdown").hide();

    $(".time-custume").removeClass('height240');
});
$(".s").change(function() {

    if ($(this).attr('name') == 'starttime') {
        $('.s').removeClass('schecked');
        $(this).addClass('schecked');
    } else {
        $('.s').removeClass('echecked');
        $(this).addClass('echecked');
    }
    var time = $('input[name="starttime"]:checked').val();
    var endtime = $('input[name="endtime"]:checked').val();
    var val = "";
    if (time != undefined && time != "" && endtime != undefined && endtime != "" && time >= endtime) {
        $('.timeconflict_err').html('Die Endzeit kann nicht vor der Startzeit liegen');
        $(this).prop('checked', false);
        $(this).closest('div').find('button').text('');
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
    mapview();
});
$(".submitdate_time").click(function() {
    $("#datadate").css("display", "none");
    mapview();
});


$(".closeTime").click(function() {
    var val = $(".t.active").val();
    //alert(val);
    if (val != 'Anytime') {
        // var time= $('input[name="starttime"]:checked').val();
        // var endtime= $('input[name="endtime"]:checked').val();
        // alert(time+'=='+endtime);
        $('.schecked').attr('checked', true);
        $('.echecked').attr('checked', true);
        var time = $('input[name="starttime"]:checked').val();
        var endtime = $('input[name="endtime"]:checked').val();
        var val = ""
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
        } else if (time != undefined && time != "" && endtime != undefined && endtime != "" && time >=
            endtime) {
            $('.timeconflict_err').html('Die Endzeit kann nicht vor der Startzeit liegen');
            //$(this).prop('checked',false);
            return false;
        } else {

            $('.timeconflict_err').html('');
        }

        $("#selctTimeFilter").val(val);

    }
    $("#timedata").css("display", "none");
    mapview();
});

/*$(".goprofile").click(function(){
	var id=$(this).attr('data-id');
	var href=$(this).attr('data-href');

	var sids=$(".servicemactch"+id).attr('data-service');
	var date=$("#setDateFilter").val();
	var time= $('input[name="starttime"]:checked').val();
	var urlParam="";
	if(date!=undefined && date!="" && date!="Anydate"){
		urlParam='&date='+date;
		}
	if(time!=undefined && time!=""){
		urlParam=urlParam+'&time='+time;
		}
	//var date=$("#setDateFilter").val(starttime);

	if(sids!=undefined && sids!=""){
		window.location.href=href+"?servicids="+sids+urlParam;
	}
	else{
		window.location.href=href;
		}


});	 */

//~ $(document).on('touchstart click',".goprofile",function(){
//~ var id=$(this).attr('data-id');
//~ var href=$(this).attr('data-href');

//~ var sids=$(".servicemactch"+id).attr('data-service');
//~ var date=$("#setDateFilter").val();
//~ var time= $('input[name="starttime"]:checked').val();
//~ var urlParam="";
//~ if(date!=undefined && date!="" && date!="Anydate"){
//~ urlParam='&date='+date;
//~ }
//~ if(time!=undefined && time!=""){
//~ urlParam=urlParam+'&time='+time;
//~ }
//~ //var date=$("#setDateFilter").val(starttime);

//~ if(sids!=undefined && sids!=""){
//~ //window.location.href=href+"?servicids="+sids+urlParam;
//~ }
//~ else{
//~ //window.location.href=href;
//~ }

//~ });
$(document).on('click', "#pagination a", function() {
    var url = $(this).attr('href');
    if (url != undefined && url != "") {
        var status = localStorage.getItem('STATUS');
        if (status == 'LOGGED_OUT') {
            console.log('STATUS=' + status);
            console.log(base_url)
            window.location.href = base_url;
            $(window.location)[0].replace(base_url);
            $(location).attr('href', base_url);
        }
        console.log('STATUS=' + status);
        loading();
        $.post(url, $("#serch_form").serialize(), function(data) {

            // alert(';ds');
            //console.log(data);
            var obj = jQuery.parseJSON(data);
            if (obj.success == '1') {
                $(window).scrollTop(0);
                $("#all_listing").html(obj.html);
                $("#pagination").html(obj.pagination);

                unloading();
                //alert('yg');
                addanchor_url();
                //alert('if');
                //console.log(obj);
            }


        });
    }
    return false;


});


$(document).on('click', '#showOnMap', function() {
    viewonmap();

});

$(document).on('click', '.showSalonOnmap', function() {
    //alert($(this).attr('data-uid'));
    viewonmap($(this).attr('data-uid'));

});

function viewonmap(uid = '') {
    console.log(uid);

    var url = base_url + "listing/get_salon_on_popup";
    //loading();
    var data = $("#serch_form").serialize();
    if (uid != '') {
        var strArray = data.split("&");
        strArray[1] = "uids=" + uid;
        data = strArray.join('&');
    }
    //console.log(data);

    var numItems = $('.popupSlideCount').length;

    for (i = -1; i < numItems; i++) {
        //  alert(i)
        $('.rio-promos').slick('slickRemove', i);
    }
    var status = localStorage.getItem('STATUS');
    if (status == 'LOGGED_OUT') {
        console.log('STATUS=' + status);
        console.log(base_url)
        window.location.href = base_url;
        $(window.location)[0].replace(base_url);
        $(location).attr('href', base_url);
    }
    console.log('STATUS=' + status);
    $.post(url, data, function(data) {
        var obj = jQuery.parseJSON(data);
        if (obj.success == '1') {
            var latlng = [];
            var latLng1 = "";
            if (uid != '') {
                //alert(theMarker2.length);
                for (var i = 0; i < theMarker2.length; i++) {
                    //console.log(theMarker2[i]);
                    if (theMarker2[i].options.id == uid) {

                        // latlngbounds.extend(theMarker2[i].position);
                        latlng.push(new L.LatLng(theMarker2[i]._latlng.lat, theMarker2[i]._latlng.lng));
                        map2.addLayer(theMarker2[i]);
                        var latLng1 = new L.LatLng(theMarker2[i]._latlng.lat, theMarker2[i]._latlng.lng, {
                            id: uid
                        });

                    } else {
                        map2.removeLayer(theMarker2[i]);
                    }
                }
            } else {
                for (var i = 0; i < theMarker2.length; i++) {
                    latlng.push(new L.LatLng(theMarker2[i]._latlng.lat, theMarker2[i]._latlng.lng));
                    map2.addLayer(theMarker2[i]);
                }

            }
            //latlngbounds.getCenter();

            if (uid != '') {

                ///map2.setCenter(latLng);
                map2.setZoom(17);
                map2.panTo(latLng1);
                setTimeout(function() {
                    $('.gmnoprint .gm-control-active').trigger('click');
                }, 1000);

            } else {
                //map2.setZoom(18);
                ///map2.fitBounds(latlngbounds);
                // map2.zoom=18;
                // var bounds = new L.LatLngBounds(latlng);
                //map2.fitBounds(bounds);


            }

            //map2.setZoom(1);
            ///map2.getCenter();
            //console.log(slideIndex);


            if (uid != '') {
                $('.rio-promos').slick('removeSlide', null, null, true);
                $('.rio-promos').slick('slickAdd', obj.html);
                $('.slick-track').css({
                    "width": "100%",
                    "transform": "translate3d(0px, 0px, 0px)"
                });
                $('.slick-slide:first-child').css('width', '100%');
                $("#map-slider").modal("show");
                setTimeout(function() {
                    map2.invalidateSize();
                }, 500);
            } else {
                $('.rio-promos').slick('slickAdd', obj.html);
                $('.slick-track').css({
                    "width": "100%",
                    "transform": "translate3d(0px, 0px, 0px)"
                });
                $('.slick-slide:first-child').css('width', '100%');
                $("#map-slider").modal("show");
                setTimeout(function() {
                    map2.invalidateSize();
                }, 500);
            }

            $(".addanchorePopup").each(function() {
                var id = $(this).attr('data-id');
                var href = $(this).attr('data-href');
                var sids = $(this).attr('data-service');
                var date = $("#setDateFilter").val();
                var time = $('input[name="starttime"]:checked').val();

                var urlParam = "";
                if (date != undefined && date != "" && date != "Anydate") {
                    urlParam = '&date=' + date;
                }
                if (time != undefined && time != "") {
                    urlParam = urlParam + '&time=' + time;
                }
                //var date=$("#setDateFilter").val(starttime);

                if (sids != undefined && sids != "") {
                    $("#" + id).attr('href', href + "?servicids=" + sids +
                        urlParam); //window.location.href=href+"?servicids="+sids+urlParam;
                } else {
                    $("#" + id).attr('href', href); //window.location.href=href;
                }

            });


            //~ $('.rio-promos').slick('slickAdd',obj.html);
            //~ $('.slick-track').css({"width": "100%","transform": "translate3d(0px, 0px, 0px)","height": "156px"});
            //~ $('.slick-slide:first-child').css('width','100%');
            //~ $("#map-slider").modal("show");
            //~ setTimeout(function() {
            //~ map2.invalidateSize();
            //~ }, 500);

            // $("#map-slider").modal("show");
            //unloading();
        }

    });
}

//~ $(document).on('click', "#showOnMap",function (){




//~ });
$(document).on('click', ".d", function() {
    //alert('d');
    $('.d.active').removeClass('active');
    $(this).addClass('active');
});
$(document).on('click', ".t", function() {
    $('.t.active').removeClass('active');
    $(this).addClass('active');
});
//~ $(document).on('click', "#showOnMap",function (){

//~ var url=base_url+"listing/get_salon_on_popup";
//~ loading();
//~ $.post(url,$("#serch_form").serialize(),function(data){
//~ var obj = jQuery.parseJSON( data );
//~ if(obj.success=='1'){
//~ $("#all_popup_slider_html").html(obj.html);
//~ $("#map-slider").modal("show");
//~ unloading();
//~ }

//~ });
//~ });
$('.rio-promos').slick({
    dots: false,
    infinite: false,
    speed: 500,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: false,
    arrows: true,

});

function changeSlick(i) {
    var it = parseInt(i);
    $('.rio-promos').slick('slickGoTo', it);
    //$('.rio-promos').slickGoTo(it);
}

$(document).ready(function() {
    $(document).on('click', ".showSalonOnmap", function() {
        return false;
    });

    $(document).on('click', ".ui-icon-circle-triangle-e", function() {
        setTimeout(function() {
            $('#datadate').css('display', 'block');
        }, 50);
        $(document).on('click', ".ui-icon-circle-triangle-w", function() {
            setTimeout(function() {
                $('#datadate').css('display', 'block');
            }, 50);
        });
    });


    //~ $('#image-map-link').click(function(){
    //~ $('.slick-track').css({"width": "100%","transform": "translate3d(0px, 0px, 0px)","height": "156px"});
    //~ $('.slick-slide:first-child').css('width','100%');
    //~ });
});
</script>


<script>
$(document).ready(function() {

    $(document).on('click', '.favourite_click', function() {
        var type = $(this).attr('id');
        var sid = $(this).attr('data-id');
        //alert(loadid);
        var data = {
            'salon_id': sid,
            'status': type
        };
        loading();
        $.ajax({
            url: base_url + "listing/myfavourite",
            type: "POST",
            data: data,
            success: function(response) {
                if (response) {
                    var obj = jQuery.parseJSON(response);
                    if (obj.success == 1) {
                        if (type == 'add') {
                            $('#no_fav_' + sid).hide();
                            $('#your_fav_' + sid).show();
                        } else {
                            $('#no_fav_' + sid).show();
                            $('#your_fav_' + sid).hide();
                        }
                    } else {
                        $('#favourite-loging-check').modal('show');
                    }


                }
                unloading();
            }

        });
        return false;
    });

});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $(".mobile-btn-show").click(function() {
        $(".mobile-none").toggle();
    });
});
</script>

<script type="text/javascript">
$(function() {
    $(window).on("scroll", function() {
        if ($(window).scrollTop() > 150) {
            $("#on-sroll-fixed").addClass("on-sroll-fixed");

        } else {
            $("#on-sroll-fixed").removeClass("on-sroll-fixed");
        }
    });
});
</script>
<script type="text/javascript">
$(".mobile-btn-show").on("click", function(e) {
    e.preventDefault();

    $("body,html").animate({
        scrollTop: $($('.mobile-btn-show').attr('href')).offset().top
    }, 600);

});

$(".leaflet-locationiq-input").val(sch_add);



/*getLocation();
     function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);

      } else {
        $("#my_lat").val("");
        $("#my_long").val("");
         mapview();

      }

    }

    function showPosition(position) {
     $("#my_lat").val(position.coords.latitude);
        $("#my_long").val(position.coords.longitude);
         mapview();
    }*/
</script>