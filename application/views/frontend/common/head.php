<!doctype html>
<html lang="de">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0,shrink-to-fit=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="Cache-Control" content="max-age=0, must-revalidate"/>

    <meta name="apple-itunes-app" content="app-id=1619747854">
    <meta name="google-play-app" content="app-id=com.si.styletimer">

    <?php if (ENVIRONMENT != 'production') {?>
      <meta name="robots" content="noindex,nofollow">
    <?php } ?>
   
    <meta name="google-site-verification" content="Y_N3LN8OkdkcQ_j9snKp9aQKvJnra5Qa1p5kkE6dV-w" />
    <meta property="og:title" content="<?php if(!empty($title)) echo $title; else echo $this->lang->line('hometitle'); ?>"/> 
    <meta property="og:description" content="<?php if(!empty($share_desc)) echo strip_tags($share_desc); else echo 'Kontaktiere uns, gerne helfen wir dir bei deinen Fragen weiter - styletimer' ?>" />
    <meta property="og:url" content="<?php if(!empty($share_url)) echo $share_url; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?php if(!empty($share_img)) echo $share_img; ?>"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/mani_layout.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/mani_frontend_style.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/mani_backend_style.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/vinu_style.css'); ?>?v=1.3">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/mani_responsive.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/dropdown-enhancement.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/fontawesome-all.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/font-awesome-animation.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/slick.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/slick-theme.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/rippler.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/gijgo.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/bootstrap-clockpicker.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/jquery.smartbanner.css');?>" type="text/css" media="screen">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" />
    <!-- 
    <link rel="stylesheet" href="<?php //echo base_url('assets/frontend/css/bootstrap-datetimepicker.min.css');?>"> -->
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/custome/userFormValidation.css'); ?>">
    <link rel="icon" href="<?php echo base_url('assets/frontend/images/fav.png'); ?>" type="image/png" sizes="16x16"> 
    <?php if($this->session->userdata('access')=='marchant'){ 
      $this->load->view('frontend/common/editer_css');
    } ?>

    <title>
      <?php if(!empty($static_content)){ echo $static_content->title;}
      else if(!empty($title)){ echo $title ;}else{ echo 'styletimer - Buchungsverwaltung'; }  ?>
    </title>
    <script>
        var base_url = '<?php echo base_url();?>';
        var sessionAccess = '<?php if(!empty($this->session->userdata("access"))) echo $this->session->userdata("access"); ?>';
        var urlsehgment2 = 'tes';
        var seg3= '<?php echo $this->uri->segment(3);?>';
        var iq_api_key = '<?php echo LOCATIONAPIKEY;?>';
	var env = '<?php echo $_SERVER['CI_ENV'] ;?>';
        console.log('environmnt', env, base_url)
        console.log("location ------------key ",iq_api_key)
        var utcTimeZone ='0530';
    </script>
    <?php $this->load->view('frontend/common/message_de'); ?>

    <?php if (ENVIRONMENT == 'production') {?>
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162701065-1"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-162701065-1');
      </script>
    <?php } else { ?>
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-24Z8YEMETD"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-24Z8YEMETD');
      </script>
    <?php } ?>
  </head>
   <body id="chg_hover" class="<?php if(empty($this->uri->segment(1)) || $this->uri->segment(1)=='home' || $this->uri->segment(2)=='thankyou') echo " "; else echo "bglightgreen1"; ?>" >

