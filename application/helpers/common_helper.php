<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * An ad network for publishers and advertisers
 *
 * @package		CodeIgniter
 * @author		Shantiinfotech
 * @copyright	Copyright (c) 2008 - 2019, Shantiinfotech
 * @link		https://www.shantiinfotech.com
 * @since		Version 1.0
 * @filesource
 */

/**
 * Date Displayer - Takes the date and makes it in a standard design
 *
 * @access	public
 * 
 */
 

 
 
 // some common functions
	function assets($path = ""){
		return base_url("assets")."/$path";
	}
function price_formate($string="",$en=""){
	
	//echo $string;
	if(!empty($en)){
	  $data=array(".");
		$replace=array(",");
		$string=(string)$string;
		//var_dump($string); die;

		$retudata=str_replace($replace,$data,$string);
    }else{
		 $retudata=number_format($string, ((int) $string == $string ? 0 : 2), ',', '.');
		}
    
	return trim($retudata);
	
	}
 
  function get_google_login_url(){
  	$CI =& get_instance();
 	return $CI->google->loginURL();
  }
   function get_facebook_login_url(){
  	$CI =& get_instance();
 	return  $CI->facebook->login_url(); 
  }
		
	function admin_assets($path = ""){
		return base_url("assets/backend")."/$path";
	}
	
	function frontend_assets($path = ""){
		return base_url("assets/frontend")."/$path";
	}
	
	function admin_url($path = ""){
		return base_url("backend")."/$path";
	}
	
	function dashboard_url($path = ""){
		return base_url("dashboard")."/$path";
	}
    
    function load_views($views=null, $data=null){
		$ci =& get_instance();
		if($views)$ci->load->view("$views", $data);
	}
    
    function views($views=null, $data=null){
		$ci =& get_instance();
		if($views)$ci->load->view("frontend/$views", $data);
	}
	
	function dashboard_views($views=null, $data=null){
		$ci =& get_instance();
		if($views)$ci->load->view("dashboard/$views", $data);
	}

	function admin_views($views=null, $data=null){
		$ci =& get_instance();
		if($views)$ci->load->view("backend/$views", $data);
	}
	
 
 
 function create_slug($string,$id=""){
		$string = replace_spec_char($string);
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		// $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		$string = strtolower($string); // Convert to lowercase
        $checkString = $string;  
        
    $CI =& get_instance();
  
    $j=1;
    for($i=0;$i<$j;$i++){
       $CI->db->select('id');
       if(!empty($id))
          $CI->db->where(array('slug'=>$checkString,'access'=>'marchant','id !='=>$id));
       else
          $CI->db->where(array('slug'=>$checkString,'access'=>'marchant'));
         
       $query = $CI->db->get('st_users');
       $result =  $query->row('id');
       
       if(!empty($result)) 
          {
		   $checkString = $string.'-'.$j;	  
		    $j++;
		  }
	  else $string = $checkString;	    		  
   }
    
    return $string;
        
        //return $string;
	}


	function replace_spec_char($subject) {
    $char_map = array(
        "ъ" => "-", "ь" => "-", "Ъ" => "-", "Ь" => "-",
        "А" => "A", "Ă" => "A", "Ǎ" => "A", "Ą" => "A", "À" => "A", "Ã" => "A", "Á" => "A", "Æ" => "A", "Â" => "A", "Å" => "A", "Ǻ" => "A", "Ā" => "A", "א" => "A",
        "Б" => "B", "ב" => "B", "Þ" => "B",
        "Ĉ" => "C", "Ć" => "C", "Ç" => "C", "Ц" => "C", "צ" => "C", "Ċ" => "C", "Č" => "C", "©" => "C", "ץ" => "C",
        "Д" => "D", "Ď" => "D", "Đ" => "D", "ד" => "D", "Ð" => "D",
        "È" => "E", "Ę" => "E", "É" => "E", "Ë" => "E", "Ê" => "E", "Е" => "E", "Ē" => "E", "Ė" => "E", "Ě" => "E", "Ĕ" => "E", "Є" => "E", "Ə" => "E", "ע" => "E",
        "Ф" => "F", "Ƒ" => "F",
        "Ğ" => "G", "Ġ" => "G", "Ģ" => "G", "Ĝ" => "G", "Г" => "G", "ג" => "G", "Ґ" => "G",
        "ח" => "H", "Ħ" => "H", "Х" => "H", "Ĥ" => "H", "ה" => "H",
        "I" => "I", "Ï" => "I", "Î" => "I", "Í" => "I", "Ì" => "I", "Į" => "I", "Ĭ" => "I", "I" => "I", "И" => "I", "Ĩ" => "I", "Ǐ" => "I", "י" => "I", "Ї" => "I", "Ī" => "I", "І" => "I",
        "Й" => "J", "Ĵ" => "J",
        "ĸ" => "K", "כ" => "K", "Ķ" => "K", "К" => "K", "ך" => "K",
        "Ł" => "L", "Ŀ" => "L", "Л" => "L", "Ļ" => "L", "Ĺ" => "L", "Ľ" => "L", "ל" => "L",
        "מ" => "M", "М" => "M", "ם" => "M",
        "Ñ" => "N", "Ń" => "N", "Н" => "N", "Ņ" => "N", "ן" => "N", "Ŋ" => "N", "נ" => "N", "ŉ" => "N", "Ň" => "N",
        "Ø" => "O", "Ó" => "O", "Ò" => "O", "Ô" => "O", "Õ" => "O", "О" => "O", "Ő" => "O", "Ŏ" => "O", "Ō" => "O", "Ǿ" => "O", "Ǒ" => "O", "Ơ" => "O",
        "פ" => "P", "ף" => "P", "П" => "P",
        "ק" => "Q",
        "Ŕ" => "R", "Ř" => "R", "Ŗ" => "R", "ר" => "R", "Р" => "R", "®" => "R",
        "Ş" => "S", "Ś" => "S", "Ș" => "S", "Š" => "S", "С" => "S", "Ŝ" => "S", "ס" => "S",
        "Т" => "T", "Ț" => "T", "ט" => "T", "Ŧ" => "T", "ת" => "T", "Ť" => "T", "Ţ" => "T",
        "Ù" => "U", "Û" => "U", "Ú" => "U", "Ū" => "U", "У" => "U", "Ũ" => "U", "Ư" => "U", "Ǔ" => "U", "Ų" => "U", "Ŭ" => "U", "Ů" => "U", "Ű" => "U", "Ǖ" => "U", "Ǜ" => "U", "Ǚ" => "U", "Ǘ" => "U",
        "В" => "V", "ו" => "V",
        "Ý" => "Y", "Ы" => "Y", "Ŷ" => "Y", "Ÿ" => "Y",
        "Ź" => "Z", "Ž" => "Z", "Ż" => "Z", "З" => "Z", "ז" => "Z",
        "а" => "a", "ă" => "a", "ǎ" => "a", "ą" => "a", "à" => "a", "ã" => "a", "á" => "a", "æ" => "a", "â" => "a", "å" => "a", "ǻ" => "a", "ā" => "a", "א" => "a",
        "б" => "b", "ב" => "b", "þ" => "b",
        "ĉ" => "c", "ć" => "c", "ç" => "c", "ц" => "c", "צ" => "c", "ċ" => "c", "č" => "c", "©" => "c", "ץ" => "c",
        "Ч" => "ch", "ч" => "ch",
        "д" => "d", "ď" => "d", "đ" => "d", "ד" => "d", "ð" => "d",
        "è" => "e", "ę" => "e", "é" => "e", "ë" => "e", "ê" => "e", "е" => "e", "ē" => "e", "ė" => "e", "ě" => "e", "ĕ" => "e", "є" => "e", "ə" => "e", "ע" => "e",
        "ф" => "f", "ƒ" => "f",
        "ğ" => "g", "ġ" => "g", "ģ" => "g", "ĝ" => "g", "г" => "g", "ג" => "g", "ґ" => "g",
        "ח" => "h", "ħ" => "h", "х" => "h", "ĥ" => "h", "ה" => "h",
        "i" => "i", "ï" => "i", "î" => "i", "í" => "i", "ì" => "i", "į" => "i", "ĭ" => "i", "ı" => "i", "и" => "i", "ĩ" => "i", "ǐ" => "i", "י" => "i", "ї" => "i", "ī" => "i", "і" => "i",
        "й" => "j", "Й" => "j", "Ĵ" => "j", "ĵ" => "j",
        "ĸ" => "k", "כ" => "k", "ķ" => "k", "к" => "k", "ך" => "k",
        "ł" => "l", "ŀ" => "l", "л" => "l", "ļ" => "l", "ĺ" => "l", "ľ" => "l", "ל" => "l",
        "מ" => "m", "м" => "m", "ם" => "m",
        "ñ" => "n", "ń" => "n", "н" => "n", "ņ" => "n", "ן" => "n", "ŋ" => "n", "נ" => "n", "ŉ" => "n", "ň" => "n",
        "ø" => "o", "ó" => "o", "ò" => "o", "ô" => "o", "õ" => "o", "о" => "o", "ő" => "o", "ŏ" => "o", "ō" => "o", "ǿ" => "o", "ǒ" => "o", "ơ" => "o",
        "פ" => "p", "ף" => "p", "п" => "p",
        "ק" => "q",
        "ŕ" => "r", "ř" => "r", "ŗ" => "r", "ר" => "r", "р" => "r", "®" => "r",
        "ş" => "s", "ś" => "s", "ș" => "s", "š" => "s", "с" => "s", "ŝ" => "s", "ס" => "s",
        "т" => "t", "ț" => "t", "ט" => "t", "ŧ" => "t", "ת" => "t", "ť" => "t", "ţ" => "t",
        "ù" => "u", "û" => "u", "ú" => "u", "ū" => "u", "у" => "u", "ũ" => "u", "ư" => "u", "ǔ" => "u", "ų" => "u", "ŭ" => "u", "ů" => "u", "ű" => "u", "ǖ" => "u", "ǜ" => "u", "ǚ" => "u", "ǘ" => "u",
        "в" => "v", "ו" => "v",
        "ý" => "y", "ы" => "y", "ŷ" => "y", "ÿ" => "y",
        "ź" => "z", "ž" => "z", "ż" => "z", "з" => "z", "ז" => "z", "ſ" => "z",
        "™" => "tm",
        "@" => "at",
        "Ä" => "ae", "Ǽ" => "ae", "ä" => "ae", "æ" => "ae", "ǽ" => "ae",
        "ĳ" => "ij", "Ĳ" => "ij",
        "я" => "ja", "Я" => "ja",
        "Э" => "je", "э" => "je",
        "ё" => "jo", "Ё" => "jo",
        "ю" => "ju", "Ю" => "ju",
        "œ" => "oe", "Œ" => "oe", "ö" => "oe", "Ö" => "oe","ü" =>"ue","Ü" =>"ue",
        "щ" => "sch", "Щ" => "sch",
        "ш" => "sh", "Ш" => "sh",
        "ß" => "ss",
        "Ü" => "ue",
        "Ж" => "zh", "ж" => "zh",
    );
    return strtr($subject, $char_map);
}




 function create_slug_without_db($string){
	 //echo $string;
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		$string = strtolower($string); // Convert to lowercase    
        
        return $string;
        
        //return $string;
	}
	
 /******************************************************************************************************************************/
 

  // For create 16 digit random genrate string
 function generateRandomString($length = 16) {
	  $characters = '0123456789';
	  $randomString = '';
	  for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	  }
	  return $randomString;
 }

 function date_to_path($date){
	return date("Y/m/d", strtotime($date))."/";
 }
  function array_insert(&$array, $stack){
	foreach($stack as $key=>$val)$array[$key] = $val;
	return $array;
 }
 function current_uri()
{
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}
function isempty(&$var) {
    return (empty($var) && $var!='0');
}
function start_of_week(){
	$dt_min = new DateTime("last friday"); // Edit
	$start = $dt_min->modify('+1 day'); // Edit
	return $start->format('Y-m-d');
}
function end_of_week(){
	$dt_max = new DateTime("last friday"); // Edit
	$end = $dt_max->modify('+6 day'); // Edit
	return $end->format('Y-m-d');
}
function array_except(&$array, $key){
	if(is_array($key)){
		foreach($key as $k)array_except($array, $k);
	}
	else	unset($array[$key]);
	return $array;
}
 function formatcurrency($floatcurr, $curr = "USD"){
	$currencies['ARS'] = array(2,',','.');          //  Argentine Peso
	$currencies['AMD'] = array(2,'.',',');          //  Armenian Dram
	$currencies['AWG'] = array(2,'.',',');          //  Aruban Guilder
	$currencies['AUD'] = array(2,'.',' ');          //  Australian Dollar
	$currencies['BSD'] = array(2,'.',',');          //  Bahamian Dollar
	$currencies['BHD'] = array(3,'.',',');          //  Bahraini Dinar
	$currencies['BDT'] = array(2,'.',',');          //  Bangladesh, Taka
	$currencies['BZD'] = array(2,'.',',');          //  Belize Dollar
	$currencies['BMD'] = array(2,'.',',');          //  Bermudian Dollar
	$currencies['BOB'] = array(2,'.',',');          //  Bolivia, Boliviano
	$currencies['BAM'] = array(2,'.',',');          //  Bosnia and Herzegovina, Convertible Marks
	$currencies['BWP'] = array(2,'.',',');          //  Botswana, Pula
	$currencies['BRL'] = array(2,',','.');          //  Brazilian Real
	$currencies['BND'] = array(2,'.',',');          //  Brunei Dollar
	$currencies['CAD'] = array(2,'.',',');          //  Canadian Dollar
	$currencies['KYD'] = array(2,'.',',');          //  Cayman Islands Dollar
	$currencies['CLP'] = array(0,'','.');           //  Chilean Peso
	$currencies['CNY'] = array(2,'.',',');          //  China Yuan Renminbi
	$currencies['COP'] = array(2,',','.');          //  Colombian Peso
	$currencies['CRC'] = array(2,',','.');          //  Costa Rican Colon
	$currencies['HRK'] = array(2,',','.');          //  Croatian Kuna
	$currencies['CUC'] = array(2,'.',',');          //  Cuban Convertible Peso
	$currencies['CUP'] = array(2,'.',',');          //  Cuban Peso
	$currencies['CYP'] = array(2,'.',',');          //  Cyprus Pound
	$currencies['CZK'] = array(2,'.',',');          //  Czech Koruna
	$currencies['DKK'] = array(2,',','.');          //  Danish Krone
	$currencies['DOP'] = array(2,'.',',');          //  Dominican Peso
	$currencies['XCD'] = array(2,'.',',');          //  East Caribbean Dollar
	$currencies['EGP'] = array(2,'.',',');          //  Egyptian Pound
	$currencies['SVC'] = array(2,'.',',');          //  El Salvador Colon
	$currencies['ATS'] = array(2,',','.');          //  Euro
	$currencies['BEF'] = array(2,',','.');          //  Euro
	$currencies['DEM'] = array(2,',','.');          //  Euro
	$currencies['EEK'] = array(2,',','.');          //  Euro
	$currencies['ESP'] = array(2,',','.');          //  Euro
	$currencies['EUR'] = array(2,',','.');          //  Euro
	$currencies['FIM'] = array(2,',','.');          //  Euro
	$currencies['FRF'] = array(2,',','.');          //  Euro
	$currencies['GRD'] = array(2,',','.');          //  Euro
	$currencies['IEP'] = array(2,',','.');          //  Euro
	$currencies['ITL'] = array(2,',','.');          //  Euro
	$currencies['LUF'] = array(2,',','.');          //  Euro
	$currencies['NLG'] = array(2,',','.');          //  Euro
	$currencies['PTE'] = array(2,',','.');          //  Euro
	$currencies['GHC'] = array(2,'.',',');          //  Ghana, Cedi
	$currencies['GIP'] = array(2,'.',',');          //  Gibraltar Pound
	$currencies['GTQ'] = array(2,'.',',');          //  Guatemala, Quetzal
	$currencies['HNL'] = array(2,'.',',');          //  Honduras, Lempira
	$currencies['HKD'] = array(2,'.',',');          //  Hong Kong Dollar
	$currencies['HUF'] = array(0,'','.');           //  Hungary, Forint
	$currencies['ISK'] = array(0,'','.');           //  Iceland Krona
	$currencies['INR'] = array(2,'.',',');          //  Indian Rupee
	$currencies['IDR'] = array(2,',','.');          //  Indonesia, Rupiah
	$currencies['IRR'] = array(2,'.',',');          //  Iranian Rial
	$currencies['JMD'] = array(2,'.',',');          //  Jamaican Dollar
	$currencies['JPY'] = array(0,'',',');           //  Japan, Yen
	$currencies['JOD'] = array(3,'.',',');          //  Jordanian Dinar
	$currencies['KES'] = array(2,'.',',');          //  Kenyan Shilling
	$currencies['KWD'] = array(3,'.',',');          //  Kuwaiti Dinar
	$currencies['LVL'] = array(2,'.',',');          //  Latvian Lats
	$currencies['LBP'] = array(0,'',' ');           //  Lebanese Pound
	$currencies['LTL'] = array(2,',',' ');          //  Lithuanian Litas
	$currencies['MKD'] = array(2,'.',',');          //  Macedonia, Denar
	$currencies['MYR'] = array(2,'.',',');          //  Malaysian Ringgit
	$currencies['MTL'] = array(2,'.',',');          //  Maltese Lira
	$currencies['MUR'] = array(0,'',',');           //  Mauritius Rupee
	$currencies['MXN'] = array(2,'.',',');          //  Mexican Peso
	$currencies['MZM'] = array(2,',','.');          //  Mozambique Metical
	$currencies['NPR'] = array(2,'.',',');          //  Nepalese Rupee
	$currencies['ANG'] = array(2,'.',',');          //  Netherlands Antillian Guilder
	$currencies['ILS'] = array(2,'.',',');          //  New Israeli Shekel
	$currencies['TRY'] = array(2,'.',',');          //  New Turkish Lira
	$currencies['NZD'] = array(2,'.',',');          //  New Zealand Dollar
	$currencies['NOK'] = array(2,',','.');          //  Norwegian Krone
	$currencies['PKR'] = array(2,'.',',');          //  Pakistan Rupee
	$currencies['PEN'] = array(2,'.',',');          //  Peru, Nuevo Sol
	$currencies['UYU'] = array(2,',','.');          //  Peso Uruguayo
	$currencies['PHP'] = array(2,'.',',');          //  Philippine Peso
	$currencies['PLN'] = array(2,'.',' ');          //  Poland, Zloty
	$currencies['GBP'] = array(2,'.',',');          //  Pound Sterling
	$currencies['OMR'] = array(3,'.',',');          //  Rial Omani
	$currencies['RON'] = array(2,',','.');          //  Romania, New Leu
	$currencies['ROL'] = array(2,',','.');          //  Romania, Old Leu
	$currencies['RUB'] = array(2,',','.');          //  Russian Ruble
	$currencies['SAR'] = array(2,'.',',');          //  Saudi Riyal
	$currencies['SGD'] = array(2,'.',',');          //  Singapore Dollar
	$currencies['SKK'] = array(2,',',' ');          //  Slovak Koruna
	$currencies['SIT'] = array(2,',','.');          //  Slovenia, Tolar
	$currencies['ZAR'] = array(2,'.',' ');          //  South Africa, Rand
	$currencies['KRW'] = array(0,'',',');           //  South Korea, Won
	$currencies['SZL'] = array(2,'.',', ');         //  Swaziland, Lilangeni
	$currencies['SEK'] = array(2,',','.');          //  Swedish Krona
	$currencies['CHF'] = array(2,'.','\'');         //  Swiss Franc
	$currencies['TZS'] = array(2,'.',',');          //  Tanzanian Shilling
	$currencies['THB'] = array(2,'.',',');          //  Thailand, Baht
	$currencies['TOP'] = array(2,'.',',');          //  Tonga, Paanga
	$currencies['$'] = array(0,'.',',');          //  UAE Dirham
	$currencies['UAH'] = array(2,',',' ');          //  Ukraine, Hryvnia
	$currencies['USD'] = array(2,'.',',');          //  US Dollar
	$currencies['VUV'] = array(0,'',',');           //  Vanuatu, Vatu
	$currencies['VEF'] = array(2,',','.');          //  Venezuela Bolivares Fuertes
	$currencies['VEB'] = array(2,',','.');          //  Venezuela, Bolivar
	$currencies['VND'] = array(0,'','.');           //  Viet Nam, Dong
	$currencies['ZWD'] = array(2,'.',' ');          //  Zimbabwe Dollar
	return number_format($floatcurr,$currencies[$curr][0],$currencies[$curr][1],$currencies[$curr][2]);
}
function get_https_content($url=NULL,$method="GET"){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0');
	curl_setopt($ch, CURLOPT_URL,$url);
	return curl_exec($ch);
}
function shortenNumber($number) {
    return ($number >= 1000)?round($number/1000, 1) . "K":$number;
}

