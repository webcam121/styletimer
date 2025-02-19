<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api2 extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('api_model'); // one model for all api
        $this->load->helper('api_helper');

        $this->lang->load('api_res_msg', 'german');
        $this->lang->load('salon_dashboard', 'german');

    }

    public $response_data = array('status' => 0, 'access_token' => '', 'response_message' => '', 'data' => []);

    // salon serchfor home page by salone name
    public function salon_list_post()
    {
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('api_key', 'Key', 'required|trim');
        $this->form_validation->set_rules('keys', 'Keys', 'required|trim');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            extract($_POST);
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {
                $date = date('Y-m-d H:i:s');

                $query = $this->db->query("SELECT id,business_name,address,zip,country,city,(SELECT count(st_review.id) FROM st_review WHERE st_review.merchant_id = st_users.id AND user_id !=0) as reviewcount,IFNULL((SELECT AVG(st_review.rate) FROM st_review WHERE st_review.merchant_id = st_users.id AND user_id !=0),'0') as rating FROM st_users WHERE status='active' AND subscription_status!='cancel' AND subscription_status!='payment_failed' AND end_date>='" . $date . "' AND online_booking='1' AND business_name LIKE '%" . trim($_POST['keys']) . "%' LIMIT 20");

                $result = $query->result();

                if (empty($result)) {
                    $result = array();
                }

                // echo $this->db->last_query();
                // $result=$query->result();
                $this->response_data['status'] = 1;
                $this->response_data['response_message'] = 'success';
                $this->response_data['data'] = $result;
            }
        }
        echo json_encode($this->response_data);
    }

