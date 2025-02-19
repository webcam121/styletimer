<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Listing extends Frontend_Controller
{
    public $data = [];
    public function __construct()
    {
        parent::__construct();

        // Load models
        $this->load->model('user_model', 'user');
        $usid = $this->session->userdata('st_userid');
        if (!empty($usid)) {
            $status = getstatus_row($usid);
            if ($status != 'active') {
                redirect(base_url('auth/logouts/') . $status);
            }
        }
        //$this->load->model('eyedrop_model', 'eyedrop');
        //$this->load->model('treatment_model', 'treatment');
        //$this->load->model('quetionaire_model', 'quetionaire');

        // Define workspace
        //$this->session->set_userdata(['workspace' => 'backend']);
    }

    //*** salon listing ***//
    public function search()
    {
        $parent = '';

        if (!empty($_GET['cid'])) {
            $parent = $this->user->select_row('st_category', 'parent_id', [
                'id' => url_decode($_GET['cid']),
            ]);
        }

        $select =
            "id,category_name,parent_id,(SELECT count(distinct(st_service_employee_relation.created_by)) FROM st_service_employee_relation INNER JOIN st_users ON st_service_employee_relation.user_id = st_users.id WHERE st_service_employee_relation.subcat_id =st_category.id AND status='active') as totalcount";
        
        if (!empty($parent)) {
            $whr = ['status' => 'active', 'parent_id ' => $parent->parent_id];
            $this->data['category'] = url_encode($parent->parent_id);
        } else {
            if (isset($_GET['filtercat']) && !empty($_GET['filtercat'])) {
                $whr = [
                    'status' => 'active',
                    'filter_category' => url_decode($_GET['filtercat']),
                ];
            } else if (isset($_GET['category']) && !empty($_GET['category'])) {
                $whr = [
                    'status' => 'active',
                    'parent_id' => url_decode($_GET['category']),
                ];
            } else {
                $whr = ['status' => 'active', 'parent_id !=' => '0'];
            }
        }
        // Comment BY Dharmendra for showing Saloon Using CID and Passing CID in All Services rather than Passing category_id

        $this->data['sucategory'] = $this->user->select(
            'st_category',
            $select,
            $whr
        );

        $selectMinMaxtime =
            "SELECT MIN(starttime) as mintime,(SELECT MAX(endtime) FROM st_availability WHERE type='open') as maxtime FROM st_availability WHERE type='open'";

        $this->data['minmaxtime'] = $this->user->custome_query(
            $selectMinMaxtime,
            'row'
        );

        $title = 'Styletimer';

        if (!empty($this->uri->segment(3))) {
            if ($this->uri->segment(3) == 'mens-haircut') {
                $title = 'Finde Hairsalons';
            } else {
                $title = ucwords(str_replace('-', ' ', $this->uri->segment(3)));
            }
        }
        if (!empty($this->uri->segment(4))) {
            $title .=
                ' in ' . ucfirst(str_replace('-', ' ', $this->uri->segment(4)));
        }

        if ($_GET['filtercat']) {
            $filterCatId = url_decode($_GET['filtercat']);
            $filterCat = $this->user->custome_query(
                'select category_name from st_filter_category where id = ' .$filterCatId,
                'row'
            );
            $filterCatName = $filterCat->category_name;
            if ($_GET['address']) {
                $adrs = $_GET['address'];
                $title = 'Finde '.$filterCatName.' in '.$adrs.' und buche 24/7 online - styletimer';
                $share_desc = 'Lese echte Kundenbewertungen und buche '.$filterCatName.' in '.$adrs.' einfach online. 24/7 Termine buchen mit styletimer!';
            } else {
                $title = 'Finde die besten Salons für '.$filterCatName;
                $share_desc = 'Lese echte Kundenbewertungen, finde die besten Salons für '.$filterCatName.' und buche 24/7 mit styletimer';
            }
        } else {
            if ($_GET['address']) {
                $adrs = $_GET['address'];
                $title = 'Finde die besten Salons für Friseur & Beauty in '.$adrs;
                $share_desc = 'Lese echte Kundenbewertungen, finde die besten Salons für Friseur & Beauty in '.$adrs.' und buche 24/7 mit styletimer';
            } else {
                $title = 'Finde die besten Salons für Friseur & Beauty mit styletimer';
                $share_desc = 'Lese echte Kundenbewertungen, finde die besten Salons für Friseur & Beauty und buche 24/7 mit styletimer';
            }
        }

        $this->data['share_desc'] = $share_desc;
        $this->data['title'] = $title;
        //Find Hairsalons in Wetzlar and book your appointment online with styletimer.
        //echo $this->db->last_query()."<pre>"; print_r($this->data); die;
        //$this->load->view('frontend/user/merchant_listing',$this->data);
        $this->data['inc_jqueryui'] = true;
        $this->load->view('frontend/user/merchant_listing_new', $this->data);
    }

    function searchSlon()
    {
        //searchSlon
        // 		$whr = array('status' => 'active', 'parent_id' => $pid,'show_dropdown'=>1);
        // 	$CI =& get_instance();
        //     $CI->db->select('id,category_name,parent_id');
        //     $CI->db->from('st_category');
        //     $CI->db->order_by('show_order','asc');
        //     $CI->db->where($whr);
        //     $child = $CI->db->get();
        //     echo $CI->last_query();die;
        //     $results = $child->result();
        $cat = $this->uri->segment(3);

        if (!empty($_GET['cid'])) {
            echo $parent_id = url_decode($_GET['cid']);
            $sql1 =
                "SELECT * FROM `st_category` 
			   WHERE status= 'active' AND id =  '" .
                $parent_id .
                "'";
            $query1 = $this->db->query($sql1);
            $users = $query1->row();

            $pagination = '';
            $uid = $this->session->userdata('st_userid');
            $sqlForcount = "SELECT count(st_users.id) as totalCount FROM `st_users` LEFT JOIN 
	    `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` WHERE
	     `status` = 'active' AND `access` = 'marchant' ";
            $dataCount = $this->db->query($sqlForcount);
            $count = $dataCount->row();
            $totalCount = $count->totalCount;
            $offset = 0;

            if ($page != 0) {
                $offset = ($page - 1) * $rowperpage;
            }

            $config['base_url'] = base_url() . 'listing/getUserdetails';
            $config['use_page_numbers'] = true;
            $config['total_rows'] = $count->totalCount;
            $config['per_page'] = $rowperpage;
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
            $config['cur_tag_close'] = '</a></li class="active">';
            $config['next_link'] = '&gt;';
            $config['prev_link'] = '&lt;';
            $config['first_link'] = '&laquo;';
            $config['last_link'] = '&raquo;';

            $this->pagination->initialize($config);

            $pagination = $this->pagination->create_links();
            $sql =
                "SELECT `st_users`.`id`,`slug`, `first_name`, `last_name`, 
					 `business_name`, `business_type`, `address`,`latitude`,`longitude`, `country`, 
					 `city`,`zip`, `about_salon`,`image`, `image1`, `image2`, `image3`, `image4`,
					 (SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as
					  rating,(SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='" .
                $uid .
                "') as favourite,
					  (SELECT count(id) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as 
					  totalcount 
					  FROM `st_users` LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id`
					   WHERE
					   `status` = 'active' AND `access` = 'marchant'  ORDER BY 
					    " .
                $order .
                ' LIMIT ' .
                $rowperpage .
                ' OFFSET ' .
                $offset .
                '';
            $query = $this->db->query($sql);
            $this->data['usersdetail'] = $query->result();
            //echo $this->db->last_query();
            if (!empty($this->data['usersdetail'])) {
                $i = 0;

                foreach ($this->data['usersdetail'] as $usr) {
                    //$sql2="SELECT `st_merchant_category`.`id`,`name`,`duration`,`price`,`subcategory_id`,`discount_price`,`st_category`.`category_name` FROM `st_merchant_category` LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id`   WHERE `st_merchant_category`.`status` = 'active' AND `st_merchant_category`.`created_by`=".$usr->id.$where." LIMIT 4";

                    $sql2 =
                        "SELECT `r`.`id`,`duration`, `price`,`price_start_option`, `buffer_time`, `discount_price`,`subcategory_id`, `u1`.`category_name` as `m_category`, `u2`.`category_name` as `s_category`,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`r`.`id` AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND online_booking=1 AND status='active')>=1),'0') as checkemp,(SELECT count(id) FROM st_merchant_category WHERE subcategory_id=r.subcategory_id AND created_by=r.created_by) as totalservice FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` WHERE `r`.`status` = 'active' AND r.online=1 AND `r`.`created_by`=" .
                        $usr->id .
                        $where .
                        ' GROUP BY `r`.`subcategory_id` LIMIT 4';

                    $query2 = $this->db->query($sql2);
                    $this->data['usersdetail'][
                        $i
                    ]->sercvices = $query2->result();
                    //echo $this->db->last_query();
                    $i++;
                }
            }
        }

        $view = $this->load->view(
            'frontend/user/listing_section',
            $this->data,
            true
        );

        echo json_encode([
            'success' => '1',
            'html' => $view,
            'pagination' => $pagination,
            'count' => $totalCount,
        ]);
        die();

        //$this->load->view('frontend/user/merchant_listing_new',$this->data);
    }

    //**** get salon detail page ****//
    public function getUserdetails($page = 0)
    {

        $lat = '';
        $lng = '';
        $distance = 20;
        $pagination = '';
        $uid = $this->session->userdata('st_userid');

        if (!empty($_POST['lat'])) {
            $lat = $_POST['lat'];
        }
        if (!empty($_POST['lng'])) {
            $lng = $_POST['lng'];
        }
        if (!empty($_POST['distance'])) {
            $distance = $_POST['distance'] * 1.60934;
        }

        // sorting from here
        if (!empty($_POST['orderby']) && $_POST['orderby'] != 'distance asc') {
            $order = $_POST['orderby'];
        } elseif (
            !empty($_POST['orderby']) &&
            $_POST['orderby'] == 'distance asc' &&
            !empty($lat) &&
            !empty($lng)
        ) {
            $order = $_POST['orderby'];
        } elseif (!empty($lat) && !empty($lng)) {
            $order = 'distance ASC';
        } else {
            $order = 'st_users.id DESC';
        }

        //echo $dayName;
        setcookie(
            'sch_date',
            isset($_POST['date']) ? $_POST['date'] : '',
            time() + 86400 * 30,
            '/'
        );
        setcookie(
            'sch_start',
            isset($_POST['starttime']) ? $_POST['starttime'] : '',
            time() + 86400 * 30,
            '/'
        );
        setcookie(
            'sch_end',
            isset($_POST['endtime']) ? $_POST['endtime'] : '',
            time() + 86400 * 30,
            '/'
        );
        setcookie(
            'sch_time',
            isset($_POST['time']) ? $_POST['time'] : '',
            time() + 86400 * 30,
            '/'
        );

        $where = '';

        if (isset($_POST['category']) && !empty($_POST['category'])) {
            $where =
                $where . ' AND category_id =' . url_decode($_POST['category']);
        }

        if (isset($_POST['filtercat']) && !empty($_POST['filtercat'])) {
            $where =
                $where . ' AND filtercat_id =' . url_decode($_POST['filtercat']);
        }

        if (!empty($_POST['startrange'])) {
            $where =
                $where .
                ' AND ((discount_price!=0 AND discount_price >=' .
                $_POST['startrange'] .
                ') OR(discount_price=0 AND price >=' .
                $_POST['startrange'] .
                '))';
        }

        if (!empty($_POST['endrange'])) {
            $where =
                $where .
                ' AND ((discount_price!=0 AND discount_price<=' .
                $_POST['endrange'] .
                ') OR(discount_price=0 AND price <=' .
                $_POST['endrange'] .
                '))';
        }

        if (!empty($_POST['date']) && $_POST['date'] != 'Beliebig') {
            $_POST['date'] = date('Y-m-d', strtotime($_POST['date']));

            if (
                empty($_POST['time']) ||
                strtolower($_POST['time']) == 'anytime' ||
                strtolower($_POST['time']) == 'beliebig'
            ) {
                // $holidaay = $this->db
                //     ->query(
                //         'SELECT `id` FROM st_national_holidays WHERE `date`="' .
                //             $_POST['date'] .
                //             '"'
                //     )
                //     ->row();

                // if (!empty($holidaay)) {
                    $_POST['starttime'] = '00:00:00';
                    $_POST['endtime'] = '23:00:00';
                    $_POST['time'] = '00:00-23:00';
                // }
            }

            $dayName = date('l', strtotime($_POST['date']));
            $dayName = strtolower($dayName);
        }

        if (!empty($_POST['expess_offer']) && $_POST['expess_offer'] == 'yes') {
            $dayName = date('l', strtotime(date('H:i:s')));
            $_POST['date'] = date('Y-m-d');
            $_POST['starttime'] = date('H:i:s');
            $_POST['endtime'] = date('H:i:s', strtotime('2 hour'));
        }

        $cur_date = date('Y-m-d H:i:s');

        $sql1 =
            "SELECT `st_merchant_category`.`id`,`subcategory_id`,`st_merchant_category`.`created_by`,st_users.allow_online_booking
            FROM `st_merchant_category` 
            LEFT JOIN `st_users` ON `st_merchant_category`.`created_by`=`st_users`.`id` 
            WHERE st_users.online_booking= 1 
                AND st_merchant_category.online=1 
                AND (st_users.end_date > '${cur_date}' OR st_users.allow_online_booking = 'true')
                AND `st_merchant_category`.`status` = 'active'" .
            $where." GROUP BY `st_merchant_category`.`created_by`";

        $query1 = $this->db->query($sql1);
        $users = $query1->result();

        // Comment BY Dharmendra for showing Saloon Using CID and Passing CID in All Services rather than Passing category_id

        $userId = [];
        $usersForAll = [];

        if (!empty($users)) {
            foreach ($users as $us) {
                $userId[] = $us->created_by;
            }
        }

        if (!empty($userId)) {
            $sdate = '';
            $edate = '';
            $stime = '';
            $etime = '';

            if (!empty($_POST['date']) && $_POST['date'] != 'Beliebig') {
                $date = date('Y-m-d', strtotime($_POST['date']));

                if (!empty($_POST['starttime'])) {
                    $sdate =
                        $date .
                        ' ' .
                        date('H:i:s', strtotime($_POST['starttime']));
                    $stime = date('H:i:s', strtotime($_POST['starttime']));
                } else {
                    $sdate = $date . ' 00:00:00';
                    $stime = '00:00:00';
                }
                if (!empty($_POST['endtime'])) {
                    $edate =
                        $date .
                        ' ' .
                        date('H:i:s', strtotime($_POST['endtime']));
                    $etime = date('H:i:s', strtotime($_POST['endtime']));
                } else {
                    $edate = $date . ' 23:00:00';
                    $etime = '23:00:00';
                }
            }

            if (
                !empty($_POST['expess_offer']) &&
                $_POST['expess_offer'] == 'yes'
            ) {
                $sdate = date('Y-m-d H:i:s');
                $edate = date('Y-m-d H:i:s', strtotime('2 hour'));
                $stime = date('H:i:s');
                $etime = date('H:i:s', strtotime('2 hour'));
            }

            if (
                (!empty($_POST['date']) && $_POST['date'] != 'Beliebig') ||
                (!empty($_POST['expess_offer']) &&
                    $_POST['expess_offer'] == 'yes')
            ) {
                $tdate = date('Y-m-d', strtotime($_POST['date']));

                $blockEmployees = [];

                $userIdTemp = $userId;
                $userId = [];
                foreach($userIdTemp as $eachUser) {
                    $avtime = "SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND user_id=".$eachUser."";
                    
                    $times = $this->user->custome_query($avtime,'row');
                    if (!empty($times)) {
                        $curtime = date('H:i:s');
                        $start = $times->starttime; //you can write here 00:00:00 but not need to it
                        if ($stime > $start) {
                            $start = $stime;
                        }
                        if ($start < $curtime && $tdate == date('Y-m-d')) {
                            $start = $curtime;
                        }
                        $end   = $times->endtime;
                        if ($etime < $end) {
                            $end = $etime;
                        }
                        if (($end >= $curtime && $tdate == date('Y-m-d')) || $tdate != date('Y-m-d')) {
                            $tStart = strtotime($start);
                            $tEnd   = strtotime(date('H:i:s',strtotime($end. "-15 minutes")));
                            $tNow     = $tStart;
                            // echo '('.$eachUser. '::'.$start.','.$tStart . '::' . $end.','.$tEnd .'::'.$tdate.'::'.date('Y-m-d') .') ';

                            $canbook = 0;
                            while($tNow <= $tEnd){ 
                                                
                                $nowTime = date("H:i:s",$tNow);								


                                $timeArray        = array();           
                                $timeArray[0] = new \stdClass();
                                $timeArray[0]->start = $tdate.' '.$nowTime;
                                $timeArray[0]->end = date('Y-m-d H:i:s',strtotime($timeArray[0]->start.' +15 minute'));

                                // echo '['.$timeArray[0]->start.','.$timeArray[0]->end.']';
                                $resultCheckSlot = checkTimeSlots($timeArray,'',$eachUser,15);
                                if($resultCheckSlot)
                                {
                                    $canbook = 1;
                                    break;
                                }

                                $tNow = strtotime('+15 minutes',$tNow); 
                            }

                            if ($canbook == 1) {
                                $userId[] = $eachUser;
                            }
                        }
                    }
                }
                $usersImp = count($userId) > 0 ? implode(',' , $userId) : "0";

                // $sqlForBookingCheck =
                //     "SELECT employee_id, booking_time, booking_endtime, 
                //         sta.starttime, sta.endtime, 
                //         sta.starttime_two, sta.endtime_two 
                //     FROM st_booking 
                //     LEFT JOIN st_availability AS sta ON sta.user_id=st_booking.employee_id 
                //     WHERE 
                //         merchant_id IN (${usersImp})
                //         AND booking_type=\"self\" 
                //         AND sta.days=\"${dayName}\" 
                //         AND 
                //         (
                //             (
                //                 (sta.type=\"open\" AND blocked_type = \"half\" AND booking_endtime>=\"${edate}\" AND booking_time<=\"${sdate}\" AND block_for = 1) OR
                //                 (blocked_type = \"full\" AND block_for = 1 AND SUBSTRING(booking_endtime,1,10)=\"${tdate}\") OR
                //                 (blocked_type = \"close\" AND close_for = 0 AND SUBSTRING(booking_endtime,1,10)=\"${tdate}\") OR
                //                 (national_holiday = 1 AND SUBSTRING(booking_endtime,1,10)=\"${tdate}\")
                //             )
                //         )
                //     GROUP BY employee_id";

                // $blockedBookings = $this->db->query($sqlForBookingCheck)->result();

                // foreach ($blockedBookings as $booking) {
                //     $blockEmployees[] = $booking->employee_id;
                // }

                // $sqlForBookingDetailCheck =
                //     "SELECT emp_id, setuptime_start, setuptime_end, finishtime_start, finishtime_end,
                //         service_type,
                //         sta.starttime, sta.endtime,
                //         sta.starttime_two, sta.endtime_two 
                //     FROM st_booking_detail 
                //     LEFT JOIN st_availability as sta ON sta.user_id=st_booking_detail.emp_id 
                //     WHERE sta.type='open' AND sta.days='${dayName}' 
                //         AND mer_id IN (${usersImp}) AND show_calender=0 
                //         AND (
                //             (
                //                 (setuptime_end<'${sdate}' AND setuptime_start>'${edate}') 
                //             ) OR (
                //                 service_type=1 AND (
                //                     (finishtime_end<'${sdate}' AND finishtime_start>'${edate}') 
                //                 )
                //             )
                //         )
                //     GROUP BY emp_id";

                // $blockedBookingDetails = $this->db->query($sqlForBookingDetailCheck)->result();

                // foreach ($blockedBookingDetails as $booking) {
                //     $blockEmployees[] = $booking->emp_id;
                // }

                $userIds = [];

                $whereSub = '';

                $blockEmployeesImp = count($blockEmployees) ? implode(", ", $blockEmployees) : "0";

                $sqlForMerchant =
                    "SELECT `st_users`.`merchant_id`, count(st_service_employee_relation.user_id) AS employees 
                    FROM st_users 
                    INNER JOIN st_service_employee_relation ON st_service_employee_relation.user_id=st_users.id 
                    WHERE `st_users`.`status`='active' AND online_booking=1 AND `st_users`.`merchant_id` IN (${usersImp})
                        AND st_service_employee_relation.user_id NOT IN (${blockEmployeesImp}) 
                        ${whereSub} 
                    GROUP BY merchant_id
                    HAVING employees > 0";

                $merchants = $this->db->query($sqlForMerchant)->result();

                if (!empty($merchants)) {
                    foreach ($merchants as $merchant) {
                        $userIds[] = $merchant->merchant_id;
                    }
                }

                $usersForAll = $userIds;

                if (!empty($_POST['sucatgory'])) {
                    $userIds = [];

                    $subcat = implode(',', $_POST['sucatgory']);
                    $whereSub = ' AND subcat_id IN(' . $subcat . ')';

                    $sqlForMerchant =
                        "SELECT `st_users`.`merchant_id`, count(st_service_employee_relation.user_id) AS employees 
                        FROM st_users 
                        INNER JOIN st_service_employee_relation ON st_service_employee_relation.user_id=st_users.id 
                        WHERE `st_users`.`status`='active' AND online_booking=1 AND `st_users`.`merchant_id` IN (${usersImp})
                            AND st_service_employee_relation.user_id NOT IN (${blockEmployeesImp}) 
                            ${whereSub} 
                        GROUP BY merchant_id
                        HAVING employees > 0";

                    $merchants = $this->db->query($sqlForMerchant)->result();

                    if (!empty($merchants)) {
                        foreach ($merchants as $merchant) {
                            $userIds[] = $merchant->merchant_id;
                        }
                    }
                    $userId = $userIds;
                } else {
                    $userId = $userIds;
                }
            } else {
                $userIds = [];

                $whereSub = '';
                $usersImp = implode(',' , $userId);
            
                $sqlForMerchant =
                    "SELECT `merchant_id` 
                    FROM st_users 
                    INNER JOIN st_service_employee_relation ON st_service_employee_relation.user_id=st_users.id 
                    WHERE status='active' AND online_booking=1 AND merchant_id IN (${usersImp}) ${whereSub} 
                    GROUP BY merchant_id";

                $merchantSql = $this->db->query($sqlForMerchant);
                $merchants = $merchantSql->result();

                if (!empty($merchants)) {
                    foreach ($merchants as $merchant) {
                        $userIds[] = $merchant->merchant_id;
                    }
                }

                $usersForAll = $userIds;

                if (!empty($_POST['sucatgory'])) {
                    $userIds = [];

                    $subcat = implode(',', $_POST['sucatgory']);
                    $whereSub = ' AND subcat_id IN(' . $subcat . ')';

                    $sqlForMerchant =
                        "SELECT `merchant_id` 
                        FROM st_users 
                        INNER JOIN st_service_employee_relation ON st_service_employee_relation.user_id=st_users.id 
                        WHERE status='active' AND online_booking=1 AND merchant_id IN (${usersImp}) ${whereSub} 
                        GROUP BY merchant_id";

                    $merchantSql = $this->db->query($sqlForMerchant);
                    $merchants = $merchantSql->result();

                    if (!empty($merchants)) {
                        foreach ($merchants as $merchant) {
                            $userIds[] = $merchant->merchant_id;
                        }
                    }

                    $userId = $userIds;
                } else {
                    $userId = $userIds;
                }
            }
        }

        $totalCount = 0;
        $subcats = [];

        if (!empty($userId) || !empty($usersForAll)) {
            $rowperpage = 15;
            $usersImp = count($userId) > 0 ? implode(',', $userId) : "0";
            $usersAllImp = count($usersForAll) > 0 ? implode(',', $usersForAll) : "0";

            if (!empty($dayName)) {
                $wherePeriod = '';
                $whereDistance = '';

                if (
                    !empty($_POST['starttime']) &&
                    !empty($_POST['endtime']) &&
                    $_POST['time'] != 'Anytime' &&
                    $_POST['time'] != 'Beliebig'
                ) {
                    $wherePeriod =
                        ' AND ((starttime>="' .
                        $_POST['starttime'] .
                        '" AND starttime<="' .
                        $_POST['endtime'] .
                        '") OR (endtime>="' .
                        $_POST['starttime'] .
                        '" AND endtime<="' .
                        $_POST['endtime'] .
                        '") OR (starttime<="' .
                        $_POST['starttime'] .
                        '" AND endtime>="' .
                        $_POST['endtime'] .
                        '") OR (starttime>="' .
                        $_POST['starttime'] .
                        '" AND endtime<="' .
                        $_POST['endtime'] .
                        '"))';
                }

                if (!empty($lng) && !empty($lat)) {
                    $whereDistance = " AND ('6371' * acos( cos( radians($lat) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng)) + sin(radians($lat)) * sin( radians(`latitude`)))) < $distance";
                }

                $sqlForcount =
                    "SELECT count(st_users.id) as totalCount 
                    FROM `st_users` 
                    INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='${dayName}' 
                    WHERE `status` = 'active' AND `access` = 'marchant' 
                    AND `st_availability`.`days`='${dayName}' AND `st_availability`.`type`='open' 
                    AND st_users.id IN (${usersImp}) ${whereDistance} ${wherePeriod}";

                $dataCount = $this->db->query($sqlForcount);
                $count = $dataCount->row();
                $totalCount = $count->totalCount;

                $sqlForServices = 
                    "SELECT 
                        st_service_employee_relation.subcat_id, 
                        count(DISTINCT(st_users.id)) AS user_count
                        FROM st_users
                        INNER JOIN st_service_employee_relation ON st_service_employee_relation.created_by = st_users.id 
                        INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='${dayName}' 
                        WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersAllImp}) 
                            AND `st_availability`.`days`='${dayName}' AND `st_availability`.`type`='open' 
                            ${whereDistance} 
                            ${wherePeriod}
                        GROUP BY st_service_employee_relation.subcat_id";

                $query3 = $this->db->query($sqlForServices);
                $subcats = $query3->result();
                
                $offset = 0;

                if ($page != 0) {
                    $offset = ($page - 1) * $rowperpage;
                }

                $config['base_url'] = base_url() . 'listing/getUserdetails';
                $config['use_page_numbers'] = true;
                $config['total_rows'] = $count->totalCount;
                $config['per_page'] = $rowperpage;
                $config['prev_tag_open'] = '<li class="page-item">';
                $config['prev_tag_close'] = '</li>';
                $config['next_tag_open'] = '<li class="page-item">';
                $config['next_tag_close'] = '</li>';
                $config['num_tag_open'] = '<li class="page-item">';
                $config['num_tag_close'] = '</li>';
                $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
                $config['cur_tag_close'] = '</a></li>';
                $config['next_link'] = '&gt;';
                $config['prev_link'] = '&lt;';
                $config['first_link'] = '&laquo;';
                $config['last_link'] = '&raquo;';

                $this->pagination->initialize($config);

                $pagination = $this->pagination->create_links();

                if (!empty($lng) && !empty($lat)) {
                    $sql =
                        "SELECT `st_users`.`id`, `slug`, `first_name`, `last_name`, `business_name`, 
                                `business_type`, `address`, `latitude`, `longitude`, `country`, `city`, `zip`, 
                                `about_salon`, `image`, `image1`, `image2`, `image3`, `image4`,
                                (SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as rating,
                                (SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='${uid}') as favourite,
                                (SELECT count(id) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as totalcount,
                                `st_availability`.`days`,
                                ('6371' * acos( cos( radians(${lat}) ) * cos( radians(`latitude`)) * cos( radians(`longitude`) - radians(${lng})) + sin(radians(${lat})) * sin( radians(`latitude`)))) AS distance 
                        FROM `st_users` 
                        LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                        INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='${dayName}' 
                        WHERE `status` = 'active' AND `access` = 'marchant' 
                            ${wherePeriod} 
                            ${whereDistance}
                            AND `st_availability`.`days`='${dayName}' 
                            AND `st_availability`.`type`='open' AND st_users.id IN (${usersImp}) 
                        ORDER BY ${order}
                        LIMIT ${rowperpage} 
                        OFFSET ${offset}";
                } else {
                    $sql =
                        "SELECT `st_users`.`id`, `slug`, `first_name`, `last_name`, `business_name`, 
                            `business_type`, `address`,`latitude`, `longitude`, `country`, `city`, `zip`, 
                            `about_salon`,`image`, `image1`, `image2`, `image3`, `image4`,
                            (SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as rating,
                            (SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='${uid}') as favourite,
                            (SELECT count(id) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as totalcount,
                            `st_availability`.`days` 
                        FROM `st_users` 
                        LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                        INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='${dayName}' 
                        WHERE `status` = 'active' AND `access` = 'marchant' 
                            ${wherePeriod} 
                            AND `st_availability`.`days`='${dayName}' 
                            AND `st_availability`.`type`='open' AND st_users.id IN (${usersImp}) 
                        ORDER BY ${order} 
                        LIMIT ${rowperpage} 
                        OFFSET ${offset}";
                }
            } else {
                $whereDistance = "";
                if (!empty($lng) && !empty($lat)) {
                    $whereDistance = " AND ('6371' * acos( cos( radians($lat) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng)) + sin(radians($lat)) * sin( radians(`latitude`)))) < $distance";
                }

                $sqlForcount =
                    "SELECT count(st_users.id) as totalCount 
                    FROM `st_users` 
                    LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                    WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersImp}) ${whereDistance}";
                
                $dataCount = $this->db->query($sqlForcount);
                $count = $dataCount->row();
                $totalCount = $count->totalCount;

                $usersAllImp = implode(',', $usersForAll);
                $sqlForServices = 
                    "SELECT 
                        st_service_employee_relation.subcat_id, 
                        count(DISTINCT(st_users.id)) AS user_count
                        FROM st_users
                        INNER JOIN st_service_employee_relation ON st_service_employee_relation.created_by = st_users.id 
                        WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersAllImp}) ${whereDistance} 
                        GROUP BY st_service_employee_relation.subcat_id";

                $query3 = $this->db->query($sqlForServices);
                $subcats = $query3->result();

                $offset = 0;

                if ($page != 0) {
                    $offset = ($page - 1) * $rowperpage;
                }

                $config['base_url'] = base_url() . 'listing/getUserdetails';
                $config['use_page_numbers'] = true;
                $config['total_rows'] = $count->totalCount;
                $config['per_page'] = $rowperpage;
                $config['prev_tag_open'] = '<li class="page-item">';
                $config['prev_tag_close'] = '</li>';
                $config['next_tag_open'] = '<li class="page-item">';
                $config['next_tag_close'] = '</li>';
                $config['num_tag_open'] = '<li class="page-item">';
                $config['num_tag_close'] = '</li>';
                $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
                $config['cur_tag_close'] = '</a></li class="active">';
                $config['next_link'] = '&gt;';
                $config['prev_link'] = '&lt;';
                $config['first_link'] = '&laquo;';
                $config['last_link'] = '&raquo;';

                $this->pagination->initialize($config);

                $pagination = $this->pagination->create_links();

                if (!empty($lng) && !empty($lat)) {
                    $sql =
                        "SELECT `st_users`.`id`, `slug`, `first_name`, `last_name`, `business_name`, `business_type`,
                            `address`,`latitude`,`longitude`, `country`, `city`,`zip`, `about_salon`,
                            `image`, `image1`, `image2`, `image3`, `image4`,
                            (SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as rating,
                            (SELECT count(id) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as totalcount,
                            (SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='${uid}') as favourite,
                            ('6371' * acos( cos( radians(${lat}) ) * cos( radians(`latitude`)) * cos( radians(`longitude`) - radians(${lng})) + sin(radians(${lat})) * sin( radians(`latitude`)))) AS distance 
                        FROM `st_users` 
                        LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                        WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersImp}) 
                            ${whereDistance}
                        ORDER BY ${order}
                        LIMIT ${rowperpage}
                        OFFSET ${offset}";
                } else {
                    $sql =
                        "SELECT `st_users`.`id`, `slug`, `first_name`, `last_name`, `business_name`, `business_type`, 
                        `address`,`latitude`,`longitude`, `country`, `city`, `zip`, `about_salon`, 
                        `image`, `image1`, `image2`, `image3`, `image4`, 
                        (SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) AS rating, 
                        (SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='${uid}') AS favourite,
                        (SELECT count(id) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) AS totalcount 
                    FROM `st_users` 
                    LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                    WHERE `status` = 'active' AND `access` = 'marchant' AND st_users.id IN (${usersImp}) 
                    ORDER BY ${order} 
                    LIMIT ${rowperpage} 
                    OFFSET ${offset}";
                }
            }

            $query = $this->db->query($sql);
            $this->data['usersdetail'] = $query->result();

            if (!empty($this->data['usersdetail'])) {
                $i = 0;

                foreach ($this->data['usersdetail'] as $usr) {
                    $sql2 =
                        "SELECT `r`.`id`,`duration`, `price`,`price_start_option`, `buffer_time`, `discount_price`, `subcategory_id`, 
                            `u1`.`category_name` AS `m_category`, 
                            `u2`.`category_name` AS `s_category`,
                            IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`r`.`id` AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND online_booking=1 AND status='active')>=1),'0') AS checkemp,
                            (SELECT count(id) FROM st_merchant_category WHERE subcategory_id=r.subcategory_id AND created_by=r.created_by) AS totalservice 
                        FROM `st_merchant_category` `r` 
                        JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` 
                        JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` 
                        WHERE `r`.`status` = 'active' AND r.online=1 AND `r`.`created_by`='$usr->id'
                        $where 
                        GROUP BY `r`.`subcategory_id`
                        LIMIT 10";

                    $query2 = $this->db->query($sql2);
                    $this->data['usersdetail'][
                        $i
                    ]->sercvices = $query2->result();
                    //echo $this->db->last_query();
                    $i++;
                }
            }
        }
        // echo "<pre>"; print_r($this->data); die;
        
        $view = $this->load->view(
            'frontend/user/listing_section',
            $this->data,
            true
        );

        echo json_encode([
            'success' => '1',
            'html' => $view,
            'services' => $subcats,
            'pagination' => $pagination,
            'count' => $totalCount,
        ]);
        die();
    }

    //**** Google marker ****//
    public function get_lat_lng_marker()
    {
        $lat = '';
        $lng = '';
        $distance = 20;
        //print_r($_POST); die;
        if (!empty($_POST['lat'])) {
            $lat = $_POST['lat'];
        }
        if (!empty($_POST['lng'])) {
            $lng = $_POST['lng'];
        }
        if (!empty($_POST['distance'])) {
            $distance = $_POST['distance'] * 1.60934;
        }
        $where = '';

        if (isset($_POST['category']) && !empty($_POST['category'])) {
            $where =
                $where . ' AND category_id =' . url_decode($_POST['category']);
        }

        if (isset($_POST['filtercat']) && !empty($_POST['filtercat'])) {
            $where =
                $where . ' AND filtercat_id =' . url_decode($_POST['filtercat']);
        }
        if (!empty($_POST['sucatgory'])) {
            $subcat = implode(',', $_POST['sucatgory']);
            $where = $where . ' AND subcategory_id IN(' . $subcat . ')';
        }
        if (!empty($_POST['startrange'])) {
            $where =
                $where .
                ' AND ((discount_price!=0 AND discount_price >=' .
                $_POST['startrange'] .
                ') OR(discount_price=0 AND price >=' .
                $_POST['startrange'] .
                '))';
        }

        if (!empty($_POST['endrange'])) {
            $where =
                $where .
                ' AND ((discount_price!=0 AND discount_price<=' .
                $_POST['endrange'] .
                ') OR(discount_price=0 AND price <=' .
                $_POST['endrange'] .
                '))';
        }

        if (!empty($_POST['date']) && $_POST['date'] != 'Beliebig') {
            $dayName = date('l', strtotime($_POST['date']));
            $dayName = strtolower($dayName);
        }
        if (!empty($_POST['expess_offer']) && $_POST['expess_offer'] == 'yes') {
            $dayName = date('l', strtotime(date('H:i:s')));
            $_POST['starttime'] = date('H:i:s');
            $_POST['endtime'] = date('H:i:s', strtotime('2 hour'));
        }

        //$sql="SELECT `id`,`created_by` FROM `st_merchant_category` WHERE `status` = 'active'".$where." GROUP BY created_by";

        $cur_date = date('Y-m-d H:i:s');
        $sql =
            "SELECT `st_merchant_category`.`id`,`st_merchant_category`.`created_by` 
            FROM `st_merchant_category` 
            LEFT JOIN `st_users` ON `st_merchant_category`.`created_by`=`st_users`.`id` 
            WHERE st_users.online_booking= 1 AND st_merchant_category.online=1 AND st_users.end_date > '" .
            $cur_date .
            "' AND `st_merchant_category`.`status` = 'active'" .
            $where .
            ' GROUP BY st_merchant_category.created_by';

        $query = $this->db->query($sql);
        $users = $query->result();
        //echo $this->db->last_query(); die;
        $userId = [];
        if (!empty($users)) {
            foreach ($users as $us) {
                $userId[] = $us->created_by;
            }
        }

        if (!empty($userId)) {
            $sdate = '';
            $edate = '';
            $stime = '';
            $etime = '';
            if (!empty($_POST['date']) && $_POST['date'] != 'Beliebig') {
                $date = date('Y-m-d', strtotime($_POST['date']));
                if (!empty($_POST['starttime'])) {
                    $sdate =
                        $date .
                        ' ' .
                        date('H:i:s', strtotime($_POST['starttime']));
                    $stime = date('H:i:s', strtotime($_POST['starttime']));
                } else {
                    $sdate = $date . ' 00:00:00';
                    $stime = '00:00:00';
                }
                if (!empty($_POST['endtime'])) {
                    $edate =
                        $date .
                        ' ' .
                        date('H:i:s', strtotime($_POST['endtime']));
                    $etime = date('H:i:s', strtotime($_POST['endtime']));
                } else {
                    $edate = $date . ' 23:59:00';
                    $etime = '23:59:00';
                }
            }
            // echo $sdate."--".$edate;

            if (
                !empty($_POST['expess_offer']) &&
                $_POST['expess_offer'] == 'yes'
            ) {
                $sdate = date('Y-m-d H:i:s');
                $edate = date('Y-m-d H:i:s', strtotime('2 hour'));
                $stime = date('H:i:s');
                $etime = date('H:i:s', strtotime('2 hour'));
            }

            if (
                (!empty($_POST['date']) && $_POST['date'] != 'Beliebig') ||
                (!empty($_POST['expess_offer']) &&
                    $_POST['expess_offer'] == 'yes')
            ) {
                // print_r($userId); die;
                $userIds = [];
                foreach ($userId as $uisrid) {
                    // echo $uisrid;
                    $whereSub = '';
                    if (!empty($_POST['sucatgory'])) {
                        $subcat = implode(',', $_POST['sucatgory']);
                        $whereSub = ' AND subcat_id IN(' . $subcat . ')';
                    }

                    $checkEmpavailablity = '';
                    $blockSubWhere = '';
                    $wh = '';
                    if (
                        !empty($_POST['date']) &&
                        (strtolower($_POST['time']) == 'anytime' ||
                            strtolower($_POST['time']) == 'beliebig')
                    ) {
                        $blockSubWhere .=
                            ',(SELECT count(id) FROM st_booking WHERE booking_type="self" AND employee_id=st_users.id AND booking_time<="' .
                            $sdate .
                            '" AND booking_endtime>="' .
                            $edate .
                            '") as booking';
                    }

                    $bookingdetails = 0;
                    $employessCount = [];
                    if (
                        !empty($stime) &&
                        !empty($etime) &&
                        strtolower($_POST['time']) != 'anytime' &&
                        strtolower($_POST['time']) != 'beliebig'
                    ) {
                        $getuserBookingDetailsBlockqury =
                            'SELECT employee_id,booking_time,booking_endtime,(SELECT count(id) FROM st_service_employee_relation WHERE user_id=employee_id ' .
                            $whereSub .
                            ') as employess,sta.starttime,sta.endtime,sta.starttime_two,sta.endtime_two FROM st_booking LEFT JOIN st_availability as sta ON sta.user_id=st_booking.employee_id WHERE booking_type="self" AND merchant_id="' .
                            $uisrid .
                            '" AND sta.type="open" AND sta.days="' .
                            $dayName .
                            '" AND ((booking_time>="' .
                            $sdate .
                            '" AND booking_time<="' .
                            $edate .
                            '") OR (booking_endtime>="' .
                            $sdate .
                            '" AND booking_endtime<="' .
                            $edate .
                            '") OR (booking_time<="' .
                            $sdate .
                            '" AND booking_endtime>="' .
                            $sdate .
                            '") OR (booking_time>="' .
                            $sdate .
                            '" AND booking_endtime<="' .
                            $edate .
                            '")) HAVING employess>0';

                        $getuserBookingDetailsBlockSql = $this->db->query(
                            $getuserBookingDetailsBlockqury
                        );
                        $getuserBookingDetailsBlock = $getuserBookingDetailsBlockSql->result();

                        /*if($uisrid==25){
		            	print_r($getuserBookingDetailsBlock); die;
		            }*/

                        $timesbooking = [];
                        if (!empty($getuserBookingDetailsBlock)) {
                            foreach ($getuserBookingDetailsBlock as $value) {
                                $closingtime = '';

                                if (!empty($value->endtime_two)) {
                                    $closingtime = $value->endtime_two;
                                } elseif (!empty($value->endtime)) {
                                    $closingtime = $value->endtime;
                                }

                                $openingtime = '';

                                if (!empty($value->starttime)) {
                                    $openingtime = $value->starttime;
                                } elseif (!empty($value->starttime_two)) {
                                    $openingtime = $value->starttime_two;
                                }

                                $settime = [
                                    'start' => $value->booking_time,
                                    'end' => $value->booking_endtime,
                                    'openingtime' => $openingtime,
                                    'closingtime' => $closingtime,
                                ];
                                $timesbooking[$value->employee_id][] = $settime;
                            }
                        }

                        /*$getuserBookingDetailsqury = 'SELECT emp_id,setuptime_start,setuptime_end,finishtime_start,finishtime_end,service_type,(SELECT count(id) FROM st_service_employee_relation WHERE user_id=emp_id '.$whereSub.') as employess FROM st_booking_detail WHERE mer_id="'.$uisrid.'" AND show_calender=0 AND ((setuptime_start>="'.$sdate.'" AND setuptime_start<="'.$edate.'") OR ((service_type=0 AND (setuptime_end>="'.$sdate.'" AND setuptime_end<="'.$edate.'")) OR (service_type=1 AND (finishtime_end>="'.$sdate.'" AND finishtime_end<="'.$edate.'"))) OR (setuptime_start<="'.$sdate.'" AND ((service_type=0 AND setuptime_start>="'.$edate.'") OR (service_type=1 AND setuptime_start>="'.$edate.'"))) OR (setuptime_start>="'.$sdate.'" AND ((service_type=0 AND setuptime_end<="'.$edate.'") OR (service_type=1 AND finishtime_end<="'.$edate.'")))) HAVING employess>0';*/

                        $wh123 =
                            " AND (((setuptime_start>='" .
                            $sdate .
                            "' AND setuptime_start<'" .
                            $edate .
                            "') OR (setuptime_end>'" .
                            $sdate .
                            "' AND setuptime_end<='" .
                            $edate .
                            "') OR (setuptime_start<='" .
                            $sdate .
                            "' AND setuptime_end>'" .
                            $sdate .
                            "') OR (setuptime_start>'" .
                            $sdate .
                            "' AND setuptime_end<='" .
                            $edate .
                            "')) OR (service_type=1 AND ((finishtime_start>='" .
                            $sdate .
                            "' AND finishtime_start<'" .
                            $edate .
                            "') OR (finishtime_end>'" .
                            $sdate .
                            "' AND finishtime_end<='" .
                            $edate .
                            "') OR (finishtime_start<='" .
                            $sdate .
                            "' AND finishtime_end>'" .
                            $sdate .
                            "') OR (finishtime_start>'" .
                            $sdate .
                            "' AND finishtime_end<='" .
                            $edate .
                            "'))))";

                        $getuserBookingDetailsqury =
                            'SELECT emp_id,setuptime_start,setuptime_end,finishtime_start,finishtime_end,service_type,(SELECT count(id) FROM st_service_employee_relation WHERE user_id=emp_id ' .
                            $whereSub .
                            ') as employess,sta.starttime,sta.endtime,sta.starttime_two,sta.endtime_two FROM st_booking_detail LEFT JOIN st_availability as sta ON sta.user_id=st_booking_detail.emp_id WHERE sta.type="open" AND sta.days="' .
                            $dayName .
                            '" AND mer_id="' .
                            $uisrid .
                            '" AND show_calender=0 ' .
                            $wh123 .
                            ' HAVING employess>0';

                        $getuserBookingDetailsSql = $this->db->query(
                            $getuserBookingDetailsqury
                        );
                        $getuserBookingDetails = $getuserBookingDetailsSql->result();

                        /* if($uisrid==25){
		            				echo $this->db->last_query().'<pre>'; print_r($getuserBookingDetails); die;
		            	 }
		            */

                        if (!empty($getuserBookingDetails)) {
                            foreach ($getuserBookingDetails as $value) {
                                $closingtime = '';

                                if (!empty($value->endtime_two)) {
                                    $closingtime = $value->endtime_two;
                                } elseif (!empty($value->endtime)) {
                                    $closingtime = $value->endtime;
                                }

                                $openingtime = '';

                                if (!empty($value->starttime)) {
                                    $openingtime = $value->starttime;
                                } elseif (!empty($value->starttime_two)) {
                                    $openingtime = $value->starttime_two;
                                }

                                $settime = [
                                    'start' => $value->setuptime_start,
                                    'end' => $value->setuptime_end,
                                    'openingtime' => $openingtime,
                                    'closingtime' => $closingtime,
                                ];

                                $timesbooking[$value->emp_id][] = $settime;

                                if ($value->service_type == 1) {
                                    $settime = [
                                        'start' => $value->finishtime_start,
                                        'end' => $value->finishtime_end,
                                        'openingtime' => $openingtime,
                                        'closingtime' => $closingtime,
                                    ];

                                    $timesbooking[$value->emp_id][] = $settime;
                                }
                            }
                        }
                        //echo '<pre>'; print_r($timesbooking);
                        if (!empty($timesbooking)) {
                            $timesbookingRepleat = [];

                            //$employessCount = count($timesbooking);

                            foreach ($timesbooking as $key => $row) {
                                asort($timesbooking[$key]);
                                $timesbookingRepleat[$key] = array_values(
                                    $timesbooking[$key]
                                );
                            }

                            //asort($timesbooking);
                            foreach ($timesbookingRepleat as $key => $row) {
                                $ijk = 0;
                                $employessCount[] = $key;
                                //asort($timesbooking[$key]);
                                //echo '<pre>'; print_r($timesbooking[$key]); die;
                                foreach ($timesbookingRepleat[$key] as $row2) {
                                    if (
                                        !empty($row2['openingtime']) &&
                                        $row2['openingtime'] > $stime
                                    ) {
                                        $sdate1 =
                                            date(
                                                'Y-m-d',
                                                strtotime($_POST['date'])
                                            ) .
                                            ' ' .
                                            $row2['openingtime'];
                                        //$edate1  = $edate;
                                    } else {
                                        $sdate1 = $sdate;
                                        // $edate1  = $edate;
                                    }

                                    if (
                                        !empty($row2['closingtime']) &&
                                        $row2['closingtime'] < $etime
                                    ) {
                                        $edate1 =
                                            date(
                                                'Y-m-d',
                                                strtotime($_POST['date'])
                                            ) .
                                            ' ' .
                                            $row2['closingtime'];
                                        // $edate1  = $edate;
                                    } else {
                                        //$sdate1  = $sdate;
                                        $edate1 = $edate;
                                    }
                                    /*if($uisrid==25){
		            			  echo $row2['start'].'=='.$sdate1.'=='.$edate1.'<pre>'; print_r($timesbooking[$key][$ijk]); die;
		            		    }*/
                                    if (
                                        $row2['start'] <= $sdate1 &&
                                        $row2['end'] >= $edate1
                                    ) {
                                        //echo '1s';
                                        break;
                                    }
                                    if (
                                        $sdate1 < $row2['start'] ||
                                        $edate1 >
                                            $timesbookingRepleat[$key][
                                                count(
                                                    $timesbookingRepleat[$key]
                                                ) - 1
                                            ]['end']
                                    ) {
                                        if (
                                            $ijk == 0 &&
                                            $row2['start'] > $sdate1
                                        ) {
                                            $bookingdetails = 1;
                                            //echo $uisrid.'==1y<br/>';
                                            break;
                                        } elseif (
                                            $ijk != 0 &&
                                            $row2['start'] >
                                                $timesbookingRepleat[$key][
                                                    $ijk - 1
                                                ]['end']
                                        ) {
                                            $bookingdetails = 1;
                                            //echo $uisrid.'=='.$ijk.$row2['start'].'2y'.$timesbookingRepleat[$key][$ijk-1]['end'].'<br/>';
                                            break;
                                            //echo $row2[$ijk-1]['start']; die;
                                            //$row2['start'];
                                        } elseif (
                                            $ijk != 0 &&
                                            $row2['start'] <=
                                                $timesbookingRepleat[$key][
                                                    $ijk - 1
                                                ]['end'] &&
                                            $row2['end'] >= $edate1
                                        ) {
                                            //echo '2s';
                                            break;
                                        } elseif (
                                            $ijk != 0 &&
                                            $row2['start'] <=
                                                $timesbookingRepleat[$key][
                                                    $ijk - 1
                                                ]['end'] &&
                                            $row2['end'] >= $edate1
                                        ) {
                                            //echo '3s';
                                            break;
                                        } elseif (
                                            $ijk ==
                                                count(
                                                    $timesbookingRepleat[$key]
                                                ) -
                                                    1 &&
                                            $row2['end'] < $edate1
                                        ) {
                                            //echo $uisrid.'==3y<br/>';
                                            $bookingdetails = 1;
                                            break;
                                        }
                                    }
                                    //echo $row2['start'];
                                    $ijk++;

                                    //echo $row2['start'].' '.$row2['end'].'<br/>';
                                }
                            }
                        } else {
                            $bookingdetails = 1;
                        }

                        $checkEmpavailablity =
                            ' AND (((starttime>="' .
                            $stime .
                            '" AND starttime<="' .
                            $etime .
                            '") OR (endtime>"' .
                            $stime .
                            '" AND endtime<="' .
                            $etime .
                            '") OR (starttime<="' .
                            $stime .
                            '" AND endtime>"' .
                            $stime .
                            '")OR (starttime>="' .
                            $stime .
                            '" AND endtime<="' .
                            $etime .
                            '")) OR ((starttime_two>="' .
                            $stime .
                            '" AND starttime_two<="' .
                            $etime .
                            '") OR (endtime_two>"' .
                            $stime .
                            '" AND endtime_two<="' .
                            $etime .
                            '") OR (starttime_two<="' .
                            $stime .
                            '" AND endtime_two>"' .
                            $stime .
                            '")OR (starttime_two>="' .
                            $stime .
                            '" AND endtime_two<="' .
                            $etime .
                            '")))';
                    } else {
                        $bookingdetails = 1;
                    }

                    $sqlForemp =
                        'SELECT `st_users`.`id` ' .
                        $blockSubWhere .
                        $wh .
                        ',(SELECT count(id) FROM st_service_employee_relation WHERE user_id=st_users.id ' .
                        $whereSub .
                        ") as employess FROM st_users INNER JOIN `st_availability` ON `st_availability`.`user_id`=`st_users`.`id` AND `st_availability`.`days`='" .
                        $dayName .
                        "'  WHERE status='active' AND online_booking=1 AND access='employee' AND st_availability.type='open' " .
                        $checkEmpavailablity .
                        ' AND merchant_id=' .
                        $uisrid .
                        ' HAVING employess>0';

                    $empleeSql = $this->db->query($sqlForemp);
                    $emplee = $empleeSql->result();
                    /* if($uisrid==25){
		       //echo $this->db->last_query(); 
		       echo $bookingdetails.count($employessCount).'<pre>'; print_r($emplee); die;
		       }*/
                    if (!empty($emplee)) {
                        $totalEmpcount = count($emplee);

                        if (
                            empty($employessCount) ||
                            count($employessCount) < $totalEmpcount
                        ) {
                            $bookingdetails = 1;
                        }

                        foreach ($emplee as $emp) {
                            if (
                                (!empty($bookingdetails) ||
                                    !in_array($emp->id, $employessCount)) &&
                                empty($emp->booking) &&
                                !empty($emp->employess)
                            ) {
                                $userIds[] = $uisrid;
                                break;
                            }
                        }
                    }
                }
                $userId = $userIds;
            } else {
                $userIds = [];
                foreach ($userId as $uisrid) {
                    // echo $uisrid;
                    $whereSub = '';
                    if (!empty($_POST['sucatgory'])) {
                        $subcat = implode(',', $_POST['sucatgory']);
                        $whereSub = ' AND subcat_id IN(' . $subcat . ')';
                    }
                    $sqlForemp =
                        'SELECT `id`,(SELECT count(id) FROM st_service_employee_relation WHERE user_id=st_users.id ' .
                        $whereSub .
                        ") as employess FROM st_users WHERE status='active' AND online_booking=1 AND merchant_id=" .
                        $uisrid;

                    $empleeSql = $this->db->query($sqlForemp);
                    $emplee = $empleeSql->result();
                    // echo $this->db->last_query(); die;
                    if (!empty($emplee)) {
                        foreach ($emplee as $emp) {
                            if (!empty($emp->employess)) {
                                $userIds[] = $uisrid;
                                break;
                            }
                        }
                    }
                }
                $userId = $userIds;
            }
        }

        if (!empty($userId)) {
            //$user= implode(',',$userId);
            if (!empty($dayName)) {
                $whereAS = '';
                if (
                    !empty($_POST['time']) &&
                    !empty($_POST['endtime']) &&
                    ($_POST['time'] != 'anytime' || $_POST['time'] != 'beliebig')
                ) {
                    $whereAS =
                        ' AND ((starttime>="' .
                        $_POST['starttime'] .
                        '" AND starttime<="' .
                        $_POST['endtime'] .
                        '") OR (endtime>="' .
                        $_POST['starttime'] .
                        '" AND endtime<="' .
                        $_POST['endtime'] .
                        '") OR (starttime<="' .
                        $_POST['starttime'] .
                        '" AND endtime>="' .
                        $_POST['endtime'] .
                        '") OR (starttime>="' .
                        $_POST['starttime'] .
                        '" AND endtime<="' .
                        $_POST['endtime'] .
                        '"))';
                }

                if (!empty($lat) && !empty($lng)) {
                    $sql1 =
                        "SELECT `st_users`.`id`, `first_name`, `last_name`, `business_name`, `business_type`, `address`,`latitude`,`longitude`, `country`, `city`,`zip`, `about_salon`,`st_availability`.`days` FROM `st_users` INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='" .
                        $dayName .
                        "' WHERE `status` = 'active' AND `access` = 'marchant' " .
                        $whereAS .
                        " AND `st_availability`.`days`='" .
                        $dayName .
                        "' AND `st_availability`.`type`='open' AND st_users.id IN (" .
                        implode(',', $userId) .
                        ") AND ( '6371' * acos( cos( radians($lat) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng)) + sin(radians($lat)) * sin( radians(`latitude`)))) < $distance ";
                } else {
                    $sql1 =
                        "SELECT `st_users`.`id`, `first_name`, `last_name`, `business_name`, `business_type`, `address`,`latitude`,`longitude`, `country`, `city`,`zip`, `about_salon`,`st_availability`.`days` FROM `st_users` INNER JOIN `st_availability` ON `st_users`.`id`=`st_availability`.`user_id` AND `st_availability`.`days`='" .
                        $dayName .
                        "' WHERE `status` = 'active' AND `access` = 'marchant' " .
                        $whereAS .
                        " AND `st_availability`.`days`='" .
                        $dayName .
                        "' AND `st_availability`.`type`='open' AND st_users.id IN (" .
                        implode(',', $userId) .
                        ')';
                }
            } else {
                if (!empty($lat) && !empty($lng)) {
                    $sql1 =
                        "SELECT `id`, `first_name`, `last_name`, `business_name`,`latitude`,`longitude` FROM `st_users` WHERE `status` = 'active' AND id IN (" .
                        implode(',', $userId) .
                        ") AND `access` = 'marchant' AND ( '6371' * acos( cos( radians($lat) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng)) + sin(radians($lat)) * sin( radians(`latitude`)))) < $distance";
                } else {
                    $sql1 =
                        "SELECT `id`, `first_name`, `last_name`, `business_name`,`latitude`,`longitude` FROM `st_users` WHERE `status` = 'active' AND id IN (" .
                        implode(',', $userId) .
                        ") AND `access` = 'marchant'";
                }
            }
            $query1 = $this->db->query($sql1);
            $latlong = $query1->result();
            //echo $this->db->last_query(); die;
            if (!empty($latlong)) {
                echo json_encode(['success' => '1', 'latlng' => $latlong]);
                die();
            } else {
                echo json_encode(['success' => '0', 'latlng' => '']);
                die();
            }
        } else {
            echo json_encode(['success' => '0', 'latlng' => '']);
        }
        die();
    }

    //**** get merchant available ****//
    function get_merchant_availablity()
    {
        $sql =
            'SELECT `id`,`days`,`type`,`starttime`,`endtime` FROM `st_availability` WHERE `user_id` =' .
            $_POST['uid'] .
            '';
        $query = $this->db->query($sql);
        $availablity = $query->result();
        //echo "<pre>"; print_r($availablity['days']); die;
        $days = [];
        $type = [];
        $stime = [];
        $etime = [];
        if (!empty($availablity)) {
            foreach ($availablity as $avb) {
                $days[] = $avb->days;
                $type[] = $avb->type;
                $stime[] = $avb->starttime;
                $etime[] = $avb->endtime;
            }
        }
        $days_array = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];
        $html = '';
        $i = 0;
        foreach ($days_array as $day) {
            $mt = '';
            if ($i != 0) {
                $mt = 'mt-10';
            }
            if (in_array($day, $days)) {
                //print_r(in_array($day, $days));die;
                if ($day == 'sunday') {
                    $day = 'Sonntag';
                }
                if ($day == 'monday') {
                    $day = 'Montag';
                }
                if ($day == 'tuesday') {
                    $day = 'Dienstag';
                }
                if ($day == 'wednesday') {
                    $day = 'Mittwoch';
                }
                if ($day == 'thursday') {
                    $day = 'Donnerstag';
                }
                if ($day == 'friday') {
                    $day = 'Freitag';
                }
                if ($day == 'saturday') {
                    $day = 'Samstag';
                }

                if ($type[$i] == 'open') {
                    $html =
                        $html .
                        '<div class="d-flex border-w border-radius4 bgwhite pt-10 pb-10 px-3 ' .
                        $mt .
                        '"><span class="color333 font-size-14 fontfamily-medium">' .
                        ucfirst($day) .
                        '</span><span class="color999 font-size-14 fontfamily-regular ml-auto">' .
                        date('H:i', strtotime($stime[$i])) .
                        ' - ' .
                        date('H:i', strtotime($etime[$i])) .
                        '</span></div>';
                } else {
                    $html =
                        $html .
                        '<div class="d-flex bge8e8e8 border-radius4 pt-10 pb-10 px-3  ' .
                        $mt .
                        '"><span class="colorwhite font-size-14 fontfamily-medium">' .
                        ucfirst($day) .
                        '</span><span class="colorwhite font-size-14 fontfamily-regular ml-auto">Geschlossen</span></div>';
                }
            } else {
                $html =
                    $html .
                    '<div class="d-flex bge8e8e8 border-radius4 pt-10 pb-10 px-3  ' .
                    $mt .
                    '"><span class="colorwhite font-size-14 fontfamily-medium">' .
                    ucfirst($day) .
                    '</span><span class="colorwhite font-size-14 fontfamily-regular ml-auto">Closed</span></div>';
            }
            $i++;
        }
        echo json_encode(['success' => '1', 'dayhtml' => $html]);
        die();
    }

    //**** Get salon on popup ****//
    public function get_salon_on_popup()
    {
        $where = '';
        $usid = $this->session->userdata('st_userid');
        if (isset($_POST['category']) && !empty($_POST['category'])) {
            $where =
                $where . ' AND category_id =' . url_decode($_POST['category']);
        }
        if (!empty($_POST['sucatgory'])) {
            $subcat = implode(',', $_POST['sucatgory']);
            $where = $where . ' AND subcategory_id IN(' . $subcat . ')';
        }
        if (!empty($_POST['startrange'])) {
            $where = $where . ' AND price >=' . $_POST['startrange'];
        }
        if (!empty($_POST['endrange'])) {
            $where = $where . ' AND price <=' . $_POST['endrange'];
        }

        $sql =
            "SELECT `st_users`.`id`,`slug`, `first_name`, `last_name`, `business_name`,(SELECT count(id) FROM st_favourite WHERE salon_id=`st_users`.`id` AND user_id='" .
            $usid .
            "') as favourite, `business_type`, `address`,`latitude`,`longitude`, `country`, `city`,`zip`, `about_salon`,`image`, `image1`, `image2`, `image3`, `image4`,(SELECT AVG(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as rating, (SELECT COUNT(rate) FROM st_review WHERE merchant_id=`st_users`.`id` AND user_id !=0) as ratingcnt FROM `st_users` LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` WHERE `st_users`.`id` IN(" .
            $_POST['uids'] .
            ')';

        $query = $this->db->query($sql);
        $this->data['usersdetail'] = $query->result();

        if (!empty($this->data['usersdetail'])) {
            $i = 0;
            foreach ($this->data['usersdetail'] as $usr) {
                $sql2 =
                    "SELECT `r`.`id`,`duration`, `price`, `buffer_time`, `discount_price`,`subcategory_id`, `u1`.`category_name` as `m_category`, `u2`.`category_name` as `s_category` FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` WHERE `r`.`status` = 'active' AND `r`.`created_by`=" .
                    $usr->id .
                    $where .
                    ' GROUP BY `r`.`subcategory_id` LIMIT 4';
                $query2 = $this->db->query($sql2);
                $this->data['usersdetail'][$i]->sercvices = $query2->result();
                //echo $this->db->last_query();
                $i++;
            }
        }

        //echo "<pre>"; print_r($this->data); die;

        $view = $this->load->view(
            'frontend/user/listing_popup_section',
            $this->data,
            true
        );

        if (count($this->data['usersdetail']) == 1) {
            echo json_encode([
                'success' => '1',
                'html' => $view,
                'place' => $this->data['usersdetail'][0]->address .', '. $this->data['usersdetail'][0]->zip .' '. $this->data['usersdetail'][0]->city .', '.$this->data['usersdetail'][0]->country,
                'lat' => $this->data['usersdetail'][0]->latitude,
                'lng' => $this->data['usersdetail'][0]->longitude,
            ]);
            die();
        } else {
            echo json_encode(['success' => '1', 'html' => $view]);
            die();
        }
    }

    //**** My favourite salon ****//
    public function myfavourite()
    {
        $status = $_POST['status'];
        $usid = $this->session->userdata('st_userid');
        //if($status =='add'){
        if ($this->session->userdata('access') != 'user' || $usid == '') {
            echo json_encode(['success' => '0']);
            die();
        }

        if (
            $this->user->countResult('st_favourite', [
                'salon_id' => $_POST['salon_id'],
                'user_id' => $usid,
            ]) > 0
        ) {
            $res = $this->user->delete('st_favourite', [
                'user_id' => $usid,
                'salon_id' => $_POST['salon_id'],
            ]);
            echo json_encode(['success' => '1']);
        } else {
            $insertArr['user_id'] = $usid;
            $insertArr['salon_id'] = $_POST['salon_id'];
            $insertArr['created_on'] = date('Y-m-d H:i:s');
            $insertArr['created_by'] = $usid;
            $res = $this->user->insert('st_favourite', $insertArr);
            echo json_encode(['success' => '1']);
        }
        //echo $res;
    }
}