function dateSlug($date){
	$removespace = explode(" ", $date);
	$newdate = explode("-", $removespace[0]);
	return $newdate[0].'/'.$newdate[1].'/'.$newdate[2].'/';
}

function correctSlug($raw){
	echo '<meta charset="utf-8">';
	return urldecode($raw);
}

function rightDate($date, $type){
	if($type == "short")$newdate = date("F j, Y", strtotime($date));
	else $newdate = date("F j, Y, g:i a", strtotime($date));
	return $newdate;
}

function numberdate($date)
{
	$date = explode(" ", $date);

	switch ($date[1]) {
	    case "January,":
	        $date[1] = '01';
	        break;
	    case "February,":
	        $date[1] = '02';
	        break;
		case "March,":
	        $date[1] = '03';
	        break;
		case "April,":
	        $date[1] = '04';
	        break;
		case "May,":
	        $date[1] = '05';
	        break;
		case "June,":
	        $date[1] = '06';
	        break;
		case "July,":
	        $date[1] = '07';
	        break;
		case "August,":
	        $date[1] = '08';
	        break;
		case "Septemeber,":
	        $date[1] = '09';
	        break;
		case "October,":
	        $date[1] = '10';
	        break;
		case "November,":
	        $date[1] = '11';
	        break;
		case "December,":
	        $date[1] = '12';
	        break;
		}
	return $date[2]."-".$date[1]."-".$date[0];
}

function sortGenerator($dir, $thiss, $current, $page)
{
	if($this == $current)
	{
		if($dir == "desc") $string = 'class="downArrow" href="' . base_url("admin/") . $page . '/listing?sortby=' . $thiss . '&sortdir=asc' . '"';
		if($dir == "asc") $string = 'class="upArrow" href="' . base_url("admin/") . $page . '/listing?sortby=' . $thiss . '&sortdir=desc' . '"';
	}
	else $string = 'href="' . base_url("admin/") . $page . '/listing?sortby=' . $thiss . '&sortdir=desc' . '"';

	return $string;
}
function number_to_persian($string) {
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $num = range(0, 9);
    return str_replace($num, $persian, $string);
}
function get_sortlink($str, $db_column){
	$link = "<a href='";
	$link.="?sort_by=$db_column&sort_order=";
	$link.=(isset($_GET['sort_order']) && isset($_GET['sort_by']) && $_GET['sort_order']=="asc" && $_GET['sort_by']==$db_column)?"desc":"asc";
	foreach($_GET as $key=>$val)if($key!='sort_by' && $key!='sort_order')$link.="&$key=$val";
	$link.="'". ((isset($_GET['sort_order']) && isset($_GET['sort_by']) && $_GET['sort_by'] == $db_column)?" class='{$_GET['sort_order']}'":"") .">$str</a>";
	return $link;
}


