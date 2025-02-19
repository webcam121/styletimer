<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller {

    function __construct(){
        // Construct the parent class
        parent::__construct();
        $this->load->library('email');
        $this->load->model('api_model');  // one model for all api 
        $this->load->helper('api_helper');
        $this->lang->load('push_notification','german');
        $this->lang->load('api_res_msg','german');
        $this->lang->load('ion_auth','german');
        $this->lang->load('salon_dashboard','german');

    }

    public $response_data = array('status' => 0 ,'access_token' => '', 'response_message' => '','data'=>[]);

    // Generate access token once seller
	function generate_access_token_post()
	{   
		$status = 0;
		$response_message = '';
		$access_token = '';
 
		//~ $lang = ($this->input->post('lang') !='')?$this->input->post('lang'):'english';
		//~ $this->config->set_item('language', $lang);

        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|is_natural_no_zero|less_than[3]|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		
		
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			$device_id = $this->input->post('device_id', TRUE);
			$device_type = $this->input->post('device_type', TRUE);
			
			
			if(checkApikey($this->input->post('api_key')))
			{
				// check for already registered access token against this device_id
				$is_valid_user = $this->api_model->access_token_check('', $device_type, $device_id, '');

				// if there is no access token then delete all access token for this device_id
				if(!empty($is_valid_user->access_token)){			
					// delete access token
					$this->api_model->access_token_delete('', $device_id);
				}
				
				// bind new access token
				$access_token = md5($device_id . time());
				// save new access token
				$is_saved = $this->api_model->saveAccessToken($device_id, $device_type, $access_token);

				if(!empty($is_saved)){
					$status = 1;
					$response_message = "Success";
				} else {
					$response_message = "Something is wrong."; //$this->lang->line('temprery_err');
				}

				$this->response_data['status'] = $status;
				$this->response_data['response_message'] = $response_message;
				$this->response_data['access_token'] = $access_token;
				
		    }
		    else 
		    {
		    	$this->response_data['response_message'] = INVALID_API_KEY;	
			}
        }  
		echo json_encode($this->response_data);
	}

	// user registration
	function user_registration_post() 
    {

    	$status =0;	
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('first_name', 'Frist Name', 'required|trim');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'valid_email|required|trim');
		$this->form_validation->set_message('email', 'This email is already registered');
		
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|trim');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('birth_date', 'Date of Birth', 'required|trim');
		// $this->form_validation->set_rules('country', 'Country', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg(); 
			
		}else
		{    
			extract($_POST);
			if(checkApikey($this->input->post('api_key')))
			{

			
				  if(isset($newsletter)){
				  	if($newsletter =='' || $newsletter ==0)
				  		$newsletter = 0;
				  	else
				  		$newsletter = 1;
				  }
				  else
				  	$newsletter = 0;

				  if(isset($service_email)){
				  	if($service_email =='' || $service_email ==0)
				  		$service_email = 0;
				  	else
				  		$service_email = 1;
				  }
				  else
				  	$service_email = 0;

				  		if(!empty($how_toknow))
				  			$how_toknow = $how_toknow;
				  		else
				  			$how_toknow="";
				  			
				  			
				  	$ress=$this->api_model->getWhereRowSelect('st_users',array('email' => $email,'status !='=>'deleted'),'id,status');		
			         if(!empty($ress->id)){
						 $this->response_data['status'] = 0;
				         $this->response_data['response_message'] = 'The email already registered.';
						 }
			        else{
					$Insert_Data["first_name"] = $first_name; 
					$Insert_Data["last_name"] = $last_name; 
					$Insert_Data["email"] = $email; 
					$Insert_Data["country"] = $country ? $country : ''; 
					$Insert_Data["gender"] = '';
					$Insert_Data["password"] = $this->ion_auth_model->hash_password($password); 
					$Insert_Data["access"] = 'user';
					$Insert_Data["created_on"] = date("Y-m-d");
					$Insert_Data["status"] ='inactive';
					$Insert_Data["activation_code"] = encryptPass($email);
					$Insert_Data['reg_from'] = $device_type;
					$Insert_Data['dob'] = date('Y-m-d',strtotime($birth_date));
					$Insert_Data['newsletter'] = $newsletter;
					$Insert_Data['service_email'] = $service_email;
					$Insert_Data['reffrel_code'] = $how_toknow;
					$Insert_Data['notification_status'] = 1;
					$actv=encryptPass($email);
					$user_id = $this->api_model->insert('st_users', $Insert_Data);
						if($user_id){
						
							$message = $this->load->view('email/user_activtion_link',array('link'=>base_url("auth/activate/$user_id/$actv"), "name"=>ucwords($this->input->post('first_name')),"button"=>"ACTIVATE ACCOUNT", "msg"=>"This message has been sent to you by StyleTimer. Click on the link below to Activate your account."), true);
							$mail = emailsend($email,'styletimer - Account aktivieren',$message,'styletimer');
						$this->response_data['status'] = 1;
						$this->response_data['response_message'] = "you are registered successfully";
						
						}else{
						$this->response_data['status'] = 0;
						$this->response_data['response_message'] = "Something is wrong please try again";
						}
			  }
			}else{
				$this->response_data['status'] = $status;
				$this->response_data['response_message'] = INVALID_API_KEY;
			}
			echo json_encode($this->response_data);
        }
    }

    function user_login_post(){
	    basicFromValidation();
		$this->form_validation->set_rules('identity', 'Email', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		//$this->form_validation->set_rules('access', 'Access', 'required|trim');


		
		if($this->form_validation->run() == FALSE)
		{
			  validationErrorMsg();
		}
		else
		{
		 	if(checkApikey($this->input->post('api_key')))
			{
				
			
				extract($_POST);
				$uid=null;
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);
				
				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['status'] = 0;
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else {

					$user = $this->api_model->login($identity);
					if($user)
					{  

						$user = $user[0];
						$uid = $user->id;  
						$password=$this->ion_auth_model->verify_password($password, $user->password, $user->email);
						if($password === TRUE)
						{
							if($user->status == 'active')
							{
								if($user->access == 'user')
								{
									$this->api_model->update_uid_AccessToken($user->id, $device_id, $device_type, $access_token);
									$this->response_data['status'] = 1; 
									$this->response_data['response_message'] = "success";
									$this->response_data['data']=$user;
									$this->api_model->delete('st_login_attempts',array('login' => $identity));
								}
								else if($user->access == 'employee'){
									$this->response_data['response_message'] = "Als Mitarbeiter kannst du dich hier leider nicht anmelden.";
								}
								else
									$this->response_data['response_message'] = "Als Salon kannst du dich hier leider nicht anmelden.";

								
							}
							else
								$this->response_data['response_message'] = "Your account is ".$user->status;
						}
						else
							$this->response_data['response_message'] = $this->lang->line('login_unsuccessful');
							
					}
					else
						$this->response_data['response_message'] = $this->lang->line('login_unsuccessful');
						
					if($this->response_data['status']==0){
						$this->response_data['inactive']='0';
						$ins_arry=array();
						$ins_arry['ip_address']=  $this->input->ip_address();
						$ins_arry['login']= $identity; 
						$ins_arry['time']= time();
						$this->api_model->insert('st_login_attempts',$ins_arry);
						$ress=$this->api_model->getWhereRowSelect('st_users',array('email' => $identity,'status !='=>'deleted'),'activation_code,status,(SELECT count(id) FROM st_login_attempts WHERE login="'.$identity.'") as login_count');
						//print_r($ress);
						if(!empty($ress->activation_code) && $ress->status =="inactive"){
							$this->response_data['inactive']='1';
						
						}
						if(!empty($ress->login_count))
						{
							$this->response_data['data'] = $ress->login_count;
						}else{
							$this->response_data['data'] = 0;
							}
					}
						
				}
				
			}
			else
			{
				$this->response_data['status'] = 0;
				$this->response_data['response_message'] = INVALID_API_KEY;
			}// api key check 
			echo json_encode($this->response_data);
        }
	 }

	// forgot password open  -----------------------------------------
	public function forgot_password_post()
	{
		/*$response_data = $data = $update_data= array();
		$status = 0;
		$response_message ="";*/
        
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');		

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{   
			extract($_POST); 
			  
			  // email check  
			  $email_check = check('st_users',array('email'=>$email,'access' => 'user'));  // check mobile no. is unique  
			  if($email_check)
			  {
					$this->load->model('ion_auth_model'); 
					$forgotten = $this->ion_auth_model->forgotten_password($email);

					$identity=$this->api_model->getWhereRowSelect('st_users',array('email' => $email),'first_name,email,forgotten_password_code,socialtype');

					if ($identity->socialtype){
						$this->response_data['response_message']='Bei Anmeldung über Google oder Apple-ID ist ein Zurücksetzen des Passworts leider nicht möglich';			
					}else {
						$identity=$this->api_model->getWhereRowSelect('st_users',array('email' => $email),'first_name,email,forgotten_password_code');

						$message = $this->load->view('email/forgot_password',array('link'=>base_url("auth/reset_password/$identity->forgotten_password_code"), "name"=>ucwords($identity->first_name), "button"=>"Passwort zurücksetzen", "msg"=>"This message has been sent to you by StyleTimer. Click on the link below to reset your account password."), true);
						$mail = emailsend($identity->email,'styletimer - Passwort zurücksetzen',$message,'styletimer');

						$this->response_data['status'] = 1;
						$this->response_data['response_message']='Check your email address. Password reset link has been sent.';
					}
			  }
			  else 
				 $this->response_data['response_message']='Please enter the registered email';    	   

		} // form validation 
		
		// send response  
		echo json_encode($this->response_data); 			
	}// function close 
	// forgot password close  ------------------------------------------

	public function change_password_post()
	{
		$response_data = $data = $update_data= array();
		$status = 0;
		$response_message ="";
        
		basicFromValidation();
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('password', 'password', 'required|trim|min_length[6]');
		/*$this->form_validation->set_rules('old_password', 'old password', 'required|trim');*/
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');				

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{   
			
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
				extract($_POST); 
				//$uid = (isset($uid))?$uid:'';
				$uid=(!empty($uid))?$uid:'';
				$old_password=(!empty($old_password))?$old_password:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						  $this->api_model->access_token_update_time($uid, $device_id, $access_token);
						
						  // change password  
						  if(!empty($old_password))
						  		$old_pwd_check =  $this->ion_auth_model->hash_password_db($uid,$old_password); // old password check 
						  else
						  		$old_pwd_check = TRUE;

						  if($old_pwd_check)
						  {
							  $update_data['password'] = $this->ion_auth_model->hash_password($password); 
							  $result = $this->api_model->update('st_users',array('id'=>$uid),$update_data);
							  if($result)
							  {
									$this->response_data['status']=1;
									$this->response_data['response_message']='Password changed successfully'; 
							  }
						  }
						  else 
							 $this->response_data['response_message']='Old password does not matched';    	   

					 }// token expire 

				}// token valid 

			 }// api key check 		

		} // form validation 
		
		// send response  
			
		echo json_encode($this->response_data);   			
	}// function close 
	// change password close  ------------------------------------------

	function update_user_reminder_time_post() {
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('reminder_duration', 'Reminder Duration', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else {
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else {
				extract($_POST);

				$uid = (isset($uid))?$uid:'';
				if($this->api_model->update('st_users',array('id'=>$uid,'access'=> 'user'),[
					'extra_hrs' => $reminder_duration
				])){						
					$this->response_data['status']=1;
					$this->response_data['response_message']='success';
					
				}
				else{
					$this->response_data['response_message']='There is some technical error.';
				}
			}
		}
		echo json_encode($this->response_data); 
	}

	function get_user_reminder_time_post() {
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else {
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else {
				extract($_POST);

				$uid = (isset($uid))?$uid:'';
				$user=$this->api_model->getWhereRowSelect('st_users',array('id' => $uid,'access' => 'user'),'extra_hrs');
				if(!empty($user)){
					$this->response_data['status']=1;
					$this->response_data['response_message']='success';
					$this->response_data['duration']=$user->extra_hrs;
				}
				else
					$this->response_data['response_message']="No data found";
			}
		}
		echo json_encode($this->response_data); 
	}

	function user_profile_post(){
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('action', 'action', 'required|trim|in_list[view,edit]');
		if(isset($_POST['action']) &&  $_POST['action'] == 'edit'){

			$this->form_validation->set_rules('first_name', 'Frist Name', 'required|trim');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
			//$this->form_validation->set_rules('telephone', 'Telephone', 'required|trim');
			//$this->form_validation->set_rules('location', 'Location', 'required|trim');
			//$this->form_validation->set_rules('latitude', 'Latitude', 'required|trim');
			//$this->form_validation->set_rules('longitude', 'Longitude', 'required|trim');
			//$this->form_validation->set_rules('country', 'Country', 'required|trim');
			//$this->form_validation->set_rules('city', 'City', 'required|trim');
			$this->form_validation->set_rules('birth_date', 'Date of Birth', 'required|trim');
			//$this->form_validation->set_rules('post_code', 'Postal Code', 'required|trim');
			//$this->form_validation->set_rules('gender', 'Gender', 'required|trim');
			//$this->form_validation->set_rules('gender', 'Gender', 'required|trim|in_list[male,female,other]');
		}


		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
				
				extract($_POST);
				extract($_FILES);
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else{
					 
					 if($action == 'edit'){

						extract($_POST);
			        	$insertArr=array();
			        	$insertArr['first_name']=$first_name;
					  	$insertArr['last_name']=$last_name;
					  	
					  	if(isset($telephone))
					  	$insertArr['mobile']=$telephone;
					  	
				      	if(isset($location))
				      	$insertArr['address']=$location;
				      	
				      	if(isset($country))
				      	$insertArr['country']=$country;
				      	
				      	if(isset($city))
					  	$insertArr['city']=$city;
					  	
					  	if(isset($post_code))
				      	$insertArr['zip']=$post_code;
				      	//$insertArr['newsletter']=$newsletter;
				      	
				      	if(isset($latitude))
				      	$insertArr['latitude']=$latitude;
				      	
				      	if(isset($longitude))
			            $insertArr['longitude']=$longitude;
			            
			            if(isset($gender))
				      		$insertArr['gender']=$gender;
				      	$insertArr['dob']=date('Y-m-d',strtotime($birth_date));
						$insertArr['updated_on']=date('Y-m-d H:i:s');
						$insertArr['updated_by']=$uid;

						$path = 'assets/uploads/users/'.$uid.'/';
								
						if(isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name']!="")
							{
								$allowed =  array('gif','png' ,'jpg','jpeg','svg');
								$filename = explode('.',$_FILES["profile_pic"]["name"]);
								$ext = $filename[count($filename)-1];
								$ext = strtolower($ext);
								
								if($ext=='webp'){
									$allowed =  array('*');
									}
									
								if(!is_dir($path)){ @mkdir($path ,0777,TRUE);}

								if(isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name']!="" && (!in_array($ext,$allowed)  || $_FILES["profile_pic"]["size"] > 4000000)) 
									{
										$this->response_data['response_message']='There is some technical error.';
										die;
									}

								$this->load->library('Image_moo');
									$filename = 'Prf_'.time().$uid.'.'.$filename[count($filename)-1];
									$tmpfile = $_FILES["profile_pic"]["tmp_name"];
									$uploadfil = move_uploaded_file($tmpfile, $path.$filename);
									
									foreach(array("thumb_"=>array(250,250),"icon_"=>array(115,115)) as $key=>$val){
										$this->image_moo->load($path.$filename)->set_jpeg_quality(100)->resize_crop($val[0], $val[1])->save("{$path}{$key}{$filename}",true);
										
									}
									$insertArr['profile_pic']=$filename;
									
								$filepath3 = $path.'thumb_'.$filename;
								$filepath2 = $path.'icon_'.$filename;
								$filepath1 = $path.$filename;
										
								if(strtolower($ext)!='webp'){
									/*****************************************/
										$image1 = imagecreatefromstring(file_get_contents($filepath1));
										ob_start();
										imagejpeg($image1,NULL,100);
										$cont1 = ob_get_contents();
										ob_end_clean();
										imagedestroy($image1);
										$content1 = imagecreatefromstring($cont1);
											
									   $output1 = $filepath1.'.webp';
									   
									   imagewebp($content1,$output1);
									   imagedestroy($content1);
									   
								 /*****************************************/   
							
									   $image2 = imagecreatefromstring(file_get_contents($filepath2));
										ob_start();
										imagejpeg($image2,NULL,100);
										$cont2 = ob_get_contents();
										ob_end_clean();
										imagedestroy($image2);
										$content2 = imagecreatefromstring($cont2);
											
									   $output2 = $filepath2.'.webp';
									   
									   imagewebp($content2,$output2);
									   imagedestroy($content2);
									   
									/*****************************************/
									
									  $image3 = imagecreatefromstring(file_get_contents($filepath3));
										ob_start();
										imagejpeg($image3,NULL,100);
										$cont3 = ob_get_contents();
										ob_end_clean();
										imagedestroy($image3);
										$content3 = imagecreatefromstring($cont3);
										
									   $output3 = $filepath3.'.webp';
									   
									   imagewebp($content3,$output3);
									   imagedestroy($content3);
									
								
									 // $uploadPath = "assets/uploads/banners/{$uid}/webp";
									 // $uploadPath1 = "assets/uploads/banners/{$uid}/other";
									}
								else{
									   $content1 = imagecreatefromwebp($filepath1);
									   $output1 = $filepath1.'.png';
										// Convert it to a jpeg file with 100% quality
										imagepng($content1, $output1);
										imagedestroy($content1);
									
									/*************************************************************/
									
									   $content2 = imagecreatefromwebp($filepath2);
									   $output2 = $filepath2.'.png';
										// Convert it to a jpeg file with 100% quality
										imagepng($content2, $output2);
										imagedestroy($content2);
									
								 /*************************************************************/
								 
									 $content3 = imagecreatefromwebp($filepath3);
									   $output3 = $filepath3.'.png';
										// Convert it to a jpeg file with 100% quality
										imagepng($content3, $output3);
										imagedestroy($content3);
										
									}	
									
									
						

						}

						if($this->api_model->update('st_users',array('id'=>$uid,'access'=> 'user'),$insertArr)){
							 	
							 	//if(isset($_POST['old_image'])!=''){	
							 		if(!empty($_POST['old_image'])!=''){
											if (file_exists($path.$_POST['old_image']))  
											{ 
											    unlink($path.$_POST['old_image']);
											    unlink($path.'icon_'.$_POST['old_image']);
											    unlink($path.'thumb_'.$_POST['old_image']); 
											} 
										}
									

							$this->response_data['status']=1;
							$this->response_data['response_message']='Profil erfolgreich aktualisiert.';
			        		
			        	}
						else{
							$this->response_data['response_message']='There is some technical error.';
						}


					}

					$user=$this->api_model->getWhereRowSelect('st_users',array('id' => $uid,'access' => 'user'),'id,first_name,last_name,email,mobile,profile_pic,address,zip,newsletter,country,city,latitude,dob,longitude,gender');
						if(!empty($user)){
							$this->response_data['status']=1;
							if($action == 'view'){ $this->response_data['response_message']='success'; }
							$this->response_data['data']=$user;
							$this->response_data['image_path']= base_url().'assets/uploads/users/';
						}
						else
							$this->response_data['response_message']="Not data found";

				
				}



			}   
		}

		echo json_encode($this->response_data); 
	}

	function category_post(){

		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
  				$main_menu=get_menu();
  				$i=0;
  				foreach($main_menu as $menu){
           		 $this->response_data['data'][$i]=$menu;
           		 $submenu=get_filtersub_menu($menu->id);
           		 $this->response_data['data'][$i]->sub_category=$submenu;
               	 $i++;
      			}	
      			$this->response_data['status']=1;
				$this->response_data['response_message']='Success.';
      	  }
      	}
   	      echo json_encode($this->response_data);  	
  	
  	}
	  
	function fetch_salon_gallery_data_post() {
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
                $this->response_data['response_message'] = INVALID_API_KEY;
			else {
				// $mid=(!empty($_POST['farm_id']))?$_POST['farm_id']:0;
				$mid=(!empty($_POST['salon_id']))?$_POST['salon_id']:0;
				$gbannerdata = $this->api_model->select('st_gallery_banner_images', '*', array('merchant_id' => $mid));
				if(!empty($gbannerdata)){
					$this->response_data['data']=$gbannerdata;
					$this->response_data['status']=1;
					$this->response_data['response_message']='Success';
				}
				else
					$this->response_data['response_message']='No record found.';
			
			}
		}
		echo json_encode($this->response_data);
	}

	function fetch_salon_gallery_item_post() {
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
                $this->response_data['response_message'] = INVALID_API_KEY;
			else {
				try {
					$id=(!empty($_POST['item_id']))?$_POST['item_id']:0;
					$gal = $this->api_model->select_row('st_gallery_banner_images', '*', array('id' => $id));
					$emp = $this->api_model->select_row('st_users','*', array('id' => $gal->employee_id));
					$cat = $this->api_model->select_row('st_merchant_category','*', array('id' => $gal->category_id));
					$sub = $this->api_model->select_row('st_category','*', array('id' => $cat->subcategory_id));
					$mcat = $this->api_model->select_row('st_category','*', array('id' => $cat->category_id));
				
					$employee_avatar = $emp->profile_pic !='' ? (base_url('assets/uploads/employee/').$emp->id.'/'.$emp->profile_pic) : (base_url('assets/frontend/images/user-icon-gret.svg'));
					$employee_id = $emp->id;
					$employee_name = $emp->first_name;

					$rate = $this->api_model->custome_query('SELECT AVG(rate) as reviewrate FROM st_review WHERE emp_id='.$emp->id, 'result');
					$rate = $rate[0]->reviewrate ? $rate[0]->reviewrate : 0;
					$rate = number_format($rate, 1);
					$rcnt = $this->api_model->custome_query('SELECT COUNT(rate) as cnt FROM st_review WHERE emp_id='.$emp->id, 'result');
					$rcnt  = $rcnt ? ($rcnt[0]->cnt . ' Bewertungen') : 'noch keine Bewertungen';

					$service_id = $cat->id;
					$service_name = $cat->name ? ($sub->category_name . ' - ' . $cat->name) : $sub->category_name;
					$service_img = getimge_url('assets/uploads/category_icon/'.$mcat->id.'/',$mcat->icon,'png');
					$service_duration = $cat->duration;
					$service_price = $cat->price;

					$this->response_data['data']['service_id'] = $service_id;
					$this->response_data['data']['service_img'] = $service_img;
					$this->response_data['data']['service_name'] = $service_name;
					$this->response_data['data']['service_duration'] = $service_duration;
					$this->response_data['data']['service_price'] = $service_price;
					$this->response_data['data']['rate'] = $rate;
					$this->response_data['data']['count'] = $rcnt;
					$this->response_data['data']['employee_id'] = $employee_id;
					$this->response_data['data']['employee_name'] = $employee_name;
					$this->response_data['data']['employee_avatar'] = $employee_avatar;
					$this->response_data['status']=1;
					$this->response_data['response_message']='Success';

				} catch (Exception $e) {
					$this->response_data['response_message']='Something went wrong.';
				}
			}
		}
		echo json_encode($this->response_data);
	}

    function salon_listing_post(){
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
                $this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
				$uid=(!empty($_POST['uid']))?$_POST['uid']:0;
			    if(empty($_POST['subcatgory']) && empty($_POST['main_category'])){
				    $response_data = array('status' => 0, 'response_message' =>'Please select category or sub category');
				    echo json_encode($response_data);
				    die;
			    }

                $lat='';
                $lng='';
                $distance=20;
                $pagination="";
                if(!empty($_POST['lat'])) $lat=$_POST['lat'];
                if(!empty($_POST['lng'])) $lng=$_POST['lng'];
                if(!empty($_POST['distance'])) $distance=$_POST['distance'] * 1.60934;
        
                // sorting from here
                if(!empty($_POST['orderby']) && $_POST['orderby']!='distance asc') $order=$_POST['orderby'];         
                elseif(!empty($_POST['orderby']) && $_POST['orderby']=='distance asc' && !empty($lat) && !empty($lng)) $order=$_POST['orderby'];                 
                elseif(!empty($lat) && !empty($lng)) $order="distance ASC";        
                else $order="st_users.id DESC";
            
        
                //$order="distance ASC";
                
                //echo $dayName;
                $where="";
                if(!empty($_POST['subcatgory']))
                {  
                    $mdat = explode('-', $_POST['subcatgory']);
                    //$subcat=implode(',',$_POST['sucatgory']);
                    //$where=$where." AND subcategory_id IN(".$subcat.")";
                    $where=$where." AND category_id =".$mdat[0];
                    $where=$where." AND filtercat_id =".$mdat[1];
                }
                if(!empty($_POST['main_category'])){
                    $where=$where." AND category_id =".$_POST['main_category'];
                }
                    
                //if(!empty($_POST['startrange'])){
                if(isset($_POST['startrange'])){
                    $where=$where." AND ((discount_price!=0 AND discount_price >=".$_POST['startrange'].") OR(discount_price=0 AND price >=".$_POST['startrange']."))";
                } 
                    
                //if(!empty($_POST['endrange'])){
                if(isset($_POST['endrange'])){
                    $where=$where." AND ((discount_price!=0 AND discount_price<=".$_POST['endrange'].") OR(discount_price=0 AND price <=".$_POST['endrange']."))";
                }
                    
                if(!empty($_POST['date']) && $_POST['date']!="anydate"){ 
                    $_POST['date'] = date('Y-m-d', strtotime($_POST['date']));

                    if (
                        empty($_POST['time']) ||
                        strtolower($_POST['time']) == 'anytime' ||
                        strtolower($_POST['time']) == 'beliebig'
                    ) {
                        // $holidaay = $this->db
                        //     ->query(
                        //         'SELECT `id` FROM st_national_holidays WHERE `date`="' .
                        //             $_POST['date'] .
                        //             '"'
                        //     )
                        //     ->row();

                        // if (!empty($holidaay)) {
                            $_POST['starttime'] = '00:00:00';
                            $_POST['endtime'] = '23:00:00';
                            $_POST['time'] = '00:00-23:00';
                        // }
                    }

                    $dayName = date('l', strtotime($_POST['date']));
                    $dayName = strtolower($dayName);
                }

                if(!empty($_POST['expess_offer']) && $_POST['expess_offer']=='yes'){
                    $dayName = date('l', strtotime(date('H:i:s')));
					$_POST['date'] = date('Y-m-d');
                    $_POST['starttime'] = date('H:i:s');
                    $_POST['endtime'] = date('H:i:s', strtotime('2 hour'));			 
                }

                $cur_date=date('Y-m-d H:i:s');
                $sql1="SELECT `st_merchant_category`.`id`,`subcategory_id`,`st_merchant_category`.`created_by`,`st_users`.`id`,`st_users`.`allow_online_booking`
                FROM `st_merchant_category` JOIN `st_users` ON 
                `st_merchant_category`.`created_by`=`st_users`.`id` 
                WHERE st_users.online_booking= 1 AND st_merchant_category.online=1 AND (st_users.end_date > '".$cur_date."' OR st_users.allow_online_booking = 'true') 
                AND `st_merchant_category`.`status` = 'active' ".$where." GROUP BY `st_merchant_category`.`created_by`";

                $query1=$this->db->query($sql1);
                $users=$query1->result();

                $userId=[];
                $usersForAll = [];

                if (!empty($users)) {
                    foreach ($users as $us) {
                        $userId[] = $us->created_by;
                    }
                }

                if(!empty($userId)){
                    $sdate = '';
                    $edate = '';
                    $stime = '';
                    $etime = '';
                    
                    if (!empty($_POST['date']) && $_POST['date'] != 'anydate') {
                        $date = date('Y-m-d', strtotime($_POST['date']));
        
                        if (!empty($_POST['starttime'])) {
                            $sdate =
                                $date .
                                ' ' .
                                date('H:i:s', strtotime($_POST['starttime']));
                            $stime = date('H:i:s', strtotime($_POST['starttime']));
                        } else {
                            $sdate = $date . ' 00:00:00';
                            $stime = '00:00:00';
                        }
                        if (!empty($_POST['endtime'])) {
                            $edate =
                                $date .
                                ' ' .
                                date('H:i:s', strtotime($_POST['endtime']));
                            $etime = date('H:i:s', strtotime($_POST['endtime']));
                        } else {
                            $edate = $date . ' 23:00:00';
                            $etime = '23:00:00';
                        }
                    }

                    if (
                        !empty($_POST['expess_offer']) &&
                        $_POST['expess_offer'] == 'yes'
                    ) {
                        $sdate = date('Y-m-d H:i:s');
                        $edate = date('Y-m-d H:i:s', strtotime('2 hour'));
                        $stime = date('H:i:s');
                        $etime = date('H:i:s', strtotime('2 hour'));
                    }

                    if (
                        (!empty($_POST['date']) && $_POST['date'] != 'anydate') ||
                        (!empty($_POST['expess_offer']) &&
                            $_POST['expess_offer'] == 'yes')
                    ) {
                        $tdate = date('Y-m-d', strtotime($_POST['date']));
        
                        $blockEmployees = [];
        
                        $userIdTemp = $userId;
                        $userId = [];
                        foreach($userIdTemp as $eachUser) {
                            $avtime = "SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$eachUser."";
                            $times = $this->api_model->custome_query($avtime,'row');

                            if (!empty($times)) {
								$curtime = date('H:i:s');
                                $start = $times->starttime; //you can write here 00:00:00 but not need to it
								if ($stime > $start) {
									$start = $stime;
								}
								if ($start < $curtime && $tdate == date('Y-m-d')) {
									$start = $curtime;
								}
                                $end   = $times->endtime;
								if ($etime < $end) {
									$end = $etime;
								}
								if (($end >= $curtime && $tdate == date('Y-m-d')) || $tdate != date('Y-m-d')) {
									$tStart = strtotime($start);
									$tEnd   = strtotime(date('H:i:s',strtotime($end. "-15 minutes")));
									$tNow     = $tStart;
				
									$canbook = 0;
									while($tNow <= $tEnd){ 
														
										$nowTime = date("H:i:s",$tNow);								
										
				
										$timeArray        = array();           
										$timeArray[0] = new \stdClass();
										$timeArray[0]->start = $tdate." ".$nowTime;
										$timeArray[0]->end = date('Y-m-d H:i:s',strtotime($timeArray[0]->start.' +15 minute'));
										
										// echo '['.$timeArray[0]->start.','.$timeArray[0]->end.']';

										$resultCheckSlot = checkTimeSlots($timeArray,'',$eachUser,15);
										if($resultCheckSlot)
										{
											$canbook = 1;
											break;
										}
				
										$tNow = strtotime('+15 minutes',$tNow); 
									}
				
									if ($canbook == 1) {
										$userId[] = $eachUser;
									}
								}
                            }
                        }
                        $usersImp = count($userId) > 0 ? implode(',' , $userId) : "0";
                        $userIds = [];

                        $whereSub = '';

                        $blockEmployeesImp = count($blockEmployees) ? implode(", ", $blockEmployees) : "0";

                        $sqlForMerchant =
                            "SELECT `st_users`.`merchant_id`, count(st_service_employee_relation.user_id) AS employees 
                            FROM st_users 
                            INNER JOIN st_service_employee_relation ON st_service_employee_relation.user_id=st_users.id 
                            WHERE `st_users`.`status`='active' AND online_booking=1 AND `st_users`.`merchant_id` IN (${usersImp})
                                AND st_service_employee_relation.user_id NOT IN (${blockEmployeesImp}) 
                                ${whereSub} 
                            GROUP BY merchant_id
                            HAVING employees > 0";

                        $merchants = $this->db->query($sqlForMerchant)->result();

                        if (!empty($merchants)) {
                            foreach ($merchants as $merchant) {
                                $userIds[] = $merchant->merchant_id;
                            }
                        }

                        $usersForAll = $userIds;

                        if (!empty($_POST['sucatgory'])) {
                            $userIds = [];

                            $subcat = implode(',', $_POST['sucatgory']);
                            $whereSub = ' AND subcat_id IN(' . $subcat . ')';

                            $sqlForMerchant =
                                "SELECT `st_users`.`merchant_id`, count(st_service_employee_relation.user_id) AS employees 
                                FROM st_users 
                                INNER JOIN st_service_employee_relation ON st_service_employee_relation.user_id=st_users.id 
                                WHERE `st_users`.`status`='active' AND online_booking=1 AND `st_users`.`merchant_id` IN (${usersImp})
                                    AND st_service_employee_relation.user_id NOT IN (${blockEmployeesImp}) 
                                    ${whereSub} 
                                GROUP BY merchant_id
                                HAVING employees > 0";

                            $merchants = $this->db->query($sqlForMerchant)->result();

                            if (!empty($merchants)) {
                                foreach ($merchants as $merchant) {
                                    $userIds[] = $merchant->merchant_id;
                                }
                            }
                            $userId = $userIds;
                        } else {
                            $userId = $userIds;
                        }
                    } else {
                        $userIds = [];

                        $whereSub = '';
                        $usersImp = implode(',' , $userId);
                    
                        $sqlForMerchant =
                            "SELECT `merchant_id` 
                            FROM st_users 
                            INNER JOIN st_service_employee_relation ON st_service_employee_relation.user_id=st_users.id 
                            WHERE status='active' AND online_booking=1 AND merchant_id IN (${usersImp}) ${whereSub} 
                            GROUP BY merchant_id";

                        $merchantSql = $this->db->query($sqlForMerchant);
                        $merchants = $merchantSql->result();

                        if (!empty($merchants)) {
                            foreach ($merchants as $merchant) {
                                $userIds[] = $merchant->merchant_id;
                            }
                        }

                        $usersForAll = $userIds;

                        if (!empty($_POST['sucatgory'])) {
                            $userIds = [];

                            $subcat = implode(',', $_POST['sucatgory']);
                            $whereSub = ' AND subcat_id IN(' . $subcat . ')';

                            $sqlForMerchant =
                                "SELECT `merchant_id` 
                                FROM st_users 
                                INNER JOIN st_service_employee_relation ON st_service_employee_relation.user_id=st_users.id 
                                WHERE status='active' AND online_booking=1 AND merchant_id IN (${usersImp}) ${whereSub} 
                                GROUP BY merchant_id";

                            $merchantSql = $this->db->query($sqlForMerchant);
                            $merchants = $merchantSql->result();

                            if (!empty($merchants)) {
                                foreach ($merchants as $merchant) {
                                    $userIds[] = $merchant->merchant_id;
                                }
                            }

                            $userId = $userIds;
                        } else {
                            $userId = $userIds;
                        }
                    }
                }

                $totalCount = 0;
                $subcats = [];

                if (!empty($userId) || !empty($usersForAll)) {
                    $rowperpage = 10;
                    $usersImp = count($userId) > 0 ? implode(',', $userId) : "0";
                    $usersAllImp = count($usersForAll) > 0 ? implode(',', $usersForAll) : "0";

                    if (!empty($dayName)) {
                        $wherePeriod = '';
                        $whereDistance = '';

                        if (
                            !empty($_POST['starttime']) &&
                            !empty($_POST['endtime']) &&
                            $_POST['time'] != 'Anytime' &&
                            $_POST['time'] != 'anytime'
                        ) {
                            $wherePeriod =
                                ' AND ((starttime>="' .
                                $_POST['starttime'] .
                                '" AND starttime<="' .
                                $_POST['endtime'] .
                                '") OR (endtime>="' .
                                $_POST['starttime'] .
                                '" AND endtime<="' .
                                $_POST['endtime'] .
                                '") OR (starttime<="' .
                                $_POST['starttime'] .
                                '" AND endtime>="' .
                                $_POST['endtime'] .
                                '") OR (starttime>="' .
                                $_POST['starttime'] .
                                '" AND endtime<="' .
                                $_POST['endtime'] .
                                '"))';
                        }

                        if (!empty($lng) && !empty($lat)) {
                            $whereDistance = " AND ('6371' * acos( cos( radians($lat) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng)) + sin(radians($lat)) * sin( radians(`latitude`)))) < $distance";
                        }

                        $sqlForcount =
                            "SELECT count(st_users.id) as totalCount 
                            FROM `st_users` 
                            INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='${dayName}' 
                            WHERE `status` = 'active' AND `access` = 'marchant' 
                            AND `st_availability`.`days`='${dayName}' AND `st_availability`.`type`='open' 
                            AND st_users.id IN (${usersImp}) ${whereDistance} ${wherePeriod}";

                        $dataCount = $this->db->query($sqlForcount);
                        $count = $dataCount->row();
                        $totalCount = $count->totalCount;

                        $sqlForServices = 
                            "SELECT 
                                st_service_employee_relation.subcat_id, 
                                count(DISTINCT(st_users.id)) AS user_count
                                FROM st_users
                                INNER JOIN st_service_employee_relation ON st_service_employee_relation.created_by = st_users.id 
                                INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='${dayName}' 
                                WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersAllImp}) 
                                    AND `st_availability`.`days`='${dayName}' AND `st_availability`.`type`='open' 
                                    ${whereDistance} 
                                    ${wherePeriod}
                                GROUP BY st_service_employee_relation.subcat_id";

                        $query3 = $this->db->query($sqlForServices);
                        $subcats = $query3->result();

                        $offset = 0;

                        if ($_POST['page'] != 0) {
                            $offset = ($_POST['page'] - 1) * $rowperpage;
                        }

                        if (!empty($lng) && !empty($lat)) {
                            $sql =
                                "SELECT `st_users`.`id`, `slug`, `first_name`, `last_name`, `business_name`, 
                                        `business_type`, `address`, `latitude`, `longitude`, `country`, `city`, `zip`, 
                                        `about_salon`, `image`, `image1`, `image2`, `image3`, `image4`,
                                        (SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as rating,
                                        (SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='${uid}') as favourite,
                                        (SELECT count(id) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as totalcount,
                                        `st_availability`.`days`,
                                        ('6371' * acos( cos( radians(${lat}) ) * cos( radians(`latitude`)) * cos( radians(`longitude`) - radians(${lng})) + sin(radians(${lat})) * sin( radians(`latitude`)))) AS distance 
                                FROM `st_users` 
                                LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                                INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='${dayName}' 
                                WHERE `status` = 'active' AND `access` = 'marchant' 
                                    ${wherePeriod} 
                                    ${whereDistance}
                                    AND `st_availability`.`days`='${dayName}' 
                                    AND `st_availability`.`type`='open' AND st_users.id IN (${usersImp}) 
                                ORDER BY ${order}
                                LIMIT ${rowperpage} 
                                OFFSET ${offset}";
                        } else {
                            $sql =
                                "SELECT `st_users`.`id`, `slug`, `first_name`, `last_name`, `business_name`, 
                                    `business_type`, `address`,`latitude`, `longitude`, `country`, `city`, `zip`, 
                                    `about_salon`,`image`, `image1`, `image2`, `image3`, `image4`,
                                    (SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as rating,
                                    (SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='${uid}') as favourite,
                                    (SELECT count(id) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as totalcount,
                                    `st_availability`.`days` 
                                FROM `st_users` 
                                LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                                INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='${dayName}' 
                                WHERE `status` = 'active' AND `access` = 'marchant' 
                                    ${wherePeriod} 
                                    AND `st_availability`.`days`='${dayName}' 
                                    AND `st_availability`.`type`='open' AND st_users.id IN (${usersImp}) 
                                ORDER BY ${order} 
                                LIMIT ${rowperpage} 
                                OFFSET ${offset}";
                        }
                    } else {
                        $whereDistance = "";
                        if (!empty($lng) && !empty($lat)) {
                            $whereDistance = " AND ('6371' * acos( cos( radians($lat) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng)) + sin(radians($lat)) * sin( radians(`latitude`)))) < $distance";
                        }

                        $sqlForcount =
                            "SELECT count(st_users.id) as totalCount 
                            FROM `st_users` 
                            LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                            WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersImp}) ${whereDistance}";
                        
                        $dataCount = $this->db->query($sqlForcount);
                        $count = $dataCount->row();
                        $totalCount = $count->totalCount;

                        $usersAllImp = implode(',', $usersForAll);
                        $sqlForServices = 
                            "SELECT 
                                st_service_employee_relation.subcat_id, 
                                count(DISTINCT(st_users.id)) AS user_count
                                FROM st_users
                                INNER JOIN st_service_employee_relation ON st_service_employee_relation.created_by = st_users.id 
                                WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersAllImp}) ${whereDistance} 
                                GROUP BY st_service_employee_relation.subcat_id";

                        $query3 = $this->db->query($sqlForServices);
                        $subcats = $query3->result();

                        $offset = 0;

                        if ($_POST['page'] != 0) {
                            $offset = ($_POST['page'] - 1) * $rowperpage;
                        }

                        if (!empty($lng) && !empty($lat)) {
                            $sql =
                                "SELECT `st_users`.`id`, `slug`, `first_name`, `last_name`, `business_name`, `business_type`,
                                    `address`,`latitude`,`longitude`, `country`, `city`,`zip`, `about_salon`,
                                    `image`, `image1`, `image2`, `image3`, `image4`,
                                    (SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as rating,
                                    (SELECT count(id) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as totalcount,
                                    (SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='${uid}') as favourite,
                                    ('6371' * acos( cos( radians(${lat}) ) * cos( radians(`latitude`)) * cos( radians(`longitude`) - radians(${lng})) + sin(radians(${lat})) * sin( radians(`latitude`)))) AS distance 
                                FROM `st_users` 
                                LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                                WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersImp}) 
                                    ${whereDistance}
                                ORDER BY ${order}
                                LIMIT ${rowperpage}
                                OFFSET ${offset}";
                        } else {
                            $sql =
                                "SELECT `st_users`.`id`, `slug`, `first_name`, `last_name`, `business_name`, `business_type`, 
                                `address`,`latitude`,`longitude`, `country`, `city`, `zip`, `about_salon`, 
                                `image`, `image1`, `image2`, `image3`, `image4`, 
                                (SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) AS rating, 
                                (SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='${uid}') AS favourite,
                                (SELECT count(id) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) AS totalcount 
                            FROM `st_users` 
                            LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                            WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersImp}) 
                            ORDER BY ${order} 
                            LIMIT ${rowperpage} 
                            OFFSET ${offset}";
                        }
                    }

                    $query = $this->db->query($sql);
                    $this->data['usersdetail'] = $query->result();

                    if (!empty($this->data['usersdetail'])) {
                        $i = 0;

                        foreach ($this->data['usersdetail'] as $usr) {
                            $sql2 =
                                "SELECT `r`.`id`,`duration`, `price`,`price_start_option`, `buffer_time`, `discount_price`, `subcategory_id`, 
                                    `u1`.`category_name` AS `m_category`, 
                                    `u2`.`category_name` AS `s_category`,
                                    IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`r`.`id` AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND online_booking=1 AND status='active')>=1),'0') AS checkemp,
                                    (SELECT count(id) FROM st_merchant_category WHERE subcategory_id=r.subcategory_id AND created_by=r.created_by) AS totalservice 
                                FROM `st_merchant_category` `r` 
                                JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` 
                                JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` 
                                WHERE `r`.`status` = 'active' AND r.online=1 AND `r`.`created_by`='$usr->id'
                                $where 
                                GROUP BY `r`.`subcategory_id`
                                LIMIT 3";

                            $query2 = $this->db->query($sql2);
                            $this->data['usersdetail'][$i]->sercvices = $query2->result();
                            //echo $this->db->last_query();
                            $i++;
                        }
                        $this->response_data['data'] = $this->data['usersdetail'];
                        $this->response_data['status']=1;
                        $this->response_data['response_message']='success';
                        $this->response_data['total_count']=$totalCount;
                        $this->response_data['per_page']=$rowperpage;
                        $this->response_data['current_page']=$_POST['page'];  
                    } else {
                        $this->response_data['status']=0;
                        $this->response_data['response_message']='No record found..';    
                    }
                } else {
                    $this->response_data['status']=0;
                    $this->response_data['response_message']='No record found..';
                }
            }
        }
		echo json_encode($this->response_data); 
   }


   public function map_marker_post()
	{
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

			if(empty($_POST['subcatgory']) && empty($_POST['main_category'])){
				 $response_data = array('status' => 0, 'response_message' =>'Please select category or sub category');
				 echo json_encode($response_data);
				die;
			}

				    $lat="";
			        $lng="";
			        $distance=20;
			        //print_r($_POST); die;
			       if(!empty($_POST['lat'])) $lat=$_POST['lat'];
        		   if(!empty($_POST['lng'])) $lng=$_POST['lng'];
        		   if(!empty($_POST['distance'])) $distance=$_POST['distance']*1.60934;
        			$where="";
			        
			        if(!empty($_POST['subcatgory']))
			         {  
						$mdat = explode('-', $_POST['subcatgory']);
						//$subcat=implode(',',$_POST['sucatgory']);
					   	//$where=$where." AND subcategory_id IN(".$subcat.")";
						$where=$where." AND category_id =".$mdat[0];
						$where=$where." AND filtercat_id =".$mdat[1];
					 }
					 if(!empty($_POST['main_category'])){
						$where=$where." AND category_id =".$_POST['main_category'];
						}
					 if(!empty($_POST['startrange'])){
						$where=$where." AND ((discount_price!=0 AND discount_price >=".$_POST['startrange'].") OR(discount_price=0 AND price >=".$_POST['startrange']."))";
						} 
						
					if(!empty($_POST['endrange'])){
						$where=$where." AND ((discount_price!=0 AND discount_price<=".$_POST['endrange'].") OR(discount_price=0 AND price <=".$_POST['endrange']."))";
						}
					
					if(!empty($_POST['date']) && $_POST['date']!="anydate"){
						$dayName=date("l", strtotime($_POST['date']));
						$dayName=strtolower($dayName);
					   }

					if(!empty($_POST['expess_offer']) && $_POST['expess_offer']=='yes'){
			      		$dayName=date("l", strtotime(date('H:i:s')));
				  		$_POST['starttime']=date('H:i:s');
				  		$_POST['endtime']=date('H:i:s', strtotime('2 hour'));				 
				 	}
							   
			        //$sql="SELECT `id`,`created_by` FROM `st_merchant_category` WHERE `status` = 'active'".$where." GROUP BY created_by";
					$cur_date=date('Y-m-d H:i:s');
    			 	$sql="SELECT `st_merchant_category`.`id`,`st_merchant_category`.`created_by` FROM `st_merchant_category` LEFT JOIN `st_users` ON `st_merchant_category`.`created_by`=`st_users`.`id` WHERE st_users.end_date > '".$cur_date."' AND `st_merchant_category`.`status` = 'active'".$where." GROUP BY st_merchant_category.created_by";

					$query=$this->db->query($sql);
			        $users=$query->result();
			       // echo $this->db->last_query(); die;
			        $userId=array();
			        if(!empty($users)){
						foreach($users as $us){
							 $userId[]=$us->created_by;
							}
						}

					if(!empty($userId)){	
					 $sdate="";
					 $edate="";
					 $stime="";
					 $etime="";
					 if(!empty($_POST['date']) && $_POST['date']!="anydate"){
						 $date=date('Y-m-d',strtotime($_POST['date']));
						   if(!empty($_POST['starttime'])){
							   $sdate=$date." ".date('H:i:s',strtotime($_POST['starttime']));
							   $stime=date('H:i:s',strtotime($_POST['starttime']));
							   }
							else{
								$sdate=$date." 00:00:00";
								$stime="00:00:00";
								}
						   if(!empty($_POST['endtime'])){
							   $edate=$date." ".date('H:i:s',strtotime($_POST['endtime']));
							   $etime=date('H:i:s',strtotime($_POST['endtime']));
							   }
							else{
								$edate=$date." 23:59:00";
								$etime="23:59:00";
								} 	   
						 }
						// echo $sdate."--".$edate;
						 
					 if(!empty($_POST['expess_offer']) && $_POST['expess_offer']=='yes'){
						  $sdate=date('Y-m-d H:i:s');
						  $edate=date('Y-m-d H:i:s', strtotime('2 hour'));	
						  $stime=date('H:i:s');		 
						  $etime=date('H:i:s', strtotime('2 hour'));				 
						 }
					 
					 if((!empty($_POST['date']) && $_POST['date']!="anydate")||(!empty($_POST['expess_offer']) && $_POST['expess_offer']=='yes')){
						// print_r($userId); die;
						 $userIds=array();
				     foreach($userId as $uisrid){ 
						// echo $uisrid;
						 $whereSuv="";
						  if(!empty($_POST['subcatgory']))
							 {   
								 $mdat = explode('-', $_POST['subcatgory']);
								 $mres = get_sub_cat_from_filter($mdat[0], $mdat[1]);
								 $mres = array_map(function($el) {
									return $el->id;
								 }, $mres);
								 $subcat=implode(',',$mres);
								 $whereSuv=" AND subcat_id IN(".$subcat.")";
							 }		 
					  $checkEmpavailablity = "";
			   $blockSubWhere="";
			   $wh="";
			   if(!empty($_POST['date']) && strtolower($_POST['time'])=='anytime'){

                $blockSubWhere.=',(SELECT count(id) FROM st_booking WHERE booking_type="self" AND employee_id=st_users.id AND booking_time<="'.$sdate.'" AND booking_endtime>="'.$edate.'") as booking';
             }
						  $bokkingdetails = 0;
             $employessCount =[];
			  if(!empty($stime) && !empty($etime))
			    {
			    	$getuserBookingDetailsBlockqury = 'SELECT employee_id,booking_time,booking_endtime,(SELECT count(id) FROM st_service_employee_relation WHERE user_id=employee_id '.$whereSuv.') as employess,(SELECT online_booking FROM st_users WHERE id=employee_id) as online_emp,sta.starttime,sta.endtime,sta.starttime_two,sta.endtime_two FROM st_booking LEFT JOIN st_availability as sta ON sta.user_id=st_booking.employee_id WHERE booking_type="self" AND merchant_id="'.$uisrid.'" AND sta.type="open" AND sta.days="'.$dayName.'" AND ((booking_time>="'.$sdate.'" AND booking_time<="'.$edate.'") OR (booking_endtime>="'.$sdate.'" AND booking_endtime<="'.$edate.'") OR (booking_time<="'.$sdate.'" AND booking_endtime>="'.$sdate.'") OR (booking_time>="'.$sdate.'" AND booking_endtime<="'.$edate.'")) HAVING employess>0 AND online_emp>0';

                    $getuserBookingDetailsBlockSql = $this->db->query($getuserBookingDetailsBlockqury);
		            $getuserBookingDetailsBlock    = $getuserBookingDetailsBlockSql->result();

		            /*if($uisrid==25){
		            	print_r($getuserBookingDetailsBlock); die;
		            }*/

                   $timesbooking =array();
                    if(!empty($getuserBookingDetailsBlock)){
		            	foreach ($getuserBookingDetailsBlock as $value) {

		            		$closingtime = "";

		            		if(!empty($value->endtime_two)){
                                $closingtime = $value->endtime_two;
		            		}
		            		elseif(!empty($value->endtime)){
		            		$closingtime = $value->endtime;
		            		}

		            		$openingtime ="";

			            	if(!empty($value->starttime)){
			            	 $openingtime = $value->starttime;
			            	}
			            	elseif(!empty($value->starttime_two)){
			            	 $openingtime = $value->starttime_two;
			            	}	

		            		$settime = array('start' =>$value->booking_time ,'end' =>$value->booking_endtime,'openingtime' =>$openingtime,'closingtime'=>$closingtime);
		            		$timesbooking[$value->employee_id][]=$settime;

		            		

		            	}
		            }
		            

			    	/*$getuserBookingDetailsqury = 'SELECT emp_id,setuptime_start,setuptime_end,finishtime_start,finishtime_end,service_type,(SELECT count(id) FROM st_service_employee_relation WHERE user_id=emp_id '.$whereSuv.') as employess FROM st_booking_detail WHERE mer_id="'.$uisrid.'" AND show_calender=0 AND ((setuptime_start>="'.$sdate.'" AND setuptime_start<="'.$edate.'") OR ((service_type=0 AND (setuptime_end>="'.$sdate.'" AND setuptime_end<="'.$edate.'")) OR (service_type=1 AND (finishtime_end>="'.$sdate.'" AND finishtime_end<="'.$edate.'"))) OR (setuptime_start<="'.$sdate.'" AND ((service_type=0 AND setuptime_start>="'.$edate.'") OR (service_type=1 AND setuptime_start>="'.$edate.'"))) OR (setuptime_start>="'.$sdate.'" AND ((service_type=0 AND setuptime_end<="'.$edate.'") OR (service_type=1 AND finishtime_end<="'.$edate.'")))) HAVING employess>0';*/

			    $wh123= " AND (((setuptime_start>='".$sdate."' AND setuptime_start<'".$edate."') OR (setuptime_end>'".$sdate."' AND setuptime_end<='".$edate."') OR (setuptime_start<='".$sdate."' AND setuptime_end>'".$sdate."') OR (setuptime_start>'".$sdate."' AND setuptime_end<='".$edate."')) OR (service_type=1 AND ((finishtime_start>='".$sdate."' AND finishtime_start<'".$edate."') OR (finishtime_end>'".$sdate."' AND finishtime_end<='".$edate."') OR (finishtime_start<='".$sdate."' AND finishtime_end>'".$sdate."') OR (finishtime_start>'".$sdate."' AND finishtime_end<='".$edate."'))))"; 


			    	$getuserBookingDetailsqury = 'SELECT emp_id,setuptime_start,setuptime_end,finishtime_start,finishtime_end,service_type,(SELECT count(id) FROM st_service_employee_relation WHERE user_id=emp_id '.$whereSuv.') as employess,(SELECT online_booking FROM st_users WHERE id=emp_id) as online_emp,sta.starttime,sta.endtime,sta.starttime_two,sta.endtime_two FROM st_booking_detail LEFT JOIN st_availability as sta ON sta.user_id=st_booking_detail.emp_id WHERE sta.type="open" AND sta.days="'.$dayName.'" AND mer_id="'.$uisrid.'" AND show_calender=0 '.$wh123.' HAVING employess>0 AND online_emp>0';

                    $getuserBookingDetailsSql = $this->db->query($getuserBookingDetailsqury);
		            $getuserBookingDetails    = $getuserBookingDetailsSql->result(); 

		          /* if($uisrid==25){
		            				echo $this->db->last_query().'<pre>'; print_r($getuserBookingDetails); die;
		            	 }
		            */

		            if(!empty($getuserBookingDetails)){
		            	foreach ($getuserBookingDetails as $value) {

                           $closingtime = "";

		            		if(!empty($value->endtime_two)){
                                $closingtime = $value->endtime_two;
		            		}
		            		elseif(!empty($value->endtime)){
		            		$closingtime = $value->endtime;
		            		}

		            		$openingtime ="";

			            	if(!empty($value->starttime)){
			            	 $openingtime = $value->starttime;
			            	}
			            	elseif(!empty($value->starttime_two)){
			            	 $openingtime = $value->starttime_two;
			            	}	
                              


		            		$settime = array('start' =>$value->setuptime_start ,'end' =>$value->setuptime_end,'openingtime' =>$openingtime,'closingtime'=>$closingtime);

		            		$timesbooking[$value->emp_id][]=$settime;

		            		if($value->service_type==1){

                              $settime = array('start' =>$value->finishtime_start ,'end' =>$value->finishtime_end,'openingtime' =>$openingtime,'closingtime'=>$closingtime);

		            		     $timesbooking[$value->emp_id][]=$settime;
		            		  }

		            	}
		            }
		            //echo '<pre>'; print_r($timesbooking);
		            if(!empty($timesbooking)){
		            	  $timesbookingRepleat =array();

		            	 //$employessCount = count($timesbooking);

		            	foreach ($timesbooking as $key=>$row) {
		            		asort($timesbooking[$key]);
		            	$timesbookingRepleat[$key] =array_values($timesbooking[$key]);
		            	}
                         

		            	//asort($timesbooking);
		            	foreach ($timesbookingRepleat as $key=>$row) {
                             $ijk =0;
                             $employessCount[]=$key;
                             //asort($timesbooking[$key]);
                            // echo '<pre>'; print_r($timesbookingRepleat); die;
		            		foreach ($timesbookingRepleat[$key] as $row2) {
		            			
		            			if(!empty($row2['openingtime']) && ($row2['openingtime']>$stime || strtolower($_POST['time'])=='anytime')){

		            			  $sdate1  = date('Y-m-d',strtotime($_POST['date'])).' '.$row2['openingtime'];
		            			  //$edate1  = $edate;	

		            			}else{
                                  $sdate1  = $sdate;
		            			 // $edate1  = $edate;
		            			}

		            		if(!empty($row2['closingtime']) && ($row2['closingtime']<$etime || strtolower($_POST['time'])=='anytime')){

		            			  $edate1  = date('Y-m-d',strtotime($_POST['date'])).' '.$row2['closingtime'];
		            			 // $edate1  = $edate;	

		            			}else{
                                  //$sdate1  = $sdate;
		            			  $edate1  = $edate;
		            			}	
		            		  /* if($uisrid==25){
		            			  echo $row2['start'].'=='.$sdate1.'=='.$edate1.'<pre>'; print_r($timesbooking[$key][$ijk]); die;
		            		    }*/
		            			if($row2['start']<=$sdate1 && $row2['end'] >=$edate1){
		            				//echo '1s';
                                  break;
		            			}
		            		if($sdate1<$row2['start'] || $edate1>$timesbookingRepleat[$key][count($timesbookingRepleat[$key])-1]['end'])
		            		    {

		            			if($ijk==0 && $row2['start']>$sdate1){
		            				$bokkingdetails=1;
		            				//echo $uisrid.'==1y<br/>';
                                  break;
		            			}
		            		 elseif($ijk !=0 && $row2['start']>$timesbookingRepleat[$key][$ijk-1]['end']){
		            		 	$bokkingdetails=1;
		            		 	//echo $uisrid.'=='.$ijk.$row2['start'].'2y'.$timesbookingRepleat[$key][$ijk-1]['end'].'<br/>';
                                  break;
		            				//echo $row2[$ijk-1]['start']; die;
		            				//$row2['start'];
		            		}
		            		elseif($ijk !=0 && $row2['start']<=$timesbookingRepleat[$key][$ijk-1]['end'] && $row2['end']>=$edate1){
                              //echo '2s';
		            			break;
                        
		            			}
		            	    elseif($ijk !=0 && $row2['start']<=$timesbookingRepleat[$key][$ijk-1]['end'] && $row2['end']>=$edate1){
                                //echo '3s';
		            			break;
                        
		            			}
		            		elseif($ijk==(count($timesbookingRepleat[$key])-1) && $row2['end']<$edate1){
		            			//echo $uisrid.'==3y<br/>';
		            			$bokkingdetails=1;
		            			break;
                        
		            			}
		            		  }	
		            		//echo $row2['start'];
		            		  $ijk++;	
		            		 
		            		//echo $row2['start'].' '.$row2['end'].'<br/>';
		            		}
		            		
		            	}
		            }else{
		            	$bokkingdetails=1;
		            }
		           


				 $checkEmpavailablity  = ' AND (((starttime>="'.$stime.'" AND starttime<="'.$etime.'") OR (endtime>"'.$stime.'" AND endtime<="'.$etime.'") OR (starttime<="'.$stime.'" AND endtime>"'.$stime.'")OR (starttime>="'.$stime.'" AND endtime<="'.$etime.'")) OR ((starttime_two>="'.$stime.'" AND starttime_two<="'.$etime.'") OR (endtime_two>"'.$stime.'" AND endtime_two<="'.$etime.'") OR (starttime_two<="'.$stime.'" AND endtime_two>"'.$stime.'")OR (starttime_two>="'.$stime.'" AND endtime_two<="'.$etime.'")))';
				}else{
					 $bokkingdetails=1;
				}
						 
			 $sqlForemp = "SELECT `st_users`.`id` ".$blockSubWhere.$wh.",(SELECT count(id) FROM st_service_employee_relation WHERE user_id=st_users.id ".$whereSuv.") as employess FROM st_users INNER JOIN `st_availability` ON `st_availability`.`user_id`=`st_users`.`id` AND `st_availability`.`days`='".$dayName."'  WHERE status='active' AND online_booking=1 AND access='employee' AND st_availability.type='open' ".$checkEmpavailablity." AND merchant_id=".$uisrid." HAVING employess>0"; 
			   
			   $empleeSql = $this->db->query($sqlForemp);
		       $emplee    = $empleeSql->result(); 
		      /* if($uisrid==25){
		       //echo $this->db->last_query(); 
		       echo $bokkingdetails.count($employessCount).'<pre>'; print_r($emplee); die;
		       }*/
		       if(!empty($emplee))
		          {
		          	$totalEmpcount = count($emplee);

		          if(empty($employessCount) || count($employessCount)<$totalEmpcount){

                       $bokkingdetails =1;
		          	}

				  foreach($emplee as $emp)
				      {
					  if((!empty($bokkingdetails) || !in_array($emp->id, $employessCount)) && empty($emp->booking) && !empty($emp->employess))
					     {
						   $userIds[]=$uisrid;
						   break;
						 }
					  }
				   }
						   }
						$userId= $userIds; 
						}


						}

					if(!empty($userId)){	
			       //$user= implode(',',$userId);
			        if(!empty($dayName)){  
						  $whereAS="";
						   if(!empty($_POST['time']) && !empty($_POST['endtime']) && $_POST['time']!='anytime')
						    {
							 $whereAS=' AND ((starttime>="'.$_POST['starttime'].'" AND starttime<="'.$_POST['endtime'].'") OR (endtime>="'.$_POST['starttime'].'" AND endtime<="'.$_POST['endtime'].'") OR (starttime<="'.$_POST['starttime'].'" AND endtime>="'.$_POST['endtime'].'") OR (starttime>="'.$_POST['starttime'].'" AND endtime<="'.$_POST['endtime'].'"))';
							}
								
							 if(!empty($lat) && !empty($lng))
							   {
						          $sql1="SELECT `st_users`.`id`,`latitude`,`longitude` FROM `st_users` INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='".$dayName."' WHERE `status` = 'active' AND `access` = 'marchant' ".$whereAS." AND `st_availability`.`days`='".$dayName."' AND `st_availability`.`type`='open' AND st_users.id IN (".implode(',',$userId).") AND ( '6371' * acos( cos( radians($lat) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng)) + sin(radians($lat)) * sin( radians(`latitude`)))) < $distance ";
					           }
					        else{
								
								$sql1="SELECT `st_users`.`id`,`latitude`,`longitude` FROM `st_users` INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='".$dayName."' WHERE `status` = 'active' AND `access` = 'marchant' ".$whereAS." AND `st_availability`.`days`='".$dayName."' AND `st_availability`.`type`='open' AND st_users.id IN (".implode(',',$userId).")";
													
								} 
					 
						 }else{
							 if(!empty($lat) && !empty($lng))
							   { 
								 $sql1="SELECT `id`,`latitude`,`longitude` FROM `st_users` WHERE `status` = 'active' AND id IN (".implode(',',$userId).") AND `access` = 'marchant' AND ( '6371' * acos( cos( radians($lat) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng)) + sin(radians($lat)) * sin( radians(`latitude`)))) < $distance";
							  }
						  else
						     {
								 $sql1="SELECT `id`,`latitude`,`longitude` FROM `st_users` WHERE `status` = 'active' AND id IN (".implode(',',$userId).") AND `access` = 'marchant'";
							 
							 }
				    }

					$query1=$this->db->query($sql1);
					$latlong=$query1->result(); 
					//echo $this->db->last_query(); die;
					if(!empty($latlong)){
							$this->response_data['data']=$latlong;
							$this->response_data['status']=1;
			      			$this->response_data['response_message']='Success';

						} 
					else{ 
						$this->response_data['status']=0;
			     		 $this->response_data['response_message']='No record found..';
					 }
				 }else{
				 		$this->response_data['status']=0;
			      		$this->response_data['response_message']='No record found..';
				 	}
    

				}
			}
			echo json_encode($this->response_data);

    }

   function salon_profile_post()
   {

   	$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
   	$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
	$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
	$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
	
	if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
   			$id=$_POST['salon_id'];
   		
			
			$this->data['service_id']=$id;
			
			$uid=(!empty($_POST['uid']))?$_POST['uid']:0;
			

			if($uid !=0){
			 $this->api_model->delete('st_cart',array('merchant_id !='=>$id,'user_id'=>$uid));
			}


			$field="st_users.id,first_name,last_name,business_name,business_type,address,email,mobile,address,zip,latitude,longitude,country,city,about_salon,user_id,image,image1,image2,image3,image4,(select ROUND(AVG(rate),2) from st_review where merchant_id=st_users.id AND user_id !=0) as rating,(SELECT COUNT(id) FROM st_review WHERE merchant_id=st_users.id AND user_id !=0) as total_review";
			$whr=array('st_users.id'=>$id);
			$this->data['sid']=$id;
			//$_GET['servicids']="'3','4','5','6'";

			$services_by_subcategory = [];
			if(!empty($_POST['servicids']))
			{
				 //$sids= url_decode($_GET['servicids']);
				 //$esid=array_unique(explode(',',$sids));

                $sql="SELECT st_merchant_category.*,(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id=".$uid." AND st_cart.service_id=st_merchant_category.id) as in_cart,st_category.category_name,st_category.id as subcatid FROM st_merchant_category LEFT JOIN st_category ON st_category.id=st_merchant_category.subcategory_id WHERE st_merchant_category.status='active' AND st_merchant_category.created_by=".$id." AND st_merchant_category.subcategory_id IN(".$_POST['servicids'].") ORDER BY st_merchant_category.name,st_merchant_category.subcategory_id";

                //AND st_merchant_category.subcategory_id IN(".implode(',',$esid).")"

                $matchcatsubcat=$this->api_model->custome_query($sql,'result');
				$startFrom="";
			
				if($matchcatsubcat){
					$ky=$j=0;$serId=0;
					$sub_prc1=0;
                 foreach($matchcatsubcat as $service){
                  if(!empty($service->discount_price)) 
                  	$price=$service->discount_price; 
                  else 
                  	$price= $service->price;

                  	//echo $price;
                  if(empty($startFrom)) $startFrom=$price;

                  if($startFrom>$price) $startFrom=$price;

                 // echo $startFrom;
                  if($service->name !=''){
                  				
                      if($service->subcategory_id != $serId){
                      	 $sqlDropcount1="SELECT count(st_cart.id) as ctCount FROM st_cart LEFT JOIN st_merchant_category ON st_cart.service_id=st_merchant_category.id WHERE subcategory_id = '".$service->subcategory_id."' AND st_cart.user_id='".$uid."' AND name !=''";
			 	
			 							$dataCt1=$this->db->query($sqlDropcount1);
			 							$chkdrop1=$dataCt1->row();
			 							if($chkdrop1->ctCount > 0)
			 								$ckm=1;
			 							else
			 								$ckm=0;
			 			$services_by_subcategory[$j]['id'] = $service->id;
						$services_by_subcategory[$j]['key'] = $service->category_name;
						$services_by_subcategory[$j]['status'] =0;
						$services_by_subcategory[$j]['start_price']=$startFrom;
						$services_by_subcategory[$j]['is_open']=$ckm;
						$services_by_subcategory[$j]['value'][] = $service;
						//$services_by_subcategory[$service->category_name ][] = $service;
						$j++;
						$sub_prc1=$startFrom;
                      }

                      else{
                      		if($sub_prc1 > $startFrom)
			                      $sub_prc1=$startFrom;
							$a=$j-1;
							$services_by_subcategory[$a]['start_price']=$sub_prc1;//$startFrom;
							$services_by_subcategory[$a]['value'][] = $service;               
						  }
					$serId=$service->subcategory_id;

                  }

                  else{
                  	  $services_by_subcategory[$j]['id'] = $service->id;
                      $services_by_subcategory[$j]['key'] = $service->category_name;
					  $services_by_subcategory[$j]['status'] =1;
					  $services_by_subcategory[$j]['start_price']=$startFrom;
					  $services_by_subcategory[$j]['is_open']=intval($service->in_cart);
					  $services_by_subcategory[$j]['value'][] = $matchcatsubcat[$ky];
					  $serId=0;
						$j++;

                  } 
                  $startFrom="";                   
                  	$ky++;
			 }

               

              //echo "<pre>"; print_r($services_by_subcategory); die;

               }
              }

			$field2='st_merchant_category.id,st_category.category_name,st_merchant_category.subcategory_id as subid,count(*) as count,(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id="'.$uid.'" AND st_cart.service_id=st_merchant_category.id) as is_open'; //chg for is_open ios
			$sub_category= $this->api_model->join_two('st_merchant_category','st_category','subcategory_id','id',array('st_merchant_category.created_by' =>$id,'st_merchant_category.status' =>'active'),$field2,'subcategory_id');
			$i=0;

	   		$details= $this->api_model->join_two('st_users','st_banner_images','id','user_id',$whr,$field);
	   		$this->response_data['data']['details']= $details;
	   		
	   		$allservices=[];
	   		$sub_services=[];
	   		 //$all_services=[];

	   		if(!empty($sub_category)){
	   			//print_r($sub_category);
	   			$kyy=0;
	   			$jj=0;
	   			$serIds=0;
			foreach($sub_category as $service1){
           		$this->response_data['data']['all_service'][$i]=$service1;

           		 $sql1="SELECT st_merchant_category.id,name,subcategory_id,price_start_option,duration,category_name,price,discount_price,st_category.category_name,(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id=".$uid." AND st_cart.service_id=st_merchant_category.id) as in_cart,st_category.id as subcatid FROM st_merchant_category LEFT JOIN st_category ON st_category.id=st_merchant_category.subcategory_id WHERE st_merchant_category.status='active' AND st_merchant_category.created_by=".$id." AND st_merchant_category.subcategory_id=".$service1->subid." ORDER BY st_merchant_category.subcategory_id, st_merchant_category.id";
           		  $allservices=$this->api_model->custome_query($sql1,'result');

           		
           		 $kyy=0;$jj=0;
           		 $ct= $service1->count;
           		
           		    $all_services=[];
           		    $sub_prc=0;
           		 	foreach($allservices as $service){
                            
						  //print_r($service);
						    if(!empty($service->discount_price)) 
						    	$price=$service->discount_price; 
						    else 
						    	$price= $service->price;

						      if(empty($startFrom)) $startFrom=$price;

			                  if($startFrom >$price) $startFrom=$price;

			                  //echo $startFrom;

			                  if($service->name !=''){
			                  		$chkdrop=0;
			                  		
			                  		if($service->in_cart == 1)
                                  		$chkdrop=1;

                                  if($service->subcategory_id != $serIds){

                                  	$sqlDropcount="SELECT count(st_cart.id) as ctCount FROM st_cart LEFT JOIN st_merchant_category ON st_cart.service_id=st_merchant_category.id WHERE subcategory_id = '".$service->subcategory_id."' AND st_cart.user_id='".$uid."' AND name !=''";
			 	
			 							$dataCt=$this->db->query($sqlDropcount);
			 							$chkdrop=$dataCt->row();
			 							if($chkdrop->ctCount > 0)
			 								$ck=1;
			 							else
			 								$ck=0;

									$all_services[$jj]['key'] = $service->category_name;
									$all_services[$jj]['start_price']=$startFrom;
									$all_services[$jj]['status']=0;
									$all_services[$jj]['is_open']=$ck;
									$all_services[$jj]['value'][] = $service;
									$jj++;
									$sub_prc=$startFrom;
                                   }

			                      else{
			                      		if($sub_prc > $startFrom)
			                      			$sub_prc=$startFrom;
										$aa=$jj-1;
										$all_services[$aa]['start_price']=$sub_prc; //$startFrom;
										$all_services[$aa]['value'][] = $service;

									  }
								$serIds=$service->subcategory_id;

			                  }
			                  else{
			                  		
			                  	  $all_services[$jj]['key'] = $service->category_name;
								  $all_services[$jj]['status'] =1;
								  $all_services[$jj]['is_open']=intval($service->in_cart);
								  $all_services[$jj]['start_price']=$startFrom;
								  $all_services[$jj]['value'][] = $service;
							

								  //$allservices[$kyy];
								  $serIds=0;
									$jj++;
                                  //echo $jj;

			                  }    
			                  $startFrom="";                
			                  	
           		 	}
           		 	$this->response_data['data']['all_service'][$i]->sub_services=$all_services;
                   $kyy++;
           		 
               	 $i++;
      			}	
      		}else{
      			$sub_category=array();
      		}
	   		//$main_menu=get_menu();
	   		$field3='st_category.id,st_category.category_name,image,icon,st_merchant_category.subcategory_id as sub,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`st_merchant_category`.`id`),0) as checkemp';
	   			$this->db->having('checkemp >0');
	   		$main_menu= $this->api_model->join_two('st_merchant_category','st_category','category_id','id',array('st_merchant_category.created_by' =>$id,'st_merchant_category.status' =>'active'),$field3,'category_id');
			$i=0;
			if(!empty($details)){
				//$details=$details[0];
				$this->response_data['data']['details'][0]->share_url=base_url('user/service_provider/'.url_encode($details[0]->id));
				$this->response_data['status']=1;
			    $this->response_data['response_message']='success';
			    $this->api_model->delete('st_recently_search',array('salon_id' => $_POST['salon_id'],'device_id' => $_POST['device_id']));
			    $ins_arry=array();
			    $ins_arry['salon_id']= $_POST['salon_id'];
			    $ins_arry['device_id']= $_POST['device_id']; 
			    $ins_arry['created_on']= date('Y-m-d H:i:s');

			    $this->api_model->insert('st_recently_search',$ins_arry);

			    //$this->response_data['data']['match_subcat']=$services_by_subcategory;
			    foreach($details as $row){
					$this->response_data['data']['details'][$i]->user_id=($row->user_id !='')?$row->user_id:$row->id;
			    	$this->response_data['data']['details'][$i]->image=($row->image !='')?$row->image:'';
					$this->response_data['data']['details'][$i]->image1=($row->image1 !='')?$row->image1:'';
					$this->response_data['data']['details'][$i]->image2=($row->image2 !='')?$row->image2:'';
					$this->response_data['data']['details'][$i]->image3=($row->image3 !='')?$row->image3:'';
					$this->response_data['data']['details'][$i]->image4=($row->image4 !='')?$row->image4:'';
			    	$this->response_data['data']['details'][$i]->rating= ($row->rating !='')?number_format($row->rating,1):'0.0';
			    	$i++;
			    }
				$this->response_data['data']['main_category']=$main_menu;
				//$this->response_data['data']->sub_category=$sub_category;
				 $this->response_data['data']['matchcatsubcat']=$services_by_subcategory;
			}
			else{
				$this->response_data['status']=0;
			    $this->response_data['response_message']='No record found..';
			}

		}
	}
		echo json_encode($this->response_data);


   }

   function salon_profile_detail_post()
   {
   		$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
   				$id=$_POST['salon_id'];
   				extract($_POST);
   				$distaceField = "";	
   				if(!empty($latitude) && !empty($longitude))
   				  {	
                    $distaceField=",( '6371' * acos( cos( radians($latitude) ) * cos( radians(`latitude`)) * cos( radians(`longitude`) - radians($longitude)) + sin(radians($latitude)) * sin( radians(`latitude`)))) AS distance";
                  }
                  
   				$data=$this->api_model->getWhereRowSelect('st_users',array('id' => $_POST['salon_id']),'id,first_name,last_name,latitude,longitude,about_salon,business_name,address,city,zip,fb_link,web_link,insta_link'.$distaceField);
   				if(!empty($data)){
   					$data->share_url='';
				    $data->share_url=base_url('user/service_provider/'.url_encode($data->id));
   					$this->response_data['status']=1;
   				$this->response_data['data']=$data;
   				$avl= $this->api_model->getWhereAllselect('st_availability',array('user_id'=>$_POST['salon_id']),"id,starttime,endtime");
   				
   				if(!empty($avl))
   					$this->response_data['data']->availability=$avl;
   				else
   					$this->response_data['data']->availability=array();
   					
   				
   				$emp = $this->api_model->getWhereAllselect('st_users',array('merchant_id'=>$_POST['salon_id'],'status' => 'active'),"id,first_name,last_name,profile_pic");

   				if(!empty($emp))
   					$this->response_data['data']->employee=$emp;
   				else
   					$this->response_data['data']->employee=array();
   					
   				
   					
   			   $payment_method = $this->api_model->getWhereAllselect('st_merchant_payment_method',array('status'=>'active','user_id'=>$_POST['salon_id']),'id,method_name,defualt');
   			 
               if(!empty($payment_method))
   					$this->response_data['data']->payment_methods=$payment_method;
   				else
   					$this->response_data['data']->payment_methods=array();
   					

   				$this->response_data['response_message']='Success';

   				}  
   			}
   		}
	
		echo json_encode($this->response_data);


   }

   function category_allservice_post(){
   	$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
   	$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
	$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
	$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
	$this->form_validation->set_rules('category_id', 'Category Id', 'required|trim');

	if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
			$uid=(!empty($_POST['uid']))?$_POST['uid']:0;
   			$field2='st_merchant_category.id,st_category.category_name,st_merchant_category.subcategory_id as subid,count(*) as count,st_merchant_category.service_detail,(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id="'.$uid.'" AND st_cart.service_id=st_merchant_category.id) as is_open,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE subcat_id =`st_merchant_category`.`subcategory_id` AND created_by="'.$_POST['salon_id'].'"),0) as checkemp';
   				$this->db->having('checkemp > 0');
			$sub_category= $this->api_model->join_two('st_merchant_category','st_category','subcategory_id','id',array('st_merchant_category.created_by' =>$_POST['salon_id'],'st_merchant_category.status' =>'active','category_id'=>$_POST['category_id']),$field2,'subcategory_id');
			
//echo $this->db->last_query(); print_r($sub_category); die;
			$i=0;$serIds=0; $allservices=[];
			$ck_pr='';
			$start_prc='';
	   		$sub_services=[];
			if(!empty($sub_category)){
			foreach($sub_category as $service1){
           		 $this->response_data['data']['all_service'][$i]=$service1;

           		 $sql1="SELECT st_merchant_category.id,name,subcategory_id,category_id as main_categoryid,duration,category_name,price,price_start_option,discount_price,st_category.category_name,(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id=".$uid." AND st_cart.service_id=st_merchant_category.id) as in_cart,st_category.id as subcatid,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`st_merchant_category`.`id` AND created_by=".$_POST['salon_id']."),0) as checkemp FROM st_merchant_category LEFT JOIN st_category ON st_category.id=st_merchant_category.subcategory_id WHERE st_merchant_category.status='active' AND st_merchant_category.created_by=". $_POST['salon_id']." AND st_merchant_category.subcategory_id=".$service1->subid." HAVING checkemp > 0  ORDER BY st_merchant_category.name,st_merchant_category.subcategory_id";
           		            //  $this->db->having('checkemp > 0');
           		 $allservices=$this->api_model->custome_query($sql1,'result');


           		 	$jj=0;
           		 	$all_services=[];
           		 	 $ser_dur=array();
           		 	foreach($allservices as $service){
           		 		 $ser_dur[]=$service->duration;
                        if(!empty($service->discount_price)) $price=$service->discount_price; else $price= $service->price;

			                  if(empty($startFrom)) $startFrom=$price;

			                  if($startFrom>$price) $startFrom=$price;

			                     if($i == $ck_pr){
			                    	if($start_prc!=""){
			                    		if($start_prc > $startFrom)
			                    			$start_prc= $startFrom;
			                    	}
			                    	else
			                    		$start_prc= $startFrom;
			                    }
			                    else{
			                    	$start_prc= $startFrom;
			                    }

			                 // if($service->name !=''){
                                 // if($service->subcategory_id != $serIds){

                                  		$sqlDropcount="SELECT count(st_cart.id) as ctCount FROM st_cart LEFT JOIN st_merchant_category ON st_cart.service_id=st_merchant_category.id WHERE subcategory_id = '".$service->subcategory_id."' AND st_cart.user_id='".$uid."' AND name !=''";
			 	
			 							$dataCt=$this->db->query($sqlDropcount);
			 							$chkdrop=$dataCt->row();
			 							if($chkdrop->ctCount > 0)
			 								$ck=1;
			 							else
			 								$ck=0;

			 						$this->response_data['data']['all_service'][$i]->start_price=$start_prc;
			 						 $min1 = min($ser_dur);
                             		 $max1 = max($ser_dur);
                             		 if($min1 == $max1)
			 							$this->response_data['data']['all_service'][$i]->service_duration= $min1.' Min';
			 						 else
			 						 	$this->response_data['data']['all_service'][$i]->service_duration=$min1.' Min-'.$max1.' Min';

			 						$all_services[$jj] = $service;

									/*$all_services[$jj]['key'] = $service->category_name;
									$all_services[$jj]['start_price']=$startFrom;
									$all_services[$jj]['status']=0;
									$all_services[$jj]['is_open']=$ck;
									$all_services[$jj]['value'][] = $service;*/
									$jj++;
                                 /*  }
								 else{
										$aa=$jj-1;
										$all_services[$aa]['value'][] = $service;
									  }*/
								$serIds=$service->subcategory_id;

			                  //}
			                 /* else{

			                  	  $all_services[$jj]['key'] = $service->category_name;
								  $all_services[$jj]['status'] =1;
								  $all_services[$jj]['is_open']=intval($service->in_cart);
								  $all_services[$jj]['start_price']=$startFrom;
								  $all_services[$jj]['value'][] = $service;
									$serIds=0;
									$jj++;
                              	}  */  
                              	 $startFrom="";    
                              	$ck_pr=$i;                 
			        	}
           		 	$this->response_data['data']['all_service'][$i]->sub_services=$all_services;

           		 	//$this->response_data['data'][$i]->sub_services=$allservices;
               	 $i++;
      			}	
      		}else{
      			$sub_category=array();
      		}
	
				if(!empty($sub_category)){
					$this->response_data['data']['all_service']=$sub_category;
					$this->response_data['status']=1;
					$this->response_data['response_message']='Success';
				}
				else
					$this->response_data['response_message']='No record found.';
			
			}
		}
		echo json_encode($this->response_data);
   }

   function category_allservice_old_post(){
   	$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
   	$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
	$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
	$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
	$this->form_validation->set_rules('category_id', 'Category Id', 'required|trim');

	if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
			$uid=(!empty($_POST['uid']))?$_POST['uid']:0;
   			$field2='st_merchant_category.id,st_category.category_name,st_merchant_category.subcategory_id as subid,count(*) as count';
			$sub_category= $this->api_model->join_two('st_merchant_category','st_category','subcategory_id','id',array('st_merchant_category.created_by' =>$_POST['salon_id'],'st_merchant_category.status' =>'active','category_id'=>$_POST['category_id']),$field2,'subcategory_id');
			$i=0;$serIds=0; $allservices=[];
	   		$sub_services=[];
			if(!empty($sub_category)){
			foreach($sub_category as $service1){
           		 $this->response_data['data']['all_service'][$i]=$service1;

           		 $sql1="SELECT st_merchant_category.id,name,subcategory_id,duration,category_name,price,discount_price,st_category.category_name,(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id=".$uid." AND st_cart.service_id=st_merchant_category.id) as in_cart,st_category.id as subcatid FROM st_merchant_category LEFT JOIN st_category ON st_category.id=st_merchant_category.subcategory_id WHERE st_merchant_category.status='active' AND st_merchant_category.created_by=". $_POST['salon_id']." AND st_merchant_category.subcategory_id=".$service1->subid." ORDER BY st_merchant_category.name,st_merchant_category.subcategory_id";
           		  $allservices=$this->api_model->custome_query($sql1,'result');


           		 	$jj=0;
           		 	$all_services=[];
           		 	foreach($allservices as $service){
                        if(!empty($service->discount_price)) $price=$service->discount_price; else $price= $service->price;

			                  if(empty($startFrom)) $startFrom=$price;

			                  if($startFrom>$price) $startFrom=$price;

			                  if($service->name !=''){
                                  if($service->subcategory_id != $serIds){

                                  		$sqlDropcount="SELECT count(st_cart.id) as ctCount FROM st_cart LEFT JOIN st_merchant_category ON st_cart.service_id=st_merchant_category.id WHERE subcategory_id = '".$service->subcategory_id."' AND st_cart.user_id='".$uid."' AND name !=''";
			 	
			 							$dataCt=$this->db->query($sqlDropcount);
			 							$chkdrop=$dataCt->row();
			 							if($chkdrop->ctCount > 0)
			 								$ck=1;
			 							else
			 								$ck=0;

									$all_services[$jj]['key'] = $service->category_name;
									$all_services[$jj]['start_price']=$startFrom;
									$all_services[$jj]['status']=0;
									$all_services[$jj]['is_open']=$ck;
									$all_services[$jj]['value'][] = $service;
									$jj++;
                                   }
								 else{
										$aa=$jj-1;
										$all_services[$aa]['value'][] = $service;
									  }
								$serIds=$service->subcategory_id;

			                  }
			                  else{

			                  	  $all_services[$jj]['key'] = $service->category_name;
								  $all_services[$jj]['status'] =1;
								  $all_services[$jj]['is_open']=intval($service->in_cart);
								  $all_services[$jj]['start_price']=$startFrom;
								  $all_services[$jj]['value'][] = $service;
									$serIds=0;
									$jj++;
                              	}                    
			        	}
           		 	$this->response_data['data']['all_service'][$i]->sub_services=$all_services;

           		 	
               	 $i++;
      			}	
      		}else{
      			$sub_category=array();
      		}
	
				if(!empty($sub_category)){
					$this->response_data['data']['all_service']=$sub_category;
					$this->response_data['status']=1;
					$this->response_data['response_message']='Success';
				}
				else
					$this->response_data['response_message']='No record found.';
			
			}
		}
		echo json_encode($this->response_data);
   }

   function subcategory_list_post(){
   	 $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
 	 $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
	 $this->form_validation->set_rules('api_key', 'Key', 'required|trim');
	 $this->form_validation->set_rules('cid', 'cid id', 'required|trim');
	// $this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		//(SELECT count(distinct(created_by)) FROM st_merchant_category WHERE subcategory_id=st_category.id AND status='active') as totalcount
	 if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
		   		$select="id,category_name,parent_id";
				$mdat = explode('-', $_POST['cid']);

				$sub_category=get_filter_menu();
				if(!empty($sub_category)){
					$this->response_data['data']=$sub_category;
					$this->response_data['status']=1;
					$this->response_data['response_message']='Success';
				}
				else
					$this->response_data['response_message']='No record found.';
			}
		}

		echo json_encode($this->response_data);

   }

   function review_listing_post(){


   		$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{	extract($_POST);
				$offset = $perPageData = $total_count= $status = $page_no= 0;
				$limit = 10; 
				$perPage = null; 
			
				$page_no = (isset($page) and !empty($page))?$page:1;  
				 if($page_no !='all')
				 {
					$offset = ($page_no == 1) ? 0 : ($page_no * $limit) - $limit;
					$perPage= $perPageData = $limit; 
				 }
				 else 
					$perPageData ='all';


				$where='';
					if(isset($_POST['rating_point']) && !empty($_POST['rating_point'][0])){  
					  $subcat=implode(',',$_POST['rating_point']);
					  $where=$where." AND rate IN(".$subcat.")";
					}
					

					if(isset($_POST['category']) && !empty($_POST['category'][0])){

						   $allsubcats = "";
							$i=0;
							
							foreach($_POST['category'] as $k=>$v){
								if($i==0)
								  $allsubcats=$v;
								else
								  $allsubcats=$allsubcats.','.$v;
								  
								  $i++;
								}
						 // echo $allsubcats; die;
								
			             $subcat=$allsubcats;
			           
					 	 $whr="service_id IN(".$subcat.")";	
					 	$msql="SELECT booking_id FROM st_booking_detail WHERE ".$whr."";
					 	$book_id=$this->api_model->custome_query($msql,'result');
					 	if(!empty($book_id)){
					 		//$book_id=implode(',',$book_id);
					 		$i=0;
					 		foreach($book_id as $ids){
					 			$book[$i]=$ids->booking_id;
					 			$i++;
					 		}
					 		
					 		$book_id=implode(',',$book);
					 		$where=$where."AND booking_id IN(".$book_id.")";
					 	}
					 	else{
					 		$where=$where."AND booking_id IN(0)";	
					 	}
					}

				  $csql="SELECT count(st_review.id) as tcount FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=".$_POST['salon_id']." ".$where." AND st_review.user_id!='0'";
				  $totalcount=$this->api_model->custome_query($csql,'row');
				 
				 if(!empty($totalcount->tcount))
				 	$count=$totalcount->tcount;
				 else
				 	$count=0;
				//$count =  $this->api_model->getWhereRowSelect('st_review',array('merchant_id' => $salon_id,'user_id!='=>0),$select='count(id) as count');

				//$data=$this->api_model->getWhere('st_review',array('merchant_id' => $salon_id,'user_id !='=>0),'id,rate,review,user_id,created_on,anonymous,(select first_name from st_users where id=st_review.user_id) as first_name,(select last_name from st_users where id=st_review.user_id) as last_name,IFNULL((select first_name from st_users where id=st_review.emp_id),"") as emp_firstname,IFNULL((select last_name from st_users where id=st_review.emp_id),"") as emp_lastname',$perPage,$offset);

				$sql="SELECT st_review.id,st_review.user_id,st_review.booking_id,st_review.merchant_id,rate,review,anonymous,st_review.created_on,first_name,last_name,(SELECT business_name from st_users where st_users.id=st_review.merchant_id) as salon_name,IFNULL((SELECT first_name from st_users where st_users.id=st_review.emp_id),'') as emp_firstname, IFNULL((SELECT last_name from st_users where st_users.id=st_review.emp_id),'') as emp_lastname FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=".$_POST['salon_id']." ".$where." AND st_review.user_id!='0' GROUP BY st_review.id DESC LIMIT ".$perPage." OFFSET ".$offset."";
				$data=$this->api_model->custome_query($sql,'result');


				$i=0;
				if(!empty($data)){
					foreach($data as $row){
						$timestamp              = strtotime($row->created_on); 
                        $data[$i]->time_ago     = time_passed($timestamp);
                        $data[$i]->service_name = get_servicename_with_sapce($row->booking_id);
					$reply=$this->api_model->getWhereRowSelect('st_review',array('review_id' => $row->id),'id,review');
						if(!empty($reply))
							$data[$i]->reply=$reply;
						else{
							$data[$i]->reply['id']="";
							$data[$i]->reply['review']="";
						}

						//$data[$i]->reply=(!empty($reply))?$reply:new stdClass();
					$i++;
					}
				}

				if(!empty($data)){
					$this->response_data['status'] = 1;
					$this->response_data['data'] = $data;
					$this->response_data['response_message'] = 'success';
					$this->response_data['total_count']= $count; 		//$count->count;
				  	$this->response_data['per_page']=$perPage;
				  	$this->response_data['current_page']=$page_no;
				}
				else
					$this->response_data['response_message'] = 'No record found..';

			}
		}

		echo json_encode($this->response_data);

   }


   function add_booking_post()
   {
		$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('service_id', 'Service Id', 'required|trim');
		$this->form_validation->set_rules('price', 'Price', 'required|trim');

	if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{

						$insertArr=array();
							$usid=$_POST['uid'];

							//if($this->session->userdata('access')=='user'){
						if($this->api_model->get_datacount('st_client_block',array('client_id'=>$usid,'merchant_id'=>$salon_id)) > 0){
							$this->response_data['status']=0;
							$this->response_data['response_message']='Du bist aktuell nicht berechtigt, Buchungen in diesem Salon zu erstellen.';
						}
						else if($this->api_model->get_datacount('st_users',array('id'=> $salon_id,'online_booking' => 0)) > 0){
							$this->response_data['status']=0;
							$this->response_data['response_message']='Der Salon nimmt im Moment keine neuen Buchungen über styletimer entgegen';
							}
						else{

							 if($this->api_model->get_datacount('st_cart',array('user_id' => $usid,'service_id' => $_POST['service_id'])) == 0){

							 		$service_det=$this->api_model->getWhereRowSelect('st_merchant_category',array('id'=>$_POST['service_id']),'price,created_by,discount_price,subcategory_id');
									
									if(!empty($service_det)){
										
											if($this->api_model->get_datacount('st_cart',array('merchant_id'=>$service_det->created_by,'user_id'=>$usid)))
											{

												$ss = "SELECT service_id FROM st_cart where user_id=".$uid."";
												$ress = $this->api_model->custome_query($ss,'result');
								   				 $serId[] = $_POST['service_id'];	
												foreach ($ress as $key => $value)
									 			{
												$serId[]       = $value->service_id;
												}
									 			$emp = 1;
									 			$sql = $this->db->query("SELECT DISTINCT user_id,service_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$service_det->created_by."");
												$uidsRes= $sql->result();
												//echo '<pre>'; print_r($uidsRes);
												if(!empty($uidsRes)){
											
								               $users = array();
								               
												foreach($uidsRes as $res)
												{
												 $users[$res->user_id][] = $res->service_id;
												}

									            $userids=array();
												foreach($users as $k=>$v)
												{
													$arraymatch = count(array_intersect($v, $serId)) >= count($serId);
													if($arraymatch)
														$userids[] = $k;
												}		
													
								           if(empty($userids)){
												$emp =0;
											} 
											
										   } else $emp =0;
										   if($emp ==0){
										   		$this->response_data['status']=0;
												$this->response_data['response_message']='Die ausgewählten Services müssen von unterschiedlichen Mitarbeitern ausgeführt werden. Du musst daher für diese Behandlung eine separate Buchung erstellen.';
										   		echo json_encode($this->response_data);
										   		die;
										   	}

										   	$checkIntocart=$this->api_model->getWhereRowSelect('st_cart',array('subcat_id'=>$service_det->subcategory_id,'user_id'=>$usid),'id,service_id,subcat_id,(SELECT allow FROM st_subcategory_settings WHERE subcat_id='.$service_det->subcategory_id.' AND merchant_id='.$salon_id.') as multiallow');

										   	  $delID =0;
										   	
					                          if(!empty($checkIntocart->subcat_id) && $checkIntocart->subcat_id==$service_det->subcategory_id && empty($checkIntocart->multiallow)){
					                              
					                              $delID = $checkIntocart->service_id;
					                                  //echo $delID; die;
					                          	  $this->api_model->delete('st_cart',array('id' => $checkIntocart->id));

					                               }

												$insertArr['service_id']=$_POST['service_id'];
								      			$insertArr['user_id']=$usid;
								      			
								      			if(!empty($service_det->discount_price))
								      			    {
													  $price=$service_det->discount_price;
												    }
												else{
													  $price=$service_det->price;
													 }
								      			
								      			$insertArr['total_price']=$price;
								      			$insertArr['subcat_id']   = $service_det->subcategory_id;
								      			$insertArr['merchant_id']=$service_det->created_by;
								      			$insertArr['created_by']= $usid;
								      			$insertArr['created_on']= date('Y-m-d H:i:s');
												$this->api_model->insert('st_cart',$insertArr);
												$this->response_data['status']=1;
												$this->response_data['delete_sid']=$delID;
												$this->response_data['response_message']='Service added to cart';
											}
											else{
												$this->api_model->delete('st_cart',array('user_id' => $usid));

												 $serId[] = $_POST['service_id'];	
									
												 $emp = 1;
												 $sql = $this->db->query("SELECT DISTINCT user_id,service_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$service_det->created_by."");
													$uidsRes= $sql->result();
													//echo '<pre>'; print_r($uidsRes);
													if(!empty($uidsRes)){
														
											               $users = array();
											               
															foreach($uidsRes as $res)
															{
															 $users[$res->user_id][] = $res->service_id;
															}

												            $userids=array();
															foreach($users as $k=>$v)
															{
																$arraymatch = count(array_intersect($v, $serId)) >= count($serId);
																if($arraymatch)
																	$userids[] = $k;
															}		
																
											           if(empty($userids)){
															$emp =0;
														} 
														
													   } else $emp =0;
													   if($emp ==0){
													   	$this->response_data['status']=0;
														$this->response_data['response_message']='This service is not provided by any of the employee, so please select another service.';
												   		echo json_encode($this->response_data);
												   		die;
													   	}

												$insertArr['service_id']=$_POST['service_id'];
								      			$insertArr['user_id']=$usid;
								      			if(!empty($service_det->discount_price))
								      			    {
													  $price=$service_det->discount_price;
												    }
												else{
													  $price=$service_det->price;
													 }
								      			$insertArr['total_price']=$price;
								      			$insertArr['subcat_id']   = $service_det->subcategory_id;
								      			$insertArr['merchant_id']=$service_det->created_by;
								      			$insertArr['created_by']= $usid;
								      			$insertArr['created_on']= date('Y-m-d H:i:s');
												$this->api_model->insert('st_cart',$insertArr);
												$this->response_data['status']=1;
												$this->response_data['delete_sid']=0;
												$this->response_data['response_message']='Service added to cart';
											}
										

									}
							}
							else{
								 
								$this->api_model->delete('st_cart',array('user_id' => $usid,'service_id' => $_POST['service_id']));
								$this->response_data['status']=1;
								$this->response_data['delete_sid']=0;
								$this->response_data['response_message']='Service removed from cart';
							}
						}
							$field='COUNT(*) as tot_service, SUM(total_price) as tot_price';
							$data=$this->api_model->getWhereRowSelect('st_cart',array('user_id'=>$_POST['uid'],'merchant_id' =>$_POST['salon_id']),$field);

							$this->response_data['data']=$data;
							$this->response_data['data']->tot_price= ($data->tot_price !='')?$data->tot_price:'0';

					}
				}
			}
		}

		echo json_encode($this->response_data); 
	}

	function book_employee_post(){


		$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						
						
						if(isset($_POST['date']) && $_POST['date']!=""){
							  $date=date('Y-m-d',strtotime($_POST['date']));
							  $dayName=date("l", strtotime($_POST['date']));
							  $dayName=strtolower($dayName);
							  //$whr1 = "AND `st_offer_availability`.`days`='".$dayName."'";
							  $whr1 = "";
						}
						else{
							 $date=date('Y-m-d');
							 $dayName=date("l", strtotime($date));
							 $dayName=strtolower($dayName);
							 $whr1 = "";
						} 
						$sqlForservice="SELECT `st_cart`.`id`, `st_cart`.`service_id`,`st_category`.`category_name`, `name`, `st_merchant_category`.`duration`, `price`, `discount_price`,`buffer_time`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` ".$whr1.") LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$_POST['uid']." AND merchant_id=".$_POST['salon_id']." GROUP BY `st_cart`.`service_id`";

							
						$details= $this->api_model->custome_query($sqlForservice,'result');
						
						//echo $this->db->last_query().'<pre>'; print_r($details); die;

						if(empty($details))
							$this->response_data['response_message'] = 'No service added to cart..';
						else{
							foreach ($details as $key => $value){
								$serId[]=$value->service_id;
							}
								
							$sql=$this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$_POST['salon_id']."");
							
							$uidsRes= $sql->result();
                          	// echo $this->db->last_query().'<pre>'; print_r($uidsRes); die;

							if(!empty($uidsRes)){
								$users=array();
								foreach($uidsRes as $res){
									$users[$res->user_id][]=$res->service_id;
								}
								$userids=array();
								foreach($users as $k=>$v){
									//echo $k.'<pre>'; print_r($v); 
									$arraymatch = count(array_intersect($v, $serId)) >= count($serId);
									if($arraymatch){
										$userids[]=$k;
										
									}
								}		
								//print_r($userids); die;		
								if(!empty($userids)){
									if(isset($_POST['date']) && $_POST['date']!=""){
										//$select="SELECT st_users.id,first_name,last_name,profile_pic,type,(SELECT AVG(rate) FROM st_review WHERE emp_id=st_users.id) as rating,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND online_booking='1' AND days='".$dayName."' AND type='open' AND access='employee' AND merchant_id=".$_POST['salon_id']." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
										$select="SELECT st_users.id,first_name,last_name,profile_pic,type,(SELECT AVG(rate) FROM st_review WHERE emp_id=st_users.id) as rating,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id) as emp_time FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND online_booking='1' AND type='open' AND access='employee' AND merchant_id=".$_POST['salon_id']." AND st_users.id IN(".implode(',',$userids).") GROUP BY st_users.id ORDER BY emp_time ASC";
									}
									else{
										$select="SELECT st_users.id,first_name,last_name,profile_pic,type,(SELECT AVG(rate) FROM st_review WHERE emp_id=st_users.id) as rating,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id) as emp_time FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND online_booking='1' AND type='open' AND access='employee' AND merchant_id=".$_POST['salon_id']." AND st_users.id IN(".implode(',',$userids).") GROUP BY st_users.id ORDER BY emp_time ASC";
									}
							

									$employee_list= $this->api_model->custome_query($select,'result');
								
									if(!empty($employee_list)){
										$this->response_data['status'] = 1;
										$this->response_data['data'] = $employee_list;
										$this->response_data['response_message'] = 'Success';
										$i=0;
										foreach($employee_list as $val){
											$this->response_data['data'][$i]->rating=(!empty($val->rating)?number_format($val->rating,1):'0.0');
											$this->response_data['data'][$i]->emp_time=(!empty($val->emp_time)?$val->emp_time:'0');
											$i++;
										}

									}
									else
										$this->response_data['response_message'] = 'kein Mitarbeiter verfügbar';							 
								
								} else {
									$this->response_data['status'] = 0;
									$salon= $this->api_model->custome_query('SELECT business_name FROM st_users WHERE id='.$_POST["salon_id"].'','row');
									$this->response_data['response_message'] = 'Einer oder mehrere Services in deiner Buchung existieren nicht mehr bei '.$salon->business_name.'. Bitte führe eine neue Buchung über das Salonprofil durch';
								} 
								
							} else{
								$this->response_data['status'] = 0;
								$this->response_data['response_message'] = 'kein Mitarbeiter verfügbar';
							} 
					    }

					}
				}

			}
		}
			echo json_encode($this->response_data);

	}




