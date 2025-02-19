<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Frontend_Controller {
    
	function __construct() {
		parent::__construct();
	$data;
		if(empty($this->session->userdata('st_userid'))){
			redirect(base_url('auth/login'));
		 }
		 else{
		 	if($this->session->userdata('access')=='marchant'){

				$emp=get_employeecount('st_users',array('merchant_id' => $this->session->userdata('st_userid')));
		 			if(empty($emp)){
		 				$this->session->set_userdata(array('st_regMid'  => $this->session->userdata('st_userid')));
		 				redirect(base_url('merchant/addemployee'));
		 			}
		 		}
		 }

		//$this->load->model('Ion_auth_model','ion_auth');
	}

	public function edit_user_profile()
	{
		if(empty($this->session->userdata('st_userid'))){
			redirect(base_url('auth/login'));
		 }

		$field="id,first_name,last_name,address,email,mobile,address,zip,country,city,gender,profile_pic,latitude,longitude,newsletter";  

		if(isset($_POST['frmUpdate']))
        {
        	extract($_POST);
        	$insertArr=array();
        	$insertArr['first_name']=$first_name;
		  	$insertArr['last_name']=$last_name;
		  	$insertArr['mobile']=$telephone;
	      	$insertArr['address']=$location;
	      	$insertArr['country']=$country;
		  	$insertArr['city']=$city;
	      	$insertArr['zip']=$post_code;
	      	$insertArr['latitude']=$latitude;
            $insertArr['longitude']=$longitude;
	      	$insertArr['gender']=$gender;
			$insertArr['updated_on']=date('Y-m-d H:i:s');
        	
        	if($this->user->update('st_users',$insertArr,array('id'=>$this->session->userdata('st_userid')))){
        		$this->session->set_userdata('sty_fname',$first_name);
        		$this->session->set_flashdata('success','Profile updated successfully.');
        	}
			else{
				$this->session->set_flashdata('error','There is some technical error.');
			}

		redirect(base_url('profile/edit_user_profile'));
	 }


	   $this->data['userdetail']=$this->user->select_row('st_users',$field,array('id'=>$this->session->userdata('st_userid')));  
		$this->load->view('frontend/user/edit_profile',$this->data);
	}
	public function edit_employee_profile(){

		if(empty($this->session->userdata('st_userid'))){
			redirect(base_url('auth/login'));
		 }
		
		 if(isset($_POST['frmUpdate'])){
		 	extract($_POST);
		 	$insertUpd['first_name']=$first_name;
		  	$insertUpd['last_name']=$last_name;
		  	$insertUpd['mobile']=$telephone;

        	if($this->user->update('st_users',$insertUpd,array('id'=>$this->session->userdata('st_userid')))){
        		$this->session->set_userdata('sty_fname',$first_name);
        		$this->session->set_flashdata('success','Profile updated successfully.');
        	}
			else{
				$this->session->set_flashdata('error','There is some technical error.');
			}
			redirect(base_url('profile/edit_employee_profile'));
		 }
		$field="id,first_name,last_name,address,email,mobile,address,zip,country,city,gender,profile_pic,latitude,longitude";  
		 $this->data['userdetail']=$this->user->select_row('st_users',$field,array('id'=>$this->session->userdata('st_userid')));
		
		 
		
		$this->load->view('frontend/employee/edit_profile',$this->data);
	}
	public function edit_marchant_profile(){

		 if(empty($this->session->userdata('st_userid')) || $this->session->userdata('access')!='marchant'){
			   $this->session->set_flashdata('error','There is some technical error.');
			   redirect(base_url('auth/login'));
		 }
	  else{ 
		$field="st_users.id,first_name,last_name,business_name,business_type,address,email,mobile,address,zip,latitude,longitude,country,city,about_salon,online_booking,user_id,image,image1,image2,image3,image4";  
		$whr=array('st_users.id'=>$this->session->userdata('st_userid'));
	   $this->data['userdetail']= $this->user->join_two('st_users','st_banner_images','id','user_id',$whr,$field);
	  $this->data['user_available']=$this->user->select('st_availability','starttime,endtime',array('user_id'=>$this->session->userdata('st_userid')),'','id','ASC');  
	  //print_r($this->data); die;
	   $this->load->view('frontend/marchant/edit_profile',$this->data);	
	 
	  }
	}
	
 public function update_marchant_profile(){
	 if(empty($this->session->userdata('st_userid')) || $this->session->userdata('access')!='marchant'){
			   $this->session->set_flashdata('error','There is some technical error.');
			   redirect(base_url('auth/login'));
		 }
	 else{	 
	   if(isset($_POST['first_name'])){
	   	extract($_POST);
		 //print_r($_FILES); die;
           //print_r($_POST); die;
		    $days_array = array('monday','tuesday','wednesday','thursday','friday','saturday', 'sunday');
            
            $postdays=$_POST['days'];
            $poststart=$_POST['start'];
            $postend=$_POST['end'];

            $insertUpd=array();
        	$insertUpd['first_name']=$first_name;
		  	$insertUpd['last_name']=$last_name;
		  	$insertUpd['mobile']=$mobile;
	      	$insertUpd['address']=$address;
	      	$insertUpd['country']=$country;
		  	$insertUpd['city']=$city;
		  	$insertUpd['latitude']=$latitude;
            $insertUpd['longitude']=$longitude;
		  	$insertUpd['business_name']=$business_name;
		  	$insertUpd['business_type']=$business_type;
	      	$insertUpd['zip']=$zip;
	      	$insertUpd['online_booking']= isset($chk_online)?1:0;
	      	$insertUpd['about_salon']=$about;
			$insertUpd['updated_on']=date('Y-m-d H:i:s');

            $i=0;
            $mid=$this->session->userdata('st_userid');
            foreach($days_array as $day){
				 
				    $daydata=$this->user->select_row('st_availability','id',array('user_id'=>$this->session->userdata('st_userid'),'days'=>$day)); 
		           	  if(empty($daydata)){
						  if(in_array($day,$postdays)){
							  $insertArr=array();
							  $insertArr['user_id']=$this->session->userdata('st_userid');
							  $insertArr['days']=$day;
							  $insertArr['type']='open';
							  $insertArr['starttime']=$poststart[$i];
						      $insertArr['endtime']=$postend[$i];
						      $insertArr['created_on']=date('Y-m-d H:i:s');
						      $insertArr['created_by']=$this->session->userdata('st_userid');
							  }
						   else{
							  $insertArr=array();
							  $insertArr['user_id']=$this->session->userdata('st_userid');
							  $insertArr['days']=$day;
							  $insertArr['type']='close';
							  //$insertArr['starttime']=$poststart[$i];
						     // $insertArr['endtime']=$postend[$i];
						      $insertArr['created_on']=date('Y-m-d H:i:s');
						      $insertArr['created_by']=$this->session->userdata('st_userid');
							   
							  }	  
						  $this->user->insert('st_availability',$insertArr);
						  } 
						 else{
						 if(in_array($day,$postdays)){
							  $updateArr=array();
							  $updateArr['user_id']=$this->session->userdata('st_userid');
							  $updateArr['days']=$day;
							  $updateArr['type']='open';
							  $updateArr['starttime']=$poststart[$i];
						      $updateArr['endtime']=$postend[$i];
						      //$updateArr['created_on']=date('Y-m-d H:i:s');
						      //$updateArr['created_by']=$this->session->userdata('st_userid');
							  $employee=$this->user->select('st_users','id',array('merchant_id'=>$mid),'','id','ASC');	

							  if(!empty($employee)){
							  	foreach($employee as $emp){
							  		$emp_time=$this->user->select_row('st_availability','id,user_id,starttime,endtime,starttime_two,endtime_two',array('user_id'=>$emp->id,'days'=>$day));
							  		//print_r($emp_time);

							  			if(!empty($emp_time)){
							  				$updatetime=array();
							  				$emp_start=$emp_time->starttime;
							  				$emp_end=$emp_time->endtime;
							  				$emp_stwo=$emp_time->starttime_two;
							  				$emp_etwo=$emp_time->endtime_two;	
							  			
							  			if(($poststart[$i] >= $emp_start) && ($poststart[$i] >= $emp_end))
							  			{
							  				$updatetime['starttime']="";
							  				$updatetime['endtime']="";
							  				$updatetime['starttime_two']="";
							  				$updatetime['endtime_two']="";
							  			}
							  			else{	
							  				if($poststart[$i] > $emp_start)
							  					$updatetime['starttime']=$poststart[$i];

							  				if($postend[$i] < $emp_end)
							  					$updatetime['endtime']=$postend[$i];
							  				else if($postend[$i] > $emp_stwo && $postend[$i] < $emp_etwo)
							  					$updatetime['endtime_two']=$postend[$i];
							  				
							  				if($postend[$i] <= $emp_stwo){
							  					  $updatetime['starttime_two']="";
							  					  $updatetime['endtime_two']="";
							  					}
							  			}
							  			  $this->user->update('st_availability',$updatetime,array('user_id'=>$emp_time->user_id,'days'=>$day));	

							  			}

							  	 }
							  	
							  }						 


							  }
						   else{
							  $updateArr=array();
							  $updateArr['user_id']=$this->session->userdata('st_userid');
							  $updateArr['days']=$day;
							  $updateArr['type']='close';
							  $updateArr['starttime']="";
						      $updateArr['endtime']="";
						     // $updateArr['created_on']=date('Y-m-d H:i:s');
						      //$updateArr['created_by']=$this->session->userdata('st_userid');
							   
							  }
							 
							 $this->user->update('st_availability',$updateArr,array('user_id'=>$this->session->userdata('st_userid'),'days'=>$day));
							 
							 }   
						$i++;
					 }
					
				unset($_POST['days']);
				unset($_POST['start']);
				unset($_POST['end']);
				
		     $banner=array();
		     $uid = $this->session->userdata('st_userid');
			 $path = 'assets/uploads/banners/'.$uid.'/';
			 if(!is_dir($path)){ @mkdir($path ,0777,TRUE);} 

			   if(isset($_FILES['pimage']['name']) && !empty($_FILES['pimage']['name'])){
					 /*'./assets/uploads/banners'*/
					$config = ['upload_path'=>$path, 'allowed_types'=> 'gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML','max_size' => 20480,'max_width' => 10240, 'max_height' => 7680 ];
					
					$new_name = 'banner_'.time().'.'.pathinfo($_FILES['pimage']['name'],PATHINFO_EXTENSION);
					$config['file_name'] = $new_name;
						
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					 if ( ! $this->upload->do_upload('pimage'))
						{  //echo  $this->upload->display_errors(); die;
						 
						  $validate = true; $msg['pimage'] = $this->upload->display_errors();
						  //echo json_encode(['success'=>0,'error'=>$msg]); die; 
						 
						}
						else
						{  //echo "succes"; die;
								$data = array('upload_data' => $this->upload->data());
							  	if (file_exists($path.$_POST['old_banner']))  
								{ unlink($path.$_POST['old_banner']); } 
								 $banner['image'] = $new_name;				    
								
						}
			}

			 if(isset($_FILES['pimage1']['name']) && !empty($_FILES['pimage1']['name'])){
					 
					$config = ['upload_path'=>$path, 'allowed_types'=> 'gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML','max_size' => 20480,'max_width' => 10240, 'max_height' => 7680 ];
					
					$new_name = 'banner1_'.time().'.'.pathinfo($_FILES['pimage1']['name'],PATHINFO_EXTENSION);
					$config['file_name'] = $new_name;
						
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					 if ( ! $this->upload->do_upload('pimage1'))
						{  //echo  $this->upload->display_errors(); die;
						 
						  $validate = true; $msg['pimage1'] = $this->upload->display_errors();
						  //echo json_encode(['success'=>0,'error'=>$msg]); die; 
						 
						}
						else
						{  //echo "succes"; die;
								$data = array('upload_data' => $this->upload->data());
							  	if (file_exists($path.$_POST['old_image1']))  
								{ unlink($path.$_POST['old_image1']); }
								 $banner['image1'] = $new_name;				    
								
						}
			}
			
			if(isset($_FILES['pimage2']['name']) && !empty($_FILES['pimage2']['name'])){
					 
					$config = ['upload_path'=>$path, 'allowed_types'=> 'gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML','max_size' => 20480,'max_width' => 10240, 'max_height' => 7680 ];
					
					$new_name = 'banner2_'.time().'.'.pathinfo($_FILES['pimage2']['name'],PATHINFO_EXTENSION);
					$config['file_name'] = $new_name;
						
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					 if ( ! $this->upload->do_upload('pimage2'))
						{  //echo  $this->upload->display_errors(); die;
						 
						  $validate = true; $msg['pimage2'] = $this->upload->display_errors();
						  //echo json_encode(['success'=>0,'error'=>$msg]); die; 
						 
						}
						else
						{  //echo "succes"; die;
								$data = array('upload_data' => $this->upload->data());
							  	if (file_exists($path.$_POST['old_image2']))  
								{ unlink($path.$_POST['old_image2']); }
								 $banner['image2'] = $new_name;				    
								
						}
			}
			
			if(isset($_FILES['pimage3']['name']) && !empty($_FILES['pimage3']['name'])){
					 
					$config = ['upload_path'=>$path, 'allowed_types'=> 'gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML','max_size' => 20480,'max_width' => 10240, 'max_height' => 7680 ];
					
					$new_name = 'banner3_'.time().'.'.pathinfo($_FILES['pimage3']['name'],PATHINFO_EXTENSION);
					$config['file_name'] = $new_name;
						
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					 if ( ! $this->upload->do_upload('pimage3'))
						{  //echo  $this->upload->display_errors(); die;
						 
						  $validate = true; $msg['pimage3'] = $this->upload->display_errors();
						  //echo json_encode(['success'=>0,'error'=>$msg]); die; 
						 
						}
						else
						{  //echo "succes"; die;
								$data = array('upload_data' => $this->upload->data());
							  	if (file_exists($path.$_POST['old_image3']))  
								{ unlink($path.$_POST['old_image3']); }
								 $banner['image3'] = $new_name;				    
								
						}
			}		
			
			if(isset($_FILES['pimage4']['name']) && !empty($_FILES['pimage4']['name'])){
					 
					$config = ['upload_path'=>$path, 'allowed_types'=> 'gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML','max_size' => 20480,'max_width' => 10240, 'max_height' => 7680 ];
					
					$new_name = 'banner4_'.time().'.'.pathinfo($_FILES['pimage4']['name'],PATHINFO_EXTENSION);
					$config['file_name'] = $new_name;
						
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					 if ( ! $this->upload->do_upload('pimage4'))
						{  //echo  $this->upload->display_errors(); die;
						 
						  $validate = true; $msg['pimage4'] = $this->upload->display_errors();
						  //echo json_encode(['success'=>0,'error'=>$msg]); die; 
						 
						}
						else
						{  //echo "succes"; die;
								$data = array('upload_data' => $this->upload->data());
							  	if (file_exists($path.$_POST['old_image4']))  
								{ unlink($path.$_POST['old_image4']); }
								 $banner['image4'] = $new_name;				    
								
						}
			}
			if(!empty($banner)){
				$banerdata=$this->user->select_row('st_banner_images','id',array('user_id'=>$this->session->userdata('st_userid')));
				
				if(empty($banerdata)){
					$banner['user_id']=$this->session->userdata('st_userid');
					$banner['created_on']=date("Y-m-d H:i:s");
					$banner['created_by']=$this->session->userdata('st_userid');
					$this->user->insert('st_banner_images',$banner);
					
					}else{
						$banner['created_on']=date("Y-m-d H:i:s");
				     	$banner['created_by']=$this->session->userdata('st_userid');
						$this->user->update('st_banner_images',$banner,array('user_id'=>$this->session->userdata('st_userid')));
						
						} 
				
				}		
			    unset($_POST['pimage']);
				unset($_POST['pimage1']);
				unset($_POST['pimage2']);	
				unset($_POST['pimage3']);	
				unset($_POST['pimage4']);	
						
			$res=$this->user->update('st_users', $insertUpd,array('id'=>$this->session->userdata('st_userid')));
			//die;
			if($res){
				
				$this->session->set_flashdata('success','Profile updated successfully.');
				redirect(base_url('profile/edit_marchant_profile'));
					//echo json_encode(['success'=>'1','message'=>'Profile updated successfully.']); die;
				}
			else{
				$this->session->set_flashdata('error','There is some technical error.');
				redirect(base_url('profile/edit_marchant_profile'));
				//echo json_encode(['success'=>'0','message'=>'There is some technical error.']); die;
				}	
			//print_r($_POST);
			
		 } 
	   }
	 }	
public function clientview($id=''){

		 if(empty($this->session->userdata('st_userid')) || $this->session->userdata('access')!='marchant'){
			   $this->session->set_flashdata('error','There is some technical error.');
			   redirect(base_url('auth/login'));
		 }
	  else{ 
		if(!empty($id))
		  {
            $cid=url_decode($id);
            $mid=$this->session->userdata('st_userid');
            
            $query="SELECT st_users.id,first_name,last_name,st_users.gender,profile_pic,st_users.email,mobile,address,zip,country,city,(select notes from st_usernotes WHERE user_id=st_users.id and created_by = ".$mid.") as notes,count(st_booking.id) as totalbook,(SELECT count(id) FROM st_booking WHERE user_id=".$cid." AND merchant_id=".$mid." AND status='completed') as totalcomplete,(SELECT SUM(total_price) FROM st_booking WHERE user_id=".$cid." AND merchant_id=".$mid." AND status='completed') as totalrevenew,(SELECT count(id) FROM st_booking WHERE user_id=".$cid." AND merchant_id=".$mid." AND status='cancelled') as totalcanceled,(SELECT count(id) FROM st_booking WHERE user_id=".$cid." AND merchant_id=".$mid." AND status='no show') as totalnoshow,(SELECT count(id) FROM st_booking WHERE user_id=".$cid." AND merchant_id=".$mid." AND status='confirmed') as totalupcoming FROM st_users LEFT JOIN st_booking ON user_id=st_users.id AND st_booking.merchant_id=".$mid." WHERE st_users.id=".$cid." ORDER BY booking_time ASC";
            
            $this->data['userdata']=$this->user->custome_query($query,'row');
            
            $blockQuery="SELECT id FROM st_client_block WHERE client_id=".$cid." AND merchant_id=".$mid;
            $this->data['blockclient']=$this->user->custome_query($blockQuery,'row');
            
	       // echo "<pre>"; print_r($this->data); die;
	        $this->load->view('frontend/marchant/client_profile_view',$this->data);	
         }
	   else redirect(base_url('merchant/customers'));
	     
	  }
	}
	
public function client_booking_list($id='',$page='0'){
 
		if(!empty($id))
		  {
            $cid=url_decode($id);
            $mid=$this->session->userdata('st_userid');
            
           $where=array('user_id'=>$cid,'st_booking.merchant_id'=>$mid);
           if(isset($_POST['order']))
           	  $order=$_POST['order'];
           else
              $order="";
		 $totalcount = $this->user->getbookinglist($where,0,0,'employee_id',$order);
		 if(!empty($totalcount))
		 	$total=count($totalcount);
		 else
		 	$total=0;

		 $limit = isset($_POST['limit'])?$_POST['limit']:20;	//PER_PAGE10
		 $url = 'profile/client_booking_list/'.$id;
		 $segment = 4;    
		// $page = mypaging($url,$total,$segment,$limit);
		$offset=0;
		 if($page != 0){
			  $offset = ($page-1) * $limit;
			}
		$config = array();
	   $config["base_url"] = base_url().$url;
	   $config["total_rows"] = $total;
	   $config["per_page"] =$limit;
	   $config['use_page_numbers'] = TRUE;
	   $config['num_links'] =   2;
	   $config['num_tag_open'] = '<li class="page-item">';
       $config['num_tag_close'] = '</li>';
	   $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
	   $config['cur_tag_close'] = '</a></li>';
	   $config['first_tag_open'] = '<li class="page-item">';
	   $config['first_tag_close'] = '</li>';
	   $config['next_link'] = '&gt;';
	   $config['prev_link'] = '&lt;';
	   $config['first_link'] = '&laquo;';
	   $config['last_link'] = '&raquo;';
	   $config['last_tag_open'] = '<li class="page-item">';
	   $config['last_tag_close'] = '</li>';
	   $config['next_tag_open'] = '<li class="page-item">';
	   $config['next_tag_close'] = '</li>';
	   $config['prev_tag_open'] = '<li class="page-item">';
	   $config['prev_tag_close'] = '</li>';
	   $config["uri_segment"] = $segment;
		
		 $this->pagination->initialize($config);
		 
	     $pagination=$this->pagination->create_links();
	     
		 $booking=$this->user->getbookinglist($where,$config["per_page"],$offset,'employee_id',$order);
		//echo '<pre>'; print_r($booking); die;
		 $html="";
		 if(!empty($booking)){
			 foreach($booking as $row){
				$sevices= get_servicename($row->id); 
				$cls=''; if($row->status =='confirmed')
				             {
                      				$cls='conform';
                      				$detalClass="";
                      				$txtDacoration="text-decoration: none !important;";
                      				$bk_class="booking_row";
                      				$recp='';
                      		}
                      	 else if($row->status =='cancelled')
                      	      {
                      		   		$cls='cencel';
                      		   		$detalClass="";
                      		   		$txtDacoration="text-decoration: none !important;";
                      		   		$bk_class="booking_row";
                      		   		$recp='';
							 }
                       else if($row->status =='completed')
                      		  { 
                      		   		$cls='completed';
                      		   		$detalClass="bookDetailShow";
                      		   		$txtDacoration="cursor:pointer;";
                      		   		$bk_class="";
                      		   		$recp='Receipt';
							 }
                       else if($row->status =='no show')
                             { 
                                $cls='cencel'; 
                               $detalClass="";
                               $txtDacoration="text-decoration: none !important;";
                               $bk_class="booking_row";
                               $recp='';
						   }
				
				 $html=$html.'<tr>   
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.date("d/m/Y",strtotime($row->booking_time)).'</td>
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.date("H:i",strtotime($row->booking_time)).'</td>                   
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">'.$row->id.'</td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular booking_row" id="'.url_encode($row->id).'"><p class=" overflow_elips vertical-meddile mb-0 display-ib" style="width: 200px;">'.$sevices.'</p></td>
                      <td class="text-center height56v font-size-14 color666 fontfamily-regular booking_row" id="'.url_encode($row->id).'">'.$row->total_minutes.' Mins</td>
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center" id="'.url_encode($row->id).'">'.$row->total_price.' €</td>
                      <td class="text-center height56v booking_row" id="'.url_encode($row->id).'">
                        <span href="#" class="'.$cls.' font-size-14 fontfamily-regular a_hover_red">'.$row->status.'</span>
                      </td>  
                      <td class="text-center"><a style="'.$txtDacoration.'" data-duration="'.$row->total_minutes.' Mins" data-price="'.$row->total_price.' €" data-id="'.$row->id.'" data-time="'.date("d F Y, H : m",strtotime($row->booking_time)).'" data-complete ="'.date("d F Y, H : m",strtotime($row->updated_on)).'" data-salone="'.$row->business_name.'"  class="text-underline '.$detalClass.' color333">'.$recp.'</a></td>                    
                    </tr>';
				 
				 
				 }
			 
			 }
		else{
			$html='<tr><td colspan="7" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="'.base_url('assets/frontend/images/no_listing.png').'"><p style="margin-top: 20px;"> Whoops! Looks like there is no data.</p></div></td></tr>';
			
			}	 
			
		  echo json_encode(array('success'=>'1','msg'=>'','html'=>$html,'pagination'=>$pagination));	die;
	        //echo "<pre>"; print_r($booking); die;
	       // $this->load->view('frontend/marchant/client_profile_view',$this->data);	
         }  else echo json_encode(array('success'=>'0','msg'=>'','html'=>'','pagination'=>'')); die;
	   
	     
	}	
 
 public function client_block(){
	        $cid=url_decode($_POST['uid']);
	        $mid=$this->session->userdata('st_userid');
	        $blockQuery="SELECT id FROM st_client_block WHERE client_id=".$cid." AND merchant_id=".$mid;
            $blockCheck=$this->user->custome_query($blockQuery,'row');
            if(!empty($blockCheck->id)){
				$delete=$this->db->query("DELETE FROM st_client_block WHERE client_id=".$cid." AND merchant_id=".$mid);
				 echo json_encode(array('success'=>'1','text'=>'Block'));	die;
				}
			else{
				$data=array();
				$data['client_id']=$cid;
				$data['merchant_id']=$mid;
				$data['created_on']=date('Y-m-d H:i:s');
				$data['created_by']=$mid;
				$insert=$this->db->insert('st_client_block',$data);
				 echo json_encode(array('success'=>'1','text'=>'Unblock'));	die;
				}	
	 
	 
	 } 
	 
public function update_notes(){
	
		 $upd['notes']=$_POST['notes'];
		 $mid = $this->session->userdata('st_userid');
		 if($this->user->countResult('st_usernotes',array('user_id' =>  $_POST['uid'],'created_by' => $mid)) > 0){
		 	$this->user->update('st_usernotes',$upd,array('user_id'=>$_POST['uid'],'created_by' => $mid));
		 }
		 else{
		 	$insert_note['user_id']= $_POST['uid'];
		 	$insert_note['notes']= $_POST['notes'];
		 	$insert_note['created_by']=$mid;
		 	$insert_note['created_on']= date('Y-m-d H:i:s');
		 	$this->user->insert('st_usernotes',$insert_note);
		 }
		//$this->user->update('st_users',array('notes'=>$_POST['notes']),array('id'=>$_POST['uid']));
	  	echo json_encode(array('success'=>'1','msg'=>''));	die;
	}	 
	 
}
