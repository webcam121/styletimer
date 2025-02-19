<!DOCTYPE html>
    <!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Style timer admin</title>
        
        <!-- Vendor CSS -->
        <link href="<?php echo admin_assets(); ?>vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?php echo admin_assets(); ?>vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
            
        <!-- CSS -->
        <link href="<?php echo admin_assets(); ?>css/app.min.1.css" rel="stylesheet">
        <link href="<?php echo admin_assets(); ?>css/app.min.2.css" rel="stylesheet">
        <link href="<?php echo admin_assets(); ?>css/adm.css" rel="stylesheet">
         <script  src='https://www.google.com/recaptcha/api.js'></script>

    </head>
    
    <body class="login-content">
		
		 
		 
		 
        <!-- Login -->
         <form name="adm-login" id="adm-login" class="adm-form" action="<?php echo base_url('auth/administrator'); ?>" method="post" enctype="multipart/form-data">
			<div class="lc-block toggled" id="l-login">
				<div class="input-group m-b-20">
					<span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
					<div class="fg-line">
						<input name="identity" type="text" class="form-control" placeholder="Username">
					</div>
				</div>
				
				<div class="input-group m-b-20">
					<span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
					<div class="fg-line">
						<input name="password" type="password" class="form-control" placeholder="Password">
					</div>
				</div>
				
				<div class="clearfix"></div>
				
				<div class="checkbox">
					<label>
						<input name="remember" type="checkbox" value="">
						<i class="input-helper"></i>
						Keep me signed in
					</label>
				</div>
                <?php $cap=''; 
                if(!empty($_SESSION['admin_count'])){
                $cap='on'; ?>
               <div style="width: 100%; padding-left: 40px; margin-top:20px;"> 
				<div class="g-recaptcha" id="recaptcha" data-type="image" style="text-align:center" data-sitekey="6Lel0UoeAAAAAJm755rLYXI9_DCfeeKIQ6TqRA9Z">
                    <!-- 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI -->
                </div>
                </div>
                <?php } ?>
                <input type="hidden" id="admin_captch" value="<?php echo $cap; ?>" name="">
				<a id="login-submit" class="btn btn-login btn-danger btn-float"><i class="zmdi zmdi-arrow-forward"></i></a>
			</div>
        </form>
        
      
        
        <div class="adm-msg">
			<?php if(isset($error) && $error!=''){ ?>
			 <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
			 <?php } ?>
		</div> 
        
        <!-- Older IE warning message -->
        <!--[if lt IE 9]>
            <div class="ie-warning">
                <h1 class="c-white">Warning!!</h1>
                <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
                <div class="iew-container">
                    <ul class="iew-download">
                        <li>
                            <a href="http://www.google.com/chrome/">
                                <img src="img/browsers/chrome.png" alt="">
                                <div>Chrome</div>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.mozilla.org/en-US/firefox/new/">
                                <img src="img/browsers/firefox.png" alt="">
                                <div>Firefox</div>
                            </a>
                        </li>
                        <li>
                            <a href="http://www.opera.com">
                                <img src="img/browsers/opera.png" alt="">
                                <div>Opera</div>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.apple.com/safari/">
                                <img src="img/browsers/safari.png" alt="">
                                <div>Safari</div>
                            </a>
                        </li>
                        <li>
                            <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                                <img src="img/browsers/ie.png" alt="">
                                <div>IE (New)</div>
                            </a>
                        </li>
                    </ul>
                </div>
                <p>Sorry for the inconvenience!</p>
            </div>   
        <![endif]-->
        
        <!-- Javascript Libraries -->
        <script src="<?php echo admin_assets(); ?>vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo admin_assets(); ?>vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        
        <script src="<?php echo admin_assets(); ?>vendors/bower_components/Waves/dist/waves.min.js"></script>
        
        
        <!--- Custom Validation --->
        <script src="<?php echo admin_assets(); ?>js/jquery.validate.min.js"></script>
        <script src="<?php echo admin_assets(); ?>js/form-validation.js"></script>
        <!-- Placeholder for IE9 -->
        <!--[if IE 9 ]>
            <script src="vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
        <![endif]-->
        
        <script src="<?php echo admin_assets(); ?>js/functions.js"></script>
        
        <script type="text/javascript">
            $(document).on('submit','#adm-login',function(){
                if($("#admin_captch").val()=='on'){
                        var $captcha = $('#recaptcha'),
                          response = grecaptcha.getResponse();
                          console.log(response);
                          if (response.length === 0) 
                            {
                                return false;       
                            } 
                }
            });
        </script>
    </body>
</html>