function employee_time_post()
{

		$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('date', 'Date', 'required|trim');
		$this->form_validation->set_rules('employee_select', 'Employee Id', 'required|trim');

	if($this->form_validation->run() == FALSE)
	{
		validationErrorMsg();
	}
	else
	{
		if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
		else
		{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

			if(empty($is_valid_user->access_token))
			{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
			}
			else
			{   
					// if access token is 7 days older
				if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
				{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{
						

						if(isset($_POST['date']) && $_POST['date']!=""){
							  $date=date('Y-m-d',strtotime($_POST['date']));
							  $dayName=date("l", strtotime($_POST['date']));
							  $dayName=strtolower($dayName);
							  }
						else{
							$date=date('Y-m-d');
							$dayName=date("l", strtotime($date));
							$dayName=strtolower($dayName);
							}  
					
						$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`service_id`,`st_category`.`category_name`, `name`, st_merchant_category.duration,st_merchant_category.price_start_option,`st_merchant_category`.`type` as stype,setuptime,processtime,finishtime,`price`, `discount_price`,`buffer_time`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime`,`parent_service_id` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$_POST['uid']." AND st_cart.merchant_id=".$_POST['salon_id']."";
						//echo $sqlForservice; die;
						$booking_detail= $this->api_model->custome_query($sqlForservice,'result');

					    //echo $this->db->last_query(); print_r($booking_detail); die;
					  
						$totalDuration = 0;
					if(empty($booking_detail))
							$this->response_data['response_message'] = 'No service added to cart..';
					else
					{
						$price_start_option = $booking_detail[0]->price_start_option;
						//print_r($booking_detail); die;
								foreach ($booking_detail as $key => $value)
								{
									
									$serId[]=$value->service_id;
									 if($value->stype==1)
											$totalDuration       = $totalDuration+$value->duration;
									 else{  
											$totalMin            = $value->duration+$value->buffer_time;   
											$totalDuration       = $totalDuration+$totalMin;
										 }
								}
								
							
								$sql=$this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$_POST['salon_id']."");
							
								$uidsRes= $sql->result();
						if(!empty($uidsRes))
						{
							$users=array();
							foreach($uidsRes as $res)
							{
								$users[$res->user_id][]=$res->service_id;
							}

							$userids=array();
							foreach($users as $k=>$v)
							{
									$arraymatch = count(array_intersect($v, $serId)) >= count($serId);
									if($arraymatch)
									{
										$userids[]=$k;
									}
							}		
							
							
						
							if(!empty($userids))
							{
								
								
								//$time2  = $time;
								$wherT = "";
								$select = "SELECT st_users.id,first_name,last_name,profile_pic,type,starttime,endtime,starttime_two,endtime_two,(SELECT AVG(rate) FROM st_review WHERE emp_id=st_users.id) as myrating,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND online_booking='1' AND days='".$dayName."' ".$wherT." AND type='open' AND access='employee' AND merchant_id=".$_POST['salon_id']." AND st_users.id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
								
							
							
								$employee_list= $this->api_model->custome_query($select,'result');
							
								if(!empty($_POST['employee_select']) && $_POST['employee_select']!="any")
								{
									//$employeId=$_POST['employee_select'];
									$employeId=" AND employee_id=".$_POST['employee_select'];
									$avial_uid=$_POST['employee_select'];
									
									$select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$avial_uid."";
								}
								else
								{
								
										if(!empty($employee_list) && count($employee_list)==1)
										{
											$employeId=" AND employee_id=".$employee_list[0]->id;
											$avial_uid=$employee_list[0]->id;
										
											$select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$avial_uid."";
										}
										else
										{ 
											$employeId=" AND merchant_id=".$_POST['salon_id'];
											$avial_uid=$_POST['salon_id'];
											$selectStart="SELECT MIN(starttime) as starttime,MIN(starttime_two) as starttime_two FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id!=".$avial_uid." AND created_by=".$avial_uid."";
												
											$startTime= $this->api_model->custome_query($selectStart,'row');
											$dayslot = new stdClass();
											if(!empty($startTime->starttime))
											{
												$dayslot->starttime=$startTime->starttime; 
											}
											if(!empty($startTime->starttime_two))
												$dayslot->starttime_two = $startTime->starttime_two; 
											$selectEnd="SELECT MIN(starttime) as starttime,MAX(endtime) as endtime,MAX(endtime_two) as endtime_two FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id!=".$avial_uid." AND created_by=".$avial_uid."";
											$endTime =$this->api_model->custome_query($selectEnd,'row');
											if(!empty($endTime->endtime))
											{
												$dayslot->endtime=$endTime->endtime;
											}
											if(!empty($endTime->endtime_two))
												$dayslot->endtime_two = $endTime->endtime_two;
										}
								

								}	 



								/*if(!empty($employeId))
								{
									
									$selectBookingvailablity = "SELECT st_booking.id,employee_id,booking_time,booking_endtime,total_minutes,total_buffer,st_users.id as eid,st_availability.type FROM st_users INNER JOIN st_booking ON st_booking.employee_id=st_users.id INNER JOIN st_availability ON st_availability.user_id=st_users.id WHERE st_booking.status='confirmed' AND online_booking='1' AND days='".$dayName."' ".$wherT." AND type='open' AND st_users.status='active' AND st_booking.employee_id IN(".implode(',',$userids).") AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."'".$employeId." ORDER BY employee_id asc";
									$empBookSlot  = $this->api_model->custome_query($selectBookingvailablity,'result');
									
								}*/

								if(!empty($select123))
								{
									$dayslot= $this->api_model->custome_query($select123,'row');
								}
								/*$select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$_POST['salon_id']."";
								$dayslot= $this->api_model->custome_query($select123,'row');*/
					


								$totaldurationTim=0;

								if(!empty($_POST['time']))
								{
								$time=$_POST['time'];
								}
								else
								{
								$time=date('H:i:s');
								}
					
								if(!empty($_POST['date']))
								{
								$dateslct=$_POST['date'];
								}
								else
								{
								$dateslct=date('Y-m-d');
								}	
								// echo  $time; die;
								$checkTime=0;

								//if(!empty($dayslot)){
							
								if(!empty($dayslot->starttime) && !empty($dayslot->endtime))
								{
									foreach($booking_detail as $row)
									{
										if($row->stype==1)
												$totaldurationTim       = $totaldurationTim+$row->duration;
										else{  
												$totalMin               = $row->duration+$row->buffer_time;   
												$totaldurationTim       = $totaldurationTim+$totalMin;
											}		
										if ($row->parent_service_id) {
											$pstime = $this->api_model->select(
												'st_offer_availability',
												'starttime,endtime,days,type',
												array(
												'service_id'=>$row->parent_service_id,
												'days' => $dayName
												)
											);
											if ($pstime) {
												$row->starttime = $pstime[0]->starttime;
												$row->endtime = $pstime[0]->endtime;
												$row->type = $pstime[0]->type;
												$row->days = $pstime[0]->days;
											}
										}
									}
									
									$start = $dayslot->starttime; 
									//$end = $dayslot->endtime;
									//$end=date('H:i:s',strtotime($dayslot->endtime. "- ".$totaldurationTim." minutes"));
									$end=date('H:i:s',strtotime($dayslot->endtime));


									$tStart = strtotime($start);
									$tEnd = strtotime($end);
									$tNow = $tStart;
									$k=1;
									$chekDuration=1;
									$ii =0;

									//echo date('H:i:s',$tNow)."-".date('H:i:s',$tEnd);
									while($tNow <= $tEnd)
									{ 
										$dis=0;
										$total=0;
											
										$timeArray        = array();                           
										$ikj              = 0;
										$strtodatyetime   = $dateslct." ".date('H:i:s',$tNow);
										$employeeId       ="";
											
										foreach($booking_detail as $row)
										{
											
											$timeArray[$ikj]        = new stdClass;
											$bkstartTime            = $strtodatyetime;
											$timeArray[$ikj]->start = $bkstartTime; 
								
											if($row->stype==1)
											{
												//$totaldurationTim=$totaldurationTim+$row->duration;
																				
												$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
												$timeArray[$ikj]->end   = $bkEndTime;							    	
												$ikj++;	
												
												$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
												$timeArray[$ikj]        = new stdClass;
												$timeArray[$ikj]->start = $finishStart;
												
												$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
												$timeArray[$ikj]->end   = $finishEnd;
												$ikj++;
												
												$strtodatyetime=$finishEnd;
																			
											}
											else
											{
												$totalMin               = $row->duration+$row->buffer_time;   
												
												$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
												$timeArray[$ikj]->end   = $bkEndTime;							    	
												$ikj++;	
													
												//$totaldurationTim       = $totaldurationTim+$totalMin;
												
												$strtodatyetime=$bkEndTime;							   
											}
												
											if(!empty($row->type) && $row->type=='open')
											{ 
												if($row->starttime<=date('H:i:s',$tNow) && $row->endtime>=date('H:i:s',$tNow))
												{	  
													if(!empty($row->discount_price))
													{ 
														$dis=($row->price-$row->discount_price)+$dis;
														$total=$row->price+$total;
													}
													else
													{
														$total=$row->price+$total;
													} 
												}
												else
												{
													$total=$row->price+$total;
												}
											}
											else
											{ 
												$total=$row->price+$total;
											}
											$checkBoking=0;
											$checkallUser=0;


												
											if((!empty($_POST['employee_select']) && $_POST['employee_select']!='any' && !empty($employee_list)) || (!empty($employee_list) && count($employee_list)==1))
											{
													
													
												if(!empty($_POST['employee_select']) && $_POST['employee_select']!='any')
													{
														$employeeId = $_POST['employee_select'];
													}
												else 
													$employeeId  = $employee_list[0]->id;
								
											} // condition for any
												
				
												/*if(!empty($empBookSlot) && (empty($_GET['employee_select']) || $_GET['employee_select']=='any') && !empty($employee_list) && count($employee_list)!=1)
													{
															$employeeId = $employee_list[0]->id;
													
													}*/
				
									
									

										}
											
										if(!empty($available_employees))
										{
											$resultCheckSlot = checkTimeSlots($timeArray,$employeeId,$_POST['salon_id'],$totaldurationTim,"",json_decode($available_employees));
										}
										else{		
											$resultCheckSlot = checkTimeSlots($timeArray,$employeeId,$_POST['salon_id'],$totaldurationTim);
										}
							
										//echo $checkBoking;
										
										if($checkBoking!=1 && $resultCheckSlot==true)
										{
											/*if($checkBoking!=1)
											{*/
											//if($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s')){ }
						
											/*if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s')) || $checkallUser!=0){ }*/
											if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s'))){ }
											else
											{ 
												$checkTime++;
												$this->response_data['status']=1;
												$this->response_data['response_message']='Success';
												$this->response_data['data'][$ii]['price_start_option']=$price_start_option;
												$this->response_data['data'][$ii]['time']=date("H:i",$tNow);
												$this->response_data['data'][$ii]['price']= $total-$dis;
												$this->response_data['data'][$ii]['discount']= (!empty($dis))?$total:'0';
												$ii++;
											} 
										}
										$k++; $tNow = strtotime('+15 minutes',$tNow); 
									}
										
								}
								else 
								{   
									$checkTime++; 
									$this->response_data['response_message']="Leider sind am ausgewählten Tag keine freien Termine verfügbar. Bitte wähle einen anderen Tag";
								} 


								if(!empty($dayslot->starttime_two) && !empty($dayslot->endtime_two))
								{
									
							
									/*foreach($booking_detail as $row)
									{
									$ti=$row->duration+$row->buffer_time;
									$totaldurationTim=$totaldurationTim+$ti;
									}*/
						
									$start = $dayslot->starttime_two; 
									//$end=date('H:i:s',strtotime($dayslot->endtime_two. "- ".$totaldurationTim." minutes"));
									//$end= $dayslot->endtime;
									$end    = date('H:i:s',strtotime($dayslot->endtime_two));

									$tStart = strtotime($start);
									$tEnd = strtotime($end);
									$tNow = $tStart;
									$k=1;
									$chekDuration=1;
									while($tNow <= $tEnd)
									{ 
										$dis=0;
										$total=0;
										$timeArray        = array();                           
										$ikj              = 0;
										$strtodatyetime   = $dateslct." ".date('H:i:s',$tNow);
										$employeeId       = "";
										foreach($booking_detail as $row)
										{
												
											$timeArray[$ikj]        = new stdClass;
											$bkstartTime            = $strtodatyetime;
											$timeArray[$ikj]->start = $bkstartTime;
							
											if($row->stype==1)
											{
												//$totaldurationTim=$totaldurationTim+$row->duration;
																	
												$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
												$timeArray[$ikj]->end   = $bkEndTime;							    	
												$ikj++;	
												
												$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
												$timeArray[$ikj]        = new stdClass;
												$timeArray[$ikj]->start = $finishStart;
												
												$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
												$timeArray[$ikj]->end   = $finishEnd;
												$ikj++;
												
												$strtodatyetime=$finishEnd;
																		
											}
											else
											{
												$totalMin               = $row->duration+$row->buffer_time;   
												
												$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
												$timeArray[$ikj]->end   = $bkEndTime;							    	
												$ikj++;	
												
												//$totaldurationTim       = $totaldurationTim+$totalMin;
												
												$strtodatyetime=$bkEndTime;							   
											} 
											
											
											if(!empty($row->type) && $row->type=='open')
											{ 
												if($row->starttime<=date('H:i:s',$tNow) && $row->endtime>=date('H:i:s',$tNow))
												{	  
													if(!empty($row->discount_price))
													{ 
														$dis=($row->price-$row->discount_price)+$dis;
														$total=$row->discount_price+$total;
													}
													else
													{
														$total=$row->price+$total;
													} 
												}
												else
												{
													$total=$row->price+$total;
												}
											}
											else
											{ 
												$total=$row->price+$total;
											}

							
											$checkBoking=0;
											$checkallUser=0;
									
											if((!empty($_GET['employee_select']) && $_GET['employee_select']!='any' && !empty($employee_list)) || (!empty($employee_list) && count($employee_list)==1))
											{
												if(!empty($_GET['employee_select']) && $_GET['employee_select']!='any')
												{
														$employeeId = $_GET['employee_select'];
												}
												else 
													$employeeId  = $employee_list[0]->id;									  
											}
												//echo '<pre>'; print_r($employee_list); die;	  
													
												/*if(!empty($empBookSlot) && (empty($_GET['employee_select']) || $_GET['employee_select']=='any') && !empty($employee_list) && count($employee_list)!=1)
													{
														$employeeId = $employee_list[0]->id;
												
													}*/	
										
												
										}
										
										if(!empty($available_employees))
										{
											$resultCheckSlot = checkTimeSlots($timeArray,$employeeId,$_POST['salon_id'],$totaldurationTim,"",json_decode($available_employees));
										}
										else{		
											$resultCheckSlot = checkTimeSlots($timeArray,$employeeId,$_POST['salon_id'],$totaldurationTim);
										}
										//if($checkBoking!=1)
										if($checkBoking!=1 && $resultCheckSlot==true)
										{
											//if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s')) || $checkallUser!=0){ }
											if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s'))){ }
											else{ $checkTime++; 

												$this->response_data['status']=1;
												$this->response_data['response_message']='Success';
												$this->response_data['data'][$ii]['price_start_option']=$price_start_option;
												$this->response_data['data'][$ii]['time']=date("H:i",$tNow);
												$this->response_data['data'][$ii]['price']= $total-$dis;
												$this->response_data['data'][$ii]['discount']= (!empty($dis))?$total:'0';		
												$ii++;	
												} 
										} 
										//echo $k;
										$k++;
										$tNow = strtotime('+15 minutes',$tNow); 
											
									}


		
								}
							}
							else 
							{ 
								$this->response_data['status'] = 0;
								$this->response_data['response_message'] = 'kein Mitarbeiter verfügbar'; 
							}
						
						}
						else 
						{
							$this->response_data['status'] = 0;
							$this->response_data['response_message'] = 'kein Mitarbeiter verfügbar'; 
						}

					}

				}
			}

		}
	}
	echo json_encode($this->response_data);

}


function employee_time_post_old(){


		$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('date', 'Date', 'required|trim');
		$this->form_validation->set_rules('employee_select', 'Key', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						

						if(isset($_POST['date']) && $_POST['date']!=""){
							  $date=date('Y-m-d',strtotime($_POST['date']));
							  $dayName=date("l", strtotime($_POST['date']));
							  $dayName=strtolower($dayName);
							  }
						else{
							$date=date('Y-m-d');
							$dayName=date("l", strtotime($date));
							$dayName=strtolower($dayName);
							}  
									
						$sqlForservice="SELECT `st_cart`.`id`, `st_cart`.`service_id`,`st_category`.`category_name`, `name`, `st_merchant_category`.`duration`, `price`, `discount_price`,`buffer_time`,`days`,`type`,`starttime`,`endtime` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$_POST['uid']."";

						$booking_detail= $this->api_model->custome_query($sqlForservice,'result');
					    
					   // echo $this->db->last_query(); die;
					    if(empty($booking_detail))
							$this->response_data['response_message'] = 'No service added to cart..';
						else{
							foreach ($booking_detail as $key => $value){
								$serId[]=$value->service_id;
							}
							
							$sql=$this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$_POST['salon_id']."");
							
							$uidsRes= $sql->result();
						if(!empty($uidsRes)){
				               $users=array();
							 foreach($uidsRes as $res){
								 $users[$res->user_id][]=$res->service_id;
								 }

				            $userids=array();
							foreach($users as $k=>$v){
								$arraymatch = count(array_intersect($v, $serId)) == count($serId);
								if($arraymatch){
									$userids[]=$k;
									
									}
								}		
									
				           if(!empty($userids)){
				           	  $select="SELECT st_users.id,first_name,last_name,profile_pic,type FROM st_users INNER JOIN st_availability ON st_availability.user_id=st_users.id  WHERE status='active' AND online_booking='1' AND days='".$dayName."' AND type='open' AND access='employee' AND merchant_id=".$_POST['salon_id']." AND st_users.id IN(".implode(',',$userids).")";

							 $employee_list= $this->api_model->custome_query($select,'result');
							 
							 if(!empty($_POST['employee_select']) && $_POST['employee_select']!="any")
							     {
								  //$employeId=$_POST['employee_select'];
							     	 $employeId=" AND employee_id=".$_POST['employee_select'];
									 $avial_uid=$_POST['employee_select'];
									  
									 $select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$avial_uid."";
								 }
								 else{
								 	
								   if(!empty($employee_list) && count($employee_list)==1){
									   $employeId=" AND employee_id=".$employee_list[0]->id;
									   $avial_uid=$employee_list[0]->id;
									   
									   $select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$avial_uid."";
									   }
									else{ $employeId=" AND merchant_id=".$_POST['salon_id'];
											    $avial_uid=$_POST['salon_id'];
											    $selectStart="SELECT MIN(starttime) as starttime FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id!=".$avial_uid." AND created_by=".$avial_uid."";
											   
											    $startTime= $this->api_model->custome_query($selectStart,'row');
											    $dayslot = new stdClass();
											    if(!empty($startTime->starttime)){
													$dayslot->starttime=$startTime->starttime; 
										     	}
											    $selectEnd="SELECT MIN(starttime) as starttime,MAX(endtime) as endtime FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id!=".$avial_uid." AND created_by=".$avial_uid."";
											    $endTime =$this->api_model->custome_query($selectEnd,'row');
											    if(!empty($endTime->endtime)){
											    	$dayslot->endtime=$endTime->endtime;
											  		}
											    
										}
						 		 

						 		 }	 


							 /* else{
								   if(!empty($this->data['employee_list'])){
									   $employeId=$employee_list[0]->id;
									   }
									 else $employeId="";
								  }	 */


							  if(!empty($employeId)){
						        $selectBookingvailablity="SELECT * FROM st_booking WHERE status='confirmed' AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."'".$employeId." ORDER BY employee_id asc";
						        $empBookSlot= $this->api_model->custome_query($selectBookingvailablity,'result');
						      }

						      if(!empty($select123)){
			     				$dayslot= $this->api_model->custome_query($select123,'row');
			    				}
							  /*$select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$_POST['salon_id']."";
							  $dayslot= $this->api_model->custome_query($select123,'row');*/
							 


						$totaldurationTim=0;

					      if(!empty($_POST['time'])){
							 $time=$_POST['time'];
							}
						 else{
							 $time=date('H:i:s');
							}
						
						 if(!empty($_POST['date'])){
							 $dateslct=$_POST['date'];
							}
						 else{
							 $dateslct=date('Y-m-d');
							}	
					   // echo  $time; die;
					    $checkTime=0;

					    //if(!empty($dayslot)){
					    if(!empty($dayslot->starttime) && !empty($dayslot->endtime)){
					     
					     	 foreach($booking_detail as $row){
								$ti=$row->duration+$row->buffer_time;
					            $totaldurationTim=$totaldurationTim+$ti;
							 	}

							$start = $dayslot->starttime; 
							//$end = $dayslot->endtime;
							$end=date('H:i:s',strtotime($dayslot->endtime. "- ".$totaldurationTim." minutes"));


							$tStart = strtotime($start);
							$tEnd = strtotime($end);
							$tNow = $tStart;
							$k=1;
							$chekDuration=1;
							$ii =0;

							while($tNow <= $tEnd){ 
                           $dis=0;
                           $total=0;

                            foreach($booking_detail as $row){
							   if(!empty($row->type) && $row->type=='open'){ 
								if($row->starttime<=date('H:i:s',$tNow) && $row->endtime>=date('H:i:s',$tNow)){	  
									if(!empty($row->discount_price)){ 
								    	$dis=($row->price-$row->discount_price)+$dis;
									    $total=$row->price+$total;
								     }
								    else{
									   $total=$row->price+$total;
									 } 
								   }
								   else{
									   $total=$row->price+$total;
									   }
								}else{ 
								 $total=$row->price+$total;
							    }
							  $checkBoking=0;
							  $checkallUser=0;


							  if((!empty($empBookSlot) && !empty($_POST['employee_select']) && $_POST['employee_select']!='any' && !empty($employee_list)) || (!empty($empBookSlot) && !empty($employee_list) && count($employee_list)==1)){

								  foreach($empBookSlot as $eslot){
								  	  $estarttime=date('H:i:s',strtotime($eslot->booking_time. "- ".$totaldurationTim." minutes"));
									 // $estarttime=date('H:i:s',strtotime($eslot->booking_time));
									  $eendtime=date('H:i:s',strtotime($eslot->booking_endtime));
									   if($estarttime<=date('H:i:s',$tNow) && $eendtime>=date('H:i:s',$tNow)){
								        	 $checkBoking=1;		  
										  	 break;
										  }
									  }
								  }
								  

								  if(!empty($empBookSlot) && (empty($_POST['employee_select']) || $_POST['employee_select']=='any') && !empty($employee_list) && count($employee_list)!=1){
								   $empSlotDet=[];
								   foreach($empBookSlot as $eslot1){
									$empSlotDet[$eslot1->employee_id][]=$eslot1;   
								   }
								   $kl=0;
                                   
								  foreach($empSlotDet as $k=>$v){
									  $jk=0;
									  foreach($v as $eslot){
									  $estarttime=date('H:i:s',strtotime($eslot->booking_time. "- ".$totaldurationTim." minutes"));
									  $eendtime=date('H:i:s',strtotime($eslot->booking_endtime));
									   if($estarttime<=date('H:i:s',$tNow) && $eendtime>=date('H:i:s',$tNow)){
										 $jk++;
								         break;
										  }
									    }
									   if($jk>=1){
										   $kl++;
										   }
										  
									  }
									if($kl==count($employee_list)){
										$checkallUser=1;
										}
								  }	

							}
							
						if($checkBoking!=1){
							//if($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s')){ }
							
							if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s')) || $checkallUser!=0){ }
							else{ 
								$checkTime++;
							$this->response_data['status']=1;
							$this->response_data['response_message']='Success';
							$this->response_data['data'][$ii]['time']=date("H:i",$tNow);
							$this->response_data['data'][$ii]['price']= $total-$dis;
							$this->response_data['data'][$ii]['discount']= (!empty($dis))?$total:'0';
							$ii++;
								} 
						} $k++; $tNow = strtotime('+15 minutes',$tNow); 
						}
					 }
                     else {   
                          	  $checkTime++; 
                            $this->response_data['response_message']="Leider sind am ausgewählten Tag keine freien Termine verfügbar. Bitte wähle einen anderen Tag";
							 } 


							 if(!empty($dayslot->starttime_two) && !empty($dayslot->endtime_two)){
							 
							  		foreach($booking_detail as $row){
										$ti=$row->duration+$row->buffer_time;
					            		$totaldurationTim=$totaldurationTim+$ti;
							 			}
							
									$start = $dayslot->starttime_two; 
									$end=date('H:i:s',strtotime($dayslot->endtime_two. "- ".$totaldurationTim." minutes"));
							 //$end= $dayslot->endtime;

									$tStart = strtotime($start);
									$tEnd = strtotime($end);
									$tNow = $tStart;
									$k=1;
									$chekDuration=1;
									while($tNow <= $tEnd){ 
			                    	   $dis=0;
			                       		$total=0;
                            
                           			 foreach($booking_detail as $row){
							 
					            
									   if(!empty($row->type) && $row->type=='open'){ 
										if($row->starttime<=date('H:i:s',$tNow) && $row->endtime>=date('H:i:s',$tNow)){	  
											if(!empty($row->discount_price)){ 
										    	$dis=($row->price-$row->discount_price)+$dis;
											    $total=$row->price+$total;
										     }
										    else{
											   $total=$row->price+$total;
											 } 
										   }
										   else{
											   $total=$row->price+$total;
											   }
										}else{ 
												 $total=$row->price+$total;
							   				 }

							 
									 $checkBoking=0;
		                             $checkallUser=0;
									  if((!empty($empBookSlot) && !empty($_POST['employee_select']) && $_POST['employee_select']!='any' && !empty($employee_list)) || (!empty($empBookSlot) && !empty($employee_list) && count($employee_list)==1)){
		                                     
										  foreach($empBookSlot as $eslot){
											  
											  $estarttime=date('H:i:s',strtotime($eslot->booking_time. "- ".$totaldurationTim." minutes"));
											  $eendtime=date('H:i:s',strtotime($eslot->booking_endtime));
											   if($estarttime<=date('H:i:s',$tNow) && $eendtime>=date('H:i:s',$tNow)){
										        $checkBoking=1;	  
												  break;
												  }
											  }
											  
										  }
								  
							  if(!empty($empBookSlot) && (empty($_POST['employee_select']) || $_POST['employee_select']=='any') && !empty($employee_list) && count($employee_list)!=1){
								   $empSlotDet=[];
								   		foreach($empBookSlot as $eslot1){
											$empSlotDet[$eslot1->employee_id][]=$eslot1;   
								   			}
								  //echo "<pre>"; print_r($empSlotDet); die;
                                   $kl=0;
                                   
								  foreach($empSlotDet as $key=>$v){
									  $jk=0;
									
									  foreach($v as $eslot){
										   
											  $estarttime=date('H:i:s',strtotime($eslot->booking_time. "- ".$totaldurationTim." minutes"));
											  $eendtime=date('H:i:s',strtotime($eslot->booking_endtime));
											   if($estarttime<=date('H:i:s',$tNow) && $eendtime>=date('H:i:s',$tNow)){
												  // echo $k;
										         $jk++;
										         break;
												  }
									  	  }
									  	 if($jk>=1){
										   $kl++; }
										  
									  }
									
									if($kl==count($employee_list)){
										$checkallUser=1;
										}
								  		
								  }	  
							}
							
							if($checkBoking!=1){
								if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s')) || $checkallUser!=0){ }
								else{ $checkTime++; 

								$this->response_data['status']=1;
							$this->response_data['response_message']='Success';
							$this->response_data['data'][$ii]['time']=date("H:i",$tNow);
							$this->response_data['data'][$ii]['price']= $total-$dis;
							$this->response_data['data'][$ii]['discount']= (!empty($dis))?$total:'0';		
							$ii++;	?>
                        	<?php } } 
                        	 //echo $k;
                         		$k++;
                         			$tNow = strtotime('+15 minutes',$tNow); 
                       			}
                       		 }


		 
						    } else { $this->response_data['status'] = 0;
										$this->response_data['response_message'] = 'kein Mitarbeiter verfügbar'; }
							
						   } else { $this->response_data['status'] = 0;
										$this->response_data['response_message'] = 'kein Mitarbeiter verfügbar'; }

						}

					}
				}

			}
		}
			echo json_encode($this->response_data);

	}

		function book_detailforconfirm_post(){


		//$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('employee_select', 'Employee Id', 'required|trim');
		$this->form_validation->set_rules('date', 'Booking Date', 'required|trim');
		$this->form_validation->set_rules('time', 'Booking time', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						
						
						 if(isset($_POST['date']) && $_POST['date']!=""){
							  $date=date('Y-m-d',strtotime($_POST['date']));
							  $dayName=date("l", strtotime($_POST['date']));
							  $dayName=strtolower($dayName);
							  }
						else{
							$date=date('Y-m-d');
							$dayName=date("l", strtotime($date));
							$dayName=strtolower($dayName);
							} 

							
							$sqlForservice = "SELECT `st_cart`.`id`, `st_cart`.`merchant_id`,(SELECT cancel_booking_allow FROM st_users WHERE `st_users`.`id` = `st_cart`.`merchant_id`) as cancel_booking_allow,(SELECT hr_before_cancel  FROM st_users WHERE `st_users`.`id` = `st_cart`.`merchant_id`) as hr_before_cancel ,`st_cart`.`service_id`,`st_category`.`category_name`, `name`, st_merchant_category.duration,st_merchant_category.price_start_option,`st_merchant_category`.`type` as stype,setuptime,processtime,finishtime,`price`, `discount_price`,`buffer_time`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime`, `parent_service_id` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$_POST['uid']."";
							
							$details= $this->api_model->custome_query($sqlForservice,'result');

	    					$totaldurationTim=0;
	    					if(empty($details)){
								$this->response_data['status'] = 0;
								$this->response_data['response_message'] = 'No services added in cart';
							  }
							 else{ 

							 $salon_images=$this->api_model->getWhereRowSelect('st_banner_images',array('user_id' => $details[0]->merchant_id),'id,user_id,image,image1,image2,image3,image4');

							  $i=0;  $dis= 0; $total= 0; $discount=0;
							 	$this->response_data['status'] = 1;
								$this->response_data['response_message'] = 'Success';
							 		foreach($details as $row){
							 			$discount = 0;
							 			
										if($row->stype==1)
												$totaldurationTim       = $totaldurationTim+$row->duration;
										else{  
											$totalMin            = $row->duration+$row->buffer_time;   
											$totaldurationTim       = $totaldurationTim+$totalMin;
										}
										
										if ($row->parent_service_id) {
											$pstime = $this->api_model->select(
												'st_offer_availability',
												'starttime,endtime,days,type',
												array(
												'service_id'=>$row->parent_service_id,
												'days' => $dayName
												)
											);
											if ($pstime) {
												$row->starttime = $pstime[0]->starttime;
												$row->endtime = $pstime[0]->endtime;
												$row->type = $pstime[0]->type;
												$row->days = $pstime[0]->days;
											}
										}
							 			//$ti=$row->duration+$row->buffer_time;
					            		//$totaldurationTim=$totaldurationTim+$ti;
							   
									   if(!empty($row->type) && $row->type=='open'){ 
									   	//echo ($row->starttime." <= ".$time ."&&". $row->endtime." >= ".$time);
									   	//die;
										if($row->starttime<=$time.':00' && $row->endtime>=$time.':00'){	  
											if(!empty($row->discount_price)){ 
											$discount=get_discount_percent($row->price,$row->discount_price);
					                        $dis=($row->price-$row->discount_price)+$dis;
					                        $total=$row->discount_price+$total; 
									        $priceCh=$row->discount_price;
									        }
									       else{
					                         $total=$row->price+$total; 
					                         $priceCh=$row->price;
					                        }
								           }
								        else{
											$total=$row->price+$total; 
					                         $priceCh=$row->price;
											}
									   }
								       else{
										   $priceCh=$row->price;
					                      $total=$row->price+$total; 
					                     } 

					                    $this->response_data['data']['cart'][$i]['id']=$row->id; 
					                    $this->response_data['data']['cart'][$i]['merchant_id']=$row->merchant_id;
					                    $this->response_data['data']['cart'][$i]['service_id']=$row->service_id;
					                    $this->response_data['data']['cart'][$i]['category_name']=$row->category_name;
					                    $this->response_data['data']['cart'][$i]['price_start_option']=$row->price_start_option;
					                    $this->response_data['data']['cart'][$i]['name']=$row->name;
					                    $this->response_data['data']['cart'][$i]['buffer_time']=$row->buffer_time;
					                    $this->response_data['data']['cart'][$i]['duration']=$row->duration;
					                    $this->response_data['data']['cart'][$i]['price']=$row->price;  //$priceCh;
					                    $this->response_data['data']['cart'][$i]['discount_price']=(!empty($discount))?$dis:0;
					                    $this->response_data['data']['cart'][$i]['discount_percent']=$discount;			//$row->discount_price;
					                   $this->response_data['data']['cart'][$i]['cancel_booking_allow']=$row->cancel_booking_allow;
					                   $this->response_data['data']['cart'][$i]['hr_before_cancel']=$row->hr_before_cancel;

					                   $i++;
					                 }
					                if(!empty($salon_images)){
											 $this->response_data['data']['salonimages']=$salon_images;
										 }
										 else
										 	 $this->response_data['data']['salonimages']=new stdClass();


					                  $this->response_data['data']['totalDuration']=$totaldurationTim;
									  $this->response_data['data']['tot_disc']=(!empty($dis))?$dis:0;
					                  $this->response_data['data']['total']=$total;
					                 	

									
							   	} 
					}
				}


			}
		}

			echo json_encode($this->response_data);

	}

	function book_detailforconfirm_old_post(){


		//$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('employee_select', 'Employee Id', 'required|trim');
		$this->form_validation->set_rules('date', 'Booking Date', 'required|trim');
		$this->form_validation->set_rules('time', 'Booking time', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						
						
						 if(isset($_POST['date']) && $_POST['date']!=""){
							  $date=date('Y-m-d',strtotime($_POST['date']));
							  $dayName=date("l", strtotime($_POST['date']));
							  $dayName=strtolower($dayName);
							  }
						else{
							$date=date('Y-m-d');
							$dayName=date("l", strtotime($date));
							$dayName=strtolower($dayName);
							} 

							$sqlForservice="SELECT `st_cart`.`id`,`st_cart`.`merchant_id`,`st_cart`.`service_id`,`st_category`.`category_name`, `name`,`buffer_time`, `st_merchant_category`.`duration`, `price`, `discount_price`,`days`,`type`,`starttime`,`endtime` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$_POST['uid']."";
							
	    					$details= $this->api_model->custome_query($sqlForservice,'result');

	    					$totaldurationTim=0;
	    					if(empty($details)){
								$this->response_data['status'] = 0;
								$this->response_data['response_message'] = 'No services added in cart';
							  }
							 else{ 

							 $salon_images=$this->api_model->getWhereRowSelect('st_banner_images',array('user_id' => $details[0]->merchant_id),'id,user_id,image,image1,image2,image3,image4');

							  $i=0;  $dis= 0; $total= 0; $discount=0;
							 	$this->response_data['status'] = 1;
								$this->response_data['response_message'] = 'Success';
							 		foreach($details as $row){
							 			
							 			$ti=$row->duration+$row->buffer_time;
					            		$totaldurationTim=$totaldurationTim+$ti;
							   
									   if(!empty($row->type) && $row->type=='open'){ 
										if($row->starttime<=$time && $row->endtime>=$time){	  
											if(!empty($row->discount_price)){ 
											$discount=get_discount_percent($row->price,$row->discount_price);
					                        $dis=($row->price-$row->discount_price)+$dis;
					                        $total=$row->discount_price+$total; 
									        $priceCh=$row->discount_price;
									        }
									       else{
					                         $total=$row->price+$total; 
					                         $priceCh=$row->price;
					                        }
								           }
								        else{
											$total=$row->price+$total; 
					                         $priceCh=$row->price;
											}
									   }
								       else{
										   $priceCh=$row->price;
					                      $total=$row->price+$total; 
					                     } 

					                    $this->response_data['data']['cart'][$i]['id']=$row->id; 
					                   $this->response_data['data']['cart'][$i]['merchant_id']=$row->merchant_id;
					                    $this->response_data['data']['cart'][$i]['service_id']=$row->service_id;
					                    $this->response_data['data']['cart'][$i]['category_name']=$row->category_name;
					                    $this->response_data['data']['cart'][$i]['name']=$row->name;
					                    $this->response_data['data']['cart'][$i]['buffer_time']=$row->buffer_time;
					                    $this->response_data['data']['cart'][$i]['duration']=$row->duration;
					                    $this->response_data['data']['cart'][$i]['price']=$priceCh;  //$row->price;
					                    $this->response_data['data']['cart'][$i]['discount_price']=(!empty($discount))?$dis:0;			//$row->discount_price;
					                   $i++;
					                 }
					                if(!empty($salon_images)){
											 $this->response_data['data']['salonimages']=$salon_images;
										 }
										 else
										 	 $this->response_data['data']['salonimages']=new stdClass();


					                  $this->response_data['data']['totalDuration']=$totaldurationTim;
									  $this->response_data['data']['tot_disc']=(!empty($dis))?$dis:0;
					                  $this->response_data['data']['total']=$total;
					                 	

									
							   	} 
					}
				}


			}
		}

			echo json_encode($this->response_data);

	}


	public function remove_service_post()
	{
		
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('cart_id', 'cart Id', 'required|trim');
		$this->form_validation->set_rules('employee_select', 'Employee Id', 'required|trim');
		$this->form_validation->set_rules('date', 'Booking Date', 'required|trim');
		$this->form_validation->set_rules('time', 'Booking time', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{

						if($this->api_model->delete('st_cart',array('user_id' => $uid,'id' => $cart_id))){


							if(isset($_POST['date']) && $_POST['date']!=""){
							  $date=date('Y-m-d',strtotime($_POST['date']));
							  $dayName=date("l", strtotime($_POST['date']));
							  $dayName=strtolower($dayName);
							  }
						else{
							$date=date('Y-m-d');
							$dayName=date("l", strtotime($date));
							$dayName=strtolower($dayName);
							} 

							$sqlForservice="SELECT `st_cart`.`id`,`st_cart`.`merchant_id`,`st_cart`.`service_id`,`buffer_time`, `st_merchant_category`.`duration`, `price`, `discount_price`,`days`,`st_offer_availability`.`type`,st_merchant_category.type as stype,`starttime`,`endtime` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$_POST['uid']."";
							
	    					$details= $this->api_model->custome_query($sqlForservice,'result');
	    					$totaldurationTim=0;
	    					if(empty($details)){
								$this->response_data['response_message'] = 'No services added in cart';
								$this->response_data['data']['totalDuration']=0;
								$this->response_data['data']['tot_disc']=0;
					            $this->response_data['data']['total']=0;
							  }
							 else{  $i=0;  $dis= 0; $total= 0; $discount=0;
							 		foreach($details as $row){
										
							 			if($row->stype==1)
							 			   $ti=$row->duration;
							 			else
							 			  $ti=$row->duration+$row->buffer_time;
							 			
					            		$totaldurationTim=$totaldurationTim+$ti;
							   
									   if(!empty($row->type) && $row->type=='open'){ 
										if($row->starttime<=$time && $row->endtime>=$time){	  
											if(!empty($row->discount_price)){ 
											$discount=get_discount_percent($row->price,$row->discount_price);
					                        $dis=($row->price-$row->discount_price)+$dis;
					                        $total=$row->discount_price+$total; 
									        $priceCh=$row->discount_price;
									        }
									       else{
					                         $total=$row->price+$total; 
					                         $priceCh=$row->price;
					                        }
								           }
								        else{
											$total=$row->price+$total; 
					                         $priceCh=$row->price;
											}
									   }
								       else{
										   $priceCh=$row->price;
					                      $total=$row->price+$total; 
					                     } 

					                   $i++;
					                 }
					                  $this->response_data['data']['totalDuration']=$totaldurationTim;
									  $this->response_data['data']['tot_disc']=(!empty($dis))?$dis:0;
					                  $this->response_data['data']['total']=$total;
					                  $this->response_data['response_message']='Success'; 
					                 	
					            } 




							$this->response_data['status'] = 1;
							//$this->response_data['response_message']='Success'; 
						}
						else
							$this->response_data['response_message']='Something is wrong.';
					}
				}
			}
		}
		echo json_encode($this->response_data);
	}


	function booking_confirm_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('employee_select', 'Employee Id', 'required|trim');
		$this->form_validation->set_rules('date', 'Booking Date', 'required|trim');
		$this->form_validation->set_rules('time', 'Booking time', 'required|trim');
		$this->form_validation->set_rules('totalDuration','Booking duration', 'required|trim');
		$this->form_validation->set_rules('salon_id', 'Salon Id', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
				$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
					
						if($this->api_model->get_datacount('st_client_block',array('client_id'=>$uid,'merchant_id'=> $salon_id))>0){
							echo json_encode(array('status'=>0, 'access_token' =>'','response_message'=>'Du bist aktuell nicht berechtigt, Buchungen in diesem Salon zu erstellen.')); die;
						}
						else if(($this->api_model->get_datacount('st_booking',array('user_id'=>$uid,'status' => 'completed')) == 0) && ($this->api_model->get_datacount('st_booking',array('user_id'=>$uid,'status'=>'confirmed')) > 2)){
							echo json_encode(array('status'=>0, 'access_token' =>'','response_message'=>'Zum Schutz unserer Salons wird Ihr Profil bei styletimer erst für weitere Buchungen freigeschaltet, nachdem mindestens eine Buchung erfolgreich vom Salon abgeschlossen wurde. Danach können Sie beliebig viele Buchungen gleichzeitig vornehmen.')); die;
						}
						else if($this->api_model->get_datacount('st_users',array('id'=> $salon_id,'online_booking' => 0)) > 0){
							echo json_encode(array('status' =>0,'access_token' =>'', 'response_message' => 'Der Salon nimmt im Moment keine neuen Buchungen über styletimer entgegen'));  die;
						}

						//die;
									 
						$empId=$_POST['employee_select'];
						if(isset($_POST['date']) && $_POST['date']!="") {
							$date=date('Y-m-d',strtotime($_POST['date']));
							$dayName=date("l", strtotime($_POST['date']));
							$dayName=strtolower($dayName);
						}
						else{
							$date=date('Y-m-d');
							$dayName=date("l", strtotime($date));
							$dayName=strtolower($dayName);
						}
											
						$sqlForservice   = "SELECT `st_cart`.`id`,`st_cart`.`merchant_id`,`st_cart`.`service_id`,`st_category`.`category_name`, `name`,`buffer_time`, st_merchant_category.duration,`st_merchant_category`.`type` as stype,setuptime,processtime,finishtime,`price`, `discount_price`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime`, `parent_service_id`, (SELECT notification_time FROM st_users WHERE st_users.id=st_cart.merchant_id) as notification_time FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$_POST['uid']."";
						
						/*$sqlForservice="SELECT `st_cart`.`id`,`st_cart`.`merchant_id`,`st_cart`.`service_id`,`st_category`.`category_name`, `name`,`buffer_time`, `st_merchant_category`.`duration`, `price`, `discount_price`,`days`,`type`,`starttime`,`endtime` FROM `st_cart` LEFT JOIN `st_merchant_category` ON `st_cart`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_cart`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `user_id` = ".$_POST['uid'].""; */
						
						$booking_detail= $this->api_model->custome_query($sqlForservice,'result');
						if(!empty($booking_detail))
						{
							
							if($empId=='any')
							{
									
								if(empty($booking_detail))
								{
									$this->response_data['status'] = 0;
									$this->response_data['response_message']='No record found';
								}
								else
								{
									$timeArray        = array();                           
									$ikj              = 0;
									$strtodatyetime   = $_POST['date']." ".$_POST['time'];
									
									foreach ($booking_detail as $key => $value)
									{
										$serId[]=$value->service_id;
										
										$timeArray[$ikj]        = new stdClass;
										$bkstartTime            = $strtodatyetime;
										$timeArray[$ikj]->start = $bkstartTime; 
										
										if($value->stype==1)
										{
											//$totaldurationTim=$totaldurationTim+$row->duration;
																			
											$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$value->setuptime.' minute'));
											$timeArray[$ikj]->end   = $bkEndTime;							    	
											$ikj++;	
											
											$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$value->processtime.' minute'));									
											$timeArray[$ikj]        = new stdClass;
											$timeArray[$ikj]->start = $finishStart;
											
											$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$value->finishtime.' minute'));
											$timeArray[$ikj]->end   = $finishEnd;
											$ikj++;
											
											$strtodatyetime=$finishEnd;
																		
										}
										else
										{
											$totalMin               = $value->duration+$value->buffer_time;   
											
											$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
											$timeArray[$ikj]->end   = $bkEndTime;							    	
											$ikj++;	
											
											//$totaldurationTim       = $totaldurationTim+$totalMin;
											
											$strtodatyetime=$bkEndTime;							   
										}
									}
										
									$id=$booking_detail[0]->merchant_id;
								
									$sql=$this->db->query("SELECT service_id,user_id FROM st_service_employee_relation WHERE service_id IN(".implode(',',$serId).") AND created_by= ".$id."");
						
									$uidsRes= $sql->result();
									if(!empty($uidsRes))
									{
										$users=array();
										foreach($uidsRes as $res)
										{
											$users[$res->user_id][]=$res->service_id;
										}

										$userids=array();
										foreach($users as $k=>$v)
										{
											$arraymatch = count(array_intersect($v, $serId)) == count($serId);
											if($arraymatch)
											{
											$userids[]=$k;
											}
										}		
												
										if(!empty($userids))
										{
											
											$select="SELECT id,first_name,last_name,profile_pic,(SELECT SUM(total_time) FROM st_booking WHERE employee_id = st_users.id AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."') as emp_time FROM st_users WHERE status='active' AND online_booking='1' AND access='employee' AND merchant_id=".$id." AND id IN(".implode(',',$userids).") ORDER BY emp_time ASC";
										
											$employee=$this->api_model->custome_query($select,'result');
										
											if(!empty($employee))
											{
												//$empId=0;
												$reqtime=$_POST['date']." ".$_POST['time'];
												$chePastTime= date('Y-m-d H:i:s',strtotime($reqtime));
												$k=0;
												$l=0;
												foreach($employee as $emp)
												{
													$resultCheckSlot = checkTimeSlots($timeArray,$emp->id,$id,$_POST['totalDuration']);
													//echo $emp->id; 
													if($resultCheckSlot==true)
													{
																
														$empId = $emp->id;
														$k     = 1;
																	
													}
													if($k==1) break;
														
												}
																					
												
												
												//if(!empty($empId))
												if(!empty($empId) && $empId!="any")
												{
													$book_Arr=array();
													$user_detail=$this->api_model->getWhereRowSelect('st_users',array('id'=>$_POST['uid']),'first_name,last_name,email,mobile');

													$total_price=$total_buffer=$total_min=$total_dis=$i=0;
													
													$bk_time=$_POST['date']." ".$_POST['time'];
											
													foreach($booking_detail as $row)
													{
														
														$total_buffer=$row->buffer_time+$total_buffer;
														$total_min=$row->duration+$total_min;
														
														if ($row->parent_service_id) {
															$pstime = $this->api_model->select(
																'st_offer_availability',
																'starttime,endtime,days,type',
																array(
																'service_id'=>$row->parent_service_id,
																'days' => $dayName
																)
															);
															if ($pstime) {
																$row->starttime = $pstime[0]->starttime;
																$row->endtime = $pstime[0]->endtime;
																$row->type = $pstime[0]->type;
																$row->days = $pstime[0]->days;
															}
														}

														if(!empty($row->type) && $row->type=='open')
														{ 
															if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
															{	  
																if(!empty($row->discount_price))
																{	  
																//$total_dis=($row->price-$row->discount_price)+$total_dis;
																//$total_price=$row->discount_price+$total_price;
																$total_price=$total_price+$row->discount_price;  
																$total_dis=$total_dis + $row->price-$row->discount_price;
																
																}
																else
																{
																	$total_price=$row->price+$total_price;  
																} 
															}
															else $total_price=$row->price+$total_price;  
														}
														else $total_price=$row->price+$total_price; 
													}  


													$min=$total_buffer+$total_min;
													$newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
													$book_end=date('Y-m-d H:i:s', $newtimestamp);

													$book_id = get_last_booking_id($booking_detail[0]->merchant_id);
														
													$book_Arr['user_id']         = $_POST['uid'];
													$book_Arr['merchant_id']     = $booking_detail[0]->merchant_id;
													$book_Arr['employee_id']     = $empId;
													$book_Arr['book_id']         = $book_id;
													$book_Arr['booking_time']    = $bk_time;
													$book_Arr['booking_endtime'] = $book_end;
													$book_Arr['total_minutes']   = $total_min;
													$book_Arr['total_buffer']    = $total_buffer;
													$book_Arr['total_buffer']    = $total_buffer;
													$book_Arr['total_price']     = $total_price;
													$book_Arr['total_discount']  = $total_dis;
													$book_Arr['total_time']      = $total_min+$total_buffer;
													
												
													$book_Arr['pay_status']      = 'cash';
													$book_Arr['status']          = 'confirmed';
													$book_Arr['created_by']      = $_POST['uid'];
													$book_Arr['created_on']      = date('Y-m-d H:i:s');
													$book_Arr['updated_on']      = date('Y-m-d H:i:s');
													$book_Arr['updated_by']      = $_POST['uid'];
													$book_Arr['book_by']         = $device_type;
													$book_Arr['notes']           = isset($notes)?$notes:'';
												
													$tid=$this->api_model->insert('st_booking',$book_Arr);
													$boojkstartTime=$bk_time;
													if($tid)
													{
														foreach($booking_detail as $row)
														{
															$detail_Arr = [];
															$detail_Arr['mer_id']           = $booking_detail[0]->merchant_id;
															$detail_Arr['emp_id']           = $empId;
															$detail_Arr['service_type']     = $row->stype;
															if ($row->buffer_time > 0)
																$detail_Arr['has_buffer']   = 1;	
															if($row->stype==1)
															{
								
																$detail_Arr['setuptime']        = $row->setuptime;
																$detail_Arr['processtime']      = $row->processtime;
																$detail_Arr['finishtime']       = $row->finishtime;
																$detail_Arr['setuptime_start']  = $boojkstartTime;										 
																											
																$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$row->setuptime.' minute'));
																$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$row->processtime.' minute'));
																$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
																
																$detail_Arr['setuptime_end']    = $setuEnd;	
																$detail_Arr['finishtime_start'] = $finishStart;	
																$detail_Arr['finishtime_end']   = $finishEnd;
																												
																$boojkstartTime                 = $finishEnd;
															
															}
															else
															{
																$totalMin                       = $row->duration+$row->buffer_time;
															
																$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
																$detail_Arr['setuptime_start']  = $boojkstartTime;	
																$detail_Arr['setuptime_end']    = $setuEnd;	
															
																$boojkstartTime                 = $setuEnd;
															}
															
															$detail_Arr['booking_id']=$tid;
															$detail_Arr['service_id']=$row->service_id;
															if(!empty($row->name))
																$detail_Arr['service_name']=$row->name;
															else
																$detail_Arr['service_name']=$row->category_name;
														
															if(!empty($row->type) && $row->type=='open')
															{ 
																if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
																{		  
																	if(!empty($row->discount_price))
																	{
																	//$detail_Arr['price']=$row->discount_price;
																	//$detail_Arr['discount_price']=$row->price-$row->discount_price;
																	$detail_Arr['price']=$row->discount_price;
																	$detail_Arr['discount_price']=$row->price-$row->discount_price;
																	}  
																	else $detail_Arr['price']=$row->price;
																}
																else $detail_Arr['price']=$row->price;
															}
															else $detail_Arr['price']=$row->price;
																
															if ($row->buffer_time) {
																$detail_Arr['has_buffer']=1;	
															}
															$detail_Arr['duration']=$row->duration+$row->buffer_time;
															$detail_Arr['buffer_time']=$row->buffer_time;
															$detail_Arr['user_id']=$uid;
															$detail_Arr['created_on']=date('Y-m-d H:i:s');
															$detail_Arr['created_by']=$_POST['uid'];

															$this->api_model->insert('st_booking_detail',$detail_Arr);
														

														}  
														$this->api_model->delete('st_cart',array('user_id' => $_POST['uid']));
														$this->data['main']="";
														///mail section
														$this->data['main']= $this->api_model->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$tid),'st_booking.id,business_name,st_users.first_name as merchant_name,st_users.email,employee_id,st_users.salon_email_setting,total_time,st_booking.user_id,booking_time,book_id,st_booking.merchant_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
														if(!empty($this->data['main']))
														{
															$field="st_users.id,first_name,last_name,profile_pic,app_review_status,booking_count ,service_id,service_name,duration,price,buffer_time,discount_price,(SELECT COUNT(id) FROM st_booking WHERE user_id='".$this->data['main'][0]->user_id."') as total_booking,(SELECT booking_count FROM st_users WHERE id=1) as ask_after_booking";  
															$whr=array('booking_id'=>$tid);
															$this->data['booking_detail']= $this->api_model->join_two('st_booking_detail','st_users','created_by','id',$whr,$field);
															$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

															$body_msg = str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_confirm_body"));
															$MsgTitle = $this->lang->line("booking_confirm_title");
															if($this->data['main'][0]->user_notify != 0)
															{
																sendPushNotification($uid,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $tid, 'booking_status' => 'confirmed','click_action' => 'BOOKINGDETAIL'));
															}
														
															$empDat = is_mail_enable_for_user_action($this->data['main'][0]->employee_id);
															if ($empDat) {
																$tmp = $this->data;
																$tmp['main'][0]->first_name = $empDat->first_name;
																$message2 = $this->load->view('email/service_booking_merchant',$tmp, true);
																emailsend($empDat->email,'styletimer - Neue Buchung',$message2,'styletimer');
															}
														}
															

														$message = $this->load->view('email/service_booking',$this->data, true);
														$this->data['main'][0]->first_name = $this->data['main'][0]->merchant_name;
														$message1 = $this->load->view('email/service_booking_merchant',$this->data, true);
														$mail = emailsend($user_detail->email,'styletimer - Buchung bestätigt',$message,'styletimer');
														
														if($this->data['main'][0]->salon_email_setting==1){
															$mail    = emailsend($this->data['main'][0]->email,'styletimer - Neue Buchung',$message1,'styletimer');
														}

														$this->response_data['status'] = 1;
														$this->response_data['book_id'] =$tid;
														
														$this->response_data['total_booking'] = $this->data['booking_detail'][0]->total_booking;
														$this->response_data['ask_after_booking'] = $this->data['booking_detail'][0]->ask_after_booking;
														$this->response_data['app_review_status'] = $this->data['booking_detail'][0]->app_review_status;
														$this->response_data['booking_count'] = $this->data['booking_detail'][0]->booking_count;
										
														$this->response_data['booking_status'] = 'confirmed';
														$this->response_data['response_message']='Your service has been booked successfully. We have sent a confirmation mail to your registered Email Id.';
															
													}
													else
													{
														$this->response_data['status'] = 0;
														$this->response_data['response_message']='There is some technical error.';
													}
												}
												else
												{
													$this->response_data['status'] = 0;
													$this->response_data['response_message']='Any employee not available for this time';
												}
									
											}  
											else
											{
													$this->response_data['status'] = 0;
													$this->response_data['response_message']='Any employee not available for this time';
											}
										} 
										else
										{ 
											$this->response_data['status'] = 0;
											$this->response_data['response_message']='Any employee not available for this time'; 
										}
									} 
									else
									{ 
										$this->response_data['status'] = 0;
										$this->response_data['response_message']='Any employee not available for this time';
									
									}
										
										
								}
							}	  
							else
							{

								$bookTrue=0;
								$reqtime=$_POST['date']." ".$_POST['time'];
								$chePastTime= date('Y-m-d H:i:s',strtotime($reqtime)); 
								
								$total_price = $total_buffer=$total_min=$total_dis=$i=0;
								$bk_time     = $_POST['date']." ".$_POST['time'];
								
								$timeArray        = array();                           
								$ikj              = 0;
								$strtodatyetime   = $_POST['date']." ".$_POST['time'];
									
								foreach($booking_detail as $row)
								{
									$timeArray[$ikj]        = new stdClass;
									
									$bkstartTime            = $strtodatyetime;
									$timeArray[$ikj]->start = $bkstartTime; 
										
									if($row->stype==1)
									{
										//$totaldurationTim=$totaldurationTim+$row->duration;
																		
										$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
										$timeArray[$ikj]->end   = $bkEndTime;							    	
										$ikj++;	
										
										$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
										$timeArray[$ikj]        = new stdClass;
										$timeArray[$ikj]->start = $finishStart;
										
										$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
										$timeArray[$ikj]->end   = $finishEnd;
										$ikj++;
										
										$strtodatyetime=$finishEnd;
										
										$total_min    = $row->duration+$total_min;	
																	
									}
									else
									{
										$totalMin               = $row->duration+$row->buffer_time;   
										
										$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
										$timeArray[$ikj]->end   = $bkEndTime;							    	
										$ikj++;	
									
										$strtodatyetime=$bkEndTime;		
										
										$total_buffer = $row->buffer_time+$total_buffer;
										$total_min    = $row->duration+$total_min;					   
									}
									if ($row->parent_service_id) {
										$pstime = $this->api_model->select(
											'st_offer_availability',
											'starttime,endtime,days,type',
											array(
											'service_id'=>$row->parent_service_id,
											'days' => $dayName
											)
										);
										if ($pstime) {
											$row->starttime = $pstime[0]->starttime;
											$row->endtime = $pstime[0]->endtime;
											$row->type = $pstime[0]->type;
											$row->days = $pstime[0]->days;
										}
									}
									if(!empty($row->type) && $row->type=='open')
									{ 
										if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
										{	  
											if(!empty($row->discount_price)){	  
												$total_dis     = ($row->price-$row->discount_price)+$total_dis;
												$total_price   = $row->discount_price+$total_price;  
											}
											else
											{
												$total_price = $row->price+$total_price;  
											} 
										}
										else $total_price  = $row->price+$total_price;  
									}
									else
										$total_price        = $row->price+$total_price; 
								} 
									
								$resultCheckSlot = checkTimeSlots($timeArray,$empId,$booking_detail[0]->merchant_id,$_POST['totalDuration']);
									
								/*$selectBookingvailablity="SELECT * FROM st_booking WHERE status='confirmed' AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."' AND employee_id=".$empId."";
								$empBookSlot= $this->api_model->custome_query($selectBookingvailablity,'result');
									if(!empty($empBookSlot))
									{
										foreach($empBookSlot as $eslot)
										{
											$estarttime=date('Y-m-d H:i:s',strtotime($eslot->booking_time. "- ".$_POST['totalDuration']." minutes"));
											$eendtime=date('Y-m-d H:i:s',strtotime($eslot->booking_endtime));
											if($estarttime<=$chePastTime && $eendtime>=$chePastTime)
											{ }
											else
											{
											$bookTrue++;
											}  
										}
									}
									else
									{
										$bookTrue=1;
									} */ 

								//if(empty($empBookSlot) || count($empBookSlot)==$bookTrue)
								if($resultCheckSlot==true)
								{
									$book_Arr=array();
									$user_detail=$this->api_model->getWhereRowSelect('st_users',array('id'=>$_POST['uid']),'first_name,last_name,email,mobile');

									//$total_price=$total_buffer=$total_min=$total_dis=$i=0;	
									//$bk_time=$_POST['date']." ".$_POST['time'];
							
									/*foreach($booking_detail as $row)
									{
										$total_buffer=$row->buffer_time+$total_buffer;
										$total_min=$row->duration+$total_min;
										
										if(!empty($row->type) && $row->type=='open')
										{ 
											if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
											{	  
												if(!empty($row->discount_price))
												{	  
													$total_dis=($row->price-$row->discount_price)+$total_dis;
													$total_price=$row->discount_price+$total_price;  
												}
												else
												{
													$total_price=$row->price+$total_price;  
												} 
											}	
											else $total_price=$row->price+$total_price;  
										}
										else
										{
											$total_price=$row->price+$total_price; 
										}
										
									}  */


									$min=$total_buffer+$total_min;
									$newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
									$book_end=date('Y-m-d H:i:s', $newtimestamp);

									$book_Arr['user_id']         = $_POST['uid'];
									$book_Arr['merchant_id']     = $booking_detail[0]->merchant_id;
									$book_Arr['employee_id']     = $empId;
									$book_Arr['book_id']         = get_last_booking_id($booking_detail[0]->merchant_id);
									$book_Arr['booking_time']    = $bk_time;
									$book_Arr['booking_endtime'] = $book_end;
									$book_Arr['total_minutes']   = $total_min;
									$book_Arr['total_buffer']    = $total_buffer;
									$book_Arr['total_price']     = $total_price;
									$book_Arr['total_discount']  = $total_dis;
									$book_Arr['total_time']      = $total_min+$total_buffer;
								
									$book_Arr['pay_status']      = 'cash';
									$book_Arr['status']          = 'confirmed';
									$book_Arr['created_by']      = $_POST['uid'];
									$book_Arr['created_on']      = date('Y-m-d H:i:s');
									$book_Arr['updated_on']      = date('Y-m-d H:i:s');
									$book_Arr['updated_by']      = $_POST['uid'];
									$book_Arr['book_by']         = $device_type;
									$book_Arr['notes']           = isset($notes)?$notes:'';
							
									$tid=$this->api_model->insert('st_booking',$book_Arr);
									$boojkstartTime=$bk_time;
									if($tid)
									{
										foreach($booking_detail as $row)
										{
											$detail_Arr = [];
											$detail_Arr['mer_id']           = $booking_detail[0]->merchant_id;
											$detail_Arr['emp_id']           = $empId;
											$detail_Arr['service_type']     = $row->stype;
											if ($row->buffer_time > 0)
												$detail_Arr['has_buffer']   = 1;
											if($row->stype==1)
											{
													
												$detail_Arr['setuptime']        = $row->setuptime;
												$detail_Arr['processtime']      = $row->processtime;
												$detail_Arr['finishtime']       = $row->finishtime;
												$detail_Arr['setuptime_start']  = $boojkstartTime;										 
																							
												$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$row->setuptime.' minute'));
												$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$row->processtime.' minute'));
												$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
												
												$detail_Arr['setuptime_end']    = $setuEnd;	
												$detail_Arr['finishtime_start'] = $finishStart;	
												$detail_Arr['finishtime_end']   = $finishEnd;
																								
												$boojkstartTime                 = $finishEnd;
													
											}
											else
											{
												$totalMin                       = $row->duration+$row->buffer_time;
													
												$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
												$detail_Arr['setuptime_start']  = $boojkstartTime;	
												$detail_Arr['setuptime_end']    = $setuEnd;	
													
												$boojkstartTime                 = $setuEnd;
											}
											$detail_Arr['booking_id']=$tid;
											$detail_Arr['service_id']=$row->service_id;
												
												
											if(!empty($row->name))
												$detail_Arr['service_name']=$row->name;
											else
												$detail_Arr['service_name']=$row->category_name;
												
											if(!empty($row->type) && $row->type=='open')
											{ 
												if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
												{		  
													if(!empty($row->discount_price))
													{
														$detail_Arr['price']=$row->discount_price;
														$detail_Arr['discount_price']=$row->price-$row->discount_price;
													}  
													else $detail_Arr['price']=$row->price;
												}
												else $detail_Arr['price']=$row->price;
											}
											else $detail_Arr['price']=$row->price;

											if ($row->buffer_time) {
												$detail_Arr['has_buffer']=1;	
											}
											$detail_Arr['duration']=$row->duration+$row->buffer_time;
											$detail_Arr['buffer_time']=$row->buffer_time;
											$detail_Arr['user_id']=$uid;
											$detail_Arr['created_on']=date('Y-m-d H:i:s');
											$detail_Arr['created_by']=$_POST['uid'];

											$this->api_model->insert('st_booking_detail',$detail_Arr);
												

										}  
										$this->api_model->delete('st_cart',array('user_id' => $_POST['uid']));
										$this->data['main']="";
										///mail section
										$this->data['main']= $this->api_model->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$tid),'st_booking.id,business_name,st_users.email,st_users.first_name as merchant_name,st_users.salon_email_setting,employee_id,st_booking.user_id,total_time,booking_time,st_booking.merchant_id,book_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
												
										if(!empty($this->data['main']))
										{

											$field="st_users.id,first_name,last_name,profile_pic,service_id,app_review_status,booking_count ,service_name,duration,price,buffer_time,discount_price,(SELECT COUNT(id) FROM st_booking WHERE user_id='".$this->data['main'][0]->user_id."') as total_booking,(SELECT booking_count FROM st_users WHERE id=1) as ask_after_booking";  
											
											$whr=array('booking_id'=>$tid);
											
											$this->data['booking_detail']= $this->api_model->join_two('st_booking_detail','st_users','created_by','id',$whr,$field);
											$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

											$body_msg=str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_confirm_body"));
											$MsgTitle=$this->lang->line("booking_confirm_title");
											if($this->data['main'][0]->user_notify != 0)
											{
												sendPushNotification($uid,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $tid, 'booking_status' => 'confirmed','click_action' => 'BOOKINGDETAIL'));
											}
														
											$empDat = is_mail_enable_for_user_action($this->data['main'][0]->employee_id);
											if ($empDat) {
												$tmp = $this->data;
												$tmp['main'][0]->first_name = $empDat->first_name;
												$message2 = $this->load->view('email/service_booking_merchant',$tmp, true);
												emailsend($empDat->email,'styletimer - Neue Buchung',$message2,'styletimer');
											}
										}

										$message = $this->load->view('email/service_booking',$this->data, true);
										$this->data['main'][0]->first_name = $this->data['main'][0]->merchant_name;
										$message1 = $this->load->view('email/service_booking_merchant',$this->data, true);
										$mail = emailsend($user_detail->email,'styletimer - Buchung bestätigt',$message,'styletimer');
										
										if($this->data['main'][0]->salon_email_setting==1){
											$mail    = emailsend($this->data['main'][0]->email,'styletimer - Neue Buchung',$message1,'styletimer');
										}
										
										$this->response_data['status'] = 1;
										$this->response_data['book_id'] =$tid;
										
										$this->response_data['total_booking'] = $this->data['booking_detail'][0]->total_booking;
										$this->response_data['ask_after_booking'] = $this->data['booking_detail'][0]->ask_after_booking;
										$this->response_data['app_review_status'] = $this->data['booking_detail'][0]->app_review_status;
										$this->response_data['booking_count'] = $this->data['booking_detail'][0]->booking_count;
										
										$this->response_data['booking_status'] = 'confirmed';
										$this->response_data['response_message']='Your service has been booked successfully. We have sent a confirmation mail to your registered Email Id.'; 
											
									}
									else
									{
										$this->response_data['status'] = 0;
										$this->response_data['response_message']='There is some technical error.';
									}
								}
								else
								{
									$this->response_data['status'] = 0;
									$this->response_data['response_message']='Der Mitarbeiter ist im ausgewählten Zeitraum nicht verfügbar.';
								}
							} 
								
						}
						else
						{
							$this->response_data['status'] = 0;
							$this->response_data['response_message']='There is some technical error.';
						}
					}

				}
			}
		}
		echo json_encode($this->response_data);
	}

	function booking_listing_post(){


   		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('action', 'Action', 'required|trim|in_list[confirmed,completed,cancelled]');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						$offset = $perPageData = $total_count= $status = $page_no= 0;
						$limit = 10; 
						$perPage = null; 
					
						$page_no = (isset($page) and !empty($page))?$page:1;  
						 if($page_no !='all')
						 {
							$offset = ($page_no == 1) ? 0 : ($page_no * $limit) - $limit;
							$perPage= $perPageData = $limit; 
						 }
						 else 
							$perPageData ='all';

						$count=0;
						$date= date('Y-m-d H:i:s');
						if($action == 'cancelled' || $action == 'completed'){
							//$whr=array('user_id'=> $uid , 'st_booking.status'=>'cancelled');
							//$or_whr=array('st_booking.status' => 'no show');
							$whr=" `st_booking.user_id` = '".$uid."' AND (`st_booking`.`status` = 'cancelled' OR `st_booking`.`status` = 'no show' OR `st_booking`.`status` = 'completed' OR (`st_booking`.`status` = 'confirmed' AND `st_booking`.`booking_time` < '".$date."'))";
							$or_whr='';
							// $sortby="st_booking.id";
							$sortby="st_booking.booking_time";
							$orderby="DESC";
						}
						else{
							//$whr=array('user_id'=> $uid,'st_booking.status' => $action);
							$whr="`st_booking.user_id` = '".$uid."' AND `st_booking`.`status` = 'confirmed' AND `st_booking`.`booking_time` > '".$date."'";
							$or_whr='';	
							// $sortby="st_booking.id";
							$sortby="st_booking.booking_time";
							$orderby="ASC";
						}

						$count_tot =  $this->api_model->getbookinglist($whr,0,0,$or_whr);

						
						if(!empty($count_tot))
							$count=count($count_tot);

                              $sql123213="SELECT extra_trial_month from st_users where id = ".$uid;
							$trial12=$this->db->query($sql123213);
							$trial  =  $trial12->row();
							$newMonth = $trial->extra_trial_month;
							$finalDATA =  date('Y-m-d', strtotime('+'.$newMonth.' months'));
							$triEndData =  $finalDATA;
							$cuD = date("Y-m-d");
							$currentData = "'$cuD'" .' '."'00:00:00'";
                                 $fild ="DATE_ADD(`us`.`created_on`, INTERVAL + extra_trial_month MONTH) AS endTrialcc,CASE WHEN (SELECT DATE_ADD(`us`.`created_on`, INTERVAL + extra_trial_month MONTH) AS endTrial) > $currentData THEN 'no' ELSE 'yes' END AS slonExp,st_booking.id,st_booking.book_id,us.business_name,us.mobile ,us.cancel_booking_allow,us.hr_before_cancel,reshedule_count_byuser,st_booking.employee_id,us.address,city,zip,booking_time,total_minutes,total_price,st_booking.id,st_booking.merchant_id,st_booking.status,us.end_date,us.online_booking";
						$data=$this->api_model->getbookinglistmyChange($fild,$whr,$perPage,$offset,$or_whr,$sortby,$orderby);
						
						$i=0;
						if(!empty($data)){
						foreach($data as $row){
							//$sql = "select service_id from st_booking_detail where booking_id=".$row->id;
							//$detail=$this->api_model->custome_query($sql);
							//$detail=$this->api_model->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$book_id),'st_booking.id,st_booking.book_id,st_booking.invoice_id,st_booking.booking_endtime,st_booking.merchant_id,reshedule_count_byuser,business_name,cancel_booking_allow,st_users.mobile,hr_before_cancel,address,latitude,longitude,mobile,reason,city,zip,st_booking.booking_time,st_booking.created_on,st_booking.status,end_date,st_booking.employee_id,(select first_name from st_users where id=st_booking.employee_id) as emp_first_name,(select last_name from st_users where id=st_booking.employee_id) as emp_last_name,st_booking.reason,email_text');
							$s_name=get_single_servicename($row->id);

							$this->response_data['data'][$i]['slonExp']=$row->slonExp;
							$this->response_data['data'][$i]['id']=$row->id;
							$this->response_data['data'][$i]['business_name']=$row->business_name;
							$this->response_data['data'][$i]['mobile']=$row->mobile;
							$this->response_data['data'][$i]['address']=$row->address;
							$this->response_data['data'][$i]['city']=$row->city;
							$this->response_data['data'][$i]['zip']=$row->zip;
							$this->response_data['data'][$i]['booking_time']=$row->booking_time;
							$this->response_data['data'][$i]['total_minutes']=$row->total_minutes;
							$this->response_data['data'][$i]['total_price']=$row->total_price;
							$this->response_data['data'][$i]['merchant_id']=$row->merchant_id;
							$this->response_data['data'][$i]['employee_id']=$row->employee_id;
							$this->response_data['data'][$i]['reshedule_status']=$row->reshedule_count_byuser;
							$this->response_data['data'][$i]['end_date'] = $row->end_date;
							$this->response_data['data'][$i]['status']=$row->status;
							$this->response_data['data'][$i]['cancel_booking_allow']=$row->cancel_booking_allow;
							$this->response_data['data'][$i]['hr_before_cancel']=$row->hr_before_cancel;
							$this->response_data['data'][$i]['book_id']=$row->book_id;
							$this->response_data['data'][$i]['online_booking']=$row->online_booking;
							$price_start_option='';
							if(!empty($s_name)){
								$price_start_option=$s_name->price_start_option;
							}
							$sql123213="SELECT extra_trial_month from st_users where id = ".$uid;
							$trial12=$this->db->query($sql123213);
							$trial  =  $trial12->row();
							$newMonth = $trial->extra_trial_month;
							$finalDATA =  date('Y-m-d', strtotime('+'.$newMonth.' months'));
							$triEndData =  $finalDATA;
							$cuD = date("Y-m-d");
							$currentData = "'$cuD'" .' '."'00:00:00'";
							
							
							
							$field="st_users.id,service_name,duration,price,buffer_time,discount_price,service_id,(select price_start_option from st_merchant_category WHERE id=st_booking_detail.service_id) as price_start_option";  
							$whr=array('booking_id'=>$row->id);
							$booking_detail = $this->api_model->join_twomyChange('st_booking_detail','st_users','created_by','id',$whr,$field);
							//print_r($booking_detail); die;
					        $j = 0;	
							$subcat_arry = array();
							//$subservice_arry = array();
							foreach($booking_detail as $row){
                            $sub_name=get_subservicename($row->service_id); 
                        	if($sub_name == $row->service_name){
                            	$service_name= "";
                            	$cat_name= $sub_name;
                        	}
                          	else{
                            	$service_name= $row->service_name;
                            	$cat_name= $sub_name;
                          	}
								
								$subcat_arry[$j]['category_name']= $cat_name;
								$subcat_arry[$j]['service_name']= $service_name;
		
								$j++;
				    		}
				    		$this->response_data['data'][$i]['all_services']=$subcat_arry;
							//$this->response_data['data'][$i]['service_name']=$subservice_arry;
				    		//$this->response_data['data']['all_services']=$sub_arry;
							
							
							/*$sub_name=get_subservicename($row->service_id); 
							print_r($sub_name); die;
                        	if($sub_name == $row->service_name){
                            	//$service_name= $row->service_name;
                            	$service_name= "";
                            	$cat_name= $sub_name;
                            	//$cat_name= $row->service_name;
                        	}
                          	else{
                            	//$service_name= $sub_name.' - '.$row->service_name;
                            	$service_name= $row->service_name;
                            	$cat_name= $sub_name;
                          	}*/
                          	
							/*if(!empty($s_name)){
								//print_r($s_name); die;
								$price_start_option=$s_name->price_start_option;
								if($s_name->cat_name ==$s_name->name){
									//$name=$s_name->cat_name;
									//$name=$s_name->name;
									$name="";
									$cat_name=$s_name->cat_name;
								}
								else{
									$cat_name = $s_name->cat_name;
									$name = $s_name->name;
									//$name=$s_name->cat_name.'-'.$s_name->name;
								}
							}
							else{
								$name='';
								$cat_name='';
							} */
							//$this->response_data['data'][$i]['category_name']=$cat_name;
							//$this->response_data['data'][$i]['service_name']=$name;
							$this->response_data['data'][$i]['price_start_option']=$price_start_option;
							
							$review=$this->api_model->getWhereRowSelect('st_review',array('booking_id'=>$this->response_data['data'][$i]['id'],'rate,review'));
							//echo $row->id;
							//print_r($review); die;
							
							$this->response_data['data'][$i]['is_review']=(!empty($review))?'1':'0';


							$i++;
							}
							$this->response_data['status'] = 1;
							//$this->response_data['data'] = $data;
							$this->response_data['response_message'] = 'success';
							$this->response_data['total_count']=$count;
						  	$this->response_data['per_page']=$perPage;
						  	$this->response_data['current_page']=$page_no;
						}
						else
							$this->response_data['response_message'] = 'No record found..';
					}
				}
			}
		}
		echo json_encode($this->response_data);
	}

	function booking_cancel_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('book_id', 'Booking Id', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
							$id = $_POST['book_id'];
							$check=$this->api_model->getWhereRowSelect('st_booking',array('id'=>$id,'status'=>'confirmed'),'booking_time,merchant_id');
							if(!empty($check))
							{
								if(strtotime($check->booking_time) < strtotime(date('Y-m-d H:i:s'))){
									 $this->response_data['response_message']='Booking time out unable to cancel';
									}
									else{
								
								$acc='user';
								$reso=isset($_POST['reason'])?$_POST['reason']:'';
								if($this->api_model->update('st_booking',array('id'=>$id),array('status' => 'cancelled','reason' => $reso,'updated_by' => $uid,'updated_on' => date('Y-m-d H:i:s')))){
									$field='st_booking.id,user_id,booking_time,total_time,st_booking.merchant_id,book_id,first_name,last_name,st_users.email,(select business_name from st_users where st_users.id = st_booking.merchant_id) as salon_name,(select email from st_users where st_users.id=st_booking.merchant_id) as m_email,employee_id,(select first_name from st_users where st_users.id = st_booking.merchant_id) as merchant_name,st_users.notification_status';
									$info= $this->api_model->join_two('st_booking','st_users','user_id','id',array('st_booking.id'=>$id),$field);
									$body_msg=str_replace('*salonname*',$info[0]->salon_name,$this->lang->line("booking_cancelled_body"));
									$MsgTitle=$this->lang->line("booking_cancelled_title");
									

									if(!empty($info)){

										if($info[0]->notification_status != 0 ){
										sendPushNotification($uid,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$info[0]->merchant_id ,'book_id'=> $id,'booking_status' =>'cancelled','click_action' => 'BOOKINGDETAIL'));
										}

										$insertArrs=array("booking_id" => $book_id ,"status" => "cancel","merchant_id" => $check->merchant_id,"created_by" => $uid,"created_on" => date('Y-m-d H:i:s'));
										$this->api_model->insert('st_booking_notification',$insertArrs);

										$time = new DateTime($info[0]->booking_time);
							                 		$date = $time->format('d.m.Y');
							                 		$time = $time->format('H:i');
										$message = $this->load->view('email/booking_cancel',array("fname"=>ucwords($info[0]->first_name),"lname"=>ucwords($info[0]->last_name),'book_id'=>$info[0]->book_id,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"duration"=>$info[0]->total_time,"booking_time"=>$time,"booking_id" => $id), true);
												$m_name=$this->session->userdata('sty_fname');	
										$message2 = $this->load->view('email/booking_cancel_salon',array("fname"=>ucwords($info[0]->first_name),"lname"=>ucwords($info[0]->last_name),"merchant_name" => $info[0]->merchant_name,'book_id'=>$info[0]->book_id,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,'access' => $acc,'emp_name' =>$m_name,"booking_id" => $id, 'duration'=>$info[0]->total_time), true);
										$mail = emailsend($info[0]->email,$this->lang->line("styletimer_booking_cancel"),$message,'styletimer');
										$mail = emailsend($info[0]->m_email,$this->lang->line("styletimer_booking_cancel"),$message2,'styletimer');

										$empDat = is_mail_enable_for_user_action($info[0]->employee_id);
										if ($empDat) {
											$message2 = $this->load->view('email/booking_cancel_salon',array("fname"=>$info[0]->first_name,"lname"=> $info[0]->last_name,"merchant_name" => $empDat->first_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,'access' => $acc,'emp_name' =>$m_name,"booking_id" => $id,'book_id'=>$info[0]->book_id, 'duration'=>$info[0]->total_time), true);	
											emailsend($empDat->email,$this->lang->line('styletimer_booking_cancel'),$message2,'styletimer');
										}
									}
									 $this->response_data['status']=1;
									 $this->response_data['response_message']='Your booking has been cancelled successfully';
									
								}
								else
									$this->response_data['response_message']='Sorry Unable to process...!';
								}
							}
							else
								$this->response_data['response_message']='This booking already completed or cancelled !';
							

					}
				}
			}
		}
		echo json_encode($this->response_data);
	}


	function booking_detail_post()
	{

		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('book_id', 'Booking Id', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						$detail=$this->api_model->join_two('st_booking','st_users',
						'merchant_id','id',
						array('st_booking.id' =>$book_id),
						'st_booking.id,st_booking.book_id,st_booking.invoice_id,
						st_booking.booking_endtime,st_booking.merchant_id,
						reshedule_count_byuser,business_name,
						cancel_booking_allow,st_users.mobile,
						hr_before_cancel,address,latitude,
						longitude,mobile,reason,city,zip,
						st_booking.booking_time,st_booking.created_on,
						total_price,
						st_booking.status,end_date,st_booking.employee_id,
						(select first_name from st_users where id=
						st_booking.employee_id) as emp_first_name,
						(select last_name from st_users where 
						id=st_booking.employee_id) as emp_last_name,
						st_booking.reason,email_text,st_users.online_booking');
						//echo $this->db->last_query();
						if(!empty($detail)){
						$detail[0]->share_url='';
						$detail[0]->share_url=base_url('user/service_provider/'.url_encode($detail[0]->merchant_id));
						//print_r($detail[0]->share_url=base_url('user/service_provider/'.url_encode($detail[0]->merchant_id)));die;
						$images=$this->api_model->getWhereRowSelect('st_banner_images',array('user_id'=>$detail[0]->merchant_id),'user_id,image,image1,image2,image3,image4');
						
						$this->response_data['images']=$images;
						
						$field="st_users.id,service_name,duration,price,
						buffer_time,discount_price,service_id,
						(select price_start_option from st_merchant_category 
						WHERE id=st_booking_detail.service_id) as price_start_option,
						(select SUM(discount_price) from st_booking_detail WHERE 
						booking_id=$book_id) as totalDiscount";  
						$whr=array('booking_id'=>$book_id);
				    	$booking_detail = $this->api_model->join_two('st_booking_detail',
					    'st_users','created_by','id',$whr,$field);
					//echo $this->db->last_query();die;
				    	$dis=$prc=0;
				    	$i=0;
				    	$this->response_data['status']= 1;
				    	$this->response_data['data']['detail']= $detail[0];
				    	
				    	//  $sql2="SELECT `r`.`id`,`duration`, `price`, `buffer_time`, `discount_price`,`subcategory_id`, 
					// 	`u1`.`category_name` as `m_category`, `u2`.`category_name` as `s_category`,
					// 	(SELECT SUM(discount_price)  FROM `st_merchant_category` `r` WHERE `r`.`status` = 'active' AND `r`.`created_by`=".$detail[0]->merchant_id." )  AS totalDiscounta
					// 	FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON 
					// 	`r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` 
					// 	WHERE `r`.`status` = 'active' AND `r`.`created_by`=".$detail[0]->merchant_id." 
					// 	GROUP BY `r`.`subcategory_id` LIMIT 4";
						 $sql2="SELECT `r`.`id`,`duration`, `price`, `buffer_time`, `discount_price`,`subcategory_id`, 
						`u1`.`category_name` as `m_category`, `u2`.`category_name` as `s_category`
						FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON 
						`r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` 
						WHERE `r`.`status` = 'active' AND `r`.`created_by`=".$detail[0]->merchant_id." 
						GROUP BY `r`.`subcategory_id` LIMIT 4";
				        $query2=$this->db->query($sql2);
					   // echo $this->db->last_query();die;
					   $serviceData = $query2->result();
					   //print_r($serviceData);die;
					    $totalDiscounta =0;
					   	foreach($serviceData as $row1){
                                   $totalDiscounta = $totalDiscounta + $row1->discount_price;
						   }
						  
				        $this->response_data['data']['sercvices']=$serviceData;
				    	
				    	$sub_arry=array();
				     $newDis=0;
				    	foreach($booking_detail as $row){
				    		$dis=$dis+$row->discount_price ;
							$newDis = $newDis + $row->discount_price;
                            $prc=$prc+$row->price+$row->discount_price;

                            $sub_name=get_subservicename($row->service_id); 
                        	if($sub_name == $row->service_name){
                            	
                            	$service_name= "";
                            	$cat_name= $sub_name;
                            	//$cat_name= $row->service_name;
                        	}
                          	else{
                            	//$service_name= $sub_name.' - '.$row->service_name;
                            	$service_name= $row->service_name;
                            	$cat_name= $sub_name;
                          	}

                            $sub_arry[$i]['id']= $row->id;
                            $sub_arry[$i]['category_name']= $cat_name;
                            $sub_arry[$i]['service_name']= $service_name;
                            $sub_arry[$i]['duration']= (intval($row->duration) - intval($row->buffer_time)).'';
                            $sub_arry[$i]['price']= $row->price;
                            $sub_arry[$i]['buffer_time']= $row->buffer_time;
                            $sub_arry[$i]['discount_price']= $row->discount_price;
                            $sub_arry[$i]['price_start_option']= $row->price_start_option;
				    		/*$this->response_data['data'][$i]['all_services']['id']= $row->id;
				    		$this->response_data['data'][$i]['all_services']['service_name']= $service_name;
				    		$this->response_data['data'][$i]['all_services']['duration']= $row->duration;
				    		$this->response_data['data'][$i]['all_services']['price']= $row->price;
				    		$this->response_data['data'][$i]['all_services']['buffer_time']= $row->buffer_time;
				    		$this->response_data['data'][$i]['all_services']['discount_price']= $row->discount_price;*/

				    		 $i++;
				    		}
				    		$this->response_data['data']['all_services']=$sub_arry;
				    		
				    	$review=$this->api_model->getWhereRowSelect('st_review',array('booking_id'=>$book_id),'id,anonymous,rate,review,user_id,(select first_name from st_users where id= st_review.user_id) as first_name,(select last_name from st_users where id= st_review.user_id) as last_name');
							
								$this->response_data['data']['detail']->tot_discount= $prc - $detail[0]->total_price;
				    			$this->response_data['data']['detail']->tot_price= $prc;
				    			$this->response_data['data']['detail']->is_review=(!empty($review))?'1':'0';
				    			$this->response_data['data']['detail']->review_id=isset($review->id)?$review->id:'';
				    			$this->response_data['data']['detail']->anonymous=isset($review->anonymous)?$review->anonymous:'';

				    			$this->response_data['data']['detail']->first_name=isset($review->first_name)?$review->first_name:'';
				    			$this->response_data['data']['detail']->last_name=isset($review->last_name)?$review->last_name:'';

				    			$this->response_data['data']['detail']->rating=isset($review->rate)?$review->rate:'';
				    			$this->response_data['data']['detail']->review=isset($review->review)?$review->review:'';
				    				

							}
							else{
								$this->response_data['response_message']='No detail found..';
							}

								
				}
			}
		}

	 }	
		echo json_encode($this->response_data); 
	}

	function addreview_service_post(){

		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('book_id', 'Booking Id', 'required|trim');
		$this->form_validation->set_rules('salon_id', 'Salon Id', 'required|trim');
		$this->form_validation->set_rules('rating', 'Rating', 'required|trim');
		//$this->form_validation->set_rules('review', 'Review', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('employeeid', 'Employee id', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						$insertArr=array();
							$insertArr['rate']=$_POST['rating'];
							$insertArr['booking_id']=$_POST['book_id'];
							$insertArr['merchant_id']=$_POST['salon_id'];
							$insertArr['review']=isset($_POST['review'])?$_POST['review']:'';
							$insertArr['user_id']=$_POST['uid'];
							$insertArr['created_by']=$_POST['uid'];
							$insertArr['created_on']=date('Y-m-d H:i:s');
							$insertArr['anonymous']=isset($_POST['anonymous'])?$_POST['anonymous']:0;
							$insertArr['emp_id']=$_POST['employeeid'];
							$insertArr['read_status']='unread';
							if($this->api_model->insert('st_review',$insertArr)){

						$this->api_model->update('st_booking',array('id'=>$_POST['book_id']),array('updated_on' => date('Y-m-d H:i:s')));

								$this->response_data['status']=1;
								$this->response_data['response_message']='Success';
							}
							else{
								$this->response_data['status']=0;
								$this->response_data['response_message']='Something is wrong.';
							}


					}
				}
			}
		}
		echo json_encode($this->response_data); 
	}
	
   function recently_view_post()
   {
   	$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
	$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
	$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
	
	if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				$field="st_users.id,business_name,address,city,zip,country,(select ROUND(AVG(rate),2) from st_review where merchant_id=st_users.id AND user_id !=0) as rating,(SELECT COUNT(id) FROM st_review WHERE merchant_id=st_users.id AND user_id !=0) as total_review";
				$whr=array('device_id' => $_POST['device_id']);
	   			$details= $this->api_model->join_two('st_recently_search','st_users','salon_id','id',$whr,$field);
	   			if(!empty($details)){
	   				$i=0;
	   				$this->response_data['status']=1;
					$this->response_data['response_message']='Success';
					$this->response_data['data']=$details;
					foreach($details as $row){
						$this->response_data['data'][$i]->rating=($row->rating !='')?$row->rating:'0';
						$i++;
					}
	   			}
	   			else
	   				$this->response_data['response_message']='No record found..';

			}
		}
	  echo json_encode($this->response_data); 
	}


	function socialLogin_post(){
	   
	    basicFromValidation();
		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');

		/*$this->form_validation->set_rules('phone', 'phone', 'trim');*/
		if(!empty($this->input->post('type')) && $this->input->post('type')!='apple'){			
		
		$this->form_validation->set_rules('name', 'name', 'required|trim');
		$this->form_validation->set_rules('email', 'email', 'trim');
	       
	       }
		$this->form_validation->set_rules('unique_id', 'unique id', 'required|trim');
		$this->form_validation->set_rules('type', 'type', 'required|trim');
		
		if($this->form_validation->run() == FALSE)
		{
			  validationErrorMsg();
		}
		else
		{
			 if(checkApikey($this->input->post('api_key')))
			 {
				extract($_POST); 
				$uid=null;
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['status']=INVALID_TOKEN;
					$this->response_data['response_message']=INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					 //$phone = (isset($phone))?$phone:'';
					 $email = (!empty($email))?$_POST['email']:'';
					 $type = (!empty($type))?$type:'';
					 
					 $user = $this->api_model->socialLogin($unique_id,$email,$type);
					
					 if($user)
					 {    
						 $user = $user[0];
						 
						 
						 
						 if($user->status == 'active')
						 {
							// update access token and uid for this device
							$this->api_model->update_uid_AccessToken($user->id, $device_id, $device_type, $access_token);
							$this->response_data['status']=1;
							$this->response_data['response_message']='Success'; 
							$this->response_data['data']=$user;

						 }	    
						 else
							 $this->response_data['response_message'] = "your account is ".$user->status;
					 }
					 else
					 {

					 	  $arr = explode(' ',$name);

						  $reg_data = array(); 
						  $reg_data['status'] = 'active'; 
						  $reg_data['access'] = 'user'; 
						  $reg_data['email'] = $email; 
						 // $reg_data['mobile'] = $phone; 
						  $reg_data['created_on'] = date('Y-m-d H:i:s'); 
						  $reg_data['ip_address'] = $_SERVER['REMOTE_ADDR']; 
						  $reg_data['first_name'] = isset($arr[0])?$arr[0]:'';
						  $reg_data['last_name'] = isset($arr[1])?$arr[1]:'';
						  $reg_data['reg_from'] = $device_type;
						  $reg_data['unique_id'] = $unique_id;
						  $reg_data['socialtype'] = $type;
						
						  $insert_id = $this->api_model->insert('st_users',$reg_data);
						  if($insert_id)
						  { 

						  	if(!empty($social_imageurl)){
			                    //echo $fbUser['picture']['data']['url'];
			                    $url = $social_imageurl;
								$img = 'assets/uploads/users/'.$insert_id.'/';
								if(is_dir($img)) {
									  //echo ("$file is a directory");
									} else {
									 mkdir($img, 0777);
									}
								//mkdir($img, 0777);
								$imgname ='fb'.time().'.JPG';
								$imgs = $img.$imgname;
								$imgs1 = $img.'thumb_'.$imgname;
								$imgs2 = $img.'icon_'.$imgname;
								//file_put_contents($imgs, file_get_contents($url));
								$ch = curl_init($url);					
								$fp = fopen($imgs, 'wb');					
								curl_setopt($ch, CURLOPT_FILE, $fp);				
								curl_setopt($ch, CURLOPT_HEADER, 0);
								curl_exec($ch);
								curl_close($ch);					
								fclose($fp);
								
								$ch1 = curl_init($url);					
								$fp1 = fopen($imgs1, 'wb');
								curl_setopt($ch1, CURLOPT_FILE, $fp1);
								curl_setopt($ch1, CURLOPT_HEADER, 0);
								curl_exec($ch1);
								curl_close($ch1);					
								fclose($fp1);
								$ch2 = curl_init($url);					
								$fp2 = fopen($imgs2, 'wb');
								curl_setopt($ch2, CURLOPT_FILE, $fp2);
								curl_setopt($ch2, CURLOPT_HEADER, 0);
								curl_exec($ch2);
								curl_close($ch2);					
								fclose($fp2);
								
							$this->db->where('id',$insert_id);
							$this->db->update('st_users',array('profile_pic'=>$imgname));		
								
						      }

						  	$user = $this->api_model->socialLogin($unique_id,$email,$type);
							$user = $user[0];

						  	$this->response_data['status']=1;
							$this->response_data['response_message']='Success';
							$this->response_data['data']=$user;

							$this->api_model->update_uid_AccessToken($user->id, $device_id, $device_type, $access_token); 
						  }
						  else 
							 $this->response_data['response_message'] ='Something is wrong please try again.';
					 }
			    } 	
			     		
	         }
	         else
	         {
	         	 $this->response_data['response_message']=INVALID_API_KEY;
			 }// api key check 
        }
  		echo json_encode($this->response_data); 
   }

 	function rebook_service_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('book_id', 'Booking Id', 'required|trim');
		$this->form_validation->set_rules('salon_id', 'Salon Id', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'0';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{

						if($this->api_model->get_datacount('st_client_block',array('client_id'=>$uid,'merchant_id'=>$salon_id)) > 0){
							$this->response_data['status']=0;
							$this->response_data['response_message']='Du bist aktuell nicht berechtigt, Buchungen in diesem Salon zu erstellen.';
						}
						else{
						$insertArr=array();
						$usid=$_POST['uid'];
						$id=$_POST['book_id'];
						$sal_id= $_POST['salon_id'];
						
						if($usid !=0){
			 			$this->api_model->delete('st_cart',array('merchant_id !='=>$sal_id,'user_id'=>$usid));
							}
                        
                         $merchantDetail = $this->api_model->getWhereRowSelect('st_users',array('id'=>$sal_id),'id,status');
		
						 if($merchantDetail->status !='active'){
							   $this->response_data['status'] = 0;
						       $this->response_data['response_message'] = 'Der Salon ist leider nicht mehr bei styletimer verfügbar';
							 
							 }	
                       else{
						$service = $this->api_model->getWhereAllselect('st_booking_detail',array('booking_id' =>$id,'user_id'=> $usid),'service_id'); //created_by
						if(!empty($service)){
							
							$fieldz="st_users.id,first_name,last_name,business_name,business_type,address,email,mobile,address,zip,latitude,longitude,country,city,about_salon,user_id,image,image1,image2,image3,image4,(select ROUND(AVG(rate),2) from st_review where merchant_id=st_users.id AND user_id !=0) as rating,(SELECT COUNT(id) FROM st_review WHERE merchant_id=st_users.id AND user_id !=0) as total_review";
							$whr=array('st_users.id'=>$sal_id);

							$details= $this->api_model->join_two('st_users','st_banner_images','id','user_id',$whr,$fieldz);
	   						$this->response_data['data']['details']= $details;
	   						$i=0;
	   						if(!empty($details)){
	   						  foreach($details as $row){
									$this->response_data['data']['details'][$i]->user_id=($row->user_id !='')?$row->user_id:$row->id;
							    	$this->response_data['data']['details'][$i]->image=($row->image !='')?$row->image:'';
									$this->response_data['data']['details'][$i]->image1=($row->image1 !='')?$row->image1:'';
									$this->response_data['data']['details'][$i]->image2=($row->image2 !='')?$row->image2:'';
									$this->response_data['data']['details'][$i]->image3=($row->image3 !='')?$row->image3:'';
									$this->response_data['data']['details'][$i]->image4=($row->image4 !='')?$row->image4:'';
							    	$this->response_data['data']['details'][$i]->rating= ($row->rating !='')?number_format($row->rating,1):'0.0';
							    	$i++;
							    }
							  }
							foreach($service as $serv){
								$service_det=$this->api_model->getWhereRowSelect('st_merchant_category',array('id'=>$serv->service_id),'id,price,created_by');
								if(!empty($service_det)){
									if($this->api_model->get_datacount('st_cart',array('user_id' => $usid,'service_id' => $service_det->id)) == 0){
										 $insertArr['service_id']=$service_det->id;
								      	 $insertArr['user_id']=$usid;
								      	 $insertArr['total_price']=$service_det->price;
								      	 $insertArr['merchant_id']=$service_det->created_by;
								       	 $insertArr['created_by']= $usid;
								      	 $insertArr['created_on']= date('Y-m-d H:i:s');
										 if($this->api_model->insert('st_cart',$insertArr)){
										 	$this->response_data['status']=1;
										 	$this->response_data['response_message']='Service added to cart';
										 }
									 	else{
											$this->response_data['status']=0;
										 	$this->response_data['response_message']='Unable to add service';
									 		}
									  
									  }
									   else{
									   	$this->response_data['status']=1;
										$this->response_data['response_message']='Service added to cart';
									   }
									   			
									}
								}
							}
							else {
								$this->response_data['status']=0;
								$this->response_data['response_message']='Unable to add service';
							}
                          }
					  }
					}
				}
			}
		}
		echo json_encode($this->response_data); 
			
	}
	function clear_cart_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('salon_id', 'Salon Id', 'required|trim');
		$this->api_model->delete('st_cart',array('user_id' => $_POST['uid'], 'merchant_id' => $_POST['salon_id']));
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						$this->api_model->delete('st_cart',array('user_id' => $_POST['uid'], 'merchant_id' => $_POST['salon_id']));
						
						
						$this->response_data['status'] = 1;
						$this->response_data['response_message'] = 'success';
						
					}
				}
			}
		}
		echo json_encode($this->response_data); 
	}

 	function total_service_count_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('salon_id', 'Salon Id', 'required|trim');
		
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						$cot=$this->api_model->getWhereRowSelect('st_cart',array('user_id' => $_POST['uid'], 'merchant_id' => $_POST['salon_id']),'count(id) as total_service,(SELECT count(id) FROM st_client_block WHERE merchant_id="'.$salon_id.'" AND client_id="'.$uid.'") as user_block');
						
						$this->response_data['status'] = 1;
						$this->response_data['response_message'] = 'success';
						$this->response_data['data']=$cot;
					}
				}
			}
		}
		echo json_encode($this->response_data); 
	}

	function salon_mapdetail_post(){
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('salon_id', 'Salon Id', 'required|trim');
		

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
				$i=0;
				$field="st_users.id,business_name,business_type,address,address,zip,latitude,longitude,country,city,user_id,image,image1,image2,image3,image4,(select ROUND(AVG(rate),2) from st_review where merchant_id=st_users.id AND user_id !=0) as rating,(SELECT COUNT(id) FROM st_review WHERE merchant_id=st_users.id AND user_id !=0) as total_review";
				$whr=array('st_users.id'=>$_POST['salon_id']);
				$details= $this->api_model->join_two('st_users','st_banner_images','id','user_id',$whr,$field);
				 
				 //$this->response_data['data']=$details;
				 if(!empty($details)){

				 	$this->response_data['status']=1;
				 	$this->response_data['response_message']='success';
						 foreach($details as $usr){

					$this->response_data['data']['id']=$usr->id;
					$this->response_data['data']['business_name']=$usr->business_name;
					$this->response_data['data']['business_type']=$usr->business_type;
					$this->response_data['data']['address']=$usr->address;
					$this->response_data['data']['zip']=$usr->zip;
					$this->response_data['data']['latitude']=$usr->latitude;
					$this->response_data['data']['longitude']=$usr->longitude;
					$this->response_data['data']['country']=$usr->country;
					$this->response_data['data']['city']=$usr->city;
					$this->response_data['data']['image']=($usr->image !='')?$usr->image:'';
					$this->response_data['data']['image1']=($usr->image1 !='')?$usr->image1:'';
					$this->response_data['data']['image2']=($usr->image2 !='')?$usr->image2:'';
					$this->response_data['data']['image3']=($usr->image3 !='')?$usr->image3:'';
					$this->response_data['data']['image4']=($usr->image4 !='')?$usr->image4:'';
					$this->response_data['data']['rating']=($usr->rating !='')?$usr->rating:'0';
					$this->response_data['data']['total_review']=$usr->total_review;
					
					}
					 $where="";
			        if(isset($_POST['subcatgory']) && !empty($_POST['subcatgory']))
			         {   
						$mdat = explode('-', $_POST['subcatgory']);
						//$subcat=implode(',',$_POST['sucatgory']);
					   //$where=$where." AND subcategory_id IN(".$subcat.")";
					   $where=$where." AND category_id =".$mdat[0];
					   $where=$where." AND filtercat_id =".$mdat[1];
					 }
					 if(isset($_POST['main_category']) && !empty($_POST['main_category'])){
						$where=$where." AND category_id =".$_POST['main_category'];
						}

					 $sql2="SELECT `r`.`id`,`duration`, `price`, `buffer_time`, `discount_price`,`subcategory_id`, `u1`.`category_name` as `m_category`, `u2`.`category_name` as `s_category` FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` WHERE `r`.`status` = 'active' AND `r`.`created_by`=".$_POST['salon_id']." ".$where." GROUP BY `r`.`subcategory_id` LIMIT 2";
					 $query2=$this->db->query($sql2);
					 $this->response_data['data']['sercvices']=$query2->result();
				  }
				  else{
				  	$this->response_data['status']=0;
				 	$this->response_data['response_message']='No record found...';
				  }


			}
		}

		echo json_encode($this->response_data); 
	}


  function salon_profile_page_post()
   {

   	$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
   	$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
	$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
	$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
	
	if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
   			$id=$_POST['salon_id'];
   		
			
			$this->data['service_id']=$id;
			
			$uid=(!empty($_POST['uid']))?$_POST['uid']:0;
			

			if($uid !=0){
			 $this->api_model->delete('st_cart',array('merchant_id !='=>$id,'user_id'=>$uid));
			}
		
			//$ser_details & $srv_detail  detail variable

			$field="st_users.id,first_name,last_name,business_name,business_type,address,email,mobile,address,zip,latitude,longitude,online_booking,country,city,(SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='".$uid."') as favourite,(SELECT count(id) FROM st_client_block WHERE merchant_id=`st_users`.`id` AND client_id='".$uid."') as user_block,about_salon,user_id,image,image1,image2,image3,image4,(select ROUND(AVG(rate),2) from st_review where merchant_id=st_users.id AND user_id !=0) as rating,(SELECT COUNT(id) FROM st_review WHERE merchant_id=st_users.id AND user_id !=0) as total_review";
			$whr=array('st_users.id'=>$id);
			$this->data['sid']=$id;
			//$_GET['servicids']="'3','4','5','6'";

            $start_prc="";

			$services_by_subcategory = [];
			if(!empty($_POST['servicids']))
			{
				 //$sids= url_decode($_GET['servicids']);
				 //$esid=array_unique(explode(',',$sids));
				 
				 

                $sql="SELECT st_merchant_category.*,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`st_merchant_category`.`id`),'0') as checkemp,(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id=".$uid." AND st_cart.service_id=st_merchant_category.id) as in_cart,st_category.category_name,st_category.id as subcatid FROM st_merchant_category LEFT JOIN st_category ON st_category.id=st_merchant_category.subcategory_id WHERE st_merchant_category.status='active' AND st_merchant_category.created_by=".$id." AND st_merchant_category.online=1 AND st_merchant_category.subcategory_id IN(".$_POST['servicids'].") ORDER BY st_merchant_category.subcategory_id,st_merchant_category.id";

                //AND st_merchant_category.subcategory_id IN(".implode(',',$esid).")"

                $matchcatsubcat=$this->api_model->custome_query($sql,'result');
				$startFrom="";
				

				if($matchcatsubcat){
					$ky=$j=0;$serId=0;
					$sub_prc1=0;
					$allid=array();
					$duration=array();
                 foreach($matchcatsubcat as $service)
				 {
					if($service->checkemp>0){
						
                  if(!empty($service->discount_price)) 
                  	$price=$service->discount_price; 
                  else 
                  	$price= $service->price;

                  	//echo $price;
                  if(empty($startFrom)) $startFrom=$price;

                  if($startFrom>$price) $startFrom=$price;

                 // echo $startFrom;
                  if($service->name !=''){
                  			$duration[]=$service->duration;	
                  			$allid[] = $service->id;
                  	  if($service->subcategory_id != $serId){
                      	$allid=array();
                      	 $sqlDropcount1="SELECT count(st_cart.id) as ctCount FROM st_cart LEFT JOIN st_merchant_category ON st_cart.service_id=st_merchant_category.id WHERE subcategory_id = '".$service->subcategory_id."' AND st_cart.user_id='".$uid."' AND name !=''";
			 	
			 							$dataCt1=$this->db->query($sqlDropcount1);
			 							$chkdrop1=$dataCt1->row();
			 							if($chkdrop1->ctCount > 0)
			 								$ckm=1;
			 							else
			 								$ckm=0;

			 			$cherv = check_review($service->id);
			 			$services_by_subcategory[$j]['id'] = $service->id;
						$services_by_subcategory[$j]['key'] = $service->category_name;
						$services_by_subcategory[$j]['main_categoryid'] = $service->category_id;
						$services_by_subcategory[$j]['subid'] = $service->subcategory_id;
						$services_by_subcategory[$j]['status'] =0;
						
						$services_by_subcategory[$j]['review_count'] = $cherv;
						$services_by_subcategory[$j]['price_start_option'] = $service->price_start_option;
						$services_by_subcategory[$j]['service_detail'] =$service->service_detail;
						$services_by_subcategory[$j]['service_detail_id'] =$service->id;
						$services_by_subcategory[$j]['start_price']=$startFrom;
						$services_by_subcategory[$j]['service_duration'] = $service->duration.' Min';
						$services_by_subcategory[$j]['is_open']=$ckm;
						$services_by_subcategory[$j]['value'][] = $service;
						//$services_by_subcategory[$service->category_name ][] = $service;
						$j++;
						$sub_prc1=$startFrom;
						$allid[] = $service->id;
						//$ser_details=$service->service_detail;
						
						
                      }

                      else{
                      		if($sub_prc1 > $startFrom)
			                      $sub_prc1=$startFrom;
							$a=$j-1;
							 $min = min($duration);
                             $max = max($duration);
                             $cherv = check_review(implode(",",$allid));
                            // echo $this->db->last_query();
                             if($min == $max)
							  	$services_by_subcategory[$a]['service_duration'] = $min.' Min';
							  else
							  	 $services_by_subcategory[$a]['service_duration'] = $min.' Min-'.$max.' Min';

							  	//$services_by_subcategory[$a]['service_duration'] = $min.' Minutes-'.$max.' Minutes';

							$services_by_subcategory[$a]['start_price']=$sub_prc1;//$startFrom;
							//if($ser_details == ""){
							if($services_by_subcategory[$a]['service_detail'] == ""){
								$services_by_subcategory[$a]['service_detail']=$service->service_detail;
								$services_by_subcategory[$a]['service_detail_id'] =$service->id;
							}
							$services_by_subcategory[$a]['review_count']=$cherv;
							$services_by_subcategory[$a]['value'][] = $service;

						  }
					$serId=$service->subcategory_id;

                  }

                  else{
                  	$duration=array();
                  	  $cherv = check_review($service->id);
                  	  $services_by_subcategory[$j]['id'] = $service->id;
                      $services_by_subcategory[$j]['key'] = $service->category_name;
                      $services_by_subcategory[$j]['main_categoryid'] = $service->category_id;
                      $services_by_subcategory[$j]['subid'] = $service->subcategory_id;
					  $services_by_subcategory[$j]['status'] =1;
					  $services_by_subcategory[$j]['review_count'] = $cherv;
					  $services_by_subcategory[$j]['price_start_option'] = $service->price_start_option;
					  $services_by_subcategory[$j]['service_detail'] =$service->service_detail;
					  $services_by_subcategory[$j]['service_detail_id'] =$service->id;
					  $services_by_subcategory[$j]['start_price']=$startFrom;
					  $services_by_subcategory[$j]['service_duration'] = $service->duration.' Min';
					  $services_by_subcategory[$j]['is_open']=intval($service->in_cart);
					  $services_by_subcategory[$j]['value'][] = $matchcatsubcat[$ky];
					  $serId=0;
						$j++;

                  } 
                    $startFrom="";                   
                     	$ky++;
				  }
	  	    	 }
			   }
             }
             
             $add ="";
		 
		 if(!empty($uid)){
				   $add.=",IFNULL((SELECT count(id) FROM st_cart WHERE subcat_id =`st_merchant_category`.`subcategory_id` AND user_id=".$uid."),'0') as total_in_cart";
			     }	 
             //IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`st_merchant_category`.`id`),0) as checkemp
			$field2='st_merchant_category.id,st_category.category_name,st_merchant_category.service_detail,st_merchant_category.subcategory_id as subid,count(*) as count,(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id='.$uid.' AND st_cart.subcat_id=`st_merchant_category`.`subcategory_id`) as is_open,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE subcat_id =`st_merchant_category`.`subcategory_id` AND created_by="'.$id.'"),0) as checkemp'.$add; //chg for is_open ios
			//(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id='.$uid.' AND st_cart.service_id=`st_merchant_category`.`id`) as is_open,
			
			$this->db->having('checkemp >0');
			$sub_category= $this->api_model->join_two('st_merchant_category','st_category','subcategory_id','id',array('st_merchant_category.created_by' =>$id,'st_merchant_category.status' =>'active','st_merchant_category.online'=>1),$field2,'subcategory_id');
			//echo $this->db->last_query();
			$i=0;
			$ck_pr='';

	   		$details= $this->api_model->join_two('st_users','st_banner_images','id','user_id',$whr,$field);
	   		$this->response_data['data']['details']= $details;
	   		$allservices=[];
	   		$sub_services=[];

	   		
	   		 //$all_services=[];

	   		if(!empty($sub_category)){
	   			//print_r($sub_category);
	   			$kyy=0;
	   			$jj=0;
	   			$serIds=0;
			foreach($sub_category as $service1){
           		$this->response_data['data']['all_service'][$i]=$service1;

           		 $sql1="SELECT st_merchant_category.id,name,subcategory_id,category_id as main_categoryid,duration,category_name,price_start_option,price,discount_price,st_category.category_name,st_merchant_category.service_detail,(SELECT COUNT(id) FROM st_cart WHERE st_cart.user_id=".$uid." AND st_cart.service_id=st_merchant_category.id) as in_cart,st_category.id as subcatid FROM st_merchant_category LEFT JOIN st_category ON st_category.id=st_merchant_category.subcategory_id WHERE st_merchant_category.status='active' AND st_merchant_category.created_by=".$id." AND st_merchant_category.online=1  AND st_merchant_category.subcategory_id=".$service1->subid." ORDER BY st_merchant_category.subcategory_id,st_merchant_category.id";
           		  $allservices=$this->api_model->custome_query($sql1,'result');

           		
           		 $kyy=0;$jj=0;
           		 $ct= $service1->count;
           		
           		    $all_services=[];
           		    $sub_prc=0;
           		    $ser_dur=array();
           		    $all_ser_id=array();
           		    $srv_detail="";
           		    $ser_dt_id="";
           		    $totalincart=0;
           		 	foreach($allservices as $service){
                            $ser_dur[]=$service->duration;
                            $all_ser_id[]=	$service->id;

                            if($srv_detail ==""){
                            	$srv_detail = $service->service_detail;
                            	$ser_dt_id = $service->id;
                            }
						  //print_r($service);
						    if(!empty($service->discount_price)) 
						    	$price=$service->discount_price; 
						    else 
						    	$price= $service->price;

						      if(empty($startFrom)) $startFrom=$price;

			                  if($startFrom >$price) $startFrom=$price;

			                    if($i == $ck_pr){
			                    	if($start_prc!=""){
			                    		if($start_prc > $startFrom)
			                    			$start_prc= $startFrom;
			                    	}
			                    	else
			                    		$start_prc= $startFrom;
			                    }
			                    else{
			                    	$start_prc= $startFrom;
			                    }
			                 // if($service->name !=''){
			                  		$chkdrop=0;
			                  		
			                  		if($service->in_cart == 1){
                                  	  $chkdrop=1;
                                  	  $totalincart++;
								     }

                                 // echo $service->subcategory_id.'-'.$serIds;
                                  //if($service->subcategory_id != $serIds){

                                  	$sqlDropcount="SELECT count(st_cart.id) as ctCount FROM st_cart LEFT JOIN st_merchant_category ON st_cart.service_id=st_merchant_category.id WHERE subcategory_id = '".$service->subcategory_id."' AND st_cart.user_id='".$uid."' AND name !=''";
			 	
			 							$dataCt=$this->db->query($sqlDropcount);
			 							$chkdrop=$dataCt->row();
			 							if($chkdrop->ctCount > 0)
			 								$ck=1;
			 							else
			 								$ck=0;

									//$all_services[$jj]['key'] = $service->category_name;
									//$all_services[$jj]['start_price']=$startFrom;
									//$all_services[$jj]['status']=0;
									//$all_services[$jj]['is_open']=$ck;

			 						$this->response_data['data']['all_service'][$i]->start_price=$start_prc;  //$startFrom;
			 						 $min1 = min($ser_dur);
                             		 $max1 = max($ser_dur);
                             		 if($min1 == $max1)
			 							$this->response_data['data']['all_service'][$i]->service_duration= $min1.' Min';
			 						 else
			 						 	$this->response_data['data']['all_service'][$i]->service_duration=$min1.' Min-'.$max1.' Min';

									$all_services[$jj] = $service;
									$jj++;
									$sub_prc=$startFrom;
                                  // }
                                  /* else{

			                      		if($sub_prc > $startFrom)
			                      			$sub_prc=$startFrom;
										$aa=$jj-1;
										//$all_services[$aa]['start_price']=$sub_prc; //$startFrom;
										$all_services[$aa] = $service;

									  }*/
								$serIds=$service->subcategory_id;

			                 // }
			                  /*else{
			                  		
			                  	  $all_services[$jj]['key'] = $service->category_name;
								  $all_services[$jj]['status'] =1;
								  $all_services[$jj]['is_open']=intval($service->in_cart);
								  $all_services[$jj]['start_price']=$startFrom;
								  $all_services[$jj]['value'][] = $service;
							

								  $serIds=0;
									$jj++;
                                 
			                  } */   
			                  $startFrom="";      
			                  $ck_pr=$i;          
			                  	
           		 	}
           		 	 $chervs = check_review(implode(",",$all_ser_id));
           		 	 //echo $this->db->last_query();
           		 	$this->response_data['data']['all_service'][$i]->service_detail=$srv_detail;
           		 	$this->response_data['data']['all_service'][$i]->service_detail_id=$ser_dt_id;
           		 	$this->response_data['data']['all_service'][$i]->review_count=$chervs;
           		 	$this->response_data['data']['all_service'][$i]->sub_services=$all_services;
           		 	$this->response_data['data']['all_service'][$i]->total_in_cart=$totalincart;
                   $kyy++;
           		 
               	 $i++;
      			}	
      		}else{
      			$sub_category=array();
				$this->response_data['data']['all_service']=array();
      		}
	   		//$main_menu=get_menu();
	   		$field3='st_category.id,st_category.category_name,image,icon,st_merchant_category.subcategory_id as sub , IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE subcat_id =`st_merchant_category`.`subcategory_id` AND created_by="'.$id.'"),0) as checkemp'.$add;
	   			$this->db->having('checkemp >0');
	   		$main_menu= $this->api_model->join_two('st_merchant_category','st_category','category_id','id',array('st_merchant_category.created_by' =>$id,'st_merchant_category.status' =>'active'),$field3,'category_id');
			$i=0;
			if(!empty($details)){

				$details[0]->share_url='';
				$details[0]->share_url=base_url('user/service_provider/'.url_encode($details[0]->id));
				//$details=$details[0];
				
				$this->response_data['status']=1;
			    $this->response_data['response_message']='success';
			    $this->api_model->delete('st_recently_search',array('salon_id' => $_POST['salon_id'],'device_id' => $_POST['device_id']));
			    $ins_arry=array();
			    $ins_arry['salon_id']= $_POST['salon_id'];
			    $ins_arry['device_id']= $_POST['device_id']; 
			    $ins_arry['created_on']= date('Y-m-d H:i:s');

			    $this->api_model->insert('st_recently_search',$ins_arry);

			    //$this->response_data['data']['match_subcat']=$services_by_subcategory;
			    foreach($details as $row){
					$this->response_data['data']['details'][$i]->user_id=($row->user_id !='')?$row->user_id:$row->id;
			    	$this->response_data['data']['details'][$i]->image=($row->image !='')?$row->image:'';
					$this->response_data['data']['details'][$i]->image1=($row->image1 !='')?$row->image1:'';
					$this->response_data['data']['details'][$i]->image2=($row->image2 !='')?$row->image2:'';
					$this->response_data['data']['details'][$i]->image3=($row->image3 !='')?$row->image3:'';
					$this->response_data['data']['details'][$i]->image4=($row->image4 !='')?$row->image4:'';
			    	$this->response_data['data']['details'][$i]->rating= ($row->rating !='')?number_format($row->rating,1):'0.0';
			    	$i++;
			    }
				$this->response_data['data']['main_category']=(!empty($main_menu)?$main_menu:array());
				//$this->response_data['data']->sub_category=$sub_category;
				 $this->response_data['data']['matchcatsubcat']=$services_by_subcategory;
			}
			else{
				$this->response_data['status']=0;
			    $this->response_data['response_message']='No record found..';
			}

		}
	}
		echo json_encode($this->response_data);


   }

   function service_forratefilter_post(){


   		$this->form_validation->set_rules('salon_id', 'Salon id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{	
				extract($_POST); 
				 $data= $this->api_model->getWhereAllselect('st_merchant_category',array('created_by'=>$salon_id, 'status' => 'active'),"id,name,(SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as catname");
						 $count=$this->api_model->getWhereRowSelect('st_review',array('merchant_id' => $salon_id),'(SELECT count(rate) FROM st_review where rate=5 AND merchant_id='.$salon_id.') as five,(SELECT count(rate) FROM st_review where rate=4 AND merchant_id='.$salon_id.') as four,(SELECT count(rate) FROM st_review where rate=3 AND merchant_id='.$salon_id.') as three,(SELECT count(rate) FROM st_review where rate=2 AND merchant_id='.$salon_id.') as two,(SELECT count(rate) FROM st_review where rate=1 AND merchant_id='.$salon_id.') as one');

						 
						if(!empty($data)){
							/*
							$merchant_subcat =array();
								foreach($data as $row){
									 $merchant_subcat[$row->catname][]=$row->id;										
									}
							  foreach($merchant_subcat as $k=>$v)
							    {
								  $ar =array('key'=>$k,'value'=>implode(',',$v));
								  $merchant_subcat[]=$ar;	
								} */
							
						$this->response_data['status'] = 1;
						$this->response_data['response_message'] = 'success';
						$this->response_data['data']=$data;
						$this->response_data['count'] = $count; 
						}
						else{
							$this->response_data['status'] = 0;
							$this->response_data['response_message'] = 'No service found';
						}	
			}
		}

		echo json_encode($this->response_data);

   }  


   function favourite_salon_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('salon_id', 'Salon Id', 'required|trim');
		//$this->form_validation->set_rules('action', 'action', 'required|trim|in_list[add,remove]');
		
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						if($this->api_model->get_datacount('st_favourite',array('user_id' => $uid,'salon_id' => $salon_id)) > 0){
						  	 	$res=$this->api_model->delete('st_favourite',array('user_id' => $uid,'salon_id' =>$salon_id)); 
						  	 	$fav=0;	
						  }
						  else{
						  	 	$insertArr['user_id']=$uid;
						  	 	$insertArr['salon_id']= $salon_id;
						  	 	$insertArr['created_on']= date('Y-m-d H:i:s');
						  	 	$insertArr['created_by']= $uid;
						  	 	$res=$this->api_model->insert('st_favourite',$insertArr);
						  	 	$fav=1;
						  	 }
						  
						if($res){
						$this->response_data['status'] = 1;
						$this->response_data['response_message'] = 'success';
						$this->response_data['favourite'] = $fav;
						}
						else{
						$this->response_data['status'] = 0;
						$this->response_data['response_message'] = 'Unable to process..';
						$this->response_data['favourite'] = $fav;
						}
					}
				}
			}
		}
		echo json_encode($this->response_data); 
	}

	function review_detail_post(){
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		$this->form_validation->set_rules('review_id', 'Review Id', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{	extract($_POST);
				$field='id,review,rate,anonymous,st_review.user_id,(select first_name from st_users where id = st_review.user_id) as first_name,(select last_name from st_users where id = st_review.user_id) as last_name,(select first_name from st_users where id = st_review.emp_id) as emp_fname,(select last_name from st_users where id = st_review.emp_id) as emp_lname,(select profile_pic from st_users where id = st_review.user_id) as profile_pic,(select business_name from st_users where id = st_review.merchant_id) as business_name,created_on';
						 $data=$this->api_model->getWhereRowSelect('st_review',array('id' => $review_id),$field);
						 
						 if(!empty($data)){
						 	$this->response_data['status'] = 1;
							$this->response_data['response_message'] = 'success';
							$this->response_data['data'] = $data;
							
							$reply=$this->api_model->getWhereRowSelect('st_review',array('review_id' => $review_id),'id,review,created_on');
								if(!empty($reply))
									$data->reply=$reply;
								else{
									$data->reply['id']="";
									$data->reply['review']="";
									$data->reply['created_on']="";
								}

						}
						else{
							$this->response_data['status'] = 0;
							$this->response_data['response_message'] = 'No record found..';
						}
			}
		}
		echo json_encode($this->response_data); 
	}

	function allsalon_opentime_post(){
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{	extract($_POST);

				$today=strtolower(date("l"));
				$selectMinMaxtime="SELECT MIN(starttime) as mintime,(SELECT MAX(endtime) FROM st_availability WHERE type='open' AND days='".$today."') as maxtime FROM st_availability WHERE type='open' AND days='".$today."'";
         
        		$data= $this->api_model->custome_query($selectMinMaxtime,'row');
				
						if(!empty($data)){
						 	$this->response_data['status'] = 1;
							$this->response_data['response_message'] = 'success';
							$this->response_data['data'] = $data;
							
						}
						else{
							$this->response_data['status'] = 0;
							$this->response_data['response_message'] = 'No record found..';
						}
			}
		}
		echo json_encode($this->response_data); 
	}

	function booking_receipt_new_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('book_id', 'Booking Id', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						$field = "st_invoices.*,st_booking.employee_id,st_booking.merchant_id,book_id,book_by,total_time,booking_time,st_users.first_name,st_users.last_name,st_users.email,st_users.profile_pic,st_users.address,st_users.zip,st_users.country,st_users.city,st_booking.booking_type,st_booking.updated_on,(SELECT first_name FROM st_users where st_users.id=st_booking.employee_id) as emp_name,(SELECT city FROM st_users where st_users.id=st_booking.merchant_id) as m_city,(SELECT tax_number FROM st_users where st_users.id=st_booking.merchant_id) as tax_number,(SELECT zip FROM st_users where st_users.id=st_booking.merchant_id) as m_zip,(SELECT address FROM st_users where st_users.id=st_booking.merchant_id) as m_address,(SELECT country FROM st_users where st_users.id=st_booking.merchant_id) as m_country";
		
						$invoice_detail = $this->api_model->join_three_row('st_invoices','st_booking','st_users','booking_id','id','user_id','id',array('st_invoices.id'=>$book_id),$field);
					  if(!empty($invoice_detail->booking_id))
					    {
					    	$tax_arry =[];
					    	$j=0;
					    	if(!empty($invoice_detail->taxes)){ 
					    		$taxes=json_decode($invoice_detail->taxes);
						   		foreach($taxes as $k=>$v){
						   				$tax_arry[$j]['name']=$k;
						   				$tax_arry[$j]['value']=round($v,2);
						   			$j++;
						   			}
						   		}
						   		

					    	$field1 = "id,user_id,tax,setuptime_start,service_id,service_name,duration,price,discount_price,service_id";  
				
							$whr    = array('booking_id'=>$invoice_detail->booking_id);

							$this->db->order_by('st_booking_detail.id','asc');
							$booking_detail= $this->api_model->getWhereAllselect('st_booking_detail',$whr,$field1);
								
							$this->response_data['status'] = 1;
							$this->response_data['response_message'] = 'success';
							$this->response_data['data'] = $invoice_detail;
							$this->response_data['data']->taxes = $tax_arry;

							$this->response_data['data']->receipt_url=base_url('checkout/printinvoice/').url_encode($book_id);

							if(!empty($booking_detail))
							{
								$ii=0;
								$this->response_data['data']->book_detail = $booking_detail;
								foreach($booking_detail as $rows){
									$sub_name=get_subservicename($rows->service_id);
									 if($sub_name == $rows->service_name)
                            			$service_nm=$rows->service_name;
                          			 else
                            			$service_nm=$sub_name.' - '.$rows->service_name; 
									$this->response_data['data']->book_detail[$ii]->service_name = $service_nm;	
								 $ii++;
								}
								
							}		   				
							

					    }
					    else{
					    	$this->response_data['status'] = 0;
							$this->response_data['response_message'] = 'No record found.';
					    }

					}
				}
			}
		}
		echo json_encode($this->response_data);
	}

	function booking_receipt_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('book_id', 'Booking Id', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						$field="st_booking.id,book_by,(SELECT email from st_users where st_users.id=st_booking.user_id) as email,first_name,last_name,profile_pic,user_id,booking_time,total_minutes,total_price,st_users.gender,(SELECT business_name FROM st_users where st_users.id=st_booking.merchant_id) as business_name,(SELECT city FROM st_users where st_users.id=st_booking.merchant_id) as m_city,(SELECT zip FROM st_users where st_users.id=st_booking.merchant_id) as m_zip,(SELECT address FROM st_users where st_users.id=st_booking.merchant_id) as m_address,country,address,city,zip,st_booking.booking_time,st_booking.updated_on";
							$booking= $this->api_model->join_two('st_booking','st_users','user_id','id',array('st_booking.id' =>$book_id),$field);

							if(!empty($booking))
							{  
								$this->response_data['status'] = 1;
								$this->response_data['response_message'] = 'success';
								$this->response_data['data'] = $booking[0];
								$this->response_data['data']->receipt_url=base_url('booking/downloadReceipt/').url_encode($book_id);
							}
							else{
								$this->response_data['status'] = 0;
								$this->response_data['response_message'] = 'No record found.';
							}

					}
				}
			}
		}
		echo json_encode($this->response_data);
	}

	function notification_status_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						$this->response_data['status'] = 1;
						if(isset($notification) && $notification !=''){
						 $update_data['notification_status']=$notification;
						 $result = $this->api_model->update('st_users',array('id'=>$uid),$update_data);

							if(!empty($result))
								$this->response_data['response_message'] = 'success';
							else{
								$this->response_data['status'] = 0;
								$this->response_data['response_message'] = 'Unable to process.';
							}

						}
						
								$not=$this->api_model->getWhereRowSelect('st_users',array('id' => $uid),'notification_status');
								$this->response_data['data'] = $not->notification_status;
						

					}
				}
			}
		}
		echo json_encode($this->response_data);
	}

	function myfavourite_salon_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						$offset = $perPageData = $total_count= $status = $page_no= 0;
							$limit = 10; 
							$perPage = null; 
						
							$page_no = (isset($page) and !empty($page))?$page:1;  
							 if($page_no !='all')
							 {
								$offset = ($page_no == 1) ? 0 : ($page_no * $limit) - $limit;
								$perPage= $perPageData = $limit; 
							 }
							
							$csql="SELECT count(st_favourite.id) as tcount FROM st_favourite LEFT JOIN st_users ON st_favourite.salon_id=st_users.id WHERE st_favourite.user_id=".$uid."";
							  $totalcount=$this->api_model->custome_query($csql,'row');
							 
							 if(!empty($totalcount->tcount))
							 	$count=$totalcount->tcount;
							 else
							 	$count=0;
								$sql123213="SELECT extra_trial_month from st_users where id = ".$uid;
								$trial12=$this->db->query($sql123213);
								$trial  =  $trial12->row();

								$newMonth = $trial->extra_trial_month;
								$finalDATA =  date('Y-m-d', strtotime('+'.$newMonth.' months'));
								$triEndData =  $finalDATA;
								$cuD = date("Y-m-d");
								$currentData = "'$cuD'" .' '."'00:00:00'";
								 $where123 =array('user_id'=>$this->session->userdata('st_userid'));
		//  $field='st_users.id,user_id,salon_id,business_name,DATE_ADD(`st_users`.`created_on`, INTERVAL + extra_trial_month MONTH) AS endTrial,
		//  (SELECT image FROM st_banner_images WHERE user_id=st_users.id) as 
		//  image,profile_pic,st_favourite.created_on';

							$sql="SELECT DATE_ADD(`st_users`.`created_on`, INTERVAL + extra_trial_month MONTH) AS endTrial,st_users.online_booking, st_users.allow_online_booking, st_favourite.id,st_users.id as salon_id,business_name,business_type,address,email,mobile,address,zip,latitude,longitude,country,city,image,(SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as rating FROM st_favourite LEFT JOIN st_users ON st_favourite.salon_id=st_users.id LEFT JOIN st_banner_images ON st_favourite.salon_id=st_banner_images.user_id WHERE st_favourite.user_id=".$uid."  GROUP BY st_favourite.id DESC HAVING (endTrial  > ".$currentData." OR (allow_online_booking = 'true' AND online_booking='1')) LIMIT ".$perPage." OFFSET ".$offset."";
							$data=$this->api_model->custome_query($sql,'result');
                                    //$this->db->last_query();die;
							if(!empty($data)){
								$i=0;
								$this->response_data['status'] = 1;
								$this->response_data['response_message'] = 'success';
								$this->response_data['total_count']= $count; 		//$count->count;
							  	$this->response_data['per_page']=$perPage;
							  	$this->response_data['current_page']=$page_no;
								$ndata = [];
							  	foreach($data as $usr){
									if (isset($usr->salon_id)) {
										$dataCont = getmembership_exp($usr->salon_id);
										if(!$dataCont['expired']){
											$ndata[] = $usr;
										}
									}
								}
								$this->response_data['data'] = $ndata;
								foreach($ndata as $usr) {
								
									$this->response_data['data'][$i]->rating=($usr->rating !='')?number_format($usr->rating,1):'0.0';
									if(isset($usr->salon_id)){
										$sql2="SELECT `r`.`id`,`duration`, `price`, `buffer_time`, `discount_price`,`subcategory_id`, `u1`.`category_name` as `m_category`, `u2`.`category_name` as `s_category` FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` WHERE `r`.`status` = 'active' AND `r`.`created_by`=".$usr->salon_id." GROUP BY `r`.`subcategory_id` LIMIT 4";
								
										$query2=$this->db->query($sql2);
										$ress=$query2->result();
										if(!empty($ress))
											$this->response_data['data'][$i]->sercvices=$ress;
										else
											$this->response_data['data'][$i]->sercvices=array();

									}
									else
										$this->response_data['data'][$i]->sercvices=array();


									$this->response_data['data'][$i]->image=($usr->image !='')?$usr->image:'';
									$i++;
								}
							}
							else
								$this->response_data['response_message'] = 'No record found..';
						

					}
				}
			}
		}
		echo json_encode($this->response_data);
	}

	// for logout
   function logout_post()
   {

   		$this->form_validation->set_rules('uid', 'User Id', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else{
			if(isset($_POST['device_id']))
			{ 	
			    $uid = (isset($_POST['uid']))?$_POST['uid']:'';
				$device_id = $_POST['device_id'];
				$this->api_model->access_token_delete($uid, $device_id);
				//~ $this->generate_access_token();
			}
			$this->response_data['status'] = 1;
			$this->response_data['response_message'] = 'success';
		}
		echo json_encode($this->response_data); 
   }
   // -------------------------- logout close --------------------------
   
   function remove_profilepic_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		//$this->form_validation->set_rules('image_name', 'Image name', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						 $path = 'assets/uploads/users/'.$uid.'/';
						 $update_data=array();
						 $update_data['profile_pic']='';
						 $result = $this->api_model->update('st_users',array('id'=>$uid),$update_data);

							if(!empty($result))
							{
								$files = glob($path.'*');
								foreach($files as $file){
									if(is_file($file))
									unlink($file); //delete file
								}
								$this->response_data['status'] = 1;
								$this->response_data['response_message'] = 'success';
							}
							else{
								$this->response_data['status'] = 0;
								$this->response_data['response_message'] = 'Unable to process.';
							}

						
						
					
						

					}
				}
			}
		}
		echo json_encode($this->response_data);
	}

	// salon service details
	function service_detail_post(){
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('service_id', 'Service Id', 'required|trim');
		

		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			extract($_POST); 
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{
				$result=$this->api_model->getWhereRowSelect('st_merchant_category',array('id' => $service_id),'id,service_detail,subcategory_id,(SELECT category_name from st_category WHERE st_category.id = subcategory_id) as cat_name');
				
				$this->response_data['status'] = 1;
				$this->response_data['response_message'] = 'success';
				$this->response_data['data']=$result;
			}
		}
		echo json_encode($this->response_data);
	}


