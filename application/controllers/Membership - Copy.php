<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Membership extends Frontend_Controller
{
    public function __construct()
    {
        parent::__construct();
              // if(($this->session->userdata('user_id')=='') ) { redirect('/', 'refresh'); }
        require_once(FCPATH.'/stripe/init.php');
        // our test credential ankit sir id
		$stripe = array(
           "secret_key"      => STRIPE_SK,
           "publishable_key" => STRIPE_PK
		);
		
		//client acciount credential 
		//~ $stripe = array(
           //~ "secret_key"      => "sk_test_a1FT5pS7qPKpw2wuhtOhr1it",
           //~ "publishable_key" => "pk_test_gdnVrLe5D7NWVxa2Nx9YNSp3"
		//~ );
		
		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		
		if(empty($this->session->userdata('access'))||$this->session->userdata('access')!='marchant'){ redirect(base_url()); }
				
    }
    //*** Membership ***// 
    public function index()
    {
		$uid=$this->session->userdata('st_userid');
		$user=$this->user->select_row('st_users','subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status',array('id' =>$uid));
		$this->data['userdata']=$user;	 
      
       if(!empty($user->stripe_id) && !empty($user->subscription_id) && !empty($user->plan_id) && $user->subscription_status!='cancel')
          {
		   $this->data['planDetail']=$this->user->select_row('st_membership_plan','*',array('stripe_plan_id' =>$user->plan_id));
		   $this->data['user']=$user;
		   $this->load->view('frontend/marchant/current_plan',$this->data);
		  }
	  else
	     {  
			                       $this->db->order_by('id','asc');
		 $this->data['memberships']=$this->user->select('st_membership_plan','*',array('status' =>'active'));
		 	 
         $this->load->view('frontend/marchant/dash_membership',$this->data);
         }
    }
 
  //**** upgrade membership ****//   
 public function upgrade()
    {
		 $uid=$this->session->userdata('st_userid');
		 $user=$this->user->select_row('st_users','subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status',array('id' =>$uid));
		 $this->data['userdata']=$user;	
		  
                   $this->db->order_by('id','asc');
		 $this->data['memberships']=$this->user->select('st_membership_plan','*',array('status' =>'active'));
		 	 
		// echo '<pre>'; print_r($this->data); die;	 
         $this->load->view('frontend/marchant/upgrade_membership',$this->data);
         
    }

 //**** Check employee ****//   
 public function checkemployee(){
	 
	 $planDteial=$this->user->select_row('st_membership_plan','id,employee',array('stripe_plan_id'=>$_POST['plan_id']));
	 
	 $employeeCount=$this->user->select_row('st_users','count(id) as empCount',array('merchant_id'=>$this->session->userdata('st_userid'),'status!='=>'deleted'));
	if($planDteial->employee>=$employeeCount->empCount){
		 echo json_encode(['success'=>1,'msg'=>'']); 
		}
	else{
		$remainEmp=$employeeCount->empCount-$planDteial->employee;
		echo json_encode(['success'=>0,'msg'=>'To degrade membership, you must delete '.$remainEmp.' employees.']); 
		}	
	 
	 }   
 
 //*** Upgrade plan and submit ***//
 public function upgrade_plan_submit($planId)
    {
		 $uid=$this->session->userdata('st_userid');
		 $user=$this->user->select_row('st_users','subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status',array('id' =>$uid));
		if($user->plan_id==$planId)
		 {
		
	    }else{
			$subscription = \Stripe\Subscription::retrieve($user->subscription_id);
			//echo "<pre>"; print_r($subscription['id']); die;
			if(!empty($subscription['id']))
			 {
				$upgradeStatus=\Stripe\Subscription::update($user->subscription_id, [
					  'cancel_at_period_end' => true,
					  'items' => [
						[
						  'id' => $subscription->items->data[0]->id,
						  'plan' =>$planId,
						],
					  ],
					]);
				//echo "<pre>"; print_r($upgradeStatus); die;
			if(!empty($upgradeStatus['id']))
			 {
					
				$res=$this->user->update('st_users',array('plan_id'=>$planId),array('id' =>$this->session->userdata('st_userid')));	
				if($res){ 
					   $this->session->set_flashdata('success','Plan upgrade successfully.'); 
					  redirect(base_url('membership/upgrade'));
					}
				else{
					 $this->session->set_flashdata('error','There is some technical error try again.');
					 redirect(base_url('membership/upgrade'));
					 }
			}
		   else	{  $this->session->set_flashdata('error','There is some technical error try again.');
			       redirect(base_url('membership/upgrade'));			   
			    }		
	     }
	  else{ 
		    $this->session->set_flashdata('error','There is some technical error try again.'); 
		    redirect(base_url('membership/upgrade'));
		   }	
		}
         
    }
   
   //**** Payment ****//
  function payment($id){
	   if(!empty($id)){
		   $this->data['plan_id']=$id;
		   $pid=url_decode($id);
	    $this->data['planDetail']=$this->user->select_row('st_membership_plan','id,price',array('id' =>$pid));
		$this->load->view('frontend/marchant/dash_payment',$this->data);
	   }
	  else{
		  redirect('membership');
		  } 
	}  
  // function paymentSepa(){
	// 	$this->load->view('frontend/marchant/payment-sepa');
	// }    
 //*** Thankyou Page ***//
 function thankyou(){
		$this->load->view('frontend/marchant/thank_you_payment');
	}      
 //~ function current_plan(){
		//~ $this->load->view('frontend/marchant/current_plan');
	//~ } 
 
 
 
 function securepayment(){
     //	if($payamount==""){ if(isset($_POST['memAmount'])){ $payamount=$_POST['memAmount']; } }
     if(!empty($_POST['card_number']) && !empty($_POST['planid']) && !empty($this->session->userdata('st_userid'))){
       extract($_POST);  
       //print_r($_POST); die;
        $email=$this->session->userdata('email');
          $expir_month_year=explode('/',$expir_month_year);
        
        $stripeTokencerete =$this->checkCard($nameofcarthoder,$card_number,$expir_month_year[0],$expir_month_year[1],$cvv);
       //print_r($stripeTokencerete); die;
			if($stripeTokencerete['status']!=false){

      $cardId  = $stripeTokencerete['token']['card']['id'];

			$user=$this->user->select_row('st_users','id,stripe_id,plan_id,subscription_id,created_on',array('id' =>$this->session->userdata('st_userid')));
			if(!empty($user->stripe_id)){
			    $customers=$user->stripe_id;

       $this->user->update('st_users',array("card_id"=>$cardId),array('id' =>$this->session->userdata('st_userid')));
			}else{
			$customer = \Stripe\Customer::create(array(
						  'email' => $email,
						  'source'  => $stripeTokencerete['token']
					  ));
			 $customers=$customer['id'];
			$atripupdateres= $this->user->update('st_users',array("stripe_id"=>$customers,"card_id"=>$cardId),array('id' =>$this->session->userdata('st_userid')));
			//echo $atripupdateres;
			}
		$planDetails=$this->user->select_row('st_membership_plan','*',array('id' =>url_decode($_POST['planid'])));
		
		
         // $plan='plan_EmUTeFmgDiAY7Z';  //monthly
         //$plan='plan_Ez9l0WSWtISom9';    //daily
         $plan=$plan=$planDetails->stripe_plan_id;    //daily
          
          if(!empty($user->plan_id) && $user->plan_id==$plan)
            {
				echo json_encode(['success'=>'0','message'=>'You have already a plan subscription']); die;
			}
          else{  
			//$date=date($user->created_on, strtotime("+90 days"));  	
			$date=date('Y-m-d H:i:s', strtotime($user->created_on. ' + 90 days'));	
            $endTime=strtotime($date);   
            if($date<=date('Y-m-d H:i:s')){
			  $endTime='now';	
				}
				 $endTime='now';	
           // echo  $date."==".$endTime; die;   
		    $subscribe=  \Stripe\Subscription::create([
              "customer" => $customers,
              "items" => [
               [
                  "plan" =>$plan
                ],
              ],
             "trial_end"=>$endTime
            ]);
          //if($subscribe)
       //echo "<pre>"; print_r($subscribe); die;
            $subscriberID=$subscribe->id;
            $start=date('Y-m-d H:i:s',$subscribe->current_period_start);
            $end=date('Y-m-d H:i:s',$subscribe->current_period_end);
  //echo $start.$end.$subscriberID; die;
      $res=$this->user->update('st_users',array("subscription_id"=>$subscriberID,'plan_id'=>$plan,'start_date'=>$start,'end_date'=>$end,'subscription_status'=>'active'),array('id' =>$this->session->userdata('st_userid')));
	  if($res){
		//echo $res; die;
		   echo json_encode(['success'=>'1','message'=>'Subscription activate successfully']); die;
		  }	
	  else{ echo json_encode(['success'=>'0','message'=>'There is a technical problem.']); die; }	 
	   
	   } 
		
     }
     else{
		 echo json_encode(['success'=>'0','message'=>$stripeTokencerete['err']]); die;
		 
		 } 
  }else{ echo json_encode(['success'=>'0','message'=>'There is a technical problem.']); die; }	 
}
public function webhook_responce(){
	
	//echo date('Y-m-d H:i:s',1553850219); die;
 //$input = @file_get_contents("php://input");
 // $event_json = json_decode($input);

//$event_id = $event_json->id; //'evt_1ATc4eDmedOclPPq2iw9LwKv' 
 $event_id = 'evt_1H2AnpGfXvWyQoHZFXiHcpem';
$event = \Stripe\Event::retrieve($event_id);
//echo "<pre>"; print_r($event); die;
  
           if($event->type=='charge.succeeded'){
        
          $event_data = $event->__toArray(true);
            
           $subscription_data =   $event_data['data']['object'];  
  //echo "<pre>"; print_r(); die;
          $type= $subscription_data['payment_method_details']['type'];
            // $sid = $subscription_data['id'];
             $pid = $subscription_data['id'];
             $cid = $subscription_data['customer'];
             $invoice_id =  $subscription_data['invoice'];
             $status =  $subscription_data['status'];
             $create_time = date('Y-m-d',$subscription_data['created']);
             $amount = $subscription_data['amount']/100;
             $currency = $subscription_data['currency'];
             $transactionId = $subscription_data['balance_transaction'];
         
         /******************************/
         $trnsction=$this->user->select_row('st_payments','id',array('transuction_id' =>$transactionId));
         
         if(!empty($trnsction)){         
           $ins = $this->db->query("UPDATE `st_payments` SET `status`='".$status."' WHERE transuction_id='".$transactionId."'");
          }
         else{
			 $user=$this->user->select_row('st_users','id,subscription_id,plan_id',array('stripe_id' =>$cid));
			 
			  $ins = $this->db->query("INSERT INTO `st_payments`( `user_id`, `stripe_id`, `transuction_id`,`subscription_id`,`plan_id`, `amount`, `currency`,`invoice`,`status`,`type`,`created_on`,`created_by`) VALUES ('{$user->id}','{$cid}','{$transactionId}','{$user->subscription_id}','{$user->plan_id}','{$amount}','{$currency}','{$invoice_id}','{$status}','{$type}','{$create_time}','{$user->id}')");
			 
			 } 
         
         
                  /*******************************/

         }
    if($event->type=='invoice.payment_succeeded'){
        
          $event_data = $event->__toArray(true);
            
            $subscription_data =   $event_data['data']['object']; 
           // echo "<pre>"; print_r($subscription_data); die; 
            $start=date('Y-m-d H:i:s',$subscription_data['lines']['data'][0]['period']['start']);
            $end=date('Y-m-d H:i:s',$subscription_data['lines']['data'][0]['period']['end']);
 
             $invoice_id = $subscription_data['id'];
             //start_date end_date
             $invoice_url = $subscription_data['hosted_invoice_url'];
             $invoice_pdf_url =  $subscription_data['invoice_pdf'];
             $strip_id =  $subscription_data['customer'];
         
         /******************************/
        // $trnsction=$this->user->select_row('st_payments','id',array('transuction_id' =>$transactionId));
         
              
           $ins = $this->db->query("UPDATE `st_payments` SET `start_date`='".$start."',`end_date`='".$end."',`invoice_url`='".$invoice_url."',`invoice_pdf_url`='".$invoice_pdf_url."' WHERE invoice='".$invoice_id."' AND stripe_id='".$strip_id."'");
           
           $ins = $this->db->query("UPDATE `st_users` SET `start_date`='".$start."',`end_date`='".$end."',`subscription_status`='active' WHERE stripe_id='".$strip_id."'");
         
         
         
                  /*******************************/

  }
  if($event->type=='charge.pending'){
        
         $event_data = $event->__toArray(true);
            
           $subscription_data =   $event_data['data']['object'];  
  //echo "<pre>"; print_r($subscription_data); die;
           
            // $sid = $subscription_data['id'];
             $type= $subscription_data['payment_method_details']['type'];
             $pid = $subscription_data['id'];
             $cid = $subscription_data['customer'];
             $invoice_id =  $subscription_data['invoice'];
             $status =  $subscription_data['status'];
             $create_time = date('Y-m-d',$subscription_data['created']);
             $amount = $subscription_data['amount']/100;
             $currency = $subscription_data['currency'];
             $transactionId = $subscription_data['balance_transaction'];
         
         /******************************/
         $trnsction=$this->user->select_row('st_payments','id',array('transuction_id' =>$transactionId));
         
         if(!empty($trnsction)){         
           $ins = $this->db->query("UPDATE `st_payments` SET `status`='".$status."' WHERE transuction_id='".$transactionId."'");
          }
         else{
			 $user=$this->user->select_row('st_users','id,subscription_id,plan_id',array('stripe_id' =>$cid));
			 
			  $ins = $this->db->query("INSERT INTO `st_payments`( `user_id`, `stripe_id`, `transuction_id`,`subscription_id`,`plan_id`, `amount`, `currency`,`invoice`,`status`,`type`,`created_on`,`created_by`) VALUES ('{$user->id}','{$cid}','{$transactionId}','{$user->subscription_id}','{$user->plan_id}','{$amount}','{$currency}','{$invoice_id}','{$status}','{$type}','{$create_time}','{$user->id}')");
			 
			 } 
         //$r = $conn->query($s);
         /*******************************/

  }
  if($event->type=='charge.failed'){
        
       $event_data = $event->__toArray(true);
            
           $subscription_data =   $event_data['data']['object'];  
  //echo "<pre>"; print_r($subscription_data); die;
           
            // $sid = $subscription_data['id'];
            $type= $subscription_data['payment_method_details']['type'];
             $pid = $subscription_data['id'];
             $cid = $subscription_data['customer'];
             $invoice_id =  $subscription_data['invoice'];
             $status =  $subscription_data['status'];
             $create_time = date('Y-m-d',$subscription_data['created']);
             $amount = $subscription_data['amount']/100;
             $currency = $subscription_data['currency'];
             $transactionId = $subscription_data['balance_transaction'];
         
         /******************************/
         $trnsction=$this->user->select_row('st_payments','id',array('transuction_id' =>$transactionId));
         
         if(!empty($trnsction)){         
           $ins = $this->db->query("UPDATE `st_payments` SET `status`='".$status."' WHERE transuction_id='".$transactionId."'");
          }
         else{
			 $user=$this->user->select_row('st_users','id,subscription_id,plan_id',array('stripe_id' =>$cid));
			 
			  $ins = $this->db->query("INSERT INTO `st_payments`( `user_id`, `stripe_id`, `transuction_id`,`subscription_id`,`plan_id`, `amount`, `currency`,`invoice`,`status`,`type`,`created_on`,`created_by`) VALUES ('{$user->id}','{$cid}','{$transactionId}','{$user->subscription_id}','{$user->plan_id}','{$amount}','{$currency}','{$invoice_id}','{$status}','{$type}','{$create_time}','{$user->id}')");
			 
			 } 
         //$r = $conn->query($s);
         /*******************************/

      }
      if($event->type=="invoice.created"){

      	      $event_data = $event->__toArray(true);
            
              $subscription_data =   $event_data['data']['object'];  
  //echo "<pre>"; print_r($subscription_data); die;
           
            // $sid = $subscription_data['id'];
             $type= $subscription_data['payment_method_details']['type'];
             $pid = $subscription_data['id'];
             $cid = $subscription_data['customer'];
             $invoice_id =  $subscription_data['id'];
             $status =  $subscription_data['status'];
             $create_time = date('Y-m-d',$subscription_data['created']);
             $amount = $subscription_data['amount']/100;
             $currency = $subscription_data['currency'];
             $transactionId = $subscription_data['balance_transaction'];
         
         /******************************/
         $trnsction=$this->user->select_row('st_payments','id',array('transuction_id' =>$transactionId));
         
         if(!empty($trnsction)){         
           $ins = $this->db->query("UPDATE `st_payments` SET `status`='".$status."' WHERE transuction_id='".$transactionId."'");
          }
         else{
			 $user=$this->user->select_row('st_users','id,subscription_id,plan_id',array('stripe_id' =>$cid));
			 
			  $ins = $this->db->query("INSERT INTO `st_payments`( `user_id`, `stripe_id`, `transuction_id`,`subscription_id`,`plan_id`, `amount`, `currency`,`invoice`,`status`,`type`,`created_on`,`created_by`) VALUES ('{$user->id}','{$cid}','{$transactionId}','{$user->subscription_id}','{$user->plan_id}','{$amount}','{$currency}','{$invoice_id}','{$status}','{$type}','{$create_time}','{$user->id}')");
			 
			 } 


      }
  
    }
  

  /// *** check card *** ///
  function checkCard($nameofcarthoder,$card_number,$expirymonth,$expiryyear,$card_cvc){
 try {
    $stripeTokencerete = \Stripe\Token::create(
			array(
				"card" => array(
				"name" => $nameofcarthoder,
				"number" => $card_number,
				"exp_month" => $expirymonth,
				"exp_year" => $expiryyear,
				"cvc" => $card_cvc
			              )
				)
			);
			$arr=array('status'=>true,'token'=>$stripeTokencerete);
			//echo '<pre>'; print_r($arr); die;
			     return $arr;
 }catch(\Stripe\Error\Card $e){
	            //print_r($e);
                    //$err=$e->__toArray(true);
              	$arr=array('status'=>false,'err'=>$e->getMessage());
			     return $arr;
              	  } 
			//if(isset($stripeTokencerete->id)){ return true; }else{ return false;}
}

//*** cancel subscription ***//
function cancen_subscription(){
	 $userDtails=$this->user->select_row('st_users','id,subscription_id,stripe_id',array('id' =>$this->session->userdata('st_userid'),'subscription_status'=>'active'));
      if(!empty($userDtails->subscription_id)){

			$subscription= \Stripe\Subscription::update(
				  $userDtails->subscription_id,
				  [
					'cancel_at_period_end' => true,
				  ]
				);
				
		  //$event_data = $subscription->__toArray(true);
		  
		  if($subscription->cancel_at_period_end==1){
			  $res=$ins = $this->db->query("UPDATE `st_users` SET `subscription_status`='cancel' WHERE id='".$this->session->userdata('st_userid')."'");
			  if($res){
				  $this->session->set_flashdata('success','Subscription has been canceled successfully');
				  
				  }
				else{
				  $this->session->set_flashdata('error','There is some technical issue please try latter');
				  
				  }  
			// echo "<pre>"; print_r($event_data); die;
		      }
		    else{
				  $this->session->set_flashdata('error','There is some technical issue please try latter');
				  
			  }    
			}
		  else{
				  $this->session->set_flashdata('error','There is no subscipton for you');
				  
			  } 
		redirect(base_url('membership'));
		//if(!empty($this->session->flashdata('error'))) echo $this->session->flashdata('error');	    	
		//if(!empty($this->session->flashdata('success'))) echo $this->session->flashdata('success');	    	
      }

public function creatsorce_for_giropay(){

		$sourceres= \Stripe\Source::create([
		  "type" => "giropay",
		  "amount"=>1000,
		  "currency" => "eur",
		  "statement_descriptor"=>'ORDER AT11990',
		  "owner" => [
		    "name"=>"narendra rathore",
			"email" => "narendra.shantiinfotech@gmail.com"
		  ],
		  "redirect"=>[
              "return_url"=>'http://localhost/styletimer/membership/source_return',
            ]
		]);
		redirect( $sourceres['redirect']['url']);
	 //echo '<pre>'.$sourceres['id']; print_r($sourceres); die;
	}
	
	
  public function change_card(){

    $uid = $this->session->userdata('st_userid');
    if(!empty($uid)) 
    {


      $planDetails = $this->user->select_row('st_users','stripe_id,card_id,subscription_status,end_date',array('id' =>$uid));

        if(!empty($_POST)){
          extract($_POST);
          $expir_month_year=explode('/',$expir_month_year);
             $stripeTokencerete =$this->checkCard($nameofcarthoder,$card_number,$expir_month_year[0],$expir_month_year[1],$cvv);
       //print_r($stripeTokencerete); die;
           if($stripeTokencerete['status']!=false){

             $cardresponse = \Stripe\Customer::deleteSource(
                $planDetails->stripe_id,
                $planDetails->card_id,
                []
              );

            $cardresponse = \Stripe\Customer::createSource(
                $planDetails->stripe_id,
                ['source' => $stripeTokencerete['token']]
              );
            if(!empty($cardresponse)){
               $this->user->update('st_users',array("card_id"=>$cardresponse['id']),array('id' =>$uid));
               }
            echo json_encode(['success'=>'1','message'=>'Card changed successfully.']); die;   
             // echo '<pre>'; print_r($cardresponse); die;

             }else{
               echo json_encode(['success'=>'0','message'=>$stripeTokencerete['err']]); die;
               
               } 


         }else{

           if(!empty($planDetails->card_id) && $planDetails->subscription_status=='active')
            {
        
              $paymentMethod = \Stripe\Customer::retrieveSource(
                $planDetails->stripe_id,
                $planDetails->card_id,
                []
              );
               $this->data['card_details'] = $paymentMethod;
               $this->data['plan_details'] = $planDetails;
             $this->load->view('frontend/marchant/change_card');
             //echo "<pre>"; print_r($paymentMethod); die;
            }
	  
        }
     }

}



}

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */



