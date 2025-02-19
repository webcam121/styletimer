<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <title>StyleTImer</title>

<style>
@import url('https://fonts.googleapis.com/css?family=Poppins');
table{
  font-size: 12px;
  color:#545454;
  font-family: 'arial', sans-serif;
  font-weight: normal!important;
}
</style>
  </head>
  <body class="" style="font-family: 'arial', sans-serif">
  <?php if(!empty($booking_detail)){ 
        $time = new DateTime($main[0]->booking_time);
         $date = $time->format('d.m.Y');
         $time = $time->format('H:i');
    ?>
    <table style="width:600px;background: #fff;margin:0 auto;font-family: 'arial', sans-serif">
    <?php /* <tr>
        <td style="text-align: center;padding:16px 0px;">
          <img src="<?php echo base_url("assets/frontend/images/".HEADER_LOGO_IMAGE); ?>" style="height: 50px;width: 180px;">
        </td>
      </tr> */?>
      <tr>
        <td>
          <img src="<?php echo base_url("assets/frontend/images/Banner_email.png") ?>" style="width:600px;height:160px;object-fit: contain;">
        </td>
      </tr>
      <tr style="border-bottom:1px solid #e8e8e8;">
        <td style="padding:0px 30px;">
          <p  style="font-size: 16px;color:#333;font-weight:500;line-height: 30px; ">Deine Buchung bei <?php if(!empty($main[0]->business_name)) echo $main[0]->business_name; ?> wurde vom Salon verlegt.<br/>
          Wenn du denkst, dass es sich hierbei um ein Versehen handelt, kontaktiere <?php if(!empty($main[0]->business_name)) echo $main[0]->business_name; ?>. <br/>
          Hier die Details deiner Buchung:<br/>
          </p>
        </td>
      </tr>
      <tr>
        <td style="padding:20px 30px 20px;border-top:1px solid #e8e8e8;">
          <table style="">
            <tr>
              <td style="width:65%;">
                <div class="" style="display: inline-block;">
                  <h3 class="" style="font-size: 16px;margin-bottom:0px;margin-top:0px"><?php echo $booking_detail[0]->first_name; if(!empty($booking_detail[0]->last_name)) " ".$booking_detail[0]->last_name; ?></h3>
                  <p class="" style="font-size: 16px;margin-bottom:5px;margin-top:0px;"><?php if(!empty($main[0]->business_name)) echo $main[0]->business_name; ?></p>
                  <a href="#" class="" style="background:#4BB543;color:#fff;font-size: 16px;border-radius: 4px;font-weight: 400;padding: 5px 10px;text-decoration: none;display: inline-block;margin-top:5px;"><?php echo $this->lang->line('Confirmed'); ?></a>
                </div>
              </td>
              <td>
                <p style="font-size:16px;margin-top:0px;margin-bottom:0px;"><span><?php echo $this->lang->line('By'); ?> : </span> <?php echo $main[0]->first_name; ?></p>
                <p style="font-size:16px;margin-top:0px;margin-bottom:0px;"><span><?php echo $this->lang->line('Date'); ?> : </span> <?php echo $date; ?></p>
                <p style="font-size:16px;margin-top:0px;margin-bottom:0px;"><span><?php echo $this->lang->line('Time'); ?> : </span> <?php echo $time; ?> Uhr</p>
                <?php if(isset($main[0]->id)){ ?>
                <p style="font-size:16px;margin-top:0px;margin-bottom:0px;"><span><?php echo $this->lang->line('Booking_Id'); ?> : </span> <a style="color:#00b3bf;" href="<?php echo base_url('home/booking_redirect/').url_encode($main[0]->id); ?>"><?php if(!empty($main[0]->book_id)) echo $main[0]->book_id; else echo $main[0]->id; ?></a> </p> <?php } ?>
                
                <?php //echo base_url('booking/detail/').url_encode($main[0]->id); ?>
              </td>
            </tr>

          </table>
        </td>
      </tr>
      <tr>
        <td style="padding:20px 0px 0px;border-bottom:1px solid #e8e8e8;">
          <table style="width: 100%;">
            <thead>
              <tr style="border-bottom:  1px solid #e8e8e8;">              
                <th style="font-size:16px;text-align: left;border-bottom:1px solid #e8e8e8;padding-left:30px;"><?php echo $this->lang->line('service'); ?> </th>
                <th style="font-size:16px;text-align: left;border-bottom:1px solid #e8e8e8;">Dauer </th>
                <th style="font-size:16px;text-align: left;border-bottom:1px solid #e8e8e8;"><?php echo $this->lang->line('Discount1'); ?> </th>
                <th style="font-size:16px;text-align: left;border-bottom:1px solid #e8e8e8;padding-right:30px;"><?php echo $this->lang->line('Price1'); ?> </th>
              </tr>
            </thead>
            <body>
            <?php $min=$dis=$prc=0;$ab='';
                foreach($booking_detail as $row){
                  $price = get_booking_detail_price_ab($row->service_id);
                  if (strpos($price, 'ab') !== false) $ab = 'ab ';
              ?>
              <tr style="border-top:1px solid #e8e8e8;">
                <td style="text-align: left;font-size: 16px;color:#666;font-weight: 500;padding-top:15px;padding-left:30px;"><?php echo get_booking_detail_service_name($row->service_id);?></td>
                <td style="text-align: left;font-size: 16px;color:#666;font-weight: 500;padding-top:15px;"><?php echo $row->duration; ?> Min.</td>
                <td style="text-align: left;font-size: 16px;color:#666;font-weight: 500;padding-top:15px;"><?php echo $row->discount_price; ?> €</td>
                <td style="text-align: left;font-size: 16px;color:#666;font-weight: 500;padding-top:15px;"><?php echo $price.$row->price; ?> €</td>
              </tr>
              <?php   $min=$min+$row->duration;
                            $dis=$dis+$row->discount_price;
                            $prc=$prc+$row->price; 
                      } ?>
              <tr style="">
                <td style="text-align: left;font-size: 16px;color:#000;font-weight: 700;padding:15px 0px;border-top:1px solid #e8e8e8;border-bottom:1px solid #e8e8e8;"></td>
                <td style="text-align: left;font-size: 16px;color:#000;font-weight: 700;padding:15px 0px;border-top:1px solid #e8e8e8;border-bottom:1px solid #e8e8e8;"><?php echo $min; ?> Min.</td>
                <td style="text-align: left;font-size: 16px;color:#000;font-weight: 700;padding:15px 0px;border-top:1px solid #e8e8e8;border-bottom:1px solid #e8e8e8;"><?php echo $dis; ?> €</td>
                <td style="text-align: left;font-size: 16px;color:#000;font-weight: 700;padding:15px 0px;border-top:1px solid #e8e8e8;border-bottom:1px solid #e8e8e8;"><?php echo $ab.$prc; ?> €</td>
              </tr>
              <tr style="">
                <td style="text-align: left;font-size: 16px;color:#666;font-weight: 500;padding:15px 0px 10px 30px; "><?php echo $this->lang->line('Discount1'); ?></td>
                <td style="text-align: left;font-size: 16px;color:#666;font-weight: 500;padding:15px 0px 10px; "></td>
                <td style="text-align: left;font-size: 16px;color:#666;font-weight: 500;padding:15px 0px 10px; "></td>
                <td style="text-align: left;font-size: 16px;color:#666;font-weight: 500;padding:15px 0px 10px; "><?php echo $dis; ?> €</td>
              </tr>
              <tr style="">
                <td style="text-align: left;font-size: 16px;color:#000;font-weight: 700;padding:10px 0px 10px 30px; "><?php echo $this->lang->line('Payable_Amount'); ?></td>
                <td style="text-align: left;font-size: 16px;color:#000;font-weight: 700;padding:10px 0px; "></td>
                <td style="text-align: left;font-size: 16px;color:#000;font-weight: 700;padding:10px 0px; "></td>
                <td style="text-align: left;font-size: 16px;color:#000;font-weight: 700;padding:10px 0px; "><?php echo $ab.$prc; ?> €</td>
              </tr>
            </body>
          </table>
        </td>
      </tr>
      <?php if(!empty($main[0]->email_text)){ ?>
      <tr>
        <td style="padding:25px 20px 10px 30px;"><span style="font-weight:500 ;font-size: 16px;"><h4><b>Zusätzliche Information von <?php if(!empty($main[0]->business_name)) echo $main[0]->business_name.':'; ?></b></h4>
          <?php
         echo $main[0]->email_text; ?>
        </td>
      </tr>
    <?php } ?>
    <tr>
        <td style="padding:0px 30px;">
          <p style="font-size: 16px;font-weight:500;margin-top:50px; "><?php echo $this->lang->line('Have_nice_day'); ?></p>
          <span style="font-size: 16px;font-weight:500; "><?php echo $this->lang->line('Regards'); ?>,</span>
          <p style="font-size: 16px;font-weight:500;margin-top:0px;margin-bottom:35px; "><?php echo $this->lang->line('The_Style_Timer_Team'); ?></p>
        </td>
      </tr>
      <tr style="background:#00A3AD;">
        <td style="padding:7px 30px;"><span style="font-weight:300 ;font-size: 14px;color:#fff;">@copyright styletimer <?php echo date('Y'); ?>  |  All rights reserved</td>
      </tr>
       <tr>
       <td style="text-align: center;">
           <img src="<?php echo base_url("assets/frontend/images/logo-email-new.png"); ?>" style="margin-top: 10px;width: 61px;height:70px;object-fit: contain;" alt="">
           <h3 class="" style="margin-bottom:20px;margin-top:10px;font-size:22px;color:#333;">Dein Style in einer App</h3>
            <div style="text-align: center;">
                <a href="<?php echo ANDROID_LINK; ?>" style="display:inline-block;margin-right: 10px;">
                    <img src="<?php echo base_url("assets/frontend/images/google-play-and-app-store-badges.png"); ?>" style="width:140px;height:42px;">
                </a>
                <a href="<?php echo IOS_LINK; ?>" style="display:inline-block;">
                    <img src="<?php echo base_url("assets/frontend/images/google-play-and-app-store-badges1.png"); ?>" style="width:140px;height:42px;">
                </a>
            </div>

            <div style="text-align: center;margin: 16px 0px;">
                <a href="<?php echo FACEBOOK_LINK; ?>" target="_blank" style="display:inline-block;margin-right: 10px;width: 30px;height: 20px;border-radius:4px;padding: 9px 4px;background: #00A3AD;">
                    <img src="<?php echo base_url("assets/frontend/images/footer_social_facebook_icon.png"); ?>" style="width:10px;height: 20px;object-fit: contain;">
                </a>
                <a href="<?php echo INSTAGRAM_LINK; ?>" target="_blank" style="display:inline-block;margin-left:10px;width: 30px;height: 20px;border-radius:4px;padding: 9px 4px;background: #00A3AD;">
                    <img src="<?php echo base_url("assets/frontend/images/footer_social_instagram_icon.png"); ?>" style="width:20px;height: 20px;object-fit: contain;">
                </a>
            </div>
            <p style="font-size: 14px;font-weight:500;color:#333;margin-top:30px;margin-bottom:0px;">styletimer UG (haftungsbeschränkt)
            </p>
            <p style="font-size: 14px;font-weight:500;color:#333;margin-top:0px;margin-bottom:0px;">Quembachallee 35, 35641 Schöffengrund</p>
            <a href="JavaScript:Void(0);" style="color:#333;text-decoration: none;display:block;font-size:14px;font-weight:500;">info@styletimer.de</a>
            <a href="JavaScript:Void(0);" style="color:#333;text-decoration: none;display:block;font-size:14px;font-weight:500;">styletimer.de</a>
        </td>
      </tr>
    </table>
    <?php } ?>
  </body>
</html>