function employee_reschedule_time_post()
{
	$this->form_validation->set_rules('uid', 'User id', 'required|trim');
	$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
	$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
	$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
	$this->form_validation->set_rules('date', 'Date', 'required|trim');
	$this->form_validation->set_rules('eid', 'Employee id', 'required|trim');
	$this->form_validation->set_rules('bookid', 'Booking id', 'required|trim');
	//$this->form_validation->set_rules('image_name', 'Image name', 'required|trim');
	if($this->form_validation->run() == FALSE)
	{
		validationErrorMsg();
	}
	else
	{
		if(!checkApikey($this->input->post('api_key')))
			$this->response_data['response_message'] = INVALID_API_KEY;
		else
		{

			extract($_POST); 
			$uid = (isset($uid))?$uid:'';
			$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

			if(empty($is_valid_user->access_token))
			{  
				$this->response_data['response_message'] = INVALID_TOKEN_MSG;
				// delete access token
				$this->api_model->access_token_delete($uid, $device_id);
			}
			else
			{   
				// if access token is 7 days older
				if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
				{
					$this->response_data['status'] = INVALID_TOKEN;
					$this->response_data['response_message'] = TOKEN_EXPIRED;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{
					
					$dayName=date("l", strtotime($date));
					$dayName=strtolower($dayName);
					
					$id=$eid;
					$ii=0;
					$availablity=$this->api_model->getWhereRowSelect('st_availability',array('user_id'=>$id,'days'=>$dayName),'days,type,starttime,endtime,starttime_two,endtime_two');

					if(!empty($availablity)){
						$employeeId  = $eid;
						
						if($availablity->type=='open'){
							//$employeId=" AND employee_id=".$id."";
							
							$date=date("Y-m-d", strtotime($_POST['date']));
							$dateslct = $date;
							$checkTime=0;
							

							// for price and discount //
							$sqlForservice="SELECT `st_booking_detail`.`id`,`st_booking_detail`.`mer_id`,`st_booking_detail`.`user_id`,`st_booking_detail`.`emp_id`,`st_booking_detail`.`buffer_time`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`, st_booking_detail.duration,price_start_option,`st_merchant_category`.`price`,`st_merchant_category`.`type` as stype,`st_booking_detail`.`setuptime`,`st_booking_detail`.`processtime`,`st_booking_detail`.`finishtime`, `st_merchant_category`.`discount_price`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime`,`parent_service_id` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = ".$bookid."  ORDER BY st_booking_detail.id";
							$booking_detail= $this->api_model->custome_query($sqlForservice,'result');
							// for price and discount //

							$totaldurationTim=0;
							if(!empty($availablity->starttime) && !empty($availablity->endtime))
							{
								foreach($booking_detail as $row)
								{
									if($row->stype==1)
										$totaldurationTim       = $totaldurationTim+$row->duration;
									else{  
										$totalMin               = $row->duration;   
										$totaldurationTim       = $totaldurationTim+$totalMin;
									}		

									if ($row->parent_service_id) {
										$pstime = $this->api_model->select(
											'st_offer_availability',
											'starttime,endtime,days,type',
											array(
											'service_id'=>$row->parent_service_id,
											'days' => $dayName
											)
										);
										if ($pstime) {
											$row->starttime = $pstime[0]->starttime;
											$row->endtime = $pstime[0]->endtime;
											$row->type = $pstime[0]->type;
											$row->days = $pstime[0]->days;
										}
									}
								}
								
								$start = $availablity->starttime; 
								//$end = $dayslot->endtime;
								$end=date('H:i:s',strtotime($availablity->endtime. "- ".$totaldurationTim." minutes"));
								// $end=date('H:i:s',strtotime($availablity->endtime));


								$tStart = strtotime($start);
								$tEnd = strtotime($end);
								$tNow = $tStart;
								$k=1;
								$chekDuration=1;
								$ii =0;

								while($tNow <= $tEnd)
								{ 
									$dis=0;
									$total=0;
									
									$timeArray        = array();                           
									$ikj              = 0;
									$strtodatyetime   = $dateslct." ".date('H:i:s',$tNow);
									//$employeeId       ="";
							
									foreach($booking_detail as $row)
									{
										$timeArray[$ikj]        = new stdClass;
										$bkstartTime            = $strtodatyetime;
										$timeArray[$ikj]->start = $bkstartTime; 
							
										if($row->stype==1)
										{
											//$totaldurationTim=$totaldurationTim+$row->duration;
																			
											$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
											$timeArray[$ikj]->end   = $bkEndTime;							    	
											$ikj++;	
											
											$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
											$timeArray[$ikj]        = new stdClass;
											$timeArray[$ikj]->start = $finishStart;
											
											$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
											$timeArray[$ikj]->end   = $finishEnd;
											$ikj++;
											
											$strtodatyetime=$finishEnd;
																		
										}
										else
										{
											$totalMin               = $row->duration;   
											
											$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
											$timeArray[$ikj]->end   = $bkEndTime;							    	
											$ikj++;	
											
											//$totaldurationTim       = $totaldurationTim+$totalMin;
											
											$strtodatyetime=$bkEndTime;							   
										}
										
										if(!empty($row->type) && $row->type=='open')
										{ 
											if($row->starttime<=date('H:i:s',$tNow) && $row->endtime>=date('H:i:s',$tNow))
											{	  
												if(!empty($row->discount_price))
												{ 
													$dis=($row->price-$row->discount_price)+$dis;
													$total=$row->price+$total;
												}
												else
												{
													$total=$row->price+$total;
												} 
											}
											else
											{
												$total=$row->price+$total;
											}
										}
										else
										{ 
											$total=$row->price+$total;
										}
										$checkBoking=0;
										$checkallUser=0;

									}
									
									$resultCheckSlot = checkTimeSlots($timeArray,$employeeId,$booking_detail[0]->mer_id,$totaldurationTim,$bookid);
					
					
								//echo $checkBoking;
								
									if($checkBoking!=1 && $resultCheckSlot==true)
									{
										/*if($checkBoking!=1)
										{*/
										//if($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s')){ }
					
										/*if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s')) || $checkallUser!=0){ }*/
										if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s'))){ }
										else
										{ 
											$checkTime++;
											$this->response_data['status']=1;
											$this->response_data['response_message']='Success';
											$this->response_data['data'][$ii]['time']=date("H:i",$tNow);
											$this->response_data['data'][$ii]['price']= $total-$dis;
											$this->response_data['data'][$ii]['discount']= (!empty($dis))?$total:'0';
											$this->response_data['data'][$ii]['aa']= $row;
											$ii++;
										} 
									}
									$k++; $tNow = strtotime('+15 minutes',$tNow); 
									}
									
							}
							else 
							{   
								$checkTime++; 
								$this->response_data['response_message']="Leider sind am ausgewählten Tag keine freien Termine verfügbar. Bitte wähle einen anderen Tag";
							} 
										
							if(!empty($availablity->starttime_two) && !empty($availablity->endtime_two))
							{
								
						
								/*foreach($booking_detail as $row)
								{
								$ti=$row->duration+$row->buffer_time;
								$totaldurationTim=$totaldurationTim+$ti;
								}*/
					
								$start = $availablity->starttime_two; 
								//$end=date('H:i:s',strtotime($dayslot->endtime_two. "- ".$totaldurationTim." minutes"));
								//$end= $dayslot->endtime;
								$end    = date('H:i:s',strtotime($availablity->endtime_two));

								$tStart = strtotime($start);
								$tEnd = strtotime($end);
								$tNow = $tStart;
								$k=1;
								$chekDuration=1;
								while($tNow <= $tEnd)
								{ 
									$dis=0;
									$total=0;
									$timeArray        = array();                           
									$ikj              = 0;
									$strtodatyetime   = $dateslct." ".date('H:i:s',$tNow);
									//$employeeId       = "";
							
										foreach($booking_detail as $row)
										{
											$timeArray[$ikj]        = new stdClass;
											$bkstartTime            = $strtodatyetime;
											$timeArray[$ikj]->start = $bkstartTime;
							
											if($row->stype==1)
											{
												//$totaldurationTim=$totaldurationTim+$row->duration;
																	
												$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
												$timeArray[$ikj]->end   = $bkEndTime;							    	
												$ikj++;	
												
												$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
												$timeArray[$ikj]        = new stdClass;
												$timeArray[$ikj]->start = $finishStart;
												
												$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
												$timeArray[$ikj]->end   = $finishEnd;
												$ikj++;
												
												$strtodatyetime=$finishEnd;
																		
											}
											else
											{
												$totalMin               = $row->duration;   
												
												$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
												$timeArray[$ikj]->end   = $bkEndTime;							    	
												$ikj++;	
												
												//$totaldurationTim       = $totaldurationTim+$totalMin;
												
												$strtodatyetime=$bkEndTime;							   
											} 
										
										
											if(!empty($row->type) && $row->type=='open')
											{ 
												if($row->starttime<=date('H:i:s',$tNow) && $row->endtime>=date('H:i:s',$tNow))
												{	  
													if(!empty($row->discount_price))
													{ 
														$dis=($row->price-$row->discount_price)+$dis;
														$total=$row->price+$total;
													}
													else
													{
														$total=$row->price+$total;
													} 
												}
												else
												{
													$total=$row->price+$total;
												}
											}
											else
											{ 
												$total=$row->price+$total;
											}

						
											$checkBoking=0;
											$checkallUser=0;
									
											if((!empty($_GET['employee_select']) && $_GET['employee_select']!='any' && !empty($employee_list)) || (!empty($employee_list) && count($employee_list)==1))
												{
												if(!empty($_GET['employee_select']) && $_GET['employee_select']!='any')
												{
														$employeeId = $_GET['employee_select'];
												}
												else 
													$employeeId  = $employee_list[0]->id;									  
												}
											//echo '<pre>'; print_r($employee_list); die;	  
												
											/*if(!empty($empBookSlot) && (empty($_GET['employee_select']) || $_GET['employee_select']=='any') && !empty($employee_list) && count($employee_list)!=1)
												{
													$employeeId = $employee_list[0]->id;
											
												}*/	
									
											
										}
									
											$resultCheckSlot = checkTimeSlots($timeArray,$employeeId,$booking_detail[0]->mer_id,$totaldurationTim,$bookid);
					
											//if($checkBoking!=1)
											if($checkBoking!=1 && $resultCheckSlot==true)
											{
												//if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s')) || $checkallUser!=0){ }
												if(($dateslct==date('Y-m-d') && date('H:i:s',$tNow)<date('H:i:s'))){ }
												else{ $checkTime++; 
													
													$this->response_data['status']=1;
													$this->response_data['response_message']='Success';
													$this->response_data['data'][$ii]['time']=date("H:i",$tNow);
													$this->response_data['data'][$ii]['price']= $total-$dis;
													$this->response_data['data'][$ii]['discount']= (!empty($dis))?$total:'0';		
													$ii++;	
													} 
											} 
											//echo $k;
											$k++;
											$tNow = strtotime('+15 minutes',$tNow); 
								
								}


	
							}


							$this->response_data['status'] = 1;
							$this->response_data['response_message']='Success';
						}
						else{
						$this->response_data['status'] = 0;
						$this->response_data['response_message']='Leider sind am ausgewählten Tag keine freien Termine verfügbar.
							Bitte wähle einen anderen Tag';
						} 
						}
					else{
					$this->response_data['status'] = 0;
					$this->response_data['response_message']='Salon not available';
					}

				}
			}
		}
	}
	echo json_encode($this->response_data);
}



function employee_reschedule_time_old_post()
	{
		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('date', 'Date', 'required|trim');
		$this->form_validation->set_rules('eid', 'Employee id', 'required|trim');
		$this->form_validation->set_rules('bookid', 'Booking id', 'required|trim');
		//$this->form_validation->set_rules('image_name', 'Image name', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{   
					// if access token is 7 days older
					if(($is_valid_user->last_access + ACCESS_TOKEN_TIME) < time())
					{
						$this->response_data['status'] = INVALID_TOKEN;
						$this->response_data['response_message'] = TOKEN_EXPIRED;
						// delete access token
						$this->api_model->access_token_delete($uid, $device_id);
					}
					else
					{
						
					$dayName=date("l", strtotime($date));
					$dayName=strtolower($dayName);
					
					$id=$eid;
					$ii=0;
			     	$availablity=$this->api_model->getWhereRowSelect('st_availability',array('user_id'=>$id,'days'=>$dayName),'days,type,starttime,endtime,starttime_two,endtime_two');

			     	 if(!empty($availablity)){
						 
						  if($availablity->type=='open'){
							 $employeId=" AND employee_id=".$id."";
							 
							 $date=date("Y-m-d", strtotime($_POST['date']));
							  
							 $info=$this->api_model->getWhereRowSelect('st_booking',array('id'=>$bookid),'id,total_minutes,total_buffer');

							 $totaldurationTim=$info->total_minutes+$info->total_buffer;
							  
							 
							$selectBookingvailablity="SELECT id,booking_time,booking_endtime FROM st_booking WHERE status='confirmed' AND booking_time>='".$date." 00:00:00"."' AND booking_endtime<='".$date." 23:59:00"."'".$employeId." AND id!=".$bookid."";
							 
							$empBookSlot= $this->api_model->custome_query($selectBookingvailablity,'result');

							// for price and discount //
							$sqlForservice="SELECT `st_booking_detail`.`id`,`st_booking_detail`.`user_id`,`st_merchant_category`.`buffer_time`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`, st_merchant_category.duration, `st_merchant_category`.`price`, `st_merchant_category`.`discount_price`,`days`,`type`,`starttime`,`endtime` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = ".$bookid." ORDER BY st_booking_detail.id";
							  $booking_detail= $this->api_model->custome_query($sqlForservice,'result');
							// for price and discount //

	    					


							  //echo "<pre>"; print_r($availablity); die;
							  
												 $start = $availablity->starttime; //you can write here 00:00:00 but not need to it
												$end =$availablity->endtime;

												$tStart = strtotime($start);
												$tEnd = strtotime(date('H:i:s',strtotime($end. "- ".$totaldurationTim." minutes")));
												
												$currtime=date('H:i:s');
												$crntdate=date('Y-m-d');
												//echo $tStart."==".$tEnd;
												$tNow = $tStart;
												while($tNow <= $tEnd){ 

													// for discount //
													  $dis=0;
							                           $total=0;

							                            foreach($booking_detail as $row){
														   if(!empty($row->type) && $row->type=='open'){ 
															if($row->starttime<=date('H:i:s',$tNow) && $row->endtime>=date('H:i:s',$tNow)){	  
																if(!empty($row->discount_price)){ 
															    	$dis=($row->price-$row->discount_price)+$dis;
																    $total=$row->price+$total;
															     }
															    else{
																   $total=$row->price+$total;
																 } 
															   }
															   else{
																   $total=$row->price+$total;
																   }
															}else{ 
															 $total=$row->price+$total;
														    }
														}
															// end discount //

													$checkBoking=1;
													 if(!empty($empBookSlot)){	
														foreach($empBookSlot as $eslot){
														  
														  $estarttime=date('H:i:s',strtotime($eslot->booking_time. "- ".$totaldurationTim." minutes"));
														  $eendtime=date('H:i:s',strtotime($eslot->booking_endtime));
														   if($estarttime<=date('H:i:s',$tNow) && $eendtime>=date('H:i:s',$tNow)){
															$checkBoking=2;	  
															  break;
															  }
														  }
														}	
												
			                                       $nowTime=date("H:i:s",$tNow);
			                                       //~ echo $nowTime."==".$currtime."==".$date."==".$crntdate."</br>";									
												 if($checkBoking!=2 && ($date==$crntdate && $currtime<=$nowTime || $date!=$crntdate)){
												
														$this->response_data['data'][$ii]['time']=date("H:i",$tNow);
														$this->response_data['data'][$ii]['price']= $total-$dis;
														$this->response_data['data'][$ii]['discount']= (!empty($dis))?$total:'0';
														$ii++;
													 }
										       $tNow = strtotime('+15 minutes',$tNow); 
							                }

							             if(!empty($availablity->starttime_two) && !empty($availablity->endtime_two)){
											  
											    $start = $availablity->starttime_two; //you can write here 00:00:00 but not need to it
												$end =$availablity->endtime_two;

												$tStart = strtotime($start);
												$tEnd = strtotime(date('H:i:s',strtotime($end. "- ".$totaldurationTim." minutes")));
												
												$currtime=date('H:i:s');
												$crntdate=date('Y-m-d');
												//echo $tStart."==".$tEnd;
												$tNow = $tStart;
												while($tNow <= $tEnd){


												// for discount //
													  $dis=0;
							                           $total=0;

							                            foreach($booking_detail as $row){
														   if(!empty($row->type) && $row->type=='open'){ 
															if($row->starttime<=date('H:i:s',$tNow) && $row->endtime>=date('H:i:s',$tNow)){	  
																if(!empty($row->discount_price)){ 
															    	$dis=($row->price-$row->discount_price)+$dis;
																    $total=$row->price+$total;
															     }
															    else{
																   $total=$row->price+$total;
																 } 
															   }
															   else{
																   $total=$row->price+$total;
																   }
															}else{ 
															 $total=$row->price+$total;
														    }
														}
															// end discount //
															 
													$checkBoking=1;
													 if(!empty($empBookSlot)){	
														foreach($empBookSlot as $eslot){
														  
														  $estarttime=date('H:i:s',strtotime($eslot->booking_time. "- ".$totaldurationTim." minutes"));
														  $eendtime=date('H:i:s',strtotime($eslot->booking_endtime));
														   if($estarttime<=date('H:i:s',$tNow) && $eendtime>=date('H:i:s',$tNow)){
															$checkBoking=2;	  
															  break;
															  }
														  }
														}	
												
			                                       $nowTime=date("H:i:s",$tNow);
			                                       //~ echo $nowTime."==".$currtime."==".$date."==".$crntdate."</br>";									
												 if($checkBoking!=2 && ($date==$crntdate && $currtime<=$nowTime || $date!=$crntdate)){
														$this->response_data['data'][$ii]['time']=date("H:i",$tNow);
														$this->response_data['data'][$ii]['price']= $total-$dis;
														$this->response_data['data'][$ii]['discount']= (!empty($dis))?$total:'0';		
														$ii++;
													  
													 }
										       $tNow = strtotime('+15 minutes',$tNow); 
							                }
											 
										}  
							   $this->response_data['status'] = 1;
							   $this->response_data['response_message']='Success';
						    }
						  else{
						  	$this->response_data['status'] = 0;
						  	$this->response_data['response_message']='Leider sind am ausgewählten Tag keine freien Termine verfügbar.
							  Bitte wähle einen anderen Tag';
						  	} 
						 }
					 else{
					 	$this->response_data['status'] = 0;
						$this->response_data['response_message']='Salon not available';
					  	}

					}
				}
			}
		}
		echo json_encode($this->response_data);
	}

	function booking_reshedule_post()
	{

		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('date', 'Date', 'required|trim');
		$this->form_validation->set_rules('eid', 'Employee id', 'required|trim');
		$this->form_validation->set_rules('bookid', 'Booking id', 'required|trim');
		$this->form_validation->set_rules('chg_time', 'Time', 'required|trim');
		
		//$this->form_validation->set_rules('image_name', 'Image name', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
				$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{ 


					$originalDate = $date;
					$newDate = date("Y-m-d", strtotime($originalDate));
					$field='id,merchant_id,booking_time,total_minutes,booking_type,email,fullname,total_buffer,employee_id,(SELECT notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as additional_notification_time';
					$oldDate = '';

					$info=$this->api_model->getWhereRowSelect('st_booking',array('id'=>$bookid,'reshedule_count_byuser !='=>1),$field);

					if(!empty($info))
					{
						$toDate= date('Y-m-d H:i:s');
						$last_date= date('Y-m-d H:i:s',strtotime('+24 hours'));
						$book_date = $info->booking_time;		
						$oldDate = $info->booking_time;

						/*if($last_date > $book_date){
							$this->response_data['status'] = 0;
							$this->response_data['response_message']='You can reshedule booking before 24 hr';
						}*/
						if(strtotime($info->booking_time) < strtotime(date('Y-m-d H:i:s')))
						{
							$this->response_data['status'] = 0;
							$this->response_data['response_message']='Booking time out unable to reshedule';
						} else {
				
							$date = $newDate;
							$nameOfDay = date('l', strtotime($date));
							// $totalMinutes=$info->total_minutes+$info->total_buffer;
							$times = strtotime($chg_time);
							// $newTime = date("H:i", strtotime('+ '.$totalMinutes.' minutes', $times));
							$dayName=strtolower($nameOfDay);
							
							$totalMinutes = 0;
						
							// $select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND ((`starttime`<='".$chg_time."' AND endtime>='".$newTime."') OR (`starttime_two`<='".$chg_time."' AND endtime_two>='".$newTime."')) AND user_id=".$info->employee_id."";
																		
							//$check= $this->api_model->custome_query($select123,'row');

							//if(!empty($check)){

							$bk_time=$newDate.' '.$chg_time;
							//$newtimestamp = strtotime(''.$bk_time.' + '.$totalMinutes.' minute');
							//$book_end=date('Y-m-d H:i:s', $newtimestamp);
							
								
							//$whereAS='((booking_time>="'.$bk_time.'" AND booking_time<="'.$book_end.'") OR (booking_endtime>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'") OR (booking_time<="'.$bk_time.'" AND booking_endtime>="'.$book_end.'") OR (booking_time>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'")) AND status!="cancelled"';
									
									
							//$this->db->where($whereAS);
							//$check=$this->api_model->getWhereRowSelect('st_booking',array('employee_id'=>$info->employee_id,'id !='=>$bookid),'id');

				      
							$sqlForservice = "SELECT `st_booking_detail`.`id`,`st_booking_detail`.`user_id`,`st_booking_detail`.`buffer_time`,`st_booking_detail`.`has_buffer`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`,st_booking_detail.duration,st_booking_detail.service_type as stype,st_booking_detail.setuptime,st_booking_detail.processtime,st_booking_detail.finishtime,`st_merchant_category`.`price`, `st_merchant_category`.`discount_price`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime`, `parent_service_id` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = ".$bookid."  ORDER BY st_booking_detail.id";

							$totaldurationTim = 0; 
							$booking_detail= $this->api_model->custome_query($sqlForservice,'result');

							$total_price=$total_buffer=$total_min=$total_dis=0;
				               
							if(empty($check) && !empty($booking_detail))
							{
								$eid = $this->input->post('eid');
								$employee_detail= $this->api_model->custome_query('SELECT salon.business_name FROM st_users as emp JOIN st_users as salon ON emp.merchant_id = salon.id WHERE emp.status != "active" AND emp.id = ' .$eid, 'row');

								if (empty($employee_detail)) {
									$timeArray        = array();                           
									$ikj              = 0;
									$strtodatyetime   = $bk_time;
									foreach($booking_detail as $row)
									{
															
										if($row->stype==1)
											$totaldurationTim       = $totaldurationTim+$row->duration;
										else{  
											$totalMin               = $row->duration;   
											$totaldurationTim       = $totaldurationTim+$totalMin;
										}

										if ($row->parent_service_id) {
											$pstime = $this->api_model->select(
												'st_offer_availability',
												'starttime,endtime,days,type',
												array(
												'service_id'=>$row->parent_service_id,
												'days' => $dayName
												)
											);
											if ($pstime) {
												$row->starttime = $pstime[0]->starttime;
												$row->endtime = $pstime[0]->endtime;
												$row->type = $pstime[0]->type;
												$row->days = $pstime[0]->days;
											}
										}
										//$total_buffer=$row->buffer_time+$total_buffer;
										//$total_min=$row->duration+$total_min;

										$timeArray[$ikj]        = new stdClass;						
										$bkstartTime            = $strtodatyetime;
										$timeArray[$ikj]->start = $bkstartTime; 

										if($row->stype==1)
										{
											$total_min=$row->duration+$total_min;
																		
											$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
											$timeArray[$ikj]->end   = $bkEndTime;							    	
											$ikj++;	
											
											$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
											$timeArray[$ikj]        = new stdClass;
											$timeArray[$ikj]->start = $finishStart;
											
											$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
											$timeArray[$ikj]->end   = $finishEnd;
											$ikj++;
											
											$strtodatyetime=$finishEnd;
																						
										}else
										{
											$total_buffer           = $row->buffer_time+$total_buffer;
											$totalMin               = $row->duration;  
											
											$total_min              = $totalMin+$total_min - $row->buffer_time;
											
											$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
											$timeArray[$ikj]->end   = $bkEndTime;							    	
											$ikj++;	
											
											$strtodatyetime=$bkEndTime;							   
										}	
															
															
										if(!empty($row->type) && $row->type=='open')
										{ 
											if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
											{	  
												if(!empty($row->discount_price)){	  
													$total_dis=($row->price-$row->discount_price)+$total_dis;
													$total_price=$row->discount_price+$total_price;  
												}
												else{
													$total_price=$row->price+$total_price;  
												} 
											}
											else $total_price=$row->price+$total_price;  
										}else $total_price=$row->price+$total_price; 
															
															
									}  
									$resultCheckSlot = checkTimeSlots($timeArray,$info->employee_id,$info->merchant_id,$totalMinutes,$bookid);
									if($resultCheckSlot==true)
									{

										$min           =  $total_buffer+$total_min;
										$newtimestamp  = strtotime(''.$bk_time.' + '.$min.' minute');
										$book_end      = date('Y-m-d H:i:s', $newtimestamp);
										//notification set time
										$notif_time    = $info->notification_time;
										$ad_notif_time    = $info->additional_notification_time;
										$timestamp     = strtotime($bk_time);
										$time          = $timestamp - ($notif_time * 60 * 60);
										$ad_time          = $timestamp - ($ad_notif_time * 60 * 60);
										// Date and time after subtraction
										$notif_date    = date("Y-m-d H:i:s", $time);
										if($ad_notif_time != '0'){
											$ad_notif_date    = date("Y-m-d H:i:s", $ad_time);
											$book_Arr['additional_notification_date'] = $ad_notif_date;	
										}

										$book_Arr['booking_time']      = $bk_time;
										$book_Arr['booking_endtime']   = $book_end;
										$book_Arr['total_minutes']     = $total_min;
										$book_Arr['total_buffer']      = $total_buffer;
										$book_Arr['total_time']        = $min;
										$book_Arr['total_price']       = $total_price;
										$book_Arr['total_discount']    = $total_dis;
										$book_Arr['pay_status']        = 'cash';
										$book_Arr['status']            = 'confirmed';
										$book_Arr['notification_date'] = $notif_date;
										$book_Arr['reshedule_count_byuser']=1;
										$book_Arr['updated_on']        = date('Y-m-d H:i:s');
										$book_Arr['updated_by']        = $this->session->userdata('st_userid');
							
										$book_Arr['seen_status'] = 0;


										if($this->api_model->update('st_booking',array('id'=>$bookid),$book_Arr))
										{
				
											//$this->api_model->delete('st_booking_detail',array('booking_id' =>$bookid));

											$boojkstartTime  = $bk_time;
							
											foreach($booking_detail as $row)
											{
												$detail_Arr = [];
												//$detail_Arr['booking_id']       = url_decode($_POST['reSchedule_id']);							
												$detail_Arr['mer_id']           = $info->merchant_id;
												$detail_Arr['emp_id']           = $info->employee_id;
												$detail_Arr['service_type']     = $row->stype;
												$detail_Arr['has_buffer']       = $row->has_buffer;
												if($row->stype==1)
												{							
													$detail_Arr['setuptime']        = $row->setuptime;
													$detail_Arr['processtime']      = $row->processtime;
													$detail_Arr['finishtime']       = $row->finishtime;
													$detail_Arr['setuptime_start']  = $boojkstartTime;										 
																							
													$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$row->setuptime.' minute'));
													$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$row->processtime.' minute'));
													$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
												
													$detail_Arr['setuptime_end']    = $setuEnd;	
													$detail_Arr['finishtime_start'] = $finishStart;	
													$detail_Arr['finishtime_end']   = $finishEnd;
																								
													$boojkstartTime                 = $finishEnd;
											
												}
												else
												{
													$totalMin                       = $row->duration;							
													$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
													$detail_Arr['setuptime_start']  = $boojkstartTime;	
													$detail_Arr['setuptime_end']    = $setuEnd;	
												
													$boojkstartTime                 = $setuEnd;
												}
								
												$detail_Arr['service_id']    = $row->service_id;
												if(!empty($row->name))         
													$detail_Arr['service_name']  = $row->name;
												else                            
													$detail_Arr['service_name']  = $row->category_name;
											
												if(!empty($row->type) && $row->type=='open')
												{ 
													if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
													{	  
														if(!empty($row->discount_price)){
															$detail_Arr['price']           = $row->discount_price;
															$detail_Arr['discount_price']  = $row->price-$row->discount_price;
														}  
														else $detail_Arr['price']        = $row->price;
													}
													else $detail_Arr['price']            = $row->price;
												}
												else $detail_Arr['price']                  = $row->price;
									

												$detail_Arr['duration']                    = $row->duration;
												$detail_Arr['buffer_time']                 = $row->buffer_time;
												$detail_Arr['updated_on']                  = date('Y-m-d H:i:s');
												$detail_Arr['user_id']                     = $uid;
												$detail_Arr['updated_by']                  = $uid;
												$this->api_model->update('st_booking_detail',array('id'=>$row->id),$detail_Arr);
								

											}
							

											$this->data['main']="";
											///mail section
											
											$this->data['main']= $this->api_model->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$bookid),'st_booking.id,business_name,st_users.salon_email_setting,employee_id,st_users.first_name as salon_name,st_users.email,booking_time,total_time,st_booking.merchant_id,st_booking.user_id,book_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select email from st_users where id=st_booking.user_id) as usemail,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
											if(!empty($this->data['main'])){

												$field="st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";  
												$whr=array('booking_id'=>$bookid);
												$this->data['booking_detail']= $this->api_model->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
												$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

												$body_msg=str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_reshedule_body"));
												$MsgTitle=$this->lang->line("booking_reshedule_title");
												
												if($info->booking_type=='guest'){
													$email=$info->email;
													$this->data['booking_detail'][0]->first_name=$info->fullname;
												}
												else{
													if($this->data['main'][0]->user_notify != 0){
														sendPushNotification($this->data['main'][0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $bookid, 'booking_status' => 'reschedule' ,'click_action' => 'BOOKINGDETAIL'));
													}
													$email=$this->data['main'][0]->usemail;
												}	

												$message = $this->load->view('email/reshedule_booking_new',$this->data, true);
												$mail = emailsend($email,$this->lang->line("styletimer_reschedule_booking"),$message,'styletimer');
												
												if($this->data['main'][0]->salon_email_setting==1){
													$this->data['old_date'] = $oldDate;
													$message1 = $this->load->view('email/reshedule_booking_salon',$this->data, true);
													$mail1    = emailsend($this->data['main'][0]->email,$this->lang->line("styletimer_reschedule_booking"),$message1,'styletimer');
												}

												$empDat = is_mail_enable_for_user_action($this->data['main'][0]->employee_id);
												if ($empDat) {
													$tmp = $this->data;
													$tmp['main'][0]->salon_name = $empDat->first_name;
													$tmp['old_date'] = $oldDate;
													$message2 = $this->load->view('email/reshedule_booking_salon',$tmp, true);
													emailsend($empDat->email,$this->lang->line('styletimer_reschedule_booking'),$message2,'styletimer');
												}
											}

											$yrdata= strtotime($_POST['date']);
											$ddd = date('d F Y', $yrdata);
											$yrda = strtotime($_POST['chg_time']);
											$ttt = date('H:i', $yrda);
											
											$yrdata= strtotime($_POST['date'].' '.$_POST['chg_time']);
											
											$mname =  date('F',$yrdata);
											
											$date =  str_replace($mname,$this->lang->line($mname),date('d. F Y \u\m H:i \U\h\r',$yrdata));

											$this->response_data['status'] = 1;
											$this->response_data['response_message'] = str_replace('*date*',$date,$this->lang->line("reshedule_scs_msg"));
										}
										else{
											$this->response_data['status'] = 0;
											$this->response_data['response_message']='Sorry unable to process';
										}
						
									}
									else
									{
										$this->response_data['status'] = 0;
										$this->response_data['response_message']='Der Mitarbeiter ist im ausgewählten Zeitraum nicht verfügbar.';
									}
								}
								else {
									$this->response_data['status'] = 0;
									$this->response_data['response_message']='Der ausgewählte Mitarbeiter existiert leider nicht mehr bei '.$employee_detail->business_name.'. Bitte führe die Buchung erneut über das Profil des Salons durch.';
								}
				  			}
							else{
								$this->response_data['status'] = 0;
								$this->response_data['response_message']='Der Mitarbeiter ist im ausgewählten Zeitraum nicht verfügbar.';
							} 		
						//}	
						// else{
								//$this->response_data['status'] = 0;
								//$this->response_data['response_message']='Der Mitarbeiter ist im ausgewählten Zeitraum nicht verfügbar.';
							//}
						

						}

						//date("h:i");
					}
					else{
						$this->response_data['status'] = 0;
						$this->response_data['response_message']='You can not reschedule this booking.';
					}
				}
			}
		}
		echo json_encode($this->response_data);
	}

	function booking_reshedule_old_post()
	{

		$this->form_validation->set_rules('uid', 'User id', 'required|trim');
   		$this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
		$this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		$this->form_validation->set_rules('date', 'Date', 'required|trim');
		$this->form_validation->set_rules('eid', 'Employee id', 'required|trim');
		$this->form_validation->set_rules('bookid', 'Booking id', 'required|trim');
		$this->form_validation->set_rules('chg_time', 'Time', 'required|trim');
		
		//$this->form_validation->set_rules('image_name', 'Image name', 'required|trim');
		if($this->form_validation->run() == FALSE)
		{
			validationErrorMsg();
		}
		else
		{
			if(!checkApikey($this->input->post('api_key')))
					$this->response_data['response_message'] = INVALID_API_KEY;
			else
			{

				extract($_POST); 
				$uid = (isset($uid))?$uid:'';
				$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

				if(empty($is_valid_user->access_token))
				{  
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
				else
				{ 


		$originalDate = $date;
		$newDate = date("Y-m-d", strtotime($originalDate));
		$field='id,merchant_id,booking_time,total_minutes,booking_type,email,fullname,total_buffer,employee_id,(SELECT notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as additional_notification_time';

		$info=$this->api_model->getWhereRowSelect('st_booking',array('id'=>$bookid),$field);
		$oldDate = '';
		if(!empty($info)){
			$oldDate = $info->booking_time;
			$toDate= date('Y-m-d H:i:s');
			$last_date= date('Y-m-d H:i:s',strtotime('+24 hours'));
			$book_date = $info->booking_time;		
			if($last_date > $book_date){
				$this->response_data['status'] = 0;
				$this->response_data['response_message']='You can reshedule booking before 24 hr';
			}
			else{
				
				 $date = $newDate;
            	 $nameOfDay = date('l', strtotime($date));
           		 $totalMinutes=$info->total_minutes+$info->total_buffer;
            	 $times = strtotime($chg_time);
				 $newTime = date("H:i", strtotime('+ '.$totalMinutes.' minutes', $times));
				 $dayName=strtolower($nameOfDay);

             
               	 $select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND ((`starttime`<='".$chg_time."' AND endtime>='".$newTime."') OR (`starttime_two`<='".$chg_time."' AND endtime_two>='".$newTime."')) AND user_id=".$info->employee_id."";
															
				$check= $this->api_model->custome_query($select123,'row');

               	if(!empty($check)){

				  $bk_time=$newDate.' '.$chg_time;
				  $newtimestamp = strtotime(''.$bk_time.' + '.$totalMinutes.' minute');
				  $book_end=date('Y-m-d H:i:s', $newtimestamp);
				  
					
				  $whereAS='((booking_time>="'.$bk_time.'" AND booking_time<="'.$book_end.'") OR (booking_endtime>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'") OR (booking_time<="'.$bk_time.'" AND booking_endtime>="'.$book_end.'") OR (booking_time>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'")) AND status!="cancelled"';
				        
				        
				       $this->db->where($whereAS);
				       $check=$this->api_model->getWhereRowSelect('st_booking',array('employee_id'=>$info->employee_id,'id !='=>$bookid),'id');

				      

				  $sqlForservice="SELECT `st_booking_detail`.`id`,`st_booking_detail`.`user_id`,`st_merchant_category`.`buffer_time`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`, st_merchant_category.duration, `st_merchant_category`.`price`, `st_merchant_category`.`discount_price`,`days`,`type`,`starttime`,`endtime` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = ".$bookid."  ORDER BY st_booking_detail.id";
		
	    		  $booking_detail= $this->api_model->custome_query($sqlForservice,'result');

	    		  $total_price=$total_buffer=$total_min=$total_dis=0;
				               
			      if(empty($check) && !empty($booking_detail)){

			      		foreach($booking_detail as $row)
										 {
											
											$total_buffer=$row->buffer_time+$total_buffer;
											$total_min=$row->duration+$total_min;
											
										if(!empty($row->type) && $row->type=='open'){ 
											if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
											  {	  
											   if(!empty($row->discount_price)){	  
											     $total_dis=($row->price-$row->discount_price)+$total_dis;
											     $total_price=$row->discount_price+$total_price;  
											    }
											   else{
												   $total_price=$row->price+$total_price;  
												   } 
											  }
											 else $total_price=$row->price+$total_price;  
										   }else $total_price=$row->price+$total_price; 
											  
											
										 }  


									 $min=$total_buffer+$total_min;
									 $newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
									 $book_end=date('Y-m-d H:i:s', $newtimestamp);
	 								 //notification set time
	 								 $notif_time=$info->notification_time;
	 								 $ad_notif_time=$info->additional_notification_time;
									 $timestamp = strtotime($bk_time);
									 $time = $timestamp - ($notif_time * 60 * 60);
									 $ad_time = $timestamp - ($ad_notif_time * 60 * 60);
									// Date and time after subtraction
									 $notif_date = date("Y-m-d H:i:s", $time);
									 if($ad_notif_time != '0'){
									 	$ad_notif_date = date("Y-m-d H:i:s", $ad_time);	
									 	$book_Arr['additional_notification_date']=$ad_notif_date;
									 }


									 $book_Arr['booking_time']=$bk_time;
									 $book_Arr['booking_endtime']=$book_end;
									 $book_Arr['total_minutes']=$total_min;
									 $book_Arr['total_buffer']=$total_buffer;
									 $book_Arr['total_price']=$total_price;
									 $book_Arr['total_discount']=$total_dis;
									 $book_Arr['pay_status']='cash';
									 $book_Arr['status']='confirmed';
									 $book_Arr['reshedule_count_byuser']=1;
									 $book_Arr['notification_date']=$notif_date;
									 $book_Arr['updated_on']=date('Y-m-d H:i:s');
									 $book_Arr['updated_by']=$uid;
									


					if($this->api_model->update('st_booking',array('id'=>$bookid),$book_Arr)){

						$this->api_model->delete('st_booking_detail',array('booking_id' =>$bookid));

						foreach($booking_detail as $row){
							$detail_Arr = [];
							$detail_Arr['booking_id']=$bookid;
							$detail_Arr['service_id']=$row->service_id;
							if(!empty($row->name))
							$detail_Arr['service_name']=$row->name;
						   else
							$detail_Arr['service_name']=$row->category_name;
							
							if(!empty($row->type) && $row->type=='open'){ 
								if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
						          {		  
									 if(!empty($row->discount_price)){
										$detail_Arr['price']=$row->discount_price;
										$detail_Arr['discount_price']=$row->price-$row->discount_price;
										}  
								      else $detail_Arr['price']=$row->price;
								  }
								  else $detail_Arr['price']=$row->price;
							   }
							 
							else $detail_Arr['price']=$row->price;
						         

							$detail_Arr['duration']=$row->duration;
							$detail_Arr['buffer_time']=$row->buffer_time;
							$detail_Arr['created_on']=date('Y-m-d H:i:s');
							$detail_Arr['user_id']=$row->user_id;
							$detail_Arr['created_by']=$uid;

							$this->api_model->insert('st_booking_detail',$detail_Arr);
							

						}

						

			            $this->data['main']="";
						///mail section
						$this->data['main']= $this->api_model->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$bookid),'st_booking.id,business_name,book_id,booking_time,st_users.first_name as salon_name,st_booking.merchant_id,employee_id,st_booking.user_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select email from st_users where id=st_booking.user_id) as usemail,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
						if(!empty($this->data['main'])){

							$field="st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";  
							$whr=array('booking_id'=>$bookid);
							$this->data['booking_detail']= $this->api_model->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
							$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

							$salonDetails = $this->user->select_row('st_users', '*', array('id' => $this->data['main'][0]->merchant_id));
							$MsgTitle = "styletimer - Termin verschoben";
							$body_msg = 'Dein Termin bei '.$salonDetails->business_name.' wurde erfolgreich verschoben!';
							
							if($info->booking_type=='guest'){
								$email=$info->email;
								$this->data['booking_detail'][0]->first_name=$info->fullname;
							}
							else{
								if($this->data['main'][0]->user_notify != 0){
									sendPushNotification($this->data['main'][0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $bookid, 'booking_status' => 'reschedule' ,'click_action' => 'BOOKINGDETAIL'));
									}
									$email=$this->data['main'][0]->usemail;
								}	

							
							
													

							$message = $this->load->view('email/reshedule_booking_new',$this->data, true);
						    $mail = emailsend($email,$this->lang->line("styletimer_reschedule_booking"),$message,'styletimer');

							$empDat = is_mail_enable_for_user_action($this->data['main'][0]->employee_id);
							if ($empDat) {
								$tmp = $this->data;
								$tmp['main'][0]->salon_name = $empDat->first_name;
								$tmp['old_date'] = $oldDate;
								$message2 = $this->load->view('email/reshedule_booking_salon',$tmp, true);
								emailsend($empDat->email,$this->lang->line('styletimer_reschedule_booking'),$message2,'styletimer');
							}

						}

						$this->response_data['status'] = 1;
						$this->response_data['response_message']='Booking Reschedule successfully';
					}
					else{
						$this->response_data['status'] = 0;
						$this->response_data['response_message']='Sorry unable to process';
					}

				  }
				  else{
					$this->response_data['status'] = 0;
					$this->response_data['response_message']='Der Mitarbeiter ist im ausgewählten Zeitraum nicht verfügbar.';
				   } 		
			   }	
			    else{
			    	$this->response_data['status'] = 0;
					$this->response_data['response_message']='Der Mitarbeiter ist im ausgewählten Zeitraum nicht verfügbar.';
			    }
			

			}

			//date("h:i");
		}





				}
			}
		}
		echo json_encode($this->response_data);
	}


