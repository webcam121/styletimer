<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Frontend_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('image_lib');
        $this->load->library('upload');
        $this->load->library('image_moo');
        $usid = $this->session->userdata('st_userid');
        if (!empty($usid)) {
            $status = getstatus_row($usid);
            if ($status != 'active') {
                redirect(base_url('auth/logouts/') . $status);
            }
        }
        $this->lang->load('push_notification', 'german');
        $this->lang->load('api_res_msg', 'german');
        //echo $_SERVER['HTTP_USER_AGENT'];  die;
        //phpinfo(); die;

    }

//*** public function  ***//
    public function index()
    {
        $yrdata = strtotime('25-07-2020 10:00');

        $yrda = strtotime('10:00:15');
        // $mname =  date('F',$yrdata);
        // $date =  str_replace($mname,$this->lang->line($mname),date('d. F Y \u\m H:i \U\h\r',$yrdata));

        //echo $mname;
        //echo $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
        //die;

        //echo str_replace('*date*',$date,$this->lang->line("reshedule_scs_msg")); die;

        //echo str_replace('*salonname*','Test',$this->lang->line("dashboard")); die;
        $data = array();
        if (isset($_COOKIE['testing']) && $_COOKIE['testing'] != '') {
            $selectQuery = "SELECT `st_users`.`id`, `business_name`, `business_type`,`image`,`image1`,`image2`,`image3`,`image4`,`address`, `country`, `city`,`zip`, `about_salon`,(SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` and `user_id` != 0) as rating FROM st_users LEFT JOIN st_banner_images ON st_banner_images.user_id=st_users.id WHERE st_users.id IN (" . $_COOKIE['testing'] . ")";

            $query1 = $this->db->query($selectQuery);
            $data['recent_view'] = $query1->result();
        }
        setcookie('sch_date', '', time() + (86400 * 30), "/");
        setcookie('sch_start', '', time() + (86400 * 30), "/");
        setcookie('sch_end', '', time() + (86400 * 30), "/");
        setcookie('sch_time', '', time() + (86400 * 30), "/");
        $cat = $this->db->query("SELECT id, category_name FROM st_category WHERE status='active' AND parent_id='0' ORDER BY show_order asc");
        $data['category'] = $cat->result();

        // $fcat = $this->db->query("SELECT id, category_name FROM st_filter_category WHERE status='active'");
        // $data['filter_category'] = $fcat->result();
        $data['filter_category'] = get_filter_with_parent_cat_menu();

        $cur_date = date('Y-m-d H:i:s');
        $sqlForservice = "SELECT MAX(st_merchant_category.discount_percent) as percent,st_category.id,st_category.image,st_category.category_name,st_category.offer_text,st_offer_availability.service_id FROM st_merchant_category LEFT JOIN `st_users` ON `st_merchant_category`.`created_by`= `st_users`.`id` LEFT JOIN `st_category` ON `st_merchant_category`.`category_id`= `st_category`.`id` LEFT JOIN `st_offer_availability` ON `st_merchant_category`.`id` = `st_offer_availability`.`service_id` WHERE st_users.end_date > '" . $cur_date . "' AND st_merchant_category.discount_percent >'0' AND st_offer_availability.type='open' GROUP BY st_merchant_category.category_id LIMIT 12";

        $data['offer_listing'] = $this->user->custome_query($sqlForservice, 'result');

        $selectMinMaxtime = "SELECT MIN(starttime) as mintime,(SELECT MAX(endtime) FROM st_availability WHERE type='open') as maxtime FROM st_availability WHERE type='open'";

        $data['minmaxtime'] = $this->user->custome_query($selectMinMaxtime, 'row');
        $data['share_desc'] = 'Buche Friseur, Kosmetik & Massage Termine online wo und wann immer du willst! Finde 24/7 die besten Salons in deiner Gegend inkl. Kundenbewertungen mit styletimer';
        $data['static_content'] = $this->user->select_row('st_static_page', '*', array('typesSet' => 'salonmainpages'));
        //'Friseur, Kosmetik, Nagelstudio, Massage Termine online buchen - styletimer';

        //$this->load->view('frontend/home',$data);
        $data['inc_jqueryui'] = true;
        $this->load->view('frontend/home_new', $data);

    }

    // function loginRedirect(){

    //  $url=base_url();
    //     //         $resData = ['success'=>'1','message'=>'login success','url'=>$url];
    //     //         echo json_encode($resData);    die;
    //     redirect($url, 'refresh');
    // }
    public function google_signup()
    {
        if (isset($_GET['code'])) {

            $res = $this->google->getAuthenticate();
            $gpInfo = $this->google->getUserInfo();

            //   echo '<pre>';  print_r(json_decode($res)); die;
            $authdata = json_decode($res);
            //echo '<pre>'; print_r($gpInfo); die;

            if (!empty($this->session->userdata('st_userid'))) {
                $accessToken = $authdata->access_token;

                //$onotherData          = array();
                // $onotherData['google_access_token'] = $accessToken;
                ////$onotherData['google_email'] = $gpInfo['email'];

                $calendar_id = 'primary';
                $event_title = 'Styletimer Booking';
                //$event_starttime = $this->data['main'][0]->booking_time;
                //$event_endtime = $this->data['main'][0]->booking_endtime;
                $event_starttime = '2020-11-27T17:00:00';
                $event_endtime = '2020-11-27T19:00:00';

                if (!empty($this->session->userdata('booking_id'))) {

                    $bid = url_decode($this->session->userdata('booking_id'));

                    $this->data['main'] = $this->user->join_two('st_booking', 'st_users', 'merchant_id', 'id', array('st_booking.id' => $bid), 'st_booking.id,st_booking.book_id,st_booking.merchant_id,business_name,st_users.address,st_users.city,st_users.zip,st_booking.booking_time,st_booking.booking_endtime,st_booking.notes as booknotes,st_booking.employee_id,st_booking.fullname');

                    if (!empty($this->data['main'])) {
                        $field = "st_users.id,st_users.status,st_booking_detail.user_id,first_name,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price,email,gender, address,city,country,zip,service_id";

                        $whr = array('booking_id' => $bid);

                        $this->data['booking_detail'] = $this->user->join_two('st_booking_detail', 'st_users', 'user_id', 'id', $whr, $field);

                        $calendar_id = 'primary';
                        $event_title = 'Styletimer Booking at ' . $this->data['main'][0]->business_name;
                        $event_starttime = date('Y-m-d\TH:i:s', strtotime($this->data['main'][0]->booking_time));
                        $event_endtime = date('Y-m-d\TH:i:s', strtotime($this->data['main'][0]->booking_endtime));
                        $timezone = 'Asia/Kolkata';

                        $res = $this->CreateCalendarEvent($calendar_id, $event_title, $event_starttime, $event_endtime, $timezone, $accessToken);
                        if ($res) {
                            $this->db->where('id', $bid);
                            $this->db->update('st_booking', array('google_event_id' => $res));
                            $this->session->set_userdata('booking_id', "");
                            redirect(base_url('user/all_bookings'));
                        }

                        //print_r($res); die;
                    } else {redirect(base_url('user/all_bookings'));}

                    // $timezone = 'Asia/Kolkata';

                    //$res = $this->CreateCalendarEvent($calendar_id,$event_title,$event_starttime,$event_endtime,$timezone,$accessToken);
                } else {redirect(base_url('user/all_bookings'));}

            } else {
                $accessToken = $authdata->access_token;

                $userData['socialtype'] = 'google';
                $userData['unique_id'] = $gpInfo['id'];
                $userData['first_name'] = $gpInfo['given_name'];
                $userData['last_name'] = $gpInfo['family_name'];
                $userData['email'] = $gpInfo['email'];
                //$userData['google_access_token'] = $accessToken;
                //$userData['google_email'] = $gpInfo['email'];

                //$userData['profile_pic']     = !empty($gpInfo['picture'])?$gpInfo['picture']:'';
                $userdata = $this->user->select_row('st_users', 'id,status,access', array('email' => $gpInfo['email'], 'status !=' => 'deleted'));
                if (empty($userdata)) {
                    $userID = $this->user->checkUser($userData);
                    if (!empty($gpInfo['picture'])) {

                        $url = $gpInfo['picture'];

                        $img = 'assets/uploads/users/' . $userID . '/';
                        if (is_dir($img)) {
                            //echo ("$file is a directory");
                        } else {
                            mkdir($img, 0777);
                        }
                        //mkdir($img, 0777);
                        $imgname = 'google' . time() . '.JPG';
                        $imgs = $img . $imgname;
                        $imgs1 = $img . 'thumb_' . $imgname;
                        $imgs2 = $img . 'icon_' . $imgname;
                        //file_put_contents($imgs, file_get_contents($url));
                        $ch = curl_init($url);
                        $fp = fopen($imgs, 'wb');
                        curl_setopt($ch, CURLOPT_FILE, $fp);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_exec($ch);
                        curl_close($ch);
                        fclose($fp);

                        $ch1 = curl_init($url);
                        $fp1 = fopen($imgs1, 'wb');
                        curl_setopt($ch1, CURLOPT_FILE, $fp1);
                        curl_setopt($ch1, CURLOPT_HEADER, 0);
                        curl_exec($ch1);
                        curl_close($ch1);
                        fclose($fp1);

                        $ch2 = curl_init($url);
                        $fp2 = fopen($imgs2, 'wb');
                        curl_setopt($ch2, CURLOPT_FILE, $fp2);
                        curl_setopt($ch2, CURLOPT_HEADER, 0);
                        curl_exec($ch2);
                        curl_close($ch2);
                        fclose($fp2);

                        $this->db->where('id', $userID);
                        $this->db->update('st_users', array('profile_pic' => $imgname));
                    }
                } else {
                    if ($userdata->status == 'inactive') {
                        redirect(base_url('?page=login&status=inactive'));
                    } else {
                        $userID = $userdata->id;
                    }
                }

                $where = array('id' => $userID);

                $user = $this->user->select_row("st_users", 'id,first_name,last_name,email,last_login,access,status,business_name,profile_status,login_status', $where);
                if (!empty($user)) {
                    $session_data = [
                        'identity' => $user->email,
                        'email' => $user->email,
                        'st_userid' => $user->id, //everyone likes to overwrite id so we'll use user_id
                        'old_last_login' => $user->last_login,
                        'last_check' => time(),
                        'access' => $user->access,
                        'status' => $user->status,
                        'sty_fname' => $user->first_name,
                        'business_name' => $user->business_name,
                        'sty_profile' => $user->profile_pic,
                        'profile_status' => $user->profile_status,
                    ];

                    $this->session->set_userdata($session_data);

                    if ($this->session->userdata('access') == 'marchant') {

                        if ($user->login_status == 0) {
                            $this->db->update('st_users', array('login_status' => '1'), array('email' => $user->email, 'status' => 'active'));
                            $url = base_url("merchant/dashboard?setup=profile");
                        } else {
                            $query = $this->db->query("SELECT about_salon,(SELECT count(id) FROM st_availability WHERE user_id=" . $this->session->userdata('st_userid') . " AND type='open') as workinhhrs,(SELECT count(id) FROM st_users WHERE merchant_id=" . $this->session->userdata('st_userid') . " AND access='employee') as employeeCount,(SELECT count(id) FROM st_merchant_category WHERE created_by=" . $this->session->userdata('st_userid') . " AND status !='deleted') as serviceCount FROM st_users WHERE id=" . $this->session->userdata('st_userid'));

                            $checkdata = $query->row();
                            if (empty($checkdata->about_salon)) {
                                $url = base_url("merchant/dashboard?setup=profile");
                            } else if (empty($checkdata->workinhhrs)) {
                                $url = base_url("merchant/dashboard?setup=workinghour");
                            } else if (empty($checkdata->employeeCount)) {
                                $url = base_url("merchant/dashboard?setup=employee");
                            } else if (empty($checkdata->serviceCount)) {
                                $url = base_url("merchant/dashboard?setup=service");
                            } else {
                                $url = base_url('merchant/dashboard');
                            }

                        }
                        //~ }

                    } elseif ($this->session->userdata('access') == 'employee') {
                        $url = base_url("profile/edit_employee_profile");
                    } else {

                        if ($user->login_status == 0) {
                            $this->db->update('st_users', array('login_status' => '1'), array('email' => $user->email, 'status' => 'active'));
                            $url = base_url("profile/edit_user_profile");
                        } else {
                            if (!empty($_COOKIE['redirect_url'])) {
                                $url = $_COOKIE['redirect_url'];
                                setcookie('redirect_url', '', time() + (1000 * 50), "/");
                            } else {
                                $url = base_url("");
                            }
                        }

                    }

                    redirect($url);
                }
            }
        }

    }

    public function facebook_login()
    {
        $userData = array();

        // Authenticate user with facebook
        if ($this->facebook->is_authenticated()) {
            // Get user info from facebook
            $fbUser = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,birthday,gender,picture');
            if (empty($fbUser['id'])) {
                redirect(base_url('?page=login&status=email'));
            }
            //   if(empty($fbUser['email']))
            //    {
            //        redirect(base_url('?page=login&status=email'));
            //    }

            $userData['socialtype'] = 'facebook';
            $userData['unique_id'] = !empty($fbUser['id']) ? $fbUser['id'] : '';
            $userData['first_name'] = !empty($fbUser['first_name']) ? $fbUser['first_name'] : '';
            $userData['last_name'] = !empty($fbUser['last_name']) ? $fbUser['last_name'] : '';
            $userData['email'] = !empty($fbUser['email']) ? $fbUser['email'] : '';
            $userData['gender'] = !empty($fbUser['gender']) ? $fbUser['gender'] : 'male';

            //$userData['profile_pic']     = !empty($gpInfo['picture'])?$gpInfo['picture']:'';
            $userdata = $this->user->select_row('st_users', 'id,status,access', array('email' => $userData['email'], 'status !=' => 'deleted'));
            if (empty($userdata)) {
                $userID = $this->user->checkUser($userData);
                if (!empty($fbUser['picture']['data']['url'])) {
                    //echo $fbUser['picture']['data']['url'];
                    //$url = $fbUser['picture']['data']['url'];
                    $url = "https://graph.facebook.com/" . $fbUser['id'] . "/picture?type=large&width=320&height=320";
                    // echo  $fbUser['picture']['data']['url'].'=='.$url; die;
                    $img = 'assets/uploads/users/' . $userID . '/';
                    if (is_dir($img)) {
                        //echo ("$file is a directory");
                    } else {
                        mkdir($img, 0777);
                    }
                    //mkdir($img, 0777);
                    $imgname = 'fb' . time() . '.JPG';
                    $imgs = $img . $imgname;
                    $imgs1 = $img . 'thumb_' . $imgname;
                    $imgs2 = $img . 'icon_' . $imgname;

                    copy($url, $imgs);
                    copy($url, $imgs1);
                    copy($url, $imgs2);
                    //file_put_contents($imgs, file_get_contents($url));
                    /*$ch = curl_init($url);
                    $fp = fopen($imgs, 'wb');
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_exec($ch);
                    curl_close($ch);
                    fclose($fp);
                     */
/*
$ch1 = curl_init($url);
$fp1 = fopen($imgs1, 'wb');
curl_setopt($ch1, CURLOPT_FILE, $fp1);
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_exec($ch1);
curl_close($ch1);
fclose($fp1);

$ch2 = curl_init($url);
$fp2 = fopen($imgs2, 'wb');
curl_setopt($ch2, CURLOPT_FILE, $fp2);
curl_setopt($ch2, CURLOPT_HEADER, 0);
curl_exec($ch2);
curl_close($ch2);
fclose($fp2);*/

                    $this->db->where('id', $userID);
                    $this->db->update('st_users', array('profile_pic' => $imgname));

                }
            } else {
                if ($userdata->status == 'inactive') {
                    redirect(base_url('?page=login&status=inactive'));
                } else {
                    $userID = $userdata->id;
                }
            }

            //echo base_url($imgs); die;
            $where = array('id' => $userID);

            $user = $this->user->select_row("st_users", 'id,first_name,last_name,email,last_login,access,status,business_name,profile_status,login_status', $where);
            if (!empty($user)) {
                $session_data = [
                    'identity' => $user->email,
                    'email' => $user->email,
                    'st_userid' => $user->id, //everyone likes to overwrite id so we'll use user_id
                    'old_last_login' => $user->last_login,
                    'last_check' => time(),
                    'access' => $user->access,
                    'status' => $user->status,
                    'sty_fname' => $user->first_name,
                    'business_name' => $user->business_name,
                    'sty_profile' => $user->profile_pic,
                    'profile_status' => $user->profile_status,
                ];

                $this->session->set_userdata($session_data);

                if ($this->session->userdata('access') == 'marchant') {

                    if ($user->login_status == 0) {
                        $this->db->update('st_users', array('login_status' => '1'), array('email' => $user->email, 'status' => 'active'));
                        $url = base_url("merchant/dashboard?setup=profile");
                    } else {
                        $query = $this->db->query("SELECT about_salon,(SELECT count(id) FROM st_availability WHERE user_id=" . $this->session->userdata('st_userid') . " AND type='open') as workinhhrs,(SELECT count(id) FROM st_users WHERE merchant_id=" . $this->session->userdata('st_userid') . " AND access='employee') as employeeCount,(SELECT count(id) FROM st_merchant_category WHERE created_by=" . $this->session->userdata('st_userid') . " AND status !='deleted') as serviceCount FROM st_users WHERE id=" . $this->session->userdata('st_userid'));

                        $checkdata = $query->row();
                        if (empty($checkdata->about_salon)) {
                            $url = base_url("merchant/dashboard?setup=profile");
                        } else if (empty($checkdata->workinhhrs)) {
                            $url = base_url("merchant/dashboard?setup=workinghour");
                        } else if (empty($checkdata->employeeCount)) {
                            $url = base_url("merchant/dashboard?setup=employee");
                        } else if (empty($checkdata->serviceCount)) {
                            $url = base_url("merchant/dashboard?setup=service");
                        } else {
                            $url = base_url('merchant/dashboard');
                        }

                    }
                    //~ }

                } elseif ($this->session->userdata('access') == 'employee') {
                    $url = base_url("profile/edit_employee_profile");
                } else {

                    if ($user->login_status == 0) {
                        $this->db->update('st_users', array('login_status' => '1'), array('email' => $user->email, 'status' => 'active'));
                        $url = base_url("profile/edit_user_profile");
                    } else {
                        if (!empty($_COOKIE['redirect_url'])) {
                            $url = $_COOKIE['redirect_url'];
                            setcookie('redirect_url', '', time() + (1000 * 50), "/");
                        } else {
                            $url = base_url("");
                        }
                    }

                }

                redirect($url);
            }
        } else {
            redirect('?page=login');
            // Facebook authentication url
            //$data['authURL'] =  $this->facebook->login_url();
        }

        // Load login/profile view
        //$this->load->view('user_authentication/index',$data);
    }
    public function set_redirect_cookies()
    {
        setcookie('redirect_url', $_POST['url'], time() + (1000 * 50), "/");
    }

    //~ public function insertcity()
    //~ {
    //~ $query=$this->db->query("SELECT * FROM states WHERE country_id=212");

    //~ $data=$query->result();
    //~ if(!empty($data))
    //~ {
    //~ foreach($data as $d){
    //~ $query1=$this->db->query("SELECT * FROM cities WHERE state_id=".$d->id);
    //~ $city=$query1->result();
    //~ if(!empty($city)){
    //~ foreach($city as $c){
    //~ $ct=array();
    //~ $ct['name']=$c->name;
    //~ $ct['country_id']=3;
    //~ $this->db->insert('city',$ct);

    //~ }

    //~ }
    //~ }
    //~ }
    //~ }

    public function getsalon_list()
    {
        $date = date('Y-m-d H:i:s');
        $query = $this->db->query("SELECT id,business_name FROM st_users WHERE status='active' AND end_date>='" . $date . "' AND online_booking='1' AND business_name LIKE '%" . trim($_POST['keys']) . "%'");
        $data = $query->result();
        $html = "";
        if (!empty($data)) {
            $html .= '<ul class="pl-0 scroll-effect">';
            foreach ($data as $row) {
                $html .= '<li class="key_word"><input type="radio" id="salondrop' . $row->id . '" class="salon_li" data-val="' . $row->business_name . '" name="salon" value="' . url_encode($row->id) . '"><label for="salondrop' . $row->id . '">' . $row->business_name . '</label></li>';
            }
            $html .= '</ul>';
        } else {
            $html = '<ul class="pl-0 scroll-effect">
                      <li class="key_word">
                       Keine passenden Ergebnisse gefunden
                      </li>
                      <li class="key_word">
                        Du möchtest Termine bei deinem Salon über styletimer buchen ? <a href="#" data-toggle="modal" data-target="#salon-list-blank" data-backdrop="static" data-keyboard="false" style="color:#00b3bf ;">Hier klicken!</a>
                      </li>
                    </ul>';

        }
        echo json_encode(['success' => 1, 'html' => $html]);
    }

