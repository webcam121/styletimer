<?php
class Faq extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_faq'; 
	
	function __construct() {
		parent::__construct();
		$this->db->cache_off();
		$this->data['segment1'] = $this->uri->segment(2);
		$this->data['segment2'] = $this->uri->segment(3);
		$this->load->library('upload');
		$this->load->library('image_moo');
		// Redirect if user is not logged in as admin
		$this->is_not_logged_in_as_admin_redirect();
		if(empty($this->session->userdata('st_userid')) && $this->session->userdata('access')!='admin'){
		redirect(base_url('backend'));
		}
        
	}
	
	
	function index(){  
		
	   $this->listing();
	}
	
	/***
	 *  List all user according to his role
	 ***/
	function listing(){ 
			
		
		$this->data['faq'] = $this->user->select($this->table,'id,question,status,(SELECT name FROM st_faq_category WHERE id=cat_id) as cat_name',['status!='=>'deleted']);
		
		 // echo $this->db->last_query(); die();
		  // echo "<pre>"; print_r($this->data);die;
		   
		admin_views("/faq/listing",$this->data);
		
	}
	function make($id=''){
			
		 $this->form_validation->set_rules('answer', 'Answer', 'required');
		 $this->form_validation->set_rules('question', 'Question', 'required');

			 if($this->form_validation->run() === TRUE)
			 {   
			  if(!empty($id)){
					 $update = $this->user->update('st_faq',array('question'=>$_POST['question'],'answer'=>$_POST['answer'],'cat_id'=>$_POST['category']),['id'=>$id]);
					 if($update)   
						$this->session->set_flashdata('message', 'FAQ updated Successfully.'); 
					  else  
						$this->session->set_flashdata('error', 'Something went wrong.');   
					 
				 }
				else{


					 $update = $this->user->insert('st_faq',array('question'=>$_POST['question'],'answer'=>$_POST['answer'],'cat_id'=>$_POST['category']));


			 	   if($update)   
				      $this->session->set_flashdata('message', 'FAQ added Successfully.'); 
				   else  
				      $this->session->set_flashdata('error', 'Something went wrong.');   
					
					} 
				  redirect(admin_url($this->classname).'/listing');
				  
			 }
			 else{
			 	 $this->data['message'] = validation_errors();
			 }
		$this->data['query_type']='Add';	 
		 if($id)
		   {
			$this->data['query_type']='Edit';
		    $this->data['faq'] = $this->user->select_row($this->table,'id,question,answer,cat_id,status',['id' => $id]);
		  }
		  $this->data['categories'] = $this->user->select('st_faq_category','id,name',['status!='=>'deleted']);

			admin_views("/faq/input",$this->data);

	}
	/**
	 * Delete user 
	 ***/
	function delete($id=''){ 
		
		  if($id!=''){
			     
			     $update = $this->user->update($this->table,['status'=>'deleted'],['id'=>$id]);
			  
			     if($update)   
				    $this->session->set_flashdata('message', 'Faq deleted Successfully.'); 
				 else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/listing/');
		  }else 
		     redirect(admin_url('404'));
	   
	              
	       	
	}

	function category($id=''){ 
			  	
		if($id!=''){ //update
			 
			 $this->data['query_type'] = 'Update';
			 
			 $this->form_validation->set_rules('category_name', 'category name', 'required');
			 //$this->form_validation->set_rules('hospital_id', 'Hospital id', 'required');
			 
			 if ($this->form_validation->run() === TRUE)
			 {     
				  
				  extract($_POST);
				    
				  $UpdateData = ['name'=>$category_name,'status'=>$status];
				  
				  $update = $this->user->update('st_faq_category',$UpdateData,['id'=>$id]);
				  
				  if($update)   
				    $this->session->set_flashdata('message', 'Category updated Successfully.'); 
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/category_listing');
	
			 }else
			 {    $this->data['message'] = validation_errors();
				  $this->data['user'] = $this->user->select_row('st_faq_category','*',['id'=>$id]);
				   admin_views("/faq/category",$this->data);
			      
			 } 
			 
			 			
		}else{ //add
			
			 $this->data['query_type'] = 'Add';
			
			 //$this->form_validation->set_rules('first_name', 'First name', 'required');
			 $this->form_validation->set_rules('category_name', 'category name', 'required|callback_uniqe_category_check');
			        
			       // print_r($_POST); die;
			        //~ echo var_dump($this->form_validation->run());
			        //~ print_r(validation_errors()); die;
			         
			 if ($this->form_validation->run() === TRUE)
			 {
				  extract($_POST);
				  
				  $InsertData = ['name'=>$category_name,'status'=>$status];
				  
				  $insert = $this->user->insert('st_faq_category',$InsertData);
				 // echo $this->db->last_query(); die;
				  
				  if($insert){ 
					  $this->session->set_flashdata('message', 'Category added Successfully.'); 
				    }
				  else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(admin_url($this->classname).'/category_listing');
				  
			 }else
			 {
				     //$this->data['message'] = validation_errors();
					 $this->data['category_name'] = $this->form_validation->set_value('category_name');
					 $this->data['status'] = $this->form_validation->set_value('status');
					 
					 //echo "<pre>"; print_r($this->data); die;
					 admin_views("/faq/category",$this->data);
			 }
			
			
		}
		
	}

	function category_listing(){ 
			
		$this->data['faq'] = $this->user->select('st_faq_category','id,name,status',['status!='=>'deleted']);
		admin_views("/faq/category_listing",$this->data);
		
	}

	function uniqe_category_check($cat)
    {
        $users=$this->user->select_row('st_faq_category','id',['name'=>$cat,'status !='=>'deleted']);  

        if(empty($users)) return true;
        else{ $this->form_validation->set_message('uniqe_category_check', 'This category already exist.');
			  return false;
			  }
      //  print_r($users); die;      
          
    }

    function deletecategory($id=''){ 
		
		  if($id!=''){
			     
			     $update = $this->user->update('st_faq_category',['status'=>'deleted'],['id'=>$id]);
			  
			     if($update)   
				    $this->session->set_flashdata('message', 'Category deleted Successfully.'); 
				 else  
				    $this->session->set_flashdata('error', 'Something went wrong.');   
				 
				  redirect(strtolower(admin_url($this->classname)).'/category_listing');
		  }else 
		     redirect(admin_url('404'));
	   
	              
	       	
	}

	function upload(){
		/*print_r($_POST);
		print_r($_FILES);
		echo $_FILES['file']['name'];
		die;*/
		if(!empty($_FILES['file']['name'])){ 

			$filename = explode('.',$_FILES["file"]["name"]);
			//$_FILES['file']['name'] = 'faq_'.time().'.'.$filename[count($filename)-1];
		  	$config['upload_path'] = 'assets/uploads/faq/';
	        $config['allowed_types'] = 'gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML';
	        //$config['max_size'] = 2000;
       		//$config['max_width'] = 1500;
        	//$config['max_height'] = 1500;
	        $new_name = 'faq_'.time().'.'.$filename[count($filename)-1];
			$config['file_name'] = $new_name;
	        @mkdir($config['upload_path'] ,0777,TRUE);
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('file')){
                 $err = $this->upload->display_errors(); 
                 echo json_encode(array('success'=>1, 'error' =>$err));
   			}else{


				 $image=base_url().$config['upload_path'].$new_name;

				 echo json_encode(array('success'=>1, 'link' =>$image));
			}


       		// $this->load->library('upload', $config);
       }

		/*if(!empty($_FILES['file']['name'])){ 

							 $filename = explode('.',$_FILES["file"]["name"]);
							 $_FILES['file']['name'] = 'faq_'.time().'.'.$filename[count($filename)-1];
							 
							array_insert($config, array('upload_path'=>"assets/uploads/faq/", "allowed_types"=>'gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML'));
							


							@mkdir($config['upload_path'] ,0777,TRUE);
							$this->upload->initialize($config);
							
							
							if (!$this->upload->do_upload('file')){
                                 echo $this->upload->display_errors(); die;
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
												
							 $image=base_url().$config['upload_path'].$image_info['file_name'];

							 echo json_encode(array('success'=>1, 'link' =>$image));
							}
						}*/

	}

	function upload_video(){
    	if(!empty($_FILES['file']['name'])){ 

			$filename = explode('.',$_FILES["file"]["name"]);
			//$_FILES['file']['name'] = 'faq_'.time().'.'.$filename[count($filename)-1];
		  	$config['upload_path'] = 'assets/uploads/faq/';
	        $config['allowed_types'] = 'mp4|3gp|flv|mp3';
	        $config['max_size'] = 10240;
       		//$config['max_width'] = 1500;
        	//$config['max_height'] = 1500;
	        $new_name = 'faq_vid'.time().'.'.$filename[count($filename)-1];
			$config['file_name'] = $new_name;
	        @mkdir($config['upload_path'] ,0777,TRUE);
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('file')){
                 $err = $this->upload->display_errors(); 
                 echo $err; 
                 //echo json_encode(array('success'=>0, 'message' =>$err));
   			}else{


				 $image=base_url().$config['upload_path'].$new_name;

				 echo json_encode(array('success'=>1, 'link' =>$image));
			}


       		// $this->load->library('upload', $config);
       }
	}