function breakDateFolder($date, $root)
{
	$removespace = explode(" ", $date);
	$newdate = explode("-", $removespace[0]);
	return 'assets/'.$root.'/'.$newdate[0].'/'.$newdate[1].'/'.$newdate[2].'/';
}

function createDateFolder($date, $root)
{
	$removespace = explode(" ", $date);
	$newdate = explode("-", $removespace[0]);

	if (!is_dir('assets/'.$root.'/'.$newdate[0]))
	{
		@mkdir('assets/'.$root.'/'.$newdate[0], 0755, TRUE);
	}

	if (!is_dir('assets/'.$root.'/'.$newdate[0].'/'.$newdate[1]))
	{
		@mkdir('assets/'.$root.'/'.$newdate[0].'/'.$newdate[1], 0755, TRUE);
	}

	if (!is_dir('assets/'.$root.'/'.$newdate[0].'/'.$newdate[1].'/'.$newdate[2]))
	{
		@mkdir('assets/'.$root.'/'.$newdate[0].'/'.$newdate[1].'/'.$newdate[2], 0755, TRUE);
	}

	return 'assets/'.$root.'/'.$newdate[0].'/'.$newdate[1].'/'.$newdate[2].'/';
}
function remove_tag($html){
	$html = preg_replace('/<p.*?>/i', '', $html);
	$html = preg_replace('/<\/p>/i', "\n\n", $html);
	$html = preg_replace('/<.*br.*?>/i', "\n", $html);
	return str_ireplace("&nbsp;","",$html);
}

function ul_to_array($ul){
	$ul = preg_replace('/<ul.*?>/i', '', $ul);
	$ul = preg_replace('/<\/ul>/i', "", $ul);
	$ul = preg_replace('/<li.*?>/i', "", $ul);
	$ul = str_ireplace(PHP_EOL, '', $ul);
	$ul = preg_replace('/<\/li>/i', " ||| ", $ul);
	return explode(" ||| ", trim($ul));
}

function get_video_thumb($url){
	 $image_url = parse_url($url);
	if($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com'){
		$array = explode("&", $image_url['query']);
		return "http://img.youtube.com/vi/".substr($array[0], 2)."/0.jpg";
	}
	else if($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com'){
		$headers = get_headers($url);
		if(strpos($headers[0],'200') !== false){
			$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".substr($image_url['path'], 1).".php"));
			return $hash[0]["thumbnail_small"];
		}
	}
	return NULL;
}

function encryptPass($password){
	return hash("sha256", $password);
	//return md5($password);
	$options = array('cost' => 9,'salt' => 'f0¾<2R¿xà![2=9¢L-!ƒ');
	$new_pass = password_hash($password, PASSWORD_BCRYPT, $options)."\n";
	return $new_pass;
}
function set_defaults(&$array){
	foreach($array as $key=>$val){
		if(is_array($val))set_defaults($array[$key]);
		elseif($val!==0 && empty($val)){
			if($key == "password")unset($array[$key]);
			elseif($key == "about" || $key == "body")$array[$key]='';
			else unset($array[$key]);
		}
		elseif(is_string($val))$array[$key]=ucfirst($val);
	}
}
function time_passed($timestamp){
	$CI =& get_instance();
    $diff = time() - (int)$timestamp;
    if($diff == 0)return 'just now';
    $intervals = array(
        1 => array('year',    31556926),
        $diff < 31556926 => array('month',   2628000),
        $diff < 2629744 => array('week',    604800),
        $diff < 604800 => array('day',     86400),
        $diff < 86400 => array('hour',    3600),
        $diff < 3600 => array('minute',  60),
        $diff < 60 => array('second',  1)
    );
     $value = floor($diff/$intervals[1][1]);
     
     if($intervals[1][0]=='day'){
		 if($value==1)
		   $retunval = $CI->lang->line('one_day_ago');
		 else
		  $retunval =str_replace('*count*',$value,$CI->lang->line('multiple_day_ago'));
		 }
	 elseif($intervals[1][0]=='week'){
		 if($value==1)
		   $retunval = $CI->lang->line('one_week_ago');
		 else
		  $retunval =str_replace('*count*',$value,$CI->lang->line('multiple_week_ago'));
		 }	 
	elseif($intervals[1][0]=='month'){
		 if($value==1)
		   $retunval = $CI->lang->line('one_month_ago');
		 else
		  $retunval =str_replace('*count*',$value,$CI->lang->line('multiple_month_ago'));
		 }	
	elseif($intervals[1][0]=='year'){
		 if($value==1)
		   $retunval = $CI->lang->line('one_year_ago');
		 else
		  $retunval =str_replace('*count*',$value,$CI->lang->line('multiple_year_ago'));
		 }	
   	elseif($intervals[1][0]=='hour'){
		 if($value==1)
		   $retunval = $CI->lang->line('one_hour_ago');
		 else
		  $retunval =str_replace('*count*',$value,$CI->lang->line('multiple_hour_ago'));
		 }	
	elseif($intervals[1][0]=='minute'){
		 if($value==1)
		   $retunval = $CI->lang->line('one_minute_ago');
		 else
		  $retunval =str_replace('*count*',$value,$CI->lang->line('multiple_minute_ago'));
		 }	
	elseif($intervals[1][0]=='second'){
		 if($value==1)
		   $retunval = $CI->lang->line('one_second_ago');
		 else
		  $retunval =str_replace('*count*',$value,$CI->lang->line('multiple_second_ago'));
		 }		 		 		   
    else{ 
     $retunval = $value.' '.$intervals[1][0].($value > 1 ? 's' : '').' ago';
     }
     return $retunval;
}
function str_min($str, $chr){
	return strlen($str)>$chr?substr($str,0,$chr).' …':$str;
}
function rand_str($min_len = 4, $max_len = 10){
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),rand(0,50),rand($min_len,$max_len));
}
function item_rowid($item = array()){ // This function is grabbed from System/libraries/Cart.php
	if(!isset($item['id']))return false;
	return (isset($item['options']) AND count($item['options']) > 0)?md5($item['id'].implode('', $item['options'])):md5($item['id']);
}
function find_array($array, $key, $value){
	if (is_array($array)){
		foreach($array as $k=>$v){
			if($v->$key == $value)return $v;
		}
	}return false;
}
function error_union($str){
	if(strpos($str,'is required') === false || count(explode(" field is", $str))==2)return $str;
	return "The fields ".substr(str_replace(array("The ", " field is required."), array("",", "),strip_tags($str)),0, -3)." are required.";
}
if( !function_exists( "bcdiv" ) )
{
    function bcdiv( $first, $second, $scale = 0 )
    {
        $res = $first / $second;
        return round( $res, $scale );
    }
}
function translator($object){
	if(isset($_SESSION['lang']) && ($lang=$_SESSION['lang'])!='en'){
		if(is_array($object)){
			foreach($object as $key=>$val)translator($val);
		}
		else{
			if(!empty($object->translator) && count($data = unserialize($object->translator))){
				foreach($data = $data[$lang] as $key=>$val)$object->{$key} = $val;
				return $object;
			}
			return $object;
		}
	}
	return $object;
}
function instagram($ig_no=0){
	$ci = &get_instance();
	if($ci->cache->get("instagram_$ig_no"))return $ci->cache->get("instagram_$ig_no");
	$data = json_decode(get_https_content("https://api.instagram.com/v1/users/1902504902/media/recent/?client_id=ea69458ef6a34f13949b99e84d79ccf2"));
	$i = 0;
	if(count($data)){
		foreach($data->data as $key=>$val){
			$url_path = explode("?",$val->images->standard_resolution->url);
			$file = pathinfo($url_path[0]);
			$config['source_image'] = INSTAGRAM_DIR."{$file['filename']}.{$file['extension']}";
			$config['new_image'] = INSTAGRAM_DIR."210x{$file['filename']}.{$file['extension']}";
			if(file_exists($config['source_image'])){
				$cache_str = base_url()."{$config['new_image']} | {$val->link}";
				$ci->cache->write($cache_str, "instagram_$i",  3600*24);
			}
			elseif(file_put_contents($config['source_image'], file_get_contents(str_replace("https:","http:",$val->images->standard_resolution->url)))){
				$config['image_library'] = 'gd2';
				$config['maintain_ratio'] = FALSE;
				$config['width'] = 210; //biggest
				$config['height'] = 210;
				$ci->image_lib->initialize($config);
				$ci->image_lib->resize();
				$ci->image_lib->clear();
				$cache_str = base_url()."{$config['new_image']} | {$val->link}";
				$ci->cache->write($cache_str, "instagram_$i",  3600*24);
			}else $i--;
			if($i++ >= 3)break;  // limit to access only 2 images
		}
	}
	return $ci->cache->get("instagram_$ig_no");
}

function myLevel($points)
{
	$level = '1';
	if($points > 200 && $points < 2001) $level = '2';
	elseif($points > 2000 && $points < 10001) $level = '3';
	elseif($points > 10000 && $points < 20001) $level = '4';
	elseif($points > 20000) $level = '5';
	return $level;
}

function myLevelUpgrade($points)
{
	$level = myLevel($points);
	if($level == 1) $next = 200 - $points;
	if($level == 2) $next = 2000 - $points;
	if($level == 3) $next = 10000 - $points;
	if($level == 4) $next = 20000 - $points;
	if($level == 5) $next = 1000000 - $points;

	return $next;
}

function myLevelBar($points)
{
	$level = myLevel($points);
	if($level == 1) $next = 200;
	if($level == 2) $next = 2000;
	if($level == 3) $next = 10000;
	if($level == 4) $next = 20000;
	if($level == 5) {$breaker = 5; return $breaker; }

	$score = $next;
	$break = $score/5;
	if($points <= $break) $breaker = 1;
	elseif($points <= $break*2 && $points >= $break) $breaker = 2;
	elseif($points <= $break*3 && $points >= $break*2) $breaker = 3;
	elseif($points <= $break*4 && $points >= $break*3) $breaker = 4;
	elseif($points <= $break*5 && $points >= $break*4) $breaker = 5;

	return $breaker;
}