function resend_activation_mail_post(){
	    basicFromValidation();
		$this->form_validation->set_rules('identity', 'Email', 'required|trim');
		$this->form_validation->set_rules('api_key', 'Key', 'required|trim');
		//$this->form_validation->set_rules('access', 'Access', 'required|trim');


		
		if($this->form_validation->run() == FALSE)
		{
			  validationErrorMsg();
		}
		else
		{
		 	if(checkApikey($this->input->post('api_key')))
			{
			
			extract($_POST);
			$uid=null;
			$is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);
			
			if(empty($is_valid_user->access_token))
				{  
					$this->response_data['status'] = 0;
					$this->response_data['response_message'] = INVALID_TOKEN_MSG;
					// delete access token
					$this->api_model->access_token_delete($uid, $device_id);
				}
			else {

				// $user = $this->api_model->login($identity);
				$user=$this->api_model->getWhereRowSelect('st_users',array('email' => $identity),'id,activation_code,access,first_name,business_name,status');
				//print_r($user);
				 if($user)
				{  

				 	 if($user->status == 'inactive')
					 {
						if($user->access == 'user')
						{
							$tid = $user->id;
					    	$actv = $user->activation_code;
					    	$name = $user->first_name;
						// print_r($name);	
							$message = $this->load->view('email/user_activtion_link',array('link'=>base_url("auth/activate/$tid/$actv"), "name"=>ucwords($name),"button"=>"ACTIVATE ACCOUNT", "msg"=>"This message has been sent to you by StyleTimer. Click on the link below to Activate your account."), true);
							 $mail = emailsend($identity,'styletimer - Account aktivieren',$message,'styletimer');	
                                  //  $mail = $this->send_mail($identity,$message,'Styletimer - Account Activation','styletimer');
							if($mail==true){
								
								$this->response_data['status'] = '1';
								$this->response_data['success'] = $mail;
							$this->response_data['response_message'] = "Activation link send to email.";
							}else{
								//print_r($mail);die;
								$this->response_data['status'] = '0';
								$this->response_data['error'] = $mail;
							$this->response_data['response_message'] = "no send.";
							}
							
								
						}
						else if($user->access == 'employee'){
							$this->response_data['response_message'] = "As a employee you can not resend mail.";
						}
						else
							$this->response_data['response_message'] = "As a salon you can not resend mail.";

						
					}
					else
						$this->response_data['response_message'] = "Your account is ".$user->status;
				  
					
				 }
				 else
				 	$this->response_data['response_message'] = "Email id does not exist";
				 }
			
			}
			else
	         {
				$this->response_data['status'] = 0;
				$this->response_data['response_message'] = INVALID_API_KEY;
			 }// api key check 
			echo json_encode($this->response_data);
        }
	 }


