<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_methods extends Frontend_Controller {

	function __construct() {
		parent::__construct();
	
		$this->load->model('Taxes_model','taxes');
		$usid=$this->session->userdata('st_userid');
		if(!empty($usid)){
		  $status=getstatus_row($usid);
		  if($status != 'active'){
		  	redirect(base_url('auth/logouts/').$status);
		   }
		}
		
	}
	
 // tax listing for perticuler merchant	
public function listing(){
		 $taxes = $this->taxes->select('st_merchant_payment_method','id,method_name,defualt',array('status !='=>'deleted','user_id'=>$this->session->userdata('st_userid')));	  
	 	 if(!empty($taxes)){
	 	 	$html=''; 
                 foreach($taxes as $row){   
                 	$def='';
                 	if($row->defualt==1){
                 		$def='<span class="default-tax"><i>'.$this->lang->line('Default').'</i></span>';
                      }
                        $html.='<tr>
                          <td class="text-center">'.$row->method_name.'</td>
                          <td class="text-center">'.$def.'</td>
                          <td class="text-center">
                            <div class="dropdown">
                              <div class="pt-1 pb-1" data-toggle="dropdown" aria-expanded="false">
                            <img src="'.base_url('assets/frontend/images/table-more-icon.svg').'">
                          </div>
                              <ul class="dropdown-menu" aria-labelledby="Menu" style="left:auto!important;border:none;">
                                <li class="px-13 py-2">
                                  <a href="#" class="color333 font-size-14 fontfamily-regular editpaymentmethod display-b" data-id="'.url_encode($row->id).'" data-name="'.$row->method_name.'"   data-toggle="modal" data-target=".edit-payment" data-backdrop="static" data-keyboard="false">'.$this->lang->line('Edit').'</a>
                                </li>';
                                 if($row->defualt !=1){ 
                                $html.='<li class="px-13 py-2">
                                  <a href="#" data-pmid="'.url_encode($row->id).'" class="deletepaymentmethod color333 font-size-14 fontfamily-regular display-b">'.$this->lang->line('Delete').'</a>
                                </li>
                                <li class="px-13 py-2">
                                  <a data-id="'.url_encode($row->id).'" href="javascript:void(0)" class="color333 font-size-14 fontfamily-regular display-b set_default_paymentmethod">'.$this->lang->line('Set_as_Default').'</a>
                                </li>';
                                 }
                              $html.='</ul>
                            </div>
                          </td>
                        </tr>';
                         }
                      }
                      else{
                      	$html='<td colspan="4">No method added ....!</td>';
                  }
                  echo json_encode(['html'=>$html]);

	}

// tax add for perticuler merchant		
public function add(){
	  
	  extract($_POST);
	//echo "<pre>"; print_r($_POST); die;
	  $insert                = array();
	  $insert['user_id'] = $this->session->userdata('st_userid');
	  $insert['method_name']    = $name;
	  $insert['status']      = "active";
	  
	  $id = $this->taxes->insert('st_merchant_payment_method',$insert);
	  
	  if($id)
	     {
	      //$this->session->set_flashdata('success',"Payment method added successfully.");
	     	 echo json_encode(['success'=>1,'message' =>'Payment method added successfully.']);
	      //redirect("taxes/listing");
	     }
	  else{
		   $this->session->set_flashdata('error',"Try again.");
	        echo json_encode(['success'=>0,'message' =>'Try again.']);
	       //redirect("taxes/listing");
		  }  
		  
	}	

// tax Edit for perticuler merchant
public function edit(){
	  
	  extract($_POST);
	//echo "<pre>"; print_r($_POST); die;
	 if(!empty($paymentmethodid))
	   {
		
	  $id                    = url_decode($paymentmethodid);
	     
	  $insert                = array();
	  $insert['method_name'] = $ename;
	  $insert['updated_on']  = date('Y-m-d H:i:s');
	  $insert['updated_by']  = $this->session->userdata('st_userid');
	  
	  
	  $res = $this->taxes->update('st_merchant_payment_method',$insert,array('id'=>$id,'user_id'=>$this->session->userdata('st_userid')));
	  
	  if($res)
	     {
	      //$this->session->set_flashdata('success',"Payment method updated successfully.");
	       echo json_encode(['success'=>1,'message' =>'Payment method updated successfully.']);
	      //redirect("taxes/listing");
	     }
	  else{
		   $this->session->set_flashdata('error',"Try again.");
	        echo json_encode(['success'=>0,'message' =>'Try again.']);
	       //redirect("taxes/listing");
		  }  
		  
	  }
   }	

// tax Edit for perticuler merchant
public function delete(){
	  
	  extract($_POST);
	//echo "<pre>"; print_r($_POST); die;
	 if(!empty($pmid))
	   {
	  $id                    = url_decode($pmid);
	  $data = array('status'=>'deleted','updated_on'=>date('Y-m-d H:i:s'),'updated_by'=>$this->session->userdata('st_userid'));  
	  	  
	  $res   = $this->taxes->update('st_merchant_payment_method',$data,array('id'=>$id,'user_id'=>$this->session->userdata('st_userid')));
	  
	  if($res)
	     {
		  //$this->taxes->update('st_merchant_category',array('tax_id'=>0),array('tax_id'=>$id));
		   
	      $this->session->set_flashdata('success',"Payment method deleted successfully.");
	      echo json_encode(['success'=>1,'message' =>'Tax deleted successfully.']);
	      //redirect("taxes/listing");
	     }
	  else{
		   $this->session->set_flashdata('error',"Try again.");
	        echo json_encode(['success'=>0]);
		  }  
		  
	  }
   }
  
 //set defualt tax for service add 
 
 public function set_default($pmid=""){
	 if(!empty($pmid)){
		  $id    = url_decode($pmid);
	      $mid   = $this->session->userdata('st_userid');
	      $data  = array('defualt'=>'1','updated_on'=>date('Y-m-d H:i:s'),'updated_by'=>$mid);
	      $res   = $this->taxes->update('st_merchant_payment_method',$data,array('id'=>$id,'user_id'=>$mid));
	     
	      if($res){
			   $this->taxes->update('st_merchant_payment_method',array('defualt'=>'0'),array('id !='=>$id,'user_id'=>$mid));
			  }
	     
	      
	      //$this->session->set_flashdata('success',"Payment method updated successfully.");
	      echo json_encode(['success'=>1,'message' =>'Payment method updated successfully.']);
	       //redirect("taxes/listing");  
       }else{
		   $this->session->set_flashdata('error',"Try again.");
		   echo json_encode(['success'=>0,'message' =>'Try again.']);
		   //redirect("taxes/listing");
		   }
       
       
	 } 	



}	
