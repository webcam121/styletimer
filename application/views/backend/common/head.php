<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php if(!empty($title)) echo $title; else echo "Style timer admin"; ?></title>

        <!-- Vendor CSS -->
        <link href="<?php echo admin_assets(); ?>vendors/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
        <link href="<?php echo admin_assets(); ?>vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?php echo admin_assets(); ?>vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?php echo admin_assets(); ?>vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        
        
        <link href="<?php echo admin_assets(); ?>vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        
        <link href="<?php echo admin_assets(); ?>/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
        
        
        <!--Date picker-->
        <link href="<?php echo admin_assets(); ?>/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        
        <!-- CSS -->
        <link href="<?php echo admin_assets(); ?>css/app.min.1.css" rel="stylesheet">
        <link href="<?php echo admin_assets(); ?>css/app.min.2.css" rel="stylesheet">
        
        <link href="<?php echo admin_assets(); ?>css/cutome.css" rel="stylesheet">
        
        
        <script> var base_url = '<?php echo base_url(); ?>'; </script>

        <style>
            #loading_div{
                position: fixed;
                top: 0;
                bottom: 0;
                right: 0;
                left: 0;
                margin: auto;
                z-index: 9;
                width: 200px;
                height: 200px;
            }
            .process_loading{
                width:100%;
                height: 100%;
            }
           .fr-toolbar.fr-desktop.fr-top.fr-basic.fr-sticky-off {
			z-index: 2 !important;
			}


        </style>
    </head>
