<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>SalonERP</title>
        <style type="text/css">
            body {font-size:14px;font-family: arial, sans-serif;color:#000000;margin: 0; padding: 0; min-width: 100%!important;}
            img{max-width: 100%;}
            a{text-decoration: none;outline:none;color:inherit;}
            a img{border:none;outline:none;}
            p{ margin-top:5px;margin-bottom:5px;}
            @media only screen and (min-device-width: 601px) {
                .content {width: 600px !important;}
            }
        </style>
    </head>
    <body bgcolor="#eaecf0" style="font-size: 14px;font-family: arial, sans-serif;margin: 0;padding: 0;color: #20232a;min-width: 100%!important;">
        <table width="100%" bgcolor="#fefefe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
        <!--[if (gte mso 9)|(IE)]>
        <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <![endif]-->                                   
                    <table width="600" class="content" bgcolor="#eaecf0" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%;max-width: 600px;">
                        <tr>
                            <td class="content" align="center" style="background-color:#333333; padding: 25px;">
                                <img src="http://34.223.228.22/obsoleum/assets/images/o_logo.png">
                              
                            </td>
                        </tr>
                       <tr><td><br></td></tr>
                        <tr>
                            <td bgcolor="#eaecf0" style="padding:0 20px 20px 20px;">
                                <table width="100%" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" style="padding: 25px 30px; border-bottom: 1px solid #e3e3e3; border-radius:10px;">
                                    <tr>
                                        <td style="color:#010101;">
                                        <h5 style="font-size:18px;margin:0;">Hi, <?php echo $first_name." ".$last_name; ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:17px;line-height:1.6em;">
                                            <p>We have invite you to join our business 
                                            just need to click this link and complete your signup.
                                           
                                            <br> 
                                             For join us : 
                                             <a style="font-weight: 900;color: blue;" href="<?php echo $subdomain.'/user/joinstylist/'. $id .'/'. $activation;  ?>">Join Us</a>
                                             <?php //echo $subdomain.'/user/joinstylist/'. $id .'/'. $activation; ?>
                                             </p>
                                           
                                        </td>
                                    </tr>
                                   

                                   
                                    <tr>
                                        <td style="padding-top:17px;line-height:1.6em;">
                                            <p> Thanks, <br/>
                                             The Obsoleum Team </P>

                                        </td>
                                    </tr>
                                    
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#333333" style="padding: 30px 10px;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                     <tr>
                                        <td align="center" style="font-size:13px;color:#999;">
                                            &copy;<?php echo date('Y'); ?> Obsoleum | All Rights Reserved.
                                        </td>
                                    </tr>
                                   
                                </table>
                            </td>
                        </tr>
                       
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                </td>
            </tr>
        </table>
        <![endif]-->
                </td>
            </tr>
        </table>
    </body>
</html>
