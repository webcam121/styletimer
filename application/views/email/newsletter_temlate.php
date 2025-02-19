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
  font-size: 12px;
  color:#545454;
  font-family: 'arial', sans-serif;
  font-weight: normal!important;
}
</style>
  </head>
  <body class="" style="font-family: 'arial', sans-serif">
    <table style="width:600px;background: #fff;border-top: 6px solid #00b3bf;margin:0 auto;padding:20px 30px;font-family: 'arial', sans-serif">
      <tr>
        <td style="disply:flex:align-items-center;justify-content:center;">
          <img  src="<?php  if(!empty($logo)) echo $logo; else echo base_url("assets/frontend/images/".HEADER_LOGO_IMAGE); ?>" style="max-height: 250px;max-width: 700px; margin-bottom: 20px;">
        </td>
      </tr>
     
     
      <tr>
        <td>
			     <p style="font-size: 16px;font-weight:500;line-height: 20px;"><?php if(!empty($message)) echo $message; ?></p>
          <p></p>
        </td>
      </tr>
      
      <tr>
        <td><p style="margin-top: 20px;color: #545454;font-size: 13px!important;font-family:  'arial', sans-serif"><?php if(!empty($footer)) echo $footer; ?></p>
      </tr>
      <tr>
        <td><p style="margin-top: 20px;color: #ccc;font-size: 13px!important;font-family:  'arial', sans-serif">Sie erhalten diese E-Mail, weil Sie Anbietern auf styletimer gestattet haben, Sie über Terminangelegenheiten, Services
und Angebote zu informieren. Diese Option können Sie jederzeit in ihrem Profil bei <a href="<?php echo base_url('?page=login'); ?>">styletimer</a> ändern.</p>
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
