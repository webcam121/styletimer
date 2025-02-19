
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <title>StyleTimer</title>

<style>
@import url('https://fonts.googleapis.com/css?family=Poppins');
table{
  font-size: 16px;
  color:#545454;
  font-family: 'arial', sans-serif;
  font-weight: normal!important;
}
</style>
  </head>
  <body class="" style="font-family: 'arial', sans-serif">
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
      <tr>
        <td style="text-align: center;">
           <img src="<?php echo base_url('assets/frontend/images/fireworks.png'); ?>" style="margin-top: 10px;width: 80px;height:80px;object-fit: contain;object-position: top center;">
        </td>
      </tr> 
      <tr>
        <td style="padding:0px 30px;">
          <p  style="font-size: 16px;font-weight:500;line-height: 30px; ">
            <b>Noch ein Klick und es kann losgehen!</b>
          </p>
        </td>
      </tr>      
      <tr>
        <td style="padding:0px 30px;">
          <p style="font-size: 16px;font-weight:500;margin-top:5px; ">Hallo <?php echo $name; ?>,</p>
          <p style="font-size: 16px;font-weight:500;line-height: 20px;">
            herzlich willkommen an Bord bei styletimer! <br/> <br/>
            Wir freuen uns riesig, dass du mit dabei bist! <br/> <br/>
            Um deinen Account zu aktivieren und deinen Salon bei styletimer einzurichten, klicke einfach auf den folgenden Link: <br/> <br/>
          </p>
        <?php if(isset($link)){ ?>
          <a href="<?php echo $link; ?>" class="" style="border-radius: 4px;height:40px;width:170px;line-height: 40px;color: #fff;font-weight:500;font-size: 16px;background:#46a6b1;display: block;text-decoration: none;text-align: center;margin:0 auto;">Account aktivieren</a>
          <?php } ?>
        </td>
      </tr>
      <tr>
        <td style="padding:0px 30px;">
          <p  style="font-size: 16px;font-weight:500;margin-top: 20px;">
            Nachdem du deinen Salon erfolgreich eingerichtet hast, kannst du styletimer 4 Wochen lang völlig unverbindlich und kostenlos testen.
          </p>
        </td>
      </tr>
      <tr>
        <td style="padding:0px 30px;">
          <p style="font-size: 16px;font-weight:500;margin-top:20px; "><?php echo $this->lang->line('Have_nice_day'); ?></p>
          <span style="font-size: 16px;font-weight:500; "><?php echo $this->lang->line('Regards'); ?>,</span>
          <p style="font-size: 16px;font-weight:500;margin-top:0px;margin-bottom:35px; "><?php echo $this->lang->line('The_Style_Timer_Team'); ?></p>
        </td>
      </tr>
      <tr style="background:#00A3AD;">
        <td style="padding:7px 30px;"><span style="font-weight:300 ;font-size: 14px;">@copyright styletimer <?php echo date('Y'); ?>  |  All rights reserved</td>
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
  </body>
</html>