function simple_enc($text, $salt = "b$2!aSyu@mbs$123")
{
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}
// This function will be used to decrypt data.
function simple_dec($text, $salt = "b$2!aSyu@mbs$123")
{
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

function simple_enc_url($text, $salt = "b$2!aSyu@mbs$123")
{
    $encrypted = simple_enc($text, $salt);
    return strtr($encrypted, '+/=', '-_:');
}
function simple_dec_url($text, $salt = "b$2!aSyu@mbs$123")
{
    $text = strtr($text, '-_:', '+/=');
    return simple_dec($text, $salt);
}

function mysql_escape_mimic($inp) {
    if(is_array($inp))
        return array_map(__METHOD__, $inp);

    if(!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
    }

    return $inp;
}
function escape_clean($string) {
	    //echo $string;
          $char = array( ",","<",".",">","?","/","'","\"",";",":","[","{","}","]","\\","|","`","~","!","@","#","$","^","*","(",")" );
 		  foreach($char as $c){
		          if(strpos($string, $c)>0){
					     return true;die;
				   }
			  }
}

function url_encode($input){
 return strtr(base64_encode($input), '+/=', '-_,');
}

function url_decode($input) {
 return base64_decode(strtr($input, '-_,', '+/='));
}


/** convert string url in link **/

function makeLinks($str) {
	
	$str= str_replace("http://www","www",$str);
	$str= str_replace("https://www","www",$str);
	$str= str_replace("ftp://www","www",$str);
	$str= str_replace("ftps://www","www",$str);
	$str= str_replace("www","http://www",$str);
	//$reg_exUrl = "/(www)\.[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$urls = array();
	$urlsToReplace = array();
	if(preg_match_all($reg_exUrl, $str, $urls)) {
		$numOfMatches = count($urls[0]);
		$numOfUrlsToReplace = 0;
		for($i=0; $i<$numOfMatches; $i++) {
			$alreadyAdded = false;
			$numOfUrlsToReplace = count($urlsToReplace);
			for($j=0; $j<$numOfUrlsToReplace; $j++) {
				if($urlsToReplace[$j] == $urls[0][$i]) {
					$alreadyAdded = true;
				}
			}
			if(!$alreadyAdded) {
				array_push($urlsToReplace, $urls[0][$i]);
			}
		}
		$numOfUrlsToReplace = count($urlsToReplace);
		for($i=0; $i<$numOfUrlsToReplace; $i++) {
			$str = str_replace($urlsToReplace[$i], "<p class='scf_p'><a class='comment_link' href='$urlsToReplace[$i]' target=_blank'>".$urlsToReplace[$i]."</a></p> ", $str);
		}
		return $str;
	} else {
		return $str;
	}
}

//getting ip address
function get_client_ip() {
			$ipaddress = '';
			if (isset($_SERVER['HTTP_CLIENT_IP']))
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_X_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if(isset($_SERVER['REMOTE_ADDR']))
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}

                /**
 * Get user level based on best comments, posts and posts shares
 *
 * @param int $best_comments_count
 * @param int $posts_count
 * @param int $posts_share_count
 * @return string
 */
function get_user_level($best_comments_count = 0, $posts_count = 0, $posts_share_count = 0)
{
	if($best_comments_count >= 100 && $posts_count >= 200 && $posts_share_count >= 200)
        {
            $user_level = 'کاربر طلایی';
        }
        else if($best_comments_count >= 50 && $posts_count >= 100 && $posts_share_count >= 100)
        {
            $user_level = 'کاربر نقره ای';
        }
        else if($best_comments_count >= 10 && $posts_count >= 50 && $posts_share_count >= 50)
        {
            $user_level = 'کاربر برنزی';
        }
        else
        {
            $user_level = 'کاربر عادی';
        }

	return $user_level;
}

function get_user_color($user_level)
{
	if($user_level == 'کاربر طلایی')
		$color = 'gold';
	if($user_level == 'کاربر نقره ای')
		$color = 'silver';
	if($user_level == 'کاربر برنزی')
		$color = 'bronze';
	if($user_level == 'کاربر عادی')
		$color = 'regular';

	return $color;
}


/**
 * Get user level from points
 *
 * @param int $points
 * @return int
 */
function get_level($points = 0)
{
	$level = 1;
	if ($points > 200 && $points < 2001)
	{
		$level = 2;
	}
	elseif ($points > 2000 && $points < 10001)
	{
		$level = 3;
	}
	elseif ($points > 10000 && $points < 20001)
	{
		$level = 4;
	}
	elseif ($points > 20000)
	{
		$level = 5;
	}

	return $level;
}

/**
 * Return level label from points
 *
 * @param int $points
 * @return string
 */
function get_level_label($points = 0)
{
	$level = get_level($points);
	$levels = array(
		'junior',
		'senior',
		'mania',
		'Star',
		'Staff'
	);
	return $levels[$level - 1];
}

/**
 * @param array$datas
 * @param string $value
 * @param string $key
 * @return array
 * By Asaidi
 */
function make_lists($datas = array(), $value, $key = NULL)
{
    $return_data = array();

    is_array($datas) OR $datas = array($datas);

    foreach ($datas as $data) {
        is_array($data) OR $data = (array) $data;

        if($key) {
            if(array_key_exists($key, $data))
                $return_data[$data[$key]] = array_key_exists($value, $data) ? $data[$value] : NULL;
        }
        else {
            if(array_key_exists($value, $data))
                $return_data[] = $data[$value];
        }
    }

    return $return_data;
}
function getCategory(){   
    $CI =& get_instance();
    $CI->db->where('parent_id',0);
    $CI->db->order_by('show_order','asc');
    $query = $CI->db->get('category');
    $result =  $query->result();
    return $result;
}
function getsubcategory($id=""){   
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $CI->db->order_by('show_order','asc');
    $query = $CI->db->get('category');
    $result =  $query->result();
    return $result;
}

function getselect($table,$select='*',$where){   
    $CI =& get_instance();
    $CI->db->select($select);
    $CI->db->where($where);
    $query = $CI->db->get($table);
    $result =  $query->result();
    return $result;
}

function getselect_row($table,$select='*',$where){   
    $CI =& get_instance();
    $CI->db->select($select);
    $CI->db->where($where);
    $query = $CI->db->get($table);
    $result =  $query->row();
    return $result;
}

function fromated_date($time){
	  
	   return date("Y-m-d H:i:s",$time);  
	
	}
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'Year',
        'm' => 'Month',
        'w' => 'Week',
        'd' => 'Day',
        'h' => 'Hour',
        'i' => 'Minute',
        's' => 'Second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'Just Now';
}

function get_employeecount($table="",$status=""){
$CI =& get_instance();
$CI->db->where($status);
$num_rows = $CI->db->count_all_results($table);
//echo $CI->db->last_query();
//die;
return $num_rows;
}

function mypaging($url="",$tot="",$segment="",$Perpage=PER_PAGE10)
{
	  $CI =& get_instance();
	   $config = array();
	   $config["base_url"] = base_url().$url;
	   $config["total_rows"] = $tot;
	   $config["per_page"] =$Perpage;
	   $config['use_page_numbers'] = TRUE;
	   $config['num_links'] =   2;
	   $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
	   $config['cur_tag_open'] = '<li class="page-item"><a class="active pagination-btn" style="background-color: #00b3bf;">';
	   $config['cur_tag_close'] = '</a></li>';
	   $config['next_link'] = '&gt;';
	   $config['prev_link'] = '&lt;';
	   $config['first_link'] = '&laquo;';
	   $config['last_link'] = '&raquo;';
	   $config['first_tag_open'] = '<li class="page-item">';
	   $config['first_tag_close'] = '</li>';
	   $config['last_tag_open'] = '<li class="page-item">';
	   $config['last_tag_close'] = '</li>';
	   $config['next_tag_open'] = '<li class="page-item">';
	   $config['next_tag_close'] = '</li>';
	   $config['prev_tag_open'] = '<li class="page-item">';
	   $config['prev_tag_close'] = '</li>';
	   if(isset($_GET) && !empty($_GET)){

           $config['first_url'] = base_url().$url."?".http_build_query($_GET, '', "&");

           $config['suffix'] = '?'.http_build_query($_GET, '', "&");

       }
	   $config["uri_segment"] = $segment;
	  
	   if($config["total_rows"] >=0)
	   {
		 $page_number = $CI->uri->segment($segment);
		 $page['offset']="0";
		 if(isset($page_number))
		 {
			 $CI->pagination->initialize($config);
			 $page['offset'] =(isset($page_number)&&is_numeric($page_number))?($page_number-1) *  $config["per_page"]:0;
		 }  
		 else
		 {    
			 $CI->pagination->initialize($config);
			 $page['offset'] =(isset($page_number)&&is_numeric($page_number))?($page_number-1) *  $config["per_page"]:0;
			   
		 } 
	  }


	  $data = array();
	  $data['offset'] =  $page['offset'];
	  $data['per_page'] =  $config["per_page"];
	  
	  return $data;
}

function get_month_de_translation($name) {
	$arr_month=['January'=>'Januar','February'=>'Februar','March'=>'März','April'=>'April','May'=>'Mai','June'=>'Juni','July'=>'Juli','August'=>'August','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Dezember'];

	return $arr_month[$name];
}

function data_join_two($tbl1,$tbl2 ,$field1,$field2,$where,$select,$group_by="",$jtype='left')
	{   
		  $CI =& get_instance();	
	      $CI->db->group_by($group_by);
		  $CI->db->select($select);
		  $CI->db->from($tbl1);
		  $CI->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2,$jtype);
		  $CI->db->where($where);		
		  $getdata  = $CI->db->get();
		  $num = $getdata->num_rows();
		  if($num> 0) { 
			$arr=$getdata->result();
			return $arr;
		  } else 
		   return false;
	}	

