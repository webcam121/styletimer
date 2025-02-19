<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*****security functions**************************************/
 
 /**
 * Check if user is logged in
 * @return boolean
 */
	if( ! function_exists('is_logged_in'))
	{
		function is_loged_in()
		{  
		    return (isset($_SESSION['user_id']) && $_SESSION['user_id']) ? TRUE : FALSE;		
		}
	}
 
 
	
function phpmail($to,$subject,$message)
{
		
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        $headers .= 'From: <info@shantiinfotech.com>' . "\r\n";
                
        return  mail($to,$subject,$message,$headers);
}

// mail function

function emailsend($to,$subject,$msg,$from,$file='',$filename='',$content='',$type='',$auto='')
{
	$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => getenv('SMTP_HOST'),
		'smtp_port' => getenv('SMTP_PORT'),
		'smtp_user' => getenv('SMTP_USER'),
		'smtp_pass' => getenv('SMTP_PASS'),
		'mailtype' => 'html',
		'crlf' => "\r\n",
		'newline' => "\r\n"
	);

	$CI =& get_instance();
	$CI->load->library('email', $config);
	$CI->email->initialize($config);
	$CI->email->set_mailtype("html");
	$CI->email->set_newline("\r\n");
	$CI->email->from(ADMIN_EMAIL_SEND, $from);
	$CI->email->to( $to );
	$CI->email->subject($subject);
	$CI->email->message($msg);
		
	if($type=='pdf') {
		if ($auto) $CI->email->attach($file, 'application/pdf', $filename . ".pdf", false);
	  	else $CI->email->attach($file, 'application/pdf', "Pdf File " . date("m-d H-i-s") . ".pdf", false);
	} else
	{
		$CI->email->attach( $file);
	}
	$result = $CI->email->send();
	if ($result) {
		return true;
	} else {
		return false;
	}    
}

 
function getimge_url($url,$imgename,$return_format=""){ 
	   $exntstin = explode('.',$imgename);
	   $ext = $exntstin[count($exntstin)-1];
	   if($ext=='webp'){
		  if($return_format=='webp')
	         return base_url($url.$imgename);
	      else   
	         return base_url($url.$imgename.'.png');
	    }else{
          if($return_format=='webp')
	         return base_url($url.$imgename.'.webp');
	      else   
	         return base_url($url.$imgename);			
		}
	
	}