///*** cookie accept ***///
    public function accept_cookie()
    {
        setcookie('styletimer_cookie_accept', 'yes', time() + (86400 * 30), "/");
        echo 'true';
    }

    public function test_resize_image()
    {

        if (!empty($_FILES['image']['name'])) {

            //$created_on = date_to_path($this->category_model->Read(array('id'=>$id), array('created_on'))[0]->created_on);
            $filename = explode('.', $_FILES["image"]["name"]);
            $_FILES['image']['name'] = 'Cat_' . time() . '.' . $filename[count($filename) - 1];

            array_insert($config, array('upload_path' => "assets/test/", "allowed_types" => 'gif|jpg|jpeg|png|JPG|JPEG|PNG'));
            //array_map('unlink', glob($config['upload_path'].'*.*'));
            @mkdir($config['upload_path'], 0777, true);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('image')) {
                echo $this->upload->display_errors();
                //$this->session->set_flashdata('error',$this->upload->display_errors());
                //redirect(admin_url("{$this->classname}/make?id=$id"));
            } else {
                $data = array('upload_data' => $this->upload->data());
                $image_info = $this->upload->data();
                array_insert($config, array('image_library' => 'gd2', 'source_image' => $config['upload_path'] . $image_info['file_name'], 'maintain_ratio' => false));
                //ini_set('memory_limit','128M');
                foreach (array("thumb_" => array(120, 120), "tn_" => array(1110, 375), "icon_" => array(32, 32), "162x115_" => array(162, 115)) as $key => $val) {
                    array_insert($config, array('new_image' => "{$config['upload_path']}$key{$image_info['file_name']}", 'width' => $val[0], 'height' => $val[1]));
                    $arr = getimagesize($config['upload_path'] . $image_info['file_name']);
                    //print_r($arr); die;
                    if ($arr[0] >= 1110) {
                        $widht = 1110;
                    } else {
                        $widht = $arr[0];
                    }

                    if ($arr[1] >= 375) {
                        $higt = 375;
                    } else {
                        $higt = $arr[1];
                    }

                    $this->image_moo->load($config['upload_path'] . $image_info['file_name'])->resize_crop($widht, $higt)->save($config['upload_path'] . 'crop_' . $image_info['file_name'], true);
                    //$this->image_lib->initialize($config)->resize();
                } //$this->image_moo->load($config['upload_path'].$image_info['file_name'])->resize(array(272,281))->save("{$config['upload_path']}{$image_info['file_name']}",true);

                //$this->db->where(array("id" => $id));
                //$this->db->update($this->classname, array("image" => $image_info['file_name']));
                $this->session->set_flashdata('success', "Data Saved Successfully with image.");
            }
        }
        $this->load->view('welcome_message');
    }

    public function contact_for_salon()
    {

        extract($_POST);
        $uid = $this->session->userdata('st_userid');
        $date = date('Y-m-d H:i:s');
        $userid = 0;
        if (!empty($uid)) {
            $userid = $uid;
        }

        if (!empty($_POST['salon_name']) && !empty($_POST['salon_city']) && !empty($_POST['name'])) {
            $query = $this->db->query("INSERT INTO st_contactforsalon(`name`,`salon_name`,`salon_city`,`created_by`,`created_on`,`via`) VALUES('" . $name . "','" . $salon_name . "','" . $salon_city . "','" . $userid . "','" . $date . "',0)");
            $_POST['date'] = $date;
            $datasend['data'] = $_POST;

            $message = $this->load->view('email/salon_contact', $datasend, true);
            //echo $message; die;

            $mail = emailsend('mario80853@googlemail.com', 'New Salon request', $message, 'styletimer'); //info@styletimer.de
        }
        echo json_encode(['success' => 1, 'msg' => 'Wir haben deinen Vorschlag erhalten.']);

    }

    public function submit_suggestion()
    {

        if (!empty($this->session->userdata('st_userid'))) {
            $insertArr = array();
            $insertArr['user_id'] = $this->session->userdata('st_userid');
            $insertArr['description'] = $_POST['suggest'];

            $res = $this->user->insert('st_suggestions', $insertArr);

            if ($res) {
                $datasend = array();
                $datasend['salonname'] = $this->session->userdata('business_name');
                $datasend['message'] = $_POST['suggest'];
                $message = $this->load->view('email/notify_admin_for_suggetion', $datasend, true);
                $mail = emailsend(OFFICE_EMAIL, 'New suggestion for website', $message, 'styletimer');

                $resarr = array('success' => 1, 'message' => $this->lang->line('success_suggested'));
            } else {
                $resarr = array('success' => 0, 'message' => 'There is some technical issue. Try again');
            }

        } else {
            $resarr = array('success' => 0, 'message' => 'Please logged in and submit suggestions.');
        }
        echo json_encode($resarr);die;
        //echo "<pre>"; print_r($_POST); die;

    }