function upload_file(){
		/*print_r($_POST);
		print_r($_FILES);
		echo $_FILES['file']['name'];
		die;*/
		if(!empty($_FILES['file']['name'])){ 

			$filename = explode('.',$_FILES["file"]["name"]);
			//$_FILES['file']['name'] = 'faq_'.time().'.'.$filename[count($filename)-1];
		  	$config['upload_path'] = 'assets/uploads/faq/';
	        $config['allowed_types'] = '*';
	        //$config['max_size'] = 2000;
       		//$config['max_width'] = 1500;
        	//$config['max_height'] = 1500;
	        $new_name = 'faq_'.time().'.'.$filename[count($filename)-1];
			$config['file_name'] = $new_name;
	        @mkdir($config['upload_path'] ,0777,TRUE);
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('file')){
                 $err = $this->upload->display_errors(); 
                 echo json_encode(array('success'=>1, 'error' =>$err));
   			}else{


				 $image=base_url().$config['upload_path'].$new_name;

				 echo json_encode(array('success'=>1, 'link' =>$image));
			}


       		// $this->load->library('upload', $config);
       }

	}

	function remove_file(){
		
	  	if(!empty($_POST['path'])){
	  		$dd = (explode("/",$_POST['path']));
	  		$lng =count($dd);
	  		$path = 'assets/uploads/faq/'.$dd[$lng-1];
	  	}
	  	else
	  		$path ="";

		if(file_exists($path)){
		    unlink($path);
		}

		
	}


}