function get_category_name($id) {
	$whr = array('status' => 'active', 'id' => $id);
	$CI =& get_instance();	
    $CI->db->select('category_name');
    $CI->db->from('st_category');
    $CI->db->where($whr);
    $child = $CI->db->get();
    $results = $child->result();
    return $results;
}
function get_menu(){
	$whr = array('status' => 'active', 'parent_id' => 0);
	$CI =& get_instance();	
    $CI->db->select('id,category_name,image,(select count(*) from st_category as cat where cat.parent_id=st_category.id) as sub');
    $CI->db->from('st_category');
    $CI->db->where($whr);
    $CI->db->order_by('show_order','asc');
    $child = $CI->db->get();
    $results = $child->result();
    return $results;
}
function get_sub_menu($pid){
	$whr = array('status' => 'active', 'parent_id' => $pid,'show_dropdown'=>1);
	$CI =& get_instance();	
    $CI->db->select('id,category_name,parent_id');
    $CI->db->from('st_category');
    $CI->db->order_by('show_order','asc');
    $CI->db->where($whr);
    $child = $CI->db->get();
    $results = $child->result();
    return $results;
}
function get_sub_cat_from_filter($pid, $fid){
	$whr = array('status' => 'active', 'parent_id' => $pid,'filter_category' => $fid);
	$CI =& get_instance();	
    $CI->db->select('id');
    $CI->db->from('st_category');
    $CI->db->order_by('show_order','asc');
    $CI->db->where($whr);
    $child = $CI->db->get();
    $results = $child->result();
    return $results;
}
function get_filter_menu() {
	$whr = array('status' => 'active');
	$CI =& get_instance();	
	$CI->db->select('id,category_name');
    $CI->db->from('st_filter_category');
	$CI->db->where($whr);
    $child = $CI->db->get();
    $results = $child->result();

	$results = array_map(function($el) use($CI){
		$whr = array('status' => 'active', 'filtercat_id' => $el->id);
		$CI->db->select('category_id');
		$CI->db->from('st_merchant_category');
		$CI->db->where($whr);
		$CI->db->group_by('category_id'); 
		$child = $CI->db->get();
		$res = $child->result();

		$pid = count($res) ? $res[0]->category_id : '0';
		return [
			'id' => $pid.'-'.$el->id,
			'category_name' => $el->category_name,
			'parent_id' => $pid,
		];
	}, $results);

    return $results;
}
function get_filter_with_parent_cat_menu($keyword = "") {
	$main_menu=get_menu();
	$i=0;
	$data = [];
	foreach($main_menu as $menu){
		$data[$i]=$menu;
		$submenu=get_filtersub_menu($menu->id, $keyword);
		$data[$i]->sub_category=$submenu;
		$i++;
	}
	if ($keyword) {
		$data = array_filter($data, function($el) {
			return count($el->sub_category);
		});
	}
	return $data;
}
function get_filtersub_menu($pid, $keyword = ""){
	$whr = array('status' => 'active', 'parent_id' => $pid);
	$CI =& get_instance();	
    $CI->db->select('filter_category');
    $CI->db->from('st_category');
    $CI->db->where($whr);
	$CI->db->group_by('filter_category'); 
    $child = $CI->db->get();
    $results = $child->result();

	$results = array_map(function($el) {
		return $el->filter_category;
	}, $results);
	$whr = array('status' => 'active');
	$CI->db->select('id,category_name');
    $CI->db->from('st_filter_category');
    $CI->db->where($whr);
	$CI->db->where_in('id', $results);
	if ($keyword) {
		$CI->db->like('category_name', $keyword);
	}
    $child = $CI->db->get();
    $results = $child->result();

	$results = array_map(function($el) use($pid) {
		return [
			'id' => $pid.'-'.$el->id,
			'category_name' => $el->category_name,
			'parent_id' => $pid,
			'my_cat_id' => $el->id,
		];
	}, $results);

    return $results;
}
function get_servicename($id){
	$whr = array('booking_id' => $id);
	$CI =& get_instance();	
    $CI->db->select('st_booking_detail.service_name, st_booking_detail.service_id, st_merchant_category.parent_service_id');
	$CI->db->from('st_booking_detail');
    $CI->db->join('st_merchant_category','st_booking_detail.service_id=st_merchant_category.id');
    $CI->db->where($whr);
    $child = $CI->db->get();
	$results = $child->result();
	$str = '';
	foreach ($results as $res) {
		$ps = '';
		$sub_name=get_subservicename($res->service_id);
		if($sub_name == $res->service_name) {
			$ps = $res->service_name;
		} else {
			$ps = $sub_name.' - '.$res->service_name; 
		}
		$ps .= ', ';
		$str .= $ps;
	}
    $str = substr($str, 0, -2);
    return $str;
}
function get_booking_detail_service_name($id) {
	$whr = array('id' => $id);
	$CI =& get_instance();	
    $CI->db->select('name');
    $CI->db->from('st_merchant_category');
    $CI->db->where($whr);
    $child = $CI->db->get();
    $sname=$child->row('name');

	$sub_name=get_subservicename($id);
	$ps = $sub_name;
	if($sub_name != $sname && $sname) {
		$ps .= ' - '.$sname;
	}
	return $ps;
}
function get_booking_detail_price_ab($id) {
	$whr = array('id' => $id);
	$CI =& get_instance();	
    $CI->db->select('price_start_option, price');
    $CI->db->from('st_merchant_category');
    $CI->db->where($whr);
    $child = $CI->db->get();
    $result=$child->row();
	$price = '';
	if ($result->price_start_option == 'ab') {
		$price .= 'ab ';
	}
    return $price;
}
function is_mail_enable_for_user_action($id) {
	$whr = array('id' => $id);
	$CI =& get_instance();	
    $CI->db->select('first_name,email,mail_by_user');
    $CI->db->from('st_users');
    $CI->db->where($whr);
    $child = $CI->db->get();
    $result=$child->row();
	if ($result->mail_by_user) {
		return $result;
	}
    return '';
}

function is_mail_enable_for_merchant_action($id) {
	$whr = array('id' => $id);
	$CI =& get_instance();	
    $CI->db->select('first_name,email,mail_by_merchant');
    $CI->db->from('st_users');
    $CI->db->where($whr);
    $child = $CI->db->get();
    $result=$child->row();
	if ($result->mail_by_merchant) {
		return $result;
	}
    return '';
}

function get_servicename_with_sapce($id){
	$whr = array('booking_id' => $id);
	$CI =& get_instance();	
    $CI->db->select('st_booking_detail.service_name,st_merchant_category.subcategory_id,(select category_name from st_category where id=subcategory_id) as sub_name');
    $CI->db->from('st_booking_detail');
    $CI->db->join('st_merchant_category','st_booking_detail.service_id=st_merchant_category.id');
    $CI->db->where($whr);
    $child = $CI->db->get();
    $results=$child->result();
    $sname=[];
    if(!empty($results))
      { //print_r($results); die;
		 
		  foreach($results as $res){ if($res->sub_name==$res->service_name) $sname[]=$res->service_name; else $sname[]=$res->sub_name.' - '.$res->service_name; }
	  
	  return implode(', ',$sname);
	  }
   else	  
    return "";
}

function get_discount_percent($mainprice,$discounted_price){
	$results=round(($mainprice-$discounted_price)*100/$mainprice);
    return $results;
}

function get_subservicename($id)
{
	$whr = array('id' => $id);
	$CI =& get_instance();	
    $CI->db->select('subcategory_id,(select category_name from st_category where id=subcategory_id) as sub_name');
    $CI->db->from('st_merchant_category');
    $CI->db->where($whr);
    $child = $CI->db->get();
    //echo $CI->db->last_query(); die;
    $results=$child->row('sub_name');
    return $results;
}

function get_single_servicename($id){
	$whr = array('booking_id' => $id);
	$CI =& get_instance();	
    $CI->db->select('service_name as name,(select category_name from st_category where st_category.id=(select subcategory_id from st_merchant_category where st_merchant_category.id= st_booking_detail.service_id)) as cat_name, (select price_start_option from st_merchant_category where st_merchant_category.id= st_booking_detail.service_id) as price_start_option');
    $CI->db->from('st_booking_detail',1);
    $CI->db->where($whr);
    $child = $CI->db->get();
    //echo $CI->db->last_query(); die;
    $results=$child->row();
    //$results = $child->result();
    return $results;
}

function sendPushNotification($uid,$data=array()){    
	$fields = json_encode(array(

           'to' =>'/topics/styletimer_'.$uid,

           'notification' =>$data,

           'data' =>$data

              ));

       //~ preprint($fields);

       // Set POST variables

       $url = 'https://fcm.googleapis.com/fcm/send';

       $headers = array(

           'Authorization: key=' . FIREBASE_API_KEY,

           'Content-Type: application/json'

       );

       // Open connection

       $ch = curl_init();

       // Set the url, number of POST vars, POST data

       curl_setopt($ch, CURLOPT_URL, $url);

       curl_setopt($ch, CURLOPT_POST, true);

       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

       // Disabling SSL Certificate support temporarly

       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

       curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);

       // Execute post

       $result = curl_exec($ch);

       if ($result === FALSE) {

           die('Curl failed: ' . curl_error($ch));

       }

       // Close connection

       curl_close($ch);

       return $result;

}
function daysOfWeekBetween($start_date,$dayValue,$terms,$specificDate,$check)
{  //echo $terms; die;
	$date=date('Y-m-d');
	$endDate=date('Y-m-d',strtotime($date." +3 months"));
	
	$dateArr = array();
	
	$date1=date('Y-m-d',strtotime($start_date));
	$date2=$endDate;
	function dateDiff($date1, $date2) 
	{
	  $date1_ts = strtotime($date1);
	  $date2_ts = strtotime($date2);
	  $diff = $date2_ts - $date1_ts;
	  return round($diff / 86400);
	}
	$dateDiff= dateDiff($date1, $date2);
	//cho $dateDiff; die;
	if($dayValue!="month")
	  {
	     $j=1;
	     
	     if($check=="check")
	      {
			$check=$dayValue;
		  }
	    else{
			 $check=0;
			 } 
		for($i=$check;$i<$dateDiff;$i+=$dayValue){
		
		  if($terms=='specific')
		     {
			 //echo "tems \n";
			 $startDate = strtotime($start_date." +".$i." days");
			 
			  if(date('Y-m-d',strtotime($specificDate))>=date('Y-m-d',$startDate))
			    {
				  $startDate = strtotime($start_date." +".$i." days");
				  $dateArr[]=date('Y-m-d',$startDate);
				  		 
			    }
			    else{
					 break;
					}
		    }
		 else{ //echo $j."=".$terms;
			  if($j<$terms){
				  //echo $j;
				   $startDate = strtotime($start_date." +".$i." days");
				   //echo $startDate."\n";
				   $dateArr[]=date('Y-m-d',$startDate); 
				  }
			  else{
				  break;
				  }  
			  
			}	  
		  $j++;  
		}
				//return($dateArr);
     }
   else{
	   
	   if($check!="check")
	      {
			$dateArr[]=$date1;
		  }
		  
		 $j=1;
	     $check=1;

	   for($i=$check;$i<=3;$i++){
	   $startDate = strtotime($start_date." +".$i." month");
	   $dateS=date('Y-m-d',$startDate);
	    if($terms=='specific')
		  { //echo "if";
			  if(date('Y-m-d',strtotime($specificDate))>=$dateS)
	             {
					 $dateArr[]=$dateS;
				 }	 
				 
		 }	 
	  else{  //echo "else";
		   if($endDate>=$dateS && $j<=$terms)
			 {
				 $dateArr[]=$dateS;
			 }
		 }
		 $j++;	 
	    }
	   }
	  //print_r($dateArr); die;

  return($dateArr);

}

function getstatus_row($id){   
    $CI =& get_instance();
    $CI->db->select('status');
    $CI->db->where('id',$id);
    $query = $CI->db->get('st_users');
    $result =  $query->row('status');
    return $result;
}

function getTrialPeriodDuration(){   
    $CI =& get_instance();
    $CI->db->select('notification_time');
    $CI->db->where('id',1);
    $query = $CI->db->get('st_users');
    $result =  $query->row('notification_time');
    return $result;
}

function getMerchentCountBycode($code){   
    $CI =& get_instance();
    $CI->db->select('count(id) as totalcount');
    $CI->db->where(array('access'=>'marchant','salesman_code'=>$code));
    $query = $CI->db->get('st_users');
    $result =  $query->row();
    return $result->totalcount;
}

function checkTimeSlotsMerchant($times_arry,$empid,$mrchntid,$totaldurationTim,$bk_id="",$employee_list=""){   
    $CI =& get_instance();
  //echo $empid.'=='.$mrchntid.'=='.$totaldurationTim.'<pre>'; print_r($employee_list); die;
     if(!empty($empid)){
		 $dayName  = strtolower(date("l",strtotime($times_arry[0]->start)));
		 
		 $stime = date('H:i:s',strtotime($times_arry[0]->start));
		 $etime = date('H:i:s',strtotime(''.$times_arry[0]->start.' +'.$totaldurationTim.' minute'));			
		 
		 $where = "user_id=".$mrchntid." AND days='".$dayName."' AND type='open'";	
		 $query = "SELECT id, starttime, endtime FROM st_availability WHERE ".$where;
		 $sql   = $CI->db->query($query);
		 $timelimit   = $sql->row();

		 if (!empty($timelimit->id)) {
			if ($stime < $timelimit->starttime)  $stime = $timelimit->starttime;
			if ($etime > $timelimit->endtime)  $etime = $timelimit->endtime;
		 }

		 $where = "user_id=".$mrchntid." AND days='".$dayName."' AND type='open' AND starttime<='".$stime."' AND endtime>='".$etime."'";	
		 	 	 	 
		 $query = "SELECT id FROM st_availability WHERE ".$where;
		 $sql   = $CI->db->query($query);
		 $res   = $sql->row();
		 
		 if(!empty($res->id)){
			   
			     $where1 = "user_id=".$empid." AND days='".$dayName."' AND type='open'  AND ((starttime<='".$stime."' AND endtime>='".$etime."') OR (starttime_two<='".$stime."' AND endtime_two>='".$etime."'))";	
		 	 		 
				 $query1 = "SELECT id,(SELECT online_booking FROM st_users WHERE id=".$empid.") as online_booking FROM st_availability WHERE ".$where1;
				 $sql1   = $CI->db->query($query1);
				 $res1   = $sql1->row();
			   if(!empty($res1->id)){
				   
				     $bdwhere = "st_booking_detail.emp_id=".$empid." AND (st_booking.status='confirmed' OR st_booking.status='completed') AND st_booking_detail.show_calender !=1";
				     
				      $i  = 0;
					  $wh = " (";
					  $blockWhere = "booking_type='self' AND employee_id=".$empid;
					  $blockSubWhere = " (";
					foreach($times_arry as $times)
					  { 
						 if($i==0){
							 
							 $blockSubWhere.='((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))'; 
							 
							 $wh.= "(((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))"; 
							 }
					     else{
							 $blockSubWhere.=' OR ((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))'; 
							 
						     $wh.=" OR (((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))";  
						    }	  
						//$where.=" AND preferences LIKE '%".$pre."%'";  
						$i++;
					  }
					 $wh.= ")";
					 $bdwhere.=" AND".$wh;	
					 
					 $blockSubWhere.=")";
					 $blockWhere.=" AND".$blockSubWhere;
					 
					 $blockQuery = "SELECT id FROM st_booking WHERE ".$blockWhere;
				     $blocksql   = $CI->db->query($blockQuery);
				     $blockres   = $blocksql->result();
				     
				     if(!empty($blockres))
				       {
				         return false;
				       }
				    else
				       {
			         	return true;
						 if(!empty($bk_id)) $bdwhere.=" AND st_booking_detail.booking_id !=".$bk_id;
						 											 
						 $bdquery = "SELECT st_booking_detail.id FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE ".$bdwhere;
						 $bdsql   = $CI->db->query($bdquery);
						 $bdres   = $bdsql->result();
						 
						 if(!empty($bdres))
						   { 
							  return false;						   
						   }
						 else return true; 
						} 
				     
				     //echo $CI->db->last_query().'<pre>'; print_r($bdres); 
				   }
				else return false;   
			 
			 }
		 else return false;	 
		 
		 }
	else{
		//return true;
		 $dayName  = strtolower(date("l", strtotime($times_arry[0]->start)));		 
		 $stime    = date('H:i:s',strtotime($times_arry[0]->start));
		 $etime    = date('H:i:s',strtotime(''.$times_arry[0]->start.' +'.$totaldurationTim.' minute'));			
		 
		 $where = "user_id=".$mrchntid." AND days='".$dayName."' AND type='open'";	
		 $query = "SELECT id, starttime, endtime FROM st_availability WHERE ".$where;
		 $sql   = $CI->db->query($query);
		 $timelimit   = $sql->row();

		 if (!empty($timelimit->id)) {
			if ($stime < $timelimit->starttime)  $stime = $timelimit->starttime;
			if ($etime > $timelimit->endtime)  $etime = $timelimit->endtime;
		 }

		 $where = "user_id=".$mrchntid." AND days='".$dayName."' AND type='open' AND starttime<='".$stime."' AND endtime>='".$etime."'";	
		 	 	 	 
		 $query = "SELECT id FROM st_availability WHERE ".$where;
		 $sql   = $CI->db->query($query);
		 $res   = $sql->row();
		 
		 if(!empty($res->id)){
			 
			if(empty($employee_list)){ 
			 $getEmpwhere = "merchant_id=".$mrchntid." AND status='active' AND access='employee' AND days='".$dayName."' AND type='open' AND ((starttime<='".$stime."' AND endtime>='".$etime."') OR (starttime_two<='".$stime."' AND endtime_two>='".$etime."'))";	
			 	 	 	 	 
			 $getEmpquery = "SELECT st_users.id,st_users.first_name FROM st_users JOIN st_availability ON st_availability.user_id=st_users.id WHERE  ".$getEmpwhere;
			 $getEmpsql   = $CI->db->query($getEmpquery);
			 $getEmpres   = $getEmpsql->result();
			 
		    }else{
				 $getEmpres   = $employee_list;
				}
			 //if($stime=='09:15:00')
			 //echo $CI->db->last_query().'<pre>'; print_r($getEmpres); die;
			 			 
			 if(!empty($getEmpres)){

				 $checkEmp=0;
				 
					 foreach($getEmpres as $emp)
					   {				   
						 $bdwhere = "st_booking_detail.emp_id=".$emp->id." AND (st_booking.status='confirmed' OR st_booking.status='completed') AND st_booking_detail.show_calender !=1 ";
						  $i  = 0;
						  $wh = " (";
						
						$blockWhere    = "booking_type='self' AND employee_id=".$emp->id;
					    $blockSubWhere = " (";	
							
						foreach($times_arry as $times)
							{ 
								 if($i==0){
									 
								 $blockSubWhere.='((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))';  
								 
								 $wh.= "(((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))"; 
								 }
							 else{
								 
								  $blockSubWhere.=' OR ((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))'; 
								 
								 $wh.=" OR (((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))";  
								}	  
							 
							   $i++;
							  
						   }
							 $wh.= ")";
							 
							 $bdwhere.=" AND".$wh;
							 
							 $blockSubWhere.= ")";
							 $blockWhere.= " AND".$blockSubWhere;
							 
							 $blockQuery = "SELECT id FROM st_booking WHERE ".$blockWhere;
							 $blocksql   = $CI->db->query($blockQuery);
							 $blockres   = $blocksql->result();
							 	
							if(!empty($blockres)){
								}							
							else{	
								$checkEmp=1;					 				 
								// $bdquery = "SELECT st_booking_detail.id FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE ".$bdwhere;
								// $bdsql   = $CI->db->query($bdquery);
								// $bdres   = $bdsql->result();

								// if(!empty($bdres))
								// { 
												   
								// }
								// else{
								//   $checkEmp=1;
								//    break;
								// }
						 }
				    }
				    
				    if($checkEmp==1)
						return true;
					else
					    return false;
				     
				     //echo $CI->db->last_query().'<pre>'; print_r($bdres); 
				   }
				else return false;   
			 
			 }
		 else return false;	
		
		}	 
    
}

function checkTimeSlotsMerchantDuplicate($times_arry,$empid,$mrchntid,$totaldurationTim,$bk_id="",$employee_list=""){   
    $CI =& get_instance();
  	//echo $empid.'=='.$mrchntid.'=='.$totaldurationTim.'<pre>'; print_r($employee_list); die;
	if(!empty($empid)){
		$dayName  = strtolower(date("l",strtotime($times_arry[0]->start)));
		
		$stime = date('H:i:s',strtotime($times_arry[0]->start));
		$etime = date('H:i:s',strtotime(''.$times_arry[0]->start.' +'.$totaldurationTim.' minute'));			
		
		$where = "user_id=".$mrchntid." AND days='".$dayName."' AND type='open'";	
		$query = "SELECT id, starttime, endtime FROM st_availability WHERE ".$where;
		$sql   = $CI->db->query($query);
		$timelimit   = $sql->row();

		if (!empty($timelimit->id)) {
			if ($stime < $timelimit->starttime)  $stime = $timelimit->starttime;
			if ($etime > $timelimit->endtime)  $etime = $timelimit->endtime;
		}

		 $where = "user_id=".$mrchntid." AND days='".$dayName."' AND type='open' AND starttime<='".$stime."' AND endtime>='".$etime."'";	
		 	 	 	 
		 $query = "SELECT id FROM st_availability WHERE ".$where;
		 $sql   = $CI->db->query($query);
		 $res   = $sql->row();
		 
		 if(!empty($res->id)){
			   
			     $where1 = "user_id=".$empid." AND days='".$dayName."' AND type='open'  AND ((starttime<='".$stime."' AND endtime>='".$etime."') OR (starttime_two<='".$stime."' AND endtime_two>='".$etime."'))";	
		 	 		 
				 $query1 = "SELECT id,(SELECT online_booking FROM st_users WHERE id=".$empid.") as online_booking FROM st_availability WHERE ".$where1;
				 $sql1   = $CI->db->query($query1);
				 $res1   = $sql1->row();
			   if(!empty($res1->id)){
				   
				     $bdwhere = "st_booking_detail.emp_id=".$empid." AND (st_booking.status='confirmed' OR st_booking.status='completed') AND st_booking_detail.show_calender !=1";
				     
				      $i  = 0;
					  $wh = " (";
					  $blockWhere = "booking_type='self' AND employee_id=".$empid;
					  $blockSubWhere = " (";
					foreach($times_arry as $times)
					  { 
						 if($i==0){
							 
							 $blockSubWhere.='((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))'; 
							 
							 $wh.= "(((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))"; 
							 }
					     else{
							 $blockSubWhere.=' OR ((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))'; 
							 
						     $wh.=" OR (((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))";  
						    }	  
						//$where.=" AND preferences LIKE '%".$pre."%'";  
						$i++;
					  }
					 $wh.= ")";
					 $bdwhere.=" AND".$wh;	
					 
					 $blockSubWhere.=")";
					 $blockWhere.=" AND".$blockSubWhere;
					 
					 $blockQuery = "SELECT id FROM st_booking WHERE ".$blockWhere;
				     $blocksql   = $CI->db->query($blockQuery);
				     $blockres   = $blocksql->result();
				     
				     if(!empty($blockres))
				       {
				         return false;
				       }
				    else
				       {

						 if(!empty($bk_id)) $bdwhere.=" AND st_booking_detail.booking_id !=".$bk_id;
						 											 
						 $bdquery = "SELECT st_booking_detail.id FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE ".$bdwhere;
						 $bdsql   = $CI->db->query($bdquery);
						 $bdres   = $bdsql->result();
						 
						 if(!empty($bdres))
						   { 
							  return false;						   
						   }
						 else return true; 
						} 
				     
				     //echo $CI->db->last_query().'<pre>'; print_r($bdres); 
				   }
				else return false;   
			 
			 }
		 else return false;	 
		 
		}
	 
    
}

