<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/phpexcel/PHPExcel.php';

class Merchant extends Frontend_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->library('image_moo');
        $this->load->library('PHPExcel');
        $usid = $this->session->userdata('st_userid');
        $p_status = $this->session->userdata('profile_status');
        if (!empty($usid)) {
            $status = getstatus_row($usid);
            if ($status != 'active') {
                redirect(base_url('auth/logouts/') . $status);
            }
            require_once FCPATH . '/stripe/init.php';
            \Stripe\Stripe::setApiKey(STRIPE_SK);
        }

        //(empty($_GET['setup']))
        /*if(empty($_POST) && ($p_status !="complete") && (empty($_GET['setup'])) && $this->session->userdata('access') =='marchant'){
			$p_status = getprofilestatus_row($uid);
			if(empty($p_status))
				$p_status = 'profile';
			redirect(base_url('merchant/dashboard?setup=').$p_status);
		}*/
        if (
            empty($_POST) &&
            empty($_GET['setup']) &&
            $this->session->userdata('access') == 'marchant'
        ) {
            $p_status = getprofilestatus_row($usid);

            if ($p_status != 'complete') {
                if (empty($p_status)) {
                    $p_status = 'profile';
                }
                redirect(base_url('merchant/dashboard?setup=') . $p_status);
            }
        }
        $this->lang->load('push_notification', 'german');
    }

    //**** Merchant Registartion  ****//
    public function registration()
    {
        if (empty($this->session->userdata('st_userid'))) {
            $this->data['ptype'] = 'merchant_registration';
            $this->data['share_desc'] =
                'Digitale Termin- und Kundenverwaltung für Friseur und Beauty mit intelligentem Kalender inkl. Android & IOS Apps für ihre Kunden. Jetzt kostenlos testen!';
            //echo '<pre>'; print_r($this->data); die;
            $this->load->view(
                'frontend/marchant/merchant_registration',
                $this->data
            );
        } else {
            redirect(base_url());
        }
    }

    //**** Thankyou after registration ****//
    public function thankyou()
    {
        if (!empty($this->session->userdata('st_userid'))) {
            redirect(base_url());
        } else {
            $this->load->view('frontend/thank_you');
        }
    }
    //**** change password ****//
    public function change_password()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        } else {
            $this->load->view('frontend/marchant/change_password');
        }
    }
    //**** Remove Image ****//
    public function remove_image()
    {
        $upd_data = ['image' . $_POST['field'] => ''];

        $res = $this->user->update('st_banner_images', $upd_data, [
            'user_id' => $_POST['id'],
        ]);
        if ($res) {
            $path =
                'assets/uploads/banners/' .
                $this->session->userdata('st_userid') .
                '/';
            if (file_exists($path . $_POST['old_image'])) {
                unlink($path . $_POST['old_image']);
            }
        }
        echo $res;
    }
    //**** Add Employee **** //
    public function addemployee()
    {
        if ($this->session->userdata('st_regMid') == '') {
            redirect(base_url());
        }

        if (isset($_POST['subEmps'])) {
            extract($_POST);
            $pass = $this->ion_auth->hash_password($password);
            $insertArr = [];
            $insertArr['first_name'] = $first_name;
            $insertArr['last_name'] = $last_name;
            $insertArr['mobile'] = $telephone;
            $insertArr['email'] = $email;
            $insertArr['password'] = $pass;
            $insertArr['created_by'] = $this->session->userdata('st_regMid');
            $insertArr['merchant_id'] = $this->session->userdata('st_regMid');
            $insertArr['status'] = 'active';
            $insertArr['access'] = 'employee';
            $insertArr['created_on'] = date('Y-m-d H:i:s');
            $uid = $this->user->insert('st_users', $insertArr);
            if ($uid) {
                if (
                    isset($_FILES['profile_img']['name']) &&
                    $_FILES['profile_img']['name'] != ''
                ) {
                    $path = 'assets/uploads/employee/' . $uid . '/';
                    $filename = explode('.', $_FILES['profile_img']['name']);
                    $ext = $filename[count($filename) - 1];
                    $ext = strtolower($ext);
                    if (!is_dir($path)) {
                        @mkdir($path, 0777, true);
                    }

                    $this->load->library('Image_moo');
                    $filename =
                        'Prf_' .
                        time() .
                        $uid .
                        '.' .
                        $filename[count($filename) - 1];
                    $tmpfile = $_FILES['profile_img']['tmp_name'];
                    $uploadfil = move_uploaded_file(
                        $tmpfile,
                        $path . $filename
                    );

                    foreach (
                        ['thumb_' => [250, 250], 'icon_' => [115, 115]]
                        as $key => $val
                    ) {
                        $this->image_moo
                            ->load($path . $filename)
                            ->set_jpeg_quality(100)
                            ->resize_crop($val[0], $val[1])
                            ->save("{$path}{$key}{$filename}", true);
                    }
                    $upd['profile_pic'] = $filename;
                    //$this->user->update('st_users',array('profile_pic'=>$filename),array('id'=>$uid));
                }

                $ids = time() . $uid;
                $upd['activation_code'] = $ids;

                $this->user->update('st_users', $upd, ['id' => $uid]);

                //$ids=time().$uid;
                //$message = $this->load->view('email/employee_activtion_link',array('link'=>base_url("merchant/employee_registration/$ids"),"business_name"=>$this->session->userdata('business_name'),"mname"=>$this->session->userdata('sty_fname'),"name"=>ucwords($this->input->post('first_name')), "button"=>"Employee Registration", "msg"=>"This message has been sent to you by StyleTimer. Click on the link below to Register your account."), true);
                if (!empty($this->session->userdata('business_name'))) {
                    $business_name = $this->session->userdata('business_name');
                } else {
                    $mrchant_detail = $this->user->select_row(
                        'st_users',
                        'business_name',
                        ['id' => $this->session->userdata('st_regMid')]
                    );
                    $business_name = $mrchant_detail->business_name;
                }

                $message = $this->load->view(
                    'email/employee_activtion_link',
                    [
                        'username' => $email,
                        'password' => $password,
                        'business_name' => $business_name,
                        'mname' => $this->session->userdata('sty_fname'),
                        'name' => ucwords($this->input->post('first_name')),
                        'button' => 'Employee Registration',
                        'msg' =>
                            'This message has been sent to you by StyleTimer. Click on the link below to Register your account.',
                    ],
                    true
                );

                $mail = emailsend(
                    $email,
                    'styletimer - Mitarbeiterregistrierung',
                    $message,
                    'styletimer'
                );

                $this->session->unset_userdata('st_regMid');
                $this->session->set_flashdata(
                    'success',
                    'Employee added successfully.'
                );
                if ($this->session->userdata('access') == 'marchant') {
                    //redirect(base_url('merchant/thankyou'));
                    redirect(base_url('profile/edit_marchant_profile'));
                } else {
                    redirect(base_url('merchant/thankyou'));
                }
            }
        }
        $this->load->view('frontend/marchant/registration_addemployee');
    }

    //**** Employee Registration ****//
    public function employee_registration($id = '')
    {
        if ($id != '') {
            $field = 'id,first_name,last_name,email,mobile';
            $this->data['userdetail'] = $this->user->select_row(
                'st_users',
                $field,
                ['activation_code' => $id]
            );
            if (!empty($this->data['userdetail'])) {
                $this->load->view(
                    'frontend/marchant/employee_registration',
                    $this->data
                );
            } else {
                redirect(base_url());
            }
        }
    }

    //**** Dashboard Add Employee ****//
    public function dashboard_addemployee($id = '')
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        if (isset($_POST['first_name'])) {
            $postdays = $_POST['days'];
            // $poststart=$_POST['start'];
            //$postend=$_POST['end'];
            //$poststarttwo=$_POST['starttwo'];
            // $postendtwo=$_POST['endtwo'];
            //echo "<pre>"; print_r($_POST); die;
            if (isset($_POST['empid'])) {
                extract($_POST);
                $insertArrdata = [];
                $insertArrdata['first_name'] = $first_name;
                $insertArrdata['last_name'] = $last_name;
                $insertArrdata['mobile'] = $telephone;
                $insertArrdata['calender_color'] = '#' . $calender_color;
                $insertArrdata['online_booking'] = isset($chk_online) ? 1 : 0;
                $insertArrdata['mail_by_user'] = !empty($mail_by_user) ? 1 : 0;
                $insertArrdata['mail_by_merchant'] = !empty($mail_by_merchant) ? 1 : 0;
                $insertArrdata['online_booking'] = isset($chk_online) ? 1 : 0;
                $insertArrdata['status'] = isset($chk_status)
                    ? 'active'
                    : 'inactive';
                $insertArrdata['updated_on'] = date('Y-m-d H:i:s');
                $insertArrdata['updated_by'] = $this->session->userdata(
                    'st_userid'
                );
                $insertArrdata['allow_emp_to_delete_cancel_booking'] = !empty(
                    $allow_emp_to_delete_cancel_booking
                )
                    ? 1
                    : 0;
                $eid = url_decode($_POST['empid']);

                if (isset($commission_check)) {
                    $insertArrdata['commission'] = price_formate(
                        $commission,
                        'en'
                    );
                } else {
                    $insertArrdata['commission'] = 0;
                }

                $upload_path = 'assets/uploads/employee/' . $eid . '/';
                $filepath =
                    'assets/uploads/profile_temp/' .
                    $this->session->userdata('st_userid') .
                    '/';

                @mkdir($upload_path, 0777, true);
                $filepath2 = $upload_path;

                $images = scandir($filepath);
                // echo "<pre>"; print_r($images); die;
                $nimages = '';
                $InserData = [];

                for ($i = 2; $i < count($images); $i++) {
                    if (file_exists($filepath . $images[$i])) {
                        echo file_exists($filepath . $images[$i]);
                        rename(
                            $filepath . $images[$i],
                            $filepath2 . $images[$i]
                        );
                        $nimages = $images[$i];
                    }
                }
                if (!empty($nimages)) {
                    $filename = explode('.', $nimages);
                    $fextention = $filename[count($filename) - 1];

                    $insertArrdata['profile_pic'] = $nimages;
                    $this->session->set_userdata(
                        'sty_profile',
                        'thumb_' . $nimages
                    );
                    $this->image_moo
                        ->load($filepath2 . $nimages)
                        ->resize(250, 250)
                        ->save($filepath2 . 'thumb_' . $nimages, true);

                    // resize with slider resolution
                    $this->image_moo
                        ->load($filepath2 . $nimages)
                        ->resize(115, 115)
                        ->save($filepath2 . 'icon_' . $nimages, true);

                    $filepath3 = $upload_path . 'thumb_' . $nimages;
                    $filepath2 = $upload_path . 'icon_' . $nimages;
                    $filepath1 = $upload_path . $nimages;

                    if (strtolower($fextention) != 'webp') {
                        /*****************************************/
                        $image1 = imagecreatefromstring(
                            file_get_contents($filepath1)
                        );
                        ob_start();
                        imagejpeg($image1, null, 100);
                        $cont1 = ob_get_contents();
                        ob_end_clean();
                        imagedestroy($image1);
                        $content1 = imagecreatefromstring($cont1);

                        $output1 = $filepath1 . '.webp';

                        imagewebp($content1, $output1);
                        imagedestroy($content1);

                        /*****************************************/

                        $image2 = imagecreatefromstring(
                            file_get_contents($filepath2)
                        );
                        ob_start();
                        imagejpeg($image2, null, 100);
                        $cont2 = ob_get_contents();
                        ob_end_clean();
                        imagedestroy($image2);
                        $content2 = imagecreatefromstring($cont2);

                        $output2 = $filepath2 . '.webp';

                        imagewebp($content2, $output2);
                        imagedestroy($content2);

                        /*****************************************/

                        $image3 = imagecreatefromstring(
                            file_get_contents($filepath3)
                        );
                        ob_start();
                        imagejpeg($image3, null, 100);
                        $cont3 = ob_get_contents();
                        ob_end_clean();
                        imagedestroy($image3);
                        $content3 = imagecreatefromstring($cont3);

                        $output3 = $filepath3 . '.webp';

                        imagewebp($content3, $output3);
                        imagedestroy($content3);

                        // $uploadPath = "assets/uploads/banners/{$uid}/webp";
                        // $uploadPath1 = "assets/uploads/banners/{$uid}/other";
                    } else {
                        $content1 = imagecreatefromwebp($filepath1);
                        $output1 = $filepath1 . '.png';
                        // Convert it to a jpeg file with 100% quality
                        imagepng($content1, $output1);
                        imagedestroy($content1);

                        /*************************************************************/

                        $content2 = imagecreatefromwebp($filepath2);
                        $output2 = $filepath2 . '.png';
                        // Convert it to a jpeg file with 100% quality
                        imagepng($content2, $output2);
                        imagedestroy($content2);

                        /*************************************************************/

                        $content3 = imagecreatefromwebp($filepath3);
                        $output3 = $filepath3 . '.png';
                        // Convert it to a jpeg file with 100% quality
                        imagepng($content3, $output3);
                        imagedestroy($content3);
                    }
                }
                $pathck = $upload_path;
                //var_dump($nimages); die;
                if (
                    !empty($_POST['old_img']) &&
                    !empty($nimages) &&
                    file_exists($pathck . $_POST['old_img'])
                ) {
                    $del_file = $pathck . $_POST['old_img'];
                    unlink($del_file);
                    if (file_exists($pathck . 'icon_' . $_POST['old_img'])) {
                        unlink($pathck . 'icon_' . $_POST['old_img']);
                    }
                    if (file_exists($pathck . 'thumb_' . $_POST['old_img'])) {
                        unlink($pathck . 'thumb_' . $_POST['old_img']);
                    }
                }

                $old_service = [];
                if (!empty($old_assined_service)) {
                    $old_service = explode(',', $old_assined_service);
                }
                if (!empty($assigned_service)) {
                    $i = 0;
                    $assigned_service = explode(',', $assigned_service);
                    $assigned_subcat = explode(',', $all_subcat);
                    foreach ($assigned_service as $service) {
                        $assin = [];
                        $assin['user_id'] = $eid;
                        $assin['service_id'] = $service;
                        $assin['subcat_id'] = $assigned_subcat[$i];
                        if (in_array($service, $old_service)) {
                            if (
                                ($key = array_search(
                                    $service,
                                    $old_service
                                )) !== false
                            ) {
                                unset($old_service[$key]);
                            }
                        } else {
                            $res_id = $this->user->insert(
                                'st_service_employee_relation',
                                $assin
                            );
                        }
                        $i++;
                    }
                }
                if (!empty($old_service)) {
                    $where =
                        'user_id=' .
                        $eid .
                        ' AND service_id IN(' .
                        implode(',', $old_service) .
                        ')';
                    $this->db->delete('st_service_employee_relation', $where);
                }
                /*********************************************************Start Time**********************************************************************/
                $days_array = [
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                    'sunday',
                ];
                $postdays = $_POST['days'];
                //$i=0;
                /*print_r($_POST);
                 die;*/
                foreach ($days_array as $day) {
                    //echo $poststart[$day];

                    $daydata = $this->user->select_row(
                        'st_availability',
                        'id',
                        ['user_id' => $eid, 'days' => $day]
                    );
                    if (empty($daydata)) {
                        if (in_array($day, $postdays)) {
                            $insertArr = [];
                            $insertArr['user_id'] = $eid;
                            $insertArr['days'] = $day;
                            $insertArr['type'] = 'open';
                            $insertArr['starttime'] = $_POST[$day . '_start'];
                            $insertArr['endtime'] = $_POST[$day . '_end'];
                            $insertArr['starttime_two'] = isset(
                                $_POST[$day . '_start_two']
                            )
                                ? $_POST[$day_two . '_start_two']
                                : '';
                            $insertArr['endtime_two'] = isset(
                                $_POST[$day . '_end_two']
                            )
                                ? $_POST[$day . '_end_two']
                                : '';
                            $insertArr['created_on'] = date('Y-m-d H:i:s');
                            $insertArr['created_by'] = $this->session->userdata(
                                'st_userid'
                            );
                        } else {
                            $insertArr = [];
                            $insertArr['user_id'] = $eid;
                            $insertArr['days'] = $day;
                            $insertArr['type'] = 'close';
                            $insertArr['starttime'] = '';
                            $insertArr['endtime'] = '';
                            $insertArr['starttime_two'] = '';
                            $insertArr['endtime_two'] = '';
                            $insertArr['created_on'] = date('Y-m-d H:i:s');
                            $insertArr['created_by'] = $this->session->userdata(
                                'st_userid'
                            );
                        }
                        $this->user->insert('st_availability', $insertArr);
                    } else {
                        if (in_array($day, $postdays)) {
                            $updateArr = [];
                            $updateArr['user_id'] = $eid;
                            $updateArr['days'] = $day;
                            $updateArr['type'] = 'open';
                            $updateArr['starttime'] = $_POST[$day . '_start'];
                            $updateArr['endtime'] = $_POST[$day . '_end'];
                            $updateArr['starttime_two'] = isset(
                                $_POST[$day . '_start_two']
                            )
                                ? $_POST[$day . '_start_two']
                                : '';
                            $updateArr['endtime_two'] = isset(
                                $_POST[$day . '_end_two']
                            )
                                ? $_POST[$day . '_end_two']
                                : '';

                            //$updateArr['created_on']=date('Y-m-d H:i:s');
                            //$updateArr['created_by']=$this->session->userdata('st_userid');
                        } else {
                            $updateArr = [];
                            $updateArr['user_id'] = $eid;
                            $updateArr['days'] = $day;
                            $updateArr['type'] = 'close';
                            $updateArr['starttime'] = '';
                            $updateArr['endtime'] = '';
                            $updateArr['starttime_two'] = '';
                            $updateArr['endtime_two'] = '';
                            // $updateArr['created_on']=date('Y-m-d H:i:s');
                            //$updateArr['created_by']=$this->session->userdata('st_userid');
                        }

                        $this->user->update('st_availability', $updateArr, [
                            'user_id' => $eid,
                            'days' => $day,
                        ]);
                    }
                    // $i++;
                }

                /**********************************************************End time**********************************************************************/

                if (
                    $this->user->update('st_users', $insertArrdata, [
                        'id' => $eid,
                    ])
                ) {
                    $this->session->set_flashdata(
                        'success',
                        $this->lang->line('succss_update_emloyee')
                    );
                    redirect(base_url('merchant/employee_listing'));
                    //redirect(base_url('merchant/dashboard_addemployee/'.$_POST['empid']));
                } else {
                    $this->session->set_flashdata(
                        'error',
                        'There is some technical error.'
                    );
                    redirect(base_url('merchant/dashboard_addemployee'));
                }
            } else {
                $membership = $this->user->select_row(
                    'st_users',
                    'subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status, profile_status,online_booking, allow_online_booking',
                    ['id' => $this->session->userdata('st_userid')]
                );

                $status = false;
                if (
                    ($membership->subscription_status == 'trial' &&
                    $membership->plan_id == '' &&
                    $membership->end_date > date('Y-m-d H:i:s') )||
                    $membership->profile_status != 'complete' ||
                    ($membership->online_booking && $membership->allow_online_booking == 'true')
                ) {
                    $status = true;
                } elseif ($membership->end_date > date('Y-m-d H:i:s') || $membership->profile_status != 'complete') {
                    if ($membership->plan_id == 'st_premium' || $membership->profile_status != 'complete') {
                        $status = true;
                    } else {
                        $planDteial = $this->user->select_row(
                            'st_membership_plan',
                            'id,employee',
                            ['stripe_plan_id' => $membership->plan_id]
                        );

                        $employeeCount = $this->user->select_row(
                            'st_users',
                            'count(id) as empCount',
                            [
                                'merchant_id' => $this->session->userdata(
                                    'st_userid'
                                ),
                                'status!=' => 'deleted',
                            ]
                        );

                        if ($planDteial->employee > $employeeCount->empCount) {
                            $status = true;
                        } else {
                            $this->session->set_flashdata(
                                'error',
                                'Um weitere Mitarbeiter hinzufügen zu können, musst du deine Mitgliedschaft hochstufen.'
                            );
                            redirect(
                                base_url('merchant/dashboard_addemployee')
                            );
                            //echo json_encode(['success'=>1,'msg'=>'Um weitere Mitarbeiter hinzufügen zu können, musst du deine Mitgliedschaft hochstufen.','url'=>'']);
                        }
                    }
                } else {
                    //if ($membership->subscription_status == 'trial') {
                        $this->session->set_flashdata(
                            'error',
                            'Deine Mitgliedschaft ist abgelaufen. Bitte schließe eine neue Mitgliedschaft ab.'
                        );
                        redirect(base_url('merchant/dashboard_addemployee'));
                        // echo json_encode(['success'=>1,'msg'=>'Deine Mitgliedschaft ist abgelaufen. Bitte schließe eine neue Mitgliedschaft ab.','url'=>'']);
                    // } else {
                    //     $this->session->set_flashdata(
                    //         'error',
                    //         'Your membership has expired. Please subscribe for a membership plan.'
                    //     );
                    //     redirect(base_url('merchant/dashboard_addemployee'));
                    //     // echo json_encode(['success'=>1,'msg'=>'Your membership has expired. Please subscribe for a membership plan.','url'=>'']);
                    // }
                }

                if ($status == true) {
                    extract($_POST);
                    $pass = $this->ion_auth->hash_password($password);
                    $insertArr = [];
                    $insertArr['first_name'] = $first_name;
                    $insertArr['last_name'] = $last_name;
                    $insertArr['mobile'] = $telephone;
                    $insertArr['calender_color'] = '#' . $calender_color;
                    $insertArr['email'] = $email;
                    $insertArr['password'] = $pass;
                    $insertArr['created_by'] = $this->session->userdata(
                        'st_userid'
                    );
                    $insertArr['merchant_id'] = $this->session->userdata(
                        'st_userid'
                    );
                    $insertArr['status'] = 'active';
                    $insertArr['access'] = 'employee';
                    $insertArr['online_booking'] = isset($chk_online) ? 1 : 0;
                    $insertArrdata['mail_by_user'] = !empty($mail_by_user) ? 1 : 0;
                    $insertArrdata['mail_by_merchant'] = !empty($mail_by_merchant) ? 1 : 0;
                    $insertArr['allow_emp_to_delete_cancel_booking'] = !empty(
                        $allow_emp_to_delete_cancel_booking
                    )
                        ? 1
                        : 0;
                    $insertArr['created_on'] = date('Y-m-d H:i:s');
                    if (isset($commission_check)) {
                        $insertArr['commission'] = price_formate(
                            $commission,
                            'en'
                        );
                    } else {
                        $insertArr['commission'] = 0;
                    }

                    $uid = $this->user->insert('st_users', $insertArr);

                    if ($uid) {
                        //$uid = $this->session->userdata('st_userid');
                        $upload_path = 'assets/uploads/employee/' . $uid . '/';
                        $filepath =
                            'assets/uploads/profile_temp/' .
                            $this->session->userdata('st_userid') .
                            '/';

                        @mkdir($upload_path, 0777, true);
                        $filepath2 = $upload_path;

                        $images = scandir($filepath);
                        // echo "<pre>"; print_r($images); die;
                        $nimages = '';
                        $InserData = [];

                        for ($i = 2; $i < count($images); $i++) {
                            if (file_exists($filepath . $images[$i])) {
                                echo file_exists($filepath . $images[$i]);
                                rename(
                                    $filepath . $images[$i],
                                    $filepath2 . $images[$i]
                                );
                                $nimages = $images[$i];
                            }
                        }
                        if (!empty($nimages)) {
                            $this->image_moo
                                ->load($filepath2 . $nimages)
                                ->resize(250, 250)
                                ->save($filepath2 . 'thumb_' . $nimages, true);

                            // resize with slider resolution
                            $this->image_moo
                                ->load($filepath2 . $nimages)
                                ->resize(115, 115)
                                ->save($filepath2 . 'icon_' . $nimages, true);

                            $this->user->update(
                                'st_users',
                                ['profile_pic' => $nimages],
                                ['id' => $uid]
                            );
                        }

                        $ids = time() . $uid;
                        $upd['activation_code'] = $ids;

                        $this->user->update('st_users', $upd, ['id' => $uid]);

                        //$ids=time().$uid;
                        $message = $this->load->view(
                            'email/employee_activtion_link',
                            [
                                'username' => $email,
                                'password' => $password,
                                'business_name' => $this->session->userdata(
                                    'business_name'
                                ),
                                'mname' => $this->session->userdata(
                                    'sty_fname'
                                ),
                                'name' => ucwords(
                                    $this->input->post('first_name')
                                ),
                                'button' => 'Employee Registration',
                                'msg' =>
                                    'This message has been sent to you by StyleTimer. Click on the link below to Register your account.',
                            ],
                            true
                        );
                        $mail = emailsend(
                            $email,
                            'styletimer - Mitarbeiterregistrierung',
                            $message,
                            'styletimer'
                        );

                        if (!empty($assigned_service)) {
                            $i = 0;
                            $assigned_service = explode(',', $assigned_service);
                            $assigned_subcat = explode(',', $all_subcat);
                            foreach ($assigned_service as $service) {
                                $assin = [];
                                $assin['user_id'] = $uid;
                                $assin['service_id'] = $service;
                                $assin['subcat_id'] = $assigned_subcat[$i];
                                // $assin['created_by']=$this->session->userdata('st_userid');
                                //$assin['created_on']=date('Y-m-d H:i:s');
                                $this->user->insert(
                                    'st_service_employee_relation',
                                    $assin
                                );
                                $i++;
                            }
                        }
                        /*********************************************************Start Time**********************************************************************/
                        $days_array = [
                            'monday',
                            'tuesday',
                            'wednesday',
                            'thursday',
                            'friday',
                            'saturday',
                            'sunday',
                        ];
                        $postdays = $_POST['days'];
                        //$i=0;
                        foreach ($days_array as $day) {
                            if (in_array($day, $postdays)) {
                                $insertArr = [];
                                $insertArr['user_id'] = $uid;
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'open';
                                $insertArr['starttime'] =
                                    $_POST[$day . '_start'];
                                $insertArr['endtime'] = $_POST[$day . '_end'];
                                $insertArr['starttime_two'] = isset(
                                    $_POST[$day . '_start_two']
                                )
                                    ? $_POST[$day_two . '_start_two']
                                    : '';
                                $insertArr['endtime_two'] = isset(
                                    $_POST[$day . '_end_two']
                                )
                                    ? $_POST[$day . '_end_two']
                                    : '';
                                $insertArr['created_on'] = date('Y-m-d H:i:s');
                                $insertArr[
                                    'created_by'
                                ] = $this->session->userdata('st_userid');
                                //$i++;
                            } else {
                                $insertArr = [];
                                $insertArr['user_id'] = $uid;
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'close';
                                //$insertArr['starttime']=$poststart[$i];
                                // $insertArr['endtime']=$postend[$i];
                                $insertArr['created_on'] = date('Y-m-d H:i:s');
                                $insertArr[
                                    'created_by'
                                ] = $this->session->userdata('st_userid');
                            }
                            $this->user->insert('st_availability', $insertArr);
                        }

                        /**********************************************************End time**********************************************************************/

                        /**********************************************************block national holidays**********************************************************************/

                        $mid = $this->session->userdata('st_userid');
                        $getNationalHolidays = $this->user->select(
                            'st_booking',
                            'id,booking_time,booking_endtime',
                            [
                                'merchant_id' => $mid,
                                'national_holiday' => 1,
                                'blocked_perent' => 0,
                                'booking_type' => 'self',
                            ]
                        );

                        $getNationalHolidays1 = $this->user->select(
                            'st_booking',
                            'id,booking_time,booking_endtime',
                            [
                                'merchant_id' => $mid,
                                'national_holiday' => 0,
                                'close_for' => 0,
                                'blocked_perent' => 0,
                                'blocked_type' => 'close',
                                'booking_type' => 'self',
                            ]
                        );

                        $getNationalHolidays2 = $this->user->select(
                            'st_booking',
                            'id,booking_time,booking_endtime',
                            [
                                'merchant_id' => $mid,
                                'national_holiday' => 0,
                                'block_for' => 1,
                                'blocked_perent' => 0,
                                'booking_type' => 'self',
                            ]
                        );

                        array_push($getNationalHolidays, ...$getNationalHolidays1, ...$getNationalHolidays2);

                        if (empty($getNationalHolidays)) {
                            $date = date('Y-m-d');
                            $edate = strtotime(
                                date('Y-m-d', strtotime($date)) . ' +6 month'
                            );
                            $edate = date('Y-m-d', $edate);
                            //echo $date;
                            $this->db->group_by('date');
                            $getSixmonthHoliday = $this->user->select(
                                'st_national_holidays',
                                '*',
                                ['date >=' => $date, 'date <' => $edate]
                            );

                            if (!empty($getSixmonthHoliday)) {
                                foreach ($getSixmonthHoliday as $holiday) {
                                    $inserArray = [];
                                    $inserArray['merchant_id'] = $mid;
                                    $inserArray['employee_id'] = $uid;
                                    $inserArray['notes'] = isset($holiday->name)
                                        ? $holiday->name
                                        : '';
                                    $inserArray['booking_time'] =
                                        $holiday->date . ' 00:00:00';
                                    $inserArray['booking_endtime'] =
                                        $holiday->date . ' 23:00:00';
                                    $inserArray['booking_type'] = 'self';
                                    $inserArray['blocked'] = 0;
                                    $inserArray['blocked_perent'] = 0;
                                    $inserArray['blocked_type'] = 'close';
                                    $inserArray['national_holiday'] = 1;

                                    $res = $this->user->insert(
                                        'st_booking',
                                        $inserArray
                                    );
                                    $this->user->update(
                                        'st_booking',
                                        ['blocked' => $res],
                                        ['id' => $res]
                                    );
                                }
                            }
                        } else {
                            $added = [];
                            foreach ($getNationalHolidays as $holiday) {
                                if (in_array($holiday->id, $added)) {
                                    continue;
                                }
                                $added[] = $holiday->id;
                                $inserArray = [];
                                $inserArray['merchant_id'] = $mid;
                                $inserArray['employee_id'] = $uid;
                                $inserArray['notes'] = isset($holiday->notes)
                                    ? $holiday->notes
                                    : '';
                                $inserArray['booking_time'] =
                                    $holiday->booking_time;
                                $inserArray['booking_endtime'] =
                                    $holiday->booking_endtime;
                                $inserArray['booking_type'] = 'self';
                                $inserArray['blocked'] = $holiday->id;
                                $inserArray['blocked_type'] = 'close';
                                $inserArray['blocked_perent'] = $holiday->id;
                                $inserArray['national_holiday'] = 1;

                                $res = $this->user->insert(
                                    'st_booking',
                                    $inserArray
                                );
                            }
                        }
                        /**********************************************************block national holidays end**********************************************************************/

                        $this->session->set_flashdata(
                            'success',
                            $this->lang->line('succss_add_emloyee')
                        );
                        redirect(base_url('merchant/employee_listing'));
                        //redirect(base_url('merchant/dashboard_addemployee'));
                    } else {
                        $this->session->set_flashdata(
                            'error',
                            'There is some technical error.'
                        );
                        redirect(base_url('merchant/dashboard_addemployee'));
                    }
                }
            }
        }
        $this->data['Empdetail'] = [];
        if ($id != '') {
            $field =
                'id,first_name,last_name,address,email,status,calender_color,mobile,profile_pic,online_booking,commission,allow_emp_to_delete_cancel_booking,mail_by_user,mail_by_merchant';
            $this->data['Empdetail'] = $this->user->select_row(
                'st_users',
                $field,
                ['id' => url_decode($id)]
            );

            $selctServis =
                'st_service_employee_relation.service_id,st_merchant_category.name,st_merchant_category.id,st_merchant_category.duration,st_merchant_category.price,`st_category`.`category_name`,`st_merchant_category`.`subcategory_id`';

            $where = [
                'st_service_employee_relation.user_id' => url_decode($id),
            ];

            $this->data['services'] = $this->user->join_three_orderby(
                'st_service_employee_relation',
                'st_merchant_category',
                'st_category',
                'service_id',
                'id',
                'subcategory_id',
                'id',
                $where,
                $selctServis
            );
            $this->data['user_available'] = $this->user->select(
                'st_availability',
                'days,type,starttime,endtime,starttime_two,endtime_two',
                ['user_id' => url_decode($id)],
                '',
                'id',
                'ASC'
            );
        } else {
            $this->data['selectedcolor'] = $this->user->select(
                'st_users',
                'id,calender_color',
                ['merchant_id' => $this->session->userdata('st_userid')]
            );
        }
        $this->data['merchant_id'] = $this->session->userdata('st_userid');
        //echo $this->db->last_query()."<pre>"; print_r($this->data); die;
        $this->data['merchant_available'] = $this->user->select(
            'st_availability',
            'days,type,starttime,endtime',
            ['user_id' => $this->session->userdata('st_userid')],
            '',
            'id',
            'ASC'
        );
        //echo '<pre>'; print_r($this->data); die;
        $html = $this->load->view(
            'frontend/marchant/add_employee_popup',
            $this->data,
            true
        );

        echo json_encode(['success' => 1, 'html' => $html]);
    }

    //**** Employee Listing  ****//
    public function employee_listing()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        //$this->data['membership']=$this->user->select_row('st_users','subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status',array('id' =>$this->session->userdata('st_userid')));

        $totalcount = get_employeecount('st_users', [
            'merchant_id' => $this->session->userdata('st_userid'),
            'status !=' => 'deleted',
        ]);
        $total = $totalcount;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : PER_PAGE10; //PER_PAGE10
        $url = 'merchant/employee_listing';
        $segment = 3;
        $page = mypaging($url, $total, $segment, $limit);
        //$this->db->order_by('r.id','desc');
        $this->data['employees'] = $this->user->select(
            'st_users',
            'id,first_name,last_name,email,calender_color,mobile,profile_pic,online_booking,status',
            [
                'merchant_id' => $this->session->userdata('st_userid'),
                'status !=' => 'deleted',
            ],
            '',
            'id',
            'desc',
            $page['per_page'],
            $page['offset']
        );

        // echo '<pre>'; print_r($this->data); die;
        $this->load->view('frontend/marchant/employee_listing', $this->data);
    }

    //**** Employee Shift ****//
    public function employee_shift($id = '')
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        $this->data['merchant_available'] = $this->user->select(
            'st_availability',
            'days,type,starttime,endtime',
            ['user_id' => $this->session->userdata('st_userid')],
            '',
            'id',
            'ASC'
        );
        $this->data['employees_list'] = $this->user->select(
            'st_users',
            'id,first_name,last_name',
            [
                'merchant_id' => $this->session->userdata('st_userid'),
                'status !=' => 'deleted',
            ],
            '',
            'id',
            'desc'
        );
        if ($id != '') {
            $this->data['employees'] = $this->user->select(
                'st_users',
                'id,first_name,last_name,email,profile_pic',
                [
                    'merchant_id' => $this->session->userdata('st_userid'),
                    'status !=' => 'deleted',
                    'id' => url_decode($id),
                ],
                '',
                'id',
                'desc'
            );
        } else {
            $this->data['employees'] = $this->user->select(
                'st_users',
                'id,first_name,last_name,email,profile_pic',
                [
                    'merchant_id' => $this->session->userdata('st_userid'),
                    'status !=' => 'deleted',
                ],
                '',
                'id',
                'desc'
            );
        }
        $this->data['merchant_id'] = $this->session->userdata('st_userid');
        $this->load->view('frontend/marchant/employee_shift', $this->data);
    }

    public function closed_date()
    {
        $this->db->order_by('st_booking.booking_time', 'asc');
        // $this->data['closetimes'] = $this->user->join_two('st_booking','st_users','employee_id','id',
        // array('st_booking.merchant_id'=>$this->session->userdata('st_userid')
        // ,'blocked_type'=>'close','booking_type'=>'self','blocked_perent'=>0,
        // 'st_booking.status'=>'confirmed'),
        // 'st_booking.id,st_booking.employee_id,st_booking.booking_time,
        // st_booking.booking_endtime,st_booking.notes,st_booking.close_for,
        // st_users.first_name,st_users.last_name');
        $curdate = date('Y-m-d') . ' 00:00:00';
        $this->data['closetimes'] = $this->user->join_two(
            'st_booking',
            'st_users',
            'employee_id',
            'id',
            ['st_booking.merchant_id' => $this->session->userdata('st_userid'), 'st_booking.blocked_perent' => 0, 'booking_type'=>'self', 'blocked_type'=>'close', 'st_booking.status'=>'confirmed', 'st_booking.employee_id !=' => -1, 'st_booking.booking_time >= ' => $curdate],
            'st_booking.id,st_booking.employee_id,st_booking.booking_time,
		st_booking.booking_endtime,st_booking.notes,st_booking.close_for,
		st_users.first_name,st_users.last_name'
        );

        //echo $this->db->last_query();die;
        //echo '<pre>'; print_r($this->data['closetimes']); die;
        $this->load->view('frontend/marchant/closed_date', $this->data);
    }

    public function get_close_timeform($id = '')
    {
        $this->data['formtitle'] = $this->lang->line('new_close_date');
        $edit = 0;
        if (!empty($id)) {
            $this->data['closetime'] = $this->user->select_row(
                'st_booking',
                'id,count(id) as totalcount,blocked,booking_time,booking_endtime,notes,employee_id',
                [
                    'merchant_id' => $this->session->userdata('st_userid'),
                    'blocked' => $id,
                ]
            );

            $this->data['formtitle'] = $this->lang->line('edit_new_close_date');
            $edit = 1;
        }
        $this->data['employees'] = $this->user->select(
            'st_users',
            'id,first_name,last_name,email,mobile,profile_pic,online_booking,status',
            [
                'merchant_id' => $this->session->userdata('st_userid'),
                'status !=' => 'deleted',
            ]
        );
        //echo '<pre>'; print_r($this->data); die;
        $html = $this->load->view(
            'frontend/marchant/profile/closetime_form',
            $this->data,
            true
        );

        echo json_encode(['success' => 1, 'html' => $html, 'edit' => $edit]);
    }

    //**** Get availability ****//
    public function getavailability()
    {
        $salon_avl = $this->user->select_row(
            'st_availability',
            'starttime,endtime',
            [
                'user_id' => $_POST['merchant_id'],
                'days' => $_POST['day_nm'],
                'type' => 'open',
            ]
        );
        if (!empty($salon_avl)) {
            $start = '00:00';
            $end = '23:00';

            $tStart = strtotime($salon_avl->starttime);
            $tEnd = strtotime($salon_avl->endtime);
            $tNow = $tStart;
            $str = $end = $str1 = $end1 = '';
            $i = 1;
            while ($tNow <= $tEnd) {
                $str .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_start' .
                    $i .
                    '" name="startone_time" class="shiftone" data-val="" value="' .
                    date('H:i:s', $tNow) .
                    '">
                        <label for="id_start' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                $end .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_end' .
                    $i .
                    '" name="endone_time" class="shiftone" data-val="" value="' .
                    date('H:i:s', $tNow) .
                    '">
                        <label for="id_end' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                /* $str1.='<li class="radiobox-image">
                        <input type="radio" id="id_starttwo'.$i.'" name="starttwo_time" class="shifttwo" data-val="" value="'.date("H:i:s",$tNow).'">
                        <label for="id_starttwo'.$i.'">
                        '.date("H:i",$tNow).'                   
                      </label>
                  </li>';
                  $end1.='<li class="radiobox-image">
                        <input type="radio" id="id_endtwo'.$i.'" name="endtwo_time" class="shifttwo" data-val="" value="'.date("H:i:s",$tNow).'">
                        <label for="id_endtwo'.$i.'">
                        '.date("H:i",$tNow).'                   
                      </label>
                  </li>';*/
                $tNow = strtotime('+60 minutes', $tNow);

                $i++;
            }

            echo json_encode([
                'success' => 1,
                'start_list' => $str,
                'end_list' => $end,
            ]);
        } else {
            echo json_encode([
                'success' => 0,
                'time_list' => '',
                'message' => 'Salon is not open on ' . $_POST['day_nm'],
            ]);
        }
    }

    ///**** updated availaility ****//
    public function getupdatedavailability()
    {
        $salon_avl = $this->user->select_row(
            'st_availability',
            'starttime,endtime,starttime_two,endtime_two',
            [
                'user_id' => $_POST['merchant_id'],
                'days' => $_POST['day_nm'],
                'type' => 'open',
            ]
        );

        $emp_info = $this->user->select_row(
            'st_availability',
            'starttime,endtime,starttime_two,endtime_two',
            ['id' => $_POST['table_id'], 'days' => $_POST['day_nm']]
        );

        if (!empty($salon_avl)) {
            $start = '00:00';
            $end = '23:00';

            $tStart = strtotime($salon_avl->starttime);
            $tEnd = strtotime($salon_avl->endtime);
            $tNow = $tStart;
            $html = $str = $end = $str1 = $end1 = '';
            $i = 1;
            while ($tNow <= $tEnd) {
                $srt_dt = $end_dt = $srttwo_dt = $endtwo_dt = '';

                if (
                    !empty($emp_info->starttime) &&
                    $emp_info->starttime == date('H:i:s', $tNow)
                ) {
                    $srt_dt = 'checked="checked"';
                }

                if (
                    !empty($emp_info->endtime) &&
                    $emp_info->endtime == date('H:i:s', $tNow)
                ) {
                    $end_dt = 'checked="checked"';
                }

                if (
                    !empty($emp_info->starttime_two) &&
                    $emp_info->starttime_two == date('H:i:s', $tNow)
                ) {
                    $srttwo_dt = 'checked="checked"';
                }

                if (
                    !empty($emp_info->endtime_two) &&
                    $emp_info->endtime_two == date('H:i:s', $tNow)
                ) {
                    $endtwo_dt = 'checked="checked"';
                }

                $str .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_start' .
                    $i .
                    '" name="shift_starttime1" class="shiftone shiftstartChg" data-text="' .
                    date('H:i', $tNow) .
                    '" data-id="1" value="' .
                    date('H:i:s', $tNow) .
                    '" ' .
                    $srt_dt .
                    '>
                        <label for="id_start' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                $end .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_end' .
                    $i .
                    '" name="shift_endtime1" class="shiftone shiftendChg" data-text="' .
                    date('H:i', $tNow) .
                    '" data-id="1" value="' .
                    date('H:i:s', $tNow) .
                    '" ' .
                    $end_dt .
                    '>
                        <label for="id_end' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';

                $str1 .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_starttwos' .
                    $i .
                    '" name="shift_starttime2" class="shifttwo shiftstartChg" data-val="" data-text="' .
                    date('H:i', $tNow) .
                    '" data-id="2" value="' .
                    date('H:i:s', $tNow) .
                    '" ' .
                    $srttwo_dt .
                    '>
                        <label for="id_starttwos' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                $end1 .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_endtwos' .
                    $i .
                    '" name="shift_endtime2" class="shifttwo shiftendChg" data-val="" data-text="' .
                    date('H:i', $tNow) .
                    '" data-id="2" value="' .
                    date('H:i:s', $tNow) .
                    '" ' .
                    $endtwo_dt .
                    '>
                        <label for="id_endtwos' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';

                $tNow = strtotime('+30 minutes', $tNow);

                $i++;
            }

            if (!empty($emp_info->starttime) && !empty($emp_info->endtime)) {
                $st = substr($emp_info->starttime, 0, 5);
                $ed = substr($emp_info->endtime, 0, 5);
                $html .=
                    '<div class="row align-items-center" id="addmore_shift_div1">
                 <div class="col-5 col-sm-5 pr-0">
                   <div class="form-group vmb-20">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new show">
                                <span class="label label_add_top">' .
                    $this->lang->line('Start_Time') .
                    '</span>
                                  <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="cat_btn_start" aria-expanded="false">' .
                    $st .
                    '</button>                                
                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
					   x-placement="bottom-start" id="start_one" style="max-height: 320px !important;overflow: auto;">' .
                    $str .
                    '</ul>
                              

                         </div>
                         <input id="shiftStart_1" type="hidden" data-id="1" name="shift_starttime[]" value="' .
                    $emp_info->starttime .
                    '">
                          <label class="error" id="shift_stime_err_1"></label>
                     </div>
                 </div>
                 <div class="col-5 col-sm-5 pr-0">
                   <div style="margin-bottom:20px" class="form-group">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new show">
                                <span class="label label_add_top">' .
                    $this->lang->line('End_Time') .
                    '</span>
                              <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="cat_btn_end" aria-expanded="false">' .
                    $ed .
                    '</button>

                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
					   x-placement="bottom-start" id="end_one" style="max-height: 320px !important;overflow: auto;">' .
                    $end .
                    '</ul>
						</div>
						<input id="shiftEnd_1" type="hidden" data-id="1" name="shift_endtime[]" value="' .
                    $emp_info->endtime .
                    '">
						 <label class="error" id="shift_etime_err_1"></label>
                     </div>
                 </div>
                 <div class="col-2 col-sm-2 pr-0">
                   <a data-id="1" href="javascript:void(0);" class="display-b mb-40 remove_shift" id="remove_shift">
                      <img src="' .
                    base_url('assets/frontend/images/remove.svg') .
                    '" class="width24v">
                    </a>
                 </div>
               </div>';
            }
            if (
                !empty($emp_info->starttime_two) &&
                !empty($emp_info->endtime_two)
            ) {
                $st2 = substr($emp_info->starttime_two, 0, 5);
                $ed2 = substr($emp_info->endtime_two, 0, 5);
                $html .=
                    '<div class="row align-items-center" id="addmore_shift_div2">
                 <div class="col-5 col-sm-5 pr-0">
                   <div class="form-group vmb-20">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new show">
                                <span class="label label_add_top">' .
                    $this->lang->line('Start_Time') .
                    '</span>
                                <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="btn_start_two" aria-expanded="false">' .
                    $st2 .
                    '</button>
                               
                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
					   x-placement="bottom-start" id="start_shifttwo" style="max-height: 320px !important;overflow: auto;">' .
                    $str1 .
                    '</ul>

                         </div>
                         <input id="shiftStart_2" type="hidden" data-id="2" name="shift_starttime[]" value="' .
                    $emp_info->starttime_two .
                    '">
                          <label class="error" id="shift_stime_err_2"></label>
                     </div>
                 </div>
                 <div class="col-5 col-sm-5 pr-0">
                   <div class="form-group vmb-20">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new show">
                                <span class="label label_add_top">' .
                    $this->lang->line('End_Time') .
                    '</span>
                                <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="btn_end_two" aria-expanded="false">' .
                    $ed2 .
                    '</button>
                             
                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
					   x-placement="bottom-start" id="end_shifttwo" style="max-height: 320px !important;overflow: auto;">' .
                    $end1 .
                    '</ul>
						</div>
						<input id="shiftEnd_2" type="hidden" data-id="2" name="shift_endtime[]" value="' .
                    $emp_info->endtime_two .
                    '">
						 <label class="error" id="shift_etime_err_2"></label>
                     </div>
                 </div>
                 <div class="col-2 col-sm-2 pr-0">
                   <a data-id="2" href="javascript:void(0);" class="display-b mb-40 remove_shift" id="remove_shift">
                      <img src="' .
                    base_url('assets/frontend/images/remove.svg') .
                    '" class="width24v">
                    </a>
                 </div>
               </div>';
            }

            echo json_encode(['success' => 1, 'html' => $html]);
        } else {
            echo json_encode([
                'success' => 0,
                'time_list' => '',
                'message' => 'salon is not open on ' . $_POST['day_nm'],
            ]);
        }
    }

    public function getupdatedavailability_old()
    {
        $salon_avl = $this->user->select_row(
            'st_availability',
            'starttime,endtime,starttime_two,endtime_two',
            [
                'user_id' => $_POST['merchant_id'],
                'days' => $_POST['day_nm'],
                'type' => 'open',
            ]
        );

        $emp_info = $this->user->select_row(
            'st_availability',
            'starttime,endtime,starttime_two,endtime_two',
            ['id' => $_POST['table_id'], 'days' => $_POST['day_nm']]
        );

        if (!empty($salon_avl)) {
            $start = '00:00';
            $end = '23:00';

            $tStart = strtotime($salon_avl->starttime);
            $tEnd = strtotime($salon_avl->endtime);
            $tNow = $tStart;
            $html = $str = $end = $str1 = $end1 = '';
            $i = 1;
            while ($tNow <= $tEnd) {
                if (
                    !empty($emp_info->starttime) &&
                    $emp_info->starttime == date('H:i:s', $tNow)
                ) {
                    $srt_dt = 'checked="checked"';
                } else {
                    $srt_dt = '';
                }

                if (
                    !empty($emp_info->endtime) &&
                    $emp_info->endtime == date('H:i:s', $tNow)
                ) {
                    $end_dt = 'checked="checked"';
                } else {
                    $end_dt = '';
                }

                if (
                    !empty($emp_info->starttime_two) &&
                    $emp_info->starttime_two == date('H:i:s', $tNow)
                ) {
                    $srttwo_dt = 'checked="checked"';
                } else {
                    $srttwo_dt = '';
                }

                if (
                    !empty($emp_info->endtime_two) &&
                    $emp_info->endtime_two == date('H:i:s', $tNow)
                ) {
                    $endtwo_dt = 'checked="checked"';
                } else {
                    $endtwo_dt = '';
                }

                $str .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_start' .
                    $i .
                    '" name="startone_time" class="shiftone" data-val="" data-text="' .
                    date('H:i', $tNow) .
                    '" value="' .
                    date('H:i:s', $tNow) .
                    '" ' .
                    $srt_dt .
                    '>
                        <label for="id_start' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                $end .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_end' .
                    $i .
                    '" name="endone_time" class="shiftone" data-val="" data-text="' .
                    date('H:i', $tNow) .
                    '" value="' .
                    date('H:i:s', $tNow) .
                    '" ' .
                    $end_dt .
                    '>
                        <label for="id_end' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';

                $str1 .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_starttwo' .
                    $i .
                    '" name="starttwo_time" class="shifttwo" data-val="" data-text="' .
                    date('H:i', $tNow) .
                    '" value="' .
                    date('H:i:s', $tNow) .
                    '" ' .
                    $srttwo_dt .
                    '>
                        <label for="id_starttwo' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                $end1 .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_endtwo' .
                    $i .
                    '" name="endtwo_time" class="shifttwo" data-val="" data-text="' .
                    date('H:i', $tNow) .
                    '" value="' .
                    date('H:i:s', $tNow) .
                    '" ' .
                    $endtwo_dt .
                    '>
                        <label for="id_endtwo' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';

                $tNow = strtotime('+60 minutes', $tNow);

                $i++;
            }

            if (
                !empty($emp_info->starttime_two) &&
                !empty($emp_info->endtime_two)
            ) {
                $html .=
                    '<div class="row align-items-center" id="addmore_shift_div">
                 <div class="col-5 col-sm-5 pr-0">
                   <div class="form-group vmb-40">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new show">
                                <span class="label ">' .
                    $this->lang->line('Start_Time') .
                    '</span>
                                <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="btn_start_two" aria-expanded="false"></button>
                               
                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
					   x-placement="bottom-start" id="start_shifttwo" style="max-height: none;height: auto !important;overflow-x: auto;">' .
                    $str1 .
                    '</ul>

                         </div>
                          <label class="error" id="starttwo_time_err"></label>
                     </div>
                 </div>
                 <div class="col-5 col-sm-5 pr-0">
                   <div class="form-group vmb-40">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new show">
                                <span class="label ">' .
                    $this->lang->line('End_Time') .
                    '</span>
                                <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="btn_end_two" aria-expanded="false"></button>
                             
                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
					   x-placement="bottom-start" id="end_shifttwo" style="max-height: none;height: auto !important;overflow-x: auto;">' .
                    $end1 .
                    '</ul>
						</div>
						 <label class="error" id="endtwo_time_err"></label>
                     </div>
                 </div>
                 <div class="col-2 col-sm-2 pr-0">
                   <a href="javascript:void(0);" class="display-b mb-40" id="remove_shift">
                      <img src="' .
                    base_url('assets/frontend/images/remove.svg') .
                    '" class="width24v">
                    </a>
                 </div>
               </div>';
            }

            echo json_encode([
                'success' => 1,
                'start_list' => $str,
                'end_list' => $end,
                'html' => $html,
            ]);
        } else {
            echo json_encode([
                'success' => 0,
                'time_list' => '',
                'message' => 'salon is not open on ' . $_POST['day_nm'],
            ]);
        }
    }

    //**** Add More shift ****//
    public function addmoreshift()
    {
        $salon_avl = $this->user->select_row(
            'st_availability',
            'starttime,endtime',
            [
                'user_id' => $_POST['merchant_id'],
                'days' => $_POST['day_nm'],
                'type' => 'open',
            ]
        );
        if (!empty($salon_avl)) {
            $start = '00:00';
            $end = '23:00';

            $tStart = strtotime($salon_avl->starttime);
            $tEnd = strtotime($salon_avl->endtime);
            $tNow = $tStart;
            $end1 = $str1 = $html = '';
            $i = $j = 1;

            while ($tNow <= $tEnd) {
                $str1 .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_' .
                    $_POST['count'] .
                    'starttwo' .
                    $i .
                    '" name="shift_starttime' .
                    $_POST['count'] .
                    '" data-id="' .
                    $_POST['count'] .
                    '" class="shifttwo shiftstartChg" data-val="" value="' .
                    date('H:i:s', $tNow) .
                    '">
                        <label for="id_' .
                    $_POST['count'] .
                    'starttwo' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                $end1 .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_' .
                    $_POST['count'] .
                    'endtwo' .
                    $i .
                    '" name="shift_endtime' .
                    $_POST['count'] .
                    '" data-id="' .
                    $_POST['count'] .
                    '" class="shifttwo shiftendChg" data-val="" value="' .
                    date('H:i:s', $tNow) .
                    '">
                        <label for="id_' .
                    $_POST['count'] .
                    'endtwo' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                $tNow = strtotime('+30 minutes', $tNow);
                $i++;
            }

            $html .=
                '<div class="row align-items-center" id="addmore_shift_div' .
                $_POST['count'] .
                '">
                 <div class="col-5 col-sm-5 pr-0">
                   <div class="form-group vmb-40">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new">
                                <span class="label label_add_top">' .
                $this->lang->line('Start_Time') .
                '</span>
                                <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="btn_start_two" aria-expanded="false">'.date('H:i', $tStart).'</button>
                                
                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
					   x-placement="bottom-start" id="start_shifttwo" style="max-height: 320px !important;overflow: auto;">' .
                $str1 .
                '</ul>

                         </div>
                         <input id="shiftStart_' .
                $_POST['count'] .
                '" type="hidden" data-id="' .
                $_POST['count'] .
                '" name="shift_starttime[]" value="'.date('H:i:s', $tStart).'">
                         <label class="error" id="shift_stime_err_' .
                $_POST['count'] .
                '"></label>
                     </div>
                 </div>
                 <div class="col-5 col-sm-5 pr-0">
                   <div class="form-group vmb-40">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new">
                                <span class="label label_add_top">' .
                $this->lang->line('End_Time') .
                '</span>
                                <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="btn_end_two" aria-expanded="false">'.date('H:i', $tEnd).'</button>
                                
                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll"
					    x-placement="bottom-start" id="end_shifttwo" style="max-height: 320px !important;overflow: auto;">' .
                $end1 .
                '</ul>
						</div>
						<input id="shiftEnd_' .
                $_POST['count'] .
                '" type="hidden" data-id="' .
                $_POST['count'] .
                '" name="shift_endtime[]" value="'.date('H:i:s', $tEnd).'">
						<label class="error" id="shift_etime_err_' .
                $_POST['count'] .
                '"></label>
                     </div>
                 </div>
                 <div class="col-2 col-sm-2 pr-0">
                   <a class="display-b mb-40 remove_shift" data-id="' .
                $_POST['count'] .
                '" href="javascript:void(0);" id="remove_shift">
                      <img src="' .
                base_url('assets/frontend/images/remove.svg') .
                '" class="width24v">
                    </a>
                 </div>
               </div>';

            echo json_encode(['success' => 1, 'html' => $html]);
        } else {
            echo json_encode(['success' => 0, 'html' => '']);
        }
    }

    public function addmoreshift_old()
    {
        $salon_avl = $this->user->select_row(
            'st_availability',
            'starttime,endtime',
            [
                'user_id' => $_POST['merchant_id'],
                'days' => $_POST['day_nm'],
                'type' => 'open',
            ]
        );
        if (!empty($salon_avl)) {
            $start = '00:00';
            $end = '23:00';

            $tStart = strtotime($salon_avl->starttime);
            $tEnd = strtotime($salon_avl->endtime);
            $tNow = $tStart;
            $end1 = $str1 = $html = '';
            $i = $j = 1;

            while ($tNow <= $tEnd) {
                $str1 .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_starttwo' .
                    $i .
                    '" name="starttwo_time" class="shifttwo" data-val="" value="' .
                    date('H:i:s', $tNow) .
                    '">
                        <label for="id_starttwo' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                $end1 .=
                    '<li class="radiobox-image">
                        <input type="radio" id="id_endtwo' .
                    $i .
                    '" name="endtwo_time" class="shifttwo" data-val="" value="' .
                    date('H:i:s', $tNow) .
                    '">
                        <label for="id_endtwo' .
                    $i .
                    '">
                        ' .
                    date('H:i', $tNow) .
                    '                   
                      </label>
                  </li>';
                $tNow = strtotime('+60 minutes', $tNow);
                $i++;
            }

            $html .=
                '<div class="row align-items-center" id="addmore_shift_div">
                 <div class="col-5 col-sm-5 pr-0">
                   <div class="form-group vmb-40">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new">
                                <span class="label ">' .
                $this->lang->line('Start_Time') .
                '</span>
                                <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="btn_start_two" aria-expanded="false"></button>
                                
                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll"
					    x-placement="bottom-start" id="start_shifttwo" style="max-height: none;height: auto !important;overflow-x: auto;">' .
                $str1 .
                '</ul>

                         </div>
                         <label class="error" id="starttwo_time_err"></label>
                     </div>
                 </div>
                 <div class="col-5 col-sm-5 pr-0">
                   <div class="form-group vmb-40">
                        <div class="btn-group multi_sigle_select inp_select v_inp_new">
                                <span class="label ">' .
                $this->lang->line('End_Time') .
                '</span>
                                <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" id="btn_end_two" aria-expanded="false"></button>
                                
                            <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" 
					   x-placement="bottom-start" id="end_shifttwo" style="max-height: none;height: auto !important;overflow-x: auto;">' .
                $end1 .
                '</ul>
						</div>
						<label class="error" id="endtwo_time_err"></label>
                     </div>
                 </div>
                 <div class="col-2 col-sm-2 pr-0">
                   <a href="javascript:void(0);" class="display-b mb-40" id="remove_shift">
                      <img src="' .
                base_url('assets/frontend/images/remove.svg') .
                '" class="width24v">
                    </a>
                 </div>
               </div>';

            echo json_encode(['success' => 1, 'html' => $html]);
        } else {
            echo json_encode(['success' => 0, 'html' => '']);
        }
    }

    //**** Update availability ****//
    public function updateavailability()
    {
        if (
            !empty($_POST['shift_starttime']) &&
            !empty($_POST['shift_endtime'])
        ) {
            $upd_data['starttime'] = $_POST['shift_starttime'][0];
            $upd_data['endtime'] = $_POST['shift_endtime'][0];
            $upd_data['type'] = 'open';
            $upd_data['starttime_two'] = isset($_POST['shift_starttime'][1])
                ? $_POST['shift_starttime'][1]
                : '';
            $upd_data['endtime_two'] = isset($_POST['shift_endtime'][1])
                ? $_POST['shift_endtime'][1]
                : '';
        } else {
            $upd_data['starttime'] = '';
            $upd_data['endtime'] = '';
            $upd_data['type'] = 'close';
            $upd_data['starttime_two'] = '';
            $upd_data['endtime_two'] = '';
        }
        if (
            $res = $this->user->update('st_availability', $upd_data, [
                'id' => $_POST['tab_id'],
            ])
        ) {
            $html = '';
            if ($upd_data['type'] == 'open') {
                $html .=
                    '<input id="' .
                    $_POST['tab_id'] .
                    '" data-id="' .
                    $_POST['current_day'] .
                    '" type="text" name="" value="' .
                    substr($upd_data['starttime'], 0, 5) .
                    ' - ' .
                    substr($upd_data['endtime'], 0, 5) .
                    '" readonly="" class="calender-chips-new edit_shift">';
                if (
                    $upd_data['starttime_two'] != '' &&
                    $upd_data['endtime_two'] != ''
                ) {
                    $html .=
                        '<input id="' .
                        $_POST['tab_id'] .
                        '" data-id="' .
                        $_POST['current_day'] .
                        '" type="text" name="" value="' .
                        substr($upd_data['starttime_two'], 0, 5) .
                        ' - ' .
                        substr($upd_data['endtime_two'], 0, 5) .
                        '" readonly="" class="calender-chips-new edit_shift">';
                }
            } else {
                $html .=
                    '<a href="javascript:void(0);" id="' .
                    $_POST['tab_id'] .
                    '" data-id="' .
                    $_POST['current_day'] .
                    '" class="mt-1 display-b shift_popup"><img src="' .
                    base_url('assets/frontend/images/add_blue_plus_icon.svg') .
                    '" class="width24v"></a>';
            }
            $this->session->set_flashdata(
                'success',
                'Arbeitszeiten aktualisiert'
            );
            echo json_encode([
                'success' => 1,
                'msg' => 'Arbeitszeiten aktualisiert',
                'html' => $html,
                'id' => $_POST['tab_id'],
            ]);
        } else {
            $this->session->set_flashdata(
                'error',
                'There is some technical error'
            );
            echo json_encode([
                'success' => 0,
                'msg' => 'There is some technical error',
                'html' => '',
            ]);
        }
    }
    public function updateavailability_old()
    {
        $upd_data['starttime'] = $_POST['startone_time'];
        $upd_data['endtime'] = $_POST['endone_time'];
        $upd_data['type'] = 'open';
        $upd_data['starttime_two'] = isset($_POST['starttwo_time'])
            ? $_POST['starttwo_time']
            : '';
        $upd_data['endtime_two'] = isset($_POST['endtwo_time'])
            ? $_POST['endtwo_time']
            : '';
        if (
            $res = $this->user->update('st_availability', $upd_data, [
                'id' => $_POST['tab_id'],
            ])
        ) {
            $html = '';

            $html .=
                '<input id="' .
                $_POST['tab_id'] .
                '" data-id="' .
                $_POST['current_day'] .
                '" type="text" name="" value="' .
                substr($_POST['startone_time'], 0, 5) .
                ' - ' .
                substr($_POST['endone_time'], 0, 5) .
                '" readonly="" class="calender-chips-new edit_shift">';
            if (
                $upd_data['starttime_two'] != '' &&
                $upd_data['endtime_two'] != ''
            ) {
                $html .=
                    '<input id="' .
                    $_POST['tab_id'] .
                    '" data-id="' .
                    $_POST['current_day'] .
                    '" type="text" name="" value="' .
                    substr($_POST['starttwo_time'], 0, 5) .
                    ' - ' .
                    substr($_POST['endtwo_time'], 0, 5) .
                    '" readonly="" class="calender-chips-new edit_shift">';
            }

            echo json_encode([
                'success' => 1,
                'html' => $html,
                'id' => $_POST['tab_id'],
            ]);
        } else {
            echo json_encode(['success' => 0, 'html' => '']);
        }
    }

    //**** Change Employee online Status ****//
    public function employee_onlinestatus()
    {
        $upd_data = ['online_booking' => $_POST['status']];
        $res = $this->user->update('st_users', $upd_data, [
            'id' => url_decode($_POST['tid']),
        ]);
        echo $res;
    }

    //**** Delete Employee ***//
    public function deleteemployee()
    {
        $upd_data = ['status' => 'deleted'];
        $res = $this->user->update('st_users', $upd_data, [
            'id' => url_decode($_POST['tid']),
        ]);
        if ($res) {
            echo json_encode([
                'success' => 1,
                'ids' => url_decode($_POST['tid']),
            ]);
        } else {
            echo json_encode([
                'success' => 0,
                'ids' => url_decode($_POST['tid']),
            ]);
        }
    }

    public function deletecustomer()
    {

    }

    //**** Add Service from merchant ****//
    public function add_service($id = '')
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        if (isset($_POST['name'])) {
            //echo '<pre>'; print_r($_POST); die;
            if (!empty($_POST['id'])) {
                extract($_POST);
                //echo '<pre>'; print_r($_POST); die;
                $id = url_decode($id);

                $insert = [];
                $insert['name'] = $name;
                //$insert['category_id']    = $category;
                $insert['filtercat_id'] = $filtercategory;
                $insert['subcategory_id'] = $sub_category;
                $insert['price_start_option'] = $price_start_option;

                $catres = $this->user->select_row(
                    'st_category',
                    'id,parent_id',
                    ['id' => $_POST['sub_category']]
                );

                $category = 0;
                if (!empty($catres->parent_id)) {
                    $insert['category_id'] = $catres->parent_id;
                    $category = $catres->parent_id;
                }

                if (!empty($tax) && $tax != 'notax') {
                    $tax_id = url_decode($tax);
                } else {
                    $tax_id = 0;
                }

                $insert['tax_id'] = $tax_id;

                if (!empty($proccess_time)) {
                    $insert['type'] = 1;
                    $insert['setuptime'] = $setuptime;
                    $insert['processtime'] = $processtime;
                    $insert['finishtime'] = $finishtime;

                    $drsin = $setuptime + $processtime + $finishtime;

                    $insert['duration'] = $drsin;
                } else {
                    $insert['type'] = 0;
                    $insert['duration'] = $duration;
                    $insert['buffer_time'] = !empty($buffer_time)
                        ? $buffer_time
                        : 0;
                }
                $insert['service_detail'] = $detail;

                if (!empty($check_online_option)) {
                    $insert['online'] = '1';
                } else {
                    $insert['online'] = 0;
                }

                $price = str_replace(',', '.', (string) $price);
                if (isset($discount_price) && $discount_price != '') {
                    $discount_price = str_replace(
                        ',',
                        '.',
                        (string) $discount_price
                    );
                    $insert['discount_price'] = $discount_price;
                    $insert['discount_percent'] = get_discount_percent(
                        $price,
                        $discount_price
                    );
                } else {
                    $insert['discount_percent'] = '0.00';
                    $insert['discount_price'] = 0;
                }
                $insert['price'] = $price;
                $insert['ip_address'] = $this->input->ip_address();

                // echo '<pre>'; print_r($insert); die;

                $res_id = $this->user->update('st_merchant_category', $insert, [
                    'id' => $id,
                ]);
                if ($res_id) {
                    $old_users = [];
                    if (!empty($old_assined_user)) {
                        $old_users = explode(',', $old_assined_user);
                    }
                    if (!empty($assigned_users)) {
                        foreach ($assigned_users as $uid) {
                            $assin = [];
                            $assin['user_id'] = $uid;
                            $assin['service_id'] = $id;
                            $assin['subcat_id'] = $sub_category;
                            if (in_array($uid, $old_users)) {
                                // echo $key.'=='.array_search($uid, $old_users); die;
                                if (
                                    ($key = array_search($uid, $old_users)) !==
                                    false
                                ) {
                                    unset($old_users[$key]);
                                }
                                $this->user->update(
                                    'st_service_employee_relation',
                                    $assin,
                                    ['service_id' => $id, 'user_id' => $uid]
                                );
                            } else {
                                $res_id = $this->user->insert(
                                    'st_service_employee_relation',
                                    $assin
                                );
                            }
                        }
                    }
                    if (!empty($old_users)) {
                        $where =
                            'service_id=' .
                            $id .
                            ' AND user_id IN(' .
                            implode(',', $old_users) .
                            ')';
                        $this->db->delete(
                            'st_service_employee_relation',
                            $where
                        );
                    }
                    /************************************* Insert More service section *****************************************/
                    //$insertAssin=array();
                    // $insertOfferVailablity=array();

                    if (
                        !empty($subPrice) &&
                        !empty($subDuration) &&
                        !empty($subBuffer_time)
                    ) {
                        $j = 0;
                        $kl = 1;
                        foreach ($subPrice as $prc) {
                            $arrins = [];
                            if (!empty($subService[$j])) {
                                $arrins['name'] = $subService[$j];
                            } else {
                                $arrins['name'] = '';
                            }

                            $arrins['category_id'] = $category;
                            $arrins['subcategory_id'] = $sub_category;
                            $arrins['filtercat_id'] = $filtercategory;

                            $subPrice[$j] = str_replace(
                                ',',
                                '.',
                                (string) $subPrice[$j]
                            );

                            if (!empty($subServiceId[$j])) {
                                $arrins['price_start_option'] =
                                    $subprice_start_option1[$subServiceId[$j]];
                            } else {
                                $arrins['price_start_option'] =
                                    $subprice_start_option[$kl];
                                $kl++;
                            }
                            $arrins['price'] = $subPrice[$j];
                            $arrins['service_detail'] = $detail;
                            $arrins['tax_id'] = $tax_id;

                            if (!empty($subonline[$j])) {
                                $arrins['online'] = '1';
                            } else {
                                $arrins['online'] = '0';
                            }

                            if (!empty($subproccess_time[$j])) {
                                $arrins['type'] = 1;
                                $arrins['setuptime'] = $subsetuptime[$j];
                                $arrins['processtime'] = $subprocesstime[$j];
                                $arrins['finishtime'] = $subfinishtime[$j];
                                $drsin =
                                    $subsetuptime[$j] +
                                    $subprocesstime[$j] +
                                    $subfinishtime[$j];
                                $arrins['duration'] = $drsin;
                            } else {
                                $arrins['type'] = 0;
                                $arrins['duration'] = $subDuration[$j];
                                $arrins['buffer_time'] =
                                    $subBuffer_time[$j] != ''
                                        ? $subBuffer_time[$j]
                                        : 0;
                            }

                            if ($subDiscount_price[$j] != '') {
                                $subDiscount_price[$j] = str_replace(
                                    ',',
                                    '.',
                                    (string) $subDiscount_price[$j]
                                );
                                $arrins['discount_price'] =
                                    $subDiscount_price[$j];
                                $arrins[
                                    'discount_percent'
                                ] = get_discount_percent(
                                    $subPrice[$j],
                                    $subDiscount_price[$j]
                                );
                            } else {
                                $insert['discount_percent'] = '0.00';
                                $insert['discount_price'] = 0;
                            }
                            // $arrins['discount_price']=$subDiscount_price[$j];
                            $arrins['parent_service_id'] = $id;
                            if (!empty($subServiceId[$j])) {
                                $this->user->update(
                                    'st_merchant_category',
                                    $arrins,
                                    ['id' => $subServiceId[$j]]
                                );
                                $subRes_id = $subServiceId[$j];
                                $newService = '';
                            } else {
                                $subRes_id = $this->user->insert(
                                    'st_merchant_category',
                                    $arrins
                                );
                                $newService = 'yes';
                            }
                            if ($subRes_id) {
                                //echo 'new'.$newService.'<br/>';

                                $old_users = [];
                                if (
                                    empty($newService) &&
                                    !empty($old_assined_user)
                                ) {
                                    $old_users = explode(
                                        ',',
                                        $old_assined_user
                                    );
                                }

                                if (!empty($assigned_users)) {
                                    foreach ($assigned_users as $uid) {
                                        $assin = [];
                                        $assin['user_id'] = $uid;
                                        $assin['service_id'] = $subRes_id;
                                        $assin['subcat_id'] = $sub_category;
                                        if (in_array($uid, $old_users)) {
                                            if (
                                                ($key = array_search(
                                                    $uid,
                                                    $old_users
                                                )) !== false
                                            ) {
                                                unset($old_users[$key]);
                                            }

                                            $this->user->update(
                                                'st_service_employee_relation',
                                                $assin,
                                                [
                                                    'service_id' => $subRes_id,
                                                    'user_id' => $uid,
                                                ]
                                            );
                                        } else {
                                            $res_id = $this->user->insert(
                                                'st_service_employee_relation',
                                                $assin
                                            );
                                        }
                                    }
                                }

                                if (!empty($old_users)) {
                                    $where =
                                        'service_id=' .
                                        $subRes_id .
                                        ' AND user_id IN(' .
                                        implode(',', $old_users) .
                                        ')';
                                    $this->db->delete(
                                        'st_service_employee_relation',
                                        $where
                                    );
                                }

                                if (isset($subDiscount_price[$j])) {
                                    $days_array = [
                                        'monday',
                                        'tuesday',
                                        'wednesday',
                                        'thursday',
                                        'friday',
                                        'saturday',
                                        'sunday',
                                    ];
                                    $i = 0;

                                    foreach ($days_array as $day) {
                                        if (in_array($day, $postdays)) {
                                            $insertArr = [];
                                            $insertArr['days'] = $day;
                                            $insertArr['type'] = 'open';
                                            $insertArr['starttime'] =
                                                $_POST[$day . '_start'];
                                            $insertArr['endtime'] =
                                                $_POST[$day . '_end'];
                                        } else {
                                            $insertArr = [];
                                            $insertArr['days'] = $day;
                                            $insertArr['type'] = 'close';
                                        }

                                        $service = $this->user->select_row(
                                            'st_offer_availability',
                                            'id',
                                            [
                                                'service_id' => $subRes_id,
                                                'days' => $day,
                                            ]
                                        );
                                        if (!empty($service)) {
                                            $this->user->update(
                                                'st_offer_availability',
                                                $insertArr,
                                                [
                                                    'service_id' => $subRes_id,
                                                    'days' => $day,
                                                ]
                                            );
                                        } else {
                                            $insertArr[
                                                'service_id'
                                            ] = $subRes_id;
                                            $this->user->insert(
                                                'st_offer_availability',
                                                $insertArr
                                            );
                                        }
                                        $i++;
                                    }
                                }
                            }
                            $j++;
                            //$arrins['created_by']=$res_id;
                        }
                    }

                    /************************************* Insert More service section end *****************************************/

                    if (isset($discount_price)) {
                        $postdays = $_POST['days'];
                        $poststart = $_POST['start'];
                        $postend = $_POST['end'];

                        $days_array = [
                            'monday',
                            'tuesday',
                            'wednesday',
                            'thursday',
                            'friday',
                            'saturday',
                            'sunday',
                        ];
                        $i = 0;

                        foreach ($days_array as $day) {
                            if (in_array($day, $postdays)) {
                                $insertArr = [];
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'open';
                                $insertArr['starttime'] =
                                    $_POST[$day . '_start'];
                                $insertArr['endtime'] = $_POST[$day . '_end'];
                            } else {
                                $insertArr = [];
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'close';
                                $insertArr['starttime'] = '';
                                $insertArr['endtime'] = '';
                            }
                            $service = $this->user->select_row(
                                'st_offer_availability',
                                'id',
                                ['service_id' => $id, 'days' => $day]
                            );

                            if (!empty($service)) {
                                $this->user->update(
                                    'st_offer_availability',
                                    $insertArr,
                                    ['service_id' => $id, 'days' => $day]
                                );
                            } else {
                                $insertArr['service_id'] = $id;
                                $this->user->insert(
                                    'st_offer_availability',
                                    $insertArr
                                );
                            }
                            $i++;
                        }
                    }

                    $this->session->set_flashdata(
                        'success',
                        $this->lang->line('succss_update_service')
                    );
                    redirect(base_url('merchant/service_listing'));
                    //redirect(base_url('merchant/add_service/'.url_encode($id)));
                }
            } else {
                extract($_POST);
                //print_r($_POST); die;
                $postdays = empty($days) ? [] : $days; 
                //$poststart=$_POST['start'];
                // $postend=$_POST['end'];

                $insert = [];
                $insert['name'] = $name;
                // $insert['category_id']    = $category;
                $insert['filtercat_id'] = $filtercategory;

                $insert['subcategory_id'] = $sub_category;
                //$insert['duration']       = $duration;
                $insert['service_detail'] = $detail;
                if ($price_start_option)
                    $insert['price_start_option'] = $price_start_option;
                //$insert['buffer_time']    = ($buffer_time !='')?$buffer_time:0;

                $catres = $this->user->select_row(
                    'st_category',
                    'id,parent_id',
                    ['id' => $sub_category]
                );
                $category = 0;
                if (!empty($catres->parent_id)) {
                    $insert['category_id'] = $catres->parent_id;
                    $category = $catres->parent_id;
                }

                if (!empty($tax) && $tax != 'notax') {
                    $tax_id = url_decode($tax);
                } else {
                    $tax_id = 0;
                }

                $insert['tax_id'] = $tax_id;

                if (!empty($proccess_time)) {
                    $insert['type'] = 1;
                    $insert['setuptime'] = $setuptime;
                    $insert['processtime'] = $processtime;
                    $insert['finishtime'] = $finishtime;

                    $drsin = $setuptime + $processtime + $finishtime;

                    $insert['duration'] = $drsin;
                } else {
                    $insert['type'] = 0;
                    $insert['duration'] = $duration;
                    $insert['buffer_time'] = !empty($buffer_time)
                        ? $buffer_time
                        : 0;
                }

                $price = str_replace(',', '.', (string) $price);

                if (isset($discount_price) && $discount_price != '') {
                    $discount_price = str_replace(
                        ',',
                        '.',
                        (string) $discount_price
                    );

                    $insert['discount_price'] = $discount_price;
                    $insert['discount_percent'] = get_discount_percent(
                        $price,
                        $discount_price
                    );
                } else {
                    $insert['discount_percent'] = '0.00';
                    $insert['discount_price'] = 0;
                }

                $insert['price'] = $price;
                $insert['status'] = 'active';
                $insert['ip_address'] = $this->input->ip_address();

                if (!empty($check_online_option)) {
                    $insert['online'] = '1';
                } else {
                    $insert['online'] = '0';
                }

                $res_id = $this->user->insert('st_merchant_category', $insert);
                if ($res_id) {
                    /************************************* Insert More service section *****************************************/
                    if (
                        !empty($subPrice) &&
                        !empty($subDuration)
                    ) {
                        $j = 0;
                        $kl = 1;
                        foreach ($subPrice as $prc) {
                            $arrins = [];
                            if (!empty($subService[$j])) {
                                $arrins['name'] = $subService[$j];
                            } else {
                                $arrins['name'] = '';
                            }

                            $arrins['category_id'] = $category;
                            $arrins['filtercat_id'] = $filtercategory;
                            $arrins['subcategory_id'] = $sub_category;

                            if (!empty($subServiceId[$j]) && !empty($subprice_start_option1)) {
                                $arrins['price_start_option'] =
                                    $subprice_start_option1[$subServiceId[$j]];
                            } else if (!empty($subprice_start_option)){
                                $arrins['price_start_option'] =
                                    $subprice_start_option[$kl];
                                $kl++;
                            }
                            //$arrins['price_start_option'] = $subprice_start_option[$j+1];
                            $subPrice[$j] = str_replace(
                                ',',
                                '.',
                                (string) $subPrice[$j]
                            );

                            $arrins['price'] = $subPrice[$j];
                            $arrins['service_detail'] = $detail;
                            $arrins['tax_id'] = $tax_id;

                            if (!empty($subonline[$j])) {
                                $arrins['online'] = '1';
                            } else {
                                $arrins['online'] = '0';
                            }

                            if (!empty($subproccess_time[$j])) {
                                $arrins['type'] = 1;
                                $arrins['setuptime'] = $subsetuptime[$j];
                                $arrins['processtime'] = $subprocesstime[$j];
                                $arrins['finishtime'] = $subfinishtime[$j];
                                $drsin =
                                    $subsetuptime[$j] +
                                    $subprocesstime[$j] +
                                    $subfinishtime[$j];
                                $arrins['duration'] = $drsin;
                            } else {
                                $arrins['type'] = 0;
                                $arrins['duration'] = $subDuration[$j];
                                $arrins['buffer_time'] =
                                    $subBuffer_time[$j] != ''
                                        ? $subBuffer_time[$j]
                                        : 0;
                            }

                            if (!empty($subDiscount_price[$j])) {
                                $subDiscount_price[$j] = str_replace(
                                    ',',
                                    '.',
                                    (string) $subDiscount_price[$j]
                                );

                                $arrins['discount_price'] =
                                    $subDiscount_price[$j];
                                $arrins[
                                    'discount_percent'
                                ] = get_discount_percent(
                                    $subPrice[$j],
                                    $subDiscount_price[$j]
                                );
                            }
                            $arrins['parent_service_id'] = $res_id;
                            $arrins['status'] = 'active';

                            $subRes_id = $this->user->insert(
                                'st_merchant_category',
                                $arrins
                            );
                            if ($subRes_id) {
                                if (!empty($assigned_users)) {
                                    foreach ($assigned_users as $uid) {
                                        $assin = [];
                                        $assin['user_id'] = $uid;
                                        $assin['service_id'] = $subRes_id;
                                        $assin['subcat_id'] = $sub_category;
                                        $assin['created_on'] = date(
                                            'Y-m-d H:i:s'
                                        );
                                        $assin[
                                            'created_by'
                                        ] = $this->session->userdata(
                                            'st_userid'
                                        );
                                        //$insertAssin[]=$assin;
                                        $this->user->insert(
                                            'st_service_employee_relation',
                                            $assin
                                        );
                                    }
                                }

                                if (!empty($subDiscount_price[$j])) {
                                    $days_array = [
                                        'monday',
                                        'tuesday',
                                        'wednesday',
                                        'thursday',
                                        'friday',
                                        'saturday',
                                        'sunday',
                                    ];
                                    $i = 0;

                                    foreach ($days_array as $day) {
                                        if (in_array($day, $postdays)) {
                                            $insertArr = [];
                                            $insertArr[
                                                'service_id'
                                            ] = $subRes_id;
                                            $insertArr['days'] = $day;
                                            $insertArr['type'] = 'open';
                                            $insertArr['starttime'] =
                                                $_POST[$day . '_start'];
                                            $insertArr['endtime'] =
                                                $_POST[$day . '_end'];
                                        } else {
                                            $insertArr = [];
                                            $insertArr[
                                                'service_id'
                                            ] = $subRes_id;
                                            $insertArr['days'] = $day;
                                            $insertArr['type'] = 'close';
                                        }
                                        $insertArr['created_on'] = date(
                                            'Y-m-d H:i:s'
                                        );
                                        $insertArr[
                                            'created_by'
                                        ] = $this->session->userdata(
                                            'st_userid'
                                        );
                                        //$insertOfferVailablity[]=$insertArr;
                                        $this->user->insert(
                                            'st_offer_availability',
                                            $insertArr
                                        );
                                        $i++;
                                    }
                                }
                            }
                            $j++;
                            //$arrins['created_by']=$res_id;
                        }
                    }

                    /************************************* Insert More service section end *****************************************/

                    if (!empty($assigned_users)) {
                        foreach ($assigned_users as $uid) {
                            $assin = [];
                            $assin['user_id'] = $uid;
                            $assin['service_id'] = $res_id;
                            $assin['subcat_id'] = $sub_category;
                            $assin['created_on'] = date('Y-m-d H:i:s');
                            $assin['created_by'] = $this->session->userdata(
                                'st_userid'
                            );
                            // $assin=array('user_id'=>$uid,'service_id'=>$res_id,'created_on'=>date('Y-m-d H:i:s'),'created_by'=>$this->session->userdata('st_userid'));
                            // $insertAssin[]=$assin;
                            $this->user->insert(
                                'st_service_employee_relation',
                                $assin
                            );
                        }
                    }

                    if (!empty($discount_price)) {
                        $days_array = [
                            'monday',
                            'tuesday',
                            'wednesday',
                            'thursday',
                            'friday',
                            'saturday',
                            'sunday',
                        ];
                        $i = 0;

                        foreach ($days_array as $day) {
                            if (in_array($day, $postdays)) {
                                $insertArr = [];
                                $insertArr['service_id'] = $res_id;
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'open';
                                $insertArr['starttime'] =
                                    $_POST[$day . '_start'];
                                $insertArr['endtime'] = $_POST[$day . '_end'];
                            } else {
                                $insertArr = [];
                                $insertArr['service_id'] = $res_id;
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'close';
                            }
                            $this->user->insert(
                                'st_offer_availability',
                                $insertArr
                            );

                            $i++;
                        }
                    }

                    $this->session->set_flashdata(
                        'success',
                        $this->lang->line('succss_add_service')
                    );

                    redirect(base_url('merchant/service_listing'));
                    //redirect(base_url('merchant/add_service'));
                }
            }
        } else {
            $mid = $this->session->userdata('st_userid');

            if ($_GET['id']) {
                $id = url_decode($_GET['id']);
                $service = $this->user->select_row(
                    'st_merchant_category',
                    '*',
                    ['status' => 'active', 'id' => $id]
                );
                $this->data['service'] = $service;
                $assigned_user = $this->user->select(
                    'st_service_employee_relation',
                    'user_id',
                    ['service_id' => $id],
                    '',
                    'id',
                    'ASC'
                );
                $uid = [];

                if (!empty($assigned_user)) {
                    foreach ($assigned_user as $user) {
                        $uid[] = $user->user_id;
                    }
                }
                $this->data['assigned_user'] = $uid;

                $this->data['subcategory'] = $this->user->select(
                    'st_category',
                    'id,category_name',
                    [
                        'status' => 'active',
                        'filter_category' => $service->filtercat_id,
                    ],
                    '',
                    'category_name',
                    'ASC'
                );

                $this->data['offer'] = $this->user->select(
                    'st_offer_availability',
                    'id,service_id,days,type,starttime,endtime',
                    ['service_id' => $id],
                    '',
                    'id',
                    'ASC'
                );

                $this->data[
                    'offer_check'
                ] = $this->user->select_row('st_offer_availability', 'id', [
                    'service_id' => $id,
                    'type' => 'open',
                ]);
                //$this->data['offer_check']=$this->user->select('st_offer_availability','id',array('service_id'=>$id,'type' => 'open'),'','id','ASC');

                $this->data['moreServices'] = $this->user->select(
                    'st_merchant_category',
                    '*',
                    ['parent_service_id' => $id, 'status' => 'active'],
                    '',
                    'id',
                    'ASC'
                );
            }
            $duplicate = '';

            if (!empty($_GET['duplecate'])) {
                $this->data['duplicate'] = 'yes';
            }

            $this->data['category'] = $this->user->select(
                'st_category',
                'id,category_name',
                ['status' => 'active', 'parent_id' => 0],
                '',
                'category_name',
                'ASC'
            );

            $this->data['filtercategory'] = get_filter_with_parent_cat_menu();

            $this->data['taxes'] = $this->user->select(
                'st_taxes',
                'id,tax_name,price,defualt',
                ['status' => 'active', 'merchant_id' => $mid],
                '',
                'id',
                'ASC'
            );

            $this->data['days_array'] = $this->user->select(
                'st_availability',
                'id,days,type,starttime,endtime',
                ['user_id' => $mid, 'type' => 'open'],
                '',
                'id',
                'ASC'
            );

            $this->data['users'] = $this->user->select(
                'st_users',
                'id,profile_pic,first_name,last_name',
                [
                    'status' => 'active',
                    'merchant_id' => $mid,
                    'access' => 'employee',
                ],
                '',
                'id',
                'ASC'
            );
            // echo "<pre>"; print_r($this->data); die;
            $html = $this->load->view(
                'frontend/marchant/add_service_popup',
                $this->data,
                true
            );

            echo json_encode(['success' => 1, 'html' => $html]);
            die();
        }
    }

    public function get_sub_service()
    {
        $mid = $this->session->userdata('st_userid');
        $eid = $_POST['eid'];
        if ($_POST['id'] && $eid) {
            $servicelists = $this->user->select(
                'st_service_employee_relation',
                'service_id',
                ['user_id' => $eid]
            );
            $serarr = [];
            if ($servicelists) {
                foreach($servicelists as $sl) {
                    $serarr[] = $sl->service_id;
                }
            }

            $res = $this->user->select(
                'st_merchant_category',
                '*',
                [
                    'subcategory_id' => $_POST['id'],
                    'status' => 'active',
                    'created_by' => $mid
                ],
                '',
                'id',
                'ASC'
            );
            if ($res) {

                $html = '';
                foreach($res as $row) {
                    if (in_array($row->id, $serarr )) {
                        $html .= '<li class="radiobox-image ">';
                        $html .= '<input type="radio" id="subservice'.$row->id.'" name="service_select" value="'.$row->id.'"><label for="subservice'.$row->id.'" class="height48v vertical-middle pt-2">'.$row->name.'</label></li>';
                    }
                }
                echo json_encode(['success' => 1, 'html' => $html]);
            } else {
                echo json_encode(['success' => 0, 'html' => '']);    
            }
        } else {
            echo json_encode(['success' => 0, 'html' => '']);
        }
    }

    public function get_sub_category_by_emp() {
        $eid = $_POST['id'];

        $servicelists = $this->user->select(
            'st_service_employee_relation',
            'service_id',
            ['user_id' => $eid]
        );

        $serarr = [];
        if ($servicelists) {
            foreach($servicelists as $sl) {
                $serarr[] = $sl->service_id;
            }
        }
        $where = [
            'r.created_by' => $this->session->userdata('st_userid'),
            'r.status !=' => 'deleted',
            'r.parent_service_id' => 0
        ];
        
        $serviceList = $this->user->getservicelist($where, $serarr);

        $filtercat = '';
        $html = '';

        foreach($serviceList as $row){
            if ($row['m_category'] != $filtercat) {
                $html .= '<li style="background-color: rgba(0, 179, 190, 0.5)" class="categorybar">
                <div class="p-2 d-flex justify-content-center align-items-center" style="font-size: 16px; color: #333; font-weight:bold;">
                    '.$row['m_category'].'
                </div></tr>';
                $filtercat = $row['m_category'];
            }

            $html .= '<li class="radiobox-image ">
            <input type="radio" data-subservice="'.(empty($row['subService'])?'n':'y').'" id="category'.$row['subcategory_id'].'" name="category_select" value="'.$row['subcategory_id'].'" data-serid="'.$row['sid'].'">
            <label for="category'.$row['subcategory_id'].'" class="height48v vertical-middle pt-2">
                <div style="width: 12px; display:inline-block; font-weight:bold;">'.(empty($row['subService'])?' ':'+').'</div>
                '.$row['s_category'].'
            </label>
        </li>';
        }

        echo json_encode(['access' => '1', 'html' => $html, '2' => $serarr]);
    }
    //**** Get Sub Category ****//
    public function get_sub_category()
    {
        $subcategory = $this->user->select(
            'st_category',
            'id,category_name',
            ['status' => 'active', 'filter_category' => $_POST['catid']],
            '',
            'category_name',
            'ASC'
        );

        if (!empty($subcategory)) {
            $html = '';
            foreach ($subcategory as $subcat) {
                $html =
                    $html .
                    '<li class="radiobox-image"><input type="radio" id="id_subcat' .
                    $subcat->id .
                    '" name="sub_category" value="' .
                    $subcat->id .
                    '"><label for="id_subcat' .
                    $subcat->id .
                    '">' .
                    $subcat->category_name .
                    '</label></li>';
            }
            echo json_encode(['access' => '1', 'html' => $html]);
            die();
        } else {
            $html =
                '<li class="radiobox-image"><input type="radio" id="id_skin3"><label for="id_skin3">No sub category available</label></li>';
            echo json_encode(['access' => '1', 'html' => $html]);
            die();
        }

        //print_r($this->data); die;
        //$this->load->view('frontend/marchant/add_service',$this->data);
    }

    //**** Service Listing *****//
    public function service_listing()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        $where = [
            'r.created_by' => $this->session->userdata('st_userid'),
            'r.status !=' => 'deleted',
            'r.parent_service_id' => 0,
        ];

        $limit = isset($_GET['limit']) ? $_GET['limit'] : PER_PAGE10; //
        $url = 'merchant/service_listing';
        $segment = 3;
        $page = mypaging($url, $total, $segment, $limit);
        // $thi
        $this->data['serviceList'] = $this->user->getservicelist($where);

        $this->load->view('frontend/marchant/service_listing', $this->data);
    }

    //**** Delete Service ****//
    public function deleteservice()
    {
        $upd_data = ['status' => 'deleted'];
        $res = $this->user->update('st_merchant_category', $upd_data, [
            'id' => url_decode($_POST['tid']),
        ]);
        if ($res) {
            $this->user->update('st_merchant_category', $upd_data, [
                'parent_service_id' => url_decode($_POST['tid']),
            ]);
            $this->user->delete('st_service_employee_relation', [
                'service_id' => url_decode($_POST['tid']),
            ]);
            $this->user->delete('st_cart', [
                'service_id' => url_decode($_POST['tid']),
            ]);

            $childids = $this->user->select('st_merchant_category', 'id', [
                'parent_service_id' => url_decode($_POST['tid']),
            ]);
            if (!empty($childids)) {
                foreach ($childids as $pid) {
                    $this->user->delete('st_service_employee_relation', [
                        'service_id' => $pid->id,
                    ]);
                    $this->user->delete('st_cart', [
                        'service_id' => url_decode($pid->id),
                    ]);
                }
            }

            echo json_encode([
                'success' => 1,
                'ids' => url_decode($_POST['tid']),
            ]);
        } else {
            echo json_encode([
                'success' => 0,
                'ids' => url_decode($_POST['tid']),
            ]);
        }
    }

    //**** Get service assign ****//
    public function get_services_for_assign()
    {
        $this->data['search'] = isset($_POST['search']) ? $_POST['search'] : '';
        $this->data['services'] = $this->user->get_all_service_of_merchant(
            $this->session->userdata('st_userid'),
            $this->data['search']
        );

        //$this->data['getarr1'] =  $getarr1;
        //$this->data['eid'] = $_POST['eId'];
        //print_r($this->data); die;
        $view = $this->load->view(
            'frontend/common/assign_service_poup',
            $this->data,
            true
        );

        echo json_encode(['success' => 1, 'html' => $view]);
        die();
    }

    //**** Merchant Dashboard ****//
    public function dashboard()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        $mid = $this->session->userdata('st_userid');

        $this->load->library('user_agent');
        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = $this->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $borsers = ['safari', 'firefox', 'uc browser', 'chrome', 'opera'];
        if (in_array(strtolower($agent), $borsers)) {
            //echo "Match found";
        } else {
            $this->session->set_flashdata(
                'member_error',
                'Browser is not supported and to use chrome, firefox or safari for best experience.'
            );
        }

        $this->data['employee'] = '';
        if (!empty($_GET['id'])) {
            $uid = url_decode($_GET['id']);
            $this->data['employee'] = $this->user->select_row(
                'st_users',
                'id,first_name,last_name',
                ['id' => $uid]
            );
        }
        $this->db->query(
            'UPDATE st_booking SET seen_status=1 WHERE seen_status=0 AND merchant_id=' .
                $this->session->userdata('st_userid')
        );

        $this->data['employees'] = $this->user->select(
            'st_users',
            'id,first_name,last_name,email,mobile,profile_pic,online_booking,status',
            [
                'merchant_id' => $this->session->userdata('st_userid'),
                'status' => 'active',
            ]
        );

        $selectMinMaxtime =
            "SELECT MIN(starttime) as mintime,(SELECT MAX(endtime) FROM st_availability WHERE type='open' AND user_id=" .
            $this->session->userdata('st_userid') .
            ") as maxtime FROM st_availability WHERE type='open' AND user_id=" .
            $this->session->userdata('st_userid');

        $this->data['minmaxtime'] = $this->user->custome_query(
            $selectMinMaxtime,
            'row'
        );
        $this->data['minmaxtime']->mintime = getPreExtraHrs($mid);
        $this->data['minmaxtime']->maxtime = getAfterExtraHrs($mid);
        $this->user->delete('st_cart', [
            'user_id' => $this->session->userdata('st_userid'),
        ]);
        $_SESSION['book_session'] = '';

        $returndata = [];

        if (!empty($_GET['id'])) {
            $id = url_decode($_GET['id']);

            $data = $this->user->select('st_availability', '*', [
                'user_id' => $id,
            ]);

            if (!empty($data)) {
                foreach ($data as $row) {
                    if ($row->type == 'open') {
                        $returndata[] = [
                            'start' => getPreExtraHrs($id),
                            'end' => getAfterExtraHrs($id),
                            'dow' => [date('w', strtotime($row->days))],
                        ];
                        if (
                            !empty($row->starttime_two) &&
                            !empty($row->endtime_two)
                        ) {
                            $returndata[] = [
                                'start' => getPreExtraHrs($id),
                                'end' => getAfterExtraHrs($id),
                                'dow' => [date('w', strtotime($row->days))],
                            ];
                        }
                    } else {
                        $returndata[] = [
                            'start' => '00:00:00',
                            'end' => '00:00:00',
                            'dow' => [date('w', strtotime($row->days))],
                        ];
                    }
                }
            }
        } else {
            $data = $this->user->select('st_availability', '*', [
                'user_id' => $mid,
            ]);
            //echo $this->db->last_query().'<pre>'; print_r($data); die;

            if (!empty($data)) {
                foreach ($data as $row) {
                    if ($row->type == 'open') {
                        $returndata[] = [
                            'start' => getPreExtraHrs($mid),
                            'end' => getAfterExtraHrs($mid),
                            'dow' => [date('w', strtotime($row->days))],
                        ];
                    } else {
                        $returndata[] = [
                            'start' => '00:00:00',
                            'end' => '00:00:00',
                            'dow' => [date('w', strtotime($row->days))],
                        ];
                    }
                }
            }
        }

        //$this->session->set_userdata('businesshours',json_encode($returndata));
        $this->data['businesshours'] = $returndata;

        if (!empty($_SESSION['booking_complete_success'])) {
            $this->session->set_flashdata(
                'success',
                $_SESSION['booking_complete_success']
            );
            $_SESSION['booking_complete_success'] = '';
        }
        //echo '<pre>'; print_r($this->data); die;
        $this->data['mdetails'] = $this->user->select_row(
            'st_users',
            'calendar_view',
            ['id' => $mid]
        );
        // echo '<pre>'; print_r($this->data); die;

        $this->load->view('frontend/marchant/dashboard', $this->data);
    }

    public function getResource()
    {
        $usid = $this->session->userdata('st_userid');
        if (!empty($_POST['id'])) {
            $uid = url_decode($_POST['id']);
            $users = $this->user->select(
                'st_users',
                'id,first_name,last_name,profile_pic',
                ['merchant_id' => $usid, 'status' => 'active', 'id' => $uid]
            );
            //$bookings=$this->user->join_two_without_limit('st_booking','st_users','employee_id','id',array('st_booking.merchant_id'=>$usid, 'st_booking.status!='=>'deleted','st_booking.status!='=>'cancelled','employee_id'=>$uid),'st_booking.id,st_booking.merchant_id,booking_time,booking_endtime,booking_type,st_booking.status,st_users.first_name,st_users.last_name,st_users.calender_color as color','','st_booking.id');
        } else {
            $users = $this->user->select(
                'st_users',
                'id,first_name,last_name,profile_pic',
                ['merchant_id' => $usid, 'status' => 'active']
            );
        }
        $data = [];
        if (!empty($users)) {
            foreach ($users as $user) {
                $imgUrl = base_url('assets/frontend/images/user-icon-gret.svg');

                if (!empty($user->profile_pic)) {
                    $imgUrl = base_url(
                        'assets/uploads/employee/' .
                            $user->id .
                            '/' .
                            $user->profile_pic
                    );
                }
                $data[] = [
                    'id' => $user->id,
                    'title' => $user->first_name,
                    'imgurl' => $imgUrl,
                ];
            }
        }
        echo json_encode($data);
        die();
    }

    //**** Merchant Booking ****//
    public function booking()
    {
        //print_r($_POST); die;

        $usid = $this->session->userdata('st_userid');
        if (!empty($_POST['id'])) {
            $uid = url_decode($_POST['id']);

            $sdate = date('Y-m-d 00:00:00', strtotime($_POST['start']));
            $edate = date('Y-m-d 00:00:00', strtotime($_POST['end']));

            //$bookings = $this->user->select('st_booking','id,merchant_id,employee_id,booking_time,booking_endtime,booking_type,status,(SELECT calender_color FROM st_users WHERE id=st_booking.employee_id) as color,(SELECT first_name FROM st_users WHERE id=st_booking.employee_id) as first_name',['merchant_id'=>$usid, 'status!='=>'deleted','status!='=>'cancelled','employee_id'=>$uid]);
            $bookings = $this->user->join_two_without_limit_calender(
                'st_booking',
                'st_users',
                'employee_id',
                'id',
                [
                    'st_booking.merchant_id' => $usid,
                    'st_booking.status!=' => 'deleted',
                    'st_booking.status!=' => 'cancelled',
                    'employee_id' => $uid,
                    'booking_time >=' => $sdate,
                    'booking_time <' => $edate,
                    'close_for' => 0,
                ],
                'st_booking.id,st_booking.merchant_id,,st_booking.block_for,st_booking.user_id,st_booking.total_price,st_booking.booking_type,st_booking.blocked,st_booking.notes,st_booking.fullname,booking_time,employee_id,booking_endtime,booking_type,blocked_type,st_booking.status,st_users.first_name,st_users.profile_pic,st_users.last_name,st_users.calender_color as color,(SELECT first_name FROM st_users WHERE id=st_booking.user_id) as ufirst_name,(SELECT last_name FROM st_users WHERE id=st_booking.user_id) as ulast_name,(SELECT profile_pic FROM st_users WHERE id=st_booking.user_id) as uprofile_pic,(SELECT notes FROM st_usernotes WHERE user_id=st_booking.user_id AND created_by=' .
                    $usid .
                    ') as unotes',
                '',
                'st_booking.id'
            );
        } else {
            $sdate = date('Y-m-d 00:00:00', strtotime($_POST['start']));
            $edate = date('Y-m-d 00:00:00', strtotime($_POST['end']));

            $bookings = $this->user->join_two_without_limit_calender(
                'st_booking',
                'st_users',
                'employee_id',
                'id',
                [
                    'st_booking.merchant_id' => $usid,
                    'st_booking.status!=' => 'deleted',
                    'st_booking.status!=' => 'cancelled',
                    'blocked_perent' => 0,
                    'booking_time >=' => $sdate,
                    'booking_time <' => $edate,
                    'close_for' => 0,
                ],
                'st_booking.id,st_booking.walkin_customer_notes,st_booking.id as bid,st_booking.merchant_id,st_booking.block_for,st_booking.user_id,employee_id,st_booking.total_price,st_booking.notes,st_booking.blocked,st_booking.booking_type,st_booking.fullname,booking_time,booking_endtime,booking_type,st_booking.status,blocked_type,st_users.first_name,st_users.last_name,st_users.profile_pic,st_users.calender_color as color,(SELECT first_name FROM st_users WHERE id=st_booking.user_id) as ufirst_name,(SELECT last_name FROM st_users WHERE id=st_booking.user_id) as ulast_name,(SELECT count(id) FROM st_booking WHERE st_booking.blocked=bid) as totalblockcheck,(SELECT profile_pic FROM st_users WHERE id=st_booking.user_id) as uprofile_pic,(SELECT notes FROM st_usernotes WHERE user_id=st_booking.user_id AND created_by=' .
                    $usid .
                    ') as unotes',
                '',
                'st_booking.id'
            );
            //$bookings = $this->user->select('st_booking','id,merchant_id,booking_time,booking_endtime,booking_type,status,(SELECT calender_color FROM st_users WHERE id=st_booking.employee_id) as color,(SELECT first_name FROM st_users WHERE id=st_booking.employee_id) as first_name',['merchant_id'=>$usid, 'status!='=>'deleted','status!='=>'cancelled','booking_type!='=>'self']);
        }
        //echo '<pre>'; print_r($bookings); die;
        //    echo $this->db->last_query();die;
        $data = [];
        if ($bookings) {
            foreach ($bookings as $booking) {
                // booking service name with sub category
                $book_details = $this->user->select(
                    'st_booking_detail',
                    'id,booking_id,service_id,service_name,service_type,setuptime_start,setuptime_end,finishtime_start,finishtime_end,(SELECT price_start_option FROM st_merchant_category WHERE id=service_id) as price_start_option',
                    ['booking_id' => $booking->id, 'show_calender !=' => 1],
                    '',
                    'id',
                    'ASC'
                );

                $sevices = '';
                $abcheck = '';
                if (!empty($book_details)) {
                    foreach ($book_details as $serv) {
                        if ($serv->price_start_option == 'ab') {
                            if (empty($abcheck)) {
                                $abcheck = 'ab';
                            }
                        }

                        $sub_name = get_subservicename($serv->service_id);

                        if ($sub_name == $serv->service_name) {
                            $sevices .= $serv->service_name . ', ';
                        } else {
                            $sevices .=
                                $sub_name . ' - ' . $serv->service_name . ', ';
                        }
                    }
                }
                $name = rtrim($sevices, ', ');
                //~ //$name=get_servicename($booking->id);
                if (empty($name)) {
                    $name = '';
                } else {
                    $name = $name;
                }

                $difference =
                    strtotime($booking->booking_endtime) -
                    strtotime($booking->booking_time);

                $difference_in_minutes = $difference / 60;

                $image = base_url('assets/frontend/images/user-icon-gret.svg');
                // $detail_url=base_url("booking/detail/").url_encode($booking->id);
                if (
                    !empty($booking->booking_type) &&
                    $booking->booking_type == 'self'
                ) {
                    if (!empty($booking->profile_pic)) {
                        if (
                            (!empty($booking->totalblockcheck) &&
                                $booking->totalblockcheck <= 1) ||
                            empty($booking->totalblockcheck)
                        ) {
                            $image = base_url(
                                'assets/uploads/employee/' .
                                    $booking->employee_id .
                                    '/icon_' .
                                    $booking->profile_pic
                            );
                        }
                    }
                    if ($booking->blocked_type == 'close') {
                        $data[] = [
                            'id' => $booking->id,
                            'resourceId' => $booking->employee_id,
                            'title' => '',
                            'serviceName' => '',
                            'status' => '',
                            'notes' => $booking->notes,
                            'blocked' => $booking->blocked,
                            'start' => $booking->booking_time,
                            'end' => $booking->booking_endtime,
                            'totaltime' => $difference_in_minutes,
                            'color' => '',
                            'abcheck' => $abcheck,
                            'blocked_type' => $booking->blocked_type,
                            'image_url' => '',
                            'detail_url' => url_encode($booking->id),
                            'add_class' => '',
                        ];
                    } else {
                        if ($booking->block_for != 1) {
                            $blockEmpsquery =
                                'SELECT bk.id,bk.employee_id,us.first_name FROM st_booking as bk LEFT JOIN st_users as us ON us.id=bk.employee_id WHERE bk.blocked=' .
                                $booking->blocked;

                            $blockEmps = $this->user->custome_query(
                                $blockEmpsquery
                            );
                            $emparray = [];
                            if (!empty($blockEmps)) {
                                foreach ($blockEmps as $emp) {
                                    $emparray[] = $emp->first_name;
                                }
                            }

                            //echo '<pre>'; print_r($emparray); die;
                            $empname = implode($emparray, ',');
                        } else {
                            $empname = $this->lang->line('All_staff');
                        }

                        $data[] = [
                            'id' => $booking->id,
                            'resourceId' => $booking->employee_id,
                            'title' => '',
                            'serviceName' => $this->lang->line('blocked_time'),
                            'status' => '',
                            'notes' => $booking->notes,
                            'blocked' => $booking->blocked,
                            'start' => $booking->booking_time,
                            'end' => $booking->booking_endtime,
                            'totaltime' => $difference_in_minutes,
                            'abcheck' => $abcheck,
                            'color' => '#4D4D4D',
                            'blocked_type' => $booking->blocked_type,
                            'image_url' => $image,
                            'detail_url' => url_encode($booking->id),
                            'userName' => $empname,
                            'add_class' => 'block-time-bg',
                        ];
                    }
                } else {
                    if ($booking->status == 'completed') {
                        if ($booking->booking_type == 'guest') {
                            $userName = $booking->fullname;
                        } else {
                            $userName =
                                $booking->ufirst_name .
                                ' ' .
                                $booking->ulast_name;
                            if (!empty($booking->uprofile_pic)) {
                                $image = base_url(
                                    'assets/uploads/users/' .
                                        $booking->user_id .
                                        '/icon_' .
                                        $booking->uprofile_pic
                                );
                            }
                        }

                        $unotes = '';
                        if (!empty($booking->unotes)) {
                            $unotes = $booking->unotes;
                        }
                        if ($booking->booking_type == 'guest') {
                            $unotes = $booking->walkin_customer_notes;
                        }
                        $tstarttime = $book_details[0]->setuptime_start;
                        $tendtime = $book_details[0]->setuptime_end;
                        foreach ($book_details as $details) {
                            if ($details->service_type == 1) {
                                $tendtime = $details->setuptime_end;
                                $data[] = [
                                    'id' => $booking->id,
                                    'resourceId' => $booking->employee_id,
                                    'title' => $booking->first_name,
                                    'serviceName' => $name,
                                    'status' => 'completed',
                                    'notes' => $booking->notes,
                                    'blocked' => $booking->blocked,
                                    'unotes' => $unotes,
                                    'abcheck' => '',
                                    'start' => $tstarttime,
                                    'end' => $tendtime,
                                    'totaltime' => $difference_in_minutes,
                                    'color' => '#C1BEBE60',
                                    'totalprice' => price_formate(
                                        $booking->total_price
                                    ),
                                    'blocked_type' => $booking->blocked_type,
                                    'userName' => $userName,
                                    'image_url' => $image,
                                    'detail_url' => url_encode($booking->id),
                                    'add_class' => '',
                                ];

                                $tstarttime = $details->finishtime_start;
                                $tendtime = $details->finishtime_end;
                            } else {
                                $tendtime = $details->setuptime_end;
                            }
                        }
                        $data[] = [
                            'id' => $booking->id,
                            'resourceId' => $booking->employee_id,
                            'title' => $booking->first_name,
                            'serviceName' => $name,
                            'status' => 'completed',
                            'notes' => $booking->notes,
                            'blocked' => $booking->blocked,
                            'unotes' => $unotes,
                            'abcheck' => '',
                            'start' => $tstarttime,
                            'end' => $tendtime,
                            'totaltime' => $difference_in_minutes,
                            'color' => '#C1BEBE60',
                            'totalprice' => price_formate(
                                $booking->total_price
                            ),
                            'blocked_type' => $booking->blocked_type,
                            'userName' => $userName,
                            'image_url' => $image,
                            'detail_url' => url_encode($booking->id),
                            'add_class' => '',
                        ];
                    } elseif ($booking->status == 'no show') {
                        if ($booking->booking_type == 'guest') {
                            $userName = $booking->fullname;
                        } else {
                            $userName =
                                $booking->ufirst_name .
                                ' ' .
                                $booking->ulast_name;
                            if (!empty($booking->uprofile_pic)) {
                                $image = base_url(
                                    'assets/uploads/users/' .
                                        $booking->user_id .
                                        '/icon_' .
                                        $booking->uprofile_pic
                                );
                            }
                        }

                        $unotes = '';
                        if (!empty($booking->unotes)) {
                            $unotes = $booking->unotes;
                        }
                        if ($booking->booking_type == 'guest') {
                            $unotes = $booking->walkin_customer_notes;
                        }
                        $tstarttime = $book_details[0]->setuptime_start;
                        $tendtime = $book_details[0]->setuptime_end;
                        foreach ($book_details as $details) {
                            if ($details->service_type == 1) {
                                $tendtime = $details->setuptime_end;
                                $data[] = [
                                    'id' => $booking->id,
                                    'resourceId' => $booking->employee_id,
                                    'title' => $booking->first_name,
                                    'serviceName' => $name,
                                    'status' => 'no-show',
                                    'notes' => $booking->notes,
                                    'blocked' => $booking->blocked,
                                    'unotes' => $unotes,
                                    'abcheck' => $abcheck,
                                    'start' => $tstarttime,
                                    'end' => $tendtime,
                                    'totaltime' => $difference_in_minutes,
                                    'color' => '#C1BEBE60',
                                    'totalprice' => price_formate(
                                        $booking->total_price
                                    ),
                                    'blocked_type' => $booking->blocked_type,
                                    'userName' => $userName,
                                    'image_url' => $image,
                                    'detail_url' => url_encode($booking->id),
                                    'add_class' => '',
                                ];
                                $tstarttime = $details->finishtime_start;
                                $tendtime = $details->finishtime_end;
                            } else {
                                $tendtime = $details->setuptime_end;
                            }
                        }
                        $data[] = [
                            'id' => $booking->id,
                            'resourceId' => $booking->employee_id,
                            'title' => $booking->first_name,
                            'serviceName' => $name,
                            'status' => 'no-show',
                            'notes' => $booking->notes,
                            'blocked' => $booking->blocked,
                            'unotes' => $unotes,
                            'abcheck' => $abcheck,
                            'start' => $tstarttime,
                            'end' => $tendtime,
                            'totaltime' => $difference_in_minutes,
                            'color' => '#C1BEBE60',
                            'totalprice' => price_formate(
                                $booking->total_price
                            ),
                            'blocked_type' => $booking->blocked_type,
                            'userName' => $userName,
                            'image_url' => $image,
                            'detail_url' => url_encode($booking->id),
                            'add_class' => '',
                        ];
                    } elseif ($booking->status == 'confirmed') {
                        if (!empty($booking->color)) {
                            $color = $booking->color;
                        } else {
                            $color = '#FF9944';
                        }

                        if ($booking->booking_type == 'guest') {
                            $userName = $booking->fullname;
                        } else {
                            $userName =
                                $booking->ufirst_name .
                                ' ' .
                                $booking->ulast_name;
                            if (!empty($booking->uprofile_pic)) {
                                $image = base_url(
                                    'assets/uploads/users/' .
                                        $booking->user_id .
                                        '/icon_' .
                                        $booking->uprofile_pic
                                );
                            }
                        }

                        $unotes = '';
                        if (!empty($booking->unotes)) {
                            $unotes = $booking->unotes;
                        }
                        if ($booking->booking_type == 'guest') {
                            $unotes = $booking->walkin_customer_notes;
                        }
                        $tstarttime = $book_details[0]->setuptime_start;
                        $tendtime = $book_details[0]->setuptime_end;
                        foreach ($book_details as $details) {
                            if ($details->service_type == 1) {
                                $tendtime = $details->setuptime_end;
                                $tcolor = getTextColorFromBgColor($color);
                                $data[] = [
                                    'id' => $booking->id,
                                    'resourceId' => $booking->employee_id,
                                    'title' => $booking->first_name,
                                    'serviceName' => $name,
                                    'status' => 'confirmed',
                                    'notes' => $booking->notes,
                                    'blocked' => $booking->blocked,
                                    'unotes' => $unotes,
                                    'start' => $tstarttime,
                                    'end' => $tendtime,
                                    'totaltime' => $difference_in_minutes,
                                    'color' => $color,
                                    'abcheck' => $abcheck,
                                    'totalprice' => price_formate(
                                        $booking->total_price
                                    ),
                                    'blocked_type' => $booking->blocked_type,
                                    'userName' => $userName,
                                    'image_url' => $image,
                                    'detail_url' => url_encode($booking->id),
                                    'add_class' => $tcolor ? 'event-text-white' : '',
                                ];
                                $tstarttime = $details->finishtime_start;
                                $tendtime = $details->finishtime_end;
                            } else {
                                $tendtime = $details->setuptime_end;
                            }
                        }
                        $tcolor = getTextColorFromBgColor($color);
                        $data[] = [
                            'id' => $booking->id,
                            'resourceId' => $booking->employee_id,
                            'title' => $booking->first_name,
                            'serviceName' => $name,
                            'status' => 'confirmed',
                            'notes' => $booking->notes,
                            'blocked' => $booking->blocked,
                            'unotes' => $unotes,
                            'start' => $tstarttime,
                            'end' => $tendtime,
                            'totaltime' => $difference_in_minutes,
                            'color' => $color,
                            'abcheck' => $abcheck,
                            'totalprice' => price_formate(
                                $booking->total_price
                            ),
                            'blocked_type' => $booking->blocked_type,
                            'userName' => $userName,
                            'image_url' => $image,
                            'detail_url' => url_encode($booking->id),
                            'add_class' => $tcolor ? 'event-text-white' : '',
                        ];
                    } else {
                        if (!empty($booking->color)) {
                            $color = $booking->color;
                        } else {
                            $color = '#FF9944';
                        }

                        if ($booking->booking_type == 'guest') {
                            $userName = $booking->fullname;
                        } else {
                            $userName =
                                $booking->ufirst_name .
                                ' ' .
                                $booking->ulast_name;
                            if (!empty($booking->uprofile_pic)) {
                                $image = base_url(
                                    'assets/uploads/users/' .
                                        $booking->user_id .
                                        '/icon_' .
                                        $booking->uprofile_pic
                                );
                            }
                        }

                        $unotes = '';
                        if (!empty($booking->unotes)) {
                            $unotes = $booking->unotes;
                        }
                        if ($booking->booking_type == 'guest') {
                            $unotes = $booking->walkin_customer_notes;
                        }
                        if (!empty($book_details)) {
                            $tstarttime = $book_details[0]->setuptime_start;
                            $tendtime = $book_details[0]->setuptime_end;
                            foreach ($book_details as $details) {
                                $tendtime = $details->setuptime_end;
                                if ($details->service_type == 1) {
                                    $tcolor = getTextColorFromBgColor($color);
                                    $data[] = [
                                        'id' => $booking->id,
                                        'resourceId' => $booking->employee_id,
                                        'title' => $booking->first_name,
                                        'serviceName' => $name,
                                        'status' => '',
                                        'notes' => $booking->notes,
                                        'blocked' => $booking->blocked,
                                        'unotes' => $unotes,
                                        'start' => $tstarttime,
                                        'end' => $tendtime,
                                        'totaltime' => $difference_in_minutes,
                                        'color' => $color,
                                        'abcheck' => $abcheck,
                                        'totalprice' => price_formate(
                                            $booking->total_price
                                        ),
                                        'blocked_type' =>
                                            $booking->blocked_type,
                                        'userName' => $userName,
                                        'image_url' => $image,
                                        'detail_url' => url_encode(
                                            $booking->id
                                        ),
                                        'add_class' => $tcolor ? 'event-text-white' : '',
                                    ];
                                    $tstarttime = $details->finishtime_start;
                                    $tendtime = $details->finishtime_end;
                                } else {
                                    $tendtime = $details->setuptime_end;
                                }
                            }
                            $tcolor = getTextColorFromBgColor($color);
                            $data[] = [
                                'id' => $booking->id,
                                'resourceId' => $booking->employee_id,
                                'title' => $booking->first_name,
                                'serviceName' => $name,
                                'status' => '',
                                'notes' => $booking->notes,
                                'blocked' => $booking->blocked,
                                'unotes' => $unotes,
                                'start' => $tstarttime,
                                'end' => $tendtime,
                                'totaltime' => $difference_in_minutes,
                                'color' => $color,
                                'abcheck' => $abcheck,
                                'totalprice' => price_formate(
                                    $booking->total_price
                                ),
                                'blocked_type' =>
                                    $booking->blocked_type,
                                'userName' => $userName,
                                'image_url' => $image,
                                'detail_url' => url_encode(
                                    $booking->id
                                ),
                                'add_class' => $tcolor ? 'event-text-white' : '',
                            ];
                        }
                    }
                }
            };
        }

        $data1 = $this->user->select('st_availability', '*', [
            'user_id' => $usid,
        ]);
        if (!empty($data1)) {
            $startdate = date('Y-m-d', strtotime($_POST['start']));

            $days = [];
            for($ii = 0; $ii < 7; $ii++) {
                $dayName = strtolower(date('l', strtotime($startdate)));
                $days[$dayName] = $startdate;
                $startdate = date('Y-m-d', strtotime($startdate.'+1 day'));
            }
            foreach ($data1 as $row) {
                if ($row->type == 'open') {
                    $data[] = [
                        'className' => 'fc-extrabooking',
                        'start' => $days[$row->days] .' '. getPreExtraHrs($usid),
                        'end' => $days[$row->days] .' '. $row->starttime,
                        'rendering' => 'background'
                    ];
                    $data[] = [
                        'className' => 'fc-extrabooking',
                        'start' => $days[$row->days] .' '. $row->endtime,
                        'end' => $days[$row->days] .' '. getAfterExtraHrs($usid),
                        'rendering' => 'background'
                    ];
                }
            }
        }

        echo json_encode($data);
        die();
    }

    //**** Booking Listing ****//
    public function booking_listing()
    {
        if ($this->session->userdata('access') != 'marchant') {
            redirect(base_url('auth/login'));
        }
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        //~ $where=array('st_booking.merchant_id'=>$this->session->userdata('st_userid'),'st_booking.booking_type !='=>'self');
        //~ $totalcount = $this->user->getAllbookinglist($where);
        //~ if(!empty($totalcount))
        //~ $total=count($totalcount);
        //~ else
        //~ $total=0
        //~ $limit = isset($_GET['limit'])?$_GET['limit']:PER_PAGE10;	//PER_PAGE10
        //~ $url = 'merchant/booking_listing';
        //~ $segment = 3;
        //~ $page = mypaging($url,$total,$segment,$limit);
        //~ $this->data['booking']=$this->user->getAllbookinglist($where,$page["per_page"],$page["offset"],'st_booking.booking_time');
        //$this->data['title']  = 'Styletimer Buchungen';
        $this->data = '';
        $this->load->view('frontend/marchant/booking_listing', $this->data);
    }

    //**** Booking listing from ajax ****//
    public function getbokkinglist_ajax()
    {
        /* print_r($_GET);
	  print_r($_POST);
	  die;*/

        $where = [
            'st_booking.merchant_id' => $this->session->userdata('st_userid'),
            'st_booking.booking_type !=' => 'self',
            'st_booking.status !=' => 'deleted',
        ];
        $search = '';

        if (!empty($_GET['short'])) {
            if ($_GET['short'] == 'current_week') {
                $monday = strtotime('last monday');
                $monday =
                    date('w', $monday) == date('w')
                        ? $monday + 7 * 86400
                        : $monday;
                $sunday = strtotime(date('Y-m-d', $monday) . ' +6 days');
                $start_date = date('Y-m-d', $monday);
                $end_date = date('Y-m-d', $sunday);
            } elseif ($_GET['short'] == 'current_month') {
                $start_date = date('Y-m-01 00:00:00');
                $end_date = date('Y-m-t 23 23:59:00');
            } elseif ($_GET['short'] == '30') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_GET['short'] == '90') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-90 days'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_GET['short'] == 'cwtd') {
                $start_date = date('Y-m-d 00:00:00', strtotime('last monday'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_GET['short'] == 'cqtd') {
                $current_month = date('m');
                $current_year = date('Y');

                if ($current_month >= 1 && $current_month <= 3) {
                    $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-January-' . $current_year)
                    ); // timestamp or 1-Januray 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-April 12:00:00 AM means end of 31 March
                } elseif ($current_month >= 4 && $current_month <= 6) {
                    $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-April-' . $current_year)
                    ); // timestamp or 1-April 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-July 12:00:00 AM means end of 30 June
                } elseif ($current_month >= 7 && $current_month <= 9) {
                    $start_date = $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-July-' . $current_year)
                    ); // timestamp or 1-July 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-October 12:00:00 AM means end of 30 September
                } elseif ($current_month >= 10 && $current_month <= 12) {
                    $start_date = $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-October-' . $current_year)
                    ); // timestamp or 1-October 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
                }
            } elseif ($_GET['short'] == 'cmtd') {
                $start_date = date('Y-m-01 00:00:00');
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_GET['short'] == 'cytd') {
                $start_date = date('Y-01-01 00:00:00');
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_GET['short'] == 'yesterday') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $end_date = date('Y-m-d 23:59:00', strtotime('-1 days'));
            } elseif ($_GET['short'] == 'last_month') {
                $start_date = date('Y-m-01', strtotime('last month'));
                $end_date = date('Y-m-t', strtotime('last month'));
            } elseif ($_GET['short'] == 'current_year') {
                $start_date = date('Y-01-01');
                $end_date = date('Y-12-01');
            } elseif ($_GET['short'] == 'last_year') {
                $start_date = date('Y-01-01', strtotime('last year'));
                $end_date = date('Y-12-01', strtotime('last year'));
            } elseif ($_GET['short'] == 'date') {
                if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                    //$date = DateTime::createFromFormat('d.m.Y', $_GET['start_date']);
                    //$start_date= $date->format('Y-m-d');

                    //echo $_GET['start_date'].'-'.$_GET['end_date']; die;
                    $start_date = date('Y-m-d', strtotime($_GET['start_date']));
                    //$date1 = DateTime::createFromFormat('d.m.Y', $_GET['end_date']);
                    //$end_date= $date1->format('Y-m-d');
                    $end_date = date('Y-m-d', strtotime($_GET['end_date']));
                }
            } elseif ($_GET['short'] == 'all') {
            } else {
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d');
            }
            if (!empty($start_date) && !empty($end_date)) {
                $whr = [
                    'DATE(st_booking.booking_time) >=' => $start_date,
                    'DATE(st_booking.booking_time) <=' => $end_date,
                ];
                $where = $where + $whr;
            }
        }

        if (!empty($_GET['status'])) {
            if ($_GET['status'] == 'upcoming') {
                $td = date('Y-m-d');
                $whr = [
                    'DATE(st_booking.booking_time) >=' => $td,
                    'st_booking.status' => 'confirmed',
                ];
                $where = $where + $whr;
            } elseif ($_GET['status'] == 'recent') {
                /* $whr = array('st_booking.status' => 'completed');
                 $where=$where+$whr;*/
                $td = date('Y-m-d H:i:s');
                $whr1 =
                    '(st_booking.status="completed" OR st_booking.status="cancelled" OR (st_booking.status="confirmed" AND st_booking.booking_time<= "' .
                    $td .
                    '"))';
            } elseif ($_GET['status'] == 'cancelled') {
                $whr1 =
                    '(st_booking.status="cancelled" OR st_booking.status="no_show")';
            }
        }
        if (!empty($_POST['search']) && $_POST['search'] != 'undefined') {
            $search = $_POST['search'];
        }
        if (!empty($whr1)) {
            $this->db->where($whr1);
        }

        /*if(!empty($_POST['search'])){ 
			$whrr="(us2.first_name LIKE '%".$_POST['search']."%' OR us2.last_name LIKE '%".$_POST['search']."%' OR us2.email LIKE '%".$_POST['search']."%' OR st_booking.fullname LIKE '%".$_POST['search']."%')";
		 	 $this->db->where($whrr);
		 	}*/
        $totalcount = $this->user->getAllbookinglist(
            $where,
            0,
            0,
            'st_booking.id',
            'DESC',
            $search
        );
        //echo $this->db->last_query();
        //die;
        if (!empty($totalcount)) {
            $total = count($totalcount);
        } else {
            $total = 0;
        }

        $limit = isset($_POST['limit']) ? $_POST['limit'] : PER_PAGE10; //PER_PAGE10
        $url = 'merchant/getbokkinglist_ajax';
        $segment = 3;
        $page = mypaging($url, $total, $segment, $limit);

        $pagination = '';
        if ($this->pagination->create_links()) {
            $pagination = $this->pagination->create_links();
        }

        if (!empty($_POST['orderby'])) {
            $ordrer = $_POST['orderby'];
        } else {
            $ordrer = 'st_booking.booking_time';
        }
        if (!empty($_POST['shortby'])) {
            $short = $_POST['shortby'];
        } else {
            $short = 'desc';
        }

        if (!empty($whr1)) {
            $this->db->where($whr1);
        }

        /*if(!empty($_POST['search'])){ 
			$whrr="(us2.first_name LIKE '%".$_POST['search']."%' OR us2.last_name LIKE '%".$_POST['search']."%' OR us2.email LIKE '%".$_POST['search']."%' OR st_booking.fullname LIKE '%".$_POST['search']."%')";
		 	 $this->db->where($whrr);
		 	}*/
        $booking = $this->user->getAllbookinglist(
            $where,
            $page['per_page'],
            $page['offset'],
            $ordrer,
            $short,
            $search
        );

        //  echo $this->db->last_query();
        //   die;
        if (!empty($booking)) {
            $html = '';
            foreach ($booking as $row) {
                if ($row->profile_pic != '') {
                    $usimg = getimge_url(
                        'assets/uploads/users/' . $row->user_id . '/',
                        'icon_' . $row->profile_pic,
                        'png'
                    );
                    $usimgw = getimge_url(
                        'assets/uploads/users/' . $row->user_id . '/',
                        'icon_' . $row->profile_pic,
                        'webp'
                    );
                } else {
                    $usimg = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                    $usimgw = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                }

                if ($row->emp_pic != '') {
                    $empimg = getimge_url(
                        'assets/uploads/employee/' . $row->employee_id . '/',
                        'icon_' . $row->emp_pic,
                        'png'
                    );
                    $empimgw = getimge_url(
                        'assets/uploads/employee/' . $row->employee_id . '/',
                        'icon_' . $row->emp_pic,
                        'webp'
                    );
                } else {
                    $empimg = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                    $empimgw = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                }

                $time = new DateTime($row->booking_time);
                $date = $time->format('d.m.Y');
                $time = $time->format('H:i');

                $up_time = new DateTime($row->updated_on);
                $action_date = $up_time->format('d.m.Y');

                if ($row->booking_type == 'guest') {
                    $us_name = $row->fullname;
                } else {
                    $us_name = $row->first_name . ' ' . $row->last_name;
                }

                $bookId = url_encode($row->id);
                $userids = url_encode($row->user_id);
                $cls = '';
                $icon = '';
                $updated_time = '';
                if ($row->status == 'confirmed') {
                    $cls = 'conform';
                    $updated_time = 'am ' . $action_date;
                } elseif ($row->status == 'cancelled') {
                    $updated_time = 'am ' . $action_date;
                    $cls = 'cencel';
                } elseif ($row->status == 'completed') {
                    $cls = 'completed';
                    $icon = ' <i class="fa fa-check" aria-hidden="true"></i>';
                    $updated_time = 'am ' . $action_date;
                } elseif ($row->status == 'no show') {
                    $cls = 'cencel';
                    $updated_time = 'am ' . $action_date;
                }
                $book_detail = $this->user->select(
                    'st_booking_detail',
                    'id,booking_id,service_id,service_name',
                    ['booking_id' => $row->id],
                    '',
                    'id',
                    'ASC'
                );
                $ser_nm = '';

                if (!empty($book_detail)) {
                    $ij = 0;
                    foreach ($book_detail as $serv) {
                        $ij++;
                        $sub_name = get_subservicename($serv->service_id);

                        if (count($book_detail) == $ij) {
                            if ($sub_name == $serv->service_name) {
                                $ser_nm .= $serv->service_name;
                            } else {
                                $ser_nm .=
                                    $sub_name . ' - ' . $serv->service_name;
                            }
                        } else {
                            if ($sub_name == $serv->service_name) {
                                $ser_nm .= $serv->service_name . ', ';
                            } else {
                                $ser_nm .=
                                    $sub_name .
                                    ' - ' .
                                    $serv->service_name .
                                    ', ';
                            }
                        }
                    }
                }

                $dropdown = '';
                $price_start_option = '';

                $dropdown .=
                    '<a href="#" id="' .
                    $bookId .
                    '" data-uid="' .
                    $userids .
                    '" class="dropdown-item color666 font-size-14 fontfamily-regular deleteBooking" data-url="' .
                    base_url(
                        'rebook/delete_booking/' . $bookId . '/booking_listing'
                    ) .
                    '">' .
                    $this->lang->line('Delete') .
                    '</a>';

                if (
                    strtotime($row->booking_time) >
                        strtotime(date('Y-m-d H:i:s')) &&
                    $row->status != 'cancelled' &&
                    ($row->status != 'deleted' || $row->booking_type == 'guest')
                ) {
                    $dropdown .=
                        '<div id="divStatus_' .
                        $row->id .
                        '"><a class="dropdown-item color666 font-size-14 fontfamily-regular" href="javascript:void(0)" id="cancel_booking" data-click="list" data-uid="' .
                        $userids .
                        '" data-id="' .
                        $bookId .
                        '">' .
                        $this->lang->line('Cancel') .
                        '</a></div>';
                } /*<a class="dropdown-item color666 font-size-14 fontfamily-regular booking_cancels" href="#" data-toggle="modal" data-target="#service-cencel-table" id="'.$bookId.'">Cancel</a>*/
                if (
                    strtotime($row->booking_time) <
                        strtotime(date('Y-m-d H:i:s')) &&
                    $row->status != 'completed'
                ) {
                    $dropdown .=
                        '<div id="divSta_<?php echo $row->id; ?>"><a class="dropdown-item color666 font-size-14 fontfamily-regular complete_book" href="' .
                        base_url('checkout/process/' . $bookId) .
                        '">' .
                        $this->lang->line('Complete') .
                        '</a></div>';
                }
                if (
                    $row->status != 'no show' &&
                    $row->status != 'cancelled' &&
                    $row->status != 'completed'
                ) {
                    $dropdown .=
                        '<div id="divSta_' .
                        $row->id .
                        '"><a class="dropdown-item color666 font-size-14 fontfamily-regular noshow_book" href="javascript:void(0)" data-id="' .
                        $bookId .
                        '" data-click="list" id="noshow_booking">' .
                        $this->lang->line('No_show') .
                        '</a></div>';
                } /*<a class="dropdown-item color666 font-size-14 fontfamily-regular noshow_book" href="#" data-toggle="modal" data-target="#service-noshow-table" id="'.$bookId.'">No Show</a>*/
                if (
                    strtotime($row->booking_time) >
                        strtotime(date('Y-m-d H:i:s')) &&
                    $row->status != 'completed' &&
                    $row->status != 'cancelled' &&
                    $row->status != 'no show'
                ) {
                    $dropdown .=
                        '<div id="divRes_' .
                        $row->id .
                        '"><a class="dropdown-item color666 font-size-14 fontfamily-regular reSchedule_book" href="#" data-toggle="modal" data-eid="' .
                        url_encode($row->employee_id) .
                        '" id="' .
                        $bookId .
                        '">' .
                        $this->lang->line('Reschedule') .
                        '</a></div>';
                }

                if ($row->price_start_option == 'ab' && $row->status != 'completed') {
                    $price_start_option =
                        $row->price_start_option . ' ' . $row->total_price;
                } else {
                    $price_start_option = $row->total_price;
                }
                // <source srcset="'.$usimgw.'" type="image/webp">
                //  <source srcset="'.$usimgw.'" type="image/webp">
                $html .=
                    '<tr>
                      <td class="font-size-14 color666 fontfamily-regular booking_row" id="' .
                    $bookId .
                    '">
                        
                        <picture>
						
                         <img src="' .
                    $usimg .
                    '" style="border-radius: 50%;" class="mr-3 width30" type="image/png">
                          </picture>
                        <p class="overflow_elips mb-0 display-ib">' .
                    $us_name .
                    '</p>
                      </td>
                      <td class="text-left font-size-14 color666 fontfamily-regular booking_row" id="' .
                    $bookId .
                    '">
                        
                        <picture>
                        <img src="' .
                    $empimg .
                    '" style="border-radius: 50%;" class="mr-3 width30">
                          </picture>
                        <p class="overflow_elips mb-0 display-ib">' .
                    $row->emp_fn .
                    '</p>
                      </td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row" id="' .
                    $bookId .
                    '">' .
                    $date .
                    '</td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row" id="' .
                    $bookId .
                    '">' .
                    $time .
                    ' Uhr</td>
					  <td class="text-center font-size-14 color666 fontfamily-regular booking_row" id="' .
                    $bookId .
                    '">

							<p class="overflow_elips mb-0 tooltipnew" data-toggle="tooltip" data-placement="top" title="" data-original-title="' .
                    rtrim($ser_nm, ',') .
                    '">' .
                    rtrim($ser_nm, ',') .
                    '</p>							

                      </td>
                      <td class="text-center font-size-14 color666 fontfamily-regular
				   booking_row" id="' .
                    $bookId .
                    '">' .
                    $price_start_option .
                    ' €</td>
                      <td class="text-center font-size-14 color666 fontfamily-regular booking_row" 
				  id="' .
                    $bookId .
                    '">' .
                    ($row->total_minutes + $row->total_buffer) .
                    ' Min.</td>                      
                      <td class="text-center font-size-14 fontfamily-regular booking_row" id="' .
                    $bookId .
                    '">                        
                        <span id="CssStatus_' .
                    $row->id .
                    '" class="' .
                    $cls .
                    '">' .
                    ucfirst(
                        $this->lang->line(
                            'book_status_' . str_replace(' ', '_', $row->status)
                        )
                    ) .
                    $icon .
                    '</span>
                         <span class="font-size-10 color666 fontfamily-regular display-b">' .
                    $updated_time .
                    '</span>
                      </td>
                      <td class="text-center">                        
                        <div class="dropdown">
                          <div class="" data-toggle="dropdown">
                            <img src="' .
                    base_url('assets/frontend/images/table-more-icon.svg') .
                    '">
                          </div>
                          <div class="dropdown-menu widthfit">
                             <a class="dropdown-item color666 font-size-14 fontfamily-regular booking_row" href="#" id="' .
                    $bookId .
                    '">' .
                    $this->lang->line('View') .
                    '</a>' .
                    $dropdown .
                    '                         
                             </div>
                        </div>                      
                      </td>
                    </tr>';
                //get_servicename($row->id) removed from service name
            }
        } else {
            $html =
                '<tr><td colspan="12"><div class="text-center" 
				style="padding-bottom:20px;padding-top: 50px;">
					  <img src="' .
                base_url('assets/frontend/images/no_listing.png') .
                '"><p style="margin-top: 20px;">' .
                $this->lang->line('you_dont_have_any_booking_my') .
                '</p></div></td>
                    </tr>';
        }
        echo json_encode([
            'success' => '1',
            'msg' => '',
            'html' => $html,
            'pagination' => $pagination,
        ]);
        die();
    }

    //**** employee Unavailablity ****//
    function employee_unavailablity()
    {
        //sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssprint_r($_POST); die;
        if (
            empty($this->session->userdata('st_userid')) &&
            $this->session->userdata('access') != 'marchant'
        ) {
            echo json_encode(['success' => 0, 'url' => base_url('auth/login')]);
            die();
        } else {
            $dayName = date('l', strtotime($_POST['date']));
            $dayName = strtolower($dayName);
            $_POST['date'] = date('Y-m-d', strtotime($_POST['date']));

            if (!empty($_POST['uid'])) {
                $uid = $_POST['uid'];
            }
            //else $uid                     = url_decode($_POST['uid']);

            $merchantTime = $this->user->select_row(
                'st_availability',
                'days,type,starttime,endtime',
                [
                    'user_id' => $this->session->userdata('st_userid'),
                    'days' => strtolower($dayName),
                ]
            );
            //echo $this->db->last_query();
            if (!empty($merchantTime) && $merchantTime->type == 'open') {
                //print_r($_POST); die;
                if (!empty($_POST['block_id'])) {
                    if (isset($_POST['starttime'])) {
                        $startTime = $_POST['date'] . ' ' . $_POST['starttime'];
                    }

                    if (isset($_POST['endtime'])) {
                        $endTime = $_POST['date'] . ' ' . $_POST['endtime'];
                    }

                    $sql = 'SELECT * FROM `st_booking` WHERE blocked='.$_POST['block_id'].' AND blocked_perent = 0';
                    $query = $this->db->query($sql);
                    $booking = $query->result();

                    $res = $this->user->delete('st_booking', [
                        'blocked' => $_POST['block_id'],
                        'blocked_perent !=' => 0,
                    ]);

                    $update = (array)$booking[0];
                    /* if(!empty($_POST['allday']) && $_POST['allday']=='yes')
			            { 
						  $update['booking_time']    = $_POST['date']." 00:00:00";
						  $update['booking_endtime'] = $_POST['date']." 23:00:00";
						}
						else{*/
                    $update['booking_time'] = $startTime;
                    $update['booking_endtime'] = $endTime;
                    /* }*/

                    unset($update['id']);

                    if (!empty($_POST['allday']) && $_POST['allday'] == 'yes') {
                        $update['blocked_type'] = 'full';
                    } else {
                        $update['blocked_type'] = 'half';
                    }

                    $update['notes'] = $_POST['block_note'];

                    $update['block_for'] = 0;
                    if (
                        !empty($_POST['allemaployee']) &&
                        $_POST['allemaployee'] == 'yes'
                    ) {
                        $sql =
                                'SELECT id FROM `st_users` WHERE access="employee" AND merchant_id=' .
                                $this->session->userdata('st_userid') .
                                ' AND status !="deleted"';

                            $query = $this->db->query($sql);

                            $employeIds = $query->result();

                            $uid = [];
                            foreach ($employeIds as $eids) {
                                $uid[] = url_encode($eids->id);
                            }
                            $update['block_for'] = 1;
                    }
                    $ii = 0;
                    foreach ($uid as $eid) {
                        $update['employee_id'] = url_decode($eid);
                        if ($ii == 0) {
                            $res = $this->user->update('st_booking', $update, [
                                'blocked' => $_POST['block_id'],
                            ]);
                            $update['blocked'] = $_POST['block_id'];
                            $update['blocked_perent'] = $_POST['block_id'];
                        } else {
                            $res = $this->user->insert(
                                'st_booking',
                                $update
                            );
                        }
                        $ii++;
                    }
                    $this->session->set_flashdata(
                        'success',
                        'Blockierung erfolgreich bearbeitet'
                    );
                    if ($res) {
                        echo json_encode(['success' => 1, 'url' => '1']);
                        die();
                    } else {
                        echo json_encode(['success' => 1, 'url' => '2']);
                        die();
                    }
                } else {
                    if (
                        ($merchantTime->starttime <= $_POST['starttime'] &&
                            $merchantTime->endtime >= $_POST['endtime']) ||
                        (!empty($_POST['allday']) && $_POST['allday'] == 'yes')
                    ) {
                        if (isset($_POST['starttime'])) {
                            $startTime =
                                $_POST['date'] . ' ' . $_POST['starttime'];
                        }

                        if (isset($_POST['endtime'])) {
                            $endTime = $_POST['date'] . ' ' . $_POST['endtime'];
                        }

                        if (
                            !empty($_POST['allemaployee']) &&
                            $_POST['allemaployee'] == 'yes'
                        ) {
                            $sql =
                                'SELECT id FROM `st_users` WHERE access="employee" AND merchant_id=' .
                                $this->session->userdata('st_userid') .
                                ' AND status !="deleted"';

                            $query = $this->db->query($sql);

                            $employeIds = $query->result();

                            //print_r($employeIds); die;

                            $blockedId = 0;
                            $blockedIds = [];
                            if (!empty($employeIds)) {
                                $startTimes = [$startTime];
                                $endTimes = [$endTime];
                                if ( !empty($_POST['block_more']) && $_POST['block_more'] == 'yes') {
                                    for ($ii = 1; $ii <= $_POST['block_more_period']; $ii++) {
                                        array_push(
                                            $startTimes,
                                            date('Y-m-d H:i:s', strtotime($startTime . ' + ' . $ii .' week'))
                                        );
                                        array_push(
                                            $endTimes,
                                            date('Y-m-d H:i:s', strtotime($endTime . ' + ' . $ii .' week'))
                                        );
                                    }

                                    if ($_POST['block_more_period'] == 0) {
                                        $ii = 1;
                                        while (true) {
                                            if (strtotime($startTime . ' + ' . $ii .' week') > strtotime($_POST['block_specific_date'] . '+1 day')) {
                                                break;
                                            }
                                            array_push(
                                                $startTimes,
                                                date('Y-m-d H:i:s', strtotime($startTime . ' + ' . $ii .' week'))
                                            );
                                            array_push(
                                                $endTimes,
                                                date('Y-m-d H:i:s', strtotime($endTime . ' + ' . $ii .' week'))
                                            );
                                            $ii++;
                                        }
                                    }
                                }
                                
                                foreach ($employeIds as $eids) {
                                    $insertArr = [];
                                    $insertArr['employee_id'] = $eids->id;
                                    $insertArr[
                                        'merchant_id'
                                    ] = $this->session->userdata('st_userid');

                                    if (
                                        !empty($_POST['allday']) &&
                                        $_POST['allday'] == 'yes'
                                    ) {
                                        /*$insertArr['booking_time']    = $_POST['date']." 00:00:00";
                                         $insertArr['booking_endtime'] = $_POST['date']." 23:00:00";*/
                                        $insertArr['blocked_type'] = 'full';
                                    }
                                    /*else{*/
                                    $insertArr['booking_time'] = $startTime;
                                    $insertArr['booking_endtime'] = $endTime;
                                    /*}*/
                                    
                                    $insertArr['block_for'] = 1;

                                    //~ $insertArr['booking_endtime'] = $endTime;

                                    $insertArr['notes'] = $_POST['block_note'];

                                    $insertArr['booking_type'] = 'self';
                                    $insertArr['created_on'] = date(
                                        'Y-m-d H:i:s'
                                    );
                                    $insertArr[
                                        'created_by'
                                    ] = $this->session->userdata('st_userid');

                                    for ($ii = 0; $ii < count($startTimes); $ii++) {
                                        $insertArr['booking_time'] = $startTimes[$ii];
                                        $insertArr['booking_endtime'] = $endTimes[$ii];
                                        if ($blockedId) {
                                            $insertArr['blocked'] = $blockedIds[$ii];
                                            $insertArr['blocked_perent'] = $blockedIds[$ii];
                                        }
                                        $res = $this->user->insert(
                                            'st_booking',
                                            $insertArr
                                        );
    
                                        if ($blockedId == 0) {
                                            $blockedIds[] = $res;
                                            $this->db->where('id', $res);
                                            $this->db->update('st_booking', [
                                                'blocked' => $res,
                                            ]);
                                        }
                                    }
                                    $blockedId = 1;
                                }
                            }
                            $this->session->set_flashdata(
                                'success',
                                'Zeitraum erfolgreich blockiert'
                            );
                            echo json_encode(['success' => 1, 'url' => '']);
                            die();
                        } else {
                            $blockedId = 0;
                            $blockedIds = [];
                            $startTimes = [$startTime];
                            $endTimes = [$endTime];
                            if ( !empty($_POST['block_more']) && $_POST['block_more'] == 'yes') {
                                for ($ii = 1; $ii <= $_POST['block_more_period']; $ii++) {
                                    array_push(
                                        $startTimes,
                                        date('Y-m-d H:i:s', strtotime($startTime . ' + ' . $ii .' week'))
                                    );
                                    array_push(
                                        $endTimes,
                                        date('Y-m-d H:i:s', strtotime($endTime . ' + ' . $ii .' week'))
                                    );
                                }

                                if ($_POST['block_more_period'] == 0) {
                                    $ii = 1;
                                    while (true) {
                                        if (strtotime($startTime . ' + ' . $ii .' week') > strtotime($_POST['block_specific_date'] . '+1 day')) {
                                            break;
                                        }
                                        array_push(
                                            $startTimes,
                                            date('Y-m-d H:i:s', strtotime($startTime . ' + ' . $ii .' week'))
                                        );
                                        array_push(
                                            $endTimes,
                                            date('Y-m-d H:i:s', strtotime($endTime . ' + ' . $ii .' week'))
                                        );
                                        $ii++;
                                    }
                                }
                            }

                            foreach ($uid as $eids) {
                                if ($eids) {
                                    $insertArr = [];
                                    $insertArr['employee_id'] = url_decode($eids);
                                    $insertArr[
                                        'merchant_id'
                                    ] = $this->session->userdata('st_userid');

                                    if (
                                        !empty($_POST['allday']) &&
                                        $_POST['allday'] == 'yes'
                                    ) {
                                        /*$insertArr['booking_time']    = $_POST['date']." 00:00:00";
                                        $insertArr['booking_endtime'] = $_POST['date']." 23:00:00";*/
                                        $insertArr['blocked_type'] = 'full';
                                    }
                                    /*else{*/
                                    $insertArr['booking_time'] = $startTime;
                                    $insertArr['booking_endtime'] = $endTime;
                                    /*}*/

                                    //~ $insertArr['booking_time']    = $startTime;
                                    //~ $insertArr['booking_endtime'] = $endTime;

                                    $insertArr['notes'] = $_POST['block_note'];

                                    $insertArr['booking_type'] = 'self';
                                    $insertArr['created_on'] = date('Y-m-d H:i:s');
                                    $insertArr[
                                        'created_by'
                                    ] = $this->session->userdata('st_userid');

                                    for ($ii = 0; $ii < count($startTimes); $ii++) {
                                        $insertArr['booking_time'] = $startTimes[$ii];
                                        $insertArr['booking_endtime'] = $endTimes[$ii];
                                        if ($blockedId) {
                                            $insertArr['blocked'] = $blockedIds[$ii];
                                            $insertArr['blocked_perent'] = $blockedIds[$ii];
                                        }

                                        $res = $this->user->insert(
                                            'st_booking',
                                            $insertArr
                                        );

                                        if ($blockedId == 0) {
                                            $blockedIds[] = $res;
                                            $this->db->where('id', $res);
                                            $this->db->update('st_booking', [
                                                'blocked' => $res,
                                            ]);
                                        }
                                    }
                                    $blockedId = 1;
                                }
                            }
                        }
                        $this->session->set_flashdata(
                            'success',
                            'Zeitraum erfolgreich blockiert'
                        );
                        echo json_encode(['success' => 1, 'url' => '']);
                        die();
                    } else {
                        echo json_encode([
                            'success' => 0,
                            'url' => '',
                            'message' =>
                                'Your selected time is not matched with salon opening time',
                        ]);
                    }
                    die();
                }
            } else {
                echo json_encode([
                    'success' => 0,
                    'url' => '',
                    'message' =>
                        'Der Salon ist an der ausgewählten Zeit bereits geschlossen',
                ]);
            }
            die();
        }
        //print_r($_POST); die;
    }

    //**** Booking Confirm ****//
    public function booking_confirm()
    {
        $id = url_decode($_POST['book_id']);
        $usid = $this->session->userdata('st_userid');
        if (
            $this->user->update(
                'st_booking',
                [
                    'status' => 'completed',
                    'updated_by' => $usid,
                    'updated_on' => date('Y-m-d H:i:s'),
                ],
                ['id' => $id]
            )
        ) {
            $field =
                'user_id,merchant_id,booking_type,total_time,fullname,email as guestemail,book_id,(select first_name from st_users where st_users.id=user_id) as fname,(select email from st_users where st_users.id=user_id) as email,(select last_name from st_users where st_users.id=user_id) as lname,(select business_name from st_users where st_users.id=st_booking.merchant_id) as salon_name,(select notification_status from st_users where st_users.id=st_booking.user_id) as user_notify';
            $info = $this->user->select_row('st_booking', $field, [
                'id' => $id,
            ]);
            if ($info->booking_type == 'guest') {
                $first_name = ucwords($info->fullname);
                $last_name = '';
                $emailsend = $info->guestemail;
            } else {
                $first_name = ucwords($info->fname);
                $last_name = ucwords($info->lname);
                $emailsend = $info->email;
            }

            $message = $this->load->view(
                'email/booking_complete',
                [
                    'fname' => $first_name,
                    'lname' => $last_name,
                    'salon_name' => $info->salon_name,
                    'bookid' => url_encode($id),
                    'book_id' => $info->book_id,
                    'duration' => $info->total_time,
                ],
                true
            );

            $body_msg = str_replace(
                '*salonname*',
                $info->salon_name,
                $this->lang->line('booking_complete_body')
            );
            $MsgTitle = $this->lang->line('booking_complete_title');

            if ($info->booking_type != 'guest' && $info->user_notify != 0) {
                $ress = sendPushNotification($info->user_id, [
                    'body' => $body_msg,
                    'title' => $MsgTitle,
                    'salon_id' => $info->merchant_id,
                    'book_id' => $id,
                    'booking_status' => 'completed',
                    'click_action' => 'BOOKINGDETAIL',
                ]);
            }
            $mail = emailsend(
                $emailsend,
                'styletimer - Buchung abgeschlossen',
                $message,
                'styletimer'
            );

            echo json_encode(['success' => 1, 'id' => $id]);
        } else {
            echo json_encode(['success' => 0, 'id' => '']);
        }
    }
    public function booking_reshedule_old()
    {
        $originalDate = $_POST['chg_date'];
        $newDate = date('Y-m-d', strtotime($originalDate));
        $field =
            'id,merchant_id,booking_time,total_minutes,total_buffer,employee_id';
        $info = $this->user->select_row('st_booking', $field, [
            'id' => url_decode($_POST['reSchedule_id']),
        ]);
        if (!empty($info)) {
            $toDate = date('Y-m-d');
            if ($newDate < $toDate) {
                echo json_encode([
                    'success' => 0,
                    'msg' => 'Please select valid date',
                ]);
            } else {
                $date = $newDate;
                $nameOfDay = date('l', strtotime($date));
                $totalMinutes = $info->total_minutes + $info->total_buffer;
                $times = strtotime($_POST['chg_time']);
                $newTime = date(
                    'H:i',
                    strtotime('+ ' . $totalMinutes . ' minutes', $times)
                );

                $check = $this->user->select_row('st_availability', 'id', [
                    'user_id' => $info->employee_id,
                    'days' => strtolower($nameOfDay),
                    'type' => 'open',
                    'starttime <=' => $_POST['chg_time'],
                    'endtime >=' => $newTime,
                ]);
                if (!empty($check)) {
                    $bk_time = $newDate . ' ' . $_POST['chg_time'];
                    $newtimestamp = strtotime(
                        '' . $bk_time . ' + ' . $totalMinutes . ' minute'
                    );
                    $book_end = date('Y-m-d H:i:s', $newtimestamp);

                    $upd['booking_time'] = $bk_time;
                    $upd['booking_endtime'] = $book_end;

                    //check booking overlapping
                    $whereAS =
                        '((booking_time>="' .
                        $bk_time .
                        '" AND booking_time<="' .
                        $book_end .
                        '") OR (booking_endtime>="' .
                        $bk_time .
                        '" AND booking_endtime<="' .
                        $book_end .
                        '") OR (booking_time<="' .
                        $bk_time .
                        '" AND booking_endtime>="' .
                        $book_end .
                        '") OR (booking_time>="' .
                        $bk_time .
                        '" AND booking_endtime<="' .
                        $book_end .
                        '"))';

                    $this->db->where($whereAS);
                    $check = $this->user->select_row('st_booking', 'id', [
                        'employee_id' => $info->employee_id,
                        'id !=' => url_decode($_POST['reSchedule_id']),
                    ]);
                    // echo $this->db->last_query(); die;
                    //check booking overlapping
                    if (empty($check)) {
                        if (
                            $this->user->update('st_booking', $upd, [
                                'id' => url_decode($_POST['reSchedule_id']),
                            ])
                        ) {
                            $field =
                                'st_booking.id,user_id,booking_time,total_time,st_booking.merchant_id,book_id,first_name,last_name,st_users.email,(select business_name from st_users where st_users.id = st_booking.merchant_id) as salon_name,(select email_text from st_users where st_users.id = st_booking.merchant_id) as email_text,st_users.notification_status';
                            $info = $this->user->join_two(
                                'st_booking',
                                'st_users',
                                'user_id',
                                'id',
                                [
                                    'st_booking.id' => url_decode(
                                        $_POST['reSchedule_id']
                                    ),
                                ],
                                $field
                            );
                            if (!empty($info)) {
                                $body_msg =
                                    'Your booking has been reshedule successfully';
                                $MsgTitle = 'Styletimer-Reshedule Booking';

                                if ($info[0]->notification_status != 0) {
                                    sendPushNotification($info[0]->user_id, [
                                        'body' => $body_msg,
                                        'title' => $MsgTitle,
                                        'salon_id' => $info[0]->merchant_id,
                                        'book_id' => url_decode(
                                            $_POST['reSchedule_id']
                                        ),
                                        'booking_status' => 'reschedule',
                                    ]);
                                }

                                $time = new DateTime($info[0]->booking_time);
                                $date = $time->format('d/m/Y');
                                $time = $time->format('H:i');
                                $message = $this->load->view(
                                    'email/reshedule_booking',
                                    [
                                        'fname' => ucwords(
                                            $info[0]->first_name
                                        ),
                                        'lname' => ucwords($info[0]->last_name),
                                        'salon_name' => $info[0]->salon_name,
                                        'book_id' => $info[0]->book_id,
                                        'service_name' => get_servicename(
                                            $info[0]->id
                                        ),
                                        'booking_date' => $date,
                                        'booking_time' => $time,
                                        'email_text' => $info[0]->email_text,
                                        'duration' => $info[0]->total_time,
                                    ],
                                    true
                                );
                                $mail = emailsend(
                                    $info[0]->email,
                                    $this->lang->line("styletimer_reschedule_booking"),
                                    $message,
                                    'styletimer'
                                );
                            }
                            echo json_encode(['success' => 1]);
                        } else {
                            echo json_encode([
                                'success' => 0,
                                'msg' => 'Sorry unable to process',
                            ]);
                        }
                    } else {
                        echo json_encode([
                            'success' => 0,
                            'msg' =>
                                'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar',
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => 0,
                        'msg' =>
                            'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar',
                    ]);
                }
            }

            //date("h:i");
        }
    }

    //**** Booking reshedule ****//
    public function booking_reshedule()
    {
        $originalDate = $_POST['chg_date'];
        $newDate = date('Y-m-d', strtotime($originalDate));
        $field =
            'id,merchant_id,booking_time,total_minutes,booking_type,email,fullname,total_buffer,employee_id,(SELECT notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as additional_notification_time';

        $info = $this->user->select_row('st_booking', $field, [
            'id' => url_decode($_POST['reSchedule_id']),
        ]);
        $oldDate = '';

        if (!empty($info)) {
            $toDate = date('Y-m-d');
            $oldDate = $info->booking_time;

            if ($newDate < $toDate) {
                echo json_encode([
                    'success' => 0,
                    'msg' => 'Please select valid date',
                ]);
            } else {
                $date = $newDate;
                $nameOfDay = date('l', strtotime($date));
                $totalMinutes = 0; //$info->total_minutes+$info->total_buffer;
                $times = strtotime($_POST['chg_time']);
                $newTime = date(
                    'H:i',
                    strtotime('+ ' . $totalMinutes . ' minutes', $times)
                );
                $dayName = strtolower($nameOfDay);

                //~ $select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND ((`starttime`<='".$_POST['chg_time']."' AND endtime>='".$newTime."') OR (`starttime_two`<='".$_POST['chg_time']."' AND endtime_two>='".$newTime."')) AND user_id=".$info->employee_id."";

                //~ $check= $this->user->custome_query($select123,'row');

                //~ if(!empty($check)){

                $bk_time = $newDate . ' ' . $_POST['chg_time'];
                // $newtimestamp = strtotime(''.$bk_time.' + '.$totalMinutes.' minute');
                //$book_end     = date('Y-m-d H:i:s', $newtimestamp);

                //~ $whereAS      = '((booking_time>="'.$bk_time.'" AND booking_time<="'.$book_end.'") OR (booking_endtime>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'") OR (booking_time<="'.$bk_time.'" AND booking_endtime>="'.$book_end.'") OR (booking_time>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'")) AND status!="cancelled"';

                //~ $this->db->where($whereAS);
                //~ $check = $this->user->select_row('st_booking','id',array('employee_id'=>$info->employee_id,'id !='=>url_decode($_POST['reSchedule_id'])));

                $sqlForservice =
                    "SELECT `st_booking_detail`.`id`,`st_booking_detail`.`user_id`,`st_booking_detail`.`buffer_time`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`,`parent_service_id`,st_booking_detail.duration,st_booking_detail.service_type as stype,st_booking_detail.setuptime,st_booking_detail.processtime,st_booking_detail.finishtime,`st_merchant_category`.`price`, `st_merchant_category`.`discount_price`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='" .
                    $dayName .
                    "') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = " .
                    url_decode($_POST['reSchedule_id']) .
                    ' ORDER BY st_booking_detail.id';

                $booking_detail = $this->user->custome_query(
                    $sqlForservice,
                    'result'
                );

                $total_price = $total_buffer = $total_min = $total_dis = 0;

                if (!empty($booking_detail)) {
                    $timeArray = [];
                    $ikj = 0;
                    $strtodatyetime = $bk_time;

                    $availabeTime = $this->user->select_row('st_availability', '*', [
                        'user_id' => $this->session->userdata('st_userid'),
                        'days' => $dayName,
                        'type' => 'open'
                    ]);

                    foreach ($booking_detail as $row) {
                        $timeArray[$ikj] = new stdClass();
                        $bkstartTime = $strtodatyetime;
                        $timeArray[$ikj]->start = $bkstartTime;

                        if ($row->stype == 1) {
                            $total_min = $row->duration + $total_min;

                            $bkEndTime = date(
                                'Y-m-d H:i:s',
                                strtotime(
                                    '' .
                                        $bkstartTime .
                                        ' + ' .
                                        $row->setuptime .
                                        ' minute'
                                )
                            );
                            $timeArray[$ikj]->end = $bkEndTime;
                            $ikj++;

                            $finishStart = date(
                                'Y-m-d H:i:s',
                                strtotime(
                                    '' .
                                        $bkEndTime .
                                        ' + ' .
                                        $row->processtime .
                                        ' minute'
                                )
                            );
                            $timeArray[$ikj] = new stdClass();
                            $timeArray[$ikj]->start = $finishStart;

                            $finishEnd = date(
                                'Y-m-d H:i:s',
                                strtotime(
                                    '' .
                                        $finishStart .
                                        ' + ' .
                                        $row->finishtime .
                                        ' minute'
                                )
                            );
                            $timeArray[$ikj]->end = $finishEnd;
                            $ikj++;

                            $strtodatyetime = $finishEnd;
                        } else {
                            $total_buffer = $row->buffer_time + $total_buffer;
                            $totalMin = $row->duration;

                            $total_min = $total_min + $row->duration - $row->buffer_time;

                            $bkEndTime = date(
                                'Y-m-d H:i:s',
                                strtotime(
                                    '' .
                                        $bkstartTime .
                                        ' + ' .
                                        $totalMin .
                                        ' minute'
                                )
                            );
                            $timeArray[$ikj]->end = $bkEndTime;
                            $ikj++;

                            $strtodatyetime = $bkEndTime;
                        }

                        if ($row->parent_service_id) {
                            $pstime = $this->user->select(
                                'st_offer_availability',
                                'starttime,endtime,type,days',
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

                        if (!empty($row->type) && $row->type == 'open') {
                            if (
                                $row->starttime <=
                                    date('H:i:s', strtotime($bk_time)) &&
                                $row->endtime >=
                                    date('H:i:s', strtotime($bk_time))
                            ) {
                                if (!empty($row->discount_price)) {
                                    $total_dis =
                                        $row->price -
                                        $row->discount_price +
                                        $total_dis;
                                    $total_price =
                                        $row->discount_price + $total_price;
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

                    $resultCheckSlot = checkTimeSlotsMerchant(
                        $timeArray,
                        $info->employee_id,
                        $info->merchant_id,
                        $totalMinutes,
                        $info->id
                    );

                    if ($resultCheckSlot == true) {
                        $min = $total_buffer + $total_min;
                        $newtimestamp = strtotime(
                            '' . $bk_time . ' + ' . $min . ' minute'
                        );
                        $book_end = date('Y-m-d H:i:s', $newtimestamp);
                        //notification set time
                        $notif_time = $info->notification_time;
                        $ad_notif_time = $info->additional_notification_time;

                        $timestamp = strtotime($bk_time);
                        $time = $timestamp - $notif_time * 60 * 60;
                        $ad_time = $timestamp - $ad_notif_time * 60 * 60;
                        // Date and time after subtraction
                        $notif_date = date('Y-m-d H:i:s', $time);
                        if ($ad_notif_time != '0') {
                            $ad_notif_date = date('Y-m-d H:i:s', $ad_time);
                            $book_Arr[
                                'additional_notification_date'
                            ] = $ad_notif_date;
                        }

                        $book_Arr['booking_time'] = $bk_time;
                        $book_Arr['booking_endtime'] = $book_end;
                        $book_Arr['total_minutes'] = $total_min;
                        $book_Arr['total_buffer'] = $total_buffer;
                        $book_Arr['total_time'] = $total_buffer + $total_min;
                        $book_Arr['total_price'] = $total_price;
                        $book_Arr['total_discount'] = $total_dis;
                        $book_Arr['pay_status'] = 'cash';
                        $book_Arr['status'] = 'confirmed';
                        $book_Arr['notification_date'] = $notif_date;
                        $book_Arr['updated_on'] = date('Y-m-d H:i:s');
                        $book_Arr['updated_by'] = $this->session->userdata(
                            'st_userid'
                        );

                        if (
                            $this->user->update('st_booking', $book_Arr, [
                                'id' => url_decode($_POST['reSchedule_id']),
                            ])
                        ) {
                            //$this->user->delete('st_booking_detail',array('booking_id' => url_decode($_POST['reSchedule_id'])));
                            $boojkstartTime = $bk_time;

                            foreach ($booking_detail as $row) {
                                $detail_Arr = [];
                                //$detail_Arr['booking_id']       = url_decode($_POST['reSchedule_id']);
                                $detail_Arr['mer_id'] = $info->merchant_id;
                                $detail_Arr['emp_id'] = $info->employee_id;
                                $detail_Arr['service_type'] = $row->stype;
                                if ($row->buffer_time > 0)
                                    $detail_Arr['has_buffer']   = 1;
                                if ($row->stype == 1) {
                                    $detail_Arr['setuptime'] = $row->setuptime;
                                    $detail_Arr['processtime'] =
                                        $row->processtime;
                                    $detail_Arr['finishtime'] =
                                        $row->finishtime;
                                    $detail_Arr[
                                        'setuptime_start'
                                    ] = $boojkstartTime;

                                    $setuEnd = date(
                                        'Y-m-d H:i:s',
                                        strtotime(
                                            '' .
                                                $boojkstartTime .
                                                ' + ' .
                                                $row->setuptime .
                                                ' minute'
                                        )
                                    );
                                    $finishStart = date(
                                        'Y-m-d H:i:s',
                                        strtotime(
                                            '' .
                                                $setuEnd .
                                                ' + ' .
                                                $row->processtime .
                                                ' minute'
                                        )
                                    );
                                    $finishEnd = date(
                                        'Y-m-d H:i:s',
                                        strtotime(
                                            '' .
                                                $finishStart .
                                                ' + ' .
                                                $row->finishtime .
                                                ' minute'
                                        )
                                    );

                                    $detail_Arr['setuptime_end'] = $setuEnd;
                                    $detail_Arr[
                                        'finishtime_start'
                                    ] = $finishStart;
                                    $detail_Arr['finishtime_end'] = $finishEnd;

                                    $boojkstartTime = $finishEnd;
                                } else {
                                    $totalMin =
                                        $row->duration;
                                    $setuEnd = date(
                                        'Y-m-d H:i:s',
                                        strtotime(
                                            '' .
                                                $boojkstartTime .
                                                ' + ' .
                                                $totalMin .
                                                ' minute'
                                        )
                                    );
                                    $detail_Arr[
                                        'setuptime_start'
                                    ] = $boojkstartTime;
                                    $detail_Arr['setuptime_end'] = $setuEnd;

                                    $boojkstartTime = $setuEnd;
                                }

                                $detail_Arr['service_id'] = $row->service_id;
                                if (!empty($row->name)) {
                                    $detail_Arr['service_name'] = $row->name;
                                } else {
                                    $detail_Arr['service_name'] =
                                        $row->category_name;
                                }

                                $detail_Arr['price'] = 0;
                                $detail_Arr['discount_price'] = 0;
                                if (
                                    !empty($row->type) &&
                                    $row->type == 'open'
                                ) {
                                    if (
                                        $row->starttime <=
                                            date(
                                                'H:i:s',
                                                strtotime($bk_time)
                                            ) &&
                                        $row->endtime >=
                                            date('H:i:s', strtotime($bk_time))
                                    ) {
                                        if (!empty($row->discount_price)) {
                                            $detail_Arr['price'] =
                                                $row->discount_price;
                                            $detail_Arr['discount_price'] =
                                                $row->price -
                                                $row->discount_price;
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
                                $detail_Arr['updated_on'] = date('Y-m-d H:i:s');
                                $detail_Arr['user_id'] = $row->user_id;
                                $detail_Arr[
                                    'updated_by'
                                ] = $this->session->userdata('st_userid');
                                $this->user->update(
                                    'st_booking_detail',
                                    $detail_Arr,
                                    ['id' => $row->id]
                                );
                            }

                            $this->data['main'] = '';
                            ///mail section
                            $this->data['main'] = $this->user->join_two(
                                'st_booking',
                                'st_users',
                                'merchant_id',
                                'id',
                                [
                                    'st_booking.id' => url_decode(
                                        $_POST['reSchedule_id']
                                    ),
                                ],
                                'st_booking.id,business_name,booking_time,book_id,st_booking.merchant_id,st_booking.user_id,st_booking.created_on,employee_id,(select first_name from st_users where id=st_booking.employee_id) as first_name,total_time,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select email from st_users where id=st_booking.user_id) as usemail,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text'
                            );

                            if (!empty($this->data['main'])) {
                                $field =
                                    'st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price';
                                $whr = [
                                    'booking_id' => url_decode(
                                        $_POST['reSchedule_id']
                                    ),
                                ];

                                $this->data[
                                    'booking_detail'
                                ] = $this->user->join_two(
                                    'st_booking_detail',
                                    'st_users',
                                    'user_id',
                                    'id',
                                    $whr,
                                    $field
                                );

                                $this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
                                $body_msg = str_replace(
                                    '*salonname*',
                                    $this->data['main'][0]->business_name,
                                    $this->lang->line('booking_reshedule_body')
                                );
                                $MsgTitle = $this->lang->line(
                                    'booking_reshedule_title'
                                );

                                if ($info->booking_type == 'guest') {
                                    $email = $info->email;
                                    $this->data[
                                        'booking_detail'
                                    ][0]->first_name = $info->fullname;
                                } else {
                                    if (
                                        $this->data['main'][0]->user_notify != 0
                                    ) {
                                        sendPushNotification(
                                            $this->data['main'][0]->user_id,
                                            [
                                                'body' => $body_msg,
                                                'title' => $MsgTitle,
                                                'salon_id' =>
                                                    $this->data['main'][0]
                                                        ->merchant_id,
                                                'book_id' => url_decode(
                                                    $_POST['reSchedule_id']
                                                ),
                                                'booking_status' =>
                                                    'reschedule',
                                                'click_action' =>
                                                    'BOOKINGDETAIL',
                                            ]
                                        );
                                    }
                                    $email = $this->data['main'][0]->usemail;
                                }

                                $message = $this->load->view(
                                    'email/reshedule_booking_new_by_merchant',
                                    $this->data,
                                    true
                                );
                                $mail = emailsend(
                                    $email,
                                    $this->lang->line("styletimer_reschedule_booking"),
                                    $message,
                                    'styletimer'
                                );

                                $empDat = is_mail_enable_for_merchant_action($this->data['main'][0]->employee_id);
                                if ($empDat) {
                                    $tmp = $this->data;
                                    $tmp['main'][0]->employee_name = $empDat->first_name;
                                    $tmp['old_date'] = $oldDate;
                                    $message2 = $this->load->view('email/reshedule_booking_employee_by_merchant',$tmp, true);
                                    emailsend($empDat->email,'styletimer - Buchung verlegt',$message2,'styletimer');
                                }
                            }
                            $yrdata = strtotime($_POST['chg_date']);
                            //$ddd = date('d F Y', $yrdata);
                            $ddd = date('d.m.Y', $yrdata);
                            $yrda = strtotime($_POST['chg_time']);
                            $ttt = date('H:i', $yrda);

                            if(isset($ddd) && $ddd !="") {
                                $_SESSION['booking_rebook_date_value'] = $ddd;
                            }

                            echo json_encode([
                                'success' => 1,
                                'msg' =>
                                    'Die Buchung wurde erfolgreich auf den ' .
                                    $ddd .
                                    ' um ' .
                                    $ttt .
                                    ' Uhr verlegt.',
                            ]);
                        } else {
                            echo json_encode([
                                'success' => 0,
                                'msg' => 'Sorry unable to process',
                            ]);
                        }
                    } else {
                        echo json_encode([
                            'success' => 0,
                            'msg' =>
                                'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar',
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => 0,
                        'msg' =>
                            'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar',
                    ]);
                }
            }

            //date("h:i");
        } else {
            echo json_encode([
                'success' => 0,
                'msg' => 'You can not reschedule this booking.',
            ]);
        }
    }

    //**** Booking reshedule from calender *****//
    public function booking_reshedule_from_calender()
    {
        ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
        $newtime = $_POST['new_time'];
        $originNewTime = strtotime($newtime);
        $firstCharacter = substr($_POST['new_time'], 0, 1);
        if ($firstCharacter == '-') {
            $cal_time = $_POST['new_time'];
        } else {
            $cal_time = $_POST['new_time'];
        }
        $seconds = floor($cal_time / 1000);
        $minutes = floor($seconds / 60);
        //$hours = floor($minutes / 60);
        //$start = '2019-06-03 15:00:00';
        //echo date('Y-m-d H:i',strtotime($minutes.' minutes',strtotime($start)));
        //die;
        //$newDate = date("Y-m-d", strtotime($originalDate));
        $field =
            'id,merchant_id,booking_time,total_minutes,booking_type,email,fullname,total_buffer,employee_id,(SELECT notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as additional_notification_time';
        $info = $this->user->select_row('st_booking', $field, [
            'id' => $_POST['reSchedule_id'],
        ]);
        $oldDate = '';

        if (!empty($info)) {
            $oldDate = $info->booking_time;
            $new_book = date(
                'Y-m-d H:i',
                strtotime($minutes . ' minutes', strtotime($info->booking_time))
            );
            $tdy = date(
                'Y-m-d',
                strtotime($minutes . ' minutes', strtotime($info->booking_time))
            );
            if ($tdy < date('Y-m-d')) {
                echo json_encode([
                    'success' => 0,
                    'msg' =>
                        'Du kannst keine Buchung um mehr als einen Tag in die Vergangenheit verlegen.',
                ]);
                die();
            }

            $newDate = date('Y-m-d', strtotime($new_book));
            $str_time = date('H:i:s', strtotime($new_book));

            $toDate = date('Y-m-d');

            $date = $newDate;
            $nameOfDay = date('l', strtotime($date));
            $totalMinutes = 0; //$info->total_minutes+$info->total_buffer;
            $times = strtotime($str_time);
            $newTime = date(
                'H:i',
                strtotime('+ ' . $totalMinutes . ' minutes', $times)
            );
            $dayName = strtolower($nameOfDay);

            $bk_time = $newDate . ' ' . $str_time;

            $sqlForservice =
                "SELECT `st_booking_detail`.`id`,`st_booking_detail`.`user_id`,`st_booking_detail`.`buffer_time`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`,st_booking_detail.duration,st_booking_detail.service_type as stype,st_booking_detail.setuptime,st_booking_detail.processtime,st_booking_detail.finishtime,`st_merchant_category`.`parent_service_id`,`st_merchant_category`.`price`, `st_merchant_category`.`discount_price`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='" .
                $dayName .
                "') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = " .
                $_POST['reSchedule_id'] .
                ' ORDER BY st_booking_detail.id';

            $booking_detail = $this->user->custome_query(
                $sqlForservice,
                'result'
            );

            $total_price = $total_buffer = $total_min = $total_dis = 0;

            if (!empty($booking_detail)) {
                $timeArray = [];
                $ikj = 0;
                $strtodatyetime = $bk_time;

                $availabeTime = $this->user->select_row('st_availability', '*', [
                    'user_id' => $this->session->userdata('st_userid'),
                    'days' => $dayName,
                    'type' => 'open'
                ]);

                foreach ($booking_detail as $row) {
                    $timeArray[$ikj] = new stdClass();
                    $bkstartTime = $strtodatyetime;
                    $timeArray[$ikj]->start = $bkstartTime;

                    if ($row->stype == 1) {
                        $total_min = $row->duration + $total_min;

                        $bkEndTime = date(
                            'Y-m-d H:i:s',
                            strtotime(
                                '' .
                                    $bkstartTime .
                                    ' + ' .
                                    $row->setuptime .
                                    ' minute'
                            )
                        );
                        $timeArray[$ikj]->end = $bkEndTime;
                        $ikj++;

                        $finishStart = date(
                            'Y-m-d H:i:s',
                            strtotime(
                                '' .
                                    $bkEndTime .
                                    ' + ' .
                                    $row->processtime .
                                    ' minute'
                            )
                        );
                        $timeArray[$ikj] = new stdClass();
                        $timeArray[$ikj]->start = $finishStart;

                        $finishEnd = date(
                            'Y-m-d H:i:s',
                            strtotime(
                                '' .
                                    $finishStart .
                                    ' + ' .
                                    $row->finishtime .
                                    ' minute'
                            )
                        );
                        $timeArray[$ikj]->end = $finishEnd;
                        $ikj++;

                        $strtodatyetime = $finishEnd;
                    } else {
                        $total_buffer = $row->buffer_time + $total_buffer;
                        $totalMin = $row->duration;

                        $total_min = $total_min + $row->duration - $row->buffer_time;

                        $bkEndTime = date(
                            'Y-m-d H:i:s',
                            strtotime(
                                '' .
                                    $bkstartTime .
                                    ' + ' .
                                    $totalMin .
                                    ' minute'
                            )
                        );
                        $timeArray[$ikj]->end = $bkEndTime;
                        $ikj++;

                        $strtodatyetime = $bkEndTime;
                    }

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

                    if (!empty($row->type) && $row->type == 'open') {
                        if (
                            $row->starttime <=
                                date('H:i:s', strtotime($bk_time)) &&
                            $row->endtime >= date('H:i:s', strtotime($bk_time))
                        ) {
                            if (!empty($row->discount_price)) {
                                $total_dis =
                                    $row->price -
                                    $row->discount_price +
                                    $total_dis;
                                $total_price =
                                    $row->discount_price + $total_price;
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

                $resultCheckSlot = checkTimeSlotsMerchant(
                    $timeArray,
                    $info->employee_id,
                    $info->merchant_id,
                    $totalMinutes,
                    $_POST['reSchedule_id']
                );

                if ($resultCheckSlot == true) {
                    $min = $total_buffer + $total_min;
                    $newtimestamp = strtotime(
                        '' . $bk_time . ' + ' . $min . ' minute'
                    );
                    $book_end = date('Y-m-d H:i:s', $newtimestamp);
                    //notification set time
                    $notif_time = $info->notification_time;
                    $ad_notif_time = $info->additional_notification_time;
                    $timestamp = strtotime($bk_time);
                    $time = $timestamp - $notif_time * 60 * 60;
                    $ad_time = $timestamp - $ad_notif_time * 60 * 60;
                    // Date and time after subtraction
                    $notif_date = date('Y-m-d H:i:s', $time);
                    if ($ad_notif_time != '0') {
                        $ad_notif_date = date('Y-m-d H:i:s', $ad_time);
                        $book_Arr[
                            'additional_notification_date'
                        ] = $ad_notif_date;
                    }

                    $book_Arr['booking_time'] = $bk_time;
                    $book_Arr['booking_endtime'] = $book_end;
                    $book_Arr['total_minutes'] = $total_min;
                    $book_Arr['total_buffer'] = $total_buffer;
                    $book_Arr['total_time'] = $total_buffer + $total_min;
                    $book_Arr['total_price'] = $total_price;
                    $book_Arr['total_discount'] = $total_dis;
                    $book_Arr['pay_status'] = 'cash';
                    $book_Arr['status'] = 'confirmed';
                    $book_Arr['notification_date'] = $notif_date;
                    $book_Arr['updated_on'] = date('Y-m-d H:i:s');
                    $book_Arr['updated_by'] = $this->session->userdata(
                        'st_userid'
                    );

                    if (
                        $this->user->update('st_booking', $book_Arr, [
                            'id' => $_POST['reSchedule_id'],
                        ])
                    ) {
                        //$this->user->delete('st_booking_detail',array('booking_id' => url_decode($_POST['reSchedule_id'])));
                        $boojkstartTime = $bk_time;

                        foreach ($booking_detail as $row) {
                            $detail_Arr = [];
                            //$detail_Arr['booking_id']       = url_decode($_POST['reSchedule_id']);
                            $detail_Arr['mer_id'] = $info->merchant_id;
                            $detail_Arr['emp_id'] = $info->employee_id;
                            $detail_Arr['service_type'] = $row->stype;
                            if ($row->buffer_time > 0)
                                $detail_Arr['has_buffer']   = 1;
                            if ($row->stype == 1) {
                                $detail_Arr['setuptime'] = $row->setuptime;
                                $detail_Arr['processtime'] = $row->processtime;
                                $detail_Arr['finishtime'] = $row->finishtime;
                                $detail_Arr[
                                    'setuptime_start'
                                ] = $boojkstartTime;

                                $setuEnd = date(
                                    'Y-m-d H:i:s',
                                    strtotime(
                                        '' .
                                            $boojkstartTime .
                                            ' + ' .
                                            $row->setuptime .
                                            ' minute'
                                    )
                                );
                                $finishStart = date(
                                    'Y-m-d H:i:s',
                                    strtotime(
                                        '' .
                                            $setuEnd .
                                            ' + ' .
                                            $row->processtime .
                                            ' minute'
                                    )
                                );
                                $finishEnd = date(
                                    'Y-m-d H:i:s',
                                    strtotime(
                                        '' .
                                            $finishStart .
                                            ' + ' .
                                            $row->finishtime .
                                            ' minute'
                                    )
                                );

                                $detail_Arr['setuptime_end'] = $setuEnd;
                                $detail_Arr['finishtime_start'] = $finishStart;
                                $detail_Arr['finishtime_end'] = $finishEnd;

                                $boojkstartTime = $finishEnd;
                            } else {
                                $totalMin = $row->duration;
                                $setuEnd = date(
                                    'Y-m-d H:i:s',
                                    strtotime(
                                        '' .
                                            $boojkstartTime .
                                            ' + ' .
                                            $totalMin .
                                            ' minute'
                                    )
                                );
                                $detail_Arr[
                                    'setuptime_start'
                                ] = $boojkstartTime;
                                $detail_Arr['setuptime_end'] = $setuEnd;

                                $boojkstartTime = $setuEnd;
                            }

                            $detail_Arr['service_id'] = $row->service_id;
                            if (!empty($row->name)) {
                                $detail_Arr['service_name'] = $row->name;
                            } else {
                                $detail_Arr['service_name'] =
                                    $row->category_name;
                            }

                            $detail_Arr['price'] = 0;
                            $detail_Arr['discount_price'] = 0;
                            if (!empty($row->type) && $row->type == 'open') {
                                if (
                                    $row->starttime <=
                                        date('H:i:s', strtotime($bk_time)) &&
                                    $row->endtime >=
                                        date('H:i:s', strtotime($bk_time))
                                ) {
                                    if (!empty($row->discount_price)) {
                                        $detail_Arr['price'] =
                                            $row->discount_price;
                                        $detail_Arr['discount_price'] =
                                            $row->price - $row->discount_price;
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
                            $detail_Arr['updated_on'] = date('Y-m-d H:i:s');
                            $detail_Arr['user_id'] = $row->user_id;
                            $detail_Arr[
                                'updated_by'
                            ] = $this->session->userdata('st_userid');
                            $this->user->update(
                                'st_booking_detail',
                                $detail_Arr,
                                ['id' => $row->id]
                            );
                        }

                        $this->data['main'] = '';
                        ///mail section
                        $this->data['main'] = $this->user->join_two(
                            'st_booking',
                            'st_users',
                            'merchant_id',
                            'id',
                            ['st_booking.id' => $_POST['reSchedule_id']],
                            'st_booking.id,business_name,booking_time,book_id,st_booking.merchant_id,st_booking.user_id,st_booking.created_on,employee_id,(select first_name from st_users where id=st_booking.employee_id) as first_name,total_time,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select email from st_users where id=st_booking.user_id) as usemail,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text'
                        );

                        if (!empty($this->data['main'])) {
                            $field =
                                'st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price';
                            $whr = ['booking_id' => $_POST['reSchedule_id']];

                            $this->data[
                                'booking_detail'
                            ] = $this->user->join_two(
                                'st_booking_detail',
                                'st_users',
                                'user_id',
                                'id',
                                $whr,
                                $field
                            );
                            $this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);

                            $body_msg = str_replace(
                                '*salonname*',
                                $this->data['main'][0]->business_name,
                                $this->lang->line('booking_reshedule_body')
                            );
                            $MsgTitle = $this->lang->line(
                                'booking_reshedule_title'
                            );

                            if ($info->booking_type == 'guest') {
                                $email = $info->email;
                                $this->data['booking_detail'][0]->first_name =
                                    $info->fullname;
                            } else {
                                if ($this->data['main'][0]->user_notify != 0) {
                                    sendPushNotification(
                                        $this->data['main'][0]->user_id,
                                        [
                                            'body' => $body_msg,
                                            'title' => $MsgTitle,
                                            'salon_id' =>
                                                $this->data['main'][0]
                                                    ->merchant_id,
                                            'book_id' =>
                                                $_POST['reSchedule_id'],
                                            'booking_status' => 'reschedule',
                                            'click_action' => 'BOOKINGDETAIL',
                                        ]
                                    );
                                }
                                $email = $this->data['main'][0]->usemail;
                            }

                            $message = $this->load->view(
                                'email/reshedule_booking_new_by_merchant',
                                $this->data,
                                true
                            );
                            $mail = emailsend(
                                $email,
                                $this->lang->line("styletimer_reschedule_booking"),
                                $message,
                                'styletimer'
                            );

                            $empDat = is_mail_enable_for_merchant_action($this->data['main'][0]->employee_id);
                            if ($empDat) {
                                $tmp = $this->data;
                                $tmp['main'][0]->employee_name = $empDat->first_name;
                                $tmp['old_date'] = $oldDate;
                                $message2 = $this->load->view('email/reshedule_booking_employee_by_merchant',$tmp, true);
                                emailsend($empDat->email,'styletimer - Buchung verlegt',$message2,'styletimer');
                            }
                        }

                        $yrdata = strtotime($bk_time);
                        //$ddd = date('d F Y', $yrdata);
                        $ddd = date('d.m.Y', $yrdata);
                        $yrda = strtotime($bk_time);
                        $ttt = date('H:i', $yrda);
                        echo json_encode([
                            'success' => 1,
                            'msg' =>
                                'Die Buchung wurde erfolgreich auf den ' .
                                $ddd .
                                ' um ' .
                                $ttt .
                                ' Uhr verlegt.',
                        ]);

                        //echo json_encode(array('success'=>1));
                    } else {
                        echo json_encode([
                            'success' => 0,
                            'msg' => 'Sorry unable to process',
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => 0,
                        'msg' =>
                            'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar',
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => 0,
                    'msg' =>
                        'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar',
                ]);
            }

            /*$check=$this->user->select_row('st_availability','id',array('user_id'=>$info->employee_id,'days' =>strtolower($nameOfDay),'type'=>'open','starttime <=' =>$str_time,'endtime >=' => $newTime));*/ //$_POST['chg_time']

            //~ $select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND ((`starttime`<='".$str_time."' AND endtime>='".$newTime."') OR (`starttime_two`<='".$str_time."' AND endtime_two>='".$newTime."'))";

            //~ $check= $this->user->custome_query($select123,'row');

            //~ if(!empty($check)){

            //~ $bk_time=$newDate.' '.$str_time;
            //~ $newtimestamp = strtotime(''.$bk_time.' + '.$totalMinutes.' minute');
            //~ $book_end=date('Y-m-d H:i:s', $newtimestamp);

            //~ $upd['booking_time']=$bk_time;
            //~ $upd['booking_endtime']=$book_end;

            //~ //check booking overlapping
            //~ /* $whereAS='((booking_time>="'.$bk_time.'" AND booking_time<="'.$book_end.'") OR (booking_endtime>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'") OR (booking_time<="'.$bk_time.'" AND booking_endtime>="'.$book_end.'") OR (booking_time>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'")) AND status!="cancelled"';*/ // time slot changes

            //~ $whereAS='((booking_time>="'.$bk_time.'" AND booking_time<"'.$book_end.'") OR (booking_endtime>"'.$bk_time.'" AND booking_endtime<="'.$book_end.'") OR (booking_time<="'.$bk_time.'" AND booking_endtime>"'.$book_end.'") OR (booking_time>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'")) AND status!="cancelled"';

            //~ $this->db->where($whereAS);
            //~ $check=$this->user->select_row('st_booking','id',array('employee_id'=>$info->employee_id,'id !='=>$_POST['reSchedule_id']));
            //~ // echo $this->db->last_query(); die;
            //~ //check booking overlapping

            //~ $sqlForservice="SELECT `st_booking_detail`.`id`,`st_booking_detail`.`user_id`,`st_merchant_category`.`buffer_time`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`, st_merchant_category.duration, `st_merchant_category`.`price`, `st_merchant_category`.`discount_price`,`days`,`type`,`starttime`,`endtime` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = ".$_POST['reSchedule_id']."";

            //~ $booking_detail= $this->user->custome_query($sqlForservice,'result');

            //~ $total_price=$total_buffer=$total_min=$total_dis=0;

            //~ if(empty($check) && !empty($booking_detail)){

            //~ foreach($booking_detail as $row)
            //~ {

            //~ $total_buffer=$row->buffer_time+$total_buffer;
            //~ $total_min=$row->duration+$total_min;

            //~ if(!empty($row->type) && $row->type=='open'){
            //~ if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
            //~ {
            //~ if(!empty($row->discount_price)){
            //~ $total_dis=($row->price-$row->discount_price)+$total_dis;
            //~ $total_price=$row->discount_price+$total_price;
            //~ }
            //~ else{
            //~ $total_price=$row->price+$total_price;
            //~ }
            //~ }
            //~ else $total_price=$row->price+$total_price;
            //~ }else $total_price=$row->price+$total_price;

            //~ }

            //~ $min=$total_buffer+$total_min;
            //~ $newtimestamp = strtotime(''.$bk_time.' + '.$min.' minute');
            //~ $book_end=date('Y-m-d H:i:s', $newtimestamp);

            //~ $book_Arr['booking_time']=$bk_time;
            //~ $book_Arr['booking_endtime']=$book_end;
            //~ $book_Arr['total_minutes']=$total_min;
            //~ $book_Arr['total_buffer']=$total_buffer;
            //~ $book_Arr['total_price']=$total_price;
            //~ $book_Arr['total_discount']=$total_dis;
            //~ $book_Arr['pay_status']='cash';
            //~ $book_Arr['status']='confirmed';
            //~ $book_Arr['updated_on']=date('Y-m-d H:i:s');
            //~ $book_Arr['updated_by']=$this->session->userdata('st_userid');

            //~ // if(empty($check)  && !empty($booking_detail)){
            //~ if($this->user->update('st_booking',$book_Arr,array('id'=>$_POST['reSchedule_id'])))
            //~ {

            //~ $this->user->delete('st_booking_detail',array('booking_id' => $_POST['reSchedule_id']));

            //~ foreach($booking_detail as $row){
            //~ $detail_Arr['booking_id']=$_POST['reSchedule_id'];
            //~ $detail_Arr['service_id']=$row->service_id;
            //~ if(!empty($row->name))
            //~ $detail_Arr['service_name']=$row->name;
            //~ else
            //~ $detail_Arr['service_name']=$row->category_name;

            //~ if(!empty($row->type) && $row->type=='open'){
            //~ if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
            //~ {
            //~ if(!empty($row->discount_price)){
            //~ $detail_Arr['price']=$row->discount_price;
            //~ $detail_Arr['discount_price']=$row->price-$row->discount_price;
            //~ }
            //~ else $detail_Arr['price']=$row->price;
            //~ }
            //~ else $detail_Arr['price']=$row->price;
            //~ }

            //~ else $detail_Arr['price']=$row->price;

            //~ $detail_Arr['duration']=$row->duration;
            //~ $detail_Arr['buffer_time']=$row->buffer_time;
            //~ $detail_Arr['created_on']=date('Y-m-d H:i:s');
            //~ $detail_Arr['user_id']=$row->user_id;
            //~ $detail_Arr['created_by']=$this->session->userdata('st_userid');

            //~ $this->user->insert('st_booking_detail',$detail_Arr);

            //~ }

            //~ $this->data['main']="";
            //~ ///mail section
            //~ $this->data['main']= $this->user->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$_POST['reSchedule_id']),'business_name,booking_time,st_booking.merchant_id,st_booking.user_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select email from st_users where id=st_booking.user_id) as usemail,(select notification_status from st_users where id=st_booking.user_id) as user_notify');
            //~ if(!empty($this->data['main'])){

            //~ $field="st_users.id,first_name,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price";
            //~ $whr=array('booking_id'=>$_POST['reSchedule_id']);
            //~ $this->data['booking_detail']= $this->user->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);

            //~ $body_msg="Your booking has been reshedule successfully";
            //~ $MsgTitle="Styletimer-Reshedule Booking";

            //~ if($info->booking_type=='guest' && $this->data['main'][0]->user_notify == 0){
            //~ $email=$info->email;
            //~ $this->data['booking_detail'][0]->first_name=$info->fullname;
            //~ }
            //~ else{
            //~ sendPushNotification($this->data['main'][0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> $_POST['reSchedule_id'], 'booking_status' => 'reschedule' ,'click_action' => 'BOOKINGDETAIL'));
            //~ $email=$this->data['main'][0]->usemail;
            //~ }

            //~ $message = $this->load->view('email/reshedule_booking_new',$this->data, true);
            //~ $mail = emailsend($email,$this->lang->line("styletimer_reschedule_booking"),$message,'styletimer');

            //~ }
            //~ echo json_encode(array('success'=>1));
            //~ }
            //~ else
            //~ echo json_encode(array('success'=>0,'msg'=>'Sorry unable to process'));
            //~ }
            //~ else echo json_encode(array('success'=>0,'msg'=>'Der Mitarbeiter ist im ausgewählten Zeitraum nicht verfügbar.'));
            //~ }
            //~ else echo json_encode(array('success'=>0,'msg'=>'Der Mitarbeiter ist im ausgewählten Zeitraum nicht verfügbar.'));

            //}

            //date("h:i");
        } else {
            echo json_encode([
                'success' => 0,
                'msg' => 'You can not reschedule this booking.',
            ]);
        }
    }

    public function booking_reshedule_from_calender_old()
    {
        $newtime = $_POST['new_time'];
        $firstCharacter = substr($_POST['new_time'], 0, 1);
        if ($firstCharacter == '-') {
            $cal_time = $_POST['new_time'];
        } else {
            $cal_time = $_POST['new_time'];
        }
        $seconds = floor($cal_time / 1000);
        $minutes = floor($seconds / 60);
        //$hours = floor($minutes / 60);
        //$start = '2019-06-03 15:00:00';
        //echo date('Y-m-d H:i',strtotime($minutes.' minutes',strtotime($start)));
        //die;
        //$newDate = date("Y-m-d", strtotime($originalDate));
        $field =
            'id,merchant_id,booking_time,total_minutes,total_buffer,employee_id';
        $info = $this->user->select_row('st_booking', $field, [
            'id' => $_POST['reSchedule_id'],
        ]);

        if (!empty($info)) {
            $new_book = date(
                'Y-m-d H:i',
                strtotime($minutes . ' minutes', strtotime($info->booking_time))
            );
            if ($new_book <= date('Y-m-d H:i')) {
                echo json_encode([
                    'success' => 0,
                    'msg' =>
                        'Du kannst keine Buchung um mehr als einen Tag in die Vergangenheit verlegen.',
                ]);
                die();
            }

            $newDate = date('Y-m-d', strtotime($new_book));
            $str_time = date('H:i', strtotime($new_book));

            $toDate = date('Y-m-d');
            /*if($newDate < $toDate)
				echo json_encode(array('success'=>0, 'msg' =>'Please select valid date'));
			else{*/

            $date = $newDate;
            $nameOfDay = date('l', strtotime($date));
            $totalMinutes = $info->total_minutes + $info->total_buffer;
            $times = strtotime($str_time); //$_POST['chg_time']
            $newTime = date(
                'H:i',
                strtotime('+ ' . $totalMinutes . ' minutes', $times)
            );

            $check = $this->user->select_row('st_availability', 'id', [
                'user_id' => $info->employee_id,
                'days' => strtolower($nameOfDay),
                'type' => 'open',
                'starttime <=' => $str_time,
                'endtime >=' => $newTime,
            ]); //$_POST['chg_time']
            if (!empty($check)) {
                $bk_time = $newDate . ' ' . $str_time;
                $newtimestamp = strtotime(
                    '' . $bk_time . ' + ' . $totalMinutes . ' minute'
                );
                $book_end = date('Y-m-d H:i:s', $newtimestamp);

                $upd['booking_time'] = $bk_time;
                $upd['booking_endtime'] = $book_end;

                //check booking overlapping
                $whereAS =
                    '((booking_time>="' .
                    $bk_time .
                    '" AND booking_time<="' .
                    $book_end .
                    '") OR (booking_endtime>="' .
                    $bk_time .
                    '" AND booking_endtime<="' .
                    $book_end .
                    '") OR (booking_time<="' .
                    $bk_time .
                    '" AND booking_endtime>="' .
                    $book_end .
                    '") OR (booking_time>="' .
                    $bk_time .
                    '" AND booking_endtime<="' .
                    $book_end .
                    '"))';

                $this->db->where($whereAS);
                $check = $this->user->select_row('st_booking', 'id', [
                    'employee_id' => $info->employee_id,
                    'id !=' => $_POST['reSchedule_id'],
                ]);
                // echo $this->db->last_query(); die;
                //check booking overlapping
                if (empty($check)) {
                    if (
                        $this->user->update('st_booking', $upd, [
                            'id' => $_POST['reSchedule_id'],
                        ])
                    ) {
                        $field =
                            'st_booking.id,user_id,booking_time,total_time,st_booking.merchant_id,book_id,first_name,last_name,st_users.email,(select business_name from st_users where st_users.id = st_booking.merchant_id) as salon_name,(select email_text from st_users where st_users.id = st_booking.merchant_id) as email_text,notification_status';
                        $info = $this->user->join_two(
                            'st_booking',
                            'st_users',
                            'user_id',
                            'id',
                            ['st_booking.id' => $_POST['reSchedule_id']],
                            $field
                        );
                        if (!empty($info)) {
                            $body_msg =
                                'Your booking has been reshedule successfully';
                            $MsgTitle = 'Styletimer-Reshedule Booking';
                            if ($info[0]->notification_status != 0) {
                                sendPushNotification($info[0]->user_id, [
                                    'body' => $body_msg,
                                    'title' => $MsgTitle,
                                    'salon_id' => $info[0]->merchant_id,
                                    'book_id' => $_POST['reSchedule_id'],
                                    'booking_status' => 'confirmed',
                                ]);
                            }

                            $time = new DateTime($info[0]->booking_time);
                            $date = $time->format('d/m/Y');
                            $time = $time->format('H:i');
                            $message = $this->load->view(
                                'email/reshedule_booking',
                                [
                                    'fname' => ucwords($info[0]->first_name),
                                    'lname' => ucwords($info[0]->last_name),
                                    'salon_name' => $info[0]->salon_name,
                                    'service_name' => get_servicename(
                                        $info[0]->id
                                    ),
                                    'booking_date' => $date,
                                    'booking_time' => $time,
                                    'book_id' => $info[0]->book_id,
                                    'email_text' => $info[0]->email_text,
                                    'duration' => $info[0]->total_time,
                                ],
                                true
                            );
                            $mail = emailsend(
                                $info[0]->email,
                                $this->lang->line("styletimer_reschedule_booking"),
                                $message,
                                'styletimer'
                            ); //$info[0]->email
                        }
                        echo json_encode(['success' => 1]);
                    } else {
                        echo json_encode([
                            'success' => 0,
                            'msg' => 'Sorry unable to process',
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => 0,
                        'msg' =>
                            'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar',
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => 0,
                    'msg' =>
                        'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar',
                ]);
            }

            //}

            //date("h:i");
        }
    }

    //**** get customer listing ****//
    public function customers()
    {
        if ($this->session->userdata('access') != 'marchant') {
            redirect(base_url());
        }
        $search = '';
        if (isset($_GET['sch'])) {
            $search = $_GET['sch'];
        }

        $sessuid = $this->session->userdata('st_userid');

        // $where = ['st_booking.merchant_id' => $sessuid];
        // $totalcount = $data = $this->user->join_two_orderby(
        //     'st_booking',
        //     'st_users',
        //     'user_id',
        //     'id',
        //     $where,
        //     'user_id',
        //     'st_booking.id',
        //     'user_id',
        //     0,
        //     0,
        //     $search
        // );
        // if (!empty($totalcount)) {
        //     $total = count($totalcount);
        // } else {
        //     $total = 0;
        // }

        // $limit = isset($_GET['limit']) ? $_GET['limit'] : PER_PAGE10; //PER_PAGE10
        // $url = 'merchant/customers';
        // $segment = 3;
        // $page = mypaging($url, $total, $segment, $limit);
        // $field =
        //     'st_booking.id,user_id,st_booking.merchant_id,
		//  first_name,last_name,profile_pic,address,st_users.gender,st_users.email,mobile,
		//  (SELECT notes from st_usernotes WHERE created_by = "' .
        //     $sessuid .
        //     '" AND user_id= st_booking.user_id ) AS notes,
		//  (SELECT count(id) FROM st_booking WHERE user_id=st_users.id AND merchant_id="' .
        //     $sessuid .
        //     '") as bookcount,
		//  (SELECT booking_time FROM st_booking WHERE user_id=st_users.id AND status="completed" AND merchant_id="' .
        //     $sessuid .
        //     '" ORDER BY id DESC LIMIT 1) as lastbook';
        // $this->data['customer'] = $this->user->join_two_orderby(
        //     'st_booking',
        //     'st_users',
        //     'user_id',
        //     'id',
        //     $where,
        //     $field,
        //     'st_booking.id',
        //     'user_id',
        //     $page['per_page'],
        //     $page['offset'],
        //     $search
        // );
        $this->data['customer'] = [];
        //print_r($this->data['customer']);
        //function join_two_without_limit($tbl1,$tbl2 ,$field1,$field2,$where,$select,$orderby="id",$groupby=""){
        if (!empty($_GET['tempid'])) {
            $this->data['merchant_category'] = $this->user->join_two_without_limit(
                'st_booking_detail',
                'st_merchant_category',
                'service_id',
                'id',
                ['mer_id' => $sessuid],
                'category_id',
                'st_merchant_category.category_id',
                'st_merchant_category.category_id'
            );

            $this->data['merchant_category'] = array_map(function($el) {
                return (object)[
                    'category_id' => $el->category_id,
                    'category' => get_category_name($el->category_id)[0]->category_name
                ];
            }, $this->data['merchant_category']);
        } else {
            $this->data['merchant_category'] = [];
        }
        //echo $this->db->last_query();

        /*$this->data['merchant_category']=$this->user->select('st_merchant_category','id,name,(SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as catname',array('created_by'=>$uid, 'status' => 'active'),'','id','ASC');*/

        //echo '<pre>'; print_r($this->data); die;
        //echo $this->db->last_query(); die;
        $this->data['is_edit_profile'] = true;
        $this->data['include_jqueryui'] = true;
        $this->load->view('frontend/marchant/customers_list', $this->data);
        //$this->load->view('frontend/marchant/customers',$this->data);
    }
    public function customer_list($page = '0')
    {
        // print_r($_POST);
        /*if(!empty($id))
         {*/
        //$cid=url_decode($id);
        $mid = $this->session->userdata('st_userid');
        $short_day = '';
        $category = '';
        //$short_day=date('Y-m-d', strtotime('-30 days'));
        $wh = '';
        $wh2 = '';
        $wh_c = '';

        $join_st_merchant_category_str = '';
        if (!empty($_POST['category'])) {
            $category = $_POST['category'];
            $msql =
                "SELECT GROUP_CONCAT(DISTINCT bd.user_id) as bid FROM st_booking_detail as bd JOIN st_merchant_category as mc ON mc.id=bd.service_id JOIN st_booking as bk on bk.id = bd.booking_id WHERE bd.mer_id='" .
                $mid .
                "' AND mc.category_id='" .
                $category .
                "' AND (bk.status='confirmed' OR bk.status='completed') AND bd.user_id!='0' GROUP BY bd.user_id";
            //GROUP_CONCAT(id
            // echo $msql;//die();
            $wh_c = "AND st_merchant_category.status='active' AND st_merchant_category.category_id='" . $category . "'";
            $book_id = $this->user->custome_query($msql, 'result');

            $join_st_merchant_category_str = ' LEFT JOIN st_booking_detail ON st_booking_detail.booking_id=st_booking.id LEFT JOIN st_merchant_category ON st_merchant_category.id=st_booking_detail.service_id';

            //print_r($book_id); die;
            if (!empty($book_id)) {
                $ct = count($book_id);
                $us_in = [];
                for ($i = 0; $i < $ct; $i++) {
                    $us_in[$i] = $book_id[$i]->bid;
                }
                //implode(',', $us_in);
                $wh =
                    'AND st_booking.user_id IN (' . implode(',', $us_in) . ')';
            } else {
                $html =
                '<tr><td colspan="8" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="' .
                base_url('assets/frontend/images/no_listing.png') .
                '"><p style="margin-top: 20px;">' .
                $this->lang->line('dont_any_customer') .
                '</p></div></td></tr>';

                echo json_encode([
                    'success' => '1',
                    'msg' => '',
                    'html' => $html,
                    'pagination' => '',
                    'tt' => 1,
                ]);
                die();
            }
            /*if(!empty($book_id->bid))
             $wh = 'AND user_id IN('.$book_id->bid.')';*/
        }
        if (!empty($_POST['visit'])) {
            if ($_POST['visit'] == '1w') {
                $short_day = date('Y-m-d H:i:s', strtotime('-7 days'));
            }
            if ($_POST['visit'] == '2w') {
                $short_day = date('Y-m-d H:i:s', strtotime('-14 days'));
            }
            if ($_POST['visit'] == '4w') {
                $short_day = date('Y-m-d H:i:s', strtotime('-28 days'));
            }
            if ($_POST['visit'] == '1m') {
                $short_day = date('Y-m-d H:i:s', strtotime('-30 days'));
            }
            if ($_POST['visit'] == '2m') {
                $short_day = date('Y-m-d H:i:s', strtotime('-60 days'));
            }
            if ($_POST['visit'] == '3m') {
                $short_day = date('Y-m-d H:i:s', strtotime('-90 days'));
            }
            if ($_POST['visit'] == '4m') {
                $short_day = date('Y-m-d H:i:s', strtotime('-120 days'));
            }
            if ($_POST['visit'] == '5m') {
                $short_day = date('Y-m-d H:i:s', strtotime('-150 days'));
            }
            if ($_POST['visit'] == '6m') {
                $short_day = date('Y-m-d H:i:s', strtotime('-180 days'));
            }
            if ($_POST['visit'] == '1y') {
                $short_day = date('Y-m-d H:i:s', strtotime('-365 days'));
            }

            $wh2 = 'AND st_booking.booking_time > "' . $short_day . '" ';
        }

        $search = '';
        if (isset($_POST['search']) && !empty($_POST['search'])) {
            //$search = $_POST['search'];
            $search =
                "AND (st_users.first_name LIKE '%" .
                $_POST['search'] .
                "%' OR st_users.last_name LIKE '%" .
                $_POST['search'] .
                "%' OR st_users.email LIKE '%" .
                $_POST['search'] .
                "%')";
        }

        if (!empty($_POST['orderby'])) {
            $orderby = $_POST['orderby'];
            $shortby = $_POST['shortby'];
        } else {
            $orderby = 'st_booking.id';
            $shortby = 'ASC';
        }
        $checknet = $_POST['newcheck'];
        if (!empty($checknet)) {
            //$where=array('st_booking.merchant_id'=>$mid,'st_users.newsletter' =>1,'st_users.status !='=>'deleted');
            $where =
                'WHERE (st_booking.status="confirmed" OR st_booking.status="completed") AND st_booking.merchant_id="' .
                $mid .
                '" AND st_users.service_email="1" AND st_users.status!="deleted" ' .
                $wh .
                $wh2.
                '';
        } else {
            //$where=array('st_booking.merchant_id'=>$mid,'st_users.status !='=>'deleted');
            $where =
                'WHERE (st_booking.status="confirmed" OR st_booking.status="completed") AND st_booking.merchant_id="' .
                $mid .
                $wh2.
                '" AND st_users.status!="deleted"';
        }

        //(SELECT booking_time FROM st_booking WHERE user_id=st_users.id AND status='completed' AND merchant_id='".$mid."' ORDER BY id DESC LIMIT 1) as lastbook
        // $sql="SELECT user_id,".$sub." FROM st_booking JOIN st_users ON st_users.id=st_booking.user_id ".$where." ".$search." GROUP BY user_id ".$wh2."";

        $sql =
            'SELECT st_booking.id FROM st_booking JOIN st_users ON st_users.id=st_booking.user_id'.$join_st_merchant_category_str.' ' .
            $where .
            ' ' .
            $wh_c.
            ' ' .
            $search .
            ' GROUP BY st_booking.user_id';
        // $sql =
        // 'SELECT st_booking.id,st_booking.user_id,st_booking.merchant_id,st_booking.booking_time as lastbook,first_name,last_name,profile_pic,address,st_users.gender,st_users.email,mobile,st_usernotes.notes,st_usernotes.id as note_id,(SELECT count(id) FROM st_booking WHERE st_booking.user_id=st_users.id AND merchant_id=' .
        // $mid .
        // ') as bookcount FROM st_booking JOIN st_users ON st_users.id=st_booking.user_id  LEFT JOIN st_usernotes ON st_usernotes.user_id =st_booking.user_id LEFT JOIN st_booking_detail ON st_booking_detail.booking_id=st_booking.id '.$join_st_merchant_category_str.' ' .
        // $where .
        // ' ' .
        // $search .
        // ' GROUP BY st_booking.user_id';
        $totalcount = $this->user->custome_query($sql, 'result');

        //$totalcount = $data=$this->user->join_two_orderby('st_booking','st_users','user_id','id',$where,'user_id','st_booking.id','user_id',0,0,$search);
        if (!empty($totalcount)) {
            $total = count($totalcount);
        } else {
            $total = 0;
        }

        //echo $total;
        if (!empty($_POST['limit']) && $_POST['limit'] == 'all') {
            $limit = $total;
        } else {
            $limit = isset($_POST['limit']) ? $_POST['limit'] : PER_PAGE10; //PER_PAGE10
        }
        $url = 'merchant/customer_list';
        $segment = 3;
        // $page = mypaging($url,$total,$segment,$limit);
        $offset = 0;
        if ($page != 0) {
            $offset = ($page - 1) * $limit;
        }
        $config = [];
        $config['base_url'] = base_url() . $url;
        $config['total_rows'] = $total;
        $config['per_page'] = $limit;
        $config['use_page_numbers'] = true;
        $config['num_links'] = 2;
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = '&gt;';
        $config['prev_link'] = '&lt;';
        $config['first_link'] = '&laquo;';
        $config['last_link'] = '&raquo;';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();

        // $field='st_booking.id,user_id,st_booking.merchant_id,first_name,last_name,profile_pic,address,st_users.gender,st_users.email,mobile,(select notes from st_usernotes where user_id=st_users.id AND created_by="'.$mid.'") as notes,(select id from st_usernotes where user_id=st_users.id AND created_by="'.$mid.'") as note_id,(SELECT count(id) FROM st_booking WHERE user_id=st_users.id AND merchant_id="'.$mid.'") as bookcount, '.$sub.'';
        //(SELECT booking_time FROM st_booking WHERE user_id=st_users.id AND status="completed" AND merchant_id="'.$mid.'" ORDER BY id DESC LIMIT 1) as lastbook

        if (!empty($_POST['orderby'])) {
            $order = $_POST['orderby'];
        } else {
            $order = 'st_booking.id';
        }

        if (!empty($_POST['shortby'])) {
            $sort = $_POST['shortby'];
        } else {
            $sort = 'asc';
        }

        // $sql="SELECT ".$field." FROM st_booking JOIN st_users ON st_users.id=st_booking.user_id ".$where." ".$search." GROUP BY user_id ".$wh2." ORDER BY ".$orderby." ".$shortby." LIMIT ".$config["per_page"]." OFFSET ".$offset."";
        //   $sql = "SELECT st_booking.id,st_booking.user_id,st_booking.merchant_id,st_booking.booking_time as lastbook,first_name,last_name,profile_pic,address,st_users.gender,st_users.email,mobile,st_usernotes.notes,st_usernotes.id as note_id,(SELECT count(id) FROM st_booking WHERE st_booking.user_id=st_users.id AND merchant_id=".$mid.") as bookcount FROM st_booking JOIN st_users ON st_users.id=st_booking.user_id  LEFT JOIN st_usernotes ON st_usernotes.user_id =st_booking.user_id LEFT JOIN st_booking_detail ON st_booking_detail.booking_id=st_booking.id '.$join_st_merchant_category_str.' ".$where." ".$search." GROUP BY st_booking.user_id ORDER BY ".$orderby." ".$shortby." LIMIT ".$config["per_page"]." OFFSET ".$offset."";
        // 	$customer=$this->user->custome_query($sql,'result');
        $sql =
            "SELECT st_booking.id,st_booking.user_id,st_booking.merchant_id,st_booking.created_by,
	st_booking.booking_time as lastbook,first_name,last_name,profile_pic,address,
	st_users.gender,st_users.email,mobile,st_users.temp_user,st_usernotes.id as st_usernotes_id,st_usernotes.notes as notes"
    .',(SELECT count(id) FROM st_booking WHERE st_booking.user_id=st_users.id AND st_booking.employee_id != -1 AND merchant_id=' .
            $mid .
            ") as bookcount, (SELECT booking_endtime FROM st_booking WHERE st_booking.user_id=st_users.id AND merchant_id=$mid AND status ='completed' ORDER BY st_booking.booking_endtime DESC LIMIT 1) as booking_endDate FROM st_booking JOIN st_users ON st_users.id=st_booking.user_id  LEFT JOIN st_usernotes ON (st_usernotes.user_id =st_booking.user_id AND st_usernotes.created_by=$mid)".$join_st_merchant_category_str." " .
            $where .
            ' ' .
            $wh2 .
            ' ' .
            $wh_c.
            ' ' .
            $search .
            ' GROUP BY st_booking.user_id ORDER BY ' .
            $orderby .
            ' ' .
            $shortby .
            ' LIMIT ' .
            $config['per_page'] .
            ' OFFSET ' .
            $offset .
            '';
        $customer = $this->user->custome_query($sql, 'result');
        //print_r($customer);
        //$last =  $this->db->last_query();//die();
        //$customer=$this->user->join_two_orderby('st_booking','st_users','user_id','id',$where,$field,$order,'user_id',$config["per_page"],$offset,$search,$sort);

        $html = '';
        if (!empty($customer)) {
            foreach ($customer as $row) {
                if ($row->profile_pic != '') {
                    $usimg = getimge_url(
                        'assets/uploads/users/' . $row->user_id . '/',
                        'icon_' . $row->profile_pic,
                        'png'
                    );
                    $usimgw = getimge_url(
                        'assets/uploads/users/' . $row->user_id . '/',
                        'icon_' . $row->profile_pic,
                        'webp'
                    );
                } else {
                    $usimg = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                    $usimgw = base_url(
                        'assets/frontend/images/user-icon-gret.webp'
                    );
                }

                if (!empty($row->mobile)) {
                    $mobile = $row->mobile;
                } else {
                    $mobile = '-';
                }

                $uIDencoded = url_encode($row->user_id);
                // 	<source srcset="'.$usimgw.'" type="image/webp">
                //     <source srcset="'.$usimgw.'" type="image/webp">
                $html =
                    $html .
                    '<tr><td class="text-center pl-3 news_row">
                          <div class="checkbox mt-0 mb-0">
                            <label class="fontsize-12">
                              <input type="checkbox" class="checkbox" name="remember" value="' .
                    $row->user_id .
                    '">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                            </label>
                        </div>
                      </td>
                      <td class="font-size-14 height56v color666 fontfamily-regular editCust" data-id="' .
                    $uIDencoded .
                    '">
					 <picture>
					
					     <img src="' .
                    $usimg .
                    '" class="mr-3 width30 border-radius50">
					  </picture>
                        <p class="overflow_elips mb-0 display-ib color666">' .
                    $row->first_name .
                    ' ' .
                    $row->last_name .
                    '</p>
                        
                      </td>                      
                      <td class="font-size-14 height56v color666 fontfamily-regular editCust" data-id="' .
                    $uIDencoded .
                    '"><p class="mb-0 overflow_elips" style="width:175px;text-align:center;">' .
                    $row->email .
                    '</p></td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular editCust" data-id="' .
                    $uIDencoded .
                    '">' .
                    $mobile .
                    '</td>
                      <td class="text-center font-size-14 color666 fontfamily-regular height56v editCust" data-id="' .
                    $uIDencoded .
                    '">
                        <p class="mb-0 display-ib">' .
                    $this->lang->line('gender_' . $row->gender) .
                    '</p>
                      </td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center editCust" data-id="' .
                    $uIDencoded .
                    '">
                        <p class="mb-0 display-ib">' .
                    (!empty($row->bookcount) ? $row->bookcount : '0') .
                    '</p>
                      </td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center editCust" data-id="' .
                    $uIDencoded .
                    '">
                        <p class="mb-0 display-ib">' .
                    (!empty($row->booking_endDate)
                        ? date('d.m.Y', strtotime($row->booking_endDate))
                        : '-') .
                    '</p>
                      </td> 
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center editCust" data-id="' .
                    $uIDencoded .
                    '">
                        <p class="mb-0 display-ib">' .
                    ($row->temp_user
                        ? 'Kunde'
                        : 'App') .
                    '</p>
                      </td> 
                      <td class="text-center">';
                if ($row->notes == '') {
                    $html .=
                        '<a href="#" id="' .
                        url_encode($row->user_id) .
                        '" class="colororange font-size-14 fontfamily-medium text-underline a_hover_orange addnote" data-toggle="modal" data-target="#add-note">' .
                        $this->lang->line('Add_Note') .
                        '</a>';
                } else {
                    $html .=
                        '<a data-noteid="' .
                        url_encode($row->st_usernotes_id) .
                        '" href="#" class="color666 font-size-14 fontfamily-regular a_hover_666 overflow_elips editNotes" data-id="' .
                        $uIDencoded .
                        '"  data-toggle="popover" data-content="' .
                        strip_tags($row->notes) .
                        '">';
                    
                    if (strlen(strip_tags($row->notes)) > 18) {
                        $html .=
                            substr(strip_tags($row->notes), 0, 18) .
                            '...' .
                            '</a>';
                    } else {
                        $html .= strip_tags($row->notes) . '</a>';
                    }
                    $html .=
                        '</td>';
                }

                if ($row->temp_user) {
                    $html .= '<td style="text-align:center;">
                        <a href="#" data-id="'.url_encode($row->user_id).'" class="mr-3 editcustomerbutton" title="'.$this->lang->line("Edit").'" >
                            <img src="'.base_url("assets/frontend/images/new-icon-edit-service.png").'" class="width22"></a>
                        <a href="#" data-toggle="modal" data-target="#customer-delete-popup" class="deleteserviceclick"  title="'.$this->lang->line("Delete").'" id="'.url_encode($row->user_id).'"><img src="'.base_url("assets/frontend/images/new-icon-bin.png").'" class="width22"></a>
                    </td>';
                } else {
                    $html .= '<td style="text-align: center;">
                    <span style="margin-right: 1rem!important; width: 22px;display: inline-block;">&nbsp</span>
                    <a href="#" data-toggle="modal" data-target="#customer-delete-popup" class="deleteserviceclick"  title="'.$this->lang->line("Delete").'" id="'.url_encode($row->user_id).'"><img src="'.base_url("assets/frontend/images/new-icon-bin.png").'" class="width22"></a>
                    </td>';
                }
                $html .= '</td></tr>';
            }
        } else {
            $html =
                '<tr><td colspan="8" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="' .
                base_url('assets/frontend/images/no_listing.png') .
                '"><p style="margin-top: 20px;">' .
                $this->lang->line('dont_any_customer') .
                '</p></div></td></tr>';
        }

        echo json_encode([
            'success' => '1',
            'msg' => '',
            'html' => $html,
            'pagination' => $pagination,
        ]);
        die();
        //echo "<pre>"; print_r($booking); die;
        // $this->load->view('frontend/marchant/client_profile_view',$this->data);
        /*}  else echo json_encode(array('success'=>'0','msg'=>'','html'=>'','pagination'=>'')); die;*/
    }

    public function customer_list_old_06_11_2020($page = '0')
    {
        /*if(!empty($id))
         {*/
        //$cid=url_decode($id);
        $mid = $this->session->userdata('st_userid');
        $short_day = '';
        $category = '';
        //$short_day=date('Y-m-d', strtotime('-30 days'));
        $wh = '';
        $wh2 = '';
        $wh_c = '';
        if (!empty($_POST['category'])) {
            $category = $_POST['category'];
            $msql =
                "SELECT GROUP_CONCAT(DISTINCT user_id) as bid FROM st_booking_detail as bd JOIN st_merchant_category as mc ON mc.id=bd.service_id WHERE bd.mer_id='" .
                $mid .
                "' AND mc.category_id='" .
                $category .
                "' AND bd.user_id!='0' GROUP BY bd.user_id";
            //GROUP_CONCAT(id
            $wh_c = "AND st_merchant_category.category_id='" . $category . "'";
            $book_id = $this->user->custome_query($msql, 'result');

            print_r($book_id);
            die();
            if (!empty($book_id)) {
                $ct = count($book_id);
                $us_in = [];
                for ($i = 0; $i < $ct; $i++) {
                    $us_in[$i] = $book_id[$i]->bid;
                }
                //implode(',', $us_in);
                $wh = 'AND user_id IN (' . implode(',', $us_in) . ')';
            }
            /*if(!empty($book_id->bid))
             $wh = 'AND user_id IN('.$book_id->bid.')';*/
        }
        if (!empty($_POST['visit'])) {
            if ($_POST['visit'] == '1w') {
                $short_day = date('Y-m-d', strtotime('-7 days'));
            }
            if ($_POST['visit'] == '2w') {
                $short_day = date('Y-m-d', strtotime('-14 days'));
            }
            if ($_POST['visit'] == '4w') {
                $short_day = date('Y-m-d', strtotime('-28 days'));
            }
            if ($_POST['visit'] == '1m') {
                $short_day = date('Y-m-d', strtotime('-30 days'));
            }
            if ($_POST['visit'] == '2m') {
                $short_day = date('Y-m-d', strtotime('-60 days'));
            }
            if ($_POST['visit'] == '3m') {
                $short_day = date('Y-m-d', strtotime('-90 days'));
            }
            if ($_POST['visit'] == '4m') {
                $short_day = date('Y-m-d', strtotime('-120 days'));
            }
            if ($_POST['visit'] == '5m') {
                $short_day = date('Y-m-d', strtotime('-150 days'));
            }
            if ($_POST['visit'] == '6m') {
                $short_day = date('Y-m-d', strtotime('-180 days'));
            }
            if ($_POST['visit'] == '1y') {
                $short_day = date('Y-m-d', strtotime('-365 days'));
            }

            $wh2 = 'Having lastbook > "' . $short_day . '"';
        }

        $search = '';
        if (isset($_POST['search']) && !empty($_POST['search'])) {
            //$search = $_POST['search'];
            $search =
                "AND (st_users.first_name LIKE '%" .
                $_POST['search'] .
                "%' OR st_users.last_name LIKE '%" .
                $_POST['search'] .
                "%' OR st_users.email LIKE '%" .
                $_POST['search'] .
                "%')";
        }

        if (!empty($_POST['orderby'])) {
            $orderby = $_POST['orderby'];
            $shortby = $_POST['shortby'];
        } else {
            $orderby = 'st_booking.id';
            $shortby = 'ASC';
        }

        if ($_POST['newcheck'] != '') {
            //$where=array('st_booking.merchant_id'=>$mid,'st_users.newsletter' =>1,'st_users.status !='=>'deleted');
            $where =
                'WHERE st_booking.merchant_id="' .
                $mid .
                '" AND st_users.service_email="1" AND st_users.status!="deleted" ' .
                $wh .
                '';
        } else {
            //$where=array('st_booking.merchant_id'=>$mid,'st_users.status !='=>'deleted');
            $where =
                'WHERE st_booking.merchant_id="' .
                $mid .
                '" AND st_users.status!="deleted"';
        }

        $sub =
            "(SELECT st_booking.booking_time FROM st_merchant_category JOIN st_booking_detail ON st_booking_detail.service_id=st_merchant_category.id JOIN st_booking On st_booking.id=st_booking_detail.booking_id WHERE st_booking.user_id=st_users.id AND st_booking.status='completed' AND merchant_id='" .
            $mid .
            "' " .
            $wh_c .
            ' ORDER BY st_booking.booking_time DESC LIMIT 1) as lastbook';

        //(SELECT booking_time FROM st_booking WHERE user_id=st_users.id AND status='completed' AND merchant_id='".$mid."' ORDER BY id DESC LIMIT 1) as lastbook
        $sql =
            'SELECT user_id,' .
            $sub .
            ' FROM st_booking JOIN st_users ON st_users.id=st_booking.user_id ' .
            $where .
            ' ' .
            $wh_c.
            ' ' .
            $search .
            ' GROUP BY user_id ' .
            $wh2 .
            '';
        $totalcount = $this->user->custome_query($sql, 'result');

        //$totalcount = $data=$this->user->join_two_orderby('st_booking','st_users','user_id','id',$where,'user_id','st_booking.id','user_id',0,0,$search);
        if (!empty($totalcount)) {
            $total = count($totalcount);
        } else {
            $total = 0;
        }

        //echo $total;
        if (!empty($_POST['limit']) && $_POST['limit'] == 'all') {
            $limit = $total;
        } else {
            $limit = isset($_POST['limit']) ? $_POST['limit'] : PER_PAGE10; //PER_PAGE10
        }
        $url = 'merchant/customer_list';
        $segment = 3;
        // $page = mypaging($url,$total,$segment,$limit);
        $offset = 0;
        if ($page != 0) {
            $offset = ($page - 1) * $limit;
        }
        $config = [];
        $config['base_url'] = base_url() . $url;
        $config['total_rows'] = $total;
        $config['per_page'] = $limit;
        $config['use_page_numbers'] = true;
        $config['num_links'] = 2;
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['prev_link'] = '&lt;';
        $config['first_link'] = '&laquo;';
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();

        $field =
            'st_booking.id,user_id,st_booking.merchant_id,first_name,last_name,profile_pic,address,st_users.gender,st_users.email,mobile,(select notes from st_usernotes where user_id=st_users.id AND created_by="' .
            $mid .
            '") as notes,(select id from st_usernotes where user_id=st_users.id AND created_by="' .
            $mid .
            '") as note_id,(SELECT count(id) FROM st_booking WHERE user_id=st_users.id AND merchant_id="' .
            $mid .
            '") as bookcount, ' .
            $sub .
            '';
        //(SELECT booking_time FROM st_booking WHERE user_id=st_users.id AND status="completed" AND merchant_id="'.$mid.'" ORDER BY id DESC LIMIT 1) as lastbook

        if (!empty($_POST['orderby'])) {
            $order = $_POST['orderby'];
        } else {
            $order = 'st_booking.id';
        }

        if (!empty($_POST['shortby'])) {
            $sort = $_POST['shortby'];
        } else {
            $sort = 'asc';
        }

        $sql =
            'SELECT ' .
            $field .
            ' FROM st_booking JOIN st_users ON st_users.id=st_booking.user_id ' .
            $where .
            ' ' .
            $search .
            ' GROUP BY user_id ' .
            $wh2 .
            ' ORDER BY ' .
            $orderby .
            ' ' .
            $shortby .
            ' LIMIT ' .
            $config['per_page'] .
            ' OFFSET ' .
            $offset .
            '';
        $customer = $this->user->custome_query($sql, 'result');

        //echo $this->db->last_query(); die;
        //$customer=$this->user->join_two_orderby('st_booking','st_users','user_id','id',$where,$field,$order,'user_id',$config["per_page"],$offset,$search,$sort);

        $html = '';
        if (!empty($customer)) {
            foreach ($customer as $row) {
                if ($row->profile_pic != '') {
                    $usimg =
                        base_url('assets/uploads/users/') .
                        $row->user_id .
                        '/icon_' .
                        $row->profile_pic;
                } else {
                    $usimg = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                }

                if (!empty($row->mobile)) {
                    $mobile = $row->mobile;
                } else {
                    $mobile = '-';
                }

                $uIDencoded = url_encode($row->user_id);

                $html =
                    $html .
                    '<tr><td class="text-center pl-3 news_row">
                          <div class="checkbox mt-0 mb-0">
                            <label class="fontsize-12">
                              <input type="checkbox" class="checkbox" name="remember" value="' .
                    $row->user_id .
                    '">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                            </label>
                        </div>
                      </td>
                      <td class="font-size-14 height56v color666 fontfamily-regular editCust" data-id="' .
                    $uIDencoded .
                    '">
					 
                        <img src="' .
                    $usimg .
                    '" class="mr-3 width30 border-radius50">
                        <p class="overflow_elips mb-0 display-ib color666">' .
                    $row->first_name .
                    ' ' .
                    $row->last_name .
                    '</p>
                        
                      </td>                      
                      <td class="font-size-14 height56v color666 fontfamily-regular editCust" data-id="' .
                    $uIDencoded .
                    '"><p class="mb-0 overflow_elips" style="width:175px;">' .
                    $row->email .
                    '</p></td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular editCust" data-id="' .
                    $uIDencoded .
                    '">' .
                    $mobile .
                    '</td>
                      <td class="text-center font-size-14 color666 fontfamily-regular height56v editCust" data-id="' .
                    $uIDencoded .
                    '">
                        <p class="mb-0 display-ib">' .
                    $this->lang->line('gender_' . $row->gender) .
                    '</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center editCust" data-id="' .
                    $uIDencoded .
                    '">
                        <p class="mb-0 display-ib">' .
                    (!empty($row->bookcount) ? $row->bookcount : '0') .
                    '</p>
                      </td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center editCust" data-id="' .
                    $uIDencoded .
                    '">
                        <p class="mb-0 display-ib">' .
                    (!empty($row->lastbook)
                        ? date('d.m.Y', strtotime($row->lastbook))
                        : 'NA') .
                    '</p>
                      </td> 
                      <td class="text-center">';
                if ($row->notes == '') {
                    $html .=
                        '<a href="#" id="' .
                        url_encode($row->user_id) .
                        '" class="colororange font-size-14 fontfamily-medium text-underline a_hover_orange addnote" data-toggle="modal" data-target="#add-note">' .
                        $this->lang->line('Add_Note') .
                        '</a>';
                } else {
                    $html .=
                        '<a data-noteid="' .
                        url_encode($row->st_usernotes_id) .
                        '" href="#" class="color666 font-size-14 fontfamily-regular a_hover_666 overflow_elips editNotes" data-id="' .
                        $uIDencoded .
                        '"  data-toggle="popover" data-content="' .
                        strip_tags($row->notes) .
                        '">';
                    //data-notes="'.($row->notes).'"
                    if (strlen(strip_tags($row->notes)) > 18) {
                        $html .=
                            substr(strip_tags($row->notes), 0, 18) .
                            '...' .
                            '</a>';
                    } else {
                        $html .= strip_tags($row->notes) . '</a>';
                    }
                }

                $html .= '</td></tr>';
            }
        } else {
            $html =
                '<tr><td colspan="8" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="' .
                base_url('assets/frontend/images/no_listing.png') .
                '"><p style="margin-top: 20px;">' .
                $this->lang->line('dont_any_customer') .
                '</p></div></td></tr>';
        }

        echo json_encode([
            'success' => '1',
            'msg' => '',
            'html' => $html,
            'pagination' => $pagination,
        ]);
        die();
        //echo "<pre>"; print_r($booking); die;
        // $this->load->view('frontend/marchant/client_profile_view',$this->data);
        /*}  else echo json_encode(array('success'=>'0','msg'=>'','html'=>'','pagination'=>'')); die;*/
    }

    //**** Customer Listing ****//
    public function customer_list_old($page = '0')
    {
        /*if(!empty($id))
         {*/
        //$cid=url_decode($id);
        $mid = $this->session->userdata('st_userid');

        $search = '';
        if (isset($_POST['search']) && !empty($_POST['search'])) {
            $search = $_POST['search'];
        }

        if ($_POST['newcheck'] != '') {
            $where = [
                'st_booking.merchant_id' => $mid,
                'st_users.service_email' => 1,
                'st_users.status !=' => 'deleted',
            ];
        } else {
            $where = [
                'st_booking.merchant_id' => $mid,
                'st_users.status !=' => 'deleted',
            ];
        }

        $totalcount = $data = $this->user->join_two_orderby(
            'st_booking',
            'st_users',
            'user_id',
            'id',
            $where,
            'user_id',
            'st_booking.id',
            'user_id',
            0,
            0,
            $search
        );
        if (!empty($totalcount)) {
            $total = count($totalcount);
        } else {
            $total = 0;
        }

        $limit = isset($_POST['limit']) ? $_POST['limit'] : PER_PAGE10; //PER_PAGE10
        $url = 'merchant/customer_list';
        $segment = 3;
        // $page = mypaging($url,$total,$segment,$limit);
        $offset = 0;
        if ($page != 0) {
            $offset = ($page - 1) * $limit;
        }
        $config = [];
        $config['base_url'] = base_url() . $url;
        $config['total_rows'] = $total;
        $config['per_page'] = $limit;
        $config['use_page_numbers'] = true;
        $config['num_links'] = 2;
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['prev_link'] = '&lt;';
        $config['first_link'] = '&laquo;';
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();

        $field =
            'st_booking.id,user_id,st_booking.merchant_id,first_name,last_name,profile_pic,address,st_users.gender,st_users.email,mobile,(select notes from st_usernotes where user_id=st_users.id AND created_by="' .
            $mid .
            '") as notes,(select id from st_usernotes where user_id=st_users.id AND created_by="' .
            $mid .
            '") as note_id,(SELECT count(id) FROM st_booking WHERE user_id=st_users.id AND merchant_id="' .
            $mid .
            '") as bookcount,(SELECT booking_time FROM st_booking WHERE user_id=st_users.id AND status="completed" AND merchant_id="' .
            $mid .
            '" ORDER BY id DESC LIMIT 1) as lastbook';

        if (!empty($_POST['orderby'])) {
            $order = $_POST['orderby'];
        } else {
            $order = 'st_booking.id';
        }

        if (!empty($_POST['shortby'])) {
            $sort = $_POST['shortby'];
        } else {
            $sort = 'asc';
        }

        $customer = $this->user->join_two_orderby(
            'st_booking',
            'st_users',
            'user_id',
            'id',
            $where,
            $field,
            $order,
            'user_id',
            $config['per_page'],
            $offset,
            $search,
            $sort
        );
        //echo $this->db->last_query().'<pre>'; print_r($customer); die;
        $html = '';
        if (!empty($customer)) {
            foreach ($customer as $row) {
                if ($row->profile_pic != '') {
                    $usimg =
                        base_url('assets/uploads/users/') .
                        $row->user_id .
                        '/icon_' .
                        $row->profile_pic;
                } else {
                    $usimg = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                }

                if (!empty($row->mobile)) {
                    $mobile = $row->mobile;
                } else {
                    $mobile = '-';
                }

                $uIDencoded = url_encode($row->user_id);

                $html =
                    $html .
                    '<tr>
											<td class="text-center pl-3 news_row">
												<div class="checkbox mt-0 mb-0">
													<label class="fontsize-12">
														<input type="checkbox" class="checkbox" name="remember" value="' . $row->user_id . '">
                            <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                          </label>
                        </div>
                      </td>
                      <td class="font-size-14 height56v color666 fontfamily-regular editCust" data-id="' . $uIDencoded .'">
                        <img src="' . $usimg .'" class="mr-3 width30 border-radius50">
                        <p class="overflow_elips mb-0 display-ib color666">' . $row->first_name . ' ' . $row->last_name . '</p>
                      </td>                      
                      <td class="font-size-14 height56v color666 fontfamily-regular editCust" data-id="' . $uIDencoded . '">
												<p class="mb-0 overflow_elips" style="width:175px;">' . $row->email . '</p>
											</td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular editCust" data-id="' . $uIDencoded . '">' . $mobile . '</td>
                      <td class="text-center font-size-14 color666 fontfamily-regular height56v editCust" data-id="' . $uIDencoded . '">
                        <p class="mb-0 display-ib">' . $this->lang->line('gender_' . $row->gender) . '</p>
                      </td>
											<td class="font-size-14 color666 fontfamily-regular height56v text-center editCust" data-id="' . $uIDencoded . '">
                        <p class="mb-0 display-ib">' . (!empty($row->bookcount) ? $row->bookcount : '0') . '</p>
                      </td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center editCust" data-id="' . $uIDencoded . '">
                        <p class="mb-0 display-ib">' .
												(!empty($row->lastbook)
                        	? date('d.m.Y', strtotime($row->lastbook))
                        	: 'NA') .
                    		'</p>
                      </td> 
                      <td class="text-center">';
											if ($row->notes == '') {
												$html .=
                        '<a href="#" id="' . url_encode($row->user_id) . '" class="colororange font-size-14 fontfamily-medium text-underline a_hover_orange addnote" data-toggle="modal" data-target="#add-note">' .
                        	$this->lang->line('Add_Note') .
                        '</a>';
											} else {
													$html .=
                        '<a data-noteid="' .
                        url_encode($row->note_id) .
                        '" href="#" class="color666 font-size-14 fontfamily-regular a_hover_666 overflow_elips editNotes" data-id="' .
                        $uIDencoded .
                        '"  data-toggle="popover" data-content="' .
                        strip_tags($row->notes) .
                        '">';
												//data-notes="'.($row->notes).'"
												if (strlen(strip_tags($row->notes)) > 18) {
														$html .=
																substr(strip_tags($row->notes), 0, 18) .
																'...' .
																'</a>';
												} else {
														$html .= strip_tags($row->notes) . '</a>';
												}
											}
											$html .= '</td></tr>';
								}
        } else {
            $html =
                '<tr><td colspan="8" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="' .
                base_url('assets/frontend/images/no_listing.png') .
                '"><p style="margin-top: 20px;">' .
                $this->lang->line('dont_any_customer') .
                '</p></div></td></tr>';
        }

        echo json_encode([
            'success' => '1',
            'msg' => '',
            'html' => $html,
            'pagination' => $pagination,
        ]);
        die();
        //echo "<pre>"; print_r($booking); die;
        // $this->load->view('frontend/marchant/client_profile_view',$this->data);
        /*}  else echo json_encode(array('success'=>'0','msg'=>'','html'=>'','pagination'=>'')); die;*/
    }

    //***** Add notes on user ****//
    public function addnotes()
    {
        if ($this->session->userdata('access') != 'marchant') {
            redirect(base_url());
        }
        $id = url_decode($_POST['booking_id']);
        $upd['notes'] = $_POST['txtnote'];
        $mid = $this->session->userdata('st_userid');
        if (
            $this->user->countResult('st_usernotes', [
                'user_id' => $id,
                'created_by' => $mid,
            ]) > 0
        ) {
            $this->user->update('st_usernotes', $upd, [
                'user_id' => $id,
                'created_by' => $mid,
            ]);
        } else {
            $insert_note['user_id'] = $id;
            $insert_note['notes'] = $_POST['txtnote'];
            $insert_note['created_by'] = $mid;
            $insert_note['created_on'] = date('Y-m-d H:i:s');
            $this->user->insert('st_usernotes', $insert_note);
        }

        redirect(base_url('merchant/customers'));
    }
    public function addnotes_fromlist()
    {
        if ($this->session->userdata('access') != 'marchant') {
            redirect(base_url());
        }
        $id = url_decode($_POST['id']);
        $upd['notes'] = $_POST['note'];
        $mid = $this->session->userdata('st_userid');
        if (
            $this->user->countResult('st_usernotes', [
                'user_id' => $id,
                'created_by' => $mid,
            ]) > 0
        ) {
            $this->user->update('st_usernotes', $upd, [
                'user_id' => $id,
                'created_by' => $mid,
            ]);
        } else {
            $insert_note['user_id'] = $id;
            $insert_note['notes'] = $_POST['note']; //$_POST['txtnote'];
            $insert_note['created_by'] = $mid;
            $insert_note['created_on'] = date('Y-m-d H:i:s');
            $this->user->insert('st_usernotes', $insert_note);
        }

        //redirect(base_url('merchant/customers'));
    }

    //**** Payment List ****//
    public function payment_list()
    {
        if ($this->session->userdata('access') != 'marchant') {
            redirect(base_url());
        }
        $uid = $this->session->userdata('st_userid');
        $where = [
            'user_id' => $uid,
            'status !=' => 'open',
            'status !=' => 'draft',
            'amount >' => '0',
        ];
        $totalcount = $data = $this->user->select_row(
            'st_payments',
            'count(id) as tcount ',
            $where
        );
        if (!empty($totalcount->tcount)) {
            $total = $totalcount->tcount;
        } else {
            $total = 0;
        }

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10; //PER_PAGE10
        $url = 'merchant/payment_list';
        $segment = 3;
        $page = mypaging($url, $total, $segment, $limit);
        $field = '*';
        $this->data['payment'] = $this->user->select(
            'st_payments',
            '*',
            $where,
            '',
            'id',
            'desc',
            $page['per_page'],
            $page['offset']
        );

        $planDetails = $this->user->select_row(
            'st_users',
            'stripe_id,card_id,subscription_status,end_date',
            ['id' => $uid]
        );

        // $getdata = \Stripe\Invoice::upcoming(["customer" => "cus_HZweFBe0vFY5Jy"]);

        if (
            !empty($planDetails->card_id) &&
            $planDetails->subscription_status == 'active'
        ) {
            //echo substr($planDetails->card_id,0,4); die;
            
            $paymentMethod = \Stripe\PaymentMethod::retrieve(
                $planDetails->card_id,
                []
            );
            if ($paymentMethod['card']) {
                $paymentDetail['brand'] = 'Kreditkarte von';
                $paymentDetail['last4'] =
                    $paymentMethod['card']['last4'] . ' eingezogen';
            } else {
                $paymentDetail['brand'] = 'SEPA-Lastschrifteinzug von ';
                $paymentDetail['last4'] =
                    $paymentMethod['sepa_debit']['last4'] . ' eingezogen';
            }

            $this->data['card_details'] = $paymentDetail;
            $this->data['plan_details'] = $planDetails;
        }

        $this->load->view('frontend/marchant/payment_list', $this->data);
    }

    //**** News Letter ***//
    public function newsletter($id = '')
    {
        if ($id != '') {
            $id = url_decode($id);
        }

        if (isset($_POST['txtsubject'])) {
            //print_r($_POST); die;

            $allowed = ['gif', 'png', 'jpg', 'jpeg', 'svg'];
            $uid = $this->session->userdata('st_userid');
            $upload_path = 'assets/uploads/newsletter/' . $uid . '/';

            //~ $filename = explode('.',$_FILES["newsImg"]["name"]);
            //~ $ext = $filename[count($filename)-1];
            //~ $ext = strtolower($ext);
            //~ if(!is_dir($path)){ @mkdir($path ,0777,TRUE);}

            //~ if(isset($_FILES['newsImg']['name']) && $_FILES['newsImg']['name']!="" && (!in_array($ext,$allowed)  || $_FILES["newsImg"]["size"] > 4000000))
            //~ {
            //~ echo $obj = json_encode(array('success'=>0,'html'=>'ImageErr')); die;
            //~ }

            //~ if(isset($_FILES['newsImg']['name']) && $_FILES['newsImg']['name']!="")
            //~ {
            //~ $this->load->library('Image_moo');
            //~ $filename = 'Newsletter_'.time().$this->session->userdata('user_id').'.'.$filename[count($filename)-1];
            //~ $tmpfile = $_FILES["newsImg"]["tmp_name"];
            //~ $uploadfil = move_uploaded_file($tmpfile, $path.$filename);

            //~ foreach(array("thumb_"=>array(200,200)) as $key=>$val){
            //~ $this->image_moo->load($path.$filename)->set_jpeg_quality(100)->resize_crop($val[0], $val[1])->save("{$path}{$key}{$filename}",true);

            //~ }
            //~ $InserData['image_path']=$filename;

            //~ if(isset($_POST['old_image'])){
            //~ if (file_exists($path.$_POST['old_image']))
            //~ {
            //~ unlink($path.$_POST['old_image']);
            //~ unlink($path.'thumb_'.$_POST['old_image']);
            //~ }
            //~ }

            //~ }

            $filepath =
                'assets/uploads/temp/' .
                $this->session->userdata('st_userid') .
                '/';

            @mkdir($upload_path, 0777, true);
            $filepath2 = $upload_path;

            $images = scandir($filepath);
            // echo "<pre>"; print_r($images); die;
            $nimages = '';
            $InserData = [];

            for ($i = 2; $i < count($images); $i++) {
                if (file_exists($filepath . $images[$i])) {
                    echo file_exists($filepath . $images[$i]);
                    rename($filepath . $images[$i], $filepath2 . $images[$i]);
                    $nimages = $images[$i];
                }
            }
            if (!empty($nimages)) {
                $InserData['image_path'] = $nimages;
            }

            $pathck = $upload_path;
            //var_dump($nimages); die;
            if (
                !empty($_POST['old_img']) &&
                !empty($nimages) &&
                file_exists($pathck . $_POST['old_img'])
            ) {
                $del_file = $pathck . $_POST['old_img'];
                //echo $del_file; die;
                unlink($del_file);
            }

            //echo "<pre>"; print_r($InserData); die;

            $InserData['merchant_id'] = $uid;
            $InserData['subject'] = $_POST['txtsubject'];
            $InserData['description'] = $_POST['description'];
            $InserData['footer'] = $_POST['txtfooter'];
            $InserData['created_on'] = date('Y-m-d H:i:s');
            $InserData['created_by'] = $uid;

            if ($id != '') {
                $InserData['updated_by'] = $uid;
                $InserData['updated_on'] = date('Y-m-d H:i:s');
                $res = $this->user->update('st_newsletter', $InserData, [
                    'id' => $id,
                ]);
                if ($res) {
                    $this->session->set_flashdata(
                        'success',
                        'Newsletter-Vorlage erfolgreich bearbeitet'
                    );
                } else {
                    $this->session->set_flashdata(
                        'error',
                        'Sorry unable to process.'
                    );
                }
            } else {
                $tid = $this->user->insert('st_newsletter', $InserData);
                if ($tid) {
                    $this->session->set_flashdata(
                        'success',
                        'Newsletter-Vorlage erfolgreich erstellt'
                    );
                } else {
                    $this->session->set_flashdata(
                        'error',
                        'Sorry unable to process.'
                    );
                }
            }

            redirect(base_url('merchant/selectnewesletter'));
        } else {
            $this->data['newsletter'] = [];
            if ($id != '') {
                $this->data[
                    'newsletter'
                ] = $this->user->select_row(
                    'st_newsletter',
                    'id,merchant_id,image_path,subject,description,footer',
                    ['id' => $id]
                );
            }
            $this->load->view('frontend/marchant/newsletter', $this->data);
        }
    }

    public function editCustomer() {
        if ($this->session->userdata('access') == 'marchant') {
            $cusdata = $this->user->select_row(
                'st_users',
                '*',
                [
                    'id' => url_decode($_GET['id']),
                ]
            );

            $notes = $this->user->select_row('st_usernotes', 'notes', [
                'user_id' => url_decode($_GET['id']),
                'created_by' => $this->session->userdata('st_userid')
            ]);

            $html = $this->load->view(
                'frontend/marchant/edit_new_customer',
                ['customer' => $cusdata, 'notes' => $notes],
                true
            );
            echo json_encode(['success' => 1, 'html' => $html]);
        } else {
            echo json_encode(['success' => 0]);
        }
    }

    public function update_temp_customer() {
        $usid = $this->session->userdata('st_userid');
	  
        if ($usid) {

            $cusid = url_decode($this->input->post('customerid'));
            $email = strtolower($this->input->post('email'));
            $gender = $this->input->post('gender');
            $notification = $this->input->post('send_notification') == 'on' ? 1 : 0;
            $cityCheck = $this->input->post('city');
            if (!empty($cityCheck)) {
                $city = $this->input->post('city');
            } else {
                $city = "";
            }
            if (!empty($this->input->post('dob'))) {
                $dob = date('Y-m-d', strtotime($this->input->post('dob')));
            } else {
                $dob = "";
            }

            $nimages = '';
            if (!empty($this->input->post('hasimage'))) {
                $upload_path = 'assets/uploads/users/' . $cusid . '/icon_';
                $filepath = 'assets/uploads/profile_temp/' . $usid . '/';
    
                @mkdir($upload_path, 0777, true);
                @mkdir($filepath, 0777, true);
                $filepath2 = $upload_path;
    
                $images = scandir($filepath);
                $InserData = array();
    
                if (file_exists($filepath . $images[2])) {
                    // echo file_exists($filepath.$images[2]);
                    rename($filepath . $images[2], $filepath2 . $images[2]);
                    $nimages = $images[2];
                }
            }
            $additional_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'gender' => $gender ? $gender : 'male',
                'email' => $email,
                'mobile' => $this->input->post('telephone'),
                'zip' => (!empty($this->input->post('post_code')) ? $this->input->post('post_code') : ''),
                'address' => (!empty($this->input->post('location')) ? $this->input->post('location') : ''),
                'latitude' => (!empty($this->input->post('latitude')) ? $this->input->post('latitude') : ''),
                'longitude' => (!empty($this->input->post('longitude')) ? $this->input->post('longitude') : ''),
                'country' => (!empty($this->input->post('country')) ? $this->input->post('country') : ''),
                'city' => $city,
                'dob' => $dob,
                'profile_pic' => $nimages,
                'service_email' => $notification,
            ];
            
            $res = $this->user->update('st_users',$additional_data, ['id' => $cusid]);

            if (!empty($this->input->post('notes'))) {
                $exist = $this->user->select('st_usernotes', 'id', [
                    'user_id' => $cusid,
                    'created_by' => $usid
                ]);

                if (!empty($exist)) {
                    $this->user->update('st_usernotes', ['notes' => $this->input->post('notes')], [
                        'user_id' => $cusid,
                        'created_by' => $usid
                    ]);
                } else {
                    $insert_note['user_id'] = $cusid;
                    $insert_note['notes'] = $this->input->post('notes');
                    $insert_note['created_by'] = $usid;
                    $insert_note['created_on'] = date('Y-m-d H:i:s');
                    $this->user->insert('st_usernotes', $insert_note);
                }
            }
            echo json_encode(array('success' => 1)); die;
        }
        else {
            echo json_encode(array('success' =>0)); die;
        }
    }

    //**** Select news Letter Listing ****//
    public function selectnewesletter()
    {
        $uid = $this->session->userdata('st_userid');
        $this->data['newsletter'] = $this->user->select(
            'st_newsletter',
            'id,merchant_id,image_path,subject,description,footer',
            ['merchant_id' => $this->session->userdata('st_userid')]
        );
        $this->load->view('frontend/marchant/newsletter_listing', $this->data);
    }

    //**** Delete Newsletter ****//
    public function deletenewsLetter()
    {
        $id = url_decode($_POST['tid']);
        $uid = $this->session->userdata('st_userid');
        $path = 'assets/uploads/newsletter/' . $uid . '/';
        $data = $this->user->select_row('st_newsletter', 'image_path', [
            'id' => $id,
        ]);
        if ($this->user->delete('st_newsletter', ['id' => $id])) {
            if (!empty($data)) {
                if (
                    file_exists($path . $data->image_path) &&
                    $data->image_path != ''
                ) {
                    unlink($path . $data->image_path);
                    //unlink($path.'thumb_'.$data->image_path);
                }
            }
            echo json_encode(['success' => 1, 'ids' => $id]);
        } else {
            echo json_encode(['success' => 0, 'ids']);
        }
    }

    //**** get opening hour of salon ****//
    function get_opning_hour()
    {
        if (
            !empty($_POST['date']) &&
            !empty($this->session->userdata('st_userid'))
        ) {
            $dayName = date('l', strtotime($_POST['date']));
            $dateslect = date('Y-m-d', strtotime($_POST['date']));
            $dayName = strtolower($dayName);
            $id = url_decode($_POST['eid']);
            $bookid = url_decode($_POST['bk_id']);

            $info = $this->user->select_row(
                'st_booking',
                'id,total_minutes,merchant_id,total_buffer,booking_time',
                ['id' => $bookid, 'status' => 'confirmed']
            );

            $emptyhtml =
                '<h6 class="mt-3">Leider sind am ' .
                date('d', strtotime($dateslect)) .
                '. ' .
                get_month_de_translation(date('F',strtotime($dateslect))).
                ' keine Termine verfügbar.</h6>
							<h6>Bitte wähle einen anderen Tag</h6>';
            if (!empty($info)) {
                $mrntid = $info->merchant_id;

                $availablity1 = $this->user->select_row(
                    'st_availability',
                    'id,type',
                    ['user_id' => $mrntid, 'days' => $dayName, 'type' => 'open']
                );
                //~ $availablity = $this->user->select_row('st_availability','days,type,starttime,endtime,starttime_two,endtime_two',array('user_id'=>$mrntid,'days'=>$dayName));
                if (!empty($availablity1)) {
                    $availablity = $this->user->select_row(
                        'st_availability',
                        'days,type,starttime,endtime,starttime_two,endtime_two',
                        ['user_id' => $id, 'days' => $dayName, 'type' => 'open']
                    );
                    $html = '';

                    if (!empty($availablity)) { 
                        $totaldurationTim =
                            $info->total_minutes + $info->total_buffer;

                        $detailSelectQry = '';

                        $bookdteialQuery =
                            "SELECT bd.id,service_type,bd.duration,bd.buffer_time,bd.setuptime,bd.processtime,bd.finishtime,mc.parent_service_id,oa.starttime,oa.endtime,mc.price,mc.discount_price FROM st_booking_detail as bd JOIN st_merchant_category as mc ON mc.id=bd.service_id  LEFT JOIN st_offer_availability as oa ON oa.service_id=bd.service_id AND oa.days='" .
                            $dayName .
                            "' AND oa.type='open' WHERE booking_id=" .
                            $bookid;

                        $serviceDetails = $this->user->custome_query(
                            $bookdteialQuery,
                            'result'
                        );

                        // echo '<pre>'; print_r($serviceDetails); die;

                        //$serviceDetails   = $this->user->select('st_booking_detail','id,service_type,duration,buffer_time,setuptime,processtime,finishtime',array('booking_id'=>$bookid));

                        //$html=$html.'<option value="">Select time</option>';

                        $originstart = $availablity->starttime;
                        $originend = $availablity->endtime;
                        $start = getPreExtraHrs($mrntid); //you can write here 00:00:00 but not need to it
                        $end = getAfterExtraHrs($mrntid);

                        $tStart = strtotime($start);
                        $tEnd = strtotime(
                            date(
                                'H:i:s',
                                strtotime(
                                    $end . '- ' . $totaldurationTim . ' minutes'
                                )
                            )
                        );

                        $currtime = date('H:i:s');
                        $crntdate = date('Y-m-d');
                        $tNow = $tStart;
                        $k = 1;
                        $checkedTime = '';
                        if (
                            date('Y-m-d', strtotime($info->booking_time)) ==
                            $_POST['date']
                        ) {
                            $checkedTime = date(
                                'H:i:s',
                                strtotime($info->booking_time)
                            );
                        }

                        while ($tNow <= $tEnd) {
                            $nowTime = date('H:i:s', $tNow);
                            //~ echo $nowTime."==".$currtime."==".$date."==".$crntdate."</br>";
                            if (
                                ($dateslect == $crntdate &&
                                    $currtime <= $nowTime) ||
                                $dateslect != $crntdate
                            ) {
                                $timeArray = [];
                                $ikj = 0;
                                $strtodatyetime =
                                    $dateslect . ' ' . date('H:i:s', $tNow);

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

                                    if (
                                        !empty($row->starttime) &&
                                        !empty($row->endtime) &&
                                        $row->starttime <= $nowTime &&
                                        $row->endtime >= $nowTime
                                    ) {
                                        if (!empty($row->discount_price)) {
                                            $dis =
                                                $row->price -
                                                $row->discount_price +
                                                $dis;
                                            $total = $row->price + $total;
                                        } else {
                                            $total = $row->price + $total;
                                        }
                                    } else {
                                        $total = $row->price + $total;
                                    }

                                    $timeArray[$ikj] = new stdClass();

                                    $bkstartTime = $strtodatyetime;
                                    $timeArray[$ikj]->start = $bkstartTime;

                                    if ($row->service_type == 1) {
                                        $bkEndTime = date(
                                            'Y-m-d H:i:s',
                                            strtotime(
                                                '' .
                                                    $bkstartTime .
                                                    ' + ' .
                                                    $row->setuptime .
                                                    ' minute'
                                            )
                                        );
                                        $timeArray[$ikj]->end = $bkEndTime;
                                        $ikj++;

                                        $finishStart = date(
                                            'Y-m-d H:i:s',
                                            strtotime(
                                                '' .
                                                    $bkEndTime .
                                                    ' + ' .
                                                    $row->processtime .
                                                    ' minute'
                                            )
                                        );
                                        $timeArray[$ikj] = new stdClass();
                                        $timeArray[$ikj]->start = $finishStart;

                                        $finishEnd = date(
                                            'Y-m-d H:i:s',
                                            strtotime(
                                                '' .
                                                    $finishStart .
                                                    ' + ' .
                                                    $row->finishtime .
                                                    ' minute'
                                            )
                                        );
                                        $timeArray[$ikj]->end = $finishEnd;
                                        $ikj++;

                                        $strtodatyetime = $finishEnd;
                                    } else {
                                        $totalMin =
                                            $row->duration + $row->buffer_time;

                                        $bkEndTime = date(
                                            'Y-m-d H:i:s',
                                            strtotime(
                                                '' .
                                                    $bkstartTime .
                                                    ' + ' .
                                                    $totalMin .
                                                    ' minute'
                                            )
                                        );
                                        $timeArray[$ikj]->end = $bkEndTime;
                                        $ikj++;

                                        $strtodatyetime = $bkEndTime;
                                    }
                                }

                                $resultCheckSlot = checkTimeSlotsMerchant(
                                    $timeArray,
                                    $id,
                                    $mrntid,
                                    $totaldurationTim,
                                    $bookid
                                );

                                if ($resultCheckSlot == true) {
                                    $pdisc = '';

                                    if (!empty($dis)) {
                                        $pdisc = price_formate($total) . ' €';
                                    }

                                    $ptotal = $total - $dis;

                                    $checkClass = '';
                                    $selctedClass = '';
                                    if ($nowTime == $checkedTime) {
                                        $checkClass = 'checked';
                                        $selctedClass = ' selected_time';
                                    }

                                    $html =
                                        $html .
                                        '<li class="select-time-price lineheight40 ' .
                                        $selctedClass .
                                        '">
															<input type="radio" id="id_time-1price' .
                                        $k .
                                        '" name="chg_time" class="slectTime" value="' .
                                        date('H:i:s', $tNow) .
                                        '" ' .
                                        $checkClass .
                                        '>
															<label for="id_time-1price' .
                                        $k .
                                        '">
															  <span class="text-left pl-10">' .
                                        date('H:i', $tNow) .
                                        '</span>
															  <span>													
																 <span class="new-price float-right">' .
                                        price_formate($ptotal) .
                                        ' €</span>													 
																<span class="old-price "><del>' .
                                        $pdisc .
                                        ' </del></span>
															  </span>
															</label>
														  </li>';
                                    $k++;
                                }
                            }
                            $tNow = strtotime('+15 minutes', $tNow);
                        }

                        if (
                            !empty($availablity->starttime_two) &&
                            !empty($availablity->endtime_two)
                        ) {
                            $start = getPreExtraHrs($mrntid); //you can write here 00:00:00 but not need to it
                            $end = getAfterExtraHrs($mrntid);

                            $tStart = strtotime($start);
                            $tEnd = strtotime(
                                date(
                                    'H:i:s',
                                    strtotime(
                                        $end .
                                            '- ' .
                                            $totaldurationTim .
                                            ' minutes'
                                    )
                                )
                            );

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

                                $nowTime = date('H:i:s', $tNow);
                                //~ echo $nowTime."==".$currtime."==".$date."==".$crntdate."</br>";
                                if (
                                    ($dateslect == $crntdate &&
                                        $currtime <= $nowTime) ||
                                    $dateslect != $crntdate
                                ) {
                                    $timeArray = [];
                                    $ikj = 0;
                                    $strtodatyetime =
                                        $dateslect . ' ' . date('H:i:s', $tNow);
                                    $dis = 0;
                                    $total = 0;

                                    foreach ($serviceDetails as $row) {
                                        if (
                                            !empty($row->starttime) &&
                                            !empty($row->endtime) &&
                                            $row->starttime <= $nowTime &&
                                            $row->endtime >= $nowTime
                                        ) {
                                            if (!empty($row->discount_price)) {
                                                $dis =
                                                    $row->price -
                                                    $row->discount_price +
                                                    $dis;
                                                $total = $row->price + $total;
                                            } else {
                                                $total = $row->price + $total;
                                            }
                                        } else {
                                            $total = $row->price + $total;
                                        }

                                        $timeArray[$ikj] = new stdClass();

                                        $bkstartTime = $strtodatyetime;
                                        $timeArray[$ikj]->start = $bkstartTime;

                                        if ($row->stype == 1) {
                                            $bkEndTime = date(
                                                'Y-m-d H:i:s',
                                                strtotime(
                                                    '' .
                                                        $bkstartTime .
                                                        ' + ' .
                                                        $row->setuptime .
                                                        ' minute'
                                                )
                                            );
                                            $timeArray[$ikj]->end = $bkEndTime;
                                            $ikj++;

                                            $finishStart = date(
                                                'Y-m-d H:i:s',
                                                strtotime(
                                                    '' .
                                                        $bkEndTime .
                                                        ' + ' .
                                                        $row->processtime .
                                                        ' minute'
                                                )
                                            );
                                            $timeArray[$ikj] = new stdClass();
                                            $timeArray[
                                                $ikj
                                            ]->start = $finishStart;

                                            $finishEnd = date(
                                                'Y-m-d H:i:s',
                                                strtotime(
                                                    '' .
                                                        $finishStart .
                                                        ' + ' .
                                                        $row->finishtime .
                                                        ' minute'
                                                )
                                            );
                                            $timeArray[$ikj]->end = $finishEnd;
                                            $ikj++;

                                            $strtodatyetime = $finishEnd;
                                        } else {
                                            $totalMin =
                                                $row->duration +
                                                $row->buffer_time;

                                            $bkEndTime = date(
                                                'Y-m-d H:i:s',
                                                strtotime(
                                                    '' .
                                                        $bkstartTime .
                                                        ' + ' .
                                                        $totalMin .
                                                        ' minute'
                                                )
                                            );
                                            $timeArray[$ikj]->end = $bkEndTime;
                                            $ikj++;

                                            $strtodatyetime = $bkEndTime;
                                        }
                                    }

                                    $resultCheckSlot = checkTimeSlotsMerchant(
                                        $timeArray,
                                        $id,
                                        $mrntid,
                                        $totaldurationTim
                                    );

                                    if (strtotime($tNow) < strtotime($originstart) || strtotime($tNow) > strtotime($originstart))
                                        $resultCheckSlot = true;

                                    if ($resultCheckSlot == true) {
                                        $pdisc = '';

                                        if (!empty($dis)) {
                                            $pdisc = $total . ' €';
                                        }

                                        $ptotal = $total - $dis;

                                        $checkClass = '';
                                        $selctedClass = '';

                                        if ($nowTime == $checkedTime) {
                                            $checkClass = 'checked';
                                            $selctedClass = ' selected_time';
                                        }

                                        $html =
                                            $html .
                                            '<li class="select-time-price lineheight40">
																	<input type="radio" id="id_time-2price' .
                                            $k .
                                            '" name="chg_time" class="slectTime ' .
                                            $selctedClass .
                                            '" value="' .
                                            date('H:i:s', $tNow) .
                                            '" ' .
                                            $checkClass .
                                            '>
																		<label for="id_time-2price' .
                                            $k .
                                            '">
																		  <span class="text-left pl-10">' .
                                            date('H:i', $tNow) .
                                            '</span>
																		  <span>													
																			<span class="new-price float-right">' .
                                            $ptotal .
                                            ' €</span>													 
																			<span class="old-price "><del>' .
                                            $pdisc .
                                            '</del></span>
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

                            echo json_encode([
                                'success' => 1,
                                'message' => 'success1',
                                'html' => $html,
                            ]);
                        } else {
                            echo json_encode([
                                'success' => 1,
                                'message' => 'success',
                                'html' => $html,
                            ]);
                        }

                    } else {
                        echo json_encode([
                            'success' => 1,
                            'message' => 'employee not work for selected day',
                            'html' => $emptyhtml,
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => 1,
                        'message' => 'Salon not update the time',
                        'html' => $emptyhtml,
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => 1,
                    'message' => 'You can not reschedule this booking',
                    'html' => $emptyhtml,
                ]);
            }
        } else {
            echo json_encode([
                'success' => 1,
                'message' => 'Please select a valid date.',
                'html' => $emptyhtml,
            ]);
        }
    }

    //**** append new employee time div ****//
    function employeenewtimediv()
    {
        $day = $this->user->select_row(
            'st_availability',
            'days,starttime,endtime',
            [
                'user_id' => $_POST['merchant_id'],
                'days' => $_POST['day_nm'],
                'type' => 'open',
            ]
        );
        $html = '';
        if (!empty($day)) {
            // print_r($day); die;
            //~ $html.='<div class="d-flex" id="remove_'.$_POST['day_nm'].'">
            //~ <div class="display-ib mr-20 ml-auto">
            //~ <div class="form-group form-group-mb-50">
            //~ <select class="widthfit btn-group custome-select pl-2" name="starttwo['.$_POST['day_nm'].']" id="starttwo_'.$_POST['day_nm'].'">
            //~ <option value="">Start time</option>';
            //~ $start = "00:00";
            //~ $end = "23:00";

            //~ $tStart = strtotime($salon_avl->starttime);
            //~ $tEnd = strtotime($salon_avl->endtime);
            //~ $tNow = $tStart;
            //~ while($tNow <= $tEnd){
            //~ $html.='<option value="'.date("H:i:s",$tNow).'">'.date("H:i",$tNow).'</option>';
            //~ $tNow = strtotime('+60 minutes',$tNow);
            //~ }
            //~ $html.='</select>
            //~ <span id="Serrtwo_'.$_POST['day_nm'].'" class="error"></span>
            //~ </div>
            //~ </div>
            //~ <div class="display-ib">
            //~ <div class="form-group form-group-mb-50">
            //~ <select class="widthfit btn-group custome-select pl-2" name="endtwo['.$_POST['day_nm'].']"  id="endtwo_'.$_POST['day_nm'].'">
            //~ <option value="">End time</option>';
            //~ $start = "01:00";
            //~ $end = "23:00";

            //~ $tStart = strtotime($salon_avl->starttime);
            //~ $tEnd = strtotime($salon_avl->endtime);
            //~ $tNow = $tStart;
            //~ while($tNow <= $tEnd){

            //~ $html.='<option value="'.date("H:i:s",$tNow).'">'.date("H:i",$tNow).'</option>';
            //~ $tNow = strtotime('+60 minutes',$tNow);
            //~ }

            //~ $html.='</select>
            //~ <span id="Eerrtwo_'.$_POST['day_nm'].'" class="error"></span>
            //~ </div>
            //~ </div>

            //~ <a href="javascript:void(0);" id="'.$_POST['day_nm'].'" class="mt-1 display-b ml-2 remove_timeset" data-toggle="modal" data-target="">
            //~ <img src="'.base_url('assets/frontend/images/remove.svg').'" class="width24v">
            //~ </a>
            //~ </div>';
            $startHtml = '';

            if (!empty($day->starttime) && !empty($day->endtime)) {
                $tStart = strtotime($day->starttime);
                $tEnd = strtotime($day->endtime);
                $tNow = $tStart;
                while ($tNow <= $tEnd) {
                    $startHtml .=
                        '<li class="radiobox-image">
															<input type="radio" id="id_time_two' .
                        strtolower($day->days) .
                        $tNow .
                        '" name="' .
                        strtolower($day->days) .
                        '_start_two" class="start_' .
                        strtolower($day->days) .
                        '" data-val="" value="' .
                        date('H:i:s', $tNow) .
                        '">
															<label for="id_time_two' .
                        strtolower($day->days) .
                        $tNow .
                        '">
															' .
                        date('H:i', $tNow) .
                        '                  
														  </label>
														  </li>';
                    $tNow = strtotime('+60 minutes', $tNow);
                }
            }

            $endHtml = '';
            if (!empty($day->starttime) && !empty($day->endtime)) {
                $tStart = strtotime($day->starttime);
                $tEnd = strtotime($day->endtime);
                $tNow = $tStart;
                while ($tNow <= $tEnd) {
                    $endHtml .=
                        '<li class="radiobox-image">
												<input type="radio" id="endid_time_two' .
                        strtolower($day->days) .
                        $tNow .
                        '" name="' .
                        strtolower($day->days) .
                        '_end_two" class="end_' .
                        strtolower($day->days) .
                        '" data-val="" value="' .
                        date('H:i:s', $tNow) .
                        '">
												<label for="endid_time_two' .
                        strtolower($day->days) .
                        $tNow .
                        '">
												' .
                        date('H:i', $tNow) .
                        '
											  </label>
											  </li>';
                    $tNow = strtotime('+60 minutes', $tNow);
                }
            }

            $html .=
                '<div class="d-flex" id="remove_' .
                strtolower($day->days) .
                '">
                              <div class="display-ib mr-20 ml-auto">
                                <div class="form-group form-group-mb-50">
									<div class="btn-group multi_sigle_select inp_select v_inp_new width130">
                                    <span class="label">' .
                $this->lang->line('Start_Time') .
                '</span>
                                    <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false"></button>     
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="max-height: none;height: auto !important;overflow-x: auto;">
										 ' .
                $startHtml .
                '                    
                                    </ul>
                                 </div>
                                  <span id="Serrtwo_' .
                strtolower($day->days) .
                '" class="error"></span>
                                </div>
                            </div>
                            <div class="display-ib">
                                <div class="form-group form-group-mb-50">
                                <div class="btn-group multi_sigle_select inp_select v_inp_new width130">
                                    <span class="label">' .
                $this->lang->line('End_Time') .
                '</span>
                                    <button data-toggle="dropdown" class="height56v btn btn-default dropdown-toggle mss_sl_btn" aria-expanded="false"></button>                                
                                    
                                    <ul class="dropdown-menu mss_sl_btn_dm scroll200 custom_scroll" style="max-height: none;height: auto !important;overflow-x: auto;">
                                      ' .
                $endHtml .
                '                      
                                    </ul>
                                 </div>
                                  <span id="Eerrtwo_' .
                strtolower($day->days) .
                '" class="error"></span>
                                </div>
                            </div>

                             <a href="javascript:void(0);" id="' .
                strtolower($day->days) .
                '" class="mt-3 display-b ml-2 remove_timeset" data-toggle="modal" data-target="">
                              <img src="' .
                base_url('assets/frontend/images/remove.svg') .
                '" class="width24v">
                            </a>
                          </div>';

            echo json_encode(['success' => 1, 'html' => $html]);
        } else {
            echo json_encode(['success' => 0, 'html' => $html]);
        }
    }

    //**** get all review list ***//
    public function rating_review()
    {
        $uid = $this->session->userdata('st_userid');
        /*print_r($_POST);
         die;*/
        $res = $this->user->update(
            'st_review',
            ['read_status' => 'read'],
            ['merchant_id' => $uid]
        );
        $rowperpage = 5;
        $offset = 0;
        $this->data['totalcount'] = $data = $this->user->select_row(
            'st_review',
            'count(id) as tcount,(SELECT count(rate) FROM st_review where rate=5 AND merchant_id=' .
                $uid .
                ') as five,(SELECT count(rate) FROM st_review where rate=4 AND merchant_id=' .
                $uid .
                ') as four,(SELECT count(rate) FROM st_review where rate=3 AND merchant_id=' .
                $uid .
                ') as three,(SELECT count(rate) FROM st_review where rate=2 AND merchant_id=' .
                $uid .
                ') as two,(SELECT count(rate) FROM st_review where rate=1 AND merchant_id=' .
                $uid .
                ') as one',
            ['merchant_id' => $uid, 'user_id !=' => 0]
        );
        /*if(!empty($totalcount->tcount))
		 	$this->data['total']=$totalcount->tcount;
		 else
		 	$this->data['total']=0;*/
        $sql =
            "SELECT st_review.id,st_review.user_id,st_review.booking_id,
		st_review.merchant_id,rate,review,anonymous,st_review.created_on,
		(SELECT AVG(rate) from st_review where merchant_id=" .
            $uid .
            " 
		AND user_id!='0') as average,first_name,last_name,
		(SELECT first_name from st_users where st_users.id=st_review.emp_id) 
		as fname,(SELECT last_name from st_users where st_users.id=st_review.emp_id) 
		as lname FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id 
		WHERE st_review.merchant_id=" .
            $uid .
            " AND st_review.user_id!='0'  
		GROUP BY st_review.id ORDER BY st_review.id DESC LIMIT " .
            $rowperpage .
            ' OFFSET ' .
            $offset .
            '';

        // $this->data['merchant_category']=$this->user->select('st_merchant_category','id,name,(SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as catname',array('created_by'=>$uid, 'status' => 'active'),'','id','ASC');
        //echo $this->db->last_query();die;

        //$this->data['merchant_category']=$this->user->select('st_merchant_category','id,name,(SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as catname',array('created_by'=>$uid, 'status' => 'active'),'','id','ASC');
        //echo '<pre>'; print_r($this->data['merchant_category']);die();
        //$sql = "SELECT `name`,st_merchant_category.category_id as id, (SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as catname FROM `st_merchant_category` WHERE `created_by` = $uid  AND `status` = 'active' GROUP BY catname ORDER BY `id` ASC";

        //$query = $this->db->query($sql);
        //$res = $query->result();
        //echo $sql;
        //echo '<pre>'; print_r($res);die();
        //$this->data['merchant_category'] = $res;

        //
        $sqlsqls = "SELECT `id`, `name`, (SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as catname FROM `st_merchant_category` WHERE `created_by` = $uid AND `status` = 'active' GROUP BY catname ORDER BY `id` ASC;";
        //     $queryquery = $this->db->query($sqlsqls);
        // 	$resress = $queryquery->result();
        $this->data['merchant_category'] = $this->user->custome_query(
            $sqlsqls,
            'result'
        );
        $this->data['allreviews'] = $this->user->custome_query($sql, 'result');

        $this->load->view('frontend/marchant/ratingandreview', $this->data);
    }

    //*** Apply Review filter ****//
    public function reviewfilter()
    {
        $uid = $this->session->userdata('st_userid');

        $where = '';
        if (!empty($_POST['rating_point'])) {
            $subcat = implode(',', $_POST['rating_point']);
            //print_r($subcat);
            $where = $where . ' AND rate IN(' . $subcat . ')';
        }
        if (!empty($_POST['category']) && !empty($_POST['category'][0])) {
            $subcat = implode(',', $_POST['category']);
            $whr = "service_id IN('" . $subcat . "')";
            // $whr="service_name IN('".$subcat."')";
            $msql =
                'SELECT booking_id FROM st_booking_detail WHERE ' . $whr . '';
            $book_id = $this->user->custome_query($msql, 'result');
            //print_r($book_id);die;
            if (!empty($book_id)) {
                $i = 0;
                foreach ($book_id as $ids) {
                    $book[$i] = $ids->booking_id;
                    $i++;
                }

                $book_id = implode(',', $book);
                $where = $where . 'AND booking_id IN(' . $book_id . ')';
            } else {
                $where = $where . 'AND booking_id IN(0)';
            }
        }

        if (!empty($_POST['serch_by_name_or_review'])) {
            if (strpos('anonym', strtolower($_POST['serch_by_name_or_review'])) !== false) {
                $where =
                    $where .
                    "AND (st_review.review LIKE '%" .
                    $_POST['serch_by_name_or_review'] .
                    "%' OR st_users.first_name LIKE '%" .
                    $_POST['serch_by_name_or_review'] .
                    "%' OR st_review.anonymous=1)";
            } else {
                $where =
                    $where .
                    "AND (st_review.review LIKE '%" .
                    $_POST['serch_by_name_or_review'] .
                    "%' OR st_users.first_name LIKE '%" .
                    $_POST['serch_by_name_or_review'] .
                    "%')";
            }
        }

        $rowperpage = 5;
        $offset = 0;

        $csql =
            'SELECT count(st_review.id) as tcount FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=' .
            $uid .
            ' ' .
            $where .
            " AND st_review.user_id!='0'";
        
        $totalcount = $this->user->custome_query($csql, 'row');

        // $this->data['totalcount'] = $data=$this->user->select_row('st_review','count(id) as tcount',array('merchant_id' => $uid, 'user_id !=' => 0));

        if (!empty($totalcount->tcount)) {
            $count = $totalcount->tcount;
        } else {
            $count = 0;
        }

        $html = '';
        $sql =
            'SELECT st_review.id,st_review.user_id,st_review.booking_id,st_review.merchant_id,rate,review,anonymous,st_review.created_on,first_name,last_name,(SELECT first_name from st_users where st_users.id=st_review.emp_id) as fname,(SELECT last_name from st_users where st_users.id=st_review.emp_id) as lname FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=' .
            $uid .
            ' ' .
            $where .
            " AND st_review.user_id!='0' GROUP BY st_review.id DESC LIMIT " .
            $rowperpage .
            ' OFFSET ' .
            $offset .
            '';
        // echo $sql;
        $allreviews = $this->user->custome_query($sql, 'result');

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
                    $namr =
                        '<span class="font-size-14 fontfamily-medium color333">Anonym</span>';
                } else {
                    $namr =
                        '<span class="font-size-14 fontfamily-medium color333 mb-10">' .
                        $rev->first_name .
                        '</span>';
                }

                $html .=
                    '<span class="color999 font-size-14 fontfamily-regular float-right">' .
                    time_passed($timestamp) .
                    '</span>
                      </div>
                      <p class="color666 fontfamily-regular font-size-14 text-lines-overflow-1" style="-webkit-line-clamp:2;">' .
                    $rev->review .
                    '</p>
                      <p>' .
                    $namr .
                    '<span class="font-size-14 color999 fontfamily-regular"> behandelt von ' .
                    $rev->fname .
                    '</span></p>
                      <div class="d-flex">';

                $html .=
                    ''.$this->lang->line('Services_Booked').' : ' .
                    get_servicename_with_sapce($rev->booking_id);
                $m_reply = getselect_row('st_review', 'id,review,merchant_id', [
                    'review_id' => $rev->id,
                    'created_by' => $rev->merchant_id,
                ]);
                $dis = 'none';
                if (!empty($m_reply)) {
                    $edit_btn = '';
                    $reply_btn = 'none';
                    $dis = '';
                    $myreview = $m_reply->review;
                } else {
                    $edit_btn = 'none';
                    $reply_btn = '';
                    $myreview = '';
                }

                $html .=
                    '<div id="show_edit_' .
                    $rev->id .
                    '" class="ml-auto" style="display:' .
                    $edit_btn .
                    '"><a href="javascript:void(0);" class="font-size-14 fontfamily-regular colororange mb-10 text-underline a_hover_orange ml-auto reply_click" id="' .
                    $rev->id .
                    '">'.$this->lang->line('Edit').'</a></div>
                         <div id="show_reply_' .
                    $rev->id .
                    '" class="ml-auto" style="display:' .
                    $reply_btn .
                    '"><a href="javascript:void(0);" class="font-size-14 fontfamily-regular colororange mb-10 text-underline a_hover_orange ml-auto reply_click" id="' .
                    $rev->id .
                    '">Antworten</a></div>
                      </div>';

                $html .=
                    '<div class="accordion pb-2" id="show_reply_div' .
                    $rev->id .
                    '" style="display: ' .
                    $dis .
                    '">
                        <div class="accordion-group">
                          <div class="accordion-heading p-3 relative">
                           <a class="color333 fontfamily-medium font-size-14 a_hover_333 display-ib relative">
						   Kommentar von ' .
                    $this->session->userdata('business_name') .
                    '
                            </a>
                          </div>
                          <div id="collapsev_' .
                    $rev->id .
                    '" class="">
                            <div class="accordion-inner" id="append_row_' .
                    $rev->id .
                    '">';
                if (!empty($m_reply)) {
                    $html .=
                        '<div class="d-flex py-2 px-3">
                                  <p class="fontfamily-regular color666 font-size-14 mb-0">' .
                        $m_reply->review .
                        '</p>
                                   <a href="javascript:void(0);" class="absolute delete_reply" style="right:1rem;" id="' .
                        $m_reply->id .
                        '" data-id="' .
                        $rev->id .
                        '">
                                    <img src="' .
                        base_url(
                            'assets/frontend/images/delete-color-icon.svg'
                        ) .
                        '">
                                  </a>
                              </div>';
                }
                $html .= '</div>
                          </div>
                        </div>
                      </div>';
                $html .=
                    '<div class="massage-box-v_' .
                    $rev->id .
                    '" style="display: none;">
                        <p class="font-size-16 color333 fontfamily-medium mt-10 mb-2">' .
                    $this->lang->line('Your_Reply') .
                    '</p>
                        <div class="form-group">
                          <textarea class="form-control scroll-effect height90v" placeholder="' .
                    $this->lang->line('Enter_here') .
                    '" id="mer_reply_' .
                    $rev->id .
                    '" style="min-height: 90px;max-height:90px;">' .
                    $myreview .
                    '</textarea>
                        </div>
                        <label for="identity" id="err_' .
                    $rev->id .
                    '" generated="true" class="error"></label>
                        <div class="text-right">
                          <button id="' .
                    $rev->id .
                    '" class="btn widthfit height44v width150v merchant_reply">' .
                    $this->lang->line('Submit') .
                    '</button>
                        </div>
                      </div>
                    </div>
                  </div>';
            }

            if ($count > 5) {
                $html .=
                    '<div id="load-1" class="text-center"><a href="javascript:void(0)" data-id="1"  class="colorcyan a_hover_cyan text-underline font-size-18 fontfamily-medium mt-4 mb-3 display-ib loadMoreReview">weitere Bewertungen laden</a></div>';
            }

            echo json_encode([
                'success' => 1,
                'html' => $html,
                'count' => $count,
            ]);
        } else {
            $html =
                '<div class="text-center" style="padding-bottom: 45px;">
					  <img src="' .
                base_url('assets/frontend/images/no_listing.png') .
                '"><p style="margin-top: 20px;"> Bisher wurden keine Bewertungen abgegeben.</p></div>';
            echo json_encode(['success' => 0, 'html' => $html]);
        }
    }

    //**** load review filter ****//
    public function loadreviewfilter()
    {
        $uid = $this->session->userdata('st_userid');

        $where = '';
        if (!empty($_POST['rating_point'])) {
            $subcat = implode(',', $_POST['rating_point']);
            $where = $where . ' AND rate IN(' . $subcat . ')';
        }
        if (!empty($_POST['category']) && !empty($_POST['category'][0])) {
            $subcat = implode(',', $_POST['category']);
            //$whr="service_id IN(".$subcat.")";
            $whr = "service_name IN('" . $subcat . "')";
            $msql =
                'SELECT booking_id FROM st_booking_detail WHERE ' . $whr . '';
            $book_id = $this->user->custome_query($msql, 'result');
            if (!empty($book_id)) {
                //$book_id=implode(',',$book_id);
                $i = 0;
                foreach ($book_id as $ids) {
                    $book[$i] = $ids->booking_id;
                    $i++;
                }

                $book_id = implode(',', $book);
                $where = $where . 'AND booking_id IN(' . $book_id . ')';
            }
        }

        $limit = $_POST['load_more'] + 1;
        $allCount = $limit * 5;
        $rowperpage = 5;
        $offset = 0;
        if ($limit != 1) {
            $offset = ($limit - 1) * $rowperpage;
        }

        $csql =
            'SELECT count(st_review.id) as tcount FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=' .
            $uid .
            ' ' .
            $where .
            " AND st_review.user_id!='0'";
        $totalcount = $this->user->custome_query($csql, 'row');

        // $this->data['totalcount'] = $data=$this->user->select_row('st_review','count(id) as tcount',array('merchant_id' => $uid, 'user_id !=' => 0));

        if (!empty($totalcount->tcount)) {
            $count = $totalcount->tcount;
        } else {
            $count = 0;
        }

        $html = '';
        $sql =
            'SELECT st_review.id,st_review.user_id,st_review.booking_id,st_review.merchant_id,rate,review,anonymous,st_review.created_on,first_name,last_name,(SELECT first_name from st_users where st_users.id=st_review.emp_id) as fname,(SELECT last_name from st_users where st_users.id=st_review.emp_id) as lname FROM st_review LEFT JOIN st_users ON st_review.user_id=st_users.id WHERE st_review.merchant_id=' .
            $uid .
            ' ' .
            $where .
            " AND st_review.user_id!='0' GROUP BY st_review.id DESC LIMIT " .
            $rowperpage .
            ' OFFSET ' .
            $offset .
            '';
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
                    $namr =
                        '<span class="font-size-14 fontfamily-medium color333">Anonym</span>';
                } else {
                    $namr =
                        '<span class="font-size-14 fontfamily-medium color333 mb-10">' .
                        $rev->first_name .
                        '</span>';
                }

                $html .=
                    '<span class="color999 font-size-14 fontfamily-regular float-right">' .
                    time_passed($timestamp) .
                    '</span>
                      </div>
                      <p class="color666 fontfamily-regular font-size-14 text-lines-overflow-1" style="-webkit-line-clamp:2;">' .
                    $rev->review .
                    '</p>
                       <p>' .
                    $namr .
                    ' <span class="font-size-14 color999 fontfamily-regular"> behandelt von ' .
                    $rev->fname .
                    '</span></p>
                      <div class="d-flex">';

                $html .=
                    ' '.$this->lang->line('Services_Booked').' : ' .
                    get_servicename_with_sapce($rev->booking_id);
                //$m_reply=getselect('st_review','id,review',array('review_id' =>$rev->id,'created_by' => $rev->merchant_id));
                $m_reply = getselect_row('st_review', 'id,review,merchant_id', [
                    'review_id' => $rev->id,
                    'created_by' => $rev->merchant_id,
                ]);
                $dis = 'none';
                if (!empty($m_reply)) {
                    $edit_btn = '';
                    $reply_btn = 'none';
                    $dis = '';
                    $myreview = $m_reply->review;
                } else {
                    $edit_btn = 'none';
                    $reply_btn = '';
                    $myreview = '';
                }

                $html .=
                    '
                        <div id="show_edit_' .
                    $rev->id .
                    '" class="ml-auto" style="display:' .
                    $edit_btn .
                    '"><a href="javascript:void(0);" class="font-size-14 fontfamily-regular colororange mb-10 text-underline a_hover_orange ml-auto reply_click" id="' .
                    $rev->id .
                    '">'.$this->lang->line('Edit').'</a></div>
                         <div id="show_reply_' .
                    $rev->id .
                    '" class="ml-auto" style="display:' .
                    $reply_btn .
                    '"><a href="javascript:void(0);" class="font-size-14 fontfamily-regular colororange mb-10 text-underline a_hover_orange ml-auto reply_click" id="' .
                    $rev->id .
                    '">Antworten</a></div>
                      </div>';

                $html .=
                    '<div class="accordion pb-2" id="show_reply_div' .
                    $rev->id .
                    '" style="display: ' .
                    $dis .
                    '">
                        <div class="accordion-group">
                          <div class="accordion-heading p-3 relative">
                           <a class="color333 fontfamily-medium font-size-14 a_hover_333 display-ib relative">
						   Kommentar von ' .
                    $this->session->userdata('business_name') .
                    '
                            </a>
                          </div>
                          <div id="collapsev_' .
                    $rev->id .
                    '" class="">
                            <div class="accordion-inner" id="append_row_' .
                    $rev->id .
                    '">';
                if (!empty($m_reply)) {
                    $html .=
                        '<div class="d-flex py-2 px-3">
                                  <p class="fontfamily-regular color666 font-size-14 mb-0">' .
                        $m_reply->review .
                        '</p>
                                   <a href="javascript:void(0);" class="absolute delete_reply" style="right:1rem;" id="' .
                        $m_reply->id .
                        '" data-id="' .
                        $rev->id .
                        '">
                                    <img src="' .
                        base_url(
                            'assets/frontend/images/delete-color-icon.svg'
                        ) .
                        '">
                                  </a>
                              </div>';
                }
                $html .= '</div>
                          </div>
                        </div>
                      </div>';

                $html .=
                    '<div class="massage-box-v_' .
                    $rev->id .
                    '" style="display: none;">
                        <p class="font-size-16 color333 fontfamily-medium mt-10 mb-2">' .
                    $this->lang->line('Your_Reply') .
                    '</p>
                        <div class="form-group">
                          <textarea class="form-control scroll-effect height90v" placeholder="' .
                    $this->lang->line('Enter_here') .
                    '" id="mer_reply_' .
                    $rev->id .
                    '" style="min-height: 90px;max-height:90px;">' .
                    $myreview .
                    '</textarea>
                        </div>
                        <label for="identity" id="err_' .
                    $rev->id .
                    '" generated="true" class="error"></label>
                        <div class="text-right">
                          <button id="' .
                    $rev->id .
                    '" class="btn widthfit height44v width150v merchant_reply">' .
                    $this->lang->line('Submit') .
                    '</button>
                        </div>
                      </div>
                    </div>
                  </div>';
            }

            if ($count > $allCount) {
                $html .=
                    '<div id="load-' .
                    $limit .
                    '" class="text-center"><a href="javascript:void(0)" data-id="' .
                    $limit .
                    '"  class="colorcyan a_hover_cyan text-underline font-size-18 fontfamily-medium mt-4 mb-3 display-ib loadMoreReview">' .
                    $this->lang->line('Load_More') .
                    '</a></div>';
            }
            echo json_encode([
                'success' => 1,
                'html' => $html,
                'limit' => $limit,
                'count' => $allCount,
            ]);
        } else {
            echo json_encode(['success' => 0, 'html' => $html]);
        }
    }

    //**** reply on review ****//
    public function replyonreview()
    {
        $mid = $this->session->userdata('st_userid');
        $insertArr['review_id'] = $_POST['reviewid'];
        $insertArr['review'] = $_POST['reply'];
        $insertArr['merchant_id'] = $mid;
        $insertArr['created_by'] = $mid;
        $insertArr['created_on'] = date('Y-m-d H:i:s');
        if (
            $this->user->countResult('st_review', [
                'review_id' => $_POST['reviewid'],
                'merchant_id' => $mid,
            ])
        ) {
            $upd_data['review'] = $_POST['reply'];
            $upd_data['updated_on'] = date('Y-m-d H:i:s');

            $result = $this->user->update('st_review', $upd_data, [
                'review_id' => $_POST['reviewid'],
                'merchant_id' => $mid,
            ]);
        } else {
            $result = $this->user->insert('st_review', $insertArr);
        }

        if ($result) {
            $html =
                '<div class="d-flex py-2 px-3" id="remove_reply_' .
                $result .
                '">
                                  <p class="fontfamily-regular color666 font-size-14 mb-0">' .
                $_POST['reply'] .
                '</p>
                                   <a href="javascript:void(0);" class="absolute delete_reply" style="right:1rem;" id="' .
                $result .
                '" data-id="' .
                $_POST['reviewid'] .
                '">
                                    <img src="' .
                base_url('assets/frontend/images/delete-color-icon.svg') .
                '">
                                  </a>
                              </div>';
            echo json_encode(['success' => 1, 'html' => $html]);
        } else {
            echo json_encode(['success' => 0, 'html' => $html]);
        }
    }

    //***** Delete review reply *****//
    public function replydelete()
    {
        $id = $_POST['replyid'];
        $rid = $_POST['review'];
        if (
            $this->db->delete('st_review', ['id' => $id, 'review_id' => $rid])
        ) {
            $count = $this->user->countResult('st_review', [
                'review_id' => $rid,
            ]);
            //$count=get_employeecount('st_review',array('review_id' => $rid));
            echo json_encode(['success' => 1, 'count' => $count]);
        } else {
            echo json_encode(['success' => 0, 'count' => 0]);
        }
    }

    //**** Report view page ****//
    public function reports()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }
        $this->load->view('frontend/marchant/reports');
    }

    //**** Report Sale BY service List ****//
    public function saleby_service_list($page = '0')
    {
        /*if(!empty($id))
         {*/
        //$cid=url_decode($id);
        $mid = $this->session->userdata('st_userid');
        $search = '';
        //$where=array('st_booking.merchant_id'=>$mid);
        $where = '';
        $rowperpage = 5;
        $offset = 0;
        $whr = '';
        if (!empty($_POST['filter'])) {
            if ($_POST['filter'] == 'day') {
                $end_date = date('Y-m-d 23:59:59');
                $start_date = date('Y-m-d 00:00:00');
            } elseif ($_POST['filter'] == 'current_week') {
                $monday = strtotime('last monday');
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime('+1 week', $monday)
                );
                $start_date = date('Y-m-d 00:00:00', $monday);
            } elseif ($_POST['filter'] == 'current_month') {
                $end_date = date('Y-m-t 23:59:59');
                $start_date = date('Y-m-01 00:00:00');
            } elseif ($_POST['filter'] == 'current_year') {
                $end_date = date('Y-12-t 23:59:59');
                $start_date = date('Y-01-01 00:00:00');
            } elseif ($_POST['filter'] == 'last_month') {
                $end_date = date('Y-m-t 23:59:59', strtotime('last month'));
                $start_date = date('Y-m-01 00:00:00', strtotime('last month'));
            } elseif ($_POST['filter'] == 'last_year') {
                $end_date = date('Y-12-31 23:59:59', strtotime('last year'));
                $start_date = date('Y-01-01 00:00:00', strtotime('last year'));
            } elseif (
                $_POST['filter'] == 'date' &&
                $_POST['startdate'] != '' &&
                $_POST['enddate'] != ''
            ) {
                $start_date = date(
                    'Y-m-d 00:00:00',
                    strtotime($_POST['startdate'])
                );
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime($_POST['enddate'])
                );
            } elseif ($_POST['filter'] == '30') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == '90') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-90 days'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'cwtd') {
                $start_date = date('Y-m-d 00:00:00', strtotime('last monday'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'cqtd') {
                $current_month = date('m');
                $current_year = date('Y');

                if ($current_month >= 1 && $current_month <= 3) {
                    $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-January-' . $current_year)
                    ); // timestamp or 1-Januray 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-April 12:00:00 AM means end of 31 March
                } elseif ($current_month >= 4 && $current_month <= 6) {
                    $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-April-' . $current_year)
                    ); // timestamp or 1-April 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-July 12:00:00 AM means end of 30 June
                } elseif ($current_month >= 7 && $current_month <= 9) {
                    $start_date = $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-July-' . $current_year)
                    ); // timestamp or 1-July 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-October 12:00:00 AM means end of 30 September
                } elseif ($current_month >= 10 && $current_month <= 12) {
                    $start_date = $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-October-' . $current_year)
                    ); // timestamp or 1-October 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
                }
            } elseif ($_POST['filter'] == 'cmtd') {
                $start_date = date('Y-m-01 00:00:00');
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'cytd') {
                $start_date = date('Y-01-01 00:00:00');
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'yesterday') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $end_date = date('Y-m-d 23:59:00', strtotime('-1 days'));
            }
            if ($_POST['filter'] != 'all') {
                $whr =
                    "AND st_booking.updated_on >= '" .
                    $start_date .
                    "' AND st_booking.updated_on <= '" .
                    $end_date .
                    "'";
            }
        }
        if (!empty($_POST['order'])) {
            if ($_POST['order'] == 'name asc') {
                $order = 'cat_name asc filtercat_id asc category_id asc subcategory_id asc';    
            } else if ($_POST['order'] == 'name desc') {
                $order = 'cat_name desc filtercat_id desc category_id desc subcategory_id desc';    
            } else {
                $order = $_POST['order'];
            }
        } else {
            $order = 'cat_name asc filtercat_id asc category_id asc subcategory_id asc';
        }
        $pagination = '';

        $all_service = $this->user->select_custome_orderBy(
            'st_merchant_category',
            'id,name,filtercat_id,(SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as cat_name,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking.status="confirmed" AND st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ') as confirm,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking.status="completed" AND st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ' ) as complete,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE (st_booking.status="cancelled" OR st_booking.status ="no show") AND st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ') as cancel,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ') as total,IFNULL((SELECT SUM(`st_booking_detail`.`price`) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking_detail.service_id=st_merchant_category.id AND st_booking.status="completed" ' .
                $whr .
                '),0) as revenue',
            ['created_by' => $mid, 'status' => 'active'],
            '',
            $order
        );
        $all_count_service = $this->user->select_custome_orderBy(
            'st_merchant_category',
            'id,name,filtercat_id,(SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as cat_name,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking.status="confirmed" AND st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ') as confirm,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking.status="completed" AND st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ' ) as complete,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE (st_booking.status="cancelled" OR st_booking.status ="no show") AND st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ') as cancel,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ') as total,IFNULL((SELECT SUM(`st_booking_detail`.`price`) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking_detail.service_id=st_merchant_category.id AND st_booking.status="completed" ' .
                $whr .
                '),0) as revenue',
            ['created_by' => $mid, 'status' => 'active'],
            '',
            $order
        );
        $sumData = [];

        if (!empty($all_count_service)) {
            $rec_totall = count($all_count_service);
            $ijj = 0;

            $comp = $canc = $totval = $revs = 0;
            foreach ($all_count_service as $row1) {
                $total_s = $row1->complete + $row1->confirm + $row1->cancel;
                $comp = $comp + $row1->complete;
                $canc = $canc + $row1->cancel;
                $totval = $totval + $total_s;
                $revs = $revs + $row1->revenue;
                $ijj++;
                if ($rec_totall == $ijj) {
                    array_push(
                        $sumData,
                        $total_s,
                        $comp,
                        $canc,
                        $totval,
                        $revs
                    );
                }
            }
        }
        //print_r($sumData); echo '</pre>';
        //echo $this->db->last_query(); die;
        $html = '';
        if (!empty($all_service)) {
            $rec_total = count($all_service);
            $ij = 0;
            $comp = $canc = $totval = $revs = 0;
            foreach ($all_service as $row) {
                if ($row->name != '') {
                    $s_name = $row->cat_name . ' - ' . $row->name;
                } else {
                    $s_name = $row->cat_name;
                }

                $total_s = $row->complete + $row->confirm + $row->cancel;
                $comp = $comp + $row->complete;
                $canc = $canc + $row->cancel;
                $totval = $totval + $total_s;
                $revs = $revs + $row->revenue;

                $html =
                    $html .
                    '<tr><td class="font-size-14 height56v color666 fontfamily-regular"><p class="mb-0 overflow_elips">' .
                    $s_name .
                    '</p></td>
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center"><p class="mb-0">' .
                    $row->complete .
                    '</p></td>
                    
                       <td class="text-center font-size-14 height56v color666 fontfamily-regular text-center">' .
                    $row->cancel .
                    '</td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                    $total_s .
                    '</p>
                      </td><td class="text-center font-size-14 height56v color666 fontfamily-regular text-center">' .
                    price_formate($row->revenue) .
                    ' €</td></tr>';

                $ij++;
                if ($rec_total == $ij) {
                    $html =
                        $html .
                        '<tr><td class="font-size-14 height56v color666 fontfamily-regular"><p class="color333 mb-0 overflow_elips" style="width:175px;"><b> ' .
                        $this->lang->line('Total') .
                        ' </b></p></td>
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center"><p class="mb-0">' .
                        $sumData[1] .
                        '</p></td>
                    
                       <td class="text-center font-size-14 height56v color666 fontfamily-regular text-center">' .
                        $sumData[2] .
                        '</td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                        $sumData[3] .
                        '</p>
                      </td><td class="text-center font-size-14 height56v color666 fontfamily-regular text-center">' .
                      price_formate($sumData[4]) .
                        ' €</td></tr>';
                }
            }
        } else {
            $html =
                '<tr><td colspan="7" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="' .
                base_url('assets/frontend/images/no_listing.png') .
                '"><p style="margin-top: 20px;">' . $this->lang->line('dont_any_reports') . '</p></div></td></tr>';
        }

        echo json_encode([
            'success' => '1',
            'msg' => '',
            'html' => $html,
            'pagination' => $pagination,
        ]);
        die();
    }

    //**** Report Sale BY Staff List ****//
    public function saleby_staff_list($page = '0')
    {
        $mid = $this->session->userdata('st_userid');
        $search = '';
        $whr = '';
        $tipWher = '';
        if (!empty($_POST['filter'])) {
            if ($_POST['filter'] == 'day') {
                $end_date = date('Y-m-d 23:59:59');
                $start_date = date('Y-m-d 00:00:00');
            } elseif ($_POST['filter'] == 'current_week') {
                $monday = strtotime('last monday');
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime('+1 week', $monday)
                );
                $start_date = date('Y-m-d 00:00:00', $monday);
            } elseif ($_POST['filter'] == 'current_month') {
                $end_date = date('Y-m-t 23:59:59');
                $start_date = date('Y-m-01 00:00:00');
            } elseif ($_POST['filter'] == 'current_year') {
                $end_date = date('Y-12-t 23:59:59');
                $start_date = date('Y-01-01 00:00:00');
            } elseif ($_POST['filter'] == 'last_month') {
                $end_date = date('Y-m-t 23:59:59', strtotime('last month'));
                $start_date = date('Y-m-01 00:00:00', strtotime('last month'));
            } elseif ($_POST['filter'] == 'last_year') {
                $end_date = date('Y-12-31 23:59:59', strtotime('last year'));
                $start_date = date('Y-01-01 00:00:00', strtotime('last year'));
            } elseif (
                $_POST['filter'] == 'date' &&
                $_POST['startdate'] != '' &&
                $_POST['enddate'] != ''
            ) {
                $start_date = date(
                    'Y-m-d 00:00:00',
                    strtotime($_POST['startdate'])
                );
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime($_POST['enddate'])
                );
            } elseif ($_POST['filter'] == '30') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == '90') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-90 days'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'cwtd') {
                $start_date = date('Y-m-d 00:00:00', strtotime('last monday'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'cqtd') {
                $current_month = date('m');
                $current_year = date('Y');

                if ($current_month >= 1 && $current_month <= 3) {
                    $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-January-' . $current_year)
                    ); // timestamp or 1-Januray 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-April 12:00:00 AM means end of 31 March
                } elseif ($current_month >= 4 && $current_month <= 6) {
                    $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-April-' . $current_year)
                    ); // timestamp or 1-April 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-July 12:00:00 AM means end of 30 June
                } elseif ($current_month >= 7 && $current_month <= 9) {
                    $start_date = $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-July-' . $current_year)
                    ); // timestamp or 1-July 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-October 12:00:00 AM means end of 30 September
                } elseif ($current_month >= 10 && $current_month <= 12) {
                    $start_date = $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-October-' . $current_year)
                    ); // timestamp or 1-October 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
                }
            } elseif ($_POST['filter'] == 'cmtd') {
                $start_date = date('Y-m-01 00:00:00');
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'cytd') {
                $start_date = date('Y-01-01 00:00:00');
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'yesterday') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $end_date = date('Y-m-d 23:59:00', strtotime('-1 days'));
            }

            if ($_POST['filter'] != 'all') {
                $whr =
                    "date(st_booking.updated_on) >= '" .
                    $start_date .
                    "' AND date(st_booking.updated_on) <= '" .
                    $end_date .
                    "' AND booking_type!='self'";
                $tipWher =
                    "AND date(created_on) >= '" .
                    $start_date .
                    "' AND date(created_on) <= '" .
                    $end_date .
                    "'";
            }
            //booking_time
        }

        if ($_POST['status'] == 'active') {
            $wh = ['merchant_id' => $mid, 'status !=' => 'deleted'];
            $where = [
                'st_users.merchant_id' => $mid,
                'st_users.status !=' => 'deleted',
            ];
        } else {
            $wh = ['merchant_id' => $mid];
            $where = ['st_users.merchant_id' => $mid];
        }

        $pagination = '';

        $field =
            'st_users.id,first_name,last_name,profile_pic,st_users.status,IFNULL((SELECT SUM(tip) FROM st_invoices WHERE emp_id=st_users.id ' .
            $tipWher .
            '),0) as totalTip';
        $where['st_users.status'] = 'active';
        $customer = $this->user->join_two_orderby_report(
            'st_users',
            'st_booking',
            'id',
            'employee_id',
            $where,
            $field,
            '',
            'st_booking.employee_id'
        );

        $bookings = getBookings($mid, $whr);

        $html = '';
        if (!empty($customer)) {
            $comp = $conf = $canc = $tots = $revs = $tips = $comss = 0;
            $ij = 0;
            $counts = count($customer);
            foreach ($customer as $row) {

                $rcomp = 0;
                $rconfirm = 0;
                $rcancel = 0;
                $rtotal = 0;
                $rrevenue = 0;
                $rcomission = 0;

                if ($bookings) {
                    foreach ($bookings as $booking) {
                        if ($booking->employee_id == $row->id) {

                            if ($booking->status == 'completed') {
                                $rcomp++;
                                $rrevenue += $booking->total_price;
                                $rcomission += $booking->emp_commission;
                            }
                            if ($booking->status == 'confirmed' && $booking->booking_type != 'self') {
                                $rconfirm++;
                            }
                            if ($booking->status == "cancelled" || $booking->status == "no show") {
                                $rcancel++;
                            }
    
                            $rtotal++;
                        }
                    }
                }

                $row->complete = $rcomp;
                $row->confirm = $rconfirm;
                $row->cancel = $rcancel;
                $row->total = (int) $rcomp + (int) $rconfirm + (int) $rcancel;;
                $row->revenue = $rrevenue;
                $row->comission = $rcomission;
            }
            if (isset($_POST['order']) && !empty($_POST['order'])) {
                $ors = explode(' ', $_POST['order']);
                $sortingcol = array_column($customer, $ors[0]);
                array_multisort($sortingcol, $ors[1] == 'asc' ? SORT_ASC : SORT_DESC, $customer);
            }
            foreach ($customer as $row) {

                $rcomp = $row->complete;
                $rconfirm = $row->confirm;
                $rcancel = $row->cancel;
                $rtotal = $row->total;
                $rrevenue = $row->revenue;
                $rcomission = $row->comission;

                if ($row->status == 'deleted') {
                    $cls = 'colorred';
                } else {
                    $cls = 'color666';
                }
                if ($row->profile_pic != '') {
                    $usimg = getimge_url(
                        'assets/uploads/employee/' . $row->id . '/',
                        'icon_' . $row->profile_pic,
                        'png'
                    );
                    $usimgw = getimge_url(
                        'assets/uploads/employee/' . $row->id . '/',
                        'icon_' . $row->profile_pic,
                        'webp'
                    );
                } else {
                    $usimg = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                    $usimgw = base_url(
                        'assets/frontend/images/user-icon-gret.webp'
                    );
                }

                if ($rrevenue != 0) {
                    $bk_total = $rrevenue;
                }
                //-$row->totalTip;
                else {
                    $bk_total = 0;
                }

                $total =
                    (int) $rcomp +
                    (int) $rconfirm +
                    (int) $rcancel;
                $comp = $comp + $rcomp;
                $conf = $conf + $rconfirm;
                $canc = $canc + $rcancel;
                $tots = $tots + $total;
                $revs = $revs + $rrevenue;
                $tips = $tips + $row->totalTip;
                $comss = $comss + round($rcomission, 2);
                //     <source srcset="'.$usimgw.'" type="image/webp">
                // 					    <source srcset="'.$usimgw.'" type="image/webp">
                $html =
                    $html .
                    '<tr><td class="font-size-14 height56v color666 fontfamily-regular"><a class="editEmp" href="javascript:void(0)" data-id="' .
                    url_encode($row->id) .
                    '">
					  
					  <picture>
						
					 <img id="empimg_' .
                    $row->id .
                    '" src="' .
                    $usimg .
                    '" class="mr-3 width30 border-radius50">
					</picture>
                        <p class="overflow_elips mb-0 display-ib ' .
                    $cls .
                    '" id="empnm_' .
                    $row->id .
                    '">' .
                    $row->first_name .
                    ' ' .
                    $row->last_name .
                    '</p></a>
                       
                      </td>                      
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center"><p class="mb-0">' .
                    $rcomp .
                    '</p></td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular text-center">' .
                    $rconfirm .
                    '</td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                    $rcancel .
                    '</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                    $total .
                    '</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                    price_formate($bk_total) .
                    ' €</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                    price_formate($row->totalTip) .
                    ' €</p>
                      </td><td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                    price_formate(round($rcomission, 2)) .
                    ' €</p>
                      </td>
                      </tr>';
                $ij++;
                if ($counts == $ij) {
                    $html =
                        $html .
                        '<tr><td class="font-size-14 height56v color666 fontfamily-regular">
                        <p class="overflow_elips mb-0 display-ib"><b>' .
                        $this->lang->line('Total') .
                        '</b></p>
                      </td>                      
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center"><p class="mb-0">' .
                        $comp .
                        '</p></td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular text-center">' .
                        $conf .
                        '</td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                        $canc .
                        '</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                        $tots .
                        '</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                        price_formate($revs) .
                        ' €</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                        price_formate($tips) .
                        ' €</p>
                      </td><td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                        price_formate($comss) .
                        ' €</p>
                      </td>
                      </tr>';
                }
            }
        } else {
            $html =
                '<tr>
                    <td colspan="7" style="text-align:center;">
                    <div class="text-center pb-20 pt-50">
                        <img src="' . base_url('assets/frontend/images/no_listing.png') . '">
                        <p style="margin-top: 20px;">' . $this->lang->line('dont_any_reports') . '</p>
                    </div>
                    </td>
                </tr>';
        }

        echo json_encode([
            'success' => '1',
            'msg' => '',
            'html' => $html,
            'pagination' => $pagination,
        ]);
        die();
    }

    //**** Report sale By client List *****//
    public function saleby_client_list($page = '0')
    {
        $mid = $this->session->userdata('st_userid');
        $search = '';
        $whr = '';
        if (!empty($_POST['filter'])) {
            if ($_POST['filter'] == 'day') {
                $end_date = date('Y-m-d 23:59:59');
                $start_date = date('Y-m-d 00:00:00');
            } elseif ($_POST['filter'] == 'current_week') {
                $monday = strtotime('last monday');
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime('+1 week', $monday)
                );
                $start_date = date('Y-m-d 00:00:00', $monday);
            } elseif ($_POST['filter'] == 'current_month') {
                $end_date = date('Y-m-t 23:59:59');
                $start_date = date('Y-m-01 00:00:00');
            } elseif ($_POST['filter'] == 'current_year') {
                $end_date = date('Y-12-t 23:59:59');
                $start_date = date('Y-01-01 00:00:00');
            } elseif ($_POST['filter'] == 'last_month') {
                $end_date = date('Y-m-t 23:59:59', strtotime('last month'));
                $start_date = date('Y-m-01 00:00:00', strtotime('last month'));
            } elseif ($_POST['filter'] == 'last_year') {
                $end_date = date('Y-12-31 23:59:59', strtotime('last year'));
                $start_date = date('Y-01-01 00:00:00', strtotime('last year'));
            } elseif (
                $_POST['filter'] == 'date' &&
                $_POST['startdate'] != '' &&
                $_POST['enddate'] != ''
            ) {
                $start_date = date(
                    'Y-m-d 00:00:00',
                    strtotime($_POST['startdate'])
                );
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime($_POST['enddate'])
                );
            } elseif ($_POST['filter'] == '30') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == '90') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-90 days'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'cwtd') {
                $start_date = date('Y-m-d 00:00:00', strtotime('last monday'));
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'cqtd') {
                $current_month = date('m');
                $current_year = date('Y');

                if ($current_month >= 1 && $current_month <= 3) {
                    $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-January-' . $current_year)
                    ); // timestamp or 1-Januray 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-April 12:00:00 AM means end of 31 March
                } elseif ($current_month >= 4 && $current_month <= 6) {
                    $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-April-' . $current_year)
                    ); // timestamp or 1-April 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-July 12:00:00 AM means end of 30 June
                } elseif ($current_month >= 7 && $current_month <= 9) {
                    $start_date = $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-July-' . $current_year)
                    ); // timestamp or 1-July 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-October 12:00:00 AM means end of 30 September
                } elseif ($current_month >= 10 && $current_month <= 12) {
                    $start_date = $start_date = date(
                        'Y-m-d 00:00:00',
                        strtotime('1-October-' . $current_year)
                    ); // timestamp or 1-October 12:00:00 AM
                    $end_date = date('Y-m-d 23:59:00'); // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
                }
            } elseif ($_POST['filter'] == 'cmtd') {
                $start_date = date('Y-m-01 00:00:00');
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'cytd') {
                $start_date = date('Y-01-01 00:00:00');
                $end_date = date('Y-m-d 23:59:00');
            } elseif ($_POST['filter'] == 'yesterday') {
                $start_date = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $end_date = date('Y-m-d 23:59:00', strtotime('-1 days'));
            }

            if ($_POST['filter'] != 'all') {
                $whr =
                    "date(st_booking.updated_on) >= '" .
                    $start_date .
                    "' AND date(st_booking.updated_on) <= '" .
                    $end_date .
                    "'";
            }
            //booking_time
        }
        if (!empty($_POST['search'])) {
            $search = $_POST['search'];
        }
        $where = ['st_booking.merchant_id' => $mid];

        $pagination = '';

        $field =
            'st_users.id,first_name,last_name,profile_pic,st_users.email';

        $where['st_users.status'] = 'active';
        $customer = $this->user->join_two_orderby_report(
            'st_users',
            'st_booking',
            'id',
            'user_id',
            $where,
            $field,
            '',
            'st_booking.user_id',
            0,
            0,
            $search
        );
        //join_two_orderby
        //'st_users.id'
        //echo '<pre>'; print_r($booking); die;

        $bookings = getBookings($mid, $whr);

        $html = '';
        if (!empty($customer)) {
            $comp = $conf = $canc = $tots = $revs = 0;
            $ij = 0;
            $counts = count($customer);
            foreach ($customer as $row) {

                $rcomp = 0;
                $rconfirm = 0;
                $rcancel = 0;
                $rtotal = 0;
                $rrevenue = 0;
                $rcomission = 0;

                if ($bookings) {
                    foreach ($bookings as $booking) {
                        if (intval($booking->user_id) == intval($row->id)) {
                            if ($booking->employee_id != -1 && $booking->status == 'completed') {
                                $rcomp++;
                                $rrevenue += $booking->total_price;
                                $rcomission += $booking->emp_commission;
                            }
                            if ($booking->employee_id != -1 && $booking->status == 'confirmed' && $booking->booking_type != 'self') {
                                $rconfirm++;
                            }
                            if ($booking->employee_id != -1 && ($booking->status == "cancelled" || $booking->status == "no show")) {
                                $rcancel++;
                            }

                            $rtotal++;
                        }
                    }
                }
                $row->complete = $rcomp;
                $row->confirm = $rconfirm;
                $row->cancel = $rcancel;
                $row->total = (int) $rcomp + (int) $rconfirm + (int) $rcancel;;
                $row->revenue = $rrevenue;
                $row->comission = $rcomission;
            }
            if (isset($_POST['order']) && !empty($_POST['order'])) {
                $ors = explode(' ', $_POST['order']);
                $sortingcol = array_column($customer, $ors[0]);
                array_multisort($sortingcol, $ors[1] == 'asc' ? SORT_ASC : SORT_DESC, $customer);
            }
            foreach ($customer as $row) {

                $rcomp = $row->complete;
                $rconfirm = $row->confirm;
                $rcancel = $row->cancel;
                $rtotal = $row->total;
                $rrevenue = $row->revenue;
                $rcomission = $row->comission;
                
                if ($row->profile_pic != '') {
                    $usimg = getimge_url(
                        'assets/uploads/users/' . $row->id . '/',
                        'icon_' . $row->profile_pic,
                        'png'
                    );
                    $usimgw = getimge_url(
                        'assets/uploads/users/' . $row->id . '/',
                        'icon_' . $row->profile_pic,
                        'webp'
                    );
                } else {
                    $usimg = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                    $usimgw = base_url(
                        'assets/frontend/images/user-icon-gret.webp'
                    );
                }

                $total =
                    (int) $rcomp +
                    (int) $rconfirm +
                    (int) $rcancel;
                $comp = $conf = $canc = $tots = $revs = 0;
                $comp = $comp + $rcompe;
                $conf = $conf + $rconfirm;
                $canc = $canc + $rcancel;
                $tots = $tots + $total;
                $revs = $revs + $rrevenue;
                // <source srcset="'.$usimgw.'" type="image/webp">
                // 				    <source srcset="'.$usimgw.'" type="image/webp">
                $html =
                    $html .
                    '<tr><td class="font-size-14 height56v color666 fontfamily-regular editCust" data-id="' .
                    url_encode($row->id) .
                    '">
					  
					   <picture>
					
					 <img src="' .
                    $usimg .
                    '" class="mr-3 width30 border-radius50">
					</picture>
                        <p class="overflow_elips mb-0 display-ib color666">' .
                    $row->first_name .
                    ' ' .
                    $row->last_name .
                    '</p>
                       
                      </td>      
                      <td class="font-size-14 height56v color666 fontfamily-regular"><p class="mb-0">' .
                      $row->email .
                      '</p></td>                
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center"><p class="mb-0">' .
                    $rcomp .
                    '</p></td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular text-center">' .
                    $rconfirm .
                    '</td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                    $rcancel .
                    '</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                    $total .
                    '</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">' .
                    price_formate($rrevenue) .
                    ' €</p>
                      </td></tr>';

                $ij++;
                /*if($counts = $ij){
						$html=$html.'<tr><td class="font-size-14 height56v color666 fontfamily-regular"><p class="overflow_elips mb-0 display-ib color666"><b>TOTAL</b></p>
                       
                      </td>                      
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center"><p class="mb-0">'.$comp.'</p></td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular text-center">'.$conf.'</td>
                      <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">'.$canc.'</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">'.$tots.'</p>
                      </td>
                       <td class="font-size-14 color666 fontfamily-regular height56v text-center">
                        <p class="mb-0 display-ib">'.$revs.' €</p>
                      </td></tr>';
					}*/
            }
        } else {
            $html =
                '<tr><td colspan="7" style="text-align:center;">
                    <div class="text-center pb-20 pt-50">
                        <img src="' . base_url('assets/frontend/images/no_listing.png') . '">
                        <p style="margin-top: 20px;">' . $this->lang->line('dont_any_reports') . '</p>
                    </div>
                </td></tr>';
        }

        echo json_encode([
            'success' => '1',
            'msg' => '',
            'html' => $html,
            'pagination' => $pagination,
        ]);
        die();
    }

    //**** My Dashboard ****//
    public function mydashboard()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }
        $mid = $this->session->userdata('st_userid');
        $today = date('Y-m-d 00:00:00');
        $sevenday = date('Y-m-d 23:59:59', strtotime('+6 days'));
        $lastseven = date('Y-m-d 23:59:59', strtotime('-7 days'));
        $lastnintyday = date('Y-m-d 23:59:59', strtotime('-90 days'));

        $field =
            'st_booking.id,user_id,st_booking.updated_on,st_booking.book_id,st_booking.booking_time,st_booking.fullname,st_booking.created_on,st_booking.merchant_id,first_name,last_name,profile_pic,fullname,booking_time,booking_type,st_booking.status';

        $sqlForservice =
            "SELECT `st_booking`.`id`,`st_booking`.`user_id`,`review`,`rate`,`anonymous`,`st_booking`.`updated_on`,`st_booking`.`book_id`,`st_booking`.`booking_time`,`st_booking`.`fullname`,(SELECT first_name FROM `st_users` WHERE `st_users`.`id`=`st_booking`.`employee_id`) as emp_name,`st_booking`.`created_on`,`st_booking`.`merchant_id`,`first_name`,`last_name`,`profile_pic`,`fullname`,`booking_time`,`st_review`.`created_on` as rev_date,`booking_type`,`st_booking`.`status` FROM `st_booking` LEFT JOIN `st_users` ON `st_booking`.`user_id`=`st_users`.`id` LEFT JOIN `st_review` ON `st_booking`.`id`=`st_review`.`booking_id` WHERE st_booking.merchant_id = '" .
            $mid .
            "' AND date(st_booking.updated_on) <= '" .
            $today .
            "' AND date(st_booking.updated_on) > '" .
            $lastnintyday .
            "' AND booking_type != 'self' AND st_booking.status !='deleted' AND st_booking.employee_id != -1 ORDER BY st_booking.updated_on DESC ";

        $this->data['booking'] = $this->user->custome_query(
            $sqlForservice,
            'result'
        );

        // $this->data['booking']=$this->user->join_two_orderby('st_booking','st_users','user_id','id',array('st_booking.merchant_id' => $mid,'date(st_booking.updated_on) <=' =>$today,'date(st_booking.updated_on) >' =>$lastnintyday,'booking_type !='=>'self'),$field,'st_booking.updated_on','',0,0,'','desc','left');

        $todaytime = date('Y-m-d H:i:s');
        $sevendaytime = date('Y-m-d 23:59:59', strtotime('+6 days'));

        $this->data['upcomingbooking'] = $this->user->join_two_orderby(
            'st_booking',
            'st_users',
            'user_id',
            'id',
            [
                'st_booking.merchant_id' => $mid,
                'st_booking.booking_time >=' => $todaytime,
                'st_booking.booking_time<=' => $sevendaytime,
                'st_booking.status' => 'confirmed',
                'booking_type !=' => 'self',
            ],
            $field,
            'st_booking.booking_time',
            '',
            0,
            0,
            '',
            ' asc',
            'left'
        );

        //echo $this->db->last_query(); die;

        $field1 =
            "id,(SELECT count(id) FROM st_booking where merchant_id='" .
            $mid .
            "' AND booking_time >= '" .
            $today .
            "' AND booking_time < '" .
            $sevenday .
            "' AND status='confirmed') as confirm, (SELECT count(id) FROM st_booking where merchant_id='" .
            $mid .
            "' AND booking_time >= '" .
            $today .
            "' AND booking_time < '" .
            $sevenday .
            "' AND status='cancelled') as cancel";
        $this->data['up_count'] = $this->user->select_row(
            'st_booking',
            $field1,
            [
                'merchant_id' => $mid,
                'booking_time >' => $today,
                'booking_time <=' => $sevenday,
            ]
        );
        //echo $this->db->last_query();
        $field2 =
            "id,(SELECT SUM(total_price) FROM st_booking where merchant_id='" .
            $mid .
            "' AND booking_time > '" .
            $lastseven .
            "' AND booking_time <= '" .
            $today .
            "' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where merchant_id='" .
            $mid .
            "' AND booking_time > '" .
            $lastseven .
            "' AND booking_time <= '" .
            $today .
            "' AND status='completed') as tot_booking";
        $this->data['sale_count'] = $this->user->select_row(
            'st_booking',
            $field2,
            [
                'merchant_id' => $mid,
                'booking_time >' => $lastseven,
                'booking_time <=' => $today,
                'status' => 'completed',
            ]
        );
        //echo $this->db->last_query(); die;
        //echo '<pre>'; print_r($this->data); die;

        $today_day = strtolower(date('l'));
        $employee = $this->user->select_row(
            'st_users',
            'GROUP_CONCAT(id) as emp_id',
            ['merchant_id' => $mid, 'status' => 'active']
        );
        if (!empty($employee->emp_id)) {
            $employee = $employee->emp_id;
        } else {
            $employee = '0';
        }

        for ($i = 0; $i < 7; $i++) {
            $a[] = strtolower(date('l', strtotime('-' . $i . ' days')));
        }
        $all_daysname = "'" . implode("', '", $a) . "'";

        $sql =
            'SELECT id,(SELECT SUM(total_time) as todays_total FROM st_booking WHERE employee_id IN(' .
            $employee .
            ") AND DATE(booking_time)='" .
            date('Y-m-d') .
            "' AND (status='confirmed' OR status='completed')) as todays_total,(SELECT SUM(total_time) FROM st_booking where merchant_id='" .
            $mid .
            "' AND booking_time > '" .
            $today .
            "' AND booking_time <= '" .
            $sevendaytime .
            "' AND employee_id IN(" .
            $employee .
            ') AND (status="confirmed" OR status="completed")) as tot_time FROM st_booking';
        $this->data['booking_time'] = $this->user->custome_query($sql, 'row');

        $sqls =
            'SELECT starttime,endtime FROM st_availability WHERE user_id IN(' .
            $employee .
            ") AND type='open' AND days='" .
            $today_day .
            "'";
        $this->data['employee_today'] = $this->user->custome_query(
            $sqls,
            'result'
        );

        $sqlss =
            'SELECT starttime,endtime FROM st_availability WHERE user_id IN (' .
            $employee .
            ") AND type='open' AND days IN ('monday','tuesday','wednesday','thursday','friday','saturday','sunday')";
        $this->data['employee_alldays'] = $this->user->custome_query(
            $sqlss,
            'result'
        );

        // $sql5="SELECT id,(SELECT count(id) FROM st_invoices WHERE payment_type='card' ".$whr.") as card,(SELECT count(id) FROM st_invoices WHERE payment_type='other' ".$whr.") as other,(SELECT count(id) FROM st_invoices WHERE payment_type='cash' ".$whr.") as cash,(SELECT count(id) FROM st_invoices ) as total_paytype FROM st_invoices";

        $sql5 =
            "SELECT id,(SELECT count(st_invoices.id) FROM st_invoices JOIN `st_booking` ON `st_booking`.`id`=`st_invoices`.`booking_id` WHERE merchant_id='" .
            $mid .
            "' AND payment_type='card') as card,(SELECT count(st_invoices.id) FROM st_invoices JOIN `st_booking` ON `st_booking`.`id`=`st_invoices`.`booking_id` WHERE merchant_id='" .
            $mid .
            "' AND payment_type='other') as other,(SELECT count(st_invoices.id) FROM st_invoices JOIN `st_booking` ON `st_booking`.`id`=`st_invoices`.`booking_id` WHERE merchant_id='" .
            $mid .
            "' AND payment_type='cash') as cash,(SELECT count(st_invoices.id) FROM st_invoices ) as total_paytype FROM st_invoices";

        $this->data['pay_type'] = $this->user->custome_query($sql5, 'row');
        $this->load->view('frontend/marchant/mydashboard', $this->data);
    }

    //**** Get Booking List for chart ****//
    public function getbookinglistforchart()
    {
        $data = [];
        if ($_POST['filter'] == 'upcoming_7_day') {
            $today = date('Y-m-d');

            $startDate = date('Y-m-d 00:00:00');
            $endDate = date(
                'Y-m-d 23:59:59',
                strtotime('+6 days', strtotime($today))
            );
            //$today=date("Y-m-d", strtotime("-6 days"));
            $mid = $this->session->userdata('st_userid');
            for ($i = 1; $i < 8; $i++) {
                $s = date('Y-m-d 00:00:00', strtotime($today));
                $e = date('Y-m-d 23:59:59', strtotime($today));
                $res = $this->user->select_row(
                    'st_booking',
                    "count('id') as total,SUM(total_price) as tprice",
                    [
                        'booking_time >' => $s,
                        'booking_time <' => $e,
                        'merchant_id' => $mid,
                        'status' => 'confirmed',
                    ]
                );
                //echo $this->db->last_query(); die;
                //if(!empty($data)
                $nameOfDay = date('l', strtotime($today));
                $ODay = date('d', strtotime($today));
                $data1 = [
                    'y' => (int) $res->total,
                    'label' =>
                        substr($this->lang->line($nameOfDay), 0, 2) .
                        '. (' .
                        $ODay .
                        '.)',
                    'color' => '#00b3bf',
                ];
                $data[] = $data1;
                //{ y: 20, label: "Fri 12",color: "#00b3bf" }
                // }
                $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));
            }

            $field1 =
                "id,(SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time >= '" .
                $startDate .
                "' AND booking_time < '" .
                $endDate .
                "' AND status='confirmed') as confirm, (SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time >= '" .
                $startDate .
                "' AND booking_time < '" .
                $endDate .
                "' AND status='cancelled') as cancel";
            $up_count = $this->user->select_row('st_booking', $field1, [
                'merchant_id' => $mid,
                'booking_time >' => $startDate,
                'booking_time <=' => $endDate,
            ]);
            //echo $this->db->last_query(); die;
        } else {
            $today = date('Y-m-d');
            $maxDay = 30;

            $startDate = date('Y-m-d 00:00:00');
            $endDate = date(
                'Y-m-d 00:00:00',
                strtotime('+30 days', strtotime($startDate))
            );
            //$today=date("Y-m-d", strtotime("-6 days"));
            $mid = $this->session->userdata('st_userid');
            for ($i = 1; $i <= $maxDay; $i++) {
                $res = $this->user->select_row(
                    'st_booking',
                    "count('id') as total,SUM(total_price) as tprice",
                    [
                        'date(booking_time)' => $today,
                        'merchant_id' => $mid,
                        'status' => 'confirmed',
                    ]
                );

                //if(!empty($data)
                $nameOfDay = date('M', strtotime($today));
                $ODay = date('d', strtotime($today));
                //$res->tprice
                $data1 = [
                    'y' => (int) $res->total,
                    'label' => $nameOfDay . ' ' . $ODay,
                    'color' => '#00b3bf',
                ];
                $data[] = $data1;
                //{ y: 20, label: "Fri 12",color: "#00b3bf" }
                // }
                $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));
            }

            $field1 =
                "id,(SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time >= '" .
                $startDate .
                "' AND booking_time < '" .
                $endDate .
                "' AND status='confirmed') as confirm, (SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time >= '" .
                $startDate .
                "' AND booking_time < '" .
                $endDate .
                "' AND status='cancelled') as cancel";
            $up_count = $this->user->select_row('st_booking', $field1, [
                'merchant_id' => $mid,
                'booking_time >' => $startDate,
                'booking_time <=' => $endDate,
            ]);
        }

        //print_r($data); die;
        echo json_encode(['graphdata' => $data, 'countdata' => $up_count]);
        die();
    }

    //**** get top five services ****//
    public function gettopfiveservice()
    {
        $mid = $this->session->userdata('st_userid');
        $field =
            'st_booking_detail.id,st_booking_detail.service_name,SUM(st_booking_detail.price) as tprice,(SELECT category_name FROM st_category WHERE id=st_merchant_category.subcategory_id) as subcategory,count(st_booking_detail.service_id) as count';

        if ($_POST['filter'] == 'day') {
            $today = date('Y-m-d 23:59:59');
            $lastday = date('Y-m-d 00:00:00');
        } elseif ($_POST['filter'] == 'current_week') {
            $monday = strtotime('last monday');
            $today = date('Y-m-d 23:59:59', strtotime('+1 week', $monday));
            $lastday = date('Y-m-d 00:00:00', $monday);
        } elseif ($_POST['filter'] == 'current_month') {
            $today = date('Y-m-t 23:59:59');
            $lastday = date('Y-m-01 00:00:00');
        } elseif ($_POST['filter'] == 'current_year') {
            $today = date('Y-12-t 23:59:59');
            $lastday = date('Y-01-01 00:00:00');
        } elseif ($_POST['filter'] == 'last_month') {
            $today = date('Y-m-t 23:59:59', strtotime('last month'));
            $lastday = date('Y-m-01 00:00:00', strtotime('last month'));
        } elseif ($_POST['filter'] == 'last_year') {
            $today = date('Y-12-31 23:59:59', strtotime('last year'));
            $lastday = date('Y-01-01 00:00:00', strtotime('last year'));
            //    print_r($today);
            //    print_r($lastday);
        } else {
            $today = date('Y-m-d 23:59:59');
            $lastday = date('Y-m-d', strtotime('-6 days'));
        }

        //  $services = $this->user->join_two_orderby('st_merchant_category','st_booking_detail','id','service_id',array('st_merchant_category.created_by' => $mid,'st_booking_detail.created_on <=' =>$today, 'st_booking_detail.created_on >=' =>$lastday),$field,'count','st_booking_detail.service_id',5,0,'','desc');
        // echo $this->db->last_query();die;
        //echo '<pre>'; print_r($services); die;
        $whes = [
            'st_merchant_category.created_by' => $mid,
            'st_booking_detail.created_on <=' => $today,
            'st_booking_detail.created_on >=' => $lastday,
            'st_booking.status' => 'completed',
        ];
        $services = $this->user->getTopServiceData($field, $whes);
        $data = [];
        if (!empty($services)) {
            foreach ($services as $row) {
                $data[] = $row;
            }
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    //**** get top five staff ****//
    public function gettopfivestaff()
    {
        $mid = $this->session->userdata('st_userid');
        $field =
            'st_users.id,st_users.first_name,st_users.last_name,count(st_booking.id) as count,SUM(st_booking.total_price) as tprice';

        if ($_POST['filter'] == 'day') {
            $today = date('Y-m-d 23:59:59');
            $lastday = date('Y-m-d 00:00:00');
        } elseif ($_POST['filter'] == 'current_week') {
            $monday = strtotime('last monday');
            $today = date('Y-m-d 23:59:59', strtotime('+1 week', $monday));
            $lastday = date('Y-m-d 00:00:00', $monday);
        } elseif ($_POST['filter'] == 'current_month') {
            $today = date('Y-m-t 23:59:59');
            $lastday = date('Y-m-01 00:00:00');
        } elseif ($_POST['filter'] == 'current_year') {
            $today = date('Y-12-t 23:59:59');
            $lastday = date('Y-01-01 00:00:00');
        } elseif ($_POST['filter'] == 'last_month') {
            $today = date('Y-m-t 23:59:59', strtotime('last month'));
            $lastday = date('Y-m-01 00:00:00', strtotime('last month'));
        } elseif ($_POST['filter'] == 'last_year') {
            $today = date('Y-12-31 23:59:59', strtotime('last year'));
            $lastday = date('Y-01-01 00:00:00', strtotime('last year'));
        } else {
            $today = date('Y-m-d 23:59:59');
            $lastday = date('Y-m-d', strtotime('-6 days'));
        }
        $services = $this->user->join_two_orderby(
            'st_users',
            'st_booking',
            'id',
            'employee_id',
            [
                'st_users.merchant_id' => $mid,
                'st_booking.booking_time <=' => $today,
                'st_booking.booking_time >=' => $lastday,
                'st_booking.status' => 'completed',
            ],
            $field,
            'count',
            'st_booking.employee_id',
            5,
            0,
            '',
            'desc'
        );
        // echo $this->db->last_query();
        $data = [];
        if (!empty($services)) {
            foreach ($services as $row) {
                $data[] = $row;
            }
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    //**** get recent booking sale ****//
    public function getbookingrecentsale()
    {
        $data = [];
        $mid = $this->session->userdata('st_userid');

        if ($_POST['filter'] == 'current_week') {
            $monday = strtotime('last monday');
            $startDate = date('Y-m-d 00:00:00', $monday);
            $endDate = date('Y-m-d 23:59:59', strtotime('+7 day', $monday));

            $today = date('Y-m-d', $monday);
            for ($i = 1; $i < 8; $i++) {
                $today_start = date('Y-m-d 00:00:00', strtotime($today));
                $today_end = date('Y-m-d 23:59:59', strtotime($today));
                $res = $this->user->select_row(
                    'st_booking',
                    "count('id') as total_booking,SUM(total_price) as sales",
                    [
                        'booking_time>=' => $today_start,
                        'booking_time<=' => $today_end,
                        'merchant_id' => $mid,
                        'status' => 'completed',
                    ]
                );
                // echo $this->db->last_query(); die;

                $nameOfDay = date('l', strtotime($today));
                $ODay = date('d', strtotime($today));
                $OMonth = get_month_de_translation(date('F', strtotime($today)));
                $data['days'][] =
                    substr($this->lang->line($nameOfDay), 0, 2) .
                    '. (' .
                    $ODay .
                    '. '.
                    substr($OMonth, 0, 3).
                    ')';
                $data['booking'][] = (int) $res->total_booking;
                $data['sales'][] = (int) $res->sales;
                /*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/

                $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));
            }

            $field2 =
                "id,(SELECT SUM(total_price) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_booking";
            $sale_count = $this->user->select_row('st_booking', $field2, [
                'merchant_id' => $mid,
                'booking_time >' => $startDate,
                'booking_time <=' => $endDate,
                'status' => 'completed',
            ]);
            // echo $this->db->last_query();
            $data['sale_count'] = $sale_count;
        } elseif ($_POST['filter'] == 'current_month') {
            $today = date('Y-m-01');
            $maxDays = date('t');

            $startDate = date('Y-m-01 00:00:00');
            $endDate = date('Y-m-t 23:59:59');
            //$end_date=date('Y-m-t');
            for ($i = 1; $i <= $maxDays; $i++) {
                $today_start = date('Y-m-d 00:00:00', strtotime($today));
                $today_end = date('Y-m-d 23:59:59', strtotime($today));
                $res = $this->user->select_row(
                    'st_booking',
                    "count('id') as total_booking,SUM(total_price) as sales",
                    [
                        'booking_time>=' => $today_start,
                        'booking_time<=' => $today_end,
                        'merchant_id' => $mid,
                        'status' => 'completed',
                    ]
                );
                // echo $this->db->last_query(); die;

                $OMonth = get_month_de_translation(date('F', strtotime($today)));
                $ODay = date('d', strtotime($today));
                $data['days'][] = $ODay.'. '. substr($OMonth, 0, 3);
                $data['booking'][] = (int) $res->total_booking;
                $data['sales'][] = (int) $res->sales;
                /*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/

                $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));
            }

            $field2 =
                "id,(SELECT SUM(total_price) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_booking";

            $sale_count = $this->user->select_row('st_booking', $field2, [
                'merchant_id' => $mid,
                'booking_time >' => $startDate,
                'booking_time <=' => $endDate,
                'status' => 'completed',
            ]);
            //echo $this->db->last_query();

            $data['sale_count'] = $sale_count;
        } elseif ($_POST['filter'] == 'last_month') {
            $today = date('Y-m-01', strtotime('last month'));
            $maxDays = date('t', strtotime('last month'));

            $startDate = date('Y-m-01 00:00:00', strtotime('last month'));
            $endDate = date('Y-m-t 23:59:59', strtotime('last month'));

            for ($i = 1; $i <= $maxDays; $i++) {
                $today_start = date('Y-m-d 00:00:00', strtotime($today));
                $today_end = date('Y-m-d 23:59:59', strtotime($today));
                $res = $this->user->select_row(
                    'st_booking',
                    "count('id') as total_booking,SUM(total_price) as sales",
                    [
                        'booking_time>=' => $today_start,
                        'booking_time<=' => $today_end,
                        'merchant_id' => $mid,
                        'status' => 'completed',
                    ]
                );
                // echo $this->db->last_query(); die;

                $OMonth = get_month_de_translation(date('F', strtotime($today)));
                $ODay = date('d', strtotime($today));
                $data['days'][] = $ODay.'. '.substr($OMonth, 0, 3);
                $data['booking'][] = (int) $res->total_booking;
                $data['sales'][] = (int) $res->sales;
                /*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/

                $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));
            }

            $field2 =
                "id,(SELECT SUM(total_price) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_booking";

            $sale_count = $this->user->select_row('st_booking', $field2, [
                'merchant_id' => $mid,
                'booking_time >' => $startDate,
                'booking_time <=' => $endDate,
                'status' => 'completed',
            ]);
            // echo $this->db->last_query();
            $data['sale_count'] = $sale_count;
        } elseif ($_POST['filter'] == 'current_year') {
            $today = date('Y-01-01');
            $maxDays = 12;

            $startDate = date('Y-01-01 00:00:00');
            $endDate = date('Y-12-t 23:59:59');

            for ($i = 1; $i <= $maxDays; $i++) {
                $today_start = date('Y-m-d 00:00:00', strtotime($today));
                $today_end = date('Y-m-t 23:59:59', strtotime($today));
                $res = $this->user->select_row(
                    'st_booking',
                    "count('id') as total_booking,SUM(total_price) as sales",
                    [
                        'booking_time>=' => $today_start,
                        'booking_time<=' => $today_end,
                        'merchant_id' => $mid,
                        'status' => 'completed',
                    ]
                );
                // echo $this->db->last_query(); die;

                $OMonth = get_month_de_translation(date('F', strtotime($today)));
                $data['days'][] = substr($OMonth, 0, 3);
                $data['booking'][] = (int) $res->total_booking;
                $data['sales'][] = (int) $res->sales;
                /*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/

                $today = date(
                    'Y-m-d',
                    strtotime('+1 month', strtotime($today))
                );
            }

            $field2 =
                "id,(SELECT SUM(total_price) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time> '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_booking";

            $sale_count = $this->user->select_row('st_booking', $field2, [
                'merchant_id' => $mid,
                'booking_time >' => $startDate,
                'booking_time <=' => $endDate,
                'status' => 'completed',
            ]);
            //echo $this->db->last_query();

            $data['sale_count'] = $sale_count;
        } elseif ($_POST['filter'] == 'last_year') {
            $today = date('Y-01-01', strtotime('last year'));
            $maxDays = 12;

            $startDate = date('Y-01-01 00:00:00', strtotime('last year'));
            $endDate = date('Y-12-t 23:59:59', strtotime('last year'));

            for ($i = 1; $i <= $maxDays; $i++) {
                $today_start = date('Y-m-d 00:00:00', strtotime($today));
                $today_end = date('Y-m-t 23:59:59', strtotime($today));
                $res = $this->user->select_row(
                    'st_booking',
                    "count('id') as total_booking,SUM(total_price) as sales",
                    [
                        'booking_time>=' => $today_start,
                        'booking_time<=' => $today_end,
                        'merchant_id' => $mid,
                        'status' => 'completed',
                    ]
                );
                // echo $this->db->last_query(); die;

                $OMonth = get_month_de_translation(date('F', strtotime($today)));
                $year = date('Y', strtotime($today));
                $data['days'][] = substr($OMonth, 0, 3) . ' ' . $year;
                $data['booking'][] = (int) $res->total_booking;
                $data['sales'][] = (int) $res->sales;
                /*$data1=array("y"=>(int)$res->total,
					 "label" => $nameOfDay.' '.$ODay,
					 "color" => "#00b3bf" );
				$data[]=$data1;*/

                $today = date(
                    'Y-m-d',
                    strtotime('+1 month', strtotime($today))
                );
            }

            $field2 =
                "id,(SELECT SUM(total_price) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_booking";

            $sale_count = $this->user->select_row('st_booking', $field2, [
                'merchant_id' => $mid,
                'booking_time >' => $startDate,
                'booking_time <=' => $endDate,
                'status' => 'completed',
            ]);
            //echo $this->db->last_query();

            $data['sale_count'] = $sale_count;
        } elseif ($_POST['filter'] == 'last_seven_day') {
            $today = date('Y-m-d', strtotime('-6 days'));

            $startDate = date('Y-m-d 00:00:00', strtotime($today));
            $endDate = date('Y-m-d 23:59:59');

            //$today= date('Y-m-d');
            $mid = $this->session->userdata('st_userid');
            for ($i = 1; $i < 8; $i++) {
                $today_start = date('Y-m-d 00:00:00', strtotime($today));
                $today_end = date('Y-m-d 23:59:59', strtotime($today));
                $res = $this->user->select_row(
                    'st_booking',
                    "count('id') as total_booking,SUM(total_price) as sales",
                    [
                        'booking_time>=' => $today_start,
                        'booking_time<=' => $today_end,
                        'merchant_id' => $mid,
                        'status' => 'completed',
                    ]
                );
                // echo $this->db->last_query(); die;

                $nameOfDay = date('l', strtotime($today));
                $ODay = date('d', strtotime($today));
                $OMonth = get_month_de_translation(date('F', strtotime($today)));
                $data['days'][] =
                    substr($this->lang->line($nameOfDay), 0, 2) .
                    '. (' .
                    $ODay .
                    '. '.
                    substr($OMonth, 0, 3).
                    ')';
                $data['booking'][] = (int) $res->total_booking;
                $data['sales'][] = (int) $res->sales;
                /*$data1=array("y"=>(int)$res->total,
				 "label" => $nameOfDay.' '.$ODay,
				 "color" => "#00b3bf" );
		 	$data[]=$data1;*/

                $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));
            }

            $field2 =
                "id,(SELECT SUM(total_price) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time <= '" .
                $endDate .
                "' AND status='completed') as tot_booking";

            $sale_count = $this->user->select_row('st_booking', $field2, [
                'merchant_id' => $mid,
                'booking_time >' => $startDate,
                'booking_time <=' => $endDate,
                'status' => 'completed',
            ]);
            // echo $this->db->last_query();
            $data['sale_count'] = $sale_count;
        } else {
            $monday = strtotime('last monday');
            $today = date('Y-m-d', $monday);
            $currentdate = date('Y-m-d');

            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');

            for ($i = 1; $i < 8; $i++) {
                if ($currentdate == date('Y-m-d', strtotime($today))) {
                    $today_start = date('Y-m-d 00:00:00', strtotime($today));
                    $today_end = date('Y-m-d 23:59:59', strtotime($today));
                    $res = $this->user->select_row(
                        'st_booking',
                        "count('id') as total_booking,SUM(total_price) as sales",
                        [
                            'booking_time>=' => $today_start,
                            'booking_time<=' => $today_end,
                            'merchant_id' => $mid,
                            'status' => 'completed',
                        ]
                    );
                    // echo $this->db->last_query(); die;

                    $nameOfDay = date('l', strtotime($today));
                    $ODay = date('d', strtotime($today));
                    $OMonth = get_month_de_translation(date('F', strtotime($today)));
                    $data['days'][] =
                        substr($this->lang->line($nameOfDay), 0, 2) .
                        '. (' .
                        $ODay .
                        '. '.
                        substr($OMonth, 0, 3).
                        ')';
                    $data['booking'][] = (int) $res->total_booking;
                    $data['sales'][] = (int) $res->sales;
                } else {
                    $nameOfDay = date('l', strtotime($today));
                    $ODay = date('d', strtotime($today));
                    $OMonth = get_month_de_translation(date('F', strtotime($today)));
                    $data['days'][] =
                        substr($this->lang->line($nameOfDay), 0, 2) .
                        '. (' .
                        $ODay .
                        '. '.
                        substr($OMonth, 0, 3).
                        ')';
                    $data['booking'][] = 0;
                    $data['sales'][] = 0;
                }

                /*$data1=array("y"=>(int)$res->total,
						 "label" => $nameOfDay.' '.$ODay,
						 "color" => "#00b3bf" );
					$data[]=$data1;*/

                $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));
            }
            $field2 =
                "id,(SELECT SUM(total_price) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time> '" .
                $startDate .
                "' AND booking_time<= '" .
                $endDate .
                "' AND status='completed') as tot_sale, (SELECT count(id) FROM st_booking where merchant_id='" .
                $mid .
                "' AND booking_time > '" .
                $startDate .
                "' AND booking_time<= '" .
                $endDate .
                "' AND status='completed') as tot_booking";

            $sale_count = $this->user->select_row('st_booking', $field2, [
                'merchant_id' => $mid,
                'booking_time >' => $startDate,
                'booking_time <=' => $endDate,
                'status' => 'completed',
            ]);
            //echo $this->db->last_query();
            $data['sale_count'] = $sale_count;
        }
        if (!empty($data['sale_count']->tot_sale)) {
            $data['sale_count']->tot_sale = price_formate(
                $data['sale_count']->tot_sale
            );
        }

        echo json_encode($data);
        die();
    }

    public function bookingtime_resize_by_popup() {
        $mid = $this->session->userdata('st_userid');
        if ($mid) {
            $dat = (array)json_decode($_POST['details']);
            $bdid = array_keys($dat)[0];
            $temp = $this->user->select_row(
                'st_booking_detail',
                '*',
                ['id' => $bdid]
            );
            $book_details = $this->user->select(
                'st_booking_detail',
                '*',
                ['booking_id' => $temp->booking_id, 'show_calender !=' => 1],
                '',
                'id',
                'ASC'
            );

            if (!empty($book_details)) {
                $edate = '';
                $tot_duration = 0;
                $tot_buffer = 0;
                foreach ($book_details as $serv) {
                    if ($edate == '') {
                        $edate = $serv->setuptime_start;
                    }
                    $durations = $dat[$serv->id];
                    if ($durations) {
                        $nstart = $edate;
                        $edate = date('Y-m-d H:i:s', strtotime($nstart . ' + ' . $durations[0] . ' minute'));

                        if ($serv->service_type == 1) {
                            $edate = date('Y-m-d H:i:s', strtotime($nstart . ' + ' . $durations[0] . ' minute'));
                            $finishtime_start = date('Y-m-d H:i:s', strtotime($edate . ' + ' . $durations[1] . ' minute'));
                            $finishtime_end = date('Y-m-d H:i:s', strtotime($finishtime_start . ' + ' . $durations[2] . ' minute'));
                            $this->user->update(
                                'st_booking_detail',
                                [
                                    'setuptime_start' => $nstart,
                                    'setuptime_end' => $edate,
                                    'setuptime' => $durations[0],
                                    'duration' => $durations[0] + $durations[1] + $durations[2],
                                    'processtime' => $durations[1],
                                    'finishtime' => $durations[2],
                                    'finishtime_start' => $finishtime_start,
                                    'finishtime_end' => $finishtime_end
                                ],
                                ['id' => $serv->id]
                            );
                            $edate = $finishtime_end;
                            $tot_duration += ($durations[0] + $durations[1] + $durations[2]);
                        } else if ($serv->has_buffer) {
                            $edate = date('Y-m-d H:i:s', strtotime($nstart . ' + ' . ($durations[0] + $durations[1]) . ' minute'));
                            $this->user->update(
                                'st_booking_detail',
                                [
                                    'setuptime_start' => $nstart,
                                    'setuptime_end' => $edate,
                                    'duration' => $durations[0] + $durations[1],
                                    'buffer_time' => $durations[1]
                                ],
                                ['id' => $serv->id]
                            );
                            $tot_duration += ($durations[0]);
                            $tot_buffer += $durations[1];
                        } else {
                            $this->user->update(
                                'st_booking_detail',
                                [
                                    'duration' => $durations[0],
                                    'setuptime_start' => $nstart,
                                    'setuptime_end' => $edate
                                ],
                                ['id' => $serv->id]
                            );
                            $tot_duration += $durations[0];
                        }
                    } else {
                        $tot_duration += ($serv->duration - $serv->buffer_time);
                        $tot_buffer += $serv->buffer_time;
                    }
                }

                $bookinfo = $this->user->select_row('st_booking', '*', [
                    'id' => $temp->booking_id
                ]);
                $this->user->update(
                    'st_booking',
                    [
                        'total_minutes' => $tot_duration,
                        'total_time' => $tot_duration + $tot_buffer,
                        'total_buffer' => $tot_buffer,
                        'booking_endtime' => date('Y-m-d H:i:s', strtotime($bookinfo->booking_time . ' + ' . ($tot_duration + $tot_buffer) . ' minute'))
                    ],
                    ['id' => $bookinfo->id]
                );
            }
            echo json_encode([ 'success' => 1]);
        } else {
            echo json_encode([
                'success' => 0,
                'msg' => 'unable to resize.',
            ]);
        }
    }


    //**** block time reshedule ****//  d-flex cnc_top
    public function blocktime_reshedule()
    {
        $newtime = $_POST['new_time'];
        $firstCharacter = substr($_POST['new_time'], 0, 1);

        if ($firstCharacter == '-') {
            $cal_time = $_POST['new_time'];
        } else {
            $cal_time = $_POST['new_time'];
        }
        $seconds = floor($cal_time / 1000);
        $minutes = floor($seconds / 60);

        $start_seconds = floor($_POST['start'] / 1000);
        $start_minutes = floor($start_seconds / 60);
        $end_seconds = floor($_POST['end'] / 1000);
        $end_minutes = floor($end_seconds / 60);

        $booktime_minuts = $end_minutes - $start_minutes;
        //$hours = floor($minutes / 60);
        //$start = '2019-06-03 15:00:00';
        //echo date('Y-m-d H:i',strtotime($minutes.' minutes',strtotime($start)));
        //die;
        //$newDate = date("Y-m-d", strtotime($originalDate));
        $field = 'id,merchant_id,booking_time,booking_endtime';

        $info = $this->user->select_row('st_booking', $field, [
            'id' => $_POST['blocked_id'],
        ]);

        if (!empty($info)) {
            $new_book = date(
                'Y-m-d H:i',
                strtotime($minutes . ' minutes', strtotime($info->booking_time))
            );
            $tdy = date(
                'Y-m-d',
                strtotime($info->booking_time)
            );

            if ($new_book < date('Y-m-d')) {
                if ($tdy < date('Y-m-d')) {
                    echo json_encode([
                        'success' => 0,
                        'msg' =>
                            'Eine bereits vergangener blockierter Zeitraum kann nicht verlegt werden.',
                    ]);
                    die();
                }
                echo json_encode([
                    'success' => 0,
                    'msg' =>
                        'Du kannst keine Buchung um mehr als einen Tag in die Vergangenheit verlegen.',
                ]);
                die();
            }

            $newDate = date('Y-m-d', strtotime($new_book));
            $str_time = date('H:i', strtotime($new_book));

            $toDate = date('Y-m-d');

            $date = $newDate;
            //$nameOfDay = date('l', strtotime($date));
            $totalMinutes = $booktime_minuts;
            $times = strtotime($str_time); //$_POST['chg_time']
            $newTime = date(
                'H:i',
                strtotime('+ ' . $totalMinutes . ' minutes', $times)
            );

            $bk_time = $newDate . ' ' . $str_time;
            $newtimestamp = strtotime(
                '' . $bk_time . ' + ' . $totalMinutes . ' minute'
            );
            $book_end = date('Y-m-d H:i:s', $newtimestamp);

            $bk_time = $newDate . ' ' . $str_time;
            $newtimestamp = strtotime(
                '' . $bk_time . ' + ' . $totalMinutes . ' minute'
            );
            $book_end = date('Y-m-d H:i:s', $newtimestamp);

            $upd['booking_time'] = $bk_time;
            $upd['booking_endtime'] = $book_end;

            // if(empty($check)  && !empty($booking_detail)){
            if (
                $this->user->update('st_booking', $upd, [
                    'blocked' => $_POST['blocked_id'],
                ])
            ) {
                echo json_encode(['success' => 1]);
            } else {
                echo json_encode([
                    'success' => 0,
                    'msg' => 'unable to process.',
                ]);
            }

            //date("h:i");
        } else {
            echo json_encode([
                'success' => 0,
                'msg' => 'Block time already deleted.',
            ]);
        }
    }

    //***** get block time details ****//
    public function get_blocktime_details()
    {
        // echo '<pre>'; print_r($_POST); die;
        $field =
            'count(id) as totalCount,employee_id,blocked_type,block_for,blocked';
        $info = $this->user->select_row('st_booking', $field, [
            'blocked' => $_POST['block_id'],
        ]);
        $mid = $this->session->userdata('st_userid');
        if (!empty($info->totalCount)) {
            if ($info->block_for != 1) {
                $blockEmpsquery =
                    'SELECT bk.id,bk.employee_id,us.first_name, us.last_name FROM st_booking as bk LEFT JOIN st_users as us ON us.id=bk.employee_id WHERE bk.blocked=' .
                    $info->blocked;

                $blockEmps = $this->user->custome_query($blockEmpsquery);
                $emparray = [];
                $empids = [];
                if (!empty($blockEmps)) {
                    foreach ($blockEmps as $emp) {
                        $emparray[] = $emp->first_name . ' ' . $emp->last_name;
                        $empids[] = url_encode($emp->employee_id);
                    }
                }

                //echo '<pre>'; print_r($emparray); die;
                $empcheck = '';
                if ($info->totalCount > 1) {
                    $empname = $info->totalCount . ' Mitarbeiter ausgewählt';
                } else {
                    $empname = implode($emparray, ',');
                }
            } else {
                $empcheck = 'yes';
                $empname = $this->lang->line('All_staff');
            }

            $nameOfDay = date('l', strtotime($_POST['date']));

            $resTime = $this->user->select_row(
                'st_availability',
                'starttime,endtime',
                [
                    'user_id' => $mid,
                    'type' => 'open',
                    'days' => strtolower($nameOfDay),
                ]
            );

            if (!empty($resTime)) {
                $endHtml = '';
                $startHtml = '';
                $i = 0;

                $tStart = strtotime($resTime->starttime);
                $tEnd = strtotime($resTime->endtime);
                $tNow = $tStart;

                while ($tNow <= $tEnd) {
                    if ($i == 0) {
                        $startClass = 'checkFirstSlot';
                    } else {
                        $startClass = '';
                    }

                    if ($tNow == $tEnd) {
                        $endClass = 'checkLastSlot';
                    } else {
                        $endClass = '';
                    }

                    $endHtml .=
                        '<li class="radiobox-image">
							<input type="radio" id="id_' .
                        date('H-i', $tNow) .
                        '" name="endtime" class="' .
                        $endClass .
                        '" value="' .
                        date('H:i:s', $tNow) .
                        '" data-val="' .
                        date('H:i', $tNow) .
                        '">
							<label for="id_' .
                        date('H-i', $tNow) .
                        '">' .
                        date('H:i', $tNow) .
                        '</label>
						  </li>';

                    $startHtml .=
                        '<li class="radiobox-image">
							<input type="radio" id="idstart_' .
                        date('H-i', $tNow) .
                        '" name="starttime" class="' .
                        $startClass .
                        '" data-val="' .
                        date('H:i', $tNow) .
                        '" value="' .
                        date('H:i:s', $tNow) .
                        '">
							<label for="idstart_' .
                        date('H-i', $tNow) .
                        '">' .
                        date('H:i', $tNow) .
                        '</label>
						  </li>';
                    $tNow = strtotime('+15 minutes', $tNow);
                    $i++;
                }

                $endtimes =
                    '<span class="label" id="levelEnd">Endzeit</span>
								 <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn timeSelected height56v levelEnd"></button>
								 <ul class="dropdown-menu mss_sl_btn_dm custome_scroll height200 "  style="max-height: none; overflow-x: auto; height: 320px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -322px, 0px);" x-placement="top-start">' .
                    $endHtml .
                    '							
								</ul>';

                $startTime =
                    '<span class="label" id="levelStart">Startzeit</span>
								 <button data-toggle="dropdown" class="btn btn-default dropdown-toggle mss_sl_btn timeSelected height56v levelStart"></button>
								  <ul class="dropdown-menu mss_sl_btn_dm custome_scroll height200" style="max-height: none; overflow-x: auto; height: 320px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -322px, 0px);" x-placement="top-start">' .
                    $startHtml .
                    '									
								</ul>';

                //echo json_encode(['success'=>1,'starttime'=>$startTime,'endtime'=>$endtimes]); die;
            } else {
                $startTime = '';
                $startTime = '';
                //echo json_encode(['success'=>0,'starttime'=>'','endtime'=>'']); die;
            }

            if ($info->totalCount > 1) {
                echo json_encode([
                    'success' => 1,
                    'employee_txt' => $empname,
                    'emp_id' => 'all',
                    'empcheck' => $empcheck,
                    'starttime' => $startTime,
                    'endtime' => $endtimes,
                    'emp_ids' => $empids,
                    'blocked_type' => $info->blocked_type,
                ]);
            } else {
                echo json_encode([
                    'success' => 1,
                    'employee_txt' => $empname,
                    'emp_id' => $info->employee_id,
                    'empcheck' => $empcheck,
                    'starttime' => $startTime,
                    'endtime' => $endtimes,
                    'emp_ids' => $empids,
                    'blocked_type' => $info->blocked_type,
                ]);
            }
        } else {
            echo json_encode([
                'success' => 0,
                'employee_txt' => '',
                'emp_id' => '',
                'empcheck' => '',
                'starttime' => '',
                'endtime' => '',
            ]);
        }
    }
    //**** Block delete ****//
    public function blockdelete($id = '')
    {
        if (!empty($id)) {
            $getDate = $this->db->query("SELECT * FROM st_booking WHERE id=".$id."");
            $getDate = $getDate->row()->booking_time;
            if(isset($getDate) && $getDate !="") {
                $_SESSION['block_slot_delete_date_value'] = $getDate;
            }

            $this->user->delete('st_booking', ['blocked' => $id]);
        }
        $this->session->set_flashdata(
            'success',
            'Blockierung erfolgreich gelöscht'
        );
        redirect(base_url('merchant/dashboard'));
    }

    //*** cancel booking popup view   ***//
    public function cancel_bookingpopup()
    {
        $mid = $this->session->userdata('st_userid');
        $info = $this->user->join_two(
            'st_booking_notification',
            'st_booking',
            'booking_id',
            'id',
            ['st_booking_notification.merchant_id' => $mid, 'view_status' => 0],
            'st_booking_notification.booking_id,book_id,booking_time,book_by,(select first_name from st_users WHERE id = st_booking_notification.created_by) as first_name,(select last_name from st_users WHERE id = st_booking_notification.created_by) as last_name'
        );
        // print_r($info);die;
        $html = '';
        if (!empty($info)) {
            foreach ($info as $book) {
                if ($book->book_by == '0') {
                    $bookedvia = 'Web';
                } else {
                    $bookedvia = 'App';
                }
                $book_details = $this->user->select(
                    'st_booking_detail',
                    'id,booking_id,service_id,service_name',
                    ['booking_id' => $book->booking_id],
                    '',
                    'id',
                    'ASC'
                );
                // $this->db->last_query();die;
                $sevices = '';
                if (!empty($book_details)) {
                    $idx = 0;
                    foreach ($book_details as $serv) {
                        if ($idx) {
                            $sevices .= ',';
                            $sevices .= '<br/>';
                        }
                        $sub_name = get_subservicename($serv->service_id);

                        $sevices .= $sub_name;
                        if ($sub_name != $serv->service_name) {
                            $sevices .= ('-' . $serv->service_name);
                        }
                        $idx++;
                        //   if($sub_name == $serv->service_name)
                        //      $sevices.=$serv->service_name.',';
                        //  else
                        //      $sevices.=$serv->service_name.',';
                    }
                }
                //$name = rtrim($sevices, ',');
                $name = $sevices;
                $newString = substr(trim($name), 0, -5);
                //$totalString = strlen($name);
                $newString = rtrim($name, ', ');
                // $totalString - 1;
                //print_r($newString);die;
                $germ_month = [
                    'Jan' => 'Jan',
                    'Feb' => 'Feb',
                    'Mar' => 'Mär',
                    'Apr' => 'Apr',
                    'May' => 'Mai',
                    'Jun' => 'Jun',
                    'Jul' => 'Jul',
                    'Aug' => 'Aug',
                    'Sep' => 'Sep',
                    'Oct' => 'Okt',
                    'Nov' => 'Nov',
                    'Dec' => 'Dez',
                ];
                $rev_month = date('M', strtotime($book->booking_time));
                $mon = $germ_month[$rev_month];

                $date = date('d M Y, H:i', strtotime($book->booking_time));
                $ger_date = str_replace($rev_month, $mon, $date);
                //get_servicename($book->booking_id)
                //$bookurl=base_url('booking/detail/').url_encode($book->booking_id);
                $bookurl = base_url('merchant/booking_listing');
                $html .=
                    ' <div class="modal-body pt-10 pl-30 pr-30">
           <h5 class="text-left fontfamily-medium color333 font-size-16">' .
                    $book->first_name .
                    ' ' .
                    $book->last_name .
                    ' ' .
                    $this->lang->line('has_cancelled_this_booking') .
                    '</h5>
           <h6 class="text-left fontfamily-medium colororange font-size-14">' .
                    $newString .
                    '</h6>
           <div class="row text-left">
            <div class="col-md-4">
              <label>' .
                    $this->lang->line('Booking_Id') .
                    '</label>
              <p class="fontfamily-medium color333 "><a class="color333 text-underline booking_row" id="' .
                    url_encode($book->booking_id) .
                    '" href="#">' .
                    $book->book_id .
                    '</a></p>
            </div>
            <div class="col-md-4">
              <label>' .
                    $this->lang->line('Booking_Date1') .
                    '</label>
			  
              <p class="fontfamily-medium color333">' .
                    $ger_date .
                    ' Uhr</p>
            </div>
            <div class="col-md-4">
              <label>' .
                    $this->lang->line('booking_mode') .
                    '</label>
              <p class="fontfamily-medium color333">' .
                    $bookedvia .
                    '</p>
            </div>
           </div>
          </div>';
            }
        }

        if (
            $this->user->update(
                'st_booking_notification',
                ['view_status' => 1],
                ['merchant_id' => $mid, 'view_status' => 0]
            )
        ) {
            echo json_encode([
                'success' => 1,
                'msg' => 'success',
                'html' => $html,
            ]);
        } else {
            echo json_encode(['success' => 0, 'msg' => 'unable to process.']);
        }
    }

    // search client

    public function search_client()
    {
        $this->load->view('frontend/marchant/search_client');
    }
    function search_client_filter()
    {

        extract($_POST);
        $where = '';
        $date = date('Y-m-d');
        $c_list = 'Keine Daten vorhanden';
        $b_list = 'Keine Daten vorhanden';
        $rowperpage = 10;
        $offset = 0;
        $c_count = $b_count = 0;
        $m_id = $this->session->userdata('st_userid');
        if (!empty($search_client)) {
            $where =
                "AND (st_users.first_name LIKE '%" .
                $search_client .
                "%' OR st_users.last_name LIKE '%" .
                $search_client .
                "%' OR st_users.email LIKE '%" .
                $search_client .
                "%' OR st_users.mobile LIKE '%" .
                $search_client .
                "%' OR st_booking.book_id LIKE '%" .
                $search_client .
                "%')";
        }

        $sqlForusercount =
            "SELECT st_booking.user_id client FROM `st_booking` LEFT JOIN `st_users` ON `st_booking`.`user_id`=`st_users`.`id` WHERE `st_booking`.`merchant_id`='" .
            $m_id .
            "' AND `st_booking`.`booking_type` ='user' AND `st_users`.`status` != 'deleted' " .
            $where .
            ' GROUP BY st_booking.user_id';

        $c_list_count = $this->user->custome_query($sqlForusercount, 'result');

        //echo $this->db->last_query();

        if (!empty($c_list_count)) {
            $c_count = count($c_list_count);
        }

        $sqlForuser =
            "SELECT `st_booking`.`id`,`st_booking`.`user_id`,`st_users`.`first_name`,`st_users`.`last_name`,`st_users`.`mobile`,`profile_pic` FROM `st_booking` LEFT JOIN `st_users` ON `st_booking`.`user_id`=`st_users`.`id` WHERE `st_booking`.`merchant_id`='" .
            $m_id .
            "' AND `st_booking`.`booking_type` ='user' AND `st_users`.`status` != 'deleted' " .
            $where .
            ' GROUP BY st_booking.user_id ORDER BY st_booking.id ASC';
        //LIMIT ".$rowperpage." OFFSET ".$offset."

        $client_list = $this->user->custome_query($sqlForuser, 'result');

        if (!empty($client_list)) {
            $c_list = '';

            $userId = [];
            foreach ($client_list as $us) {
                $userId[] = $us->user_id;
            }

            $sqlForbook_count =
                "SELECT `st_booking`.`id` FROM `st_booking` LEFT JOIN `st_users` ON `st_booking`.`employee_id`=`st_users`.`id` WHERE `st_booking`.`merchant_id`='" .
                $m_id .
                "' AND `st_booking`.`status` = 'confirmed' AND DATE(booking_time) >= '" .
                $date .
                "' AND st_booking.user_id IN (" .
                implode(',', $userId) .
                ')';
            $b_list_count = $this->user->custome_query(
                $sqlForbook_count,
                'result'
            );
            if (!empty($b_list_count)) {
                $b_count = count($b_list_count);
            }

            //~ $bkWhre="";
            //~ if(!empty($search_client)){
            //~ $bkWhre =" AND bookid=".$search_client;
            //~ }

            $sqlForbooking =
                "SELECT `st_booking`.`id`,`st_booking`.`booking_time`,`total_price`,`total_minutes`,`st_users`.`first_name`,`st_users`.`last_name`,`st_users`.`mobile`,`profile_pic` FROM `st_booking` LEFT JOIN `st_users` ON `st_booking`.`employee_id`=`st_users`.`id` WHERE `st_booking`.`merchant_id`='" .
                $m_id .
                "' AND `st_booking`.`status` = 'confirmed' AND DATE(booking_time) >= '" .
                $date .
                "' AND st_booking.user_id IN (" .
                implode(',', $userId) .
                ') ORDER BY st_booking.booking_time ASC LIMIT ' .
                $rowperpage .
                ' OFFSET ' .
                $offset .
                ' ';
            $booking_list = $this->user->custome_query(
                $sqlForbooking,
                'result'
            );
            if (!empty($booking_list)) {
                $b_list = '';
                foreach ($booking_list as $booking) {
                    $day = date('d', strtotime($booking->booking_time));
                    $month = date('M', strtotime($booking->booking_time));
                    $time = date('H:i', strtotime($booking->booking_time));
                    $nameOfDay = date('D', strtotime($booking->booking_time));

                    $arr_days=['Mon'=>'Mo.','Tue'=>'Di.','Wed'=>'Mi.','Thu'=>'Do.','Fri'=>'Fr.','Sat'=>'Sa.','Sun'=>'So.'];
                    $arr_month=['Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mär','Apr'=>'Apr','May'=>'Mai','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Okt','Nov'=>'Nov','Dec'=>'Dez'];

                    $month = $arr_month[$month];
                    $nameOfDay = $arr_days[$nameOfDay];

                    $total_time = $booking->total_minutes . ' Min';
                    $book_detail = getselect(
                        'st_booking_detail',
                        'id,booking_id,service_id,service_name',
                        ['booking_id' => $booking->id]
                    );

                    $ser_nm = '';
                    foreach ($book_detail as $serv) {
                        $sub_name = get_subservicename($serv->service_id);
                        if ($sub_name == $serv->service_name) {
                            $ser_nm .= $serv->service_name . ',';
                        } else {
                            $ser_nm .=
                                $sub_name . ' - ' . $serv->service_name . ',';
                        }
                    }

                    $b_list .=
                        '<div class=" on-hover-div">
                    <a class="booking_row" id="' .
                        url_encode($booking->id) .
                        '"">
                    <div class="inner-hover-div">
                      <div class="start-flex" style="align-self: flex-start;">
                        <span class="display-b fontsize-24 color333 pt-3">' .
                        $day .
                        '</span>
                        <span class="display-b fontsize-14 color333" style="">' .
                        $month .
                        '</span>
                      </div>
                      <div class="" style="display: flex; flex-direction: column;flex-grow: 1;">
                        <span class="fontsize-14 color333" style="">' .
                        $nameOfDay .
                        ', ' .
                        $time .
                        ' Uhr</span>
                        <p class="color333 fontsize-16 fontfamily-medium mb-0">' .
                        rtrim($ser_nm, ',') .
                        '</p>
                        <span class="fontsize-14 color333" style="">' .
                        $total_time .
                        '. bei ' .
                        $booking->first_name .
                        '</span>
                      </div>
                      <span class="fontsize-16 color333 fontfamily-medium">€ ' .
                        price_formate($booking->total_price) .
                        '</span>
                    </div></a>
                  </div>';
                }
                if ($b_count > 10) {
                    $b_list .=
                        '<a href="javascript:void(0)" data-page="2" class="colorblue a_hover_blue font-size-14 fontfamily-medium display-b p-3 load_booking" style="margin-top:20px;color:#00b3bf;">Weitere Buchungen ansehen</a>';
                }
            }

            foreach ($client_list as $client) {
                $pic = '';
                if ($client->profile_pic != '') {
                    $pic =
                        base_url('assets/uploads/users/') .
                        $client->user_id .
                        '/icon_' .
                        $client->profile_pic;
                }

                $c_list .=
                    '<a href="#" data-id="' .
                    url_encode($client->user_id) .
                    '" class="editCust"><div class=" on-hover-div d-flex" style="align-items: center;">
            <div class="display-ib mr-3  new-popup-img-text">';
                if ($pic != '') {
                    $c_list .= '<img src="' . $pic . '" class="">';
                } else {
                    $c_list .=
                        '<span>' .
                        substr($client->first_name, 0, 1) .
                        '</span>';
                }
                $c_list .=
                    '</div>
            <div class="display-ib">
              <p class="mb-0 color333 fontsize-16 fontfamily-medium">' .
                    $client->first_name .
                    ' ' .
                    $client->last_name .
                    '</p>
              <span class="fontsize-14 color333 fontfamily-regular">' .
                    $client->mobile .
                    '</span>
            </div>
          </div></a>';
            }
            /*if($c_count > 10)
             $c_list.='<a href="javascript:void(0)" data-page="2" class="colorblue a_hover_blue font-size-14 fontfamily-medium display-b p-3 load_client" style="margin-top:20px;">See more apointments</a>';*/

            echo json_encode([
                'success' => 1,
                'booking_list' => $b_list,
                'client_list' => $c_list,
                'c_count' => $c_count,
                'b_count' => $b_count,
            ]);
        } else {
            echo json_encode([
                'success' => 0,
                'booking_list' => $b_list,
                'client_list' => $c_list,
            ]);
        }
    }

    // load more booking
    function loadmore_booking()
    {
        extract($_POST);
        $where = '';
        $date = date('Y-m-d');
        $b_list = '';
        $rowperpage = 10;
        $offset = 0;
        if ($page != 0) {
            $offset = ($page - 1) * $rowperpage;
        }
        $b_count = 0;
        $m_id = $this->session->userdata('st_userid');
        if (!empty($search_client)) {
            $where =
                "AND (st_users.first_name LIKE '%" .
                $search_client .
                "%' OR st_users.last_name LIKE '%" .
                $search_client .
                "%' OR st_users.email LIKE '%" .
                $search_client .
                "%' OR st_users.mobile LIKE '%" .
                $search_client .
                "%' OR st_booking.id LIKE '%" .
                $search_client .
                "%')";
        }

        $sqlForuser =
            "SELECT `st_booking`.`user_id` FROM `st_booking` LEFT JOIN `st_users` ON `st_booking`.`user_id`=`st_users`.`id` WHERE `st_booking`.`merchant_id`='" .
            $m_id .
            "' AND `st_booking`.`booking_type` ='user' AND `st_users`.`status` != 'deleted' " .
            $where .
            ' GROUP BY st_booking.user_id ORDER BY st_booking.id ASC ';

        $client_list = $this->user->custome_query($sqlForuser, 'result');

        if (!empty($client_list)) {
            $c_list = '';
            $b_list = '';
            $userId = [];
            foreach ($client_list as $us) {
                $userId[] = $us->user_id;
            }

            $sqlForbook_count =
                "SELECT `st_booking`.`id` FROM `st_booking` LEFT JOIN `st_users` ON `st_booking`.`employee_id`=`st_users`.`id` WHERE `st_booking`.`merchant_id`='" .
                $m_id .
                "' AND `st_booking`.`status` = 'confirmed' AND DATE(booking_time) <= '" .
                $date .
                "' AND st_booking.user_id IN (" .
                implode(',', $userId) .
                ')';
            $b_list_count = $this->user->custome_query(
                $sqlForbook_count,
                'result'
            );
            if (!empty($b_list_count)) {
                $b_count = count($b_list_count);
            }

            $sqlForbooking =
                "SELECT `st_booking`.`id`,`st_booking`.`booking_time`,`total_price`,`total_minutes`,`st_users`.`first_name`,`st_users`.`last_name`,`st_users`.`mobile`,`profile_pic` FROM `st_booking` LEFT JOIN `st_users` ON `st_booking`.`employee_id`=`st_users`.`id` WHERE `st_booking`.`merchant_id`='" .
                $m_id .
                "' AND `st_booking`.`status` = 'confirmed' AND DATE(booking_time) <= '" .
                $date .
                "' AND st_booking.user_id IN (" .
                implode(',', $userId) .
                ') ORDER BY st_booking.booking_time ASC LIMIT ' .
                $rowperpage .
                ' OFFSET ' .
                $offset .
                ' ';
            $booking_list = $this->user->custome_query(
                $sqlForbooking,
                'result'
            );
            if (!empty($booking_list)) {
                foreach ($booking_list as $booking) {
                    $day = date('d', strtotime($booking->booking_time));
                    $month = date('M', strtotime($booking->booking_time));
                    $time = date('H:i', strtotime($booking->booking_time));
                    $nameOfDay = date('D', strtotime($booking->booking_time));

                    $arr_days=['Mon'=>'Mo.','Tue'=>'Di.','Wed'=>'Mi.','Thu'=>'Do.','Fri'=>'Fr.','Sat'=>'Sa.','Sun'=>'So.'];
                    $arr_month=['Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mär','Apr'=>'Apr','May'=>'Mai','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Okt','Nov'=>'Nov','Dec'=>'Dez'];

                    $month = $arr_month[$month];
                    $nameOfDay = $arr_days[$nameOfDay];

                    $total_time = $booking->total_minutes . ' Min';

                    $book_detail = getselect(
                        'st_booking_detail',
                        'id,booking_id,service_id,service_name',
                        ['booking_id' => $booking->id]
                    );

                    $ser_nm = '';
                    foreach ($book_detail as $serv) {
                        $sub_name = get_subservicename($serv->service_id);
                        if ($sub_name == $serv->service_name) {
                            $ser_nm .= $serv->service_name . ',';
                        } else {
                            $ser_nm .=
                                $sub_name . ' - ' . $serv->service_name . ',';
                        }
                    }

                    $b_list .=
                        '<div class=" on-hover-div">
                    <a href="' .
                        base_url('booking/detail/') .
                        url_encode($booking->id) .
                        '">
                    <div class="inner-hover-div">
                      <div class="start-flex" style="align-self: flex-start;">
                        <span class="display-b fontsize-24 color333 pt-3">' .
                        $day .
                        '</span>
                        <span class="display-b fontsize-14 color333">' .
                        $month .
                        '</span>
                      </div>
                      <div class="" style="display: flex; flex-direction: column;flex-grow: 1;">
                        <span class="fontsize-14 color333">' .
                        $nameOfDay .
                        ', ' .
                        $time .
                        '</span>
                        <p class="color333 fontsize-16 fontfamily-medium mb-0">' .
                        rtrim($ser_nm, ',') .
                        '</p>
                        <span class="fontsize-14 color333">' .
                        $total_time .
                        '. bei ' .
                        $booking->first_name .
                        '</span>
                      </div>
                      <span class="fontsize-16 color333 fontfamily-medium">€ ' .
                        price_formate($booking->total_price) .
                        '</span>
                    </div></a>
                  </div>';
                }
                /*color: rgb(40, 131, 210); */
                if ($b_count - $offset > 10) {
                    $b_list .=
                        '<a href="javascript:void(0)" data-page="' .
                        ($page + 1) .
                        '" class="colorblue a_hover_blue font-size-14 fontfamily-medium display-b p-3 load_booking" style="margin-top:20px;color:#00b3bf;">Weitere Buchungen ansehen</a>';
                }
            }

            echo json_encode([
                'success' => 1,
                'booking_list' => $b_list,
                'b_count' => $b_count,
            ]);
        } else {
            echo json_encode([
                'success' => 0,
                'booking_list' => $b_list,
                'client_list' => $c_list,
            ]);
        }
    }

    function getall_recentbooking()
    {
        $mid = $this->session->userdata('st_userid');
        $this->db->query(
            'UPDATE st_booking SET seen_status=1 WHERE seen_status=0 AND merchant_id=' .
                $mid
        );
        $this->db->query(
            'UPDATE st_booking_notification SET view_status=1 WHERE view_status=0 AND merchant_id=' .
                $mid
        );
        $this->user->update(
            'st_review',
            ['read_status' => 'read'],
            ['merchant_id' => $mid]
        );

        $field =
            'st_booking.id,user_id,st_booking.book_id,st_booking.updated_on,st_booking.booking_time,st_booking.fullname,(SELECT first_name FROM `st_users` WHERE `st_users`.`id`=`st_booking`.`employee_id`) as emp_name,st_booking.created_on,st_booking.merchant_id,first_name,last_name,profile_pic,fullname,booking_time,booking_type,st_booking.status';
        //~ $booking=$this->user->join_two_orderby('st_booking','st_users','user_id','id',array('st_booking.merchant_id' => $mid,'st_booking.status !=' =>'confirmed','booking_type !='=>'self'),$field,'st_booking.updated_on','',5,0,'','desc','left');

        $sqlForservice =
            "SELECT `st_booking`.`id`,`st_booking`.`user_id`,`review`,`rate`,`anonymous`,`st_booking`.`updated_on`,`st_booking`.`book_id`,`st_booking`.`booking_time`,`st_booking`.`fullname`,`st_booking`.`created_on`,`st_booking`.`merchant_id`,`first_name`,`last_name`,(SELECT first_name FROM `st_users` WHERE `st_users`.`id`=`st_booking`.`employee_id`) as emp_name,`profile_pic`,`fullname`,`booking_time`,`st_review`.`created_on` as rev_date,`booking_type`,`st_booking`.`status` FROM `st_booking` LEFT JOIN `st_users` ON `st_booking`.`user_id`=`st_users`.`id` LEFT JOIN `st_review` ON `st_booking`.`id`=`st_review`.`booking_id` WHERE st_booking.merchant_id = '" .
            $mid .
            "' AND booking_type != 'self' AND st_booking.status !='deleted' AND st_booking.employee_id != -1 ORDER BY st_booking.updated_on DESC LIMIT 50";
        //AND st_booking.status !='confirmed'
        $booking = $this->user->custome_query($sqlForservice, 'result');

        $activity = '';
        $upcoming = '';
        if (!empty($booking)) {
            foreach ($booking as $book) {
                $ser_nm = '';
                $book_details = getselect(
                    'st_booking_detail',
                    'id,service_id,service_name',
                    ['booking_id' => $book->id]
                );
                if (!empty($book_details)) {
                    $countser = count($book_details);
                    $ijk = 1;
                    foreach ($book_details as $serv) {
                        $sub_name = get_subservicename($serv->service_id);
                        if ($countser == $ijk) {
                            if ($sub_name == $serv->service_name) {
                                $ser_nm .= $serv->service_name;
                            } else {
                                $ser_nm .=
                                    $sub_name . ' - ' . $serv->service_name;
                            }
                        } else {
                            if ($sub_name == $serv->service_name) {
                                $ser_nm .= $serv->service_name . ', ';
                            } else {
                                $ser_nm .=
                                    $sub_name .
                                    ' - ' .
                                    $serv->service_name .
                                    ', ';
                            }
                        }
                        $ijk++;
                    }
                }

                if ($book->status == 'completed' && $book->rate != '') {
                    if ($book->booking_type != 'guest') {
                        $name = $book->first_name . ' ' . $book->last_name;
                    } else {
                        $name = $book->fullname;
                    }

                    if (!empty($book->anonymous)) {
                        $name = 'Anonymous';
                        $bookID = '';
                    } else {
                        $bookID =
                            'ID: <a href="#" id="' .
                            url_encode($book->id) .
                            '" class="colorcyan a_hover_cyan text-underline booking_row">' .
                            $book->book_id .
                            '</a>';
                    }

                    $service_nm = rtrim($ser_nm, ',');
                    /*if(strlen($service_nm) > 25)
                     $service_nm=substr($service_nm, 0, 25).'..';*/

                    if (
                        $book->booking_type == 'user' &&
                        empty($book->anonymous)
                    ) {
                        $link = 'cursor-p editCust';
                    } else {
                        $link = '';
                    }

                    if ($book->profile_pic != '' && empty($book->anonymous)) {
                        $img = getimge_url(
                            'assets/uploads/users/' . $book->user_id . '/',
                            'icon_' . $book->profile_pic,
                            'png'
                        );
                        $imgw = getimge_url(
                            'assets/uploads/users/' . $book->user_id . '/',
                            'icon_' . $book->profile_pic,
                            'webp'
                        );
                    } else {
                        $img = base_url(
                            'assets/frontend/images/user-icon-gret.svg'
                        );
                        $imgw = base_url(
                            'assets/frontend/images/user-icon-gret.svg'
                        );
                    }

                    $rateHtml = '';
                    for ($i = 1; $i < 6; $i++) {
                        if ($i <= $book->rate) {
                            $rateHtml .=
                                "<i class='fas fa-star colororange mr-2 font-size-16'></i>";
                        } else {
                            $rateHtml .=
                                "<i class='fas fa-star colore99999940 mr-2 font-size-16'></i>";
                        }
                    }
                    //$review= nl2br($book->review);
                    if (strlen($book->review) < 50) {
                        $review = nl2br($book->review);
                    } else {
                        $review = nl2br(substr($book->review, 0, 50)) . '...';
                    }

                    $germ_month = [
                        'Jan' => 'Jan',
                        'Feb' => 'Feb',
                        'Mar' => 'Mär',
                        'Apr' => 'Apr',
                        'May' => 'Mai',
                        'Jun' => 'Jun',
                        'Jul' => 'Jul',
                        'Aug' => 'Aug',
                        'Sep' => 'Sep',
                        'Oct' => 'Okt',
                        'Nov' => 'Nov',
                        'Dec' => 'Dez',
                    ];
                    $rev_month = date('M', strtotime($book->updated_on));
                    $month = $germ_month[$rev_month];
                    $activity .=
                        '<div class="clear relative d-flex pt-3 pb-2">
							<div>							
							<picture>
							    <img class="round-new-v40 display-ib mr-3" src="' .
                        $img .
                        '">
							</picture>
							<h3 class="mt-10 mb-0 mr-3 text-center color333 font-size-20 fontfamily-semibold">' .
                        date('d', strtotime($book->updated_on)) .
                        '</h3>
							<p class="mb-0 mr-3 text-center 
							color999 font-size-14 fontfamily-regular">' .
                        $month .
                        '</p>
							</div> 
							<div class="display-ib" style="padding-right: 10px !important">
							<span class="font-size-15 fontfamily-regular">Neue Bewertung erhalten ' .
                        $bookID .
                        '</span>
							<p class="color333 font-size-15 fontfamily-semibold mb-0 ' .
                        $link .
                        '" data-id="' .
                        url_encode($book->user_id) .
                        '">' .
                        $name .
                        '</p>
							<p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0">' .
                        $this->lang->line('Employee') .
                        ' : </span>' .
                        $book->emp_name .
                        '</p>
							<p class="color999 fontfamily-regular font-size-12 mb-0 display-ib rating-p"><span class="color999 fontfamily-regular font-size-12 mb-0">' .
                        $this->lang->line('Rating') .
                        ' : </span></p>
							<a href="' .
                        base_url('merchant/rating_review') .
                        '" class="rating-box"><div class="display-ib">' .
                        $rateHtml .
                        '</div></a>
							<p class="color999 fontfamily-regular font-size-12 mb-0">' .
                        $review .
                        '</p>
							<span class="color999 fontfamily-regular font-size-12 mb-0">' .
                        $service_nm .
                        '</span><p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0">Datum : </span> ' .
                        date('d.m.Y - H:i', strtotime($book->booking_time)) .
                        ' Uhr</p>
							<p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0"> ' .
                        $this->lang->line('Rating') .
                        ' ' .
                        $this->lang->line('Received') .
                        ' : </span><br> ' .
                        date('d.M.Y - H:i', strtotime($book->updated_on)) .
                        ' Uhr</p>
							</div>
							</div>';
                } else {
                    if ($book->booking_type != 'guest') {
                        $name = $book->first_name . ' ' . $book->last_name;
                    } else {
                        $name = $book->fullname;
                    }

                    if ($book->booking_type == 'user') {
                        $link = 'cursor-p editCust';
                    } else {
                        $link = '';
                    }

                    if (
                        $book->status == 'confirmed' &&
                        $book->updated_on != $book->created_on
                    ) {
                        $status_nm = 'rescheduled';
                    } else {
                        $status_nm = str_replace(' ', '_', $book->status);
                    }
                    if ($book->profile_pic != '') {
                        $img = getimge_url(
                            'assets/uploads/users/' . $book->user_id . '/',
                            'icon_' . $book->profile_pic,
                            'png'
                        );
                        $imgw = getimge_url(
                            'assets/uploads/users/' . $book->user_id . '/',
                            'icon_' . $book->profile_pic,
                            'webp'
                        );
                    } else {
                        $img = base_url(
                            'assets/frontend/images/user-icon-gret.svg'
                        );
                        $imgw = base_url(
                            'assets/frontend/images/user-icon-gret.svg'
                        );
                    }
                    $service_nm = rtrim($ser_nm, ',');
                    /*if(strlen($service_nm) > 25)
                     $service_nm=substr($service_nm, 0, 25).'..';*/

                    $activtyImg = '';

                    if ($book->status == 'completed') {
                        $activtyImg =
                            '<img src="' .
                            base_url(
                                'assets/frontend/images/completed-booking-icon.svg'
                            ) .
                            '" width="20" height="18" class="ml-0 mt--2">';
                    } elseif (
                        $book->status == 'confirmed' &&
                        $book->updated_on != $book->created_on
                    ) {
                        $activtyImg =
                            '<img src="' .
                            base_url(
                                'assets/frontend/images/reshedule-icon.svg'
                            ) .
                            '" width="22" height="18" class="ml-0 mt--2">';
                    } elseif ($book->status == 'cancelled') {
                        $activtyImg =
                            '<img src="' .
                            base_url(
                                'assets/frontend/images/booking-cancel-icon.svg'
                            ) .
                            '" width="20" height="18" class="ml-0 mt--2">';
                    } else {
                        $activtyImg =
                            '<img src="' .
                            base_url('assets/frontend/images/checked.svg') .
                            '" width="16" height="16" class="ml-1 mt--2">';
                    }
                    // 		 $m = date("M",strtotime($book->updated_on));
                    // $month= ;
                    $germ_month = [
                        'Jan' => 'Jan',
                        'Feb' => 'Feb',
                        'Mar' => 'Mär',
                        'Apr' => 'Apr',
                        'May' => 'Mai',
                        'Jun' => 'Jun',
                        'Jul' => 'Jul',
                        'Aug' => 'Aug',
                        'Sep' => 'Sep',
                        'Oct' => 'Okt',
                        'Nov' => 'Nov',
                        'Dec' => 'Dez',
                    ];
                    $rev_month = date('M', strtotime($book->updated_on));
                    $month = $germ_month[$rev_month];
                    $activity .=
                        '<div class="clear relative d-flex pt-3 pb-2">
							 <div>
							  <picture>
							    <img class="round-new-v40 display-ib mr-3" src="' .
                        $img .
                        '">
							</picture>
							  <h3 class="mt-10 mb-0 mr-3 text-center color333 font-size-20 fontfamily-semibold">' .
                        date('d', strtotime($book->updated_on)) .
                        '</h3>
							  <p class="mb-0 mr-3 text-center color999 font-size-14 fontfamily-regular">' .
                        $month .
                        '</p>
							 </div> 
							<div class="display-ib">
							<span class="font-size-15 fontfamily-regular">' .
                        $this->lang->line('booking_' . $status_nm) .
                        ', ID : <a href="#" id="' .
                        url_encode($book->id) .
                        '" class="colorcyan a_hover_cyan text-underline booking_row"> ' .
                        $book->book_id .
                        '</a></span>
							<p class="color333 font-size-15 fontfamily-semibold mb-0 ' .
                        $link .
                        '" data-id="' .
                        url_encode($book->user_id) .
                        '">' .
                        $name .
                        $activtyImg .
                        '</p>
							<p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0">' .
                        $this->lang->line('Employee') .
                        ' : </span>' .
                        $book->emp_name .
                        '</p>
							<p class="color999 fontfamily-regular font-size-12 mb-0">' .
                        $service_nm .
                        '</p><p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0">Datum : </span> ' .
                        date('d.m.Y - H:i', strtotime($book->booking_time)) .
                        ' Uhr</p>
							<p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0"> Gebucht am : </span> ' .
                        date('d.m.Y - H:i', strtotime($book->created_on)) .
                        ' Uhr</p>
							</div>
							</div>';
                }
            }
        } else {
            $activity =
                '<p class="mt-20 mb-20">Keine Buchungsaktivitäten</p>';
        }

        $todaytime = date('Y-m-d H:i:s');
        $upcomingbooking = $this->user->join_two_orderby(
            'st_booking',
            'st_users',
            'user_id',
            'id',
            [
                'st_booking.merchant_id' => $mid,
                'st_booking.booking_time >=' => $todaytime,
                'st_booking.status' => 'confirmed',
                'booking_type !=' => 'self',
            ],
            $field,
            'st_booking.id',
            '',
            5,
            0,
            '',
            'desc',
            'left'
        );
        // print_r($upcomingbooking);die;
        if (!empty($upcomingbooking)) {
            foreach ($upcomingbooking as $book) {
                if ($book->booking_type != 'guest') {
                    $name = $book->first_name . ' ' . $book->last_name;
                } else {
                    $name = $book->fullname;
                }

                if ($book->booking_type == 'user') {
                    $link = 'cursor-p editCust';
                } else {
                    $link = '';
                }

                $activtyImg = '';

                if (
                    $book->status == 'confirmed' &&
                    $book->updated_on != $book->created_on
                ) {
                    $sttstext = 'rescheduled';
                } else {
                    $sttstext = str_replace(' ', '_', $book->status);
                }

                if ($book->status == 'completed') {
                    $activtyImg =
                        '<img src="' .
                        base_url(
                            'assets/frontend/images/completed-booking-icon.svg'
                        ) .
                        '" width="20" height="18" class="ml-0 mt--2">';
                } elseif (
                    $book->status == 'confirmed' &&
                    $book->updated_on != $book->created_on
                ) {
                    $activtyImg =
                        '<img src="' .
                        base_url('assets/frontend/images/reshedule-icon.svg') .
                        '" width="22" height="18" class="ml-0 mt--2">';
                } elseif ($book->status == 'cancelled') {
                    $activtyImg =
                        '<img src="' .
                        base_url(
                            'assets/frontend/images/booking-cancel-icon.svg'
                        ) .
                        '" width="20" height="18" class="ml-0 mt--2">';
                } else {
                    $activtyImg =
                        '<img src="' .
                        base_url('assets/frontend/images/checked.svg') .
                        '" width="16" height="16" class="ml-1 mt--2">';
                }

                $ser_nm = '';
                $book_details = getselect(
                    'st_booking_detail',
                    'id,service_id,service_name',
                    ['booking_id' => $book->id]
                );
                if (!empty($book_details)) {
                    foreach ($book_details as $serv) {
                        $sub_name = get_subservicename($serv->service_id);
                        if ($sub_name == $serv->service_name) {
                            $ser_nm .= $serv->service_name . ',';
                        } else {
                            $ser_nm .=
                                $sub_name . ' - ' . $serv->service_name . ',';
                        }
                    }
                }
                if ($book->profile_pic != '') {
                    $img = getimge_url(
                        'assets/uploads/users/' . $book->user_id . '/',
                        'icon_' . $book->profile_pic,
                        'png'
                    );
                    $imgw = getimge_url(
                        'assets/uploads/users/' . $book->user_id . '/',
                        'icon_' . $book->profile_pic,
                        'webp'
                    );
                } else {
                    $img = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                    $imgw = base_url(
                        'assets/frontend/images/user-icon-gret.svg'
                    );
                }

                $service_nm = rtrim($ser_nm, ',');
                /* if(strlen($service_nm) > 25)
                 $service_nm=substr($service_nm, 0, 25).'..';*/
                //     $m = date("M",strtotime($book->updated_on));
                //     $month= $germ_month[$m];
                $germ_month = [
                    'Jan' => 'Jan',
                    'Feb' => 'Feb',
                    'Mar' => 'Mär',
                    'Apr' => 'Apr',
                    'May' => 'Mai',
                    'Jun' => 'Jun',
                    'Jul' => 'Jul',
                    'Aug' => 'Aug',
                    'Sep' => 'Sep',
                    'Oct' => 'Okt',
                    'Nov' => 'Nov',
                    'Dec' => 'Dez',
                ];
                $rev_month = date('M', strtotime($book->updated_on));
                $month = $germ_month[$rev_month];

                $upcoming .=
                    '<div class="clear relative d-flex pt-3 pb-2">
			     <div>
			       <picture>
					<img class="round-new-v40 display-ib mr-3" src="' .
                    $img .
                    '">
				</picture>
			      <h3 class="mt-10 mb-0 mr-3 text-center color333 
				 font-size-20 fontfamily-semibold">' .
                    date('d', strtotime($book->updated_on)) .
                    '</h3>
				  <p class="mb-0 mr-3 text-center color999 font-size-14 fontfamily-regular">' .
                    $month .
                    '</p>
			     </div> 
              <div class="display-ib">
              <p class="color333 font-size-15 fontfamily-semibold mb-0 ' .
                    $link .
                    '" data-id="' .
                    url_encode($book->user_id) .
                    '">' .
                    $name .
                    $activtyImg .
                    '</p>
              <span class="font-size-15 fontfamily-regular">' .
                    $this->lang->line('booking_' . $sttstext) .
                    ', 
		    ID : <a href="#" id="' .
                    url_encode($book->id) .
                    '" class="colorcyan a_hover_cyan text-underline booking_row"> ' .
                    $book->book_id .
                    '</a></span><br/>
              <p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0">' .
                    $this->lang->line('Employee') .
                    ' : </span>' .
                    $book->emp_name .
                    '</p>
              <span class="color999 fontfamily-regular font-size-12 mb-0">' .
                    $service_nm .
                    '</span><p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0">Datum : </span> ' .
                    date('d.m.Y - H:i', strtotime($book->booking_time)) .
                    ' Uhr</p>
              <p class="color999 fontfamily-regular font-size-12 mb-0"><span class="color999 fontfamily-regular font-size-12 mb-0"> Gebucht am : </span> ' .
                    date('d.m.Y - H:i', strtotime($book->created_on)) .
                    ' Uhr</p>
              </div>
			</div>';
            }
        } else {
            $upcoming =
                '<p class="mt-20 mb-20">Keine Buchungsaktivitäten</p>';
        }

        echo json_encode([
            'success' => 1,
            'activity' => $activity,
            'upcoming' => $upcoming,
        ]);
    }

    function booking_export_tocsv($type = '')
    {
        $where = [
            'st_booking.merchant_id' => $this->session->userdata('st_userid'),
            'st_booking.booking_type !=' => 'self',
        ];

        $search = '';
        if (!empty($_GET['short'])) {
            if ($_GET['short'] == 'current_week') {
                $monday = strtotime('last monday');
                $monday =
                    date('w', $monday) == date('w')
                        ? $monday + 7 * 86400
                        : $monday;
                $sunday = strtotime(date('Y-m-d', $monday) . ' +6 days');
                $start_date = date('Y-m-d', $monday);
                $end_date = date('Y-m-d', $sunday);
            } elseif ($_GET['short'] == 'current_month') {
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-t');
            } elseif ($_GET['short'] == 'last_month') {
                $start_date = date('Y-m-01', strtotime('last month'));
                $end_date = date('Y-m-t', strtotime('last month'));
            } elseif ($_GET['short'] == 'current_year') {
                $start_date = date('Y-01-01');
                $end_date = date('Y-12-01');
            } elseif ($_GET['short'] == 'last_year') {
                $start_date = date('Y-01-01', strtotime('last year'));
                $end_date = date('Y-12-01', strtotime('last year'));
            } elseif ($_GET['short'] == 'date') {
                if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                    $date = DateTime::createFromFormat(
                        'd/m/Y',
                        $_GET['start_date']
                    );
                    $start_date = $date->format('Y-m-d');
                    $date1 = DateTime::createFromFormat(
                        'd/m/Y',
                        $_GET['end_date']
                    );
                    $end_date = $date1->format('Y-m-d');
                }
            } else {
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d');
            }
            if (!empty($start_date) && !empty($end_date)) {
                $whr = [
                    'DATE(st_booking.booking_time) >=' => $start_date,
                    'DATE(st_booking.booking_time) <=' => $end_date,
                ];
                $where = $where + $whr;
            }
        }

        if (!empty($_GET['status'])) {
            if ($_GET['status'] == 'upcoming') {
                $td = date('Y-m-d');
                $whr = [
                    'DATE(st_booking.booking_time) >=' => $td,
                    'st_booking.status' => 'confirmed',
                ];
                $where = $where + $whr;
            } elseif ($_GET['status'] == 'recent') {
                $whr = ['st_booking.status' => 'completed'];
                $where = $where + $whr;
            } elseif ($_GET['status'] == 'cancelled') {
                $whr1 =
                    '(st_booking.status="cancelled" OR st_booking.status="no_show")';
            }
        }

        if (!empty($_GET['orderby'])) {
            $ordrer = $_GET['orderby'];
        } else {
            $ordrer = 'st_booking.booking_time';
        }
        if (!empty($_GET['shortby'])) {
            $short = $_GET['shortby'];
        } else {
            $short = 'desc';
        }
        if (!empty($_GET['search'])) {
            $search = $_GET['search'];
        }

        if (!empty($whr1)) {
            $this->db->where($whr1);
        }
        $booking = $this->user->getAllbookinglist(
            $where,
            0,
            0,
            $ordrer,
            $short,
            $search
        );

        $header = [
            'Sr.No',
            'Booking Id',
            'CUSTOMER',
            'EMPLOYEE',
            'DATE',
            'TIME',
            'SERVICE',
            'PRICE',
            'DURATION',
            'STATUS',
        ];
        $delimiter = ',';
        if ($type == 'excel') {
            $filename = 'excel_report' . time() . '.xls';
        } else {
            $filename = 'csv_report' . time() . '.csv';
        }

        header('Content-Description: File Transfer');
        header("Content-Disposition: attachment; filename=$filename");
        /*if($type=='excel')
			header("Content-Type: application/vnd.ms-excel");
		 else 
		    header("Content-Type: application/csv;");*/

        if ($type == 'excel') {
            $object = new PHPExcel();
            $object->setActiveSheetIndex(0);
            $table_columns = [
                'Sr.No',
                'Booking Id',
                'CUSTOMER',
                'EMPLOYEE',
                'DATE',
                'TIME',
                'SERVICE',
                'PRICE',
                'DURATION',
                'STATUS',
            ];
            $column = 0;
            //$object->getActiveSheet()->setCellValueByColumnAndRow($column, 0, '');

            foreach ($table_columns as $field) {
                $object
                    ->getActiveSheet()
                    ->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }
            if (!empty($booking)) {
                $excel_row = 2;
                $i = 1;
                foreach ($booking as $row) {
                    $time = new DateTime($row->booking_time);
                    $date = $time->format('d.m.Y');
                    $time = $time->format('H:i');

                    if ($row->booking_type == 'guest') {
                        $us_name = $row->fullname;
                    } else {
                        $us_name = $row->first_name . ' ' . $row->last_name;
                    }

                    $book_detail = $this->user->select(
                        'st_booking_detail',
                        'id,booking_id,service_id,service_name',
                        ['booking_id' => $row->id],
                        '',
                        'id',
                        'ASC'
                    );
                    $ser_nm = '';

                    foreach ($book_detail as $serv) {
                        $sub_name = get_subservicename($serv->service_id);
                        if ($sub_name == $serv->service_name) {
                            $ser_nm .= $serv->service_name . ',';
                        } else {
                            $ser_nm .=
                                $sub_name . ' - ' . $serv->service_name . ',';
                        }
                    }

                    $s_names = rtrim($ser_nm, ',');
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(0, $excel_row, $i++);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            1,
                            $excel_row,
                            $row->book_id
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(2, $excel_row, $us_name);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            3,
                            $excel_row,
                            $row->emp_fn
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(4, $excel_row, $date);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(5, $excel_row, $time);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(6, $excel_row, $s_names);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            7,
                            $excel_row,
                            $row->total_price
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            8,
                            $excel_row,
                            $row->total_minutes . ' Mins'
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            9,
                            $excel_row,
                            ucfirst($row->status)
                        );
                    $excel_row++;
                }
            }

            $object_writer = PHPExcel_IOFactory::createWriter(
                $object,
                'Excel2007'
            );
            header('Content-Type: application/vnd.ms-excel');
            header(
                'Content-Disposition: attachment;filename="excel_report-' .
                    time() .
                    '.xlsx"'
            );
            $object_writer->save('php://output');
        } else {
            header('Content-Type: application/csv;');
            // file creation
            $file = fopen('php://output', 'w');

            fputcsv($file, $header);
            $i = 1;
            if (!empty($booking)) {
                foreach ($booking as $row) {
                    $time = new DateTime($row->booking_time);
                    $date = $time->format('d.m.Y');
                    $time = $time->format('H:i');

                    if ($row->booking_type == 'guest') {
                        $us_name = $row->fullname;
                    } else {
                        $us_name = $row->first_name . ' ' . $row->last_name;
                    }

                    $book_detail = $this->user->select(
                        'st_booking_detail',
                        'id,booking_id,service_id,service_name',
                        ['booking_id' => $row->id],
                        '',
                        'id',
                        'ASC'
                    );
                    $ser_nm = '';

                    foreach ($book_detail as $serv) {
                        $sub_name = get_subservicename($serv->service_id);
                        if ($sub_name == $serv->service_name) {
                            $ser_nm .= $serv->service_name . ',';
                        } else {
                            $ser_nm .=
                                $sub_name . ' - ' . $serv->service_name . ',';
                        }
                    }

                    $s_names = rtrim($ser_nm, ',');
                    $data = [
                        $i,
                        $row->book_id,
                        $us_name,
                        $row->emp_fn,
                        $date,
                        $time,
                        $s_names,
                        $row->total_price,
                        $row->total_minutes . ' Mins',
                        ucfirst($row->status),
                    ];
                    fputcsv($file, $data);
                    $i++;
                }
            }

            fclose($file);
            exit();
        }

        //print_r($booking);
    }

    //**** Get Booking List for chart ****//
    public function getpaymenttype_ratio()
    {
        $where = '';
        $mid = $this->session->userdata('st_userid');

        if ($_POST['filter'] == 'day') {
            $today = date('Y-m-d 23:59:59');
            $lastday = date('Y-m-d 00:00:00');
        } elseif ($_POST['filter'] == 'current_week') {
            $monday = strtotime('last monday');
            $today = date('Y-m-d 23:59:59', strtotime('+1 week', $monday));
            $lastday = date('Y-m-d 00:00:00', $monday);
        } elseif ($_POST['filter'] == 'current_month') {
            $today = date('Y-m-t 23:59:59');
            $lastday = date('Y-m-01 00:00:00');
        } elseif ($_POST['filter'] == 'current_year') {
            $today = date('Y-12-t 23:59:59');
            $lastday = date('Y-01-01 00:00:00');
        } elseif ($_POST['filter'] == 'last_month') {
            $today = date('Y-m-t 23:59:59', strtotime('last month'));
            $lastday = date('Y-m-01 00:00:00', strtotime('last month'));
        } elseif ($_POST['filter'] == 'last_year') {
            $today = date('Y-12-31 23:59:59', strtotime('last year'));
            $lastday = date('Y-01-01 00:00:00', strtotime('last year'));
        } elseif ($_POST['filter'] == 'last_seven_day') {
            $today = date('Y-m-d 23:59:59');
            $lastday = date('Y-m-d', strtotime('-6 days'));
        }

        if (!empty($today) && !empty($lastday)) {
            $where =
                " AND st_invoices.created_on <= '" .
                $today .
                "' AND st_invoices.created_on >= '" .
                $lastday .
                "'";
        }

        $sql5 =
            "SELECT id,(SELECT count(st_invoices.id) FROM st_invoices JOIN `st_booking` ON `st_booking`.`id`=`st_invoices`.`booking_id` WHERE merchant_id='" .
            $mid .
            "' AND payment_type='card' " .
            $where .
            ") as card,(SELECT count(st_invoices.id) FROM st_invoices JOIN `st_booking` ON `st_booking`.`id`=`st_invoices`.`booking_id` WHERE merchant_id='" .
            $mid .
            "' AND payment_type='other' " .
            $where .
            ") as other,(SELECT count(st_invoices.id) FROM st_invoices JOIN `st_booking` ON `st_booking`.`id`=`st_invoices`.`booking_id` WHERE merchant_id='" .
            $mid .
            "' AND payment_type='cash' " .
            $where .
            ") as cash,(SELECT count(st_invoices.id) FROM st_invoices JOIN `st_booking` ON `st_booking`.`id`=`st_invoices`.`booking_id` WHERE merchant_id='" .
            $mid .
            "' AND payment_type='voucher' " .
            $where .
            ") as voucher,(SELECT count(st_invoices.id) FROM st_invoices JOIN `st_booking` ON `st_booking`.`id`=`st_invoices`.`booking_id` WHERE merchant_id='" .
            $mid .
            "' " .
            $where .
            ') as total_paytype FROM st_invoices';

        $pay_types = $this->user->custome_query($sql5, 'row');
        // print_r($pay_types);
        $card = $cash = $other = $voucher = $card_p = $cash_p = $oth_p = $vouch_p = 0;
        if (!empty($pay_types->total_paytype)) {
            $card_p = round(
                ($pay_types->card * 100) / $pay_types->total_paytype
            );
            $cash_p = round(
                ($pay_types->cash * 100) / $pay_types->total_paytype
            );
            $oth_p = round(
                ($pay_types->other * 100) / $pay_types->total_paytype
            );
            $vouch_p = round(
                ($pay_types->voucher * 100) / $pay_types->total_paytype
            );
            $card = $pay_types->card;
            $cash = $pay_types->cash;
            $other = $pay_types->other;
            $voucher = $pay_types->voucher;
        }

        //echo json_encode($data,JSON_NUMERIC_CHECK);
        echo json_encode([
            'card' => $card,
            'cash' => $cash,
            'other' => $other,
            'voucher' => $voucher,
            'card_p' => $card_p,
            'cash_p' => $cash_p,
            'other_p' => $oth_p,
            'vouch_p' => $vouch_p,
        ]);
        die();
    }

    function test_cookies()
    {
        extract($_POST);
        setcookie('side_nav_status', $status, time() + 86400 * 30, '/');
        echo $status;
    }

    function client_report_export_tocsv($type = '')
    {
        $mid = $this->session->userdata('st_userid');
        $search = '';
        $whr = '';
        if (!empty($_GET['filter'])) {
            if ($_GET['filter'] == 'day') {
                $end_date = date('Y-m-d 23:59:59');
                $start_date = date('Y-m-d 00:00:00');
            } elseif ($_GET['filter'] == 'current_week') {
                $monday = strtotime('last monday');
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime('+1 week', $monday)
                );
                $start_date = date('Y-m-d 00:00:00', $monday);
            } elseif ($_GET['filter'] == 'current_month') {
                $end_date = date('Y-m-t 23:59:59');
                $start_date = date('Y-m-01 00:00:00');
            } elseif ($_GET['filter'] == 'current_year') {
                $end_date = date('Y-12-t 23:59:59');
                $start_date = date('Y-01-01 00:00:00');
            } elseif ($_GET['filter'] == 'last_month') {
                $end_date = date('Y-m-t 23:59:59', strtotime('last month'));
                $start_date = date('Y-m-01 00:00:00', strtotime('last month'));
            } elseif ($_GET['filter'] == 'last_year') {
                $end_date = date('Y-12-31 23:59:59', strtotime('last year'));
                $start_date = date('Y-01-01 00:00:00', strtotime('last year'));
            } elseif (
                $_GET['filter'] == 'date' &&
                $_GET['startdate'] != '' &&
                $_GET['enddate'] != ''
            ) {
                $start_date = date(
                    'Y-m-d 00:00:00',
                    strtotime($_GET['startdate'])
                );
                $end_date = date('Y-m-d 23:59:59', strtotime($_GET['enddate']));
            }
            $whr =
                "AND date(st_booking.updated_on) >= '" .
                $start_date .
                "' AND date(st_booking.updated_on) <= '" .
                $end_date .
                "'";
            //booking_time
        }
        if (!empty($_GET['search'])) {
            $search = $_GET['search'];
        }
        $where = ['st_booking.merchant_id' => $mid];
        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        } else {
            $order = '';
        }

        $field =
            'st_users.id,first_name,last_name,profile_pic,(SELECT count(id) FROM st_booking WHERE user_id=st_users.id AND merchant_id="' .
            $mid .
            '" AND status ="completed" ' .
            $whr .
            ') as complete,(SELECT count(id) FROM st_booking WHERE user_id=st_users.id AND merchant_id="' .
            $mid .
            '" AND status ="confirmed" ' .
            $whr .
            ') as confirm,(SELECT count(id) FROM st_booking WHERE user_id=st_users.id AND merchant_id="' .
            $mid .
            '" AND (status ="cancelled" OR status ="no show") ' .
            $whr .
            ') as cancel,(SELECT count(id) FROM st_booking WHERE user_id=st_users.id AND merchant_id="' .
            $mid .
            '" ' .
            $whr .
            ') as total,IFNULL((SELECT SUM(total_price) FROM st_booking WHERE user_id=st_users.id AND merchant_id="' .
            $mid .
            '" AND status ="completed" ' .
            $whr .
            '),0) as revenue';
        $customer = $this->user->join_two_orderby_report(
            'st_users',
            'st_booking',
            'id',
            'user_id',
            $where,
            $field,
            $order,
            'st_booking.user_id',
            0,
            0,
            $search
        );

        $header = [
            'Sr.No',
            'CLIENT NAME',
            'COMPLETED',
            'CONFIRMED',
            'CANCELLED',
            'TOTAL BOOKING',
            'REVENUE',
        ];
        $delimiter = ',';
        if ($type == 'excel') {
            $filename = 'excel_clientreport' . time() . '.xls';
        } else {
            $filename = 'csv_clientreport' . time() . '.csv';
        }

        header('Content-Description: File Transfer');
        header("Content-Disposition: attachment; filename=$filename");
        /*if($type=='excel')
			header("Content-Type: application/vnd.ms-excel");
		 else 
		    header("Content-Type: application/csv;");*/

        if ($type == 'excel') {
            $object = new PHPExcel();
            $object->setActiveSheetIndex(0);
            $table_columns = [
                'Sr.No',
                'CLIENT NAME',
                'COMPLETED',
                'CONFIRMED',
                'CANCELLED',
                'TOTAL BOOKING',
                'REVENUE',
            ];
            $column = 0;
            //$object->getActiveSheet()->setCellValueByColumnAndRow($column, 0, '');

            foreach ($table_columns as $field) {
                $object
                    ->getActiveSheet()
                    ->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }
            if (!empty($customer)) {
                $excel_row = 2;
                $i = 1;
                foreach ($customer as $row) {
                    $total =
                        (int) $row->complete +
                        (int) $row->confirm +
                        (int) $row->cancel;
                    $book_detail = $this->user->select(
                        'st_booking_detail',
                        'id,booking_id,service_id,service_name',
                        ['booking_id' => $row->id],
                        '',
                        'id',
                        'ASC'
                    );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(0, $excel_row, $i++);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            1,
                            $excel_row,
                            $row->first_name . ' ' . $row->last_name
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            2,
                            $excel_row,
                            $row->complete
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            3,
                            $excel_row,
                            $row->confirm
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            4,
                            $excel_row,
                            $row->cancel
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(5, $excel_row, $total);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            6,
                            $excel_row,
                            price_formate($row->revenue) . ' €'
                        );
                    $excel_row++;
                }
            }

            $object_writer = PHPExcel_IOFactory::createWriter(
                $object,
                'Excel2007'
            );
            header('Content-Type: application/vnd.ms-excel');
            header(
                'Content-Disposition: attachment;filename="excel_report-' .
                    time() .
                    '.xlsx"'
            );
            $object_writer->save('php://output');
        } else {
            header('Content-Type: application/csv;');
            // file creation
            $file = fopen('php://output', 'w');

            fputcsv($file, $header);
            $i = 1;
            if (!empty($customer)) {
                foreach ($customer as $row) {
                    $total =
                        (int) $row->complete +
                        (int) $row->confirm +
                        (int) $row->cancel;
                    $data = [
                        $i,
                        $row->first_name . ' ' . $row->last_name,
                        $row->complete,
                        $row->confirm,
                        $row->cancel,
                        $total,
                        price_formate($row->revenue),
                    ];
                    fputcsv($file, $data);
                    $i++;
                }
            }

            fclose($file);
            exit();
        }

        //print_r($booking);
    }

    function staff_report_export_tocsv($type = '')
    {
        $mid = $this->session->userdata('st_userid');
        $search = '';
        $whr = '';
        $tipWher = '';
        if (!empty($_GET['filter'])) {
            if ($_GET['filter'] == 'day') {
                $end_date = date('Y-m-d 23:59:59');
                $start_date = date('Y-m-d 00:00:00');
            } elseif ($_GET['filter'] == 'current_week') {
                $monday = strtotime('last monday');
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime('+1 week', $monday)
                );
                $start_date = date('Y-m-d 00:00:00', $monday);
            } elseif ($_GET['filter'] == 'current_month') {
                $end_date = date('Y-m-t 23:59:59');
                $start_date = date('Y-m-01 00:00:00');
            } elseif ($_GET['filter'] == 'current_year') {
                $end_date = date('Y-12-t 23:59:59');
                $start_date = date('Y-01-01 00:00:00');
            } elseif ($_GET['filter'] == 'last_month') {
                $end_date = date('Y-m-t 23:59:59', strtotime('last month'));
                $start_date = date('Y-m-01 00:00:00', strtotime('last month'));
            } elseif ($_GET['filter'] == 'last_year') {
                $end_date = date('Y-12-31 23:59:59', strtotime('last year'));
                $start_date = date('Y-01-01 00:00:00', strtotime('last year'));
            } elseif (
                $_GET['filter'] == 'date' &&
                $_GET['startdate'] != '' &&
                $_POST['enddate'] != ''
            ) {
                $start_date = date(
                    'Y-m-d 00:00:00',
                    strtotime($_GET['startdate'])
                );
                $end_date = date('Y-m-d 23:59:59', strtotime($_GET['enddate']));
            }
            $whr =
                "AND date(st_booking.updated_on) >= '" .
                $start_date .
                "' AND date(st_booking.updated_on) <= '" .
                $end_date .
                "' AND booking_type!='self'";
            $tipWher =
                "AND date(created_on) >= '" .
                $start_date .
                "' AND date(created_on) <= '" .
                $end_date .
                "'";
            //booking_time
        }
        if ($_GET['status'] == 'active') {
            $wh = ['merchant_id' => $mid, 'status !=' => 'deleted'];
            $where = [
                'st_users.merchant_id' => $mid,
                'st_users.status !=' => 'deleted',
            ];
        } else {
            $wh = ['merchant_id' => $mid];
            $where = ['st_users.merchant_id' => $mid];
        }

        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        } else {
            $order = '';
        }

        $field =
            'st_users.id,first_name,last_name,profile_pic,st_users.status,IFNULL((SELECT SUM(tip) FROM st_invoices WHERE emp_id=st_users.id ' .
            $tipWher .
            '),0) as totalTip,(SELECT count(id) FROM st_booking WHERE employee_id=st_users.id AND merchant_id="' .
            $mid .
            '" AND status ="completed" ' .
            $whr .
            ') as complete,(SELECT count(id) FROM st_booking WHERE employee_id=st_users.id AND booking_type!="self" AND merchant_id="' .
            $mid .
            '" AND status ="confirmed" ' .
            $whr .
            ') as confirm,(SELECT count(id) FROM st_booking WHERE employee_id=st_users.id AND merchant_id="' .
            $mid .
            '" AND (status ="cancelled" OR status ="no show") ' .
            $whr .
            ') as cancel,(SELECT count(id) FROM st_booking WHERE employee_id=st_users.id AND merchant_id="' .
            $mid .
            '" ' .
            $whr .
            ') as total,IFNULL((SELECT SUM(total_price) FROM st_booking WHERE employee_id=st_users.id AND merchant_id="' .
            $mid .
            '" AND status ="completed" ' .
            $whr .
            '),0) as revenue,IFNULL((SELECT SUM(emp_commission) FROM st_booking WHERE employee_id=st_users.id AND merchant_id="' .
            $mid .
            '" AND status ="completed" ' .
            $whr .
            '),0) as commission';
        $customer = $this->user->join_two_orderby_report(
            'st_users',
            'st_booking',
            'id',
            'employee_id',
            $where,
            $field,
            $order,
            'st_booking.employee_id',
            0,
            0,
            $search
        );

        $header = [
            'Sr.No',
            'EMPLOYEE NAME',
            'COMPLETED',
            'CONFIRMED',
            'CANCELLED',
            'TOTAL BOOKING',
            'REVENUE',
            'TIPS',
            'COMMISSION',
        ];
        $delimiter = ',';
        if ($type == 'excel') {
            $filename = 'excel_staffreport' . time() . '.xls';
        } else {
            $filename = 'csv_staffreport' . time() . '.csv';
        }

        header('Content-Description: File Transfer');
        header("Content-Disposition: attachment; filename=$filename");
        /*if($type=='excel')
			header("Content-Type: application/vnd.ms-excel");
		 else 
		    header("Content-Type: application/csv;");*/

        if ($type == 'excel') {
            $object = new PHPExcel();
            $object->setActiveSheetIndex(0);
            $table_columns = [
                'Sr.No',
                'EMPLOYEE NAME',
                'COMPLETED',
                'CONFIRMED',
                'CANCELLED',
                'TOTAL BOOKING',
                'REVENUE',
                'TIPS',
                'COMMISSION',
            ];
            $column = 0;
            //$object->getActiveSheet()->setCellValueByColumnAndRow($column, 0, '');

            foreach ($table_columns as $field) {
                $object
                    ->getActiveSheet()
                    ->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }
            if (!empty($customer)) {
                $excel_row = 2;
                $i = 1;
                $comp = $conf = $canc = $tots = $revs = $tips = $comss = $ij = 0;
                $rec_total = count($customer);
                foreach ($customer as $row) {
                    if ($row->revenue != 0) {
                        $bk_total = $row->revenue;
                    }
                    //-$row->totalTip;
                    else {
                        $bk_total = 0;
                    }
                    $total =
                        (int) $row->complete +
                        (int) $row->confirm +
                        (int) $row->cancel;
                    $comp = $comp + $row->complete;
                    $conf = $conf + $row->confirm;
                    $canc = $canc + $row->cancel;
                    $tots = $tots + $total;
                    $revs = $revs + $row->revenue;
                    $tips = $tips + $row->totalTip;
                    $comss = $comss + round($row->commission, 2);

                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(0, $excel_row, $i++);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            1,
                            $excel_row,
                            $row->first_name . ' ' . $row->last_name
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            2,
                            $excel_row,
                            $row->complete
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            3,
                            $excel_row,
                            $row->confirm
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            4,
                            $excel_row,
                            $row->cancel
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(5, $excel_row, $total);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            6,
                            $excel_row,
                            price_formate($bk_total) . ' €'
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            7,
                            $excel_row,
                            price_formate($row->totalTip) . ' €'
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            8,
                            $excel_row,
                            price_formate($row->commission) . ' €'
                        );
                    $excel_row++;
                    $ij++;
                }
                if ($rec_total == $ij) {
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(0, $excel_row, '');
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(1, $excel_row, 'TOTAL');
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(2, $excel_row, $comp);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(3, $excel_row, $conf);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(4, $excel_row, $canc);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(5, $excel_row, $tots);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            6,
                            $excel_row,
                            price_formate($revs) . ' €'
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            7,
                            $excel_row,
                            price_formate($tips) . ' €'
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            8,
                            $excel_row,
                            price_formate($comss) . ' €'
                        );
                }
            }

            $object_writer = PHPExcel_IOFactory::createWriter(
                $object,
                'Excel2007'
            );
            header('Content-Type: application/vnd.ms-excel');
            header(
                'Content-Disposition: attachment;filename="excel_report-' .
                    time() .
                    '.xlsx"'
            );
            $object_writer->save('php://output');
        } else {
            header('Content-Type: application/csv;');
            // file creation
            $file = fopen('php://output', 'w');

            fputcsv($file, $header);
            $i = 1;
            if (!empty($customer)) {
                $comp = $conf = $canc = $tots = $revs = $tips = $comss = $ij = 0;
                $rec_total = count($customer);

                foreach ($customer as $row) {
                    if ($row->revenue != 0) {
                        $bk_total = $row->revenue - $row->totalTip;
                    } else {
                        $bk_total = 0;
                    }
                    $total =
                        (int) $row->complete +
                        (int) $row->confirm +
                        (int) $row->cancel;
                    $comp = $comp + $row->complete;
                    $conf = $conf + $row->confirm;
                    $canc = $canc + $row->cancel;
                    $tots = $tots + $total;
                    $revs = $revs + $row->revenue;
                    $tips = $tips + $row->totalTip;
                    $comss = $comss + round($row->commission, 2);

                    $data = [
                        $i,
                        $row->first_name . ' ' . $row->last_name,
                        $row->complete,
                        $row->confirm,
                        $row->cancel,
                        $total,
                        price_formate($bk_total),
                        price_formate($row->totalTip),
                        price_formate($row->commission),
                    ];
                    fputcsv($file, $data);
                    $i++;
                    $ij++;
                }
                if ($rec_total == $ij) {
                    $data = [
                        '',
                        'TOTAL',
                        $comp,
                        $conf,
                        $canc,
                        $tots,
                        price_formate($revs),
                        price_formate($tips),
                        price_formate($comss),
                    ];
                    fputcsv($file, $data);
                }
            }

            fclose($file);
            exit();
        }

        //print_r($booking);
    }

    function service_report_export_tocsv($type = '')
    {
        $mid = $this->session->userdata('st_userid');
        $search = '';
        $where = '';
        $whr = '';
        if (!empty($_GET['filter'])) {
            if ($_GET['filter'] == 'day') {
                $end_date = date('Y-m-d 23:59:59');
                $start_date = date('Y-m-d 00:00:00');
            } elseif ($_GET['filter'] == 'current_week') {
                $monday = strtotime('last monday');
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime('+1 week', $monday)
                );
                $start_date = date('Y-m-d 00:00:00', $monday);
            } elseif ($_GET['filter'] == 'current_month') {
                $end_date = date('Y-m-t 23:59:59');
                $start_date = date('Y-m-01 00:00:00');
            } elseif ($_GET['filter'] == 'current_year') {
                $end_date = date('Y-12-t 23:59:59');
                $start_date = date('Y-01-01 00:00:00');
            } elseif ($_GET['filter'] == 'last_month') {
                $end_date = date('Y-m-t 23:59:59', strtotime('last month'));
                $start_date = date('Y-m-01 00:00:00', strtotime('last month'));
            } elseif ($_GET['filter'] == 'last_year') {
                $end_date = date('Y-12-31 23:59:59', strtotime('last year'));
                $start_date = date('Y-01-01 00:00:00', strtotime('last year'));
            } elseif (
                $_GET['filter'] == 'date' &&
                $_POST['startdate'] != '' &&
                $_POST['enddate'] != ''
            ) {
                $start_date = date(
                    'Y-m-d 00:00:00',
                    strtotime($_GET['startdate'])
                );
                $end_date = date(
                    'Y-m-d 23:59:59',
                    strtotime($_POST['enddate'])
                );
            }

            $whr =
                "AND st_booking.updated_on >= '" .
                $start_date .
                "' AND st_booking.updated_on <= '" .
                $end_date .
                "'";
        }

        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        } else {
            $order = 'id asc';
        }

        $all_service = $this->user->select_custome_orderBy(
            'st_merchant_category',
            'id,name,(SELECT category_name from st_category where st_category.id=st_merchant_category.subcategory_id) as cat_name,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking.status="confirmed" AND st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ') as confirm,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking.status="completed" AND st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ' ) as complete,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE (st_booking.status="cancelled" OR st_booking.status ="no show") AND st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ') as cancel,(SELECT count(st_booking_detail.id) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking_detail.service_id=st_merchant_category.id ' .
                $whr .
                ') as total,IFNULL((SELECT SUM(`st_booking_detail`.`price`) FROM st_booking_detail JOIN st_booking ON st_booking.id=st_booking_detail.booking_id WHERE st_booking_detail.service_id=st_merchant_category.id AND st_booking.status="completed" ' .
                $whr .
                '),0) as revenue',
            ['created_by' => $mid, 'status' => 'active'],
            '',
            $order,
            0,
            0
        );

        $header = [
            'Sr.No',
            'SERVICE NAME',
            'COMPLETED',
            'CANCELLED',
            'TOTAL BOOKING',
            'VALUE OF SERVICE',
        ];
        $delimiter = ',';
        if ($type == 'excel') {
            $filename = 'excel_servicereport' . time() . '.xls';
        } else {
            $filename = 'csv_servicereport' . time() . '.csv';
        }

        header('Content-Description: File Transfer');
        header("Content-Disposition: attachment; filename=$filename");
        /*if($type=='excel')
			header("Content-Type: application/vnd.ms-excel");
		 else 
		    header("Content-Type: application/csv;");*/

        if ($type == 'excel') {
            $object = new PHPExcel();
            $object->setActiveSheetIndex(0);
            $table_columns = [
                'Sr.No',
                'SERVICE NAME',
                'COMPLETED',
                'CANCELLED',
                'TOTAL BOOKING',
                'VALUE OF SERVICE',
            ];
            $column = 0;
            //$object->getActiveSheet()->setCellValueByColumnAndRow($column, 0, '');

            foreach ($table_columns as $field) {
                $object
                    ->getActiveSheet()
                    ->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }
            if (!empty($all_service)) {
                $excel_row = 2;
                $i = 1;
                $ij = 0;
                $rec_total = count($all_service);
                $comp = $canc = $totval = $revs = 0;
                foreach ($all_service as $row) {
                    if ($row->name != '') {
                        $s_name = $row->cat_name . ' - ' . $row->name;
                    } else {
                        $s_name = $row->cat_name;
                    }

                    $total_s = $row->complete + $row->confirm + $row->cancel;
                    $comp = $comp + $row->complete;
                    $canc = $canc + $row->cancel;
                    $totval = $totval + $total_s;
                    $revs = $revs + $row->revenue;

                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(0, $excel_row, $i++);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(1, $excel_row, $s_name);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            2,
                            $excel_row,
                            $row->complete
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            3,
                            $excel_row,
                            $row->cancel
                        );
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(4, $excel_row, $total_s);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            5,
                            $excel_row,
                            price_formate($row->revenue) . ' €'
                        );
                    $excel_row++;

                    $ij++;
                }

                if ($rec_total == $ij) {
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(0, $excel_row, '');
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(1, $excel_row, 'TOTAL');
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(2, $excel_row, $comp);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(3, $excel_row, $canc);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(4, $excel_row, $totval);
                    $object
                        ->getActiveSheet()
                        ->setCellValueByColumnAndRow(
                            5,
                            $excel_row,
                            price_formate($revs) . ' €'
                        );
                }
            }

            $object_writer = PHPExcel_IOFactory::createWriter(
                $object,
                'Excel2007'
            );
            header('Content-Type: application/vnd.ms-excel');
            header(
                'Content-Disposition: attachment;filename="excel_report-' .
                    time() .
                    '.xlsx"'
            );
            $object_writer->save('php://output');
        } else {
            header('Content-Type: application/csv;');
            // file creation
            $file = fopen('php://output', 'w');

            fputcsv($file, $header);
            $i = 1;
            if (!empty($all_service)) {
                $ij = 0;
                $rec_total = count($all_service);
                $comp = $canc = $totval = $revs = 0;
                foreach ($all_service as $row) {
                    if ($row->name != '') {
                        $s_name = $row->cat_name . ' - ' . $row->name;
                    } else {
                        $s_name = $row->cat_name;
                    }

                    $total_s = $row->complete + $row->confirm + $row->cancel;
                    $comp = $comp + $row->complete;
                    $canc = $canc + $row->cancel;
                    $totval = $totval + $total_s;
                    $revs = $revs + $row->revenue;
                    $data = [
                        $i,
                        $s_name,
                        $row->complete,
                        $row->cancel,
                        $total_s,
                        price_formate($row->revenue),
                    ];

                    fputcsv($file, $data);
                    $i++;
                    $ij++;
                }
                if ($rec_total == $ij) {
                    $data1 = [
                        '',
                        'TOTAL',
                        $comp,
                        $canc,
                        $totval,
                        price_formate($revs),
                    ];
                    fputcsv($file, $data1);
                }
            }

            fclose($file);
            exit();
        }

        //print_r($booking);
    }

    public function delete_single_customer()
    {
        $id = url_decode($_POST['id']);
        $mid = $this->session->userdata('st_userid');
        $where = ['user_id' => $id, 'merchant_id' => $mid];
        $whr = ['user_id' => $id, 'mer_id' => $mid];
        $select =
            'SELECT GROUP_CONCAT(id) as m_id FROM `st_booking` WHERE user_id="' .
            $id .
            '" AND merchant_id="' .
            $mid .
            '" ';
        $all_id = $this->user->custome_query($select, 'row');
        if (!empty($all_id->m_id)) {
            $where1 = 'booking_id IN(' . $all_id->m_id . ')';
            $this->db->delete('st_invoices', $where1);
        }

        if ($this->db->delete('st_booking_detail', $whr)) {
            $this->db->delete('st_review', $where);
            $this->db->delete('st_booking', $where);
            //$this->db->delete('st_booking_detail', $whr);
            echo true;
        }
    }

    function employee($id = '')
    {
        $_SESSION['frm_emp'] = $id;
        redirect(base_url('merchant/employee_listing'));
    }

    public function edit_marchantprofile_report()
    {
        $postdays = $_POST['days'];
        extract($_POST);
        $insertArrdata = [];
        $insertArrdata['first_name'] = $first_name;
        $insertArrdata['last_name'] = $last_name;
        $insertArrdata['mobile'] = $telephone;
        $insertArrdata['calender_color'] = '#' . $calender_color;
        $insertArrdata['online_booking'] = isset($chk_online) ? 1 : 0;
        $insertArrdata['updated_on'] = date('Y-m-d H:i:s');
        $insertArrdata['updated_by'] = $this->session->userdata('st_userid');
        $eid = url_decode($_POST['empid']);

        if (isset($commission_check)) {
            $insertArrdata['commission'] = $commission;
        } else {
            $insertArrdata['commission'] = 0;
        }

        $upload_path = 'assets/uploads/employee/' . $eid . '/';
        $filepath =
            'assets/uploads/profile_temp/' .
            $this->session->userdata('st_userid') .
            '/';

        @mkdir($upload_path, 0777, true);
        $filepath2 = $upload_path;

        $images = scandir($filepath);
        // echo "<pre>"; print_r($images); die;
        $nimages = '';
        $InserData = [];

        for ($i = 2; $i < count($images); $i++) {
            if (file_exists($filepath . $images[$i])) {
                // file_exists($filepath.$images[$i]);
                rename($filepath . $images[$i], $filepath2 . $images[$i]);
                $nimages = $images[$i];
            }
        }
        $empimgs = '';
        if (!empty($nimages)) {
            $insertArrdata['profile_pic'] = $nimages;
            $this->image_moo
                ->load($filepath2 . $nimages)
                ->resize(250, 250)
                ->save($filepath2 . 'thumb_' . $nimages, true);

            // resize with slider resolution
            $this->image_moo
                ->load($filepath2 . $nimages)
                ->resize(115, 115)
                ->save($filepath2 . 'icon_' . $nimages, true);
            $empimgs = 'icon_' . $nimages;
        }
        $pathck = $upload_path;
        //var_dump($nimages); die;
        if (
            !empty($_POST['old_img']) &&
            !empty($nimages) &&
            file_exists($pathck . $_POST['old_img'])
        ) {
            $del_file = $pathck . $_POST['old_img'];
            unlink($del_file);
            if (file_exists($pathck . 'icon_' . $_POST['old_img'])) {
                unlink($pathck . 'icon_' . $_POST['old_img']);
            }
            if (file_exists($pathck . 'thumb_' . $_POST['old_img'])) {
                unlink($pathck . 'thumb_' . $_POST['old_img']);
            }
        }

        $old_service = [];
        if (!empty($old_assined_service)) {
            $old_service = explode(',', $old_assined_service);
        }
        if (!empty($assigned_service)) {
            $i = 0;
            $assigned_service = explode(',', $assigned_service);
            $assigned_subcat = explode(',', $all_subcat);
            foreach ($assigned_service as $service) {
                $assin = [];
                $assin['user_id'] = $eid;
                $assin['service_id'] = $service;
                $assin['subcat_id'] = $assigned_subcat[$i];
                if (in_array($service, $old_service)) {
                    if (
                        ($key = array_search($service, $old_service)) !== false
                    ) {
                        unset($old_service[$key]);
                    }
                } else {
                    $res_id = $this->user->insert(
                        'st_service_employee_relation',
                        $assin
                    );
                }
                $i++;
            }
        }
        if (!empty($old_service)) {
            $where =
                'user_id=' .
                $eid .
                ' AND service_id IN(' .
                implode(',', $old_service) .
                ')';
            $this->db->delete('st_service_employee_relation', $where);
        }
        /*********************************************************Start Time**********************************************************************/
        $days_array = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];
        $postdays = $_POST['days'];
        //$i=0;
        /*print_r($_POST);
         die;*/
        foreach ($days_array as $day) {
            //echo $poststart[$day];

            $daydata = $this->user->select_row('st_availability', 'id', [
                'user_id' => $eid,
                'days' => $day,
            ]);
            if (empty($daydata)) {
                if (in_array($day, $postdays)) {
                    $insertArr = [];
                    $insertArr['user_id'] = $eid;
                    $insertArr['days'] = $day;
                    $insertArr['type'] = 'open';
                    $insertArr['starttime'] = $_POST[$day . '_start'];
                    $insertArr['endtime'] = $_POST[$day . '_end'];
                    $insertArr['starttime_two'] = isset(
                        $_POST[$day . '_start_two']
                    )
                        ? $_POST[$day_two . '_start_two']
                        : '';
                    $insertArr['endtime_two'] = isset($_POST[$day . '_end_two'])
                        ? $_POST[$day . '_end_two']
                        : '';
                    $insertArr['created_on'] = date('Y-m-d H:i:s');
                    $insertArr['created_by'] = $this->session->userdata(
                        'st_userid'
                    );
                } else {
                    $insertArr = [];
                    $insertArr['user_id'] = $eid;
                    $insertArr['days'] = $day;
                    $insertArr['type'] = 'close';
                    $insertArr['starttime'] = '';
                    $insertArr['endtime'] = '';
                    $insertArr['starttime_two'] = '';
                    $insertArr['endtime_two'] = '';
                    $insertArr['created_on'] = date('Y-m-d H:i:s');
                    $insertArr['created_by'] = $this->session->userdata(
                        'st_userid'
                    );
                }
                $this->user->insert('st_availability', $insertArr);
            } else {
                if (in_array($day, $postdays)) {
                    $updateArr = [];
                    $updateArr['user_id'] = $eid;
                    $updateArr['days'] = $day;
                    $updateArr['type'] = 'open';
                    $updateArr['starttime'] = $_POST[$day . '_start'];
                    $updateArr['endtime'] = $_POST[$day . '_end'];
                    $updateArr['starttime_two'] = isset(
                        $_POST[$day . '_start_two']
                    )
                        ? $_POST[$day . '_start_two']
                        : '';
                    $updateArr['endtime_two'] = isset($_POST[$day . '_end_two'])
                        ? $_POST[$day . '_end_two']
                        : '';

                    //$updateArr['created_on']=date('Y-m-d H:i:s');
                    //$updateArr['created_by']=$this->session->userdata('st_userid');
                } else {
                    $updateArr = [];
                    $updateArr['user_id'] = $eid;
                    $updateArr['days'] = $day;
                    $updateArr['type'] = 'close';
                    $updateArr['starttime'] = '';
                    $updateArr['endtime'] = '';
                    $updateArr['starttime_two'] = '';
                    $updateArr['endtime_two'] = '';
                    // $updateArr['created_on']=date('Y-m-d H:i:s');
                    //$updateArr['created_by']=$this->session->userdata('st_userid');
                }

                $this->user->update('st_availability', $updateArr, [
                    'user_id' => $eid,
                    'days' => $day,
                ]);
            }
            // $i++;
        }

        if ($this->user->update('st_users', $insertArrdata, ['id' => $eid])) {
            //$this->session->set_flashdata('success','Employee Updated successfully.');
            //redirect(base_url('merchant/employee_listing'));
            echo json_encode([
                'success' => 1,
                'msg' => 'Employee Updated successfully.',
                'img' => $empimgs,
                'name' => $first_name . ' ' . $last_name,
                'emp' => $eid,
            ]);
        } else {
            echo json_encode([
                'success' => 0,
                'msg' => 'There is some technical error.',
            ]);
            //$this->session->set_flashdata('error','There is some technical error.');
            //redirect(base_url('merchant/dashboard_addemployee'));
        }
    }

    public function get_bookingnote()
    {
        $bid = url_decode($_POST['bid']);
        $result = $this->user->select_row('st_booking', 'notes', [
            'id' => $bid,
        ]);

        echo json_encode([
            'success' => 1,
            'note' => !empty($result->notes) ? $result->notes : '',
        ]);
    }

    public function get_clientusernote()
    {
        if ($_POST['nid']) {
            $nid = url_decode($_POST['nid']);
            $mid = $this->session->userdata('st_userid');
            $result = $this->user->select_row('st_usernotes', 'notes', [
                'id' => $nid,
            ]);
            echo json_encode([
                'success' => 1,
                'note' => !empty($result->notes) ? $result->notes : '',
            ]);
        }
        else {
            $bid = url_decode($_POST['bid']);
            $result = $this->user->select_row('st_booking', 'walkin_customer_notes', [
                'id' => $bid,
            ]);
            echo json_encode([
                'success' => 1,
                'note' => !empty($result->walkin_customer_notes) ? $result->walkin_customer_notes : '',
            ]);
        }
    }

    function delete_clientusernote()
    {
        $nid = url_decode($_POST['nid']);
        //$this->db->delete('st_usernotes', array('user_id' => $nid));
        $data = ['notes' => ''];
        $this->db->where('user_id', $nid);
        $this->db->update('st_usernotes', $data);

        echo json_encode(['success' => 1, 'note' => '']);
    }

    public function update_bookingnote()
    {
        $bid = url_decode($_POST['bid']);
        $bnote = $_POST['bnote'];
        //$this->db->delete('st_usernotes', array('user_id' => $nid));
        $data = ['notes' => $bnote];
        $this->db->where('id', $bid);
        $this->db->update('st_booking', $data);

        echo json_encode(['success' => 1, 'note' => '']);
    }

    public function get_singleemployee($id = '')
    {
        $this->data['Empdetail'] = [];
        if ($id != '') {
            $field =
                'id,first_name,last_name,address,email,calender_color,mobile,profile_pic,online_booking,commission';
            $this->data['Empdetail'] = $this->user->select_row(
                'st_users',
                $field,
                ['id' => url_decode($id)]
            );

            $selctServis =
                'st_service_employee_relation.service_id,st_merchant_category.name,st_merchant_category.id,st_merchant_category.duration,st_merchant_category.price,`st_category`.`category_name`,`st_merchant_category`.`subcategory_id`';

            $where = [
                'st_service_employee_relation.user_id' => url_decode($id),
            ];

            $this->data['services'] = $this->user->join_three_orderby(
                'st_service_employee_relation',
                'st_merchant_category',
                'st_category',
                'service_id',
                'id',
                'subcategory_id',
                'id',
                $where,
                $selctServis
            );
            $this->data['user_available'] = $this->user->select(
                'st_availability',
                'days,type,starttime,endtime,starttime_two,endtime_two',
                ['user_id' => url_decode($id)],
                '',
                'id',
                'ASC'
            );
        } else {
            $this->data['selectedcolor'] = $this->user->select(
                'st_users',
                'id,calender_color',
                ['merchant_id' => $this->session->userdata('st_userid')]
            );
        }
        $this->data['merchant_id'] = $this->session->userdata('st_userid');
        //echo $this->db->last_query()."<pre>"; print_r($this->data); die;
        $this->data['merchant_available'] = $this->user->select(
            'st_availability',
            'days,type,starttime,endtime',
            ['user_id' => $this->session->userdata('st_userid')],
            '',
            'id',
            'ASC'
        );
        //echo '<pre>'; print_r($this->data); die;
        $html = $this->load->view(
            'frontend/marchant/employee_popup_report',
            $this->data,
            true
        );

        echo json_encode(['success' => 1, 'html' => $html]);
    }
}