public function pushtest_post()

    {

         $aps = array('alert'=>array('body'=>'Test notification message','title'=> 'Test notification message title','sound'=>'default'));

            $res = sendPushNotification($_POST['uid'],array('body'=>'test','title'=> 'test','aps'=>$aps));

            print_r($res);

    }

    public function send_mail($to_email) { 
	    
	// $config = array(
	// 	'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
	// 	'smtp_host' => 'smtp.example.com', 
	// 	'smtp_port' => 465,
	// 	'smtp_user' => 'no-reply@example.com',
	// 	'smtp_pass' => '12345!',
	// 	'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
	// 	'mailtype' => 'text', //plaintext 'text' mails or 'html'
	// 	'smtp_timeout' => '4', //in seconds
	// 	'charset' => 'iso-8859-1',
	// 	'wordwrap' => TRUE
	//  );
	$from_email = "ranjeetvit@gmail.com"; 
	//$to_email = $this->input->post('email'); 
    // print_r($to_email);
	//Load email library 
	$this->load->library('email'); 

	$this->email->from($from_email, 'Ranjeet kumar mahto'); 
	$this->email->to($to_email);
	$this->email->subject('Email Test'); 
	$this->email->message('Testing the email class.'); 
     //print_r($this->email->send());die;
	//Send mail 
	if($this->email->send()) 
	 return true;
	else 
	return $this->email->print_debugger();
  } 


}

