<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Category_setting extends Frontend_Controller{

	function __construct() {
		parent::__construct();
	
		
		$usid=$this->session->userdata('st_userid');
		if(!empty($usid)){
		  $status=getstatus_row($usid);
		  if($status != 'active'){
		  	redirect(base_url('auth/logouts/').$status);
		   }
		}
		
	}
	//** Add to booking by salon **//
	function check_permission()
	{
		$insertArr = array();
		$usid      = $this->session->userdata('st_userid');

		if($this->session->userdata('access')=='marchant')
		{
			
                  $service_det=$this->user->select_row('st_subcategory_settings','id,allow',array('merchant_id'=>$usid,'subcat_id'=>$_POST['subcat_id']));
                    
				if(!empty($service_det))
				   {
					   if($service_det->allow==1)
					      {
						   $chck ="checked";
						  }
					   else{
						   $chck ="";
						   }	 
                      $html ='<tr>
							<td class="text-center pl-4" style="max-width:200px;">'.$_POST['subcat_name'].'</td>
							<td class="text-center">
								<label class="switch ml-2" for="subCategorySetting" style="">
									<input type="checkbox" id="subCategorySetting" name="catallow_option" value="1" data-id="'.$_POST['subcat_id'].'" '.$chck.'>
									<div class="slider round"></div>
								</label>
							</td>
						</tr>';

			       }
			     else{
					 $html ='<tr>
							<td class="text-center pl-4" style="max-width:200px;">'.$_POST['subcat_name'].'</td>
							<td class="text-center">
								<label class="switch ml-2" for="subCategorySetting" style="">
									<input type="checkbox" id="subCategorySetting" value="1" name="catallow_option" data-id="'.$_POST['subcat_id'].'">
									<div class="slider round"></div>
								</label>
							</td>
						</tr>';
					 } 
		
			echo json_encode(array('success' => 1,'html'=>$html));
	    }
	else
	   {
	  	
	  	  echo json_encode(array('success' =>0));  die;	
	   }

	}
	
	
	//** Add to booking by salon **//
 function update_category_setting()
	{
		$insertArr = array();
		$usid      = $this->session->userdata('st_userid');

		if($this->session->userdata('access')=='marchant')
		{
			
                  $service_det=$this->user->select_row('st_subcategory_settings','id,allow',array('merchant_id'=>$usid,'subcat_id'=>$_POST['subcat_id']));
                    
				if(!empty($service_det))
				   {
					   $this->user->update('st_subcategory_settings',array('allow'=>$_POST['val'],'updated_by'=>$usid,'updated_on'=>date('Y-m-d H:i:s')),array('merchant_id'=>$usid,'subcat_id'=>$_POST['subcat_id'])); 
			       }
			     else{
					$this->user->insert('st_subcategory_settings',array('merchant_id'=>$usid,'subcat_id'=>$_POST['subcat_id'],'allow'=>$_POST['val'],'created_on'=>date('Y-m-d H:i:s'),'created_by'=>$usid,'updated_by'=>$usid,'updated_on'=>date('Y-m-d H:i:s'))); 
					 } 
		
			echo json_encode(array('success' => 1));
	    }
	else
	   {
	  	
	  	  echo json_encode(array('success' =>0));  die;	
	   }

	}
	

}
