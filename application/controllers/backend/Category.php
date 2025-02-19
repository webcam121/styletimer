<?php
class Category extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_category'; 
	
	function __construct() {
		parent::__construct();
		$this->db->cache_off();
		$this->data['segment1'] = $this->uri->segment(2);
		$this->data['segment2'] = $this->uri->segment(3);
		$this->load->library('upload');
		$this->load->library('image_moo');
		if(empty($this->session->userdata('st_userid')) && $this->session->userdata('access')!='admin'){
		redirect(base_url('backend'));
		}
		// Redirect if user is not logged in as admin
		
		
		$this->is_not_logged_in_as_admin_redirect();
        
	}
	
	
	function index(){  
		
	   $this->listing();
	}
	
	/***
	 *  List all user according to his role
	 ***/
	function listing(){ 
			
		
		$this->data['category'] = $this->user->select($this->table,'id,category_name,parent_id,status,image',['status!='=>'deleted']);
		
		 // echo $this->db->last_query(); die();
		  // echo "<pre>"; print_r($this->data);die;
		   
		admin_views("/category/listing",$this->data);
		
	}
	
	/**
	 * Create or edit user 
	 ***/ 
	function make($id=''){ 
			  	
		if($id!=''){ //update
			 
			 $this->data['query_type'] = 'Update';
			 
			 $this->form_validation->set_rules('category_name', 'Category name', 'required');
			 $this->form_validation->set_rules('sorting_position', 'Sorting position', 'required');

			 if(!empty($_POST['parent_id'])){
			   $this->form_validation->set_rules('filter_category', 'Filter category', 'required');
			 }
			 if ($this->form_validation->run() === TRUE)
			 {     
				  
				  //print_r($_FILES); die;
				  extract($_POST);
				  $whrr=array('category_name' => $category_name, 'id !='=>$id,'status !='=>'deleted');
				  $ct=get_employeecount('st_category', $whrr);
				  if($ct > 0){
				  		 $this->session->set_flashdata('err_mesg', '<p>This category already added.</p>');
				  		redirect(admin_url($this->classname).'/make/'.$id);
				   		die;
				  }

				    if(!empty($_FILES['icon']['name'])){ 

							 $filename = explode('.',$_FILES["icon"]["name"]);
							 $_FILES['icon']['name'] = 'Icon_'.time().'.'.$filename[count($filename)-1];
                            
                            $fextention = $filename[count($filename)-1];
                          
							 if($fextention=='webp')
							 	$f_type = "*";
							 else 
							 	$f_type = "gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML|webp|WEBP";
							 
							array_insert($config, array('upload_path'=>"assets/uploads/category_icon/$id/", "allowed_types"=>''.$f_type.''));
							//gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML|webp
							array_map('unlink', glob($config['upload_path'].'*.*'));
							@mkdir($config['upload_path'] ,0777,TRUE);
							$this->upload->initialize($config);
							
							
							if (!$this->upload->do_upload('icon')){
                                 echo $this->upload->display_errors(); die;
								$this->session->set_flashdata('error',$this->upload->display_errors());
								redirect(admin_url("category/make/$id"));
							}
							else{
								$data = array('upload_data' => $this->upload->data());
								$image_info = $this->upload->data();
							
							$arr= getimagesize($config['upload_path'].$image_info['file_name']);
							//print_r($arr); die;
							if($arr[0]>=320){
								$widht=320;
								}
							else $widht=$arr[0];
							
							if($arr[1]>=205){
								$higt=205;
								}
							else $higt=$arr[1];
							
							$this->image_moo->load($config['upload_path'].$image_info['file_name'])->resize($widht,$higt)->save($config['upload_path'].'crop_'.$image_info['file_name'],true);
							
							$filepath2 = $config['upload_path'].'crop_'.$image_info['file_name'];
						    $filepath1 = $config['upload_path'].$image_info['file_name'];
							
						   if(strtolower($fextention)!='webp'){
								/*****************************************/
									$image1 = imagecreatefromstring(file_get_contents($filepath1));
									ob_start();
									imagejpeg($image1,NULL,100);
									$cont1 = ob_get_contents();
									ob_end_clean();
									imagedestroy($image1);
									$content1 = imagecreatefromstring($cont1);
										
								   $output1 = $config['upload_path'].$image_info['file_name'].'.webp';
								   
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
										
								   $output2 = $config['upload_path'].'crop_'.$image_info['file_name'].'.webp';
								   
								   imagewebp($content2,$output2);
								   imagedestroy($content2);
							
					
						 // $uploadPath = "assets/uploads/banners/{$uid}/webp";
						 // $uploadPath1 = "assets/uploads/banners/{$uid}/other";
						}
					else{
						   $content1 = imagecreatefromwebp($filepath1);
						   $output1 = $config['upload_path'].$image_info['file_name'].'.png';
							// Convert it to a jpeg file with 100% quality
							imagepng($content1, $output1);
							imagedestroy($content1);
						
						/*************************************************************/
						
						   $content2 = imagecreatefromwebp($filepath2);
						   $output2 = $config['upload_path'].'crop_'.$image_info['file_name'].'.png';
							// Convert it to a jpeg file with 100% quality
							imagepng($content2, $output2);
							imagedestroy($content2);
						
					 /*************************************************************/
					 
							
						}
												
							 $icon_imge=$image_info['file_name'];
							 $update = $this->user->update('st_category',array('icon'=>$icon_imge),['id'=>$id]);
							}
						}

             

				  $imge="";
				   if(!empty($_FILES['image']['name'])){ 

							 $filename = explode('.',$_FILES["image"]["name"]);
							 $_FILES['image']['name'] = 'Cat_'.time().'.'.$filename[count($filename)-1];
							 
							 $fextention = $filename[count($filename)-1];
							 
							if($filename[count($filename)-1] =='webp')
							 	$f_type = "*";
							 else 
							 	$f_type = "gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML|webp|WEBP";

							array_insert($config, array('upload_path'=>"assets/uploads/category/$id/", "allowed_types"=>''.$f_type.''));
							//gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML|webp
							array_map('unlink', glob($config['upload_path'].'*.*'));
							@mkdir($config['upload_path'] ,0777,TRUE);
							$this->upload->initialize($config);
							
							
							if (!$this->upload->do_upload('image')){
                                 echo $this->upload->display_errors(); die;
								$this->session->set_flashdata('error',$this->upload->display_errors());
								redirect(admin_url("category/make/$id"));
							}
							else{
								$data = array('upload_data' => $this->upload->data());
								$image_info = $this->upload->data();
								$arr= getimagesize($config['upload_path'].$image_info['file_name']);
							//print_r($arr); die;
								if($arr[0]>=320){
									$widht=320;
									}
								else $widht=$arr[0];
								
								if($arr[1]>=205){
									$higt=205;
									}
								else $higt=$arr[1];
								
								$this->image_moo->load($config['upload_path'].$image_info['file_name'])->resize($widht,$higt)->save($config['upload_path'].'crop_'.$image_info['file_name'],true);
								
							
							$filepath2 = $config['upload_path'].'crop_'.$image_info['file_name'];
						    $filepath1 = $config['upload_path'].$image_info['file_name'];
							
						   if(strtolower($fextention)!='webp'){
								/*****************************************/
									$image1 = imagecreatefromstring(file_get_contents($filepath1));
									ob_start();
									imagejpeg($image1,NULL,100);
									$cont1 = ob_get_contents();
									ob_end_clean();
									imagedestroy($image1);
									$content1 = imagecreatefromstring($cont1);
										
								   $output1 = $config['upload_path'].$image_info['file_name'].'.webp';
								   
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
										
								   $output2 = $config['upload_path'].'crop_'.$image_info['file_name'].'.webp';
								   
								   imagewebp($content2,$output2);
								   imagedestroy($content2);
							
									}
								else{
									   $content1 = imagecreatefromwebp($filepath1);
									   $output1 = $config['upload_path'].$image_info['file_name'].'.png';
										// Convert it to a jpeg file with 100% quality
										imagepng($content1, $output1);
										imagedestroy($content1);
									
									/*************************************************************/
									
									   $content2 = imagecreatefromwebp($filepath2);
									   $output2 = $config['upload_path'].'crop_'.$image_info['file_name'].'.png';
										// Convert it to a jpeg file with 100% quality
										imagepng($content2, $output2);
										imagedestroy($content2);
									
								 /*************************************************************/
								 
										
									}	
								
								
												
							 $imge=$image_info['file_name'];
							}
						}

			      if(empty($_POST['parent_id'])){
							$filter_category=0;
						 }	
						 
				  $showDropdown = 0;
				  if(!empty($show_on_dropdown)){
					  $showDropdown = 1;
					  }
				  		 
						 
				if(!empty($imge)){
					 $UpdateData = ['category_name'=>$category_name,'parent_id'=>$parent_id,'show_order'=>$sorting_position,'show_dropdown'=>$showDropdown,'status'=>$status,'image'=>$imge];
					}		
				 else{  
				  $UpdateData = ['category_name'=>$category_name,'filter_category'=>$filter_category,'show_order'=>$sorting_position,'parent_id'=>$parent_id,'show_dropdown'=>$showDropdown,'status'=>$status];
			      }
				  
				  $update = $this->user->update('st_category',$UpdateData,['id'=>$id]);
				  
				  if($update)   
				    $this->session->set_flashdata('message', 'Category updated Successfully.'); 
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing/'.$access);
	
			 }else
			 {    $this->data['message'] = validation_errors();
				  $this->data['category'] = $this->user->select_row($this->table,'*',['id'=>$id]);
				  $this->data['categories'] = $this->user->select($this->table,'id,category_name',['status!='=>'deleted','parent_id'=>0]);
				  $this->data['filter_category'] = $this->user->select('st_filter_category','id,category_name',['status!='=>'deleted']);
				   admin_views("/category/input",$this->data);
			 }
			 
			 			
		}else{ //add
			
			 $this->data['query_type'] = 'Add';
			 $this->form_validation->set_rules('category_name', 'Category name', 'required|trim');
			  $this->form_validation->set_rules('sorting_position', 'Sorting position', 'required');
			 if(!empty($_POST['parent_id'])){
			   $this->form_validation->set_rules('filter_category', 'Filter category', 'required');
			 }
			 		        
			        //~ print_r($_POST);
			        //~ echo var_dump($this->form_validation->run());
			        //~ print_r(validation_errors()); die;
			         
			 if ($this->form_validation->run() === TRUE)
			 {
				  extract($_POST);
				   $whrr=array('category_name' => $category_name,'status !='=>'deleted');
				  $ct=get_employeecount('st_category', $whrr);
				  if($ct > 0){
				  		 $this->session->set_flashdata('err_mesg', '<p>This category already added.</p>');
				  		redirect(admin_url($this->classname).'/make');
				   		die;
				  }
				  if(empty($_POST['parent_id'])){
                     $filter_category=0;
				  }
				  $showDropdown = 0;
				  if(!empty($show_on_dropdown)){
					  $showDropdown = 1;
					  }
				  
				  $InsertData = ['category_name'=>$category_name,'filter_category'=>$filter_category,'show_order'=>$sorting_position,'show_dropdown'=>$showDropdown,'parent_id'=>$parent_id,'status'=>$status];
				  
				  $insert = $this->user->insert($this->table,$InsertData);
				  
				  if($insert){   
					  
					    if(!empty($_FILES['icon']['name'])){ 

							 $filename = explode('.',$_FILES["icon"]["name"]);
							 $_FILES['icon']['name'] = 'Icon_'.time().'.'.$filename[count($filename)-1];
							 
							 $fextention = $filename[count($filename)-1];

							 if($filename[count($filename)-1] =='webp')
							 	$f_type = "*";
							 else 
							 	$f_type = "gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML|webp|WEBP";

							 
							array_insert($config, array('upload_path'=>"assets/uploads/category_icon/$insert/", "allowed_types"=>''.$f_type.''));
							//gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML|webp
							array_map('unlink', glob($config['upload_path'].'*.*'));
							@mkdir($config['upload_path'] ,0777,TRUE);
							$this->upload->initialize($config);
							
							
							if (!$this->upload->do_upload('icon')){
                                 echo $this->upload->display_errors(); die;
								$this->session->set_flashdata('error',$this->upload->display_errors());
								redirect(admin_url("category/make/$id"));
							}
							else{
								$data = array('upload_data' => $this->upload->data());
								$image_info = $this->upload->data();
								//print_r($image_info);
								
							$arr= getimagesize($config['upload_path'].$image_info['file_name']);
							//print_r($arr); die;
							if($arr[0]>=320){
								$widht=320;
								}
							else $widht=$arr[0];
							
							if($arr[1]>=205){
								$higt=205;
								}
							else $higt=$arr[1];
							
							$this->image_moo->load($config['upload_path'].$image_info['file_name'])->resize($widht,$higt)->save($config['upload_path'].'crop_'.$image_info['file_name'],true);
							
							    
							 $filepath2 = $config['upload_path'].'crop_'.$image_info['file_name'];
						    $filepath1 = $config['upload_path'].$image_info['file_name'];
							
						   if(strtolower($fextention)!='webp'){
								/*****************************************/
									$image1 = imagecreatefromstring(file_get_contents($filepath1));
									ob_start();
									imagejpeg($image1,NULL,100);
									$cont1 = ob_get_contents();
									ob_end_clean();
									imagedestroy($image1);
									$content1 = imagecreatefromstring($cont1);
										
								   $output1 = $config['upload_path'].$image_info['file_name'].'.webp';
								   
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
										
								   $output2 = $config['upload_path'].'crop_'.$image_info['file_name'].'.webp';
								   
								   imagewebp($content2,$output2);
								   imagedestroy($content2);
							
									}
								else{
								   $content1 = imagecreatefromwebp($filepath1);
								   $output1 = $config['upload_path'].$image_info['file_name'].'.png';
									// Convert it to a jpeg file with 100% quality
									imagepng($content1, $output1);
									imagedestroy($content1);
								
								/*************************************************************/
								
								   $content2 = imagecreatefromwebp($filepath2);
								   $output2 = $config['upload_path'].'crop_'.$image_info['file_name'].'.png';
									// Convert it to a jpeg file with 100% quality
									imagepng($content2, $output2);
									imagedestroy($content2);
								
							 /*************************************************************/
							 
									
								}	
								
												
							 $icon_imge=$image_info['file_name'];
							 $update = $this->user->update('st_category',array('icon'=>$icon_imge),['id'=>$insert]);
							}
						}
					  
					  if(!empty($_FILES['image']['name'])){ 

							 $filename = explode('.',$_FILES["image"]["name"]);
							 $_FILES['image']['name'] = 'Cat_'.time().'.'.$filename[count($filename)-1];
							 
							 $fextention = $filename[count($filename)-1];
							  
							 if($filename[count($filename)-1] =='webp')
							 	$f_type = "*";
							 else 
							 	$f_type = "gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML|webp|WEBP";

							 
							array_insert($config, array('upload_path'=>"assets/uploads/category/$insert/", "allowed_types"=>''.$f_type.''));
							//gif|jpg|jpeg|png|JPG|JPEG|PNG|webp
							array_map('unlink', glob($config['upload_path'].'*.*'));
							@mkdir($config['upload_path'] ,0777,TRUE);
							$this->upload->initialize($config);
							
							
							if (!$this->upload->do_upload('image')){

								$this->session->set_flashdata('error',$this->upload->display_errors());
								redirect(admin_url("{$this->classname}/make?id=$insert"));
							}
							else{
								$data = array('upload_data' => $this->upload->data());
								$image_info = $this->upload->data();
								
							$arr= getimagesize($config['upload_path'].$image_info['file_name']);
							//print_r($arr); die;
								if($arr[0]>=320){
									$widht=320;
									}
								else $widht=$arr[0];
								
								if($arr[1]>=205){
									$higt=205;
									}
								else $higt=$arr[1];
								
								$this->image_moo->load($config['upload_path'].$image_info['file_name'])->resize($widht,$higt)->save($config['upload_path'].'crop_'.$image_info['file_name'],true);
							    
							 $filepath2 = $config['upload_path'].'crop_'.$image_info['file_name'];
						    $filepath1 = $config['upload_path'].$image_info['file_name'];
							
						   if(strtolower($fextention)!='webp'){
								/*****************************************/
									$image1 = imagecreatefromstring(file_get_contents($filepath1));
									ob_start();
									imagejpeg($image1,NULL,100);
									$cont1 = ob_get_contents();
									ob_end_clean();
									imagedestroy($image1);
									$content1 = imagecreatefromstring($cont1);
										
								   $output1 = $config['upload_path'].$image_info['file_name'].'.webp';
								   
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
										
								   $output2 = $config['upload_path'].'crop_'.$image_info['file_name'].'.webp';
								   
								   imagewebp($content2,$output2);
								   imagedestroy($content2);
							
									}
								else{
								   $content1 = imagecreatefromwebp($filepath1);
								   $output1 = $config['upload_path'].$image_info['file_name'].'.png';
									// Convert it to a jpeg file with 100% quality
									imagepng($content1, $output1);
									imagedestroy($content1);
								
								/*************************************************************/
								
								   $content2 = imagecreatefromwebp($filepath2);
								   $output2 = $config['upload_path'].'crop_'.$image_info['file_name'].'.png';
									// Convert it to a jpeg file with 100% quality
									imagepng($content2, $output2);
									imagedestroy($content2);
								
							 /*************************************************************/
							 
									
								}	
								
							   
							    
												
								$this->db->where(array("id" => $insert));
								$this->db->update('st_category', array("image" => $image_info['file_name']));
								$this->session->set_flashdata('success',"Data Saved Successfully with image.");
							}
						}
					  
				    $this->session->set_flashdata('message', 'Category added Successfully.'); 
				   }
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing/');
				  
			 }else
			 {
				     //$this->data['message'] = validation_errors();
					 $this->data['category_name'] = $this->form_validation->set_value('category_name');
					 $this->data['categories'] = $this->user->select($this->table,'id,category_name',['status!='=>'deleted','parent_id'=>0]);

					 $this->data['filter_category'] = $this->user->select('st_filter_category','id,category_name',['status!='=>'deleted']);
					   //echo "<pre>"; print_r($this->data); die;
					  admin_views("/category/input",$this->data);
			 }
			
			
		}
		
	}
	
	/**
	 * Delete category 
	 ***/
	function delete($id=''){ 
		
		  if($id!=''){
			     
			     $update = $this->user->update($this->table,['status'=>'deleted'],['id'=>$id]);
			  
			     if($update)   
				    $this->session->set_flashdata('message', 'Category deleted Successfully.'); 
				 else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing/');
		  }else 
		     redirect(admin_url('404'));
	   
	              
	       	
	}
	
	
	
}
?>
