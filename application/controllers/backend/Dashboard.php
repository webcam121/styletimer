<?php
class Dashboard extends Admin_Controller{
	
	public $classname = __CLASS__;
	public $data = array(); 
	
	function __construct() {
		parent::__construct();
		$this->db->cache_off();
		$this->data['segment1'] = $this->uri->segment(2);
		$this->data['segment2'] = $this->uri->segment(3);
		if(empty($this->session->userdata('st_userid')) && $this->session->userdata('access')!='admin'){
		redirect(base_url('backend'));
		}
		//Redirect if user is not logged in as admin
		$this->is_not_logged_in_as_admin_redirect();
	}
	


	function index()
	{  
		
		if(empty($_GET['short'])){
			 if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
				$ed_date= $date1->format('Y-m-d');
				$whr='AND DATE(created_on) >="'.$st_date.'" AND DATE(created_on) <="'.$ed_date.'"';
			    $whr1='AND DATE(updated_on) >="'.$st_date.'" AND Date(updated_on) <="'.$ed_date.'"';
			    //$whr3='AND DATE(booking_time) >="'.$st_date.'" AND Date(booking_time) <="'.$ed_date.'"';
			}
			else{
			// $short_day=date('Y-m-d 23:59:59', strtotime('-30 days'));
			 $whr='AND DATE(created_on) >="'.date('Y-m-d').'" AND DATE(created_on) <="'.date('Y-m-d').'"';
			 $whr1='AND DATE(updated_on) >="'.date('Y-m-d').'" AND DATE(updated_on) <="'.date('Y-m-d').'"';
			 //$whr3='AND DATE(booking_time) >="'.date('Y-m-d 00:00:00').'" AND DATE(booking_time) <="'.date('Y-m-d 23:59:00').'"';
			}
		}
		else if($_GET['short'] =='all'){
				$whr=$whr1='';
				//$whr3='';
		}
		else{
			if($_GET['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_GET['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_GET['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
            else if($_GET['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-t', strtotime('last year'));
            }
			else{
				$start_date=date('Y-m-d');
                $end_date=date('Y-m-d');
			}

			 $whr='AND DATE(created_on) >="'.$start_date.'" AND DATE(created_on) <="'.$end_date.'"';
			 $whr1='AND DATE(updated_on) >="'.$start_date.'" AND DATE(updated_on) <="'.$end_date.'"';
			 //$whr3='AND DATE(booking_time) >="'.$start_date.'" AND DATE(booking_time) <="'.$end_date.'"';

		}
	

		$this->data['all_total']=$this->user->select_row('st_booking','id,(SELECT count(*) FROM st_users WHERE status="active" AND access="marchant" '.$whr.') as merchant_total,(SELECT SUM(total_price) FROM st_booking WHERE status="confirmed" '.$whr1.') as confirm_total_value,(SELECT count(*) FROM st_users WHERE status="active" AND access="user" '.$whr.') as user_total,(SELECT count(*) FROM st_booking WHERE status="confirmed" '.$whr1.') as total_booking,(SELECT SUM(total_price) FROM st_booking WHERE status="completed" '.$whr1.') as total_price');


		$sql="SELECT id,merchant_id,merchant_id as mid, SUM(total_price) as orderTotal,(SELECT count(*) FROM st_booking WHERE st_booking.merchant_id=mid AND st_booking.status='completed' ".$whr1.") as tot_comp,(SELECT business_name from st_users WHERE st_users.id=st_booking.merchant_id) as merchant,(SELECT SUM(total_price) FROM st_booking WHERE status='completed' ".$whr1.") as total_price FROM st_booking WHERE status='completed' ".$whr1." GROUP BY merchant_id ORDER BY orderTotal DESC LIMIT 10";
		$this->data['top_ten_revenue'] = $this->user->custome_query($sql,'result');
		//echo $this->db->last_query();

		$sql1="SELECT id,business_type, count(id) as salon_total FROM st_users WHERE access='marchant' ".$whr." GROUP BY business_type";
		$this->data['salon_by_type'] = $this->user->custome_query($sql1,'result');
	     
		$sql2="SELECT user_id FROM st_booking WHERE status!='cancelled' ".$whr1." GROUP BY user_id";
		$userid= $this->user->custome_query($sql2,'result');
		$userIds=array(0);
		
		 if(!empty($userid)){
			foreach($userid as $user){
					    $userIds[]=$user->user_id;
				 }
		   }

	    $sql3="SELECT id,(SELECT count(*) FROM st_users WHERE st_users.id IN (".implode(',',$userIds).") AND st_users.gender='male') as male,(SELECT count(*) FROM st_users WHERE st_users.id IN (".implode(',',$userIds).") AND st_users.gender='female') as female,(SELECT count(*) FROM st_booking WHERE status='confirmed' AND book_by='0' ".$whr1.") as web_ratio,(SELECT count(*) FROM st_booking WHERE status='confirmed' AND book_by!='0' ".$whr1.") as app_ratio FROM st_booking";
	    
		$this->data['ratio'] = $this->user->custome_query($sql3,'row');
		
	  
	   $latesMerchatsql="SELECT id,business_name,email,business_type,status,access,first_name,last_name,created_on FROM st_users WHERE access='marchant' AND status !='deleted' ORDER BY id desc LIMIT 10";
	   $this->data['latest_merchant']= $this->user->custome_query($latesMerchatsql,'result');
	   
	   //echo '<pre>'; print_r($this->data); die;
	   
		$slaesMansql="SELECT id,first_name,last_name,salesman_code,(SELECT count(id) FROM st_users as u WHERE u.salesman_code=st_users.salesman_code AND access='marchant' ".$whr.") as merchantCount,(SELECT COUNT(id) FROM st_users as u WHERE u.salesman_code=st_users.salesman_code AND u.plan_id !='' ".$whr.") as paidCount,(SELECT SUM(first_plan_price) FROM st_users as u WHERE u.salesman_code=st_users.salesman_code AND u.plan_id !='' ".$whr.") as paidCountValue FROM st_users WHERE status !='deleted' AND access='salesman' ORDER BY merchantCount desc";
		
		//$slaesMansql="SELECT id,first_name,last_name,salesman_code,(SELECT count(id) FROM st_users as u WHERE u.salesman_code=st_users.salesman_code AND access='marchant' ".$whr.") as merchantCount,(SELECT SUM(mp.price) FROM st_users as u LEFT JOIN st_membership_plan as mp ON mp.stripe_plan_id=u.plan_id WHERE u.salesman_code=st_users.salesman_code AND u.plan_id !='' ".$whr.") as paidCount FROM st_users WHERE status !='deleted' AND access='salesman' ORDER BY merchantCount desc";
		
		$this->data['salesman'] = $this->user->custome_query($slaesMansql,'result');
		
		//echo $this->db->last_query().'<pre>'; print_r($this->data); die;

		$sql4="SELECT id,(SELECT count(id) FROM st_users WHERE reffrel_code='Facebook/ Instagram' AND access='marchant' ".$whr.") as fb,(SELECT count(id) FROM st_users WHERE reffrel_code='LinkedIn' AND access='marchant' ".$whr.") as link,(SELECT count(id) FROM st_users WHERE reffrel_code='Google' AND access='marchant' ".$whr.") as google,(SELECT count(id) FROM st_users WHERE reffrel_code='Software comparison site' AND access='marchant' ".$whr.") as site,(SELECT count(id) FROM st_users WHERE reffrel_code='Outdoor advertising' AND access='marchant' ".$whr.") as outdoor,(SELECT count(id) FROM st_users WHERE reffrel_code='TV advertising' AND access='marchant' ".$whr.") as tv,(SELECT count(id) FROM st_users WHERE reffrel_code='Events' AND access='marchant' ".$whr.") as event,(SELECT count(id) FROM st_users WHERE reffrel_code='Referral' AND access='marchant' ".$whr.") as refer,(SELECT count(id) FROM st_users WHERE reffrel_code='Other' AND access='marchant' ".$whr.") as other,(SELECT count(id) FROM st_users WHERE reffrel_code='Recommended by a customer' AND access='marchant' ".$whr.") as rec_customer,(SELECT count(id) FROM st_users WHERE reffrel_code='Recommended by another salon' AND access='marchant' ".$whr.") as rec_salon,(SELECT count(id) FROM st_users WHERE reffrel_code='Magazine/ print advertising' AND access='marchant' ".$whr.") as mag,(SELECT count(id) FROM st_users WHERE reffrel_code!='' AND access='marchant' ".$whr.") as total_heard FROM st_users";
	    
		$this->data['heard_about'] = $this->user->custome_query($sql4,'row');

		//$sql44="SELECT id,(SELECT count(id) FROM st_users WHERE reffrel_code='Facebook/ Instagram' AND access='marchant' ".$whr.") as fb,(SELECT count(id) FROM st_users WHERE reffrel_code='LinkedIn' AND access='marchant' ".$whr.") as link,(SELECT count(id) FROM st_users WHERE reffrel_code='Google' AND access='marchant' ".$whr.") as google,(SELECT count(id) FROM st_users WHERE reffrel_code='Software comparison site' AND access='marchant' ".$whr.") as site,(SELECT count(id) FROM st_users WHERE reffrel_code='Outdoor advertising' AND access='marchant' ".$whr.") as outdoor,(SELECT count(id) FROM st_users WHERE reffrel_code='TV advertising' AND access='marchant' ".$whr.") as tv,(SELECT count(id) FROM st_users WHERE reffrel_code='Events' AND access='marchant' ".$whr.") as event,(SELECT count(id) FROM st_users WHERE reffrel_code='Referral' AND access='marchant' ".$whr.") as refer,(SELECT count(id) FROM st_users WHERE reffrel_code='Other' AND access='marchant' ".$whr.") as other,(SELECT count(id) FROM st_users WHERE reffrel_code='Recommended by a customer' AND access='marchant' ".$whr.") as rec_customer,(SELECT count(id) FROM st_users WHERE reffrel_code='Recommended by another salon' AND access='marchant' ".$whr.") as rec_salon,(SELECT count(id) FROM st_users WHERE reffrel_code='Magazine/ print advertising' AND access='marchant' ".$whr.") as mag,(SELECT count(id) FROM st_users WHERE reffrel_code!='' AND access='marchant' ".$whr.") as total_heard FROM st_users";


		$sql44="SELECT id,(SELECT count(id) FROM st_users WHERE reffrel_code='Facebook' AND access='user' ".$whr.") as fb,(SELECT count(id) FROM st_users WHERE reffrel_code='Instagram' AND access='user' ".$whr.") as insta,(SELECT count(id) FROM st_users WHERE reffrel_code='Google' AND access='user' ".$whr.") as google,(SELECT count(id) FROM st_users WHERE reffrel_code='TV/cinema' AND access='user' ".$whr.") as tv,(SELECT count(id) FROM st_users WHERE reffrel_code='Events' AND access='user' ".$whr.") as event,(SELECT count(id) FROM st_users WHERE reffrel_code='Other' AND access='user' ".$whr.") as other,(SELECT count(id) FROM st_users WHERE reffrel_code='Outdoor advertising' AND access='user' ".$whr.") as outdoor,(SELECT count(id) FROM st_users WHERE reffrel_code='Heard about in salon' AND access='user' ".$whr.") as insalon,(SELECT count(id) FROM st_users WHERE reffrel_code='Heard from other customer' AND access='user' ".$whr.") as oth_cus,(SELECT count(id) FROM st_users WHERE reffrel_code='Magazine/print advertising' AND access='user' ".$whr.") as mag,(SELECT count(id) FROM st_users WHERE reffrel_code!='' AND access='user' ".$whr.") as total_heard FROM st_users";
	    
		
		$this->data['heard_about_user'] = $this->user->custome_query($sql44,'row');

		//print_r($this->data['heard_about_user']);
		$sql5="SELECT id,(SELECT count(id) FROM st_invoices WHERE payment_type='card' ".$whr.") as card,(SELECT count(id) FROM st_invoices WHERE payment_type='other' ".$whr.") as other,(SELECT count(id) FROM st_invoices WHERE payment_type='cash' ".$whr.") as cash,(SELECT count(id) FROM st_invoices WHERE payment_type='voucher' ".$whr.") as voucher,(SELECT count(id) FROM st_invoices ) as total_paytype FROM st_invoices";
	    
		$this->data['pay_type'] = $this->user->custome_query($sql5,'row');
		
		$sql6="SELECT id,(SELECT count(id) FROM st_users WHERE (socialtype='fb' OR socialtype='facebook') AND access='user' ".$whr.") as fb,(SELECT count(id) FROM st_users WHERE socialtype='apple' AND access='user' ".$whr.") as apple,(SELECT count(id) FROM st_users WHERE (socialtype='gmail' OR socialtype='google') AND access='user' ".$whr.") as gmail,(SELECT count(id) FROM st_users WHERE socialtype='' AND access='user' ".$whr.") as regular,(SELECT count(id) FROM st_users WHERE access='user' ".$whr.") as total_user FROM st_users";
		$this->data['user_reg'] = $this->user->custome_query($sql6,'row');
		
		//print_r($this->data['pay_type']);
		admin_views("/dashboard",$this->data);
		
		
	}

	function user_listing(){ 
		if(empty($_GET['short'])){
			 if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
				$ed_date= $date1->format('Y-m-d');
				$whr='AND DATE(created_on) >="'.$st_date.'" AND DATE(created_on) <="'.$ed_date.'"';
			    $whr1='AND DATE(updated_on) >="'.$st_date.'" AND DATE(updated_on) <="'.$ed_date.'"';
			}else{
			// $short_day=date('Y-m-d 23:59:59', strtotime('-30 days'));
			 $whr='AND DATE(created_on) >="'.date('Y-m-d').'" AND DATE(created_on) <="'.date('Y-m-d').'"';
			 $whr1='AND DATE(updated_on) >="'.date('Y-m-d').'" AND DATE(updated_on) <="'.date('Y-m-d').'"';
			}
		}
		else if($_GET['short'] =='all'){
				$whr=$whr1='';
			}
		else{
			if($_GET['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_GET['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_GET['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
            else if($_GET['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-01', strtotime('last year'));
            }
			else{
				$start_date=date('Y-m-d');
                $end_date=date('Y-m-d');
			}

			 $whr='AND DATE(created_on) >="'.$start_date.'" AND DATE(created_on) <="'.$end_date.'"';
			 $whr1='AND DATE(updated_on) >="'.$start_date.'" AND DATE(updated_on) <="'.$end_date.'"';

		}

		$sql="SELECT id,first_name,last_name,email,created_on,(SELECT count(*) FROM st_booking WHERE st_booking.user_id=st_users.id ".$whr1.") as total_booking,(SELECT count(*) FROM st_booking WHERE st_booking.user_id=st_users.id AND st_booking.status='completed' ".$whr1.") as completed_booking FROM st_users WHERE access='user' AND status='active' ".$whr." ";
		$this->data['users']= $this->user->custome_query($sql,'result');

		// echo "<pre>"; print_r($this->data);die;
		admin_views("/user/user_listing",$this->data);
		
	}
	
	function salon_listing(){ 
		
		if(empty($_GET['short'])){
			 if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
				$date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
				$st_date= $date->format('Y-m-d');
				$date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
				$ed_date= $date1->format('Y-m-d');
				$whr='AND DATE(created_on) >="'.$st_date.'" AND DATE(created_on) <="'.$ed_date.'"';
			    $whr1='AND DATE(updated_on) >="'.$st_date.'" AND DATE(updated_on) <="'.$ed_date.'"';
			}else{
			// $short_day=date('Y-m-d 23:59:59', strtotime('-30 days'));
			 $whr='AND DATE(created_on) >="'.date('Y-m-d').'" AND DATE(created_on) <="'.date('Y-m-d').'"';
			 $whr1='AND DATE(updated_on) >="'.date('Y-m-d').'" AND DATE(updated_on) <="'.date('Y-m-d').'"';
			}
		}
		else if($_GET['short'] =='all'){
				$whr=$whr1='';
			}
		else{
			if($_GET['short'] =='current_week'){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$start_date = date("Y-m-d",$monday);
				$end_date = date("Y-m-d",$sunday);
			}
			else if($_GET['short'] =='current_month'){
				 $start_date=date('Y-m-01');
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='last_month'){
				 $start_date=date('Y-m-01',strtotime('last month'));
                 $end_date=date('Y-m-t',strtotime('last month'));
            }
			else if($_GET['short'] =='last_sixmonth'){
				 $start_date=date('Y-m-d', strtotime('-5 months'));
                 $end_date=date('Y-m-t');
            }
			else if($_GET['short'] =='current_year'){
				 $start_date=date('Y-01-01');
                 $end_date=date('Y-12-01');
            }
             else if($_GET['short'] == 'last_year'){
            	$start_date=date('Y-01-01', strtotime('last year'));
            	$end_date=date('Y-12-01', strtotime('last year'));
            }
			else{
				$start_date=date('Y-m-d');
                $end_date=date('Y-m-d');
			}

			 $whr='AND DATE(created_on) >="'.$start_date.'" AND DATE(created_on) <="'.$end_date.'"';
			 $whr1='AND DATE(updated_on) >="'.$start_date.'" AND DATE(updated_on) <="'.$end_date.'"';

		}
		if(!empty($_GET['type'])){
			$whr.='AND business_type="'.$_GET['type'].'"';
		}


		$sql="SELECT id,business_name,email,business_type,created_on,(SELECT count(*) FROM st_booking WHERE st_booking.booking_type !='self' AND st_booking.merchant_id=st_users.id ".$whr1.") as total_booking,(SELECT count(*) FROM st_booking WHERE st_booking.merchant_id=st_users.id AND st_booking.status='completed' ".$whr1.") as completed_booking FROM st_users WHERE access='marchant' ".$whr." ";
		$this->data['users']= $this->user->custome_query($sql,'result');
		
		// echo "<pre>"; print_r($this->data);die;
		admin_views("/user/salon_listing",$this->data);
		
	}

	//**** get recent booking sale ****//
    public function getbookingrecentsale(){
		
		$data  = array();
		
		$data['totalUser_compare'] ='';		
		$data['totalMerchant_compare'] = '';		
		$data['totalBooking_compare'] = '';		
		$data['sales_compare'] = '';	
        //$mid=$this->session->userdata('st_userid');
        
		if($_POST['filter'] =='current_week'){
			$monday = strtotime("last monday");
			$startDate=date('Y-m-d 00:00:00',$monday);
			$endDate=date('Y-m-d 23:59:59', strtotime("+7 day",$monday));
			$startDate2=date('Y-m-d 00:00:00', strtotime("-7 day",strtotime($startDate)));
			$endDate2=date('Y-m-d 23:59:59', strtotime($startDate));
			
			$today = date("Y-m-d",$monday);	
			 for($i=1; $i < 8; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-d 23:59:59',strtotime($today));
			    $res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales,(SELECT count(id) FROM st_users WHERE access='marchant' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as merchant,(SELECT count(id) FROM st_users WHERE access='user' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as user",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('D', strtotime($today));
				 $ODay = date('d', strtotime($today));
				 $data['days'][]=$nameOfDay.' '.$ODay;
				 $data['booking'][]=(int)$res->total_booking;
				 $data['sales'][]=(int)$res->sales;
				 $data['merchant'][]=(int)$res->merchant;
				 $data['user'][]=(int)$res->user;

				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
			}
		
		
		  	 $field_compare="id,(SELECT SUM(total_price) FROM st_booking where 
			 booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='user') as tot_user";
		 
		    $sale_count_comparision = $this->user->select_row('st_booking',$field_compare,array('booking_time >'=> $startDate2, 'booking_time <=' => $endDate2,'status'=>'completed'));	
		    
		
		  $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		 $data['sale_count'] = $sale_count;
		 
		 	 if(empty($sale_count_comparision)){
				$totalUser_compare='<span class="completed">+100%</span>';		
				$totalMerchant_compare='<span class="completed">+100%</span>';		
				$totalBooking_compare='<span class="completed">+100%</span>';		
			    $sales_compare='<span class="completed">+100%</span>';	
			  }
			elseif(empty($sale_count)){
				$totalUser_compare='(<span class="colorred">-100%</span>)';		
				$totalMerchant_compare='<span class="colorred">-100%</span>';		
				$totalBooking_compare='<span class="colorred">-100%</span>';		
			    $sales_compare='<span class="colorred">-100%</span>';	
				}
		   else{		
			  if($sale_count->tot_sale==$sale_count_comparision->tot_sale){
				   $sales_compare='<span class="colorred">0%</span>';
				 }
				elseif($sale_count->tot_sale<$sale_count_comparision->tot_sale){
				 if(!empty($sale_count->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =100-$percent;
					 $sales_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				 else{
					 $sales_compare  ='<span class="colorred">-100%</span>';
					 }  
				 }
				else{
				  if(!empty($sale_count_comparision->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =$percent-100;
					 $sales_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				  }
				 else  
				   $sales_compare  ='<span class="completed">+100%</span>';
				}	
					 
				if($sale_count->tot_booking==$sale_count_comparision->tot_booking){
				$totalBooking_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_booking<$sale_count_comparision->tot_booking){
				if(!empty($sale_count->tot_booking))
				{
				  $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				  $totalMinusPercent =100-$percent;
				  $totalBooking_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else
				 $totalBooking_compare  ='<span class="colorred">-100%</span>';  
				}
				else{
				if(!empty($sale_count_comparision->tot_booking)){ 
				 $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				 $totalMinusPercent =$percent-100;
				 $totalBooking_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				 $totalBooking_compare  ='<span class="pluspercent">+100%</span>'; 
				}

				if($sale_count->tot_merchant==$sale_count_comparision->tot_merchant){
				$totalMerchant_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_merchant<$sale_count_comparision->tot_merchant){

				if(!empty($sale_count->tot_merchant)){
				 
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =100-$percent;
				$totalMerchant_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else{
					$totalMerchant_compare  ='<span class="colorred">-100%</span>';
				  
				  } 
				}
				else{
				if(!empty($sale_count_comparision->tot_merchant))
				{
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =$percent-100;
				$totalMerchant_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				$totalMerchant_compare  ='<span class="pluspercent">+100%</span>';
				}	


				if($sale_count->tot_user==$sale_count_comparision->tot_user){
				$totalUser_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_user<$sale_count_comparision->tot_user){
				 
				 if(!empty($sale_count->tot_user)){
					  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
					  $totalMinusPercent =100-$percent;
					  $totalUser_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				else $totalUser_compare  ='<span class="colorred">-100%</span>';   
				}
				else{
				if(!empty($sale_count_comparision->tot_user))
				 {
				  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
				  $totalMinusPercent =$percent-100;
				  $totalUser_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				 }
				else $totalUser_compare  ='<span class="completed">+100%</span>';  
				}	
               }


				$data['totalUser_compare'] = ' ('.$totalUser_compare.' compared to previous week)';		
				$data['totalMerchant_compare'] = ' ('.$totalMerchant_compare.' compared to previous week)';		
				$data['totalBooking_compare'] = ' ('.$totalBooking_compare.' compared to previous week)';		
				$data['sales_compare'] = ' ('.$sales_compare.' compared to previous week)';	

			
		}
		else if($_POST['filter'] =='current_month'){
			 $today=date('Y-m-01');
			 $maxDays=date('t');
			 
			 $startDate = date('Y-m-01 00:00:00');
			 $endDate   = date('Y-m-t 23:59:59');
			$startDate2=date('Y-m-d 23:59:59', strtotime("-1 month",strtotime($startDate)));
			$endDate2=date('Y-m-d 23:59:59', strtotime($startDate));
			 //$end_date=date('Y-m-t');
			  for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-d 23:59:59',strtotime($today));
			    $res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales,(SELECT count(id) FROM st_users WHERE access='marchant' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as merchant,(SELECT count(id) FROM st_users WHERE access='user' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as user",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('M', strtotime($today));
				 $ODay = date('d', strtotime($today));
				 $data['days'][]=$nameOfDay.' '.$ODay;
				 $data['booking'][]=(int)$res->total_booking;
				 $data['sales'][]=(int)$res->sales;
				 $data['merchant'][]=(int)$res->merchant;
				 $data['user'][]=(int)$res->user;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
			}
			
			
			 $field_compare="id,(SELECT SUM(total_price) FROM st_booking where 
			 booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='user') as tot_user";
		 
		    $sale_count_comparision = $this->user->select_row('st_booking',$field_compare,array('booking_time >'=> $startDate2, 'booking_time <=' => $endDate2,'status'=>'completed'));		
			
		 $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		 $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
		  //echo $this->db->last_query();
			
		 $data['sale_count'] = $sale_count;	
		 
		 	 if(empty($sale_count_comparision)){
				$totalUser_compare='<span class="completed">+100%</span>';		
				$totalMerchant_compare='<span class="completed">+100%</span>';		
				$totalBooking_compare='<span class="completed">+100%</span>';		
			    $sales_compare='<span class="completed">+100%</span>';	
			  }
			elseif(empty($sale_count)){
				$totalUser_compare='(<span class="colorred">-100%</span>)';		
				$totalMerchant_compare='<span class="colorred">-100%</span>';		
				$totalBooking_compare='<span class="colorred">-100%</span>';		
			    $sales_compare='<span class="colorred">-100%</span>';	
				}
		   else{		
			  if($sale_count->tot_sale==$sale_count_comparision->tot_sale){
				   $sales_compare='<span class="colorred">0%</span>';
				 }
				elseif($sale_count->tot_sale<$sale_count_comparision->tot_sale){
				 if(!empty($sale_count->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =100-$percent;
					 $sales_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				 else{
					 $sales_compare  ='<span class="colorred">-100%</span>';
					 }  
				 }
				else{
				  if(!empty($sale_count_comparision->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =$percent-100;
					 $sales_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				  }
				 else  
				   $sales_compare  ='<span class="completed">+100%</span>';
				}	
					 
				if($sale_count->tot_booking==$sale_count_comparision->tot_booking){
				$totalBooking_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_booking<$sale_count_comparision->tot_booking){
				if(!empty($sale_count->tot_booking))
				{
				  $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				  $totalMinusPercent =100-$percent;
				  $totalBooking_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else
				 $totalBooking_compare  ='<span class="colorred">-100%</span>';  
				}
				else{
				if(!empty($sale_count_comparision->tot_booking)){ 
				 $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				 $totalMinusPercent =$percent-100;
				 $totalBooking_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				 $totalBooking_compare  ='<span class="pluspercent">+100%</span>'; 
				}

				if($sale_count->tot_merchant==$sale_count_comparision->tot_merchant){
				$totalMerchant_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_merchant<$sale_count_comparision->tot_merchant){

				if(!empty($sale_count->tot_merchant)){
				 
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =100-$percent;
				$totalMerchant_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else{
					$totalMerchant_compare  ='<span class="colorred">-100%</span>';
				  
				  } 
				}
				else{
				if(!empty($sale_count_comparision->tot_merchant))
				{
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =$percent-100;
				$totalMerchant_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				$totalMerchant_compare  ='<span class="pluspercent">+100%</span>';
				}	


				if($sale_count->tot_user==$sale_count_comparision->tot_user){
				$totalUser_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_user<$sale_count_comparision->tot_user){
				 
				 if(!empty($sale_count->tot_user)){
					  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
					  $totalMinusPercent =100-$percent;
					  $totalUser_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				else $totalUser_compare  ='<span class="colorred">-100%</span>';   
				}
				else{
				if(!empty($sale_count_comparision->tot_user))
				 {
				  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
				  $totalMinusPercent =$percent-100;
				  $totalUser_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				 }
				else $totalUser_compare  ='<span class="completed">+100%</span>';  
				}	
               }

				$data['totalUser_compare'] = ' ('.$totalUser_compare.' compared to previous month)';		
				$data['totalMerchant_compare'] = ' ('.$totalMerchant_compare.' compared to previous month)';		
				$data['totalBooking_compare'] = ' ('.$totalBooking_compare.' compared to previous month)';		
				$data['sales_compare'] = ' ('.$sales_compare.' compared to previous month)';			

			
			 
		}
	 else if($_POST['filter']=='last_month'){
			 $today=date('Y-m-01',strtotime('last month'));
			 $maxDays=date('t',strtotime('last month'));
			 
			 $startDate = date('Y-m-01 00:00:00',strtotime('last month'));
			 $endDate   = date('Y-m-t 23:59:59',strtotime('last month'));
			 $startDate2=date('Y-m-d 23:59:59', strtotime("-1 month",strtotime($startDate)));
			 $endDate2=date('Y-m-d 23:59:59', strtotime($startDate));
			 
			 for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-d 23:59:59',strtotime($today));
			    $res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales,(SELECT count(id) FROM st_users WHERE access='marchant' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as merchant,(SELECT count(id) FROM st_users WHERE access='user' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as user",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('M', strtotime($today));
				 $ODay = date('d', strtotime($today));
				 $data['days'][]=$nameOfDay.' '.$ODay;
				 $data['booking'][]=(int)$res->total_booking;
				 $data['sales'][]=(int)$res->sales;
				 $data['merchant'][]=(int)$res->merchant;
				 $data['user'][]=(int)$res->user;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
			}
		
		
			
			 $field_compare="id,(SELECT SUM(total_price) FROM st_booking where 
			 booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='user') as tot_user";
		 
		    $sale_count_comparision = $this->user->select_row('st_booking',$field_compare,array('booking_time >'=> $startDate2, 'booking_time <=' => $endDate2,'status'=>'completed'));	
			
			 
			
		 $field2="id,(SELECT SUM(total_price) FROM st_booking where 
		 booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		 $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		 $data['sale_count'] = $sale_count;	
		 
		 	if(empty($sale_count_comparision)){
				$totalUser_compare='<span class="completed">+100%</span>';		
				$totalMerchant_compare='<span class="completed">+100%</span>';		
				$totalBooking_compare='<span class="completed">+100%</span>';		
			    $sales_compare='<span class="completed">+100%</span>';	
			  }
			elseif(empty($sale_count)){
				$totalUser_compare='(<span class="colorred">-100%</span>)';		
				$totalMerchant_compare='<span class="colorred">-100%</span>';		
				$totalBooking_compare='<span class="colorred">-100%</span>';		
			    $sales_compare='<span class="colorred">-100%</span>';	
				}
		   else{		
			  if($sale_count->tot_sale==$sale_count_comparision->tot_sale){
				   $sales_compare='<span class="colorred">0%</span>';
				 }
				elseif($sale_count->tot_sale<$sale_count_comparision->tot_sale){
				 if(!empty($sale_count->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =100-$percent;
					 $sales_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				 else{
					 $sales_compare  ='<span class="colorred">-100%</span>';
					 }  
				 }
				else{
				  if(!empty($sale_count_comparision->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =$percent-100;
					 $sales_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				  }
				 else  
				   $sales_compare  ='<span class="completed">+100%</span>';
				}	
					 
				if($sale_count->tot_booking==$sale_count_comparision->tot_booking){
				$totalBooking_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_booking<$sale_count_comparision->tot_booking){
				if(!empty($sale_count->tot_booking))
				{
				  $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				  $totalMinusPercent =100-$percent;
				  $totalBooking_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else
				 $totalBooking_compare  ='<span class="colorred">-100%</span>';  
				}
				else{
				if(!empty($sale_count_comparision->tot_booking)){ 
				 $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				 $totalMinusPercent =$percent-100;
				 $totalBooking_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				 $totalBooking_compare  ='<span class="pluspercent">+100%</span>'; 
				}

				if($sale_count->tot_merchant==$sale_count_comparision->tot_merchant){
				$totalMerchant_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_merchant<$sale_count_comparision->tot_merchant){

				if(!empty($sale_count->tot_merchant)){
				 
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =100-$percent;
				$totalMerchant_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else{
					$totalMerchant_compare  ='<span class="colorred">-100%</span>';
				  
				  } 
				}
				else{
				if(!empty($sale_count_comparision->tot_merchant))
				{
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =$percent-100;
				$totalMerchant_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				$totalMerchant_compare  ='<span class="pluspercent">+100%</span>';
				}	


				if($sale_count->tot_user==$sale_count_comparision->tot_user){
				$totalUser_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_user<$sale_count_comparision->tot_user){
				 
				 if(!empty($sale_count->tot_user)){
					  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
					  $totalMinusPercent =100-$percent;
					  $totalUser_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				else $totalUser_compare  ='<span class="colorred">-100%</span>';   
				}
				else{
				if(!empty($sale_count_comparision->tot_user))
				 {
				  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
				  $totalMinusPercent =$percent-100;
				  $totalUser_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				 }
				else $totalUser_compare  ='<span class="completed">+100%</span>';  
				}	
               }

				$data['totalUser_compare'] = ' ('.$totalUser_compare.' compared to previous month)';		
				$data['totalMerchant_compare'] = ' ('.$totalMerchant_compare.' compared to previous month)';		
				$data['totalBooking_compare'] = ' ('.$totalBooking_compare.' compared to previous month)';		
				$data['sales_compare'] = ' ('.$sales_compare.' compared to previous month)';		
	
			 
		}
		else if($_POST['filter'] =='current_year'){
			 $today   = date('Y-01-01');
			 $maxDays = 12;
			 
			 $startDate = date('Y-01-01 00:00:00');
			 $endDate   = date('Y-12-t 23:59:59');
			  $startDate2=date('Y-m-d 00:00:00', strtotime("-1 year",strtotime($startDate)));
			 $endDate2=date('Y-m-d 23:59:59', strtotime($startDate));
			 
			  for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-t 23:59:59',strtotime($today));
			    $res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales,(SELECT count(id) FROM st_users WHERE access='marchant' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as merchant,(SELECT count(id) FROM st_users WHERE access='user' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as user",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
			// echo $this->db->last_query(); die;
			 
				 $nameOfDay = date('M', strtotime($today));
				 $data['days'][]=$nameOfDay;
				 $data['booking'][]=(int)$res->total_booking;
				 $data['sales'][]=(int)$res->sales;
				 $data['merchant'][]=(int)$res->merchant;
				 $data['user'][]=(int)$res->user;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 month", strtotime($today)));
			}
			
		    $field_compare="id,(SELECT SUM(total_price) FROM st_booking where 
			 booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='user') as tot_user";
		 
		    $sale_count_comparision = $this->user->select_row('st_booking',$field_compare,array('booking_time >'=> $startDate2, 'booking_time <=' => $endDate2,'status'=>'completed'));		
			
			
		  $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time> '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		 $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
		  //echo $this->db->last_query();
			
		  $data['sale_count'] = $sale_count;
		  
		  	if(empty($sale_count_comparision)){
				$totalUser_compare='<span class="completed">+100%</span>';		
				$totalMerchant_compare='<span class="completed">+100%</span>';		
				$totalBooking_compare='<span class="completed">+100%</span>';		
			    $sales_compare='<span class="completed">+100%</span>';	
			  }
			elseif(empty($sale_count)){
				$totalUser_compare='(<span class="colorred">-100%</span>)';		
				$totalMerchant_compare='<span class="colorred">-100%</span>';		
				$totalBooking_compare='<span class="colorred">-100%</span>';		
			    $sales_compare='<span class="colorred">-100%</span>';	
				}
		   else{		
			  if($sale_count->tot_sale==$sale_count_comparision->tot_sale){
				   $sales_compare='<span class="colorred">0%</span>';
				 }
				elseif($sale_count->tot_sale<$sale_count_comparision->tot_sale){
				 if(!empty($sale_count->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =100-$percent;
					 $sales_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				 else{
					 $sales_compare  ='<span class="colorred">-100%</span>';
					 }  
				 }
				else{
				  if(!empty($sale_count_comparision->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =$percent-100;
					 $sales_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				  }
				 else  
				   $sales_compare  ='<span class="completed">+100%</span>';
				}	
					 
				if($sale_count->tot_booking==$sale_count_comparision->tot_booking){
				$totalBooking_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_booking<$sale_count_comparision->tot_booking){
				if(!empty($sale_count->tot_booking))
				{
				  $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				  $totalMinusPercent =100-$percent;
				  $totalBooking_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else
				 $totalBooking_compare  ='<span class="colorred">-100%</span>';  
				}
				else{
				if(!empty($sale_count_comparision->tot_booking)){ 
				 $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				 $totalMinusPercent =$percent-100;
				 $totalBooking_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				 $totalBooking_compare  ='<span class="pluspercent">+100%</span>'; 
				}

				if($sale_count->tot_merchant==$sale_count_comparision->tot_merchant){
				$totalMerchant_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_merchant<$sale_count_comparision->tot_merchant){

				if(!empty($sale_count->tot_merchant)){
				 
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =100-$percent;
				$totalMerchant_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else{
					$totalMerchant_compare  ='<span class="colorred">-100%</span>';
				  
				  } 
				}
				else{
				if(!empty($sale_count_comparision->tot_merchant))
				{
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =$percent-100;
				$totalMerchant_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				$totalMerchant_compare  ='<span class="pluspercent">+100%</span>';
				}	


				if($sale_count->tot_user==$sale_count_comparision->tot_user){
				$totalUser_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_user<$sale_count_comparision->tot_user){
				 
				 if(!empty($sale_count->tot_user)){
					  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
					  $totalMinusPercent =100-$percent;
					  $totalUser_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				else $totalUser_compare  ='<span class="colorred">-100%</span>';   
				}
				else{
				if(!empty($sale_count_comparision->tot_user))
				 {
				  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
				  $totalMinusPercent =$percent-100;
				  $totalUser_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				 }
				else $totalUser_compare  ='<span class="completed">+100%</span>';  
				}	
               }
				$data['totalUser_compare'] = ' ('.$totalUser_compare.' compared to previous year)';		
				$data['totalMerchant_compare'] = ' ('.$totalMerchant_compare.' compared to previous year)';			
				$data['totalBooking_compare'] = ' ('.$totalBooking_compare.' compared to previous year)';			
				$data['sales_compare'] = ' ('.$sales_compare.' compared to previous year)';			

		  		
		}
		else if($_POST['filter'] == 'last_year'){
			$today=date('Y-01-01', strtotime('last year'));
			$maxDays = 12;
			 
			  $startDate = date('Y-01-01 00:00:00',strtotime('last year'));
			  $endDate   = date('Y-12-t 23:59:59',strtotime('last year'));
			
			 $startDate2=date('Y-m-d 00:00:00', strtotime("-1 year",strtotime($startDate)));
			 $endDate2=date('Y-m-d 23:59:59', strtotime($startDate));
			 
			  for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-t 23:59:59',strtotime($today));
			    $res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales,(SELECT count(id) FROM st_users WHERE access='marchant' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as merchant,(SELECT count(id) FROM st_users WHERE access='user' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as user",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('M', strtotime($today));
				$year  = date('Y', strtotime($today));
				 $data['days'][]=$nameOfDay.' '.$year;
				 $data['booking'][]=(int)$res->total_booking;
				 $data['sales'][]=(int)$res->sales;
				 $data['merchant'][]=(int)$res->merchant;
				 $data['user'][]=(int)$res->user;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 month", strtotime($today)));
			}
			
		    $field_compare="id,(SELECT SUM(total_price) FROM st_booking where 
			 booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='user') as tot_user";
		 
		    $sale_count_comparision = $this->user->select_row('st_booking',$field_compare,array('booking_time >'=> $startDate2, 'booking_time <=' => $endDate2,'status'=>'completed'));	 
		   	
			
		  $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		  $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
		   //echo $this->db->last_query();
			
		  $data['sale_count'] = $sale_count;	
		 
		 			if(empty($sale_count_comparision)){
				$totalUser_compare='<span class="completed">+100%</span>';		
				$totalMerchant_compare='<span class="completed">+100%</span>';		
				$totalBooking_compare='<span class="completed">+100%</span>';		
			    $sales_compare='<span class="completed">+100%</span>';	
			  }
			elseif(empty($sale_count)){
				$totalUser_compare='(<span class="colorred">-100%</span>)';		
				$totalMerchant_compare='<span class="colorred">-100%</span>';		
				$totalBooking_compare='<span class="colorred">-100%</span>';		
			    $sales_compare='<span class="colorred">-100%</span>';	
				}
		   else{		
			  if($sale_count->tot_sale==$sale_count_comparision->tot_sale){
				   $sales_compare='<span class="colorred">0%</span>';
				 }
				elseif($sale_count->tot_sale<$sale_count_comparision->tot_sale){
				 if(!empty($sale_count->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =100-$percent;
					 $sales_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				 else{
					 $sales_compare  ='<span class="colorred">-100%</span>';
					 }  
				 }
				else{
				  if(!empty($sale_count_comparision->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =$percent-100;
					 $sales_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				  }
				 else  
				   $sales_compare  ='<span class="completed">+100%</span>';
				}	
					 
				if($sale_count->tot_booking==$sale_count_comparision->tot_booking){
				$totalBooking_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_booking<$sale_count_comparision->tot_booking){
				if(!empty($sale_count->tot_booking))
				{
				  $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				  $totalMinusPercent =100-$percent;
				  $totalBooking_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else
				 $totalBooking_compare  ='<span class="colorred">-100%</span>';  
				}
				else{
				if(!empty($sale_count_comparision->tot_booking)){ 
				 $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				 $totalMinusPercent =$percent-100;
				 $totalBooking_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				 $totalBooking_compare  ='<span class="pluspercent">+100%</span>'; 
				}

				if($sale_count->tot_merchant==$sale_count_comparision->tot_merchant){
				$totalMerchant_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_merchant<$sale_count_comparision->tot_merchant){

				if(!empty($sale_count->tot_merchant)){
				 
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =100-$percent;
				$totalMerchant_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else{
					$totalMerchant_compare  ='<span class="colorred">-100%</span>';
				  
				  } 
				}
				else{
				if(!empty($sale_count_comparision->tot_merchant))
				{
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =$percent-100;
				$totalMerchant_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				$totalMerchant_compare  ='<span class="pluspercent">+100%</span>';
				}	


				if($sale_count->tot_user==$sale_count_comparision->tot_user){
				$totalUser_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_user<$sale_count_comparision->tot_user){
				 
				 if(!empty($sale_count->tot_user)){
					  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
					  $totalMinusPercent =100-$percent;
					  $totalUser_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				else $totalUser_compare  ='<span class="colorred">-100%</span>';   
				}
				else{
				if(!empty($sale_count_comparision->tot_user))
				 {
				  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
				  $totalMinusPercent =$percent-100;
				  $totalUser_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				 }
				else $totalUser_compare  ='<span class="completed">+100%</span>';  
				}	
               }

				$data['totalUser_compare'] = ' ('.$totalUser_compare.' compared to previous year)';		
				$data['totalMerchant_compare'] = ' ('.$totalMerchant_compare.' compared to previous year)';				
				$data['totalBooking_compare'] = ' ('.$totalBooking_compare.' compared to previous year)';				
				$data['sales_compare'] = ' ('.$sales_compare.' compared to previous year)';				
 
		   
		}
		else if($_POST['filter'] == 'last_seven_day'){
			$today=date("Y-m-d", strtotime("-6 days"));
			
			$startDate = date('Y-m-d 00:00:00',strtotime($today));
			$endDate   = date('Y-m-d 23:59:59');
			
			 $startDate2=date('Y-m-d 00:00:00', strtotime("-6 days",strtotime($startDate)));
			 
			 $endDate2=date('Y-m-d 23:59:59', strtotime($startDate));
			
    	 //$today= date('Y-m-d');
    	 
		 for($i=1; $i < 8; $i++){
			 $today_start=date('Y-m-d 00:00:00',strtotime($today));
			 $today_end=date('Y-m-d 23:59:59',strtotime($today));
		    $res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
		// echo $this->db->last_query(); die;
		 
		    $nameOfDay = date('D', strtotime($today));
		     $ODay = date('d', strtotime($today));
		     $data['days'][]=$nameOfDay.' '.$ODay;
		     $data['booking'][]=(int)$res->total_booking;
		     $data['sales'][]=(int)$res->sales;
		    /*$data1=array("y"=>(int)$res->total,
				 "label" => $nameOfDay.' '.$ODay,
				 "color" => "#00b3bf" );
		 	$data[]=$data1;*/  
				
		 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
		   }
		
		
		   $field_compare="id,(SELECT SUM(total_price) FROM st_booking where 
			 booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='user') as tot_user";
		 
		    $sale_count_comparision = $this->user->select_row('st_booking',$field_compare,array('booking_time >'=> $startDate2, 'booking_time <=' => $endDate2,'status'=>'completed'));		
			
		  $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where AND booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking";
		 
		  $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		  $data['sale_count'] = $sale_count;
		  
		  		if(empty($sale_count_comparision)){
				$totalUser_compare='<span class="completed">+100%</span>';		
				$totalMerchant_compare='<span class="completed">+100%</span>';		
				$totalBooking_compare='<span class="completed">+100%</span>';		
			    $sales_compare='<span class="completed">+100%</span>';	
			  }
			elseif(empty($sale_count)){
				$totalUser_compare='(<span class="colorred">-100%</span>)';		
				$totalMerchant_compare='<span class="colorred">-100%</span>';		
				$totalBooking_compare='<span class="colorred">-100%</span>';		
			    $sales_compare='<span class="colorred">-100%</span>';	
				}
		   else{		
			  if($sale_count->tot_sale==$sale_count_comparision->tot_sale){
				   $sales_compare='<span class="colorred">0%</span>';
				 }
				elseif($sale_count->tot_sale<$sale_count_comparision->tot_sale){
				 if(!empty($sale_count->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =100-$percent;
					 $sales_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				 else{
					 $sales_compare  ='<span class="colorred">-100%</span>';
					 }  
				 }
				else{
				  if(!empty($sale_count_comparision->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =$percent-100;
					 $sales_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				  }
				 else  
				   $sales_compare  ='<span class="completed">+100%</span>';
				}	
					 
				if($sale_count->tot_booking==$sale_count_comparision->tot_booking){
				$totalBooking_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_booking<$sale_count_comparision->tot_booking){
				if(!empty($sale_count->tot_booking))
				{
				  $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				  $totalMinusPercent =100-$percent;
				  $totalBooking_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else
				 $totalBooking_compare  ='<span class="colorred">-100%</span>';  
				}
				else{
				if(!empty($sale_count_comparision->tot_booking)){ 
				 $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				 $totalMinusPercent =$percent-100;
				 $totalBooking_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				 $totalBooking_compare  ='<span class="pluspercent">+100%</span>'; 
				}

				if($sale_count->tot_merchant==$sale_count_comparision->tot_merchant){
				$totalMerchant_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_merchant<$sale_count_comparision->tot_merchant){

				if(!empty($sale_count->tot_merchant)){
				 
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =100-$percent;
				$totalMerchant_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else{
					$totalMerchant_compare  ='<span class="colorred">-100%</span>';
				  
				  } 
				}
				else{
				if(!empty($sale_count_comparision->tot_merchant))
				{
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =$percent-100;
				$totalMerchant_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				$totalMerchant_compare  ='<span class="pluspercent">+100%</span>';
				}	


				if($sale_count->tot_user==$sale_count_comparision->tot_user){
				$totalUser_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_user<$sale_count_comparision->tot_user){
				 
				 if(!empty($sale_count->tot_user)){
					  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
					  $totalMinusPercent =100-$percent;
					  $totalUser_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				else $totalUser_compare  ='<span class="colorred">-100%</span>';   
				}
				else{
				if(!empty($sale_count_comparision->tot_user))
				 {
				  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
				  $totalMinusPercent =$percent-100;
				  $totalUser_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				 }
				else $totalUser_compare  ='<span class="completed">+100%</span>';  
				}	
               }

				$data['totalUser_compare'] = ' ('.$totalUser_compare.' compared to previous 7 days)';		
				$data['totalMerchant_compare'] = ' ('.$totalMerchant_compare.' compared to previous 7 days)';			
				$data['totalBooking_compare'] = ' ('.$totalBooking_compare.' compared to previous 7 days)';			
				$data['sales_compare'] = ' ('.$sales_compare.' compared to previous 7 days)';			

			
	     }
	     else if($_POST['filter'] == 'day'){
			
			    $monday = strtotime("last monday");
				$today = date("Y-m-d",$monday);	
				$currentdate = date('Y-m-d');
				
				$startDate = date('Y-m-d 00:00:00');
			    $endDate   = date('Y-m-d 23:59:59');
			     $startDate2=date('Y-m-d 00:00:00', strtotime("-1 day",strtotime($startDate)));
			     $endDate2=date('Y-m-d 00:00:00', strtotime($startDate));
				
				 for($i=1; $i < 8; $i++){
					 
				if($currentdate==date('Y-m-d',strtotime($today))){
					 $today_start=date('Y-m-d 00:00:00',strtotime($today));
					 $today_end=date('Y-m-d 23:59:59',strtotime($today));
					$res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales,(SELECT count(id) FROM st_users WHERE access='marchant' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as merchant,(SELECT count(id) FROM st_users WHERE access='user' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as user",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
				// echo $this->db->last_query(); die;
				 
					 $nameOfDay = date('D', strtotime($today));
					 $ODay = date('d', strtotime($today));
					 $data['days'][]=$nameOfDay.' '.$ODay;
					 $data['booking'][]=(int)$res->total_booking;
					 $data['sales'][]=(int)$res->sales;
					 $data['merchant'][]=(int)$res->merchant;
				     $data['user'][]=(int)$res->user;
					}
				else{
					 $nameOfDay = date('D', strtotime($today));
					 $ODay = date('d', strtotime($today));
					 $data['days'][]=$nameOfDay.' '.$ODay;
					 $data['booking'][]=0;
					 $data['sales'][]=0;
					 $data['merchant'][]=0;
				     $data['user'][]=0;
					}	
					 
					/*$data1=array("y"=>(int)$res->total,
						 "label" => $nameOfDay.' '.$ODay,
						 "color" => "#00b3bf" );
					$data[]=$data1;*/  
						
				 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
				}
			
			 $field_compare="id,IFNULL((SELECT SUM(total_price) FROM st_booking where 
			 booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed'),'0') as tot_sale, IFNULL((SELECT count(id) FROM st_booking where booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed'),'0') as tot_booking,IFNULL((SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='marchant'),'0') as tot_merchant,IFNULL((SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='user'),'0') as tot_user";
		 
		    $sale_count_comparision = $this->user->select_row('st_booking',$field_compare,array('booking_time >'=> $startDate2, 'booking_time <=' => $endDate2,'id !='=>'0'));	
		    
		    // echo $this->db->last_query();

			$field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time> '".$startDate."' AND booking_time<= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time<= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where access='marchant' AND created_on > '".$startDate."' AND created_on<= '".$endDate."') as tot_merchant,(SELECT count(id) FROM st_users where access='user' AND created_on > '".$startDate."' AND created_on<= '".$endDate."') as tot_users";
		 
		  $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'id !='=>'0'));	
		  //echo '<pre>'; print_r($sale_count_comparision); echo '<pre>'; print_r($sale_count); die; 
		  //$this->db->last_query();
		  $data['sale_count'] = $sale_count;
		  	
		  	if(empty($sale_count_comparision)){
				$totalUser_compare='<span class="completed">+100%</span>';		
				$totalMerchant_compare='<span class="completed">+100%</span>';		
				$totalBooking_compare='<span class="completed">+100%</span>';		
			    $sales_compare='<span class="completed">+100%</span>';	
			  }
			elseif(empty($sale_count)){
				$totalUser_compare='(<span class="colorred">-100%</span>)';		
				$totalMerchant_compare='<span class="colorred">-100%</span>';		
				$totalBooking_compare='<span class="colorred">-100%</span>';		
			    $sales_compare='<span class="colorred">-100%</span>';	
				}
		   else{		
			  if($sale_count->tot_sale==$sale_count_comparision->tot_sale){
				   $sales_compare='<span class="colorred">0%</span>';
				 }
				elseif($sale_count->tot_sale<$sale_count_comparision->tot_sale){
				 if(!empty($sale_count->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =100-$percent;
					 $sales_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				 else{
					 $sales_compare  ='<span class="colorred">-100%</span>';
					 }  
				 }
				else{
				  if(!empty($sale_count_comparision->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =$percent-100;
					 $sales_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				  }
				 else  
				   $sales_compare  ='<span class="completed">+100%</span>';
				}	
					 
				if($sale_count->tot_booking==$sale_count_comparision->tot_booking){
				$totalBooking_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_booking<$sale_count_comparision->tot_booking){
				if(!empty($sale_count->tot_booking))
				{
				  $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				  $totalMinusPercent =100-$percent;
				  $totalBooking_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else
				 $totalBooking_compare  ='<span class="colorred">-100%</span>';  
				}
				else{
				if(!empty($sale_count_comparision->tot_booking)){ 
				 $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				 $totalMinusPercent =$percent-100;
				 $totalBooking_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				 $totalBooking_compare  ='<span class="pluspercent">+100%</span>'; 
				}

				if($sale_count->tot_merchant==$sale_count_comparision->tot_merchant){
				$totalMerchant_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_merchant<$sale_count_comparision->tot_merchant){

				if(!empty($sale_count->tot_merchant)){
				 
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =100-$percent;
				$totalMerchant_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else{
					$totalMerchant_compare  ='<span class="colorred">-100%</span>';
				  
				  } 
				}
				else{
				if(!empty($sale_count_comparision->tot_merchant))
				{
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =$percent-100;
				$totalMerchant_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				$totalMerchant_compare  ='<span class="pluspercent">+100%</span>';
				}	


				if($sale_count->tot_user==$sale_count_comparision->tot_user){
				$totalUser_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_user<$sale_count_comparision->tot_user){
				 
				 if(!empty($sale_count->tot_user)){
					  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
					  $totalMinusPercent =100-$percent;
					  $totalUser_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				else $totalUser_compare  ='<span class="colorred">-100%</span>';   
				}
				else{
				if(!empty($sale_count_comparision->tot_user))
				 {
				  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
				  $totalMinusPercent =$percent-100;
				  $totalUser_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				 }
				else $totalUser_compare  ='<span class="completed">+100%</span>';  
				}	
               }

				$data['totalUser_compare'] = '('.$totalUser_compare.' compared to previous day)';		
				$data['totalMerchant_compare'] = '('.$totalMerchant_compare.' compared to previous day)';		
				$data['totalBooking_compare'] = '('.$totalBooking_compare.' compared to previous day)';			
				$data['sales_compare'] = '('.$sales_compare.' compared to previous day)';			


		}
		else if($_POST['filter'] == 'last_sixmonth')
		{

			 $startDate=date("Y-m-01 00:00:00", strtotime(" -5 month"));
			 $endDate=date('Y-m-31 23:59:59');
			 
			 $startDate2=date('Y-m-d 00:00:00', strtotime("-5 month",strtotime($startDate)));
			 $endDate2=date('Y-m-d 00:00:00', strtotime($startDate));
			
			//$today = date("Y-m-d",$monday);	
			 for ($j = 5; $j > -1; $j--) {
					//echo date("F Y", strtotime(" -$j month"));
					$today= date("Y-m-d 00:00:00", strtotime(" -$j month"));
					//echo date("Y-m-d 00:00:00", strtotime(" -$j month"));
				
				 $today_start=date('Y-m-01 00:00:00',strtotime(" -$j month"));
				 $today_end=date('Y-m-31 23:59:59',strtotime(" -$j month"));
				 //$today_start=date('Y-m-d 00:00:00',strtotime(" -$j month"));
				 //$today_end=date('Y-m-d 23:59:59',strtotime(" -$j month"));
			    $res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales,(SELECT count(id) FROM st_users WHERE access='marchant' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as merchant,(SELECT count(id) FROM st_users WHERE access='user' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as user",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
			// echo $this->db->last_query(); die;
			 
				 //$nameOfDay = date('D', strtotime($today));
				 //$ODay = date('d', strtotime($today));
				 //$data['days'][]=$nameOfDay.' '.$ODay;
			     $data['days'][]= date("M Y", strtotime(" -$j month"));;
				 $data['booking'][]=(int)$res->total_booking;
				 $data['sales'][]=(int)$res->sales;
				 $data['merchant'][]=(int)$res->merchant;
				 $data['user'][]=(int)$res->user;

				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 month", strtotime($today)));
			}
		
		  $field_compare="id,(SELECT SUM(total_price) FROM st_booking where 
			 booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate2."' AND booking_time <= '".$endDate2."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate2."' AND created_on <= '".$endDate2."' AND access='user') as tot_user";
		 
		    $sale_count_comparision = $this->user->select_row('st_booking',$field_compare,array('booking_time >'=> $startDate2, 'booking_time <=' => $endDate2,'status'=>'completed'));	
		
		  $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		 $data['sale_count'] = $sale_count;
		 
			if(empty($sale_count_comparision)){
				$totalUser_compare='<span class="completed">+100%</span>';		
				$totalMerchant_compare='<span class="completed">+100%</span>';		
				$totalBooking_compare='<span class="completed">+100%</span>';		
			    $sales_compare='<span class="completed">+100%</span>';	
			  }
			elseif(empty($sale_count)){
				$totalUser_compare='(<span class="colorred">-100%</span>)';		
				$totalMerchant_compare='<span class="colorred">-100%</span>';		
				$totalBooking_compare='<span class="colorred">-100%</span>';		
			    $sales_compare='<span class="colorred">-100%</span>';	
				}
		   else{		
			  if($sale_count->tot_sale==$sale_count_comparision->tot_sale){
				   $sales_compare='<span class="colorred">0%</span>';
				 }
				elseif($sale_count->tot_sale<$sale_count_comparision->tot_sale){
				 if(!empty($sale_count->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =100-$percent;
					 $sales_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				 else{
					 $sales_compare  ='<span class="colorred">-100%</span>';
					 }  
				 }
				else{
				  if(!empty($sale_count_comparision->tot_sale)){
					 $percent = $sale_count->tot_sale*100/$sale_count_comparision->tot_sale;
					 $totalMinusPercent =$percent-100;
					 $sales_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				  }
				 else  
				   $sales_compare  ='<span class="completed">+100%</span>';
				}	
					 
				if($sale_count->tot_booking==$sale_count_comparision->tot_booking){
				$totalBooking_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_booking<$sale_count_comparision->tot_booking){
				if(!empty($sale_count->tot_booking))
				{
				  $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				  $totalMinusPercent =100-$percent;
				  $totalBooking_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else
				 $totalBooking_compare  ='<span class="colorred">-100%</span>';  
				}
				else{
				if(!empty($sale_count_comparision->tot_booking)){ 
				 $percent = $sale_count->tot_booking*100/$sale_count_comparision->tot_booking;
				 $totalMinusPercent =$percent-100;
				 $totalBooking_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				 $totalBooking_compare  ='<span class="pluspercent">+100%</span>'; 
				}

				if($sale_count->tot_merchant==$sale_count_comparision->tot_merchant){
				$totalMerchant_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_merchant<$sale_count_comparision->tot_merchant){

				if(!empty($sale_count->tot_merchant)){
				 
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =100-$percent;
				$totalMerchant_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				}
				else{
					$totalMerchant_compare  ='<span class="colorred">-100%</span>';
				  
				  } 
				}
				else{
				if(!empty($sale_count_comparision->tot_merchant))
				{
				$percent = $sale_count->tot_merchant*100/$sale_count_comparision->tot_merchant;
				$totalMinusPercent =$percent-100;
				$totalMerchant_compare  ='<span class="pluspercent">+'.round($totalMinusPercent,2).'%</span>';
				}
				else 
				$totalMerchant_compare  ='<span class="pluspercent">+100%</span>';
				}	


				if($sale_count->tot_user==$sale_count_comparision->tot_user){
				$totalUser_compare='<span class="colorred">0%</span>';
				}
				elseif($sale_count->tot_user<$sale_count_comparision->tot_user){
				 
				 if(!empty($sale_count->tot_user)){
					  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
					  $totalMinusPercent =100-$percent;
					  $totalUser_compare  ='<span class="colorred">-'.round($totalMinusPercent,2).'%</span>';
				   }
				else $totalUser_compare  ='<span class="colorred">-100%</span>';   
				}
				else{
				if(!empty($sale_count_comparision->tot_user))
				 {
				  $percent = $sale_count->tot_user*100/$sale_count_comparision->tot_user;
				  $totalMinusPercent =$percent-100;
				  $totalUser_compare  ='<span class="completed">+'.round($totalMinusPercent,2).'%</span>';
				 }
				else $totalUser_compare  ='<span class="completed">+100%</span>';  
				}	
               }

				$data['totalUser_compare'] = '('.$totalUser_compare.' compared to previous 6 month)';		
				$data['totalMerchant_compare'] = '('.$totalMerchant_compare.' compared to previous 6 month)';			
				$data['totalBooking_compare'] = '('.$totalBooking_compare.' compared to previous 6 month)';		
				$data['sales_compare'] = '('.$sales_compare.' compared to previous 6 month)';			
    //echo $sales_compare.' - '.$totalBooking_compare.' - '.$totalMerchant_compare.' - '.$totalUser_compare; 
    //echo '<pre>'; print_r($sale_count_comparision);
    //echo '<pre>'; print_r($sale_count); die;
				// $start_date=date('Y-m-d', strtotime('-5 months'));
                // $end_date=date('Y-m-t');
		}
		else if($_POST['filter'] =='all'){

			$ress=$this->user->select_row('st_booking',"booking_time",array('status'=>'completed'));
			if(!empty($ress->booking_time))
				$date1=date('Y-m-01',strtotime($ress->booking_time));
			else
				$date1 = date('Y-m-01');

			$date2 = date('Y-m-01');

			$ts1 = strtotime($date1);
			$ts2 = strtotime($date2);

			$year1 = date('Y', $ts1);
			$year2 = date('Y', $ts2);

			$month1 = date('m', $ts1);
			$month2 = date('m', $ts2);

			$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
			
			 $today   = $date1;
			 $maxDays = $diff+1;
			 
			 $startDate = date('Y-01-01 00:00:00');
			 $endDate   = date('Y-12-t 23:59:59');
			 
			  for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-t 23:59:59',strtotime($today));
			    $res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales,(SELECT count(id) FROM st_users WHERE access='marchant' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as merchant,(SELECT count(id) FROM st_users WHERE access='user' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as user",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
			 //echo $this->db->last_query(); die;
			 
				 $nameOfDay = date('M', strtotime($today));
				 $data['days'][]=$nameOfDay;
				 $data['booking'][]=(int)$res->total_booking;
				 $data['sales'][]=(int)$res->sales;
				 $data['merchant'][]=(int)$res->merchant;
				 $data['user'][]=(int)$res->user;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 month", strtotime($today)));
			}
			
		  $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time> '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		 $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
		  //echo $this->db->last_query();
			
		  $data['sale_count'] = $sale_count;		
		}
		else
		{
			if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
					$date = DateTime::createFromFormat('d/m/Y', $_POST['start_date']);
					$start_date= $date->format('Y-m-d');
					$startDate= $date->format('Y-m-d');
					$date1 = DateTime::createFromFormat('d/m/Y', $_POST['end_date']);
					$end_date= $date1->format('Y-m-d');
					$endDate= $date1->format('Y-m-d');
					//$whr='AND DATE(created_on) >="'.$st_date.'" AND DATE(created_on) <="'.$ed_date.'"';
			    	//$whr1='AND DATE(updated_on) >="'.$st_date.'" AND Date(updated_on) <="'.$ed_date.'"';
							

			 //$startDate=date("Y-m-01 00:00:00", strtotime(" -5 month"));
			 //$endDate=date('Y-m-31 23:59:59');
			

			while (strtotime($start_date) <= strtotime($end_date)) {
			   

			//$today = date("Y-m-d",$monday);	
			 //for ($j = 5; $j > -1; $j--) {
					//echo date("F Y", strtotime(" -$j month"));
					//$today= date("Y-m-d 00:00:00", strtotime(" -$j month"));
					//echo date("Y-m-d 00:00:00", strtotime(" -$j month"));
				
				 $today_start=date($start_date.' 00:00:00',strtotime($start_date));
				 $today_end=date($start_date.' 23:59:59',strtotime($start_date));
				 //$today_start=date('Y-m-d 00:00:00',strtotime(" -$j month"));
				 //$today_end=date('Y-m-d 23:59:59',strtotime(" -$j month"));
			    $res=$this->user->select_row('st_booking',"count('id') as total_booking,SUM(total_price) as sales,(SELECT count(id) FROM st_users WHERE access='marchant' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as merchant,(SELECT count(id) FROM st_users WHERE access='user' AND created_on >='".$today_start."' AND created_on >='".$today_end."') as user",array('booking_time>='=>$today_start,'booking_time<='=>$today_end,'status'=>'completed'));
			 	//echo $this->db->last_query(); die;
			 
				 //$nameOfDay = date('D', strtotime($today));
				 //$ODay = date('d', strtotime($today));
				 //$data['days'][]=$nameOfDay.' '.$ODay;
			     $data['days'][]= date("d-m-y", strtotime($start_date));
				 $data['booking'][]=(int)$res->total_booking;
				 $data['sales'][]=(int)$res->sales;
				 $data['merchant'][]=(int)$res->merchant;
				 $data['user'][]=(int)$res->user;

				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
				
				 $start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
			}

			 //$today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
			//}
		
		  $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		 $data['sale_count'] = $sale_count;

			}
				// $start_date=date('Y-m-d', strtotime('-5 months'));
                // $end_date=date('Y-m-t');
		}
		
    	  
	echo json_encode($data); die;
    }



	//**** get booking value ****//
    public function getbookingvalues(){
		
		$data  = array();
        //$mid=$this->session->userdata('st_userid');
        
		if($_POST['filter'] =='current_week'){
			$monday = strtotime("last monday");
			$startDate=date('Y-m-d 00:00:00',$monday);
			$endDate=date('Y-m-d 23:59:59', strtotime("+7 day",$monday));
			
			$today = date("Y-m-d",$monday);	
			 for($i=1; $i < 8; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-d 23:59:59',strtotime($today));
			    $res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('D', strtotime($today));
				 $ODay = date('d', strtotime($today));
				 $data['days'][]=$nameOfDay.' '.$ODay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;
				// $data['user'][]=(int)$res->user;

				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
			}
		
		 // $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		// $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		// $data['sale_count'] = $sale_count;
			
		}
		else if($_POST['filter'] =='current_month'){
			 $today=date('Y-m-01');
			 $maxDays=date('t');
			 
			 $startDate = date('Y-m-01 00:00:00');
			 $endDate   = date('Y-m-t 23:59:59');
			 //$end_date=date('Y-m-t');
			  for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-d 23:59:59',strtotime($today));
			     $res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('M', strtotime($today));
				 $ODay = date('d', strtotime($today));
				 $data['days'][]=$nameOfDay.' '.$ODay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
			}
			
			
		 //$field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		 //$sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
		  //echo $this->db->last_query();
			
		// $data['sale_count'] = $sale_count;	
			
			 
		}
	 else if($_POST['filter']=='last_month'){
			 $today=date('Y-m-01',strtotime('last month'));
			 $maxDays=date('t',strtotime('last month'));
			 
			 $startDate = date('Y-m-01 00:00:00',strtotime('last month'));
			 $endDate   = date('Y-m-t 23:59:59',strtotime('last month'));
			 
			 for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-d 23:59:59',strtotime($today));
			    $res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('D', strtotime($today));
				 $ODay = date('d', strtotime($today));
				 $data['days'][]=$nameOfDay.' '.$ODay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
			}
			
		// $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		// $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		 //$data['sale_count'] = $sale_count;		
			 
		}
		else if($_POST['filter'] =='current_year'){
			 $today   = date('Y-01-01');
			 $maxDays = 12;
			 
			 $startDate = date('Y-01-01 00:00:00');
			 $endDate   = date('Y-12-t 23:59:59');
			 
			  for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-t 23:59:59',strtotime($today));
			  $res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('M', strtotime($today));
				 $data['days'][]=$nameOfDay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 month", strtotime($today)));
			}
			
		 // $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time> '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		 //$sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
		  //echo $this->db->last_query();
			
		 // $data['sale_count'] = $sale_count;		
		}
		else if($_POST['filter'] == 'last_year'){
			$today=date('Y-01-01', strtotime('last year'));
			$maxDays = 12;
			 
			  $startDate = date('Y-01-01 00:00:00',strtotime('last year'));
			  $endDate   = date('Y-12-t 23:59:59',strtotime('last year'));
			 
			  for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-t 23:59:59',strtotime($today));
			  $res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			 //echo $this->db->last_query(); die;
			 
				$nameOfDay = date('M', strtotime($today));
				 $data['days'][]=$nameOfDay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 month", strtotime($today)));
			}
			
		  //$field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		 // $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
		   //echo $this->db->last_query();
			
		  //$data['sale_count'] = $sale_count;	 
		}
		else if($_POST['filter'] == 'last_seven_day'){
			$today=date("Y-m-d", strtotime("-6 days"));
			
			$startDate = date('Y-m-d 00:00:00',strtotime($today));
			$endDate   = date('Y-m-d 23:59:59');
			
    	 //$today= date('Y-m-d');
    	 
		 for($i=1; $i < 8; $i++){
			 $today_start=date('Y-m-d 00:00:00',strtotime($today));
			 $today_end=date('Y-m-d 23:59:59',strtotime($today));
		   $res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('D', strtotime($today));
				 $ODay = date('d', strtotime($today));
				 $data['days'][]=$nameOfDay.' '.$ODay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;
		    /*$data1=array("y"=>(int)$res->total,
				 "label" => $nameOfDay.' '.$ODay,
				 "color" => "#00b3bf" );
		 	$data[]=$data1;*/  
				
		 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
		   }
			
			
		  //$field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where AND booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking";
		 
		 // $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		  //$data['sale_count'] = $sale_count;
			
	     }
	     else if($_POST['filter'] == 'day'){
			
			    $monday = strtotime("last monday");
				$today = date("Y-m-d",$monday);	
				$currentdate = date('Y-m-d');
				
				$startDate = date('Y-m-d 00:00:00');
			    $endDate   = date('Y-m-d 23:59:59');
				
				 for($i=1; $i < 8; $i++){
					 
				if($currentdate==date('Y-m-d',strtotime($today))){
					 $today_start=date('Y-m-d 00:00:00',strtotime($today));
					 $today_end=date('Y-m-d 23:59:59',strtotime($today));
				$res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('D', strtotime($today));
				 $ODay = date('d', strtotime($today));
				 $data['days'][]=$nameOfDay.' '.$ODay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;
					}
				else{
					 $nameOfDay = date('D', strtotime($today));
					 $ODay = date('d', strtotime($today));
					 $data['days'][]=$nameOfDay.' '.$ODay;
					$data['complete_booking_value'][]=0;
				   $data['confirmed_booking_value'][]=0;
				    $data['total_booking_value'][]=0;
					}	
					 
					/*$data1=array("y"=>(int)$res->total,
						 "label" => $nameOfDay.' '.$ODay,
						 "color" => "#00b3bf" );
					$data[]=$data1;*/  
						
				 $today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
				}

			//$field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time> '".$startDate."' AND booking_time<= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time<= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where access='marchant' AND created_on > '".$startDate."' AND created_on<= '".$endDate."') as tot_merchant,(SELECT count(id) FROM st_users where access='user' AND created_on > '".$startDate."' AND created_on<= '".$endDate."') as tot_users";
		 
		//  $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
		  //echo $this->db->last_query();
		  //$data['sale_count'] = $sale_count;

		}
		else if($_POST['filter'] == 'last_sixmonth')
		{

			 $startDate=date("Y-m-01 00:00:00", strtotime(" -5 month"));
			 $endDate=date('Y-m-31 23:59:59');
			
			//$today = date("Y-m-d",$monday);	
			 for ($j = 5; $j > -1; $j--) {
					//echo date("F Y", strtotime(" -$j month"));
					$today= date("Y-m-d 00:00:00", strtotime(" -$j month"));
					//echo date("Y-m-d 00:00:00", strtotime(" -$j month"));
				
				 $today_start=date('Y-m-01 00:00:00',strtotime(" -$j month"));
				 $today_end=date('Y-m-31 23:59:59',strtotime(" -$j month"));
				 //$today_start=date('Y-m-d 00:00:00',strtotime(" -$j month"));
				 //$today_end=date('Y-m-d 23:59:59',strtotime(" -$j month"));
			$res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('M', strtotime($today));
				 $data['days'][]=$nameOfDay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;

				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 month", strtotime($today)));
			}
		
		 // $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		// $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		// $data['sale_count'] = $sale_count;


				// $start_date=date('Y-m-d', strtotime('-5 months'));
                // $end_date=date('Y-m-t');
		}
		else if($_POST['filter'] =='all'){

			$ress=$this->user->select_row('st_booking',"booking_time",array('id'=>1));
			if(!empty($ress->booking_time))
				$date1=date('Y-m-01',strtotime($ress->booking_time));
			else
				$date1 = date('Y-m-01');

			$date2 = date('Y-m-t');

			$ts1 = strtotime($date1);
			$ts2 = strtotime($date2);

			$year1 = date('Y', $ts1);
			$year2 = date('Y', $ts2);

			$month1 = date('m', $ts1);
			$month2 = date('m', $ts2);

			$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
			
			 $today   = $date1;
			 $maxDays = $diff+1;
			 
			 $startDate = date('Y-01-01 00:00:00');
			 $endDate   = date('Y-12-t 23:59:59');
			 
			  for($i=1; $i <= $maxDays; $i++){
				 $today_start=date('Y-m-d 00:00:00',strtotime($today));
				 $today_end=date('Y-m-t 23:59:59',strtotime($today));
			$res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('M', strtotime($today));
				 $data['days'][]=$nameOfDay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
					
			 $today=date('Y-m-d', strtotime("+1 month", strtotime($today)));
			}
			
		 // $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time> '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		 
		 //$sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
		  //echo $this->db->last_query();
			
		 // $data['sale_count'] = $sale_count;		
		}
		else
		{
			if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
					$date = DateTime::createFromFormat('d/m/Y', $_POST['start_date']);
					$start_date= $date->format('Y-m-d');
					$startDate= $date->format('Y-m-d');
					$date1 = DateTime::createFromFormat('d/m/Y', $_POST['end_date']);
					$end_date= $date1->format('Y-m-d');
					$endDate= $date1->format('Y-m-d');
					//$whr='AND DATE(created_on) >="'.$st_date.'" AND DATE(created_on) <="'.$ed_date.'"';
			    	//$whr1='AND DATE(updated_on) >="'.$st_date.'" AND Date(updated_on) <="'.$ed_date.'"';
							

			 //$startDate=date("Y-m-01 00:00:00", strtotime(" -5 month"));
			 //$endDate=date('Y-m-31 23:59:59');
			

			while (strtotime($start_date) <= strtotime($end_date)) {
			   

			//$today = date("Y-m-d",$monday);	
			 //for ($j = 5; $j > -1; $j--) {
					//echo date("F Y", strtotime(" -$j month"));
					//$today= date("Y-m-d 00:00:00", strtotime(" -$j month"));
					//echo date("Y-m-d 00:00:00", strtotime(" -$j month"));
				
				 $today_start=date($start_date.' 00:00:00',strtotime($start_date));
				 $today_end=date($start_date.' 23:59:59',strtotime($start_date));
				 //$today_start=date('Y-m-d 00:00:00',strtotime(" -$j month"));
				 //$today_end=date('Y-m-d 23:59:59',strtotime(" -$j month"));
               $res=$this->user->select_row('st_booking',"SUM(total_price) as total_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='confirmed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as confirm_booking_value,(SELECT SUM(total_price) FROM st_booking WHERE status='completed ' AND booking_time >='".$today_start."' AND booking_time <='".$today_end."') as completed_booking_value",array('booking_time >='=>$today_start,'booking_time<='=>$today_end));
			// echo $this->db->last_query(); die;
			 
				$nameOfDay = date('M', strtotime($today));
				 $data['days'][]=$nameOfDay;
				 $data['complete_booking_value'][]=(int)$res->completed_booking_value;
				 $data['confirmed_booking_value'][]=(int)$res->confirm_booking_value;
				 $data['total_booking_value'][]=(int)$res->total_booking_value;
				/*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/  
				
				 $start_date = date ("Y-m-d", strtotime("+1 month", strtotime($start_date)));
			}

			 //$today=date('Y-m-d', strtotime("+1 day", strtotime($today)));
			//}
		
		// $field2="id,(SELECT SUM(total_price) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where booking_time > '".$startDate."' AND booking_time <= '".$endDate."' AND status='completed') as tot_booking,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='marchant') as tot_merchant,(SELECT count(id) FROM st_users where created_on > '".$startDate."' AND created_on <= '".$endDate."' AND access='user') as tot_user";
		// $sale_count = $this->user->select_row('st_booking',$field2,array('booking_time >'=> $startDate, 'booking_time <=' => $endDate,'status'=>'completed'));	
			// echo $this->db->last_query();
		// $data['sale_count'] = $sale_count;

			}
				// $start_date=date('Y-m-d', strtotime('-5 months'));
                // $end_date=date('Y-m-t');
		}
		
   // echo '<pre>'; print_r($data); die;	  
	echo json_encode($data); die;
    }

	
}
?>