function checkTimeSlots($times_arry,$empid,$mrchntid,$totaldurationTim,$bk_id="",$employee_list=""){   
    $CI =& get_instance();
  //echo $empid.'=='.$mrchntid.'=='.$totaldurationTim.'<pre>'; print_r($employee_list); die;
     if(!empty($empid)){
		 $dayName  = strtolower(date("l",strtotime($times_arry[0]->start)));
		 
		 $stime = date('H:i:s',strtotime($times_arry[0]->start));
		 $etime = date('H:i:s',strtotime(''.$times_arry[0]->start.' +'.$totaldurationTim.' minute'));			
		 
		 $where = "user_id=".$mrchntid." AND days='".$dayName."' AND type='open' AND starttime<='".$stime."' AND endtime>='".$etime."'";	
		 	 	 	 
		 $query = "SELECT id FROM st_availability WHERE ".$where;
		 $sql   = $CI->db->query($query);
		 $res   = $sql->row();
		 
		 if(!empty($res->id)){
			   
			     $where1 = "user_id=".$empid." AND days='".$dayName."' AND type='open'  AND ((starttime<='".$stime."' AND endtime>='".$etime."') OR (starttime_two<='".$stime."' AND endtime_two>='".$etime."'))";	
		 	 		 
				 $query1 = "SELECT id,(SELECT online_booking FROM st_users WHERE id=".$empid.") as online_booking FROM st_availability WHERE ".$where1." HAVING online_booking>0";
				 $sql1   = $CI->db->query($query1);
				 $res1   = $sql1->row();
			   if(!empty($res1->id)){
				   
				     $bdwhere = "st_booking_detail.emp_id=".$empid." AND (st_booking.status='confirmed' OR st_booking.status='completed') AND st_booking_detail.show_calender !=1";
				     
				      $i  = 0;
					  $wh = " (";
					  $blockWhere = "booking_type='self' AND employee_id=".$empid;
					  $blockSubWhere = " (";
					foreach($times_arry as $times)
					  { 
						 if($i==0){
							 
							 $blockSubWhere.='((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))'; 
							 
							 $wh.= "(((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))"; 
							 }
					     else{
							 $blockSubWhere.=' OR ((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))'; 
							 
						     $wh.=" OR (((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))";  
						    }	  
						//$where.=" AND preferences LIKE '%".$pre."%'";  
						$i++;
					  }
					 $wh.= ")";
					 $bdwhere.=" AND".$wh;	
					 
					 $blockSubWhere.=")";
					 $blockWhere.=" AND".$blockSubWhere;
					 
					 $blockQuery = "SELECT id FROM st_booking WHERE ".$blockWhere;
				     $blocksql   = $CI->db->query($blockQuery);
				     $blockres   = $blocksql->result();
				     
				     if(!empty($blockres))
				       {
				         return false;
				       }
				    else
				       {
			         
						 if(!empty($bk_id)) $bdwhere.=" AND st_booking_detail.booking_id !=".$bk_id;
						 											 
						 $bdquery = "SELECT st_booking_detail.id FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE ".$bdwhere;
						 $bdsql   = $CI->db->query($bdquery);
						 $bdres   = $bdsql->result();
						 
						 if(!empty($bdres))
						   { 
							  return false;						   
						   }
						 else return true; 
						} 
				     
				     //echo $CI->db->last_query().'<pre>'; print_r($bdres); 
				   }
				else return false;   
			 
			 }
		 else return false;	 
		 
		 }
	else{
		//return true;
		 $dayName  = strtolower(date("l", strtotime($times_arry[0]->start)));		 
		 $stime    = date('H:i:s',strtotime($times_arry[0]->start));
		 $etime    = date('H:i:s',strtotime(''.$times_arry[0]->start.' +'.$totaldurationTim.' minute'));			
		 
		 $where = "user_id=".$mrchntid." AND days='".$dayName."' AND type='open' AND starttime<='".$stime."' AND endtime>='".$etime."'";	
		 	 	 	 
		 $query = "SELECT id FROM st_availability WHERE ".$where;
		 $sql   = $CI->db->query($query);
		 $res   = $sql->row();
		 
		 if(!empty($res->id)){
			 
			//if(empty($employee_list)){ 
			 $getEmpwhere = "merchant_id=".$mrchntid." AND status='active' AND access='employee' AND online_booking=1 AND days='".$dayName."' AND type='open' AND ((starttime<='".$stime."' AND endtime>='".$etime."') OR (starttime_two<='".$stime."' AND endtime_two>='".$etime."'))";	
			 	 	 	 	 
			 $getEmpquery = "SELECT st_users.id,st_users.first_name FROM st_users JOIN st_availability ON st_availability.user_id=st_users.id WHERE  ".$getEmpwhere;
			 $getEmpsql   = $CI->db->query($getEmpquery);
			 $getEmpres   = $getEmpsql->result();
			 
		    // }else{
			// 	 $getEmpres   = $employee_list;
			// 	}
			 //if($stime=='09:15:00')
			 //echo $CI->db->last_query().'<pre>'; print_r($getEmpres); die;
			 			 
			 if(!empty($getEmpres)){
				 
				 $checkEmp=0;
				 
					 foreach($getEmpres as $emp)
					   {				   
						 $bdwhere = "st_booking_detail.emp_id=".$emp->id." AND (st_booking.status='confirmed' OR st_booking.status='completed') AND st_booking_detail.show_calender !=1 ";
						  $i  = 0;
						  $wh = " (";
						
						$blockWhere    = "booking_type='self' AND employee_id=".$emp->id;
					    $blockSubWhere = " (";	
							
						foreach($times_arry as $times)
							{ 
								 if($i==0){
									 
								 $blockSubWhere.='((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))';  
								 
								 $wh.= "(((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))"; 
								 }
							 else{
								 
								  $blockSubWhere.=' OR ((booking_time>="'.$times->start.'" AND booking_time<"'.$times->end.'") OR (booking_endtime>"'.$times->start.'" AND booking_endtime<="'.$times->end.'") OR (booking_time<="'.$times->start.'" AND booking_endtime>"'.$times->start.'") OR (booking_time>"'.$times->start.'" AND booking_endtime<="'.$times->end.'"))'; 
								 
								 $wh.=" OR (((setuptime_start>='".$times->start."' AND setuptime_start<'".$times->end."') OR (setuptime_end>'".$times->start."' AND setuptime_end<='".$times->end."') OR (setuptime_start<='".$times->start."' AND setuptime_end>'".$times->start."') OR (setuptime_start>'".$times->start."' AND setuptime_end<='".$times->end."')) OR (service_type=1 AND ((finishtime_start>='".$times->start."' AND finishtime_start<'".$times->end."') OR (finishtime_end>'".$times->start."' AND finishtime_end<='".$times->end."') OR (finishtime_start<='".$times->start."' AND finishtime_end>'".$times->start."') OR (finishtime_start>'".$times->start."' AND finishtime_end<='".$times->end."'))))";  
								}	  
							 
							   $i++;
							  
						   }
							 $wh.= ")";
							 
							 $bdwhere.=" AND".$wh;
							 
							 $blockSubWhere.= ")";
							 $blockWhere.= " AND".$blockSubWhere;
							 
							 $blockQuery = "SELECT id FROM st_booking WHERE ".$blockWhere;
							 $blocksql   = $CI->db->query($blockQuery);
							 $blockres   = $blocksql->result();
							 	
							if(!empty($blockres)){
								}							
							else{	
							 							 				 
								$bdquery = "SELECT st_booking_detail.id FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE ".$bdwhere;
								$bdsql   = $CI->db->query($bdquery);
								$bdres   = $bdsql->result();

								if(!empty($bdres))
								{ 
												   
								}
								else{
								  $checkEmp=1;
								   break;
								}
						 }
				    }
				    
				    if($checkEmp==1)
						return true;
					else
					    return false;
				     
				     //echo $CI->db->last_query().'<pre>'; print_r($bdres); 
				   }
				else return false;   
			 
			 }
		 else return false;	
		
		}	 
    
}

function get_tax_details($id=""){
	$CI =& get_instance();
    $CI->db->select('price,tax_name');
    $CI->db->where(array('id'=>$id,'status'=>'active'));
    $query = $CI->db->get('st_taxes');
    $result =  $query->row();
    return $result;
	
	}
	