// check reffrel code is exist or not for  salesman or not
    public function checkrefferal()
    {
        if (empty($_POST['referral_code'])) {
            echo 'true';die;
        } else {
            $user = $this->user->select_row('st_users', 'id', array('access' => 'salesman', 'status' => 'active', 'salesman_code' => $_POST['referral_code']));
            if (!empty($user->id)) {
                echo 'true';die;
            } else {
                echo 'false';
            }
            die;

        }

    }

// check reffrel code is exist or not for  salesman or not
    public function update_slug()
    {

        $user = $this->user->select('st_users', 'id,business_name', array('access' => 'marchant'));

        foreach ($user as $row) {
            $slug = create_slug($row->business_name);
            $this->user->update('st_users', array('slug' => $slug), array('id' => $row->id));
        }

        //echo '<pre>'; print_r($user); die;

    }

    public function booking_redirect($id = '')
    {

        if ($this->session->userdata('access') == 'user') {
            redirect(base_url('user/all_bookings?bid=') . $id);
        } else if ($this->session->userdata('access') == 'marchant') {
            redirect(base_url('merchant/booking_listing?bid=') . $id);
        } else {
            redirect(base_url('?bid=') . $id);
        }

    }

    public function contact()
    {
//    $this->data['contact']=$this->user->select_row('st_static_page','*',array('type'=>'contactus'));
        //    $this->data['title']  = 'Styletimer - '.$this->data['contact']->title;
        $this->data['static_content'] = $this->user->select_row('st_static_page', '*', array('typesSet' => 'contactus'));
        $this->data['share_desc'] = 'Digitale Termin- und Kundenverwaltung für Friseur und Beauty mit intelligentem Kalender inkl. Android & IOS Apps für ihre Kunden. Jetzt kostenlos testen!';

        $this->load->view('frontend/contact', $this->data);
    }

    public function about()
    {
        $this->data['about'] = $this->user->select_row('st_static_page', '*', array('type' => 'aboutus'));
        $this->data['title'] = $this->data['about']->title . ' - styletimer';
        $this->data['share_desc'] = 'Alles was du über styletimer wissen musst.';
        $this->load->view('frontend/about', $this->data);
    }
    public function imprint()
    {
        $this->data['imprint'] = $this->user->select_row('st_static_page', '*', array('type' => 'imprint'));
        $this->data['title'] = 'styletimer - ' . $this->data['imprint']->title;
        $this->load->view('frontend/imprint', $this->data);
    }

    public function appstatic($type)
    {
        $this->data['terms'] = $this->user->select_row('st_static_page', '*', array('type' => $type));

        $this->load->view('frontend/terms_privacy_app', $this->data);
    }

    public function contact_post()
    {
        if (!empty($_POST['name'])) {
            $insert = array();

            if (!empty($this->session->userdata('st_users'))) {
                $insert['uid'] = $this->session->userdata('st_users');
            }
            $insert['name'] = $_POST['name'];
            $insert['email'] = $_POST['email'];
            $insert['subject'] = $_POST['subject'];
            $insert['text'] = $_POST['message'];
            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
            }

            if (!$captcha) {
                echo json_encode(['success' => '0', 'message' => 'Bitte überprüfen Sie das Captcha']);die;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => '6Lel0UoeAAAAAIvhcWXEsMIgEkgcuSn2lMl4K-tb', 'response' => $captcha)));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response, true);

            if ($response['success'] == false) {
                echo json_encode(['success' => '0', 'message' => 'Bitte überprüfen Sie das Captcha']);die;
            }
            $res = $this->user->insert('st_contactus', $insert);
            $_POST['date'] = date('Y-m-d H:i:s');
            $datasend['data'] = $_POST;

            $message = $this->load->view('email/contactus', $datasend, true);

            $mail = emailsend('info@styletimer.de', 'New Contact', $message, 'styletimer');
            //$mail = emailsend('test@mailinator.com','New Contact',$message,'styletimer');

            if ($res) {
                echo json_encode(['success' => '1', 'message' => 'Anfrage erfolgreich versendet']);die;
            } else {
                echo json_encode(['success' => '0', 'message' => 'Es gibt ein Problem. Versuche es später.']);die;
            }
        }
        redirect(base_url('contact'));
    }

    public function dsgvo()
    {
        $this->data['dsgvo'] = $this->user->select_row('st_static_page', '*', array('type' => 'dsgvo'));
        $this->data['title'] = 'styletimer - ' . $this->data['dsgvo']->title;
        $this->load->view('frontend/dsgvo', $this->data);
    }

    public function calculator()
    {

        if (isset($_POST['booking_take_perweek'])) {
            // hours save in week = (sum of bookings each month x time to take booking manually)
            $saveHours = round((($_POST['booking_take_perweek'] * $_POST['taking_a_booking_in_min']) / 60) * 24, 2) . ' Stunden';

            // Revenue increase in week = (bookings taken each month manually x average spend per treatment + bookings taken each month manually x average spend per treatment x increase because of after hours booking ) + (sum of no-shows each month x average spend per treatment x 0.8)

            $revenueIncrease = round((((($_POST['booking_take_perweek'] * 24) * $_POST['average_spend_per_treatment'] * $_POST['increase_in_booking_percent']) / 100) + (($_POST['how_many_cancelled_in_a_week'] * 4) * $_POST['average_spend_per_treatment']) * 0.8), 2) . ' €';

            $resarr = array('success' => 1, 'savehours' => $saveHours, 'revenueIncrease' => $revenueIncrease);

            echo json_encode($resarr);die;

            // echo $saveHours.'==='.$revenueIncrease; die;
        }

        $data['static_content'] = $this->user->select_row('st_static_page', '*', array('typesSet' => 'calculator'));
        $data['share_desc'] = 'Digitale Termin- und Kundenverwaltung für Friseur und Beauty mit intelligentem Kalender inkl. Android & IOS Apps für ihre Kunden. Jetzt kostenlos testen!';
        $this->load->view('frontend/calculator', $data);
    }

    // public function add_event_on_google_calender($id = "")
    // {

    //     if (!empty($id)) {
    //         $this->session->set_userdata('booking_id', $id);
    //         redirect(get_google_login_url());

    //     } else {
    //         redirect(base_url());
    //     }

        /*    $bid = url_decode($id);

    $this->data['main'] = $this->user->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$bid),'st_booking.id,st_booking.book_id,st_booking.merchant_id,business_name,st_users.address,st_users.city,st_users.zip,st_booking.booking_time,st_booking.booking_endtime,st_booking.notes as booknotes,st_booking.employee_id,st_booking.fullname');

    if(!empty($this->data['main']))
    {
    $field = "st_users.id,st_users.status,st_booking_detail.user_id,first_name,last_name,google_access_token,google_email,profile_pic,service_name,duration,price,buffer_time,discount_price,email,gender, address,city,country,zip,service_id";

    $whr   = array('booking_id'=>$bid);

    $this->data['booking_detail'] = $this->user->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);

    $calendar_id = 'primary';
    $event_title = 'Styletimer Booking';
    $event_starttime = date('Y-m-d\TH:i:s',strtotime($this->data['main'][0]->booking_time));
    $event_endtime = date('Y-m-d\TH:i:s',strtotime($this->data['main'][0]->booking_endtime));
    $timezone = 'Asia/Kolkata';

    $res = $this->CreateCalendarEvent($calendar_id,$event_title,$event_starttime,$event_endtime,$timezone,$this->data['booking_detail'][0]->google_access_token);

    print_r($res); die;
    } */

    // }

    public function terms()
    {
        $this->data['terms'] = $this->user->select_row('st_static_page', '*', array('type' => 'userterms'));
        // print_r($this->data);die;
        $this->load->view('frontend/term_condition', $this->data);
    }

    public function privacy()
    {
        $this->data['privacy'] = $this->user->select_row('st_static_page', '*', array('type' => 'userpolicy')); // print_r($this->data);die;
        $this->load->view('frontend/privacy', $this->data);
    }

    public function CreateCalendarEvent($calendar_id, $summary, $start_time, $end_time, $event_timezone, $access_token)
    {
        //echo $calendar_id."=".$summary."==".$start_time."==".$end_time."==".$event_timezone."=".$access_token; die;
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events';

        $curlPost = array('summary' => $summary);

        $curlPost['start'] = array('dateTime' => $start_time, 'timeZone' => $event_timezone);
        $curlPost['end'] = array('dateTime' => $end_time, 'timeZone' => $event_timezone);

        //    print_r($curlPost); die;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo "<pre>"; print_r($data); die;
        //~ preprint($http_code);
        if ($http_code != 200) {
            throw new Exception('Error : Failed to create event');
        }

        return $data['id'];
    }

}
