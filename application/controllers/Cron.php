<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends Frontend_Controller
{

    public function __construct()
    {
        parent::__construct();

        require_once(FCPATH.'/stripe/init.php');
        // our test credential ankit sir id
        $stripe = array(
            "secret_key" => STRIPE_SK,
            "publishable_key" => STRIPE_PK,
        );
        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        //$this->load->model('Ion_auth_model','ion_auth');
    }

    //*** Get recently booking ***//
    public function get_recently_booking()
    {
        if (!empty($this->session->userdata('st_userid'))) {

            $userId = $this->session->userdata('st_userid');

            $query = $this->db->query('SELECT count(id) as totalneworder FROM st_booking WHERE merchant_id=' . $userId . ' AND seen_status=0');
            $data = $query->row();

            if (!empty($data->totalneworder)) {
                echo json_encode(['success' => 1, 'message' => $data->totalneworder . ' New booking']);
            } else {
                echo json_encode(['success' => 0, 'message' => '']);
            }
        } else {
            echo json_encode(['success' => 0, 'message' => '']);
        }
    }

    //*** Get recently Review ****//
    public function get_recently_review()
    {
        if (!empty($this->session->userdata('st_userid'))) {

            $userId = $this->session->userdata('st_userid');
            $query = $this->db->query('SELECT count(id) as totalreview FROM st_review WHERE merchant_id=' . $userId . ' AND user_id !="0" AND read_status="unread"');
            $data = $query->row();
            if (!empty($data->totalreview)) {
                $reviewCount = $data->totalreview;
            } else { $reviewCount = 0;}

            $query2 = $this->db->query('SELECT count(id) as totalneworder FROM st_booking WHERE merchant_id=' . $userId . ' AND seen_status=0 AND booking_type!="self"');
            $data2 = $query2->row();

            if (!empty($data2->totalneworder)) {
                $bookingCount = $data2->totalneworder;
            } else { $bookingCount = 0;}

            $query3 = $this->db->query('SELECT count(id) as totalcancel FROM st_booking_notification WHERE merchant_id=' . $userId . ' AND status ="cancel" AND view_status="0"');
            $data3 = $query3->row();

            if (!empty($data3->totalcancel)) {
                $cancelCount = $data3->totalcancel;
            } else { $cancelCount = 0;}

            echo json_encode(['success' => 1, 'count' => $reviewCount, 'bookingCount' => $bookingCount, 'cancelCount' => $cancelCount]);
        } else {
            echo json_encode(['success' => 0, 'count' => '']);
        }
    }

    public function run_all_crons()
    {
        $this->booking_notification();
        $this->block_holiday_calendar();
        $this->demo_expired_remider();
        $this->block_merchant_calendar_oneday();
    }

    public function booking_reminder_notification()
    {

        //$date = date('Y-m-d H:i:00');
        $startTime = date("Y-m-d H:i:s");
        $sql23 = "SELECT id,notification_time, additional_notification_time FROM `st_users` WHERE status = 'active' AND access ='marchant'";
        $resultUser = $this->user->custome_query($sql23, 'result');
        $userData = [];
        function array_push_assoc($array, $key, $value)
        {
            $array[$key] = $value;
            return $array;
        }
        if (!empty($resultUser)) {
            foreach ($resultUser as $key => $vala) {
                $userData = array_push_assoc($userData, $key, $vala);

            }
        }

        if ($userData) {
            $dataTimeNEW = date("Y-m-d h:i:s");
            $sql = "SELECT st_booking.id, st_users.additional_notification_times, AddTime(st_booking.booking_time,st_users.notification_times) as notiTime,AddTime(st_users.notification_times,'00:30:00') as sendnotiTime, ADDTIME(st_booking.booking_time,st_users.additional_notification_times) as addNotTime, st_booking.user_id, st_booking.merchant_id, st_booking.employee_id, st_booking.booking_time, st_booking.booking_endtime, st_booking.notification_date, st_users.notification_time, st_users.additional_notification_time, st_users.notification_times FROM st_booking INNER JOIN st_users ON st_users.id=st_booking.merchant_id WHERE st_booking.status='confirmed' AND st_booking.booking_type='user' AND st_booking.merchant_id IN(SELECT id FROM st_users) HAVING notiTime > sendnotiTime";
            $result = $this->user->custome_query($sql, 'result');
            //echo $this->db->last_query();die;
            //print_r($result);
            foreach ($result as $val) {
                $body_msg = "Booking Reminder";
                $MsgTitle = "Styletimer-Booking Reminder";
                sendPushNotification($val->user_id, array('body' => $body_msg, 'title' => $MsgTitle, 'salon_id' => $val->merchant_id, 'book_id' => $val->id, 'booking_status' => 'confirmed', 'click_action' => 'BOOKINGDETAIL'));
            }

        }

        //   if(!empty($resultUser)){
        //       $d=mktime(11, 14, 54, 8, 12, 2014);
        //      echo $NewDateTime= date("Y-m-d h:i:sa", $d);
        //   }

        //     $date = date('Y-m-d H:i:00');

        //     $sql   = "SELECT id,user_id,merchant_id,employee_id,booking_time,booking_endtime,notification_date
        //     FROM st_booking WHERE status='confirmed' AND (notification_date='".$date."' OR additional_notification_date='".$date."') AND booking_type='user' ORDER BY id ASC";

        //     $result  = $this->user->custome_query($sql,'result');
        //     echo       $this->db->last_query();die;
        //     print_r($result);die;
        // if(!empty($result)){

        //     $body_msg="Booking Reminder";
        //     $MsgTitle="Styletimer-Booking Reminder";
        //     foreach($result as $val){
        //     sendPushNotification($val->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$val->merchant_id ,'book_id'=> $val->id, 'booking_status' => 'confirmed','click_action' => 'BOOKINGDETAIL'));
        //     }
        //echo json_encode(['success'=>1,'result'=>$result]);
        //}

        /*$NewDate=date('Y-m-d H:i:s', strtotime("-3 days"));
        $this->user->update('st_booking',array('status'=>'completed','updated_on'=>date('Y-m-d H:i:s')),array('status'=>'confirmed','booking_time<=' => $NewDate));*/

        //  echo $NewDate; die;
        //else
        //echo json_encode(['success'=>0,'result'=>'']);
    }

    //*** Get recently Review ****//
    public function booking_notification()
    {

        $date = date('Y-m-d H:i:00');
        // // $userId=$this->session->userdata('st_userid');
        //     $dataUser = $this->session->userdata('access');
        //     print_r($dataUser);
        //  echo "hekk";
        //  print_r($userId);
        //   $sql23 = "SELECT hr_before_cancel,notification_date FROM `st_users` WHERE status = 'active' AND access ='marchant'";
        //   $resultUser  = $this->user->custome_query($sql23,'result');
        //   if(!empty($resultUser)){
        //       $d=mktime(11, 14, 54, 8, 12, 2014);
        //      echo $NewDateTime= date("Y-m-d h:i:sa", $d);
        //   }
        // echo  $this->db->last_query();die;
        //print_r($resultUser);die;
        //print_r($date);die;
        //echo "hello";die;
        //$date = date('Y-m-d H:i:00');
        //$newdate=strtotime('+15 minutes', strtotime($date));
        //$datenext=date("Y-m-d H:i:s", $newdate);
        //$result=$this->user->select('st_booking','id,user_id,merchant_id,employee_id,booking_time,booking_endtime,notification_date',array('status'=>'confirmed','notification_date' => $date,'booking_type' => 'user'),'','id','ASC');

        $sql = "SELECT id,user_id,merchant_id,employee_id,booking_time,booking_endtime,notification_date
			FROM st_booking WHERE status='confirmed' AND (notification_date='" . $date . "' OR additional_notification_date='" . $date . "') AND booking_type='user' ORDER BY id ASC";

        $result = $this->user->custome_query($sql, 'result');
        // echo       $this->db->last_query();die;
        // print_r($result);die;
        if (!empty($result)) {
            //Erinnerung an deinen Termin am 'date' um 'time' bei 'salon name'
            $body_msg = "Booking Reminder";
            $MsgTitle = "Styletimer-Booking Reminder";
            foreach ($result as $val) {
                sendPushNotification($val->user_id, array('body' => $body_msg, 'title' => $MsgTitle, 'salon_id' => $val->merchant_id, 'book_id' => $val->id, 'booking_status' => 'confirmed', 'click_action' => 'BOOKINGDETAIL'));
            }
            //echo json_encode(['success'=>1,'result'=>$result]);
        }

        /*$NewDate=date('Y-m-d H:i:s', strtotime("-3 days"));
        $this->user->update('st_booking',array('status'=>'completed','updated_on'=>date('Y-m-d H:i:s')),array('status'=>'confirmed','booking_time<=' => $NewDate));*/

        //  echo $NewDate; die;
        //else
        //echo json_encode(['success'=>0,'result'=>'']);
    }

    public function block_holiday_calendar()
    {
        $fromdate = date("Y-m-d");
        $todate = date("Y-m-d", strtotime("+6 months"));

        $fromYear = date('Y', strtotime($fromdate));
        $fromMonth = date('m', strtotime($fromdate));
        $toYear = date('Y', strtotime($todate));
        $toMonth = date('m', strtotime($fromdate));

        $lastHoliday = $fromdate;

        $query = $this->db->query("SELECT * FROM `st_national_holidays` ORDER BY `st_national_holidays`.`date` DESC LIMIT 1");
        $last_row = $query->row();

        // if (isset($last_row)) {
        //     $lastHoliday = $last_row->date;
        // }

        $holidays = array();

        $result = getholidays($fromYear);

        if (!empty($result->feiertage)) {
            foreach ($result->feiertage as $row) {
                if (strtotime($row->date) > strtotime($lastHoliday)
                    && strtotime($row->date) < strtotime($todate) && $row->all_states == 1) {

                    $inserArray = array();
                    $inserArray['name'] = $row->fname;
                    $inserArray['year'] = $fromYear;
                    $inserArray['date'] = $row->date;
                    $inserArray['description'] = $row->comment;

                    $holidays[] = $inserArray;
                }
            }
        }

        if ($fromYear != $toYear) {
            $result = getholidays($toYear);

            if (!empty($result->feiertage)) {
                foreach ($result->feiertage as $row) {
                    if (strtotime($row->date) > strtotime($lastHoliday)
                        && strtotime($row->date) < strtotime($todate) && $row->all_states == 1) {

                        $inserArray = array();
                        $inserArray['name'] = $row->fname;
                        $inserArray['year'] = $toYear;
                        $inserArray['date'] = $row->date;
                        $inserArray['description'] = $row->comment;

                        $holidays[] = $inserArray;
                    }
                }
            }
        }

        foreach ($holidays as $holiday) {
            $date = $holiday['date'];
            $query = $this->db->query("SELECT * FROM `st_national_holidays` WHERE `st_national_holidays`.`date`='${date}'");
            $rows = $query->result();
            if (empty($rows)) {
                $res = $this->user->insert('st_national_holidays', $holiday);
            }
        }

        // print_r($holidays);
    }

    public function demo_expired_remider()
    {

        $todaydate = date('Y-m-d H:i:s');
        $newdate = strtotime('+7 days', strtotime($todaydate));
        $startdate = date("Y-m-d 00:00:00", $newdate);
        $enddate = date("Y-m-d 25:59:59", $newdate);
        $checkMerchant = $this->user->select('st_users', 'id,first_name,last_name,email', array('status' => 'active', 'access' => 'marchant', 'subscription_status' => 'trial', 'end_date >=' => $startdate, 'end_date <=' => $enddate));

        if (!empty($checkMerchant)) {
            foreach ($checkMerchant as $row) {
                $this->data['name'] = $row->first_name;
                $message = $this->load->view('email/forgot_password',
                    array('link' => base_url("membership"),
                        "name" => ucwords($row->first_name), "button" => "Jetzt Mitgliedschaft sichern"), true);
                $mail = emailsend($row->email, 'Styletimer - Trial period expiration.', $message, 'styletimer');

            }
        }

        //echo $startdate.'==='.$enddate.'<pre>'; print_r($checkMerchant);

    }

    public function block_merchant_calendar()
    {
        $date = date("Y-m-d");
        $edate = strtotime(date("Y-m-d", strtotime($date)) . " +6 month");
        $edate = date("Y-m-d", $edate);
        //echo $date;
        $this->db->group_by('date');
        $getSixmonthHoliday = $this->user->select('st_national_holidays', '*', array('date >=' => $date, 'date <' => $edate));

        $merchants = $this->user->select('st_users', 'id', array('access' => 'marchant', 'status !=' => 'deleted'));

        // echo $this->db->last_query()."<pre>"; print_r($merchants); die;
        if (!empty($merchants) && !empty($getSixmonthHoliday)) {
            foreach ($merchants as $row) {
                $employee = $this->user->select('st_users', 'id', array('access' => 'employee', 'status !=' => 'deleted', 'merchant_id' => $row->id));
                if (!empty($employee)) {
                    foreach ($getSixmonthHoliday as $holiday) {$i = 0;
                        $blockId = 0;
                        foreach ($employee as $erow) {

                            $inserArray = array();
                            $inserArray['merchant_id'] = $row->id;
                            $inserArray['employee_id'] = $erow->id;
                            $inserArray['notes'] = isset($holiday->name) ? $holiday->name : "";
                            $inserArray['booking_time'] = $holiday->date . " 00:00:00";
                            $inserArray['booking_endtime'] = $holiday->date . " 23:00:00";
                            $inserArray['booking_type'] = 'self';
                            $inserArray['blocked'] = $blockId;
                            $inserArray['blocked_perent'] = $blockId;
                            $inserArray['national_holiday'] = 1;

                            $res = $this->user->insert('st_booking', $inserArray);
                            if ($blockId == 0) {

                                $blockId = $res;

                                $this->user->update('st_booking', array('blocked' => $res), array('id' => $res));
                            }
                        }

                    }

                }
            }
        }

    }

    public function block_merchant_calendar_oneday()
    {
        $date = date("Y-m-d");
        $edate = date('Y-m-d', strtotime($date . ' +6 month'));
        $this->db->group_by('date');
        $getSixmonthHoliday = $this->user->select('st_national_holidays', '*', array('date >' => $date, 'date <=' => $edate));
        $merchants = $this->user->select('st_users', 'id', array('access' => 'marchant', 'status !=' => 'deleted'));

        if (!empty($merchants) && !empty($getSixmonthHoliday)) {
            foreach ($merchants as $row) {
                $employee = $this->user->select('st_users', 'id', array('access' => 'employee', 'status !=' => 'deleted', 'merchant_id' => $row->id));
                if (!empty($employee)) {
                    foreach ($getSixmonthHoliday as $holiday) {$i = 0;
                        $blockId = 0;
                        foreach ($employee as $erow) {

                            $exist = $this->user->select('st_booking', 'id', array('national_holiday' => 1, 'booking_time' => $holiday->date . " 00:00:00", 'booking_endtime' => $holiday->date . " 23:00:00", 'merchant_id' => $row->id, 'employee_id' => $erow->id));

                            if (empty($exist)) {
                                $inserArray = array();
                                $inserArray['merchant_id'] = $row->id;
                                $inserArray['employee_id'] = $erow->id;
                                $inserArray['notes'] = isset($holiday->name) ? $holiday->name : "";
                                $inserArray['booking_time'] = $holiday->date . " 00:00:00";
                                $inserArray['booking_endtime'] = $holiday->date . " 23:00:00";
                                $inserArray['booking_type'] = 'self';
                                $inserArray['blocked'] = $blockId;
                                $inserArray['blocked_perent'] = $blockId;
                                $inserArray['national_holiday'] = 1;
                                $inserArray['blocked_type'] = 'close';
                                $inserArray['status'] = 'confirmed';

                                $res = $this->user->insert('st_booking', $inserArray);
                                if ($blockId == 0) {

                                    $blockId = $res;

                                    $this->user->update('st_booking', array('blocked' => $res), array('id' => $res));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function webhook_responce()
    {
        ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
        
        // $endpoint_secret = 'whsec_SFkLMgiDvKeJEh7TJGmdv435wjxJGqQf';
        $endpoint_secret = WEBHOOK_KEY;
        $payload = @file_get_contents('php://input');
        
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        if ($event->type == 'checkout.session.completed') {
            $event_data = $event->__toArray(true);
            $data = $event_data['data']['object'];

            if ($data['mode'] == 'subscription') {
                $user = $this->user->select_row('st_users', '*', array('email' => $data['customer_email']));

                $customer = \Stripe\Customer::update(
                    $data['customer'],
                    array(
                        'description' => $user->business_type,
                        'address' => [
                            'city' => $user->city,
                            'country' => $user->country,
                            'postal_code' => $user->zip,
                            'line1' => $user->address,
                        ],
                        'name' => $user->business_name.'('.$user->first_name.' '.$user->last_name.')',
                        'phone' => $user->mobile
                    )
                );

                $price = $data['amount_total'];

                $subobj = \Stripe\Subscription::retrieve($data['subscription']);
                $plan = $subobj['items']['data'][0]['price']['id'];

                if ($user->first_plan_price) {
                    $res=$this->user->update('st_users',array("subscription_id"=>$data['subscription'],'plan_id'=>$plan,'subscription_status'=>'active'),array('id' =>$user->id));
                } else {
                    $res=$this->user->update('st_users',array("subscription_id"=>$data['subscription'],'plan_id'=>$plan,'subscription_status'=>'active','first_plan_price'=>$price,'first_plan_date'=>date('Y-m-d H:i:s')),array('id' =>$user->id));
                }
            } else if ($data['mode'] == 'setup') {
                $setupIntentId = $data['setup_intent'];

                if ($setupIntentId) {
                    $url = "https://api.stripe.com/v1/setup_intents/".$setupIntentId;

                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                    $headers = array(
                        "Authorization: Basic ".CURL_SK,
                    );
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                    $resp = curl_exec($curl);
                    curl_close($curl);
                    $res = json_decode($resp);

                    $pmid = $res->payment_method;
                    if ($pmid) {
                        $user = $this->user->select_row('st_users', '*', array('stripe_id' => $data['customer']));
                        if ($user->subscription_id) {
                            $subscription= \Stripe\Subscription::update(
                                $user->subscription_id,[
                                    'default_payment_method' => $pmid,
                                ]
                            );

                            $res=$this->user->update('st_users',array("card_id"=>$pmid),array('id' =>$user->id));

                        }
                    }
                }
            }
        }
        if ($event->type == 'invoice.paid') {

            $event_data = $event->__toArray(true);

            $subscription_data = $event_data['data']['object'];
            // echo "<pre>"; print_r($subscription_data); die;

            $billing_reason = $subscription_data['billing_reason'];
            $objId = 0;
            if ($billing_reason == 'subscription_update') {
                $objId = 1;
            }

            $start = date('Y-m-d H:i:s', $subscription_data['lines']['data'][$objId]['period']['start']);
            $end = date('Y-m-d H:i:s', $subscription_data['lines']['data'][$objId]['period']['end']);

            $c_time = time();
            $new_start = date('Y-m-d H:i:s', $c_time);
            $new_end = date('Y-m-d H:i:s', $c_time + $subscription_data['lines']['data'][$objId]['period']['end'] - $subscription_data['lines']['data'][$objId]['period']['start']);


            $invoice_id = $subscription_data['id'];
            //start_date end_date
            $invoice_url = $subscription_data['hosted_invoice_url'];
            $invoice_pdf_url = $subscription_data['invoice_pdf'];
            $strip_id = $subscription_data['customer'];
            $status = $subscription_data['status'];
            $amount = $subscription_data['amount_paid'] / 100;
            $subscriptionid = $subscription_data['lines']['data'][$objId]['subscription'];
            $planid = $subscription_data['lines']['data'][$objId]['plan']['id'];
            $charge = $subscription_data['charge'];
            /******************************/
            // $trnsction=$this->user->select_row('st_payments','id',array('transuction_id' =>$transactionId));

            $ins = $this->db->query("UPDATE `st_payments` SET amount='" . $amount . "',`start_date`='" . $start . "',`end_date`='" . $end . "',`invoice_url`='" . $invoice_url . "',`invoice_pdf_url`='" . $invoice_pdf_url . "',status='" . $status . "', subscription_id='". $subscriptionid ."', `charge_id`='".$charge."', plan_id='".$planid."' WHERE invoice='" . $invoice_id . "' AND stripe_id='" . $strip_id . "'");

            if ($amount > 0 || $billing_reason == 'subscription_update') {
                $ins = $this->db->query("UPDATE `st_users` SET `start_date`='" . $new_start . "',`end_date`='" . $new_end . "',`subscription_status`='active' WHERE stripe_id='" . $strip_id . "'");
            } else {
                $ins = $this->db->query("UPDATE `st_users` SET `subscription_status`='active' WHERE stripe_id='" . $strip_id . "'");
            }
            /*******************************/

        }
        if ($event->type == 'charge.dispute.funds_withdrawn') {

            $event_data = $event->__toArray(true);

            $subscription_data = $event_data['data']['object'];
            //echo "<pre>"; print_r($subscription_data); die;

            // $sid = $subscription_data['id'];
            // $type = $subscription_data['payment_method_details']['type'];
            $type = '';
            $charge = $subscription_data['charge'];
            $status = 'disputed';

            $c_time = time();
            $new_end = date('Y-m-d H:i:s', $c_time);

            /******************************/
            $trnsction = $this->user->select_row('st_payments', 'id,user_id', array('charge_id' => $charge));

            if (!empty($trnsction)) {
                $ins = $this->db->query("UPDATE `st_payments` SET `status`='" . $status . "' WHERE charge_id='" . $charge . "'");

                $user = $this->user->select_row('st_users', '*', array('id' => $trnsction->user_id));
                $this->db->query("UPDATE `st_users` SET `end_date`='".$new_end."', `online_booking`='0', `subscription_status`='payment_failed' WHERE id='" . $user->id . "'");
            }
        }
        if ($event->type == 'charge.succeeded') {
            $event_data = $event->__toArray(true);
            $subscription_data = $event_data['data']['object'];
            $type = $subscription_data['payment_method_details']['type'];
            $pid = $subscription_data['id'];
            $cid = $subscription_data['customer'];
            $paymentid = $subscription_data['payment_method'];
            $invoice_id = $subscription_data['invoice'];
            $status = $subscription_data['status'];
            $create_time = date('Y-m-d', $subscription_data['created']);
            $amount = $subscription_data['amount'] / 100;
            $currency = $subscription_data['currency'];
            $transactionId = $subscription_data['balance_transaction'];
            $email = $subscription_data['billing_details']['email'];
            /******************************/
            $trnsction = $this->user->select_row('st_payments', 'id', array('transuction_id' => $transactionId));

            if (!empty($trnsction)) {
                $ins = $this->db->query("UPDATE `st_payments` SET `status`='" . $status . "' WHERE transuction_id='" . $transactionId . "'");
            } else {
                $user = $this->user->select_row('st_users', 'id,subscription_id,plan_id', array('email' => $email));
                $invoicecheck = $this->user->select_row('st_payments', 'id', array('invoice' => $invoice_id));

                $res=$this->user->update('st_users',array("stripe_id"=>$cid,'card_id'=>$paymentid),array('email' =>$email));

                if (empty($invoicecheck->id)) {
                    $ins = $this->db->query("INSERT INTO `st_payments`( `user_id`, `stripe_id`, `transuction_id`, `amount`, `currency`,`invoice`,`status`,`type`,`created_on`,`created_by`) VALUES ('{$user->id}','{$cid}','{$transactionId}','{$amount}','{$currency}','{$invoice_id}','{$status}','{$type}','{$create_time}','{$user->id}')");
                } else {
                    $ins = $this->db->query("UPDATE `st_payments` SET `status`='" . $status . "',transuction_id='" . $transactionId . "',amount='".$amount."',currency='".$currency."',type='" . $type . "' WHERE invoice='" . $invoice_id . "'");

                }
            }

            /*******************************/

        }
        if ($event->type == 'charge.pending') {
            $event_data = $event->__toArray(true);
            $subscription_data = $event_data['data']['object'];
            $type = '';
            $pid = $subscription_data['id'];
            $cid = $subscription_data['customer'];
            $paymentid = $subscription_data['payment_method'];
            $invoice_id = $subscription_data['invoice'];
            $status = $subscription_data['status'];
            $create_time = date('Y-m-d', $subscription_data['created']);
            $amount = $subscription_data['amount'] / 100;
            $currency = $subscription_data['currency'];
            $transactionId = $subscription_data['balance_transaction'];
            $email = $subscription_data['billing_details']['email'];
            /******************************/
            $trnsction = $this->user->select_row('st_payments', 'id', array('transuction_id' => $transactionId));

            if (!empty($trnsction)) {
                $ins = $this->db->query("UPDATE `st_payments` SET `status`='" . $status . "' WHERE transuction_id='" . $transactionId . "'");
            } else {
                $user = $this->user->select_row('st_users', 'id,subscription_id,plan_id', array('email' => $email));
                $res=$this->user->update('st_users',array("stripe_id"=>$cid,'card_id'=>$paymentid),array('email' =>$email));

                $ins = $this->db->query("INSERT INTO `st_payments`( `user_id`, `stripe_id`, `transuction_id`,`amount`, `currency`,`invoice`,`status`,`type`,`created_on`,`created_by`) VALUES ('{$user->id}','{$cid}','{$transactionId}','{$amount}','{$currency}','{$invoice_id}','{$status}','{$type}','{$create_time}','{$user->id}')");

            }
            //$r = $conn->query($s);
            /*******************************/

        }
        if ($event->type == 'invoice.payment_failed') {
            $event_data = $event->__toArray(true);

            $subscription_data = $event_data['data']['object'];
            // echo "<pre>"; print_r($subscription_data); die;
            $start = date('Y-m-d H:i:s', $subscription_data['lines']['data'][0]['period']['start']);
            $end = date('Y-m-d H:i:s', $subscription_data['lines']['data'][0]['period']['end']);
            
            $c_time = time();
            $new_end = date('Y-m-d H:i:s', $c_time);

            $invoice_id = $subscription_data['id'];
            //start_date end_date
            $invoice_url = $subscription_data['hosted_invoice_url'];
            $invoice_pdf_url = $subscription_data['invoice_pdf'];
            $strip_id = $subscription_data['customer'];
            $status = $subscription_data['status'];
            $amount = $subscription_data['amount_paid'] / 100;
            /******************************/
            // $trnsction=$this->user->select_row('st_payments','id',array('transuction_id' =>$transactionId));

            $ins = $this->db->query("UPDATE `st_payments` SET amount='" . $amount . "',`start_date`='" . $start . "',`end_date`='" . $end . "',`invoice_url`='" . $invoice_url . "',`invoice_pdf_url`='" . $invoice_pdf_url . "',status='" . $status . "' WHERE invoice='" . $invoice_id . "' AND stripe_id='" . $strip_id . "'");

            $ins = $this->db->query("UPDATE `st_users` SET `end_date`='".$new_end."', `online_booking`='0', `subscription_status`='payment_failed' WHERE stripe_id='" . $strip_id . "'");
        }
        if ($event->type == 'charge.failed') {

            $event_data = $event->__toArray(true);

            $subscription_data = $event_data['data']['object'];
            //echo "<pre>"; print_r($subscription_data); die;

            // $sid = $subscription_data['id'];
            // $type = $subscription_data['payment_method_details']['type'];
            $type = '';
            $pid = $subscription_data['id'];
            $cid = $subscription_data['customer'];
            $invoice_id = $subscription_data['invoice'];
            $status = $subscription_data['status'];
            $create_time = date('Y-m-d', $subscription_data['created']);
            $amount = $subscription_data['amount'] / 100;
            $currency = $subscription_data['currency'];
            $transactionId = $subscription_data['balance_transaction'];

            if ($transactionId) {
                /******************************/
                $trnsction = $this->user->select_row('st_payments', 'id', array('transuction_id' => $transactionId));

                if (!empty($trnsction)) {
                    $ins = $this->db->query("UPDATE `st_payments` SET `status`='" . $status . "' WHERE transuction_id='" . $transactionId . "'");
                } else {
                    $user = $this->user->select_row('st_users', 'id', array('stripe_id' => $cid));

                    $ins = $this->db->query("INSERT INTO `st_payments`( `user_id`, `stripe_id`, `transuction_id`, `amount`, `currency`,`invoice`,`status`,`type`,`created_on`,`created_by`) VALUES ('{$user->id}','{$cid}','{$transactionId}','{$amount}','{$currency}','{$invoice_id}','{$status}','{$type}','{$create_time}','{$user->id}')");

                }
                //$r = $conn->query($s);
                /*******************************/
            }
        }
        
        http_response_code(200);
    }

	public function test_email() {

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => getenv('SMTP_HOST'),
            'smtp_port' => getenv('SMTP_PORT'),
            'smtp_user' => getenv('SMTP_USER'),
            'smtp_pass' => getenv('SMTP_PASS'),
            'mailtype' => 'html',
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );

		$this->load->library('email');
        
        $this->email->initialize($config);
		$this->email->from('info@styletimer.de', 'Atos Dev');
		$this->email->to('coronarider01@gmail.com');
		
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');
		
		if ($this->email->send()){
			echo 'Your e-mail has been sent';
	   }         
	   else{
		   show_error($this->email->print_debugger());
	   }
	}	
}