// get last id of booking for peritculer salon
function get_last_booking_id($mid=""){
	
	$date = date('Y-01-01 00:00:00');
	
	$CI =& get_instance();
    $CI->db->select('id,book_id');
    $CI->db->where(array('merchant_id'=>$mid,'booking_type !='=>'self','booking_time >='=>$date,'book_id !='=>0));
    $CI->db->order_by('id','desc');
    $query = $CI->db->get('st_booking');
    $result =  $query->row('book_id');
    if(!empty($result)){
		$y     = date('Y');		
		$newId = str_replace($y,"",$result);
		//str_replace($y,"",$result)
		$newId = $newId+1;
		$return = date('Y').$newId;
		//echo $return.'='.$newId.'='.$y.'=='.$result; die;
		}
	else $return = date('Y').'1';	
   // echo $result;
	return $return;
	}
	
	function check_review($allid){
	
	$serviceReviewQuery = 'SELECT count(rv.id) as totalreview FROM st_booking_detail as bd INNER JOIN st_review as rv ON bd.booking_id=rv.booking_id WHERE bd.service_id IN ('.$allid.')';
	
	 $CI = & get_instance();
	 $res = $CI->db->query($serviceReviewQuery)->row();
	 return $res->totalreview;
	// echo '<pre>'; print_r($res); 
	}


	function getholidays($year){
    	$url='https://get.api-feiertage.de?years='.$year.'';

		// Open connection
		$headers = array(
        	'content-type: application/json',
			// 'X-DFA-Token: dfa'
		);

		$ch = curl_init();

		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result   = curl_exec($ch);
     
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		// Close connection
		curl_close($ch);
		return json_decode($result); //die;
	}


	function getmembership_daycount($id=""){
		$CI =& get_instance();
	    $CI->db->select('subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status,online_booking,allow_online_booking');
	    $CI->db->where("id",$id);
	    $query = $CI->db->get('st_users');
	    $result =  $query->row();

	    //echo date('Y-m-d H:i:s',strtotime('-7 days',time())); die;
	   $msg="";
	   if(!empty($result)){
		if($result->end_date>date('Y-m-d H:i:s',strtotime('+14 days',time()))){
		} else if ($result->allow_online_booking == 'true' && $result->online_booking) {
		} else {   
			   $date1 = strtotime($result->end_date);  
			   $date2 = strtotime(date('Y-m-d H:i:s'));  
			     
			   // Formulate the Difference between two dates 
			   $diff = abs($date2 - $date1);  
			   
			   // total seconds in a year (365*60*60*24) 
				$years = floor($diff / (365*60*60*24));  
				  
				$months = floor(($diff - $years * 365*60*60*24) 
											   / (30*60*60*24));  
				$days = floor(($diff - $years * 365*60*60*24 -  
							 $months*30*60*60*24)/ (60*60*24));
				if($result->end_date<date('Y-m-d H:i:s')){
					$day = 0;
					}
			   else{
				    $day = $days;
				   }			  
			   
			   if($result->subscription_status =='trial'){
			   	   if($result->end_date>date('Y-m-d H:i:s')){
				       $msg= "";
				      }
				   else{
					   
                    $msg= `Dein Abonnement ist abgelaufen. Sichere dir jetzt eine neue <a style="text-decoration: underline" href='".base_url()."membership'>Mitgliedschaft!</a>`;
				   }   
				  }
			  else{ 
				  if($result->end_date<=date('Y-m-d H:i:s')){
				   $msg= "Dein Abonnement ist abgelaufen. Sichere dir jetzt eine neue <a href='".base_url()."membership'>Mitgliedschaft!</a>";
				  }
			  } 
				   
		   } 
		}
		return $msg;

	}

	function getBookings($merchant_id, $where) {
		$CI =& get_instance();
		$CI->db->select('id, status, total_price, emp_commission, employee_id, user_id, booking_type');
		if ($where) {
			$CI->db->where($where);
		}
		$CI->db->where('merchant_id', $merchant_id);
		$query = $CI->db->get('st_booking');
		$result = $query->result();
		return $result;
	}

	function getmembership_exp($id=""){
		
		$CI =& get_instance();
	    $CI->db->select('subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status,online_booking,allow_online_booking');
	    $CI->db->where("id",$id);
	    $query = $CI->db->get('st_users');
	    $result =  $query->row();

	    //echo date('Y-m-d H:i:s',strtotime('-7 days',time())); die;
	   $msg="";
	   $expired = 0;
		// print_r($result);
	   if(!empty($result)){
			if($result->end_date>date('Y-m-d H:i:s',strtotime('+14 days',time()))) {
				if ($result->subscription_status == 'cancel') {
					$date1 = strtotime($result->end_date);  
					$date2 = strtotime(date('Y-m-d H:i:s')); 

					$exp_date = date('d.m.Y', $date1);
					$exp_time = date('H:i', $date1);

					$msg = 'Deine Mitgliedschaft bei styletimer läuft am '.$exp_date.'<br/>um '.$exp_time.' Uhr vollständig aus.';
					$_SESSION['sty_membership']=$msg;
				}
			} 
			else
			{   
				$date1 = strtotime($result->end_date);  
				$date2 = strtotime(date('Y-m-d H:i:s')); 

				$exp_date = date('d.m.Y', $date1);
				$exp_time = date('H:i', $date1);
				$exp_dat = date('Y-m-d H:i', $date1);

				// Formulate the Difference between two dates 
				$diff = abs($date2 - $date1);  
			
				// total seconds in a year (365*60*60*24) 
				$years = floor($diff / (365*60*60*24));  
				
				$months = floor(($diff - $years * 365*60*60*24) 
											/ (30*60*60*24));  
				$days = floor(($diff - $years * 365*60*60*24 -  
							$months*30*60*60*24)/ (60*60*24));
				
				if($result->end_date < date('Y-m-d H:i:s')){
					$day = 0;
					if ($result->allow_online_booking == 'true' && $result->online_booking == 1) {
						$expired = 0;
					} else {
						$expired = 1;
					}
				}
				else{
					$day = $days;
				}			  
			
				if($result->subscription_status =='trial'){
					if($result->end_date > date('Y-m-d H:i:s')){
						if ($day == 1) {
							$text ='Tag';
						}
						else{
							$text ='Tage';
						}
						$msg= "Du hast noch <b>".$day." ".$text." </b>bevor dein Testkonto abläuft.  <br/><a href='".base_url()."membership'>Jetzt Mitgliedschaften ansehen und styletimer weiterhin nutzen.</a>";
						// $_SESSION['sty_membership']='';
					}
					else{
						$CI->db->where("id",$id);
						$CI->db->update('st_users', array('online_booking' => '0'));
						$msg= "Dein kostenloser Testzeitraum ist abgelaufen. Um styletimer weiterhin nutzen zu können, sichere dir jetzt eine unserer <a href='".base_url()."membership'>Mitgliedschaften!</a>";
						$_SESSION['sty_membership']=$msg;
					}
				}
				else{ 
					if($result->end_date<=date('Y-m-d H:i:s')){
						$CI->db->where("id",$id);
						if ($result->allow_online_booking == 'true' && $result->online_booking == 1)
						{
							$CI->db->update('st_users', array('online_booking' => '1'));
							$_SESSION['sty_membership']='';
						} else {
							$CI->db->update('st_users', array('online_booking' => '0'));
							if ($result->subscription_status == 'payment_failed') {
								$msg = 'Leider ist deine letzte Zahlung fehlgeschlagen und deine Mitgliedschaft wurde deshalb automatisch zum '.$exp_date.' gekündigt. Wenn du styletimer weiterhin nutzen möchtest, kannst du deine <a href="'.base_url().'membership">Mitgliedschaft hier erneuern.</a>';
							} else {
								$msg = 'Deine Mitgliedschaft bei styletimer ist am '.$exp_date.' ausgelaufen. <br/> <a href="'.base_url().'membership">Klicke hier um deine Mitgliedschaft zu erneuern.</a>';
							}
							$_SESSION['sty_membership']=$msg;
						}
					} else if ($result->subscription_status == 'cancel') {
						$msg = 'Deine Mitgliedschaft bei styletimer läuft am '.$exp_date.'<br/>um '.$exp_time.' Uhr vollständig aus.';
						$_SESSION['sty_membership']=$msg;
					} else {
						$_SESSION['sty_membership']=$msg;
					}
				}				   
			} 
		}
		return [
			'msg' => $msg,
			'expired' => $expired
		];

	}

	function getmembership_status_for_onlinebooking($id=""){
		$CI =& get_instance();
	    $CI->db->select('subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status,online_booking,allow_online_booking');
	    $CI->db->where("id",$id);
	    $query = $CI->db->get('st_users');
	    $result =  $query->row();

	    //echo date('Y-m-d H:i:s',strtotime('-7 days',time())); die;
	   $msg="";
	   if(!empty($result)){
		if($result->end_date>date('Y-m-d H:i:s',strtotime('+7 days',time())))
		{ } 
	    else
		   {   
			   $date1 = strtotime($result->end_date);  
			   $date2 = strtotime(date('Y-m-d H:i:s'));  
			     
			   // Formulate the Difference between two dates 
			   $diff = abs($date2 - $date1);  
			   
			   // total seconds in a year (365*60*60*24) 
				$years = floor($diff / (365*60*60*24));  
				  
				$months = floor(($diff - $years * 365*60*60*24) 
											   / (30*60*60*24));  
				$days = floor(($diff - $years * 365*60*60*24 -  
							 $months*30*60*60*24)/ (60*60*24));
				if($result->end_date<date('Y-m-d H:i:s')){
					$day = 0;
					}
			   else{
				    $day = $days;
				   }			  
			   
			   if($result->subscription_status =='trial'){
			   	   if($result->end_date>date('Y-m-d H:i:s')){
					   if($day==1){
						   $text ='Tag';
						   }
						 else{
							 $text ='Tage';
							 }  
				      // $msg= "Du hast noch <b>".$day." ".$text." </b>bevor dein Testkonto abläuft.  <a href='".base_url()."membership' target='_blank'>Jetzt Mitgliedschaften ansehen und styletimer weiterhin nutzen.</a>";
				         $msg= "yes";
				      }
				   else{
				   		$CI->db->where("id",$id);
	           			$CI->db->update('st_users', array('online_booking' => '0'));
					  $msg= "EXP";
				   }   
				  }
			  else{ 
				  if($result->end_date<=date('Y-m-d H:i:s')){
				  		$CI->db->where("id",$id);
						if ($result->online_booking == '1' && $result->allow_online_booking == 'true') {
							$CI->db->update('st_users', array('online_booking' => '1'));	
						} else {
	           				$CI->db->update('st_users', array('online_booking' => '0'));
						}
				   		$msg= "EXP";
				   //$_SESSION['sty_membership']='exp';
				  }
				  else
				  	$msg= "yes";
			  } 
				   
		   } 
		}
		return $msg;

	}

	function getprofilestatus_row($id){   
	    $CI =& get_instance();
	    $CI->db->select('profile_status');
	    $CI->db->where('id',$id);
	    $query = $CI->db->get('st_users');
	    $result =  $query->row('profile_status');
	    return $result;
	}

	function getMerchentCountBycode_plan($code){   
    	$CI =& get_instance();
    	$CI->db->select('count(id) as totalcount');
    	$CI->db->where(array('access'=>'marchant','salesman_code'=>$code,'plan_id !=' => ""));
    	$query = $CI->db->get('st_users');
    	$result =  $query->row();
    	return $result->totalcount;
	}
function get_footer_links(){   
    	$CI =& get_instance();
    	$CI->db->select('*');    	
    	$query = $CI->db->get('st_pages_names');
    	$result =  $query->row();
    	return $result;
	}
function get_pin_status($id){   
    	$CI =& get_instance();
    	$CI->db->select('pinstatus');
    	$CI->db->where('id',$id);    	
    	$query = $CI->db->get('st_users');
    	$result =  $query->row();
    	return $result;
	}
function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      
    // Declare an empty array 
    $array = array(); 
   // echo $end; die; 
    // Variable that store the date interval 
    // of period 1 day 
    $interval = new DateInterval('P1D'); 
  
    $realEnd = new DateTime($end); 
    $realEnd->add($interval); 
  
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
  
    // Use loop to store date into array 
    foreach($period as $date) {
		                  
        $array[] = $date->format($format);  
    } 
  
    // Return the array elements 
    return $array; 
} 	
	
//curl 'https://calendarific.com/api/v2/holidays?&api_key=baa9dc110aa712sd3a9fa2a3dwb6c01d4c875950dc32vs&country=US&year=2019'


function getEmpPermissionForDeletCancel($uid) { 
       	$CI =& get_instance();
    	$CI->db->select('allow_emp_to_delete_cancel_booking');    	
    	$CI->db->where(array('id'=>$uid,'status'=>'active'));
    	$query = $CI->db->get('st_users');
    	$result =  $query->row();
    	return $result;
} 	

function getTimeInSeconds($str) {
	$curr_time = explode(':', $str);
	for ($i = 0; $i < count($curr_time); $i++) {
		$curr_time[$i] = intval($curr_time[$i]);
	}
	$t = $curr_time[0] * 3600 + $curr_time[1] * 60 + $curr_time[2];
	return $t;
}

function convertSecToTime($t) {
	$hours = floor($t / 3600);
	$hh = $hours < 10 ? "0" . $hours : $hours;
	$min = floor(($t % 3600) / 60);
	$mm = $min < 10 ? "0" . $min : $min;
	$sec = (($t % 3600) % 60);
	$ss = $sec < 10 ? "0" . $sec : $sec;
	$ans = $hh . ":" . $mm . ":" . $ss;
	return $ans;
}

function getPreExtraHrs($id) {
	$CI =& get_instance();
	$CI->db->select('MIN(starttime) as mintime,(SELECT MAX(endtime) FROM st_availability WHERE type="open" AND user_id="'.$id.'") as maxtime');    	
	$CI->db->where(array('user_id'=>$id,'type'=>'open'));
	$query = $CI->db->get('st_availability');
	$result =  $query->row();

	$mintime = getTimeInSeconds($result->mintime);

	$CI->db->select('extra_hrs');    	
	$CI->db->where(array('id'=>$id));
	$query = $CI->db->get('st_users');
	$result =  $query->row();

	if ($mintime >= 3600 * intval($result->extra_hrs)) $mintime -= 3600 * intval($result->extra_hrs);
	else $mintime = 0;
	return convertSecToTime($mintime);
}

function getAfterExtraHrs($id) {
	$CI =& get_instance();
	$CI->db->select('MIN(starttime) as mintime,(SELECT MAX(endtime) FROM st_availability WHERE type="open" AND user_id="'.$id.'") as maxtime');    	
	$CI->db->where(array('user_id'=>$id,'type'=>'open'));
	$query = $CI->db->get('st_availability');
	$result =  $query->row();

	$maxtime = getTimeInSeconds($result->maxtime);

	$CI->db->select('extra_hrs');    	
	$CI->db->where(array('id'=>$id));
	$query = $CI->db->get('st_users');
	$result =  $query->row();

	if ($maxtime <= 3600 * (23 - intval($result->extra_hrs))) $maxtime += 3600 * intval($result->extra_hrs);
	else $maxtime = 3600 * 23;
	return convertSecToTime($maxtime);
}

function getTextColorFromBgColor($hexColor)
{
	// hexColor RGB
	$R1 = hexdec(substr($hexColor, 1, 2));
	$G1 = hexdec(substr($hexColor, 3, 2));
	$B1 = hexdec(substr($hexColor, 5, 2));

	// Black RGB
	$blackColor = "#000000";
	$R2BlackColor = hexdec(substr($blackColor, 1, 2));
	$G2BlackColor = hexdec(substr($blackColor, 3, 2));
	$B2BlackColor = hexdec(substr($blackColor, 5, 2));

		// Calc contrast ratio
		$L1 = 0.2126 * pow($R1 / 255, 2.2) +
			0.7152 * pow($G1 / 255, 2.2) +
			0.0722 * pow($B1 / 255, 2.2);

	$L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
			0.7152 * pow($G2BlackColor / 255, 2.2) +
			0.0722 * pow($B2BlackColor / 255, 2.2);

	$contrastRatio = 0;
	if ($L1 > $L2) {
		$contrastRatio = (int)(($L1 + 0.05) / ($L2 + 0.05));
	} else {
		$contrastRatio = (int)(($L2 + 0.05) / ($L1 + 0.05));
	}

	// If contrast is more than 5, return black color
	if ($contrastRatio > 5) {
		return 0;
	} else { 
		// if not, return white color.
		return 1;
	}
}
