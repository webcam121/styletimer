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
      
       if(!empty($user->stripe_id) && !empty($user->subscription_id) && !empty($user->plan_id) && $user->subscription_status!='cancel' && $user->subscription_status!='payment_failed')
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
	if($planDteial->employee=='unlimited'){
		echo json_encode(['success'=>1,'msg'=>'']); 
		}
	elseif($planDteial->employee>=$employeeCount->empCount){
		 echo json_encode(['success'=>1,'msg'=>'']); 
		}
	else{
		$remainEmp=$employeeCount->empCount-$planDteial->employee;
		 if(!empty($_POST['type']) && $_POST['type']=='choose'){
			 echo json_encode(['success'=>0,'msg'=>'Deine aktuelle Mitarbeiterzahl ist zu
			 hoch für diese Mitgliedschaft.']); 
			 }
		else{	 
		   echo json_encode(['success'=>0,'msg'=>'Damit deine Mitgliedschaft herabgestuft werden kann, müssen zunächst '.$remainEmp.' Mitarbeiter gelöscht werden.']); 
	       }
		}	
	 
	 }   
 
	//*** Upgrade plan and submit ***//
	public function upgrade_plan_submit($planId)
	{
		$uid=$this->session->userdata('st_userid');
		$user=$this->user->select_row('st_users','subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status',array('id' =>$uid));
		if($user->plan_id==$planId){}
		else{
			$subscription = \Stripe\Subscription::retrieve($user->subscription_id);
			//echo "<pre>"; print_r($subscription['id']); die;
			if(!empty($subscription['id']))
			{
				$upgradeStatus=\Stripe\Subscription::update($user->subscription_id, [
					'proration_behavior' => 'create_prorations',
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
				else
				{
					$this->session->set_flashdata('error','There is some technical error try again.');
					redirect(base_url('membership/upgrade'));			   
				}		
			}
			else
			{ 
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
	if(!empty($_POST['planid']) && !empty($this->session->userdata('st_userid'))){
		extract($_POST);  
		//print_r($_POST); die;
		$email=$this->session->userdata('email');

		$planDetails=$this->user->select_row('st_membership_plan','*',array('id' =>url_decode($_POST['planid'])));
		$plan=$planDetails->stripe_plan_id;

		try {
			$checkout_session = \Stripe\Checkout\Session::create([
				'line_items' => [[
					'price' => $plan,
					'quantity' => 1,
				]],
				'subscription_data' => [
					'default_tax_rates' => [TAX_ID],
				],
				'mode' => 'subscription',
				'customer_email' => $email,
				'payment_method_types' => ['sepa_debit','card'],
				'success_url' => site_url('/membership/thankyou?session_id={CHECKOUT_SESSION_ID}'),
				'cancel_url' => site_url('/membership'),
			]);

			$checkout_session_id=$checkout_session->id;
			$start = date('Y-m-d H:i:s', $checkout_session->created);
			$end = date('Y-m-d H:i:s', strtotime($start. ' + 31 days'));	
			$res=$this->user->update('st_users',array("subscription_id"=>$checkout_session_id,'plan_id'=>$plan,'start_date'=>$start,'end_date'=>$end,'subscription_status'=>'active'),array('id' =>$this->session->userdata('st_userid')));

			echo json_encode(['success'=>'1','redirect_url'=>$checkout_session->url]); die;
		} catch (Exception $e) {
			echo json_encode(['success'=>'0']); die;
		}
	} 
	echo json_encode(['success'=>'0']); die;
}

  /// *** check card *** ///
  function checkCard($type,$nameofcarthoder,$card_number,$expirymonth,$expiryyear,$card_cvc){
 if($type=='card'){
   try {
     // credit card and debit card 
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
    }else{
      // sepa direct debit
        try {
        $stripeTokencerete = \Stripe\Source::create([
            "type" => "sepa_debit",
            "sepa_debit" => ["iban" => $card_number],
            "currency" => "eur",
            "owner" => [
              "name" => $nameofcarthoder,
            ],
          ]);
        $arr=array('status'=>true,'token'=>$stripeTokencerete);
       // echo '<pre>'; print_r($arr); die;
             return $arr;
        }catch(\Stripe\Error\InvalidRequest $e){
            #errr =$e
                
                     // $err=$e->__toArray(true);
         // echo $e->getMessage();
          //print_r($e); die;
                  $arr=array('status'=>false,'err'=>$e->getMessage());
             return $arr;
                    } 
    }
}

//*** cancel subscription ***//
function cancen_subscription(){
	$userDtails=$this->user->select_row('st_users','id,subscription_id,stripe_id',array('id' =>$this->session->userdata('st_userid'),'subscription_status'=>'active'));
	$userDtailsForPay=$this->user->select_row('st_users','id,subscription_id,stripe_id',array('id' =>$this->session->userdata('st_userid'),'subscription_status'=>'payment_failed'));

	if (empty($userDtails->subscription_id)) {
		$userDtails = $userDtailsForPay;
	}

	if(!empty($userDtails->subscription_id)){

		$subscription= \Stripe\Subscription::update(
			$userDtails->subscription_id,[
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

		try {
			$checkout_session = \Stripe\Checkout\Session::create([
				'mode' => 'setup',
				'customer' => $planDetails->stripe_id,
				'payment_method_types' => ['sepa_debit','card'],
				'success_url' => site_url('/merchant/payment_list'),
				'cancel_url' => site_url('/membership'),
			]);
			redirect($checkout_session->url);
		} catch (Exception $e) {
			redirect(base_url('merchant/payment_list'));
		}
	}

}



}

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */



