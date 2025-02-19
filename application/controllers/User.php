<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Frontend_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library('upload');
        $this->load->library('image_moo');
        $this->load->model('Booking_model', 'booking');

        $usid = $this->session->userdata('st_userid');

        if (!empty($usid)) {
            $status = getstatus_row($usid);
            if ($status != 'active') {
                redirect(base_url('auth/logouts/') . $status);
            }
        }
        //$this->load->model('Ion_auth_model','ion_auth');
    }

    //***** user registration ****//
    public function registration()
    {
        if (!empty($this->session->userdata('st_userid'))) {
            redirect(base_url());
        } else {
            $this->load->view('frontend/user_registration');
        }
    }
    //**** thankyou after reg ****//
    public function thankyou()
    {
        if (!empty($this->session->userdata('st_userid'))) {
            redirect(base_url());

        } else {
            //$_SESSION['reguser_email']='mnma@malinator.com';
            $this->load->view('frontend/thank_you');
        }
    }
    //***** change image ****//
    public function changeImage()
    {
        $allowed = array('gif', 'png', 'jpg', 'jpeg', 'svg');
        $uid = $this->session->userdata('st_userid');
        if ($this->session->userdata('access') == 'employee') {
            $path = 'assets/uploads/employee/' . $uid . '/';
        } else {
            $path = 'assets/uploads/users/' . $uid . '/';
        }

        $filename = explode('.', $_FILES["profile_pic"]["name"]);
        $ext = $filename[count($filename) - 1];
        $ext = strtolower($ext);
        if (!is_dir($path)) {@mkdir($path, 0777, true);}

        if (isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] != "" && (!in_array($ext, $allowed) || $_FILES["profile_pic"]["size"] > 4000000)) {
            echo $obj = json_encode(array('success' => 0, 'html' => 'ImageErr'));die;
        }

        if (isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] != "") {
            //print_r($_FILES); die;
            //$created_on = date_to_path($this->category_model->Read(array('id'=>$id), array('created_on'))[0]->created_on);
            $filename = explode('.', $_FILES["profile_pic"]["name"]);
            $_FILES['image']['name'] = 'Prf_' . time() . '.' . $filename[count($filename) - 1];

            $uid = $this->session->userdata('st_userid');
            array_insert($config, array('upload_path' => $path, "allowed_types" => 'gif|jpg|jpeg|png|svg|JPG|JPEG|PNG|SVG'));
            //array_map('unlink', glob($config['upload_path'].'*.*'));
            @mkdir($config['upload_path'], 0777, true);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('profile_pic')) {
                // echo $this->upload->display_errors();
                echo json_encode(['success' => 0, 'message' => strip_tags($this->upload->display_errors()), 'return_url' => '']);
                //$this->session->set_flashdata('error',$this->upload->display_errors());
                //redirect(admin_url("{$this->classname}/make?id=$id"));
            } else {
                $data = array('upload_data' => $this->upload->data());
                $image_info = $this->upload->data();
                array_insert($config, array('image_library' => 'gd2', 'source_image' => $config['upload_path'] . $image_info['file_name'], 'maintain_ratio' => false));
                //ini_set('memory_limit','128M');

                foreach (array("thumb_" => array(250, 250), "icon_" => array(115, 115)) as $key => $val) {
                    $this->image_moo->load($config['upload_path'] . $image_info['file_name'])->resize_crop($val[0], $val[1])->save($config['upload_path'] . $key . $image_info['file_name'], true);
                }
                //$this->image_lib->initialize($config)->resize();

                $filename = $image_info['file_name'];
                $Count = $this->user->update('st_users', array('profile_pic' => $filename), array('id' => $uid));
                if ($Count) {
                    if ($_POST['old_image'] != '') {
                        if (file_exists($path . $_POST['old_image'])) {
                            unlink($path . $_POST['old_image']);
                            unlink($path . 'icon_' . $_POST['old_image']);
                            unlink($path . 'thumb_' . $_POST['old_image']);
                        }
                    }

                    $this->session->set_userdata('sty_profile', 'thumb_' . $filename);
                    echo $obj = json_encode(array('success' => 1, 'html' => '<img style="width:115px; height:115px;" src="' . base_url($path . 'thumb_' . $filename) . '" alt="" class="responsive">'));die;
                } else {echo $obj = json_encode(array('success' => 0, 'html' => ''));die;}

            }

            //~ $this->load->library('image_moo');
            //~ $filename = 'Prf_'.time().$this->session->userdata('user_id').'.'.$filename[count($filename)-1];
            //~ $tmpfile = $_FILES["profile_pic"]["tmp_name"];
            //~ $uploadfil = move_uploaded_file($tmpfile, $path.$filename);

            //~ foreach(array("thumb_"=>array(250,250),"icon_"=>array(115,115)) as $key=>$val){
            //~ $this->image_moo->load($path.$filename)->resize($val[0], $val[1])->save("{$path}{$key}{$filename}",false);

            //~ }
            //~ $Count = $this->user->update('st_users',array('profile_pic'=>$filename),array('id'=>$uid));

            //~ if($Count){
            //~ if($_POST['old_image'] !=''){
            //~ if (file_exists($path.$_POST['old_image']))
            //~ {
            //~ unlink($path.$_POST['old_image']);
            //~ unlink($path.'icon_'.$_POST['old_image']);
            //~ unlink($path.'thumb_'.$_POST['old_image']);
            //~ }
            //~ }

            //~ $this->session->set_userdata('sty_profile','thumb_'.$filename);
            //~ echo $obj = json_encode(array('success'=>1,'html'=>'<img style="width:115px; height:115px;" src="'.base_url($path.'thumb_'.$filename).'" alt="" class="responsive">')); die; }
            //~ else{ echo $obj = json_encode(array('success'=>0,'html'=>'')); die; }
        }

    }
    public function getgallerydetail() {
        
        $id = $_POST['id'];

        $gal = $this->user->select_row('st_gallery_banner_images', '*', array('id' => $id));

        $emp = $this->user->select_row('st_users','*', array('id' => $gal->employee_id));
        $cat = $this->user->select_row('st_merchant_category','*', array('id' => $gal->category_id));
        $sub = $this->user->select_row('st_category','*', array('id' => $cat->subcategory_id));
        $mcat = $this->user->select_row('st_category','*', array('id' => $cat->category_id));

        $imgsrc = $emp->profile_pic !='' ? (base_url('assets/uploads/employee/').$emp->id.'/'.$emp->profile_pic) : (base_url('assets/frontend/images/user-icon-gret.svg'));

        $rate = $this->user->custome_query('SELECT AVG(rate) as reviewrate FROM st_review WHERE emp_id='.$emp->id, 'result');
        $rcnt = $this->user->custome_query('SELECT COUNT(rate) as cnt FROM st_review WHERE emp_id='.$emp->id, 'result');

        $rate = $rate[0]->reviewrate ? $rate[0]->reviewrate : 0;
        $rate = number_format($rate, 1);

        $img = '<img width="30" height="30" class="mr-2" src="'.getimge_url('assets/uploads/category_icon/'.$mcat->id.'/',$mcat->icon,'png').'"/>';
        $rcnt  = $rcnt ? ($rcnt[0]->cnt . ' Bewertungen') : 'noch keine Bewertungen';
        $html = '<h5> <b>Service</b> </h5> <br/>';
        $html .= ('<div class="d-flex flex-row" style="align-items:center;">'.$img.'<h6 style="margin-bottom:0px;">'.($cat->name ? $sub->category_name . ' - ' . $cat->name: $sub->category_name) . ' <ul style="display:inline-block;margin-bottom:0px;padding-inline-start:30px;"><li style="list-style-type: disc;">' .$cat->duration.' Min.</li><ul/></h6></div><br/><br/>');
        $html .= '<h5> <b>Der Stylist</b> </h5> <br/>';
        $html .= ('<div><label class="vertical-middle pt-2" style="height: 60px;"><img style="width:50px;height:50px;" class="employee-round-icon display-ib" src="'.$imgsrc.'"/>
        <span style="font-size: 1rem" id="empId" data-val="'.url_encode($gal->employee_id).'">'.$emp->first_name).' </span></label>';
        $html .= '<div style="padding-left: 65px;"><i class="fas fa-star colororange font-size-20"></i><span class="ml-1" style="font-size: 1.3rem; color: #FF9944;">'.$rate.'</span><span class="ml-2"> ( '.$rcnt.' ) </span></div>';

        $myid = $this->session->userdata('st_userid');
        $iscarted = false;
        if ($myid) {
            $isCart = $this->user->select_row('st_cart','*', array('user_id' => $myid, 'service_id' => $gal->category_id));
            if ($isCart) {
                $iscarted = true;
            }
        }

        $html .= '<div class="" style="margin-top:30px;max-width:450px;width:100%;border: 2px solid #efefef;border-radius: 5px;padding: 15px 20px;">
        <div class="d-flex flex-row mt-3 mb-3" style="justify-content:space-between;">
            <div style="font-size:1rem; flex-grow:1;"> <b>Buche diese Behandlung bei '.$emp->first_name.'</b></div>
            <div class="ml-3" style="font-size:1.1rem;min-width:55px;text-align:right;"> <b>'.$cat->price.' €</b> </div>
        </div>
        <div style="width: 100%;" class="mt-2">
          <a class="'.($iscarted ? 'bg-grey' : 'galbook bgpinkorangegradient').' nav-link header-btn colorwhite font-size-14 fontfamily-regular widthfit" style="text-align:center;color: white !important;cursor:pointer;" data-id="'.$cat->id.'" data-mid="'.$cat->created_by.'">Jetzt buchen</a>
        </div><div class="text-center ml-2 mt-1" style="font-size:0.8rem;">'.($iscarted?'Behandlung liegt bereits in Warenkorb':'').'</div>
      </div>';
        

        echo json_encode(array('success' => 1, 'html' => $html));
    }

    //**** user change password ****//
    public function change_password()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));} else {
            $this->data['title'] = 'Passwort ändern';
            $this->load->view('frontend/user/change_password', $this->data);
        }
    }

    public function check_employee_membership()
    {
        if (!empty($this->session->userdata('access')) && $this->session->userdata('access') == 'marchant') {

            $availablity_check = $this->user->select_row('st_availability', 'count(id) availablity', array('user_id' => $this->session->userdata('st_userid'), 'type' => 'open'));

            if (empty($availablity_check->availablity)) { //echo $availablity_check->availablity.'if'; die;
                echo json_encode(['success' => 1, 'msg' => 'Please add salon working hours first.', 'url' => '']);die;
            } else {

                $membership = $this->user->select_row('st_users', 'subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status, profile_status,online_booking, allow_online_booking', array('id' => $this->session->userdata('st_userid')));
                if (($membership->subscription_status == 'trial' && $membership->plan_id == '' && $membership->end_date > date('Y-m-d H:i:s')) || $membership->profile_status != 'complete' || ($membership->online_booking && $membership->allow_online_booking == 'true')) {
                    echo json_encode(['success' => 1, 'msg' => '', 'url' => base_url('merchant/dashboard_addemployee')]);
                } elseif (($membership->end_date > date('Y-m-d H:i:s') && $membership->subscription_status != 'trial') || $membership->profile_status != 'complete') {
                    if ($membership->plan_id == STRIPE_P3 || $membership->profile_status != 'complete') {
                        echo json_encode(['success' => 1, 'msg' => '', 'url' => base_url('merchant/dashboard_addemployee')]);
                    } else {
                        $planDteial = $this->user->select_row('st_membership_plan', 'id,employee', array('stripe_plan_id' => $membership->plan_id));

                        $employeeCount = $this->user->select_row('st_users', 'count(id) as empCount', array('merchant_id' => $this->session->userdata('st_userid'), 'status!=' => 'deleted'));

                        //print_r($planDteial);
                        //print_r($employeeCount); die;
                        if (!empty($planDteial) && !empty($employeeCount) && $planDteial->employee > $employeeCount->empCount) {
                            echo json_encode(['success' => 1, 'msg' => '', 'url' => base_url('merchant/dashboard_addemployee')]);die;

                        } else {
                            echo json_encode(['success' => 1, 'msg' => 'Um weitere Mitarbeiter hinzufügen zu können, musst du deine Mitgliedschaft hochstufen.', 'url' => '']);die;
                        }

                    }
                } else {
                    // if ($membership->subscription_status == 'trial') {
                        echo json_encode(['success' => 1, 'msg' => 'Deine Mitgliedschaft ist abgelaufen. Bitte schließe eine neue Mitgliedschaft ab.', 'url' => '']);
                    // } else {
                    //     echo json_encode(['success' => 1, 'msg' => 'Your membership has expired. Please subscribe for a membership plan.', 'url' => '']);
                    // }
                }
            }
        } else {
            echo json_encode(['success' => 1, 'msg' => '', 'url' => base_url()]);
        }

    }
    //***** view merchant *****//

    public function salon_profile($slug = "", $city="")
    {
        $rowperpage = 5;
        $offset = 0;

        $slug = urldecode($slug);
        $slug = $this->db->escape($slug);
        if ($slug != "") {
            $sql = "SELECT slug, `st_users`.`id`, `first_name`, `last_name`, `business_name`, `business_type`, 
                        `address`, `email`, `mobile`, `address`, `zip`, `latitude`, `longitude`, 
                        `country`, `city`, `about_salon`, `user_id`, `image`, `image1`, `image2`, 
                        `image3`, `image4`, `insta_link`, `web_link`, `fb_link`, 
                        (SELECT ROUND(AVG(rate), 2) FROM st_review WHERE merchant_id=st_users.id AND user_id !=0) AS rating 
                    FROM `st_users` 
                    LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                    WHERE `status` = 'active' AND slug = ${slug}";
            $query = $this->db->query($sql);
            $this->data['userdetail'] = $query->result();
        } else if (!is_null($this->input->get('id'))) {
            $id = url_decode($this->input->get('id'));
            $sql = "SELECT slug, `st_users`.`id`, `first_name`, `last_name`, `business_name`, `business_type`, 
                        `address`, `email`, `mobile`, `address`, `zip`, `latitude`, `longitude`, 
                        `country`, `city`, `about_salon`, `user_id`, `image`, `image1`, `image2`, 
                        `image3`, `image4`, `insta_link`, `web_link`, `fb_link`, 
                        (SELECT ROUND(AVG(rate), 2) FROM st_review WHERE merchant_id=st_users.id AND user_id !=0) AS rating 
                    FROM `st_users` 
                    LEFT JOIN `st_banner_images` ON `st_users`.`id`=`st_banner_images`.`user_id` 
                    WHERE `status` = 'active' AND `st_users`.`id` = ${id}";
            $query = $this->db->query($sql);
            $this->data['userdetail'] = $query->result();
        }

        if (!empty($this->data['userdetail'])) {
            $this->data['service_id'] = url_encode($this->data['userdetail'][0]->id);

            $id = $this->data['userdetail'][0]->id;

            if (!empty($this->session->userdata('st_userid'))) {
                $this->user->delete('st_cart', array('merchant_id !=' => $id, 'user_id' => $this->session->userdata('st_userid')));
            }

            $this->data['sid'] = $id;

            if (!empty($_GET['servicids'])) {
                $sids = url_decode($_GET['servicids']);
                $esid = array_unique(explode(',', $sids));

                $sql = "SELECT st_merchant_category.*,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`st_merchant_category`.`id` AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND online_booking=1 AND status='active')>=1),'0') as checkemp,st_category.category_name,st_category.id as subcatid FROM st_merchant_category LEFT JOIN st_category ON st_category.id=st_merchant_category.subcategory_id WHERE st_merchant_category.status='active' AND st_merchant_category.online=1 AND st_merchant_category.created_by=" . $id . " AND st_merchant_category.subcategory_id IN(" . implode(',', $esid) . ")";

                $matchcatsubcat = $this->user->custome_query($sql, 'result');

                if ($matchcatsubcat) {

                    $services_by_subcategory = [];

                    foreach ($matchcatsubcat as $service) {
                        if ($service->checkemp > 0) {
                            $services_by_subcategory[$service->category_name][] = $service;
                        }
                    }

                    $this->data['matchcatsubcat'] = $services_by_subcategory;
                    //echo "<pre>"; print_r($services_by_subcategory); die;

                }

            }

            $add = "";

            if (!empty($uid)) {
                $add .= ",IFNULL((SELECT count(id) FROM st_cart WHERE subcat_id =`st_merchant_category`.`subcategory_id` AND user_id=" . $uid . "),'0') as total_in_cart";
            }

            $field2 = 'st_merchant_category.id,image,icon,st_category.category_name,st_category.filter_category,st_merchant_category.created_by,st_merchant_category.subcategory_id as subid,st_merchant_category.category_id as mainid,name,count(*) as count,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE subcat_id =`st_merchant_category`.`subcategory_id` AND created_by="' . $id . '" AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND online_booking=1 AND status="active")>=1),0) as checkemp,IFNULL((SELECT category_name FROM st_filter_category WHERE id =`st_category`.`filter_category`),"") as filter_category_name' . $add . '';

            $this->db->having('checkemp >0');
            $this->data['sub_category'] = $this->user->join_two('st_merchant_category', 'st_category', 'subcategory_id', 'id', array('st_merchant_category.created_by' => $id, 'st_merchant_category.status' => 'active', 'st_merchant_category.online' => 1), $field2, 'subcategory_id');

            if (!empty($this->data['sub_category'])) {
                $filcatarr = array();
                $filterCat = array();
                $filtAllSubCat = array();

                foreach ($this->data['sub_category'] as $cat) {
                    $filtAllSubCat[$cat->filter_category][] = $cat->subid;
                    if (!in_array($cat->filter_category, $filterCat)) {
                        $filcatarr[] = $cat;
                        $filterCat[] = $cat->filter_category;
                    }
                }
                $this->data['filter_category'] = $filcatarr;
                $this->data['all_subcats'] = $filtAllSubCat;
            }

            $this->data['share_url'] = base_url('user/service_provider/' . $this->data['service_id']);

            if (!empty($this->data['userdetail'][0])) {
                $this->data['share_desc'] = 'Lese echte Kundenbewertungen und buche deinen Termin bei '.$this->data['userdetail'][0]->business_name.' in '.$this->data['userdetail'][0]->city.' einfach online. 24/7 Termine buchen mit styletimer!';
                $this->data['share_img'] = base_url('assets/uploads/banners/' . $this->data['userdetail'][0]->id . '/' . $this->data['userdetail'][0]->image);
            }

            $this->data['user_available'] = $this->user->select('st_availability', "id,starttime,endtime", array('user_id' => $id), '', 'id', 'ASC');
            $this->db->having('checkemp >0');
            $this->data['main_category'] = $this->user->join_two('st_merchant_category', 'st_category', 'category_id', 'id', array('st_merchant_category.created_by' => $id, 'st_merchant_category.status' => 'active', 'st_merchant_category.online' => 1), $field2, 'category_id');
            //  echo $this->db->last_query();
            if ($this->session->userdata('access') == 'user') {

                $this->data['my_cart'] = $this->user->select_row('st_cart', 'COUNT(*) as service, SUM(total_price) as tot_price,(SELECT price_start_option FROM st_merchant_category WHERE id=service_id AND price_start_option="ab" LIMIT 1) as price_start_option', array('user_id' => $this->session->userdata('st_userid')));

                $this->data['all_cart_item'] = $this->user->select('st_cart', 'service_id,total_price', array('user_id' => $this->session->userdata('st_userid')));
            }
            $this->db->having('checkemp >0');
            $this->data['merchant_category'] = $this->user->select('st_merchant_category', 'id,name,(SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as catname,IFNULL((SELECT count(st_review.id) FROM st_review LEFT JOIN st_booking_detail ON st_review.booking_id = st_booking_detail.booking_id AND st_review.merchant_id='.$id.' WHERE st_booking_detail.service_id =`st_merchant_category`.`id`),0) as checkemp', array('created_by' => $id, 'status' => 'active'), '', 'id', 'ASC');

            //$this->data['rcount']=$this->user->countResult('st_review',array('merchant_id' => $id));
            $this->data['totalcount'] = $data = $this->user->select_row('st_review', 'count(id) as tcount,(SELECT count(rate) FROM st_review where rate=5 AND merchant_id=' . $id . ') as five,(SELECT count(rate) FROM st_review where rate=4 AND merchant_id=' . $id . ') as four,(SELECT count(rate) FROM st_review where rate=3 AND merchant_id=' . $id . ') as three,(SELECT count(rate) FROM st_review where rate=2 AND merchant_id=' . $id . ') as two,(SELECT count(rate) FROM st_review where rate=1 AND merchant_id=' . $id . ') as one', array('merchant_id' => $id, 'user_id !=' => 0));

            if (!empty($this->data['totalcount']->tcount)) {
                $this->data['rcount'] = $this->data['totalcount']->tcount;
            } else {
                $this->data['rcount'] = 0;
            }

            //$this->data['review']=$this->user->select('st_review','rate,review,user_id,created_on,(select first_name from st_users where id=st_review.user_id) as userfn,(select last_name from st_users where id=st_review.user_id) as userln',array('merchant_id' => $id),'','id','desc',5,0);

            $sql = "SELECT st_review.id,st_review.user_id,st_review.booking_id,st_review.merchant_id,rate,review,anonymous,st_review.created_on,first_name,last_name,(SELECT first_name from st_users where st_users.id=st_review.emp_id) as fname,(SELECT last_name from st_users where st_users.id=st_review.emp_id) as lname FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=" . $id . " AND st_review.user_id!='0' GROUP BY st_review.id ORDER BY st_review.created_on DESC LIMIT " . $rowperpage . " OFFSET " . $offset . "";

            $this->data['review'] = $this->user->custome_query($sql, 'result');

            $idss = array();
            if (isset($_COOKIE['testing']) && $_COOKIE['testing'] != '') {
                $arry2 = explode(',', $_COOKIE['testing']);
                if (count($arry2) < 3) {

                    $arry2[] = $id;
                } else {

                    if (in_array($id, $arry2)) {

                    } else {

                        array_shift($arry2);
                        $arry2[] = $id;

                    }
                }
                $arry2 = array_unique($arry2);
                $idss = implode(',', $arry2);
                setcookie('testing', $idss, time() + (86400 * 30), "/");

            } else {
                $ary = $id;
                setcookie('testing', $ary, time() + (86400 * 30), "/");
            }

            $title = 'Termin buchen bei ' . $this->data['userdetail'][0]->business_name . ' | ' . $this->data['userdetail'][0]->business_type . ' in ' . $this->data['userdetail'][0]->city . ' -  styletimer';

            $this->data['title'] = $title;

            $this->data['payment_method'] = $this->user->select('st_merchant_payment_method', 'id,method_name,defualt', array('status' => 'active', 'user_id' => $this->data['userdetail'][0]->id));
            //echo '<pre>'; print_r($this->data); die;

            $this->data['gbanerdata'] = $this->user->select('st_gallery_banner_images', '*', array('merchant_id' => $id));
            $this->load->view('frontend/user/service_provider_profile', $this->data);
        } else {
            redirect(base_url());
        }
    }

    public function service_provider($id = "")
    {
        if ($id != "") {
            $salonDetails = $this->user->select_row('st_users', 'slug, city', array('id' => url_decode($id)));

            if (!empty($salonDetails)) {
                $returnUrl = base_url("salons/");
                if ($salonDetails->slug) {
                    $returnUrl = base_url("salons/" . urlencode($salonDetails->slug) .'/'. rawurlencode(replace_spec_char($salonDetails->city)));
                } else {
                    $_GET['id'] = $id;
                }

                if (!empty($_GET)) {

                    $param = [];

                    foreach ($_GET as $k => $v) {
                        $param[] = $k . '=' . $v;
                    }

                    //print_r($param); die;
                    $getQuery = implode('&', $param);
                    $returnUrl = $returnUrl . "?" . $getQuery;
                }
                redirect($returnUrl);
            } else {
                redirect(base_url());
            }
        }
    }

    //~ public function service_provider_old($id=""){

    //~ $rowperpage = 5;
    //~ $offset     = 0;
    //~ if($id !=""){

    //~ $this->data['service_id'] = $id;

    //~ $id                       = url_decode($id);

    //~ if(!empty($this->session->userdata('st_userid'))){
    //~ $this->user->delete('st_cart',array('merchant_id !='=>$id,'user_id'=>$this->session->userdata('st_userid')));
    //~ }

    //~ $field = "st_users.id,first_name,last_name,business_name,business_type,address,email,mobile,address,zip,latitude,longitude,country,city,about_salon,user_id,image,image1 ,image2,image3,image4,(select ROUND(AVG(rate),2) from st_review where merchant_id=st_users.id AND user_id !=0) as rating";

    //~ $whr               = array('st_users.id'=>$id);
    //~ $this->data['sid'] = $id;

    //~ if(!empty($_GET['servicids']))
    //~ {
    //~ $sids = url_decode($_GET['servicids']);
    //~ $esid = array_unique(explode(',',$sids));

    //~ $sql   = "SELECT st_merchant_category.*,st_category.category_name,st_category.id as subcatid FROM st_merchant_category LEFT JOIN st_category ON st_category.id=st_merchant_category.subcategory_id WHERE st_merchant_category.status='active' AND st_merchant_category.created_by=".$id." AND st_merchant_category.subcategory_id IN(".implode(',',$esid).")";

    //~ $matchcatsubcat  = $this->user->custome_query($sql,'result');

    //~ if($matchcatsubcat){

    //~ $services_by_subcategory = [];

    //~ foreach($matchcatsubcat as $service) $services_by_subcategory[$service->category_name ][] = $service;

    //~ $this->data['matchcatsubcat']=$services_by_subcategory;
    //~ //echo "<pre>"; print_r($services_by_subcategory); die;

    //~ }

    //~ }

    //~ $field2         = 'st_merchant_category.id,image,icon,st_category.category_name,st_merchant_category.created_by,st_merchant_category.subcategory_id as subid,st_merchant_category.category_id as mainid,name,count(*) as count';

    //~ $this->data['sub_category']  = $this->user->join_two('st_merchant_category','st_category','subcategory_id','id',array('st_merchant_category.created_by' =>$id,'st_merchant_category.status' =>'active'),$field2,'subcategory_id');

    //~ $this->data['userdetail']= $this->user->join_two('st_users','st_banner_images','id','user_id',$whr,$field);

    //~ $this->data['share_url']     = base_url('user/service_provider/'.$this->data['service_id']);

    //~ if(!empty($this->data['userdetail'][0])){
    //~ $this->data['share_desc'] = $this->data['userdetail'][0]->about_salon;
    //~ $this->data['share_img']  = base_url('assets/uploads/banners/'.$this->data['userdetail'][0]->id.'/'.$this->data['userdetail'][0]->image);
    //~ }

    //~ $this->data['user_available'] = $this->user->select('st_availability',"id,starttime,endtime",array('user_id'=>$id),'','id','ASC');

    //~ $this->data['main_category']  = $this->user->join_two('st_merchant_category','st_category','category_id','id',array('st_merchant_category.created_by' =>$id,'st_merchant_category.status' =>'active'),$field2,'category_id');

    //~ if($this->session->userdata('access')=='user'){

    //~ $this->data['my_cart']      = $this->user->select_row('st_cart','COUNT(*) as service, SUM(total_price) as tot_price',array('user_id'=>$this->session->userdata('st_userid')));

    //~ $this->data['all_cart_item'] = $this->user->select('st_cart','service_id,total_price',array('user_id'=>$this->session->userdata('st_userid')));
    //~ }
    //~ //$this->data['rcount']=$this->user->countResult('st_review',array('merchant_id' => $id));
    //~ $this->data['totalcount'] = $data = $this->user->select_row('st_review','count(id) as tcount,(SELECT count(rate) FROM st_review where rate=5 AND merchant_id='.$id.') as five,(SELECT count(rate) FROM st_review where rate=4 AND merchant_id='.$id.') as four,(SELECT count(rate) FROM st_review where rate=3 AND merchant_id='.$id.') as three,(SELECT count(rate) FROM st_review where rate=2 AND merchant_id='.$id.') as two,(SELECT count(rate) FROM st_review where rate=1 AND merchant_id='.$id.') as one',array('merchant_id' => $id, 'user_id !=' => 0));

    //~ if(!empty($this->data['totalcount']->tcount))
    //~ $this->data['rcount'] = $this->data['totalcount']->tcount;
    //~ else
    //~ $this->data['rcount'] = 0;

    //~ //$this->data['review']=$this->user->select('st_review','rate,review,user_id,created_on,(select first_name from st_users where id=st_review.user_id) as userfn,(select last_name from st_users where id=st_review.user_id) as userln',array('merchant_id' => $id),'','id','desc',5,0);

    //~ $sql   = "SELECT st_review.id,st_review.user_id,st_review.booking_id,st_review.merchant_id,rate,review,anonymous,st_review.created_on,first_name,last_name,(SELECT first_name from st_users where st_users.id=st_review.emp_id) as fname,(SELECT last_name from st_users where st_users.id=st_review.emp_id) as lname FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=".$id." AND st_review.user_id!='0' GROUP BY st_review.id DESC LIMIT ".$rowperpage." OFFSET ".$offset."";

    //~ $this->data['review'] = $this->user->custome_query($sql,'result');

    //~ $idss  =  array();
    //~ if(isset($_COOKIE['testing']) && $_COOKIE['testing'] !='')
    //~ {
    //~ $arry2 = explode(',', $_COOKIE['testing']);
    //~ if(count($arry2) < 3)
    //~ {

    //~ $arry2[] = $id;
    //~ }
    //~ else{

    //~ if(in_array($id,$arry2)){

    //~ }else{

    //~ array_shift($arry2);
    //~ $arry2[]=$id;

    //~ }
    //~ }
    //~ $arry2 = array_unique($arry2);
    //~ $idss  = implode(',', $arry2);
    //~ setcookie('testing',$idss , time() + (86400 * 30), "/");

    //~ }
    //~ else{
    //~ $ary = $id;
    //~ setcookie('testing',$ary , time() + (86400 * 30), "/");
    //~ }

    //~ $this->data['merchant_category'] = $this->user->select('st_merchant_category','id,name,(SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as catname',array('created_by'=>$id, 'status' => 'active'),'','id','ASC');

    //~ $mainCatName = [];
    //~ $title       = "";

    //~ if(!empty($this->data['main_category']))
    //~ {
    //~ foreach($this->data['main_category'] as $row) $mainCatName[]=$row->category_name;

    //~ $title=implode(' & ',$mainCatName);
    //~ }

    //~ $title=$title.' '.$this->data['userdetail'][0]->business_name.' in '.$this->data['userdetail'][0]->city.' -  Book appointment online';

    //~ $this->data['title'] = $title;
    //~ //echo '<pre>'; print_r($this->data); die;

    //~ $this->load->view('frontend/user/service_provider_profile',$this->data);
    //~ }
    //~ }

    //**** get service ****//
    public function get_service()
    {$this->db->having('checkemp > 0');
        $this->db->where_in('subcategory_id', explode(',', $_POST['sub_catid']));
        $allservices = $this->user->join_two('st_merchant_category', 'st_category', 'subcategory_id', 'id', array('st_merchant_category.created_by' => $_POST['id'], 'st_merchant_category.status' => 'active', 'st_merchant_category.online' => 1), 'st_merchant_category.id,st_merchant_category.category_id,st_merchant_category.filtercat_id,st_merchant_category.service_detail,name,duration,category_name,price,discount_price,price_start_option,IFNULL((SELECT count(id) FROM st_service_employee_relation WHERE service_id =`st_merchant_category`.`id` AND (select COUNT(id) FROM st_users WHERE st_users.id=st_service_employee_relation.user_id AND online_booking=1 AND status="active")>=1),0) as checkemp');
        //echo $this->db->last_query(); die;

        $all_cart_item = $this->user->select('st_cart', 'service_id,total_price', array('user_id' => $this->session->userdata('st_userid')));

        $cartValue = array();
        if (!empty($all_cart_item)) {
            foreach ($all_cart_item as $all) {
                $cartValue[] = $all->service_id;
            }
        }

        $html = '';

        if (!empty($allservices)) {

            $services_by_subcategory = array();

            foreach ($allservices as $service) {
                $services_by_subcategory[$service->category_name][] = $service;
            }

            $i = 0;
            $sub_ids = 0;
            foreach ($services_by_subcategory as $k => $v) {

                //$chk= (in_array($sub->id,$cartValue))?'selectedBtn':'btn-border-orange';

                $multiPlecat = "";
                $startFrom = "";
                $allid = array();

                $duration = array();
                $abd = "";
                $ij = 1;
                $s2 = 0; $ss2 = 0;
                foreach ($v as $ser) {
                    if ($ij == 1) {
                    $s2 = $ser->price; $ss2 = 0;
                    }
                                if ($s2 != $ser->price) $ss2 = 1;
                    else if ($s2 == $ser->price && $ser->price_start_option == 'ab')  $ss2 = 1;
                    if ($s2 > $ser->price) $s2 = $ser->price;
                    
                    if (in_array($ser->id, $cartValue)) {
                        $slectClass = 'selectedBtn';
                    } else {
                        $slectClass = 'btn-border-orange';
                    }

                    if (empty($ser->name)) {

                        if (!empty($ser->discount_price)) {
                            $price = "<small class='font-size-18 fontfamily-semibold'>" . price_formate($ser->discount_price) . " €</small>";
                        } else {
                            $price = "<small class='font-size-18 fontfamily-semibold'>" . ($ser->price_start_option == "ab" ? $ser->price_start_option : '') . " " . price_formate($ser->price) . " €</small>";
                        }

                        $discntPrice = "";

                        if (!empty($ser->discount_price)) {
                            $discount = get_discount_percent($ser->price, $ser->discount_price);
                            if (!empty($discount)) {
                                $discntPrice = '<span class="colorcyan fontfamily-regular font-size-14">' . $this->lang->line('save-up-to') . ' ' . $discount . ' %</span>';
                            }
                        }
                        $show = '';

                        $cherv = check_review($ser->id);

                        if (!empty($ser->service_detail) || !empty($cherv)) {
                            $show = '<a data-id="' . $ser->id . '" data-allid="' . $ser->id . '" href="javascript:void(0)" class="colorcyan a_hover_cyan first_pop salondetail_popup">' . $this->lang->line('Show-Details') . '</a>';
                        }

                        $html = $html . $show . '<div class="border-b d-flex py-3 pl-3  bookingSelect" data-id="' . $_POST['id'] . '" id="' . $ser->id . '" value="' . $price . '">
						  <div class="deatail-box-left">
							<p class="color333 font-size-16 fontfamily-medium mb-0">' . $k . '</p>
							<span class="font-size-14 color666 fontfamily-regular">' . $ser->duration . ' Min.</span>
						  </div>
						  <div class="deatail-box-right d-inline-flex">
							<div class="relative text-right width200">
							  <p class="fontfamily-medium color333 font-size-14 mb-0">' . $price . ' </p>' . $discntPrice . '
							</div>
							<button class="btn ' . $slectClass . ' btn-small widthfit2 btn-ml-20 class-' . $ser->id . '" data-cid="' . $ser->category_id . '" data-fid="' . $ser->filtercat_id . '">'.$this->lang->line('Select').'</button>
						  </div>
						</div>';
                    } else {
                        // if (!empty($ser->discount_price)) {
                        //     $price = $ser->discount_price;
                        // } else {
                            $price = $ser->price;
                        // }

                        $discntPrice = "";

                        if ($ser->price_start_option == 'ab' || $ij == 2) {
                            $abd = "ab ";
                        }

                        $ij++;

                        if (!empty($ser->discount_price)) {
                            $discount = get_discount_percent($ser->price, $ser->discount_price);
                            if (!empty($discount)) {
                                $discntPrice = '<span class="colorcyan fontfamily-regular font-size-14">' . $this->lang->line('save-up-to') . ' ' . $discount . ' %</span>';
                            }
                        }
                        if (empty($startFrom)) {
                            $startFrom = $price;
                        }

                        if ($startFrom > $price) {
                            $startFrom = $price;
                        }

                        $duration[] = $ser->duration;

                        $sub_ids = $ser->id;
                        $allid[] = $ser->id;
                        $chk_detail = $ser->service_detail;
                        $multiPlecat = $multiPlecat . '<div class="d-flex py-2 pl-3 bookingSelect" data-id="' . $_POST['id'] . '" id="' . $ser->id . '" value="' . $ser->price . '">
											  <div class="deatail-box-left">
												<p class="color666 font-size-16 fontfamily-medium mb-0">' . $ser->name . '</p>
												<span class="font-size-14 color999 fontfamily-regular">' . $ser->duration . ' Min.</span>
											  </div>
											  <div class="deatail-box-right d-inline-flex pl-20">
												<div class="relative text-right width160">
												  <p class="fontfamily-medium color333 font-size-14 mb-0">' . ($ser->price_start_option == "ab" ? $ser->price_start_option : '') . ' ' . price_formate($price) . ' €</p>' . $discntPrice . '
												</div>
												<button class=" btn ' . $slectClass . ' widthfit2 btn-ml-20 class-' . $ser->id . '" data-cid="' . $ser->category_id . '" data-fid="' . $ser->filtercat_id . '">'.$this->lang->line('Select').'</button>
											  </div>
											</div>';
                    }}
                if (!empty($multiPlecat)) {
                    $min = min($duration);
                    $max = max($duration);
                    if ($min == $max) {$dur = $min . " Min.";
                        $cls = "first_pop";} else { $dur = $min . " Min. - " . $max . " Min.";
                        $cls = "second_pop";}
                    //$v[0]->id
                    $show = '';
                    $cherv = check_review(implode(",", $allid));
                    if (!empty($chk_detail) || !empty($cherv)) {
                        $show = '<a data-id="' . $sub_ids . '" data-allid="' . implode(',', $allid) . '" style="" href="javascript:void(0)" class="colorcyan a_hover_cyan ' . $cls . ' salondetail_popup">' . $this->lang->line('Show-Details') . '</a>';
                    }
                    $html = $html . $show . '<div class="border-b px-3">
								  <div class="accordion" id="right-side-box-accordian4">
									<div class="accordion-group">
									  <div class="accordion-heading p-3 relative">
										<a class="accordion-toggle color333 fontfamily-medium font-size-16 a_hover_333 d-flex collapsed" data-toggle="collapse" data-parent="#right-side-box-accordian4" href="#collapseOne42ajx' . $i . '"><p class="relative vertical-top deatail-box-left">
										  ' . $k . '<span class="font-size-14 color999 fontfamily-regular display-b">' . $dur . '</span></p>
										  <span class="fontfamily-medium color333 font-size-14 mb-0" style="float:right;margin-right: 27px;">' . ($ss2 ? 'ab ' : '') . '<small class="font-size-18 fontfamily-semibold">' . $startFrom . ' €</small></span>
										</a>
									  </div>
									  <div id="collapseOne42ajx' . $i . '" class="accordion-body collapse">
										<div class="accordion-inner">
										 ' . $multiPlecat . '
										</div>
									  </div>
									</div>
								  </div>
								</div>';

                }$i++;}} else {
            $html = '<div class="border-b d-flex p-3">No service found..</div>';
        }

        echo $html;
    }

    //***** get all booking user ****//
    public function all_bookings()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        $where = array('st_booking.user_id' => $this->session->userdata('st_userid'));
        $whr1 = '';
        $orderby = 'st_booking.booking_time desc';
        if (empty($_GET['status'])) {
            $_GET['status'] = 'upcoming';
        }
        if (!empty($_GET['status'])) {
            if ($_GET['status'] == 'upcoming') {
                $td = date('Y-m-d');
                $whr1 = array('DATE(st_booking.booking_time) >=' => $td, 'st_booking.status' => 'confirmed');
                $orderby = 'st_booking.booking_time asc';
            } else if ($_GET['status'] == 'recent') {
                /* $whr = array('st_booking.status' => 'completed');
                $where=$where+$whr;*/
                $td = date('Y-m-d H:i:s');
                $whr1 = '(st_booking.status="completed" OR st_booking.status="cancelled" OR (st_booking.status="confirmed" AND st_booking.booking_time<= "' . $td . '"))';
            } else if ($_GET['status'] == 'cancelled') {
                $whr1 = '(st_booking.status="cancelled" OR st_booking.status="no_show")';
            }

        }

        if (!empty($whr1)) {
            $this->db->where($whr1);
        }

        $totalcount = $this->user->getbookinglistmodifiy($where);
        if (!empty($totalcount)) {
            $total = count($totalcount);
        } else {
            $total = 0;
        }

        if (!empty($_GET['limit']) && $_GET['limit'] == 'all') {

            $limit = $total;
        } else {
            $limit = isset($_GET['limit']) ? $_GET['limit'] : PER_PAGE10; //PER_PAGE10
        }
        $url = 'user/all_bookings';

        $segment = 3;
        $page = mypaging($url, $total, $segment, $limit);

        if (!empty($whr1)) {
            $this->db->where($whr1);
        }

        $this->data['booking'] = $this->user->getbookinglistmodifiy($where, $page["per_page"], $page["offset"], 'employee_id', $orderby);
        //echo "<pre>"; print_r($this->data); die;
        $this->data['title'] = 'Meine Buchungen';
        $this->load->view('frontend/user/my_booking', $this->data);
    }

    //**** load more review ****//
    public function loadmorereview()
    {
        /*print_r($_POST);
        die;*/
        $mid = url_decode($_POST['mid']);
        $limit = $_POST['load_id'] + 1;
        $allCount = $limit * 5;
        $rowperpage = 5;
        $offset = 0;
        if ($limit != 1) {
            $offset = ($limit - 1) * $rowperpage;
        }
        $review = $this->user->select('st_review', 'rate,review,user_id,created_on,(select first_name from st_users where id=st_review.user_id) as userfn,(select last_name from st_users where id=st_review.user_id) as userln', array('merchant_id' => $mid), '', 'id', 'desc', $rowperpage, $offset);
        $html = '';
        $count = $this->user->countResult('st_review', array('merchant_id' => $mid));
        if (!empty($review)) {
            foreach ($review as $rev) {
                $html .= '<div class="relative border-b pt-20">
                <div class="clear mb-3">';
                for ($i = 0; $i < 5; $i++) {
                    if ($i < $rev->rate) {
                        $html .= "<i class='fas fa-star colororange mr-2'></i>";
                    } else {
                        $html .= "<i class='fas fa-star color999 mr-2'></i>";
                    }

                }
                $timestamp = strtotime($rev->created_on);
                $ago = time_passed($timestamp);
                $html .= '<span class="color999 font-size-14 fontfamily-regular float-right">' . $ago . '</span>
                </div>
                <p class="color666 fontfamily-regular font-size-14 text-lines-overflow-1" style="-webkit-line-clamp:2;">' . $rev->review . '</p>
                <p class="font-size-16 fontfamily-medium color333 mb-10">' . $rev->userfn . ' ' . $rev->userln . '</p>
              </div>';
            }
            if ($count > $allCount) {
                $html .= '<div id="load-' . $limit . '"><a href="javascript:void(0)" id="' . $_POST['mid'] . '" data-id="' . $limit . '"  class="colorcyan a_hover_cyan font-size-18 fontfamily-medium text-underline mt-30 display-b loadMore">weitere Bewertungen laden</a></div>';
            }
        }

        echo $html;

    }

    //***** category search ******//

    public function category_search()
    {
        $html = "";
        if (isset($_POST['keyword'])) {
            extract($_POST);
            $keyword = trim($keyword);

            $category = get_filter_with_parent_cat_menu($keyword);

            if (isset($category) && !empty($category)) {
                foreach ($category as $subcat) {
                    $html .= '<li class="key_word"  style="background:#00b3bf9e; text-align:center;"><label style="color:black;" for="category_' . $subcat->id . '_parent">' . $subcat->category_name . '</label></li>';
                    foreach ($subcat->sub_category as $cat) {
                        $html .= '<li class="key_word"><input type="radio" id="category_' . $cat['my_cat_id'] . '" class="category_li" name="filtercat" data-id="' . $cat['category_name'] . '" value="' . url_encode($cat['my_cat_id']) . '"><label for="category_' . $cat['my_cat_id'] . '">' . $cat['category_name'] . '</label></li>';
                    }

                }} else { $html .= '<p class="key_word">Keine passenden Ergebnisse gefunden</p>';}
        }

        echo $html;
    }

    //**** review filter ****//
    public function reviewfilter()
    {
        //$uid = $this->session->userdata('st_userid');
        //print_r($_POST['category']);die;
        $where = '';
        if (!empty($_POST['rating_point'])) {
            $subcat = implode(',', $_POST['rating_point']);
            $where = $where . " AND rate IN(" . $subcat . ")";
        }
        if (!empty($_POST['category']) && !empty($_POST['category'][0])) {

            $allsubcats = "";
            $i = 0;

            foreach ($_POST['category'] as $k => $v) {
                if ($i == 0) {
                    $allsubcats = $v;
                } else {
                    $allsubcats = $allsubcats . ',' . $v;
                }

                $i++;
            }
            // echo $allsubcats; die;

            $subcat = $allsubcats;

            $whr = "service_id IN(" . $subcat . ")";

            $msql = "SELECT booking_id FROM st_booking_detail WHERE " . $whr . "";
            $book_id = $this->user->custome_query($msql, 'result');
            if (!empty($book_id)) {
                //$book_id=implode(',',$book_id);
                $i = 0;
                foreach ($book_id as $ids) {
                    $book[$i] = $ids->booking_id;
                    $i++;
                }

                $book_id = implode(',', $book);
                $where = $where . "AND booking_id IN(" . $book_id . ")";
            } else {
                $where = $where . "AND booking_id IN(0)";
            }
        }

        $rowperpage = 5;
        $offset = 0;

        $csql = "SELECT count(st_review.id) as tcount FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=" . $_POST['merchant_id'] . " " . $where . " AND st_review.user_id!='0'";
        $totalcount = $this->user->custome_query($csql, 'row');

        if (!empty($totalcount->tcount)) {
            $count = $totalcount->tcount;
        } else {
            $count = 0;
        }

        $html = '';
        $sql = "SELECT st_review.id,st_review.user_id,st_review.booking_id,st_review.merchant_id,rate,review,anonymous,st_review.created_on,first_name,last_name,(SELECT business_name from st_users where st_users.id=st_review.merchant_id) as salon_name,(SELECT first_name from st_users where st_users.id=st_review.emp_id) as fname,(SELECT last_name from st_users where st_users.id=st_review.emp_id) as lname FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=" . $_POST['merchant_id'] . " " . $where . " AND st_review.user_id!='0' GROUP BY st_review.id DESC LIMIT " . $rowperpage . " OFFSET " . $offset . "";
        $allreviews = $this->user->custome_query($sql, 'result');
        //echo $this->db->last_query();
        //die;
        if (!empty($allreviews)) {
            foreach ($allreviews as $rev) {
                $html .= '<div class="relative">
                    <div class="relative border-b pb-3 pt-3">
                      <div class="clear mb-3">';

                for ($i = 0; $i < 5; $i++) {
                    if ($i < $rev->rate) {
                        $html .= '<i class="fas fa-star colororange mr-1"></i>';
                    } else {
                        $html .= '<i class="far fa-star colororange mr-1"></i>';
                    }

                }
                $timestamp = strtotime($rev->created_on);

                if ($rev->anonymous == 1) {
                    $namr = '<span class="font-size-14 fontfamily-medium color333 mr-3">Anonymous</span>';
                } else {
                    $namr = '<a href="#" class="font-size-14 fontfamily-medium color333 mb-10 text-underline a_hover_333 mr-3">' . $rev->first_name . '</a>';
                }
                $html .= '<span class="color999 font-size-14 fontfamily-regular float-right">' . time_passed($timestamp) . '</span>
                      </div>
                      <p class="color666 fontfamily-regular font-size-14 text-lines-overflow-1" style="-webkit-line-clamp:2;">' . $rev->review . '</p>
                      <p>' . $namr . ' <span class="font-size-14 color999 fontfamily-regular"> - Behandelt von ' . $rev->fname . '</span></p>
                      <div class="d-flex">';

                $html .= ' ' . $this->lang->line('Services_Booked') . ' : ' . get_servicename_with_sapce($rev->booking_id);
                $html .= '</div>';
                $m_reply = getselect('st_review', 'id,review', array('review_id' => $rev->id, 'created_by' => $rev->merchant_id));
                $dis = 'none';
                if (!empty($m_reply)) {
                    $dis = '';
                }

                $html .= '<div class="accordion pb-2" id="show_reply_div' . $rev->id . '" style="display: ' . $dis . '">
                        <div class="accordion-group">
                          <div class="accordion-heading p-3 relative">
                            <a class="color333 fontfamily-medium font-size-14 a_hover_333 display-ib relative">
							Kommentar von ' . $rev->salon_name . '
                            </a>
                          </div>
                          <div id="collapsev_' . $rev->id . '" class="accordion-body">
                            <div class="accordion-inner" id="append_row_' . $rev->id . '">';
                if (!empty($m_reply)) {foreach ($m_reply as $rep) {
                    $html .= '<div class="d-flex py-2 px-3">
                                  <p class="fontfamily-regular color666 font-size-14 mb-0">' . $rep->review . '</p></div>';
                }}
                $html .= '</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>';
            }

            if ($count > 5) {
                $html .= '<div id="load-1"><a href="javascript:void(0)" data-id="1"  class="colorcyan a_hover_cyan text-underline font-size-18 fontfamily-medium mt-4 mb-3 display-ib loadMoreReview">weitere Bewertungen laden</a></div>';
            }
            echo json_encode(array('success' => 1, 'html' => $html, 'count' => $count));
        } else {
            $html = '<div class="text-center" style="padding-bottom: 45px;">
					  <img src="' . base_url('assets/frontend/images/no_listing.png') . '"><p style="margin-top: 20px;"> Bisher wurden keine Bewertungen abgegeben.</p></div>';
            echo json_encode(array('success' => 0, 'html' => $html));
        }

    }
    //**** favourite salon *****//
    public function favourite_salon()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        $where = array('user_id' => $this->session->userdata('st_userid'));
        $id = $this->session->userdata('st_userid');

        $totalcount = $this->user->countResult('st_favourite', $where);
        if (!empty($totalcount)) {
            $total = $totalcount;
        } else {
            $total = 0;
        }

        $limit = isset($_GET['limit']) ? $_GET['limit'] : PER_PAGE10; //PER_PAGE10
        $url = 'user/favourite_salon';
        $segment = 3;
        $page = mypaging($url, $total, $segment, $limit);
        // exprice trial period
        $sql123213 = "SELECT extra_trial_month from st_users where id = " . $id;
        $trial12 = $this->db->query($sql123213);
        $trial = $trial12->row();

        $newMonth = $trial->extra_trial_month;
        $finalDATA = date('Y-m-d', strtotime('+' . $newMonth . ' months'));
        $triEndData = $finalDATA;
        $cuD = date("Y-m-d");
        $currentData = "'$cuD'" . ' ' . "'00:00:00'";
        $where123 = array('user_id' => $this->session->userdata('st_userid'));
        $field = 'st_users.id,st_users.allow_online_booking, st_users.online_booking,user_id,salon_id,business_name,DATE_ADD(`st_users`.`created_on`, INTERVAL + extra_trial_month MONTH) AS endTrial,
		 (SELECT image FROM st_banner_images WHERE user_id=st_users.id) as
		 image,profile_pic,st_favourite.created_on';

        $this->data['favourite'] = $this->user->join_two_orderbyMy('st_favourite', 'st_users', 'salon_id', 'id', $where, $currentData, $field, 'id', '', $page["per_page"], $page["offset"]);
        //print_r($this->data['favourite']);
        if (!empty($this->data['favourite'])) {
            $i = 0;
            foreach ($this->data['favourite'] as $usr) {

                $sql2 = "SELECT `r`.`id`,`duration`, `price`, `buffer_time`, `discount_price`,`subcategory_id`, `u1`.`category_name` as `m_category`, `u2`.`category_name` as `s_category` FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` WHERE `r`.`status` = 'active' AND `r`.`created_by`=" . $usr->id . " GROUP BY `r`.`subcategory_id` LIMIT 4";
                $query2 = $this->db->query($sql2);
                $this->data['favourite'][$i]->sercvices = $query2->result();
                //echo $this->db->last_query();
                $i++;
            }
        }
        $this->data['title'] = 'Meine Favoriten';
        $this->load->view('frontend/user/my_favourite', $this->data);
    }

    //**** unfavourite salon ****//
    public function unfavourite_salon()
    {
        $uid = $this->session->userdata('st_userid');
        if ($this->user->delete('st_favourite', array('salon_id' => url_decode($_POST['fav_id']), 'user_id' => $uid))) {
            echo 1;
        } else {
            echo 0;
        }

    }

    //**** change news letter ****//
    public function change_news_letter()
    {
        $upd_data = array('newsletter' => $_POST['status']);
        $res = $this->user->update('st_users', $upd_data, array('id' => url_decode($_POST['tid'])));
        echo $res;
    }
    //**** change news letter ****//
    public function notification_status()
    {
        $upd_data = array('service_email' => $_POST['status']);
        $res = $this->user->update('st_users', $upd_data, array('id' => url_decode($_POST['tid'])));
        echo $res;
    }

    //**** load review filter ****//
    public function loadreviewfilter()
    {
        //$uid = $this->session->userdata('st_userid');

        $where = '';
        if (!empty($_POST['rating_point'])) {
            $subcat = implode(',', $_POST['rating_point']);
            $where = $where . " AND rate IN(" . $subcat . ")";
        }
        if (!empty($_POST['category'][0])) {
            $subcat = implode(',', $_POST['category']);
            $whr = "service_id IN(" . $subcat . ")";
            $msql = "SELECT booking_id FROM st_booking_detail WHERE " . $whr . "";
            $book_id = $this->user->custome_query($msql, 'result');
            if (!empty($book_id)) {
                //$book_id=implode(',',$book_id);
                $i = 0;
                foreach ($book_id as $ids) {
                    $book[$i] = $ids->booking_id;
                    $i++;
                }

                $book_id = implode(',', $book);
                $where = $where . "AND booking_id IN(" . $book_id . ")";
            }
        }

        $limit = $_POST['load_more'] + 1;
        $allCount = $limit * 5;
        $rowperpage = 5;
        $offset = 0;
        if ($limit != 1) {
            $offset = ($limit - 1) * $rowperpage;
        }

        $csql = "SELECT count(st_review.id) as tcount FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=" . $_POST['merchant_id'] . " " . $where . " AND st_review.user_id!='0'";
        $totalcount = $this->user->custome_query($csql, 'row');

        if (!empty($totalcount->tcount)) {
            $count = $totalcount->tcount;
        } else {
            $count = 0;
        }

        $html = '';
        $sql = "SELECT st_review.id,st_review.user_id,st_review.booking_id,st_review.merchant_id,rate,review,anonymous,st_review.created_on,first_name,last_name,(SELECT business_name from st_users where st_users.id=st_review.merchant_id) as salon_name,(SELECT first_name from st_users where st_users.id=st_review.emp_id) as fname,(SELECT last_name from st_users where st_users.id=st_review.emp_id) as lname FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=" . $_POST['merchant_id'] . " " . $where . " AND st_review.user_id!='0' GROUP BY st_review.id DESC LIMIT " . $rowperpage . " OFFSET " . $offset . "";
        $allreviews = $this->user->custome_query($sql, 'result');
        //echo $this->db->last_query();
        //die;
        if (!empty($allreviews)) {
            foreach ($allreviews as $rev) {
                $html .= '<div class="relative">
                    <div class="relative border-b pb-3 pt-3">
                      <div class="clear mb-3">';

                for ($i = 0; $i < 5; $i++) {
                    if ($i < $rev->rate) {
                        $html .= '<i class="fas fa-star colororange mr-1"></i>';
                    } else {
                        $html .= '<i class="far fa-star colororange mr-1"></i>';
                    }

                }
                $timestamp = strtotime($rev->created_on);

                if ($rev->anonymous == 1) {
                    $namr = '<span class="font-size-14 fontfamily-medium color333">Anonymous</span>';
                } else {
                    $namr = '<span class="font-size-14 fontfamily-medium color333 mb-10 a_hover_333">' . $rev->first_name . '</span>';
                }

                $html .= '<span class="color999 font-size-14 fontfamily-regular float-right">' . time_passed($timestamp) . '</span>
                      </div>
                      <p class="color666 fontfamily-regular font-size-14 text-lines-overflow-1" style="-webkit-line-clamp:2;">' . $rev->review . '</p>
                      <p>' . $namr . ' <span class="font-size-14 color999 fontfamily-regular"> - Behandelt von ' . $rev->fname . '</span></p><div class="d-flex">';
                $html .= ' ' . $this->lang->line('Services_Booked') . ' : ' . get_servicename_with_sapce($rev->booking_id) . '</div>';

                $m_reply = getselect('st_review', 'id,review', array('review_id' => $rev->id, 'created_by' => $rev->merchant_id));
                $dis = 'none';
                if (!empty($m_reply)) {
                    $dis = '';
                }

                $html .= '<div class="accordion pb-2" id="show_reply_div' . $rev->id . '" style="display: ' . $dis . '">
                        <div class="accordion-group">
                          <div class="accordion-heading p-3 relative">
                            <a class="color333 fontfamily-medium font-size-14 a_hover_333 display-ib relative">
							Kommentar von ' . $rev->salon_name . '
                            </a>
                          </div>
                          <div id="collapsev_' . $rev->id . '" class="accordion-body">
                            <div class="accordion-inner" id="append_row_' . $rev->id . '">';
                if (!empty($m_reply)) {foreach ($m_reply as $rep) {
                    $html .= '<div class="d-flex py-2 px-3">
                                  <p class="fontfamily-regular color666 font-size-14 mb-0">' . $rep->review . '</p>
                              </div>';
                }}
                $html .= '</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>';
            }

            if ($count > $allCount) {
                $html .= '<div id="load-' . $limit . '"><a href="javascript:void(0)" data-id="' . $limit . '"  class="colorcyan a_hover_cyan text-underline font-size-18 fontfamily-medium mt-4 mb-3 display-ib loadMoreReview">weitere Bewertungen laden</a></div>';
            }
            echo json_encode(array('success' => 1, 'html' => $html, 'limit' => $limit, 'count' => $allCount));
        } else {
            echo json_encode(array('success' => 0, 'html' => $html));
        }

    }

    //*** static page terms and condition ****//
    public function terms_condition($access = "")
    {
        if ($access == 'user') {
            $this->load->view('frontend/terms_condition_user');
        } else if ($access == 'merchant') {
            $this->load->view('frontend/terms_condition_merchant');
        } else {
            redirect(base_url());
        }

    }

    public function getsalon_servicedetail()
    {
        extract($_POST);
        $result = $this->user->select_row('st_merchant_category', 'id,service_detail,subcategory_id,(SELECT category_name from st_category WHERE st_category.id = subcategory_id) as cat_name', array('id' => $id));

        $html = 'No service detail found..!';

        if (!empty($result)) {
            if (!empty($result->service_detail)) {
                $detail = $result->service_detail;
                $detailHtml = '<p class="font-size-16 color666 fontfamily-medium mt-3 mb-3 text-left">' . $this->lang->line('ABOUT-THIS-SERVICE') . '</p>
                               <p class="font-size-14 color333 fontfamily-medium mt-0 mb-3 text-left">' . $detail . '
                                  </p>';
            } else {
                $detailHtml = "";
            }

            $ser_name = $result->cat_name;

            //$allids = explode(',',$_POST['allid']);

            $serviceReviewQuery = 'SELECT bd.id,bd.booking_id,bd.emp_id,rate,review,anonymous,rv.created_on,rv.id as revid,rv.merchant_id, (SELECT first_name FROM st_users WHERE id=bd.emp_id) as employee, (SELECT business_name from st_users where st_users.id=rv.merchant_id) as salon_name, (SELECT first_name FROM st_users WHERE id=bd.user_id) as username FROM st_booking_detail as bd INNER JOIN st_review as rv ON bd.booking_id=rv.booking_id WHERE bd.service_id IN (' . $allid . ') ORDER BY rv.id desc LIMIT 20';

            $reviews = $this->user->custome_query($serviceReviewQuery);
            //echo $this->db->last_query().'<pre>'; print_r($reviews); die;
            $reviewHtml = "";

            $reviewsum = 0;

            if (!empty($reviews)) {
                $count = count($reviews);
                $revieCountAncrHtml = '<a href="#service_reting" class="fontsize-14 starcolor fontfamily-regular a_hover_orange" id="onscrollreview">' . $this->lang->line('Show') . ' ' . $count . ' ' . $this->lang->line('service-reviews') . '...</a>';

                foreach ($reviews as $row) {

                    $reviewsum = $reviewsum + $row->rate;

                    $starHtml = "";
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $row->rate) {
                            $starHtml .= '<i class="fas fa-star starcolor mr-1 fontsize-18"></i>';
                        } else {
                            $starHtml .= '<i class="fas fa-star color999 mr-1 fontsize-18"></i>';
                        }
                    }

                    if ($row->anonymous == '1') {
                        $username = $this->lang->line('Anonymous');
                    } else {
                        $username = $row->username;
                    }

                    $m_reply = getselect('st_review', 'id,review', array('review_id' => $row->revid, 'created_by' => $row->merchant_id));
                    $dis = 'none';
                    if (!empty($m_reply)) {
                        $dis = '';
                        $rhtml = '<p class="pt-3 color333 fontfamily-medium font-size-14 a_hover_333 display-ib relative">Kommentar von ' . $row->salon_name . '</p>';
                        if (!empty($m_reply)) {foreach ($m_reply as $rep) {
                            $rhtml .= '<p class="fontfamily-regular color666 font-size-14 mb-0">' . $rep->review . '</p>';
                        }}
                    }

                    $reviewHtml .= '<div class="service-review-box border-b pb-3 pt-3">
						  <span class="display-b mb-2">' . $starHtml . '
						  </span>
						  <p class="font-size-14 color333 mt-2">' . nl2br($row->review) . '</p>
						  <p class="font-size-14 color333 mt-2 relative after-line-small-bottom">' . $this->lang->line('Treatment-by') . ' ' . $row->employee . '</p>'.$m_reply[0] .'

						  <div class="d-flex justify-content-between ">
							<div class="relative">
							  <span class="font-size-14 color666">' . $username . ' <i class="far fa-check-circle color666"></i> <span class="font-size-14 color666 display-ib ml-3 after-dot-small-left relative">' . time_passed(strtotime($row->created_on)) . '</span></span>
							</div>
						  </div>';
                    $reviewHtml .= $rhtml;
					$reviewHtml .= '</div>';
                    
                    
                }

            } else {
                $revieCountAncrHtml = "";
                $count = 0;
                $reviewHtml .= '<div class="service-review-box border-b pb-3 pt-3"><p class="font-size-14 color333 mt-2">Bisher wurden keine Bewertungen abgegeben.</p></div>';
            }

            if (!empty($reviewsum)) {
                $avrage = round(($reviewsum / $count), 1);
                $avragehtml = "";
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $avrage) {
                        $avragehtml .= '<i class="fas fa-star starcolor mr-1 fontsize-22"></i>';
                    } elseif (($i - 1) < $avrage && $i > $avrage) {
                        $avragehtml .= '<i class="fas fa-star color999 mr-2 fontsize-22"></i>
                                  <i class="fas fa-star-half colororange mr-2 fontsize-22" ></i>';
                    } else {
                        $avragehtml .= '<i class="fas fa-star color999 mr-1 fontsize-22"></i>';
                    }
                }

            } else {
                $avrage = 0.0;
                $avragehtml = '<i class="fas fa-star color999 mr-2 fontsize-22"></i>
                <i class="fas fa-star color999 mr-2 fontsize-22"></i>
                <i class="fas fa-star color999 mr-2 fontsize-22"></i>
                <i class="fas fa-star color999 mr-2 fontsize-22"></i>
                <i class="fas fa-star color999 mr-2 fontsize-22"></i>';
            }

            $html = '<h3 class="font-size-20 color333 fontfamily-medium mt-10 text-left">' . $ser_name . '</h3>
        <div class="relative ">
          <p class="color666 font-size-14">' . $this->lang->line('Service-Rating') . '</p>
          <div class="d-flex ">
            <div class="lineheight40 mr-2">
              <span class="starcolor fontfamily-regular fontsize-48">' . $avrage . '</span>
            </div>
            <div class="">
              <span>
                ' . $avragehtml . '
              </span>
              <p class="m-0 color666 font-size-14">' . $count . ' ' . $this->lang->line('Reviews') . '</p>
            </div>
          </div>
          ' . $revieCountAncrHtml . $detailHtml . '

          <div id="service_reting">
            <p class="font-size-16 color666 fontfamily-medium mt-3 mb-1 text-left ">' . $this->lang->line('service-reviews') . '</p>
           ' . $reviewHtml . '
          </div>';
        }

        //echo $html;
        echo json_encode(array('success' => 1, 'html' => $html));

    }

