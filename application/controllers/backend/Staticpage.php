<?php
class Staticpage extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	public $table = 'st_faq'; 
	
	function __construct() {
		parent::__construct();
		$this->db->cache_off();
		$this->data['segment1'] = $this->uri->segment(2);
		$this->data['segment2'] = $this->uri->segment(3);
		$this->data['segment3'] = $this->uri->segment(4);
		$this->load->library('upload');
		$this->load->library('image_moo');
		// Redirect if user is not logged in as admin
		$this->is_not_logged_in_as_admin_redirect();
		if(empty($this->session->userdata('st_userid')) && $this->session->userdata('access')!='admin'){
		redirect(base_url('backend'));
		}
        
	}
	
	
	/*function index(){  
		
	   $this->listing();
	}*/
	
	function termsandcondition($types=""){ 
		if($types!=""){
			if($types == "user")
				$type="userterms";
			else
				$type="salonterms";

		$this->data['terms']=$this->user->select_row('st_static_page','*',array('type'=>$type));
	
		if(isset($_POST['description'])){
			$Insert_Data['type']= $type;
			$Insert_Data['title']= $_POST['title_name'];
			$Insert_Data['text']= $_POST['description'];
			$Insert_Data['created_on']= date('Y-m-d H:i:s');
			if($_POST['edit_check'] == 0){
				$this->user->insert('st_static_page', $Insert_Data);
					redirect(admin_url().'staticpage/termsandcondition/'.$types);
			}
			else{
				$this->user->update('st_static_page',array('title' => $_POST['title_name'],'text' => $_POST['description']),array('type' => $type));
				redirect(admin_url().'staticpage/termsandcondition/'.$types);
			}
		}
		admin_views("static/terms_condition",$this->data);
		}
		else
			redirect(admin_url('404'));
	}



	function privacypolicy($types=""){ 
		if($types!=""){
			if($types == "user")
				$type="userpolicy";
			else
				$type="salonpolicy";

		$this->data['terms']=$this->user->select_row('st_static_page','*',array('type'=>$type));
		if(isset($_POST['description'])){
			$Insert_Data['type']= $type;
			$Insert_Data['title']= $_POST['title_name'];
			$Insert_Data['text']= $_POST['description'];
			$Insert_Data['created_on']= date('Y-m-d H:i:s');
			if($_POST['edit_check'] == 0){
				$this->user->insert('st_static_page', $Insert_Data);
					redirect(admin_url().'staticpage/privacypolicy/'.$types);
			}
			else{
				$this->user->update('st_static_page',array('title' => $_POST['title_name'],'text' => $_POST['description']),array('type' => $type));
				redirect(admin_url().'staticpage/privacypolicy/'.$types);
			}
		}
		admin_views("static/privacy_policy",$this->data);
		}
		else
			redirect(admin_url('404'));

	}

function pages($type=""){ 
		if($type!=""){
			
		$this->data['terms']=$this->user->select_row('st_static_page','*',array('type'=>$type));
	
		if(isset($_POST['description'])){
			$Insert_Data['type']= $type;
			$Insert_Data['title']= $_POST['title_name'];
			$Insert_Data['text']= $_POST['description'];
			$Insert_Data['created_on']= date('Y-m-d H:i:s');
			if($_POST['edit_check'] == 0){
				$this->user->insert('st_static_page', $Insert_Data);
					redirect(admin_url().'staticpage/pages/'.$type);
			}
			else{
				$this->user->update('st_static_page',array('title' => $_POST['title_name'],'text' => $_POST['description']),array('type' => $type));
				redirect(admin_url().'staticpage/pages/'.$type);
			}
		}
		admin_views("static/pages",$this->data);
		}
		else
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
		  	$config['upload_path'] = 'assets/uploads/staticpages/';
	        $config['allowed_types'] = 'gif|jpg|jpeg|png|svg|svg+xml|JPG|JPEG|PNG|SVG|SVG+XML';
	        //$config['max_size'] = 2000;
       		//$config['max_width'] = 1500;
        	//$config['max_height'] = 1500;
	        $new_name = 'static_'.time().'.'.$filename[count($filename)-1];
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

	function upload_video(){
    	if(!empty($_FILES['file']['name'])){ 

			$filename = explode('.',$_FILES["file"]["name"]);
			//$_FILES['file']['name'] = 'faq_'.time().'.'.$filename[count($filename)-1];
		  	$config['upload_path'] = 'assets/uploads/staticpages/';
	        $config['allowed_types'] = 'mp4|3gp|flv|mp3';
	        $config['max_size'] = 10240;
       		//$config['max_width'] = 1500;
        	//$config['max_height'] = 1500;
	        $new_name = 'static_vid'.time().'.'.$filename[count($filename)-1];
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
		
		if(!empty($_FILES['file']['name'])){ 

			$filename = explode('.',$_FILES["file"]["name"]);
			//$_FILES['file']['name'] = 'faq_'.time().'.'.$filename[count($filename)-1];
		  	$config['upload_path'] = 'assets/uploads/staticpages/';
	        $config['allowed_types'] = '*';
	        //$config['max_size'] = 2000;
       		//$config['max_width'] = 1500;
        	//$config['max_height'] = 1500;
	        $new_name = 'static_'.time().'.'.$filename[count($filename)-1];
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
	  		$path = 'assets/uploads/staticpages/'.$dd[$lng-1];
	  	}
	  	else
	  		$path ="";

		if(file_exists($path)){
		    unlink($path);
		}

		
	}

function pages_links_name(){
    $this->data['query_type'] = 'edit';
	 if(!empty($_POST)){

	 $this->form_validation->set_rules('reg_salon', 'Register your Salon', 'required');
	 $this->form_validation->set_rules('book_appointment', 'Book Appointment', 'required');
	 $this->form_validation->set_rules('contact_us', 'Contact Us', 'required');
	 $this->form_validation->set_rules('about_us', 'About Us', 'required');
	 $this->form_validation->set_rules('conditions', 'Conditions', 'required');
	 $this->form_validation->set_rules('terms_of_use', 'Terms of Use', 'required');
	 $this->form_validation->set_rules('imprint', 'Imprint', 'required');
			 
			 //$this->form_validation->set_rules('hospital_id', 'Hospital id', 'required');
			 
			 if ($this->form_validation->run() === TRUE)
			 {
			 	$this->user->update('st_pages_names',$_POST,array('id' =>1));
			 	$this->session->set_flashdata('success','Links name updated successfully.');
			   redirect(base_url('backend/staticpage/pages_links_name'));
			 }
			 else{
			 	$this->data['message'] = validation_errors();

			 $this->data['names']=$this->user->select_row('st_pages_names','*',array('id'=>1));
				
			 admin_views("static/pages_names_input",$this->data);
			 }
	 

	 }else{
	 

	 $this->data['names']=$this->user->select_row('st_pages_names','*',array('id'=>1));
		
	 admin_views("static/pages_names_input",$this->data);
	}
		
	}	


}




