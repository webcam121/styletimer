<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// for check API Key valid or not
function checkApikey($key="")
{
	return ($key==API_KEY)?true:false; 
}

// basic form validation like device-id, device type
function basicFromValidation()
{
	$CI =& get_instance();
	$CI->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
	$CI->form_validation->set_rules('device_type', 'Device Type', 'required|is_natural_no_zero|less_than[3]|trim');
	$CI->form_validation->set_rules('api_key', 'Key', 'required|trim');
	$CI->form_validation->set_rules('access_token', 'Access Token', 'required|trim');
}

// show error message of form validation  
function validationErrorMsg()
{
	
	$CI =& get_instance();
	$fetch =   $CI->form_validation->error_array();
	foreach($fetch as $eval)
		$msg = $eval;
	
	$response_data = array();  
	$response_data["status"] = 0; 
	$response_data["response_message"] = $msg; 
		
	echo json_encode($response_data);
	exit();
}

// check yes or no 
function check($table,$where)
{
	$CI =& get_instance();
	$CI->db->select('count(id) as count');
	$CI->db->where($where); 	
    $q = $CI->db->get($table);
    $result = $q->row();
	return $result->count;
}

//----------------------------------------------------------------------
// use for fast-debuging if no use can delete any time lastquery(), preprint(),sessiondata() 
function lastquery($die="")
{
	 $CI =& get_instance();
	 echo $CI->db->last_query(); 
	 if($die!="")
	 die;
}
function preprint($data="")
{
	 echo "<pre>"; print_r($data); die;
}

function sessiondata()
{
	 $CI =& get_instance();
	 echo "<pre>"; print_r($CI->session->userdata()); die;
}
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