// reshedule form for popup with slider
    public function reshedule_form()
    {

        //echo '<pre>'; print_r($_POST); die;

        $eid = url_decode($_POST['emp_id']);
        $activeday = $this->user->select('st_availability', 'days', array('user_id' => $eid, 'type' => 'close'));

        $days = array();
        if (!empty($activeday)) {
            foreach ($activeday as $row) {
                $days[] = $row->days;
            }
        }

        $this->data['days'] = $days;
        $this->data['emp_id'] = $_POST['emp_id'];
        $this->data['book_id'] = $_POST['book_id'];
        $this->data['merchant_id'] = $_POST['merchant_id'];

        $bookingdetai = $this->user->select_row('st_booking', 'id,booking_time', array('id' => url_decode($_POST['book_id'])));
        // echo '<pre>'; print_r($bookingdetai); die;
        if (!empty($bookingdetai)) {
            $this->data['postdate'] = date('Y-m-d', strtotime($bookingdetai->booking_time));
        }

        $html = $this->load->view('frontend/common/resheduleform_user', $this->data, true);

        echo json_encode(['success' => 1, 'html' => $html]);die;

    }

//**** get opening hour of salon for reshedule user****//
    public function get_opning_hour()
    {

        if (!empty($_POST['date']) && !empty($this->session->userdata('st_userid'))) {

            $dayName = date("l", strtotime($_POST['date']));
            $dateslect = date("Y-m-d", strtotime($_POST['date']));
            $dayName = strtolower($dayName);
            $id = url_decode($_POST['eid']);
            $bookid = url_decode($_POST['bk_id']);

            $info = $this->user->select_row('st_booking', 'id,total_minutes,merchant_id,total_buffer,booking_time', array('id' => $bookid, 'status' => 'confirmed'));

            $emptyhtml = '<h6 class="mt-3">Leider sind am ' . date('d', strtotime($dateslect)) . '. ' . get_month_de_translation(date('F',strtotime($dateslect))) . ' keine Termine verfügbar.</h6>
							<h6>Bitte wähle einen anderen Tag</h6>';
            if (!empty($info)) {
                $mrntid = $info->merchant_id;

                $availablity1 = $this->user->select_row('st_availability', 'id,type', array('user_id' => $mrntid, 'days' => $dayName, 'type' => 'open'));
                //~ $availablity = $this->user->select_row('st_availability','days,type,starttime,endtime,starttime_two,endtime_two',array('user_id'=>$mrntid,'days'=>$dayName));
                if (!empty($availablity1)) {

                    $availablity = $this->user->select_row('st_availability', 'days,type,starttime,endtime,starttime_two,endtime_two', array('user_id' => $id, 'days' => $dayName, 'type' => 'open'));
                    $html = "";

                    if (!empty($availablity)) {

                        $totaldurationTim = $info->total_minutes + $info->total_buffer;

                        $detailSelectQry = "";

                        $bookdteialQuery = "SELECT bd.id,service_type,bd.duration,bd.buffer_time,bd.setuptime,bd.processtime,bd.finishtime,oa.starttime,oa.endtime,mc.parent_service_id,mc.price,mc.discount_price FROM st_booking_detail as bd JOIN st_merchant_category as mc ON mc.id=bd.service_id  LEFT JOIN st_offer_availability as oa ON oa.service_id=bd.service_id AND oa.days='" . $dayName . "' AND oa.type='open' WHERE booking_id=" . $bookid;

                        $serviceDetails = $this->user->custome_query($bookdteialQuery, 'result');

                        // echo '<pre>'; print_r($serviceDetails); die;

                        //$serviceDetails   = $this->user->select('st_booking_detail','id,service_type,duration,buffer_time,setuptime,processtime,finishtime',array('booking_id'=>$bookid));

                        //$html=$html.'<option value="">Select time</option>';
                        $start = $availablity->starttime; //you can write here 00:00:00 but not need to it
                        $end = $availablity->endtime;

                        $tStart = strtotime($start);
                        $tEnd = strtotime(date('H:i:s', strtotime($end . "- " . $totaldurationTim . " minutes")));

                        $currtime = date('H:i:s');
                        $crntdate = date('Y-m-d');
                        $tNow = $tStart;
                        $k = 1;

                        $checkedTime = "";
                        if (date('Y-m-d', strtotime($info->booking_time)) == $_POST['date']) {
                            $checkedTime = date('H:i:s', strtotime($info->booking_time));
                        }
                        while ($tNow <= $tEnd) {

                            $nowTime = date("H:i:s", $tNow);
                            //~ echo $nowTime."==".$currtime."==".$date."==".$crntdate."</br>";
                            if ($dateslect == $crntdate && $currtime <= $nowTime || $dateslect != $crntdate) {

                                $timeArray = array();
                                $ikj = 0;
                                $strtodatyetime = $dateslect . " " . date('H:i:s', $tNow);

                                $dis = 0;
                                $total = 0;

                                foreach ($serviceDetails as $row) {

                                    if ($row->parent_service_id) {
                                        $pstime = $this->user->select(
                                            'st_offer_availability',
                                            'starttime,endtime',
                                            array(
                                              'service_id'=>$row->parent_service_id,
                                              'days' => $dayName
                                            )
                                        );
                                        if ($pstime) {
                                            $row->starttime = $pstime[0]->starttime;
                                            $row->endtime = $pstime[0]->endtime;
                                        }
                                    }

                                    if (!empty($row->starttime) && !empty($row->endtime) && $row->starttime <= $nowTime && $row->endtime >= $nowTime) {
                                        if (!empty($row->discount_price)) {
                                            $dis = ($row->price - $row->discount_price) + $dis;
                                            $total = $row->discount_price + $total;
                                        } else {
                                            $total = $row->price + $total;
                                        }
                                    } else {
                                        $total = $row->price + $total;
                                    }

                                    $timeArray[$ikj] = new stdClass;

                                    $bkstartTime = $strtodatyetime;
                                    $timeArray[$ikj]->start = $bkstartTime;

                                    if ($row->service_type == 1) {

                                        $bkEndTime = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $row->setuptime . ' minute'));
                                        $timeArray[$ikj]->end = $bkEndTime;
                                        $ikj++;

                                        $finishStart = date('Y-m-d H:i:s', strtotime('' . $bkEndTime . ' + ' . $row->processtime . ' minute'));
                                        $timeArray[$ikj] = new stdClass;
                                        $timeArray[$ikj]->start = $finishStart;

                                        $finishEnd = date('Y-m-d H:i:s', strtotime('' . $finishStart . ' + ' . $row->finishtime . ' minute'));
                                        $timeArray[$ikj]->end = $finishEnd;
                                        $ikj++;

                                        $strtodatyetime = $finishEnd;

                                    } else {
                                        $totalMin = $row->duration;

                                        $bkEndTime = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $totalMin . ' minute'));
                                        $timeArray[$ikj]->end = $bkEndTime;
                                        $ikj++;

                                        $strtodatyetime = $bkEndTime;
                                    }
                                }

                                $resultCheckSlot = checkTimeSlots($timeArray, $id, $mrntid, $totaldurationTim, $bookid);

                                if ($resultCheckSlot == true) {
                                    $pdisc = "";

                                    if (!empty($dis)) {
                                        $pdisc = price_formate($dis + $total) . " €";
                                    }

                                    $ptotal = $total;

                                    $checkClass = "";
                                    $selctedClass = "";
                                    if ($nowTime == $checkedTime) {
                                        $checkClass = "checked";
                                        $selctedClass = " selected_time";
                                    }

                                    $html = $html . '<li class="select-time-price lineheight40 ' . $selctedClass . '">
															<input type="radio" id="id_time-1price' . $k . '" name="chg_time" class="slectTime" value="' . date("H:i:s", $tNow) . '" ' . $checkClass . '>
															<label for="id_time-1price' . $k . '">
															  <span class="text-left pl-10">' . date("H:i", $tNow) . '</span>
															  <span>
																 <span class="new-price float-right">' . price_formate($ptotal) . '€</span>
																<span class="old-price "><del>' . $pdisc . ' </del></span>
															  </span>
															</label>
														  </li>';
                                    $k++;
                                }

                            }
                            $tNow = strtotime('+15 minutes', $tNow);
                        }

                        if (!empty($availablity->starttime_two) && !empty($availablity->endtime_two)) {

                            $start = $availablity->starttime_two; //you can write here 00:00:00 but not need to it
                            $end = $availablity->endtime_two;

                            $tStart = strtotime($start);
                            $tEnd = strtotime(date('H:i:s', strtotime($end . "- " . $totaldurationTim . " minutes")));

                            $currtime = date('H:i:s');
                            $crntdate = date('Y-m-d');
                            //echo $tStart."==".$tEnd;
                            $k = 1;
                            $tNow = $tStart;
                            while ($tNow <= $tEnd) {
                                //~ $checkBoking=1;
                                //~ if(!empty($empBookSlot)){
                                //~ foreach($empBookSlot as $eslot){

                                //~ $estarttime=date('H:i:s',strtotime($eslot->booking_time. "- ".$totaldurationTim." minutes"));
                                //~ $eendtime=date('H:i:s',strtotime($eslot->booking_endtime));
                                //~ //if($estarttime<=date('H:i:s',$tNow) && $eendtime>=date('H:i:s',$tNow)) // time slot changes
                                //~ if($estarttime<date('H:i:s',$tNow) && $eendtime>date('H:i:s',$tNow)){
                                //~ $checkBoking=2;
                                //~ break;
                                //~ }
                                //~ }
                                //~ }

                                $nowTime = date("H:i:s", $tNow);
                                //~ echo $nowTime."==".$currtime."==".$date."==".$crntdate."</br>";
                                if (($date == $crntdate && $currtime <= $nowTime || $date != $crntdate)) {

                                    $timeArray = array();
                                    $ikj = 0;
                                    $strtodatyetime = $dateslect . " " . date('H:i:s', $tNow);
                                    $dis = 0;
                                    $total = 0;

                                    foreach ($serviceDetails as $row) {

                                        if (!empty($row->starttime) && !empty($row->endtime) && $row->starttime <= $nowTime && $row->endtime >= $nowTime) {
                                            if (!empty($row->discount_price)) {
                                                $dis = ($row->price - $row->discount_price) + $dis;
                                                $total = $row->price + $total;
                                            } else {
                                                $total = $row->price + $total;
                                            }
                                        } else {
                                            $total = $row->price + $total;
                                        }

                                        $timeArray[$ikj] = new stdClass;

                                        $bkstartTime = $strtodatyetime;
                                        $timeArray[$ikj]->start = $bkstartTime;

                                        if ($row->stype == 1) {

                                            $bkEndTime = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $row->setuptime . ' minute'));
                                            $timeArray[$ikj]->end = $bkEndTime;
                                            $ikj++;

                                            $finishStart = date('Y-m-d H:i:s', strtotime('' . $bkEndTime . ' + ' . $row->processtime . ' minute'));
                                            $timeArray[$ikj] = new stdClass;
                                            $timeArray[$ikj]->start = $finishStart;

                                            $finishEnd = date('Y-m-d H:i:s', strtotime('' . $finishStart . ' + ' . $row->finishtime . ' minute'));
                                            $timeArray[$ikj]->end = $finishEnd;
                                            $ikj++;

                                            $strtodatyetime = $finishEnd;

                                        } else {
                                            $totalMin = $row->duration;

                                            $bkEndTime = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $totalMin . ' minute'));
                                            $timeArray[$ikj]->end = $bkEndTime;
                                            $ikj++;

                                            $strtodatyetime = $bkEndTime;
                                        }
                                    }

                                    $resultCheckSlot = checkTimeSlots($timeArray, $id, $mrntid, $totaldurationTim, $bookid);

                                    if ($resultCheckSlot == true) {
                                        $pdisc = "";

                                        if (!empty($dis)) {
                                            $pdisc = price_formate($dis + $total) . " €";
                                        }
    
                                        $ptotal = $total;

                                        $checkClass = "";
                                        $selctedClass = "";

                                        if ($nowTime == $checkedTime) {
                                            $checkClass = "checked";
                                            $selctedClass = " selected_time";
                                        }

                                        $html = $html . '<li class="select-time-price lineheight40">
																	<input type="radio" id="id_time-2price' . $k . '" name="chg_time" class="slectTime" ' . $selctedClass . ' value="' . date("H:i:s", $tNow) . '" ' . $checkClass . '>
																		<label for="id_time-2price' . $k . '">
																		  <span class="text-left pl-10">' . date("H:i", $tNow) . '</span>
																		  <span>
																			<span class="new-price float-right">' . $ptotal . '€</span>
																			<span class="old-price "><del>' . $pdisc . '</del></span>
																		  </span>
																		</label>
																	  </li>';
                                        $k++;
                                    }

                                }
                                $tNow = strtotime('+15 minutes', $tNow);
                            }

                        }

                        if (empty($html)) {
                            $html = $emptyhtml;
                        }

                        echo json_encode(array('success' => 1, 'message' => 'success', 'html' => $html));
                    } else {
                        echo json_encode(array('success' => 1, 'message' => 'employee not work for selected day', 'html' => $emptyhtml));
                    }

                } else {
                    echo json_encode(array('success' => 1, 'message' => 'Salon not update the time', 'html' => $emptyhtml));
                }

            } else {
                echo json_encode(array('success' => 1, 'message' => 'You can not reschedule this booking', 'html' => $emptyhtml));
            }

        } else {
            echo json_encode(array('success' => 1, 'message' => 'Please select a valid date.', 'html' => $emptyhtml));
        }

    }

//**** Booking reshedule ****//
    public function booking_reshedule()
    {
        $originalDate = $_POST['chg_date'];
        $oldDate = '';
        $newDate = date("Y-m-d", strtotime($originalDate));
        $field = 'id,merchant_id,booking_time,total_minutes,booking_type,email,fullname,total_buffer,employee_id,(SELECT notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as additional_notification_time';

        $info = $this->user->select_row('st_booking', $field, array('id' => url_decode($_POST['reSchedule_id']), 'reshedule_count_byuser !=' => 1));


        if (!empty($info)) {
            $oldDate = $info->booking_time;
            $toDate = date('Y-m-d');

            if ($newDate < $toDate) {
                echo json_encode(array('success' => 0, 'msg' => 'Please select valid date'));
            } else {

                $date = $newDate;
                $nameOfDay = date('l', strtotime($date));
                $totalMinutes = 0; //$info->total_minutes+$info->total_buffer;
                $times = strtotime($_POST['chg_time']);
                $newTime = date("H:i", strtotime('+ ' . $totalMinutes . ' minutes', $times));
                $dayName = strtolower($nameOfDay);

                $bk_time = $newDate . ' ' . $_POST['chg_time'];

                $sqlForservice = "SELECT `st_booking_detail`.`id`,`st_booking_detail`.`user_id`,`st_booking_detail`.`buffer_time`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`,st_booking_detail.duration,st_booking_detail.service_type as stype,st_booking_detail.setuptime,st_booking_detail.processtime,st_booking_detail.finishtime,st_booking_detail.has_buffer,`st_merchant_category`.`price`, `st_merchant_category`.`discount_price`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime`,`parent_service_id` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='" . $dayName . "') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = " . url_decode($_POST['reSchedule_id']) . "  ORDER BY st_booking_detail.id";

                $booking_detail = $this->user->custome_query($sqlForservice, 'result');

                $total_price = $total_buffer = $total_min = $total_dis = 0;

                if (!empty($booking_detail)) {

                    $timeArray = array();
                    $ikj = 0;
                    $strtodatyetime = $bk_time;

                    foreach ($booking_detail as $row) {

                        $timeArray[$ikj] = new stdClass;
                        $bkstartTime = $strtodatyetime;
                        $timeArray[$ikj]->start = $bkstartTime;

                        if ($row->parent_service_id) {
                            $pstime = $this->user->select(
                                'st_offer_availability',
                                'starttime,endtime,days,type',
                                array(
                                'service_id'=>$row->parent_service_id,
                                'days' => $dayName
                                )
                            );
                            if ($pstime) {
                                $row->starttime = $pstime[0]->starttime;
                                $row->endtime = $pstime[0]->endtime;
                                $row->type = $pstime[0]->type;
                                $row->days = $pstime[0]->days;
                            }
                        }

                        if ($row->stype == 1) {
                            $total_min = $row->duration + $total_min;

                            $bkEndTime = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $row->setuptime . ' minute'));
                            $timeArray[$ikj]->end = $bkEndTime;
                            $ikj++;

                            $finishStart = date('Y-m-d H:i:s', strtotime('' . $bkEndTime . ' + ' . $row->processtime . ' minute'));
                            $timeArray[$ikj] = new stdClass;
                            $timeArray[$ikj]->start = $finishStart;

                            $finishEnd = date('Y-m-d H:i:s', strtotime('' . $finishStart . ' + ' . $row->finishtime . ' minute'));
                            $timeArray[$ikj]->end = $finishEnd;
                            $ikj++;

                            $strtodatyetime = $finishEnd;

                        } else {
                            $total_buffer = $row->buffer_time + $total_buffer;
                            $totalMin = $row->duration;

                            $total_min = $row->duration + $total_min - $row->buffer_time;

                            $bkEndTime = date('Y-m-d H:i:s', strtotime('' . $bkstartTime . ' + ' . $totalMin . ' minute'));
                            $timeArray[$ikj]->end = $bkEndTime;
                            $ikj++;

                            $strtodatyetime = $bkEndTime;
                        }

                        if (!empty($row->type) && $row->type == 'open') {
                            if ($row->starttime <= date('H:i:s', strtotime($bk_time)) && $row->endtime >= date('H:i:s', strtotime($bk_time))) {
                                if (!empty($row->discount_price)) {
                                    $total_dis = ($row->price - $row->discount_price) + $total_dis;
                                    $total_price = $row->discount_price + $total_price;
                                } else {
                                    $total_price = $row->price + $total_price;
                                }
                            } else {
                                $total_price = $row->price + $total_price;
                            }

                        } else {
                            $total_price = $row->price + $total_price;
                        }

                    }
                    $totalMinutes = $total_buffer + $total_min;

                    $resultCheckSlot = checkTimeSlots($timeArray, $info->employee_id, $info->merchant_id, $totalMinutes, url_decode($_POST['reSchedule_id']));

                    if ($resultCheckSlot == true) {

                        $min = $total_buffer + $total_min;
                        $newtimestamp = strtotime('' . $bk_time . ' + ' . $min . ' minute');
                        $book_end = date('Y-m-d H:i:s', $newtimestamp);
                        //notification set time
                        $notif_time = $info->notification_time;
                        $ad_notif_time = $info->additional_notification_time;
                        $timestamp = strtotime($bk_time);
                        $time = $timestamp - ($notif_time * 60 * 60);
                        $ad_time = $timestamp - ($ad_notif_time * 60 * 60);
                        // Date and time after subtraction
                        $notif_date = date("Y-m-d H:i:s", $time);
                        if ($ad_notif_time != '0') {
                            $ad_notif_date = date("Y-m-d H:i:s", $ad_time);
                            $book_Arr['additional_notification_date'] = $ad_notif_date;
                        }

                        $book_Arr['booking_time'] = $bk_time;
                        $book_Arr['booking_endtime'] = $book_end;
                        $book_Arr['total_minutes'] = $total_min;
                        $book_Arr['total_buffer'] = $total_buffer;
                        $book_Arr['total_time'] = $min;
                        $book_Arr['total_price'] = $total_price;
                        $book_Arr['total_discount'] = $total_dis;
                        $book_Arr['pay_status'] = 'cash';
                        $book_Arr['status'] = 'confirmed';
                        $book_Arr['notification_date'] = $notif_date;
                        $book_Arr['updated_on'] = date('Y-m-d H:i:s');
                        $book_Arr['updated_by'] = $this->session->userdata('st_userid');
                        $book_Arr['reshedule_count_byuser'] = 1;
                        $book_Arr['seen_status'] = 0;

                        if ($this->user->update('st_booking', $book_Arr, array('id' => url_decode($_POST['reSchedule_id'])))) {
                            //$this->user->delete('st_booking_detail',array('booking_id' => url_decode($_POST['reSchedule_id'])));
                            $boojkstartTime = $bk_time;

                            foreach ($booking_detail as $row) {

                                $detail_Arr = [];
                                //$detail_Arr['booking_id']       = url_decode($_POST['reSchedule_id']);
                                $detail_Arr['mer_id'] = $info->merchant_id;
                                $detail_Arr['emp_id'] = $info->employee_id;
                                $detail_Arr['service_type'] = $row->stype;

                                if ($row->stype == 1) {
                                    $detail_Arr['setuptime'] = $row->setuptime;
                                    $detail_Arr['processtime'] = $row->processtime;
                                    $detail_Arr['finishtime'] = $row->finishtime;
                                    $detail_Arr['setuptime_start'] = $boojkstartTime;

                                    $setuEnd = date('Y-m-d H:i:s', strtotime('' . $boojkstartTime . ' + ' . $row->setuptime . ' minute'));
                                    $finishStart = date('Y-m-d H:i:s', strtotime('' . $setuEnd . ' + ' . $row->processtime . ' minute'));
                                    $finishEnd = date('Y-m-d H:i:s', strtotime('' . $finishStart . ' + ' . $row->finishtime . ' minute'));

                                    $detail_Arr['setuptime_end'] = $setuEnd;
                                    $detail_Arr['finishtime_start'] = $finishStart;
                                    $detail_Arr['finishtime_end'] = $finishEnd;

                                    $boojkstartTime = $finishEnd;

                                } else {
                                    $totalMin = $row->duration;
                                    $setuEnd = date('Y-m-d H:i:s', strtotime('' . $boojkstartTime . ' + ' . $totalMin . ' minute'));
                                    $detail_Arr['setuptime_start'] = $boojkstartTime;
                                    $detail_Arr['setuptime_end'] = $setuEnd;

                                    $boojkstartTime = $setuEnd;
                                }

                                $detail_Arr['service_id'] = $row->service_id;
                                if (!empty($row->name)) {
                                    $detail_Arr['service_name'] = $row->name;
                                } else {
                                    $detail_Arr['service_name'] = $row->category_name;
                                }

                                $detail_Arr['price'] = 0;
                                $detail_Arr['discount_price'] = 0;
                                if (!empty($row->type) && $row->type == 'open') {
                                    if ($row->starttime <= date('H:i:s', strtotime($bk_time)) && $row->endtime >= date('H:i:s', strtotime($bk_time))) {
                                        if (!empty($row->discount_price)) {
                                            $detail_Arr['price'] = $row->discount_price;
                                            $detail_Arr['discount_price'] = $row->price - $row->discount_price;
                                        } else {
                                            $detail_Arr['price'] = $row->price;
                                        }

                                    } else {
                                        $detail_Arr['price'] = $row->price;
                                    }

                                } else {
                                    $detail_Arr['price'] = $row->price;
                                }

                                $detail_Arr['duration'] = $row->duration;
                                $detail_Arr['buffer_time'] = $row->buffer_time;
                                $detail_Arr['has_buffer'] = $row->has_buffer;
                                $detail_Arr['updated_on'] = date('Y-m-d H:i:s');
                                $detail_Arr['user_id'] = $row->user_id;
                                $detail_Arr['updated_by'] = $this->session->userdata('st_userid');
                                $this->user->update('st_booking_detail', $detail_Arr, array('id' => $row->id));

                            }

                            $this->data['main'] = "";
                            ///mail section
                            $this->data['main'] = $this->user->join_two('st_booking', 'st_users', 'merchant_id', 'id', array('st_booking.id' => url_decode($_POST['reSchedule_id'])), 'st_booking.id,business_name,first_name as salon_name,st_users.salon_email_setting,st_users.email,booking_time,book_id,st_booking.merchant_id,employee_id,total_time,st_booking.user_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select email from st_users where id=st_booking.user_id) as usemail,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');

                            if (!empty($this->data['main'])) {

                                $field = "st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";
                                $whr = array('booking_id' => url_decode($_POST['reSchedule_id']));

                                $this->data['booking_detail'] = $this->user->join_two('st_booking_detail', 'st_users', 'user_id', 'id', $whr, $field);
                                $this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

                                $salonDetails = $this->user->select_row('st_users', '*', array('id' => $this->data['main'][0]->merchant_id));
                                $MsgTitle = "styletimer - Termin verschoben";
                                $body_msg = 'Dein Termin bei '.$salonDetails->business_name.' wurde erfolgreich verschoben!';

                                if ($info->booking_type == 'guest') {
                                    $email = $info->email;
                                    $this->data['booking_detail'][0]->first_name = $info->fullname;
                                } else {
                                    if ($this->data['main'][0]->user_notify != 0) {
                                        sendPushNotification($this->data['main'][0]->user_id, array('body' => $body_msg, 'title' => $MsgTitle, 'salon_id' => $this->data['main'][0]->merchant_id, 'book_id' => url_decode($_POST['reSchedule_id']), 'booking_status' => 'reschedule', 'click_action' => 'BOOKINGDETAIL'));
                                    }
                                    $email = $this->data['main'][0]->usemail;
                                }
                                if ($this->data['main'][0]->salon_email_setting == 1) {
                                    $this->data['old_date'] = $oldDate;
                                    $message1 = $this->load->view('email/reshedule_booking_salon', $this->data, true);
                                    $mail1 = emailsend($this->data['main'][0]->email, $this->lang->line("styletimer_reschedule_booking"), $message1, 'styletimer');
                                }
                                $message = $this->load->view('email/reshedule_booking_new', $this->data, true);
                                $mail = emailsend($email, $this->lang->line("styletimer_reschedule_booking"), $message, 'styletimer');

                                $empDat = is_mail_enable_for_user_action($this->data['main'][0]->employee_id);
                                if ($empDat) {
                                    $tmp = $this->data;
                                    $tmp['main'][0]->salon_name = $empDat->first_name;
                                    $tmp['old_date'] = $oldDate;
                                    $message2 = $this->load->view('email/reshedule_booking_salon',$tmp, true);
                                    emailsend($empDat->email,$this->lang->line('styletimer_reschedule_booking'),$message2,'styletimer');
                                }

                            }
                            $yrdata = strtotime($_POST['chg_date']);
                            //$ddd = date('d F Y', $yrdata);
                            $ddd = date('d.m.Y', $yrdata);
                            $yrda = strtotime($_POST['chg_time']);
                            $ttt = date('H:i', $yrda);
                            echo json_encode(array('success' => 1,
                                'msg' => 'Die Buchung wurde erfolgreich auf den <br/>' . $ddd . ' um ' . $ttt . ' Uhr verlegt.',
                            ));
                        } else {
                            echo json_encode(array('success' => 0, 'msg' => 'Sorry unable to process'));
                        }

                    } else {
                        echo json_encode(array('success' => 0, 'msg' => 'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar'));
                    }

                } else {
                    echo json_encode(array('success' => 0, 'msg' => 'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar'));
                }

            }

            //date("h:i");
        } else {
            echo json_encode(array('success' => 0, 'msg' => 'You can not reschedule this booking.'));
        }

    }

    public function viewinvoice($id = "")
    {
        if (!empty($id)) {
            $invoiceId = url_decode($id);

            $field = "st_invoices.*,st_booking.employee_id,st_booking.merchant_id,book_id,book_by,total_time,booking_time,st_users.first_name,st_users.last_name,st_booking.booking_type,st_booking.updated_on,fullname";

            $this->data['invoice_detail'] = $this->booking->join_three_row('st_invoices', 'st_booking', 'st_users', 'booking_id', 'id', 'employee_id', 'id', array('st_invoices.id' => $invoiceId), $field);
            if (!empty($this->data['invoice_detail']->booking_id)) {
                $field = "st_booking_detail.id,st_booking_detail.user_id,st_booking_detail.tax,setuptime_start,first_name,last_name,profile_pic,email,address,zip,country,city,service_name,duration,price,discount_price,service_id";

                $whr = array('booking_id' => $this->data['invoice_detail']->booking_id);
                $this->db->order_by('st_booking_detail.id', 'asc');
                $this->data['booking_detail'] = $this->booking->join_two('st_booking_detail', 'st_users', 'user_id', 'id', $whr, $field);

                $this->data['slondetail'] = $this->booking->select_row('st_users', 'business_name,email,address,zip,country,city', array('id' => $this->data['invoice_detail']->merchant_id));

            }

            //echo "<pre>"; print_r($this->data); die;
            $this->load->view('frontend/user/invoice', $this->data);
        }

    }

    // terms_popup
    public function termsin_popup()
    {
        if ($_POST['access'] == 'user') {
            if ($_POST['type'] == 'terms') {
                $this->data['terms'] = $this->user->select_row('st_static_page', '*', array('type' => 'userterms'));
                $html = $this->load->view('frontend/terms_condition_user_popup', $this->data, true);
            } elseif ($_POST['type'] == 'conditions') {
                $this->data['policy'] = $this->user->select_row('st_static_page', '*', array('type' => 'conditions'));
                $html = $this->load->view('frontend/privacy_policy_user_popup', $this->data, true);

            } else {
                $this->data['policy'] = $this->user->select_row('st_static_page', '*', array('type' => 'userpolicy'));
                $html = $this->load->view('frontend/privacy_policy_user_popup', $this->data, true);
            }
        } else {
            if ($_POST['type'] == 'terms') {
                $this->data['terms'] = $this->user->select_row('st_static_page', '*', array('type' => 'salonterms'));
                $html = $this->load->view('frontend/terms_condition_merchant_popup', $this->data, true);
            } elseif ($_POST['type'] == 'conditions') {
                $this->data['policy'] = $this->user->select_row('st_static_page', '*', array('type' => 'conditions'));
                $html = $this->load->view('frontend/privacy_policy_user_popup', $this->data, true);

            } else {
                $this->data['policy'] = $this->user->select_row('st_static_page', '*', array('type' => 'salonpolicy'));
                $html = $this->load->view('frontend/privacy_policy_merchant_popup', $this->data, true);
            }
        }
        //echo $html;
        echo json_encode(['success' => 1, 'html' => $html]);
    }

    public function get_staticpage($type)
    {
        if ($type == 'terms') {
            $this->data['terms'] = $this->user->select_row('st_static_page', '*', array('type' => 'userterms'));
        } else {
            $this->data['terms'] = $this->user->select_row('st_static_page', '*', array('type' => 'userpolicy'));
        }

        $this->load->view('frontend/terms_privacy_app', $this->data);

    }

    // get review popup
    public function get_review_popup()
    {
        $bid = url_decode($_POST['id']);

        $main = $this->booking->join_two('st_booking', 'st_users', 'user_id', 'id', array('st_booking.id' => $bid), 'st_booking.id,st_booking.merchant_id,st_users.id as uid,st_users.first_name,st_users.last_name,st_users.profile_pic,st_booking.employee_id,(SELECT count(id) FROM st_review WHERE st_review.booking_id = st_booking.id) as review,(SELECT business_name FROM st_users WHERE st_users.id=st_booking.id) as business_name');

        //$this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$bid),'st_booking.id,st_booking.book_id,st_booking.invoice_id,st_booking.merchant_id,business_name');

        $reviehtml = "";
        if (!empty($main)) {
            if (empty($main[0]->review)) {

                if ($main[0]->profile_pic != '') {
                    $img = base_url('assets/uploads/users/') . $main[0]->uid . '/icon_' . $main[0]->profile_pic;
                } else {
                    $img = base_url('assets/frontend/images/user-icon-gret.svg');
                }

                $us_name = $main[0]->first_name . ' ' . $main[0]->last_name;

                $reviehtml = '<div class="display-ib vertical-middle">
                      <img src="' . $img . '" class="width40v border-radius50 mr-3">
                    </div>
                    <div class="display-ib vertical-middle">
                      <p class="font-size-16 color333 fontfamily-medium mb-1">' . $us_name . '</p>
                      <p class="fontfamily-regular font-size-14 color666 mb-0">' . $main[0]->business_name . '</p>
                    </div>
                    <form id="frmReview" method="post" action="' . base_url("booking/review") . '">
                    <div class="form-group mb-30 pt-3 mt-3" style="border-top: 1px solid #c4c4c4;">
                      <fieldset class="rating vertical-sub" style="" >
                        <input type="radio" id="star-5" name="rating" value="5">
                        <label class="rating" for="star-5" title="">
                          <i class="fas fa-star"></i>
                        </label>

                        <input type="radio" id="star-4" name="rating" value="4">
                        <label class="rating" for="star-4" title="">
                          <i class="fas fa-star"></i>
                        </label>

                        <input type="radio" id="star-3" name="rating" value="3">
                        <label class="rating" for="star-3" title="">
                          <i class="fas fa-star"></i>
                        </label>

                        <input type="radio" id="star-2" name="rating" value="2">
                        <label class="rating" for="star-2" title="">
                          <i class="fas fa-star"></i>
                        </label>

                        <input type="radio" id="star-1" name="rating" value="1">
                        <label class="rating" for="star-1" title="">
                          <i class="fas fa-star"></i>
                        </label>

                      </fieldset>
                    </div>
                     <label class="error_label" id="rating_err" style="margin-top: -15px;"></label>
                    <div class="form-group inp v_inp_new "  style="height:100px;">
                      <textarea type="text" id="txtreview" name="txtreview" placeholder="&nbsp;" class="form-control custom_scroll w-100" style="max-height:100px;min-height: 100px"></textarea>
                       <label class="label">' . $this->lang->line('Write-Review') . '</label>
                   </div>
                    <label class="error_label" id="rating_text"></label>
                   <div class="checkbox mt-4 mb-5">
                      <label class="font-size-14 pl0 colorcyan">
                        <input type="checkbox" name="anonymous" value="1">
                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                        ' . $this->lang->line('Rate-as-Anonymous') . '
                      </label>
                    </div>
                    <input type="hidden" id="booking_id" name="booking_id" value="' . url_encode($main[0]->id) . '">
                    <input type="hidden" id="merchant_id" name="merchant_id" value="' . url_encode($main[0]->merchant_id) . '">
                     <input type="hidden" id="" name="employeeid" value="' . $main[0]->employee_id . '">
                   <div class="text-center">
                    <button type="button" id="saveReviewRatingUsers" class="btn btnlarge widthfit">' . $this->lang->line('Submit-Review') . '</button>
                  </div>
                  </form>';
            } else {
                $reviehtml = '<h5>Review already saved, please refresh your page....!</h5>';
            }
        }
        echo json_encode(['success' => 1, 'html' => $reviehtml]);
    }
}