// if no salon is found in search contact mail send for admin
    public function contact_for_salon_post()
    {
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('api_key', 'Key', 'required|trim');
        $this->form_validation->set_rules('name', 'name', 'required|trim');
        $this->form_validation->set_rules('salon_name', 'salone name', 'required|trim');
        $this->form_validation->set_rules('salon_city', 'salone city', 'required|trim');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            extract($_POST);
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {extract($_POST);

                $date = date('Y-m-d H:i:s');
                $userid = 0;
                $email = isset($_POST['email']) && $_POST['email'] !== '' && !is_null($_POST['email']) ? $_POST['email'] : null;
                if (!empty($uid)) {
                    $userid = $uid;
                }

                $query = $this->db->query("INSERT INTO st_contactforsalon(`name`,`email`,`salon_name`,`salon_city`,`created_by`,`created_on`,`via`) VALUES('" . $name . "','" . $email . "','" . $salon_name . "','" . $salon_city . "','" . $userid . "','" . $date . "'," . $device_type . ")");
                $_POST['date'] = $date;
                $datasend['data'] = $_POST;
                $datasend['data']['email'] = $email;

                $message = $this->load->view('email/salon_contact', $datasend, true);

                $mail = emailsend('mario80853@googlemail.com', 'New Salon request', $message, 'styletimer'); //OFFICE_EMAIL

                $this->response_data['status'] = 1;
                $this->response_data['response_message'] = $this->lang->line('contact_for_new_salon_scs_msg');

            }
        }
        echo json_encode($this->response_data);
    }

    // category and sub category search
    public function category_subcategory_search_post()
    {
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('api_key', 'Key', 'required|trim');
        //$this->form_validation->set_rules('keys', 'Keys', 'required|trim');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            extract($_POST);
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {
                $submenus = get_filter_menu();
                $mkeys = $_POST['keys'];
                $result = array();
                foreach ($submenus as $menu) {
                    if (!$mkeys) {
                        $result[] = $menu;
                    } else if (strpos(
                        strtolower($menu['category_name']),
                        strtolower($mkeys)
                    ) !== false) {
                        $result[] = $menu;
                    }
                }

                if (empty($result)) {
                    $result = array();
                    $this->response_data['status'] = 0;
                    $this->response_data['response_message'] = 'No record found...!';
                } else {
                    $this->response_data['status'] = 1;
                    $this->response_data['response_message'] = 'success';
                }

                $this->response_data['data'] = $result;
            }
        }
        echo json_encode($this->response_data);
    }

    public function getsalon_servicedetail_post()
    {

        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('api_key', 'Key', 'required|trim');
        //$this->form_validation->set_rules('keys', 'Keys', 'required|trim');
        $this->form_validation->set_rules('id', 'Id', 'required|trim');
        $this->form_validation->set_rules('allid', 'All id', 'required|trim');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {

            extract($_POST);

            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {
                $result = $this->api_model->select_row('st_merchant_category', 'id,service_detail,subcategory_id,(SELECT category_name from st_category WHERE st_category.id = subcategory_id) as cat_name', array('id' => $id));

                if (!empty($result)) {

                    $this->response_data['status'] = 1;
                    $this->response_data['data'] = $result;

                    //$allids = explode(',',$_POST['allid']);

                    $serviceReviewQuery = 'SELECT bd.id,bd.booking_id,bd.emp_id,rate,review,anonymous,rv.created_on,rv.id as revid,rv.merchant_id,(SELECT concat(first_name," ",last_name) FROM st_users WHERE id=bd.emp_id) as employee,(SELECT concat(first_name," ",last_name) FROM st_users WHERE id=bd.user_id) as username FROM st_booking_detail as bd INNER JOIN st_review as rv ON bd.booking_id=rv.booking_id WHERE bd.service_id IN (' . $allid . ') ORDER BY rv.id desc LIMIT 20';

                    $reviews = $this->api_model->custome_query($serviceReviewQuery);
                    //echo $this->db->last_query().'<pre>'; print_r($reviews); die;

                    if (!empty($reviews)) {$i = 0;
                        foreach ($reviews as $row) {
                            $m_reply = getselect('st_review', 'id,review', array('review_id' => $row->revid, 'created_by' => $row->merchant_id));
                            $revs = [];
                            if (!empty($m_reply)) {foreach ($m_reply as $rep) {
                                $revs[] = $rep->review;
                            }}
                            $reviews[$i]->time_ago = time_passed(strtotime($row->created_on));
                            $reviews[$i]->salon_reviews = $revs;
                            $i++;
                        }
                        $this->response_data['data']->reviews = $reviews;

                        $serviceReviewQueryAVG = 'SELECT AVG(rate) as avgrage,count(rv.id) as totalcount FROM st_booking_detail as bd INNER JOIN st_review as rv ON bd.booking_id=rv.booking_id WHERE bd.service_id IN (' . $allid . ')';
                        $avgRating = $this->api_model->custome_query($serviceReviewQueryAVG, 'row');
                        if (!empty($avgRating)) {

                            $this->response_data['data']->avgrate_count = $avgRating;
                        }

                    } else {

                        $blanArr = array('avgrage' => 0, 'totalcount' => 0);
                        $this->response_data['data']->reviews = array();
                        $this->response_data['data']->avgrate_count = $blanArr;

                    }

                }

            }

        }
        echo json_encode($this->response_data);
    }

    public function salon_calendar_slots_post()
    {
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('api_key', 'Key', 'required|trim');
        //$this->form_validation->set_rules('keys', 'Keys', 'required|trim');
        $this->form_validation->set_rules('salon_id', 'Salon Id', 'required|trim');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            extract($_POST);
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {
                $currentdate = date('Y-m-d 00:00:00');
                $threemonthaftr = date('Y-m-d 23:59:59', strtotime("+3 months", strtotime($currentdate)));
                $this->db->group_by('booking_time');
                $nationalholidaysdays = $this->api_model->getWhereAllselect('st_booking', array('booking_time >=' => $currentdate, 'booking_endtime <=' => $threemonthaftr, 'merchant_id' => $_POST['salon_id'], 'booking_type' => 'self', 'national_holiday' => 1), "booking_time");

                $nationalDays = array();
                if (!empty($nationalholidaysdays)) {
                    foreach ($nationalholidaysdays as $value) {
                        $nationalDays[] = date('Y-m-d', strtotime($value->booking_time));
                    }
                }

                if (!empty($employeeid) && $employeeid != 'any') {
                    $this->db->group_by('booking_time');
                    $blockdays = $this->api_model->getWhereAllselect('st_booking', array('booking_time >=' => $currentdate, 'booking_endtime <=' => $threemonthaftr, 'employee_id' => $employeeid, 'booking_type' => 'self', 'blocked_type' => 'full'), "booking_time");
                } else {
                    $this->db->group_by('booking_time');
                    $blockdays = $this->api_model->getWhereAllselect('st_booking', array('booking_time >=' => $currentdate, 'booking_endtime <=' => $threemonthaftr, 'merchant_id' => $_POST['salon_id'], 'booking_type' => 'self', 'blocked_type' => 'full', 'block_for' => 1), "booking_time");
                }

                if (!empty($blockdays)) {
                    foreach ($blockdays as $value) {
                        $nationalDays[] = date('Y-m-d', strtotime($value->booking_time));
                    }
                }

                $whereaas = "merchant_id=" . $_POST['salon_id'] . " AND booking_type='self' AND ((booking_time>='" . $currentdate . "' AND booking_time<'" . $threemonthaftr . "') OR (booking_endtime>'" . $currentdate . "' AND booking_endtime<='" . $threemonthaftr . "') OR (booking_time<='" . $currentdate . "' AND booking_endtime>'" . $currentdate . "') OR (booking_time>'" . $currentdate . "' AND booking_endtime<='" . $threemonthaftr . "'))";

                if (!empty($employeeid) && $employeeid != 'any') {
                    $this->db->where('employee_id', $employeeid);
                } else {
                    $this->db->where('close_for', 0);
                }

                $this->db->where($whereaas);
                $this->db->group_by('booking_time');
                $closedays = $this->api_model->getWhereAllselect('st_booking', array('blocked_type' => 'close'), 'booking_time,booking_endtime');
                // echo $this->db->last_query(); die;
                if (!empty($closedays)) {
                    foreach ($closedays as $value) {
                        $dates = getDatesFromRange(date('Y-m-d', strtotime($value->booking_time)), date('Y-m-d', strtotime($value->booking_endtime)));
                        //$dates =getDatesFromRange('2020-07-23','2020-07-23');
                        foreach ($dates as $date) {
                            $nationalDays[] = $date;
                        }
                        // echo '<pre>'; print_r($dates);
                        //$nationalDays[] = date('Y-m-d',strtotime($value->booking_time));
                    }
                }

                $activeday = [];
                // if (!empty($employeeid) && $employeeid != 'any') {
                //     $activeday = $this->api_model->getWhereAllselect('st_availability', array('user_id' => $employeeid, 'type' => 'close'), "days");
                // } else {
                    $activeday = $this->api_model->getWhereAllselect('st_availability', array('user_id' => $_POST['salon_id'], 'type' => 'close'), "days");
                // }

                // print_r($activeday);
                $days = array();
                if (!empty($activeday)) {
                    foreach ($activeday as $row) {
                        if ($row->days == 'monday') {
                            $days[] = 'Mon';
                        } else if ($row->days == 'tuesday') {
                            $days[] = 'Tue';
                        } else if ($row->days == 'wednesday') {
                            $days[] = 'Wed';
                        } else if ($row->days == 'thursday') {
                            $days[] = 'Thu';
                        } else if ($row->days == 'friday') {
                            $days[] = 'Fri';
                        } else if ($row->days == 'saturday') {
                            $days[] = 'Sat';
                        } else if ($row->days == 'sunday') {
                            $days[] = 'Sun';
                        }
                    }
                }
                //$this->data['days']  = $days;

                //$this->response_data['data']=array();
                if (!empty($nationalDays) || !empty($days)) {
                    //$tags = implode(',', $nationalDays);
                    //$tags2 = implode(',', $days);

                    $this->response_data['status'] = 1;
                    $this->response_data['response_message'] = 'success';
                    $this->response_data['data']['nationalDays'] = $nationalDays;
                    $this->response_data['data']['days'] = $days;
                } else {
                    $this->response_data['status'] = 0;
                    $this->response_data['response_message'] = 'No record found...!';
                    //$this->response_data['data']->array();
                    $this->response_data['data'] = array();
                }

                //$this->response_data['data']=$nationalDays;
            }
        }
        echo json_encode($this->response_data);
    }
    public function update_newslatter_post()
    {
        $this->form_validation->set_rules('uid', 'User id', 'required|trim');
        $this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('action', 'action', 'required|trim|in_list[view,edit]');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {

                extract($_POST);
                $uid = (isset($uid)) ? $uid : '';
                $is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

                if (empty($is_valid_user->access_token)) {
                    $this->response_data['response_message'] = INVALID_TOKEN_MSG;
                    // delete access token
                    $this->api_model->access_token_delete($uid, $device_id);
                } else {

                    if ($action == 'edit') {

                        extract($_POST);
                        $insertArr = array();

                        $insertArr['newsletter'] = $newslatter;
                        $insertArr['updated_on'] = date('Y-m-d H:i:s');
                        $insertArr['updated_by'] = $uid;

                        if ($this->api_model->update('st_users', array('id' => $uid, 'access' => 'user'), $insertArr)) {

                            $this->response_data['status'] = 1;
                            $this->response_data['response_message'] = 'Newslatter updated successfully.';

                        } else {
                            $this->response_data['response_message'] = 'There is some technical error.';
                        }

                    }

                    $user = $this->api_model->getWhereRowSelect('st_users', array('id' => $uid, 'access' => 'user'), 'id,newsletter');
                    if (!empty($user)) {
                        $this->response_data['status'] = 1;
                        if ($action == 'view') {$this->response_data['response_message'] = 'success';}
                        $this->response_data['data'] = $user;
                        $this->response_data['image_path'] = base_url() . 'assets/uploads/users/';
                    } else {
                        $this->response_data['response_message'] = "Not data found";
                    }

                }

            }
        }

        echo json_encode($this->response_data);
    }
    public function update_salonenewslatter_post()
    {
        $this->form_validation->set_rules('uid', 'User id', 'required|trim');
        $this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('action', 'action', 'required|trim|in_list[view,edit]');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {

                extract($_POST);
                $uid = (isset($uid)) ? $uid : '';
                $is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

                if (empty($is_valid_user->access_token)) {
                    $this->response_data['response_message'] = INVALID_TOKEN_MSG;
                    // delete access token
                    $this->api_model->access_token_delete($uid, $device_id);
                } else {

                    if ($action == 'edit') {

                        extract($_POST);
                        $insertArr = array();

                        $insertArr['service_email'] = $newslatter;
                        $insertArr['updated_on'] = date('Y-m-d H:i:s');
                        $insertArr['updated_by'] = $uid;

                        if ($this->api_model->update('st_users', array('id' => $uid, 'access' => 'user'), $insertArr)) {

                            $this->response_data['status'] = 1;
                            $this->response_data['response_message'] = 'Newslatter updated successfully.';

                        } else {
                            $this->response_data['response_message'] = 'There is some technical error.';
                        }

                    }

                    $user = $this->api_model->getWhereRowSelect('st_users', array('id' => $uid, 'access' => 'user'), 'id,service_email');
                    if (!empty($user)) {
                        $this->response_data['status'] = 1;
                        if ($action == 'view') {$this->response_data['response_message'] = 'success';}
                        $this->response_data['data'] = $user;
                        $this->response_data['image_path'] = base_url() . 'assets/uploads/users/';
                    } else {
                        $this->response_data['response_message'] = "Not data found";
                    }

                }

            }
        }

        echo json_encode($this->response_data);
    }

    public function send_otp_for_delete_account_post()
    {
        $this->form_validation->set_rules('uid', 'User id', 'required|trim');
        $this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('otp', 'Otp', 'required|trim');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {
                extract($_POST);
                $uid = (isset($uid)) ? $uid : '';
                $is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

                if (empty($is_valid_user->access_token)) {
                    $this->response_data['response_message'] = INVALID_TOKEN_MSG;
                    // delete access token
                    $this->api_model->access_token_delete($uid, $device_id);
                } else {

                    $userdata = $this->api_model->select_row('st_users', 'first_name,email', array('id' => $uid));

                    if (!empty($userdata)) {

                        $this->api_model->update('st_users', array('id' => $uid), array('forgotten_password_code' => $_POST['otp']));

                        $datasend['otp'] = $_POST['otp'];
                        $datasend['name'] = $userdata->first_name;

                        $message = $this->load->view('email/delete_account_otp', $datasend, true);

                        $mail = emailsend($userdata->email, 'styletimer - Account löschen', $message, 'styletimer');

                        $this->response_data['status'] = 1;
                        $this->response_data['response_message'] = 'otp send successfully.';
                    } else {

                        $this->response_data['status'] = 0;
                        $this->response_data['response_message'] = 'Account not existing.';

                    }
                }
            }

        }

        echo json_encode($this->response_data);

    }

    public function delete_account_post()
    {
        $this->form_validation->set_rules('uid', 'User id', 'required|trim');
        $this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('otp', 'Otp', 'required|trim');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {
                extract($_POST);
                $uid = (isset($uid)) ? $uid : '';
                $is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);
                if (empty($is_valid_user->access_token)) {
                    $this->response_data['response_message'] = INVALID_TOKEN_MSG;
                    // delete access token
                    $this->api_model->access_token_delete($uid, $device_id);
                } else {

                    $userdata = $this->api_model->select_row('st_users', 'password,forgotten_password_code', array('id' => $uid));

                    if (!empty($userdata) && ($_POST['account_type'] == 'apple' || $_POST['otp'] == $userdata->forgotten_password_code)) {
                        //$this->api_model->update('st_booking',array('status !='=>'completed','user_id'=>$uid),array('status'=>'deleted'));

                        $this->api_model->update('st_users', array('id' => $uid), array('status' => 'deleted'));
                        $this->api_model->delete('st_devices', array('uid' => $uid));

                        $usid = $uid;
                        $acc = 'user';
                        $res = 'Automatisch abgebrochen';

                        //echo get_class($this->user); die;
                        $getBooking = $this->api_model->select('st_booking', 'id', array('user_id' => $usid, 'status' => 'confirmed'));

                        //$getBooking = $this->user->select('st_booking','id',array('user_id'=>$usid,'status'=>'confirmed'));

                        if (!empty($getBooking)) {

                            foreach ($getBooking as $row) {
                                $id = $row->id;

                                //if($this->user->update('st_booking',array('status' => 'cancelled','updated_by' => $usid,'updated_on' =>date('Y-m-d H:i:s'),'reason' => $res),array('id'=>$id)))
                                if ($this->api_model->updateCI('st_booking', array('status' => 'cancelled', 'updated_by' => $usid, 'updated_on' => date('Y-m-d H:i:s'), 'reason' => $res), array('id' => $id))) {
                                    $field = 'st_booking.id,user_id,booking_time,total_timest_booking.merchant_id,first_name,last_name,book_id,st_users.email,st_booking.booking_type,st_booking.fullname,st_booking.email as guestemail,(select business_name from st_users where st_users.id = st_booking.merchant_id) as salon_name,employee_id,(select first_name from st_users where st_users.id = st_booking.merchant_id) as merchant_name,(select email from st_users where st_users.id=st_booking.merchant_id) as m_email,st_users.notification_status';
                                    $info = $this->api_model->join_two('st_booking', 'st_users', 'user_id', 'id', array('st_booking.id' => $id), $field);
                                    if (!empty($info)) {

                                        $time = new DateTime($info[0]->booking_time);
                                        $date = $time->format('d.m.Y');
                                        $time = $time->format('H:i');

                                        if ($info[0]->booking_type == 'guest') {
                                            $first_name = ucwords($info[0]->fullname);
                                            $last_name = "";
                                            $emailsend = $info[0]->guestemail;
                                        } else {
                                            $first_name = ucwords($info[0]->first_name);
                                            $last_name = ucwords($info[0]->last_name);
                                            $emailsend = $info[0]->email;
                                        }
                                        if ($acc == 'user') {
                                            $insertArr = array("booking_id" => $id, "status" => "cancel", "merchant_id" => $info[0]->merchant_id, "created_by" => $usid, "created_on" => date('Y-m-d H:i:s'));
                                            $this->api_model->insert('st_booking_notification', $insertArr);
                                        }
                                        $message = $this->load->view('email/booking_cancel', array("fname" => $first_name, "lname" => $last_name, "salon_name" => $info[0]->salon_name, "service_name" => get_servicename($info[0]->id), "booking_date" => $date, "booking_time" => $time, "booking_id" => $id, 'book_id' => $info[0]->book_id, "duration" => $info[0]->total_time), true);

                                        $m_name = $this->session->userdata('sty_fname');

                                        $message2 = $this->load->view('email/booking_cancel_salon', array("fname" => $first_name, "lname" => $last_name, "salon_name" => $info[0]->salon_name, "merchant_name" => $info[0]->merchant_name, "service_name" => get_servicename($info[0]->id), "booking_date" => $date, "booking_time" => $time, 'access' => $acc, 'emp_name' => $m_name, "booking_id" => $id, 'book_id' => $info[0]->book_id, 'duration' => $info[0]->total_time), true);

                                        $mail = emailsend($emailsend, $this->lang->line("styletimer_booking_cancel"), $message, 'styletimer');
                                        $mail = emailsend($info[0]->m_email, $this->lang->line("styletimer_booking_cancel"), $message2, 'styletimer');

                                        $empDat = is_mail_enable_for_user_action($info[0]->employee_id);
                                        if ($empDat) {
                                            $message2 = $this->load->view('email/booking_cancel_salon', array("fname" => $first_name, "lname" => $last_name, "merchant_name" => $empDat->first_name, "salon_name" => $info[0]->salon_name, "service_name" => get_servicename($info[0]->id), "booking_date" => $date, "booking_time" => $time, 'access' => $acc, 'emp_name' => $m_name, "booking_id" => $id, 'book_id' => $info[0]->book_id, 'duration' => $info[0]->total_time), true);
                                            emailsend($empDat->email, $this->lang->line('styletimer_booking_cancel'), $message2, 'styletimer');
                                        }
                                    }

                                }
                            }
                        }

                        $this->response_data['status'] = 1;
                        $this->response_data['response_message'] = 'Account deleted successfully.';
                    } else {
                        $this->response_data['status'] = 0;

                        $this->response_data['response_message'] = 'OTP does not match.';

                    }
                }
            }

        }

        echo json_encode($this->response_data);

    }

    public function resend_activation_mail_post()
    {

        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {

                $identity = $this->ion_auth->where('email', $_POST['email'])->where('status !=', "deleted")->users()->row();
                //echo '<pre>'; print_r($identity); die;
                if (!empty($identity)) {
                    if ($identity->status == 'inactive' && empty($identity->last_login) && !empty($identity->activation_code)) {
                        $activation_code = encryptPass($_POST['email']);

                        $this->api_model->update('st_users', array('id' => $identity->id), array('activation_code' => $activation_code));

                        $message = $this->load->view('email/user_activtion_link', array('link' => base_url("auth/activate/{$identity->id}/{$identity->activation_code}"), "name" => ucwords($identity->first_name), "button" => "ACTIVATE ACCOUNT", "msg" => "This message has been sent to you by StyleTimer. Click on the link below to Activate your account.", 'role' => $identity->access), true);
                        $mail = emailsend($identity->email, 'styletimer - Account aktivieren', $message, 'styletimer');

                        $this->response_data['status'] = 1;
                        $this->response_data['response_message'] = 'Activation link send to your email.';
                    } else {

                        $this->response_data['status'] = 0;
                        $this->response_data['response_message'] = 'Admin has inactive or deleted your account.';

                    }
                } else {
                    $this->response_data['status'] = 0;
                    $this->response_data['response_message'] = 'This account already activated or not registered on styletimer.';

                }

            }

        }

        echo json_encode($this->response_data);

    }

    public function update_app_review_status_post()
    {
        $this->form_validation->set_rules('uid', 'User id', 'required|trim');
        $this->form_validation->set_rules('access_token', 'Access token', 'required|trim');
        $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim');
        $this->form_validation->set_rules('booking_count', 'Booking count', 'required|trim');
        $this->form_validation->set_rules('app_review_status', 'Review status', 'required|trim');
        //$this->form_validation->set_rules('feedback', 'feedback', 'required|trim');

        if ($this->form_validation->run() == false) {
            validationErrorMsg();
        } else {
            if (!checkApikey($this->input->post('api_key'))) {
                $this->response_data['response_message'] = INVALID_API_KEY;
            } else {

                extract($_POST);
                $uid = (isset($uid)) ? $uid : '';
                $is_valid_user = $this->api_model->access_token_check($uid, $device_type, $device_id, $access_token);

                if (empty($is_valid_user->access_token)) {
                    $this->response_data['response_message'] = INVALID_TOKEN_MSG;
                    // delete access token
                    $this->api_model->access_token_delete($uid, $device_id);
                } else {
                    extract($_POST);
                    $insertArr = array();

                    $insertArr['app_review_status'] = $app_review_status;
                    $insertArr['booking_count'] = $booking_count;
                    $insertArr['updated_on'] = date('Y-m-d H:i:s');
                    $insertArr['updated_by'] = $uid;

                    if ($this->api_model->update('st_users', array('id' => $uid, 'access' => 'user'), $insertArr)) {
                        if (!empty($feedback)) {
                            $message = $this->load->view('email/app_feedback', array("feedback" => $feedback), true);
                            $mail = emailsend('mario80853@googlemail.com', 'Styletimer - App Feedback', $message, 'styletimer');
                        }
                        $this->response_data['status'] = 1;
                        $this->response_data['response_message'] = 'Vielen Dank für dein Feedback!';

                    } else {
                        $this->response_data['response_message'] = 'There is some technical error.';
                    }
                }

            }
        }

        echo json_encode($this->response_data);
    }

}
